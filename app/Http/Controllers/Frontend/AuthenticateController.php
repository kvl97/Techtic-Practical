<?php
namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use DB, Hash, Mail, Validator,Session,Excel,Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use App\Models\Customers,App\Models\EmailTemplate,App\Models\GeoPoint,App\Models\SystemNameDef,App\Models\CustomerAddresses,App\Models\GeoCities,App\Models\Reservations;


class AuthenticateController extends BaseController {

    public function index(Request $request) {
        if(auth()->guard('customers')->check()) {
            return redirect(FRONTEND_URL);
        }
        return view('frontend.authenticate.login', array('title' => 'Login'));
    }

    public function loginValidate(Request $request) {
        $data = $request->all();
        
        if ($data) {
           
            $remember = (isset($data['remember'])) ? true : false;
            if ($remember) {
                Session::put('remember', 'yes');
            }

            if(!array_key_exists('v_email', $data) || !array_key_exists('password', $data)) {
                return response()->json([
                    'status' => 'FALSE',
                    'message' => ERR_PWS,
                ]);
            }
            $CustomerData = Auth::guard('customers')->attempt(['v_email' => $data['v_email'], 'password' => $data['password']], $remember);
            if (!empty($CustomerData)) {
                auth()->guard('admin')->logout();
                
                return response()->json([
                    'status' => 'TRUE',
                    'redirect_url' => FRONTEND_URL,
                ]);

            }
        }

        return response()->json([
            'status' => 'FALSE',
            'message' => ERR_PWS,
        ]);
    }

    public function logout() {
        auth()->guard('customers')->logout();
        Session::flash('msg', 'You are successfully logged out.');
        return redirect(FRONTEND_URL.'login');
    }


    public function myProfile(Request $request) {
        $inputs = $request->all();
        $user = auth()->guard('customers')->user();
        $today = date("Y-m-d", strtotime(date('Y-m-d')));

        $address_record = CustomerAddresses::where('i_customer_id', $user->id)->get()->toArray();
       
        $upcoming_reservation_data = Reservations::with(['GeoOriginServiceArea', 'GeoDestServiceArea', 'Admin', 'Customers', 'SystemResCategory'])->where('i_customer_id',$user['id'])->where('d_travel_date','>=', $today)->get()->toArray();

        $past_reservation_data = Reservations::with(['GeoOriginServiceArea', 'GeoDestServiceArea', 'Admin', 'Customers', 'SystemResCategory'])->where('i_customer_id',$user['id'])->where('d_travel_date','<', $today)->get()->toArray();
     
        if($inputs) {
            $id = $user->id;
            $validator = Validator::make($request->all(), [
                "v_firstname" => 'required',
                "v_lastname" => 'required',
                "v_email" =>'required|unique:customers,v_email,' . $id . '',
                "v_phone" => 'required',
            ]);
            $attributeNames = [
                "v_firstname" => 'first name',
                "v_lastname" => 'last name',
                "v_email" => 'email id',
                "v_phone" => 'phone number',
            ];
            $validator->setAttributeNames($attributeNames);
            if ($validator->fails()) {
                return json_encode([$validator->errors()]);
            } else {
               
                $user->v_email = trim($inputs['v_email']);
                $user->v_firstname = trim($inputs['v_firstname']);
                $user->v_lastname = trim($inputs['v_lastname']);
    			if($inputs['password'] != "" && $inputs['cpassword'] != "" && $inputs['password'] == $inputs['cpassword']) {
    				$user->password = Hash::make($inputs['password']);
                    $user->remember_token = '';
                }
                $user->v_phone = trim($inputs['v_phone']);
                $user->v_landline_number = trim($inputs['v_landline_number']);
                $user->d_dob = $inputs['d_dob'] ? date(SAVE_DATE_FORMAT,strtotime($inputs['d_dob'])) : NULL ;
                $user->e_gender = $inputs['gender'] ? $inputs['gender'] : '';
    			if($user->save()){
                    Session::flash('success-message', 'Profile info saved successfully.');
                    return response()->json([
                        'status' => 'TRUE',
                        'redirect_url' => FRONTEND_URL.'my-profile',
                    ]);
                }
            }
        }
        return view('frontend.authenticate.myprofile', array('title' => 'My Profile','user' => $user,'upcoming_reservation_data'=>$upcoming_reservation_data,'past_reservation_data'=>$past_reservation_data, 'address_record' => $address_record));
    }

    
}

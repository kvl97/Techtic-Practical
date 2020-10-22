<?php
namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use DB, Hash, Mail, Validator,Session , Excel,Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use App\Models\Admin,App\Models\EmailTemplate,App\Models\Reservations;


class AuthenticateController extends BaseController {

    public function index(Request $request) {
        return view('backend.authenticate.login', array('title' => 'Login'));
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

            $adminData = Auth::guard('admin')->attempt(['v_email' => $data['v_email'], 'password' => $data['password']], $remember);

            if (!empty($adminData)) {
                auth()->guard('customers')->logout();
                
                if(Auth::guard('admin')->user()->i_role_id == 6){
                    return response()->json([
                        'status' => 'TRUE',
                        'redirect_url' => ADMIN_URL.'rocket-manifest'
                    ]);
                } else{
                    return response()->json([
                        'status' => 'TRUE',
                        'redirect_url' => ADMIN_URL.'dashboard'
                    ]);
                }
                

            }
        }

        return response()->json([
            'status' => 'FALSE',
            'message' => ERR_PWS,
        ]);
    }

    public function logout() {
        auth()->guard('admin')->logout();
        Session::forget('reservation_rec1');
        Session::forget('reservation_rec2');
        Session::forget('DetailFareQuote');
        Session::flash('success-message', 'You are successfully logged out.');
        return redirect(ADMIN_URL.'login');
    }

    public function forgot_password(Request $request)
    {
        $data = $request->all();
        if ($data && array_key_exists('email', $data)) {
            $adminData = Admin::where('v_email', '=', e(trim($data['email'])))->first();

            if(empty($adminData) || $adminData == '') {
                Session::flash('message',INVALID_EMAIL); // Message of invalid email
                Session::put('FORGOTPASS_FLAG', '1');
                return ['status' => 'FALSE','message' => 'Email not registered with us'];
            } else {

                $v_access_code= Str::random(64);
                $adminData->remember_token = $v_access_code; // random access_code

                if ($adminData->save()) {
                    $objEmailTemplate = EmailTemplate::find(1)->toArray();
                    $strTemplate = $objEmailTemplate['t_email_content'];
                    $strTemplate = str_replace('[SITE_NAME]',SITE_NAME,$strTemplate);
                    $strTemplate = str_replace('[SITE_URL]',SITE_URL,$strTemplate);
                    $strTemplate = str_replace('../',SITE_URL,$strTemplate);
                    $strTemplate = str_replace('[LINK]',ADMIN_URL.'reset-password/'.$v_access_code,$strTemplate);
                    $strTemplate = str_replace('[USERNAME]',$adminData->v_firstname." ".$adminData->v_lastname, $strTemplate);

                    // mail sent to user with new link
                    Mail::send('emails.auth.generate-email-template', array('strTemplate'=>$strTemplate), function($message) use ($adminData)
                    {
                      $message->from(CONTACT_EMAIL_ID,SITE_NAME);
                      $message->to($adminData->v_email);
                      $message->replyTo(CONTACT_EMAIL_ID);
                      $message->subject('Forgot Password');
                    });
                    Session::flash('msg',PWD_SENT);
                    Session::remove('FORGOTPASS_FLAG');
                    return ['status' => 'TRUE'];
                }
            }
        }
        exit;
    }

    public function reset_password($code,Request $request)
	{
        $records = Admin::where('remember_token' , '=' , $code)->first();
        if($records['remember_token'] == ''){
            return redirect(ADMIN_URL);
        }
        $id = $records['id'];
        $rec = Admin::find($id);
        if($request->all()) {

            $inputs = $request->all();
            if($inputs['password'] != "" && $inputs['confirm_password'] != "" && $inputs['password'] == $inputs['confirm_password']) {
                $rec->password = Hash::make($inputs['password']);
                $rec->remember_token = '';
                $rec->save();
                Session::flash('success-message', PASSWORD_SUCCESS);
                Session::remove('FORGOTPASS_FLAG');
                return ['status' => 'TRUE','redirect_url' => ADMIN_URL.'login'];
            } else{
                Session::flash('message', 'Invalid Password');
                return ['status' => 'FALSE','message' => 'Passwords do not match'];
            }
		}
        return View('backend.authenticate.reset_password')->with('record' , $records)->with('title' , 'Reset Password');
	}

    public function dashboard() {
        if(Auth::guard('admin')->user()->i_role_id == 6){
            return redirect(ADMIN_URL.'rocket-manifest');
        }
        $today = date("Y-m-d", strtotime(date('Y-m-d')));
        $refund_requested_data= Reservations::where(['e_reservation_status'=>'Refund Requested'])->orderBy('updated_at', 'desc')->get()->toArray();

        $private_bookings_request_data = Reservations::where(['e_shuttle_type'=>'Private','e_reservation_status'=>'Requested'])->with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory','PickupCity','DropOffCity'])->where('d_travel_date','>=', $today)->whereNotIn('e_reservation_status',['Quote','Cancelled','Refund Requested','Refunded'])->orderBy('updated_at', 'desc');

        $private_bookings_request_data = $private_bookings_request_data->whereNotIn('id',function($q){
            $q->select('id')->from('reservations')->where('e_shuttle_type','Private')->whereIn('e_reservation_status',['Requested','Request Confirmed'])->whereNotNull('i_parent_id');
        });

        $private_bookings_request_data = $private_bookings_request_data->get()->toArray();

        $reservation_chart_data = Reservations::distinct('d_travel_date')->get()->toArray();        
        
        $record = array();
        $res_data = array();
        foreach ($reservation_chart_data as $key => $value) {
            $record[]= date("F", strtotime($value['d_travel_date']));
        }
        
        $res_data['xAxis'] = json_encode(array_unique($record));
        $res_data['yAxis'] = json_encode(array_count_values($record));
        
        return view('backend.authenticate.dashboard', array('title' => 'Dashboard','refund_requested_data'=>$refund_requested_data,'private_bookings_request_data'=>$private_bookings_request_data, 'chart_record' => $res_data));
    }

    public function registerChartData(Request $request) {
        $data = $request->all();
        /* $reservation_chart_data = Reservations::select(DB::raw("d_travel_date, count(id) AS totalReservation, sum(d_total_fare) AS totalFare,count(e_shuttle_type) as SharedShuttle where`e_shuttle_type` = 'Shared' "))->groupBy('d_travel_date');
 */
        $reservation_chart_data = Reservations::select('d_travel_date',DB::raw("count(id) AS totalReservation"), DB::raw("sum(d_total_fare) AS totalFare"),DB::raw("(select count(e_shuttle_type) from reservations as r WHERE `e_shuttle_type` = 'Shared' and r.d_travel_date = reservations.d_travel_date and r.deleted_at is null) as sharedCount"),DB::raw("(select count(e_shuttle_type) from reservations as r WHERE `e_shuttle_type` = 'Private' and r.d_travel_date = reservations.d_travel_date and r.deleted_at is null) as privateCount"))->where('e_reservation_status', '!=', 'Quote')->groupBy('d_travel_date');
        
        
        if(isset($data['start']) && $data['start']!=""){
            $d_date_from = date('Y-m-d', strtotime($data['start']));
            $reservation_chart_data = $reservation_chart_data->where(DB::raw("DATE(reservations.d_travel_date)"), '>=', $d_date_from);
        } 
        if(isset($data['end']) && $data['end']!=""){
            $d_date_to = date('Y-m-d', strtotime($data['end']));
            $reservation_chart_data = $reservation_chart_data->where(DB::raw("DATE(reservations.d_travel_date)"), '<=', $d_date_to);
        } 

        $rec = array();
        $rec['records'] = $reservation_chart_data->get()->toArray();
       /*  pr($rec['records']);
        exit; */
        $records['start'] = date('Y-m-d', strtotime($data['start']));
        $records['end'] = date('Y-m-d', strtotime($data['end']));
        
        $data = ['records' => [], 'reservations'=> []];
        // pr($rec['records']); exit;
        foreach($rec['records'] as $key => $val) {
            $data['records'][$key]['date'] = date(DATE_FORMAT,strtotime($val['d_travel_date']));;
            $data['records'][$key]['total_reservation'] = $val['totalReservation'];
            $data['records'][$key]['total_fare'] = (int)$val['totalFare'];
            $data['records'][$key]['shared'] = $val['sharedCount'];
            $data['records'][$key]['private'] = $val['privateCount'];
        }
       
        $data['reservations']['totalReservation'] = 'Total Reservation';
        $data['reservations']['totalFare'] = 'Total Fare';
        
        $data['records'] = array_values($data['records']);
        $data['reservations'] = array_values($data['reservations']);
      /*   pr($data);
        exit; */
        return response()->json($data);
    }

    public function myProfile(Request $request) {
        $inputs = $request->all();
        $user = auth()->guard('admin')->user();
        if($inputs) {
            $id = $user->id;
            $validator = Validator::make($inputs, array("v_email" =>'unique:admin,v_email,' . $id . ''));
            if ($validator->fails()) {
                return $validator->errors();
            } else {
                $user->v_email = trim($inputs['v_email']);
                $user->v_firstname = trim($inputs['v_firstname']);
                $user->v_lastname = trim($inputs['v_lastname']);
    			if($inputs['password'] != "" && $inputs['cpassword'] != "" && $inputs['password'] == $inputs['cpassword']) {
    				$user->password = Hash::make($inputs['password']);
                    $user->remember_token = '';
    			}

                if (isset($inputs['imgbase64']) && $inputs['imgbase64'] != '' && $inputs['is_edit_img_flag'] == '1') {
                    $profileImgPath = ADMIN_USER_PROFILE_IMG_PATH;
                    $profileImgThumbPath = ADMIN_USER_PROFILE_IMG_PATH.'thumb/';

                    $imageName = $this->cropImages($inputs['imgbase64'], $inputs['x'], $inputs['y'], $inputs['h'], $inputs['w'], $profileImgPath, $profileImgThumbPath);
                    @unlink( $profileImgThumbPath . $user->v_profile_image);
                    @unlink($profileImgPath . ($user->v_profile_image));

                    $user->v_profile_image = $imageName;
                } elseif($inputs['imgbase64'] == '' && $user->v_profile_image != '') {
                    @unlink(ADMIN_USER_PROFILE_THUMB_IMG_PATH . $user->v_profile_image);
                    @unlink(ADMIN_USER_PROFILE_IMG_PATH . ($user->v_profile_image));
                    $user->v_profile_image = '';
                } else {
                    $user->v_profile_image = $user->v_profile_image;
                }
    			if($user->save()){
                    Session::flash('success-message', 'Profile info saved successfully.');
                    return '';
                }
            }
        }
        return view('backend.authenticate.myprofile', array('title' => 'My Profile','user' => $user));
    }
 }

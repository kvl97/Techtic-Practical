<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customers;
use App\Models\Reservations;
use App\Models\GeoPoint;
use App\Models\SystemLuggageDef;
use App\Models\LineRun;
use App\Models\SystemIcaoDef;
use App\Models\SystemResCategory;
use App\Models\ReservationTravellerInfo;
use App\Models\ReservationLuggageInfo;
use App\Models\Offers;
use App\Models\FareTable;
use App\Models\FareClass;
use App\Models\GeoCities;
use App\Models\ReservationLeg;
use App\Models\Transactions;
use App\Models\EmailTemplate;
use App\Models\SystemSettings;
use App\Models\BlackoutDate;
use Carbon\Carbon;
use Mail, Session, Redirect, Validator, DB, Hash, PDF,Response,Str, Auth;

class CustomerReservationController extends BaseController {

    public function backendAddReservation() {
        Session::forget('reservation_rec1');
        Session::forget('reservation_rec2');
        Session::forget('DetailFareQuote');
        auth()->guard('customers')->logout();
        return redirect(FRONTEND_URL.'book-a-shuttle');
    }

    public function postContinueProcess(Request $request) {
        $inputs = $request->all();
        if(isset($inputs['incomplete_id']) && $inputs['incomplete_id'] != '') {
            if(isset($inputs['process']) && $inputs['process'] == 'Continue') {
                return response()->json(['status' => 'TRUE', 'redirect_url' => FRONTEND_URL.'book-a-shuttle/'.$inputs['incomplete_id']]);
            } else if(isset($inputs['process']) && $inputs['process'] == 'Stop') {
                $reservation_record = Reservations::find($inputs['incomplete_id']);
                $reservation_record_rt = Reservations::where('i_parent_id', $inputs['incomplete_id'])->first();
                if(!empty($reservation_record)) {
                    ReservationTravellerInfo::where('i_reservation_id', $reservation_record->id)->forceDelete();
                    ReservationLuggageInfo::where('i_reservation_id', $reservation_record->id)->forceDelete();
                    ReservationLeg::where('i_reservation_id',$reservation_record->id)->forceDelete();
                    Reservations::where('id',$reservation_record->id)->forceDelete();
                }             
                $newReservation = new Reservations;
                if(auth()->guard('customers')->check()) {
                    $newReservation->v_contact_name = ucwords(auth()->guard('customers')->user()->v_firstname.' '.auth()->guard('customers')->user()->v_lastname);
                    $newReservation->v_contact_phone_number = auth()->guard('customers')->user()->v_phone;
                    $newReservation->v_contact_email = auth()->guard('customers')->user()->email;
                    $newReservation->i_customer_id = auth()->guard('customers')->user()->id;
                }
                $newReservation->save();
                Session::put('reservation_rec1', $newReservation->id);
                if(!empty($reservation_record_rt)) {
                    ReservationTravellerInfo::where('i_reservation_id', $reservation_record_rt->id)->forceDelete();
                    ReservationLuggageInfo::where('i_reservation_id', $reservation_record_rt->id)->forceDelete();
                    ReservationLeg::where('i_reservation_id',$reservation_record_rt->id)->forceDelete();
                    Reservations::where('id',$reservation_record_rt->id)->forceDelete();
                    Session::forget('reservation_rec2');
                }               
                Session::forget('location_info');
                return response()->json(['status' => 'TRUE', 'redirect_url' => FRONTEND_URL.'book-a-shuttle']);
            } else {
                return response()->json(['status' => 'FALSE']);
            }
        } else {
            return response()->json(['status' => 'FALSE']);
        }
    }

    public function LocationInfo(Request $request, $id = ''){
        $inputs = $request->all();
        $customer_info = $location_info = '';
        if($id != '') {
            Session::forget('reservation_rec1');
            Session::forget('reservation_rec2');
            $reservation_record = Reservations::find($id);
            $reservation_record_rt = Reservations::where('i_parent_id', $id)->first();
            if(Session::has('location_info_edit') && isset(Session::get('location_info_edit')[$id])) {
                $location_info = Session::get('location_info_edit')[$id];
            } else {
                $location_info = $this->setEditLocationInfo($reservation_record, $reservation_record_rt);
            }
        } else {
            $reservation_rec1 = Session::get('reservation_rec1');
            $reservation_rec2 = Session::get('reservation_rec2');
            $reservation_record = Reservations::find($reservation_rec1);
            $reservation_record_rt = Reservations::find($reservation_rec2);
            $customer_info = auth()->guard('customers')->user();
            if(Session::has('DetailFareQuote')) {
                $location_info = Session::get('DetailFareQuote');
            } else {
                $location_info = $this->setEditLocationInfo($reservation_record, $reservation_record_rt, 'Add');
            }
        }
        
        $paymentStatus = $this->getPaymentStatus($reservation_record, $reservation_record_rt);
        
        if(!empty($inputs)) {
            $rules = array(
                'v_name' => 'required',
                'v_email' => 'required',
                'v_phone' => 'required',
                'e_class_type' => 'required',
                'e_shuttle_type' => 'required',
                'd_depart_date' => 'required',
                'home_pickup_location' => 'required',
                'home_dropoff_location' => 'required',
            );
            
            if($inputs['e_class_type'] == 'RT') {
                $rules = $rules + array(
                    'd_return_date' => 'required',
                    'home_pickup_location_rt' => 'required',
                    'home_dropoff_location_rt' => 'required',
                );
            }

            $attributeNames = [
                'v_name' => 'Name',
                'v_phone' => 'Contact Number',
                'v_email' => 'Email',
                'd_depart_date' => 'Date of Travel',
                'd_return_date' => 'Date of Travel',
                'home_pickup_location' => 'Pickup Location',
                'home_dropoff_location' => 'Drop Off Location',
                'home_pickup_location_rt' => 'Pickup Location',
                'home_dropoff_location_rt' => 'Drop Off Location',
            ];

            $validator = Validator::make($inputs, $rules);
            $validator->setAttributeNames($attributeNames);

            $redirect_url_slug = 'display-line-runs';
            if ($validator->fails()) {
                return json_encode($validator->errors());
            } else {
                
                if($id == '') {
                    $customer = Customers::where("v_email", $inputs['v_email'])->first();
                    $arr = explode(' ',trim($inputs['v_name']));
                    if(empty($customer)) {
                        $customer = new Customers;
                        $customer->v_firstname = isset($arr[0]) ? trim($arr[0]) : '';
                        $customer->v_lastname = isset($arr[1]) ? trim($arr[1]) : '';
                        $customer->v_email = trim($inputs['v_email']);
                        $customer->v_phone = trim($inputs['v_phone']);
                        $temp_customer_password = Str::random(10);
                        $customer->password = Hash::make($temp_customer_password);
                        $customer->e_status = "Active";
                        $customer->e_user_type = "Guest";
                        $customer->created_at = Carbon::now();
                        $customer->save();
                    }

                    if(auth()->guard('customers')->check()) {
                        $customer_id = auth()->guard('customers')->user()->id;
                    } else {
                        $customer_id = $customer->id;
                    }
                    $inputs['customer_id'] = $customer_id;
                }

                // code to manage private shuttle booking is here
                if($inputs['e_shuttle_type'] == 'Private') {
                    $redirect_url_slug = 'passenger-information';
                    if(empty($reservation_record)) {
                        $reservation_record = new Reservations;
                        if(auth()->guard('admin')->check()) {
                            $reservation_record->added_by_id = auth()->guard('admin')->user()->id;
                            $reservation_record->added_by_type_id = auth()->guard('admin')->user()->i_role_id;
                        }
                        $reservation_record->i_customer_id = $customer_id;
                    }
                                           
                    $reservation_record->v_contact_name = ucwords(trim($inputs['v_name']));
                    $reservation_record->v_contact_phone_number = trim($inputs['v_phone']);
                    $reservation_record->v_contact_email = trim($inputs['v_email']);
                    $reservation_record->e_shuttle_type = $inputs['e_shuttle_type'];
                    $reservation_record->i_total_num_passengers = $inputs['peoples'];
                    $reservation_record->e_class_type = ($reservation_record['i_parent_id'] != NULL) ? 'RT' : $inputs['e_class_type'];
                    $reservation_record->d_travel_date = date('Y-m-d', strtotime(trim($inputs['d_depart_date'])));
                    $reservation_record->i_pickup_city_id = $inputs['home_pickup_location'];
                    $reservation_record->i_dropoff_city_id = $inputs['home_dropoff_location'];
                    
                    if($reservation_record->save()){
                        $reservation_record->v_reservation_number = reservationNumber($reservation_record->id);
                        $reservation_record->save();
                        ReservationLeg::where('i_reservation_id',$reservation_record->id)->delete();
                        if($id == '') {
                            Session::put('reservation_rec1',$reservation_record->id);
                        }           
                    }                    
                    if($inputs['e_class_type'] == 'OW' && !empty($reservation_record_rt)) {
                        Reservations::where('id',$reservation_record_rt->id)->delete();
                        ReservationTravellerInfo::where('i_reservation_id', $reservation_record_rt->id)->delete();
                        ReservationLuggageInfo::where('i_reservation_id', $reservation_record_rt->id)->delete();
                        ReservationLeg::where('i_reservation_id',$reservation_record_rt->id)->delete();
                        Session::forget('reservation_rec2');
                    } elseif($inputs['e_class_type'] == 'RT') {
                        if(empty($reservation_record_rt)) {
                            $reservation_record_rt = new Reservations;
                            if(auth()->guard('admin')->check()) {
                                $reservation_record_rt->added_by_id = auth()->guard('admin')->user()->id;
                                $reservation_record_rt->added_by_type_id = auth()->guard('admin')->user()->i_role_id;
                            }
                            $reservation_record_rt->i_customer_id = $reservation_record->i_customer_id;
                        }                        
                        $reservation_record_rt->v_contact_name =  ucwords($reservation_record->v_contact_name);
                        $reservation_record_rt->v_contact_phone_number = $reservation_record->v_contact_phone_number;
                        $reservation_record_rt->v_contact_email = $reservation_record->v_contact_email;
                        $reservation_record_rt->i_pickup_city_id = $inputs['home_pickup_location_rt'];
                        $reservation_record_rt->i_dropoff_city_id = $inputs['home_dropoff_location_rt'];
                        $reservation_record_rt->i_parent_id = $reservation_record->id;
                        $reservation_record_rt->e_shuttle_type = $inputs['e_shuttle_type'];
                        $reservation_record_rt->i_total_num_passengers = $inputs['peoples'];
                        $reservation_record_rt->e_class_type = $inputs['e_class_type'];
                        $reservation_record_rt->d_travel_date = date('Y-m-d', strtotime(trim($inputs['d_return_date'])));
                        if($reservation_record_rt->save()) {
                            $reservation_record_rt->v_reservation_number = reservationNumber($reservation_record_rt->id);
                            $reservation_record_rt->save();
                            ReservationLeg::where('i_reservation_id',$reservation_record_rt->id)->delete();
                            if($id == '') {
                                Session::put('reservation_rec2',$reservation_record_rt->id);
                            }
                        }
                    }
                } else {
                    //Set session for second step redirect
                    if($id != '') {
                        $this->setLocationInfo($inputs, $id);
                    } else {
                        $this->setLocationInfo($inputs);
                    }
                }                
                
                if((!auth()->guard('customers')->check()) && (!auth()->guard('admin')->check())) {
                    //Show Popup to login
                    if(isset($customer) && $customer->e_user_type != 'Guest') {
                        return response()->json([
                            'status' => 'LOGIN_ACCOUNT',
                            'email' => trim($inputs['v_email']),
                        ]);
                    } else {
                        return response()->json([
                            'status' => 'TRUE',
                            'redirect_url' => FRONTEND_URL.$redirect_url_slug.(($id != '') ? '/'.$id : ''),
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 'TRUE',
                        'redirect_url' => FRONTEND_URL.$redirect_url_slug.(($id != '') ? '/'.$id : ''),
                    ]);
                }
            }
        } else {
            $incompleteRecord = array();
            if(auth()->guard('customers')->check()) {
                $customer_id = auth()->guard('customers')->user()->id;
                $incompleteRecord = Reservations::with(['PickupCity','DropOffCity'])->where(['i_customer_id' => $customer_id, 'e_reservation_status' => 'Quote'])->select('id', 'i_pickup_city_id', 'i_dropoff_city_id', 'd_travel_date', 'e_class_type', 'e_shuttle_type')->whereNull('i_parent_id')->orderBy('id', 'desc')->first();
            } else if(auth()->guard('admin')->check()) {
                $admin_id = auth()->guard('admin')->user()->id;
                $incompleteRecord = Reservations::with(['PickupCity','DropOffCity'])->where(['added_by_id' => $admin_id, 'e_reservation_status' => 'Quote'])->select('id', 'i_pickup_city_id', 'i_dropoff_city_id', 'd_travel_date', 'e_class_type', 'e_shuttle_type')->whereNull('i_parent_id')->orderBy('id', 'desc')->first();
            }
            
            if(!empty($incompleteRecord) && empty($reservation_record)) {
                $incompleteRecord = $incompleteRecord->toArray();
            } else {
                $incompleteRecord = array();
            }
            
            if($paymentStatus) {
                $serviceIds = $this->getServiceID($location_info['home_pickup_location'], $location_info['home_dropoff_location']);
                $geo_point_location = GeoCities::where('i_service_area_id', $serviceIds['home_pickup_service_id'])->select('id','v_county','v_city','i_service_area_id','v_drop_off_city_cant_be','v_drop_off_city_must_be')->orderBy('i_order', 'ASC')->get()->toArray();
            } else {
                $geo_point_location = GeoCities::select('id','v_county','v_city','i_service_area_id','v_drop_off_city_cant_be','v_drop_off_city_must_be')->orderBy('i_order', 'ASC')->get()->toArray();
            }
          
            $arr_all_county = [];
            foreach($geo_point_location as $k => $v) {
                $arr_all_county[$v['v_county']][] = $v;
            }
             
            $lastSuggested = '';
            if(auth()->guard('customers')->check() && $location_info['home_pickup_location'] == '' && $location_info['home_dropoff_location'] == '') {
                $customer_id = auth()->guard('customers')->user()->id;
                $lastSuggested = Reservations::where(['i_customer_id' => $customer_id, 'e_reservation_status' => 'Booked'])->select('id', 'i_pickup_city_id', 'i_dropoff_city_id', 'd_travel_date', 'e_class_type', 'e_shuttle_type')->whereNull('i_parent_id')->orderBy('id', 'desc')->first();
                if(!empty($lastSuggested)) {
                    $location_info['home_pickup_location'] = $lastSuggested->i_pickup_city_id;
                    $location_info['home_dropoff_location'] = $lastSuggested->i_dropoff_city_id;
                    Session::put('DetailFareQuote', $location_info);
                }
            }        
           
            return view('frontend.customer_reservation.location-information', array('title' => 'Location Information', 'customer_info' => $customer_info,'location_info' => $location_info, 'reservation_record' => $reservation_record, 'reservation_record_rt' => $reservation_record_rt, 'arr_all_county' => $arr_all_county, 'incompleteRecord' => $incompleteRecord, 'paymentStatus' => $paymentStatus, 'lastSuggested' => $lastSuggested));        
        }
    }

    public function getPickupLocations(Request $request, $id = '') {
        $inputs = $request->all();
        if($id != '') {
            $reservation_record = Reservations::find($id);
            $reservation_record_rt = Reservations::where('i_parent_id', $id)->first();
            $location_info = Session::get('location_info_edit')[$id];
        } else {
            $reservation_rec1 = Session::get('reservation_rec1');
            $reservation_rec2 = Session::get('reservation_rec2');
            $reservation_record = Reservations::find($reservation_rec1);
            $reservation_record_rt = Reservations::find($reservation_rec2);
            $location_info = Session::get('DetailFareQuote');
        }
        
        if($inputs['shuttle_type'] == "Private") {
            $geo_point_location = GeoCities::select('id','v_county','v_city','i_service_area_id','v_drop_off_city_cant_be','v_drop_off_city_must_be')->orderBy('i_order', 'ASC')->get()->toArray();
        } else {
            $geo_point_location = GeoCities::whereIn('i_service_area_id',function($q){
                $q->select('i_origin_service_area_id')
                ->from(with(new FareTable)->getTable());
            })->select('id','v_county','v_city','i_service_area_id','v_drop_off_city_cant_be','v_drop_off_city_must_be')->orderBy('i_order', 'ASC')->where('e_only_private', '0')->get()->toArray();
        }

        $arr_country = [];
        foreach($geo_point_location as $k => $v) {
            $arr_country[$v['v_county']][] = $v;
        }
        return view('frontend.customer_reservation.dropoff-locations',['arr_country' => $arr_country, 'location_info' => $location_info, 'inputs' => $inputs]);
        
    }

    public function getDropoffLocations(Request $request, $id = '') {
        $inputs = $request->all();
        if($id != '') {
            $reservation_record = Reservations::find($id);
            $reservation_record_rt = Reservations::where('i_parent_id', $id)->first();
            $location_info = Session::get('location_info_edit')[$id];
        } else {
            $reservation_rec1 = Session::get('reservation_rec1');
            $reservation_rec2 = Session::get('reservation_rec2');
            $reservation_record = Reservations::find($reservation_rec1);
            $reservation_record_rt = Reservations::find($reservation_rec2);
            $location_info = Session::get("DetailFareQuote");
        }
        $arr_country = [];
        if(isset($inputs['origin_service_area_id'])) {
            if($inputs['tab'] == 'location_dropoff_rt'){
                
                $dropoff_location_rt = GeoCities::where('i_service_area_id',$inputs['origin_service_area_id'])->select('id','v_county','v_city','i_service_area_id','v_drop_off_city_cant_be','v_drop_off_city_must_be')->orderBy('i_order', 'ASC')->get()->toArray();
                foreach($dropoff_location_rt as $key => $val) {
                    $arr_country[$val['v_county']][] = $val;
                }
                
            } else if($inputs['tab'] == "location_pickup_rt"){
                $pickup_location_rt = GeoCities::where('i_service_area_id',$inputs['origin_service_area_id'])->select('id','v_county','v_city','i_service_area_id','v_drop_off_city_cant_be','v_drop_off_city_must_be')->orderBy('i_order', 'ASC')->get()->toArray();
                foreach($pickup_location_rt as $key => $val) {
                    $arr_country[$val['v_county']][] = $val;
                }
                
            } else if($inputs['tab'] == "select-lineruns-to-rt"){
              
                $pickup_location_rt = GeoCities::where('i_service_area_id',$inputs['origin_service_area_id'])->select('id','v_county','v_city','i_service_area_id','v_drop_off_city_cant_be','v_drop_off_city_must_be')->orderBy('i_order', 'ASC')->get()->toArray();
                foreach($pickup_location_rt as $key => $val) {
                    $arr_country[$val['v_county']][] = $val;
                }
            } else if($inputs['tab'] == "select-lineruns-from-rt"){
                $pickup_location_rt = GeoCities::where('i_service_area_id',$inputs['origin_service_area_id'])->select('id','v_county','v_city','i_service_area_id','v_drop_off_city_cant_be','v_drop_off_city_must_be')->orderBy('i_order', 'ASC')->get()->toArray();
                foreach($pickup_location_rt as $key => $val) {
                    $arr_country[$val['v_county']][] = $val;
                }                
            } else { 
                if(isset($inputs['shuttle_type']) && $inputs['shuttle_type'] == 'Private') {
                    $geo_point_location = GeoCities::select('id','v_county','v_city','i_service_area_id','v_drop_off_city_cant_be','v_drop_off_city_must_be')->orderBy('i_order', 'ASC');
                } else {
                    $geo_point_location = GeoCities::whereIn('i_service_area_id',function($q) use($inputs) {
                        $q->select('i_dest_service_area_id')
                        ->from(with(new FareTable)->getTable())->where('i_origin_service_area_id',$inputs['origin_service_area_id']);
                    })->select('id','v_county','v_city','i_service_area_id','v_drop_off_city_cant_be','v_drop_off_city_must_be')->orderBy('i_order', 'ASC')->where('e_only_private','0');
                }

                if(isset($inputs['city_id']) && $inputs['city_id']!='') {
                    $geo_point_location = $geo_point_location->where('id','<>',$inputs['city_id']);
                }

                $geo_point_location = $geo_point_location->get()->toArray();

                foreach($geo_point_location as $k => $v) {
                    $arr_country[$v['v_county']][] = $v;
                }
            }
        }
        
        return view('frontend.customer_reservation.dropoff-locations',['arr_country' => $arr_country, 'location_info' => $location_info, 'inputs' => $inputs]);
    }
   
    public function getPaymentStatus($resv, $resv_rt) {
        $status = true;
        if(!empty($resv)) {
            $paymentStatus = Transactions::where(['i_reservation_id' => $resv->id, 'e_status' => 'Success'])->whereIn('e_type', array('Booked', 'Cash-On-Board'))->first();
            if(empty($paymentStatus)) {
                $status = false;
            }
            if($resv['e_class_type'] == 'RT' && $resv['i_parent_id'] == NULL) {
                if(!empty($resv_rt)) {
                    $paymentStatusRT = Transactions::where(['i_reservation_id' => $resv_rt->id, 'e_status' => 'Success'])->whereIn('e_type', array('Booked', 'Cash-On-Board'))->first();
                    if(empty($paymentStatusRT)) {
                        $status = false;
                    }
                } else {
                    $status = false;
                }
            }
        } else {
            $status = false;
        }
        

        return $status;
    }

    protected function setEditLocationInfo($resv, $resv_rt, $type = 'Edit') {
        $location_info['v_name'] = $resv['v_contact_name'];
        $location_info['v_phone'] = $resv['v_contact_phone_number'];
        $location_info['v_email'] = $resv['v_contact_email'];
        $location_info['peoples'] = $resv['i_total_num_passengers'];
        $location_info['e_class_type'] = ($resv['i_parent_id'] != NULL) ? 'OW' : $resv['e_class_type'];   
        $location_info['e_shuttle_type'] = $resv['e_shuttle_type'];    
        $location_info['d_depart_date'] = $resv['d_travel_date'];    
        $location_info['home_pickup_location'] = $resv['i_pickup_city_id'];
        $location_info['home_dropoff_location'] = $resv['i_dropoff_city_id'];
        $location_info['d_return_date'] = $location_info['home_pickup_location_rt'] = $location_info['home_dropoff_location_rt'] = "";
        if($resv['e_class_type'] == 'RT' && (!empty($resv_rt))) {
            $location_info['d_return_date'] = $resv_rt['d_travel_date'];
            $location_info['home_pickup_location_rt'] = $resv_rt['i_pickup_city_id'];
            $location_info['home_dropoff_location_rt'] = $resv_rt['i_dropoff_city_id'];   
        }
        if($type == 'Add') {
            Session::put('DetailFareQuote',$location_info);
        } else {
            $record[$resv['id']] = $location_info;
            Session::put('location_info_edit',$record);
        }
        return $location_info;
        
    }
   
    protected function setLocationInfo($inputs, $id = '') {
        $location_info['v_name'] = $inputs['v_name'];
        $location_info['v_phone'] = $inputs['v_phone'];
        $location_info['v_email'] = $inputs['v_email'];
        $location_info['peoples'] = $inputs['peoples'];   
        $location_info['e_class_type'] = $inputs['e_class_type'];    
        $location_info['e_shuttle_type'] = $inputs['e_shuttle_type'];    
        $location_info['d_depart_date'] = $inputs['d_depart_date'];    
        $location_info['home_pickup_location'] = $inputs['home_pickup_location'];
        $location_info['home_dropoff_location'] = $inputs['home_dropoff_location'];
        if($inputs['e_class_type'] == 'RT'){
            $location_info['d_return_date'] = $inputs['d_return_date'];
            $location_info['home_pickup_location_rt'] = $inputs['home_pickup_location_rt'];
            $location_info['home_dropoff_location_rt'] = $inputs['home_dropoff_location_rt'];
        } else {
            $location_info['d_return_date'] = $location_info['home_pickup_location_rt'] = $location_info['home_dropoff_location_rt'] = "";
        }
        if(isset($inputs['customer_id'])) {
            $location_info['customer_id'] = $inputs['customer_id'];
        }
        if($id != '') {
            $record[$id] = $location_info;
            Session::put('location_info_edit',$record);            
        } else {
            Session::put('DetailFareQuote',$location_info);
        }        
    }

    public function SelectLineRun(Request $request, $id = '') {
        $inputs = $request->all();
        if($id != '') {
            $reservation_record = Reservations::find($id);
            $reservation_record_rt = Reservations::where('i_parent_id', $id)->first();
            if(Session::has('location_info_edit') && isset(Session::get('location_info_edit')[$id])) {
                $location_info = Session::get('location_info_edit')[$id];
            } else {
                $location_info = $this->setEditLocationInfo($reservation_record, $reservation_record_rt);
            }
        } else {
            $reservation_rec1 = Session::get('reservation_rec1');
            $reservation_rec2 = Session::get('reservation_rec2');
            $reservation_record = Reservations::find($reservation_rec1);
            $reservation_record_rt = Reservations::find($reservation_rec2);
            if(Session::has('DetailFareQuote')) {
                $location_info = Session::get('DetailFareQuote');
            } else {
                $location_info = $this->setEditLocationInfo($reservation_record, $reservation_record_rt, 'Add');
            }
        }  
        
        if(!empty($location_info)) {
            /* if($reservation_record['e_class_type'] == 'RT' && $reservation_record['i_parent_id'] == NULL && empty($reservation_record_rt)) {
                return redirect(FRONTEND_URL.'book-a-shuttle'.(($id != '') ? '/'.$id : ''));
            } */
            $paymentStatus = $this->getPaymentStatus($reservation_record, $reservation_record_rt);
            
            if(!empty($inputs)) { 
                
                $rules = array(
                    'd_depart_date' => 'required',
                    'home_pickup_location' => 'required',
                    'home_dropoff_location' => 'required',
                );
                
                if($inputs['e_class_type'] == 'RT') {
                    $rules = $rules + array(
                        'd_return_date' => 'required',
                        'home_pickup_location_rt' => 'required',
                        'home_dropoff_location_rt' => 'required',
                    );
                }
                $attributeNames = [
                    'd_depart_date' => 'Date of Travel',
                    'd_return_date' => 'Date of Travel',
                    'home_pickup_location' => 'Pickup Location',
                    'home_dropoff_location' => 'Drop Off Location',
                    'home_pickup_location_rt' => 'Pickup Location',
                    'home_dropoff_location_rt' => 'Drop Off Location',
                ];
                $validator = Validator::make($inputs, $rules);
                $validator->setAttributeNames($attributeNames);
                if ($validator->fails()) {
                    return json_encode($validator->errors());
                } else {
                    $idsExclude = array();
                    // Reservation Entry
                    if(empty($reservation_record)) {
                        $reservation_record = new Reservations;
                        if(auth()->guard('admin')->check()) {
                            $reservation_record->added_by_id = auth()->guard('admin')->user()->id;
                            $reservation_record->added_by_type_id = auth()->guard('admin')->user()->i_role_id;
                        }
                        $reservation_record->i_customer_id = $location_info['customer_id'];
                    }
                    $reservation_record->v_contact_name = ucwords($location_info['v_name']);
                    $reservation_record->v_contact_phone_number = $location_info['v_phone'];
                    $reservation_record->v_contact_email = $location_info['v_email'];
                    $reservation_record->e_shuttle_type = isset($inputs['e_shuttle_type']) ? $inputs['e_shuttle_type'] : 'Shared';
                    $reservation_record->i_total_num_passengers = $inputs['peoples'];
                    $reservation_record->e_class_type = ($reservation_record['i_parent_id'] != NULL) ? 'RT' : $inputs['e_class_type'];
                    $reservation_record->d_travel_date = date('Y-m-d', strtotime(trim($inputs['d_depart_date'])));
                    $reservation_record->i_pickup_city_id = $inputs['home_pickup_location'];
                    $reservation_record->i_dropoff_city_id = $inputs['home_dropoff_location'];
                    if($reservation_record->save()) {
                        $reservation_record->v_reservation_number = reservationNumber($reservation_record->id);
                        $reservation_record->save();
                        if($id == '') {
                            Session::put('reservation_rec1',$reservation_record->id);
                            $idsExclude[] = $reservation_record->id;
                        }
                        if($inputs['e_class_type'] == 'OW' && !empty($reservation_record_rt)) {
                            Reservations::where('id',$reservation_record_rt->id)->delete();
                            ReservationTravellerInfo::where('i_reservation_id', $reservation_record_rt->id)->delete();
                            ReservationLuggageInfo::where('i_reservation_id', $reservation_record_rt->id)->delete();
                            ReservationLeg::where('i_reservation_id',$reservation_record_rt->id)->delete();
                            Session::forget('reservation_rec2');
                        } else if($inputs['e_class_type'] == 'RT') {
                            if(empty($reservation_record_rt)) {
                                $reservation_record_rt = new Reservations;
                                $reservation_record_rt->i_customer_id = $reservation_record->i_customer_id;
                                $reservation_record_rt->v_contact_name =  ucwords($reservation_record->v_contact_name);
                                $reservation_record_rt->v_contact_phone_number = $reservation_record->v_contact_phone_number;
                                $reservation_record_rt->v_contact_email = $reservation_record->v_contact_email;
                                if(auth()->guard('admin')->check()) {
                                    $reservation_record->added_by_id = auth()->guard('admin')->user()->id;
                                    $reservation_record->added_by_type_id = auth()->guard('admin')->user()->i_role_id;
                                }
                            }
                            $reservation_record_rt->i_parent_id = $reservation_record->id;
                            $reservation_record_rt->e_shuttle_type =  isset($inputs['e_shuttle_type']) ? $inputs['e_shuttle_type'] : 'Shared';
                            $reservation_record_rt->i_total_num_passengers = $inputs['peoples'];
                            $reservation_record_rt->e_class_type = $inputs['e_class_type'];
                            $reservation_record_rt->d_travel_date = date('Y-m-d', strtotime(trim($inputs['d_return_date'])));
                            $reservation_record_rt->i_pickup_city_id = $inputs['home_pickup_location_rt'];
                            $reservation_record_rt->i_dropoff_city_id = $inputs['home_dropoff_location_rt'];
                            if($reservation_record_rt->save()) {
                                $reservation_record_rt->v_reservation_number = reservationNumber($reservation_record_rt->id);
                                $reservation_record_rt->save();
                                if($id == '') {
                                    Session::put('reservation_rec2',$reservation_record_rt->id);
                                    $idsExclude[] = $reservation_record_rt->id;
                                }
                            }
                        }
                    }

                    if($id != '') {
                       $edit_session = Session::get('location_info_edit');
                       unset($edit_session[$id]);
                       Session::put('location_info_edit', $edit_session);
                    } else {                        
                        //Remove reservation of customer in quote state except just added two
                        Reservations::where(['i_customer_id' => $reservation_record['i_customer_id'], 'e_reservation_status' => 'Quote'])->whereNotIn('id', $idsExclude)->delete();
                        Session::forget('DetailFareQuote');
                    }
                    return response()->json([
                        'status' => 'TRUE',
                        'redirect_url' => isset($inputs['redirect_url']) ? FRONTEND_URL.$inputs['redirect_url'] : FRONTEND_URL.'passenger-information'.(($id != '') ? '/'.$id : ''),
                    
                    ]); 
                }
            }
            $serviceIds = $this->getServiceID($location_info['home_pickup_location'], $location_info['home_dropoff_location']);
            if($paymentStatus) {
                $geo_point_location = GeoCities::where('i_service_area_id', $serviceIds['home_pickup_service_id'])->select('id','v_county','v_city','i_service_area_id','v_drop_off_city_cant_be','v_drop_off_city_must_be')->orderBy('i_order', 'ASC')->get()->toArray();
            } else {
                $geo_point_location = GeoCities::select('id','v_county','v_city','i_service_area_id','v_drop_off_city_cant_be','v_drop_off_city_must_be')->orderBy('i_order', 'ASC')->get()->toArray();
            }
          
            $arr_all_county = [];
            foreach($geo_point_location as $k => $v) {
                $arr_all_county[$v['v_county']][] = $v;
            }
            return view('frontend.customer_reservation.select-line-runs', array('title' => 'Select Line Runs','reservation_record' => $reservation_record, 'location_info' => $location_info, 'paymentStatus' => $paymentStatus, 'arr_all_county' => $arr_all_county, 'serviceIds' => $serviceIds));
        } else {
            return redirect(FRONTEND_URL.'book-a-shuttle'.(($id != '') ? '/'.$id : ''));
        }
    }

    public function PassengerInfo(Request $request, $id = '') {
        $inputs = $request->all();
       
        if($id != '') {
            $reservation_record = Reservations::find($id);
            $reservation_record_rt = Reservations::where('i_parent_id', $id)->first();
        } else {
            $reservation_rec1 = Session::get('reservation_rec1');
            $reservation_rec2 = Session::get('reservation_rec2');
            $reservation_record = Reservations::find($reservation_rec1);
            $reservation_record_rt = Reservations::find($reservation_rec2);
        }
        $fare_class_detail_ow = FareClass::where(['e_class_type' => 'OW', 'deleted_at' => NULL])->where('v_class_label', '!=', 'Companion')->get()->toArray();
                
        if(!empty($reservation_record)) {
            if($reservation_record['e_class_type'] == 'RT' && $reservation_record['i_parent_id'] == NULL && empty($reservation_record_rt)) {
                return redirect(FRONTEND_URL.'book-a-shuttle'.(($id != '') ? '/'.$id : ''));
            }
            $paymentStatus = $this->getPaymentStatus($reservation_record, $reservation_record_rt);
            if(!empty($inputs)) {
                
                if($reservation_record['e_class_type']=='OW' || ($reservation_record['e_class_type'] == 'RT' && $reservation_record['i_parent_id'] == NULL)) {
                    $total_travelllers = $num_ff_adults =  0;
                    $total_travelllers_rt = $num_ff_adults_rt =  0;
                    foreach($fare_class_detail_ow as $data) {
                        $type = ($data['v_class_label'] == 'Full Fare') ? 'adult' : strtolower($data['v_class_label']);
                        if(isset($inputs['v_'.$type.'_name']) && $inputs['v_'.$type.'_name'][0] != "") {
                            $total_travelllers += count(array_filter($inputs['v_'.$type.'_name']));
                        }

                        if($reservation_record['e_class_type'] == 'RT') {
                            if(isset($inputs['v_'.$type.'_return_name']) && $inputs['v_'.$type.'_return_name'][0] != "") {
                                $total_travelllers_rt += count(array_filter($inputs['v_'.$type.'_return_name']));
                            }
                        }                        
                    }
                    $serviceIds = $this->getServiceID($reservation_record->i_pickup_city_id, $reservation_record->i_dropoff_city_id);
                    
                    if($reservation_record['e_class_type'] == 'OW') {
                        $ff_rate_code = "FFOW";
                        $companion_rate_code = "CMOW";
                    } else {
                        $ff_rate_code = "FFRT";
                        $companion_rate_code = "CMRT";
                    }

                    $fare_calc = FareTable::where('i_origin_service_area_id', $serviceIds['home_pickup_service_id'])->where('i_dest_service_area_id', $serviceIds['home_dropoff_service_id'])->select('id', 'i_rate_class_id', 'v_rate_code', 'd_fare_amount')->get()->keyBy('v_rate_code')->toArray();
                    
                    ReservationTravellerInfo::where('i_reservation_id', $reservation_record->id)->delete();
                    if($reservation_record['e_class_type'] == 'RT') {
                        ReservationTravellerInfo::where('i_reservation_id', $reservation_record_rt->id)->delete();
                        
                        $fare_calc_rt = FareTable::where('i_origin_service_area_id', $serviceIds['home_dropoff_service_id'])->where('i_dest_service_area_id', $serviceIds['home_pickup_service_id'])->select('id', 'i_rate_class_id', 'v_rate_code', 'd_fare_amount')->get()->keyBy('v_rate_code')->toArray(); 
                    }
                    foreach($fare_class_detail_ow as $data) {
                        
                        $fare_rate = $fare_rate_code = 0;
                        $ff_infants_index = null;

                        $fareType = $data['v_class_label'];
                        $type = ($data['v_class_label'] == 'Full Fare') ? 'adult' : strtolower($data['v_class_label']);
                        
                        if(isset($inputs['v_'.$type.'_name']) && $inputs['v_'.$type.'_name'][0] != "") {
                            $fare_class = FareClass::where('e_class_type', $reservation_record['e_class_type'])->where('v_class_label', $fareType)->select('id', 'v_rate_code')->first()->toArray();

                            if(isset($fare_calc[$fare_class['v_rate_code']])) {
                                $fare_rate = $fare_calc[$fare_class['v_rate_code']]['d_fare_amount'];
                                $fare_rate_code = $fare_class['v_rate_code'];
                            }
                            
                            // Conditions for fare calculations
                            if($fareType == 'Full Fare'){
                                /* if($inputs['v_'.$type.'_name'][0] != "") { */
                                    $num_ff_adults += count($inputs['v_'.$type.'_name']);
                                /* } */
                                if(isset($fare_calc[$fare_class['v_rate_code']])) {
                                    $fare_rate = $fare_calc[$fare_class['v_rate_code']]['d_fare_amount'];
                                    $fare_rate_code = $fare_class['v_rate_code'];
                                }
                            }
                            //Senior or Military single traveller
                            if($fareType == 'Senior' || $fareType == 'Military'){
                                if($total_travelllers > 1) {
                                    /* if($inputs['v_'.$type.'_name'][0] !=  "") { */
                                        $num_ff_adults += count($inputs['v_'.$type.'_name']);
                                    /* } */
                                    if(isset($fare_calc[$ff_rate_code])) {
                                        $fare_rate = $fare_calc[$ff_rate_code]['d_fare_amount'];
                                        $fare_rate_code = $ff_rate_code;
                                    }
                                }
                            }

                            //Must be 1 adult/senior/military
                            if($fareType == 'Child') {
                                if($num_ff_adults == 0 && count($inputs['v_'.$type.'_name']) > 0) {
                                    if(isset($fare_calc[$ff_rate_code])) {
                                        $fare_rate = $fare_calc[$ff_rate_code]['d_fare_amount'];
                                        $fare_rate_code = $ff_rate_code;
                                    }
                                }
                            }

                            //1 adult 1 infant free , 2-2, 3-3, so on
                            if($fareType == 'Infant'){
                                if(count($inputs['v_'.$type.'_name']) > $num_ff_adults) {
                                    $ff_infants_index = count($inputs['v_'.$type.'_name']) - $num_ff_adults;
                                }
                            }
                            
                            // Divide passenger fare code 22 sep 2020
                            if($reservation_record['e_shuttle_type']=='Shared') {
                                if($reservation_record['e_class_type'] == 'RT'){
                                    $ow_fare_code = str_replace('RT','OW',$fare_rate_code); 
                                    $rt_fare_code =  $fare_rate_code;
    
                                    $rt_fare = $fare_calc[$rt_fare_code]['d_fare_amount'];
                                    $ow_fare = $fare_calc[$ow_fare_code]['d_fare_amount'];
                                } else {
                                    $ow_fare = $fare_rate;
                                }
                            } else {
                                $ow_fare = 0.00;
                            }
                            // End

                            $totalRecord = $type.'_total_details';
                            for($i = 0; $i <= $inputs[$totalRecord]; $i++) {
                                if($inputs['v_'.$type.'_name'][$i] != '') {
                                    //Companion	
                                    if($fareType == 'Full Fare' && $i > 0) {	
                                        $fare_rate_code = $companion_rate_code;	
                                        
                                            $ow_fare = $fare_calc[$companion_rate_code]['d_fare_amount'];	
                                            $ow_fare_code = str_replace('RT','OW',$fare_rate_code); 
                                            $rt_fare_code =  $fare_rate_code;
            
                                            $rt_fare = $fare_calc[$rt_fare_code]['d_fare_amount'];
                                            $ow_fare = $fare_calc[$ow_fare_code]['d_fare_amount'];
                                        
                                    }
                                    if($fareType == 'Infant' && $ff_infants_index && $i > $ff_infants_index) {
                                        $fare_rate = $fare_calc[$ff_rate_code]['d_fare_amount'];
                                        $fare_rate_code = $ff_rate_code;
                                    } 

                                    $resTravellerInfo = new ReservationTravellerInfo;
                                    $resTravellerInfo->i_reservation_id	= $reservation_record->id;
                                    $resTravellerInfo->v_traveller_name = $inputs['v_'.$type.'_name'][$i];
                                    $resTravellerInfo->d_birth_month_year = $inputs['v_'.$type.'_year'][$i].'-'.$inputs['v_'.$type.'_month'][$i].'-1';
                                    $resTravellerInfo->e_is_travel_alone = $inputs['v_'.$type.'_radio_group'][$i];
                                    $resTravellerInfo->e_type = ucwords($type);
                                    if($reservation_record['e_shuttle_type'] == "Shared"){
                                        $resTravellerInfo->d_fare_amount = $ow_fare;
                                    }
                                    $resTravellerInfo->v_rate_code = $fare_rate_code;
                                    $resTravellerInfo->save();
                                }                   
                            }
                        }
                        if($reservation_record['e_class_type'] == 'RT') {
                            $fare_rate_rt = $fare_rate_code_rt = 0;
                            $ff_infants_index_rt = null;
                            $check_type = $type.'_return';
                            if(isset($inputs['v_'.$check_type.'_name']) && $inputs['v_'.$check_type.'_name'][0] != "") {
                        
                                $totalRecordRt = $check_type.'_total_details';
                        
                                $fare_class_rt = FareClass::where('e_class_type', $reservation_record['e_class_type'])->where('v_class_label', $fareType)->select('id', 'v_rate_code')->first()->toArray();
                                
                                foreach ($fare_calc_rt as $key => $value) {
                                    if($fare_class_rt['v_rate_code'] == $value['v_rate_code']) {
                                        $fare_rate_rt = $value['d_fare_amount'];
                                        $fare_rate_code_rt = $value['v_rate_code'];
                                    }
                                }
                        
                                // Conditions for fare calculations
                                if($fareType == 'Full Fare'){
                                    $num_ff_adults_rt += count($inputs['v_'.$check_type.'_name']);
                                    if(isset($fare_calc_rt[$fare_class_rt['v_rate_code']])) {
                                        $fare_rate_rt = $fare_calc_rt[$fare_class_rt['v_rate_code']]['d_fare_amount'];
                                        $fare_rate_code_rt = $fare_class_rt['v_rate_code'];
                                    }
                                }
                        
                                if($fareType == 'Senior' || $fareType == 'Military'){
                                    if($total_travelllers_rt > 1) {
                                        $num_ff_adults_rt += count($inputs['v_'.$check_type.'_name']);
                                        if(isset($fare_calc_rt[$ff_rate_code])) {
                                            $fare_rate_rt = $fare_calc_rt[$ff_rate_code]['d_fare_amount'];
                                            $fare_rate_code_rt = $ff_rate_code;
                                        }
                                    }
                                }
                        
                                if($fareType == 'Child'){
                                    if($num_ff_adults_rt == 0 && count($inputs['v_'.$check_type.'_name']) > 0) {
                                        if(isset($fare_calc_rt[$ff_rate_code])) {
                                            $fare_rate_rt = $fare_calc_rt[$ff_rate_code]['d_fare_amount'];
                                            $fare_rate_code_rt = $ff_rate_code;
                                        }
                                    }
                                }
                        
                                if($fareType == 'Infant'){
                                    if(count($inputs['v_'.$check_type.'_name']) > $num_ff_adults_rt) {
                                        $ff_infants_index_rt = count($inputs['v_'.$check_type.'_name']) - $num_ff_adults_rt;
                                    }
                                }

                                if($reservation_record['e_shuttle_type']=='Shared') {
                                    // Divide passenger fare code 22 sep 2020
                                    if($reservation_record['e_class_type'] == 'RT'){
                                        $ow_fare_code = str_replace('RT','OW',$fare_rate_code_rt); 
                                        $rt_fare_code =  $fare_rate_code_rt;
                            
                                        $rt_fare = $fare_calc_rt[$rt_fare_code]['d_fare_amount'];
                                        $ow_fare = $fare_calc_rt[$ow_fare_code]['d_fare_amount'];
                            
                                        $rt_fare = $rt_fare - $ow_fare;
                                    }
                                    // End
                                } else {
                                    $rt_fare = 0.00;
                                }
                        
                                
                                
                                // End
                                for($i = 0; $i <= $inputs[$totalRecordRt]; $i++) {
                                    if($inputs['v_'.$check_type.'_name'][$i] != '') {
                                        //Companion	
                                        if($fareType == 'Full Fare' && $i > 0) {
                                            $fare_rate_code_rt = $companion_rate_code;	
                                            $ow_fare_code = str_replace('RT','OW',$fare_rate_code_rt); 
                                            $rt_fare_code =  $fare_rate_code_rt;
                                
                                            $rt_fare = $fare_calc_rt[$rt_fare_code]['d_fare_amount'];
                                            $ow_fare = $fare_calc_rt[$ow_fare_code]['d_fare_amount'];
                                
                                            $rt_fare = $rt_fare - $ow_fare;
                                        }
                                        if($fareType == 'Infant' && $ff_infants_index_rt && $i > $ff_infants_index_rt) {
                                            $fare_rate_rt = $fare_calc_rt[$ff_rate_code]['d_fare_amount'];
                                            $fare_rate_code_rt = $ff_rate_code;
                                        }
                        
                                        $resTravellerInfo = new ReservationTravellerInfo;
                                        $resTravellerInfo->i_reservation_id	= $reservation_record_rt->id;
                                        $resTravellerInfo->v_traveller_name = $inputs['v_'.$check_type.'_name'][$i];
                                        $resTravellerInfo->d_birth_month_year = $inputs['v_'.$check_type.'_year'][$i].'-'.$inputs['v_'.$check_type.'_month'][$i].'-1';
                                        $resTravellerInfo->e_is_travel_alone = $inputs['v_'.$check_type.'_radio_group'][$i];
                                        $resTravellerInfo->e_type = ucwords($type);
                                        if($reservation_record['e_shuttle_type'] == "Shared"){
                                            $resTravellerInfo->d_fare_amount = $rt_fare;
                                        }
                                        $resTravellerInfo->v_rate_code = $fare_rate_code_rt;
                                        $resTravellerInfo->save();
                                    }                   
                                }
                            }
                        
                        }
                    }

                    $reservation_record->i_total_num_passengers = ReservationTravellerInfo::where('i_reservation_id', $reservation_record->id)->count();
                    $reservation_record->save();
                    if($reservation_record['e_class_type'] == 'RT') {
                        $reservation_record_rt->i_total_num_passengers = ReservationTravellerInfo::where('i_reservation_id', $reservation_record_rt->id)->count();
                        $reservation_record_rt->save();
                    }  
                } else if($reservation_record['e_class_type'] == 'RT' && $reservation_record['i_parent_id'] != NULL) {
                    //Only RT Trip in edit
                    $total_travelllers_rt = $num_ff_adults_rt =  0;
                    foreach($fare_class_detail_ow as $data) {
                        $type = ($data['v_class_label'] == 'Full Fare') ? 'adult' : strtolower($data['v_class_label']);
                        if(isset($inputs['v_'.$type.'_name']) && $inputs['v_'.$type.'_name'][0] != "") {
                            $total_travelllers_rt += count(array_filter($inputs['v_'.$type.'_name']));
                        }                       
                    }
                    $serviceIds = $this->getServiceID($reservation_record->i_pickup_city_id, $reservation_record->i_dropoff_city_id);
                    $ff_rate_code = "FFRT";
                    $companion_rate_code = "CMRT";
                    ReservationTravellerInfo::where('i_reservation_id', $reservation_record->id)->delete();
                        
                    $fare_calc_rt = FareTable::where('i_origin_service_area_id', $serviceIds['home_pickup_service_id'])->where('i_dest_service_area_id', $serviceIds['home_dropoff_service_id'])->select('id', 'i_rate_class_id', 'v_rate_code', 'd_fare_amount')->get()->keyBy('v_rate_code')->toArray(); 
                    foreach($fare_class_detail_ow as $data) {
                        $fare_rate_rt = $fare_rate_code_rt = 0;
                        $ff_infants_index_rt = null;
                        $fareType = $data['v_class_label'];
                        $type = ($data['v_class_label'] == 'Full Fare') ? 'adult' : strtolower($data['v_class_label']);                       
                        
                        if(isset($inputs['v_'.$type.'_name']) && $inputs['v_'.$type.'_name'][0] != "") {
                    
                            $totalRecordRt = $type.'_total_details';
                    
                            $fare_class_rt = FareClass::where('e_class_type', $reservation_record['e_class_type'])->where('v_class_label', $fareType)->select('id', 'v_rate_code')->first()->toArray();
                            
                            foreach ($fare_calc_rt as $key => $value) {
                                if($fare_class_rt['v_rate_code'] == $value['v_rate_code']) {
                                    $fare_rate_rt = $value['d_fare_amount'];
                                    $fare_rate_code_rt = $value['v_rate_code'];
                                }
                            }
                    
                            // Conditions for fare calculations
                            if($fareType == 'Full Fare'){
                                $num_ff_adults_rt += count($inputs['v_'.$type.'_name']);
                                if(isset($fare_calc_rt[$fare_class_rt['v_rate_code']])) {
                                    $fare_rate_rt = $fare_calc_rt[$fare_class_rt['v_rate_code']]['d_fare_amount'];
                                    $fare_rate_code_rt = $fare_class_rt['v_rate_code'];
                                }
                            }
                    
                            if($fareType == 'Senior' || $fareType == 'Military'){
                                if($total_travelllers_rt > 1) {
                                    $num_ff_adults_rt += count($inputs['v_'.$type.'_name']);
                                    if(isset($fare_calc_rt[$ff_rate_code])) {
                                        $fare_rate_rt = $fare_calc_rt[$ff_rate_code]['d_fare_amount'];
                                        $fare_rate_code_rt = $ff_rate_code;
                                    }
                                }
                            }
                    
                            if($fareType == 'Child'){
                                if($num_ff_adults_rt == 0 && count($inputs['v_'.$type.'_name']) > 0) {
                                    if(isset($fare_calc_rt[$ff_rate_code])) {
                                        $fare_rate_rt = $fare_calc_rt[$ff_rate_code]['d_fare_amount'];
                                        $fare_rate_code_rt = $ff_rate_code;
                                    }
                                }
                            }
                    
                            if($fareType == 'Infant'){
                                if(count($inputs['v_'.$type.'_name']) > $num_ff_adults_rt) {
                                    $ff_infants_index_rt = count($inputs['v_'.$type.'_name']) - $num_ff_adults_rt;
                                }
                            }
                    
                            // Divide passenger fare code 22 sep 2020
                            if($reservation_record['e_class_type'] == 'RT'){
                                $ow_fare_code = str_replace('RT','OW',$fare_rate_code_rt); 
                                $rt_fare_code =  $fare_rate_code_rt;
                    
                                $rt_fare = $fare_calc_rt[$rt_fare_code]['d_fare_amount'];
                                $ow_fare = $fare_calc_rt[$ow_fare_code]['d_fare_amount'];
                    
                                $rt_fare = $rt_fare - $ow_fare;
                            }
                            // End
                            
                            // Divide passenger fare code 22 sep 2020
                            $ow_fare_code = str_replace('RT','OW',$fare_rate_code_rt);
                            $rt_fare_code = $fare_rate_code_rt;
                            
                            $rt_fare = $fare_calc_rt[$rt_fare_code]['d_fare_amount'];
                            $ow_fare = $fare_calc_rt[$ow_fare_code]['d_fare_amount'];
                            
                            $rt_fare = $rt_fare - $ow_fare;
                            // End
                            for($i = 0; $i <= $inputs[$totalRecordRt]; $i++) {
                                if($inputs['v_'.$type.'_name'][$i] != '') {
                                    //Companion	
                                    if($fareType == 'Full Fare' && $i > 0) {	
                                        $fare_rate_code_rt = $companion_rate_code;	
                                        $ow_fare_code = str_replace('RT','OW',$fare_rate_code_rt); 
                                        $rt_fare_code =  $fare_rate_code_rt;
                            
                                        $rt_fare = $fare_calc_rt[$rt_fare_code]['d_fare_amount'];
                                        $ow_fare = $fare_calc_rt[$ow_fare_code]['d_fare_amount'];
                            
                                        $rt_fare = $rt_fare - $ow_fare;	
                                    }
                                    if($fareType == 'Infant' && $ff_infants_index_rt && $i > $ff_infants_index_rt) {
                                        $fare_rate_rt = $fare_calc_rt[$ff_rate_code]['d_fare_amount'];
                                        $fare_rate_code_rt = $ff_rate_code;
                                    }
                    
                                    $resTravellerInfo = new ReservationTravellerInfo;
                                    $resTravellerInfo->i_reservation_id	= $reservation_record->id;
                                    $resTravellerInfo->v_traveller_name = $inputs['v_'.$type.'_name'][$i];
                                    $resTravellerInfo->d_birth_month_year = $inputs['v_'.$type.'_year'][$i].'-'.$inputs['v_'.$type.'_month'][$i].'-1';
                                    $resTravellerInfo->e_is_travel_alone = $inputs['v_'.$type.'_radio_group'][$i];
                                    $resTravellerInfo->e_type = ucwords($type);
                                    if($reservation_record['e_shuttle_type'] == "Shared"){
                                        $resTravellerInfo->d_fare_amount = $rt_fare;
                                    }
                                    $resTravellerInfo->v_rate_code = $fare_rate_code_rt;
                                    $resTravellerInfo->save();
                                }                   
                            }
                        }
                        $reservation_record->i_total_num_passengers = ReservationTravellerInfo::where('i_reservation_id', $reservation_record->id)->count();
                        $reservation_record->save();
                    }
                }
                /* if($reservation_record['e_class_type'] == 'RT') {
                    $total_travelllers = $num_ff_adults =  0;
                    $ff_infants_index = null;

                    foreach($fare_class_detail_ow as $data) {
                        $type = ($data['v_class_label'] == 'Full Fare') ? 'adult' : strtolower($data['v_class_label']);
                        if(isset($inputs['v_'.$type.'_return_name']) && $inputs['v_'.$type.'_return_name'][0] != "") {
                            $total_travelllers += count(array_filter($inputs['v_'.$type.'_return_name']));
                        }
                    }

                    ReservationTravellerInfo::where('i_reservation_id', $reservation_record_rt->id)->delete();

                    $fare_calc = FareTable::where('i_origin_service_area_id', $serviceIds['home_dropoff_service_id'])->where('i_dest_service_area_id', $serviceIds['home_pickup_service_id'])->select('id', 'i_rate_class_id', 'v_rate_code', 'd_fare_amount')->get()->keyBy('v_rate_code')->toArray(); 

                    foreach($fare_class_detail_ow as $data) {
                        $fare_rate =  $fare_rate_code = 0;

                        $fareType = $data['v_class_label'];
                        $type = ($data['v_class_label'] == 'Full Fare') ? 'adult' : strtolower($data['v_class_label']);
                    
                        $check_type = $type.'_return';

                        if(isset($inputs['v_'.$check_type.'_name']) && $inputs['v_'.$check_type.'_name'][0] != "") {

                            $totalRecord = $check_type.'_total_details';

                            $fare_class = FareClass::where('e_class_type', $reservation_record['e_class_type'])->where('v_class_label', $fareType)->select('id', 'v_rate_code')->first()->toArray();
                            
                            foreach ($fare_calc as $key => $value) {
                                if($fare_class['v_rate_code'] == $value['v_rate_code']) {
                                    $fare_rate = $value['d_fare_amount'];
                                    $fare_rate_code = $value['v_rate_code'];
                                }
                            }

                            // Conditions for fare calculations
                            if($fareType == 'Full Fare'){
                                $num_ff_adults += count($inputs['v_'.$check_type.'_name']);
                                if(isset($fare_calc[$fare_class['v_rate_code']])) {
                                    $fare_rate = $fare_calc[$fare_class['v_rate_code']]['d_fare_amount'];
                                    $fare_rate_code = $fare_class['v_rate_code'];
                                }
                            }

                            if($fareType == 'Senior' || $fareType == 'Military'){
                                if($total_travelllers > 1) {
                                    $num_ff_adults += count($inputs['v_'.$check_type.'_name']);
                                    if(isset($fare_calc[$ff_rate_code])) {
                                        $fare_rate = $fare_calc[$ff_rate_code]['d_fare_amount'];
                                        $fare_rate_code = $ff_rate_code;
                                    }
                                }
                            }

                            if($fareType == 'Child'){
                                if($num_ff_adults == 0 && count($inputs['v_'.$check_type.'_name']) > 0) {
                                    if(isset($fare_calc[$ff_rate_code])) {
                                        $fare_rate = $fare_calc[$ff_rate_code]['d_fare_amount'];
                                        $fare_rate_code = $ff_rate_code;
                                    }
                                }
                            }

                            if($fareType == 'Infant'){
                                if(count($inputs['v_'.$check_type.'_name']) > $num_ff_adults) {
                                    $ff_infants_index = count($inputs['v_'.$check_type.'_name']) - $num_ff_adults;
                                }
                            }

                            // Divide passenger fare code 22 sep 2020
                            if($reservation_record['e_class_type'] == 'RT'){
                                $ow_fare_code = str_replace('RT','OW',$fare_rate_code); 
                                $rt_fare_code =  $fare_rate_code;

                                $rt_fare = $fare_calc[$rt_fare_code]['d_fare_amount'];
                                $ow_fare = $fare_calc[$ow_fare_code]['d_fare_amount'];

                                $rt_fare = $rt_fare - $ow_fare;
                            }
                            // End
                            
                            // Divide passenger fare code 22 sep 2020
                            $ow_fare_code = str_replace('RT','OW',$fare_rate_code);
                            $rt_fare_code = $fare_rate_code;
                            
                            $rt_fare = $fare_calc[$rt_fare_code]['d_fare_amount'];
                            $ow_fare = $fare_calc[$ow_fare_code]['d_fare_amount'];
                            
                            $rt_fare = $rt_fare - $ow_fare;
                            // End
                            for($i = 0; $i <= $inputs[$totalRecord]; $i++) {
                                if($inputs['v_'.$check_type.'_name'][$i] != '') {
                                    //Companion	
                                    if($fareType == 'Full Fare' && $i > 0) {	
                                        $fare_rate = $fare_calc[$companion_rate_code]['d_fare_amount'];	
                                        $fare_rate_code = $companion_rate_code;	
                                    }
                                    if($fareType == 'Infant' && $ff_infants_index && $i > $ff_infants_index) {
                                        $fare_rate = $fare_calc[$ff_rate_code]['d_fare_amount'];
                                        $fare_rate_code = $ff_rate_code;
                                    }

                                    $resTravellerInfo = new ReservationTravellerInfo;
                                    $resTravellerInfo->i_reservation_id	= $reservation_record_rt->id;
                                    $resTravellerInfo->v_traveller_name = $inputs['v_'.$check_type.'_name'][$i];
                                    $resTravellerInfo->d_birth_month_year = $inputs['v_'.$check_type.'_year'][$i].'-'.$inputs['v_'.$check_type.'_month'][$i].'-1';
                                    $resTravellerInfo->e_is_travel_alone = $inputs['v_'.$check_type.'_radio_group'][$i];
                                    $resTravellerInfo->e_type = ucwords($type);
                                    if($reservation_record['e_shuttle_type'] == "Shared"){
                                        $resTravellerInfo->d_fare_amount = $rt_fare;
                                    }
                                    $resTravellerInfo->v_rate_code = $fare_rate_code;
                                    $resTravellerInfo->save();
                                }                   
                            }
                        }
                    }  
                    $reservation_record_rt->i_total_num_passengers = ReservationTravellerInfo::where('i_reservation_id', $reservation_record_rt->id)->count();
                    $reservation_record_rt->save();                                    
                } */
                return response()->json([
                    'status' => 'TRUE',
                    'redirect_url' => isset($inputs['redirect_url']) ? FRONTEND_URL.$inputs['redirect_url'] : FRONTEND_URL.'luggage-animals'.(($id != '') ? '/'.$id : ''),
                ]);
            }    
            
            $traveller_info = ReservationTravellerInfo::where('i_reservation_id', $reservation_record->id)->get()->groupBy(function ($item) {
                return strtolower($item['e_type']);
            })->toArray();
            $traveller_info_rt = '';
            if(!empty($reservation_record_rt)) {
                $traveller_info_rt = ReservationTravellerInfo::where('i_reservation_id', $reservation_record_rt->id)->get()->groupBy(function ($item) {
                    return strtolower($item['e_type']);
                })->toArray();
            }
            
            return view('frontend.customer_reservation.passenger-information', array('title' => 'Passenger Information','reservation_record' => $reservation_record, 'traveller_info' => $traveller_info, 'traveller_info_rt' => $traveller_info_rt, 'fare_class_detail_ow' => $fare_class_detail_ow, 'paymentStatus' => $paymentStatus, 'reservation_record_rt' => $reservation_record_rt));
        } else {
            return redirect(FRONTEND_URL.'book-a-shuttle'.(($id != '') ? '/'.$id : ''));
        }
    
    }

    public function getServiceID($pickup_city, $dropoff_city) {
        $serviceIdPickup = GeoCities::where('id', $pickup_city)->first();
        $serviceIdDropoff = GeoCities::where('id', $dropoff_city)->first();
        
        return array('home_pickup_service_id' => (isset($serviceIdPickup) && !empty($serviceIdPickup)) ? $serviceIdPickup->i_service_area_id : '', 'home_dropoff_service_id' => (isset($serviceIdDropoff) && !empty($serviceIdDropoff)) ?$serviceIdDropoff->i_service_area_id : '');
    }

    public function LuggageAnimals(Request $request, $id = '') {
        $inputs = $request->all();
        if($id != '') {
            $reservation_record = Reservations::find($id);
            $reservation_record_rt = Reservations::where('i_parent_id', $id)->first();
        } else {
            $reservation_rec1 = Session::get('reservation_rec1');
            $reservation_rec2 = Session::get('reservation_rec2');      
            $reservation_record = Reservations::find($reservation_rec1);
            $reservation_record_rt = Reservations::find($reservation_rec2);
        }
        
        $sys_luggage_def = SystemLuggageDef::where(['e_type' => 'Luggage', 'deleted_at' => NULL])->get()->keyBy('id')->toArray();
        // pr($sys_luggage_def);exit;
        $sys_animal_def = SystemLuggageDef::where('e_type', 'Animal')->get()->toArray();
        
        if(!empty($reservation_record)) {
            if($reservation_record['e_class_type'] == 'RT' && $reservation_record['i_parent_id'] == NULL && empty($reservation_record_rt)) {
                return redirect(FRONTEND_URL.'book-a-shuttle'.(($id != '') ? '/'.$id : ''));
            }
            $paymentStatus = $this->getPaymentStatus($reservation_record, $reservation_record_rt);
            if(!empty($inputs)) {
                
                if(isset($inputs['i_number_of_luggages']) && isset($inputs['i_num_pets'])) {
                    ReservationLuggageInfo::where('i_reservation_id', $reservation_record['id'])->forceDelete();
                    if($reservation_record['e_class_type'] == 'RT'){
                        ReservationLuggageInfo::where('i_reservation_id', $reservation_record_rt['id'])->forceDelete();
                    }
                    $total_amount = $total_amount_rt = 0;

                    for($i = 0; $i <= count($sys_luggage_def) - 1; $i++) {
                    
                        if(isset($inputs['sys_luggage_'.$i]) && ($inputs['sys_luggage_'.$i] != '' && $inputs['sys_luggage_'.$i] != 0)) {
                            $no_of_val = $inputs['sys_luggage_'.$i];
                            if(isset($sys_luggage_def[$inputs['i_sys_luggage_'.$i]]) && $sys_luggage_def[$inputs['i_sys_luggage_'.$i]]['i_is_per_traveller_free']==1) {
                                $passengers = $reservation_record['i_total_num_passengers'] * 2;
                                if($inputs['sys_luggage_'.$i] <= $passengers) {
                                    $inputs['d_unit_price_'.$i] = 0;
                                } else {
                                    $no_of_val = $inputs['sys_luggage_'.$i] - $passengers;
                                }
                            }
                            $reservation_lugg_info = new ReservationLuggageInfo;
                            $reservation_lugg_info->i_reservation_id = $reservation_record['id'];
                            $reservation_lugg_info->i_sys_luggage_id = $inputs['i_sys_luggage_'.$i];
                            $reservation_lugg_info->i_value = $inputs['sys_luggage_'.$i];
                            $total_unit_price = ($no_of_val * $inputs['d_unit_price_'.$i]);
                            if($reservation_record['e_shuttle_type']=='Shared') {
                                $reservation_lugg_info->d_price = $total_unit_price;
                            } else {
                                $reservation_lugg_info->d_price = 0;
                            }
                            $reservation_lugg_info->created_at = Carbon::now();
                            $reservation_lugg_info->save();
                        }
                        if(isset($inputs['i_sys_pet_'.$i]) && isset($inputs['fare_amt_pet_'.$i]) && ($inputs['fare_amt_pet_'.$i] != '' && $inputs['fare_amt_pet_'.$i] != 0)) {
                            $reservation_lugg_info = new ReservationLuggageInfo;
                            $reservation_lugg_info->i_reservation_id = $reservation_record['id'];
                            $reservation_lugg_info->i_sys_luggage_id = $inputs['i_sys_pet_'.$i];
                            $reservation_lugg_info->i_value = 1;
                            if($reservation_record['e_shuttle_type']=='Shared') {
                                $reservation_lugg_info->d_price = $inputs['fare_amt_pet_'.$i];
                            }else {
                                $reservation_lugg_info->d_price = 0;
                            }
                            $reservation_lugg_info->save();
                        }

                        if($reservation_record['e_class_type'] == 'RT') {
                            
                            if(isset($inputs['sys_luggage_rt_'.$i]) && ($inputs['sys_luggage_rt_'.$i] != '' && $inputs['sys_luggage_rt_'.$i] != 0)) {
                                $no_of_val = $inputs['sys_luggage_rt_'.$i];
                                if(isset($sys_luggage_def[$inputs['i_sys_luggage_rt_'.$i]]) && $sys_luggage_def[$inputs['i_sys_luggage_rt_'.$i]]['i_is_per_traveller_free']==1) {
                                    $passengers = $reservation_record_rt['i_total_num_passengers'] * 2;
                                    if($inputs['sys_luggage_rt_'.$i] <= $passengers) {
                                        $inputs['d_unit_price_rt_'.$i] = 0;
                                    } else {
                                        $no_of_val = $inputs['sys_luggage_rt_'.$i] - $passengers;
                                    }
                                }
                                $reservation_lugg_info = new ReservationLuggageInfo;
                                $reservation_lugg_info->i_reservation_id = $reservation_record_rt['id'];
                                $reservation_lugg_info->i_sys_luggage_id = $inputs['i_sys_luggage_rt_'.$i];
                                $reservation_lugg_info->i_value = $inputs['sys_luggage_rt_'.$i];
                                $total_unit_price = ($no_of_val * $inputs['d_unit_price_rt_'.$i]);
                                if($reservation_record['e_shuttle_type']=='Shared') {
                                    $reservation_lugg_info->d_price = $total_unit_price;
                                } else {
                                    $reservation_lugg_info->d_price = 0;
                                }
                                $reservation_lugg_info->created_at = Carbon::now();
                                $reservation_lugg_info->save();
                            }
                            if(isset($inputs['i_sys_pet_rt_'.$i]) && isset($inputs['fare_amt_pet_rt_'.$i]) && ($inputs['fare_amt_pet_rt_'.$i] != '' && $inputs['fare_amt_pet_rt_'.$i] != 0)) {
                                $reservation_lugg_info = new ReservationLuggageInfo;
                                $reservation_lugg_info->i_reservation_id = $reservation_record_rt['id'];
                                $reservation_lugg_info->i_sys_luggage_id = $inputs['i_sys_pet_rt_'.$i];
                                $reservation_lugg_info->i_value = 1;
                                if($reservation_record['e_shuttle_type']=='Shared') {
                                    $reservation_lugg_info->d_price = $inputs['fare_amt_pet_rt_'.$i];
                                } else {
                                    $reservation_lugg_info->d_price = 0;
                                }
                                $reservation_lugg_info->save();
                            }
                        }
                    }

                    $reservation_record->i_num_pets = $inputs['i_num_pets'];
                    $reservation_record->i_number_of_luggages = $inputs['i_number_of_luggages'];
                    $reservation_record->save();
                    if($reservation_record['e_class_type'] == 'RT') {
                        $reservation_record_rt->i_num_pets = $inputs['i_num_pets_rt'];
                        $reservation_record_rt->i_number_of_luggages = $inputs['i_number_of_luggages_rt'];
                        $reservation_record_rt->save();
                    }
                }
                
                return response()->json([
                    'status' => 'TRUE',
                    'redirect_url' => isset($inputs['redirect_url']) ? FRONTEND_URL.$inputs['redirect_url'] : FRONTEND_URL.'travel-details'.(($id != '') ? '/'.$id : ''),
                ]);
            } else {
                $reservation_luggage_info = ReservationLuggageInfo::where('i_reservation_id', $reservation_record['id'])->get();
                $travellerCount = ReservationTravellerInfo::where('i_reservation_id', $reservation_record['id'])->count();
               
                $reservation_luggage_info_rt = '';
                $travellerRtCount = 0;
                if($reservation_record['e_class_type'] == 'RT') {
                    $reservation_luggage_info_rt = ReservationLuggageInfo::where('i_reservation_id', $reservation_record_rt['id'])->get();
                    $travellerRtCount = ReservationTravellerInfo::where('i_reservation_id',  $reservation_record_rt['id'])->count();
                }else{
                    $reservation_record_rt = '';
                }
                
                return view('frontend.customer_reservation.luggage-animals', array('title' => 'Luggage Animals', 'sys_luggage_def' => $sys_luggage_def,'sys_animal_def' => $sys_animal_def,'reservation_luggage_info' => $reservation_luggage_info,'reservation_luggage_info_rt' => $reservation_luggage_info_rt, 'reservation_record' => $reservation_record, 'reservation_record_rt' => $reservation_record_rt, 'travellerCount' => $travellerCount, 'travellerRtCount' => $travellerRtCount, 'paymentStatus' => $paymentStatus));
            }    

        } else {
            return redirect(FRONTEND_URL.'book-a-shuttle'.(($id != '') ? '/'.$id : ''));
        }     
    }
    
    public function TravelDetails(Request $request, $id = '') {
        $inputs = $request->all();
        if($id != '') {
            $reservation_record = Reservations::with(['PickupCity','DropOffCity'])->where('id',$id)->first();
            $reservation_record_rt = Reservations::with(['PickupCity','DropOffCity'])->where('i_parent_id', $id)->first();
        } else {
            $reservation_rec1 = Session::get('reservation_rec1');
            $reservation_rec2 = Session::get('reservation_rec2');
            
            $reservation_record = Reservations::with(['PickupCity','DropOffCity'])->where('id',$reservation_rec1)->first();
            $reservation_record_rt = Reservations::with(['PickupCity','DropOffCity'])->where('id',$reservation_rec2)->first();
        }
                
        if(!empty($reservation_record)) {
            
            if($reservation_record['e_class_type'] == 'RT' && $reservation_record['i_parent_id'] == NULL && empty($reservation_record_rt)) {
                return redirect(FRONTEND_URL.'book-a-shuttle'.(($id != '') ? '/'.$id : ''));
            }
            $paymentStatus = $this->getPaymentStatus($reservation_record, $reservation_record_rt);
            if(!empty($inputs)) {
                
                $validations = [
                    'v_contact_name' => 'required',
                    'v_contact_phone_number' => 'required',
                    'v_contact_email' => 'required|email',
                    'i_reservation_category_id' => 'required',
                    'i_origin_point_id' => 'required',
                    'i_destination_point_id' => 'required',
                    't_flight_time' => 'required',
                    't_comfortable_time' => 'required',
                ];
                if($reservation_record['e_class_type'] == 'RT' && $reservation_record['i_parent_id'] == NULL) {
                    $validations['rt_t_flight_time'] = 'required';
                    $validations['rt_t_comfortable_time'] = 'required';
                    $validations['rt_i_reservation_category_id'] = 'required';
                }

                $validator = Validator::make($inputs,$validations);

                if ($validator->fails()) {
                    return json_encode($validator->errors());
                } else {
                    // $inputs = self::convert_from_latin1_to_utf8_recursively($inputs);
                    $reservation_record->v_contact_name = ucwords($inputs['v_contact_name']);
                    $reservation_record->v_contact_phone_number = $inputs['v_contact_phone_number'];
                    $reservation_record->v_contact_email = $inputs['v_contact_email'];
                    if(isset($inputs['t_best_time_tocall']) && $inputs['t_best_time_tocall'] != '') {
                        $reservation_record->t_best_time_tocall = date("G:i", strtotime($inputs['t_best_time_tocall']));
                    }
                    $reservation_record->i_reservation_category_id = $inputs['i_reservation_category_id'];
                    $reservation_record->v_pickup_address = utf8_encode(ucwords($inputs['i_origin_point_id'])); 
                    $reservation_record->v_dropoff_address = utf8_encode(ucwords($inputs['i_destination_point_id']));  
                    $reservation_record->i_dropoff_point_type_id = $inputs['i_dropoff_point_type_id'];   
                    /* if($reservation_record->e_shuttle_type == "Shared") {
                        $reservation_record->i_origin_point_id = $inputs['i_origin_point_id']; 
                        $reservation_record->i_destination_point_id = $inputs['i_destination_point_id'];
                    } */
                    
                    if(isset($inputs['i_airline_id']) && $inputs['i_airline_id'] != '') {
                        $rec1_airline_rec = SystemIcaoDef::select('v_airline_name')->where('id',$inputs['i_airline_id'])->first();            
                        $reservation_record->v_flight_name = ($rec1_airline_rec) ? $rec1_airline_rec->v_airline_name : '';
                        $reservation_record->e_flight_type = $inputs['e_flight_type'];
                    } else {
                        $reservation_record->v_flight_name = NULL;
                        $reservation_record->e_flight_type = NULL;
                    }
                    if(isset($inputs['v_flight_number']) && $inputs['v_flight_number'] != '') {
                        $reservation_record->v_flight_number = $inputs['v_flight_number'];
                    } else {
                        $reservation_record->v_flight_number = NULL;
                    }
                    $reservation_record->t_flight_time = date("G:i", strtotime($inputs['t_flight_time']));
                    $reservation_record->t_comfortable_time = date("G:i", strtotime($inputs['t_comfortable_time']));
                    $reservation_record->t_target_time = (isset($inputs['t_target_time']) && $inputs['t_target_time']) ? date("G:i", strtotime($inputs['t_target_time'])) : NULL;
                    $reservation_record->d_total_fare = $this->calculateTotalFare($reservation_record->id);
                    $reservation_record->save();
                
                    if($reservation_record['e_class_type'] == 'RT' && $reservation_record['i_parent_id'] == NULL) {
                       
                        $reservation_record_rt->v_contact_name = ucwords($inputs['v_contact_name']);
                        $reservation_record_rt->v_contact_phone_number = $inputs['v_contact_phone_number'];
                        $reservation_record_rt->v_contact_email = $inputs['v_contact_email'];
                        if(isset($inputs['t_best_time_tocall']) && $inputs['t_best_time_tocall']!='') {
                            $reservation_record_rt->t_best_time_tocall = date("G:i", strtotime($inputs['t_best_time_tocall']));
                        }
                        $reservation_record_rt->i_reservation_category_id = $inputs['rt_i_reservation_category_id'];
                        $reservation_record_rt->v_pickup_address = utf8_encode(ucwords($inputs['rt_i_origin_point_id'])); 
                        $reservation_record_rt->v_dropoff_address = utf8_encode(ucwords($inputs['rt_i_destination_point_id']));
                        $reservation_record_rt->i_dropoff_point_type_id = $inputs['rt_i_dropoff_point_type_id'];
                        /* if($reservation_record_rt->e_shuttle_type == 'Shared') {
                            $reservation_record_rt->i_origin_point_id = $inputs['rt_i_origin_point_id']; 
                            $reservation_record_rt->i_destination_point_id = $inputs['rt_i_destination_point_id'];
                        } */
                            
                        if(isset($inputs['rt_i_airline_id']) && $inputs['rt_i_airline_id']!=''){
                            $rec1_airline_rec = SystemIcaoDef::select('v_airline_name')->where('id',$inputs['rt_i_airline_id'])->first();            
                            $reservation_record_rt->v_flight_name = ($rec1_airline_rec) ? $rec1_airline_rec->v_airline_name : '';
                            $reservation_record_rt->e_flight_type = $inputs['rt_e_flight_type'];
                        } else {
                            $reservation_record_rt->v_flight_name = NULL;
                            $reservation_record_rt->e_flight_type = NULL;
                        }
                        if(isset($inputs['rt_v_flight_number']) && $inputs['rt_v_flight_number']!='') {
                            $reservation_record_rt->v_flight_number = $inputs['rt_v_flight_number'];
                        } else {
                            $reservation_record_rt->v_flight_number = NULL;
                        }
                        $reservation_record_rt->t_flight_time = date("G:i", strtotime($inputs['rt_t_flight_time']));
                        $reservation_record_rt->t_comfortable_time = date("G:i", strtotime($inputs['rt_t_comfortable_time']));
                        $reservation_record_rt->t_target_time = (isset($inputs['rt_t_target_time']) && $inputs['rt_t_target_time']) ? date("G:i", strtotime($inputs['rt_t_target_time'])) : NULL;
                        $reservation_record_rt->d_total_fare = $this->calculateTotalFare($reservation_record_rt->id);
                        $reservation_record_rt->save();
                    }
                    
                    return response()->json([
                        'status' => 'TRUE',
                        'redirect_url' => isset($inputs['redirect_url']) ? ((strpos($inputs['redirect_url'], 'backend') !== false) ? $inputs['redirect_url'] : FRONTEND_URL.$inputs['redirect_url']) : FRONTEND_URL.'currently-assigned-line-runs'.(($id != '') ? '/'.$id : ''),
                    ]);
                }
            } else {
                
                $resv1_sel_flight_name = $resv2_sel_flight_name = null;
                
                if($reservation_record->v_flight_name != '') {
                    $resv1_sel_flight_name = SystemIcaoDef::select('id', 'v_airline_name')->where('v_airline_name', $reservation_record->v_flight_name)->first();
                }
                if(!empty($reservation_record_rt) && $reservation_record_rt->v_flight_name != '') {
                    $resv2_sel_flight_name = SystemIcaoDef::select('id','v_airline_name')->where('v_airline_name', $reservation_record_rt->v_flight_name)->first();
                }
 
                $res_categories = $pick_res_categories = $drop_res_categories = $pick_res_categories_rt = $drop_res_categories_rt = SystemResCategory::select('id','v_label')->get()->toArray();

                $pickup_points = GeoCities::select('id','v_city','v_county','i_service_area_id')->with('GeoPoints')->where('id',$reservation_record->i_pickup_city_id)->first()->toArray();

                $dropoff_points = GeoCities::select('id','v_city','v_county','i_service_area_id')->with('GeoPoints')->where('id',$reservation_record->i_dropoff_city_id)->first()->toArray();

                if($pickup_points && (in_array($pickup_points['v_county'],['Clallam','Jefferson']))) {
                    $pick_res_categories = SystemResCategory::select('id','v_label')->whereIn('id',[11,12,15])->get()->toArray();
                }

                if($dropoff_points && (in_array($dropoff_points['v_county'],['Clallam','Jefferson']))) {
                    $drop_res_categories = SystemResCategory::select('id','v_label')->whereIn('id',[11,12,15])->get()->toArray();
                }

                $pickup_points_rt = $dropoff_points_rt = [];

                if($reservation_record->e_class_type=='RT') {
                    if($reservation_record->i_pickup_city_id == $reservation_record_rt->i_dropoff_city_id) {
                        $pickup_points_rt = $dropoff_points;
                    } else {
                        $pickup_points_rt = GeoCities::select('id','v_city','v_county','i_service_area_id')->with('GeoPoints')->where('id',$reservation_record_rt->i_pickup_city_id)->first()->toArray();
                    }
                    if($reservation_record->i_dropoff_city_id == $reservation_record_rt->i_pickup_city_id) {
                        $dropoff_points_rt = $pickup_points;
                    } else {
                        $dropoff_points_rt = GeoCities::select('id','v_city','v_county','i_service_area_id')->with('GeoPoints')->where('id',$reservation_record_rt->i_dropoff_city_id)->first()->toArray();
                    }

                    if($pickup_points_rt && (in_array($pickup_points_rt['v_county'],['Clallam','Jefferson']))) {
                        $pick_res_categories_rt = SystemResCategory::select('id','v_label')->whereIn('id',[11,12,15])->get()->toArray();
                    }
    
                    if($dropoff_points_rt && (in_array($dropoff_points_rt['v_county'],['Clallam','Jefferson']))) {
                        $drop_res_categories_rt = SystemResCategory::select('id','v_label')->whereIn('id',[11,12,15])->get()->toArray();
                    }
                }

                return view('frontend.customer_reservation.travel-details', array('title' => 'Travel Details','pick_res_categories' => $pick_res_categories,'drop_res_categories' => $drop_res_categories,'res_categories' => $res_categories, 'pickup_points' => $pickup_points, 'dropoff_points' => $dropoff_points, 'reservation_record' => $reservation_record, 'reservation_record_rt' => $reservation_record_rt, 'resv1_sel_flight_name' => $resv1_sel_flight_name, 'resv2_sel_flight_name' => $resv2_sel_flight_name, 'paymentStatus' => $paymentStatus,'dropoff_points_rt' => $dropoff_points_rt,'pickup_points_rt' => $pickup_points_rt,'pick_res_categories_rt' => $pick_res_categories_rt,'drop_res_categories_rt' => $drop_res_categories_rt));
            }
        } else {
            return redirect(FRONTEND_URL.'book-a-shuttle'.(($id != '') ? '/'.$id : ''));
        }

    }

    public function getGeoPoints(Request $request,$id){
        $geo_points = GeoPoint::select('id','v_label','v_street1','i_city_id')->with('GeoCities')->whereHas('GeoCities',function($q) use($id) {
            $q->where('id',$id);
        });
        $inputs = $request->all();
        $geo_points = $geo_points->where(DB::raw('CONCAT(v_label," ",v_street1)'),'LIKE','%'.$inputs['term'].'%');
        $geo_points = $geo_points->get()->toArray();
        $return = [];
        foreach($geo_points as $gp){
            $return[] = $gp['v_label']." ".$gp['v_street1'];
        }
        return response()->json($return);
    }

    public function ConfirmLineRuns(Request $request, $id = '') {
        $input = $request->all();
        if($id != '') {
            $reservation_record = Reservations::find($id);
            $reservation_record_rt = Reservations::where('i_parent_id', $id)->first();
        } else {
            $reservation_rec1 = Session::get('reservation_rec1');
            $reservation_rec2 = Session::get('reservation_rec2');
            $reservation_record = Reservations::find($reservation_rec1);
            $reservation_record_rt = Reservations::find($reservation_rec2);
        }
                
        if(!empty($reservation_record)) {
            
            if($reservation_record['e_class_type'] == 'RT' && $reservation_record['i_parent_id'] == NULL && empty($reservation_record_rt)) {
                return redirect(FRONTEND_URL.'book-a-shuttle'.(($id != '') ? '/'.$id : ''));
            }
            if($reservation_record->e_reservation_status == "Callback" && $request->server('HTTP_REFERER') == SITE_URL.'callback-request') {
                Session::forget('reservation_rec1');
                Session::forget('reservation_rec2');
                Session::forget('callback_reservation_rec1');
                return redirect(FRONTEND_URL.'book-a-shuttle'.(($id != '') ? '/'.$id : ''));
            }

            $paymentStatus = $this->getPaymentStatus($reservation_record, $reservation_record_rt);
            
            if(!empty($input)) {
                if(isset($input['redirect_type']) && $input['redirect_type'] == 'call_request') {
                    $reservation_record->e_reservation_status = "Callback";
                    $reservation_record->save();
                    if(!empty($reservation_record_rt)) {
                        $reservation_record_rt->e_reservation_status = 'Callback';
                        $reservation_record_rt->save();
                    }

                    Session::put('callback_reservation_rec1', $reservation_record->id);
                    Session::forget('reservation_rec1');
                    Session::forget('reservation_rec2');
                    return response()->json([
                        'status' => 'TRUE',
                        'redirect_url' => FRONTEND_URL.'callback-request',
                    ]);                    
                } else {
                    if($reservation_record['e_class_type'] == 'OW' && isset($input['i_run_id']) && $input['i_run_id'] != '') {
                        $conFirmLinerun = ReservationLeg::where('i_reservation_id',$reservation_record->id)->first();
                        if(empty($conFirmLinerun)) {
                            $conFirmLinerun = new ReservationLeg;
                            $conFirmLinerun->created_at = Carbon::now();
                            $conFirmLinerun->i_reservation_id = $reservation_record['id'];
                        }                
                        $conFirmLinerun->e_status = 'Quote';
                        $conFirmLinerun->d_travel_date = $reservation_record['d_travel_date'];
                        /*  $conFirmLinerun->i_origin_point_id = $reservation_record['i_origin_point_id'];
                        $conFirmLinerun->i_destination_point_id = $reservation_record['i_destination_point_id']; */
                        $conFirmLinerun->i_run_id = $input['i_run_id'];                
                        $conFirmLinerun->save();
                        if($paymentStatus) {
                            $reservation_record->e_reservation_status = 'Booked';
                            $reservation_record->save();
                        }
                    } elseif($reservation_record['e_class_type'] == 'RT' && isset($input['i_run_id']) && $input['i_run_id'] != '' && isset($input['i_run_id_rt']) && $input['i_run_id_rt'] != '') {
                        $conFirmLinerun = ReservationLeg::where('i_reservation_id',$reservation_record->id)->first();
                        if(empty($conFirmLinerun)) {
                            $conFirmLinerun = new ReservationLeg;
                            $conFirmLinerun->created_at = Carbon::now();
                            $conFirmLinerun->i_reservation_id = $reservation_record['id'];
                        }                
                        $conFirmLinerun->e_status = 'Quote';
                        $conFirmLinerun->d_travel_date = $reservation_record['d_travel_date'];
                        /* $conFirmLinerun->i_origin_point_id = $reservation_record['i_origin_point_id'];
                        $conFirmLinerun->i_destination_point_id = $reservation_record['i_destination_point_id']; */
                        $conFirmLinerun->i_run_id = $input['i_run_id'];                
                        $conFirmLinerun->save();

                        $conFirmLinerun_rt = ReservationLeg::where('i_reservation_id',$reservation_record_rt->id)->first();
                        if(empty($conFirmLinerun_rt)) {
                            $conFirmLinerun_rt = new ReservationLeg;
                            $conFirmLinerun_rt->created_at = Carbon::now(); 
                            $conFirmLinerun_rt->i_reservation_id = $reservation_record_rt['id'];
                        }                    
                        $conFirmLinerun_rt->e_status = 'Quote';
                        $conFirmLinerun_rt->d_travel_date = $reservation_record_rt['d_travel_date'];
                        /*  $conFirmLinerun_rt->i_origin_point_id = $reservation_record_rt['i_destination_point_id'];
                        $conFirmLinerun_rt->i_destination_point_id = $reservation_record_rt['id']; */
                        $conFirmLinerun_rt->i_run_id = $input['i_run_id_rt']; 
                        $conFirmLinerun_rt->save();

                        if($paymentStatus) {
                            $reservation_record->e_reservation_status = 'Booked';
                            $reservation_record->save();
                            $reservation_record_rt->e_reservation_status = 'Booked';
                            $reservation_record_rt->save();
                        }

                    } elseif($reservation_record['e_class_type'] == 'RT' && isset($input['i_run_id']) && $input['i_run_id'] != '') {
                        $conFirmLinerun = ReservationLeg::where('i_reservation_id',$reservation_record->id)->first();
                        if(empty($conFirmLinerun)) {
                            $conFirmLinerun = new ReservationLeg;
                            $conFirmLinerun->created_at = Carbon::now();
                            $conFirmLinerun->i_reservation_id = $reservation_record['id'];
                        }                
                        $conFirmLinerun->e_status = 'Quote';
                        $conFirmLinerun->d_travel_date = $reservation_record['d_travel_date'];
                        /*  $conFirmLinerun->i_origin_point_id = $reservation_record['i_origin_point_id'];
                        $conFirmLinerun->i_destination_point_id = $reservation_record['i_destination_point_id']; */
                        $conFirmLinerun->i_run_id = $input['i_run_id'];                
                        $conFirmLinerun->save();

                        //Return run line not available
                        if($paymentStatus) {
                            //Put return trip on hold as payment done
                            if(!empty($reservation_record_rt)) {
                                $reservation_record_rt->e_reservation_status = 'Hold';
                                $reservation_record_rt->save();
                            }
                        } else {
                            if($id == '') {
                                Session::forget('reservation_rec2');
                            }
                            Reservations::where('id',$reservation_record_rt->id)->delete();
                            ReservationTravellerInfo::where('i_reservation_id', $reservation_record_rt->id)->delete();
                            ReservationLuggageInfo::where('i_reservation_id', $reservation_record_rt->id)->delete();
                            ReservationLeg::where('i_reservation_id',$reservation_record_rt->id)->delete();
                                                
                            $reservation_record->e_class_type = 'OW';
                            //Upgrade Discount Price
                            $totalFare = $this->calculateTotalFare($reservation_record->id);
                            $discountPrice = $this->calculateDiscountFare($reservation_record->v_discount_code, 'OW', $totalFare);
                            if($discountPrice > 0) {
                                $reservation_record->d_discount_price = $discountPrice;
                                $reservation_record->d_total_fare = $totalFare - $discountPrice;
                            } else {
                                $reservation_record->v_discount_code = NULL;
                                $reservation_record->d_discount_price = NULL;
                                $reservation_record->d_total_fare = $totalFare;
                            }
                            $reservation_record->save();
                        }

                    } elseif($reservation_record['e_class_type'] == 'RT' && isset($input['i_run_id_rt']) && $input['i_run_id_rt'] != '') {
                        $conFirmLinerun_rt = ReservationLeg ::where('i_reservation_id',$reservation_record_rt->id)->first();
                        if(empty($conFirmLinerun_rt)) {
                            $conFirmLinerun_rt = new ReservationLeg;
                            $conFirmLinerun_rt->created_at = Carbon::now(); 
                            $conFirmLinerun_rt->i_reservation_id = $reservation_record_rt['id'];
                        }                    
                        $conFirmLinerun_rt->e_status = 'Quote';
                        $conFirmLinerun_rt->d_travel_date = $reservation_record_rt['d_travel_date'];
                        /*  $conFirmLinerun_rt->i_origin_point_id = $reservation_record_rt['i_destination_point_id'];
                        $conFirmLinerun_rt->i_destination_point_id = $reservation_record_rt['id']; */
                        $conFirmLinerun_rt->i_run_id = $input['i_run_id_rt']; 
                        $conFirmLinerun_rt->save();                    
                        //Departure run line not available
                        if($paymentStatus) {
                            //Put departure trip on hold as payment done
                            $reservation_record->e_reservation_status = 'Hold';
                            $reservation_record->save();
                        } else {
                            if($id == '') {
                                Session::put('reservation_rec1',$reservation_record_rt->id);
                                Session::forget('reservation_rec2');
                            }                        
                            Reservations::where('id',$reservation_record->id)->delete();
                            ReservationTravellerInfo::where('i_reservation_id', $reservation_record->id)->delete();
                            ReservationLuggageInfo::where('i_reservation_id', $reservation_record->id)->delete();
                            ReservationLeg::where('i_reservation_id',$reservation_record->id)->delete();

                            $reservation_record_rt->i_parent_id = NULL;
                            $reservation_record_rt->e_class_type = 'OW';
                            //Upgrade Discount Price
                            //Only return trip so new fare calculate
                            $totalFare = $this->calculateNewTotalFare($reservation_record_rt->id, true);
                            $discountPrice = $this->calculateDiscountFare($reservation_record_rt->v_discount_code, 'OW', $totalFare);
                            if($discountPrice > 0) {
                                $reservation_record_rt->d_discount_price = $discountPrice;
                                $reservation_record_rt->d_total_fare = $totalFare - $discountPrice;
                            } else {
                                $reservation_record_rt->v_discount_code = NULL;
                                $reservation_record_rt->d_discount_price = NULL;
                                $reservation_record_rt->d_total_fare = $totalFare;
                            }
                            $reservation_record_rt->save();
                        }
                    } else {
                        ReservationLeg::where('i_reservation_id',$reservation_record->id)->delete();
                        if($reservation_record['e_class_type'] == 'RT' && !empty($reservation_record_rt)) {
                            ReservationLeg::where('i_reservation_id',$reservation_record_rt->id)->delete();
                        }
                        if($paymentStatus) {
                            //Set On Hold Record as payment is already done
                            $reservation_record->e_reservation_status = 'Hold';
                            $reservation_record->save();
                            if($reservation_record['e_class_type'] == 'RT' && !empty($reservation_record_rt)) {
                                $reservation_record_rt->e_reservation_status = 'Hold';
                                $reservation_record_rt->save();
                            }
                        }
                    }

                    return response()->json([
                        'status' => 'TRUE',
                        'redirect_url' => (isset($input['redirect_url']) ? ((strpos($input['redirect_url'], 'backend') !== false) ? $input['redirect_url'] : FRONTEND_URL.$input['redirect_url']) : FRONTEND_URL.'reservation-summary'),
                    ]);
                }

            } else {
                return view('frontend.customer_reservation.confirm-line-runs', array('title' => 'Confirm Line Runs', 'paymentStatus' => $paymentStatus));
            }
        } else {
            return redirect(FRONTEND_URL.'book-a-shuttle'.(($id != '') ? '/'.$id : ''));
        }

    }

    public function ReservationSummary(Request $request, $id = '') {
        $inputs = $request->all();
        if($id != '') {
            $reservation_record = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory','PickupCity','DropOffCity'])->where(['id' => $id])->first();
            $reservation_record_rt = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory'])->where(['i_parent_id' => $id])->first();
        } else {
            $reservation_rec1 = Session::get('reservation_rec1');
            $reservation_rec2 = Session::get('reservation_rec2');
            $reservation_record = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory','PickupCity','DropOffCity'])->where(['id' => $reservation_rec1])->first();
            $reservation_record_rt = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory'])->where(['id' => $reservation_rec2])->first();
        }
        if($reservation_record['e_class_type'] == 'OW') {
            $total_payment = $this->calculateTotalFare($reservation_record['id']);
        } else {
            $total_payment = $this->calculateTotalFare($reservation_record['id'],$reservation_record_rt['id']);
        }
        if(!empty($reservation_record)) {            
            if($reservation_record['e_class_type'] == 'RT' && $reservation_record['i_parent_id'] == NULL && empty($reservation_record_rt)) {
                return redirect(FRONTEND_URL.'book-a-shuttle'.(($id != '') ? '/'.$id : ''));
            }
            $reservation_luggage_info = ReservationLuggageInfo::with(['SystemLuggageDef'])->where('i_reservation_id', $reservation_record->id)->get()->toArray();
            $reservation_pet_info = ReservationLuggageInfo::with(['SystemAnimalDef'])->where('i_reservation_id', $reservation_record->id)->get()->toArray();
            $reservation_luggage_info_total = ReservationLuggageInfo ::where('i_reservation_id', $reservation_record->id)->get()->sum('d_price');
                
            $total_fare_amount = ReservationTravellerInfo::where('i_reservation_id', $reservation_record->id)->select(DB::raw('SUM(d_fare_amount) AS total'), DB::raw('SUM(IF(e_type = "Adult", d_fare_amount, 0)) AS adult_total'), DB::raw('SUM(IF(e_type = "Child", d_fare_amount, 0)) AS child_total'),DB::raw('SUM(IF(e_type = "Senior", d_fare_amount, 0)) AS senior_total'),DB::raw('SUM(IF(e_type = "Military", d_fare_amount, 0)) AS military_total'),DB::raw('SUM(IF(e_type = "Infant", d_fare_amount, 0)) AS infant_total'), DB::raw('SUM(IF(e_type = "Adult", 1, 0)) AS adult_count'), DB::raw('SUM(IF(e_type = "Child", 1, 0)) AS child_count'), DB::raw('SUM(IF(e_type = "Senior", 1, 0)) AS senior_count'), DB::raw('SUM(IF(e_type = "Military", 1, 0)) AS military_count'), DB::raw('SUM(IF(e_type = "Infant", 1, 0)) AS infant_count'))->first()->toArray();

            $reservation_luggage_info_total_rt = "";
            $reservation_luggage_info_rt = $reservation_pet_info_rt = $total_fare_amount_rt = array();
            $res1_tt_text = $this->getTravelTypeText($reservation_record->i_reservation_category_id,$reservation_record->i_dropoff_point_type_id);
            $res2_tt_text = false;
            if($reservation_record['e_class_type'] == 'RT') {
                
                $reservation_luggage_info_rt = ReservationLuggageInfo::with(['SystemLuggageDef'])->where('i_reservation_id', $reservation_record_rt->id)->get()->toArray();
                $reservation_pet_info_rt = ReservationLuggageInfo::with(['SystemAnimalDef'])->where('i_reservation_id', $reservation_record_rt->id)->get()->toArray();
                $total_fare_amount_rt = ReservationTravellerInfo::where('i_reservation_id', $reservation_record_rt->id)->select(DB::raw('SUM(d_fare_amount) AS total'), DB::raw('SUM(IF(e_type = "Adult", d_fare_amount, 0)) AS adult_total'), DB::raw('SUM(IF(e_type = "Child", d_fare_amount, 0)) AS child_total'),DB::raw('SUM(IF(e_type = "Senior", d_fare_amount, 0)) AS senior_total'),DB::raw('SUM(IF(e_type = "Military", d_fare_amount, 0)) AS military_total'),DB::raw('SUM(IF(e_type = "Infant", d_fare_amount, 0)) AS infant_total'), DB::raw('SUM(IF(e_type = "Adult", 1, 0)) AS adult_count'), DB::raw('SUM(IF(e_type = "Child", 1, 0)) AS child_count'), DB::raw('SUM(IF(e_type = "Senior", 1, 0)) AS senior_count'), DB::raw('SUM(IF(e_type = "Military", 1, 0)) AS military_count'), DB::raw('SUM(IF(e_type = "Infant", 1, 0)) AS infant_count'))->first()->toArray();
                $reservation_luggage_info_total_rt = ReservationLuggageInfo ::where('i_reservation_id', $reservation_record_rt->id)->get()->sum('d_price');
                $res2_tt_text = $this->getTravelTypeText($reservation_record_rt->i_reservation_category_id,$reservation_record_rt->i_dropoff_point_type_id);
            }

            $paymentStatus = $this->getPaymentStatus($reservation_record, $reservation_record_rt);
            $paymentMode = $this->getPaymentMode($reservation_record->id);
            
            return view('frontend.customer_reservation.reservation-summary', array('title' => 'Reservation Summary','reservation_record' => $reservation_record, 'reservation_record_rt' => $reservation_record_rt,'reservation_luggage_info' => $reservation_luggage_info, 'reservation_pet_info' => $reservation_pet_info,'reservation_luggage_info_rt' => $reservation_luggage_info_rt, 'reservation_pet_info_rt'=> $reservation_pet_info_rt, 'total_fare_amount' => $total_fare_amount,'total_fare_amount_rt' => $total_fare_amount_rt, 'reservation_luggage_info_total' => $reservation_luggage_info_total,'reservation_luggage_info_total_rt' => $reservation_luggage_info_total_rt,'total_payment'=>$total_payment,'paymentStatus' => $paymentStatus,'paymentMode' => $paymentMode,'res1_tt_text' => $res1_tt_text,'res2_tt_text' => $res2_tt_text));
        } else {
            return redirect(FRONTEND_URL.'book-a-shuttle'.(($id != '') ? '/'.$id : ''));
        }
        
    }

    public function CustomerPayment(Request $request, $id = '') {
        $inputs = $request->all();
        
        if($id != '') {
            $reservation_record = Reservations::find($id);
            $reservation_record_rt = Reservations::where('i_parent_id', $id)->first();
            $reservation_rec1 = $reservation_record->id;
            $reservation_rec2 = '';
        } else {
            $reservation_rec1 = Session::get('reservation_rec1');
            $reservation_rec2 = Session::get('reservation_rec2');
            $reservation_record = Reservations::find($reservation_rec1);
            $reservation_record_rt = Reservations::find($reservation_rec2);
        }
        
        $current_user = auth()->guard('customers')->user();
        
        if(!empty($reservation_record)) {
            if($reservation_record['e_class_type'] == 'RT' && $reservation_record['i_parent_id'] == NULL && empty($reservation_record_rt)) {
                return redirect(FRONTEND_URL.'book-a-shuttle'.(($id != '') ? '/'.$id : ''));
            }
            if($reservation_record['e_class_type'] == 'RT') {
                $reservation_rec2 = $reservation_record_rt->id;
            }
            if($reservation_record->e_reservation_status == "Booked" || ($reservation_record->e_reservation_status != "Quote" && $reservation_record->e_reservation_status != "Pending Payment")) {
                Session::forget('reservation_rec1');
                Session::forget('reservation_rec2');
                Session::forget('pp_reservation_rec1');
                return redirect(FRONTEND_URL.'book-a-shuttle'.(($id != '') ? '/'.$id : ''));
            }
            
            $customer = Customers::find($reservation_record->i_customer_id);
    
            $stripe_customer = null;        
            if($customer->customer_stripe_id != '') {
                try {
    
                    \Stripe\Stripe::setApiKey(STRIP_API_KEY);
                    $stripe_customer = \Stripe\Customer::retrieve($customer->customer_stripe_id);
    
                } catch (\Stripe\Exception\InvalidRequest $e) {
                    $body = $e->getJsonBody();
                    throw new ApplicationException($e->getMessage());
                } catch(\Stripe\Exception\CardException $e) {
                    $body = $e->getJsonBody();
                    throw new ApplicationException($e->getMessage());
                } catch (\Stripe\Exception\Authentication $e) {
                    $body = $e->getJsonBody();
                    throw new ApplicationException($e->getMessage());
                } catch (\Stripe\Exception\Permission $e) {
                    $body = $e->getJsonBody();
                    throw new ApplicationException($e->getMessage());
                } catch (\Stripe\Exception\Card $e) {
                    $body = $e->getJsonBody();
                    throw new ApplicationException($e->getMessage());
                } catch (\Stripe\Exception\RateLimit $e) {
                    $body = $e->getJsonBody();
                    throw new ApplicationException($e->getMessage());
                } catch (\Stripe\Exception\Api $e) {
                    $body = $e->getJsonBody();
                    throw new ApplicationException($e->getMessage());
                } catch (\Stripe\Exception\Subscription $e) {
                    $body = $e->getJsonBody();
                    throw new ApplicationException($e->getMessage());
                } catch (\Stripe\Exception\Customer $e) {
                    $body = $e->getJsonBody();
                    throw new ApplicationException($e->getMessage());
                } catch (Exception $e) {
                    $body = $e->getJsonBody();
                    throw new ApplicationException($e->getMessage());
                }
            }
    
    
            if(!empty($inputs)) {
                $is_ufwb = (isset($inputs['ufwb']) && $inputs['ufwb']==1) ? $inputs['ufwb'] : 0; 
                // Apply discount coupon code
                
                if($reservation_record['e_class_type'] == 'OW') {
                    $total_payment = $this->calculateTotalFare($reservation_record['id']);
                } else {
                    $total_payment = $this->calculateTotalFare($reservation_record['id'],$reservation_record_rt['id']);
                }
                $discount = $discount_code = null;
    
                if(isset($inputs['v_discount_code']) && $inputs['v_discount_code'] != '') {
                    $today_date = date('Y-m-d');
                    $trip_type = $reservation_record->e_class_type == 'OW' ? 'One Way' : 'Round Trip';
                    if(auth()->guard('customers')->check()) {
                        $coupon_code = Offers::where('v_coupon_code',$inputs['v_discount_code'])->where('d_start_date','<=',$today_date)->where('d_expire_date','>',$today_date)->where('e_type','<>','Employee')->whereIn('e_trip_type',[$trip_type,'Both'])->where('e_status','Active')->first();
                    } else if(auth()->guard('admin')->check()) {
                        $coupon_code = Offers::where('v_coupon_code',$inputs['v_discount_code'])->where('d_start_date','<=',$today_date)->where('d_expire_date','>',$today_date)->where('e_type','Employee')->whereIn('e_trip_type',[$trip_type,'Both'])->where('e_status','Active')->first();
                    }
                    
                    if($coupon_code) {
                        $discount_code = $inputs['v_discount_code'];
                        if($coupon_code->f_discount_percentage) {
                            $discount = ($total_payment * $coupon_code->f_discount_percentage) / 100;
                            $total_payment = $total_payment - $discount;
                        } else {
                            $discount = ($coupon_code->d_discount_flat_price);
                            $total_payment = $total_payment - $discount;
                        }
                        
                        // Code to manage if user applies 100% discount
                        if($total_payment <= 0) {
                            $reservation_record->e_reservation_status = 'Booked';
                            $reservation_record->v_discount_code = $discount_code;
                            $reservation_record->d_discount_price = $discount;
                            $reservation_record->d_total_fare = $discount;
                            $reservation_record->save();
                            if($reservation_rec2) {
                                $reservation_record_rt->v_discount_code = $discount_code;
                                $reservation_record_rt->d_discount_price = $discount;
                                $reservation_record_rt->d_total_fare = $discount;
                                $reservation_record_rt->e_reservation_status = 'Booked';
                                $reservation_record_rt->save();
                            }
                            return response()->json([
                                'status' => 'TRUE',
                                'redirect_url' => FRONTEND_URL.'payment/success',
                            ]);
                        }
    
                    }
                }
                // end apply discount coupon code
    
                if(isset($inputs['cash_on_board']) && $inputs['cash_on_board'] == 'on') {
                    
                    $trans = new Transactions;
                    $trans->i_customer_id = $customer->id;
                    $trans->i_reservation_id = $reservation_rec1;
                    $trans->v_stripe_payment_id = '';
                    $trans->d_amount = $reservation_record->d_total_fare;;
                    $trans->e_type = 'Cash-On-Board';
                    $trans->e_status = 'Success';
                    $trans->save();
                    $reservation_record->e_reservation_status = 'Booked';
                    if($discount && $discount_code && $reservation_record->e_class_type == 'OW'){
                        $reservation_record->v_discount_code = $discount_code;
                        $reservation_record->d_discount_price = $discount;
                        $reservation_record->d_total_fare = $reservation_record->d_total_fare - $discount;
                    }
                    $reservation_record->save();
                    if($reservation_rec2) {
                        $trans = new Transactions;
                        $trans->i_customer_id = $customer->id;
                        $trans->v_stripe_payment_id = '';
                        $trans->i_reservation_id = $reservation_rec2;
                        $trans->d_amount = $reservation_record_rt->d_total_fare;
                        $trans->e_status = 'Success';
                        $trans->e_type = 'Cash-On-Board';
                        $trans->save();
                        if($discount && $discount_code){
                            $reservation_record_rt->v_discount_code = $discount_code;
                            $reservation_record_rt->d_discount_price = $discount;
                            $reservation_record_rt->d_total_fare = $reservation_record_rt->d_total_fare - $discount;
                        }
                        $reservation_record_rt->e_reservation_status = 'Booked';
                        $reservation_record_rt->save();
                    }    
                    if($id != '') {
                        Session::put('pp_reservation_rec1',$reservation_rec1);
                    } 
                    
                    return response()->json([
                        'status' => 'TRUE',
                        'redirect_url' => FRONTEND_URL.'payment/success',
                    ]);
                } else {
                    $rules = $attributeNames = [];
                    if($is_ufwb == 0) {
                        $rules = [
                            'v_card_name' => 'required',
                            'i_card_num' => 'required',               
                            'i_card_exp_month' => 'required',   
                            'i_card_exp_year' => 'required',               
                            'i_cvc' => 'required',                
                        ];
                        $attributeNames = [
                            'v_card_name' => 'Card holder name',
                            'i_card_num' => 'Card Number',
                            'i_card_exp_month' => 'Expiry Month',
                            'i_card_exp_year' => 'Expiry Year',
                            'i_cvc' => 'Cvv',
                        ];
                    }
                }
                
                
    
                $validator = Validator::make($inputs, $rules);
                $validator->setAttributeNames($attributeNames);
    
                if ($validator->fails()) {
                    return json_encode($validator->errors());
                } else {
                    // Code to use wallet balance if available
                    if(isset($inputs['use_wallet']) && $inputs['use_wallet']==1 && $current_user) {
                        if($is_ufwb && $total_payment <= $current_user->d_wallet_balance){
                            if($discount && $discount_code && $reservation_record->e_class_type=='OW'){
                                $reservation_record->v_discount_code = $discount_code;
                                $reservation_record->d_discount_price = $discount;
                                $reservation_record->d_total_fare = $reservation_record->d_total_fare - $discount;
                                $reservation_record->save();
                            }
    
                            $reservation_record->e_reservation_status = "Booked";
                            $reservation_record->save();
    
                            $trans = new Transactions;
                            $trans->i_customer_id = $customer->id;
                            $trans->v_stripe_payment_id = '';
                            $trans->i_reservation_id = $reservation_rec1;
                            $trans->d_amount = $reservation_record->d_total_fare;
                            $trans->e_status = "Success";
                            $trans->e_type = "Booked-Wallet";
                            $trans->save();
    
                            if($reservation_rec2) {
                                $reservation_record_rt->e_reservation_status = "Booked";
                                $reservation_record_rt->save();
    
                                if($discount && $discount_code){
                                    $reservation_record_rt->v_discount_code = $discount_code;
                                    $reservation_record_rt->d_discount_price = $discount;
                                    $reservation_record_rt->d_total_fare = $reservation_record->d_total_fare - $discount;
                                    $reservation_record_rt->save();
                                }
    
                                $trans = new Transactions;
                                $trans->i_customer_id = $customer->id;
                                $trans->v_stripe_payment_id = '';
                                $trans->i_reservation_id = $reservation_rec2;
                                $trans->d_amount = $reservation_record_rt->d_total_fare;
                                $trans->e_status = "Success";
                                $trans->e_type = "Booked-Wallet";
                                $trans->save();
                            }
                            $current_user->d_wallet_balance = $current_user->d_wallet_balance - $total_payment;
                            $current_user->save();
                            return response()->json([
                                'status' => 'TRUE',
                                'redirect_url' => FRONTEND_URL.'payment/success',
                            ]);
                        } else {
                            $total_payment = $total_payment - $current_user->d_wallet_balance;
                        }
                    }
    
                    if($stripe_customer && $stripe_customer->sources->total_count > 0) {
                        foreach ($stripe_customer->sources->data as $cardDetail){
                            $input_card_num_4digits = substr($inputs['i_card_num'], -4);
                            if($input_card_num_4digits == $cardDetail['last4'] && $inputs['i_card_exp_month'] == $cardDetail['exp_month'] && $inputs['i_card_exp_year'] == $cardDetail['exp_year']) {
                                $inputs['stripe_card_id'] = $cardDetail['id'];
                            }
                        }
                    }
                    
                    try {
                    
                        \Stripe\Stripe::setApiKey(STRIP_API_KEY);
                        $card_id = $card_info = $stripe_customer = null;
                        
                        if($customer->customer_stripe_id != '') {
                            $stripe_customer = \Stripe\Customer::retrieve($customer->customer_stripe_id);
                            
                            if(isset($inputs['stripe_card_id']) && trim($inputs['stripe_card_id'])!='') {
                                $card_info = \Stripe\Customer::retrieveSource($customer->customer_stripe_id,
                                trim($inputs['stripe_card_id']),[]);
                                
                                if($card_info) {
                                    $stripe_customer->default_source = trim($inputs['stripe_card_id']);
                                    $stripe_customer->save();
                                    $card_id = trim($inputs['stripe_card_id']);
                                }
                            }
    
                        }

                        if(!$stripe_customer) {
                            $email = $customer->v_email;
                            $stripe_customer = \Stripe\Customer::create(array(
                                'source'   => $card_id,
                                'email'    => $email,
                            ));
    
                            $customer->customer_stripe_id = $stripe_customer->id;
                            $customer->save();
                        }
    
                        if(!$card_info) {
                            $myCard = array('name' => $inputs['v_card_name'],'number' => $inputs['i_card_num'], 'exp_month' => $inputs['i_card_exp_month'], 'exp_year' => $inputs['i_card_exp_year'],'cvc' => $inputs['i_cvc']);
                            
                            
                            $response = \Stripe\Token::create(array(
                                "card" => $myCard,
                            ));
    
                            $card_id = $response->id;

                            $source = \Stripe\Customer::createSource(
                                $customer->customer_stripe_id,
                                [
                                'source' => $card_id,
                                ]
                            );
                            
                            \Stripe\Customer::update(
                                $customer->customer_stripe_id,
                                [
                                'default_source' => $source->id,
                                ]
                            );
                        }
        
                        $amount = $total_payment * 100;
                        
                        
                        $charge = \Stripe\Charge::create(array(
                            'customer' => $customer->customer_stripe_id,
                            'amount' => $amount, 
                            'currency' => 'usd', 
                            'metadata' => array('order_id' => $reservation_record->id)
                        ));
    
                        if($charge->paid) {
                            $use_wallet_bal = 0;
                            if(isset($inputs['use_wallet']) && $inputs['use_wallet']==1 && $current_user){
                                $use_wallet_bal = $current_user->d_wallet_balance;
    
                                $trans = new Transactions;
                                $trans->i_customer_id = $customer->id;
                                $trans->v_stripe_payment_id = $charge->id;
                                $trans->i_reservation_id = $reservation_rec1;
                                $trans->d_amount = ($reservation_rec2 && $use_wallet_bal > 0) ? $use_wallet_bal / 2 : $use_wallet_bal;
                                $trans->e_status = "Success";
                                $trans->e_type = 'Booked-Wallet';
                                $trans->save();
    
                                if($reservation_rec2 && $use_wallet_bal > 0){
                                    $trans = new Transactions;
                                    $trans->i_customer_id = $customer->id;
                                    $trans->v_stripe_payment_id = $charge->id;
                                    $trans->i_reservation_id = $reservation_rec2;
                                    $trans->d_amount = $use_wallet_bal / 2;
                                    $trans->e_status = "Success";
                                    $trans->e_type = 'Booked-Wallet';
                                    $trans->save();
                                }
    
                                $current_user->d_wallet_balance = 0;
                                $current_user->save();
                                $trans_type = "Booked";
                            } else {            
                                $trans_type = "Booked";                    
                            }
    
                            $reservation_record = Reservations::find($reservation_rec1);
                            $reservation_record->e_reservation_status = 'Booked';
                            if($discount && $discount_code && $reservation_record->e_class_type=='OW'){
                                $reservation_record->v_discount_code = $discount_code;
                                $reservation_record->d_discount_price = $discount;
                                $reservation_record->d_total_fare = $reservation_record->d_total_fare - $discount;
                            }
                            $reservation_record->save();
    
                            $trans = new Transactions;
                            $trans->i_customer_id = $customer->id;
                            $trans->v_stripe_payment_id = $charge->id;
                            $trans->i_reservation_id = $reservation_rec1;
                            $trans->d_amount = ($reservation_rec2 && $use_wallet_bal > 0) ? ($reservation_record->d_total_fare - ($use_wallet_bal / 2)) : ($reservation_record->d_total_fare - $use_wallet_bal);
                            $trans->e_status = "Success";
                            $trans->e_type = $trans_type;
                            $trans->save();
                            
    
                            if($reservation_rec2) {
                                if($discount && $discount_code){
                                    $reservation_record_rt->v_discount_code = $discount_code;
                                    $reservation_record_rt->d_discount_price = $discount;
                                    $reservation_record_rt->d_total_fare = $reservation_record_rt->d_total_fare - $discount;
                                }
                                $reservation_record_rt->e_reservation_status = 'Booked';
                                $reservation_record_rt->save();
    
                                $trans = new Transactions;
                                $trans->i_customer_id = $customer->id;
                                $trans->v_stripe_payment_id = $charge->id;
                                $trans->i_reservation_id = $reservation_rec2;
                                $trans->d_amount = ($use_wallet_bal > 0) ? ($reservation_record_rt->d_total_fare - ($use_wallet_bal / 2)) : $reservation_record_rt->d_total_fare;
                                $trans->e_status = "Success";
                                $trans->e_type = $trans_type;
                                $trans->save();
                            }
                            if($id != '') {
                                Session::put('pp_reservation_rec1',$reservation_rec1);
                            }
                        }
                        return response()->json([
                            'status' => 'TRUE',
                            'redirect_url' => FRONTEND_URL.'payment/success',
                        ]);
    
                    } catch (\Stripe\Exception\CardException $e) {
                        $this->recordFailedTransaction($total_payment,$e->getMessage(),$customer->id,$reservation_rec1);
                        return response()->json(['status' => 'TRUE', 'message' => $e->getMessage(),'redirect_url' => FRONTEND_URL.'payment/failed']);
                    } catch (\Stripe\Exception\InvalidRequestException $e) {
                        $this->recordFailedTransaction($total_payment,$e->getMessage(),$customer->id,$reservation_rec1);
                        return response()->json(['status' => 'TRUE', 'message' => $e->getMessage(),'redirect_url' => FRONTEND_URL.'payment/failed']);
                    } catch (\Stripe\Exception\AuthenticationException $e) {
                        $this->recordFailedTransaction($total_payment,$e->getMessage(),$customer->id,$reservation_rec1);
                        return response()->json(['status' => 'TRUE', 'message' => $e->getMessage(),'redirect_url' => FRONTEND_URL.'payment/failed']);
                    } catch (\Stripe\Exception\RateLimitException $e) {
                        $this->recordFailedTransaction($total_payment,$e->getMessage(),$customer->id,$reservation_rec1);
                        return response()->json(['status' => 'TRUE', 'message' => $e->getMessage(),'redirect_url' => FRONTEND_URL.'payment/failed']);
                    } catch (\Stripe\Exception\ApiConnectionException $e) {
                        $this->recordFailedTransaction($total_payment,$e->getMessage(),$customer->id,$reservation_rec1);
                        return response()->json(['status' => 'TRUE', 'message' => $e->getMessage(),'redirect_url' => FRONTEND_URL.'payment/failed']);
                    } catch (\Stripe\Exception\ApiErrorException $e) {
                        $this->recordFailedTransaction($total_payment,$e->getMessage(),$customer->id,$reservation_rec1);
                        return response()->json(['status' => 'TRUE', 'message' => $e->getMessage(),'redirect_url' => FRONTEND_URL.'payment/failed']);
                    } catch (Exception $e) {
                        $this->recordFailedTransaction($total_payment,$e->getMessage(),$customer->id,$reservation_rec1);
                        return response()->json(['status' => 'TRUE', 'message' => $e->getMessage(),'redirect_url' => FRONTEND_URL.'payment/failed']);
                    }
                    
                }
            } else {
                
                $total_fare = ($reservation_record->e_class_type == 'OW') ? $this->calculateTotalFare($reservation_record->id) : $this->calculateTotalFare($reservation_record->id, $reservation_record_rt->id);
                
                $discountPrice = $this->calculateDiscountFare($reservation_record->v_discount_code, $reservation_record->e_class_type, $total_fare);
                
                return view('frontend.customer_reservation.payment', array('title' => 'Payment','stripe_customer' => $stripe_customer, 'reservation_record' => $reservation_record,'reservation_record_rt' => $reservation_record_rt,'total_fare' => $total_fare, 'discountPrice' => $discountPrice,'current_user' => $current_user));
            }
        } else {
            return redirect(FRONTEND_URL.'book-a-shuttle'.(($id != '') ? '/'.$id : ''));
        }
    }

    protected function recordFailedTransaction($amount,$msg,$customer_id,$reservation_id){
        $customer_info = Customers::find($customer_id);
       /*  pr($customer_info.'654');
        exit; */
        $pending_payment_email = Session::get('pending_payment_email_'.$reservation_id);
        if(!$pending_payment_email){
            $objEmailTemplate = EmailTemplate::find(6)->toArray();
          
            if($objEmailTemplate) {
                $reservation_record = Reservations::with(['PickupCity','DropOffCity'])->where(['id' => $reservation_id])->first();

                $send_email_payment_pending = 0;
                $selected_linerun_first_leg = ReservationLeg::select('id','i_run_id')->with('LineRune')->where('i_reservation_id',$reservation_id)->first();
                
                if($selected_linerun_first_leg) {
                    $selected_linerun_first_leg = $selected_linerun_first_leg->toArray();
                    $refund_compare_date_time = strtotime($selected_linerun_first_leg['line_rune']['d_run_date']." ".$selected_linerun_first_leg['line_rune']['t_scheduled_arr_time']);
                } else {
                    $refund_compare_date_time = ($reservation_record) ? strtotime($reservation_record->d_travel_date) : strtotime(date('Y-m-d H:i'));
                }
                
                $total_hours = round(($refund_compare_date_time - strtotime(date('Y-m-d H:i'))) / (60*60),2);
                if($total_hours >= 48) {
                    $send_email_payment_pending = 1;
                }
                $htmlData = $this->getAddressInfo($reservation_record['id']);
                if($reservation_record && $send_email_payment_pending==1) {
                    $reservation_record = $reservation_record->toArray();
                    $sys_settings = SystemSettings::select('v_comp_email')->find(1);
                    /* $pick_city = $reservation_record['pickup_city']['v_city'];
                    $drop_city = $reservation_record['drop_off_city']['v_city']; */
                    $strTemplate = $objEmailTemplate['t_email_content'];
                    $strTemplate = str_replace('[SITE_NAME]', SITE_NAME, $strTemplate);
                    $strTemplate = str_replace('[TICKET_INFORMATION]', $htmlData, $strTemplate);
                    /* $strTemplate = str_replace('[PICK_LOC]', $pick_city." - ".$reservation_record['v_pickup_address'], $strTemplate);
                    $strTemplate = str_replace('[DROP_LOC]', $drop_city." - ".$reservation_record['v_dropoff_address'], $strTemplate);
                    $strTemplate = str_replace('[TRAVEL_DATE]',date("m/d/Y",strtotime($reservation_record['d_travel_date'])),$strTemplate);
                    $strTemplate = str_replace('[TRAVEL_TIME]',date('g:i A' , strtotime($reservation_record['t_comfortable_time'])),$strTemplate);
                    $strTemplate = str_replace('[TRIP_TYPE]',$reservation_record['e_shuttle_type'],$strTemplate);
                    $strTemplate = str_replace('[NO_OF_PASSENGERS]',$reservation_record['i_total_num_passengers'],$strTemplate); */
                    $strTemplate = str_replace('[CUSTOMER_NAME]',$reservation_record['v_contact_name'],$strTemplate);
                    $strTemplate = str_replace('[PAYMENT_LINK]',SITE_URL."payment/".$reservation_id,$strTemplate);
    
                    $subject = $objEmailTemplate['v_template_subject'];
                    $subject = str_replace('[RESV_NUMBER]',$reservation_record['v_reservation_number'],$subject);
                    // mail sent to user with new link
                   
                    Mail::send('emails.auth.generate-email-template', array('strTemplate'=>$strTemplate), function($message) use ($subject,$reservation_record,$customer_info)
                    {
                        $message->from(CONTACT_EMAIL_ID, SITE_NAME);
                        $message->to($reservation_record['v_contact_email']);
                        if($customer_info['v_email'] != $reservation_record['v_contact_email']){			
                            $message->replyTo($customer_info['v_email']);	
                        }
                        $message->subject($subject);
                    });
                    Session::put('pending_payment_email_'.$reservation_record['id'],'1');
                }
            }
        } 

        $reservation_record = Reservations::find($reservation_id);
        $reservation_record->e_reservation_status = 'Pending Payment';
        $reservation_record->save();
        $reservation_record_rt = Reservations::where('i_parent_id',$reservation_id)->first();
        if($reservation_record_rt) {
            $reservation_record_rt->e_reservation_status = 'Pending Payment';
            $reservation_record_rt->save();
        }
        
        $trans = new Transactions;
        $trans->i_customer_id = $customer_id;
        $trans->v_stripe_payment_id = "";
        $trans->i_reservation_id = $reservation_id;
        $trans->d_amount = $amount;
        $trans->e_status = "Failed";
        $trans->v_error_log = $msg;
        $trans->save();
    }

    public function PaySuccess(Request $request) {
        $customer_info = auth()->guard('customers')->user();
        $reservation_rec1 = null;
        $edit_id = '';
        if(Session::has('pp_reservation_rec1') && $request->server('HTTP_REFERER') == SITE_URL.'payment/'.Session::get('pp_reservation_rec1')) {
            $reservation_rec1 = Session::get('pp_reservation_rec1');
        }
        if(Session::has('pp_reservation_rec1') && $request->server('HTTP_REFERER') == SITE_URL.'reservation-payment/'.Session::get('pp_reservation_rec1')) {
            $reservation_rec1 = Session::get('pp_reservation_rec1');
            $edit_id = $reservation_rec1;
        }
        
        if(Session::has('reservation_rec1') && $request->server('HTTP_REFERER') == SITE_URL.'reservation-payment') {
            $reservation_rec1 = Session::get('reservation_rec1');
        }        
        if(!$reservation_rec1) {
            return View('frontend.404', array('title' => 'Page not found'));
        }
                  
        $reservation_record = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory'])->where(['id' => $reservation_rec1])->first();
        $transaction = Transactions::where('i_reservation_id',$reservation_rec1)->orderBy('created_at','DESC')->first();
        $system_setting = SystemSettings::find(1);
        $contact = $system_setting['v_comp_tel_1'].''.($system_setting['v_comp_tel_2'] ? ' / '.$system_setting['v_comp_tel_2'] : '');
        $title = "Transaction Failed";

        $full_discounted = false;
        if($reservation_record->d_discount_price==$reservation_record->d_total_fare) {
            $full_discounted = true;
        }

        if($transaction && $transaction->e_status == "Success") {
                   
            // mail sent to user with new ticket
            $reservation_number = $reservation_record['v_reservation_number'];
            $htmlData = $this->getReservationData($reservation_record['id']);
            $objEmailTemplate = EmailTemplate::find(8)->toArray();
                $strTemplate = $objEmailTemplate['t_email_content'];
                $strTemplate = str_replace('[SITE_NAME]', SITE_NAME, $strTemplate);
                $strTemplate = str_replace('[SITE_URL]', SITE_URL, $strTemplate);
                $strTemplate = str_replace('[TICKET]',$htmlData,$strTemplate);
                $strTemplate = str_replace('[CONTACT]', $contact,$strTemplate);
                $strTemplate = str_replace('[EMAIL]', $system_setting['v_comp_email'],$strTemplate);
                $subject = $objEmailTemplate['v_template_subject'];
                /* pr($strTemplate);
                exit; */
                $subject = str_replace('[RESV_NUMBER]',$reservation_number,$subject);
               
                Mail::send('emails.auth.reservation-email-template', array('strTemplate' => $strTemplate), function($message) use ($reservation_record,$subject)
                {
                    $message->from(CONTACT_EMAIL_ID, SITE_NAME);
                    $message->to($reservation_record->v_contact_email);
                    if($reservation_record->Customers->v_email != $reservation_record['v_contact_email']){			
                        $message->replyTo($reservation_record->Customers->v_email);	
                    }
                    $message->subject($subject);
                });
                 // End mail sent to user with new ticket

            Session::forget('reservation_rec1');
            Session::forget('reservation_rec2');
            Session::forget('pp_reservation_rec1');
            $title = "Thank You";
        }
        return view('frontend.customer_reservation.payment_success', array('title' => $title,'reservation_record' => $reservation_record,'transaction' => $transaction, 'edit_id' => $edit_id,'full_discounted' => $full_discounted));
       
    }

    public function PrivateBookSuccess(Request $request) {
        $reservation_rec1 = null;
        
        if(Session::has('reservation_rec1') && ($request->server('HTTP_REFERER') == SITE_URL.'reservation-summary' || $request->server('HTTP_REFERER') == SITE_URL.'reservation-summary/'.Session::get('reservation_rec1'))) {
            $reservation_rec1 = Session::get('reservation_rec1');
        }
        
        if(!$reservation_rec1) {
            return View('frontend.404', array('title' => 'Page not found'));
        }

        $reservation_record = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory','PickupCity','DropOffCity'])->where(['id' => $reservation_rec1])->first();
       
        if($reservation_record->e_shuttle_type!='Private') {
            return View('frontend.404', array('title' => 'Page not found'));
        }
        
        $reservation_record->e_reservation_status = "Requested";
        $reservation_record->save();
        
        $objEmailTemplate = EmailTemplate::find(9)->toArray();
        $htmlData = $this->getAddressInfo($reservation_record['id']);
        if($objEmailTemplate) {
            
            if($reservation_record) {
                $reservation_record = $reservation_record->toArray();
                $customer_email = $reservation_record['customers']['v_email'];
                $strTemplate = $objEmailTemplate['t_email_content'];
                $strTemplate = str_replace('[SITE_NAME]', SITE_NAME, $strTemplate);
                $strTemplate = str_replace('[TICKET_INFORMATION]', $htmlData, $strTemplate);
                $strTemplate = str_replace('[CUSTOMER_NAME]',$reservation_record['v_contact_name'],$strTemplate);
                $subject = $objEmailTemplate['v_template_subject'];
              
                // mail sent to user with new link
                
                Mail::send('emails.auth.generate-email-template', array('strTemplate'=>$strTemplate), function($message) use ($subject,$reservation_record,$customer_email)
                {
                    $message->from(CONTACT_EMAIL_ID, SITE_NAME);
                    $message->to($reservation_record['v_contact_email']);
                    if($customer_email != $reservation_record['v_contact_email']){			
                        $message->replyTo($customer_email);	
                    }
                    $message->subject($subject);
                });
            }
        }

        Session::forget('reservation_rec1');
        Session::forget('reservation_rec2');
        Session::forget('pp_reservation_rec1');

        $title = "Thank You";
        Reservations::where('i_parent_id',$reservation_rec1)->update(['e_reservation_status' => 'Requested']);
        return view('frontend.customer_reservation.private_book_success', array('title' => $title));

    }

    public function CallbackRequestSuccess(Request $request) {
        $reservation_rec1 = null;
        if(Session::has('callback_reservation_rec1') && ($request->server('HTTP_REFERER') == SITE_URL.'currently-assigned-line-runs' || $request->server('HTTP_REFERER') == SITE_URL.'currently-assigned-line-runs/'.Session::get('callback_reservation_rec1'))) {
            $reservation_rec1 = Session::get('callback_reservation_rec1');
        }
        if($reservation_rec1 == null) {
            return View('frontend.404', array('title' => 'Page not found'));
        }
        Session::forget('callback_reservation_rec1');
        return view('frontend.customer_reservation.callback_success', array('title' => 'Thank You'));
    }
    
    public function CustomerPendingPayment(Request $request,$id) {
        $inputs = $request->all();
        $reservation_rec1 = $id;
        $reservation_record = Reservations::find($reservation_rec1);

        if(!$reservation_record){
            return View('frontend.404', array('title' => 'Page not found'));
        }

        if($reservation_record->e_shuttle_type=="Shared"){
            if($reservation_record->e_reservation_status=="Pending Payment"){
                $allow_another_payment = 0;
                $selected_linerun_first_leg = ReservationLeg::select('id','i_run_id')->with('LineRune')->where('i_reservation_id',$reservation_rec1)->first();
                
                if($selected_linerun_first_leg) {
                    $selected_linerun_first_leg = $selected_linerun_first_leg->toArray();
                    $refund_compare_date_time = strtotime($selected_linerun_first_leg['line_rune']['d_run_date']." ".$selected_linerun_first_leg['line_rune']['t_scheduled_arr_time']);
                } else {
                    $refund_compare_date_time = ($reservation_record) ? strtotime($reservation_record->d_travel_date) : strtotime(date('Y-m-d H:i'));
                }
                
                $total_hours = round(($refund_compare_date_time - strtotime(date('Y-m-d H:i'))) / (60*60),2);
                if($total_hours < 48) {
                    return View('frontend.404', array('title' => 'Payment Link Expired','expired' => 1));
                }
            } else {
                return View('frontend.404', array('title' => 'Payment Link Expired','expired' => 1));
            }
        } else {
            // code for private shuttle conditions
            $request_confirmed_time = strtotime($reservation_record->updated_at);
            $current_time = strtotime(date('Y-m-d H:i:s'));
            $payment_within = round(($current_time - $request_confirmed_time) / (60*60),2);
            $flight_time = strtotime($reservation_record->d_travel_date." ".$reservation_record->t_flight_time);
            $comfort_time = strtotime($reservation_record->d_travel_date." ".$reservation_record->t_comfortable_time);
            
            if($reservation_record->e_reservation_status!="Request Confirmed" || $payment_within > 24 || $current_time > $flight_time || $current_time > $comfort_time){
                return View('frontend.404', array('title' => 'Payment Link Expired','expired' => 1));
            }
        }

        $reservation_record2 = Reservations::where('i_parent_id',$reservation_rec1)->first();
        $reservation_rec2 = null;
        
        if($reservation_record2){
            $reservation_rec2 = $reservation_record2->id;
        }

        $total_payment = (isset($reservation_record2) && $reservation_record2->d_total_fare) ? $reservation_record->d_total_fare + $reservation_record2->d_total_fare : $reservation_record->d_total_fare;
        
        $stripe_customer = null;        
            
        $customer = Customers::find($reservation_record->i_customer_id);
        if(!empty($customer) && $customer->customer_stripe_id != '') {
            try {
                \Stripe\Stripe::setApiKey(STRIP_API_KEY);
                $stripe_customer = \Stripe\Customer::retrieve($customer->customer_stripe_id);

            } catch (\Stripe\Exception\InvalidRequest $e) {
                $body = $e->getJsonBody();
                throw new ApplicationException($e->getMessage());
            } catch(\Stripe\Exception\CardException $e) {
                $body = $e->getJsonBody();
                throw new ApplicationException($e->getMessage());
            } catch (\Stripe\Exception\Authentication $e) {
                $body = $e->getJsonBody();
                throw new ApplicationException($e->getMessage());
            } catch (\Stripe\Exception\Permission $e) {
                $body = $e->getJsonBody();
                throw new ApplicationException($e->getMessage());
            } catch (\Stripe\Exception\Card $e) {
                $body = $e->getJsonBody();
                throw new ApplicationException($e->getMessage());
            } catch (\Stripe\Exception\RateLimit $e) {
                $body = $e->getJsonBody();
                throw new ApplicationException($e->getMessage());
            } catch (\Stripe\Exception\Api $e) {
                $body = $e->getJsonBody();
                throw new ApplicationException($e->getMessage());
            } catch (\Stripe\Exception\Subscription $e) {
                $body = $e->getJsonBody();
                throw new ApplicationException($e->getMessage());
            } catch (\Stripe\Exception\Customer $e) {
                $body = $e->getJsonBody();
                throw new ApplicationException($e->getMessage());
            } catch (Exception $e) {
                $body = $e->getJsonBody();
                throw new ApplicationException($e->getMessage());
            }
        }

        if(!empty($inputs)) {
            $is_ufwb = (isset($inputs['ufwb']) && $inputs['ufwb']==1) ? $inputs['ufwb'] : 0; 

            // Apply discount coupon code
            $discount = $discount_code = null;

            if(isset($inputs['v_discount_code']) && $inputs['v_discount_code'] != '') {
                $today_date = date('Y-m-d');
                $trip_type = $reservation_record->e_class_type == 'OW' ? 'One Way' : 'Round Trip';
                if(auth()->guard('customers')->check()) {
                    $coupon_code = Offers::where('v_coupon_code',$inputs['v_discount_code'])->where('d_start_date','<=',$today_date)->where('d_expire_date','>',$today_date)->where('e_type','<>','Employee')->whereIn('e_trip_type',[$trip_type,'Both'])->where('e_status','Active')->first();
                } else if(auth()->guard('admin')->check()) {
                    $coupon_code = Offers::where('v_coupon_code',$inputs['v_discount_code'])->where('d_start_date','<=',$today_date)->where('d_expire_date','>',$today_date)->where('e_type','Employee')->whereIn('e_trip_type',[$trip_type,'Both'])->where('e_status','Active')->first();
                }
                if($coupon_code) {
                    $discount_code = $inputs['v_discount_code'];
                    if($coupon_code->f_discount_percentage) {
                        $discount = ($total_payment * $coupon_code->f_discount_percentage) / 100;
                        $total_payment = $total_payment - $discount;
                    } else {
                        $discount = ($coupon_code->d_discount_flat_price);
                        $total_payment = $total_payment - $discount;
                    }

                    // Code to manage if user applies 100% discount
                    if($total_payment <= 0) {
                        $reservation_record->e_reservation_status = 'Booked';
                        $reservation_record->v_discount_code = $discount_code;
                        $reservation_record->d_discount_price = $discount;
                        $reservation_record->d_total_fare = $discount;
                        $reservation_record->save();
                        if($reservation_record2) {
                            $reservation_record2->v_discount_code = $discount_code;
                            $reservation_record2->d_discount_price = $discount;
                            $reservation_record2->d_total_fare = $discount;
                            $reservation_record2->e_reservation_status = 'Booked';
                            $reservation_record2->save();
                        }
                        return response()->json([
                            'status' => 'TRUE',
                            'redirect_url' => FRONTEND_URL.'payment/success',
                        ]);
                    }
                }
            }
            // end apply discount coupon code

            $rules = $attributeNames = [];
            if($is_ufwb == 0) {
                $rules = [
                    'v_card_name' => 'required',
                    'i_card_num' => 'required',               
                    'i_card_exp_month' => 'required',   
                    'i_card_exp_year' => 'required',               
                    'i_cvc' => 'required',                
                ];

                $attributeNames = [
                    'v_card_name' => 'Card holder name',
                    'i_card_num' => 'Card Number',
                    'i_card_exp_month' => 'Expiry Month',
                    'i_card_exp_year' => 'Expiry Year',
                    'i_cvc' => 'Cvv',
                ];
            }

            $validator = Validator::make($inputs, $rules);
            $validator->setAttributeNames($attributeNames);

            if ($validator->fails()) {
                return json_encode($validator->errors());
            } else {
                $reservation_record = Reservations::find($reservation_rec1);
                if($reservation_rec2) {
                    $reservation_record_rt = Reservations::find($reservation_rec2);
                }
                // Code to use wallet balance if available
                if(isset($inputs['use_wallet']) && $inputs['use_wallet']==1 && $customer) {
                    if($is_ufwb && $total_payment <= $customer->d_wallet_balance){
                        if($discount && $discount_code && $reservation_record->e_class_type=='OW'){
                            $reservation_record->v_discount_code = $discount_code;
                            $reservation_record->d_discount_price = $discount;
                            $reservation_record->d_total_fare = $reservation_record->d_total_fare - $discount;
                            $reservation_record->save();
                        }

                        $reservation_record->e_reservation_status = "Booked";
                        $reservation_record->save();

                        $trans = new Transactions;
                        $trans->i_customer_id = $customer->id;
                        $trans->v_stripe_payment_id = '';
                        $trans->i_reservation_id = $reservation_rec1;
                        $trans->d_amount = $reservation_record->d_total_fare;
                        $trans->e_status = "Success";
                        $trans->e_type = "Booked-Wallet";
                        $trans->save();

                        if($reservation_rec2) {
                            $reservation_record_rt->e_reservation_status = "Booked";
                            $reservation_record_rt->save();

                            if($discount && $discount_code){
                                $reservation_record_rt->v_discount_code = $discount_code;
                                $reservation_record_rt->d_discount_price = $discount;
                                $reservation_record_rt->d_total_fare = $reservation_record->d_total_fare - $discount;
                                $reservation_record_rt->save();
                            }

                            $trans = new Transactions;
                            $trans->i_customer_id = $customer->id;
                            $trans->v_stripe_payment_id = '';
                            $trans->i_reservation_id = $reservation_rec2;
                            $trans->d_amount = $reservation_record_rt->d_total_fare;
                            $trans->e_status = "Success";
                            $trans->e_type = "Booked-Wallet";
                            $trans->save();
                        }
                        $customer->d_wallet_balance = $customer->d_wallet_balance - $total_payment;
                        $customer->save();
                        return response()->json([
                            'status' => 'TRUE',
                            'redirect_url' => FRONTEND_URL.'payment/success',
                        ]);
                    } else {
                        $total_payment = $total_payment - $customer->d_wallet_balance;
                    }
                }

                

                if($stripe_customer && $stripe_customer->sources->total_count > 0) {
                    foreach ($stripe_customer->sources->data as $cardDetail){
                        $input_card_num_4digits = substr($inputs['i_card_num'], -4);
                        if($input_card_num_4digits == $cardDetail['last4'] && $inputs['i_card_exp_month'] == $cardDetail['exp_month'] && $inputs['i_card_exp_year'] == $cardDetail['exp_year']) {
                            $inputs['stripe_card_id'] = $cardDetail['id'];
                        }
                    }
                }
                
                try {
                
                    \Stripe\Stripe::setApiKey(STRIP_API_KEY);
                    $card_id = $card_info = $stripe_customer = null;
                    
                    if($customer->customer_stripe_id != '') {
                        $stripe_customer = \Stripe\Customer::retrieve($customer->customer_stripe_id);
                        
                        if(isset($inputs['stripe_card_id']) && trim($inputs['stripe_card_id'])!='') {
                            $card_info = \Stripe\Customer::retrieveSource($customer->customer_stripe_id,
                            trim($inputs['stripe_card_id']),[]);
                            
                            if($card_info) {
                                $stripe_customer->default_source = trim($inputs['stripe_card_id']);
                                $stripe_customer->save();
                                $card_id = trim($inputs['stripe_card_id']);
                            }
                        }

                    }

                    if(!$stripe_customer) {
                        $email = $customer->v_email;
                        $stripe_customer = \Stripe\Customer::create(array(
                            'source'   => $card_id,
                            'email'    => $email,
                        ));

                        $customer->customer_stripe_id = $stripe_customer->id;
                        $customer->save();
                    }

                    if(!$card_info) {
                        $myCard = array('name' => $inputs['v_card_name'],'number' => $inputs['i_card_num'], 'exp_month' => $inputs['i_card_exp_month'], 'exp_year' => $inputs['i_card_exp_year'],'cvc' => $inputs['i_cvc']);
                        
                        $response = \Stripe\Token::create(array(
                            "card" => $myCard,
                        ));

                        $card_id = $response->id;

                        $source = \Stripe\Customer::createSource(
                            $customer->customer_stripe_id,
                            [
                            'source' => $card_id,
                            ]
                        );
                        
                        \Stripe\Customer::update(
                            $customer->customer_stripe_id,
                            [
                            'default_source' => $source->id,
                            ]
                        );
                    }
    
                    $amount = $total_payment * 100;
                    
                    $charge = \Stripe\Charge::create(array(
                        'customer' => $customer->customer_stripe_id,
                        'amount' => $amount, 
                        'currency' => 'usd', 
                        'metadata' => array('order_id' => $reservation_record->id)
                    ));

                    
                    if($charge->paid) {
                        $use_wallet_bal = 0;
                        if(isset($inputs['use_wallet']) && $inputs['use_wallet']==1 && $customer){
                            $use_wallet_bal = $customer->d_wallet_balance;

                            $trans = new Transactions;
                            $trans->i_customer_id = $customer->id;
                            $trans->v_stripe_payment_id = $charge->id;
                            $trans->i_reservation_id = $reservation_rec1;
                            $trans->d_amount = ($reservation_rec2 && $use_wallet_bal > 0) ? $use_wallet_bal / 2 : $use_wallet_bal;
                            $trans->e_status = "Success";
                            $trans->e_type = 'Booked-Wallet';
                            $trans->save();

                            if($reservation_rec2 && $use_wallet_bal > 0){
                                $trans = new Transactions;
                                $trans->i_customer_id = $customer->id;
                                $trans->v_stripe_payment_id = $charge->id;
                                $trans->i_reservation_id = $reservation_rec2;
                                $trans->d_amount = $use_wallet_bal / 2;
                                $trans->e_status = "Success";
                                $trans->e_type = 'Booked-Wallet';
                                $trans->save();
                            }

                            $customer->d_wallet_balance = 0;
                            $customer->save();
                            $trans_type = "Booked";
                        } else {            
                            $trans_type = "Booked";                    
                        }

                        $reservation_record = Reservations::find($reservation_rec1);
                        $reservation_record->e_reservation_status = 'Booked';
                        if($discount && $discount_code && $reservation_record->e_class_type=='OW'){
                            $reservation_record->v_discount_code = $discount_code;
                            $reservation_record->d_discount_price = $discount;
                            $reservation_record->d_total_fare = $reservation_record->d_total_fare - $discount;
                        }
                        $reservation_record->save();

                        $trans = new Transactions;
                        $trans->i_customer_id = $customer->id;
                        $trans->v_stripe_payment_id = $charge->id;
                        $trans->i_reservation_id = $reservation_rec1;
                        $trans->d_amount = ($reservation_rec2 && $use_wallet_bal > 0) ? ($reservation_record->d_total_fare - ($use_wallet_bal / 2)) : ($reservation_record->d_total_fare - $use_wallet_bal);
                        $trans->e_status = "Success";
                        $trans->e_type = $trans_type;
                        $trans->save();
                        

                        if($reservation_rec2) {
                            if($discount && $discount_code){
                                $reservation_record_rt->v_discount_code = $discount_code;
                                $reservation_record_rt->d_discount_price = $discount;
                                $reservation_record_rt->d_total_fare = $reservation_record_rt->d_total_fare - $discount;
                            }
                            $reservation_record_rt->e_reservation_status = 'Booked';
                            $reservation_record_rt->save();

                            $trans = new Transactions;
                            $trans->i_customer_id = $customer->id;
                            $trans->v_stripe_payment_id = $charge->id;
                            $trans->i_reservation_id = $reservation_rec2;
                            $trans->d_amount = ($use_wallet_bal > 0) ? ($reservation_record_rt->d_total_fare - ($use_wallet_bal / 2)) : $reservation_record_rt->d_total_fare;
                            $trans->e_status = "Success";
                            $trans->e_type = $trans_type;
                            $trans->save();
                        }
                        Session::forget('pending_payment_email_'.$reservation_rec1);
                        if($id != '') {
                            Session::put('pp_reservation_rec1',$reservation_rec1);
                        }
                    }
                    // -----------------------------------------
                    
                    return response()->json([
                        'status' => 'TRUE',
                        'redirect_url' => FRONTEND_URL.'payment/success',
                    ]);

                } catch (\Stripe\Exception\CardException $e) {
                    return response()->json(['status' => 'FALSE', 'message' => $e->getMessage()]);
                } catch (\Stripe\Exception\InvalidRequestException $e) {
                    return response()->json(['status' => 'FALSE', 'message' => $e->getMessage()]);
                } catch (\Stripe\Exception\AuthenticationException $e) {
                    return response()->json(['status' => 'FALSE', 'message' => $e->getMessage()]);
                } catch (\Stripe\Exception\RateLimitException $e) {
                    return response()->json(['status' => 'FALSE', 'message' => $e->getMessage()]);
                } catch (\Stripe\Exception\ApiConnectionException $e) {
                    return response()->json(['status' => 'FALSE', 'message' => $e->getMessage()]);
                } catch (\Stripe\Exception\ApiErrorException $e) {
                    return response()->json(['status' => 'FALSE', 'message' => $e->getMessage()]);
                } catch (Exception $e) {
                    return response()->json(['status' => 'FALSE', 'message' => $e->getMessage()]);
                }
            }    
        } else {
            $discountPrice = 0.00;
            return view('frontend.customer_reservation.pending_payment', array('title' => 'Payment','stripe_customer' => $stripe_customer, 'reservation_record' => $reservation_record,'reservation_record_rt' => $reservation_record2,'total_payment' => $total_payment,'current_user' => $customer,'discountPrice' => $discountPrice));
        }
        
    }

    /* public function customerAutoComplete (Request $request) {
        $data = $request->all();
        $name = $data['name'];
        if(isset($data['name']) && $data['name'] != ''){

            $tag = Customers::where('v_email','LIKE',"%".$name."%")->select('id', 'v_email')->groupBy('v_email')->groupBy('id')->get()->toArray();
            return json_encode($tag);
        }
    }

    public function getCustomerData(Request $request) {
        $data = $request->all();
       
        if(isset($data['id']) && $data['id'] != '') {
            $record = Customers::where('id', $data['id'])->first();            
            if(!empty($record)) {
                return json_encode(['status' => 'TRUE', 'data' => $record]);
            } else {
                return json_encode(['status' => 'FALSE']);  
            }
        } else {
            return json_encode(['status' => 'FALSE']);  
        }
                
    } */

    public function getLineRunData(Request $request, $id = '') {
        $data = $request->all();
        if($data) {
            $paymentStatus = false;
            $reservation_id = $reservation_rt_id = '';
            if($id != '') {
                $reservation_record = Reservations::find($id);
                $reservation_record_rt = Reservations::where('i_parent_id', $id)->first();
                $paymentStatus = $this->getPaymentStatus($reservation_record, $reservation_record_rt);
                $reservation_id = $reservation_record->id;         
                if(!empty($reservation_record_rt)) {
                    $reservation_rt_id = $reservation_record_rt->id;
                }
            }

            $checkDates = array();
            /* $origin_service_ow = $this->getServiceID($data['i_origin_service_area_id'], $data['i_dest_service_area_id']); */
            $departAvailable = $returnAvailable = 0;
            $line_run_data_departur = LineRun::select('*',DB::raw('IFNULL((select sum(i_total_num_passengers) from reservations as res inner join reservation_leg res_leg on res.id=res_leg.i_reservation_id where (res.e_reservation_status="Booked" OR res.e_reservation_status="Pending Payment") and res.deleted_at is null and res_leg.deleted_at is null and res_leg.i_run_id = linerun.id and res_leg.i_reservation_id != "'.$reservation_id.'"),0) as i_num_booked_seats'))/* ->whereRaw("IFNULL((select sum(i_total_num_passengers) from reservations as res inner join reservation_leg res_leg on res.id = res_leg.i_reservation_id where (res.e_reservation_status = 'Booked' OR res.e_reservation_status = 'Pending Payment') and res.deleted_at is null and res_leg.deleted_at is null and res_leg.i_run_id = linerun.id and res_leg.i_reservation_id != '".$reservation_id."'),0) < i_num_available") */->where(DB::raw('DATE(d_run_date)'), '=', trim(date('Y-m-d', strtotime(trim($data['d_depart_date'])))))->where(['i_origin_service_area_id' => $data['i_origin_service_area_id'],'i_dest_service_area_id' => $data['i_dest_service_area_id']])->with(['VehicleFleet' => function($q) {
                $q->select('*')->with(['get_vehicle_specification' => function($qa){
                    $qa->select('i_vehicle_id','v_make', 'v_model', 'v_series', 'v_vehicle_type');
                }]);
            }])->whereHas('VehicleFleet')->whereHas('VehicleFleet.get_vehicle_specification')->where('e_run_status', 'Open')->orderBy('t_scheduled_arr_time', 'ASC')->get()->toArray();

            if(count($line_run_data_departur) > 0) {
                foreach($line_run_data_departur as $key => $val) {
                    if(($val['i_num_available'] - $val['i_num_booked_seats']) >= $data['passengers_details']) {
                        $departAvailable ++;
                    }
                }
            }

            $checkDates[] = date('Y-m-d', strtotime(trim($data['d_depart_date'])));

            $line_run_data_return = array();
            if($data['type_of_trip'] == 'RT') {
                
                $line_run_data_return = LineRun::select('*',DB::raw('IFNULL((select sum(i_total_num_passengers) from reservations as res inner join reservation_leg res_leg on res.id=res_leg.i_reservation_id where (res.e_reservation_status="Booked" OR res.e_reservation_status="Pending Payment") and res.deleted_at is null and res_leg.deleted_at is null and res_leg.i_run_id = linerun.id and res_leg.i_reservation_id != "'.$reservation_rt_id.'"),0) as i_num_booked_seats'))/* ->whereRaw("IFNULL((select sum(i_total_num_passengers) from reservations as res inner join reservation_leg res_leg on res.id=res_leg.i_reservation_id where (res.e_reservation_status='Booked' OR res.e_reservation_status='Pending Payment') and res.deleted_at is null and res_leg.deleted_at is null and res_leg.i_run_id = linerun.id and res_leg.i_reservation_id != '".$reservation_rt_id."'),0) < i_num_available") */->where(DB::raw('DATE(d_run_date)'), '=', trim(date('Y-m-d', strtotime(trim($data['d_return_date'])))))->where(['i_origin_service_area_id'=>$data['i_dest_service_area_id'],'i_dest_service_area_id'=>$data['i_origin_service_area_id']])->with(['VehicleFleet' => function($q) {
                    $q->with(['get_vehicle_specification' => function($qa){
                        $qa->select('i_vehicle_id','v_make', 'v_model', 'v_series', 'v_vehicle_type');
                    }]);
                }])->whereHas('VehicleFleet')->whereHas('VehicleFleet.get_vehicle_specification')->where('e_run_status', 'Open')->orderBy('t_scheduled_arr_time', 'ASC')->get()->toArray();

                if(count($line_run_data_return) > 0) {
                    foreach($line_run_data_return as $key => $val) {
                        if(($val['i_num_available'] - $val['i_num_booked_seats']) >= $data['passengers_details']) {
                            $returnAvailable ++;
                        }
                    }
                }

                $checkDates[] = date('Y-m-d', strtotime(trim($data['d_return_date'])));
            }

            $dateNotice = BlackoutDate::whereIn(DB::raw('DATE(d_blackout_date)'), $checkDates)->get()->toArray();
            
            return View('frontend.customer_reservation.linerun_data', array('departure_data' => $line_run_data_departur, 'return_data' => $line_run_data_return, 'type_of_trip' => $data['type_of_trip'], 'paymentStatus' => $paymentStatus, 'passengerCount' => $data['passengers_details'], 'departAvailable' => $departAvailable, 'returnAvailable' => $returnAvailable, 'dateNotice' => $dateNotice, 'departure_location' => $data['departure_location'], 'return_location' => $data['return_location']));
       }
    }
    
    public function getAirlineData(Request $request) {
        $data = $request->all();

        if(isset($data['q']['term'])) {
            $airlines = SystemIcaoDef::select('id','v_airline_name as text')->where('e_status','Active')->where('v_airline_name','LIKE','%'.$data['q']['term'].'%')->get()->toArray();

            return Response::Json($airlines);
        }
    }

    public function applyDiscountCoupon(Request $request, $id = '') {
        $inputs = $request->all();
        if($id != '') {
            $reservation_rec1 = $id;
            $reservation_record = Reservations::find($id);
            $reservation_record_rt = Reservations::where('i_parent_id', $id)->first();
        } else {
            $reservation_rec1 = Session::get('reservation_rec1');
            $reservation_rec2 = Session::get('reservation_rec2');
            $reservation_record = Reservations::find($reservation_rec1);
            $reservation_record_rt = Reservations::where('i_parent_id', $reservation_rec1)->first();
        }
        
        if($inputs) {
            $today_date = date('Y-m-d');
            
            $trip_type = $reservation_record['e_class_type'] == 'OW' ? 'One Way' : 'Round Trip';
            
            if($reservation_record['e_class_type'] == 'OW') {
                $total_fare = $this->calculateTotalFare($reservation_rec1);
            } else {
                if($id != '') {
                    $reservation_rec2 = $reservation_record_rt->id;
                    $total_fare = $this->calculateTotalFare($reservation_rec1, $reservation_rec2);
                } else {
                    $total_fare = $this->calculateTotalFare($reservation_rec1, $reservation_rec2);
                }
            }

            $coupon_code = null;
            
            if(auth()->guard('customers')->check()) {
                $coupon_code = Offers::where('v_coupon_code', $inputs['v_discount_code'])->where('d_start_date','<=',$today_date)->where('d_expire_date','>', $today_date)->where('e_type','<>','Employee')->whereIn('e_trip_type',[$trip_type,'Both'])->where('e_status','Active')->first();
            } else if(auth()->guard('admin')->check()) {
                $coupon_code = Offers::where('v_coupon_code', $inputs['v_discount_code'])->where('d_start_date','<=',$today_date)->where('d_expire_date','>', $today_date)->where('e_type','Employee')->whereIn('e_trip_type',[$trip_type,'Both'])->where('e_status','Active')->first();
            }

            $discount = 0.00;
            
            if($coupon_code) {
                $status = 'TRUE';
                if($coupon_code->f_discount_percentage) {
                    $discount = ($total_fare * $coupon_code->f_discount_percentage) / 100;
                    $total_fare = $total_fare - $discount;
                } else {
                    $discount = $coupon_code->d_discount_flat_price;
                    $total_fare = $total_fare - $discount;
                }
            } else {
                $status = 'FALSE';
            }

            return response()->json([
                'status' => $status,
                'total' => '$'.number_format($total_fare,2),
                'discount' => '$'.number_format($discount,2)
            ]);
        }
    }

    public function downloadReservationData(Request $request, $id) {
        $inputs = $request->all();
        
        $reservation_record = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory','PickupCity','DropOffCity'])->where(['id' => $id])->first();
     
        if($reservation_record) {
            $reservation_luggage_info = ReservationLuggageInfo::with(['SystemLuggageDef'])->where('i_reservation_id', $id)->get()->toArray();
            $reservation_pet_info = ReservationLuggageInfo::with(['SystemAnimalDef'])->where('i_reservation_id', $id)->get()->toArray();
            $reservation_luggage_info_total = ReservationLuggageInfo ::where('i_reservation_id', $id)->get()->sum('d_price');
        
            $payment_info = Transactions::where('i_reservation_id',$id)->whereIn('e_type',['Booked','Booked-Wallet','Cash-On-Board'])->orderBy('created_at','DESC')->get()->toArray();
            $payment_mode = $this->getPaymentMode($id);
            $total_fare_amount = ReservationTravellerInfo::where('i_reservation_id', $id)->select(DB::raw('SUM(d_fare_amount) AS total'), DB::raw('SUM(IF(e_type = "Adult", d_fare_amount, 0)) AS adult_total'), DB::raw('SUM(IF(e_type = "Child", d_fare_amount, 0)) AS child_total'),DB::raw('SUM(IF(e_type = "Senior", d_fare_amount, 0)) AS senior_total'),DB::raw('SUM(IF(e_type = "Military", d_fare_amount, 0)) AS military_total'),DB::raw('SUM(IF(e_type = "Infant", d_fare_amount, 0)) AS infant_total'), DB::raw('SUM(IF(e_type = "Adult", 1, 0)) AS adult_count'), DB::raw('SUM(IF(e_type = "Child", 1, 0)) AS child_count'), DB::raw('SUM(IF(e_type = "Senior", 1, 0)) AS senior_count'), DB::raw('SUM(IF(e_type = "Military", 1, 0)) AS military_count'), DB::raw('SUM(IF(e_type = "Infant", 1, 0)) AS infant_count'))->first()->toArray();
            
            $reservation_luggage_info_total_rt = "";
            $reservation_record_rt = $reservation_luggage_info_rt = $reservation_pet_info_rt = $total_fare_amount_rt = array();
            $res1_tt_text = $this->getTravelTypeText($reservation_record->i_reservation_category_id,$reservation_record->i_dropoff_point_type_id);
            $res2_tt_text = false;
            if($reservation_record['e_class_type'] == 'RT'){
                $reservation_record_get_id = Reservations::select('id')->where('i_parent_id', $id)->first();
                $reservation_rec2 = $reservation_record_get_id['id'];
                $reservation_record_rt = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory','PickupCity','DropOffCity'])->where(['id' => $reservation_rec2])->first();
                $reservation_luggage_info_rt = ReservationLuggageInfo::with(['SystemLuggageDef'])->where('i_reservation_id', $reservation_rec2)->get()->toArray();
                $reservation_pet_info_rt = ReservationLuggageInfo::with(['SystemAnimalDef'])->where('i_reservation_id', $reservation_rec2)->get()->toArray();
                $total_fare_amount_rt = ReservationTravellerInfo::where('i_reservation_id', $reservation_rec2)->select(DB::raw('SUM(d_fare_amount) AS total'), DB::raw('SUM(IF(e_type = "Adult", d_fare_amount, 0)) AS adult_total'), DB::raw('SUM(IF(e_type = "Child", d_fare_amount, 0)) AS child_total'),DB::raw('SUM(IF(e_type = "Senior", d_fare_amount, 0)) AS senior_total'),DB::raw('SUM(IF(e_type = "Military", d_fare_amount, 0)) AS military_total'),DB::raw('SUM(IF(e_type = "Infant", d_fare_amount, 0)) AS infant_total'), DB::raw('SUM(IF(e_type = "Adult", 1, 0)) AS adult_count'), DB::raw('SUM(IF(e_type = "Child", 1, 0)) AS child_count'), DB::raw('SUM(IF(e_type = "Senior", 1, 0)) AS senior_count'), DB::raw('SUM(IF(e_type = "Military", 1, 0)) AS military_count'), DB::raw('SUM(IF(e_type = "Infant", 1, 0)) AS infant_count'))->first()->toArray();
                $reservation_luggage_info_total_rt = ReservationLuggageInfo::where('i_reservation_id', $reservation_rec2)->get()->sum('d_price');
                $res2_tt_text = $this->getTravelTypeText($reservation_record_rt->i_reservation_category_id,$reservation_record_rt->i_dropoff_point_type_id);
            }
            if($reservation_record['e_class_type'] == 'OW') {
                $total_payment = $this->calculateTotalFare($reservation_record['id']);
            } else {
                $total_payment = $this->calculateTotalFare($reservation_record['id'],$reservation_record_rt['id']);
            }

            PDF::setOutputMode('F'); 
            PDF::setPageSize('a4');
            PDF::setOrientation('portrait');
            PDF::setOptions('--disable-smart-shrinking');
            $filename = $reservation_record['v_reservation_number'];
            //return view('frontend.customer_reservation.download-reservation-summary', array('title' => 'Rservation Summary','reservation_record' => $reservation_record,'reservation_record_rt' => $reservation_record_rt,'reservation_luggage_info'=>$reservation_luggage_info,'reservation_pet_info'=>$reservation_pet_info,'reservation_luggage_info_rt'=>$reservation_luggage_info_rt,'reservation_pet_info_rt'=>$reservation_pet_info_rt,'total_fare_amount'=>$total_fare_amount,'total_fare_amount_rt'=>$total_fare_amount_rt,'reservation_luggage_info_total'=>$reservation_luggage_info_total,'reservation_luggage_info_total_rt'=>$reservation_luggage_info_total_rt));
            PDF::html('frontend.customer_reservation.download-reservation-summary', array('title' => 'Reservation Summary','reservation_record' => $reservation_record,'reservation_record_rt' => $reservation_record_rt,'reservation_luggage_info'=>$reservation_luggage_info,'reservation_pet_info'=>$reservation_pet_info,'reservation_luggage_info_rt'=>$reservation_luggage_info_rt,'reservation_pet_info_rt'=>$reservation_pet_info_rt,'total_fare_amount'=>$total_fare_amount,'total_fare_amount_rt'=>$total_fare_amount_rt,'reservation_luggage_info_total'=>$reservation_luggage_info_total,'reservation_luggage_info_total_rt'=>$reservation_luggage_info_total_rt,'total_payment'=>$total_payment,'res1_tt_text' => $res1_tt_text,'res2_tt_text' => $res2_tt_text,'payment_info' => $payment_info,'payment_mode' => $payment_mode),ADMIN_FILES_PATH.$filename);
            
            return Response::download(ADMIN_FILES_PATH.$filename.'.pdf');
        }
        
    }

    public function getReservationPrintData(Request $request, $id) {

        $inputs = $request->all();
        
        $reservation_record = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory','PickupCity','DropOffCity'])->where(['id' => $id])->first();
     
        if($reservation_record){
            $reservation_luggage_info = ReservationLuggageInfo::with(['SystemLuggageDef'])->where('i_reservation_id', $id)->get()->toArray();
            $reservation_pet_info = ReservationLuggageInfo::with(['SystemAnimalDef'])->where('i_reservation_id', $id)->get()->toArray();
            $reservation_luggage_info_total = ReservationLuggageInfo ::where('i_reservation_id', $id)->get()->sum('d_price');
        
            $payment_info = Transactions::where('i_reservation_id',$id)->whereIn('e_type',['Booked','Booked-Wallet','Cash-On-Board'])->orderBy('created_at','DESC')->get()->toArray();
            $payment_mode = $this->getPaymentMode($id);
            $total_fare_amount = ReservationTravellerInfo::where('i_reservation_id', $id)->select(DB::raw('SUM(d_fare_amount) AS total'), DB::raw('SUM(IF(e_type = "Adult", d_fare_amount, 0)) AS adult_total'), DB::raw('SUM(IF(e_type = "Child", d_fare_amount, 0)) AS child_total'),DB::raw('SUM(IF(e_type = "Senior", d_fare_amount, 0)) AS senior_total'),DB::raw('SUM(IF(e_type = "Military", d_fare_amount, 0)) AS military_total'),DB::raw('SUM(IF(e_type = "Infant", d_fare_amount, 0)) AS infant_total'), DB::raw('SUM(IF(e_type = "Adult", 1, 0)) AS adult_count'), DB::raw('SUM(IF(e_type = "Child", 1, 0)) AS child_count'), DB::raw('SUM(IF(e_type = "Senior", 1, 0)) AS senior_count'), DB::raw('SUM(IF(e_type = "Military", 1, 0)) AS military_count'), DB::raw('SUM(IF(e_type = "Infant", 1, 0)) AS infant_count'))->first()->toArray();

            $reservation_luggage_info_total_rt = "";
            $reservation_record_rt = $reservation_luggage_info_rt = $reservation_pet_info_rt = $total_fare_amount_rt = array();
            $res1_tt_text = $this->getTravelTypeText($reservation_record->i_reservation_category_id,$reservation_record->i_dropoff_point_type_id);
            $res2_tt_text = false;
            if($reservation_record['e_class_type'] == 'RT'){
                $reservation_record_get_id = Reservations::select('id')->where('i_parent_id', $id)->first();
                $reservation_rec2 = $reservation_record_get_id['id'];
                $reservation_record_rt = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory','PickupCity','DropOffCity'])->where(['id'=>$reservation_rec2])->first();
                $reservation_luggage_info_rt = ReservationLuggageInfo::with(['SystemLuggageDef'])->where('i_reservation_id', $reservation_rec2)->get()->toArray();
                $reservation_pet_info_rt = ReservationLuggageInfo::with(['SystemAnimalDef'])->where('i_reservation_id', $reservation_rec2)->get()->toArray();
                $total_fare_amount_rt = ReservationTravellerInfo::where('i_reservation_id', $reservation_rec2)->select(DB::raw('SUM(d_fare_amount) AS total'), DB::raw('SUM(IF(e_type = "Adult", d_fare_amount, 0)) AS adult_total'), DB::raw('SUM(IF(e_type = "Child", d_fare_amount, 0)) AS child_total'),DB::raw('SUM(IF(e_type = "Senior", d_fare_amount, 0)) AS senior_total'),DB::raw('SUM(IF(e_type = "Military", d_fare_amount, 0)) AS military_total'),DB::raw('SUM(IF(e_type = "Infant", d_fare_amount, 0)) AS infant_total'), DB::raw('SUM(IF(e_type = "Adult", 1, 0)) AS adult_count'), DB::raw('SUM(IF(e_type = "Child", 1, 0)) AS child_count'), DB::raw('SUM(IF(e_type = "Senior", 1, 0)) AS senior_count'), DB::raw('SUM(IF(e_type = "Military", 1, 0)) AS military_count'), DB::raw('SUM(IF(e_type = "Infant", 1, 0)) AS infant_count'))->first()->toArray();
                $reservation_luggage_info_total_rt = ReservationLuggageInfo ::where('i_reservation_id', $reservation_rec2)->get()->sum('d_price');
                $res2_tt_text = $this->getTravelTypeText($reservation_record_rt->i_reservation_category_id,$reservation_record_rt->i_dropoff_point_type_id);
            }
            if($reservation_record['e_class_type'] == 'OW') {
                $total_payment = $this->calculateTotalFare($reservation_record['id']);
            } else {
                $total_payment = $this->calculateTotalFare($reservation_record['id'],$reservation_record_rt['id']);
            }
           return view('frontend.customer_reservation.download-reservation-summary', array('title' => 'Rservation Summary','reservation_record' => $reservation_record,'reservation_record_rt' => $reservation_record_rt,'reservation_luggage_info'=>$reservation_luggage_info,'reservation_pet_info'=>$reservation_pet_info,'reservation_luggage_info_rt'=>$reservation_luggage_info_rt,'reservation_pet_info_rt'=>$reservation_pet_info_rt,'total_fare_amount'=>$total_fare_amount,'total_fare_amount_rt'=>$total_fare_amount_rt,'reservation_luggage_info_total'=>$reservation_luggage_info_total,'reservation_luggage_info_total_rt'=>$reservation_luggage_info_total_rt,'total_payment'=>$total_payment,'res1_tt_text' => $res1_tt_text,'res2_tt_text' => $res2_tt_text,'payment_info' => $payment_info,'payment_mode' => $payment_mode));
            //return Response::download(ADMIN_FILES_PATH.$filename.'.pdf');
        }
      
       

    }

    public function getConfirmLineRunData(Request $request, $id = '') {
        $data = $request->all();
        if($id != '') {
            $reservation_record = Reservations::where('id',$id)->with(['PickupCity' => function($q) {
                $q->select('id', 'v_city');
            }])->first();
            $reservation_record_rt = Reservations::where('i_parent_id', $id)->with(['PickupCity' => function($q) {
                $q->select('id', 'v_city');
            }])->first();
            
        } else {
            $reservation_rec1 = Session::get('reservation_rec1');
            $reservation_rec2 = Session::get('reservation_rec2');
            $reservation_record = Reservations::where('id',$reservation_rec1)->with(['PickupCity' => function($q) {
                $q->select('id', 'v_city');
            }])->first();
            $reservation_record_rt = Reservations::where('id',$reservation_rec2)->with(['PickupCity' => function($q) {
                $q->select('id', 'v_city');
            }])->first();
        }
        $departPassengerCount = $returnPassengerCount = 0;
        $departAvailable = $returnAvailable = 0;
        $checkDates = array();
        if(!empty($reservation_record)) {
            $paymentStatus = $this->getPaymentStatus($reservation_record, $reservation_record_rt);
            $departPassengerCount = $reservation_record->i_total_num_passengers;
            $serviceIds = $this->getServiceID($reservation_record->i_pickup_city_id, $reservation_record->i_dropoff_city_id);
            $reservation_id = $reservation_record->id;       
            
            $departure_location = $reservation_record->PickupCity->v_city;            
            $checkDates[] = date('Y-m-d', strtotime(trim($reservation_record['d_travel_date'])));

            $line_run_data_departur = LineRun::select('*',DB::raw('IFNULL((select sum(i_total_num_passengers) from reservations as res inner join reservation_leg res_leg on res.id=res_leg.i_reservation_id where (res.e_reservation_status="Booked" OR res.e_reservation_status="Pending Payment") and res.deleted_at is null and res_leg.deleted_at is null and res_leg.i_run_id = linerun.id and res_leg.i_reservation_id != "'.$reservation_id.'"),0) as i_num_booked_seats'))/* ->whereRaw("IFNULL((select sum(i_total_num_passengers) from reservations as res inner join reservation_leg res_leg on res.id=res_leg.i_reservation_id where (res.e_reservation_status='Booked' OR res.e_reservation_status='Pending Payment') and res.deleted_at is null and res_leg.deleted_at is null and res_leg.i_run_id = linerun.id and res_leg.i_reservation_id != '".$reservation_id."'),0) < i_num_available") */->where(DB::raw('DATE(d_run_date)'), '=', trim(date('Y-m-d', strtotime(trim($reservation_record['d_travel_date'])))))->where(['i_origin_service_area_id' => $serviceIds['home_pickup_service_id'],'i_dest_service_area_id' => $serviceIds['home_dropoff_service_id']])->with(['VehicleFleet' => function($q) {
                $q->with(['get_vehicle_specification' => function($p){
                    $p->select('i_vehicle_id','v_make', 'v_model', 'v_series', 'v_vehicle_type');
                }]);
            }])->whereHas('VehicleFleet')->whereHas('VehicleFleet.get_vehicle_specification')->where('e_run_status', 'Open');

            if(isset($data['first_leg_dir']) && $data['first_leg_dir'] == 'Pick') {
                $line_run_data_departur = $line_run_data_departur->where('t_scheduled_arr_time','>',$reservation_record['t_comfortable_time'])->orderBy('t_scheduled_arr_time', 'ASC')->get()->toArray();
            } else if(isset($data['first_leg_dir']) && $data['first_leg_dir'] == 'Drop') {
                $line_run_data_departur = $line_run_data_departur->where('t_scheduled_arr_time','<',$reservation_record['t_comfortable_time'])->orderBy('t_scheduled_arr_time', 'DESC')->get()->toArray();
            }

            if(count($line_run_data_departur) > 0) {
                foreach($line_run_data_departur as $key => $val) {
                    if(($val['i_num_available'] - $val['i_num_booked_seats']) >= $departPassengerCount) {
                        $departAvailable ++;
                    }
                }
            }

            $line_run_data_return = array();
            $return_location = '';
            if($reservation_record['e_class_type'] == 'RT' && $reservation_record['i_parent_id'] == NULL) {
                $reservation_rt_id = $reservation_record_rt['id'];
                $return_location = $reservation_record_rt->PickupCity->v_city;
                $returnPassengerCount = $reservation_record_rt->i_total_num_passengers;
                $line_run_data_return = LineRun::select('*',DB::raw('IFNULL((select sum(i_total_num_passengers) from reservations as res inner join reservation_leg res_leg on res.id=res_leg.i_reservation_id where (res.e_reservation_status="Booked" OR res.e_reservation_status="Pending Payment") and res.deleted_at is null and res_leg.deleted_at is null and res_leg.i_run_id = linerun.id and res_leg.i_reservation_id != "'.$reservation_rt_id.'"),0) as i_num_booked_seats'))->whereRaw("IFNULL((select sum(i_total_num_passengers) from reservations as res inner join reservation_leg res_leg on res.id=res_leg.i_reservation_id where (res.e_reservation_status='Booked' OR res.e_reservation_status='Pending Payment') and res.deleted_at is null and res_leg.deleted_at is null and res_leg.i_run_id = linerun.id and res_leg.i_reservation_id != '".$reservation_rt_id."'),0) < i_num_available")->where(DB::raw('DATE(d_run_date)'), '=', trim(date('Y-m-d', strtotime(trim($reservation_record_rt['d_travel_date'])))))->where(['i_origin_service_area_id' => $serviceIds['home_dropoff_service_id'], 'i_dest_service_area_id' => $serviceIds['home_pickup_service_id']])->with(['VehicleFleet' => function($q) {
                    $q->with(['get_vehicle_specification' => function($qa){
                        $qa->select('i_vehicle_id','v_make', 'v_model', 'v_series', 'v_vehicle_type');
                    }]);
                }])->whereHas('VehicleFleet')->whereHas('VehicleFleet.get_vehicle_specification')->where('e_run_status', 'Open');

                if(isset($data['second_leg_dir']) && $data['second_leg_dir']=='Pick') {
                    $line_run_data_return = $line_run_data_return->where('t_scheduled_arr_time','>',$reservation_record_rt['t_comfortable_time'])->orderBy('t_scheduled_arr_time', 'ASC')->get()->toArray();
                } else if(isset($data['second_leg_dir']) && $data['second_leg_dir']=='Drop') {
                    $line_run_data_return = $line_run_data_return->where('t_scheduled_arr_time','<',$reservation_record_rt['t_comfortable_time'])->orderBy('t_scheduled_arr_time', 'DESC')->get()->toArray();
                }
                
                if(count($line_run_data_return) > 0) {
                    foreach($line_run_data_return as $key => $val) {
                        if(($val['i_num_available'] - $val['i_num_booked_seats']) >= $returnPassengerCount) {
                            $returnAvailable ++;
                        }
                    }
                }
                $checkDates[] = date('Y-m-d', strtotime(trim($reservation_record_rt['d_travel_date'])));
            }

            $dateNotice = BlackoutDate::whereIn(DB::raw('DATE(d_blackout_date)'), $checkDates)->get()->toArray();
            
            $totalFare = $discountPrice = 0;
            $type_of_trip = ($reservation_record['e_class_type'] == 'OW') ? 'OW' : ($reservation_record['i_parent_id'] == NULL ? 'RT' : 'OW');
             
            if($type_of_trip == 'OW' && count($line_run_data_departur) > 0) {
                $totalFare = $this->calculateTotalFare($reservation_record->id);
                $discountPrice = $this->calculateDiscountFare($reservation_record->v_discount_code, 'OW', $totalFare);
            } else if($type_of_trip == 'RT' && count($line_run_data_departur) > 0 && count($line_run_data_return) > 0) {
                $totalFare = $this->calculateTotalFare($reservation_record->id, $reservation_record_rt->id);
                $discountPrice = $this->calculateDiscountFare($reservation_record->v_discount_code, 'RT', $totalFare);
            } else if($type_of_trip == 'RT' && count($line_run_data_departur) > 0) {
                //Departure Trip run only avaiable
                $totalFare = $this->calculateTotalFare($reservation_record->id);
                $discountPrice = $this->calculateDiscountFare($reservation_record->v_discount_code, 'OW', $totalFare);
            } else if($type_of_trip == 'RT' && count($line_run_data_return) > 0) {

                //Return Trip run only avaiable so calculate new fare
                $totalFare = $this->calculateNewTotalFare($reservation_record_rt->id);
                $discountPrice = $this->calculateDiscountFare($reservation_record_rt->v_discount_code, 'OW', $totalFare);

            }           
            
            return View('frontend.customer_reservation.linerun_data_confirm', array('departure_data' => $line_run_data_departur, 'return_data' => $line_run_data_return, 'type_of_trip' => $type_of_trip, 'total_fare' => $totalFare, 'discountPrice' => $discountPrice, 'paymentStatus' => $paymentStatus, 'departPassengerCount' => $departPassengerCount, 'returnPassengerCount' => $returnPassengerCount, 'departAvailable' => $departAvailable, 'returnAvailable' => $returnAvailable, 'dateNotice' => $dateNotice, 'departure_location' => $departure_location, 'return_location' => $return_location));
        }
    }

    public function calculateNewTotalFare($resv_id, $store = false) {
        $total_fare = 0.00;
        
        $reservation_record = Reservations::find($resv_id);
        
        $fare_class_detail_ow = FareClass::where(['e_class_type' => 'OW', 'deleted_at' => NULL])->where('v_class_label', '!=', 'Companion')->get()->toArray();
        $serviceIds = $this->getServiceID($reservation_record->i_pickup_city_id, $reservation_record->i_dropoff_city_id);
        $fare_calc = FareTable::where('i_origin_service_area_id', $serviceIds['home_pickup_service_id'])->where('i_dest_service_area_id', $serviceIds['home_dropoff_service_id'])->select('id', 'i_rate_class_id', 'v_rate_code', 'd_fare_amount')->get()->keyBy('v_rate_code')->toArray();
        $travellers = ReservationTravellerInfo::where('i_reservation_id',$resv_id)->get()->groupBy('e_type')->toArray();
        
        $ff_rate_code = "FFOW";
        $companion_rate_code = "CMOW";    
        $total_travelllers = count($travellers);
        
        $num_ff_adults =  0;
        
        foreach($fare_class_detail_ow as $data) {
            $fare_rate = $fare_rate_code = 0;
            $ff_infants_index = null;
    
            $fareType = $data['v_class_label'];
            $type = ($data['v_class_label'] == 'Full Fare') ? 'Adult' : $data['v_class_label'];
            
            if(isset($travellers[$type]) && count($travellers[$type]) > 0) {
                $fare_class = FareClass::where('e_class_type', 'OW')->where('v_class_label', $fareType)->select('id', 'v_rate_code')->first()->toArray();
                
                if(isset($fare_calc[$fare_class['v_rate_code']])) {
                    $fare_rate = $fare_calc[$fare_class['v_rate_code']]['d_fare_amount'];
                    $fare_rate_code = $fare_class['v_rate_code'];
                }
                
                // Conditions for fare calculations
                if($fareType == 'Full Fare'){
                    
                    $num_ff_adults += count($travellers[$type]);
                    
                    if(isset($fare_calc[$fare_class['v_rate_code']])) {
                        $fare_rate = $fare_calc[$fare_class['v_rate_code']]['d_fare_amount'];
                        $fare_rate_code = $fare_class['v_rate_code'];
                    }
                }
                //Senior or Military single traveller
                if($fareType == 'Senior' || $fareType == 'Military'){
                    if($total_travelllers > 1) {
                        $num_ff_adults += count($travellers[$type]);
                        if(isset($fare_calc[$ff_rate_code])) {
                            $fare_rate = $fare_calc[$ff_rate_code]['d_fare_amount'];
                            $fare_rate_code = $ff_rate_code;
                        }
                    }
                }
        
                //Must be 1 adult/senior/military
                if($fareType == 'Child') {
                    if($num_ff_adults == 0 && count($travellers[$type]) > 0) {
                        if(isset($fare_calc[$ff_rate_code])) {
                            $fare_rate = $fare_calc[$ff_rate_code]['d_fare_amount'];
                            $fare_rate_code = $ff_rate_code;
                        }
                    }
                }
        
                //1 adult 1 infant free , 2-2, 3-3, so on
                if($fareType == 'Infant'){
                    if(count($travellers[$type]) > $num_ff_adults) {
                        $ff_infants_index = count($travellers[$type]) - $num_ff_adults;
                    }
                }
                
                for($i = 0; $i < count($travellers[$type]); $i++) {
                    
                    //Companion	
                    if($fareType == 'Full Fare' && $i > 0) {	
                        $fare_rate = $fare_calc[$companion_rate_code]['d_fare_amount'];	
                        $fare_rate_code = $companion_rate_code;	
                    }
                    if($fareType == 'Infant' && $ff_infants_index && $i > $ff_infants_index) {
                        $fare_rate = $fare_calc[$ff_rate_code]['d_fare_amount'];
                        $fare_rate_code = $ff_rate_code;
                    } 
                    if($store) {
                        $resTravellerInfo = ReservationTravellerInfo::find($travellers[$type][$i]['id']);
                        if($reservation_record['e_shuttle_type'] == "Shared"){
                            $resTravellerInfo->d_fare_amount = $fare_rate;
                        }
                        $resTravellerInfo->v_rate_code = $fare_rate_code;
                        $resTravellerInfo->save();
                    } else {
                       $total_fare += $fare_rate;
                    }
                }
            }
        }
        
        $total_fare += (float) ReservationLuggageInfo::where('i_reservation_id',$resv_id)->sum('d_price');
            
        return $total_fare;
    }

    public function calculateDiscountFare($couponCode, $tripType, $totalFare) {
        
        $tripType = $tripType == 'OW' ? 'One Way' : 'Round Trip';
        
        $discount = 0.00;
        if($couponCode != '') {
            $coupon_code = Offers::where('v_coupon_code', $couponCode)->where('d_start_date','<=', date('Y-m-d'))->where('d_expire_date','>', date('Y-m-d'))->where('e_type','<>','Employee')->whereIn('e_trip_type',[$tripType,'Both'])->where('e_status','Active')->first();

            if(!empty($coupon_code)) {
                if($coupon_code->f_discount_percentage) {
                    $discount = ($totalFare * $coupon_code->f_discount_percentage) / 100;
                } else {
                    $discount = $coupon_code->d_discount_flat_price;
                }
            }
        }
        return $discount;
            
    }

    public function printTest() {
        return view('frontend.customer_reservation.print_test');
    }

    public function targetDeparture(Request $request){
        $data = $request->all();
        $timestring = date('H:i:s',strtotime($data['time']));
       
        if($data){
            /*$line_run_data_departur = LineRun::whereRaw("IFNULL((select sum(i_total_num_passengers) from reservations as res inner join reservation_leg res_leg on res.id=res_leg.i_reservation_id where (res.e_reservation_status='Booked' OR res.e_reservation_status='Pending Payment') and res.deleted_at is null and res_leg.deleted_at is null and res_leg.i_run_id = linerun.id),0) < i_num_available")->where(DB::raw('DATE(d_run_date)'), '=', trim(date('Y-m-d', strtotime(trim($data['d_depart_date'])))))->where(['i_origin_service_area_id' => $data['i_origin_service_area_id'],'i_dest_service_area_id' => $data['i_dest_service_area_id']])->where('t_scheduled_arr_time','>',$timestring)->select('t_scheduled_arr_time')->first();*/

            $line_run_data_departur = LineRun::whereRaw("IFNULL((select sum(i_total_num_passengers) from reservations as res inner join reservation_leg res_leg on res.id=res_leg.i_reservation_id where (res.e_reservation_status='Booked' OR res.e_reservation_status='Pending Payment') and res.deleted_at is null and res_leg.deleted_at is null and res_leg.i_run_id = linerun.id),0) < i_num_available")->where(DB::raw('DATE(d_run_date)'), '=', trim(date('Y-m-d', strtotime(trim($data['d_depart_date'])))))->where(['i_origin_service_area_id' => $data['i_origin_service_area_id'],'i_dest_service_area_id' => $data['i_dest_service_area_id']])->select('t_scheduled_arr_time');

            if($data['direction']=='Drop') {
                $line_run_data_departur = $line_run_data_departur->where('t_scheduled_arr_time','<',$timestring)->orderBy('t_scheduled_arr_time','DESC')->first();
            } else {
                $line_run_data_departur = $line_run_data_departur->where('t_scheduled_arr_time','>',$timestring)->orderBy('t_scheduled_arr_time')->first();
            }

            if(!empty($line_run_data_departur)){
                $target_time = date('g:i A',strtotime($line_run_data_departur['t_scheduled_arr_time']));
            } else{
                $target_time = '';
            }
            return response()->json([
                'status' => 'TRUE',
                't_target_time' => $target_time,
            ]);
        }

    }
    
    public function rtTargetDeparture(Request $request){
        $data = $request->all();
        $timestring = date('H:i:s',strtotime($data['time']));
        if($data) {
           
            // $line_run_data_departur_rt = LineRun::whereRaw("IFNULL((select sum(i_total_num_passengers) from reservations as res inner join reservation_leg res_leg on res.id=res_leg.i_reservation_id where (res.e_reservation_status='Booked' OR res.e_reservation_status='Pending Payment') and res.deleted_at is null and res_leg.deleted_at is null and res_leg.i_run_id = linerun.id),0) < i_num_available")->where(DB::raw('DATE(d_run_date)'), '=', trim(date('Y-m-d', strtotime(trim($data['d_return_date'])))))->where(['i_origin_service_area_id' => $data['i_origin_service_area_id'],'i_dest_service_area_id' => $data['i_dest_service_area_id']])->where('t_scheduled_arr_time','>',$timestring)->select('t_scheduled_arr_time')->first();

            $line_run_data_departur_rt = LineRun::whereRaw("IFNULL((select sum(i_total_num_passengers) from reservations as res inner join reservation_leg res_leg on res.id=res_leg.i_reservation_id where (res.e_reservation_status='Booked' OR res.e_reservation_status='Pending Payment') and res.deleted_at is null and res_leg.deleted_at is null and res_leg.i_run_id = linerun.id),0) < i_num_available")->where(DB::raw('DATE(d_run_date)'), '=', trim(date('Y-m-d', strtotime(trim($data['d_return_date'])))))->where(['i_origin_service_area_id' => $data['i_origin_service_area_id'],'i_dest_service_area_id' => $data['i_dest_service_area_id']])->select('t_scheduled_arr_time');

            if($data['direction']=='Drop') {
                $line_run_data_departur_rt = $line_run_data_departur_rt->where('t_scheduled_arr_time','<',$timestring)->orderBy('t_scheduled_arr_time','DESC')->first();
            } else {
                $line_run_data_departur_rt = $line_run_data_departur_rt->where('t_scheduled_arr_time','>',$timestring)->orderBy('t_scheduled_arr_time')->first();
            }
           
            if(!empty($line_run_data_departur_rt)) {
                $target_time = date('g:i A',strtotime($line_run_data_departur_rt['t_scheduled_arr_time']));
            } else{
                $target_time = '';
            }
            return response()->json([
                'status' => 'TRUE',
                't_target_time' => $target_time,
            ]);
        }

    }

    public function getReservationData($id) {

        $reservation_record = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory','PickupCity','DropOffCity'])->where(['id' => $id])->first();
     
        if($reservation_record){
            $reservation_luggage_info = ReservationLuggageInfo::with(['SystemLuggageDef'])->where('i_reservation_id', $id)->get()->toArray();
            $reservation_pet_info = ReservationLuggageInfo::with(['SystemAnimalDef'])->where('i_reservation_id', $id)->get()->toArray();
            $reservation_luggage_info_total = ReservationLuggageInfo ::where('i_reservation_id', $id)->get()->sum('d_price');
        
            $payment_info = Transactions::where('i_reservation_id',$id)->whereIn('e_type',['Booked','Booked-Wallet','Cash-On-Board'])->orderBy('created_at','DESC')->get()->toArray();
            $payment_mode = $this->getPaymentMode($id);
            $total_fare_amount = ReservationTravellerInfo::where('i_reservation_id', $id)->select(DB::raw('SUM(d_fare_amount) AS total'), DB::raw('SUM(IF(e_type = "Adult", d_fare_amount, 0)) AS adult_total'), DB::raw('SUM(IF(e_type = "Child", d_fare_amount, 0)) AS child_total'),DB::raw('SUM(IF(e_type = "Senior", d_fare_amount, 0)) AS senior_total'),DB::raw('SUM(IF(e_type = "Military", d_fare_amount, 0)) AS military_total'),DB::raw('SUM(IF(e_type = "Infant", d_fare_amount, 0)) AS infant_total'), DB::raw('SUM(IF(e_type = "Adult", 1, 0)) AS adult_count'), DB::raw('SUM(IF(e_type = "Child", 1, 0)) AS child_count'), DB::raw('SUM(IF(e_type = "Senior", 1, 0)) AS senior_count'), DB::raw('SUM(IF(e_type = "Military", 1, 0)) AS military_count'), DB::raw('SUM(IF(e_type = "Infant", 1, 0)) AS infant_count'))->first()->toArray();

            $reservation_luggage_info_total_rt = "";
            $reservation_record_rt = $reservation_luggage_info_rt = $reservation_pet_info_rt = $total_fare_amount_rt = array();
            $res1_tt_text = $this->getTravelTypeText($reservation_record->i_reservation_category_id,$reservation_record->i_dropoff_point_type_id);
            $res2_tt_text = false;
            if($reservation_record['e_class_type'] == 'RT'){
                $reservation_record_get_id = Reservations::select('id')->where('i_parent_id', $id)->first();
                $reservation_rec2 = $reservation_record_get_id['id'];
                $reservation_record_rt = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory','PickupCity','DropOffCity'])->where(['id'=>$reservation_rec2])->first();
                $reservation_luggage_info_rt = ReservationLuggageInfo::with(['SystemLuggageDef'])->where('i_reservation_id', $reservation_rec2)->get()->toArray();
                $reservation_pet_info_rt = ReservationLuggageInfo::with(['SystemAnimalDef'])->where('i_reservation_id', $reservation_rec2)->get()->toArray();
                $total_fare_amount_rt = ReservationTravellerInfo::where('i_reservation_id', $reservation_rec2)->select(DB::raw('SUM(d_fare_amount) AS total'), DB::raw('SUM(IF(e_type = "Adult", d_fare_amount, 0)) AS adult_total'), DB::raw('SUM(IF(e_type = "Child", d_fare_amount, 0)) AS child_total'),DB::raw('SUM(IF(e_type = "Senior", d_fare_amount, 0)) AS senior_total'),DB::raw('SUM(IF(e_type = "Military", d_fare_amount, 0)) AS military_total'),DB::raw('SUM(IF(e_type = "Infant", d_fare_amount, 0)) AS infant_total'), DB::raw('SUM(IF(e_type = "Adult", 1, 0)) AS adult_count'), DB::raw('SUM(IF(e_type = "Child", 1, 0)) AS child_count'), DB::raw('SUM(IF(e_type = "Senior", 1, 0)) AS senior_count'), DB::raw('SUM(IF(e_type = "Military", 1, 0)) AS military_count'), DB::raw('SUM(IF(e_type = "Infant", 1, 0)) AS infant_count'))->first()->toArray();
                $reservation_luggage_info_total_rt = ReservationLuggageInfo ::where('i_reservation_id', $reservation_rec2)->get()->sum('d_price');
                $res2_tt_text = $this->getTravelTypeText($reservation_record_rt->i_reservation_category_id,$reservation_record_rt->i_dropoff_point_type_id);
            }
            if($reservation_record['e_class_type'] == 'OW') {
                $total_payment = $this->calculateTotalFare($reservation_record['id']);
            } else {
                $total_payment = $this->calculateTotalFare($reservation_record['id'],$reservation_record_rt['id']);
            }
           return view('frontend.customer_reservation.mail-reservation-summary', array('title' => 'Rservation Summary','reservation_record' => $reservation_record,'reservation_record_rt' => $reservation_record_rt,'reservation_luggage_info'=>$reservation_luggage_info,'reservation_pet_info'=>$reservation_pet_info,'reservation_luggage_info_rt'=>$reservation_luggage_info_rt,'reservation_pet_info_rt'=>$reservation_pet_info_rt,'total_fare_amount'=>$total_fare_amount,'total_fare_amount_rt'=>$total_fare_amount_rt,'reservation_luggage_info_total'=>$reservation_luggage_info_total,'reservation_luggage_info_total_rt'=>$reservation_luggage_info_total_rt,'total_payment'=>$total_payment,'res1_tt_text' => $res1_tt_text,'res2_tt_text' => $res2_tt_text,'payment_info'=>$payment_info,'payment_mode' => $payment_mode))->render();
            //return Response::download(ADMIN_FILES_PATH.$filename.'.pdf');
        }

    }
    protected function calculateTotalFare($resv_id1, $resv_id2 = null) {
        $total_fare = 0.00;
        
        $total_fare = (float) ReservationTravellerInfo::where('i_reservation_id',$resv_id1)->sum('d_fare_amount') + (float) ReservationLuggageInfo::where('i_reservation_id',$resv_id1)->sum('d_price');
            
        if($resv_id2) {
            $total_fare += (float) ReservationTravellerInfo::where('i_reservation_id',$resv_id2)->sum('d_fare_amount') + (float) ReservationLuggageInfo::where('i_reservation_id',$resv_id2)->sum('d_price');
        }
        
        return $total_fare;
    }
    public function getAddressInfo($id){
        $reservation_record = Reservations::with(['Admin', 'Customers', 'SystemResCategory','PickupCity','DropOffCity'])->where(['id' => $id])->first();
     
        if($reservation_record){
            $reservation_luggage_info = ReservationLuggageInfo::with(['SystemLuggageDef'])->where('i_reservation_id', $id)->get()->toArray();
            $reservation_pet_info = ReservationLuggageInfo::with(['SystemAnimalDef'])->where('i_reservation_id', $id)->get()->toArray();
            $reservation_luggage_info_total = ReservationLuggageInfo ::where('i_reservation_id', $id)->get()->sum('d_price');
        
        
            $total_fare_amount = ReservationTravellerInfo::where('i_reservation_id', $id)->select(DB::raw('SUM(d_fare_amount) AS total'), DB::raw('SUM(IF(e_type = "Adult", d_fare_amount, 0)) AS adult_total'), DB::raw('SUM(IF(e_type = "Child", d_fare_amount, 0)) AS child_total'),DB::raw('SUM(IF(e_type = "Senior", d_fare_amount, 0)) AS senior_total'),DB::raw('SUM(IF(e_type = "Military", d_fare_amount, 0)) AS military_total'),DB::raw('SUM(IF(e_type = "Infant", d_fare_amount, 0)) AS infant_total'), DB::raw('SUM(IF(e_type = "Adult", 1, 0)) AS adult_count'), DB::raw('SUM(IF(e_type = "Child", 1, 0)) AS child_count'), DB::raw('SUM(IF(e_type = "Senior", 1, 0)) AS senior_count'), DB::raw('SUM(IF(e_type = "Military", 1, 0)) AS military_count'), DB::raw('SUM(IF(e_type = "Infant", 1, 0)) AS infant_count'))->first()->toArray();

            $reservation_luggage_info_total_rt = "";
            $reservation_record_rt = $reservation_luggage_info_rt = $reservation_pet_info_rt = $total_fare_amount_rt = array();
            if($reservation_record['e_class_type'] == 'RT'){
                $reservation_record_get_id = Reservations::select('id')->where('i_parent_id', $id)->first();
                $reservation_rec2 = $reservation_record_get_id['id'];
                $reservation_record_rt = Reservations::with(['Admin', 'Customers', 'SystemResCategory','PickupCity','DropOffCity'])->where(['id'=>$reservation_rec2])->first();
                $reservation_luggage_info_rt = ReservationLuggageInfo::with(['SystemLuggageDef'])->where('i_reservation_id', $reservation_rec2)->get()->toArray();
                $reservation_pet_info_rt = ReservationLuggageInfo::with(['SystemAnimalDef'])->where('i_reservation_id', $reservation_rec2)->get()->toArray();
                $total_fare_amount_rt = ReservationTravellerInfo::where('i_reservation_id', $reservation_rec2)->select(DB::raw('SUM(d_fare_amount) AS total'), DB::raw('SUM(IF(e_type = "Adult", d_fare_amount, 0)) AS adult_total'), DB::raw('SUM(IF(e_type = "Child", d_fare_amount, 0)) AS child_total'),DB::raw('SUM(IF(e_type = "Senior", d_fare_amount, 0)) AS senior_total'),DB::raw('SUM(IF(e_type = "Military", d_fare_amount, 0)) AS military_total'),DB::raw('SUM(IF(e_type = "Infant", d_fare_amount, 0)) AS infant_total'), DB::raw('SUM(IF(e_type = "Adult", 1, 0)) AS adult_count'), DB::raw('SUM(IF(e_type = "Child", 1, 0)) AS child_count'), DB::raw('SUM(IF(e_type = "Senior", 1, 0)) AS senior_count'), DB::raw('SUM(IF(e_type = "Military", 1, 0)) AS military_count'), DB::raw('SUM(IF(e_type = "Infant", 1, 0)) AS infant_count'))->first()->toArray();
                $reservation_luggage_info_total_rt = ReservationLuggageInfo ::where('i_reservation_id', $reservation_rec2)->get()->sum('d_price');
            }
            if($reservation_record['e_class_type'] == 'OW') {
                $total_payment = $this->calculateTotalFare($reservation_record['id']);
            } else {
                $total_payment = $this->calculateTotalFare($reservation_record['id'],$reservation_record_rt['id']);
            }
           return view('frontend.customer_reservation.mail-address-info', array('title' => 'Rservation Summary','reservation_record' => $reservation_record,'reservation_record_rt' => $reservation_record_rt,'reservation_luggage_info'=>$reservation_luggage_info,'reservation_pet_info'=>$reservation_pet_info,'reservation_luggage_info_rt'=>$reservation_luggage_info_rt,'reservation_pet_info_rt'=>$reservation_pet_info_rt,'total_fare_amount'=>$total_fare_amount,'total_fare_amount_rt'=>$total_fare_amount_rt,'reservation_luggage_info_total'=>$reservation_luggage_info_total,'reservation_luggage_info_total_rt'=>$reservation_luggage_info_total_rt,'total_payment'=>$total_payment))->render();
           
        }

    }
    /* public function getSharedLocaltion(Request $request){
        $input = $request->all();
        $geo_point_location = GeoCities::whereIn('i_service_area_id',function($q){
            $q->select('i_origin_service_area_id')
            ->from(with(new FareTable)->getTable());
        })->select('id','v_county','v_city','i_service_area_id','v_drop_off_city_cant_be','v_drop_off_city_must_be')->orderBy('i_order', 'ASC')->where('e_only_private','0')->get()->toArray();
      
        $arr_country = [];
        foreach($geo_point_location as $k => $v) {
            $arr_country[$v['v_county']][] = $v;
        }
        $status = "TRUE";
        $data = $arr_country;
        return view('frontend.customer_reservation.dropoff-locations',['arr_country' => $data, 'status' => $status]);
    } */
}
<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Reservations;
use App\Models\Customers;
use App\Models\Admin;
use App\Models\GeoPoint;
use App\Models\GeoCities;
use App\Models\SystemResCategory;
use App\Models\FareTable;
use App\Models\FareClass;
use App\Models\ReservationTravellerInfo;
use App\Models\SystemLuggageDef;
use App\Models\ReservationLuggageInfo;
use App\Models\ReservationInfo;
use App\Models\SystemIcaoDef;
use App\Models\SystemPaymentDef;
use App\Models\Transactions;
use App\Models\SystemSettings;
use App\Models\EmailTemplate;
use App\Models\Logs;
use Illuminate\Http\Request;
use Carbon\Carbon;
use \Stripe\Stripe;
use Mail, Session, Redirect, Validator, DB, Hash,PDF,Response,Auth;
use Illuminate\Validation\Rule;
use App\Exports\ReservationListExport;
use Excel;

class ReservationsController extends BaseController {

    public function getIndex() {

        $service_area = GeoPoint::with(['GeoCities' => function($q) {
            $q->select('id', 'v_city', 'v_county');
        }])->select('id', 'i_city_id', 'v_street1', 'v_postal_code')->orderBy('v_street1')->get()->toArray();
        $reservation_category = SystemResCategory::select('id', 'v_label')->orderBy('v_label')->get()->toArray();
        $customer_data = Customers::where('e_status', 'Active')->select('id', 'v_firstname', 'v_lastname')->orderBy('v_firstname')->get()->toArray();
        $admin_users_list = Admin:: Wherehas('Permissions', function($q){
            $q->where(['i_module_id'=>20,'i_add_edit'=>1]);
        })->get()->toArray();
        return View('backend.reservations.index', array('title' => "Reservations", 'service_area' => $service_area, 'reservation_category' => $reservation_category, 'customer_data' => $customer_data,'admin_users_list'=>$admin_users_list));
    }

    public function anyListAjax(Request $request) {
        
        $data = $request->all();
        if(Auth::guard('admin')->user()->i_role_id != 6){

            $sortColumn = array('','v_reservation_number', 'i_customer_id', '', 'v_pickup_address', 'v_dropoff_address', 'e_class_type', 'e_shuttle_type', 'd_travel_date', 'i_total_num_passengers', 'e_reservation_status');
        } else {
            $sortColumn = array('','v_reservation_number', 'i_customer_id','v_pickup_address', 'v_dropoff_address', 'e_class_type', 'e_shuttle_type', 'd_travel_date', 'i_total_num_passengers', 'e_reservation_status');
        }
        $query = Reservations::with(['GeoOriginServiceArea', 'GeoDestServiceArea', 'Admin', 
                                'Customers' => function($q) {
                                    $q->select('customers.id', 'customers.v_firstname', 'customers.v_lastname');
                            }, 'GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities','PickupCity','DropOffCity','AdminBookedBy'])
                            ->select('reservations.*');
                            if(Auth::guard('admin')->user()->i_role_id == 6){
                                $query = $query->where('added_by_id', '=',Auth::guard('admin')->user()->id);
                            }
                    


        if (isset($data['v_reservation_number']) && $data['v_reservation_number'] != '') {
            $query = $query->where('v_reservation_number', 'LIKE', '%' . $data['v_reservation_number'] . '%');
        }
       /*  if (isset($data['i_customer_id']) && $data['i_customer_id'] != '') {
            
            $query = $query->WhereHas('Customers', function($q) use($data){
                $q->where(function($q1) use($data) {
                    $q1->where('customers.v_firstname', 'LIKE', '%' . trim($data['i_customer_id']) . '%')
                    ->orWhere('customers.v_lastname', 'LIKE', '%' . trim($data['i_customer_id']) . '%')
                    ->orWhere(DB::raw("CONCAT(customers.v_firstname, ' ',customers.v_lastname)"), 'LIKE', '%' . trim($data['i_customer_id']) . '%');
                }); 
            }); 
        } */
        if (isset($data['i_customer_id']) && $data['i_customer_id'] != '') {
            $query = $query->where('v_contact_name', 'LIKE', '%' . $data['i_customer_id'] . '%');
            
        }
        if (isset($data['i_booked_by_id']) && $data['i_booked_by_id'] != '') {
            
            if($data['i_booked_by_id'] == 0){
                $query = $query->whereNull('added_by_id');
            }else{
                $query = $query->where('added_by_id', '=',$data['i_booked_by_id']);
            }
            
        }
        if (isset($data['i_origin_point_id']) && $data['i_origin_point_id'] != '') {
            $query = $query->WhereHas('PickupCity', function($qa) use($data){
                $qa->where(DB::raw("CONCAT(reservations.v_pickup_address, COALESCE(CONCAT(', ',geo_cities.v_city), ''), COALESCE(CONCAT(', ',geo_cities.v_county),''))"), 'LIKE', '%' . trim($data['i_origin_point_id']) . '%');
          });
        }
        if (isset($data['i_destination_point_id']) && $data['i_destination_point_id'] != '') {
            $query = $query->WhereHas('DropOffCity', function($qa) use($data){
                $qa->where(DB::raw("CONCAT(reservations.v_dropoff_address, COALESCE(CONCAT(', ',geo_cities.v_city), ''), COALESCE(CONCAT(', ',geo_cities.v_county),''))"), 'LIKE', '%' . trim($data['i_destination_point_id']) . '%');

          });
        }
        if (isset($data['e_class_type']) && $data['e_class_type'] != '') {
            $query = $query->where('e_class_type', '=', $data['e_class_type'] );
        }
        if (isset($data['e_shuttle_type']) && $data['e_shuttle_type'] != '') {
            $query = $query->where('e_shuttle_type', '=', $data['e_shuttle_type'] );
        }
        if (isset($data['departureStartDate']) && trim($data['departureStartDate']) != '') {
            $query = $query->where(DB::raw('DATE(d_travel_date)'), '>=', trim(date('Y-m-d', strtotime(trim($data['departureStartDate'])))));
        }
        if (isset($data['departureEndDate']) && trim($data['departureEndDate']) != '') {
            $query = $query->where(DB::raw('DATE(d_travel_date)'), '<=', trim(date('Y-m-d', strtotime(trim($data['departureEndDate'])))));
        }
        
        if (isset($data['i_total_num_passengers']) && $data['i_total_num_passengers'] != '') {
            $query = $query->where('i_total_num_passengers', '=', $data['i_total_num_passengers']);
        }
        if (isset($data['e_reservation_status']) && $data['e_reservation_status'] != '') {
            $query = $query->where('e_reservation_status', 'LIKE', '%' . $data['e_reservation_status'] . '%');
        }

        $query = $query->whereNotIn('reservations.id',function($q){
            $q->select('id')->from('reservations')->where('e_shuttle_type','Private')->whereIn('e_reservation_status',['Requested','Request Confirmed'])->whereNotNull('i_parent_id');
        });

        $rec_per_page = REC_PER_PAGE;
        if (isset($data['length'])) {
            if ($data['length'] == '-1') {
                $rec_per_page = '';
            } else {
                $rec_per_page = $data['length'];
            }
        }

        $sort_order = $data['order']['0']['dir'];
        $order_field = $sortColumn[$data['order']['0']['column']];
        // pr($data['order']['0']['column']); exit;
        if ($sort_order != '' && $order_field != '') {
            if($order_field == 'i_customer_id') {
                $query = $query->leftjoin('customers','customers.id','=','reservations.i_customer_id')->orderBy('customers.v_firstname',$sort_order);
            } else if($order_field == 'i_origin_point_id') {
                $query = $query->leftjoin('geo_point','geo_point.id','=','reservations.i_origin_point_id')->orderBy('geo_point.v_street1',$sort_order);
            } else  if($order_field == 'i_destination_point_id') {
                $query = $query->leftjoin('geo_point','geo_point.id','=','reservations.i_destination_point_id')->orderBy('geo_point.v_street1',$sort_order);
            } else if($data['order']['0']['column'] == 0) {
                $query = $query->orderBy('reservations.updated_at', 'desc');
            } else {
                $query = $query->orderBy($order_field, $sort_order);
            }
        } else {
            $query = $query->orderBy('reservations.updated_at', 'desc');
        }
        $users = $query->paginate($rec_per_page);
        
        $arrUsers = $users->toArray();
       /*  pr($arrUsers);
        exit; */
        $data = array();
          
        foreach ($arrUsers['data'] as $key => $val) {
            $index = 0;
            if(isset($this->permission) && isset($this->permission[20]['i_delete']) && $this->permission[20]['i_delete'] == 1) {
                $data[$key][$index++] = '<label class="kt-checkbox kt-checkbox--single kt-checkbox--solid "><input type="checkbox" name="id[]" value="' . $val['id'] . '" class="kt-checkable"><span></span></label>';
            }
            $data[$key][$index++] = '<a href="' . ADMIN_URL .'reservations/view/'.$val['id']. '">'.$val['v_reservation_number'].'</a>';
            
            $data[$key][$index++] = !empty($val['v_contact_name']) ? $val['v_contact_name']: '';

            
            if(Auth::guard('admin')->user()->i_role_id != 6){
            $data[$key][$index++] = !empty($val['admin_booked_by']) ? $val['admin_booked_by']['v_firstname'].' '.$val['admin_booked_by']['v_lastname']: 'Customer';
            }
            if(isset($val['pickup_city']['v_city']) && $val['v_pickup_address'] != ''){
                $data[$key][$index++] =$val['v_pickup_address'].', '.$val['pickup_city']['v_city'].', '.$val['pickup_city']['v_county'];
            }else {
                $data[$key][$index++] = '';
            }
            if(isset($val['drop_off_city']['v_city']) && $val['v_dropoff_address'] != ''){
                $data[$key][$index++] = $val['v_dropoff_address'].', '.$val['drop_off_city']['v_city'].', '.$val['drop_off_city']['v_county'];
            }else {
                $data[$key][$index++] = '';
            }
           
            if($val['e_class_type'] == 'RT') {
                $data[$key][$index++] = 'Round Trip';
            } else {
                $data[$key][$index++] = 'One Way';
            }
            $data[$key][$index++] = $val['e_shuttle_type'];
            $data[$key][$index++] = date(DATE_FORMAT,strtotime($val['d_travel_date']));
            $data[$key][$index++] = $val['i_total_num_passengers'];
            $data[$key][$index++] = $val['e_reservation_status'];
            $action = '';
            $action .= '<div class="d-flex">';

            if(isset($this->permission) &&  isset($this->permission[20]['i_list']) && $this->permission[20]['i_list'] == 1) {
                $action .= '<a title="View" id="view_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg view_record" rel="' . $val['id'] . '" href="' . ADMIN_URL . 'reservations/view/' . $val['id'] . '"><i class="la la-eye"></i></a>';
            }
            if(isset($this->permission) &&  isset($this->permission[20]['i_delete']) && $this->permission[20]['i_delete'] == 1) {
                $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' . ADMIN_URL . 'reservations/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';
            }
            
            $action .= '</div>';
            $data[$key][$index++] = $action;
        }
        $return_data['data'] = $data;
        $return_data['iTotalRecords'] = $arrUsers['total'];
        $return_data['iTotalDisplayRecords'] = $arrUsers['total'];
        $return_data['data_array'] = $arrUsers['data'];
        return $return_data;
    }

    public function anyAdd(Request $request) {

        $inputs = $request->all();
        $auth_user = auth()->guard('admin')->user()->toArray();
       
        if ($inputs) {
         
            $record = new Reservations;
            $validator = Validator::make($inputs, [
                'i_customer_id' => 'required',               
                'i_origin_point_id' => 'required',               
                'd_depart_date' => 'required',   
                'e_class_type' => 'required',               
                'v_contact_phone_number' => 'required',                
                't_best_time_tocall' => 'required',               
                // 'i_reservation_category_id' => 'required', 
                'i_reservation_category_id' => 'required',  
                'v_contact_email' => 'required',
                'e_voice_mail_setup' => 'required', 
                'e_shuttle_type' => 'required',
                'e_reservation_status' => 'required', 
                'v_traveller_name' => 'required', 
                'd_birth_month_year' => 'required', 
                'e_type' => 'required',
            ]);
            
            if ($validator->fails()) {
                return json_encode($validator->errors());
            } else {
               
                $record->i_admin_id = $auth_user['id'];
                $record->i_reservation_category_id = trim($inputs['i_reservation_category_id']);
                $record->i_origin_point_id = trim($inputs['i_origin_point_id']);
                $record->i_destination_point_id = trim($inputs['i_destination_point_id']);
                $record->i_total_num_passengers = ($inputs['i_total_num_passengers'] == '') ? NULL : trim($inputs['i_total_num_passengers']);
                $record->e_reservation_status = trim($inputs['e_reservation_status']);
                $record->e_class_type = trim($inputs['e_class_type']);
                $myvalue = $inputs['i_customer_id'];
                $arr = explode(' ',trim($myvalue));
                $record->i_customer_id = trim($arr[0]);
                $record->v_contact_name = trim($arr[1].' '.$arr[2]);
                $record->d_travel_date = date('Y-m-d', strtotime(trim($inputs['d_depart_date'])));
                $record->v_contact_phone_number = trim($inputs['v_contact_phone_number']);
                $record->t_best_time_tocall = trim($inputs['t_best_time_tocall']);
                $record->i_number_of_luggages = ($inputs['i_number_of_luggages'] == '') ? NULL : trim($inputs['i_number_of_luggages']);
                if($inputs['e_shuttle_type'] == 'Private') {
                    $record->i_private_approved_by = $auth_user['id'];
                } else {
                    $record->i_private_approved_by = NULL;
                }
                // $record->i_private_approved_by = ($inputs['i_private_approved_by'] == '') ? NULL : trim($inputs['i_private_approved_by']);
                $record->t_special_instruction = trim($inputs['t_special_instruction']);
                /* if($inputs['e_class_type'] == 'RT') {
                    $record->d_return_date = date('Y-m-d', strtotime(trim($inputs['d_return_date'])));
                } else {
                    $record->d_return_date = NULL;
                } */
               /*  $record->e_travel_alone = trim($inputs['e_travel_alone']); */
                $record->v_contact_email = trim($inputs['v_contact_email']);
                $record->e_voice_mail_setup = trim($inputs['e_voice_mail_setup']);
                $record->i_num_pets = ($inputs['i_num_pets'] == '') ? NULL :  trim($inputs['i_num_pets']);
                $record->e_shuttle_type = trim($inputs['e_shuttle_type']);
                $record->e_flight_type = ($inputs['e_flight_type'] == '') ? NULL :  trim($inputs['e_flight_type']);
                $record->v_flight_number = ($inputs['v_flight_number'] == '') ? NULL :  trim($inputs['v_flight_number']);
                $record->t_flight_time = ($inputs['t_flight_time'] == '') ? NULL :  trim($inputs['t_flight_time']);
                // $record->v_flight_name = $inputs['v_flight_name'] ? trim($inputs['v_flight_name']) : NULL; 
                $record->t_comfortable_time = trim($inputs['t_comfortable_time']);
                $record->t_target_time = trim($inputs['t_target_time']);
                $record->created_at = Carbon::now();
                if ($record->save()) {

                    $record->v_reservation_number = reservationNumber($record->id);
                    $record->i_parent_id = NULL;
                    $record->save();

                    $fare_rate = 0;
                    $fare_rate_code = 0;

                    if($inputs['e_class_type'] == 'RT') {
                       
                        $round_trip_record = new Reservations;
                        $round_trip_record->i_parent_id = $record->id;
                        $round_trip_record->i_reservation_category_id = trim($inputs['i_reservation_category_id_round_trip']);
                        /* $round_trip_record->i_reservation_category_id = trim($inputs['i_reservation_category_id']); */
                        $myvalue = $inputs['i_customer_id'];
                        $arr = explode(' ',trim($myvalue));
                        $round_trip_record->i_customer_id = trim($arr[0]);
                        $round_trip_record->v_contact_name = trim($arr[1].' '.$arr[2]);
                        $round_trip_record->i_admin_id = $auth_user['id'];
                        $round_trip_record->i_origin_point_id = trim($inputs['i_origin_point_id']); 
                        $round_trip_record->i_destination_point_id = trim($inputs['i_destination_point_id']);
                        $round_trip_record->d_travel_date = date('Y-m-d', strtotime(trim($inputs['d_return_date'])));	
                      /*   $round_trip_record->d_return_date = date('Y-m-d', strtotime(trim($inputs['d_return_date']))); */
                        $round_trip_record->e_class_type = trim($inputs['e_class_type']);		
                        $round_trip_record->v_contact_phone_number = trim($inputs['v_contact_phone_number']);
                        $round_trip_record->v_contact_email = trim($inputs['v_contact_email']);	
                        $round_trip_record->t_best_time_tocall = trim($inputs['t_best_time_tocall']);	
                        $round_trip_record->e_voice_mail_setup = trim($inputs['e_voice_mail_setup']);
                        $round_trip_record->i_total_num_passengers = ($inputs['i_total_num_passengers_rt'] == '') ? NULL : trim($inputs['i_total_num_passengers_rt']);
                        $round_trip_record->i_num_pets = ($inputs['i_num_pets_rt'] == '') ? NULL :  trim($inputs['i_num_pets_rt']);	
                        $round_trip_record->i_number_of_luggages = ($inputs['i_number_of_luggages_rt'] == '') ? NULL : trim($inputs['i_number_of_luggages_rt']);
                        $round_trip_record->e_shuttle_type = trim($inputs['e_shuttle_type_rt']);
                        if($inputs['e_shuttle_type_rt'] == 'Private') {
                            $round_trip_record->i_private_approved_by = $auth_user['id'];
                        } else {
                            $round_trip_record->i_private_approved_by = NULL;
                        }
                        $round_trip_record->t_special_instruction = trim($inputs['t_special_instruction_rt']);
                        $round_trip_record->e_reservation_status = trim($inputs['e_reservation_status_rt']);
                        $round_trip_record->e_flight_type = $inputs['e_flight_type_round_trip'] ? trim($inputs['e_flight_type_round_trip']) : NULL ;
                        $round_trip_record->v_flight_number = $inputs['v_flight_number_round_trip'] ? trim($inputs['v_flight_number_round_trip']) : NULL ;
                        $round_trip_record->t_flight_time = $inputs['t_flight_time_round_trip'] ? trim($inputs['t_flight_time_round_trip']) : NULL ;
                        // $round_trip_record->v_flight_name = $inputs['v_flight_name_rt'] ? trim($inputs['v_flight_name_rt']) : NULL; 
                        $round_trip_record->t_comfortable_time = trim($inputs['t_comfortable_time_rt']);
                        $round_trip_record->t_target_time = trim($inputs['t_target_time_rt']);
                        $round_trip_record->created_at = Carbon::now();

                        if($round_trip_record->save()) {
                            $round_trip_record->v_reservation_number = reservationNumber($round_trip_record->id);
                        }
                    }
                    
                    $GeoPoint_origin = GeoPoint::where('id',$inputs['i_origin_point_id'])->first();
                    $GeoPoint_destination = GeoPoint::where('id',$inputs['i_destination_point_id'])->first();
                    $location_info_origin = "";
                    $location_info_destination = "";
                    if($GeoPoint_origin){
                        $location_info_origin = GeoCities::where('id',$GeoPoint_origin['i_city_id'])->first();
                    }
                    if($GeoPoint_destination){
                        $location_info_destination = GeoCities::where('id',$GeoPoint_destination['i_city_id'])->first();
                    }
                  

                      $fare_calc = FareTable::where('i_origin_service_area_id', $location_info_origin['i_service_area_id'])->where('i_dest_service_area_id', $location_info_destination['i_service_area_id'])->select('id', 'i_rate_class_id', 'v_rate_code', 'd_fare_amount')->get()->toArray();
                    
                    $total_passenger = $record->i_total_num_passengers;
                    if($inputs['e_class_type'] == 'RT') { 

                        $fare_calc_rt = FareTable::where('i_origin_service_area_id', $location_info_destination['i_service_area_id'])->where('i_dest_service_area_id', $location_info_origin['i_service_area_id'])->select('id', 'i_rate_class_id', 'v_rate_code', 'd_fare_amount')->get()->toArray();
                        
                        $total_passenger_rt = $round_trip_record->i_total_num_passengers;
                        
                        // RETURN RESERVATION
                        for($i=1; $i<=$total_passenger_rt; $i++) {

                            $passenger_name_rt = explode(",",$inputs['v_traveller_name_rt'][0]);
                            $passenger_dob_rt = explode(",",$inputs['d_birth_month_year_rt'][0]);
                            $passenger_type_rt = explode(",",$inputs['e_type_rt'][0]);
                          
                            $travel_info = new ReservationTravellerInfo;
                            $travel_info->i_reservation_id = $round_trip_record->id;
                            $travel_info->v_traveller_name = $passenger_name_rt[$i-1];
                            $travel_info->d_birth_month_year = date('Y-m-d', strtotime(trim($passenger_dob_rt[$i-1])));
                            $travel_info->e_type = $passenger_type_rt[$i-1] ? $passenger_type_rt[$i-1] : NULL ;
                            $fare_type_rec = ($passenger_type_rt[$i-1] == 'Adult') ? 'Full Fare' : $passenger_type_rt[$i-1];
                            $fare_class = FareClass::where('e_class_type', $inputs['e_class_type'])->where('v_class_label', $fare_type_rec)->select('id', 'v_rate_code')->first()->toArray();
                                // pr($fare_class); exit;
                                foreach ($fare_calc_rt as $key => $value_rt) {
                                    if($fare_class['v_rate_code'] == $value_rt['v_rate_code']) {
                                        $fare_rate = $value_rt['d_fare_amount'];
                                        $fare_rate_code = $value_rt['v_rate_code'];
                                    }
                                }
                            $travel_info->d_fare_amount = $fare_rate;
                            $travel_info->v_rate_code = $fare_rate_code;
                            $travel_info->save(); 
                        }
                        $total_fare_amount_rt = 0;
                        $total_fare_amount_rt = ReservationTravellerInfo::where('i_reservation_id',$round_trip_record->id)->select('id','d_fare_amount')->sum('d_fare_amount');
                    }
                    
                    // ONEWAY RESERVATION
                    for($i=1; $i<=$total_passenger; $i++) {

                        $passenger_name = explode(",",$inputs['v_traveller_name'][0]);
                        $passenger_dob = explode(",",$inputs['d_birth_month_year'][0]);
                        $passenger_type = explode(",",$inputs['e_type'][0]);

                        $travel_info = new ReservationTravellerInfo;
                        $travel_info->i_reservation_id = $record->id;
                        $travel_info->v_traveller_name = $passenger_name[$i-1];
                        $travel_info->d_birth_month_year = date('Y-m-d', strtotime(trim($passenger_dob[$i-1])));
                        //$travel_info->e_is_travel_alone = $record->e_travel_alone;
                        $travel_info->e_type = $passenger_type[$i-1] ? $passenger_type[$i-1] : NULL ;
                        $passenger_type_rec = ($passenger_type[$i-1] == 'Adult') ? 'Full Fare' : $passenger_type[$i-1];
                        $fare_class = FareClass::where('e_class_type', $inputs['e_class_type'])->where('v_class_label', $passenger_type_rec)->select('id', 'v_rate_code')->first();
                        
                        foreach ($fare_calc as $key => $value) {
                            if($fare_class['v_rate_code'] == $value['v_rate_code']) {
                                $fare_rate = $value['d_fare_amount'];
                                $fare_rate_code = $value['v_rate_code'];
                            }
                        }
                        $travel_info->d_fare_amount = $fare_rate;
                        $travel_info->v_rate_code = $fare_rate_code;
                        $travel_info->save(); 
                        
                    }
                    $total_fare_amount = 0;
                    $total_fare_amount = ReservationTravellerInfo::where('i_reservation_id',$record->id)->select('id','d_fare_amount')->sum('d_fare_amount');
                    
                    // $total_luggage_count = SystemLuggageDef::count();
                    $total_luggage_count = SystemLuggageDef::withTrashed()->count();
                    $luggages_amount = $animal_amount = $total_amount= 0;
                    $luggages_amount_rt = $animal_amount_rt = $total_amount_rt= 0;
                    for($i=1; $i<=$total_luggage_count; $i++) {

                        // ONEWAY RESERVATION
                        if(isset($inputs['luggage_numbers_'.$i]) && ($inputs['luggage_numbers_'.$i] != '' && $inputs['luggage_numbers_'.$i] != 0)) {
                            $reservation_lugg_info = new ReservationLuggageInfo;
                            $reservation_lugg_info->i_reservation_id = $record->id;
                            $reservation_lugg_info->i_sys_luggage_id = $inputs['luggage_id_'.$i];
                            $reservation_lugg_info->i_value = $inputs['luggage_numbers_'.$i];
                            $reservation_lugg_info->d_price = $inputs['total_fare_amt_'.$i];
                            $reservation_lugg_info->save();
                            $luggages_amount = $luggages_amount + $reservation_lugg_info->d_price;
                        }
                        if(isset($inputs['pet_available_'.$i]) && isset($inputs['total_fare_amt_pet_'.$i]) && ($inputs['total_fare_amt_pet_'.$i] != '' && $inputs['total_fare_amt_pet_'.$i] != 0)) {
                            
                            $reservation_lugg_info = new ReservationLuggageInfo;
                            $reservation_lugg_info->i_reservation_id = $record->id;
                            $reservation_lugg_info->i_sys_luggage_id = $inputs['animal_rec_id_'.$i];
                            $reservation_lugg_info->i_value = 1;
                            $reservation_lugg_info->d_price = $inputs['total_fare_amt_pet_'.$i];
                            $reservation_lugg_info->save();

                            $animal_amount = $animal_amount + $reservation_lugg_info->d_price;
                        }
                        $total_amount = $luggages_amount + $animal_amount;
                        if($inputs['e_class_type'] == 'RT') {
                        // RETURN RESERVATION
                            if(isset($inputs['luggage_numbers_'.$i.'_rt']) && ($inputs['luggage_numbers_'.$i.'_rt'] != '' && $inputs['luggage_numbers_'.$i.'_rt'] != 0)) {
                                $reservation_lugg_info = new ReservationLuggageInfo;
                                $reservation_lugg_info->i_reservation_id = $round_trip_record->id;
                                $reservation_lugg_info->i_sys_luggage_id = $inputs['luggage_id_'.$i.'_rt'];
                                $reservation_lugg_info->i_value = $inputs['luggage_numbers_'.$i.'_rt'];
                                $reservation_lugg_info->d_price = $inputs['total_fare_amt_'.$i.'_rt'];
                                $reservation_lugg_info->save();
                                $luggages_amount_rt = $luggages_amount_rt + $reservation_lugg_info->d_price;
                            }
                            if(isset($inputs['pet_available_'.$i.'_rt']) && isset($inputs['total_fare_amt_pet_'.$i.'_rt']) && ($inputs['total_fare_amt_pet_'.$i.'_rt'] != '' && $inputs['total_fare_amt_pet_'.$i.'_rt'] != 0)) {
                                
                                $reservation_lugg_info = new ReservationLuggageInfo;
                                $reservation_lugg_info->i_reservation_id = $round_trip_record->id;
                                $reservation_lugg_info->i_sys_luggage_id = $inputs['animal_rec_id_'.$i.'_rt'];
                                $reservation_lugg_info->i_value = 1;
                                $reservation_lugg_info->d_price = $inputs['total_fare_amt_pet_'.$i.'_rt'];
                                $reservation_lugg_info->save();

                                $animal_amount_rt = $animal_amount_rt + $reservation_lugg_info->d_price;
                            }
                            $total_amount_rt = $luggages_amount_rt + $animal_amount_rt;
                        }
                    }

                    // store final amount of reervation
                    $final_fare_amount = $total_fare_amount + $total_amount;
                    $record->d_total_fare = $final_fare_amount;
                    $record->save();

                    if($inputs['e_class_type'] == 'RT') {
                        $final_fare_amount_rt = $total_fare_amount_rt + $total_amount_rt;
                        $round_trip_record->d_total_fare = $final_fare_amount_rt;
                        $round_trip_record->save();
                    }

                    Session::flash('success-message', 'Reservations added successfully.');
                    return '';
                }
            }
        } else {
            $admin_data = Admin::where('e_status', 'Active')->select('id', 'v_firstname', 'v_lastname')->orderBy('v_firstname')->get()->toArray();
            $service_area = GeoPoint::with(['GeoCities' => function($q) {
                $q->where('e_status', 'Active')->select('id', 'v_city', 'v_county');
            }])->select('id', 'v_street1', 'v_postal_code', 'i_city_id')->orderBy('v_street1')->get()->toArray();
            $reservation_category = SystemResCategory::select('id', 'v_label')->orderBy('v_label')->get()->toArray();
            $customer_data = Customers::where('e_status', 'Active')->select('id', 'v_firstname', 'v_lastname')->orderBy('v_firstname')->get()->toArray();
            // pr($customer_data); exit;
            $animals_list = SystemLuggageDef::where('e_type', 'Animal')->select('id', 'v_name', 'e_type', 'd_unit_price')->orderBy('v_name')->get()->toArray();
            $luggages_list = SystemLuggageDef::where('e_type', 'Luggage')->select('id', 'v_name', 'e_type', 'd_unit_price')->orderBy('v_name')->get()->toArray();
            $system_icao_def = SystemIcaoDef::where('e_status', 'Active')->select('id', 'v_airline_name')->get()->toArray();
            // pr(count($system_icao_def)); exit;

            return View('backend.reservations.add', array('title' => 'Add Reservations', 'service_area' => $service_area, 'reservation_category' => $reservation_category, 'admin_data' => $admin_data, 'customer_data' => $customer_data, 'animals_list' => $animals_list, 'luggages_list' => $luggages_list, 'system_icao_def' => $system_icao_def));
        }
        return Redirect(ADMIN_URL . 'reservations');
    }
 
    public function anyEdit(Request $request, $id) {
        $inputs = $request->all();
        $auth_user = auth()->guard('admin')->user()->toArray();
        
        $reservation_record = Reservations::find($id);
        // pr($reservation_record); exit;
        if($reservation_record->i_parent_id == NULL) {
            $record = $reservation_record;
            if($reservation_record->e_class_type == 'RT') {
                $record_rt = Reservations::where('e_class_type', 'RT')->where('i_parent_id', $id)->first();
            } else {
                $record_rt = array();
            }
        } 
        if ($inputs) {
           
            $validator = Validator::make($inputs, [
                'i_customer_id' => 'required',               
                'i_origin_point_id' => 'required',               
                'd_depart_date' => 'required',   
                // 'e_class_type' => 'required',               
                'v_contact_phone_number' => 'required',                
                't_best_time_tocall' => 'required',               
                'i_reservation_category_id' => 'required', 
                // 'i_reservation_category_id' => 'required',  
                'v_contact_email' => 'required',
                'e_voice_mail_setup' => 'required', 
                'e_shuttle_type' => 'required',
                'e_reservation_status' => 'required', 
                'v_traveller_name' => 'required', 
                'd_birth_month_year' => 'required', 
                'e_type' => 'required',
            ]);
            
            if ($validator->fails()) {
                return json_encode($validator->errors());
            } else {
                $record->i_admin_id = $auth_user['id'];
                $record->i_reservation_category_id = trim($inputs['i_reservation_category_id']);
                $record->i_origin_point_id = trim($inputs['i_origin_point_id']);
                $record->i_destination_point_id = trim($inputs['i_destination_point_id']);
                $record->i_total_num_passengers = ($inputs['i_total_num_passengers'] == '') ? NULL : trim($inputs['i_total_num_passengers']);
                $record->e_reservation_status = trim($inputs['e_reservation_status']);
                $record->e_class_type = trim($inputs['e_class_type']);
                //$record->i_customer_id = trim($inputs['i_customer_id']);
                $myvalue = $inputs['i_customer_id'];
                $arr = explode(' ',trim($myvalue));
                $record->i_customer_id = trim($arr[0]);
                $record->v_contact_name = trim($arr[1].' '.$arr[2]);
                //$record->d_travel_date = date('Y-m-d', strtotime(trim($inputs['d_depart_date'])));
                $record->d_travel_date = date('Y-m-d', strtotime(trim($inputs['d_depart_date'])));
                $record->v_contact_phone_number = trim($inputs['v_contact_phone_number']);
                $record->t_best_time_tocall = trim($inputs['t_best_time_tocall']);
                $record->i_number_of_luggages = ($inputs['i_number_of_luggages'] == '') ? NULL : trim($inputs['i_number_of_luggages']);
                if($inputs['e_shuttle_type'] == 'Private') {
                    $record->i_private_approved_by = $auth_user['id'];
                } else {
                    $record->i_private_approved_by = NULL;
                }
                $record->t_special_instruction = trim($inputs['t_special_instruction']);
                $record->v_contact_email = trim($inputs['v_contact_email']);
                $record->e_voice_mail_setup = trim($inputs['e_voice_mail_setup']);
                $record->i_num_pets = ($inputs['i_num_pets'] == '') ? NULL :  trim($inputs['i_num_pets']);
                $record->e_shuttle_type = trim($inputs['e_shuttle_type']);
                // $record->v_flight_name = ($inputs['v_flight_name'] == '') ? NULL :  trim($inputs['v_flight_name']);
                $record->t_comfortable_time = trim($inputs['t_comfortable_time']);
                $record->t_target_time = trim($inputs['t_target_time']);
                $record->e_flight_type = ($inputs['e_flight_type'] == '') ? NULL :  trim($inputs['e_flight_type']);
                $record->v_flight_number = ($inputs['v_flight_number'] == '') ? NULL :  trim($inputs['v_flight_number']);
                $record->t_flight_time = ($inputs['t_flight_time'] == '') ? NULL :  trim($inputs['t_flight_time']);

                if ($record->save()) {

                    $record->v_reservation_number = reservationNumber($record->id);
                    $record->i_parent_id = NULL;
                    $record->save();

                    $fare_rate = 0;
                    $fare_rate_code = 0;

                    if($inputs['e_class_type'] == 'RT') {
                        $round_trip_record = Reservations::find($inputs['reservation_rt_record']);
                        $round_trip_record->i_parent_id = $record->id;
                        $round_trip_record->i_reservation_category_id = trim($inputs['i_reservation_category_id']);
                        //$round_trip_record->i_customer_id = trim($inputs['i_customer_id']);
                        $myvalue = $inputs['i_customer_id'];
                        $arr = explode(' ',trim($myvalue));
                        $round_trip_record->i_customer_id = trim($arr[0]);
                        $round_trip_record->v_contact_name = trim($arr[1].' '.$arr[2]);
                        $round_trip_record->i_admin_id = $auth_user['id'];
                        $round_trip_record->i_origin_point_id = trim($inputs['i_origin_point_id']); 
                        $round_trip_record->i_destination_point_id = trim($inputs['i_destination_point_id']);
                        $round_trip_record->d_travel_date = date('Y-m-d', strtotime(trim($inputs['d_return_date'])));
                        $round_trip_record->e_class_type = trim($inputs['e_class_type']);	
                       // $round_trip_record->e_travel_alone = trim($inputs['e_travel_alone_rt']);	
                        $round_trip_record->v_contact_phone_number = trim($inputs['v_contact_phone_number']);
                        $round_trip_record->v_contact_email = trim($inputs['v_contact_email']);	
                        $round_trip_record->t_best_time_tocall = trim($inputs['t_best_time_tocall']);	
                        $round_trip_record->e_voice_mail_setup = trim($inputs['e_voice_mail_setup']);	
                        $round_trip_record->i_total_num_passengers = ($inputs['i_total_num_passengers_rt'] == '') ? NULL : trim($inputs['i_total_num_passengers_rt']);
                        $round_trip_record->i_num_pets = ($inputs['i_num_pets_rt'] == '') ? NULL :  trim($inputs['i_num_pets_rt']);	
                        $round_trip_record->i_number_of_luggages = ($inputs['i_number_of_luggages_rt'] == '') ? NULL : trim($inputs['i_number_of_luggages_rt']);
                        $round_trip_record->e_shuttle_type = trim($inputs['e_shuttle_type_rt']);
                        if($inputs['e_shuttle_type_rt'] == 'Private') {
                            $round_trip_record->i_private_approved_by = $auth_user['id'];
                        } else {
                            $round_trip_record->i_private_approved_by = NULL;
                        }
                        $round_trip_record->t_special_instruction = trim($inputs['t_special_instruction_rt']);
                        $round_trip_record->e_reservation_status = trim($inputs['e_reservation_status_rt']);
                        $round_trip_record->e_flight_type = $inputs['e_flight_type_round_trip'] ? trim($inputs['e_flight_type_round_trip']) : NULL ;
                        $round_trip_record->v_flight_number = $inputs['v_flight_number_round_trip'] ? trim($inputs['v_flight_number_round_trip']) : NULL ;
                        $round_trip_record->t_flight_time = $inputs['t_flight_time_round_trip'] ? trim($inputs['t_flight_time_round_trip']) : NULL ;
                        // $round_trip_record->v_flight_name = $inputs['v_flight_name_rt'] ? trim($inputs['v_flight_name_rt']) : NULL; 
                        $round_trip_record->t_comfortable_time = trim($inputs['t_comfortable_time_rt']);
                        $round_trip_record->t_target_time = trim($inputs['t_target_time_rt']);
                        $round_trip_record->created_at = Carbon::now();

                        if($round_trip_record->save()) {
                            $round_trip_record->v_reservation_number = reservationNumber($round_trip_record->id);
                        }
                    }
                    $GeoPoint_origin = GeoPoint::where('id',$inputs['i_origin_point_id'])->first();
                    $GeoPoint_destination = GeoPoint::where('id',$inputs['i_destination_point_id'])->first();
                    $location_info_origin = "";
                    $location_info_destination = "";
                    if($GeoPoint_origin){
                        $location_info_origin = GeoCities::where('id',$GeoPoint_origin['i_city_id'])->first();
                    }
                    if($GeoPoint_destination){
                        $location_info_destination = GeoCities::where('id',$GeoPoint_destination['i_city_id'])->first();
                    }
                  
                      $fare_calc = FareTable::where('i_origin_service_area_id', $location_info_origin['i_service_area_id'])->where('i_dest_service_area_id', $location_info_destination['i_service_area_id'])->select('id', 'i_rate_class_id', 'v_rate_code', 'd_fare_amount')->get()->toArray();

                   /*  $fare_calc = FareTable::where('i_origin_service_area_id', $inputs['i_origin_point_id'])->where('i_dest_service_area_id', $inputs['i_destination_point_id'])->select('id', 'i_rate_class_id', 'v_rate_code', 'd_fare_amount')->get()->toArray(); */
                    // pr($fare_calc); exit;
                    
                    $total_passenger = $record->i_total_num_passengers;
                    $passenger_id = explode(",",$inputs['passanger_reservation_id'][0]);
                    $old_travel_info = ReservationTravellerInfo::where('i_reservation_id', $record->id)->delete();
                    $passenger_name = explode(",",$inputs['v_traveller_name'][0]);
                    $passenger_dob = explode(",",$inputs['d_birth_month_year'][0]);
                    $passenger_type = explode(",",$inputs['e_type'][0]);
                    // pr($passenger_name);exit;
                    // ONEWAY RESERVATION
                    for($i=1; $i<=$total_passenger; $i++) {
                        /* $j=$i;
                        foreach($passenger_id as $val) { 
                            if($val == '') {*/
                            $travel_info = new ReservationTravellerInfo;
                            /* } else {
                                $travel_info = ReservationTravellerInfo::find($val);
                            } */
                            $travel_info->i_reservation_id = $record->id;
                            $travel_info->v_traveller_name = $passenger_name[$i-1];
                            $travel_info->d_birth_month_year = date('Y-m-d', strtotime(trim($passenger_dob[$i-1])));
                            $travel_info->e_type = $passenger_type[$i-1] ;
                            $passenger_type_rec = ($passenger_type[$i-1] == 'Adult') ? 'Full Fare' : $passenger_type[$i-1];
                            $fare_class = FareClass::where('e_class_type', $inputs['e_class_type'])->where('v_class_label', $passenger_type_rec)->select('id', 'v_rate_code')->first();
                            foreach ($fare_calc as $key => $value) {
                                if($fare_class['v_rate_code'] == $value['v_rate_code']) {
                                    $fare_rate = $value['d_fare_amount'];
                                    $fare_rate_code = $value['v_rate_code'];
                                }
                            }
                            $travel_info->d_fare_amount = $fare_rate;
                            $travel_info->v_rate_code = $fare_rate_code;
                            $travel_info->save(); 
                           /*  $j++;
                        } */
                    } 
                    $total_fare_amount = 0;
                    $total_fare_amount = ReservationTravellerInfo::where('i_reservation_id',$record->id)->select('id','d_fare_amount')->sum('d_fare_amount');
                    
                    // RETURN RESERVATION
                    if($inputs['e_class_type'] == 'RT') {

                        $fare_calc_rt = FareTable::where('i_origin_service_area_id', $location_info_origin['i_service_area_id'])->where('i_dest_service_area_id', $location_info_destination['i_service_area_id'])->select('id', 'i_rate_class_id', 'v_rate_code', 'd_fare_amount')->get()->toArray();

                        $total_passenger_rt = ($inputs['i_total_num_passengers_rt'] == '') ? NULL : trim($inputs['i_total_num_passengers_rt']);
                        $passenger_id_rt = explode(",",$inputs['passanger_reservation_id_rt'][0]);
                        $old_travel_info = ReservationTravellerInfo::where('i_reservation_id', $round_trip_record->id)->delete();
                        $passenger_name_rt = explode(",",$inputs['v_traveller_name_rt'][0]);
                        $passenger_dob_rt = explode(",",$inputs['d_birth_month_year_rt'][0]);
                        $passenger_type_rt = explode(",",$inputs['e_type_rt'][0]);
                        // pr($passenger_name_rt); exit;
                        for($i=1; $i<=$total_passenger_rt; $i++) {
                            // $j=$i;
                          
                            // foreach($passenger_id_rt as $val) {
                                /* if(!empty($passenger_id_rt)) {
                                    $travel_info = ReservationTravellerInfo::find($passenger_id_rt[$i-1]);
                                } else { */
                                $travel_info = new ReservationTravellerInfo;
                                /* } */
                                $travel_info->i_reservation_id = $round_trip_record->id;
                                $travel_info->v_traveller_name = $passenger_name_rt[$i-1];
                                $travel_info->d_birth_month_year = date('Y-m-d', strtotime(trim($passenger_dob_rt[$i-1])));
                                $travel_info->e_type = $passenger_type_rt[$i-1] ;
                                $fare_type_rec = ($passenger_type_rt[$i-1] == 'Adult') ? 'Full Fare' : $passenger_type_rt[$i-1];
                                $fare_class = FareClass::where('e_class_type', $inputs['e_class_type'])->where('v_class_label', $fare_type_rec)->select('id', 'v_rate_code')->first()->toArray();
                                foreach ($fare_calc_rt as $key => $value_rt) {
                                    if($fare_class['v_rate_code'] == $value_rt['v_rate_code']) {
                                        $fare_rate = $value_rt['d_fare_amount'];
                                        $fare_rate_code = $value_rt['v_rate_code'];
                                    }
                                }
                                $travel_info->d_fare_amount = $fare_rate;
                                $travel_info->v_rate_code = $fare_rate_code;
                                $travel_info->save(); 
                                // $j++;
                            // }
                        }
                        $total_fare_amount_rt = 0;
                        $total_fare_amount_rt = ReservationTravellerInfo::where('i_reservation_id',$round_trip_record->id)->select('id','d_fare_amount')->sum('d_fare_amount');
                    }
                    
                    $total_luggage_count = SystemLuggageDef::withTrashed()->count();
                    $luggages_amount = $animal_amount = $total_amount= 0;   
                    $luggages_amount_rt = $animal_amount_rt = $total_amount_rt= 0;

                    for($i=1; $i<=$total_luggage_count; $i++) {
                        // ONEWAY RESERVATION
                        if(isset($inputs['luggage_numbers_'.$i]) && ($inputs['luggage_numbers_'.$i] != '' && $inputs['luggage_numbers_'.$i] != 0)) {
                            if(isset($inputs['luggage_data_id_'.$i])){
                                $reservation_lugg_info = ReservationLuggageInfo::find($inputs['luggage_data_id_'.$i]);                               
                            } else {
                                $reservation_lugg_info = new ReservationLuggageInfo;
                            }
                            $reservation_lugg_info->i_reservation_id = $record->id;
                            $reservation_lugg_info->i_sys_luggage_id = $inputs['luggage_id_'.$i];
                            $reservation_lugg_info->i_value = $inputs['luggage_numbers_'.$i];
                            $reservation_lugg_info->d_price = $inputs['total_fare_amt_'.$i];
                            $reservation_lugg_info->save();
                            $luggages_amount = $luggages_amount + $reservation_lugg_info->d_price;
                        }
                        if(isset($inputs['total_fare_amt_pet_'.$i]) && ($inputs['total_fare_amt_pet_'.$i] != '' && $inputs['total_fare_amt_pet_'.$i] != 0)) {
                            
                            if(isset($inputs['luggage_data_id_'.$i])){                                
                                $reservation_lugg_info = ReservationLuggageInfo::find($inputs['luggage_data_id_'.$i]);       
                            } else {
                                $reservation_lugg_info = new ReservationLuggageInfo;
                            }
                            $reservation_lugg_info->i_reservation_id = $record->id;
                            $reservation_lugg_info->i_sys_luggage_id = $inputs['animal_rec_id_'.$i];
                            $reservation_lugg_info->i_value = 1;
                            $reservation_lugg_info->d_price = $inputs['total_fare_amt_pet_'.$i];
                            $reservation_lugg_info->save();

                            $animal_amount = $animal_amount + $reservation_lugg_info->d_price;
                        }
                        $total_amount = $luggages_amount + $animal_amount;

                        //Luggage record delete for OneWay
                        if(isset($inputs['luggage_data_id_'.$i]) &&  isset($inputs['luggage_numbers_'.$i]) && $inputs['luggage_numbers_'.$i] == 0) {
                            $old_passanger_info = ReservationLuggageInfo::where('id', $inputs['luggage_data_id_'.$i])->where('i_reservation_id',$record->id)->delete();
                        }
                        if(isset($inputs['luggage_data_id_'.$i]) && isset($inputs['total_fare_amt_pet_'.$i]) && $inputs['total_fare_amt_pet_'.$i] == 0) {
                            $old_passanger_info = ReservationLuggageInfo::where('id', $inputs['luggage_data_id_'.$i])->where('i_reservation_id',$record->id)->delete();
                        }

                        // RETURN RESERVATION
                        if($inputs['e_class_type'] == 'RT') {
                            if(isset($inputs['luggage_numbers_'.$i.'_rt']) && ($inputs['luggage_numbers_'.$i.'_rt'] != '' && $inputs['luggage_numbers_'.$i.'_rt'] != 0)) {
                                $reservation_lugg_info = new ReservationLuggageInfo;
                                $reservation_lugg_info->i_reservation_id = $round_trip_record->id;
                                $reservation_lugg_info->i_sys_luggage_id = $inputs['luggage_id_'.$i.'_rt'];
                                $reservation_lugg_info->i_value = $inputs['luggage_numbers_'.$i.'_rt'];
                                $reservation_lugg_info->d_price = $inputs['total_fare_amt_'.$i.'_rt'];
                                $reservation_lugg_info->save();
                                $luggages_amount_rt = $luggages_amount_rt + $reservation_lugg_info->d_price;
                            }
                            if(isset($inputs['pet_available_'.$i.'_rt']) && isset($inputs['total_fare_amt_pet_'.$i.'_rt']) && ($inputs['total_fare_amt_pet_'.$i.'_rt'] != '' && $inputs['total_fare_amt_pet_'.$i.'_rt'] != 0)) {
                                
                                $reservation_lugg_info = new ReservationLuggageInfo;
                                $reservation_lugg_info->i_reservation_id = $round_trip_record->id;
                                $reservation_lugg_info->i_sys_luggage_id = $inputs['animal_rec_id_'.$i.'_rt'];
                                $reservation_lugg_info->i_value = 1;
                                $reservation_lugg_info->d_price = $inputs['total_fare_amt_pet_'.$i.'_rt'];
                                $reservation_lugg_info->save();
                                $animal_amount_rt = $animal_amount_rt + $reservation_lugg_info->d_price;
                            }
                            $total_amount_rt = $luggages_amount_rt + $animal_amount_rt;

                            //Luggage record delete for Return Trip
                            if(isset($inputs['luggage_data_id_'.$i.'_rt']) &&  isset($inputs['luggage_numbers_'.$i.'_rt']) && $inputs['luggage_numbers_'.$i.'_rt'] == 0) {
                                $old_passanger_info = ReservationLuggageInfo::where('id', $inputs['luggage_data_id_'.$i.'_rt'])->where('i_reservation_id',$record_rt->id)->delete();
                            }
                            if(isset($inputs['luggage_data_id_'.$i.'_rt']) && isset($inputs['total_fare_amt_pet_'.$i.'_rt']) && $inputs['total_fare_amt_pet_'.$i.'_rt'] == 0) {
                                $old_passanger_info = ReservationLuggageInfo::where('id', $inputs['luggage_data_id_'.$i.'_rt'])->where('i_reservation_id',$record_rt->id)->delete();
                            }
                        }
                    } 
                    
                    // store final amount of reservation
                    $final_fare_amount = $total_fare_amount + $total_amount;
                    $record->d_total_fare = $final_fare_amount;
                    $record->save();

                    if($inputs['e_class_type'] == 'RT') {
                        $final_fare_amount_rt = $total_fare_amount_rt + $total_amount_rt;
                        $round_trip_record->d_total_fare = $final_fare_amount_rt;
                        $round_trip_record->save();
                    }
                    Session::flash('success-message', 'Reservations edited successfully.');
                    return '';
                }
            }
        } else {
            $admin_data = Admin::where('e_status', 'Active')->select('id', 'v_firstname', 'v_lastname')->orderBy('v_firstname')->get()->toArray();
            $service_area = GeoPoint::with(['GeoCities' => function($q) {
                $q->where('e_status', 'Active')->select('id', 'v_city', 'v_county');
            }])->select('id', 'v_street1', 'v_postal_code', 'i_city_id')->orderBy('v_street1')->get()->toArray();
            $reservation_category = SystemResCategory::select('id', 'v_label')->orderBy('v_label')->get()->toArray();
            $customer_data = Customers::where('e_status', 'Active')->select('id', 'v_firstname', 'v_lastname')->orderBy('v_firstname')->get()->toArray();
            $animals_list = SystemLuggageDef::where('e_type', 'Animal')->select('id', 'v_name', 'e_type', 'd_unit_price')->orderBy('v_name')->get()->toArray();
            $luggages_list = SystemLuggageDef::where('e_type', 'Luggage')->select('id', 'v_name', 'e_type', 'd_unit_price')->orderBy('v_name')->get()->toArray();
            $reservation_data = ReservationTravellerInfo::where('i_reservation_id', $record->id)->get()->toArray();
            $reservation_luggage_info = ReservationLuggageInfo::where('i_reservation_id', $record->id)->get()->toArray();
            if($reservation_record->e_class_type == 'RT') {
                $reservation_luggage_info_rt = ReservationLuggageInfo::where('i_reservation_id', $record_rt->id)->get()->toArray();
                $reservation_data_rt = ReservationTravellerInfo::where('i_reservation_id', $record_rt->id)->get()->toArray();
            } else {
                $reservation_luggage_info_rt = array();
                $reservation_data_rt = array();
            }
            $system_icao_def = SystemIcaoDef::where('e_status', 'Active')->select('id', 'v_airline_name')->get()->toArray();
            return View('backend.reservations.edit', array('title' => 'Edit Reservations', 'service_area' => $service_area, 'reservation_category' => $reservation_category, 'admin_data' => $admin_data, 'customer_data' => $customer_data, 'record' => $record, 'reservation_data' => $reservation_data, 'animals_list' => $animals_list, 'luggages_list' => $luggages_list, 'reservation_luggage_info' => $reservation_luggage_info, 'system_icao_def' => $system_icao_def,'record_rt' => $record_rt, 'reservation_luggage_info_rt' => $reservation_luggage_info_rt, 'reservation_data_rt' => $reservation_data_rt));
        }
        return Redirect(ADMIN_URL . 'reservations');
    }

    public function anyView(Request $request, $id) {
        $inputs = $request->all();
        
        $edit_record = Reservations::where(['id' => $id])->first();
        
        $reservation_rec1 = $reservation_rec2 = '';
        if($edit_record['e_class_type'] == 'RT' && $edit_record['i_parent_id'] != NULL) {
            $record_rt = Reservations::where('id',$edit_record['i_parent_id'])->first();
            $reservation_rec1 = $record_rt['id'];
            $reservation_rec2 = $id;

        } elseif ($edit_record['e_class_type'] == 'RT' && $edit_record['i_parent_id'] == NULL) {
            $record_rt = Reservations::where('i_parent_id',$id)->first();
            $reservation_rec1 = $id;
            $reservation_rec2 = $record_rt['id'];
        } else{
            $reservation_rec1 = $id;
        }
        
        if(!empty($edit_record)) {    

            $reservation_record = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory','PickupCity','DropOffCity', 'ReservationLogs' => function($q) {
                $q->with(['CustomersLogs', 'AdminLogs', 'Reservations', 'Reservations.Customers'])->where('e_module_name', 'Reservation');
            }])->where(['id' => $reservation_rec1])->first();
            

            $payment_info = Transactions::where('i_reservation_id',$id)->whereIn('e_type',['Booked','Booked-Wallet','Cash-On-Board'])->orderBy('created_at','DESC')->get()->toArray();
            $payment_mode = $this->getPaymentMode($id);
            $reservation_luggage_info = ReservationLuggageInfo::with(['SystemLuggageDef'])->where('i_reservation_id', $reservation_rec1)->get()->toArray();
            $reservation_pet_info = ReservationLuggageInfo::with(['SystemAnimalDef'])->where('i_reservation_id', $reservation_rec1)->get()->toArray();
            $reservation_luggage_info_total = ReservationLuggageInfo ::where('i_reservation_id', $reservation_rec1)->get()->sum('d_price');
            
                
            $total_fare_amount = ReservationTravellerInfo::where('i_reservation_id', $reservation_rec1)->select(DB::raw('SUM(d_fare_amount) AS total'), DB::raw('SUM(IF(e_type = "Adult", d_fare_amount, 0)) AS adult_total'), DB::raw('SUM(IF(e_type = "Child", d_fare_amount, 0)) AS child_total'),DB::raw('SUM(IF(e_type = "Senior", d_fare_amount, 0)) AS senior_total'),DB::raw('SUM(IF(e_type = "Military", d_fare_amount, 0)) AS military_total'),DB::raw('SUM(IF(e_type = "Infant", d_fare_amount, 0)) AS infant_total'), DB::raw('SUM(IF(e_type = "Adult", 1, 0)) AS adult_count'), DB::raw('SUM(IF(e_type = "Child", 1, 0)) AS child_count'), DB::raw('SUM(IF(e_type = "Senior", 1, 0)) AS senior_count'), DB::raw('SUM(IF(e_type = "Military", 1, 0)) AS military_count'), DB::raw('SUM(IF(e_type = "Infant", 1, 0)) AS infant_count'))->first()->toArray();

            $reservation_luggage_info_total_rt = "";
            $reservation_record_rt = $reservation_luggage_info_rt = $reservation_pet_info_rt = $total_fare_amount_rt = array();
            $res1_tt_text = $this->getTravelTypeText($reservation_record->i_reservation_category_id,$reservation_record->i_dropoff_point_type_id);
            $res2_tt_text = false;
            if($reservation_record['e_class_type'] == 'RT') {

                $reservation_record_rt = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory','PickupCity','DropOffCity'])->where(['id'=>$reservation_rec2])->first();
                
               
                $reservation_luggage_info_rt = ReservationLuggageInfo::with(['SystemLuggageDef'])->where('i_reservation_id', $reservation_rec2)->get()->toArray();
                $reservation_pet_info_rt = ReservationLuggageInfo::with(['SystemAnimalDef'])->where('i_reservation_id', $reservation_rec2)->get()->toArray();
                $total_fare_amount_rt = ReservationTravellerInfo::where('i_reservation_id', $reservation_rec2)->select(DB::raw('SUM(d_fare_amount) AS total'), DB::raw('SUM(IF(e_type = "Adult", d_fare_amount, 0)) AS adult_total'), DB::raw('SUM(IF(e_type = "Child", d_fare_amount, 0)) AS child_total'),DB::raw('SUM(IF(e_type = "Senior", d_fare_amount, 0)) AS senior_total'),DB::raw('SUM(IF(e_type = "Military", d_fare_amount, 0)) AS military_total'),DB::raw('SUM(IF(e_type = "Infant", d_fare_amount, 0)) AS infant_total'), DB::raw('SUM(IF(e_type = "Adult", 1, 0)) AS adult_count'), DB::raw('SUM(IF(e_type = "Child", 1, 0)) AS child_count'), DB::raw('SUM(IF(e_type = "Senior", 1, 0)) AS senior_count'), DB::raw('SUM(IF(e_type = "Military", 1, 0)) AS military_count'), DB::raw('SUM(IF(e_type = "Infant", 1, 0)) AS infant_count'))->first()->toArray();
                $reservation_luggage_info_total_rt = ReservationLuggageInfo ::where('i_reservation_id', $reservation_rec2)->get()->sum('d_price');
                $res2_tt_text = $this->getTravelTypeText($reservation_record_rt->i_reservation_category_id,$reservation_record_rt->i_dropoff_point_type_id);
            }
            
            return view('backend.reservations.view', array('title' => 'View Reservations','reservation_record' => $reservation_record,'reservation_record_rt' => $reservation_record_rt,'reservation_luggage_info'=>$reservation_luggage_info,'reservation_pet_info'=>$reservation_pet_info,'reservation_luggage_info_rt'=>$reservation_luggage_info_rt,'reservation_pet_info_rt'=>$reservation_pet_info_rt,'total_fare_amount'=>$total_fare_amount,'total_fare_amount_rt'=>$total_fare_amount_rt,'reservation_luggage_info_total'=>$reservation_luggage_info_total,'reservation_luggage_info_total_rt'=>$reservation_luggage_info_total_rt,'id'=>$id,'payment_info'=>$payment_info,'res1_tt_text' => $res1_tt_text,'res2_tt_text' => $res2_tt_text,'payment_mode' => $payment_mode));
            
        
        }   
    }

    public function getDelete($id) {
        $record = Reservations::find($id);
        if (!empty($record)) {
            if ($record->delete()) {
                return 'TRUE';
            } else {
                return 'FALSE';
            }
        } else {
            return 'FALSE';
        }
    }

    public function postBulkAction(Request $request) {
        $data = $request->all();
        if (count($data) > 0) {
            if ($data['action'] == 'Delete') {
                $user_data = Reservations::whereIn('id', array_values($data['ids']))->get();
                if ($user_data) {
                    foreach ($user_data as $data) {
                        $data->deleted_at = Carbon::now();
                        $data->save();
                        $data->delete();
                    }
                    return 'TRUE';
                } else {
                    return 'FALSE';
                }
            }
        }
    }

    public function getServicePointList(Request $request) {
        $data = $request->all();  
        $return_data = array();
        if(isset($data['q']['term'])) {
            $service_area = GeoPoint::with(['GeoCities' => function($q) {
                $q->where('e_status', 'Active')->select('id', 'v_city', 'v_county');
            }])->select('id', 'v_street1', 'v_postal_code', 'i_city_id')->orderBy('v_street1')->get()->toArray();
            $service_area = GeoPoint::with(['GeoCities'])->orWhereHas('GeoCities', function($q1) use($data) {
                            $q1->where('geo_cities.v_city', 'LIKE', '%' . $data['q']['term'] . '%');
                            $q1->orWhere('geo_cities.v_county', 'LIKE', '%' . $data['q']['term'] . '%'); 
                        })->orWhere('geo_point.v_street1', 'LIKE', '%' . $data['q']['term'] . '%')
                        ->get()->toArray();
            foreach($service_area as $key => $val) {
                $return_data[$key]['id'] = $val['id'];
                $return_data[$key]['text'] = $val['v_street1'].' ,'.$val['geo_cities']['v_city'];
            }
        }/*  else {
            $service_area = GeoPoint::where('i_origin_point_id',$data['point_id_origin'])->where('i_destination_point_id',$data['point_id_destination'])->select('id', 'v_street1', 'v_city', 'v_country', 'v_postal_code')->orderBy('v_street1')->get()->toArray();
            foreach($service_area as $key => $val) {
                $return_data[$key]['id'] = $val['id'];
                $return_data[$key]['text'] = $val['v_street1'].' ,'.$val['v_city'].' ,'.$val['v_country'].' ,'.$val['v_postal_code'];
            } 
        }*/
        return json_encode($return_data);
        // return ['service_point' => $return_data];
    }

    public function getServiceDestinationPointList(Request $request) {
        $data = $request->all();  
        $return_data = array();
        if(isset($data['q']['term'])) {
            $geo_point_city_id = GeoPoint::where('id', $data['city_id'])->select('id','i_city_id')->first();
            $drop_of_city = GeoCities::where('id', $geo_point_city_id['i_city_id'])->select('v_drop_off_city_cant_be', 'v_drop_off_city_must_be')->first();
            $drop_off_city_cant_be =  explode(',', $drop_of_city['v_drop_off_city_cant_be']);
            $drop_off_city_must_be = explode(',', $drop_of_city['v_drop_off_city_must_be']);
            DB::enableQueryLog();

            if((!empty($drop_of_city['v_drop_off_city_cant_be']) && !empty($drop_of_city['v_drop_off_city_must_be'])) || (empty($drop_of_city['v_drop_off_city_cant_be']) && !empty($drop_of_city['v_drop_off_city_must_be']))) {

                $service_area = GeoPoint::with(['GeoCities'])->orWhereHas('GeoCities', function($q) use($data, $v_drop_off_city_must_be) {
                    $q->whereIn('geo_cities.id', $v_drop_off_city_must_be)->where(function($q1) use($data) {
                        $q1->Where('geo_point.v_street1', 'LIKE', '%'.$data['q']['term'].'%');
                        $q1->orWhere('geo_cities.v_city', 'LIKE', '%' . $data['q']['term'] . '%');
                        $q1->orWhere('geo_cities.v_county', 'LIKE', '%' . $data['q']['term'] . '%');
                    });
                })->where('geo_point.id', '<>', $data['city_id'])->get()->toArray();

            } else if(!empty($drop_of_city['v_drop_off_city_cant_be']) && empty($drop_of_city['v_drop_off_city_must_be'])) {
                $service_area = GeoPoint::with(['GeoCities'])->orWhereHas('GeoCities', function($q1) use($data, $drop_off_city_cant_be) {

                    $q1->whereNotIn('geo_cities.id', $drop_off_city_cant_be)->where(function($q2) use($data){
                        $q2->Where('geo_point.v_street1', 'LIKE', '%'.$data['q']['term'].'%')
                           ->orWhere('geo_cities.v_city', 'LIKE', '%' . $data['q']['term'] . '%')
                           ->orWhere('geo_cities.v_county', 'LIKE', '%' . $data['q']['term'] . '%');
                    });
                })->where('geo_point.id', '<>', $data['city_id'])->get()->toArray();

            } else {
                $service_area = GeoPoint::with(['GeoCities'])->orWhereHas('GeoCities', function($q1) use($data) {
                    $q1->where('geo_cities.v_city', 'LIKE', '%' . $data['q']['term'] . '%');
                    $q1->orWhere('geo_cities.v_county', 'LIKE', '%' . $data['q']['term'] . '%'); 
                })->where('geo_point.id', '<>', $data['city_id'])
                  ->orWhere('geo_point.v_street1', 'LIKE', '%' . $data['q']['term'] . '%')->get()->toArray();

            }

            foreach($service_area as $key => $val) {
                if($data['city_id'] == $val['id']) {
                    $return_data[$key]['id'] = '';
                    $return_data[$key]['text'] = '';
                } else {
                    $return_data[$key]['id'] = $val['id'];
                    if(isset($val['geo_cities'])) {
                        $return_data[$key]['text'] = $val['v_street1'].' ,'.$val['geo_cities']['v_city'];
                    } else {
                        $return_data[$key]['text'] = $val['v_street1'].' ,'.$val['v_city'];
                    }
                }
            }
        }
        return json_encode($return_data);
    }

    public function getAirlineList(Request $request) {

        $data = $request->all();  
        $return_data = array();
        if(isset($data['q']['term'])) {
            DB::enableQueryLog();
            $airline_list = SystemIcaoDef::where('e_status', 'Active')
                                    ->where('sys_icao_def.v_airline_name', 'LIKE', '%' . $data['q']['term'] . '%')
                                    ->select('sys_icao_def.id','sys_icao_def.v_airline_name')
                                    ->distinct('v_airline_name')
                                    ->get()->toArray();
            foreach($airline_list as $key => $val) {
                $return_data[$key]['id'] = $val['id'];
                $return_data[$key]['text'] = $val['v_airline_name'];
            }
        }
        return json_encode($return_data);
    }

    public function anyResevationDetailDownload(Request $request, $id){
      
        $inputs = $request->all();
        $edit_record = Reservations::where(['id' => $id])->first();
        $reservation_rec1 = $reservation_rec2 = '';

        if($edit_record['e_class_type'] == 'RT' && $edit_record['i_parent_id'] != NULL) {
            $record_rt = Reservations::where('id',$edit_record['i_parent_id'])->first();
            $reservation_rec1 = $record_rt['id'];
            $reservation_rec2 = $id;

        } elseif ($edit_record['e_class_type'] == 'RT' && $edit_record['i_parent_id'] == NULL) {
            $record_rt = Reservations::where('i_parent_id',$id)->first();
            $reservation_rec1 = $id;
            $reservation_rec2 = $record_rt['id'];
        } else{
            $reservation_rec1 = $id;
        }
        if(!empty($edit_record)) {   
            $reservation_record = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory','PickupCity','DropOffCity'])->where(['id' => $reservation_rec1])->first();         
            $payment_info = Transactions::where('i_reservation_id',$id)->whereIn('e_type',['Booked','Booked-Wallet','Cash-On-Board'])->orderBy('created_at','DESC')->get()->toArray();
            $payment_mode = $this->getPaymentMode($id);
            $reservation_luggage_info = ReservationLuggageInfo::with(['SystemLuggageDef'])->where('i_reservation_id', $reservation_rec1)->get()->toArray();
            $reservation_pet_info = ReservationLuggageInfo::with(['SystemAnimalDef'])->where('i_reservation_id', $reservation_rec1)->get()->toArray();
            $reservation_luggage_info_total = ReservationLuggageInfo ::where('i_reservation_id', $reservation_rec1)->get()->sum('d_price');
                
            $total_fare_amount = ReservationTravellerInfo::where('i_reservation_id', $reservation_rec1)->select(DB::raw('SUM(d_fare_amount) AS total'), DB::raw('SUM(IF(e_type = "Adult", d_fare_amount, 0)) AS adult_total'), DB::raw('SUM(IF(e_type = "Child", d_fare_amount, 0)) AS child_total'),DB::raw('SUM(IF(e_type = "Senior", d_fare_amount, 0)) AS senior_total'),DB::raw('SUM(IF(e_type = "Military", d_fare_amount, 0)) AS military_total'),DB::raw('SUM(IF(e_type = "Infant", d_fare_amount, 0)) AS infant_total'), DB::raw('SUM(IF(e_type = "Adult", 1, 0)) AS adult_count'), DB::raw('SUM(IF(e_type = "Child", 1, 0)) AS child_count'), DB::raw('SUM(IF(e_type = "Senior", 1, 0)) AS senior_count'), DB::raw('SUM(IF(e_type = "Military", 1, 0)) AS military_count'), DB::raw('SUM(IF(e_type = "Infant", 1, 0)) AS infant_count'))->first()->toArray();

            $reservation_luggage_info_total_rt = "";
            $reservation_record_rt = $reservation_luggage_info_rt = $reservation_pet_info_rt = $total_fare_amount_rt = array();
            $res1_tt_text = $this->getTravelTypeText($reservation_record->i_reservation_category_id,$reservation_record->i_dropoff_point_type_id);
            $res2_tt_text = false;
            if($reservation_record['e_class_type'] == 'RT') {

                $reservation_record_rt = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory','PickupCity','DropOffCity'])->where(['id'=>$reservation_rec2])->first();
                
              
                $reservation_luggage_info_rt = ReservationLuggageInfo::with(['SystemLuggageDef'])->where('i_reservation_id', $reservation_rec2)->get()->toArray();
                $reservation_pet_info_rt = ReservationLuggageInfo::with(['SystemAnimalDef'])->where('i_reservation_id', $reservation_rec2)->get()->toArray();
                $total_fare_amount_rt = ReservationTravellerInfo::where('i_reservation_id', $reservation_rec2)->select(DB::raw('SUM(d_fare_amount) AS total'), DB::raw('SUM(IF(e_type = "Adult", d_fare_amount, 0)) AS adult_total'), DB::raw('SUM(IF(e_type = "Child", d_fare_amount, 0)) AS child_total'),DB::raw('SUM(IF(e_type = "Senior", d_fare_amount, 0)) AS senior_total'),DB::raw('SUM(IF(e_type = "Military", d_fare_amount, 0)) AS military_total'),DB::raw('SUM(IF(e_type = "Infant", d_fare_amount, 0)) AS infant_total'), DB::raw('SUM(IF(e_type = "Adult", 1, 0)) AS adult_count'), DB::raw('SUM(IF(e_type = "Child", 1, 0)) AS child_count'), DB::raw('SUM(IF(e_type = "Senior", 1, 0)) AS senior_count'), DB::raw('SUM(IF(e_type = "Military", 1, 0)) AS military_count'), DB::raw('SUM(IF(e_type = "Infant", 1, 0)) AS infant_count'))->first()->toArray();
                $reservation_luggage_info_total_rt = ReservationLuggageInfo ::where('i_reservation_id', $reservation_rec2)->get()->sum('d_price');
                $res2_tt_text = $this->getTravelTypeText($reservation_record_rt->i_reservation_category_id,$reservation_record_rt->i_dropoff_point_type_id);
            }
           
        
            PDF::setOutputMode('F'); 
            PDF::setPageSize('a4');
            PDF::setOrientation('portrait');
            PDF::setOptions('--disable-smart-shrinking');
            
            $filename = $reservation_record['v_reservation_number'];
            
            PDF::html('backend.reservations.download_pdf_data', array('title' => 'Rservation Summary','reservation_record' => $reservation_record,'reservation_record_rt' => $reservation_record_rt,'reservation_luggage_info'=>$reservation_luggage_info,'reservation_pet_info'=>$reservation_pet_info,'reservation_luggage_info_rt'=>$reservation_luggage_info_rt,'reservation_pet_info_rt'=>$reservation_pet_info_rt,'total_fare_amount'=>$total_fare_amount,'total_fare_amount_rt'=>$total_fare_amount_rt,'reservation_luggage_info_total'=>$reservation_luggage_info_total,'reservation_luggage_info_total_rt'=>$reservation_luggage_info_total_rt,'payment_info'=>$payment_info,'payment_mode' => $payment_mode,'res1_tt_text' => $res1_tt_text,'res2_tt_text' => $res2_tt_text),ADMIN_FILES_PATH.$filename);
            
            return Response::download(ADMIN_FILES_PATH.$filename.'.pdf');
        } else {
            return redirect(ADMIN_URL);
        }
            
    }

    public function anyResevationDetailPrint(Request $request, $id){
      
        
            $inputs = $request->all();
           
            $edit_record = Reservations::where(['id' => $id])->first();
            $reservation_rec1 = $reservation_rec2 = '';
            if($edit_record['e_class_type'] == 'RT' && $edit_record['i_parent_id'] != NULL) {
                $record_rt = Reservations::where('id',$edit_record['i_parent_id'])->first();
                $reservation_rec1 = $record_rt['id'];
                $reservation_rec2 = $id;
    
            } elseif ($edit_record['e_class_type'] == 'RT' && $edit_record['i_parent_id'] == NULL) {
                $record_rt = Reservations::where('i_parent_id',$id)->first();
                $reservation_rec1 = $id;
                $reservation_rec2 = $record_rt['id'];
            } else{
                $reservation_rec1 = $id;
            }
            if(!empty($edit_record)) {     
                $reservation_record = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory','PickupCity','DropOffCity'])->where(['id' => $reservation_rec1])->first();       
                $payment_info = Transactions::where('i_reservation_id',$id)->whereIn('e_type',['Booked','Booked-Wallet','Cash-On-Board'])->orderBy('created_at','DESC')->get()->toArray();
            $payment_mode = $this->getPaymentMode($id);
                $reservation_luggage_info = ReservationLuggageInfo::with(['SystemLuggageDef'])->where('i_reservation_id', $reservation_rec1)->get()->toArray();
                $reservation_pet_info = ReservationLuggageInfo::with(['SystemAnimalDef'])->where('i_reservation_id', $reservation_rec1)->get()->toArray();
                $reservation_luggage_info_total = ReservationLuggageInfo ::where('i_reservation_id', $reservation_rec1)->get()->sum('d_price');
                    
                $total_fare_amount = ReservationTravellerInfo::where('i_reservation_id', $reservation_rec1)->select(DB::raw('SUM(d_fare_amount) AS total'), DB::raw('SUM(IF(e_type = "Adult", d_fare_amount, 0)) AS adult_total'), DB::raw('SUM(IF(e_type = "Child", d_fare_amount, 0)) AS child_total'),DB::raw('SUM(IF(e_type = "Senior", d_fare_amount, 0)) AS senior_total'),DB::raw('SUM(IF(e_type = "Military", d_fare_amount, 0)) AS military_total'),DB::raw('SUM(IF(e_type = "Infant", d_fare_amount, 0)) AS infant_total'), DB::raw('SUM(IF(e_type = "Adult", 1, 0)) AS adult_count'), DB::raw('SUM(IF(e_type = "Child", 1, 0)) AS child_count'), DB::raw('SUM(IF(e_type = "Senior", 1, 0)) AS senior_count'), DB::raw('SUM(IF(e_type = "Military", 1, 0)) AS military_count'), DB::raw('SUM(IF(e_type = "Infant", 1, 0)) AS infant_count'))->first()->toArray();

                $reservation_luggage_info_total_rt = "";
                $reservation_record_rt = $reservation_luggage_info_rt = $reservation_pet_info_rt = $total_fare_amount_rt = array();
                $res1_tt_text = $this->getTravelTypeText($reservation_record->i_reservation_category_id,$reservation_record->i_dropoff_point_type_id);
                $res2_tt_text = false;
                if($reservation_record['e_class_type'] == 'RT') {
    
                    $reservation_record_rt = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory','PickupCity','DropOffCity'])->where(['id'=>$reservation_rec2])->first();
                    
                    if(empty($reservation_record_rt)) {
                        return redirect(FRONTEND_URL.'book-a-shuttle');
                    }
                    $reservation_luggage_info_rt = ReservationLuggageInfo::with(['SystemLuggageDef'])->where('i_reservation_id', $reservation_rec2)->get()->toArray();
                    $reservation_pet_info_rt = ReservationLuggageInfo::with(['SystemAnimalDef'])->where('i_reservation_id', $reservation_rec2)->get()->toArray();
                    $total_fare_amount_rt = ReservationTravellerInfo::where('i_reservation_id', $reservation_rec2)->select(DB::raw('SUM(d_fare_amount) AS total'), DB::raw('SUM(IF(e_type = "Adult", d_fare_amount, 0)) AS adult_total'), DB::raw('SUM(IF(e_type = "Child", d_fare_amount, 0)) AS child_total'),DB::raw('SUM(IF(e_type = "Senior", d_fare_amount, 0)) AS senior_total'),DB::raw('SUM(IF(e_type = "Military", d_fare_amount, 0)) AS military_total'),DB::raw('SUM(IF(e_type = "Infant", d_fare_amount, 0)) AS infant_total'), DB::raw('SUM(IF(e_type = "Adult", 1, 0)) AS adult_count'), DB::raw('SUM(IF(e_type = "Child", 1, 0)) AS child_count'), DB::raw('SUM(IF(e_type = "Senior", 1, 0)) AS senior_count'), DB::raw('SUM(IF(e_type = "Military", 1, 0)) AS military_count'), DB::raw('SUM(IF(e_type = "Infant", 1, 0)) AS infant_count'))->first()->toArray();
                    $reservation_luggage_info_total_rt = ReservationLuggageInfo ::where('i_reservation_id', $reservation_rec2)->get()->sum('d_price');
                    $res2_tt_text = $this->getTravelTypeText($reservation_record_rt->i_reservation_category_id,$reservation_record_rt->i_dropoff_point_type_id);
                }
               
                return view('backend.reservations.download_pdf_data', array('title' => 'Rservation Summary','reservation_record' => $reservation_record,'reservation_record_rt' => $reservation_record_rt,'reservation_luggage_info'=>$reservation_luggage_info,'reservation_pet_info'=>$reservation_pet_info,'reservation_luggage_info_rt'=>$reservation_luggage_info_rt,'reservation_pet_info_rt'=>$reservation_pet_info_rt,'total_fare_amount'=>$total_fare_amount,'total_fare_amount_rt'=>$total_fare_amount_rt,'reservation_luggage_info_total'=>$reservation_luggage_info_total,'reservation_luggage_info_total_rt'=>$reservation_luggage_info_total_rt,'id',$id,'payment_info'=>$payment_info,'res1_tt_text' => $res1_tt_text,'res2_tt_text' => $res2_tt_text,'payment_mode' => $payment_mode));
                
                
            } else {
                return redirect(ADMIN_URL);
            }
        
            
    }
    
    public function anyAddSpecialInstructionStatus(Request $request, $id) {
        $inputs = $request->all();
        $record = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory','PickupCity','DropOffCity'])->where('id',$id)->first();

        if($inputs['e_reservation_status']=="Refunded" && $record->e_reservation_status=="Refund Requested") {
            $payment_info_booked = Transactions::where('i_reservation_id',$id)->orderBy('created_at','DESC')->where('e_type','Booked')->first();

            $payment_info_refunded = Transactions::where('i_reservation_id',$id)->where('e_type','Refunded')->first();
            if($payment_info_refunded) {
                $record->e_reservation_status = $inputs['e_reservation_status'];
                $record->save();
                return response()->json(['status' => 'TRUE', 'message' => "Refund already processed for this booking",'refund_process' => 1]);
            }
            if($payment_info_booked && !$payment_info_refunded && $payment_info_booked['e_status']=="Success") {
                $refund_amount = $payment_info_booked->d_amount;
                if($inputs['refund_option']=='wallet') {
                    $record->e_reservation_status = $inputs['e_reservation_status'];
                    $record->t_special_instruction = trim($inputs['t_special_instruction']);
                    $record->save();
                    $customer = Customers::find($record->i_customer_id);
                    $customer->d_wallet_balance = $customer->d_wallet_balance + $refund_amount;
                    $customer->save();
                    return response()->json(['status' => 'TRUE', 'message' => "Refund processed successfully. Amount added to customer wallet.",'refund_process' => 1]);
                } else {

                    try{
    
                        $stripe = new \Stripe\StripeClient(STRIP_API_KEY);
                        $res = $stripe->refunds->create(['charge' => $payment_info_booked->v_stripe_payment_id,'amount' => $refund_amount]);
                        
                        if($res && $res->status=="succeeded" || $res->status=="pending") {
                            $trans = new Transactions;
                            $trans->i_customer_id = $record->i_customer_id;
                            $trans->v_stripe_payment_id = $res->id;
                            $trans->i_reservation_id = $id;
                            $trans->d_amount = ($res->amount) / 100;
                            $trans->e_status = "Success";
                            $trans->e_type = "Refunded";
                            $trans->save();
                            $record->t_special_instruction = trim($inputs['t_special_instruction']);
                            $record->e_reservation_status = $inputs['e_reservation_status'];
                            $record->save();
                            return response()->json(['status' => 'TRUE', 'message' => "Refund processed successfully.",'refund_process' => 1]);
                        } else {
                            $msg = "Error occurred in processing refund";
                            if($res && $res->status=="failed") {
                                $msg = $res->failure_reason;
                            }
                            return response()->json(['status' => 'FALSE', 'message' => $msg,'refund_process' => 1]);
                        }
    
                    } catch (\Stripe\Exception\CardException $e) {
                        return response()->json(['status' => 'FALSE', 'message' => $e->getMessage(),'refund_process' => 1]);
                    } catch (\Stripe\Exception\InvalidRequestException $e) {
                        return response()->json(['status' => 'FALSE', 'message' => $e->getMessage(),'refund_process' => 1]);
                    } catch (\Stripe\Exception\AuthenticationException $e) {
                        return response()->json(['status' => 'FALSE', 'message' => $e->getMessage(),'refund_process' => 1]);
                    } catch (\Stripe\Exception\RateLimitException $e) {
                        return response()->json(['status' => 'FALSE', 'message' => $e->getMessage(),'refund_process' => 1]);
                    } catch (\Stripe\Exception\ApiConnectionException $e) {
                        return response()->json(['status' => 'FALSE', 'message' => $e->getMessage(),'refund_process' => 1]);
                    } catch (\Stripe\Exception\ApiErrorException $e) {
                        return response()->json(['status' => 'FALSE', 'message' => $e->getMessage(),'refund_process' => 1]);
                    } catch (Exception $e) {
                        return response()->json(['status' => 'FALSE', 'message' => $e->getMessage(),'refund_process' => 1]);
                    }
                }


            } else {
                $payment_info_booked_wallet = Transactions::where('i_reservation_id',$id)->orderBy('created_at','DESC')->where('e_type','Booked-Wallet')->first();
                if($payment_info_booked_wallet) {
                    $refund_amount = $payment_info_booked_wallet->d_amount;
                    if($inputs['refund_option']=='wallet') {
                        $record->e_reservation_status = $inputs['e_reservation_status'];
                        $record->t_special_instruction = trim($inputs['t_special_instruction']);
                        $record->save();
                        $customer = Customers::find($record->i_customer_id);
                        $customer->d_wallet_balance = $customer->d_wallet_balance + $refund_amount;
                        $customer->save();
                        return response()->json(['status' => 'TRUE', 'message' => "Refund processed successfully. Amount added to customer wallet.",'refund_process' => 1]);
                    } else {
                        return response()->json(['status' => 'FALSE', 'message' => "Reservation done via full wallet balance can not be refunded to bank account.",'refund_process' => 1]);
                    }
                } else {
                    return response()->json(['status' => 'FALSE', 'message' => "Error occurred in processing refund",'refund_process' => 1]);
                }
            }
        } else if($record->e_shuttle_type=="Private" && in_array($inputs['e_reservation_status'],["Request Confirmed"]) && $record->e_reservation_status=="Requested"){
            
            $objEmailTemplate = EmailTemplate::find(10)->toArray();
            
            $total_amount = ($inputs['d_total_fare']) ? '$'.number_format((float)$inputs['d_total_fare'], 2, '.', '') : 0; 
         
            $payment_link = SITE_URL."payment/".$id;
            $htmlData = $this->getAddressInfo($record['id']);
            if($objEmailTemplate) {
                $strTemplate = $objEmailTemplate['t_email_content'];
                $strTemplate = str_replace('[SITE_NAME]', SITE_NAME, $strTemplate);
                $strTemplate = str_replace('[TICKET_INFORMATION]', $htmlData, $strTemplate);
                $strTemplate = str_replace('[CUSTOMER_NAME]',$record->v_contact_name,$strTemplate);
                $strTemplate = str_replace('[PAYMENT_LINK]',$payment_link,$strTemplate);
                $strTemplate = str_replace('[TOTAL_AMOUNT]',$total_amount,$strTemplate);
                if(trim($inputs['t_special_instruction'])!=''){
                    $strTemplate = str_replace('[ADMIN_NOTES]',"<p><strong>Admin Notes</strong>: ".$inputs['t_special_instruction']."</p>",$strTemplate);
                } else {
                    $strTemplate = str_replace('[ADMIN_NOTES]',"",$strTemplate);
                }

                $subject = $objEmailTemplate['v_template_subject'];
                
                // mail sent to user with new link
                
                Mail::send('emails.auth.generate-email-template', array('strTemplate'=>$strTemplate), function($message) use ($subject,$record)
                {
                    $message->from(CONTACT_EMAIL_ID, SITE_NAME);
                    $message->to($record->v_contact_email);
                    if($record->Customers->v_email != $record->v_contact_email){			
                        $message->replyTo($record->Customers->v_email);	
                    }
                    $message->subject($subject);
                });
            }
        } else if($record->e_shuttle_type=="Private" && in_array($inputs['e_reservation_status'],["Rejected"]) && $record->e_reservation_status=="Requested"){
            $objEmailTemplate = EmailTemplate::find(12)->toArray();
            $htmlData = $this->getAddressInfo($record['id']);
            if($objEmailTemplate) {
                /* $pick_city = $record['PickupCity']['v_city'];
                $drop_city = $record['DropOffCity']['v_city']; */
                $strTemplate = $objEmailTemplate['t_email_content'];
                $strTemplate = str_replace('[SITE_NAME]', SITE_NAME, $strTemplate);
                $strTemplate = str_replace('[TICKET_INFORMATION]', $htmlData, $strTemplate);
               /*  $strTemplate = str_replace('[PICK_LOC]', $pick_city." - ".$record['v_pickup_address'], $strTemplate);
                $strTemplate = str_replace('[DROP_LOC]',$drop_city." - ".$record['v_dropoff_address'], $strTemplate);
                $strTemplate = str_replace('[TRAVEL_DATE]',date("m/d/Y",strtotime($record->d_travel_date)),$strTemplate);
                $strTemplate = str_replace('[TRAVEL_TIME]',date('g:i A' , strtotime($record->t_comfortable_time)),$strTemplate);
                $strTemplate = str_replace('[TRIP_TYPE]',$record->e_shuttle_type,$strTemplate);
                $strTemplate = str_replace('[NO_OF_PASSENGERS]',$record->i_total_num_passengers,$strTemplate); */
                $strTemplate = str_replace('[CUSTOMER_NAME]',$record->v_contact_name,$strTemplate);
                if(trim($inputs['t_special_instruction'])!=''){
                    $strTemplate = str_replace('[ADMIN_NOTES]',"<p><strong>Admin Notes</strong>: ".$inputs['t_special_instruction']."</p>",$strTemplate);
                } else {
                    $strTemplate = str_replace('[ADMIN_NOTES]',"",$strTemplate);
                }

                $subject = $objEmailTemplate['v_template_subject'];
               /*  pr($strTemplate);
                exit; */
                // mail sent to user with new link
                
                Mail::send('emails.auth.generate-email-template', array('strTemplate'=>$strTemplate), function($message) use ($subject,$record)
                {
                    $message->from(CONTACT_EMAIL_ID, SITE_NAME);
                    $message->to($record->v_contact_email);
                    if($record->Customers->v_email != $record->v_contact_email){			
                        $message->replyTo($record->Customers->v_email);	
                    }
                    $message->subject($subject);
                });
            }
        }
        
        $record->e_reservation_status = $inputs['e_reservation_status'];
        $record->t_special_instruction = trim($inputs['t_special_instruction']);
        
        if($record['e_shuttle_type']  == "Private"){
            $record_rt = null;

            if($record['e_class_type'] == "RT"){
                $record_rt = Reservations::where('i_parent_id',$id)->first();
            }

            if(isset($inputs['d_total_fare'])) {
                $d_total_fare = str_replace('$', '',$inputs['d_total_fare']);
                if($record_rt) {
                    $record->d_total_fare =  ($d_total_fare / 2);
                    $record_rt->d_total_fare =  ($d_total_fare / 2);
                } else {
                    $record->d_total_fare =  $d_total_fare;
                }
            }

            if($record_rt) {
                $record_rt->e_reservation_status = $inputs['e_reservation_status'];
                $record_rt->save();
            }
            $record->e_reservation_status = $inputs['e_reservation_status'];
        }
        $record->save();
        return 'RESERVATION_VIEW_RECORD_ADD';
    }

    public function anyExportToExacle() {
        return Excel::download(new ReservationListExport, 'reservation-list.xlsx');
    }

    public function anyListAjaxReservationLog(Request $request,$id){
    
        $data = $request->all();
        $sortColumn = array('','','','','','');
        $query = Logs::with(['CustomersLogs', 'AdminLogs', 'Reservations','Reservations.Customers'])->where('i_rel_id',$id)->where('e_module_name', 'Reservation');
        
        $rec_per_page = REC_PER_PAGE;
        if (isset($data['length'])) {
            if ($data['length'] == '-1') {
                $rec_per_page = '';
            } else {
                $rec_per_page = $data['length'];
            }
        }

        $sort_order = $data['order']['0']['dir'];
        $order_field = $sortColumn[$data['order']['0']['column']];
        if ($sort_order != '' && $order_field != '') {
            $query = $query->orderBy($order_field, $sort_order);
        } else {
            $query = $query->orderBy('updated_at', 'desc');
        }
        $users = $query->paginate($rec_per_page);
        ;
        $arrUsers = $users->toArray();
        /* pr($arrUsers);
        exit; */
        $data = array();
        
        foreach ($arrUsers['data'] as $key => $logs) {
            
           /* pr($logs);
           exit; */

           /*  foreach ($val['reservation_logs'] as $logs){ */
                $index = 0;
                $log = json_decode($logs['v_log_json'] , true);
                
                if(isset($log['data']['oldArray']['Reservation Status'])) {
                    $old_status = $log['data']['oldArray']['Reservation Status'];
                }else{
                    $old_status = "";
                }
                $data[$key][$index++] =$old_status;

                if(isset($log['data']['newArray']['Reservation Status'])) {
                    $new_satus = $log['data']['newArray']['Reservation Status'];
                } else{
                    $new_satus = "";
                }
                $data[$key][$index++] = $new_satus;

                if(isset($log['data']['newArray']['Special Instruction'])) {
                    $special_instruction = $log['data']['newArray']['Special Instruction'].'</br>';
                } else{
                    $special_instruction = '';
                }
                if(isset($log['data']['newArray']['Total Fare'])) {
                    $total_fare = "<p class='kt-mt-5 kt-mb-0'><b>Amount to Pay:</b> $". $log['data']['newArray']['Total Fare'].'</p>';
                } else{
                    $total_fare = "";
                }
                $data[$key][$index++] = $special_instruction.''.$total_fare;

               
              
               
                if($logs['i_modified_by'] == 0){
                   /*  pr($logs);
                    exit; */
                    $modified_by =  $logs['reservations'][0]['customers']['v_firstname'] .' '.$logs['reservations'][0]['customers']['v_lastname'];
                   
                } elseif($logs['e_user_type'] == 'Customer'){

                    $modified_by = $logs['customers_logs']['v_firstname'].' '.$logs['customers_logs']['v_lastname'];

                } elseif($logs['e_user_type'] == 'Admin'){

                    $modified_by = $logs['admin_logs']['v_firstname'].' '.$logs['admin_logs']['v_lastname'];
                }                
                $data[$key][$index++] = $modified_by;

                $data[$key][$index++] = $logs['e_user_type'];

                $data[$key][$index++] = date(DATETIME_FORMAT,strtotime($logs['created_at']));
            /* } */
           
        }
        $return_data['data'] = $data;
        $return_data['iTotalRecords'] = $arrUsers['total'];
        $return_data['iTotalDisplayRecords'] = $arrUsers['total'];
        $return_data['data_array'] = $arrUsers['data'];
        return $return_data;
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
         
           return view('frontend.customer_reservation.mail-address-info', array('title' => 'Rservation Summary','reservation_record' => $reservation_record,'reservation_record_rt' => $reservation_record_rt,'reservation_luggage_info'=>$reservation_luggage_info,'reservation_pet_info'=>$reservation_pet_info,'reservation_luggage_info_rt'=>$reservation_luggage_info_rt,'reservation_pet_info_rt'=>$reservation_pet_info_rt,'total_fare_amount'=>$total_fare_amount,'total_fare_amount_rt'=>$total_fare_amount_rt,'reservation_luggage_info_total'=>$reservation_luggage_info_total,'reservation_luggage_info_total_rt'=>$reservation_luggage_info_total_rt))->render();
           
        }

    }
}
?>
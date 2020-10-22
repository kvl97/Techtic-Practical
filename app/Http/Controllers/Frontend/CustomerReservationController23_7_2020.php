<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customers;
use App\Models\Reservations;
use App\Models\GeoPoint;
use App\Models\SystemLuggageDef;
use App\Models\ReservationLuggageInfo;
use App\Models\ReservationTravellerInfo;
use App\Models\LineRun;
use Carbon\Carbon;
use \Stripe\Stripe;
use Mail, Session, Redirect, Validator, DB, Hash, PDF,Response,Str;

class CustomerReservationController extends BaseController {

    public function LocationInfo(Request $request){
        //Session::flush();
        $inputs = $request->all();
        $customer_info = auth()->guard('customers')->user();
        $service_area = GeoPoint::with(['GeoCities' => function($q) {
            $q->where('e_status', 'Active')->select('*');
        }])->select('*')->orderBy('v_street1')->get()->toArray();
        
        if(!empty($inputs)){
            $customer = Customers::where("v_email",$inputs['v_email'])->first();
            if(!$customer) {
                $customer = new Customers;
                $myvalue = $inputs['v_name'];
                $arr = explode(' ',trim($myvalue));
                $customer->v_firstname = trim($arr[0]);
                $customer->v_lastname = trim($arr[1]);
                $customer->v_email = trim($inputs['v_email']);
                $customer->v_phone = trim($inputs['v_phone']);
                $temp_customer_password = Str::random(10);
                $customer->password = Hash::make($temp_customer_password);
                $customer->e_status = "Active";
                $customer->created_at = Carbon::now();
                $customer->save();
                $customer_id = $customer->id;
            }
            Session::put('customer_info',$customer);
            if(isset($inputs['e_class_type'])) {
                $inputs['e_class_type'] = ($inputs['e_class_type']=="One Way") ?'OW' : 'RT';
            }
            Session::put('location_info', $inputs);  
            return response()->json([
                'status' => 'TRUE',
                'redirect_url' => FRONTEND_URL.'select-line-runs',
            ]);
        }else {
            $customer_sess = Session::get('customer_info');
            $location_info = Session::get('location_info');
            
            if($customer_sess) {
                $customer_info = $customer_sess;
            }
            return view('frontend.customer_reservation.location-information', array('title' => 'Location Information','customer_info'=>$customer_info,'service_area'=>$service_area,'location_info' => $location_info));        
        }
    }

    public function SelectLineRun(Request $request){
        $inputs = $request->all();
        $location_info = Session::get("location_info");
        $customer_info = Session::get("customer_info");
        
        if(!empty($inputs)){ 
            $reservation_rec1 = Session::get('reservation_rec1');
            $reservation_rec2 = Session::get('reservation_rec2');

            $location_info['e_class_type'] = $inputs['trip_type'];    
            $location_info['e_shuttle_type'] = $inputs['shuttle_type'];    
            $location_info['d_depart_date'] = $inputs['d_depart_date'];    
            $location_info['pickup_location'] = $inputs['pickup_location'];    
            $location_info['dropoff_location'] = $inputs['dropoff_location'];    
            if($inputs['trip_type'] == 'RT'){
                $location_info['d_return_date'] = $inputs['d_return_date'];
                $location_info['pickup_location_rt'] = $inputs['dropoff_location'];
                $location_info['dropoff_location_rt'] = $inputs['pickup_location'];
            } else {
                $location_info['d_return_date'] = "";
                $location_info['pickup_location_rt'] = "";
                $location_info['dropoff_location_rt'] = "";
            }
            Session::put('location_info',$location_info);

            // Add New Records For Reservation
            if(!$reservation_rec1) {
                $resv1 = new Reservations;
                $resv1->i_customer_id = $customer_info->id;
                $resv1->e_shuttle_type = $location_info['e_shuttle_type'];
                $resv1->i_total_num_passengers = $location_info['peoples'];
                $resv1->v_contact_name = $location_info['v_name'];
                $resv1->v_contact_phone_number = $location_info['v_phone'];
                $resv1->v_contact_email = $location_info['v_email'];
                $resv1->e_class_type = $location_info['e_class_type'];
                $resv1->d_travel_date = date('Y-m-d', strtotime(trim($location_info['d_depart_date'])));

                if($resv1->save()) {
                    Session::put('reservation_rec1',$resv1->id);
                    $resv1->v_reservation_number = reservationNumber($resv1->id);
                    $resv1->save();

                    if($location_info['e_class_type'] == 'RT') {
                        $resv2 = new Reservations;
                        $resv2->i_customer_id = $customer_info->id;
                        $resv2->e_shuttle_type = $location_info['e_shuttle_type'];
                        $resv2->i_total_num_passengers = $location_info['peoples'];
                        $resv2->v_contact_name = $location_info['v_name'];
                        $resv2->v_contact_phone_number = $location_info['v_phone'];
                        $resv2->v_contact_email = $location_info['v_email'];
                        $resv2->e_class_type = $location_info['e_class_type'];
                        $resv2->d_travel_date = date('Y-m-d', strtotime(trim($location_info['d_return_date'])));
    
                        if($resv2->save()) {
                            Session::put('reservation_rec2',$resv2->id);
                            $resv2->v_reservation_number = reservationNumber($resv2->id);
                            $resv2->i_parent_id = $resv1->id;
                            $resv2->save();
                        }
                    }
                }
            } else { // Edit Existing Reservation Records
                $resv1 = Reservations::find($reservation_rec1);
                $resv1->i_customer_id = $customer_info->id;
                $resv1->e_shuttle_type = $location_info['e_shuttle_type'];
                $resv1->i_total_num_passengers = $location_info['peoples'];
                $resv1->v_contact_name = $location_info['v_name'];
                $resv1->v_contact_phone_number = $location_info['v_phone'];
                $resv1->v_contact_email = $location_info['v_email'];
                $resv1->e_class_type = $location_info['e_class_type'];
                $resv1->d_travel_date = date('Y-m-d', strtotime(trim($location_info['d_depart_date'])));

                if($resv1->save()) {
                    if($location_info['e_class_type'] == 'OW' && $reservation_rec2){
                        Session::forget('reservation_rec2');
                        Reservations::where('id',$reservation_rec2)->forceDelete();   
                    } else if($location_info['e_class_type'] == 'RT') {
                        if($reservation_rec2) {
                            $resv2 = Reservations::find($reservation_rec2);
                        } else {
                            $resv2 = new Reservations;
                        }
                        $resv2->i_customer_id = $customer_info->id;
                        $resv2->e_shuttle_type = $location_info['e_shuttle_type'];
                        $resv2->i_total_num_passengers = $location_info['peoples'];
                        $resv2->v_contact_name = $location_info['v_name'];
                        $resv2->v_contact_phone_number = $location_info['v_phone'];
                        $resv2->v_contact_email = $location_info['v_email'];
                        $resv2->e_class_type = $location_info['e_class_type'];
                        $resv2->d_travel_date = date('Y-m-d', strtotime(trim($location_info['d_return_date'])));
    
                        if($resv2->save()) {
                            if(!$reservation_rec2) {
                                Session::put('reservation_rec2',$resv2->id);
                                $resv2->v_reservation_number = reservationNumber($resv2->id);
                                $resv2->i_parent_id = $resv1->id;
                                $resv2->save();
                            }
                        }
                    }
                }
            }

            return response()->json([
                'status' => 'TRUE',
                'redirect_url' => FRONTEND_URL.'passenger-information',
            ]); 
        }else {
            if(empty($location_info) || empty($customer_info)) {
                return redirect(FRONTEND_URL.'location-information');
            }

            return view('frontend.customer_reservation.select-line-runs', array('title' => 'Select Line Runs','location_info' => $location_info));
        }
    }

    public function PassengerInfo(Request $request) {
        $inputs = $request->all();
        $reservation_rec1 = Session::get('reservation_rec1');
        $reservation_rec2 = Session::get('reservation_rec2');
        $reservation_record = Reservations::find($reservation_rec1);
        if($reservation_record['e_class_type'] == 'RT'){
            $reservation_record_rt = Reservations::find($reservation_rec2);
        }else{
            $reservation_record_rt = "";
        }
       
     
        
        if(!empty($inputs)){
         
               pr($inputs);
               exit;    
                   

        }else {
            return view('frontend.customer_reservation.passenger-information', array('title' => 'Passenger Information','reservation_record'=>$reservation_record));
        }

    }

    public function LuggageAnimals(Request $request){
        $inputs = $request->all();
        $reservation_rec1 = Session::get('reservation_rec1');
        $reservation_rec2 = Session::get('reservation_rec2');
      
        $reservation_record = Reservations::find($reservation_rec1);
      
        $reservation_luggage_info_edit_mode = ReservationLuggageInfo::where('i_reservation_id', $reservation_record['id'])->get();

      

        if($reservation_record['e_class_type'] == 'RT'){
            $reservation_record_rt = Reservations::find($reservation_rec2);
            $reservation_luggage_info_rt_edit_mode = ReservationLuggageInfo::where('i_reservation_id', $reservation_record_rt['id'])->get();
           
        }else{
            $reservation_record_rt = "";
            $reservation_luggage_info_rt_edit_mode = "";
        }
       
        $sys_luggage_def = SystemLuggageDef::where(['e_type' => 'Luggage','deleted_at'=>NULL])->get();
        $sys_animal_def = SystemLuggageDef::where('e_type','Animal')->get();
        $total_luggage_count =  ($sys_luggage_def->count() - 1);
      
        
      
        if(!empty($inputs)){
           
            if($reservation_luggage_info_edit_mode){
                $reservation_record->i_num_pets = $inputs['i_num_pets'];
                $reservation_record->i_number_of_luggages = $inputs['i_number_of_luggages'];
                $reservation_record->save();
                if($reservation_record['e_class_type'] == 'RT'){
                    $reservation_record_rt->i_num_pets = $inputs['i_num_pets_rt'];
                    $reservation_record_rt->i_number_of_luggages = $inputs['i_number_of_luggages_rt'];
                    $reservation_record_rt->save();
                }
                for($i=0; $i<=$total_luggage_count; $i++) {

                    
                    if(isset($inputs['sys_luggage_'.$i]) && ($inputs['sys_luggage_'.$i] != '' && $inputs['sys_luggage_'.$i] != 0)) {
                       
                        if(isset($inputs['luggage_data_id_'.$i])){
                            
                            $reservation_lugg_info = ReservationLuggageInfo::find($inputs['luggage_data_id_'.$i]); 
                                                  
                        } else {
                            $reservation_lugg_info = new ReservationLuggageInfo;
                        }
                        $reservation_lugg_info->i_reservation_id = $reservation_record['id'];
                        $reservation_lugg_info->i_sys_luggage_id = $inputs['i_sys_luggage_'.$i];
                        $reservation_lugg_info->i_value = $inputs['sys_luggage_'.$i];
                        $total_unit_price = ($inputs['sys_luggage_'.$i] * $inputs['d_unit_price_'.$i]);
                        $reservation_lugg_info->d_price = $total_unit_price;
                        //$reservation_lugg_info->created_at = Carbon::now();
                        $reservation_lugg_info->save();
                       /*  pr($reservation_lugg_info);
                        exit; */
    
     
                    }
                    if(isset($inputs['i_sys_pet_'.$i]) && isset($inputs['fare_amt_pet_'.$i]) && ($inputs['fare_amt_pet_'.$i] != '' && $inputs['fare_amt_pet_'.$i] != 0)) {
                                
                        if(isset($inputs['animal_rec_id_'.$i])){
                            $reservation_lugg_info = ReservationLuggageInfo::find($inputs['animal_rec_id_'.$i]);                               
                        } else {
                            $reservation_lugg_info = new ReservationLuggageInfo;
                        }
                        $reservation_lugg_info->i_reservation_id = $reservation_record['id'];
                        $reservation_lugg_info->i_sys_luggage_id = $inputs['i_sys_pet_'.$i];
                        $reservation_lugg_info->i_value = 1;
                        $reservation_lugg_info->d_price = $inputs['fare_amt_pet_'.$i];
                        $reservation_lugg_info->created_at = Carbon::now();
                        $reservation_lugg_info->save();
    
                       
                    }else{
                        if(isset($inputs['animal_rec_id_'.$i])) {
                            $old_passanger_info = ReservationLuggageInfo::where('id', $inputs['animal_rec_id_'.$i])->where('i_reservation_id',$reservation_record['id'])->delete();
                        }
                    }

                    if($reservation_record['e_class_type'] == 'RT'){
                    
                        if(isset($inputs['sys_luggage_rt_'.$i]) && ($inputs['sys_luggage_rt_'.$i] != '' && $inputs['sys_luggage_rt_'.$i] != 0)) {
                          
                            if(!empty($inputs['luggage_data_id_rt_'.$i])){
                              
                                $reservation_lugg_info_rt = ReservationLuggageInfo::find($inputs['luggage_data_id_rt_'.$i]);
                               
                                                            
                            } else {
                                $reservation_lugg_info_rt = new ReservationLuggageInfo;
                            }
                            $reservation_lugg_info_rt->i_reservation_id = $reservation_record_rt['id'];
                            $reservation_lugg_info_rt->i_sys_luggage_id = $inputs['i_sys_luggage_rt_'.$i];
                            $reservation_lugg_info_rt->i_value = $inputs['sys_luggage_rt_'.$i];
                            $total_unit_price_rt = ($inputs['sys_luggage_rt_'.$i] * $inputs['d_unit_price_rt_'.$i]);
                            $reservation_lugg_info_rt->d_price = $total_unit_price_rt;
                            $reservation_lugg_info_rt->created_at = Carbon::now();
                            $reservation_lugg_info_rt->save();            
                        }
                    
                        if(isset($inputs['i_sys_pet_rt_'.$i]) && isset($inputs['fare_amt_pet_rt_'.$i]) && ($inputs['fare_amt_pet_rt_'.$i] != '' && $inputs['fare_amt_pet_rt_'.$i] != 0)) {
                           
                            if(isset($inputs['animal_rec_id_rt_'.$i])){
                                $reservation_lugg_info_rt = ReservationLuggageInfo::find($inputs['animal_rec_id_rt_'.$i]);                               
                            } else {
                               
                                $reservation_lugg_info_rt = new ReservationLuggageInfo;
                            }
                            $reservation_lugg_info_rt->i_reservation_id = $reservation_record_rt['id'];
                            $reservation_lugg_info_rt->i_sys_luggage_id = $inputs['i_sys_pet_rt_'.$i];
                            $reservation_lugg_info_rt->i_value = 1;
                            $reservation_lugg_info_rt->d_price = $inputs['fare_amt_pet_rt_'.$i];
                            $reservation_lugg_info_rt->created_at = Carbon::now();
                            $reservation_lugg_info_rt->save();
                            
                        }else{

                            if(isset($inputs['animal_rec_id_rt_'.$i])) {
                                $old_passanger_info = ReservationLuggageInfo::where('id', $inputs['animal_rec_id_rt_'.$i])->where('i_reservation_id',$reservation_record_rt['id'])->delete();
                            }
                        }
                      
                       $total_price =  ($inputs['sys_luggage_'.$i] * $inputs['d_unit_price_'.$i]);
                       
                         
                        if(isset($inputs['luggage_data_id_'.$i]) &&  isset($inputs['sys_luggage_'.$i]) && $total_price == 0) {
                           
                            $old_passanger_info = ReservationLuggageInfo::where('id', $inputs['luggage_data_id_'.$i])->where('i_reservation_id',$reservation_record['id'])->delete();
                        }
                       
                        $total_price_rt =  ($inputs['sys_luggage_rt_'.$i] * $inputs['d_unit_price_rt_'.$i]); 

                        if(isset($inputs['luggage_data_id_rt_'.$i]) && isset($inputs['sys_luggage_rt_'.$i]) && $total_price_rt == 0) {
                            $old_passanger_info = ReservationLuggageInfo::where('id', $inputs['luggage_data_id_rt_'.$i])->where('i_reservation_id',$reservation_record_rt['id'])->delete();
                        }
                       
                       
                    }
                
                }
               
                return response()->json([
                    'status' => 'TRUE',
                    'redirect_url' => FRONTEND_URL.'travel-details',
                ]);  

            }else {
                $reservation_record->i_num_pets = $inputs['i_num_pets'];
                $reservation_record->i_number_of_luggages = $inputs['i_number_of_luggages'];
                $reservation_record->save();
                if($reservation_record['e_class_type'] == 'RT') {

                    $reservation_record_rt->i_num_pets = $inputs['i_num_pets_rt'];
                    $reservation_record_rt->i_number_of_luggages = $inputs['i_number_of_luggages_rt'];
                    $reservation_record_rt->save();

                }
                for($i=0; $i<=$total_luggage_count; $i++) {

               
               
                    if(isset($inputs['sys_luggage_'.$i]) && ($inputs['sys_luggage_'.$i] != '' && $inputs['sys_luggage_'.$i] != 0)) {
    
                        $reservation_lugg_info = new ReservationLuggageInfo;
                        $reservation_lugg_info->i_reservation_id = $reservation_record['id'];
                        $reservation_lugg_info->i_sys_luggage_id = $inputs['i_sys_luggage_'.$i];
                        $reservation_lugg_info->i_value = $inputs['sys_luggage_'.$i];
                        $total_unit_price = ($inputs['sys_luggage_'.$i] * $inputs['d_unit_price_'.$i]);
                        $reservation_lugg_info->d_price = $total_unit_price;
                        $reservation_lugg_info->created_at = Carbon::now();
                        $reservation_lugg_info->save();
    
     
                    }
                    if(isset($inputs['i_sys_pet_'.$i]) && isset($inputs['fare_amt_pet_'.$i]) && ($inputs['fare_amt_pet_'.$i] != '' && $inputs['fare_amt_pet_'.$i] != 0)) {
                                
                        $reservation_lugg_info = new ReservationLuggageInfo;
                        $reservation_lugg_info->i_reservation_id = $reservation_record['id'];
                        $reservation_lugg_info->i_sys_luggage_id = $inputs['i_sys_pet_'.$i];
                        $reservation_lugg_info->i_value = 1;
                        $reservation_lugg_info->d_price = $inputs['fare_amt_pet_'.$i];
                        $reservation_lugg_info->save();
    
                       
                    }
                    if($reservation_record['e_class_type'] == 'RT') {
    
                        if(isset($inputs['sys_luggage_rt_'.$i]) && ($inputs['sys_luggage_rt_'.$i] != '' && $inputs['sys_luggage_rt_'.$i] != 0)) {
    
                            $reservation_lugg_info = new ReservationLuggageInfo;
                            $reservation_lugg_info->i_reservation_id = $reservation_record_rt['id'];
                            $reservation_lugg_info->i_sys_luggage_id = $inputs['i_sys_luggage_rt_'.$i];
                            $reservation_lugg_info->i_value = $inputs['sys_luggage_rt_'.$i];
                            $total_unit_price = ($inputs['sys_luggage_rt_'.$i] * $inputs['d_unit_price_rt_'.$i]);
                            $reservation_lugg_info->d_price = $total_unit_price;
                            $reservation_lugg_info->created_at = Carbon::now();
                            $reservation_lugg_info->save();
         
                        }
                        if(isset($inputs['i_sys_pet_rt_'.$i]) && isset($inputs['fare_amt_pet_rt_'.$i]) && ($inputs['fare_amt_pet_rt_'.$i] != '' && $inputs['fare_amt_pet_rt_'.$i] != 0)) {
                                    
                            $reservation_lugg_info = new ReservationLuggageInfo;
                            $reservation_lugg_info->i_reservation_id = $reservation_record_rt['id'];
                            $reservation_lugg_info->i_sys_luggage_id = $inputs['i_sys_pet_rt_'.$i];
                            $reservation_lugg_info->i_value = 1;
                            $reservation_lugg_info->d_price = $inputs['fare_amt_pet_rt_'.$i];
                            $reservation_lugg_info->save();
        
                           
                        }
    
                    }
                
                }
                return response()->json([
                    'status' => 'TRUE',
                    'redirect_url' => FRONTEND_URL.'travel-details',
                ]); 

            }
            
        }else {
            return view('frontend.customer_reservation.luggage-animals', array('title' => 'Luggage Animals','sys_luggage_def'=>$sys_luggage_def,'sys_animal_def'=>$sys_animal_def,'reservation_luggage_info_edit_mode'=>$reservation_luggage_info_edit_mode,'reservation_luggage_info_rt_edit_mode'=>$reservation_luggage_info_rt_edit_mode,'reservation_record'=>$reservation_record,'reservation_record_rt'=>$reservation_record_rt));
        }

    }

    public function TravelDetails(Request $request){
        $input = $request->all();
       
        if(!empty($input)){

        }else {
            return view('frontend.customer_reservation.travel-details', array('title' => 'Travel Details'));
        }

    }

    public function ConfirmLlineRruns(Request $request){
        $input = $request->all();
        if(!empty($input)){

        }else {
            return view('frontend.customer_reservation.confirm-line-runs', array('title' => 'Confirm Line Runs'));
        }

    }

    public function RservationSummary(Request $request){
        $inputs = $request->all();
        $reservation_rec1 = Session::get('reservation_rec1');
        $reservation_rec2 = Session::get('reservation_rec2');
      
   
        $reservation_record = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory'])->where(['id'=>$reservation_rec1])->first();

        $reservation_luggage_info = ReservationLuggageInfo::with(['SystemLuggageDef'])->where('i_reservation_id', $reservation_rec1)->get()->toArray();
        $reservation_pet_info = ReservationLuggageInfo::with(['SystemAnimalDef'])->where('i_reservation_id', $reservation_rec1)->get()->toArray();
        $reservation_luggage_info_total = ReservationLuggageInfo ::where('i_reservation_id', $reservation_rec1)->get()->sum('d_price');
       
       
        $total_fare_amount = ReservationTravellerInfo::where('i_reservation_id', $reservation_rec1)->select(DB::raw('SUM(d_fare_amount) AS total'), DB::raw('SUM(IF(e_type = "Adult", d_fare_amount, 0)) AS adult_total'), DB::raw('SUM(IF(e_type = "Child", d_fare_amount, 0)) AS child_total'),DB::raw('SUM(IF(e_type = "Senior", d_fare_amount, 0)) AS senior_total'),DB::raw('SUM(IF(e_type = "Military", d_fare_amount, 0)) AS military_total'),DB::raw('SUM(IF(e_type = "Infant", d_fare_amount, 0)) AS infant_total'))->first()->toArray();

            if($reservation_record['e_class_type'] == 'RT'){

                $reservation_record_rt = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory'])->where(['id'=>$reservation_rec2])->first();
                $reservation_luggage_info_rt = ReservationLuggageInfo::with(['SystemLuggageDef'])->where('i_reservation_id', $reservation_rec2)->get()->toArray();
                $reservation_pet_info_rt = ReservationLuggageInfo::with(['SystemAnimalDef'])->where('i_reservation_id', $reservation_rec2)->get()->toArray();
                $total_fare_amount_rt = ReservationTravellerInfo::where('i_reservation_id', $reservation_rec2)->select(DB::raw('SUM(d_fare_amount) AS total'), DB::raw('SUM(IF(e_type = "Adult", d_fare_amount, 0)) AS adult_total'), DB::raw('SUM(IF(e_type = "Child", d_fare_amount, 0)) AS child_total'),DB::raw('SUM(IF(e_type = "Senior", d_fare_amount, 0)) AS senior_total'),DB::raw('SUM(IF(e_type = "Military", d_fare_amount, 0)) AS military_total'),DB::raw('SUM(IF(e_type = "Infant", d_fare_amount, 0)) AS infant_total'))->first()->toArray();
                $reservation_luggage_info_total_rt = ReservationLuggageInfo ::where('i_reservation_id', $reservation_rec2)->get()->sum('d_price');
            }else{
                $reservation_record_rt = array();
                $reservation_luggage_info_rt = array();;
                $reservation_pet_info_rt = array();;
                $total_fare_amount_rt = array();
                $reservation_luggage_info_total_rt = "";
            }


       
        if(!empty($input)){

        }else {
            return view('frontend.customer_reservation.reservation-summary', array('title' => 'Rservation Summary','reservation_record' => $reservation_record,'reservation_record_rt' => $reservation_record_rt,'reservation_luggage_info'=>$reservation_luggage_info,'reservation_pet_info'=>$reservation_pet_info,'reservation_luggage_info_rt'=>$reservation_luggage_info_rt,'reservation_pet_info_rt'=>$reservation_pet_info_rt,'total_fare_amount'=>$total_fare_amount,'total_fare_amount_rt'=>$total_fare_amount_rt,'reservation_luggage_info_total'=>$reservation_luggage_info_total,'reservation_luggage_info_total_rt'=>$reservation_luggage_info_total_rt));
        }

    }

    public function CustomerPayment(Request $request){
        $input = $request->all();
        if(!empty($input)){

        }else {
            return view('frontend.customer_reservation.payment', array('title' => 'Payment'));
        }

    }
    
    public function customerAutoComplete (Request $request) {
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
                
    }

    public function getLineRunData(Request $request) {
        $data = $request->all();
       if($data){

            if($data['type_of_trip'] == 'OW') {
            
                $line_run_data_one_way = LineRun::where(DB::raw('DATE(d_run_date)'), '=', trim(date('Y-m-d', strtotime(trim($data['d_depart_date'])))))->where(['i_origin_service_area_id'=>$data['i_origin_service_area_id'],'i_dest_service_area_id'=>$data['i_dest_service_area_id']]);
                
                $line_run_data_one_way = $line_run_data_one_way->with(['VehicleFleet' => function($q) {
                    $q->select('*')->with(['get_vehicle_specification' => function($qa){
                        $qa->select('i_vehicle_id','v_make', 'v_model', 'v_series', 'v_vehicle_type');
                    }]);
                }])->get()->toArray();
               
                if(!empty($line_run_data_one_way))  {
                    return View('frontend.customer_reservation.linerun_data', array('title' => "Select LineRun", 'line_run_data_one_way' => $line_run_data_one_way,'type_of_trip' => 'OW'));
                } else {
                    return View('frontend.customer_reservation.no_surch_data', array('title' => "Select LineRun",'type_of_trip' => 'OW'));
                }
                
            }else{
                
                $line_run_data_departur = LineRun ::where(DB::raw('DATE(d_run_date)'), '=', trim(date('Y-m-d', strtotime(trim($data['d_depart_date'])))))->where(['i_origin_service_area_id'=>$data['i_origin_service_area_id'],'i_dest_service_area_id'=>$data['i_dest_service_area_id']]);

                $line_run_data_departur = $line_run_data_departur->with(['VehicleFleet' => function($q) {
                    $q->select('*')->with(['get_vehicle_specification' => function($qa){
                        $qa->select('i_vehicle_id','v_make', 'v_model', 'v_series', 'v_vehicle_type');
                    }]);
                }])->get()->toArray();

                $line_run_data_return = LineRun ::where(DB::raw('DATE(d_run_date)'), '=', trim(date('Y-m-d', strtotime(trim($data['d_return_date'])))))->where(['i_origin_service_area_id'=>$data['i_dest_service_area_id'],'i_dest_service_area_id'=>$data['i_origin_service_area_id']]);

                $line_run_data_return = $line_run_data_return->with(['VehicleFleet' => function($q) {
                    $q->select('*')->with(['get_vehicle_specification' => function($qa){
                        $qa->select('i_vehicle_id','v_make', 'v_model', 'v_series', 'v_vehicle_type');
                    }]);
                }])->get()->toArray();
             
                if(!empty($line_run_data_departur))  {
                   
                    return View('frontend.customer_reservation.linerun_data', array('title' => "Select LineRun", 'return_data' => $line_run_data_return,'departur_data'=>$line_run_data_departur,'type_of_trip' => 'RT'));
                } else {
                    return View('frontend.customer_reservation.no_surch_data', array('title' => "Select LineRun",'type_of_trip' => 'RT'));
                }
                
            }
       }
    }
    
    public function downloadReservationData(Request $request,$id){
        $inputs = $request->all();
        $reservation_rec1 = Session::get('reservation_rec1');
        $reservation_rec2 = Session::get('reservation_rec2');
      
   
        $reservation_record = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory'])->where(['id'=>$reservation_rec1])->first();
       /*  pr($reservation_record);
        exit; */
        if($reservation_record){
            $reservation_luggage_info = ReservationLuggageInfo::with(['SystemLuggageDef'])->where('i_reservation_id', $reservation_rec1)->get()->toArray();
            $reservation_pet_info = ReservationLuggageInfo::with(['SystemAnimalDef'])->where('i_reservation_id', $reservation_rec1)->get()->toArray();
            $reservation_luggage_info_total = ReservationLuggageInfo ::where('i_reservation_id', $reservation_rec1)->get()->sum('d_price');
        
        
            $total_fare_amount = ReservationTravellerInfo::where('i_reservation_id', $reservation_rec1)->select(DB::raw('SUM(d_fare_amount) AS total'), DB::raw('SUM(IF(e_type = "Adult", d_fare_amount, 0)) AS adult_total'), DB::raw('SUM(IF(e_type = "Child", d_fare_amount, 0)) AS child_total'),DB::raw('SUM(IF(e_type = "Senior", d_fare_amount, 0)) AS senior_total'),DB::raw('SUM(IF(e_type = "Military", d_fare_amount, 0)) AS military_total'),DB::raw('SUM(IF(e_type = "Infant", d_fare_amount, 0)) AS infant_total'))->first()->toArray();

            if($reservation_record['e_class_type'] == 'RT'){

                $reservation_record_rt = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory'])->where(['id'=>$reservation_rec2])->first();
                $reservation_luggage_info_rt = ReservationLuggageInfo::with(['SystemLuggageDef'])->where('i_reservation_id', $reservation_rec2)->get()->toArray();
                $reservation_pet_info_rt = ReservationLuggageInfo::with(['SystemAnimalDef'])->where('i_reservation_id', $reservation_rec2)->get()->toArray();
                $total_fare_amount_rt = ReservationTravellerInfo::where('i_reservation_id', $reservation_rec2)->select(DB::raw('SUM(d_fare_amount) AS total'), DB::raw('SUM(IF(e_type = "Adult", d_fare_amount, 0)) AS adult_total'), DB::raw('SUM(IF(e_type = "Child", d_fare_amount, 0)) AS child_total'),DB::raw('SUM(IF(e_type = "Senior", d_fare_amount, 0)) AS senior_total'),DB::raw('SUM(IF(e_type = "Military", d_fare_amount, 0)) AS military_total'),DB::raw('SUM(IF(e_type = "Infant", d_fare_amount, 0)) AS infant_total'))->first()->toArray();
                $reservation_luggage_info_total_rt = ReservationLuggageInfo ::where('i_reservation_id', $reservation_rec2)->get()->sum('d_price');
            }else{
                $reservation_record_rt = array();
                $reservation_luggage_info_rt = array();;
                $reservation_pet_info_rt = array();;
                $total_fare_amount_rt = array();
                $reservation_luggage_info_total_rt = "";
            }
            PDF::setOutputMode('F'); 
            PDF::setPageSize('a4');
            PDF::setOrientation('portrait');
            
            $filename = $reservation_record['v_reservation_number'];

            PDF::html('frontend.customer_reservation.download-reservation-summary', array('title' => 'Rservation Summary','reservation_record' => $reservation_record,'reservation_record_rt' => $reservation_record_rt,'reservation_luggage_info'=>$reservation_luggage_info,'reservation_pet_info'=>$reservation_pet_info,'reservation_luggage_info_rt'=>$reservation_luggage_info_rt,'reservation_pet_info_rt'=>$reservation_pet_info_rt,'total_fare_amount'=>$total_fare_amount,'total_fare_amount_rt'=>$total_fare_amount_rt,'reservation_luggage_info_total'=>$reservation_luggage_info_total,'reservation_luggage_info_total_rt'=>$reservation_luggage_info_total_rt),ADMIN_FILES_PATH.$filename);
            return Response::download(ADMIN_FILES_PATH.$filename.'.pdf');
        }
        
    }
}    
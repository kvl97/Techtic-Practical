<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomePageContent;
use App\Models\Testimonials;
use App\Models\Reservations;
use App\Models\ReservationTravellerInfo;
use App\Models\ReservationLuggageInfo;
use App\Models\FareTable;
use App\Models\GeoCities;
use App\Models\FareClass;
use App\Models\DriverExtension;
use App\Models\SystemLuggageDef;
use App\Models\Transactions;
use App\Models\ReservationLeg;
use App\Models\Customers;
use App\Models\SystemSettings;
use App\Models\EmailTemplate;
use Carbon\Carbon;
use \Stripe\Stripe;
use Mail, Session, Redirect, Validator, DB, Hash, PDF,Response;

class ReservationController extends BaseController {

    public function getDetailFare(Request $request){
        $data = $request->all();
        
        //$data['home_pickup_location_rt'] = $data['home_dropoff_location_rt'] = '';
        if($data['e_class_type'] == 'RT') {
            $data['home_pickup_location_rt'] = $data['home_pickup_location_rt'];
            $data['home_dropoff_location_rt'] = $data['home_dropoff_location_rt'];
        }
        Session::put('DetailFareQuote', $data);
        return json_encode([
            'status' => 'TRUE',
            'redirect_url' =>  FRONTEND_URL.'detail-fare-quote',
        ]);
    }

    public function detailFareQuote(Request $request){
        $inputs = $request->all();
        $sys_luggage_def = SystemLuggageDef::where(['e_type' => 'Luggage','deleted_at'=>NULL])->get()->toArray();
        $sys_animal_def = SystemLuggageDef::where('e_type','Animal')->get()->toArray();
        $quote_info = Session::get('DetailFareQuote');
        if($inputs) {
            $location_info['peoples'] = trim($inputs['peoples']);   
            $location_info['e_class_type'] = $inputs['radio-group-round'];
            $location_info['home_pickup_location'] = $inputs['home_pickup_location'];
            $location_info['home_dropoff_location'] = $inputs['home_dropoff_location'];
            $location_info['home_pickup_location_rt'] = $inputs['home_pickup_location_rt'];
            $location_info['home_dropoff_location_rt'] = $inputs['home_dropoff_location_rt'];
            $location_info['e_shuttle_type'] = isset($quote_info['e_shuttle_type']) ? $quote_info['e_shuttle_type'] : 'Shared';
            $location_info['d_depart_date'] = isset($quote_info['d_depart_date']) ? $quote_info['d_depart_date'] : '';
            $location_info['d_return_date'] = isset($quote_info['d_return_date']) ? $quote_info['d_return_date'] : '';
            $location_info['i_number_of_luggages'] = (isset($inputs['i_number_of_luggages']) && $inputs['i_number_of_luggages'] != '') ? $inputs['i_number_of_luggages'] : 0;  
            for($i=0; $i <= count($sys_luggage_def) - 1; $i++) {
                if(isset($inputs['sys_luggage_'.$i]) && ($inputs['sys_luggage_'.$i] != '' && $inputs['sys_luggage_'.$i] != 0)) {
                    $location_info['sys_luggage_'.$i] = $inputs['sys_luggage_'.$i];
                    $location_info['i_sys_luggage_'.$i] = $inputs['i_sys_luggage_'.$i];
                    $location_info['d_unit_price_'.$i] = $inputs['d_unit_price_'.$i];
                }
                if(isset($inputs['i_sys_pet_'.$i]) && isset($inputs['fare_amt_pet_'.$i]) && ($inputs['fare_amt_pet_'.$i] != '' && $inputs['fare_amt_pet_'.$i] != 0)) {
                    $location_info['i_sys_pet_'.$i] = $inputs['i_sys_pet_'.$i];
                    $location_info['fare_amt_pet_'.$i] = $inputs['fare_amt_pet_'.$i];
                }
            }
            $location_info['redirect_url'] = 'detail-fare-quote';
            
            Session::put('DetailFareQuote', $location_info);

            return redirect(FRONTEND_URL.'book-a-shuttle');
        } else {
            return View('frontend.reservation.index', array('title' => 'Reservation', 'quote_info' => $quote_info,'sys_luggage_def' => $sys_luggage_def, 'sys_animal_def' => $sys_animal_def)); 
        }
        /* if($inputs && count($inputs) > 0){
            
            $resv1 = new Reservations;
            $resv1->d_travel_date = date('Y-m-d');
            $resv1->v_contact_name = '';
            $resv1->v_contact_phone_number = '';
            $resv1->v_contact_email = '';
            $resv1->e_class_type = $inputs['radio-group-round'];
            $resv1->i_total_num_passengers = trim($inputs['peoples']);
            $resv1->i_num_pets = ($inputs['i_num_pets']!='') ? trim($inputs['i_num_pets']) : 0;
            $resv1->i_number_of_luggages = ($inputs['i_number_of_luggages']!='') ? trim($inputs['i_number_of_luggages']) : 0;
            $resv1->e_shuttle_type = 'Shared';
            $resv1->e_reservation_status = 'Quote';
            
            if($resv1->save()) {
                $resv1->v_reservation_number = reservationNumber($resv1->id);
                $resv1->save();
                Session::put('reservation_rec1',$resv1->id);    

                if($inputs['radio-group-round']=='RT') {
                    $resv2 = new Reservations;
                    $resv2->d_travel_date = date('Y-m-d');
                    $resv2->v_contact_name = '';
                    $resv2->v_contact_phone_number = '';
                    $resv2->v_contact_email = '';
                    $resv2->e_class_type = $inputs['radio-group-round'];
                    $resv2->i_total_num_passengers = trim($inputs['peoples']);
                    $resv2->i_num_pets = ($inputs['i_num_pets']!='') ? trim($inputs['i_num_pets']) : 0;
                    $resv2->i_number_of_luggages = ($inputs['i_number_of_luggages']!='') ? trim($inputs['i_number_of_luggages']) : 0;
                    $resv2->e_shuttle_type = 'Shared';
                    $resv2->e_reservation_status = 'Quote';
                    if($resv2->save()) {
                        $resv2->v_reservation_number = reservationNumber($resv2->id);
                        $resv2->save();
                        Session::put('reservation_rec2',$resv2->id);
                    }
                }
                
                for($i=0; $i <= count($sys_luggage_def) - 1; $i++) {
                   
                    if(isset($inputs['sys_luggage_'.$i]) && ($inputs['sys_luggage_'.$i] != '' && $inputs['sys_luggage_'.$i] != 0)) {
                        $reservation_lugg_info = new ReservationLuggageInfo;
                        $reservation_lugg_info->i_reservation_id = $resv1->id;
                        $reservation_lugg_info->i_sys_luggage_id = $inputs['i_sys_luggage_'.$i];
                        $reservation_lugg_info->i_value = $inputs['sys_luggage_'.$i];
                        $total_unit_price = ($inputs['sys_luggage_'.$i] * $inputs['d_unit_price_'.$i]);
                        $reservation_lugg_info->d_price = $total_unit_price;
                        $reservation_lugg_info->created_at = Carbon::now();
                        $reservation_lugg_info->save();
                    }
                    if(isset($inputs['i_sys_pet_'.$i]) && isset($inputs['fare_amt_pet_'.$i]) && ($inputs['fare_amt_pet_'.$i] != '' && $inputs['fare_amt_pet_'.$i] != 0)) {
                        $reservation_lugg_info = new ReservationLuggageInfo;
                        $reservation_lugg_info->i_reservation_id = $resv1->id;
                        $reservation_lugg_info->i_sys_luggage_id = $inputs['i_sys_pet_'.$i];
                        $reservation_lugg_info->i_value = 1;
                        $reservation_lugg_info->d_price = $inputs['fare_amt_pet_'.$i];
                        $reservation_lugg_info->save();
                    }

                    if($inputs['radio-group-round']=='RT') {
                        
                        if(isset($inputs['sys_luggage_rt_'.$i]) && ($inputs['sys_luggage_rt_'.$i] != '' && $inputs['sys_luggage_rt_'.$i] != 0)) {
    
                            $reservation_lugg_info = new ReservationLuggageInfo;
                            $reservation_lugg_info->i_reservation_id = $resv2->id;
                            $reservation_lugg_info->i_sys_luggage_id = $inputs['i_sys_luggage_rt_'.$i];
                            $reservation_lugg_info->i_value = $inputs['sys_luggage_rt_'.$i];
                            $total_unit_price = ($inputs['sys_luggage_rt_'.$i] * $inputs['d_unit_price_rt_'.$i]);
                            $reservation_lugg_info->d_price = $total_unit_price;
                            $reservation_lugg_info->created_at = Carbon::now();
                            $reservation_lugg_info->save();
                        }
                        if(isset($inputs['i_sys_pet_rt_'.$i]) && isset($inputs['fare_amt_pet_rt_'.$i]) && ($inputs['fare_amt_pet_rt_'.$i] != '' && $inputs['fare_amt_pet_rt_'.$i] != 0)) {
                            $reservation_lugg_info = new ReservationLuggageInfo;
                            $reservation_lugg_info->i_reservation_id = $resv2->id;
                            $reservation_lugg_info->i_sys_luggage_id = $inputs['i_sys_pet_rt_'.$i];
                            $reservation_lugg_info->i_value = 1;
                            $reservation_lugg_info->d_price = $inputs['fare_amt_pet_rt_'.$i];
                            $reservation_lugg_info->save();
                        }
                    }
                }

                $location_info['peoples'] = trim($inputs['peoples']);   
                $location_info['e_class_type'] = $resv1->e_class_type;    
                $location_info['e_shuttle_type'] = $location_info['d_depart_date'] = $location_info['home_pickup_location'] = $location_info['home_dropoff_location'] = $location_info['home_pickup_service_id'] = $location_info['home_dropoff_service_id'] = $location_info['d_return_date'] = $location_info['home_pickup_location_rt'] = $location_info['home_dropoff_location_rt'] = $location_info['home_pickup_service_id_rt'] = $location_info['home_dropoff_service_id_rt'] = '';
                if($inputs['reservation_from_pickup_location'] != '') {
                    $home_pickup_location = GeoCities::where('id', $inputs['reservation_from_pickup_location'])->first();
                    $location_info['home_pickup_location'] = $home_pickup_location->id;
                    $location_info['home_pickup_service_id'] = $home_pickup_location->i_service_area_id;
                } 
                if($inputs['reservation_from_dropoff_location'] != '') {
                    $home_dropoff_location = GeoCities::where('id', $inputs['reservation_from_dropoff_location'])->first();
                    $location_info['home_dropoff_location'] = $home_dropoff_location->id;
                    $location_info['home_dropoff_service_id'] = $home_dropoff_location->i_service_area_id; 
                }
                if($resv1->e_class_type == 'RT'){
                    $location_info['d_return_date'] = '';
                    $location_info['home_pickup_location_rt'] = $location_info['home_dropoff_location'];
                    $location_info['home_dropoff_location_rt'] = $location_info['home_pickup_location'];
                    $location_info['home_pickup_service_id_rt'] = $location_info['home_dropoff_service_id'];
                    $location_info['home_dropoff_service_id_rt'] = $location_info['home_pickup_service_id'];
                }
                Session::put('location_info', $location_info);

                return redirect(FRONTEND_URL.'book-a-shuttle');
            }
            
        } */
    }

    public function getTotalAmount(Request $request){
        $input = $request->all();
        $total_amount = 0;
        
        if($input['trip_status']=='OW') {
            $ff_rate_code = "FFOW";
        } else {
            $ff_rate_code = "FFRT";
        }
        
        $fares_arr = FareTable::select('v_rate_code','d_fare_amount')->where(['i_origin_service_area_id'=>$input['origin_service_area_id'],'i_dest_service_area_id'=>$input['dest_service_area_id']])->get()->keyBy('v_rate_code')->toArray();

        $num_ff_adults = 0;

        if(isset($input['detail_fare_data']) && count($input['detail_fare_data']) > 0) {
            foreach($input['detail_fare_data'] as $key => $val){
                // Full Fare Adults
                if(($key=='FFOW' || $key =='FFRT')){
                    $num_ff_adults += $val;
                    if(isset($fares_arr[$key])){
                        $total_amount  += $val * $fares_arr[$key]['d_fare_amount']; 
                    }
                }

                // Seniors Travelling Alone Condition
                if(($key=='SROW' || $key =='SRRT')){
                    if(isset($fares_arr[$key]) && $input['number_of_people'] == 1){
                        $total_amount  += $val * $fares_arr[$key]['d_fare_amount']; 
                    } else {
                        $num_ff_adults += $val;
                        $total_amount  += $val * $fares_arr[$ff_rate_code]['d_fare_amount']; 
                    }
                } 

                // Military Person Travelling Alone Condition
                if(($key=='MLOW' || $key =='MLRT')){
                    if(isset($fares_arr[$key]) && $input['number_of_people'] == 1){
                        $total_amount  += $val * $fares_arr[$key]['d_fare_amount']; 
                    } else {
                        $num_ff_adults += $val;
                        $total_amount  += $val * $fares_arr[$ff_rate_code]['d_fare_amount']; 
                    }
                } 

                // Children Travelling Alone Condition
                if(($key=='CHOW' || $key =='CHRT')){
                    if($num_ff_adults == 0 && $val > 0) {
                        // $num_ff_adults += $val;
                        $total_amount  += $val*$fares_arr[$ff_rate_code]['d_fare_amount'];
                    } else {
                        if(isset($fares_arr[$key])){
                            $total_amount  += $val*$fares_arr[$key]['d_fare_amount']; 
                        }
                    }  
                }

                 // Infants Condition
                 if(($key=='INOW' || $key =='INRT')){
                    if($val <= $num_ff_adults) {
                        if(isset($fares_arr[$key])){
                            $total_amount  += $val*$fares_arr[$key]['d_fare_amount']; 
                        }
                    } else {
                        $non_dis_inf = $val - $num_ff_adults;
                        $total_amount  += $non_dis_inf * $fares_arr[$ff_rate_code]['d_fare_amount'];
                        if(isset($fares_arr[$key])){
                            $total_amount  += $num_ff_adults * $fares_arr[$key]['d_fare_amount']; 
                        }
                    }
                 }

            }
        } else {
            $total_amount = $input['number_of_people'] * $fare_table_info[$ff_rate_code]['d_fare_amount'];
        }

        $data = [
            'status' => 'TRUE',
            'total_amount' => number_format((float)$total_amount, 2, '.', ''),
        ];

        return  json_encode($data);
    }
    
    public function getUpcomingReservationDetail(Request $request){
        $user = auth()->guard('customers')->user();
        /* $today = date("Y-m-d", strtotime(date('Y-m-d')));

        $upcoming_reservation_data = Reservations::with(['GeoOriginServiceArea', 'GeoDestServiceArea', 'Admin', 'Customers', 'SystemResCategory'])->where('i_customer_id',$user['id'])->where('d_travel_date','>=', $today)->get()->toArray(); */

        return view('frontend.reservation.upcoming_reservation', array('title' => 'Upcoming Reservations','user'=>$user));
       
    }

    public function UpcomingReservationListAjax(Request $request){
        $current_user = auth()->guard('customers')->user();
        $today = date("Y-m-d", strtotime(date('Y-m-d')));
        $userId =  $current_user->id;
        $data = $request->all();

        $query = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory','PickupCity','DropOffCity'])->where('i_customer_id',$userId)->where('d_travel_date','>=', $today)->whereNotIn('e_reservation_status',['Quote','Cancelled','Refund Requested','Refunded']);

        if(isset($data['upcoming_reserv_data']) && $data['upcoming_reserv_data'] != '') {
           /*  pr($data);
            exit; */

            $query = $query->where(function($q) use($data){
                $q->orWhere('v_reservation_number','LIKE', '%'. $data['upcoming_reserv_data']. '%')->orWhere('e_reservation_status','LIKE', '%'. $data['upcoming_reserv_data']. '%')->orWhere('i_total_num_passengers','LIKE', '%'. $data['upcoming_reserv_data']. '%')->orWhereHas('PickupCity', function($qa) use($data){
                        $qa->where(DB::raw("CONCAT(reservations.v_pickup_address, COALESCE(CONCAT(', ',geo_cities.v_city), ''), COALESCE(CONCAT(', ',geo_cities.v_county),''))"), 'LIKE', '%' . trim($data['upcoming_reserv_data']) . '%');

                  })->orWhereHas('DropOffCity', function($qa) use($data){
                        $qa->where(DB::raw("CONCAT(reservations.v_dropoff_address, COALESCE(CONCAT(', ',geo_cities.v_city), ''), COALESCE(CONCAT(', ',geo_cities.v_county),''))"), 'LIKE', '%' . trim($data['upcoming_reserv_data']) . '%');

                  })->orWhere(DB::raw('DATE(d_travel_date)'), '=', trim(date('Y-m-d', strtotime($data['upcoming_reserv_data']))) );
            });
        
        }
       
        $rec_per_page = REC_PER_PAGE;
        
        if (isset($data['length'])) {
            if ($data['length'] == '-1') {
                $rec_per_page = '';
            } else {
                $rec_per_page = $data['length'];
            }
        }
        $query = $query->orderBy('updated_at', 'desc');
        $users = $query->paginate($rec_per_page);
        ;
        $arrUsers = $users->toArray();
        $data = array();
        foreach ($arrUsers['data'] as $key => $val) {
            $index = 0;
            
            if($val['e_class_type'] == 'RT'){ 
                if($val['e_shuttle_type'] == "Private"){

                    $data[$key][$index++] = '<a href="' . SITE_URL . 'upcoming-reservation/' . $val['id'] . '">'.$val['v_reservation_number'].'</a>'.'</br>'.'<i class="icon icon-round-trip" style="color:red;"></i><span style="color: lightblue;">&nbsp; | &nbsp;</span><i class="icon icon-passengers" style="color:red;"></i> &nbsp;&nbsp;'.$val['i_total_num_passengers'].'<span style="color: lightblue;">&nbsp; | &nbsp;</span><button type="button" class="btn-red btn-sm btn-xxs">Private</button>';
                } else{

                    $data[$key][$index++] = '<a href="' . SITE_URL . 'upcoming-reservation/' . $val['id'] . '">'.$val['v_reservation_number'].'</a>'.'</br>'.'<i class="icon icon-round-trip" style="color:red;"></i><span style="color: lightblue;">&nbsp; | &nbsp;</span><i class="icon icon-passengers" style="color:red;"></i> &nbsp;&nbsp;'.$val['i_total_num_passengers'];
                }
            }else{
                if($val['e_shuttle_type'] == "Private"){

                    $data[$key][$index++] = '<a href="' . SITE_URL . 'upcoming-reservation/' . $val['id'] . '">'.$val['v_reservation_number'].'</a>'.'</br>'.'<i class="icon icon-one-way" style="color:red;"></i><span style="color: lightblue;">&nbsp; | &nbsp;</span><i class="icon icon-passengers" style="color:red;"></i> &nbsp;&nbsp;'.$val['i_total_num_passengers'].'<span style="color: lightblue;">&nbsp; | &nbsp;</span><button type="button" class="btn-red btn-sm btn-xxs">Private</button>';

                } else{

                    $data[$key][$index++] = '<a href="' . SITE_URL . 'upcoming-reservation/' . $val['id'] . '">'.$val['v_reservation_number'].'</a>'.'</br>'.'<i class="icon icon-one-way"></i><span style="color: lightblue;">&nbsp; | &nbsp;</span><i class="icon icon-passengers"></i> &nbsp;&nbsp;'.$val['i_total_num_passengers'];
                }
               
            }

            
            $travel_type = $this->getTravelTypeText($val['i_reservation_category_id'],$val['i_dropoff_point_type_id']);

            $data[$key][$index++] = $travel_type['switch_text'];
           
            if(isset($val['pickup_city']['v_city']) && $val['v_pickup_address'] != ''){
                $data[$key][$index++] = $val['v_pickup_address'].', '.$val['pickup_city']['v_city'].', '.$val['pickup_city']['v_county'];
            }else {
                $data[$key][$index++] = '';
            }
            if(isset($val['drop_off_city']['v_city']) && $val['v_dropoff_address'] != ''){
                $data[$key][$index++] = $val['v_dropoff_address'].', '.$val['drop_off_city']['v_city'].', '.$val['drop_off_city']['v_county'];
            }else {
                $data[$key][$index++] = '';
            }

            
           
            $date = 'Dept:'.$val['d_travel_date'] ? '<div style="padding:0 0 0.75rem">Date: '.date(DATE_FORMAT, strtotime($val['d_travel_date'])).'</div>' : '-';
            
            
            $data[$key][$index++] =  $date;
           

        }
        $return_data['data'] = $data;
        $return_data['iTotalRecords'] = $arrUsers['total'];
        $return_data['iTotalDisplayRecords'] = $arrUsers['total'];
        $return_data['data_array'] = $arrUsers['data'];
        return $return_data;
    }

    public function getPastReservationDetail(Request $request){
        $user = auth()->guard('customers')->user();
        return view('frontend.reservation.past_reservation', array('title' => 'Past Reservations','user'=>$user));
        
    }

    public function PastReservationListAjax(Request $request){
        $current_user = auth()->guard('customers')->user();
        $today = date("Y-m-d", strtotime(date('Y-m-d')));
        $userId =  $current_user->id;
        $data = $request->all();
        DB::enableQueryLog();
        $query  = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory','PickupCity','DropOffCity','ReservAtionLeg'=> function($q) {
            $q->with(['LineRune' => function($q1) {
                $q1->with(['Driver','VehicleFleet'])->select('*');  
            }])->select('*');    
        }])->where('i_customer_id',$userId)->where('e_reservation_status','!=','Quote')->where(function($p) {
            $p->where('d_travel_date','<', date('Y-m-d'))->orWhereIn('e_reservation_status',['Cancelled','Refund Requested','Refunded']);
        });               
       
        if(isset($data['past_reserv_data']) && $data['past_reserv_data'] != '') {
            
          
            $query = $query->where(function($q) use($data){
                $q->orWhere('v_reservation_number','LIKE', '%'. $data['past_reserv_data']. '%')->orWhere('e_reservation_status','LIKE', '%'. $data['past_reserv_data']. '%')->orWhere('i_total_num_passengers','LIKE', '%'. $data['past_reserv_data']. '%')->orWhereHas('PickupCity', function($qa) use($data){
                    $qa->where(DB::raw("CONCAT(reservations.v_pickup_address, COALESCE(CONCAT(', ',geo_cities.v_city), ''), COALESCE(CONCAT(', ',geo_cities.v_county),''))"), 'LIKE', '%' . trim($data['past_reserv_data']) . '%');

              })->orWhereHas('DropOffCity', function($qa) use($data){
                    $qa->where(DB::raw("CONCAT(reservations.v_dropoff_address, COALESCE(CONCAT(', ',geo_cities.v_city), ''), COALESCE(CONCAT(', ',geo_cities.v_county),''))"), 'LIKE', '%' . trim($data['past_reserv_data']) . '%');

              })->orWhereHas('GeoOriginServiceArea', function($q) use($data){

                    $q->whereHas('GeoCities', function($s) use($data){
                        $s->where(DB::raw("CONCAT(geo_point.v_street1, COALESCE(CONCAT(', ',geo_cities.v_city), ''), COALESCE(CONCAT(', ',geo_cities.v_county),''), COALESCE(CONCAT(', ',geo_point.v_postal_code),''))"), 'LIKE', '%' . trim($data['past_reserv_data']) . '%');
                    });
                    
                })->orWhereHas('GeoDestServiceArea', function($q) use($data){

                    $q->whereHas('GeoCities', function($s) use($data){
                        $s->where(DB::raw("CONCAT(geo_point.v_street1, COALESCE(CONCAT(', ',geo_cities.v_city), ''), COALESCE(CONCAT(', ',geo_cities.v_county),''), COALESCE(CONCAT(', ',geo_point.v_postal_code),''))"), 'LIKE', '%' . trim($data['past_reserv_data']) . '%');
                    });
                    
                })->orWhere(DB::raw('DATE(d_travel_date)'), '=', trim(date('Y-m-d', strtotime($data['past_reserv_data']))) )->orWhere(DB::raw('DATE(d_travel_date)'), '=', trim(date('Y-m-d', strtotime($data['past_reserv_data']))) );
            });
           
          
        }
        
        $query = $query->orderBy('updated_at', 'desc');
        $rec_per_page = REC_PER_PAGE;
        
        if (isset($data['length'])) {
            if ($data['length'] == '-1') {
                $rec_per_page = '';
            } else {
                $rec_per_page = $data['length'];
            }
        }
        $users = $query->paginate($rec_per_page);
        ;
        $arrUsers = $users->toArray();
        $queries = DB::getQueryLog();
        
        /*pr($arrUsers);
        exit;*/
        $data = array();
        foreach ($arrUsers['data'] as $key => $val) {
            $index = 0;
            
                        
            if($val['e_class_type'] == 'RT'){ 
                if($val['e_shuttle_type'] == "Private"){

                    $data[$key][$index++] = '<a href="' . SITE_URL . 'past-reservation/' . $val['id'] . '">'.$val['v_reservation_number'].'</a>'.'</br>'.'<i class="icon icon-round-trip" style="color:red;"></i><span style="color: lightblue;">&nbsp; | &nbsp;</span><i class="icon icon-passengers" style="color:red;"></i> &nbsp;&nbsp;'.$val['i_total_num_passengers'].'<span style="color: lightblue;">&nbsp; | &nbsp;</span><button type="button" class="btn-red btn-sm btn-xxs" style="">Private</button>';
                } else{

                    $data[$key][$index++] = '<a href="' . SITE_URL . 'past-reservation/' . $val['id'] . '">'.$val['v_reservation_number'].'</a>'.'</br>'.'<i class="icon icon-round-trip" style="color:red;"></i><span style="color: lightblue;">&nbsp; | &nbsp;</span><i class="icon icon-passengers" style="color:red;"></i> &nbsp;&nbsp;'.$val['i_total_num_passengers'];
                }
            }else{
                if($val['e_shuttle_type'] == "Private"){

                    $data[$key][$index++] = '<a href="' . SITE_URL . 'past-reservation/' . $val['id'] . '">'.$val['v_reservation_number'].'</a>'.'</br>'.'<i class="icon icon-one-way" style="color:red;"></i><span style="color: lightblue;">&nbsp; | &nbsp;</span><i class="icon icon-passengers" style="color:red;"></i> &nbsp;&nbsp;'.$val['i_total_num_passengers'].'<span style="color: lightblue;">&nbsp; | &nbsp;</span><button type="button" class="btn-red btn-sm btn-xxs" style="">Private</button>';

                } else{

                    $data[$key][$index++] = '<a href="' . SITE_URL . 'past-reservation/' . $val['id'] . '">'.$val['v_reservation_number'].'</a>'.'</br>'.'<i class="icon icon-one-way"></i><span style="color: lightblue;">&nbsp; | &nbsp;</span><i class="icon icon-passengers"></i> &nbsp;&nbsp;'.$val['i_total_num_passengers'];
                }
               
            }

            // $data[$key][$index++] = ($val['system_res_category']) ? $val['system_res_category']['v_label'] : '';
            $travel_type = $this->getTravelTypeText($val['i_reservation_category_id'],$val['i_dropoff_point_type_id']);

            $data[$key][$index++] = $travel_type['switch_text'];
            
            
            if(isset($val['pickup_city']['v_city']) && $val['v_pickup_address'] != ''){
                $data[$key][$index++] = $val['v_pickup_address'].', '.$val['pickup_city']['v_city'];
            }else {
                $data[$key][$index++] = '';
            }
            if(isset($val['drop_off_city']['v_city']) && $val['v_dropoff_address'] != ''){
                $data[$key][$index++] = $val['v_dropoff_address'].', '.$val['drop_off_city']['v_city'];
            }else {
                $data[$key][$index++] = '';
            }

             

            $date = 'Dept:'.$val['d_travel_date'] ?  '<div style="padding:0 0 0.75rem">Date: '.date(DATE_FORMAT, strtotime($val['d_travel_date'])).'</div>' : '-';
            
            
            $data[$key][$index++] =  $date;


            if(!empty($val['reserv_ation_leg']) && $val['reserv_ation_leg']['line_rune'] && $val['reserv_ation_leg']['line_rune']['vehicle_fleet']){
                if($val['e_reservation_status'] == "Cancelled"){
                    $shuttle_mumber =  "-";
                }else{
                    $shuttle_mumber = $val['reserv_ation_leg']['line_rune']['vehicle_fleet']['v_vehicle_code'];
                }
            }else{
                $shuttle_mumber =  "-";
            }
            $data[$key][$index++] = $shuttle_mumber;
            $data[$key][$index++] =  $val['e_reservation_status'];
        }
        $return_data['data'] = $data;
        $return_data['iTotalRecords'] = $arrUsers['total'];
        $return_data['iTotalDisplayRecords'] = $arrUsers['total'];
        $return_data['data_array'] = $arrUsers['data'];
        return $return_data;
    }
    
    public function getUpcomingViewReservationDetail(Request $request,$id) {
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
        } else {
            $reservation_rec1 = $id;
        }
        if(!empty($edit_record)) {      
            $reservation_record = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory','PickupCity','DropOffCity', 'ReservationLogs' => function($q) {
                $q->with(['CustomersLogs', 'AdminLogs', 'Reservations', 'Reservations.Customers'])->where('e_module_name', 'Reservation');
            }])->where(['id' => $reservation_rec1])->first();   
            // pr($reservation_record);exit();   
            $payment_info = Transactions::where('i_reservation_id',$id)->whereIn('e_type',['Booked','Booked-Wallet','Cash-On-Board'])->orderBy('created_at','DESC')->get()->toArray();
            $payment_mode = $this->getPaymentMode($id);
            $reservation_luggage_info = ReservationLuggageInfo::with(['SystemLuggageDef'])->where('i_reservation_id', $reservation_rec1)->get()->toArray();
            $reservation_pet_info = ReservationLuggageInfo::with(['SystemAnimalDef'])->where('i_reservation_id', $reservation_rec1)->get()->toArray();
            $reservation_luggage_info_total = ReservationLuggageInfo ::where('i_reservation_id', $reservation_rec1)->get()->sum('d_price');

            
            // Code to display "Cancel" button to user based on various conditions
            $show_cancel_btn = $this->isCancelButtonShow($id);
            

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
        
            return view('frontend.reservation.upcoming_reservation_view', array('title' => 'Reservation Detail','reservation_record' => $reservation_record,'reservation_record_rt' => $reservation_record_rt,'reservation_luggage_info'=>$reservation_luggage_info,'reservation_pet_info'=>$reservation_pet_info,'reservation_luggage_info_rt'=>$reservation_luggage_info_rt,'reservation_pet_info_rt'=>$reservation_pet_info_rt,'total_fare_amount'=>$total_fare_amount,'total_fare_amount_rt'=>$total_fare_amount_rt,'reservation_luggage_info_total'=>$reservation_luggage_info_total,'reservation_luggage_info_total_rt'=>$reservation_luggage_info_total_rt,'id'=>$id,'payment_info'=>$payment_info,'show_cancel_btn' => $show_cancel_btn,'res1_tt_text' => $res1_tt_text,'res2_tt_text' => $res2_tt_text,'payment_mode' => $payment_mode));
        } else {
            return redirect(FRONTEND_URL);
        }
        
    }

    public function getPastViewReservationDetail(Request $request,$id) {
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
        }else {
            $reservation_rec1 = $id;
        }
        
        if(!empty($edit_record)) {     
            $reservation_record = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory', 'ReservationLogs' => function($q) {
                $q->with(['CustomersLogs', 'AdminLogs', 'Reservations', 'Reservations.Customers'])->where('e_module_name', 'Reservation');
            }])->where(['id' => $reservation_rec1])->first();
            
            $payment_info = Transactions::where('i_reservation_id',$id)->whereIn('e_type',['Booked','Booked-Wallet','Cash-On-Board'])->orderBy('created_at','DESC')->get()->toArray();
            $payment_mode = $this->getPaymentMode($id);
            $reservation_luggage_info = ReservationLuggageInfo::with(['SystemLuggageDef'])->where('i_reservation_id', $reservation_rec1)->get()->toArray();
            $reservation_pet_info = ReservationLuggageInfo::with(['SystemAnimalDef'])->where('i_reservation_id', $reservation_rec1)->get()->toArray();
            $reservation_luggage_info_total = ReservationLuggageInfo ::where('i_reservation_id', $reservation_rec1)->get()->sum('d_price');

            
            // Code to display "Cancel" button to user based on various conditions
            $show_cancel_btn = $this->isCancelButtonShow($id);

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
        
            return view('frontend.reservation.past_reservation_view', array('title' => 'Reservation Detail','reservation_record' => $reservation_record,'reservation_record_rt' => $reservation_record_rt,'reservation_luggage_info'=>$reservation_luggage_info,'reservation_pet_info'=>$reservation_pet_info,'reservation_luggage_info_rt'=>$reservation_luggage_info_rt,'reservation_pet_info_rt'=>$reservation_pet_info_rt,'total_fare_amount'=>$total_fare_amount,'total_fare_amount_rt'=>$total_fare_amount_rt,'reservation_luggage_info_total'=>$reservation_luggage_info_total,'reservation_luggage_info_total_rt'=>$reservation_luggage_info_total_rt,'id'=>$id,'payment_info'=>$payment_info,'show_cancel_btn' => $show_cancel_btn,'res1_tt_text' => $res1_tt_text,'res2_tt_text' => $res2_tt_text,'payment_mode' => $payment_mode));
        } else {
            return redirect(FRONTEND_URL);
        }
        
    }
    
    public function getCardInformation(Request $request){
        $current_user = auth()->guard('customers')->user();
        $customer = [];
        if($current_user->customer_stripe_id){
            try {
                
                Stripe::setApiKey(STRIP_API_KEY);
                $customer = \Stripe\Customer::retrieve($current_user->customer_stripe_id);
               
            
            } catch (\Stripe\Exception\InvalidRequest $e) {
                $body = $e->getJsonBody();
                return array('status' => 'ERROR', 'message' => $e->getMessage());
            } catch(\Stripe\Exception\CardException $e) {
                 $body = $e->getJsonBody();
                return array('status' => 'ERROR', 'message' => $e->getMessage());
            } catch (\Stripe\Exception\Authentication $e) {
                $body = $e->getJsonBody();
                return array('status' => 'ERROR', 'message' => $e->getMessage());
            } catch (\Stripe\Exception\Permission $e) {
                $body = $e->getJsonBody();
                return array('status' => 'ERROR', 'message' => $e->getMessage());
            } catch (\Stripe\Exception\Card $e) {
                $body = $e->getJsonBody();
                return array('status' => 'ERROR', 'message' => $e->getMessage());
            } catch (\Stripe\Exception\RateLimit $e) {
                $body = $e->getJsonBody();
                return array('status' => 'ERROR', 'message' => $e->getMessage());
            } catch (\Stripe\Exception\Api $e) {
                $body = $e->getJsonBody();
                return array('status' => 'ERROR', 'message' => $e->getMessage());
            } catch (\Stripe\Exception\Subscription $e) {
                $body = $e->getJsonBody();
                return array('status' => 'ERROR', 'message' => $e->getMessage());
            } catch (\Stripe\Exception\Customer $e) {
                $body = $e->getJsonBody();
                return array('status' => 'ERROR', 'message' => $e->getMessage());
            } catch (Exception $e) {
                $body = $e->getJsonBody();
                return array('status' => 'ERROR', 'message' => $e->getMessage());
            }
        }
        return view('frontend.reservation.card_information', array('title' => 'My card information','customer'=>$customer,'records'=>$current_user));
    }

    public function AddAnyCardInformations(Request $request){
        $current_user = auth()->guard('customers')->user();
        $inputs = $request->all();
        $cust_id = $current_user->id;
        $currunt_year =  date("Y");
        $rest_year =  $currunt_year + 5 ;
        
        
            if ($inputs) {
            
                $validator = Validator::make($inputs, [
                    'i_card_num' => 'required',               
                    'i_card_exp_month' => 'required',   
                    'i_card_exp_year' => 'required',               
                    'i_cvc' => 'required',                
                ]);
                
                if ($validator->fails()) {
                    return json_encode($validator->errors());
                } else {

                    try {

                        Stripe::setApiKey(STRIP_API_KEY);
                        $myCard = array('number' => $inputs['i_card_num'], 'exp_month' => $inputs['i_card_exp_month'], 'exp_year' => $inputs['i_card_exp_year'],'cvc' => $inputs['i_cvc'],);
                        $email = $current_user['v_email'];
                        
                        $response = \Stripe\Token::create(array(
                            "card" => $myCard,
                        ));

                        if(is_null($current_user['customer_stripe_id'])){

                            $customer = \Stripe\Customer::create(array(
                                'source'   => $response->id,
                                'email'    => $email,
                            ));
                            $current_user->customer_stripe_id = $customer->id;
                            $current_user->save();
                        }else{
                            $customer = \Stripe\Customer::retrieve($current_user->customer_stripe_id);
                            $source = $customer->sources->create(array('source'   => $response->id));
                        }
                    
                        
                        return response()->json([
                            'status' => 'TRUE',
                            'redirect_url' => FRONTEND_URL.'my-card-information',
                        ]);
                
                    }catch (\Stripe\Exception\InvalidRequest $e) {
                        $body = $e->getJsonBody();
                            return array('status' => 'ERROR', 'message' => $e->getMessage());
                        } catch(\Stripe\Exception\CardException $e) {
                            $body = $e->getJsonBody();
                            return array('status' => 'ERROR', 'message' => $e->getMessage());
                        }catch (\Stripe\Exception\Authentication $e) {
                        $body = $e->getJsonBody();
                            return array('status' => 'ERROR', 'message' => $e->getMessage());
                        } catch (\Stripe\Exception\Permission $e) {
                        $body = $e->getJsonBody();
                            return array('status' => 'ERROR', 'message' => $e->getMessage());
                        } catch (\Stripe\Exception\Card $e) {
                        $body = $e->getJsonBody();
                            return array('status' => 'ERROR', 'message' => $e->getMessage());
                        } catch (\Stripe\Exception\RateLimit $e) {
                        $body = $e->getJsonBody();
                            return array('status' => 'ERROR', 'message' => $e->getMessage());
                        } catch (\Stripe\Exception\Api $e) {
                        $body = $e->getJsonBody();
                            return array('status' => 'ERROR', 'message' => $e->getMessage());
                        } catch (\Stripe\Exception\Subscription $e) {
                        $body = $e->getJsonBody();
                            return array('status' => 'ERROR', 'message' => $e->getMessage());
                        } catch (\Stripe\Exception\Customer $e) {
                        $body = $e->getJsonBody();
                            return array('status' => 'ERROR', 'message' => $e->getMessage());
                        } catch (Exception $e) {
                        $body = $e->getJsonBody();
                            return array('status' => 'ERROR', 'message' => $e->getMessage());
                        }
                    
                }
            } else {
                return View('frontend.reservation.add_card_info', array('title' => 'Add Card information','customer_id'=>$cust_id,'currunt_year'=>$currunt_year,'rest_year'=>$rest_year));
            }
        return Redirect(SITE_URl . 'my-profile');
    }

    public function anyDeleteCard($id){
        $current_user = auth()->guard('customers')->user();
        Stripe::setApiKey(STRIP_API_KEY);
      
        $customer = \Stripe\Customer::retrieve($current_user->customer_stripe_id);

        $response =  \Stripe\Customer::deleteSource(
                
                $current_user['customer_stripe_id'],
                $customer['default_source']
            );
           
            if ($response['deleted'] = 1) {

                return 'TRUE';
            } else {
                return 'FALSE';
            }
    }

    public function setDefaultCard($id,$source){
        $current_user = auth()->guard('customers')->user();
        Stripe::setApiKey(STRIP_API_KEY);
        if($current_user){

            $customer = \Stripe\Customer::retrieve($current_user->customer_stripe_id);
            $customer->default_source = $source;

            if($customer->save()){
                return 'TRUE';
            }else{
                return 'FALSE';
            }

         }else{
            return 'FALSE';
         }
      
        
    }

    protected function doStripeRefund($record,$id) {
        $payment_info_booked = Transactions::where('i_reservation_id',$id)->orderBy('created_at','DESC')->where('e_type','Booked')->first();
       
        if($payment_info_booked && $payment_info_booked['e_status']=="Success") {
            $refund_amount = (float) $payment_info_booked->d_amount;
            $refund_amount = ($refund_amount) ? $refund_amount : 0; 
            $refund_amount = $refund_amount * 100; // Convert to cents

            try {
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
                    return ['status' => 'TRUE', 'message' => "Refund processed successfully."];
                } else {
                    $msg = "Error occurred in processing refund";
                    if($res && $res->status=="failed") {
                        $msg = $res->failure_reason;
                    }
                    return ['status' => 'FALSE', 'message' => $msg];
                }

            } catch (\Stripe\Exception\CardException $e) {
                return ['status' => 'FALSE', 'message' => $e->getMessage()];
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                return ['status' => 'FALSE', 'message' => $e->getMessage()];
            } catch (\Stripe\Exception\AuthenticationException $e) {
                return ['status' => 'FALSE', 'message' => $e->getMessage()];
            } catch (\Stripe\Exception\RateLimitException $e) {
                return ['status' => 'FALSE', 'message' => $e->getMessage()];
            } catch (\Stripe\Exception\ApiConnectionException $e) {
                return ['status' => 'FALSE', 'message' => $e->getMessage()];
            } catch (\Stripe\Exception\ApiErrorException $e) {
                return ['status' => 'FALSE', 'message' => $e->getMessage()];
            } catch (Exception $e) {
                return ['status' => 'FALSE', 'message' => $e->getMessage()];
            }
            
        } else {
            return ['status' => 'FALSE', 'message' => "Error occurred in processing refund"];
        }
    }

    public function cancelResrvation($id){
        $record = Reservations::find($id);
        $current_user = auth()->guard('customers')->user();
        $refund_available = $this->checkRefundAvailability($id);
        
        $status = "Cancelled";
        $msg = 'Your reservation cancelled successfully.';

        switch($refund_available) {
            case 1:
                $response = $this->doStripeRefund($record,$id);
                if($response['status'] == 'TRUE') {
                    $status = "Refunded";
                    $msg = $msg." ".$response['message'];
                } else {
                    $msg = $msg." Error processing refund amount. Please contact system admin.";
                }
                break;

            case 2:
                $payment_info_booked = Transactions::where('i_reservation_id',$id)->orderBy('created_at','DESC')->where('e_type','Booked')->first();
                $current_user->d_wallet_balance = $current_user->d_wallet_balance - $payment_info_booked->d_amount;
                $current_user->save();
                $status = "Refunded";
                $msg = $msg." And refund amount added to your wallet balance";
                break;

            case 3:
                $status = "Refund Requested";
                $msg = $msg." And refund request sent to system admin.";
                break;
        }


        $record['e_reservation_status'] =  $status;
        if (!empty($record)) {
            if ($record->save()) {
                Session::flash('success-message', $msg);
                return 'TRUE';
            } else {
                return 'FALSE';
            }
        } else {
            return 'FALSE';
        }
       
    }

    public function makeRefundRequest($id){
        $record = Reservations::with(['Transactions' => function($q){
            $q->where('e_type','Booked');
        }])->where('id',$id)->first();
        
        if (!empty($record) && $record['e_reservation_status']=='Cancelled' && count($record['transactions']) > 0) {
            $record->e_reservation_status =  "Refund Requested";
            if ($record->save()) {
                $objEmailTemplate = EmailTemplate::find(5)->toArray();
                if($objEmailTemplate) {
                    $sys_settings = SystemSettings::select('v_comp_email')->find(1);
                    
                    $strTemplate = $objEmailTemplate['t_email_content'];
                    $strTemplate = str_replace('[SITE_NAME]', SITE_NAME, $strTemplate);
                    $strTemplate = str_replace('[CUSTOMER_NAME]',$record['v_contact_name'],$strTemplate);
                    $strTemplate = str_replace('[RESV_VIEW_URL]',ADMIN_URL."reservations/view/".$id,$strTemplate);
                   
                    $subject = str_replace('[RESV_NUMBER]',$record['v_reservation_number'], $objEmailTemplate['v_template_subject']);
                   
                    // mail sent to user with new link
                    
                    Mail::send('emails.auth.generate-email-template', array('strTemplate'=>$strTemplate), function($message) use ($subject,$sys_settings)
                    {
                        $message->from(CONTACT_EMAIL_ID, SITE_NAME);
                        $message->to($sys_settings->v_comp_email);
                        $message->subject($subject);
                    });
                }
                return 'TRUE';
            } else {
                return 'FALSE';
            }
        } else {
            return 'FALSE';
        }
       
    }

    public function getPDFdata(Request $request,$id){
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
        }else {
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
            
            PDF::html('frontend.reservation.download_pdf_data', array('title' => 'Rservation Summary','reservation_record' => $reservation_record,'reservation_record_rt' => $reservation_record_rt,'reservation_luggage_info'=>$reservation_luggage_info,'reservation_pet_info'=>$reservation_pet_info,'reservation_luggage_info_rt'=>$reservation_luggage_info_rt,'reservation_pet_info_rt'=>$reservation_pet_info_rt,'total_fare_amount'=>$total_fare_amount,'total_fare_amount_rt'=>$total_fare_amount_rt,'reservation_luggage_info_total'=>$reservation_luggage_info_total,'reservation_luggage_info_total_rt'=>$reservation_luggage_info_total_rt, 'payment_info' => $payment_info,'total_payment'=>$total_payment,'res1_tt_text' => $res1_tt_text,'res2_tt_text' => $res2_tt_text,'payment_mode' => $payment_mode),ADMIN_FILES_PATH.$filename);
            
            return Response::download(ADMIN_FILES_PATH.$filename.'.pdf');
        } else {
            return redirect(FRONTEND_URL);
        }
        
    }

    public function getPrintdata(Request $request,$id){
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
        }else {
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
            if($reservation_record['e_class_type'] == 'OW') {
                $total_payment = $this->calculateTotalFare($reservation_record['id']);
            } else {
                $total_payment = $this->calculateTotalFare($reservation_record['id'],$reservation_record_rt['id']);
            }
           
            
            return view('frontend.reservation.download_pdf_data', array('title' => 'Rservation Summary','reservation_record' => $reservation_record,'reservation_record_rt' => $reservation_record_rt,'reservation_luggage_info'=>$reservation_luggage_info,'reservation_pet_info'=>$reservation_pet_info,'reservation_luggage_info_rt'=>$reservation_luggage_info_rt,'reservation_pet_info_rt'=>$reservation_pet_info_rt,'total_fare_amount'=>$total_fare_amount,'total_fare_amount_rt'=>$total_fare_amount_rt,'reservation_luggage_info_total'=>$reservation_luggage_info_total,'reservation_luggage_info_total_rt'=>$reservation_luggage_info_total_rt,'payment_info'=>$payment_info,'total_payment'=>$total_payment,'res1_tt_text' => $res1_tt_text,'res2_tt_text' => $res2_tt_text,'payment_mode' => $payment_mode));
            
            
        } else {
            return redirect(FRONTEND_URL);
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
}    
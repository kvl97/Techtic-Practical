<?php
namespace App\Http\Controllers;
use App\Http\Requests;
use Hash, Mail, Redirect, Validator, Cookie, Auth, Session, DB, URL, DateTime, Date, PDF;
use App\Models\Offers;
use App\Models\LineRun;
use App\Models\Reservations;
use App\Models\ReservationLeg;
use App\Models\ReservationLuggageInfo;
use App\Models\ReservationTravellerInfo;
use App\Models\EmailTemplate;
use App\Models\Customers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App;

class CronController 
{
    public function changeExpireDateStatus() {
        $record = Offers::where('d_expire_date', '<', date("Y/m/d"))->update(['e_status' => 'Expired']);
    }

    public function changeLinerunStatus() {
        $record = LineRun::where('d_run_date', '<', date("Y/m/d"))->update(['e_run_status' => 'Completed']);
    }

    public function cancelPendingPaymentReservations(){
      
        $current_date_time = date('Y-m-d H:i');
        // $current_date_time = "2020-09-11 09:01";
        $before_24_hrs_date_time = strtotime($current_date_time) - (60*60*24);
        $before_24_hrs_date_time = date('Y-m-d H:i',$before_24_hrs_date_time);

        // code to cancel pending payment bookings
        $reservations = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities','Customers'])->where('e_reservation_status','Pending Payment','PickupCity','DropOffCity')->where('updated_at','<',$before_24_hrs_date_time)->get();
        
        if($reservations){
            $objEmailTemplate = EmailTemplate::find(7)->toArray();
            foreach($reservations as $r){
                $r->e_reservation_status = "Cancelled";
                $r->save();

                if($objEmailTemplate) {
                    $htmlData = $this->getAddressInfo($r['id']);
                   /*  $pick_city = $r->PickupCity->v_city;
                    $drop_city = $r->DropOffCity->v_city; */
                    $customer_email = $r->Customers->v_email;
                    $strTemplate = $objEmailTemplate['t_email_content'];
                    $strTemplate = str_replace('[SITE_NAME]', SITE_NAME, $strTemplate);
                    $strTemplate = str_replace('[TICKET_INFORMATION]', $htmlData, $strTemplate);
                    /* $strTemplate = str_replace('[PICK_LOC]', $pick_city." - ".$r->v_pickup_address, $strTemplate);
                    $strTemplate = str_replace('[DROP_LOC]', $drop_city." - ".$r->v_dropoff_address, $strTemplate);
                    $strTemplate = str_replace('[TRAVEL_DATE]',date("m/d/Y",strtotime($r->d_travel_date)),$strTemplate);
                    $strTemplate = str_replace('[TRAVEL_TIME]',date('g:i A' , strtotime($r->t_comfortable_time)),$strTemplate);
                    $strTemplate = str_replace('[TRIP_TYPE]',$r->e_shuttle_type,$strTemplate);
                    $strTemplate = str_replace('[NO_OF_PASSENGERS]',$r->i_total_num_passengers,$strTemplate); */
                    $strTemplate = str_replace('[CUSTOMER_NAME]',$r->v_contact_name,$strTemplate);
    
                    $subject = $objEmailTemplate['v_template_subject'];
                    
                    // mail sent to user with new link
                    
                    Mail::send('emails.auth.generate-email-template', array('strTemplate'=>$strTemplate), function($message) use ($subject,$r,$customer_email)
                    {
                        $message->from(CONTACT_EMAIL_ID, SITE_NAME);
                        $message->to($r->v_contact_email);
                        if($customer_email != $r['v_contact_email']){			
                            $message->replyTo($customer_email);	
                        }
                        $message->subject($subject);
                    });
                }
                
            }
        }

        // code to cancel pending payment/requested bookings for private shuttle
        $reservations = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities','Customers','PickupCity','DropOffCity'])->where(function($q) use($current_date_time) {
            $q->where('e_reservation_status','Requested')->where(function($q) use($current_date_time){
                $q->where(DB::raw('CONCAT(d_travel_date," ",t_flight_time)'),'<=',$current_date_time)->orWhere(DB::raw('CONCAT(d_travel_date," ",t_comfortable_time)'),'<=',$current_date_time);
            });
        })->orWhere(function($q) use($before_24_hrs_date_time) {
            $q->where('e_reservation_status','Request Confirmed')->where('updated_at','<',$before_24_hrs_date_time);
        })->get();

        if($reservations){
            $objEmailTemplate = EmailTemplate::find(11)->toArray();
            foreach($reservations as $r){
                $old_status = $r->e_reservation_status;
                $r->e_reservation_status = "Rejected";
                $r->save();

                if($objEmailTemplate && $old_status=='Request Confirmed') {
                    $strTemplate = $objEmailTemplate['t_email_content'];
                   /*  $pick_city = $r->PickupCity->v_city;
                    $drop_city = $r->DropOffCity->v_city; */
                    $customer_email = $r->Customers->v_email;
                    $htmlData = $this->getAddressInfo($r['id']);
                    $strTemplate = str_replace('[SITE_NAME]', SITE_NAME, $strTemplate);
                    $strTemplate = str_replace('[TICKET_INFORMATION]', $htmlData, $strTemplate);
                     /* $strTemplate = str_replace('[PICK_LOC]', $pick_city." - ".$r['v_pickup_address'], $strTemplate);
                    $strTemplate = str_replace('[DROP_LOC]', $drop_city." - ".$r['v_dropoff_address'], $strTemplate);
                    $strTemplate = str_replace('[TRAVEL_DATE]',date("m/d/Y",strtotime($r->d_travel_date)),$strTemplate);
                    $strTemplate = str_replace('[TRAVEL_TIME]',date('g:i A' , strtotime($r->t_comfortable_time)),$strTemplate);
                    $strTemplate = str_replace('[TRIP_TYPE]',$r->e_shuttle_type,$strTemplate);
                    $strTemplate = str_replace('[NO_OF_PASSENGERS]',$r->i_total_num_passengers,$strTemplate); */
                    $strTemplate = str_replace('[CUSTOMER_NAME]',$r->v_contact_name,$strTemplate);
    
                    $subject = $objEmailTemplate['v_template_subject'];
                    
                    // mail sent to user with new link
                    
                    Mail::send('emails.auth.generate-email-template', array('strTemplate'=>$strTemplate), function($message) use ($subject,$r,$customer_email)
                    {
                        $message->from(CONTACT_EMAIL_ID, SITE_NAME);
                        $message->to($r->v_contact_email);
                        if($customer_email != $r['v_contact_email']){			
                            $message->replyTo($customer_email);	
                        }
                        $message->subject($subject);
                    });
                }
                
            }
        }
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
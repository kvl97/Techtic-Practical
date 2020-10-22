<?php 
namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Reservations;
use App\Models\Customers;
use App\Models\GeoPoint;
use App\Models\GeoCities;
use App\Models\ReservationTravellerInfo;
use App\Models\ReservationLuggageInfo;
use App\Models\ReservationLeg;
use App\Models\LineRun;
use App\Models\Admin;
use App\Models\DriverExtension;
use Session, Redirect, Validator, DB, Hash, Response;

class ImportHudsonController extends BaseController {

    public function handleImport() {
        $file = fopen(FILES_PATH.'Export_Reservations_2016_Jan_to_June.csv',"r");
    
        $importData_arr = array();
        $i = 0;
        $imported_count = 0;

        while (($row = fgetcsv($file,2, ",")) !== FALSE) {
            if($i > 10) {
                break;
            }
            
            // Skip first row (Remove below comment if you want to skip the first row)
            if($i == 0){
                $i++;
                continue; 
            }
            // pr($row);exit;

            // Create or get customer
            $cust_id = $this->createCustomer($row[67],$row[65],$row[66],$row[49]);
            if($cust_id) {
                // Create reservation
                $resv_number = reservationNumber($row[0]);
                $resv = Reservations::where('v_reservation_number',$resv_number)->first();    
                if(!$resv) {
                    $resv = $this->createReservation($row,$cust_id);
                    $linerun = $this->createLineRun($row,$resv);
                    $this->createLeg($resv,$linerun);
                    $this->insertPassengerInfo($resv->id,$row);
                    $this->insertLuggageInfo($resv->id,$row);
                    $imported_count++;
                }
            }

            // exit();
            $i++;
        }
        fclose($file);
        echo "Total imported records = ".$imported_count;
    }

    private function insertLuggageInfo($res_id,$row) {
        $record = ReservationLuggageInfo::where('i_reservation_id',$res_id)->first();
        if(!$record) {
            $record = new ReservationLuggageInfo;
            $record->i_reservation_id = $res_id;
        }
        $record->i_sys_luggage_id = 1;
        $record->i_value = $row[74];
        $record->d_price = 0.00;
        $record->save();
    }

    private function insertPassengerInfo($res_id,$row) {
        $total_pass = ($row[15] + $row[16] + $row[17] + $row[18]);
        if($total_pass=="") {
            $total_pass = 0;
        }
        for($i=0; $i<$total_pass;$i++){
            $record = new ReservationTravellerInfo;
            $record->i_reservation_id = $res_id;
            $record->v_traveller_name = '';
            $record->d_birth_month_year = date('Y-m-d');
            $record->e_is_travel_alone = 'NO';
            $record->e_type = 'Adult';
            $record->d_fare_amount = ($row[12]!="" && $i==0) ? str_replace('$','',$row[12]) : 0.00;
            $record->save();
        }
    }

    private function createLeg($resv,$linerun) {
        if($resv && $linerun) {
            
            $leg = ReservationLeg::where('i_reservation_id',$resv->id)->first();
            if(!$leg) {
                $leg = new ReservationLeg;
                $leg->i_reservation_id = $resv->id;
            }
            $leg->i_run_id = $linerun->id;
            $leg->save();
        }   
    }

    private function createCustomer($email,$tel1,$tel2,$created_at) {
        $modelCustomers = Customers::where(['v_email' => $email])->first();
        if(!$modelCustomers) {
            $modelCustomers = new Customers;
            $modelCustomers->v_email = $email;
            $modelCustomers->password = '';
            $modelCustomers->v_phone = $tel1;
            $modelCustomers->v_landline_number = $tel2;
            $modelCustomers->v_firstname = explode('@',$email)[0];
            $modelCustomers->v_lastname = '';
            $modelCustomers->e_user_type = 'Guest';
            $modelCustomers->created_at = (isset($created_at) && $created_at!='') ? date('Y-m-d H:i:s',strtotime($created_at)) : Carbon::now();
            $modelCustomers->save();
            return $modelCustomers->id;
        } else {
            return $modelCustomers->id;
        }
        return false;
    }

    private function createReservation($row,$cust_id) {
        $resv_number = reservationNumber($row[0]);
        $resv = Reservations::where('v_reservation_number',$resv_number)->first();    
        if($resv) {
            return $resv;
        }

        $resv = new Reservations;
        $resv->id = $row[0];
        $resv->v_reservation_number = $resv_number; 
        $resv->i_customer_id = $cust_id;

        if(strpos($row[9],'HOTEL')){
            $resv->i_reservation_category_id = 11; // For Hotel
        } else if(strpos($row[9],'MEET')) {
            $resv->i_reservation_category_id = 12; // For Meet point
        } else if(strpos($row[9],'TRAIN')) {
            $resv->i_reservation_category_id = 7; // For Train/Amtrak
        } else {
            $resv->i_reservation_category_id = 13; // For Airport
        }

        $pickup_addr = "";
        if($row[6]!='') {
            $pickup_addr = $row[6];
        } else if($row[5]!='') {
            $pickup_addr = $row[5];
        }

        $drop_addr = "";
        if($row[8]!='') {
            $drop_addr = $row[8];
        } else if($row[7]!='') {
            $drop_addr = $row[7];
        }
        
        $resv->v_pickup_address = $pickup_addr;
        $resv->v_dropoff_address = $drop_addr;
        $resv->i_pickup_city_id = $this->getCityId($row[5],$row[51]);
        $resv->i_dropoff_city_id = $this->getCityId($row[7],$row[56]);
        $resv->d_travel_date = ($row[3]!='') ? date('Y-m-d',strtotime($row[3])) : '';
        $resv->v_contact_name = explode('@',$row[67])[0];
        $resv->v_contact_phone_number = ($row[65]!='') ? $row[65] : $row[66];
        $resv->v_contact_email = $row[67];
        $resv->i_total_num_passengers = ($row[15] + $row[16] + $row[17] + $row[18]);
        $resv->i_number_of_luggages = $row[74];
        $resv->e_shuttle_type = ($row[1]=='' || $row[1]=='Shuttle Passenger Service') ? 'Shared' : 'Private';
        $resv->t_special_instruction = $row[9];
        $resv->e_reservation_status = ($row[13]=='Cancelled') ? $row[13] : ($row[13]=='Normal') ? 'Booked' : '';
        $resv->e_flight_type = ($row[62]!='') ? $row[62] : 'Domestic';
        $resv->v_flight_name = $row[60];
        $resv->v_flight_number = $row[61];
        $resv->t_flight_time = $row[4];
        $resv->d_total_fare = ($row[12]!="") ? str_replace('$','',$row[12]) : 0.00;
        $resv->t_comfortable_time = date('H:i:s',strtotime($row[3]));
        $resv->created_at = (isset($row[49]) && $row[49]!='') ? date('Y-m-d H:i:s',strtotime($row[49])) : Carbon::now();
        $resv->save();
        return $resv;
    }

    private function getCityId($loc,$city) {
        $city_id = null;
        $city_rec  = GeoCities::select('id','i_service_area_id')->where('v_city','like','%'.$city.'%')->first();
        
        if(!$city_rec) {
            $city_rec  = GeoCities::select('id','i_service_area_id')->where('v_city','like','%'.$loc.'%')->first();
        }

        if($city_rec){
            $city_id = $city_rec->id;
        }

        if($loc=='Seattle/Tacoma Airport' || $city=='Seattle/Tacoma Airport') {
            $city_id = 2; // Considering city as Seatac
        }

        return $city_id;
    }

    private function createLineRun($row,$resv) {
        $linerun = LineRun::find($row[11]);
        $pick_loc = GeoCities::select('i_service_area_id')->where('id',$resv->i_pickup_city_id)->first();
        $drop_loc = GeoCities::select('i_service_area_id')->where('id',$resv->i_dropoff_city_id)->first();

        if(!$linerun){
            $linerun = new LineRun;
            $linerun->id = $row[11];
            $linerun->d_run_date =  date('Y-m-d',strtotime($row[3]));
            $linerun->i_origin_service_area_id = ($pick_loc) ? $pick_loc->i_service_area_id : 0;
            $linerun->i_dest_service_area_id = ($drop_loc) ? $drop_loc->i_service_area_id : 0;
            $linerun->t_scheduled_arr_time = date('H:i:s',strtotime($row[3]));
            $linerun->e_run_status = 'Completed';
            $linerun->i_driver_id = $this->getDriverId($row[10]);
            $linerun->save();
        }
        return $linerun;
    }

    private function getDriverId($driver_ext) {
        $driver_ext = (int) filter_var($driver_ext, FILTER_SANITIZE_NUMBER_INT);
        $driver_ext = DriverExtension::select('i_driver_id')->where('v_extension','LIKE','%'.$driver_ext.'%')->first();
        if($driver_ext) {
            return $driver_ext->i_driver_id;
        } else {
            return false;
        }
        
    }
}
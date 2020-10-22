<?php

namespace App\Observers;
use App\Models\Logs, App\Models\Reservations; 
use Carbon\Carbon, Auth;
use Illuminate\Http\Request;

class ReservationObserver
{       
    protected $fields_array = [
        'e_reservation_status' => 'Reservation Status',
        't_special_instruction' => 'Special Instruction',
        'd_total_fare' => 'Total Fare'
    ];
    
    protected $ignore_field = [
        'v_reservation_number' => 'Resrvation Number',
        'i_customer_id' => 'Customer',
        'i_reservation_category_id' => 'Reservation Category',
        'added_by_id' => 'Added By',
        'added_by_type_id' => 'Added by Type',
        'i_origin_point_id' => 'Origin Point',
        'i_destination_point_id' => 'Destination Point',
        'v_pickup_address' => 'Pickup Address',
        'v_dropoff_address' => 'Dropoff Address',
        'i_pickup_city_id' => 'Pickup City',
        'i_dropoff_city_id' => 'Dropoff City',
        'd_travel_date' => 'Travel Date',
        'e_class_type' => 'Class Type',
        'v_contact_name' => 'Contact Name',
        'v_contact_phone_number' => 'Contact Phone Number',
        'v_contact_email' => 'Contact Email',
        't_best_time_tocall' => 'Best Time Tocall',
        'e_voice_mail_setup' => 'Voice Mail Setup',
        'i_total_num_passengers' => 'Total Number Passengers',
        'i_num_pets' => 'Number of Pets',
        'i_number_of_luggages' => 'Number of Luggages',
        'e_shuttle_type' => 'Shuttle Type',
        'i_private_approved_by' => 'Private Approved by',
        'e_flight_type' => 'Flight Type',
        'v_flight_name' => 'Flight Name',
        'v_flight_number' => 'Flight Number',
        't_flight_time' => 'Flight Time',
        't_comfortable_time' => 'Comfortable Time',
        't_target_time' => 'Target Time',
        'v_discount_code' => 'Discount Code',
        'd_discount_price' => 'Discount Price'
    ];

    public function updated(Reservations $reservationsObserverdata) {

        $requestData = request()->all();
        $old_data = $reservationsObserverdata->getOriginal();
        $new_data = $reservationsObserverdata->toArray();

        

        $diffNew = array_diff_assoc($new_data, $old_data);  
        // unset($diffNew['get_permissions']);
        
        $newRaw = [];
        $oldRaw = [];

        

        foreach ($diffNew as $key => $value) {
            
            if (in_array($key, $this->ignore_field)) {
                continue;
            }
            
            if (isset($this->fields_array[$key])) {
                
                if($diffNew[$key] != $old_data[$key]){
                    if(!array_key_exists('e_reservation_status',$diffNew)) {
                        $resv = Reservations::find($new_data['id']);
                        $oldRaw['Reservation Status'] = $resv['e_reservation_status'];
                        $newRaw['Reservation Status'] = $resv['e_reservation_status'];
                    }

                    $oldRaw[$this->fields_array[$key]] = $old_data[$key] == "" || $old_data[$key] == null  ? '-' : $old_data[$key];
                    $newRaw[$this->fields_array[$key]] = $new_data[$key] == "" || $new_data[$key] == null  ? '-' : $new_data[$key];
                } else {
                    continue;
                }                
            }
        }

        $newArray = array();
        $oldArray = array();
        $newArray['data'] =[
            'newArray' => $newRaw,
            'oldArray' => $oldRaw,
        ];
        
        
        if (auth()->guard('admin')->check()) {
            $authId = auth()->guard('admin')->user()->id;
            $authName = 'Admin';
        } elseif (auth()->guard('customers')->check()) {
           $authId = auth()->guard('customers')->user()->id;
           $authName = 'Customer';
        } else {
            $authId = 0;
            $authName = 'Customer';
        }

        if(isset($newArray['data']['newArray']) && !empty($newArray['data']['newArray'])) { 
            $reservationsLogdata = new Logs;
            $reservationsLogdata->i_rel_id  = $new_data['id'];
            $reservationsLogdata->e_module_name  = 'Reservation';
            $reservationsLogdata->v_log_json = json_encode($newArray);
            $reservationsLogdata->i_modified_by = $authId;
            $reservationsLogdata->e_user_type = $authName;
            $reservationsLogdata->e_action_name  = 'Update';
            $reservationsLogdata->created_at  = date('Y-m-d H:i:s');
            $reservationsLogdata->save();
        }
    }

    public function created(Reservations $reservations) {
        $new_data = $reservations->toArray();
        $newRaw = [];

        foreach ($new_data as $key => $value) {

            if (in_array($key, $this->ignore_field)) {
                continue;
            }
            if (isset($this->fields_array[$key])) {
                $newRaw[$this->fields_array[$key]] = $new_data[$key] == "" || $new_data[$key] == null  ? '-' : $new_data[$key];
            }
        }

        $newArray = array();
        $newArray['data'] = [
            'newArray' => $newRaw
        ];

        if (auth()->guard('admin')->check()) {
            $authId = auth()->guard('admin')->user()->id;
            $authName = 'Admin';
        } elseif (auth()->guard('customers')->check()) {
           $authId = auth()->guard('customers')->user()->id;
           $authName = 'Customer';
        } else {
            $authId = 0;
            $authName = 'Customer';
        }

        if(!empty($newRaw)) {
            $reservationsLogdata = new Logs;
            $reservationsLogdata->i_rel_id  = $new_data['id'];
            $reservationsLogdata->e_module_name  = 'Reservation';
            $reservationsLogdata->v_log_json = json_encode($newArray);
            $reservationsLogdata->i_modified_by = $authId;
            $reservationsLogdata->e_user_type = $authName;
            $reservationsLogdata->e_action_name  = 'Add';
            $reservationsLogdata->created_at  = date('Y-m-d H:i:s');
            $reservationsLogdata->save();
        }
    } 

    public function deleted(Reservations $reservations) {
     
        $newRaw = [];
        $newRaw['id'] = $reservations->id;
        
        $newArray['data'] =[
            'newArray' => $newRaw,
        ];
        if (auth()->guard('admin')->check()) {
            $authId = auth()->guard('admin')->user()->id;
            $authName = 'Admin';
        } elseif (auth()->guard('customers')->check()) {
           $authId = auth()->guard('customers')->user()->id;
           $authName = 'Customer';
        }

        if(!empty($newArray)) {           
            $reservationsLogdata = new Logs;
            $reservationsLogdata->i_rel_id  = $reservations['id'];
            $reservationsLogdata->e_module_name  = 'Reservation';
            $reservationsLogdata->v_log_json = json_encode($newArray);
            $reservationsLogdata->i_modified_by = $authId;
            $reservationsLogdata->e_user_type  = $authName;
            $reservationsLogdata->e_action_name  = 'Delete';
            $reservationsLogdata->created_at  = date('Y-m-d H:i:s');
            $reservationsLogdata->save();
        }
    } 

}
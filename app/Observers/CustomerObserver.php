<?php

namespace App\Observers;
use App\Models\Logs, App\Models\Customers; 
use Carbon\Carbon, Auth;

class CustomerObserver
{       
    protected $fields_array  = [
        'v_firstname' => 'First Name',
        'v_lastname' => 'Last Name',
        'e_gender' => 'Gender',
        'v_email' => 'Email',
        'd_dob' => 'Date Of Birth',
        'v_phone' => 'Phone Number',
        'e_status' => 'Status',
        'password' => 'Password',
        'e_user_type' => 'Type',
        'd_wallet_balance' => 'Wallet balance'
    ];

    protected $ignore_field = ['password','remember_token','customer_stripe_id'];

    public function updated(Customers $clientObserverdata) {
        $old_data = $clientObserverdata->getOriginal();
        $new_data = $clientObserverdata->toArray();
        
       
        $diffNew = array_diff_assoc($new_data, $old_data);  
        // unset($diffNew['get_permissions']);
      
        $newRaw = [];
        $oldRaw = [];
        foreach ($diffNew as $key => $value) {
           
            // $tmp_diffOldVal = $old_data[$key];
            if (in_array($key, $this->ignore_field)) {
                continue;
            }

            if (isset($this->fields_array[$key])) {
                if($diffNew[$key] != $old_data[$key]){
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
        
        // $newArray['noti_title'] = $new_data['v_name'];        
       
        if (auth()->guard('admin')->check()) {
            $authId = auth()->guard('admin')->user()->id;
            $authName = 'Admin';

        } elseif (auth()->guard('customers')->check()) {
           $authId = auth()->guard('customers')->user()->id;
           $authName = 'Customer';
        } elseif ($newRaw['Type'] == 'Customer' && $oldRaw['Type'] == 'Guest') {
            $authId = 0;
            $authName = 'Customer';
        } else {
            $authId = 0;
            $authName = 'Customer';
        }

        if(isset($newArray['data']['newArray']) && !empty($newArray['data']['newArray'])) {     
           
            $customerLogdata = new Logs;
            $customerLogdata->i_rel_id  = $new_data['id'];
            $customerLogdata->e_module_name  = 'Customer';
            $customerLogdata->v_log_json = json_encode($newArray);
            $customerLogdata->i_modified_by = $authId;
            $customerLogdata->e_user_type = $authName;
            $customerLogdata->e_action_name  = 'Update';
            $customerLogdata->created_at  = date('Y-m-d H:i:s');
            $customerLogdata->save();
        }
        
    }

    public function created(Customers $customer) {
        $new_data = $customer->toArray();
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
            $customerLogdata = new Logs;
            $customerLogdata->i_rel_id  = $new_data['id'];
            $customerLogdata->e_module_name  = 'Customer';
            $customerLogdata->v_log_json = json_encode($newArray);
            $customerLogdata->i_modified_by = $authId;
            $customerLogdata->e_user_type = $authName;
            $customerLogdata->e_action_name  = 'Add';
            $customerLogdata->created_at  = date('Y-m-d H:i:s');
            $customerLogdata->save();
        }
    }  

    public function deleted(Customers $customer) {
     
        // $client = Clients::withTrashed()->where('id', $client->id)->first(); // to access relational function auto_assigned, template, ...
       
        $newRaw = [];
        $newRaw['id'] = $customer->id;
        
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
            $customerLogdata = new Logs;
            $customerLogdata->i_rel_id  = $customer['id'];
            $customerLogdata->e_module_name  = 'Customer';
            $customerLogdata->v_log_json = json_encode($newArray);
            $customerLogdata->i_modified_by = $authId;
            $customerLogdata->e_user_type  = $authName;
            $customerLogdata->e_action_name  = 'Delete';
            $customerLogdata->created_at  = date('Y-m-d H:i:s');
            $customerLogdata->save();
        }
     

    }  
}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Models\CustomerAddresses;
use App\Models\SystemResCategory;
use App\Models\GeoPoint;
use App\Models\GeoCities;
use App\Models\Admin;
use App\Models\Reservations;
use App\Models\FareTable;
use App\Models\FareClass;
use App\Models\ReservationTravellerInfo;
use App\Models\SystemLuggageDef;
use App\Models\ReservationLuggageInfo;
use App\Models\ReservationInfo;
use App\Models\SystemIcaoDef;
use App\Models\SystemPaymentDef;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail, Session, Redirect, Validator, DB, Hash;
use Illuminate\Validation\Rule;
use \Stripe\Stripe;

class CustomersController extends BaseController {

    public function getIndex() {
        return View('backend.customers.index', array('title' => "Customers"));
    }

    public function anyListAjax(Request $request) {
        $data = $request->all();

        $sortColumn = array('','v_firstname', 'v_email', 'e_gender', 'd_dob', 'v_phone', 'e_status');
        $query = new Customers;
        if (isset($data['v_firstname']) && $data['v_firstname'] != '') {
            $query = $query->where(DB::raw("CONCAT(v_firstname, ' ',v_lastname)"), 'LIKE', '%' . trim($data['v_firstname']) . '%');
        }
        if (isset($data['v_email']) && $data['v_email'] != '') {
            $query = $query->where('v_email', 'LIKE', '%' . $data['v_email'] . '%');
        }
        if (isset($data['e_gender']) && $data['e_gender'] != '') {
            $query = $query->where('e_gender', '=', $data['e_gender']);
        }
        if (isset($data['d_dob']) && $data['d_dob'] != '') {
            $query = $query->where(DB::raw('DATE(d_dob)'), '=', trim(date('Y-m-d',strtotime($data['d_dob']))));
        }
        if (isset($data['v_phone']) && $data['v_phone'] != '') {
            $query = $query->where('v_phone', 'LIKE', '%' . $data['v_phone'] . '%');
        }
        if (isset($data['e_status']) && $data['e_status'] != '') {
            $query = $query->where('e_status', '=', $data['e_status']);
        }
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
        $arrUsers = $users->toArray();
        $data = array();
        foreach ($arrUsers['data'] as $key => $val) {
            $index = 0;
            $data[$key][$index++] = '<label class="kt-checkbox kt-checkbox--single kt-checkbox--solid "><input type="checkbox" name="id[]" value="' . $val['id'] . '" class="kt-checkable"><span></span></label>';
            $data[$key][$index++] = $val['v_firstname'].' '.$val['v_lastname'];
            $data[$key][$index++] = $val['v_email'];
            $data[$key][$index++] = $val['e_gender'];
            $data[$key][$index++] = $val['d_dob'] ? date(DATE_FORMAT,strtotime($val['d_dob'])) : '';
            $data[$key][$index++] = $val['v_phone'];
            $data[$key][$index++] = $val['e_status'];
            $action = '';
            $action .= '<div class="d-flex">';
            if(isset($this->permission) && isset($this->permission[3]['i_add_edit']) && $this->permission[3]['i_add_edit'] == 1) {
                $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . ADMIN_URL . 'customers/edit/' . $val['id'] . '" title="edit"><i class="la la-edit"></i> </a>';
            }
            if(isset($this->permission) && isset($this->permission[3]['i_delete']) && $this->permission[3]['i_delete'] == 1) {
                $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' . ADMIN_URL . 'customers/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';
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
        // pr($inputs); exit;
        if ($inputs) {
            $record = new Customers;
            $validator = Validator::make($inputs, [

                'v_email' => 'required|unique:customers,v_email,NULL,id,deleted_at,NULL',
                'v_firstname' => 'required',
                'v_lastname' => 'required',
                'e_gender' => 'required',
                'd_dob' => 'required',
                'v_phone' => 'required', 
                'e_status' => 'required', 
                'v_street' => 'required', 
                'v_city' => 'required', 
                'v_state' => 'required', 
                'v_country' => 'required', 
                'v_postal_code' => 'required', 
                /* 'v_lat' => 'required', 
                'v_lon' => 'required', */ 
            ]);

            if ($validator->fails()) {
                return json_encode($validator->errors());
            } else {
                $record->v_firstname = trim($inputs['v_firstname']);
                $record->v_lastname = trim($inputs['v_lastname']);
                $record->v_email = trim($inputs['v_email']);
                $record->password = Hash::make($inputs['password']);
                $record->e_gender = trim($inputs['e_gender']);
                $record->d_dob = date(SAVE_DATE_FORMAT, strtotime($inputs['d_dob']));
                $record->v_phone = trim($inputs['v_phone']);
                $record->v_landline_number = trim($inputs['v_landline_number']);
                $record->e_status = trim($inputs['e_status']);
                $record->created_at = Carbon::now();
                if ($record->save()) {  
                    $customerAddress = new CustomerAddresses;
                    $customerAddress->i_customer_id = $record->id;
                    $customerAddress->v_address_label = "Primary Address";
                    $customerAddress->v_street = trim($inputs['v_street']);
                    $customerAddress->v_city = trim($inputs['v_city']);
                    $customerAddress->v_state = trim($inputs['v_state']);
                    $customerAddress->v_country = trim($inputs['v_country']);
                    $customerAddress->v_postal_code = trim($inputs['v_postal_code']);
                    $address = $inputs['v_street'].' '.$inputs['v_city'].' '.$inputs['v_state'].' '.$inputs['v_country'].' '.$inputs['v_postal_code'];
                    
                    $get_lat_lng = array();
                    if($address != '') {			
                        $get_lat_lng = $this->fetchLatLng($inputs, $address);	
                    }
                    if($get_lat_lng) {
                        $customerAddress->v_lat = $get_lat_lng['latitude'];
                        $customerAddress->v_lon = $get_lat_lng['longitude'];
                    } else {
                        $record->v_lat = 0;
                        $record->v_lon = 0;
                    }
                    if($customerAddress->save()) {
                        Session::flash('success-message', 'Customers record added successfully.');
                        return '';
                    }
                }
            }
        } else {
            return View('backend.customers.add', array('title' => 'Add Customer'));
        }
        return Redirect(ADMIN_URL . 'customers');
    }

    public function anyEdit(Request $request, $id) {

        $inputs  = $request->all();
        $record = Customers::find($id);
        $address_record = CustomerAddresses::where('i_customer_id', $id)->get()->toArray();
      
        $customer = [];
        
        if ($record || !empty($record)) {
            if ($inputs) {
                $validator = Validator::make($request->all(), [
                    'v_email' => 'required|unique:customers,v_email,' . $id . ',id,deleted_at,NULL',
                    'v_firstname' => 'required',
                    'v_lastname' => 'required',
                    'e_gender' => 'required',
                    'd_dob' => 'required',
                    'v_phone' => 'required',
                    'e_status' => 'required',
                ]);

                if ($validator->fails()) {
                    return json_encode($validator->errors());
                } else {
                $record->v_firstname = trim($inputs['v_firstname']);
                $record->v_lastname = trim($inputs['v_lastname']);
                $record->v_email = trim($inputs['v_email']);
                $record->password = Hash::make($inputs['password']);
                $record->e_gender = trim($inputs['e_gender']);
                $record->d_dob = date(SAVE_DATE_FORMAT, strtotime($inputs['d_dob']));
                $record->v_phone = trim($inputs['v_phone']);
                $record->v_landline_number = trim($inputs['v_landline_number']);
                $record->e_status = trim($inputs['e_status']);
                $record->created_at = Carbon::now();
                if ($record->save()) {
                    Session::flash('success-message', 'Customers record updated successfully.');
                    return '';
                }
                }
            } else {
                
                $service_area = GeoPoint::with(['GeoCities' => function($q) {
                    $q->select('id', 'v_city', 'v_county');
                }])->select('id', 'v_street1', 'v_postal_code', 'i_city_id')->orderBy('v_street1')->get()->toArray();
               
                $reservation_category = SystemResCategory::select('id', 'v_label')->orderBy('v_label')->get()->toArray();
               /*  pr($reservation_category);exit; */
                $reservation_data = Reservations::where('i_customer_id', $id)->get()->toArray();
               
                return View('backend.customers.edit', array('record' => $record, 'title' => 'Edit Customers', 'address_record' => $address_record, 'service_area' => $service_area, 'reservation_category' => $reservation_category,'reservation_data' => $reservation_data,'customer'=>$customer));
            }
        }
        return Redirect(ADMIN_URL . 'customers');
    }

    public function getDelete($id) {
        $record = Customers::find($id);
        if (!empty($record)) {
            if ($record->delete()) {
                CustomerAddresses::where('i_customer_id',$record->id)->delete();
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
            if ($data['action'] == 'Active') {
                if (Customers::whereIn('id', $data['ids'])->update(array('e_status' => ACTIVE_STATUS))) {
                    return 'TRUE';
                } else {
                    return 'FALSE';
                }
            } else if ($data['action'] == 'Inactive') {
                if (Customers::whereIn('id', $data['ids'])->update(array('e_status' => INACTIVE_STATUS))) {
                    return 'TRUE';
                } else {
                    return 'FALSE';
                }
            } else if ($data['action'] == 'Delete') {
                $user_data = Customers::whereIn('id', array_values($data['ids']))->get();
               
                if ($user_data) {
                    foreach ($user_data as $data) {

                        $data->deleted_at = Carbon::now();
                        $data->save();
                        $data->delete();
                        CustomerAddresses::where('i_customer_id',$data->id)->delete();
                    }
                    return 'TRUE';
                } else {
                    return 'FALSE';
                }
            }
        }
    }

    /* public function anySaveCustAddress(Request $request) {
        $data = $request->all();
        
        $address = $data['v_street'].' '.$data['v_city'].' '.$data['v_state'].' '.$data['v_country'].' '.$data['v_postal_code'];
        $get_lat_lng = array();
        $get_lat_lng = $this->fetchLatLng($data, $address);	
        CustomerAddresses::where('id',$data['id'])->update(['v_address_label' => $data['v_address_label'], 
                                                            'v_street' => $data['v_street'], 
                                                            'v_city' => $data['v_city'], 
                                                            'v_state' => $data['v_state'], 
                                                            'v_country' => $data['v_country'], 
                                                            'v_postal_code' => $data['v_postal_code'],
                                                            'v_lat' => $get_lat_lng['latitude'],
                                                            'v_lon' => $get_lat_lng['longitude'],
                                                            ]);
        return 'TRUE';
    }
    
    public function anyAddCustAddress(Request $request) {
        $inputs = $request->all();
        $customerAddress = new CustomerAddresses;
     
        if ($inputs) {
            // pr($inputs); exit; 
            $validator = Validator::make($inputs, [
                'v_address_label' => 'required', 
                'v_street' => 'required', 
                'v_city' => 'required', 
                'v_state' => 'required', 
                'v_country' => 'required', 
                'v_postal_code' => 'required', 
            ]);

            if ($validator->fails()) {
                return json_encode($validator->errors());
            } else {
                $customerAddress->i_customer_id =trim($inputs['current_customer_id']);
                $customerAddress->v_address_label = trim($inputs['v_address_label']);
                $customerAddress->v_street = trim($inputs['v_street']);
                $customerAddress->v_city = trim($inputs['v_city']);
                $customerAddress->v_state = trim($inputs['v_state']);
                $customerAddress->v_country = trim($inputs['v_country']);
                $customerAddress->v_postal_code = trim($inputs['v_postal_code']);
                $address = $inputs['v_street'].' '.$inputs['v_city'].' '.$inputs['v_state'].' '.$inputs['v_country'].' '.$inputs['v_postal_code'];
                    
                $get_lat_lng = array();
                if($address != '') {			
                    $get_lat_lng = $this->fetchLatLng($inputs, $address);	
                }
                if($get_lat_lng) {
                    $customerAddress->v_lat = $get_lat_lng['latitude'];
                    $customerAddress->v_lon = $get_lat_lng['longitude'];
                } else {
                    $record->v_lat = 0;
                    $record->v_lon = 0;
                }
                if ($customerAddress->save()) {  
                    Session::flash('success-message', 'Address added successfully.');
                    return '';
                }
            }
        } else {
            return View('backend.customers.edit', array('title' => 'Add Address'));
        }
        return Redirect(ADMIN_URL . 'customers-address/save');
    }

    public function anyDeleteCustAddress($id) {
        $record = CustomerAddresses::find($id);
        if (!empty($record)) {
            if ($record->delete()) {
                return 'TRUE';
            } else {
                return 'FALSE';
            }
        } else {
            return 'FALSE';
        }
    } */

    public function anyListAjaxCustomerReservation(Request $request, $id) {
        // pr("here listing.."); exit;
        $data = $request->all();
        
        $sortColumn = array('','v_reservation_number', 'i_customer_id', 'i_reservation_category_id', 'i_origin_point_id', 'i_destination_point_id', 'e_class_type', 'e_shuttle_type', 'd_travel_date', 'i_total_num_passengers', 'e_reservation_status');
        $query = Reservations::with(['GeoOriginServiceArea', 'GeoDestServiceArea', 'Admin', 
        'Customers' => function($q) {
                $q->select('customers.id', 'customers.v_firstname', 'customers.v_lastname');
        }, 'SystemResCategory', 'GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities','PickupCity','DropOffCity'])
        ->select('reservations.*');

        $query = $query->where('i_customer_id',$id);
        

        if (isset($data['v_reservation_number']) && $data['v_reservation_number'] != '') {
            $query = $query->where('v_reservation_number', 'LIKE', '%' . $data['v_reservation_number'] . '%');
        }
        if (isset($data['i_customer_id']) && $data['i_customer_id'] != '') {
            /* $query = $query->WhereHas('Customers', function($q) use($data){
                $q->where('customers.id', '=', $data['i_customer_id']);
            }); */
            $query = $query->WhereHas('Customers', function($q) use($data){
                $q->where(function($q1) use($data) {
                    $q1->where('customers.v_firstname', 'LIKE', '%' . trim($data['i_customer_id']) . '%')
                    ->orWhere('customers.v_lastname', 'LIKE', '%' . trim($data['i_customer_id']) . '%')
                    ->orWhere(DB::raw("CONCAT(customers.v_firstname, ' ',customers.v_lastname)"), 'LIKE', '%' . trim($data['i_customer_id']) . '%');
                }); 
            }); 
        }
        if (isset($data['i_reservation_category_id']) && $data['i_reservation_category_id'] != '') {
            $query = $query->WhereHas('SystemResCategory', function($q) use($data){
                $q->where('geo_point_types.id', 'LIKE', '%' . $data['i_reservation_category_id'] . '%');
            });
        }
        if (isset($data['i_origin_point_id']) && $data['i_origin_point_id'] != '') {
            $query = $query->orWhereHas('PickupCity', function($qa) use($data){
                $qa->where(DB::raw("CONCAT(reservations.v_pickup_address, COALESCE(CONCAT(', ',geo_cities.v_city), ''), COALESCE(CONCAT(', ',geo_cities.v_county),''))"), 'LIKE', '%' . trim($data['i_origin_point_id']) . '%');
               /*  pr($qa);
                exit; */
          });
         
        }
        if (isset($data['i_destination_point_id']) && $data['i_destination_point_id'] != '') {
            $query = $query->orWhereHas('DropOffCity', function($qa) use($data){
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
        $query = $query->whereNotIn('id',function($q){
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
        if ($sort_order != '' && $order_field != '') {
            if($order_field == 'i_origin_point_id') {
                $query = $query->join('geo_point','geo_point.id','=','i_origin_point_id')->orderBy('geo_point.v_street1',$sort_order);
            } else  if($order_field == 'i_destination_point_id') {
                $query = $query->join('geo_point','geo_point.id','=','i_destination_point_id')->orderBy('geo_point.v_street1',$sort_order);
            } else if($data['order']['0']['column'] == 0) {
                $query = $query->orderBy('reservations.updated_at', 'desc');
            } else {
                $query = $query->orderBy($order_field, $sort_order);
            }
        } else {
            $query = $query->orderBy('updated_at', 'desc');
        }
        $users = $query->paginate($rec_per_page);
        
        $arrUsers = $users->toArray();
        $data = array();
        // pr($arrUsers['data']); exit;
        foreach ($arrUsers['data'] as $key => $val) {
            $index = 0;
            if(isset($this->permission) && isset($this->permission[20]['i_delete']) && $this->permission[20]['i_delete'] == 1) {
                $data[$key][$index++] = '<label class="kt-checkbox kt-checkbox--single kt-checkbox--solid "><input type="checkbox" name="id[]" value="' . $val['id'] . '" class="kt-checkable"><span></span></label>';
            }
            $data[$key][$index++] = $val['v_reservation_number'];
            $data[$key][$index++] = $val['system_res_category']['v_label'];
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
            $data[$key][$index++] = date(DATE_FORMAT,strtotime($val['d_travel_date']));
            // $data[$key][$index++] = ($val['d_return_date']) ? date(DATE_FORMAT,strtotime($val['d_return_date'])) : '';
            $data[$key][$index++] = $val['i_total_num_passengers'];
            $data[$key][$index++] = $val['e_reservation_status'];

            $action = '';
            $action .= '<div class="d-flex">';
            /* if(isset($this->permission) && isset($this->permission[20]['i_add_edit']) && $this->permission[20]['i_add_edit'] == 1) {
                $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . ADMIN_URL . 'reservations/edit/'. $val['id'] .'/customer/'. $id . '" title="edit"><i class="la la-edit"></i> </a>';
            } */
            if(isset($this->permission) &&  isset($this->permission[20]['i_list']) && $this->permission[20]['i_list'] == 1) {
                $action .= '<a title="View" id="view_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg view_record" rel="' . $val['id'] . '" href="' . ADMIN_URL . 'reservations/view/' . $val['id'] .'/customer/'. $id . '"><i class="la la-eye"></i></a>';
            }
            
            if(isset($this->permission) &&  isset($this->permission[20]['i_delete']) && $this->permission[20]['i_delete'] == 1) {
                $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' . ADMIN_URL . 'customers-reservation/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';
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
    
    public function anyAddCustomerReservation(Request $request, $cust_id) {
        $inputs = $request->all();
        // pr($inputs); exit;
        $auth_user = auth()->guard('admin')->user()->toArray();
       
        if ($inputs) {
            $record = new Reservations;
            $validator = Validator::make($inputs, [
                'i_origin_point_id' => 'required',               
                'd_depart_date' => 'required',   
                'e_class_type' => 'required',               
                'v_contact_phone_number' => 'required',                
                't_best_time_tocall' => 'required',               
                'i_reservation_category_id' => 'required', 
                // 'i_reservation_category_id' => 'required',  
                // 'e_travel_alone' => 'required',
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
                $record->i_customer_id = $cust_id;
                $record->v_contact_name = $inputs['reservation_customer_name'];
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
                        $round_trip_record->i_reservation_category_id = trim($inputs['i_reservation_category_id']);
                        $round_trip_record->i_customer_id = $cust_id;
                        $round_trip_record->v_contact_name = $inputs['reservation_customer_name'];
                        $round_trip_record->i_admin_id = $auth_user['id'];
                        $round_trip_record->i_origin_point_id = trim($inputs['i_origin_point_id']); 
                        $round_trip_record->i_destination_point_id = trim($inputs['i_destination_point_id']);
                        $round_trip_record->d_travel_date = date('Y-m-d', strtotime(trim($inputs['d_return_date'])));	
                        // $round_trip_record->d_return_date = date('Y-m-d', strtotime(trim($inputs['d_return_date'])));
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
                    // pr($fare_calc); exit;
                    $total_passenger = $record->i_total_num_passengers;
                    
                    if($inputs['e_class_type'] == 'RT') { 
                        $fare_calc_rt = FareTable::where('i_origin_service_area_id', $location_info_origin['i_service_area_id'])->where('i_dest_service_area_id', $location_info_destination['i_service_area_id'])->select('id', 'i_rate_class_id', 'v_rate_code', 'd_fare_amount')->get()->toArray();
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
                            $travel_info->e_type = $passenger_type_rt[$i-1] ;

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
                        
                    }
                    $total_fare_amount = 0;
                    $total_fare_amount = ReservationTravellerInfo::where('i_reservation_id',$record->id)->select('id','d_fare_amount')->sum('d_fare_amount');
                    
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

                    // store final amount of reservation
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
            $reservation_category = SystemResCategory::select('id', 'v_title')->orderBy('v_title')->get()->toArray();
            $customer_data = Customers::where('id', $cust_id)->where('e_status', 'Active')->select('id', 'v_firstname', 'v_lastname')->orderBy('v_firstname')->first();
            $customer_id = $cust_id;
            $animals_list = SystemLuggageDef::where('e_type', 'Animal')->select('id', 'v_name', 'e_type', 'd_unit_price')->orderBy('v_name')->get()->toArray();	
            $luggages_list = SystemLuggageDef::where('e_type', 'Luggage')->select('id', 'v_name', 'e_type', 'd_unit_price')->orderBy('v_name')->get()->toArray();
            $cust_reservation = 'cust_reservation';
            return View('backend.reservations.add', array('title' => 'Add Reservations', 'service_area' => $service_area, 'reservation_category' => $reservation_category, 'admin_data' => $admin_data, 'customer_id' => $customer_id, 'animals_list' => $animals_list, 'luggages_list' => $luggages_list, 'cust_reservation' => $cust_reservation, 'customer_data' => $customer_data));
        }
        return Redirect(ADMIN_URL . 'customers/edit/'.$cust_id);
    }

    public function anyEditCustomerReservation(Request $request, $id) {
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
            //pr($inputs); exit;
            $validator = Validator::make($inputs, [
                // 'i_customer_id' => 'required',               
                'i_origin_point_id' => 'required',               
                'd_depart_date' => 'required',   
                // 'e_class_type' => 'required',               
                'v_contact_phone_number' => 'required',                
                't_best_time_tocall' => 'required',               
                'i_reservation_category_id' => 'required', 
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
                $record->i_customer_id = $record->i_customer_id;
                $record->v_contact_name = $inputs['reservation_customer_name'];
                $record->d_travel_date  = date('Y-m-d', strtotime(trim($inputs['d_depart_date'])));
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
                        $round_trip_record->i_customer_id = $record->i_customer_id;
                        $round_trip_record->i_admin_id = $auth_user['id'];
                        $round_trip_record->i_origin_point_id = trim($inputs['i_origin_point_id']); 
                        $round_trip_record->i_destination_point_id = trim($inputs['i_destination_point_id']);
                        $round_trip_record->d_travel_date = date('Y-m-d', strtotime(trim($inputs['d_depart_date'])));	
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
                    // pr($fare_calc); exit;
                    
                    $total_passenger = $record->i_total_num_passengers;
                    $passenger_id = explode(",",$inputs['passanger_reservation_id'][0]);                    
                    $old_travel_info = ReservationTravellerInfo::where('i_reservation_id', $record->id)->delete();
                    $passenger_name = explode(",",$inputs['v_traveller_name'][0]);
                    $passenger_dob = explode(",",$inputs['d_birth_month_year'][0]);
                    $passenger_type = explode(",",$inputs['e_type'][0]);
                    
                    // ONEWAY RESERVATION
                    for($i=1; $i<=$total_passenger; $i++) {
                        /* $j=$i;
                        foreach($passenger_id as $val) {
                            
                            if($val == '') { */
                            $travel_info = new ReservationTravellerInfo;
                            /* } else {
                                $travel_info = ReservationTravellerInfo::find($val);
                            } */
                            $travel_info->i_reservation_id = $record->id;
                            $travel_info->v_traveller_name = $passenger_name[$i-1];
                            $travel_info->d_birth_month_year = date('Y-m-d', strtotime(trim($passenger_dob[$i-1])));
                            $travel_info->e_type = $passenger_type[$i-1] ;
                            $passenger_type = ($passenger_type[$i-1] == 'Adult') ? 'Full Fare' : $passenger_type[$i-1];
                            $fare_class = FareClass::where('e_class_type', $inputs['e_class_type'])->where('v_class_label', $passenger_type)->select('id', 'v_rate_code')->first();
                            
                            foreach ($fare_calc as $key => $value) {
                                if($fare_class['v_rate_code'] == $value['v_rate_code']) {
                                    $fare_rate = $value['d_fare_amount'];
                                    $fare_rate_code = $value['v_rate_code'];
                                }
                            }
                            $travel_info->d_fare_amount = $fare_rate;
                            $travel_info->v_rate_code = $fare_rate_code;
                            $travel_info->save(); 
                            /* $j++;
                        } */
                    } 
                    $total_fare_amount = 0;
                    $total_fare_amount = ReservationTravellerInfo::where('i_reservation_id',$record->id)->select('id','d_fare_amount')->sum('d_fare_amount');
                    
                    // RETURN RESERVATION
                    if($inputs['e_class_type'] == 'RT') {

                        $fare_calc_rt = FareTable::where('i_origin_service_area_id', $location_info_origin['i_service_area_id'])->where('i_dest_service_area_id', $location_info_destination['i_service_area_id'])->select('id', 'i_rate_class_id', 'v_rate_code', 'd_fare_amount')->get()->toArray();

                        $total_passenger_rt = $round_trip_record->i_total_num_passengers;
                        $passenger_id_rt = explode(",",$inputs['passanger_reservation_id_rt'][0]);
                        $old_travel_info = ReservationTravellerInfo::where('i_reservation_id', $round_trip_record->id)->delete();
                        $passenger_name_rt = explode(",",$inputs['v_traveller_name_rt'][0]);
                        $passenger_dob_rt = explode(",",$inputs['d_birth_month_year_rt'][0]);
                        $passenger_type_rt = explode(",",$inputs['e_type_rt'][0]);
                        for($i=1; $i<=$total_passenger_rt; $i++) {

                            /* $j=$i;
                            foreach($passenger_id_rt as $val) {
                                if($val == '') { */
                                    $travel_info = new ReservationTravellerInfo;
                                /* } else {
                                    $travel_info = ReservationTravellerInfo::find($val);
                                } */
                                $travel_info->i_reservation_id = $round_trip_record->id;
                                $travel_info->v_traveller_name = $passenger_name_rt[$i-1];
                                $travel_info->d_birth_month_year = date('Y-m-d', strtotime(trim($passenger_dob_rt[$i-1])));
                                // $travel_info->e_is_travel_alone = $round_trip_record->e_travel_alone;
                                $travel_info->e_type = $passenger_type_rt[$i-1] ;
                                $fare_type = ($passenger_type_rt[$i-1] == 'Adult') ? 'Full Fare' : $passenger_type_rt[$i-1];
                                $fare_class = FareClass::where('e_class_type', $inputs['e_class_type'])->where('v_class_label', $fare_type)->select('id', 'v_rate_code')->first()->toArray();
                                foreach ($fare_calc_rt as $key => $value_rt) {
                                    if($fare_class['v_rate_code'] == $value_rt['v_rate_code']) {
                                        $fare_rate = $value_rt['d_fare_amount'];
                                        $fare_rate_code = $value_rt['v_rate_code'];
                                    }
                                }
                                $travel_info->d_fare_amount = $fare_rate;
                                $travel_info->v_rate_code = $fare_rate_code;
                                $travel_info->save(); 
                               /*  $j++;
                            } */
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

                        //Luggage record delete for OneWay
                        if(isset($inputs['luggage_data_id_'.$i]) &&  isset($inputs['luggage_numbers_'.$i]) && $inputs['luggage_numbers_'.$i] == 0) {
                            $old_passanger_info = ReservationLuggageInfo::where('id', $inputs['luggage_data_id_'.$i])->where('i_reservation_id',$record->id)->delete();
                        }
                        if(isset($inputs['luggage_data_id_'.$i]) && isset($inputs['total_fare_amt_pet_'.$i]) && $inputs['total_fare_amt_pet_'.$i] == 0) {
                            $old_passanger_info = ReservationLuggageInfo::where('id', $inputs['luggage_data_id_'.$i])->where('i_reservation_id',$record->id)->delete();
                        }

                        //Luggage record delete for Return Trip
                        if(isset($inputs['luggage_data_id_'.$i.'_rt']) &&  isset($inputs['luggage_numbers_'.$i.'_rt']) && $inputs['luggage_numbers_'.$i.'_rt'] == 0) {
                            $old_passanger_info = ReservationLuggageInfo::where('id', $inputs['luggage_data_id_'.$i.'_rt'])->where('i_reservation_id',$record_rt->id)->delete();
                        }
                        if(isset($inputs['luggage_data_id_'.$i.'_rt']) && isset($inputs['total_fare_amt_pet_'.$i.'_rt']) && $inputs['total_fare_amt_pet_'.$i.'_rt'] == 0) {
                            $old_passanger_info = ReservationLuggageInfo::where('id', $inputs['luggage_data_id_'.$i.'_rt'])->where('i_reservation_id',$record_rt->id)->delete();
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
                   
                    Session::flash('success-message', 'Reservations edited successfully.');
                    return 'Success_add';
                }
            }
        } else {
            $admin_data = Admin::where('e_status', 'Active')->select('id', 'v_firstname', 'v_lastname')->orderBy('v_firstname')->get()->toArray();
            $service_area = GeoPoint::with(['GeoCities' => function($q) {
                $q->where('e_status', 'Active')->select('id', 'v_city', 'v_county');
            }])->select('id', 'v_street1', 'v_postal_code', 'i_city_id')->orderBy('v_street1')->get()->toArray();
            $reservation_category = SystemResCategory::select('id', 'v_title')->orderBy('v_title')->get()->toArray();
            $customer_id = $reservation_record->i_customer_id;
            // pr($record); exit;
            $customer_data = Customers::where('id', $reservation_record->i_customer_id)->where('e_status', 'Active')->select('id', 'v_firstname', 'v_lastname')->orderBy('v_firstname')->first();
           
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
            // $reservation_information = ReservationInfo::where('i_reservation_id', $record->id)->first();
           
            /* if($reservation_record->e_class_type == 'RT') {
                $reservation_information_rt = ReservationInfo::where('i_reservation_id', $record_rt->id)->first();
            } else {
                $reservation_information_rt = array();
            }  */

            $cust_reservation = 'cust_reservation';

            return View('backend.reservations.edit', array('title' => 'Edit Reservations', 'service_area' => $service_area, 'reservation_category' => $reservation_category, 'admin_data' => $admin_data, 'customer_data' => $customer_data, 'record' => $record, 'reservation_data' => $reservation_data, 'animals_list' => $animals_list, 'luggages_list' => $luggages_list, 'reservation_luggage_info' => $reservation_luggage_info, 'system_icao_def' => $system_icao_def,/*  'reservation_information' => $reservation_information, */ 'record_rt' => $record_rt, 'reservation_luggage_info_rt' => $reservation_luggage_info_rt, 'reservation_data_rt' => $reservation_data_rt, 'cust_reservation' => $cust_reservation, 'customer_id' => $customer_id, /* 'reservation_information_rt' => $reservation_information_rt */));
        }
        return Redirect(ADMIN_URL . 'customers/edit/'.$record['i_customer_id']);
    }

    public function anyDeleteCustomerReservation($id) {
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

    public function anyBulkActionCustomerReservation(Request $request) {
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

   
    public function anyViewCustomerReservation(Request $request,$id) {
        
        $inputs = $request->all();
        $reservation_rec1 = $id;
       
   
        $reservation_record = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory'])->where(['id' => $reservation_rec1])->first();
        $reservation_rec2 = '';
        if($reservation_record['e_class_type'] == 'RT') {
            $record_rt = Reservations::where('i_parent_id',$reservation_rec1)->first();
            $reservation_rec2 = $record_rt['id'];

        }
        if(!empty($reservation_record)) { 
            $payment_info = Transactions ::where('i_reservation_id',$id)->get()->toArray();           
       
            $reservation_luggage_info = ReservationLuggageInfo::with(['SystemLuggageDef'])->where('i_reservation_id', $reservation_rec1)->get()->toArray();
            $reservation_pet_info = ReservationLuggageInfo::with(['SystemAnimalDef'])->where('i_reservation_id', $reservation_rec1)->get()->toArray();
            $reservation_luggage_info_total = ReservationLuggageInfo ::where('i_reservation_id', $reservation_rec1)->get()->sum('d_price');
                
            $total_fare_amount = ReservationTravellerInfo::where('i_reservation_id', $reservation_rec1)->select(DB::raw('SUM(d_fare_amount) AS total'), DB::raw('SUM(IF(e_type = "Adult", d_fare_amount, 0)) AS adult_total'), DB::raw('SUM(IF(e_type = "Child", d_fare_amount, 0)) AS child_total'),DB::raw('SUM(IF(e_type = "Senior", d_fare_amount, 0)) AS senior_total'),DB::raw('SUM(IF(e_type = "Military", d_fare_amount, 0)) AS military_total'),DB::raw('SUM(IF(e_type = "Infant", d_fare_amount, 0)) AS infant_total'), DB::raw('SUM(IF(e_type = "Adult", 1, 0)) AS adult_count'), DB::raw('SUM(IF(e_type = "Child", 1, 0)) AS child_count'), DB::raw('SUM(IF(e_type = "Senior", 1, 0)) AS senior_count'), DB::raw('SUM(IF(e_type = "Military", 1, 0)) AS military_count'), DB::raw('SUM(IF(e_type = "Infant", 1, 0)) AS infant_count'))->first()->toArray();

            $reservation_luggage_info_total_rt = "";
            $reservation_record_rt = $reservation_luggage_info_rt = $reservation_pet_info_rt = $total_fare_amount_rt = array();
            if($reservation_record['e_class_type'] == 'RT') {

                $reservation_record_rt = Reservations::with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory'])->where(['id'=>$reservation_rec2])->first();
                
                
                $reservation_luggage_info_rt = ReservationLuggageInfo::with(['SystemLuggageDef'])->where('i_reservation_id', $reservation_rec2)->get()->toArray();
                $reservation_pet_info_rt = ReservationLuggageInfo::with(['SystemAnimalDef'])->where('i_reservation_id', $reservation_rec2)->get()->toArray();
                $total_fare_amount_rt = ReservationTravellerInfo::where('i_reservation_id', $reservation_rec2)->select(DB::raw('SUM(d_fare_amount) AS total'), DB::raw('SUM(IF(e_type = "Adult", d_fare_amount, 0)) AS adult_total'), DB::raw('SUM(IF(e_type = "Child", d_fare_amount, 0)) AS child_total'),DB::raw('SUM(IF(e_type = "Senior", d_fare_amount, 0)) AS senior_total'),DB::raw('SUM(IF(e_type = "Military", d_fare_amount, 0)) AS military_total'),DB::raw('SUM(IF(e_type = "Infant", d_fare_amount, 0)) AS infant_total'), DB::raw('SUM(IF(e_type = "Adult", 1, 0)) AS adult_count'), DB::raw('SUM(IF(e_type = "Child", 1, 0)) AS child_count'), DB::raw('SUM(IF(e_type = "Senior", 1, 0)) AS senior_count'), DB::raw('SUM(IF(e_type = "Military", 1, 0)) AS military_count'), DB::raw('SUM(IF(e_type = "Infant", 1, 0)) AS infant_count'))->first()->toArray();
                $reservation_luggage_info_total_rt = ReservationLuggageInfo ::where('i_reservation_id', $reservation_rec2)->get()->sum('d_price');
            }
        
           
            $cust_reservation = 'cust_reservation';
            return view('backend.reservations.view', array('title' => 'Rservation Summary','reservation_record' => $reservation_record,'reservation_record_rt' => $reservation_record_rt,'reservation_luggage_info'=>$reservation_luggage_info,'reservation_pet_info'=>$reservation_pet_info,'reservation_luggage_info_rt'=>$reservation_luggage_info_rt,'reservation_pet_info_rt'=>$reservation_pet_info_rt,'total_fare_amount'=>$total_fare_amount,'total_fare_amount_rt'=>$total_fare_amount_rt,'reservation_luggage_info_total'=>$reservation_luggage_info_total,'reservation_luggage_info_total_rt'=>$reservation_luggage_info_total_rt,'id'=>$id,'cust_reservation'=>$cust_reservation,'payment_info'=>$payment_info));
            
            
        } else {
            return redirect(ADMIN_URL);
        }
        
       
    }
    
    
}

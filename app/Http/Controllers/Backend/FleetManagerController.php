<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\FleetManager;
use App\Models\FleetVehicleSpecification;
use App\Models\SystemSettings;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Image;
use Mail, Session, Redirect, Validator, DB, Hash;
use Illuminate\Support\Str;

class FleetManagerController extends BaseController {

    public function getIndex() {

        return View('backend.fleet_vehicle.index', array('title' => "Fleet"));
    }

    public function anyListAjax(Request $request) {
        $data = $request->all();

        $sortColumn = array('','e_vehicle_status','v_vehicle_code','v_model_year','','i_total_customer_booking_seats','i_optimal_booking_seats');


        $query = FleetManager::join('fleet_vehicle_spec', function ($join) {
            $join->on('fleet_vehicle_spec.i_vehicle_id', '=', 'fleet_vehicle.id');
        })->select(['fleet_vehicle.*','fleet_vehicle_spec.i_vehicle_id','fleet_vehicle_spec.i_vehicle_id','fleet_vehicle_spec.v_make as make','fleet_vehicle_spec.v_model as model','fleet_vehicle_spec.v_series as series']);


        if (isset($data['e_vehicle_status']) && $data['e_vehicle_status'] != '') {
            $query = $query->where('e_vehicle_status', 'LIKE', '%' . $data['e_vehicle_status'] . '%');
        }
        if (isset($data['v_vehicle_code']) && $data['v_vehicle_code'] != '') {
            $query = $query->where('v_vehicle_code', 'LIKE', '%' . $data['v_vehicle_code'] . '%');
        }
        if (isset($data['v_model_year']) && $data['v_model_year'] != '') {
            $query = $query->where('v_model_year', 'LIKE', '%' . $data['v_model_year'] . '%');
        }
        if (isset($data['v_description']) && $data['v_description'] != '') {
            $query = $query->where(DB::raw("CONCAT(fleet_vehicle_spec.v_make, ' ',fleet_vehicle_spec.v_model, ' ',fleet_vehicle_spec.v_series)"), 'LIKE', '%' . trim($data['v_description']) . '%');
        }
        if (isset($data['i_total_customer_booking_seats']) && $data['i_total_customer_booking_seats'] != '') {
            $query = $query->where('i_total_customer_booking_seats',$data['i_total_customer_booking_seats']);
        }
        if (isset($data['i_optimal_booking_seats']) && $data['i_optimal_booking_seats'] != '') {
            $query = $query->where('i_optimal_booking_seats',$data['i_optimal_booking_seats']);
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
        ;
        $arrUsers = $users->toArray();

        $data = array();
        foreach ($arrUsers['data'] as $key => $val) {
            $index = 0;
            if(isset($this->permission) && isset($this->permission[4]['i_delete']) && $this->permission[4]['i_delete'] == 1) {
                $data[$key][$index++] = '<label class="kt-checkbox kt-checkbox--single kt-checkbox--solid"><input type="checkbox" name="id[]" value="' . $val['id'] . '" class="kt-checkable"><span></span></label>';
            }
            $data[$key][$index++] = $val['e_vehicle_status'];
            $data[$key][$index++] = $val['v_vehicle_code'];
            $data[$key][$index++] = $val['v_model_year'];
            $data[$key][$index++] = $val['make'].' '.$val['model'].' '.$val['series'];
            $data[$key][$index++] = $val['i_total_customer_booking_seats'];
            $data[$key][$index++] = $val['i_optimal_booking_seats'];
            
            $action = '';
            $action .= '<div class="d-flex">';
            if(isset($this->permission) &&  isset($this->permission[4]['i_add_edit']) && $this->permission[4]['i_add_edit'] == 1) {
                $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . ADMIN_URL . 'fleet-vehicles/edit/' . $val['id'] . '"
                title="edit"><i class="la la-edit"></i> </a>';
            }

            if(isset($this->permission) && isset($this->permission[4]['i_delete']) && $this->permission[4]['i_delete'] == 1) {
                $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' . ADMIN_URL . 'fleet-vehicles/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';
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
        $license_state= $this->arrStates();
        $us_dot_number =  SystemSettings ::select('us_dot_number')->first();
       
        $records ='';

        if ($inputs) {

            $record = new FleetManager;
            $validator = Validator::make($inputs, [

                'e_vehicle_status' => 'required',
                'v_model' => 'required',
                'v_series' => 'required',
                'v_make' => 'required',
                'v_vehicle_code' => 'required',
                'v_vin' => 'required',
                'v_model_year' => 'required',
                'i_total_customer_booking_seats' => 'required',
                'v_lic_plate' => 'required',
                'c_lic_state' => 'required',
                'd_aq_cost' => 'required',
                'd_aq_date' => 'required',
                'd_inservice_date' => 'required',
            ]);

            if ($validator->fails()) {
                return json_encode($validator->errors());
            } else {

                $record->e_vehicle_status = trim($inputs['e_vehicle_status']);
                $record->v_vehicle_code = trim($inputs['v_vehicle_code']);
                $record->v_vin = trim($inputs['v_vin']);
                $record->v_usdot_number = trim($inputs['v_usdot_number']);
                $record->v_model_year = trim($inputs['v_model_year']);
                $record->i_total_customer_booking_seats = trim($inputs['i_total_customer_booking_seats']);
                $record->i_optimal_booking_seats = trim($inputs['i_optimal_booking_seats']);
                $record->i_dispatcher_booking_seats = ($inputs['i_total_customer_booking_seats']) - ($inputs['i_optimal_booking_seats']);
                $record->v_lic_plate = trim($inputs['v_lic_plate']);
                $record->c_lic_state = trim($inputs['c_lic_state']);
                $record->d_tag_exp_date = $inputs['d_tag_exp_date'] ? date(SAVE_DATE_FORMAT, strtotime($inputs['d_tag_exp_date'])) : null;
                $record->d_aq_cost = trim($inputs['d_aq_cost']);
                $record->d_aq_date = date(SAVE_DATE_FORMAT, strtotime($inputs['d_aq_date']));
                $record->d_inservice_date = date(SAVE_DATE_FORMAT, strtotime($inputs['d_inservice_date']));
                $record->d_end_service_date = $inputs['d_end_service_date'] ? date(SAVE_DATE_FORMAT, strtotime($inputs['d_end_service_date'])) : null;
                
                if (isset($inputs['imgbase64']) && $inputs['imgbase64'] != '' && $inputs['is_edit_img_flag'] == '1') {
                    $profileImgPath = VEHICLES_PROFILE_IMG_PATH;
                    // $profileImgThumbPath = VEHICLES_PROFILE_THUMB_IMG_PATH;
                    
                    /* $imageName = $this->cropImages($inputs['imgbase64'], $inputs['x'], $inputs['y'], $inputs['h'], $inputs['w'], $profileImgPath, $profileImgThumbPath); */
                    $imageName = $this->saveImage($inputs['imgbase64'], $profileImgPath);
                    $record->v_image = $imageName;

                    $getimagesize = getimagesize($inputs['imgbase64']);
                    $imgWidth = 300;
                    $imgHeight = 300;
                    if((isset($getimagesize[0]) && $getimagesize[0] > 300) || (isset($getimagesize[1]) && $getimagesize[1] > 300)) {
                        $png_url = time() . '-' . str::random(6) . '.png';
                        $path = VEHICLES_PROFILE_THUMB_IMG_PATH.$png_url;
                        $base64img = $inputs['imgbase64'];
                        if (strpos($base64img, 'data:image/jpeg;base64,') !== false) {
                            $base64img = str_replace('data:image/jpeg;base64,', '', $base64img);
                        }
                        if (strpos($base64img, 'data:image/png;base64,') !== false) {
                            $base64img = str_replace('data:image/png;base64,', '', $base64img);
                        }
                        if (strpos($base64img, 'data:image/webp;base64,') !== false) {
                            $base64img = str_replace('data:image/webp;base64,', '', $base64img);
                        }
                
                        if (strpos($base64img, 'data:image/jpg;base64,') !== false) {
                            $base64img = str_replace('data:image/jpg;base64,', '', $base64img);
                        }
                        if (strpos($base64img, 'data:image/gif;base64,') !== false) {
                            $base64img = str_replace('data:image/gif;base64,', '', $base64img);
                        }
                        $base=base64_decode($base64img);
                        Image::make($base)->resize($imgWidth, $imgHeight)->save($path);
                    }
                }
                $record->created_at = Carbon::now();
                if ($record->save()) {

                    if($inputs['v_make']){
                        $vehicle_specification = new FleetVehicleSpecification;
                        $vehicle_specification->v_make = trim($inputs['v_make']);
                        $vehicle_specification->v_model = trim($inputs['v_model']);
                        $vehicle_specification->v_series = trim($inputs['v_series']);
                        $vehicle_specification->i_vehicle_id = $record->id;
                        $vehicle_specification->save();
                    }
                    Session::flash('success-message', 'Fleet Information added successfully.');
                    return '';
                }
            }
        } else {
            return View('backend.fleet_vehicle.add', array('title' => 'Add Fleet','license_state' => $license_state,'records'=>$records,'us_dot_number'=>$us_dot_number));
        }
        return Redirect(ADMIN_URL . 'fleet-vehicles');
    }

    public function anyEdit(Request $request, $id) {

        $inputs  = $request->all();
        $license_state= $this->arrStates();
        $records = FleetManager::with(['get_vehicle_specification' => function($q) use($id) {
            $q->select('*');
        }])->where('id',$id)->first();
      
        if ($records || !empty($records)) {
            if ($inputs) {
                
                $validator = Validator::make($request->all(), [
                    'e_vehicle_status' => 'required',
                    'v_vehicle_code' => 'required',
                    'v_vin' => 'required',
                    'v_model_year' => 'required',
                    'i_total_customer_booking_seats' => 'required',
                    'v_lic_plate' => 'required',
                    'c_lic_state' => 'required',
                    'd_aq_cost' => 'required',
                    'd_aq_date' => 'required',
                    'd_inservice_date' => 'required',
                ]);
                if ($validator->fails()) {
                    return json_encode($validator->errors());
                } else {
                    if (isset($inputs['imgbase64']) && $inputs['imgbase64'] != '' && $inputs['is_edit_img_flag'] == '1') {
                        $profileImgPath = VEHICLES_PROFILE_IMG_PATH;
                        $profileImgThumbPath = VEHICLES_PROFILE_THUMB_IMG_PATH;
                       /*  $imageName = $this->cropImages($inputs['imgbase64'], $inputs['x'], $inputs['y'], $inputs['h'], $inputs['w'], $profileImgPath, $profileImgThumbPath); */

                        $imageName = $this->saveImage($inputs['imgbase64'], $profileImgPath);
                        
                        @unlink(VEHICLES_PROFILE_IMG_PATH.($records->v_image));
                        $records->v_image = $imageName;

                        /* if($records->v_image != '') {
                            if(file_exists($profileImgPath . $records->v_image)) {
                                @unlink($profileImgPath.($records->v_image));
                            }
                            if(file_exists($profileImgThumbPath . $records->v_image)) {
                                @unlink($profileImgThumbPath.($records->v_image));
                            }
                        } */
                        
                        $getimagesize = getimagesize($inputs['imgbase64']);
                        $imgWidth = 300;
                        $imgHeight = 300;
                        if((isset($getimagesize[0]) && $getimagesize[0] > 300) || (isset($getimagesize[1]) && $getimagesize[1] > 300)) {
                            $png_url = $imageName;
                            $path = VEHICLES_PROFILE_THUMB_IMG_PATH.$png_url;
                            $base64img = $inputs['imgbase64'];
                            if (strpos($base64img, 'data:image/jpeg;base64,') !== false) {
                                $base64img = str_replace('data:image/jpeg;base64,', '', $base64img);
                            }
                            if (strpos($base64img, 'data:image/png;base64,') !== false) {
                                $base64img = str_replace('data:image/png;base64,', '', $base64img);
                            }
                            if (strpos($base64img, 'data:image/webp;base64,') !== false) {
                                $base64img = str_replace('data:image/webp;base64,', '', $base64img);
                            }
                    
                            if (strpos($base64img, 'data:image/jpg;base64,') !== false) {
                                $base64img = str_replace('data:image/jpg;base64,', '', $base64img);
                            }
                            if (strpos($base64img, 'data:image/gif;base64,') !== false) {
                                $base64img = str_replace('data:image/gif;base64,', '', $base64img);
                            }
                            $base=base64_decode($base64img);
                            Image::make($base)->resize($imgWidth, $imgHeight)->save($path);
                        }
                    } else {
                        $records->v_image = '';
                    } 

                    $records->e_vehicle_status = trim($inputs['e_vehicle_status']);
                    $records->v_vehicle_code = trim($inputs['v_vehicle_code']);
                    $records->v_vin = trim($inputs['v_vin']);
                    $records->v_usdot_number = trim($inputs['v_usdot_number']);
                    $records->v_model_year = trim($inputs['v_model_year']);
                    $records->i_total_customer_booking_seats = trim($inputs['i_total_customer_booking_seats']);
                    $records->i_optimal_booking_seats = trim($inputs['i_optimal_booking_seats']);
                    $records->i_dispatcher_booking_seats = ($inputs['i_total_customer_booking_seats']) - ($inputs['i_optimal_booking_seats']);
                    $records->v_lic_plate = trim($inputs['v_lic_plate']);
                    $records->c_lic_state = trim($inputs['c_lic_state']);
                    $records->d_tag_exp_date = $inputs['d_tag_exp_date'] ?  date(SAVE_DATE_FORMAT, strtotime($inputs['d_tag_exp_date'])) : null ;
                    $records->d_aq_cost = trim($inputs['d_aq_cost']);
                    $records->d_aq_date = date(SAVE_DATE_FORMAT, strtotime($inputs['d_aq_date']));
                    $records->d_inservice_date = date(SAVE_DATE_FORMAT, strtotime($inputs['d_inservice_date']));
                    $records->d_end_service_date = $inputs['d_end_service_date'] ?  date(SAVE_DATE_FORMAT, strtotime($inputs['d_end_service_date'])) : null;
                    if ($records->save()) {
                        Session::flash('success-message', 'Fleet Information edited successfully.');
                        return '';
                    }
                }
            } else {
                return View('backend.fleet_vehicle.edit', array('records' => $records , 'title' => 'Edit Fleet','license_state' => $license_state));
            }
        }
        return Redirect(ADMIN_URL . 'fleet-vehicles');
    }
    public function anyEditSpecification (Request $request, $id) {
        $inputs  = $request->all();
        $license_state= $this->arrStates();
        $record = FleetVehicleSpecification::where('i_vehicle_id',$id)->first();
        if ($record || !empty($record)) {
            if ($inputs) {
                $validator = Validator::make($request->all(), [
                    'v_make' => 'required',
                    'v_model' => 'required',
                    'v_series' => 'required',
                    'v_vehicle_type' => 'required',
                    'v_body_class' => 'required',
                    'v_gvwr' => 'required',
                    'v_body_type' => 'required',
                    'i_engine_cylinders' => 'required',
                    'd_enginepower_kw' => 'required',
                    'v_engine_config' => 'required',
                    'v_engine_hp' => 'required',
                    'v_fuel_type' => 'required',
                    'd_fuel_capacity_gal' => 'required',
                    'd_displacement_cc' => 'required',
                    'd_displacement_ci' => 'required',
                    'd_displacement_l' => 'required',
                ]);
                if ($validator->fails()) {
                    return json_encode($validator->errors());
                } else {

                    $record->v_make = trim($inputs['v_make']);
                    $record->v_model = trim($inputs['v_model']);
                    $record->v_series = trim($inputs['v_series']);
                    $record->v_vehicle_type = trim($inputs['v_vehicle_type']);
                    $record->v_body_class = trim($inputs['v_body_class']);
                    $record->i_wheelbase_in = trim($inputs['i_wheelbase_in']);
                    $record->v_gvwr = trim($inputs['v_gvwr']);
                    $record->v_body_type = trim($inputs['v_body_type']);
                    $record->t_nhtsa_notes = trim($inputs['t_nhtsa_notes']);
                    $record->v_tyre_size_front = trim($inputs['v_tyre_size_front']);
                    $record->v_tyre_size_rear = trim($inputs['v_tyre_size_rear']);
                    $record->i_engine_cylinders = trim($inputs['i_engine_cylinders']);
                    $record->d_displacement_cc = trim($inputs['d_displacement_cc']);
                    $record->d_displacement_l = trim($inputs['d_displacement_l']);
                    $record->d_displacement_ci = trim($inputs['d_displacement_ci']);
                    $record->d_enginepower_kw = trim($inputs['d_enginepower_kw']);
                    $record->v_engine_config = trim($inputs['v_engine_config']);
                    $record->v_engine_hp = trim($inputs['v_engine_hp']);
                    $record->v_fuel_type = trim($inputs['v_fuel_type']);
                    $record->d_fuel_capacity_gal = trim($inputs['d_fuel_capacity_gal']);

                    if ($record->save()) {
                        Session::flash('success-message', 'Fleet Information edited successfully.');
                        return '';
                    }
                }
            } else {
                return View('backend.fleet_vehicle.edit', array('records' => $record , 'title' => 'Edit Fleet','license_state' => $license_state));
            }
        }

        return Redirect(ADMIN_URL . 'fleet-vehicles');


    }

    public function getDelete($id) {
        $record = FleetManager::find($id);
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
                $user_data = FleetManager::whereIn('id', array_values($data['ids']))->get();
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
}


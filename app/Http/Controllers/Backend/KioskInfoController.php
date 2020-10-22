<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Kiosk;
use App\Models\AdminRoles;
use App\Models\DriverExtension;
use App\Models\FleetManager;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail, Session, Redirect, Validator, DB, Hash;
use Illuminate\Validation\Rule;

class KioskInfoController extends BaseController {

    public function anyIndex(Request $request) {
        $driver_name = AdminRoles::with(['Admin' => function($q) {
            $q->select('id', 'i_role_id', 'v_firstname', 'v_lastname')->orderBy('v_firstname');
        }, 'Admin.DriverExtension'])->where('v_name', '=', 'Driver')->get()->toArray();
        
        $vehicle_name = FleetManager::where('e_vehicle_status', 'Ready')->select('id', 'v_vehicle_code')->orderBy('v_vehicle_code')->get()->toArray();
        return View('backend.kiosk.index', array('title' => "Kiosk Information", "driver_name" => $driver_name, "vehicle_name" => $vehicle_name));
    }

    public function anyListAjax(Request $request) {
        $data = $request->all();
        // pr(date("Y-m-d")); exit;
        $sortColumn = array('','v_location_code','', 'd_departure_time','v_notice', 'd_departure_time', 'i_driver_id');
        // pr($data['dStartDate']); exit;
        if(isset($data['dStartDate']) || isset($data['dEndDate'])) {
            $query = Kiosk::with(['DriverName' => function($q) {
                                $q->select('admin.id', 'admin.v_firstname', 'admin.v_lastname');
                            }, 'VehicleCode' => function($q1) {
                                $q1->select('fleet_vehicle.id', 'fleet_vehicle.v_vehicle_code');
                            },'DriverExtension' => function($q1) {
                                $q1->select('driver_extension.id', 'driver_extension.i_driver_id', 'driver_extension.v_extension');
                            }])->select('kiosk.*');
        } else {
            $query = Kiosk::with(['DriverName' => function($q) {
                $q->select('admin.id', 'admin.v_firstname', 'admin.v_lastname');
            }, 'VehicleCode' => function($q1) {
                $q1->select('fleet_vehicle.id', 'fleet_vehicle.v_vehicle_code');
            },'DriverExtension' => function($q1) {
                $q1->select('driver_extension.id', 'driver_extension.i_driver_id', 'driver_extension.v_extension');
            }])->where(DB::raw('DATE(d_departure_time)'), '=', date("Y-m-d"))->select('kiosk.*');
        }
        
        if (isset($data['v_location_code']) && $data['v_location_code'] != '') {
            $query = $query->where('v_location_code', 'LIKE', '%' . $data['v_location_code'] . '%');
        }
        
        if (isset($data['dStartDate']) && trim($data['dStartDate']) != '') {
            $query = $query->where(DB::raw('DATE(d_departure_time)'), '>=', trim(date('Y-m-d',strtotime($data['dStartDate']))));
        }
        if (isset($data['dEndDate']) && trim($data['dEndDate']) != '') {
            $query = $query->where(DB::raw('DATE(d_departure_time)'), '<=', trim(date('Y-m-d', strtotime($data['dEndDate']))));
        }
        
        if (isset($data['v_notice']) && $data['v_notice'] != '') {
            $query = $query->where('v_notice', 'LIKE', '%' . $data['v_notice'] . '%');
        }
        if (isset($data['d_departure_time']) && $data['d_departure_time'] != '') {
            $query = $query->where('d_departure_time', 'LIKE', '%' . date('H:i:s', strtotime($data['d_departure_time'])) . '%');
        }
        
        /* if (isset($data['van_id_input']) && $data['van_id_input'] != '') {
            $query = $query->where('v_van_id_input', 'LIKE', '%' . $data['van_id_input'] . '%');
        } */

        if (isset($data['v_van_id']) && $data['v_van_id'] != '' && $data['van_id_input'] == '') {
            $query = $query->WhereHas('VehicleCode', function($q) use($data){
                $q->where('fleet_vehicle.id', 'LIKE', '%' . $data['v_van_id'] . '%');
            });
        } elseif (isset($data['van_id_input']) && $data['van_id_input'] != '') {
            $query = $query->WhereHas('VehicleCode', function($q) use($data){
                $q->where('fleet_vehicle.id', 'LIKE', '%' . $data['v_van_id'] . '%');
            })->orWhere('v_van_id_input', 'LIKE', '%' . $data['van_id_input'] . '%');
        }

        if (isset($data['i_driver_id']) && $data['i_driver_id'] != '') {
            $query = $query->whereHas('DriverName', function($q) use($data){
               $q = $q->where('admin.id','=', $data['i_driver_id']);
            });
        }
        /* if (isset($data['v_extension']) && $data['v_extension'] != '') {
            $query = $query->whereHas('DriverExtension', function($q) use($data){
               $q = $q->where('driver_extension.v_extension', 'LIKE', '%' . $data['v_extension'] . '%');
            });
        } */
        
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
            if($order_field == 'v_van_id') {
               $query = $query->join('fleet_vehicle','fleet_vehicle.id','=','v_van_id')->orderBy('fleet_vehicle.v_vehicle_code',$sort_order);
            } else if($order_field == 'i_driver_id') {
               $query = $query->join('admin','admin.id','=','i_driver_id')->orderBy('admin.v_firstname',$sort_order);
            }  else if($order_field == 'v_extension') {
               $query = $query->join('driver_extension','driver_extension.i_driver_id','=','i_driver_id')->orderBy('driver_extension.v_extension',$sort_order);
            } else {
                $query = $query->orderBy($order_field, $sort_order);
            }
        } else {
            $query = $query->orderBy('kiosk.updated_at', 'desc');
        }
        $users = $query->paginate($rec_per_page);
        
        $arrUsers = $users->toArray();
        $data = array();
        // pr($arrUsers['data']); exit;
        
        foreach ($arrUsers['data'] as $key => $val) {
            $index = 0;
            
                if(isset($this->permission) && isset($this->permission[19]['i_delete']) && $this->permission[19]['i_delete'] == 1) {
                    $data[$key][$index++] = '<label class="kt-checkbox kt-checkbox--single kt-checkbox--solid "><input type="checkbox" name="id[]" value="' . $val['id'] . '" class="kt-checkable"><span></span></label>';
                }
                $data[$key][$index++] = $val['v_location_code'];
    
                if($val['v_van_id'] == 'Other') {
                    $data[$key][$index++] = $val['v_van_id_input'];
                } else {
                    $data[$key][$index++] = $val['vehicle_code']['v_vehicle_code'];
                }
                
                // $data[$key][$index++] = date('D M-d-Y', strtotime(str_replace('/', '-', $val['d_departure_time'])));
                $data[$key][$index++] = date(DATE_FORMAT,strtotime($val['d_departure_time']));;
                $data[$key][$index++] = $val['v_notice'];
                $data[$key][$index++] = date('h:i:A', strtotime($val['d_departure_time']));
                $data[$key][$index++] = ($val['driver_name']) ? $val['driver_name']['v_firstname'].' '.$val['driver_name']['v_lastname'] .' ('.$val['driver_extension']['v_extension'].' )' : '';
                // $data[$key][$index++] = ($val['driver_extension']) ? $val['driver_extension']['v_extension'] : '';
    
                $action = '';
                $action .= '<div class="d-flex">';
                if(isset($this->permission) && isset($this->permission[19]['i_add_edit']) && $this->permission[19]['i_add_edit'] == 1) {
                    $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg kiosk-info-edit" rel="' . $val['id'] . '" href="' . ADMIN_URL . 'kiosk-info/edit/' . $val['id'] . '" title="edit" ><i class="la la-edit"></i> </a>';
                }
                if(isset($this->permission) && isset($this->permission[19]['i_delete']) && $this->permission[19]['i_delete'] == 1) {
                    $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' . ADMIN_URL . 'kiosk-info/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';
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
        $current_user = auth()->guard('admin')->user();
        $id = $current_user->id;
        $inputs = $request->all();
        // pr($inputs); exit;
        
        $driver_name = AdminRoles::with(['Admin' => function($q) {
            $q->select('id', 'i_role_id', 'v_firstname', 'v_lastname')->orderBy('v_firstname');
        }, 'Admin.DriverExtension'])->where('v_name', '=', 'Driver')->get()->toArray();
        // pr($driver_name); exit;
        $vehicle_name = FleetManager::where('e_vehicle_status', 'Ready')->select('id', 'v_vehicle_code')->orderBy('v_vehicle_code')->get()->toArray();

        if($inputs) {

            $record = new Kiosk;
        
            $validator = Validator::make($inputs, [
                // 'v_notice' => 'required',
                'd_departure_time' => 'required|unique:kiosk,d_departure_time,' . $id . ',id,deleted_at,NULL',
                /* 'v_van_id' => 'required',
                'i_driver_id' => 'required',  */
            ]);
            if ($validator->fails()) {
                return json_encode($validator->errors());
            } else {
                $date = Kiosk::where('d_departure_time', date('Y-m-d H:i:s',strtotime($inputs['d_departure_time'])))->get();
                // pr(count($date)); exit;
                if(count($date) > 0) {
                    return 'DATE_MATCH';
                } else {
                    $record->v_location_code = "SEA";
                    $record->v_notice = trim($inputs['v_notice']);
                    $record->d_departure_time = date('Y-m-d H:i:s', strtotime($inputs['d_run_date'].' '.$inputs['d_departure_time']));
                    $record->i_driver_id = trim($inputs['i_driver_id']);
                    $record->v_van_id_input = isset($inputs['van_id_input']) ? trim($inputs['van_id_input']) : NULL;
                    $record->v_van_id = trim($inputs['v_van_id']);
                    $record->created_at = Carbon::now();
                    if ($record->save()) {  
                        Session::flash('success-message', 'Kiosk Information added successfully.');
                        return '';
                    }
                }
            }
        } else {
            return View('backend.kiosk.add', array('title' => "Kiosk Information", "driver_name" => $driver_name, "vehicle_name" => $vehicle_name));
        }
        return Redirect(ADMIN_URL . 'kiosk-info');
    }

    public function anyEdit(Request $request, $id) {
        
        $inputs = $request->all();
        
        $record = Kiosk::find($id);
        $driver_name = AdminRoles::with(['Admin' => function($q) {
            $q->select('id', 'i_role_id', 'v_firstname', 'v_lastname')->orderBy('v_firstname');
        }, 'Admin.DriverExtension'])->where('v_name', '=', 'Driver')->get()->toArray();
        $vehicle_name = FleetManager::where('e_vehicle_status', 'Ready')->select('id', 'v_vehicle_code')->orderBy('v_vehicle_code')->get()->toArray();
        //pr($driver_name); exit;
        if(!empty($inputs)) {
            // pr($inputs); exit;
            $validator = Validator::make($inputs, [
                // 'v_notice' => 'required',
                'd_departure_time' => 'required|unique:kiosk,d_departure_time,' . $id . ',id,deleted_at,NULL',
                /* 'v_van_id' => 'required',
                'i_driver_id' => 'required',  */
            ]);
            if ($validator->fails()) {
                return json_encode($validator->errors());
            } else {
                $date = Kiosk::where('id', '<>', $id)->where('d_departure_time', date('Y-m-d H:i:s',strtotime($inputs['d_departure_time'])))->get();
                // pr(count($date)); exit;
                if(count($date) > 0) {
                    return 'DATE_MATCH';
                } else {
                    $record->v_location_code = "SEA";
                    $record->v_notice = trim($inputs['v_notice']);
                    $record->d_departure_time = date('Y-m-d H:i:s', strtotime($inputs['d_run_date'].' '.$inputs['d_departure_time']));
                    $record->v_van_id_input = isset($inputs['van_id_input']) ? trim($inputs['van_id_input']) : NULL;
                    $record->v_van_id = trim($inputs['v_van_id']);
                    $record->i_driver_id = trim($inputs['i_driver_id']);
                    $record->created_at = Carbon::now();
                    if ($record->save()) {  
                        Session::flash('success-message', 'Kiosk Information edited successfully.');
                        return '';
                    }
                }
            }
        } else {
            return View('backend.kiosk.edit', array('title' => "Kiosk Information", "driver_name" => $driver_name, "vehicle_name" => $vehicle_name, 'record' => $record));
        }
        return Redirect(ADMIN_URL . 'kiosk-info');
    }

    public function getDelete($id) {
        $record = Kiosk::find($id);
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
                $user_data = Kiosk::whereIn('id', array_values($data['ids']))->get();
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
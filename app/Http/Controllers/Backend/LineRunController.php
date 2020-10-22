<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\LineRun;
use App\Models\Admin;
use App\Models\AdminRoles;
use App\Models\FleetManager;
use App\Models\GeoServiceArea;
use App\Models\LineRunSetting;
use App\Models\ReservationLeg;
use App\Models\LineRunTemplate;
use DateInterval;
use DateTime;
use DatePeriod;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail, Session, Redirect, Validator, DB, Hash, Auth;
use Illuminate\Validation\Rule;

class LineRunController extends BaseController {

    public function getIndex() {

        $record_service_area = GeoServiceArea::select('id', 'v_area_label')->orderBy('v_area_label')->where(['e_status' => 'Active'])->get()->toArray();

        $record_driver = AdminRoles::with(['Admin' => function($q) {
            $q->select('id', 'i_role_id', 'v_firstname', 'v_lastname')->orderBy('v_firstname');
        }, 'Admin.DriverExtension'])->where('v_name', '=', 'Driver')->get()->toArray();

        return View('backend.line_run.index', array('title' => "Line Run", 'record_service_area' => $record_service_area, 'record_driver' => $record_driver));
    }

    public function anyListAjax(Request $request) {
        $this->changeLinerunStatusController();
        $data = $request->all();
        if(Auth::guard('admin')->user()->i_role_id != 6){
            $sortColumn = array('id','c_run_key','d_run_date', 'id','i_origin_service_area_id','i_dest_service_area_id',  't_scheduled_arr_time', 'e_run_status', 'i_driver_id', 'e_service_type','i_num_booked_seats');
        } else{
            $sortColumn = array('id','c_run_key','d_run_date', 'id','i_origin_service_area_id','i_dest_service_area_id',  't_scheduled_arr_time', 'e_run_status', 'e_service_type','i_num_booked_seats');
        }
        
        DB::enableQueryLog();
        $query = LineRun::select('linerun.*',DB::raw('IFNULL((select sum(i_total_num_passengers) from reservations as res inner join reservation_leg res_leg on res.id=res_leg.i_reservation_id where res.e_reservation_status="Booked" and res.deleted_at is null and res_leg.deleted_at is null and res_leg.i_run_id = linerun.id),0) as i_num_booked_seats'))->with(['GeoOriginServiceArea' => function($q) {
            $q->select('id', 'v_area_label');
        }, 'GeoDestServiceArea' => function($q1) {
            $q1->select('id', 'v_area_label');
        }, 'Driver.DriverExtension', 'Driver']);

        if(Auth::guard('admin')->user()->i_role_id == 6){
            $query = $query->where('i_driver_id', '=',Auth::guard('admin')->user()->id);
        }

        if (isset($data['c_run_key']) && $data['c_run_key'] != '') {
            $query = $query->where('c_run_key', 'LIKE', '%' . $data['c_run_key'] . '%');
        }
        if (isset($data['v_run_number']) && $data['v_run_number'] != '') {
            $query = $query->where('linerun.id', '=' ,$data['v_run_number']);
        }
        if (isset($data['dStartDate']) && trim($data['dStartDate']) != '') {
            $query = $query->where(DB::raw('DATE(d_run_date)'), '>=', trim(date('Y-m-d',strtotime(trim($data['dStartDate'])))));
        } else {
            $query = $query->where(DB::raw('DATE(d_run_date)'), '=', trim(date('Y-m-d')));
        }
        if (isset($data['dEndDate']) && trim($data['dEndDate']) != '') {
            $query = $query->where(DB::raw('DATE(d_run_date)'), '<=', trim(date('Y-m-d', strtotime(trim($data['dEndDate'])))));
        }  else {
            $query = $query->where(DB::raw('DATE(d_run_date)'), '=', trim(date('Y-m-d')));
        }
        if (isset($data['i_origin_service_area_id']) && $data['i_origin_service_area_id'] != '') {
            $query = $query->whereHas('GeoOriginServiceArea', function($q) use($data){
                $q->where('geo_service_area.id', 'LIKE', '%' . $data['i_origin_service_area_id'] . '%');
            });
        }
        if (isset($data['i_dest_service_area_id']) && $data['i_dest_service_area_id'] != '') {
            $query = $query->whereHas('GeoDestServiceArea', function($q) use($data){
                $q->where('geo_service_area.id', 'LIKE', '%' . $data['i_dest_service_area_id'] . '%');
            });
        }
        if (isset($data['e_run_status']) && $data['e_run_status'] != '') {
            $query = $query->where('e_run_status', 'LIKE', '%' . $data['e_run_status'] . '%');
        }
        if (isset($data['i_num_total']) && $data['i_num_total'] != '') {
            $query = $query->where('i_num_total', 'LIKE', '%' . $data['i_num_total'] . '%');
        }
        if (isset($data['t_scheduled_arr_time']) && $data['t_scheduled_arr_time'] != '') {
            $query = $query->where('t_scheduled_arr_time', '=',  date('H:i:s', strtotime($data['t_scheduled_arr_time'])));
        }

        if (isset($data['e_service_type']) && $data['e_service_type'] != '') {
            $query = $query->where('e_service_type', 'LIKE', '%' . $data['e_service_type'] . '%');
        }

        if (isset($data['i_driver_id']) && $data['i_driver_id'] != '') {
            $query = $query->whereHas('Driver', function($q) use($data){ 
               $q = $q->where('admin.id','=', $data['i_driver_id']);
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

        $sort_order = $data['order']['0']['dir'];
        $order_field = $sortColumn[$data['order']['0']['column']];
        
        if ($sort_order != '' && $order_field != '') {
            if($order_field == 'i_origin_service_area_id') {
               $query = $query->join('geo_service_area','geo_service_area.id','=','i_origin_service_area_id')->orderBy('geo_service_area.v_area_label',$sort_order);
            } else if($order_field == 'i_dest_service_area_id') {
               $query = $query->join('geo_service_area','geo_service_area.id','=','i_dest_service_area_id')->orderBy('geo_service_area.v_area_label',$sort_order);
            }else if($order_field == 'c_run_key'){
                $query = $query->orderBy('c_run_key',$sort_order)->orderBy('i_origin_service_area_id',$sort_order)->orderBy('d_run_date',$sort_order);
            } else if($data['order']['0']['column'] == 0) {
                //'Open','Closed','Set','Dispatched','Modified','Departed','Delayed','Dead head','Completed','Private'
                $query = $query->orderBy(DB::raw("CASE WHEN e_run_status LIKE 'Open' THEN 1 
                                                   WHEN e_run_status LIKE 'Closed' THEN  2 
                                                   WHEN e_run_status LIKE 'Set' THEN  3 
                                                   WHEN e_run_status LIKE 'Dispatched' THEN 4 
                                                   WHEN e_run_status LIKE 'Modified' THEN 5 
                                                   WHEN e_run_status LIKE 'Departed' THEN 6 
                                                   WHEN e_run_status LIKE 'Delayed' THEN 7 
                                                   WHEN e_run_status LIKE 'Dead head' THEN 8 
                                                   WHEN e_run_status LIKE 'Completed' THEN 9 
                                                   WHEN e_run_status LIKE 'Private' THEN 10 
                                                   END"), 'asc')->orderBy('d_run_date', 'asc')->orderBy('t_scheduled_arr_time', 'asc');
            } else if($order_field=='i_driver_id') {
                $query = $query->join('admin as d','d.id','=','linerun.i_driver_id')->orderBy('v_firstname',$sort_order)->orderBy('v_lastname',$sort_order);
            } else {
                $query = $query->orderBy($order_field, $sort_order);
            }
            
        } else {
            $query = $query->orderBy(DB::raw("CASE WHEN e_run_status LIKE 'Open' THEN 1 
                                                   WHEN e_run_status LIKE 'Closed' THEN  2 
                                                   WHEN e_run_status LIKE 'Set' THEN  3 
                                                   WHEN e_run_status LIKE 'Dispatched' THEN 4 
                                                   WHEN e_run_status LIKE 'Modified' THEN 5 
                                                   WHEN e_run_status LIKE 'Departed' THEN 6 
                                                   WHEN e_run_status LIKE 'Delayed' THEN 7 
                                                   WHEN e_run_status LIKE 'Dead head' THEN 8 
                                                   WHEN e_run_status LIKE 'Completed' THEN 9 
                                                   WHEN e_run_status LIKE 'Private' THEN 10 
                                                   END"), 'asc')->orderBy('d_run_date', 'asc')->orderBy('t_scheduled_arr_time', 'asc');
        }
        
        $users = $query->paginate($rec_per_page);
        
        $arrUsers = $users->toArray();
        $data = array();
        
        foreach ($arrUsers['data'] as $key => $val) {
            $index = 0;
           /*  pr($val);
            exit; */

            if($val['i_num_booked_seats'] == 0) {
            $data[$key][$index++] = '<span class="free_line_run">'.$val['id'].'</span>';
            $data[$key][$index++] = $val['c_run_key'];
            $data[$key][$index++] = '<span class="free_line_run">'.date(DATE_FORMAT,strtotime($val['d_run_date'])).'</span>';
            $data[$key][$index++] = $val['id'];
            $data[$key][$index++] = $val['geo_origin_service_area']['v_area_label'];
            $data[$key][$index++] = $val['geo_dest_service_area']['v_area_label'];
            $data[$key][$index++] = ($val['t_scheduled_arr_time']) ? date('g:i A', strtotime($val['t_scheduled_arr_time'])) : '';
            $data[$key][$index++] = $val['e_run_status'];
            if(Auth::guard('admin')->user()->i_role_id != 6){
                $data[$key][$index++] = ($val['driver']) ? $val['driver']['v_firstname']." ".$val['driver']['v_lastname']." (". $val['driver']['driver_extension'][0]['v_extension'].")" : '';
            }
            $data[$key][$index++] = $val['e_service_type'];
            $data[$key][$index++] = '<span class="free_line_run">'.$val['i_num_booked_seats'].'/'.$val['i_num_total'].'</span>';
            } 
            else{
                $data[$key][$index++] = $val['id'];
                $data[$key][$index++] = $val['c_run_key'];
                $data[$key][$index++] = date(DATE_FORMAT,strtotime($val['d_run_date']));
                $data[$key][$index++] = $val['id'];
                $data[$key][$index++] = $val['geo_origin_service_area']['v_area_label'];
                $data[$key][$index++] = $val['geo_dest_service_area']['v_area_label'];
                $data[$key][$index++] = ($val['t_scheduled_arr_time']) ? date('g:i A', strtotime($val['t_scheduled_arr_time'])) : '';
                $data[$key][$index++] = $val['e_run_status'];
                if(Auth::guard('admin')->user()->i_role_id != 6){
                    $data[$key][$index++] = ($val['driver']) ? $val['driver']['v_firstname']." ".$val['driver']['v_lastname']." (". $val['driver']['driver_extension'][0]['v_extension'].")" : '';
                }
                $data[$key][$index++] = $val['e_service_type'];
                $data[$key][$index++] = $val['i_num_booked_seats'].'/'.$val['i_num_total'];
            }
            

            $action = '';
            $action .= '<div class="d-flex">';
            if(isset($this->permission) && isset($this->permission[13]['i_add_edit']) && $this->permission[13]['i_add_edit'] == 1) {
                $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . ADMIN_URL . 'linerun/edit/' . $val['id'] . '" title="edit"><i class="la la-edit"></i> </a>';
            }
            if(isset($this->permission) && isset($this->permission[13]['i_delete']) && $this->permission[13]['i_delete'] == 1) {
                $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' . ADMIN_URL . 'linerun/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';
            }
            
            $action .= '<a title="View" id="view_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg view_record" rel="' . $val['id'] . '" href="' . ADMIN_URL . 'linerun/view/' . $val['id'] . '"><i class="la la-eye"></i></a>';
            if($val['i_num_booked_seats'] > 0) {
                $action .= '<a title="Print Manifest" class="btn btn-sm btn-clean btn-icon btn-icon-lg print_page" href="javascript:;" printId="' . $val['id'] . '" ><i class="la la-print"></i></a>';
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

    public function anyView($id) {
        $record = LineRun::where('id',$id);
        $reservation_detail = ReservationLeg::with(['ReservAtionInfo' =>function($q){ 
            $q->select('*')->with(['PickupCity','DropOffCity','Admin', 'Customers', 'SystemResCategory']);
        }])->where('i_run_id',$id)->whereHas('ReservAtionInfo')->get();

        
      
        $record = $record->with(['Driver' => function($q){
            $q->select('id','v_firstname','v_lastname','v_profile_image');
        },'VehicleFleet' => function($q) {
            $q->select('id', 'v_image', 'v_lic_plate')->with(['get_vehicle_specification' => function($q){
                $q->select('i_vehicle_id','v_make', 'v_model', 'v_series', 'v_vehicle_type');
            }]);
        },'GeoOriginServiceArea' => function($q) {
            $q->select('id','v_area_label');
        },'GeoDestServiceArea' => function($q){
            $q->select('id','v_area_label');
        }, 'Driver.DriverExtension'])->first();
        
        if(!$record) {
            return redirect(ADMIN_URL.'linerun');
        }

        $record = $record->toArray();
        return View('backend.line_run.view', array('title' => "Line Run - #".$id, 'record' => $record,'reservation_detail'=>$reservation_detail));
    }

    public function anyEdit(Request $request, $id) {

        $inputs = $request->all();
        //pr($inputs); exit;

        $record = LineRun::find($id);

        $record_service_area = GeoServiceArea::select('id', 'v_area_label')->orderBy('v_area_label')->where(['e_status' => 'Active'])->get()->toArray();

        $record_driver = AdminRoles::with(['Admin' => function($q) {
            $q->select('id', 'i_role_id', 'v_firstname', 'v_lastname')->orderBy('v_firstname');
        }, 'Admin.DriverExtension'])->where('v_name', '=', 'Driver')->get()->toArray();

        $record_vehicle = FleetManager::with(['get_vehicle_specification' => function($q) {
            $q->select('i_vehicle_id','v_make', 'v_model', 'v_series');
        }])->where('e_vehicle_status', 'Ready')->select('id','e_vehicle_status','v_vehicle_code')->orderBy('v_vehicle_code')->get()->toArray();
        // pr($record_vehicle); exit;

        if ($inputs) {
            //pr($inputs); exit;
            $validator = Validator::make($request->all(), [
                    'c_run_key' => 'required',
                    'i_origin_service_area_id' => 'required',
                    // 't_scheduled_dep_time' => 'required',
                    'i_num_available' => 'required',
                    'e_run_status' => 'required',
                    'i_vehicle_id' => 'required',
                    'd_run_date' => 'required',
                    'i_dest_service_area_id' => 'required',
                    't_scheduled_arr_time' => 'required',
                    // 'i_num_total' => 'required',
                    'i_driver_id' => 'required',
                    'e_service_type' => 'required',
                ]);

                if ($validator->fails()) {
                    return json_encode($validator->errors());
                } else {
                    $record->c_run_key = trim($inputs['c_run_key']);
                    $record->i_origin_service_area_id = trim($inputs['i_origin_service_area_id']);
                    // $record->t_scheduled_dep_time = trim($inputs['t_scheduled_dep_time']);
                    if($inputs['i_num_total'] >= $inputs['i_num_available']) {
                        $record->i_num_available = trim($inputs['i_num_available']);
                    } else {
                        return 'BOOKABLE_SEAT_LESS_TOTAL_SEAT';
                    }
                    $record->e_run_status = trim($inputs['e_run_status']);
                    $record->i_vehicle_id = trim($inputs['i_vehicle_id']);
                    $record->i_dest_service_area_id = trim($inputs['i_dest_service_area_id']);
                    $record->t_scheduled_arr_time = trim($inputs['t_scheduled_arr_time']);
                    $record->i_num_total = trim($inputs['i_num_total']);
                    $record->i_driver_id = trim($inputs['i_driver_id']);
                    $record->v_kiosk_notice = trim($inputs['v_kiosk_notice']);
                    /* $linerun_existing_date = LineRun::where('id','<>',$id)->where('d_run_date', trim(date('Y-m-d',strtotime(trim($inputs['d_run_date'])))))->where('t_scheduled_dep_time',$inputs['t_scheduled_dep_time'])->where('t_scheduled_arr_time',$inputs['t_scheduled_arr_time'])->get()->toArray(); */
                    $linerun_existing_date = LineRun::where('id','<>',$id)->where('d_run_date', trim(date('Y-m-d',strtotime(trim($inputs['d_run_date'])))))->where('t_scheduled_arr_time',$inputs['t_scheduled_arr_time'])->where('i_origin_service_area_id', $inputs['i_origin_service_area_id'])->where('i_dest_service_area_id', $inputs['i_dest_service_area_id'])->where('c_run_key', $inputs['c_run_key'])->get()->toArray();
                    /* $linerun_existing_date = LineRun::where('id','<>',$id)->where('d_run_date', trim(date('Y-m-d',strtotime(trim($inputs['d_run_date'])))))->where('t_scheduled_arr_time',$inputs['t_scheduled_arr_time'])->get()->toArray(); */
                    // pr(count($linerun_existing_date)); exit;
                    if(count($linerun_existing_date) < 1) {
                        $record->d_run_date = date('Y-m-d', strtotime(trim($inputs['d_run_date'])));
                    } else {
                        return 'SAME_DATE_MESSAGE_ADD';
                    }
                    $record->e_service_type = trim($inputs['e_service_type']);
                    $record->t_special_instruction = trim($inputs['t_special_instruction']);
                    if ($record->save()) {
                        Session::flash('success-message', 'Line run record updated successfully.');
                        return '';
                    }
                }
        } else {
            return View('backend.line_run.edit', array('title' => "Edit Line Run - #".$id, 'record' => $record, 'record_driver' => $record_driver, 'record_vehicle' => $record_vehicle, 'record_service_area' => $record_service_area));
        }
    }

    public function getDelete($id) {
        $record = LineRun::find($id);
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
                $linerun_data = LineRun::whereIn('id', array_values($data['ids']))->get();
                if ($linerun_data) {
                    foreach ($linerun_data as $data) {

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

    public function anyAdd(Request $request) {

        $inputs = $request->all();
        $record_count_max = LineRun::max('id');
       
        $record_service_area = GeoServiceArea::select('id', 'v_area_label')->orderBy('v_area_label')->where(['e_status' => 'Active'])->get()->toArray();

        /* $record_driver = AdminRoles::with(['Admin' => function($q) {
            $q->select('id', 'i_role_id', 'v_firstname', 'v_lastname');
        }])->where('v_name', 'Driver')->get()->toArray(); */

        $record_driver = AdminRoles::with(['Admin' => function($q) {
            $q->select('id', 'i_role_id', 'v_firstname', 'v_lastname')->orderBy('v_firstname');
        }, 'Admin.DriverExtension'])->where('v_name', '=', 'Driver')->get()->toArray();

        // pr($record_driver); exit;

        $record_vehicle = FleetManager::with(['get_vehicle_specification' => function($q) {
            $q->select('i_vehicle_id','v_make', 'v_model', 'v_series');
        }])->where('e_vehicle_status', 'Ready')->select('id','e_vehicle_status','v_vehicle_code')->orderBy('v_vehicle_code')->get()->toArray();
        
        if ($inputs) {
            // pr($inputs); exit;
                $validator = Validator::make($request->all(), [
                    'c_run_key' => 'required',
                    'i_origin_service_area_id' => 'required',
                    // 't_scheduled_dep_time' => 'required',
                    'i_num_available' => 'required',
                    'e_run_status' => 'required',
                    'i_vehicle_id' => 'required',
                    'd_run_date' => 'required',
                    'i_dest_service_area_id' => 'required',
                    't_scheduled_arr_time' => 'required',
                    // 'i_num_total' => 'required',
                    'i_driver_id' => 'required',
                    'e_service_type' => 'required',
                ]);

                if ($validator->fails()) {
                    return json_encode($validator->errors());
                } else {
                    $record = new LineRun;
                    $record->c_run_key = trim($inputs['c_run_key']);
                    $record->i_origin_service_area_id = trim($inputs['i_origin_service_area_id']);
                    // $record->t_scheduled_dep_time = trim($inputs['t_scheduled_dep_time']);
                    if($inputs['i_num_total'] >= $inputs['i_num_available']) {
                        $record->i_num_available = trim($inputs['i_num_available']);
                    } else {
                        return 'BOOKABLE_SEAT_LESS_TOTAL_SEAT';
                    }
                    $record->e_run_status = trim($inputs['e_run_status']);
                    $record->i_vehicle_id = trim($inputs['i_vehicle_id']);
                    $record->i_dest_service_area_id = trim($inputs['i_dest_service_area_id']);
                    $record->t_scheduled_arr_time = trim($inputs['t_scheduled_arr_time']);
                    $record->i_num_total = trim($inputs['i_num_total']);
                    $record->i_driver_id = trim($inputs['i_driver_id']);
                    $record->v_kiosk_notice = trim($inputs['v_kiosk_notice']);
                   
                    /* $linerun_existing_date = LineRun::where('d_run_date', trim(date('Y-m-d',strtotime(trim($inputs['d_run_date'])))))->where('t_scheduled_dep_time',$inputs['t_scheduled_dep_time'])->where('t_scheduled_arr_time',$inputs['t_scheduled_arr_time'])->get()->toArray(); */
                    $linerun_existing_date = LineRun::where('d_run_date', trim(date('Y-m-d',strtotime(trim($inputs['d_run_date'])))))->where('t_scheduled_arr_time',$inputs['t_scheduled_arr_time'])->get()->toArray();
                    if(count($linerun_existing_date) < 1) {
                        $record->d_run_date = date('Y-m-d', strtotime(trim($inputs['d_run_date'])));
                    } else {
                        return 'SAME_DATE_MESSAGE_ADD';
                    }

                    $record->e_service_type = trim($inputs['e_service_type']);
                    $record->t_special_instruction = trim($inputs['t_special_instruction']);
                    // pr($record); exit;
                    if ($record->save()) {
                        Session::flash('success-message', 'Line Run record added successfully.');
                        return '';
                    }
                }
        } else {
            return View('backend.line_run.add', array('title' => "Add Line Run", 'record_driver' => $record_driver, 'record_vehicle' => $record_vehicle, 'record_service_area' => $record_service_area,'record_count_max'=>$record_count_max));
        }
    }

    public function generateLineRun(Request $request) {
        $inputs = $request->all();
        
        if($inputs) {
            
            $from = trim(date('Y-m-d',strtotime($inputs['start_run_date'])));
            $to = trim(date('Y-m-d',strtotime($inputs['end_run_date'])));
            //date difference
            $q1 = date_create($from);
            $q2 = date_create($to);
            $diff = date_diff($q1, $q2);
            

            $line_run_date = LineRunSetting::select('d_peak_start_date','d_peak_end_date', 'd_off_season_start_date','d_off_season_end_date')->first();
            
            if($from >= $line_run_date['d_peak_start_date'] && $to <= $line_run_date['d_peak_end_date']) {
                $rate_season = 'PK';
            } else {
                $rate_season = 'OP';
            }
           
            $line_run_template = LineRunTemplate::where('e_rate_season', $rate_season)->where('e_status', 'Active')->orderBy('c_run_key')->get()->toArray();
            
            $is_new_record = 0;
            $total_save_reord = 0 ;
            for ($i=0; $i <= $diff->days; $i++) { 
                $temp_date = $from;
                $run_key = '';
                
                foreach ($line_run_template as $key => $value) {
                    $line_run = $this->getDriverVehicleData($from,$value['t_target_time'],$value['i_origin_service_area_id'],$value['i_dest_service_area_id']);

                    if( $run_key == $value['c_run_key'] && $from == $temp_date) {
                        $new_linerun_record = new LineRun;
                        $new_linerun_record->c_run_key = $value['c_run_key'];
                        $new_linerun_record->d_run_date = $from;
                        $new_linerun_record->i_origin_service_area_id = $value['i_origin_service_area_id'];
                        $new_linerun_record->i_dest_service_area_id = $value['i_dest_service_area_id'];
                        $new_linerun_record->t_scheduled_arr_time = $value['t_target_time'];
                        $new_linerun_record->i_vehicle_id = ($line_run) ? $line_run->i_vehicle_id : 0;
                        $new_linerun_record->i_num_available = ($line_run && $line_run->VehicleFleet) ? $line_run->VehicleFleet->i_optimal_booking_seats : 0;
                        $new_linerun_record->i_num_total = ($line_run && $line_run->VehicleFleet) ? $line_run->VehicleFleet->i_total_customer_booking_seats : 0;
                        
                        $new_linerun_record->save();
                       
                    } else {
                        $linerun_existing_time = LineRun::where('d_run_date', $temp_date)->where('c_run_key', $value['c_run_key'])->where(function($q) use ($value){            $q->where('t_scheduled_arr_time', $value['t_target_time']); })->get()->toArray();

                        if(count($linerun_existing_time) == 0 ) {
                            $new_linerun_record = new LineRun;
                            $new_linerun_record->c_run_key = $value['c_run_key'];
                            $new_linerun_record->d_run_date = $from;
                            $new_linerun_record->i_origin_service_area_id = $value['i_origin_service_area_id'];
                            $new_linerun_record->i_dest_service_area_id = $value['i_dest_service_area_id'];
                            $new_linerun_record->t_scheduled_arr_time = $value['t_target_time'];
                            $new_linerun_record->i_vehicle_id = ($line_run) ? $line_run->i_vehicle_id : 0;
                            $new_linerun_record->i_num_available = ($line_run && $line_run->VehicleFleet) ? $line_run->VehicleFleet->i_optimal_booking_seats : 0;
                            $new_linerun_record->i_num_total = ($line_run && $line_run->VehicleFleet) ? $line_run->VehicleFleet->i_total_customer_booking_seats : 0;
                            $new_linerun_record->save();

                            $temp_date = $new_linerun_record->d_run_date;
                            $run_key = $new_linerun_record->c_run_key;
                            $is_new_record++;
                            $total_save_reord++;
                        }
                    } 
                }
                    
                if($from <=  $to) {
                    $date1 = str_replace('-', '/', $from);
                    $from = date('Y-m-d',strtotime($date1 . "+1 days"));
                } 
            }

            if($is_new_record == 0) {
                return 'SAME_DATE_MESSAGE';
            }
            
            $tot_saved_record = $total_save_reord * 2;
            Session::flash('success-message', $tot_saved_record. ' Line Run generated successfully.');
            return 'GENERATE_LINE_RUN';
        
        } else {
            return View('backend.line_run.generate_line_run', array('title' => "Generate Line Run"));
        }
        
    }
    
    public function changeLinerunStatusController() {
        $record = LineRun::where('d_run_date', '<', date("Y/m/d"))->update(['e_run_status' => 'Completed']);
    }

    public function getVehicleSeatInformation($vehicle_id) {
        $data = array();
        $record = FleetManager::find($vehicle_id);
        $data['total_seat'] = $record->i_total_customer_booking_seats;
        $data['bookable_seat'] = $record->i_optimal_booking_seats;
        return $data;
    }

    public function getDriverVehicleData($date,$time,$origin_service_area_id,$dest_service_area_id) {
        $line_run = LineRun::where('d_run_date', '<',$date)->where('t_scheduled_arr_time',$time)->where('i_origin_service_area_id',$origin_service_area_id)->where('i_dest_service_area_id',$dest_service_area_id)->where('e_service_type','Shuttle')->where('i_vehicle_id', '<>', 0)->select('id', 'd_run_date', 'i_vehicle_id')->with('VehicleFleet')->first();
        return $line_run;
    }

    public function addDuplicateLineruns(){
        $from_date = "2020-09-03";
        $to_date = "2020-09-30";

        $dates_to_generate = $this->getDatesFromRange($from_date,$to_date); 

        $copy_from_date = "2020-08-31";

        $lineruns = LineRun::where('d_run_date',$copy_from_date)->get();
        $total_generated = 0;

        foreach($dates_to_generate as $dt){
            foreach($lineruns as $lr){
                $record = new LineRun;
                $record->c_run_key = $lr->c_run_key;
                $record->i_origin_service_area_id = $lr->i_origin_service_area_id;
                $record->i_num_available = $lr->i_num_available;
                $record->e_run_status = $lr->e_run_status;
                $record->i_vehicle_id = $lr->i_vehicle_id;
                $record->i_dest_service_area_id = $lr->i_dest_service_area_id;
                $record->t_scheduled_arr_time = $lr->t_scheduled_arr_time;
                $record->i_num_total = $lr->i_num_total;
                $record->i_driver_id = $lr->i_driver_id;
                $record->d_run_date = $dt;
                $record->e_service_type = $lr->e_service_type;
                $record->v_run_number = $lr->v_run_number;
                $record->t_special_instruction = $lr->t_special_instruction;
                $record->save();
                $total_generated++;
            }
        }

        echo "Total generated lineruns for dates ".$from_date." - ".$to_date." = ".$total_generated;
    }

    function getDatesFromRange($start, $end, $format = 'Y-m-d') { 
      
        // Declare an empty array 
        $array = array(); 
          
        // Variable that store the date interval 
        // of period 1 day 
        $interval = new DateInterval('P1D'); 
      
        $realEnd = new DateTime($end); 
        $realEnd->add($interval); 
      
        $period = new DatePeriod(new DateTime($start), $interval, $realEnd); 
      
        // Use loop to store date into array 
        foreach($period as $date) {                  
            $array[] = $date->format($format);  
        } 
      
        // Return the array elements 
        return $array; 
    }
    public function anyRocketManifestPrint(Request $request,$id) {
        $inputs = $request->all();
       
        $reservation_detail = ReservationLeg::with(['ReservAtionInfo' =>function($q){ 
            $q->select('*')->with(['PickupCity','DropOffCity','Admin', 'Customers', 'SystemResCategory']);
        }])->where('i_run_id',$id)->whereHas('ReservAtionInfo')->get();

        if(isset($inputs['total_count'])){
            for($i = 1; $i<=($inputs['total_count']);$i++) {
                $new_data = array();
                $id = $inputs['id_'.$i];
                $record = ReservationLeg::find($id);
                $new_data['pu_time_'.$id] = $inputs['pu_time_'.$i];
                $new_data['pu_milege_'.$id] = $inputs['pu_milege_'.$i];
                $new_data['do_time_'.$id] = $inputs['do_time_'.$i];
                $new_data['do_mileage_'.$id] = $inputs['do_mileage_'.$i];
                $new_data['cross_st_'.$id] = $inputs['cross_st_'.$i];
                $new_data['actual_time_'.$id] = $inputs['actual_time_'.$i];
                $new_data['contact_text_'.$id] = $inputs['contact_text_'.$i];
                $record['v_manifest_json'] = json_encode($new_data);
                $record->save();
            }
        }
      
        return View('backend.line_run.manifest_print', array('title' => "Data", 'record' => $inputs,'reservation_detail'=>$reservation_detail));
    }
    public function anyRocketLineRunPrint(Request $request,$id){
      
        $reservation_detail = ReservationLeg::with(['ReservAtionInfo' =>function($q){ 
            $q->select('*')->with(['PickupCity','DropOffCity','Admin', 'Customers', 'SystemResCategory']);
        }])->where('i_run_id',$id)->whereHas('ReservAtionInfo')->get();
        /* pr($reservation_detail);
        exit; */
        return View('backend.line_run.line_print', array('title' => "Data",'reservation_detail'=>$reservation_detail));
    }
}
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
use Mail, Session, Redirect, Validator, DB, Hash;
use Illuminate\Validation\Rule;

class RocketManifestController extends BaseController {

    public function getIndex(Request $request) {

        $inputs = $request->all();
        $arrDates = date('Y-m-d');
        $auth_user = auth()->guard('admin')->user();
       
        $record_driver = AdminRoles::with(['Admin' => function($q) {
            $q->select('id', 'i_role_id', 'v_firstname', 'v_lastname')->orderBy('v_firstname');
        }, 'Admin.DriverExtension'])->where('v_name', '=', 'Driver')->get()->toArray();

        if($auth_user['i_role_id'] != 6){
            $reservation_detail = ReservationLeg::with(['ReservAtionInfo' =>function($q){ 
            $q->select('*')->with(['PickupCity','DropOffCity', 'Admin', 'Customers', 'SystemResCategory']);
            }])->where('d_travel_date',$arrDates)->whereHas('ReservAtionInfo')->get();
          /*   pr($reservation_detail);
            exit; */
        } else {
            $driver_id = $auth_user['id'];
            $reservation_detail = ReservationLeg::with(['ReservAtionInfo' =>function($q){ 
                $q->with(['GeoOriginServiceArea.GeoCities', 'GeoDestServiceArea.GeoCities', 'Admin', 'Customers', 'SystemResCategory']);
                },'LineRune'  => function($qa)use($driver_id){
                    if($driver_id > 0){
                        $qa->where('i_driver_id',$driver_id);
                    }

                 }])->where('d_travel_date',$arrDates)->whereHas('ReservAtionInfo')->whereHas('LineRune',function($qa)use($driver_id){
                    if($driver_id > 0){
                        $qa->where('i_driver_id',$driver_id);
                    }
                 })->get();
                
        }
       
        if($inputs){
            $driver_id = $inputs['i_driver_id'];
            $reservation_detail = ReservationLeg::with(['ReservAtionInfo' =>function($q){ 
                $q->with(['PickupCity','DropOffCity','Admin', 'Customers', 'SystemResCategory']);
                },'LineRune'  => function($qa)use($driver_id){
                    if($driver_id > 0){
                        $qa->where('i_driver_id',$driver_id);
                    }

                 }])->where('d_travel_date',trim(date('Y-m-d', strtotime(trim($inputs['d_travel_date'])))))->whereHas('ReservAtionInfo')->whereHas('LineRune',function($qa)use($driver_id){
                    if($driver_id > 0){
                        $qa->where('i_driver_id',$driver_id);
                    }
                 })->get();
                return View('backend.manifest.manifest_search', array('title' => "Rocket Manifest", 'reservation_detail' => $reservation_detail, 'todaydate'=>$arrDates,'record_driver'=>$record_driver,'record'=>$inputs));
               
        }else {

            return View('backend.manifest.index', array('title' => "Rocket Manifest", 'reservation_detail' => $reservation_detail, 'todaydate'=>$arrDates,'record_driver'=>$record_driver,'auth_user'=>$auth_user));
        }
        
    }
    public function anyRocketManifestPrint(Request $request) {
        $inputs = $request->all();
        if($inputs){
            $arrDates = date('Y-m-d');
            $driver_id = $inputs['i_driver_id'];
            $reservation_detail = ReservationLeg::with(['ReservAtionInfo' =>function($q){ 
                $q->with(['PickupCity','DropOffCity','Admin', 'Customers', 'SystemResCategory']);
                },'LineRune'  => function($qa)use($driver_id){
                    if($driver_id > 0){
                        $qa->where('i_driver_id',$driver_id);
                    }

                }])->where('d_travel_date',trim(date('Y-m-d', strtotime(trim($inputs['d_travel_date'])))))->whereHas('ReservAtionInfo')->whereHas('LineRune',function($qa)use($driver_id){
                    if($driver_id > 0){
                        $qa->where('i_driver_id',$driver_id);
                    }
                })->get();
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
    }
    public function anyRocketManifestSave(Request $request){
        $inputs = $request->all();
        if($inputs){
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
                return 'TRUE';
            }
        }
        
    }
    

    
}
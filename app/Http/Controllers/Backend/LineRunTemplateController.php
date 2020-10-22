<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\LineRunTemplate;
use App\Models\GeoServiceArea;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail, Session, Redirect, Validator, DB, Hash;

class LineRunTemplateController extends BaseController {

    public function getIndex(Request $request){
        $inputs = $request->all();
        $geo_service_area = GeoServiceArea::where(['e_status' => 'Active'])->get();

        if($inputs && isset($this->permission) && isset($this->permission[13]['i_add_edit']) && $this->permission[13]['i_add_edit'] == 1) {

            $record_arrival = LineRunTemplate::where('id',$inputs['data'][0])->first();
            $record_arrival->t_target_time = trim($inputs['data'][0]['time']);
            $record_arrival->e_status = trim($inputs['data'][2]['e_stats']);
            $record_departure  = LineRunTemplate::where('id',$inputs['data'][1])->first();
            $record_departure->t_target_time = trim($inputs['data'][1]['time']);
            $record_departure->e_status = trim($inputs['data'][2]['e_stats']);

            if($record_arrival->save() && $record_departure->save()){

                return array('data'=>'TRUE');
            }

        }
        $data = LineRunTemplate::orderBy('c_run_key')->orderBy('e_rate_season')->orderBy('t_target_time')->get()->toArray();
        $arr_template = [];
       

        if($data) {
            foreach($data as $k => $v) {
                $arr_template[$v['e_rate_season']][$v['c_run_key']][] = $v;
            }
        }
        return View('backend.line_run_template.index', array('title' => "Line Run Templates",'arr_template' => $arr_template,'geo_service_area' => $geo_service_area));
    }

    public function anyAdd(Request $request){
        // $inputs = $request->all();
        // if($inputs){

        //     $validator = Validator::make($inputs, [

        //         'i_origin_service_area_id' => 'required',
        //         't_target_time_arrival' => 'required',
        //         'c_run_key' => 'required',
        //         'e_direction' => 'required',
        //         'i_dest_service_area_id' => 'required',
        //         't_target_time_departure' => 'required',
        //         'e_rate_season' => 'required',
        //         'e_status' => 'required',
        //     ]);

        //     if ($validator->fails()) {
        //         return json_encode($validator->errors());
        //     } else {

        //         if($inputs['e_direction'] == "E"){

        //             $direction_departure = "W";
        //             $direction_arrival = "E";
        //         }else{
        //             $direction_departure = "E";
        //             $direction_arrival = "W";
        //         }

        //             $data =[array('c_run_key'=>$inputs['c_run_key'],'e_rate_season'=>$inputs['e_rate_season'],'i_origin_service_area_id'=>$inputs['i_origin_service_area_id'],'i_dest_service_area_id'=>$inputs['i_dest_service_area_id'],'t_target_time'=>$inputs['t_target_time_arrival'],'e_target_type'=>'Arrive','e_direction'=>$direction_arrival,'e_status'=>$inputs['e_status'],'created_at'=> Carbon::now()),array('c_run_key'=>$inputs['c_run_key'],'e_rate_season'=>$inputs['e_rate_season'],'i_origin_service_area_id'=>$inputs['i_dest_service_area_id'],'i_dest_service_area_id'=>$inputs['i_origin_service_area_id'],'t_target_time'=>$inputs['t_target_time_departure'],'e_target_type'=>'Depart','e_direction'=>$direction_departure,'e_status'=>$inputs['e_status'],'created_at'=> Carbon::now())];
                
        //         if (LineRunTemplate::insert($data)) {
        //             Session::flash('success-message', 'Fleet Information added successfully.');
        //             return '';
        //         }
        //     }


        // }else{
        //     return Redirect(ADMIN_URL . 'line-run-templates');
        // }


    }


}


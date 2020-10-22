<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LineRun;
use App\Models\AdminRoles;
use App\Models\DriverExtension;
use App\Models\FleetManager;
use App\Models\SystemSettings;
use App\Models\CmsPages;
use Carbon\Carbon;
use DateTime;
use DatePeriod;
use DateInterval;
use Mail, Session, Redirect, Validator, DB, Hash, View, parseHexCode;

class KioskInformationController extends BaseController {

    public function getIndex() {
        
        $arrDates = array();
        $arrDates['range_start'] = date('Y-m-d');
        $arrDates['range_end'] = date('Y-m-d', strtotime(date('Y-m-d').' +7 days'));
        $arrDates['range_time'] = date('H:i:s');
        
        
         
        $arrData = LineRun::with(['VehicleFleet' => function($q) {
            $q->where('e_vehicle_status', 'Ready');
            $q->select('id', 'v_vehicle_code');
        }, 'Driver' => function($q1){
            $q1->select('id', 'i_role_id', 'v_firstname', 'v_lastname','v_dispatch_name');
        }, 'Driver.AdminRole' => function($q2){
            $q2->where('v_name', '=', 'Driver');
            $q2->select('id', 'v_name');
        }, 'DriverExtension'])->whereIn('i_origin_service_area_id',[4,5])
                                ->where(DB::raw('DATE(d_run_date)'), '<=', $arrDates['range_end'])
                                ->where(DB::raw('CONCAT(d_run_date," ",t_scheduled_arr_time)'), '>=', $arrDates['range_start'].' '.$arrDates['range_time'])
                                ->orderByRaw("d_run_date,t_scheduled_arr_time ASC")
                                ->get()->toArray();

                                //->where(DB::raw('DATE(d_run_date)'), '>=', $arrDates['range_start'])
        
        $arrSlideData = array();
        foreach($arrData AS $arrRun){
            $runDate = date('Y-m-d', strtotime($arrRun['d_run_date']));
            $arrSlideData[$runDate][] = $arrRun;
        }
        //pr($arrSlideData); exit;

        //block color
        $recordColor = SystemSettings::get();
        $arrColor = (array) json_decode($recordColor[0]['kiosk_params']);

        //font color
        foreach ($arrColor['colors'] as $key => $value) {
            $arrHexCode = array();
            $first_param = array('r','g','b');
            $second_param = str_split(ltrim($value,'#'),2);
            $arrHexCode = array_combine($first_param, $second_param);
            if(((hexdec($arrHexCode['r'])*0.299)+(hexdec($arrHexCode['g'])*0.587)+(hexdec($arrHexCode['b'])*0.114)) > 186 ){
                $strTextColor[] = '#000000'; #dark text;
            } else {
                $strTextColor[] = '#ffffff'; #light text;
            }
        }
        $contant_of_kioskInfo = CmsPages ::where(['v_slug'=>'kiosk-information','e_status'=>'Active'])->first();
        if($contant_of_kioskInfo){
            $contant_of_kioskInfo['t_content'] = str_replace('[SITE_URL]', SITE_URL, $contant_of_kioskInfo['t_content']);
            $contant_of_kioskInfo['t_content'] = str_replace('$title', $contant_of_kioskInfo['v_title'],$contant_of_kioskInfo['t_content']);

            return View('frontend.kiosk-information.index', array('title' =>  $contant_of_kioskInfo['v_title'], "record" => $arrData, "arrDates" => $arrDates, 'arrColor' => $arrColor, 'arrSlideData' => $arrSlideData, "strTextColor" => $strTextColor,'v_meta_description'=>$contant_of_kioskInfo['v_meta_desc'],'v_meta_keywords'=> $contant_of_kioskInfo['v_meta_keywords'],'contant_of_kioskInfo' => $contant_of_kioskInfo));

        }else{
            return View('frontend.404', array('title' => 'Not Found',));
        }

        // pr($strTextColor); exit;

        
    }

}
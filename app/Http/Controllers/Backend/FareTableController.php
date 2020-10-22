<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\FareTable,App\Models\FareClass;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail, Session, Redirect, Validator, DB, Hash;

class FareTableController extends BaseController {

    public function getIndex() {
        $arrResponse = [];
        $rate_codes = FareClass::select('v_rate_code','v_class_desc')->orderBy('id')->get()->toArray();

        $fare_table = new FareTable;
        $fare_table = $fare_table->select('c.v_class_label','a.v_area_label as origin','a2.v_area_label as destination','sys_rate_table.*');
        $fare_table = $fare_table->leftjoin('sys_rate_class_def as c','sys_rate_table.i_rate_class_id','=','c.id');
        $fare_table = $fare_table->leftjoin('geo_service_area as a','sys_rate_table.i_origin_service_area_id','=','a.id');
        $fare_table = $fare_table->leftjoin('geo_service_area as a2','sys_rate_table.i_dest_service_area_id','=','a2.id');
        $fare_table = $fare_table->get()->toArray();
        foreach($fare_table AS $arrFare){
            $strCombo = $arrFare['i_origin_service_area_id'].','.$arrFare['i_dest_service_area_id'];
            $arrResponse[$strCombo]['origin'] = $arrFare['origin'];
            $arrResponse[$strCombo]['destination'] = $arrFare['destination'];
            $arrResponse[$strCombo]['rates'][$arrFare['v_rate_code']] = $arrFare;
        }
        return View('backend.fare_table.index', array('title' => "Fare Table",'rate_codes' => $rate_codes,'fare_table' => $arrResponse));
    }

    public function postSaveFare(Request $request) {
        $data = $request->all();
        $data = json_decode($data['data'],TRUE);

        foreach($data as $fare) {
            FareTable::where('id',$fare['id'])->update(['d_fare_amount' => $fare['amt']]);
        }
        return 'TRUE';
    }
}


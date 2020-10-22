<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\LineRunSetting;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail, Session, Redirect, Validator, DB, Hash;

class LineRunSettingsController extends BaseController {

    public function getIndex(Request $request) {
        $inputs = $request->all();
        
        if($inputs) {
            $record = LineRunSetting::find(1);
            $record->d_peak_start_date = date(SAVE_DATE_FORMAT, strtotime($inputs['d_peak_start_date']));
            $record->d_peak_end_date = date(SAVE_DATE_FORMAT, strtotime($inputs['d_peak_end_date']));
            $record->d_off_season_start_date = date(SAVE_DATE_FORMAT, strtotime($inputs['d_off_season_start_date']));
            $record->d_off_season_end_date = date(SAVE_DATE_FORMAT, strtotime($inputs['d_off_season_end_date']));
            $record->created_at = Carbon::now();
            if($record->save()){
                Session::flash('success-message', 'Line Run Setting edited successfully.');
                return '';
            }
        }
        $data = LineRunSetting::first();
        return View('backend.linerun_setting.index', array('title' => "Line Run Settings", 'data' => $data));
    }

   
}


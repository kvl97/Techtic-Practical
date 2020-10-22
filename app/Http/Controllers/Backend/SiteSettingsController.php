<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SystemSettings;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail, Session, Redirect, Validator, DB, Hash;

class SiteSettingsController extends BaseController {

    public function getIndex(Request $request) {
        $inputs = $request->all();
        
        if($inputs) {
           
           
            $record = SystemSettings::find(1);
            $record->v_site_name = trim($inputs['v_site_name']);
            $record->v_site_description = trim($inputs['v_site_description']);
            $record->v_facebook_link = trim($inputs['v_facebook_link']);
            $record->v_twitter_link = trim($inputs['v_twitter_link']);
            $record->v_comp_tel_1 = trim($inputs['v_comp_tel_1']);
            $record->v_comp_tel_2 = trim($inputs['v_comp_tel_2']);
            $record->v_comp_email = trim($inputs['v_comp_email']);
            $record->v_office_hours = trim($inputs['v_office_hours']);
            $record->us_dot_number  = trim($inputs['us_dot_number']);
            $record->d_refund_process_fee  = trim($inputs['d_refund_process_fee']);
            
            $record->created_at = Carbon::now();
            if($record->save()){
                Session::flash('success-message', 'Site Setting edited successfully.');
                return '';
            }
        }
        $data = SystemSettings::first();
        return View('backend.site_setting.index', array('title' => "Site Settings", 'data' => $data));
    }

   
}


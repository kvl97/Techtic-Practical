<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SystemSettings;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail, Session, Redirect, Validator, DB, Hash;

class SystemSettingsController extends BaseController {

    public function getIndex(Request $request) {
        $inputs = $request->all();
        if($inputs) {
            $colors = SystemSettings::find(1);
            $colors->timestamps = false;
            $colors->kiosk_params = json_encode($inputs);
            $colors->save();
            if(isset($inputs['submit']) && $inputs['submit'] == 1) {
                Session::flash('success-message-kiosk', 'Kiosk settings saved successfully.');
            }
        }
        $colors = SystemSettings::first();
        
        return View('backend.system_setting.index', array('title' => "Kiosk Settings", 'colors' => $colors));
    }
}
?>
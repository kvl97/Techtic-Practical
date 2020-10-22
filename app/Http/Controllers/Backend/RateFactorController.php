<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\FareTable,App\Models\FareClass;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail, Session, Redirect, Validator, DB, Hash;

class RateFactorController extends BaseController {

    public function getIndex() {
        $arrResponse = [];
        $rate_factor = FareClass::where(['deleted_at'=>NULL])->get()->toArray();

        return View('backend.rate_factor.index', array('title' => "Rate Factor",'rate_factor' => $rate_factor,));
    }

    public function postSaveFare(Request $request) {
        $data = $request->all();
        if($data){
            FareClass::where('id',$data['id'])->update(['d_base_rate_factor' => $data['d_base_rate_factor'],'v_tooltip_text' => $data['v_tooltip_text']]);
            return 'TRUE';
        }else{
            return 'FALSE';
        }
    }
}


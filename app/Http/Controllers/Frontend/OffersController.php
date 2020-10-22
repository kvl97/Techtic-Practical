<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Offers;
use App\Models\CmsPages;
use Carbon\Carbon;
use \Stripe\Stripe;

use Mail, Session, Redirect, Validator, DB, Hash;



class OffersController extends BaseController {

   
    public function getOffersInfo(Request $request){
        
        $user = auth()->guard('customers')->user();
        $admin = auth()->guard('admin')->user();
        $today = date("Y-m-d", strtotime(date('Y-m-d')));
        
        if($user) {
            
            $available_offers =  Offers::where(function ($q) use($today) {
                $q->where('d_expire_date','>=', $today);
            })->where(function ($qa) use($user) {
                $qa->where('e_type',$user['e_user_type'])->orWhere('e_type','Both');
            })->orWhere('d_expire_date',NULL)->orderBy('d_expire_date', 'asc')->get();
            
        } elseif($admin) {
            $available_offers =  Offers::where(function ($q) use($today) {
                $q->where('d_expire_date','>=', $today);
            })->where(function ($qa) {
                $qa->where('e_type','Employee')->orWhere('e_type','Both');
            })->orWhere('d_expire_date',NULL)->orderBy('d_expire_date', 'asc')->get();
        } else {
            $available_offers =  Offers::where(function ($q) use($today) {
                $q->where('d_expire_date','>=', $today);
            })->where(function ($qa) {
                $qa->where('e_type','Customer')->orWhere('e_type','Both');
            })->orWhere('d_expire_date',NULL)->orderBy('d_expire_date', 'asc')->get();
        }
        $contant_of_offersPage = CmsPages ::where(['v_slug'=>'offers','e_status'=>'Active'])->first();

        if($contant_of_offersPage) {
            $contant_of_offersPage['t_content'] = str_replace('[SITE_URL]', SITE_URL, $contant_of_offersPage['t_content']);
            $contant_of_offersPage['t_content'] = str_replace('$title', $contant_of_offersPage['v_title'],$contant_of_offersPage['t_content']);

            return View('frontend.offers.available_offers', array('title' =>  $contant_of_offersPage['v_title'],'v_meta_description'=>$contant_of_offersPage['v_meta_desc'],'v_meta_keywords'=> $contant_of_offersPage['v_meta_keywords'],'contant_of_offersPage' => $contant_of_offersPage,'available_offers'=>$available_offers));

        } else {
            return View('frontend.404', array('title' => 'Not Found','contant_of_offersPage' => $contant_of_offersPage));
        }
    }
}    
  

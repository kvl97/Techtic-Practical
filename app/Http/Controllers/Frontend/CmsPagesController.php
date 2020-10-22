<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactUs;
use App\Models\SystemSettings;
use App\Models\EmailTemplate;
use App\Models\CmsPages;
use App\Models\Faqs;
use Carbon\Carbon;

use Mail, Session, Redirect, Validator, DB, Hash;

class CmsPagesController extends BaseController {

    public function getAboutUs(Request $request) {
        $siteSetting = SystemSettings ::first();
        $contant_of_aboutUs = CmsPages ::where(['v_slug'=>'about-us','e_status'=>'Active'])->first();
        if($contant_of_aboutUs){
            $contant_of_aboutUs['t_content'] = str_replace('[SITE_URL]', SITE_URL, $contant_of_aboutUs['t_content']);
            $contant_of_aboutUs['t_content'] = str_replace('$title', $contant_of_aboutUs['v_title'], $contant_of_aboutUs['t_content']);
        
       
            return View('frontend.cms_page.about_us', array('title' => $contant_of_aboutUs['v_title'],'v_meta_description' => $contant_of_aboutUs['v_meta_desc'],'v_meta_keywords'=> $contant_of_aboutUs['v_meta_keywords'],'siteSetting' => $siteSetting,'contant_of_aboutUs' => $contant_of_aboutUs));
        }else {
            return View('frontend.404', array('title' => 'Not Found','siteSetting' => $siteSetting,'contant_of_aboutUs' => $contant_of_aboutUs));
        }
       
        
    }
    public function getPrivacyPolicy(Request $request) {
        $contant_of_privacy_policy = CmsPages ::where(['v_slug'=>'privacy-policy','e_status'=>'Active'])->first();
        $contant_of_privacy_policy['t_content'] = str_replace('[SITE_URL]', SITE_URL, $contant_of_privacy_policy['t_content']);
        $contant_of_privacy_policy['t_content'] = str_replace('$title', $contant_of_privacy_policy['v_title'], $contant_of_privacy_policy['t_content']);

        if($contant_of_privacy_policy['t_content']){

            return View('frontend.cms_page.privacy_policy', array('title' => $contant_of_privacy_policy['v_title'],'v_meta_description' => $contant_of_privacy_policy['v_meta_desc'],'v_meta_keywords'=> $contant_of_privacy_policy['v_meta_keywords'],'contant_of_privacy_policy' => $contant_of_privacy_policy));

        }else {

            return View('frontend.404', array('title' => 'Not Found','contant_of_privacy_policy' => $contant_of_privacy_policy));
        }
    }
    public function getTermsAndConditions(Request $request){
        $contant_of_terms_and_conditions = CmsPages ::where(['v_slug'=>'terms-and-conditions','e_status'=>'Active'])->first();
        $contant_of_terms_and_conditions['t_content'] = str_replace('[SITE_URL]', SITE_URL, $contant_of_terms_and_conditions['t_content']);
        $contant_of_terms_and_conditions['t_content'] = str_replace('$title', $contant_of_terms_and_conditions['v_title'], $contant_of_terms_and_conditions['t_content']);
        
        if($contant_of_terms_and_conditions['t_content']){

            return View('frontend.cms_page.terms_and_conditions', array('title' => $contant_of_terms_and_conditions['v_title'],'v_meta_description' => $contant_of_terms_and_conditions['v_meta_desc'],'v_meta_keywords'=> $contant_of_terms_and_conditions['v_meta_keywords'],'contant_of_terms_and_conditions' => $contant_of_terms_and_conditions));

        } else{
            return View('frontend.404',  array('title' => 'Not Found','contant_of_terms_and_conditions' => $contant_of_terms_and_conditions));
        }
    }
    public function getFaqsInfo(Request $request){
        $faq_info = Faqs ::where('e_status','Active')->orderBy('i_order', 'asc')->get();

        $contant_of_faqs = CmsPages::where(['v_slug'=>'faqs','e_status'=>'Active'])->first();
        $contant_of_faqs['t_content'] = str_replace('[SITE_URL]', SITE_URL, $contant_of_faqs['t_content']); 
        $contant_of_faqs['t_content'] = str_replace('$title', $contant_of_faqs['v_title'], $contant_of_faqs['t_content']);
       
        if($contant_of_faqs['t_content']){
            return View('frontend.cms_page.faqs', array('title' => $contant_of_faqs['v_title'],'faq_info' => $faq_info,'v_meta_description'=>$contant_of_faqs['v_meta_desc'],'v_meta_keywords'=> $contant_of_faqs['v_meta_keywords'],'contant_of_faqs' => $contant_of_faqs));
        } else{
            $contant_of_faqs['t_content'] = "";
            return View('frontend.cms_page.faqs', array('title' => 'Not Found','faq_info' => $faq_info,'contant_of_faqs' => $contant_of_faqs));
        }
        
       
    }
    public function getShuttleSerivces(Request $request){
        $contant_of_shuttle_service = CmsPages ::where(['v_slug'=>'shuttle-services','e_status'=>'Active'])->first();
      
        $contant_of_shuttle_service['t_content'] = str_replace('[SITE_URL]', SITE_URL, $contant_of_shuttle_service['t_content']);
        $contant_of_shuttle_service['t_content'] = str_replace('$title', $contant_of_shuttle_service['v_title'], $contant_of_shuttle_service['t_content']);
        if($contant_of_shuttle_service['t_content']){

            return View('frontend.cms_page.shuttle_services', array('title' => $contant_of_shuttle_service['v_title'],'v_meta_description'=>$contant_of_shuttle_service['v_meta_desc'],'v_meta_keywords'=> $contant_of_shuttle_service['v_meta_keywords'],'contant_of_shuttle_service' => $contant_of_shuttle_service));
        }else {

            return View('frontend.404', array('title' => 'Not Found','contant_of_shuttle_service' => $contant_of_shuttle_service));
        }
    }
    public function getPassengerRules(Request $request){
        $contant_of_passenger_rules = CmsPages ::where(['v_slug'=>'passenger-rules','e_status'=>'Active'])->first();
        $contant_of_passenger_rules['t_content'] = str_replace('[SITE_URL]', SITE_URL, $contant_of_passenger_rules['t_content']);
        $contant_of_passenger_rules['t_content'] = str_replace('$title', $contant_of_passenger_rules['v_title'], $contant_of_passenger_rules['t_content']);

        if($contant_of_passenger_rules['t_content']){

            return View('frontend.cms_page.passenger_rules', array('title' => $contant_of_passenger_rules['v_title'],'v_meta_description'=>$contant_of_passenger_rules['v_meta_desc'],'v_meta_keywords'=> $contant_of_passenger_rules['v_meta_keywords'],'contant_of_passenger_rules' => $contant_of_passenger_rules));

        }else{

            return View('frontend.404', array('title' => 'Not Found','contant_of_passenger_rules' => $contant_of_passenger_rules));
        }
    }
    public function getCharterServices(Request $request){

        $contant_of_charter_services = CmsPages ::where(['v_slug'=>'charter-service','e_status'=>'Active'])->first();
        $contant_of_charter_services['t_content'] = str_replace('[SITE_URL]', SITE_URL, $contant_of_charter_services['t_content']);
        $contant_of_charter_services['t_content'] = str_replace('$title', $contant_of_charter_services['v_title'],$contant_of_charter_services['t_content']);
        

        if($contant_of_charter_services['t_content']){

            return View('frontend.cms_page.charter_service', array('title' =>  $contant_of_charter_services['v_title'],'v_meta_description'=>$contant_of_charter_services['v_meta_desc'],'v_meta_keywords'=> $contant_of_charter_services['v_meta_keywords'],'contant_of_charter_services' => $contant_of_charter_services));

        }else{

            return View('frontend.404', array('title' => 'Not Found','contant_of_charter_services' => $contant_of_charter_services));
        }

    }
    public function getServiceArea(Request $request){

        $contant_of_service_area = CmsPages ::where(['v_slug'=>'service-area','e_status'=>'Active'])->first();
        $contant_of_service_area['t_content'] = str_replace('[SITE_URL]', SITE_URL, $contant_of_service_area['t_content']);
        $contant_of_service_area['t_content'] = str_replace('$title', $contant_of_service_area['v_title'],$contant_of_service_area['t_content']);

        if($contant_of_service_area['t_content']){

            return View('frontend.cms_page.service_area', array('title' => $contant_of_service_area['v_title'],'v_meta_description'=>$contant_of_service_area['v_meta_desc'],'v_meta_keywords'=> $contant_of_service_area['v_meta_keywords'],'contant_of_service_area' => $contant_of_service_area));

        }else{

            return View('frontend.404', array('title' => 'Not Found','contant_of_service_area' => $contant_of_service_area));
        }

    }
    
    public function getNonFixdPages(Request $request,$page_slug){
        
        $contant_of_not_fixed_pages = CmsPages ::where(['v_slug'=>$page_slug,'e_status'=>'Active','e_is_fixed_page'=>'0'])->first();

        if($contant_of_not_fixed_pages){

        
            $contant_of_not_fixed_pages['t_content'] = str_replace('[SITE_URL]', SITE_URL, $contant_of_not_fixed_pages['t_content']);
            $contant_of_not_fixed_pages['t_content'] = str_replace('$title', $contant_of_not_fixed_pages['v_title'],$contant_of_not_fixed_pages['t_content']);
        
            if($contant_of_not_fixed_pages['t_content']){

                return View('frontend.cms_page.non_fixed_pages', array('title' => $contant_of_not_fixed_pages['v_title'],'v_meta_description'=>$contant_of_not_fixed_pages['v_meta_desc'],'v_meta_keywords'=> $contant_of_not_fixed_pages['v_meta_keywords'],'contant_of_not_fixed_pages' => $contant_of_not_fixed_pages));

            }else {

                return View('frontend.404', array('title' => 'Not Found','contant_of_not_fixed_pages' => $contant_of_not_fixed_pages));
            }
        } else {
            return View('frontend.404', array('title' => 'Not Found',));
        }
    }
    
}    
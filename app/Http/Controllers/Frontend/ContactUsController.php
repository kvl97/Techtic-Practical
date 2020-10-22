<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactUs;
use App\Models\SystemSettings;
use App\Models\EmailTemplate;
use App\Models\CmsPages;
use Carbon\Carbon;

use Mail, Session, Redirect, Validator, DB, Hash;

class ContactUsController extends BaseController {

    public function getIndex(Request $request) {
        $post_data = $request->all();
        $siteSetting = SystemSettings ::first();
        
        $contant_of_contactUs = CmsPages ::where(['v_title'=>'Contact Us','e_status'=>'Active'])->first();
        
        if(!empty($post_data)) {
            $validator = Validator::make($request->all(), [
                "v_firstname" => 'required',
                "v_lastname" => 'required',
                "v_email" => 'required',
                "v_phone" => 'required',
            ]);
            $attributeNames = [
                "v_firstname" => 'first name',
                "v_lastname" => 'last name',
                "v_email" => 'e-mail',
                "password" => 'password', 
            ];
            $validator->setAttributeNames($attributeNames);
            if ($validator->fails()) {
                return json_encode([$validator->errors()]);
            }else {
                $modelContactUs= new ContactUs();
                $modelContactUs->v_firstname = $post_data['v_firstname'];
                $modelContactUs->v_lastname = $post_data['v_lastname'];
                $modelContactUs->v_email = $post_data['v_email'];
                $modelContactUs->v_phone = $post_data['v_phone'];
                $modelContactUs->t_message = $post_data['t_message'];
                $modelContactUs->created_at = Carbon::now();

                if($modelContactUs->save()) {

                    $objEmailTemplate = EmailTemplate::find(4)->toArray();
                    $strTemplate = $objEmailTemplate['t_email_content'];
                    $strTemplate = str_replace('[SITE_NAME]', SITE_NAME, $strTemplate);
                    $strTemplate = str_replace('[NAME]',$modelContactUs['v_firstname'].' '.$modelContactUs['v_lastname'],$strTemplate);
                    $strTemplate = str_replace('[EMAIL]',$modelContactUs['v_email'],$strTemplate);
                    $strTemplate = str_replace('[PHONE]',$modelContactUs['v_phone'],$strTemplate);
                    $strTemplate = str_replace('[MESSAGE]',$modelContactUs['t_message'] ? $modelContactUs['t_message'] : '-',$strTemplate);

                    $subject = str_replace('[SITE_NAME]', SITE_NAME, $objEmailTemplate['v_template_subject']);
                  /*  pr($strTemplate);
                   exit; */
                    // mail sent to user with new link
                    
                    Mail::send('emails.auth.generate-email-template', array('strTemplate'=>$strTemplate), function($message) use ($modelContactUs,$subject,$siteSetting)
                    {
                        $message->from(CONTACT_EMAIL_ID, SITE_NAME);
                        $message->to($siteSetting->v_comp_email);
                        $message->replyTo(CONTACT_EMAIL_ID);
                        $message->subject($subject);
                    });      
                    Session::flash('msg','Contact enquiry has been sent successfully.');          
                    return response()->json([
                        'status' => 'TRUE',
                        'redirect_url' => FRONTEND_URL.'contact-us',
                    ]);
                } else {
                    return json_encode(['status' => 'FALSE', 'error' => 'Something went to wrong. Please try again.']);
                }

            }
            
        }else {
            if($contant_of_contactUs){
                return View('frontend.contact_us.index', array('title' => 'Contact Us','v_meta_description'=>$contant_of_contactUs['v_meta_desc'],'v_meta_keywords'=> $contant_of_contactUs['v_meta_keywords'],'siteSetting' => $siteSetting,'contant_of_contactUs' => $contant_of_contactUs));
            }else {
                
                return View('frontend.404', array('title' => 'Contact Us','siteSetting' => $siteSetting,'contant_of_contactUs' => $contant_of_contactUs));
            }
        }
    }
}    
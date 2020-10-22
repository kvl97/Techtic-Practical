<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\HomePageContent;
use Illuminate\Http\Request;
use Carbon\Carbon;

use Mail, Session, Redirect, Validator, DB, Hash, File;

class HomePageContentController extends BaseController {
    
    public function anyBannerImages(Request $request) {
        $inputs  = $request->all();
        // pr($inputs); exit;
        if ($inputs) {

            if($inputs['type_hidden'] == 'Banner Content') {
                $record = HomePageContent::find(1);
                $record->v_title = $inputs['v_title'];
                $record->v_desc = $inputs['v_desc'];
                
                if(isset($inputs['banner_imgbase64'])) {
                    $profileImgPath = HOME_PAGE_IMAGE;
                    if(File::exists(HOME_PAGE_IMAGE .$record->v_image)){
                        @unlink(HOME_PAGE_IMAGE .$record->v_image);
                    }
                    $imageName = $this->saveImage($inputs['banner_imgbase64'],$profileImgPath);
                    $record->v_image = $imageName;
                } else {
                    $record->v_image = $record->v_image;
                }

                if($record->save()) {
                    if(isset($inputs['submit']) && $inputs['submit'] == 1) {
                        Session::flash('success-message', "Record has been updated successfully.");
                        return '';
                    }
                }
            } else if($inputs['type_hidden'] == 'Service Content') {
                $record1 = HomePageContent::find(2);
                $record1->v_title = $inputs['v_title'];
                $record1->v_desc = $inputs['v_desc'];
                if(isset($inputs['page_service_imgbase64_1'])) {
                    $profileImgPath = HOME_PAGE_IMAGE;
                    if(File::exists(HOME_PAGE_IMAGE .$record1->v_image)){
                        @unlink(HOME_PAGE_IMAGE .$record1->v_image);
                    }
                    $imageName = $this->saveImage($inputs['page_service_imgbase64_1'],$profileImgPath);
                    $record1->v_image = $imageName;
                } else {
                    $record1->v_image = $record1->v_image;
                }

                $record2 = HomePageContent::find(3);
                $record2->v_title = $inputs['v_title_2'];
                $record2->v_desc = $inputs['v_desc_2'];
                if(isset($inputs['page_service_imgbase64_2'])) {
                    $profileImgPath = HOME_PAGE_IMAGE;
                    if(File::exists(HOME_PAGE_IMAGE .$record2->v_image)){
                        @unlink(HOME_PAGE_IMAGE .$record2->v_image);
                    }
                    $imageName = $this->saveImage($inputs['page_service_imgbase64_2'],$profileImgPath);
                    $record2->v_image = $imageName;
                } else {
                    $record2->v_image = $record2->v_image;
                }

                $record3 = HomePageContent::find(4);
                $record3->v_title = $inputs['v_title_3'];
                $record3->v_desc = $inputs['v_desc_3'];
                if(isset($inputs['page_service_imgbase64_3'])) {
                    $profileImgPath = HOME_PAGE_IMAGE;
                    if(File::exists(HOME_PAGE_IMAGE .$record3->v_image)){
                        @unlink(HOME_PAGE_IMAGE .$record3->v_image);
                    }
                    $imageName = $this->saveImage($inputs['page_service_imgbase64_3'],$profileImgPath);
                    $record3->v_image = $imageName;
                } else {
                    $record3->v_image = $record3->v_image;
                }

                $record4 = HomePageContent::find(5);
                $record4->v_title = $inputs['v_title_4'];
                $record4->v_desc = $inputs['v_desc_4'];
                if(isset($inputs['page_service_imgbase64_4'])) {
                    $profileImgPath = HOME_PAGE_IMAGE;
                    if(File::exists(HOME_PAGE_IMAGE .$record4->v_image)){
                        @unlink(HOME_PAGE_IMAGE .$record4->v_image);
                    }
                    $imageName = $this->saveImage($inputs['page_service_imgbase64_4'],$profileImgPath);
                    $record4->v_image = $imageName;
                } else {
                    $record4->v_image = $record4->v_image;
                }

                $record5 = HomePageContent::find(6);
                $record5->v_title = $inputs['v_title_5'];
                $record5->v_desc = $inputs['v_desc_5'];
                if(isset($inputs['page_service_imgbase64_5'])) {
                    $profileImgPath = HOME_PAGE_IMAGE;
                    if(File::exists(HOME_PAGE_IMAGE .$record5->v_image)){
                        @unlink(HOME_PAGE_IMAGE .$record5->v_image);
                    }
                    $imageName = $this->saveImage($inputs['page_service_imgbase64_5'],$profileImgPath);
                    $record5->v_image = $imageName;
                } else {
                    $record5->v_image = $record5->v_image;
                }

                $record6 = HomePageContent::find(7);
                $record6->v_title = $inputs['v_title_6'];
                $record6->v_desc = $inputs['v_desc_6'];
                if(isset($inputs['page_service_imgbase64_6'])) {
                    $profileImgPath = HOME_PAGE_IMAGE;
                    if(File::exists(HOME_PAGE_IMAGE .$record6->v_image)){
                        @unlink(HOME_PAGE_IMAGE .$record6->v_image);
                    }
                    $imageName = $this->saveImage($inputs['page_service_imgbase64_6'],$profileImgPath);
                    $record6->v_image = $imageName;
                } else {
                    $record6->v_image = $record6->v_image;
                }

                $record7 = HomePageContent::find(8);
                $record7->v_title = $inputs['v_title_7'];
                $record7->v_desc = $inputs['v_desc_7'];
                if(isset($inputs['page_service_imgbase64_7'])) {
                    $profileImgPath = HOME_PAGE_IMAGE;
                    if(File::exists(HOME_PAGE_IMAGE .$record7->v_image)){
                        @unlink(HOME_PAGE_IMAGE .$record7->v_image);
                    }
                    $imageName = $this->saveImage($inputs['page_service_imgbase64_7'],$profileImgPath);
                    $record7->v_image = $imageName;
                } else {
                    $record7->v_image = $record7->v_image;
                }

                $record8 = HomePageContent::find(9);
                $record8->v_title = $inputs['v_title_8'];
                $record8->v_desc = $inputs['v_desc_8'];
                if(isset($inputs['page_service_imgbase64_8'])) {
                    $profileImgPath = HOME_PAGE_IMAGE;
                    if(File::exists(HOME_PAGE_IMAGE .$record8->v_image)){
                        @unlink(HOME_PAGE_IMAGE .$record8->v_image);
                    }
                    $imageName = $this->saveImage($inputs['page_service_imgbase64_8'],$profileImgPath);
                    $record8->v_image = $imageName;
                } else {
                    $record8->v_image = $record8->v_image;
                }
                
                $record9 = HomePageContent::find(10);
                $record9->v_title = $inputs['v_title_9'];
                $record9->v_desc = $inputs['v_desc_9'];
                if(isset($inputs['page_service_imgbase64_9'])) {
                    $profileImgPath = HOME_PAGE_IMAGE;
                    if(File::exists(HOME_PAGE_IMAGE .$record9->v_image)){
                        @unlink(HOME_PAGE_IMAGE .$record9->v_image);
                    }
                    $imageName = $this->saveImage($inputs['page_service_imgbase64_9'],$profileImgPath);
                    $record9->v_image = $imageName;
                } else {
                    $record9->v_image = $record9->v_image;
                }

                if($record1->save() && $record2->save() && $record3->save() && $record4->save() && $record5->save() && $record6->save() && $record7->save() && $record8->save() && $record9->save()) {
                    if(isset($inputs['submit']) && $inputs['submit'] == 1) {
                        Session::flash('success-message', "Record has been updated successfully.");
                        return '';
                    }
                }
            } else if($inputs['type_hidden'] == 'Footer Content') {
                $record1 = HomePageContent::find(11);
                $record1->v_title = $inputs['v_title'];
                $record1->v_desc = $inputs['v_desc'];
                $record1->v_link = $inputs['v_link'];
                if(isset($inputs['page_service_imgbase64_1'])) {
                    $profileImgPath = HOME_PAGE_IMAGE;
                    if(File::exists(HOME_PAGE_IMAGE .$record1->v_image)){
                        @unlink(HOME_PAGE_IMAGE .$record1->v_image);
                    }
                    $imageName = $this->saveImage($inputs['page_service_imgbase64_1'],$profileImgPath);
                    $record1->v_image = $imageName;
                } else {
                    $record1->v_image = $record1->v_image;
                }

                $record2 = HomePageContent::find(12);
                $record2->v_title = $inputs['v_title_2'];
                $record2->v_desc = $inputs['v_desc_2'];
                $record2->v_link = $inputs['v_link_2'];
                if(isset($inputs['page_service_imgbase64_2'])) {
                    $profileImgPath = HOME_PAGE_IMAGE;
                    if(File::exists(HOME_PAGE_IMAGE .$record2->v_image)){
                        @unlink(HOME_PAGE_IMAGE .$record2->v_image);
                    }
                    $imageName = $this->saveImage($inputs['page_service_imgbase64_2'],$profileImgPath);
                    $record2->v_image = $imageName;
                } else {
                    $record2->v_image = $record2->v_image;
                }

                $record3 = HomePageContent::find(13);
                $record3->v_title = $inputs['v_title_3'];
                $record3->v_desc = $inputs['v_desc_3'];
                $record3->v_link = $inputs['v_link_3'];
                if(isset($inputs['page_service_imgbase64_3'])) {
                    $profileImgPath = HOME_PAGE_IMAGE;
                    if(File::exists(HOME_PAGE_IMAGE .$record3->v_image)){
                        @unlink(HOME_PAGE_IMAGE .$record3->v_image);
                    }
                    $imageName = $this->saveImage($inputs['page_service_imgbase64_3'],$profileImgPath);
                    $record3->v_image = $imageName;
                } else {
                    $record3->v_image = $record3->v_image;
                }

                $record4 = HomePageContent::find(14);
                $record4->v_title = $inputs['v_title_4'];
                $record4->v_desc = $inputs['v_desc_4'];
                $record4->v_link = $inputs['v_link_4'];
                if(isset($inputs['page_service_imgbase64_4'])) {
                    $profileImgPath = HOME_PAGE_IMAGE;
                    if(File::exists(HOME_PAGE_IMAGE .$record4->v_image)){
                        @unlink(HOME_PAGE_IMAGE .$record4->v_image);
                    }
                    $imageName = $this->saveImage($inputs['page_service_imgbase64_4'],$profileImgPath);
                    $record4->v_image = $imageName;
                } else {
                    $record4->v_image = $record4->v_image;
                }
                if($record1->save() && $record2->save() && $record3->save() && $record4->save()) {
                    if(isset($inputs['submit']) && $inputs['submit'] == 1) {
                        Session::flash('success-message', "Record has been updated successfully.");
                        return '';
                    }
                }
            }
            
        } else {
            $record_footer = HomePageContent::whereIn('id', [11,12,13,14])->get()->toArray();
            $record_banner = HomePageContent::where('e_type', 'Banner')->get()->toArray();
            $record_service = HomePageContent::where('e_type', 'Service-info')->get()->toArray();
            return View('backend.home_page.home_page_content', array('title' => 'Home Page Content', 'record_footer' => $record_footer, 'record_banner' => $record_banner, 'record_service' => $record_service));
        }
        return Redirect(ADMIN_URL . 'banner-images');
    }

}
?>
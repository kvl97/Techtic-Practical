@extends('backend.layouts.default')
@section('content')

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

  <!-- begin:: Subheader -->
  <div class="kt-subheader   kt-grid__item" id="kt_subheader">
    
  </div>

  <!-- end:: Subheader -->

  <!-- begin:: Content -->
  <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <!--begin::Portlet-->
    <div class="row">
      <div class="col-lg-12">

        <!--begin::Portlet-->
        <div class="kt-portlet">
          <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fas fa-home"></i>
                </span>
              <h3 class="kt-portlet__head-title">
                Home Page Content
              </h3>
            </div>
          </div>

          <!--begin::Form-->
          
          <div class="kt-portlet__body">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#kt_tabs_1_1" data-target="#kt_tabs_1_1">Banner Info.</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#kt_tabs_1_2"> Transportation Facilities Info.</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#kt_tabs_1_3">Service Info.</a>
                </li>                
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="kt_tabs_1_1" role="tabpanel">
                    <form class="kt-form kt-form--label-right home_page_content" id="home_page_1" action="{{ ADMIN_URL }}home-page-content">

                        <input type="hidden" class="form-control" name="type_hidden" value="Banner Content">
                        <input type="hidden" class="form-control" name="submit" value="1">

                        <div class="form-group row">
                            <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Title</label>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                            <input type="text" class="form-control" name="v_title" placeholder="Title" value="{{ $record_banner[0]['v_title'] }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Tag Line</label>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                            <input type="text" class="form-control" name="v_desc" placeholder="Tag Line" value="{{ $record_banner[0]['v_desc'] }}">                  
                            </div>
                        </div>
                    
                        <div class="form-group row">
                            <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Image
                            </label>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="fileinput" data-provides="fileinput">
                                    <img width="200" src="<?php
                                    if (File::exists(HOME_PAGE_IMAGE . $record_banner[0]['v_image']) && $record_banner[0]['v_image'] != '') {
                                        echo SITE_URL . HOME_PAGE_IMAGE .$record_banner[0]['v_image'];
                                    } else {
                                        echo ASSET_URL . 'images/no_image.png';
                                    }
                                    ?>" class="img-responsive default_img_size" name="profileimg"  alt="" id="banner_pic" />

                                    <img  width="200" src="{{ ASSET_URL.'images/no_image.png'}}" class="img-responsive default_img_size" id="default_banner_pic" style="display: none;"/>
                                </div>
                                <div class="clearfix"></div>
                                <div style="margin-top:20px">
                                    <button class="btn btn-default" type="button" id="file_trriger_banner"><?php echo File::exists(HOME_PAGE_IMAGE . $record_banner[0]['v_image']) && $record_banner[0]['v_image'] != '' ? 'Change' : 'Select' ?></button>
                                    <button class="btn btn-default" type="button" id="remove_image_banner" 
                                        <?php echo File::exists(HOME_PAGE_IMAGE . $record_banner[0]['v_image']) && $record_banner[0]['v_image'] != '' ? "" : 'style="display:none;"'; ?>
                                    >Remove</button>
                                    <br><br>
                                </div>
                                <input type="file" data-isshow="true" id="image_change_banner" style="display: none;" />
                                <input type="hidden" id="banner_image" name="banner_image" value=""/>
                                <input type="hidden" id="default_img_banner" name="default_img_banner" value="0"/>
                                <input type="hidden" name="banner_imgbase64" value="" id="banner_imgbase64" />
                            </div>
                        </div>  
                
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-9 ml-lg-auto">
                                <button type="submit" class="btn btn-brand banner-submit-btn">Submit</button>
                                </div>
                            </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="tab-pane" id="kt_tabs_1_2" role="tabpanel">
                    <form class="kt-form kt-form--label-right home_page_content" id="home_page_2" action="{{ ADMIN_URL }}home-page-content">

                        <input type="hidden" class="form-control" name="type_hidden" value="Service Content">
                        <input type="hidden" class="form-control" name="submit" value="1">


                        <div class="row page-service-row">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group row page-service-input">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Title</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <input type="text" class="form-control" name="v_title" placeholder="Title" value="{{ $record_service[0]['v_title'] }}">
                                    </div>
                                </div>

                                <div class="form-group row page-service-input">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Description</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <textarea type="text" class="form-control"  name="v_desc" placeholder="Description" rows="5"> {{ $record_service[0]['v_desc'] }} </textarea>                 
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="row">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Image
                                    </label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <div class="fileinput" data-provides="fileinput">
                                            <img width="200" src="<?php
                                            if (File::exists(HOME_PAGE_IMAGE . $record_service[0]['v_image']) && $record_service[0]['v_image'] != '') {
                                                echo SITE_URL . HOME_PAGE_IMAGE .$record_service[0]['v_image'];
                                            } else {
                                                echo ASSET_URL . 'images/no_image.png';
                                            }
                                            ?>" class="img-responsive default_img_size" name="profileimg"  alt="" id="trans_facility_pic" />

                                            <img  width="200" src="{{ ASSET_URL.'images/no_image.png'}}" class="img-responsive default_img_size" id="default_trans_facility_pic" style="display: none;"/>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div style="margin-top:20px">
                                            <button class="btn btn-default" type="button" id="file_trriger_trans_facility"><?php echo File::exists(HOME_PAGE_IMAGE . $record_service[0]['v_image']) && $record_service[0]['v_image'] != '' ? 'Change' : 'Select' ?></button>
                                            <button class="btn btn-default" type="button" id="remove_image_trans_facility" 
                                                <?php echo File::exists(HOME_PAGE_IMAGE . $record_service[0]['v_image']) && $record_service[0]['v_image'] != '' ? "" : 'style="display:none;"'; ?>
                                            >Remove</button>
                                            <br><br>
                                        </div>
                                        <input type="file" data-isshow="true" id="image_change_trans_facility" style="display: none;" />
                                        <input type="hidden" id="trans_facility_image" name="trans_facility_image" value=""/>
                                        <input type="hidden" id="default_img_trans_facility" name="default_img_trans_facility" value="0"/>
                                        <input type="hidden" name="trans_facility_imgbase64_1" value="" id="trans_facility_imgbase64_1" />
                                    </div>
                                </div>
                            </div> -->
                        </div>

                        <div class="row page-service-row">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group row page-service-input">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Title</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <input type="text" class="form-control" name="v_title_2" placeholder="Title" value="{{ $record_service[1]['v_title'] }}">
                                    </div>
                                </div>

                                <div class="form-group row page-service-input">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Description</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <textarea type="text" class="form-control"  name="v_desc_2" placeholder="Description" rows="5"> {{ $record_service[1]['v_desc'] }} </textarea>                  
                                    </div>
                                </div>

                            </div>
                            <!--<div class="col-md-6 col-lg-6 col-sm-12 row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Image
                                </label>
                                <div class="col-lg-8 col-md-8 col-sm-12">
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <div class="fileinput" data-provides="fileinput">
                                            <img width="200" src="<?php
                                            if (File::exists(HOME_PAGE_IMAGE . $record_service[1]['v_image']) && $record_service[1]['v_image'] != '') {
                                                echo SITE_URL . HOME_PAGE_IMAGE .$record_service[1]['v_image'];
                                            } else {
                                                echo ASSET_URL . 'images/no_image.png';
                                            }
                                            ?>" class="img-responsive default_img_size" name="profileimg"  alt="" id="trans_facility_pic_2" />

                                            <img  width="200" src="{{ ASSET_URL.'images/no_image.png'}}" class="img-responsive default_img_size" id="default_trans_facility_pic_2" style="display: none;"/>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div style="margin-top:20px">
                                            <button class="btn btn-default" type="button" id="file_trriger_trans_facility_2"><?php echo File::exists(HOME_PAGE_IMAGE . $record_service[1]['v_image']) && $record_service[1]['v_image'] != '' ? 'Change' : 'Select' ?></button>
                                            <button class="btn btn-default" type="button" id="remove_image_trans_facility_2" 
                                                <?php echo File::exists(HOME_PAGE_IMAGE . $record_service[1]['v_image']) && $record_service[1]['v_image'] != '' ? "" : 'style="display:none;"'; ?>
                                            >Remove</button>
                                            <br><br>
                                        </div>
                                        <input type="file" data-isshow="true" id="image_change_trans_facility_2" style="display: none;" />
                                        <input type="hidden" id="trans_facility_image_2" name="trans_facility_image_2" value=""/>
                                        <input type="hidden" id="default_img_trans_facility_2" name="default_img_trans_facility_2" value="0"/>
                                        <input type="hidden" name="trans_facility_imgbase64_2" value="" id="trans_facility_imgbase64_2" />
                                    </div>
                                </div>
                            </div> -->
                        </div>

                        <div class="row page-service-row">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group row page-service-input">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Title</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <input type="text" class="form-control" name="v_title_3" placeholder="Title" value="{{ $record_service[2]['v_title'] }}">
                                    </div>
                                </div>

                                <div class="form-group row page-service-input">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Description</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <textarea type="text" class="form-control"  name="v_desc_3" placeholder="Description" rows="5"> {{ $record_service[2]['v_desc'] }} </textarea>                  
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-md-6 col-lg-6 col-sm-12 row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Image
                                </label>
                                <div class="col-lg-8 col-md-8 col-sm-12">
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <div class="fileinput" data-provides="fileinput">
                                            <img width="200" src="<?php
                                            if (File::exists(HOME_PAGE_IMAGE . $record_service[2]['v_image']) && $record_service[2]['v_image'] != '') {
                                                echo SITE_URL . HOME_PAGE_IMAGE .$record_service[2]['v_image'];
                                            } else {
                                                echo ASSET_URL . 'images/no_image.png';
                                            }
                                            ?>" class="img-responsive default_img_size" name="profileimg"  alt="" id="trans_facility_pic_3" />

                                            <img  width="200" src="{{ ASSET_URL.'images/no_image.png'}}" class="img-responsive default_img_size" id="default_trans_facility_pic_3" style="display: none;"/>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div style="margin-top:20px">
                                            <button class="btn btn-default" type="button" id="file_trriger_trans_facility_3"><?php echo File::exists(HOME_PAGE_IMAGE . $record_service[2]['v_image']) && $record_service[2]['v_image'] != '' ? 'Change' : 'Select' ?></button>
                                            <button class="btn btn-default" type="button" id="remove_image_trans_facility_3" 
                                                <?php echo File::exists(HOME_PAGE_IMAGE . $record_service[2]['v_image']) && $record_service[2]['v_image'] != '' ? "" : 'style="display:none;"'; ?>
                                            >Remove</button>
                                            <br><br>
                                        </div>
                                        <input type="file" data-isshow="true" id="image_change_trans_facility_3" style="display: none;" />
                                        <input type="hidden" id="trans_facility_image_3" name="trans_facility_image_3" value=""/>
                                        <input type="hidden" id="default_img_trans_facility_3" name="default_img_trans_facility_3" value="0"/>
                                        <input type="hidden" name="trans_facility_imgbase64_3" value="" id="trans_facility_imgbase64_3" />
                                    </div>
                                </div>
                            </div> -->
                        </div>

                        <div class="row page-service-row">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group row page-service-input">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Title</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <input type="text" class="form-control" name="v_title_4" placeholder="Title" value="{{ $record_service[3]['v_title'] }}">
                                    </div>
                                </div>

                                <div class="form-group row page-service-input">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Description</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <textarea type="text" class="form-control" name="v_desc_4" placeholder="Description" rows="5"> {{ $record_service[3]['v_desc'] }} </textarea>                  
                                    </div>
                                </div>

                            </div>
                            <!-- <div class="col-md-6 col-lg-6 col-sm-12 row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Image
                                </label>
                                <div class="col-lg-8 col-md-8 col-sm-12">
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <div class="fileinput" data-provides="fileinput">
                                            <img width="200" src="<?php
                                            if (File::exists(HOME_PAGE_IMAGE . $record_service[3]['v_image']) && $record_service[3]['v_image'] != '') {
                                                echo SITE_URL . HOME_PAGE_IMAGE .$record_service[3]['v_image'];
                                            } else {
                                                echo ASSET_URL . 'images/no_image.png';
                                            }
                                            ?>" class="img-responsive default_img_size" name="profileimg"  alt="" id="trans_facility_pic_4" />

                                            <img  width="200" src="{{ ASSET_URL.'images/no_image.png'}}" class="img-responsive default_img_size" id="default_trans_facility_pic_4" style="display: none;"/>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div style="margin-top:20px">
                                            <button class="btn btn-default" type="button" id="file_trriger_trans_facility_4"><?php echo File::exists(HOME_PAGE_IMAGE . $record_service[3]['v_image']) && $record_service[3]['v_image'] != '' ? 'Change' : 'Select' ?></button>
                                            <button class="btn btn-default" type="button" id="remove_image_trans_facility_4" 
                                                <?php echo File::exists(HOME_PAGE_IMAGE . $record_service[3]['v_image']) && $record_service[3]['v_image'] != '' ? "" : 'style="display:none;"'; ?>
                                            >Remove</button>
                                            <br><br>
                                        </div>
                                        <input type="file" data-isshow="true" id="image_change_trans_facility_4" style="display: none;" />
                                        <input type="hidden" id="trans_facility_image_4" name="trans_facility_image_4" value=""/>
                                        <input type="hidden" id="default_img_trans_facility_4" name="default_img_trans_facility_4" value="0"/>
                                        <input type="hidden" name="trans_facility_imgbase64_4" value="" id="trans_facility_imgbase64_4" />
                                    </div>
                                </div>
                            </div> -->
                        </div>

                        <div class="row page-service-row">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group row page-service-input">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Title</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <input type="text" class="form-control" name="v_title_5" placeholder="Title" value="{{ $record_service[4]['v_title'] }}">
                                    </div>
                                </div>

                                <div class="form-group row page-service-input">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Description</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <textarea type="text" class="form-control"  name="v_desc_5" placeholder="Description" rows="5"> {{ $record_service[4]['v_desc'] }} </textarea>                 
                                    </div>
                                </div>

                            </div>
                            <!--<div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="row">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Image
                                    </label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <div class="fileinput" data-provides="fileinput">
                                            <img width="200" src="<?php
                                            if (File::exists(HOME_PAGE_IMAGE . $record_service[4]['v_image']) && $record_service[4]['v_image'] != '') {
                                                echo SITE_URL . HOME_PAGE_IMAGE .$record_service[4]['v_image'];
                                            } else {
                                                echo ASSET_URL . 'images/no_image.png';
                                            }
                                            ?>" class="img-responsive default_img_size" name="profileimg"  alt="" id="trans_facility_pic_5" />

                                            <img  width="200" src="{{ ASSET_URL.'images/no_image.png'}}" class="img-responsive default_img_size" id="default_trans_facility_pic_5" style="display: none;"/>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div style="margin-top:20px">
                                            <button class="btn btn-default" type="button" id="file_trriger_trans_facility_5"><?php echo File::exists(HOME_PAGE_IMAGE . $record_service[4]['v_image']) && $record_service[4]['v_image'] != '' ? 'Change' : 'Select' ?></button>
                                            <button class="btn btn-default" type="button" id="remove_image_trans_facility_5" 
                                                <?php echo File::exists(HOME_PAGE_IMAGE . $record_service[4]['v_image']) && $record_service[4]['v_image'] != '' ? "" : 'style="display:none;"'; ?>
                                            >Remove</button>
                                            <br><br>
                                        </div>
                                        <input type="file" data-isshow="true" id="image_change_trans_facility_5" style="display: none;" />
                                        <input type="hidden" id="trans_facility_image_5" name="trans_facility_image_5" value=""/>
                                        <input type="hidden" id="default_img_trans_facility_5" name="default_img_trans_facility_5" value="0"/>
                                        <input type="hidden" name="trans_facility_imgbase64_5" value="" id="trans_facility_imgbase64_5" />
                                    </div>
                                </div>
                            </div> -->
                        </div>

                        <div class="row page-service-row">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group row page-service-input">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Title</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <input type="text" class="form-control" name="v_title_6" placeholder="Title" value="{{ $record_service[5]['v_title'] }}">
                                    </div>
                                </div>

                                <div class="form-group row page-service-input">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Description</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <textarea type="text" class="form-control"  name="v_desc_6" placeholder="Description" rows="5"> {{ $record_service[5]['v_desc'] }} </textarea>                  
                                    </div>
                                </div>

                            </div>
                            <!--<div class="col-md-6 col-lg-6 col-sm-12 row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Image
                                </label>
                                <div class="col-lg-8 col-md-8 col-sm-12">
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <div class="fileinput" data-provides="fileinput">
                                            <img width="200" src="<?php
                                            if (File::exists(HOME_PAGE_IMAGE . $record_service[5]['v_image']) && $record_service[5]['v_image'] != '') {
                                                echo SITE_URL . HOME_PAGE_IMAGE .$record_service[5]['v_image'];
                                            } else {
                                                echo ASSET_URL . 'images/no_image.png';
                                            }
                                            ?>" class="img-responsive default_img_size" name="profileimg"  alt="" id="trans_facility_pic_6" />

                                            <img  width="200" src="{{ ASSET_URL.'images/no_image.png'}}" class="img-responsive default_img_size" id="default_trans_facility_pic_6" style="display: none;"/>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div style="margin-top:20px">
                                            <button class="btn btn-default" type="button" id="file_trriger_trans_facility_6"><?php echo File::exists(HOME_PAGE_IMAGE . $record_service[5]['v_image']) && $record_service[5]['v_image'] != '' ? 'Change' : 'Select' ?></button>
                                            <button class="btn btn-default" type="button" id="remove_image_trans_facility_6" 
                                                <?php echo File::exists(HOME_PAGE_IMAGE . $record_service[5]['v_image']) && $record_service[5]['v_image'] != '' ? "" : 'style="display:none;"'; ?>
                                            >Remove</button>
                                            <br><br>
                                        </div>
                                        <input type="file" data-isshow="true" id="image_change_trans_facility_6" style="display: none;" />
                                        <input type="hidden" id="trans_facility_image_6" name="trans_facility_image_6" value=""/>
                                        <input type="hidden" id="default_img_trans_facility_6" name="default_img_trans_facility_6" value="0"/>
                                        <input type="hidden" name="trans_facility_imgbase64_6" value="" id="trans_facility_imgbase64_6" />
                                    </div>
                                </div>
                            </div> -->
                        </div>

                        <div class="row page-service-row">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group row page-service-input">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Title</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <input type="text" class="form-control" name="v_title_7" placeholder="Title" value="{{ $record_service[6]['v_title'] }}">
                                    </div>
                                </div>

                                <div class="form-group row page-service-input">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Description</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <textarea type="text" class="form-control"  name="v_desc_7" placeholder="Description" rows="5"> {{ $record_service[6]['v_desc'] }} </textarea>        
                                    </div>
                                </div>
                            </div>
                            <!--<div class="col-md-6 col-lg-6 col-sm-12 row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Image
                                </label>
                                <div class="col-lg-8 col-md-8 col-sm-12">
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <div class="fileinput" data-provides="fileinput">
                                            <img width="200" src="<?php
                                            if (File::exists(HOME_PAGE_IMAGE . $record_service[6]['v_image']) && $record_service[6]['v_image'] != '') {
                                                echo SITE_URL . HOME_PAGE_IMAGE .$record_service[6]['v_image'];
                                            } else {
                                                echo ASSET_URL . 'images/no_image.png';
                                            }
                                            ?>" class="img-responsive default_img_size" name="profileimg"  alt="" id="trans_facility_pic_7" />

                                            <img  width="200" src="{{ ASSET_URL.'images/no_image.png'}}" class="img-responsive default_img_size" id="default_trans_facility_pic_7" style="display: none;"/>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div style="margin-top:20px">
                                            <button class="btn btn-default" type="button" id="file_trriger_trans_facility_7"><?php echo File::exists(HOME_PAGE_IMAGE . $record_service[6]['v_image']) && $record_service[6]['v_image'] != '' ? 'Change' : 'Select' ?></button>
                                            <button class="btn btn-default" type="button" id="remove_image_trans_facility_7" 
                                                <?php echo File::exists(HOME_PAGE_IMAGE . $record_service[6]['v_image']) && $record_service[6]['v_image'] != '' ? "" : 'style="display:none;"'; ?>
                                            >Remove</button>
                                            <br><br>
                                        </div>
                                        <input type="file" data-isshow="true" id="image_change_trans_facility_7" style="display: none;" />
                                        <input type="hidden" id="trans_facility_image_7" name="trans_facility_image_7" value=""/>
                                        <input type="hidden" id="default_img_trans_facility_7" name="default_img_trans_facility_7" value="0"/>
                                        <input type="hidden" name="trans_facility_imgbase64_7" value="" id="trans_facility_imgbase64_7" />
                                    </div>
                                </div>
                            </div> -->
                        </div>

                        <div class="row page-service-row">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group row page-service-input">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Title</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <input type="text" class="form-control" name="v_title_8" placeholder="Title" value="{{ $record_service[7]['v_title'] }}">
                                    </div>
                                </div>

                                <div class="form-group row page-service-input">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Description</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <textarea type="text" class="form-control"  name="v_desc_8" placeholder="Description" rows="5"> {{ $record_service[7]['v_desc'] }} </textarea>        
                                    </div>
                                </div>

                            </div>
                            <!--<div class="col-md-6 col-lg-6 col-sm-12 row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Image
                                </label>
                                <div class="col-lg-8 col-md-8 col-sm-12">
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <div class="fileinput" data-provides="fileinput">
                                            <img width="200" src="<?php
                                            if (File::exists(HOME_PAGE_IMAGE . $record_service[7]['v_image']) && $record_service[7]['v_image'] != '') {
                                                echo SITE_URL . HOME_PAGE_IMAGE .$record_service[7]['v_image'];
                                            } else {
                                                echo ASSET_URL . 'images/no_image.png';
                                            }
                                            ?>" class="img-responsive default_img_size" name="profileimg"  alt="" id="trans_facility_pic_8" />

                                            <img  width="200" src="{{ ASSET_URL.'images/no_image.png'}}" class="img-responsive default_img_size" id="default_trans_facility_pic_8" style="display: none;"/>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div style="margin-top:20px">
                                            <button class="btn btn-default" type="button" id="file_trriger_trans_facility_8"><?php echo File::exists(HOME_PAGE_IMAGE . $record_service[7]['v_image']) && $record_service[7]['v_image'] != '' ? 'Change' : 'Select' ?></button>
                                            <button class="btn btn-default" type="button" id="remove_image_trans_facility_8" 
                                                <?php echo File::exists(HOME_PAGE_IMAGE . $record_service[7]['v_image']) && $record_service[7]['v_image'] != '' ? "" : 'style="display:none;"'; ?>
                                            >Remove</button>
                                            <br><br>
                                        </div>
                                        <input type="file" data-isshow="true" id="image_change_trans_facility_8" style="display: none;" />
                                        <input type="hidden" id="trans_facility_image_8" name="trans_facility_image_8" value=""/>
                                        <input type="hidden" id="default_img_trans_facility_8" name="default_img_trans_facility_8" value="0"/>
                                        <input type="hidden" name="trans_facility_imgbase64_8" value="" id="trans_facility_imgbase64_8" />
                                    </div>
                                </div>
                            </div> -->
                        </div>

                        <div class="row page-service-row">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group row page-service-input">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Title</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <input type="text" class="form-control" name="v_title_9" placeholder="Title" value="{{ $record_service[8]['v_title'] }}">
                                    </div>
                                </div>

                                <div class="form-group row page-service-input">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Description</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <textarea type="text" class="form-control"  name="v_desc_9" placeholder="Description" rows="5"> {{ $record_service[8]['v_desc'] }} </textarea>                
                                    </div>
                                </div>

                            </div>
                            <!--<div class="col-md-6 col-lg-6 col-sm-12 row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Image
                                </label>
                                <div class="col-lg-8 col-md-8 col-sm-12">
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <div class="fileinput" data-provides="fileinput">
                                            <img width="200" src="<?php
                                            if (File::exists(HOME_PAGE_IMAGE . $record_service[8]['v_image']) && $record_service[8]['v_image'] != '') {
                                                echo SITE_URL . HOME_PAGE_IMAGE .$record_service[8]['v_image'];
                                            } else {
                                                echo ASSET_URL . 'images/no_image.png';
                                            }
                                            ?>" class="img-responsive default_img_size" name="profileimg"  alt="" id="trans_facility_pic_9" />

                                            <img  width="200" src="{{ ASSET_URL.'images/no_image.png'}}" class="img-responsive default_img_size" id="default_trans_facility_pic_9" style="display: none;"/>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div style="margin-top:20px">
                                            <button class="btn btn-default" type="button" id="file_trriger_trans_facility_9"><?php echo File::exists(HOME_PAGE_IMAGE . $record_service[8]['v_image']) && $record_service[8]['v_image'] != '' ? 'Change' : 'Select' ?></button>
                                            <button class="btn btn-default" type="button" id="remove_image_trans_facility_9" 
                                                <?php echo File::exists(HOME_PAGE_IMAGE . $record_service[8]['v_image']) && $record_service[8]['v_image'] != '' ? "" : 'style="display:none;"'; ?>
                                            >Remove</button>
                                            <br><br>
                                        </div>
                                        <input type="file" data-isshow="true" id="image_change_trans_facility_9" style="display: none;" />
                                        <input type="hidden" id="trans_facility_image_9" name="trans_facility_image_9" value=""/>
                                        <input type="hidden" id="default_img_trans_facility_9" name="default_img_trans_facility_9" value="0"/>
                                        <input type="hidden" name="trans_facility_imgbase64_9" value="" id="trans_facility_imgbase64_9" />
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <div class="row">
                                <div class="col-lg-9 ml-lg-auto">
                                    <button type="submit" class="btn btn-brand service-submit-btn">Submit</button>
                                </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="tab-pane" id="kt_tabs_1_3" role="tabpanel">
                    <form class="kt-form kt-form--label-right home_page_content" id="home_page_3" action="{{ ADMIN_URL }}home-page-content">

                        <input type="hidden" class="form-control" name="type_hidden" value="Footer Content">
                        <input type="hidden" class="form-control" name="submit" value="1">

                        <div class="row page-service-row">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group row page-service-input">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Title</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <input type="text" class="form-control" name="v_title" placeholder="Title" value="{{ $record_footer[0]['v_title'] }}">
                                    </div>
                                </div>

                                <div class="form-group row page-service-input">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Description</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <textarea type="text" class="form-control"  name="v_desc" placeholder="Description" rows="4"  maxlength="120"> {{ $record_footer[0]['v_desc'] }} </textarea>                 
                                    </div>
                                </div>

                                <div class="form-group row page-service-input">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Link</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <input type="text" class="form-control url" name="v_link" placeholder="Link" value="{{ $record_footer[0]['v_link'] }}">                  
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12 row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12 footer-service-image-align">Image
                                </label>
                                <div class="col-lg-8 col-md-8 col-sm-12">
                                    
                                        <div class="fileinput" data-provides="fileinput">
                                            <img width="200" src="<?php
                                            if (File::exists(HOME_PAGE_IMAGE . $record_footer[0]['v_image']) && $record_footer[0]['v_image'] != '') {
                                                echo SITE_URL . HOME_PAGE_IMAGE .$record_footer[0]['v_image'];
                                            } else {
                                                echo ASSET_URL . 'images/no_image.png';
                                            }
                                            ?>" class="img-responsive default_img_size" name="profileimg"  alt="" id="page_service_pic" />

                                            <img  width="200" src="{{ ASSET_URL.'images/no_image.png'}}" class="img-responsive default_img_size" id="default_page_service_pic" style="display: none;"/>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div style="margin-top:20px">
                                            <button class="btn btn-default" type="button" id="file_trriger_page_service"><?php echo File::exists(HOME_PAGE_IMAGE . $record_footer[0]['v_image']) && $record_footer[0]['v_image'] != '' ? 'Change' : 'Select' ?></button>
                                            <button class="btn btn-default" type="button" id="remove_image_page_service" 
                                                <?php echo File::exists(HOME_PAGE_IMAGE . $record_footer[0]['v_image']) && $record_footer[0]['v_image'] != '' ? "" : 'style="display:none;"'; ?>
                                            >Remove</button>
                                            <br><br>
                                        </div>
                                        <input type="file" data-isshow="true" id="image_change_page_service" style="display: none;" />
                                        <input type="hidden" id="page_service_image" name="page_service_image" value=""/>
                                        <input type="hidden" id="default_img_page_service" name="default_img_page_service" value="0"/>
                                        <input type="hidden" name="page_service_imgbase64_1" value="" id="page_service_imgbase64_1" />
                                    
                                </div>
                            </div>
                        </div>

                        <div class="row page-service-row">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group row page-service-input">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Title</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <input type="text" class="form-control" name="v_title_2" placeholder="Title" value="{{ $record_footer[1]['v_title'] }}">
                                    </div>
                                </div>

                                <div class="form-group row page-service-input">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Description</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <textarea type="text" class="form-control" name="v_desc_2" placeholder="Description" rows="4" maxlength="120"> {{ $record_footer[1]['v_desc'] }} </textarea>                  
                                    </div>
                                </div>

                                <div class="form-group row page-service-input">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Link</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <input type="text" class="form-control url" name="v_link_2" placeholder="Link" value="{{ $record_footer[1]['v_link'] }}">                  
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12 row ">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12 footer-service-image-align">Image
                                </label>
                                <div class="col-lg-8 col-md-8 col-sm-12">
                                    
                                        <div class="fileinput" data-provides="fileinput">
                                            <img width="200" src="<?php
                                            if (File::exists(HOME_PAGE_IMAGE . $record_footer[1]['v_image']) && $record_footer[1]['v_image'] != '') {
                                                echo SITE_URL . HOME_PAGE_IMAGE .$record_footer[1]['v_image'];
                                            } else {
                                                echo ASSET_URL . 'images/no_image.png';
                                            }
                                            ?>" class="img-responsive default_img_size" name="profileimg"  alt="" id="page_service_pic_2" />

                                            <img  width="200" src="{{ ASSET_URL.'images/no_image.png'}}" class="img-responsive default_img_size" id="default_page_service_pic_2" style="display: none;"/>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div style="margin-top:20px">
                                            <button class="btn btn-default" type="button" id="file_trriger_page_service_2"><?php echo File::exists(HOME_PAGE_IMAGE . $record_footer[1]['v_image']) && $record_footer[1]['v_image'] != '' ? 'Change' : 'Select' ?></button>
                                            <button class="btn btn-default" type="button" id="remove_image_page_service_2" 
                                                <?php echo File::exists(HOME_PAGE_IMAGE . $record_footer[1]['v_image']) && $record_footer[1]['v_image'] != '' ? "" : 'style="display:none;"'; ?>
                                            >Remove</button>
                                            <br><br>
                                        </div>
                                        <input type="file" data-isshow="true" id="image_change_page_service_2" style="display: none;" />
                                        <input type="hidden" id="page_service_image_2" name="page_service_image_2" value=""/>
                                        <input type="hidden" id="default_img_page_service_2" name="default_img_page_service_2" value="0"/>
                                        <input type="hidden" name="page_service_imgbase64_2" value="" id="page_service_imgbase64_2" />
                                    
                                </div>
                            </div>
                        </div>

                        <div class="row page-service-row">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group row page-service-input">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Title</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <input type="text" class="form-control" name="v_title_3" placeholder="Title" value="{{ $record_footer[2]['v_title'] }}">
                                    </div>
                                </div>

                                <div class="form-group row page-service-input">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Description</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <textarea type="text" class="form-control"  name="v_desc_3" placeholder="Description" rows="4" maxlength="120"> {{ $record_footer[2]['v_desc'] }} </textarea>                 
                                    </div>
                                </div>

                                <div class="form-group row page-service-input">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Link</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <input type="text" class="form-control url" name="v_link_3" placeholder="Link" value="{{ $record_footer[2]['v_link'] }}">                  
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12 row ">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12 footer-service-image-align">Image
                                </label>
                                <div class="col-lg-8 col-md-8 col-sm-12">
                                    
                                        <div class="fileinput" data-provides="fileinput">
                                            <img width="200" src="<?php
                                            if (File::exists(HOME_PAGE_IMAGE . $record_footer[2]['v_image']) && $record_footer[2]['v_image'] != '') {
                                                echo SITE_URL . HOME_PAGE_IMAGE .$record_footer[2]['v_image'];
                                            } else {
                                                echo ASSET_URL . 'images/no_image.png';
                                            }
                                            ?>" class="img-responsive default_img_size" name="profileimg"  alt="" id="page_service_pic_3" />

                                            <img  width="200" src="{{ ASSET_URL.'images/no_image.png'}}" class="img-responsive default_img_size" id="default_page_service_pic_3" style="display: none;"/>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div style="margin-top:20px">
                                            <button class="btn btn-default" type="button" id="file_trriger_page_service_3"><?php echo File::exists(HOME_PAGE_IMAGE . $record_footer[2]['v_image']) && $record_footer[2]['v_image'] != '' ? 'Change' : 'Select' ?></button>
                                            <button class="btn btn-default" type="button" id="remove_image_page_service_3" 
                                                <?php echo File::exists(HOME_PAGE_IMAGE . $record_footer[2]['v_image']) && $record_footer[2]['v_image'] != '' ? "" : 'style="display:none;"'; ?>
                                            >Remove</button>
                                            <br><br>
                                        </div>
                                        <input type="file" data-isshow="true" id="image_change_page_service_3" style="display: none;" />
                                        <input type="hidden" id="page_service_image_3" name="page_service_image_3" value=""/>
                                        <input type="hidden" id="default_img_page_service_3" name="default_img_page_service_3" value="0"/>
                                        <input type="hidden" name="page_service_imgbase64_3" value="" id="page_service_imgbase64_3" />
                                   
                                </div>
                            </div>
                        </div>

                        <div class="row page-service-row">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group row page-service-input">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Title</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <input type="text" class="form-control" name="v_title_4" placeholder="Title" value="{{ $record_footer[3]['v_title'] }}">
                                    </div>
                                </div>

                                <div class="form-group row page-service-input">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Description</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <textarea type="text" class="form-control"  name="v_desc_4" placeholder="Description" rows="4" maxlength="120"> {{ $record_footer[3]['v_desc'] }} </textarea>                   
                                    </div>
                                </div>

                                <div class="form-group row page-service-input">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Link</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <input type="text" class="form-control url" name="v_link_4" placeholder="Link" value="{{ $record_footer[3]['v_link'] }}">                  
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12 row ">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12 footer-service-image-align">Image
                                </label>
                                <div class="col-lg-8 col-md-8 col-sm-12">
                                    
                                        <div class="fileinput" data-provides="fileinput">
                                            <img width="200" src="<?php
                                            if (File::exists(HOME_PAGE_IMAGE . $record_footer[3]['v_image']) && $record_footer[3]['v_image'] != '') {
                                                echo SITE_URL . HOME_PAGE_IMAGE .$record_footer[3]['v_image'];
                                            } else {
                                                echo ASSET_URL . 'images/no_image.png';
                                            }
                                            ?>" class="img-responsive default_img_size" name="profileimg"  alt="" id="page_service_pic_4" />

                                            <img  width="200" src="{{ ASSET_URL.'images/no_image.png'}}" class="img-responsive default_img_size" id="default_page_service_pic_4" style="display: none;"/>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div style="margin-top:20px">
                                            <button class="btn btn-default" type="button" id="file_trriger_page_service_4"><?php echo File::exists(HOME_PAGE_IMAGE . $record_footer[3]['v_image']) && $record_footer[3]['v_image'] != '' ? 'Change' : 'Select' ?></button>
                                            <button class="btn btn-default" type="button" id="remove_image_page_service_4" 
                                                <?php echo File::exists(HOME_PAGE_IMAGE . $record_footer[3]['v_image']) && $record_footer[3]['v_image'] != '' ? "" : 'style="display:none;"'; ?>
                                            >Remove</button>
                                            <br><br>
                                        </div>
                                        <input type="file" data-isshow="true" id="image_change_page_service_4" style="display: none;" />
                                        <input type="hidden" id="page_service_image_4" name="page_service_image_4" value=""/>
                                        <input type="hidden" id="default_img_page_service_4" name="default_img_page_service_4" value="0"/>
                                        <input type="hidden" name="page_service_imgbase64_4" value="" id="page_service_imgbase64_4" />
                                    
                                </div>
                            </div>
                        </div>
                    
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <div class="row">
                                <div class="col-lg-9 ml-lg-auto">
                                    <button type="submit" class="btn btn-brand footer-submit-btn">Submit</button>
                                </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
          
          <!--end::Form-->
        </div>

        <!--end::Portlet-->
      </div>
      
    </div>
  </div>

  <!-- end:: Content -->
</div>

@stop

@section('custom_js')
  <script>
    $(document).ready(function() {
        
        @if(Session::has('success-message'))
            toastr.success('{{ Session::get('success-message') }}');
        @endif

        var url = window.location.href;        
        if(url.indexOf("#") <= 0) {
            var activeTab = 'kt_tabs_1_1';
        } else {
            var activeTab = url.substring(url.indexOf("#") + 1);
        }
        $(".nav-item li").removeClass("active"); 
        $('a[href="#'+ activeTab +'"]').tab('show')

      //**********Banner content***********
        $('#file_trriger_banner').on('click',function() {
            $('#image_change_banner').trigger('click');
        });
        $('#remove_image_banner').click(function(){
            $('#banner_imgbase64').val('');
            $('#image_change_banner').val('');
            $('#image_change_banner').addClass('required');
            $('#file_trriger_banner').text("Select Image");
            $('#remove_image_banner').hide();
            $('#banner_pic').hide();
            $('#default_img_banner').val('1');
            $('#default_banner_pic').fadeIn(100);
        });
        $('#image_change_banner').on('change', function (evt) {
            var file = evt.currentTarget.files[0];
            var reader = new FileReader();
            var image = new Image();
            reader.onload = function (evt) {
                if ((file.type == 'image/jpeg' || file.type == 'image/png' || file.type == 'image/jpg')) {
                    if (~~(file.size / 1024) < 4000) {
                        image.src = evt.target.result;
                        image.onload = function () {
                            var w = this.width, h = this.height, t = file.type, n = file.name, file_size = ~~(file.size / 1024);

                            if (w >= '450' && h >= '275') {
                                $('#banner_pic').show();
                                $('#banner_pic').attr('src', image.src);
                                $('#banner_imgbase64').val(image.src);
                                $('#default_banner_pic').hide();
                                
                                $('#default_img_banner').val('0');
                                $('#file_trriger_banner').text('Change');
                                $('#remove_image_banner').show();
                            } else {
                                swal.fire("Image must be greater than 450 X 275").then(function(result) {
                                    $('#image_change_banner').val('');
                                });
                                return false;
                            }
                        }

                    } else {
                        swal.fire("The maximum size for file upload is 4Mb.", "", "error").then(function(result) {
                            $('#image_change_banner').val('');
                        });
                        return false;
                    }
        
                } else {
                    swal.fire("Please upload image only", "", "error").then(function(result) {
                    $('#image_change_banner').val('');
                    });
                    return false;
                }
            };
            reader.readAsDataURL(file);
        });
      
      //**********Page Services content***********
        $('#file_trriger_page_service').on('click',function() {
            $('#image_change_page_service').trigger('click');
        });
        $('#remove_image_page_service').click(function(){
            $('#page_service_imgbase64_1').val('');
            $('#image_change_page_service').val('');
            $('#image_change_page_service').addClass('required');
            $('#file_trriger_page_service').text("Select Image");
            $('#remove_image_page_service').hide();
            $('#page_service_pic').hide();
            $('#default_img_page_service').val('1');
            $('#default_page_service_pic').fadeIn(100);
        });
        $('#image_change_page_service').on('change', function (evt) {
            var file = evt.currentTarget.files[0];
            var reader = new FileReader();
            var image = new Image();
            reader.onload = function (evt) {
                if ((file.type == 'image/jpeg' || file.type == 'image/png' || file.type == 'image/jpg')) {
                    if (~~(file.size / 1024) < 4000) {
                        image.src = evt.target.result;
                        image.onload = function () {
                            var w = this.width, h = this.height, t = file.type, n = file.name, file_size = ~~(file.size / 1024);

                            if (w >= '450' && h >= '350') {
                                $('#page_service_pic').show();
                                $('#page_service_pic').attr('src', image.src);
                                $('#page_service_imgbase64_1').val(image.src);
                                $('#default_page_service_pic').hide();
                                
                                $('#default_img_page_service').val('0');
                                $('#file_trriger_page_service').text('Change');
                                $('#remove_image_page_service').show();
                            } else {
                                swal.fire("Image must be greater than 450 X 275").then(function(result) {
                                    $('#image_change_page_service').val('');
                                });
                                return false;
                            }
                        }

                    } else {
                        swal.fire("The maximum size for file upload is 4Mb.", "", "error").then(function(result) {
                            $('#image_change_page_service').val('');
                        });
                        return false;
                    }
        
                } else {
                    swal.fire("Please upload image only", "", "error").then(function(result) {
                    $('#image_change_page_service').val('');
                    });
                    return false;
                }
            };
            reader.readAsDataURL(file);
        });

        $('#file_trriger_page_service_2').on('click',function() {
            $('#image_change_page_service_2').trigger('click');
        });
        $('#remove_image_page_service_2').click(function(){
            $('#page_service_imgbase64_2').val('');
            $('#image_change_page_service_2').val('');
            $('#image_change_page_service_2').addClass('required');
            $('#file_trriger_page_service_2').text("Select Image");
            $('#remove_image_page_service_2').hide();
            $('#page_service_pic_2').hide();
            $('#default_img_page_service_2').val('1');
            $('#default_page_service_pic_2').fadeIn(100);
        });
        $('#image_change_page_service_2').on('change', function (evt) {
            var file = evt.currentTarget.files[0];
            var reader = new FileReader();
            var image = new Image();
            reader.onload = function (evt) {
                if ((file.type == 'image/jpeg' || file.type == 'image/png' || file.type == 'image/jpg')) {
                    if (~~(file.size / 1024) < 4000) {
                        image.src = evt.target.result;
                        image.onload = function () {
                            var w = this.width, h = this.height, t = file.type, n = file.name, file_size = ~~(file.size / 1024);

                            if (w >= '450' && h >= '350') {
                                $('#page_service_pic_2').show();
                                $('#page_service_pic_2').attr('src', image.src);
                                $('#page_service_imgbase64_2').val(image.src);
                                $('#default_page_service_pic_2').hide();
                                
                                $('#default_img_page_service_2').val('0');
                                $('#file_trriger_page_service_2').text('Change');
                                $('#remove_image_page_service_2').show();
                            } else {
                                swal.fire("Image must be greater than 450 X 275").then(function(result) {
                                    $('#image_change_page_service_2').val('');
                                });
                                return false;
                            }
                        }

                    } else {
                        swal.fire("The maximum size for file upload is 4Mb.", "", "error").then(function(result) {
                            $('#image_change_page_service_2').val('');
                        });
                        return false;
                    }
        
                } else {
                    swal.fire("Please upload image only", "", "error").then(function(result) {
                    $('#image_change_page_service_2').val('');
                    });
                    return false;
                }
            };
            reader.readAsDataURL(file);
        });

        $('#file_trriger_page_service_3').on('click',function() {
            $('#image_change_page_service_3').trigger('click');
        });
        $('#remove_image_page_service_3').click(function(){
            $('#page_service_imgbase64_3').val('');
            $('#image_change_page_service_3').val('');
            $('#image_change_page_service_3').addClass('required');
            $('#file_trriger_page_service_3').text("Select Image");
            $('#remove_image_page_service_3').hide();
            $('#page_service_pic_3').hide();
            $('#default_img_page_service_3').val('1');
            $('#default_page_service_pic_3').fadeIn(100);
        });
        $('#image_change_page_service_3').on('change', function (evt) {
            var file = evt.currentTarget.files[0];
            var reader = new FileReader();
            var image = new Image();
            reader.onload = function (evt) {
                if ((file.type == 'image/jpeg' || file.type == 'image/png' || file.type == 'image/jpg')) {
                    if (~~(file.size / 1024) < 4000) {
                        image.src = evt.target.result;
                        image.onload = function () {
                            var w = this.width, h = this.height, t = file.type, n = file.name, file_size = ~~(file.size / 1024);

                            if (w >= '450' && h >= '350') {
                                $('#page_service_pic_3').show();
                                $('#page_service_pic_3').attr('src', image.src);
                                $('#page_service_imgbase64_3').val(image.src);
                                $('#default_page_service_pic_3').hide();
                                
                                $('#default_img_page_service_3').val('0');
                                $('#file_trriger_page_service_3').text('Change');
                                $('#remove_image_page_service_3').show();
                            } else {
                                swal.fire("Image must be greater than 450 X 275").then(function(result) {
                                    $('#image_change_page_service_3').val('');
                                });
                                return false;
                            }
                        }

                    } else {
                        swal.fire("The maximum size for file upload is 4Mb.", "", "error").then(function(result) {
                            $('#image_change_page_service_3').val('');
                        });
                        return false;
                    }
        
                } else {
                    swal.fire("Please upload image only", "", "error").then(function(result) {
                    $('#image_change_page_service_3').val('');
                    });
                    return false;
                }
            };
            reader.readAsDataURL(file);
        });

        $('#file_trriger_page_service_4').on('click',function() {
            $('#image_change_page_service_4').trigger('click');
        });
        $('#remove_image_page_service_4').click(function(){
            $('#page_service_imgbase64_4').val('');
            $('#image_change_page_service_4').val('');
            $('#image_change_page_service_4').addClass('required');
            $('#file_trriger_page_service_4').text("Select Image");
            $('#remove_image_page_service_4').hide();
            $('#page_service_pic_4').hide();
            $('#default_img_page_service_4').val('1');
            $('#default_page_service_pic_4').fadeIn(100);
        });
        $('#image_change_page_service_4').on('change', function (evt) {
            var file = evt.currentTarget.files[0];
            var reader = new FileReader();
            var image = new Image();
            reader.onload = function (evt) {
                if ((file.type == 'image/jpeg' || file.type == 'image/png' || file.type == 'image/jpg')) {
                    if (~~(file.size / 1024) < 4000) {
                        image.src = evt.target.result;
                        image.onload = function () {
                            var w = this.width, h = this.height, t = file.type, n = file.name, file_size = ~~(file.size / 1024);

                            if (w >= '450' && h >= '350') {
                                $('#page_service_pic_4').show();
                                $('#page_service_pic_4').attr('src', image.src);
                                $('#page_service_imgbase64_4').val(image.src);
                                $('#default_page_service_pic_4').hide();
                                
                                $('#default_img_page_service_4').val('0');
                                $('#file_trriger_page_service_4').text('Change');
                                $('#remove_image_page_service_4').show();
                            } else {
                                swal.fire("Image must be greater than 450 X 275").then(function(result) {
                                    $('#image_change_page_service_4').val('');
                                });
                                return false;
                            }
                        }

                    } else {
                        swal.fire("The maximum size for file upload is 4Mb.", "", "error").then(function(result) {
                            $('#image_change_page_service_4').val('');
                        });
                        return false;
                    }
        
                } else {
                    swal.fire("Please upload image only", "", "error").then(function(result) {
                    $('#image_change_page_service_4').val('');
                    });
                    return false;
                }
            };
            reader.readAsDataURL(file);
        });

      //**********Transportation facilities content***********

        $('#file_trriger_trans_facility').on('click',function() {
            $('#image_change_trans_facility').trigger('click');
        });
        $('#remove_image_trans_facility').click(function(){
            $('#trans_facility_imgbase64_1').val('');
            $('#image_change_trans_facility').val('');
            $('#image_change_trans_facility').addClass('required');
            $('#file_trriger_trans_facility').text("Select Image");
            $('#remove_image_trans_facility').hide();
            $('#trans_facility_pic').hide();
            $('#default_img_trans_facility').val('1');
            $('#default_trans_facility_pic').fadeIn(100);
        });
        $('#image_change_trans_facility').on('change', function (evt) {
            var file = evt.currentTarget.files[0];
            var reader = new FileReader();
            var image = new Image();
            reader.onload = function (evt) {
                if ((file.type == 'image/jpeg' || file.type == 'image/png' || file.type == 'image/jpg')) {
                    if (~~(file.size / 1024) < 4000) {
                        image.src = evt.target.result;
                        image.onload = function () {
                            var w = this.width, h = this.height, t = file.type, n = file.name, file_size = ~~(file.size / 1024);

                            if (w >= '450' && h >= '275') {
                                $('#trans_facility_pic').show();
                                $('#trans_facility_pic').attr('src', image.src);
                                $('#trans_facility_imgbase64_1').val(image.src);
                                $('#default_trans_facility_pic').hide();
                                
                                $('#default_img_trans_facility').val('0');
                                $('#file_trriger_trans_facility').text('Change');
                                $('#remove_image_trans_facility').show();
                            } else {
                                swal.fire("Image must be greater than 450 X 275").then(function(result) {
                                    $('#image_change_trans_facility').val('');
                                });
                                return false;
                            }
                        }

                    } else {
                        swal.fire("The maximum size for file upload is 4Mb.", "", "error").then(function(result) {
                            $('#image_change_trans_facility').val('');
                        });
                        return false;
                    }
        
                } else {
                    swal.fire("Please upload image only", "", "error").then(function(result) {
                    $('#image_change_trans_facility').val('');
                    });
                    return false;
                }
            };
            reader.readAsDataURL(file);
        });

        $('#file_trriger_trans_facility_2').on('click',function() {
            $('#image_change_trans_facility_2').trigger('click');
        });
        $('#remove_image_trans_facility_2').click(function(){
            $('#trans_facility_imgbase64_2').val('');
            $('#image_change_trans_facility_2').val('');
            $('#image_change_trans_facility_2').addClass('required');
            $('#file_trriger_trans_facility_2').text("Select Image");
            $('#remove_image_trans_facility_2').hide();
            $('#trans_facility_pic_2').hide();
            $('#default_img_trans_facility_2').val('1');
            $('#default_trans_facility_pic_2').fadeIn(100);
        });
        $('#image_change_trans_facility_2').on('change', function (evt) {
            var file = evt.currentTarget.files[0];
            var reader = new FileReader();
            var image = new Image();
            reader.onload = function (evt) {
                if ((file.type == 'image/jpeg' || file.type == 'image/png' || file.type == 'image/jpg')) {
                    if (~~(file.size / 1024) < 4000) {
                        image.src = evt.target.result;
                        image.onload = function () {
                            var w = this.width, h = this.height, t = file.type, n = file.name, file_size = ~~(file.size / 1024);

                            if (w >= '450' && h >= '275') {
                                $('#trans_facility_pic_2').show();
                                $('#trans_facility_pic_2').attr('src', image.src);
                                $('#trans_facility_imgbase64_2').val(image.src);
                                $('#default_trans_facility_pic_2').hide();
                                
                                $('#default_img_trans_facility_2').val('0');
                                $('#file_trriger_trans_facility_2').text('Change');
                                $('#remove_image_trans_facility_2').show();
                            } else {
                                swal.fire("Image must be greater than 450 X 275").then(function(result) {
                                    $('#image_change_trans_facility_2').val('');
                                });
                                return false;
                            }
                        }

                    } else {
                        swal.fire("The maximum size for file upload is 4Mb.", "", "error").then(function(result) {
                            $('#image_change_trans_facility_2').val('');
                        });
                        return false;
                    }
        
                } else {
                    swal.fire("Please upload image only", "", "error").then(function(result) {
                    $('#image_change_trans_facility_2').val('');
                    });
                    return false;
                }
            };
            reader.readAsDataURL(file);
        });

        $('#file_trriger_trans_facility_3').on('click',function() {
            $('#image_change_trans_facility_3').trigger('click');
        });
        $('#remove_image_trans_facility_3').click(function(){
            $('#trans_facility_imgbase64_3').val('');
            $('#image_change_trans_facility_3').val('');
            $('#image_change_trans_facility_3').addClass('required');
            $('#file_trriger_trans_facility_3').text("Select Image");
            $('#remove_image_trans_facility_3').hide();
            $('#trans_facility_pic_3').hide();
            $('#default_img_trans_facility_3').val('1');
            $('#default_trans_facility_pic_3').fadeIn(100);
        });
        $('#image_change_trans_facility_3').on('change', function (evt) {
            var file = evt.currentTarget.files[0];
            var reader = new FileReader();
            var image = new Image();
            reader.onload = function (evt) {
                if ((file.type == 'image/jpeg' || file.type == 'image/png' || file.type == 'image/jpg')) {
                    if (~~(file.size / 1024) < 4000) {
                        image.src = evt.target.result;
                        image.onload = function () {
                            var w = this.width, h = this.height, t = file.type, n = file.name, file_size = ~~(file.size / 1024);

                            if (w >= '450' && h >= '275') {
                                $('#trans_facility_pic_3').show();
                                $('#trans_facility_pic_3').attr('src', image.src);
                                $('#trans_facility_imgbase64_3').val(image.src);
                                $('#default_trans_facility_pic_3').hide();
                                
                                $('#default_img_trans_facility_3').val('0');
                                $('#file_trriger_trans_facility_3').text('Change');
                                $('#remove_image_trans_facility_3').show();
                            } else {
                                swal.fire("Image must be greater than 450 X 275").then(function(result) {
                                    $('#image_change_trans_facility_3').val('');
                                });
                                return false;
                            }
                        }

                    } else {
                        swal.fire("The maximum size for file upload is 4Mb.", "", "error").then(function(result) {
                            $('#image_change_trans_facility_3').val('');
                        });
                        return false;
                    }
        
                } else {
                    swal.fire("Please upload image only", "", "error").then(function(result) {
                    $('#image_change_trans_facility_3').val('');
                    });
                    return false;
                }
            };
            reader.readAsDataURL(file);
        });

        $('#file_trriger_trans_facility_4').on('click',function() {
            $('#image_change_trans_facility_4').trigger('click');
        });
        $('#remove_image_trans_facility_4').click(function(){
            $('#trans_facility_imgbase64_4').val('');
            $('#image_change_trans_facility_4').val('');
            $('#image_change_trans_facility_4').addClass('required');
            $('#file_trriger_trans_facility_4').text("Select Image");
            $('#remove_image_trans_facility_4').hide();
            $('#trans_facility_pic_4').hide();
            $('#default_img_trans_facility_4').val('1');
            $('#default_trans_facility_pic_4').fadeIn(100);
        });
        $('#image_change_trans_facility_4').on('change', function (evt) {
            var file = evt.currentTarget.files[0];
            var reader = new FileReader();
            var image = new Image();
            reader.onload = function (evt) {
                if ((file.type == 'image/jpeg' || file.type == 'image/png' || file.type == 'image/jpg')) {
                    if (~~(file.size / 1024) < 4000) {
                        image.src = evt.target.result;
                        image.onload = function () {
                            var w = this.width, h = this.height, t = file.type, n = file.name, file_size = ~~(file.size / 1024);

                            if (w >= '450' && h >= '275') {
                                $('#trans_facility_pic_4').show();
                                $('#trans_facility_pic_4').attr('src', image.src);
                                $('#trans_facility_imgbase64_4').val(image.src);
                                $('#default_trans_facility_pic_4').hide();
                                
                                $('#default_img_trans_facility_4').val('0');
                                $('#file_trriger_trans_facility_4').text('Change');
                                $('#remove_image_trans_facility_4').show();
                            } else {
                                swal.fire("Image must be greater than 450 X 275").then(function(result) {
                                    $('#image_change_trans_facility_4').val('');
                                });
                                return false;
                            }
                        }

                    } else {
                        swal.fire("The maximum size for file upload is 4Mb.", "", "error").then(function(result) {
                            $('#image_change_trans_facility_4').val('');
                        });
                        return false;
                    }
        
                } else {
                    swal.fire("Please upload image only", "", "error").then(function(result) {
                    $('#image_change_trans_facility_4').val('');
                    });
                    return false;
                }
            };
            reader.readAsDataURL(file);
        });

        $('#file_trriger_trans_facility_5').on('click',function() {
            $('#image_change_trans_facility_5').trigger('click');
        });
        $('#remove_image_trans_facility_5').click(function(){
            $('#trans_facility_imgbase64_5').val('');
            $('#image_change_trans_facility_5').val('');
            $('#image_change_trans_facility_5').addClass('required');
            $('#file_trriger_trans_facility_5').text("Select Image");
            $('#remove_image_trans_facility_5').hide();
            $('#trans_facility_pic_5').hide();
            $('#default_img_trans_facility_5').val('1');
            $('#default_trans_facility_pic_5').fadeIn(100);
        });
        $('#image_change_trans_facility_5').on('change', function (evt) {
            var file = evt.currentTarget.files[0];
            var reader = new FileReader();
            var image = new Image();
            reader.onload = function (evt) {
                if ((file.type == 'image/jpeg' || file.type == 'image/png' || file.type == 'image/jpg')) {
                    if (~~(file.size / 1024) < 4000) {
                        image.src = evt.target.result;
                        image.onload = function () {
                            var w = this.width, h = this.height, t = file.type, n = file.name, file_size = ~~(file.size / 1024);

                            if (w >= '450' && h >= '275') {
                                $('#trans_facility_pic_5').show();
                                $('#trans_facility_pic_5').attr('src', image.src);
                                $('#trans_facility_imgbase64_5').val(image.src);
                                $('#default_trans_facility_pic_5').hide();
                                
                                $('#default_img_trans_facility_5').val('0');
                                $('#file_trriger_trans_facility_5').text('Change');
                                $('#remove_image_trans_facility_5').show();
                            } else {
                                swal.fire("Image must be greater than 450 X 275").then(function(result) {
                                    $('#image_change_trans_facility_5').val('');
                                });
                                return false;
                            }
                        }

                    } else {
                        swal.fire("The maximum size for file upload is 4Mb.", "", "error").then(function(result) {
                            $('#image_change_trans_facility_5').val('');
                        });
                        return false;
                    }
        
                } else {
                    swal.fire("Please upload image only", "", "error").then(function(result) {
                    $('#image_change_trans_facility_5').val('');
                    });
                    return false;
                }
            };
            reader.readAsDataURL(file);
        });

        $('#file_trriger_trans_facility_6').on('click',function() {
            $('#image_change_trans_facility_6').trigger('click');
        });
        $('#remove_image_trans_facility_6').click(function(){
            $('#trans_facility_imgbase64_6').val('');
            $('#image_change_trans_facility_6').val('');
            $('#image_change_trans_facility_6').addClass('required');
            $('#file_trriger_trans_facility_6').text("Select Image");
            $('#remove_image_trans_facility_6').hide();
            $('#trans_facility_pic_6').hide();
            $('#default_img_trans_facility_6').val('1');
            $('#default_trans_facility_pic_6').fadeIn(100);
        });
        $('#image_change_trans_facility_6').on('change', function (evt) {
            var file = evt.currentTarget.files[0];
            var reader = new FileReader();
            var image = new Image();
            reader.onload = function (evt) {
                if ((file.type == 'image/jpeg' || file.type == 'image/png' || file.type == 'image/jpg')) {
                    if (~~(file.size / 1024) < 4000) {
                        image.src = evt.target.result;
                        image.onload = function () {
                            var w = this.width, h = this.height, t = file.type, n = file.name, file_size = ~~(file.size / 1024);

                            if (w >= '450' && h >= '275') {
                                $('#trans_facility_pic_6').show();
                                $('#trans_facility_pic_6').attr('src', image.src);
                                $('#trans_facility_imgbase64_6').val(image.src);
                                $('#default_trans_facility_pic_6').hide();
                                
                                $('#default_img_trans_facility_6').val('0');
                                $('#file_trriger_trans_facility_6').text('Change');
                                $('#remove_image_trans_facility_6').show();
                            } else {
                                swal.fire("Image must be greater than 450 X 275").then(function(result) {
                                    $('#image_change_trans_facility_6').val('');
                                });
                                return false;
                            }
                        }

                    } else {
                        swal.fire("The maximum size for file upload is 4Mb.", "", "error").then(function(result) {
                            $('#image_change_trans_facility_6').val('');
                        });
                        return false;
                    }
        
                } else {
                    swal.fire("Please upload image only", "", "error").then(function(result) {
                    $('#image_change_trans_facility_6').val('');
                    });
                    return false;
                }
            };
            reader.readAsDataURL(file);
        });

        $('#file_trriger_trans_facility_7').on('click',function() {
            $('#image_change_trans_facility_7').trigger('click');
        });
        $('#remove_image_trans_facility_7').click(function(){
            $('#trans_facility_imgbase64_7').val('');
            $('#image_change_trans_facility_7').val('');
            $('#image_change_trans_facility_7').addClass('required');
            $('#file_trriger_trans_facility_7').text("Select Image");
            $('#remove_image_trans_facility_7').hide();
            $('#trans_facility_pic_7').hide();
            $('#default_img_trans_facility_7').val('1');
            $('#default_trans_facility_pic_7').fadeIn(100);
        });
        $('#image_change_trans_facility_7').on('change', function (evt) {
            var file = evt.currentTarget.files[0];
            var reader = new FileReader();
            var image = new Image();
            reader.onload = function (evt) {
                if ((file.type == 'image/jpeg' || file.type == 'image/png' || file.type == 'image/jpg')) {
                    if (~~(file.size / 1024) < 4000) {
                        image.src = evt.target.result;
                        image.onload = function () {
                            var w = this.width, h = this.height, t = file.type, n = file.name, file_size = ~~(file.size / 1024);

                            if (w >= '450' && h >= '275') {
                                $('#trans_facility_pic_7').show();
                                $('#trans_facility_pic_7').attr('src', image.src);
                                $('#trans_facility_imgbase64_7').val(image.src);
                                $('#default_trans_facility_pic_7').hide();
                                
                                $('#default_img_trans_facility_7').val('0');
                                $('#file_trriger_trans_facility_7').text('Change');
                                $('#remove_image_trans_facility_7').show();
                            } else {
                                swal.fire("Image must be greater than 450 X 275").then(function(result) {
                                    $('#image_change_trans_facility_7').val('');
                                });
                                return false;
                            }
                        }

                    } else {
                        swal.fire("The maximum size for file upload is 4Mb.", "", "error").then(function(result) {
                            $('#image_change_trans_facility_7').val('');
                        });
                        return false;
                    }
        
                } else {
                    swal.fire("Please upload image only", "", "error").then(function(result) {
                    $('#image_change_trans_facility_7').val('');
                    });
                    return false;
                }
            };
            reader.readAsDataURL(file);
        });

        $('#file_trriger_trans_facility_8').on('click',function() {
            $('#image_change_trans_facility_8').trigger('click');
        });
        $('#remove_image_trans_facility_8').click(function(){
            $('#trans_facility_imgbase64_8').val('');
            $('#image_change_trans_facility_8').val('');
            $('#image_change_trans_facility_8').addClass('required');
            $('#file_trriger_trans_facility_8').text("Select Image");
            $('#remove_image_trans_facility_8').hide();
            $('#trans_facility_pic_8').hide();
            $('#default_img_trans_facility_8').val('1');
            $('#default_trans_facility_pic_8').fadeIn(100);
        });
        $('#image_change_trans_facility_8').on('change', function (evt) {
            var file = evt.currentTarget.files[0];
            var reader = new FileReader();
            var image = new Image();
            reader.onload = function (evt) {
                if ((file.type == 'image/jpeg' || file.type == 'image/png' || file.type == 'image/jpg')) {
                    if (~~(file.size / 1024) < 4000) {
                        image.src = evt.target.result;
                        image.onload = function () {
                            var w = this.width, h = this.height, t = file.type, n = file.name, file_size = ~~(file.size / 1024);

                            if (w >= '450' && h >= '275') {
                                $('#trans_facility_pic_8').show();
                                $('#trans_facility_pic_8').attr('src', image.src);
                                $('#trans_facility_imgbase64_8').val(image.src);
                                $('#default_trans_facility_pic_8').hide();
                                
                                $('#default_img_trans_facility_8').val('0');
                                $('#file_trriger_trans_facility_8').text('Change');
                                $('#remove_image_trans_facility_8').show();
                            } else {
                                swal.fire("Image must be greater than 450 X 275").then(function(result) {
                                    $('#image_change_trans_facility_8').val('');
                                });
                                return false;
                            }
                        }

                    } else {
                        swal.fire("The maximum size for file upload is 4Mb.", "", "error").then(function(result) {
                            $('#image_change_trans_facility_8').val('');
                        });
                        return false;
                    }
        
                } else {
                    swal.fire("Please upload image only", "", "error").then(function(result) {
                    $('#image_change_trans_facility_8').val('');
                    });
                    return false;
                }
            };
            reader.readAsDataURL(file);
        });

        $('#file_trriger_trans_facility_9').on('click',function() {
            $('#image_change_trans_facility_9').trigger('click');
        });
        $('#remove_image_trans_facility_9').click(function(){
            $('#trans_facility_imgbase64_9').val('');
            $('#image_change_trans_facility_9').val('');
            $('#image_change_trans_facility_9').addClass('required');
            $('#file_trriger_trans_facility_9').text("Select Image");
            $('#remove_image_trans_facility_9').hide();
            $('#trans_facility_pic_9').hide();
            $('#default_img_trans_facility_9').val('1');
            $('#default_trans_facility_pic_9').fadeIn(100);
        });
        $('#image_change_trans_facility_9').on('change', function (evt) {
            var file = evt.currentTarget.files[0];
            var reader = new FileReader();
            var image = new Image();
            reader.onload = function (evt) {
                if ((file.type == 'image/jpeg' || file.type == 'image/png' || file.type == 'image/jpg')) {
                    if (~~(file.size / 1024) < 4000) {
                        image.src = evt.target.result;
                        image.onload = function () {
                            var w = this.width, h = this.height, t = file.type, n = file.name, file_size = ~~(file.size / 1024);

                            if (w >= '450' && h >= '275') {
                                $('#trans_facility_pic_9').show();
                                $('#trans_facility_pic_9').attr('src', image.src);
                                $('#trans_facility_imgbase64_9').val(image.src);
                                $('#default_trans_facility_pic_9').hide();
                                
                                $('#default_img_trans_facility_9').val('0');
                                $('#file_trriger_trans_facility_9').text('Change');
                                $('#remove_image_trans_facility_9').show();
                            } else {
                                swal.fire("Image must be greater than 450 X 275").then(function(result) {
                                    $('#image_change_trans_facility_9').val('');
                                });
                                return false;
                            }
                        }

                    } else {
                        swal.fire("The maximum size for file upload is 4Mb.", "", "error").then(function(result) {
                            $('#image_change_trans_facility_9').val('');
                        });
                        return false;
                    }
        
                } else {
                    swal.fire("Please upload image only", "", "error").then(function(result) {
                    $('#image_change_trans_facility_9').val('');
                    });
                    return false;
                }
            };
            reader.readAsDataURL(file);
        });
    });    
  </script>
@stop
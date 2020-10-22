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
              <h3 class="kt-portlet__head-title">
                Edit Driver
              </h3>
            </div>
          </div>

          <!--begin::Form-->
        <form class="kt-form kt-form--label-right" id="frmEdit" action="{{ ADMIN_URL }}drivers/edit/{{ $record->id }}">
            <div class="kt-portlet__body">
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">First Name <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required" name="v_firstname" placeholder="First Name" value="{{ $record->v_firstname }}">                  
                </div>
              </div>

              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Last Name <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required" name="v_lastname" placeholder="Last Name" value="{{ $record->v_lastname }}">                  
                </div>
              </div>
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Dispatch Name <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required" name="v_dispatch_name" placeholder="Dispatch Name" value="{{ $record->v_dispatch_name }}">                  
                </div>
              </div>

              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Email <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required email" name="v_email" placeholder="Email" value="{{ $record->v_email }}"><span id="v_email_error" class="help-block exist_label" style="display:none;">Email id already exists.</span>                  
                </div>
              </div>

              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Password</label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="password" class="form-control validate_password" name="password" id="password" placeholder="Password" >                  
                </div>
              </div>

              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Confirm Password</label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="password" class="form-control" name="cpassword" id="cpassword" placeholder="Confirm Password" equalTo="password">                  
                </div>
              </div>
              
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Phone Number <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <div class="input-group">
                    <input type="text" class="form-control required phone" name="v_phone" placeholder="Phone" value="{{ $record->v_phone }}">                    
                  </div>                  
                </div>
              </div>

              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Street <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required v_street" name="v_street" placeholder="Street" value="{{ $record->v_street }}">                  
                </div>
              </div>
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">City <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required v_city" name="v_city" placeholder="City" value="{{ $record->v_city }}">                  
                </div>
              </div>
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">State <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required v_state" name="v_state" placeholder="State" value="{{ $record->v_state }}">                  
                </div>
              </div>
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Country <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required v_country" name="v_country" placeholder="Country" value="{{ $record->v_country }}">                  
                </div>
              </div>
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Postal Code <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required v_postal_code" name="v_postal_code" placeholder="Postal Code" value="{{ $record->v_postal_code }}">                  
                </div>
              </div>
              
              <div class="form-group row">
                  <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Extension <span class="required">*</span></label>
                  <div class="col-md-4 col-lg-4 col-sm-12">
                    <input type="text" class="form-control required v_extension" name="v_extension" placeholder="Extension" value="{{ ($driver_extension) ? $driver_extension[0]['v_extension'] : '' }}"/>
                    <span id="v_extension_error" class="exist_label" style="display:none;">Extension is already exists.</span>                  
                  </div>
              </div>

              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Status <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12 form-group-sub">
                  <select class="form-control required" name="e_status" placeholder="Status">
                    <option value="">Select</option>
                    <option value="Active" {{ $record->e_status == 'Active' ? 'selected=""' : '' }}>Active</option>
                    <option value="Inactive" {{ $record->e_status == 'Inactive' ? 'selected=""' : '' }}>Inactive</option>
                    <option value="Terminated" {{ $record->e_status == 'Terminated' ? 'selected=""' : '' }}>Terminated</option>
                  </select>                  
                </div>
              </div>       

              <div class="form-group row">
                  <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Profile Picture
                  </label>
                  <div class="col-lg-4 col-md-6 col-sm-12">
                      <div class="fileinput" data-provides="fileinput">

                          <img  width="220px" src="<?php
                          if (File::exists(DRIVER_PROFILE_IMG_PATH . $record['v_profile_image']) && $record['v_profile_image'] != '') {
                              echo SITE_URL . DRIVER_PROFILE_IMG_PATH . $record['v_profile_image'];
                          } else {
                              echo ASSET_URL . 'images/default-image.png';
                          }
                          ?>" class="img-responsive default_img_size profile_preview_elements" name="profileimg"  alt="" id="profile_preview" />

                          <div class="cropper_area_elements" style="display:none;">
                              <img width="{{ File::exists(DRIVER_PROFILE_IMG_PATH . $record['v_profile_image']) && $record['v_profile_image'] != '' ? '220px' : '220px' }}" src="<?php
                              if (File::exists(DRIVER_PROFILE_IMG_PATH . $record['v_profile_image']) && $record['v_profile_image'] != '') {
                                  echo SITE_URL . DRIVER_PROFILE_IMG_PATH . $record['v_profile_image'];
                              } else {
                                  echo ASSET_URL . 'images/default-image.png';
                              }
                              ?>" class="img-responsive default_img_size" name="profileimg"  alt=""  id="profile_pic" />
                          </div>

                          <img  width="220px" src="{{ ASSET_URL.'images/default-image.png'}}" class="img-responsive default_img_size" id="defailt_profile_pic" style="display: none;"/>
                      </div>
                      <div class="clearfix"></div>
                      <div style="margin-top:20px">
                          <button class="btn btn-default profile_preview_elements" type="button" id="edit_image" style="display: {{  File::exists(DRIVER_PROFILE_IMG_PATH.$record['v_profile_image']) && $record['v_profile_image'] !='' ? 'inline-block' : 'none' }};">Edit</button>	
                          <button class="btn btn-default" style="display: {{  File::exists(DRIVER_PROFILE_IMG_PATH.$record['v_profile_image']) && $record['v_profile_image'] !='' ? 'none' : 'inline-block' }};" type="button" id="file_trriger"><?php echo File::exists(DRIVER_PROFILE_IMG_PATH . $record['v_profile_image']) && $record['v_profile_image'] != '' ? 'Change' : 'Select Image'; ?></button>
                          <span class="cropper_area_elements" style="display:none;">
                              <button class="btn btn-default" type="button" id="remove_image" style="display: {{  File::exists(DRIVER_PROFILE_IMG_PATH.$record['v_profile_image']) && $record['v_profile_image'] !='' ? 'inline-block' : 'none' }};">Remove</button>
                              
                              <button class="btn btn-default" type="button" id="cancel_image_crop" style="display: {{  File::exists(DRIVER_PROFILE_IMG_PATH.$record['v_profile_image']) && $record['v_profile_image'] !='' ? 'inline-block' : 'none' }};">Cancel</button>
                          </span>
                          <div class="clearfix mt10" style="margin-top: 5px;">
                                  <span class="label label-sm label-danger">Note</span> <span>Only jpg, jpeg and png image format are allowed.</span>
                              </div>
                      </div>

                      <input type="file" id="image_change" style="display: none;" />

                      <input type="hidden" id="user_profile_iamge" name="user_profile_iamge" value="<?php
                      if (File::exists(DRIVER_PROFILE_IMG_PATH . $record['v_profile_image']) && $record['v_profile_image'] != '') {
                          echo SITE_URL . DRIVER_PROFILE_IMG_PATH . $record['v_profile_image'];
                      } else {
                          echo '';
                      }
                      ?>"/>
                      <input type="hidden" name="is_edit_img_flag" id="is_edit_img_flag" value="0" />
                      <input type="hidden" id="default_img" name="default_img" value="<?php
                      if (File::exists(DRIVER_PROFILE_IMG_PATH . $record['v_profile_image']) && $record['v_profile_image'] != '') {
                          echo '0';
                      } else {
                          echo '1';
                      }
                      ?>"/>
                      <input type="hidden" name="imgbase64" value=""  id="imgbase64" />
                      <input type="hidden" id="x" name="x" />
                      <input type="hidden" id="y" name="y" />
                      <input type="hidden" id="x2" name="x2" />
                      <input type="hidden" id="y2" name="y2" />
                      <input type="hidden" id="w" name="w" />
                      <input type="hidden" id="h" name="h" />
                  </div>
              </div>       
              
            </div>
            <div class="kt-portlet__foot">
              <div class="kt-form__actions">
                <div class="row">
                  <div class="col-lg-9 ml-lg-auto">
                    <button type="submit" class="btn btn-brand">Submit</button>
                    <a href="{{ ADMIN_URL }}drivers" class="btn btn-secondary"> Cancel </a>
                  </div>
                </div>
              </div>
            </div>
          </form>

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
     
    });    
  </script>
@stop
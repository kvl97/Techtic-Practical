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
                Edit Profile
              </h3>
            </div>
          </div>

          <!--begin::Form-->
          <form class="kt-form kt-form--label-right" id="update_profile" name="update_profile" action="{{ ADMIN_URL }}my-profile">
            <div class="kt-portlet__body">
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">First Name <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required" name="v_firstname" placeholder="First Name" value="{{ $user->v_firstname }}" />                  
                </div>
              </div>

              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Last Name <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required" name="v_lastname" placeholder="Last Name" value="{{ $user->v_lastname }}" />                  
                </div>
              </div>

              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Email <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required email" name="v_email" placeholder="Email" value="{{ $user->v_email }}" />  
                  <span id="v_email_error" class="help-block exist_label" style="display:none;">Email id already exists.</span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">New Password</label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="password" class="form-control validate_password" name="password" id="password" placeholder="Password" />
                  <span class="help-block"> Leave blank to not to change password. </span>                 
                </div>
              </div>
              
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Cell number <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <div class="input-group">
                    <input type="text" class="form-control required" name="v_phone" placeholder="Cell number" value="{{ $user->v_phone }}" />                    
                  </div>                  
                </div>
              </div>
              
            <div class="form-group row">
                <label class="control-label col-md-2 col-lg-2 col-sm-12">Profile Pic
                </label>
                <div class="input-file-box col-lg-4 col-md-6 col-sm-12">
                    <div class="fileinput" data-provides="fileinput">

                        <img  width="150px" src="<?php
                        if (File::exists(ADMIN_USER_PROFILE_IMG_PATH.'thumb/' . $user->v_profile_image) && $user->v_profile_image != '') {
                            echo SITE_URL . ADMIN_USER_PROFILE_IMG_PATH.'thumb/' . $user->v_profile_image;
                        } else {
                            echo ASSET_URL . 'images/default-image.png';
                        }
                        ?>" class="img-responsive default_img_size profile_preview_elements" name="profileimg"  alt="" id="profile_preview" />

                        <div class="cropper_area_elements" style="display:none;">
                            <img width="{{ File::exists(ADMIN_USER_PROFILE_IMG_PATH . $user->v_profile_image) && $user->v_profile_image != '' ? '220px' : '150px' }}" src="<?php
                            if (File::exists(ADMIN_USER_PROFILE_IMG_PATH . $user->v_profile_image) && $user->v_profile_image != '') {
                                echo SITE_URL . ADMIN_USER_PROFILE_IMG_PATH . $user->v_profile_image;
                            } else {
                                echo ASSET_URL . 'images/default-image.png';
                            }
                            ?>" class="img-responsive default_img_size" name="profileimg"  alt=""  id="profile_pic" />
                        </div>

                        <img width="150px" src="{{ ASSET_URL.'images/default-image.png'}}" class="img-responsive default_img_size" id="defailt_profile_pic" style="display: none;"/>
                    </div>
                    <div class="clearfix"></div>
                    <div style="margin-top:20px">
                        <button class="btn btn-default profile_preview_elements" type="button" id="edit_image" style="display: {{  File::exists(ADMIN_USER_PROFILE_IMG_PATH.$user->v_profile_image) && $user->v_profile_image !='' ? 'inline-block' : 'none' }};">Change</button>	
                        <button class="btn btn-default" style="display: {{  File::exists(ADMIN_USER_PROFILE_IMG_PATH.$user->v_profile_image) && $user->v_profile_image !='' ? 'none' : 'inline-block' }};" type="button" id="file_trriger"><?php echo File::exists(ADMIN_USER_PROFILE_IMG_PATH . $user->v_profile_image) && $user->v_profile_image != '' ? 'Change' : 'Select Photo'; ?></button>
                        <span class="cropper_area_elements" style="display:none;">
                            <button class="btn btn-default" type="button" id="remove_image" style="display: {{  File::exists(ADMIN_USER_PROFILE_IMG_PATH.$user->v_profile_image) && $user->v_profile_image !='' ? 'inline-block' : 'none' }};">Remove</button>
                            <button class="btn btn-default" type="button" id="cancel_image_crop" style="display: {{  File::exists(ADMIN_USER_PROFILE_IMG_PATH.$user->v_profile_image) && $user->v_profile_image !='' ? 'inline-block' : 'none' }};">Cancel</button>
                        </span>
                    </div>

                    <input type="file" id="image_change" style="display: none;" />

                    <input type="hidden" id="user_profile_iamge" name="user_profile_iamge" value="<?php
                    if (File::exists(ADMIN_USER_PROFILE_IMG_PATH . $user->v_profile_image) && $user->v_profile_image != '') {
                        echo SITE_URL . ADMIN_USER_PROFILE_IMG_PATH . $user->v_profile_image;
                    } else {
                        echo '';
                    }
                    ?>"/>
                    <input type="hidden" name="is_edit_img_flag" id="is_edit_img_flag" value="0" />
                    <input type="hidden" id="default_img" name="default_img" value="<?php
                    if (File::exists(ADMIN_USER_PROFILE_IMG_PATH . $user->v_profile_image) && $user->v_profile_image != '') {
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
                    <a href="{{ ADMIN_URL }}admin-users" class="btn btn-secondary"> Cancel </a>
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
        @if(Session::has('success-message'))
        toastr.success('{{ Session::get('success-message') }}');
      @endif
    });    
  </script>
@stop
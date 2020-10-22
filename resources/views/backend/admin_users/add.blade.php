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
                Add User
              </h3>
            </div>
          </div>

          <!--begin::Form-->
          <form class="kt-form kt-form--label-right" id="frmAdd" action="{{ ADMIN_URL }}admin-users/add">
            <div class="kt-portlet__body">
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">First Name <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required" name="v_firstname" placeholder="First Name">                  
                </div>
              </div>

              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Last Name <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required" name="v_lastname" placeholder="Last Name">                  
                </div>
              </div>

              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Email <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required email" name="v_email" placeholder="Email">  
                  <span id="v_email_error" class="exist_label" style="display:none;">Email id already exists.</span>                
                </div>
              </div>

              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Password <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="password" class="form-control required validate_password" name="password" id="password" placeholder="Password">                  
                </div>
              </div>

              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Confirm Password <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="password" class="form-control required" name="cpassword" id="cpassword" placeholder="Confirm Password" equalTo="password">                  
                </div>
              </div>
              
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Cell Number<span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <div class="input-group">
                    <input type="text" class="form-control required" name="v_phone" placeholder="Cell Number">                    
                  </div>                  
                </div>
              </div>
              
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Street <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required v_street" name="v_street" placeholder="Street">                  
                </div>
              </div>
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">City <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required v_city" name="v_city" placeholder="City">                  
                </div>
              </div>
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">State <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required v_state" name="v_state" placeholder="State">                  
                </div>
              </div>
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Country <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required v_country" name="v_country" placeholder="Country">                  
                </div>
              </div>
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Postal Code <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required v_postal_code" name="v_postal_code" placeholder="Postal Code">                  
                </div>
              </div>


              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Role <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12 form-group-sub">
                  <select class="form-control required" name="i_role_id" placeholder="Role">
                    <option value="">Select</option>                    
                    @foreach ($adminRoles as $index => $val)
                      <option value="{{ $index }}">{{ $val }}</option>    
                    @endforeach                    
                  </select>                  
                </div>
              </div> 

              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Status <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12 form-group-sub">
                  <select class="form-control required" name="e_status" placeholder="Status">
                    <option value="">Select</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                  </select>                  
                </div>
              </div>     

              <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Profile Picture:</label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="fileinput" data-provides="fileinput">

                                <img  width="150px" src="<?php
                                    echo ASSET_URL . 'images/default-image.png';
                                ?>" class="img-responsive default_img_size profile_preview_elements" name="profileimg"  alt="" id="profile_preview" />

                                <div class="cropper_area_elements" style="display:none;">
                                    <img width="150" src="<?php
                                        echo ASSET_URL . 'images/default-image.png';?>" class="img-responsive default_img_size" name="profileimg"  alt=""  id="profile_pic" />
                                </div>
                                <img  width="220px" src="{{ ASSET_URL.'images/default-image.png'}}" class="img-responsive default_img_size" id="defailt_profile_pic" style="display: none;"/>
                            </div>
                            <div class="clearfix"></div>
                            <div style="margin-top:20px">
                                

                                <button class="btn btn-default" style="display: {{  File::exists(ADMIN_USER_PROFILE_IMG_PATH.$records) && $records !='' ? 'none' : 'inline-block' }};" type="button" id="file_trriger"><?php echo File::exists(ADMIN_USER_PROFILE_IMG_PATH . $records) && $records != '' ? 'Change' : 'Select Image'; ?></button>
                                <span class="cropper_area_elements" style="display:none;">
                                    <button class="btn btn-default" type="button" id="remove_image" style="display: {{  File::exists(ADMIN_USER_PROFILE_IMG_PATH.$records) && $records !='' ? 'inline-block' : 'none' }};">Remove</button>
                                    
                                    
                                </span>
                                <div class="clearfix mt10" style="margin-top: 5px;">
                                    <span class="label label-sm label-danger">Note</span> <span>Only jpg, jpeg and png image format are allowed.</span>
                                </div>
                            </div>
                            <input type="file" id="image_change" style="display: none;" />
                            <input type="hidden" id="user_profile_iamge" name="user_profile_iamge" value=""/>
                            <input type="hidden" name="is_edit_img_flag" id="is_edit_img_flag" value="0" />
                            <input type="hidden" id="default_img" name="default_img" value="1"/>
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
     
    });    
  </script>
@stop
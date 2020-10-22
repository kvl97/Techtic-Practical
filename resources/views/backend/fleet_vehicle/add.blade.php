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
                    {{$title}}
                </h3>
                </div>
            </div>

            <!--begin::Form-->
            <form class="kt-form kt-form--label-right" id="frmAdd" action="{{ ADMIN_URL }}fleet-vehicles/add">
                <div class="kt-portlet__body">

                    <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Status <span class="required">*</span></label>
                        <div class="col-lg-4 col-md-6 col-sm-12 form-group-sub">
                            <select class="form-control required e_vehicle_status" name="e_vehicle_status" placeholder="Status">
                                <option value="">-- Select status --</option>
                                <option value="Ready">Ready</option>
                                <option value="Unavailable">Unavailable</option>
                                <option value="Maintenance">Maintenance</option>
                                <option value="Retired">Retired</option>
                                <option value="Repair">Repair</option>
                                <option value="Disposed">Disposed</option>
                            </select>                  
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Make <span class="required">*</span></label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <input type="text" class="form-control required" name="v_make" placeholder="Make" autocomplete="off" value="@if(!empty($records['get_vehicle_specification']) && $records['get_vehicle_specification']['v_make']){!! $records['get_vehicle_specification']['v_make'] !!}@endif" maxlength="50">                  
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Model <span class="required">*</span></label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <input type="text" class="form-control required" name="v_model" placeholder="Model" autocomplete="off" maxlength="50">                  
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Series <span class="required">*</span></label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <input type="text" class="form-control required" name="v_series" placeholder="Series" autocomplete="off" maxlength="20">                  
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Vehicle Number: <span class="required">*</span></label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <input type="text" class="form-control required" name="v_vehicle_code" maxlength="8" placeholder="Vehicle Number" autocomplete="off">                  
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">VIN: <span class="required">*</span></label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <input type="text" class="form-control required" name="v_vin" maxlength="20" placeholder="VIN" autocomplete="off">                   
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">US DOT Number: </label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                        <input type="text" class="form-control" name="v_usdot_number" maxlength="8" value="{{$us_dot_number['us_dot_number']}}" placeholder="US DOT Number" autocomplete="off">                   
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Model Year: <span class="required">*</span></label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <input type="text" class="form-control required" name="v_model_year" maxlength="4" placeholder="Model Year" autocomplete="off">                  
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Total Seats in Van: <span class="required">*</span></label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <input type="text" class="form-control required number" name="i_total_customer_booking_seats" maxlength="2" placeholder="Total Seats in Van" autocomplete="off">                  
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Total Seats Bookable: <span class="required">*</span></label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <input type="text" class="form-control required number" name="i_optimal_booking_seats" maxlength="2" placeholder="Total Seats Bookable" autocomplete="off">                  
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">License Plate: <span class="required">*</span></label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <input type="text" class="form-control required" name="v_lic_plate" maxlength="8" placeholder="License Plate" autocomplete="off">                  
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">License State: <span class="required">*</span></label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <select class="form-control required" name="c_lic_state" placeholder="State">
                                <option value=""> -- Select state -- </option>
                                @foreach($license_state as $key => $val)
                                    <option value="{{$val}}">{{$key}}</option>
                                @endforeach
                            </select>                 
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Registration Expiration Date: </label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <input type="text" class="form-control date_picker"  name="d_tag_exp_date" placeholder="Registration Expiration Date" autocomplete="off"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Acquisition Cost: <span class="required">*</span></label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <input type="text" class="form-control required number_decimal" name="d_aq_cost" placeholder="Acquisition Cost" autocomplete="off" maxlength="6">                  
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Acquisition Date: <span class="required">*</span></label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <input type="text" class="form-control required date_picker"  name="d_aq_date" placeholder="Acquisition Date" autocomplete="off"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Service Start Date: <span class="required">*</span></label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <input type="text" class="form-control required date_picker_service_start"  name="d_inservice_date" placeholder="Service Start Date" autocomplete="off"/>
                        </div>
                    </div>
                    <div class="form-group row service_end_date">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Service End Date: </label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <input type="text" class="form-control  date_picker_service_end d_end_service_date"  name="d_end_service_date" placeholder="Service End Date" autocomplete="off"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Vehicle Image:</label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="fileinput" data-provides="fileinput">

                                <img  width="300px" src="<?php
                                    echo ASSET_URL . 'images/default-image-vehicle.png';
                                ?>" class="img-responsive default_img_size profile_preview_elements_temp" name="profileimg"  alt="" id="profile_preview_temp" />

                                <div class="cropper_area_elements_temp" style="display:none;">
                                    <img width="300px" src="<?php
                                        echo ASSET_URL . 'images/default-image-vehicle.png';?>" class="img-responsive default_img_size" name="profileimg"  alt=""  id="profile_pic_temp" />
                                </div>
                                <!-- <img  width="300px" src="{{ ASSET_URL.'images/default-image-vehicle.png'}}" class="img-responsive default_img_size" id="defailt_profile_pic_temp" style="display: none;"/> -->
                            </div>
                            <div class="clearfix"></div>
                            <div style="margin-top:20px">
                                <button class="btn btn-default" style="display: inline-block;" type="button" id="file_trriger_temp">Select Image</button>
                                <span class="cropper_area_elements_temp" style="display:none;">
                                    <button class="btn btn-default" type="button" id="remove_image_temp" style="display:inline-block;">Remove</button>
                                    
                                </span>
                                <div class="clearfix mt10" style="margin-top: 5px;">
                                    <span class="label label-sm label-danger">Note</span> <span>Only jpg, jpeg and png image format are allowed.</span>
                                </div>
                            </div>
                            <input type="file" id="image_change_temp" style="display: none;" />
                            <input type="hidden" id="user_profile_iamge_temp" name="user_profile_iamge" value=""/>
                            <input type="hidden" id="is_edit_img_flag_temp" name="is_edit_img_flag" value="0" />
                            <input type="hidden" id="default_img_temp" name="default_img" value="1"/>
                            <input type="hidden" id="imgbase64_temp" name="imgbase64" value=""  /> 
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
                        <a href="{{ ADMIN_URL }}fleet-vehicles" class="btn btn-secondary"> Cancel </a>
                        
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
        var move_flag = 0;
        var date = new Date();
        date.setDate(date.getDate());
      
        $('.date_picker').datepicker({
            format: 'mm/dd/yyyy',
            autoclose: true,
        }).on('changeDate', function(e) {
            $('.date_picker').trigger('blur');
        });

        $('.date_picker_service_start').datepicker({
            format: 'mm/dd/yyyy',
            autoclose: true,
            //orientation: "bottom auto",
            todayHighlight: true,
        }).on('changeDate', function(selected) {
            var minDate = new Date(selected.date.valueOf());
            $('.date_picker_service_end').datepicker('setStartDate', minDate);
        })

        $('.date_picker_service_end').datepicker({
            format: 'mm/dd/yyyy',
            autoclose: true,
            //orientation: "bottom auto",
            todayHighlight: true,
        }).on('changeDate', function(selected) {
            var maxDate = new Date(selected.date.valueOf());
            $('.date_picker_service_start').datepicker('setEndDate', maxDate);
        })
        $('.e_vehicle_status').on('change', function(){
            var value = $(this).val();
            if(value != 'Retired' && value != 'Disposed'){
                $('.d_end_service_date').removeClass('required');
                $('.service_end_date').removeClass('is-invalid');
            } else{
                $('.d_end_service_date').addClass('required');
            }
        });

        $('#file_trriger_temp').on('click', function() {
            $('#is_edit_img_flag_temp').val(1);
            $('.profile_preview_elements_temp').hide();
            $('.cropper_area_elements_temp').show();
            $('#image_change_temp').trigger('click');
        })

        $('#image_change_temp').on('change', function(evt) {
            var file = evt.currentTarget.files[0];
            var reader = new FileReader();
            var image = new Image();
            reader.onload = function(evt) {
                if ((file.type == 'image/jpeg' || file.type == 'image/png' || file.type == 'image/gif')) {
                    if (~~(file.size / 1024) < 4000) {
                        image.src = evt.target.result;
                        image.onload = function() {

                            $('#profile_pic_temp').show();
                            $('#profile_pic_temp').attr('src', image.src);
                            $('#imgbase64_temp').val(image.src);
                            // $('#default_banner_pic').hide();
                            
                            $('#default_img_temp').val('0');
                            $('#file_trriger_temp').text('Change Image');
                            $('#remove_image_temp').show();

                        }
                    } else {
                        
                        swal.fire("The maximum size for file upload is 4Mb.", "", "error").then(function(result) {
                            $('#image_change_temp').val('');
                        });
                        move_flag = 1;
                        set_one = 0;
                        return false;
                    }

                } else {
                    swal.fire("Please upload image only", "", "error").then(function(result) {
                        $('#image_change_temp').val('');
                    });
                    move_flag = 1;
                    set_one = 0;
                    return false;
                }
            };
            reader.readAsDataURL(file);
        });

        $('#remove_image_temp').click(function() {
            $('#is_edit_img_flag_temp').val(0);
            $('#image_change_temp').val('');
            move_flag = 1;
            $('#imgbase64_temp').val('');
            $('#remove_image_temp').hide();
            $('#profile_pic_temp').hide();
            $('#default_img_temp').val('0');
            $('#profile_preview_temp').show();
            $('#file_trriger_temp').text('Select Image');
        });

    });    

  </script>
@stop
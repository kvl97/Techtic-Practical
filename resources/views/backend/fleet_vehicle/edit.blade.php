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
            <div class="kt-portlet__body">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#" data-target="#kt_tabs_1_1">Information</a>
                    </li>
                   
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#kt_tabs_1_2">Specifications</a>
                    </li>
                    
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="kt_tabs_1_1" role="tabpanel">
                        <form class="kt-form kt-form--label-right" id="frmEdit" action="{{ ADMIN_URL }}fleet-vehicles/edit/{{ $records->id }}">
            
                 
                            <div class="form-group row">
                                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Status <span class="required">*</span></label>
                                <div class="col-lg-4 col-md-6 col-sm-12 form-group-sub">
                                    <select class="form-control required e_vehicle_status" name="e_vehicle_status" placeholder="Status">
                                        <option value="">-- Select ststus --</option>
                                        <option value="Ready" {{ $records->e_vehicle_status == 'Ready' ? 'selected' : '' }}>Ready</option>
                                        <option value="Unavailable" {{ $records->e_vehicle_status == 'Unavailable' ? 'selected' : '' }}>Unavailable</option>
                                        <option value="Maintenance" {{ $records->e_vehicle_status == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        <option value="Retired" {{ $records->e_vehicle_status == 'Retired' ? 'selected' : '' }}>Retired</option>
                                        <option value="Repair" {{ $records->e_vehicle_status == 'Repair' ? 'selected' : '' }}>Repair</option>
                                        <option value="Disposed" {{ $records->e_vehicle_status == 'Disposed' ? 'selected' : '' }}>Disposed</option>
                                    </select>                  
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Vehicle Number: <span class="required">*</span></label>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <input type="text" class="form-control required" name="v_vehicle_code" maxlength="8" placeholder="Vehicle Number" autocomplete="off" value="{{ $records->v_vehicle_code }}">                  
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">VIN: <span class="required">*</span></label>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <input type="text" class="form-control required" name="v_vin" maxlength="20" placeholder="VIN" autocomplete="off" value="{{$records->v_vin}}">                   
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">US DOT Number:</label>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <input type="text" class="form-control" name="v_usdot_number" maxlength="8" placeholder="US DOT Number" autocomplete="off" value="{{$records->v_usdot_number}}">                   
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Model Year: <span class="required">*</span></label>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <input type="text" class="form-control required" name="v_model_year" maxlength="4" placeholder="Model Year" autocomplete="off" value="{{$records['v_model_year']}}">                  
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Total Seats in Van: <span class="required">*</span></label>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <input type="text" class="form-control required number" name="i_total_customer_booking_seats" maxlength="2" placeholder="Total Seats in Van" autocomplete="off" value="{{$records['i_total_customer_booking_seats']}}">                  
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Total Seats Bookable: <span class="required">*</span></label>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <input type="text" class="form-control required number" name="i_optimal_booking_seats" maxlength="2" placeholder="Total Seats Bookable" autocomplete="off" value="{{$records['i_optimal_booking_seats']}}">                  
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">License Plate: <span class="required">*</span></label>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <input type="text" class="form-control required" name="v_lic_plate" maxlength="8" placeholder="License Plate" autocomplete="off" value="{{$records->v_lic_plate}}">                  
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">License State: <span class="required">*</span></label>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <select class="form-control required" name="c_lic_state" placeholder="State">
                                        <option value=""> -- Select state -- </option>
                                        @foreach($license_state as $key => $val)
                                            <option value="{{$val}}" <?php if($records['c_lic_state'] != '' && $val == $records['c_lic_state']){ echo 'selected';} ?>>{{$key}}</option>
                                        @endforeach
                                    </select>            
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Registration Expiration Date: </label>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <input type="text" class="form-control date_picker" name="d_tag_exp_date" placeholder="Registration Expiration Date" autocomplete="off"  value="{{ $records['d_tag_exp_date'] ? date('m/d/Y',strtotime($records['d_tag_exp_date'])): '' }}"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Acquisition Cost: <span class="required">*</span></label>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <input type="text" class="form-control required number_decimal" name="d_aq_cost" placeholder="Acquisition Cost" autocomplete="off" value="{{$records->d_aq_cost}}" maxlength="6">                  
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Acquisition Date: <span class="required">*</span></label>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <input type="text" class="form-control required date_picker" name="d_aq_date" placeholder="Acquisition Date" autocomplete="off" value="{{ $records['d_aq_date'] ? date('m/d/Y',strtotime($records['d_aq_date'])): '' }}"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Service Start Date: <span class="required">*</span></label>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <input type="text" class="form-control required date_picker_service_start"  name="d_inservice_date" placeholder="Service Start Date" autocomplete="off"
                                    value="{{ $records['d_inservice_date'] ? date('m/d/Y',strtotime($records['d_inservice_date'])): '' }}"/>
                                </div>
                            </div>
                            <div class="form-group row service_end_date">
                                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Service End Date: </label>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <input type="text" class="form-control date_picker_service_end d_end_service_date"  name="d_end_service_date" placeholder="Service End Date" autocomplete="off"  value="{{ $records['d_end_service_date'] ? date('m/d/Y',strtotime($records['d_end_service_date'])): '' }}"/>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Vehicle Image:
                                </label>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="fileinput" data-provides="fileinput">

                                        <img  width="300px" src="<?php
                                            if (File::exists(VEHICLES_PROFILE_IMG_PATH . $records['v_image']) && $records['v_image'] != '') {
                                                echo SITE_URL . VEHICLES_PROFILE_IMG_PATH . $records['v_image'];
                                            } else {
                                                echo ASSET_URL . 'images/default-image-vehicle.png';
                                            }
                                        ?>" class="img-responsive default_img_size profile_preview_elements_temp" name="profileimg"  alt="" id="profile_preview_temp" />

                                        <div class="cropper_area_elements_temp" style="display:none;">
                                            <img width="300px" src="<?php
                                                if (File::exists(VEHICLES_PROFILE_IMG_PATH . $records['v_image']) && $records['v_image'] != '') {
                                                    echo SITE_URL . VEHICLES_PROFILE_IMG_PATH . $records['v_image'];
                                                } else {
                                                    echo ASSET_URL . 'images/default-image-vehicle.png';
                                                }    
                                            ?>" class="img-responsive default_img_size" name="profileimg"  alt=""  id="profile_pic_temp" />
                                        </div>
                                        <img  width="300px" src="{{ ASSET_URL.'images/default-image-vehicle.png'}}" class="img-responsive default_img_size" id="defailt_profile_pic_temp" style="display: none;"/>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div style="margin-top:20px">
                                            <button class="btn btn-default" style="display:inline-block;" type="button" id="file_trriger_temp"><?php if(File::exists(VEHICLES_PROFILE_IMG_PATH . $records['v_image']) && $records['v_image'] != '') { ?> Change Image <?php } else { ?> Select Image <?php } ?></button>
                                        <span class="cropper_area_elements" >
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
                                    <input type="hidden" id="imgbase64_temp" name="imgbase64" value=""  id="imgbase64" /> 
                                    <input type="hidden" id="x" name="x" />
                                    <input type="hidden" id="y" name="y" />
                                    <input type="hidden" id="x2" name="x2" />
                                    <input type="hidden" id="y2" name="y2" />
                                    <input type="hidden" id="w" name="w" />
                                    <input type="hidden" id="h" name="h" />
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
                    </div>
                    
                    <div class="tab-pane" id="kt_tabs_1_2" role="tabpanel">
                      
                        <form class="kt-form kt-form--label-right frm-spec" id="frmEditFleetSpecifications" action="{{ ADMIN_URL }}fleet-specification/edit/{{ $records->id }}">
                        <div class="row">
                            <div class="col-lg-6">
                                <h3 class="kt-portlet__head-title kt-mb-40 kt-mt-10">Specifications</h3>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Make <span class="required">*</span></label>
                                    <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control required" name="v_make" placeholder="Make" autocomplete="off" value="@if(!empty($records['get_vehicle_specification']) && $records['get_vehicle_specification']['v_make']){!! $records['get_vehicle_specification']['v_make'] !!}@endif">                  
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Model <span class="required">*</span></label>
                                    <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control required" name="v_model" placeholder="Model" autocomplete="off" value="@if(!empty($records['get_vehicle_specification']) && $records['get_vehicle_specification']['v_model']){!! $records['get_vehicle_specification']['v_model'] !!}@endif">                  
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Series <span class="required">*</span></label>
                                    <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control required" name="v_series" placeholder="Series" autocomplete="off" value="@if(!empty($records['get_vehicle_specification']) && $records['get_vehicle_specification']['v_series']){!! $records['get_vehicle_specification']['v_series'] !!}@endif">                  
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Vehicle Type <span class="required">*</span></label>
                                    <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control required" name="v_vehicle_type" placeholder="Vehicle Type" autocomplete="off" value="@if(!empty($records['get_vehicle_specification']) && $records['get_vehicle_specification']['v_vehicle_type']){!! $records['get_vehicle_specification']['v_vehicle_type'] !!}@endif">                  
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Body Class <span class="required">*</span></label>
                                    <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control required" name="v_body_class" placeholder="Body Class" autocomplete="off" value="@if(!empty($records['get_vehicle_specification']) && $records['get_vehicle_specification']['v_body_class']){!! $records['get_vehicle_specification']['v_body_class'] !!}@endif">                  
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Wheelbase (In) <span class="required">*</span></label>
                                    <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control number required" name="i_wheelbase_in" placeholder="Wheelbase" autocomplete="off" value="@if(!empty($records['get_vehicle_specification']) && $records['get_vehicle_specification']['i_wheelbase_in']){!! $records['get_vehicle_specification']['i_wheelbase_in'] !!}@endif" maxlength="4">                  
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">GVWR (In) <span class="required">*</span></label>
                                    <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control required" name="v_gvwr" placeholder="GVWR" autocomplete="off" value="@if(!empty($records['get_vehicle_specification']) && $records['get_vehicle_specification']['v_gvwr']){!! $records['get_vehicle_specification']['v_gvwr'] !!}@endif">                  
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Body Type <span class="required">*</span></label>
                                    <div class="col-lg-7 col-md-8 col-sm-12">
                                        <textarea type="text" class="form-control required"  name="v_body_type" placeholder="Body Type" rows="5">@if(!empty($records['get_vehicle_specification']) && $records['get_vehicle_specification']['v_body_type']){!! $records['get_vehicle_specification']['v_body_type'] !!}@endif</textarea>                   
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">NHTSA Notes</label>
                                    <div class="col-lg-7 col-md-7 col-sm-12">
                                        <textarea type="text" class="form-control"  name="t_nhtsa_notes" placeholder="NHTSA Notes" rows="5">@if(!empty($records['get_vehicle_specification']) && $records['get_vehicle_specification']['t_nhtsa_notes']){!! $records['get_vehicle_specification']['t_nhtsa_notes'] !!}@endif</textarea>                
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Tire Size Front</label>
                                    <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control" name="v_tyre_size_front" placeholder="Tire Size Front" autocomplete="off" value="@if(!empty($records['get_vehicle_specification']) && $records['get_vehicle_specification']['v_tyre_size_front']){!! $records['get_vehicle_specification']['v_tyre_size_front'] !!}@endif">                  
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Tire Size Rear </label>
                                    <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control" name="v_tyre_size_rear" placeholder="Tire Size Rear" autocomplete="off" value="@if(!empty($records['get_vehicle_specification']) && $records['get_vehicle_specification']['v_tyre_size_rear']){!! $records['get_vehicle_specification']['v_tyre_size_rear'] !!}@endif">                  
                                    </div>
                                </div>
                            </div>     
                            <div class="col-lg-6">
                                <h3 class="kt-portlet__head-title kt-mb-40 kt-mt-10">Engine Information</h3>
                               
                                <div class="form-group row">
                                    <label class="col-form-label col-md-3 col-lg-3 col-sm-12">Engine Cylinders <span class="required">*</span></label>
                                    <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control required" name="i_engine_cylinders" placeholder="Engine Cylinders" autocomplete="off" value="@if(!empty($records['get_vehicle_specification']) && $records['get_vehicle_specification']['i_engine_cylinders']){!! $records['get_vehicle_specification']['i_engine_cylinders'] !!}@endif">                  
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-3 col-lg-3 col-sm-12">Engine Displacemen <span class="required">*</span></label>
                                    <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control required" name="d_displacement_cc" placeholder="Engine Displacemen" autocomplete="off" value="@if(!empty($records['get_vehicle_specification']) && $records['get_vehicle_specification']['d_displacement_cc']){!! $records['get_vehicle_specification']['d_displacement_cc'] !!}@endif">                  
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-3 col-lg-3 col-sm-12">Engine Displacemen <span class="required">*</span></label>
                                    <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control required" name="d_displacement_ci" placeholder="Engine Displacemen" autocomplete="off" value="@if(!empty($records['get_vehicle_specification']) && $records['get_vehicle_specification']['d_displacement_ci']){!! $records['get_vehicle_specification']['d_displacement_ci'] !!}@endif">                  
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-3 col-lg-3 col-sm-12">Engine Displacemen <span class="required">*</span></label>
                                    <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control required" name="d_displacement_l" placeholder="Engine Displacemen" autocomplete="off" value="@if(!empty($records['get_vehicle_specification']) && $records['get_vehicle_specification']['d_displacement_l']){!! $records['get_vehicle_specification']['d_displacement_l'] !!}@endif">                  
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-3 col-lg-3 col-sm-12">Engine Power(KW)<span class="required">*</span></label>
                                    <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control required" name="d_enginepower_kw" placeholder="Engine Power" autocomplete="off" value="@if(!empty($records['get_vehicle_specification']) && $records['get_vehicle_specification']['d_enginepower_kw']){!! $records['get_vehicle_specification']['d_enginepower_kw'] !!}@endif">                  
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-3 col-lg-3 col-sm-12">Engine Config<span class="required">*</span></label>
                                    <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control required" name="v_engine_config" placeholder="Engine Config" autocomplete="off" value="@if(!empty($records['get_vehicle_specification']) && $records['get_vehicle_specification']['v_engine_config']){!! $records['get_vehicle_specification']['v_engine_config'] !!}@endif">                  
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-3 col-lg-3 col-sm-12">Engine HP<span class="required">*</span></label>
                                    <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control required" name="v_engine_hp" placeholder="Engine HP" autocomplete="off" value="@if(!empty($records['get_vehicle_specification']) && $records['get_vehicle_specification']['v_engine_hp']){!! $records['get_vehicle_specification']['v_engine_hp'] !!}@endif">                  
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-3 col-lg-3 col-sm-12">Fuel<span class="required">*</span></label>
                                    <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control required" name="v_fuel_type" placeholder="Fuel" autocomplete="off" value="@if(!empty($records['get_vehicle_specification']) && $records['get_vehicle_specification']['v_fuel_type']){!! $records['get_vehicle_specification']['v_fuel_type'] !!}@endif">                  
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-3 col-lg-3 col-sm-12">Fuel Capacity<span class="required">*</span></label>
                                    <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control required" name="d_fuel_capacity_gal" placeholder="Fuel Capacity" autocomplete="off" value="@if(!empty($records['get_vehicle_specification']) && $records['get_vehicle_specification']['d_fuel_capacity_gal']){!! $records['get_vehicle_specification']['d_fuel_capacity_gal'] !!}@endif">                  
                                    </div>
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
                    </div>
                   
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
            startDate : new Date($('.date_picker_service_start').val()),
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
            $('#defailt_profile_pic_temp').attr('style', 'display:none');
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
                            $('#defailt_profile_pic_temp').attr('style', 'display:none');

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
            $('#default_img_temp').val('1');
            $('#profile_preview_temp').hide();
            $('#file_trriger_temp').text('Select Image');
            $('#defailt_profile_pic_temp').removeAttr('style', true);
        });
    });
  </script>
@stop
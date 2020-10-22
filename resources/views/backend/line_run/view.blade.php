@extends('backend.layouts.default')
@section('content')
<style>
.table-bordered, .table-bordered td, .table-bordered th {
    border: 1px solid black !important;
}
.table td, .table th {
    padding: .25rem !important;
    vertical-align: middle !important;
}
</style>
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Subheader -->
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
    </div>
    <!-- end:: Subheader -->
    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <!--Begin::Dashboard 1-->
        <!--Begin::Row-->
        
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        {{ $title }}
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            <a href="{{ ADMIN_URL }}linerun" class="btn btn btn-secondary btn-icon-sm">
                                Back To Listing
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#kt_tabs_reservation" data-target="#kt_tabs_reservation">Rocket Manifest</a>
                </li> 
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#kt_tabs_line_run_info">Line Run Info.</a>
                </li>
                 
            </ul>
            <div class="tab-content">
                <div class="tab-pane" id="kt_tabs_line_run_info" role="tabpanel">
                    @if(isset($permission) && isset($permission[13]['i_add_edit']) && $permission[13]['i_add_edit'] == 1)
                    <div class="kt-portlet__head-actions" style="text-align:end;margin:0px 10px 10px 0px;">
                        <a class="btn btn-secondary btn-icon-sm" href="{{ ADMIN_URL }}linerun/edit/{{$record['id']}}"><i class="la la-edit"></i>Edit</a>
                    </div>
                    @endif
                    <div class="row">
                        
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="kt-portlet" style="border: groove; border-color: #ebedf2; border-width: 1px;">
                                <div class="kt-portlet__head" style="background-color: #f0f0f9; min-height: 50px !important; padding: 0 15px !important;">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">
                                            <b>Line Run Information</b>
                                        </h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body" style="padding:15px !important;">
                                    <div class="form-group kt-mb-0 row">
                                        <label class="col-form-label col-md-6 col-lg-6 col-sm-12"><b>Run key:</b> </label>
                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                            <label class="kt-mt-10"> {{ $record['c_run_key'] }} </label>                  
                                        </div>
                                    </div>

                                    <div class="form-group kt-mb-0 row">
                                        <label class="col-form-label col-md-6 col-lg-6 col-sm-12"><b>Origin Service Area:</b> </label>
                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                            <label class="kt-mt-10"> {{ $record['geo_origin_service_area']['v_area_label'] }} </label>                  
                                        </div>
                                    </div>

                                    <!-- <div class="form-group kt-mb-0 row">
                                        <label class="col-form-label col-md-6 col-lg-6 col-sm-12"><b>Scheduled departure Time:</b> </label>
                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                            <label class="kt-mt-10"> {{ date('H:i a', strtotime($record['t_scheduled_dep_time'])) }} </label>                  
                                        </div>
                                    </div> -->

                                    <div class="form-group kt-mb-0 row">
                                        <label class="col-form-label col-md-6 col-lg-6 col-sm-12"><b>Bookable Seats:</b> </label>
                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                            <label class="kt-mt-10"> {{ $record['i_num_available'] }} </label>                  
                                        </div>
                                    </div>

                                    <div class="form-group kt-mb-0 row">
                                        <label class="col-form-label col-md-6 col-lg-6 col-sm-12"><b>Run Status:</b> </label>
                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                            <label class="kt-mt-10"> {{ $record['e_run_status'] }} </label>                  
                                        </div>
                                    </div>

                                    <div class="form-group kt-mb-0 row">
                                        <label class="col-form-label col-md-6 col-lg-6 col-sm-12"><b>Special Instruction:</b> </label>
                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                            <label class="kt-mt-10"> {{ $record['t_special_instruction'] }} </label>                 
                                        </div>
                                    </div>

                                    <div class="form-group kt-mb-0 row">
                                        <label class="col-form-label col-md-6 col-lg-6 col-sm-12"><b>Run Date:</b> </label>
                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                            <label class="kt-mt-10">  {{ date('m/d/Y',strtotime($record['d_run_date'])) }} </label>                 
                                        </div>
                                    </div>
                                   
                                    <div class="form-group kt-mb-0 row">
                                        <label class="col-form-label col-md-6 col-lg-6 col-sm-12"><b>Destination Service Area:</b> </label>
                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                            <label class="kt-mt-10"> {{ $record['geo_dest_service_area']['v_area_label'] }} </label>                 
                                        </div>
                                    </div>
                                   
                                        <div class="form-group kt-mb-0 row">
                                            @if(isset($record['geo_dest_service_area']['v_area_label']) && $record['geo_dest_service_area']['v_area_label'] == "Base")
                                                <label class="col-form-label col-md-6 col-lg-6 col-sm-12"><b>Scheduled Departure Time:</b> </label>
                                            @else
                                                <label class="col-form-label col-md-6 col-lg-6 col-sm-12"><b>Scheduled Arrival Time:</b> </label>
                                            @endif
                                            <div class="col-md-6 col-lg-6 col-sm-12">
                                                <label class="kt-mt-10"> {{ date('g:i A', strtotime($record['t_scheduled_arr_time'])) }} </label>                 
                                            </div>
                                        </div>

                                    <div class="form-group kt-mb-0 row">
                                        <label class="col-form-label col-md-6 col-lg-6 col-sm-12"><b>Run Number:</b> </label>
                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                            <label class="kt-mt-10"> {{ $record['id'] }} </label>                 
                                        </div>
                                    </div>

                                    <div class="form-group kt-mb-0 row">
                                        <label class="col-form-label col-md-6 col-lg-6 col-sm-12"><b>Service Type:</b> </label>
                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                            <label class="kt-mt-10"> 
                                            {{ $record['e_service_type'] }} 
                                            </label>                 
                                        </div>
                                    </div>
                                    
                                    <div class="form-group kt-mb-0 row">
                                        <label class="col-form-label col-md-6 col-lg-6 col-sm-12"><b>Total Seats:</b> </label>
                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                            <label class="kt-mt-10"> {{ $record['i_num_total'] }} </label>                 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            @if(isset($record['driver']))
                                @if(count($record['driver']) > 0)
                                    <div class="kt-portlet" style="border: groove; border-color: #ebedf2; border-width: 1px;">
                                        <div class="kt-portlet__head" style="background-color: #f0f0f9; min-height: 50px !important; padding: 0 15px !important;">
                                            <div class="kt-portlet__head-label">
                                                <h3 class="kt-portlet__head-title">
                                                    <b>Driver Information</b>
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="kt-portlet__body" style="padding:15px !important;">
                                            <div class="row">
                                                <div class="col-md-6 col-lg-6 col-sm-12">
                                                    <div class="form-group kt-mb-0 row">
                                                        <label class="col-form-label col-md-6 col-lg-6 col-sm-12"><b>Driver Name:</b></label>
                                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                                            <label class="kt-mt-10"> {{ $record['driver']['v_firstname']." ".$record['driver']['v_lastname'] }} </label>                  
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group kt-mb-0 row">
                                                        <label class="col-form-label col-md-6 col-lg-6 col-sm-12"><b>Extension:</b></label>
                                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                                            <label class="kt-mt-10"> {{ $record['driver']['driver_extension'][0]['v_extension'] }} </label>                  
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-md-6 col-lg-6 col-sm-12">
                                                    
                                                    <div class="row">
                                                        <label class="col-form-label col-md-5 col-lg-5 col-sm-12"><b>Image:</b> </label>
                                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                                            <div class="fileinput" data-provides="fileinput">
                                                        
                                                                <img width="100" src="<?php
                                                                if (File::exists(DRIVER_PROFILE_THUMB_IMG_PATH . $record['driver']['v_profile_image']) && $record['driver']['v_profile_image'] != '') {
                                                                    echo SITE_URL.DRIVER_PROFILE_THUMB_IMG_PATH .$record['driver']['v_profile_image'];
                                                                } else {
                                                                    echo ASSET_URL . 'images/no_image.png';
                                                                }
                                                                ?>" class="img-responsive default_img_size" name="profileimg"  alt=""  />

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif    
                            @endif                                          

                            @if(isset($record['vehicle_fleet']))                                    
                                @if(count($record['vehicle_fleet']) > 0)  
                                    <div class="kt-portlet" style="border: groove; border-color: #ebedf2; border-width: 1px;">
                                        <div class="kt-portlet__head" style="background-color: #f0f0f9; min-height: 50px !important; padding: 0 15px !important;">
                                            <div class="kt-portlet__head-label">
                                                <h3 class="kt-portlet__head-title">
                                                    <b>Vehicle Information</b>
                                                </h3>
                                            </div>
                                        </div>
                                        
                                        <div class="kt-portlet__body" style="padding:15px !important;">
                                            <div class="row">
                                                <div class="col-md-6 col-lg-6 col-sm-12">
                                                    <div class="form-group kt-mb-0 row">
                                                        <label class="col-form-label col-md-6 col-lg-6 col-sm-12"><b>Vehicle Number:</b> </label>
                                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                                            <label class="kt-mt-10"> {{ $record['vehicle_fleet']['v_lic_plate'] }} </label>                  
                                                        </div>
                                                    </div>
                                                    <div class="form-group kt-mb-0 row">
                                                        <label class="col-form-label col-md-6 col-lg-6 col-sm-12"><b>Vehicle Type:</b> </label>
                                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                                            <label class="kt-mt-10"> {{ $record['vehicle_fleet']['get_vehicle_specification']['v_vehicle_type'] }} </label>                  
                                                        </div>
                                                    </div>

                                                    <div class="form-group kt-mb-0 row">
                                                        <label class="col-form-label col-md-6 col-lg-6 col-sm-12"><b>Make:</b> </label>
                                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                                            <label class="kt-mt-10"> {{ $record['vehicle_fleet']['get_vehicle_specification']['v_make'] }} </label>                  
                                                        </div>
                                                    </div>

                                                    <div class="form-group kt-mb-0 row">
                                                        <label class="col-form-label col-md-6 col-lg-6 col-sm-12"><b>Model:</b> </label>
                                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                                            <label class="kt-mt-10"> {{ $record['vehicle_fleet']['get_vehicle_specification']['v_model'] }} </label>                  
                                                        </div>
                                                    </div>

                                                    <div class="form-group kt-mb-0 row">
                                                        <label class="col-form-label col-md-6 col-lg-6 col-sm-12"><b>Series:</b> </label>
                                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                                            <label class="kt-mt-10"> {{ $record['vehicle_fleet']['get_vehicle_specification']['v_series'] }} </label>                  
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6 col-lg-6 col-sm-12">
                                                    
                                                    <div class="row">
                                                        <label class="col-form-label col-md-5 col-lg-5 col-sm-12"><b>Image:</b> </label>
                                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                                            <div class="fileinput" data-provides="fileinput">
                                                                <img width="100" src="<?php
                                                                if (File::exists(VEHICLES_PROFILE_THUMB_IMG_PATH . $record['vehicle_fleet']['v_image']) && $record['vehicle_fleet']['v_image'] != '') {
                                                                    echo SITE_URL.VEHICLES_PROFILE_THUMB_IMG_PATH . $record['vehicle_fleet']['v_image'];
                                                                } else {
                                                                    echo ASSET_URL . 'images/no_image.png';
                                                                }
                                                                ?>" class="img-responsive default_img_size" name="profileimg"  alt=""  />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div> 
                    
                </div>
                <div class="tab-pane active" id="kt_tabs_reservation" role="tabpanel">
                    <form class="kt-form kt-form--label-right" id="printForm" action="" methode="POST">
                        <div class="kt-portlet__head-actions" style="text-align:end;margin:0px 10px 10px 0px;">
                        @if(count($reservation_detail) > 0)
                            <button type="button" class="btn btn-secondary btn-icon-sm" id="print_Page" printId="{{ $record['id'] }}"><i class="la la-print"></i>Print</button>
                        @endif
                        </div>                                           
                        <div class="col-md-12 col-lg-12 table-responsive mb-3">
                            <table class="table table-bordered" id="myTable" style="min-width: 768px;">
                                @if(count($reservation_detail) == 0)
                                <thead>
                                    <tr>
                                        <th style="text-align: center"><strong>No reservation found.</strong></th>
                                    </tr>
                                </thead>
                                @else    
                                    <input type="hidden" name="total_count" value="{{count($reservation_detail)}}">                           
                                    @foreach($reservation_detail as $key => $value)

                                    <?php $data = json_decode($value['v_manifest_json'], true); /*  pr($data['pu_time_1']); exit; */ ?>
                                    
                                    
                                        @if($key == 0)
                                            <tr>
                                                <th colspan="3" style="text-align: center;"><strong>{{$value['d_travel_date'] ? date('l, F d, Y',strtotime ($value['d_travel_date'])) : '-'}}</strong></th>
                                                <th colspan="4" style="text-align: center;"><strong>Westbound</strong></th>
                                            </tr>
                                        @endif
                                        <tr style="background: #fe9600;">
                                            <td style="width:7%;text-align: center;"><strong>P/U #</strong></td>
                                            <td style="width:8%;text-align: center;"><strong>Direction</strong></td>
                                            <td colspan="5"></td>
                                            
                                        </tr>
                                        <tr>
                                            <td rowspan="11" class="align-middle" style="background: darkseagreen;text-align: center;"><strong>{{$key+1}}</strong></td>
                                            <td rowspan="11" class="align-middle" style="writing-mode: tb-rl;transform:rotate(270deg);"><strong>Westbound</strong></td>
                                            <td style="width:17%;background: #dcdcf3;"><strong>Address</strong></td>
                                            <?php   if(isset($value['ReservAtionInfo']['v_pickup_address']) != '') { 
                                                $street_origin = $value['ReservAtionInfo']['v_pickup_address'];
                                            } else {
                                                $street_origin = '-';
                                            } ?>
                                            <td style="width:20%">{{$street_origin}}</td>
                                            <td style="width:18%"><strong></strong></td>
                                            <td style="width:15%;background: #dcdcf3;"><strong>P/U Time</strong></td>
                                            <td style="width:15%"><input type="text" class="" name="pu_time_{{$key+1}}" value="<?= isset($data['pu_time_'.($value['id'])]) ? $data['pu_time_'.($value['id'])] : '' ?>" style="width: 100%;"></td>
                                            <input type="hidden" class="" name="id_{{$key+1}}" value="{{$value['id']}}">
                                            
                                            
                                        </tr>
                                        <tr>
                                            
                                        
                                            <td style="width:17%;background: #dcdcf3;"><strong>Airline</strong></td>
                                            <td style="width:20%">{{$value['ReservAtionInfo']['v_flight_name'] ? $value['ReservAtionInfo']['v_flight_name'] : '-'}}</td>
                                            <td style="width:18%"><strong></strong></td>
                                            <td style="width:15%;background: #dcdcf3;"><strong>P/U Mileage</strong></td>
                                            <td style="width:15%"><input type="text" class="" name="pu_milege_{{$key+1}}" value="<?= isset($data['pu_milege_'.($value['id'])]) ? $data['pu_milege_'.($value['id'])] : '' ?>" style="width: 100%;"></td>
                                            
                                        </tr>
                                        <tr>
                                        
                                            
                                            <td style="width:17%;background: #dcdcf3;"><strong>Flight</strong></td>
                                            <td style="width:20%">{{$value['ReservAtionInfo']['v_flight_number'] ? $value['ReservAtionInfo']['v_flight_number'] : '-'}}</td>
                                            <td style="width:18%"><strong></strong></td>
                                            <td style="width:15%;background: #dcdcf3;"><strong>D/O Time</strong></td>
                                            <td style="width:15%"><input type="text" class="" name="do_time_{{$key+1}}" value="<?= isset($data['do_time_'.($value['id'])]) ? $data['do_time_'.($value['id'])] : '' ?>" style="width: 100%;"></td>
                                            
                                        </tr>
                                        <tr>
                                            
                                            
                                            <td style="width:17%;background: #dcdcf3;"><strong>Est. Arrival / Actual</strong></td>
                                            <td style="width:20%">{{$value['ReservAtionInfo']['t_flight_time'] ? date(TIME_FORMAT,strtotime ($value['ReservAtionInfo']['t_flight_time'])) : '-'}}</td>
                                            <td style="width:18%"><input type="text" class="" name="actual_time_{{$key+1}}"  value="<?= isset($data['actual_time_'.($value['id'])]) ? $data['actual_time_'.($value['id'])] : '' ?>" style="width: 100%;"></td>
                                            <td style="width:15%;background: #dcdcf3;"><strong>D/O Mileage</strong></td>
                                            <td style="width:15%"><input type="text" class="" name="do_mileage_{{$key+1}}" value="<?= isset($data['do_mileage_'.($value['id'])]) ? $data['do_mileage_'.($value['id'])] : '' ?>" style="width: 100%;"></td>
                                            
                                        </tr>
                                        <tr>
                                        
                                            
                                            <td style="width:17%;background: #dcdcf3;"><strong>P/U Time / Contact</strong></td>
                                            <td style="width:20%"> {{$value['ReservAtionInfo']['t_comfortable_time'] ? date(TIME_FORMAT,strtotime ($value['ReservAtionInfo']['t_comfortable_time'])) : '-'}}</td>
                                        
                                            <td style="width:18%"><input type="text" class="" name="contact_text_{{$key+1}}" value="<?= isset($data['contact_text_'.($value['id'])]) ? $data['contact_text_'.($value['id'])] : '' ?>"  style="width: 100%;"></td>
                                            <td style="width:15%;background: #dcdcf3;"><strong>International</strong></td>
                                            <td style="width:15%">{{$value['ReservAtionInfo']['e_flight_type'] == "International" ? 'Yes' : '-'  }}</td>
                                            
                                        </tr>
                                        <tr>
                                            
                                        
                                            <td style="width:17%;background: #dcdcf3;"><strong>Name</strong></td>
                                            <td style="width:20%">{{$value['ReservAtionInfo']['v_contact_name'] ? $value['ReservAtionInfo']['v_contact_name'] : '-'}}</td>
                                            <td style="width:18%"><strong></strong></td>
                                            <td style="width:15%;background: #dcdcf3;"><strong>Domestic</strong></td>
                                            <td style="width:15%">{{$value['ReservAtionInfo']['e_flight_type'] == "Domestic" ? 'Yes' : '-'  }}</td>
                                            
                                        </tr>
                                        <tr>
                                            
                                            
                                            <td style="width:17%;background: #dcdcf3;"><strong>PAX</strong></td>
                                            <td style="width:20%">{{$value['ReservAtionInfo']['i_total_num_passengers'] ? $value['ReservAtionInfo']['i_total_num_passengers'] : '0'}}</td>
                                            <td style="width:18%"><strong></strong></td>
                                            <td style="width:15%;background: #dcdcf3;"><strong>Res #</strong></td>
                                            <td style="width:15%">{{$value['ReservAtionInfo']['v_reservation_number'] ? $value['ReservAtionInfo']['v_reservation_number'] : '-'}}</td>
                                            
                                        </tr>
                                        <tr>
                                        
                                        
                                            <td style="width:17%;background: #dcdcf3;"><strong>Bags</strong></td>
                                            <?php $bags = ($value['ReservAtionInfo']['i_number_of_luggages'] + $value['ReservAtionInfo']['i_num_pets'] ); ?>
                                            <td style="width:20%">{{ $bags ? $bags  : '-'}}</td>
                                            <td style="width:18%"><strong></strong></td>
                                            <td colspan="2" rowspan="4" style="width:30%"></td>
                                            
                                            
                                        </tr>
                                        <tr>
                                            
                                            
                                            <td style="width:17%;background: #dcdcf3;"><strong>Phone</strong></td>   
                                            <td style="width:20%">{{$value['ReservAtionInfo']['v_contact_phone_number'] ? $value['ReservAtionInfo']['v_contact_phone_number'] : '-'}}</td>
                                            <td style="width:18%"><strong></strong></td>
                                            
                                        
                                            
                                        </tr>
                                        <tr>
                                            
                                        
                                            <td style="width:17%;background: #dcdcf3;"><strong>Destination</strong></td>
                                            <?php   if(isset($value['ReservAtionInfo']['v_dropoff_address']) != '') { 
                                                $street_dest = $value['ReservAtionInfo']['v_dropoff_address'];
                                            } else {
                                                $street_dest = '-';
                                            } ?>
                                            <td style="width:20%">{{$street_dest}}</td>
                                            <td style="width:18%"><strong></strong></td>
                                            
                                            
                                            
                                        </tr>
                                        <tr>

                                            <td style="width:17%;background: #dcdcf3;"><strong>Cross St</strong></td>
                                            <td><input type="text" class="" name="cross_st_{{$key+1}}" style="width: 100%;" value="<?= isset($data['cross_st_'.($value['id'])]) ? $data['cross_st_'.($value['id'])] : '' ?>"></td>
                                            <td></td>
                                        </tr>
                                        @if($key < (count($reservation_detail) - 1))
                                            <tr>
                                                <td colspan="7">&nbsp;</td>
                                            </tr>
                                        @endif
                                                        
                                    
                                    @endforeach
                                
                                @endif
                                
                                
                            </table>
                        </div>
                        <div class="d-none" id="print_manifest">
                        </div>    
                        
                    </form>

                </div>
            </div>
            </div>
        </div>  

        
        
        
        <!--End::Row-->
        <!--End::Dashboard 1-->
    </div>
    <!-- end:: Content -->
</div>

@stop

@section('custom_js')
  <script>
    $(document).ready(function() {
        $('.kt_time_picker').val('');
        rowCount = $('#myTable').length;
        $('#print_Page').on('click',function(){
            var id = $(this).attr('printId');
            var url = (ADMIN_URL + 'rocket-manifest/print/'+id);
            var data = $('#printForm').serialize()
            $.post(url,data,function(response) {
            
                $('#print_manifest').html(response);

                var divToPrint=document.getElementById("print_manifest");
                $(divToPrint).find('#print_manifest').show();
                newWin= window.open("");
                newWin.document.write(divToPrint.outerHTML);
                newWin.print();
                newWin.close();
                $(divToPrint).find('#print_manifest').hide();
            
            });

        });
        
    });

  </script>
@stop

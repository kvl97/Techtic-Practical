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
                                Add Reservation
                            </h3>
                        </div>
                    </div>

                    <!--begin::Form-->
                    <form class="kt-form kt-form--label-right add-reservation-frm-page" id="<?php if(!isset($cust_reservation)) {?>frmAdd<?php } else {?>frmAdd_cust_reservation<?php } ?>" action="{{ ADMIN_URL }}<?php if(!isset($cust_reservation)) {?>reservations/add <?php } else {?>reservations/add/customer/{{ $customer_id }} <?php } ?>">
                        <div class="kt-portlet__body">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    @if(!isset($cust_reservation))
                                        <div class="form-group row">
                                            <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Customer Name <span class="required">*</span></label>
                                            <div class="col-lg-7 col-md-7 col-sm-12">
                                                <select class="form-control required i_customer_id" name="i_customer_id" placeholder="Customer Name">
                                                    <option value="">Select</option>
                                                    @if(count($customer_data) > 0)
                                                        @foreach($customer_data as $val)
                                                            <option value="{{ $val['id'] .' '.$val['v_firstname'].' '.$val['v_lastname'] }}">{{ $val['v_firstname'].' '.$val['v_lastname'] }}</option>
                                                        @endforeach
                                                    @endif 
                                                </select>  
                                                <input type="hidden" class="reservation_customer_name" name="reservation_customer_name" id="reservation_category_name" value="">             
                                            </div>
                                        </div>
                                    @else 
                                        <div class="form-group row">
                                            <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Customer Name </label>
                                            <div class="col-lg-7 col-md-7 col-sm-12">
                                                <label class="kt-mt-10" name="i_customer_id" placeholder="Customer Name">{{ $customer_data['v_firstname'].' '.$customer_data['v_lastname']}}</label>
                                                <input type="hidden" class="reservation_customer_name" name="reservation_customer_name" id="reservation_customer_name" value="{{ $customer_data['v_firstname'].' '.$customer_data['v_lastname']}}"> 
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Origin Point <span class="required">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                            <select class="form-control required i_origin_point_id" placeholder="Origin Point" id="i_origin_point_id" name="i_origin_point_id">
                                            
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row flight_time ">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12 flignt_time_lable">Fight/Train/Bus Time</label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control kt_time_picker t_flight_time" name="t_flight_time" placeholder="Fight/Train/Bus Time" onblur="$(this).attr('readonly','readonly');" readonly="readonly">
                                        </div>
                                    </div>

                                    
                                    <div class="form-group row ">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Class Type <span class="required">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-12 radio-btn-msg">
                                            <div class="kt-radio-inline mt-10">
                                                <label class="kt-radio">
                                                <input type="radio" class="required-least-one-radio e_class_type" name="e_class_type" value="OW" id="one_way" groupid="class_type"> One Way
                                                </label>

                                                <label class="kt-radio">
                                                <input type="radio" class="required-least-one-radio e_class_type" name="e_class_type" value="RT" id="round_trip" groupid="class_type"> Round Trip
                                                </label>
                                                <span class="check"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Depart Date <span class="required">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control required date_picker_depart d_depart_date" name="d_depart_date" placeholder="Depart Date"  onblur="$(this).attr('readonly','readonly');" readonly="readonly">
                                        </div>
                                    </div>

                                    <!-- <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12"> Departure Time<span class="required">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control required kt_time_picker" name="t_departure_time" placeholder="Departure Time"  onblur="$(this).attr('readonly','readonly');" readonly="readonly">
                                        </div>
                                    </div>-->
                                    <!-- <div class="form-group row airline_info_field ">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Airline Info. <span class="required">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                            <select class="form-control required i_oneway_airline_id" id="i_oneway_airline_id" name="i_oneway_airline_id" placeholder="Airline Info"></select>               
                                        </div>
                                    </div> -->

                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12"> Contact Number<span class="required">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control required phone" name="v_contact_phone_number" placeholder="Contact Number">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12"> Best Time For Call<span class="required">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control required kt_time_picker" name="t_best_time_tocall" placeholder="Best Time For Call"  onblur="$(this).attr('readonly','readonly');" readonly="readonly">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Number of Passengers<span class="required">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-12 row">
                                            <div class="col-lg-8 col-md-8 col-sm-12">
                                                <input type="text" class="form-control required i_total_num_passengers" name="i_total_num_passengers" placeholder="Number of Passengers" id="total_passenger" onclick="addInfoLink();">
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <a href="" class="passenger_info_popup popup_info_link" id="passenger_info_popup" style="display:none;" onclick="addFields();return false;" >Add info</a> 
                                            </div>        
                                        </div>
                                    </div> 

                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Number of luggages</label>
                                        <div class="col-lg-7 col-md-7 col-sm-12 row">
                                            <div class="col-lg-8 col-md-8 col-sm-12">
                                                <input type="text" class="form-control i_number_of_luggages" name="i_number_of_luggages" placeholder="Number of luggages" id="total_luggages" readonly>  
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <a href="" class="luggages_info_popup popup_info_link" id="luggages_info_popup" data-toggle="modal" data-target="#kt_modal_luggages_info" >Add info</a> 
                                            </div>    
                                        </div>
                                    </div> 

                                    <!-- <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Private Approved By</label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                        <select class="form-control" name="i_private_approved_by" placeholder="Private Approved By">
                                                <option value="">Select</option>
                                                @if(count($admin_data) > 0)
                                                    @foreach($admin_data as $val)
                                                    <option value="{{ $val['id'] }}">{{ $val['v_firstname'].' '.$val['v_lastname'] }}</option>
                                                    @endforeach
                                                @endif  
                                            </select>          
                                        </div>
                                    </div>  -->
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-9 col-lg-9 col-sm-12"> The earliest time I am comfortable targeting pick up at Airport, Train, etc is: <span class="required">*</span></label>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                        <input type="text" class="form-control  kt_time_picker t_comfortable_time" name="t_comfortable_time" placeholder="Time"  onblur="$(this).attr('readonly','readonly');" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Special Instruction</label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                            <textarea class="form-control t_special_instruction" name="t_special_instruction" placeholder="Special Instruction"></textarea>
                                        </div>
                                    </div>

                                    <!-- <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12"> Place Type<span class="required">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control required" name="v_type" placeholder="Place Type">
                                        </div>
                                    </div> -->
                                    
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12">

                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Travel Form <span class="required">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                            <select class="form-control required i_reservation_category_id" name="i_reservation_category_id" placeholder="Travel Form">
                                                <option value="">Select</option>
                                                @if(count($reservation_category) > 0)
                                                    @foreach($reservation_category as $val)
                                                        <option value="{{ $val['id']}}">{{ $val['v_title'] }}</option>
                                                    @endforeach
                                                @endif  
                                            </select>   
                                            <input type="hidden" class="i_reservation_category_name" name="i_reservation_category_name" id="i_reservation_category_name" value="">            
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Destination Point <span class="required">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-12 destination_dropdwon">
                                            <select class="form-control required i_destination_point_id" placeholder="Destination Point" id="i_destination_point_id" name="i_destination_point_id">
                                            
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row flight_type_airport ">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Flight Type </label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                            <select class="form-control e_flight_type" name="e_flight_type" placeholder="Flight Type">
                                                <option value="">Select</option>
                                                <option value="Domestic">Domestic</option>
                                                <option value="International">International</option>
                                                 
                                            </select>             
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row flight_number ">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12 flignt_number_lable">Fight/Train/Bus Num.</label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control v_flight_number" name="v_flight_number" placeholder="Fight/Train/Bus Number">
                                        </div>
                                    </div>
                                    
                                    <!-- <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Fight/Train/Bus Name</label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                            <input type="text" class="form-control v_flight_name" name="v_flight_name" placeholder="Fight/Train/Bus Name">
                                        </div>
                                    </div> -->
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Return Date </label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control date_picker_return d_return_date return_trip_fields" name="d_return_date" placeholder="Return Date" onblur="$(this).attr('readonly','readonly');" readonly="readonly" disabled>
                                        </div>
                                    </div>

                                    <!-- <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Arrival Time<span class="required">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control required kt_time_picker" name="t_arrival_time" placeholder="Arrival Time">
                                        </div>
                                    </div> -->

                                    

                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Contact Email <span class="required">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control required email" name="v_contact_email" placeholder="Contact Email">
                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Voice Mail Setup <span class="required">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                            <div class="kt-radio-inline mt-10">
                                                <label class="kt-radio">
                                                <input type="radio" class="required-least-one-radio" name="e_voice_mail_setup" value="YES" id="e_voice_mail_setup_yes" groupid="voice_mail_setup"> YES
                                                </label>

                                                <label class="kt-radio">
                                                <input type="radio" class="required-least-one-radio" name="e_voice_mail_setup" value="NO" id="e_voice_mail_setup_no" groupid="voice_mail_setup"> NO
                                                </label>
                                                <span class="check"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Number of Pets
                                        </label>
                                        <div class="col-lg-7 col-md-7 col-sm-12 row">
                                            <div class="col-lg-8 col-md-8 col-sm-12">
                                                <input type="text" class="form-control i_num_pets" name="i_num_pets" placeholder="Number of Pets" id="tatal_pets" readonly>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <a href="" class="pets_info_popup popup_info_link" id="pets_info_popup" data-toggle="modal" data-target="#kt_modal_pets_info" >Add info</a> 
                                            </div>   
                                        </div>
                                    </div>

                                    <!-- <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Shuttle Type <span class="required">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                            <select class="form-control required" name="e_shuttle_type" placeholder="Shuttle Type">
                                                <option value="">Select</option>  
                                                <option value="Private">Private</option>
                                                <option value="Shared">Shared</option>       
                                            </select>          
                                        </div>
                                    </div> -->
                                    <div class="form-group row ">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Shuttle Type <span class="required">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-12 radio-btn-msg">
                                            <div class="kt-radio-inline mt-10">
                                                <label class="kt-radio">
                                                <input type="radio" class="required-least-one-radio" name="e_shuttle_type" value="Private" id="private" groupid="shuttle_type"> Private
                                                </label>

                                                <label class="kt-radio">
                                                <input type="radio" class="required-least-one-radio" name="e_shuttle_type" value="Shared" id="shared" groupid="shuttle_type"> Shared
                                                </label>
                                                <span class="check"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-9 col-lg-9 col-sm-12"> To make the shared service balance based on reservations on this day, I may need to target departure as late as: <span class="required">*</span></label>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                        <input type="text" class="form-control required kt_time_picker" name="t_target_time" placeholder="Time"  onblur="$(this).attr('readonly','readonly');" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Reservation Status <span class="required">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                        <select class="form-control required" name="e_reservation_status" placeholder="Status">
                                            <option value="">Select</option> 
                                            <option value="Quote">Quote</option>
                                            <option value="Booked">Booked</option>
                                            <option value="Cancelled">Cancelled</option>
                                            <option value="Refund Requested">Refund Requested</option>
                                            <option value="Refunded">Refunded</option>
                                        </select>                
                                        </div>
                                    </div>
                                    
                                    
                                </div>
                                
                                
                            </div> 
                                 
                            <div class="return_reservation_detail" style="display:none;" >
                                <h5><b>Return Trip Information:</b></h5>
                                <div class="row" >
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group row">
                                            <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Return Date </label>
                                            <div class="col-lg-7 col-md-7 col-sm-12">
                                                <!-- <input type="text" class="form-control date_picker_return d_return_date return_trip_fields" name="d_return_date_rt" placeholder="Return Date" onblur="$(this).attr('readonly','readonly');"> -->
                                                <label class="return_date_rt kt-mt-10" id="return_date_rt"></label>
                                            </div>
                                        </div>
                                        <div class="form-group row flight_time_round_trip ">
                                            <label class="col-form-label col-md-4 col-lg-4 col-sm-12 flignt_time_lable_round_trip">Fight/Train/Bus Time</label>
                                            <div class="col-lg-7 col-md-7 col-sm-12">
                                            <input type="text" class="form-control kt_time_picker t_flight_time_round_trip" name="t_flight_time_round_trip" placeholder="Fight/Train/Bus Time"  onblur="$(this).attr('readonly','readonly');" readonly="readonly">
                                            </div>
                                        </div>

                                       <!--  <div class="form-group row">
                                            <label class="col-form-label col-md-4 col-lg-4 col-sm-12"> Departure Time<span class="required">*</span></label>
                                            <div class="col-lg-7 col-md-7 col-sm-12">
                                                <input type="text" class="form-control required kt_time_picker rt_required_fields" name="t_departure_time_rt" placeholder="Departure Time"  onblur="$(this).attr('readonly','readonly');" readonly="readonly">
                                            </div>
                                        </div> -->
                                        <!-- <div class="form-group row return-airline-info ">
                                            <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Return Airline Info. </label>
                                            <div class="col-lg-7 col-md-7 col-sm-12">
                                                <select class="form-control  i_return_airline_id_rt" id="i_return_airline_id" name="i_return_airline_id_rt" placeholder="Return Airline Info." readonly="readonly">
                                                    
                                                </select>               
                                            </div>
                                        </div> -->

                                        <!--  -->

                                        <div class="form-group row">
                                            <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Number of Passengers<span class="required">*</span></label>
                                            <div class="col-lg-7 col-md-7 col-sm-12 row">
                                                <div class="col-lg-8 col-md-8 col-sm-12">
                                                    <input type="text" class="form-control required i_total_num_passengers_rt rt_required_fields" name="i_total_num_passengers_rt" placeholder="Number of Passengers" id="total_passenger_rt" onclick="addInfoLink('rt');">
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-12">
                                                    <a href="" class="passenger_info_popup_rt popup_info_link" id="passenger_info_popup_rt" style="display:none;" onclick="addFields('rt');return false;" >Add info</a> 
                                                </div>        
                                            </div>
                                        </div> 

                                        <div class="form-group row">
                                            <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Number of luggages</label>
                                            <div class="col-lg-7 col-md-7 col-sm-12 row">
                                                <div class="col-lg-8 col-md-8 col-sm-12">
                                                    <input type="text" class="form-control i_number_of_luggages_rt" name="i_number_of_luggages_rt" placeholder="Number of luggages" id="total_luggages_rt" readonly>  
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-12">
                                                    <a href="" class="luggages_info_popup_rt popup_info_link" id="luggages_info_popup_rt" data-toggle="modal" data-target="#kt_modal_luggages_info_rt" >Add info</a> 
                                                </div>    
                                            </div>
                                        </div> 
                                        <div class="form-group row ">
                                            <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Shuttle Type <span class="required">*</span></label>
                                            <div class="col-lg-7 col-md-7 col-sm-12 radio-btn-msg">
                                                <div class="kt-radio-inline mt-10">
                                                    <label class="kt-radio">
                                                    <input type="radio" class="required-least-one-radio rt_required_fields" name="e_shuttle_type_rt" value="Private" id="private" groupid="shuttle_type"> Private
                                                    </label>

                                                    <label class="kt-radio">
                                                    <input type="radio" class="required-least-one-radio rt_required_fields" name="e_shuttle_type_rt" value="Shared" id="shared" groupid="shuttle_type"> Shared
                                                    </label>
                                                    <span class="check"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-md-9 col-lg-9 col-sm-12"> The earliest time I am comfortable targeting pick up at Airport, Train, etc is: <span class="required">*</span></label>
                                            <div class="col-lg-2 col-md-2 col-sm-12">
                                            <input type="text" class="form-control required kt_time_picker t_comfortable_time_rt" name="t_comfortable_time_rt" placeholder="Time"  onblur="$(this).attr('readonly','readonly');" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Special Instruction</label>
                                            <div class="col-lg-7 col-md-7 col-sm-12">
                                                <textarea class="form-control t_special_instruction_rt" name="t_special_instruction_rt" placeholder="Special Instruction"></textarea>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Travel Form <span class="required">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                            <select class="form-control i_reservation_category_id_round_trip" name="i_reservation_category_id_round_trip" placeholder="Travel Form">
                                                <option value="">Select</option>
                                                @if(count($reservation_category) > 0)
                                                    @foreach($reservation_category as $val)
                                                        <option value="{{ $val['id']}}">{{ $val['v_title'] }}</option>
                                                    @endforeach
                                                @endif  
                                            </select>   
                                            <input type="hidden" class="i_reservation_category_name_round_trip" name="i_reservation_category_name_round_trip" id="i_reservation_category_name_round_trip" value="">            
                                        </div>
                                    </div>
                                   
                                    
                                    <div class="form-group row flight_number_round_trip ">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12 flignt_number_lable_round_trip">Fight/Train/Bus Num</label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control v_flight_number_round_trip" name="v_flight_number_round_trip" placeholder="Fight/Train/Bus Num">
                                        </div>
                                    </div>
                                    <!-- <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Fight/Train/Bus Name</label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                            <input type="text" class="form-control v_flight_name_rt" name="v_flight_name_rt" placeholder="Fight/Train/Bus Name">
                                        </div>
                                    </div> -->
                                    <div class="form-group row flight_type_airport_round_trip ">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Flight Type </label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                            <select class="form-control e_flight_type_round_trip" name="e_flight_type_round_trip" placeholder="Flight Type">
                                                <option value="">Select</option>
                                                <option value="Domestic">Domestic</option>
                                                <option value="International">International</option>
                                                 
                                            </select>             
                                        </div>
                                    </div>

                                        <div class="form-group row">
                                            <label class="col-form-label col-md-4 col-lg-4 col-sm-12"> Return Information</label>
                                            <div class="col-lg-7 col-md-7 col-sm-12">
                                                <input type="text" class="form-control v_return_info_rt" name="v_return_info_rt" placeholder="Return Information" readonly="readonly" >
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Number of Pets
                                            </label>
                                            <div class="col-lg-7 col-md-7 col-sm-12 row">
                                                <div class="col-lg-8 col-md-8 col-sm-12">
                                                    <input type="text" class="form-control i_num_pets_rt" name="i_num_pets_rt" placeholder="Number of Pets" id="tatal_pets_rt" readonly>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-12">
                                                    <a href="" class="pets_info_popup_rt popup_info_link" id="pets_info_popup_rt" data-toggle="modal" data-target="#kt_modal_pets_info_rt" >Add info</a> 
                                                </div>   
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-md-9 col-lg-9 col-sm-12"> To make the shared service balance based on reservations on this day, I may need to target departure as late as: <span class="required">*</span></label>
                                            <div class="col-lg-2 col-md-2 col-sm-12">
                                                <input type="text" class="form-control required kt_time_picker t_target_time_rt" name="t_target_time_rt" placeholder="Time"  onblur="$(this).attr('readonly','readonly');" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Reservation Status <span class="required">*</span></label>
                                            <div class="col-lg-7 col-md-7 col-sm-12">
                                                <select class="form-control required rt_required_fields" name="e_reservation_status_rt" placeholder="Status">
                                                    <option value="">Select</option> 
                                                    <option value="Quote">Quote</option>
                                                    <option value="Booked">Booked</option>
                                                    <option value="Cancelled">Cancelled</option>
                                                    <option value="Refund Requested">Refund Requested</option>
                                                    <option value="Refunded">Refunded</option>
                                                </select>                
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="modal fade" id="kt_modal_luggages_info_rt" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header popup-model-header-margin">
                                                <h5 class="modal-title" id="exampleModalLabel">Luggages Information. </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                </button>
                                            </div>
                                            <div class="row add-address-content">
                                                <div class="modal-body">
                                                    <div class="frmAdd_luggages_info_rt" id="frmAdd_luggages_info_rt">
                                                        <div class="add_passenger_info_margin" >
                                                            <div id="container_luggages">
                                                                <table class="table table-bordered table-hover table-checkable popup_luggages_table_rt" id="popup_luggages_table_rt">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Luggage Type.</th>
                                                                            <th>No. of Luggage</th>
                                                                            <th>Charge</th>
                                                                            <th>Total Fare</th>
                                                                        </tr> 
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php $i=1; ?>
                                                                        @foreach($luggages_list as $val)
                                                                            <tr>
                                                                                <td>
                                                                                    <input type="text" class="form-control luggage_name" name="v_luggage_name" placeholder="Luggage Name" value="{{$val['v_name']}}" readonly>
                                                                                </td> 
                                                                                <td>
                                                                                    <?php
                                                                                        if($val['id'] == 1 || $val['id'] == 2 || $val['id'] == 3 || $val['id'] == 4) { ?>  
                                                                                            <input type="hidden" class="luggage_id_{{ $val['id'] }}_rt" name="luggage_id_{{ $val['id'] }}_rt" id="luggage_id_{{ $val['id'] }}_rt" value="{{ $val['id'] }}"> 
                                                                                            <select class="form-control luggage_type_{{ $val['id'] }}_rt luggage_dropdown_rt" name="luggage_numbers_{{ $val['id'] }}_rt" placeholder="Type">
                                                                                            <option value=""> Select </option>
                                                                                                <?php for($i=0; $i <= 20; $i++) { ?>
                                                                                                    <option value="{{$i}}" > {{$i}} </option>
                                                                                                <?php } ?>
                                                                                            </select>
                                                                                        <?php } else if($val['id'] == 6 || $val['id'] == 7 || $val['id'] == 8 || $val['id'] == 12 || $val['id'] == 13 || $val['id'] == 14 || $val['id'] == 15 || $val['id'] == 16) { ?>
                                                                                            <input type="hidden" class="luggage_id_{{ $val['id'] }}_rt" name="luggage_id_{{ $val['id'] }}_rt" id="luggage_id_{{ $val['id'] }}_rt" value="{{ $val['id'] }}"> 
                                                                                            <select class="form-control luggage_type_{{ $val['id'] }}_rt luggage_dropdown_rt" name="luggage_numbers_{{ $val['id'] }}_rt" placeholder="Type">
                                                                                            <option value=""> Select </option>
                                                                                                <?php for($i=0; $i <= 20; $i++) { ?>
                                                                                                    <option value="{{$i}}" > {{$i}} </option>
                                                                                                <?php } ?>
                                                                                            </select>
                                                                                        <?php } else if($val['id'] == 6 || $val['id'] == 7 || $val['id'] == 8 || $val['id'] == 12 || $val['id'] == 13 || $val['id'] == 14 || $val['id'] == 15 || $val['id'] == 16) { ?>
                                                                                            <input type="hidden" class="luggage_id_{{ $val['id'] }}_rt" name="luggage_id_{{ $val['id'] }}_rt" id="luggage_id_{{ $val['id'] }}_rt" value="{{ $val['id'] }}"> 
                                                                                            <select class="form-control  luggage_type_{{ $val['id'] }}_rt luggage_dropdown_rt" name="luggage_numbers_{{ $val['id'] }}_rt" placeholder="Type">
                                                                                            <option value=""> Select </option>
                                                                                                <?php for($i=0; $i <= 10; $i++) { ?>
                                                                                                    <option value="{{$i}}" > {{$i}} </option>
                                                                                                <?php } ?>
                                                                                            </select>
                                                                                        <?php } else if($val['id'] == 9 || $val['id'] == 11) { ?>
                                                                                            <input type="hidden" class="luggage_id_{{ $val['id'] }}_rt" name="luggage_id_{{ $val['id'] }}_rt" id="luggage_id_{{ $val['id'] }}_rt" value="{{ $val['id'] }}"> 
                                                                                            <select class="form-control luggage_type_{{ $val['id'] }}_rt luggage_dropdown_rt" name="luggage_numbers_{{ $val['id'] }}_rt" placeholder="Type">
                                                                                                <option value=""> Select </option>
                                                                                                <option value="0" > 0 </option>
                                                                                                <option value="1" > 1 </option>
                                                                                                <option value="2" > 2 </option>
                                                                                            </select>
                                                                                        <?php } else if($val['id'] == 10) { ?>
                                                                                            <input type="hidden" class="luggage_id_{{ $val['id'] }}_rt" name="luggage_id_{{ $val['id'] }}_rt" id="luggage_id_{{ $val['id'] }}_rt" value="{{ $val['id'] }}"> 
                                                                                            <select class="form-control luggage_type_{{ $val['id'] }}_rt luggage_dropdown_rt" name="luggage_numbers_{{ $val['id'] }}_rt" placeholder="Type">
                                                                                                <option value=""> Select </option>
                                                                                                <option value="0" > 0 </option>
                                                                                                <option value="1" > 1 </option>
                                                                                            </select>
                                                                                        <?php } else if($val['id'] == 5) { ?>
                                                                                            <input type="hidden" class="luggage_id_{{ $val['id'] }}_rt" name="luggage_id_{{ $val['id'] }}_rt" id="luggage_id_{{ $val['id'] }}_rt" value="{{ $val['id'] }}"> 
                                                                                            <select class="form-control personel_luggage_info_rt luggage_type_{{ $val['id'] }}_rt luggage_dropdown_rt" name="luggage_numbers_{{ $val['id'] }}_rt" placeholder="Type">
                                                                                                <option value=""> Select </option>
                                                                                            </select>
                                                                                        <?php }
                                                                                    ?>
                                                                                </td>
                                                                                <td>
                                                                                    <?php if($val['d_unit_price'] == '0.00') { ?>
                                                                                        <input type="text" class="form-control luggage_charge_rt" name="d_unit_price_{{ $val['id'] }}_rt" placeholder="Luggage Charge" value="FREE" readonly>
                                                                                    <?php } else { ?>
                                                                                        <input type="text" class="form-control luggage_charge_rt" name="d_unit_price_{{ $val['id'] }}_rt" placeholder="Luggage Charge" value="$ {{$val['d_unit_price']}} EACH" readonly>
                                                                                    <?php } ?>
                                                                                    
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" class="form-control total_fare_amt_rt" value="0" id="total_fare_amt_{{ $val['id'] }}_rt" name="total_fare_amt_{{ $val['id'] }}_rt" readonly/>
                                                                                </td>
                                                                            </tr>
                                                                            <?php $i++; ?>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>           
                                                            </div> 
                                                        </div>
                                                    </div>
                                                    <!-- </form> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="kt_modal_pets_info_rt" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header popup-model-header-margin">
                                                <h5 class="modal-title" id="exampleModalLabel_rt">Animals Information. </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                </button>
                                            </div>
                                            <div class="row add-address-content">
                                                <div class="modal-body">
                                                    <div class="frmAdd_pets_info_rt" id="frmAdd_pets_info_rt">
                                                        <div class="add_passenger_info_margin" >
                                                            <div id="container_pet_rt">
                                                                <table class="table table-bordered table-hover table-checkable" id="popup_pet_table_rt">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width:300px">Animals Type.</th>
                                                                            <th></th>
                                                                            <th>Charge</th>
                                                                            <th>Total Fare</th>
                                                                        </tr> 
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php $i=1; ?>
                                                                        @foreach($animals_list as $val)
                                                                            <tr>
                                                                                <td>
                                                                                    <input type="hidden" class="animal_rec_id_rt" name="animal_rec_id_{{$val['id']}}_rt" id="animal_rec_id_{{$val['id']}}_rt" value="{{$val['id']}}"> 

                                                                                    <input type="text" class="form-control pet_name_rt" name="v_pet_name_{{$val['id']}}_rt" placeholder="Pet Name" value="{{$val['v_name']}}" readonly>
                                                                                </td> 
                                                                                <td>
                                                                                    <input type="checkbox" value="0" class="kt-group-checkable is_pet_available_rt" name="pet_available_{{$val['id']}}_rt" id="is_pet_available_{{$val['id']}}_rt">
                                                                                </td>
                                                                                <td>
                                                                                    <?php if($val['d_unit_price'] == '0.00') { ?>
                                                                                        <input type="text" class="form-control pet_charge_rt" name="d_unit_price_{{ $val['id'] }}_rt" placeholder="Pet Charge" value="FREE" readonly>
                                                                                    <?php } else { ?>
                                                                                        <input type="text" class="form-control pet_charge_rt" name="d_unit_price_{{ $val['id'] }}_rt" placeholder="Pet Charge" value="$ {{$val['d_unit_price']}} EACH" readonly>
                                                                                    <?php } ?> 
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" class="form-control total_fare_amt_pet_rt" value="0" id="total_fare_amt_pet_{{ $val['id'] }}_rt" name="total_fare_amt_pet_{{ $val['id'] }}_rt" readonly/>
                                                                                </td>
                                                                            </tr>
                                                                            <?php $i++; ?>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>  
                                                            </div> 
                                                        </div>
                                                    </div>
                                                    <!-- </form> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>   
                            
                            <input type="hidden" class="" name="v_traveller_name[]" id="v_traveller_name" > 
                            <input type="hidden" class="" name="d_birth_month_year[]" id="d_birth_month_year">  
                            <input type="hidden" class="" name="e_type[]" id="e_type">  
                            <input type="hidden" class="" name="popup_value" id="popup_value" value="0">  

                            <input type="hidden" class="" name="v_traveller_name_rt[]" id="v_traveller_name_rt" > 
                            <input type="hidden" class="" name="d_birth_month_year_rt[]" id="d_birth_month_year_rt">  
                            <input type="hidden" class="" name="e_type_rt[]" id="e_type_rt"> 
                            <input type="hidden" class="" name="popup_value_rt" id="popup_value_rt" value="0">  

                        </div>

                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <div class="row">
                                    <div class="col-lg-9 ml-lg-auto">
                                        <button type="submit" class="btn btn-brand frm_submit_btn">Submit</button>
                                        <a href="{{ ADMIN_URL }}<?php if(!isset($cust_reservation)) {?>reservations <?php } else {?>customers/edit/{{ $customer_id }}#kt_tabs_reservation <?php } ?>" class="btn btn-secondary"> Cancel </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <div class="modal fade" id="kt_modal_luggages_info" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header popup-model-header-margin">
                                        <h5 class="modal-title" id="exampleModalLabel">Luggages Information. </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        </button>
                                    </div>
                                    <div class="row add-address-content">
                                        <div class="modal-body">
                                            <div class="frmAdd_luggages_info" id="frmAdd_luggages_info">
                                                <div class="add_passenger_info_margin" >
                                                    <div id="container_luggages">
                                                        <table class="table table-bordered table-hover table-checkable popup_luggages_table" id="popup_luggages_table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Luggage Type.</th>
                                                                    <th>No. of Luggage</th>
                                                                    <th>Charge</th>
                                                                    <th>Total Fare</th>
                                                                </tr> 
                                                            </thead>
                                                            <tbody>
                                                                <?php $i=1; ?>
                                                                @foreach($luggages_list as $val)
                                                                    <tr>
                                                                        <td>
                                                                            <input type="text" class="form-control luggage_name" name="v_luggage_name" placeholder="Luggage Name" value="{{$val['v_name']}}" readonly>
                                                                        </td> 
                                                                        <td>
                                                                            <?php
                                                                                if($val['id'] == 1 || $val['id'] == 2 || $val['id'] == 3 || $val['id'] == 4) { ?>  
                                                                                    <input type="hidden" class="luggage-id" name="luggage_id_{{ $val['id'] }}" id="luggage_id_{{ $val['id'] }}" value="{{ $val['id'] }}"> 
                                                                                    <select class="form-control luggage_type_{{ $val['id'] }} luggage_dropdown" name="luggage_numbers_{{ $val['id'] }}" placeholder="Type">
                                                                                    <option value=""> Select </option>
                                                                                        <?php for($i=0; $i <= 20; $i++) { ?>
                                                                                            <option value="{{$i}}" > {{$i}} </option>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                <?php } else if($val['id'] == 6 || $val['id'] == 7 || $val['id'] == 8 || $val['id'] == 12 || $val['id'] == 13 || $val['id'] == 14 || $val['id'] == 15 || $val['id'] == 16) { ?>
                                                                                    <input type="hidden" class="luggage-id" name="luggage_id_{{ $val['id'] }}" id="luggage_id_{{ $val['id'] }}" value="{{ $val['id'] }}"> 
                                                                                    <select class="form-control  luggage_type_{{ $val['id'] }} luggage_dropdown" name="luggage_numbers_{{ $val['id'] }}" placeholder="Type">
                                                                                    <option value=""> Select </option>
                                                                                        <?php for($i=0; $i <= 10; $i++) { ?>
                                                                                            <option value="{{$i}}" > {{$i}} </option>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                <?php } else if($val['id'] == 9 || $val['id'] == 11) { ?>
                                                                                    <input type="hidden" class="luggage-id_{{ $val['id'] }}" name="luggage_id_{{ $val['id'] }}" id="luggage_id_{{ $val['id'] }}" value="{{ $val['id'] }}"> 
                                                                                    <select class="form-control luggage_type_{{ $val['id'] }} luggage_dropdown" name="luggage_numbers_{{ $val['id'] }}" placeholder="Type">
                                                                                        <option value=""> Select </option>
                                                                                        <option value="0" > 0 </option>
                                                                                        <option value="1" > 1 </option>
                                                                                        <option value="2" > 2 </option>
                                                                                    </select>
                                                                                <?php } else if($val['id'] == 10) { ?>
                                                                                    <input type="hidden" class="luggage-id" name="luggage_id_{{ $val['id'] }}" id="luggage_id_{{ $val['id'] }}" value="{{ $val['id'] }}"> 
                                                                                    <select class="form-control luggage_type_{{ $val['id'] }} luggage_dropdown" name="luggage_numbers_{{ $val['id'] }}" placeholder="Type">
                                                                                        <option value=""> Select </option>
                                                                                        <option value="0" > 0 </option>
                                                                                        <option value="1" > 1 </option>
                                                                                    </select>
                                                                                <?php } else if($val['id'] == 5) { ?>
                                                                                    <input type="hidden" class="luggage-id" name="luggage_id_{{ $val['id'] }}" id="luggage_id_{{ $val['id'] }}" value="{{ $val['id'] }}"> 
                                                                                    <select class="form-control personel_luggage_info luggage_type_{{ $val['id'] }} luggage_dropdown" name="luggage_numbers_{{ $val['id'] }}" placeholder="Type">
                                                                                        <option value=""> Select </option>
                                                                                    </select>
                                                                                <?php }
                                                                            ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php if($val['d_unit_price'] == '0.00') { ?>
                                                                                <input type="text" class="form-control luggage_charge" name="d_unit_price_{{ $val['id'] }}" placeholder="Luggage Charge" value="FREE" readonly>
                                                                            <?php } else { ?>
                                                                                <input type="text" class="form-control luggage_charge" name="d_unit_price_{{ $val['id'] }}" placeholder="Luggage Charge" value="$ {{$val['d_unit_price']}} EACH" readonly>
                                                                            <?php } ?>
                                                                            
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control total_fare_amt" value="0" id="total_fare_amt_{{ $val['id'] }}" name="total_fare_amt_{{ $val['id'] }}" readonly/>
                                                                        </td>
                                                                    </tr>
                                                                    <?php $i++; ?>
                                                                @endforeach
                                                            </tbody>
                                                        </table>           
                                                    </div> 
                                                </div>
                                            </div>
                                            <!-- </form> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="kt_modal_pets_info" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header popup-model-header-margin">
                                        <h5 class="modal-title" id="exampleModalLabel">Animals Information. </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        </button>
                                    </div>
                                    <div class="row add-address-content">
                                        <div class="modal-body">
                                            <div class="frmAdd_pets_info" id="frmAdd_pets_info">
                                                <div class="add_passenger_info_margin" >
                                                    <div id="container_pet">
                                                        <table class="table table-bordered table-hover table-checkable" id="popup_pet_table">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width:300px">Animals Type.</th>
                                                                    <th></th>
                                                                    <th>Charge</th>
                                                                    <th>Total Fare</th>
                                                                </tr> 
                                                            </thead>
                                                            <tbody>
                                                                <?php $i=1; ?>
                                                                @foreach($animals_list as $val)
                                                                    <tr>
                                                                        <td>
                                                                            <input type="hidden" class="animal_rec_id" name="animal_rec_id_{{$val['id']}}" id="animal_rec_id_{{$val['id']}}" value="{{$val['id']}}"> 

                                                                            <input type="text" class="form-control pet_name" name="v_pet_name_{{$val['id']}}" placeholder="Pet Name" value="{{$val['v_name']}}" readonly>
                                                                        </td> 
                                                                        <td>
                                                                            <input type="checkbox" value="0" class="kt-group-checkable is_pet_available" name="pet_available_{{$val['id']}}" id="is_pet_available_{{$val['id']}}">
                                                                        </td>
                                                                        <td>
                                                                            <?php if($val['d_unit_price'] == '0.00') { ?>
                                                                                <input type="text" class="form-control pet_charge" name="d_unit_price_{{ $val['id'] }}" placeholder="Pet Charge" value="FREE" readonly>
                                                                            <?php } else { ?>
                                                                                <input type="text" class="form-control pet_charge" name="d_unit_price_{{ $val['id'] }}" placeholder="Pet Charge" value="$ {{$val['d_unit_price']}} EACH" readonly>
                                                                            <?php } ?> 
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control total_fare_amt_pet" value="0" id="total_fare_amt_pet_{{ $val['id'] }}" name="total_fare_amt_pet_{{ $val['id'] }}" readonly/>
                                                                        </td>
                                                                    </tr>
                                                                    <?php $i++; ?>
                                                                @endforeach
                                                            </tbody>
                                                        </table>  
                                                    </div> 
                                                </div>
                                            </div>
                                            <!-- </form> -->
                                        </div>
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

    <div class="modal fade" id="kt_modal_passenger_info" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header popup-model-header-margin">
                    <h5 class="modal-title" id="exampleModalLabel">Passenger Information. </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="row add-address-content">
                    <div class="modal-body">
                        <!-- <form action="" enctype="multipart/form-data" class="form-horizontal" id="frmAdd_passenger_info" method="POST"> -->
                        <div class="frmAdd_passenger_info" id="frmAdd_passenger_info">
                            <div class="add_passenger_info_margin" >
                                <div id="container_passenger">
                                    
                                </div> 
                            </div>
                        </div>
                        <!-- </form> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="kt_modal_passenger_info_rt" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header popup-model-header-margin">
                    <h5 class="modal-title" id="exampleModalLabel">Passenger Information. </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="row add-address-content">
                    <div class="modal-body">
                        <!-- <form action="" enctype="multipart/form-data" class="form-horizontal" id="frmAdd_passenger_info" method="POST"> -->
                        <div class="frmAdd_passenger_info_rt" id="frmAdd_passenger_info_rt">
                            <div class="add_passenger_info_margin" >
                                <div id="container_passenger_rt">
                                    
                                </div> 
                            </div>
                        </div>
                        <!-- </form> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

  <!-- end:: Content -->
</div>

@stop

@section('custom_js')

<script src="{{ asset('js/reservation_backend.js') }}{{JS_VERSION}}"></script>
@stop
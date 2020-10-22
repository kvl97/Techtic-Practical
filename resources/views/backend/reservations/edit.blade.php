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
                    Edit Reservation
                </h3>
                </div>
            </div>

            <!--begin::Form-->

            <form class="kt-form kt-form--label-right" id="<?php if(!isset($cust_reservation)) {?>frmEdit<?php } else {?>frmEdit_cust_reservation<?php } ?>" action="{{ ADMIN_URL }}<?php if(!isset($cust_reservation)) {?>reservations/edit/{{ $record->id }} <?php } else {?>reservations/edit/{{ $record->id }}/customer/{{$customer_id}} <?php } ?>">
                <div class="kt-portlet__body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            @if(!isset($cust_reservation))
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Customer Name <span class="required">*</span></label>
                                    <div class="col-lg-7 col-md-7 col-sm-12">
                                        <select class="form-control required" name="i_customer_id" placeholder="Customer Name">
                                            <option value="">Select</option>
                                            @if(count($customer_data) > 0)
                                                @foreach($customer_data as $val)
                                                    <option value="{{ $val['id'].' '.$val['v_firstname'].' '.$val['v_lastname']}}" {{ $record->i_customer_id == $val['id'] ? 'selected=""' : '' }}>{{ $val['v_firstname'].' '.$val['v_lastname'] }}</option>
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
                                        <input type="hidden" value="{{$record->i_customer_id}}" id="customer_id_reservation" /> 
                                    </div>
                                </div>
                            @endif

                            <?php  ?>
                            <div class="form-group row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Origin Point <span class="required">*</span></label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <select class="form-control required i_origin_point_id" placeholder="Origin Point" id="i_origin_point_id" name="i_origin_point_id">
                                        @foreach($service_area as $val)
                                            <option value="{{ $val['id'] }}" {{ $record->i_origin_point_id == $val['id'] ? 'selected=""' : '' }}>{{ $val['v_street1'].' '.$val['geo_cities']['v_city'].' '.$val['geo_cities']['v_county'].' '.$val['v_postal_code'] }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" class="form-control" id="origin_point_val" value="{{ $record->i_origin_point_id}}" />
                                </div>
                            </div>
                            <div class="form-group row flight_time">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12 flignt_time_lable">Fight/Train/Bus Time</label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                <input type="text" class="form-control kt_time_picker t_flight_time" name="t_flight_time" placeholder="Fight/Train/Bus Time"  onblur="$(this).attr('readonly','readonly');" value="{{$record['t_flight_time'] ? $record['t_flight_time'] : ''}}" readonly="readonly">
                                </div>
                            </div>

                            <div class="form-group row ">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Class Type <span class="required">*</span></label>
                                <div class="col-lg-7 col-md-7 col-sm-12 radio-btn-msg">
                                    <!-- <div class="kt-radio-inline mt-10">
                                        <label class="kt-radio">
                                        <input type="radio" class="required-least-one-radio e_class_type" name="e_class_type" value="OW" id="one_way" groupid="class_type" <?php //if($record->e_class_type =='OW') { ?> checked='checked' <?php //} ?> readonly="readonly"> One Way
                                        </label>

                                        <label class="kt-radio">
                                            <input type="radio" class="required-least-one-radio e_class_type" name="e_class_type" value="RT" id="round_trip" groupid="class_type"  <?php //if($record->e_class_type =='RT') { ?> checked='checked' <?php //} ?> readonly="readonly"> Round Trip
                                        </label>
                                        <span class="check"></span>
                                    </div> -->
                                    <label class="kt-mt-10 e_class_type" name="e_class_type" ><?php if($record->e_class_type =='RT') {?>Round Trip <?php } else {?> One Way <?php } ?></label>
                                    <input type="hidden" class="form-control" id="e_class_type" name="e_class_type" value="{{ $record->e_class_type}}" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Depart Date <span class="required">*</span></label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                <input type="text" class="form-control required date_picker_depart d_depart_date" name="d_depart_date" placeholder="Depart Date"  onblur="$(this).attr('readonly','readonly');" readonly="readonly" value="{{ date('m/d/Y',strtotime($record->d_travel_date)) }}">
                                </div>
                            </div>

                            <!-- <div class="form-group row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12"> Departure Time<span class="required">*</span></label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                <input type="text" class="form-control required kt_time_picker" name="t_departure_time" placeholder="Departure Time"  onblur="$(this).attr('readonly','readonly');" value="{{ date('h:i',strtotime($record->d_travel_date)) }}"  readonly="readonly">
                                </div>
                            </div> -->

                            <div class="form-group row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12"> Contact Number<span class="required">*</span></label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                <input type="text" class="form-control required phone" name="v_contact_phone_number" placeholder="Contact Number" value="{{ $record->v_contact_phone_number}}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12"> Best Time For Call<span class="required">*</span></label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                <input type="text" class="form-control required kt_time_picker" name="t_best_time_tocall" placeholder="Best Time For Call" value="{{ $record->t_best_time_tocall}}" autocomplete="off"  onblur="$(this).attr('readonly','readonly');" readonly="readonly">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Number of Passengers<span class="required">*</span></label>
                                <div class="col-lg-7 col-md-7 col-sm-12 row">

                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <input type="text" class="form-control required i_total_num_passengers" name="i_total_num_passengers" placeholder="Number of Passengers" id="total_passenger" onclick="addInfoLink();" value="{{ $record->i_total_num_passengers }}" >
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <a href="" class="passenger_info_popup popup_info_link" id="passenger_info_popup" onclick="addFields();return false;"> Add info</a>
                                        <input type="hidden" class="" name="edit_total_passenger" id="edit_total_passenger" value="{{ $record->i_total_num_passengers }}">

                                        <?php
                                            //if(isset($record->i_total_num_passengers) && $record->i_total_num_passengers == count($reservation_data)) { ?>

                                                <!-- <a href="" class="passenger_info_popup popup_info_link" id="passenger_info_popup" data-toggle="modal" data-target="#kt_modal_passenger_info">Add info</a>  -->
                                            <?php //} else {  ?>
                                                <!-- <a href="" class="passenger_info_popup popup_info_link" id="passenger_info_popup" onclick="addFields();return false;">Add info</a>  -->
                                        <?php //} ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Number of luggages</label>
                                <div class="col-lg-7 col-md-7 col-sm-12 row">
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <input type="text" class="form-control i_number_of_luggages" name="i_number_of_luggages" placeholder="Number of luggages" id="total_luggages" value="{{ ($record->i_number_of_luggages) ? $record->i_number_of_luggages : 0}}" readonly>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <a href="" class="luggages_info_popup popup_info_link" id="luggages_info_popup" data-toggle="modal" data-target="#kt_modal_luggages_info" >Add info</a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-9 col-lg-9 col-sm-12"> The earliest time I am comfortable targeting pick up at Airport, Train, etc is: <span class="required">*</span></label>
                                <div class="col-lg-2 col-md-2 col-sm-12">
                                    <input type="text" class="form-control required kt_time_picker t_comfortable_time" name="t_comfortable_time" placeholder="Time"  onblur="$(this).attr('readonly','readonly');" readonly="readonly" value="{{$record->t_comfortable_time}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Special Instruction</label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <textarea class="form-control " name="t_special_instruction" placeholder="Special Instruction"> {{  $record->t_special_instruction  }} </textarea>
                                </div>
                            </div>



                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">

                            <div class="form-group row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Travel Form </label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <select class="form-control i_reservation_category_id" name="i_reservation_category_id" placeholder="Travel Form">
                                        <option value="">Select</option>
                                        @if(count($reservation_category) > 0)
                                            @foreach($reservation_category as $val)
                                                <option value="{{ $val['id'] }}" {{ $record->i_reservation_category_id == $val['id'] ? 'selected=""' : '' }}>{{ $val['v_title'] }}</option>
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
                                        @foreach($service_area as $val)
                                            <option value="{{ $val['id'] }}" {{ $record->i_destination_point_id == $val['id'] ? 'selected=""' : '' }}>{{ $val['v_street1'].' '.$val['geo_cities']['v_city'].' '.$val['geo_cities']['v_county'].' '.$val['v_postal_code'] }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" id="destination_point_val" value="{{ $record->i_destination_point_id}}" />
                                </div>
                            </div>
                            <div class="form-group row flight_number">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12 flignt_number_lable">Fight/Train/Bus Num.</label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                <input type="text" class="form-control v_flight_number" name="v_flight_number" placeholder="Fight/Train/Bus Number" value="{{$record->v_flight_number ? $record->v_flight_number : ''}}">
                                </div>
                            </div>
                            <!-- <div class="form-group row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Fight/Train/Bus Name</label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <input type="text" class="form-control v_flight_name" name="v_flight_name" placeholder="Fight/Train/Bus Name" value="{{$record->v_flight_name ? $record->v_flight_name : ''}}">
                                </div>
                            </div> -->
                            <div class="form-group row flight_type_airport">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Flight Type </label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <select class="form-control e_flight_type" name="e_flight_type" placeholder="Flight Type">
                                        <option value="">Select</option>
                                        <option value="Domestic" {{ $record->e_flight_type == 'Domestic' ? 'selected=""' : '' }}>Domestic</option>
                                        <option value="International" {{$record->e_flight_type == 'International' ? 'selected=""' : '' }}}}>International</option>

                                    </select>
                                </div>
                            </div>
                            @if($record['e_class_type'] == 'RT')
                            <div class="form-group row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Return Date </label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                <input type="text" class="form-control date_picker_return d_return_date return_trip_fields" name="d_return_date" placeholder="Return Date" onblur="$(this).attr('readonly','readonly');" readonly="readonly" disabled  value="{{ $record_rt['d_travel_date'] ? date('m/d/Y',strtotime($record_rt['d_travel_date'])) : '' }}">
                                </div>
                            </div>

                            <!-- <div class="form-group row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Arrival Time</label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                <input type="text" class="form-control kt_time_picker " name="t_arrival_time" placeholder="Arrival Time"  value="{{ date('h:i A',strtotime($record_rt->d_travel_date)) }}">
                                </div>
                            </div> -->
                            @else
                            <div class="form-group row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Return Date </label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                <input type="text" class="form-control date_picker_return d_return_date return_trip_fields" name="d_return_date" placeholder="Return Date" onblur="$(this).attr('readonly','readonly');" readonly="readonly" disabled  value="{{ $record['d_travel_date'] ? date('m/d/Y',strtotime($record['d_travel_date'])) : '' }}">
                                </div>
                            </div>

                            <!-- <div class="form-group row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Arrival Time</label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                <input type="text" class="form-control kt_time_picker " name="t_arrival_time" placeholder="Arrival Time"  value="{{ date('h:i',strtotime($record->d_travel_date)) }}">
                                </div>
                            </div> -->
                            @endif

                            <!-- <div class="form-group row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12"> Oneway Information<span class="required">*</span></label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                <input type="text" class="form-control required v_oneway_info" name="v_oneway_info" value="" placeholder="Oneway Information">
                                </div>
                            </div> -->

                            <div class="form-group row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Contact Email Address <span class="required">*</span></label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                <input type="text" class="form-control required email" name="v_contact_email" placeholder="Contact Email Address" value="{{ $record->v_contact_email }}">
                                </div>
                            </div>

                            <div class="form-group row ">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Voice Mail Setup <span class="required">*</span></label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <div class="kt-radio-inline mt-10">
                                        <label class="kt-radio">
                                        <input type="radio" class="required-least-one-radio" name="e_voice_mail_setup" value="YES" id="e_voice_mail_setup_yes" groupid="voice_mail_setup" <?php if($record->e_voice_mail_setup =='YES') { ?> checked='checked' <?php } ?>> YES
                                        </label>

                                        <label class="kt-radio">
                                        <input type="radio" class="required-least-one-radio" name="e_voice_mail_setup" value="NO" id="e_voice_mail_setup_no" groupid="voice_mail_setup" <?php if($record->e_voice_mail_setup =='NO') { ?> checked='checked' <?php } ?>> NO
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
                                        <input type="text" class="form-control i_num_pets" name="i_num_pets" placeholder="Number of Pets" id="tatal_pets" value="{{ ($record->i_num_pets) ? $record->i_num_pets : 0 }}" readonly>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <a href="" class="pets_info_popup popup_info_link" id="pets_info_popup" data-toggle="modal" data-target="#kt_modal_pets_info" >Add info</a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Shuttle Type <span class="required">*</span></label>
                                <div class="col-lg-7 col-md-7 col-sm-12 radio-btn-msg">
                                    <div class="kt-radio-inline mt-10">
                                        <label class="kt-radio">
                                        <input type="radio" class="required-least-one-radio" name="e_shuttle_type" value="Private" id="private" groupid="shuttle_type" <?php if($record->e_shuttle_type =='Private') { ?> checked='checked' <?php } ?> > Private
                                        </label>

                                        <label class="kt-radio">
                                        <input type="radio" class="required-least-one-radio" name="e_shuttle_type" value="Shared" id="shared" groupid="shuttle_type" <?php if($record->e_shuttle_type =='Shared') { ?> checked='checked' <?php } ?> > Shared
                                        </label>
                                        <span class="check"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-9 col-lg-9 col-sm-12"> To make the shared service balance based on reservations on this day, I may need to target departure as late as: <span class="required">*</span></label>
                                <div class="col-lg-2 col-md-2 col-sm-12">
                                    <input type="text" class="form-control required kt_time_picker" name="t_target_time" placeholder="Time"  onblur="$(this).attr('readonly','readonly');" readonly="readonly" value="{{$record['t_target_time']}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Reservation Status <span class="required">*</span></label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <select class="form-control required" name="e_reservation_status" placeholder="Status">
                                        <option value="">Select</option>
                                        <option value="Quote" {{ $record->e_reservation_status == 'Quote' ? 'selected=""' : '' }}>Quote</option>
                                        <option value="Booked" {{ $record->e_reservation_status == 'Booked' ? 'selected=""' : '' }}>Booked</option>
                                        <option value="Cancelled" {{ $record->e_reservation_status == 'Cancelled' ? 'selected=""' : '' }}>Cancelled</option>
                                        <option value="Refund Requested" {{ $record->e_reservation_status == 'Refund Requested' ? 'selected=""' : '' }}>Refund Requested</option>
                                        <option value="Refunded" {{ $record->e_reservation_status == 'Refunded' ? 'selected=""' : '' }}>Refunded</option>
                                        
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    @if($record['e_class_type'] == 'RT')
                        <div class="return_reservation_detail">
                            <h5><b>Return Trip Information:</b></h5>
                            <input type="hidden" value="{{ $record_rt->id }}" id="reservation_rt_record" name="reservation_rt_record" />
                            <div class="row" >
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Return Date </label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">

                                            <label class="return_date_rt kt-mt-10" id="return_date_rt">{{ $record_rt['d_travel_date'] ? date('m/d/Y',strtotime($record_rt['d_travel_date'])) : '-' }}</label>
                                        </div>
                                    </div>
                                    <div class="form-group row flight_time_round_trip">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12 flignt_time_lable">Fight/Train/Bus Time <span class="required">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control kt_time_picker t_flight_time_round_trip" name="t_flight_time_round_trip" placeholder="Fight/Train/Bus Time"  onblur="$(this).attr('readonly','readonly');" value="{{$record_rt['t_flight_time'] ? $record_rt['t_flight_time'] : ''}}" readonly="readonly">
                                        </div>
                                    </div>
                                    <!-- <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12"> Departure Time<span class="required">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                            <input type="text" class="form-control required kt_time_picker rt_required_fields" name="t_departure_time_rt" placeholder="Departure Time"  onblur="$(this).attr('readonly','readonly');" readonly="readonly" value="{{ date('h:i',strtotime($record->d_travel_date)) }}">
                                        </div>
                                    </div> -->



                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Number of Passengers<span class="required">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-12 row">
                                            <div class="col-lg-8 col-md-8 col-sm-12">
                                                <input type="text" class="form-control required i_total_num_passengers_rt rt_required_fields" name="i_total_num_passengers_rt" placeholder="Number of Passengers" id="total_passenger_rt" onclick="addInfoLink('rt');" value="{{ $record_rt->i_total_num_passengers }}"
                                                <?php if($record_rt->e_travel_alone =='YES') {  ?> disabled="disabled" <?php } ?> >
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <a href="" class="passenger_info_popup_rt popup_info_link" id="passenger_info_popup_rt"  onclick="addFields('rt');return false;" >Add info</a>
                                                <input type="hidden" class="" name="edit_total_passenger_rt" id="edit_total_passenger_rt" value="{{ $record_rt->i_total_num_passengers }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Number of luggages</label>
                                        <div class="col-lg-7 col-md-7 col-sm-12 row">
                                            <div class="col-lg-8 col-md-8 col-sm-12">
                                                <input type="text" class="form-control i_number_of_luggages_rt" name="i_number_of_luggages_rt" placeholder="Number of luggages" id="total_luggages_rt" value="{{ ($record_rt->i_number_of_luggages) ? $record_rt->i_number_of_luggages : 0}}" readonly>
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
                                                <input type="radio" class="required-least-one-radio rt_required_fields" name="e_shuttle_type_rt" value="Private" id="private" groupid="shuttle_type" <?php if($record_rt->e_shuttle_type =='Private') { ?> checked='checked' <?php } ?>> Private
                                                </label>

                                                <label class="kt-radio">
                                                <input type="radio" class="required-least-one-radio rt_required_fields" name="e_shuttle_type_rt" value="Shared" id="shared" groupid="shuttle_type" <?php if($record_rt->e_shuttle_type =='Shared') { ?> checked='checked' <?php } ?> > Shared
                                                </label>
                                                <span class="check"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-9 col-lg-9 col-sm-12"> The earliest time I am comfortable targeting pick up at Airport, Train, etc is: <span class="required">*</span></label>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                        <input type="text" class="form-control required kt_time_picker t_comfortable_time_rt" name="t_comfortable_time_rt" placeholder="Time"  onblur="$(this).attr('readonly','readonly');" readonly="readonly" value="{{$record_rt->t_comfortable_time ? $record_rt->t_comfortable_time : ''}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Special Instruction</label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                            <textarea class="form-control t_special_instruction_rt" name="t_special_instruction_rt" placeholder="Special Instruction">{{  $record_rt->t_special_instruction  }}</textarea>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12">


                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Travel Form </label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                            <select class="form-control i_reservation_category_id_round_trip" name="i_reservation_category_id_round_trip" placeholder="Travel Form">
                                                <option value="">Select</option>
                                                @if(count($reservation_category) > 0)
                                                    @foreach($reservation_category as $val)
                                                        <option value="{{ $val['id'] }}" {{ $record_rt->i_reservation_category_id == $val['id'] ? 'selected=""' : '' }}>{{ $val['v_title'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <input type="hidden" class="i_reservation_category_name" name="i_reservation_category_name" id="i_reservation_category_name" value="">
                                        </div>
                                    </div>
                                    <div class="form-group row flight_number_round_trip">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12 flignt_number_lable">Fight/Train/Bus Num.<span class="required">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control v_flight_number_round_trip " name="v_flight_number_round_trip" placeholder="Fight/Train/Bus Number" value="{{$record_rt->v_flight_number ? $record_rt->v_flight_number : ''}}">
                                        </div>
                                    </div>
                                    <!-- <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Fight/Train/Bus Name</label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                            <input type="text" class="form-control v_flight_name_rt" name="v_flight_name_rt" placeholder="Fight/Train/Bus Name" value="{{$record_rt->v_flight_name ? $record_rt->v_flight_name : ''}}">
                                        </div>
                                    </div> -->
                                    <!-- <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Arrival Time<span class="required">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                            <input type="text" class="form-control required kt_time_picker rt_required_fields" name="t_arrival_time_rt" placeholder="Arrival Time" value="{{ date('h:i',strtotime($record_rt->d_travel_date)) }}">
                                        </div>
                                    </div> -->
                                    <div class="form-group row flight_type_airport_round_trip <?php if($record_rt->i_reservation_category_id == 1 ){ echo '';} else{echo '';}?>">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Flight Type </label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                            <select class="form-control e_flight_type_round_trip" name="e_flight_type_round_trip" placeholder="Flight Type">
                                                <option value="">Select</option>
                                                <option value="Domestic" {{ $record_rt->e_flight_type == 'Domestic' ? 'selected=""' : '' }}>Domestic</option>
                                                <option value="International" {{$record_rt->e_flight_type == 'International' ? 'selected=""' : '' }}}}>International</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12"> Return Information</label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control v_return_info_rt" name="v_return_info_rt" placeholder="Return Information" value="" readonly="readonly" >
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Number of Pets
                                        </label>
                                        <div class="col-lg-7 col-md-7 col-sm-12 row">
                                            <div class="col-lg-8 col-md-8 col-sm-12">
                                                <input type="text" class="form-control i_num_pets_rt" name="i_num_pets_rt" placeholder="Number of Pets" id="tatal_pets_rt" value="{{ ($record_rt->i_num_pets) ? $record_rt->i_num_pets : 0 }}"  readonly>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <a href="" class="pets_info_popup_rt popup_info_link" id="pets_info_popup_rt" data-toggle="modal" data-target="#kt_modal_pets_info_rt" >Add info</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-9 col-lg-9 col-sm-12"> To make the shared service balance based on reservations on this day, I may need to target departure as late as: <span class="required">*</span></label>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <input type="text" class="form-control required kt_time_picker t_target_time_rt" name="t_target_time_rt" placeholder="Time"  onblur="$(this).attr('readonly','readonly');" readonly="readonly" value="{{$record_rt->t_target_time ? $record_rt->t_target_time : ''}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Reservation Status <span class="required">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                            <select class="form-control required rt_required_fields" name="e_reservation_status_rt" placeholder="Status">
                                            <option value="">Select</option>
                                            <option value="Quote" {{ $record_rt->e_reservation_status == 'Quote' ? 'selected=""' : '' }}>Quote</option>
                                            <option value="Booked" {{ $record_rt->e_reservation_status == 'Booked' ? 'selected=""' : '' }}>Booked</option>
                                            <option value="Cancelled" {{ $record_rt->e_reservation_status == 'Cancelled' ? 'selected=""' : '' }}>Cancelled</option>
                                            <option value="Refund Requested" {{ $record_rt->e_reservation_status == 'Refund Requested' ? 'selected=""' : '' }}>Refund Requested</option>
                                            <option value="Refunded" {{ $record_rt->e_reservation_status == 'Refunded' ? 'selected=""' : '' }}>Refunded</option>
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
                                                            <?php
                                                                $luggage_value_array_rt = Arr::pluck($reservation_luggage_info_rt, 'i_value','i_sys_luggage_id');
                                                                $luggage_rate_array_rt = Arr::pluck($reservation_luggage_info_rt, 'd_price','i_sys_luggage_id');
                                                                $luggage_id_array_rt = Arr::pluck($reservation_luggage_info_rt, 'id','i_sys_luggage_id');
                                                                /* echo ".........kshdjs hsdvs .................";
                                                                pr($luggage_value_array_rt);
                                                                //."...".$luggage_rate_array_rt."...".$luggage_id_array_rt);
                                                                exit; */
                                                            ?>
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
                                                                                <input type="text" class="form-control luggage_name_rt" name="v_luggage_name_rt" placeholder="Luggage Name" value="{{$val['v_name']}}" readonly>
                                                                            </td>
                                                                            <td>
                                                                                @if($val['id'] == 1 || $val['id'] == 2 || $val['id'] == 3 || $val['id'] == 4)
                                                                                    <input type="hidden" class="luggage-id" name="luggage_id_{{ $val['id'] }}_rt" id="luggage_id_{{ $val['id'] }}_rt" value="{{ $val['id'] }}">
                                                                                    @if(isset($luggage_id_array[$val['id']]))
                                                                                        <input type="hidden" class="luggage_data_id_rt" name="luggage_data_id_{{ $val['id'] }}_rt" id="luggage_data_id_rt" value="{{ $luggage_id_array[$val['id']] }}">
                                                                                    @endif

                                                                                    <select class="form-control luggage_type_{{ $val['id'] }}_rt luggage_dropdown_rt" name="luggage_numbers_{{ $val['id'] }}_rt" placeholder="Type">
                                                                                        <option value=""> Select </option>
                                                                                        @for($i=0; $i <= 20; $i++)
                                                                                            @if(isset($luggage_value_array_rt[$val['id']]))
                                                                                                <option value="{{$i}}" {{ $luggage_value_array_rt[$val['id']] == $i ? 'selected=""' : '' }}> {{$i}} </option>
                                                                                            @else
                                                                                                <option value="{{$i}}"> {{$i}} </option>
                                                                                            @endif
                                                                                        @endfor
                                                                                    </select>
                                                                                @elseif($val['id'] == 6 || $val['id'] == 7 || $val['id'] == 8 || $val['id'] == 12 || $val['id'] == 13 || $val['id'] == 14 || $val['id'] == 15 || $val['id'] == 16)
                                                                            <!--  id take in array -->
                                                                                    <input type="hidden" class="luggage-id" name="luggage_id_{{ $val['id'] }}_rt" id="luggage_id_{{ $val['id'] }}_rt" value="{{ $val['id'] }}">

                                                                                    @if(isset($luggage_id_array_rt[$val['id']]))
                                                                                        <input type="hidden" class="luggage_data_id_rt" name="luggage_data_id_{{ $val['id'] }}_rt" id="luggage_data_id_rt" value="{{ $luggage_id_array_rt[$val['id']] }}">
                                                                                    @endif

                                                                                    <select class="form-control  luggage_type_{{ $val['id'] }}_rt luggage_dropdown_rt" name="luggage_numbers_{{ $val['id'] }}_rt" placeholder="Type">
                                                                                        <option value=""> Select </option>
                                                                                        @for($i=0; $i <= 10; $i++)
                                                                                            @if(isset($luggage_value_array_rt[$val['id']]))
                                                                                                <option value="{{$i}}" {{ $luggage_value_array_rt[$val['id']] == $i ? 'selected=""' : '' }}> {{$i}} </option>
                                                                                            @else
                                                                                                <option value="{{$i}}"> {{$i}} </option>
                                                                                            @endif
                                                                                        @endfor
                                                                                    </select>
                                                                                @elseif($val['id'] == 9 || $val['id'] == 11)
                                                                                    <input type="hidden" class="luggage-id_{{ $val['id'] }}_rt" name="luggage_id_{{ $val['id'] }}_rt" id="luggage_id_rt" value="{{ $val['id'] }}">

                                                                                    @if(isset($luggage_id_array_rt[$val['id']]))
                                                                                        <input type="hidden" class="luggage_data_id_rt" name="luggage_data_id_{{ $val['id'] }}_rt" id="luggage_data_id_rt" value="{{ $luggage_id_array_rt[$val['id']] }}">
                                                                                    @endif

                                                                                    <select class="form-control luggage_type_{{ $val['id'] }}_rt luggage_dropdown_rt" name="luggage_numbers_{{ $val['id'] }}_rt" placeholder="Type">
                                                                                        <option value=""> Select </option>
                                                                                        @if(isset($luggage_value_array_rt[$val['id']]))
                                                                                            <option value="0" > 0 </option>
                                                                                            <option value="1" {{ $luggage_value_array_rt[$val['id']] == 1 ? 'selected=""' : '' }}> 1 </option>
                                                                                            <option value="2" {{ $luggage_value_array_rt[$val['id']] == 2 ? 'selected=""' : '' }}> 2 </option>
                                                                                        @else
                                                                                            <option value="0" > 0 </option>
                                                                                            <option value="1" > 1 </option>
                                                                                            <option value="2" > 2 </option>
                                                                                        @endif
                                                                                    </select>
                                                                                @elseif($val['id'] == 10)
                                                                                    <input type="hidden" class="luggage-id" name="luggage_id_{{ $val['id'] }}_rt" id="luggage_id_{{ $val['id'] }}_rt" value="{{ $val['id'] }}">

                                                                                    @if(isset($luggage_id_array_rt[$val['id']]))
                                                                                        <input type="hidden" class="luggage_data_id_rt" name="luggage_data_id_{{ $val['id'] }}_rt" id="luggage_data_id_rt" value="{{ $luggage_id_array_rt[$val['id']] }}">
                                                                                    @endif

                                                                                    <select class="form-control luggage_type_{{ $val['id'] }}_rt luggage_dropdown_rt" name="luggage_numbers_{{ $val['id'] }}_rt" placeholder="Type">
                                                                                        <option value=""> Select </option>
                                                                                        @if(isset($luggage_value_array_rt[$val['id']]))
                                                                                            <option value="0" > 0 </option>
                                                                                            <option value="1" {{ $luggage_value_array_rt[$val['id']] == 1 ? 'selected=""' : '' }}> 1 </option>
                                                                                        @else
                                                                                            <option value="0" > 0 </option>
                                                                                            <option value="1"> 1 </option>
                                                                                        @endif
                                                                                    </select>
                                                                                @elseif($val['id'] == 5)
                                                                                    <input type="hidden" class="luggage-id" name="luggage_id_{{ $val['id'] }}_rt" id="luggage_id_{{ $val['id'] }}_rt" value="{{ $val['id'] }}">

                                                                                    @if(isset($luggage_id_array_rt[$val['id']]))
                                                                                        <input type="hidden" class="luggage_data_id_rt" name="luggage_data_id_{{ $val['id'] }}_rt" id="luggage_data_id_rt" value="{{ $luggage_id_array_rt[$val['id']] }}">
                                                                                    @endif

                                                                                    <select class="form-control personel_luggage_info_rt luggage_type_{{ $val['id'] }}_rt luggage_dropdown_rt" name="luggage_numbers_{{ $val['id'] }}_rt" placeholder="Type">
                                                                                    <option value=""> Select </option>
                                                                                    @if(isset($luggage_value_array_rt[$val['id']]))
                                                                                        @for($i=1; $i<=$record->i_total_num_passengers; $i++)
                                                                                            <option value="{{$i}}" {{ $luggage_value_array_rt[$val['id']] == $i ? 'selected=""' : '' }}> {{$i}} </option>
                                                                                        @endfor
                                                                                    @else
                                                                                    @endif
                                                                                    </select>
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                @if($val['d_unit_price'] == '0.00')
                                                                                    <input type="text" class="form-control luggage_charge_rt" name="d_unit_price_{{ $val['id'] }}_rt" placeholder="Luggage Charge" value="FREE" readonly>
                                                                                @else
                                                                                    <input type="text" class="form-control luggage_charge_rt" name="d_unit_price_{{ $val['id'] }}_rt" placeholder="Luggage Charge" value="$ {{$val['d_unit_price']}} EACH" readonly>
                                                                                @endif

                                                                            </td>
                                                                            <td>
                                                                                @if(isset($luggage_rate_array_rt[$val['id']]))
                                                                                    <input type="text" class="form-control total_fare_amt_rt" value="{{ $luggage_rate_array_rt[$val['id']] }}" id="total_fare_amt_{{ $val['id'] }}_rt" name="total_fare_amt_{{ $val['id'] }}_rt" readonly/>
                                                                                @else
                                                                                    <input type="text" class="form-control total_fare_amt_rt" value="0 " id="total_fare_amt_{{ $val['id'] }}_rt" name="total_fare_amt_{{ $val['id'] }}_rt" readonly/>
                                                                                @endif

                                                                            </td>
                                                                        </tr>
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
                                                            <?php
                                                                $luggage_id_array_rt = Arr::pluck($reservation_luggage_info_rt, 'id','i_sys_luggage_id');
                                                                //pr($luggage_id_array); exit;
                                                            ?>
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

                                                                                @if(isset($luggage_id_array_rt[$val['id']]))
                                                                                    <input type="hidden" class="luggage_data_id_rt" name="luggage_data_id_{{ $val['id'] }}_rt" id="luggage_data_id_rt" value="{{ $luggage_id_array_rt[$val['id']] }}">
                                                                                @endif

                                                                                @if(isset($luggage_value_array_rt[$val['id']]) && $luggage_value_array_rt[$val['id']] == 1)
                                                                                    <input type="checkbox" value="0" class="kt-group-checkable is_pet_available_rt" name="pet_available_{{$val['id']}}_rt" id="is_pet_available_{{$val['id']}}_rt" checked>
                                                                                @else
                                                                                    <input type="checkbox" value="0" class="kt-group-checkable is_pet_available_rt" name="pet_available_{{$val['id']}}_rt" id="is_pet_available_{{$val['id']}}_rt">
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                <?php if($val['d_unit_price'] == '0.00') { ?>
                                                                                    <input type="text" class="form-control pet_charge_rt" name="d_unit_price_{{ $val['id'] }}_rt" placeholder="Pet Charge" value="FREE" readonly>
                                                                                <?php } else { ?>
                                                                                    <input type="text" class="form-control pet_charge_rt" name="d_unit_price_{{ $val['id'] }}_rt" placeholder="Pet Charge" value="$ {{$val['d_unit_price']}} EACH" readonly>
                                                                                <?php } ?>
                                                                            </td>
                                                                            <td>
                                                                                @if(isset($luggage_rate_array_rt[$val['id']]))
                                                                                    <input type="text" class="form-control total_fare_amt_pet_rt" value="{{ $luggage_rate_array_rt[$val['id']] }}" id="total_fare_amt_pet_{{ $val['id'] }}_rt" name="total_fare_amt_pet_{{ $val['id'] }}_rt" readonly/>
                                                                                @else
                                                                                    <input type="text" class="form-control total_fare_amt_pet_rt" value="0" id="total_fare_amt_pet_{{ $val['id'] }}_rt" name="total_fare_amt_pet_{{ $val['id'] }}_rt" readonly/>
                                                                                @endif
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

                        <input type="hidden" class="" name="v_traveller_name_rt[]" id="v_traveller_name_rt" >
                        <input type="hidden" class="" name="d_birth_month_year_rt[]" id="d_birth_month_year_rt">
                        <input type="hidden" class="" name="e_type_rt[]" id="e_type_rt">
                        <input type="hidden" class="" name="passanger_reservation_id_rt[]" id="passanger_reservation_id_rt">
                        <input type="hidden" class="" name="popup_value_rt" id="popup_value_rt" value="{{ $record_rt->i_total_num_passengers }}">
                    @endif

                    <input type="hidden" class="" name="e_type[]" id="e_type">
                    <input type="hidden" class="" name="v_traveller_name[]" id="v_traveller_name" >
                    <input type="hidden" class="" name="d_birth_month_year[]" id="d_birth_month_year">
                    <input type="hidden" class="" name="passanger_reservation_id[]" id="passanger_reservation_id">
                    <input type="hidden" class="" name="popup_value" id="popup_value" value="{{ $record->i_total_num_passengers }}">

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
                                                <?php
                                                    $luggage_value_array = Arr::pluck($reservation_luggage_info, 'i_value','i_sys_luggage_id');
                                                    $luggage_rate_array = Arr::pluck($reservation_luggage_info, 'd_price','i_sys_luggage_id');
                                                    $luggage_id_array = Arr::pluck($reservation_luggage_info, 'id','i_sys_luggage_id');
                                                    //pr($luggage_id_array); exit;
                                                ?>
                                                <table class="table table-bordered table-hover table-checkable" id="popup_luggages_table">
                                                    <thead>
                                                        <tr>
                                                            <th>Luggage Type.</th>
                                                            <th>No. of Luggage</th>
                                                            <th>Charge</th>
                                                            <th>Total Fare</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($luggages_list as $val)
                                                            <tr>
                                                                <td>
                                                                    <input type="text" class="form-control luggage_name" name="v_luggage_name" placeholder="Luggage Name" value="{{$val['v_name']}}" readonly>
                                                                </td>
                                                                <td>
                                                                    @if($val['id'] == 1 || $val['id'] == 2 || $val['id'] == 3 || $val['id'] == 4)
                                                                        <input type="hidden" class="luggage-id" name="luggage_id_{{ $val['id'] }}" id="luggage_id_{{ $val['id'] }}" value="{{ $val['id'] }}">
                                                                        @if(isset($luggage_id_array[$val['id']]))
                                                                            <input type="hidden" class="luggage_data_id" name="luggage_data_id_{{ $val['id'] }}" id="luggage_data_id" value="{{ $luggage_id_array[$val['id']] }}">
                                                                        @endif

                                                                        <select class="form-control luggage_type_{{ $val['id'] }} luggage_dropdown" name="luggage_numbers_{{ $val['id'] }}" placeholder="Type">
                                                                            <option value=""> Select </option>
                                                                            @for($i=0; $i <= 20; $i++)
                                                                                @if(isset($luggage_value_array[$val['id']]))
                                                                                    <option value="{{$i}}" {{ $luggage_value_array[$val['id']] == $i ? 'selected=""' : '' }}> {{$i}} </option>
                                                                                @else
                                                                                    <option value="{{$i}}"> {{$i}} </option>
                                                                                @endif
                                                                            @endfor
                                                                        </select>
                                                                    @elseif($val['id'] == 6 || $val['id'] == 7 || $val['id'] == 8 || $val['id'] == 12 || $val['id'] == 13 || $val['id'] == 14 || $val['id'] == 15 || $val['id'] == 16)
                                                                   <!--  id take in array -->
                                                                        <input type="hidden" class="luggage-id" name="luggage_id_{{ $val['id'] }}" id="luggage_id_{{ $val['id'] }}" value="{{ $val['id'] }}">

                                                                        @if(isset($luggage_id_array[$val['id']]))
                                                                            <input type="hidden" class="luggage_data_id" name="luggage_data_id_{{ $val['id'] }}" id="luggage_data_id" value="{{ $luggage_id_array[$val['id']] }}">
                                                                        @endif

                                                                        <select class="form-control  luggage_type_{{ $val['id'] }} luggage_dropdown" name="luggage_numbers_{{ $val['id'] }}" placeholder="Type">
                                                                            <option value=""> Select </option>
                                                                            @for($i=0; $i <= 10; $i++)
                                                                                @if(isset($luggage_value_array[$val['id']]))
                                                                                    <option value="{{$i}}" {{ $luggage_value_array[$val['id']] == $i ? 'selected=""' : '' }}> {{$i}} </option>
                                                                                @else
                                                                                    <option value="{{$i}}"> {{$i}} </option>
                                                                                @endif
                                                                            @endfor
                                                                        </select>
                                                                    @elseif($val['id'] == 9 || $val['id'] == 11)
                                                                        <input type="hidden" class="luggage-id_{{ $val['id'] }}" name="luggage_id_{{ $val['id'] }}" id="luggage_id" value="{{ $val['id'] }}">

                                                                        @if(isset($luggage_id_array[$val['id']]))
                                                                            <input type="hidden" class="luggage_data_id" name="luggage_data_id_{{ $val['id'] }}" id="luggage_data_id" value="{{ $luggage_id_array[$val['id']] }}">
                                                                        @endif

                                                                        <select class="form-control luggage_type_{{ $val['id'] }} luggage_dropdown" name="luggage_numbers_{{ $val['id'] }}" placeholder="Type">
                                                                            <option value=""> Select </option>
                                                                            @if(isset($luggage_value_array[$val['id']]))
                                                                                <option value="0" > 0 </option>
                                                                                <option value="1" {{ $luggage_value_array[$val['id']] == 1 ? 'selected=""' : '' }}> 1 </option>
                                                                                <option value="2" {{ $luggage_value_array[$val['id']] == 2 ? 'selected=""' : '' }}> 2 </option>
                                                                            @else
                                                                                <option value="0" > 0 </option>
                                                                                <option value="1" > 1 </option>
                                                                                <option value="2" > 2 </option>
                                                                            @endif
                                                                        </select>
                                                                    @elseif($val['id'] == 10)
                                                                        <input type="hidden" class="luggage-id" name="luggage_id_{{ $val['id'] }}" id="luggage_id_{{ $val['id'] }}" value="{{ $val['id'] }}">

                                                                        @if(isset($luggage_id_array[$val['id']]))
                                                                            <input type="hidden" class="luggage_data_id" name="luggage_data_id_{{ $val['id'] }}" id="luggage_data_id" value="{{ $luggage_id_array[$val['id']] }}">
                                                                        @endif

                                                                        <select class="form-control luggage_type_{{ $val['id'] }} luggage_dropdown" name="luggage_numbers_{{ $val['id'] }}" placeholder="Type">
                                                                            <option value=""> Select </option>
                                                                            @if(isset($luggage_value_array[$val['id']]))
                                                                                <option value="0" > 0 </option>
                                                                                <option value="1" {{ $luggage_value_array[$val['id']] == 1 ? 'selected=""' : '' }}> 1 </option>
                                                                            @else
                                                                                <option value="0" > 0 </option>
                                                                                <option value="1"> 1 </option>
                                                                            @endif
                                                                        </select>
                                                                    @elseif($val['id'] == 5)
                                                                        <input type="hidden" class="luggage-id" name="luggage_id_{{ $val['id'] }}" id="luggage_id_{{ $val['id'] }}" value="{{ $val['id'] }}">

                                                                        @if(isset($luggage_id_array[$val['id']]))
                                                                            <input type="hidden" class="luggage_data_id" name="luggage_data_id_{{ $val['id'] }}" id="luggage_data_id" value="{{ $luggage_id_array[$val['id']] }}">
                                                                        @endif

                                                                        <select class="form-control personel_luggage_info luggage_type_{{ $val['id'] }} luggage_dropdown" name="luggage_numbers_{{ $val['id'] }}" placeholder="Type">
                                                                        <option value=""> Select </option>
                                                                        @if(isset($luggage_value_array[$val['id']]))
                                                                            @for($i=1; $i<=$record->i_total_num_passengers; $i++)
                                                                                <option value="{{$i}}" {{ $luggage_value_array[$val['id']] == $i ? 'selected=""' : '' }}> {{$i}} </option>
                                                                            @endfor
                                                                        @else
                                                                        @endif
                                                                        </select>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($val['d_unit_price'] == '0.00')
                                                                        <input type="text" class="form-control luggage_charge" name="d_unit_price_{{ $val['id'] }}" placeholder="Luggage Charge" value="FREE" readonly>
                                                                    @else
                                                                        <input type="text" class="form-control luggage_charge" name="d_unit_price_{{ $val['id'] }}" placeholder="Luggage Charge" value="$ {{$val['d_unit_price']}} EACH" readonly>
                                                                    @endif

                                                                </td>
                                                                <td>
                                                                    @if(isset($luggage_rate_array[$val['id']]))
                                                                        <input type="text" class="form-control total_fare_amt" value="{{ $luggage_rate_array[$val['id']] }}" id="total_fare_amt_{{ $val['id'] }}" name="total_fare_amt_{{ $val['id'] }}" readonly/>
                                                                    @else
                                                                        <input type="text" class="form-control total_fare_amt" value="0 " id="total_fare_amt_{{ $val['id'] }}" name="total_fare_amt_{{ $val['id'] }}" readonly/>
                                                                    @endif

                                                                </td>
                                                            </tr>

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
                                                <?php
                                                    $luggage_id_array = Arr::pluck($reservation_luggage_info, 'id','i_sys_luggage_id');
                                                    //pr($luggage_id_array); exit;
                                                ?>
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

                                                                        @if(isset($luggage_id_array[$val['id']]))
                                                                            <input type="hidden" class="luggage_data_id" name="luggage_data_id_{{ $val['id'] }}" id="luggage_data_id" value="{{ $luggage_id_array[$val['id']] }}">
                                                                        @endif

                                                                    @if(isset($luggage_value_array[$val['id']]) && $luggage_value_array[$val['id']] == 1)
                                                                        <input type="checkbox" value="0" class="kt-group-checkable is_pet_available" name="pet_available_{{$val['id']}}" id="is_pet_available_{{$val['id']}}" checked>
                                                                    @else
                                                                        <input type="checkbox" value="0" class="kt-group-checkable is_pet_available" name="pet_available_{{$val['id']}}" id="is_pet_available_{{$val['id']}}">
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <?php if($val['d_unit_price'] == '0.00') { ?>
                                                                        <input type="text" class="form-control pet_charge" name="d_unit_price_{{ $val['id'] }}" placeholder="Pet Charge" value="FREE" readonly>
                                                                    <?php } else { ?>
                                                                        <input type="text" class="form-control pet_charge" name="d_unit_price_{{ $val['id'] }}" placeholder="Pet Charge" value="$ {{$val['d_unit_price']}} EACH" readonly>
                                                                    <?php } ?>
                                                                </td>
                                                                <td>
                                                                    @if(isset($luggage_rate_array[$val['id']]))
                                                                        <input type="text" class="form-control total_fare_amt_pet" value="{{ $luggage_rate_array[$val['id']] }}" id="total_fare_amt_pet_{{ $val['id'] }}" name="total_fare_amt_pet_{{ $val['id'] }}" readonly/>
                                                                    @else
                                                                        <input type="text" class="form-control total_fare_amt_pet" value="0" id="total_fare_amt_pet_{{ $val['id'] }}" name="total_fare_amt_pet_{{ $val['id'] }}" readonly/>
                                                                    @endif
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
                    <h5 class="modal-title" id="exampleModalLabel">Passenger Info. </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="row add-address-content">
                    <div class="modal-body">
                        <!-- <form action="" enctype="multipart/form-data" class="form-horizontal" id="frmEdit_passenger_info" method="POST"> -->
                        <div class="frmEdit_passenger_info" id="frmEdit_passenger_info">
                            <div class="add_passenger_info_margin" >
                                <div id="container" class="edit_passenger_table_informations">
                                    <table class="table table-bordered table-hover table-checkable" id="popup_passenger_table">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Name</th>
                                                <th>Date of Birth</th>
                                                <th>Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1; ?>
                                            @foreach($reservation_data as $val)
                                                <tr>
                                                    <td>
                                                        <?php echo $i; ?>
                                                        <input type="hidden" class="passanger_data_id" name="passanger_data_id[]" id="passanger_data_id" value="{{ $val['id'] }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control traveller_name required" name="v_traveller_name[]" placeholder="Passenger Name" value="{{$val['v_traveller_name']}}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control date_picker_dob birth_month_year required" name="d_birth_month_year[]" placeholder="Passenger Date of Birth" value="{{ ($val['d_birth_month_year']) ? date('m/d/Y',strtotime($val['d_birth_month_year'])) : '' }}">
                                                    </td>
                                                    <td>
                                                        <select class="form-control passanger_type required" name="e_type[]" placeholder="Type">
                                                            <option value="">Select</option>
                                                            <option value="Adult" {{ $val['e_type'] == 'Adult' ? 'selected=""' : '' }}>Adult</option>
                                                            <option value="Senior" {{ $val['e_type'] == 'Senior' ? 'selected=""' : '' }}>Senior</option>
                                                            <option value="Military" {{ $val['e_type'] == 'Military' ? 'selected=""' : '' }}>Military</option>
                                                            <option value="Child" {{ $val['e_type'] == 'Child' ? 'selected=""' : '' }}>Child</option>
                                                            <option value="Infant" {{ $val['e_type'] == 'Infant' ? 'selected=""' : '' }}>Infant</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <?php $i++; ?>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="after_changed_passenger_num">
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
                        <div class="frmEdit_passenger_info_rt" id="frmEdit_passenger_info_rt">
                            <div class="add_passenger_info_margin" >
                                <div id="container"  class="edit_passenger_table_informations_rt">
                                    <table class="table table-bordered table-hover table-checkable" id="popup_passenger_table_rt">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Name</th>
                                                <th>Date of Birth</th>
                                                <th>Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1; ?>
                                            @foreach($reservation_data_rt as $val)
                                                <tr>
                                                    <td>
                                                        <?php echo $i; ?>
                                                        <input type="hidden" class="passanger_data_id_rt" name="passanger_data_id_rt[]" id="passanger_data_id_rt" value="{{ $val['id'] }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control traveller_name_rt required" name="v_traveller_name_rt[]" placeholder="Passenger Name" value="{{$val['v_traveller_name']}}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control date_picker_dob birth_month_year_rt required" name="d_birth_month_year_rt[]" placeholder="Passenger Date of Birth" value="{{ ($val['d_birth_month_year']) ? date('m/d/Y',strtotime($val['d_birth_month_year'])) : '' }}">
                                                    </td>
                                                    <td>
                                                        <select class="form-control passanger_type_rt required" name="e_type[]" placeholder="Type">
                                                            <option value="">Select</option>
                                                            <option value="Adult" {{ $val['e_type'] == 'Adult' ? 'selected=""' : '' }}>Adult</option>
                                                            <option value="Senior" {{ $val['e_type'] == 'Senior' ? 'selected=""' : '' }}>Senior</option>
                                                            <option value="Military" {{ $val['e_type'] == 'Military' ? 'selected=""' : '' }}>Military</option>
                                                            <option value="Child" {{ $val['e_type'] == 'Child' ? 'selected=""' : '' }}>Child</option>
                                                            <option value="Infant" {{ $val['e_type'] == 'Infant' ? 'selected=""' : '' }}>Infant</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <?php $i++; ?>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="after_changed_passenger_num_rt">
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
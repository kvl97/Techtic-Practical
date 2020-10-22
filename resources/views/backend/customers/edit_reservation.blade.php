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
                <?php //pr($record); exit; ?>
                <!--begin::Form-->
                <form class="kt-form kt-form--label-right" id="frmEdit_cust_reservation" action="{{ ADMIN_URL }}customers-reservation/edit/{{ $record->id }}">
                    <div class="kt-portlet__body">
                    <div class="row">
                        
                        <input type="hidden" value="{{$record->i_customer_id}}" id="customer_id_reservation" />
                        <div class="col-lg-6 col-md-6 col-sm-12">

                            <div class="form-group row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Reservation Category </label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <select class="form-control " name="i_reservation_category_id" placeholder="Reservation Category">
                                        <option value="">Select</option>
                                        @if(count($reservation_category) > 0)
                                            @foreach($reservation_category as $val)
                                                <option value="{{ $val['id'] }}" {{ $record->i_reservation_category_id == $val['id'] ? 'selected=""' : '' }}>{{ $val['v_title'] }}</option>
                                            @endforeach
                                        @endif  
                                    </select>               
                                </div>
                            </div>

                            <?php //pr($record); exit; ?>
                            <div class="form-group row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Origin Point <span class="required">*</span></label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <select class="form-control required i_origin_point_id" placeholder="Origin Point" id="i_origin_point_id" name="i_origin_point_id">
                                        @foreach($service_area as $val)
                                            <option value="{{ $val['id'] }}" {{ $record->i_origin_point_id == $val['id'] ? 'selected=""' : '' }}>{{ $val['v_street1'].' '.$val['v_city'].' '.$val['v_country'].' '.$val['v_postal_code'] }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" class="form-control" id="origin_point_val" value="{{ $record->i_origin_point_id}}" />
                                </div>
                            </div>
                            
                            <div class="form-group row ">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Class Type <span class="required">*</span></label>
                                <div class="col-lg-7 col-md-7 col-sm-12 radio-btn-msg">
                                    <div class="kt-radio-inline mt-10">
                                        <label class="kt-radio">
                                        <input type="radio" class="required-least-one-radio e_class_type" name="e_class_type" value="OW" id="one_way" groupid="class_type" <?php if($record->e_class_type =='OW') { ?> checked='checked' <?php } ?>> One Way
                                        </label>

                                        <label class="kt-radio">
                                            <input type="radio" class="required-least-one-radio e_class_type" name="e_class_type" value="RT" id="round_trip" groupid="class_type"  <?php if($record->e_class_type =='RT') { ?> checked='checked' <?php } ?>> Round Trip
                                        </label>
                                        <span class="check"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Depart Date <span class="required">*</span></label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                <input type="text" class="form-control required date_picker_depart d_depart_date" name="d_depart_date" placeholder="Depart Date"  onblur="$(this).attr('readonly','readonly');" readonly="readonly" value="{{ date('m/d/Y',strtotime($record->d_depart_date)) }}">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12"> Contact Number<span class="required">*</span></label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                <input type="text" class="form-control required phone" name="v_contact_phone_number" placeholder="Contact Number" value="{{ $record->v_contact_phone_number}}">
                                </div>
                            </div>
                            <?php //pr($record); exit; ?>
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
                                        <input type="text" class="form-control required i_total_num_passengers" name="i_total_num_passengers" placeholder="Number of Passengers" id="total_passenger" onclick="addInfoLink();" value="{{ $record->i_total_num_passengers }}">
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
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Special Instruction</label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <textarea class="form-control " name="t_special_instruction" placeholder="Special Instruction"> {{  $record->t_special_instruction  }} </textarea>
                                </div>
                            </div>

                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">

                            <div class="form-group row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Reservation Status <span class="required">*</span></label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <select class="form-control required" name="e_reservation_status" placeholder="Status">
                                        <option value="">Select</option> 
                                        <option value="Quote" {{ $record->e_reservation_status == 'Quote' ? 'selected=""' : '' }}>Quote</option>
                                        <option value="New" {{ $record->e_reservation_status == 'New' ? 'selected=""' : '' }}>New</option>
                                        <option value="Provisional" {{ $record->e_reservation_status == 'Provisional' ? 'selected=""' : '' }}>Provisional</option>
                                        <option value="Processing" {{ $record->e_reservation_status == 'Processing' ? 'selected=""' : '' }}>Processing</option>
                                        <option value="Confirmed" {{ $record->e_reservation_status == 'Confirmed' ? 'selected=""' : '' }}>Confirmed</option>
                                        <option value="Pending" {{ $record->e_reservation_status == 'Pending' ? 'selected=""' : '' }}>Pending</option>
                                        <option value="Finalized" {{ $record->e_reservation_status == 'Finalized' ? 'selected=""' : '' }}>Finalized</option>
                                        <option value="Dispatched" {{ $record->e_reservation_status == 'Dispatched' ? 'selected=""' : '' }}>Dispatched</option>
                                        <option value="Active" {{ $record->e_reservation_status == 'Active' ? 'selected=""' : '' }}>Active</option>
                                        <option value="Completed" {{ $record->e_reservation_status == 'Completed' ? 'selected=""' : '' }}>Completed</option>
                                        <option value="Cancelled" {{ $record->e_reservation_status == 'Cancelled' ? 'selected=""' : '' }}>Cancelled</option>
                                        <option value="Refunded" {{ $record->e_reservation_status == 'Refunded' ? 'selected=""' : '' }}>Refunded</option>
                                        <option value="Voucher" {{ $record->e_reservation_status == 'Voucher' ? 'selected=""' : '' }}>Voucher</option>
                                    </select>  
                                </div>              
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Destination Point <span class="required">*</span></label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <select class="form-control required i_destination_point_id" placeholder="Destination Point" id="i_destination_point_id" name="i_destination_point_id">
                                        @foreach($service_area as $val)
                                            <option value="{{ $val['id'] }}" {{ $record->i_destination_point_id == $val['id'] ? 'selected=""' : '' }}>{{ $val['v_street1'].' '.$val['v_city'].' '.$val['v_country'].' '.$val['v_postal_code'] }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" id="destination_point_val" value="{{ $record->i_destination_point_id}}" />
                                </div>
                            </div>
                            
                            <div class="form-group row ">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Travel Alone <span class="required">*</span></label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                    <div class="kt-radio-inline mt-10">
                                        <label class="kt-radio">
                                        <input type="radio" class="required-least-one-radio e_travel_alone" name="e_travel_alone" value="YES" id="travel_alone_yes" groupid="travel_alone" <?php if($record->e_travel_alone =='YES') { ?> checked='checked' <?php } ?>> YES
                                        </label>

                                        <label class="kt-radio">
                                        <input type="radio" class="required-least-one-radio e_travel_alone" name="e_travel_alone" value="NO" id="travel_alone_no" groupid="travel_alone" <?php if($record->e_travel_alone =='NO') { ?> checked='checked' <?php } ?>> NO
                                        </label>
                                        <span class="check"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Return Date </label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                <input type="text" class="form-control date_picker_return d_return_date" name="d_return_date" placeholder="Return Date" onblur="$(this).attr('readonly','readonly');" readonly="readonly" disabled  value="{{ ($record->d_return_date) ? date('m/d/Y',strtotime($record->d_return_date)) : '' }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Contact Email Address <span class="required">*</span></label>
                                <div class="col-lg-7 col-md-7 col-sm-12">
                                <input type="text" class="form-control required email" name="v_contact_email_add" placeholder="Contact Email Address" value="{{ $record->v_contact_email_add }}">
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

                            
                        </div>

                        <input type="hidden" class="" name="e_type[]" id="e_type">  
                        <input type="hidden" class="" name="v_traveller_name[]" id="v_traveller_name" > 
                        <input type="hidden" class="" name="d_birth_month_year[]" id="d_birth_month_year">  
                        <input type="hidden" class="" name="passanger_reservation_id[]" id="passanger_reservation_id">  
                        <input type="hidden" class="" name="popup_value" id="popup_value" value="0"> 
                    </div>    

                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-9 ml-lg-auto">
                                    <button type="submit" class="btn btn-brand frm_submit_btn">Submit</button>
                                    <a href="{{ ADMIN_URL }}customers/edit/{{ $customer_id }}#kt_tabs_reservation" class="btn btn-secondary"> Cancel </a>
                                    
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
                                <div id="container">
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
<script>
    $(document).ready(function() {

        var date = new Date();
        $('.date_picker_depart').datepicker({
            format: 'mm/dd/yyyy',
            autoclose: true,
            //orientation: "bottom auto",
            startDate : date,
            todayHighlight: true,
        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('.date_picker_return').datepicker('setStartDate', minDate);
        })

        $('.date_picker_return').datepicker({
            format: 'mm/dd/yyyy',
            autoclose: true,
            //orientation: "bottom auto",
            todayHighlight: true,
        }).on('changeDate', function (selected) {
            var maxDate = new Date(selected.date.valueOf());
            $('.date_picker_depart').datepicker('setEndDate', maxDate);
        })

        //console.log($('.e_class_type:checked').val()); 
        if($('.e_class_type:checked').val() == 'RT') {
            $('.date_picker_return').removeAttr('disabled');
        } else {
            $('.date_picker_return').attr("disabled", true);
            $('.date_picker_return').val('');
        }
        
        $('.e_class_type').on('click', function() {
            if($('.e_class_type:checked').val() == 'RT') {
              $('.date_picker_return').removeAttr('disabled');
            } else {
              $('.date_picker_return').attr("disabled", true);
              $('.date_picker_return').val('');
            }
        });

        setTimeout(function() {
            $("#i_destination_point_id").select2({
                tags: true,
                tokenSeparators: [",", " "],
                delay: 250,
                ajax: {
                    url: ADMIN_URL + "get-service-point-list",
                    dataType: "json",
                    
                    data: function(term, page) {
                        return {
                            q: term
                        };
                    },
                    processResults: function(data) {
                    return {
                        results: data
                    };
                    }
                },
            });
        },3000);

        setTimeout(function() {
             $("#i_origin_point_id").select2({
                tags: true,
                tokenSeparators: [",", " "],
                delay: 250,
                ajax: {
                    url: ADMIN_URL + "get-service-point-list",
                    dataType: "json",
                    
                    data: function(term, page) {
                        return {
                            q: term
                        };
                    },
                    processResults: function(data) {
                    return {
                        results: data
                    };
                    }
                },
            });
        },3000);

        setTimeout(function(){
            addInfoLink();
        }, 1000); 

    });    

    $('.frm_submit_btn').on('click', function() {
        $('#passenger_info_popup').click(function() {
            addFields();
            $('#kt_modal_passenger_info').modal('hide');
        });
        
        var passenger_name = $('.traveller_name').val();
        var passenger_dob = $('.birth_month_year').val();
        var passenger_type = $('.passanger_type').val();
        console.log("popup.............",passenger_name);
        if (passenger_name == '' || passenger_dob == '' || passenger_type == '' || passenger_name == undefined || passenger_dob == undefined || passenger_type == undefined) {
            // alert("Please enter passenger informations.");
            swal.fire({
				title: 'Please add passenger informations.',
				text: '',
				type: 'warning',
				showCancelButton: false,
				cancelButtonText: 'Ok',
			});
            return false;
        }
    });

    $('.e_travel_alone').on('click', function() {
        travelAlone();
    });

    $("#total_passenger").on('click', function() {
        setTimeout(function(){
            addInfoLink();
        }, 1000);
    });

    $("#total_passenger").on('blur', function() {
        setTimeout(function(){
            addInfoLink();
        }, 500);
    });

    $("#luggages_info_popup").on('click', function() {
        
        setTimeout(function() {
            var total_passengers = $("#total_passenger").val();
            var length_dropdown = $('.personel_luggage_info  > option').length;
            if($('.personel_luggage_info').length > 0 && total_passengers != (length_dropdown-1)) {
                $(".personel_luggage_info option").remove();
                var html = '';
                html += '<option value="">Select</option>'; 
                for (i=0; i<=total_passengers; i++) {
                    html += '<option value="'+i+'">'+i+'</option>'; 
                }
                $('.personel_luggage_info').append(html); 
            }
        }, 1000);
    });

    $("#popup_luggages_table").on("change","select.luggage_dropdown",function(){
        var passanger_total_luggage = 0 ;
        var dropdown_value =$(this).val(); 
        var luggage_charge_value = $(this).parent().next().find(".luggage_charge").val(); 
        if(luggage_charge_value == 'FREE') {
            luggage_charge_value1 = 0;
        } else {  
            luggage_charge_value1 = luggage_charge_value.split('$ ').pop().split(' EACH')[0];
        }
        var total_lugg_amt = dropdown_value * luggage_charge_value1;
        $(this).parents('tr').find(".total_fare_amt").val(total_lugg_amt);
        
        $("select.luggage_dropdown").each(function(){
            if($(this).val() == '' || $(this).val() == undefined) {
                passanger_total_luggage = passanger_total_luggage;
            } else {
                passanger_total_luggage = passanger_total_luggage + parseInt($(this).val());
            }
            console.log("passanger_total_luggage.....",passanger_total_luggage);
            $('#total_luggages').val(passanger_total_luggage);
        });
        
        passanger_total_luggage = passanger_total_luggage;
    });

    var pet_amt = 0;
    
    $(".is_pet_available").on("change",function(){
        var pet_charge_value = $(this).parent().next().find(".pet_charge").val(); 
        pet_amt = pet_charge_value.split('$ ').pop().split(' EACH')[0];
        
        if($(this).prop("checked") == true) {
            $(this).parents('tr').find('.total_fare_amt_pet').val(pet_amt);
        } else {
            $(this).parents('tr').find('.total_fare_amt_pet').val('0');
        }
        console.log(",,,, ,,,,,,,,,,,,",$(".is_pet_available:checked").length);
        $('#tatal_pets').val($(".is_pet_available:checked").length);
    }); 

    function addFields(){
        console.log("hunction add  fields here...");
        if ($("#total_passenger").val()  == $("#edit_total_passenger").val()) {
            console.log("here...");
            $('#kt_modal_passenger_info').modal('show');
            return false;
        }
        setTimeout(function() {
            var date = new Date();
            $('.date_picker_dob').datepicker({
                format: 'mm/dd/yyyy',
                autoclose: true,
                //orientation: "bottom auto",
                endDate : date,
                todayHighlight: true,
            });
        }, 1000);

        var hidden_popup_value = $('#popup_value').val();

        var travel_alone = $('.e_travel_alone:checked').val();
        if(travel_alone == 'YES') {
            var total_number_passenger = 1;
        } else {
            var total_number_passenger = $("#total_passenger").val();
        }
        console.log("hidden_popup_value", hidden_popup_value);
        console.log("total_number_passenger", total_number_passenger);
        if(hidden_popup_value == 0  || hidden_popup_value == total_number_passenger) {
            console.log("if loop...");
            $('#popup_value').val(total_number_passenger);
            var html = '';
            
            html += '<table class="table table-bordered table-hover table-checkable" id="popup_table">';
            html += '<thead><tr>';
            html += '<th>No.</th><th>Name</th><th>Date of Birth</th><th>Type</th>'; 
            html += '</tr> </thead>'; 
            html += '<tbody>'; 
            for (i=0; i<total_number_passenger; i++) {
                html += '<tr>'; 
                html += '<td>'+(i+1)+'</td>'; 
                html += '<td><input type="text" class="form-control traveller_name required" name="v_traveller_name[]" placeholder="Name"></td>'; 
                html += '<td><input type="text" class="form-control date_picker_dob birth_month_year required" name="d_birth_month_year[]" placeholder="Date of Birth"></td>'; 
                html += '<td><select class="form-control passanger_type required" name="e_type[]" placeholder="Type"><option value="">Select</option><option value="Adult">Adult</option> <option value="Senior">Senior</option> <option value="Military">Military</option> <option value="Child">Child</option> <option value="Infant">Infant</option> </select></td>'; 
                html += '</tr>'; 
            }
            html += '</tbody>'; 
            html += '</table>';

            $('#container').html(html); 

            $('#kt_modal_passenger_info').modal('show');

        } else if(hidden_popup_value > total_number_passenger || hidden_popup_value < total_number_passenger) {
            console.log("else  loop...");
            swal.fire({
				title: 'your data will be removed',
				text: '',
				type: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Ok',
				cancelButtonText: 'Cancel',
				reverseButtons: true
			}).then(function(result) {
                if (result.dismiss === 'cancel') {
                    $("#total_passenger").val($("#edit_total_passenger").val());
                } else {
                    /* $('#popup_value').val('0'); */
                    $('#popup_value').val(total_number_passenger);
                    addFields();
                }          
            });
        } 
    }

    function travelAlone() {
        var travel_alone = $('.e_travel_alone:checked').val();
        setTimeout(function(){
            addInfoLink();
        }, 1000);
        if(travel_alone == 'YES') {
            $('.i_total_num_passengers').val('1');
            $('.i_total_num_passengers').attr('disabled', true);
        } else {
            $('.i_total_num_passengers').val('');
            $('.i_total_num_passengers').removeAttr('disabled', true);
        }
    }

    function addInfoLink() {
        
        var total_passenger = $("#total_passenger").val();
        if(total_passenger == '') {
            $('.passenger_info_popup').attr('style', 'display:none');
        } else {
            $('.passenger_info_popup').removeAttr('style', true);
        }
    }
    
  </script>
@stop
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
                    <?php //pr($record); exit; ?>
                <!--begin::Form-->
                    <form class="kt-form kt-form--label-right" id="frmAdd_cust_reservation" action="{{ ADMIN_URL }}customers-reservation/add/{{ $customer_id }}">
                        <div class="kt-portlet__body">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">

                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Reservation Category <span class="required">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                            <select class="form-control required" name="i_reservation_category_id" placeholder="Reservation Category">
                                                <option value="">Select</option>
                                                @if(count($reservation_category) > 0)
                                                    @foreach($reservation_category as $val)
                                                        <option value="{{ $val['id'] }}">{{ $val['v_title'] }}</option>
                                                    @endforeach
                                                @endif  
                                            </select>               
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Origin Point <span class="required">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                            <select class="form-control required i_origin_point_id" placeholder="Origin Point" id="i_origin_point_id" name="i_origin_point_id">
                                            
                                            </select>
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
                                                <input type="text" class="form-control i_number_of_luggages" name="i_number_of_luggages" placeholder="Number of luggages" id="total_luggages" readonly >  
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <a href="" class="luggages_info_popup popup_info_link" id="luggages_info_popup" data-toggle="modal" data-target="#kt_modal_luggages_info" >Add info</a> 
                                            </div>    
                                        </div>
                                    </div> 

                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Special Instruction</label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                            <textarea class="form-control" name="t_special_instruction" placeholder="Special Instruction"></textarea>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12">

                                <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Reservation Status <span class="required">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                            <select class="form-control required" name="e_reservation_status" placeholder="Status">
                                                <option value="">Select</option> 
                                                <option value="Quote">Quote</option>
                                                <option value="New">New</option>
                                                <option value="Provisional">Provisional</option>
                                                <option value="Processing">Processing</option>
                                                <option value="Confirmed">Confirmed</option>
                                                <option value="Pending">Pending</option>
                                                <option value="Finalized">Finalized</option>
                                                <option value="Dispatched">Dispatched</option>
                                                <option value="Active">Active</option>
                                                <option value="Completed">Completed</option>
                                                <option value="Cancelled">Cancelled</option>
                                                <option value="Refunded">Refunded</option>
                                                <option value="Voucher">Voucher</option>
                                            </select>                
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Destination Point <span class="required">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                            <select class="form-control required i_destination_point_id" placeholder="Destination Point" id="i_destination_point_id" name="i_destination_point_id">
                                            
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Travel Alone <span class="required">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                            <div class="kt-radio-inline mt-10">
                                                <label class="kt-radio">
                                                <input type="radio" class="required-least-one-radio e_travel_alone" name="e_travel_alone" value="YES" id="travel_alone_yes" groupid="travel_alone"> YES
                                                </label>

                                                <label class="kt-radio">
                                                <input type="radio" class="required-least-one-radio e_travel_alone" name="e_travel_alone" value="NO" id="travel_alone_no" groupid="travel_alone"> NO
                                                </label>
                                                <span class="check"></span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Return Date </label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control date_picker_return d_return_date" name="d_return_date" placeholder="Return Date" onblur="$(this).attr('readonly','readonly');" readonly="readonly" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-4 col-sm-12">Contact Email Address <span class="required">*</span></label>
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                        <input type="text" class="form-control required email" name="v_contact_email_add" placeholder="Contact Email Address">
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
                                                <input type="text" class="form-control i_num_pets" name="i_num_pets" placeholder="Number of Pets" id="tatal_pets" readonly >
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
                                                <input type="radio" class="required-least-one-radio" name="e_shuttle_type" value="Private" id="private" groupid="shuttle_type"> Private
                                                </label>

                                                <label class="kt-radio">
                                                <input type="radio" class="required-least-one-radio" name="e_shuttle_type" value="Shared" id="shared" groupid="shuttle_type"> Shared
                                                </label>
                                                <span class="check"></span>
                                            </div>
                                        </div>
                                    </div>

                                    

                                </div>

                                <input type="hidden" class="" name="e_type[]" id="e_type">  
                                <input type="hidden" class="" name="v_traveller_name[]" id="v_traveller_name" > 
                                <input type="hidden" class="" name="d_birth_month_year[]" id="d_birth_month_year">  
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
                                                                                    <input type="hidden" class="luggage-id_{{ $val['id'] }}" name="luggage_id_{{ $val['id'] }}" id="luggage_id" value="{{ $val['id'] }}"> 
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
    <!-- end:: Content -->
</div>

@stop

@section('custom_js')
<script>
    $(document).ready(function() {

        $('.kt_time_picker').val('');
        
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
        }, 500);
    });   
    
    $('.e_travel_alone').on('click', function() {
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
    });

    $('.frm_submit_btn').on('click', function() {
        $('#passenger_info_popup').click(function() {
            addFields();
            $('#kt_modal_passenger_info').modal('hide');
        });
        
        var passenger_name = $('.traveller_name').val();
        var passenger_dob = $('.birth_month_year').val();
        var passenger_type = $('.passanger_type').val();
        
        if (passenger_name == '' || passenger_dob == '' || passenger_type == '' || passenger_name == undefined || passenger_dob == undefined || passenger_type == undefined) {
            // alert("Please enter passenger informations..");
            swal.fire({
				title: 'Please add passenger informations.',
				text: '',
				type: 'warning',
				showCancelButton: false,
				cancelButtonText: 'Ok',
			});
            return false;
        }
        console.log("...test sbmt..");
    });

    $("#total_passenger").on('blur', function() {
        setTimeout(function(){
            addInfoLink();
        }, 500);
    });

    $("#total_passenger").on('ckick', function() {
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
        console.log("....1......",hidden_popup_value);
        console.log("....2......",total_number_passenger);

        if(hidden_popup_value == 0 || hidden_popup_value == total_number_passenger) {
                
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
                html += '<td><input type="text" class="form-control traveller_name" name="v_traveller_name[]" placeholder="Name"></td>'; 
                html += '<td><input type="text" class="form-control date_picker_dob birth_month_year" name="d_birth_month_year[]" placeholder="Date of Birth"></td>'; 
                html += '<td><select class="form-control passanger_type" name="e_type[]" placeholder="Type"><option value="">Select</option><option value="Adult">Adult</option> <option value="Senior">Senior</option> <option value="Military">Military</option> <option value="Child">Child</option> <option value="Infant">Infant</option> </select></td>'; 
                html += '</tr>'; 
            }
            html += '</tbody>'; 
            html += '</table>';
            
            $('#container_passenger').html(html); 
    
            $('#kt_modal_passenger_info').modal('show');

        } else if(hidden_popup_value > total_number_passenger || hidden_popup_value < total_number_passenger) {
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
                    $("#total_passenger").val(hidden_popup_value);
                } else {
                    $('#popup_value').val('0');
                    addFields();
                }          
            });
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
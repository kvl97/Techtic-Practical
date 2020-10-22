@extends('frontend.layouts.default')
@section('content')
<?php 
if(isset($location_info) && isset($location_info['e_shuttle_type']) && $location_info['e_shuttle_type'] == "Private") {
    $arr_country = $arr_all_county;
} else if(request()->route('id') != '' && $paymentStatus) {
    $arr_country = $arr_all_county;
}
 ?>
<!-- content area part -->
<div class="main-content">
    <!-- information block -->
    <section class="rocket-info">
        <div class="container">
            <div class="row no-gutters">
                <div class="col-md-3">
                    <div class="rocket-info-left dropdown-rocket">
                        <div class="dropdown-pills">
                            <a href="javascript:void(0);" class="dropbtn-rocket btn-filter icon icon-down-arrow">Select</a>
                        </div>
                        <div class="nav flex-column nav-pills dropdown-content-rocket" id="tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active" id="v-location-tab" href="javascript:;" role="tab" aria-controls="location" aria-selected="true" data-action="{{ ((request()->route('id') != '') ? 'book-a-shuttle/'.request()->route('id') : 'book-a-shuttle') }}">Start Reservation</a>
                            
                            <a class="nav-link private-tab-hide {{ (isset($location_info['e_shuttle_type']) && $location_info['e_shuttle_type'] == 'Private') ? 'd-none' : '' }}" id="runs-tab" href="javascript:;" role="tab" aria-controls="runs" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'display-line-runs/'.request()->route('id') : 'display-line-runs') }}">Display Line Runs</a>
                            
                            <a class="nav-link disable" id="passenger-information-tab" href="javascript:;" role="tab" aria-controls="passenger-information" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'passenger-information/'.request()->route('id') : 'passenger-information') }}">Passenger Information</a>
                            
                            <a class="nav-link disable" id="luggage-animals-tab" href="javascript:;" role="tab" aria-controls="luggage-animals" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'luggage-animals/'.request()->route('id') : 'luggage-animals') }}">Luggage and Animals</a>

                            <a class="nav-link disable" id="travel-tab" href="javascript:;" role="tab" aria-controls="travel" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'travel-details/'.request()->route('id') : 'travel-details') }}">Travel Details</a>
                            
                            <a class="nav-link disable private-tab-hide {{ (isset($location_info['e_shuttle_type']) && $location_info['e_shuttle_type'] == 'Private') ? 'd-none' : '' }}" id="confirm-tab" href="javascript:;" role="tab" aria-controls="confirm" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'currently-assigned-line-runs/'.request()->route('id') : 'currently-assigned-line-runs') }}">Currently Assigned Line Run</a>
                            @if((request()->route('id') != '' && !($paymentStatus)) || request()->route('id') == '')
                                <a class="nav-link disable" id="reservation-summary-tab" href="javascript:;" role="tab" aria-controls="reservation-summary" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'reservation-summary/'.request()->route('id') : 'reservation-summary') }}">Reservation Summary</a>

                                <a class="nav-link disable private-tab-hide {{ (isset($location_info['e_shuttle_type']) && $location_info['e_shuttle_type'] == 'Private') ? 'd-none' : '' }}" id="payment-tab" href="javascript:;" role="tab" aria-controls="payment" aria-selected="false"data-action="{{ ((request()->route('id') != '') ? 'reservation-payment/'.request()->route('id') : 'reservation-payment') }}">Payment</a>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="col-md-9">
                    <div class="rocket-info-right" id="home-page-location">
                        <div class="tab-content" id="v-tabContent">
                            <!-- 1 -->
                            <div class="tab-pane fade show active" id="location " role="tabpanel" aria-labelledby="location-tab">
                                <form action="{{ ((request()->route('id') != '') ? SITE_URL.'book-a-shuttle/'.request()->route('id') : SITE_URL.'book-a-shuttle') }}" class="rocket-info-details rocket-info-location" id="frontend_location_information" method="POST">
                                    <h3 class="rocket-info__title">Start Reservation</h3>
                                    <div class="rocket-info__one inquiry-form">
                                        <div class="row no-gutters">
                                            <div class="col-md-12 form-group">
                                                <div class="input-field">
                                                    <input type="text" class="form-control required" placeholder="Name" id="v_name" name="v_name" value="{{ 
                                                        (isset($location_info['v_name']) && $location_info['v_name'] != '') ? $location_info['v_name'] : ((!empty($customer_info) && $customer_info['v_firstname']) ? $customer_info['v_firstname'].' '. $customer_info['v_lastname']: '') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-12 form-group pr-1">
                                                <div class="input-field">
                                                    <input type="text" class="form-control required" placeholder="Contact Number" id="v_phone" name="v_phone" value="{{ (isset($location_info['v_phone']) && $location_info['v_phone'] != '') ? $location_info['v_phone'] : ((!empty($customer_info) && $customer_info['v_phone']) ? $customer_info['v_phone'] : '') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-12 form-group pl-1">
                                                <div class="input-field">
                                                    <input type="text" class="form-control email required" placeholder="Email" name="v_email" id="v_email" value="{{ (isset($location_info['v_email']) && $location_info['v_email'] != '') ? $location_info['v_email'] : ((!empty($customer_info) && $customer_info['v_email']) ? $customer_info['v_email'] : '') }}" />
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-bottom">
                                            <div class="row align-items-center">
                                                @if(request()->route('id') != '' && $paymentStatus)
                                                    <div class="col-md-12 col-lg-4 col-xl-4 form-group ps-wrapper d-flex align-items-center justify-content-start">
                                                        <p>Total Traveler:</p>    
                                                        <input type="hidden" name="peoples" value="{{ $location_info['peoples'] }}">
                                                        <span>{{ $location_info['peoples'] }}</span>
                                                    </div>
                                                    <div class="col-sm-6 col-lg-4 col-xl-4 form-group d-flex align-items-center justify-content-start">
                                                        <p>Booked Trip:</p>    
                                                        <input type="hidden" name="e_class_type" value="{{ $location_info['e_class_type'] }}" class="e_class_type">
                                                        <span>{{ $location_info['e_class_type'] == 'RT' ? 'Round Trip' : 'One Way' }}</span>
                                                    </div>
                                                    <div class="col-sm-6 col-lg-4 col-xl-4 form-group d-flex align-items-center justify-content-start">
                                                        <p>Shuttle:</p>    
                                                        <input type="hidden" name="e_shuttle_type" value="{{ $location_info['e_shuttle_type'] }}">
                                                        <span>{{ $location_info['e_shuttle_type'] }}</span>
                                                    </div>
                                                @else
                                                    <div class="col-md-12 col-lg-4 col-xl-5 form-group ps-wrapper d-flex align-items-center justify-content-start">
                                                        <p>How many people <span>(Including Children)?</span></p>
                                                        <div class="ps-info customNumber">
                                                            <input type="text" name="peoples" value="{{ ((isset($location_info['peoples']) && $location_info['peoples']) ? $location_info['peoples'] : '1') }}" class="ps-digit" data-limit="13" readonly>
                                                            <em class="up" data-value="up"></em>
                                                            <em class="down" data-value="down"></em>
                                                        </div>
                                                    </div>
                                                        
                                                    <div class="col-sm-6 col-lg-4 col-xl-3 form-group">
                                                        <div class="custom-radio-block mb-2">
                                                            
                                                            <input type="radio" class="e_class_type required-least-one-radio" id="radio11" groupid="class_type" name="e_class_type" value="OW" <?= (isset($location_info['e_class_type']) ? (($location_info['e_class_type'] == 'OW') ? "checked='true'" : "") : "checked='true'"); ?> />
                                                            <label for="radio11">One Way</label>
                                                        </div>
                                                        <div class="custom-radio-block">
                                                            <input type="radio" class="e_class_type required-least-one-radio" id="radio1" groupid="class_type" name="e_class_type" value="RT" <?= (isset($location_info['e_class_type']) && $location_info['e_class_type'] == 'RT') ? "checked='true'" : ""; ?>/>
                                                            <label for="radio1">Round Trip</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-lg-4 col-xl-3 form-group">
                                                        <div class="custom-radio-block mb-2">
                                                            <input type="radio" class="required-least-one-radio" id="radio2" groupid="shuttle_type" name="e_shuttle_type" value="Shared" <?= (isset($location_info['e_shuttle_type']) ? (($location_info['e_shuttle_type'] != 'Private') ? "checked='true'" : "") : "checked='true'"); ?>>
                                                            <label for="radio2">Shared Shuttle</label>
                                                        </div>
                                                        <div class="custom-radio-block">
                                                            <input type="radio" class="required-least-one-radio" id="radio22" groupid="shuttle_type" name="e_shuttle_type" value="Private" <?= (isset($location_info['e_shuttle_type']) && $location_info['e_shuttle_type'] == 'Private') ? "checked='true'" : ""; ?>>
                                                            <label for="radio22">Private Shuttle</label>
                                                        </div>
                                                    </div>
                                                @endif
                                            
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rocket-info__two">
                                        <div class="rocket-info__two--wrap">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="row">
                                                        <div class="col-xl-6 col-sm-12 form-group">
                                                            <div class="row no-gutters align-items-start">
                                                                <label class="col-xl-5 col-sm-6 col-form-label"><strong>1st Date of Travel</strong></label>
                                                                <div class="col-xl-7 col-sm-6 pl-sm-3 calendar-field">
                                                                    <input type="text" class="form-control date_picker_depart required" name="d_depart_date" id="d_depart_date" placeholder="Date of Travel" value="<?= (isset($location_info['d_depart_date']) && $location_info['d_depart_date'] != '') ? date('m/d/Y',strtotime($location_info['d_depart_date'])) : '' ?>" readonly="readonly">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-sm-12 form-group">
                                                    <div class="row no-gutters">
                                                        <label class="col-xl-5 col-sm-6 col-form-label">Pickup Location</label>
                                                        <div class="pickup-oneway select-field col-xl-7 col-sm-6 pl-sm-3">
                                                            <select id="home_pickup_location_resv" class="coll_exp_outgroup location_select required" placeholder="Pickup Location" name="home_pickup_location">
                                                                <option value="">Pick up Locations</option>
                                                            
                                                                @foreach($arr_country as $k=>$v)
                                                                    <optgroup label="{{$k}}">
                                                                        @foreach($v as $key => $value)
                                                                            <option value="{{$value['id']}}" location_ids="{{$value['id']}}" home_drop_off_city_ids="{{$value['v_drop_off_city_cant_be']}}" home_drop_off_city_must_be_ids="{{$value['v_drop_off_city_must_be']}}" service_area="{{$value['i_service_area_id']}}"<?php if(isset($location_info['home_pickup_location']) && $location_info['home_pickup_location'] == $value['id']){ echo 'selected';} ?>>{{$value['v_city']}}</option>
                                                                        @endforeach
                                                                    </optgroup>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-sm-12 form-group">
                                                    <div class="row no-gutters">
                                                        <label class="col-xl-5 col-sm-6 col-form-label">Drop off location</label>
                                                        <div class="dropoff-oneway select-field col-xl-7 col-sm-6 pl-sm-3">
                                                        <select id="home_dropoff_location_resv" class="coll_exp_outgroup location_select required" placeholder="Drop Off Location" name="home_dropoff_location">
                                                            <option value="">Drop Off Location</option>
                                                        </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="rocket-info__two--wrap <?= (isset($location_info['e_class_type']) && $location_info['e_class_type'] == 'RT') ? "" : "d-none" ?>" id="departure">
                                       
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="row">
                                                        <div class="col-xl-6 col-sm-12 form-group">
                                                            <div class="row no-gutters align-items-start">
                                                                <label class="col-xl-5 col-sm-6 col-form-label"><strong>2nd Date of Travel</strong></label>
                                                                <div class="col-xl-7 col-sm-6 pl-sm-3 calendar-field">
                                                                    <input type="text" class="form-control date_picker_return <?= (isset($location_info['e_class_type']) && $location_info['e_class_type'] == 'RT') ? "required" : ""; ?>" name="d_return_date" id="d_return_date" placeholder="Date of Travel" value="<?= (isset($location_info['d_return_date']) && $location_info['d_return_date'] != '') ? date('m/d/Y',strtotime($location_info['d_return_date'])) : '' ?>" readonly="readonly">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-sm-12 form-group">
                                                    <div class="row no-gutters">
                                                        <label class="col-xl-5 col-sm-6 col-form-label">Pickup Location</label>
                                                        <div class="pickup_round_trip select-field col-xl-7 col-sm-6 pl-sm-3">
                                                        <select id="home_pickup_location_rt_resv" class="coll_exp_outgroup location_select <?= (isset($location_info['e_class_type']) && $location_info['e_class_type'] == 'RT') ? "required" : ""; ?>" name="home_pickup_location_rt" placeholder="Pickup Location">
                                                            <option value="">Pick up Locations</option>
                                                        </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-sm-12 form-group">
                                                    <div class="row no-gutters">
                                                        <label class="col-xl-5 col-sm-6 col-form-label">Drop off location</label>
                                                        <div class="dropoff_round_trip select-field col-xl-7 col-sm-6 pl-sm-3">
                                                        <select id="home_dropoff_location_rt_resv" class="coll_exp_outgroup location_select <?= (isset($location_info['e_class_type']) && $location_info['e_class_type'] == 'RT') ? "required" : ""; ?>" name="home_dropoff_location_rt" placeholder="Drop Off Location">
                                                            <option value="">Drop Off Location</option>
                                                            
                                                        </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="basic-quote {{ (isset($location_info['e_shuttle_type']) && $location_info['e_shuttle_type'] == 'Private') ? 'd-none' : '' }} rocket-info__two--text pt-30 pb-30 pl-20 pr-20">
                                            <div class="row align-items-center">
                                                <div class="col-lg-9 col-md-8">
                                                    <p>Basic quote for selected options assuming all travellers are full fare adults - select further details on next steps for a detailed quote</p>
                                                </div>
                                                <div class="col-lg-3 col-md-4 text-right mt-20 mt-md-0">
                                                    <div class="amount d-none">
                                                        <button id="btnAddProfile" class="btn btn-md btn-red btn-red-display" type="button"></button>
                                                    </div>
                                                    <!--   <span class="btn btn-md btn-red">$150</span> -->
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="rocket-info__next mt-4 text-right">
                                        <!--  <a href="javascript:void(0);" class="btn btn-md btnNext btn-purple">Next</a> -->

                                        <button type="submit" class="btn btn-md btnNext btn-purple" value="Next">Next</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>  

<div class="modal fade bd-example-modal-sm p-md-0" id="kt_modal_login_account" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Login</h5>
                
            </div>
            <div class="row login-content"> 
                <div class="modal-body">
                    <form id="loginAccountFrm" class="custom-form" method="POST" action="{{ FRONTEND_URL }}login">
                        <div class="row ml-1 mr-1">
                            <div class="col-md-12 pl-10 pr-10 mb-3">    
                                <p style="font-size: 0.875rem">Please enter your Email Id and Password to login into system.</p>
                            </div>
                            <div class="col-md-12 pl-10 pr-10">                             
                                <div class="alert alert-danger invalid-error-message" style="display:none;">
                                    <a href="javascript:;" class="close" data-close="alert" aria-label="close">&times;</a>
                                    <span class="message">You have some form errors. Please check below.</span>
                                </div>
                            </div>
                            
                            <input class="form-input" name="login_redirect_url" type="hidden" id="login_redirect_url" value="{{ (isset($reservation_record['e_shuttle_type']) && $reservation_record['e_shuttle_type']=='Private') ? 'passenger-information' : 'display-line-runs' }}"> 
                            <div class="col-sm-12 form-group focused">
                                <label class="form-label" for="v_email">Email Id</label>
                                <input class="form-input filled" name="v_email" type="text" id="v_email" err-msg="Email Id" readonly="readonly"> 
                                <span class="icon fa fa-envelope-o"></span>
                            </div>
                            <div class="col-sm-12 form-group" style="margin-bottom: 1.5rem!important">
                                <label class="form-label" for="password">Password</label>
                                <input class="form-input required" name="password" type="password" id="password" err-msg="Password"> 
                                <span class="icon icon-lock"></span>
                            </div>
                        
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-xs btn-red login_account_popup">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@if(count($incompleteRecord) > 0)
<div class="modal fade bd-example-modal-sm p-md-0" id="kt_modal_welcome_back" tabindex="-1" role="dialog" aria-labelledby="WelcomeBackModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="WelcomeBackModalLabel">Welcome back!</h5>
            </div>
            
            <div class="modal-body">
                <p>We found that you missed to complete the last booking process.</p>
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr><td>Date of Travel</td><td>{!! ($incompleteRecord['d_travel_date'] != '') ? date('m/d/Y',strtotime($incompleteRecord['d_travel_date'])) : '' !!}</td></tr>
                        <tr><td>Pickup Location</td><td>{!! (!empty($incompleteRecord['pickup_city']) ? $incompleteRecord['pickup_city']['v_city'].' ('.$incompleteRecord['pickup_city']['v_county'].')' : '-') !!}</td></tr>
                        <tr><td>Drop-off Location</td><td>{!! (!empty($incompleteRecord['drop_off_city']) ? $incompleteRecord['drop_off_city']['v_city'].' ('.$incompleteRecord['drop_off_city']['v_county'].')' : '-') !!}</td></tr>
                        <tr><td>Trip Type</td><td>{!! ($incompleteRecord['e_class_type'] == 'OW') ? 'One Way' : 'Round Trip' !!}</td></tr>
                    </tbody>
                </table>
                <p>Would you like complete that process?</p>
            </div>    
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary welcome_back_popup_no" data-id="{!! $incompleteRecord['id'] !!}">No</button>
                <button type="submit" class="btn btn-xs btn-red welcome_back_popup_yes" data-id="{!! $incompleteRecord['id'] !!}">Yes</button>
            </div>
            
        </div>
    </div>
</div>
@endif
@section('custom_js')
<script>
    $(document).ready(function() {
       
        KTReservationFrontend.init();
        KTReservationFrontend.locationInformation();

        var startDateofReturn = $('.date_picker_depart').val();
        var dateOfReturn = new Date(startDateofReturn);
        var date = new Date();
        $('.date_picker_depart').datepicker({
            format: 'mm/dd/yyyy',
            autoclose: true,
            //orientation: "bottom auto",
            startDate: date,
            todayHighlight: true,
        }).on('changeDate', function(selected) {
            var minDate = new Date(selected.date.valueOf());
            if(minDate > moment($('.date_picker_return').val())) {
                $('.date_picker_return').val('');
            }
            $('.date_picker_return').datepicker('setStartDate', minDate);
        })

        $('.date_picker_return').datepicker({
            format: 'mm/dd/yyyy',
            autoclose: true,
            startDate: dateOfReturn,
            //orientation: "bottom auto",
            todayHighlight: true,
        })/* .on('changeDate', function(selected) {
            var maxDate = new Date(selected.date.valueOf());
            $('.date_picker_depart').datepicker('setEndDate', maxDate);
        }) */
        
        var dropoff_location = "{{ (isset($location_info['home_dropoff_location'])) ? $location_info['home_dropoff_location'] : '' }}";
        $resv_id = "{{ ((request()->route('id') != '') ? request()->route('id') : '') }}";
        
        if(dropoff_location != '') {
            $('#home_pickup_location_resv').trigger('change',[{onLoad:true}]);
        }

        var incompleteRecord = <?php echo count($incompleteRecord); ?>;
        
        if(incompleteRecord > 0) {
            $('#kt_modal_welcome_back').modal('show'); 
        }
    });

    $('.welcome_back_popup_yes').click(function() {
        var incompleteId = $(this).attr('data-id');
        $.post(SITE_URL + 'continue-process', {'incomplete_id': incompleteId, 'process' : 'Continue'}, function(data) {
            if(data.status == 'TRUE') {
                window.location.href = data.redirect_url;
            }
        });
    });
    $('.welcome_back_popup_no').click(function() {
        var incompleteId = $(this).attr('data-id');
        $.post(SITE_URL + 'continue-process', {'incomplete_id': incompleteId, 'process' : 'Stop'}, function(data) {
            if(data.status == 'TRUE') {
                location.reload(true);
            }
        });
    });

</script>
@stop

@stop
 
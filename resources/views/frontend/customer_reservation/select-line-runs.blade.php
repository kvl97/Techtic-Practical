@extends('frontend.layouts.default')
@section('content')
<?php 
if(request()->route('id') != '' && $paymentStatus) {
    
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
                            <a class="nav-link completed" id="v-location-tab" href="javascript:;" role="tab" aria-controls="location" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'book-a-shuttle/'.request()->route('id') : 'book-a-shuttle') }}">Start Reservation</a>
                            
                            <a class="nav-link active" id="runs-tab" href="javascript:;" role="tab" aria-controls="runs" aria-selected="true" data-action="{{ ((request()->route('id') != '') ? 'display-line-runs/'.request()->route('id') : 'display-line-runs') }}">Display Line Runs</a>
                            
                            <a class="nav-link" id="passenger-information-tab" href="javascript:;" role="tab" aria-controls="passenger-information" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'passenger-information/'.request()->route('id') : 'passenger-information') }}">Passenger Information</a>
                            
                            <a class="nav-link disable" id="luggage-animals-tab" href="javascript:;" role="tab" aria-controls="luggage-animals" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'luggage-animals/'.request()->route('id') : 'luggage-animals') }}">Luggage and Animals</a>

                            <a class="nav-link disable" id="travel-tab" href="javascript:;" role="tab" aria-controls="travel" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'travel-details/'.request()->route('id') : 'travel-details') }}">Travel Details</a>
                            
                            <a class="nav-link disable" id="confirm-tab" href="javascript:;" role="tab" aria-controls="confirm" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'currently-assigned-line-runs/'.request()->route('id') : 'currently-assigned-line-runs') }}">Currently Assigned Line Run</a>
                            
                            @if((request()->route('id') != '' && !($paymentStatus)) || request()->route('id') == '')
                                <a class="nav-link disable" id="reservation-summary-tab" href="javascript:;" role="tab" aria-controls="reservation-summary" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'reservation-summary/'.request()->route('id') : 'reservation-summary') }}">Reservation Summary</a>

                                <a class="nav-link disable" id="payment-tab" href="javascript:;" role="tab" aria-controls="payment" aria-selected="false"data-action="{{ ((request()->route('id') != '') ? 'reservation-payment/'.request()->route('id') : 'reservation-payment') }}">Payment</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="rocket-info-right">
                        <div class="tab-content" id="v-tabContent">
                            <!-- 2 -->
                            <div class="tab-pane fade show active" id="runs" role="tabpanel" aria-labelledby="runs-tab">
                                <form action="{{ ((request()->route('id') != '') ? SITE_URL.'display-line-runs/'.request()->route('id') : SITE_URL.'display-line-runs') }}" class="rocket-info-details rocket-info-runs-tab" id="frontend_linerun_info" method="POST">
                                <input type="hidden" name="redirect_url" value="{{ ((request()->route('id') != '') ? 'passenger-information/'.request()->route('id') : 'passenger-information') }}" id="redirect_url">
                                <input type="hidden" name="paymentStatus" value="{{ $paymentStatus }}" id="paymentStatus">
                                <input type="hidden" name="departure_data_count" value="" id="departure_data_count">
                                <input type="hidden" name="return_data_count" value="" id="return_data_count">
                                <input type="hidden" name="continue_process" value="Yes" id="continue_process">
                                    <h3 class="rocket-info__title">Display Line Runs</h3>
                                    <div class="rocket-info__three">
                                        <div class="rocket-info__line">
                                            <div class="from-location form-group select-field">
                                                <label class="label-select">From</label>
                                                <select class="form-control coll_exp_outgroup location_select required" placeholder="Pickup Location" name="home_pickup_location" id="select_linerun_from">
                                                    @foreach($arr_country as $k=>$v)
                                                        <optgroup label="{{$k}}">
                                                            @foreach($v as $key => $value)
                                                                <option value="{{ $value['id'] }}" location_ids="{{ $value['id'] }}" home_drop_off_city_ids="{{ $value['v_drop_off_city_cant_be'] }}" home_drop_off_city_must_be_ids="{{ $value['v_drop_off_city_must_be'] }}" service_area="{{ $value['i_service_area_id'] }}" <?= (isset($location_info['home_pickup_location']) && $location_info['home_pickup_location'] == $value['id']) ? 'selected' : ''; ?>>{{$value['v_city']}}</option>
                                                                
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="line-runs-icons select-5">
                                                <a href="#"><img src="{{ FRONTEND_URL}}frontend/assets/images/line-runs.png" /> </a>
                                            </div>
                                            <div class="to-location form-group select-field">
                                                <label class="label-select">To</label>
                                                <select class="form-control coll_exp_outgroup location_select required" placeholder="Drop Off Location" name="home_dropoff_location" id="select_linerun_to">
                                                    @foreach($arr_country as $k=>$v)
                                                    <optgroup label="{{$k}}">
                                                        @foreach($v as $key => $value)
                                                            <option value="{{$value['id']}}" location_ids="{{$value['id']}}" service_area="{{$value['i_service_area_id']}}" <?= (isset($location_info['home_dropoff_location']) && $location_info['home_dropoff_location'] == $value['id']) ? 'selected' : ''; ?>>{{$value['v_city']}}</option>
                                                            
                                                        @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group calendar-field select-field">
                                                <label class="label-select">Departure</label>
                                                <input type="text" class="form-control date_picker_depart required" id="d_depart_date" name="d_depart_date" placeholder="Date of Travel" value="{{ $location_info['d_depart_date'] ? date('m/d/Y',strtotime($location_info['d_depart_date'])) : '' }}" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="rocket-info__line <?= ((isset($location_info['e_class_type']) && $location_info['e_class_type'] == 'OW') ? 'd-none' : ''); ?>" id="display_line_runs_rt">
                                            <div class="from-location-rt form-group select-field">
                                                <label class="label-select">From</label>
                                                <select class="form-control coll_exp_outgroup location_select <?= ($location_info['e_class_type'] == 'RT') ? 'required' : ''; ?>" placeholder="Pickup Location" name="home_pickup_location_rt" id="select_linerun_from_rt">
                                                   
                                                </select>
                                            </div>
                                            <div class="line-runs-icons select-5">
                                                <a href="#"><img src="{{ FRONTEND_URL}}frontend/assets/images/line-runs.png" /> </a>
                                            </div>
                                            <div class="to-location-rt form-group select-field">
                                                <label class="label-select">To</label>
                                                <select class="form-control coll_exp_outgroup location_select <?= ($location_info['e_class_type'] == 'RT') ? 'required' : ''; ?>" placeholder="Drop Off Location" name="home_dropoff_location_rt" id="select_linerun_to_rt">
                                                    
                                                </select>
                                            </div>
                                            <div class="form-group calendar-field select-field return_date_rt">
                                                <label class="label-select">Return</label>
                                                <input type="text" class="form-control date_picker_return <?= ($location_info['e_class_type'] == 'RT') ? 'required' : ''; ?>" name="d_return_date" id="d_return_date" placeholder="Date of Travel" value="<?= (($location_info['d_return_date']) ? date('m/d/Y',strtotime($location_info['d_return_date'])) : '') ?>" readonly="readonly">
                                            </div>
                                        </div>
                                        
                                        <div class="rocket-info__line rocket-info__line_bottom">
                                            @if(request()->route('id') != '' && $paymentStatus)
                                                <div class="col-sm-6 col-lg-4 col-xl-4 form-group d-flex align-items-center justify-content-start">
                                                    <p>Booked Trip: </p>    
                                                    <input type="hidden" name="e_class_type" value="{{ $location_info['e_class_type'] }}" id="type_of_trip">
                                                    <span>{{ $location_info['e_class_type'] == 'RT' ? 'Round Trip' : 'One Way' }}</span>
                                                </div>
                                                <div class="col-sm-6 col-lg-4 col-xl-4 form-group d-flex align-items-center justify-content-start">
                                                    <p>Shuttle: </p>    
                                                    <input type="hidden" name="e_shuttle_type" value="{{ $location_info['e_shuttle_type'] }}" id="type_of_shuttle">
                                                    <span>{{ $location_info['e_shuttle_type'] }}</span>
                                                </div>
                                                <div class="col-sm-6 col-lg-4 col-xl-4 form-group d-flex align-items-center justify-content-start">
                                                    <p>Total Traveler: </p>    
                                                    <input type="hidden" name="peoples" value="{{ $location_info['peoples'] }}" id="passengers_details">
                                                    <span>{{ $location_info['peoples'] }}</span>
                                                </div>
                                                <div class="rocket-search d-none">
                                                    <a href="javascript:;" class="btn btn-sm btn-red" id="line_runs_search">SEARCH</a>
                                                </div>
                                            @else
                                                <div class="select-field">
                                                    <label class="label-select">Trip Type</label>
                                                    <select name="e_class_type" class="form-control" id="type_of_trip">
                                                        <option value="OW" <?= ($location_info['e_class_type'] && $location_info['e_class_type'] == 'OW')  ? 'selected=""' : ''; ?>>One Way</option>
                                                        <option value="RT" <?= ($location_info['e_class_type'] && $location_info['e_class_type'] == 'RT') ? 'selected=""' : ''; ?>>Round Trip</option>
                                                    </select>
                                                </div>
                                                <div class="select-field disabled">
                                                    <label class="label-select">Shuttle Type</label>
                                                    <select name="e_shuttle_type" class="form-control" id="type_of_shuttle" disabled="true">
                                                        <option value="Shared" <?= ($location_info['e_shuttle_type'] && $location_info['e_shuttle_type']=='Shared') ? 'selected=""' : ''; ?>>Shared</option>
                                                    </select>
                                                </div>
                                                <div class="select-field">
                                                    <label class="label-select">No. Of Passengers</label>
                                                    <select class="form-control" id="passengers_details" name="peoples">
                                                    @for ($i = 1; $i <= 20; $i++)
                                                        <option value="{{ $i }}" <?= ($location_info['peoples'] == $i) ? 'selected' : ''; ?>>{{ $i }}</option>
                                                    @endfor
                                                </select>
                                                </div>
                                                <div class="rocket-search d-none">
                                                    <a href="javascript:;" class="btn btn-sm btn-red" id="line_runs_search">SEARCH</a>
                                                </div>
                                            @endif
                                           
                                        </div>
                                    </div>
                                    <div class="result_data">
                                        
                                    </div>
                                    <div class="rocket-info__next mt-4 text-right">
                                        <button type="submit" class="btn btn-md btnNext btn-purple d-none" value="Next">Next</button>
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
<div class="modal fade bd-example-modal-sm p-md-0" id="kt_modal_trip_not_available" tabindex="-1" role="dialog" aria-labelledby="TripNotAvailableLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="TripNotAvailableLabel">Line run not available</h5>
            </div>            
            <div class="modal-body">
                <p class="message">There is no line run available for return/departure trip. So, system is considering it as One Way trip.</p>
                <p>Do you want to proceed?</p>
            </div>    
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-xs btn-red trip_not_available_yes">Yes</button>
            </div>
            
        </div>
    </div>
</div>
@section('custom_js')
<script>
    $resv_id = "{{ ((request()->route('id') != '') ? request()->route('id') : '') }}";
    function get_line_runs(pick_up_location_id,drpoOff_location_id) {       
        var departure_location = $('option:selected','#select_linerun_from').text();
        var return_location = $('option:selected','#select_linerun_from_rt').text();
        var d_depart_date = $('#d_depart_date').val();
        var d_return_date = $('#d_return_date').val();
        var type_of_trip = $('#type_of_trip').val();
        var type_of_shuttle = $('#type_of_shuttle').val();
        var passengers_details = $('#passengers_details').val();
       
        if(d_depart_date != '' && pick_up_location_id != '' && drpoOff_location_id != '') {
            if($resv_id != '') {
                var url = SITE_URL + 'line_run_search/' + $resv_id;
            } else {
                var url = SITE_URL + 'line_run_search';
            }
            $.ajax({
                url : url,
                method: 'POST',
                data: {'i_origin_service_area_id': pick_up_location_id, 'i_dest_service_area_id': drpoOff_location_id,'d_depart_date': d_depart_date, 'd_return_date': d_return_date, 'type_of_trip': type_of_trip,'type_of_shuttle':type_of_shuttle, 'passengers_details': passengers_details, 'departure_location': departure_location, 'return_location': return_location},
                success: function (data) {
                    $(".result_data").html(data);
                }
            });
        }
    }
    $(document).ready(function() {
        $('#select_linerun_from').trigger("change",[{onLoad:true}]);
        KTReservationFrontend.init();
        KTReservationFrontend.selectLineRuns();

        //get_line_runs('{{$serviceIds['home_pickup_service_id'] ? $serviceIds['home_pickup_service_id'] :''}}','{{$serviceIds['home_dropoff_service_id'] ? $serviceIds['home_dropoff_service_id'] :''}}');
        
        var dateOfReturn = new Date($('.date_picker_depart').val());
        $('.date_picker_depart').datepicker({
            format: 'mm/dd/yyyy',
            autoclose: true,
            orientation: "auto top",
            startDate: new Date(),
            todayHighlight: true,
        }).on('changeDate', function(selected) {
            var minDate = new Date(selected.date.valueOf());
            if(minDate > moment($('.date_picker_return').val())) {
                $('.date_picker_return').val('')
            }
            $('.date_picker_return').datepicker('setStartDate', minDate);
            $('#line_runs_search').trigger('click');
        });

        $('.date_picker_return').datepicker({
            format: 'mm/dd/yyyy',
            autoclose: true,
            startDate: dateOfReturn,
            orientation: "auto top",
            todayHighlight: true,
        }).on('changeDate', function(selected) {
            $('#line_runs_search').trigger('click');
        });

        $('body').on('change','#frontend_linerun_info select', function() {
            var type_of_trip = $('#type_of_trip').val();
            var return_location = $('option:selected','#select_linerun_from_rt').text();
            var d_return_date = $('#d_return_date').val();
            if(type_of_trip == 'OW' && $(this).attr('id') != 'select_linerun_from_rt' && $(this).attr('id') != 'select_linerun_to_rt') {
                $('#line_runs_search').trigger('click');
            } else if(type_of_trip == 'RT' && return_location != '' && d_return_date != '') {
                $('#line_runs_search').trigger('click');
            }
        });

        $('#line_runs_search').on('click', function() {
            var origin_service_area_id = $('option:selected', '#select_linerun_from').attr('service_area');
            var dest_service_area_id = $('option:selected', '#select_linerun_to').attr('service_area');
            get_line_runs(origin_service_area_id,dest_service_area_id);
        });

        $('#type_of_trip').change(function() {
            if (this.value == 'OW') {
                $('#display_line_runs_rt').addClass('d-none')
                $('#display_line_runs_rt input, #display_line_runs_rt select').removeClass('required');                
            } else {
                $('#display_line_runs_rt').removeClass('d-none')
                $('#display_line_runs_rt input, #display_line_runs_rt select').addClass('required');
            }
        });

       
    });
    $('#select_linerun_from').on('change', function (e, data) {
        var onload = (data != undefined) ? data.onLoad : false;
        var origin_service_area_id = $('option:selected', this).attr('service_area');
        var value = $(this).val();
        if($resv_id != '') {
            var url = SITE_URL + 'get-dropoff-locations/' + $resv_id;
        } else {
            var url = SITE_URL + 'get-dropoff-locations';
        }
        $.post(url, {origin_service_area_id: origin_service_area_id, tab: 'select-lineruns'},function(data){
            $('#select_linerun_to').html(data).trigger('change', [{onLoad: onload}]);
        });
        $.post(url, {origin_service_area_id: origin_service_area_id, tab: 'select-lineruns-to-rt'},function(data) {
            if(onload) {
                $('#select_linerun_to_rt').html(data).trigger('change');
            } else {
                $('#select_linerun_to_rt').html(data).val(value).trigger('change');
            }            
        });
    });
    $('body').on('change','#select_linerun_to', function (e, data) {
        var onload = (data != undefined) ? data.onLoad : false;
        var value = $(this).val();
        if($resv_id != '') {
            var url = SITE_URL + 'get-dropoff-locations/' + $resv_id;
        } else {
            var url = SITE_URL + 'get-dropoff-locations';
        }
        var origin_service_area_id = $('option:selected', this).attr('service_area');
        $.post(url, {origin_service_area_id: origin_service_area_id, tab: 'select-lineruns-from-rt'},function(data) {
            if(onload) {
                $('#select_linerun_from_rt').html(data).trigger('change');
            } else {
                $('#select_linerun_from_rt').html(data).val(value).trigger('change');
            }
        });
    });
    $('.trip_not_available_yes').click(function() {
        $('#continue_process').val('Yes');
        $('.btnNext').trigger('click');
    });
</script>
@stop

@stop
        
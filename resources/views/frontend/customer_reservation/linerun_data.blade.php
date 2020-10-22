@if($type_of_trip == "RT")
    <div class="rocket-info__two" id="show_line_run">
        <div class="rocket-info__tickets" >
            <div class="row">
                @if(count($departure_data) > 0 || count($return_data) > 0)
                    @if($departAvailable == 0 && $returnAvailable == 0) 
                        <div class="col-md-12 col-12 rocket-info__tickets--bg">
                            <div class="alert alert-danger" role="alert">
                                @if(count($departure_data) > 0 && count($return_data) > 0)
                                    Shuttles in your desired Travel Window are currently full; there is no available seat to book.
                                @elseif(count($departure_data) > 0)
                                    Shuttles in your desired Travel Window are currently full; there is no available seat to book. There is no line run available for return trip. 
                                    @if($paymentStatus)
                                        You can't be proceed next to update reservation information. Please cancel this ticket and try to book a new one by selecting trip as 'One Way'. Thank you.
                                    @endif
                                @elseif(count($return_data) > 0)
                                    Shuttles in your desired Travel Window are currently full; there is no available seat to book. There is no line run available for departure trip.
                                    @if($paymentStatus)
                                        You can't be proceed next to update reservation information. Please cancel this ticket and try to book a new one by selecting trip as 'One Way'. Thank you.
                                    @endif
                                @endif
                            </div>
                        </div>
                    @elseif($departAvailable == 0)
                        <div class="col-md-12 col-12 rocket-info__tickets--bg">
                            <div class="alert alert-danger" role="alert">
                                @if(count($departure_data) == 0)
                                    There is no line run available for departure trip. 
                                    @if($paymentStatus)
                                        You can't be proceed next to update reservation information. Please cancel this ticket and try to book a new one by selecting trip as 'One Way'. Thank you.
                                    @endif
                                @else
                                    Shuttles in your desired Travel Window for Leg 1 (departure) are currently full; there is no available seat to book. You can't be proceed next to update reservation information. 
                                    @if($paymentStatus)
                                        Please cancel this ticket and try to book a new one by selecting trip as 'One Way'. Thank you.
                                    @endif
                                @endif
                            </div>
                        </div>                        
                    @elseif($returnAvailable == 0)
                        <div class="col-md-12 col-12 rocket-info__tickets--bg">
                            <div class="alert alert-danger" role="alert">
                                @if(count($return_data) == 0)
                                    There is no line run available for return trip. 
                                    @if($paymentStatus)
                                        You can't be proceed next to update reservation information. Please cancel this ticket and try to book a new one by selecting trip as 'One Way'. Thank you.
                                    @endif
                                @else
                                    Shuttles in your desired Travel Window for Leg 2 (return) are currently full; there is no available seat to book.
                                    @if($paymentStatus)
                                        Please cancel this ticket and try to book a new one by selecting trip as 'One Way'. Thank you.
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endif
                @endif

                @if(count($dateNotice) > 0)
                    <div class="col-md-12 col-12 rocket-info__tickets--bg">
                        <div class="alert alert-info" role="alert">
                        @foreach($dateNotice as $dk => $date)
                            <p>{!! date('m/d/Y', strtotime($date['d_blackout_date'])) !!} - {!! $date['v_date_desc'] !!}</p>
                        @endforeach
                        </div>
                    </div>
                @endif
                @if(count($departure_data) > 0 && count($return_data) > 0)
                    <div class="col-md-6 col-12 rocket-info__tickets--bg">
                        <p class="rocket-info__tickets-title">Target Departure from {!! $departure_location !!}</p>
                        <div class="row">                            
                            @foreach ($departure_data as $daparTwoWayKey => $daparTwoWayVal)
                                <div class="col-md-12 col-sm-6 mb-3">
                                    <div class="rocket-info__tickets--inner <?= ($passengerCount <= ($daparTwoWayVal['i_num_available'] - $daparTwoWayVal['i_num_booked_seats']) ? 'available-bg' : 'red-bg')?>">
                                        <div class="tickets-name">
                                            {!! $daparTwoWayVal['vehicle_fleet']['get_vehicle_specification']['v_make']!!} {!! $daparTwoWayVal['vehicle_fleet']['get_vehicle_specification']['v_model']!!} {!! $daparTwoWayVal['vehicle_fleet']['get_vehicle_specification']['v_series']!!}
                                        </div>
                                        <div class="row no-gutters">
                                            <div class="col-md-12 col-lg-6 col-sm-6 col-6 mb-2">
                                                <span class="tickets-one">Date</span>
                                                <span class="tickets-two">@if($daparTwoWayVal['d_run_date'] != ''){!! date(DATE_FORMAT , strtotime($daparTwoWayVal['d_run_date'])) !!}@endif</span>
                                            </div>
                                            <div class="col-md-12 col-lg-6 col-sm-6 col-6 mb-2">
                                                <span class="tickets-one">Time</span>
                                                <span class="tickets-two"> @if($daparTwoWayVal['t_scheduled_arr_time'] != ''){!! date('g:i a' , strtotime($daparTwoWayVal['t_scheduled_arr_time'])) !!}@endif</span>
                                            </div>
                                            <div class="col-md-12 col-lg-6 col-sm-6 col-6">
                                                <span class="tickets-one">Van ID</span>
                                                <span class="tickets-two">@if($daparTwoWayVal['vehicle_fleet'] != ''){!! $daparTwoWayVal['vehicle_fleet']['v_vehicle_code'] !!}@endif</span>
                                            </div>
                                            <div class="col-md-12 col-lg-6 col-sm-6 col-6">
                                                <span class="tickets-one">Booked / No. Seat</span>
                                                <span class="tickets-two">{{ $daparTwoWayVal['i_num_booked_seats'] }} / {!! $daparTwoWayVal['i_num_available']!!}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-6 col-6 col-12 rocket-info__tickets--bg">
                        <p class="rocket-info__tickets-title">Target Arrival at {!! $return_location !!}</p>
                        <div class="row">
                            @foreach ($return_data as $returnTwoWayKey => $returnTwoWayVal)
                                
                                <div class="col-md-12 col-sm-6 mb-3">
                                    <div class="rocket-info__tickets--inner <?= ($passengerCount <= ($returnTwoWayVal['i_num_available'] - $returnTwoWayVal['i_num_booked_seats']) ? 'available-bg' : 'red-bg')?>">
                                        <div class="tickets-name">
                                            {!! $returnTwoWayVal['vehicle_fleet']['get_vehicle_specification']['v_make']!!} {!! $returnTwoWayVal['vehicle_fleet']['get_vehicle_specification']['v_model']!!} {!! $returnTwoWayVal['vehicle_fleet']['get_vehicle_specification']['v_series']!!}
                                        </div>
                                        <div class="row no-gutters">
                                            <div class="col-md-12 col-lg-6 col-sm-6 col-6 mb-2">
                                                <span class="tickets-one">Date</span>
                                                <span class="tickets-two">@if($returnTwoWayVal['d_run_date'] != ''){!! date(DATE_FORMAT , strtotime($returnTwoWayVal['d_run_date'])) !!}@endif</span>
                                            </div>
                                            <div class="col-md-12 col-lg-6 col-sm-6 col-6 mb-2">
                                                <span class="tickets-one">Time</span>
                                                <span class="tickets-two"> @if($returnTwoWayVal['t_scheduled_arr_time'] != ''){!! date('g:i a' , strtotime($returnTwoWayVal['t_scheduled_arr_time'])) !!}@endif</span>
                                            </div>
                                            <div class="col-md-12 col-lg-6 col-sm-6 col-6">
                                                <span class="tickets-one">Van ID</span>
                                                <span class="tickets-two">@if($returnTwoWayVal['vehicle_fleet'] != ''){!! $returnTwoWayVal['vehicle_fleet']['v_vehicle_code'] !!}@endif</span>
                                            </div>
                                            <div class="col-md-12 col-lg-6 col-sm-6 col-6">
                                                <span class="tickets-one">Booked / No. Seat</span>
                                                <span class="tickets-two">{{ $returnTwoWayVal['i_num_booked_seats'] }} / {!! $returnTwoWayVal['i_num_available']!!}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @elseif(count($departure_data) > 0)                    
                    <div class="col-md-12 col-12 rocket-info__tickets--bg">
                       
                        <p class="rocket-info__tickets-title">Target Departure from {!! $departure_location !!}</p>
                        <div class="row">                            
                            @foreach ($departure_data as $daparTwoWayKey => $daparTwoWayVal)
                                <div class="col-md-12 col-sm-12 mb-3">
                                    <div class="rocket-info__tickets--inner <?= ($passengerCount <= ($daparTwoWayVal['i_num_available'] - $daparTwoWayVal['i_num_booked_seats']) ? 'available-bg' : 'red-bg')?>">
                                        <div class="tickets-name">
                                            {!! $daparTwoWayVal['vehicle_fleet']['get_vehicle_specification']['v_make']!!} {!! $daparTwoWayVal['vehicle_fleet']['get_vehicle_specification']['v_model']!!} {!! $daparTwoWayVal['vehicle_fleet']['get_vehicle_specification']['v_series']!!}
                                        </div>
                                        <div class="row no-gutters">
                                            <div class="col-md-3 col-lg-3 col-sm-6 col-6">
                                                <span class="tickets-one">Date</span>
                                                <span class="tickets-two">@if($daparTwoWayVal['d_run_date'] != ''){!! date(DATE_FORMAT , strtotime($daparTwoWayVal['d_run_date'])) !!}@endif</span>
                                            </div>
                                            <div class="col-md-3 col-lg-3 col-sm-6 col-6">
                                                <span class="tickets-one">Time</span>
                                                <span class="tickets-two"> @if($daparTwoWayVal['t_scheduled_arr_time'] != ''){!! date('g:i a' , strtotime($daparTwoWayVal['t_scheduled_arr_time'])) !!}@endif</span>
                                            </div>
                                            <div class="col-md-3 col-lg-3 col-sm-6 col-6">
                                                <span class="tickets-one">Van ID</span>
                                                <span class="tickets-two">@if($daparTwoWayVal['vehicle_fleet'] != ''){!! $daparTwoWayVal['vehicle_fleet']['v_vehicle_code'] !!}@endif</span>
                                            </div>
                                            <div class="col-md-3 col-lg-3 col-sm-6 col-6">
                                                <span class="tickets-one">Booked / No. Seat</span>
                                                <span class="tickets-two">{{ $daparTwoWayVal['i_num_booked_seats'] }} / {!! $daparTwoWayVal['i_num_available']!!}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                @elseif(count($return_data) > 0)
                    <div class="col-md-12 col-12 rocket-info__tickets--bg">
                        <p class="rocket-info__tickets-title">Target Arrival at {!! $return_location !!}</p>
                        <div class="row">
                            @foreach ($return_data as $returnTwoWayKey => $returnTwoWayVal)
                                
                                <div class="col-md-12 col-sm-12 mb-3">
                                    <div class="rocket-info__tickets--inner <?= ($passengerCount <= ($returnTwoWayVal['i_num_available'] - $returnTwoWayVal['i_num_booked_seats']) ? 'available-bg' : 'red-bg')?>">
                                        <div class="tickets-name">
                                            {!! $returnTwoWayVal['vehicle_fleet']['get_vehicle_specification']['v_make']!!} {!! $returnTwoWayVal['vehicle_fleet']['get_vehicle_specification']['v_model']!!} {!! $returnTwoWayVal['vehicle_fleet']['get_vehicle_specification']['v_series']!!}
                                        </div>
                                        <div class="row no-gutters">
                                            <div class="col-md-3 col-lg-3 col-sm-6 col-6">
                                                <span class="tickets-one">Date</span>
                                                <span class="tickets-two">@if($returnTwoWayVal['d_run_date'] != ''){!! date(DATE_FORMAT , strtotime($returnTwoWayVal['d_run_date'])) !!}@endif</span>
                                            </div>
                                            <div class="col-md-3 col-lg-3 col-sm-6 col-6">
                                                <span class="tickets-one">Time</span>
                                                <span class="tickets-two"> @if($returnTwoWayVal['t_scheduled_arr_time'] != ''){!! date('g:i a' , strtotime($returnTwoWayVal['t_scheduled_arr_time'])) !!}@endif</span>
                                            </div>
                                            <div class="col-md-3 col-lg-3 col-sm-6 col-6">
                                                <span class="tickets-one">Van ID</span>
                                                <span class="tickets-two">@if($returnTwoWayVal['vehicle_fleet'] != ''){!! $returnTwoWayVal['vehicle_fleet']['v_vehicle_code'] !!}@endif</span>
                                            </div>
                                            <div class="col-md-3 col-lg-3 col-sm-6 col-6">
                                                <span class="tickets-one">Booked / No. Seat</span>
                                                <span class="tickets-two">{{ $returnTwoWayVal['i_num_booked_seats'] }} / {!! $returnTwoWayVal['i_num_available']!!}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="col-md-12 col-12 rocket-info__tickets--bg">
                        <div class="alert alert-danger" role="alert">
                            <p class="rocket-info__tickets-title">No line run found.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
   
@else
    <div class="rocket-info__two" id="show_line_run">
        <div class="rocket-info__tickets" >
            <div class="row">
            @if($departAvailable == 0 && count($departure_data) > 0) 
                <div class="col-md-12 col-12 rocket-info__tickets--bg">
                    <div class="alert alert-danger" role="alert">
                        Shuttles in your desired Travel Window are currently full; there is no available seat to book.
                    </div>
                </div>
            @endif
            @if(count($dateNotice) > 0)
                <div class="col-md-12 col-12 rocket-info__tickets--bg">
                    <div class="alert alert-info" role="alert">
                    @foreach($dateNotice as $dk => $date)
                        <p>{!! date('m/d/Y', strtotime($date['d_blackout_date'])) !!} - {!! $date['v_date_desc'] !!}</p>
                    @endforeach
                    </div>
                </div>
            @endif
            @if(count($departure_data) > 0)
                <div class="col-md-12 col-12 rocket-info__tickets--bg">
                    <p class="rocket-info__tickets-title">Target Departure from {!! $departure_location !!}</p>
                    <div class="row">
                    
                        @foreach ($departure_data as $oneWayKey => $oneWayVal)
                        
                            <div class="col-md-12 col-sm-12 mb-3">
                                <div class="rocket-info__tickets--inner <?= ($passengerCount <= ($oneWayVal['i_num_available'] - $oneWayVal['i_num_booked_seats']) ? 'available-bg' : 'red-bg')?>">
                                    <div class="tickets-name">
                                        {!! $oneWayVal['vehicle_fleet']['get_vehicle_specification']['v_make']!!} {!! $oneWayVal['vehicle_fleet']['get_vehicle_specification']['v_model']!!} {!! $oneWayVal['vehicle_fleet']['get_vehicle_specification']['v_series']!!}
                                    </div>
                                    <div class="row no-gutters">
                                        <div class="col-md-3 col-lg-3 col-sm-6 col-6">
                                            <span class="tickets-one">Date</span>
                                            <span class="tickets-two">@if($oneWayVal['d_run_date'] != ''){!! date(DATE_FORMAT , strtotime($oneWayVal['d_run_date'])) !!}@endif</span>
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-6 col-6">
                                            <span class="tickets-one">Time</span>
                                            <span class="tickets-two">@if($oneWayVal['t_scheduled_arr_time'] != ''){!! date('g:i a' , strtotime($oneWayVal['t_scheduled_arr_time'])) !!}@endif</span>
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-6 col-6">
                                            <span class="tickets-one">Van ID</span>
                                            <span class="tickets-two">@if($oneWayVal['vehicle_fleet'] != ''){!! $oneWayVal['vehicle_fleet']['v_vehicle_code'] !!}@endif</span>
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-6 col-6">
                                            <span class="tickets-one">Booked / No. Seat</span>
                                            <span class="tickets-two">{{ $oneWayVal['i_num_booked_seats'] }} / {!! $oneWayVal['i_num_available']!!}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="col-md-12 col-12 rocket-info__tickets--bg">
                    <div class="alert alert-danger" role="alert">
                        <p class="rocket-info__tickets-title">No line run found.</p>
                    </div>
                </div>
            @endif
                
            </div>
        </div>
    </div>
@endif

<script>
    var departure_count = '{!! ($departAvailable) !!}';
    var return_count = '{!! ($returnAvailable) !!}';
    var paymentStatus = '{!! $paymentStatus !!}';
    var type_of_trip = '{!! $type_of_trip !!}';
    
    $(document).ready(function() {
        $('.btnNext').removeClass('d-none');
        $('#departure_data_count').val(departure_count);
        $('#return_data_count').val(return_count);
        $('#passenger-information-tab').removeClass('disable');
        if(departure_count == 0 && return_count == 0) {
            $('.btnNext').addClass('d-none');
            $('#passenger-information-tab').addClass('disable');
        } else if(departure_count == 0) {
            if(paymentStatus) {
                $('.btnNext').addClass('d-none');
                $('#passenger-information-tab').addClass('disable');
            } else {
                $('#continue_process').val('No');
            }
            
            /* setTimeout(() => {
                $('#type_of_trip').val('OW');
                var from = $('#select_linerun_from').val();
                var to = $('#select_linerun_to').val();
                $('#select_linerun_from').val(to).trigger('change').after(function() {
                    setTimeout(() => {
                        $('#select_linerun_to').val(from).trigger('change');
                    }, 500);
                });
                $('.date_picker_depart').datepicker('setDate', new Date($('.date_picker_return').val()));
                $('.date_picker_return').val('')
                $('#type_of_trip').trigger('change');  
            }, 100); */
        } else if(return_count == 0 && type_of_trip == 'RT') {
            if(paymentStatus) {
                $('.btnNext').addClass('d-none');
                $('#passenger-information-tab').addClass('disable');
            } else {
                $('#continue_process').val('No');
            }
            /* $('#type_of_trip').val('OW');
            $('#type_of_trip').trigger('change'); */
        }
        
        var divCount = $('#show_line_run .rocket-info__tickets .row .rocket-info__tickets--bg').length;
        if(divCount == 2) {
            var leftHeight = $('#show_line_run .rocket-info__tickets .row .rocket-info__tickets--bg:first-child .rocket-info__tickets-title').height();
            var rightHeight = $('#show_line_run .rocket-info__tickets .row .rocket-info__tickets--bg:nth-child(2) .rocket-info__tickets-title').height();
            if(leftHeight > rightHeight) {
                $('#show_line_run .rocket-info__tickets .row .rocket-info__tickets--bg:nth-child(2) .rocket-info__tickets-title').css("height", (leftHeight+10) + "px");
            } else {
                $('#show_line_run .rocket-info__tickets .row .rocket-info__tickets--bg:first-child .rocket-info__tickets-title').css("height", (rightHeight+10) + "px");
            }
        }
    })
</script>
<!-- <div class="rocket-info__two d-none" id="no_line_run">
    <div class="rocket-info__tickets" >
        <div class="row" >
            <div class="col-md-6 col-12 rocket-info__tickets--bg">
                <p class="rocket-info__tickets-title">No line run found</p>
            </div>
        </div>
    </div>
</div> -->
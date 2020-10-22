
@if($type_of_trip == "RT")
    <div class="rocket-info__two" id="show_line_run">
        <div class="rocket-info__tickets" >
            <div class="row">
            @if($departAvailable == 0 && $returnAvailable == 0)
                <div class="col-md-12 col-12 rocket-info__tickets--bg">
                    <div class="alert alert-danger" role="alert">
                        <p>Shuttles in your desired Travel Window are currently full; there is no available seat to book.</p>
                        <p>Please select an option below:</p>
                        <ul class="custom-list">
                            <li>Modify your travel plans to work within the available seats. In order to check for available seats, please click here: <a href="{{ ((request()->route('id') != '') ? SITE_URL.'display-line-runs/'.request()->route('id') : SITE_URL.'display-line-runs') }}">Display Line Runs</a></li>
                            @if($paymentStatus)
                                <li>If your plans are not able to be changed, we will put your ticket on <strong>Hold</strong>. If there will availability within your desired Travel Window, we will contact you with our findings.</li>
                            @else
                                <li>If your plans are not able to be changed, <a href="javascript:;" class="call_request_btn">  Click here </a> to send a request for the Dispatcher to check availability within your desired Travel Window. We will contact you with our findings.</li>
                                <script>
                                    $(document).ready(function() {
                                        $('.btnNext').addClass('d-none');
                                    });
                                </script>
                            @endif
                        </ul>
                    </div>
                </div>
            @elseif($departAvailable == 0)
                <div class="col-md-12 col-12 rocket-info__tickets--bg">
                    <div class="alert alert-danger" role="alert">
                        <p>Shuttles in your desired Travel Window for Leg 1 (departure) are currently full; there is no available seat to book.</p>
                        <p>Please select an option below: </p>
                        <ul class="custom-list">
                            <li>Modify your travel plans to work within the available seats. In order to check for available seats, please click here: <a href="{{ ((request()->route('id') != '') ? SITE_URL.'display-line-runs/'.request()->route('id') : SITE_URL.'display-line-runs') }}">Display Shuttles</a></li>
                            @if($paymentStatus)
                                <li>If your plans are not able to be changed, we will put your ticket for Leg 1 (departure) on <strong>Hold</strong>. If there will availability within your desired Travel Window, we will contact you with our findings.</li>
                            @else
                                <li>If you wish to continue, system will consider it as One Way trip.</li>
                            @endif
                        </ul>
                    </div>
                </div>
            @elseif($returnAvailable == 0)
                <div class="col-md-12 col-12 rocket-info__tickets--bg">
                    <div class="alert alert-danger" role="alert">
                        <p>Shuttles in your desired Travel Window for Leg 2 (return) are currently full; there is no available seat to book.</p>
                        <p>Please select an option below: </p>
                        <ul class="custom-list">
                            <li>Modify your travel plans to work within the available seats. In order to check for available seats, please click here: <a href="{{ ((request()->route('id') != '') ? SITE_URL.'display-line-runs/'.request()->route('id') : SITE_URL.'display-line-runs') }}">Display Shuttles</a></li>
                            @if($paymentStatus)
                                <li>If your plans are not able to be changed, we will put your ticket for Leg 2 (return) on <strong>Hold</strong>. If there will availability within your desired Travel Window, we will contact you with our findings.</li>
                            @else
                                <li>If you wish to continue, system will consider it as One Way trip.</li>
                            @endif
                        </ul>                        
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

            @if(count($departure_data) > 0 && count($return_data) > 0)
                <div class="col-md-6 col-12 rocket-info__tickets--bg">
                    <p class="rocket-info__tickets-title">Target Departure from {!! $departure_location !!}</p>
                    <div class="row">
                        <input type="hidden" name="i_run_id" class="departure_data" value="{{$departure_data[0]['id']}}">
                        @foreach ($departure_data as $daparTwoWayKey => $daparTwoWayVal)
                            <div class="col-md-12 col-sm-6 mb-3">
                                <div class="rocket-info__tickets--inner confirm_line_run <?= ($departPassengerCount <= ($daparTwoWayVal['i_num_available'] - $daparTwoWayVal['i_num_booked_seats']) ? 'available-bg' : 'red-bg')?>" rel="{{$daparTwoWayVal['id']}}">
                                    <div class="tickets-name">
                                        {!! $daparTwoWayVal['vehicle_fleet']['get_vehicle_specification']['v_make']!!} 
                                        {!! $daparTwoWayVal['vehicle_fleet']['get_vehicle_specification']['v_model']
                                        !!} 
                                        {!! $daparTwoWayVal['vehicle_fleet']['get_vehicle_specification']['v_series']!!}
                                    </div>
                                    <div class="row no-gutters">
                                        <div class="col-md-12 col-lg-6 col-sm-6 col-6 mb-2">
                                            <span class="tickets-one">Date</span>
                                            <span class="tickets-two">@if($daparTwoWayVal['d_run_date'] != '') {!! date(DATE_FORMAT , strtotime($daparTwoWayVal['d_run_date'])) !!} @endif</span>
                                        </div>
                                        <div class="col-md-12 col-lg-6 col-sm-6 col-6 mb-2">
                                            <span class="tickets-one">Time</span>
                                            <span class="tickets-two"> @if($daparTwoWayVal['t_scheduled_arr_time'] != '') {!! date('g:i a' , strtotime($daparTwoWayVal['t_scheduled_arr_time'])) !!} @endif</span>
                                        </div>
                                        <div class="col-md-12 col-lg-6 col-sm-6 col-6">
                                            <span class="tickets-one">Van ID</span>
                                            <span class="tickets-two">@if($daparTwoWayVal['vehicle_fleet'] != '') {!! $daparTwoWayVal['vehicle_fleet']['v_vehicle_code'] !!} @endif</span>
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
                        <input type="hidden" name="i_run_id_rt" class="return_data" value="{{$return_data[0]['id']}}">
                        @foreach ($return_data as $returnTwoWayKey => $returnTwoWayVal)
                            <div class="col-md-12 col-sm-6 mb-3">
                                <div class="rocket-info__tickets--inner confirm_line_run_rt <?= ($returnPassengerCount <= ($returnTwoWayVal['i_num_available'] - $returnTwoWayVal['i_num_booked_seats']) ? 'available-bg' : 'red-bg')?>" rel="{{$returnTwoWayVal['id']}}">
                                    <div class="tickets-name">
                                        {!! $returnTwoWayVal['vehicle_fleet']['get_vehicle_specification']['v_make']!!} 
                                        {!! $returnTwoWayVal['vehicle_fleet']['get_vehicle_specification']['v_model']!!} 
                                        {!! $returnTwoWayVal['vehicle_fleet']['get_vehicle_specification']['v_series']!!}
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

                <div class="col-md-6 col-12 text-right mt-3 mt-lg-0"></div>
                <div class="col-md-6 col-12 text-right mt-3 mt-lg-0">
                    <div class="price-box">
                                                            
                        <p class="mb-2"><span>Subtotal </span> <span><strong class="show_sub_total">{{ '$'.number_format($total_fare,2) }}</strong></span></p>
                        
                        @if($discountPrice > 0)
                            <p class="mb-2"><span>Discount </span> <span><strong class="show_discount">{{ '$'.number_format($discountPrice,2) }}</strong></span></p>
                            <p class="total-prices">
                                <span>Total Fare </span><span class="show_total_total">{{ '$'.number_format(($total_fare - $discountPrice),2) }}</span>
                            </p>
                        @else
                            <p class="total-prices">
                                <span>Total Fare </span><span class="show_total_total">{{ '$'.number_format($total_fare,2) }}</span>
                            </p>
                        @endif
                    </div>
                </div>
            @elseif(count($departure_data) > 0)
                <div class="col-md-12 col-12 rocket-info__tickets--bg">
                    <p class="rocket-info__tickets-title">Target Departure from {!! $departure_location !!}</p>
                    <div class="row">
                        <input type="hidden" name="i_run_id" class="departure_data" value="{{$departure_data[0]['id']}}" >
                        @foreach($departure_data as $daparTwoWayKey => $daparTwoWayVal)
                            <div class="col-md-12 col-sm-12 mb-3">
                                <div class="rocket-info__tickets--inner confirm_line_run <?= ($departPassengerCount <= ($daparTwoWayVal['i_num_available'] - $daparTwoWayVal['i_num_booked_seats']) ? 'available-bg' : 'red-bg')?>" rel="{{$daparTwoWayVal['id']}}">
                                    <div class="tickets-name">
                                        {!! $daparTwoWayVal['vehicle_fleet']['get_vehicle_specification']['v_make']!!} 
                                        {!! $daparTwoWayVal['vehicle_fleet']['get_vehicle_specification']['v_model']
                                        !!} 
                                        {!! $daparTwoWayVal['vehicle_fleet']['get_vehicle_specification']['v_series']!!}
                                    </div>
                                    <div class="row no-gutters">
                                        <div class="col-md-3 col-lg-3 col-sm-3 col-6">
                                            <span class="tickets-one">Date</span>
                                            <span class="tickets-two">@if($daparTwoWayVal['d_run_date'] != '') {!! date(DATE_FORMAT , strtotime($daparTwoWayVal['d_run_date'])) !!} @endif</span>
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-3 col-6">
                                            <span class="tickets-one">Time</span>
                                            <span class="tickets-two"> @if($daparTwoWayVal['t_scheduled_arr_time'] != '') {!! date('g:i a' , strtotime($daparTwoWayVal['t_scheduled_arr_time'])) !!} @endif</span>
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-3 col-6">
                                            <span class="tickets-one">Van ID</span>
                                            <span class="tickets-two">@if($daparTwoWayVal['vehicle_fleet'] != '') {!! $daparTwoWayVal['vehicle_fleet']['v_vehicle_code'] !!} @endif</span>
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-3 col-6">
                                            <span class="tickets-one">Booked / No. Seat</span>
                                            <span class="tickets-two">{{ $daparTwoWayVal['i_num_booked_seats'] }} / {!! $daparTwoWayVal['i_num_available']!!}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-6 col-12 text-right mt-3 mt-lg-0"></div>
                <div class="col-md-6 col-12 text-right mt-3 mt-lg-0">
                    <div class="price-box">
                                                            
                        <p class="mb-2"><span>Subtotal </span> <span><strong class="show_sub_total">{{ '$'.number_format($total_fare,2) }}</strong></span></p>
                        
                        @if($discountPrice > 0)
                            <p class="mb-2"><span>Discount </span> <span><strong class="show_discount">{{ '$'.number_format($discountPrice,2) }}</strong></span></p>
                            <p class="total-prices">
                                <span>Total Fare </span><span class="show_total_total">{{ '$'.number_format(($total_fare - $discountPrice),2) }}</span>
                            </p>
                        @else
                            <p class="total-prices">
                                <span>Total Fare </span><span class="show_total_total">{{ '$'.number_format($total_fare,2) }}</span>
                            </p>
                        @endif
                    </div>
                </div>                   
            @elseif(count($return_data) > 0)
                <div class="col-md-12 col-6 col-12 rocket-info__tickets--bg">
                    <p class="rocket-info__tickets-title">Target Arrival at {!! $return_location !!}</p>
                    <div class="row">
                        <input type="hidden" name="i_run_id_rt" class="return_data" value="{{$return_data[0]['id']}}">
                        @foreach ($return_data as $returnTwoWayKey => $returnTwoWayVal)
                            <div class="col-md-12 col-sm-12 mb-3">
                                <div class="rocket-info__tickets--inner confirm_line_run_rt <?= ($returnPassengerCount <= ($returnTwoWayVal['i_num_available'] - $returnTwoWayVal['i_num_booked_seats']) ? 'available-bg' : 'red-bg')?>" rel="{{$returnTwoWayVal['id']}}">
                                    <div class="tickets-name">
                                        {!! $returnTwoWayVal['vehicle_fleet']['get_vehicle_specification']['v_make']!!} 
                                        {!! $returnTwoWayVal['vehicle_fleet']['get_vehicle_specification']['v_model']!!} 
                                        {!! $returnTwoWayVal['vehicle_fleet']['get_vehicle_specification']['v_series']!!}
                                    </div>
                                    <div class="row no-gutters">
                                        <div class="col-md-3 col-lg-3 col-sm-3 col-6">
                                            <span class="tickets-one">Date</span>
                                            <span class="tickets-two">@if($returnTwoWayVal['d_run_date'] != ''){!! date(DATE_FORMAT , strtotime($returnTwoWayVal['d_run_date'])) !!}@endif</span>
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-3 col-6">
                                            <span class="tickets-one">Time</span>
                                            <span class="tickets-two"> @if($returnTwoWayVal['t_scheduled_arr_time'] != ''){!! date('g:i a' , strtotime($returnTwoWayVal['t_scheduled_arr_time'])) !!}@endif</span>
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-3 col-6">
                                            <span class="tickets-one">Van ID</span>
                                            <span class="tickets-two">@if($returnTwoWayVal['vehicle_fleet'] != ''){!! $returnTwoWayVal['vehicle_fleet']['v_vehicle_code'] !!}@endif</span>
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-3 col-6">
                                            <span class="tickets-one">Booked / No. Seat</span>
                                            <span class="tickets-two">{{ $returnTwoWayVal['i_num_booked_seats'] }} / {!! $returnTwoWayVal['i_num_available']!!}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-6 col-12 text-right mt-3 mt-lg-0"></div>
                <div class="col-md-6 col-12 text-right mt-3 mt-lg-0">
                    <div class="price-box">
                                                            
                        <p class="mb-2"><span>Subtotal </span> <span><strong class="show_sub_total">{{ '$'.number_format($total_fare,2) }}</strong></span></p>
                        
                        @if($discountPrice > 0)
                            <p class="mb-2"><span>Discount </span> <span><strong class="show_discount">{{ '$'.number_format($discountPrice,2) }}</strong></span></p>
                            <p class="total-prices">
                                <span>Total Fare </span><span class="show_total_total">{{ '$'.number_format(($total_fare - $discountPrice),2) }}</span>
                            </p>
                        @else
                            <p class="total-prices">
                                <span>Total Fare </span><span class="show_total_total">{{ '$'.number_format($total_fare,2) }}</span>
                            </p>
                        @endif
                    </div>
                </div>
            @endif
            </div>
        </div>
    </div>
   
     
@elseif($type_of_trip == 'OW')

<div class="rocket-info__two" id="show_line_run">
    <div class="rocket-info__tickets" >
        <div class="row">
        @if($departAvailable == 0)
            <div class="col-md-12 col-12 rocket-info__tickets--bg">
                <div class="alert alert-danger" role="alert">
                    <p>Shuttles in your desired Travel Window are currently full; there is no available seat to book.</p>
                    <p>Please select an option below:</p>
                    <ul class="custom-list">
                        <li>Modify your travel plans to work within the available seats. In order to check for available seats, please click here: <a href="{{ ((request()->route('id') != '') ? SITE_URL.'display-line-runs/'.request()->route('id') : SITE_URL.'display-line-runs') }}">Display Line Runs</a></li>
                        @if($paymentStatus)
                            <li>If your plans are not able to be changed, we will put your ticket on <strong>Hold</strong>. If there will availability within your desired Travel Window, we will contact you with our findings.</li>
                        @else
                            <li>If your plans are not able to be changed, <a href="javascript:;" class="call_request_btn"> Click here </a> to send a request for the Dispatcher to check availability within your desired Travel Window. We will contact you with our findings.</li>
                            <script>
                                $(document).ready(function() {
                                    $('.btnNext').addClass('d-none');
                                });
                            </script>
                        @endif
                    </ul>
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
                    <input type="hidden" name="i_run_id" class="departure_data" value="{{$departure_data[0]['id']}}" >
                    @foreach($departure_data as $daparTwoWayKey => $daparTwoWayVal)
                        <div class="col-md-12 col-sm-12 mb-3">
                            <div class="rocket-info__tickets--inner confirm_line_run <?= ($departPassengerCount <= ($daparTwoWayVal['i_num_available'] - $daparTwoWayVal['i_num_booked_seats']) ? 'available-bg' : 'red-bg')?>" rel="{{$daparTwoWayVal['id']}}">
                                <div class="tickets-name">
                                    {!! $daparTwoWayVal['vehicle_fleet']['get_vehicle_specification']['v_make']!!} {!! $daparTwoWayVal['vehicle_fleet']['get_vehicle_specification']['v_model']!!} {!! $daparTwoWayVal['vehicle_fleet']['get_vehicle_specification']['v_series']!!}
                                </div>
                                <div class="row no-gutters">
                                    <div class="col-md-3 col-lg-3 col-sm-3 col-6">
                                        <span class="tickets-one">Date</span>
                                        <span class="tickets-two">@if($daparTwoWayVal['d_run_date'] != ''){!! date(DATE_FORMAT , strtotime($daparTwoWayVal['d_run_date'])) !!}@endif</span>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-3 col-6">
                                        <span class="tickets-one">Time</span>
                                        <span class="tickets-two">@if($daparTwoWayVal['t_scheduled_arr_time'] != ''){!! date('g:i a' , strtotime($daparTwoWayVal['t_scheduled_arr_time'])) !!}@endif</span>
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
            <div class="col-md-6 col-12 text-right mt-3 mt-lg-0"></div>
            <div class="col-md-6 col-12 text-right mt-3 mt-lg-0">
                <div class="price-box">
                                                        
                    <p class="mb-2"><span>Subtotal </span> <span><strong class="show_sub_total">{{ '$'.number_format($total_fare,2) }}</strong></span></p>
                    
                    @if($discountPrice > 0)
                        <p class="mb-2"><span>Discount </span> <span><strong class="show_discount">{{ '$'.number_format($discountPrice,2) }}</strong></span></p>
                        <p class="total-prices">
                            <span>Total Fare </span><span class="show_total_total">{{ '$'.number_format(($total_fare - $discountPrice),2) }}</span>
                        </p>
                    @else
                        <p class="total-prices">
                            <span>Total Fare </span><span class="show_total_total">{{ '$'.number_format($total_fare,2) }}</span>
                        </p>
                    @endif
                </div>
            </div>
        @endif
            
        </div>
    </div>
</div>
@endif
<script>
    $(document).ready(function() {
        $('.confirm_line_run.available-bg:first').addClass('active');
        $('.departure_data').val($('.confirm_line_run.available-bg:first').attr('rel'));
        if($('.confirm_line_run_rt').length > 0) {
            $('.confirm_line_run_rt.available-bg:first').addClass('active');
            $('.return_data').val($('.confirm_line_run_rt.available-bg:first').attr('rel'));
        }
    });
</script>
<!-- <div class="rocket-info__two d-none" id="no_line_run">
    <div class="rocket-info__tickets" >
        <div class="row" >
            <div class="col-md-6 col-12 rocket-info__tickets--bg">
                <p class="rocket-info__tickets-title">No line run found.</p>
            </div>
        </div>
    </div>
</div> -->
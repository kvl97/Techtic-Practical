@extends('frontend.layouts.default')
@section('content')

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
                                
                                <a class="nav-link completed {{ (isset($reservation_record) && $reservation_record['e_shuttle_type']=='Private') ? 'd-none' : '' }}" id="runs-tab" href="javascript:;" role="tab" aria-controls="runs" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'display-line-runs/'.request()->route('id') : 'display-line-runs') }}">Display Line Runs</a>
                                
                                <a class="nav-link completed" id="passenger-information-tab" href="javascript:;" role="tab" aria-controls="passenger-information" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'passenger-information/'.request()->route('id') : 'passenger-information') }}">Passenger Information</a>
                                
                                <a class="nav-link completed" id="luggage-animals-tab" href="javascript:;" role="tab" aria-controls="luggage-animals" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'luggage-animals/'.request()->route('id') : 'luggage-animals') }}">Luggage and Animals</a>

                                <a class="nav-link completed" id="travel-tab" href="javascript:;" role="tab" aria-controls="travel" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'travel-details/'.request()->route('id') : 'travel-details') }}">Travel Details</a>
                                
                                <a class="nav-link completed {{ (isset($reservation_record) && $reservation_record['e_shuttle_type']=='Private') ? 'd-none' : '' }}" id="confirm-tab" href="javascript:;" role="tab" aria-controls="confirm" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'currently-assigned-line-runs/'.request()->route('id') : 'currently-assigned-line-runs') }}">Currently Assigned Line Run</a>
                                
                                <a class="nav-link active" id="reservation-summary-tab" href="javascript:;" role="tab" aria-controls="reservation-summary" aria-selected="true" data-action="{{ ((request()->route('id') != '') ? 'reservation-summary/'.request()->route('id') : 'reservation-summary') }}">Reservation Summary</a>

                                <a class="nav-link {{ (isset($reservation_record) && $reservation_record['e_shuttle_type']=='Private') ? 'd-none' : '' }}" id="payment-tab" href="javascript:;" role="tab" aria-controls="payment" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'reservation-payment/'.request()->route('id') : 'reservation-payment') }}">Payment</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="rocket-info-right">
                            <div class="tab-content" id="v-tabContent">
                                <!-- 7 -->
                                <div class="tab-pane fade show active" id="reservation-summary" role="tabpanel" aria-labelledby="settings-tab">
                                    
                                    <form action="{{ ((request()->route('id') != '') ? SITE_URL.'reservation-summary/'.request()->route('id') : SITE_URL.'reservation-summary') }}" class="rocket-info-details rocket-info-reservation-summary">
                                    <input type="hidden" name="redirect_url" value="{{ ((request()->route('id') != '') ? 'reservation-payment/'.request()->route('id') : 'reservation-payment') }}" id="redirect_url">
                                    <h3 class="rocket-info__title">Reservation Summary</h3>
                                    
                                        <div class="rocket-info__one">
                                            <div class="row align-items-center">
                                                <div class="col-lg-6">
                                                    @if($reservation_record['e_class_type']=='OW')
                                                    <p class="reservation-code py-2 px-4"><strong>Reservation No. : </strong><span>@if(isset($reservation_record['v_reservation_number'])) {!! $reservation_record['v_reservation_number']!!}@endif</span></p>
                                                    @endif
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="rocket-info__two">
                                            <div class="row">
                                                <div class="col-xl-6 col-md-12">
                                                    <div class="reservation-summary-block mb-xl-20 mb-10">
                                                        <div class="summary-block__title py-3 pl-20 pr-20">
                                                            <p>Main Traveler</p>
                                                        </div>
                                                        <div class="main-traveler p-20">
                                                            <ul>
                                                                <li><span>Name</span><span>@if(isset($reservation_record['v_contact_name'])) {!! $reservation_record['v_contact_name']!!}@endif</span></li>
                                                                <li><span>Email</span><span>@if(isset($reservation_record['v_contact_email'])) {!! $reservation_record['v_contact_email'] !!}@endif</span></li>
                                                                <li><span>Phone</span><span>@if(isset($reservation_record['v_contact_phone_number'])) {!! $reservation_record['v_contact_phone_number'] !!}@endif</span></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="reservation-summary-block flex-wrap flex-sm-nowrap right-third d-flex align-items-center mb-10">
                                                                <div class="summary-block__title w-xs-50 w-100 py-3 pl-20 pr-20">
                                                                    <p>Total Traveler</p>
                                                                </div>
                                                                <div class="main-traveler pl-20 pr-20 w-xs-50 w-100 py-3">
                                                                    <p>@if(isset($reservation_record['i_total_num_passengers'])) {!! $reservation_record['i_total_num_passengers'] !!}@endif</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="reservation-summary-block flex-wrap flex-sm-nowrap right-third d-flex align-items-center mb-10">
                                                                <div class="summary-block__title w-xs-50 w-100 py-3 pl-20 pr-20">
                                                                    <p>booked trip</p>
                                                                </div>
                                                                <div class="main-traveler pl-20 pr-20 w-xs-50 w-100 py-3">
                                                                    <p>@if(isset($reservation_record['e_class_type']) && $reservation_record['e_class_type'] =="OW")  {{'One Way'}}

                                                                    @elseif(isset($reservation_record['e_class_type']) && $reservation_record['e_class_type'] =="RT") 
                                                                    {{'Round Trip'}}
                                                                    @else

                                                                    @endif</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="reservation-summary-block flex-wrap flex-sm-nowrap right-third d-flex align-items-center mb-10">
                                                                <div class="summary-block__title w-xs-50 w-100 py-3 pl-20 pr-20">
                                                                    <p>TOTAL FARE</p>
                                                                </div>
                                                                <div class="main-traveler pl-20 pr-20 w-xs-50 w-100 py-3">
                                                                        <p>
                                                                        @if($reservation_record['e_shuttle_type']=='Shared')
                                                                            <?= '$'.number_format((float)$total_payment, 2, '.', '') ?>
                                                                        @else
                                                                            {{'-'}}    
                                                                        @endif
                                                                        </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="reservation-summary-block mb-20">
                                                <div class="summary-block__title py-3 pl-20 pr-20">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            @if($reservation_record['e_class_type']=='OW')
                                                            <p>Travel Details</p>
                                                            @else 
                                                            <!-- <p>1st Leg Reservation: {{ $reservation_record['v_reservation_number'] }}</p> -->
                                                            <span class="reservation-code py-2 px-4"><strong class="d-sm-none d-md-none d-lg-inline">1st Leg of Travel: </strong><span>{{ $reservation_record['v_reservation_number'] }}</span></span>
                                                            @endif
                                                        </div>
                                                        <div class="col-sm-6 text-sm-right">
                                                            <p class="date"> Date: <span> @if(isset($reservation_record['d_travel_date'])) {!! date(DATE_FORMAT,strtotime($reservation_record['d_travel_date'])) !!}@else {{'-'}} @endif </span></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="leg-traveler-inner-wrap py-3 pl-20 pr-20">
                                                    <div class="leg-traveler-inner">
                                                       
                                                        <div class="main-traveler">
                                                            <ul>
                                                                <li><span style="width:55% !important">To arrive at pickup location between </span><span style="width:45% !important">{{$reservation_record['t_comfortable_time'] ?  date('H:i' , strtotime($reservation_record['t_comfortable_time']))   : '-' }} ({{ $reservation_record['t_comfortable_time'] ? date('g:i A' , strtotime($reservation_record['t_comfortable_time'])) : '-'}}) {{$reservation_record['t_target_time'] ?  'and '.date('H:i' , strtotime($reservation_record['t_target_time']))   : '' }} {{ $reservation_record['t_target_time'] ? '('.date('g:i A' , strtotime($reservation_record['t_target_time'])).')' : ''}}</span></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="row no-gutters">
                                                        <div class="col-xl-6 col-md-12 pr-xl-2">
                                                            <div class="main-traveler main-traveler-listing p-20 h-100">
                                                                <p class="main-traveler--title mb-0">Pickup Location</p>
                                                               
                                                                <span class="main-traveler--subtitle d-block mb-20">{{ $reservation_record['PickupCity']['v_city'].' ('.$reservation_record['PickupCity']['v_county'].')' }}</span>
                                                               
                                                                <ul>
                                                                    <li><span>Address</span><span>{{ $reservation_record['v_pickup_address']  ? $reservation_record['v_pickup_address'] :  '-'}}</span></li>
                                                                    <li><span>City</span><span> {{$reservation_record['PickupCity']['v_city'] ? $reservation_record['PickupCity']['v_city'] : '-' }}</span></li>
                                                                    <li><span>County</span><span>{{$reservation_record['PickupCity']['v_county'] ? $reservation_record['PickupCity']['v_county'] : '-' }}{{ $reservation_record['GeoOriginServiceArea']['v_postal_code'] ? '- '.$reservation_record['GeoOriginServiceArea']['v_postal_code'] : '' }}</span></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-6 col-md-12 pl-xl-2 mt-3 mt-xl-0">
                                                            <div class="main-traveler main-traveler-listing p-20 h-100">
                                                                <p class="main-traveler--title mb-0">Drop Off Location</p>
                                                                
                                                                    <span class="main-traveler--subtitle d-block mb-20">{{ $reservation_record['DropOffCity']['v_city'].' ('.$reservation_record['DropOffCity']['v_county'].')' }}</span>
                                                               
                                                                <ul>
                                                                    <li><span>Address</span><span>{{ $reservation_record['v_dropoff_address']  ? $reservation_record['v_dropoff_address'] :  '-'}}</span></li>
                                                                    <li><span>City</span><span>{{$reservation_record['DropOffCity']['v_city'] ? $reservation_record['DropOffCity']['v_city'] : '-' }}</span></li>
                                                                    <li><span>County</span><span>{{$reservation_record['DropOffCity']['v_county'] ? $reservation_record['DropOffCity']['v_county'] : '-' }}</span></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="arrival-flight mt-10 mb-1">
                                                        <p class="mb-0"><strong>{{ ($res1_tt_text) ? $res1_tt_text['direction'] : '' }} Flight Details</strong></p>
                                                    </div>
                                                    <div class="custom-dots">
                                                        <ul>
                                                            @if(isset($reservation_record['e_flight_type']))
                                                                <li><span>Flight Type</span><span>:  {!! $reservation_record['e_flight_type'] !!} </span></li>
                                                            @endif
                                                            @if(isset($reservation_record['v_flight_name'])) 
                                                                <li><span>Airline</span><span>: {!! $reservation_record['v_flight_name'] !!}</span></li>
                                                            @endif
                                                            @if(isset($reservation_record['v_flight_number']))
                                                                <li><span>Flight #: </span><span> {!! $reservation_record['v_flight_number'] !!}</span></li>
                                                            @endif 
                                                            @if(isset($reservation_record['t_flight_time'])) 
                                                                <li><span>Flight Time: </span><span>{{ $reservation_record['t_flight_time'] ? date('g:i A' , strtotime($reservation_record['t_flight_time'])) : '-'}}</span></li>
                                                                
                                                            @endif 
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            @if(isset($reservation_record_rt['e_class_type']) && $reservation_record_rt['e_class_type'] =="RT") 
                                                <div class="reservation-summary-block mb-20">
                                                    <div class="summary-block__title py-3 pl-20 pr-20">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                
                                                            <span class="reservation-code py-2 px-4"><strong class="d-sm-none d-md-none d-lg-inline">2nd Leg Of Travel: </strong><span>{{ $reservation_record_rt['v_reservation_number'] }}</span></span>
                                                                
                                                                <!-- <p>2nd Leg of Travel</p> -->
                                                            </div>
                                                            <div class="col-sm-6 text-sm-right">
                                                                <p class="date"> Date: <span> @if(isset($reservation_record_rt['d_travel_date'])) {!! date(DATE_FORMAT,strtotime($reservation_record_rt['d_travel_date'])) !!}@else {{'-'}} @endif </span></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="leg-traveler-inner-wrap py-3 pl-20 pr-20">
                                                    <div class="leg-traveler-inner">
                                                        
                                                        <div class="main-traveler">
                                                            <ul>
                                                                <li><span style="width:55% !important">To arrive at pickup location between</span><span style="width:45% !important">{{$reservation_record_rt['t_comfortable_time'] ?  date('H:i' , strtotime($reservation_record_rt['t_comfortable_time']))   : '-' }} ({{ $reservation_record_rt['t_comfortable_time'] ? date('g:i A' , strtotime($reservation_record_rt['t_comfortable_time'])) : '-'}}) {{$reservation_record_rt['t_target_time'] ?  'and '.date('H:i' , strtotime($reservation_record_rt['t_target_time']))   : '' }} {{ $reservation_record_rt['t_target_time'] ? '('.date('g:i A' , strtotime($reservation_record_rt['t_target_time'])).')' : ''}}</span></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row no-gutters">
                                                        <div class="col-xl-6 col-md-12 pr-xl-2">
                                                            <div class="main-traveler main-traveler-listing p-20 h-100">
                                                                <p class="main-traveler--title mb-0">Pickup Location</p>
                                                                
                                                                    <span class="main-traveler--subtitle d-block mb-20">{{ $reservation_record_rt['PickupCity']['v_city'].' ('.$reservation_record_rt['PickupCity']['v_county'].')' }}</span>
                                                                <ul>
                                                                    <li><span>Address</span><span>{{ $reservation_record_rt['v_pickup_address']  ? $reservation_record_rt['v_pickup_address'] :  '-'}}</span></li>
                                                                    <li><span>City</span><span>{{$reservation_record_rt['PickupCity']['v_city'] ? $reservation_record_rt['PickupCity']['v_city'] : '-' }}</span></li>
                                                                    <li><span>County</span><span>{{$reservation_record_rt['PickupCity']['v_county'] ? $reservation_record_rt['PickupCity']['v_county'] : '-' }}</span></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-6 col-md-12 pl-xl-2 mt-3 mt-xl-0">
                                                            <div class="main-traveler main-traveler-listing p-20 h-100">
                                                                <p class="main-traveler--title mb-0">Drop Off Location</p>
                                                                    <span class="main-traveler--subtitle d-block mb-20">{{ $reservation_record_rt['DropOffCity']['v_city'].' ('.$reservation_record_rt['DropOffCity']['v_county'].')' }}</span>
                                                                <ul>
                                                                    <li><span>Address</span><span>{{ $reservation_record_rt['v_dropoff_address']  ? $reservation_record_rt['v_dropoff_address'] :  '-'}}</span></li>
                                                                    <li><span>City</span><span>{{$reservation_record_rt['DropOffCity']['v_city'] ? $reservation_record_rt['DropOffCity']['v_city'] : '-' }}</span></li>
                                                                    <li><span>County</span><span>{{$reservation_record_rt['DropOffCity']['v_county'] ? $reservation_record_rt['DropOffCity']['v_county'] : '-' }}</span></li>
                                                                    
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="arrival-flight mt-10 mb-1">
                                                        <p class="mb-0"><strong>{{ ($res2_tt_text) ? $res2_tt_text['direction'] : '' }} Flight Details</strong></p>
                                                    </div>
                                                    <div class="custom-dots">
                                                        <ul>
                                                            @if(isset($reservation_record_rt['e_flight_type']))
                                                                <li><span>Flight Type</span><span>:  {!! $reservation_record_rt['e_flight_type'] !!} </span></li>
                                                            @endif
                                                            @if(isset($reservation_record_rt['v_flight_name']))
                                                                <li><span>Airline</span><span>:  {!! $reservation_record_rt['v_flight_name'] !!}</span></li>
                                                            @endif
                                                            @if(isset($reservation_record_rt['v_flight_number']))
                                                                <li><span>Flight #:</span><span> {!! $reservation_record_rt['v_flight_number'] !!}</span></li>
                                                            @endif
                                                            @if(isset($reservation_record_rt['t_flight_time']))
                                                            <li><span>Flight Time: </span><span> {{ $reservation_record_rt['t_flight_time'] ? date('g:i A' , strtotime($reservation_record_rt['t_flight_time'])) : '-'}}</span></li>
                                                            @endif 
                                                        </ul>
                                                    </div>
                                                </div>
                                                </div>
                                            @endif

                                            @if($reservation_record['e_shuttle_type']=='Shared')
                                            <div class="reservation-summary-block breakdown-wrap mb-20">
                                                <div class="summary-block__title py-3 pl-20 pr-20">
                                                    <p>breakdown of Charges</p>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="main-traveler pt-3 pl-3 pb-10 pr-10 pr-lg-0">
                                                            <ul>
                                                            
                                                            <?php if(!empty($total_fare_amount_rt) && ($total_fare_amount_rt['total'])){  
                                                                
                                                                    $total_rt_fare_amount = $total_fare_amount_rt['total']; 
                                                                    $total_fare_amounts_ow = $total_fare_amount['total'] ? $total_fare_amount['total'] : 0;
                                                                }else {
                                                                    
                                                                    $total_rt_fare_amount = 0.00;

                                                                    $total_fare_amounts_ow = $total_fare_amount['total'] ? $total_fare_amount['total'] : 0;
                                                                }
                                                                $total_fare_amounts_ow_rt = $total_rt_fare_amount + $total_fare_amounts_ow;

                                                                $total_fare_amount_rt_ow = number_format((float)$total_fare_amounts_ow_rt, 2, '.', '')
                                                                ?>
                                                            
                                                                <li><span>Fares</span><span>${{$total_fare_amount_rt_ow}}</span></li>
                                                                <li><span>Mode of Payment</span><span>{{ ($paymentMode) ? $paymentMode : ' - ' }}</span></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="main-traveler pt-lg-3 pr-lg-3 pr-10 pb-10 pl-lg-0 pl-3">
                                                            <ul>
                                                            
                                                            @if($reservation_record['e_class_type'] =="RT")
                                                                @if($reservation_luggage_info_total !='')
                                                                <?php $other_total = ($reservation_luggage_info_total + $reservation_luggage_info_total_rt); 
                                                                $other_total_charges = number_format((float)$other_total, 2, '.', '')?>
                                                                <li><span>Other Charges: </span><span>${{$other_total_charges}}</span></li>
                                                                @else
                                                                <li><span>Other Charges: </span>$0.00<span></span></li>
                                                                @endif
                                                            @else
                                                            <?php $reservation_luggage_info_totals = number_format((float)$reservation_luggage_info_total, 2, '.', '')  ?>
                                                                <li><span>Other Charges: </span><span>${{ $reservation_luggage_info_totals ? $reservation_luggage_info_totals : 0 }}</span></li>
                                                            @endif
                                                                <li><span>Payment Status: </span><span>{{ ($paymentStatus) ? $paymentStatus : 'Pending' }}</span></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="reservation-summary-table table-custom mb-20 table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" width="50%">Fare details</th>
                                                            <th scope="col" width="16%" style="text-align: right;">1st leg </th>
                                                            <th scope="col" width="16%" style="text-align: right;">2nd leg</th>
                                                            <th scope="col" width="16%" style="text-align: right;">total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if($total_fare_amount['adult_total'] > 0 || (isset($total_fare_amount_rt['adult_total']) && $total_fare_amount_rt['adult_total'] > 0 ))
                                                    <?php if(isset($total_fare_amount_rt['adult_count'])){
                                                        $total_fare_rt = $total_fare_amount_rt['adult_count'];
                                                    } else{
                                                        $total_fare_rt = 0;
                                                    }
                                                    $total_adult_fare = $total_fare_amount['adult_count'] + $total_fare_rt ?>
                                                            <tr>
                                                                <td scope="row">Adult Fare&nbsp;({{$total_adult_fare}})</td>
                                                                <td style="text-align: right;"><span>{{$total_fare_amount['adult_total'] ? '$'.number_format((float)$total_fare_amount['adult_total'], 2, '.', '') : '$0' }}</span></td>
                                                                <?php if(!empty($total_fare_amount_rt) && ($total_fare_amount_rt['adult_total'])){  $total_amount_rt = $total_fare_amount_rt['adult_total']; } else{
                                                                    $total_amount_rt = 0.00;
                                                                } 
                                                                $total_adult_fare_details = $total_fare_amount['adult_total'] +  $total_amount_rt

                                                                
                                                                ?>
                                                                <td style="text-align: right;"><span>{{'$'. number_format((float)$total_amount_rt, 2, '.', '') }}</span></td>
                                                                <td style="text-align: right;"><span>{{'$'.  number_format((float)$total_adult_fare_details, 2, '.', '')}}</span></td>
                                                            </tr>
                                                        @endif
                                                        @if($total_fare_amount['child_total'] > 0 || (isset($total_fare_amount_rt['child_total']) && $total_fare_amount_rt['child_total'] > 0 ))
                                                        <?php if(isset($total_fare_amount_rt['child_count'])){
                                                            $child_rt = $total_fare_amount_rt['child_count'];
                                                        } else{
                                                            $child_rt = 0;
                                                        }
                                                        $total_child_fare = $total_fare_amount['child_count'] + $child_rt ?>
                                                            <tr>
                                                                <td scope="row">Child Fare&nbsp;({{$total_child_fare}})</td>
                                                                <td style="text-align: right;"><span>{{$total_fare_amount['child_total'] ? '$'.number_format((float)$total_fare_amount['child_total'], 2, '.', '') : '$0' }}</span></td>
                                                                <?php if(!empty($total_fare_amount_rt) && ($total_fare_amount_rt['child_total'])){  $total_child_rt = $total_fare_amount_rt['child_total']; } else{
                                                                    $total_child_rt = 0.00;
                                                                }
                                                                $total_child_fare_details = $total_fare_amount['child_total'] +  $total_child_rt 

                                                                ?>
                                                                <td style="text-align: right;"><span>{{ '$'. number_format((float)$total_child_rt, 2, '.', '') }}</span></td>
                                                                <td style="text-align: right;"><span>{{ '$'. number_format((float)$total_child_fare_details, 2, '.', '')}}</span></td>
                                                            </tr>
                                                        @endif
                                                        @if($total_fare_amount['infant_count'] > 0 || (isset($total_fare_amount_rt['infant_count']) && $total_fare_amount_rt['infant_count'] > 0 ))
                                                        <?php if(isset($total_fare_amount_rt['infant_count'])){
                                                            $infant_rt = $total_fare_amount_rt['infant_count'];
                                                        } else{
                                                            $infant_rt = 0;
                                                        }
                                                        $total_infant_fare = $total_fare_amount['infant_count'] + $infant_rt ?>
                                                            <tr>
                                                                <td scope="row">Infant Details&nbsp;({{$total_infant_fare}})</td>
                                                                <td style="text-align: right;"><span>{{$total_fare_amount['infant_total'] ? '$'.number_format((float)$total_fare_amount['infant_total'], 2, '.', '') : 0 }}</span></td>
                                                                <?php if(!empty($total_fare_amount_rt) && ($total_fare_amount_rt['infant_total'])){  $total_infant_rt = $total_fare_amount_rt['infant_total']; } else{
                                                                    $total_infant_rt = 0.00;
                                                                } 
                                                                $total_infant_fare_details =  $total_fare_amount['infant_total'] +  $total_infant_rt
                                                                ?>
                                                                <td style="text-align: right;"><span>{{'$'. number_format((float)$total_infant_rt, 2, '.', '') }}</span></td>
                                                                <td style="text-align: right;"><span>{{'$'.number_format((float)$total_infant_fare_details, 2, '.', '') }}</span></td>
                                                            </tr>
                                                        @endif
                                                        @if($total_fare_amount['military_count'] > 0 || (isset($total_fare_amount_rt['military_count']) && $total_fare_amount_rt['military_count'] > 0 ))
                                                        <?php if(isset($total_fare_amount_rt['military_count'])){
                                                            $military_rt = $total_fare_amount_rt['military_count'];
                                                        } else{
                                                            $military_rt = 0;
                                                        }
                                                        $total_military_fare = $total_fare_amount['military_count'] + $military_rt ?>
                                                            <tr>
                                                                <td scope="row">Military Fare&nbsp;({{$total_military_fare}})</td>
                                                                <td style="text-align: right;"><span>{{$total_fare_amount['military_total'] ? '$'.number_format((float)$total_fare_amount['military_total'], 2, '.', '') : '$0' }}</span></td>
                                                                <?php if(!empty($total_fare_amount_rt) && ($total_fare_amount_rt['military_total'])){  $total_military_rt = $total_fare_amount_rt['military_total']; } else{
                                                                    $total_military_rt = 0.00;
                                                                }
                                                                $total_military_fare_details = $total_fare_amount['military_total'] +  $total_military_rt
                                                                    ?>
                                                                <td style="text-align: right;"><span>{{ '$'.number_format((float)$total_military_rt, 2, '.', '') }}</span></td>
                                                                <td style="text-align: right;"><span>{{'$'.number_format((float)$total_military_fare_details, 2, '.', '') }}</span></td>
                                                            </tr>
                                                        @endif
                                                        @if($total_fare_amount['senior_count'] > 0 || (isset($total_fare_amount_rt['senior_count']) && $total_fare_amount_rt['senior_count'] > 0 ))
                                                         <?php if(isset($total_fare_amount_rt['senior_count'])){
                                                            $senior_rt = $total_fare_amount_rt['senior_count'];
                                                        } else{
                                                            $senior_rt = 0;
                                                        }
                                                        $total_senior_fare = $total_fare_amount['senior_count'] + $senior_rt ?>
                                                            <tr>
                                                                <td scope="row">Senior Fare&nbsp;({{$total_senior_fare}})</td>
                                                                <td style="text-align: right;"><span>{{$total_fare_amount['senior_total'] ? '$'.number_format((float)$total_fare_amount['senior_total'], 2, '.', '') :'$0' }}</span></td>
                                                                <?php if(!empty($total_fare_amount_rt) && ($total_fare_amount_rt['senior_total'])){  $total_senior_rt = $total_fare_amount_rt['senior_total']; } else{
                                                                    $total_senior_rt = 0.00;
                                                                } 
                                                                $total_senior_fare_details = $total_fare_amount['senior_total'] +  $total_senior_rt
                                                                ?>
                                                                <td style="text-align: right;"><span>{{ '$'.number_format((float)$total_senior_rt, 2, '.', '') }}</span></td>
                                                                <td style="text-align: right;"><span>{{'$'.number_format((float)$total_senior_fare_details, 2, '.', '') }}</span></td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                            @endif
                                            @if((count($reservation_luggage_info) > 0) || (count($reservation_pet_info) > 0))
                                            <div class="reservation-summary-table table-custom table-responsive">
                                                @if($reservation_record['e_shuttle_type']=='Shared')
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" width="70%">1st Leg of Luggage Charge Details</th>
                                                            <th scope="col" width="15%" style="text-align: right;">Charge</th>
                                                            <th scope="col" width="15%" style="text-align: right;">Total Fare</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($reservation_luggage_info as $records)
                                                     
                                                            
                                                                @if(count($records['system_luggage_def']) > 0)
                                                                
                                                                <tr>
                                                                    <td><strong style="tfont-size: 14px;">{{ $records['system_luggage_def'][0]['v_name']}}&nbsp;{{'('.$records['i_value'].')' }}</strong></td>
                                                                    <td style="font-size: 14px; text-align:right;">{{ '$'.$records['system_luggage_def'][0]['d_unit_price'] }}</td>
                                                                    <td style="font-size: 14px; text-align:right;">{{ '$'.$records['d_price'] }}</td>
                                                                </tr>
                                                                @endif
                                                            
                                                            
                                                        @endforeach
                                                        @foreach($reservation_pet_info as $records)
                                                            @if(count($records['system_animal_def']) > 0)
                                                            <tr>
                                                                <td  scope="row"><strong>{{ $records['system_animal_def'][0]['v_name']}}&nbsp;(1)  </strong></td>
                                                                <td style="text-align:right;"> {{ '$'.$records['system_animal_def'][0]['d_unit_price'] }}</td>
                                                                <td style="text-align:right;">  {{ '$'.$records['system_animal_def'][0]['d_unit_price'] }}</td>
                                                            </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                @else                    
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" width="100%">1st Leg of Luggage Details</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($reservation_luggage_info as $records)
                                                     
                                                            
                                                                @if(count($records['system_luggage_def']) > 0)
                                                                
                                                                <tr>
                                                                    <td><strong style="tfont-size: 14px;">{{ $records['system_luggage_def'][0]['v_name']}}&nbsp;{{'('.$records['i_value'].')' }}</strong></td>
                                                                </tr>
                                                                @endif
                                                            
                                                            
                                                        @endforeach
                                                        @foreach($reservation_pet_info as $records)
                                                            @if(count($records['system_animal_def']) > 0)
                                                            <tr>
                                                                <td  scope="row"><strong>{{ $records['system_animal_def'][0]['v_name']}}&nbsp;(1)  </strong></td>
                                                            </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                @endif
                                            </div>
                                            @endif
                                            @if(isset($reservation_luggage_info_rt))
                                                @if((count($reservation_luggage_info_rt) > 0) || (count($reservation_pet_info_rt) > 0))
                                                <div class="reservation-summary-table table-custom table-responsive">
                                                @if($reservation_record['e_shuttle_type']=='Shared')
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" width="70%">2nd Leg of Luggage Charge Details</th>
                                                                <th scope="col" width="15%" style="text-align: right;">Charge</th>
                                                                <th scope="col" width="15%" style="text-align: right;">Total Fare</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($reservation_luggage_info_rt as $records_rt)
                                                                
                                                                
                                                                    @if(count($records_rt['system_luggage_def']) > 0)
                                                                    <tr>
                                                                        <td><strong style="tfont-size: 14px;">{{ $records_rt['system_luggage_def'][0]['v_name'] }}&nbsp;{{'('.$records_rt['i_value'].')' }}</strong></td>
                                                                        <td style="font-size: 14px; text-align:right;">{{ '$'.$records_rt['system_luggage_def'][0]['d_unit_price'] }}</td>
                                                                        <td style="font-size: 14px; text-align:right;">{{ '$'.$records_rt['d_price'] }}</td>
                                                                    </tr>
                                                                    @endif
                                                                
                                                                
                                                            @endforeach
                                                            @foreach($reservation_pet_info_rt as $records_rt)
                                                                @if(count($records_rt['system_animal_def']) > 0)
                                                                <tr>
                                                                    <td  scope="row"><strong>{{ $records_rt['system_animal_def'][0]['v_name']}}&nbsp;(1) </strong></td>
                                                                    <td style="text-align:right;"> {{ '$'.$records_rt['system_animal_def'][0]['d_unit_price'] }}</td>
                                                                    <td style="text-align:right;">  {{ '$'.$records_rt['system_animal_def'][0]['d_unit_price'] }}</td>
                                                                </tr>
                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                @else                
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" width="100%">2nd Leg of Luggage Details</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($reservation_luggage_info_rt as $records_rt)
                                                                
                                                                
                                                                    @if(count($records_rt['system_luggage_def']) > 0)
                                                                    <tr>
                                                                        <td><strong style="tfont-size: 14px;">{{ $records_rt['system_luggage_def'][0]['v_name'] }}&nbsp;{{'('.$records_rt['i_value'].')' }}</strong></td>
                                                                    </tr>
                                                                    @endif
                                                                
                                                                
                                                            @endforeach
                                                            @foreach($reservation_pet_info_rt as $records_rt)
                                                                @if(count($records_rt['system_animal_def']) > 0)
                                                                <tr>
                                                                    <td  scope="row"><strong>{{ $records_rt['system_animal_def'][0]['v_name']}}&nbsp;(1) </strong></td>
                                                                </tr>
                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                @endif    
                                                </div>
                                                @endif
                                            @endif
                                            @if($reservation_record['e_shuttle_type']=='Shared')
                                            <div class="text-left">
                                                <p class="mb-0">*Charge does not apply to first two checked or carryon bags per traveler after checked and carryon detail</p>
                                                <p class="mb-0">*Must travel with Full Fare Adult</p>
                                                <p class="mb-0">**Must tell us in advance</p>
                                            </div>
                                            @endif
                                        </div>
                                    
                                    </form>
                                    
                                    <div class="rocket-info__next mt-4 text-right">
                                            @if(isset($reservation_record) && $reservation_record['e_shuttle_type'] == 'Private')
                                                <a href="{{SITE_URL}}success" class="btn btn-md btnNext btn-purple">Submit Request</a>
                                            @else                    
                                            <a href="{{ ((request()->route('id') != '') ? SITE_URL.'reservation-payment/'.request()->route('id') : SITE_URL.'reservation-payment') }}" class="btn btn-md btnNext btn-purple">Next</a>
                                            @endif
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="d-none" id="ps_desc"></div>
    
@section('custom_js')
<script>
    $(document).ready(function() {
        KTReservationFrontend.init();
        $('#printPage').on('click',function(){
            var url = (SITE_URL + 'get-reservation-print-data');
            $.post(url,function(response) {
                $('#ps_desc').html(response);
                var divToPrint=document.getElementById("ps_desc");
                $(divToPrint).show();
                newWin= window.open("");
                newWin.document.write(divToPrint.innerHTML);
                newWin.print();
                newWin.close();
                $(divToPrint).hide();
            });
         
        });
       
    });
</script>
@stop

@stop
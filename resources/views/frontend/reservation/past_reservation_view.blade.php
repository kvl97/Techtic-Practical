@extends('frontend.layouts.default')
@section('content')
<?php
    $route = URL::previous();
    $explode_url = explode('/', $route);
    $last_part = end($explode_url);
    // pr($last_part); exit; 
    $resv_status = $reservation_record['e_reservation_status'];
    if($reservation_record_rt) {
        if(request()->route('id') == $reservation_record_rt['id']) {
            $resv_status = $reservation_record_rt['e_reservation_status'];
        }
    }
?>
<section class="about-section mt-5 mb-5">
    <div class="container">

        <div class="profile-quick-links">
            <ul>
                <li><a href="{{FRONTEND_URL.'my-profile'}}">My Profile</a></li>
                <li><a href="{{FRONTEND_URL.'upcoming-reservation'}}">Upcoming Reservation</a></li>
                @if($last_part == 'payment-history')
                    <li><a href="{{FRONTEND_URL.'past-reservation'}}">Past Reservation</a></li>
                    <li><a href="{{FRONTEND_URL}}my-card-information">Card Info</a></li>
                    <li class="active"><a href="{{FRONTEND_URL.'payment-history'}}">Payment History</a></li>
                @else
                    <li class="active"><a href="{{FRONTEND_URL.'past-reservation'}}">Past Reservation</a></li>
                    <li><a href="{{FRONTEND_URL}}my-card-information">Card Info</a></li>
                    <li><a href="{{FRONTEND_URL.'payment-history'}}">Payment History</a></li>
                @endif
            </ul>
            @if(auth()->guard('customers')->user() && auth()->guard('customers')->user()->d_wallet_balance > 0.00)
                <span class="sec-wallet-balance-lg"><strong>Wallet Balance:</strong> ${{ auth()->guard('customers')->user()->d_wallet_balance }}</span>
            @endif
        </div>
        @if(auth()->guard('customers')->user() && auth()->guard('customers')->user()->d_wallet_balance > 0.00)
            <div class="row sec-wallet-balance-sm">
                <span><strong>Wallet Balance:</strong> ${{ auth()->guard('customers')->user()->d_wallet_balance }}</span>
            </div>
        @endif
        <div class="contact-wrapper row">
            <div class="contact-paginate col-md-12 col-xl-12 mt-4 upcoming-reservations-table">
                
                <div class="">
                    <div class="tab-content" id="v-tabContent">
                        <!-- 7 -->
                        <div class="tab-pane fade show active" id="reservation-summary" role="tabpanel" aria-labelledby="settings-tab">
                            <div id="succuse_msg">
                                @if(Session::has('success-message'))
                                <div class="alert alert-success alert-dismissible" role="alert"><span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></span>{{ Session::get('success-message') }}</div>
                                @endif
                            </div>
                            <form action="{{SITE_URL}}reservation-detail-download/{{$id}}" class="rocket-info-details rocket-info-reservation-summary">
                            <div class="align-items-center justify-content-between profile-address-bar pb-4">
                                <div class="col-md-6 col-sm-12 pl-0">
                                    @if($last_part == 'payment-history')
                                        <a href="{{FRONTEND_URL.'payment-history'}}" class="btn btn-xs btn-red manage-back-url">Back</a>
                                    @else
                                        <a href="{{FRONTEND_URL.'past-reservation'}}" class="btn btn-xs btn-red manage-back-url">Back</a>
                                    @endif
                                </div>

                                <div class="col-md-6 col-sm-12 pr-0 button-group" id="print-block">
                                    @if($reservation_record['e_reservation_status']=='Booked')
                                        <a href="{{SITE_URL.'reservation-detail-download/'.$id}}" class="btn btn-xs btn-purple ">DOWNLOAD</a>
                                        <button type="button" id="printPage" class="btn btn-xs btn-purple" print_id="{{$id}}">PRINT</button>                                                 
                                    @endif
                                    @if($show_cancel_btn)
                                        <a class="btn btn-xs btn-red" rel="{{$id}}" cancel-url="{{ SITE_URL }}reservation-cancel/{{ request()->route('id') }}" href="javascript:;"  title="Cancel" id="cancel_cust_reservation">Cancel</a>
                                    @endif
                                </div>
                            </div>
                            @if(request()->route('id') == $reservation_record['id'] && $reservation_record['e_class_type'] == 'RT' && $reservation_record['i_parent_id'] == NULL && !empty($reservation_record_rt))
                                <div class="alert alert-info" role="alert">
                                    This is round trip reservation. Please check 2nd leg information on this ticket: &nbsp;<a href="{{SITE_URL.'past-reservation/'.$reservation_record_rt['id']}}">{{$reservation_record_rt['v_reservation_number']}}</a>.
                                </div>
                            @elseif($reservation_record['e_class_type'] == 'RT' && request()->route('id') == $reservation_record_rt['id'] && $reservation_record['i_parent_id'] == NULL && !empty($reservation_record_rt))  
                                <div class="alert alert-info" role="alert">
                                    This is round trip reservation. Please check 1st leg information on this ticket: &nbsp;<a href="{{SITE_URL.'past-reservation/'.$reservation_record['id']}}">{{$reservation_record['v_reservation_number']}}</a>.
                                </div>
                            @endif
                            
                                <div class="rocket-info__one">
                                    <div class="row align-items-center">
                                        <div class="col-lg-6 col-md-6">
                                            @if(request()->route('id') == $reservation_record['id'] && $reservation_record['e_class_type'] == 'RT' && $reservation_record['i_parent_id'] == NULL && !empty($reservation_record_rt))

                                                <p class="reservation-code py-2 px-4"><strong>Reservation No. : </strong><span>@if(isset($reservation_record['v_reservation_number'])) {!! $reservation_record['v_reservation_number']!!}@endif</span></p>
                                            @elseif($reservation_record['e_class_type'] == 'RT' && request()->route('id') == $reservation_record_rt['id'] &&  $reservation_record['i_parent_id'] == NULL && !empty($reservation_record_rt))
                                                <p class="reservation-code py-2 px-4"><strong>Reservation No. : </strong><span>@if(isset($reservation_record_rt['v_reservation_number'])) {!! $reservation_record_rt['v_reservation_number']!!}@endif</span></p>
                                            @else
                                                <p class="reservation-code py-2 px-4"><strong>Reservation No. : </strong><span>@if(isset($reservation_record['v_reservation_number'])) {!! $reservation_record['v_reservation_number']!!}@endif</span></p>
                                            @endif
                                           
                                        </div>
                                        <div class="d-flex flex-row-reverse col-lg-6 text-lg-right mt-3 mt-md-0 mt-lg-0 col-md-6" id="print-block">
                                                @if($resv_status != 'Booked' && $resv_status != 'Quote')
                                                    <span class="ml-lg-2 ml-md-2" style="background-color: #fff;border-radius: 50px;padding:8px 20px">
                                                        {{ $resv_status }}
                                                    </span>
                                                @endif
                                                <span style="background-color: #fff;border-radius: 50px;padding:8px 20px">{{ ($reservation_record['e_shuttle_type']) }} Shuttle</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="rocket-info__two" style="background-color: whitesmoke;">
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
                                                                        @if(isset($reservation_record['e_class_type']) && $reservation_record['e_class_type'] =="RT")     
                                                                            $<?= (isset($reservation_record['d_total_fare']) && (isset($reservation_record_rt['d_total_fare']))) ? (number_format((float)$reservation_record['d_total_fare'] + $reservation_record_rt['d_total_fare'], 2, '.', '')) : (isset($reservation_record['d_total_fare']) ? number_format((float)$reservation_record['d_total_fare'], 2, '.', '') : (isset($reservation_record_rt['d_total_fare']) ? number_format((float)$reservation_record_rt['d_total_fare'], 2, '.', '') : '0.00')); ?>
                                                                        @else    
                                                                            <?= (isset($reservation_record['d_total_fare'])) ? '$'.number_format((float)$reservation_record['d_total_fare'], 2, '.', '') : '$0.00'; ?>
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
                                                        <!-- <p>1st Leg of Travel</p> -->
                                                        @if($reservation_record['e_class_type']=='OW')
                                                        <p>Travel Details</p>
                                                        @else 
                                                        <span class="reservation-code py-2 px-4"><strong class="d-none d-sm-none d-md-none d-lg-inline">1st Leg Of Travel: </strong><span>{{ $reservation_record['v_reservation_number'] }}</span></span>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-6 text-sm-right">
                                                        <p class="date"> Date: <span> @if(isset($reservation_record['d_travel_date'])) {!! date(DATE_FORMAT,strtotime($reservation_record['d_travel_date'])) !!}@else {{'-'}} @endif </span></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="leg-traveler-inner-wrap py-3 pl-20 pr-20">
                                                @if(isset($reservation_record['PickupCity']) && (!empty($reservation_record['PickupCity']) && !empty($reservation_record['DropOffCity'] )))
                                                        <div class="leg-traveler-inner">
                                                            
                                                            <div class="main-traveler location">
                                                                <ul>
                                                                    <li class="pb-10"><span>To arrive at pickup location between </span><span>{{$reservation_record['t_comfortable_time'] ?  date('H:i' , strtotime($reservation_record['t_comfortable_time']))   : '-' }} ({{ $reservation_record['t_comfortable_time'] ? date('g:i A' , strtotime($reservation_record['t_comfortable_time'])) : '-'}}) {{$reservation_record['t_target_time'] ?  'and '.date('H:i' , strtotime($reservation_record['t_target_time']))   : '' }} {{ $reservation_record['t_target_time'] ? '('.date('g:i A' , strtotime($reservation_record['t_target_time'])).')' : ''}}</span></li>
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
                                                                    <li><span>City</span><span>{{$reservation_record['PickupCity']['v_city'] ? $reservation_record['PickupCity']['v_city'] : '-' }}</span></li>
                                                                    <li><span>County</span><span>{{$reservation_record['PickupCity']['v_county'] ? $reservation_record['PickupCity']['v_county'] : '-' }}</span></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-6 col-md-12 pl-xl-2 mt-3 mt-xl-0">
                                                            <div class="main-traveler main-traveler-listing p-20 h-100">
                                                                <p class="main-traveler--title mb-0">Drop Off Location</p>
                                                                <span class="main-traveler--subtitle d-block mb-20">{{ $reservation_record['DropOffCity']['v_city'].' ('.$reservation_record['DropOffCity']['v_county'].')' }}</span>
                                                                <ul>
                                                                    <li><span>Address</span><span> {{ $reservation_record['v_dropoff_address']  ? $reservation_record['v_dropoff_address'] :  '-'}}</span></li>
                                                                    <li><span>City</span> <span>{{$reservation_record['DropOffCity']['v_city'] ? $reservation_record['DropOffCity']['v_city'] : '-' }}</span></li>
                                                                    <li><span>County</span><span>{{$reservation_record['DropOffCity']['v_county'] ? $reservation_record['DropOffCity']['v_county'] : '-' }}</span></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if(!empty($reservation_record['e_flight_type'] || $reservation_record['v_flight_number'] || $reservation_record['t_flight_time'] || $reservation_record['v_flight_name']))
                                                    <div class="arrival-flight mt-10 mb-1">
                                                        <p class="mb-0"><strong>{{ ($res1_tt_text) ? $res1_tt_text['direction'] : '' }} Flight Details</strong></p>
                                                    </div>
                                                    @endif
                                                    <div class="custom-dots">
                                                        <ul>
                                                            @if(isset($reservation_record['e_flight_type']))
                                                                <li><span>Flight Type</span><span>:  {!! $reservation_record['e_flight_type'] !!} </span></li>
                                                            @endif
                                                            @if(isset($reservation_record['v_flight_name'])) 
                                                                <li><span>Airline</span><span>: {!! $reservation_record['v_flight_name'] !!}</span></li>
                                                            @endif
                                                            @if(isset($reservation_record['v_flight_number']))
                                                                <li><span>Flight #:</span><span> {!! $reservation_record['v_flight_number'] !!}</span></li>
                                                            @endif 
                                                            @if(isset($reservation_record['t_flight_time'])) 
                                                                <li><span>Flight Time: </span><span>{{ $reservation_record['t_flight_time'] ? date('g:i a' , strtotime($reservation_record['t_flight_time'])) : '-'}}</span></li>
                                                                
                                                            @endif 
                                                        </ul>
                                                    </div>
                                                @else
                                                <p class="info-not-added">Information is not yet added.</p>
                                                @endif
                                            </div>
                                        </div>
                                        @if(isset($reservation_record_rt['e_class_type']) && $reservation_record_rt['e_class_type'] =="RT") 
                                            <div class="reservation-summary-block mb-20">
                                                <div class="summary-block__title py-3 pl-20 pr-20">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <!-- <p>2nd Leg of Travel</p> -->
                                                            <span class="reservation-code py-2 px-4"><strong class="d-none d-sm-none d-md-none d-lg-inline">2nd Leg Of Travel: </strong><span>{{ $reservation_record_rt['v_reservation_number'] }}</span></span>
                                                        </div>
                                                        <div class="col-sm-6 text-sm-right">
                                                            <p class="date"> Date: <span> @if(isset($reservation_record_rt['d_travel_date'])) {!! date(DATE_FORMAT,strtotime($reservation_record_rt['d_travel_date'])) !!}@else {{'-'}} @endif </span></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="leg-traveler-inner-wrap py-3 pl-20 pr-20">
                                                @if(isset($reservation_record['PickupCity']) && (!empty($reservation_record['PickupCity']) && !empty($reservation_record['DropOffCity'] )))
                                                    <div class="leg-traveler-inner">
                                                        
                                                        <div class="main-traveler location">
                                                            <ul>
                                                                <li class="pb-10"><span>To arrive at pickup location between</span><span>{{$reservation_record_rt['t_comfortable_time'] ?  date('H:i' , strtotime($reservation_record_rt['t_comfortable_time']))   : '-' }} ({{ $reservation_record_rt['t_comfortable_time'] ? date('g:i A' , strtotime($reservation_record_rt['t_comfortable_time'])) : '-'}}) {{$reservation_record_rt['t_target_time'] ?  'and '.date('H:i' , strtotime($reservation_record_rt['t_target_time']))   : '' }} {{ $reservation_record_rt['t_target_time'] ? '('.date('g:i A' , strtotime($reservation_record_rt['t_target_time'])).')' : ''}}</span></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="row no-gutters">
                                                        <div class="col-xl-6 col-md-12 pr-xl-2">
                                                            <div class="main-traveler main-traveler-listing p-20 h-100">
                                                                <p class="main-traveler--title mb-0">Pickup Location</p>
                                                                <span class="main-traveler--subtitle d-block mb-20">{{ $reservation_record_rt['PickupCity']['v_city'].' ('.$reservation_record_rt['PickupCity']['v_county'].')' }}</span>
                                                                <ul>
                                                                    <li><span>Address</span><span> {{ $reservation_record_rt['v_pickup_address']  ? $reservation_record_rt['v_pickup_address'] :  '-'}}</span></li>
                                                                    <li><span>City</span><span>{{$reservation_record_rt['PickupCity']['v_city'] ? $reservation_record_rt['PickupCity']['v_city'] : '-' }}</span></li>
                                                                    <li><span>County</span><span>{{$reservation_record_rt['PickupCity']['v_county'] ? $reservation_record_rt['PickupCity']['v_county'] : '-' }}</li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-6 col-md-12 pl-xl-2 mt-3 mt-xl-0">
                                                            <div class="main-traveler main-traveler-listing p-20 h-100">
                                                                <p class="main-traveler--title mb-0">Drop Off Location</p>
                                                                <span class="main-traveler--subtitle d-block mb-20"> {{ $reservation_record_rt['DropOffCity']['v_city'].' ('.$reservation_record_rt['DropOffCity']['v_county'].')' }}</span>
                                                                <ul>
                                                                    <li><span>Address</span> <span> {{ $reservation_record_rt['v_dropoff_address']  ? $reservation_record_rt['v_dropoff_address'] :  '-'}}</span></li>
                                                                    <li><span>City</span><span>{{$reservation_record_rt['DropOffCity']['v_city'] ? $reservation_record_rt['DropOffCity']['v_city'] : '-' }}</span></li>
                                                                    <li><span>County</span><span>{{$reservation_record_rt['DropOffCity']['v_county'] ? $reservation_record_rt['DropOffCity']['v_county'] : '-' }}</span></li>
                                                                    
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if(!empty($reservation_record_rt['e_flight_type'] || $reservation_record_rt['v_flight_number'] || $reservation_record_rt['t_flight_time'] || $reservation_record_rt['v_flight_name']))
                                                    <div class="arrival-flight mt-10 mb-1">
                                                        <p class="mb-0"><strong>{{ ($res2_tt_text) ? $res2_tt_text['direction'] : '' }} Flight Details</strong></p>
                                                    </div>
                                                    @endif
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
                                                            <li><span>Flight Time: </span><span> {{ $reservation_record_rt['t_flight_time'] ? date('g:i a' , strtotime($reservation_record_rt['t_flight_time'])) : '-'}}</span></li>
                                                            @endif 
                                                        </ul>
                                                    </div>
                                                @else
                                                <p class="info-not-added">Information is not yet added.</p>
                                                @endif
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
                                                                @if(!empty($payment_info))
                                                                    <li><span>Mode of Payment</span><span>{{ ($payment_mode) ? $payment_mode : '-' }}</span></li>
                                                                @endif
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
                                                                <li><span>Other Charges</span><span>${{$other_total_charges}}</span></li>
                                                                @else
                                                                <li><span>Other Charges</span>$0.00<span></span></li>
                                                                @endif
                                                            @else
                                                            <?php $reservation_luggage_info_totals = number_format((float)$reservation_luggage_info_total, 2, '.', '')  ?>
                                                                <li><span>Other Charges</span><span>${{ $reservation_luggage_info_totals ? $reservation_luggage_info_totals : 0 }}</span></li>
                                                            @endif
                                                            @if(!empty($payment_info))
                                                                <li><span>Payment Status</span><span> {{ (count($payment_info) > 0 && $payment_info[0]['e_status']=="Success") ? "Paid" : "Failed" }}</span></li>
                                                            @endif
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if(!empty($total_fare_amount['adult_count'] || $total_fare_amount['child_count'] || $total_fare_amount['infant_count'] || $total_fare_amount['military_count'] || $total_fare_amount['senior_count']))
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
                                            <p class="mb-0">*Must travel with Full Fare Adult</p>
                                            <p class="mb-0">**Must tell us in advance</p>
                                        </div>
                                        @endif
                                        
                                    </div>
                                    <div class="d-none">
                                        @if(isset($reservation_record['ReservationLogs']) && count($reservation_record['ReservationLogs']) > 0)
                                            <div class="reservation_log_details kt-mt-20 table-responsive">
                                                <h5><b> Log Activity Details: </b></h5>
                                                
                                                <table class="table table-bordered table-hover mt-3 ">
                                                    <thead>
                                                        <tr>
                                                            <th>Old Status</th>
                                                            <th>New Status</th>
                                                            <th>Note</th>
                                                            <th>Modify By</th>
                                                            <th>User Type</th>
                                                            <th>Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                       
                                                        @foreach ($reservation_record['ReservationLogs'] as $logs)
                                                            <tr>
                                                                <?php $log = json_decode($logs->v_log_json , true); ?>
                                                                <td>
                                                                    <?php
                                                                        if(isset($log['data']['oldArray']['Reservation Status'])) {
                                                                            echo $log['data']['oldArray']['Reservation Status'];
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php 
                                                                        if(isset($log['data']['newArray']['Reservation Status'])) {
                                                                            echo $log['data']['newArray']['Reservation Status'];
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php 
                                                                        if(isset($log['data']['newArray']['Special Instruction'])) {
                                                                            echo $log['data']['newArray']['Special Instruction'];
                                                                        }
                                                                    ?></br>
                                                                    <?php 
                                                                        if(isset($log['data']['newArray']['Total Fare'])) {
                                                                            echo "<p style='font-size:14.4px;'><b>Amount to Pay:</b> $". $log['data']['newArray']['Total Fare'].'</p>';
                                                                        }
                                                                    ?>
                                                                </td>
                                                                @if($logs->i_modified_by == 0) 
                                                                    <td>{{ $logs->Reservations['Customers']['v_firstname'] }} {{ $logs->Reservations['Customers']['v_lastname'] }}</td>
                                                                @elseif ($logs->e_user_type == 'Customer')
                                                                    <td>{{ $logs->CustomersLogs['v_firstname'] }} {{ $logs->CustomersLogs['v_lastname'] }}</td>
                                                                @elseif ($logs->e_user_type == 'Admin')
                                                                    <td>{{ $logs->AdminLogs['v_firstname'] }} {{ $logs->AdminLogs['v_lastname'] }}</td>
                                                                @endif
                                                                <td>{{ $logs->e_user_type }}</td>
                                                                <td>{{ date(DATETIME_FORMAT,strtotime($logs->created_at)) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                
                                            </div>
                                        @endif
                                    </div>
                            </form>      
                            <div class="d-none" id="ps_desc">
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>                                      
    </div>
</section>
<div class="modal fade bd-example-modal-sm p-md-0" id="cancelReservation" tabindex="-1" role="dialog" aria-labelledby="cancelReservation" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-xl modal-dialog modal-dialog-scrollable"
        role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelReservation">Cancel Reservation</h5>
            </div>
            <div class="modal-body text-md-left">
                <div class="contestants-info mt-0">
                    Do you want to cancel this reservation ?
                </div>
                
            </div>
            <div class="modal-footer">
                    <button type="button" class="btn btn-xs btn-red" id="modal-btn-set">Yes</button>
                    <button type="button" class="btn btn-primary" id="modal-btn-cencel">No</button>
                </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-sm p-md-0" id="requestRefundModal" tabindex="-1" role="dialog" aria-labelledby="requestRefundModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-xl modal-dialog modal-dialog-scrollable"
        role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Request Refund</h5>
            </div>
            <div class="modal-body text-md-left">
                <div class="contestants-info mt-0">
                    Are you sure you want to request for refund?
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-xs btn-red" id="refund-modal-btn-set">Yes</button>
                <button type="button" class="btn btn-primary" id="refund-modal-btn-cencel">No</button>
            </div>
        </div>
    </div>
</div>


@section('custom_js')

<script>
    $(document).ready(function () {
        var d = new Date();
        var n = d.getTime();
        setCookie("payment_history","Yes", 365);
        
        $(document).on('click','#btn_request_refund',function(e) {
            var url =  $(this).attr('refund-url');
            var that = this;
            refundModalSetConfirm(function(confirm){
                if(confirm){
                    if(url!=undefined && url!='') {
                        $(that).html('<i class="fa fa-refresh fa-spin"></i> Processing');
                        $.ajax({
                            type: 'POST',
                            url: url,
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function(resultData) {
                                $('#btn_request_refund').html('Request Refund');
                                if(resultData == 'TRUE') {
                                    $('#btn_request_refund').removeAttr('refund-url').html("Refund Requested");
                                    $('#btn_request_refund').removeClass('btn-purple').addClass('btn-red').removeAttr('title');
                                    $("#succuse_msg").html('<div class="alert alert-success alert-dismissible" role="alert"><span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></span>Refund request successful.</div>');
                                } else {
                                    $("#succuse_msg").html('<div class="alert alert-danger alert-dismissible" role="alert"><span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></span>Error processing refund request.</div>');
                                }
                                $("html, body").animate({
                                        scrollTop: 0
                                    }, 1000);
                            }
                        }); 
                    }
                } else {
                    $("#requestRefundModal").modal('hide');
                }
            });
        });

        var refundModalSetConfirm = function(callback) {  
            $("#requestRefundModal").modal('show');
            $("#refund-modal-btn-set").on("click", function(){
                callback(true);
                $("#requestRefundModal").modal('hide');
            });
        
            $("#refund-modal-btn-cencel").on("click", function(){
                callback(false);
                $("#requestRefundModal").modal('hide');
            });
        };
        
        $('#printPage').on('click',function(){
            var id = $(this).attr('print_id');
            var url = (SITE_URL + 'reservation-detail-print/'+id);
            $.post(url,function(response) {
                $('#ps_desc').html(response);
                var divToPrint=document.getElementById("ps_desc");
                $(divToPrint).find('#ps_desc').show();
                newWin= window.open("");
                newWin.document.write(divToPrint.outerHTML);
                newWin.print();
                newWin.close();
                $(divToPrint).find('#ps_desc').hide();
            });
         
        });
        $(document).on('click','#cancel_cust_reservation',function(e) {
        var that = this;
            modalSetConfirm(function(confirm){
                if(confirm){
                    var url =  $(that).attr('cancel-url');  
                    $.ajax({
                        type: 'POST',
                        url: url,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(resultData) {
                            if(resultData == 'TRUE') {
                                $("#cancelReservation").modal('hide');
                                $('#cancel_cust_reservation').addClass('d-none');
                                window.location.reload();
                            } 
                        }
                    }); 
                }else {
                    $("#cancelReservation").modal('hide');
                }
            });
        });
        var modalSetConfirm = function(callback) {  
            $("#cancelReservation").modal('show');
            $("#modal-btn-set").on("click", function(){
                callback(true);
                $("#mi-modal_set").modal('hide');
            });
        
            $("#modal-btn-cencel").on("click", function(){
                callback(false);
                $("#mi-modal_set").modal('hide');
            });
        };
        
    });

    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

</script>
@stop
@stop
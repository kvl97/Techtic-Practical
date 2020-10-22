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
                            
                            <a class="nav-link completed {{ (isset($reservation_record) && $reservation_record['e_shuttle_type']=='Private') ? 'd-none' : '' }}" id="runs-tab" href="javascript:;" role="tab" aria-controls="runs" aria-selected="true" data-action="{{ ((request()->route('id') != '') ? 'display-line-runs/'.request()->route('id') : 'display-line-runs') }}">Display Line Runs</a>
                            
                            <a class="nav-link completed" id="passenger-information-tab" href="javascript:;" role="tab" aria-controls="passenger-information" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'passenger-information/'.request()->route('id') : 'passenger-information') }}">Passenger Information</a>
                            
                            <a class="nav-link completed" id="luggage-animals-tab" href="javascript:;" role="tab" aria-controls="luggage-animals" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'luggage-animals/'.request()->route('id') : 'luggage-animals') }}">Luggage and Animals</a>

                            <a class="nav-link active" id="travel-tab" href="javascript:;" role="tab" aria-controls="travel" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'travel-details/'.request()->route('id') : 'travel-details') }}">Travel Details</a>
                            
                            <a class="nav-link {{ (isset($reservation_record) && $reservation_record['e_shuttle_type']=='Private') ? 'd-none' : '' }}" id="confirm-tab" href="javascript:;" role="tab" aria-controls="confirm" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'currently-assigned-line-runs/'.request()->route('id') : 'currently-assigned-line-runs') }}">Currently Assigned Line Run</a>
                            @if((request()->route('id') != '' && !($paymentStatus)) || request()->route('id') == '')
                                <a class="nav-link disable" id="reservation-summary-tab" href="javascript:;" role="tab" aria-controls="reservation-summary" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'reservation-summary/'.request()->route('id') : 'reservation-summary') }}">Reservation Summary</a>

                                <a class="nav-link disable {{ (isset($reservation_record) && $reservation_record['e_shuttle_type']=='Private') ? 'd-none' : '' }}" id="payment-tab" href="javascript:;" role="tab" aria-controls="payment" aria-selected="false"data-action="{{ ((request()->route('id') != '') ? 'reservation-payment/'.request()->route('id') : 'reservation-payment') }}">Payment</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="rocket-info-right">
                        <div class="tab-content" id="v-tabContent">
                            <!-- 5 -->
                            <div class="tab-pane fade show active" id="travel" role="tabpanel" aria-labelledby="travel-tab">
                                <form id="frontend_travel_details" method="POST" action="{{ ((request()->route('id') != '') ? SITE_URL.'travel-details/'.request()->route('id') : SITE_URL.'travel-details') }}" class="rocket-info-details rocket-info-travel">
                                @if(isset($reservation_record) && $reservation_record['e_shuttle_type']=='Private')
                                    @if((request()->route('id') != '' && !($paymentStatus)) || request()->route('id') == '')
                                        <input type="hidden" name="redirect_url" value="{{ ((request()->route('id') != '') ? 'reservation-summary/'.request()->route('id') : 'reservation-summary') }}" id="redirect_url">
                                    @else
                                        <input type="hidden" name="redirect_url" value="{{ ((auth()->guard('admin')->check()) ? ADMIN_URL.'reservations/view/'.request()->route('id') : 'upcoming-reservation/'.request()->route('id')) }}" id="redirect_url">
                                    @endif
                                @else
                                    <input type="hidden" name="redirect_url" value="{{ ((request()->route('id') != '') ? 'currently-assigned-line-runs/'.request()->route('id') : 'currently-assigned-line-runs') }}" id="redirect_url">
                                @endif
                                    <h3 class="rocket-info__title">Travel Details</h3>
                                    <div class="rocket-info__one inquiry-form">
                                        <div class="row no-gutters">
                                            <div class="col-md-12 form-group">
                                                <div class="input-field">
                                                    <input type="text" class="form-control required" placeholder="Name" id="v_contact_name" name="v_contact_name" value="{{ $reservation_record['v_contact_name'] }}" />
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-12 form-group pr-1">
                                                <div class="input-field">
                                                    <input type="number" class="form-control required" placeholder="Contact Number" id="v_contact_phone_number" name="v_contact_phone_number" value="{{ $reservation_record['v_contact_phone_number'] }}" />
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-12 form-group pl-1">
                                                <div class="input-field">
                                                    <input type="email" class="form-control required email" placeholder="Email" id="v_contact_email" name="v_contact_email" value="{{ $reservation_record['v_contact_email'] }}" />
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-sm-12 d-flex align-items-center mb-2 mb-xl-0">
                                                <div class="custom-dots">
                                                    <ul class="d-flex flex-wrap">
                                                        <li> {{ $reservation_record['peoples'] }} Passengers</li>
                                                        <li> <?= ($reservation_record['e_class_type'] == 'OW') ? "One Way" : ($reservation_record['i_parent_id'] != NULL ? "One Way" : "Round Trip") ?> </li>
                                                        <li> {{ $reservation_record['e_shuttle_type'] }} </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <!-- <div class="col-xl-6 col-sm-12">
                                                <div class="row no-gutters align-items-center">
                                                    <label class="col-xl-6 col-lg-3 col-md-4 col-form-label text-xl-right"><strong>Best Time for Call</strong></label>
                                                    <div class="input-field col-xl-6 col-lg-4 col-md-8 pl-md-2">
                                                        <input type="text" value="{{ ($reservation_record && $reservation_record['t_best_time_tocall'] != '') ? date('g:i a', strtotime($reservation_record['t_best_time_tocall'])) : '' }}" placeholder="Best time to call" id="t_best_time_tocall" name="t_best_time_tocall" class="form-control kt_timepicker1" />
                                                    </div>
                                                </div>
                                            </div> -->
                                        </div>
                                    </div>
                                    <div class="rocket-info__five">
                                        <div class="rocket-info-travel-box mt-10 mb-20">
                                            <div class="row">
                                                <div class="col-xl-6 col-sm-12 form-group">
                                                    <div class="row no-gutters align-items-center">
                                                        <label class="col-md-6 col-form-label"><strong>1st Date of Travel</strong></label>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control hasDatepicker ml-xl-2" placeholder="Depart Date" readonly="readonly" value="<?= date('m/d/Y',strtotime($reservation_record['d_travel_date']))?>" id="d_depart_date" />
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-12 form-group">
                                                    <div class="row no-gutters align-items-center">
                                                        <label class="col-xl-2 col-md-2 col-form-label">Pickup Point</label>
                                                        @if($reservation_record->PickupCity->e_allow_manual_address==1 || $reservation_record['e_shuttle_type']=='Private')
                                                        <div class="col-xl-7 col-md-7 pl-md-1 mb-1 mb-lg-0">
                                                            <input type="text" service_area="{{ $reservation_record->PickupCity->i_service_area_id }}" data-cityId="{{ $reservation_record['i_pickup_city_id'] }}" value="{{ ($reservation_record && $reservation_record['v_pickup_address']!='') ? $reservation_record['v_pickup_address'] : '' }}" name="i_origin_point_id" id="i_origin_point_id" autocomplete="off" class="form-control required" placeholder="Type {{ $pickup_points['v_city'] }} location" />
                                                        </div>
                                                        @else
                                                        <div class="select-field col-xl-7 col-md-7 pl-md-1 mb-1 mb-lg-0">
                                                            <select placeholder="pickup point" class="form-control required travel_points" name="i_origin_point_id" id="i_origin_point_id">
                                                                <option value="">Select {{ $pickup_points['v_city'] }} location</option>
                                                                @foreach($pickup_points['geo_points'] as $pp) 
                                                            
                                                                <option <?= ($reservation_record && $reservation_record['v_pickup_address'] ==$pp['v_label'].' '.$pp['v_street1']) ? 'selected="selected"' : ''; ?>  value="{{ $pp['v_label'].' '.$pp['v_street1'] }}" service_area="{{$pickup_points['i_service_area_id']}}">{{ $pp['v_label'].' '.$pp['v_street1'] }}</option>   
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @endif
                                                        <div class="select-field col-xl-3 col-md-3 pl-md-1 mb-1 mb-lg-0">
                                                            <select placeholder="travel type" class="form-control travel_type required pick_type" id="ow_pickup_travel_type" name="i_reservation_category_id">
                                                                <option value="">Travel Type</option>
                                                                @foreach($pick_res_categories as $cat)
                                                                    <option <?= ($reservation_record && $reservation_record['i_reservation_category_id'] == $cat['id']) ? 'selected="selected"' : ''; ?> data-title="{{ $cat['v_label'] }}" value="{{ $cat['id'] }}">{{ $cat['v_label'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <div class="row no-gutters align-items-center">
                                                        <label class="col-xl-2 col-md-2 col-form-label">Drop off point</label>
                                                        
                                                        @if($reservation_record->DropOffCity->e_allow_manual_address==1 || $reservation_record['e_shuttle_type']=='Private')
                                                        <div class="col-xl-7 col-md-7 pl-md-1 mb-1 mb-lg-0">
                                                            <input type="text" service_area="{{ $reservation_record->DropOffCity->i_service_area_id }}" data-cityId="{{ $reservation_record['i_dropoff_city_id'] }}" name="i_destination_point_id" id="i_destination_point_id" class="form-control required" placeholder="Type {{ $dropoff_points['v_city'] }} location" autocomplete="off" value="{{ ($reservation_record && $reservation_record['v_dropoff_address']!='') ? $reservation_record['v_dropoff_address'] : '' }}" />
                                                        </div>
                                                        @else
                                                        <div class="select-field col-xl-7 col-md-7 pl-md-1 mb-1 mb-lg-0">
                                                            <select placeholder="destination point" class="form-control travel_points required" name="i_destination_point_id" id="i_destination_point_id">
                                                            <option value="">Select {{ $dropoff_points['v_city'] }} location</option>
                                                                @foreach($dropoff_points['geo_points'] as $dp) 
                                                                <option <?= ($reservation_record && $reservation_record['v_dropoff_address'] == $dp['v_label'].' '.$dp['v_street1']) ? 'selected="selected"' : ''; ?> value="{{ $dp['v_label'].' '.$dp['v_street1'] }}" service_area="{{$dropoff_points['i_service_area_id']}}">{{ $dp['v_label'].' '.$dp['v_street1'] }}</option>   
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @endif
                                                        
                                                        <div class="select-field col-xl-3 col-md-3 pl-md-1 mb-1 mb-lg-0">
                                                            <select placeholder="travel type" class="form-control travel_type required drop_type" id="ow_dropoff_travel_type" name="i_dropoff_point_type_id">
                                                                <option value="">Travel Type</option>
                                                                @foreach($drop_res_categories as $cat)
                                                                    <option <?= ($reservation_record && $reservation_record['i_dropoff_point_type_id'] == $cat['id']) ? 'selected="selected"' : ''; ?> data-title="{{ $cat['v_label'] }}" value="{{ $cat['id'] }}">{{ $cat['v_label'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <p class="mb-2">Rocket is picking up the traveler from the Airport / Train / Bus / Hotel / Meet </p>
                                                </div>
                                                <div class="col-md-12 flight-box">
                                                    <div class="row no-gutters align-items-cente mb-3 @if($reservation_record && $reservation_record->i_reservation_category_id!=1) d-none @endif airport-flight-box">
                                                        <div class="col-xl-1 col-md-3 d-flex align-items-center">
                                                            <p class="mb-0 flight-title"><strong>If Flight:</strong></p>
                                                        </div>
                                                        <div class="col-xl-2 col-md-3 mt-3 mt-md-0 d-flex align-items-center pl-md-2">
                                                            <p>Airline Name</p>
                                                        </div>
                                                        <div class="col-xl-4 col-md-6 mt-3 mt-md-0">
                                                            <div class="select-field">
                                                                <select placeholder="Airline" class="form-control sel-airlines" name="i_airline_id" id="i_airline_id">
                                                                    @if($resv1_sel_flight_name)
                                                                        <option value="{{ $resv1_sel_flight_name->id }}">{{ $resv1_sel_flight_name->v_airline_name }}</option>
                                                                    @else
                                                                        <option value="">Select Airline</option>
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-5 col-md-6 pl-xl-2">
                                                            <div class="row no-gutters h-100 align-items-center mt-2 mt-xl-0">
                                                                <div class="col-xl-12 col-md-12 mb-3 mb-md-0">
                                                                    <div class="custom-radio-block pr-3">
                                                                        <input type="radio" id="radio10-Domestic" name="e_flight_type" value="Domestic" @if($reservation_record && $reservation_record->e_flight_type=='Domestic') checked @elseif($reservation_record->e_flight_type=='') checked @endif />
                                                                        <label for="radio10-Domestic">Domestic</label>
                                                                    </div>
                                                                    <div class="custom-radio-block">
                                                                        <input type="radio" id="radio1-Domestic" name="e_flight_type" value="International" @if($reservation_record && $reservation_record->e_flight_type=='International') checked @endif />
                                                                        <label for="radio1-Domestic">International </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row no-gutters align-items-center mb-3 flight-num-sec">
                                                        <div class="col-xl-3 col-md-6">
                                                            <p class="flight-no-text">Flight / Train / Bus No.</p>
                                                        </div>
                                                        <div class="col-xl-4 col-md-6 mt-3 mt-md-0">
                                                            <div class="input-field">
                                                                <input type="text" value="{{ ($reservation_record && $reservation_record['v_flight_number'] != '') ? $reservation_record['v_flight_number'] : '' }}" name="v_flight_number" id="v_flight_number" class="form-control" placeholder="" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row no-gutters align-items-center mb-3">
                                                        <div class="col-xl-9 col-md-6 pr-3">
                                                            <p class="start-time-text">Plane / Bus / Train departure or Appt start time is:</p>
                                                        </div>
                                                        <div class="col-xl-3 col-md-6 mt-3 mt-md-0">
                                                            <div class="input-field">
                                                                <input type="text" value="{{ ($reservation_record && $reservation_record['t_flight_time'] != '') ? date('g:i a', strtotime($reservation_record['t_flight_time'])) : '' }}" placeholder="departure time" name="t_flight_time" id="t_flight_time" class="form-control required kt_timepicker2" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row no-gutters align-items-center mb-3">
                                                        <div class="col-xl-9 col-md-6 pr-3">
                                                            <p class="target-pick-drop-text">The earliest time I am comfortable targeting pick up at Airport, Train, etc is:</p>
                                                        </div>
                                                        <div class="col-xl-3 col-md-6 mt-3 mt-md-0">
                                                            <div class="input-field">
                                                                <input type="text" value="{{ ($reservation_record && $reservation_record->t_comfortable_time!='') ? date('g:i a', strtotime($reservation_record->t_comfortable_time)) : '' }}" placeholder="comfortable time" name="t_comfortable_time" id="t_comfortable_time" class="form-control required kt_timepicker1_comfort" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row no-gutters align-items-center mb-3">
                                                        @if($reservation_record['e_shuttle_type']!='Private')
                                                        <div class="col-xl-9 col-md-6 pr-3">
                                                            <p class="shared-service-balance-text">To make the shared service balance based on reservations on this day, I may need to target departure as late as:</p>
                                                        </div>
                                                        <div class="col-xl-3 col-md-6 mt-3 mt-md-0">
                                                            <div class="input-field">
                                                                <input type="text" value="{{ ($reservation_record && $reservation_record['t_target_time'] != '') ? date('g:i A', strtotime($reservation_record['t_target_time'])) : '' }}" placeholder="" name="t_target_time" id="t_target_time" class="form-control" readonly style="background: white; border: none;">  
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if(isset($reservation_record_rt['e_class_type']) && $reservation_record_rt['e_class_type'] == 'RT')
                                        <div class="rocket-info-travel-box mt-10 mb-0">
                                            <div class="row">
                                                <div class="col-xl-6 col-sm-12 form-group">
                                                    <div class="row no-gutters align-items-center">
                                                        <label class="col-md-6 col-form-label"><strong>2nd Date of Travel</strong></label>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control hasDatepicker ml-xl-2" readonly="readonly" value="<?= date('m/d/Y',strtotime($reservation_record_rt['d_travel_date']))?>" placeholder="Return Date" id="d_return_date"/>
                                                        </div>
                                                    </div>
                                                </div>
                                              
                                                <div class="col-md-12 form-group">
                                                    <div class="row no-gutters align-items-center">
                                                        <label class="col-xl-2 col-md-2 col-form-label">Pickup Point</label>
                                                        @if($reservation_record_rt->PickupCity->e_allow_manual_address==1 || $reservation_record_rt['e_shuttle_type']=='Private')
                                                        <div class="col-xl-7 col-md-7 pl-md-1 mb-1 mb-lg-0">
                                                            <input type="text" service_area="{{ $reservation_record_rt->PickupCity->i_service_area_id }}" data-cityId="{{ $reservation_record_rt['i_pickup_city_id'] }}" value="{{ ($reservation_record_rt && $reservation_record_rt['v_pickup_address']!='') ? $reservation_record_rt['v_pickup_address'] : '' }}" name="rt_i_origin_point_id" id="rt_i_origin_point_id" class="form-control" placeholder="Type {{ $pickup_points_rt['v_city'] }} location" />
                                                        </div>
                                                        @else
                                                        <div class="select-field col-xl-7 col-md-7 pl-md-1 mb-1 mb-lg-0">
                                                            <select name="rt_i_origin_point_id" id="rt_i_origin_point_id" class="form-control required travel_points_rt" >
                                                                <option value="">Select {{ $pickup_points_rt['v_city'] }} location</option>
                                                                @foreach($pickup_points_rt['geo_points'] as $dp) 
                                                                <option service_area="{{$pickup_points_rt['i_service_area_id']}}" @if($reservation_record_rt && $reservation_record_rt['v_pickup_address']==$dp['v_label'].' '.$dp['v_street1']) selected="selected" @endif value="{{ $dp['v_label'].' '.$dp['v_street1'] }}">{{ $dp['v_label'].' '.$dp['v_street1'] }}</option>   
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @endif
                                                        <div class="select-field col-xl-3 col-md-3 pl-md-1 mb-1 mb-lg-0">
                                                            <select placeholder="travel type" class="form-control travel_type required pick_type" id="rt_pickup_travel_type" name="rt_i_reservation_category_id">
                                                                <option value="">Travel Type</option>
                                                                @foreach($pick_res_categories_rt as $cat)
                                                                    <option <?= ($reservation_record_rt && $reservation_record_rt['i_reservation_category_id'] == $cat['id']) ? 'selected="selected"' : ''; ?> data-title="{{ $cat['v_label'] }}" value="{{ $cat['id'] }}">{{ $cat['v_label'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <div class="row no-gutters align-items-center">
                                                        <label class="col-xl-2 col-md-2 col-form-label">Drop off point</label>
                                                            @if($reservation_record_rt->DropOffCity->e_allow_manual_address==1 || $reservation_record_rt['e_shuttle_type']=='Private')
                                                            <div class="col-xl-7 col-md-7 pl-md-1 mb-1 mb-lg-0">
                                                                <input type="text" service_area="{{ $reservation_record_rt->DropOffCity->i_service_area_id }}" data-cityId="{{ $reservation_record_rt['i_dropoff_city_id'] }}" value="{{ ($reservation_record_rt && $reservation_record_rt['v_dropoff_address']!='') ? $reservation_record_rt['v_dropoff_address'] : '' }}" name="rt_i_destination_point_id" id="rt_i_destination_point_id" class="form-control" placeholder="Type {{ $dropoff_points_rt['v_city'] }} location" />
                                                            </div>
                                                            @else
                                                            <div class="select-field col-xl-7 col-md-7 pl-md-1 mb-1 mb-lg-0">
                                                                <select name="rt_i_destination_point_id" id="rt_i_destination_point_id" class="form-control required travel_points_rt">
                                                                <option value="">Select {{ $dropoff_points_rt['v_city'] }} location</option>
                                                                    @foreach($dropoff_points_rt['geo_points'] as $pp) 
                                                                    <option service_area="{{$dropoff_points_rt['i_service_area_id']}}" @if($reservation_record_rt && $reservation_record_rt['v_dropoff_address']==$pp['v_label'].' '.$pp['v_street1']) selected="selected" @endif value="{{ $pp['v_label'].' '.$pp['v_street1'] }}">{{ $pp['v_label'].' '.$pp['v_street1'] }}</option>   
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            @endif
                                                            <div class="select-field col-xl-3 col-md-3 pl-md-1 mb-1 mb-lg-0">
                                                                <select placeholder="travel type" class="form-control travel_type required drop_type" id="rt_dropoff_travel_type" name="rt_i_dropoff_point_type_id">
                                                                    <option value="">Travel Type</option>
                                                                    @foreach($drop_res_categories_rt as $cat)
                                                                        <option <?= ($reservation_record_rt && $reservation_record_rt['i_dropoff_point_type_id'] == $cat['id']) ? 'selected="selected"' : ''; ?> data-title="{{ $cat['v_label'] }}" value="{{ $cat['id'] }}">{{ $cat['v_label'] }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <p class="mb-2">Rocket is picking up the traveler from the Airport / Train / Bus / Hotel / Meet </p>
                                                </div>
                                                <div class="col-md-12 flight-box">
                                                    <div class="row no-gutters align-items-cente mb-3 @if($reservation_record_rt && $reservation_record_rt->i_reservation_category_id !=1) d-none @endif airport-flight-box">
                                                        <div class="col-xl-1 col-md-3 d-flex align-items-center">
                                                            <p class="mb-0 flight-title"><strong>If Flight:</strong></p>
                                                        </div>
                                                        <div class="col-xl-2 col-md-3 mt-3 mt-md-0 d-flex align-items-center pl-md-2">
                                                            <p>Airline Name</p>
                                                        </div>
                                                        <div class="col-xl-4 col-md-6 mt-3 mt-md-0">
                                                            <div class="select-field">
                                                                <select name="rt_i_airline_id" id="rt_i_airline_id" class="form-control sel-airlines">
                                                                    
                                                                    @if($resv2_sel_flight_name)
                                                                    <option value="{{ $resv2_sel_flight_name->id }}">{{ $resv2_sel_flight_name->v_airline_name }}</option>
                                                                    @else
                                                                    <option value="">Select Airline</option>
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-5 col-md-6 pl-xl-2">
                                                            <div class="row no-gutters h-100 align-items-center mt-2 mt-xl-0">
                                                                <div class="col-xl-12 col-md-12 mb-3 mb-md-0">
                                                                    <div class="custom-radio-block pr-3">
                                                                        <input type="radio" id="radio11-Domestic" name="rt_e_flight_type" value="Domestic" @if($reservation_record_rt && $reservation_record_rt->e_flight_type=='Domestic') checked @elseif($reservation_record_rt->e_flight_type=='') checked @endif />
                                                                        <label for="radio11-Domestic">Domestic</label>
                                                                    </div>
                                                                    <div class="custom-radio-block">
                                                                        <input type="radio" id="radio12-Domestic" name="rt_e_flight_type" value="International" @if($reservation_record_rt && $reservation_record_rt->e_flight_type=='International') checked @endif />
                                                                        <label for="radio12-Domestic">International </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row no-gutters align-items-center mb-3 flight-num-sec">
                                                        <div class="col-xl-3 col-md-6">
                                                            <p class="flight-no-text">Flight / Train / Bus No.</p>
                                                        </div>
                                                        <div class="col-xl-4 col-md-6 mt-3 mt-md-0">
                                                            <div class="input-field">
                                                                <input type="text" value="{{ ($reservation_record_rt && $reservation_record_rt->v_flight_number!='') ? $reservation_record_rt->v_flight_number : '' }}" name="rt_v_flight_number" id="rt_v_flight_number" class="form-control" placeholder="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row no-gutters align-items-center mb-3">
                                                        <div class="col-xl-9 col-md-6 pr-3">
                                                            <p class="start-time-text">Plane / Bus / Train departure or Appt start time is:</p>
                                                        </div>
                                                        <div class="col-xl-3 col-md-6 mt-3 mt-md-0">
                                                            <div class="input-field">
                                                                <input type="text" value="{{ ($reservation_record_rt && $reservation_record_rt->t_flight_time!='') ? date('g:i a', strtotime($reservation_record_rt->t_flight_time)) : '' }}" name="rt_t_flight_time" id="rt_t_flight_time" class="form-control required kt_timepicker2" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row no-gutters align-items-center mb-3">
                                                        <div class="col-xl-9 col-md-6 pr-3">
                                                            <p class="target-pick-drop-text">The earliest time I am comfortable targeting pick up at Airport, Train, etc is:</p>
                                                        </div>
                                                        <div class="col-xl-3 col-md-6 mt-3 mt-md-0">
                                                            <div class="input-field">
                                                                <input type="text" value="{{ ($reservation_record_rt && $reservation_record_rt->t_comfortable_time!='') ? date('g:i a', strtotime($reservation_record_rt->t_comfortable_time)) : '' }}" name="rt_t_comfortable_time" id="rt_t_comfortable_time" class="form-control required kt_timepicker1_comfort" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row no-gutters align-items-center mb-3">
                                                        @if($reservation_record['e_shuttle_type']!='Private')
                                                        <div class="col-xl-9 col-md-6 pr-3">
                                                            <p class="shared-service-balance-text">To make the shared service balance based on reservations on this day, I may need to target departure as late as:</p>
                                                        </div>
                                                        <div class="col-xl-3 col-md-6 mt-3 mt-md-0">
                                                            <div class="input-field">
                                                                <input type="text" value="{{ ($reservation_record_rt && $reservation_record_rt->t_target_time!='') ? date('g:i A', strtotime($reservation_record_rt->t_target_time)) : '' }}" name="rt_t_target_time" id="rt_t_target_time" class="form-control" style="background: white; border: none;" readonly="readonly"/>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="rocket-info__next mt-4 text-right">
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
    
@section('custom_js')
<script>
    function setTravelDirections() {
        var ow_pickup_travel_type = "{{ (isset($reservation_record) && $reservation_record['i_reservation_category_id']!='') ? 1 : 0 }}";
        var ow_dropoff_travel_type = "{{ (isset($reservation_record) && $reservation_record['i_dropoff_point_type_id']!='') ? 1 : 0 }}";
        var rt_pickup_travel_type = "{{ (isset($reservation_record_rt) && $reservation_record['i_reservation_category_id']!='') ? 1 : 0 }}";
        var rt_dropoff_travel_type = "{{ (isset($reservation_record_rt) && $reservation_record['i_dropoff_point_type_id']!='') ? 1 : 0 }}";
        if(ow_pickup_travel_type==1) {
            $('#ow_pickup_travel_type').trigger('change');
        }
        if(ow_dropoff_travel_type==1) {
            $('#ow_dropoff_travel_type').trigger('change');
        }
        if(rt_pickup_travel_type==1) {
            $('#rt_pickup_travel_type').trigger('change');
        }
        if(rt_dropoff_travel_type==1) {
            $('#rt_dropoff_travel_type').trigger('change');
        }
    }
    
    $(document).ready(function() {

        KTReservationFrontend.init();
        KTReservationFrontend.travelDetails();

        setTravelDirections();

        if($("input[name=i_origin_point_id]").length > 0) {
            var pickurl = SITE_URL+'get-geopoints/'+$("input[name=i_origin_point_id]").attr('data-cityId');
            $('#i_origin_point_id').autocomplete({
                source: pickurl
            });
        }

        if($("input[name=i_destination_point_id]").length > 0) {
            var dropurl = SITE_URL+'get-geopoints/'+$("input[name=i_destination_point_id]").attr('data-cityId');
            $('#i_destination_point_id').autocomplete({
                source: dropurl
            });
        }

        if($("input[name=rt_i_origin_point_id]").length > 0) {
            var pickurl = SITE_URL+'get-geopoints/'+$("input[name=rt_i_origin_point_id]").attr('data-cityId');
            $('#rt_i_origin_point_id').autocomplete({
                source: pickurl
            });
        }

        if($("input[name=rt_i_destination_point_id]").length > 0) {
            var dropurl = SITE_URL+'get-geopoints/'+$("input[name=rt_i_destination_point_id]").attr('data-cityId');
            $('#rt_i_destination_point_id').autocomplete({
                source: dropurl
            });
        }

        $(".kt_timepicker1").timepicker({
            minuteStep: 5,
            defaultTime: '',
        });
        $(".kt_timepicker2").timepicker({
            minuteStep: 1,
            defaultTime: '',
        }).on('changeTime.timepicker', function(e) {
            var h= e.time.hours;
            var m= e.time.minutes;
            var mer= e.time.meridian;

            $(this).closest('.flight-box').find('.kt_timepicker1_comfort').timepicker('setTime',h+':'+m+' '+mer);
        });

        $(".kt_timepicker1_comfort").timepicker({
            minuteStep: 1,
            defaultTime: '',
        }).on('changeTime.timepicker', function(e) {    
            var hp= e.time.hours;
            var mp= e.time.minutes;
            var merp= e.time.meridian;

            var full_comf_time = hp+":"+mp+" "+merp;
            //convert hours into minutes
        
            var h = $(this).closest('.flight-box').find('.kt_timepicker2').data('timepicker').hour;
            var m = $(this).closest('.flight-box').find('.kt_timepicker2').data('timepicker').minute;
            var mer = $(this).closest('.flight-box').find('.kt_timepicker2').data('timepicker').meridian;
            var full_flight_time = h+":"+m+" "+mer;
            
            var nr_dt = $(this).closest('.rocket-info-travel-box').find('.hasDatepicker').val();
            
            var fromdt=nr_dt+" "+full_flight_time;   //"2013/05/29 12:30 PM";
            var confdt=nr_dt+" "+full_comf_time;   //"2013/05/29 12:30 PM";

            // var comf_dt_time = new Date(Date.parse(confdt));
            // var minus_1_hr = new Date(Date.parse(fromdt));
            // var plus_1_hr = new Date(Date.parse(fromdt));
            // minus_1_hr.setHours(minus_1_hr.getHours() - 1);
            // plus_1_hr.setHours(plus_1_hr.getHours() + 1);

            // if(comf_dt_time > plus_1_hr || comf_dt_time < minus_1_hr){
            //     $(this).closest('.flight-box').find('.kt_timepicker1_comfort').timepicker('setTime',h+':'+m+' '+mer);
            // }

            var comf_dt_time = new Date(Date.parse(confdt));
            var flight_dt_time = new Date(Date.parse(fromdt));

            var myid = $(this).attr('id');

            var direction = localStorage.getItem(myid+"_travel_direction");
                
            if(direction=='Pick') {
                if(comf_dt_time < flight_dt_time){
                    $(this).closest('.flight-box').find('.kt_timepicker1_comfort').timepicker('setTime',h+':'+m+' '+mer);
                }
            } else {
                if(comf_dt_time > flight_dt_time){
                    $(this).closest('.flight-box').find('.kt_timepicker1_comfort').timepicker('setTime',h+':'+m+' '+mer);
                }
            }
        });

        $('.sel-airlines').select2({
            tags: true,
            tokenSeparators: [",", " "],
            delay: 250,
            placeholder:"Please type to search airline name",
            ajax: {
                url: SITE_URL + "get-airlines",
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

        $('.sel-airlines').on('select2:open',function(e){
            setTimeout(function(){ 
                $('.select2-results__message').text("Please type to search airline name");
             }, 1000);
        });

        $('#i_origin_point_id').on('change', function () {
            if($('#rt_i_destination_point_id').length > 0) {
                var value = $(this).val();
                $('#rt_i_destination_point_id').val(value);
                $('#rt_i_destination_point_id').trigger('change');
            }
        });

        $('#i_destination_point_id').on('change', function () {
            if($('#rt_i_destination_point_id').length > 0) {
                var value = $(this).val();
                $('#rt_i_origin_point_id').val(value);
                $('#rt_i_origin_point_id').trigger('change');
            }
        });

        $('#ow_pickup_travel_type').on('change',function(){
            if($('#rt_dropoff_travel_type').length > 0) {
                var value = $(this).val();
                $('#rt_dropoff_travel_type').val(value);
                $('#rt_dropoff_travel_type').trigger('change');
            }
        });

        $('#ow_dropoff_travel_type').on('change',function(){
            if($('#rt_pickup_travel_type').length > 0) {
                var value = $(this).val();
                $('#rt_pickup_travel_type').val(value);
                $('#rt_pickup_travel_type').trigger('change');
            }
        });

        $('#t_comfortable_time').on('change', function() {
            var time = $(this).val();
            var d_depart_date = $('#d_depart_date').val();
            var pic_up_service_area_id = $('option:selected', '#i_origin_point_id').attr('service_area');
            var drpoOff_service_area_id = $('option:selected', '#i_destination_point_id').attr('service_area');
            var shuttle_type = "{{ isset($reservation_record['e_shuttle_type']) ? $reservation_record['e_shuttle_type'] : '' }}";
            var direction = localStorage.getItem("t_comfortable_time_travel_direction");

            if(typeof pic_up_service_area_id == "undefined") {
                pic_up_service_area_id = $('#i_origin_point_id').attr('service_area');
            }
            if(typeof drpoOff_service_area_id == "undefined") {
                drpoOff_service_area_id = $('#i_destination_point_id').attr('service_area');
            }
            
            
            if (shuttle_type!="Private" && typeof drpoOff_service_area_id != "undefined" && typeof pic_up_service_area_id != "undefined" && typeof direction != "undefined") {
                $.ajax({
                    url : SITE_URL + "target_departure",
                    method: 'POST',
                    data: {'time':time,'d_depart_date':d_depart_date,'i_origin_service_area_id':pic_up_service_area_id,'i_dest_service_area_id':drpoOff_service_area_id,'direction':direction},
                    success: function (data) {
                        
                        if(data.status == 'TRUE') {
                            $('#t_target_time').val(data.t_target_time);
                        }
                    }
                })
            }

        });

        $('#rt_t_comfortable_time').on('change', function() {
            var time_rt = $(this).val();
            var d_return_date = $('#d_return_date').val();
            var pic_up_service_area_id_rt = $('option:selected', '#rt_i_origin_point_id').attr('service_area');
            var drpoOff_service_area_id_rt = $('option:selected', '#rt_i_destination_point_id').attr('service_area');
            var shuttle_type = "{{ isset($reservation_record['e_shuttle_type']) ? $reservation_record['e_shuttle_type'] : '' }}";
            var direction = localStorage.getItem("rt_t_comfortable_time_travel_direction");

            if(typeof pic_up_service_area_id_rt == "undefined") {
                pic_up_service_area_id_rt = $('#rt_i_origin_point_id').attr('service_area');
            }
            if(typeof drpoOff_service_area_id_rt == "undefined") {
                drpoOff_service_area_id_rt = $('#rt_i_destination_point_id').attr('service_area');
            }

            if (shuttle_type!="Private" && typeof drpoOff_service_area_id_rt != "undefined" && typeof pic_up_service_area_id_rt != "undefined" && typeof direction != "undefined") {
                $.ajax({
                    url : SITE_URL + "target_departure_rt",
                    method: 'POST',
                    data: {'time':time_rt,'d_return_date':d_return_date,'i_origin_service_area_id':pic_up_service_area_id_rt,'i_dest_service_area_id':drpoOff_service_area_id_rt,'direction':direction},
                    success: function (data) {
                        
                        if(data.status == 'TRUE') {
                            $('#rt_t_target_time').val(data.t_target_time);
                        }
                    }
                })
            }

        });

        var resv_id = "{{ ((request()->route('id') != '') ? request()->route('id') : '') }}";
        if(resv_id != '') {
            var url = SITE_URL + 'apply-coupon/' + resv_id;
        } else {
            var url = SITE_URL + 'apply-coupon';
        }
        $(document).on('click', '#apply-coupon',function(e){
            e.preventDefault();
            var v_discount_code  = $('#v_discount_code').val();
            if($.trim(v_discount_code)!='') {
                var data = {v_discount_code: v_discount_code}
                $.post(url, data, function (response) {
                    if (response.status == 'TRUE') {
                        $('.show_discount').html(response.discount);
                        $('.show_discount').closest('p').removeClass('d-none');
                        $('.show_total_total').html(response.total);
                    } else {
                        $('.show_discount').closest('p').addClass('d-none');
                        $('.show_total_total').html(response.total);
                    }
                });
            }
        });
                
    });
</script>
@stop

@stop
        
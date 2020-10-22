@extends('frontend.layouts.default')
@section('content')
<?php
    $luggage_value_array = Arr::pluck($reservation_luggage_info, 'i_value','i_sys_luggage_id');  
    $sum_of_luggage_animal = $reservation_luggage_info->sum('d_price');
    $luggage_value_array_rt = $sum_of_luggage_animal_rt = "";
    if(isset($reservation_record_rt['e_class_type']) && $reservation_record_rt['e_class_type'] == 'RT'){
        $luggage_value_array_rt = Arr::pluck($reservation_luggage_info_rt, 'i_value','i_sys_luggage_id');
        $sum_of_luggage_animal_rt = $reservation_luggage_info_rt->sum('d_price');
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
                                
                                <a class="nav-link completed {{ (isset($reservation_record) && $reservation_record['e_shuttle_type'] == 'Private') ? 'd-none' : '' }}" id="runs-tab" href="javascript:;" role="tab" aria-controls="runs" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'display-line-runs/'.request()->route('id') : 'display-line-runs') }}">Display Line Runs</a>
                                
                                <a class="nav-link completed" id="passenger-information-tab" href="javascript:;" role="tab" aria-controls="passenger-information" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'passenger-information/'.request()->route('id') : 'passenger-information') }}">Passenger Information</a>
                                
                                <a class="nav-link active" id="luggage-animals-tab" href="javascript:;" role="tab" aria-controls="luggage-animals" aria-selected="true" data-action="{{ ((request()->route('id') != '') ? 'luggage-animals/'.request()->route('id') : 'luggage-animals') }}">Luggage and Animals</a>

                                <a class="nav-link" id="travel-tab" href="javascript:;" role="tab" aria-controls="travel" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'travel-details/'.request()->route('id') : 'travel-details') }}">Travel Details</a>
                                
                                <a class="nav-link disable {{ (isset($reservation_record) && $reservation_record['e_shuttle_type'] == 'Private') ? 'd-none' : '' }}" id="confirm-tab" href="javascript:;" role="tab" aria-controls="confirm" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'currently-assigned-line-runs/'.request()->route('id') : 'currently-assigned-line-runs') }}">Currently Assigned Line Run</a>
                                @if((request()->route('id') != '' && !($paymentStatus)) || request()->route('id') == '')
                                    <a class="nav-link disable" id="reservation-summary-tab" href="javascript:;" role="tab" aria-controls="reservation-summary" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'reservation-summary/'.request()->route('id') : 'reservation-summary') }}">Reservation Summary</a>

                                    <a class="nav-link disable {{ (isset($reservation_record) && $reservation_record['e_shuttle_type'] == 'Private') ? 'd-none' : '' }}" id="payment-tab" href="javascript:;" role="tab" aria-controls="payment" aria-selected="false"data-action="{{ ((request()->route('id') != '') ? 'reservation-payment/'.request()->route('id') : 'reservation-payment') }}">Payment</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="rocket-info-right">
                            <div class="tab-content" id="v-tabContent">
                                <!-- 4 -->
                                <div class="tab-pane fade show active" id="luggage-animals" role="tabpanel" aria-labelledby="luggage-animals-tab">
                                    <div class="rocket-info-details rocket-info-luggage-animals quote-detail">
                                        <h3 class="rocket-info__title"> Luggage and Animals </h3>
                                        <form action="{{ ((request()->route('id') != '') ? SITE_URL.'luggage-animals/'.request()->route('id') : SITE_URL.'luggage-animals') }}" id="frontend_luggage_animals" method="POST">
                                        <input type="hidden" id="leg1_tot_passengers" value="{{ $reservation_record->i_total_num_passengers }}" />    
                                        <input type="hidden" name="redirect_url" value="{{ ((request()->route('id') != '') ? 'travel-details/'.request()->route('id') : 'travel-details') }}" id="redirect_url">
                                            <div class="rocket-info__four">
                                                <h4 class="rocket-info__title pb-2"> 1st Date of Travel </h4>
                                                <div class="form-wrapper">
                                                    <div class="row form-bottom">
                                                        <div class="col-sm-12">
                                                            <!-- payment done or not based on that view or select -->
                                                            @if((request()->route('id') != '' && !($paymentStatus)) || request()->route('id') == '')
                                                                <div class="counter-from">
                                                                    <div class="counter-block counter-from-bg row m-0">
                                                                        <div class="counter-title d-flex">
                                                                            <p class="m-0 mr-2">Number of luggages </p>
                                                                            <input class="form-control i_number_of_luggages" type="text" name="i_number_of_luggages" placeholder="" value="<?= (isset($reservation_record['i_number_of_luggages'])) ? $reservation_record['i_number_of_luggages'] : '0'; ?>"  readonly id="total_luggages" style="background: lightgray;">
                                                                        </div>
                                                                        @if($reservation_record['e_shuttle_type'] == 'Shared')
                                                                            <div class="btn-block d-md-flex d-none align-items-center justify-content-center ">
                                                                                <p>CHARGE</p>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    @foreach($sys_luggage_def as $luggage_key => $luggage_val)
                                                                        <div class="counter-block row m-0">
                                                                            <div class="{{ ($reservation_record['e_shuttle_type']=='Shared') ? 'counter-title' : 'counter-title-private' }}">
                                                                                <p>{!! $luggage_val['v_name'] ? $luggage_val['v_name'] : '' !!} 
                                                                                @if($reservation_record['e_shuttle_type']=='Shared')
                                                                                <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="{{$luggage_val['v_desc']}}"></span>
                                                                                @endif
                                                                                </p>
                                                                            </div>
                                                                            <div class="counter-dropdown">
                                                                                <div class="select-field">
                                                                                
                                                                                    @if($luggage_val['id'] == 1 || $luggage_val['id'] == 2 || $luggage_val['id'] == 3 || $luggage_val['id'] == 4)
                                                                                        <select data-per-traveller="{{ $luggage_val['i_is_per_traveller_free'] }}"  id="luggage_dropdown_{{$luggage_key}}" class="luggage_dropdown" name="sys_luggage_{{$luggage_key}}">
                                                                                            @for($i=0; $i <= 20; $i++)
                                                                                                <option value="{{$i}}" <?= (isset($luggage_value_array[$luggage_val['id']]) && ($luggage_value_array[$luggage_val['id']] == $i)) ? "selected='selected'" : ""; ?>> {{$i}} </option>
                                                                                            @endfor
                                                                                        </select>
                                                                                    @elseif($luggage_val['id'] == 6 || $luggage_val['id'] == 7 || $luggage_val['id'] == 8 || $luggage_val['id'] == 12 || $luggage_val['id'] == 13 || $luggage_val['id'] == 14 || $luggage_val['id'] == 15 || $luggage_val['id'] == 16)
                                                                                        <select data-per-traveller="{{ $luggage_val['i_is_per_traveller_free'] }}" id="luggage_dropdown_{{$luggage_key}}" class="luggage_dropdown" name="sys_luggage_{{$luggage_key}}">
                                                                                            @for($i=0; $i <= 10; $i++)
                                                                                                <option value="{{$i}}" <?= (isset($luggage_value_array[$luggage_val['id']]) && ($luggage_value_array[$luggage_val['id']] == $i)) ? "selected='selected'" : ""; ?>> {{$i}} </option>
                                                                                            @endfor
                                                                                        </select>
                                                                                    @elseif($luggage_val['id'] == 10)
                                                                                        <select data-per-traveller="{{ $luggage_val['i_is_per_traveller_free'] }}" id="luggage_dropdown_{{$luggage_key}}" class="luggage_dropdown" name="sys_luggage_{{$luggage_key}}">
                                                                                            <option value="0" <?= (isset($luggage_value_array[$luggage_val['id']]) && $luggage_value_array[$luggage_val['id']] == 0) ? 'selected=""' : ''; ?>> 0 </option>
                                                                                            <option value="1" <?= (isset($luggage_value_array[$luggage_val['id']]) && $luggage_value_array[$luggage_val['id']] == 1) ? 'selected=""' : ''; ?>> 1 </option>
                                                                                        </select>
                                                                                    @elseif($luggage_val['id'] == 9 || $luggage_val['id'] == 11)
                                                                                        <select data-per-traveller="{{ $luggage_val['i_is_per_traveller_free'] }}" id="luggage_dropdown_{{$luggage_key}}" class="luggage_dropdown" name="sys_luggage_{{$luggage_key}}">
                                                                                            <option value="0" <?= (isset($luggage_value_array[$luggage_val['id']]) && $luggage_value_array[$luggage_val['id']] == 0) ? 'selected=""' : ''; ?>> 0 </option>
                                                                                            <option value="1" <?= (isset($luggage_value_array[$luggage_val['id']]) && $luggage_value_array[$luggage_val['id']] == 1) ? 'selected=""' : ''; ?>> 1 </option>
                                                                                            <option value="2" <?= (isset($luggage_value_array[$luggage_val['id']]) && $luggage_value_array[$luggage_val['id']] == 2) ? 'selected=""' : ''; ?>> 2 </option>
                                                                                        </select>
                                                                                    @elseif($luggage_val['id'] == 5)
                                                                                        <select data-per-traveller="{{ $luggage_val['i_is_per_traveller_free'] }}" class="personel_luggage_info luggage_dropdown" id="luggage_dropdown_{{$luggage_key}}" name="sys_luggage_{{$luggage_key}}">
                                                                                            @for($i = 0; $i <= $travellerCount; $i++)
                                                                                                <option value="{{$i}}" <?= (isset($luggage_value_array[$luggage_val['id']]) && ($luggage_value_array[$luggage_val['id']] == $i)) ? "selected='selected'" : ""; ?>> {{$i}} </option>
                                                                                            @endfor     
                                                                                        </select>
                                                                                    @elseif($luggage_val['id'] == 17)
                                                                                        <select data-per-traveller="{{ $luggage_val['i_is_per_traveller_free'] }}" id="luggage_dropdown_{{$luggage_key}}" class="luggage_dropdown" name="sys_luggage_{{$luggage_key}}">
                                                                                            <option value="0"> 0 </option>
                                                                                        </select>
                                                                                    @endif
                                                                                </div>
                                                                                <input type="hidden" name="d_unit_price_{{ $luggage_key }}" value="{!! $luggage_val['d_unit_price'] ? $luggage_val['d_unit_price'] : '' !!}">
                                                                                <input type="hidden" name="i_sys_luggage_{{ $luggage_key }}" value="{{$luggage_val['id']}}" >
                                                                            </div>
                                                                            @if($reservation_record['e_shuttle_type']=='Shared')
                                                                            <div class="btn-block d-flex align-items-center justify-content-center">
                                                                                <span  class="btn btn-sm btn-yellow btn-yellow-display luggage_charge" data-value="{!! $luggage_val['d_unit_price'] ? $luggage_val['d_unit_price'] : '0' !!}">${!! $luggage_val['d_unit_price'] ? $luggage_val['d_unit_price'] : '' !!} each</span> 
                                                                            </div>
                                                                            @endif
                                                                        </div>
                                                                    @endforeach
                                                                    <div class="counter-block counter-from-bg row m-0">
                                                                        <div class="counter-title d-flex">
                                                                            <p class="m-0 mr-2">Number of Pets</p>
                                                                            <input class="form-control i_num_pets" type="text" name="i_num_pets" placeholder="" value="<?= (isset($reservation_record['i_num_pets'])) ? $reservation_record['i_num_pets'] : '0'; ?>" readonly id="tatal_pets" style="background: lightgray;">
                                                                        </div>
                                                                        @if($reservation_record['e_shuttle_type']=='Shared')
                                                                        <div class="btn-block d-md-flex d-none align-items-center justify-content-center">
                                                                            <p>CHARGE</p>
                                                                        </div>
                                                                        @endif
                                                                    </div>
                                                                    @foreach($sys_animal_def as $animal_key => $animal_val)
                                                                        <input type="hidden" name="i_sys_pet_{{ $animal_key }}" value="{{$animal_val['id']}}" >
                                                                        <div class="counter-block row m-0">
                                                                            <div class="{{ ($reservation_record['e_shuttle_type']=='Shared') ? 'counter-title' : 'counter-title-private' }}">
                                                                                <p>{!! $animal_val['v_name'] ? $animal_val['v_name'] : '' !!} 
                                                                                @if($reservation_record['e_shuttle_type']=='Shared')
                                                                                <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="{{$animal_val['v_desc']}}"></span>
                                                                                @endif
                                                                                </p>
                                                                            </div>
                                                                            <div class="counter-dropdown">
                                                                                <div class="custom-checkbox mr-3 mr-lg-0">
                                                                                    <input type="checkbox" class="is_pet_available" id="checkbox{{$animal_key}}" value="{{$animal_val['d_unit_price'] ? $animal_val['d_unit_price'] : '0'}}" name="fare_amt_pet_{{$animal_key}}" <?= (isset($luggage_value_array[$animal_val['id']])) ? "checked='true'" : "" ?>>
                                                                                    <label for="checkbox{{$animal_key}}">&nbsp;</label>
                                                                                </div>
                                                                            </div>
                                                                            @if($reservation_record['e_shuttle_type'] == 'Shared')
                                                                                <div class="btn-block d-flex align-items-center justify-content-center">
                                                                                    <span class="btn btn-sm btn-yellow btn-yellow-display pets_charge" data-value="{!! $animal_val['d_unit_price'] ? $animal_val['d_unit_price'] : '0' !!}">${!! $animal_val['d_unit_price'] ? $animal_val['d_unit_price'] : '0' !!}</span>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    @endforeach
                                                                    
                                                                </div>
                                                            @else
                                                                <div class="counter-from">
                                                                    <div class="counter-block counter-from-bg row m-0">
                                                                        <div class="counter-title d-flex">
                                                                            <p class="m-0 mr-2">Number of luggages </p>
                                                                            <input class="form-control i_number_of_luggages" type="text" value="<?= (isset($reservation_record['i_number_of_luggages'])) ? $reservation_record['i_number_of_luggages'] : '0'; ?>"  readonly id="total_luggages" style="background: lightgray;">
                                                                        </div>
                                                                        @if($reservation_record['e_shuttle_type'] == 'Shared' && $reservation_record['i_number_of_luggages'] > 0)
                                                                            <div class="btn-block d-md-flex d-none align-items-center justify-content-center ">
                                                                                <p>CHARGE</p>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    @foreach($sys_luggage_def as $luggage_key => $luggage_val)
                                                                        @if(isset($luggage_value_array[$luggage_val['id']]))
                                                                            <div class="counter-block row m-0">
                                                                                <div class="{{ ($reservation_record['e_shuttle_type']=='Shared') ? 'counter-title' : 'counter-title-private' }}">
                                                                                    <p>{!! $luggage_val['v_name'] ? $luggage_val['v_name'] : '' !!} 
                                                                                    @if($reservation_record['e_shuttle_type']=='Shared')
                                                                                        <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="{{$luggage_val['v_desc']}}"></span>
                                                                                    @endif
                                                                                    </p>
                                                                                </div>
                                                                                <div class="counter-dropdown">
                                                                                    <span> {{ $luggage_value_array[$luggage_val['id']] }} </span>
                                                                                </div>
                                                                                @if($reservation_record['e_shuttle_type']=='Shared')
                                                                                    <div class="btn-block d-flex align-items-center justify-content-center">
                                                                                        <span  class="btn btn-sm btn-yellow btn-yellow-display luggage_charge">${!! $luggage_val['d_unit_price'] ? $luggage_val['d_unit_price'] : '' !!} each</span> 
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                    <div class="counter-block counter-from-bg row m-0">
                                                                        <div class="counter-title d-flex">
                                                                            <p class="m-0 mr-2">Number of Pets</p>
                                                                            <input class="form-control i_num_pets" type="text" value="<?= (isset($reservation_record['i_num_pets'])) ? $reservation_record['i_num_pets'] : '0'; ?>" readonly id="tatal_pets" style="background: lightgray;">
                                                                        </div>
                                                                        @if($reservation_record['e_shuttle_type'] == 'Shared' && $reservation_record['i_num_pets'] > 0)
                                                                        <div class="btn-block d-md-flex d-none align-items-center justify-content-center">
                                                                            <p>CHARGE</p>
                                                                        </div>
                                                                        @endif
                                                                    </div>
                                                                    @foreach($sys_animal_def as $animal_key => $animal_val)
                                                                        @if(isset($luggage_value_array[$animal_val['id']]))
                                                                            <div class="counter-block row m-0">
                                                                                <div class="{{ ($reservation_record['e_shuttle_type'] == 'Shared') ? 'counter-title' : 'counter-title-private' }}">
                                                                                    <p>{!! $animal_val['v_name'] ? $animal_val['v_name'] : '' !!} 
                                                                                    @if($reservation_record['e_shuttle_type']=='Shared')
                                                                                    <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="{{$animal_val['v_desc']}}"></span>
                                                                                    @endif
                                                                                    </p>
                                                                                </div>
                                                                                <div class="counter-dropdown"></div>
                                                                                @if($reservation_record['e_shuttle_type'] == 'Shared')
                                                                                    <div class="btn-block d-flex align-items-center justify-content-center">
                                                                                        <span class="btn btn-sm btn-yellow btn-yellow-display pets_charge">${!! $animal_val['d_unit_price'] ? $animal_val['d_unit_price'] : '0' !!}</span>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @if(isset($reservation_record_rt['e_class_type']) && $reservation_record_rt['e_class_type'] == 'RT')
                                            <input type="hidden" id="leg2_tot_passengers" value="{{ $reservation_record_rt->i_total_num_passengers }}" />
                                            <div class="rocket-info__four">
                                                <div class="d-flex align-items-center justify-content-between flex-sm-nowrap flex-wrap">
                                                    <h4 class="rocket-info__title pb-2">2nd Date of Travel</h4>
                                                    @if((request()->route('id') != '' && !($paymentStatus)) || request()->route('id') == '')
                                                        <a href="javascript:void(0);" class="btn btn-sm btn-red mb-2" id="sameAsAbove">Same as above</a>
                                                    @endif
                                                </div>
                                                <div class="form-wrapper">
                                                    
                                                    <div class="row form-bottom">
                                                        <div class="col-sm-12">
                                                            <!-- payment done or not based on that view or select -->
                                                            @if((request()->route('id') != '' && !($paymentStatus)) || request()->route('id') == '')
                                                                <div class="counter-from">
                                                                    <div class="counter-block counter-from-bg row m-0">
                                                                        <div class="counter-title d-flex">
                                                                            <p class="m-0 mr-2">Number of luggages </p>
                                                                            <input class="form-control i_number_of_luggages_rt" type="text" name="i_number_of_luggages_rt" placeholder="" value="<?= (isset($reservation_record_rt['i_number_of_luggages'])) ? $reservation_record_rt['i_number_of_luggages'] : '0'; ?>"  readonly id="total_luggages_rt" style="background: lightgray;">
                                                                        </div>
                                                                        @if($reservation_record['e_shuttle_type']=='Shared')
                                                                        <div class="btn-block d-md-flex d-none align-items-center justify-content-center">
                                                                            <p>CHARGE</p>
                                                                        </div>
                                                                        @endif
                                                                    </div>
                                                                    @foreach($sys_luggage_def as $luggage_key_rt => $luggage_val_rt)
                                                                        <div class="counter-block row m-0">
                                                                            <div class="{{ ($reservation_record['e_shuttle_type']=='Shared') ? 'counter-title' : 'counter-title-private' }}">
                                                                                <p>{!! $luggage_val_rt['v_name'] ? $luggage_val_rt['v_name'] : '' !!} 
                                                                                @if($reservation_record['e_shuttle_type']=='Shared')
                                                                                <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="{{$luggage_val_rt['v_desc']}}"></span>@endif
                                                                                </p>
                                                                            </div>
                                                                            <div class="counter-dropdown">
                                                                                <div class="select-field">
                                                                                    @if($luggage_val_rt['id'] == 1 || $luggage_val_rt['id'] == 2 || $luggage_val_rt['id'] == 3 || $luggage_val_rt['id'] == 4)
                                                                                        <select data-per-traveller="{{ $luggage_val_rt['i_is_per_traveller_free'] }}" id="luggage_dropdown_rt_{{$luggage_key_rt}}" class="luggage_dropdown_rt" name="sys_luggage_rt_{{$luggage_key_rt}}">
                                                                                            @for($i=0; $i <= 20; $i++)
                                                                                                <option value="{{$i}}" <?= (isset($luggage_value_array_rt[$luggage_val_rt['id']]) && ($luggage_value_array_rt[$luggage_val_rt['id']] == $i)) ? "selected='selected'" : ""; ?>> {{$i}} </option>
                                                                                            @endfor
                                                                                        </select>
                                                                                    @elseif($luggage_val_rt['id'] == 6 || $luggage_val_rt['id'] == 7 || $luggage_val_rt['id'] == 8 || $luggage_val_rt['id'] == 12 || $luggage_val_rt['id'] == 13 || $luggage_val_rt['id'] == 14 || $luggage_val_rt['id'] == 15 || $luggage_val_rt['id'] == 16)
                                                                                        <select data-per-traveller="{{ $luggage_val_rt['i_is_per_traveller_free'] }}"  id="luggage_dropdown_rt_{{$luggage_key_rt}}" class="luggage_dropdown_rt" name="sys_luggage_rt_{{$luggage_key_rt}}">
                                                                                            @for($i=0; $i <= 10; $i++)
                                                                                                <option value="{{$i}}" <?= (isset($luggage_value_array_rt[$luggage_val_rt['id']]) && ($luggage_value_array_rt[$luggage_val_rt['id']] == $i)) ? "selected='selected'" : ""; ?>> {{$i}} </option>
                                                                                            @endfor
                                                                                        </select>
                                                                                    @elseif($luggage_val_rt['id'] == 10)
                                                                                    
                                                                                        <select data-per-traveller="{{ $luggage_val_rt['i_is_per_traveller_free'] }}"  id="luggage_dropdown_rt_{{$luggage_key_rt}}" class="luggage_dropdown_rt" name="sys_luggage_rt_{{$luggage_key_rt}}">
                                                                                            <option value="0" <?= (isset($luggage_value_array_rt[$luggage_val_rt['id']]) && $luggage_value_array_rt[$luggage_val_rt['id']] == 0) ? 'selected=""' : ''; ?>> 0 </option>
                                                                                            <option value="1" <?= (isset($luggage_value_array_rt[$luggage_val_rt['id']]) && $luggage_value_array_rt[$luggage_val_rt['id']] == 1) ? 'selected=""' : ''; ?>> 1 </option>
                                                                                        
                                                                                        </select>
                                                                                    @elseif($luggage_val_rt['id'] == 9 || $luggage_val_rt['id'] == 11)
                                                                                    
                                                                                        <select data-per-traveller="{{ $luggage_val_rt['i_is_per_traveller_free'] }}"  id="luggage_dropdown_rt_{{$luggage_key_rt}}" class="luggage_dropdown_rt" name="sys_luggage_rt_{{$luggage_key_rt}}">
                                                                                            <option value="0" <?= (isset($luggage_value_array_rt[$luggage_val_rt['id']]) && $luggage_value_array_rt[$luggage_val_rt['id']] == 0) ? 'selected=""' : ''; ?>> 0 </option>
                                                                                            <option value="1" <?= (isset($luggage_value_array_rt[$luggage_val_rt['id']]) && $luggage_value_array_rt[$luggage_val_rt['id']] == 1) ? 'selected=""' : ''; ?>> 1 </option>
                                                                                            <option value="2" <?= (isset($luggage_value_array_rt[$luggage_val_rt['id']]) && $luggage_value_array_rt[$luggage_val_rt['id']] == 2) ? 'selected=""' : ''; ?>> 2 </option>
                                                                                        </select>
                                                                                    @elseif($luggage_val_rt['id'] == 5)

                                                                                        <select data-per-traveller="{{ $luggage_val_rt['i_is_per_traveller_free'] }}"  class="personel_luggage_info luggage_dropdown_rt" id="luggage_dropdown_rt_{{$luggage_key_rt}}" name="sys_luggage_rt_{{$luggage_key_rt}}">
                                                                                            @for($i = 0; $i <= $travellerRtCount; $i++)
                                                                                                <option value="{{$i}}" <?= (isset($luggage_value_array_rt[$luggage_val['id']]) && ($luggage_value_array_rt[$luggage_val['id']] == $i)) ? "selected='selected'" : ""; ?>> {{$i}} </option>
                                                                                            @endfor   
                                                                                        </select>
                                                                                    @elseif($luggage_val_rt['id'] == 17)
                                                                                        <select data-per-traveller="{{ $luggage_val_rt['i_is_per_traveller_free'] }}"  id="luggage_dropdown_rt_{{$luggage_key_rt}}" class="luggage_dropdown_rt" name="sys_luggage_rt_{{$luggage_key_rt}}">
                                                                                            <option value="0"> 0 </option>
                                                                                        </select>
                                                                                    @endif
                                                                                    <input type="hidden" name="d_unit_price_rt_{{ $luggage_key_rt }}" value="{!! $luggage_val_rt['d_unit_price'] ? $luggage_val_rt['d_unit_price'] : '' !!}">
                                                                                    <input type="hidden" name="i_sys_luggage_rt_{{ $luggage_key_rt }}" value="{{$luggage_val_rt['id']}}">
                                                                                </div>
                                                                            </div>
                                                                            @if($reservation_record['e_shuttle_type'] == 'Shared')
                                                                            <div class="btn-block d-flex align-items-center justify-content-center">
                                                                                <span  class="btn btn-sm btn-yellow btn-yellow-display luggage_charge_rt" data-value="{!! $luggage_val_rt['d_unit_price'] ? $luggage_val_rt['d_unit_price'] : '' !!}">${!! $luggage_val_rt['d_unit_price'] ? $luggage_val_rt['d_unit_price'] : '' !!} each</span>
                                                                            </div>
                                                                            @endif
                                                                        </div>
                                                                    @endforeach
                                                                    
                                                                    
                                                                    <div class="counter-block counter-from-bg row m-0">
                                                                        <div class="counter-title d-flex">
                                                                            <p class="m-0 mr-2">Number of Pets</p>
                                                                            <input class="form-control i_num_pets_rt" type="text" name="i_num_pets_rt" placeholder="" value="<?= (isset($reservation_record_rt['i_num_pets'])) ? $reservation_record_rt['i_num_pets'] : '0'; ?>" readonly id="tatal_pets_rt" style="background: lightgray;">
                                                                        </div>
                                                                        @if($reservation_record['e_shuttle_type']=='Shared')
                                                                        <div class="btn-block d-md-flex d-none align-items-center justify-content-center">
                                                                            <p>CHARGE</p>
                                                                        </div>
                                                                        @endif
                                                                    </div>
                                                                    @foreach($sys_animal_def as $animal_key_rt => $animal_val_rt)                    
                                                                        <input type="hidden" name="i_sys_pet_rt_{{ $animal_key_rt }}" value="{{$animal_val_rt['id']}}">
                                                                        <div class="counter-block row m-0">
                                                                            <div class="{{ ($reservation_record['e_shuttle_type']=='Shared') ? 'counter-title' : 'counter-title-private' }}">
                                                                                <p>{!! $animal_val_rt['v_name'] ? $animal_val_rt['v_name'] : '' !!}
                                                                                @if($reservation_record['e_shuttle_type']=='Shared')
                                                                                <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="{{$animal_val_rt['v_desc']}}"></span>@endif
                                                                                </p>
                                                                            </div>
                                                                            <div class="counter-dropdown">
                                                                                <div class="custom-checkbox mr-3 mr-lg-0">
                                                                                    <input type="checkbox" class="is_pet_available_rt" id="checkbox_rt{{$animal_key_rt}}" value="{{$animal_val_rt['d_unit_price'] ? $animal_val_rt['d_unit_price'] : '0'}}" name="fare_amt_pet_rt_{{$animal_key_rt}}" <?= (isset($luggage_value_array_rt[$animal_val_rt['id']])) ? "checked='true'" : "" ?>>
                                                                                    <label for="checkbox_rt{{$animal_key_rt}}">&nbsp;</label>
                                                                                </div>
                                                                            </div>
                                                                            @if($reservation_record['e_shuttle_type']=='Shared')
                                                                            <div class="btn-block d-flex align-items-center justify-content-center">
                                                                                <span class="btn btn-sm btn-yellow btn-yellow-display pets_charge_rt" data-value="{!! $animal_val_rt['d_unit_price'] ? $animal_val_rt['d_unit_price'] : '0' !!}">${!! $animal_val_rt['d_unit_price'] ? $animal_val_rt['d_unit_price'] : '0' !!}</span> 
                                                                            </div>
                                                                            @endif
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <div class="counter-from">
                                                                    <div class="counter-block counter-from-bg row m-0">
                                                                        <div class="counter-title d-flex">
                                                                            <p class="m-0 mr-2">Number of luggages </p>
                                                                            <input class="form-control i_number_of_luggages_rt" type="text" value="<?= (isset($reservation_record_rt['i_number_of_luggages'])) ? $reservation_record_rt['i_number_of_luggages'] : '0'; ?>" readonly id="total_luggages_rt" style="background: lightgray;">
                                                                        </div>
                                                                        @if($reservation_record['e_shuttle_type'] == 'Shared' && $reservation_record_rt['i_number_of_luggages'] > 0)
                                                                        <div class="btn-block d-md-flex d-none align-items-center justify-content-center">
                                                                            <p>CHARGE</p>
                                                                        </div>
                                                                        @endif
                                                                    </div>
                                                                    @foreach($sys_luggage_def as $luggage_key_rt => $luggage_val_rt)
                                                                        @if(isset($luggage_value_array_rt[$luggage_val_rt['id']]))
                                                                            <div class="counter-block row m-0">
                                                                                <div class="{{ ($reservation_record['e_shuttle_type']=='Shared') ? 'counter-title' : 'counter-title-private' }}">
                                                                                    <p>{!! $luggage_val_rt['v_name'] ? $luggage_val_rt['v_name'] : '' !!} 
                                                                                    @if($reservation_record['e_shuttle_type'] == 'Shared')
                                                                                    <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="{{$luggage_val_rt['v_desc']}}"></span>@endif
                                                                                    </p>
                                                                                </div>
                                                                                <div class="counter-dropdown">
                                                                                    <span> {{ $luggage_value_array_rt[$luggage_val_rt['id']] }} </span>
                                                                                </div>
                                                                                @if($reservation_record['e_shuttle_type']=='Shared')
                                                                                <div class="btn-block d-flex align-items-center justify-content-center">
                                                                                    <span class="btn btn-sm btn-yellow btn-yellow-display luggage_charge_rt">${!! $luggage_val_rt['d_unit_price'] ? $luggage_val_rt['d_unit_price'] : '' !!} each</span>
                                                                                </div>
                                                                                @endif
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                    
                                                                    
                                                                    <div class="counter-block counter-from-bg row m-0">
                                                                        <div class="counter-title d-flex">
                                                                            <p class="m-0 mr-2">Number of Pets</p>
                                                                            <input class="form-control i_num_pets_rt" type="text" value="<?= (isset($reservation_record_rt['i_num_pets'])) ? $reservation_record_rt['i_num_pets'] : '0'; ?>" readonly id="tatal_pets_rt" style="background: lightgray;">
                                                                        </div>
                                                                        @if($reservation_record['e_shuttle_type'] == 'Shared' && $reservation_record_rt['i_num_pets'] > 0)
                                                                        <div class="btn-block d-md-flex d-none align-items-center justify-content-center">
                                                                            <p>CHARGE</p>
                                                                        </div>
                                                                        @endif
                                                                    </div>
                                                                    @foreach($sys_animal_def as $animal_key_rt => $animal_val_rt)       
                                                                        @if(isset($luggage_value_array_rt[$animal_val_rt['id']]))             
                                                                            <div class="counter-block row m-0">
                                                                                <div class="{{ ($reservation_record['e_shuttle_type']=='Shared') ? 'counter-title' : 'counter-title-private' }}">
                                                                                    <p>{!! $animal_val_rt['v_name'] ? $animal_val_rt['v_name'] : '' !!}
                                                                                    @if($reservation_record['e_shuttle_type'] == 'Shared')
                                                                                        <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="{{$animal_val_rt['v_desc']}}"></span>
                                                                                    @endif
                                                                                    </p>
                                                                                </div>
                                                                                <div class="counter-dropdown"></div>
                                                                                @if($reservation_record['e_shuttle_type']=='Shared')
                                                                                    <div class="btn-block d-flex align-items-center justify-content-center">
                                                                                        <span class="btn btn-sm btn-yellow btn-yellow-display pets_charge_rt">${!! $animal_val_rt['d_unit_price'] ? $animal_val_rt['d_unit_price'] : '0' !!}</span> 
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                            
                                                </div>
                                            </div>
                                            @endif

                                                          
                                            <div class="rocket-info__next mt-4 text-right">
                                            @if($reservation_record['e_shuttle_type']=='Shared')
                                                @if((isset($sum_of_luggage_animal_rt)) && $sum_of_luggage_animal_rt != '')
                                                    <?php $total_rt = number_format(($sum_of_luggage_animal + $sum_of_luggage_animal_rt),2)?>                              
                                                    <span class="btn btn-md btn-yellow btn-yellow-display" id="total_amount">${{$total_rt}}</span>
                                                
                                                @elseif((isset($sum_of_luggage_animal)) && $sum_of_luggage_animal != '')
                                                    <?php $total = number_format(($sum_of_luggage_animal),2)?>                              
                                                    <span class="btn btn-md btn-yellow btn-yellow-display" id="total_amount">${{$total}}</span>
                                                @else
                                                <span class="btn btn-md btn-yellow btn-yellow-display" id="total_amount">$0.00</span>
                                                @endif
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
            </div>
        </section>
    </div>
    <div class="modal fade bd-example-modal-sm p-md-0" id="sameAsAboveModal" tabindex="-1" role="dialog" aria-labelledby="sameAsAboveModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-xl modal-dialog modal-dialog-scrollable"
                role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="sameAsAboveTitle">Same As Above</h5>
                    </div>
                    <div class="modal-body text-md-left">
                        <div class="contestants-info mt-0">
                            <p>Are you sure that you want same as above data ?</p>

                            <p><strong>Note: </strong> Any information that you selected for 2nd leg will be removed in order to copy information from 1st leg.</p>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                            <button type="button" class="btn btn-xs btn-red" id="modal-btn-si">Yes</button>
                            <button type="button" class="btn btn-primary" id="modal-btn-no">No</button>
                        </div>
                </div>
            </div>
        </div>
@section('custom_js')
<script>
    $(document).ready(function() {
        KTReservationFrontend.init();
        KTReservationFrontend.luggageAnimals();
    });
</script>
@stop

@stop
@extends('frontend.layouts.default')
@section('content')
<?php 
    
    $monthDropdown = array("Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sep", "Oct", "Nov", "Dec");
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
                            
                                    <a class="nav-link completed {{ (isset($reservation_record) && $reservation_record['e_shuttle_type']=='Private') ? 'd-none' : '' }}" id="runs-tab" href="javascript:;" role="tab" aria-controls="runs" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'display-line-runs/'.request()->route('id') : 'display-line-runs') }}">Display Line Runs</a>
                                    
                                    <a class="nav-link active" id="passenger-information-tab" href="javascript:;" role="tab" aria-controls="passenger-information" aria-selected="true" data-action="{{ ((request()->route('id') != '') ? 'passenger-information/'.request()->route('id') : 'passenger-information') }}">Passenger Information</a>
                                    
                                    <a class="nav-link" id="luggage-animals-tab" href="javascript:;" role="tab" aria-controls="luggage-animals" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'luggage-animals/'.request()->route('id') : 'luggage-animals') }}">Luggage and Animals</a>

                                    <a class="nav-link disable" id="travel-tab" href="javascript:;" role="tab" aria-controls="travel" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'travel-details/'.request()->route('id') : 'travel-details') }}">Travel Details</a>
                                    
                                    <a class="nav-link disable {{ (isset($reservation_record) && $reservation_record['e_shuttle_type']=='Private') ? 'd-none' : '' }}" id="confirm-tab" href="javascript:;" role="tab" aria-controls="confirm" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'currently-assigned-line-runs/'.request()->route('id') : 'currently-assigned-line-runs') }}">Currently Assigned Line Run</a>
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
                                    <!-- 3 -->
                                    <div class="tab-pane fade show active" id="passenger-information" role="tabpanel" aria-labelledby="passenger-information-tab">
                                        <form action="{{ ((request()->route('id') != '') ? SITE_URL.'passenger-information/'.request()->route('id') : SITE_URL.'passenger-information') }}" class="rocket-info-details rocket-info-passenger" id="frontend_passengers_information"  method="POST">
                                        <input type="hidden" name="redirect_url" value="{{ ((request()->route('id') != '') ? 'luggage-animals/'.request()->route('id') : 'luggage-animals') }}" id="redirect_url">
                                            <h3 class="rocket-info__title"> Passenger Information </h3>
                                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and
                                                scrambled it to make a type specimen book.</p>
                                            <div class="rocket-info__four">
                                                <div class="passenger-number_details" id="firstDateOfTravel">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <h4 class="passenger-number_details-title">1st Date of Travel</h4>
                                                    </div>
                                                    <div class="table-responsive table-custom">
                                                        @foreach($fare_class_detail_ow as $key => $data)
                                                        <?php $type = ($data['v_class_label'] == 'Full Fare') ? 'adult' : strtolower($data['v_class_label']); ?>
                                                        <table class="table table-bordered <?php if($key == 0)echo 'mb-10'; ?>" id="passengerDetails{!! $key !!}" rel="{{ $type }}">
                                                            <thead>
                                                                @if($key == 0)
                                                                    <tr>
                                                                        <th width="5%">No</th>
                                                                        <th width="35%">Traveler Name</th>
                                                                        <th width="25%">Birth Month <br/> & Year</th>
                                                                        <th width="22%">Able to Travel Alone? Y/N</th>
                                                                        @if((request()->route('id') != '' && !($paymentStatus)) || request()->route('id') == '')
                                                                            <th width="20%">Action</th>
                                                                        @endif
                                                                    </tr>
                                                                @else
                                                                    <tr>
                                                                        <th width="5%"></th>
                                                                        <th width="35%"></th>
                                                                        <th width="25%"></th>
                                                                        <th width="22%"></th>
                                                                        @if((request()->route('id') != '' && !($paymentStatus)) || request()->route('id') == '')
                                                                            <th width="20%"></th>
                                                                        @endif
                                                                    </tr>
                                                                @endif

                                                                <tr id="passenger_detail" class="dummy-data d-none details">
                                                                    <td data-column="No" class="passenger-no v_label" id="v_{{ $type }}_label"></td>
                                                                    <td data-column="Traveler Name" class="passenger-t-name">
                                                                        <div class="input-field form-group">
                                                                            <input type="text" class="form-control v_name">
                                                                        </div>
                                                                    </td>
                                                                    <td data-column="Birth Month & Year" class="passenger-bmy">
                                                                        <div class="form-row align-items-center">
                                                                            <div class="select-field form-group">
                                                                                <select class="dummy-data-select form-control v_month">
                                                                                    <option value="">MM</option>
                                                                                    @foreach($monthDropdown as $mKey => $month)
                                                                                        <option value="{!! $mKey + 1 !!}">{!! $month !!}</option>
                                                                                    @endforeach                                                                                    
                                                                                </select>
                                                                            </div>
                                                                            <div class="select-field form-group">
                                                                                <select class="dummy-data-select form-control v_year">
                                                                                    <option value="">YY</option>
                                                                                    @if($type  == 'adult')
                                                                                        @for($year = (date('Y') - 15); $year >= (date('Y') - 61); $year--)
                                                                                            <option value="{!! $year !!}">{!! $year !!}</option>
                                                                                        @endfor
                                                                                    @elseif($type == 'senior')
                                                                                        @for($year = (date('Y') - 62); $year >= (date('Y') - 100); $year--)
                                                                                            <option value="{!! $year !!}">{!! $year !!}</option>
                                                                                        @endfor
                                                                                    
                                                                                    @elseif($type == 'military')
                                                                                        @for($year = (date('Y') - 15); $year >= (date('Y') - 61); $year--)
                                                                                            <option value="{!! $year !!}">{!! $year !!}</option>
                                                                                        @endfor
                                                                                    @elseif($type == 'child')
                                                                                        @for($year = (date('Y') - 2); $year >= (date('Y') - 14); $year--)
                                                                                            <option value="{!! $year !!}">{!! $year !!}</option>
                                                                                        @endfor
                                                                                    @elseif($type == 'infant')
                                                                                        @for($year = date('Y'); $year >= (date('Y') - 2); $year--)
                                                                                            <option value="{!! $year !!}">{!! $year !!}</option>
                                                                                        @endfor  
                                                                                    @endif
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td data-column="Able to Travel Alone? Y/N" class="passenger-yn">
                                                                        <div class="check-field-travel">
                                                                            <div class="custom-radio-block">
                                                                                <input type="radio" class="custome_radio_yes" value="YES" <?= ($type  == 'adult' ||  $type == 'senior' ||  $type == 'military') ? 'checked="true"' : ''?>>
                                                                                <label class="custome_radio_lable_yes" for="travel_{{ $type }}_y">Yes</label>
                                                                            </div>
                                                                            <div class="custom-radio-block">
                                                                                <input type="radio" class="custome_radio_no" value="NO" <?= ($type  == 'child' ||  $type == 'infant') ? 'checked="true"' : ''?>>
                                                                                <label class="custome_radio_lable_no" for="travel_{{ $type }}_n">No</label>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td data-column="Action" class="passenger-action">
                                                                        <div class="passenger-discounts">
                                                                            <a href="javascript:;" class="passenger_delete" data-delete-count="" rel="{{ $type }}"><span class="icon icon-trash-o"></span></a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                
                                                                @if(!empty($traveller_info[$type]))
                                                                    <tr class="passenger-tr">
                                                                        <td colspan="3">
                                                                            <p>
                                                                                <?php $discounts = 100 - ($data['d_base_rate_factor'] * 100);
                                                                                    $tooltip_title = $data['v_tooltip_text']; 
                                                                                ?>
                                                                                {{ $data['v_field_lable'] }}
                                                                                @if($tooltip_title != '' && $reservation_record['e_shuttle_type']!='Private')
                                                                                    <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{$tooltip_title}}"></span>
                                                                                @endif
                                                                            </p>
                                                                        </td>
                                                                        <td colspan="2">
                                                                            @if($reservation_record['e_shuttle_type']!='Private')
                                                                            <p> Discounts: <span class="percentage-tr">{{$discounts}}%</span> </p>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                    @foreach($traveller_info[$type] as $k => $info)
                                                                        <?php $birthdate = explode('-', $info['d_birth_month_year']);?>
                                                                        <tr id="passenger_detail{{ $k }}" class="details">
                                                                            @if($k == 0)
                                                                                <input type="hidden" id="{{ $type }}_total_details" name="{{ $type }}_total_details" value="{{ count($traveller_info[$type]) - 1 }}">
                                                                            @endif
                                                                            <td data-column="No" class="passenger-no v_label" id="v_{{ $type }}_label{{ $k }}">{{$k + 1}}</td>
                                                                            <td data-column="Traveler Name" class="passenger-t-name">
                                                                                <div class="input-field form-group">
                                                                                    <input type="text" class="form-control v_name" name="v_{{ $type }}_name[{{ $k }}]" id="v_{{ $type }}_name{{ $k }}" value="{{ $info['v_traveller_name'] }}">
                                                                                </div>
                                                                            </td>
                                                                            <td data-column="Birth Month &Year" class="passenger-bmy">
                                                                                <div class="form-row align-items-center">
                                                                                    <div class="select-field form-group">
                                                                                        <select class="form-control v_month" id="v_{{ $type }}_month{{ $k }}" name="v_{{ $type }}_month[{{ $k }}]" data-select2-id="v_{{ $type }}_month{{ $k }}">
                                                                                            <option value="">MM</option>
                                                                                            @foreach($monthDropdown as $mKey => $month)
                                                                                                <option value="{!! $mKey + 1 !!}" <?= (isset($birthdate[1]) && ($birthdate[1] == $mKey + 1)) ? "selected='selected'" : ""; ?>>{!! $month !!}</option>
                                                                                            @endforeach 
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="select-field form-group">
                                                                                        <select class="form-control v_year" id="v_{{ $type }}_year{{ $k }}" name="v_{{ $type }}_year[{{ $k }}]" data-select2-id="v_{{ $type }}_year{{ $k }}">
                                                                                            <option value="">YY</option>
                                                                                            @if($type  == 'adult')
                                                                                                @for($year = (date('Y') - 15); $year >= (date('Y') - 61); $year--)
                                                                                                    <option value="{!! $year !!}" <?= (isset($birthdate[0]) && ($birthdate[0] == $year)) ? "selected='selected'" : ""; ?>>{!! $year !!}</option>
                                                                                                @endfor
                                                                                            @elseif($type == 'senior')
                                                                                                @for($year = (date('Y') - 62); $year >= (date('Y') - 100); $year--)
                                                                                                    <option value="{!! $year !!}" <?= (isset($birthdate[0]) && ($birthdate[0] == $year)) ? "selected='selected'" : ""; ?>>{!! $year !!}</option>
                                                                                                @endfor
                                                                                            
                                                                                            @elseif($type == 'military')
                                                                                                @for($year = (date('Y') - 15); $year >= (date('Y') - 61); $year--)
                                                                                                    <option value="{!! $year !!}" <?= (isset($birthdate[0]) && ($birthdate[0] == $year)) ? "selected='selected'" : ""; ?>>{!! $year !!}</option>
                                                                                                @endfor
                                                                                            @elseif($type == 'child')
                                                                                                @for($year = (date('Y') - 2); $year >= (date('Y') - 14); $year--)
                                                                                                    <option value="{!! $year !!}" <?= (isset($birthdate[0]) && ($birthdate[0] == $year)) ? "selected='selected'" : ""; ?>>{!! $year !!}</option>
                                                                                                @endfor

                                                                                            @elseif($type == 'infant')
                                                                                                @for($year = date('Y'); $year >= (date('Y') - 2); $year--)
                                                                                                    <option value="{!! $year !!}" <?= (isset($birthdate[0]) && ($birthdate[0] == $year)) ? "selected='selected'" : ""; ?>>{!! $year !!}</option>
                                                                                                @endfor  
                                                                                            @endif
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td data-column="Able to Travel Alone? Y/N" class="passenger-yn">
                                                                                <div class="check-field-travel">
                                                                                    <div class="custom-radio-block">
                                                                                        <input type="radio" id="travel_{{ $type }}_y{{ $k }}" name="v_{{ $type }}_radio_group[{{ $k }}]" class="custome_radio_yes" groupid="travel_{{ $type }}{{ $k }}" value="YES" <?= ($info['e_is_travel_alone'] == 'YES') ? "checked='true'" : "" ?>>
                                                                                        <label Fclass="custome_radio_lable_yes" for="travel_{{ $type }}_y{{ $k }}">Yes</label>
                                                                                    </div>
                                                                                    <div class="custom-radio-block">
                                                                                        <input type="radio" id="travel_{{ $type }}_n{{ $k }}" name="v_{{ $type }}_radio_group[{{ $k }}]" class="custome_radio_no" groupid="travel_{{ $type }}{{ $k }}" value="NO" <?= ($info['e_is_travel_alone'] == 'NO') ? "checked='true'" : "" ?>>
                                                                                        <label class="custome_radio_lable_no" for="travel_{{ $type }}_n{{ $k }}">No</label>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            
                                                                            @if((request()->route('id') != '' && !($paymentStatus)) || request()->route('id') == '')
                                                                            <td data-column="Action" class="passenger-action">
                                                                                <div class="passenger-discounts">
                                                                                    @if($k == 0)
                                                                                        <a href="javascript:;" id="add_new_row" rel="{{ $type }}"><span class="icon icon-plus"></span></a>
                                                                                    @endif
                                                                                    <a href="javascript:;" class="passenger_delete" data-delete-count="{{ $k }}" rel="{{ $type }}"><span class="icon icon-trash-o"></span></a>
                                                                                </div>
                                                                            </td>
                                                                            @endif
                                                                        </tr>
                                                                    @endforeach
                                                                @else
                                                                    @if((request()->route('id') != '' && !($paymentStatus)) || request()->route('id') == '')
                                                                        <tr class="passenger-tr">
                                                                            <td colspan="3">
                                                                                <p>
                                                                                    <?php $discounts = 100 - ($data['d_base_rate_factor'] * 100);
                                                                                        $tooltip_title = $data['v_tooltip_text']; 
                                                                                    ?>
                                                                                    {{ $data['v_field_lable'] }}
                                                                                    @if($tooltip_title != '' && $reservation_record['e_shuttle_type']!='Private')
                                                                                        <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{$tooltip_title}}"></span>
                                                                                    @endif
                                                                                </p>
                                                                            </td>
                                                                            <td colspan="2">
                                                                                @if($reservation_record['e_shuttle_type']!='Private')
                                                                                <p> Discounts: <span class="percentage-tr">{{$discounts}}%</span> </p>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="passenger_detail0" class="details">
                                                                            <input type="hidden" id="{{ $type }}_total_details" name="{{ $type }}_total_details" value="0">
                                                                            <td data-column="No" class="passenger-no v_label" id="v_{{ $type }}_label0">1</td>
                                                                            <td data-column="Traveler Name" class="passenger-t-name">
                                                                                <div class="input-field form-group">
                                                                                    <input type="text" class="form-control v_name" name="v_{{ $type }}_name[0]" id="v_{{ $type }}_name0">
                                                                                </div>
                                                                            </td>
                                                                            <td data-column="Birth Month & Year" class="passenger-bmy">
                                                                                <div class="form-row align-items-center">
                                                                                    <div class="select-field form-group">
                                                                                        <select class="form-control v_month" id="v_{{ $type }}_month0" name="v_{{ $type }}_month[0]" da_infoa-select2-id="v_{{ $type }}_month0">
                                                                                            <option value="">MM</option>
                                                                                            @foreach($monthDropdown as $mKey => $month)
                                                                                                <option value="{!! $mKey + 1 !!}">{!! $month !!}</option>
                                                                                            @endforeach 
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="select-field form-group">
                                                                                        <select class="form-control v_year" id="v_{{ $type }}_year0" name="v_{{ $type }}_year[0]" data-select2-id="v_{{ $type }}_year0">
                                                                                            <option value="">YY</option>
                                                                                                @if($type  == 'adult')
                                                                                                    @for($year = (date('Y') - 15); $year >= (date('Y') - 61); $year--)
                                                                                                        <option value="{!! $year !!}">{!! $year !!}</option>
                                                                                                    @endfor
                                                                                                @elseif($type == 'senior')
                                                                                                    @for($year = (date('Y') - 62); $year >= (date('Y') - 100); $year--)
                                                                                                        <option value="{!! $year !!}">{!! $year !!}</option>
                                                                                                    @endfor
                                                                                                
                                                                                                @elseif($type == 'military')
                                                                                                    @for($year = (date('Y') - 15); $year >= (date('Y') - 61); $year--)
                                                                                                        <option value="{!! $year !!}">{!! $year !!}</option>
                                                                                                    @endfor
                                                                                                @elseif($type == 'child')
                                                                                                    @for($year = (date('Y') - 2); $year >= (date('Y') - 14); $year--)
                                                                                                        <option value="{!! $year !!}">{!! $year !!}</option>
                                                                                                    @endfor
                                                                                                @elseif($type == 'infant')
                                                                                                    @for($year = date('Y'); $year >= (date('Y') - 2); $year--)
                                                                                                        <option value="{!! $year !!}">{!! $year !!}</option>
                                                                                                    @endfor  
                                                                                                @endif
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td data-column="Able to Travel Alone? Y/N" class="passenger-yn">
                                                                                <div class="check-field-travel">
                                                                                    <div class="custom-radio-block">
                                                                                        <input type="radio" id="travel_{{ $type }}_y0" name="v_{{ $type }}_radio_group[0]" class="custome_radio_yes" groupid="travel_{{ $type }}0" value="YES" <?= ($type  == 'adult' ||  $type == 'senior' ||  $type == 'military') ? 'checked="true"' : ''?>>
                                                                                        <label class="custome_radio_lable_yes" for="travel_{{ $type }}_y0">Yes</label>
                                                                                    </div>
                                                                                    <div class="custom-radio-block">
                                                                                        <input type="radio" id="travel_{{ $type }}_n0" name="v_{{ $type }}_radio_group[0]" class="custome_radio_no" groupid="travel_{{ $type }}0" value="NO" <?= ($type  == 'child' ||  $type == 'infant') ? 'checked="true"' : ''?>>
                                                                                        <label class="custome_radio_lable_no" for="travel_{{ $type }}_n0">No</label>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            @if((request()->route('id') != '' && !($paymentStatus)) || request()->route('id') == '')
                                                                            <td data-column="Action" class="passenger-action">
                                                                                <div class="passenger-discounts">
                                                                                    <a href="javascript:;" id="add_new_row" rel="{{ $type }}"><span class="icon icon-plus"></span></a>
                                                                                    <a href="javascript:;" class="passenger_delete" data-delete-count="0" rel="{{ $type }}"><span class="icon icon-trash-o"></span></a>
                                                                                </div>
                                                                            </td>
                                                                            @endif
                                                                        </tr>
                                                                    @endif
                                                                @endif
                                                                
                                                            </tbody>
                                                        </table>
                                                        @endforeach                                                            
                                                    </div>
                                                </div>
                                            </div>
                                            @if(isset($reservation_record_rt['e_class_type']) && $reservation_record_rt['e_class_type'] == 'RT')
                                            <div class="rocket-info__four">
                                                <div class="passenger-number_details" id="secondDateOfTravel">
                                                    <div class="d-flex align-items-center justify-content-between flex-sm-nowrap flex-wrap mb-10">
                                                        <h4 class="passenger-number_details-title">2nd Date of Travel</h4>
                                                        <!-- <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input" id="sameAsAbove">
                                                            <label class="custom-control-label" for="sameAsAbove">Same as above</label>
                                                        </div> -->
                                                        <a href="javascript:void(0);" class="btn btn-sm btn-red" id="sameAsAbove">Same as above</a>
                                                    </div>
                                                    <div class="table-responsive table-custom">
                                                        @foreach($fare_class_detail_ow as $key => $data)
                                                        <?php $type = ($data['v_class_label'] == 'Full Fare') ? 'adult' : strtolower($data['v_class_label']); ?>
                                                            <table class="table table-bordered <?php if($key == 0)echo 'mb-10'; ?>" id="passengerDetails{!! $key !!}" rel="{{ $type }}_return">
                                                                <thead>
                                                                    @if($key == 0)
                                                                    <tr>
                                                                        <th width="5%">No</th>
                                                                        <th width="35%">Traveler Name</th>
                                                                        <th width="25%">Birth Month <br/> & Year</th>
                                                                        <th width="22%">Able to Travel Alone? Y/N</th>
                                                                        @if((request()->route('id') != '' && !($paymentStatus)) || request()->route('id') == '')
                                                                            <th width="20%">Action</th>
                                                                        @endif
                                                                    </tr>
                                                                    @else
                                                                    <tr>
                                                                        <th width="5%"></th>
                                                                        <th width="35%"></th>
                                                                        <th width="25%"></th>
                                                                        <th width="22%"></th>
                                                                        @if((request()->route('id') != '' && !($paymentStatus)) || request()->route('id') == '')
                                                                            <th width="20%"></th>
                                                                        @endif
                                                                    </tr>
                                                                    @endif

                                                                    <tr id="passenger_detail" class="dummy-data d-none details">
                                                                        <td data-column="No" class="passenger-no v_label" id="v_{{ $type }}_return_label"></td>
                                                                        <td data-column="Traveler Name" class="passenger-t-name">
                                                                            <div class="input-field form-group">
                                                                                <input type="text" class="form-control v_name">
                                                                            </div>
                                                                        </td>
                                                                        <td data-column="Birth Month & Year" class="passenger-bmy">
                                                                            <div class="form-row align-items-center">
                                                                                <div class="select-field form-group">
                                                                                    <select class="dummy-data-select form-control v_month">
                                                                                        <option value="">MM</option>
                                                                                        @foreach($monthDropdown as $mKey => $month)
                                                                                            <option value="{!! $mKey + 1 !!}">{!! $month !!}</option>
                                                                                        @endforeach                                                                                    
                                                                                    </select>
                                                                                </div>
                                                                                <div class="select-field form-group">
                                                                                    <select class="dummy-data-select form-control v_year">
                                                                                        <option value="">YY</option>
                                                                                        @if($type  == 'adult')
                                                                                            @for($year = (date('Y') - 15); $year >= (date('Y') - 61); $year--)
                                                                                                <option value="{!! $year !!}">{!! $year !!}</option>
                                                                                            @endfor
                                                                                        @elseif($type == 'senior')
                                                                                            @for($year = (date('Y') - 62); $year >= (date('Y') - 100); $year--)
                                                                                                <option value="{!! $year !!}">{!! $year !!}</option>
                                                                                            @endfor
                                                                                        
                                                                                        @elseif($type == 'military')
                                                                                            @for($year = (date('Y') - 15); $year >= (date('Y') - 61); $year--)
                                                                                                <option value="{!! $year !!}">{!! $year !!}</option>
                                                                                            @endfor
                                                                                        @elseif($type == 'child')
                                                                                            @for($year = (date('Y') - 2); $year >= (date('Y') - 14); $year--)
                                                                                                <option value="{!! $year !!}">{!! $year !!}</option>
                                                                                            @endfor
                                                                                        @elseif($type == 'infant')
                                                                                            @for($year = date('Y'); $year >= (date('Y') - 2); $year--)
                                                                                                <option value="{!! $year !!}">{!! $year !!}</option>
                                                                                            @endfor  
                                                                                        @endif
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td data-column="Able to Travel Alone? Y/N" class="passenger-yn">
                                                                            <div class="check-field-travel">
                                                                                <div class="custom-radio-block">
                                                                                    <input type="radio" class="custome_radio_yes" value="YES" <?= ($type  == 'adult' ||  $type == 'senior' ||  $type == 'military') ? 'checked="true"' : ''?>>
                                                                                    <label class="custome_radio_lable_yes" for="travel_{{ $type }}_return_y">Yes</label>
                                                                                </div>
                                                                                <div class="custom-radio-block">
                                                                                    <input type="radio" class="custome_radio_no" value="NO" <?= ($type  == 'child' ||  $type == 'infant') ? 'checked="true"' : ''?>>
                                                                                    <label class="custome_radio_lable_no" for="travel_{{ $type }}_return_n">No</label>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td data-column="Action" class="passenger-action">
                                                                            <div class="passenger-discounts">
                                                                                <a href="javascript:;" class="passenger_delete" data-delete-count="" rel="{{ $type }}_return"><span class="icon icon-trash-o"></span></a>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    
                                                                    @if(!empty($traveller_info_rt[$type]))
                                                                        <tr class="passenger-tr">
                                                                            <td colspan="3">
                                                                                <p> 
                                                                                <?php $discounts = 100 - ($data['d_base_rate_factor'] * 100);
                                                                                    $tooltip_title = $data['v_tooltip_text']; 
                                                                                ?>
                                                                                    {{ $data['v_field_lable'] }}
                                                                                    @if($tooltip_title != '' && $reservation_record['e_shuttle_type']!='Private')
                                                                                        <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{$tooltip_title}}"></span>
                                                                                    @endif
                                                                                </p>
                                                                            </td>
                                                                            <td colspan="2"></td>
                                                                        </tr>
                                                                        @foreach($traveller_info_rt[$type] as $k => $info)
                                                                            <?php $birthdate = explode('-', $info['d_birth_month_year']);?>
                                                                            <tr id="passenger_detail{{ $k }}" class="details">
                                                                                @if($k == 0)
                                                                                    <input type="hidden" id="{{ $type }}_return_total_details" name="{{ $type }}_return_total_details" value="{{ count($traveller_info_rt[$type]) - 1 }}">
                                                                                @endif
                                                                                <td data-column="No" class="passenger-no v_label" id="v_{{ $type }}_return_label{{ $k }}">{{$k + 1}}</td>
                                                                                <td data-column="Traveler Name" class="passenger-t-name">
                                                                                    <div class="input-field form-group">
                                                                                        <input type="text" class="form-control v_name" name="v_{{ $type }}_return_name[{{ $k }}]" id="v_{{ $type }}_return_name{{ $k }}" value="{{ $info['v_traveller_name'] }}">
                                                                                    </div>
                                                                                </td>
                                                                                <td data-column="Birth Month & Year" class="passenger-bmy">
                                                                                    <div class="form-row align-items-center">
                                                                                        <div class="select-field form-group">
                                                                                            <select class="form-control v_month" id="v_{{ $type }}_return_month{{ $k }}" name="v_{{ $type }}_return_month[{{ $k }}]" data-select2-id="v_{{ $type }}_return_month{{ $k }}">
                                                                                                <option value="">MM</option>
                                                                                                @foreach($monthDropdown as $mKey => $month)
                                                                                                    <option value="{!! $mKey + 1 !!}" <?= (isset($birthdate[1]) && ($birthdate[1] == $mKey + 1)) ? "selected='selected'" : ""; ?>>{!! $month !!}</option>
                                                                                                @endforeach 
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="select-field form-group">
                                                                                            <select class="form-control v_year" id="v_{{ $type }}_return_year{{ $k }}" name="v_{{ $type }}_return_year[{{ $k }}]" data-select2-id="v_{{ $type }}_return_year{{ $k }}">
                                                                                                <option value="">YY</option>
                                                                                                @if($type  == 'adult')
                                                                                                    @for($year = (date('Y') - 15); $year >= (date('Y') - 61); $year--)
                                                                                                        <option value="{!! $year !!}" <?= (isset($birthdate[0]) && ($birthdate[0] == $year)) ? "selected='selected'" : ""; ?>>{!! $year !!}</option>
                                                                                                    @endfor
                                                                                                @elseif($type == 'senior')
                                                                                                    @for($year = (date('Y') - 62); $year >= (date('Y') - 100); $year--)
                                                                                                        <option value="{!! $year !!}" <?= (isset($birthdate[0]) && ($birthdate[0] == $year)) ? "selected='selected'" : ""; ?>>{!! $year !!}</option>
                                                                                                    @endfor
                                                                                                
                                                                                                @elseif($type == 'military')
                                                                                                    @for($year = (date('Y') - 15); $year >= (date('Y') - 61); $year--)
                                                                                                        <option value="{!! $year !!}" <?= (isset($birthdate[0]) && ($birthdate[0] == $year)) ? "selected='selected'" : ""; ?>>{!! $year !!}</option>
                                                                                                    @endfor
                                                                                                @elseif($type == 'child')
                                                                                                    @for($year = (date('Y') - 2); $year >= (date('Y') - 14); $year--)
                                                                                                        <option value="{!! $year !!}" <?= (isset($birthdate[0]) && ($birthdate[0] == $year)) ? "selected='selected'" : ""; ?>>{!! $year !!}</option>
                                                                                                    @endfor
                                                                                                @elseif($type == 'infant')
                                                                                                    @for($year = date('Y'); $year >= (date('Y') - 2); $year--)
                                                                                                        <option value="{!! $year !!}" <?= (isset($birthdate[0]) && ($birthdate[0] == $year)) ? "selected='selected'" : ""; ?>>{!! $year !!}</option>
                                                                                                    @endfor  
                                                                                                @endif
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td data-column="Able to Travel Alone? Y/N" class="passenger-yn">
                                                                                    <div class="check-field-travel">
                                                                                        <div class="custom-radio-block">
                                                                                            <input type="radio" id="travel_{{ $type }}_return_y{{ $k }}" name="v_{{ $type }}_return_radio_group[{{ $k }}]" class="custome_radio_yes" groupid="travel_{{ $type }}_return{{ $k }}" value="YES" <?= ($info['e_is_travel_alone'] == 'YES') ? "checked='true'" : "" ?>>
                                                                                            <label class="custome_radio_lable_yes" for="travel_{{ $type }}_return_y{{ $k }}">Yes</label>
                                                                                        </div>
                                                                                        <div class="custom-radio-block">
                                                                                            <input type="radio" id="travel_{{ $type }}_return_n{{ $k }}" name="v_{{ $type }}_return_radio_group[{{ $k }}]" class="custome_radio_no" groupid="travel_{{ $type }}_return{{ $k }}" value="NO" <?= ($info['e_is_travel_alone'] == 'NO') ? "checked='true'" : "" ?>>
                                                                                            <label class="custome_radio_lable_no" for="travel_{{ $type }}_return_n{{ $k }}">No</label>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                
                                                                                @if((request()->route('id') != '' && !($paymentStatus)) || request()->route('id') == '')
                                                                                <td data-column="Action" class="passenger-action">
                                                                                    <div class="passenger-discounts">
                                                                                        @if($k == 0)
                                                                                        <a href="javascript:;" id="add_new_row" rel="{{ $type }}_return"><span class="icon icon-plus"></span></a>
                                                                                        @endif
                                                                                        <a href="javascript:;" class="passenger_delete" data-delete-count="{{ $k }}" rel="{{ $type }}_return"><span class="icon icon-trash-o"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                                @endif
                                                                            </tr>
                                                                        @endforeach
                                                                    @else
                                                                        @if((request()->route('id') != '' && !($paymentStatus)) || request()->route('id') == '')
                                                                            <tr class="passenger-tr">
                                                                                <td colspan="3">
                                                                                    <p> 
                                                                                    <?php $discounts = 100 - ($data['d_base_rate_factor'] * 100);
                                                                                        $tooltip_title = $data['v_tooltip_text']; 
                                                                                    ?>
                                                                                        {{ $data['v_field_lable'] }}
                                                                                        @if($tooltip_title != '' && $reservation_record['e_shuttle_type']!='Private')
                                                                                            <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{$tooltip_title}}"></span>
                                                                                        @endif
                                                                                    </p>
                                                                                </td>
                                                                                <td colspan="2"></td>
                                                                            </tr>    
                                                                            <tr id="passenger_detail0" class="details">
                                                                                <input type="hidden" id="{{ $type }}_return_total_details" name="{{ $type }}_return_total_details" value="0">
                                                                                <td data-column="No" class="passenger-no v_label" id="v_{{ $type }}_return_label0">1</td>
                                                                                <td data-column="Traveler Name" class="passenger-t-name">
                                                                                    <div class="input-field form-group">
                                                                                        <input type="text" class="form-control v_name" name="v_{{ $type }}_return_name[0]" id="v_{{ $type }}_return_name0">
                                                                                    </div>
                                                                                </td>
                                                                                <td data-column="Birth Month & Year" class="passenger-bmy">
                                                                                    <div class="form-row align-items-center">
                                                                                        <div class="select-field form-group">
                                                                                            <select class="form-control v_month" id="v_{{ $type }}_return_month0" name="v_{{ $type }}_return_month[0]" data-select2-id="v_{{ $type }}_return_month0">
                                                                                                <option value="">MM</option>
                                                                                                @foreach($monthDropdown as $mKey => $month)
                                                                                                    <option value="{!! $mKey + 1 !!}">{!! $month !!}</option>
                                                                                                @endforeach 
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="select-field form-group">
                                                                                            <select class="form-control v_year" id="v_{{ $type }}_return_year0" name="v_{{ $type }}_return_year[0]" data-select2-id="v_{{ $type }}_return_year0">
                                                                                                <option value="">YY</option>
                                                                                                @if($type  == 'adult')
                                                                                                    @for($year = (date('Y') - 15); $year >= (date('Y') - 61); $year--)
                                                                                                        <option value="{!! $year !!}">{!! $year !!}</option>
                                                                                                    @endfor
                                                                                                @elseif($type == 'senior')
                                                                                                    @for($year = (date('Y') - 62); $year >= (date('Y') - 100); $year--)
                                                                                                        <option value="{!! $year !!}">{!! $year !!}</option>
                                                                                                    @endfor
                                                                                                
                                                                                                @elseif($type == 'military')
                                                                                                    @for($year = (date('Y') - 15); $year >= (date('Y') - 61); $year--)
                                                                                                        <option value="{!! $year !!}">{!! $year !!}</option>
                                                                                                    @endfor
                                                                                                @elseif($type == 'child')
                                                                                                    @for($year = (date('Y') - 2); $year >= (date('Y') - 14); $year--)
                                                                                                        <option value="{!! $year !!}">{!! $year !!}</option>
                                                                                                    @endfor
                                                                                                @elseif($type == 'infant')
                                                                                                    @for($year = date('Y'); $year >= (date('Y') - 2); $year--)
                                                                                                        <option value="{!! $year !!}">{!! $year !!}</option>
                                                                                                    @endfor  
                                                                                                @endif
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td data-column="Able to Travel Alone? Y/N" class="passenger-yn">
                                                                                    <div class="check-field-travel">
                                                                                        <div class="custom-radio-block">
                                                                                            <input type="radio" id="travel_{{ $type }}_return_y0" name="v_{{ $type }}_return_radio_group[0]" class="custome_radio_yes" groupid="travel_{{ $type }}_return0" value="YES" <?= ($type  == 'adult' ||  $type == 'senior' ||  $type == 'military') ? 'checked="true"' : ''?>>
                                                                                            <label class="custome_radio_lable_yes" for="travel_{{ $type }}_return_y0">Yes</label>
                                                                                        </div>
                                                                                        <div class="custom-radio-block">
                                                                                            <input type="radio" id="travel_{{ $type }}_return_n0" name="v_{{ $type }}_return_radio_group[0]" class="custome_radio_no" groupid="travel_{{ $type }}_return0" value="NO" <?= ($type  == 'child' ||  $type == 'infant') ? 'checked="true"' : ''?>>
                                                                                            <label class="custome_radio_lable_no" for="travel_{{ $type }}_return_n0">No</label>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                
                                                                                @if((request()->route('id') != '' && !($paymentStatus)) || request()->route('id') == '')
                                                                                <td data-column="Action" class="passenger-action">
                                                                                    <div class="passenger-discounts">
                                                                                        <a href="javascript:;" id="add_new_row" rel="{{ $type }}_return"><span class="icon icon-plus"></span></a>
                                                                                        <a href="javascript:;" class="passenger_delete" data-delete-count="0" rel="{{ $type }}_return"><span class="icon icon-trash-o"></span></a>
                                                                                    </div>
                                                                                </td>
                                                                                @endif
                                                                            </tr>
                                                                        @endif
                                                                    @endif
                                                                    
                                                                </tbody>
                                                            </table>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
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

                            <p><strong>Note: </strong> Any information that you written for 2nd leg will be deleted in order to copy information from 1st leg.</p>
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
        KTReservationFrontend.passengerInformation();
 });


</script>
@stop

@stop

@extends('frontend.layouts.default')
@section('content')
<div class="main-content rocket-bg">
    <!-- quote detail -->
    <section class="quote-detail pb-5 pt-5">
        <div class="container">
            <div class="quote-detail-wrapper row">
                <div class="quote-detail-form col-md-10 col-lg-8">
                    <div class="heading">
                        <h3 class="mb-3">Detail Fare Quote</h3>
                    </div>
                    <form action="{{SITE_URL}}detail-fare-quote" method="POST" id="detailFareQuote">
                        <div class="form-wrapper mb-5" id="reservation-page-location">
                            <div class="row form-top py-3 py-md-4 px-3 px-md-5">
                                <div class="col-sm-6 form-group">
                                    <div class="select-field">
                                    <input type="hidden" id="person_total_amount" value="0.00">
                                        <select id="reservation_from_pickup_location" name="home_pickup_location" class="coll_exp_outgroup">
                                            <option value="">Pickup Location</option>
                                            @foreach($arr_country as $k => $v)
                                            <optgroup label="{{$k}}">
                                                @foreach($v as $key => $value)
                                                    <option value="{{$value['id']}}" 
                                                    <?php if($quote_info['home_pickup_location']== $value['id']){ echo 'selected'; } ?> service_area="{{$value['i_service_area_id']}}"  quote_drop_off_city_ids="{{$value['v_drop_off_city_cant_be']}}" quote_drop_off_city_must_be_ids="{{$value['v_drop_off_city_must_be']}}"> {{$value['v_city']}}</option>
                                                @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 form-group">
                                    <div class="select-field reservation-quote-location">
                                        <select id="reservation_from_dropoff_location" class="coll_exp_outgroup location_select" name="home_dropoff_location">
                                            <option value="">Drop Off Location</option>
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 form-group {!! $quote_info['e_class_type'] != 'RT' ? 'd-none' :''; !!}" id="picup_location">
                                    <div class="select-field detail-fare-quote-pickup-rt">
                                        <select id="reservation_to_pickup_location" class="coll_exp_outgroup" name="home_pickup_location_rt">
                                            <option value="">Pickup Location</option>
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 form-group {!! $quote_info['e_class_type'] != 'RT' ? 'd-none' :''; !!}" id="drop_of_location">
                                    <div class="select-field detail-fare-quote-drop-rt">
                                        <select id="reservation_to_dropoff_location" class="coll_exp_outgroup" name="home_dropoff_location_rt">
                                            <option value="">Drop Off Location</option>
                                           
                                        </select>
                                    </div>
                                </div>
                                    
                                <div class="col-md-6 form-group ps-wrapper flex-center justify-content-start">
                                    <p>How many people <br><small>(Including Children)?</small></p>
                                    <div class="" style="width: 100%;max-width: 100px;">
                                        <input type="number" name="peoples" value="{{ $quote_info['peoples'] ? $quote_info['peoples'] : '0' }}" class="ps-digit ml-10" id="total_number_of_people" data-limit="13" readonly style="border-radius: 25px !important;background: lightgray;">
                                        
                                    </div>
                                </div>
                                <div class="col-md-6 form-group custom-radio-wrapper mb-0 mt-3">
                                    <div class="custom-radio-block">
                                        <input type="radio" id="radio5" value="RT" name="radio-group-round" <?= (isset($quote_info['e_class_type']) && $quote_info['e_class_type'] == 'RT') ? "checked='true'" : ""; ?>>
                                        <label for="radio5">Round Trip</label>
                                    </div>
                                   
                                    <div class="custom-radio-block">
                                        <input type="radio" id="radio6" value="OW" name="radio-group-round" <?= (isset($quote_info['e_class_type']) ? (($quote_info['e_class_type'] == 'OW') ? "checked='true'" : "") : "checked='true'"); ?>>
                                        <label for="radio6">One Way</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-bottom px-3 px-md-5 pt-5 pb-3">
                                <div class="alert alert-warning alert-dismissible fade show d-none" id="error_msg_for_max" role="alert">
                                    <p class="error_msg_for_max"></p>
                                </div>
                                <div class="alert alert-warning alert-dismissible fade show d-none" id="error_msg_for_min" role="alert">
                                    <p class="error_msg_for_min"></p>
                                </div>
                                
                                <div class="counter-from mb-3">
                                    <div class="counter-block row m-0">
                                        <div class="counter-title col-sm-8 col-lg-9">
                                            <p>Number of adults 15-61</p>
                                        </div>
                                        <div class="counter-dropdown col-sm-4 col-lg-3">
                                            <div class="select-field">
                                                <select id="field-12" class="number_of_audlts_first value_count">
                                                    @for($i=0; $i <= 13; $i++)
                                                        <option value="{{$i}}" <?php if($i == $quote_info['peoples']){ echo 'selected'; } ?>>{{$i}}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="counter-block row m-0">
                                        <div class="counter-title col-sm-8 col-lg-9">
                                            <p>Number of Seniors 62+ <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Seniors traveling alone get discount"></span></p>
                                        </div>
                                        <div class="counter-dropdown col-sm-4 col-lg-3">
                                            <div class="select-field">
                                                <select id="field-13" class="number_of_seniors_second value_count">
                                                    @for($i = 0; $i <= 13; $i++)
                                                        <option value="{{$i}}">{{$i}}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="counter-block row m-0">
                                        <div class="counter-title col-sm-8 col-lg-9">
                                            <p>Number of Sactive Military <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="Active Military traveling alone get discount"></span></p>
                                        </div>
                                        <div class="counter-dropdown col-sm-4 col-lg-3">
                                            <div class="select-field">
                                                <select id="field-14" class="number_of_sactive_third value_count">
                                                    @for($i = 0; $i <= 13; $i++)
                                                        <option value="{{$i}}">{{$i}}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="counter-block row m-0">
                                        <div class="counter-title col-sm-8 col-lg-9">
                                            <p>Number of Children 2-14 <span class="icon icon-info"
                                                    data-toggle="tooltip" data-placement="top"
                                                    title="Children traveling alone pay full fare "></span></p>
                                        </div>
                                        <div class="counter-dropdown col-sm-4 col-lg-3">
                                            <div class="select-field">
                                                <select id="field-15" class="number_of_children_forth value_count">
                                                    @for($i = 0; $i <= 13; $i++)
                                                        <option value="{{$i}}">{{$i}}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="counter-block row m-0">
                                        <div class="counter-title col-sm-8 col-lg-9">
                                            <p>Number of Infants < 2 <span class="icon icon-info" data-toggle="tooltip" data-placement="top" title="limited to one free infant per full fare adult - so Senior with infant pays full fare"></span></p>
                                        </div>
                                        <div class="counter-dropdown col-sm-4 col-lg-3">
                                            <div class="select-field">
                                                <select id="field-16" class="number_oif_infants_fifth value_count">
                                                    @for($i = 0; $i <= 13; $i++)
                                                        <option value="{{$i}}">{{$i}}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button  type="button" class="btn btn-lg btn-red mx-auto" id="click_for_quote_info">CLICK FOR QUOTE</button>
                            </div>
                        </div>
                        <div id="for_more_info" class="mt-3">
                            <div class="d-flex align-items-center flex-wrap">
                                <p class="mr-4 my-2">Would you like to explore luggage and pet options?</p>
                                <button type="button" class="btn btn-md btn-purple m-0 click_more_information">CLICK FOR MORE INFORMATION</button>
                            </div>
                        </div>
                        <div class="d-none mt-3" id="hide_info">
                            <div class="d-flex align-items-center flex-wrap mt-3">
                                <p class="mr-4 my-2">Would you like to hide luggage and pet options?</p>
                                <button type="button" class="btn btn-md btn-purple m-0 hide_info">CLICK FOR LESS INFORMATION</button>
                            </div>
                        </div>
                        <div class="more_information d-none mt-5">
                            <div class="heading">
                                <h3 class="mb-3 mt-3">Luggage</h3>
                            </div>
                            <div class="form-wrapper mb-5">
                                <div class="row form-top py-3 py-md-4 px-3 px-md-5">
                                    <div class="col-sm-9 flex-center no-of-travelers justify-content-start">
                                        <p class="m-0 mr-2">Number of luggages</p>
                                        <input class="form-control" id="total_passenger" name="i_number_of_luggages" type="number" value='' readonly style="background: lightgray;">
                                    </div>
                                    <div
                                        class="col-sm-3 d-none d-sm-flex align-items-center justify-content-center">
                                        <p>CHARGE</p>
                                    </div>
                                </div>
                                <div class="row form-bottom px-3 px-md-5 pt-5 pb-3">
                                    <div class="col-sm-9">
                                        <div class="counter-from mb-3"> 
                                            @foreach($sys_luggage_def as $luggage_key => $luggage_val)
                                                <div class="counter-block row m-0">
                                                    <div class="counter-title col-sm-8 col-lg-9">
                                                        <p>{!! $luggage_val['v_name'] ? $luggage_val['v_name'] : '' !!}<span class="icon icon-info"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="{{$luggage_val['v_desc']}}"></span></p>
                                                    </div>
                                                    <div class="counter-dropdown col-sm-4 col-lg-3">
                                                        <div class="select-field">
                                                            @if($luggage_val['id'] == 1 || $luggage_val['id'] == 2 || $luggage_val['id'] == 3 || $luggage_val['id'] == 4)
                                                                <select data-per-traveller="{{ $luggage_val['i_is_per_traveller_free'] }}" name="sys_luggage_{{$luggage_key}}" id="{{$luggage_key}}" class="luggage_dropdown">
                                                                    <?php for($i=0; $i <= 20; $i++) { ?>
                                                                    <option value="{{$i}}" > {{$i}} </option><?php } ?>
                                                                </select>
                                                            @elseif($luggage_val['id'] == 6 || $luggage_val['id'] == 7 || $luggage_val['id'] == 8 || $luggage_val['id'] == 12 || $luggage_val['id'] == 13 || $luggage_val['id'] == 14 || $luggage_val['id'] == 15 || $luggage_val['id'] == 16)
                                                                <select data-per-traveller="{{ $luggage_val['i_is_per_traveller_free'] }}" name="sys_luggage_{{$luggage_key}}" id="{{$luggage_key}}" class="luggage_dropdown">
                                                                    <?php for($i=0; $i <= 10; $i++) { ?>
                                                                    <option value="{{$i}}" > {{$i}} </option><?php } ?>
                                                                </select>
                                                            @elseif($luggage_val['id'] == 10)
                                                                <select data-per-traveller="{{ $luggage_val['i_is_per_traveller_free'] }}" name="sys_luggage_{{$luggage_key}}" id="{{$luggage_key}}" class="luggage_dropdown">
                                                                    <option value="0" > 0 </option>
                                                                    <option value="1" > 1 </option>
                                                                </select>
                                                            @elseif($luggage_val['id'] == 9 || $luggage_val['id'] == 11)
                                                                <select data-per-traveller="{{ $luggage_val['i_is_per_traveller_free'] }}" name="sys_luggage_{{$luggage_key}}" id="{{$luggage_key}}" class="luggage_dropdown">
                                                                    <option value="0" > 0 </option>
                                                                    <option value="1" > 1 </option>
                                                                    <option value="2" > 2 </option>
                                                                </select>
                                                            @elseif($luggage_val['id'] == 5)
                                                                <select data-per-traveller="{{ $luggage_val['i_is_per_traveller_free'] }}" name="sys_luggage_{{$luggage_key}}" class="personel_luggage_info luggage_dropdown" id="{{$luggage_key}}">
                                                                    
                                                                </select>
                                                            @elseif($luggage_val['id'] == 17)
                                                            <select data-per-traveller="{{ $luggage_val['i_is_per_traveller_free'] }}" name="sys_luggage_{{$luggage_key}}" id="{{$luggage_key}}" class="luggage_dropdown">
                                                            <option value="0" > 0 </option>
                                                            </select>
                                                            @endif
                                                            
                                                        </div>
                                                        <div class="btn-block d-flex align-items-center d-sm-none">
                                                            <input type="hidden" name="d_unit_price_{{ $luggage_key }}" value="{!! $luggage_val['d_unit_price'] ? $luggage_val['d_unit_price'] : '' !!}">
                                                            <input type="hidden" name="i_sys_luggage_{{ $luggage_key }}" value="{{$luggage_val['id']}}" >
                                                            <button href="#" class="btn btn-sm btn-yellow luggage_charge" value="{{$luggage_val['d_unit_price'] ? $luggage_val['d_unit_price'] : ''}}">${!! $luggage_val['d_unit_price'] ? $luggage_val['d_unit_price'] : '' !!} each</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <div class="charge-btn col-sm-3 d-none d-sm-block">
                                        @foreach($sys_luggage_def as $luggage_key => $luggage_val)  

                                            <div class="btn-block flex-center button_height">
                                                <a href="#" class="btn btn-sm btn-yellow btn-yellow-display">${!! $luggage_val['d_unit_price'] ? $luggage_val['d_unit_price'] : '0' !!} each</a>
                                                </div>
                                        @endforeach
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="heading">
                                <h3 class="mb-3">Animals</h3>
                            </div>
                            <div class="form-wrapper mb-5">
                                <div class="row form-top py-3 py-md-4 px-3 px-md-5">
                                    <div class="col-sm-9 flex-center no-of-travelers justify-content-start">
                                        <p class="m-0 mr-2">Number of Pets</p>
                                        <input class="form-control" name="i_num_pets" type="number" value="" readonly id='tatal_pets' style="background: lightgray;">
                                    </div>
                                    <div
                                        class="col-sm-3 d-none d-sm-flex align-items-center justify-content-center">
                                        <p>CHARGE</p>
                                    </div>
                                </div>
                                <div class="row form-bottom px-3 px-md-5 pt-5 pb-3">
                                    
                                        <div class="col-sm-9">
                                            <div class="counter-from mb-3">
                                            @foreach($sys_animal_def as $animal_key => $animal_val)
                                            <input type="hidden" name="i_sys_pet_{{ $animal_key }}" value="{{$animal_val['id']}}" />
                                                <div class="counter-block row m-0">
                                                    <div class="counter-title col-sm-8 col-lg-9">
                                                        <p>{!! $animal_val['v_name'] ? $animal_val['v_name'] : '' !!}<span class="icon icon-info"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="{{$animal_val['v_desc']}}"></span></p>
                                                    </div>
                                                    <div class="counter-dropdown col-sm-4 col-lg-3">
                                                        <div class="custom-checkbox">
                                                            <input type="checkbox" name="fare_amt_pet_{{$animal_key}}" id="checkbox{{$animal_key}}" class="animals_charge" value="{{$animal_val['d_unit_price'] ? $animal_val['d_unit_price'] : '0'}}">
                                                            <label for="checkbox{{$animal_key}}">&nbsp;</label>
                                                        </div>
                                                        <div class="btn-block d-flex align-items-center d-sm-none">
                                                            <a href="#" class="btn btn-sm btn-yellow">${!! $animal_val['d_unit_price'] ? $animal_val['d_unit_price'] : '0' !!}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            </div>
                                        </div>
                                        <div class="charge-btn col-sm-3 d-none d-sm-block">
                                            @foreach($sys_animal_def as $animal_key => $animal_val)
                                                <div class="btn-block flex-center button_height">
                                                    <a href="#" class="btn btn-sm btn-yellow btn-yellow-display">${!! $animal_val['d_unit_price'] ? $animal_val['d_unit_price'] : '0' !!}</a>
                                                </div>
                                            @endforeach
                                        </div>
                                    
                                    <ul class="custom-list px-3 py-2">
                                        <li>Rocket accepts Cats and Dogs in appropriate Kennel.s</li>
                                        <li>ESA dogs must be in a carrier at the foot of the traveler or
                                            strapped to the body of the traveler - none of the carrier may touch
                                            the seat to reduce transfer.</li>
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="btn btn-lg btn-red mx-auto click_for_quote_info">CLICK FOR QUOTE</button>
                        </div>
                    
                        <div id="quote_for_selected_options">
                            <div class="shared-shuttle-quote d-flex align-items-center flex-column flex-sm-row justify-content-between my-4 p-3 p-sm-0 bg-gray">
                                <p class="mt-0 mt-sm-2 mb-2 px-5">Shared Shuttle quote for selected options</p>
                                <button href="#" class="btn btn-lg btn-yellow m-0 btn-yellow-display" value="{{isset($quote_info['amount']) && $quote_info['amount'] ? $quote_info['amount'] : '$0.00'}}" id="total_luggages">{{isset($quote_info['amount']) && $quote_info['amount'] ? '$'.$quote_info['amount'] : '$0.00' }}</button>
                            </div>                      
                        </div>
                        <div id="button_for_reservation">
                            <!-- <a href="{{SITE_URL}}book-a-shuttle"> <button type="button" class="btn btn-lg btn-purple mx-auto mt-3 mb-2">Make A Reservation</button></a> -->
                            <button type="submit" class="btn btn-lg btn-purple mx-auto mt-3 mb-2">Make A Reservation</button>
                        </div>
                    </form>                                                
                </div>
            </div>
        </div>
    </section>
    
</div>
@section('custom_js')

<script>
$(document).ready(function () { 

    $('#reservation_from_pickup_location').on('change', function () {
        $('#quote_for_selected_options').hide();
        $('#button_for_reservation').hide();
        $('#click_for_quote_info').removeClass('d-none');
        $('.click_for_quote_info').removeClass('d-none');
        var pickup_area_id = $('option:selected', this).attr('service_area');
        var value = $(this).val();
        if(value != '') {
            $.post(SITE_URL+'get-home-dropoff-locations',{pickup_area_id: pickup_area_id, tab: 'detailFare'},function(data){
                $('#reservation_from_dropoff_location').html(data).trigger("change");
            });
            $.post(SITE_URL+'get-home-dropoff-locations',{pickup_area_id: pickup_area_id, tab: 'detailFareRt'},function(data){
                $('#reservation_to_dropoff_location').html(data).val(value).trigger('change');
            });
        } else {
            $('#reservation_from_dropoff_location').html('<option value="">Drop Off Location</option>');
            $('#reservation_to_pickup_location').html('<option value="">Pick up Location</option>');
            $('#reservation_to_dropoff_location').html('<option value="">Drop Off Location</option>');
        }
    });
        
    $('body').on('change','#reservation_from_dropoff_location', function() {
        $('#click_for_quote_info').removeClass('d-none');
        $('.click_for_quote_info').removeClass('d-none');
        var pickup_area_id = $('option:selected', this).attr('service_area');        
        $('#quote_for_selected_options').hide();
        $('#button_for_reservation').hide();
        var value = $(this).val();
        if(value != '') {
            $.post(SITE_URL+'get-home-dropoff-locations',{pickup_area_id:pickup_area_id,tab: 'detailFarePickUpRt'},function(data){
                $('#reservation_to_pickup_location').html(data).val(value).trigger('change');
            });
        } else {
            $('#reservation_to_pickup_location').html('<option value="">Pick up Location</option>');
        }
    });

    $('#reservation_from_pickup_location').trigger("change");
    
    var quote_total = $('#total_luggages').val();
    if(quote_total == "$0.00") {
        $('#quote_for_selected_options').hide();
        $('#button_for_reservation').hide();
        $('#click_for_quote_info').removeClass('d-none');
        $('.click_for_quote_info').removeClass('d-none');
    } else {
        $('#quote_for_selected_options').show();
        $('#button_for_reservation').show();
        $('#click_for_quote_info').addClass('d-none');
        $('.click_for_quote_info').addClass('d-none');
    }
    
    $('.click_more_information').on('click',function(){
        $('.more_information').removeClass('d-none');
        $(".button_height").css({ "min-height": "63.5938px" });
        $('#for_more_info').addClass('d-none');
        $('#hide_info').removeClass('d-none');
        $('#click_for_quote_info').addClass('d-none');
        
    });
    $('.hide_info').on('click',function(){
        $('.more_information').addClass('d-none');
        $('#for_more_info').removeClass('d-none');
        $('#hide_info').addClass('d-none');
        $('#click_for_quote_info').removeClass('d-none');
        
    });    
    
    $('input[type=radio][name=radio-group-round]').change(function() {
        $('#quote_for_selected_options').hide();
        $('#button_for_reservation').hide();
        $('#click_for_quote_info').removeClass('d-none');
        $('.click_for_quote_info').removeClass('d-none');
        if (this.value == 'RT') {
            $('#picup_location').removeClass('d-none');
            $('#drop_of_location').removeClass('d-none');
        }else{
            $('#picup_location').addClass('d-none');
            $('#drop_of_location').addClass('d-none');
        }
        
    });
        
    $('.value_count').on('change', function(){ 
        $('#quote_for_selected_options').hide();
        $('#button_for_reservation').hide();        
    
        var first_val = $('.number_of_audlts_first').val();
        var secode_val  = $('.number_of_seniors_second').val();
        var third_val = $('.number_of_sactive_third').val();
        var forth_val  = $('.number_of_children_forth').val();
        var fifth_val = $('.number_oif_infants_fifth').val();
        var sub_total = parseInt(first_val) + parseInt(secode_val) + parseInt(third_val) + parseInt(forth_val) + parseInt(fifth_val);
        var totalNumberOfPeople  = sub_total;
        
        if(sub_total > 13){
            $('.error_msg_for_max').html('Total number of passengers can not exceed 13.');
            $('#error_msg_for_max').removeClass('d-none');
            $("html, body").animate({
                scrollTop: 0
            }, 1000);
        } else {
            $('#click_for_quote_info').removeClass('d-none');
            $('.click_for_quote_info').removeClass('d-none');
            $('#error_msg_for_min').addClass('d-none');
            $('#total_number_of_people').val(sub_total);
            $('#error_msg_for_max').addClass('d-none');
            $(".personel_luggage_info option").remove();
            var html = ''; 
            for (i=0; i<=sub_total; i++) {
                html += '<option value="'+i+'">'+i+'</option>'; 
            }
            $('.personel_luggage_info').append(html); 
        }
    });
    $('.animals_charge').on('change', function(){
        $('#tatal_pets').val($(".animals_charge"+ ":checked").length);
        $('#quote_for_selected_options').hide();
        $('#button_for_reservation').hide();
        $('.click_for_quote_info').removeClass('d-none');
        //totalChange();
    });
    $('.luggage_dropdown').on('change', function(){
        LuggageValue();
        $('#quote_for_selected_options').hide();
        $('#button_for_reservation').hide();
        $('.click_for_quote_info').removeClass('d-none');
        //totalChange();
    });
    function LuggageValue(){
        var passanger_total_luggage = 0;
        $("select.luggage_dropdown").each(function () {
            if ($(this).val() == '' || $(this).val() == undefined) {
                passanger_total_luggage = passanger_total_luggage;
            } else {
                passanger_total_luggage = passanger_total_luggage + parseInt($(this).val());
            }
            $('#total_passenger').val(passanger_total_luggage);
        });
    }
       
    $('#click_for_quote_info').on('click', function(){
        $('#quote_for_selected_options').show();
        $('#button_for_reservation').show();
        AmoutOfPerson();
    });
    $('.click_for_quote_info').on('click', function(){
        $('#quote_for_selected_options').show();
        $('#button_for_reservation').show();
        AmoutOfPerson();
    });
    function AmoutOfPerson() {
        var first_val = $('.number_of_audlts_first').val();        
        var secode_val  = $('.number_of_seniors_second').val();
        var third_val = $('.number_of_sactive_third').val();
        var forth_val  = $('.number_of_children_forth').val();
        var fifth_val = $('.number_oif_infants_fifth').val();
        var sub_total = parseInt(first_val) + parseInt(secode_val) + parseInt(third_val) + parseInt(forth_val) + parseInt(fifth_val);
        if(sub_total == 0) {
            $('#click_for_quote_info').addClass('d-none');
            $('.click_for_quote_info').addClass('d-none');
            $('.error_msg_for_min').html('Please select number of passengers on appropriate dropdown.');
            $('#error_msg_for_min').removeClass('d-none');
            $("html, body").animate({
                scrollTop: 0
            }, 1000);
        } else {
            $('#error_msg_for_min').addClass('d-none');
        }
    
        var trip_status = $('input[type=radio][name=radio-group-round]:checked').val();

        var detail_fare_data = {};

        if(trip_status == 'OW') {
            detail_fare_data = {'FFOW':first_val,'SROW':secode_val,'MLOW':third_val,'CHOW':forth_val,'INOW':fifth_val}
        } else {
            detail_fare_data = {'FFRT':first_val,'SRRT':secode_val,'MLRT':third_val,'CHRT':forth_val,'INRT':fifth_val}
        }
        
        var totalNumberOfPeople  = $('#total_number_of_people').val();
    
        var pic_up_service_area_id = $('option:selected', '#reservation_from_pickup_location').attr('service_area');
        var drpoOff_service_area_id = $('option:selected', '#reservation_from_dropoff_location').attr('service_area');
        if(drpoOff_service_area_id != "" &&  pic_up_service_area_id != "" && trip_status != "" &&  sub_total <= totalNumberOfPeople){
            $('#error_msg_for_max').addClass('d-none');
            $.ajax({
                url : SITE_URL + "calculate-total-amount",
                method: 'POST',
                data: {'origin_service_area_id':pic_up_service_area_id,'dest_service_area_id':drpoOff_service_area_id,'trip_status':trip_status,'number_of_people':totalNumberOfPeople,'detail_fare_data':detail_fare_data},
                success: function (data) {
                    var response = JSON.parse(data);
                    if(response.status == 'TRUE') {
                        var total_amount = response.total_amount;
                        $('#person_total_amount').val(total_amount);
                        $('#click_for_quote_info').addClass('d-none');
                        $('.click_for_quote_info').addClass('d-none');
                        totalChange();
                    }
                }
            })
        } else {
            
            if(sub_total > 13){
                $('.error_msg_for_max').html('Total number of passengers can not exceed 13.');
                $('#error_msg_for_max').removeClass('d-none');
                $("html, body").animate({
                    scrollTop: 0
                }, 1000);
            } else {
                $('#error_msg_for_max').addClass('d-none');
            }
        }
    
    }
    function totalChange() {
        var total = 0;
        $('.luggage_dropdown').each(function(k,v) {
            var leg1_tot = "{{ $quote_info['peoples'] ? $quote_info['peoples'] : '0' }}";
            leg1_tot = leg1_tot * 2;
            var flag = $(v).attr('data-per-traveller');
           
            var val = $(v).val();
            var charge = $(v).closest('div').next().find('.luggage_charge').val();

            if(flag==1) {
                if(val <= leg1_tot) {
                    charge = 0;
                } else {
                    val = val - leg1_tot;
                }
            }
            total += val * charge;
        })
    
        $('.animals_charge').each(function(k,v) {
            if ($(v).is(":checked")){
                total += parseFloat($(v).val());
            }

        })
        var number_of_Travelers_amount = parseFloat($('#person_total_amount').val());
        var final_total = (total) + (number_of_Travelers_amount);
        $('#total_luggages').text('$'+final_total.toFixed(2));
    }

    $('body').on('change','#reservation_to_pickup_location', function() {
        $('#click_for_quote_info').removeClass('d-none');
        $('.click_for_quote_info').removeClass('d-none');
    });
    $('body').on('change','#reservation_to_dropoff_location', function() {
        $('#click_for_quote_info').removeClass('d-none');
        $('.click_for_quote_info').removeClass('d-none');
    });
});
</script>
@stop
@stop
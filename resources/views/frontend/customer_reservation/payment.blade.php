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
                            
                            <a class="nav-link completed" id="runs-tab" href="javascript:;" role="tab" aria-controls="runs" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'display-line-runs/'.request()->route('id') : 'display-line-runs') }}">Display Line Runs</a>
                            
                            <a class="nav-link completed" id="passenger-information-tab" href="javascript:;" role="tab" aria-controls="passenger-information" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'passenger-information/'.request()->route('id') : 'passenger-information') }}">Passenger Information</a>
                            
                            <a class="nav-link completed" id="luggage-animals-tab" href="javascript:;" role="tab" aria-controls="luggage-animals" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'luggage-animals/'.request()->route('id') : 'luggage-animals') }}">Luggage and Animals</a>

                            <a class="nav-link completed" id="travel-tab" href="javascript:;" role="tab" aria-controls="travel" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'travel-details/'.request()->route('id') : 'travel-details') }}">Travel Details</a>
                            
                            <a class="nav-link completed" id="confirm-tab" href="javascript:;" role="tab" aria-controls="confirm" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'currently-assigned-line-runs/'.request()->route('id') : 'currently-assigned-line-runs') }}">Currently Assigned Line Run</a>
                            
                            <a class="nav-link completed" id="reservation-summary-tab" href="javascript:;" role="tab" aria-controls="reservation-summary" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'reservation-summary/'.request()->route('id') : 'reservation-summary') }}">Reservation Summary</a>

                            <a class="nav-link active" id="payment-tab" href="javascript:;" role="tab" aria-controls="payment" aria-selected="true"data-action="{{ ((request()->route('id') != '') ? 'reservation-payment/'.request()->route('id') : 'reservation-payment') }}">Payment</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="rocket-info-right">
                        <div class="tab-content" id="v-tabContent">
                            <!-- 8 -->
                            <div class="tab-pane fade show active" id="payment" role="tabpanel" aria-labelledby="payment-tab">
                                <form id="frontend_payment" method="POST" action="{{ ((request()->route('id') != '') ? SITE_URL.'reservation-payment/'.request()->route('id') : SITE_URL.'reservation-payment') }}" class="rocket-info-details rocket-info-payment custom-form">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h3 class="rocket-info__title pb-10">Payment</h3>
                                        </div>
                                        <div class="col-md-6 text-right pb-10">
                                            @if($stripe_customer && $stripe_customer->sources->total_count > 0)
                                            <button id="btn-add-card" class="opt-pay-by-card btn btn-md btn-purple mt-sm-0 mb-2" style="font-size: 0.875rem;">Add New</button>
                                            <button id="btn-sel-saved-card" class="opt-pay-by-card btn btn-md btn-purple mt-sm-0 mb-2" style="font-size: 0.875rem;">Select Saved Card</button>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="rocket-info__six py-5">
                                        <div class="opt-pay-by-card payment-block">
                                            <div class="alert alert-info" role="alert">
                                                <strong>Payment for ticket</strong>
                                                #{{ ($reservation_record->v_reservation_number." ") }}
                                                @if($reservation_record_rt) 
                                                    & #{{ $reservation_record_rt->v_reservation_number }}
                                                @endif
                                            </div>
                                            <div class="payment-block-img mb-30 text-center">
                                                <img src="{{ asset('frontend/assets/images/payment.png') }}" alt="" title="" />
                                            </div>
                                            <div class="alert custom-error alert-dismissible kt-hide" role="alert">
                                                <div class="alert-text"></div>
                                                <div class="alert-close"><i class="flaticon2-cross kt-icon-sm" data-dismiss="alert"></i>
                                                </div>
                                            </div>
                                            <div class="payment-block-form row">
                                                <div class="payment-card-name form-group">
                                                    <label class="form-label">Name As It Appears On The Card</label>
                                                    <input class="form-input required" type="text" name="v_card_name" id="v_card_name" value="{{ ($current_user) ? $current_user->v_firstname.' '.$current_user->v_lastname : '' }}" />
                                                </div>
                                                <div class="payment-card-number form-group">
                                                    <label class="form-label">Card Number</label>
                                                    <input name="i_card_num" id="i_card_num" err-msg="Card number" class="form-input required" type="text" />
                                                </div>
                                                <div class="payment-expiry-month form-group">
                                                    <div class="select-field">
                                                        <select err-msg="Expiry Month" class="pay-select-fields form-control required" id="i_card_exp_month">
                                                            <option value="">Expiry Month</option>
                                                            @for($i = 1; $i <= 12; $i++)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="payment-expiry-year form-group">
                                                    <div class="select-field">
                                                        <select err-msg="Expiry Year" class="pay-select-fields form-input form-control required" id="i_card_exp_year">
                                                            <option value="">Expiry Year</option>
                                                            @for($year = date('Y'); $year <= (date('Y') + 20); $year++)
                                                                <option value="{{ $year }}">{{ $year }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="payment-cvv form-group">
                                                    <label class="form-label">CVV</label>
                                                    <input name="i_cvc" id="i_cvc" class="form-input required" type="text" err-msg="CVV" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="rocket-info__two--text pt-md-30 pt-3">
                                            <div class="row justify-content-end">
                                                <div class="col-lg-7 col-sm-12">
                                                    <div class="coupon-code">
                                                        <div class="row no-gutters align-items-center">
                                                            <div class="col-xl-4 col-md-12 mb-xl-0 mb-2">
                                                                <span><strong>Discount Coupon</strong></span>
                                                            </div>
                                                            <div class="col-lg-4 col-md-8 mb-2 mb-md-0">
                                                                <div class="input-field">
                                                                    <input type="text" value="{{ ($reservation_record && $reservation_record->v_discount_code!='') ? $reservation_record->v_discount_code : '' }}" name="v_discount_code" id="v_discount_code" class="form-control" placeholder="" />
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3 pl-md-2 col-md-4">
                                                                <div class="input-field">
                                                                    <input type="submit" id="apply-coupon" value="apply" class="form-control btn btn-red">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($current_user && $current_user->d_wallet_balance != 0)
                                                <div class="col-lg-5 col-12 text-right mt-3 mt-lg-0 sec-wallet-balance">
                                                    <div class="price-box">
                                                    <input type="hidden" name="ufwb" value="" />
                                                    <p class="mb-2"><span><input type="checkbox" checked id="use_wallet" name="use_wallet" value="1" /> Wallet Balance </span> <span><strong class="">{{ '$'.number_format($current_user->d_wallet_balance,2) }}</strong></span></p>
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="col-lg-5 col-12 text-right mt-3 mt-lg-0">
                                                    <div class="price-box">  
                                                        <p class="mb-2"><span>Subtotal </span> <span><strong class="show_sub_total">{{ '$'.number_format($total_fare,2) }}</strong></span></p>
                                                        @if($discountPrice > 0)
                                                            <p class="mb-2"><span>Discount </span> <span><strong class="show_discount">{{ '$'.number_format($discountPrice,2) }}</strong></span></p>
                                                            <p class="total-prices">
                                                                <span>Total Fare </span><span class="show_total_total">{{ '$'.number_format(($total_fare - $discountPrice),2) }}</span>
                                                            </p>
                                                        @else
                                                        <p class="mb-2 d-none"><span>Discount </span> <span><strong class="show_discount"></strong></span></p>
                                                        <p class="total-prices">
                                                            <span>Total Fare </span><span class="show_total_total">{{ '$'.number_format($total_fare,2) }}</span>
                                                        </p>
                                                        @endif

                                                       
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="rocket-info__two--text pt-50">
                                            <div class="row no-gutters">
                                                <input type="hidden" name="stripe_card_id" />
                                                <input type="hidden" name="i_card_exp_month" />
                                                <input type="hidden" name="i_card_exp_year" />
                                                @if(auth()->guard('admin')->check() && !auth()->guard('customers')->check())
                                                    <div class="col-md-6 text-left">
                                                        <div class="custom-checkbox-wrapper custom-checkbox">
                                                            <input type="checkbox" id="cash_on_board" name="cash_on_board">
                                                            <label for="cash_on_board">Cash on Board</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 text-right">
                                                        <button class="btn-pay btn btn-md btn-red">submit</button>
                                                    </div>
                                                @else
                                                    <div class="col-md-12 text-right">
                                                        <button class="btn-pay btn btn-md btn-red">submit</button>
                                                    </div>
                                                @endif
                                                
                                            </div>
                                        </div>
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

<div class="modal fade bd-example-modal-sm p-md-0" id="my-saved-cards" tabindex="-1" role="dialog" aria-labelledby="my-saved-cards" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-xl modal-dialog  modal-dialog-scrollable"
        role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">My Saved Cards</h5>
            </div>
            <div class="modal-body text-md-left">
                <div class="contestants-info mt-0 select-field">
                    <select id="select_card" class="coll_exp_outgroup form-control location_select" placeholder="Pickup Location" name="select_card">
                        <option value="">Select Card</option>
                        @if($stripe_customer && $stripe_customer->sources->total_count > 0)
                            @php $cards_fingerprints = []; @endphp
                            @foreach ($stripe_customer->sources->data as $cardDetail)
                                @if(!in_array($cardDetail['fingerprint'],$cards_fingerprints))
                                <option data-name="{{ $cardDetail['name'] }}" data-card="{{ 'xxxx xxxx xxxx '.$cardDetail['last4'] }}" data-expmonth="{{ $cardDetail['exp_month'] }}" data-expyear="{{ $cardDetail['exp_year'] }}" value="{{ $cardDetail['id'] }}">{{ 'xxxx xxxx xxxx '.$cardDetail['last4']}}</option>
                                @endif
                                @php $cards_fingerprints[] = $cardDetail['fingerprint'] @endphp
                            @endforeach   
                        @endif 
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary" id="modal-btn-no">Cancel</button>
            </div>
        </div>
    </div>
</div>

@section('custom_js')
<script>
    function showPayForm(wb,tf) {
        wb = parseFloat(wb);
        tf = parseFloat(tf.replace("$", ""));

        
        
        if(!$('#use_wallet').is(':checked')) {
            $("input[name=ufwb]").val(0);
            $('.form-input').addClass('required');
            $('.opt-pay-by-card').removeClass('d-none');
            $('#i_card_num').trigger('focus');
            $('#v_card_name').trigger('focus');
        } else {
            if(wb >= tf){
                $("input[name=ufwb]").val(1);
                $('.form-input').removeClass('required');
                $('.opt-pay-by-card').addClass('d-none');
            } 
        }

        if(tf==0){
            $('.sec-wallet-balance').addClass('d-none');
            $('.form-input').removeClass('required');
            $('.opt-pay-by-card').addClass('d-none');
        }
    }
    
    $(document).ready(function() {
        var wallet_balance = "{{ ($current_user) ? $current_user->d_wallet_balance : 0 }}"
        var total_amount = "{{ $total_fare - $discountPrice }}";
        var discount = 0;

        $('#v_card_name').trigger('focus');

        if(parseFloat(wallet_balance) >= parseFloat(total_amount)){
            $("input[name=ufwb]").val(1);
            $('.form-input').removeClass('required');
            $('.opt-pay-by-card').addClass('d-none');
        } else {
            $("input[name=ufwb]").val(0);
        }

        if($('#use_wallet').length > 0) {
            $('#use_wallet').on('click',function(){
                total_amount = total_amount.replace("$", "");
                showPayForm(wallet_balance,total_amount);
            });
        }

        $('#i_card_exp_month').on('change',function(){
            $('input[name=i_card_exp_month]').val($(this).val());
        });

        $('#i_card_exp_year').on('change',function(){
            $('input[name=i_card_exp_year]').val($(this).val());
        });

        var show_cards_popup = "{{ ($stripe_customer && $stripe_customer->sources->total_count > 0) ? 1 : 0 }}";

        $('#select_card').on('change',function(){
            if($(this).val()!="") {
                var card_name = $('option:selected', this).attr('data-name');
                var card_num = $('option:selected', this).attr('data-card');
                var card_expmonth = $('option:selected', this).attr('data-expmonth');
                var card_expyear = $('option:selected', this).attr('data-expyear');
                var card_id = $(this).val();

                $('#v_card_name').val(card_name).trigger('focus');
                $('#i_card_num').val(card_num).trigger('focus');
                $('#i_card_exp_month').val(card_expmonth).trigger('change');
                $('#i_card_exp_year').val(card_expyear).trigger('change');
                $('input[name=stripe_card_id]').val(card_id);

                if($('#v_card_name').val() != "") {
                    $('#v_card_name').prop("readonly", true);
                }
                $('#i_card_num').prop("readonly", true);
                $('#i_card_exp_month').prop("disabled", true);
                $('#i_card_exp_year').prop("disabled", true);
                $('#my-saved-cards').modal('hide');
            }
        });

        $('#btn-add-card').on('click',function(){
            $('#select_card').val("").trigger('change');
            $('input[name=stripe_card_id]').val('');
            $('#v_card_name').removeAttr("readonly").val('');
            $('#i_card_num').removeAttr("readonly").val('');
            $('#i_card_exp_month').removeAttr("disabled").removeClass('required').val('').trigger('change').addClass('required');
            $('#i_card_exp_year').removeAttr("disabled").removeClass('required').val('').trigger('change').addClass('required');
            $('#my-saved-cards').modal('hide');
        });

        $('#btn-sel-saved-card').on('click',function(){
            $('#my-saved-cards').modal('show');
        });

        if(show_cards_popup==1) {
            $('.custom-radio-block').show();
            var card_selected = "{{ ($stripe_customer) ? $stripe_customer->default_source : '' }}";
            if(card_selected!='') {
                $('#select_card').val(card_selected).trigger('change');
                $('#i_card_num').trigger('focus');
                $('#v_card_name').trigger('focus');
            }
        } else {
            $('.custom-radio-block').hide();
        }

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
                        total_amount = response.total;
                        showPayForm(wallet_balance,total_amount);
                    } else {
                        $('.show_discount').closest('p').addClass('d-none');
                        $('.show_total_total').html(response.total);
                    }
                });
            }
        });

        KTReservationFrontend.init();
        KTReservationFrontend.paymentInfo();
    });

    $('.pay-select-fields').on('change',function(){
        $(this).blur();
    });

    
</script>
@stop

@stop
        
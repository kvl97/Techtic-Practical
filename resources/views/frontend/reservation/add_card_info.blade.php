@extends('frontend.layouts.default')
@section('content')

    
    <div class="main-content">

        <!-- contact section -->
        <section class="contact-section mt-5 mb-5">
            <div class="container">
                <div class="profile-quick-links">
                     <ul>
                        <li><a href="{{FRONTEND_URL.'my-profile'}}">My Profile</a></li>
                        <li><a href="{{FRONTEND_URL}}my-address">Addresses</a></li>
                        <li><a href="#" class="">Reservation <i class="icon icon-down-arrow"></i></a>
                            <ul>
                                <li><a href="{{FRONTEND_URL.'upcoming-reservation'}}">Upcoming Reservation</a></li>
                                <li><a href="{{FRONTEND_URL.'past-reservation'}}">Past Reservation</a></li>
                            </ul>
                        </li>
                        <li class="active"><a href="{{FRONTEND_URL}}my-card-information">Card information</a></li>
                        <li><a href="{{FRONTEND_URL.'payment-history'}}">Payment History</a></li>   
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
                    <div class="contact-numbers col-md-12 col-xl-12 mt-4">
                        <div class="tab-content">
                            <div id="home"  class="tab-pane fade show active">
                                <section class="tab-pane fade show active login-section">
                                    <div class="container-fluid p-0">
                                        <div class="m-0">
                                            <div class="login-form-inner login-form-customer">
                                                <div class="">
                                                    <div class="heading">
                                                        <h3>Add Card Information</h3>
                                                    <p></p>
                                                    </div>
                                                    <form id="AddCardInfo" class="custom-form bg-white" action="{{ FRONTEND_URL }}my-card-information/add" method="POST" style="max-width: 750px;margin: 0px auto;">
                                                        <div class="row ml-1 mr-1">
                                                            <div class="col-md-12 form-group" id="error_msg"> </div>
                                                        
                                                            <div class="col-sm-12 form-group">
                                                                <label class="form-label" for="card number">Card Number</label>
                                                                <input class="form-input required number" name="i_card_num" type="text" id="i_card_num"  err-msg="Card Number"> 
                                                            </div>
                                                            <div class="row col-sm-6" style="padding:0 !important; margin:0 !important;">
                                                                <div class="col-sm-6 form-group select-default-style">
                                                                  
                                                                    <div class="select-field">
                                                                        <select id="i_card_exp_month" name="i_card_exp_month" class="form-input required" err-msg="Expiry month">
                                                                            <option value="">Select month</option>
                                                                          
                                                                            <?php for($i=01; $i <= 12; $i++) { ?>
                                                                            <option value="{{$i}}" > {{$i}} </option><?php } ?>
                                                                           
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6 form-group select-default-style">
                                                                    <div class="select-field">
                                                                        <select id="i_card_exp_year" name="i_card_exp_year" class="form-input required" err-msg="Expiry year">
                                                                            <option value="">Select year</option>
                                                                          
                                                                            <?php for($i=$currunt_year; $i <= $rest_year; $i++) { ?>
                                                                            <option value="{{$i}}" > {{$i}} </option><?php } ?>
                                                                           
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6 form-group">
                                                                <label class="form-label" for="cvc">CVC</label>
                                                                <input class="form-input required number" name="i_cvc" type="password" id="i_cvc"  err-msg="CVC"> 
                                                            </div>                   
                                                            <div class="col-sm-12 text-center mt-1">
                                                                <button type="submit" class="btn btn-md btn-red" id="my_profile_page" value="sign in">submit</button>
                                                                <a href="{{FRONTEND_URL.'my-card-information'}}" class="btn btn-md  btn-secondary" >Cancel</a>
                                                            </div>
                                                            
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    

@section('custom_js')
<script src="{{ asset('frontend/assets/js/login-frontend.js') }}"></script>
<script>
    $(document).ready(function() {
        
       
        $(document).on('submit','#AddCardInfo',function(e){
            e.preventDefault();
            if(!form_valid('#AddCardInfo')) {
                return false;
            }else {
                var action =  $(this).attr('action');
                var data = $('#AddCardInfo').serialize()
                var form = $(this).closest('form');
                $.post(action,data,function(response) {
                    if(response.status == 'TRUE') {
                        window.location.href = response.redirect_url;
                    }else if ($.trim(response.status) == 'ERROR') {
                        
                                                      
                        $("#error_msg").html('<div class="alert alert-danger alert-dismissible" role="alert"><span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></span>'+response.message+'</div>');
                        $("html, body").animate({
                            scrollTop: 0
                        }, 1000);
                        

                } else {
                        response = $.parseJSON(response);
                        $(response).each(function (i, val) {
                            $.each(val, function (key, v) {
                                $('#' + key).closest('.form-group').addClass('is-invalid');
                                $('#' + key).after('<div id="#' + key + '_error" class="help-block invalid-feedback">' + v + '.</div>');
                                $('#' + key + '_error').show();
                            });
                        });
                        

                        if ($('.is-invalid').length > 0) {
                            $('html, body').animate({
                                scrollTop: $('.is-invalid').first().offset().top - 200
                            }, 1000);

                            $('.is-invalid').first().focus()
                        }
                    }
                });
            }
        });

		
    });
</script>
@stop

@stop

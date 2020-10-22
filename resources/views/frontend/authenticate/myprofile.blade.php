@extends('frontend.layouts.default')
@section('content')

    
    <div class="main-content">

        <!-- contact section -->
        <section class="contact-section mt-5 mb-5">
            <div class="container">
                <div class="profile-quick-links">
                     <ul>
                        <li class="active"><a href="{{FRONTEND_URL.'my-profile'}}">My Profile</a></li>
                        <li><a href="{{FRONTEND_URL.'upcoming-reservation'}}">Upcoming Reservation</a></li>
                        <li><a href="{{FRONTEND_URL.'past-reservation'}}">Past Reservation</a></li>
                        @if($user['customer_stripe_id'] != '' )
                            <li><a href="{{FRONTEND_URL}}my-card-information">Card Info</a></li>
                        @endif
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
                                                        <h3>My Profile</h3>
                                                        <p></p>
                                                    </div>
                                                    <form id="MyProfileFrm" class="custom-form bg-white" action="{{ FRONTEND_URL }}my-profile" method="POST">
                                                        <div class="row ml-1 mr-1">
                                                        <div class="col-md-12 form-group">                             
                                                            <?php
                                                            $success_msg = Session::get('success-message');
                                                            if ($success_msg) {
                                                                ?>
                                                                <div class="alert alert-success alert-dismissible">
                                                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                                    {{ $success_msg }}
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                            <div class="col-sm-6 form-group">
                                                                <label class="form-label" for="firstname">First Name</label>
                                                                <input class="form-input required" name="v_firstname" type="text" id="v_firstname" value="{{ $user->v_firstname ? $user->v_firstname : ''  }}" err-msg="First Name"> 
                                                            </div>
                                                            <div class="col-sm-6 form-group">
                                                                <label class="form-label" for="lastname">Last Name</label>
                                                                <input class="form-input required"  name="v_lastname" type="text" id="v_lastname" value="{{ $user->v_lastname ? $user->v_lastname : ''  }}" err-msg="Last Name">
                                                            </div>
                                                            <div class="col-md-6 form-group">
                                                                <label class="form-label" for="email">E-Mail Address</label>
                                                                <input class="form-input required email" name="v_email" type="text" id="v_email" value="{{ $user->v_email ? $user->v_email : ''  }}" err-msg="E-Mail Address" autocomplete="off">
                                                            </div>
                                                            <div class="col-sm-6 form-group">
                                                                <label class="form-label" for="address">Date of Birth</label>
                                                                <input class="form-input date_picker"  name="d_dob"  type="text" value="{{ isset($user->d_dob) ? date('m/d/Y',strtotime($user->d_dob)) : ''}}" err-msg="Street Address" onblur="$(this).attr('readonly','readonly');" readonly="readonly">
                                                            </div>
                                                            <div class="col-sm-6 form-group">
                                                                <label class="form-label" for="password">Password</label>
                                                                <input class="form-input"  name="password"  type="password" id="password" err-msg="Password">
                                                            </div>
                                                            <div class="col-sm-6 form-group">
                                                                <label class="form-label" for="password">Confirm Password</label>
                                                                <input class="form-input"  name="cpassword" type="password" id="cpassword" equalTo="password" err-msg="Confirm Password">
                                                            </div>
                                                            <div class="col-sm-6 form-group">
                                                                <label class="form-label" for="phone">Cell Number</label>
                                                                <input class="form-input required phone"  name="v_phone" type="tel" id="v_phone"  value="{{ $user->v_phone ? $user->v_phone : ''  }}" err-msg="Cell Number">
                                                            </div>
                                                            
                                                            <div class="col-sm-6 form-group">
                                                                <label class="form-label" for="phone">Landline Number</label>
                                                                <input class="form-input"  name="v_landline_number" type="tel" id="v_landline_number"  value="{{ $user->v_landline_number ? $user->v_landline_number : ''  }}" err-msg="Landline Number">
                                                            </div> 
                                                            <div class="col-md-12 form-group custom-radio-wrapper flex-column">
                                                                <label class="custom-radio-label" for="phone">Gender</label>
                                                                <div class="d-flex">
                                                                    <div class="custom-radio-block">
                                                                        <input type="radio" id="radio7" name="gender" value="Male" {{ ($user->e_gender == "Male") ? 'checked="checked"' : "" }} >
                                                                        <label for="radio7" style="color: #565656;">Male</label>
                                                                    </div>
                                                                    <div class="custom-radio-block">
                                                                        <input type="radio" id="radio8" name="gender" value="Female" {{ ($user->e_gender == "Female") ? 'checked="checked"' : "" }} >
                                                                        <label for="radio8" style="color: #565656;">Female</label>
                                                                    </div>
                                                                    <div class="custom-radio-block">
                                                                        <input type="radio" id="radio9" name="gender" value="Other" {{ ($user->e_gender == "Other") ? 'checked="checked"' : "" }} >
                                                                        <label for="radio9" style="color: #565656;">Other</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                                                     
                                                            <div class="col-sm-12 text-center">
                                                                <button type="submit" class="btn btn-md btn-red" id="my_profile_page" type="submit" value="sign in">submit</button>
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
        $('.custom-form input , .custom-form select, .custom-form textarea').parents('.form-group').addClass('focused');
        KTLoginFrontend.init(); 

        var date = new Date();
        date.setDate(date.getDate());
        $('.date_picker').datepicker({
            format: 'mm/dd/yyyy',
            autoclose: true,
            endDate: date,
            todayHighlight: true,
            orientation: 'auto'
        }).on('changeDate', function(e) {
            $('.date_picker').trigger('focus');
        });
       
       

		
    });
</script>
@stop

@stop

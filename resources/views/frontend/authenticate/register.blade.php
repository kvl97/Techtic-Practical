@extends('frontend.layouts.default')
@section('content')

    <div class="main-content">

        <!-- login section -->
        <section class="login-section">
            <div class="container-fluid p-0">
                <div class="login-wrapper row m-0">
                    <div class="login-form col-md-6 space-md">
                        <div class="login-form-inner">
                            <div class="heading">
                                <h3>Sign Up</h3>
                                <p>Please fill in this form to create an account!</p>
                            </div>
                            <form id="register_form" class="custom-form" action="{{ FRONTEND_URL }}register">
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label class="form-label" for="firstname">First Name</label>
                                        <input class="form-input required" name="v_firstname" type="text" id="v_firstname" err-msg="First Name">
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="form-label" for="lastname">Last Name</label>
                                        <input class="form-input required"  name="v_lastname" type="text" id="v_lastname" err-msg="Last Name">
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label class="form-label" for="email">E-Mail Address</label>
                                        <input class="form-input required email" name="v_email" type="text" id="v_email" err-msg="E-Mail Address">
                                    </div>
                                    <div class="col-sm-12 form-group">
                                        <label class="form-label" for="password">Password</label>
                                        <input class="form-input required"  name="password"  type="password" id="password" err-msg="Password">
                                    </div>
                                   
                                    <div class="col-sm-12 form-group">
                                        <label class="form-label" for="phone">Cell number</label>
                                        <input class="form-input required cell_number"  name="v_phone" type="tel" id="cell_number" err-msg="cell number">
                                    </div>
                                    <div class="col-sm-12 form-group">
                                        <label class="form-label" for="phone">Landline Number</label>
                                        <input class="form-input"  name="v_landline_number" type="tel" id="v_landline_number" err-msg="Landline Number">
                                    </div>
                                    
                                    <div class="col-sm-12 form-group">
                                        <label class="form-label" for="address">Street Address</label>
                                        <input class="form-input required"  name="v_address"  type="text" id="v_address" err-msg="Street Address">
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="form-label" for="city">City</label>
                                        <input class="form-input required" name="v_city" type="text" id="city" err-msg="city">
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="form-label" for="state">State</label>
                                        <input class="form-input required" name="v_state"  type="text" id="state" err-msg="state">
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="form-label" for="country">Country</label>
                                        <input class="form-input required" name="v_country"  type="text" id="country" err-msg="country" autocomplete="off">
                                    </div>   
                                    <div class="col-sm-6 form-group">
                                        <label class="form-label" for="postal_code">Postal / Zip Code</label>
                                        <input class="form-input required" name="v_postal_code"  type="text" id="postal_code" err-msg="Postal / Zip Code">
                                    </div>                                          
                                    <div class="col-sm-12 form-group mt-3">
                                        {{-- <input class="btn btn-red" type="submit" id="kt_register_signin_front" value="sign in"> --}}
                                        <button type="submit" class="btn btn-md btn-red w-100" id="kt_register_signin_front" value="sign in">submit</button>
                                    </div>
                                    <div class="col-sm-12 form-group text-right">
                                    <span>Already registered <a href="{{FRONTEND_URL}}login">Sign in?</a></span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="login-banner-image col-md-6"
                        style="background: url({{ asset('frontend/assets/images/login-banner.jpg') }}) no-repeat center / cover;">
                        <div class="ellipse">
                            <h3>Rocket Transportation</h3>
                            <p>The ONLY true DOOR to DOOR SeaTac Airport shuttle
                                serving Washington’s Beautiful North Olympic <br/> Peninsula.</p>
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
        $(document).ready(function() {
			KTLoginFrontend.init();
		});
    });
</script>
@stop

@stop

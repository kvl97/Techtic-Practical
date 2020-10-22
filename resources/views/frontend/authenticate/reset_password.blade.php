@extends('frontend.layouts.default')
@section('content')

    <div class="main-content">

        <!-- login section -->
        <section class="login-section">
            <div class="container-fluid p-0">
                <div class="login-wrapper row m-0">
                    <div class="login-form col-md-6 space-md">
                        <div class="login-form-inner login-form-customer">
                            <div class="heading">
                                <h3>Reset your password here</h3>
                                <p></p>
                            </div>
                            <form  class="custom-form" action="{!!FRONTEND_URL!!}reset-password/{{ $record->remember_token }}" id="reset_password_form" method="POST" >
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label class="form-label" for="password">New Password</label>
                                        <input class="form-input required" type="password" name="password" type="text" id="password" err-msg="New Password">
                                        <span class="icon icon-lock"></span>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label class="form-label" for="confirm_password">Password</label>
                                        <input class="form-input required" name="confirm_password" type="password" id="confirm_password" equalTo="password" err-msg="Password">
                                        <span class="icon icon-lock"></span>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <button type="submit" class="btn btn-md btn-red w-100" id="kt_reset_password_front" type="submit" value="sign in">submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="login-banner-image col-md-6"
                        style="background: url({{ asset('frontend/assets/images/login-banner.jpg') }}) no-repeat center / cover;">
                        <div class="ellipse">
                            <h3>Rocket Transportation</h3>
                            <p>Rocket Transportation is the ONLY true DOOR to DOOR Sea-Tac Airport shuttle
                                serving Washington’s Beautiful Olympic Peninsula.</p>
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

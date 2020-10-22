@extends('frontend.layouts.default')
@section('content')

    <div class="main-content">

        <!-- login section -->
        <section class="login-section">
            <div class="container-fluid p-0">
                <div class="login-wrapper login-form-wrapper row m-0">
                    <div class="login-form col-md-6 space-md">
                        <div class="login-form-inner login-form-customer">
                            <div class="heading">
                                <h3>Sign In</h3>
                                <p>Please enter your Email Id and Password to login into system.</p>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group pl-10 pr-10">                             
                                    <?php
                                    $success_msg = Session::get('msg');
                                    if ($success_msg) {
                                        ?>
                                        <div class="alert alert-success alert-dismissible">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            {{ $success_msg }}
                                        </div>
                                    <?php } ?>
                                   
                                    <div class="alert alert-danger invalid-error-message" style="display:none;">
                                        <a href="" class="close" data-close="alert" aria-label="close">&times;</a>
                                        <span class="message"></span>
                                    </div>
                                    
                                </div>
                            </div>
                            <form class="custom-form" action="{{ FRONTEND_URL }}login" id="loginFrm" method="POST">
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label class="form-label" for="v_email">Email Id</label>
                                        <input class="form-input required email" name="v_email" type="text" id="v_email" err-msg="Email Id">
                                        <span class="icon fa fa-envelope-o"></span>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label class="form-label" for="password">Password</label>
                                        <input class="form-input required" name="password" type="password" id="password" err-msg="password" autocomplete="off">
                                        <span class="icon icon-lock"></span>
                                    </div>
                                   
                                    <div class="col-md-12 form-group">
                                       
                                        <button type="submit" class="btn btn-md btn-red w-100 " id="kt_login_signin_front" type="submit" value="sign in">submit</button>
                                    </div>
                                    
                                </div>
                            </form>
                        </div>
                        
                    </div>
                    
                    <div class="login-banner-image col-md-6"
                        style="background: url({{ asset('frontend/assets/images/login-banner.jpg') }}) no-repeat center / cover;">
                        <div class="ellipse">
                            <h3>Blog</h3>
                            <p>The ONLY true DOOR to DOOR SeaTac Airport shuttle serving Washington’s Beautiful North Olympic <br/> Peninsula.</p>
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

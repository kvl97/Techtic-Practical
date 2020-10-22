@extends('frontend.layouts.default')
@section('content')
<section class="hero-section inner-hero-banner">
    <div class="inner-banner-bg"
        style="background: url({{SITE_URL.'frontend/assets/images/contact-bg.jpg'}}) no-repeat center / cover;"></div>
    <div class="container">
        <div
            class="hero-wrapper row justify-content-between align-items-center flex-column flex-sm-row py-4">
            <div class="title col-sm-4">
                <h3 class="m-0">{{$title}}</h3>
            </div>
            <div class="col-sm-4 d-flex justify-content-start justify-content-md-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{SITE_URL}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>
<section class="about-section space-md">
    <div class="container">
    <?php
        $success_msg = Session::get('msg');
        if($success_msg) {
            ?>
                <div class="alert alert-success alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{ $success_msg }}
                </div>
        <?php } ?>
    {!! $blog['t_content'] !!}

    @if(count($comment) > 0)
        <h3 class="mt-5">Comments</h3>
        @foreach($comment as $value)
        <p class="mt-3">{{$value['v_comment']}}</p>
        <p class="mt-0" style="text-align: right;"><i>-{{$value['user'] ? $value['user']['v_firstname'].' '. $value['user']['v_lastname'] : '-'}}</i></p>
        @endforeach
    @endif
        <form action="{{ FRONTEND_URL }}blog/{{ $blog->id }}" class="custom-form mt-5" id="contactUsFrm" method="POST">
           
            <div class="row">
                
                <div class="col-sm-12 form-group">
                    <label class="form-label" for="v_comment">Your Comment</label>
                    <textarea class="form-input required" name="v_comment" id="v_comment" err-msg="Comment"></textarea>
                </div>
                <div class="col-sm-12 form-group mt-2" style="text-align: right;">
                    <button type="submit" class="btn btn-red" id="kt_contact_us"  value="sign in">submit</button>
                </div>
            </div>
        </form>
    </div>
    
</section>
<div class="modal fade bd-example-modal-sm p-md-0" id="kt_modal_login_account" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Login</h5>
                
            </div>
            <div class="row login-content"> 
                <div class="modal-body">
                    <form id="loginAccountFrm" class="custom-form" method="POST" action="{{ FRONTEND_URL }}login">
                        <div class="row ml-1 mr-1">
                            <div class="col-md-12 pl-10 pr-10 mb-3">    
                                <p style="font-size: 0.875rem">Please enter your Email Id and Password to login into system.</p>
                            </div>
                            <div class="col-md-12 pl-10 pr-10">                             
                                <div class="alert alert-danger invalid-error-message" style="display:none;">
                                    <a href="javascript:;" class="close" data-close="alert" aria-label="close">&times;</a>
                                    <span class="message">You have some form errors. Please check below.</span>
                                </div>
                            </div>
                            
                            <input class="form-input" name="login_redirect_url" type="hidden" id="login_redirect_url" value=""> 
                            <div class="col-sm-12 form-group focused">
                                <label class="form-label" for="v_email">Email Id</label>
                                <input class="form-input filled required" name="v_email" type="text" id="v_email" err-msg="Email Id"> 
                                <span class="icon fa fa-envelope-o"></span>
                            </div>
                            <div class="col-sm-12 form-group" style="margin-bottom: 1.5rem!important">
                                <label class="form-label" for="password">Password</label>
                                <input class="form-input required" name="password" type="password" id="password" err-msg="Password"> 
                                <span class="icon icon-lock"></span>
                            </div>
                        
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-xs btn-red login_account_popup">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@section('custom_js')
<script>
    $(document).ready(function() {
        KTFrontend.init();
    });
</script>
@stop

@stop

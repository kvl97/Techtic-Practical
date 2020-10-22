@extends('frontend.layouts.default')
@section('content')

<div class="main-content">

    <!-- contact section -->
    <section class="contact-section pb-5 pt-5">
        <div class="container">
            <div class="contact-wrapper row">
                <div class="contact-form col-md-7 col-xl-8 mb-4 mb-md-0">
                    <div class="contact-form-inner p-4 p-md-5">
                        <div class="heading mb-2 mb-md-4">
                            <h3>Drop us a message for any query</h3>
                        </div>
                        <form action="{{ FRONTEND_URL }}contact-us" class="custom-form" id="contactUsFrm" method="POST">
                            <?php
                                $success_msg = Session::get('msg');
                                if($success_msg) {
                                    ?>
                                        <div class="alert alert-success alert-dismissible">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        {{ $success_msg }}
                                        </div>
                                <?php } ?>
                            <div class="row">
                                <div class="col-sm-6 form-group">
                                    <label class="form-label" for="v_firstname">First Name</label>
                                    <input class="form-input required" name="v_firstname" type="text" id="v_firstname" err-msg="First Name">
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label class="form-label" for="field-2">Last Name</label>
                                    <input class="form-input required" name="v_lastname" type="text" id="v_lastname" err-msg="Last Name">
                                </div>
                                <div class="col-sm-12 form-group">
                                    <label class="form-label" for="v_email">E-Mail</label>
                                    <input class="form-input required email" name="v_email" type="text" id="v_email" err-msg="E-Mail">
                                </div>
                                <div class="col-sm-12 form-group">
                                    <label class="form-label" for="v_phone">Phone</label>
                                    <input class="form-input required phone" name="v_phone" type="tel" id="v_phone" err-msg="Phone Number">
                                </div>
                                <div class="col-sm-12 form-group">
                                    <label class="form-label" for="t_message">Your Message</label>
                                    <textarea class="form-input" name="t_message" id="t_message"></textarea>
                                </div>
                                <div class="col-sm-12 form-group mt-2">
                                    <button type="submit" class="btn btn-red" id="kt_contact_us"  value="sign in">submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="contact-numbers col-md-5 col-xl-4">
                    <div class="contact-numbers-inner">
                        <div class="title px-4 py-2">
                            <h5 class="my-1">Rocket Contact Numbers</h5>
                        </div>
                        <div class="contact-list p-4">
                            <ul>
                                <li><i class="icon icon-call"></i><a href="">{!! $siteSetting['v_comp_tel_1'] ? $siteSetting['v_comp_tel_1'] : '' !!}</a></li>
                                <li><i class="icon icon-call"></i><a href="">{!! $siteSetting['v_comp_tel_2'] ? $siteSetting['v_comp_tel_2'] : '' !!}</a></li>
                                <li><i class="icon icon-email"></i><a href="">{!! $siteSetting['v_comp_email'] ? $siteSetting['v_comp_email'] : '' !!}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                   {!! $contant_of_contactUs['t_content']!!}
                </div>
            </div>
        </div>
    </section>
</div>

@section('custom_js')
<script>
    $(document).ready(function() {
        KTFrontend.init();
    });
</script>
@stop

@stop

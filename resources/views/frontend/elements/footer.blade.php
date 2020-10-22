<!-- footer part -->
<?php
    $curr_url = Route::getFacadeRoot()->current()->uri();       
?>
<footer class="main-footer">
    <div class="container">
        <div class="footer-top row py-5 my-0 my-md-2">
            <div class="footer-block footer-info col-md-4 mb-4 mb-md-0">
                <a href="{{SITE_URL}}" class="logo-footer mb-4">
                    <img src="{{ asset('frontend/assets/images/logo-white.png') }}" alt="">
                </a>
                <p>@if(!empty($footer_contact['v_site_description'])){!! $footer_contact['v_site_description'] !!}@endif</p>
                <div class="hours">
                    <span>OFFICE HOURS:</span>
                    <p>@if(!empty($footer_contact['v_office_hours'])){!! nl2br($footer_contact['v_office_hours']) !!}@endif</p>
                </div>
            </div>
            <div class="footer-block col-sm-6 col-md-4 mb-4 mb-md-0">
                <div class="title mb-3 mb-md-4">Useful Links</div>
                <ul class="useful-links d-flex flex-wrap">
                    
                </ul>
            </div>
            <div class="footer-block col-sm-6 col-md-4 mb-2 mb-md-0">
                <div class="title mb-3 mb-md-4">Need Help?</div>
                <span class="sub-title">Contact us via phone or email:</span>
                <ul class="footer-contact my-3">
                    <li><a href="tel:{!! $footer_contact['v_comp_tel_1'] !!}"><i class="icon icon-call"></i>@if(!empty($footer_contact['v_comp_tel_1'])){!! $footer_contact['v_comp_tel_1'] !!}@endif</a></li>
                    <li><a href="el:{!! $footer_contact['v_comp_tel_2'] !!}"><i class="icon icon-call"></i>@if(!empty($footer_contact['v_comp_tel_2'])){!! $footer_contact['v_comp_tel_2'] !!}@endif </a></li>
                    <li><a href="mailto:{{$footer_contact['v_comp_email']}}" target="_blank"><i class="icon icon-email"></i>@if(!empty($footer_contact['v_comp_email'])){!! $footer_contact['v_comp_email'] !!}@endif</a></li>
                </ul>
                <div class="social">
                    <p>Follow Us</p>
                    <ul>
                        <li><a href="@if(!empty($footer_contact['v_facebook_link'])){!! $footer_contact['v_facebook_link'] !!}@endif" target="_blank"><i class="icon icon-facebook"></i></a></li>
                        <li><a href="@if(!empty($footer_contact['v_twitter_link'])){!! $footer_contact['v_twitter_link'] !!}@endif" target="_blank"><i class="icon icon-twitter"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-wrapper">
                <div class="copyright py-3 pr-0 pr-md-4">
                    <p>Copyright 2020 Rocket Transportation LLC. All rights reserved.</p>
                </div>
                
            </div>
        </div>
    </div>
</footer>
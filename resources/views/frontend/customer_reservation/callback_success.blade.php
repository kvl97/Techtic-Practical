@extends('frontend.layouts.default')
@section('content')
<div class="main-content">
    <!-- Thank you -->
    <section class="thank-you-block" style="background-image: url({{ asset('frontend/assets/images/rocket-bg.png') }});">
        <div class="container d-flex align-items-start align-items-md-center justify-content-center">
            <div class="thank-you-block-inner text-center pt-5 pb-5">
                <img src="{{ asset('frontend/assets/images/thank-you.png') }}" alt="" title="" />
                <h3 class="h3">Success!</h3>
                <p>Thank you for your request!<br>We received your request for check availability within your desired Travel Window.<br> We will contact you with our findings.</p>
            </div>
            <div class="d-none" id="ps_desc">
            </div>
        </div>
    </section>
</div>
@stop
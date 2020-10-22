@extends('frontend.layouts.default')
@section('content')

@if(isset($expired) && $expired==1)
<div class="main-content">
    <!-- Thank you -->
    <section class="thank-you-block" style="background-image: url({{ asset('frontend/assets/images/rocket-bg.png') }});">
        <div class="container d-flex align-items-start align-items-md-center justify-content-center">
            <div class="thank-you-block-inner text-center pt-5 pb-5">
                <img src="{{ asset('frontend/assets/images/pay-failed.png') }}" alt="" title="" />
                <h3 class="h3">Link has been expired!</h3>                
            </div>
            <div class="d-none" id="ps_desc">
            </div>
        </div>
    </section>
</div>
@else
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="error-template space-md text-center">
                <h1>
                    Oops!</h1>
                <h2>
                    404 Not Found</h2>
                <div class="error-details">
                    <p>Sorry, an error has occured, Requested page not found!</p>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endif

@section('custom_js')
<script>
    $(document).ready(function() {
        KTFrontend.init();
    });
</script>
@stop

@stop

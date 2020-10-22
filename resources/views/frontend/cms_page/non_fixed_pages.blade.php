@extends('frontend.layouts.default')
@section('content')
<section class="hero-section inner-hero-banner">
    <div class="inner-banner-bg"
        style="background: url({{SITE_URL.'frontend/assets/images/contact-bg.jpg'}}) no-repeat center / cover;"></div>
    <div class="container">
        <div
            class="hero-wrapper row justify-content-between align-items-center flex-column flex-sm-row py-4">
            <div class="title col-sm-4">
                <h1 class="m-0">{{$title}}</h1>
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
    {!! $contant_of_not_fixed_pages['t_content'] !!}
    </div>
</section>

@section('custom_js')
<script>
    $(document).ready(function() {
        KTFrontend.init();
    });
</script>
@stop

@stop

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
<div class="main-content">

<!-- FAQ -->
<section class="faq-section space-md">
    <div class="container">
        <div class="faq-wrapper row">
            <div class="col-12 col-md-12">
            {!! $contant_of_faqs['t_content'] !!}
                @if($faq_info)
                    @foreach($faq_info as $key => $value)   
                        @if($key == 0)   
                            <div class="faq-block mb-3 active">
                                <h5 class="accordian-title py-3 pl-3 pl-xl-4">{!! $value['v_question'] ? $value['v_question'] : '' !!}<span class="icon icon-down-arrow d-flex"></span></h5>
                                <div class="faq-content">
                                    <div class="px-3 px-xl-4 py-3">
                                        <p>{!! $value['t_answer'] ?  $value['t_answer']  : '' !!}</p>
                                    </div>
                                </div>
                            </div> 
                        @else
                            <div class="faq-block mb-3">
                                <h5 class="accordian-title py-3 pl-3 pl-xl-4">{!! $value['v_question'] ? $value['v_question'] : '' !!}<span class="icon icon-down-arrow d-flex"></span></h5>
                                <div class="faq-content">
                                    <div class="px-3 px-xl-4 py-3">
                                        <p>{!! $value['t_answer'] ?  $value['t_answer']  : '' !!}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        

                    @endforeach
                @endif
            </div>
            
        </div>
    </div>
</section>

</div>
@section('custom_js') 
<script>
    $(document).ready(function() {
        
    });
</script>
@stop

@stop

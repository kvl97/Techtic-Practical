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

<!-- contact section -->
    <section class="contact-section space-sm">
        <div class="container">
            <div class="contact-wrapper row">
                <div class="contact-my-address col-md-12 col-xl-12">
                   
                   
                    {!! $contant_of_offersPage['t_content'] !!}
                   
                        
                    @if(count($available_offers) > 0)
                        @foreach($available_offers as $value)
                            <div class="offers p-4">
                                <div class="row" >
                                    <div class="col-6 col-md-2 pb-3" >
                                        <div class="offers--cashback-content" >
                                            <span class="offers--big-font" title="">{{ $value['f_discount_percentage'] ?  $value['f_discount_percentage'].'%' : '$'.$value['d_discount_flat_price'] }}</span>
                                            <span class="offers--small-font">OFF</span>
                                        </div>
                                    </div>
                                    <div class="col-md-8 offers--card-content order-last order-md-0" >
                                        
                                        <a>{!! $value['v_notes'] !!}</a>
                                       
                                    </div>
                                   
                                        <div class="col-6 col-md-2 pb-3" >
                                            <div class="offers--get-code" >
                                                <div class="offers--code-link" >{{ $value['v_coupon_code']}}</div>
                                                @if(isset($value['d_expire_date']))
                                                <span>Expire: {{isset($value['d_expire_date']) ?  date(DATE_FORMAT,strtotime($value['d_expire_date'])) : '-'}}</span>
                                                @endif
                                            </div>
                                        </div>
                                  
                                </div>
                            </div>  
                        @endforeach
                    @else
                    <div class="offers p-3">
                        <div class="row" style="text-align: center;">
                            <div class="col-md-12 offers--card-content order-last order-md-0">                           
                                <a><h5>No offer available.</h5></a>                         
                            </div>
                        </div>
                    </div>
                    @endif
                                      
                    </div>  
                </div>
            </div>
    </section>
</div>

@section('custom_js')
<script>    
    $(document).on('click', '.offers--code-link', function() {
        var code = $(this).html();
        var dummy = document.createElement('input');
        document.body.appendChild(dummy);
        dummy.value = code;
        dummy.select();
        dummy.setSelectionRange(0, 99999)
        document.execCommand("copy");
        document.body.removeChild(dummy);
    })

</script>
@stop

@stop
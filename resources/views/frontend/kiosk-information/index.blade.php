<!DOCTYPE HTML>
<html>

    <head>
        <meta charset="UTF-8">
        <title>{{ SITE_NAME }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
        <meta name="description" content="" />
        <meta name="keywords" content=""/>
        <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
        <link rel="icon" type="image/png" href="{{ asset('frontend/assets/images/favicon.png') }}" />

        <!-- main css -->
        <link href="{{ asset('frontend/assets/css/style.css') }}{{CSS_VERSION}}" rel="stylesheet" />

        <!-- fonts css -->
        <link
            href="https://fonts.googleapis.com/css?family=Cabin:400,500,600,700|Poppins:100,200,300,400,500,600,700,800,900&display=swap"
            rel="stylesheet">
            <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap" rel="stylesheet">
    </head>
    <style>
    .gotoHome a{
        color:white !important;
    }
    .btn:hover, button :hover{
        background-color: #FE9600 !important;
        border: 1px solid !important;
    }
    </style>

    <body>
        <div class="wrapper bg-secondary">
            <div class="main-container">
                <div class="kiosk-information">
                    <div class="">
                        <div class="information-block">
                            <div class="info-header text-center mb-4">
                                <a href="{{SITE_URL}}" class="logo">
                                    <img src="{{ asset('frontend/assets/images/logo-white-new.png') }}" alt="">
                                </a>
                                <h3 class="mt-4 current_run_date"></h3> 
                            </div>

                            <div class="kiosk-slider-wrapper w-100 m-0 row">
                                <div class="sticky-header col-md-11 col-lg-10 mx-auto">
                                    <div class="info-block-header px-0 px-md-5 py-0 py-md-1 mb-2 bg-secondary text-white justify-content-between d-none d-md-flex w-100"
                                        role="alert">
                                        <div class="info-code w-10">Vehicle</div>
                                        <div class="depart-by w-20">Depart By</div>
                                        <div class="info-content w-30">Notice</div>
                                        <div class="driver w-10">Driver</div>
                                        <div class="extension w-10">Extension</div>
                                    </div>
                                </div>
                                
                                <div class="kiosk-slider col-md-11 col-lg-10 mx-auto">
                                    @if(isset($arrSlideData))
                                    @foreach ($arrSlideData as $key=>$value)
                                        <?php $i = 0; //pr($value);  ?> 
                                        <div class="info-wrapper">
                                            <input type="hidden" value="{{date('l, F d, Y', strtotime(str_replace('/', '-', $key)))}}" id="hidden_run_date" />
                                            @foreach ($value as $val)
                                               
                                                <div class="info-block px-0 px-md-5 py-0 py-md-3 mb-2 text-white d-flex justify-content-between flex-column flex-md-row" style="background-color:{{$arrColor['colors'][$i]}} !important; color:{{$strTextColor[$i]}} !important;">
                                                    <div class="info-code w-10" data-title="Vehicle">
                                                        {{ $val['vehicle_fleet']['v_vehicle_code'] }}  
                                                    </div>
                                                    <div class="depart-by w-20" data-title="Depart By">
                                                        {{ date('h:i:A', strtotime($val['t_scheduled_arr_time'])) }}
                                                    </div>
                                                    <div class="info-content w-30" data-title="Notice">
                                                        {{ $val['v_kiosk_notice'] }}
                                                    </div>
                                                    <div class="driver w-10" data-title="Driver">
                                                        {{ $val['driver']['v_dispatch_name'] }}
                                                    </div>
                                                    <div class="extension w-10" data-title="Extension">
                                                        {{ $val['driver_extension']['v_extension'] }}
                                                    </div>

                                                </div>
                                                <?php if($i == 5) {
                                                        $i = 0;
                                                    } else {
                                                        $i++;
                                                    }
                                                ?>
                                            @endforeach
                                        </div>
                                    @endforeach 
                                    @endif
                                </div>
                            </div>
                            
                            <div class="info-footer text-center mt-3">
                                {!! $contant_of_kioskInfo['t_content'] !!}
                                <a href="{{SITE_URL}}"><button type="button" class="btn btn-md btn-yellow mx-2 mb-2 mt-3">Go To Home Page</button></a>
                            </div>
                            
                        </div>
                    </div>
                    
                </div>
               
            </div>
            
        </div>
        
        <!-- jquery library -->
        <script src="{{ asset('frontend/assets/js/jquery-3.3.1.min.js') }}{{JS_VERSION}}"></script>
        
        <!-- vendor js -->
        
        
        <script src="{{ asset('frontend/assets/js/slick.min.js') }}{{JS_VERSION}}"></script>
        <script src="{{ asset('frontend/assets/js/jquery-ui.min.js') }}{{JS_VERSION}}"></script>
        <script src="{{ asset('frontend/assets/js/jquery.fancybox.min.js') }}{{JS_VERSION}}"></script>
        <script src="{{ asset('frontend/assets/js/popper.min.js') }}{{JS_VERSION}}"></script>
        <script src="{{ asset('frontend/assets/js/bootstrap.min.js') }}{{JS_VERSION}}"></script>
        <script src="{{ asset('frontend/assets/js/select2.min.js') }}{{JS_VERSION}}"></script>
        <script src="{{ asset('frontend/assets/js/jquery.mCustomScrollbar.js') }}{{JS_VERSION}}"></script>
        <!-- theme script -->
        <script src="{{ asset('frontend/assets/js/scripts.js') }}{{JS_VERSION}}"></script>
        <script src="{{ asset('frontend/assets/js/custom_script.js') }}{{JS_VERSION}}"></script>

        <!-- validation js script -->
        <script src="{{ asset('js/custom_validation.js') }}{{JS_VERSION}}"></script>
    
    </body>
</html>
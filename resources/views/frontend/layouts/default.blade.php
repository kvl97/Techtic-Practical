<!DOCTYPE HTML>
<html>

<head>
    <meta charset="UTF-8">
    <title><?php echo (isset($title) && ($title!="")) ? $title : SITE_NAME ?> | {{ SITE_NAME }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="description" content="<?php if(isset($v_meta_description)) { echo $v_meta_description; } ?>" /> 
    <meta name="keywords" content="<?php if(isset($v_meta_keywords)) { echo $v_meta_keywords; } ?>">
    <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
    <link rel="icon" type="image/png" href="{{ asset('frontend/assets/images/favicon.png') }}" />

    <!-- main css -->
    <link href="{{ asset('frontend/assets/css/style.css') }}{{CSS_VERSION}}" rel="stylesheet" />
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link href=" {{ asset('frontend/assets/plugins/bootstrap-datepicker/css/datepicker3.css')}}{{CSS_VERSION}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('frontend/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.css')}}{{CSS_VERSION}}" rel="stylesheet" type="text/css"/>
    <link  href="{{ asset('frontend/assets/css/select2.css') }}{{CSS_VERSION}}" rel="stylesheet"/>
    <link  href="{{ asset('frontend/assets/css/custom.css') }}{{CSS_VERSION}}" rel="stylesheet"/>
    <link href="{{ asset('frontend/assets/css/autocomplete-bootstrap.css')}}{{CSS_VERSION}}" rel="stylesheet"/>

    <!-- fonts css -->
    <link
        href="https://fonts.googleapis.com/css?family=Cabin:400,500,600,700|Poppins:100,200,300,400,500,600,700,800,900&display=swap"
        rel="stylesheet">
</head>

<body>

    <!-- start -->
    <div class="wrapper">
        <div class="main-container">
            @include('frontend.elements.header')
            
            @yield('content')
        </div>

        @include('frontend.elements.footer')        
    </div>

    <!-- jquery library -->
    <script src="{{ asset('frontend/assets/js/jquery-3.3.1.min.js') }}"></script>
    <!--<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>-->

    <!-- vendor js -->
    <!-- <script src="assets/js/slick-1.8.0.js"></script> -->
    <script src="{{ asset('frontend/assets/js/jquery-ui.min.js') }}"></script>
    <script src="{{asset('frontend/assets/js/autocomplete-bootstrap.js')}}"></script>
    <script src="{{ asset('frontend/assets/js/slick.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/jquery.fancybox.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/jquery.cokie.min.js') }}"></script>
   
    <!-- theme script -->
    <script src="{{ asset('frontend/assets/js/scripts.js') }}{{JS_VERSION}}"></script>
    <script src="{{ asset('frontend/assets/js/custom_script.js') }}{{JS_VERSION}}"></script>
    <script src="{{ asset('frontend/assets/js/jquery.dataTables.min.js') }}{{JS_VERSION}}"></script>
    <script src="{{ asset('frontend/assets/plugins/bootstrap/dataTables.bootstrap.js') }}{{JS_VERSION}}"></script>
    
    <script src="{{ asset('frontend/assets/js/datatable.js') }}{{JS_VERSION}}"></script>
    <script src="{{ asset('frontend/assets/js/table-ajax.js') }}{{JS_VERSION}}"></script>
    <script src="{{ asset('frontend/assets/js/reservation-frontend.js') }}{{JS_VERSION}}"></script>
    
    <script src="{{ asset('frontend/assets/js/jquery.mCustomScrollbar.js') }}"></script>
    <!-- validation js script -->
    <script src="{{ asset('frontend/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}{{JS_VERSION}}"></script>
    <script type="text/javascript" src="{{ asset('frontend/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.js') }}{{JS_VERSION}}"></script>
    <script src="{{ asset('js/custom_validation.js') }}{{JS_VERSION}}"></script>
    <script src="{{ asset('frontend/assets/js/moment.min.js') }}{{JS_VERSION}}"></script>
    
    <script type="text/javascript">
        var SITE_URL = "{{ SITE_URL }}";
    </script>  
    @yield('custom_js')
</body>

</html>
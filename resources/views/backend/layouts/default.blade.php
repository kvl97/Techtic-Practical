<!DOCTYPE html>
<html lang="en">

	<!-- begin::Head -->
	<head>
		<base href="public/">
		<meta charset="utf-8" />
		<title><?php echo (isset($title) && ($title!="")) ? $title : SITE_NAME ?> | {{ SITE_NAME }}</title>
		<meta name="description" content="Latest updates and statistic charts">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!--begin::Fonts -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">

		<!--end::Fonts -->

		<link href="{{ asset('assets/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" >
		<link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" >
		

		<!--end::Global Theme Styles -->

		<!--begin::Layout Skins(used by all pages) -->

		<!--end::Layout Skins -->

		<link href="{{ asset('css/custom.css') }}{{CSS_VERSION}}" rel="stylesheet" type="text/css" >
		<link href="{{ asset('plugins/jcrop/css/jquery.Jcrop.min.css') }}" rel="stylesheet" type="text/css"/>
		<link rel="icon" href="{{ SITE_URL }}images/favicon.png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.css" />
	<link href="{{ asset('css/reservation-view.css') }}" rel="stylesheet" type="text/css" >
        
	</head>

	<!-- end::Head -->

	<!-- begin::Body -->
	<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-aside--enabled kt-aside--fixed kt-page--loading">

		<!-- begin:: Page -->

		
		<!-- begin:: Header Mobile -->
		<div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
			<div class="kt-header-mobile__logo">
				<a href="{{ ADMIN_URL }}">
					<img alt="Logo" src="{{ SITE_URL }}images/logo-white.png" style="width: 140px"/>
				</a>
			</div>
			<div class="kt-header-mobile__toolbar">
				<button class="kt-header-mobile__toolbar-toggler kt-header-mobile__toolbar-toggler--left" id="kt_aside_mobile_toggler"><span></span></button>
				
				<button class="kt-header-mobile__toolbar-topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more"></i></button>
			</div>
		</div>

		<!-- end:: Header Mobile -->
		<div class="kt-grid kt-grid--hor kt-grid--root">
			<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">

				<!-- begin:: Aside -->
								
				@include('backend.elements.leftsidebar')


				<!-- end:: Aside -->
				<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">

					<!-- begin:: Header -->
					@include('backend.elements.header')

					<!-- end:: Header -->

					@yield('content')

					<!-- begin:: Footer -->

					@include('backend.elements.footer')

					<!-- end:: Footer -->
				</div>
			</div>
		</div>

		<!-- end:: Page -->

	

		<!-- begin::Scrolltop -->
		<div id="kt_scrolltop" class="kt-scrolltop">
			<i class="fa fa-arrow-up"></i>
		</div>

		<!-- end::Scrolltop -->

		
		<!-- begin::Global Config(global config for global JS sciprts) -->
		<script>
			var KTAppOptions = {
				"colors": {
					"state": {
						"brand": "#5D59A6",
						"light": "#ffffff",
						"dark": "#282a3c",
						"primary": "#5867dd",
						"success": "#34bfa3",
						"info": "#36a3f7",
						"warning": "#ffb822",
						"danger": "#fd3995"
					},
					"base": {
						"label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
						"shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
					}
				}
			};
		</script>

		<!-- end::Global Config -->

		<script src="{{ asset('js/app.js') }}"></script>
		<script src="{{ asset('js/jquery.cokie.min.js') }}"></script>
		<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
		<script src="{{ asset('js/popper.min.js') }}"></script>
		<script src="{{ asset('js/bootstrap.min.js') }}"></script>

		<script src="{{ asset('assets/datatables/datatables.bundle.js') }}"></script>
		<script src="{{ asset('assets/datatables/DataTables.js') }}{{JS_VERSION}}"></script>
        <script src="{{asset('js/ckeditor-classic.js') }}"></script>
        <script src="{{asset('js/ckeditor-classic.bundle.js') }}" ></script>
		<script src="{{ asset('js/custom_validation.js') }}{{JS_VERSION}}"></script>
        <script src="{{ asset('js/custom_script.js') }}{{JS_VERSION}}"></script>
        <script src="{{asset('js/table-editable.js') }}{{JS_VERSION}}"></script>
        <script src="{{asset('plugins/jcrop/js/jquery.color.js') }}{{JS_VERSION}}" ></script>
        <script src="{{asset('plugins/jcrop/js/jquery.Jcrop.min.js') }}{{JS_VERSION}}" ></script>
        <script src="{{asset('js/image_upload.js') }}{{JS_VERSION}}"></script>
        <script src="{{asset('js/bootstrap-datepicker.js') }}{{JS_VERSION}}"></script>
        <script src="{{asset('js/bootstrap-timepicker.js') }}{{JS_VERSION}}"></script>
        <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.js"></script>
        <script src="{{ asset('js/jQuery.print.js') }}{{JS_VERSION}}"></script>
        
		<!--end::Page Scripts -->


		<!--begin:: Custom Scripts -->
		<script type="text/javascript">
			var ADMIN_URL = "{{ ADMIN_URL }}";
			var SITE_URL = "{{ SITE_URL }}";
			var ADMIN_USER_PROFILE_THUMB_IMG_PATH = "{{ ADMIN_USER_PROFILE_THUMB_IMG_PATH }}";
			
		</script>

		@yield('custom_js')
		<!--end:: Custom Scripts -->
		
	</body>

	<!-- end::Body -->
</html>
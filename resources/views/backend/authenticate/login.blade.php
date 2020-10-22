@extends('backend.layouts.login')
@section('content')

<div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v6 kt-login--signin" id="kt_login">
	<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--desktop kt-grid--ver-desktop kt-grid--hor-tablet-and-mobile">
		<div class="kt-grid__item  kt-grid__item--order-tablet-and-mobile-2  kt-grid kt-grid--hor kt-login__aside">
			<div class="kt-login__wrapper">
				<div class="kt-login__container">
					<div class="kt-login__body">
						<div class="kt-login__logo">
							<a href="{{ ADMIN_URL }}">
								<img src="{{ SITE_URL }}images/logo-black.png" style="width: 200px">
							</a>
						</div>
						<div class="kt-login__signin">

							<div class="kt-login__head">
								<h3 class="kt-login__title">Sign In To Admin Panel</h3>
							</div>
							@if(Session::has('success-message'))
								<div class="alert alert-solid-success alert-bold kt-mt-30" role="alert">
									<div class="alert-text">{{ Session::get('success-message') }}</div>
										<div class="alert-close">
											<button type="button" class="close" data-dismiss="alert" aria-label="Close">
													<span aria-hidden="true"><i class="la la-close"></i></span>
											</button>
									</div>
								</div>
								@endif
							
							<div class="kt-login__form">
								<form class="kt-form" action="{{ ADMIN_URL }}login">

									<div class="alert alert-danger alert-dismissible kt-hide" role="alert">
										<div class="alert-text">Incorrect username or password. Please try again.</div>
										<div class="alert-close">
												<i class="flaticon2-cross kt-icon-sm" data-dismiss="alert"></i>
										</div>
									</div>

									<div class="form-group">
										<input class="form-control" type="text" placeholder="Email" name="v_email" autocomplete="off">
									</div>
									<div class="form-group">
										<input class="form-control form-control-last" type="password" placeholder="Password" name="password">
									</div>
									
									<div class="kt-login__actions">
										<button id="kt_login_signin_submit" class="btn btn-brand btn-pill btn-elevate">Sign In</button>
									</div>
								</form>
							</div>
						</div>
						<div class="kt-login__forgot">
							<div class="kt-login__head">
								<h3 class="kt-login__title">Forgotten Password ?</h3>
								<div class="kt-login__desc">Enter your email to reset your password.</div>
							</div>
							<div class="kt-login__form">
								<form class="kt-form" method="POST" action="{{ ADMIN_URL }}form-admin-forgot">
									<div class="form-group">
										<input class="form-control" type="text" placeholder="Email" name="email" id="kt_email" autocomplete="off">
									</div>
									<div class="kt-login__actions">
										<button id="kt_login_forgot_submit" class="btn btn-brand btn-pill btn-elevate">Request</button>
										<button id="kt_login_forgot_cancel" class="btn btn-outline-brand btn-pill">Cancel</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>


			</div>
		</div>
	<div class="kt-grid__item kt-grid__item--fluid kt-grid__item--center kt-grid kt-grid--ver kt-login__content" style="background-image: url({{ SITE_URL }}assets/media/bg/bg-4.jpg);">
			<div class="kt-login__section">
				<div class="kt-login__block">
					<h3 class="kt-login__title" style="text-align:center">Blog</h3>
					
				</div>
			</div>
		</div>
	</div>
</div>

	@section('custom_js')
	<script>
		$(document).ready(function() {
			KTLoginGeneral.init();
		});
	</script>
	@stop

@stop

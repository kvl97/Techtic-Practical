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
								<img src="{{ SITE_URL }}images/logo.png" style="width: 150px">
							</a>
						</div>
						<div class="kt-login__signin">

							<div class="kt-login__head">
								<h3 class="kt-login__title">Reset Your Password</h3>
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
								<form class="kt-form" action="{{ ADMIN_URL }}reset-password/{{ $record->remember_token }}">

									<div class="alert alert-danger alert-dismissible kt-hide" role="alert">
										<div class="alert-text">Incorrect username or password. Please try again.</div>
										<div class="alert-close">
												<i class="flaticon2-cross kt-icon-sm" data-dismiss="alert"></i>
										</div>
									</div>

									<div class="form-group">
										<input class="form-control" type="password" placeholder="New Password" name="password" id="password" autocomplete="off">
									</div>
									<div class="form-group">
										<input class="form-control" type="password" placeholder="Repeat New Password" id="confirm_password" name="confirm_password">
									</div>
									
									<div class="kt-login__actions">
										<button id="kt_reset_pass_submit" class="btn btn-brand btn-pill btn-elevate">Reset Password</button>
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
					<h3 class="kt-login__title">Rocket Transportation</h3>
					<div class="kt-login__desc">
						Rocket Transportation is the ONLY true DOOR to DOOR Sea-Tac Airport shuttle serving Washington’s Beautiful Olympic Peninsula.
					</div>
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

<div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed ">

  <!-- begin: Header Menu -->
  <button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
  <div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
   
  </div>

  <!-- end: Header Menu -->

  <!-- begin:: Header Topbar -->
  <div class="kt-header__topbar">

   
    <!--begin: User Bar -->
    <div class="kt-header__topbar-item kt-header__topbar-item--user">
      <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">
        <div class="kt-header__topbar-user">
          <span class="kt-header__topbar-welcome">Hi,</span>
          <span class="kt-header__topbar-username">{{ auth()->guard('admin')->user()->v_firstname }}</span>
          <img alt="Pic" class="kt-radius-100" src="<?php
                    if (File::exists(ADMIN_USER_PROFILE_THUMB_IMG_PATH . auth()->guard('admin')->user()->v_profile_image) && auth()->guard('admin')->user()->v_profile_image != '') {
                        echo SITE_URL . ADMIN_USER_PROFILE_THUMB_IMG_PATH . auth()->guard('admin')->user()->v_profile_image;
                    } else {
                        echo ASSET_URL.'images/default-image.png';
                    }
                    ?>" />

          <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->

          <!--<span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold">S</span>-->
        </div>
      </div>
      <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">

        <!--begin: Head -->
        <div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x" style="background-image: url({{ SITE_URL }}assets/media/misc/bg-1.jpg)">
          <div class="kt-user-card__avatar">
            <img class="kt-hidden" alt="Pic" src="<?php
                    if (File::exists(ADMIN_USER_PROFILE_THUMB_IMG_PATH . auth()->guard('admin')->user()->v_profile_image) && auth()->guard('admin')->user()->v_profile_image != '') {
                        echo SITE_URL . ADMIN_USER_PROFILE_THUMB_IMG_PATH . auth()->guard('admin')->user()->v_profile_image;
                    } else {
                        echo ASSET_URL.'images/default-image.png';
                    }
                    ?>" />

            <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
            <span class="kt-badge kt-badge--lg kt-badge--rounded kt-badge--bold kt-font-success">{{ substr(auth()->guard('admin')->user()->v_firstname, 0,1)  }}</span>
          </div>
          <div class="kt-user-card__name">
            {{ auth()->guard('admin')->user()->v_firstname . ' ' . auth()->guard('admin')->user()->v_lastname }}
          </div>         
        </div>

        <!--end: Head -->

        <!--begin: Navigation -->
        <div class="kt-notification">
          <a href="{{ ADMIN_URL.'my-profile' }}" class="kt-notification__item">
            <div class="kt-notification__item-icon">
              <i class="flaticon2-calendar-3 kt-font-success"></i>
            </div>
            <div class="kt-notification__item-details">
              <div class="kt-notification__item-title kt-font-bold">
                My Profile
              </div>
              <div class="kt-notification__item-time">
                Account settings and more
              </div>
            </div>
          </a>
          
          
          <div class="kt-notification__custom kt-space-between">
            <a href="{{ ADMIN_URL }}logout" class="btn btn-label btn-label-brand btn-sm btn-bold">Sign Out</a>            
          </div>
        </div>

        <!--end: Navigation -->
      </div>
    </div>

    <!--end: User Bar -->
  </div>

  <!-- end:: Header Topbar -->
</div>
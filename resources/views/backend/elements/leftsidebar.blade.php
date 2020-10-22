<button class="kt-aside-close " id="kt_aside_close_btn"><i class="la la-close"></i></button>
<div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop" id="kt_aside">

  <!-- begin:: Aside -->
  <div class="kt-aside__brand kt-grid__item " id="kt_aside_brand">
    <div class="kt-aside__brand-logo">
      <a href="{{ ADMIN_URL }}">
      <img alt="Logo" src="{{ SITE_URL }}images/logo-white.png" style="width: 140px;">
      </a>
    </div>
    <div class="kt-aside__brand-tools">
      <button class="kt-aside__brand-aside-toggler" id="kt_aside_toggler"><span></span></button>
    </div>
  </div>

  <!-- end:: Aside -->
  <?php
      $data = getCurrentControllerAction();
      $explode_data = explode("||", $data);
      $curr_controller = $explode_data[0];
      $curr_action = $explode_data[1];
    //$loginUserId = auth()->guard('admin')->user()->id;

  ?>

  <!-- begin:: Aside Menu -->
  <div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
    <div id="kt_aside_menu" class="kt-aside-menu " data-ktmenu-vertical="1" data-ktmenu-scroll="1" data-ktmenu-dropdown-timeout="500">
      <ul class="kt-menu__nav ">
       
         @if(Auth::guard('admin')->user()->i_role_id != 6)
            <li class="kt-menu__item <?= $curr_controller == "Authenticate" && $curr_action == "dashboard" ? 'kt-menu__item--active' : null ?>"  aria-haspopup="true"><a href="{{ ADMIN_URL }}dashboard" class="kt-menu__link "><i class="kt-menu__link-icon flaticon2-architecture-and-city"></i><span class="kt-menu__link-text">Dashboard</span></a></li>
        @endif

        <li class="kt-menu__item  kt-menu__item--submenu <?= $curr_controller == "Blog" ? ' kt-menu__item--open' : null ?>" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="{{ ADMIN_URL }}blog" class="kt-menu__link ">
            <i class="kt-menu__link-icon fas fa-book"></i><span class="kt-menu__link-text">Blogs</span></a>
        </li>

        


      </ul>
    </div>
  </div>

  <!-- end:: Aside Menu -->
</div>

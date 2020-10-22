<!-- device menu -->
<!-- device menu -->
<div class="mobilenav d-block d-lg-none">
    <div class="nav-backdrop"></div>
    <!-- hamburger -->
    <a href="javascript:;" class="hamburger">
        <span class="wrap">
            <span class="line"></span>
            <span class="line"></span>
            <span class="line"></span>
        </span>
    </a>
    <div class="menu-state" data-clickable="true">
        <?php
            $data = getCurrentControllerAction();
            $explode_data = explode("||", $data);
            $curr_controller = $explode_data[0];
            $curr_action = $explode_data[1];
           
        ?>
        @if(auth()->guard('admin')->check() && !auth()->guard('customers')->check())
                <!-- menu header and hamburger -->
                <div class="row no-gutters">
                <div class="col-8">
                    <div class="nav-logo">
                        <a href="{{ ADMIN_URL }}reservations" class="btn btn-sm btn-red">Back To Admin</a>
                    </div>
                </div>
                <div class="col-4 d-flex justify-content-end align-items-center">
                    <a href="javascript:;" class="hamburger close">
                        <span class="wrap">
                            <span class="line"></span>
                            <span class="line"></span>
                        </span>
                    </a>
                </div>
            </div>
            <!--  main responsive menu -->
            <div class="menu-outer">
                <ul>
                    <li class="<?= $curr_controller == "Homepage"  && $curr_action != "FindAshuttle" ? 'active' : null ?>"><a href="{{FRONTEND_URL}}">Home</a></li>
                    <li class="<?= $curr_controller == "CmsPages"  && $curr_action == "AboutUs" ? 'active' : null ?>"><a href="{{FRONTEND_URL.'about-us'}}">About Us</a></li>
                    <li class="<?= $curr_controller == "CustomerReservation" ? 'active' : null ?>"><a href="{{SITE_URL.'book-a-shuttle'}}">Book a Shuttle</a></li>
                    <li class="<?= $curr_controller == "ContactUs" ? 'active' : null ?>"><a href="#">Contact Us</a></li>
                </ul>
            </div>
        @else
            <!-- menu header and hamburger -->
            <div class="row no-gutters">
                <div class="col-8">
                    <div class="nav-logo">
                        <!-- <a href="javascript:;" class="btn btn-sm btn-red" data-fancybox
                            data-src="#quote-popup">Get
                            A Quote</a> -->
                    </div>
                </div>
                <div class="col-4 d-flex justify-content-end align-items-center">
                    <a href="javascript:;" class="hamburger close">
                        <span class="wrap">
                            <span class="line"></span>
                            <span class="line"></span>
                        </span>
                    </a>
                </div>
            </div>
            <!--  main responsive menu -->
            <div class="menu-outer">
                <ul>
                    <li class="<?= $curr_controller == "Homepage" && $curr_action != "FindAshuttle" ? 'active' : null ?>"><a href="{{FRONTEND_URL}}">Home</a></li>
                    <li class="<?= $curr_controller == "CmsPages"  && $curr_action == "AboutUs" ? 'active' : null ?>"><a href="#">About Us</a></li>
                    <li class="<?= $curr_controller == "CustomerReservation" ? 'active' : null ?>"><a href="#">Book a Shuttle</a></li>
                    <li class="<?= $curr_controller == "ContactUs" ? 'active' : null ?>"><a href="#">Contact Us</a></li>
                   
                </ul>
            </div>
        @endif
    </div>
</div>

<!-- header part -->
<header class="main-header header-inner py-2 py-md-3 py-lg-0 px-3 px-lg-0">
    <div class="container">
        <div class="row d-flex align-items-center justify-content-between">
            <div class="logo-wrapper d-flex align-items-center p-0 px-lg-3 col-7 col-md-3">
                <a class="logo" href="{{ SITE_URL }}">
                    <img src="{{ asset('frontend/assets/images/logo.png') }}" alt="logo">
                </a>
            </div>
            
            @if(auth()->guard('customers')->check())
                <div class="login-btn-group d-flex d-lg-none">
                    <div class="accout-login ml-2 mr-1 mr-lg-0">
                        <button type="button" class="btn btn-yellow btn-reverse dropdown-toggle"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                class="icon icon-user-half"></i></button>
                        <div class="dropdown-menu dropdown-menu-right p-0">
                            <div class="d-flex flex-column">
                                <a href="{{FRONTEND_URL.'my-profile'}}" class="btn btn-xs"><i class="icon icon-user"></i>
                                    My Profile</a>
                                <a href="{{FRONTEND_URL.'logout'}}" class="btn btn-xs"><i class="icon icon-logout"></i>
                                    Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="header-info d-none d-lg-flex flex-column align-items-end col-md-9">
                    <div class="header-info-top d-flex my-2">
                      
                        <div class="accout-login ml-2">
                            <button type="button"
                                class="btn btn-yellow btn-reverse dropdown-toggle d-lg-flex align-items-center"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="icon icon-user-half d-flex mr-2"></i>{{ Auth::guard('customers')->user()->v_firstname }}<i
                                    class="icon icon-down-arrow m-0 ml-2"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right p-0">
                                <div class="d-flex flex-column">
                                    <a href="{{FRONTEND_URL.'my-profile'}}" class="btn btn-xs"><i class="icon icon-user"></i>
                                        My Profile</a>
                                    <a href="{{FRONTEND_URL.'logout'}}" class="btn btn-xs"><i class="icon icon-logout"></i>
                                        Logout</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <nav>
                        <ul>
                            <li class="<?= $curr_controller == "Homepage" && $curr_action != "FindAshuttle" ? 'active' : null ?>"><a href="{{FRONTEND_URL}}">Home</a></li>
                            <li class="<?= $curr_controller == "CmsPages"  && $curr_action == "AboutUs" ? 'active' : null ?>"><a href="#">About Us</a></li>
                            <li class="<?= $curr_controller == "CustomerReservation" ? 'active' : null ?>"><a href="#">Book a Shuttle</a></li>
                            <li class="<?= $curr_controller == "ContactUs" ? 'active' : null ?>"><a href="#">Contact Us</a></li>
                        </ul>
                    </nav>
                </div>
            @elseif(auth()->guard('admin')->check() && !auth()->guard('customers')->check())
                <div class="login-btn-group d-flex d-lg-none">
                    <a href="{{ ADMIN_URL }}reservations" class="login-btn btn-red"><i class="icon icon-enter reverse"></i></a>
                </div>
                <div class="header-info d-none d-lg-flex flex-column align-items-end col-md-9">
                    
                    <div class="header-info-top my-2">
                        <span>Hi, {{ auth()->guard('admin')->user()->v_firstname }} </span>
                        <a href="{{ ADMIN_URL }}blog" class="btn btn-xs btn-red ml-2"><i class="icon icon-enter reverse"></i>Back To Admin</a>
                    </div>
                    <nav>
                        <ul>
                            <li class="<?= $curr_controller == "Homepage" && $curr_action != "FindAshuttle" ? 'active' : null ?>"><a href="{{FRONTEND_URL}}">Home</a></li>
                            <li class="<?= $curr_controller == "CmsPages"  && $curr_action == "AboutUs" ? 'active' : null ?>"><a href="#">About Us</a></li>
                            <li class="<?= $curr_controller == "CustomerReservation" ? 'active' : null ?>"><a href="#">Book a Shuttle</a></li>
                            <li class="<?= $curr_controller == "ContactUs" ? 'active' : null ?>"><a href="#">Contact Us</a></li>
                            
                        </ul>
                    </nav>
                </div>
                        
            @else
                <div class="login-btn-group d-flex d-lg-none">
                    <a href="{{FRONTEND_URL.'login'}}" class="login-btn"><i class="icon icon-enter"></i></a>
                  
                </div>
                <div class="header-info d-none d-lg-flex flex-column align-items-end col-md-9">
                    <div class="header-info-top d-flex my-2">
                        <!--  -->
                        
                            <a href="{{FRONTEND_URL.'login'}}" class="btn btn-xs btn-purple btn-reverse ml-2"><i
                                class="icon icon-enter"></i>
                            Login</a>
                            <!-- <a href="{{FRONTEND_URL.'register'}}" class="btn btn-xs btn-yellow btn-reverse ml-2"><i
                                class="icon icon-user-half"></i>
                            Register</a> -->
                        
                    </div>
                    <nav>
                        <ul>
                            <li class="<?= $curr_controller == "Homepage" && $curr_action != "FindAshuttle" ? 'active' : null ?>"><a href="{{FRONTEND_URL}}">Home</a></li>
                            <li class="<?= $curr_controller == "CmsPages"  && $curr_action == "AboutUs" ? 'active' : null ?>"><a href="#">About Us</a></li>
                            <li class="<?= $curr_controller == "CustomerReservation" ? 'active' : null ?>"><a href="#">Book a Shuttle</a></li>
                            <li class="<?= $curr_controller == "ContactUs" ? 'active' : null ?>"><a href="#">Contact Us</a></li>
                            
                        </ul>
                    </nav>
                </div>
            @endif
        </div>
    </div>
</header>
<div id="quote-popup" class="get-quote-popup mb-0" style="background: url({{ asset('assets/images/get-quote-bg.jpg')}}) no-repeat center / cover;">
    <form action="" name="getAQuotePopup" method="POST" id="getAQuotePopup">
        <div class="heading text-center pt-1 pt-md-3 pb-4">
            <h4>Rocket Fare Quote</h4>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
        </div>
        <div class="inquiry-form">
        
            <div class="row align-items-center">
                <input type="hidden" name="amount" id="amount">
                <div class="col-md-6 form-group">
                    <div class="select-field">
                        <select id="from_pickup_location" class="custom-select coll_exp_outgroup" name="home_pickup_location">
                        <option value="">Pickup Locations</option>
                            @foreach($arr_country as $k=>$v)
                            <optgroup label="{{$k}}">
                                @foreach($v as $key => $value)
                                    <option value="{{$value['id']}}" location_id="{{$value['id']}}" drop_off_city_ids="{{$value['v_drop_off_city_cant_be']}}" drop_off_city_must_be_ids="{{$value['v_drop_off_city_must_be']}}" service_area="{{$value['i_service_area_id']}}">{{$value['v_city']}}</option>
                                @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6 form-group">
                    <div class="select-field dropoff-location">
                        
                        <select id="from_dropoff_location" class="custom-select coll_exp_outgroup" name="home_dropoff_location">
                            <option value="">Drop Off Location</option>
                        </select>
                        
                    </div>
                </div>
                <div class="col-md-6 form-group d-none" id="pickup_location">
                    <div class="select-field pickup-location-rt">
                        <select id="to_pickup_location" class="custom-select coll_exp_outgroup" name="home_pickup_location_rt">
                        <option value="">Pickup Locations</option>
                           
                        </select>
                    </div>
                </div>
                <div class="col-md-6 form-group d-none" id="dropoff_location">
                    <div class="select-field dropoff-location-rt">
                        <select id="to_dropoff_location" class="custom-select coll_exp_outgroup" name="home_dropoff_location_rt">
                            <option value="">Drop Off Location</option>
                                
                        </select>
                    </div>
                </div>
                <div class="col-md-6 form-group ps-wrapper flex-center justify-content-start">
                    <p>How many people <small>(Including Children)?</small></p>
                    <div class="ps-info customNumber">
                        <input type="number" name="peoples" value="1" class="ps-digit" data-limit="13" readonly>
                        <em class="up" data-value="up"></em>
                        <em class="down" data-value="down"></em>
                    </div>
                </div>
                <div class="col-md-6 form-group custom-radio-wrapper">
                    <div class="custom-radio-block">
                        <input type="radio" id="radio3" name="e_class_type" class="trip_type" value="RT" groupid="trip_type">
                        <label for="radio3">Round Trip</label>
                    </div>
                    <div class="custom-radio-block">
                        <input type="radio" id="radio4" name="e_class_type" class="trip_type" value="OW" groupid="trip_type" checked>
                        <label for="radio4">One Way</label>
                    </div>
                </div>
                <div class="col-md-12 form-group submit-btn">
                    <button type="button" class="btn btn-red w-100 click_for_quote" style="padding:13px 40px">click for quote</button>
                </div>
                <div class="col-md-12 form-group shared-shuttle">
                    <div
                        class="top d-flex align-items-sm-center flex-column flex-sm-row justify-content-between">
                        <p>Shared shuttle quote for selected options assuming all travelers are full
                            fare adults</p>
                        <div class="amount_header d-none">
                            <button id="btnAddProfile" class="btn btn-md btn-yellow mt-3 mt-sm-0" type="button">$0.00</button>
                        </div>
                    </div>
                    <hr>
                    <div class="d-none" id="details_quote">
                        <div
                            class="bottom d-flex align-items-sm-center flex-column flex-sm-row justify-content-between">
                            <p>Check for discounted fares</p>
                            <button type="button" class="btn btn-md btn-purple mt-3 mt-sm-0 details_quote">Update Quote</button>
                        </div>
                    </div>
                </div>
            </div>
       
        </div>
    </form>
</div>
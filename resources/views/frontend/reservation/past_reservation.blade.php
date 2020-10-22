@extends('frontend.layouts.default')
@section('content')
<link href="{{ asset('frontend/assets/plugins/bootstrap/dataTables.bootstrap.css') }}{{CSS_VERSION}}" rel="stylesheet" />

    
    <div class="main-content">

        <!-- contact section -->
        <section class="contact-section mt-5 mb-5">
            <div class="container">
                <div class="profile-quick-links">
                     <ul>
                        <li><a href="{{FRONTEND_URL.'my-profile'}}">My Profile</a></li>
                        <!-- <li> <a href="{{FRONTEND_URL}}my-address" class="customnavbar">Addresses</a></li> -->
                        <li><a href="{{FRONTEND_URL.'upcoming-reservation'}}">Upcoming Reservation</a></li>
                        <li  class="active"><a href="{{FRONTEND_URL.'past-reservation'}}">Past Reservation</a></li>
                        @if($user['customer_stripe_id'] != '' )
                            <li><a href="{{FRONTEND_URL}}my-card-information">Card Info</a></li>
                        @endif
                        <li><a href="{{FRONTEND_URL.'payment-history'}}">Payment History</a></li> 
                    </ul>
                    @if(auth()->guard('customers')->user() && auth()->guard('customers')->user()->d_wallet_balance > 0.00)
                        <span class="sec-wallet-balance-lg"><strong>Wallet Balance:</strong> ${{ auth()->guard('customers')->user()->d_wallet_balance }}</span>
                    @endif
                </div>
                @if(auth()->guard('customers')->user() && auth()->guard('customers')->user()->d_wallet_balance > 0.00)
                    <div class="row sec-wallet-balance-sm">
                        <span><strong>Wallet Balance:</strong> ${{ auth()->guard('customers')->user()->d_wallet_balance }}</span>
                    </div>
                @endif
                <div class="contact-wrapper row">
                    <div class="contact-paginate col-md-12 col-xl-12 mt-4 past-reservations-table">
                        
                          
                            <div class="align-items-center d-flex flex-column flex-sm-row justify-content-between profile-address-bar pb-4">
                                <p class="m-0">Past Reservations</p> 
                                <div class="search-block d-flex align-items-center">
                                    <p class="m-0">Search:</p>
                                    <form  method="POST" onsubmit="return false;">
                                        <input type="text" class="quick_search" placeholder="Search" name="search">
                                        <div class="button-group">
                                            <button type="submit" id="btn_search"><i class="icon icon-search"></i></button>
                                            <button id="btn_clear"><i class="icon icon-delete"></i></button>
                                        </div>
                                    </form>
                                   
                                </div>
                              
                            </div>         
                            <table data-empmsg="No record found. Please <a href='{{ SITE_URL }}book-a-shuttle'>click here</a> to book a shuttle." class="table table-striped- table-bordered table-hover table-checkable dt-emp-hide-pg" id="datatable_ajax">

                                <thead class="thed-darkslateblue">
                                    <tr>
                                        <th class="no-sort" width="18%">Reservation No.</th>
                                        <th class="no-sort">Category</th>
                                        <th class="no-sort">Origin Point</th>
                                        <th class="no-sort">Destination</th>
                                        <th class="no-sort" style="width:140px;">Date</th>
                                    
                                        <th class="no-sort" style="width:80px;">Shuttle #</th>
                                        <th class="no-sort">Status</th>

                                    </tr>
                                    <tr role="row" class="filter d-none">
                                    
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                       
                                        <td>
                                            <input type="text" name="past_reserv_data" class="form-control form-filter  quick_search_value">
                                            <div class="margin-bottom-5">
                                                <button class="btn btn-sm filter-submit">Search</button>
                                                <button class="btn btn-sm filter-cancel">Reset</button>
                                            </div>
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                            
                                </tbody>
                            </table>
                        
                    </div>
                </div>
            </div>
        </section>
    </div>
    

@section('custom_js')
<script src="{{ asset('frontend/assets/js/login-frontend.js') }}"></script>
<script>
     $(document).ready(function() {
        

        var url = "{!! SITE_URL.'past-reservation/list-ajax'!!}";
        TableAjax.init('datatable_ajax', url);
        $(document).on("click", "#btn_search", function (event) {

            $('#datatable_ajax .quick_search_value').val($('.quick_search').val());
            $('table#datatable_ajax').find(".filter-submit").trigger('click');

        });
        $(document).on("click", "#btn_clear", function (event) {
            $('#datatable_ajax .quick_search_value').val($('.quick_search').val(''));
            $('table#datatable_ajax').find(".filter-cancel").trigger('click');

        });

        
});
</script>
@stop

@stop

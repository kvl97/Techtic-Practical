
@extends('frontend.layouts.default')
@section('content')

    <!-- content area part -->
    <div class="main-content">
        <!-- information block -->
        <section class="rocket-info">
            <div class="container">
                <div class="row no-gutters">
                    <div class="col-md-3">
                        <div class="rocket-info-left dropdown-rocket">
                            <div class="dropdown-pills">
                                <a href="javascript:void(0);" class="dropbtn-rocket btn-filter icon icon-down-arrow">Select</a>
                            </div>
                            <div class="nav flex-column nav-pills dropdown-content-rocket" id="tab" role="tablist" aria-orientation="vertical">
                                <a class="nav-link completed" id="v-location-tab" href="javascript:;" role="tab" aria-controls="location" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'book-a-shuttle/'.request()->route('id') : 'book-a-shuttle') }}">Start Reservation</a>
                                
                                <a class="nav-link completed" id="runs-tab" href="javascript:;" role="tab" aria-controls="runs" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'display-line-runs/'.request()->route('id') : 'display-line-runs') }}">Display Line Runs</a>
                                
                                <a class="nav-link completed" id="passenger-information-tab" href="javascript:;" role="tab" aria-controls="passenger-information" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'passenger-information/'.request()->route('id') : 'passenger-information') }}">Passenger Information</a>
                                
                                <a class="nav-link completed" id="luggage-animals-tab" href="javascript:;" role="tab" aria-controls="luggage-animals" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'luggage-animals/'.request()->route('id') : 'luggage-animals') }}">Luggage and Animals</a>

                                <a class="nav-link completed" id="travel-tab" href="javascript:;" role="tab" aria-controls="travel" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'travel-details/'.request()->route('id') : 'travel-details') }}">Travel Details</a>
                                
                                <a class="nav-link active" id="confirm-tab" href="javascript:;" role="tab" aria-controls="confirm" aria-selected="true" data-action="{{ ((request()->route('id') != '') ? 'currently-assigned-line-runs/'.request()->route('id') : 'currently-assigned-line-runs') }}">Currently Assigned Line Run</a>
                                @if((request()->route('id') != '' && !($paymentStatus)) || request()->route('id') == '')
                                    <a class="nav-link" id="reservation-summary-tab" href="javascript:;" role="tab" aria-controls="reservation-summary" aria-selected="false" data-action="{{ ((request()->route('id') != '') ? 'reservation-summary/'.request()->route('id') : 'reservation-summary') }}">Reservation Summary</a>

                                    <a class="nav-link disable" id="payment-tab" href="javascript:;" role="tab" aria-controls="payment" aria-selected="false"data-action="{{ ((request()->route('id') != '') ? 'reservation-payment/'.request()->route('id') : 'reservation-payment') }}">Payment</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="rocket-info-right">
                            <div class="tab-content" id="v-tabContent">
                                <!-- 6 -->
                                <div class="tab-pane fade show active" id="confirm" role="tabpanel" aria-labelledby="confirm-tab">
                                    <form action="{{ ((request()->route('id') != '') ? SITE_URL.'currently-assigned-line-runs/'.request()->route('id') : SITE_URL.'currently-assigned-line-runs') }}" class="rocket-info-details rocket-info-runs-tab" id="frontend_confrim_line_runs" method="POST">
                                        
                                        @if((request()->route('id') != '' && !($paymentStatus)) || request()->route('id') == '')
                                            <input type="hidden" name="redirect_url" value="{{ ((request()->route('id') != '') ? 'reservation-summary/'.request()->route('id') : 'reservation-summary') }}" id="redirect_url">
                                        @else
                                            <input type="hidden" name="redirect_url" value="{{ ((auth()->guard('admin')->check()) ? ADMIN_URL.'reservations/view/'.request()->route('id') : 'upcoming-reservation/'.request()->route('id')) }}" id="redirect_url">
                                        @endif
                                        <input type="hidden" name="redirect_type" value="submit" id="redirect_type">
                                        <h3 class="rocket-info__title">Currently Assigned Line Run</h3>
                                        <div class="result_data">
                                            
                                        </div>
                                        <div class="rocket-info__next mt-4 text-right">
                                            <button type="submit" class="btn btn-md btnNext btn-purple">{{ (request()->route('id') != '' && $paymentStatus) ? 'Submit' : 'Next' }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @section('custom_js')
<script>
   
    $(document).ready(function() {

        KTReservationFrontend.init();
        KTReservationFrontend.confirmLineRuns();
        get_line_runs_data();

    });
    var resv_id = "{{ ((request()->route('id') != '') ? request()->route('id') : '') }}";
    if(resv_id != '') {
        var url = SITE_URL + 'get_line_run_search/' + resv_id;
    } else {
        var url = SITE_URL + 'get_line_run_search';
    }
    function get_line_runs_data() {
        var first_leg_dir = localStorage.getItem("t_comfortable_time_travel_direction");
        var second_leg_dir = localStorage.getItem("rt_t_comfortable_time_travel_direction");
       
        $.ajax({
            url: url,
            method: 'POST',
            data: {first_leg_dir:first_leg_dir,second_leg_dir:second_leg_dir},
            success: function (data) {
                $(".result_data").html(data);
            }
        });
    }
    
      
    
</script>
@stop

@stop
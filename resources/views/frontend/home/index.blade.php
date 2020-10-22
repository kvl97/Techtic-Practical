@extends('frontend.layouts.default')
@section('content')
<!-- banner area part -->


<!-- content area part -->
<div class="main-content">

    <!-- Inquiry form -->
    
    <?php $svgArray = ['rocket', 'time', 'trophy', 'heart', 'professional', 'wifi', 'friendly', 'award', 'comfort','rocket', 'time', 'trophy', 'heart', 'professional', 'wifi', 'friendly', 'award', 'comfort','rocket', 'time', 'trophy', 'heart', 'professional', 'wifi', 'friendly', 'award', 'comfort'] ?>
    <section class="services-home space-lg pb-5 mb-0 mb-md-3">
        <div class="container">
            <div class="services-wrapper row">
                @foreach($homeFacility as $key => $val)
                <div class="services-block col-sm-6 col-lg-4 mb-3">
                    <div class="block-inner p-2 p-md-3">
                        <div class="services-icon flex-center">
                           <img src="{!! SITE_URL.'frontend/assets/images/'.$svgArray[$key].'.svg' !!}" alt="" class="purple-img">
                            <img src="{!! SITE_URL.'frontend/assets/images/'.$svgArray[$key].'-white.svg' !!}" alt="" class="white-img">
                        </div>
                        <a href="{{ SITE_URL }}blog/{{ $val->id }}"><div class="services-text pl-3">
                            <h4>@if(!empty($val['v_title'])){!! $val['v_title'] !!}@endif</h4>
                            <p>@if(!empty($val['t_content'])){!! substr($val['t_content'], 0, 50) !!}@endif</p>
                        </div></a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

</div>
@section('custom_js')

<script>
$(document).ready(function () { 
    
    var startDateofReturn = $('.date_picker_depart').val();
    var dateOfReturn = new Date(startDateofReturn);
    var date = new Date();
    $('.date_picker_depart_home').datepicker({
        format: 'mm/dd/yyyy',
        autoclose: true,
        /* orientation: "bottom auto", */
        startDate: date,
        todayHighlight: true,
    }).on('changeDate', function(selected) {
        var minDate = new Date(selected.date.valueOf());
        if(minDate > moment($('.date_picker_return_home').val())) {
            $('.date_picker_return_home').val('')
        }
        $('.date_picker_return_home').datepicker('setStartDate', minDate);
    })

    $('.date_picker_return_home').datepicker({
        format: 'mm/dd/yyyy',
        autoclose: true,
        startDate: dateOfReturn,
        /* orientation: "bottom auto", */
        todayHighlight: true,
    })
    
    $('#home_pickup_location').on('change', function () {
        var pickup_area_id = $('option:selected', this).attr('service_area');
        var value = $(this).val();
        if(value != '') {            
            $.post(SITE_URL+'get-home-dropoff-locations',{pickup_area_id: pickup_area_id,tab:'location'},function(data) {
                $('#home_dropoff_location').html(data).trigger("change");
            });
            $.post(SITE_URL+'get-home-dropoff-locations',{pickup_area_id:pickup_area_id,tab:'locationRt'},function(data) {
                $('#home_to_dropoff_location').html(data).val(value).trigger('change');
            });
        } else {
            $('#home_dropoff_location').html('<option value="">Drop Off Location</option>');
            $('#home_to_pickup_location').html('<option value="">Pick up Location</option>');
            $('#home_to_dropoff_location').html('<option value="">Drop Off Location</option>');
        }
    });

    $('body').on('change','#home_dropoff_location', function() {
        var pickup_area_id = $('option:selected', this).attr('service_area');
        var value = $(this).val();
        if(value != '') {
            $.post(SITE_URL+'get-home-dropoff-locations',{pickup_area_id:pickup_area_id,tab:'locationPickUpRt'},function(data) {
                $('#home_to_pickup_location').html(data).val(value).trigger('change');
            });
        } else {
            $('#home_to_pickup_location').html('<option value="">Pick up Location</option>');
        }
    });
    $('#find_a_shuttle').on('click', function () {
               
        var home_pickup_location= $('#home_pickup_location').val();
        var home_dropoff_location = $('#home_dropoff_location').val();
        if (home_pickup_location != '' && home_dropoff_location != '') {
            $.ajax({
                url: SITE_URL + "find-a-shuttle",
                method: 'POST',
                data: $('#findAShuttle').serialize(),
                success: function (resultData) {
                    if (resultData.status == 'TRUE') {
                        window.location.href = resultData.redirect_url;
                    } 
                }
            });
        }
      
    });
});
</script>
@stop
@stop
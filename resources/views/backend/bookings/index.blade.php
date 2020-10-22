@extends('backend.layouts.default')
@section('content')
<style>
    tbody > tr {
        cursor: pointer;
    }
    tbody > tr.ui-droppable-hover {
        border: 3px solid #000 !important;
        /* color: #FFF !important; */
    }
    #draggingContainer tr {
        background-color: #f2f2f2;
    }
</style>
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
   
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
    </div>
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="card card-custom card-stretch" style='margin-bottom: 10px;'>
            <div class="card-header border-0" style="background-color: #fff;">
                <div class="row">
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <h3 class="card-title d-flex"><span class="card-label font-weight-bolder text-dark" style="margin: 10px 0px 0px 0px;padding: 0;font-size: 1.2rem;font-weight: 500;color: #48465b;"><i class="kt-menu__link-icon fa fa-list" style="padding-right: .75rem;color: #5d59a6!important;"></i>{!! $title !!}</span></h3>
                    </div>
                    <div class="col-lg-7 col-md-6 col-sm-12 align-self-center">
                        <h4><span class="current_date"></span></h4>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <form class="justify-content-between d-flex kt-form kt-portlet__head-right" id="searchForm" action="" methode="POST">
                            <!-- <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <label class="col-form-label col-md-3 col-lg-3 col-sm-12">Date</label>
                                        <div class="d-flex"> -->
                                            <button class="btn btn-secondary prev_week mr-2" data-diff="-7"><i class="fa fa-angle-double-left" style="padding-right: 0;"></i></button>
                                            
                                            <button class="btn btn-secondary prev_date mr-2" data-diff="-1"><i class="fa fa-angle-left" style="padding-right: 0;"></i></button> 
                                            
                                            <input type="text" class="form-control run_date" name="d_run_date" placeholder="Run Date" readonly="readonly" value="{{date('m/d/Y',strtotime($today))}}"> 
                                            
                                            <button class="btn btn-secondary next_date ml-2" data-diff="1"><i class="fa fa-angle-right" style="padding-right: 0;"></i></button> 
                                            
                                            <button class="btn btn-secondary next_week ml-2" data-diff="7"><i class="fa fa-angle-double-right" style="padding-right: 0;"></i></button>

                                            <button type="button" class="btn btn-secondary btn-icon-sm d-none" id="search_button"><i class="la la-search"></i>Search</button>
                                        <!-- </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-5 d-none">
                                    <button type="button" class="btn btn-secondary btn-icon-sm" id="search_button"><i class="la la-search"></i>Search</button>
                                </div>
                            </div> -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-custom card-stretch mb-3 line_run_card" id="line_run_card">
            <div class="card-body pt-8">
                <div class="table-responsive">
                    <table class="table" id="line_run_datatable_ajax">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Origin</th>
                                <th>Destination</th>
                                <th>Target</th>
                                <th>Service Type</th>
                                <th>Total</th>
                                <th>Vehicle No.</th>
                                <th>Driver</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="line_runs_data">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card card-custom card-stretch mb-3 reservations_card" id="reservations_card">
            <div class="card-body pt-8">
                <div class="table-responsive">
                    <table class="table" id="reservations_datatable_ajax">
                        <thead>
                            <tr>
                                <th>Reservation Number</th>
                                <th>Customer Name</th>
                                <!-- <th>Category</th> -->
                                <th>Origin Point</th>
                                <th>Destination Point</th>
                                <th>Travel Window</th>
                                <!-- <th>Plane / Bus / Train / <br/> Appt start time</th> -->
                                <th>Total Passengers</th>
                                <th>Shuttle Type</th>
                                <th></th>
                            </tr>
                            
                        </thead>
                        <tbody id ="reservations_data">
                            <tr><td colspan="8">No reservations found.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="d-none" id="print_manifest">
</div> 
@stop

@section('custom_js')
<script>
    $(document).ready(function() {
        
        $('.run_date').datepicker({
            format: 'mm/dd/yyyy',
            autoclose: true,
            //orientation: "bottom auto",
            todayHighlight: true,
            showButtonPanel: true
        }).on('changeDate', function(selected) {
            if(selected.date !== undefined) {
                $('#search_button').trigger('click');
            }
        });

        $arrDrivers = "{{ json_encode($arrDrivers) }}";
        $arrDrivers = $arrDrivers.replace(/&quot;/g, '"');
        $arrDrivers = $.parseJSON($arrDrivers);

        $arrVehicles = "{{ json_encode($arrVehicles) }}";
        $arrVehicles = $arrVehicles.replace(/&quot;/g, '"');
        $arrVehicles = $.parseJSON($arrVehicles);
        
        setTimeout(() => {
            $('#search_button').trigger('click');
        }, 100);
        
        $('#search_button').on('click',function() {
            var data = $('#searchForm').serialize();
            KTApp.block('#line_run_card', {
                overlayColor: '#000000',
                state: 'warning', // a bootstrap color
                size: 'lg' //available custom sizes: sm|lg
            });
            $.post(ADMIN_URL + 'bookings', data, function(response) {
                $('#line_runs_data').html(response);
                /* if($('#line_runs_data .line-run:first').length == 1) {
                    $('#line_runs_data .line-run:first').trigger('click');
                } */
                $.post(ADMIN_URL + 'bookings/unassigned-reservations-data', {'date': $('.run_date').val()}, function(response) {
                    $('#reservations_data').html(response);
                    KTApp.unblock('#reservations_card');
                });
                KTApp.unblock('#line_run_card');               
            });
        });

        $('.prev_date, .next_date, .prev_week, .next_week').click(function(e) {
            e.preventDefault();
            var diff = $(this).data('diff');
            var date = $('.run_date').data('datepicker').getDate();
            newDate = moment(date).add(diff, 'days').format('MM/DD/YYYY');
            $('.run_date').data('datepicker').setDate(newDate);
        })
        
    });
    
    $(document).on('click', '.line-run', function(e) {
        var lineRunId = $(this).attr('rel');
        if(!($(e.target).hasClass('redirect_linerun')) && $(e.target).parents('.action-buttons').length == 0 && !($(e.target).hasClass('form-control'))) {
            if(!$(this).hasClass('active')) {
                $('.line-run').removeClass('active');
                $('.line-run').find('.icon-class').addClass('d-none');
                $('.line-run').droppable("enable");
                $(this).addClass('active');
                $(this).find('.icon-class').removeClass('d-none');
                $(this).droppable("disable");
                KTApp.block('#reservations_card', {
                    overlayColor: '#000000',
                    state: 'warning', // a bootstrap color
                    size: 'lg' //available custom sizes: sm|lg
                });
                $.post(ADMIN_URL + 'bookings/reservations-data', {'lineRunId': lineRunId}, function(response) {
                    $('#reservations_data').html(response);
                    KTApp.unblock('#reservations_card');
                });
            } else {
                $('.line-run').removeClass('active');
                $('.line-run').find('.icon-class').addClass('d-none');
                $('.line-run').droppable("enable");
                KTApp.block('#reservations_card', {
                    overlayColor: '#000000',
                    state: 'warning', // a bootstrap color
                    size: 'lg' //available custom sizes: sm|lg
                });
                $.post(ADMIN_URL + 'bookings/unassigned-reservations-data', {'date': $('.run_date').val()}, function(response) {
                    $('#reservations_data').html(response);
                    KTApp.unblock('#reservations_card');
                });
            }
        }
    });

    $(document).on('click', '.line_run_status_edit', function() {
        var tr = $(this).closest('tr');
        $('.line_run_status_edit').addClass('d-none');
        $('.line_run_manifest_print').addClass('d-none');
        tr.find('.line_run_status_save').removeClass('d-none');
        tr.find('.line_run_status_cancel').removeClass('d-none');
        var lineRunId = tr.attr('rel');
        var status = $(this).attr('data-status');
        var vehicle = $(this).attr('data-vehicle');
        var driver = $(this).attr('data-driver');
        var targetTime = $(this).attr('data-time');
        var statusAll = ['Open', 'Closed', 'Set', 'Dispatched', 'Modified', 'Departed', 'Delayed', 'Dead head', 'Completed', 'Private'];
        var statusCanBe = statusAll;
        if(status == 'Set') {
            statusCanBe = ['Set', 'Dispatched', 'Modified', 'Departed', 'Delayed', 'Dead head', 'Completed', 'Private']
        } else if(status == 'Dispatched' || status == 'Modified') {
            statusCanBe = ['Dispatched', 'Modified', 'Departed', 'Delayed', 'Dead head', 'Completed', 'Private']
        } else if(status == 'Departed') {
            statusCanBe = ['Departed', 'Delayed', 'Dead head', 'Completed', 'Private']
        }
        var statusSelectPicker = '<select name="e_run_status" class="form-control e_run_status"><option value="">Select</option>';
        $.each(statusCanBe, function(k, v) {
            if(v == status) {
                statusSelectPicker += '<option value="'+v+'" selected="selected">'+v+'</option>';
            } else {
                statusSelectPicker += '<option value="'+v+'">'+v+'</option>';
            }
            
        });
        statusSelectPicker += '</select>';
        tr.find('.status').html(statusSelectPicker);

        var driversCanBe = JSON.parse(JSON.stringify($arrDrivers));
        var vehiclesCanBe = JSON.parse(JSON.stringify($arrVehicles));
        
        var sameTimeRecords = [];
        $('.line-run').each(function(k, v) {
            if(targetTime == $(v).find('.line_run_status_edit').attr('data-time') && tr.attr('rel') != $(v).attr('rel')) {
                var vehicle = $(v).find('.line_run_status_edit').attr('data-vehicle');
                var driver = $(v).find('.line_run_status_edit').attr('data-driver');
                delete driversCanBe[driver];
                delete vehiclesCanBe[vehicle];
            }
        });
        
        var driverSelectPicker = '<select name="i_driver_id" class="form-control i_driver_id"><option value="">Select</option>';
        $.each(driversCanBe, function(k, v) {
            if(k == driver) {
                driverSelectPicker += '<option value="'+k+'" selected="selected">'+v+'</option>';
            } else {
                driverSelectPicker += '<option value="'+k+'">'+v+'</option>';
            }
            
        });
        driverSelectPicker += '</select>';
        tr.find('.driver').attr('data-val', tr.find('.driver').html());
        tr.find('.driver').html(driverSelectPicker);

        
        var vehicleSelectPicker = '<select name="i_vehicle_id" class="form-control i_vehicle_id"><option value="">Select</option>';
        $.each(vehiclesCanBe, function(k, v) {
            if(k == vehicle) {
                vehicleSelectPicker += '<option value="'+k+'" selected="selected">'+v+'</option>';
            } else {
                vehicleSelectPicker += '<option value="'+k+'">'+v+'</option>';
            }
            
        });
        vehicleSelectPicker += '</select>';
        tr.find('.vehicle').attr('data-val', tr.find('.vehicle').html());
        tr.find('.vehicle').html(vehicleSelectPicker);

        tr.find('.target').html('<input type="text" class="kt_timepicker2 form-control t_scheduled_arr_time" name="t_scheduled_arr_time" value="'+targetTime+'">');
        $(".kt_timepicker2").timepicker({
            minuteStep: 1,
            defaultTime: '',
        });
    });
    
    $(document).on('click', '.line_run_status_save', function() {
        var that = $(this);
        var tr = $(this).closest('tr');
        var lineRunId = tr.attr('rel');
        var editButton = tr.find('.line_run_status_edit');
        var oldStatus = editButton.attr('data-status');
        var oldVehicle = editButton.attr('data-vehicle');
        var oldDriver = editButton.attr('data-driver');
        var oldTime = editButton.attr('data-time');
        var status = tr.find('.e_run_status').val();
        var driver = tr.find('.i_driver_id').val();
        var vehicle = tr.find('.i_vehicle_id').val();
        var targetTime = tr.find('.t_scheduled_arr_time').val();
        KTApp.block('#line_run_card', {
            overlayColor: '#000000',
            state: 'warning', // a bootstrap color
            size: 'lg' //available custom sizes: sm|lg
        });
        $.post(ADMIN_URL + 'bookings/linerun-update', {'lineRunId': lineRunId, 'status': status, 'driver': driver, 'vehicle': vehicle, 'targetTime': targetTime}, function(data) {
            if(data.status == 'TRUE') {
                tr.find('.status').html(data.lineRunStatus);
                tr.find('.driver').html(data.lineRunDriver != '0' ? $arrDrivers[data.lineRunDriver] : '');
                tr.find('.vehicle').html(data.lineRunVehicle != '0' ? $arrVehicles[data.lineRunVehicle] : '');
                tr.find('.target').html(moment(new Date(moment($('.run_date').val()).format('YYYY/MM/DD')+" "+data.lineRunTime)).format('hh:mm A'));
                editButton.attr({'data-status': data.lineRunStatus, 'data-driver': data.lineRunDriver, 'data-vehicle': data.lineRunVehicle, 'data-time': data.lineRunTime});
            } else {
                tr.find('.status').html(oldStatus);
                tr.find('.vehicle').html($arrVehicles[oldVehicle] !== undefined ? $arrVehicles[oldVehicle] : tr.find('.vehicle').attr('data-val'));
                tr.find('.driver').html($arrDrivers[oldDriver] !== undefined ? $arrDrivers[oldDriver] : tr.find('.driver').attr('data-val'));
                oldTime = new Date(moment($('.run_date').val()).format('YYYY/MM/DD')+" "+oldTime);
                tr.find('.target').html(moment(oldTime).format('hh:mm A'));
            }
            that.addClass('d-none');
            tr.find('.line_run_status_cancel').addClass('d-none');
            $('.line_run_status_edit').removeClass('d-none');
            $('.line_run_manifest_print').removeClass('d-none');
            KTApp.unblock('#line_run_card');
        });
    });

    $(document).on('click', '.line_run_status_cancel', function() {
        var tr = $(this).closest('tr');
        var oldStatus = tr.find('.line_run_status_edit').attr('data-status');
        var oldVehicle = tr.find('.line_run_status_edit').attr('data-vehicle');
        var oldDriver = tr.find('.line_run_status_edit').attr('data-driver');
        var oldTime = tr.find('.line_run_status_edit').attr('data-time');
        oldTime = new Date(moment($('.run_date').val()).format('YYYY/MM/DD')+" "+oldTime);
        tr.find('.status').html(oldStatus);
        tr.find('.vehicle').html($arrVehicles[oldVehicle] !== undefined ? $arrVehicles[oldVehicle] : tr.find('.vehicle').attr('data-val'));
        tr.find('.driver').html($arrDrivers[oldDriver] !== undefined ? $arrDrivers[oldDriver] : tr.find('.driver').attr('data-val'));
        tr.find('.vehicle').html($arrVehicles[oldVehicle]);
        tr.find('.driver').html($arrDrivers[oldDriver]);
        tr.find('.target').html(moment(oldTime).format('hh:mm A'));
        $(this).addClass('d-none');
        tr.find('.line_run_status_save').addClass('d-none');
        $('.line_run_status_edit').removeClass('d-none');
        $('.line_run_manifest_print').removeClass('d-none');
    });

    $(document).on('click', '.target_time_edit', function() {
        var tr = $(this).closest('tr');
        $('.target_time_edit').addClass('d-none');
        tr.find('.target_time_save').removeClass('d-none');
        tr.find('.target_time_cancel').removeClass('d-none');
        var reservationId = tr.attr('rel');
        var targetTime = $(this).attr('data-target-time');
        var comfortTime = $(this).attr('data-comfort-time');
        var switchType = $(this).attr('data-switch-type');
        var tarvelDate = moment($('.run_date').val()).format('YYYY/MM/DD');
        var oldTravelWindow = tr.find('.travel-window').html();
        $(this).attr('data-travel', oldTravelWindow);
        tr.find('.travel-window').html('<input type="text" class="kt_timepicker1 form-control" name="t_travel_time" value="'+targetTime+'">');
        $(".kt_timepicker1").timepicker({
            minuteStep: 1,
            defaultTime: '',
        }).on('changeTime.timepicker', function(e) {
            var h = e.time.hours;
            var m = e.time.minutes;
            var mer = e.time.meridian; 
            var targetTime = h + ":" + m + " " + mer;

            var comfortdt = tarvelDate +" "+ comfortTime;   //"2013/05/29 12:30 PM";
            var targetdt = tarvelDate +" "+ targetTime;   //"2013/05/29 12:30 PM"; 
           
            var target_dt_time = new Date(Date.parse(targetdt));
            var confirm_dt_time = new Date(Date.parse(comfortdt));
            
            if(switchType == 'Pick') {                
                if(target_dt_time < confirm_dt_time) {
                    tr.find('.travel-window').find('.kt_timepicker1').timepicker('setTime',moment(confirm_dt_time).format('hh:mm A'));
                }
            } else {
                if(target_dt_time > confirm_dt_time) {
                    tr.find('.travel-window').find('.kt_timepicker1').timepicker('setTime',moment(confirm_dt_time).format('hh:mm A'));
                }
            }
        });        
    });

    $(document).on('click', '.target_time_save', function() {
        var that = $(this);
        var reservationId = that.closest('tr').attr('rel');
        var targetTime = that.closest('tr').find('.travel-window input').val();
        var switchType = that.closest('tr').find('.target_time_edit').attr('data-switch-type');
        var switchText = that.closest('tr').find('.target_time_edit').attr('data-switch-text');
        var oldTravelWindow = that.closest('tr').find('.target_time_edit').attr('data-travel'); 
        KTApp.block('#reservations_card', {
            overlayColor: '#000000',
            state: 'warning', // a bootstrap color
            size: 'lg' //available custom sizes: sm|lg
        });
        $.post(ADMIN_URL + 'bookings/target-pickup-time', {'reservationId': reservationId, 'targetTime': targetTime, 'switchType' : switchType, 'switchText' : switchText}, function(data) {
            if(data.status == 'TRUE') {
                that.closest('tr').find('.target_time_edit').attr('data-target-time', data.targetTime);
                that.closest('tr').find('.travel-window').html(data.travelWindowText);
            } else {
                that.closest('tr').find('.travel-window').html(oldTravelWindow);
            }
            that.addClass('d-none');
            that.closest('tr').find('.target_time_cancel').addClass('d-none');
            $('.target_time_edit').removeClass('d-none');
            KTApp.unblock('#reservations_card');
        });

    });

    $(document).on('click', '.target_time_cancel', function() {
        var oldTravelWindow = $(this).closest('tr').find('.target_time_edit').attr('data-travel');
        $(this).closest('tr').find('.travel-window').html(oldTravelWindow);
        $(this).addClass('d-none');
        $(this).closest('tr').find('.target_time_save').addClass('d-none');
        $('.target_time_edit').removeClass('d-none');
    });

    $(document).on('click', '.line_run_manifest_print', function() {
        var lineRunId = $(this).closest('tr').attr('rel');
        $.post(ADMIN_URL+'rocket-line-run/print/'+lineRunId, {}, function(response) {
            $('#print_manifest').html(response);
            var divToPrint = document.getElementById("print_manifest");
            $(divToPrint).find('#print_manifest').show();
            newWin = window.open("");
            newWin.document.write(divToPrint.outerHTML);
            newWin.print();
            newWin.close();
            $(divToPrint).find('#print_manifest').hide();
        
        });
    });
</script>
@stop
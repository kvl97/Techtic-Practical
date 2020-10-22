
@if(count($lineRuns) > 0)
    <?php $i = 0;?>
    @foreach($lineRuns as $key => $linerun)  
        <?php $style = "background-color:".$recordColor['colors'][$i].";color:".$strTextColor[$i].";"; ?>
        <tr class="line-run" rel="{!! $linerun['id'] !!}" data-available="{!! $linerun['i_num_available']!!}" data-booked="{!! $linerun['i_num_booked_seats'] !!}" data-origin="{!! $linerun['i_origin_service_area_id'] !!}" data-destination="{!! $linerun['i_dest_service_area_id'] !!}">
            <td><i class="fa fa-check-circle icon-class fa-lg d-none"></i></td>
            <td style="{!! $style !!}">
                <a class="redirect_linerun" href="{!! ADMIN_URL !!}linerun/view/{!! $linerun['id'] !!}" target="_blank" style="color:<?= $strTextColor[$i]?>"> {!! $linerun['geo_origin_service_area']['v_area_label'] !!} </a>
            </td>
            <td style="{!! $style !!}"> {!! $linerun['geo_dest_service_area']['v_area_label'] !!} </td>
            <td style="{!! $style !!}" class="target"> @if($linerun['t_scheduled_arr_time'] != ''){!! date('g:i A' , strtotime($linerun['t_scheduled_arr_time'])) !!}@endif</td>
            <td style="{!! $style !!}">{!! $linerun['e_service_type'] !!}</td>           
            <td style="{!! $style !!}">{!! $linerun['i_num_booked_seats'] !!} / {!! $linerun['i_num_available']!!}</td>
            <td style="{!! $style !!}" class="vehicle"> {!! $linerun['vehicle_fleet']['v_vehicle_code'] !!} </td>
            <td style="{!! $style !!}" class="driver">{!! $linerun['v_dispatch_name'] !!}</td>
            <td style="{!! $style !!}" class="status"> {!! $linerun['e_run_status'] !!}</td>
            <td style="{!! $style !!}" class="action-buttons">
                <button type="button" class="btn btn-sm btn-brand btn-icon btn-icon-lg line_run_status_edit mr-2" data-status="{!! $linerun['e_run_status'] !!}" data-vehicle="{!! $linerun['i_vehicle_id'] !!}" data-driver="{!! $linerun['i_driver_id'] !!}" data-time="{!! $linerun['t_scheduled_arr_time'] !!}"><i class="la la-edit"></i></button>
                <button type="button" class="btn btn-sm btn-brand btn-icon btn-icon-lg line_run_manifest_print <?= ($linerun['i_num_booked_seats'] > 0) ? '' : 'd-none'?>"><i class="la la-print"></i></button>
                <button type="button" class="btn btn-sm btn-brand btn-icon btn-icon-lg line_run_status_save mr-2 d-none"><i class="la la-save"></i></button>
                <button type="button" class="btn btn-sm btn-brand btn-icon btn-icon-lg line_run_status_cancel d-none"><i class="la la-close"></i></button>
            </td>
        </tr>
        <?php ($i == 5) ? $i = 0 : $i++; ?>
    @endforeach
@else
    <tr><td colspan="10">No line run found.</td></tr>
@endif 
<script>
    $unassignedCount = '{{ $unassignedCount }}';
$("#line_run_datatable_ajax tbody tr.line-run").droppable({
    drop: function( event, ui ) {
        var lineRunDiv = $(this);
        var reservationDiv = $(ui.draggable[0]);
        var lineRunId = lineRunDiv.attr('rel');
        var reservationId = reservationDiv.attr('rel');
        var available = lineRunDiv.attr('data-available');
        var booked = lineRunDiv.attr('data-booked');
        var passenger = reservationDiv.attr('data-passenger');
        var selectedLineRun = $('.line-run.active').attr('rel');
        var selectedDate = $('.run_date').val();
        if(parseInt(available) >= parseInt(passenger)) {
            KTApp.block('#line_run_card', {
                overlayColor: '#000000',
                state: 'warning', // a bootstrap color
                size: 'lg' //available custom sizes: sm|lg
            });
            reservationDiv.hide();
            //Call Ajax to assign line run and refresh page
            $.ajax({
                type: "POST",
                url: ADMIN_URL + 'bookings/assign-line-run',
                data: { 'line_run_id': lineRunId, 'reservation_id': reservationId, 'selected_line_run': selectedLineRun, 'selected_date': selectedDate},
                success: function (data) {
                    if(data.status == 'TRUE') {
                        KTApp.unblock('#line_run_card');
                        $('#line_runs_data').html(data.lineRunData);
                        $('.line-run[rel="'+selectedLineRun+'"]').trigger('click');
                    }
                },
                error: function() {
                    //bootbox.alert('We are not able to assign to this line run.');
                }
            });            
        } else {
            reservationDiv.addClass('drag-revert');
            //bootbox.alert('You can't assign to this line run.');
        }
    }
});
var date = $('.run_date').data('datepicker').getDate();
$('.current_date').html('There are '+$unassignedCount+' unassigned reservations for ' + moment(date).format('dddd, MM/DD/YYYY'));
</script>
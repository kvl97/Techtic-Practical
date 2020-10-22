@if(count($reservations) > 0)
    @foreach($reservations as $key => $resv)
        <?php $pointType = \App\Http\Controllers\Backend\BaseController::getTravelTypeText($resv['i_reservation_category_id'], $resv['i_dropoff_point_type_id']); ?>
        <tr class="reservation <?= ($resv['e_reservation_status'] == 'Hold' ? 'hold-bg' : ($resv['e_reservation_status'] == 'Pending Payment' ? 'pending-payment-bg' : ($resv['e_reservation_status'] == 'Callback' ? 'callrequest-bg' : 'booked-bg'))) ?>" rel="{!! $resv['id'] !!}" data-passenger="{!! $resv['i_total_num_passengers'] !!}" data-origin="{!! $resv['pickup_city']['i_service_area_id'] !!}" data-destination="{!! $resv['drop_off_city']['i_service_area_id'] !!}">
            <td><a href="{!! ADMIN_URL !!}reservations/view/{!! $resv['id'] !!}" target="_blank"> {!! $resv['v_reservation_number'] !!} </a></td>
            <td>{!! $resv['v_contact_name'] !!}</td>
            <td>{!! $resv['pickup_city']['v_city'].' ('.$resv['pickup_city']['v_county'].')' !!}</td>
            <td>{!! $resv['drop_off_city']['v_city'].' ('.$resv['drop_off_city']['v_county'].')' !!}</td>
            <td class="travel-window">
                @if($pointType['switch_type'] == 'Pick')    
                    {!! date('H:i' , strtotime($resv['t_comfortable_time'])) !!} - {!! date('H:i' , strtotime($resv['t_target_time'])) !!}
                @else
                    {!! date('H:i' , strtotime($resv['t_target_time'])) !!} - {!! date('H:i' , strtotime($resv['t_comfortable_time'])) !!} 
                @endif
                    
                <?= ($pointType['switch_type'] == 'Pick') ? 'ARR' : 'DEP'; ?>

                {!! date('H:i' , strtotime($resv['t_flight_time'])) !!}

                <?= (($pointType['switch_text'] == 'Amtrak') ? 'Train' : (($pointType['switch_text'] == 'Greyhound') ? 'Bus' : $pointType['switch_text']))  ?>
            </td>
            <td>{!! $resv['i_total_num_passengers'] !!} </td>
            <td>{!! $resv['e_shuttle_type'] !!} </td>
            <td><button type="button" class="btn btn-sm btn-brand btn-icon btn-icon-lg target_time_edit" data-switch-type="{{ $pointType['switch_type'] }}" data-switch-text="{{ $pointType['switch_text'] }}" data-target-time="{{ $resv['t_target_time'] }}" data-comfort-time="{{ $resv['t_comfortable_time'] }}"><i class="la la-edit"></i></button><button type="button" class="btn btn-sm btn-brand btn-icon btn-icon-lg target_time_save mt-md-0 mt-2 mr-2 d-none"><i class="la la-save"></i></button><button type="button" class="btn btn-sm btn-brand btn-icon btn-icon-lg target_time_cancel mt-md-0 mt-2 d-none"><i class="la la-close"></i></button></td>
        </tr>
    @endforeach
@else
    <tr><td colspan="8">No reservations found.</td></tr>
@endif
<script>
    $('#reservations_datatable_ajax tbody tr.reservation').draggable({
        connectWith: ".connectedSortable",
        itemSelector : "tr.reservation",
        connectToSortable: ".line-run",
        cursor: "crosshair",
        revert:  function(dropped) {
            var dropped = dropped && dropped[0].id == "droppable";
            if ($(this).hasClass('drag-revert')) {
                $(this).removeClass('drag-revert').removeClass('selected-row');
                return true;
            } else if(!dropped) {
                $(this).removeClass('selected-row');
                return true;
            }
        },
        helper: function(){
            var selected = $('tr.selected-row');
            if (selected.length === 0) {
                selected = $(this).closest('tr').addClass('selected-row');
            }
            var container = $('<div/>').attr('id', 'draggingContainer');
            container.append(selected.clone().removeClass("selected-row"));
            return container;
        },
        start: function( event, ui ) {
            var element = $(this);
            var origin = $(this).attr('data-origin');
            var destination = $(this).attr('data-destination');
            $('#line_run_datatable_ajax tbody tr.line-run').droppable("disable");
            $('#line_run_datatable_ajax tbody tr.line-run[data-origin="'+origin+'"][data-destination="'+destination+'"]').droppable("enable");    
            $('#line_run_datatable_ajax tbody tr.line-run.active').droppable("disable");        
        }
    });
    
</script>
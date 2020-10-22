@extends('frontend.layouts.default')
@section('content')
<div class="main-content">
    <!-- Thank you -->
    <section class="thank-you-block" style="background-image: url({{ asset('frontend/assets/images/rocket-bg.png') }});">
        <div class="container d-flex align-items-start align-items-md-center justify-content-center">
            <div class="thank-you-block-inner text-center pt-5 pb-5">
                @if($transaction && $transaction->e_status=="Success" || $full_discounted)
                    <img src="{{ asset('frontend/assets/images/thank-you.png') }}" alt="" title="" />
                    <h3 class="h3">Success!</h3>
                    <p>Thank you! We received the payment! <br> Your reservation has been done successfully.<br> Please note down ticket number #{{ $reservation_record['v_reservation_number'] }} for future reference.</p>
                    <a href="{{SITE_URL.'reservation-summary-download/'.$reservation_record['id']}}"><button type="button" class="btn btn-md btn-yellow mx-2 mb-2 mt-3">Download Ticket</button></a>
                    <button  type="button" id="printPage" class="btn btn-md btn-purple mx-2 mb-2 mt-3" print_id="{{$reservation_record['id']}}">PRINT Ticket</button></a>
                @else
                    <img src="{{ asset('frontend/assets/images/pay-failed.png') }}" alt="" title="" />
                    <h3 class="h3">Transaction Failed!</h3>
                    <p>Error encountered during processing the payment.<br>{{ ($transaction && $transaction->e_status=="Failed" ? "Error: ".$transaction->v_error_log : '') }}</p>
                    <a href="{{ (($edit_id != '') ? SITE_URL.'reservation-payment/'.$edit_id : SITE_URL.'reservation-payment') }}"><button type="button" class="btn btn-md btn-yellow mx-2 mb-2 mt-3">Retry</button></a>
                @endif
            </div>
            <div class="d-none" id="ps_desc">
            </div>
        </div>
    </section>
</div>
@section('custom_js')
<script>
    $(document).ready(function() {
        KTReservationFrontend.init();
        $('#printPage').on('click',function(){
            var id = $(this).attr('print_id');
            var url = (SITE_URL + 'get-reservation-print-data/'+id);
            
            $.post(url,function(response) {
                $('#ps_desc').html(response);
                var divToPrint=document.getElementById("ps_desc");
                $(divToPrint).find('#ps_desc').show();
                newWin= window.open("");
                newWin.document.write(divToPrint.outerHTML);
                newWin.print();
                newWin.close();
                $(divToPrint).find('#ps_desc').hide();
            });
         
        });
       
    });
</script>
@stop
@stop
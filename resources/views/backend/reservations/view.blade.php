

<style>
    table {
        font-family: arial, sans-serif;
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        border-spacing: 0;
        
    }
    td {
        line-height: 1.5;
        font-size: 13px;
        color: #000;
    }
    strong {
        font-size: 13px;
        font-weight: bold;
        color: #000;
    }
    span {
        color: #565656;
    }
    th{
        color: #000000;
    }
    .traveller_details th {
        font-weight: normal;
    }
    .price_information {
        border: 1px solid #ccc; background-color:#fff; padding:10px 0 0 0;margin-bottom:20px;
    }
    .price_information thead td {
        background-color:#f0f0f9;
        text-transform: uppercase;
        color: #000000;
        font-weight: bold;
    }
    .price_information td {
        padding: 10px;
        border-right:1px solid #ccc;
    }
    .price_information th {
        background-color: #f0f0f9;border: 1px solid #ccc;text-transform: uppercase;padding: 10px;
    }
    .price_information tr {
        border-bottom: 1px solid #ccc;
    }
    .travel_details, .travel_charges_details {
        border: 1px solid #ccc; background-color:#fff; margin:0 0 20px 0;
    }
    .travel_details thead td, .travel_charges_details thead td {
        padding: 1rem 20px; text-transform: uppercase;
    }
    .travel_details thead tr, .travel_charges_details thead tr {
        background-color:#f0f0f9;
    }
    .travel_details > tbody > tr:first-child > td {
        padding:1rem 1rem 20px 20px;
    }
    .travel_details > tbody > tr:nth-child(2) > td {
        padding:0px 20px 20px 20px;
    }
    .travel_details_child {
        background-color:#f0f0f9;
    }
    .travel_details_sub_child tr:not(:first-child) td:first-child, .traveller_details tr th:first-child {
        width: 10%;
    }
    .form_details {
        background-color:#f0f0f9;margin: 0px 0px 20px 0px; border: 1px solid #ccc;
    }
    @media only screen and (max-width: 40em) {
        th {
            display: none;
        }
        td {
            display: block;
            text-align: left;
        }

        td[data-th]:before {
            content: attr(data-th);
            text-transform: uppercase;
            color: #000000;
            font-weight: bold;                
        }
        .price_information td[data-th]:before, .traveller_details td[data-th]:before {
            content: attr(data-th)' :  ';
        }
        .travel_details tbody td, .travel_charges_details tbody td, .travel_charges_details thead td, .travel_details thead td, .travel_details_sub_child td, .travel_details_child > tbody > tr > td:nth-child(2), .traveller_details > tbody > tr > td > table td, .traveller_details > tbody > tr > td:nth-child(2) {
            padding: 10px !important;
        }
        .travel_details_child > tbody > tr > td {
            width: 100% !important;
            padding: 0 !important;
        }
        .travel_details_sub_child td {
            display: table-cell;
        }
        .traveller_details > tbody > tr > td, .form_details td {
            width: 100% !important;
        }
        .form_details td {
            padding: 10px 10px 0 10px !important;
        }
        .form_details td:last-child {
            padding: 10px !important;
        }
    }
        
</style>
@extends('backend.layouts.default')
@section('content')

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Subheader -->
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
    </div>
    <!-- end:: Subheader -->
    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <!--Begin::Dashboard 1-->
        <!--Begin::Row-->
        
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#kt_tabs_traveller_info" data-target="#kt_tabs_traveller_info">Reservation Information</a>
            </li>
            @if(isset($reservation_record['ReservationLogs']) && count($reservation_record['ReservationLogs']) > 0) 
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#kt_tabs_activity_logs"> Activity Logs</a>
                </li>   
            @endif
        </ul>
        <?php $res_status = $reservation_record->e_reservation_status; ?>
        <div class="tab-content">
        
            <div class="tab-pane active" id="kt_tabs_traveller_info" role="tabpanel">
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                @if(request()->route('id') == $reservation_record['id'] && $reservation_record['e_class_type'] == 'RT' && $reservation_record['i_parent_id'] == NULL && !empty($reservation_record_rt)) 
                                    
                                    Reservation Information: #{{$reservation_record['v_reservation_number']}}
                                @elseif($reservation_record['e_class_type'] == 'RT' && request()->route('id') == $reservation_record_rt['id'] && $reservation_record['i_parent_id'] == NULL && !empty($reservation_record_rt))
                                    <?php $res_status = $reservation_record_rt->e_reservation_status; ?>
                                  Reservation Information: #{{$reservation_record_rt['v_reservation_number']}} 
                                @endif

                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-wrapper">
                                <div class="kt-portlet__head-actions">
                                    <a href="{{ ADMIN_URL }}<?php if(!isset($cust_reservation)) { ?>reservations <?php } else { ?>customers/edit/{{ $id }}#kt_tabs_reservation <?php } ?>" class="btn  btn-secondary btn-icon-sm mt-1 mb-1" id="back-to-list">
                                        Back To Listing
                                    </a>
                                    <a href="{{ ADMIN_URL }}reservations/download/{{ $id }}" class="btn  btn-secondary btn-icon-sm mt-1 mb-1" id="back-to-list">
                                    <i class="la la-download"></i>Download
                                    </a>
                                    <button class="btn btn-secondary btn-icon-sm mt-1 mb-1" id="printPage" printId="{{$id}}"><i class="la la-print"></i>Print</button>
                                    <?php  $arrDates = date('Y-m-d');?>
                                    @if($reservation_record['e_class_type'] == 'RT')
                                        @if(request()->route('id') == $reservation_record_rt['id'] && $arrDates > $reservation_record['d_travel_date'] && count($payment_info) > 0 && $payment_info[0]['e_status'] == "Success") 
                                            <a class="btn btn-secondary btn-icon-sm mt-1 mb-1" rel="{{$reservation_record_rt['id']}}" href="{{SITE_URL}}book-a-shuttle/{{ $reservation_record_rt['id'] }}" title="edit"><i class="la la-edit"></i>Edit </a>
                                        @else
                                            <a class="btn btn-secondary btn-icon-sm mt-1 mb-1" rel="{{$reservation_record['id']}}" href="{{SITE_URL}}book-a-shuttle/{{ $reservation_record['id'] }}" title="edit"><i class="la la-edit"></i>Edit </a>
                                        @endif
                                    @else
                                        <a class="btn btn-secondary btn-icon-sm mt-1 mb-1" rel="{{$id}}" href="{{SITE_URL}}book-a-shuttle/{{ $id }}" title="edit"><i class="la la-edit"></i>Edit </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-lg-12 table-responsive">
                        <table style="background-color: #fff; max-width: 90%; margin: 30px auto;">
                            @if(!in_array($reservation_record['e_reservation_status'],['Requested','Request Confirmed']))
                            <tr>
                                <td>@if(request()->route('id') == $reservation_record['id'] && $reservation_record['e_class_type'] == 'RT' && $reservation_record['i_parent_id'] == NULL && !empty($reservation_record_rt)) 
                                    <div class="ticket_info" role="alert">
                                        This is round trip reservation. Please check 2nd leg information on this ticket: &nbsp;<a href="{{ADMIN_URL.'reservations/view/'.$reservation_record_rt['id']}}">{{$reservation_record_rt['v_reservation_number']}}</a>.
                                    </div>
                                    @elseif($reservation_record['e_class_type'] == 'RT' && request()->route('id') == $reservation_record_rt['id'] && $reservation_record['i_parent_id'] == NULL && !empty($reservation_record_rt)) 
                                        <div class="ticket_info" role="alert">
                                                This is round trip reservation. Please check 1st leg information on this ticket: &nbsp;<a href="{{ADMIN_URL.'reservations/view/'.$reservation_record['id']}}" style="">{{$reservation_record['v_reservation_number']}}</a>.
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td style="padding:0;">

                                    <form class="kt-form kt-form--label-right add-reservation-info" action="{{ ADMIN_URL }}reservations/add_special_instruction_status/{{ $id }}" id="frmEdit">    
                                        <input type="hidden" name="view_reservation_record_update_id" id="view_reservation_record_update_id" value="{{$id}}"/>
                                        <table class="form_details">
                                            @if($reservation_record['e_reservation_status']=="Refund Requested")
                                                <tr>
                                                    <td align="left" style="padding:20px 20px 0px 20px;width:100%;color: red;" colspan="5">*Customer has requested for refund amount. Once you will change status to "Refunded", the system will automatically trigger refund request via stripe.</td>   
                                                </tr>
                                            @endif
                                            @if($reservation_record['e_shuttle_type']=='Private' && $reservation_record['e_reservation_status']=="Requested")
                                                <tr>
                                                    <td align="left" style="padding:20px 20px 0px 20px;width:100%;color: red;" colspan="4">*This is private shuttle booking request. Please confirm the request by changing status to "Request Confirmed" or you can reject it by changing status to "Rejected". Once status will change to "Request Confirmed" system will generate an email with payment link to customer.</td>   
                                                </tr>
                                            @endif
                                            <tr>
                                                <td align="left" style="padding:20px;width:18%">
                                                    <strong>Special Instructions</strong>
                                                </td>
                                                <td align="left" style="padding:20px;">
                                                    <textarea class="form-control t_special_instruction txtarea" name="t_special_instruction" placeholder="Special Instruction">{!! $reservation_record['t_special_instruction'] !!}</textarea>
                                                </td>
                                                <td align="left" style="padding:20px;width:18%">
                                                    <strong>Change Status</strong>
                                                </td>
                                                <td align="left" style="padding:20px;">
                                                    <select class="form-control required e_reservation_status" name="e_reservation_status" placeholder="Status">
                                                        <option value="">Select</option>
                                                        <option value="Quote" {{ $res_status == 'Quote' ? 'selected=""' : '' }}>Quote</option>
                                                        <option value="Pending Payment" {{ $res_status == 'Pending Payment' ? 'selected=""' : '' }}>Pending Payment</option>
                                                        @if($reservation_record->e_shuttle_type=="Private")
                                                            <option value="Requested" {{ $res_status == 'Requested' ? 'selected=""' : '' }}>Requested</option>
                                                            <option value="Request Confirmed" {{ $res_status == 'Request Confirmed' ? 'selected=""' : '' }}>Request Confirmed</option>
                                                        @endif
                                                        <option value="Rejected" {{ $res_status == 'Rejected' ? 'selected=""' : '' }}>Rejected</option>
                                                        <option value="Booked" {{ $res_status == 'Booked' ? 'selected=""' : '' }}>Booked</option>
                                                        <option value="Cancelled" {{ $res_status == 'Cancelled' ? 'selected=""' : '' }}>Cancelled</option>
                                                        <option value="Refund Requested" {{ $res_status == 'Refund Requested' ? 'selected=""' : '' }}>Refund Requested</option>
                                                        <option value="Refunded" {{ $res_status == 'Refunded' ? 'selected=""' : '' }}>Refunded</option>
                                                    </select>
                                                </td>
                                                <td class="withoutRequestedStatus">    
                                                    <button type="submit" class="btn btn-brand">Save</button>
                                                </td>
                                            </tr>

                                            @if($reservation_record['e_reservation_status']=='Refund Requested')
                                            <tr>
                                                <td align="left" style="padding:0px 20px 20px 20px;width:18%">
                                                    <strong>Refund Amount ($)</strong>
                                                </td>
                                                <td align="left" style="padding:0px 20px 20px 20px;">
                                                    <input class="form-control validate_zero" name="refund_amount" placeholder="Refund Amount" value="{{ $reservation_record['d_total_fare'] }}">
                                                </td>
                                                <td align="left" style="padding:0px 20px 20px 20px;width:18%">
                                                    <strong>Refund Option</strong>
                                                </td>
                                                <td align="left" style="padding:0px 20px 20px 20px;">
                                                    <select class="form-control required refund_option" name="refund_option" placeholder="Refund Option">
                                                        <option value="">Select</option>
                                                        <option value="wallet">Add to wallet</option>
                                                        <option value="bank_transfer">Refund to bank account</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            @endif
                                          
                                            <tr class="amount d-none">
                                                <td align="left" style="padding:0px 20px 20px 20px;width:18%">
                                                    <strong>Amount ($)</strong>
                                                </td>
                                                <td align="left" style="padding:0px 20px 20px 20px;">
                                                    @if(isset($reservation_record['e_class_type']) && $reservation_record['e_class_type'] =="RT")     
                                                        <?php $private_shuttle_amount =(isset($reservation_record['d_total_fare']) && (isset($reservation_record_rt['d_total_fare']))) ? (number_format((float)$reservation_record['d_total_fare'] + $reservation_record_rt['d_total_fare'], 2, '.', '')) : (isset($reservation_record['d_total_fare']) ? number_format((float)$reservation_record['d_total_fare'], 2, '.', '') : (isset($reservation_record_rt['d_total_fare']) ? number_format((float)$reservation_record_rt['d_total_fare'], 2, '.', '') : '0.00')); ?>
                                                    @else    
                                                        <?php  $private_shuttle_amount =(isset($reservation_record['d_total_fare'])) ? number_format((float)$reservation_record['d_total_fare'], 2, '.', '') : '0.00'; ?>
                                                    @endif 
                                                    <input class="form-control validate_zero" name="d_total_fare" placeholder="Amount" value="<?= number_format((float)$private_shuttle_amount, 2, '.', '');?>">
                                                </td>
                                                <td class="withRequestedStatus" align="right" colspan="2" style="padding:0px 20px 20px 20px;">    
                                                    <button type="submit" class="btn btn-brand">Save</button>
                                                </td>
                                            </tr>
                                           
                                        </table>
                                    </form>
                                
                                    <table style="background-color: #dcdcf3; border-radius: 10px 10px 0 0;">
                                        <tr>
                                            <td style="padding:25px 20px;">
                                                @if(request()->route('id') == $reservation_record['id'] && $reservation_record['e_class_type'] == 'RT' && $reservation_record['i_parent_id'] == NULL && !empty($reservation_record_rt))
                                                    <span style="background-color: #fff;border-radius: 50px;padding: .7em 1.5em;"><strong>Reservation No. : </strong><span>{{ $reservation_record['v_reservation_number']}}</span></span>
                                                @elseif($reservation_record['e_class_type'] == 'RT' && request()->route('id') == $reservation_record_rt['id'] && $reservation_record['i_parent_id'] == NULL && !empty($reservation_record_rt))
                                                    <span style="background-color: #fff;border-radius: 50px;padding: .7em 1.5em;"><strong>Reservation No. : </strong><span>{{ $reservation_record_rt['v_reservation_number']}}</span></span>
                                                @else
                                                    <span style="background-color: #fff;border-radius: 50px;padding: .7em 1.5em;"><strong>Reservation No. : </strong><span>{{ $reservation_record['v_reservation_number']}}</span></span>
                                                @endif
                                            </td>
                                            <td align="right" style="padding:25px 20px;">
                                                <span style="background-color: #fff;border-radius: 50px;padding: .7em 1.5em;"><span>{{ ($reservation_record['e_shuttle_type']) }} Shuttle</span></span>
                                            </td>
                                        </tr>
                                    </table>
                                    <table style="background-color:#f2f2f2;">
                                        <tr>
                                            <td style="padding: 20px 20px 0px 20px;">
                                                <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" class="traveller_details">
                                                    <tr>
                                                        <td align="left" style="background-color: #FFF; border: 1px solid #ccc;width: 48%;vertical-align: top;">
                                                            <table width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <td colspan="3" style="background-color: #f0f0f9;font-weight: 700;text-transform: uppercase;padding:1rem 20px;">
                                                                            Main Traveler
                                                                        </td>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <th style="padding:20px 0px 12px 20px;">
                                                                            Name
                                                                        </th>
                                                                        <th style="padding-top:20px;vertical-align:top;width: 20px;text-align: center;">:</th>
                                                                        <td style="padding:20px 20px 12px 0px;" data-th="Name">
                                                                            <span> @if(isset($reservation_record['v_contact_name'])) {!! $reservation_record['v_contact_name']!!}@endif</span>
                                                                        </td>
                                                                        
                                                                    </tr>
                                                                    <tr>
                                                                        <th style="padding:12px 0px 12px 20px;">
                                                                            Email
                                                                        </th>
                                                                        <th style="padding-top:12px;vertical-align:top;width: 20px;text-align: center;">:</th>
                                                                        <td style="padding:12px 20px 12px 0px;" data-th="Email">
                                                                            <span> @if(isset($reservation_record['v_contact_email'])) {!! $reservation_record['v_contact_email'] !!}@endif</span>
                                                                        </td>
                                                                    </tr>                                                        
                                                                    <tr>
                                                                        <th style="padding:12px 0px 20px 20px;">
                                                                            Phone
                                                                        </th>
                                                                        <th style="padding-top:12px;vertical-align:top;width: 20px;text-align: center;">:</th>
                                                                        <td style="padding:12px 20px 20px 0px;" data-th="phone">
                                                                            <span> @if(isset($reservation_record['v_contact_phone_number'])) {!! $reservation_record['v_contact_phone_number'] !!}@endif</span>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>

                                                            </table>
                                                        </td>
                                                        <td style="width:2%" width="2%"></td>
                                                        <td align="right" style="background-color: #fff; width: 48%">
                                                            <table>
                                                                <tr style="border: 1px solid #ccc;border-radius: 10px;">
                                                                    <td style="padding:20px;background-color:#f0f0f9;font-weight:700;text-transform:uppercase;">         
                                                                        <strong> Total Traveler </strong> 
                                                                    </td>
                                                                    <td style="padding:20px;width:50%"> {{ $reservation_record['i_total_num_passengers'] }} </td>
                                                                </tr>
                                                                <tr style="height:10px;" height="10px"><td colspan="2" style="background-color:#f2f2f2;"></td></tr>
                                                                <tr style="border: 1px solid #ccc;border-radius: 10px;">
                                                                    <td style="padding:20px;background-color:#f0f0f9;font-weight:700;text-transform:uppercase;" > <strong> Booked trip </strong></td>
                                                                    <td style="padding:20px;"> <?php if($reservation_record['e_class_type'] == 'RT') { echo "Round Trip"; } else { echo "One Way"; } ?> </td>
                                                                </tr>
                                                                <tr style="height:10px;" height="10px"><td colspan="2" style="background-color:#f2f2f2;"></td></tr>
                                                                <tr style="border: 1px solid #ccc;border-radius: 10px;">
                                                                    <td style="padding:20px;background-color:#f0f0f9;font-weight:700;text-transform:uppercase;" > <strong> TOTAL FARE </strong></td>
                                                                    <td style="padding:20px;"> 
                                                                        
                                                                        @if(isset($reservation_record['e_class_type']) && $reservation_record['e_class_type'] =="RT")     
                                                                            $<?= (isset($reservation_record['d_total_fare']) && (isset($reservation_record_rt['d_total_fare']))) ? (number_format((float)$reservation_record['d_total_fare'] + $reservation_record_rt['d_total_fare'], 2, '.', '')) : (isset($reservation_record['d_total_fare']) ? number_format((float)$reservation_record['d_total_fare'], 2, '.', '') : (isset($reservation_record_rt['d_total_fare']) ? number_format((float)$reservation_record_rt['d_total_fare'], 2, '.', '') : '0.00')); ?>
                                                                        @else    
                                                                            <?= (isset($reservation_record['d_total_fare'])) ? '$'.number_format((float)$reservation_record['d_total_fare'], 2, '.', '') : '$0.00'; ?>
                                                                        @endif 
                                                                       
                                                                        
                                                                    </td>
                                                                </tr>

                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    <table style="background-color:#f2f2f2;">
                                        <tr>
                                            <td style="padding: 20px 20px 0px 20px;">
                                                <table class="travel_details">
                                                    <thead>
                                                        <tr>
                                                            <td>@if($reservation_record['e_class_type']=='OW')
                                                            <strong><p>Travel Details</p></strong>
                                                            @else 
                                                            <strong>1ST LEG OF TRAVEL:</strong> <span>{{ $reservation_record['v_reservation_number'] }}</span>
                                                             @endif</td>
                                                            <td align="right"> <strong> Date: @if(isset($reservation_record['d_travel_date'])) {!! date(DATE_FORMAT,strtotime($reservation_record['d_travel_date'])) !!}@else {{'-'}} @endif</strong> </td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(isset($reservation_record['PickupCity']) && (!empty($reservation_record['PickupCity']) && !empty($reservation_record['DropOffCity'] )))
                                                       
                                                            <tr>
                                                                <td colspan="2">
                                                                    <strong>To arrive at pickup location between </strong> :
                                                                    <span>{{$reservation_record['t_comfortable_time'] ?  date('H:i' , strtotime($reservation_record['t_comfortable_time']))   : '-' }} ({{ $reservation_record['t_comfortable_time'] ? date('g:i A' , strtotime($reservation_record['t_comfortable_time'])) : '-'}}) {{$reservation_record['t_target_time'] ?  'and '.date('H:i' , strtotime($reservation_record['t_target_time']))   : '' }} {{ $reservation_record['t_target_time'] ? '('.date('g:i A' , strtotime($reservation_record['t_target_time'])).')' : ''}}</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <table width="100%" class="travel_details_child">
                                                                        <tr>
                                                                            <td style="width:48%;vertical-align: top;">
                                                                                <table width="100%" class="travel_details_sub_child">
                                                                                    <tr>
                                                                                        <td colspan="3"style="padding:20px;vertical-align: top;">
                                                                                            <strong>Pickup Location</strong><span class="main-traveler--subtitle d-block mb-20" style="margin-top:5px">{{ $reservation_record['PickupCity']['v_city'].' ('.$reservation_record['PickupCity']['v_county'].')' }}</span>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="padding:0px 0px 12px 20px;vertical-align: top;">
                                                                                            <strong>Address</strong>
                                                                                        </td>
                                                                                        <td style="vertical-align:top;width: 20px;text-align: center;">:</td>
                                                                                        <td style="padding:0px 20px 12px 0px;vertical-align: top;"><span>{{ $reservation_record['v_pickup_address']  ? $reservation_record['v_pickup_address'] :  '-'}}</span></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="padding:0px 0px 12px 20px;vertical-align: top;">
                                                                                            <strong>City</strong>
                                                                                        </td>
                                                                                        <td style="vertical-align:top;width: 20px;text-align: center;">:</td>
                                                                                        <td style="padding:0px 20px 12px 0px;vertical-align: top;"><span>{{$reservation_record['PickupCity']['v_city'] ? $reservation_record['PickupCity']['v_city'] : '-' }}</span></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="padding:0px 0px 20px 20px;vertical-align: top;">
                                                                                            <strong>County</strong>
                                                                                        </td>
                                                                                        <td style="vertical-align:top;width: 20px;text-align: center;">:</td>
                                                                                        <td style="padding:0px 20px 20px 0px;vertical-align: top;"><span>{{$reservation_record['PickupCity']['v_county'] ? $reservation_record['PickupCity']['v_county'] : '-' }}</span> </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </td>
                                                                            <td style="width:2%;background-color:#FFF;"></td>
                                                                            <td style="width:48%;vertical-align: top;">
                                                                                <table width="100%" class="travel_details_sub_child">
                                                                                    <tr>
                                                                                        <td colspan="3"style="padding:20px;vertical-align: top;">
                                                                                            <strong>Drop Off Location</strong>
                                                                                        <span class="main-traveler--subtitle d-block mb-20" style="margin-top:5px">{{ $reservation_record['DropOffCity']['v_city'].' ('.$reservation_record['DropOffCity']['v_county'].')' }}</span>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="padding:0px 0px 12px 20px;vertical-align: top;">
                                                                                            <strong>Address</strong>
                                                                                        </td>
                                                                                        <td style="vertical-align:top;width: 20px;text-align: center;">:</td>
                                                                                        <td style="padding:0px 20px 12px 0px;vertical-align: top;">
                                                                                           <span> {{ $reservation_record['v_dropoff_address']  ? $reservation_record['v_dropoff_address'] :  '-'}}</span>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="padding:0px 0px 12px 20px;vertical-align: top;">
                                                                                            <strong>City</strong>
                                                                                        </td>
                                                                                        <td style="vertical-align:top;width: 20px;text-align: center;">:</td>
                                                                                        <td style="padding:0px 20px 12px 0px;vertical-align: top;">
                                                                                        <span>{{$reservation_record['DropOffCity']['v_city'] ? $reservation_record['DropOffCity']['v_city'] : '-' }}</span>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="padding:0px 0px 20px 20px;vertical-align: top;">
                                                                                            <strong>County</strong>
                                                                                        </td>
                                                                                        <td style="vertical-align:top;width: 20px;text-align: center;">:</td>
                                                                                        <td style="padding:0px 20px 20px 0px;vertical-align: top;">
                                                                                       <span>{{$reservation_record['DropOffCity']['v_county'] ? $reservation_record['DropOffCity']['v_county'] : '-' }}</span>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            @if(!empty($reservation_record['e_flight_type'] || $reservation_record['v_flight_number'] || $reservation_record['t_flight_time'] || $reservation_record['v_flight_name']))
                                                                <tr>   
                                                                    <td colspan="2" style="padding:0 25px 10px 25px;">
                                                                        <strong>{{ ($res1_tt_text) ? $res1_tt_text['direction'] : '' }} Flight Details</strong>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2" style="padding: 0 25px 1rem 25px">
                                                                        @if(isset($reservation_record['e_flight_type'])) 
                                                                            
                                                                            <span style="width:8px; height:8px;background-color:#5d59a6;border-radius:100%;display: inline-block;
                                                                            vertical-align: middle;" width="8px" height="8px"></span>
                                                                            <span><strong>Flight Type</strong></span>
                                                                            <span style="margin-right: 20px;">: {!! $reservation_record['e_flight_type'] !!} </span>
                                                                            
                                                                        @endif
                                                                        @if(isset($reservation_record['v_flight_name'])) 
                                                                       
                                                                            <span style="width:8px; height:8px;background-color:#5d59a6;border-radius:100%;display: inline-block;
                                                                            vertical-align: middle;;" width="8px" height="8px"></span>
                                                                            <span><strong>Airline</strong></span>
                                                                            <span style="margin-right: 20px;">: {!! $reservation_record['v_flight_name'] !!}</span>
                                                                           
                                                                        @endif
                                                                        @if(isset($reservation_record['v_flight_number'])) 
                                                                        
                                                                            <span style="width:8px; height:8px;background-color:#5d59a6;border-radius:100%;display: inline-block;
                                                                            vertical-align: middle;;" width="8px" height="8px"></span>
                                                                            <span><strong>Flight #</strong></span>
                                                                            <span style="margin-right: 20px;">: {!! $reservation_record['v_flight_number'] !!}</span>
                                                                        
                                                                        @endif  
                                                                        @if(isset($reservation_record['t_flight_time'])) 
                                                                        
                                                                            <span style="width:8px; height:8px;background-color:#5d59a6;border-radius:100%;display: inline-block;
                                                                            vertical-align: middle;;" width="8px" height="8px"></span>
                                                                            <span><strong>Flight Time</strong></span>
                                                                            <span>: {!! date('g:i A' , strtotime($reservation_record['t_flight_time'])) !!}</span>
                                                                        
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @else
                                                        <tr>
                                                            <td colspan="2" style="text-align: center">
                                                                <strong>Information is not yet added.</strong>
                                                            </td>
                                                        </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            
                                                @if(isset($reservation_record_rt['e_class_type']) && $reservation_record_rt['e_class_type'] =="RT") 
                                                <table class="travel_details">
                                                    <thead>
                                                        <tr>
                                                            <td> 
                                                                <!-- <strong>2ND LEG OF TRAVEL </strong> -->
                                                                <strong class="d-none d-sm-none d-md-none d-lg-inline">2ND LEG OF TRAVEL: </strong><span>{{ $reservation_record_rt['v_reservation_number'] }}</span>
                                                            </td>
                                                            <td align="right"> <strong> Date: @if(isset($reservation_record_rt['d_travel_date'])) {!! date(DATE_FORMAT,strtotime($reservation_record_rt['d_travel_date'])) !!}@else {{'-'}} @endif</strong> </td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(isset($reservation_record['PickupCity']) && (!empty($reservation_record['PickupCity']) && !empty($reservation_record['DropOffCity'] )))
                                                        <tr>
                                                                <td colspan="2">
                                                                    <strong>To arrive at pickup location between </strong> :
                                                                    <span>{{$reservation_record_rt['t_comfortable_time'] ?  date('H:i' , strtotime($reservation_record_rt['t_comfortable_time']))   : '-' }} ({{ $reservation_record_rt['t_comfortable_time'] ? date('g:i A' , strtotime($reservation_record_rt['t_comfortable_time'])) : '-'}}) {{$reservation_record_rt['t_target_time'] ?  'and '.date('H:i' , strtotime($reservation_record_rt['t_target_time']))   : '' }} {{ $reservation_record_rt['t_target_time'] ? '('.date('g:i A' , strtotime($reservation_record_rt['t_target_time'])).')' : ''}}</span>
                                                                </td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <td colspan="2">
                                                                    <table width="100%" class="travel_details_child">
                                                                        <tr>
                                                                            <td style="width:48%;vertical-align: top;">
                                                                                <table width="100%" class="travel_details_sub_child">
                                                                                    <tr>
                                                                                        <td colspan="3"style="padding:20px;">
                                                                                            <strong>Pickup Location </strong>
                                                                                            <span class="main-traveler--subtitle d-block mb-20" style="margin-top:5px">{{ $reservation_record_rt['PickupCity']['v_city'].' ('.$reservation_record_rt['PickupCity']['v_county'].')' }}</span>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="padding:0px 0px 12px 20px;vertical-align: top;">
                                                                                            <strong>Address</strong>
                                                                                        </td>
                                                                                        <td style="vertical-align:top;width: 20px;text-align: center;">:</td>
                                                                                        <td style="padding:0px 20px 12px 0px;vertical-align: top;">
                                                                                            <span> {{ $reservation_record_rt['v_pickup_address']  ? $reservation_record_rt['v_pickup_address'] :  '-'}}</span></span>
                                                                                    </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="padding:0px 0px 12px 20px;vertical-align: top;">
                                                                                            <strong>City</strong>
                                                                                        </td>
                                                                                        <td style="vertical-align:top;width: 20px;text-align: center;">:</td>
                                                                                        <td style="padding:0px 20px 12px 0px;vertical-align: top;">
                                                                                            <span>{{$reservation_record_rt['PickupCity']['v_city'] ? $reservation_record_rt['PickupCity']['v_city'] : '-' }}</span>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="padding:0px 0px 20px 20px;vertical-align: top;">
                                                                                            <strong>County</strong>
                                                                                        </td>
                                                                                        <td style="vertical-align:top;width: 20px;text-align: center;">:</td>
                                                                                        <td style="padding:0px 20px 20px 0px;vertical-align: top;">
                                                                                            <span>{{$reservation_record_rt['PickupCity']['v_county'] ? $reservation_record_rt['PickupCity']['v_county'] : '-' }}
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </td>
                                                                            <td style="width:2%;background-color:#FFF;"></td>
                                                                            <td style="width:48%;vertical-align: top;">
                                                                                <table width="100%" class="travel_details_sub_child">
                                                                                    <tr>
                                                                                        <td colspan="3"style="padding:20px;vertical-align: top;">
                                                                                            <strong>Drop Off Location</strong>
                                                                                            <span class="main-traveler--subtitle d-block mb-20" style="margin-top:5px">{{ $reservation_record_rt['DropOffCity']['v_city'].' ('.$reservation_record_rt['DropOffCity']['v_county'].')' }}</span>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="padding:0px 0px 12px 20px;vertical-align: top;">
                                                                                            <strong>Address</strong>
                                                                                        </td>
                                                                                        <td style="vertical-align:top;width: 20px;text-align: center;">:</td>
                                                                                        <td style="padding:0px 20px 12px 0px;vertical-align: top;">
                                                                                            <span> {{ $reservation_record_rt['v_dropoff_address']  ? $reservation_record_rt['v_dropoff_address'] :  '-'}}</span>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="padding:0px 0px 12px 20px;vertical-align: top;">
                                                                                            <strong>City</strong>
                                                                                        </td>
                                                                                        <td style="vertical-align:top;width: 20px;text-align: center;">:</td>
                                                                                        <td style="padding:0px 20px 12px 0px;vertical-align: top;">
                                                                                           <span>{{$reservation_record_rt['DropOffCity']['v_city'] ? $reservation_record_rt['DropOffCity']['v_city'] : '-' }}</span>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="padding:0px 0px 20px 20px;vertical-align: top;">
                                                                                            <strong>County</strong>
                                                                                        </td>
                                                                                        <td style="vertical-align:top;width: 20px;text-align: center;">:</td>
                                                                                        <td style="padding:0px 20px 20px 0px;vertical-align: top;">
                                                                                           <span>{{$reservation_record_rt['DropOffCity']['v_county'] ? $reservation_record_rt['DropOffCity']['v_county'] : '-' }}
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </td>

                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            @if(!empty($reservation_record_rt['e_flight_type'] || $reservation_record_rt['v_flight_number'] || $reservation_record_rt['t_flight_time'] || $reservation_record_rt['v_flight_name']))
                                                                <tr>
                                                                    <td colspan="2" style="padding:0 25px 10px 25px;">
                                                                        <strong>{{ ($res2_tt_text) ? $res2_tt_text['direction'] : '' }} Flight Details</strong>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2" style="padding: 0 25px 1rem 25px">
                                                                        @if(isset($reservation_record_rt['e_flight_type'])) 
                                                                        <span style="width:8px; height:8px;background-color:#5d59a6;border-radius:100%;display: inline-block;
                                                                            vertical-align: middle;" width="8px" height="8px"></span>
                                                                            <span><strong>Flight Type</strong></span>
                                                                            <span style="margin-right: 20px;">: {!! $reservation_record_rt['e_flight_type'] !!}</span>
                                                                        @endif 
                                                                        @if(isset($reservation_record_rt['v_flight_name'])) 
                                                                        <span style="width:8px; height:8px;background-color:#5d59a6;border-radius:100%;display: inline-block;
                                                                        vertical-align: middle;;" width="8px" height="8px"></span>
                                                                        <span><strong>Airline</strong></span>
                                                                        <span style="margin-right: 20px;">: {!! $reservation_record_rt['v_flight_name'] !!}</span>
                                                                        @endif
                                                                        @if(isset($reservation_record_rt['v_flight_number'])) 
                                                                            <span style="width:8px; height:8px;background-color:#5d59a6;border-radius:100%;display: inline-block;
                                                                            vertical-align: middle;;" width="8px" height="8px"></span>
                                                                            <span><strong>Flight #</strong></span>
                                                                            <span style="margin-right: 20px;">: {!! $reservation_record_rt['v_flight_number'] !!}</span>
                                                                        @endif  
                                                                        @if(isset($reservation_record_rt['t_flight_time'])) 
                                                                            <span style="width:8px; height:8px;background-color:#5d59a6;border-radius:100%;display: inline-block;
                                                                            vertical-align: middle;;" width="8px" height="8px"></span>
                                                                            <span><strong>Flight Time</strong></span>
                                                                            <span>: {!! date('g:i A' , strtotime($reservation_record_rt['t_flight_time'])) !!}</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        
                                                           
                                                        @else
                                                            <tr>
                                                                <td colspan="2" style="text-align: center">
                                                                <strong>Information is not yet added.</strong>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                        
                                                    </tbody>
                                                </table>
                                                @endif
                                                @if($reservation_record['e_shuttle_type']=='Shared')
                                                    <table class="travel_charges_details">
                                                        <thead>
                                                            <tr>
                                                                <td colspan="4"> <strong>Breakdown of Charges</strong></td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <th style="padding:20px 0 20px 25px;">
                                                                    <strong> Fares </strong>
                                                                </th>
                                                                <td style="padding:20px 25px 20px 0px;" data-th="Fares">
                                                                    <?php if(!empty($total_fare_amount_rt) && ($total_fare_amount_rt['total'])){  
                                                                            $total_rt_fare_amount = $total_fare_amount_rt['total']; 
                                                                            $total_fare_amounts_ow = $total_fare_amount['total'] ? $total_fare_amount['total'] : 0;
                                                                        }else {
                                                                            $total_rt_fare_amount = 0.00;

                                                                            $total_fare_amounts_ow = $total_fare_amount['total'] ? $total_fare_amount['total'] : 0;
                                                                        }
                                                                            $total_fare_amounts_ow_rt = $total_rt_fare_amount + $total_fare_amounts_ow;
                                                                        ?>
                                                                    <span style="font-weight:bold;">:&nbsp;&nbsp; ${{ $total_fare_amounts_ow_rt ?  number_format((float)$total_fare_amounts_ow_rt, 2, '.', '') : '0.00'}} </span>
                                                                </td>
                                                                @if($reservation_record['e_class_type'] =="RT")
                                                                    @if($reservation_luggage_info_total !='')
                                                                    
                                                                        <?php $other_total = ($reservation_luggage_info_total + $reservation_luggage_info_total_rt); ?>
                                                                        <th style="padding:20px 0px 20px 0;">
                                                                            <strong> Other Charges </strong>
                                                                        </th>
                                                                        <td style="padding:20px 25px 20px 0;" data-th="Other Charges">
                                                                            <span style="font-weight:bold;">:&nbsp;&nbsp; ${{ $other_total ?  number_format((float)$other_total, 2, '.', '') : '0.00' }} </span>
                                                                        </td>
                                                                    @else
                                                                    <th style="padding:20px 0px 20px 0;">
                                                                        <strong> Other Charges </strong>
                                                                    </th>
                                                                    <td style="padding:20px 25px 20px 0;" data-th="Other Charges">
                                                                        <span style="font-weight:bold;">:&nbsp;&nbsp;$0.00</span>
                                                                    </td>
                                                                    @endif
                                                                @else
                                                                    <th style="padding:20px 0px 20px 0;">
                                                                        <strong> Other Charges </strong>
                                                                    </th>
                                                                
                                                                    <td style="padding:20px 25px 20px 0;" data-th="Other Charges">
                                                                        <span style="font-weight:bold;">:&nbsp;&nbsp; ${{ $reservation_luggage_info_total ?  number_format((float)$reservation_luggage_info_total, 2, '.', '') : '0.00' }}</span>
                                                                    </td>
                                                                        
                                                                @endif
                                                                
                                                            </tr>
                                                            @if(!empty($payment_info))
                                                                <tr>
                                                                    <th style="padding:0px 0 20px 25px;">
                                                                        <strong> Mode of Payment </strong>
                                                                    </th>
                                                                    <td style="padding:0px 25px 20px 0px;" data-th="Mode of Payment">
                                                                        <span style="font-weight:bold;">:&nbsp;&nbsp;  {{ ($payment_mode) ? $payment_mode : '-' }} </span>
                                                                    </td>
                                                                    
                                                                    <th style="padding:0px 0px 20px 0;">
                                                                        <strong> Payment Status </strong>
                                                                    </th>
                                                                    <td style="padding:0px 25px 20px 0;" data-th="Payment Status">
                                                                        <span style="font-weight:bold;">:&nbsp;&nbsp; {{ (count($payment_info) > 0 && $payment_info[0]['e_status']=="Success") ? "Paid" : "Failed" }}</span>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                
                                                    @if(!empty($total_fare_amount['adult_count'] || $total_fare_amount['child_count'] || $total_fare_amount['infant_count'] || $total_fare_amount['military_count'] || $total_fare_amount['senior_count']))
                                                        <table class="price_information">
                                                            <thead>
                                                                <tr>
                                                                    <td align="left">Fare details</td>
                                                                    <th style="text-align: right;">1st leg </th>
                                                                    <th style="text-align: right;">2nd leg</th>
                                                                    <th style="text-align: right;">total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if($total_fare_amount['adult_count'] > 0 || (isset($total_fare_amount_rt['adult_count']) && $total_fare_amount_rt['adult_count'] > 0 ))
                                                                <tr>
                                                                    <td align="left" data-th="Fare">Adult Fare&nbsp;({{  $total_fare_amount['adult_count'] + ((isset($total_fare_amount_rt['adult_count'])) ? $total_fare_amount_rt['adult_count'] : 0) }})</td>
                                                                    <td align="right" data-th="1st leg"><span>${{$total_fare_amount['adult_total'] ? number_format((float)$total_fare_amount['adult_total'], 2, '.', '') : 0 }}</span></td>
                                                                    <?php if(!empty($total_fare_amount_rt) && ($total_fare_amount_rt['adult_total'])){  $total_amount_rt = $total_fare_amount_rt['adult_total']; } else{
                                                                        $total_amount_rt = 0.00;
                                                                    } 
                                                                    $total_adult_fare_details = $total_fare_amount['adult_total'] +  $total_amount_rt

                                                                    
                                                                    ?>
                                                                    <td align="right" data-th="2nd leg"><span>${{ number_format((float)$total_amount_rt, 2, '.', '') }}</span></td>
                                                                    <td align="right" data-th="Total"><span>${{  number_format((float)$total_adult_fare_details, 2, '.', '')}}</span></td>
                                                                </tr>
                                                                @endif
                                                                @if($total_fare_amount['child_count'] > 0 || (isset($total_fare_amount_rt['child_count']) && $total_fare_amount_rt['child_count'] > 0 ))
                                                                <tr>
                                                                    <td align="left" data-th="Fare details">Child Fare&nbsp;({{  $total_fare_amount['child_count'] + ((isset($total_fare_amount_rt['child_count'])) ? $total_fare_amount_rt['child_count'] : 0) }})</td>
                                                                    <td align="right" data-th="1st leg"><span>${{$total_fare_amount['child_total'] ? number_format((float)$total_fare_amount['child_total'], 2, '.', '') : 0 }}</span></td>
                                                                    <?php if(!empty($total_fare_amount_rt) && ($total_fare_amount_rt['child_total'])){  $total_child_rt = $total_fare_amount_rt['child_total']; } else{
                                                                        $total_child_rt = 0.00;
                                                                    }
                                                                    $total_child_fare_details = $total_fare_amount['child_total'] +  $total_child_rt 

                                                                    ?>
                                                                    <td align="right" data-th="2nd leg"><span>${{ number_format((float)$total_child_rt, 2, '.', '') }}</span></td>
                                                                    <td align="right" data-th="Total"><span>${{ number_format((float)$total_child_fare_details, 2, '.', '')}}</span></td>
                                                                </tr>
                                                                @endif
                                                                
                                                                @if($total_fare_amount['infant_count'] > 0 || (isset($total_fare_amount_rt['infant_count']) && $total_fare_amount_rt['infant_count'] > 0 ))
                                                                <tr>
                                                                    <td align="left" data-th="Fare details">Infant Fare&nbsp;({{  $total_fare_amount['infant_count'] + ((isset($total_fare_amount_rt['infant_count'])) ? $total_fare_amount_rt['infant_count'] : 0) }})</td>
                                                                    <td align="right" data-th="1st leg"><span>${{$total_fare_amount['infant_total'] ? number_format((float)$total_fare_amount['infant_total'], 2, '.', '') : 0 }}</span></td>
                                                                    <?php if(!empty($total_fare_amount_rt) && ($total_fare_amount_rt['infant_total'])){  $total_infant_rt = $total_fare_amount_rt['infant_total']; } else{
                                                                        $total_infant_rt = 0.00;
                                                                    } 
                                                                    $total_infant_fare_details =  $total_fare_amount['infant_total'] +  $total_infant_rt
                                                                    ?>
                                                                    <td align="right" data-th="2nd leg"><span>${{ number_format((float)$total_infant_rt, 2, '.', '') }}</span></td>
                                                                    <td align="right" data-th="Total"><span>${{number_format((float)$total_infant_fare_details, 2, '.', '') }}</span></td>
                                                                </tr>
                                                                @endif
                                                                @if($total_fare_amount['military_count'] > 0 || (isset($total_fare_amount_rt['military_count']) && $total_fare_amount_rt['military_count'] > 0 ))
                                                                <tr>
                                                                    <td align="left" data-th="Fare details">Military Fare&nbsp;({{  $total_fare_amount['military_count'] + ((isset($total_fare_amount_rt['military_count'])) ? $total_fare_amount_rt['military_count'] : 0) }})</td>
                                                                    <td align="right" data-th="1st leg"><span>${{$total_fare_amount['military_total'] ? number_format((float)$total_fare_amount['military_total'], 2, '.', '') : 0 }}</span></td>
                                                                    <?php if(!empty($total_fare_amount_rt) && ($total_fare_amount_rt['military_total'])){  $total_military_rt = $total_fare_amount_rt['military_total']; } else{
                                                                        $total_military_rt = 0.00;
                                                                    }
                                                                    $total_military_fare_details = $total_fare_amount['military_total'] +  $total_military_rt
                                                                    ?>
                                                                    <td align="right" data-th="2nd leg"><span>${{ number_format((float)$total_military_rt, 2, '.', '') }}</span></td>
                                                                    <td align="right" data-th="Total"><span>${{number_format((float)$total_military_fare_details, 2, '.', '') }}</span></td>
                                                                </tr>
                                                                @endif
                                                                @if($total_fare_amount['senior_count'] > 0 || (isset($total_fare_amount_rt['senior_count']) && $total_fare_amount_rt['senior_count'] > 0 ))
                                                                <tr>
                                                                    <td align="left" data-th="Fare details">Senior Fare&nbsp;({{  $total_fare_amount['senior_count'] + ((isset($total_fare_amount_rt['senior_count'])) ? $total_fare_amount_rt['senior_count'] : 0) }})</td>
                                                                    <td align="right" data-th="1st leg"><span>${{$total_fare_amount['senior_total'] ? number_format((float)$total_fare_amount['senior_total'], 2, '.', '') : 0 }}</span></td>
                                                                    <?php if(!empty($total_fare_amount_rt) && ($total_fare_amount_rt['senior_total'])){  $total_senior_rt = $total_fare_amount_rt['senior_total']; } else{
                                                                        $total_senior_rt = 0.00;
                                                                    } 
                                                                    $total_senior_fare_details = $total_fare_amount['senior_total'] +  $total_senior_rt
                                                                    ?>
                                                                    <td align="right" data-th="2nd leg"><span>${{ number_format((float)$total_senior_rt, 2, '.', '') }}</span></td>
                                                                    <td align="right" data-th="Total"><span>${{number_format((float)$total_senior_fare_details, 2, '.', '') }}</span></td>
                                                                </tr>
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    @endif
                                                @endif
                                                @if((count($reservation_luggage_info) > 0) || (count($reservation_pet_info) > 0))
                                                    @if($reservation_record['e_shuttle_type']=='Shared')
                                                    <table class="price_information">
                                                        <thead>
                                                            <tr>
                                                                <td align="left">1ST LEG OF LUGGAGE CHARGE DETAILS</td>
                                                                <th style="text-align:right;">CHARGE</th>
                                                                <th style="text-align:right;">TOTAL FARE</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($reservation_luggage_info as $records)
                                                    
                                                    
                                                                @if(count($records['system_luggage_def']) > 0)
                                                                    <tr>
                                                                        <td align="left" data-th="DETAILS">{{ $records['system_luggage_def'][0]['v_name'] }}&nbsp;({{ $records['i_value'] }})</td>
                                                                        <td align="right" data-th="CHARGE"><span>${{ $records['system_luggage_def'][0]['d_unit_price'] }}</span></td>
                                                                        <td align="right" data-th="TOTAL FARE"><span>${{ $records['d_price'] }}</span></td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                            @foreach($reservation_pet_info as $records)
                                                                @if(count($records['system_animal_def']) > 0)
                                                                <tr>
                                                                    <td align="left" data-th="DETAILS">{{ $records['system_animal_def'][0]['v_name'] }}&nbsp;(1) </td>
                                                                    <td align="right" data-th="CHARGE"><span>${{ $records['system_animal_def'][0]['d_unit_price'] }}</span></td>
                                                                    <td align="right" data-th="TOTAL FARE"><span>${{ $records['system_animal_def'][0]['d_unit_price'] }}</span></td>
                                                                </tr>
                                                                @endif
                                                            @endforeach
                                                        
                                                        </tbody>
                                                    </table>
                                                    @else
                                                    <table class="price_information">
                                                        <thead>
                                                            <tr>
                                                                <td align="left">1ST LEG OF LUGGAGE CHARGE DETAILS</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($reservation_luggage_info as $records)
                                                    
                                                    
                                                                @if(count($records['system_luggage_def']) > 0)
                                                                    <tr>
                                                                        <td align="left">{{ $records['system_luggage_def'][0]['v_name'] }}&nbsp;({{ $records['i_value'] }})</td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                            @foreach($reservation_pet_info as $records)
                                                                @if(count($records['system_animal_def']) > 0)
                                                                <tr>
                                                                    <td align="left">{{ $records['system_animal_def'][0]['v_name'] }}&nbsp;(1) </td>
                                                                </tr>
                                                                @endif
                                                            @endforeach
                                                        
                                                        </tbody>
                                                    </table>
                                                    @endif
                                                @endif

                                                @if(isset($reservation_luggage_info_rt))
                                                    @if((count($reservation_luggage_info_rt) > 0) || (count($reservation_pet_info_rt) > 0))
                                                    @if($reservation_record['e_shuttle_type']=='Shared')   
                                                    <table class="price_information">
                                                        <thead>
                                                            <tr>
                                                                <td align="left">2ND LEG OF LUGGAGE CHARGE DETAILS</td>
                                                                <th style="text-align:right;">CHARGE</th>
                                                                <th style="text-align:right;">TOTAL FARE</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($reservation_luggage_info_rt as $records_rt)
                                                    
                                                    
                                                                @if(count($records_rt['system_luggage_def']) > 0)
                                                                    <tr>
                                                                        <td align="left" data-th="DETAILS">{{ $records_rt['system_luggage_def'][0]['v_name'] }}&nbsp;({{ $records_rt['i_value'] }})</td>
                                                                        <td align="right" data-th="CHARGE"><span>${{ $records_rt['system_luggage_def'][0]['d_unit_price'] }}</span></td>
                                                                        <td align="right" data-th="TOTAL FARE"><span>${{ $records_rt['d_price'] }}</span></td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                            @foreach($reservation_pet_info_rt as $records_rt)
                                                                @if(count($records_rt['system_animal_def']) > 0)
                                                                <tr>
                                                                    <td align="left" data-th="DETAILS">{{ $records_rt['system_animal_def'][0]['v_name'] }}&nbsp;(1)</td>
                                                                    <td align="right" data-th="CHARGE"><span>${{ $records_rt['system_animal_def'][0]['d_unit_price'] }}</span></td>
                                                                    <td align="right" data-th="TOTAL FARE"><span>${{ $records_rt['system_animal_def'][0]['d_unit_price'] }}</span></td>
                                                                </tr>
                                                                @endif
                                                            @endforeach
                                                        
                                                        </tbody>
                                                    </table>
                                                    @else
                                                    <table class="price_information">
                                                        <thead>
                                                            <tr>
                                                                <td align="left">2ND LEG OF LUGGAGE CHARGE DETAILS</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($reservation_luggage_info_rt as $records_rt)
                                                    
                                                    
                                                                @if(count($records_rt['system_luggage_def']) > 0)
                                                                    <tr>
                                                                        <td align="left">{{ $records_rt['system_luggage_def'][0]['v_name'] }}&nbsp;({{ $records_rt['i_value'] }})</td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                            @foreach($reservation_pet_info_rt as $records_rt)
                                                                @if(count($records_rt['system_animal_def']) > 0)
                                                                <tr>
                                                                    <td align="left">{{ $records_rt['system_animal_def'][0]['v_name'] }}&nbsp;(1)</td>
                                                                </tr>
                                                                @endif
                                                            @endforeach
                                                        
                                                        </tbody>
                                                    </table>
                                                    @endif
                                                    @endif
                                                @endif

                                                @if($reservation_record['e_shuttle_type']=='Shared')  
                                                    <table style="margin: 15px 0px;">
                                                        <tr>
                                                            <td>*Must travel with Full Fare Adult </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                **Must tell us in advance
                                                            </td>
                                                        </tr>
                                                    </table>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="d-none" id="ps_desc">
                    </div> 
                </div>
            </div>
                
            
            <div class="tab-pane" id="kt_tabs_activity_logs" role="tabpanel">
                <div class="kt-portlet kt-portlet--mobile">
                  <div class="kt-portlet__head kt-portlet__head--lg">
                      <div class="kt-portlet__head-label">
                          <span class="kt-portlet__head-icon">
                              <i class="kt-font-brand fa fa-list-alt"></i>
                          </span>
                          <h3 class="kt-portlet__head-title">
                            Activity Logs: #{{$reservation_record['v_reservation_number']}}
                          </h3>
                          </div>
                          
                  </div>
                  <div class="kt-portlet__body">
                 
                    <!--begin: Datatable -->

                    <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable_ajax">
                        <thead>
                            <tr>
                                <th class="no-sort"><strong>Old Status</strong></th>
                                <th class="no-sort"><strong>New Status</strong></th>
                                <th class="no-sort"><strong>Note</strong></th>
                                <th class="no-sort"><strong>Modify By</strong></th>
                                <th class="no-sort"><strong>User Type</strong></th>
                                <th class="no-sort"><strong>Date</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                           
                        </tbody>

                        <input type="hidden" id="reservation_id" value="{{$id }}" />
                    </table>

                    <!--end: Datatable -->
                  </div>
                </div>
              </div>
        </div>
    </div>
</div>

@stop

@section('custom_js')
    <script>
        $(document).ready(function() {
            var reservation_id = $('#reservation_id').val();
            var url = ADMIN_URL + 'reservation-log/list-ajax/' + reservation_id;
            var order =  [0, 'desc'];
            DataTables.init('#datatable_ajax', url, order);
        
            $('#printPage').on('click',function(){
                var id = $(this).attr('printId');
                var url = (ADMIN_URL + 'reservations/print/'+id);
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

            var url = window.location.href;        
            if(url.indexOf("#") <= 0) {
                var activeTab = 'kt_tabs_traveller_info';
            } else {
                var activeTab = url.substring(url.indexOf("#") + 1);
            }
            $(".nav-item li").removeClass("active"); 
            $('a[href="#'+ activeTab +'"]').tab('show');
            var reseration_status = $('.e_reservation_status option:selected').val();
            hide_amout(reseration_status);
            $('.e_reservation_status').on('change',function(){
                var value = $(this).val();
                hide_amout(value);
            });
                
        }); 
        function hide_amout(value){
            if(value == 'Request Confirmed') {
                $('.withoutRequestedStatus').attr('style', 'display:none');
                $('.withRequestedStatus').removeAttr('style', true);
                $('.withRequestedStatus').attr('style',  'padding:0px 20px 20px 20px;');
                $('.amount').removeClass('d-none');
            } else {
                $('.withoutRequestedStatus').attr('style',  'padding:20px;');
                $('.withRequestedStatus').attr('style', 'display:none;');
                //$('.withoutRequestedStatus').removeAttr('style', true);
                $('.amount').addClass('d-none');
            }
        }
    </script>
@stop
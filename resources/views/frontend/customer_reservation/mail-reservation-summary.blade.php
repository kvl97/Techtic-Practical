<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <style>
            
            table {
                font-family: arial, sans-serif;
                width: 100%;
                border-collapse: collapse;
                font-size: 13px;
                border-spacing: 0;
            }
            td {
                /* line-height: 1.5; */
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
        </style>
    </head>
    <body>
        <table style="border-collapse: collapse;background-color: #f2f2f2;margin: 0 -10px 0 0;margin-left: auto;margin-right: auto;">
            <tr>
                <td style="padding:0;">
                    <table style="border-collapse: collapse;background-color: #dcdcf3; border-radius: 10px 10px 0 0;page-break-inside: avoid;width:100%;">
                        <tr>
                            <td style="padding:25px 20px;">
                                <span style="background-color: #fff;border-radius: 50px;padding: .7em 1.5em;"><strong>Reservation No. : </strong><span>{{ $reservation_record['v_reservation_number']}}</span></span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding:20px;">
                    <table style="border-collapse: collapse;background-color:#f2f2f2;margin: 0px;page-break-inside: avoid;width:100%;">
                        <tr>
                            <td>
                                <table cellspacing="0" cellpadding="0" border="0" align="center" style="border-collapse: collapse;width:100%;background-color:#f2f2f2;">
                                    
                                    <tr>
                                        <td align="left" style="background-color: #FFF; border: 1px solid #ccc;width: 48%;vertical-align: top;">
                                            <table style="border-collapse: collapse;width:100%;">
                                                <tr>
                                                    <td colspan="3" style="background-color: #f0f0f9;font-weight: 700;text-transform: uppercase;padding:1rem 20px;">
                                                        Main Traveler
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding:20px 10px 12px 20px;">
                                                        Name
                                                    </td>
                                                    <td style="padding-top:20px;vertical-align:top;width: 20px;text-align: center;">:</td>
                                                    <td style="padding:20px 20px 12px 0px;">
                                                        <span> @if(isset($reservation_record['v_contact_name'])) {!! $reservation_record['v_contact_name']!!}@endif</span>
                                                    </td>
                                                    
                                                </tr>
                                                <tr>
                                                    <td style="padding:12px 10px 12px 20px;">
                                                        Email
                                                    </td>
                                                    <td style="padding-top:12px;vertical-align:top;width: 20px;text-align: center;">:</td>
                                                    <td style="padding:12px 20px 12px 0px;">
                                                        <span> @if(isset($reservation_record['v_contact_email'])) {!! $reservation_record['v_contact_email'] !!}@endif</span>
                                                    </td>
                                                </tr>                                                        
                                                <tr>
                                                    <td style="padding:12px 10px 20px 20px;">
                                                        Phone
                                                    </td>
                                                    <td style="padding-top:12px;vertical-align:top;width: 20px;text-align: center;">:</td>
                                                    <td style="padding:12px 20px 20px 0px;">
                                                        <span> @if(isset($reservation_record['v_contact_phone_number'])) {!! $reservation_record['v_contact_phone_number'] !!}@endif</span>
                                                    </td>
                                                </tr>

                                            </table>
                                        </td>
                                        <td style="width:2%" width="2%"></td>
                                        <td align="right" style="background-color: #fff; width: 48%">
                                            <table style="border-collapse: collapse;width:100%;">
                                                <tr style="border: 1px solid #ccc;border-radius: 10px;">
                                                    <td style="padding:20px;background-color:#f0f0f9;font-weight:700;text-transform:uppercase;">         
                                                        <strong> Total Traveler </strong> 
                                                    </td>
                                                    <td style="padding:20px;width:50%;"> {{ $reservation_record['i_total_num_passengers'] }} </td>
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
                    
                    <table style="border-collapse: collapse;width:100%;background-color:#f2f2f2;margin: 20px 0px 0px 0px;">
                        <tr>
                            <td style="padding:0px 0px 0px 0px;">
                                <table style="border-collapse: collapse;width:100%;border: 1px solid #ccc; background-color:#fff; margin:0 0 20px 0;page-break-inside: avoid;">
                                    <tr style="background-color:#f0f0f9;">
                                        <td style="padding:1rem 20px;"> @if($reservation_record['e_class_type']=='OW')
                                                <strong>Travel Details </strong>
                                                @else
                                                <strong>1st Leg Of Travel: </strong>{{ $reservation_record['v_reservation_number'] }}
                                                @endif</td>
                                        <td style="text-align:right;padding:1rem 20px;"> <strong> Date: @if(isset($reservation_record['d_travel_date'])) {!! date(DATE_FORMAT,strtotime($reservation_record['d_travel_date'])) !!}@else {{'-'}} @endif</strong> </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="padding:1rem 1rem 20px 20px;">
                                            <strong>To arrive at pickup location between </strong> :
                                            <span style="font-size: 0.70rem;">{{$reservation_record['t_comfortable_time'] ?  date('H:i' , strtotime($reservation_record['t_comfortable_time']))   : '-' }} ({{ $reservation_record['t_comfortable_time'] ? date('g:i A' , strtotime($reservation_record['t_comfortable_time'])) : '-'}}) {{$reservation_record['t_target_time'] ?  'and '.date('H:i' , strtotime($reservation_record['t_target_time']))   : '' }} {{ $reservation_record['t_target_time'] ? '('.date('g:i A' , strtotime($reservation_record['t_target_time'])).')' : ''}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="padding:0px 20px 20px 20px;">
                                            <table style="background-color:#f0f0f9;">
                                                <tr>
                                                    <td style="width:48%;vertical-align: top;">
                                                        <table style="background-color:#f0f0f9;">
                                                            <tr>
                                                                <td colspan="3" style="padding:20px;vertical-align: top;">
                                                                    <strong>Pickup Location</strong>
                                                                    <p class="main-traveler--subtitle d-block mb-20" style="margin-top:10px">{{ $reservation_record['PickupCity']['v_city'].' ('.$reservation_record['PickupCity']['v_county'].')' }}</p>                                                           
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding:0px 0px 12px 20px;vertical-align: top;">
                                                                    <strong>Address</strong>
                                                                </td>
                                                                <td style="vertical-align:top;width: 20px;text-align: center;">:</td>
                                                                <td style="padding:0px 20px 12px 0px;vertical-align: top;">
                                                                    <span>{{ $reservation_record['v_pickup_address']  ? $reservation_record['v_pickup_address'] :  '-'}}</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding:0px 0px 12px 20px;vertical-align: top;">
                                                                    <strong>City</strong>
                                                                </td>
                                                                <td style="vertical-align:top;width: 20px;text-align: center;">:</td>
                                                                <td style="padding:0px 20px 12px 0px;vertical-align: top;">
                                                                    <span>{{$reservation_record['PickupCity']['v_city'] ? $reservation_record['PickupCity']['v_city'] : '-' }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding:0px 0px 20px 20px;vertical-align: top;">
                                                                    <strong>County</strong>
                                                                </td>
                                                                <td style="vertical-align:top;width: 20px;text-align: center;">:</td>
                                                                <td style="padding:0px 20px 20px 0px;vertical-align: top;">
                                                                    <span>{{$reservation_record['PickupCity']['v_county'] ? $reservation_record['PickupCity']['v_county'] : '-' }}</span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td style="width:2%;background-color:#FFF;"></td>
                                                    <td style="width:48%;vertical-align: top;">
                                                        <table style="background-color:#f0f0f9;">
                                                            <tr>
                                                                <td colspan="3" style="padding:20px;vertical-align: top;">
                                                                    <strong>Drop Off Location</strong>
                                                                    <p class="main-traveler--subtitle d-block mb-20" style="margin-top:10px">{{ $reservation_record['DropOffCity']['v_city'].' ('.$reservation_record['DropOffCity']['v_county'].')' }}</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding:0px 0px 12px 20px;vertical-align: top;">
                                                                    <strong>Address</strong>
                                                                </td>
                                                                <td style="vertical-align:top;width: 20px;text-align: center;">:</td>
                                                                <td style="padding:0px 20px 12px 0px;vertical-align: top;">
                                                                    <span>{{ $reservation_record['v_dropoff_address']  ? $reservation_record['v_dropoff_address'] :  '-'}}</span>
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
                                                                    <strong>Country</strong>
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
                                </table>
                                @if(isset($reservation_record['e_class_type']) && $reservation_record['e_class_type'] =="RT") 
                                <table style="border-collapse: collapse;width:100%;border: 1px solid #ccc; background-color:#fff; margin:0 0 20px 0;page-break-inside: avoid;">
                                    <tr style="background-color:#f0f0f9;">
                                        <td style="padding:1rem 20px;"> <strong>2nd Leg Of Travel: </strong>{{ $reservation_record_rt['v_reservation_number'] }}</td>
                                        <td style="text-align:right;padding:1rem 20px;"> <strong> Date: @if(isset($reservation_record_rt['d_travel_date'])) {!! date(DATE_FORMAT,strtotime($reservation_record_rt['d_travel_date'])) !!}@else {{'-'}} @endif</strong> </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="padding:1rem 1rem 20px 20px;">
                                            <strong>To arrive at pickup location between </strong> :
                                            <span style="font-size: 0.70rem;">{{$reservation_record_rt['t_comfortable_time'] ?  date('H:i' , strtotime($reservation_record_rt['t_comfortable_time']))   : '-' }} ({{ $reservation_record_rt['t_comfortable_time'] ? date('g:i A' , strtotime($reservation_record_rt['t_comfortable_time'])) : '-'}}) {{$reservation_record_rt['t_target_time'] ?  'and '.date('H:i' , strtotime($reservation_record_rt['t_target_time']))   : '' }} {{ $reservation_record_rt['t_target_time'] ? '('.date('g:i A' , strtotime($reservation_record_rt['t_target_time'])).')' : ''}}</span>
                                        </td>
                                    </tr>
                                        
                                    <tr>
                                        <td colspan="2" style="padding:0px 20px 20px 20px;">
                                            <table style="background-color:#f0f0f9;">
                                                <tr>
                                                    <td style="width:48%;vertical-align: top;">
                                                        <table style="background-color:#f0f0f9;">
                                                            <tr>
                                                                <td colspan="3" style="padding:20px;vertical-align: top;">
                                                                    <strong>Pickup Location</strong>
                                                                    <p class="main-traveler--subtitle d-block mb-20" style="margin-top:10px">{{ $reservation_record_rt['PickupCity']['v_city'].' ('.$reservation_record_rt['PickupCity']['v_county'].')' }}</p>                                                            
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding:0px 0px 12px 20px;vertical-align: top;">
                                                                    <strong>Address</strong>
                                                                </td>
                                                                <td style="vertical-align:top;width: 20px;text-align: center;">:</td>
                                                                <td style="padding:0px 20px 12px 0px;vertical-align: top;">
                                                                    <span>{{ $reservation_record_rt['v_pickup_address']  ? $reservation_record_rt['v_pickup_address'] :  '-'}}</span>
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
                                                                    <strong>Country</strong>
                                                                </td>
                                                                <td style="vertical-align:top;width: 20px;text-align: center;">:</td>
                                                                <td style="padding:0px 20px 20px 0px;vertical-align: top;">
                                                                    <span>{{$reservation_record_rt['PickupCity']['v_county'] ? $reservation_record_rt['PickupCity']['v_county'] : '-' }}</span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td style="width:2%;background-color:#FFF;"></td>
                                                    <td style="width:48%;vertical-align: top;">
                                                        <table style="background-color:#f0f0f9;">
                                                            <tr>
                                                                <td colspan="3" style="padding:20px;vertical-align: top;">
                                                                    <strong>Drop Off Location</strong>
                                                                    <p class="main-traveler--subtitle d-block mb-20" style="margin-top:10px"> {{ $reservation_record_rt['DropOffCity']['v_city'].' ('.$reservation_record_rt['DropOffCity']['v_county'].')' }}</p> 
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding:0px 0px 12px 20px;vertical-align: top;">
                                                                    <strong>Address</strong>
                                                                </td>
                                                                <td style="vertical-align:top;width: 20px;text-align: center;">:</td>
                                                                <td style="padding:0px 20px 12px 0px;vertical-align: top;">
                                                                    <span>{{ $reservation_record_rt['v_dropoff_address']  ? $reservation_record_rt['v_dropoff_address'] :  '-'}}</span>
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
                                                                    <strong>Country</strong>
                                                                </td>
                                                                <td style="vertical-align:top;width: 20px;text-align: center;">:</td>
                                                                <td style="padding:0px 20px 20px 0px;vertical-align: top;">
                                                                    <span>{{$reservation_record_rt['DropOffCity']['v_county'] ? $reservation_record_rt['DropOffCity']['v_county'] : '-' }}</span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        
                                    </tr>
                                    
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
                                   
                                
                                </table>
                                @endif
                                <table style="border-collapse: collapse;width:100%;border: 1px solid #ccc; background-color:#fff; margin:0 0 20px 0;page-break-inside: avoid;">
                                    <tr style="background-color:#f0f0f9;">
                                        <td colspan="4" style="padding:1rem 20px; text-transform:uppercase;"> <strong>Breakdown of Charges</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="padding:20px 0 20px 25px;">
                                            <strong> Fares </strong>
                                        </td>
                                        <td style="padding:20px 25px 20px 0px;">
                                                <?php if(!empty($total_fare_amount_rt) && ($total_fare_amount_rt['total'])){  
                                                        $total_rt_fare_amount = $total_fare_amount_rt['total']; 
                                                        $total_fare_amounts_ow = $total_fare_amount['total'] ? $total_fare_amount['total'] : 0;
                                                    }else {
                                                        $total_rt_fare_amount = 0.00;

                                                        $total_fare_amounts_ow = $total_fare_amount['total'] ? $total_fare_amount['total'] : 0;
                                                    }
                                                        $total_fare_amounts_ow_rt = $total_rt_fare_amount + $total_fare_amounts_ow;
                                                    ?>
                                            <span style="font-weight:bold;font-size: 0.775rem;">:&nbsp;&nbsp; ${{ $total_fare_amounts_ow_rt ? number_format((float)$total_fare_amounts_ow_rt, 2, '.', '') : '0.00'}} </span>
                                        </td>
                                        @if($reservation_record['e_class_type'] =="RT")
                                            @if($reservation_luggage_info_total_rt !='' && $reservation_luggage_info_total !='')
                                                <?php $other_total = ($reservation_luggage_info_total + $reservation_luggage_info_total_rt); ?>
                                                <td style="padding:20px 0px 20px 0;">
                                                    <strong> Other Charges </strong>
                                                </td>
                                                <td style="padding:20px 25px 20px 0;">
                                                    <span style="font-weight:bold;font-size: 0.775rem;">:&nbsp;&nbsp; ${{ $other_total ? number_format((float)$other_total, 2, '.', '') : '0.00' }} </span>
                                                </td>
                                            
                                            @else
                                            <td style="padding:20px 0px 20px 0;">
                                                <strong> Other Charges </strong>
                                            </td>
                                            <td style="padding:20px 25px 20px 0;">
                                                <span style="font-weight:bold;font-size: 0.775rem;">:&nbsp;&nbsp;$0.00</span>
                                            </td>
                                            @endif
                                        @else
                                            <td style="padding:20px 0px 20px 0;">
                                                <strong> Other Charges </strong>
                                            </td>
                                            <td style="padding:20px 25px 20px 0;">
                                                <span style="font-weight:bold;font-size: 0.775rem;">:&nbsp;&nbsp; ${{ $reservation_luggage_info_total ? number_format((float)$reservation_luggage_info_total, 2, '.', '') : '0.00' }}</span>
                                            </td>
                                                
                                        @endif
                                        
                                    </tr>
                                    <tr>
                                        <td style="padding:0px 0 20px 25px;">
                                            <strong> Mode of Payment </strong>
                                        </td>
                                        <td style="padding:0px 25px 20px 0px;">
                                            <span style="font-weight:bold;font-size: 0.775rem;">:&nbsp;&nbsp;  {{ ($payment_mode) ? $payment_mode : '-' }} </span>
                                        </td>
                                        
                                        <td style="padding:0px 0px 20px 0;">
                                            <strong> Payment Status </strong>
                                        </td>
                                        <td style="padding:0px 25px 20px 0;">
                                            <span style="font-weight:bold;font-size: 0.775rem;">:&nbsp;&nbsp;  Paid </span>
                                        </td>
                                    </tr>
                                </table>
                                @if(!empty($total_fare_amount['adult_count'] || $total_fare_amount['child_count'] || $total_fare_amount['infant_count'] || $total_fare_amount['military_count'] || $total_fare_amount['senior_count']))
                                    <table style="border-collapse: collapse;width:100%;border: 1px solid #ccc; background-color:#fff; margin:0 0 20px 0;page-break-inside: avoid;">
                                        <thead>
                                            <tr style="border-bottom: 1px solid #ccc;">
                                                <th style="text-align: left;background-color: #f0f0f9;border: 1px solid #ccc;text-transform: uppercase;padding: 10px;">fare details</th>
                                                <th align="right" style="background-color: #f0f0f9;border: 1px solid #ccc;text-transform: uppercase;padding: 10px;">1st leg </th>
                                                <th align="right" style="background-color: #f0f0f9;border: 1px solid #ccc;text-transform: uppercase;padding: 10px; ">2nd leg</th>
                                                <th align="right" style="background-color: #f0f0f9;border: 1px solid #ccc;text-transform: uppercase;padding: 10px; ">total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($total_fare_amount['adult_total'] > 0 || (isset($total_fare_amount_rt['adult_total']) && $total_fare_amount_rt['adult_total'] > 0 ))
                                            <tr style="border-bottom: 1px solid #ccc;">
                                                <td style="padding: 10px; border-right:1px solid #ccc;">Adult Fare&nbsp;({{  $total_fare_amount['adult_count'] + ((isset($total_fare_amount_rt['adult_count'])) ? $total_fare_amount_rt['adult_count'] : 0) }})</td>
                                                <td align="right" style="padding: 10px;border-right:1px solid #ccc;"><span>${{$total_fare_amount['adult_total'] ? number_format((float)$total_fare_amount['adult_total'], 2, '.', '') : 0 }}</span></td>
                                                <?php if(!empty($total_fare_amount_rt) && ($total_fare_amount_rt['adult_total'])){  $total_amount_rt = $total_fare_amount_rt['adult_total']; } else{
                                                    $total_amount_rt = 0.00;
                                                } 
                                                $total_adult_fare_details = $total_fare_amount['adult_total'] +  $total_amount_rt

                                                
                                                ?>
                                                <td align="right" style="padding: 10px;border-right:1px solid #ccc;"><span>${{ number_format((float)$total_amount_rt, 2, '.', '') }}</span></td>
                                                <td align="right" style="padding: 10px;"><span>${{  number_format((float)$total_adult_fare_details, 2, '.', '')}}</span></td>
                                            </tr>
                                            @endif
                                            @if($total_fare_amount['child_total'] > 0 || (isset($total_fare_amount_rt['child_total']) && $total_fare_amount_rt['child_total'] > 0 ))
                                            <tr style="border-bottom: 1px solid #ccc;">
                                                <td style="padding: 10px; text-align:left;border-right:1px solid #ccc;">Child Fare&nbsp;({{  $total_fare_amount['child_count'] + ((isset($total_fare_amount_rt['child_count'])) ? $total_fare_amount_rt['child_count'] : 0) }})</td>
                                                <td align="right" style="padding: 10px;border-right:1px solid #ccc;"><span>${{$total_fare_amount['child_total'] ? number_format((float)$total_fare_amount['child_total'], 2, '.', '') : 0 }}</span></td>
                                                <?php if(!empty($total_fare_amount_rt) && ($total_fare_amount_rt['child_total'])){  $total_child_rt = $total_fare_amount_rt['child_total']; } else{
                                                    $total_child_rt = 0.00;
                                                }
                                                $total_child_fare_details = $total_fare_amount['child_total'] +  $total_child_rt 

                                                ?>
                                                <td align="right" style="padding: 10px;border-right:1px solid #ccc;"><span>${{ number_format((float)$total_child_rt, 2, '.', '') }}</span></td>
                                                <td align="right" style="padding: 10px;center;"><span>${{ number_format((float)$total_child_fare_details, 2, '.', '')}}</span></td>
                                            </tr>
                                            @endif
                                            
                                            @if($total_fare_amount['infant_count'] > 0 || (isset($total_fare_amount_rt['infant_count']) && $total_fare_amount_rt['infant_count'] > 0 ))
                                            <tr style="border-bottom: 1px solid #ccc;">
                                                <td style="padding: 10px; text-align:left;border-right:1px solid #ccc;">Infant Fare&nbsp;({{  $total_fare_amount['infant_count'] + ((isset($total_fare_amount_rt['infant_count'])) ? $total_fare_amount_rt['infant_count'] : 0) }})</td>
                                                <td align="right" style="padding: 10px;border-right:1px solid #ccc;"><span>${{$total_fare_amount['infant_total'] ? number_format((float)$total_fare_amount['infant_total'], 2, '.', '') : 0 }}</span></td>
                                                <?php if(!empty($total_fare_amount_rt) && ($total_fare_amount_rt['infant_total'])){  $total_infant_rt = $total_fare_amount_rt['infant_total']; } else{
                                                    $total_infant_rt = 0.00;
                                                } 
                                                $total_infant_fare_details =  $total_fare_amount['infant_total'] +  $total_infant_rt
                                                ?>
                                                <td align="right" style="padding: 10px;border-right:1px solid #ccc;"><span>${{ number_format((float)$total_infant_rt, 2, '.', '') }}</span></td>
                                                <td align="right" style="padding: 10px;"><span>${{number_format((float)$total_infant_fare_details, 2, '.', '') }}</span></td>
                                            </tr>
                                            @endif
                                            @if($total_fare_amount['military_count'] > 0 || (isset($total_fare_amount_rt['military_count']) && $total_fare_amount_rt['military_count'] > 0 ))
                                            <tr style="border-bottom: 1px solid #ccc;">
                                                <td style="padding: 10px; text-align:left;border-right:1px solid #ccc;">Military Fare&nbsp;({{  $total_fare_amount['military_count'] + ((isset($total_fare_amount_rt['military_count'])) ? $total_fare_amount_rt['military_count'] : 0) }})</td>
                                                <td align="right" style="padding: 10px;border-right:1px solid #ccc;"><span>${{$total_fare_amount['military_total'] ? number_format((float)$total_fare_amount['military_total'], 2, '.', '') : 0 }}</span></td>
                                                <?php if(!empty($total_fare_amount_rt) && ($total_fare_amount_rt['military_total'])){  $total_military_rt = $total_fare_amount_rt['military_total']; } else{
                                                    $total_military_rt = 0.00;
                                                }
                                                $total_military_fare_details = $total_fare_amount['military_total'] +  $total_military_rt
                                                ?>
                                                <td align="right" style="padding: 10px;border-right:1px solid #ccc;"><span>${{ number_format((float)$total_military_rt, 2, '.', '') }}</span></td>
                                                <td align="right" style="padding: 10px;"><span>${{number_format((float)$total_military_fare_details, 2, '.', '') }}</span></td>
                                            </tr>
                                            @endif
                                            @if($total_fare_amount['senior_count'] > 0 || (isset($total_fare_amount_rt['senior_count']) && $total_fare_amount_rt['senior_count'] > 0 ))
                                            <tr style="border-bottom: 1px solid #ccc;">
                                                <td style="padding: 10px; text-align:left;border-right:1px solid #ccc;">Senior Fare&nbsp;({{  $total_fare_amount['senior_count'] + ((isset($total_fare_amount_rt['senior_count'])) ? $total_fare_amount_rt['senior_count'] : 0) }})</td>
                                                <td align="right" style="padding: 10px;border-right:1px solid #ccc;"><span>${{$total_fare_amount['senior_total'] ? number_format((float)$total_fare_amount['senior_total'], 2, '.', '') : 0 }}</span></td>
                                                <?php if(!empty($total_fare_amount_rt) && ($total_fare_amount_rt['senior_total'])){  $total_senior_rt = $total_fare_amount_rt['senior_total']; } else{
                                                    $total_senior_rt = 0.00;
                                                } 
                                                $total_senior_fare_details = $total_fare_amount['senior_total'] +  $total_senior_rt
                                                ?>
                                                <td align="right" style="padding: 10px;border-right:1px solid #ccc;"><span>${{ number_format((float)$total_senior_rt, 2, '.', '') }}</span></td>
                                                <td align="right" style="padding: 10px;"><span>${{number_format((float)$total_senior_fare_details, 2, '.', '') }}</span></td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                @endif
                            
                                @if((count($reservation_luggage_info) > 0) || (count($reservation_pet_info) > 0))
                                    <table style="border-collapse: collapse;width:100%;border: 1px solid #ccc; background-color:#fff;  margin:0 0 20px 0;page-break-inside: avoid;">
                                        <thead>
                                            <tr style="border-bottom: 1px solid #ccc;">
                                                <th style="text-align: left;background-color: #f0f0f9;border: 1px solid #ccc;text-transform: uppercase;padding: 10px;">1ST LEG OF LUGGAGE CHARGE DETAILS</th>
                                                <th align="right" style="background-color: #f0f0f9;border: 1px solid #ccc;text-transform: uppercase;padding: 10px;">CHARGE</th>
                                                <th align="right" style="background-color: #f0f0f9;border: 1px solid #ccc;text-transform: uppercase;padding: 10px;">TOTAL FARE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($reservation_luggage_info as $records)
                                    
                                    
                                                @if(count($records['system_luggage_def']) > 0)
                                                    <tr style="border-bottom: 1px solid #ccc;">
                                                        <td style="padding: 10px; border-right:1px solid #ccc;">{{ $records['system_luggage_def'][0]['v_name'] }}&nbsp;({{ $records['i_value'] }})</td>
                                                        <td align="right" style="padding: 10px;border-right:1px solid #ccc;"><span>${{ $records['system_luggage_def'][0]['d_unit_price'] }}</span></td>
                                                        <td align="right" style="padding: 10px;border-right:1px solid #ccc;"><span>${{ $records['d_price'] }}</span></td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            @foreach($reservation_pet_info as $records)
                                                @if(count($records['system_animal_def']) > 0)
                                                <tr style="border-bottom: 1px solid #ccc;">
                                                    <td style="padding: 10px; border-right:1px solid #ccc;">{{ $records['system_animal_def'][0]['v_name'] }}&nbsp;(1)</td>
                                                    <td align="right" style="padding: 10px;border-right:1px solid #ccc;"><span>${{ $records['system_animal_def'][0]['d_unit_price'] }}</span></td>
                                                    <td align="right" style="padding: 10px;border-right:1px solid #ccc;"><span>${{ $records['system_animal_def'][0]['d_unit_price'] }}</span></td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        
                                        </tbody>
                                    </table>
                                @endif

                                @if(isset($reservation_luggage_info_rt))
                                    @if((count($reservation_luggage_info_rt) > 0) || (count($reservation_pet_info_rt) > 0))
                                    <table style="border-collapse: collapse;width:100%;border: 1px solid #ccc; background-color:#fff;  margin:0 0 20px 0;page-break-inside: avoid;">
                                        <thead>
                                            <tr style="border-bottom: 1px solid #ccc;">
                                                <th style="text-align:left;background-color: #f0f0f9;border: 1px solid #ccc;text-transform: uppercase;padding: 10px;">2ND LEG OF LUGGAGE CHARGE DETAILS</th>
                                                <th align="right" style="background-color: #f0f0f9;border: 1px solid #ccc;text-transform: uppercase;padding: 10px;">CHARGE</th>
                                                <th align="right" style="background-color: #f0f0f9;border: 1px solid #ccc;text-transform: uppercase;padding: 10px;">TOTAL FARE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($reservation_luggage_info_rt as $records_rt)
                                    
                                    
                                                @if(count($records_rt['system_luggage_def']) > 0)
                                                    <tr style="border-bottom: 1px solid #ccc;">
                                                        <td style="padding: 10px; border-right:1px solid #ccc;">{{ $records_rt['system_luggage_def'][0]['v_name'] }}&nbsp;({{ $records_rt['i_value'] }})</td>
                                                        <td align="right" style="padding: 10px;border-right:1px solid #ccc;"><span>${{ $records_rt['system_luggage_def'][0]['d_unit_price'] }}</span></td>
                                                        <td align="right" style="padding: 10px;border-right:1px solid #ccc;"><span>${{ $records_rt['d_price'] }}</span></td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            @foreach($reservation_pet_info_rt as $records_rt)
                                                @if(count($records_rt['system_animal_def']) > 0)
                                                <tr style="border-bottom: 1px solid #ccc;">
                                                    <td style="padding: 10px; border-right:1px solid #ccc;">{{ $records_rt['system_animal_def'][0]['v_name'] }}&nbsp;(1)</td>
                                                    <td align="right" style="padding: 10px;border-right:1px solid #ccc;"><span>${{ $records_rt['system_animal_def'][0]['d_unit_price'] }}</span></td>
                                                    <td align="right" style="padding: 10px;border-right:1px solid #ccc;"><span>${{ $records_rt['system_animal_def'][0]['d_unit_price'] }}</span></td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        
                                        </tbody>
                                    </table>
                                    @endif
                                @endif
                    
                                <table style="border-collapse: collapse;width:100%;page-break-inside: avoid;">
                                    <tr>
                                        <td>*Must travel with Full Fare Adult </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            **Must tell us in advance
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
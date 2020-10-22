@extends('frontend.layouts.default')
@section('content')
<style>

    table tr th, table tr td {
        padding-top: 10px !important;
        padding-right: 10px !important;
        padding-bottom:10px !important;
        padding-left:0px !important;
    }
    table tr td .lag-of-travel{
        padding-bottom: 20px !important;
    }
    </style>
    <section class="about-section mt-5 mb-5">
        <div class="container">
        
            <div class="kt-portlet">
                <div class="profile-quick-links">
                    <ul>
                        <li><a href="{{FRONTEND_URL.'my-profile'}}">My Profile</a></li>
                        <li> <a href="{{FRONTEND_URL}}my-address" class="customnavbar">Addresses</a></li>
                        <li class="active"><a href="#">Reservation <i class="icon icon-down-arrow"></i></a>
                            <ul>
                                <li><a href="{{FRONTEND_URL.'upcoming-reservation'}}" style="color:#262626;">Upcoming Reservation</a></li>
                                <li><a href="{{FRONTEND_URL.'past-reservation'}}"  style="color:#262626;">Past Reservation</a></li>
                            </ul>
                        </li>
                        <li><a href="{{FRONTEND_URL}}my-card-information">Card information</a></li>
                    </ul>
                </div>
                <div class="printPart" id="printPart">
                    <div class="kt-portlet__head mt-4" >
                        
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-wrapper">
                                <div class="align-items-center d-flex flex-column flex-sm-row justify-content-between profile-address-bar pb-4">
                            
                                    <p class="m-0">Reservation No.: {{ $record['v_reservation_number']}}</p>

                                    <div class="align-items-center" id="search-block">
                                        <a href="{{SITE_URL.'reservation-detail-download/'.$record['id']}}" class="btn  btn-secondary btn-icon-sm">Download</a>
                                        <a href="{{ url()->previous() }}" class="btn  btn-secondary btn-icon-sm">
                                            Back To Listing
                                        </a>
                                        <button class="btn btn-secondary btn-icon-sm" id="printPage">Print</button>
                                        
                                    </div>
                                
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                    <table>
                        <tr>
                            <th style="text-align:left">MAIN TRAVELER</th>
                            <th style="text-align:left">TOTAL TRAVELERS: {{ $record['i_total_num_passengers'] }}</th>
                            <th style="text-align:left">FARE AND PAYMENT INFO</th>
                            

                        </tr>
                        <tr>
                            <td style="font-size: 14px;"> {{ $record['customers']['v_firstname'].' '.$record['customers']['v_lastname'] }}</td>
                            <td style="font-size: 14px;"></td>
                            <td style="font-size: 14px;"><strong style="font-size: 14px;">Mode of Payment:</strong>  Test record</td>
                        
                        
                        </tr>
                        <tr>
                            <td style="font-size: 14px;">  {{ $record['v_contact_email'] }}</td>
                            <td style="font-size: 14px;"></td>
                            <td style="font-size: 14px;"><strong style="font-size: 14px;">Payment Status:</strong>  Test record</td>
                            
                        </tr>
                        <tr>
                            <td style="font-size: 14px;">{{ $record['v_contact_phone_number'] }}</td>
                            <td style="font-size: 14px;"></td>
                            <td style="font-size: 14px;"><strong style="font-size: 14px;">Total Fare Amount:</strong>  {{ $record['d_total_fare'] ? '$'.$record['d_total_fare'] : '0.00'}}</td>
                            
                        </tr>
                        <tr>
                        @if($record['e_class_type'] == 'OW')
                            <td colspan="2" style="vertical-align:top;padding:0;">
                                <table>
                                    <tr>
                                        <th colspan="2" style="text-align:left" class="lag-of-travel"><strong style="font-size: 14px;">BOOKED TRIP:</strong> <?php if($record['e_class_type'] == 'RT') { echo "Round Trip"; } else { echo "One Way"; } ?></th>

                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">Leg of Travel:</strong></td>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;"> {{ date(DATE_FORMAT,strtotime($record['d_travel_date'])) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong style="font-size: 14px;">{{ $record['geo_origin_service_area']['geo_cities']['v_city'].' ('.$record['geo_origin_service_area']['geo_cities']['v_county'].') to '.$record['geo_dest_service_area']['geo_cities']['v_city'].' ('.$record['geo_dest_service_area']['geo_cities']['v_county'].')' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">{{ 'To arrive at '.$record['geo_dest_service_area']['geo_cities']['v_city'].' between'}}</strong></td><td style="font-size: 14px;">14:00 (2:00pm) and 16:15 (4:15pm)</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">Pickup location: </strong> {{ $record['geo_origin_service_area']['geo_cities']['v_city'].' ('.$record['geo_origin_service_area']['geo_cities']['v_county'].')' }}</td>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">Drop-off location: </strong> {{ $record['geo_dest_service_area']['geo_cities']['v_city'].' ('.$record['geo_dest_service_area']['geo_cities']['v_county'].')' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">Address: </strong> {{ $record['geo_origin_service_area']['v_street1'] }}</td>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">Address: </strong> {{ $record['geo_dest_service_area']['v_street1'] }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">City: </strong> {{ $record['geo_origin_service_area']['geo_cities']['v_city'] }}</td>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">City: </strong> {{ $record['geo_dest_service_area']['geo_cities']['v_city'] }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">County: </strong>  {{ $record['geo_origin_service_area']['geo_cities']['v_county'] }}</td>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">County: </strong> {{ $record['geo_dest_service_area']['geo_cities']['v_county'] }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px;" class="lag-of-travel"><strong style="font-size: 14px;">Zipcode: </strong> {{ $record['geo_origin_service_area']['v_postal_code'] }}</td>
                                        <td style="font-size: 14px;" class="lag-of-travel"><strong style="font-size: 14px;">Zipcode: </strong> {{ $record['geo_dest_service_area']['v_postal_code'] }}</td>
                                    </tr>
                                    
                                    

                                    
                                </table>
                            </td>
                        @else
                            <td colspan="2" style="vertical-align:top;padding:0;">
                                <table>
                                    <tr>
                                        <th colspan="2" style="text-align:left" class="lag-of-travel"><strong style="font-size: 14px;">BOOKED TRIP:</strong> <?php if($record['e_class_type'] == 'RT') { echo "Round Trip"; } else { echo "One Way"; } ?></th>

                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px;" class="lag-of-travel"><strong style="font-size: 14px;">1<sup>st</sup>Leg of Travel:</strong></td>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;"> {{ date(DATE_FORMAT,strtotime($record['d_travel_date'])) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong style="font-size: 14px;">{{ $record['geo_origin_service_area']['geo_cities']['v_city'].' ('.$record['geo_origin_service_area']['geo_cities']['v_county'].') to '.$record['geo_dest_service_area']['geo_cities']['v_city'].' ('.$record['geo_dest_service_area']['geo_cities']['v_county'].')' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">{{ 'To arrive at '.$record['geo_dest_service_area']['geo_cities']['v_city'].' between'}}</strong></td><td style="font-size: 14px;">14:00 (2:00pm) and 16:15 (4:15pm)</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">Pickup location: </strong> {{ $record['geo_origin_service_area']['geo_cities']['v_city'].' ('.$record['geo_origin_service_area']['geo_cities']['v_county'].')' }}</td>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">Drop-off location: </strong> {{ $record['geo_dest_service_area']['geo_cities']['v_city'].' ('.$record['geo_dest_service_area']['geo_cities']['v_county'].')' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">Address: </strong> {{ $record['geo_origin_service_area']['v_street1'] }}</td>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">Address: </strong> {{ $record['geo_dest_service_area']['v_street1'] }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">City: </strong> {{ $record['geo_origin_service_area']['geo_cities']['v_city'] }}</td>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">City: </strong> {{ $record['geo_dest_service_area']['geo_cities']['v_city'] }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">County: </strong>  {{ $record['geo_origin_service_area']['geo_cities']['v_county'] }}</td>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">County: </strong> {{ $record['geo_dest_service_area']['geo_cities']['v_county'] }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px;" class="lag-of-travel"><strong style="font-size: 14px;">Zipcode: </strong> {{ $record['geo_origin_service_area']['v_postal_code'] }}</td>
                                        <td style="font-size: 14px;" class="lag-of-travel"><strong style="font-size: 14px;">Zipcode: </strong> {{ $record['geo_dest_service_area']['v_postal_code'] }}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td style="padding-top: 20px"><strong style="font-size: 14px;">2<sup>nd</sup>Leg of Travel:</strong></td>
                                        <td style="padding-top: 20px"><strong style="font-size: 14px;"> {{ date(DATE_FORMAT,strtotime($record['d_travel_date'])) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong style="font-size: 14px;">{{ $record['geo_origin_service_area']['geo_cities']['v_city'].' ('.$record['geo_origin_service_area']['geo_cities']['v_county'].') to '.$record['geo_dest_service_area']['geo_cities']['v_city'].' ('.$record['geo_dest_service_area']['geo_cities']['v_county'].')' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">{{ 'To arrive at '.$record['geo_dest_service_area']['geo_cities']['v_city'].' between'}}</strong></td><td style="font-size: 14px;">14:00 (2:00pm) and 16:15 (4:15pm)</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">Pickup location: </strong> {{ $record['geo_dest_service_area']['geo_cities']['v_city'].' ('.$record['geo_dest_service_area']['geo_cities']['v_county'].')' }}</td>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">Drop-off location: </strong> {{ $record['geo_origin_service_area']['geo_cities']['v_city'].' ('.$record['geo_origin_service_area']['geo_cities']['v_county'].')' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">Address: </strong>{{ $record['geo_dest_service_area']['v_street1'] }}</td>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">Address: </strong> {{ $record['geo_origin_service_area']['v_street1'] }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">City: </strong> {{ $record['geo_dest_service_area']['geo_cities']['v_city'] }}</td>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">City: </strong> {{ $record['geo_origin_service_area']['geo_cities']['v_city'] }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">County: </strong> {{ $record['geo_dest_service_area']['geo_cities']['v_county'] }}</td>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">County: </strong> {{ $record['geo_origin_service_area']['geo_cities']['v_county'] }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px;" class="lag-of-travel"><strong style="font-size: 14px;">Zipcode: </strong> {{ $record['geo_dest_service_area']['v_postal_code'] }}</td>
                                        <td style="font-size: 14px;" class="lag-of-travel"><strong style="font-size: 14px;">Zipcode: </strong> {{ $record['geo_origin_service_area']['v_postal_code'] }}</td>
                                    </tr>
                                   

                                    
                                </table>
                            </td>
                        @endif
                        
                            <td colspan="2" style="vertical-align:top;padding:0;">
                                <table>
                                    <tr>
                                        <th colspan="2" style="text-align:left">BREAKDOWN OF CHARGES:</th>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">Fares: </strong>  <?php $total_fare_amounts = number_format((float)$total_fare_amount, 2, '.', '')  ?>{{ $total_fare_amounts ? '$'.$total_fare_amounts: '' }}</td>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">Mode of Payment:</strong>  {{ 'Test record'}}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">Other Charges: </strong>  <?php $total_luggage_amounts = number_format((float)$total_luggage_amount, 2, '.', '')  ?> {{ $total_luggage_amounts ? '$'.$total_luggage_amounts : '' }}</td>
                                        <td style="font-size: 14px;"><strong style="font-size: 14px;">Payment Status:</strong>  {{ 'Test record'}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong style="font-size: 14px;">Total Fare: </strong> <?php $total_fare = number_format((float)$total_fare_amount + $total_luggage_amount, 2, '.', '') ?>{{ $total_fare ? '$'.$total_fare : ''  }}</td>
                                    </tr>
                                    <tr>
                                        <table>
                                            <tr>
                                                <th style="text-align:left"><strong style="font-size: 14px;">FARE DETAILS:</strong></th> 
                                                @if($record['e_class_type'] == 'RT')
                                                    <th style="text-align:left"><strong style="font-size: 14px;">1ST LEG:</strong></th>
                                                    <th style="text-align:left"><strong style="font-size: 14px;">2ND LEG:</strong></th>
                                                    <th style="text-align:left"><strong style="font-size: 14px;">TOTAL:</strong></th>
                                                @else
                                                <th style="text-align:left"><strong style="font-size: 14px;">TOTAL:</strong></th>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td style="font-size: 14px;"><strong style="font-size: 14px;">Adult Fare: ({{ $adult_cnt_rt + $adult_cnt }})</strong></td>
                                                @if($record['e_class_type'] == 'RT')
                                                    <td style="font-size: 14px;"><strong style="font-size: 14px;"></strong>{{ $passanger_amount_adult }}</td>
                                                    <td style="font-size: 14px;"><strong style="font-size: 14px;"></strong>{{ $passanger_amount_adult_rt }}</td>
                                                    <td style="font-size: 14px;"><strong style="font-size: 14px;"></strong>{{ $passanger_amount_adult + $passanger_amount_adult_rt }}</td>
                                                @else
                                                    <td style="font-size: 14px;"><strong style="font-size: 14px;"></strong>{{ $passanger_amount_adult }}</td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td style="font-size: 14px;"><strong style="font-size: 14px;">Companion Details: ({{ $companion_cnt_rt + $companion_cnt }})</strong></td>
                                                @if($record['e_class_type'] == 'RT')
                                                
                                                    <td style="font-size: 14px;"><strong style="font-size: 14px;"></strong>{{ $passanger_amount_Companion }}</td>
                                                    <td style="font-size: 14px;"><strong style="font-size: 14px;"></strong>{{ $passanger_amount_Companion_rt }}</td>
                                                    <td style="font-size: 14px;"><strong style="font-size: 14px;"></strong>{{ $passanger_amount_Companion + $passanger_amount_Companion_rt }}</td>
                                                @else
                                            
                                                    <td style="font-size: 14px;"><strong style="font-size: 14px;"></strong>{{ $passanger_amount_Companion }}</td>
                                            
                                                @endif
                                            </tr>
                                        
                                            <tr>
                                                <td style="font-size: 14px;"><strong style="font-size: 14px;">Child Fare: ({{ $child_cnt_rt + $child_cnt }})</strong></td>
                                                @if($record['e_class_type'] == 'RT')
                                                
                                                    <td style="font-size: 14px;"><strong style="font-size: 14px;"></strong>{{ $passanger_amount_Child }}</td>
                                                    <td style="font-size: 14px;"><strong style="font-size: 14px;"></strong>{{ $passanger_amount_Child_rt }}</td>
                                                    <td style="font-size: 14px;"><strong style="font-size: 14px;"></strong>{{ $passanger_amount_Child + $passanger_amount_Child_rt }}</td>
                                                @else
                                            
                                                    <td style="font-size: 14px;"><strong style="font-size: 14px;"></strong>{{ $passanger_amount_Child }}</td>
                                            
                                                @endif
                                            </tr>
                                            <tr>
                                                <td style="font-size: 14px;"><strong style="font-size: 14px;">Infant Details: ({{ $infant_cnt_rt + $infant_cnt }})</strong></td>
                                                @if($record['e_class_type'] == 'RT')
                                                
                                                    <td style="font-size: 14px;"><strong style="font-size: 14px;"></strong>{{ $passanger_amount_Infant }}</td>
                                                    <td style="font-size: 14px;"><strong style="font-size: 14px;"></strong>{{ $passanger_amount_Infant_rt }}</td>
                                                    <td style="font-size: 14px;"><strong style="font-size: 14px;"></strong>{{ $passanger_amount_Infant + $passanger_amount_Infant_rt }}</td>
                                                @else
                                            
                                                    <td style="font-size: 14px;"><strong style="font-size: 14px;"></strong>{{ $passanger_amount_Infant }}</td>
                                            
                                                @endif
                                            </tr>
                                            <tr>
                                                <td style="font-size: 14px;"><strong style="font-size: 14px;">Military Fare: ({{ $military_cnt_rt + $military_cnt }})</strong></td>
                                                @if($record['e_class_type'] == 'RT')
                                                
                                                    <td style="font-size: 14px;"><strong style="font-size: 14px;"></strong>{{ $passanger_amount_Military }}</td>
                                                    <td style="font-size: 14px;"><strong style="font-size: 14px;"></strong>{{ $passanger_amount_Military_rt }}</td>
                                                    <td style="font-size: 14px;"><strong style="font-size: 14px;"></strong>{{ $passanger_amount_Military + $passanger_amount_Military_rt }}</td>
                                                @else
                                            
                                                    <td style="font-size: 14px;"><strong style="font-size: 14px;"></strong>{{ $passanger_amount_Military }}</td>
                                            
                                                @endif
                                            </tr>
                                            
                                            <tr>
                                                <td style="font-size: 14px;"><strong style="font-size: 14px;">Senior Fare: ({{ $senior_cnt_rt + $senior_cnt_rt }})</strong></td>
                                                @if($record['e_class_type'] == 'RT')
                                                
                                                    <td style="font-size: 14px;"><strong style="font-size: 14px;"></strong>{{ $passanger_amount_Senior }}</td>
                                                    <td style="font-size: 14px;"><strong style="font-size: 14px;"></strong>{{ $passanger_amount_Senior_rt }}</td>
                                                    <td style="font-size: 14px;"><strong style="font-size: 14px;"></strong>{{ $passanger_amount_Senior + $passanger_amount_Senior_rt }}</td>
                                                @else
                                            
                                                    <td style="font-size: 14px;"><strong style="font-size: 14px;"></strong>{{ $passanger_amount_Senior }}</td>
                                            
                                                @endif
                                            </tr>
                                            <tr>
                                                <td colspan="4"> <strong style="font-size: 14px;">Luggage Charge Details:</strong>
                                            </tr>
                                            @if(count($reservation_luggage_info) > 0)
                                                <tr>
                                                    <td colspan="2"> <strong style="font-size: 14px;">LUGGAGE TYPE.</strong>
                                                    <td style="font-size: 14px;"> <strong style="font-size: 14px;">Charge</strong>
                                                    <td style="font-size: 14px;"> <strong style="font-size: 14px;">Total Fare</strong>
                                                </tr>
                                                @foreach($reservation_luggage_info as $records)
                                                <tr>
                                                
                                                    @if(count($records['system_luggage_def']) > 0)
                                                        <td colspan="2"><strong style="font-size: 14px;">{{ $records['system_luggage_def'][0]['v_name'] }}</strong></td>
                                                        <td style="font-size: 14px;">{{ $records['system_luggage_def'][0]['d_unit_price'] }}</td>
                                                        <td style="font-size: 14px;">{{ $records['d_price'] }}</td>
                                                    @endif
                                                
                                                </tr>
                                                @endforeach

                                            @endif
                                            @if(count($reservation_pet_info) > 0)
                                            
                                                @foreach($reservation_pet_info as $records)
                                                    @if(count($records['system_animal_def']) > 0)
                                                    <tr>
                                                        <td colspan="2"><strong style="font-size: 14px;">{{ $records['system_animal_def'][0]['v_name'] }} </strong></td>
                                                        <td style="font-size: 14px;"> {{ $records['system_animal_def'][0]['d_unit_price'] }}</td>
                                                        <td style="font-size: 14px;">  {{ $records['system_animal_def'][0]['d_unit_price'] }}</td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                            <tr>
                                                <td colspan="4">*must travel with Full Fare Adult</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">**must tell us in advance</td>
                                            </tr>

                                        </table>
                                    </tr>
                                
                                </table>
                            </td>
                            
                        </tr>
                        
                    
                    </table>
                </div>
                </div>


                <!--End::Row-->
                <!--End::Dashboard 1-->
            </div>
           
           
        </div>
    </section>


@section('custom_js')

<script>
    $(document).ready(function () { 
        function printData()
        {
         
            var divToPrint=document.getElementById("printPart");
            $(divToPrint).find('#search-block').hide();
                        newWin= window.open("");
                        newWin.document.write(divToPrint.outerHTML);
                        
                        newWin.print();
                        newWin.close();
            $(divToPrint).find('#search-block').show();
            
        }

        $('#printPage').on('click',function(){
            printData();
        })
    });
</script>
@stop
@stop
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <style>
        table {
                font-family: arial, sans-serif;
                width: 100%;
                border-collapse: collapse;
                font-size: 13px;
                margin-left:-5px;
                border-spacing: 0;
            }
        .table-bordered, .table-bordered td, .table-bordered th {
            border: 1px solid black !important;
        }
        .table td, .table th {
            padding: .25rem !important;
            vertical-align: middle !important;
        }
        </style>
    </head>
    <body>
        <div class="col-md-12 col-lg-12 table-responsive mb-3">
            <table class="table table-bordered" id="myTable" style="min-width: 760px;">
                @if(count($reservation_detail) == 0)
                <thead>
                    <tr>
                        <th style="text-align: center"><strong>No reservation found.</strong></th>
                    </tr>
                </thead>
                @else                               
                    @foreach($reservation_detail as $key => $value)

                    
                    
                        @if($key == 0)
                            <tr>
                                <th colspan="3" style="text-align: center;"><strong>{{$value['d_travel_date'] ? date('l, F d, Y',strtotime ($value['d_travel_date'])) : '-'}}</strong></th>
                                <th colspan="4" style="text-align: center;"><strong>Westbound</strong></th>
                            </tr>
                        @endif
                        
                        <tr style="background: #fe9600;">
                            <td style="width:7%;text-align: center;"><strong>P/U #</strong></td>
                            <td style="width:8%;text-align: center;"><strong>Direction</strong></td>
                            <td colspan="5"></td>
                            
                        </tr>
                        <tr>
                            <td rowspan="11" class="align-middle" style="background: darkseagreen;text-align: center;"><strong>{{$key+1}}</strong></td>
                            <td rowspan="11" class="align-middle" style="writing-mode: tb-rl;transform:rotate(270deg);"><strong>Westbound</strong></td>
                            <td style="width:17%;background: #dcdcf3;"><strong>Address</strong></td>
                            <?php   if(isset($value['ReservAtionInfo']['v_pickup_address']) != '') { 
                                $street_origin = $value['ReservAtionInfo']['v_pickup_address'];
                            } else {
                                $street_origin = '-';
                            } ?>
                            <td style="width:20%">{{$street_origin}}</td>
                            <td style="width:18%"><strong></strong></td>
                            <td style="width:15%;background: #dcdcf3;"><strong>P/U Time</strong></td>
                            <td style="width:15%">{{ $record['pu_time_'.($key+1).''] ? $record['pu_time_'.($key+1).''] : ''}}</td>
                            
                            
                        </tr>
                        <tr>
                            
                        
                            <td style="width:17%;background: #dcdcf3;"><strong>Airline</strong></td>
                            <td style="width:20%">{{$value['ReservAtionInfo']['v_flight_name'] ? $value['ReservAtionInfo']['v_flight_name'] : '-'}}</td>
                            <td style="width:18%"><strong></strong></td>
                            <td style="width:15%;background: #dcdcf3;"><strong>P/U Mileage</strong></td>
                            <td style="width:15%">{{ $record['pu_milege_'.($key+1).''] ? $record['pu_milege_'.($key+1).''] : ''}}</td>
                            
                        </tr>
                        <tr>
                        
                            
                            <td style="width:17%;background: #dcdcf3;"><strong>Flight</strong></td>
                            <td style="width:20%">{{$value['ReservAtionInfo']['v_flight_number'] ? $value['ReservAtionInfo']['v_flight_number'] : '-'}}</td>
                            <td style="width:18%"><strong></strong></td>
                            <td style="width:15%;background: #dcdcf3;"><strong>D/O Time</strong></td>
                            <td style="width:15%">{{ $record['do_time_'.($key+1).''] ? $record['do_time_'.($key+1).''] : ''}}</td>
                            
                        </tr>
                        <tr>
                            
                            
                            <td style="width:17%;background: #dcdcf3;"><strong>Est. Arrival / Actual</strong></td>
                            <td style="width:20%">{{$value['ReservAtionInfo']['t_flight_time'] ? date(TIME_FORMAT,strtotime ($value['ReservAtionInfo']['t_flight_time'])) : '-'}}</td>
                            <td style="width:18%">{{ $record['actual_time_'.($key+1).''] ? $record['actual_time_'.($key+1).''] : ''}}</td>
                            <td style="width:15%;background: #dcdcf3;"><strong>D/O Mileage</strong></td>
                            <td style="width:15%">{{ $record['do_mileage_'.($key+1).''] ? $record['do_mileage_'.($key+1).''] : ''}}</td>
                            
                        </tr>
                        <tr>
                        
                            
                            <td style="width:17%;background: #dcdcf3;"><strong>P/U Time / Contact</strong></td>
                            <td style="width:20%"> {{$value['ReservAtionInfo']['t_comfortable_time'] ? date(TIME_FORMAT,strtotime ($value['ReservAtionInfo']['t_comfortable_time'])) : '-'}}</td>
                        
                            <td style="width:18%">{{ $record['contact_text_'.($key+1).''] ? $record['contact_text_'.($key+1).''] : ''}}</td>
                            <td style="width:15%;background: #dcdcf3;"><strong>International</strong></td>
                            <td style="width:15%">{{$value['ReservAtionInfo']['e_flight_type'] == "International" ? 'Yes' : '-'  }}</td>
                            
                        </tr>
                        <tr>
                            
                        
                            <td style="width:17%;background: #dcdcf3;"><strong>Name</strong></td>
                            <td style="width:20%">{{$value['ReservAtionInfo']['v_contact_name'] ? $value['ReservAtionInfo']['v_contact_name'] : '-'}}</td>
                            <td style="width:18%"><strong></strong></td>
                            <td style="width:15%;background: #dcdcf3;"><strong>Domestic</strong></td>
                            <td style="width:15%">{{$value['ReservAtionInfo']['e_flight_type'] == "Domestic" ? 'Yes' : '-'  }}</td>
                            
                        </tr>
                        <tr>
                            
                            
                            <td style="width:17%;background: #dcdcf3;"><strong>PAX</strong></td>
                            <td style="width:20%">{{$value['ReservAtionInfo']['i_total_num_passengers'] ? $value['ReservAtionInfo']['i_total_num_passengers'] : '0'}}</td>
                            <td style="width:18%"><strong></strong></td>
                            <td style="width:15%;background: #dcdcf3;"><strong>Res #</strong></td>
                            <td style="width:15%">{{$value['ReservAtionInfo']['v_reservation_number'] ? $value['ReservAtionInfo']['v_reservation_number'] : '-'}}</td>
                            
                        </tr>
                        <tr>
                        
                        
                            <td style="width:17%;background: #dcdcf3;"><strong>Bags</strong></td>
                            <?php $bags = ($value['ReservAtionInfo']['i_number_of_luggages'] + $value['ReservAtionInfo']['i_num_pets'] ); ?>
                            <td style="width:20%">{{ $bags ? $bags  : '-'}}</td>
                            <td style="width:18%"><strong></strong></td>
                            <td colspan="2" rowspan="4" style="width:30%"></td>
                            
                            
                        </tr>
                        <tr>
                            
                            
                            <td style="width:17%;background: #dcdcf3;"><strong>Phone</strong></td>   
                            <td style="width:20%">{{$value['ReservAtionInfo']['v_contact_phone_number'] ? $value['ReservAtionInfo']['v_contact_phone_number'] : '-'}}</td>
                            <td style="width:18%"><strong></strong></td>
                            
                        
                            
                        </tr>
                        <tr>
                            
                        
                            <td style="width:17%;background: #dcdcf3;"><strong>Destination</strong></td>
                            <?php   if(isset($value['ReservAtionInfo']['v_dropoff_address']) != '') { 
                                $street_dest = $value['ReservAtionInfo']['v_dropoff_address'];
                            } else {
                                $street_dest = '-';
                            }?>
                            <td style="width:20%">{{$street_dest}}</td>
                            <td style="width:18%"><strong></strong></td>
                            
                            
                            
                        </tr>
                        <tr>

                            <td style="width:17%;background: #dcdcf3;"><strong>Cross St</strong></td>
                            <td>{{ $record['cross_st_'.($key+1).''] ? $record['cross_st_'.($key+1).''] : ''}}</td>
                            <td></td>
                        </tr>
                        @if($key < (count($reservation_detail) - 1))
                            <tr>
                                <td colspan="7">&nbsp;</td>
                            </tr>
                        @endif
                                        
                    
                    @endforeach
                
                @endif
                
                
            </table>
        </div>
    </body>
</html>
                                
                        
                    



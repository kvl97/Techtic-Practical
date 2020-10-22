<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <style>
            span {
                color: #565656;
            }
        </style>
    </head>
    <body>
      @if($reservation_record['e_class_type'] == "RT")
        <table style="border-collapse: collapse;border: 1px solid #ccc; background-color:#fff;margin:0 0 20px 0;page-break-inside: avoid;width:100%;">
          <thead>
              <tr style="border-bottom: 1px solid #ccc;">
                  <th style="background-color: #f0f0f9;border: 1px solid #ccc;text-transform: uppercase;padding: 10px; width:15%;"></th>
                  <th style="background-color: #f0f0f9;border: 1px solid #ccc;text-transform: uppercase;padding: 10px; width:43%;">1ST LEG OF TRAVEL</th>
                  <th style="background-color: #f0f0f9;border: 1px solid #ccc;text-transform: uppercase;padding: 10px;">2ND LEG OF TRAVEL</th>
              </tr>
          </thead>
          <tbody>
            <tr style="border-bottom: 1px solid #ccc;">

              <th style="padding: 10px; border-right:1px solid #ccc;text-align: left;">Pickup Location</th>
              <td style="padding: 10px;border-right:1px solid #ccc;"><span>{{$reservation_record['PickupCity']['v_city']." - ".$reservation_record['v_pickup_address']}}</span></td>
              <td style="padding: 10px;border-right:1px solid #ccc;"><span>{{$reservation_record_rt['PickupCity']['v_city']." - ".$reservation_record_rt['v_pickup_address']}}</span></td>
              
            </tr>
                                                                                                      
            <tr style="border-bottom: 1px solid #ccc;">
              <th style="padding: 10px; border-right:1px solid #ccc;text-align: left;">Dropoff Location</th>
              <td style="padding: 10px;border-right:1px solid #ccc;"><span>{{$reservation_record['DropOffCity']['v_city']." - ".$reservation_record['v_dropoff_address']}}</span></td>
              <td style="padding: 10px;border-right:1px solid #ccc;"><span>{{$reservation_record_rt['DropOffCity']['v_city']." - ".$reservation_record_rt['v_dropoff_address']}}</span></td>
            </tr>
            <tr style="border-bottom: 1px solid #ccc;">
              <th style="padding: 10px; border-right:1px solid #ccc;text-align: left;">Date</th>
              <td style="padding: 10px;border-right:1px solid #ccc;"><span>{{date("m/d/Y",strtotime($reservation_record['d_travel_date']))}}</span></td>
              <td style="padding: 10px;border-right:1px solid #ccc;"><span>{{date("m/d/Y",strtotime($reservation_record_rt['d_travel_date']))}}</span></td>
            </tr>
            <tr style="border-bottom: 1px solid #ccc;">
              <th style="padding: 10px; border-right:1px solid #ccc;text-align: left;">Time</th>
              <td style="padding: 10px;border-right:1px solid #ccc;"><span>{{date('g:i A' , strtotime($reservation_record['t_comfortable_time']))}}</span></td>
              <td style="padding: 10px;border-right:1px solid #ccc;"><span>{{date('g:i A' , strtotime($reservation_record_rt['t_comfortable_time']))}}</span></td>
            </tr>
            <tr style="border-bottom: 1px solid #ccc;">
              <th style="padding: 10px; border-right:1px solid #ccc;text-align: left;">Trip Type</th>
              <td style="padding: 10px;border-right:1px solid #ccc;"><span>{{$reservation_record['e_shuttle_type']}}</span></td>
              <td style="padding: 10px;border-right:1px solid #ccc;"><span>{{$reservation_record_rt['e_shuttle_type']}}</span></td>
            </tr>
            <tr style="border-bottom: 1px solid #ccc;">
              <th style="padding: 10px; border-right:1px solid #ccc;text-align: left;">No. Of Passengers</th>
              <td style="padding: 10px;border-right:1px solid #ccc;"><span>{{$reservation_record['i_total_num_passengers']}}</span></td>
              <td style="padding: 10px;border-right:1px solid #ccc;"><span>{{$reservation_record_rt['i_total_num_passengers']}}</span></td>
            </tr>
                                                                                              
          </tbody>
        </table>
      @else
        <table style="border-collapse: collapse;width:50%;border: 1px solid #ccc; background-color:#fff;  margin:0 0 20px 0;page-break-inside: avoid;">
          <thead>
              <tr style="border-bottom: 1px solid #ccc;">
                  <th style="background-color: #f0f0f9;border: 1px solid #ccc;text-transform: uppercase;padding: 10px;" colspan="2">TRAVEL DETAIL</th>
              </tr>
          </thead>
          <tbody>
            <tr style="border-bottom: 1px solid #ccc;">
              <th style="padding: 10px; border-right:1px solid #ccc;text-align: left;">Pickup Location</th>
              <td style="padding: 10px;border-right:1px solid #ccc;"><span>{{$reservation_record['PickupCity']['v_city']." - ".$reservation_record['v_pickup_address']}}</span></td>
              
            </tr>
                                                                                                      
            <tr style="border-bottom: 1px solid #ccc;">
              <th style="padding: 10px; border-right:1px solid #ccc;text-align: left;">Dropoff Location</th>
              <td style="padding: 10px;border-right:1px solid #ccc;"><span>{{$reservation_record['DropOffCity']['v_city']." - ".$reservation_record['v_dropoff_address']}}</span></td>
            </tr>
            <tr style="border-bottom: 1px solid #ccc;">
              <th style="padding: 10px; border-right:1px solid #ccc;text-align: left;">Date</th>
              <td style="padding: 10px;border-right:1px solid #ccc;"><span>{{date("m/d/Y",strtotime($reservation_record['d_travel_date']))}}</span></td>
            </tr>
            <tr style="border-bottom: 1px solid #ccc;">
              <th style="padding: 10px; border-right:1px solid #ccc;text-align: left;">Time</th>
              <td style="padding: 10px;border-right:1px solid #ccc;"><span>{{date('g:i A' , strtotime($reservation_record['t_comfortable_time']))}}</span></td>
            </tr>
            <tr style="border-bottom: 1px solid #ccc;">
              <th style="padding: 10px; border-right:1px solid #ccc;text-align: left;">Trip Type</th>
              <td style="padding: 10px;border-right:1px solid #ccc;"><span>{{$reservation_record['e_shuttle_type']}}</span></td>
            </tr>
            <tr style="border-bottom: 1px solid #ccc;">
              <th style="padding: 10px; border-right:1px solid #ccc;text-align: left;">No. Of Passengers</th>
              <td style="padding: 10px;border-right:1px solid #ccc;"><span>{{$reservation_record['i_total_num_passengers']}}</span></td>
            </tr>
                                                                                              
          </tbody>
        </table>
      @endif
    </body>
</html>
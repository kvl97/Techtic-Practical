@extends('backend.layouts.default')
@section('content')
<style>
.table-bordered, .table-bordered td, .table-bordered th {
    border: 1px solid black !important;
}
.table td, .table th {
    padding: .25rem !important;
    vertical-align: middle !important;
}
</style>
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
   
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
    </div>
 
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
       
        
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-rocket"></i>
                </span>
                    <h3 class="kt-portlet__head-title">
                       {{$title}}
                    </h3>
                </div>
               
            </div>
            <div class="kt-portlet__body">
            
                <div class="tab-content">
                    
                    <form class="kt-form kt-form--label-right" id="printForm" action="" methode="POST">
                         
                    
                        <div class="row" style="margin:0px 0px 20px 0px">
                        @if($auth_user['i_role_id'] != 6)
                            <div class="col-md-4">
                                <div class="row">
                                    <label class="col-form-label col-md-3 col-lg-3 col-sm-12">Driver</label>
                                        <div class="col-md-9 col-lg-9 col-sm-12">
                                    
                                            <select class="form-control" name="i_driver_id" placeholder="Driver">
                                            <option value="">Select Driver</option>
                                            @if(count($record_driver[0]['admin']) > 0)
                                                @foreach($record_driver[0]['admin'] as $val)
                                                    <option value="{{ $val['id'] }}">{{ $val['v_firstname']." ".$val['v_lastname']." (". $val['driver_extension'][0]['v_extension'].")" }}</option>
                                                @endforeach
                                            @endif
                                        </select>                  
                                    </div>
                                </div>
                            </div>
                        
                            <div class="col-md-3 col-lg-4">
                                <div class="row">
                                    <label class="col-form-label col-md-3 col-lg-3 col-sm-12">Date</label>
                                    <div class="col-md-9 col-lg-9 col-sm-12">
                                        <input type="text" class="form-control d_run_date" name="d_travel_date" placeholder="Run Date" readonly="readonly" value="{{date('m/d/Y',strtotime($todaydate))}}">              
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 col-lg-4 mt-lg-0 mt-2 mt-md-0">
                                <div class="row">
                                    <div class="col-md-6 col-sm-3">
                                        <button type="button" class="btn btn-secondary btn-icon-sm" id="search_button" printId=""><i class="la la-search"></i>Search</button>                  
                                    </div>
                                    <div class="col-md-6 col-sm-9" style="text-align: end;">
                                        @if(isset($permission) && isset($permission[23]['i_list']) && $permission[23]['i_list'] == 1)
                                        
                                            <button type="button" class="btn btn-secondary btn-icon-sm mr-1 d-none" id="print_Page" printId=""><i class="la la-print"></i>Print</button>
                                        
                                        @endif
                                        <button type="button" id="save_manifesta" class="btn btn-brand d-none">Save</button>
                                        
                                    </div>
                                </div>
                            </div>
                        @else
                            
                        
                            <div class="col-md-4">
                                <div class="row">
                                    <label class="col-form-label col-md-3 col-lg-3 col-sm-12">Date</label>
                                        <div class="col-md-9 col-lg-9 col-sm-12">
                                    
                                        <input type="text" class="form-control d_run_date" name="d_travel_date" placeholder="Run Date" readonly="readonly" value="{{date('m/d/Y',strtotime($todaydate))}}">   
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <button type="button" class="btn btn-secondary btn-icon-sm" id="search_button" printId=""><i class="la la-search"></i>Search</button>
                            </div>
                            <div class="col-md-4">
                                <input type="hidden" class="form-control" name="i_driver_id" value="{{$auth_user['id']}}">   
                            </div>
                            <div class="col-md-2" style="text-align: end;">
                            <button type="button" class="btn btn-secondary btn-icon-sm d-none" id="print_Page" printId=""><i class="la la-print"></i>Print</button>
                            </div>
                        @endif
                            
                            
                        </div>
                           
                            
                                                       
                        <div class="col-md-12 col-lg-12 table-responsive mb-3">
                            <div id="myTableDiv">
                                <table class="table table-bordered" id="myTable" style="min-width: 768px;">
                                
                                        @if(count($reservation_detail) == 0)
                                        <thead>
                                            <tr>
                                                <th style="text-align: center"><strong>No reservation found.</strong></th>
                                            </tr>
                                        </thead>
                                        @else   
                                            <tbody>
                                            <input type="hidden" name="total_count" value="{{count($reservation_detail)}}">                              
                                            @foreach($reservation_detail as $key => $value)
                                                
                                                <?php $data = json_decode($value['v_manifest_json'], true); /*  pr($data['pu_time_1']); exit; */ ?>
                                               
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
                                                    <td style="width:15%;background: #dcdcf3;"><strong>P/U Time 
                                                        </strong></td>
                                                    <td style="width:15%"><input type="text" class="" name="pu_time_{{$key+1}}" value="<?= isset($data['pu_time_'.($value['id'])]) ? $data['pu_time_'.($value['id'])] : '' ?>" style="width: 100%;"></td>
                                                    <input type="hidden" class="" name="id_{{$key+1}}" value="{{$value['id']}}">
                                                    
                                                    
                                                </tr>
                                                <tr>
                                                    
                                                
                                                    <td style="width:17%;background: #dcdcf3;"><strong>Airline</strong></td>
                                                    <td style="width:20%">{{$value['ReservAtionInfo']['v_flight_name'] ? $value['ReservAtionInfo']['v_flight_name'] : '-'}}</td>
                                                    <td style="width:18%"><strong></strong></td>
                                                    <td style="width:15%;background: #dcdcf3;"><strong>P/U Mileage</strong></td>
                                                    <td style="width:15%"><input type="text" class="" name="pu_milege_{{$key+1}}" style="width: 100%;" value="<?= isset($data['pu_milege_'.($value['id'])]) ? $data['pu_milege_'.($value['id'])] : '' ?>"></td>
                                                    
                                                </tr>
                                                <tr>
                                                
                                                    
                                                    <td style="width:17%;background: #dcdcf3;"><strong>Flight</strong></td>
                                                    <td style="width:20%">{{$value['ReservAtionInfo']['v_flight_number'] ? $value['ReservAtionInfo']['v_flight_number'] : '-'}}</td>
                                                    <td style="width:18%"><strong></strong></td>
                                                    <td style="width:15%;background: #dcdcf3;"><strong>D/O Time</strong></td>
                                                    <td style="width:15%"><input type="text" class="" name="do_time_{{$key+1}}" value="<?= isset($data['do_time_'.($value['id'])]) ? $data['do_time_'.($value['id'])] : '' ?>" style="width: 100%;"></td>
                                                    
                                                </tr>
                                                <tr>
                                                    
                                                    
                                                    <td style="width:17%;background: #dcdcf3;"><strong>Est. Arrival / Actual</strong></td>
                                                    <td style="width:20%">{{$value['ReservAtionInfo']['t_flight_time'] ? date(TIME_FORMAT,strtotime ($value['ReservAtionInfo']['t_flight_time'])) : '-'}}</td>
                                                    <td style="width:18%"><input type="text" class="" name="actual_time_{{$key+1}}" value="<?= isset($data['actual_time_'.($value['id'])]) ? $data['actual_time_'.($value['id'])] : '' ?>" style="width: 100%;"></td>
                                                    <td style="width:15%;background: #dcdcf3;"><strong>D/O Mileage</strong></td>
                                                    <td style="width:15%"><input type="text" class="" name="do_mileage_{{$key+1}}"  value="<?= isset($data['do_mileage_'.($value['id'])]) ? $data['do_mileage_'.($value['id'])] : '' ?>" style="width: 100%;"></td>
                                                    
                                                </tr>
                                                <tr>
                                                
                                                    
                                                    <td style="width:17%;background: #dcdcf3;"><strong>P/U Time / Contact</strong></td>
                                                    <td style="width:20%"> {{$value['ReservAtionInfo']['t_comfortable_time'] ? date(TIME_FORMAT,strtotime ($value['ReservAtionInfo']['t_comfortable_time'])) : '-'}}</td>
                                                
                                                    <td style="width:18%"><input type="text" class="" name="contact_text_{{$key+1}}" value="<?= isset($data['contact_text_'.($value['id'])]) ? $data['contact_text_'.($value['id'])] : '' ?>" style="width: 100%;"></td>
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
                                                    }
                                                    $destination = $street_dest;?>
                                                    <td style="width:20%">{{$destination}}</td>
                                                    <td style="width:18%"><strong></strong></td>
                                                    
                                                    
                                                    
                                                </tr>
                                                <tr>

                                                    <td style="width:17%;background: #dcdcf3;"><strong>Cross St</strong></td>
                                                    <td><input type="text" class="" name="cross_st_{{$key+1}}"  value="<?= isset($data['cross_st_'.($value['id'])]) ? $data['cross_st_'.($value['id'])] : '' ?>" style="width: 100%;"></td>
                                                    <td></td>
                                                </tr>
                                                @if($key < (count($reservation_detail) - 1))
                                                    <tr>
                                                        <td colspan="7">&nbsp;</td>
                                                    </tr>
                                                @endif
                                                                
                                            
                                            @endforeach
                                            </tbody>
                                        
                                        @endif
                                    
                                
                                </table>
                            </div>
                            <div id="myTableSearch" class="d-none">
                            </div>                   
                        </div>
                        <div class="d-none" id="print_manifest">
                        </div>    
                        
                    </form>

                
                </div>
            </div>
        </div> 
    </div>
</div>

@stop

@section('custom_js')
  <script>
    $(document).ready(function() {
      
        rowCount = $('#myTable tbody tr').length;
        if(rowCount > 0) {
            $('#print_Page').removeClass('d-none');
            $('#save_manifesta').removeClass('d-none');
        }
        $('#print_Page').on('click',function(){
         /*    var id = $(this).attr('printId'); */
            var url = (ADMIN_URL + 'rocket-manifest/print');
            var data = $('#printForm').serialize()
            $.post(url,data,function(response) {
            
                $('#print_manifest').html(response);

                var divToPrint=document.getElementById("print_manifest");
                $(divToPrint).find('#print_manifest').show();
                newWin= window.open("");
                newWin.document.write(divToPrint.outerHTML);
                newWin.print();
                newWin.close();
                $(divToPrint).find('#print_manifest').hide();
            
            });

        });
        $('#save_manifesta').on('click',function(){
            var url = (ADMIN_URL + 'rocket-manifest/save');
            var data = $('#printForm').serialize()
            $.post(url,data,function(response) {
                if(response == "TRUE"){
                    toastr.success('Rocket Manifest data updated successfully ');
                }
            });
        });
        
       
        $('.d_run_date').datepicker({
            format: 'mm/dd/yyyy',
            autoclose: true,
            //orientation: "bottom auto",
            todayHighlight: true,
        });
        $('#search_button').on('click',function(){
            var data = $('#printForm').serialize()
            var url = (ADMIN_URL + 'rocket-manifest');
            $.post(url,data,function(response) {
               $('#myTable').addClass('d-none')
               $('#myTableDiv').html(response);
               $('#myTableDiv').removeClass('d-none')
                rowCount = $('#myTable tbody tr').length;
                if(rowCount > 0) {
                    $('#print_Page').removeClass('d-none');
                    $('#save_manifesta').removeClass('d-none');
                } else {
                    $('#print_Page').addClass('d-none');
                    $('#save_manifesta').addClass('d-none');
                }
            })
        });
        
    });

  </script>
@stop

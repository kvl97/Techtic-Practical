@extends('backend.layouts.default')
@section('content')

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

  <!-- begin:: Subheader -->
  <div class="kt-subheader   kt-grid__item" id="kt_subheader">
    
  </div>

  <!-- end:: Subheader -->

  <!-- begin:: Content -->
  <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <!--begin::Portlet-->
    <div class="row">
      <div class="col-lg-12">

        <!--begin::Portlet-->
        <div class="kt-portlet">
          <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
              <h3 class="kt-portlet__head-title">
                {{ $title }}
              </h3>
            </div>
          </div>

          <!--begin::Form-->
        <form class="kt-form kt-form--label-right" id="frmEdit" action="{{ ADMIN_URL }}linerun/edit/{{ $record->id }}">
            <div class="kt-portlet__body">

                <div class="row">
                    <div class="col-md-6 col-lg-6 col-sm-12">
                        <div class="form-group  row">
                            <label class="col-form-label col-md-5 col-lg-5 col-sm-12">Run key <span class="required">*</span></label>
                            <div class="col-md-7 col-lg-7 col-sm-12">
                                <input type="text" class="form-control required " name="c_run_key" placeholder="Run key" value="{{ $record->c_run_key }}" maxlength="50">                   
                            </div>
                        </div>

                        <div class="form-group  row">
                            <label class="col-form-label col-md-5 col-lg-5 col-sm-12">Origin Service Area <span class="required">*</span></label>
                            <div class="col-md-7 col-lg-7 col-sm-12">
                                <?php //pr($record_service_area); exit; ?>
                                <select class="form-control required" name="i_origin_service_area_id" placeholder="Origin Service Area">
                                    <option value="">Select</option>
                                    @if(count($record_service_area) > 0)
                                        @foreach($record_service_area as $val)
                                            @if($val['id'] == $record->i_origin_service_area_id)
                                                <option value="{{ $val['id'] }}" selected="selected">{{ $val['v_area_label'] }}</option>
                                            @else
                                                <option value="{{ $val['id'] }}">{{ $val['v_area_label'] }}</option>
                                            @endif
                                        @endforeach
                                    @endif 
                                </select>                 
                            </div>
                        </div>

                        <div class="form-group  row">
                            <label class="col-form-label col-md-5 col-lg-5 col-sm-12">Run Date <span class="required">*</span></label>
                            <div class="col-md-7 col-lg-7 col-sm-12">
                                <input type="text" class="form-control required date_picker" name="d_run_date" placeholder="Run Date" value="{{date('m/d/Y',strtotime($record->d_run_date))}}">  
                                <span id="d_run_date_error" class="linerun_run_date_error" style="display:none;">Alredy Added Line Run Record.</span>                
                            </div>
                        </div>

                        <div class="form-group  row">
                            <label class="col-form-label col-md-5 col-lg-5 col-sm-12">Driver <span class="required">*</span></label>
                            <div class="col-md-7 col-lg-7 col-sm-12">
                                <?php //pr($record_driver[0]['admin']); exit; ?>
                                <select class="form-control required" name="i_driver_id" placeholder="Driver name">
                                    <option value="">Select</option>
                                    @if(count($record_driver[0]['admin']) > 0)
                                        @foreach($record_driver[0]['admin'] as $val)
                                            @if($val['id'] == $record->i_driver_id)
                                                <option value="{{ $val['id'] }}" selected="selected">{{ $val['v_firstname'].' '.$val['v_lastname']." (".$val['driver_extension'][0]['v_extension'].")"  }}</option>
                                            @else
                                                <option value="{{ $val['id'] }}">{{ $val['v_firstname'].' '.$val['v_lastname']." (".$val['driver_extension'][0]['v_extension'].")"  }}</option>
                                            @endif
                                        @endforeach
                                    @endif 
                                   
                                    exit;
                                </select>            
                            </div>
                        </div>

                        <div class="form-group  row">
                            <label class="col-form-label col-md-5 col-lg-5 col-sm-12">Total Seats</label>
                            <div class="col-md-7 col-lg-7 col-sm-12">
                                <!-- <input type="text" class="form-control required i_num_total" name="i_num_total" placeholder="Total Seats" value="{{ $record->i_num_total }}"> --> 
                                <input type="hidden" class="i_num_total_hidden" name="i_num_total" value ="{{ $record->i_num_total }}">   
                                <label class="kt-mt-10 i_num_total" name="i_num_total"> {{ $record->i_num_total }} </label>           
                            </div>
                        </div>

                        <div class="form-group  row">
                            <label class="col-form-label col-md-5 col-lg-5 col-sm-12">Run Status <span class="required">*</span></label>
                            <div class="col-md-7 col-lg-7 col-sm-12">
                                <select class="form-control required e_run_status" name="e_run_status" placeholder="Run Status">
                                    <option value="">Select</option>
                                    <option value="Open" {{ $record->e_run_status == 'Open' ? 'selected=""' : '' }}>Open</option>
                                    <option value="Closed" {{ $record->e_run_status == 'Closed' ? 'selected=""' : '' }}>Closed</option>
                                    <option value="Set" {{ $record->e_run_status == 'Set' ? 'selected=""' : '' }}>Set</option>
                                    <option value="Dispatched" {{ $record->e_run_status == 'Dispatched' ? 'selected=""' : '' }}>Dispatched</option>
                                    <option value="Modified" {{ $record->e_run_status == 'Modified' ? 'selected=""' : '' }}>Modified</option>
                                    <option value="Departed" {{ $record->e_run_status == 'Departed' ? 'selected=""' : '' }}>Departed</option>
                                    <option value="Delayed" {{ $record->e_run_status == 'Delayed' ? 'selected=""' : '' }}>Delayed</option>
                                    <option value="Dead head" {{ $record->e_run_status == 'Dead head' ? 'selected=""' : '' }}>Dead head</option>
                                    <option value="Completed" {{ $record->e_run_status == 'Completed' ? 'selected=""' : '' }}>Completed</option>
                                    <option value="Private" {{ $record->e_run_status == 'Private' ? 'selected=""' : '' }}>Private</option>
                                </select>                    
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-5 col-lg-5 col-sm-12">Special Instruction</label>
                            <div class="col-lg-7 col-md-7 col-sm-12">
                                <textarea class="form-control t_special_instruction" name="t_special_instruction" placeholder="Special Instruction">{{  $record->t_special_instruction  }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6 col-sm-12">
                        
                        <div class="form-group  row">
                            <label class="col-form-label col-md-5 col-lg-5 col-sm-12">Run Number<span class="required">*</span></label>
                            <div class="col-md-7 col-lg-7 col-sm-12">
                                <label type="text"  style="padding: .65rem 1rem;">{{$record->id}}</label>                  
                            </div>
                        </div>
                        
                        <div class="form-group  row">
                            <label class="col-form-label col-md-5 col-lg-5 col-sm-12">Destination Service Area <span class="required">*</span></label>
                            <div class="col-md-7 col-lg-7 col-sm-12">
                                <?php //pr($record_service_area); exit; ?>
                                <select class="form-control required" id="i_dest_service_area_id" name="i_dest_service_area_id" placeholder="Destination Service Area">
                                    <option value="">Select</option>
                                    @if(count($record_service_area) > 0)
                                        @foreach($record_service_area as $val)
                                            @if($val['id'] == $record->i_dest_service_area_id)
                                                <option value="{{ $val['id'] }}" selected="selected">{{ $val['v_area_label'] }}</option>
                                            @else
                                                <option value="{{ $val['id'] }}">{{ $val['v_area_label'] }}</option>
                                            @endif
                                        @endforeach
                                    @endif 
                                </select>                  
                            </div>
                        </div>

                        <div class="form-group  row">
                            <label class="col-form-label col-md-5 col-lg-5 col-sm-12">Scheduled <span id="scheduled_time">Arrival / Departure</span> Time <span class="required">*</span></label>
                            <div class="col-md-7 col-lg-7 col-sm-12">
                                <input type="text" class="form-control required kt_time_picker t_scheduled_arr_time" name="t_scheduled_arr_time" placeholder="Scheduled Arrival / Departure Time" value="{{ $record->t_scheduled_arr_time }}">               
                            </div>
                        </div>

                        <div class="form-group  row">
                            <label class="col-form-label col-md-5 col-lg-5 col-sm-12">Vehicle <span class="required">*</span></label>
                            <div class="col-md-7 col-lg-7 col-sm-12">
                                <?php //pr($record_vehicle); exit; ?>
                                <select class="form-control required i_vehicle_id" name="i_vehicle_id" placeholder="Vehicle">
                                    <option value="">Select</option>
                                    @if(count($record_vehicle) > 0)
                                        @foreach($record_vehicle as $val)
                                            @if($val['id'] == $record->i_vehicle_id)
                                                <option value="{{ $val['id'] }}" selected="selected">{{ $val['v_vehicle_code'].', '.$val['get_vehicle_specification']['v_make'].', '.$val['get_vehicle_specification']['v_model'].', '.$val['get_vehicle_specification']['v_series'] }}</option>
                                            @else
                                                <option value="{{ $val['id'] }}">{{ $val['v_vehicle_code'].', '.$val['get_vehicle_specification']['v_make'].', '.$val['get_vehicle_specification']['v_model'].', '.$val['get_vehicle_specification']['v_series'] }}</option>
                                            @endif
                                        @endforeach
                                    @endif 
                                </select>            
                            </div>
                        </div>

                        <div class="form-group  row">
                            <label class="col-form-label col-md-5 col-lg-5 col-sm-12">Bookable Seats <span class="required">*</span></label>
                            <div class="col-md-7 col-lg-7 col-sm-12">
                                <input type="text" class="form-control required i_num_available" name="i_num_available" placeholder="Bookable Seats" value="{{ $record->i_num_available }}">       
                                <span id="i_num_available_error" class="linerun_run_available_error" style="display:none;">Do not add bookable seat greater than total seat.</span>             
                            </div>
                        </div>
                        
                        <div class="form-group row ">
                            <label class="col-form-label col-md-5 col-lg-5 col-sm-12">Passanger Service Type <span class="required">*</span></label>
                            <div class="col-lg-7 col-md-7 col-sm-12 radio-btn-msg">
                                <div class="kt-radio-inline mt-10">
                                    <label class="kt-radio">
                                    <input type="radio" class="required-least-one-radio" name="e_service_type" value="Shuttle" id="shuttle" groupid="service_type"<?php if($record->e_service_type =='Shuttle') { ?> checked='checked' <?php } ?>> Shuttle
                                    </label>

                                    <label class="kt-radio">
                                    <input type="radio" class="required-least-one-radio" name="e_service_type" value="Private" id="private" groupid="service_type"<?php if($record->e_service_type =='Private') { ?> checked='checked' <?php } ?>> Private 
                                    </label>
                                    <span class="check"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group  row">
                            <label class="col-form-label col-md-5 col-lg-5 col-sm-12">Kiosk Notice</label>
                            <div class="col-md-7 col-lg-7 col-sm-12">
                                <input type="text" class="form-control" name="v_kiosk_notice" placeholder="Kiosk Notice"  value="{{ $record->v_kiosk_notice }}">
                                             
                            </div>
                        </div>
                    </div>
                </div> 

            </div>
            <div class="kt-portlet__foot">
              <div class="kt-form__actions">
                <div class="row">
                  <div class="col-lg-9 ml-lg-auto">
                    <button type="submit" class="btn btn-brand">Submit</button>
                    <a href="{{ ADMIN_URL }}linerun" class="btn btn-secondary"> Cancel </a>
                  </div>
                </div>
              </div>
            </div>
          </form>

          <!--end::Form-->
        </div>

        <!--end::Portlet-->
      </div>
      
    </div>
  </div>

  <!-- end:: Content -->
</div>

@stop

@section('custom_js')
  <script>
    $(document).ready(function() {
        $('#i_dest_service_area_id').trigger('change');
        hideOpenCloseOption();
        $('.date_picker').datepicker({
          format: 'mm/dd/yyyy',
          autoclose: true,
        })
      
    }); 
    $('.date_picker').on('change', function() { 
        var value = $(this).val();
        if(value != '') {
            $(this).closest('.form-group').removeClass('is-invalid');
            $(this).closest('.form-group').find('.invalid-feedback').remove();
        }
    });
    $('.e_run_status').on('change', function() {
        hideOpenCloseOption();
    });

    $('.i_vehicle_id').on('change', function() {
        var vehicle_id = $(this).val();
        var url = ADMIN_URL + 'linerun/get-vehicle-seat-information/' + vehicle_id;
        
        $.ajax({
            url: url,
            type: 'post',
            data: {vehicle_id : vehicle_id},
            success: function (data) {
                $('.i_num_total').text(data.total_seat);
                $('.i_num_total_hidden').val(data.total_seat);
                $('.i_num_available').val(data.bookable_seat);
            }
        });
    });
    
    function hideOpenCloseOption() {
        var selected_val = $('.e_run_status').val();
        if((selected_val == 'Set') || (selected_val == 'Dispatched') || (selected_val == 'Modified')) {
            $(".e_run_status option[value=Open]").hide();
            $(".e_run_status option[value=Closed]").hide();
        } else if(selected_val == 'Departed') {
            $(".e_run_status option[value=Open]").hide();
            $(".e_run_status option[value=Closed]").hide();
            $(".e_run_status option[value=Set]").hide();
            $(".e_run_status option[value=Dispatched]").hide();
            $(".e_run_status option[value=Modified]").hide();
        } else {
            $(".e_run_status option[value=Open]").show();
            $(".e_run_status option[value=Closed]").show();
            $(".e_run_status option[value=Open]").show();
            $(".e_run_status option[value=Closed]").show();
            $(".e_run_status option[value=Set]").show();
            $(".e_run_status option[value=Dispatched]").show();
            $(".e_run_status option[value=Modified]").show();
        }
    }
    $('body').on('change','#i_dest_service_area_id', function () {
        var value = $(this).val();
        if(value == 1) {
            $('#scheduled_time').text("Departure");
            $('.t_scheduled_arr_time').attr("placeholder", "Scheduled departure Time");
        } else {
            $('#scheduled_time').text("Arrival");
            $('.t_scheduled_arr_time').attr("placeholder", "Scheduled arrived Time");
        }
    });
  </script>
@stop
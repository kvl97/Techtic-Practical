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
                Edit Kiosk Information
              </h3>
            </div>
          </div>

          <!--begin::Form-->
          
            <form action="{{ ADMIN_URL }}kiosk-info/edit/{{ $record->id }}" enctype="multipart/form-data" class="kt-form kt-form--label-right" id="frmEdit" method="POST">
                <div class="kt-portlet__body">
                    <input type="hidden" class="current_id">      
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Run Date <span class="required">*</span></label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <input type="text" class="form-control required date_picker d_run_date" name="d_run_date" placeholder="Date" onblur="$(this).attr('readonly','readonly');" readonly="readonly" value="{{ isset($record->d_departure_time) ? date('m/d/Y',strtotime($record->d_departure_time)) : ''}}">                  
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Notice</label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <input type="text" class="form-control v_notice" name="v_notice" placeholder="Notice" vaule="" value="{{ $record->v_notice }}">                  
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Driver Name</label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <select class="form-control v_driver_name" name="i_driver_id" placeholder="Driver Name" id="v_driver_name">
                                <option value="">Select</option>
                                @if(count($driver_name[0]['admin']) > 0)
                                    @foreach($driver_name[0]['admin'] as $val)
                                        <option value="{{ $val['id'] }}" {{ $record->i_driver_id == $val['id'] ? 'selected=""' : '' }}>{{ $val['v_firstname']." ".$val['v_lastname']." (".$val['driver_extension'][0]['v_extension'].")" }}</option>
                                    @endforeach
                                @endif 
                            </select>               
                        </div>
                    </div>
                
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Departure Time <span class="required">*</span></label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <input type="text" class="form-control required kt_time_picker d_departure_time" name="d_departure_time" placeholder="Departure Time"  onblur="$(this).attr('readonly','readonly');" readonly="readonly" value="{{ date('H:i:s', strtotime($record->d_departure_time)) }}">           
                            <span id="d_departure_time_error" class="exist_label" style="display:none;">Selected departure time already exists. Please choose another time.</span>       
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Van Id </label>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <select class="form-control v_van_id page-service-input" name="v_van_id" placeholder="Vehicle Name" id="v_van_id" >
                              <option value="">Select</option>
                              @if(count($vehicle_name) > 0)
                                  @foreach($vehicle_name as $val)
                                      <option value="{{ $val['id'] }}" {{ $record->v_van_id == $val['id'] ? 'selected=""' : '' }}>{{ $val['v_vehicle_code'] }}</option>
                                  @endforeach
                              @endif 
                              <option value="Other" {{ $record->v_van_id == 'Other' ? 'selected=""' : '' }}>Other</option>
                            </select>       
                            <input type="text" class="form-control van_id_input " name="van_id_input" placeholder="Vehicle Code" value="{{ $record->v_van_id_input }}" style="display:none;" />                
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <div class="row">
                            <div class="col-lg-9 ml-lg-auto">
                                <button type="submit" class="btn btn-brand">Submit</button>
                                <a href="{{ ADMIN_URL }}kiosk-info" class="btn btn-secondary"> Cancel </a>
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
     var date = new Date();
      date.setDate(date.getDate());
      $('.date_picker').datepicker({
          format: 'mm/dd/yyyy',
          autoclose: true,
          todayHighlight: true,
      }).on('changeDate', function(e) {
          //$('.date_picker').datepicker('destroy');
          $('.date_picker').trigger('blur');
      });

      vanInput();
    });

    function vanInput() {

      console.log($('.v_van_id option:selected').text());
      if($('.v_van_id option:selected').text() == 'Other') {
        $('.van_id_input').removeAttr('style');
      } else {
        $('.van_id_input').attr('style', 'display:none');
        $('.van_id_input').val('');
      }
    }
    $('.v_van_id').on('click', function() {
      vanInput();
    });
      
  </script>
@stop
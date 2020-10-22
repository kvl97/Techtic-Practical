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
    
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-info-circle"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Kiosk Information
                </h3>
            </div>
            @if(isset($permission) && isset($permission[19]['i_add_edit']) && $permission[19]['i_add_edit'] == 1)
            <div class="kt-portlet__head-toolbar">
              <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                  <a href="{{ ADMIN_URL }}kiosk-info/add" class="btn btn-brand btn-elevate btn-icon-sm">
                    <i class="la la-plus"></i>
                    Add Record
                  </a>
                </div>
              </div>
            </div>
            @endif
        </div>
      <div class="kt-portlet__body">
        @if(isset($permission) && isset($permission[19]['i_delete']) && $permission[19]['i_delete'] == 1) 
        <div class="table-bulk-action kt-hide">
            <label class="kt-mr-20" style="display: inline;">Bulk Action</label>
            <select class="form-control form-control-sm form-filter kt-input table-group-action-input" title="Select Action" name="bulk_action" style="width: 150px;display: inline;">
              <option value="">Select Action</option>
              <option value="Delete">Delete</option>
            </select>
            <a href="javascript:;" type="button" class="btn btn-brand kt-btn btn-sm kt-btn--icon table-group-action-submit" id="bulk_action_submit"><i class="fa fa-check"></i> Submit</a>
            <input type="hidden"  class="table-group-action-url" value="<?php echo ADMIN_URL.'kiosk-info/bulk-action';?>"/>
        </div>
        @endif

        <!--begin: Datatable -->
        <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable_ajax">
            <thead>
                <tr>
                    @if(isset($permission) && isset($permission[19]['i_delete']) && $permission[19]['i_delete'] == 1)
                    <th class="no-sort first-col-fix-width" style="width: 30px">
                        <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                            <input type="checkbox" value="" class="kt-group-checkable">
                            <span></span>
                        </label>
                      </th>
                    @endif
                    <th width="80px">Location</th>
                    <th class="no-sort" width="120px">Vehicle</th>
                    <th width="100px">Date</th>
                    <th width="100px">Notice</th>
                    <th width="50px">Departure Time</th>
                    <th width="100px">Driver (Extension)</th>
                    <!-- <th>Extension</th> -->
                    <th class="no-sort last">Actions</th>
                </tr>
            </thead>
            <thead>
                <tr class="filter">
                    @if(isset($permission) && isset($permission[19]['i_delete']) && $permission[19]['i_delete'] == 1)
                    <td></td>
                    @endif
                    <td><input type="text" class="form-control form-control-sm form-filter kt-input" name="v_location_code"></td>
                    <td>
                      <select class="form-control form-filter page-service-input v_van_id" name="v_van_id" placeholder="Vehicle Name">
                          <option value="">Select</option>
                          @if(count($vehicle_name) > 0)
                              @foreach($vehicle_name as $val)
                                  <option value="{{ $val['id'] }}">{{ $val['v_vehicle_code'] }}</option>
                              @endforeach
                          @endif 
                          <option value="Other">Other</option>
                      </select>
                      <input type="text" class="form-control van_id_input form-filter" name="van_id_input" placeholder="Vehicle Code" style="display:none;" /> 
                    </td>
                    <td>
                        <input type="text" name="dStartDate" class="form-control form-filter date_picker input-sm" placeholder="Start Date" onblur="$(this).attr('readonly','readonly');" readonly="readonly"><br>
						            <input type="text" name="dEndDate" class="form-control form-filter date_picker input-sm" placeholder="End Date" onblur="$(this).attr('readonly','readonly');" readonly="readonly"/>
                    </td>
                    <td><input type="text" class="form-control form-control-sm form-filter kt-input" name="v_notice"></td>
                    <td class="departure_timepicker"><input type="text" class="form-control form-control-sm form-filter kt-input kt_timepicker_1" name="d_departure_time"  onblur="$(this).attr('readonly','readonly');" readonly="readonly"></td>
                    
                    <td>
                      <select class="form-control form-filter" name="i_driver_id" placeholder="Driver Name">
                          <option value="">Select</option>
                          @if(count($driver_name[0]['admin']) > 0)
                              @foreach($driver_name[0]['admin'] as $val)
                                  <option value="{{ $val['id'] }}">{{ $val['v_firstname']." ".$val['v_lastname']." (".$val['driver_extension'][0]['v_extension'].")" }}</option>
                              @endforeach
                          @endif 
                      </select>
                    </td>
                    <!-- <td><input type="text" class="form-control form-control-sm form-filter kt-input" name="v_extension"></td> -->
                    <td>
                        <button class="btn btn-brand kt-btn btn-sm kt-btn--icon mb-2 filter-submit"><span><i class="la la-search"></i><span>Search</span></span></button> &nbsp;
                        <button class="btn btn-secondary kt-btn btn-sm kt-btn--icon mt-0 mb-2 filter-cancel"><span><i class="la la-close"></i><span>Reset</span></span></button>
                    </td>
                </tr>
            </thead>
        </table>

        <!--end: Datatable -->
      </div>
    </div>

    <!--End::Row-->


    <!--End::Dashboard 1-->
  </div>

  <!-- end:: Content -->

    <!-- <div class="modal fade" id="kt_modal_add_kiosk" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Kiosk Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="row">
                  <div class="modal-body">
                    <form action="" enctype="multipart/form-data" class="form-horizontal" id="add_kiosk_information" method="POST">
                        
                        <div class="row kt-ml-5">
                        
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-5 col-lg-5 col-sm-12">Run Date <span class="required">*</span></label>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                      <input type="text" class="form-control required date_picker" name="d_run_date" placeholder="Date" onblur="$(this).attr('readonly','readonly');" readonly="readonly">                  
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-md-5 col-lg-5 col-sm-12">Notice <span class="required">*</span></label>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                      <input type="text" class="form-control required" name="v_notice" placeholder="Notice">                  
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-md-5 col-lg-5 col-sm-12">Driver Name </label>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                      <select class="form-control " name="i_driver_id" placeholder="Driver Name">
                                          <option value="">Select</option>
                                          @if(count($driver_name[0]['admin']) > 0)
                                              @foreach($driver_name[0]['admin'] as $val)
                                                  <option value="{{ $val['id'] }}">{{ $val['v_firstname']." ".$val['v_lastname'] }}</option>
                                              @endforeach
                                          @endif 
                                      </select>               
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                
                                <div class="form-group row">
                                    <label class="col-form-label col-md-5 col-lg-5 col-sm-12">Departure Time <span class="required">*</span></label>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                      <input type="text" class="form-control required kt_timepicker_1_validate" name="d_departure_time" placeholder="Departure Time" onblur="$(this).attr('readonly','readonly');" readonly="readonly">                  
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-form-label col-md-5 col-lg-5 col-sm-12">Van Id </label>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                      <select class="form-control " name="v_van_id" placeholder="Vehicle Name">
                                        <option value="">Select</option>
                                        @if(count($vehicle_name) > 0)
                                            @foreach($vehicle_name as $val)
                                                <option value="{{ $val['id'] }}">{{ $val['v_vehicle_code'] }}</option>
                                            @endforeach
                                        @endif 
                                      </select>                    
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary add_address_save_popup">Save</button>
                        </div>
                    </form>
                  </div>
                </div>
            </div>
        </div>
    </div> -->

    <!-- <div class="modal fade" id="kt_modal_edit_kiosk" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Kiosk Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="row add-address-content">
                  <div class="modal-body">
                    <form action="" enctype="multipart/form-data" class="form-horizontal" id="edit_kiosk_information" method="POST">
                        <div class="row kt-ml-5">
                            <input type="hidden" class="current_id">                
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-5 col-lg-5 col-sm-12">Run Date <span class="required">*</span></label>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                      <input type="text" class="form-control required date_picker_edit d_run_date" name="d_run_date" placeholder="Date" onblur="$(this).attr('readonly','readonly');" readonly="readonly" >                  
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-md-5 col-lg-5 col-sm-12">Notice <span class="required">*</span></label>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                      <input type="text" class="form-control required v_notice" name="v_notice" placeholder="Notice" vaule="">                  
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-md-5 col-lg-5 col-sm-12">Driver Name</label>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                      <select class="form-control i_driver_id" name="i_driver_id" placeholder="Driver Name" id="i_driver_id">
                                          <option value="">Select</option>
                                          @if(count($driver_name[0]['admin']) > 0)
                                              @foreach($driver_name[0]['admin'] as $val)
                                                  <option value="{{ $val['id'] }}">{{ $val['v_firstname']." ".$val['v_lastname'] }}</option>
                                              @endforeach
                                          @endif 
                                      </select>               
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                
                                <div class="form-group row">
                                    <label class="col-form-label col-md-5 col-lg-5 col-sm-12">Departure Time <span class="required">*</span></label>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                      <input type="text" class="form-control required kt_timepicker_1_validate d_departure_time" name="d_departure_time" placeholder="Departure Time"  onblur="$(this).attr('readonly','readonly');" readonly="readonly" >                  
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-form-label col-md-5 col-lg-5 col-sm-12">Van Id </label>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                      <select class="form-control  v_van_id" name="v_van_id" placeholder="Vehicle Name" id="v_van_id">
                                        <option value="">Select</option>
                                        @if(count($vehicle_name) > 0)
                                            @foreach($vehicle_name as $val)
                                                <option value="{{ $val['id'] }}">{{ $val['v_vehicle_code'] }}</option>
                                            @endforeach
                                        @endif 
                                      </select>                    
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary add_address_save_popup">Save</button>
                        </div>
                    </form>
                  </div>
                </div>
            </div>
        </div>
    </div> -->

</div>

@stop

@section('custom_js')
  <script>
    $(document).ready(function() {
      @if(Session::has('success-message'))
        toastr.success('{{ Session::get('success-message') }}');
      @endif

      //$('.kt_timepicker_1_validate').val('');
      //$('#add_kiosk_information').find('.kt_timepicker_1_validate').val('00:00');

      /*$('#add_kiosk_information').on("submit", function(e) {
        if(!form_valid('#add_kiosk_information')) {
              return false
        }else {
          e.preventDefault();
          var form = new FormData($('#add_kiosk_information')[0]);
              $.ajax({
              type: 'POST',
              url: "{{ ADMIN_URL.'kiosk-info'}}",
              data: form,
              cache: false,
              contentType: false,
              processData: false,
              success: function(data) {
                //swal.fire({
                //  position: 'middle',
                //  type: "success",
                //  title: 'Your record has been saved',
                //  showConfirmButton: false,
                //  timer: 5000,
                //});
                //window.location.assign(ADMIN_URL + 'kiosk-info');
                window.location.reload();
              }
          });
        }
      });*/
    
      /*$('#edit_kiosk_information').on("submit", function(e) {
        var record_id = $('#kt_modal_edit_kiosk').find('.current_id').val();
        var url = ADMIN_URL + 'kiosk-info/edit/' + record_id;
        
        if(!form_valid('#edit_kiosk_information')) {
              return false
        }else {
          e.preventDefault();
          var form = new FormData($('#edit_kiosk_information')[0]);
              $.ajax({
              type: 'POST',
              url: url,
              data: form,
              cache: false,
              contentType: false,
              processData: false,
              success: function(data) {
                //swal.fire({
                //  position: 'middle',
                //  type: "success",
                //  title: 'Your record has been saved',
                //  showConfirmButton: false,
                //  timer: 5000,
                //});
                window.location.reload();
              }
          });
        }
      });*/

      var date = new Date();
      date.setDate(date.getDate());
      $('.date_picker').datepicker({
          format: 'mm/dd/yyyy',
          autoclose: true,
          todayHighlight: true,
          //orientation: "bottom auto",
          
      }).on('changeDate', function(e) {
          $('.date_picker').trigger('blur');
      });

      var url = ADMIN_URL + 'kiosk-info/list-ajax';
      DataTables.init('#datatable_ajax', url);

      vanInput();
    });

    function vanInput() {
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

    /*function kioskInfoEdit(record_id) {
     
        var url = ADMIN_URL + 'kiosk-info/edit/' + record_id;
        $.ajax({
          type: 'POST',
          url: url,
          success: function(data) {
            console.log("data...",data);
            $('#kt_modal_edit_kiosk').modal('show');   
            $('#kt_modal_edit_kiosk').find('.current_id').val(data.data.id);
            $('.date_picker_edit').datepicker("setDate",  data.data.d_run_date);
            // $('#kt_modal_edit_kiosk').find('.d_run_date').val(data.data.d_run_date);
            $('#kt_modal_edit_kiosk').find('.v_notice').val(data.data.v_notice);
            $('#kt_modal_edit_kiosk').find('.d_departure_time').val(data.data.d_departure_time).trigger('blur');
            //$('#kt_modal_edit_kiosk').find('.v_extension').val(data.data.v_extension);
            
            $(data.driver_name).each(function(i, val) {
              $(val.admin).each(function(i1, val1) {
                if(data.data.i_driver_id == val1.id) {
                  $('#kt_modal_edit_kiosk').find('#i_driver_id').val(val1.id);
                }
              });
            });
            $(data.vehicle_name).each(function(i, val) {
              if(data.data.v_van_id == val.id) {
                $('#kt_modal_edit_kiosk').find('#v_van_id').val(val.id); 
              } 
            });   
            return '';
          }
        });
    }*/

  </script>
@stop

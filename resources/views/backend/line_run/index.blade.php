@extends('backend.layouts.default')
@section('content')
<style>
  /* .table-hover tbody tr:hover {
    background-color: #D3D3D3;
  } */
</style>
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
                    <i class="kt-font-brand fas fa-exchange-alt"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Line Run
                </h3>
            </div>
            @if(isset($permission) && isset($permission[13]['i_add_edit']) && $permission[13]['i_add_edit'] == 1)
              <div class="kt-portlet__head-toolbar">
                  <div class="kt-portlet__head-wrapper">
                      <div class="kt-portlet__head-actions">
                          <a href="{{ ADMIN_URL }}linerun/add" class="btn btn-brand btn-elevate btn-icon-sm">
                              <i class="la la-plus"></i>
                              Add New Line Run
                          </a>
                          <a href="{{ ADMIN_URL }}linerun/generate-line-run" class="btn btn-brand btn-elevate btn-icon-sm">
                              <i class="la la-plus"></i>
                              Generate Line Runs
                          </a>
                      </div>
                  </div>
              </div>
            @endif
        </div>
      <div class="kt-portlet__body">
        
        
        <div class="actions d-lg-none d-md-block d-sm-block d-xs-block" style="text-align: right;">
            <div class="table-actions-wrapper">
                <button class="btn btn-brand kt-btn btn-sm kt-btn--icon mb-2 filter-submit" id="h-filter-submit"><span><i class="la la-search"></i><span>Search</span></span></button> &nbsp;
                <button class="btn btn-secondary kt-btn btn-sm kt-btn--icon mt-0 mb-2 filter-cancel" id="h-filter-cancel"><span><i class="la la-close"></i><span>Reset</span></span></button>
            </div>     
        </div>    
        <!--begin: Datatable -->
        <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable_ajax">
            <thead>
                <tr>    
                    <th>ID</th>
                    <th>Run Key</th>
                    <th>Run Date</th>
                    <th>Run Number</th>
                    <th>Origin</th>
                    <th>Destination</th>
                    <th>Arrival / Departure Time</th>
                    <th>Run Status</th>
                    @if(Auth::guard('admin')->user()->i_role_id != 6)
                        <th>Driver name</th>
                    @endif
                    <th>Service Type</th>
                    <th>Total</th>
                    <th class="no-sort last">Actions</th>
                </tr>
            </thead>
            <thead>
                <tr class="filter">
                    <td></td>
                    <td><input type="text" class="form-control form-control-sm form-filter kt-input" name="c_run_key"></td>
                    <td>
                        <input type="text" name="dStartDate" class="form-control form-filter date_picker input-sm" placeholder="Start Date" readonly="readonly"><br>
						            <input type="text" name="dEndDate" class="form-control form-filter date_picker input-sm" placeholder="End Date" readonly="readonly"/>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm form-filter kt-input " name="v_run_number" >
                    </td>
                    <td>
                      <select class="form-control form-filter" name="i_origin_service_area_id" placeholder="Origin">
                          <option value="">Select</option>
                          @if(count($record_service_area) > 0)
                              @foreach($record_service_area as $val)
                                  <option value="{{ $val['id'] }}">{{ $val['v_area_label'] }}</option>
                              @endforeach
                          @endif 
                      </select>
                    </td>
                    <td>
                        <select class="form-control form-filter" name="i_dest_service_area_id" placeholder="Destination">
                            <option value="">Select</option>
                            @if(count($record_service_area) > 0)
                                @foreach($record_service_area as $val)
                                    <option value="{{ $val['id'] }}">{{ $val['v_area_label'] }}</option>
                                @endforeach
                            @endif 
                        </select>
                    </td>
                    <td><input type="text" class="form-control form-control-sm form-filter kt-input kt_timepicker_1_validate" name="t_scheduled_arr_time"  onblur="$(this).attr('readonly','readonly');" readonly="readonly"></td>
                    <td>
                      <select class="form-control form-control-sm form-filter kt-input" title="Select" name="e_run_status">
                            <option value="">Select</option>
                            <option value="Open">Open</option>
                            <option value="Closed">Closed</option>
                            <option value="Set">Set</option>
                            <option value="Dispatched">Dispatched</option>
                            <option value="Modified">Modified</option>
                            <option value="Departed">Departed</option>
                            <option value="Delayed">Delayed</option>
                            <option value="Dead head">Dead head</option>
                            <option value="Completed">Completed</option>
                            <option value="Private">Private</option>
                      </select> 
                    </td>
                    @if(Auth::guard('admin')->user()->i_role_id != 6)
                        <td>
                            <select class="form-control form-control-sm form-filter kt-input" name="i_driver_id" placeholder="Driver name">
                                <option value="">Select</option>
                                @if(count($record_driver[0]['admin']) > 0)
                                    @foreach($record_driver[0]['admin'] as $val)
                                        <option value="{{ $val['id'] }}">{{ $val['v_firstname'].' '.$val['v_lastname']." (".$val['driver_extension'][0]['v_extension'].")" }}</option>
                                    @endforeach
                                @endif 
                            </select>    
                        </td>
                    @endif
                    <td>
                        <select class="form-control form-control-sm form-filter kt-input" title="Select" name="e_service_type">
                            <option value="">Select</option>
                            <option value="Shuttle">Shuttle</option>
                            <option value="Private">Private</option>
                      </select> 
                    </td>
                    <td><input type="text" class="form-control form-control-sm form-filter kt-input" name="i_num_total"></td>
                    <td>
                        <button id="filter-submit" class="btn btn-brand kt-btn btn-sm kt-btn--icon mb-2 filter-submit search-btn"><span><i class="la la-search"></i><span>Search</span></span></button> &nbsp;
                        <button id="filter-cancel" class="btn btn-secondary kt-btn btn-sm kt-btn--icon mt-0 mb-2 filter-cancel search-btn"><span><i class="la la-close"></i><span>Reset</span></span></button>
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
</div>
<div class="d-none" id="print_manifest">
</div> 
@stop

@section('custom_js')
  <script>
    $(document).ready(function() {
      @if(Session::has('success-message'))
        toastr.success('{{ Session::get('success-message') }}');
      @endif

      $('.kt_timepicker_1_validate').val('');

      var date = new Date();
      date.setDate(date.getDate());
      $('.date_picker').datepicker({
          format: 'mm/dd/yyyy',
          autoclose: true,
          todayHighlight: true,
          orientation: "top right",
      }).on('changeDate', function(e) {
      });

      $('body').on('click','.print_page',function(){
          
            var id = $(this).attr('printid');
             /*  console.log(id);
            return false; */
            var url = (ADMIN_URL + 'rocket-line-run/print/'+id);
            var data = {};
            //var data = $('#printForm').serialize()
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
      var url = ADMIN_URL + 'linerun/list-ajax';
      var order =  [0, 'desc'];
      DataTables.init('#datatable_ajax', url, order);

        $('#h-filter-submit').click(function() {
            $('#filter-submit').trigger('click');
        });
        
        $('#h-filter-cancel').click(function() {
            $('#filter-cancel').trigger('click');
        });
        
    });


  </script>
@stop

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
                    <i class="kt-font-brand fa fa-list-alt"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Reservation
                </h3>
                </div>
                @if(isset($permission) && isset($permission[20]['i_add_edit']) && $permission[20]['i_add_edit'] == 1)
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            <a href="{{ FRONTEND_URL }}location-information-backend" class="btn btn-brand btn-elevate btn-icon-sm">
                                <i class="la la-plus"></i>
                                Add New Reservation
                            </a>
                            <a href="javascript:;" action-url="{{ ADMIN_URL }}reservations/export-to-exacle" class="btn btn-brand btn-elevate btn-icon-sm" id="export_to_excel">
                                <i class="fas fa-file-export"></i>
                                Export to Excel
                            </a>
                            
                        </div>
                    </div>
                </div>
                @endif
        </div>
      <div class="kt-portlet__body">
        @if(isset($permission) && isset($permission[20]['i_delete']) && $permission[20]['i_delete'] == 1)
        <div class="table-bulk-action kt-hide">
          <label class="kt-mr-10" style="display: inline;">Bulk Action</label>
          <select class="form-control form-control-sm form-filter kt-input table-group-action-input" title="Select Action" name="bulk_action" style="width: 116px;display: inline;">
            <option value="">Select Action</option>
            <option value="Delete">Delete</option>
          </select>
          <a href="javascript:;" type="button" class="btn btn-brand kt-btn btn-sm kt-btn--icon table-group-action-submit submit-btn" id="bulk_action_submit"><i class="fa fa-check"></i> Submit</a>
          <input type="hidden"  class="table-group-action-url" value="<?php echo ADMIN_URL.'reservations/bulk-action';?>"/>
        </div>
        @endif
        <!--begin: Datatable -->
        <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable_ajax">
            <thead>
                <tr>
                    @if(isset($permission) && isset($permission[20]['i_delete']) && $permission[20]['i_delete'] == 1)
                        <th class="no-sort reservation-head-checkbox first-col-fix-width remove_disable_sorting_class_listing" style="width: 30px; cursor: none !important;">
                        <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                            <input type="checkbox" value="" class="kt-group-checkable">
                            <span></span>
                        </label>
                        </th>
                    @endif
                    <th style="width:100px">Reservation Number</th>
                    <th>Customer Name</th>
                    @if(Auth::guard('admin')->user()->i_role_id != 6)
                      <th class="no-sort">Booked By</th>
                    @endif
                    <th>Origin Point</th>
                    <th>Destination Point</th>
                    <th>Class Type</th>
                    <th>Shuttle Type</th>
                    <th>Travel Date</th>
                    <th>Total Passengers</th>
                    <th>Status</th>
                    <th class="no-sort last">Actions</th>
                </tr>
            </thead>
            <thead>
                <tr class="filter">
                    @if(isset($permission) && isset($permission[20]['i_delete']) && $permission[20]['i_delete'] == 1)
                        <td></td>
                    @endif
                    <td>
                      <input type="text" class="form-control form-control-sm form-filter kt-input" name="v_reservation_number">
                    </td>
                    <td>
                      <input type="text" class="form-control form-control-sm form-filter kt-input" name="i_customer_id" placeholder="Customer Name">
                    </td>
                    @if(Auth::guard('admin')->user()->i_role_id != 6)
                      <td>
                        <select class="form-control form-control-sm form-filter" name="i_booked_by_id" placeholder="Category">
                                <option value="">Select</option>
                                <option value="0">Customer</option>
                                @if(count($admin_users_list) > 0)
                                    @foreach($admin_users_list as $val)
                                        <option value="{{ $val['id'] }}">{{ $val['v_firstname'].' '.$val['v_lastname'] }}</option>
                                    @endforeach
                                @endif  
                            </select>  
                      </td>
                    @endif 
                    <td>
                      <input type="text" class="form-control form-control-sm form-filter kt-input" name="i_origin_point_id" placeholder="Origin Point">
                      <!-- <select class="form-control form-control-sm form-filter" name="i_origin_point_id" placeholder="Origin Point">
                          <option value="">Select</option>
                          @if(count($service_area) > 0)
                              @foreach($service_area as $val)
                                  <option value="{{ $val['id'] }}">{{ $val['v_street1'].', '.$val['geo_cities']['v_city'].', '.$val['geo_cities']['v_county'].', '.$val['v_postal_code'] }}</option>
                              @endforeach
                          @endif 
                      </select>  -->
                    </td>
                    <td>
                      <input type="text" class="form-control form-control-sm form-filter kt-input" name="i_destination_point_id" placeholder="Destination Point">
                      <!-- <select class="form-control form-control-sm form-filter" name="i_destination_point_id" placeholder="Destination Point">
                          <option value="">Select</option>
                          @if(count($service_area) > 0)
                              @foreach($service_area as $val)
                                  <option value="{{ $val['id'] }}">{{ $val['v_street1'].', '.$val['geo_cities']['v_city'].', '.$val['geo_cities']['v_county'].', '.$val['v_postal_code'] }}</option>
                              @endforeach
                          @endif 
                      </select>  -->
                    </td>
                    <td>
                      <select class="form-control orm-control-sm form-filter " name="e_class_type" placeholder="Class Type">
                          <option value="">Select</option> 
                          <option value="OW">One Way</option>
                          <option value="RT">Round Trip</option>       
                      </select>
                    </td>

                    <td>
                      <select class="form-control orm-control-sm form-filter " name="e_shuttle_type" placeholder="Shuttle Type">
                          <option value="">Select</option> 
                          <option value="Shared">Shared</option>
                          <option value="Private">Private</option>       
                      </select>
                    </td>

                    <td>
                      <input type="text" class="form-control form-control-sm form-filter kt-input date_picker" name="departureStartDate" placeholder="Start Date" onblur="$(this).attr('readonly','readonly');" readonly="readonly" />
                      <br>
                      <input type="text" class="form-control form-control-sm form-filter kt-input date_picker" name="departureEndDate" placeholder="End Date" onblur="$(this).attr('readonly','readonly');" readonly="readonly">
                    </td>

                    <!-- <td>
                      <input type="text" class="form-control form-control-sm form-filter kt-input date_picker" name="returnStartDate" placeholder="Start Date" onblur="$(this).attr('readonly','readonly');" readonly="readonly">
                      <br>
                      <input type="text" class="form-control form-control-sm form-filter kt-input date_picker" name="returnEndDate" placeholder="End Date" onblur="$(this).attr('readonly','readonly');" readonly="readonly">
                    </td> -->

                    <td><input type="text" class="form-control form-control-sm form-filter kt-input" name="i_total_num_passengers"></td>
                    <td>
                      <!-- <input type="text" class="form-control form-control-sm form-filter kt-input" name="e_reservation_status"> -->
                      <select class="form-control form-control-sm form-filter" name="e_reservation_status" placeholder="Status">
                            <option value="">Select</option> 
                            <option value="Quote">Quote</option>
                            <option value="Pending Payment">Pending Payment</option>
                            <option value="Requested">Requested</option>
                            <option value="Request Confirmed">Request Confirmed</option>
                            <option value="Rejected">Rejected</option>
                            <option value="Booked">Booked</option>
                            <option value="Cancelled">Cancelled</option>
                            <option value="Refund Requested">Refund Requested</option>
                            <option value="Refunded">Refunded</option>
                        </select>   
                    </td>
                    <td>
                        <button class="btn btn-brand kt-btn btn-sm kt-btn--icon mb-2 filter-submit search-btn"><span><i class="la la-search"></i><span>Search</span></span></button> &nbsp;
                        <button class="btn btn-secondary kt-btn btn-sm kt-btn--icon mt-0 mb-2 filter-cancel reset-btn search-btn"><span><i class="la la-close"></i><span>Reset</span></span></button>
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

@stop

@section('custom_js')
  <script>
    $(document).ready(function() {
      @if(Session::has('success-message'))
        toastr.success('{{ Session::get('success-message') }}');
      @endif

      $('.date_picker').datepicker({
          format: 'mm/dd/yyyy',
          autoclose: true,
          //orientation: "bottom auto",
          todayHighlight: true,
      });

      var url = ADMIN_URL + 'reservations/list-ajax';
      var order =  [0, 'desc'];
      DataTables.init('#datatable_ajax', url, order);
    });

  </script>
@stop

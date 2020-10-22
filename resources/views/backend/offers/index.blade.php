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
                    <i class="kt-font-brand fas fa-ticket-alt"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Offers
                </h3>
                </div>
                @if(isset($permission) && isset($permission[5]['i_add_edit']) && $permission[5]['i_add_edit'] == 1)
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            <a href="{{ ADMIN_URL }}offers/add" class="btn btn-brand btn-elevate btn-icon-sm">
                                <i class="la la-plus"></i>
                                Add Offer
                            </a>
                        </div>
                    </div>
                </div>
                @endif
        </div>
      <div class="kt-portlet__body">
        @if(isset($permission) && isset($permission[5]['i_delete']) && $permission[5]['i_delete'] == 1)
        <div class="table-bulk-action kt-hide">
          <label class="kt-mr-20" style="display: inline;">Bulk Action</label>
          <select class="form-control form-control-sm form-filter kt-input table-group-action-input" title="Select Action" name="bulk_action" style="width: 150px;display: inline;">
            <option value="">Select Action</option>
            <option value="Delete">Delete</option>
          </select>
          <a href="javascript:;" type="button" class="btn btn-brand kt-btn btn-sm kt-btn--icon table-group-action-submit submit-btn" id="bulk_action_submit"><i class="fa fa-check"></i> Submit</a>
          <input type="hidden"  class="table-group-action-url" value="<?php echo ADMIN_URL.'offers/bulk-action';?>"/>
        </div>
        @endif
        <!--begin: Datatable -->
        <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable_ajax">
            <thead>
                <tr>
                    @if(isset($permission) && isset($permission[5]['i_delete']) && $permission[5]['i_delete'] == 1)
                        <th class="no-sort first-col-fix-width" style="width: 30px">
                        <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                            <input type="checkbox" value="" class="kt-group-checkable">
                            <span></span>
                        </label>
                        </th>
                    @endif
                    <th style="width:90px">Coupon Code</th>
                    {{--  <th>Notes</th>  --}}
                    <th style="width:124px">Discount Percentage</th>
                    <th style="width:124px">Discount Flate Price</th>
                    <th>Start Date</th>
                    <th>Expire Date</th>
                    <th>User Type</th>
                    <th>Status</th>
                    <th class="no-sort last">Actions</th>
                </tr>
            </thead>
            <thead>
                <tr class="filter">
                    @if(isset($permission) && isset($permission[5]['i_delete']) && $permission[5]['i_delete'] == 1)
                        <td></td>
                    @endif
                    <td><input type="text" class="form-control form-control-sm form-filter kt-input" name="v_coupon_code"></td>
                    {{--  <td><input type="text" class="form-control form-control-sm form-filter kt-input" name="v_notes"></td>  --}}
                    <td><input type="text" class="form-control form-control-sm form-filter kt-input" name="f_discount_percentage"></td>
                    <td><input type="text" class="form-control form-control-sm form-filter kt-input" name="d_discount_flat_price"></td>
                    <td>
                      {{--  <input type="text" class="form-control form-control-sm form-filter kt-input" name="d_start_date">  --}}
                      <input type="text" name="dStartDate" class="form-control form-filter date_picker input-sm" placeholder="Start Date"  onblur="$(this).attr('readonly','readonly');" readonly="readonly"/><br>
										  <input type="text" name="dEndDate" class="form-control form-filter date_picker input-sm" placeholder="End Date"  onblur="$(this).attr('readonly','readonly');" readonly="readonly"/>
                    </td>
                    <td>
                      {{--  <input type="text" class="form-control form-control-sm form-filter kt-input" name="d_expire_date">  --}}
                      <input type="text" name="dFromDate" class="form-control form-filter date_picker input-sm" placeholder="Start Date"  onblur="$(this).attr('readonly','readonly');" readonly="readonly"/><br>
										  <input type="text" name="dToDate" class="form-control form-filter date_picker input-sm" placeholder="End Date"  onblur="$(this).attr('readonly','readonly');" readonly="readonly"/>
                    </td>
                    <td>
                      <select class="form-control form-control-sm form-filter kt-input" title="Select" name="e_type">
                          <option value="">Select</option>
                          <option value="Customer">Customer</option>
                          <option value="Employee">Employee</option>
                          <option value="Both">Both</option>
                      </select>
                    </td>
                    <td>
                      <select class="form-control form-control-sm form-filter kt-input" title="Select" name="e_status">
                          <option value="">Select</option>
                          <option value="Active">Active</option>
                          <option value="Inactive">Inactive</option>
                          <option value="Expired">Expired</option>
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

      var date = new Date();
      date.setDate(date.getDate());
      $('.date_picker').datepicker({
          format: 'mm/dd/yyyy',
          autoclose: true,
          todayHighlight: true,
          orientation: "bottom auto"
      }).on('changeDate', function(e) {
          //$('.date_picker').datepicker('destroy');
          $('.date_picker').trigger('blur');
      });

      var url = ADMIN_URL + 'offers/list-ajax';
      DataTables.init('#datatable_ajax', url);
    });


  </script>
@stop

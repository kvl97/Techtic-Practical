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
            <i class="kt-font-brand fa fa-shopping-bag"></i>
          </span>
          <h3 class="kt-portlet__head-title">
          {{$title}}
          </h3>
        </div>
        @if(isset($permission) && isset($permission[8]['i_add_edit']) && $permission[8]['i_add_edit'] == 1)
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                    <a href="{{ ADMIN_URL }}system-luggage-def/add" class="btn btn-brand btn-elevate btn-icon-sm">
                        <i class="la la-plus"></i>
                        Add Label
                    </a>
                    </div>
                </div>
            </div>
        @endif
      </div>
      <div class="kt-portlet__body">
        @if(isset($permission) && isset($permission[8]['i_delete']) && $permission[8]['i_delete'] == 1)
        <div class="table-bulk-action kt-hide">
            <label class="kt-mr-20" style="display: inline;">Bulk Action</label>
            <select class="form-control form-control-sm form-filter kt-input table-group-action-input" title="Select Action" name="bulk_action" style="width: 150px;display: inline;">
                <option value="">Select Action</option>
                <option value="Delete">Delete</option>
            </select>
            <a href="javascript:;" type="button" class="btn btn-brand kt-btn btn-sm kt-btn--icon table-group-action-submit" id="bulk_action_submit"><i class="fa fa-check"></i> Submit</a>
            <input type="hidden"  class="table-group-action-url" value="<?php echo ADMIN_URL.'system-luggage-def/bulk-action';?>"/>
        </div>
        @endif
        <!--begin: Datatable -->
        <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable_ajax">
          <thead>
            <tr>
                @if(isset($permission) && isset($permission[8]['i_delete']) && $permission[8]['i_delete'] == 1)
                    <th class="no-sort first-col-fix-width" style="width: 30px">
                        <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                            <input type="checkbox" value="" class="kt-group-checkable">
                            <span></span>
                        </label>
                    </th>
                @endif
                <th>Label</th>
                <th>Type</th>
                <th>Unit Price </th>
                <th>Is Free?</th>
                <th class="no-sort last">Actions</th>
            </tr>

          </thead>
          <thead>
          <tr class="filter">
            @if(isset($permission) && isset($permission[8]['i_delete']) && $permission[8]['i_delete'] == 1)
                <td></td>
            @endif
            <td><input type="text" class="form-control form-control-sm form-filter kt-input" name="v_name" autocomplete="off"></td>
            <td>
              <select class="form-control form-control-sm form-filter kt-input" title="Select" name="e_type">
                <option value="">--Select type--</option>
                <option value="Luggage">Luggage</option>
                <option value="Animal">Animal</option>
                <option value="Special">Special</option>
              </select>
            </td>
            <td><input type="text" class="form-control form-control-sm form-filter kt-input" name="d_unit_price" autocomplete="off"></td>
            <td>
                <select class="form-control form-control-sm form-filter kt-input" title="Select" name="e_is_free">
                    <option value="">Select</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </td>
            <td>
              <button class="btn btn-brand kt-btn btn-sm kt-btn--icon mb-2 filter-submit"><span><i class="la la-search"></i><span>Search</span></span></button> &nbsp;
              <button class="btn btn-secondary kt-btn btn-sm kt-btn--icon mt-0 mb-2 filter-cancel"><span><i class="la la-close"></i><span>Reset</span></span></button>
            </td>
          </tr>
        </table>
      </thead>

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

        var url = ADMIN_URL + 'system-luggage-def/list-ajax';
        DataTables.init('#datatable_ajax', url);

        @if(Session::has('success-message'))
            toastr.success('{{ Session::get('success-message') }}');
        @endif



    });
  </script>
@stop
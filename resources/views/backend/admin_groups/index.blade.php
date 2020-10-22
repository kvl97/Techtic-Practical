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
                    <i class="kt-font-brand fa fa-users-cog"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                {{ $title }}
                </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        <a href="{{ ADMIN_URL }}admin-groups/add" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            Add Group
                        </a>
                    </div>
                </div>
            </div>
        </div>
      <div class="kt-portlet__body">

        <div class="table-bulk-action kt-hide">
          <label class="kt-mr-20" style="display: inline;">Bulk Action</label>
          <select class="form-control form-control-sm form-filter kt-input table-group-action-input" title="Select Action" name="bulk_action" style="width: 150px;display: inline;">
            <option value="">Select Action</option>
            <option value="Delete">Delete</option>
          </select>
          <a href="javascript:;" type="button" class="btn btn-brand kt-btn btn-sm kt-btn--icon table-group-action-submit" id="bulk_action_submit"><i class="fa fa-check"></i> Submit</a>
          <input type="hidden"  class="table-group-action-url" value="<?php echo ADMIN_URL.'admin-groups/bulk-action';?>"/>
        </div>

        <!--begin: Datatable -->
        <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable_ajax">
            <thead>
                <tr>
                    <th class="no-sort first-col-fix-width" style="width: 30px">
                      <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                          <input type="checkbox" value="" class="kt-group-checkable">
                          <span></span>
                      </label>
                    </th>
                    <th>Label</th>
                    <th>Description</th>
                    <th class="no-sort last">Actions</th>
                </tr>
            </thead>  
            <thead>        
                <tr class="filter">
                    <td></td>
                    <td><input type="text" class="form-control form-control-sm form-filter kt-input" name="v_name"></td>
                    <td><input type="text" class="form-control form-control-sm form-filter kt-input" name="v_desc"></td>
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
</div>

@stop

@section('custom_js')
  <script>
    $(document).ready(function() {
      @if(Session::has('success-message'))
        toastr.success('{{ Session::get('success-message') }}');
      @endif
      
      var url = ADMIN_URL + 'admin-groups/list-ajax';
      DataTables.init('#datatable_ajax', url);
    });

    
  </script>
@stop
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
            <i class="kt-font-brand fa fa-bicycle"></i>
          </span>
          <h3 class="kt-portlet__head-title">
            Drivers
          </h3>
        </div>
        @if(isset($permission) && isset($permission[2]['i_add_edit']) && $permission[2]['i_add_edit'] == 1)
            <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                {{--  <div class="dropdown dropdown-inline">
                    <button type="button" class="btn btn-brand btn-elevate btn-icon-sm">
                    <i class="la la-download"></i> Export to Excel
                    </button>

                </div>  --}}
                &nbsp;
                <a href="{{ ADMIN_URL }}drivers/add" class="btn btn-brand btn-elevate btn-icon-sm">
                    <i class="la la-plus"></i>
                    Add Driver
                </a>
                </div>
            </div>
            </div>
        @endif
      </div>
      <div class="kt-portlet__body">
        <div class="table-bulk-action kt-hide">
          
        </div>

        <!--begin: Datatable -->
        <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable_ajax">
          <thead>
            <tr>
                <th>Extension</th>
               <!--  <th class="no-sort">Image</th> -->
                <th>Name</th>
                <th>Dispatch Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Address</th>
                
                <th>Status</th>
                <th class="no-sort last">Actions</th>
            </tr>

          </thead>
          <thead>
          <tr class="filter">

            <td><input type="text" class="form-control form-control-sm form-filter kt-input" name="v_extension"></td>
           <!--  <td></td> -->
            <td><input type="text" class="form-control form-control-sm form-filter kt-input" name="v_firstname"></td>
            <td><input type="text" class="form-control form-control-sm form-filter kt-input" name="v_dispatch_name"></td>
            <td><input type="text" class="form-control form-control-sm form-filter kt-input" name="v_phone"></td>
            <td><input type="text" class="form-control form-control-sm form-filter kt-input" name="v_email"></td>
            <td><input type="text" class="form-control form-control-sm form-filter kt-input" name="v_address"></td>
            <td>
              <select class="form-control form-control-sm form-filter kt-input" title="Select" name="e_status">
                <option value="">Select</option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
                <option value="Terminated">Terminated</option>
                
              </select>
            </td>
            <td>
              <button class="btn btn-brand kt-btn btn-sm kt-btn--icon mb-2 filter-submit search-btn"><span><i class="la la-search"></i><span>Search</span></span></button> &nbsp;
              <button class="btn btn-secondary kt-btn btn-sm kt-btn--icon mt-0 mb-2 filter-cancel search-btn"><span><i class="la la-close"></i><span>Reset</span></span></button>
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
        $('.fancybox').fancybox();
      @if(Session::has('success-message'))
        toastr.success('{{ Session::get('success-message') }}');
      @endif

      var url = ADMIN_URL + 'drivers/list-ajax';
      DataTables.init('#datatable_ajax', url);
    });


  </script>
@stop

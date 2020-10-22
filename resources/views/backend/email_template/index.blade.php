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
                    <i class="kt-font-brand  fa fa-envelope"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                  Email Templates
                </h3>
                </div>
                
        </div>
      <div class="kt-portlet__body">
        <!--begin: Datatable -->
        <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable_ajax">
            <thead>
                <tr>
                    <th style="width:90px">Title</th>
                    <th style="width:124px">Subject</th>
                    <th class="no-sort last">Actions</th>
                </tr>
            </thead>
            <thead>
                <tr class="filter">
                    <td><input type="text" class="form-control form-control-sm form-filter kt-input" name="v_template_title"></td>
                    <td><input type="text" class="form-control form-control-sm form-filter kt-input" name="v_template_subject"></td>
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

      var url = ADMIN_URL + 'email-template/list-ajax';
      var order = [2, 'desc'];
      DataTables.init('#datatable_ajax', url, order);
    });


  </script>
@stop

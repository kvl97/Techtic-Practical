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
                    <i class="kt-font-brand fa fa-map-marker"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Location
                </h3>
                </div>
                @if(isset($permission) && isset($permission[21]['i_add_edit']) && $permission[21]['i_add_edit'] == 1)
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            <a href="{{ ADMIN_URL }}location/add" class="btn btn-brand btn-elevate btn-icon-sm">
                                <i class="la la-plus"></i>
                                Add Location 
                            </a>
                        </div>
                    </div>
                </div>
                @endif
        </div>
      <div class="kt-portlet__body">
        @if(isset($permission) && isset($permission[21]['i_delete']) && $permission[21]['i_delete'] == 1)
        <div class="table-bulk-action kt-hide">
          <label class="kt-mr-20" style="display: inline;">Bulk Action</label>
          <select class="form-control form-control-sm form-filter kt-input table-group-action-input" title="Select Action" name="bulk_action" style="width: 150px;display: inline;">
            <option value="">Select Action</option>
            <option value="Delete">Delete</option>
          </select>
          <a href="javascript:;" type="button" class="btn btn-brand kt-btn btn-sm kt-btn--icon table-group-action-submit submit-btn" id="bulk_action_submit"><i class="fa fa-check"></i> Submit</a>
          <input type="hidden"  class="table-group-action-url" value="<?php echo ADMIN_URL.'location/bulk-action';?>"/>
        </div>
        @endif
        <!--begin: Datatable -->
        <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable_ajax">
            <thead>
                <tr>
                    @if(isset($permission) && isset($permission[21]['i_delete']) && $permission[21]['i_delete'] == 1)
                        <th class="no-sort first-col-fix-width" style="width: 30px">
                        <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                            <input type="checkbox" value="" class="kt-group-checkable">
                            <span></span>
                        </label>
                        </th>
                    @endif
                    <th>Location Type</th>
                    <th>Searvice Area</th>
                    <th>Label</th>
                    <th>Street</th>
                    <th>City</th>
                    <th>County</th>
                    <th>Post Code</th>
                    <th>Service Type</th>
                    <th class="no-sort last">Actions</th>
                </tr>
            </thead>
            <thead>
                <tr class="filter">
                    @if(isset($permission) && isset($permission[21]['i_delete']) && $permission[21]['i_delete'] == 1)
                        <td></td>
                    @endif
                    <td>
                        <select class="form-control form-control-sm form-filter" name="i_point_type_id" placeholder="Location Type">
                            <option value="">Select</option>
                            @if(count($record_point_type) > 0)
                                @foreach($record_point_type as $val)
                                    <option value="{{ $val['id'] }}">{{ $val['v_label'] }}</option>
                                @endforeach
                            @endif  
                        </select>  
                    </td>
                    <td>
                        <select class="form-control form-control-sm form-filter" name="i_service_area_id" placeholder="Searvice Area">
                            <option value="">Select</option>
                            @if(count($service_area) > 0)
                                @foreach($service_area as $val)
                                    <option value="{{ $val['id'] }}">{{ $val['v_area_label'] }}</option>
                                @endforeach
                            @endif  
                        </select>  
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm form-filter kt-input" name="v_label" placeholder="Label">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm form-filter kt-input" name="v_street1" placeholder="Street">
                    </td>
                    <td>
                        <!-- <input type="text" class="form-control form-control-sm form-filter kt-input" name="v_city" placeholder="City"> -->
                        <select class="form-control form-control-sm form-filter" name="v_city" placeholder="City">
                            <option value="">Select</option>
                            @if(count($city_list) > 0)
                                @foreach($city_list as $val)
                                    <option value="{{ $val['id'] }}">{{ $val['v_city'] }}</option>
                                @endforeach
                            @endif  
                        </select>  
                    </td>
                   <td>
                        <!-- <input type="text" class="form-control form-control-sm form-filter kt-input" name="v_country" placeholder="Country"> -->
                        <select class="form-control form-control-sm form-filter" name="v_county" placeholder="County">
                            <option value="">Select</option>
                            @if(count($county_list) > 0)
                                @foreach($county_list as $val)
                                    <option value="{{ $val['v_county'] }}">{{ $val['v_county'] }}</option>
                                @endforeach
                            @endif  
                        </select>  
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm form-filter kt-input" name="v_postal_code" placeholder="Post Code">
                    </td>
                    <td>
                        <select class="form-control form-control-sm form-filter" name="e_service_type" placeholder="Service Type">
                            <option value="">Select</option>
                            <option value="Shared">Shared</option>
                            <option value="Private">Private</option>
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

      var url = ADMIN_URL + 'location/list-ajax';
      DataTables.init('#datatable_ajax', url);
    });


  </script>
@stop

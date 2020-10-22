@extends('backend.layouts.default')
@section('content')
<style>
#rate_table_wrapper .dataTables_info {
    display:none;
}
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
                    <i class="kt-font-brand fa fa-calculator"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    {{ $title }}
                </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        <!-- Some html code if required -->
                    </div>
                </div>
            </div>
        </div>
      <div class="kt-portlet__body">
        <div class="kt-section">
            <div class="kt-section__content table-responsive">
                <table id="rate_table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="no-sort">Class Type</th>
                            <th class="no-sort">Class</th>
                            <th class="no-sort">Base Rate Code</th>
                            <th class="no-sort">Base Rate Factor</th>
                            <th class="no-sort">Tooltip Text</th>
                            <th class="no-sort last"> Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rate_factor as $key => $value)
                        <tr>
                            @if($value['e_class_type'] == 'OW')
                              <td class="td-edit-rate" data-factor-id="{{$value['id']}}">One Way</td>
                            @else
                              <td class="td-edit-rate" data-factor-id="{{$value['id']}}">Round Trip</td>
                            @endif
                            <td class="td-edit-rate" data-factor-id="{{$value['id']}}">{{ $value['v_class_label'] }}</td>
                            <td class="td-edit-rate" data-factor-id="{{$value['id']}}">{{ $value['v_base_rate_code'] }}</td>
                            <td class="td-edit-rate" data-factor-id="{{$value['id']}}">{{ $value['d_base_rate_factor'] }}</td>
                            <td class="td-edit-rate" data-factor-id="{{$value['id']}}">{{ $value['v_tooltip_text'] }}</td>
                            <td><a class="btn btn-sm btn-clean btn-icon btn-icon-lg edit input-edit-factor" rel="{{$value['id']}}" href="javascript:" title="edit"><i class="la la-edit"></i> </a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
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
      TableEditable.init('#rate_table');
    });
  </script>
@stop
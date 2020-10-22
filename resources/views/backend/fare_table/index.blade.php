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
                    <i class="kt-font-brand fa fa-dollar-sign"></i>
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
            <div class="kt-section__content">
                <table id="fare_table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="no-sort">Route</th>
                            @foreach($rate_codes as $code)
                            <th>{{ $code['v_rate_code'] }} <i data-container="body" data-toggle="kt-popover" data-placement="top" data-content="{{ $code['v_class_desc'] }}" class="fa fa-info-circle"></i></th>
                            @endforeach
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fare_table as $key => $fare)
                        <tr>
                            <td>{{ $fare['origin'].' -> '.$fare['destination'] }}</td>
                            @foreach($fare['rates'] as $rate)
                                <td class="td-edit-fare" data-rateid="{{ $rate['id'] }}">{{ $rate['d_fare_amount'] }}</td>
                            @endforeach
                            <td><a class="btn btn-sm btn-clean btn-icon btn-icon-lg edit" rel="" href="javascript:" title="edit"><i class="la la-edit"></i> </a></td>
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
      TableEditable.init('#fare_table');
    });
  </script>
@stop
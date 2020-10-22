@extends('backend.layouts.default')
@section('content')
<style>
    .font-size-lg {
        font-size: 1.08rem;
    }
    .table thead th {
        background-color: white;
    }

    #chartdiv {
        width: 100%;
        height: 500px;
    }

    .range{
        background: #fff;
        padding: 5px 10px;
        border: 1px solid #ccc;
        float: right;
    }

    .amcharts-legend-div {
        overflow-y: auto!important;
        max-height: 500px;
    }

    .tools {
        float: right;
        display: inline-block;
        padding: 12px 0 8px 0;
    }
</style>
<link src="{{asset('plugins/amcharts/plugins/export/export.css') }}" rel="stylesheet"/>
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

  <!-- begin:: Subheader -->
  <div class="kt-subheader   kt-grid__item" id="kt_subheader">

  </div>

  <!-- end:: Subheader -->

  <!-- begin:: Content -->
  <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid line_run">

    <!--begin::Portlet-->
    <div class="row">
      <div class="col-lg-12">

        <!--begin::Portlet-->
        <div class="kt-portlet custom_portlet">
          <div class="kt-portlet__head custom_head">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-architecture-and-city"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                {{$title}}
                </h3>
                
            </div>
          </div>

            
        </div>

        <!--end::Portlet-->
      </div>

    </div>
  </div>

  <!-- end:: Content -->
 
</div>

@stop

@section('custom_js')
    
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>


    <!-- <script src="{{asset('plugins/amcharts/amcharts.js') }}{{JS_VERSION}}" ></script>
    <script src="{{asset('plugins/amcharts/serial.js') }}{{JS_VERSION}}" ></script>
    <script src="{{asset('plugins/amcharts/plugins/export/export.min.js') }}{{JS_VERSION}}" ></script>
    <script src="{{asset('plugins/amcharts/themes/light.js') }}{{JS_VERSION}}" ></script> -->

    <script src="{{asset('js/chart.js') }}{{JS_VERSION}}" ></script>
    <script src="{{asset('js/moment.min.js') }}{{JS_VERSION}}" ></script>
    <script src="{{asset('js/daterangepicker.min.js') }}{{JS_VERSION}}" ></script>
    
    <script>
        $(document).ready(function() {
            @if(Session::has('success-message'))
                toastr.success('{{Session::get('success-message')}}');
            @endif
        });

        $(document).on('submit', '.frm_line_run', function(event) {
            event.preventDefault();

            var action =  $(this).attr('action');
            var send_data =  [];

            $(this).find('input').each(function(){
                send_data.push({'id':$(this).attr('data-lrid'),'time':$(this).val()});
            });
            $(this).find('select').each(function(){
                send_data.push({'e_stats':$(this).val()});
            });
            $.post(action,{'data':send_data}, function(data) {
                if ($.trim(data.data) == 'TRUE') {

                    toastr.success('Line Run Information has been saved successfully.');
                    $("html, body").animate({
                        scrollTop: 0
                    }, 600);
                }
            });
        });

        $('#line_run_template').on("submit", function(e) {

        if(!form_valid('#line_run_template')) {

            return false
        }else{

            e.preventDefault();
            var form = new FormData($('#line_run_template')[0]);
                $.ajax({
                type: 'POST',
                url: "{{ ADMIN_URL.'line-run-templates/add'}}",
                data: form,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    var resultData = JSON.parse(data);
                    if(resultData.status == 'success') {
                        
                    } else {
                        
                    }
                }
            });

        }
        });
    </script>
@stop

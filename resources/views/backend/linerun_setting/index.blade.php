@extends('backend.layouts.default')
@section('content')

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

  <!-- begin:: Subheader -->
  <div class="kt-subheader   kt-grid__item" id="kt_subheader">
    
  </div>

  <!-- end:: Subheader -->

  <!-- begin:: Content -->
  <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <!--begin::Portlet-->
    <div class="row">
      <div class="col-lg-12">

        <!--begin::Portlet-->
        <div class="kt-portlet">
          <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fas fa-star-of-david"></i>
                </span>
              <h3 class="kt-portlet__head-title">
              {{$title}}
              </h3>
            </div>
          </div>
          @if(isset($permission) && isset($permission[13]['i_add_edit']) && $permission[13]['i_add_edit'] == 1)
          <!--begin::Form-->
        <form class="kt-form kt-form--label-right" name="frmAdd" id="frmAdd" action="{{ ADMIN_URL }}line-run-settings" >
            <div class="kt-portlet__body">

                
                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Peak start date <span class="required">*</span></label>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <input type="text" class="form-control required date_picker1" name="d_peak_start_date" placeholder="Peak start date" autocomplete="off" value="{{$data->d_peak_start_date ?  date('m/d/Y',strtotime($data->d_peak_start_date)) : ''}}">                
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Peak end date <span class="required">*</span></label>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <input type="text" class="form-control required date_picker2" name="d_peak_end_date" placeholder="Peak end date" autocomplete="off" value="{{$data->d_peak_end_date ?  date('m/d/Y',strtotime($data->d_peak_end_date)) : ''}}">                
                    </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Off season start date<span class="required">*</span></label>
                  <div class="col-lg-4 col-md-6 col-sm-12">
                      <input type="text" class="form-control required date_picker3" name="d_off_season_start_date" placeholder="Off season start date" autocomplete="off" value="{{$data->d_off_season_start_date ?  date('m/d/Y',strtotime($data->d_off_season_start_date)) : ''}}">                
                  </div>
              </div>
              <div class="form-group row">
                  <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Off season end date<span class="required">*</span></label>
                  <div class="col-lg-4 col-md-6 col-sm-12">
                      <input type="text" class="form-control required date_picker4" name="d_off_season_end_date" placeholder="Off season end date" autocomplete="off" value="{{$data->d_off_season_end_date ?  date('m/d/Y',strtotime($data->d_off_season_end_date)) : ''}}">                
                  </div>
              </div>
            </div>
            <div class="kt-portlet__foot">
              <div class="kt-form__actions">
                <div class="row">
                  <div class="col-lg-9 ml-lg-auto">
                    <button type="submit" class="btn btn-brand">Submit</button>
                    <a href="{{ ADMIN_URL }}" class="btn btn-secondary"> Cancel </a>
                  </div>
                </div>
              </div>
            </div>
          </form>

          <!--end::Form-->
          @endif
        </div>

        <!--end::Portlet-->
      </div>
      
    </div>
  </div>

  <!-- end:: Content -->
</div>

@stop

@section('custom_js')
  <script>
    $(document).ready(function() {

		@if(Session::has('success-message'))
			toastr.success('{{Session::get('success-message')}}');
        @endif
        $(".date_picker1").on("change",function (){ 
           if($(this).val() != '') {
                var this_obj = $(this)
                setTimeout(function(){
                    this_obj.closest('.form-group').removeClass('is-invalid');
                    this_obj.closest('.form-group').find('.help-block invalid-feedback').remove();
                    this_obj.css('border', '1px solid rgb(229, 229, 229)');
                }, 100);
           }
        });
       
        $(".date_picker2").on("change",function (){ 
           if($(this).val() != '') {
                var this_obj = $(this)
                setTimeout(function(){
                    this_obj.closest('.form-group').removeClass('is-invalid');
                    this_obj.closest('.form-group').find('.help-block invalid-feedback').remove();
                    this_obj.css('border', '1px solid rgb(229, 229, 229)');
                }, 100);
           }
        });
        $('.date_picker1').datepicker({
            format: 'mm/dd/yyyy',
            autoclose: true,
        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('.date_picker2').datepicker('setStartDate',minDate);
        });

        $('.date_picker2').datepicker({
            format: 'mm/dd/yyyy',
            autoclose: true,
        }).on('changeDate', function (selected) {
            var maxDate = new Date(selected.date.valueOf());
            $('.date_picker1').datepicker('setEndDate',maxDate);
        });

        $('.date_picker3').datepicker({
            format: 'mm/dd/yyyy',
            autoclose: true,
        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('.date_picker4').datepicker('setStartDate',minDate);
        });
        $('.date_picker4').datepicker({
            format: 'mm/dd/yyyy',
            autoclose: true,
        }).on('changeDate', function (selected) {
            var maxDate = new Date(selected.date.valueOf());
            $('.date_picker3').datepicker('setEndDate',maxDate);
        });
        
    });
  </script>
@stop
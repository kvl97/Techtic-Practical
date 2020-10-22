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
              <h3 class="kt-portlet__head-title">
                {{ $title }}
              </h3>
            </div>
          </div>

          <!--begin::Form-->
          <form class="kt-form kt-form--label-right" id="frmAdd" action="{{ ADMIN_URL }}linerun/generate-line-run">
            <div class="kt-portlet__body">
              
              <div class="form-group  row">
                  <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Trip start date <span class="required">*</span></label>
                  <div class="col-md-6 col-lg-4 col-sm-12">
                      <input type="text" class="form-control required start_run_date" name="start_run_date" placeholder="Trip start date"  onblur="$(this).attr('readonly','readonly');" readonly="readonly">               
                  </div>
              </div>
              <div class="form-group  row">
                  <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Trip end date <span class="required">*</span></label>
                  <div class="col-md-6 col-lg-4 col-sm-12">
                      <input type="text" class="form-control required end_run_date" name="end_run_date" placeholder="Trip end date"  onblur="$(this).attr('readonly','readonly');" readonly="readonly">               
                  </div>
              </div>      

              <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                  <div class="row">
                    <div class="col-lg-11 ml-lg-auto">
                      <button type="submit" class="btn btn-brand">Generate</button>
                      <a href="{{ ADMIN_URL }}linerun" class="btn btn btn-secondary btn-icon-sm">
                          Back To Listing
                      </a> 
                    </div>
                  </div>
                </div>
              </div>
            </div> 
          </form>

          <!--end::Form-->
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
        
        var date = new Date();
        /* $('.start_run_date').datepicker({
          format: 'mm/dd/yyyy',
          autoclose: true,
          orientation: "bottom auto",
          startDate: date,
          todayHighlight: true,
      });

      $('.end_run_date').datepicker({
          format: 'mm/dd/yyyy',
          autoclose: true,
          orientation: "bottom auto",
          todayHighlight: true,
      }); */

      $('.start_run_date').datepicker({
          format: 'mm/dd/yyyy',
          autoclose: true,
          //orientation: "bottom auto",
          startDate: date,
          todayHighlight: true,
      }).on('changeDate', function(selected) {
          var minDate = new Date(selected.date.valueOf());
          $('.end_run_date').datepicker('setStartDate', minDate);
      })

      $('.end_run_date').datepicker({
          format: 'mm/dd/yyyy',
          autoclose: true,
          //orientation: "bottom auto",
          todayHighlight: true,
      }).on('changeDate', function(selected) {
          var maxDate = new Date(selected.date.valueOf());
          $('.start_run_date').datepicker('setEndDate', maxDate);
      })
    });    
  </script>
@stop
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
              {{$title}}
              </h3>
            </div>
          </div>

          <!--begin::Form-->
        <form class="kt-form kt-form--label-right" id="frmEdit" action="{{ ADMIN_URL }}system-blackout-date/edit/{{ $records->id }}">
            <div class="kt-portlet__body">
                 
                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12"> Date: <span class="required">*</span></label>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <input type="text" class="form-control required date_picker"  name="d_blackout_date" value="{{date('m/d/Y',strtotime($records->d_blackout_date))}}" placeholder="Date" autocomplete="off"/>                
                    </div>
                </div> 
                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12"> Description: <span class="required">*</span></label>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <input type="text" class="form-control required"  name="v_date_desc" placeholder="Description" autocomplete="off" value="{{ $records->v_date_desc }}"/>                
                    </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Status <span class="required">*</span></label>
                  <div class="col-lg-4 col-md-6 col-sm-12 form-group-sub">
                    <select class="form-control required" name="e_status" placeholder="Status">
                      <option value="">Select</option>
                      <option value="Holiday" {{ $records->e_status == 'Holiday' ? 'selected=""' : '' }}>Holiday</option>
                      <option value="Limited service" {{ $records->e_status == 'Limited service' ? 'selected=""' : '' }}>Limited service</option>
                    </select>                  
                  </div>
                </div>  
            </div>
            <div class="kt-portlet__foot">
              <div class="kt-form__actions">
                <div class="row">
                  <div class="col-lg-9 ml-lg-auto">
                    <button type="submit" class="btn btn-brand">Submit</button>
                    <a href="{{ ADMIN_URL }}system-blackout-date" class="btn btn-secondary"> Cancel </a>
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
     
        $('.date_picker').datepicker({
          format: 'mm/dd/yyyy',
          autoclose: true,
		    })

    });    
  </script>
@stop
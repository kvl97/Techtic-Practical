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
                    <i class="kt-font-brand fa fa-cogs"></i>
                </span>
              <h3 class="kt-portlet__head-title">
              {{$title}}
              </h3>
            </div>
          </div>

          <!--begin::Form-->
        <form class="kt-form kt-form--label-right" name="frmAdd" id="frmAdd" action="{{ADMIN_URL}}site-settings" >
            <div class="kt-portlet__body">

                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Site Name<span class="required">*</span></label>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <input type="text" class="form-control required" name="v_site_name" placeholder="Site Name" value="{{ $data->v_site_name }}">                  
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Description<span class="required">*</span></label>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <textarea type="text" class="form-control required"  name="v_site_description" placeholder="Description" rows="5" >{{ $data->v_site_description }}</textarea> 
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Facebook Link <span class="required">*</span></label>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <input type="text" class="form-control required url" name="v_facebook_link" placeholder="Facebook Link" value="{{ $data->v_facebook_link }}">                  
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Twitter Link <span class="required">*</span></label>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <input type="text" class="form-control required url" name="v_twitter_link" placeholder="Twitter Link" value="{{ $data->v_twitter_link }}" >                  
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Company telephone 1 <span class="required">*</span></label>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <input type="text" class="form-control required number" name="v_comp_tel_1" placeholder="Company telephone 1" value="{{ $data->v_comp_tel_1 }}">   
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Company telephone 2 <span class="required">*</span></label>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <input type="text" class="form-control required number" name="v_comp_tel_2" placeholder="Company telephone 2" value="{{ $data->v_comp_tel_2 }}">                
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Email <span class="required">*</span></label>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <input type="text" class="form-control required email" name="v_comp_email" placeholder="Email" value="{{ $data->v_comp_email }}">                
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Office Hours  <span class="required">*</span></label>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <textarea type="text" class="form-control required"  name="v_office_hours" placeholder="Office Hours" rows="5" >{{ $data->v_office_hours }}</textarea>      
                                       
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">USDOT Number <span class="required">*</span></label>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <input type="text" class="form-control required" name="us_dot_number" placeholder="USDOT Number" value="{{ $data->us_dot_number }}">                
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Refund Processing Fee (%)<span class="required">*</span></label>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <input type="text" class="form-control required" name="d_refund_process_fee" placeholder="Refund Processing Fee (%)" value="{{ $data->d_refund_process_fee }}">                
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
            $('.date_picker2').datepicker('setStartDate', minDate);
        })

        $('.date_picker2').datepicker({
            format: 'mm/dd/yyyy',
            autoclose: true,
        }).on('changeDate', function (selected) {
            var maxDate = new Date(selected.date.valueOf());
            $('.date_picker1').datepicker('setEndDate', maxDate);
        })
        
    });
  </script>
@stop
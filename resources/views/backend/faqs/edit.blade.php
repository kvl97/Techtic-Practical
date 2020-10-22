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
                Edit FAQ
              </h3>
            </div>
          </div>

          <!--begin::Form-->
        
            <form class="kt-form kt-form--label-right" id="frmEdit" action="{{ ADMIN_URL }}faqs/edit/{{ $record->id }}">
                <div class="kt-portlet__body">
                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Question <span class="required">*</span></label>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                    <input type="text" class="form-control required" name="v_question" placeholder="Question" value="{{ $record->v_question }}">               
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Answer <span class="required">*</span></label>
                    <div class="col-lg-10 col-md-6 col-sm-12">
                    <textarea type="text" class="form-control required ckeditor" name="t_answer" placeholder="Answer" rows="20" id="kt-ckeditor-5"> {{ $record->t_answer }} </textarea>               
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Order <span class="required">*</span></label>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                    <input type="text" class="form-control required" name="i_order" placeholder="Order" value="{{ $record->i_order }}">                  
                    </div>
                </div>
                    
                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Status <span class="required">*</span></label>
                    <div class="col-lg-4 col-md-6 col-sm-12 form-group-sub">
                    <select class="form-control required" name="e_status" placeholder="Status">
                        <option value="">Select</option>
                        <option value="Active" {{ $record->e_status == 'Active' ? 'selected=""' : '' }}>Active</option>
                        <option value="Inactive" {{ $record->e_status == 'Inactive' ? 'selected=""' : '' }}>Inactive</option>
                    </select>                  
                    </div>
                </div>  

                </div>
                <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <div class="row">
                    <div class="col-lg-9 ml-lg-auto">
                        <button type="submit" class="btn btn-brand">Submit</button>
                        <a href="{{ ADMIN_URL }}faqs" class="btn btn-secondary"> Cancel </a>
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
      date.setDate(date.getDate());
      $('.date_picker').datepicker({
          format: 'mm/dd/yyyy',
          autoclose: true,
          startDate: date,
          todayHighlight: true,
          //orientation: "bottom auto"
      }).on('changeDate', function(e) {
          //$('.date_picker').datepicker('destroy');
          $('.date_picker').trigger('blur');
      });

      $('.discount').click(function() {
        var discount = $(this).attr('value');
        if(discount != '') {            
          $('.discount_value').removeAttr("style");
        } else {
          $('.discount_value').attr("style");
        }
        if(discount == "Percentage") {
          $('.discount_value').val("");
          $('.discount_value').attr("placeholder", "Discount In Percentage (%)");
        } else {
          $('.discount_value').val("");
          $('.discount_value').attr("placeholder", "Discount In Price ($)");
        }
      });

      $('.d_start_date').on('change', function() {
        var start_date = $(this).datepicker("getDate");
        console.log(start_date);
        if(start_date != '' && start_date != null) {
          console.log("test123"); 
          $('.d_expire_date').addClass('required');
        } else {
          console.log("else test123");
          $('.d_expire_date').removeClass('required');
        }
      });

    });    
  </script>
@stop
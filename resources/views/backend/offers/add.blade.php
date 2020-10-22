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
                Add Offer
              </h3>
            </div>
          </div>

          <!--begin::Form-->
          <form class="kt-form kt-form--label-right" id="frmAdd" action="{{ ADMIN_URL }}offers/add">
            <div class="kt-portlet__body">

              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Coupon Code <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required" name="v_coupon_code" placeholder="Coupon Code" maxlength="100">
                  <span id="v_coupon_code_error" class="exist_label" style="display:none;">Coupon code already exists.</span>                       
                </div>
              </div>

              <div class="form-group row ">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Discount <span class="required">*</span></label>
                <div class="radio-list col-lg-4 col-md-6 col-sm-12">
                  <div class="required-radio-discount">
                    <label class="discout-radio">
                      <input type="radio" class="discount required-least-one-radio" name="discount" value="Percentage" id="f_discount_percentage" groupid="discount_for"> Percentage(%)
                    </label>
                    <label class="discout-radio">
                      <input type="radio" class="discount required-least-one-radio" name="discount" value="Flate Price" id="d_discount_flat_price" groupid="discount_for"> Flat Price($)
                    </label>
                    <input type="text" class="form-control  discount_value required number_decimal" name="discount_value" style="display:none;">
                    {{--  <input type="text" class="form-control d_discount_flat_price discount_value" name="d_discount_flat_price" placeholder="Discount Flate Price" style="display:none;">  --}}
                    <span class="check"></span>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Start Date</label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control date_picker d_start_date" name="d_start_date" placeholder="Start Date" onblur="$(this).attr('readonly','readonly');" readonly="readonly">                  
                </div>
              </div>

              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Expire Date</label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control date_picker d_expire_date" name="d_expire_date" placeholder="Expire Date" onblur="$(this).attr('readonly','readonly');" readonly="readonly" disabled>                  
                </div>
              </div>
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Usage <span class="required">*</span></label>
                <div class="col-lg-10 col-md-6 col-sm-12 radio-list">
                  <div class="required-radio-btn">
                    <label class="gender-radio" for="Once per user">    
                      <input class="required-least-one-radio" type="radio" name="v_usage" id="once_per_user" groupid="gender" value="Once per user" > Once per user
                    </label>
                    
                    <label class="gender-radio" for="Multiple time per user">
                      <input class="required-least-one-radio" type="radio" name="v_usage" id="multiple_time_per_user" groupid="gender"  value="Multiple time per user" > Multiple time per user
                    </label> 
                    
                    <label class="gender-radio" for="On first reservation only">
                      <input class="required-least-one-radio" type="radio" name="v_usage" id="On_first_reservation_only" groupid="gender" value="On first reservation only" > On first reservation only
                    </label>    
                    <span class="check"></span>   
                  </div>     
                </div>
              </div>
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Max number of usage <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required digits" name="v_max_number_of_usage" placeholder="Max number of usage" maxlength="50">                 
                </div>
              </div>
              
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Notes <span class="required">*</span></label>
                <div class="col-lg-10 col-md-6 col-sm-12">
                  <textarea type="text" class="form-control required ckeditor" name="v_notes" placeholder="Notes" rows="20" id="kt-ckeditor-5" maxlength="1000"></textarea>                  
                </div>
              </div>
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Trip Type <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12 form-group-sub">
                  <select class="form-control required" name="e_trip_type" placeholder="Trip Type">
                    <option value="">Select Type</option>
                    <option value="One Way">One Way</option>
                    <option value="Round Trip">Round Trip</option>
                    <option value="Both">Both</option>
                    
                  </select>                  
                </div>
              </div>
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">User Type <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12 form-group-sub">
                  <select class="form-control required" name="e_type" placeholder="User Type">
                      <option value="">Select</option>
                      <option value="customer">Customer</option>
                      <option value="employee">Employee</option>
                      <option value="both">Both</option>
                  </select>                  
                </div>
              </div>
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Status <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12 form-group-sub">
                  <select class="form-control required" name="e_status" placeholder="Status">
                    <option value="">Select</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                  </select>                  
                </div>
              </div>     
              
            </div>
            <div class="kt-portlet__foot">
              <div class="kt-form__actions">
                <div class="row">
                  <div class="col-lg-9 ml-lg-auto">
                    <button type="submit" class="btn btn-brand">Submit</button>
                    <a href="{{ ADMIN_URL }}offers" class="btn btn-secondary"> Cancel </a>
                    
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
      
      //discount
      /*$('.discount').click(function() {
        var discount = $(this).attr('value');
        if(discount == "Percentage") {            
            $('.f_discount_percentage').removeAttr("style");
            $('.d_discount_flat_price').hide();
        } else {
            $('.d_discount_flat_price').removeAttr("style");
            $('.f_discount_percentage').hide();
        }
      });*/

      $('.discount').click(function() {
        var discount = $(this).attr('value');
        if(discount == "Percentage" || discount == "Flate Price") {            
          $('.discount_value').removeAttr("style");
        } else {
          $('.discount_value').attr("style");
        }
        if(discount == "Percentage") {
          $('.discount_value').attr("placeholder", "Discount In Percentage (%)");
        } else {
          $('.discount_value').attr("placeholder", "Discount In Price ($)");
        }
      });

      $('.d_start_date').on('change', function() {
        var start_date = $(this).datepicker("getDate");
        if(start_date != '' && start_date != null) {
          $('.d_expire_date').addClass('required');
          $('.d_expire_date').removeAttr('disabled');
        } else {
          $('.d_expire_date').removeClass('required');
          $('.d_expire_date').attr("disabled", true);
        }
      });

    $('.d_start_date').datepicker({
        format: 'mm/dd/yyyy',
        autoclose: true,
        //orientation: "bottom auto",
        startDate: date,
        todayHighlight: true,
    }).on('changeDate', function(selected) {
        var minDate = new Date(selected.date.valueOf());
        $('.d_expire_date').datepicker('setStartDate', minDate);
    })

    $('.d_expire_date').datepicker({
        format: 'mm/dd/yyyy',
        autoclose: true,
        //orientation: "bottom auto",
        todayHighlight: true,
    }).on('changeDate', function(selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('.d_start_date').datepicker('setEndDate', maxDate);
    })
      

    });    

    
      
  </script>
@stop
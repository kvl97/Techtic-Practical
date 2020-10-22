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
                Add Customer
              </h3>
            </div>
          </div>

          <!--begin::Form-->
          <form class="kt-form kt-form--label-right" id="frmAdd" action="{{ ADMIN_URL }}customers/add">
            <div class="kt-portlet__body">

              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">First Name <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required" name="v_firstname" placeholder="First Name">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Last Name <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required" name="v_lastname" placeholder="Last Name">
                </div>
              </div>

              {{--  <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">User Type <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <select class="form-control required" name="e_user_type" placeholder="User Type">
                    <option value="">Select</option>
                    <option value="Customer">Customer</option>
                  </select> 
                </div>
              </div>  --}}

              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Email <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required email" name="v_email" placeholder="Email">
                  <span id="v_email_error" class="exist_label" style="display:none;">Email id already exists.</span>                         
                </div>
              </div>

              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Password <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="password" class="form-control required validate_password" name="password" id="password" placeholder="Password">                  
                </div>
              </div>

              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Confirm Password <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="password" class="form-control required" name="cpassword" id="cpassword" placeholder="Confirm Password" equalTo="password">                  
                </div>
              </div>

              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Gender <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12 radio-list">
                  <div class="required-radio-btn">
                    <label class="gender-radio" for="male">    
                      <input class="required-least-one-radio" type="radio" name="e_gender" id="male" groupid="gender" value="Male"> Male
                    </label>
                    
                    <label class="gender-radio" for="female">
                      <input class="required-least-one-radio" type="radio" name="e_gender" id="female" groupid="gender"  value="Female" > Female
                    </label> 
                    
                    <label class="gender-radio" for="other">
                      <input class="required-least-one-radio" type="radio" name="e_gender" id="other" groupid="gender" value="Other" > Other
                    </label>    
                    <span class="check"></span>   
                  </div>     
                </div>
              </div>

              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Date of Birth <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control date_picker required" name="d_dob" placeholder="Date of Birth" onblur="$(this).attr('readonly','readonly');" readonly="readonly">                  
                </div>
              </div>
              
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Cell Number <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required phone" name="v_phone" placeholder="Cell Number">                  
                </div>
              </div>
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Landline Number</label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control" name="v_landline_number" placeholder="Landline Number">                  
                </div>
              </div>

              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Street <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required v_street" name="v_street" placeholder="Street">                  
                </div>
              </div>
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">City <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required v_city" name="v_city" placeholder="City">                  
                </div>
              </div>
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">State <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required v_state" name="v_state" placeholder="State">                  
                </div>
              </div>
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Country <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required v_country" name="v_country" placeholder="Country">                  
                </div>
              </div>
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Postal Code <span class="required">*</span></label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control required v_postal_code" name="v_postal_code" placeholder="Postal Code">                  
                </div>
              </div>

              <input type="hidden" class="form-control latitude" name="v_lat" placeholder="Latitude">
              <input type="hidden" class="form-control longitude" name="v_lon" placeholder="Longitude">          

              <!-- <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Latitude </label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control latitude" name="v_lat" placeholder="Latitude">                  
                </div>
              </div>
              <div class="form-group row">
                <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Longitude </label>
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <input type="text" class="form-control longitude" name="v_lon" placeholder="Longitude">                  
                </div>
              </div> -->
              
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
                    <a href="{{ ADMIN_URL }}customers" class="btn btn-secondary"> Cancel </a>
                    
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
          endDate: date,
          todayHighlight: true,
          //orientation: "bottom auto"
      }).on('changeDate', function(e) {
          $('.date_picker').trigger('blur');
      });

    });    

    
      
  </script>
@stop
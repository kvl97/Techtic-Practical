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
            <form class="kt-form kt-form--label-right" id="frmAdd" action="{{ ADMIN_URL }}system-luggage-def/add">
                <div class="kt-portlet__body">
                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Label <span class="required">*</span></label>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                    <input type="text" class="form-control required" name="v_name" placeholder="Label">                  
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Type <span class="required">*</span></label>
                    <div class="col-lg-4 col-md-6 col-sm-12 form-group-sub">
                    <select class="form-control required" name="e_type" placeholder="Type">
                        <option value="">-- Select type --</option>
                        <option value="Luggage">Luggage</option>
                        <option value="Animal">Animal</option>
                        <option value="Special">Special</option>
                    </select>                  
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Is Free? <span class="required">*</span></label>
                    
                    <div class="col-lg-4 col-md-6 col-sm-12 radio-list">
                        <div class="required-radio-btn">
                            <label class="isfree-radio" for="yes">    
                                <input class="isfree required-least-one-radio" type="radio" name="e_is_free" id="yes" groupid="isfree" value="Yes" > Yes
                            </label>
                            
                            <label class="isfree-radio" for="no">
                                <input class="isfree required-least-one-radio" type="radio" name="e_is_free" id="no" groupid="isfree"  value="No" checked="checked" > No
                            </label> 
                        </div>     
                    </div>
                </div>

                <div class="form-group row unit-price-field">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Unit Price ($)<span class="required">*</span></label>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                    <input type="text" class="form-control required number_decimal d_unit_price" name="d_unit_price" placeholder="Unit Price ($)">                  
                    </div>
                </div>     
                <div class="form-group row">
                    <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Description</label>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <textarea  class="form-control" name="v_desc" placeholder="Description" rows="4" cols="50"> </textarea>                
                    </div>
                </div>           
                
                </div>
                <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <div class="row">
                    <div class="col-lg-9 ml-lg-auto">
                        <button type="submit" class="btn btn-brand">Submit</button>
                        <a href="{{ ADMIN_URL }}system-luggage-def" class="btn btn-secondary"> Cancel </a>
                        
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
        $('.isfree').on('click', function(){
            var isfree_val = $(this).attr('value');
            console.log("isfree_val",isfree_val);
            if(isfree_val == 'Yes') {
                $('.unit-price-field').hide();
                $('.d_unit_price').removeClass('required');
            } else {
                $('.unit-price-field').show();
                $('.d_unit_price').addClass('required');
            }
        });
        
    });    
  </script>
@stop
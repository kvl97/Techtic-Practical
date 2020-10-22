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
                        Add Card information
                    </h3>
                    </div>
                </div>
                    
                <!--begin::Form-->
                <form class="kt-form kt-form--label-right" id="frmAdd_card_info" action="{{ ADMIN_URL }}customers-card-info/add/{{ $customer_id }}">
                    <div class="kt-portlet__body">

                        <div class="form-group row">
                            <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Card Number <span class="required">*</span></label>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <input type="text" class="form-control required number" name="i_card_num" placeholder="Card Number" id="i_card_num">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Expiry month<span class="required">*</span></label>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <input type="text" class="form-control required number" name="i_card_exp_month" placeholder="Expiry month" id="i_card_exp_month">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-2 col-lg-2 col-sm-12">Expiry year<span class="required">*</span></label>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <input type="text" class="form-control required number" name="i_card_exp_year" placeholder="Expiry year" id="i_card_exp_year">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-2 col-lg-2 col-sm-12">CVC<span class="required">*</span></label>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <input type="text" class="form-control required number" name="i_cvc" placeholder="CVC" id="i_cvc">
                            </div>
                        </div>



                        <div class="form-group row">
                        
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-9 ml-lg-auto">
                                    <button type="submit" class="btn btn-brand">Submit</button>
                                    <a href="{{ ADMIN_URL }}customers/edit/{{ $customer_id }}#kt_tabs_card_info" class="btn btn-secondary"> Cancel </a>
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

        $("#frmAdd_card_info").submit(function() {
            var form = $("#frmAdd_card_info");
            form.find('.duplicate-error').hide();
            form.find(".form-group").removeClass('is-invalid');
            var flag = 0;

            if (form_valid("#frmAdd_card_info")) {
                /* var curObj = $(this);
                curObj.find('button[type=submit]').addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true); */

            


            var send_data = $("#frmAdd_card_info").serialize();

            
            $.post($("#frmAdd_card_info").attr("action"), send_data, function(data) {
                var curObj = $(this);
                curObj.find('button[type=submit]').removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
               
                if ($.trim(data) == '') {
                    
                    
                    $('#d_departure_time_error').hide();

                    $('#kt_tabs_personal_info').removeClass('active');
                    $('#kt_tabs_personal_address').removeClass('active');
                    $('#kt_tabs_reservation').removeClass('active');
                    $('#kt_tabs_card_info').addClass('active');
                    $('#kt_tabs_card_info').trigger('click');
                    flag = 1;

                    if (flag == 1) {
                        
                        var url = (window.location.href).replace('customers-card-info/add', 'customers/edit');
                        window.location.href = url + '#kt_tabs_card_info';
                        toastr.success('Card Information has been saved successfully.');
                        $("html, body").animate({
                            scrollTop: 0
                        }, 1500);
                        
                    }

                } else if ($.trim(data.status) == 'ERROR') {
                        toastr.error(data.message);
                        $("html, body").animate({
                            scrollTop: 0
                        }, 1500);
                        

                } else {
                    data = $.parseJSON(data);
                  
                    $(data).each(function(i, val) {
                        
                        $.each(val, function(key, v) {
                            $('#' + key).closest('.form-group').addClass('is-invalid');
                            $('#' + key).after('<div id="#' + key + '_error" class="help-block invalid-feedback">' + v + '.</div>');
                            $('#' + key + '_error').show();
                        });
                    });

                    if ($('.is-invalid .form-control').length > 0) {
                        $('html, body').animate({
                            scrollTop: $('.is-invalid .form-control').first().offset().top - 200
                        }, 1000);

                        $('.is-invalid .form-control').first().focus()
                    }
                }
            });
        }
            if ($('.discount_value').attr('style') != undefined) {
                setTimeout(function() {
                    $('.required-radio-discount').find('.help-block').remove();
                }, 100);
            }

            return false;
        });
    
    });
  </script>
@stop
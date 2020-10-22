$(document).ready(function () {

    $('input[type=text],input[type=password]').each(function(){
        if($(this).val()!='') {
            $(this).trigger('focus');
        }
    });

    //Collapse / expand optgroup using select2
    $(".coll_exp_outgroup").select2();
    let optgroupState = {};

    $("body").on('click', '.select2-container--open .select2-results__group', function () {
        $(this).siblings().toggle();
        let id = $(this).closest('.select2-results__options').attr('id');
        let index = $('.select2-results__group').index(this);
        optgroupState[id][index] = !optgroupState[id][index];
    })

    $(".coll_exp_outgroup").on('select2:open', function () {
        $('.select2-dropdown--below').css('opacity', 0);

        let groups = $('.select2-container--open .select2-results__group');
        let id = $('.select2-results__options').attr('id');
        if (!optgroupState[id]) {
            optgroupState[id] = {};
        }
        /* $.each(groups, (index, v) => {
            optgroupState[id][index] = optgroupState[id][index] || false;
            optgroupState[id][index] ? $(v).siblings().show() : $(v).siblings().hide();
        }) */
        $('.select2-dropdown--below').css('opacity', 1);

        if ($('body').find("#select2-home_to_dropoff_location-results").length > 0) {
            $('body').find("#select2-home_to_dropoff_location-results").find('li[aria-selected="true"]').closest('ul').show();
        }
        if ($('body').find("#select2-home_to_pickup_location-results").length > 0) {
            $('body').find("#select2-home_to_pickup_location-results").find('li[aria-selected="true"]').closest('ul').show();
        }
        if ($('body').find("#select2-to_dropoff_location-results").length > 0) {
            $('body').find("#select2-to_dropoff_location-results").find('li[aria-selected="true"]').closest('ul').show();
        }
        if ($('body').find("#select2-to_pickup_location-results").length > 0) {
            $('body').find("#select2-to_pickup_location-results").find('li[aria-selected="true"]').closest('ul').show();
        }
        if ($('body').find("#select2-reservation_from_dropoff_location-results").length > 0) {
            $('body').find("#select2-reservation_from_dropoff_location-results").find('li[aria-selected="true"]').closest('ul').show();
        }
        if ($('body').find("#select2-reservation_from_pickup_location-results").length > 0) {
            $('body').find("#select2-reservation_from_pickup_location-results").find('li[aria-selected="true"]').closest('ul').show();
        }
        if ($('body').find("#select2-reservation_to_dropoff_location-results").length > 0) {
            $('body').find("#select2-reservation_to_dropoff_location-results").find('li[aria-selected="true"]').closest('ul').show();
        }
        if ($('body').find("#select2-reservation_to_pickup_location-results").length > 0) {
            $('body').find("#select2-reservation_to_pickup_location-results").find('li[aria-selected="true"]').closest('ul').show();
        }


    });
    //End of Collapse / expand optgroup using select2

    if ($('.custom-option').length > 0) {
        $('select:not(.multiselect)').select2();
    }
    $('.custom-select').select2({
        dropdownParent: $('#quote-popup')
    }).addClass("my-custom-select");

    //kiosk information
    $(".current_run_date").html($('.slick-active').find('#hidden_run_date').val());

    $('.slick-next').on("click", function () {
        // console.log("test test");
        var run_date_val = $('.slick-active').find('#hidden_run_date').val();
        $(".current_run_date").html(run_date_val);
    });
    
    $('.slick-prev').on("click", function () {
        console.log("test test");
        var run_date_val = $('.slick-active').find('#hidden_run_date').val();
        $(".current_run_date").html(run_date_val);
    });

    /* Get A Quote Popup Start */
        $('.click_for_quote').on("click", function () {
            DynamicAmoutcalc();
        });

        //dynamic get fer ammount 
        var DynamicAmoutcalc = function () {

            var origin_service_area_id = $('option:selected', '#from_pickup_location').attr('service_area');
            var dest_service_area_id = $('option:selected', '#from_dropoff_location').attr('service_area');
            var way = $('input[groupid="trip_type"]:checked').val()
            var numbers = $('#getAQuotePopup').find('input[type=number][name=peoples]').val()

            if (typeof dest_service_area_id != "undefined" && typeof origin_service_area_id != "undefined") {
                $.ajax({
                    url: SITE_URL + "amount",
                    method: 'POST',
                    data: { 'origin_service_area_id': origin_service_area_id, 'dest_service_area_id': dest_service_area_id, 'rate': way, 'number_of_people': numbers },
                    success: function (data) {
                        var resultData = JSON.parse(data);
                        if (resultData.status == 'TRUE') {
                            $('.amount_header').removeClass('d-none');
                            if (resultData.fare_table_info != null) {
                                $('#details_quote').removeClass('d-none');
                                $(".amount_header #btnAddProfile").html('$'+ resultData.total_amount);
                                $("#amount").val(resultData.total_amount);
                            } else {
                                $('#details_quote').removeClass('d-none');
                                $(".amount_header #btnAddProfile").html('$0.00');
                                $("#amount").val('0.00');
                            }
                        } 
                    }
                });
            }
        }

        $('.trip_type').change(function () {
            if (this.value == 'RT') {
                $('#pickup_location').removeClass('d-none');
                $('#dropoff_location').removeClass('d-none');
                $('#to_pickup_location').trigger('change');
            } else {
                $('#pickup_location').addClass('d-none');
                $('#dropoff_location').addClass('d-none');
            }
        });

        $('#from_pickup_location').on('change', function () {
            $('#details_quote').addClass('d-none');
            $('.amount_header').addClass('d-none');
            var pickup_area_id = $('option:selected', this).attr('service_area');
            var value = $(this).val();
            if(value != '') {
                $.post(SITE_URL+'get-home-dropoff-locations',{ pickup_area_id: pickup_area_id, tab: 'quote' },function(data){
                    $('#from_dropoff_location').html(data).trigger("change");
                });
                $.post(SITE_URL+'get-home-dropoff-locations',{pickup_area_id:pickup_area_id,tab:'quoteRt'},function(data) {
                    $('#to_dropoff_location').html(data).val(value).trigger('change');                    
                });
            } else {
                $('#from_dropoff_location').html('<option value="">Drop Off Location</option>');
                $('#to_pickup_location').html('<option value="">Pick up Location</option>');
                $('#to_dropoff_location').html('<option value="">Drop Off Location</option>');
            }
        });

        $('body').on('change','#from_dropoff_location', function() {
            var pickup_area_id = $('option:selected', this).attr('service_area');
            $('#details_quote').addClass('d-none');
            $('.amount_header').addClass('d-none');
            var value = $(this).val();
            if(value != '') {
                $.post(SITE_URL+'get-home-dropoff-locations',{pickup_area_id:pickup_area_id,tab:'quotePickUpRt'},function(data) {
                    $('#to_pickup_location').html(data).val(value).trigger('change');
                });
            } else {
                $('#to_pickup_location').html('<option value="">Pick up Location</option>');
            }
        });

        
        $('.details_quote').on("click", function () {        
            $.ajax({
                url: SITE_URL + "detail-fare",
                method: 'POST',
                data: $('#getAQuotePopup').serialize(),
                success: function (data) {
                    var response = JSON.parse(data);
                    if (response.status == 'TRUE') {
                        window.location.href = response.redirect_url;
                    }
                }
            })
        });


    /* Get A Quote Popup End */

    $('body').on('click', '.customNumber em', function () {

        var ele = $(this).parent().find('input');
        var nmVal = $(this).attr('data-value'),
        curVal = parseInt($(ele).val());
        nmLimit = parseInt($(ele).attr('data-limit'));
        if (nmVal == 'up') {
            if (curVal >= nmLimit) {
                return false
            } else {
                var ChangedVal = curVal + 01;
                ele.attr('value', ChangedVal);
                ele.val(ChangedVal)
                DynamicAmoutcalcResv();
            }
        } else {
            if (curVal < 2) {
                return false
            } else {
                var ChangedVal = curVal - 01;
                ele.attr('value', ChangedVal);
                ele.val(ChangedVal)
                
                DynamicAmoutcalcResv();
            }
        }
    });


    // index page 
    $('input[type=radio][name=e_class_type]').change(function () {
        DynamicAmoutcalcResv();
        if (this.value == 'RT') {
            $('#departure').removeClass('d-none');
            $('#departure input, #departure select').addClass('required');
        } else {
            $('#departure').addClass('d-none');
            $('#departure .invalid-feedback').remove();
            $('#departure input, #departure select').removeClass('required');
            
        }
    });
    

    /* Reservation start */

        $('body').on('change','#home_pickup_location_resv', function (e, data) {
            var onload = (data != undefined) ? data.onLoad : false;
            var origin_service_area_id = $('option:selected', this).attr('service_area');
            var value = $(this).val();
            if(value != '') {
                $(this).closest('.form-group').removeClass('is-invalid');
                $(this).closest('.form-group').find('.invalid-feedback').remove();
                if($resv_id != '') {
                    var url = SITE_URL + 'get-dropoff-locations/' + $resv_id;
                } else {
                    var url = SITE_URL + 'get-dropoff-locations';
                }
                var shuttle_type= $("input[name=e_shuttle_type]:checked").val();
                $.post(url,{origin_service_area_id:origin_service_area_id, tab:'location', city_id:value, shuttle_type:shuttle_type}, function(data){
                    $('#home_dropoff_location_resv').html(data).trigger('change', [{onLoad: onload}]);
                    $('#home_dropoff_location_resv').closest('.form-group').removeClass('is-invalid');
                    $('#home_dropoff_location_resv').closest('.form-group').find('.invalid-feedback').remove();
                });
                $.post(url,{origin_service_area_id:origin_service_area_id, tab:'location_dropoff_rt', city_id:value, shuttle_type:shuttle_type},function(data) {
                    if(onload) {
                        $('#home_dropoff_location_rt_resv').html(data).trigger('change');
                    } else {
                        $('#home_dropoff_location_rt_resv').html(data).val(value).trigger('change');
                    }
                    $('#home_dropoff_location_rt_resv').closest('.form-group').removeClass('is-invalid');
                    $('#home_dropoff_location_rt_resv').closest('.form-group').find('.invalid-feedback').remove();
                });
            } else {
                $('#home_dropoff_location_resv').html('<option value="">Drop Off Location</option>');
                $('#home_pickup_location_rt_resv').html('<option value="">Pick up Location</option>');
                $('#home_dropoff_location_rt_resv').html('<option value="">Drop Off Location</option>');
            }           
            
        });

        $('body').on('change','#home_dropoff_location_resv', function(e, data) {
            var onload = (data != undefined) ? data.onLoad : false;
            var value = $(this).val();
            if(value != '') {
                $(this).closest('.form-group').removeClass('is-invalid');
                $(this).closest('.form-group').find('.invalid-feedback').remove();
                DynamicAmoutcalcResv();                
                var origin_service_area_id = $('option:selected', this).attr('service_area');
                if($resv_id != '') {
                    var url = SITE_URL + 'get-dropoff-locations/' + $resv_id;
                } else {
                    var url = SITE_URL + 'get-dropoff-locations';
                }
                $.post(url,{origin_service_area_id:origin_service_area_id,tab:'location_pickup_rt',city_id:value},function(data) {
                    if(onload) {
                        $('#home_pickup_location_rt_resv').html(data).trigger('change');
                    } else {
                        $('#home_pickup_location_rt_resv').html(data).val(value).trigger('change');
                    }
                    $('#home_pickup_location_rt_resv').closest('.form-group').removeClass('is-invalid');
                    $('#home_pickup_location_rt_resv').closest('.form-group').find('.invalid-feedback').remove();
                });               
            } else {
                $('#home_pickup_location_rt_resv').html('<option value="">Pick up Location</option>');
            }
        });

        var DynamicAmoutcalcResv = function () {

            var pic_up_service_area_id = $('option:selected', '#home_pickup_location_resv').attr('service_area');
            var drpoOff_service_area_id = $('option:selected', '#home_dropoff_location_resv').attr('service_area');
            // var way = $('#home-page-location').find('.e_class_type').val();
            if($('#home-page-location').find('input[type=radio][name="e_class_type"]').length > 0) {
                var way = $('#home-page-location').find('input[type=radio][name="e_class_type"]:checked').val();
            } else {
                var way = $('#home-page-location').find('.e_class_type').val();
            }
            var numbers = $('#home-page-location').find('input[name=peoples]').val();

            if (drpoOff_service_area_id != "" && pic_up_service_area_id != undefined && pic_up_service_area_id != "" && pic_up_service_area_id != undefined && way != "" && numbers != "") {
                $.ajax({
                    url: SITE_URL + "amount",
                    method: 'POST',
                    data: { 'origin_service_area_id': pic_up_service_area_id, 'dest_service_area_id': drpoOff_service_area_id, 'rate': way, 'number_of_people': numbers },
                    success: function (data) {
                        var resultData = JSON.parse(data);
                        $('.amount').removeClass('d-none');
                        if (resultData.status == 'TRUE') {

                            if (resultData.fare_table_info != null) {
                                $(".amount #btnAddProfile").html('$' + resultData.total_amount);
                            } else {
                                $(".amount #btnAddProfile").html('$0.00');
                            }
                        }
                    }
                });
            }
        }

    /* Reservation end */

    

});

// Class Definition
var KTFrontend = function () {

    var handleContactUsForm = function () {
        $(document).on('submit', '#contactUsFrm', function (e) {
            e.preventDefault();
            if (!form_valid('#contactUsFrm')) {
                return false;
            } else {
                var action = $(this).attr('action');
                var data = $('#contactUsFrm').serialize()
                $.post(action, data, function (response) {
                    if (response.status == 'TRUE') {
                        window.location.href = response.redirect_url;
                    }else if(response.status == 'LOGIN_ACCOUNT') {
                        //Login Into Account Show Popup
                        window.location.href = response.redirect_url;
                    } else {
                        response = $.parseJSON(response);
                        $(response).each(function (i, val) {
                            $.each(val, function (key, v) {
                                $('#' + key).closest('.form-group').addClass('is-invalid');
                                $('#' + key).after('<div id="#' + key + '_error" class="help-block invalid-feedback">' + v + '.</div>');
                                $('#' + key + '_error').show();
                            });
                        });
                        if ($('.is-invalid').length > 0) {
                            $('html, body').animate({
                                scrollTop: $('.is-invalid').first().offset().top - 200
                            }, 1000);

                            $('.is-invalid').first().focus()
                        }
                    }
                });
            }
        });

    }

    // Public Functions
    return {
        // public functions
        init: function () {
            handleContactUsForm();
        }
    };
}();
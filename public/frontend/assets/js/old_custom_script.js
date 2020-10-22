$(document).ready(function () {

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



    //header section 
    $('input[type=radio][name=radio-group]').change(function () {
        if (this.value == 'Round Trip') {
            $('#pic_location').removeClass('d-none');
            $('#drop_location').removeClass('d-none');
            $('#to_pickup_location').trigger('change');
        } else {
            $('#pic_location').addClass('d-none');
            $('#drop_location').addClass('d-none');
        }
    });
    

    /* passenger-info */
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
                DynamicAmoutcalcForHome();
                DynamicAmoutcalcResv();
            }
        } else {

            if (curVal < 2) {
                return false
            } else {
                var ChangedVal = curVal - 01;
                ele.attr('value', ChangedVal);
                ele.val(ChangedVal)
                DynamicAmoutcalcForHome();
                DynamicAmoutcalcResv();
            }
        }
    });

    $('.click_for_quote').on("click", function () {
        $('.ammount_header').show();
        DynamicAmoutcalc();
    });

    //dynamic get fer ammount 
    var DynamicAmoutcalc = function () {

        var pic_up_service_area_id = $('option:selected', '#from_pickup_location').attr('service_area');
        var drpoOff_service_area_id = $('option:selected', '#from_dropoff_location').attr('service_area');
        console.log(pic_up_service_area_id);
        console.log(drpoOff_service_area_id);
        var way = $('input[type=radio][name=radio-group]:checked').val()
        var numbers = $('input[type=number][name=people]').val()

        if (typeof drpoOff_service_area_id != "undefined" && typeof pic_up_service_area_id != "undefined") {
            $.ajax({
                url: SITE_URL + "amount",
                method: 'POST',
                data: { 'origin_service_area_id': pic_up_service_area_id, 'dest_service_area_id': drpoOff_service_area_id, 'rate': way, 'number_of_people': numbers },
                success: function (data) {
                    var resultData = JSON.parse(data);

                    if (resultData.status == 'TRUE') {

                        if (resultData.fare_table_info != null) {
                            $('#details_quote').removeClass('d-none');
                            var save = '<button id="btnAddProfile" value="' + resultData.total_amount + '" class="btn btn-md btn-yellow mt-3 mt-sm-0 "" type="button">$' + resultData.total_amount + '</button>';
                            $(".ammount_header").html(save);
                        } else {
                            $('#details_quote').removeClass('d-none');
                            var save = '<button id="btnAddProfile" value="0.00" class="btn btn-md btn-yellow mt-3 mt-sm-0 "" type="button">$0.00</button>';
                            $(".ammount_header").html(save);
                        }
                    } else {

                    }

                }
            });
        } else {

        }

    }

    //disebele drop off location value
    /* $('#from_pickup_location').on('change', function () {

        var value = $(this).val();
        $('#to_dropoff_location').val(value);
        $('#to_dropoff_location').trigger('change');

        var drop_off_ids = $('option:selected', this).attr('drop_off_city_ids');
        var drop_off_city_must_ids = $('option:selected', this).attr('drop_off_city_must_be_ids');

        $("#from_dropoff_location option").each(function () {
            $(this).prop("disabled", false);
        });
        if (drop_off_ids) {
            var trainindIdArray = drop_off_ids.split(',');

            $("#from_dropoff_location option").each(function () {

                if ($.inArray($(this).attr('location_id'), trainindIdArray) != -1) {
                    $(this).prop("disabled", true);
                }


            });
        } else if (drop_off_city_must_ids) {
            var cityMustIdArray = drop_off_city_must_ids.split(',');
            $("#from_dropoff_location option").each(function () {

                if ($.inArray($(this).attr('location_id'), cityMustIdArray) != -1) {
                    $(this).prop("disabled", false);
                } else {
                    $(this).prop("disabled", true);
                }


            });
        }
        $('#from_dropoff_location').select2({
            dropdownParent: $('#quote-popup')
        }).addClass("my-custom-select");
    }); */
    $('#from_pickup_location').on('change', function () {
        $('#details_quote').addClass('d-none');
        $('.ammount_header').hide();
        var origin_service_area_id = $('option:selected', this).attr('service_area');

        var value = $(this).val();
        $('#to_dropoff_location').val(value);
        $('#to_dropoff_location').trigger('change');

        $.post(SITE_URL+'get-header-dropoff-locations',{origin_service_area_id:origin_service_area_id,tab:'location'},function(data){
            // var resultsData = $.parseJSON(data);
            if(data.status != 'FALSE') {
               
                // console.log(data.data);
                $('.dropoff-location').html(data);
                $('#from_dropoff_location').select2({
                    dropdownParent: $('#quote-popup')
                }).addClass("my-custom-select");
            }
        });
    });
    $('body').on('change','#from_dropoff_location', function() {
        $('#details_quote').addClass('d-none');
        $('.ammount_header').hide();
        var value = $(this).val();
        console.log(value);
        $('#to_pickup_location').val(value);
        $('#to_pickup_location').trigger('change');
    });
    
    $('#reservation_from_pickup_location').on('change', function () {
       $('#quote_for_selected_options').hide();
       $('#button_for_reservation').hide();
        var origin_service_area_id = $('option:selected', this).attr('service_area');

        var value = $(this).val();
        $('#reservation_to_dropoff_location').val(value);
        $('#reservation_to_dropoff_location').trigger('change');
        
        $.post(SITE_URL+'get-quote-dropoff-locations',{origin_service_area_id:origin_service_area_id,tab:'detailFare'},function(data){
            // var resultsData = $.parseJSON(data);
            if(data.status != 'FALSE') {
               
                // console.log(data.data);
                $('.reservation-quote-location').html(data);
                $('#reservation_from_dropoff_location').select2({
                    dropdownParent: $('#reservation-page-location')
             }).addClass("my-custom-select");
            }
        });
    });
    $('body').on('change','#reservation_from_dropoff_location', function() {
        $('#quote_for_selected_options').hide();
        $('#button_for_reservation').hide();
        var value = $(this).val();
        $('#reservation_to_pickup_location').val(value);
        $('#reservation_to_pickup_location').trigger('change');
    });

    // index page 
    $('input[type=radio][name=e_class_type]').change(function () {
        DynamicAmoutcalcForHome();
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
    

    //disebele home drop off location value
    /* $('#home_pickup_location').on('change', function () {
        DynamicAmoutcalcForHome();
        var value = $(this).val();
        if(value != '') {
            $(this).closest('.form-group').removeClass('is-invalid');
            $(this).closest('.form-group').find('.invalid-feedback').remove();
        }

        $('#home_to_dropoff_location').val(value);
        $('#home_to_dropoff_location').trigger('change');

        var home_drop_off_ids = $('option:selected', this).attr('home_drop_off_city_ids');
        var home_drop_off_city_must_ids = $('option:selected', this).attr('home_drop_off_city_must_be_ids');

        $("#home_dropoff_location option").each(function () {
            $(this).prop("disabled", false);
        });
        if (home_drop_off_ids) {
            var trainindIdArray = home_drop_off_ids.split(',');

            $("#home_dropoff_location option").each(function () {

                if ($.inArray($(this).attr('location_ids'), trainindIdArray) != -1) {
                    $(this).prop("disabled", true);
                }


            });
        } else if (home_drop_off_city_must_ids) {
            var cityMustIdArray = home_drop_off_city_must_ids.split(',');
            $("#home_dropoff_location option").each(function () {

                if ($.inArray($(this).attr('location_ids'), cityMustIdArray) != -1) {
                    $(this).prop("disabled", false);
                } else {
                    $(this).prop("disabled", true);
                }

            });
        }
        $('#home_dropoff_location').select2({
            dropdownParent: $('#home-page-location')
        }).addClass("my-custom-select");


    })
    $('#home_dropoff_location').on('change', function () {
        DynamicAmoutcalcForHome();
        var value = $(this).val();
        if(value != '') {
            $(this).closest('.form-group').removeClass('is-invalid');
            $(this).closest('.form-group').find('.invalid-feedback').remove();
        }
        $('#home_to_pickup_location').val(value);
        $('#home_to_pickup_location').trigger('change');
    })
 */
    //home dynamic ammount get   
    var DynamicAmoutcalcForHome = function () {

        var pic_up_service_area_id = $('option:selected', '#home_pickup_location').attr('service_area');
        var drpoOff_service_area_id = $('option:selected', '#home_dropoff_location').attr('service_area');
        
        var way = $('input[type=radio][name=e_class_type]:checked').val();
        var numbers = $('input[type=number][name=peoples]').val();

        if (typeof drpoOff_service_area_id != "undefined" && typeof pic_up_service_area_id != "undefined" && typeof way != "undefined" && typeof numbers != "undefined") {
            $.ajax({
                url: SITE_URL + "amount",
                method: 'POST',
                data: { 'origin_service_area_id': pic_up_service_area_id, 'dest_service_area_id': drpoOff_service_area_id, 'rate': way, 'number_of_people': numbers },
                success: function (data) {
                    console.log(data);
                    var resultData = JSON.parse(data);

                    if (resultData.status == 'TRUE') {

                        if (resultData.fare_table_info != null) {
                            var save = '<button id="btnAddProfile" value="' + resultData.total_amount + '" class="btn btn-md btn-red" type="button">$' + resultData.total_amount + '</button>';
                            $(".ammount").html(save);
                        } else {
                            var save = '<button id="btnAddProfile" value="0.00" class="btn btn-md btn-red" type="button">$0.00</button>';
                            $(".ammount").html(save);
                        }
                    } else {

                    }

                }
            });
        } else {

        }

    }

    $('body').on('change','#home_pickup_location_resv', function () {
        var origin_service_area_id = $('option:selected', this).attr('service_area');
        var value = $(this).val();
        if(value != '') {
            $(this).closest('.form-group').removeClass('is-invalid');
            $(this).closest('.form-group').find('.invalid-feedback').remove();
        }
        
        $('#home_to_dropoff_location').val(value);
        $('#home_to_dropoff_location').trigger('change');
        if($resv_id != '') {
            var url = SITE_URL + 'get-dropoff-locations/' + $resv_id;
        } else {
            var url = SITE_URL + 'get-dropoff-locations';
        }
        var shuttle_type= $("input[name=e_shuttle_type]:checked").val();
        // alert(shuttle_type);return;
        $.post(url,{origin_service_area_id:origin_service_area_id,tab:'location',city_id:value,shuttle_type:shuttle_type},function(data){
            // var resultsData = $.parseJSON(data);
            if(data.status != 'FALSE') {
                // console.log('Start');
                // console.log(data.data);
                $('.dropoff-oneway').html(data);
                setTimeout(function() {
                    $('#home_dropoff_location_resv').select2();
                    $('#home_dropoff_location_resv').trigger('change');
                }, 200);
            }
        });
    });

    $('body').on('change','#home_dropoff_location_resv', function() {
        if($(this).val()!='') {
            DynamicAmoutcalcResv();
            var value = $(this).val();
            if(value != '') {
                $(this).closest('.form-group').removeClass('is-invalid');
                $(this).closest('.form-group').find('.invalid-feedback').remove();
            }
            $('#home_to_pickup_location').val(value);
            $('#home_to_pickup_location').trigger('change');
        }
    });

    //home dynamic ammount get   
    var DynamicAmoutcalcResv = function () {

        var pic_up_service_area_id = $('option:selected', '#home_pickup_location_resv').attr('service_area');
        var drpoOff_service_area_id = $('option:selected', '#home_dropoff_location_resv').attr('service_area');
        
        var way = $('input[type=radio][name=e_class_type]:checked').val();
        var numbers = $('input[type=number][name=peoples]').val();

        if (typeof drpoOff_service_area_id != "undefined" && typeof pic_up_service_area_id != "undefined" && typeof way != "undefined" && typeof numbers != "undefined") {
            $.ajax({
                url: SITE_URL + "amount",
                method: 'POST',
                data: { 'origin_service_area_id': pic_up_service_area_id, 'dest_service_area_id': drpoOff_service_area_id, 'rate': way, 'number_of_people': numbers },
                success: function (data) {
                    console.log(data);
                    var resultData = JSON.parse(data);

                    if (resultData.status == 'TRUE') {

                        if (resultData.fare_table_info != null) {
                            var save = '<button id="btnAddProfile" value="' + resultData.total_amount + '" class="btn btn-md btn-red" type="button">$' + resultData.total_amount + '</button>';
                            $(".ammount").html(save);
                        } else {
                            var save = '<button id="btnAddProfile" value="0.00" class="btn btn-md btn-red" type="button">$0.00</button>';
                            $(".ammount").html(save);
                        }
                    } else {

                    }

                }
            });
        } else {

        }

    }

    $('.details_quote').on("click", function () {
        var pickup_location = $('#from_pickup_location').val();
        var drop_location = $('#from_dropoff_location').val();
        var round_trip_drop_location = $('#to_dropoff_location').val();
        var round_trip_pickup_location = $('#to_pickup_location').val();
        var trip_status = $('input[type=radio][name=radio-group]:checked').val();
        var total_number_of_people = $('input[type=number][name=people]').val();
        var total_amount_of_people = $('#btnAddProfile').val();
        if (total_amount_of_people == undefined) {
            var total_amount_of_people = "0.00";
        }

        $.ajax({
            url: SITE_URL + "luggage-and-animal",
            method: 'POST',
            data: { 'pickup_location': pickup_location, 'drop_location': drop_location, 'round_trip_drop_location': round_trip_drop_location, 'round_trip_pickup_location': round_trip_pickup_location, 'trip_status': trip_status, 'number_of_people': total_number_of_people, 'total_amount_of_people': total_amount_of_people },
            success: function (data) {
                var response = JSON.parse(data);
                if (response.status == 'TRUE') {
                    window.location.href = response.redirect_url;
                }
            }
        })
    });

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
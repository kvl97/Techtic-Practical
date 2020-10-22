"use strict";

//const { extendWith } = require("lodash");

// Class Definition
var KTReservationFrontend = function () {


    var handleSidebar = function () {
        
                
        /* $(".dropdown-content-rocket .nav-link").click(function () {
            if (!$(this).hasClass('completed') && !$(this).hasClass('active')) {
                $('.tab-content').find('.btnNext').trigger('click');
            } else if ($(this).hasClass('completed')) {
                window.location.href = $(this).attr('data-action');
            }
        }); */
        $(".dropdown-content-rocket .nav-link").click(function () {
           
            if (!$(this).hasClass('completed') && !$(this).hasClass('active')) {
                if($('.tab-pane').attr('id') == 'reservation-summary') {
                    window.location.href = SITE_URL + $(this).attr('data-action');
                } else {
                    if(!$('.tab-content').find('.btnNext').hasClass('d-none')) {
                        $('#redirect_url').val($(this).attr('data-action'));
                        $('.tab-content').find('.btnNext').trigger('click'); 
                    }
                }         
            } else if ($(this).hasClass('completed')) {
                //not payment tab
                if($('.tab-pane').attr('id') == 'reservation-summary') {
                    window.location.href = SITE_URL + $(this).attr('data-action');
                } else if($('.tab-pane').attr('id') == 'runs') {
                    window.location.href = SITE_URL + $(this).attr('data-action');
                } else if($('.tab-pane').attr('id') != 'payment') { 
                    if(!$('.tab-content').find('.btnNext').hasClass('d-none')) { 
                        $('#redirect_url').val($(this).attr('data-action'));            
                        $('.tab-content').find('.btnNext').trigger('click');                        
                    } else {
                        window.location.href = SITE_URL + $(this).attr('data-action');
                    }
                } else {
                    window.location.href = SITE_URL + $(this).attr('data-action');
                }
            }
        });
    }

    var handleLocationInformation = function () {

        $("input[name=e_shuttle_type]").on('click',function(){
            var shuttle_type= $(this).val();
            if(shuttle_type == "Private"){
                $('#login_redirect_url').val('passenger-information');
                $('.private-tab-hide').addClass('d-none');
                $('.basic-quote').addClass('d-none');
            } else {
                $('#login_redirect_url').val('display-line-runs');
                $('.private-tab-hide').removeClass('d-none');
                $('.basic-quote').removeClass('d-none');
            }
            if($resv_id != '') {
                var url = SITE_URL + 'get-pickup-locations/' + $resv_id;
            } else {
                var url = SITE_URL + 'get-pickup-locations';
            }
            $.post(url,{tab:'location-pickup', shuttle_type:shuttle_type},function(data){
                $('#home_pickup_location_resv').html(data).trigger('change');
            });
        });

        $(document).on("change", ".date_picker_depart", function () {
            if ($(this).val() != '') {
                var this_obj = $(this)
                setTimeout(function () {
                    this_obj.closest('.form-group').removeClass('is-invalid');
                    this_obj.closest('.form-group').find('.invalid-feedback').remove();
                }, 500);
            }
        });
        $(document).on("change", ".date_picker_return", function () {

            if ($(this).val() != '') {
                var this_obj = $(this)
                setTimeout(function () {
                    this_obj.closest('.form-group').removeClass('is-invalid');
                    this_obj.closest('.form-group').find('.invalid-feedback').remove();
                }, 500);
            }
        });
        
        $(document).on('submit', '#frontend_location_information', function (e) {
            e.preventDefault();
            $('.invalid-feedback').closest('div.form-group').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            if (!form_valid('#frontend_location_information')) {
                return false;
            } else {
                var action = $(this).attr('action');
                var data = $('#frontend_location_information').serialize()

                $.post(action, data, function (response) {
                    if (response.status == 'TRUE') {
                        window.location.href = response.redirect_url;
                    } else if(response.status == 'LOGIN_ACCOUNT') {
                        //Login Into Account Show Popup
                        $('#kt_modal_login_account #v_email').val(response.email);
                        $('#loginAccountFrm .invalid-error-message').hide();
                        $('#kt_modal_login_account').modal('show');
                    } else {
                        response = $.parseJSON(response);
                        $(response).each(function (i, val) {
                            $.each(val, function (key, v) {
                                var message = v[0].replace('The', 'Please enter').replace(' field is required', '');
                                if($('#' + key).length == 0) {
                                    key = key + '_resv';
                                }
                                $('#' + key).closest('.form-group').addClass('is-invalid');
                                $('#' + key).after('<div id="#' + key + '_error" class="help-block invalid-feedback">' + message + '</div>');
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

        $(document).on('submit', '#loginAccountFrm', function(e) {
            e.preventDefault();
            if (!form_valid('#loginAccountFrm')) {
                return false;
            } else {
                var action = $(this).attr('action');
                var data = $('#loginAccountFrm').serialize()
                $.post(action, data, function(response) {
                    if(response.status == 'TRUE') {
                        window.location.href = response.redirect_url;
                    } else {
                        setTimeout(function() {
                            $('#loginAccountFrm .invalid-error-message').find('.message').text(response.message);
                            $('#loginAccountFrm .invalid-error-message').show();
                           
                        }, 500);
                    }
                });
            }
        });
    }

    var handleSelectLineRuns = function () {

        $(document).on('submit', '#frontend_linerun_info', function (e) {
            e.preventDefault();
            var paymentStatus = $('#paymentStatus').val();
            var continueProcess = $('#continue_process').val();
            if(!paymentStatus) {
                var departure_count = $('#departure_data_count').val();
                var return_count = $('#return_data_count').val();
                var type_of_trip = $('#type_of_trip').val();
                    
                if(continueProcess == 'No') {
                    if(type_of_trip == 'RT' && (departure_count == 0 || return_count == 0)) {
                        var type = (departure_count == 0) ? 'departure' : 'return';
                        $('#kt_modal_trip_not_available').find('.message').html('There is no line run available for ' + type + ' trip. So, system is considering it as One Way trip.');
                        $('#kt_modal_trip_not_available').modal('show');
                        return false;
                    }
                } else {
                    if(type_of_trip == 'RT') {
                        if(return_count == 0) {
                            $('#type_of_trip').val('OW');
                        } else if(departure_count == 0) {
                            $('#type_of_trip').val('OW');
                            var from = $('#select_linerun_from_rt').val();
                            var to = $('#select_linerun_to_rt').val();
                            $('#select_linerun_from').val(from).trigger('change').after(function() {
                                setTimeout(() => {
                                    $('#select_linerun_to').val(to).trigger('change');
                                }, 500);
                            });
                            $('.date_picker_depart').val($('.date_picker_return').val());
                        }
                    }
                }
            }
            
            
            $('.invalid-feedback').closest('div.form-group').removeClass('is-invalid');
            if (!form_valid('#frontend_linerun_info')) {
                $('#redirect_url').val($('.dropdown-content-rocket .nav-link.active').next('a').attr('data-action'));
                return false;
            } else {
                var action = $(this).attr('action');
                var data = $('#frontend_linerun_info').serialize()

                $.post(action, data, function (response) {
                    if (response.status == 'TRUE') {
                        window.location.href = response.redirect_url;
                    } else {
                        response = $.parseJSON(response);
                        $(response).each(function (i, val) {
                            $.each(val, function (key, v) {
                                $('#' + key).closest('.form-group').addClass('is-invalid');
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

    var handlePassengerInformation = function () {
        var modalConfirm = function (callback) {
            $("#sameAsAboveModal").modal('show');
            $("#modal-btn-si").on("click", function () {
                callback(true);
                $("#sameAsAboveModal").modal('hide');
            });

            $("#modal-btn-no").on("click", function () {
                callback(false);
                $("#sameAsAboveModal").modal('hide');
            });
        };

        $(document).on('click', '#sameAsAbove', function () {
            modalConfirm(function (confirm) {
                if (confirm) {
                    $('#firstDateOfTravel table tbody').each(function () {
                        var trCount = $(this).find('tr:not("[class^=passenger-tr]")').length;
                        var tableId = $(this).closest('table').attr('id');
                        var trCountCopy = $('#secondDateOfTravel #' + tableId).find("tbody tr:not('[class^=passenger-tr]')").length;
                        var type = $(this).closest('table').attr('rel') + '_return';
                        var $i;
                        if (trCount > trCountCopy) {
                            for ($i = trCountCopy; $i < trCount; $i++) {
                                var index = $('#secondDateOfTravel #' + tableId).find("tbody tr:not('[class^=passenger-tr]')").length;
                                var lable_count = index + 1;
                                var classes = $('#secondDateOfTravel #' + tableId).find('.dummy-data').clone();
                                classes.removeAttr('id').attr({ 'id': 'passenger_detail' + index }).removeClass('dummy-data').removeClass('d-none');
                                classes.find('.v_label').attr({ 'id': 'v_' + type + '_label_' + index }).html(lable_count);
                                classes.find('.v_name').attr({ 'name': 'v_' + type + '_name[' + index + ']', 'id': 'v_' + type + '_name' + index }).addClass('required');
                                classes.find('.v_month').removeClass('dummy-data-select').attr({ 'name': 'v_' + type + '_month[' + index + ']', 'id': 'v_' + type + '_month' + index, 'data-select2-id': 'v_' + type + '_month' + index }).addClass('required');
                                classes.find('.v_year').removeClass('dummy-data-select').attr({ 'name': 'v_' + type + '_year[' + index + ']', 'id': 'v_' + type + '_year' + index, 'data-select2-id': 'v_' + type + '_year' + index }).addClass('required');

                                classes.find('.custome_radio_yes').attr({ 'name': 'v_' + type + '_radio_group[' + index + ']', 'id': 'travel_' + type + '_y' + index, 'groupid': 'travel_' + type + index });
                                classes.find('.custome_radio_lable_yes').attr({ 'for': 'travel_' + type + '_y' + index });
                                classes.find('.custome_radio_no').attr({ 'name': 'v_' + type + '_radio_group[' + index + ']', 'id': 'travel_' + type + '_n' + index, 'groupid': 'travel_' + type + index });
                                classes.find('.custome_radio_lable_no').attr({ 'for': 'travel_' + type + '_n' + index });
                                classes.find('.passenger_delete').attr({ 'data-delete-count': index });

                                $('#secondDateOfTravel #' + tableId + ' tbody').append(classes);
                                $('#secondDateOfTravel #' + tableId).find('#' + type + '_total_details').val(index);
                                $('#secondDateOfTravel #' + tableId + ' #passenger_detail' + index + ' select:not(.multiselect)').select2();
                            }
                        } else if (trCount < trCountCopy) {
                            for ($i = (trCountCopy - 1); $i >= trCount; $i--) {
                                var length = $('#secondDateOfTravel #' + tableId).find("tbody tr:not('[class^=passenger-tr]')").length;
                                if (length == 1) {
                                    $('#secondDateOfTravel #' + tableId).find('#' + type + '_total_details').val(0);
                                    $('#secondDateOfTravel #' + tableId + ' tbody').find('.v_name').val('');
                                    $('#secondDateOfTravel #' + tableId + ' tbody').find('select').val('').trigger('change');
                                    
                                } else {
                                    $('#secondDateOfTravel #' + tableId).find('#passenger_detail' + $i + ' select').select2('destroy');
                                    $('#secondDateOfTravel #' + tableId).find('#passenger_detail' + $i).remove();
                                    $('#secondDateOfTravel #' + tableId).find('#' + type + '_total_details').val($i - 1);
                                }
                            }
                        }

                        $(this).find('tr:not("[class^=passenger-tr]")').each(function () {
                            var trID = $(this).attr('id');
                            //Set value
                            $('#secondDateOfTravel #' + tableId + ' #' + trID + ' .v_name').val($(this).find('.v_name').val());
                            $('#secondDateOfTravel #' + tableId + ' #' + trID + ' .v_month').val($(this).find('.v_month').val()).trigger('change');
                            $('#secondDateOfTravel #' + tableId + ' #' + trID + ' .v_year').val($(this).find('.v_year').val()).trigger('change');
                            $(this).find('input[type=radio]').each(function () {
                                var class_name = $(this).attr('class');
                                var checked = $(this).prop('checked');
                                $('#secondDateOfTravel #' + tableId + ' #' + trID + ' .' + class_name).prop('checked', checked);
                            });

                        });

                    });
                }
            });
        });

        $(document).on('click', '#add_new_row', function () {
            var status = true;
            var travelTable = $(this).closest('.passenger-number_details').attr('id');
            var tableId = $(this).closest('table').attr('id');
            var type = $(this).attr('rel');

            $('#' + travelTable + ' #' + tableId + ' tbody').find('input,textarea,select').each(function (i, el) {
                if ($(el).attr('type') == 'radio' && $('input[groupid="' + $(el).attr('groupid') + '"]:checked').length < 1) {
                    status = false;
                    return false;
                } else if ($.trim($(el).val()) == '') {
                    $(el).focus();
                    status = false;
                    return false;
                }
            });
            
            if (status) {
                var counter = $('#' + travelTable + ' #' + tableId).find('#' + type + '_total_details').val();
                ++counter;
                var index = $('#' + travelTable + ' #' + tableId).find("tbody tr:not('[class^=passenger-tr]')").length
                var lable_count = index + 1;
                var classes = $('#' + travelTable + ' #' + tableId).find('.dummy-data').clone();

                classes.removeAttr('id').attr({ 'id': 'passenger_detail' + counter }).removeClass('dummy-data').removeClass('d-none');
                classes.find('.v_label').attr({ 'id': 'v_' + type + '_label_' + counter }).html(lable_count);
                classes.find('.v_name').attr({ 'name': 'v_' + type + '_name[' + counter + ']', 'id': 'v_' + type + '_name' + counter }).addClass('required');
                classes.find('.v_month').removeClass('dummy-data-select').attr({ 'name': 'v_' + type + '_month[' + counter + ']', 'id': 'v_' + type + '_month' + counter, 'data-select2-id': 'v_' + type + '_month' + counter }).addClass('required');
                classes.find('.v_year').removeClass('dummy-data-select').attr({ 'name': 'v_' + type + '_year[' + counter + ']', 'id': 'v_' + type + '_year' + counter, 'data-select2-id': 'v_' + type + '_year' + counter }).addClass('required');

                classes.find('.custome_radio_yes').attr({ 'name': 'v_' + type + '_radio_group[' + counter + ']', 'id': 'travel_' + type + '_y' + counter, 'groupid': 'travel_' + type + counter });
                classes.find('.custome_radio_lable_yes').attr({ 'for': 'travel_' + type + '_y' + counter });
                classes.find('.custome_radio_no').attr({ 'name': 'v_' + type + '_radio_group[' + counter + ']', 'id': 'travel_' + type + '_n' + counter, 'groupid': 'travel_' + type + counter });
                classes.find('.custome_radio_lable_no').attr({ 'for': 'travel_' + type + '_n' + counter });
                classes.find('.passenger_delete').attr({ 'data-delete-count': counter });

                $('#' + travelTable + ' #' + tableId).find('#' + type + '_total_details').val(counter);
                $('#' + travelTable + ' #' + tableId + ' tbody').append(classes);
                $('#' + travelTable + ' #' + tableId + ' #passenger_detail' + counter + ' select:not(.multiselect)').select2();                
                
            }
        });

        $(document).on('click', '.passenger_delete', function () {

            var classesRemove = $(this).attr('data-delete-count');
            var travelTable = $(this).closest('.passenger-number_details').attr('id');
            var tableId = $(this).closest('table').attr('id');
            var type = $(this).attr('rel');

            var length = $('#' + travelTable + ' #' + tableId).find("tbody tr:not('[class^=passenger-tr]')").length;
            
            if (classesRemove == 0) {
                $('#' + travelTable + ' #' + tableId + ' tbody #passenger_detail0').find('.v_name').val('');
                $('#' + travelTable + ' #' + tableId + ' tbody #passenger_detail0').find('#' + type + '_total_details').val(length - 1);
                $('#' + travelTable + ' #' + tableId + ' tbody #passenger_detail0').find('select').val('').trigger('change');
            } else {
                //$('#' + travelTable + ' #' + tableId).find('#passenger_detail' + classesRemove + ' select').select2('destroy');
                $('#' + travelTable + ' #' + tableId + ' tbody tr select:not(.multiselect)').select2('destroy');
                $('#' + travelTable + ' #' + tableId).find('#passenger_detail' + classesRemove).remove();
                var counter = $('#' + travelTable + ' #' + tableId).find('#' + type + '_total_details').val();
                $('#' + travelTable + ' #' + tableId).find('#' + type + '_total_details').val(counter - 1);
                resuffleData(travelTable, tableId, type, classesRemove);
            }
        });

        function resuffleData(travelTable, tableId, type, classesRemove) {

            var count = 0;
            var lable = count + 1;
            var length = $('#' + travelTable + ' #' + tableId).find("tbody tr:not('[class^=passenger-tr]')").length;
            //$('#'+travelTable+' #'+tableId + ' tbody tr select').select2('destroy');
            $('#' + travelTable + ' #' + tableId + ' tbody tr:not("[class^=passenger-tr]")').each(function () {
                $(this).removeAttr('id').attr({ 'id': 'passenger_detail' + count });

                $(this).find('.v_label').attr({ 'id': 'v_' + type + '_label_' + count }).html(lable);
                $(this).find('.v_name').attr({ 'name': 'v_' + type + '_name[' + count + ']', 'id': 'v_' + type + '_name' + count }).addClass('required');
                $(this).find('.v_month').attr({ 'name': 'v_' + type + '_month[' + count + ']', 'id': 'v_' + type + '_month' + count, 'data-select2-id': 'v_' + type + '_month' + count }).addClass('required');
                $(this).find('.v_year').attr({ 'name': 'v_' + type + '_year[' + count + ']', 'id': 'v_' + type + '_year' + count, 'data-select2-id': 'v_' + type + '_year' + count }).addClass('required');

                $(this).find('.custome_radio_yes').attr({ 'name': 'v_' + type + '_radio_group[' + count + ']', 'id': 'travel_' + type + '_y' + count });
                $(this).find('.custome_radio_lable_yes').attr({ 'for': 'travel_' + type + '_y' + count });
                $(this).find('.custome_radio_no').attr({ 'name': 'v_' + type + '_radio_group[' + count + ']', 'id': 'travel_' + type + '_n' + count });
                $(this).find('.custome_radio_lable_no').attr({ 'for': 'travel_' + type + '_n' + count });
                $(this).find('.passenger_delete').attr({ 'data-delete-count': count });
                count++;
                lable++;
            });
            $('#' + travelTable + ' #' + tableId + ' tbody tr select:not(.multiselect)').select2();
            $('#' + travelTable + ' #' + tableId).find('#' + type + '_total_details').val(length - 1);

        }

        $(document).on('submit', '#frontend_passengers_information', function (e) {
            e.preventDefault();
            var travelData = false;
            $('#firstDateOfTravel table tbody tr:not("[class^=passenger-tr]")').find('input,textarea,select').removeClass('required');
            $('#firstDateOfTravel table tbody tr:not("[class^=passenger-tr]")').find('div.form-group').removeClass('is-invalid');
            $('#firstDateOfTravel table tbody tr:not("[class^=passenger-tr]")').each(function () {
                if ($(this).find('.v_name').val() != '' || $(this).find('.v_month').val() != '' || $(this).find('.v_year').val() != '') {
                    $(this).find('input[type=text],select').addClass('required');
                }
            });

            $('#firstDateOfTravel table tbody tr:not("[class^=passenger-tr]")').find('input[type=text],select').each(function () {
                if ($(this).val() != '') {
                    travelData = true;
                }
            });
            if (!travelData) {
                $('#firstDateOfTravel table:first tbody tr:not("[class^=passenger-tr]")').find('input[type=text],select').addClass('required');
            }

            if ($('#secondDateOfTravel').length > 0) {
                var returnTravelData = false;
                $('#secondDateOfTravel table tbody tr:not("[class^=passenger-tr]")').find('input,textarea,select').removeClass('required');
                $('#secondDateOfTravel table tbody tr:not("[class^=passenger-tr]")').find('div.form-group').removeClass('is-invalid');
                $('#secondDateOfTravel table tbody tr:not("[class^=passenger-tr]")').each(function () {
                    if ($(this).find('.v_name').val() != '' || $(this).find('.v_month').val() != '' || $(this).find('.v_year').val() != '') {
                        $(this).find('input[type=text],select').addClass('required');
                    }
                });

                $('#secondDateOfTravel table tbody tr:not("[class^=passenger-tr]")').find('input[type=text],select').each(function () {
                    if ($(this).val() != '') {
                        returnTravelData = true;
                    }
                });
                if (!returnTravelData) {
                    $('#secondDateOfTravel table:first tbody tr:not("[class^=passenger-tr]")').find('input[type=text],select').addClass('required');
                }
            }
            if (!form_valid('#frontend_passengers_information')) {
                $('#redirect_url').val($('.dropdown-content-rocket .nav-link.active').next('a').attr('data-action'));
                return false;
            } else {
                var action = $(this).attr('action');

                var data = $('#frontend_passengers_information').serialize();
                $.post(action, data, function (response) {
                    if (response.status == 'TRUE') {
                        window.location.href = response.redirect_url;
                    } else {
                        response = $.parseJSON(response);
                        $(response).each(function (i, val) {
                            $.each(val, function (key, v) {
                                $('#' + key).closest('.form-group').addClass('is-invalid');
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

    var handleLuggageAnimals = function () {

        $(document).on("change", "select.luggage_dropdown, select.luggage_dropdown_rt", function () {
            if ($(this).hasClass('luggage_dropdown_rt')) {
                passangerLuggage('rt', $(this));
            } else {
                passangerLuggage('', $(this));
            }
        });
        function passangerLuggage(is_rt, luggage_dropdown) {
            var leg1_tot = ($('#leg1_tot_passengers').length > 0) ? $('#leg1_tot_passengers').val() : 0;
            leg1_tot = leg1_tot * 2;
            var leg2_tot = ($('#leg2_tot_passengers').length > 0) ? $('#leg2_tot_passengers').val() : 0;
            leg2_tot = leg2_tot * 2;
            var flag = $(luggage_dropdown).attr('data-per-traveller');

            var return_trip_id = '';
            if (is_rt !== undefined && is_rt == 'rt') {
                return_trip_id = '_rt';
            }
            var passanger_total_luggage = 0;
            var dropdown_value = $(luggage_dropdown).val();
            
            var luggage_charge_value = $(luggage_dropdown).parents('.counter-block').find('.luggage_charge' + return_trip_id).attr('data-value');
            if(flag==1) {
                if(is_rt=='rt' && dropdown_value <= leg2_tot) {
                    luggage_charge_value = 0;
                } else if(dropdown_value <= leg1_tot) {
                    luggage_charge_value = 0;
                }
            }
            // return;
            var total_lugg_amt = dropdown_value * luggage_charge_value;
            $(luggage_dropdown).parents('.counter-block').find(".total_fare_amt" + return_trip_id).val(total_lugg_amt);
            
            $("select.luggage_dropdown" + return_trip_id).each(function () {
                if ($(this).val() == '' || $(this).val() == undefined) {
                    passanger_total_luggage = passanger_total_luggage;
                } else {
                    passanger_total_luggage = passanger_total_luggage + parseInt($(this).val());
                }
                $('#total_luggages' + return_trip_id).val(passanger_total_luggage);
            });
        }
        $(document).on("change", ".is_pet_available, .is_pet_available_rt", function() {
            if ($(this).hasClass('is_pet_available_rt')) {
                isPetAvailable('rt', $(this));
            } else {
                isPetAvailable('', $(this));
            }
        });
        function isPetAvailable(is_rt, is_pet_available) {
            var return_trip_id = '';
            if (is_rt !== undefined && is_rt == 'rt') {
                return_trip_id = '_rt';
            }
            var pet_charge_value = $(is_pet_available).parents('.counter-block').find('.pets_charge'+return_trip_id).attr('data-value');
           
           
            /*  pet_amt = pet_charge_value.split('$ ').pop().split(' EACH')[0]; */
            if ($(is_pet_available).prop("checked") == true) {
                $(is_pet_available).parents('.counter-block').find('.total_fare_amt_pet' + return_trip_id).val(pet_charge_value);
            } else {
                $(is_pet_available).parents('.counter-block').find('.total_fare_amt_pet' + return_trip_id).val('0');
            }
            $('#tatal_pets' + return_trip_id).val($(".is_pet_available" + return_trip_id + ":checked").length);
        }
        $(document).on("change", '.luggage_dropdown, .is_pet_available, .luggage_dropdown_rt, .is_pet_available_rt', function() {
            totalChange();
        });
        
        function totalChange() {
            var total = 0;
            $('.luggage_dropdown').each(function(k,v) {
                var leg1_tot = ($('#leg1_tot_passengers').length > 0) ? $('#leg1_tot_passengers').val() : 0;
                leg1_tot = leg1_tot * 2;
                var flag = $(v).attr('data-per-traveller');
                var val = $(v).val();
                var charge = $(v).parents('.counter-block').find('.luggage_charge').attr('data-value');
                if(flag==1) {
                   if(val <= leg1_tot) {
                        charge = 0;
                    } else {
                        val = val - leg1_tot;
                    }
                }
                total += val * charge;
            })
            $('.is_pet_available').each(function(k,v) {
                if ($(v).is(":checked")){
                    total += parseFloat($(v).val());
                }
            })
            $('.luggage_dropdown_rt').each(function(k,v) {
                var leg2_tot = ($('#leg2_tot_passengers').length > 0) ? $('#leg2_tot_passengers').val() : 0;
                leg2_tot = leg2_tot * 2;
                var flag = $(v).attr('data-per-traveller');
                var val_rt = $(v).val();
                var charge_rt = $(v).parents('.counter-block').find('.luggage_charge_rt').attr('data-value');
                if(flag==1) {
                    // console.log(val_rt+'  --  '+leg2_tot);
                    if(val_rt <= leg2_tot) {
                        charge_rt = 0;
                     } else {
                        val_rt = val_rt - leg2_tot;
                     }
                 }
                total += val_rt * charge_rt;
            })
            $('.is_pet_available_rt').each(function(k,v) {
                if ($(v).is(":checked")){
                    total += parseFloat($(v).val());
                }

            })
            $('#total_amount').text('$'+total.toFixed(2));
        }
        var modalConfirm = function(callback) {  
            $("#sameAsAboveModal").modal('show');
            $("#modal-btn-si").on("click", function(){
                callback(true);
                $("#sameAsAboveModal").modal('hide');
            });
        
            $("#modal-btn-no").on("click", function(){
                callback(false);
                $("#sameAsAboveModal").modal('hide');
            });
        };
        $(document).on('click', '#sameAsAbove', function() {
            modalConfirm(function(confirm){
                if(confirm) {
                    $('.luggage_dropdown').each(function() {
                        var id = $(this).attr('id').replace('luggage_dropdown', 'luggage_dropdown_rt');
                        $('#' + id).val($(this).val()).trigger('change');
                    })
                    $('.is_pet_available').each(function() {
                        var id = $(this).attr('id').replace('checkbox', 'checkbox_rt');
                        if($(this).prop("checked") == true) {
                            $('#' + id).prop('checked', true).trigger('change');
                        } else {
                            $('#' + id).prop('checked', false).trigger('change');
                        }                        
                    })
                    
                }
            });
        });

        $(document).on('submit', '#frontend_luggage_animals', function (e) {

            e.preventDefault();
            if (!form_valid('#frontend_luggage_animals')) {
                $('#redirect_url').val($('.dropdown-content-rocket .nav-link.active').next('a').attr('data-action'));
                return false;
            } else {
                var action = $(this).attr('action');

                var data = $('#frontend_luggage_animals').serialize()

                $.post(action, data, function (response) {
                    if (response.status == 'TRUE') {
                        window.location.href = response.redirect_url;
                    } else {
                        response = $.parseJSON(response);
                        $(response).each(function (i, val) {
                            $.each(val, function (key, v) {
                                $('#' + key).closest('.form-group').addClass('is-invalid');
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

    var handleTravelDetails = function () {

        $(document).on('change', '.travel_type', function(){

            var pick_type_text = $('option:selected', $(this).closest('.rocket-info-travel-box').find('.pick_type')).attr('data-title');
            var drop_type_text = $('option:selected', $(this).closest('.rocket-info-travel-box').find('.drop_type')).attr('data-title');
           
            var time_text = "[TYPE] [TIME] time is:";
            var target_pick_drop_text = "The [TIME] time I am comfortable targeting [PICK_DROP] at [TYPE] is:";
            var shared_service_balance_text = "To make the shared service balance based on reservations on this day, I may need to target [DEP_ARR] [TIME]:";
            
            var travels = ['Airport','Greyhound','Amtrak','Cruise Pier','Cruise Hotel'];
            
            var switch_text = pick_type_text;
            var switch_type = "Pick";

            if(travels.indexOf(pick_type_text) != -1 && travels.indexOf(drop_type_text) == -1) {
                switch_text = pick_type_text;
            } else if(travels.indexOf(pick_type_text) == -1 && travels.indexOf(drop_type_text) != -1) {
                switch_text = drop_type_text;
                switch_type = 'Drop';
            }

            if(pick_type_text == 'Airport' && drop_type_text != 'Airport') {
                switch_text = pick_type_text;
                switch_type = 'Pick';
            } else if(pick_type_text != 'Airport' && drop_type_text == 'Airport') {
                switch_text = drop_type_text;
                switch_type = 'Drop';
            }

            if(typeof pick_type_text == 'undefined') {
                switch_text = drop_type_text;
                switch_type = 'Drop';
            }

            $(this).closest('.rocket-info-travel-box').find('.flight-num-sec').show(); 

            switch (switch_text) {
                case 'Airport':
                    $(this).closest('.rocket-info-travel-box').find('.flight-no-text').text('Flight No.');
                    time_text = time_text.replace('[TYPE]','Flight');
                    target_pick_drop_text = target_pick_drop_text.replace('[TYPE]','Airport');  
                    break;
                case 'Greyhound':
                    $(this).closest('.rocket-info-travel-box').find('.flight-no-text').text('Bus No.');
                    time_text = time_text.replace('[TYPE]','Bus');
                    target_pick_drop_text = target_pick_drop_text.replace('[TYPE]','Bus');
                    break;
                case 'Amtrak':
                    $(this).closest('.rocket-info-travel-box').find('.flight-no-text').text('Train No.');
                    time_text = time_text.replace('[TYPE]','Train');
                    target_pick_drop_text = target_pick_drop_text.replace('[TYPE]','Train');
                    break;
                case 'Cruise Pier':
                case 'Cruise Hotel':
                    $(this).closest('.rocket-info-travel-box').find('.flight-num-sec').hide();
                    time_text = time_text.replace('[TYPE]','Cruise');                        
                    target_pick_drop_text = target_pick_drop_text.replace('[TYPE]','Cruise');
                    break;
                case 'Hotel': 
                    $(this).closest('.rocket-info-travel-box').find('.flight-num-sec').hide();
                    time_text = time_text.replace('[TYPE]','Hotel');                       
                    target_pick_drop_text = target_pick_drop_text.replace('[TYPE]','Hotel');
                    break;
                case 'Meet Point':     
                    $(this).closest('.rocket-info-travel-box').find('.flight-num-sec').hide();  
                    time_text = time_text.replace('[TYPE]','Meet point');                  
                    target_pick_drop_text = target_pick_drop_text.replace('[TYPE]','Meet Point');
                    break;
                case 'Primary Location':     
                    $(this).closest('.rocket-info-travel-box').find('.flight-num-sec').hide();  
                    time_text = time_text.replace('[TYPE]','Primary Location');                  
                    target_pick_drop_text = target_pick_drop_text.replace('[TYPE]','Primary Location');
                    break;
                case 'Alternate Location':     
                    $(this).closest('.rocket-info-travel-box').find('.flight-num-sec').hide();  
                    time_text = time_text.replace('[TYPE]','Alternate Location');                  
                    target_pick_drop_text = target_pick_drop_text.replace('[TYPE]','Alternate Location');
                    break;
            }

            var timepicker_id = $(this).closest('.rocket-info-travel-box').find('.kt_timepicker1_comfort').attr('id');

            if(switch_type == 'Pick'){
                localStorage.setItem(timepicker_id+"_travel_direction", "Pick");
                time_text = time_text.replace('[TIME]','arrival');
                target_pick_drop_text = target_pick_drop_text.replace('[TIME]','earliest');
                target_pick_drop_text = target_pick_drop_text.replace('[PICK_DROP]','pickup');
                shared_service_balance_text = shared_service_balance_text.replace('[DEP_ARR]','departure'); 
                shared_service_balance_text = shared_service_balance_text.replace('[TIME]','as late as'); 
            } else {
                localStorage.setItem(timepicker_id+"_travel_direction", "Drop");
                time_text = time_text.replace('[TIME]','departure');
                target_pick_drop_text = target_pick_drop_text.replace('[TIME]','latest');
                target_pick_drop_text = target_pick_drop_text.replace('[PICK_DROP]','dropoff');
                shared_service_balance_text = shared_service_balance_text.replace('[DEP_ARR]','arrival');
                shared_service_balance_text = shared_service_balance_text.replace('[TIME]','as early as');
            }

            $(this).closest('.rocket-info-travel-box').find('.start-time-text').text(time_text);
            $(this).closest('.rocket-info-travel-box').find('.target-pick-drop-text').text(target_pick_drop_text);
            $(this).closest('.rocket-info-travel-box').find('.shared-service-balance-text').text(shared_service_balance_text);

            if(switch_text == "Airport") {
                $(this).closest('.rocket-info-travel-box').find('.airport-flight-box').removeClass('d-none');
            } else {
                $(this).closest('.rocket-info-travel-box').find('.airport-flight-box').addClass('d-none');
            }
        });

        /* $(document).on("change", ".travel_points, .travel_points_rt", function() {
            if ($(this).hasClass('travel_points_rt')) {
                if($('#rt_i_origin_point_id').val() != '' && $('#rt_i_destination_point_id').val() != '') {
                    var data = {pickup_point_rt: $('#rt_i_origin_point_id').val(), 'dropoff_point_rt' : $('#rt_i_destination_point_id').val()};
                    $.post(SITE_URL+'update-fare-details', data, function (response) {
                        if (response.status == 'TRUE') {
                            $('.show_sub_total').html(response.total);
                            $('.show_total_total').html(response.total);
                        }
                    });
                }
            } else {
                if($('#i_origin_point_id').val() != '' && $('#i_destination_point_id').val() != '') {
                    var data = {pickup_point: $('#i_origin_point_id').val(), 'dropoff_point' : $('#i_destination_point_id').val()};
                    $.post(SITE_URL+'update-fare-details', data, function (response) {
                        if (response.status == 'TRUE') {
                            $('.show_sub_total').html(response.total);
                            $('.show_total_total').html(response.total);
                        }
                    });
                }
            }
        }); */

        $(document).on('submit', '#frontend_travel_details', function (e) {

            e.preventDefault();
            if (!form_valid('#frontend_travel_details')) {
                $('#redirect_url').val($('.dropdown-content-rocket .nav-link.active').next('a').attr('data-action'));
                return false;
            } else {
                var action = $(this).attr('action');

                var data = $('#frontend_travel_details').serialize()

                $.post(action, data, function (response) {
                    if (response.status == 'TRUE') {
                        window.location.href = response.redirect_url;
                    } else {
                        response = $.parseJSON(response);
                        $(response).each(function (i, val) {
                            $.each(val, function (key, v) {
                                $('#' + key).closest('.form-group').addClass('is-invalid');                               
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

    var handleConfrimLineRuns = function() {
        
        $(document).on('click','.confirm_line_run', function() {
            $('.confirm_line_run').removeClass('active');
            $(this).addClass('active');
            $('.departure_data').val($(this).attr('rel'));
        });
        
        $(document).on('click','.confirm_line_run_rt', function() {
            $('.confirm_line_run_rt').removeClass('active');
            $(this).addClass('active');
            $('.return_data').val($(this).attr('rel'));
             
        });

        $(document).on('click','.call_request_btn', function() {
            var action = $(this).attr('action');
            $('#redirect_type').val('call_request');
            var data = $('#frontend_confrim_line_runs').serialize()
            
            $.post(action, data, function (response) {
                if (response.status == 'TRUE') {
                    window.location.href = response.redirect_url;
                }
            });
             
        });

        $(document).on('submit', '#frontend_confrim_line_runs', function (e) {

            e.preventDefault();
            if (!form_valid('#frontend_confrim_line_runs')) {
                $('#redirect_url').val($('.dropdown-content-rocket .nav-link.active').next('a').attr('data-action'));
                return false;
            } else {
                var action = $(this).attr('action');

                var data = $('#frontend_confrim_line_runs').serialize()
                
                $.post(action, data, function (response) {
                    if (response.status == 'TRUE') {
                        window.location.href = response.redirect_url;
                    }
                });
            }
        });
    }

    var handlePaymentInfo = function() {
        $(document).on('click', '#cash_on_board', function() {
            if($(this).is(':checked')) {
                $('#frontend_payment').find('input[type=text],select').removeClass('required');
                $('#frontend_payment .form-group').removeClass('is-invalid');
            } else {
                $('#frontend_payment').find('input[type=text],select').addClass('required');
            }
        })

        $(document).on('submit', '#frontend_payment', function (e) {
            $('.custom-error').hide();
            e.preventDefault();
            if (!form_valid('#frontend_payment')) {
                return false;
            } else {
                var action = $(this).attr('action');

                var data = $('#frontend_payment').serialize()
                $('.btn-pay').html('<i class="fa fa-refresh fa-spin"></i> Processing');

                $.post(action, data, function (response) {
                    $('.btn-pay').html('submit');
                    if (response.status == 'TRUE') {
                        
                        window.location.href = response.redirect_url;
                    } else if(response.status == 'FALSE') {
                        //Stripe Error
                        $('.custom-error .alert-text').html(response.message);
                        $('.custom-error').show().fadeOut(8000);
                    } else {
                        response = $.parseJSON(response);
                        $(response).each(function (i, val) {
                            $.each(val, function (key, v) {
                                $('#' + key).closest('.form-group').addClass('is-invalid');                               
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
            handleSidebar();
        },
        locationInformation: function () {
            handleLocationInformation();
        },
        selectLineRuns: function () {
            handleSelectLineRuns();
        },
        passengerInformation: function () {
            handlePassengerInformation();
        },
        luggageAnimals: function () {
            handleLuggageAnimals();
        },
        travelDetails: function () {
            handleTravelDetails();
        },
        confirmLineRuns: function () {
            handleConfrimLineRuns();
        },
        paymentInfo: function () {
            handlePaymentInfo();
        }
    };
}();

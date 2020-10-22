var timeoutID;
var email_flag = true; // for email unique validation

function nl2br(str, is_xhtml) {
    var breakTag = (is_xhtml || typeof is_xhtml === undefined) ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

function rtrim(str, lastChar) {
    if (str.substring(str.length - 1) == lastChar) {
        str = str.substring(0, str.length - 1);
    }
    return str;
}

function strstr(haystack, needle, bool) {
    var pos = 0;

    haystack += "";
    pos = haystack.indexOf(needle);
    if (pos == -1) {
        return false;
    } else {
        if (bool) {
            return haystack.substr(0, pos);
        } else {
            return haystack.slice(pos);
        }
    }
}

$(document).ready(function() {

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "50000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    if ($('.alert').length) {
        setTimeout(function() {
            $(".alert").fadeOut(3000);
        }, 5000);
    }

    $(document).ajaxError(function(event, request, settings) {
        if (request.responseText === 'Unauthorized.') {
            window.location = SITE_URL;
        }
    });

    var save_and_continue_flag = false;
    $("#frmAddNewSubmit").click(function() {
        save_and_continue_flag = true
    });

    $("#frmAdd").submit(function() {
        var form = $("#frmAdd");
        form.find('.duplicate-error').hide();
        form.find(".form-group").removeClass('is-invalid');

        if (form_valid("#frmAdd")) {
            var curObj = $(this);

            if (save_and_continue_flag) {
                curObj.find('#frmAddNewSubmit').addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);
            } else {
                curObj.find('button[type=submit]').addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);
                curObj.find('#frmAddNewSubmit').removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
            }

            $('textarea.ckeditor').each(function() {
                var textarea = $(this);
                textarea.val(editor.getData());
            })

            if ($('#frmAdd_passenger_info').length > 0) {
                var name_array = [];
                var dob_array = [];
                var type_array = [];
                $('.traveller_name').each(function(k, v) {
                    // var traveller_name_string = $(this).val() + ', ';
                    name_array.push($(this).val());
                });

                $('.birth_month_year').each(function(k, v) {
                    dob_array.push($(this).val());
                });

                $('.passanger_type').each(function(k, v) {
                    type_array.push($(this).val());
                });


                $('#v_traveller_name').val(name_array);
                $('#d_birth_month_year').val(dob_array);
                $('#e_type').val(type_array);

            }

            if ($('#frmAdd_passenger_info_rt').length > 0) {
                var name_array_rt = [];
                var dob_array_rt = [];
                var type_array_rt = [];
                $('.traveller_name_rt').each(function(k, v) {
                    // var traveller_name_string = $(this).val() + ', ';
                    name_array_rt.push($(this).val());
                });

                $('.birth_month_year_rt').each(function(k, v) {
                    dob_array_rt.push($(this).val());
                });

                $('.passanger_type_rt').each(function(k, v) {
                    type_array_rt.push($(this).val());
                });

                $('#v_traveller_name_rt').val(name_array_rt);
                $('#d_birth_month_year_rt').val(dob_array_rt);
                $('#e_type_rt').val(type_array_rt);

            }

            var send_data = $("#frmAdd").serialize();


            $.post($("#frmAdd").attr("action"), send_data, function(data) {

                if (save_and_continue_flag) {
                    curObj.find('#frmAddNewSubmit').removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                } else {
                    curObj.find('button[type=submit]').removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                }
                if ($.trim(data) == '') {
                    $('#d_departure_time_error').hide();
                    $('#d_run_date_error').hide();
                    $('#i_num_available_error').hide();
                    if (save_and_continue_flag) {
                        save_and_continue_flag = false;
                        window.location.href = window.location.href;
                    } else {
                        save_and_continue_flag = false;
                        window.location.href = (window.location.href).replace('/add', '');
                    }
                    // window.location.href = (window.location.href).replace('/add', '');

                } else if ($.trim(data) == 'DATE_MATCH') {
                    $('#d_departure_time_error').show();
                } else if ($.trim(data) == 'GENERATE_LINE_RUN') {
                    window.location.href = (window.location.href).replace('/generate-line-run', '');
                } else if ($.trim(data) == 'SAME_DATE_MESSAGE_ADD') {
                    $('#d_run_date_error').show();
                    $('#d_run_date_error').removeAttr('style', true);
                } else if ($.trim(data) == 'BOOKABLE_SEAT_LESS_TOTAL_SEAT') {
                    $('#i_num_available_error').show();
                    $('#i_num_available_error').removeAttr('style', true);
                } else if ($.trim(data) == 'SAME_DATE_MESSAGE') {
                    swal.fire({
                        title: 'Alredy Added Line Run Records.',
                        text: '',
                        type: 'warning',
                        showCancelButton: false,
                        cancelButtonText: 'Ok',
                    });
                    return false;
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

    $("#frmEdit").submit(function() {

        var form = $("#frmEdit");
        form.find('.duplicate-error').hide();
        form.find(".form-group").removeClass('is-invalid');

        if (form_valid("#frmEdit")) {
            var curObj = $(this);

            curObj.find('button[type=submit]').addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            $('textarea.ckeditor').each(function() {
                var textarea = $(this);
                textarea.val(editor.getData());
            });

            if ($('#frmEdit_passenger_info').length > 0) {
                var name_array = [];
                var dob_array = [];
                var type_array = [];
                var id_array = [];
                $('.traveller_name').each(function(k, v) {
                    // var traveller_name_string = $(this).val() + ', ';
                    name_array.push($(this).val());
                });

                $('.birth_month_year').each(function(k, v) {
                    dob_array.push($(this).val());
                });

                $('.passanger_type').each(function(k, v) {
                    console.log($(this).val());
                    type_array.push($(this).val());
                });

                $('.passanger_data_id').each(function(k, v) {

                    id_array.push($(this).val());
                });

                $('#v_traveller_name').val(name_array);
                $('#d_birth_month_year').val(dob_array);
                $('#e_type').val(type_array);
                $('#passanger_reservation_id').val(id_array);
            }

            if ($('#frmEdit_passenger_info_rt').length > 0) {
                var name_array_rt = [];
                var dob_array_rt = [];
                var type_array_rt = [];
                var id_array_rt = [];
                $('.traveller_name_rt').each(function(k, v) {
                    // var traveller_name_string = $(this).val() + ', ';
                    name_array_rt.push($(this).val());
                });

                $('.birth_month_year_rt').each(function(k, v) {
                    dob_array_rt.push($(this).val());
                });

                $('.passanger_type_rt').each(function(k, v) {
                    type_array_rt.push($(this).val());
                });

                $('.passanger_data_id_rt').each(function(k, v) {

                    id_array_rt.push($(this).val());
                });

                $('#v_traveller_name_rt').val(name_array_rt);
                $('#d_birth_month_year_rt').val(dob_array_rt);
                $('#e_type_rt').val(type_array_rt);
                $('#passanger_reservation_id_rt').val(id_array_rt);
            }

            var send_data = form.serialize();


            $.post(form.attr("action"), send_data, function(data) {
                curObj.find('button[type=submit]').removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);

                if ($.trim(data) == '') {
                    $('#d_departure_time_error').hide();
                    $('#d_run_date_error').hide();
                    $('#e_status_active_error').hide();
                    $('#e_status_inactive_error').hide();
                    $('#i_num_available_error').hide();
                    if ($("#redirect_url").length == 1) {
                        window.location.href = $("#redirect_url").val();
                    } else {
                        window.location = strstr($("#frmEdit").attr("action"), '/edit/', true);
                    }

                } else if ($.trim(data) == 'DATE_MATCH') {
                    $('#d_departure_time_error').show();
                } else if ($.trim(data) == 'SAME_DATE_MESSAGE_ADD') {
                    $('#d_run_date_error').show();
                    $('#d_run_date_error').removeAttr('style', true);
                } else if ($.trim(data) == 'RESERVATION_VIEW_RECORD_ADD') {
                    var edited_record_id = $('#view_reservation_record_update_id').val();
                    window.location = ADMIN_URL + 'reservations/view/' + edited_record_id;
                    swal.fire({
                        title: 'Reservations information has been successfully added.',
                        text: '',
                        type: 'success',
                    });
                } else if ($.trim(data) == 'PREVENT_EXPIRE_STATUS') {
                    var status_error = $(".e_status option:selected").text();
                    if (status_error == 'Active') {
                        $('#e_status_inactive_error').hide();
                        $('#e_status_active_error').show();
                        $('#e_status_active_error').removeAttr('style', true);
                    } else {
                        $('#e_status_active_error').hide();
                        $('#e_status_inactive_error').show();
                        $('#e_status_inactive_error').removeAttr('style', true);
                    }
                } else if ($.trim(data) == 'BOOKABLE_SEAT_LESS_TOTAL_SEAT') {
                    $('#i_num_available_error').show();
                    $('#i_num_available_error').removeAttr('style', true);
                } else if(data.refund_process!==undefined && data.refund_process==1){
                    if(data.status=='FALSE') {
                        swal.fire({
                            title: data.message,
                            text: 'Error occurred in processing refund',
                            type: 'warning',
                        });
                    } else {
                        var edited_record_id = $('#view_reservation_record_update_id').val();
                        window.location = ADMIN_URL + 'reservations/view/' + edited_record_id;
                        swal.fire({
                            title: data.message,
                            text: '',
                            type: 'success',
                        });
                    }
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

        return false;
    });
    $("#frmEditFleetSpecifications").submit(function() {
        var form = $("#frmEditFleetSpecifications");
        form.find('.duplicate-error').hide();
        form.find(".form-group").removeClass('is-invalid');

        if (form_valid("#frmEditFleetSpecifications")) {
            var curObj = $(this);

            curObj.find('button[type=submit]').addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            $('textarea.ckeditor').each(function() {
                var textarea = $(this);
                textarea.val(editor.getData());
            });

            var send_data = form.serialize();


            $.post(form.attr("action"), send_data, function(data) {
                curObj.find('button[type=submit]').removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);

                if ($.trim(data) == '') {

                    toastr.success('Fleet Specification Information has been saved successfully.');
                    $("html, body").animate({
                        scrollTop: 0
                    }, 600);

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

        return false;
    });

    $("#update_profile").submit(function() {
        var form = $("#update_profile");
        form.find('.duplicate-error').hide();
        form.find(".form-group").removeClass('is-invalid');

        if (form_valid("#update_profile")) {
            var curObj = $(this);

            curObj.find('button[type=submit]').addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            var send_data = form.serialize();


            $.post(form.attr("action"), send_data, function(data) {

                curObj.find('button[type=submit]').removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);

                if ($.trim(data) == '') {
                    window.location.reload();
                } else {
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

        return false;
    });

    $("#kt_tabs_1_1").find('.kt-portlet__foot .banner-submit-btn').on('click', function(e) {
        var form = $("#home_page_1");
        form.find('.duplicate-error').hide();
        form.find(".form-group").removeClass('is-invalid');
        var flag = 0;

        if (form_valid("#home_page_1")) {
            var curObj = $(this);

            curObj.find('button[type=submit]').addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            var send_data = form.serialize();


            $.post(form.attr("action"), send_data, function(data) {
                curObj.find('button[type=submit]').removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);

                if ($.trim(data) == '') {

                    $('#kt_tabs_1_1').addClass('active');
                    $('#kt_tabs_1_1').trigger('click');
                    $('#kt_tabs_1_2').removeClass('active');
                    $('#kt_tabs_1_3').removeClass('active');
                    flag = 1;

                    if (flag == 1) {
                        window.location.assign(ADMIN_URL + 'home-page-content#kt_tabs_1_1');
                        window.location.reload();
                    }

                    $('html, body').animate({
                        scrollTop: $('#home_page_1').first().offset().top - 200
                    }, 1000);
                } else {
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

        return false;

    });

    $("#kt_tabs_1_2").find('.kt-portlet__foot .service-submit-btn').on('click', function(e) {
        var form = $("#home_page_2");
        form.find('.duplicate-error').hide();
        form.find(".form-group").removeClass('is-invalid');
        var flag = 0;

        if (form_valid("#home_page_2")) {
            var curObj = $(this);

            curObj.find('button[type=submit]').addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            var send_data = form.serialize();


            $.post(form.attr("action"), send_data, function(data) {

                curObj.find('button[type=submit]').removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);

                if ($.trim(data) == '') {

                    $('#kt_tabs_1_1').removeClass('active');
                    $('#kt_tabs_1_2').addClass('active');
                    $('#kt_tabs_1_2').trigger('click');
                    $('#kt_tabs_1_3').removeClass('active');
                    flag = 1;

                    if (flag == 1) {
                        window.location.assign(ADMIN_URL + 'home-page-content#kt_tabs_1_2');
                        window.location.reload();
                    }

                    $('html, body').animate({
                        scrollTop: $('#home_page_2').first().offset().top - 200
                    }, 1000);
                } else {
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

        return false;
    });

    $("#kt_tabs_1_3").find('.kt-portlet__foot .footer-submit-btn').on('click', function(e) {
        var form = $("#home_page_3");
        form.find('.duplicate-error').hide();
        form.find(".form-group").removeClass('is-invalid');
        var flag = 0;

        if (form_valid("#home_page_3")) {
            var curObj = $(this);

            curObj.find('button[type=submit]').addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            var send_data = form.serialize();


            $.post(form.attr("action"), send_data, function(data) {

                curObj.find('button[type=submit]').removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);

                if ($.trim(data) == '') {

                    $('#kt_tabs_1_1').removeClass('active');
                    $('#kt_tabs_1_2').removeClass('active');
                    $('#kt_tabs_1_3').addClass('active');
                    $('#kt_tabs_1_3').trigger('click');
                    flag = 1;

                    if (flag == 1) {
                        // setTimeout(function(){
                        window.location.assign(ADMIN_URL + 'home-page-content#kt_tabs_1_3');
                        window.location.reload();
                        // },1000);
                    }

                    $('html, body').animate({
                        scrollTop: $('#home_page_3').first().offset().top - 200
                    }, 1000);
                } else {
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

        return false;
    });

    $("#frmAdd_cust_reservation").submit(function() {
        console.log("..............");
        var form = $("#frmAdd_cust_reservation");
        form.find('.duplicate-error').hide();
        form.find(".form-group").removeClass('is-invalid');
        var flag = 0;

        if (form_valid("#frmAdd_cust_reservation")) {
            var curObj = $(this);
            curObj.find('button[type=submit]').addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

        }

        $('textarea.ckeditor').each(function() {
            var textarea = $(this);
            textarea.val(editor.getData());
        })

        if ($('#frmAdd_passenger_info').length > 0) {
            var name_array = [];
            var dob_array = [];
            var type_array = [];
            $('.traveller_name').each(function(k, v) {
                // var traveller_name_string = $(this).val() + ', ';	
                name_array.push($(this).val());
            });
            $('.birth_month_year').each(function(k, v) {
                dob_array.push($(this).val());
            });
            $('.passanger_type').each(function(k, v) {
                type_array.push($(this).val());
            });
            $('#v_traveller_name').val(name_array);
            $('#d_birth_month_year').val(dob_array);
            $('#e_type').val(type_array);
        }
        if ($('#frmAdd_passenger_info_rt').length > 0) {
            var name_array_rt = [];
            var dob_array_rt = [];
            var type_array_rt = [];
            $('.traveller_name_rt').each(function(k, v) {
                // var traveller_name_string = $(this).val() + ', ';
                name_array_rt.push($(this).val());
            });

            $('.birth_month_year_rt').each(function(k, v) {
                dob_array_rt.push($(this).val());
            });

            $('.passanger_type_rt').each(function(k, v) {
                type_array_rt.push($(this).val());
            });

            $('#v_traveller_name_rt').val(name_array_rt);
            $('#d_birth_month_year_rt').val(dob_array_rt);
            $('#e_type_rt').val(type_array_rt);
        }

        var send_data = $("#frmAdd_cust_reservation").serialize();


        $.post($("#frmAdd_cust_reservation").attr("action"), send_data, function(data) {
            /* console.log("..............222222", $.trim(data));
            curObj.find('button[type=submit]').removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false); */

            if ($.trim(data) == '') {
                $('#d_departure_time_error').hide();

                $('#kt_tabs_personal_info').removeClass('active');
                // $('#kt_tabs_personal_address').removeClass('active');
                // $('#kt_tabs_card_info').removeClass('active');
                $('#kt_tabs_reservation').addClass('active');
                $('#kt_tabs_reservation').trigger('click');
                flag = 1;

                if (flag == 1) {
                    var url = (window.location.href).replace('reservations/add/customer', 'customers/edit');
                    window.location.href = url + '#kt_tabs_reservation';
                }

            } else if ($.trim(data) == 'DATE_MATCH') {
                $('#d_departure_time_error').show();
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

        if ($('.discount_value').attr('style') != undefined) {
            setTimeout(function() {
                $('.required-radio-discount').find('.help-block').remove();
            }, 100);
        }

        return false;
    });

    $("#frmEdit_cust_reservation").submit(function() {
        var form = $("#frmEdit_cust_reservation");
        form.find('.duplicate-error').hide();
        form.find(".form-group").removeClass('is-invalid');

        if (form_valid("#frmEdit_cust_reservation")) {
            var curObj = $(this);

            curObj.find('button[type=submit]').addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            $('textarea.ckeditor').each(function() {
                var textarea = $(this);
                textarea.val(editor.getData());
            });

            if ($('#frmEdit_passenger_info').length > 0) {
                var name_array = [];
                var dob_array = [];
                var type_array = [];
                var id_array = [];
                $('.traveller_name').each(function(k, v) {
                    // var traveller_name_string = $(this).val() + ', ';	
                    name_array.push($(this).val());
                });
                $('.birth_month_year').each(function(k, v) {
                    dob_array.push($(this).val());
                });
                $('.passanger_type').each(function(k, v) {
                    type_array.push($(this).val());
                });
                $('.passanger_data_id').each(function(k, v) {
                    id_array.push($(this).val());
                });

                $('#v_traveller_name').val(name_array);
                $('#d_birth_month_year').val(dob_array);
                $('#e_type').val(type_array);
                $('#passanger_reservation_id').val(id_array);
            }

            if ($('#frmEdit_passenger_info_rt').length > 0) {
                var name_array_rt = [];
                var dob_array_rt = [];
                var type_array_rt = [];
                var id_array_rt = [];
                $('.traveller_name_rt').each(function(k, v) {
                    // var traveller_name_string = $(this).val() + ', ';
                    name_array_rt.push($(this).val());
                });

                $('.birth_month_year_rt').each(function(k, v) {
                    dob_array_rt.push($(this).val());
                });

                $('.passanger_type_rt').each(function(k, v) {
                    type_array_rt.push($(this).val());
                });

                $('.passanger_data_id_rt').each(function(k, v) {

                    id_array_rt.push($(this).val());
                });

                $('#v_traveller_name_rt').val(name_array_rt);
                $('#d_birth_month_year_rt').val(dob_array_rt);
                $('#e_type_rt').val(type_array_rt);
                $('#passanger_reservation_id_rt').val(id_array_rt);
            }

            var send_data = form.serialize();


            $.post(form.attr("action"), send_data, function(data) {
                curObj.find('button[type=submit]').removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                console.log(data);
                if ($.trim(data) == '') {
                    $('#d_departure_time_error').hide();
                    if ($("#redirect_url").length == 1) {
                        window.location.href = $("#redirect_url").val();
                    } else {
                        // window.location = strstr($("#frmEdit_cust_reservation").attr("action"), '/edit/', true);
                        $('#kt_tabs_personal_info').removeClass('active');
                        // $('#kt_tabs_personal_address').removeClass('active');
                        // $('#kt_tabs_card_info').removeClass('active');
                        $('#kt_tabs_reservation').addClass('active');
                        $('#kt_tabs_reservation').trigger('click');
                        flag = 1;

                        if (flag == 1) {
                            var cust_id = $('#customer_id_reservation').val();
                            // console.log(cust_id);
                            window.location.href = ADMIN_URL + 'customers/edit/' + cust_id + '#kt_tabs_reservation';
                        }
                    }

                } else if ($.trim(data) == 'DATE_MATCH') {
                    $('#d_departure_time_error').show();
                } else if ($.trim(data) == 'Success_add') {
                    console.log("...................Success_add.............");
                    var cust_id = $('#customer_id_reservation').val();
                    console.log(cust_id + '----' + ADMIN_URL + 'customers/edit/' + cust_id + '#kt_tabs_reservation');

                    window.location.href = ADMIN_URL + 'customers/edit/' + cust_id + '#kt_tabs_reservation';
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

        return false;
    });
});
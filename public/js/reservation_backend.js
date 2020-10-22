$(document).ready(function() {

    if ($("body").hasClass('add-reservation-frm-page')) {
        $('.kt_time_picker').val('');
    }

    var date = new Date();

    $('.date_picker_dob').datepicker({
        format: 'mm/dd/yyyy',
        autoclose: true,
        //orientation: "bottom auto",
        endDate: date,
        todayHighlight: true,
    });

    $('.date_picker_depart').datepicker({
        format: 'mm/dd/yyyy',
        autoclose: true,
        //orientation: "bottom auto",
        startDate: date,
        todayHighlight: true,
    }).on('changeDate', function(selected) {
        var minDate = new Date(selected.date.valueOf());
        $('.date_picker_return').datepicker('setStartDate', minDate);
    })

    $('.date_picker_return').datepicker({
        format: 'mm/dd/yyyy',
        autoclose: true,
        //orientation: "bottom auto",
        todayHighlight: true,
    }).on('changeDate', function(selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('.date_picker_depart').datepicker('setEndDate', maxDate);
    })

    classType();

});
var city_id = '';

setTimeout(function() {
    $("#i_origin_point_id").select2({
        tags: true,
        // tokenSeparators: [",", " "],
        delay: 250,
        ajax: {
            url: ADMIN_URL + "get-service-point-list",
            dataType: "json",

            data: function(term, page) {
                return {
                    q: term
                };
            },
            processResults: function(data) {
                return {
                    results: data
                };
            }
        },
    }).on("change", function() {
        city_id = $('#i_origin_point_id option:selected').val();
    });
}, 3000);

/* $(document).on('keydown', '.select2-selection', function(evt) {
    $("#i_destination_point_id").select2();
}); */

$("#i_destination_point_id").select2({
    tags: true,
    // tokenSeparators: [',', ' '],
    delay: 250,
    ajax: {
        url: ADMIN_URL + "get-service-destination-point-list",
        dataType: "json",
        data: function(term, page) {
            return {
                q: term,
                city_id: city_id
            };
        },
        processResults: function(data) {
            return {
                results: data
            };
        }
    },
});

setTimeout(function() {
    $("#i_oneway_airline_id").select2({
        tags: true,
        tokenSeparators: [",", " "],
        delay: 250,
        ajax: {
            url: ADMIN_URL + "get-airline-list",
            dataType: "json",

            data: function(term, page) {
                return {
                    q: term
                };
            },
            processResults: function(data) {
                return {
                    results: data
                };
            }
        },
    });
}, 3000);

setTimeout(function() {
    $("#i_return_airline_id").select2({
        tags: true,
        tokenSeparators: [",", " "],
        delay: 250,
        ajax: {
            url: ADMIN_URL + "get-airline-list",
            dataType: "json",

            data: function(term, page) {
                return {
                    q: term
                };
            },
            processResults: function(data) {
                return {
                    results: data
                };
            }
        },
    });
}, 3000);

$('.e_class_type').on('change', function() {
    classType();
});

$('.i_customer_id').on('change', function() {
    reservation_customer_name = $('.i_customer_id option:selected').text();
    $('.reservation_customer_name').val(reservation_customer_name);
});

$('.i_reservation_category_id').on('change', function() {

    reservation_cat_name = $('.i_reservation_category_id option:selected').text();
    $('.i_reservation_category_name').val(reservation_cat_name);


    /* if (reservation_cat_name == 'Airport') {

        $('.flight_time').removeClass('d-none');
        $('.flight_number').removeClass('d-none');


        $('.flight_type_airport').removeClass('d-none');
        $(".airline_info_field").removeClass("d-none");
        $(".i_oneway_airline_id").addClass("required");
        // $(".return-airline-info").removeAttr("style", true);
    } else if (reservation_cat_name == 'Amtrak') {

        $('.flight_time').removeClass('d-none');
        $('.flight_number').removeClass('d-none');

        $('.flight_type_airport').addClass('d-none');
        $(".airline_info_field").addClass('d-none');
        $(".i_oneway_airline_id").removeClass("required");
        $(".return-airline-info").addClass('d-none');
    } else if (reservation_cat_name == 'Cruise') {
        $('.flight_time').removeClass('d-none');
        $('.flight_number').removeClass('d-none');
        $('.flight_type_airport').addClass('d-none');
        $(".airline_info_field").addClass('d-none');
        $(".i_oneway_airline_id").removeClass("required");
        $(".return-airline-info").addClass('d-none');
    } else if (reservation_cat_name == 'Greyhound') {
        $('.flight_time').removeClass('d-none');
        $('.flight_number').removeClass('d-none');
        $('.flight_type_airport').addClass('d-none');
        $(".airline_info_field").addClass('d-none');
        $(".i_oneway_airline_id").removeClass("required");
        $(".return-airline-info").addClass('d-none');
    } else {

        $('.flight_time').addClass('d-none');
        $('.flight_number').addClass('d-none');
        $('.t_flight_time').removeClass("required");
        $('.v_flight_number').removeClass("required");
        $('.flight_type_airport').addClass('d-none');
        $(".airline_info_field").addClass('d-none');
        $(".i_oneway_airline_id").removeClass("required");
        $(".return-airline-info").addClass('d-none');
    } */
});
// Round trip reservation category condition
/* $('.i_reservation_category_id_round_trip').on('change', function() {
        reservation_cat_name_round_trip = $('.i_reservation_category_id_round_trip option:selected').text();
        $('.i_reservation_category_name_round_trip').val(reservation_cat_name_round_trip);

        if (reservation_cat_name_round_trip == 'Airport') {

            $('.flight_time_round_trip').removeClass('d-none');
            $('.flight_number_round_trip').removeClass('d-none');
            $('.flight_type_airport_round_trip').removeClass('d-none');

        } else if (reservation_cat_name_round_trip == 'Amtrak') {

            $('.flight_time_round_trip').removeClass('d-none');
            $('.flight_number_round_trip').removeClass('d-none');
            $('.flight_type_airport_round_trip').addClass('d-none');
        } else if (reservation_cat_name_round_trip == 'Cruise') {
            $('.flight_time_round_trip').removeClass('d-none');
            $('.flight_number_round_trip').removeClass('d-none');
            $('.flight_type_airport_round_trip').addClass('d-none');
        } else if (reservation_cat_name_round_trip == 'Greyhound') {
            $('.flight_time_round_trip').removeClass('d-none');
            $('.flight_number_round_trip').removeClass('d-none');
            $('.flight_type_airport_round_trip').addClass('d-none');
        } else {
            $('.flight_time_round_trip').addClass('d-none');
            $('.flight_number_round_trip').addClass('d-none');
            //  $('.t_flight_time_round_trip').removeClass("required");
            //  $('.v_flight_number_round_trip').removeClass("required");
            $('.flight_type_airport_round_trip').addClass('d-none');
        }
}) */
// End Round trip reservation category condition

$('.d_return_date').on('change', function() {
    return_date = $(this).val();
    $('.return_date_rt').text(return_date);
});

$('.frm_submit_btn').on('click', function() {
    // debugger;

    $('#passenger_info_popup, #passenger_info_popup_rt').click(function() {

        if ($(this).hasClass('passenger_info_popup_rt')) {
            addFields('rt');
            $('#kt_modal_passenger_info_rt').modal('hide');
        } else {
            addFields();
            $('#kt_modal_passenger_info').modal('hide');
        }
    });

    var passenger_name = $('.traveller_name').val();
    var passenger_dob = $('.birth_month_year').val();
    var passenger_type = $('.passanger_type').val();
    if ($('.e_class_type:checked').val() == 'RT' || $('.e_class_type').text().trim() == "Round Trip") {
        var passenger_name_rt = $('.traveller_name_rt').val();
        var passenger_dob_rt = $('.birth_month_year_rt').val();
        var passenger_type_rt = $('.passanger_type_rt').val();
        console.log(passenger_type_rt);

        if (passenger_name_rt == '' || passenger_name_rt == undefined || passenger_dob_rt == '' || passenger_dob_rt == undefined || passenger_type_rt == '' || passenger_type_rt == undefined) {
            swal.fire({
                title: 'Please Add Return Passenger Informations.',
                text: '',
                type: 'warning',
                showCancelButton: false,
                cancelButtonText: 'Ok',
            });
            return false;
        }
    }
    if (passenger_name == '' || passenger_dob == '' || passenger_type == '' || passenger_name == undefined || passenger_dob == undefined || passenger_type == undefined) {
        // alert("Please enter passenger informations..");
        swal.fire({
            title: 'Please add passenger informations.',
            text: '',
            type: 'warning',
            showCancelButton: false,
            cancelButtonText: 'Ok',
        });
        return false;
    }

});

$("#total_passenger,#total_passenger_rt").on('change', function() {
    var is_rt = $(this).hasClass('i_total_num_passengers_rt');
    setTimeout(function() {
        if (is_rt) {
            addInfoLink('rt');
        } else {
            addInfoLink();
        }
    }, 1000);
});

$("#luggages_info_popup, #luggages_info_popup_rt").on('click', function() {
    var is_rt = $(this).hasClass('luggages_info_popup_rt');

    setTimeout(function() {
        // debugger;
        if (is_rt) {
            luggageInfoPopup('rt');
        } else {
            luggageInfoPopup();
        }
    }, 1000);
});


$("#popup_luggages_table,#popup_luggages_table_rt").on("change", "select.luggage_dropdown,select.luggage_dropdown_rt", function() {
    //debugger;
    if ($(this).hasClass('luggage_dropdown_rt')) {
        passangerLuggage('rt', $(this));
    } else {
        passangerLuggage('', $(this));
    }
});

var pet_amt = 0;

$(".is_pet_available, .is_pet_available_rt").on("change", function() {
    if ($(this).hasClass('is_pet_available_rt')) {
        isPetAvailable('rt', $(this));
    } else {
        isPetAvailable('', $(this));
    }
});

function addFields(is_rt) {
    // debugger;
    var return_trip_id = '';
    if (is_rt !== undefined && is_rt == 'rt') {
        return_trip_id = '_rt';
    }
    setTimeout(function() {
        var date = new Date();
        $('.date_picker_dob' + return_trip_id).datepicker({
            format: 'mm/dd/yyyy',
            autoclose: true,
            //orientation: "bottom auto",
            endDate: date,
            todayHighlight: true,
        });
    }, 1000);

    var hidden_popup_value = $('#popup_value' + return_trip_id).val();
    console.log("hidden_popup_value", hidden_popup_value);
    var total_number_passenger = $("#total_passenger" + return_trip_id).val();
    console.log("total_number_passenger", total_number_passenger);

    // if (hidden_popup_value == 0 || hidden_popup_value == total_number_passenger) {
    if (hidden_popup_value == total_number_passenger) {
        $('#kt_modal_passenger_info' + return_trip_id).modal('show');
        return false;
    }

    if (hidden_popup_value == 0) {
        console.log("total_number_passenger" + total_number_passenger);
        $('#popup_value' + return_trip_id).val(total_number_passenger);
        var html = '';

        html += '<table class="table table-bordered table-hover table-checkable" id="popup_table' + return_trip_id + '">';
        html += '<thead><tr>';
        html += '<th>No.</th><th>Name</th><th>Date of Birth</th><th>Type</th>';
        html += '</tr> </thead>';
        html += '<tbody>';
        for (i = 0; i < total_number_passenger; i++) {
            html += '<tr>';
            html += '<td>' + (i + 1) + '</td>';
            if (return_trip_id == '_rt') {
                html += '<td><input type="text" class="form-control traveller_name_rt" name="v_traveller_name_rt[]" placeholder="Name"></td>';
                html += '<td><input type="text" class="form-control date_picker_dob_rt birth_month_year_rt" name="d_birth_month_year_rt[]" placeholder="Date of Birth"></td>';
                html += '<td><select class="form-control passanger_type_rt" name="e_type_rt[]" placeholder="Type"><option value="">Select</option><option value="Adult">Adult</option> <option value="Senior">Senior</option> <option value="Military">Military</option> <option value="Child">Child</option> <option value="Infant">Infant</option> </select></td>';
            } else {
                html += '<td><input type="text" class="form-control traveller_name" name="v_traveller_name[]" placeholder="Name"></td>';
                html += '<td><input type="text" class="form-control date_picker_dob birth_month_year" name="d_birth_month_year[]" placeholder="Date of Birth"></td>';
                html += '<td><select class="form-control passanger_type" name="e_type[]" placeholder="Type"><option value="">Select</option><option value="Adult">Adult</option> <option value="Senior">Senior</option> <option value="Military">Military</option> <option value="Child">Child</option> <option value="Infant">Infant</option> </select></td>';
            }
            html += '</tr>';
        }
        html += '</tbody>';
        html += '</table>';
        $('#container_passenger' + return_trip_id).html(html);

        $('#kt_modal_passenger_info' + return_trip_id).modal('show');
    } else if (hidden_popup_value > total_number_passenger || hidden_popup_value < total_number_passenger) {
        swal.fire({
            title: 'your data will be removed',
            text: '',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ok',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then(function(result) {
            if (result.dismiss === 'cancel') {
                $("#total_passenger" + return_trip_id).val(hidden_popup_value);
            } else {
                $('#popup_value' + return_trip_id).val('0');
                $(".edit_passenger_table_informations" + return_trip_id).attr('style', 'display:none');
                $('.after_changed_passenger_num' + return_trip_id).attr('id', 'container_passenger' + return_trip_id);
                $(".edit_passenger_table_informations" + return_trip_id).empty();
                if (return_trip_id == '_rt') {
                    addFields('rt');
                } else {
                    addFields();
                }
            }
        });
    }
}

function addInfoLink(is_rt) {
    var return_trip_id = '';
    if (is_rt !== undefined && is_rt == 'rt') {
        return_trip_id = '_rt';
    }
    var total_passenger = $("#total_passenger" + return_trip_id).val();
    console.log("total_passenger", total_passenger);
    if (total_passenger == '') {
        $('.passenger_info_popup' + return_trip_id).attr('style', 'display:none');
    } else {
        $('.passenger_info_popup' + return_trip_id).removeAttr('style', true);
    }
}

function classType() {

    if ($('.e_class_type').text().trim() == "Round Trip" || $('.e_class_type:checked').val() == 'RT') {
        $('.return_trip_fields').removeAttr('disabled');
        $('.v_return_info_rt').removeAttr('readonly');
        $('.return_reservation_detail').removeAttr('style', true);
        $('.rt_required_fields').addClass('required');
        $('.t_comfortable_time_rt').addClass('required');
        $('.t_target_time_rt').addClass('required');
        $('.i_reservation_category_id_round_trip').addClass('required');
        $('.e_shuttle_type_rt').addClass('required');
    } else {
        $('.return_trip_fields').attr("disabled", true);
        $('.return_trip_fields').val('');
        $('.return_reservation_detail').attr('style', 'display:none');
        $('.rt_required_fields').removeClass('required');
        $('.t_comfortable_time_rt').removeClass('required');
        $('.t_target_time_rt').removeClass('required');
        $('.i_reservation_category_id_round_trip').removeClass('required');
        $('.e_shuttle_type_rt').removeClass('required');
    }
}

function passangerLuggage(is_rt, luggage_dropdown) {
    // debugger;
    var return_trip_id = '';
    if (is_rt !== undefined && is_rt == 'rt') {
        return_trip_id = '_rt';
    }
    var passanger_total_luggage = 0;
    var dropdown_value = $(luggage_dropdown).val();
    var luggage_charge_value = $(luggage_dropdown).parent().next().find(".luggage_charge" + return_trip_id).val();
    if (luggage_charge_value == 'FREE') {
        luggage_charge_value1 = 0;
    } else {

        luggage_charge_value1 = luggage_charge_value.split('$ ').pop().split(' EACH')[0];
    }
    var total_lugg_amt = dropdown_value * luggage_charge_value1;
    $(luggage_dropdown).parents('tr').find(".total_fare_amt" + return_trip_id).val(total_lugg_amt);

    // debugger;
    $("select.luggage_dropdown" + return_trip_id).each(function() {
        // if ($(luggage_dropdown).val() == '' || $(luggage_dropdown).val() == undefined) {
        //     passanger_total_luggage = passanger_total_luggage;
        // } else {
        //     passanger_total_luggage = passanger_total_luggage + parseInt($(luggage_dropdown).val());
        // }
        if ($(this).val() == '' || $(this).val() == undefined) {
            passanger_total_luggage = passanger_total_luggage;
        } else {
            passanger_total_luggage = passanger_total_luggage + parseInt($(this).val());
        }
        $('#total_luggages' + return_trip_id).val(passanger_total_luggage);
    });

    passanger_total_luggage = passanger_total_luggage;
}

function luggageInfoPopup(is_rt) {
    // debugger;
    var return_trip_id = '';
    if (is_rt !== undefined && is_rt == 'rt') {
        return_trip_id = '_rt';
    }
    var total_passengers = $("#total_passenger" + return_trip_id).val();
    var length_dropdown = $('.personel_luggage_info' + return_trip_id + '  > option').length;
    if ($('.personel_luggage_info' + return_trip_id).length > 0 && total_passengers != (length_dropdown - 1)) {
        // $(".personel_luggage_info option" + return_trip_id).remove();
        $(".personel_luggage_info" + return_trip_id + " option").remove();
        var html = '';
        html += '<option value="">Select</option>';
        for (i = 0; i <= total_passengers; i++) {
            html += '<option value="' + i + '">' + i + '</option>';
        }
        $('.personel_luggage_info' + return_trip_id).append(html);
    }
}

function isPetAvailable(is_rt, is_pet_available) {
    // debugger;
    var return_trip_id = '';
    if (is_rt !== undefined && is_rt == 'rt') {
        return_trip_id = '_rt';
    }
    var pet_charge_value = $(is_pet_available).parent().next().find(".pet_charge" + return_trip_id).val();
    pet_amt = pet_charge_value.split('$ ').pop().split(' EACH')[0];

    if ($(is_pet_available).prop("checked") == true) {
        $(is_pet_available).parents('tr').find('.total_fare_amt_pet' + return_trip_id).val(pet_amt);
    } else {
        $(is_pet_available).parents('tr').find('.total_fare_amt_pet' + return_trip_id).val('0');
    }

    $('#tatal_pets' + return_trip_id).val($(".is_pet_available" + return_trip_id + ":checked").length);
}
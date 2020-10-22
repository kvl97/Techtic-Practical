// Class definition

var KTBootstrapTimepicker = function() {

    // Private functions
    var demos = function() {
        // minimum setup
        $('.kt_timepicker_1, .kt_timepicker_1_modal').timepicker({
            minuteStep: 1,
            defaultTime: '',
        });

        // minimum setup
        $('.kt_timepicker_2, .kt_timepicker_2_modal').timepicker({
            minuteStep: 1,
            defaultTime: '',
            showSeconds: true,
            showMeridian: false,
            snapToStep: true
        });

        // default time
        $('.kt_timepicker_3, .kt_timepicker_3_modal').timepicker({
            defaultTime: '11:45:20 AM',
            minuteStep: 1,
            showSeconds: true,
            showMeridian: true
        });

        // default time
        $('.kt_timepicker_4, .kt_timepicker_4_modal').timepicker({
            defaultTime: '10:30:20 AM',
            minuteStep: 1,
            showSeconds: true,
            showMeridian: true
        });

        // validation state demos
        // minimum setup
        $('.kt_timepicker_1_validate, .kt_timepicker_2_validate, .kt_timepicker_3_validate').timepicker({
            minuteStep: 15,
            showSeconds: false,
            showMeridian: false,
            snapToStep: true
        });

        $('.kt_time_picker').timepicker({
            minuteStep: 1,
            defaultTime: "",
            showSeconds: 0,
            showMeridian: !1,
            snapToStep: !0
        });
        // $('.kt_time_picker').timepicker();
    }

    return {
        // public functions
        init: function() {
            demos();
        }
    };
}();

jQuery(document).ready(function() {
    KTBootstrapTimepicker.init();
});
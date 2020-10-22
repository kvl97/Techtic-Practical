


$(document).ready(function () {

    //kiosk information
    $(".current_run_date").html($('.slick-active').find('#hidden_run_date').val());
    $('.slick-next').on("click", function(){
        console.log("test test");
        var run_date_val = $('.slick-active').find('#hidden_run_date').val();
        $(".current_run_date").html(run_date_val);
    });

});
"use strict";

// Class Definition
var KTFrontend = function() {
    
    var handleContactUsForm = function(){ 
        $(document).on('submit','#contactUsFrm',function(e){
            e.preventDefault();
            if(!form_valid('#contactUsFrm')) {
                return false;
            }else {
                var action =  $(this).attr('action');
                var data = $('#contactUsFrm').serialize()
                $.post(action,data,function(response) {
                    if(response.status == 'TRUE') {
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
        init: function() {
            handleContactUsForm();
        }
    };
}();

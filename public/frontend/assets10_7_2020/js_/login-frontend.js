"use strict";

// Class Definition
var KTLoginFrontend = function() {

    var showErrorMsg = function(form, type, msg, error_class){
       var errorDiv = $(error_class);
       errorDiv.find('.message').text(msg);
       errorDiv.show();
    }

    var handleSignInFormSubmit = function() {
       
        $(document).on('submit','#loginFrm',function(event){
            event.preventDefault();
            if(!form_valid('#loginFrm')) {
                return false;
            } else {
                    var action =  $(this).attr('action');
                    var data = $('#loginFrm').serialize()
                    var form = $(this).closest('form');
                    $.post(action,data,function(response) {
                        if(response.status == 'TRUE') {
                            window.location.href = response.redirect_url;
                        } else {
                            setTimeout(function() {
                                showErrorMsg(form, 'danger', response.message,'.login-form-customer .invalid-error-message');
                            }, 500);
                        }
                    });
                
            }
        });
    }
    var handleForgetPassword = function() {
        $(document).on('submit','#forgot_form',function(e){
            e.preventDefault();
            if(!form_valid('#forgot_form')) {
                return false;
            } else {
                var action =  $(this).attr('action');
                var data = $('#forgot_form').serialize()
                var form = $(this).closest('form');
                $.post(action,data,function(response) {
                   
                    if(response.status == 'TRUE') {
                       
                        window.location.href = response.redirect_url;
                    } else {
                        setTimeout(function() {
                            showErrorMsg(form, 'danger', response.message);
                        }, 500);
                    }
                });
            }
        }); 

        jQuery('#forget-password').click(function() {
            jQuery('.login-form-customer').hide();
            jQuery('.forget-form-customer').show();
            jQuery('.forget-form-customer').removeClass("d-none");
           
        });

        jQuery('#back-btn').click(function() {
            jQuery('.login-form-customer').show();
            jQuery('.forget-form-customer').hide();
            jQuery('.forget-form-customer').addClass("d-none");
           
        });
        
    }
    var handleRegistetrInFormSubmit =function(){

        $(document).on('submit','#register_form',function(e){
            e.preventDefault();
            if(!form_valid('#register_form')) {
                return false;
            }else {
                var action =  $(this).attr('action');
                var data = $('#register_form').serialize()
                var form = $(this).closest('form');
                $.post(action,data,function(response) {
                    if(response.status == 'TRUE') {
                        window.location.href = response.redirect_url;
                    } else {
                        console.log(response);
                        response = $.parseJSON(response);
                        $(response).each(function (i, val) {
                            $.each(val, function (key, v) {
                                console.log(key);
                                console.log(v);
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
    var handResetePassword = function(){ 

        $(document).on('submit','#reset_password_form',function(e){
            e.preventDefault();
            if(!form_valid('#reset_password_form')) {
                return false;
            }else {
                var action =  $(this).attr('action');
                var data = $('#reset_password_form').serialize()
                $.post(action,data,function(response) {
                    if(response.status == 'TRUE') {
                        window.location.href = response.redirect_url;
                    } else {
                        setTimeout(function() {
                            showErrorMsg(form, 'danger', response.message);
                        }, 500);
                    }
                });
            }
        });

    }


    

    // Public Functions
    return {
        // public functions
        init: function() {
            
            handleSignInFormSubmit();
            handleRegistetrInFormSubmit(); 
            handleForgetPassword();
            handResetePassword();
          
            
        }
    };
}();

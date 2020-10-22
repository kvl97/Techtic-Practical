"use strict";

// Class Definition
var KTReservationFrontend = function() {

   

  
    var handleLocaltionInformation =function(){

        $(document).on('submit','#frontend_location_information',function(e){
         /*    console.log("123");
            return false; */
            e.preventDefault();
            if(!form_valid('#frontend_location_information')) {
                return false;
            }else {
                var action =  $(this).attr('action');
                
                var data = $('#frontend_location_information').serialize()
                var form = $(this).closest('form');
               /*  console.log(data);
                return false; */
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
    
    var handleSelectLineRuns = function(){
        
        $(document).on('submit', '#frontend_linerun_info', function (e) {
           e.preventDefault();
           if(!form_valid('#frontend_linerun_info')) {
            return false;
           } else {
                var action =  $(this).attr('action');
                var data = $('#frontend_linerun_info').serialize()
                
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

    var handlePassengerInformation = function(){

        $(document).on('submit','#frontend_passengers_information',function(e){
            /*    console.log("123");
               return false; */
               e.preventDefault();
               if(!form_valid('#frontend_passengers_information')) {
                   return false;
               }else {
                   var action =  $(this).attr('action');
                   
                   var data = $('#frontend_passengers_information').serialize()
                   //var form = $(this).closest('form');
                   /* console.log(data);
                   return false; */
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
    var handleLuggageAnimals = function(){

        $(document).on('submit','#frontend_luggage_animals',function(e){
            /*    console.log("123");
               return false; */
               e.preventDefault();
               if(!form_valid('#frontend_luggage_animals')) {
                   return false;
               }else {
                   var action =  $(this).attr('action');
                   
                   var data = $('#frontend_luggage_animals').serialize()
                   //var form = $(this).closest('form');
                   /* console.log(data);
                   return false; */
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
            
            handleLocaltionInformation();
            handleSelectLineRuns();
            handlePassengerInformation();
            handleLuggageAnimals();

            
        }
    };
}();

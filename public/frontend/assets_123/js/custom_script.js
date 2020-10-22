

$(document).ready(function() {

    $(".coll_exp_outgroup").select2();
        let optgroupState = {};
        
        $("body").on('click', '.select2-container--open .select2-results__group', function() {
        $(this).siblings().toggle();
        let id = $(this).closest('.select2-results__options').attr('id');
        let index = $('.select2-results__group').index(this);
        optgroupState[id][index] = !optgroupState[id][index];
        })

    $(".coll_exp_outgroup").on('select2:open', function() {
        $('.select2-dropdown--below').css('opacity', 0);
        setTimeout(() => {
            let groups = $('.select2-container--open .select2-results__group');
            let id = $('.select2-results__options').attr('id');
            if (!optgroupState[id]) {
            optgroupState[id] = {};
            }
            $.each(groups, (index, v) => {
                optgroupState[id][index] = optgroupState[id][index] || false;
                optgroupState[id][index] ? $(v).siblings().show() : $(v).siblings().hide();
            })
            $('.select2-dropdown--below').css('opacity', 1);
        }, 0);
    });

    
    if ($('.custom-option').length > 0){
        $('select:not(.multiselect)').select2();
    }
    $('.custom-select').select2({
        dropdownParent: $('#quote-popup')
    }).addClass("my-custom-select");

    //kiosk information
    $(".current_run_date").html($('.slick-active').find('#hidden_run_date').val());

    $('.slick-next').on("click", function() {
        // console.log("test test");
        var run_date_val = $('.slick-active').find('#hidden_run_date').val();
        $(".current_run_date").html(run_date_val);
    });
    

    //header section 
    $('input[type=radio][name=radio-group]').change(function() {
        
        if (this.value == 'Round Trip') {
            $('#pic_location').removeClass('d-none');
            $('#drop_location').removeClass('d-none');
        }else {
            $('#pic_location').addClass('d-none');
            $('#drop_location').addClass('d-none');
        }
    });

    $('#from_pickup_location').on('change', function(){
      
            var value = $(this).val();
            $('#to_dropoff_location').val(value);
            $('#to_dropoff_location').trigger('change');
    });

    $('#from_dropoff_location').on('change', function(){
        
        var value = $(this).val();
        $('#to_pickup_location').val(value);
        $('#to_pickup_location').trigger('change');
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
			}
		} else {
			if (curVal < 2) {
				return false
			} else {
				var ChangedVal = curVal - 01;
				ele.attr('value', ChangedVal);
				ele.val(ChangedVal)
			}
        }
    });
   
    $('.click_for_quote').on("click",function(){
        DynamicAmoutcalc();
    });
    //dynamic get fer ammount 
    var DynamicAmoutcalc  = function() {

        var pic_up_service_area_id = $('option:selected', '#from_pickup_location').attr('service_area');
        var drpoOff_service_area_id = $('option:selected', '#from_dropoff_location').attr('service_area');
        var way = $('input[type=radio][name=radio-group]:checked').val()
        var numbers = $('input[type=number][name=people]').val()
        
        if(typeof drpoOff_service_area_id != "undefined" && typeof pic_up_service_area_id != "undefined"  ){
            $.ajax({
                url : SITE_URL + "amount",
                method: 'POST',
                data: {'origin_service_area_id':pic_up_service_area_id,'dest_service_area_id':drpoOff_service_area_id,'rate':way,'number_of_people':numbers},
                success: function (data) {
                    var resultData = JSON.parse(data);

                    if(resultData.status == 'TRUE') {
                       
                        if(resultData.fare_table_info != null) {
                            var save = '<button id="btnAddProfile"  class="btn btn-md btn-yellow mt-3 mt-sm-0 "" type="button">$'+resultData.total_amount+'</button>';
                            $(".ammount").html(save);
                        }else {
                            var save = '<button id="btnAddProfile"  class="btn btn-md btn-yellow mt-3 mt-sm-0 "" type="button">$0</button>';
                            $(".ammount").html(save);
                        }
                    }else {
                        
                    }
                    
                }
            });
        }else {
          
        }

    

    }
    
    //disebele drop off location value
    $('#from_pickup_location').on('change', function(){
        var service_area_id = $('option:selected', this).attr('service_area');
       
        $("#from_dropoff_location option").each(function() {
            
            if($(this).attr('service_area') == service_area_id) {
               
                $(this).attr("disabled","disabled");
            }else{
                $(this).removeAttr("disabled");
            }
        });
    });

    
    // index page 
    $('input[type=radio][name=radio-groups]').change(function() {
        if (this.value == 'Round Trip') {
            $('#departure').removeClass('d-none');
        }else{
            $('#departure').addClass('d-none');
            
        }
    });
    $('#home_pickup_location').on('change', function(){
        var value = $(this).val();
        $('#home_to_dropoff_location').val(value);
        $('#home_to_dropoff_location').trigger('change');
    })
    $('#home_dropoff_location').on('change', function(){
        var value = $(this).val();
        $('#home_to_pickup_location').val(value);
        $('#home_to_pickup_location').trigger('change');
    })

    $('.details_quote').on("click",function(){
        var pickup_location = $('#from_pickup_location').val();
        var drop_location = $('#from_dropoff_location').val();
        var round_trip_drop_location = $('#to_dropoff_location').val();
        var round_trip_pickup_location = $('#to_pickup_location').val();
        var trip_status = $('input[type=radio][name=radio-group]:checked').val();
        var total_number_of_people = $('input[type=number][name=people]').val();
        $.ajax({
            url : SITE_URL + "detail-fare-quote",
            method: 'POST',
            data: {'pickup_location':pickup_location,'drop_location':drop_location,'round_trip_drop_location':round_trip_drop_location,'round_trip_pickup_location':round_trip_pickup_location,'trip_status':trip_status,'number_of_people':total_number_of_people},
            success: function (data) {
                var response = JSON.parse(data);
                if(response.status == 'TRUE') {
                    window.location.href = response.redirect_url;
                }
            }
        })
    });

    
    

});

// Class Definition
var KTFrontend = function() {

    var handleContactUsForm = function() {
        $(document).on('submit', '#contactUsFrm', function(e) {
            e.preventDefault();
            if (!form_valid('#contactUsFrm')) {
                return false;
            } else {
                var action = $(this).attr('action');
                var data = $('#contactUsFrm').serialize()
                $.post(action, data, function(response) {
                    if (response.status == 'TRUE') {
                        window.location.href = response.redirect_url;
                    } else {
                        response = $.parseJSON(response);
                        $(response).each(function(i, val) {
                            $.each(val, function(key, v) {
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
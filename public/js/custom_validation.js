var err_element = 'div';
var err_class = 'invalid-feedback';
var swipe_status = 0;
var parse_status = 0;
var keycode = 0;

$(document).ready(function() {

    if ($("#kt-ckeditor-5").length > 0) {
        editor.ui.focusTracker.on('change:isFocused', function(evt, name, isFocused) {
            if (!isFocused) {
                // Do whatever you want with current editor data:
                $("#kt-ckeditor-5").val(editor.getData());
                if ($("#kt-ckeditor-5").val() != '') { $("#kt-ckeditor-5_error").remove(); } else {}
            } else {
                $("#kt-ckeditor-5").val(editor.getData());
                if ($("#kt-ckeditor-5").val() != '') { $("#kt-ckeditor-5_error").remove(); }
            }
        });
    }

    $('input,select,textarea').on('blur', function(event) {
        
        var current_obj = $(this);
        var field_value = $.trim(current_obj.val());
        var placeholder = '';
        if (current_obj.attr('placeholder') !== undefined) {
            placeholder = current_obj.attr('placeholder');
        } else if (current_obj.attr('err-msg') !== undefined) {
            placeholder = current_obj.attr('err-msg');
        }

        if ($(this).hasClass('ignore_required_onfocus')) {
            return true;
        }
        current_obj.closest('div.form-group').find('.' + err_class).remove();
        current_obj.closest('div.form-group').removeClass('is-invalid');

        var err_element_start = '<' + err_element + ' id="' + this.id + '_error" class="help-block ' + err_class + '">';
        var err_element_end = '.</' + err_element + '>';
        var error_msg = '';
        flag = true;

        if (current_obj.hasClass('email') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            var pattern = new RegExp(/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/igm);
            if (!pattern.test(field_value)) {
                error_msg = 'Please enter valid ' + placeholder.toLowerCase();
                flag = false;
            }
        }

        if (current_obj.hasClass('max') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            error_msg = 'maximum ' + current_obj.attr('maxlength') + ' characters are allowed';
            flag = false;
        }
        if (current_obj.hasClass('min') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            error_msg = 'min ' + current_obj.attr('maxlength') + ' characters are required';
            flag = false;
        }

        var attr_obj = current_obj.attr('max-slide');
        if (typeof attr_obj !== typeof undefined && attr_obj !== false && field_value != "" && field_value !== undefined && field_value != placeholder && $(attr_obj).val() != '') {
            if (parseInt($(attr_obj).val()) < parseInt(field_value)) {
                error_msg = 'Chart slides start from must be less than or equal to chart slides end at';
                flag = false;
            }
        }

        var attr_obj = current_obj.attr('min-slide');
        if (typeof attr_obj !== typeof undefined && attr_obj !== false && field_value != "" && field_value !== undefined && field_value != placeholder && $(attr_obj).val() != '') {
            if (parseInt($(attr_obj).val()) > parseInt(field_value)) {
                error_msg = 'Chart slides end to must be greater than or equal to chart slides start from';
                flag = false;
            }
        }

        if (current_obj.hasClass('url') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            var pattern = new RegExp(/((([A-Za-z]{3,9}:(?:\/\/)?)(?:[-;:&=\+\$,\w]+@)?[A-Za-z0-9.-]+|(?:www.|[-;:&=\+\$,\w]+@)[A-Za-z0-9.-]+)((?:\/[\+~%\/.\w-_]*)?\??(?:[-\+=&;%@.\w_]*)#?(?:[\w]*))?)/);
            if (!pattern.test(field_value)) {
                error_msg = 'Please enter valid URL';
                flag = false;
            }
        }

        if (current_obj.hasClass('digits') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            if (!(field_value.match(/^\d+$/))) {
                error_msg = 'Please enter valid digits';
                flag = false;
            }
        }

        if (current_obj.hasClass('number_decimal') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            if (!(field_value.match(/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/))) {
                error_msg = 'Please enter valid number';
                flag = false;
            }
        }

        if (current_obj.hasClass('number') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            // if (!(field_value.match(/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/))) {
            //     error_msg = 'Please enter valid number';                
            //     flag = false;
            // } 
            if (!(field_value.match(/^[- +()]*[0-9][- +()0-9]*$/))) {
                error_msg = 'Please enter valid number';
                flag = false;
            }
        }
        if (current_obj.hasClass("alphanumeric") && field_value != "" && field_value !== undefined && field_value != placeholder) {
            if (!field_value.match(/^[A-Za-z0-9]+$/)) {
                error_msg = "Please enter valid " + placeholder.toLowerCase();
                flag = false
            }
        }

        if (current_obj.hasClass("email_ids") && field_value != "" && field_value !== undefined && field_value != placeholder) {
            if (!field_value.match(/^^(([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5}){1,25})+([,.](([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5}){1,25})+)*$/)) {
                error_msg = "Please enter valid " + placeholder.toLowerCase() + ". Email address should be seprated by comma(,) only.";
                flag = false
            }
        }
        if (current_obj.hasClass('percentage') && field_value != "" && field_value !== undefined && field_value != placeholder) {

            if (!(field_value.match(/^(^(100{1,1}$)|^(100{1,1}\.[0]+?$))|(^([0]*\d{0,2}$)|^([0]*\d{0,2}\.(([0][1-9]{1,1}[0]*)|([1-9]{1,1}[0]*)|([0]*)|([1-9]{1,2}[0]*)))$)$/))) {
                error_msg = 'Please enter valid percentage amount';
                flag = false;
            }

            /*var x = parseFloat(field_value);
            if (isNaN(x) || x < 0 || x > 100) {
                error_msg = 'Please enter valid percentage amount';                
                flag = false;
            }*/
        }

        if (current_obj.hasClass('max-value') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            if (parseFloat(field_value) > parseFloat(current_obj.attr('max-value'))) {
                error_msg = 'Amount must be less than or equal to max purchase price';
                flag = false;
            }
        }

        var attr_obj = current_obj.attr('maxlength');
        if (typeof attr_obj !== typeof undefined && attr_obj !== false && field_value != "" && field_value !== undefined && field_value != placeholder) {

            if (field_value.length > attr_obj) {
                error_msg = 'Maximum characters length is ' + attr_obj + '.';
                flag = false;
            }
        }

        if ($(this).hasClass('max') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            error_msg = 'maximum ' + $(this).attr('maxlength') + ' characters are allowed';
            flag = false;
        }
        if ($(this).hasClass('min') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            error_msg = 'min ' + $(this).attr('maxlength') + ' characters are required';
            flag = false;
        }


        if (current_obj.hasClass('validate_zip') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            if (!(field_value.match(/^[a-z][0-9][a-z]\-s*?[0-9][a-z][0-9]$/i) || field_value.match(/^[a-z][0-9][a-z]\s*?[0-9][a-z][0-9]$/i))) {
                error_msg = 'Please enter valid ' + placeholder.toLowerCase();
                flag = false;
            }
        }

        if (current_obj.hasClass('validate_creditcard') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            var pattern = new RegExp(/^\d{15,16}$/);
            if (!pattern.test(field_value)) {
                error_msg = 'Please enter valid ' + placeholder.toLowerCase();
                flag = false;
            }
        }

        if (current_obj.hasClass('validate_month') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            var re = /^[0-9]{1,2}$/;
            if (!re.test(field_value)) {
                error_msg = 'Please enter valid ' + placeholder.toLowerCase();
                flag = false;
            }
        }
        if (current_obj.hasClass('validate_current_year') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            var re = /^[0-9]{4}$/;
            var currentYear = (new Date).getFullYear();

            if (!re.test(field_value) || parseInt(field_value) < parseInt(currentYear)) {
                error_msg = 'Please enter valid ' + placeholder.toLowerCase();
                flag = false;
            }
        }
        if (current_obj.hasClass('validate_year') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            var re = /^[0-9]{4}$/;

            if (!re.test(field_value)) {
                error_msg = 'Please enter valid ' + placeholder.toLowerCase();
                flag = false;
            }
        }
        if (current_obj.hasClass('validate_cvccode') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            var re = /^[0-9]{3}$/;
            if (!re.test(field_value)) {
                error_msg = 'Please enter valid ' + placeholder.toLowerCase();
                flag = false;
            }
        }

        if (current_obj.attr('equalTo') !== undefined) {
            if ($.trim($("#" + current_obj.attr('equalTo')).val()) != field_value) {
                error_msg = 'The password and confirm password do not match';
                flag = false;
            }
        }

        if (current_obj.hasClass('color-hex') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            var pattern = /^(#)?([0-9a-fA-F]{3})([0-9a-fA-F]{3})?$/;
            if (!pattern.test(field_value)) {
                error_msg = 'Please enter valid color';
                flag = false;
            }
        }

        if (current_obj.hasClass('validate_zero') && current_obj.val() != '' && field_value !== undefined && field_value != placeholder) {
            if (field_value < 1) {
                error_msg = 'Must be greater than 0';
                flag = false;
            }
        }



        if (this.id == 'error_msgs' && $('#error_msgs').hasClass('required')) {
            flag = false;
            error_msg = 'Please upload image';
        }

        if (current_obj.hasClass('unique') && current_obj.attr('class').indexOf("duplicate-error") >= 0 && field_value != "" && field_value !== undefined && field_value != placeholder) {
            flag = false;
            error_msg = ucfirst(placeholder) + ' already exist';
        }

        if (current_obj.hasClass('phone') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            var pattern = /^[0-9()\s]+$/; //space and 0-9 number allow
            if (!pattern.test(field_value)) {
                error_msg = 'Please enter valid phone';
                flag = false;
            }
        }
        if (current_obj.hasClass('cell_number') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            var pattern = /^[0-9()\s]+$/; //space and 0-9 number allow
            if (!pattern.test(field_value)) {
                error_msg = 'Please enter valid cell number';
                flag = false;
            }
        }

        if (current_obj.hasClass('check-url-char') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            var pattern = new RegExp(/[a-zA-Z0-9-]/g);
            if (!pattern.test(field_value)) {
                error_msg = 'Please enter valid text';
                flag = false;
            }
        }

        if (current_obj.hasClass('page_url') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            var pattern = new RegExp(/^[A-Za-z0-9_-]+$/);
            if (!pattern.test(field_value)) {
                error_msg = 'Please enter valid ' + placeholder.toLocaleLowerCase();
                flag = false;
            }
        }

        if (current_obj.hasClass('check-youtube-vimeo-url') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            var pattern = new RegExp(/^https:\/\/(?:.*?)\.?(youtube|vimeo)\.com\/(watch\?[^#]*v=(\w+)|(\d+)).+$/);
            var pattern_2 = new RegExp(/https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)/);
            if (!pattern.test(field_value) && !pattern_2.test(field_value)) {
                error_msg = 'Please enter only youtube or vimeo url';
                flag = false;
            }
        }

        if (current_obj.hasClass('required') && (field_value == "" || field_value == undefined || field_value == placeholder)) {

            if (current_obj['0'].tagName !== undefined && current_obj['0'].tagName == 'SELECT') {
                error_msg = 'Please select ' + placeholder.toLowerCase();
            } else if (current_obj.attr('type') !== undefined && current_obj.attr('type') == 'hidden') {
                error_msg = 'Please select ' + placeholder.toLowerCase();
            } else if (current_obj.attr('type') !== undefined && current_obj.attr('type') == 'select') {
                error_msg = 'Please upload file';
            } else if (current_obj.hasClass('date_picker_depart')) {
                error_msg = 'Please select ' + placeholder.toLowerCase();
            } else {
                error_msg = 'Please enter ' + placeholder.toLowerCase();
            }

            flag = false;
        }

        if (current_obj.hasClass('required-least-one') && current_obj.attr('groupid') != "" && current_obj.attr('groupid') != undefined) {
            if ($('input[groupid="' + current_obj.attr('groupid') + '"]:checked').length < 1) {
                error_msg = 'Please select any option';
                flag = false;
            }
        }
        if (current_obj.hasClass('required-least-one-radio') && current_obj.attr('groupid') != "" && current_obj.attr('groupid') != undefined) {
            if ($('input[groupid="' + current_obj.attr('groupid') + '"]:checked').length < 1) {
                error_msg = 'Please select any option';
                flag = false;
                is_check = true;
            }
        }
        if (current_obj.hasClass('required-least-one-check') && current_obj.attr('groupid') != "" && current_obj.attr('groupid') != undefined) {
            if ($('input[groupid="' + current_obj.attr('groupid') + '"]:checked').length < 1) {
                error_msg = 'Please select any option';
                flag = false;
                is_check = true;
            }
        }

        if (!flag && error_msg != '') {
            error_msg = err_element_start + error_msg + err_element_end;
            current_obj.closest('div.form-group').addClass('is-invalid');

            if (current_obj.hasClass('ckeditor')) {
                current_obj.next('div').after(error_msg);
            } else if (current_obj.hasClass('required-least-one-radio')) {
                current_obj.closest('div.form-group').addClass('is-invalid');
                current_obj.closest('div.radio-list').after(error_msg);
                current_obj.closest('div.kt-radio-inline').after(error_msg);
                $('#one_way_error').remove();
                $('#travel_alone_yes_error').remove();
                $('#e_voice_mail_setup_yes_error').remove();
                $('#private_error').remove();
            } else if(current_obj.hasClass('pay-select-fields')){
                current_obj.closest('.select-field').after(error_msg);
            } else {
                current_obj.after(error_msg);
            }

            if (current_obj.closest('form').find('.is-invalid').length > 0) {
                current_obj.closest('form').find('.alert-danger').show();
                current_obj.closest('form').find('.override-error').hide();
                current_obj.closest('form').find('.error-contain').hide();

            } else {

                current_obj.closest('form').find('.alert-danger').hide();
            }

        } else {
            var this_error_obj = current_obj.closest("." + err_class);
            this_error_obj.closest('div.form-group').removeClass('is-invalid');
            this_error_obj.remove();

            if ($("#div_validation_msg").is(':empty')) {
                $("#div_validation_msg").hide();
            }

            if (current_obj.closest('form').find('.is-invalid').length > 0) {
                current_obj.closest('form').find('.alert-danger').show();
                current_obj.closest('form').find('.override-error').hide();
                current_obj.closest('form').find('.error-contain').hide();

            } else {
                current_obj.closest('form').find('.alert-danger').hide();
            }
        }

        return flag;
    });
});


//this function will automaticall append the error msg to next to field
//err_container: If err_container is set, than append all messages to the error container(err_container) element
function form_valid(form, err_container) {

    var flag = true;
    var is_check = false;
    //setTimeout(function(){
    err_container = typeof err_container !== 'undefined' ? err_container : '';
    if (err_container != '') {
        $(err_container).html('');
    }

    $("." + err_class).closest('div.form-group').removeClass('is-invalid');
    $("." + err_class).remove();
    $('#' + this.id + '_error').remove();

    $(form).find('input,select,textarea').each(function() {

        var field_value = $.trim($(this).val());
        var placeholder = '';

        if (err_container != '') {
            $(this).css('border', '1px solid #e5e5e5');
        }

        if ($(this).attr('placeholder') !== undefined) {
            placeholder = $(this).attr('placeholder');
        } else if ($(this).attr('err-msg') !== undefined) {
            placeholder = $(this).attr('err-msg');
        }
        if (this.id == 'error_msgs' && $('#error_msgs').hasClass('required')) {
            flag = false;
            error_msg = 'Please upload image';
        }

        var err_element_start = '<' + err_element + ' id="' + this.id + '_error" class="help-block ' + err_class + '">';
        var err_element_end = '.</' + err_element + '>';
        var error_msg = '';

        if ($(this).hasClass('email') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            var pattern = new RegExp(/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/igm);
            if (!pattern.test(field_value)) {
                error_msg = 'Please enter valid ' + placeholder.toLowerCase();
                flag = false;
            }
        }

        if ($(this).hasClass('url') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            var pattern = new RegExp(/((([A-Za-z]{3,9}:(?:\/\/)?)(?:[-;:&=\+\$,\w]+@)?[A-Za-z0-9.-]+|(?:www.|[-;:&=\+\$,\w]+@)[A-Za-z0-9.-]+)((?:\/[\+~%\/.\w-_]*)?\??(?:[-\+=&;%@.\w_]*)#?(?:[\w]*))?)/);
            if (!pattern.test(field_value)) {
                error_msg = 'Please enter valid URL';
                flag = false;
            }
        }

        if ($(this).hasClass('digits') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            if (!(field_value.match(/^\d+$/))) {
                error_msg = 'Please enter valid digits';
                flag = false;
            }
        }

        if ($(this).hasClass("alphanumeric") && field_value != "" && field_value !== undefined && field_value != placeholder) {
            if (!field_value.match(/^[A-Za-z0-9]+$/)) {
                error_msg = "Please enter valid " + placeholder.toLowerCase();
                flag = false
            }
        }

        if ($(this).hasClass("email_ids") && field_value != "" && field_value !== undefined && field_value != placeholder) {
            if (!field_value.match(/^^(([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5}){1,25})+([,.](([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5}){1,25})+)*$/)) {
                error_msg = "Please enter valid " + placeholder.toLowerCase() + ". Email address should be seprated by comma(,) only.";
                flag = false
            }
        }

        if ($(this).hasClass('percentage') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            var x = parseFloat(field_value);
            if (isNaN(x) || x < 0 || x > 100) {
                error_msg = 'Please enter valid percentage amount';
                flag = false;
            }
        }

        if ($(this).hasClass('max-value') && field_value != "" && field_value !== undefined && field_value != placeholder) {

            if (parseFloat(field_value) > parseFloat($(this).attr('max-value'))) {
                error_msg = 'Amount must be less than or equal to max purchase price';
                flag = false;
            }
        }

        var attr_obj = $(this).attr('maxlength');
        if (typeof attr_obj !== typeof undefined && attr_obj !== false && field_value != "" && field_value !== undefined && field_value != placeholder) {

            if (field_value.length > attr_obj) {
                error_msg = 'Maximum characters length is ' + attr_obj + '.';
                flag = false;
            }
        }

        if ($(this).hasClass('number_decimal') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            if (!(field_value.match(/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/))) {
                error_msg = 'Please enter valid number';
                flag = false;
            }
        }

        if ($(this).hasClass('number') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            /* if (!(field_value.match(/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/))) {
                error_msg = 'Please enter valid number';                
                flag = false;
            } */
            if (!(field_value.match(/^[- +()]*[0-9][- +()0-9]*$/))) {
                error_msg = 'Please enter valid number';
                flag = false;
            }
        }

        if ($(this).hasClass('check-youtube-vimeo-url') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            if (!(field_value.match(/^https:\/\/(?:.*?)\.?(youtube|vimeo)\.com\/(watch\?[^#]*v=(\w+)|(\d+)).+$/)) && !(field_value.match(/https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)/))) {
                error_msg = 'Please enter only youtube or vimeo url';
                flag = false;
            }
        }
        if ($(this).hasClass('validate_zip') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            if (!(field_value.match(/^[a-z][0-9][a-z]\-s*?[0-9][a-z][0-9]$/i) || field_value.match(/^[a-z][0-9][a-z]\s*?[0-9][a-z][0-9]$/i))) {
                error_msg = 'Please enter valid ' + placeholder.toLowerCase();
                flag = false;
            }
        }

        if ($(this).hasClass('validate_creditcard') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            var pattern = new RegExp(/^\d{15,16}$/);
            if (!pattern.test(field_value)) {
                error_msg = 'Please enter valid ' + placeholder.toLowerCase();
                flag = false;
            }
        }


        if ($(this).hasClass('validate_month') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            var re = /^[0-9]{1,2}$/;
            if (!re.test(field_value)) {
                error_msg = 'Please enter valid ' + placeholder.toLowerCase();
                flag = false;
            }
        }

        if ($(this).hasClass('validate_current_year') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            var re = /^[0-9]{4}$/;
            var currentYear = (new Date).getFullYear();

            if (!re.test(field_value) || parseInt(field_value) < parseInt(currentYear)) {
                error_msg = 'Please enter valid ' + placeholder.toLowerCase();
                flag = false;
            }
        }

        if ($(this).hasClass('validate_year') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            var re = /^[0-9]{4}$/;
            if (!re.test(field_value)) {
                error_msg = 'Please enter valid ' + placeholder.toLowerCase();
                flag = false;
            }
        }

        if ($(this).hasClass('validate_cvccode') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            var re = /^[0-9]{3}$/;
            if (!re.test(field_value)) {
                error_msg = 'Please enter valid ' + placeholder.toLowerCase();
                flag = false;
            }
        }

        if ($(this).attr('equalTo') !== undefined) {
            if (field_value != 'Confirm Password' && $.trim($("#" + $(this).attr("equalTo")).val()) != field_value) {
                error_msg = 'The password and confirm password do not match';
                flag = false;
            }
        }

        if ($(this).hasClass('validate_zero') && !$(this).is(":disabled") && $(this).val() != '' && field_value !== undefined && field_value != placeholder) {
            if (field_value < 1) {
                error_msg = 'Must be greater than 0';
                flag = false;
            }
        }

        if ($(this).hasClass('unique') && $(this).attr('class').indexOf("duplicate-error") >= 0 && field_value != "" && field_value !== undefined && field_value != placeholder) {
            error_msg = ucfirst(placeholder) + ' already exist';
            flag = false;
        }

        if ($(this).hasClass('phone') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            var pattern = /^[0-9()\s]+$/; //space and 0-9 number allow
            if (!pattern.test(field_value)) {
                error_msg = 'Please enter valid phone';
                flag = false;
            }
        }
        if ($(this).hasClass('cell_number') && field_value != "" && field_value !== undefined && field_value != placeholder) {
            var pattern = /^[0-9()\s]+$/; //space and 0-9 number allow
            if (!pattern.test(field_value)) {
                error_msg = 'Please enter valid cell number';
                flag = false;
            }
        }

        if ($(this).hasClass('ckeditor')) {
            if (this.id == 'kt-ckeditor-5') {
                $(this).val(editor.getData());
                field_value = $(this).val();
            }
        }

        if ($(this).hasClass('required') && (field_value == "" || field_value == undefined || field_value == placeholder)) {

            if ($(this)['0'].tagName !== undefined && $(this)['0'].tagName == 'SELECT') {
                error_msg = 'Please select ' + placeholder.toLowerCase();
            } else if ($(this).attr('type') !== undefined && $(this).attr('type') == 'file') {
                error_msg = 'Please upload file';
            } else if ($(this).attr('type') !== undefined && $(this).attr('type') == 'hidden') {
                error_msg = 'Please select ' + placeholder.toLowerCase();
            } else if ($(this).hasClass('select_msg')) {
                error_msg = 'Please select ' + placeholder.toLowerCase();
            } else if ($(this).hasClass('date_picker_depart')) {
                error_msg = 'Please select ' + placeholder.toLowerCase();
            } else {
                error_msg = 'Please enter ' + placeholder.toLowerCase();
            }
            flag = false;
        }

        if ($(this).hasClass('required-least-one') && $(this).attr('groupid') != "" && $(this).attr('groupid') != undefined) {
            if ($('input[groupid="' + $(this).attr('groupid') + '"]:checked').length < 1) {
                error_msg = 'Please select any Checkbox';
                flag = false;
                is_check = true;
            }
        }

        if ($(this).hasClass('required-least-one-radio') && $(this).attr('groupid') != "" && $(this).attr('groupid') != undefined) {
            if ($('input[groupid="' + $(this).attr('groupid') + '"]:checked').length < 1) {
                error_msg = 'Please select any option';
                flag = false;
                is_check = true;
            }
        }
        if ($(this).hasClass('required-least-one-check') && $(this).attr('groupid') != "" && $(this).attr('groupid') != undefined) {
            if ($('input[groupid="' + $(this).attr('groupid') + '"]:checked').length < 1) {
                error_msg = 'Please select any option';
                flag = false;
                is_check = true;
            }
        }



        if (!flag && error_msg != '') {
            error_msg = err_element_start + error_msg + err_element_end;
            if (err_container != '') {
                $(this).closest('div.form-group').addClass('is-invalid');

                if ($(this).hasClass('ckeditor')) {
                    $(this).next('div').after(error_msg);

                } else if (is_check == true) {
                    $('.check_error').html('');
                    $('.check_error').append(error_msg);

                } else if ($(this).hasClass('url')) {
                    $(this).after(error_msg);

                } else if($(this).hasClass('pay-select-fields')){
                    $(this).closest('.select-field').after(error_msg);
                } else {
                    $(this).after(error_msg);
                }

            } else {
                /* if ($(this).hasClass('select2-hidden-accessible')) {
                    $(this).closest('.select-field').after(error_msg);
                } */
                if ($(this).hasClass('ckeditor')) {
                    $(this).next('div').after(error_msg);
                    $(this).closest('div.form-group').addClass('is-invalid');
                } else if ($(this).hasClass('bs-select')) {
                    $(this).next().next().after(error_msg);
                } else if($(this).hasClass('location_select')) {
                    $(this).next().after(error_msg);
                    $(this).closest('div.form-group').addClass('is-invalid');
                } else if ($(this).hasClass('required-least-one-radio')) {
                    $(this).closest('div.form-group').addClass('is-invalid');

                    if ($('.required-radio-discount').parent().find('.help-block').length <= 0) {
                        $(this).closest('div.required-radio-discount').after(error_msg);
                        $('.required-radio-discount div').remove();
                        $('.discount_value_error').addClass('invalid-feedback');
                    }

                    if ($('.required-radio-btn').parent().find('.help-block').length <= 0) {
                        $(this).closest('div.required-radio-btn').after(error_msg);
                        $('.required-radio-btn div').remove();
                    }

                    $(this).closest('div.kt-radio-inline').after(error_msg);
                    $('#one_way_error').remove();
                    $('#travel_alone_yes_error').remove();
                    $('#e_voice_mail_setup_yes_error').remove();
                    $('#private_error').remove();

                } else if($(this).hasClass('pay-select-fields')){
                    $(this).closest('div.form-group').addClass('is-invalid');
                    $(this).closest('.select-field').after(error_msg);
                } else {
                    $(this).closest('div.form-group').addClass('is-invalid');
                    $(this).after(error_msg);
                }
            }
        }

    });
    if (flag) {
        $(form).find('.alert-danger').hide();
        //$('.alert-success').show();
        /* For building section in admin */
        if ($('#floor_tab').length > 0) {
            $('#floor_tab').attr('data-target', '#tab2').attr('data-toggle', 'tab');
        }
    } else {
        if ($('.is-invalid').length > 0) {
            $(form).find('.alert-danger').show();
            $(form).find('.override-error').hide();
            $(form).find(".is-invalid:first input:first").focus();
            $('html, body').animate({
                scrollTop: $('.is-invalid').first().offset().top - 200
            }, 1000);
        } else {
            $(form).find('.alert-danger').hide();
        }

        if ($(form).find('.card-amount-error').length > 0) {
            $(form).find('.card-amount-error').remove();
        }
        //$('.alert-success').hide();
    }

    return flag;
    //}, 500);
}

function ucfirst(str) {
    str += '';
    var f = str.charAt(0)
        .toUpperCase();
    return f + str.substr(1);
}
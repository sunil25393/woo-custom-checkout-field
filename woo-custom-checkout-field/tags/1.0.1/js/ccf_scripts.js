(function ($) {
    $('#txt_field_type').change(function () {
        $value = $('#txt_field_type').val();

        if (($value == 'select') || ($value == 'radio')) {
            $('.option_field_hidden').css('display', 'block');
        } else {
            $('.option_field_hidden').css('display', 'none');
        }
    });
    $('#txt_field_name').blur(function () {
        var str = $('#txt_field_name').val();
        var st = /^[a-zA-Z0-9- ]*$/;

        if (st.test(str) == false) {
            $('#ccf_name_error').html('Your custom field name contains illegal characters.');
            $('#txt_field_name').focus();
        } else {
            $('#ccf_name_error').html('');
        }
    });
})(jQuery);

(function ($) {
    $('#txt_field_type').change(function () {
        $value = $('#txt_field_type').val();

        if (($value == 'select') || ($value == 'radio')) {
            $('.option_field_hidden').css('display', 'block');
        } else {
            $('.option_field_hidden').css('display', 'none');
        }
    });
    
})(jQuery);

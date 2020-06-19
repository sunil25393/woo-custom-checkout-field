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


var wcfd_settings = (function ($, window, document) {

    var fixHelper = function (e, ui) {
        ui.children().each(function () {
            $(this).width($(this).width());
        });
        return ui;
    };

    $(".ccf_checkout_fields tbody").sortable({
        items: 'tr',
        cursor: 'move',
        axis: 'y',
        handle: 'td.sort',
        scrollSensitivity: 40,
        helper: fixHelper
    });
    $(".ccf_checkout_fields tbody").disableSelection();


    $(".ccf_checkout_fields tbody").on("sortstart", function (event, ui) {
        ui.item.css('background-color', '#f6f6f6');
    });
    $(".ccf_checkout_fields tbody").on("sortstop", function (event, ui) {
        ui.item.removeAttr('style');
        wcfd_prepare_field_order_indexes();
    });

    function wcfd_prepare_field_order_indexes() {
        $('.ccf_checkout_fields tbody tr').each(function (index, el) {
            $('input.f_order', el).val(parseInt($(el).index('.ccf_checkout_fields tbody tr')));
        });
    }
    ;

    _removeSelectedFields = function removeSelectedFields() {
        $('#wcfd_checkout_fields tbody tr').removeClass('strikeout');
        $('#wcfd_checkout_fields tbody input:checkbox[name=select_field]:checked').each(function () {
            //$(this).closest('tr').remove();
            var row = $(this).closest('tr');
            if (!row.hasClass("strikeout")) {
                row.addClass("strikeout");
            }
            row.find(".f_deleted").val(1);
            row.find(".f_edit_btn").prop('disabled', true);
            //row.find('.sort').removeClass('sort');
        });
    }

    _enableDisableSelectedFields = function enableDisableSelectedFields(enabled) {
        $('#wcfd_checkout_fields tbody input:checkbox[name=select_field]:checked').each(function () {
            var row = $(this).closest('tr');
            if (enabled == 0) {
                if (!row.hasClass("thwcfd-disabled")) {
                    row.addClass("thwcfd-disabled");
                }
            } else {
                row.removeClass("thwcfd-disabled");
            }

            row.find(".f_edit_btn").prop('disabled', enabled == 1 ? false : true);
            row.find(".td_enabled").html(enabled == 1 ? '<span class="status-enabled tips">Yes</span>' : '-');
            row.find(".f_enabled").val(enabled);
        });
    }
    _selectAllCheckoutFields = function selectAllCheckoutFields(elm) {
        var checkAll = $(elm).prop('checked');
        $('.ccf_checkout_fields tbody input:checkbox[name=select_field]').prop('checked', checkAll);
    }

    return {
        removeSelectedFields: _removeSelectedFields,
        enableDisableSelectedFields: _enableDisableSelectedFields,
        selectAllCheckoutFields: _selectAllCheckoutFields,
    };

}(window.jQuery, window, document));


function removeSelectedFields() {
    wcfd_settings.removeSelectedFields();
}

function enableSelectedFields() {
    wcfd_settings.enableDisableSelectedFields(1);
}

function disableSelectedFields() {
    wcfd_settings.enableDisableSelectedFields(0);
}

function fieldTypeChangeListner(elm) {
    wcfd_settings.fieldTypeChangeListner(elm);
}

function thwcfdSelectAllCheckoutFields(elm) {
    wcfd_settings.selectAllCheckoutFields(elm);
}











































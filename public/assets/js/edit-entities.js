jQuery(document).ready(function($) {
    $('.bulk-actions').hide();
    $('#cb-select-all').change(function() {
        if ($(this).prop('checked')) {
            $('.cb-select').prop('checked', true).trigger('change');
        } else {
            $('.cb-select').prop('checked', false).trigger('change');
        }
    });
    $('.cb-select').change(function() {
        if ($('.cb-select').filter(':checked').length >= 1) {
            $('.bulk-actions').show();
        } else {
            $('.bulk-actions').hide();
        }
    });
});
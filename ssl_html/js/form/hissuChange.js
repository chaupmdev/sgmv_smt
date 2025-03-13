/*global $*/
$(function () {
    'use strict';
    $('input[name="need_reply_cd_sel"]').on('change', function () {
        var value = $.trim($('input[name="need_reply_cd_sel"]:checked').val());
        $('#mailNeedImg').toggle(value === '1');
    }).trigger('change');
});
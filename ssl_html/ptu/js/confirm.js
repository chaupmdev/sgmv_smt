/*global $,multiSend*/
$(function () {
    'use strict';

    $('[data-action]').on('click', function () {
        if (!multiSend.block()) {
            return false;
        }
        $('form').first().attr('action', $(this).data('action')).submit();
        return false;
    });
});
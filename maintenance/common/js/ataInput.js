/*global $*/
$(function () {
    'use strict';

    $('#back_list').on('click', function () {
        $('form').first().attr('action', '/ata/list').submit();
    });

    $('#back_default').on('click', function () {
        $('form').first().append('<input name="id" type="hidden" value="' + $('#travel_agency_id').val() + '" />').attr('action', '/ata/input').submit();
    });

    $('#register').on('click', function () {
        $('form').first().attr('action', '/ata/check_input').submit();
    });

    $('input,select').filter(':visible').first().focus();
});
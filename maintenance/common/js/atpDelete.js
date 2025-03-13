/*global $*/
$(function () {
    'use strict';

    $('#back_list').on('click', function () {
        $('form').first().attr('action', '/atp/list').submit();
    });

    $('#delete').on('click', function () {
        $('form').first().attr('action', '/atp/check_delete').submit();
    });

    $('a,input,select').filter(':visible').last().focus();
});
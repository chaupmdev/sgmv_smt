/*global $*/
$(function () {
    'use strict';

    $('#back_list').on('click', function () {
        $('form').first().attr('action', '/aap/list').submit();
    });

    $('#delete').on('click', function () {
        $('form').first().attr('action', '/aap/check_delete').submit();
    });

    $('a,input,select').filter(':visible').last().focus();
});
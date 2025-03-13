/*global $*/
$(function () {
    'use strict';

    $('input[name="adrs_search_btn"]').on('click', function () {
        var $form = $('form').first();
        AjaxZip2.zip2addr(
            'input_forms',
            'zip1',
            'pref_cd_sel',
            'address',
            'zip2',
            '',
            '',
            $form.data('featureId'),
            $form.data('id'),
            $('input[name="ticket"]').val()
        );
    });

    $('#back_list').on('click', function () {
        $('form').first().attr('action', '/aap/list').submit();
    });

    $('#back_default').on('click', function () {
        $('form').first().append('<input name="id" type="hidden" value="' + $('#apartment_id').val() + '" />').attr('action', '/aap/input').submit();
    });

    $('#register').on('click', function () {
        $('form').first().attr('action', '/aap/check_input').submit();
    });

    $('input,select').filter(':visible').first().focus();
});
/*global $*/
$(function () {
    'use strict';

    $.datepicker.setDefaults({
        showOn: 'both',
        buttonImageOnly: false,
        buttonText: 'カレンダー表示',
        showAnim: 'slideDown',
        speed: 'fast'
    });
    $('.datepicker').datepicker();

    $('#back_list').on('click', function () {
        $('form').first().attr('action', '/atr/list').submit();
    });

    $('#back_default').on('click', function () {
	var hiddenIdTagForAppend = '<input name="id" type="hidden" value="" />';
	if($('#travel_id').val()) {
		hiddenIdTagForAppend = '<input name="id" type="hidden" value="' + $('#travel_id').val() + '" />';
	}
        $('form').first().append(hiddenIdTagForAppend).attr('action', '/atr/input').submit();
    });

    $('#register').on('click', function () {
        $('form').first().attr('action', '/atr/check_input').submit();
    });

    $('input,select').filter(':visible').first().focus();
});
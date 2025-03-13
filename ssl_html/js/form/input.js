/*global $,AjaxZip2*/
$(function () {
    'use strict';
    $('input[name="address_search_btn"]').on('click', function () {
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
});
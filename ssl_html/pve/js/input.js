/*global $,AjaxZip2,sgwns*/
$(function () {
    'use strict';

    var address = '';

    function getFormData() {
        var $form = $('form').first(),
            data = $form.serializeArray();
        data.push({
            name: 'featureId',
            value: $form.data('featureId')
        }, {
            name: 'id',
            value: $form.data('id')
        });
        return data;
    }

    $('input[name="cur_adrs_search_btn"]').on('click', function () {
        var $form = $('form').first();
        AjaxZip2.zip2addr(
            'input_forms',
            'cur_zip1',
            'cur_pref_cd_sel',
            'cur_address',
            'cur_zip2',
            '',
            '',
            $form.data('featureId'),
            $form.data('id'),
            $('input[name="ticket"]').val()
        );
    });

    $('input[name="new_adrs_search_btn"]').on('click', function () {
        var $form = $('form').first();
        AjaxZip2.zip2addr(
            'input_forms',
            'new_zip1',
            'new_pref_cd_sel',
            'new_address',
            'new_zip2',
            '',
            '',
            $form.data('featureId'),
            $form.data('id'),
            $('input[name="ticket"]').val()
        ).done(function () {
            if (address !== '') {
                $('input[name="new_address"]').val(address);
                address = '';
            }
        });
    });

    $('select[name="apartment_cd_sel"]').on('change', function () {
        sgwns.api('/common/php/SearchApartment.php', getFormData(), function (data) {
            if (!data) {
                return false;
            }
            address = data.address;
            $('input[name="new_zip1"]').val(data.zip_code.substr(0, 3));
            $('input[name="new_zip2"]').val(data.zip_code.substr(3));
            $('input[name="new_adrs_search_btn"]').trigger('click');
        });
    });
});
/*global $,_,api*/
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
    $('.datepicker').datepicker('option', 'minDate', new Date(1990, 0, 1));
    $('.timepicker').timepicker({
        minutes: {
            interval: 1
        },
        rows: 6,
        showNowButton: true,
        showCloseButton: true,
        showDeselectButton: true
    });

    function addOption($select, data) {
        var option = '';
        if (!data || (data.ids && data.ids.length !== 1)) {
            option += '<option value="">選択してください</option>';
        }
        $.each(data.ids, function (key, value) {
            option += '<option value="' + value + '">' + data.names[key] + '</option>';
        });
        $select.empty().append(option);
    }

    // Underscore.jsのbindで関数の第一引数をあらかじめ割り当てておく
    var travelAddOption = _.bind(addOption, undefined, $('select[name="travel_cd_sel"]'));

    $('select[name="travel_agency_cd_sel"]').on('change', function () {
        api('/common/php/SearchTravels.php', $('form').first().serializeArray(), travelAddOption);
    });

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
        $('form').first().attr('action', '/att/list').submit();
    });

    $('#back_default').on('click', function () {
        $('form').first().append('<input name="id" type="hidden" value="' + $('#travel_terminal_id').val() + '" />').attr('action', '/att/input').submit();
    });

    $('#register').on('click', function () {
        $('form').first().attr('action', '/att/check_input').submit();
    });

    $('input,select').filter(':visible').first().focus();
});
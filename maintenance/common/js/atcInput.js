/*global $,_,api*/
$(function () {
    'use strict';

    function addOption($select, data) {
        var option = '';
        if (!data || (data.ids && data.ids.length !== 1)) {
            option += '<option value="">選択してください</option>';
        }
        if (data) {
            if (data.round_trip_discounts) {
                $.each(data.ids, function (key, value) {
                    option += '<option data-round-trip-discount="' + data.round_trip_discounts[key] + '" value="' + value + '">' + data.names[key] + '</option>';
                });
            } else {
                $.each(data.ids, function (key, value) {
                    option += '<option value="' + value + '">' + data.names[key] + '</option>';
                });
            }
        }
        $select.empty().append(option);
    }

    // Underscore.jsのbindで関数の第一引数をあらかじめ割り当てておく
    var travelAddOption = _.bind(addOption, undefined, $('select[name="travel_cd_sel"]')),
        travelTerminalAddOption = _.bind(addOption, undefined, $('select[name="travel_terminal_cd_sel"]'));

    $('select[name="travel_agency_cd_sel"]').on('change', function () {
        api('/common/php/SearchTravels.php', $('form').first().serializeArray(), travelAddOption).done(function () {
            $('select[name="travel_cd_sel"]').trigger('change');
        });
    });

    $('select[name="travel_cd_sel"]').on('change', function () {
        api('/common/php/SearchTravelTerminals.php', $('form').first().serializeArray(), travelTerminalAddOption).done(function () {
            $('#travel_delivery_charge_resist_table').find('input').trigger('change');
        });
    });

    $('#travel_delivery_charge_resist_table').find('th').filter('[title]').tooltip({
        show: false,
        hide: false
    }).end().end().find('input').on('change', function () {
        var $this = $(this),
            val = parseInt($this.val(), 10),
            roundTripDiscount = $('select[name="travel_cd_sel"]').find('option').filter(':selected').data('roundTripDiscount'),
            delivery_charge;
        if (_.isNaN(val) || _.isUndefined(val)) {
            val = 0;
        }
        if (_.isNaN(roundTripDiscount) || _.isUndefined(roundTripDiscount)) {
            roundTripDiscount = 0;
        }
        delivery_charge = val * 2 - roundTripDiscount;
        if (delivery_charge < 0) {
            delivery_charge = 0;
        }
        $this.closest('tr').find('td').last().text(delivery_charge.toLocaleString().split('.')[0] + ' 円');
    }).trigger('change');

    $('#back_list').on('click', function () {
        $('form').first().attr('action', '/atc/list').submit();
    });

    $('#back_default').on('click', function () {
        $('form').first().append('<input name="id" type="hidden" value="' + $('select[name="travel_terminal_cd_sel"]').val() + '" />').attr('action', '/atc/input').submit();
    });

    $('#register').on('click', function () {
        $('form').first().attr('action', '/atc/check_input').submit();
    });

    $('input,select').filter(':visible').first().focus();
});
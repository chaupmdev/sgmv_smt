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

    function getFormDataFrom() {
        var data = $('form').first().serializeArray();
        data.push({
            name: 'travel_agency_cd_sel',
            value: $('select[name="travel_agency_from_cd_sel"]').val()
        }, {
            name: 'travel_cd_sel',
            value: $('select[name="travel_from_cd_sel"]').val()
        }, {
            name: 'travel_terminal_cd_sel',
            value: $('select[name="travel_terminal_from_cd_sel"]').val()
        });
        return data;
    }

    function getFormDataTo() {
        var data = $('form').first().serializeArray();
        data.push({
            name: 'travel_agency_cd_sel',
            value: $('select[name="travel_agency_to_cd_sel"]').val()
        }, {
            name: 'travel_cd_sel',
            value: $('select[name="travel_to_cd_sel"]').val()
        }, {
            name: 'travel_terminal_cd_sel',
            value: $('select[name="travel_terminal_to_cd_sel"]').val()
        });
        return data;
    }

    function setTable(name, data) {
        var html = '',
            tbody = $('#travel_delivery_charge_' + name + '_resist_table').children('tbody'),
            round_trip_discount = $('select[name="travel_' + name + '_cd_sel"]').find('option').filter(':selected').data('roundTripDiscount');
        if (_.isNaN(round_trip_discount) || _.isUndefined(round_trip_discount)) {
            round_trip_discount = 0;
        }
        if (!data) {
            tbody.empty();
            return;
        }
        $.each(data.ids, function (key, value) {
            var name = '',
                prefecture_name = '',
                delivery_charge = '',
                delivery_charge2 = '';
            if (data.names && data.names[key]) {
                name = data.names[key];
            }
            if (data.prefecture_names && data.prefecture_names[key]) {
                prefecture_name = data.prefecture_names[key].join('\n');
            }
            if (data.delivery_chargs && data.delivery_chargs[value]) {
                delivery_charge = parseInt(data.delivery_chargs[value], 10);
                if (_.isNaN(delivery_charge) || _.isUndefined(delivery_charge)) {
                    delivery_charge = 0;
                }
                delivery_charge2 = delivery_charge * 2 - round_trip_discount;
                if (delivery_charge2 < 0) {
                    delivery_charge2 = 0;
                }
                delivery_charge = delivery_charge.toLocaleString().split('.')[0] + '円';
                delivery_charge2 = delivery_charge2.toLocaleString().split('.')[0] + '円';
            }
            html += '<tr>'
                + '<th title="' + prefecture_name + '">' + name + '</th>'
                + '<td class="number">' + delivery_charge + '</td>'
                + '<td class="number">' + delivery_charge2 + '</td>'
                + '</tr>';
        });
        tbody.empty().append(html);
    }

    // Underscore.jsのbindで関数の第一引数をあらかじめ割り当てておく
    var travelFromAddOption = _.bind(addOption, undefined, $('select[name="travel_from_cd_sel"]')),
        travelTerminalFromAddOption = _.bind(addOption, undefined, $('select[name="travel_terminal_from_cd_sel"]')),
        setTableFrom = _.bind(setTable, undefined, 'from'),
        travelToAddOption = _.bind(addOption, undefined, $('select[name="travel_to_cd_sel"]')),
        travelTerminalToAddOption = _.bind(addOption, undefined, $('select[name="travel_terminal_to_cd_sel"]')),
        setTableTo = _.bind(setTable, undefined, 'to');

    $('select[name="travel_agency_from_cd_sel"]').on('change', function () {
        api('/common/php/SearchTravels.php', getFormDataFrom(), travelFromAddOption).done(function () {
            $('select[name="travel_from_cd_sel"]').trigger('change');
        });
    });

    $('select[name="travel_agency_to_cd_sel"]').on('change', function () {
        api('/common/php/SearchTravels.php', getFormDataTo(), travelToAddOption).done(function () {
            $('select[name="travel_to_cd_sel"]').trigger('change');
        });
    });

    $('select[name="travel_from_cd_sel"]').on('change', function () {
        api('/common/php/SearchTravelTerminals.php', getFormDataFrom(), travelTerminalFromAddOption).done(function () {
            $('select[name="travel_terminal_from_cd_sel"]').trigger('change');
        });
    });

    $('select[name="travel_to_cd_sel"]').on('change', function () {
        api('/common/php/SearchTravelTerminals.php', getFormDataTo(), travelTerminalToAddOption).done(function () {
            $('select[name="travel_terminal_to_cd_sel"]').trigger('change');
        });
    });

    $('select[name="travel_terminal_from_cd_sel"]').on('change', function () {
        api('/common/php/SearchTravelDeliveryCharge.php', getFormDataFrom(), setTableFrom);
    }).trigger('change');

    $('select[name="travel_terminal_to_cd_sel"]').on('change', function () {
        api('/common/php/SearchTravelDeliveryCharge.php', getFormDataTo(), setTableTo);
    }).trigger('change');

    $('#back_list').on('click', function () {
        $('form').first().attr('action', '/atc/list').submit();
    });

    $('#register').on('click', function () {
        $('form').first().attr('action', '/atc/check_copy').submit();
    });

    $('input,select').filter(':visible').first().focus();
});
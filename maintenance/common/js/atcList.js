/*global $,_,api*/
$(function () {
    'use strict';

    function addOption($select, data) {
        var option = '';
        if (!data || (data.ids && data.ids.length !== 1)) {
            option += '<option value="">選択してください</option>';
        }
        if (data && data.ids) {
            $.each(data.ids, function (key, value) {
                option += '<option value="' + value + '">' + data.names[key] + '</option>';
            });
        }
        $select.empty().append(option).trigger('change');
    }

    // Underscore.jsのbindで関数の第一引数をあらかじめ割り当てておく
    var travelAddOption = _.bind(addOption, undefined, $('select[name="travel_cd_sel"]'));

    $('select[name="travel_agency_cd_sel"]').on('change', function () {
        api('/common/php/SearchTravels.php', $('form').first().serializeArray(), travelAddOption);
    }).trigger('change');

    $('select[name="travel_cd_sel"]').on('change', function () {
        api('/common/php/SearchTravelTerminals.php', $('form').first().serializeArray(), function (data) {
            var html = '';
            if (!data) {
                $('#travel_delivery_charge_table').children('tbody').empty();
                return;
            }
            $.each(data.ids, function (key, value) {
                html += '<tr data-id="' + value + '" data-travel-delivery-charge-id="' + $.trim(data.travel_delivery_charge_ids[key]) + '">'
                    + '<td>' + data.cds[key] + '</td>'
                    + '<td>' + data.names[key] + '</td>'
                    + '<td class="date">' + data.departure_dates[key] + '</td>'
                    + '<td class="date">' + data.arrival_dates[key] + '</td>'
                    + '<td><a data-copy="1" href="#">▼コピー</a></td>'
                    + '<td><a href="#">▼編集</a></td>'
                    + '<td>';
                if (data.travel_delivery_charge_ids[key]) {
                    html += '<a data-delete="1" href="#">▼削除</a>';
                }
                html += '</td>'
                    + '</tr>';
            });
            $('#travel_delivery_charge_table').children('tbody').empty().append(html);
        });
    }).trigger('change');

    $('#travel_delivery_charge_table').add('.add_btn').on('click', 'a', function () {
        var $this = $(this),
            url = '/atc/',
            id,
            travel_delivery_charge_id;
        if ($this.filter('[data-delete]').length) {
            url += 'delete';
        } else if ($this.filter('[data-copy]').length) {
            url += 'copy';
        } else {
            url += 'input';
        }
        if ($this.filter('[data-id]').length) {
            id = $.trim($this.data('id'));
            travel_delivery_charge_id = $.trim($this.data('travelDeliveryChargeId'));
        } else {
            id = $.trim($this.closest('[data-id]').data('id'));
            travel_delivery_charge_id = $.trim($this.closest('[data-travel-delivery-charge-id]').data('travelDeliveryChargeId'));
        }
        $('form').first().attr('action', url).find('input[name="id"]').val(id).end().find('input[name="travel_delivery_charge_id"]').val(travel_delivery_charge_id).end().submit();
    });

    $('input,select').filter(':visible').first().focus();
});
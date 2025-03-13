/*global $,api*/
$(function () {
    'use strict';

    $('select[name="travel_agency_cd_sel"]').on('change', function () {
        api('/common/php/SearchTravels.php', $('form').first().serializeArray(), function (data) {
            var html = '';
            if (!data) {
                $('#travel_table').children('tbody').empty();
                return;
            }
            $.each(data.ids, function (key, value) {
                html += '<tr data-id="' + value + '">'
                    + '<td>' + data.cds[key] + '</td>'
                    + '<td>' + data.names[key] + '</td>'
                    + '<td class="number">' + data.round_trip_discounts[key] + '</td>'
                    + '<td class="number">' + data.repeater_discounts[key] + '</td>'
                    + '<td class="date">' + data.embarkation_dates[key] + '</td>'
                    + '<td class="date">' + data.publish_begin_dates[key] + '</td>'
                    + '<td><a href="#">▼編集</a></td>'
                    + '<td><a data-delete="1" href="#">▼削除</a></td>'
                    + '</tr>';
            });
            $('#travel_table').children('tbody').empty().append(html);
        });
    }).trigger('change');

    $('#travel_table').add('.add_btn').on('click', 'a', function () {
        var $this = $(this),
            url = '/atr/',
            id;
        if ($this.filter('[data-delete]').length) {
            url += 'delete';
        } else {
            url += 'input';
        }
        if ($this.filter('[data-id]').length) {
            id = $.trim($this.data('id'));
        } else {
            id = $.trim($this.closest('[data-id]').data('id'));
        }
        $('form').first().attr('action', url).find('input[name="id"]').val(id).end().submit();
    });

    $('input,select').filter(':visible').first().focus();
});
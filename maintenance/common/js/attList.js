/*global $,_,api*/
$(function () {
    'use strict';

    var terminal = ['', '出発地のみ選択可', '到着地のみ選択可', '発着地両方で選択可'];

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
            var html = '',
                zip;
            if (!data) {
                $('#travel_terminal_table').children('tbody').empty();
                return;
            }
            $.each(data.ids, function (key, value) {
                zip = $.trim(data.zips[key]);
                if (zip === '-') {
                    zip = '';
                }
                html += '<tr data-id="' + value + '">'
                    + '<td>' + data.cds[key] + '</td>'
                    + '<td>' + data.names[key] + '</td>'
                    + '<td>' + zip + '</td>'
                    + '<td>' + data.prefecture_names[key] + '</td>'
                    + '<td>' + data.address[key] + '</td>'
                    + '<td>' + data.buildings[key] + '</td>'
                    + '<td>' + data.store_names[key] + '</td>'
                    + '<td>' + data.tels[key] + '</td>'
                    + '<td>' + terminal[data.terminal_cds[key]] + '</td>'
                    + '<td class="date">' + data.departure_dates[key] + '</td>'
                    + '<td>' + data.departure_times[key] + '</td>'
                    + '<td class="date">' + data.arrival_dates[key] + '</td>'
                    + '<td>' + data.arrival_times[key] + '</td>'
                    + '<td>' + data.departure_client_cds[key] + '</td>'
                    + '<td>' + data.departure_client_branch_cds[key] + '</td>'
                    + '<td>' + data.arrival_client_cds[key] + '</td>'
                    + '<td>' + data.arrival_client_branch_cds[key] + '</td>'
                    //+ '<td></td>'
                    + '<td><a href="#">▼編集</a></td>'
                    + '<td><a data-delete="1" href="#">▼削除</a></td>'
                    + '</tr>';
            });
            $('#travel_terminal_table').children('tbody').empty().append(html);
        });
    }).trigger('change');

    $('#travel_terminal_table').add('.add_btn').on('click', 'a', function () {
        var $this = $(this),
            url = '/att/',
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
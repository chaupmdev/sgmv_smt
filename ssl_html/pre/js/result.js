/*global $*/
$(function () {
    'use strict';

    $('a', '#calendar_table,#sp_calendar_table').filter('[href]').on('click', function () {
        // TODO 表示速度を向上させるため、Ajaxでカレンダー部分のみ再描画する
        $('#other_day').find('form').find('input').val($(this).attr('href')).end().submit();
        return false;
    });
});
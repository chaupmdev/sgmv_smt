/*global $*/
$(function () {
    'use strict';

    $('#selected').add('#unselected').on('click', 'li', function () {
        $(this).toggleClass('selected');
    });

    $('#add').add('#add_all').on('click', function () {
        var filter;
        switch (this.id) {
        case 'add':
            filter = '.selected';
            break;
        case 'add_all':
            filter = ':visible';
            break;
        }
        $.each($('#unselected').find('li').filter(filter), function () {
            $('#selected').find('[data-prefecture-id="' + $(this).addClass('hide').removeClass('selected').data('prefectureId') + '"]').removeClass('hide');
        });
    });

    $('#delete').add('#delete_all').on('click', function () {
        var filter;
        switch (this.id) {
        case 'delete':
            filter = '.selected';
            break;
        case 'delete_all':
            filter = ':visible';
            break;
        }
        $.each($('#selected').find('li').filter(filter), function () {
            $('#unselected').find('[data-prefecture-id="' + $(this).addClass('hide').removeClass('selected').data('prefectureId') + '"]').removeClass('hide');
        });
    });

    $('#back_list').on('click', function () {
        $('form').first().attr('action', '/atp/list').submit();
    });

    $('#back_default').on('click', function () {
        $('form').first().append('<input name="id" type="hidden" value="' + $('#travel_province_id').val() + '" />').attr('action', '/atp/input').submit();
    });

    $('#register').on('click', function () {
        var input = '';
        $.each($('#selected').find('li').filter(':visible'), function () {
            var prefecture_id = $(this).data('prefectureId');
            input += '<input name="prefecture_id[' + prefecture_id + ']" type="hidden" value="' + prefecture_id + '" />';
        });
         $('form').first().append(input).attr('action', '/atp/check_input').submit();
    });

    $('input,select').filter(':visible').first().focus();
});
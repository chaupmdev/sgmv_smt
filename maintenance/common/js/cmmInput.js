/*global $,FileReader*/
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

    $('textarea').resizable({
        maxWidth: $('textarea').outerWidth(),
        minHeight: 100,
        minWidth: $('textarea').outerWidth()
    });

    $.each($('.img_input'), function () {
        var selfFile = $(this);

        selfFile.find('input[type="file"]').change(function () {
            var file = $(this).prop('files')[0],
                fileRdr = new FileReader(),
                selfImg = selfFile.find('.img_view'),
                prevElm;

            if (!this.files.length) {
                if (0 < selfImg.length) {
                    selfImg.remove();
                    return;
                }
            } else if (file.type.match('image.*')) {
                if (0 >= selfImg.length) {
                    selfFile.append('<img alt="" class="img_view" />');
                }
                prevElm = selfFile.find('.img_view');
                fileRdr.onload = function () {
                    prevElm.attr('src', fileRdr.result);
                };
                fileRdr.readAsDataURL(file);
            } else if (0 < selfImg.length) {
                selfImg.remove();
                return;
            }
        });
    });

    $('#back_list').on('click', function () {
        var url;
        switch ($('#cmm_list_king').val()) {
        case '1':
            url = '/cmm/list/comments';
            break;
        case '2':
            url = '/cmm/list/attention';
            break;
        default:
            return;
        }
        $('form').first().attr('action', url).submit();
    });

    $('#back_default').on('click', function () {
        var url;
        switch ($('#cmm_list_king').val()) {
        case '1':
            url = '/cmm/input/comments';
            break;
        case '2':
            url = '/cmm/input/attention';
            break;
        default:
            return;
        }
        $('form').first().append('<input name="id" type="hidden" value="' + $('#comment_id').val() + '" />').attr('action', url).submit();
    });

    $('#register').on('click', function () {
        var url;
        switch ($('#cmm_list_king').val()) {
        case '1':
            url = '/cmm/check_input/comments';
            break;
        case '2':
            url = '/cmm/check_input/attention';
            break;
        default:
            return;
        }
        $('form').first().attr('action', url).submit();
    });
});
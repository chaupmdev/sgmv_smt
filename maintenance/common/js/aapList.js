/*global $*/
$(function () {
    'use strict';

    $('#apartment_table').add('.add_btn').find('a').on('click', function () {
        var $this = $(this),
            url = '/aap/',
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
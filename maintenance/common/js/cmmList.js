/*global $*/
$(function () {
    'use strict';

    $('#comment_table').add('.add_btn').find('a').on('click', function () {
        var $this = $(this),
        url = '/cmm/',
        id;
        if ($this.filter('[data-delete]').length) {
            url += 'delete';
        } else {
            url += 'input';
        }
        if( $('#cmm_list_king').val() == '1' ){
        	url += '/comments';
        }else if( $('#cmm_list_king').val() == '2' ){
        	url += '/attention';
        }
        if ($this.filter('[data-id]').length) {
            id = $.trim($this.data('id'));
        } else {
            id = $.trim($this.closest('[data-id]').data('id'));
        }
        $('form').first().attr('action', url).find('input[name="id"]').val(id).end().submit();
        //$('form').first().find('input[name="id"]').val($(this).data('id')).end().submit();
    });
});
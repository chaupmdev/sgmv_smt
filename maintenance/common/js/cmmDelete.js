/*global $*/
$(function () {
    'use strict';

    $('#back_list').on('click', function () {
        if( $('#cmm_list_king').val() == '1' ){
            $('form').first().attr('action', '/cmm/list/comments').submit();
        }else if( $('#cmm_list_king').val() == '2' ){
            $('form').first().attr('action', '/cmm/list/attention').submit();
        }
    });

    $('#delete').on('click', function () {
        if( $('#cmm_list_king').val() == '1' ){
            $('form').first().attr('action', '/cmm/check_delete/comments').submit();
        }else if( $('#cmm_list_king').val() == '2' ){
            $('form').first().attr('action', '/cmm/check_delete/attention').submit();
        }
    });
});
/*global jQuery,multiSend*/

// jQueryと他のライブラリの競合を回避するため、引数でjQueryを$にする
(function ($) {
    'use strict';

    $('[data-action]').on('click', (function () {
        if (!multiSend.block()) {
            return false;
        }
        $('form').first().attr('action', $(this).data('action')).submit();
        return false;
    }));
}(jQuery));
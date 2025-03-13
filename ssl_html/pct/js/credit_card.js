/*global jQuery,multiSend*/

// jQueryと他のライブラリの競合を回避するため、引数でjQueryを$にする
(function ($) {
    'use strict';

    $('input[data-pattern]').filter('[data-pattern="^\\\\d+$"],[data-pattern="^\\\\w+$"],[data-pattern="^[!-~]+$"]').on('change', (function () {
        var $this = $(this);
        $this.val($this.val().replace(/[Ａ-Ｚａ-ｚ０-９]/g, (function (s) {
            return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
        })));
    }));

    $('input').filter('[placeholder]').ahPlaceholder({
        placeholderAttr: 'placeholder'
    });
}(jQuery));
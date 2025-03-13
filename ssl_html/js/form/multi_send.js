/*global $*/
// ボタン2度押し対策
var multiSend = (function () {
    'use strict';
    var isSend = false;
    return {
        block: function () {
            if (!isSend) {
                isSend = true;
                return true;
            }
            return false;
        },
        unblock: function () {
            isSend = false;
        }
    };
}());
$(function () {
    'use strict';

    $('input[name="confirm_btn"]').on('click', function () {
        if (!multiSend.block()) {
            return false;
        }
        $('form').first().submit();
    });
});
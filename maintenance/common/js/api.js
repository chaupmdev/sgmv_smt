/*global $,_*/
function doFunction(fnc) {
    'use strict';
    if (!_.isFunction(fnc)) {
        return;
    }
    // 第一引数以外の引数を配列に変換する
    var args = Array.prototype.slice.call(arguments, 1);
    // 配列で引数を渡す
    return fnc.apply(undefined, args);
}

function api(url, data, success) {
    'use strict';
    return $.ajax({
        async: true,
        cache: false,
        data: data,
        dataType: 'json',
        type: 'post',
        url: url
    }).done(function (data, textStatus, jqXHR) {
        doFunction(success, data, textStatus, jqXHR);
        if (!window.console) {
            return;
        }
        window.console.log(data);
        window.console.log(textStatus);
        window.console.log(jqXHR);
    }).fail(function (jqXHR, textStatus, errorThrown) {
        if (!window.console) {
            return;
        }
        window.console.log(jqXHR);
        window.console.log(textStatus);
        window.console.log(errorThrown);
    });
}
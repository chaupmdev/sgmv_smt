/*global $*/
// グローバル領域を汚染しないようnamespaceを作成
var sgwns = sgwns || {};

sgwns.doFunction = function (fnc) {
    'use strict';
    if (!_.isFunction(fnc)) {
        return;
    }
    // 第一引数以外の引数を配列に変換する
    var args = Array.prototype.slice.call(arguments, 1);
    // 配列で引数を渡す
    return fnc.apply(undefined, args);
};

sgwns.api = function (url, data, success, error) {
    'use strict';
    return $.ajax({
        async: true,
        cache: false,
        data: data,
        dataType: 'json',
        timeout: 60000,
        type: 'post',
        url: url
    }).done(function (data, textStatus, jqXHR) {
        sgwns.doFunction(success, data, textStatus, jqXHR);
        // consoleの存在チェック
        if (!window.console) {
            return;
        }
        // consoleが存在する場合、取得内容を出力
        window.console.log(data);
        window.console.log(textStatus);
        window.console.log(jqXHR);
    }).fail(function (jqXHR, textStatus, errorThrown) {
        sgwns.doFunction(error, jqXHR, textStatus, errorThrown);
        // consoleの存在チェック
        if (!window.console) {
            return;
        }
        // consoleが存在する場合、エラー内容を出力
        window.console.log(jqXHR);
        window.console.log(textStatus);
        window.console.log(errorThrown);
    });
};

$(function () {
    'use strict';

    // navbtn
    $('#nav_btn a').on('click', function (e) {
        $('#nav').slideToggle();
        $('#nav_btn').toggleClass('active');
        e.preventDefault();
        return false;
    });

    /*160311*/
    var $input,
        $pagetop = $('#pagetop_wrap');
    $(window).on('scroll', function () {
        if ($(this).scrollTop() > 100) {
            $pagetop.fadeIn();
        } else {
            $pagetop.fadeOut();
        }
    });
    $pagetop.find('a').on('click', function (e) {
        e.preventDefault();
        $('body, html').animate({
            scrollTop: 0
        }, 500);
        return false;
    });

    $('.accordion_button').on('click', function (e) {
        $(this).next().slideToggle().end().toggleClass('active');
        e.preventDefault();
        return false;
    });

    $input = $('button,input,select,textarea').filter(':visible').first();
    if ($input.length && $input.offset && $input.outerHeight && $input.offset().top + $input.outerHeight() <= $(window).height()) {
        // 最初の入力欄が画面内に存在する場合、自動的にフォーカスを合わせる(ラジオボタンは除外)
        if ($input.attr('type') != 'radio') {
            $input.focus();
        }
    }
});
/*global $*/
$(function () {
    'use strict';

    $('#toarea1,#toarea2,#toarea3,#toarea4,#toarea5,#toarea6').on('change', function () {
        // 出発地域の選択値を取得
        $('input[name="formareacd"]').val($('#fromarea1').val() || $('#fromarea2').val() || $('#fromarea3').val() || '');

        // 到着地域の選択値を取得
        $('input[name="toareacd"]').val($('#toarea1').val() || $('#toarea2').val() || $('#toarea3').val() || $('#toarea4').val() || $('#toarea5').val() || $('#toarea6').val() || '');
    }).trigger('change');
/*
    $('input[name="plan_cd_sel"]').on('change', function () {
        var $area = $('#fromarea1,#fromarea2,#fromarea3,#toarea1,#toarea2,#toarea3,#toarea4,#toarea5,#toarea6'),
            showId;

        switch ($(this).val()) {
        case '1':
            // 単身カーゴプラン
            // 【出発地域】沖縄なしプルダウンに切り替える
            // 【到着地域】沖縄なしプルダウンに切り替える
            showId = '#fromarea2,#toarea2';
            break;
        case '2':
            // 単身エアカーゴ
            // 【出発地域】北海道/東京23区/大阪府/福岡県プルダウンに切り替える
            showId = '#fromarea3,';
            switch ($('#fromarea3').val()) {
            case '45':
                // 【到着地域】福岡出発用に切り替える
                showId += '#toarea4';
                break;
            case '17':
                // 【到着地域】東京23区出発用に切り替える
                showId += '#toarea5';
                break;
            case '1':
                // 【到着地域】北海道出発用に切り替える
                showId += '#toarea6';
                break;
            default:
                // 【到着地域】沖縄なしプルダウンに切り替える
                showId += '#toarea3';
                break;
            }
            break;
        default:
            // 通常
            // 【出発地域】
            // 【到着地域】
            showId = '#fromarea1,#toarea1';
            break;
        }

        if (showId) {
            $area.not(showId).hide().val('');
            $(showId).show();
        }

        $('input[name="to_area_cd_sel"]').trigger('change');
    }).filter(':checked').trigger('change');
    
*/
    $('#fromarea3').on('change', function () {
        var courcePlan, $toarea, showId;

        $toarea = $('#toarea1,#toarea2,#toarea3,#toarea4,#toarea5,#toarea6');

        switch ($('#fromarea3').val()) {
        case '45':
            // 【到着地域】福岡出発用に切り替える
            showId = '#toarea4';
            break;
        case '17':
            // 【到着地域】東京23区出発用に切り替える
            showId = '#toarea5';
            break;
        case '1':
            // 【到着地域】北海道出発用に切り替える
            showId = '#toarea6';
            break;
        default:
            break;
        }

        if (showId) {
            $toarea.not(showId).hide().val('');
            $(showId).show();
        }

        $('input[name="to_area_cd_sel"]').trigger('change');
    });
});
/*global $*/
$(function () {
    'use strict';

    var regexp = /\n/g,
        checkedPlan = $('input').filter('[name="plan_cd_sel"],[name="plan_cd_sel2"]').on('change', function () {
            var val = $.trim($(this).val()),
                $input = $('input'),
                str = '',
                $area,
                showId;
            $input.filter('[name="plan_cd_sel"]').val([val]);
            $input.filter('[name="plan_cd_sel2"]').val([val]);
            switch (val) {
            case '1':
                str += '単身カーゴプランは、沖縄県ではご利用いただけません。ご了承ください。\n';
                break;
            case '2':
                str += '単身AIR CARGOプランは以下の地域でしかご利用いただけません。\n' +
                    'ご注意ください。\n' +
                    '北海道（札幌市）　→　東京23区\n' +
                    '北海道（札幌市）　→　大阪府\n' +
                    '北海道（札幌市）　→　福岡県\n' +
                    '東京23区　→　北海道（札幌市）\n' +
                    '東京23区　→　福岡県\n' +
                    '福岡県　→　北海道（札幌市）\n' +
                    '福岡県　→　東京23区\n';
                break;
            default:
                break;
            }
            $('#planMsg').html(str.replace(regexp, '<br />\n'));


            $area = $('#fromarea1,#fromarea2,#fromarea3,#toarea1,#toarea2,#toarea3,#toarea4,#toarea5,#toarea6');

            switch (val) {
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
    if (!checkedPlan) {
        $('input[name="plan_cd_sel"]').last().trigger('change');
    }
});
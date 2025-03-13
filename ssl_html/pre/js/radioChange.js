/*global $*/
$(function () {
    'use strict';

    var regexp = /\n/g,
        img = [],
        coursedPlan;
    img[1] = new Image();
    img[1].src = "/images/pre/input/img_01.png";
    img[2] = new Image();
    img[2].src = "/images/pre/input/img_02.png";
    img[3] = new Image();
    img[3].src = "/images/pre/input/img_03.png";
    img[4] = new Image();
    img[4].src = "/images/pre/input/img_04.png";
    img[5] = new Image();
    img[5].src = "/images/pre/input/img_05.png";
    img[6] = new Image();
    img[6].src = "/images/pre/input/img_06.png";
    img[7] = new Image();
    img[7].src = "/images/pre/input/img_07.png";
    img[8] = new Image();
    img[8].src = "/images/pre/input/img_08.png";

    $('input').filter('[name="course_cd_sel"],[name="course_cd_sel2"]').on('change', function () {
        var val = $.trim($(this).val()),
            $input = $('input'),
            $enable,
            $disable,
            img,
            str;
        if (val === '1') {
            $enable = $('#p1,#p2,#p1s,#p2s');
            $disable = $('#p3,#p4,#p5,#p3s,#p4s,#p5s');
        } else {
            $enable = $('#p3,#p4,#p5,#p3s,#p4s,#p5s');
            $disable = $('#p1,#p2,#p1s,#p2s');
        }
        $enable.prop('disabled', false);
        $disable.prop('checked', false).prop('disabled', true);
        if (!$('input').filter('[name="plan_cd_sel"],[name="plan_cd_sel2"]').filter(':checked').size()) {
            $('#planMsg').text('');
        }

        img = new Image();
        str = '';
        $('#courseMsg_1,#courseMsg_2,#courseMsg_3,#courseMsg_4,#courseMsg_5,#courseMsg_6,#courseMsg_7,#courseMsg_8').text('');
        switch (val) {
        case '1':
            img.src = "/images/pre/input/img_01.png";
            str += '選択可能なプラン\n'
                + '単身カーゴプラン / 単身AIR CARGOプラン';
            break;
        case '2':
            img.src = "/images/pre/input/img_02.png";
            str += '選択可能なプラン\n'
                + 'スタンダードプラン / まるごとおまかせプラン / チャータープラン\n';
            break;
        case '3':
            img.src = "/images/pre/input/img_03.png";
            str += '選択可能なプラン\n'
                + 'スタンダードプラン / まるごとおまかせプラン / チャータープラン\n';
            break;
        case '4':
            img.src = "/images/pre/input/img_04.png";
            str += '選択可能なプラン\n'
                + 'スタンダードプラン / まるごとおまかせプラン / チャータープラン\n';
            break;
        case '5':
            img.src = "/images/pre/input/img_05.png";
            str += '選択可能なプラン\n'
                + 'スタンダードプラン / まるごとおまかせプラン / チャータープラン\n';
            break;
        case '6':
            img.src = "/images/pre/input/img_06.png";
            str += '選択可能なプラン\n'
                + 'スタンダードプラン / まるごとおまかせプラン / チャータープラン\n';
            break;
        case '7':
            img.src = "/images/pre/input/img_07.png";
            str += '選択可能なプラン\n'
                + 'スタンダードプラン / まるごとおまかせプラン / チャータープラン\n';
            break;
        case '8':
            img.src = "/images/pre/input/img_08.png";
            str += '選択可能なプラン\n'
                + 'スタンダードプラン / まるごとおまかせプラン / チャータープラン\n';
            break;
        default:
            break;
        }
        if (str !== '') {
            $('#courseMsg').css('display', 'block').html(str.replace(regexp, '<br />\n'));
            $('#courseImg').css('display', 'block').attr('src', img.src);
            $('#courseMsg2').css('display', 'block').html(str.replace(regexp, '<br />\n'));
            $input.filter('[name="course_cd_sel"]').val([val]);
            $input.filter('[name="course_cd_sel2"]').val([val]);
        }
    }).filter(':checked').trigger('change');
});
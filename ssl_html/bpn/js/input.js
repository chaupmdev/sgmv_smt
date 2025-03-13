
(function ($) {

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 住所検索
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    $('input[name="adrs_search_btn"]').on('click', (function () {
        var $form = $('form').first();
        AjaxZip2.zip2addr(
            'input_forms',
            'comiket_zip1',
            'comiket_pref_cd_sel',
            'comiket_address',
            'comiket_zip2',
            '',
            '',
            $form.data('featureId'),
            $form.data('id'),
            $('input[name="ticket"]').val()
        );
//        $('input[name="comiket_address"]').removeAttr('style');
        $('input').filter('[name="comiket_zip1"],[name="comiket_zip2"]').trigger('focusout');
    }));

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 支払
//////////////////////////////////////////////////////////////////////////////////////////////////////////
$('input[name="comiket_payment_method_cd_sel"]').on('change', (function () {
    var val = parseInt($(this).val(), 10);
    if (_.isNaN(val)) {
        val = 0;
    }
//        fadeToggle($('#convenience'), (val & 1) === 1);
    fadeToggle($('#convenience'), val == '1'); // コンビニの場合のみ
})).filter(':checked').trigger('change');

var ua = window.navigator.userAgent.toLowerCase();
var isIE8 = ua.indexOf('msie') !== -1 && ver.indexOf('msie 8.') !== -1;
function fadeToggle($object, isShowing) {
    if (isShowing) {
        $object.fadeIn(300).removeAttr('style');
        if (isIE8) {
            $object.filter('dd').css({
                'padding-left': '0',
                'width': '650px'
            });
        }
    } else {
        $object.fadeOut(300);
    }
}

    if ($.fn && $.fn.autoKana) {
        $.fn.autoKana('input[name="comiket_staff_sei"]', 'input[name="comiket_staff_sei_furi"]', {
            katakana: true
        });
        $.fn.autoKana('input[name="comiket_staff_mei"]', 'input[name="comiket_staff_mei_furi"]', {
            katakana: true
        });
    }

    $('input[name="comiket_staff_sei"], input[name="comiket_staff_mei"]').on('input', function() {
        if ($('input[name="comiket_staff_sei_furi"]').val().length >= 9) {
            $('input[name="comiket_staff_sei_furi"]').val($('input[name="comiket_staff_sei_furi"]').val().slice(0, 8));
        } else if ($('input[name="comiket_staff_mei_furi"]').val().length >= 9) {
            $('input[name="comiket_staff_mei_furi"]').val($('input[name="comiket_staff_mei_furi"]').val().slice(0, 8));
        }
    });


    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    // お申込者->当日の担当者名, 電話番号->当日の担当者電話番号 などコピー
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    $('input.btn_cstm_info_copy').on('click', function() {
        var personalNameSei = $('input[name="comiket_personal_name_sei"]').val();
        var personalNameMei = $('input[name="comiket_personal_name_mei"]').val();
        var tel = $('input[name="comiket_tel"]').val();

        $('input[name="comiket_staff_sei"]').val(personalNameSei);
        $('input[name="comiket_staff_mei"]').val(personalNameMei);
        $('input[name="comiket_staff_tel"]').val(tel);
    });


    // 引渡し日（年）プルダウンリスト変更時
    $('[name=comiket_detail_collect_date_year_sel]').on('change', function(e) {
        if (!$('[name=comiket_detail_collect_date_year_sel]').val()
                || !$('[name=comiket_detail_collect_date_month_sel]').val()
                || !$('[name=comiket_detail_collect_date_day_sel]').val()) {
            return false;
        }

        // お届け可能日付範囲を取得
        getCollectDateCal();
    });

    // 引渡し日（月）プルダウンリスト変更時
    $('[name=comiket_detail_collect_date_month_sel]').on('change', function(e) {
        if (!$('[name=comiket_detail_collect_date_year_sel]').val()
                || !$('[name=comiket_detail_collect_date_month_sel]').val()
                || !$('[name=comiket_detail_collect_date_day_sel]').val()) {
            return false;
        }

        // お届け可能日付範囲を取得
        getCollectDateCal();
    });

    // 引渡し日（日）プルダウンリスト変更時
    $('[name=comiket_detail_collect_date_day_sel]').on('change', function(e) {
        if (!$('[name=comiket_detail_collect_date_year_sel]').val()
                || !$('[name=comiket_detail_collect_date_month_sel]').val()
                || !$('[name=comiket_detail_collect_date_day_sel]').val()) {
            return false;
        }

        // お預かり可能日付範囲を取得
        getCollectDateCal();
    });


    /**
     * お届け可能日付範囲を取得
     * @returns {undefined}
     */
    function getCollectDateCal() {

        var term_fr = $('[name=eventsub_term_fr]').val();
        var term_to = $('[name=eventsub_term_to]').val();

        $('#hid_comiket-detail-collect-date-from').val(term_fr);
        $('#hid_comiket-detail-collect-date-to').val(term_to);

        fromToPlldateInit();
    }


}(jQuery));

/*global jQuery,_,AjaxZip2,sgwns*/

// jQueryと他のライブラリの競合を回避するため、引数でjQueryを$にする
var g_eventsubList = {};
//var G_DEV_INDIVIDUAL = "1"; // 個人
//var G_DEV_BUSINESS = "2"; // 法人
(function ($) {
    'use strict';
    var g_fifoVal = 500;

    // 2018.10.22 tahira add IE、EDGEのみチェックボックスのある項目を.hide()で非表示にするとチェック状態が解除される問題の対応
    var checkOutboundService;
    var checkInboundService;

    setEventsubDataFromDiv();

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

////////////////////////////////////////////////////////////////////////////////////////////////
// 郵便番号マーク 表示/非表示制御
////////////////////////////////////////////////////////////////////////////////////////////////
    function dispZipMark() {
        var comiketDiv = $('input[name="comiket_div"]:checked').val();
        if(comiketDiv == G_DEV_BUSINESS) { // 法人
            if( !$('input[name="comiket_zip1"]').val() || $('input[name="comiket_zip1"]').val() == "" ) {
                $('span.zip_mark1').html('');
                $('span.zip_mark2').html('');
//                return;
            } else {
                $('span.zip_mark1').html('〒');
                $('span.zip_mark2').html('-');
//                return;
            }
        } else if(comiketDiv == G_DEV_INDIVIDUAL) {
            $('span.zip_mark1').html('〒');
            $('span.zip_mark2').html('-');
        } else {
            $('span.zip_mark1').html('');
            $('span.zip_mark2').html('');
        }
    }
    // 郵便番号マーク 表示/非表示制御
    dispZipMark();
/////////////////////////////////////////////////////////////////////////////////////////////////

    function clearInputByDivChange() {
        $('input[name="comiket_customer_cd"]').val('');
        $('input[name="office_name"]').val('');
        $('span.office_name-lbl').html('');

        $('input[name="comiket_personal_name_sei"]').val('');
        $('input[name="comiket_personal_name_mei"]').val('');
        $('span.comiket_personal_name_sei-lbl').html('');
        $('span.comiket_personal_name_mei-lbl').html('');

        $('input[name="comiket_zip1"]').val('');
        $('span.comiket_zip1-lbl').html('');
        $('span.zip_mark1,span.zip_mark2').html('');
        // 郵便番号マーク 表示/非表示制御
        dispZipMark();

        $('input[name="comiket_zip2"]').val('');
        $('span.comiket_zip2-lbl').html('');

        $('select[name="comiket_pref_cd_sel"]').val('');
        $('span.comiket_pref_nm-lbl').html('');

        $('input[name="comiket_address"]').val('');
        $('span.comiket_address-lbl').html('');

        $('input[name="comiket_building"]').val('');
        $('span.comiket_building-lbl').html('');

        $('input[name="comiket_tel"]').val('');
        $('span.comiket_tel-lbl').html('');

//        $('input[name="comiket_staff_sei"]').val('');
//        $('input[name="comiket_staff_mei"]').val('');
//        $('input[name="comiket_staff_sei_furi"]').val('');
//        $('input[name="comiket_staff_mei_furi"]').val('');
//
//        $('input[name="comiket_staff_tel"]').val('');
//        $('input[name="comiket_detail_type_sel"]').attr("checked", false);
//
//        //////////////////////////////////////////////////////
//        // 搬入
//        //////////////////////////////////////////////////////
//
//        $('input[name="comiket_detail_outbound_name"]').val('');
//        $('input[name="comiket_detail_outbound_zip1"]').val('');
//        $('input[name="comiket_detail_outbound_zip2"]').val('');
//
//        $('select[name="comiket_detail_outbound_pref_cd_sel"]').val('');
//
//        $('input[name="comiket_detail_outbound_address"]').val('');
//        $('input[name="comiket_detail_outbound_building"]').val('');
//        $('input[name="comiket_detail_outbound_tel"]').val('');
//
////        $('.comiket-detail-outbound-collect-date-from').html('');
////        $('.comiket-detail-outbound-collect-date-to').html('');
//
//        $('select[name="comiket_detail_outbound_collect_date_year_sel"]').val('');
//        $('select[name="comiket_detail_outbound_collect_date_month_sel"]').val('');
//        $('select[name="comiket_detail_outbound_collect_date_day_sel"]').val('');
//        $('select[name="comiket_detail_outbound_collect_time_sel"]').val('');
//
//        $('select[name="comiket_detail_outbound_delivery_date_year_sel"]').val('');
//        $('select[name="comiket_detail_outbound_delivery_date_month_sel"]').val('');
//        $('select[name="comiket_detail_outbound_delivery_date_day_sel"]').val('');
//        $('select[name="comiket_detail_outbound_delivery_time_sel"]').val('');
//
//        $('input[name="comiket_detail_outbound_service_sel"]').attr("checked", false);
//
////        $('input[name^="comiket_box_outbound_num_ary"]').val('');
////        $('select[name="comiket_cargo_outbound_num_sel"]').val('');
////        $('input[name^="comiket_charter_outbound_num_ary"]').val('');
////
////        $('input[name="comiket_detail_outbound_note1"]').val('');
//
//        //////////////////////////////////////////////////////
//        // 搬出
//        //////////////////////////////////////////////////////
//
//        $('input[name="comiket_detail_inbound_name"]').val('');
//        $('input[name="comiket_detail_inbound_zip1"]').val('');
//        $('input[name="comiket_detail_inbound_zip2"]').val('');
//
//        $('select[name="comiket_detail_inbound_pref_cd_sel"]').val('');
//
//        $('input[name="comiket_detail_inbound_address"]').val('');
//        $('input[name="comiket_detail_inbound_building"]').val('');
//        $('input[name="comiket_detail_inbound_tel"]').val('');
//
////        $('.comiket-detail-outbound-collect-date-from').html('');
////        $('.comiket-detail-outbound-collect-date-to').html('');
//
//        $('select[name="comiket_detail_inbound_collect_date_year_sel"]').val('');
//        $('select[name="comiket_detail_inbound_collect_date_month_sel"]').val('');
//        $('select[name="comiket_detail_inbound_collect_date_day_sel"]').val('');
//        $('select[name="comiket_detail_inbound_collect_time_sel"]').val('');
//
//        $('select[name="comiket_detail_inbound_delivery_date_year_sel"]').val('');
//        $('select[name="comiket_detail_inbound_delivery_date_month_sel"]').val('');
//        $('select[name="comiket_detail_inbound_delivery_date_day_sel"]').val('');
//        $('select[name="comiket_detail_inbound_delivery_time_sel"]').val('');
//
//        $('input[name="comiket_detail_inbound_service_sel"]').attr("checked", false);
//
////        $('input[name^="comiket_box_inbound_num_ary"]').val('');
////        $('select[name="comiket_cargo_inbound_num_sel"]').val('');
////        $('input[name^="comiket_charter_inbound_num_ary"]').val('');
////
////        $('input[name="comiket_detail_inbound_note1"]').val('');
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 識別部分制御(ラジオボタン)
//////////////////////////////////////////////////////////////////////////////////////////////////////////
//    function isEmpty(obj){
//      return !Object.keys(obj).length;
//    }
    function dispComiketDiv() {
        var eventsubId = $('select[name="eventsub_sel"]').val();
//console.log("############## 101");
//console.log(g_eventsubList);
        if(!eventsubId) {
            return;
        }

//        if(isEmpty(g_eventsubList)) {
//console.log("############## 102");
//            return;
//        }

        var eventBusinessFlg = g_eventsubList[eventsubId]['business'];
        var eventIndividualFlg = g_eventsubList[eventsubId]['individual'];

        if(eventBusinessFlg == "1") {
            $('label.comiket_div' + G_DEV_BUSINESS).show(g_fifoVal);
        } else {
            $('label.comiket_div' + G_DEV_BUSINESS).hide(g_fifoVal);
        }

        if(eventIndividualFlg == "1") {
            $('label.comiket_div' + G_DEV_INDIVIDUAL).show(g_fifoVal);
        } else {
            $('label.comiket_div' + G_DEV_INDIVIDUAL).hide(g_fifoVal);
        }

        // 1つだけの場合はデフォルトチェックを付ける
        if(eventBusinessFlg == "1" && eventIndividualFlg != "1") {
            $('input#comiket_div' + G_DEV_BUSINESS).prop('checked', 'checked');
            $('input#comiket_div' + G_DEV_BUSINESS).attr('checked', 'checked');
        } else if(eventBusinessFlg != "1" && eventIndividualFlg == "1") {
            $('input#comiket_div' + G_DEV_INDIVIDUAL).prop('checked', 'checked');
            $('input#comiket_div' + G_DEV_INDIVIDUAL).attr('checked', 'checked');
        }
    }
    var g_first_flg = true;
    $('input[name="comiket_div"]').on('change', function() {
//        initCustomerInputArea(); // 顧客入力部分の初期化
        changeCustomerInputItem(); // 顧客入力部分の制御

        // 往復選択の表示制御
//        setEventsubDataFromDiv();
        dispComiketDetailType();


        // 搬入-時間帯指定不可地域に元ずく制御
        dispComiketDetailOutboundDeliveryTime();

        // 搬出-お届け日時・時間帯表示制御
//        dispComiketDetailInboundDeliveryDateTime();


        // お支払い方法部分の表示制御
        dispPaymentMethod();

        getBoxData();

        // 搬入-時間帯表示/非表示
//        dispComiketDetailOutboundDeliveryTimeByDivAndType();
//        dispComiketDetailOutboundCollectTimeByDivAndType();

        // 識別で変化する入力エリアクリア
//        var comiketDiv = $('input[name="comiket_div"]:checked').val();
//        if(!comiketDiv || comiketDiv == "") {
            clearInputByDivChange();
//        }

        // 注意文言の表示・非表示
        dispAttentionMessage();

        // 貼付票・説明書リンク表示/非表示 制御
        dispDocLinkEachEventsub();

        // 搬入-お預かり・お届け日表示制御
        dispOutboundColAndDelyDate();

        // 搬出-お預かり・お届け日表示制御
        dispInboundColAndDelyDate();

        // 法人のみ文言表示・非表示制御
        dispAttentionCompanuOnly();

        // 搬出-時間帯指定不可地域に元ずく制御
        dispComiketDetailInboundDeliveryTime();

        // 搬入搬出-サービス内容設定
        selectComiketDetailOutboundService();
        selectComiketDetailInboundService();

        // 搬入-時間帯表示/非表示
//        dispComiketDetailOutboundDeliveryTimeByDivAndType();
//        dispComiketDetailOutboundCollectTimeByDivAndType();

        // 搬入-時間帯表示/非表示
        dispOutboundColAndDlyDateTimeByDb();
        dispInboundColAndDlyDateTimeByDb();

        // 時間帯初期設定
        // comiket_detail_type_sel
        if($('input[name="comiket_detail_type_sel"]:checked').val() == "1" ) { // 搬入
            $('select[name=comiket_detail_outbound_delivery_time_sel]').val("00");
        }

        if($('input[name="comiket_detail_type_sel"]:checked').val() == "2" ) { // 搬出
            $('select[name=comiket_detail_inbound_delivery_time_sel]').val("00,指定なし");
        }

        if ($('input[name="comiket_div"]:checked').val() != G_DEV_INDIVIDUAL) { // 個人の場合
            $('input.btn_cstm_info_copy').hide(g_fifoVal);
        } else {
            $('input.btn_cstm_info_copy').show(g_fifoVal);
        }

//        // 郵便番号マーク 表示/非表示制御
//        dispZipMark();
    });//.first().trigger('change');

    function dispAttentionMessage() {
        var comiketDiv = $('input[name="comiket_div"]:checked').val();
        if(comiketDiv == G_DEV_BUSINESS) { // 法人
            $('.example_boxsize').hide(g_fifoVal);
            $('.convenience_store_laterpay_attention,.pay_digital_money_attention').hide(g_fifoVal);
        } else if(comiketDiv == G_DEV_INDIVIDUAL) { // 個人
            $('.example_boxsize').show(g_fifoVal);
            $('.convenience_store_laterpay_attention').show(g_fifoVal);
            var detailType = $('input[name="comiket_detail_type_sel"]:checked').val();
            if(detailType == "1") { // 搬入
                $('.pay_digital_money_attention').hide(g_fifoVal);
            } else {  // 搬出
                $('.pay_digital_money_attention').show(g_fifoVal);
            }
        } else {
            $('.example_boxsize').hide(g_fifoVal);
            $('.convenience_store_laterpay_attention,.pay_digital_money_attention').hide(g_fifoVal);
        }
    }
    dispAttentionMessage();

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 出展イベント部分制御
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     *
     * @returns {undefined}
     */
    function initInputAreaEvent() {
        $('span.event-place-lbl').html("");
        $('input[name="eventsub_address"]').val("");
        $('span.event-term_fr-lbl').html("");
        $('input[name="eventsub_term_fr"]').val("");
        $('span.event-term_to-lbl').html("");
        $('input[name="eventsub_term_to"]').val("");

        $('span.event-term_fr-str').hide(g_fifoVal);
        $('label.comiket_div1').hide(g_fifoVal);
        $('label.comiket_div2').hide(g_fifoVal);
//        $('select[name="eventsub_sel"]').hide(g_fifoVal);
    }

    function setEventsubDataFromDiv() {

//        var data = $("div.eventsub-info-list");
//        var data;

//console.log("####################### 701");
        g_eventsubList = {}
        $("div.eventsub-info-list").each(function() {
//console.log("####################### 702");
            var currentId = $(this).attr("eventsub-id");
            g_eventsubList[currentId] = {}
            g_eventsubList[currentId]['id'] = currentId;
            g_eventsubList[currentId]['business'] = $(this).attr("eventsub-business");
            g_eventsubList[currentId]['individual'] = $(this).attr("eventsub-individual");
            g_eventsubList[currentId]['place'] = $(this).attr("eventsub-place");
            g_eventsubList[currentId]['term_fr'] = $(this).attr("eventsub-term-fr");
            g_eventsubList[currentId]['term_to'] = $(this).attr("eventsub-term-to");
            g_eventsubList[currentId]['term_fr_nm'] = $(this).attr("eventsub-term-fr-nm");
            g_eventsubList[currentId]['term_to_nm'] = $(this).attr("eventsub-term-to-nm");

            g_eventsubList[currentId]['outbound_collect_fr'] = $(this).attr("eventsub-outbound-collect-fr");
            g_eventsubList[currentId]['outbound_collect_to'] = $(this).attr("eventsub-outbound-collect-to");
            g_eventsubList[currentId]['outbound_delivery_fr'] = $(this).attr("eventsub-outbound-delivery-fr");
            g_eventsubList[currentId]['outbound_delivery_to'] = $(this).attr("eventsub-outbound-delivery-to");

            g_eventsubList[currentId]['inbound_collect_fr'] = $(this).attr("eventsub-inbound-collect-fr");
            g_eventsubList[currentId]['inbound_collect_to'] = $(this).attr("eventsub-inbound-collect-to");
            g_eventsubList[currentId]['inbound_delivery_fr'] = $(this).attr("eventsub-inbound-delivery-fr");
            g_eventsubList[currentId]['inbound_delivery_to'] = $(this).attr("eventsub-inbound-delivery-to");

            g_eventsubList[currentId]['is_departure_date_range'] = $(this).attr("eventsub-is-departure-date-range");
            g_eventsubList[currentId]['is_arrival_date_range'] = $(this).attr("eventsub-is-arrival-date-range");

            g_eventsubList[currentId]['is_booth_position'] = $(this).attr("eventsub-is-booth-position");

            g_eventsubList[currentId]['is_eq_outbound_collect'] = $(this).attr("eventsub-is_eq_outbound_collect");
            g_eventsubList[currentId]['is_eq_outbound_delivery'] = $(this).attr("eventsub-is_eq_outbound_delivery");
            g_eventsubList[currentId]['is_eq_inbound_collect'] = $(this).attr("eventsub-is_eq_inbound_collect");
            g_eventsubList[currentId]['is_eq_inbound_delivery'] = $(this).attr("eventsub-is_eq_inbound_delivery");

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            g_eventsubList[currentId]['outbound_collect_fr_year'] = $(this).attr("eventsub-outbound_collect_fr_year");
            g_eventsubList[currentId]['outbound_collect_fr_month'] = $(this).attr("eventsub-outbound_collect_fr_month");
            g_eventsubList[currentId]['outbound_collect_fr_day'] = $(this).attr("eventsub-outbound_collect_fr_day");

            g_eventsubList[currentId]['outbound_collect_to_year'] = $(this).attr("eventsub-outbound_collect_to_year");
            g_eventsubList[currentId]['outbound_collect_to_month'] = $(this).attr("eventsub-outbound_collect_to_month");
            g_eventsubList[currentId]['outbound_collect_to_day'] = $(this).attr("eventsub-outbound_collect_to_day");

            g_eventsubList[currentId]['outbound_delivery_fr_year'] = $(this).attr("eventsub-outbound_delivery_fr_year");
            g_eventsubList[currentId]['outbound_delivery_fr_month'] = $(this).attr("eventsub-outbound_delivery_fr_month");
            g_eventsubList[currentId]['outbound_delivery_fr_day'] = $(this).attr("eventsub-outbound_delivery_fr_day");

            g_eventsubList[currentId]['outbound_delivery_to_year'] = $(this).attr("eventsub-outbound_delivery_to_year");
            g_eventsubList[currentId]['outbound_delivery_to_month'] = $(this).attr("eventsub-outbound_delivery_to_month");
            g_eventsubList[currentId]['outbound_delivery_to_day'] = $(this).attr("eventsub-outbound_delivery_to_day");


            g_eventsubList[currentId]['inbound_collect_fr_year'] = $(this).attr("eventsub-inbound_collect_fr_year");
            g_eventsubList[currentId]['inbound_collect_fr_month'] = $(this).attr("eventsub-inbound_collect_fr_month");
            g_eventsubList[currentId]['inbound_collect_fr_day'] = $(this).attr("eventsub-inbound_collect_fr_day");

            g_eventsubList[currentId]['inbound_collect_to_year'] = $(this).attr("eventsub-inbound_collect_to_year");
            g_eventsubList[currentId]['inbound_collect_to_month'] = $(this).attr("eventsub-inbound_collect_to_month");
            g_eventsubList[currentId]['inbound_collect_to_day'] = $(this).attr("eventsub-inbound_collect_to_day");

            g_eventsubList[currentId]['inbound_delivery_fr_year'] = $(this).attr("eventsub-inbound_delivery_fr_year");
            g_eventsubList[currentId]['inbound_delivery_fr_month'] = $(this).attr("eventsub-inbound_delivery_fr_month");
            g_eventsubList[currentId]['inbound_delivery_fr_day'] = $(this).attr("eventsub-inbound_delivery_fr_day");

            g_eventsubList[currentId]['inbound_delivery_to_year'] = $(this).attr("eventsub-inbound_delivery_to_year");
            g_eventsubList[currentId]['inbound_delivery_to_month'] = $(this).attr("eventsub-inbound_delivery_to_month");
            g_eventsubList[currentId]['inbound_delivery_to_day'] = $(this).attr("eventsub-inbound_delivery_to_day");

            g_eventsubList[currentId]['is_manual_display'] = $(this).attr("eventsub-is_manual_display");
            g_eventsubList[currentId]['is_paste_display'] = $(this).attr("eventsub-is_paste_display");
            g_eventsubList[currentId]['is_building_display'] = $(this).attr("eventsub-is_building_display");
            g_eventsubList[currentId]['is_booth_display'] = $(this).attr("eventsub-is_booth_display");


            g_eventsubList[currentId]['kojin_box_col_date_flg'] = $(this).attr("eventsub-kojin_box_col_date_flg");
            g_eventsubList[currentId]['kojin_box_col_time_flg'] = $(this).attr("eventsub-kojin_box_col_time_flg");
            g_eventsubList[currentId]['kojin_box_dlv_date_flg'] = $(this).attr("eventsub-kojin_box_dlv_date_flg");
            g_eventsubList[currentId]['kojin_box_dlv_time_flg'] = $(this).attr("eventsub-kojin_box_dlv_time_flg");
            g_eventsubList[currentId]['kojin_cag_col_date_flg'] = $(this).attr("eventsub-kojin_cag_col_date_flg");
            g_eventsubList[currentId]['kojin_cag_col_time_flg'] = $(this).attr("eventsub-kojin_cag_col_time_flg");
            g_eventsubList[currentId]['kojin_cag_dlv_date_flg'] = $(this).attr("eventsub-kojin_cag_dlv_date_flg");
            g_eventsubList[currentId]['kojin_cag_dlv_time_flg'] = $(this).attr("eventsub-kojin_cag_dlv_time_flg");
            g_eventsubList[currentId]['hojin_box_col_date_flg'] = $(this).attr("eventsub-hojin_box_col_date_flg");
            g_eventsubList[currentId]['hojin_box_col_time_flg'] = $(this).attr("eventsub-hojin_box_col_time_flg");
            g_eventsubList[currentId]['hojin_box_dlv_date_flg'] = $(this).attr("eventsub-hojin_box_dlv_date_flg");
            g_eventsubList[currentId]['hojin_box_dlv_time_flg'] = $(this).attr("eventsub-hojin_box_dlv_time_flg");
            g_eventsubList[currentId]['hojin_cag_col_date_flg'] = $(this).attr("eventsub-hojin_cag_col_date_flg");
            g_eventsubList[currentId]['hojin_cag_col_time_flg'] = $(this).attr("eventsub-hojin_cag_col_time_flg");
            g_eventsubList[currentId]['hojin_cag_dlv_date_flg'] = $(this).attr("eventsub-hojin_cag_dlv_date_flg");
            g_eventsubList[currentId]['hojin_cag_dlv_time_flg'] = $(this).attr("eventsub-hojin_cag_dlv_time_flg");
            g_eventsubList[currentId]['hojin_kas_col_date_flg'] = $(this).attr("eventsub-hojin_kas_col_date_flg");
            g_eventsubList[currentId]['hojin_kas_col_time_flg'] = $(this).attr("eventsub-hojin_kas_col_time_flg");
            g_eventsubList[currentId]['hojin_kas_dlv_date_flg'] = $(this).attr("eventsub-hojin_kas_dlv_date_flg");
            g_eventsubList[currentId]['hojin_kas_dlv_time_flg'] = $(this).attr("eventsub-hojin_kas_dlv_time_flg");

            // 引渡フラグ
            g_eventsubList[currentId]['kojin_box_del_date_flg'] = $(this).attr("eventsub-kojin_box_del_date_flg");
            g_eventsubList[currentId]['kojin_box_del_time_flg'] = $(this).attr("eventsub-kojin_box_del_time_flg");
            g_eventsubList[currentId]['hojin_box_del_date_flg'] = $(this).attr("eventsub-hojin_box_del_date_flg");
            g_eventsubList[currentId]['hojin_box_del_time_flg'] = $(this).attr("eventsub-hojin_box_del_time_flg");

            g_eventsubList[currentId]['kojin_box_col_flg'] = $(this).attr("eventsub-kojin_box_col_flg");
            g_eventsubList[currentId]['kojin_box_dlv_flg'] = $(this).attr("eventsub-kojin_box_dlv_flg");
            g_eventsubList[currentId]['kojin_cag_col_flg'] = $(this).attr("eventsub-kojin_cag_col_flg");
            g_eventsubList[currentId]['kojin_cag_dlv_flg'] = $(this).attr("eventsub-kojin_cag_dlv_flg");
            g_eventsubList[currentId]['hojin_box_col_flg'] = $(this).attr("eventsub-hojin_box_col_flg");
            g_eventsubList[currentId]['hojin_box_dlv_flg'] = $(this).attr("eventsub-hojin_box_dlv_flg");
            g_eventsubList[currentId]['hojin_cag_col_flg'] = $(this).attr("eventsub-hojin_cag_col_flg");
            g_eventsubList[currentId]['hojin_cag_dlv_flg'] = $(this).attr("eventsub-hojin_cag_dlv_flg");
            g_eventsubList[currentId]['hojin_kas_col_flg'] = $(this).attr("eventsub-hojin_kas_col_flg");
            g_eventsubList[currentId]['hojin_kas_dlv_flg'] = $(this).attr("eventsub-hojin_kas_dlv_flg");

//console.log("############501");
//console.log(g_eventsubList);
        });

        dispComiketDiv();


    }

    function getEventsubData(isFirstSelected) {
        // イベントサブ情報取得
        sgwns.api('/common/php/SearchEventsub.php', getFormData(), (function(data) {
            var selectval = $("select[name=eventsub_sel]").val();
            $("select[name=eventsub_sel] option").each(function () {
                // 最初の「選択してください」は削除しない
                if($(this).val() == '') {
                    return true;
                }
                $(this).remove();
            });
            g_eventsubList = {};
            for(var i = 0; i < data.ids.length; i++) {
                $("select[name=eventsub_sel]").append($("<option>").val(data.ids[i]).text(data.names[i]));

                g_eventsubList[data.ids[i]] = {}
                g_eventsubList[data.ids[i]]['id'] = data.list[i].id;
                g_eventsubList[data.ids[i]]['business'] = data.list[i].business;
                g_eventsubList[data.ids[i]]['individual'] = data.list[i].individual;
                g_eventsubList[data.ids[i]]['place'] = data.list[i].place;
                g_eventsubList[data.ids[i]]['term_fr'] = data.list[i].term_fr;
                g_eventsubList[data.ids[i]]['term_to'] = data.list[i].term_to;
                g_eventsubList[data.ids[i]]['term_fr_nm'] = data.list[i].term_fr_nm;
                g_eventsubList[data.ids[i]]['term_to_nm'] = data.list[i].term_to_nm;

                g_eventsubList[data.ids[i]]['outbound_collect_fr'] = data.list[i].outbound_collect_fr;
                g_eventsubList[data.ids[i]]['outbound_collect_to'] = data.list[i].outbound_collect_to;
                g_eventsubList[data.ids[i]]['outbound_delivery_fr'] = data.list[i].outbound_delivery_fr;
                g_eventsubList[data.ids[i]]['outbound_delivery_to'] = data.list[i].outbound_delivery_to;

                g_eventsubList[data.ids[i]]['inbound_collect_fr'] = data.list[i].inbound_collect_fr;
                g_eventsubList[data.ids[i]]['inbound_collect_to'] = data.list[i].inbound_collect_to;
                g_eventsubList[data.ids[i]]['inbound_delivery_fr'] = data.list[i].inbound_delivery_fr;
                g_eventsubList[data.ids[i]]['inbound_delivery_to'] = data.list[i].inbound_delivery_to;

                g_eventsubList[data.ids[i]]['is_departure_date_range'] = data.list[i].is_departure_date_range;
                g_eventsubList[data.ids[i]]['is_arrival_date_range'] = data.list[i].is_arrival_date_range;

                g_eventsubList[data.ids[i]]['is_eq_outbound_collect'] = data.list[i].is_eq_outbound_collect;
                g_eventsubList[data.ids[i]]['is_eq_outbound_delivery'] = data.list[i].is_eq_outbound_delivery;
                g_eventsubList[data.ids[i]]['is_eq_inbound_collect'] = data.list[i].is_eq_inbound_collect;
                g_eventsubList[data.ids[i]]['is_eq_inbound_delivery'] = data.list[i].is_eq_inbound_delivery;

                g_eventsubList[data.ids[i]]['is_booth_position'] = data.list[i].is_booth_position;

                g_eventsubList[data.ids[i]]['outbound_collect_fr_year'] = data.list[i].outbound_collect_fr_year;
                g_eventsubList[data.ids[i]]['outbound_collect_fr_month'] = data.list[i].outbound_collect_fr_month;
                g_eventsubList[data.ids[i]]['outbound_collect_fr_day'] = data.list[i].outbound_collect_fr_day;

                g_eventsubList[data.ids[i]]['outbound_collect_to_year'] = data.list[i].outbound_collect_to_year;
                g_eventsubList[data.ids[i]]['outbound_collect_to_month'] = data.list[i].outbound_collect_to_month;
                g_eventsubList[data.ids[i]]['outbound_collect_to_day'] = data.list[i].outbound_collect_to_day;

                g_eventsubList[data.ids[i]]['outbound_delivery_fr_year'] = data.list[i].outbound_delivery_fr_year;
                g_eventsubList[data.ids[i]]['outbound_delivery_fr_month'] = data.list[i].outbound_delivery_fr_month;
                g_eventsubList[data.ids[i]]['outbound_delivery_fr_day'] = data.list[i].outbound_delivery_fr_day;

                g_eventsubList[data.ids[i]]['outbound_delivery_to_year'] = data.list[i].outbound_delivery_to_year;
                g_eventsubList[data.ids[i]]['outbound_delivery_to_month'] = data.list[i].outbound_delivery_to_month;
                g_eventsubList[data.ids[i]]['outbound_delivery_to_day'] = data.list[i].outbound_delivery_to_day;


                g_eventsubList[data.ids[i]]['inbound_collect_fr_year'] = data.list[i].inbound_collect_fr_year;
                g_eventsubList[data.ids[i]]['inbound_collect_fr_month'] = data.list[i].inbound_collect_fr_month;
                g_eventsubList[data.ids[i]]['inbound_collect_fr_day'] = data.list[i].inbound_collect_fr_day;

                g_eventsubList[data.ids[i]]['inbound_collect_to_year'] = data.list[i].inbound_collect_to_year;
                g_eventsubList[data.ids[i]]['inbound_collect_to_month'] = data.list[i].inbound_collect_to_month;
                g_eventsubList[data.ids[i]]['inbound_collect_to_day'] = data.list[i].inbound_collect_to_day;

                g_eventsubList[data.ids[i]]['inbound_delivery_fr_year'] = data.list[i].inbound_delivery_fr_year;
                g_eventsubList[data.ids[i]]['inbound_delivery_fr_month'] = data.list[i].inbound_delivery_fr_month;
                g_eventsubList[data.ids[i]]['inbound_delivery_fr_day'] = data.list[i].inbound_delivery_fr_day;

                g_eventsubList[data.ids[i]]['inbound_delivery_to_year'] = data.list[i].inbound_delivery_to_year;
                g_eventsubList[data.ids[i]]['inbound_delivery_to_month'] = data.list[i].inbound_delivery_to_month;
                g_eventsubList[data.ids[i]]['inbound_delivery_to_day'] = data.list[i].inbound_delivery_to_day;

                g_eventsubList[data.ids[i]]['is_manual_display'] =data.list[i].is_manual_display;
                g_eventsubList[data.ids[i]]['is_paste_display'] = data.list[i].is_paste_display;
                g_eventsubList[data.ids[i]]['is_building_display'] = data.list[i].is_building_display;
                g_eventsubList[data.ids[i]]['is_booth_display'] = data.list[i].is_booth_display;

                g_eventsubList[data.ids[i]]['kojin_box_col_date_flg'] = data.list[i].kojin_box_col_date_flg;
                g_eventsubList[data.ids[i]]['kojin_box_col_time_flg'] = data.list[i].kojin_box_col_time_flg;
                g_eventsubList[data.ids[i]]['kojin_box_dlv_date_flg'] = data.list[i].kojin_box_dlv_date_flg;
                g_eventsubList[data.ids[i]]['kojin_box_dlv_time_flg'] = data.list[i].kojin_box_dlv_time_flg;
                g_eventsubList[data.ids[i]]['kojin_cag_col_date_flg'] = data.list[i].kojin_cag_col_date_flg;
                g_eventsubList[data.ids[i]]['kojin_cag_col_time_flg'] = data.list[i].kojin_cag_col_time_flg;
                g_eventsubList[data.ids[i]]['kojin_cag_dlv_date_flg'] = data.list[i].kojin_cag_dlv_date_flg;
                g_eventsubList[data.ids[i]]['kojin_cag_dlv_time_flg'] = data.list[i].kojin_cag_dlv_time_flg;
                g_eventsubList[data.ids[i]]['hojin_box_col_date_flg'] = data.list[i].hojin_box_col_date_flg;
                g_eventsubList[data.ids[i]]['hojin_box_col_time_flg'] = data.list[i].hojin_box_col_time_flg;
                g_eventsubList[data.ids[i]]['hojin_box_dlv_date_flg'] = data.list[i].hojin_box_dlv_date_flg;
                g_eventsubList[data.ids[i]]['hojin_box_dlv_time_flg'] = data.list[i].hojin_box_dlv_time_flg;
                g_eventsubList[data.ids[i]]['hojin_cag_col_date_flg'] = data.list[i].hojin_cag_col_date_flg;
                g_eventsubList[data.ids[i]]['hojin_cag_col_time_flg'] = data.list[i].hojin_cag_col_time_flg;
                g_eventsubList[data.ids[i]]['hojin_cag_dlv_date_flg'] = data.list[i].hojin_cag_dlv_date_flg;
                g_eventsubList[data.ids[i]]['hojin_cag_dlv_time_flg'] = data.list[i].hojin_cag_dlv_time_flg;
                g_eventsubList[data.ids[i]]['hojin_kas_col_date_flg'] = data.list[i].hojin_kas_col_date_flg;
                g_eventsubList[data.ids[i]]['hojin_kas_col_time_flg'] = data.list[i].hojin_kas_col_time_flg;
                g_eventsubList[data.ids[i]]['hojin_kas_dlv_date_flg'] = data.list[i].hojin_kas_dlv_date_flg;
                g_eventsubList[data.ids[i]]['hojin_kas_dlv_time_flg'] = data.list[i].hojin_kas_dlv_time_flg;

                // 引渡フラグ
                g_eventsubList[data.ids[i]]['kojin_box_del_date_flg'] = data.list[i].kojin_box_del_date_flg;
                g_eventsubList[data.ids[i]]['kojin_box_del_time_flg'] = data.list[i].kojin_box_del_time_flg;
                g_eventsubList[data.ids[i]]['hojin_box_del_date_flg'] = data.list[i].hojin_box_del_date_flg;
                g_eventsubList[data.ids[i]]['hojin_box_del_time_flg'] = data.list[i].hojin_box_del_time_flg;

                g_eventsubList[data.ids[i]]['kojin_box_col_flg'] = data.list[i].kojin_box_col_flg;
                g_eventsubList[data.ids[i]]['kojin_box_dlv_flg'] = data.list[i].kojin_box_dlv_flg;
                g_eventsubList[data.ids[i]]['kojin_cag_col_flg'] = data.list[i].kojin_cag_col_flg;
                g_eventsubList[data.ids[i]]['kojin_cag_dlv_flg'] = data.list[i].kojin_cag_dlv_flg;
                g_eventsubList[data.ids[i]]['hojin_box_col_flg'] = data.list[i].hojin_box_col_flg;
                g_eventsubList[data.ids[i]]['hojin_box_dlv_flg'] = data.list[i].hojin_box_dlv_flg;
                g_eventsubList[data.ids[i]]['hojin_cag_col_flg'] = data.list[i].hojin_cag_col_flg;
                g_eventsubList[data.ids[i]]['hojin_cag_dlv_flg'] = data.list[i].hojin_cag_dlv_flg;
                g_eventsubList[data.ids[i]]['hojin_kas_col_flg'] = data.list[i].hojin_kas_col_flg;
                g_eventsubList[data.ids[i]]['hojin_kas_dlv_flg'] = data.list[i].hojin_kas_dlv_flg;



            }
            $("select[name=eventsub_sel]").val(selectval);
            dispComiketDiv();

            if(isFirstSelected && $("select[name=eventsub_sel]").children('option').length == 2) {
                $("select[name=eventsub_sel] option").each(function () {
                    // 最初の「選択してください」は削除しない
                    if($(this).val() == '') {
                        return true;
                    }
                    $("select[name=eventsub_sel]").val($(this).val()).trigger("change");
                });
            }

//console.log("################## 10");
//console.log(selectval);
        }));
    }
//    getEventsubData();
    /**
     *
     * @returns {undefined}
     */
    function selectInputAreaEvent() {
        getEventsubData();
    }
    if(($('select[name="eventsub_sel"]').val() && $('select[name="eventsub_sel"]').val() != "")
            || ($('select[name="event_sel"]').val())) {
//console.log("################## 10");
          setEventsubDataFromDiv();
          if(!$('select[name="eventsub_sel"]').val() || $('select[name="eventsub_sel"]') == "") { // イベントサブが空の場合
              initInputAreaEvent();
          }
    } else {
        initInputAreaEvent();
    }
    {
        var eventsubId = $('select[name="eventsub_sel"]').val();
        if(eventsubId && eventsubId != "") {
            var termFrNm = g_eventsubList[eventsubId]['term_fr_nm'];
            var termFr = g_eventsubList[eventsubId]['term_fr'];
            if(termFr && termFr != "") {
                $('.event-term_fr-str').show(g_fifoVal);
            } else {
                $('.event-term_fr-str').hide(g_fifoVal);
            }
        }
    }

    $('select[name="eventsub_sel"]').on('change', function() {
        $("input[name=comiket_div]").attr("checked", false);
        $("select[name=building_name_sel] option,select[name=building_booth_position_sel] option").each(function () {
            // 最初の「選択してください」は削除しない
            if($(this).val() == '') {
                return true;
            }
            $(this).remove();
        });
//console.log("################## 601-3");
        //$('input[name="comiket_div"]')
        var eventsubSel = $(this).val();
        if(eventsubSel && eventsubSel != "") {
//            $('label.radio-label').show(g_fifoVal);

            var eventsubId = $('select[name="eventsub_sel"]').val();
            var eventPlace = g_eventsubList[eventsubId]['place'];

            $('span.event-place-lbl').html(eventPlace);
            $('input[name="eventsub_address"]').val(eventPlace);

            var termFrNm = g_eventsubList[eventsubId]['term_fr_nm'];
            var termFr = g_eventsubList[eventsubId]['term_fr'];
            $('span.event-term_fr-lbl').html(termFrNm);
            $('input[name="eventsub_term_fr"]').val(termFr);

            var termToNm = g_eventsubList[eventsubId]['term_to_nm'];
            var termTo = g_eventsubList[eventsubId]['term_to'];
            $('span.event-term_to-lbl').html(termToNm);
            $('input[name="eventsub_term_to"]').val(termTo);

            if(termFr && termFr != "") {
                $('.event-term_fr-str').show(g_fifoVal);
            } else {
                $('.event-term_fr-str').hide(g_fifoVal);
            }

            dispComiketDiv();

            var eventOutboundCollectFr = g_eventsubList[eventsubId]['outbound_collect_fr'];
            var eventOutboundCollectTo = g_eventsubList[eventsubId]['outbound_collect_to'];
            var eventOutboundDeliveryFr = g_eventsubList[eventsubId]['outbound_delivery_fr'];
            var eventOutboundDeliveryTo = g_eventsubList[eventsubId]['outbound_delivery_to'];

            var eventInboundCollectFr = g_eventsubList[eventsubId]['inbound_collect_fr'];
            var eventInboundCollectTo = g_eventsubList[eventsubId]['inbound_collect_to'];
            var eventInboundDeliveryFr = g_eventsubList[eventsubId]['inbound_delivery_fr'];
            var eventInboundDeliveryTo = g_eventsubList[eventsubId]['inbound_delivery_to'];

            $(".comiket-detail-outbound-collect-date-from").html(eventOutboundCollectFr);
            $(".comiket-detail-outbound-collect-date-to").html(eventOutboundCollectTo);
            $(".comiket-detail-outbound-delivery-date-from").html(eventOutboundDeliveryFr);
            $(".comiket-detail-outbound-delivery-date-to").html(eventOutboundDeliveryTo);

            $(".comiket-detail-inbound-collect-date-from").html(eventInboundCollectFr);
            $(".comiket-detail-inbound-collect-date-to").html(eventInboundCollectTo);
            $(".comiket-detail-inbound-delivery-date-from").html(eventInboundDeliveryFr);
            $(".comiket-detail-inbound-delivery-date-to").html(eventInboundDeliveryTo);

//            setRoundTranceportData();

            // ブース番号取得
            sgwns.api('/common/php/SearchBuilding.php', getFormData(), (function(data) {
//                $("select[name=building_booth_id_sel]").append($("<option>").val('').text("選択してください"));
                for(var i = 0; i < data.ids.length; i++) {
                    $("select[name=building_name_sel]").append($("<option>").val(data.ids[i]).text(data.names[i]));
                }
            }));

//            changeCustomerInputItem(); // 顧客入力部分の表示制御
        } else if(!eventsubSel || eventsubSel == "") {
            $("input[name=comiket_div]").attr("checked", false);

            initInputAreaEvent(); // イベント部分の表示制御
            // 往復選択の表示制御
            dispComiketDetailType();
            $('.input-outbound').hide(g_fifoVal);
            $('.input-inbound').hide(g_fifoVal);
            $("input[name=comiket_detail_type_sel]").attr("checked", false);
            $("input[name=comiket_div]").attr("checked", false);
        }

        changeCustomerInputItem(); // 顧客入力部分の表示制御
        // 往復選択の表示制御
        dispComiketDetailType();
        selectComiketDetailType();

        selectComiketDetailOutboundService();
        selectComiketDetailInboundService();

        // お支払い方法部分の表示制御
        dispPaymentMethod();

        // 搬入-時間帯表示/非表示
//        dispComiketDetailOutboundDeliveryTimeByDivAndType();
//        dispComiketDetailOutboundCollectTimeByDivAndType();
        // 搬出-時間帯表示/非表示
//        dispComiketDetailInboundDeliveryTimeByDivAndType();

        // 注意文言の表示・非表示
        dispAttentionMessage();

        // 貼付票・説明書リンク表示/非表示 制御
        dispDocLinkEachEventsub();

        // ブース位置表示・非表示制御
        dispBoothPosition();

        // コミケのみ文言表示・非表示制御
        dispAttentionEventOnly();

        // 法人のみ文言表示・非表示制御
        dispAttentionCompanuOnly();
    });

    $('select[name="event_sel"]').on('change', function() {
//        initCustomerInputArea(); // 顧客入力部分の初期化
//
        $("input[name=comiket_div]").attr("checked", false);

        $("select[name=building_name_sel] option,select[name=building_booth_position_sel] option").each(function () {
            // 最初の「選択してください」は削除しない
            if($(this).val() == '') {
                return true;
            }
            $(this).remove();
        });

        $("select[name=eventsub_sel] option").each(function () {
            // 最初の「選択してください」は削除しない
            if($(this).val() == '') {
                return true;
            }
            $(this).remove();
        });

        if($(this).val() && $(this).val() != "") {
//alert("test1");
            // イベントが選択されている場合は、イベントサブ情報取得
            getEventsubData(true);
        }

        // イベントサブに１件のみだったら、その１件を自動選択する
//        $("select[name=eventsub_sel] option").length;
//console.log($("select[name=eventsub_sel] option"));
//console.log($("select[name=event_sel]").children('option'));
//console.log($("select[name=eventsub_sel]").children());
//console.log($("select[name=eventsub_sel]"));
        if($("select[name=eventsub_sel]").children('option').length == 2) {
//            $("select[name=eventsub_sel]").val(selectval);
            $("select[name=eventsub_sel] option").each(function () {
                if($(this).val() == '') {
                    return true;
                }
                $("select[name=eventsub_sel]").val($(this).val());
                return false;
            });
        }

        // イベント部分の表示制御
        initInputAreaEvent();

        // 往復選択の表示制御
        dispComiketDetailType();

        $('.input-outbound').hide(g_fifoVal);
        $('.input-inbound').hide(g_fifoVal);
        $("input[name=comiket_detail_type_sel]").attr("checked", false);
        $("input[name=comiket_div]").attr("checked", false);

        // 顧客入力部分の表示制御
        changeCustomerInputItem();

        // お支払い方法部分の表示制御
        dispPaymentMethod();

        // 顧客入力部分の表示制御
        changeCustomerInputItem();

        // 注意文言の表示・非表示
        dispAttentionMessage();

        // 貼付票・説明書リンク表示/非表示 制御
        dispDocLinkEachEventsub();

        // ブース位置表示・非表示制御
        dispBoothPosition();

        // コミケのみ文言表示・非表示制御
        dispAttentionEventOnly();

        // 法人のみ文言表示・非表示制御
        dispAttentionCompanuOnly();
//console.log($("select[name=eventsub_sel] option"));
        // 受付時間超過文言表示制御
        seigyoEveTimeOver();

    });

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 貼付票・説明書リンク
//////////////////////////////////////////////////////////////////////////////////////////////////////////

    // キャッシュ対策
    var currentDate = new Date();
    var strCurrentDate = '' + currentDate.getFullYear() + (currentDate.getMonth() + 1) + currentDate.getDate() + currentDate.getHours() + currentDate.getMinutes();
    function dispDocLinkEachEventsub() {
        var eventSel = $('select[name="event_sel"]').val();
        var eventsubSel = $('select[name="eventsub_sel"]').val();
        var eventsubSelText = $('select[name="eventsub_sel"] option:selected').text();
        var eventSelText = $('select[name="event_sel"] option:selected').text();
        if(eventsubSel && eventsubSel != "") {
            // 暫定対応（今後マスタが増えた場合には対応の検討必要あり）
            // イベントサブコードが'11'（コミケ）の場合には説明書のリンクは表示しない
            $('.eventsub_dl_link').show(g_fifoVal);
//            if (eventsubSel != '11' && eventSel != '4') {
            if(g_eventsubList[eventsubSel]['is_manual_display']) {
//                $('.paste_tag_link').attr("href", "/evp/pdf/paste_tag/paste_tag_" + eventsubSel + ".pdf");
//                $('.manual_link').attr("href", "/evp/pdf/manual/manual_" + eventsubSel + ".pdf");

                $('.manual').show(g_fifoVal);
                eventSelText = eventSelText.replace(/\s+/g, "");
                $('.manual_link').attr("href", "/evp/pdf/manual/" + eventSelText +  ".pdf" + '?' + strCurrentDate);
            } else {
                $('.manual').hide(g_fifoVal);
            }
            if(g_eventsubList[eventsubSel]['is_paste_display']){
                $('.paste_tag').show(g_fifoVal);
                //$('.paste_tag_link').attr("href", "/evp/pdf/paste_tag/paste_tag_" + eventsubSel + ".pdf" + '?' + strCurrentDate);
            } else {
                $('.paste_tag').hide(g_fifoVal);
            }
        } else {
            $('.eventsub_dl_link').hide(g_fifoVal);
        }
    }
    // 貼付票・説明書リンク表示/非表示 制御
    dispDocLinkEachEventsub();

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// ブース位置(booth_position)
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    function dispBoothPosition() {
        var eventSel = $('select[name="event_sel"]').val();
        var eventsubId = $('select[name="eventsub_sel"]').val();

        if(g_eventsubList[eventsubId]['is_building_display']) {
            $('.class_building_name_sel').show();
        } else {
            $('.class_building_name_sel').hide();
        }

        if(g_eventsubList[eventsubId]['is_booth_display']) {
            $('.class_comiket_booth_name').show();
        } else {
            $('.class_comiket_booth_name').hide();
        }


//console.log("################### 555");
//console.log(eventsubId);
//console.log(g_eventsubList);
        if(eventsubId && eventsubId != "" && g_eventsubList[eventsubId]['is_booth_position']) {
            $('select[name="building_booth_position_sel"]').show(g_fifoVal);
        } else {
            $('select[name="building_booth_position_sel"]').hide(g_fifoVal);
        }
    }
    // ブース位置表示・非表示制御
    dispBoothPosition();

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// ブース名
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    // ブース名変更に伴うブースポジション取得
    $('select[name="building_name_sel"]').on('change', function() {
//console.log("##################### 07021330");
            $("select[name=building_booth_position_sel] option").each(function () {
                // 最初の「選択してください」は削除しない
                if($(this).val() == '') {
                    return true;
                }
                $(this).remove();
            });
//console.log("##################### 07021330-1");
            // ブース番号取得
            sgwns.api('/common/php/SearchBuildingBoothPostion.php', getFormData(), (function(data) {
//console.log("##################### 07021330-2");
//                $("select[name=building_booth_id_sel]").append($("<option>").val('').text("選択してください"));
                for(var i = 0; i < data.ids.length; i++) {
                    $("select[name=building_booth_position_sel]").append($("<option>").val(data.ids[i]).text(data.names[i]));
                }
            }));
    });

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// コミケ注意文言表示・非表示制御
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    function dispAttentionEventOnly() {
//console.log("############ dispAttentionEventOnly");
        var eventId = $('select[name="event_sel"]').val();

        if(eventId == '2') { // イベント = コミケ
            $('.disp_comiket').show(g_fifoVal);
            $('.disp_design').hide(g_fifoVal);
            $('.disp_gooutcamp').hide(g_fifoVal);
        } else if(eventId == '1' || eventId == '3') {
            $('.disp_comiket').hide(g_fifoVal);
            $('.disp_design').show(g_fifoVal);
            $('.disp_gooutcamp').hide(g_fifoVal);
        } else if(eventId == '4') {
            $('.disp_comiket').hide(g_fifoVal);
            $('.disp_design').hide(g_fifoVal);
            $('.disp_gooutcamp').show(g_fifoVal);
        } else {
            $('.disp_comiket').hide(g_fifoVal);
            $('.disp_design').hide(g_fifoVal);
            $('.disp_gooutcamp').hide(g_fifoVal);
            $('.disp_etc').show(g_fifoVal);
        }
    }

    dispAttentionEventOnly();

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 法人注意文言表示・非表示制御
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    function dispAttentionCompanuOnly() {
//console.log("############ dispAttentionEventOnly");
        var div = $('input[name="comiket_div"]:checked').val();

        if(div == G_DEV_BUSINESS) { // 法人
            $('.disp_company').show(g_fifoVal);
        } else {
            $('.disp_company').hide(g_fifoVal);
        }
    }

    dispAttentionCompanuOnly();

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 搬入、搬出 お預かり日時、お届け日時設定
//////////////////////////////////////////////////////////////////////////////////////////////////////////

//    function setRoundTranceportData() {
////console.log("############ aaa");
//        var eventsubId = $('select[name="eventsub_sel"]').val();
//
//        var eventOutboundCollectFr = g_eventsubList[eventsubId]['outbound_collect_fr'];
//        var eventOutboundCollectTo = g_eventsubList[eventsubId]['outbound_collect_to'];
//        var eventOutboundDeliveryFr = g_eventsubList[eventsubId]['outbound_delivery_fr'];
//        var eventOutboundDeliveryTo = g_eventsubList[eventsubId]['outbound_delivery_to'];
//
//        var eventInboundCollectFr = g_eventsubList[eventsubId]['inbound_collect_fr'];
//        var eventInboundCollectTo = g_eventsubList[eventsubId]['inbound_collect_to'];
//        var eventInboundDeliveryFr = g_eventsubList[eventsubId]['inbound_delivery_fr'];
//        var eventInboundDeliveryTo = g_eventsubList[eventsubId]['inbound_delivery_to'];
//
//        $(".comiket-detail-outbound-collect-date-from").html(eventOutboundCollectFr);
//        $(".comiket-detail-outbound-collect-date-to").html(eventOutboundCollectTo);
//        $(".comiket-detail-outbound-delivery-date-from").html(eventOutboundDeliveryFr);
//        $(".comiket-detail-outbound-delivery-date-to").html(eventOutboundDeliveryTo);
//
//        $(".comiket-detail-inbound-collect-date-from").html(eventInboundCollectFr);
//        $(".comiket-detail-inbound-collect-date-to").html(eventInboundCollectTo);
//        $(".comiket-detail-inbound-delivery-date-from").html(eventInboundDeliveryFr);
//        $(".comiket-detail-inbound-delivery-date-to").html(eventInboundDeliveryTo);
//    }
//    setRoundTranceportData();


//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 往復選択 部分制御
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     *
     * @returns {undefined}
     */
    function selectComiketDetailType() {
        if(!$('input[name="comiket_div"]:checked').val()) {
            // 識別未チェックの場合、搬入搬出の入力項目を非表示
            $('.input-outbound').hide(g_fifoVal);
            $('.input-inbound').hide(g_fifoVal);
            return;
        }

        if($('input[name=comiket_detail_type_sel]:checked').val() == '1') { // 搬入
            $('.input-outbound').show(g_fifoVal);
            $('.input-inbound').hide(g_fifoVal);
        } else if($('input[name=comiket_detail_type_sel]:checked').val() == '2') { // 搬出
            $('.input-outbound').hide(g_fifoVal);
            $('.input-inbound').show(g_fifoVal);
        } else if($('input[name=comiket_detail_type_sel]:checked').val() == '3') { // 往復
            $('.input-outbound').show(g_fifoVal);
            $('.input-inbound').show(g_fifoVal);
            $('.arrow-img').prop("src","/images/common/img_allow_8.png");
        } else if($('input[name=comiket_detail_type_sel]:checked').val() == '5'){ // バシ　追加
            $('.input-outbound').hide(g_fifoVal);
            $('.input-inbound').hide(g_fifoVal);
            $('.arrow-img').prop("src","/images/common/img_allow_9.png");
        } else { // 未選択
            $('.input-outbound').hide(g_fifoVal);
            $('.input-inbound').hide(g_fifoVal);
        }
    }

    function dispComiketDetailType() {
        var comiketDiv = $('input[name=comiket_div]:checked').val();
        var eventSel = $('select[name="event_sel"]').val();
//console.log("############### 101");
//console.log(comiketDiv);
//console.log(eventSel);
        if((eventSel && eventSel != "") && (comiketDiv && comiketDiv != "")) {
            $('.comiket_detail_type_sel-dd').show(g_fifoVal);

            var eventsubSel = $('select[name="eventsub_sel"]').val();
            if(comiketDiv == G_DEV_BUSINESS) { // 法人
//                $('input[name="comiket_customer_cd_sel"]').attr("checked", false);
//                var comiketCustomerCdSel = $('input[name="comiket_customer_cd_sel"]:checked').val();

                if(!eventsubSel || eventsubSel == "") {
                    return;
                }

                if(g_eventsubList[eventsubSel]['is_departure_date_range'] == true) { // 搬入申込み期間中の場合
                    $('label.comiket_detail_type_sel-label1').show(g_fifoVal); // 搬入 選択肢の表示

                } else { // 搬入申込み期間外の場合
                    $('label.comiket_detail_type_sel-label1').hide(g_fifoVal); // 搬入 選択肢の非表示
                    $('input#comiket_detail_type_sel1').attr("checked", false);
                }

                if(g_eventsubList[eventsubSel]['is_arrival_date_range'] == true) { // 搬出申込み期間中の場合
                    $('label.comiket_detail_type_sel-label2').show(g_fifoVal); // 搬出 選択肢の表示
                } else { // 搬出申込み期間外の場合
                    $('label.comiket_detail_type_sel-label2').hide(g_fifoVal); // 搬出 選択肢の非表示
                    $('input#comiket_detail_type_sel2').attr("checked", false); // 搬入と搬出 選択肢の非表示
                }

                if(g_eventsubList[eventsubSel]['is_departure_date_range'] == true && g_eventsubList[eventsubSel]['is_arrival_date_range'] == true) {
//                    if(comiketCustomerCdSel == '1') { // 顧客コード使用する
                        if( eventSel == '4') {
                            $('label.comiket_detail_type_sel-label3').hide(g_fifoVal); // 搬入と搬出 選択肢の表示
                        } else {
                            $('label.comiket_detail_type_sel-label3').show(g_fifoVal); // 搬入と搬出 選択肢の表示
                        }
//                    } else { // 顧客コード使用しない
//                        $('label.comiket_detail_type_sel-label3').hide(g_fifoVal); // 搬入と搬出 選択肢の非表示
//                        $('input#comiket_detail_type_sel3').attr("checked", false);
//                    }
                } else {
                    $('label.comiket_detail_type_sel-label3').hide(g_fifoVal); // 搬入と搬出 選択肢の非表示
                    $('input#comiket_detail_type_sel3').attr("checked", false);
                }

            } else { // 個人
//console.log("############### 30");
//console.log(eventsubSel);
                if(!eventsubSel || eventsubSel == "") {
//console.log("############### 31");
                    return;
                }
//console.log("############### 32");

                if(g_eventsubList[eventsubSel]['is_departure_date_range'] == true) { // 搬入申込み期間中の場合
                    $('label.comiket_detail_type_sel-label1').show(g_fifoVal); // 搬入 選択肢の表示

                } else { // 搬入申込み期間外の場合
                    $('label.comiket_detail_type_sel-label1').hide(g_fifoVal); // 搬入 選択肢の非表示
                    $('input#comiket_detail_type_sel1').attr("checked", false);
                }

                if(g_eventsubList[eventsubSel]['is_arrival_date_range'] == true) { // 搬出申込み期間中の場合
                    $('label.comiket_detail_type_sel-label2').show(g_fifoVal); // 搬出 選択肢の表示
                } else { // 搬出申込み期間外の場合
                    $('label.comiket_detail_type_sel-label2').hide(g_fifoVal); // 搬出 選択肢の非表示
                    $('input#comiket_detail_type_sel2').attr("checked", false); // 搬入と搬出 選択肢の非表示
                }

                $('label.comiket_detail_type_sel-label3').hide(g_fifoVal); // 搬入と搬出 選択肢の非表示
                $('input#comiket_detail_type_sel3').attr("checked", false);
                selectComiketDetailType(); // 搬入、搬出の入力エリアを再設定(未選択になる)
            }
        } else {
            $('.comiket_detail_type_sel-dd').hide(g_fifoVal);
            $('input[name="comiket_detail_type_sel"]').attr("checked", false);
        }

        // イベントの選択がコミケなら館名プルダウンリストを非表示にする
        // TODO dbで表示フラグを持っているため、ここでは制御しない（1 != 1）
        if (eventSel == '2' && 1 != 1) {
            $('[name=building_name_sel]').val('');
            $('[name=building_name_sel]').hide(g_fifoVal);
            //GiapLN implement task SMT6-225 2022.07.25
            $('[name=comiket_booth_num]').attr('placeholder', '例）12(数値2桁)');
        } else {
            $('[name=building_name_sel]').show(g_fifoVal);
            //GiapLN implement task SMT6-225 2022.07.25
            $('[name=comiket_booth_num]').attr('placeholder', '例）12');
        }
    }

//    getEventsubData();
    var comiketDetailTypeSel = $('input[name="comiket_detail_type_sel"]:checked').val();
    if(comiketDetailTypeSel && comiketDetailTypeSel != "") {
//console.log("#### 501-1");
        selectComiketDetailType();
        dispComiketDetailType();
    } else {
//console.log("#### 501-2");
        // 往復選択の表示制御
        dispComiketDetailType();

        // 初期は表示しない
        $('.input-outbound').hide(g_fifoVal);
        $('.input-inbound').hide(g_fifoVal);
    }
//    dispComiketDetailType();
    /**
     *
     * @returns {undefined}
     */
    $('input[name="comiket_detail_type_sel"]').on('change', function() {
        selectComiketDetailType();
        selectComiketDetailOutboundService();
        selectComiketDetailInboundService();

//        dispComiketDetailInboundDeliveryDateTime();
        getBoxData();

//        dispComiketDetailOutboundDeliveryTimeByDivAndType();
//        dispComiketDetailOutboundCollectTimeByDivAndType();
//        dispComiketDetailInboundDeliveryTimeByDivAndType();

        dispPaymentMethod();

        // 注意文言の表示・非表示
        dispAttentionMessage();

        // 搬入-お預かり・お届け日表示制御
        dispOutboundColAndDelyDate();

        // 搬出-お預かり・お届け日表示制御
        dispInboundColAndDelyDate();

        // 搬出-時間帯指定不可地域に元ずく制御
        dispComiketDetailInboundDeliveryTime();

        // 搬入-時間帯指定不可地域に元ずく制御
        dispComiketDetailOutboundDeliveryTime();

        // 日付・時間帯、表示/非表示
        dispOutboundColAndDlyDateTimeByDb();
        dispInboundColAndDlyDateTimeByDb();

        // 時間帯初期設定
        // comiket_detail_type_sel
        if($('input[name="comiket_detail_type_sel"]:checked').val() == "1" ) { // 搬入
            $('select[name=comiket_detail_outbound_delivery_time_sel]').val("00");
        }

        if($('input[name="comiket_detail_type_sel"]:checked').val() == "2" ) { // 搬出
            $('select[name=comiket_detail_inbound_delivery_time_sel]').val("00,指定なし");
        }

        if (!$('[name=comiket_detail_outbound_pref_cd_sel]').val()) {
            return false;
        }

        // お預かり可能日付範囲を取得
        getOutBoundCollectCal();

    });

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 搬入-サービス選択 部分制御
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    function dispOutboundServiceItem() {
        var comiketDiv = $('input[name=comiket_div]:checked').val();
        var eventsubSel = $('select[name="eventsub_sel"]').val();
//        var serviceSel = $('input[name="comiket_detail_outbound_service_sel"]:checked').val();
console.log("###### 888");
console.log(comiketDiv);
console.log(eventsubSel);
console.log(g_eventsubList[eventsubSel]);

        var isBox = false;
        var isCargo = false;
        var isCharter = false;
        var dispCount = 0;

        if(comiketDiv == G_DEV_INDIVIDUAL) {
console.log("###### 888-1");
            // 個人・宅配・往路
            if(g_eventsubList[eventsubSel]['kojin_box_col_flg'] == '1') {
console.log("###### 888-2");
                $('label[for="comiket_detail_outbound_service_sel1"]').show(g_fifoVal);
                isBox = true;
                dispCount++;
            } else {
console.log("###### 888-3");
                $('label[for="comiket_detail_outbound_service_sel1"]').hide(g_fifoVal);
                isBox = false;
            }

            // 個人・カーゴ・往路
            if(g_eventsubList[eventsubSel]['kojin_cag_col_flg'] == '1') {
                $('label[for="comiket_detail_outbound_service_sel2"]').show(g_fifoVal);
                isCargo = true;
                dispCount++;
            } else {
                $('label[for="comiket_detail_outbound_service_sel2"]').hide(g_fifoVal);
                isCargo = false;
            }

            $('label[for="comiket_detail_outbound_service_sel3"]').hide(g_fifoVal);
            isCharter = false;
        } else if(comiketDiv == G_DEV_BUSINESS) {

            // 法人・宅配・往路
            if(g_eventsubList[eventsubSel]['hojin_box_col_flg'] == '1') {
                $('label[for="comiket_detail_outbound_service_sel1"]').show(g_fifoVal);
                isBox = true;
                dispCount++;
            } else {
                $('label[for="comiket_detail_outbound_service_sel1"]').hide(g_fifoVal);
                isBox = false;
            }

            // 法人・カーゴ・往路
            if(g_eventsubList[eventsubSel]['hojin_cag_col_flg'] == '1') {
                $('label[for="comiket_detail_outbound_service_sel2"]').show(g_fifoVal);
                isCargo = true;
                dispCount++;
            } else {
                $('label[for="comiket_detail_outbound_service_sel2"]').hide(g_fifoVal);
                isCargo = false;
            }

            // 法人・貸切・往路
            if(g_eventsubList[eventsubSel]['hojin_kas_col_flg'] == '1') {
                $('label[for="comiket_detail_outbound_service_sel3"]').show(g_fifoVal);
                isCharter = true;
                dispCount++;
            } else {
                $('label[for="comiket_detail_outbound_service_sel3"]').hide(g_fifoVal);
                isCharter = false;
            }
        } else {
            isBox = isCargo = isCharter = false;
            $('label[for="comiket_detail_outbound_service_sel1"]').hide(g_fifoVal);
            $('label[for="comiket_detail_outbound_service_sel2"]').hide(g_fifoVal);
            $('label[for="comiket_detail_outbound_service_sel3"]').hide(g_fifoVal);
        }

        if(2 <= dispCount) {
            $("dl.comiket_detail_outbound_service_sel").show(); // サービス項目毎非表示
        }

//        if(isBox) {
//            $('input#comiket_detail_outbound_service_sel1').attr("checked", 'checked'); // 宅配便選択
//            $('input#comiket_detail_outbound_service_sel1').prop("checked", 'checked'); // 宅配便選択
//            $('input#comiket_detail_outbound_service_sel2').attr("checked", ''); // カーゴ選択
//            $('input#comiket_detail_outbound_service_sel2').prop("checked", ''); // カーゴ選択
//            $('input#comiket_detail_outbound_service_sel3').attr("checked", ''); // チャーター選択
//            $('input#comiket_detail_outbound_service_sel3').prop("checked", ''); // チャーター選択
//        } else if(isCargo) {
//            $('input#comiket_detail_outbound_service_sel1').attr("checked", ''); // 宅配便選択
//            $('input#comiket_detail_outbound_service_sel1').prop("checked", ''); // 宅配便選択
//            $('input#comiket_detail_outbound_service_sel2').attr("checked", 'checked'); // カーゴ選択
//            $('input#comiket_detail_outbound_service_sel2').prop("checked", 'checked'); // カーゴ選択
//            $('input#comiket_detail_outbound_service_sel3').attr("checked", ''); // チャーター選択
//            $('input#comiket_detail_outbound_service_sel3').prop("checked", ''); // チャーター選択
//        } else if(isCharter) {
//            $('input#comiket_detail_outbound_service_sel1').attr("checked", ''); // 宅配便選択
//            $('input#comiket_detail_outbound_service_sel1').prop("checked", ''); // 宅配便選択
//            $('input#comiket_detail_outbound_service_sel2').attr("checked", ''); // カーゴ選択
//            $('input#comiket_detail_outbound_service_sel2').prop("checked", ''); // カーゴ選択
//            $('input#comiket_detail_outbound_service_sel3').attr("checked", 'checked'); // チャーター選択
//            $('input#comiket_detail_outbound_service_sel3').prop("checked", 'checked'); // チャーター選択
//        }

        if(isBox && !isCargo && !isCharter) {
            $('input#comiket_detail_outbound_service_sel1').attr("checked", 'checked'); // 宅配便選択
            $('input#comiket_detail_outbound_service_sel1').prop("checked", 'checked'); // 宅配便選択
            $('input#comiket_detail_outbound_service_sel2').attr("checked", ''); // カーゴ選択
            $('input#comiket_detail_outbound_service_sel2').prop("checked", ''); // カーゴ選択
            $('input#comiket_detail_outbound_service_sel3').attr("checked", ''); // チャーター選択
            $('input#comiket_detail_outbound_service_sel3').prop("checked", ''); // チャーター選択
            $("dl.comiket_detail_outbound_service_sel").hide(); // サービス項目毎非表示
            checkOutboundService = '1';
        }

        if(!isBox && isCargo && !isCharter) {
            $('input#comiket_detail_outbound_service_sel1').attr("checked", ''); // 宅配便選択
            $('input#comiket_detail_outbound_service_sel1').prop("checked", ''); // 宅配便選択
            $('input#comiket_detail_outbound_service_sel2').attr("checked", 'checked'); // カーゴ選択
            $('input#comiket_detail_outbound_service_sel2').prop("checked", 'checked'); // カーゴ選択
            $('input#comiket_detail_outbound_service_sel3').attr("checked", ''); // チャーター選択
            $('input#comiket_detail_outbound_service_sel3').prop("checked", ''); // チャーター選択
            $("dl.comiket_detail_outbound_service_sel").hide(); // サービス項目毎非表示
            checkOutboundService = '2';
        }

        if(!isBox && !isCargo && isCharter) {
            $('input#comiket_detail_outbound_service_sel1').attr("checked", ''); // 宅配便選択
            $('input#comiket_detail_outbound_service_sel1').prop("checked", ''); // 宅配便選択
            $('input#comiket_detail_outbound_service_sel2').attr("checked", ''); // カーゴ選択
            $('input#comiket_detail_outbound_service_sel2').prop("checked", ''); // カーゴ選択
            $('input#comiket_detail_outbound_service_sel3').attr("checked", 'checked'); // チャーター選択
            $('input#comiket_detail_outbound_service_sel3').prop("checked", 'checked'); // チャーター選択
            $("dl.comiket_detail_outbound_service_sel").hide(); // サービス項目毎非表示
            checkOutboundService = '3';
        }

        if(!isBox && !isCargo && !isCharter) {
            $("dl.comiket_detail_outbound_service_sel").hide(); // サービス項目毎非表示
        }
    }

    function selectComiketDetailOutboundServiceCompany() {
        dispOutboundServiceItem();

//        var eventSel = $('select[name="event_sel"]').val();
//        var radioVal = $('input[name="comiket_detail_outbound_service_sel"]:checked').val();
//        $("dl.comiket_detail_outbound_service_sel").show();
//        if(eventSel == '4') { // Go out camp
//            $('#comiket_detail_outbound_service_sel3').hide(g_fifoVal);
//            $('label[for="comiket_detail_outbound_service_sel3"]').hide();
//            $('#comiket_detail_outbound_service_sel2').hide(g_fifoVal);
//            $('label[for="comiket_detail_outbound_service_sel2"]').hide();
//            $("dl.comiket_detail_outbound_service_sel").hide();
//            $('input#comiket_detail_outbound_service_sel1').attr("checked", 'checked'); // 宅配便選択
//            $('input#comiket_detail_outbound_service_sel1').prop("checked", 'checked'); // 宅配便選択
////            $('input#comiket_detail_outbound_service_sel1').trigger('change');
////             $('input[name="comiket_detail_outbound_service_sel"]').trigger('change');
////            comiket_detail_outbound_service_sel1
//        } else {
//            $('#comiket_detail_outbound_service_sel3').show(g_fifoVal);
//            $('label[for="comiket_detail_outbound_service_sel3"]').show();
//        }
        $(".service-outbound-item").each(function() {
            var serviceId = $(this).attr("service-id");
            var radioVal = $('input[name="comiket_detail_outbound_service_sel"]:checked').val();
            if (!radioVal) {
                radioVal = $('input[name="comiket_detail_outbound_service_sel"]').val();
            }

            if(serviceId == radioVal) {
                $(this).show(g_fifoVal);
            } else {
                $(this).hide(g_fifoVal);
            }
        });
//console.log("######## 901");
        $('div.comiket-box-outbound-num,div.comiket-charter-outbound-num').each(function() {

            var comiketDiv = $('input[name=comiket_div]:checked').val();

            if($(this).attr("div-id")) {
                if(comiketDiv == $(this).attr("div-id")) {
                    $(this).show(g_fifoVal);
                } else {
                    $(this).hide(g_fifoVal);
                }
            }
        });

        $('div.comiket-cargo-outbound-num').each(function() {
            var comiketDiv = $('input[name=comiket_div]:checked').val();
            var eventSel = $('select[name=event_sel]').val();

            if(comiketDiv == G_DEV_INDIVIDUAL) { // 個人
                if(eventSel == '2' || eventSel == '4') { // コミケ
                    $(this).show(g_fifoVal);
                } else {
                    $(this).hide(g_fifoVal);
                }
            } else { // 法人
                $(this).show(g_fifoVal);
            }

//            if($(this).attr("div-id")) {
//                if(comiketDiv == $(this).attr("div-id")) {
//                    $(this).show(g_fifoVal);
//                } else {
//                    $(this).hide(g_fifoVal);
//                }
//            }
        });
    }

    function selectComiketDetailOutboundServiceIndividual() {

        dispOutboundServiceItem();
//        var outServiceSel = $('input[name="comiket_detail_outbound_service_sel"]:checked').val();
        //comiket_detail_outbound_service_sel
//        var comiketDiv = $('input[name="comiket_div"]:checked').val();
//        var eventSel = $('select[name="event_sel"]').val();
//        if(comiketDiv == G_DEV_INDIVIDUAL) { // 個人
//            if(eventSel == '2' || eventSel == '4') { // コミケ の場合
//                $("dl.comiket_detail_outbound_service_sel").show(g_fifoVal);
//                $('#comiket_detail_outbound_service_sel3').hide(g_fifoVal);
//                $('label[for="comiket_detail_outbound_service_sel3"]').hide();
//                $('#comiket_detail_outbound_service_sel2').show(g_fifoVal);
//                $('label[for="comiket_detail_outbound_service_sel2"]').show();
//            } else {
//                $("dl.comiket_detail_outbound_service_sel").hide();
//                $('input#comiket_detail_outbound_service_sel1').attr("checked", 'checked'); // 宅配便選択
//                $('input#comiket_detail_outbound_service_sel1').prop("checked", 'checked'); // 宅配便選択
//            }
//        } else {
//            $("dl.comiket_detail_outbound_service_sel").show();
//
//            $('input#comiket_detail_outbound_service_sel1').attr("checked", ''); // 宅配便選択
//            $('input#comiket_detail_outbound_service_sel1').prop("checked", ''); // 宅配便選択
//            if(eventSel == '4') {
//                $('#comiket_detail_outbound_service_sel3').hide(g_fifoVal);
//                $('label[for="comiket_detail_outbound_service_sel3"]').hide();
//            }
//        }
        $('.service-outbound-item').each(function() { // 宅配数量 / カーゴ数量 / 台数貸切
            var serviceId = $(this).attr("service-id");
//            var radioVal = $('input[name="comiket_detail_outbound_service_sel"]:checked').val(); // サービス選択
            var radioVal = checkOutboundService;

            if (!radioVal) {
                radioVal = $('input[name="comiket_detail_outbound_service_sel"]:checked').val(); // サービス選択
            }

//console.log("######## 902");
//console.log(radioVal);
//console.log(serviceId);

            if(serviceId == radioVal) {
                $(this).show(g_fifoVal);
            } else {
                $(this).hide(g_fifoVal);
            }
        });
        $('div.comiket-box-outbound-num,div.comiket-charter-outbound-num').each(function() { // [宅配数量 / カーゴ数量 / 台数貸切] 法人 、 個人
            var comiketDiv = $('input[name=comiket_div]:checked').val();

//console.log("######## 903");
//console.log(comiketDiv);
//console.log($(this).attr("div-id"));
            if($(this).attr("div-id")) {
                if(comiketDiv == $(this).attr("div-id")) { // 法人 or 個人
                    $(this).show(g_fifoVal);
                } else {
                    $(this).hide(g_fifoVal);
                }
            }
        });

        $('div.comiket-cargo-outbound-num').each(function(){
            var comiketDiv = $('input[name=comiket_div]:checked').val();
            var eventSel = $('select[name=event_sel]').val();
            if(comiketDiv == G_DEV_INDIVIDUAL) { // 個人
                if(eventSel == '2' || eventSel == '4') { // コミケ
                    $(this).show(g_fifoVal);
                } else {
                    $(this).hide(g_fifoVal);
                }
            } else { // 法人
                $(this).show(g_fifoVal);
            }
        });
    }

//    function selectComiketDetailOutboundServiceCompanyUseCustomerCd() {
//        $("dl.comiket_detail_outbound_service_sel").hide();
//        $('input#comiket_detail_outbound_service_sel1').attr("checked", 'checked'); // 宅配便選択
//        $('input#comiket_detail_outbound_service_sel1').prop("checked", 'checked'); // 宅配便選択
//        $('.service-outbound-item').each(function() {
//            var radioVal = $('input[name="comiket_detail_outbound_service_sel"]:checked').val();
//            var serviceId = $(this).attr("service-id");
//            if(serviceId == radioVal) {
//                $(this).show(g_fifoVal);
//            } else {
//                $(this).hide(g_fifoVal);
//            }
//        });
//        $('div.comiket-box-outbound-num,div.comiket-cargo-outbound-num,div.comiket-charter-outbound-num').each(function() {
//            var comiketDiv = $('input[name=comiket_div]:checked').val();
////            var comiketCustomerCdSel = $('input[name="comiket_customer_cd_sel"]:checked').val();
////            if(comiketCustomerCdSel == '1') { // 顧客コード使用する
//                if($(this).attr("div-id")) {
//                    if(comiketDiv == $(this).attr("div-id")) {
//                        $(this).show(g_fifoVal);
//                    } else {
//                        $(this).hide(g_fifoVal);
//                    }
//                }
////            } else { // 顧客コード使用しない
////                if("2" == $(this).attr("div-id")) { // 個人用のパーツを表示
////                    $(this).show(g_fifoVal);
////                } else {
////                    $(this).hide(g_fifoVal);
////                }
////            }
//        });
//    }

    /**
     *
     * @returns {undefined}
     */
    function selectComiketDetailOutboundService() {
//console.log("############################################# 20");
//console.log($('input[name="comiket_div"]:checked').val());
        if($('input[name="comiket_div"]:checked').val() == G_DEV_BUSINESS) { // 法人
//            var comiketCustomerCdSel = $('input[name="comiket_customer_cd_sel"]:checked').val();
//            if(comiketCustomerCdSel == '1') { // 顧客コード使用する
                selectComiketDetailOutboundServiceCompany();
//            } else {
//                selectComiketDetailOutboundServiceCompanyUseCustomerCd();
//            }
        } else if($('input[name="comiket_div"]:checked').val() == G_DEV_INDIVIDUAL) { // 個人
            selectComiketDetailOutboundServiceIndividual();
        }
    }

    selectComiketDetailOutboundService();
    /**
     *
     * @returns {undefined}
     */
    $('input[name="comiket_detail_outbound_service_sel"]').on('change', function() {
        selectComiketDetailOutboundService();
        dispComiketDetailOutboundDeliveryTime();

        // お預かり時間帯 表示制御 - サービスによる
//        dispComiketDetailOutboundDeliveryTimeByService();

        //**************************a
        // 日付・時間帯、表示/非表示
        dispOutboundColAndDlyDateTimeByDb();
//        dispInboundColAndDlyDateTimeByDb();
    });

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 搬出-サービス選択 部分制御
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    function dispInboundServiceItem() {
        var comiketDiv = $('input[name=comiket_div]:checked').val();
        var eventsubSel = $('select[name="eventsub_sel"]').val();
//        var serviceSel = $('input[name="comiket_detail_outbound_service_sel"]:checked').val();
//console.log("###### 888");
//console.log(comiketDiv);
//console.log(eventsubSel);
//console.log(g_eventsubList[eventsubSel]);

        var isBox = false;
        var isCargo = false;
        var isCharter = false;
        var dispCount = 0;

        if(comiketDiv == G_DEV_INDIVIDUAL) {
//console.log("###### 888-1");
            // 個人・宅配・往路
            if(g_eventsubList[eventsubSel]['kojin_box_dlv_flg'] == '1') {
//console.log("###### 888-2");
                $('label[for="comiket_detail_inbound_service_sel1"]').show(g_fifoVal);
                isBox = true;
                dispCount++;
            } else {
//console.log("###### 888-3");
                $('label[for="comiket_detail_inbound_service_sel1"]').hide(g_fifoVal);
                isBox = false;
            }

            // 個人・カーゴ・往路
            if(g_eventsubList[eventsubSel]['kojin_cag_dlv_flg'] == '1') {
                $('label[for="comiket_detail_inbound_service_sel2"]').show(g_fifoVal);
                isCargo = true;
                dispCount++;
            } else {
                $('label[for="comiket_detail_inbound_service_sel2"]').hide(g_fifoVal);
                isCargo = false;
            }

            $('label[for="comiket_detail_inbound_service_sel3"]').hide(g_fifoVal);
            isCharter = false;
        } else if(comiketDiv == G_DEV_BUSINESS) {

            // 法人・宅配・往路
            if(g_eventsubList[eventsubSel]['hojin_box_dlv_flg'] == '1') {
                $('label[for="comiket_detail_inbound_service_sel1"]').show(g_fifoVal);
                isBox = true;
                dispCount++;
            } else {
                $('label[for="comiket_detail_inbound_service_sel1"]').hide(g_fifoVal);
                isBox = false;
            }

            // 法人・カーゴ・往路
            if(g_eventsubList[eventsubSel]['hojin_cag_dlv_flg'] == '1') {
                $('label[for="comiket_detail_inbound_service_sel2"]').show(g_fifoVal);
                isCargo = true;
                dispCount++;
            } else {
                $('label[for="comiket_detail_inbound_service_sel2"]').hide(g_fifoVal);
                isCargo = false;
            }

            // 法人・貸切・往路
            if(g_eventsubList[eventsubSel]['hojin_kas_dlv_flg'] == '1') {
                $('label[for="comiket_detail_inbound_service_sel3"]').show(g_fifoVal);
                isCharter = true;
                dispCount++;
            } else {
                $('label[for="comiket_detail_inbound_service_sel3"]').hide(g_fifoVal);
                isCharter = false;
            }
        } else {
            isBox = isCargo = isCharter = false;
            $('label[for="comiket_detail_inbound_service_sel1"]').hide(g_fifoVal);
            $('label[for="comiket_detail_inbound_service_sel2"]').hide(g_fifoVal);
            $('label[for="comiket_detail_inbound_service_sel3"]').hide(g_fifoVal);
        }

        if(2 <= dispCount) {
            $("dl.comiket_detail_inbound_service_sel").show(); // サービス項目毎非表示
        }

        if(isBox && !isCargo && !isCharter) {
            $('input#comiket_detail_inbound_service_sel1').attr("checked", 'checked'); // 宅配便選択
            $('input#comiket_detail_inbound_service_sel1').prop("checked", 'checked'); // 宅配便選択
            $("dl.comiket_detail_inbound_service_sel").hide(); // サービス項目毎非表示
            checkInboundService = '1';
        }

        if(!isBox && isCargo && !isCharter) {
            $('input#comiket_detail_inbound_service_sel2').attr("checked", 'checked'); // カーゴ選択
            $('input#comiket_detail_inbound_service_sel2').prop("checked", 'checked'); // カーゴ選択
            $("dl.comiket_detail_inbound_service_sel").hide(); // サービス項目毎非表示
            checkInboundService = '2';
        }

        if(!isBox && !isCargo && isCharter) {
            $('input#comiket_detail_inbound_service_sel3').attr("checked", 'checked'); // チャーター選択
            $('input#comiket_detail_inbound_service_sel3').prop("checked", 'checked'); // チャーター選択
            $("dl.comiket_detail_inbound_service_sel").hide(); // サービス項目毎非表示
            checkInboundService = '3';
        }

        if(!isBox && !isCargo && !isCharter) {
            $("dl.comiket_detail_inbound_service_sel").hide(); // サービス項目毎非表示
        }
    }




    function selectComiketDetailInboundServiceCompany() {
        dispInboundServiceItem();
//        var eventSel = $('select[name="event_sel"]').val();
//        var radioVal = $('input[name="comiket_detail_inbound_service_sel"]:checked').val();
//        $("dl.comiket_detail_inbound_service_sel").show();
//        if(eventSel == '4') { // go out camp
//            $('#comiket_detail_inbound_service_sel3').hide(g_fifoVal)
//            $('label[for="comiket_detail_inbound_service_sel3"]').hide();
//            $('#comiket_detail_inbound_service_sel2').hide(g_fifoVal)
//            $('label[for="comiket_detail_inbound_service_sel2"]').hide();
//            $("dl.comiket_detail_inbound_service_sel").hide();
//            $('input#comiket_detail_inbound_service_sel1').attr("checked", 'checked'); // 宅配便選択
//            $('input#comiket_detail_inbound_service_sel1').prop("checked", 'checked'); // 宅配便選択
//        } else {
//            $('#comiket_detail_inbound_service_sel3').show(g_fifoVal)
//            $('label[for="comiket_detail_inbound_service_sel3"]').show();
//        }


        $(".service-inbound-item").each(function() {
            var serviceId = $(this).attr("service-id");
            var radioVal = $('input[name="comiket_detail_inbound_service_sel"]:checked').val();
            if(serviceId == radioVal) {
                $(this).show(g_fifoVal);
            } else {
                $(this).hide(g_fifoVal);
            }
        });

        var comiketDiv = $('input[name=comiket_div]:checked').val();
        $('div.comiket-box-inbound-num,div.comiket-charter-inbound-num').each(function() {
            if($(this).attr("div-id")) {
                if(comiketDiv == $(this).attr("div-id")) {
                    $(this).show(g_fifoVal);
                } else {
                    $(this).hide(g_fifoVal);
                }
            }
        });

        $('div.comiket-cargo-inbound-num').each(function(){
            var comiketDiv = $('input[name=comiket_div]:checked').val();
            var eventSel = $('select[name=event_sel]').val();
            if(comiketDiv == G_DEV_INDIVIDUAL) { // 個人
                if(eventSel == '2' || eventSel == '4') { // コミケ / go out camp
                    $(this).show(g_fifoVal);
                } else {
                    $(this).hide(g_fifoVal);
                }
            } else { // 法人
                $(this).show(g_fifoVal);
            }
        });
    }

    function selectComiketDetailInboundServiceIndividual() {

        dispInboundServiceItem();

//        var comiketDiv = $('input[name="comiket_div"]:checked').val();
//        var eventSel = $('select[name="event_sel"]').val();
//
//        if(comiketDiv == G_DEV_INDIVIDUAL) { // 個人
//            if(eventSel == '2' || eventSel == '4') { // コミケ / GoOutCamp の場合
//                $("dl.comiket_detail_inbound_service_sel").show(g_fifoVal);
//                $('#comiket_detail_inbound_service_sel3').hide(g_fifoVal);
//                $('label[for="comiket_detail_inbound_service_sel3"]').hide();
//                $('#comiket_detail_inbound_service_sel2').show(g_fifoVal);
//                $('label[for="comiket_detail_inbound_service_sel2"]').show();
//            } else {
//                $("dl.comiket_detail_inbound_service_sel").hide();
//                $('input#comiket_detail_inbound_service_sel1').attr("checked", 'checked'); // 宅配便選択
//                $('input#comiket_detail_inbound_service_sel1').prop("checked", 'checked'); // 宅配便選択
//            }
//        } else {
//            $("dl.comiket_detail_inbound_service_sel").hide();
//            $('input#comiket_detail_inbound_service_sel1').attr("checked", 'checked'); // 宅配便選択
//            $('input#comiket_detail_inbound_service_sel1').prop("checked", 'checked'); // 宅配便選択
//            if(eventSel == '4') { // GoOutCamp の場合
//                $('#comiket_detail_inbound_service_sel3').hide(g_fifoVal);
//                $('label[for="comiket_detail_inbound_service_sel3"]').hide();
//            }
//        }

        $('.service-inbound-item').each(function() {
//            var radioVal = $('input[name="comiket_detail_inbound_service_sel"]:checked').val();
            var radioVal = checkInboundService;

            if (!radioVal) {
                radioVal = $('input[name="comiket_detail_inbound_service_sel"]:checked').val();
            }
            var serviceId = $(this).attr("service-id");
            if(serviceId == radioVal) {
                $(this).show(g_fifoVal);
            } else {
                $(this).hide(g_fifoVal);
            }
        });
        $('div.comiket-box-inbound-num,div.comiket-charter-inbound-num').each(function() {
            var comiketDiv = $('input[name=comiket_div]:checked').val();
            if($(this).attr("div-id")) {
                if(comiketDiv == $(this).attr("div-id")) {
                    $(this).show(g_fifoVal);
                } else {
                    $(this).hide(g_fifoVal);
                }
            }
        });

        $('div.comiket-cargo-inbound-num').each(function(){
            var comiketDiv = $('input[name=comiket_div]:checked').val();
            var eventSel = $('select[name=event_sel]').val();
            if(comiketDiv == G_DEV_INDIVIDUAL) { // 個人
                if(eventSel == '2' || eventSel == '4') { // コミケ
                    $(this).show(g_fifoVal);
                } else {
                    $(this).hide(g_fifoVal);
                }
            } else { // 法人
                $(this).show(g_fifoVal);
            }
        });
    }

//    function selectComiketDetailInboundServiceCompanyUseCustomerCd() {
//        $("dl.comiket_detail_inbound_service_sel").hide();
//        $('input#comiket_detail_inbound_service_sel1').attr("checked", 'checked'); // 宅配便選択
//        $('input#comiket_detail_inbound_service_sel1').prop("checked", 'checked'); // 宅配便選択
//        $('.service-inbound-item').each(function() {
//            var radioVal = $('input[name="comiket_detail_inbound_service_sel"]:checked').val();
//            var serviceId = $(this).attr("service-id");
//            if(serviceId == radioVal) {
//                $(this).show(g_fifoVal);
//            } else {
//                $(this).hide(g_fifoVal);
//            }
//        });
//        $('div.comiket-box-inbound-num,div.comiket-charter-inbound-num').each(function() {
//            var comiketDiv = $('input[name=comiket_div]:checked').val();
////            var comiketCustomerCdSel = $('input[name="comiket_customer_cd_sel"]:checked').val();
////            if(comiketCustomerCdSel == '1') { // 顧客コード使用する
//                if($(this).attr("div-id")) {
//                    if(comiketDiv == $(this).attr("div-id")) {
//                        $(this).show(g_fifoVal);
//                    } else {
//                        $(this).hide(g_fifoVal);
//                    }
//                }
////            } else { // 顧客コード使用しない
////                if("2" == $(this).attr("div-id")) { // 個人
////                    $(this).show(g_fifoVal);
////                } else {
////                    $(this).hide(g_fifoVal);
////                }
////            }
//        });
//
//        $('div.comiket-cargo-inbound-num').each(function(){
//            var comiketDiv = $('input[name=comiket_div]:checked').val();
//            var eventSel = $('select[name=event_sel]').val();
//            if(comiketDiv == G_DEV_INDIVIDUAL) { // 個人
//                if(eventSel == '2') { // コミケ
//                    $(this).show(g_fifoVal);
//                } else {
//                    $(this).hide(g_fifoVal);
//                }
//            } else { // 法人
//                $(this).show(g_fifoVal);
//            }
//        });
//    }

    /**
     *
     * @returns {undefined}
     */
    function selectComiketDetailInboundService() {
        if($('input[name="comiket_div"]:checked').val() == G_DEV_BUSINESS) { // 法人

//            var comiketCustomerCdSel = $('input[name="comiket_customer_cd_sel"]:checked').val();
//            if(comiketCustomerCdSel == '1') { // 顧客コード使用する
                selectComiketDetailInboundServiceCompany();
//            } else {
//                selectComiketDetailInboundServiceCompanyUseCustomerCd();
//            }
        } else if($('input[name="comiket_div"]:checked').val() == G_DEV_INDIVIDUAL) { // 個人
            selectComiketDetailInboundServiceIndividual();
        }
    }

    selectComiketDetailInboundService();
    /**
     *
     * @returns {undefined}
     */
    $('input[name="comiket_detail_inbound_service_sel"]').on('change', function() {
        selectComiketDetailInboundService();

        // 搬出-お届け日時・時間帯表示制御
//        dispComiketDetailInboundDeliveryDateTime();

        // 搬出-時間帯指定不可地域に元ずく制御
        dispComiketDetailInboundDeliveryTime();

        // 搬出-お届け日時・時間帯表示制御
        dispInboundColAndDlyDateTimeByDb();
    });

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 宅配数量
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    function getBoxData() {
        sgwns.api('/common/php/SearchBox.php', getFormData(), (function(data) {
            $('.comiket-box-outbound-num,.comiket-box-inbound-num').children().remove();
            var inputTypeEmail = $('input[name="input_type_email"]').val();
            var inputTypeNumber = $('input[name="input_type_number"]').val();
            if($('input[name="comiket_detail_type_sel"]:checked').val() == "1" ) { // 搬入
                
                var htmlCode = "";
                if($('input[name="comiket_div"]:checked').val() ==  G_DEV_INDIVIDUAL) {
                    htmlCode += "<table><tr><td>";
                }

                htmlCode += "<table>";
                var boxName = "";
                for(var i = 0; i < data.outbound.length; i++) {
                    boxName = data.outbound[i].name_display;
                    if(data.outbound[i].name_display == null || data.outbound[i].name_display == ""){
                        boxName =  data.outbound[i].name;
                    }
                    htmlCode += "<tr class = 'box_"+data.outbound[i].size_display+"'><td class='comiket_box_item_name'>" + boxName + "&nbsp;</td><td class='comiket_box_item_value'><input autocapitalize='off' class='number-only comiket_box_item_value_input' maxlength='2' inputmode='numeric' name='comiket_box_outbound_num_ary[" + data.outbound[i].id  + "]' data-pattern='^\d+$' placeholder='例）1' type='" + inputTypeNumber + "' value='' />個</td></tr>";
                }
                htmlCode += "</table>";

                htmlCode += "</td><td style='vertical-align: middle;text-align: right;'>";
                if($('input[name="comiket_div"]:checked').val() ==  G_DEV_INDIVIDUAL) {
                    htmlCode += "<img class='dispSeigyoPC' src='/evp/images/about_boxsize.png' width='100%'/>";
                } else {
//                    htmlCode += "<img src='/evp/images/about_boxsize.png' width='100%' style='visibility:hidden;'/>";
                }
                htmlCode += "</td></tr></table>";

                if($('input[name="comiket_div"]:checked').val() ==  G_DEV_INDIVIDUAL && $('.dispSeigyoSP').length === 0) {
                    htmlCode += "<div class='dispSeigyoSP'><img src='/evp/images/about_boxsize.png' width='250px' style='margin-top: 1em;' /></div>";
                }

                $('.comiket-box-outbound-num').append(htmlCode);

            } else if($('input[name="comiket_detail_type_sel"]:checked').val() == "2") { // 搬出
                var htmlCode = "";
                if($('input[name="comiket_div"]:checked').val() ==  G_DEV_INDIVIDUAL) {
                    htmlCode += "<table><tr><td>";
                }

                htmlCode += "<table>";
                var boxName = "";
                for(var i = 0; i < data.inbound.length; i++) {
                    boxName = data.inbound[i].name_display;
                    if(data.inbound[i].name_display == null || data.inbound[i].name_display == ""){
                        boxName =  data.inbound[i].name;
                    }
                    htmlCode += "<tr class = 'box_"+data.inbound[i].size_display+"'><td class='comiket_box_item_name'>" + boxName + "&nbsp;</td><td class='comiket_box_item_value'><input autocapitalize='off' class='number-only comiket_box_item_value_input' maxlength='2' inputmode='numeric' name='comiket_box_inbound_num_ary[" + data.inbound[i].id  + "]' data-pattern='^\d+$' placeholder='例）1' type='" + inputTypeNumber + "' value='' />個</td></tr>";
                }
                htmlCode += "</table>";

                htmlCode += "</td><td class='dispSeigyoPC' style='vertical-align: middle;text-align: right;'>";
                if($('input[name="comiket_div"]:checked').val() ==  G_DEV_INDIVIDUAL) {
                    htmlCode += "<img src='/evp/images/about_boxsize.png' width='100%'/>";
                }
                htmlCode += "</td></tr></table>";

                if($('input[name="comiket_div"]:checked').val() ==  G_DEV_INDIVIDUAL && $('.dispSeigyoSP').length === 0) {
                    htmlCode += "<div class='dispSeigyoSP'><img src='/evp/images/about_boxsize.png' width='250px' style='margin-top: 1em;'/></div>";
                }

                $('.comiket-box-inbound-num').append(htmlCode);

            } else if($('input[name="comiket_detail_type_sel"]:checked').val() == "3") { // 搬入と搬出
                var htmlCode = "";
                if($('input[name="comiket_div"]:checked').val() ==  G_DEV_INDIVIDUAL) {
                    htmlCode += "<table><tr><td>";
                }

                htmlCode += "<table>";
                var boxName = "";
                for(var i = 0; i < data.outbound.length; i++) {
                     boxName = data.outbound[i].name_display;
                    if(data.outbound[i].name_display == null || data.outbound[i].name_display == ""){
                        boxName =  data.outbound[i].name;
                    }
                    htmlCode += "<tr class = 'box_"+data.outbound[i].size_display+"'><td class='comiket_box_item_name'>" + boxName + "&nbsp;</td><td class='comiket_box_item_value'><input autocapitalize='off' class='number-only comiket_box_item_value_input' maxlength='2' inputmode='numeric' name='comiket_box_outbound_num_ary[" + data.outbound[i].id  + "]' data-pattern='^\d+$' placeholder='例）1' type='" + inputTypeNumber + "' value='' />個</td></tr>";

                }
                htmlCode += "</table>";

                htmlCode += "</td><td style='vertical-align: middle;text-align: right;'>";
                if($('input[name="comiket_div"]:checked').val() ==  G_DEV_INDIVIDUAL) {
                    htmlCode += "<img src='/evp/images/about_boxsize.png' width='100%'/>";
                }
                htmlCode += "</td></tr></table>"

                $('.comiket-box-outbound-num').append(htmlCode);

                var htmlCode2 = "";
                if($('input[name="comiket_div"]:checked').val() ==  G_DEV_INDIVIDUAL) {
                    htmlCode2 += "<table><tr><td>";
                }

                htmlCode2 += "<table>";

                var boxName = "";
                for(var i = 0; i < data.inbound.length; i++) {
                    boxName = data.inbound[i].name_display;
                    if(data.inbound[i].name_display == null || data.inbound[i].name_display == ""){
                        boxName =  data.inbound[i].name;
                    }
                    htmlCode2 += "<tr class = 'box_"+data.inbound[i].size_display+"'><td class='comiket_box_item_name'>" + boxName + "&nbsp;</td><td class='comiket_box_item_value'><input autocapitalize='off' class='number-only comiket_box_item_value_input' maxlength='2' inputmode='numeric' name='comiket_box_inbound_num_ary[" + data.inbound[i].id  + "]' data-pattern='^\d+$' placeholder='例）1' type='" + inputTypeNumber + "' value='' />個</td></tr>";
                }
                htmlCode2 += "</table>";

                htmlCode2 += "</td><td style='vertical-align: middle;text-align: right;'>";
                if($('input[name="comiket_div"]:checked').val() ==  G_DEV_INDIVIDUAL) {
                    htmlCode2 += "<img src='/evp/images/about_boxsize.png' width='100%'/>";
                }
                htmlCode2 += "</td></tr></table>"

                $('.comiket-box-inbound-num').append(htmlCode2);
            }
        }));
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 顧客コード
//////////////////////////////////////////////////////////////////////////////////////////////////////////

    function selectComiketDevCompany() { // 法人

        $('dl.office-name').show(g_fifoVal);
        $('span.office_name-lbl').show(g_fifoVal);
        $('input[name="office_name"]').hide(g_fifoVal);

        $('dl.comiket-personal-name-seimei').hide(g_fifoVal);
        $('span.comiket_personal_name_sei-lbl').hide(g_fifoVal);
        $('input[name="comiket_personal_name_sei"]').hide(g_fifoVal);
        $('span.comiket_personal_name_mei-lbl').hide(g_fifoVal);
        $('input[name="comiket_personal_name_mei"]').hide(g_fifoVal);

        $('span.comiket_zip1-lbl').show(g_fifoVal);
        $('input[name="comiket_zip1"]').hide(g_fifoVal);
        if(($('input[name="comiket_zip1"]').val() && $('input[name="comiket_zip1"]').val() != "")
                || $('input[name="comiket_div"]:checked').val() ==  G_DEV_INDIVIDUAL) { // 個人
            $('span.comiket_zip1-str').show(g_fifoVal);
        } else {
            $('span.comiket_zip1-str').hide(g_fifoVal);
        }

        // 郵便番号マーク 表示/非表示制御
        dispZipMark();

        $('span.comiket_zip2-lbl').show(g_fifoVal);
        $('input[name="comiket_zip2"]').hide(g_fifoVal);
        $('input[name="adrs_search_btn"]').hide(g_fifoVal);
        $('span.forget-address-discription').hide(g_fifoVal);

        $('span.comiket_pref_nm-lbl').show(g_fifoVal);
        $('select[name="comiket_pref_cd_sel"]').hide(g_fifoVal);

        $('span.comiket_address-lbl').show(g_fifoVal);
        $('input[name="comiket_address"]').hide(g_fifoVal);

        $('span.comiket_building-lbl').show(g_fifoVal);
        $('input[name="comiket_building"]').hide(g_fifoVal);

        $('span.comiket_tel-lbl').show(g_fifoVal);
        $('input[name="comiket_tel"]').hide(g_fifoVal);

    }

    function selectComiketDevIndividual() { // 個人
        $('dl.office-name').hide(g_fifoVal);
        $('span.office_name-lbl').hide(g_fifoVal);
        $('input[name="office_name"]').hide(g_fifoVal);
//console.log("###################### 201");

        $('dl.comiket-personal-name-seimei').show(g_fifoVal);
        $('span.comiket_personal_name_sei-lbl').hide(g_fifoVal);
        $('input[name="comiket_personal_name_sei"]').show(g_fifoVal);
        $('span.comiket_personal_name_mei-lbl').hide(g_fifoVal);
        $('input[name="comiket_personal_name_mei"]').show(g_fifoVal);

        $('span.comiket_zip1-lbl').hide(g_fifoVal);
        $('input[name="comiket_zip1"]').show(g_fifoVal);
        $('span.comiket_zip1-str').show(g_fifoVal);
        // 郵便番号マーク 表示/非表示制御
        dispZipMark();

        $('span.comiket_zip2-lbl').hide(g_fifoVal);
        $('input[name="comiket_zip2"]').show(g_fifoVal);
        $('input[name="adrs_search_btn"]').show(g_fifoVal);
        $('span.forget-address-discription').show(g_fifoVal);

        $('span.comiket_pref_nm-lbl').hide(g_fifoVal);
        $('select[name="comiket_pref_cd_sel"]').show(g_fifoVal);

        $('span.comiket_address-lbl').hide(g_fifoVal);
        $('input[name="comiket_address"]').show(g_fifoVal);

        $('span.comiket_building-lbl').hide(g_fifoVal);
        $('input[name="comiket_building"]').show(g_fifoVal);

        $('span.comiket_tel-lbl').hide(g_fifoVal);
        $('input[name="comiket_tel"]').show(g_fifoVal);
    }

//    function selectComiketDevCompanyNotUseCustomerId() { // 法人 + 顧客コード使用しない
//        $('dl.office-name').show(g_fifoVal);
//        $('span.office_name-lbl').hide(g_fifoVal);
//        $('input[name="office_name"]').show(g_fifoVal);
//
//        $('dl.comiket-personal-name-seimei').hide(g_fifoVal);
//        $('span.personal_name_sei-lbl').hide(g_fifoVal);
//        $('input[name="personal_name_sei"]').hide(g_fifoVal);
//        $('span.personal_name_mei-lbl').hide(g_fifoVal);
//        $('input[name="personal_name_mei"]').hide(g_fifoVal);
//
////        $('dl.comiket-name').hide(g_fifoVal);
////        $('span.comiket_personal_name-lbl').hide(g_fifoVal);
////        $('input[name="comiket_personal_name"]').hide(g_fifoVal);
//////console.log("###################### 201");
////
////        $('dl.comiket-name-seimei').show(g_fifoVal);
////        $('span.comiket_personal_name_sei-lbl').hide(g_fifoVal);
////        $('input[name="comiket_personal_name_sei"]').show(g_fifoVal);
////        $('span.comiket_personal_name_mei-lbl').hide(g_fifoVal);
////        $('input[name="comiket_personal_name_mei"]').show(g_fifoVal);
//
//        $('span.comiket_zip1-lbl').hide(g_fifoVal);
//        $('input[name="comiket_zip1"]').show(g_fifoVal);
//        $('span.comiket_zip1-str').show(g_fifoVal);
//        // 郵便番号マーク 表示/非表示制御
//        dispZipMark();
//
//        $('span.comiket_zip2-lbl').hide(g_fifoVal);
//        $('input[name="comiket_zip2"]').show(g_fifoVal);
//        $('input[name="adrs_search_btn"]').show(g_fifoVal);
//        $('span.forget-address-discription').show(g_fifoVal);
//
//        $('span.comiket_pref_nm-lbl').hide(g_fifoVal);
//        $('select[name="comiket_pref_cd_sel"]').show(g_fifoVal);
//
//        $('span.comiket_address-lbl').hide(g_fifoVal);
//        $('input[name="comiket_address"]').show(g_fifoVal);
//
//        $('span.comiket_building-lbl').hide(g_fifoVal);
//        $('input[name="comiket_building"]').show(g_fifoVal);
//
//        $('span.comiket_tel-lbl').hide(g_fifoVal);
//        $('input[name="comiket_tel"]').show(g_fifoVal);
//    }

    function selectComiketDevNotSelected() { // 未選択


        $('dl.office-name').val("");
        $('span.office_name-lbl').html("");
        $('input[name="office_name"]').val("");
//console.log("###################### 201");

        $('dl.comiket-personal-name-seimei').val("");
        $('span.comiket_personal_name_sei-lbl').html("");
        $('input[name="comiket_personal_name_sei"]').val("");
        $('span.comiket_personal_name_mei-lbl').html("");
        $('input[name="comiket_personal_name_mei"]').val("");

        $('span.comiket_zip1-lbl').html("");
        $('input[name="comiket_zip1"]').val("");
        $('span.comiket_zip1-str').html("");
        // 郵便番号マーク 表示/非表示制御
        dispZipMark();

        $('span.comiket_zip2-lbl').html("");
        $('input[name="comiket_zip2"]').val("");

        $('span.comiket_pref_nm-lbl').html("");
        $('select[name="comiket_pref_cd_sel"]').val("");

        $('span.comiket_address-lbl').html("");
        $('input[name="comiket_address"]').val("");

        $('span.comiket_building-lbl').html("");
        $('input[name="comiket_building"]').val("");

        $('span.comiket_tel-lbl').html("");
        $('input[name="comiket_tel"]').val("");
    }

//    function dispComiketCustomerCd() {
//
//    }

    function changeCustomerInputItem() {
        if($('input[name="comiket_div"]:checked').val() == G_DEV_BUSINESS) { // 法人
//            var comiketCustomerCdSel = $('input[name="comiket_customer_cd_sel"]:checked').val();

//            if(comiketCustomerCdSel == '1') { // 顧客コード使用する
                selectComiketDevCompany();
//            } else { // 顧客コード使用しない
//                selectComiketDevCompanyNotUseCustomerId();
//            }
            $('dl.comiket_customer_cd').show(g_fifoVal);
        } else if($('input[name="comiket_div"]:checked').val() == G_DEV_INDIVIDUAL) { // 個人
            selectComiketDevIndividual();
            $('dl.comiket_customer_cd').hide(g_fifoVal);
        } else { // 未選択
            selectComiketDevCompany();
            selectComiketDevNotSelected();
            $('dl.comiket_customer_cd').hide(g_fifoVal);
        }

//        if($('input[name="comiket_customer_cd_sel"]:checked').val() == '1') { // 顧客コード使用する
           $('input[name="comiket_customer_cd"],input[name="customer_search_btn"]').show(g_fifoVal);
//        } else { // 顧客コード使用しない
//           $('input[name="comiket_customer_cd"],input[name="customer_search_btn"]').hide(g_fifoVal);
//        }
    }

//    $('input[name="comiket_customer_cd_sel"]').on('click', function() {
//        changeCustomerInputItem();
//        selectComiketDetailOutboundService();
//        selectComiketDetailInboundService();
//
//        dispPaymentMethod();
//        dispComiketDetailType();
//
//        dispComiketDetailInboundDeliveryDateTime();
//    });

    changeCustomerInputItem(); // 顧客入力部分の制御
    if(($('input[name="comiket_zip1"]').val() && $('input[name="comiket_zip1"]').val() != "")
        || $('input[name="comiket_div"]:checked').val() == G_DEV_BUSINESS) {
        $('span.comiket_zip1-str').show(g_fifoVal);
        // 郵便番号マーク　表示/非表示制御
        dispZipMark();
    } else {
        $('span.comiket_zip1-str').hide(g_fifoVal);
        // 郵便番号マーク　表示/非表示制御
        dispZipMark();
    }

    $('input[name="comiket_customer_cd"]').on("blur", function() {

//        if($('input[name="comiket_customer_cd_sel"]:checked').val() != '1') { // 顧客コード使用する以外
//            return;
//        }

        sgwns.api('/common/php/SearchCustomer.php', getFormData(), (function (data) {

            if(data.isGetCustomerInfo) {
                $('.comiket_customer_cd_message').html('');
            } else {
                $('.comiket_customer_cd_message').html('顧客情報の取得に失敗しました。<br/>');
            }

            var kkyKokyakuYubinNo = data.kkyKokyakuYubinNo;

            ////////////////////////////////////////////////
            // 顧客名
            ////////////////////////////////////////////////
            var kkyKokyakuNm = data.kkyKokyakuNm;
//            if($('input[name="comiket_div"]:checked').val() == "1") { // 法人
                $('span.office_name-lbl').html(kkyKokyakuNm.substring(0, 16));
                $('input[name="office_name"]').val(kkyKokyakuNm.substring(0, 16));

//            } else if($('input[name="comiket_div"]:checked').val() == "2") { // 個人
                $('span.comiket_personal_name_sei-lbl').html(kkyKokyakuNm.substring(0, 8));
                $('input[name="comiket_personal_name_sei"]').val(kkyKokyakuNm.substring(0, 8));
                $('span.comiket_personal_name_mei-lbl').html(kkyKokyakuNm.substring(0, 8));
                $('input[name="comiket_personal_name_mei"]').val(kkyKokyakuNm.substring(0, 8));
//            }

            ////////////////////////////////////////////////
            // 郵便番号
            ////////////////////////////////////////////////
            var comiketZip1 = "";
            var comiketZip2 = "";
            if(kkyKokyakuYubinNo) {
                comiketZip1 = kkyKokyakuYubinNo.slice(0, 3);
                comiketZip2 = kkyKokyakuYubinNo.slice(3);
            }

            $('span.comiket_zip1-lbl').html(comiketZip1);
            if(comiketZip1 && comiketZip1 != "") {
//                $('span.comiket_zip1-str').html("-");
                $('span.comiket_zip1-str').html("");
            } else {
                $('span.comiket_zip1-str').html("");
            }
            $('input[name="comiket_zip1"]').val(comiketZip1);
            if(($('input[name="comiket_zip1"]').val() && $('input[name="comiket_zip1"]').val() != "")
                    || $('input[name="comiket_div"]:checked').val() ==  G_DEV_BUSINESS) {
                $('span.comiket_zip1-str').show(g_fifoVal);
            }
            // 郵便番号マーク 表示/非表示制御
            dispZipMark();

            $('span.comiket_zip2-lbl').html(comiketZip2);
            $('input[name="comiket_zip2"]').val(comiketZip2);

            ////////////////////////////////////////////////
            // 都道府県名
            ////////////////////////////////////////////////
            var kkyJisChikuTdfknNm = data.kkyJisChikuTdfknNm;
            if(kkyJisChikuTdfknNm) {
                $('span.comiket_pref_nm-lbl').html(kkyJisChikuTdfknNm);
                $('input[name="comiket_pref_nm"]').val(kkyJisChikuTdfknNm);
                //comiket_pref_cd_sel
            } else {
                $('span.comiket_pref_nm-lbl').html("");
                $('input[name="comiket_pref_nm"]').val("");
            }

            ////////////////////////////////////////////////
            // 都道府県コード
            ////////////////////////////////////////////////
            var kkyJisChikuTdfknCd = data.kkyJisChikuTdfknCd;
            if(kkyJisChikuTdfknCd) {
                $('select[name="comiket_pref_cd_sel"]').val(kkyJisChikuTdfknCd);
            } else {
                $('select[name="comiket_pref_cd_sel"]').val("");
            }

            ////////////////////////////////////////////////
            // 市区町村
            ////////////////////////////////////////////////
            var kkyJisChikuSkgntysnNm = data.kkyJisChikuSkgntysnNm.replace(/\s+/g, "");
            if(kkyJisChikuSkgntysnNm) {
                $('span.comiket_address-lbl').html(kkyJisChikuSkgntysnNm.substring(0, 14));
                $('input[name="comiket_address"]').val(kkyJisChikuSkgntysnNm.substring(0, 14));
            } else {
                $('span.comiket_address-lbl').html("");
                $('input[name="comiket_address"]').val("");
            }

            ////////////////////////////////////////////////
            // 番地・建物名
            ////////////////////////////////////////////////
            var outputAddress = "";
            var kkyJisChikuOaztsushoNm = data.kkyJisChikuOaztsushoNm.replace(/\s+/g, "");
            if(kkyJisChikuOaztsushoNm) {
                outputAddress = kkyJisChikuOaztsushoNm;
            }

            var kkyJisChikuAzchomeNm = data.kkyJisChikuAzchomeNm.replace(/\s+/g, "");
            if(kkyJisChikuAzchomeNm) {
                outputAddress = outputAddress + kkyJisChikuAzchomeNm;
            }

            var kkyJushoBanchi = data.kkyJushoBanchi.replace(/\s+/g, "");
            if(kkyJushoBanchi) {
                outputAddress = outputAddress + kkyJushoBanchi;
            }

            var kkyJushoGo = data.kkyJushoGo.replace(/\s+/g, "");
            if(kkyJushoBanchi && kkyJushoBanchi != ""
                    && kkyJushoGo && kkyJushoGo != "") {
                outputAddress = outputAddress + "-" + kkyJushoGo;
            }

            var kkyJushoSonota = data.kkyJushoSonota.replace(/\s+/g, "");
            if(kkyJushoBanchi && kkyJushoBanchi != ""
                    && kkyJushoGo && kkyJushoGo != ""
                    && kkyJushoSonota && kkyJushoSonota != "") {
                outputAddress = outputAddress + kkyJushoSonota;
            }

            if(outputAddress) {
                $('span.comiket_building-lbl').html(outputAddress.substring(0, 30));
                $('input[name="comiket_building"]').val(outputAddress.substring(0, 30));
            } else {
                $('span.comiket_building-lbl').html("");
                $('input[name="comiket_building"]').val("");
            }

            ////////////////////////////////////////////////
            // 電話番号
            ////////////////////////////////////////////////
            var kkyKokyakuTelno = data.kkyKokyakuTelno;
            if(kkyKokyakuTelno) {
                $('span.comiket_tel-lbl').html(kkyKokyakuTelno);
                $('input[name="comiket_tel"]').val(kkyKokyakuTelno);
            } else {
                $('span.comiket_tel-lbl').html("");
                $('input[name="comiket_tel"]').val("");
            }

            changeCustomerInputItem(); // 顧客入力部分の制御

        })).done(function(data) {

        })
    });

    $('input[name="customer_search_btn"]').on('click', function() {
        $('input[name="comiket_customer_cd"]').trigger('blur');
    });

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
// 搬入-住所検索
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    $('input[name="outbound_adrs_search_btn"]').on('click', (function () {
        var $form = $('form').first();
        AjaxZip2.zip2addr(
            'input_forms',
            'comiket_detail_outbound_zip1',
            'comiket_detail_outbound_pref_cd_sel',
            'comiket_detail_outbound_address',
            'comiket_detail_outbound_zip2',
            '',
            '',
            $form.data('featureId'),
            $form.data('id'),
            $('input[name="ticket"]').val()
        ).done(function() {
            // 集荷先都道府県、お預かり年月日が入力されている場合お届け可能日付範囲を取得する
            if ($('[name=comiket_detail_outbound_pref_cd_sel]').val()) {
            
                // お預かり可能日付範囲を取得
                getOutBoundCollectCal();
            }

        });
//        $('input[name="comiket_address"]').removeAttr('style');
        $('input').filter('[name="comiket_detail_outbound_zip1"],[name="comiket_detail_outbound_zip2"]').trigger('focusout');
    }));

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 搬出-住所検索
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    $('input[name="inbound_adrs_search_btn"]').on('click', (function () {
        var $form = $('form').first();
        AjaxZip2.zip2addr(
            'input_forms',
            'comiket_detail_inbound_zip1',
            'comiket_detail_inbound_pref_cd_sel',
            'comiket_detail_inbound_address',
            'comiket_detail_inbound_zip2',
            '',
            '',
            $form.data('featureId'),
            $form.data('id'),
            $('input[name="ticket"]').val()
        ).done(function () {
            // お届け可能日付範囲を取得
            if ($('[name=comiket_detail_inbound_collect_date_year_sel]').val()
                    && $('[name=comiket_detail_inbound_collect_date_month_sel]').val()
                    && $('[name=comiket_detail_inbound_collect_date_day_sel]').val()
                    && $('[name=comiket_detail_inbound_pref_cd_sel]').val()) {
                // お届け可能日付範囲を取得
                getInBoundUnloadingCal();
            }
        });
//        $('input[name="comiket_address"]').removeAttr('style');
        $('input').filter('[name="comiket_detail_inbound_zip1"],[name="comiket_detail_inbound_zip2"]').trigger('focusout');
    }));

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 搬入-お預かり・お届け日(時)表示制御
//////////////////////////////////////////////////////////////////////////////////////////////////////////

    function isDispOutboundColAndDlyDateTimeByDb(check) {
        var comiketDiv = $('input[name=comiket_div]:checked').val();
        var eventsubSel = $('select[name="eventsub_sel"]').val();
//        var detailType = $('input[name="comiket_detail_type_sel"]:checked').val();
        var serviceSel = $('input[name="comiket_detail_outbound_service_sel"]:checked').val();

        // 個人・往路・宅配
        if(comiketDiv == G_DEV_INDIVIDUAL && serviceSel == '1') {
            if(g_eventsubList[eventsubSel]['kojin_box_col_date_flg'] == '1' && check == 'date') {
                return true;
            }

            if(g_eventsubList[eventsubSel]['kojin_box_col_time_flg'] == '1' && check == 'time') {
                return true;
            }
        }

        // 個人・往路・カーゴ
        if(comiketDiv == G_DEV_INDIVIDUAL && serviceSel == '2') {
            if(g_eventsubList[eventsubSel]['kojin_cag_col_date_flg'] == '1' && check == 'date') {
                return true;
            }

            if(g_eventsubList[eventsubSel]['kojin_cag_col_time_flg'] == '1' && check == 'time') {
                return true;
            }
        }

        // 法人・往路・宅配
        if(comiketDiv == G_DEV_BUSINESS && serviceSel == '1') {
            if(g_eventsubList[eventsubSel]['hojin_box_col_date_flg'] == '1' && check == 'date') {
                return true;
            }

            if(g_eventsubList[eventsubSel]['hojin_box_col_time_flg'] == '1' && check == 'time') {
                return true;
            }
        }

        // 法人・往路・カーゴ
        if(comiketDiv == G_DEV_BUSINESS && serviceSel == '2') {
            if(g_eventsubList[eventsubSel]['hojin_cag_col_date_flg'] == '1' && check == 'date') {
                return true;
            }

            if(g_eventsubList[eventsubSel]['hojin_cag_col_time_flg'] == '1' && check == 'time') {
                return true;
            }
        }

        // 法人・往路・貸切
        if(comiketDiv == G_DEV_BUSINESS && serviceSel == '3') {
            if(g_eventsubList[eventsubSel]['hojin_kas_col_date_flg'] == '1' && check == 'date') {
                return true;
            }

            if(g_eventsubList[eventsubSel]['hojin_kas_col_time_flg'] == '1' && check == 'time') {
                return true;
            }
        }

        return false;
    }

    /***
     *
     * @param {type} checkDateOrTime 'date' => 日付フラグチェック, 'time' => 時間帯フラグチェック
     * @returns {Boolean}
     */
    function dispOutboundColAndDlyDateTimeByDb() {
        var comiketDiv = $('input[name=comiket_div]:checked').val();
        var eventsubSel = $('select[name="eventsub_sel"]').val();
//        var detailType = $('input[name="comiket_detail_type_sel"]:checked').val();
        var serviceSel = $('input[name="comiket_detail_outbound_service_sel"]:checked').val();
//console.log("############ 99");
//console.log(comiketDiv);
//console.log(serviceSel);
//console.log(g_eventsubList[eventsubSel]['kojin_cag_col_date_flg']);
//console.log(g_eventsubList[eventsubSel]['kojin_cag_col_time_flg']);

        // 個人・往路・宅配
        if(comiketDiv == G_DEV_INDIVIDUAL && serviceSel == '1') {
            if(g_eventsubList[eventsubSel]['kojin_box_col_date_flg'] == '1') {
                $('.class_comiket_detail_outbound_collect_date').show(g_fifoVal);
            } else {
                $('.class_comiket_detail_outbound_collect_date').hide(g_fifoVal);
            }

            if (g_eventsubList[eventsubSel]['kojin_box_del_date_flg'] == '1') {
                $('span.comiket_detail_outbound_delivery_date').show(g_fifoVal);
            } else {
                $('span.comiket_detail_outbound_delivery_date').hide(g_fifoVal);
            }

            if(g_eventsubList[eventsubSel]['kojin_box_col_time_flg'] == '1') {
                $('.comiket_detail_outbound_collect_time_sel').show(g_fifoVal);
            } else {
                $('select[name=comiket_detail_outbound_collect_time_sel]').val("00");
                $('.comiket_detail_outbound_collect_time_sel').hide(g_fifoVal);
            }

            if (g_eventsubList[eventsubSel]['kojin_box_del_time_flg'] == '1') {
                $('.comiket_detail_outbound_delivery_time_sel').show(g_fifoVal);
            } else {
                $('select[name=comiket_detail_outbound_delivery_time_sel]').val("00");
                $('.comiket_detail_outbound_delivery_time_sel').hide(g_fifoVal);
            }

            return;
        }

        // 個人・往路・カーゴ
        if(comiketDiv == G_DEV_INDIVIDUAL && serviceSel == '2') {

            if(g_eventsubList[eventsubSel]['kojin_cag_col_date_flg'] == '1') {
                $('.class_comiket_detail_outbound_collect_date').show(g_fifoVal);
            } else {
                $('.class_comiket_detail_outbound_collect_date').hide(g_fifoVal);
            }

            if(g_eventsubList[eventsubSel]['kojin_cag_col_time_flg'] == '1') {
                $('.comiket_detail_outbound_collect_time_sel').show(g_fifoVal);
            } else {
                $('select[name=comiket_detail_outbound_collect_time_sel]').val("00");
                $('.comiket_detail_outbound_collect_time_sel').hide(g_fifoVal);
            }
            return;
        }

        // 法人・往路・宅配
        if(comiketDiv == G_DEV_BUSINESS && serviceSel == '1') {
            if(g_eventsubList[eventsubSel]['hojin_box_col_date_flg'] == '1') {
                $('.class_comiket_detail_outbound_collect_date').show(g_fifoVal);
            } else {
                $('.class_comiket_detail_outbound_collect_date').hide(g_fifoVal);
            }

            if (g_eventsubList[eventsubSel]['hojin_box_del_date_flg'] == '1') {
                $('span.comiket_detail_outbound_delivery_date').show(g_fifoVal);
            } else {
                $('span.comiket_detail_outbound_delivery_date').hide(g_fifoVal);
            }

            if(g_eventsubList[eventsubSel]['hojin_box_col_time_flg'] == '1') {
                $('.comiket_detail_outbound_collect_time_sel').show(g_fifoVal);
            } else {
                $('select[name=comiket_detail_outbound_collect_time_sel]').val("00");
                $('.comiket_detail_outbound_collect_time_sel').hide(g_fifoVal);
            }

            if (g_eventsubList[eventsubSel]['hojin_box_del_time_flg'] == '1') {
                $('.comiket_detail_outbound_delivery_time_sel').show(g_fifoVal);
            } else {
                $('select[name=comiket_detail_outbound_delivery_time_sel]').val("00");
                $('.comiket_detail_outbound_delivery_time_sel').hide(g_fifoVal);
            }

            return;
        }

        // 法人・往路・カーゴ
        if(comiketDiv == G_DEV_BUSINESS && serviceSel == '2') {
            if(g_eventsubList[eventsubSel]['hojin_cag_col_date_flg'] == '1') {
                $('.class_comiket_detail_outbound_collect_date').show(g_fifoVal);
            } else {
                $('.class_comiket_detail_outbound_collect_date').hide(g_fifoVal);
            }

            if(g_eventsubList[eventsubSel]['hojin_cag_col_time_flg'] == '1') {
                $('.comiket_detail_outbound_collect_time_sel').show(g_fifoVal);
            } else {
                $('select[name=comiket_detail_outbound_collect_time_sel]').val("00");
                $('.comiket_detail_outbound_collect_time_sel').hide(g_fifoVal);
            }
            return;
        }

        // 法人・往路・貸切
        if(comiketDiv == G_DEV_BUSINESS && serviceSel == '3') {
            if(g_eventsubList[eventsubSel]['hojin_kas_col_date_flg'] == '1') {
                $('.class_comiket_detail_outbound_collect_date').show(g_fifoVal);
            } else {
                $('.class_comiket_detail_outbound_collect_date').hide(g_fifoVal);
            }

            if(g_eventsubList[eventsubSel]['hojin_kas_col_time_flg'] == '1') {
                $('.comiket_detail_outbound_collect_time_sel').show(g_fifoVal);
            } else {
                $('select[name=comiket_detail_outbound_collect_time_sel]').val("00");
                $('.comiket_detail_outbound_collect_time_sel').hide(g_fifoVal);
            }
            return;
        }

        $('.class_comiket_detail_outbound_collect_date').hide(g_fifoVal);
        $('select[name=comiket_detail_outbound_collect_time_sel]').val("00");
        $('.comiket_detail_outbound_collect_time_sel').hide(g_fifoVal);
        return;
    }

    function dispOutboundColAndDelyDate() {
        var eventsubSel = $('select[name="eventsub_sel"]').val();
        if(!eventsubSel || eventsubSel == "") {
            return;
        }

//console.log("###################### 101");
//console.log(g_eventsubList);
        if(g_eventsubList[eventsubSel]['is_eq_outbound_collect'] == true) {
//console.log("###################### 102");
            $('.comiket-detail-outbound-collect-date-fr-to').html(g_eventsubList[eventsubSel]['outbound_collect_fr']);
            $('.comiket_detail_outbound_collect_date').hide(g_fifoVal);
            // 搬入-お預かり日時の日付が、from to 同じ場合は、時間帯のみ入力可能にする
            // 搬出の場合は、お届け日時が from to が同じ場合のパターンがないため、制御は入れていない
            $('.comiket_detail_outbound_collect_time_sel').show(g_fifoVal);
            $('.comiket-detail-outbound-collect-date-fr-to').css('float', 'left');
            $('.comiket_detail_outbound_collect_date_input_part').css('white-space', 'nowrap');

//            $('.comiket_detail_outbound_collect_date_parts').hide(g_fifoVal);
            $('select[name="comiket_detail_outbound_collect_date_year_sel"]').val(g_eventsubList[eventsubSel]['outbound_collect_fr_year']);
            $('select[name="comiket_detail_outbound_collect_date_month_sel"]').val(g_eventsubList[eventsubSel]['outbound_collect_fr_month']);
            $('select[name="comiket_detail_outbound_collect_date_day_sel"]').val(g_eventsubList[eventsubSel]['outbound_collect_fr_day']);
        } else {
            $('.comiket-detail-outbound-collect-date-fr-to').html(g_eventsubList[eventsubSel]['outbound_collect_fr'] + '&nbsp;から&nbsp;' + g_eventsubList[eventsubSel]['outbound_collect_to']
                    + '&nbsp;まで選択できます。');
            $('.comiket_detail_outbound_collect_date_parts').show(g_fifoVal);
        }

        if(g_eventsubList[eventsubSel]['is_eq_outbound_delivery'] == true) {
            $('.comiket-detail-outbound-delivery-date-fr-to').html(g_eventsubList[eventsubSel]['outbound_delivery_fr']);
            $('.comiket_detail_outbound_delivery_date_parts').hide(g_fifoVal);
            $('select[name="comiket_detail_outbound_delivery_date_year_sel"]').val(g_eventsubList[eventsubSel]['outbound_delivery_fr_year']);
            $('select[name="comiket_detail_outbound_delivery_date_month_sel"]').val(g_eventsubList[eventsubSel]['outbound_delivery_fr_month']);
            $('select[name="comiket_detail_outbound_delivery_date_day_sel"]').val(g_eventsubList[eventsubSel]['outbound_delivery_fr_day']);
        } else {
            $('.comiket-detail-outbound-delivery-date-fr-to').html(g_eventsubList[eventsubSel]['outbound_delivery_fr'] + '&nbsp;から&nbsp;' + g_eventsubList[eventsubSel]['outbound_delivery_to']
                    + '&nbsp;まで選択できます。');
            $('.comiket_detail_outbound_delivery_date_parts').show(g_fifoVal);
        }
//        if(g_eventsubList['is_eq_outbound_delivery'] == true) {
//
//        }
    }

    // 搬入-お預かり・お届け日表示制御
    dispOutboundColAndDelyDate();

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 搬出-お預かり・お届け日(時)表示制御
//////////////////////////////////////////////////////////////////////////////////////////////////////////
//
    function isDispInboundColAndDlyDateTimeByDb(check) {
        var comiketDiv = $('input[name=comiket_div]:checked').val();
        var eventsubSel = $('select[name="eventsub_sel"]').val();
//        var detailType = $('input[name="comiket_detail_type_sel"]:checked').val();
        var serviceSel = $('input[name="comiket_detail_inbound_service_sel"]:checked').val();

        // 個人・復路・宅配
        if(comiketDiv == G_DEV_INDIVIDUAL && serviceSel == '1') {
            if(g_eventsubList[eventsubSel]['kojin_box_dlv_date_flg'] == '1' && check == 'date') {
                return true;
            }

            if(g_eventsubList[eventsubSel]['kojin_box_dlv_time_flg'] == '1' && check == 'time') {
                return true;
            }
        }

        // 個人・復路・カーゴ
        if(comiketDiv == G_DEV_INDIVIDUAL && serviceSel == '2') {
            if(g_eventsubList[eventsubSel]['kojin_cag_dlv_date_flg'] == '1' && check == 'date') {
                return true;
            }

            if(g_eventsubList[eventsubSel]['kojin_cag_dlv_time_flg'] == '1' && check == 'time') {
                return true;
            }
        }

        // 法人・復路・宅配
        if(comiketDiv == G_DEV_BUSINESS && serviceSel == '1') {
            if(g_eventsubList[eventsubSel]['hojin_box_dlv_date_flg'] == '1' && check == 'date') {
                return true;
            }

            if(g_eventsubList[eventsubSel]['hojin_box_dlv_time_flg'] == '1' && check == 'time') {
                return true;
            }
        }

        // 法人・復路・カーゴ
        if(comiketDiv == G_DEV_BUSINESS && serviceSel == '2') {
            if(g_eventsubList[eventsubSel]['hojin_cag_dlv_date_flg'] == '1' && check == 'date') {
                return true;
            }

            if(g_eventsubList[eventsubSel]['hojin_cag_dlv_time_flg'] == '1' && check == 'time') {
                return true;
            }
        }

        // 法人・復路・貸切
        if(comiketDiv == G_DEV_BUSINESS && serviceSel == '3') {
            if(g_eventsubList[eventsubSel]['hojin_kas_col_date_flg'] == '1' && check == 'date') {
                return true;
            }

            if(g_eventsubList[eventsubSel]['hojin_kas_dlv_time_flg'] == '1' && check == 'time') {
                return true;
            }
        }

        return false;
    }

    /***
     *
     * @param {type} checkDateOrTime 'date' => 日付フラグチェック, 'time' => 時間帯フラグチェック
     * @returns {Boolean}
     */
    function dispInboundColAndDlyDateTimeByDb() {
        var comiketDiv = $('input[name=comiket_div]:checked').val();
        var eventsubSel = $('select[name="eventsub_sel"]').val();
//        var detailType = $('input[name="comiket_detail_type_sel"]:checked').val();
        var serviceSel = $('input[name="comiket_detail_inbound_service_sel"]:checked').val();
console.log("############ 99");
console.log(comiketDiv);
console.log(serviceSel);
console.log(g_eventsubList[eventsubSel]['kojin_box_col_date_flg']);
console.log(g_eventsubList[eventsubSel]['kojin_box_col_time_flg']);
// comiket_detail_inbound_delivery_date
        // 個人・復路・宅配
        if(comiketDiv == G_DEV_INDIVIDUAL && serviceSel == '1') {
            if(g_eventsubList[eventsubSel]['kojin_box_dlv_date_flg'] == '1') {
                $('.comiket_detail_inbound_delivery_date').show(g_fifoVal);
            } else {
                $('.comiket_detail_inbound_delivery_date').hide(g_fifoVal);
            }

            if(g_eventsubList[eventsubSel]['kojin_box_dlv_time_flg'] == '1') {
                $('.comiket_detail_inbound_delivery_time_sel').show(g_fifoVal);
            } else {
                $('select[name=comiket_detail_inbound_delivery_time_sel]').val("00,指定なし");
                $('.comiket_detail_inbound_delivery_time_sel').hide(g_fifoVal);
            }
            return;
        }

        // 個人・復路・カーゴ
        if(comiketDiv == G_DEV_INDIVIDUAL && serviceSel == '2') {

            if(g_eventsubList[eventsubSel]['kojin_cag_dlv_date_flg'] == '1') {
                $('.comiket_detail_inbound_delivery_date').show(g_fifoVal);
            } else {
                $('.comiket_detail_inbound_delivery_date').hide(g_fifoVal);
            }

            if(g_eventsubList[eventsubSel]['kojin_cag_dlv_time_flg'] == '1') {
                $('.comiket_detail_inbound_delivery_time_sel').show(g_fifoVal);
            } else {
                $('select[name=comiket_detail_inbound_delivery_time_sel]').val("00,指定なし");
                $('.comiket_detail_inbound_delivery_time_sel').hide(g_fifoVal);
            }
            return;
        }

        // 法人・復路・宅配
        if(comiketDiv == G_DEV_BUSINESS && serviceSel == '1') {
            if(g_eventsubList[eventsubSel]['hojin_box_dlv_date_flg'] == '1') {
                $('.comiket_detail_inbound_delivery_date').show(g_fifoVal);
            } else {
                $('.comiket_detail_inbound_delivery_date').hide(g_fifoVal);
            }

            if(g_eventsubList[eventsubSel]['hojin_box_dlv_time_flg'] == '1') {
                $('.comiket_detail_inbound_delivery_time_sel').show(g_fifoVal);
            } else {
                $('select[name=comiket_detail_inbound_delivery_time_sel]').val("00,指定なし");
                $('.comiket_detail_inbound_delivery_time_sel').hide(g_fifoVal);
            }
            return;
        }

        // 法人・復路・カーゴ
        if(comiketDiv == G_DEV_BUSINESS && serviceSel == '2') {
            if(g_eventsubList[eventsubSel]['hojin_cag_dlv_date_flg'] == '1') {
                $('.comiket_detail_inbound_delivery_date').show(g_fifoVal);
            } else {
                $('.comiket_detail_inbound_delivery_date').hide(g_fifoVal);
            }

            if(g_eventsubList[eventsubSel]['hojin_cag_dlv_time_flg'] == '1') {
                $('.comiket_detail_inbound_delivery_time_sel').show(g_fifoVal);
            } else {
                $('select[name=comiket_detail_inbound_delivery_time_sel]').val("00,指定なし");
                $('.comiket_detail_inbound_delivery_time_sel').hide(g_fifoVal);
            }
            return;
        }

        // 法人・復路・貸切
        if(comiketDiv == G_DEV_BUSINESS && serviceSel == '3') {
            if(g_eventsubList[eventsubSel]['hojin_kas_col_date_flg'] == '1') {
                $('.comiket_detail_inbound_delivery_date').show(g_fifoVal);
            } else {
                $('.comiket_detail_inbound_delivery_date').hide(g_fifoVal);
            }

            if(g_eventsubList[eventsubSel]['hojin_kas_dlv_time_flg'] == '1') {
                $('.comiket_detail_inbound_delivery_time_sel').show(g_fifoVal);
            } else {
                $('select[name=comiket_detail_inbound_delivery_time_sel]').val("00,指定なし");
                $('.comiket_detail_inbound_delivery_time_sel').hide(g_fifoVal);
            }
            return;
        }

        $('.comiket_detail_inbound_delivery_date').hide(g_fifoVal);
        $('select[name=comiket_detail_inbound_delivery_time_sel]').val("00,指定なし");
        $('.comiket_detail_inbound_delivery_time_sel').hide(g_fifoVal);
        return;
    }

    function dispInboundColAndDelyDate() {
        var eventsubSel = $('select[name="eventsub_sel"]').val();
        if(!eventsubSel || eventsubSel == "") {
            return;
        }

        if(g_eventsubList[eventsubSel]['is_eq_inbound_collect'] == true) {
            $('.comiket-detail-inbound-collect-date-fr-to').html(g_eventsubList[eventsubSel]['inbound_collect_fr']);
            $('.comiket_detail_inbound_collect_date_parts').hide(g_fifoVal);
            $('select[name="comiket_detail_inbound_collect_date_year_sel"]').val(g_eventsubList[eventsubSel]['inbound_collect_fr_year']);
            $('select[name="comiket_detail_inbound_collect_date_month_sel"]').val(g_eventsubList[eventsubSel]['inbound_collect_fr_month']);
            $('select[name="comiket_detail_inbound_collect_date_day_sel"]').val(g_eventsubList[eventsubSel]['inbound_collect_fr_day']);
        } else {
            $('.comiket-detail-inbound-collect-date-fr-to').html(g_eventsubList[eventsubSel]['inbound_collect_fr'] + '&nbsp;から&nbsp;' + g_eventsubList[eventsubSel]['inbound_collect_to']
                    + '&nbsp;まで選択できます。');
            $('.comiket_detail_inbound_collect_date_parts').show(g_fifoVal);
            $('.comiket_detail_inbound_collect_date').show(g_fifoVal);
        }

        if(g_eventsubList[eventsubSel]['is_eq_inbound_delivery'] == true) {
            $('.comiket-detail-inbound-delivery-date-fr-to').html(g_eventsubList[eventsubSel]['inbound_delivery_fr']);
            $('.comiket_detail_inbound_delivery_date_parts').hide(g_fifoVal);
            $('select[name="comiket_detail_inbound_delivery_date_year_sel"]').val(g_eventsubList[eventsubSel]['inbound_delivery_fr_year']);
            $('select[name="comiket_detail_inbound_delivery_date_month_sel"]').val(g_eventsubList[eventsubSel]['inbound_delivery_fr_month']);
            $('select[name="comiket_detail_inbound_delivery_date_day_sel"]').val(g_eventsubList[eventsubSel]['inbound_delivery_fr_day']);



        } else {
            $('.comiket-detail-inbound-delivery-date-fr-to').html(g_eventsubList[eventsubSel]['inbound_delivery_fr'] + '&nbsp;から&nbsp;' + g_eventsubList[eventsubSel]['inbound_delivery_to']
                    + '&nbsp;まで選択できます。');
            $('.comiket_detail_inbound_delivery_date_parts').show(g_fifoVal);
            $('.comiket_detail_inbound_delivery_date').show(g_fifoVal);
        }
//        if(g_eventsubList['is_eq_outbound_delivery'] == true) {
//
//        }
    }

    // 搬出-お預かり・お届け日表示制御
    dispInboundColAndDelyDate();

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 搬入-時間帯表示制御
//////////////////////////////////////////////////////////////////////////////////////////////////////////

    function dispComiketDetailOutboundDeliveryTime() {

//        var outboundServiceSel = $('input[name="comiket_detail_outbound_service_sel"]:checked').val();
//        if(outboundServiceSel == '2') { // カーゴの場合 時間帯指定非表示
//            // お預かり時間帯 表示制御 - サービスによる
//            dispComiketDetailOutboundDeliveryTimeByService();
//            return;
//        }
//        var comiketDivVal = $('input[name="comiket_div"]:checked').val();
//        if( (comiketDivVal == G_DEV_BUSINESS && outboundServiceSel == '1')
//                || comiketDivVal == G_DEV_INDIVIDUAL) {
//            dispComiketDetailOutboundCollectTimeByDivAndType();
//            return;
//        }

        var formDataList = getFormData();
//        for(var i = 0; i < formDataList.length; i++) {
//            formDataList[0]['name'] ==
//        }
        var zip1 = $('input[name=comiket_detail_outbound_zip1]').val();
        var zip2 = $('input[name=comiket_detail_outbound_zip2]').val();

        if(!zip1 || zip1.length != 3 || !zip2 || zip2.length != 4) {
            return;
        }

        formDataList.push(
                {
                    name: 'zip1',
                    value: zip1
                },
                {
                    name: 'zip2',
                    value: zip2
                }
        );
        if(!isDispOutboundColAndDlyDateTimeByDb('date') || !isDispOutboundColAndDlyDateTimeByDb('time') ) {
            return;
        }
        sgwns.api('/common/php/SearchTimeZoneFlag.php', formDataList, (function (isDisable) {
//console.log("################### 301");
//console.log(data);

//            disableTime($select, data);
            if(isDisable) {
                $('select[name=comiket_detail_outbound_collect_time_sel]').val("00");
//                $('select[name=comiket_detail_outbound_collect_time_sel]').hide(g_fifoVal);
                $('span.comiket_detail_outbound_collect_time_sel').hide(g_fifoVal);
            } else {
                $('span.comiket_detail_outbound_collect_time_sel').show(g_fifoVal);
//                $('select[name=comiket_detail_outbound_collect_time_sel]').show(g_fifoVal);
            }
        }));
    }

    $('input').filter('[name="comiket_detail_outbound_zip1"],[name="comiket_detail_outbound_zip2"]').on('focusout keydown keyup', (function () {
        var hidOutboundCollFrom = $('#hid_comiket-detail-outbound-collect-date-from').val();
        var hidOutboundCollTo = $('#hid_comiket-detail-outbound-collect-date-to').val();
        if (hidOutboundCollFrom == '1900-01-01'
                && hidOutboundCollTo == '1900-01-01') {
            return;
        }
        
        dispComiketDetailOutboundDeliveryTime();
    })).first().trigger('focusout');

    // 搬入 /////////////////////////////
//    function dispComiketDetailOutboundDeliveryTimeByDivAndType() {
//        var comiketDivVal = $('input[name="comiket_div"]:checked').val();
//        var comiketDetailTypeVal = $('input[name="comiket_detail_type_sel"]').val();
//        if(comiketDetailTypeVal == "1" || comiketDetailTypeVal == "3") { // 搬入
//            $('.comiket_detail_outbound_delivery_time_sel').hide(g_fifoVal);
//        } else {
//            $('.comiket_detail_outbound_delivery_time_sel').show(g_fifoVal);
//        }
//    }
//    dispComiketDetailOutboundDeliveryTimeByDivAndType();

//    function dispComiketDetailOutboundCollectTimeByDivAndType() {
//
//        var comiketDivVal = $('input[name="comiket_div"]:checked').val();
//        var comiketDetailTypeVal = $('input[name="comiket_detail_type_sel"]').val();
////window.console.log(comiketDivVal);
////window.console.log(comiketDetailTypeVal);
////window.console.log($('input[name="comiket_detail_outbound_service_sel"]:checked').val());
//        if(comiketDetailTypeVal == "1" || comiketDetailTypeVal == "3") { // 搬入
//            var outboundServiceSel = $('input[name="comiket_detail_outbound_service_sel"]:checked').val();
//            if(comiketDivVal == G_DEV_BUSINESS && outboundServiceSel == '1') {
//                $('select[name=comiket_detail_outbound_collect_time_sel]').val("00");
//                $('.comiket_detail_outbound_collect_time_sel').hide(g_fifoVal);
//            } else if(comiketDivVal == G_DEV_INDIVIDUAL && outboundServiceSel == '1') {
//                $('select[name=comiket_detail_outbound_collect_time_sel]').val("00");
//                $('.comiket_detail_outbound_collect_time_sel').hide(g_fifoVal);
//            } else if(comiketDivVal == G_DEV_INDIVIDUAL && outboundServiceSel == '2') {
//                $('select[name=comiket_detail_outbound_collect_time_sel]').val("00");
//                $('.comiket_detail_outbound_collect_time_sel').hide(g_fifoVal);
//            } else {
////                $('.comiket_detail_outbound_collect_time_sel').show(g_fifoVal);
//                if(comiketDivVal == G_DEV_INDIVIDUAL) {
//                    $('select[name=comiket_detail_outbound_collect_time_sel]').val("00");
//                    $('.comiket_detail_outbound_collect_time_sel').hide(g_fifoVal);
//                } else {
//                    $('.comiket_detail_outbound_collect_time_sel').show(g_fifoVal);
//                }
//            }
//
//        } else {
////            $('.comiket_detail_outbound_collect_time_sel').show(g_fifoVal);
//        }
//    }
//    dispComiketDetailOutboundCollectTimeByDivAndType();



//    function dispComiketDetailOutboundDeliveryTimeByService() {
//        var comiketDivVal = $('input[name="comiket_div"]:checked').val();
//        var comiketDetailTypeVal = $('input[name="comiket_detail_type_sel"]').val();
//        if(comiketDetailTypeVal == "1" || comiketDetailTypeVal == "3") { // 搬入
//            var outboundServiceSel = $('input[name="comiket_detail_outbound_service_sel"]:checked').val();
////            if(comiketDivVal == G_DEV_BUSINESS && outboundServiceSel == '1') {
////                $('select[name=comiket_detail_outbound_collect_time_sel]').val("00");
////                $('.comiket_detail_outbound_collect_time_sel').hide(g_fifoVal);
////                return;
////            } else if(comiketDivVal == G_DEV_INDIVIDUAL && outboundServiceSel == '1') {
////                $('select[name=comiket_detail_outbound_collect_time_sel]').val("00");
////                $('.comiket_detail_outbound_collect_time_sel').hide(g_fifoVal);
////                return;
////            } else if(comiketDivVal == G_DEV_INDIVIDUAL && outboundServiceSel == '2') {
////                $('select[name=comiket_detail_outbound_collect_time_sel]').val("00");
////                $('.comiket_detail_outbound_collect_time_sel').hide(g_fifoVal);
////                return;
////            } else {
//////                $('.comiket_detail_outbound_collect_time_sel').show(g_fifoVal);
////                if(comiketDivVal == G_DEV_INDIVIDUAL) {
////                    $('select[name=comiket_detail_outbound_collect_time_sel]').val("00");
////                    $('.comiket_detail_outbound_collect_time_sel').hide(g_fifoVal);
////                } else {
////                    $('.comiket_detail_outbound_collect_time_sel').show(g_fifoVal);
////                }
////                return;
////            }
//
//            if(outboundServiceSel == '2') { // カーゴの場合 時間帯指定非表示
//               $('.comiket_detail_outbound_collect_time_sel').hide(g_fifoVal);
//               $('select[name="comiket_detail_outbound_collect_time_sel"]').val('00');
//            } else {
//                dispComiketDetailOutboundDeliveryTime();
//            }
//        }
//    }
    // お預かり時間帯 表示制御 - サービスによる
//    dispComiketDetailOutboundDeliveryTimeByService();

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 搬出-時間帯表示制御
//////////////////////////////////////////////////////////////////////////////////////////////////////////
//************************************時間帯指定のあるなしを制御

    function dispComiketDetailInboundDeliveryTime() {
        var formDataList = getFormData();
// お届け先郵便番号
        var zip1 = $('input[name=comiket_detail_inbound_zip1]').val();
        var zip2 = $('input[name=comiket_detail_inbound_zip2]').val();

//        var eventsubId = $('select[name="eventsub_sel"]').val();
//        var kojinBoxColTimeFlg = g_eventsubList[eventsubId]['kojin_box_col_time_flg'];
        var comiketDetailTypeVal = $('input[name="comiket_detail_type_sel"]:checked').val();
        if(comiketDetailTypeVal == "2" || comiketDetailTypeVal == "3") { // 搬出
            $('select[name=comiket_detail_inbound_collect_time_sel]').val("00");
            $('.comiket_detail_inbound_collect_time_sel').hide(g_fifoVal);
        }

        if(!zip1 || zip1.length != 3 || !zip2 || zip2.length != 4) {
            return;
        }

        formDataList.push(
                {
                    name: 'zip1',
                    value: zip1
                },
                {
                    name: 'zip2',
                    value: zip2
                }
        );


        if(!isDispInboundColAndDlyDateTimeByDb('date') || !isDispInboundColAndDlyDateTimeByDb('time') ) {
            return;
        }

// Ajaxで取得
        sgwns.api('/common/php/SearchTimeZoneFlag.php', formDataList, (function (isDisable) {
            if(isDisable) {
                $('select[name=comiket_detail_inbound_delivery_time_sel]').val("00,指定なし");
                $('span.comiket_detail_inbound_delivery_time_sel').hide(g_fifoVal);
            } else {
                $('span.comiket_detail_inbound_delivery_time_sel').show(g_fifoVal);
            }
        }));
    }

    $('input').filter('[name="comiket_detail_inbound_zip1"],[name="comiket_detail_inbound_zip2"]').on('focusout keydown keyup', (function () {
        dispComiketDetailInboundDeliveryTime();
    })).first().trigger('focusout');

    // 搬出 ///////////////////
//    function dispComiketDetailInboundDeliveryTimeByDivAndType() {
//        var comiketDivVal = $('input[name="comiket_div"]:checked').val();
//
////console.log("################## 702-1");
//        var comiketDetailTypeVal = $('input[name="comiket_detail_type_sel"]:checked').val();
////console.log("################## 702-1 : " + comiketDetailTypeVal);
////        if($('input[name="comiket_div"]:checked').val() == G_DEV_INDIVIDUAL) { // 個人
//            if(comiketDetailTypeVal == "2" || comiketDetailTypeVal == "3") { // 搬出
////console.log("################## 702-3");
//                var inboundServiceSel = $('input[name="comiket_detail_inbound_service_sel"]:checked').val();
//                if(comiketDivVal == G_DEV_BUSINESS && (inboundServiceSel == '1' || inboundServiceSel == '2')) {
//                    $('select[name="comiket_detail_inbound_delivery_time_sel"]').val('00,指定なし');
//                    $('.comiket_detail_inbound_delivery_time_sel').hide(g_fifoVal);
//                    return;
//                } else {
//                    $('.comiket_detail_inbound_delivery_time_sel').show(g_fifoVal);
//                    return;
//                }
//
//                $('select[name="comiket_detail_inbound_delivery_time_sel"]').val('00,指定なし');
//                $('.comiket_detail_inbound_collect_time_sel').hide(g_fifoVal);
////alert('test1');
//            } else {
////console.log("################## 702-4");
//                $('.comiket_detail_inbound_collect_time_sel').show(g_fifoVal);
//            }
////        }
////console.log("################## 702-5");
//    }

//    dispComiketDetailInboundDeliveryTimeByDivAndType();

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 搬出-お届け日時・時間帯表示制御
//////////////////////////////////////////////////////////////////////////////////////////////////////////
//    function dispComiketDetailInboundDeliveryDateTime() {
////console.log($('input[name="comiket_div"]:checked').val());
//        var serviceSel = $('input[name="comiket_detail_inbound_service_sel"]:checked').val();
//        if($('input[name="comiket_div"]:checked').val() == G_DEV_BUSINESS) { // 法人
//                if(serviceSel == '1') { // 宅配の場合
//                    $('dl.comiket_detail_inbound_delivery_date').show(g_fifoVal);
//                    $('.comiket_detail_inbound_delivery_date_parts').show(g_fifoVal);
//
//                } else {
//                    $('dl.comiket_detail_inbound_delivery_date').hide(g_fifoVal);
//                }
//
//        } else if($('input[name="comiket_div"]:checked').val() == G_DEV_INDIVIDUAL) { // 個人
//            if(serviceSel == '1') { // 宅配
//                $('dl.comiket_detail_inbound_delivery_date').show(g_fifoVal);
//            } else {
//                $('dl.comiket_detail_inbound_delivery_date').hide(g_fifoVal);
//            }
//        } else { // 未選択
//            $('dl.comiket_detail_inbound_delivery_date').hide(g_fifoVal);
//        }
//    }
    // 搬出-お届け日時・時間帯表示制御
//    dispComiketDetailInboundDeliveryDateTime();

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 搬入-住所と同じボタン
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    $('input[name="outbound_adrs_copy_btn"]').on('click', (function () {
        if($('input[name="comiket_div"]:checked').val() == G_DEV_BUSINESS) { // 法人
            $('input[name="comiket_detail_outbound_name"]').val($('input[name="office_name"]').val());
        } else if($('input[name="comiket_div"]:checked').val() == G_DEV_INDIVIDUAL) { // 個人
            $('input[name="comiket_detail_outbound_name"]').val($('input[name="comiket_personal_name_sei"]').val()  + " " +  $('input[name="comiket_personal_name_mei"]').val());
//            $('input[name="comiket_detail_outbound_name_mei"]').val($('input[name="comiket_personal_name_mei"').val());
        }
        $('input[name="comiket_detail_outbound_zip1"]').val($('input[name="comiket_zip1"]').val());
        $('input[name="comiket_detail_outbound_zip2"]').val($('input[name="comiket_zip2"]').val());
        $('select[name="comiket_detail_outbound_pref_cd_sel"]').val($('select[name="comiket_pref_cd_sel"]').val());
        $('input[name="comiket_detail_outbound_address"]').val($('input[name="comiket_address"]').val());
        $('input[name="comiket_detail_outbound_building"]').val($('input[name="comiket_building"]').val());
        $('input[name="comiket_detail_outbound_tel"]').val($('input[name="comiket_tel"]').val());

        // 集荷先都道府県、お預かり年月日が入力されている場合お届け可能日付範囲を取得する
        if ($('[name=comiket_detail_outbound_pref_cd_sel]').val()) {
            // お預かり可能日付範囲を取得
            getOutBoundCollectCal();
        }

        dispComiketDetailOutboundDeliveryTime();
    }));

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 搬出-住所と同じボタン
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    $('input[name="inbound_adrs_copy_btn"]').on('click', (function () {
        if($('input[name="comiket_div"]:checked').val() == G_DEV_BUSINESS) { // 法人
            $('input[name="comiket_detail_inbound_name"]').val($('input[name="office_name"]').val());
        } else if($('input[name="comiket_div"]:checked').val() == G_DEV_INDIVIDUAL) { // 個人
            $('input[name="comiket_detail_inbound_name"]').val($('input[name="comiket_personal_name_sei"]').val()  + " " +  $('input[name="comiket_personal_name_mei"]').val());
//            $('input[name="comiket_detail_inbound_name_mei"]').val($('input[name="comiket_personal_name_mei"').val());
        }
        $('input[name="comiket_detail_inbound_zip1"]').val($('input[name="comiket_zip1"]').val());
        $('input[name="comiket_detail_inbound_zip2"]').val($('input[name="comiket_zip2"]').val());
        $('select[name="comiket_detail_inbound_pref_cd_sel"]').val($('select[name="comiket_pref_cd_sel"]').val());
        $('input[name="comiket_detail_inbound_address"]').val($('input[name="comiket_address"]').val());
        $('input[name="comiket_detail_inbound_building"]').val($('input[name="comiket_building"]').val());
        $('input[name="comiket_detail_inbound_tel"]').val($('input[name="comiket_tel"]').val());

        // 配送先都道府県、預かり年月日が入力されている場合お届け可能日付範囲を取得する
        if ($('[name=comiket_detail_inbound_collect_date_year_sel]').val()
                && $('[name=comiket_detail_inbound_collect_date_month_sel]').val()
                && $('[name=comiket_detail_inbound_collect_date_day_sel]').val()
                && $('[name=comiket_detail_inbound_pref_cd_sel]').val()) {

            // お届け可能日付範囲を取得
            getInBoundUnloadingCal();
        }

        dispComiketDetailInboundDeliveryTime();
    }));

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 同意して次に進む（入力内容の確認）ボタン
//////////////////////////////////////////////////////////////////////////////////////////////////////////

    $('input[name="submit"]').on('click', function () {
        if (!multiSend.block()) {
            return false;
        }
        $('form').first().submit();
    });

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// お支払い方法
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    function dispPaymentMethod() {
        if($('input[name="comiket_div"]:checked').val() == G_DEV_BUSINESS) { // 法人
//            var comiketCustomerCdSel = $('input[name="comiket_customer_cd_sel"]:checked').val();
//            if(comiketCustomerCdSel == '1') { // 顧客コード使用する
                $('div#payment_method').hide(g_fifoVal);
//            } else { // 顧客コード使用しない
//                $('div#payment_method').show(g_fifoVal);
//            }
//console.log("############### 999");
            $('input[name="comiket_payment_method_cd_sel"]').each(function() {
//console.log("############### 999-1");
                var value = $(this).val();
//console.log("############### 999-2 : " + value);
                if(value == "5") { // ラジオボタン-法人
//console.log("############### 999-3");
                    $(this).attr('checked', 'checked');
                } else {
//console.log("############### 999-4");
                    $(this).attr("checked", false);
                }
            });
        } else if($('input[name="comiket_div"]:checked').val() == G_DEV_INDIVIDUAL) { // 個人
            $('div#payment_method').show(g_fifoVal);
            // pay_digital_money
            var comiketDetailTypeVal = $('input[name="comiket_detail_type_sel"]:checked').val();
//console.log(comiketDetailTypeVal);
            if(comiketDetailTypeVal == "1") { // 搬入
                $('label.pay_digital_money').hide(g_fifoVal);
                $('input#pay_digital_money').attr("checked", false);
                $('.pay_convenience_store').show(g_fifoVal);
                if ($('[name=comiket_convenience_store_cd_sel]:checked').val() == '1') { // コンビニ選択の場合
                    $('[name=comiket_convenience_store_cd_sel]').show(g_fifoVal);
                } else {
                    $('[name=comiket_convenience_store_cd_sel]').hide(g_fifoVal);
                }
                
            } else if(comiketDetailTypeVal == "2") { // 搬出
                $('label.pay_digital_money').show(g_fifoVal);
//                $('input#pay_digital_money').attr("checked", false);
                $('label.pay_convenience_store').hide(g_fifoVal);
                $('[name=comiket_convenience_store_cd_sel]').hide(g_fifoVal);
                $('input#pay_convenience_store').attr("checked", false);
            }

        } else { // 識別-未選択
            $('div#payment_method').hide(g_fifoVal);
            $('input[name="comiket_payment_method_cd_sel"]').attr("checked", false);
        }
    }

    // お支払い方法部分の表示制御
    dispPaymentMethod();
//////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////
//    if ($.fn && $.fn.autoKana) {
//        $.fn.autoKana('input[name="surname"]', 'input[name="surname_furigana"]', {
//            katakana: true
//        });
//        $.fn.autoKana('input[name="forename"]', 'input[name="forename_furigana"]', {
//            katakana: true
//        });
//    }
//
    function getFormData() {
        var $form = $('form').first(),
            data = $form.serializeArray();
        data.push({
            name: 'featureId',
            value: $form.data('featureId')
        }, {
            name: 'id',
            value: $form.data('id')
        });
//console.log(data);
        return data;
    }

//    $('.number-only').on('input', function(e) {
////console.log("AAAAAAAAA 2");
//        var k = e.keyCode;
//
//        // 半角変換
//        var halfVal = $(this).val().replace(/[！-～]/g,
//            function (tmpStr) {
//                // 文字コードをシフト
//                return String.fromCharCode(tmpStr.charCodeAt(0) - 0xFEE0);
//            }
//        );
//      // 数字以外の不要な文字を削除
//      $(this).val(halfVal.replace(/[^0-9]/g, ''));
////      return false;
//    });
//
//    $('.number-p-only').on('input', function(e) {
//        var k = e.keyCode;
//
//        // 半角変換
//        var halfVal = $(this).val().replace(/[！-～]/g,
//            function (tmpStr) {
//                // 文字コードをシフト
//                return String.fromCharCode(tmpStr.charCodeAt(0) - 0xFEE0);
//            }
//        );
//      // 数字,ハイフン以外の不要な文字を削除
//      $(this).val(halfVal.replace(/[^0-9-]/g, ''));
//    });

//    $('.number-only').on('keyup', function(e) {
//
//        var k = e.keyCode;
//console.log("################### 101");
//console.log(k);
//
//    // 半角変換
//    var halfVal = $(this).val().replace(/[！-～]/g,
//        function (tmpStr) {
//            // 文字コードをシフト
//            return String.fromCharCode(tmpStr.charCodeAt(0) - 0xFEE0);
//        }
//    );
//  // 数字以外の不要な文字を削除
//  $(this).val(halfVal.replace(/[^0-9]/g, ''));
//    });
//
//    function addOption($select, data) {
//        var option = '';
//        if (!data || (data.ids && data.ids.length !== 1)) {
//            option += '<option value="">選択してください</option>';
//        }
//        if (data) {
//            if (data.dates) {
//                $.each(data.ids, (function (key, value) {
//                    option += '<option data-date="' + data.dates[key] + '" value="' + value + '">' + data.names[key] + '</option>';
//                }));
//            } else {
//                $.each(data.ids, (function (key, value) {
//                    option += '<option value="' + value + '">' + data.names[key] + '</option>';
//                }));
//            }
//        }
//        $select.empty().append(option);
//        $('select[name="travel_departure_cd_sel"]').trigger('change');
//    }
//
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
//
//    var changeTerminal = function () {
//            var val = parseInt($('input[name="terminal_cd_sel"]').filter(':checked').filter(':enabled').val(), 10),
//                existDeparture,
//                existArrival;
//            if (_.isNaN(val)) {
//                val = 0;
//            }
//            existDeparture = ((val & 1) === 1);
//            existArrival = ((val & 2) === 2);
//            fadeToggle($('.departure'), existDeparture);
//            fadeToggle($('.arrival'), existArrival);
//            fadeToggle($('#quantity,#quantity_number'), existDeparture || existArrival);
//        },
//        disableTime = function ($select, data) {
//            $select.find('option').prop('disabled', data);
//            if (data) {
//                $select.val('00').find(':selected').prop('disabled', false);
//            }
//        },
//        ua = window.navigator.userAgent.toLowerCase(),
//        ver = window.navigator.appVersion.toLowerCase(),
//        isIE8 = ua.indexOf('msie') !== -1 && ver.indexOf('msie 8.') !== -1,
//        travelAddOption,
//        departureAddOption,
//        arrivalAddOption,
//        zips = {};
//
//    function disableTerminal() {
//        var departurOption = $('select[name="travel_departure_cd_sel"]').find('option'),
//            arrivalOption = $('select[name="travel_arrival_cd_sel"]').find('option'),
//            existDeparture = (departurOption.length > 1 || departurOption.first().val() !== ''),
//            existArrival = (arrivalOption.length > 1 || arrivalOption.first().val() !== ''),
//            enabledTerminal;
//        $('#terminal1').prop('disabled', !existDeparture);
//        $('#terminal2').prop('disabled', !existArrival);
//        $('#terminal3').prop('disabled', !existDeparture || !existArrival);
//        enabledTerminal = $('input[name="terminal_cd_sel"]').filter(':enabled');
//        if (enabledTerminal.length === 1) {
//            enabledTerminal.prop('checked', true).trigger('change');
//        } else {
//            changeTerminal();
//        }
//    }
//
//    disableTerminal();
//
//    function getDate(date) {
//        var week = ['日', '月', '火', '水', '木', '金', '土'];
//        return date.getFullYear() + '年' + (date.getMonth() + 1) + '月' + date.getDate() + '日（' + week[date.getDay()] + '）';
//    }
//
//    // lodash.jsのbindで関数の第一引数をあらかじめ割り当てておく
//    travelAddOption = _.bind(addOption, undefined, $('select[name="travel_cd_sel"]'));
//    departureAddOption = _.bind(addOption, undefined, $('select[name="travel_departure_cd_sel"]'));
//    arrivalAddOption = _.bind(addOption, undefined, $('select[name="travel_arrival_cd_sel"]'));
//
//    $('input').filter('[name="zip1"],[name="zip2"]').on('focusout keydown keyup', (function () {
//        var zip = $('input[name="zip1"]').val() + $('input[name="zip2"]').val(),
//            $select = $('select[name="cargo_collection_st_time_cd_sel"]'),
//            data = zips[zip];
//        if (zip.length !== 7) {
//            $select.find('option').prop('disabled', false);
//            return;
//        }
//        if (data !== undefined) {
//            disableTime($select, data);
//            return;
//        }
//        sgwns.api('/common/php/SearchTimeZoneFlag.php', getFormData(), (function (data) {
//            disableTime($select, data);
//            zips[zip] = data;
//        }));
//    })).first().trigger('focusout');
//
//    $('input[name="adrs_search_btn"]').on('click', (function () {
//        var $form = $('form').first();
//        AjaxZip2.zip2addr(
//            'input_forms',
//            'zip1',
//            'pref_cd_sel',
//            'address',
//            'zip2',
//            '',
//            '',
//            $form.data('featureId'),
//            $form.data('id'),
//            $('input[name="ticket"]').val()
//        );
//        $('input[name="address"]').removeAttr('style');
//        $('input').filter('[name="zip1"],[name="zip2"]').trigger('focusout');
//    }));
//
//    $('select[name="travel_agency_cd_sel"]').on('change', (function () {
//        sgwns.api('/common/php/SearchTravel.php', getFormData(), travelAddOption).done((function () {
//            $('select[name="travel_cd_sel"]').trigger('change');
//        }));
//    }));
//
//    $('select[name="travel_cd_sel"]').on('change', (function () {
//        sgwns.api('/common/php/SearchTravelTerminal.php', getFormData(), (function (data) {
//            departureAddOption(data.departure);
//            arrivalAddOption(data.arrival);
//        })).done(disableTerminal);
//    }));
//
//    $('input[name="terminal_cd_sel"]').on('change', changeTerminal).trigger('change');
//
//    $('select[name="travel_departure_cd_sel"]').on('change', (function () {
//        var $paragraph = $('#cargo_collection_date').find('p'),
//            str = $(this).find(':selected').first().data('date'),
//            $select = $('select[name="cargo_collection_date_year_cd_sel"]'),
//            begin,
//            end,
//            beginYear,
//            endYear,
//            data,
//            option,
//            val;
//        $paragraph.html('&nbsp;');
//        if (!str) {
//            return;
//        }
//        begin = new Date(str);
//        begin.setDate(begin.getDate() - 11);
//        end = new Date(str);
//        end.setDate(end.getDate() - 5);
//        $paragraph.text(getDate(begin) + 'から' + getDate(end) + 'まで選択できます。');
//        beginYear = begin.getFullYear();
//        endYear = end.getFullYear();
//        data = {};
//        data[beginYear] = beginYear;
//        data[endYear] = endYear;
//        option = '<option value="">年を選択</option>';
//        $.each(data, (function () {
//            option += '<option value="' + this + '">' + this + '</option>';
//        }));
//        val = $select.val();
//        $select.empty().append(option).val(val);
//    })).trigger('change');
//
    $('input[name="comiket_payment_method_cd_sel"]').on('change', (function () {
        var val = parseInt($(this).val(), 10);
        if (_.isNaN(val)) {
            val = 0;
        }
//        fadeToggle($('#convenience'), (val & 1) === 1);
        fadeToggle($('#convenience,[name=comiket_convenience_store_cd_sel]'), val == '1'); // コンビニの場合のみ
    })).filter(':checked').trigger('change');

    $('input[data-pattern]').filter('[data-pattern="^\\\\d+$"],[data-pattern="^\\\\w+$"],[data-pattern="^[!-~]+$"]').on('change', (function () {
        var $this = $(this);
        $this.val($this.val().replace(/[Ａ-Ｚａ-ｚ０-９]/g, (function (s) {
            return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
        })));
    }));

    if (!('placeholder' in document.createElement('input'))) {
        $('input[name="surname"]').on('change click focus focusout keydown keyup keypress', (function () {
            var $furigana = $('input[name="surname_furigana"]'),
                val = $furigana.val(),
                placeholder = $furigana.attr('placeholder');
            if ($.trim(val)) {
                if (val === placeholder) {
                    $furigana.css({
                        color: 'silver'
                    });
                } else {
                    $furigana.removeAttr('style');
                }
            } else {
                $furigana.val(placeholder).css({
                    color: 'silver'
                });
            }
        }));
//
//        $('input[name="forename"]').on('change click focus focusout keydown keyup keypress', (function () {
//            var $furigana = $('input[name="forename_furigana"]'),
//                val = $furigana.val(),
//                placeholder = $furigana.attr('placeholder');
//            if ($.trim(val)) {
//                if (val === placeholder) {
//                    $furigana.css({
//                        color: 'silver'
//                    });
//                } else {
//                    $furigana.removeAttr('style');
//                }
//            } else {
//                $furigana.val(placeholder).css({
//                    color: 'silver'
//                });
//            }
//        }));
//
//        $('input').filter('[placeholder]').ahPlaceholder({
//            placeholderAttr: 'placeholder'
//        });
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// お申込者->当日の担当者名, 電話番号->当日の担当者電話番号 などコピー
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    $('input.btn_cstm_info_copy').on('click', function() {
        if($('input[name="comiket_div"]:checked').val() == G_DEV_INDIVIDUAL) {
            var personalNameSei = $('input[name="comiket_personal_name_sei"]').val();
            var personalNameMei = $('input[name="comiket_personal_name_mei"]').val();
            var tel = $('input[name="comiket_tel"]').val();

            $('input[name="comiket_staff_sei"]').val(personalNameSei);
            $('input[name="comiket_staff_mei"]').val(personalNameMei);
            $('input[name="comiket_staff_tel"]').val(tel);
        }
    });
    if ($('input[name="comiket_div"]:checked').val() != G_DEV_INDIVIDUAL) { // 個人の場合
        $('input.btn_cstm_info_copy').hide(g_fifoVal);
    }

    //--------------------------------------------------------------------------
    // お届け日時 日付範囲制御処理
    //--------------------------------------------------------------------------
    // 配送先都道府県プルダウンリスト変更時
    $('[name=comiket_detail_inbound_pref_cd_sel]').on('change', function(e) {

        if (!$('[name=comiket_detail_inbound_collect_date_year_sel]').val()
                || !$('[name=comiket_detail_inbound_collect_date_month_sel]').val()
                || !$('[name=comiket_detail_inbound_collect_date_day_sel]').val()
                || !$('[name=comiket_detail_inbound_pref_cd_sel]').val()) {
            return false;
        }

        getInBoundUnloadingCal();
    });

    // お届け日時（年）プルダウンリスト変更時
    $('[name=comiket_detail_inbound_collect_date_year_sel]').on('change', function(e) {
        if (!$('[name=comiket_detail_inbound_collect_date_year_sel]').val()
                || !$('[name=comiket_detail_inbound_collect_date_month_sel]').val()
                || !$('[name=comiket_detail_inbound_collect_date_day_sel]').val()
                || !$('[name=comiket_detail_inbound_pref_cd_sel]').val()) {
            return false;
        }

        // お届け可能日付範囲を取得
        getInBoundUnloadingCal();
    });

    // お届け日時（月）プルダウンリスト変更時
    $('[name=comiket_detail_inbound_collect_date_month_sel]').on('change', function(e) {
        if (!$('[name=comiket_detail_inbound_collect_date_year_sel]').val()
                || !$('[name=comiket_detail_inbound_collect_date_month_sel]').val()
                || !$('[name=comiket_detail_inbound_collect_date_day_sel]').val()
                || !$('[name=comiket_detail_inbound_pref_cd_sel]').val()) {
            return false;
        }

        // お届け可能日付範囲を取得
        getInBoundUnloadingCal();
    });

    // お届け日時（日）プルダウンリスト変更時
    $('[name=comiket_detail_inbound_collect_date_day_sel]').on('change', function(e) {
        if (!$('[name=comiket_detail_inbound_collect_date_year_sel]').val()
                || !$('[name=comiket_detail_inbound_collect_date_month_sel]').val()
                || !$('[name=comiket_detail_inbound_collect_date_day_sel]').val()
                || !$('[name=comiket_detail_inbound_pref_cd_sel]').val()) {
            return false;
        }

        // お預かり可能日付範囲を取得
        getInBoundUnloadingCal();
    });

    /**
     * お届け可能日付範囲を取得
     * @returns {undefined}
     */
    function getInBoundUnloadingCal() {
        sgwns.api('/common/php/getInBoundUnloadingCal.php', getFormData(), (function (data) {
            var strFtDt = data.strFrDate;
            var strToDt = data.strToDate;
            var frDt = data.frDate;
            var toDt = data.toDate;
//            window.console.log(data);
            var changeMessage = strFtDt + 'から' + strToDt + 'まで選択できます。';
            $('.comiket-detail-inbound-delivery-date-fr-to').text(changeMessage);
            $('#hid_comiket-detail-inbound-delivery-date-from').val(frDt);
            $('#hid_comiket-detail-inbound-delivery-date-to').val(toDt);
        })).done(function(data) {
            fromToPlldateInit();
        });
    }

    // お届け可能日付範囲を取得
    // エラーでリダイレクトした時用（通常時は入力が不十分なので実行されないはず）
    if ($('[name=comiket_detail_inbound_pref_cd_sel]').val()) {

        var procCount = 0;
        // 初期表示時にtimer を使用しないとまだ日付コンボが作成できていない
        var timerId = setInterval(function () {
        if ($('[name=comiket_detail_inbound_collect_date_year_sel]').val()
                && $('[name=comiket_detail_inbound_collect_date_month_sel]').val()
                && $('[name=comiket_detail_inbound_collect_date_day_sel]').val()
                ) {
            // お届け可能日付範囲を取得
            getInBoundUnloadingCal();
            clearInterval(timerId);
            return;
        }
            procCount++;
            if (5 <= procCount) {
                clearInterval(timerId);
                return;
            }
        }, 150);
    }

    //--------------------------------------------------------------------------
    // お預かり日時 日付範囲制御処理
    //--------------------------------------------------------------------------

    // 集荷先都道府県プルダウンリスト変更時
    $('[name=comiket_detail_outbound_pref_cd_sel]').on('change', function(e) {

        if (!$('[name=comiket_detail_outbound_pref_cd_sel]').val()) {
            return false;
        }

        // お預かり可能日付範囲を取得
        getOutBoundCollectCal();
    });

    // お預かり日時（年）プルダウンリスト変更時
    $('[name=comiket_detail_outbound_collect_date_year_sel]').on('change', function(e) {

        if (!$('[name=comiket_detail_outbound_pref_cd_sel]').val()) {
            return false;
        }

        // お預かり可能日付範囲を取得
        getOutBoundCollectCal();
    });

    // お預かり日時（月）プルダウンリスト変更時
    $('[name=comiket_detail_outbound_collect_date_month_sel]').on('change', function(e) {

        if (!$('[name=comiket_detail_outbound_pref_cd_sel]').val()) {
            return false;
        }

        // お預かり可能日付範囲を取得
        getOutBoundCollectCal();
    });

    // お預かり日時（月）プルダウンリスト変更時
    $('[name=comiket_detail_outbound_collect_date_day_sel]').on('change', function(e) {

        if (!$('[name=comiket_detail_outbound_pref_cd_sel]').val()) {
            return false;
        }

        // お預かり可能日付範囲を取得
        getOutBoundCollectCal();

    });
    
    // お預かり日時（日）プルダウンリスト変更時
    // GiapLN comment for fix task #SMT6-227 2022.07.27
    $('[name=comiket_detail_outbound_delivery_date_year_sel]'
       + ',[name=comiket_detail_outbound_delivery_date_month_sel]'
       + ',[name=comiket_detail_outbound_delivery_date_day_sel]'
       + ',[name=comiket_detail_outbound_delivery_time_sel]'
       
            ).on('change', function(e) {
        
        if (!$('[name=comiket_detail_outbound_pref_cd_sel]').val()) {
            return false;
        }
        // お預かり可能日付範囲を取得
        getOutBoundCollectCal();
    });

    function setMinusDateByOutboundDeliverySelect() {
        var maxDeliveryDate = $('#hid_comiket-detail-outbound_delivery-date-to').val();
        var yearSelect = $('select[name="comiket_detail_outbound_delivery_date_year_sel"]').val();
        if (yearSelect === null || yearSelect === '') {
            if ($('select[name="comiket_detail_outbound_delivery_date_year_sel"]').attr('_selected') !== null && $('select[name="comiket_detail_outbound_delivery_date_year_sel"]').attr('_selected') !== '') {
                yearSelect = $('select[name="comiket_detail_outbound_delivery_date_year_sel"]').attr('_selected');
            }
        }
        
        var monthSelect = $('select[name="comiket_detail_outbound_delivery_date_month_sel"]').val();
        if (monthSelect === null || monthSelect === '') {
            if ($('select[name="comiket_detail_outbound_delivery_date_month_sel"]').attr('_selected') !== null && $('select[name="comiket_detail_outbound_delivery_date_month_sel"]').attr('_selected') !== '') {
                monthSelect = $('select[name="comiket_detail_outbound_delivery_date_month_sel"]').attr('_selected');
            }
        }
        
        var daySelect = $('select[name="comiket_detail_outbound_delivery_date_day_sel"]').val();
        if (daySelect === null || daySelect === '') {
            if ($('select[name="comiket_detail_outbound_delivery_date_day_sel"]').attr('_selected') !== null && $('select[name="comiket_detail_outbound_delivery_date_day_sel"]').attr('_selected') !== '') {
                daySelect = $('select[name="comiket_detail_outbound_delivery_date_day_sel"]').attr('_selected');
            }
        }
        
        console.log('########### maxDeliveryDate:'+maxDeliveryDate);
        console.log('########### daySelect:' +daySelect);
        if (yearSelect === '' || monthSelect === '' || daySelect === '' || maxDeliveryDate === '' || maxDeliveryDate === null) {
            $('#hid_minus_number_date').val(0);
            console.log('########### Set=0');
            return;
        }
        
        var maxDate = new Date(maxDeliveryDate);
        var dateSelect = new Date(yearSelect + "-" + monthSelect + "-" + daySelect);
        var diffirentTime = maxDate.getTime() - dateSelect.getTime();
        var minusNum = diffirentTime / (1000 * 3600 * 24);
        console.log('########### minusNum:' +minusNum);
        $('#hid_minus_number_date').val(minusNum);
    }

    /**
     * お預かり可能日付範囲を取得（搬入用）
     * @returns boolean true
     */
    function getOutBoundCollectCal() {
        //画面の引渡し日時の選ぶ値からイベントサブ.out_bound_loading_toとサブ日数を設定してから、getOutBoundCollectCalの計算に反映する。
        setMinusDateByOutboundDeliverySelect();
        sgwns.api('/common/php/getOutBoundCollectCal.php', getFormData(), (function (data) {
            var strFtDt = data.strFrDate;
            var strToDt = data.strToDate;
            var frDt = data.frDate;
            var toDt = data.toDate;
            var errorMsg = data.errorMsg;
            
            //共通機能が対応できない為、ベターで対応する(引渡し日が30日の場合、お預かり日が18～24、引渡し日が31日の場合、お預かり日が19～25)
            //共通機能が対応できない為、ベターで対応する(引渡し日が12日の場合、お預かり日が31～06、引渡し日が13日の場合、お預かり日が01～07)
            var deliveryDt = $('#comiket_detail_outbound_delivery_date_day_sel').val();
//            if (deliveryDt === '13' && frDt === '2023-07-31') {
//                frDt = "2023-08-01";
//                strFtDt = "2023年08月01日（火）";
//            }
            if (deliveryDt === '31' && frDt === '2023-12-18') {
                frDt = "2023-12-19";
                strFtDt = "2023年12月19日（火）";
            }
            var changeMessage = strFtDt + 'から' + strToDt + 'まで選択できます。';
            
            //hot fix イベントが終わったら、ソースを戻す
            
//            if (deliveryDt == '13') {
//                var msg1 = '近隣エリアかつ引渡し日13日の場合は<br/>' + strFtDt + 'から2022年08月9日（火）まで選択できます。';
//                var msg2 = '遠方エリアかつ引渡し日13日の場合は<br/>' + strFtDt + 'から2022年08月8日（月）まで選択できます。';
//                changeMessage = msg1 + '<br/><br/>' + msg2;
//            }
//            if (deliveryDt == '14') {
//                var msg1 = '近隣エリアかつ引渡し日14日の場合は<br/>' + strFtDt + 'から2022年08月10日（水）まで選択できます。';
//                var msg2 = '遠方エリアかつ引渡し日14日の場合は<br/>' + strFtDt + 'から2022年08月9日（火）まで選択できます。';
//                changeMessage = msg1 + '<br/><br/>' + msg2;
//            }   
            
            if (errorMsg && (errorMsg != undefined || errorMsg != '')) {
                $('.comiket-detail-outbound-collect-date-fr-to').text(errorMsg);
                $('#hid_comiket-detail-outbound-collect-date-from').val(frDt);
                $('#hid_comiket-detail-outbound-collect-date-to').val(toDt);
//                $('.comiket_detail_outbound_collect_date').hide(g_fifoVal);
                $('.comiket_detail_outbound_collect_date').show(g_fifoVal);
                $('.comiket-detail-outbound-collect-date-fr-to').css('float', '');
//                $('.comiket_detail_outbound_collect_time_sel').hide(g_fifoVal);
                dispComiketDetailOutboundDeliveryTime();
                $('select[name=comiket_detail_outbound_collect_time_sel]').val("00");
            } else {
                //$('.comiket-detail-outbound-collect-date-fr-to').text(changeMessage);
                $('.comiket-detail-outbound-collect-date-fr-to').html(changeMessage);
                $('#hid_comiket-detail-outbound-collect-date-from').val(frDt);
                $('#hid_comiket-detail-outbound-collect-date-to').val(toDt);
                $('.comiket_detail_outbound_collect_date').show(g_fifoVal);
                $('.comiket-detail-outbound-collect-date-fr-to').css('float', '');
                dispComiketDetailOutboundDeliveryTime();
            }
            return true;
        })).done(function(data) {
            fromToPlldateInit();
            if($('#hid_comiket-detail-outbound-collect-date-from').val() == '1900-01-01'
                    && $('#hid_comiket-detail-outbound-collect-date-to').val() == '1900-01-01') {
                $('.comiket_detail_outbound_collect_date_parts').hide(g_fifoVal);
            } else {
                $('.comiket_detail_outbound_collect_date_parts').show(g_fifoVal);
            }
            //fillDataDateDelivery();
        });
    }
    // お預かり可能日付範囲を取得
    // エラーでリダイレクトした時用（通常時は入力が不十分なので実行されないはず）
    if ($('[name=comiket_detail_outbound_pref_cd_sel]').val()) {
        // お預かり可能日付範囲を取得
        getOutBoundCollectCal();
    }

    // 受付時間超過文言表示制御
    function seigyoEveTimeOver() {
        var optionObj = $('[name=event_sel] option:selected');

        if (optionObj ==null) {
            return false;
        }

        var timeoverFlg = $(optionObj).attr('timeoverflg');
        var timeorverDate = $(optionObj).attr('timeoverdate');
        var eventNm = $(optionObj).text();
        if (timeoverFlg == '1') {
            var message = eventNm + 'の申込期間は' + timeorverDate + 'をもって終了しています。',
                message2 = 'お申込みはできませんので、ご了承ください。';
            $('#timeover').text(message);               // 文言を追加
            $('#timeover').append('<br />');
            $('#timeover').append(message2);               // 文言を追加

            $('#timeover').show(g_fifoVal);             // 文言を詰めた要素を表示
            $('#hid_timezone_flg').val(timeoverFlg);    // 隠し項目に選択したオプションのフラグをセット
        } else {
            $('#timeover').hide(g_fifoVal);
            $('#hid_timezone_flg').val(timeoverFlg);    // 隠し項目に選択したオプションのフラグをセット
        }
    }
    
    //--------------------------------------------------------------------------
    // 手荷物預かり
    //--------------------------------------------------------------------------
    // 配送受付サービス ラジオボタン
    $('.service_selected1').on('click', function() {
        $(".comiket_sel10").show(300);
        $(".class_building_name_sel").show();
        $(".class_comiket_booth_name").show();
        $(".input-azuke").hide(300);
        $(".tenimotsu-only").hide(300);
        $('.comiket_detail_type_sel').attr("checked", false);
        $('.class_comiket_booth_name,.class_building_name_sel,.class_comiket_staff_seimei,.class_comiket_staff_seimei_furi,.class_comiket_staff_tel').show(300);
    });
    
    // 手荷物預かりサービス ラジオボタン
    $('.service_selected2').on('click', function() {
        $(".comiket_sel10").hide(300);
        $(".input-outbound").hide(300);
        $(".input-inbound").hide(300);
        $(".input-azuke").show(300);
        $(".class_building_name_sel").hide();
        $(".class_comiket_booth_name").hide();
        $(".tenimotsu-only").show(300);
        $('.comiket_detail_type_sel').attr("checked", false);
        $('.class_comiket_booth_name,.class_building_name_sel,.class_comiket_staff_seimei,.class_comiket_staff_seimei_furi,.class_comiket_staff_tel').hide(300);
    });
    
    // 預り-１回のみ ラジオボタン
    $('.comiket_detail_azukari_kaisu_type_sel1').on('click', function() {
        $('.item51').hide(300);
        $('.toridashi_outbount').hide(300);
        $('.otodokesaki_simei').html('氏名');
        $('.otodokesaki_tel').html('TEL');
        $('.comiket_detail_outbound_delivery_date5').hide(300);
        $('#comiket_detail_azukari_toriatsukai_type_sel199').prop('checked',true);
    });
    
    // 預り-複数回 ラジオボタン
    $('.comiket_detail_azukari_kaisu_type_sel2').on('click', function() {
        $('.item51').show(300);
        $('.toridashi_outbount').hide(300);
        $('.otodokesaki_simei').html('氏名');
        $('.otodokesaki_tel').html('TEL');
        $('#comiket_detail_azukari_toriatsukai_type_sel199').prop('checked',true);
    });
    //--------------------------------------------------------------------------

    //--------------------------------------------------------------------------
    // 地区・ホール・ブロックなど
    //--------------------------------------------------------------------------

    $('.building_cd_and_name_sel').on('change', function() {
        getBuildingBoothPositionApi(true);
    });
    getBuildingBoothPositionApi(false);
    function getBuildingBoothPositionApi(isChangeFlg) {
        sgwns.api('/evp/api/building_booth_position.php', getFormData(), (function (data) {
            // var boothPositionList = data.boothPositionList;
            var selectedVal = $('select[name=building_booth_position_sel]').val();
            $("select[name=building_booth_position_sel] option").each(function () {
                // 最初の「選択してください」は削除しない
                if($(this).val() == '') {
                    return true;
                }
                $(this).remove();
            });

            for(var i = 0; i < data.boothPositionList.length; i++) {
                $("select[name=building_booth_position_sel]").append($("<option>")
                    .val(data.boothPositionList[i].id).text(data.boothPositionList[i].booth_position));
            }
            if (!isChangeFlg) {
                $('select[name=building_booth_position_sel]').val(selectedVal);
            }
            
            return true;
           
        })).done(function(data) {
            fromToPlldateInit();
        });
    }

    //--------------------------------------------------------------------------
    
    getEventsubData();
    dispOutboundServiceItem();
    dispInboundServiceItem();
    dispOutboundColAndDlyDateTimeByDb();
    dispInboundColAndDlyDateTimeByDb();

    // エラーでリダイレクトした時用
    seigyoEveTimeOver();


    var serviceSel = $('input[name="comiket_detail_service_selected"]:checked').val();
    if(serviceSel == "2"){
       $(".comiket_sel10").hide(300); 
    }

//FPT 2022/07/13 Fix error Javascript
//    $(window).load(function(){
//        // 初期処理の一番遅いタイミングで再度実行する（本処理前に、getOutBoundCollectCal実行しているが、タイミングが早すぎると思われるため、再実行）
//        getOutBoundCollectCal();
//    });

//$('select[name=comiket_detail_outbound_delivery_date_day_sel]').trigger('change');
    
}(jQuery));

/* GiapLN add 2022/04/07 */
function backRedirectEvent(eventNm, userType, baseUrl) {
    let subUrl = "";
    if (userType === '0') {
        //back robot check.
        subUrl = "/event/robotCheck?event_nm=";
    } else {
        if (userType === '1') {
            //back input history
            subUrl = "/event/inputHistory?event_nm=";
        }
    }
    window.location.href = baseUrl + subUrl + eventNm;
}
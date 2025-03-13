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

        // 法人のみ文言表示・非表示制御
        dispAttentionCompanuOnly();

        // 搬入搬出-サービス内容設定
        selectComiketDetailOutboundService();

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
            $("input[name=comiket_detail_type_sel]").attr("checked", false);
            $("input[name=comiket_div]").attr("checked", false);
        }

        changeCustomerInputItem(); // 顧客入力部分の表示制御
        // 往復選択の表示制御
        dispComiketDetailType();
        selectComiketDetailType();

        selectComiketDetailOutboundService();

        // お支払い方法部分の表示制御
        dispPaymentMethod();

        // 注意文言の表示・非表示
        dispAttentionMessage();

        // 貼付票・説明書リンク表示/非表示 制御
        dispDocLinkEachEventsub();

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
//                $('.paste_tag_link').attr("href", "/dsn/pdf/paste_tag/paste_tag_" + eventsubSel + ".pdf");
//                $('.manual_link').attr("href", "/dsn/pdf/manual/manual_" + eventsubSel + ".pdf");

                $('.manual').show(g_fifoVal);
                eventSelText = eventSelText.replace(/\s+/g, "");
                $('.manual_link').attr("href", "/dsn/pdf/manual/" + eventSelText +  ".pdf" + '?' + strCurrentDate);
            } else {
                $('.manual').hide(g_fifoVal);
            }
            if(g_eventsubList[eventsubSel]['is_paste_display']){
                $('.paste_tag').show(g_fifoVal);
                $('.paste_tag_link').attr("href", "/dsn/pdf/paste_tag/paste_tag_" + eventsubSel + ".pdf" + '?' + strCurrentDate);
            } else {
                $('.paste_tag').hide(g_fifoVal);
            }
        } else {
            $('.eventsub_dl_link').hide(g_fifoVal);
        }
    }
    // 貼付票・説明書リンク表示/非表示 制御
    dispDocLinkEachEventsub();
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
//        $(".comiket-detail-outbound-collect-date-from").html(eventOutboundCollectFr);
//        $(".comiket-detail-outbound-collect-date-to").html(eventOutboundCollectTo);
//        $(".comiket-detail-outbound-delivery-date-from").html(eventOutboundDeliveryFr);
//        $(".comiket-detail-outbound-delivery-date-to").html(eventOutboundDeliveryTo);
//
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
        if (eventSel == '2') {
            $('[name=building_name_sel]').val('');
            $('[name=building_name_sel]').hide(g_fifoVal);

            $('[name=comiket_booth_num]').attr('placeholder', '例）1234(数値4桁)');
        } else {
            $('[name=building_name_sel]').show(g_fifoVal);
            $('[name=comiket_booth_num]').attr('placeholder', '例）1234');
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
    }
//    dispComiketDetailType();
    /**
     *
     * @returns {undefined}
     */
    $('input[name="comiket_detail_type_sel"]').on('change', function() {
        selectComiketDetailType();
        selectComiketDetailOutboundService();

        getBoxData();

        dispPaymentMethod();

        // 注意文言の表示・非表示
        dispAttentionMessage();

        // 搬入-お預かり・お届け日表示制御
        dispOutboundColAndDelyDate();

        // 搬入-時間帯指定不可地域に元ずく制御
        dispComiketDetailOutboundDeliveryTime();
    });

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 搬入-サービス選択 部分制御
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    function dispOutboundServiceItem() {
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
            if(g_eventsubList[eventsubSel]['kojin_box_col_flg'] == '1') {
//console.log("###### 888-2");
                $('label[for="comiket_detail_outbound_service_sel1"]').show(g_fifoVal);
                isBox = true;
                dispCount++;
            } else {
//console.log("###### 888-3");
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

        if(isBox && !isCargo && !isCharter) {
            $('input#comiket_detail_outbound_service_sel1').attr("checked", 'checked'); // 宅配便選択
            $('input#comiket_detail_outbound_service_sel1').prop("checked", 'checked'); // 宅配便選択
            $("dl.comiket_detail_outbound_service_sel").hide(); // サービス項目毎非表示
            checkOutboundService = '1';
        }

        if(!isBox && isCargo && !isCharter) {
            $('input#comiket_detail_outbound_service_sel2').attr("checked", 'checked'); // カーゴ選択
            $('input#comiket_detail_outbound_service_sel2').prop("checked", 'checked'); // カーゴ選択
            $("dl.comiket_detail_outbound_service_sel").hide(); // サービス項目毎非表示
            checkOutboundService = '2';
        }

        if(!isBox && !isCargo && isCharter) {
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
//            var radioVal = $('input[name="comiket_detail_outbound_service_sel"]:checked').val();
            var radioVal = checkOutboundService;

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
            var radioVal = $('input[name="comiket_detail_outbound_service_sel"]:checked').val(); // サービス選択

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
    });



//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 宅配数量
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    function getBoxData() {
        
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
//        $('dl.office-name').hide(g_fifoVal);
//        $('span.office_name-lbl').hide(g_fifoVal);
//        $('input[name="office_name"]').hide(g_fifoVal);
////console.log("###################### 201");
//
//        $('dl.comiket-personal-name-seimei').show(g_fifoVal);
//        $('span.comiket_personal_name_sei-lbl').hide(g_fifoVal);
//        $('input[name="comiket_personal_name_sei"]').show(g_fifoVal);
//        $('span.comiket_personal_name_mei-lbl').hide(g_fifoVal);
//        $('input[name="comiket_personal_name_mei"]').show(g_fifoVal);
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
//console.log("######### 555-1");
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
        );
//        $('input[name="comiket_address"]').removeAttr('style');
        $('input').filter('[name="comiket_detail_outbound_zip1"],[name="comiket_detail_outbound_zip2"]').trigger('focusout');
    }));


//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 搬出-住所と同じボタン
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    $('input[name="inbound_adrs_copy_btn"]').on('click', (function () {
        $('input[name="comiket_detail_name"]').val($('input[name="comiket_staff_sei_furi"]').val()  + " " +  $('input[name="comiket_staff_mei_furi"]').val());
        
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
       
        $('div#payment_method').show(g_fifoVal);
        // pay_digital_money
        var comiketDetailTypeVal = $('input[name="comiket_detail_type_sel"]:checked').val();
        if(comiketDetailTypeVal == "1") { // 搬入
            $('label.pay_digital_money').hide(g_fifoVal);
            $('input#pay_digital_money').attr("checked", false);
        } else if(comiketDetailTypeVal == "2") { // 搬出
            $('label.pay_digital_money').show(g_fifoVal);
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

    $('input[name="comiket_payment_method_cd_sel"]').on('change', (function () {
        var val = parseInt($(this).val(), 10);
        if (_.isNaN(val)) {
            val = 0;
        }
//        fadeToggle($('#convenience'), (val & 1) === 1);
        fadeToggle($('#convenience'), val == '1'); // コンビニの場合のみ
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

    $('input#comiket_detail_inbound_service_sel1').attr("checked", 'checked'); // 宅配便選択
    $('input#comiket_detail_inbound_service_sel1').prop("checked", 'checked'); // 宅配便選択
    // 日付・時間帯、表示/非表示
    getEventsubData();
    changeCustomerInputItem();

    // エラーでリダイレクトした時用
    seigyoEveTimeOver();
    
}(jQuery));



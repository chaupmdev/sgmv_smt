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



        // お支払い方法部分の表示制御
        dispPaymentMethod();

        getBoxData();

        // 識別で変化する入力エリアクリア
//        var comiketDiv = $('input[name="comiket_div"]:checked').val();
//        if(!comiketDiv || comiketDiv == "") {
            clearInputByDivChange();
//        }

        // 注意文言の表示・非表示
        dispAttentionMessage();

        // 貼付票・説明書リンク表示/非表示 制御
        dispDocLinkEachEventsub();


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
        $('.example_boxsize').show(g_fifoVal);
        $('.convenience_store_laterpay_attention').show(g_fifoVal);
        var detailType = $('input[name="comiket_detail_type_sel"]:checked').val();
        if(detailType == "1") { // 搬入
            $('.pay_digital_money_attention').hide(g_fifoVal);
        } else {  // 搬出
            $('.pay_digital_money_attention').show(g_fifoVal);
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

        // お支払い方法部分の表示制御
        dispPaymentMethod();

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

                // バシ　削除　とりあえず
                //$('label.comiket_detail_type_sel-label3').hide(g_fifoVal); // 搬入と搬出 選択肢の非表示
                //$('input#comiket_detail_type_sel3').attr("checked", false);
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
    $('input[name="comiket_detail_azukari_kaisu_type_sel"]').on('change', function() {
        selectComiketDetailType();

        //getBoxData();

        dispPaymentMethod();

        // 注意文言の表示・非表示
        dispAttentionMessage();
    });

  

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 手荷物数量
// TODO 預かり回数タイプが複数の場合は対応する。
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    // function getBoxData() {
    //     sgwns.api('/common/php/SearchBox.php', getFormData(), (function(data) {

    //         $('.comiket-box-num').children().remove();

    //         var htmlCode = "";
    //         htmlCode += "<table><tbody><tr>";
    //         htmlCode += "<td><table style='width: min-width;'><tbody>";
    //         var boxName = "";
    //         for(var i = 0; i < data.azukari.length; i++) {
    //             boxName = data.azukari[i].name_display;
    //             if(data.azukari[i].name_display == null || data.azukari[i].name_display == ""){
    //                 boxName =  data.azukari[i].name;
    //             }

    //             htmlCode += "<tr class = 'box_"+data.azukari[i].size+"'><td class='comiket_box_item_name'>" + boxName + "&nbsp;</td><td class='comiket_box_item_value'><input autocapitalize='off' class='number-only comiket_box_item_value_input' maxlength='2' inputmode='numeric' name='comiket_box_num_ary[" + data.azukaribox[i].id  + "]' data-pattern='^\d+$' placeholder='例）1' type='text' value='' />個</td></tr>";
    //         }

    //         htmlCode += "</tbody></table></td>";
    //         htmlCode += "<td class='dispSeigyoPCTr' style='vertical-align: middle;text-align: right;width: 47%;'><img class='dispSeigyoPC' src='/azk/images/about_boxsize.png' width='100%'></td>";
    //         htmlCode += " </tr></tbody></table>";

    //         if( $('.dispSeigyoSP').length === 0) {
    //             htmlCode += "<div class='dispSeigyoSP'><img src='/azk/images/about_boxsize.png' width='250px' style='margin-top: 1em;' /></div>";
    //         }

    //         $('.comiket-box-num').append(htmlCode);
    //     }));
    // }

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

           $('input[name="comiket_customer_cd"],input[name="customer_search_btn"]').show(g_fifoVal);
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



//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 住所検索
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    $('input[name="adrs_search_btn"]').on('click', (function () {
        var $form = $('form').first();
        $(this).prop("disabled", true);

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
            $('input[name="ticket"]').val(),
            'adrs_search_btn'
        );

        $(this).prop("disabled", false);
        $('input').filter('[name="comiket_zip1"],[name="comiket_zip2"]').trigger('focusout');
    }));


//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 手荷物預かりサービス -住所検索
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    $('input[name="comiket_detail_adrs_search_btn"]').on('click', (function () {
        var $form = $('form').first();
        AjaxZip2.zip2addr(
            'input_forms',
            'comiket_detail_zip1',
            'comiket_detail_pref_cd_sel',
            'comiket_detail_address',
            'comiket_detail_zip2',
            '',
            '',
            $form.data('featureId'),
            $form.data('id'),
            $('input[name="ticket"]').val()
        ).done(function () {
        });
        $('input').filter('[name="comiket_detail_zip1"],[name="comiket_detail_zip2"]').trigger('focusout');
    }));



//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 搬入-お預かり・お届け日(時)表示制御
//////////////////////////////////////////////////////////////////////////////////////////////////////////

    function isDispOutboundColAndDlyDateTimeByDb(check) {
        var comiketDiv = $('input[name=comiket_div]:checked').val();
        var eventsubSel = $('select[name="eventsub_sel"]').val();
//        var detailType = $('input[name="comiket_detail_type_sel"]:checked').val();
        var serviceSel = $('input[name="comiket_detail_outbound_service_sel"]:checked').val();

       
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
       
        return false;
    }





//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 搬出-時間帯表示制御
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    function dispComiketDetailInboundDeliveryTime() {
        var formDataList = getFormData();
//        for(var i = 0; i < formDataList.length; i++) {
//            formDataList[0]['name'] ==
//        }
        var zip1 = $('input[name=comiket_detail_inbound_zip1]').val();
        var zip2 = $('input[name=comiket_detail_inbound_zip2]').val();

//        var comiketDivVal = $('input[name="comiket_div"]:checked').val();
//        var inboundServiceSel = $('input[name="comiket_detail_inbound_service_sel"]:checked').val();
//        if(comiketDivVal == G_DEV_BUSINESS && (inboundServiceSel == '1' || inboundServiceSel == '2')) {
//            dispComiketDetailInboundDeliveryTimeByDivAndType();
//            return;
//        }

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



//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 手荷物預かりサービス - お申込者と同じボタン
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    $('input[name="azuke_adrs_copy_btn"]').on('click', (function () {
        // $('input[name="comiket_detail_name"]').val($('input[name="comiket_personal_name_sei"]').val()  + " " +  $('input[name="comiket_personal_name_mei"]').val());
        $('input[name="comiket_detail_name"]').val($('input[name="comiket_staff_sei_furi"]').val()  + " " +  $('input[name="comiket_staff_mei_furi"]').val());
        // $('input[name="comiket_detail_zip1"]').val($('input[name="comiket_zip1"]').val());
        // $('input[name="comiket_detail_zip2"]').val($('input[name="comiket_zip2"]').val());
        // $('select[name="comiket_detail_pref_cd_sel"]').val($('select[name="comiket_pref_cd_sel"]').val());
        // $('input[name="comiket_detail_address"]').val($('input[name="comiket_address"]').val());
        // $('input[name="comiket_detail_building"]').val($('input[name="comiket_building"]').val());
        // $('input[name="comiket_detail_tel"]').val($('input[name="comiket_tel"]').val());
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
            } else if(comiketDetailTypeVal == "2") { // 搬出
                $('label.pay_digital_money').show(g_fifoVal);
//                $('input#pay_digital_money').attr("checked", false);
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
        } else {

        }

        // お預かり可能日付範囲を取得
        getOutBoundCollectCal();
    });

    // お預かり日時（日）プルダウンリスト変更時
    $('[name=comiket_detail_outbound_collect_date_day_sel]'
       + ',[name=comiket_detail_outbound_delivery_date_year_sel]'
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

    /**
     * お預かり可能日付範囲を取得（搬入用）
     * @returns boolean true
     */
    function getOutBoundCollectCal() {
        sgwns.api('/common/php/getOutBoundCollectCal.php', getFormData(), (function (data) {
            var strFtDt = data.strFrDate;
            var strToDt = data.strToDate;
            var frDt = data.frDate;
            var toDt = data.toDate;
            var errorMsg = data.errorMsg;
            var changeMessage = strFtDt + 'から' + strToDt + 'まで選択できます。';
            if (errorMsg && (errorMsg != undefined || errorMsg != '')) {
                $('.comiket-detail-outbound-collect-date-fr-to').text(errorMsg);
                $('#hid_comiket-detail-outbound-collect-date-from').val(frDt);
                $('#hid_comiket-detail-outbound-collect-date-to').val(toDt);

                $('.comiket_detail_outbound_collect_date_parts').hide(g_fifoVal);
                $('.comiket_detail_outbound_collect_date').hide(g_fifoVal);
                $('.comiket_detail_outbound_collect_time_sel').hide(g_fifoVal);
                
                //  $('.comiket_detail_outbound_collect_date').show(g_fifoVal);
                $('.comiket-detail-outbound-collect-date-fr-to').css('float', '');
                $('select[name=comiket_detail_outbound_collect_time_sel]').val("00");
            } else {
                $('.comiket-detail-outbound-collect-date-fr-to').text(changeMessage);
                $('#hid_comiket-detail-outbound-collect-date-from').val(frDt);
                $('#hid_comiket-detail-outbound-collect-date-to').val(toDt);
                $('.comiket_detail_outbound_collect_date').show(g_fifoVal);
                $('.comiket_detail_outbound_collect_time_sel').show(g_fifoVal);
                $('.comiket-detail-outbound-collect-date-fr-to').css('float', '');
            }
            
            return true;
        })).done(function(data) {
            fromToPlldateInit();
            if($('#hid_comiket-detail-outbound-collect-date-from').val() == '1900-01-01'
                    && $('#hid_comiket-detail-outbound-collect-date-to').val() == '1900-01-01') {
                $('.comiket_detail_outbound_collect_date_parts').hide(g_fifoVal);
            } else {
                $('.comiket_detail_outbound_collect_date_parts').show(g_fifoVal);
                $('.comiket_detail_outbound_collect_date').show(g_fifoVal);
            }
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
    getEventsubData();
    
    
    // エラーでリダイレクトした時用
    seigyoEveTimeOver();

    $(".plus").on('click', function(){
        addOrMinusSuuryo($(this).attr("data-id"), "1");
    });

    $(".minus").on('click', function(){
        addOrMinusSuuryo($(this).attr("data-id"), "2");
    });

    function addOrMinusSuuryo(dataId, opt){
        var suuryoVal = parseInt($(".suuryo_"+dataId).val());
        if(opt == "1"){
            suuryoVal = suuryoVal+1;
        }else{
            suuryoVal = suuryoVal-1;
        }
        
        if(isNaN(suuryoVal) && opt == "2"){
            suuryoVal = 0;
        }else if(isNaN(suuryoVal)){
            suuryoVal = 1;
        }else if(suuryoVal < 0){
            suuryoVal = 0;
        }else if(isNaN(suuryoVal)){
            suuryoVal = 0;
        }else if(suuryoVal >= 99){
            suuryoVal = 99;
        }

        $(".suuryo_"+dataId).val(suuryoVal);
    }

}(jQuery));
<?php
/**
 * @package    ClassDefFile
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
Sgmov_Lib::useServices(array('Building', 'InBoundUnloadingCal'));
/**#@-*/

 /**
 * イベントデータコンバート情報を扱います。
 *
 * @package Service
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_EventsubCmb {

    /**
     * 館マスタサービス(ブース番号)
     * @var type
     */
    private $_BuildingService;

    private $_InBoundUnloadingCalService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_BuildingService = new Sgmov_Service_Building();
//        $this->_TravelService = new Sgmov_Service_Travel();

        $this->_InBoundUnloadingCalService = new Sgmov_Service_InBoundUnloadingCal();
    }



    /**
     * 搬入・搬出の開催チェックを行い、開催期間を返す
     */
    public function cmbEventsubList($eventsubList, $selectedId = "", $inboundHatsuJis2 = "", $inboundChakuJis2 = "") {

        // DB接続
        $db = Sgmov_Component_DB::getPublic();

        $week = array("日", "月", "火", "水", "木", "金", "土");
        $eventsubAry3 = array();
        $selectedData = array();
        foreach($eventsubList as $key => $val) {

            $termFrNm = $val["term_fr"];
            $termFrNmWeek = $week[date('w', strtotime($val["term_fr"]))];
            $termToNm = $val["term_to"];
            $termToNmWeek = $week[date('w', strtotime($val["term_to"]))];

            ////////////////////////////////////////////////////////////////////////////////////////
            // 搬入
            ////////////////////////////////////////////////////////////////////////////////////////
            $todayOnePlus = date('Y-m-d', strtotime("1 day"));
            $outboundCollectFr = date('Y-m-d', strtotime($val["out_bound_unloading_fr"]));
            if($outboundCollectFr < $todayOnePlus) {
                $val["out_bound_unloading_fr"] = $outboundCollectFr = $todayOnePlus;
            }
            $outboundCollectFrYear = date('Y', strtotime($val["out_bound_unloading_fr"]));
            $outboundCollectFrMonth = date('m', strtotime($val["out_bound_unloading_fr"]));
            $outboundCollectFrDay = date('d', strtotime($val["out_bound_unloading_fr"]));
            $outboundCollectFrWeek = $week[date('w', strtotime($outboundCollectFr))];

            $outboundCollectTo = date('Y-m-d', strtotime($val["out_bound_unloading_to"]));
            $outboundCollectToYear = date('Y', strtotime($val["out_bound_unloading_to"]));
            $outboundCollectToMonth = date('m', strtotime($val["out_bound_unloading_to"]));
            $outboundCollectToDay = date('d', strtotime($val["out_bound_unloading_to"]));
            $outboundCollectToWeek = $week[date('w', strtotime($outboundCollectTo))];

//            $today = date('Y-m-d');
            $outboundDeliveryFr = date('Y-m-d', strtotime($val["out_bound_loading_fr"]));
//            if()
            $outboundDeliveryFrYear = date('Y', strtotime($val["out_bound_loading_fr"]));
            $outboundDeliveryFrMonth = date('m', strtotime($val["out_bound_loading_fr"]));
            $outboundDeliveryFrDay = date('d', strtotime($val["out_bound_loading_fr"]));
            $outboundDeliveryFrWeek = $week[date('w', strtotime($outboundDeliveryFr))];

            $outboundDeliveryTo = date('Y-m-d', strtotime($val["out_bound_loading_to"]));
            $outboundDeliveryToYear = date('Y', strtotime($val["out_bound_loading_to"]));
            $outboundDeliveryToMonth = date('m', strtotime($val["out_bound_loading_to"]));
            $outboundDeliveryToDay = date('d', strtotime($val["out_bound_loading_to"]));
            $outboundDeliveryToWeek = $week[date('w', strtotime($outboundDeliveryTo))];

            ///////////////////////////////////////////////////////////////////////////////////////////

            $val["outbound_collect_fr_year"]  = $outboundCollectFrYear;
            $val["outbound_collect_fr_month"]  = $outboundCollectFrMonth;
            $val["outbound_collect_fr_day"]  = $outboundCollectFrDay;

            $val["outbound_collect_to_year"]  = $outboundCollectToYear;
            $val["outbound_collect_to_month"]  = $outboundCollectToMonth;
            $val["outbound_collect_to_day"]  = $outboundCollectToDay;

            $val["outbound_delivery_fr_year"] = $outboundDeliveryFrYear;
            $val["outbound_delivery_fr_month"] = $outboundDeliveryFrMonth;
            $val["outbound_delivery_fr_day"] = $outboundDeliveryFrDay;

            $val["outbound_delivery_to_year"] = $outboundDeliveryToYear;
            $val["outbound_delivery_to_month"] = $outboundDeliveryToMonth;
            $val["outbound_delivery_to_day"] = $outboundDeliveryToDay;


            ////////////////////////////////////////////////////////////////////////////////////////
            // 搬出
            ////////////////////////////////////////////////////////////////////////////////////////
            $today = date('Y-m-d');
            $inboundCollectFr = date('Y-m-d', strtotime($val["in_bound_loading_fr"]));
            // アクセス日が預かり開始日を過ぎている場合はアクセス日に丸める
            if($inboundCollectFr < $today) {
                $val["in_bound_loading_fr"] = $inboundCollectFr = $today;
            }

            // 預かり開始日
            $inboundCollectFrYear = date('Y', strtotime($val["in_bound_loading_fr"]));
            $inboundCollectFrMonth = date('m', strtotime($val["in_bound_loading_fr"]));
            $inboundCollectFrDay = date('d', strtotime($val["in_bound_loading_fr"]));
            $inboundCollectFrWeek = $week[date('w', strtotime($inboundCollectFr))];

            // 預かり終了日
            $inboundCollectTo = date('Y-m-d', strtotime($val["in_bound_loading_to"]));
            $inboundCollectToYear = date('Y', strtotime($val["in_bound_loading_to"]));
            $inboundCollectToMonth = date('m', strtotime($val["in_bound_loading_to"]));
            $inboundCollectToDay = date('d', strtotime($val["in_bound_loading_to"]));
            $inboundCollectToWeek = $week[date('w', strtotime($inboundCollectTo))];

//            if(!empty($selectedId) && !empty($inboundHatsuJis2) && !empty($inboundChakuJis2)) {
//                $inBoundUnloadingCalInfo = $this->_InBoundUnloadingCalService->fetchInBoundUnloadingCalByHaChaku($db, $selectedId, $inboundHatsuJis2, $inboundChakuJis2);
//                $val["in_bound_unloading_fr"] = date('Y-m-d', strtotime("+{$inBoundUnloadingCalInfo['plus_period']} day", $val["in_bound_unloading_fr"]));
//                $val["in_bound_unloading_to"] = date('Y-m-d', strtotime("+{$inBoundUnloadingCalInfo['deli_period']} day", $val["in_bound_unloading_fr"]));

            // お届け開始
                $inboundDeliveryFr = date('Y-m-d', strtotime($val["in_bound_unloading_fr"]));
                $inboundDeliveryFrYear = date('Y', strtotime($val["in_bound_unloading_fr"]));
                $inboundDeliveryFrMonth = date('m', strtotime($val["in_bound_unloading_fr"]));
                $inboundDeliveryFrDay = date('d', strtotime($val["in_bound_unloading_fr"]));
                $inboundDeliveryFrWeek = $week[date('w', strtotime($inboundDeliveryFr))];

            // お届け終了
                $inboundDeliveryTo = date('Y-m-d', strtotime($val["in_bound_unloading_to"]));
                $inboundDeliveryToYear = date('Y', strtotime($val["in_bound_unloading_to"]));
                $inboundDeliveryToMonth = date('m', strtotime($val["in_bound_unloading_to"]));
                $inboundDeliveryToDay = date('d', strtotime($val["in_bound_unloading_to"]));
                $inboundDeliveryToWeek = $week[date('w', strtotime($inboundDeliveryTo))];

                $val["inbound_delivery_fr_year"]  = $inboundDeliveryFrYear;
                $val["inbound_delivery_fr_month"]  = $inboundDeliveryFrMonth;
                $val["inbound_delivery_fr_day"]  = $inboundDeliveryFrDay;

                $val["inbound_delivery_to_year"]  = $inboundDeliveryToYear;
                $val["inbound_delivery_to_month"]  = $inboundDeliveryToMonth;
                $val["inbound_delivery_to_day"]  = $inboundDeliveryToDay;

                $val["inbound_delivery_fr"] = date('Y年m月d日', strtotime($inboundDeliveryFr)) . "（" . $inboundDeliveryFrWeek . "）";
                $val["inbound_delivery_to"] = date('Y年m月d日', strtotime($inboundDeliveryTo)) . "（" . $inboundDeliveryToWeek . "）";
                $val["inbound_delivery_fr_dt"] = date('Y-m-d', strtotime($inboundDeliveryFr));
                $val["inbound_delivery_to_dt"] = date('Y-m-d', strtotime($inboundDeliveryTo));

                // お届け日のfrom-toが同じ日付に設定されている場合
                $val["is_eq_inbound_delivery"] = FALSE;
                if(@!empty($inboundDeliveryFr) && @!empty($inboundDeliveryTo)
                        && $inboundDeliveryFr == $inboundDeliveryTo) {
                    $val["is_eq_inbound_delivery"] = TRUE;
                }
//            } else {
//                $val["in_bound_unloading_fr"] = "";
//                $val["in_bound_unloading_to"] = "";
//
//                $val["inbound_delivery_fr_year"]  = "";
//                $val["inbound_delivery_fr_month"]  = "";
//                $val["inbound_delivery_fr_day"]  = "";
//
//                $val["inbound_delivery_to_year"]  = "";
//                $val["inbound_delivery_to_month"]  = "";
//                $val["inbound_delivery_to_day"]  = "";
//
//                $val["inbound_delivery_fr"] = "";
//                $val["inbound_delivery_to"] = "";
//
//                $val["is_eq_inbound_delivery"] = FALSE;
//            }

            ///////////////////////////////////////////////////////////////////////////////////////////

            // 搬出預かり
            $val["inbound_collect_fr_year"]   = $inboundCollectFrYear;
            $val["inbound_collect_fr_month"]   = $inboundCollectFrMonth;
            $val["inbound_collect_fr_day"]   = $inboundCollectFrDay;

            $val["inbound_collect_to_year"]   = $inboundCollectToYear;
            $val["inbound_collect_to_month"]   = $inboundCollectToMonth;
            $val["inbound_collect_to_day"]   = $inboundCollectToDay;


            ///////////////////////////////////////////////////////////////////////////////////////////

            // イベント開催期間
            $val["term_fr_nm"] = date('Y年m月d日', strtotime($termFrNm)) . "（" . $termFrNmWeek . "）";
            $val["term_to_nm"] = date('Y年m月d日', strtotime($termToNm)) . "（" . $termToNmWeek . "）";

            // 搬入預かり
            $val["outbound_collect_fr"] = date('Y年m月d日', strtotime($outboundCollectFr)) . "（" . $outboundCollectFrWeek . "）";
            $val["outbound_collect_to"] = date('Y年m月d日', strtotime($outboundCollectTo)) . "（" . $outboundCollectToWeek . "）";
            $val["outbound_collect_fr_dt"] = date('Y-m-d', strtotime($outboundCollectFr));
            $val["outbound_collect_to_dt"] = date('Y-m-d', strtotime($outboundCollectTo));
            // 搬入お届け
            $val["outbound_delivery_fr"] = date('Y年m月d日', strtotime($outboundDeliveryFr)) . "（" . $outboundDeliveryFrWeek . "）";
            $val["outbound_delivery_to"] = date('Y年m月d日', strtotime($outboundDeliveryTo)) . "（" . $outboundDeliveryToWeek . "）";
            $val["outbound_delivery_fr_dt"] = date('Y-m-d', strtotime($outboundDeliveryFr));
            $val["outbound_delivery_to_dt"] = date('Y-m-d', strtotime($outboundDeliveryTo));

            // 搬出預かり
            $val["inbound_collect_fr"] = date('Y年m月d日', strtotime($inboundCollectFr)) . "（" . $inboundCollectFrWeek . "）";
            $val["inbound_collect_to"] = date('Y年m月d日', strtotime($inboundCollectTo)) . "（" . $inboundCollectToWeek . "）";
            $val["inbound_collect_fr_dt"] = date('Y-m-d', strtotime($inboundCollectFr));
            $val["inbound_collect_to_dt"] = date('Y-m-d', strtotime($inboundCollectTo));


            $today = strtotime(date('Y-m-d'));
            // 搬入期間
            $departureFr = strtotime($val["departure_fr"]);
            $departureTo = strtotime($val["departure_to"]);

            // アクセス日が搬入期間内の場合はTRUE
            // TODO：使用していないっぽい
            if($departureFr <= $today && $today <= $departureTo) {
                $val["is_departure_date_range"] = TRUE;
            } else {
                $val["is_departure_date_range"] = FALSE;
            }

            // 搬出期間
            $arrivalFr = strtotime($val["arrival_fr"]);
            $todayTime = strtotime(date('Y-m-d H:i:s'));
            $arrivalToTime = strtotime($val["arrival_to_time"]);

            // アクセス日が搬出期間内の場合はTRUE
            // TODO：使用していないっぽい
            if($arrivalFr <= $today && $todayTime <= $arrivalToTime) {
                $val["is_arrival_date_range"] = TRUE;
            } else {
                $val["is_arrival_date_range"] = FALSE;
            }

            ////////////////////////////////////////////////////
            // お預かり・お届け開始日と終了日が同じかどうか
            ////////////////////////////////////////////////////

            // 搬入・預かり
            $val["is_eq_outbound_collect"] = FALSE;
            if(@!empty($outboundCollectFr) && @!empty($outboundCollectTo)
                    && $outboundCollectFr == $outboundCollectTo) {
                $val["is_eq_outbound_collect"] = TRUE;
            }

            // 搬入・お届け
            $val["is_eq_outbound_delivery"] = FALSE;
            if(@!empty($outboundDeliveryFr) && @!empty($outboundDeliveryTo)
                    && $outboundDeliveryFr == $outboundDeliveryTo) {
                $val["is_eq_outbound_delivery"] = TRUE;
            }

            // 搬出・預かり
            $val["is_eq_inbound_collect"] = FALSE;
            // 開始(開始日をすぎている場合は当日)、終了が一致した場合
            // 最終日以外はtrueにならない
            if(@!empty($inboundCollectFr) && @!empty($inboundCollectTo)
                   &&  $inboundCollectFr == $inboundCollectTo) {
                $val["is_eq_inbound_collect"] = TRUE;
            }

            // 説明書表示有無
            $val["is_manual_display"] = FALSE;
            if($val['manual_display'] == '1') {
                $val["is_manual_display"] = TRUE;
            }

            // 貼付票表示有無
            $val["is_paste_display"] = FALSE;
            if($val['paste_display'] == '1') {
                $val["is_paste_display"] = TRUE;
            }

            // 館名表示有無
            $val["is_building_display"] = FALSE;
            if($val['building_display'] == '1') {
                $val["is_building_display"] = TRUE;
            }

            // ブース名表示有無
            $val["is_booth_display"] = FALSE;
            if($val['booth_display'] == '1') {
                $val["is_booth_display"] = TRUE;
            }

            // 個人・宅配・往路集荷・日付指定
            $this->setEventsubFlg('kojin_box_col_date_flg', $val);

            // 個人・宅配・復路配達・日付指定
            $this->setEventsubFlg('kojin_box_dlv_date_flg', $val);

            // 個人・宅配・復路配達・時間指定
            $this->setEventsubFlg('kojin_box_dlv_time_flg', $val);

            // 個人・カーゴ・往路集荷・日付指定
            $this->setEventsubFlg('kojin_cag_col_date_flg', $val);

            // 個人・カーゴ・往路集荷・時間指定
            $this->setEventsubFlg('kojin_cag_col_time_flg', $val);

            // 個人・カーゴ・復路配達・日付指定
            $this->setEventsubFlg('kojin_cag_dlv_date_flg', $val);

            // 個人・カーゴ・復路配達・時間指定
            $this->setEventsubFlg('kojin_cag_dlv_time_flg', $val);

            // 法人・宅配・往路集荷・日付指定
            $this->setEventsubFlg('hojin_box_col_date_flg', $val);

            // 法人・宅配・往路集荷・時間指定
            $this->setEventsubFlg('hojin_box_col_time_flg', $val);

            // 法人・宅配・復路配達・日付指定
            $this->setEventsubFlg('hojin_box_dlv_date_flg', $val);

            // 法人・宅配・復路配達・時間指定
            $this->setEventsubFlg('hojin_box_dlv_time_flg', $val);

            // 法人・カーゴ・往路集荷・日付指定
            $this->setEventsubFlg('hojin_cag_col_date_flg', $val);

            // 法人・カーゴ・往路集荷・時間指定
            $this->setEventsubFlg('hojin_cag_col_time_flg', $val);

            // 法人・カーゴ・復路配達・日付指定
            $this->setEventsubFlg('hojin_cag_dlv_date_flg', $val);

            // 法人・カーゴ・復路配達・時間指定
            $this->setEventsubFlg('hojin_cag_dlv_time_flg', $val);

            // 法人・貸切・往路集荷・日付指定
            $this->setEventsubFlg('hojin_kas_col_date_flg', $val);

            // 法人・貸切・往路集荷・時間指定
            $this->setEventsubFlg('hojin_kas_col_time_flg', $val);

            // 法人・貸切・復路配達・日付指定
            $this->setEventsubFlg('hojin_kas_dlv_date_flg', $val);

            // 法人・貸切・復路配達・時間指定
            $this->setEventsubFlg('hojin_kas_dlv_time_flg', $val);


//            // 個人・宅配・往路
//            $this->setEventsubFlg('kojin_box_col_flg', $val);
//
//            // 個人・宅配・復路
//            $this->setEventsubFlg('kojin_box_dlv_flg', $val);
//
//            // 個人・カーゴ・往路
//            $this->setEventsubFlg('kojin_cag_col_flg', $val);
//
//            // 個人・カーゴ・復路
//            $this->setEventsubFlg('kojin_cag_dlv_flg', $val);
//
//            // 法人・宅配・往路
//            $this->setEventsubFlg('hojin_box_col_flg', $val);
//
//            // 法人・宅配・復路
//            $this->setEventsubFlg('hojin_box_dlv_flg', $val);
//
//            // 法人・カーゴ・往路
//            $this->setEventsubFlg('hojin_cag_col_flg', $val);
//
//            // 法人・カーゴ・復路
//            $this->setEventsubFlg('hojin_cag_dlv_flg', $val);
//
//            // 法人・貸切・往路
//            $this->setEventsubFlg('hojin_kas_col_flg', $val);
//
//            // 法人・貸切・復路
//            $this->setEventsubFlg('hojin_kas_dlv_flg', $val);


            $val["is_booth_position"] = TRUE;
            $checkBooth = "";
            $buildingAry = $this->_BuildingService->fetchBuildingNameByEventsubId($db, $val["id"]);
            foreach($buildingAry['list'] as $key2 => $val2) {
                $boothPosAry = $this->_BuildingService->fetchBuildingBoothPostionByBuildingCd($db, $val2["cd"], $val["id"]);
                foreach($boothPosAry['list'] as $key3 => $val3) {
                    $checkBooth .= $val3['booth_position'];
                    if(!empty($checkBooth)) {
                        break 2;
                    }
                }
            }
//            $buildingList = $buildingAry['list'];
//Sgmov_Component_Log::debug("####################### 1001 :" . $val["id"]);
//Sgmov_Component_Log::debug($buildingList);

            if(empty($checkBooth)) {
                $val["is_booth_position"] = FALSE;
            }

            $eventsubAry3[] = $val;

            if(!empty($selectedId) && $selectedId == $val["id"]) {
                $selectedData= $val;
            }
        }

        return array(
            "list" => $eventsubAry3,
            "selectedData" =>$selectedData,
        );
    }

    /**
     *
     * @param type $flgName
     * @param type $val
     */
    private function setEventsubFlg($flgName, &$val) {
        $val["is_{$flgName}"] = FALSE;
        if($val[$flgName] == '1') {
            $val["is_{$flgName}"] = TRUE;
        }
    }
}
<?php
/**
 * @package    ClassDefFile
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__).'/../../Lib.php';
Sgmov_Lib::useView('htl/Common');
Sgmov_Lib::useForms(array('Error', 'EveSession', 'Eve003Out'));
/**#@-*/

/**
 * イベント手荷物受付サービスのお申し込み確認画面を表示します。
 * @package    View
 * @subpackage EVE
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Eve_Confirm extends Sgmov_View_Eve_Common {
    
    /**
     * 共通サービス
     * @var Sgmov_Service_AppCommon
     */
    private $_appCommon;

    /**
     * 都道府県サービス
     * @var Sgmov_Service_Prefecture
     */
    private $_PrefectureService;
    
    /**
     * イベントサービス
     * @var Sgmov_Service_Event
     */    
    private $_EventService;
    
    /**
     * 宅配サービス
     * @var type 
     */
    private $_BoxService;
    
    /**
     * カーゴサービス
     * @var type 
     */
    private $_CargoService;
    
    /**
     * 館マスタサービス(ブース番号)
     * @var type 
     */
    private $_BuildingService;
    
    
//
//    /**
//     * 共通サービス
//     * @var Sgmov_Service_AppCommon
//     */
//    public $_appCommon;
//
//    /**
//     * クルーズリピータサービス
//     * @var Sgmov_Service_CruiseRepeater
//     */
//    public $_CruiseRepeater;
//
//    /**
//     * 都道府県サービス
//     * @var Sgmov_Service_Prefecture
//     */
//    public $_PrefectureService;
//
//    /**
//     * ツアー会社サービス
//     * @var Sgmov_Service_TravelAgency
//     */
//    private $_TravelAgencyService;
//
//    /**
//     * ツアーサービス
//     * @var Sgmov_Service_Travel
//     */
//    private $_TravelService;
//
//    /**
//     * ツアー発着地サービス
//     * @var Sgmov_Service_TravelTerminal
//     */
//    private $_TravelTerminalService;
//
//    /**
//     * ツアー配送料金エリアサービス
//     * @var Sgmov_Service_TravelDeliveryChargeAreas
//     */
//    private $_TravelDeliveryChargeAreasService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_appCommon             = new Sgmov_Service_AppCommon();
        $this->_PrefectureService     = new Sgmov_Service_Prefecture();
        $this->_EventService          = new Sgmov_Service_Event();
        $this->_BoxService            = new Sgmov_Service_Box();
        $this->_CargoService          = new Sgmov_Service_Cargo();
        $this->_BuildingService       = new Sgmov_Service_Building();
        $this->_CharterService        = new Sgmov_Service_Charter();
        
        parent::__construct();
//        $this->_appCommon                        = new Sgmov_Service_AppCommon();
//        $this->_CruiseRepeater                   = new Sgmov_Service_CruiseRepeater();
//        $this->_PrefectureService                = new Sgmov_Service_Prefecture();
//        $this->_TravelAgencyService              = new Sgmov_Service_TravelAgency();
//        $this->_TravelService                    = new Sgmov_Service_Travel();
//        $this->_TravelTerminalService            = new Sgmov_Service_TravelTerminal();
//        $this->_TravelDeliveryChargeAreasService = new Sgmov_Service_TravelDeliveryChargeAreas();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * セッションの継続を確認
     * </li><li>
     * セッションに入力チェック済みの情報があるかどうかを確認
     * </li><li>
     * セッション情報を元に出力情報を設定
     * </li><li>
     * チケット発行
     * </li></ol>
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['ticket']:チケット文字列
     * </li><li>
     * ['outForm']:出力フォーム
     * </li></ul>
     */
    public function executeInner() {

        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();

        // DB接続
        $db = Sgmov_Component_DB::getPublic();

        // セッションに入力チェック済みの情報があるかどうかを確認
        $sessionForm = $session->loadForm(self::FEATURE_ID);

        if (!isset($sessionForm) || $sessionForm->status != self::VALIDATION_SUCCEEDED) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
        }

        // セッション情報を元に出力情報を設定
        $resultData = $this->_createOutFormByInForm($sessionForm->in, $db);
        $outForm = $resultData["outForm"];
        $dispItemInfo = $resultData["dispItemInfo"];
        $inForm = (array)$sessionForm->in;
        if(@!empty($inForm['comiket_id'])) { // 編集画面の場合
//            $dispItemInfo['comiket_id'] = $inForm['comiket_id'];
            $dispItemInfo['back_input_path'] = "input2";
        } else {
            $dispItemInfo['back_input_path'] = "input";
        }

        // チケットを発行
        $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_EVE003);

        return array(
            'ticket'  => $ticket,
            'outForm' => $outForm,
            'dispItemInfo' => $dispItemInfo,
        );
    }
    /**
     * 入力フォームの値を元に出力フォームを生成します。
     * @param Sgmov_Form_Eve001In $inForm 入力フォーム
     * @return Sgmov_Form_Eve003Out 出力フォーム
     */
    public function _createOutFormByInForm($inForm, $db) {
        
        return $this->createOutFormByInForm($inForm, new Sgmov_Form_Eve003Out());

//        $outForm = new Sgmov_Form_Eve003Out();
//
//        // TODO オブジェクトから値を直接取得できるよう修正する
//        // オブジェクトから取得できないため、配列に型変換
//        $inForm = (array)$inForm;
//        
//        $db = Sgmov_Component_DB::getPublic();
//        
//        $eventAll = $this->_EventService->fetchEventListWithinTerm2($db);
//        $eventAll2 = array();
//        $eventIds = array();
//        $eventNames = array();
//        
//        $week = array("日", "月", "火", "水", "木", "金", "土");
//        foreach($eventAll as $key => $val) {
//            $eventIds[] = $val["id"];
//            $eventNames[] = $val["name"];
//            $outboundCollectFr = date('Y-m-d', strtotime('-11 day', strtotime($val["term_fr"])));
//            $outboundCollectFrWeek = $week[date('w', strtotime($outboundCollectFr))];
//            $outboundCollectTo = date('Y-m-d', strtotime('-5 day', strtotime($val["term_to"])));
//            $outboundCollectToWeek = $week[date('w', strtotime($outboundCollectTo))];
//            $outboundDeliveryFr = date('Y-m-d', strtotime($val["term_fr"]));
//            $outboundDeliveryFrWeek = $week[date('w', strtotime($outboundDeliveryFr))];
//            $outboundDeliveryTo = date('Y-m-d', strtotime($val["term_to"]));
//            $outboundDeliveryToWeek = $week[date('w', strtotime($outboundDeliveryTo))];
//            
//            $inboundCollectFr = date('Y-m-d', strtotime($val["term_fr"]));
//            $inboundCollectFrWeek = $week[date('w', strtotime($inboundCollectFr))];
//            $inboundCollectTo = date('Y-m-d', strtotime($val["term_to"]));
//            $inboundCollectToWeek = $week[date('w', strtotime($inboundCollectTo))];
//            $inboundDeliveryFr = date('Y-m-d', strtotime('+5 day', strtotime($val["term_fr"])));
//            $inboundDeliveryFrWeek = $week[date('w', strtotime($inboundDeliveryFr))];
//            $inboundDeliveryTo = date('Y-m-d', strtotime('+10 day', strtotime($val["term_to"])));
//            $inboundDeliveryToWeek = $week[date('w', strtotime($inboundDeliveryTo))];
//            
//            $val["outbound_collect_fr"] = date('Y年m月d日', strtotime($outboundCollectFr)) . "（" . $outboundCollectFrWeek . "）";
//            $val["outbound_collect_to"] = date('Y年m月d日', strtotime($outboundCollectTo)) . "（" . $outboundCollectToWeek . "）";
//            $val["outbound_delivery_fr"] = date('Y年m月d日', strtotime($outboundDeliveryFr)) . "（" . $outboundDeliveryFrWeek . "）";
//            $val["outbound_delivery_to"] = date('Y年m月d日', strtotime($outboundDeliveryTo)) . "（" . $outboundDeliveryToWeek . "）";
//            
//            $val["inbound_collect_fr"] = date('Y年m月d日', strtotime($inboundCollectFr)) . "（" . $inboundCollectFrWeek . "）";
//            $val["inbound_collect_to"] = date('Y年m月d日', strtotime($inboundCollectTo)) . "（" . $inboundCollectToWeek . "）";
//            $val["inbound_delivery_fr"] = date('Y年m月d日', strtotime($inboundDeliveryFr)) . "（" . $inboundDeliveryFrWeek . "）";
//            $val["inbound_delivery_to"] = date('Y年m月d日', strtotime($inboundDeliveryTo)) . "（" . $inboundDeliveryToWeek . "）";
//            $eventAll2[] = $val;
//        }
//
////Sgmov_Component_Log::debug("##################### youbi");
////Sgmov_Component_Log::debug($eventAll2);
////Sgmov_Component_Log::debug($_GET);
//        $dispItemInfo = array();
//        $dispItemInfo["event_alllist"] = $eventAll2;
//        $outForm->raw_event_cds  = $eventIds; 
//        $outForm->raw_event_lbls = $eventNames;
//        $outForm->raw_event_cd_sel = $inForm["event_sel"];
////        $arrayNum = array_search($inForm["event_sel"], $eventIds);
////        $outForm->raw_event_cd_sel_nm = $eventNames[$arrayNum];
//        
//        $outForm->raw_event_place = $inForm["event_place"];
//        
//        $outForm->raw_event_term_fr = date('Y年m月d日（' . $week[date('w', strtotime($inForm["event_term_fr"]))] . '）', strtotime($inForm["event_term_fr"]));
//        $outForm->raw_event_term_to = date('Y年m月d日（' . $week[date('w', strtotime($inForm["event_term_to"]))] . '）', strtotime($inForm["event_term_to"]));
//        
////Sgmov_Component_Log::debug("##################### 20");
////Sgmov_Component_Log::debug($inForm);
////Sgmov_Component_Log::debug($outForm);
////Sgmov_Component_Log::debug($eventNames);
//        
//        $outForm->raw_comiket_div = $inForm["comiket_div"];
//        $dispItemInfo["comiket_div_lbls"] = $this->comiket_div_lbls;
//        
//        // 顧客コード
//        $outForm->raw_comiket_customer_cd = $inForm["comiket_customer_cd"];
//        
//        // 顧客名
//        $outForm->raw_comiket_name = $inForm["comiket_name"];
//        
//        // 郵便番号1
//        $outForm->raw_comiket_zip1 = $inForm["comiket_zip1"];
//        
//        // 郵便番号2
//        $outForm->raw_comiket_zip2 = $inForm["comiket_zip2"];
//        
//        // 都道府県名
//        $outForm->raw_comiket_pref_nm = "";
//        if(@!empty($inForm["comiket_pref_cd_sel"])) {
//            $prefInfo = $this->_PrefectureService->fetchPrefecturesById($db, $inForm["comiket_pref_cd_sel"]);
//            $outForm->raw_comiket_pref_nm = $prefInfo["name"];
//        }
//        $prefectureAry = $this->_PrefectureService->fetchPrefectures($db);
//        array_shift($prefectureAry["ids"]);
//        array_shift($prefectureAry["names"]);
//        $outForm->raw_comiket_pref_cds  = $prefectureAry["ids"]; 
//        $outForm->raw_comiket_pref_lbls = $prefectureAry["names"]; 
//        $outForm->raw_comiket_pref_cd_sel = $inForm["comiket_pref_cd_sel"];
//        
//        // 市区町村
//        $outForm->raw_comiket_address = $inForm["comiket_address"];
//        
//        // 番地・建物名
//        $outForm->raw_comiket_building = $inForm["comiket_building"];
//        
//        // 電話番号
//        $outForm->raw_comiket_tel = $inForm["comiket_tel"];;
//        
//        // メールアドレス
//        $outForm->raw_comiket_mail = $inForm["comiket_mail"];
//        
//        // メールアドレス確認
//        $outForm->raw_comiket_mail_retype = $inForm["comiket_mail_retype"];
//        
//        // ブース名
//        $outForm->raw_comiket_booth_name = $inForm["comiket_booth_name"];
//        
//        // ブース番号
//        $buildingList = $this->_BuildingService->fetchBuildingByEventId($db, $inForm["event_sel"]);
//        $outForm->raw_building_booth_ids = $buildingList["ids"];
//        $outForm->raw_building_booth_lbls = $buildingList["names"];
//        $outForm->raw_building_booth_id_sel = $inForm["building_booth_id_sel"];
//        
////        $arrayNumBuilding = array_search($inForm["building_booth_id_sel"],  $buildingList["ids"]);
////        $outForm->raw_building_booth_id_sel_nm = $buildingList["names"][$arrayNumBuilding];
//        
//        // ブース番号-テキスト
//        $outForm->raw_comiket_booth_num = $inForm["comiket_booth_num"];
//        
//        // 搬入選択
//        $outForm->raw_comiket_detail_type_sel = $inForm["comiket_detail_type_sel"];
//        $dispItemInfo["comiket_detail_type_lbls"] = $this->comiket_detail_type_lbls;
//        
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//// 搬入・搬出共通
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//        // 搬入、搬出-サービス選択
//        $dispItemInfo["comiket_detail_service_lbls"] = $this->comiket_detail_service_lbls;
//        
//        // 搬入、搬出-各種サービス
//        $dispItemInfo["box_lbls"] = $this->_BoxService->fetchBox($db);
//        $dispItemInfo["cargo_lbls"] = $this->_CargoService->fetchCargo($db);
//        $dispItemInfo["charter_lbls"] = $this->_CharterService->fetchCharter($db);
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//// 搬入
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//        
//        // 搬入-集荷先名
//        $outForm->raw_comiket_detail_outbound_name = $inForm["comiket_detail_outbound_name"];
//        
//        // 搬入-集荷先郵便番号1
//        $outForm->raw_comiket_detail_outbound_zip1 = $inForm["comiket_detail_outbound_zip1"];
//        
//        // 搬入-集荷先郵便番号2
//        $outForm->raw_comiket_detail_outbound_zip2 = $inForm["comiket_detail_outbound_zip2"];
//        
//        // 搬入-集荷先都道府県
//        $outForm->raw_comiket_detail_outbound_pref_cds  = $prefectureAry["ids"];
//        $outForm->raw_comiket_detail_outbound_pref_lbls = $prefectureAry["names"];
//        $outForm->raw_comiket_detail_outbound_pref_cd_sel = $inForm["comiket_detail_outbound_pref_cd_sel"];
//        
////        $arrayNumOutboundPref = array_search($inForm["comiket_detail_outbound_pref_cd_sel"],  $prefectureAry["ids"]);
////        $outForm->raw_comiket_detail_outbound_pref_cd_sel_num = $prefectureAry["names"][$arrayNumOutboundPref];
//        
//        
//        // 搬入-集荷先市区町村
//        $outForm->raw_comiket_detail_outbound_address =  $inForm["comiket_detail_outbound_address"];
//        
//        // 搬入-集荷先番地・建物名
//        $outForm->raw_comiket_detail_outbound_building =  $inForm["comiket_detail_outbound_building"];
//        
//        // 搬入-集荷先TEL
//        $outForm->raw_comiket_detail_outbound_tel =  $inForm["comiket_detail_outbound_tel"];
//        
//        $date = new DateTime();
//        $years  = $this->_appCommon->getYears($date->format('Y'), 1, false);
//        $months = $this->_appCommon->months;
//        $days   = $this->_appCommon->days;
//        array_shift($months);
//        array_shift($days);
//        
//        // 搬入-お預かり日時
//        $outForm->raw_comiket_detail_outbound_collect_date_year_sel = $inForm["comiket_detail_outbound_collect_date_year_sel"];
//        $outForm->raw_comiket_detail_outbound_collect_date_year_cds = $years;
//        $outForm->raw_comiket_detail_outbound_collect_date_year_lbls = $years;
//        $outForm->raw_comiket_detail_outbound_collect_date_month_sel = $inForm["comiket_detail_outbound_collect_date_month_sel"];
//        $outForm->raw_comiket_detail_outbound_collect_date_month_cds = $months;
//        $outForm->raw_comiket_detail_outbound_collect_date_month_lbls = $months;
//        $outForm->raw_comiket_detail_outbound_collect_date_day_sel = $inForm["comiket_detail_outbound_collect_date_day_sel"];
//        $outForm->raw_comiket_detail_outbound_collect_date_day_cds = $days;
//        $outForm->raw_comiket_detail_outbound_collect_date_day_lbls = $days;
//        // 搬入-お預かり日時-時間帯
//        $comiket_detail_time_lbls = $this->comiket_detail_time_lbls;
//        $outForm->raw_comiket_detail_outbound_collect_time_sel = $inForm["comiket_detail_outbound_collect_time_sel"];
//        $outForm->raw_comiket_detail_outbound_collect_time_cds = array_keys($comiket_detail_time_lbls);
//        $outForm->raw_comiket_detail_outbound_collect_time_lbls = array_values($comiket_detail_time_lbls);
//        
//        // 搬入-お届け日時
//        $outForm->raw_comiket_detail_outbound_delivery_date_year_sel = $inForm["comiket_detail_outbound_delivery_date_year_sel"];
//        $outForm->raw_comiket_detail_outbound_delivery_date_year_cds = $years;
//        $outForm->raw_comiket_detail_outbound_delivery_date_year_lbls = $years;
//        $outForm->raw_comiket_detail_outbound_delivery_date_month_sel = $inForm["comiket_detail_outbound_delivery_date_month_sel"];
//        $outForm->raw_comiket_detail_outbound_delivery_date_month_cds = $months;
//        $outForm->raw_comiket_detail_outbound_delivery_date_month_lbls = $months;
//        $outForm->raw_comiket_detail_outbound_delivery_date_day_sel = $inForm["comiket_detail_outbound_delivery_date_day_sel"];
//        $outForm->raw_comiket_detail_outbound_delivery_date_day_cds = $days;
//        $outForm->raw_comiket_detail_outbound_delivery_date_day_lbls = $days;
//        // 搬入-お届け日時-時間帯
//        $outForm->raw_comiket_detail_outbound_delivery_time_sel = $inForm["comiket_detail_outbound_delivery_time_sel"];
//        $outForm->raw_comiket_detail_outbound_delivery_time_cds = array_keys($comiket_detail_time_lbls);
//        $outForm->raw_comiket_detail_outbound_delivery_time_lbls = array_values($comiket_detail_time_lbls);
//        
//        // 搬入-サービス選択
//        $outForm->raw_comiket_detail_outbound_service_sel = $inForm["comiket_detail_outbound_service_sel"];
//        
//        // 搬入-宅配
//        $outForm->raw_comiket_box_outbound_num_ary = $inForm["comiket_box_outbound_num_ary"];
//        
//        // 搬入-カーゴ
//        $outForm->raw_comiket_cargo_outbound_num_ary = $inForm["comiket_cargo_outbound_num_ary"];
//        
//        // 搬入-チャーター
//        $outForm->raw_comiket_charter_outbound_num_ary = $inForm["comiket_charter_outbound_num_ary"];
//        
//        // 搬入-備考
//        $outForm->raw_comiket_detail_outbound_note = $inForm["comiket_detail_outbound_note"];
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//// 搬出
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//        
//        // 搬出-集荷先名
//        $outForm->raw_comiket_detail_inbound_name = $inForm["comiket_detail_inbound_name"];
//        
//        // 搬出-集荷先郵便番号1
//        $outForm->raw_comiket_detail_inbound_zip1 = $inForm["comiket_detail_inbound_zip1"];
//        
//        // 搬出-集荷先郵便番号2
//        $outForm->raw_comiket_detail_inbound_zip2 = $inForm["comiket_detail_inbound_zip2"];
//        
//        // 搬出-集荷先都道府県
//        $outForm->raw_comiket_detail_inbound_pref_cds  = $prefectureAry["ids"];
//        $outForm->raw_comiket_detail_inbound_pref_lbls = $prefectureAry["names"];
//        $outForm->raw_comiket_detail_inbound_pref_cd_sel = $inForm["comiket_detail_inbound_pref_cd_sel"];
////Sgmov_Component_Log::debug("##################### 700");
////Sgmov_Component_Log::debug($outForm);
//
//        
//        // 搬出-集荷先市区町村
//        $outForm->raw_comiket_detail_inbound_address =  $inForm["comiket_detail_inbound_address"];
//        
//        // 搬出-集荷先番地・建物名
//        $outForm->raw_comiket_detail_inbound_building =  $inForm["comiket_detail_inbound_building"];
//        
//        // 搬出-集荷先TEL
//        $outForm->raw_comiket_detail_inbound_tel =  $inForm["comiket_detail_inbound_tel"];
//        
//        // 搬出-お預かり日時
//        $outForm->raw_comiket_detail_inbound_collect_date_year_sel = $inForm["comiket_detail_inbound_collect_date_year_sel"];
//        $outForm->raw_comiket_detail_inbound_collect_date_year_cds = $years;
//        $outForm->raw_comiket_detail_inbound_collect_date_year_lbls = $years;
//        $outForm->raw_comiket_detail_inbound_collect_date_month_sel = $inForm["comiket_detail_inbound_collect_date_month_sel"];
//        $outForm->raw_comiket_detail_inbound_collect_date_month_cds = $months;
//        $outForm->raw_comiket_detail_inbound_collect_date_month_lbls = $months;
//        $outForm->raw_comiket_detail_inbound_collect_date_day_sel = $inForm["comiket_detail_inbound_collect_date_day_sel"];
//        $outForm->raw_comiket_detail_inbound_collect_date_day_cds = $days;
//        $outForm->raw_comiket_detail_inbound_collect_date_day_lbls = $days;
//        // 搬出-お預かり日時-時間帯
//        $outForm->raw_comiket_detail_inbound_collect_time_sel = $inForm["comiket_detail_inbound_collect_time_sel"];
//        $outForm->raw_comiket_detail_inbound_collect_time_cds = array_keys($comiket_detail_time_lbls);
//        $outForm->raw_comiket_detail_inbound_collect_time_lbls = array_values($comiket_detail_time_lbls);
//        
//        // 搬出-お届け日時
//        $outForm->raw_comiket_detail_inbound_delivery_date_year_sel = $inForm["comiket_detail_inbound_delivery_date_year_sel"];
//        $outForm->raw_comiket_detail_inbound_delivery_date_year_cds = $years;
//        $outForm->raw_comiket_detail_inbound_delivery_date_year_lbls = $years;
//        $outForm->raw_comiket_detail_inbound_delivery_date_month_sel = $inForm["comiket_detail_inbound_delivery_date_month_sel"];
//        $outForm->raw_comiket_detail_inbound_delivery_date_month_cds = $months;
//        $outForm->raw_comiket_detail_inbound_delivery_date_month_lbls = $months;
//        $outForm->raw_comiket_detail_inbound_delivery_date_day_sel = $inForm["comiket_detail_inbound_delivery_date_day_sel"];
//        $outForm->raw_comiket_detail_inbound_delivery_date_day_cds = $days;
//        $outForm->raw_comiket_detail_inbound_delivery_date_day_lbls = $days;
//        // 搬出-お届け日時-時間帯
//        $outForm->raw_comiket_detail_inbound_delivery_time_sel = $inForm["comiket_detail_inbound_delivery_time_sel"];
//        $outForm->raw_comiket_detail_inbound_delivery_time_cds = array_keys($comiket_detail_time_lbls);
//        $outForm->raw_comiket_detail_inbound_delivery_time_lbls = array_values($comiket_detail_time_lbls);
//        
//        // 搬出-サービス選択
//        $outForm->raw_comiket_detail_inbound_service_sel = $inForm["comiket_detail_inbound_service_sel"];
////Sgmov_Component_Log::debug("################ 122");
////Sgmov_Component_Log::debug($inForm);
//        
//        // 搬出-宅配
//        $outForm->raw_comiket_box_inbound_num_ary = $inForm["comiket_box_inbound_num_ary"];
//        
//        // 搬出-カーゴ
//        $outForm->raw_comiket_cargo_inbound_num_ary = $inForm["comiket_cargo_inbound_num_ary"];
//        
//        // 搬出-チャーター
//        $outForm->raw_comiket_charter_inbound_num_ary = $inForm["comiket_charter_inbound_num_ary"];
//        
//        // 搬出-備考
//        $outForm->raw_comiket_detail_inbound_note = $inForm["comiket_detail_inbound_note"];
//        
//        // 送料
//        // 計算方法が不明
//
//        return array(
//            "outForm" => $outForm,
//            "dispItemInfo" => $dispItemInfo,
//        );

    }
}
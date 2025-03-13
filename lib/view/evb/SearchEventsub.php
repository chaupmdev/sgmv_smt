<?php
/**
 * @package    ClassDefFile
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useAllComponents();
Sgmov_Lib::useView('Public');
Sgmov_Lib::useView('eve/Common');
Sgmov_Lib::useServices(array('Eventsub', 'EventsubCmb'));
/**#@-*/

/**
 * イベントIDからブース情報を検索して返します。
 * @package    View
 * @subpackage EVB
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Evb_SearchEventsub extends Sgmov_View_Public {

    /**
     * 館マスタサービス(ブース番号)
     * @var type 
     */
    private $_EventsubService;
    
    private $_EventsubCmbService;
    
//    private $_EveCommonView;
    
    /**
     * ツアーサービス
     * @var Sgmov_Service_Travel
     */
//    public $_TravelService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_EventsubService = new Sgmov_Service_Eventsub();
        $this->_EventsubCmbService = new Sgmov_Service_EventsubCmb();
//        $this->_EveCommonView = new Sgmov_View_Eve_Common();
//        $this->_TravelService = new Sgmov_Service_Travel();
    }

    /**
     * 処理を実行します。
     *
     */
    public function executeInner() {
        
//Sgmov_Component_Log::degug("###################### 201");
//error_log("######################### 201");
        
        $featureId            = filter_input(INPUT_POST, 'featureId');
        $fromGamenId          = filter_input(INPUT_POST, 'id');
        $ticket               = filter_input(INPUT_POST, 'ticket');
        $eventId = filter_input(INPUT_POST, 'event_sel');
        $eventsubId = filter_input(INPUT_POST, 'eventsub_sel');
        $comiketDetailTypeSel = filter_input(INPUT_POST, 'comiket_detail_type_sel');
        $comiketDetailInboundPrefCdSel = filter_input(INPUT_POST, 'comiket_detail_inbound_pref_cd_sel');
        
        if(empty($eventId)) {
            return array(
                'ids' => array(),
                'names' => array(),
                'list' => array(),
            );
        }
        
        // チケット確認
        $this->_checkSession($featureId, $fromGamenId, $ticket);
        
        try {
            // DB接続
            $db = Sgmov_Component_DB::getPublic();
            $returnAry = $this->_EventsubService->fetchEventsubListWithinTermByEventId($db, $eventId);
        } catch (exception $e) {
        }
        
//        $returnAry2 = array();
        if(empty($returnAry)) {
            return array(
                'ids' => array(),
                'names' => array(),
                'list' => array(),
            );
        } else {
            $inboundHatsuJis2 = "";
            $inboundChakuJis2 = "";
//            if(($comiketDetailTypeSel == "2" || $comiketDetailTypeSel == "3") 
//                    && !empty($comiketDetailInboundPrefCdSel)) {
//                $inForm = $_REQUEST;
//                $this->setYubinDllInfoToInForm($inForm);
//                $inboundHatsuJis2 = $inForm["inbound_hatsu_jis2code"];
//                $inboundChakuJis2 = $inForm["inbound_chaku_jis2code"];
//            }
            $cmbAry = $this->_EventsubCmbService->cmbEventsubList($returnAry["list"], $eventsubId, $inboundHatsuJis2, $inboundChakuJis2);
            $returnAry["list"] = $cmbAry["list"];
        }

        return array(
            "ids" => $returnAry["ids"],
            "names" => $returnAry["names"],
            "list" => $returnAry["list"],
        );
    }
    
    /**
     * 
     * @param type $inForm
     */
    public function setYubinDllInfoToInForm(&$inForm) {
        $db = Sgmov_Component_DB::getPublic();
//Sgmov_Component_Log::debug("##################################### 310 common");
//Sgmov_Component_Log::debug($inForm["eventsub_sel"]);
        $eventsubData = $this->_EventsubService->fetchEventsubByEventsubId($db, $inForm["eventsub_sel"]);
//Sgmov_Component_Log::debug("##################################### 312 common");
//Sgmov_Component_Log::debug($eventsubData);

        $resultEventZipDll = $this->_HttpsZipCodeDll->_execYubin7(array(
            "szZipCode" => @$eventsubData["zip"],
            "szAddress" => @$eventsubData["address"],
            "szTel" => "",
        ));

        if($inForm['comiket_detail_type_sel'] == "1" 
                || $inForm['comiket_detail_type_sel'] == "3" ) { // 搬入（もしくは搬入搬出）
            // 搬入 /////////////////////////////////////////////
            
//            $resultOutboundPrefData = array();
//            if(!empty($inForm['comiket_detail_outbound_pref_cd_sel'])) {
            
                $resultOutboundPrefData = $this->_PrefectureService->fetchPrefecturesById($db, $inForm['comiket_detail_outbound_pref_cd_sel']);
            
                $resultOutboundHatsuZipDll = $this->_HttpsZipCodeDll->_execYubin7(array(
                    "szZipCode" => @$inForm['comiket_detail_outbound_zip1'] . @$inForm['comiket_detail_outbound_zip2'],
                    "szAddress" => @$resultOutboundPrefData["name"] . @$inForm['comiket_detail_outbound_address'] . @$inForm['comiket_detail_outbound_building'],
                    "szTel" => @$inForm['comiket_detail_outbound_tel'],
                ));

                $inForm["outbound_hatsu_jis2code"] = @$resultOutboundHatsuZipDll["JIS2Code"];
                $inForm["outbound_hatsu_jis5code"] = @$resultOutboundHatsuZipDll["JIS5Code"];
                $inForm["outbound_hatsu_shop_check_code"] = @$resultOutboundHatsuZipDll["ShopCheckCode"];
                $inForm["outbound_hatsu_shop_check_code_eda"] = @$resultOutboundHatsuZipDll["ShopCheckCodeEda"];
                $inForm["outbound_hatsu_shop_code"] = @$resultOutboundHatsuZipDll["ShopCode"];
                $inForm["outbound_hatsu_shop_local_code"] = @$resultOutboundHatsuZipDll["ShopLocalCode"];

                $inForm["outbound_chaku_jis2code"] = @$resultEventZipDll["JIS2Code"];        
                $inForm["outbound_chaku_jis5code"] = @$resultEventZipDll["JIS5Code"];
                $inForm["outbound_chaku_shop_check_code"] = @$resultEventZipDll["ShopCheckCode"];
                $inForm["outbound_chaku_shop_check_code_eda"] = @$resultEventZipDll["ShopCheckCodeEda"];
                $inForm["outbound_chaku_shop_code"] = @$resultEventZipDll["ShopCode"];
                $inForm["outbound_chaku_shop_local_code"] = @$resultEventZipDll["ShopLocalCode"];
//            }
        }
        
        // 搬出 /////////////////////////////////////////////
        if($inForm['comiket_detail_type_sel'] == "2" 
                || $inForm['comiket_detail_type_sel'] == "3" ) { // 搬出（もしくは搬入搬出）
            // 搬出 /////////////////////////////////////////////
            $inForm["inbound_hatsu_jis2code"] = @$resultEventZipDll["JIS2Code"];
            $inForm["inbound_hatsu_jis5code"] = @$resultEventZipDll["JIS5Code"];
            $inForm["inbound_hatsu_shop_check_code"] = @$resultEventZipDll["ShopCheckCode"];
            $inForm["inbound_hatsu_shop_check_code_eda"] = @$resultEventZipDll["ShopCheckCodeEda"];
            $inForm["inbound_hatsu_shop_code"] = @$resultEventZipDll["ShopCode"];
            $inForm["inbound_hatsu_shop_local_code"] = @$resultEventZipDll["ShopLocalCode"];


            $resultInboundPrefData = $this->_PrefectureService->fetchPrefecturesById($db, $inForm['comiket_detail_inbound_pref_cd_sel']);

            $resultInboundZipDll = $this->_HttpsZipCodeDll->_execYubin7(array(
                "szZipCode" => @$inForm['comiket_detail_inbound_zip1'] . @$inForm['comiket_detail_inbound_zip2'],
                "szAddress" => @$resultInboundPrefData["name"] . @$inForm['comiket_detail_inbound_address'] . @$inForm['comiket_detail_inbound_building'],
                "szTel" => @$inForm['comiket_detail_inbound_tel'],
            ));

            $inForm["inbound_chaku_jis2code"] = @$resultInboundZipDll["JIS2Code"];
            $inForm["inbound_chaku_jis5code"] = @$resultInboundZipDll["JIS5Code"];
            $inForm["inbound_chaku_shop_check_code"] = @$resultInboundZipDll["ShopCheckCode"];
            $inForm["inbound_chaku_shop_check_code_eda"] = @$resultInboundZipDll["ShopCheckCodeEda"];
            $inForm["inbound_chaku_shop_code"] = @$resultInboundZipDll["ShopCode"];
            $inForm["inbound_chaku_shop_local_code"] = @$resultInboundZipDll["ShopLocalCode"];
        }
        
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    }

    /**
     * チケットの確認を行います。
     * TODO ybn/SearchAddressと同記述あり
     */
    public function _checkSession($featureId, $fromGamenId, $ticket) {
        // セッション
        $session = Sgmov_Component_Session::get();

        // チケットの確認
        if (!isset($_SESSION[Sgmov_Component_Session::_KEY_TICKETS])) {
            Sgmov_Component_Log::warning('【イベントサブ検索 不正使用】チケットが存在していません。');
            header('HTTP/1.0 404 Not Found');
            exit;
        }

        $tickets = &$_SESSION[Sgmov_Component_Session::_KEY_TICKETS];
        if (!isset($tickets[$featureId]) || $tickets[$featureId] !== $fromGamenId . $ticket) {
            Sgmov_Component_Log::warning('【イベントサブ検索 不正使用】チケットが不正です。　'.$tickets[$featureId].' <=> '.$fromGamenId . $ticket);
            header('HTTP/1.0 404 Not Found');
            exit;
        } else {
            Sgmov_Component_Log::debug('イベントサブ実行 機能ID=>'.$tickets[$featureId].' <=> '.$fromGamenId . $ticket);
        }
    }
}
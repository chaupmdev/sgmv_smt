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
Sgmov_Lib::useView('mlk/Common');
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
Sgmov_Component_Log::debug('############## Conf');
        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();

        // DB接続
        $db = Sgmov_Component_DB::getPublic();

        // セッションに入力チェック済みの情報があるかどうかを確認
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        
        //Put file
        //$serializedObject = serialize($sessionForm);
        //file_put_contents('object.txt', $serializedObject);
        //Get data from file
        //$data = file_get_contents('object.txt');
        //$sessionForm = unserialize($data);
        
        
        if (!isset($sessionForm->in) || $sessionForm->status != self::VALIDATION_SUCCEEDED) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
        }

        // セッション情報を元に出力情報を設定
        $resultData = $this->_createOutFormByInForm($sessionForm->in, $db);
        $outForm = $resultData["outForm"];
        $dispItemInfo = $resultData["dispItemInfo"];
        $inForm = (array)$sessionForm->in;
        if(@!empty($inForm['comiket_id'])) { // 編集画面の場合
            $dispItemInfo['back_input_path'] = "input2";
        } else {
            $dispItemInfo['back_input_path'] = "input";
        }
        
        //$hotelInfo = $this->_BuildingService->fetchBuildingNameByCd($db, $hotelCd, $inForm['eventsub_sel']);
        //$dispItemInfo['hotelInfo'] = [];//$hotelInfo;
        
        //$boxInfoList = $this->_BoxService->fetchBoxByEventsubId($db, $inForm['eventsub_sel']);
        
        //$dispItemInfo['boxId'] =  @$boxInfoList[0]['id'];
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_EVE003);
        $code = substr($outForm->comiket_id(), 0, 8);
        $hachakutenInfo = $this->_HachakutenService->fetchValidHachakutenByCode($db, $code);
        $hachakutenInfo['delivery_date_store'] = '';
        $currentTime = date('His');
        if (!empty($hachakutenInfo['input_end_time'])) {
            if ($currentTime >= $hachakutenInfo['input_end_time']."00") {
                $hachakutenInfo['delivery_date_store'] = date('Y/m/d', strtotime("+1 day", strtotime(date('Y-m-d'))));
            } else {
                $hachakutenInfo['delivery_date_store'] = date('Y/m/d');
            }
        }
        $this->fetchDataOutForm($outForm, $dispItemInfo);
        
        return array(
            'ticket'  => $ticket,
            'outForm' => $outForm,
            'dispItemInfo' => $dispItemInfo,
            'hachakutenInfo' => $hachakutenInfo,
        );
    }
    /**
     * 入力フォームの値を元に出力フォームを生成します。
     * @param Sgmov_Form_Eve001In $inForm 入力フォーム
     * @return Sgmov_Form_Eve003Out 出力フォーム
     */
    public function _createOutFormByInForm($inForm, $db) {
        
        return $this->createOutFormByInForm($inForm, new Sgmov_Form_Eve003Out());
    }
    private function getNameSelectBox($cds, $lbls, $select) {
        if (empty($select)) {
            return "";
        }
        $count = count($cds);
        for ($i = 0; $i < $count; ++$i) {
            if ($select == $cds[$i]) {
                return $lbls[$i];
            }
        }
        return "";
    }
    private function fetchDataOutForm(&$outForm, $dispItemInfo) {
        $outForm->addressee_type_name = $this->getNameSelectBox($outForm->raw_addressee_type_cds, $outForm->raw_addressee_type_lbls, $outForm->raw_addressee_type_sel);
        
        $outForm->hotel_service_airport_name = '';
        if ($outForm->raw_addressee_type_sel == self::DELIVERY_TYPE_AIRPORT) {
            $outForm->hotel_service_airport_name = $this->getNameSelectBox($outForm->raw_airport_cds, $outForm->raw_airport_lbls, $outForm->raw_airport_sel);
        } else if ($outForm->raw_addressee_type_sel == self::DELIVERY_TYPE_SERVICE) {
            $outForm->hotel_service_airport_name = $this->getNameSelectBox($outForm->raw_sevice_center_cds, $outForm->raw_sevice_center_lbls, $outForm->raw_sevice_center_sel);
        } else if ($outForm->raw_addressee_type_sel == self::DELIVERY_TYPE_HOTEL) {
            $outForm->hotel_service_airport_name = $this->getNameSelectBox($outForm->raw_hotel_cds, $outForm->raw_hotel_lbls, $outForm->raw_hotel_sel);
        }
        $outForm->comiket_box_name = '';
        $boxLbls = $dispItemInfo['box_lbls'];
        
        
        $boxIds = array_keys($outForm->raw_comiket_box_inbound_num_ary);

        foreach ($boxLbls as $item) {
            if ($item['cd'] == $boxIds[0]) {
                $outForm->comiket_box_name = $item['name'];
            }
        }

    }
}
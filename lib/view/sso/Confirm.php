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
Sgmov_Lib::useView('sso/Common');
Sgmov_Lib::useForms(array('Error', 'EveSession', 'Eve003Out'));
/**#@-*/

/**
 * イベント手荷物受付サービスのお申し込み確認画面を表示します。
 * @package    View
 * @subpackage EVE
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Sso_Confirm extends Sgmov_View_Sso_Common {
    
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
        $dispItemInfo['back_input_path'] = $this->getBackInputPath($inForm['comiket_id']);

        // チケットを発行
        $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_EVE003);

        return array(
            'ticket'  => $ticket,
            'outForm' => $outForm,
            'dispItemInfo' => $dispItemInfo,
        );
    }
    
    /**
     * 
     * @param type $comiketId
     * @return string
     */
    protected function getBackInputPath($comiketId) {
        if(@!empty($comiketId)) { // 編集画面の場合
            return "input2";
        } else {
            return "input";
        }
    }
    
    /**
     * 入力フォームの値を元に出力フォームを生成します。
     * @param Sgmov_Form_Eve001In $inForm 入力フォーム
     * @return Sgmov_Form_Eve003Out 出力フォーム
     */
    public function _createOutFormByInForm($inForm, $db) {
        return $this->createOutFormByInForm($inForm, new Sgmov_Form_Eve003Out());
    }
}
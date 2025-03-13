<?php
/**
 * @package    ClassDefFile
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/**#@+
 * include files
 */
require_once dirname(__FILE__).'/../../Lib.php';
Sgmov_Lib::useView('bpn/Common');
Sgmov_Lib::useServices(array('BoxFare'));
Sgmov_Lib::useForms(array('Error', 'BpnSession', 'Bpn002Out'));
/**#@-*/
/**
 * 物販お申し込みのクレジットカード入力画面を表示します。
 * @package    View
 * @subpackage BPN
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Bpn_CreditCard extends Sgmov_View_Bpn_Common {

    /**
     * 共通サービス
     * @var Sgmov_Service_AppCommon
     */
    public $_appCommon;
    
    /**
     * 宅配サービス
     * @var type 
     */
    private $_BoxFareService;
    
    /**
     * イベントサブマスタ
     * @var Sgmov_Service_Eventsub 
     */
    private $_EventsubService;
//
//    /**
//     * クルーズリピータサービス
//     * @var Sgmov_Service_CruiseRepeater
//     */
//    public $_CruiseRepeater;
//
//    /**
//     * ツアーサービス
//     * @var SSgmov_Service_Travel
//     */
//    private $_TravelService;
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
        $this->_appCommon = new Sgmov_Service_AppCommon();
        $this->_BoxFareService = new Sgmov_Service_BoxFare();
        $this->_EventsubService = new Sgmov_Service_Eventsub();
        
        parent::__construct();
//        $this->_CruiseRepeater                   = new Sgmov_Service_CruiseRepeater();
//        $this->_TravelService                    = new Sgmov_Service_Travel();
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

        if (!isset($sessionForm)) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
        } else {

            
            // 情報の取得
            $outForm = $this->_createOutFormByInForm($sessionForm->in);
            // セッション情報を元に出力情報を作成
            $errorForm = $sessionForm->error;
            // セッション破棄
            $sessionForm->error = NULL;
        }



        // チケットを発行
        $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_BPN002);

        return array(
            'ticket'    => $ticket,
            'outForm'   => $outForm,
            'errorForm' => $errorForm,
        );
    }

    /**
     * 入力フォームの値を元に出力フォームを生成します。
     * @param Sgmov_Form_Pcr002In $sessionForm 入力フォーム
     * @return Sgmov_Form_Pcr002Out 出力フォーム
     */
    public function _createOutFormByInForm($inForm) {

        $outForm = new Sgmov_Form_Bpn002Out();

        // TODO オブジェクトから値を直接取得できるよう修正する
        // オブジェクトから取得できないため、配列に型変換
        $inForm = (array)$inForm;


        // テンプレート用の値をセット
        $db = Sgmov_Component_DB::getPublic();

        // イベントサブマスタを取得
        $eventsubInfo = $this->_EventsubService->fetchEventsubByEventsubId($db, $inForm['eventsub_sel']);
        // 期間開始日をセット
        $outForm->raw_term_fr = $eventsubInfo['term_fr'];
        // クレジットカード有効期限年の最大カウント値をセット
        $outForm->input_creditcard_cnt = Sgmov_Service_AppCommon::INPUT_CREDITCARD_CNT;

        $now = new DateTime('now');
        $plusYear = 0;
        if ($now->format('m') == 12) {
            $plusYear = 1;
        }
        $years  = $this->_appCommon->getYears($now->format('Y')+$plusYear, Sgmov_Service_AppCommon::INPUT_CREDITCARD_CNT, false);
        $months = $this->_appCommon->months;
        array_shift($months);
        $outForm->raw_card_expire_year_cds   = $years;
        $outForm->raw_card_expire_year_lbls  = $years;
        $outForm->raw_card_expire_month_cds  = $months;
        $outForm->raw_card_expire_month_lbls = $months;


        $calcDataInfoData = $this->calcEveryKindData($inForm);
        $calcDataInfo = $calcDataInfoData["treeData"];
      
        $calcDataInfoForBuppan = $calcDataInfoData["treeDataForBuppan"];

        $outForm->raw_eventsub_cd_sel = $inForm['eventsub_sel'];
        
        // 送料
        $outForm->raw_delivery_charge = @empty($calcDataInfo["amount_tax"]) ? 0 : $calcDataInfo["amount_tax"];
        $outForm->raw_delivery_charge_buppan = @empty($calcDataInfoForBuppan["amount_tax"]) ? 0 : $calcDataInfoForBuppan["amount_tax"];
//        $outForm->raw_repeater_discount = "300";
        $outForm->raw_card_number = $inForm['card_number'];
        $outForm->raw_card_expire_month_cd_sel = $inForm['card_expire_month_cd_sel'];
        $outForm->raw_card_expire_year_cd_sel = $inForm['card_expire_year_cd_sel'];
        $outForm->raw_security_cd = $inForm['security_cd'];
        //$outForm->raw_event_cd_sel = $calcDataInfo["event_id"];
        $outForm->raw_comiket_id = $inForm['comiket_id'];

        $outForm->raw_bpn_type = $inForm['bpn_type'];
        $outForm->raw_shohin_pattern = $inForm['shohin_pattern'];
        $outForm->raw_shikibetsushi = $inForm['shikibetsushi'];

        return $outForm;
    }
}
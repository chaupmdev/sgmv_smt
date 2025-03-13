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
Sgmov_Lib::useView('mlk/Common');
Sgmov_Lib::useServices(array('BoxFare'));
Sgmov_Lib::useForms(array('Error', 'EveSession', 'Eve002Out'));
/**#@-*/
/**
 * イベント手荷物受付サービスのお申し込みのクレジットカード入力画面を表示します。
 * @package    View
 * @subpackage EVE
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Eve_CreditCard extends Sgmov_View_Eve_Common {

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
        $this->_appCommon                        = new Sgmov_Service_AppCommon();
        $this->_BoxFareService = new Sgmov_Service_BoxFare();
        
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
//Sgmov_Component_Log::debug("##################################### 201 credit_card");
//Sgmov_Component_Log::debug($sessionForm->in);
            $isBack = filter_input(INPUT_GET, 'back');
            if ($isBack && $isBack == 1) {
                $form = (array) $sessionForm->in;
                $code = substr($form['comiket_id'], 0, 8);
                $hachakutenInfo = $this->_HachakutenService->fetchValidHachakutenByCode($db, $code);
                $deliveryDateStore = '';
                $currentTime = date('His');
                if (!empty($hachakutenInfo['input_end_time'])) {
                    if ($currentTime >= $hachakutenInfo['input_end_time']."00") {
                        $deliveryDateStore = date('Y/m/d', strtotime("+1 day", strtotime(date('Y/m/d'))));
                    } else {
                        $deliveryDateStore = date('Y/m/d');
                    }
                }
                if ($form['delivery_date_store'] != $deliveryDateStore) {
                    Sgmov_Component_Redirect::redirectPublicSsl('/mlk/input?tagId=' . $form['comiket_id'] . '&back=1');
                }
            }
            
            // 情報の取得
            $outForm = $this->_createOutFormByInForm($sessionForm->in);
            // セッション情報を元に出力情報を作成
            $errorForm = $sessionForm->error;
            // セッション破棄
            $sessionForm->error = NULL;
        }

        // チケットを発行
        $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_EVE002);

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

        $outForm = new Sgmov_Form_Eve002Out();

        // TODO オブジェクトから値を直接取得できるよう修正する
        // オブジェクトから取得できないため、配列に型変換
        $inForm = (array)$inForm;

        // テンプレート用の値をセット
        $db = Sgmov_Component_DB::getPublic();

        $now = new DateTime('now');
        $outForm->raw_term_fr = $now->format("Y-m-d");
        // クレジットカード有効期限年の最大カウント値をセット
        $outForm->input_creditcard_cnt = Sgmov_Service_AppCommon::INPUT_CREDITCARD_CNT;
        
        $years  = $this->_appCommon->getYears($now->format('Y'), Sgmov_Service_AppCommon::INPUT_CREDITCARD_CNT, false);
        $months = $this->_appCommon->months;
        array_shift($months);
        $outForm->raw_card_expire_year_cds   = $years;
        $outForm->raw_card_expire_year_lbls  = $years;
        $outForm->raw_card_expire_month_cds  = $months;
        $outForm->raw_card_expire_month_lbls = $months;
        
        $calcDataInfo = $this->calcAmount((array)$inForm); 
        // 送料
        $outForm->raw_delivery_charge = @$calcDataInfo['aountTax'];
//        $outForm->raw_repeater_discount = "300";
        $outForm->raw_card_number = $inForm['card_number'];
        $outForm->raw_card_expire_month_cd_sel = $inForm['card_expire_month_cd_sel'];
        $outForm->raw_card_expire_year_cd_sel = $inForm['card_expire_year_cd_sel'];
        $outForm->raw_security_cd = $inForm['security_cd'];
        $outForm->raw_event_cd_sel = $inForm['event_sel']; //$calcDataInfo["event_id"];
        $outForm->raw_comiket_id = $inForm['comiket_id'];
        
                
//        $data = array(
//            'prefecture_id' => $inForm['comiket_pref_cd_sel'],
//        );
//        $departure_charge = 0;
//        $arrival_charge   = 0;
//
//        // TODO マジックナンバーを定数にする
//        $checkDeparture = ((intval($inForm['terminal_cd_sel']) & 1) === 1);
//        $checkArrival   = ((intval($inForm['terminal_cd_sel']) & 2) === 2);
//
//        if ($checkDeparture) {
//            $departure_charge = $this->_TravelDeliveryChargeAreasService->fetchDeliveryCharge($db,
//                    $data + array('travel_terminal_id' => $inForm['travel_departure_cd_sel']));
//        }
//
//        if ($checkArrival) {
//            $arrival_charge = $this->_TravelDeliveryChargeAreasService->fetchDeliveryCharge($db,
//                    $data + array('travel_terminal_id' => $inForm['travel_arrival_cd_sel']));
//        }
//
//        $discount = parent::_getDiscount($checkDeparture, $checkArrival, $db, $this->_TravelService, $this->_CruiseRepeater, $inForm);
//
//        $outForm->raw_delivery_charge = number_format(
//            $departure_charge * intval($inForm['departure_quantity'])
//            + $arrival_charge * intval($inForm['arrival_quantity'])
//            - ($discount['round_trip_discount'] + $discount['repeater_discount']) * min($inForm['departure_quantity'], $inForm['arrival_quantity'])
//        );
//        $outForm->raw_repeater_discount = $discount['repeater_discount'] * min($inForm['departure_quantity'], $inForm['arrival_quantity']);
//
//        $outForm->raw_card_number = $inForm['card_number'];
//        $outForm->raw_card_expire_month_cd_sel = $inForm['card_expire_month_cd_sel'];
//        $outForm->raw_card_expire_year_cd_sel  = $inForm['card_expire_year_cd_sel'];
//        $outForm->raw_security_cd = $inForm['security_cd'];

        return $outForm;
    }
}
<?php
/**
 * @package    ClassDefFile
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('pcr/Common', 'CommonConst');
Sgmov_Lib::useForms(array('Error', 'PcrSession', 'Pcr004Out'));
Sgmov_Lib::useServices(array('Cruise', 'CenterMail','GyomuApi'));
Sgmov_Lib::use3gMdk(array('3GPSMDK'));
/**#@-*/

/**
 * 旅客手荷物受付サービスのお申し込み内容を登録し、完了画面を表示します。
 * @package    View
 * @subpackage PCR
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pcr_Complete extends Sgmov_View_Pcr_Common {

    const SEVEN_ELEVEN_CODE = 'sej';
    const E_CONTEXT_CODE    = 'econ';
    const WELL_NET_CODE     = 'other';
    const TOIAWASE_ALL_ZERO = '000000000000';
    /**
     * クルーズリピータサービス
     * @var Sgmov_Service_CruiseRepeater
     */
    public $_CruiseRepeater;

    /**
     * 都道府県サービス
     * @var Sgmov_Service_Prefecture
     */
    public $_PrefectureService;

    /**
     * ツアーサービス
     * @var Sgmov_Service_Travel
     */
    private $_TravelService;

    /**
     * ツアー発着地サービス
     * @var Sgmov_Service_TravelTerminal
     */
    private $_TravelTerminalService;

    /**
     * ツアー配送料金エリアサービス
     * @var Sgmov_Service_TravelDeliveryChargeAreas
     */
    private $_TravelDeliveryChargeAreasService;

    /**
     * 旅客手荷物受付サービスのお申し込みサービス
     * @var Sgmov_Service_Cruise
     */
    private $_CruiseService;

    /**
     * 拠点メールアドレスサービス
     * @var Sgmov_Service_CenterMail
     */
    public $_centerMailService;

    /**
     * 業務連携請求書問番
     * @var Sgmov_Service_CenterMail
     */
    public $_gyomuApiService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_CruiseRepeater                   = new Sgmov_Service_CruiseRepeater();
        $this->_PrefectureService                = new Sgmov_Service_Prefecture();
        $this->_TravelService                    = new Sgmov_Service_Travel();
        $this->_TravelTerminalService            = new Sgmov_Service_TravelTerminal();
        $this->_TravelDeliveryChargeAreasService = new Sgmov_Service_TravelDeliveryChargeAreas();
        $this->_CruiseService                    = new Sgmov_Service_Cruise();
        $this->_centerMailService                = new Sgmov_Service_CenterMail();
        $this->_gyomuApiService                  = new Sgmov_Service_GyomuApi();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * セッションの継続を確認
     * </li><li>
     * チケットの確認と破棄
     * </li><li>
     * 入力チェック
     * </li><li>
     * セッションから情報を取得
     * </li><li>
     * 情報をDBへ格納
     * </li><li>
     * 出力情報を設定
     * </li><li>
     * セッション情報を破棄
     * </li></ol>
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['outForm']:出力フォーム
     * </li></ul>
     */
    public function executeInner() {

        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();

        //チケットの確認と破棄
        $featureId = self::FEATURE_ID;
        if (strpos($_SERVER['PHP_SELF'], 'complete_call')) {
            $featureId = self::FEATURE_ID_IVR;
        }
        $session->checkTicket($featureId, self::GAMEN_ID_PCR003, $this->_getTicket());

        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        // セッションから情報を取得
        $sessionForm = $session->loadForm($featureId);
        if (empty($sessionForm)) {
            $sessionForm = new Sgmov_Form_PcrSession();
        }

        $inForm = $this->_createDataByInForm($db, $sessionForm->in);
        
        //登録用IDを取得
        $id = $this->_CruiseService->select_id($db, $inForm['req_flg']);
        
        //WEB又はコンビニ決済の場合、ベリトランスを使う
        if ($inForm['req_flg'] == self::PCR_WEB_REQUEST || $inForm['payment_method_cd_sel'] == '1') {
            switch ($inForm['payment_method_cd_sel']) {
                case '2':
                    $checkForm = $this->_createCheckCreditCardDataByInForm($inForm);
                    break;
                case '1':
                    $checkForm = $this->_createCheckConvenienceStoreDataByInForm($db, $inForm);
                    break;
                default:
                    break;
            }

            if (!empty($checkForm)) {
                $inForm = $this->_transact($checkForm, $inForm);
            }
        } else {//コールセンター
            //決済情報連携 APIを連携する
            //TODO
//            $inForm['merchant_result'] = 1;
//            $inForm['authorization_cd'] = null;
//            $inForm['receipt_cd'] = null;
//            $inForm['payment_url'] = null;
            
            $urlIVR = Sgmov_Component_Config::getUrlPaymentInforApiForIVR();
            $passwordIVR = Sgmov_Component_Config::getPswdPaymentInforApiForIVR();
            $sendRequestData = $this->createDataForRequestIVR($db, $inForm, $passwordIVR);
            //決済情報を連携する
            $resultData = $this->sendPaymentInforRequestForIVRService($urlIVR, $sendRequestData);
            if ($resultData['result'] == 'NG') {//連携失敗
                $errorForm = new Sgmov_Form_Error();
                $errorForm->addError('complete_back_call_api_Ivr', $resultData['message']);

                $sessionForm->error = $errorForm;
                $session->saveForm($featureId, $sessionForm);
                if ($errorForm->hasError()) {
                    Sgmov_Component_Redirect::redirectPublicSsl('/pcr/input_call');
                }
            } else {//連携成功
                $inForm['merchant_result'] = null;
                $inForm['merchant_datetime'] = null;
                $inForm['authorization_cd'] = null;
                $inForm['receipt_cd'] = null;
                $inForm['payment_url'] = null;
            }
        }

        // メール送信(WEB申込又はコンビニ決済の場合、メール送信する）
        if (!empty($inForm['mail']) && !empty($inForm['merchant_result']) && ($inForm['req_flg'] == self::PCR_WEB_REQUEST || $inForm['payment_method_cd_sel'] == '1')) {
            // メール送信用データを作成
            $mailData = $this->_createMailDataByInForm($db, $inForm, $id);
            switch ($inForm['terminal_cd_sel']) {
                case '1':
                    $mailTemplate = $inForm['req_flg'] == self::PCR_WEB_REQUEST ? '/pcr_user_departure.txt' : '/pcr_user_departure_ivr.txt';
                    break;
                case '2':
                    $mailTemplate =  $inForm['req_flg'] == self::PCR_WEB_REQUEST ? '/pcr_user_arrival.txt' : '/pcr_user_arrival_ivr.txt';
                    break;
                case '3':
                default:
                    $mailTemplate = $inForm['req_flg'] == self::PCR_WEB_REQUEST ? '/pcr_user.txt' : '/pcr_user_ivr.txt';
                    break;
            }
            $this->_centerMailService->_sendThankYouMail($mailTemplate, $inForm['mail'], $mailData);
        }
        $data = $this->_createInsertDataByInForm($inForm, $id);

        // 情報をDBへ格納
        $this->_CruiseService->insert($db, $data);

        // 出力情報を設定
        $outForm = $this->_createOutFormByInForm($inForm);

        // セッション情報を破棄
        $session->deleteForm($featureId);
        
        if ($inForm['req_flg'] == self::PCR_IVR_REQUEST) {
            $_SESSION['PCR_CALL_CENTER'] = $inForm['call_operator_id_cd_sel'];
        }

        return array('outForm' => $outForm);
    }

    /**
     * POST値からチケットを取得します。
     * チケットが存在しない場合は空文字列を返します。
     * @return string チケット文字列
     */
    public function _getTicket() {
        if (!isset($_POST['ticket'])) {
            $ticket = '';
        } else {
            $ticket = $_POST['ticket'];
        }
        return $ticket;
    }

    /**
     * 入力フォームの値を元に支払期限を生成します。
     * @param Sgmov_Form_Pcr001-003In $inForm 入力フォーム
     * @return string 支払期限
     */
    public function _getPayLimit($db, $inForm) {
        // コンビニの支払期限
        $date_convenience_store = new DateTime();
        switch ($inForm['convenience_store_cd_sel']) {
            case '1':
                $service_option_type = self::SEVEN_ELEVEN_CODE;
                $max_day = '+150 day';
                break;
            case '2':
                $service_option_type = self::E_CONTEXT_CODE;
                $max_day = '+60 day';
                break;
            case '3':
                $service_option_type = self::WELL_NET_CODE;
                $max_day = '+365 day';
                break;
            default:
                return;
        }
        $date_convenience_store->modify($max_day);
        $pay_limit_convenience_store = $date_convenience_store->format('Y/m/d');
Sgmov_Component_Log::debug($pay_limit_convenience_store);

        // SGムービングの支払期限
        $embarkation_date = $this->_TravelService->fetchEmbarkationDate($db, array('travel_id' => $inForm['travel_cd_sel']));
        //$departure = $this->_TravelTerminalService->fetchTravelDeparture($db, array('travel_id' => $inForm['travel_cd_sel']), true);
        if (!empty($embarkation_date)) {
            $date = new DateTime($embarkation_date);
            $pay_limit = $date->format('Y/m/d');
        }
//        elseif (!empty($departure['dates'])) {
//            $date = new DateTime($departure['dates'][array_search($inForm['travel_departure_cd_sel'], $departure['ids'])]);
//        }
//        if (!empty($date)) {
//            $date->modify('-10 day');
//            $pay_limit = $date->format('Y/m/d');
//Sgmov_Component_Log::debug($pay_limit);
//        }

        if (empty($pay_limit) || $pay_limit > $pay_limit_convenience_store) {
            $pay_limit = $pay_limit_convenience_store;
        }
/*
        if (!empty($inForm['cargo_collection_date_year_cd_sel'])
                && !empty($inForm['cargo_collection_date_month_cd_sel'])
                && !empty($inForm['cargo_collection_date_day_cd_sel'])) {
            $date2 = new DateTime($inForm['cargo_collection_date_year_cd_sel']
                    . '/' . $inForm['cargo_collection_date_month_cd_sel']
                    . '/' . $inForm['cargo_collection_date_day_cd_sel']);
            switch ($date2->format('N')) {
                case '1': // 月
                case '2': // 火
                    $date2->modify('-4 day');
                    break;
                case '3': // 水
                case '4': // 木
                case '5': // 金
                case '6': // 土
                    $date2->modify('-2 day');
                    break;
                case '7': // 日
                    $date2->modify('-3 day');
                    break;
                default:
                    break;
            }
            $pay_limit2 = $date2->format('Y/m/d');
Sgmov_Component_Log::debug($pay_limit2);
            if (empty($pay_limit) || $pay_limit > $pay_limit2) {
                $pay_limit = $pay_limit2;
            }
Sgmov_Component_Log::debug($pay_limit);
        }
*/
Sgmov_Component_Log::debug($pay_limit);
        return $pay_limit;
    }

    /**
     * 入力フォームの値を元にデータを生成します。
     * @param Sgmov_Form_Pcr001-003In $inForm 入力フォーム
     * @return array データ
     */
    public function _createDataByInForm($db, $inForm) {

        // TODO オブジェクトから値を直接取得できるよう修正する
        // オブジェクトから取得できないため、配列に型変換
        $inForm = (array) $inForm;

        $data = array(
            'prefecture_id' => $inForm['pref_cd_sel'],
        );
        $departure_charge = 0;
        $arrival_charge   = 0;

        // sawada svnと本番ソースに差異があったため戻す
        $discount = array(
            'round_trip_discount' => 0,
            'repeater_discount'   => 0,
        );

        // TODO マジックナンバーを定数にする
        $checkDeparture = ((intval($inForm['terminal_cd_sel']) & 1) === 1);
        $checkArrival   = ((intval($inForm['terminal_cd_sel']) & 2) === 2);
        $travelId = $inForm['travel_cd_sel'];
        $travelInfo = $this->_TravelService->fetchTravelLimit($db, array('id' => $travelId));
        if ($checkDeparture) {
            //往路の問合せ番号を取得する
            $toiawaseNo = $this->getToiawaseNo();
            $inForm['toiawase_no_departure'] = $toiawaseNo;
            
            if ($travelInfo['charge_flg'] == '1') {
                $departure_charge = $this->_TravelDeliveryChargeAreasService->fetchDeliveryCharge($db, $data + array('travel_terminal_id' => $inForm['travel_departure_cd_sel']), $inForm['req_flg']);
            } else {
                $departure_charge = $this->_TravelDeliveryChargeAreasService->fetchDeliveryChargeNewCharge($db, $data + array('travel_terminal_id' => $inForm['travel_departure_cd_sel']), $inForm['req_flg']);
            }
            $inForm['cargo_collection_ed_time_cd_sel']    = $this->cargo_collection_ed_time_lbls[$inForm['cargo_collection_st_time_cd_sel']];
        } else {
            $inForm['departure_quantity']                 = null;
            $inForm['travel_departure_cd_sel']            = null;
            $inForm['cargo_collection_date_year_cd_sel']  = null;
            $inForm['cargo_collection_date_month_cd_sel'] = null;
            $inForm['cargo_collection_date_day_cd_sel']   = null;
            $inForm['cargo_collection_st_time_cd_sel']    = null;
            $inForm['cargo_collection_ed_time_cd_sel']    = null;
            $inForm['toiawase_no_departure']              = self::TOIAWASE_ALL_ZERO;
        }

        if ($checkArrival) {
            //復路の問合せ番号を取得する
            $toiawaseNo = $this->getToiawaseNo();
            $inForm['toiawase_no_arrival'] = $toiawaseNo;

            if ($travelInfo['charge_flg'] == '1') {
                $arrival_charge = $this->_TravelDeliveryChargeAreasService->fetchDeliveryCharge($db, $data + array('travel_terminal_id' => $inForm['travel_arrival_cd_sel']), $inForm['req_flg']);
            } else {
                $arrival_charge = $this->_TravelDeliveryChargeAreasService->fetchDeliveryChargeNewCharge($db, $data + array('travel_terminal_id' => $inForm['travel_arrival_cd_sel']), $inForm['req_flg']);
            }
        } else {
            $inForm['arrival_quantity']      = null;
            $inForm['travel_arrival_cd_sel'] = null;
            $inForm['toiawase_no_arrival']   = self::TOIAWASE_ALL_ZERO;
        }

        // sawada svnと本番ソースに差異があったため戻す
        //$discount = parent::_getDiscount($checkDeparture, $checkArrival, $db, $this->_TravelService, $this->_CruiseRepeater, $inForm);
        if ($checkDeparture && $checkArrival) {
            $discount = $this->_TravelService->fetchDiscount($db,
                    array('travel_id' => $inForm['travel_cd_sel']));
            if ($inForm['payment_method_cd_sel'] !== '2') {
                $discount['repeater_discount'] = 0;
            } else {
                $cruise_repeater = $this->_CruiseRepeater->fetchCruiseRepeaterLimit($db,
                        array('tel'=>$inForm['tel1'] . $inForm['tel2'] . $inForm['tel3'], 'zip'=>$inForm['zip1'] . $inForm['zip2']));
                if (empty($cruise_repeater['tels'][0])) {
                    $discount['repeater_discount'] = 0;
                }
            }
        }

        $inForm['departure_charge']    = $departure_charge;
        $inForm['arrival_charge']      = $arrival_charge;
        $inForm['round_trip_discount'] = $discount['round_trip_discount'] + $discount['repeater_discount'];

        $inForm['delivery_charge'] = $departure_charge * intval($inForm['departure_quantity'])
                                   + $arrival_charge * intval($inForm['arrival_quantity'])
                                   - ($inForm['round_trip_discount']) * min($inForm['departure_quantity'], $inForm['arrival_quantity']);

        $inForm['authorization_cd'] = '';
        $inForm['receipt_cd']       = '';

        // 2038年問題対応のため、date()ではなくDateTime()を使う
        // DateTime::createFromFormat()はPHP5.3未満で対応していない
        if (method_exists('DateTime', 'createFromFormat')) {
            $date = DateTime::createFromFormat('U.u', gettimeofday(true))
                ->setTimezone(new DateTimeZone('Asia/Tokyo'));
        } else {
            $date = new DateTime();
        }

        $inForm['merchant_datetime'] = $date->format('Y/m/d H:i:s.u');

        //$inForm['payment_order_id'] = 'sagawa-moving_' . $date->format('YmdHis') . '_' . str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');
        //前固定(sagawa-moving)+_申込日時(14桁)+_ランダム文字(36桁)+_往路問合せ番号(12桁)+_復路問合せ番号(12桁)
        $inForm['payment_order_id'] = 'sagawa-moving_' . $date->format('YmdHis') . '_' . str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghij')."_{$inForm['toiawase_no_departure']}_{$inForm['toiawase_no_arrival']}";
        
        //コンビニ決済の場合、入金期限を取得
        if ($inForm['payment_method_cd_sel'] == '1') {
            $inForm['pay_limit'] = $this->_getPayLimit($db, $inForm);
        }
        
        //コールセンターの時、メールが入力しない場合、固定値で設定する
        if (empty($inForm['mail']) && $inForm['req_flg'] == self::PCR_IVR_REQUEST) {
            //$inForm['mail'] = 'tua-lekha@spcom.co.jp';//TODO
            $inForm['mail'] = Sgmov_Component_Config::getCallCenterCommonMailTo();
        }
        
        return $inForm;
    }

    /**
     * 入力フォームの値を元にクレジットカード決済用データを生成します。
     * @param Sgmov_Form_Pcr001-003In $inForm 入力フォーム
     * @return array 決済用データ
     */
    public function _createCheckCreditCardDataByInForm($inForm) {

        // セキュリティコード
        $securityCode = htmlspecialchars($inForm['security_cd']);

        // 要求電文パラメータ値の指定
        $data = new CardAuthorizeRequestDto();

        // 取引ID
        $data->setOrderId($inForm['payment_order_id']);

        // 支払金額
        $data->setAmount(strval($inForm['delivery_charge']));

        // カード番号
        $data->setCardNumber($inForm['card_number']);

        // カード有効期限 MM/YY
        $cardExpire = $inForm['card_expire_month_cd_sel'] . '/' . substr($inForm['card_expire_year_cd_sel'], -2);
        $data->setCardExpire($cardExpire);

        // 与信方法
        $data->setWithCapture('true');

        // 支払オプション
/*
        $jpo1 = $inForm['jpo1'];
        $jpo2 = $inForm['jpo2'];
        switch ($jpo1) {
            case '61';
                $jpo = $jpo1.'C'.$jpo2;
                break;
            case '10';
            case '80';
            default:
                $jpo = $jpo1;
                break;
        }
*/
        // 支払は一回払い固定にする
        $jpo = '10';
        if (isset($jpo)) {
            $data->setJpo($jpo);
        }

        // セキュリティコード
        if (isset($securityCode)) {
            $data->setSecurityCode($securityCode);
        }

        return $data;
    }

    /**
     * 入力フォームの値を元にコンビニ決済用データを生成します。
     * @param Sgmov_Form_Pcr001-003In $inForm 入力フォーム
     * @return array 決済用データ
     */
    public function _createCheckConvenienceStoreDataByInForm($db, $inForm) {

        // 要求電文パラメータ値の指定
        $data = new CvsAuthorizeRequestDto();

        // お支払店舗
        switch ($inForm['convenience_store_cd_sel']) {
            case '1':
                $service_option_type = self::SEVEN_ELEVEN_CODE;
                break;
            case '2':
                $service_option_type = self::E_CONTEXT_CODE;
                break;
            case '3':
                $service_option_type = self::WELL_NET_CODE;
                break;
            default:
                break;
        }
        $data->setServiceOptionType($service_option_type);

        // 取引ID
        $data->setOrderId($inForm['payment_order_id']);

        // 支払金額
        $data->setAmount(strval($inForm['delivery_charge']));

        if ($inForm['req_flg'] == self::PCR_IVR_REQUEST) {
            // 姓
            $data->setName1($inForm['surname_furigana']);
            // 名
            $data->setName2($inForm['forename_furigana']); 
        } else {
            // 姓
            $data->setName1($inForm['surname']);
            // 名
            $data->setName2($inForm['forename']);   
        }
        
        // 電話番号
        $data->setTelNo($inForm['tel1'] . '-' . $inForm['tel2'] . '-' . $inForm['tel3']);

        // 支払期限
        $data->setPayLimit($inForm['pay_limit']);

        // 支払区分
        // リザーブパラメータのため無条件に '0' を設定する
        $data->setPaymentType('0');

        return $data;
    }

    /**
     * 入力フォームの値を元にインサート用データを生成します。
     * @param Sgmov_Form_Pcr001-003In $inForm 入力フォーム
     * @return array インサート用データ
     */
    public function _createInsertDataByInForm($inForm, $id) {

        $cargo_collection_date = null;
        if (!empty($inForm['cargo_collection_date_year_cd_sel'])
                && !empty($inForm['cargo_collection_date_month_cd_sel'])
                && !empty($inForm['cargo_collection_date_day_cd_sel'])) {
            $cargo_collection_date = $inForm['cargo_collection_date_year_cd_sel']
                    . '/' . $inForm['cargo_collection_date_month_cd_sel']
                    . '/' . $inForm['cargo_collection_date_day_cd_sel'];
        }

        $batch_status = '0';
        if (!empty($inForm['merchant_result']) && !empty($inForm['authorization_cd'])) {
            $batch_status = '1';
        }

        $data = array(
            'id'                       => $id,
            'merchant_result'          => $inForm['merchant_result'],
            'merchant_datetime'        => $inForm['merchant_datetime'],
            'batch_status'             => $batch_status,
            'surname'                  => $inForm['req_flg'] != self::PCR_IVR_REQUEST ? $inForm['surname'] : null,
            'forename'                 => $inForm['req_flg'] != self::PCR_IVR_REQUEST ? $inForm['forename'] : null,
            'surname_furigana'         => $inForm['surname_furigana'],
            'forename_furigana'        => $inForm['forename_furigana'],
            'number_persons'           => !empty($inForm['number_persons']) ? $inForm['number_persons'] : null,
            'tel'                      => $inForm['tel1'] . $inForm['tel2'] . $inForm['tel3'],
            'mail'                     => $inForm['mail'],
            'zip'                      => $inForm['zip1'] . $inForm['zip2'],
            'pref_id'                  => $inForm['pref_cd_sel'],
            'address'                  => $inForm['address'],
            'building'                 => $inForm['building'],
            'travel_id'                => $inForm['travel_cd_sel'],
            'room_number'              => $inForm['room_number'],
            'terminal_cd'              => $inForm['terminal_cd_sel'],
            'departure_quantity'       => $inForm['departure_quantity'],
            'arrival_quantity'         => $inForm['arrival_quantity'],
            'travel_departure_id'      => !empty($inForm['travel_departure_cd_sel']) ? $inForm['travel_departure_cd_sel'] : null,
            'cargo_collection_date'    => $cargo_collection_date,
            'cargo_collection_st_time' => !empty($inForm['cargo_collection_st_time_cd_sel']) ? $inForm['cargo_collection_st_time_cd_sel'] . ':00' : null,
            'cargo_collection_ed_time' => !empty($inForm['cargo_collection_ed_time_cd_sel']) ? $inForm['cargo_collection_ed_time_cd_sel'] . ':00' : null,
            'travel_arrival_id'        => !empty($inForm['travel_arrival_cd_sel']) ? $inForm['travel_arrival_cd_sel'] : null,
            'payment_method_cd'        => !empty($inForm['payment_method_cd_sel']) ? $inForm['payment_method_cd_sel'] : null,
            'convenience_store_cd'     => !empty($inForm['convenience_store_cd_sel']) ? $inForm['convenience_store_cd_sel'] : null,
            'authorization_cd'         => $inForm['authorization_cd'],
            'receipt_cd'               => $inForm['receipt_cd'],
            'payment_order_id'         => $inForm['payment_order_id'],
            'toiawase_no_departure'    => $inForm['toiawase_no_departure'],
            'toiawase_no_arrival'      => $inForm['toiawase_no_arrival'],
            'req_flg'                  => $inForm['req_flg'],
            'call_operator_id'          => $inForm['req_flg'] == self::PCR_IVR_REQUEST ? $inForm['call_operator_id_cd_sel'] : null,
        );

        return $data;
    }

    /**
     * 入力フォームの値を元にメール送信用データを生成します。
     * @param Sgmov_Form_Pin001In $inForm 入力フォーム
     * @return array インサート用データ
     */
    public function _createMailDataByInForm($db, $inForm, $id) {

        $prefectures = $this->_PrefectureService->fetchPrefectures($db);

        $travel    = $this->_TravelService->fetchTravel($db, array('travel_agency_id' => $inForm['travel_agency_cd_sel']), $inForm['req_flg'], self::SITE_FLAG);
        $departure = $this->_TravelTerminalService->fetchTravelDeparture($db,array('travel_id' => $inForm['travel_cd_sel']), $inForm['req_flg'], self::SITE_FLAG);
        $arrival   = $this->_TravelTerminalService->fetchTravelArrival($db,array('travel_id' => $inForm['travel_cd_sel']), $inForm['req_flg'], self::SITE_FLAG);

        $cargo_collection_date = '';
        if (!empty($inForm['cargo_collection_date_year_cd_sel'])
                && !empty($inForm['cargo_collection_date_month_cd_sel'])
                && !empty($inForm['cargo_collection_date_day_cd_sel'])) {
            $cargo_collection_date = $inForm['cargo_collection_date_year_cd_sel']
                    . '年' . ltrim($inForm['cargo_collection_date_month_cd_sel'], '0')
                    . '月' . ltrim($inForm['cargo_collection_date_day_cd_sel'], '0')
                    . '日';
        }

        $cargo_collection_st_time = '';
        if (!empty($inForm['cargo_collection_st_time_cd_sel'])) {
            if ($inForm['cargo_collection_st_time_cd_sel'] === '00') {
                $cargo_collection_st_time = '指定なし';
            } else {
                $cargo_collection_st_time = ltrim($inForm['cargo_collection_st_time_cd_sel'], '0') . '時';
            }
        }

        $cargo_collection_ed_time = '';
        if (!empty($inForm['cargo_collection_ed_time_cd_sel'])) {
            if ($inForm['cargo_collection_ed_time_cd_sel'] === '00') {
                $cargo_collection_ed_time = '指定なし';
            } else {
                $cargo_collection_ed_time = ltrim($inForm['cargo_collection_ed_time_cd_sel'], '0') . '時';
            }
        }

        $data = array(
            'surname'                  => $inForm['surname'],
            'forename'                 => $inForm['forename'],
            'surname_furigana'         => $inForm['surname_furigana'],
            'forename_furigana'        => $inForm['forename_furigana'],
            'number_persons'           => $inForm['number_persons'],
            'mail'                     => $inForm['mail'],
            'tel'                      => $inForm['tel1'] . '-' . $inForm['tel2'] . '-' . $inForm['tel3'],
            'zip'                      => $inForm['zip1'] . '-' . $inForm['zip2'],
            'pref_name'                => $prefectures['names'][array_search($inForm['pref_cd_sel'], $prefectures['ids'])],
            'address'                  => $inForm['address'],
            'building'                 => $inForm['building'],
            'travel_name'              => $travel['names'][array_search($inForm['travel_cd_sel'], $travel['ids'])],
            'room_number'              => $inForm['room_number'],
            'departure_quantity'       => $inForm['departure_quantity'],
            'arrival_quantity'         => $inForm['arrival_quantity'],
            'departure_name'           => $departure['names'][array_search($inForm['travel_departure_cd_sel'], $departure['ids'])],
            'cargo_collection_date'    => $cargo_collection_date,
            'cargo_collection_st_time' => $cargo_collection_st_time,
            'cargo_collection_ed_time' => $cargo_collection_ed_time,
            'arrival_name'             => @$arrival['names'][array_search($inForm['travel_arrival_cd_sel'], $arrival['ids'])],
            'amount'                   => '\\' . number_format(ceil((string)($inForm['delivery_charge'] / Sgmov_View_Pcr_Common::CURRENT_TAX))),
            'amount_tax'               => '\\' . number_format($inForm['delivery_charge']),
        );

        // 受付番号
        $data['mail_receipt_cd'] = '';
        if ($inForm['req_flg'] == self::PCR_IVR_REQUEST) {//コールセンターの場合、受付番号に申込番号を設定する
            $data['mail_receipt_cd'] = $id;
        } elseif (!empty($inForm['receipt_cd'])) {
            $data['mail_receipt_cd'] = $inForm['receipt_cd'];
        } elseif (!empty($inForm['authorization_cd'])) {
            $data['mail_receipt_cd'] = $inForm['authorization_cd'];
        }

        // 集荷の往復
        switch ($inForm['terminal_cd_sel']) {
            case '1':
                $data['terminal'] = '往路のみ';
                break;
            case '2':
                $data['terminal'] = '復路のみ';
                break;
            case '3':
                $data['terminal'] = '往復';
                break;
            default:
                $data['terminal'] = '';
                break;
        }
        //問合せ番号を設定する
        $data['toiawase_no_departure'] = $inForm['toiawase_no_departure'];
        $data['toiawase_no_arrival']   = $inForm['toiawase_no_arrival'];
        
        $data['convenience_store_late'] = "";
        // お支払方法
        switch ($inForm['payment_method_cd_sel']) {
            case '1':
                //$data['payment_method'] = 'コンビニ決済';
                $data['payment_method'] = "コンビニ前払い （{$this->convenience_store_lbls[(int)$inForm['convenience_store_cd_sel']]}）";
                $data['convenience_store_late'] .="【受付番号】{$inForm['receipt_cd']}" . PHP_EOL;
                $data['convenience_store_late'] .= !empty($inForm['payment_url']) ? "【払込票URL】".$inForm['payment_url'].PHP_EOL : "";
                $data['convenience_store_late'] .= PHP_EOL;
                $data['convenience_store_late'] .= '※お支払いはお預かり日時の前日17時までに入金していただきますようお願いいたします。'.PHP_EOL;
                $data['convenience_store_late'] .= PHP_EOL;
                $data['convenience_store_late'] .= "※集荷希望日前日までにお支払いお願い致します。\n　お支払い確認後、受付完了となります。";
                break;
            case '2':
                $data['payment_method'] = 'クレジットカード';
                break;
            default:
                $data['payment_method'] = '';
                break;
        }

        return $data;
    }

    /**
     * セッションの値を元に出力フォームを生成します。
     * @param $inForm 入力フォーム
     * @return Sgmov_Form_Pcr004Out 出力フォーム
     */
    public function _createOutFormByInForm($inForm) {

        $outForm = new Sgmov_Form_Pcr004Out();

        $outForm->raw_convenience_store_cd_sel = $inForm['convenience_store_cd_sel'];
        $outForm->raw_mail = $inForm['mail'];
        $outForm->raw_merchant_result = $inForm['merchant_result'];
        $outForm->raw_payment_method_cd_sel = $inForm['payment_method_cd_sel'];
        $outForm->raw_payment_url = isset($inForm['payment_url']) ? $inForm['payment_url'] : null;
        $outForm->raw_receipt_cd = $inForm['receipt_cd'];
/*
        $payment_limit = '';
        if (!empty($inForm['cargo_collection_date_year_cd_sel'])
                && !empty($inForm['cargo_collection_date_month_cd_sel'])
                && !empty($inForm['cargo_collection_date_day_cd_sel'])) {
            $date = new DateTime($inForm['cargo_collection_date_year_cd_sel']
                    . '/' . $inForm['cargo_collection_date_month_cd_sel']
                    . '/' . $inForm['cargo_collection_date_day_cd_sel']);
            switch ($date->format('N')) {
                case '1': // 月
                case '2': // 火
                    $date->modify('-4 day');
                    break;
                case '3': // 水
                case '4': // 木
                case '5': // 金
                case '6': // 土
                    $date->modify('-2 day');
                    break;
                case '7': // 日
                    $date->modify('-3 day');
                    break;
                default:
                    break;
            }
            $payment_limit = $date->format('Y年m月d日');
            switch ($date->format('N')) {
                case '1':
                    $payment_limit .= '（月）';
                    break;
                case '2':
                    $payment_limit .= '（火）';
                    break;
                case '3':
                    $payment_limit .= '（水）';
                    break;
                case '4':
                    $payment_limit .= '（木）';
                    break;
                case '5':
                    $payment_limit .= '（金）';
                    break;
                case '6':
                    $payment_limit .= '（土）';
                    break;
                case '7':
                    $payment_limit .= '（日）';
                    break;
                default:
                    break;
            }
        }
        $outForm->raw_payment_limit = $payment_limit;
*/
        return $outForm;
    }

    /**
     * システム管理者へ失敗メールを送信
     * @return
     */
    public function errorInformation($parm = array())
    {

        // システム管理者メールアドレスを取得する。
        $mail_to = Sgmov_Component_Config::getLogMailTo();
        //テンプレートメールを送信する。
        Sgmov_Component_Mail::sendTemplateMail($parm, dirname(__FILE__) . '/../../mail_template/pcr_error.txt', $mail_to);
    }

    /**
     * 決済用データの入力値の妥当性検査を行います。
     * @param $checkForm 決済用データ
     * @param Sgmov_Form_Pcr003In $inForm 入力フォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _transact($checkForm, $inForm) {
Sgmov_Component_Log::debug($checkForm);Sgmov_Component_Log::debug($inForm);
        // VeriTrans3G MerchantDevelopmentKitマーチャントCCID、マーチャントパスワード設定
        switch ($inForm['payment_method_cd_sel']) {
            case '2': // クレジットカード決済
                $props = array(
                    'merchant_ccid'       => Sgmov_Component_Config::getMdkCreditCardMerchantCcId(),
                    'merchant_secret_key' => Sgmov_Component_Config::getMdkCreditCardMerchantSecretKey(),
                );
                break;
            case '1': // コンビニ決済
                $props = array(
                    'merchant_ccid'       => Sgmov_Component_Config::getMdkConvenienceStoreMerchantCcId(),
                    'merchant_secret_key' => Sgmov_Component_Config::getMdkConvenienceStoreMerchantSecretKey(),
                );
                break;
            default:
                $props = null;
                break;
        }
Sgmov_Component_Log::debug($props);
        // 決済の実行
        $transaction = new TGMDK_Transaction();
        $response = $transaction->execute($checkForm, $props);
Sgmov_Component_Log::debug($response);
        if (!isset($response)) {
            // 予期しない例外
            $inForm['merchant_result'] = '0';
            Sgmov_Component_Log::debug('予期しない例外');
            $this->errorInformation(array("payment_order_id" => "","errMsg" => ""));
        } else {
            // 想定応答の取得
            Sgmov_Component_Log::debug('想定応答の取得');

            // 取引ID取得
            $resultOrderId = $response->getOrderId();
            Sgmov_Component_Log::debug($resultOrderId);

            // 結果コード取得
            $resultStatus = $response->getMStatus();
            Sgmov_Component_Log::debug($resultStatus);

            // 詳細コード取得
            $resultCode = $response->getVResultCode();
            Sgmov_Component_Log::debug($resultCode);

            // エラーメッセージ取得
            $errorMessage = $response->getMerrMsg();
            Sgmov_Component_Log::debug($errorMessage);

            switch ($resultStatus) {
                case 'success';
                    // 成功
                    $inForm['merchant_result'] = '1';
                    Sgmov_Component_Log::debug('成功');
                    break;
                case 'pending';
                    // 失敗
                    $inForm['merchant_result'] = '0';
                    Sgmov_Component_Log::debug('失敗');
                    break;
                case 'failure';
                    $this->errorInformation(array("payment_order_id" => $inForm['payment_order_id'],"errMsg" => $errorMessage));
                default:
                    // 失敗
                    $inForm['merchant_result'] = '0';
                    Sgmov_Component_Log::debug('失敗');
                    break;
            }

            switch ($inForm['payment_method_cd_sel']) {
                case '2': // クレジットカード決済
                    // 承認番号
                    $inForm['authorization_cd'] = $response->getResAuthCode();
                    Sgmov_Component_Log::debug($inForm['authorization_cd']);
                    break;
                case '1': // コンビニ決済
                    // 受付番号
                    $inForm['receipt_cd'] = $response->getReceiptNo();
                    Sgmov_Component_Log::debug($inForm['receipt_cd']);

                    // 払込票URL
                    $inForm['payment_url'] = $response->getHaraikomiUrl();
                    Sgmov_Component_Log::debug($inForm['payment_url']);
                    break;
                default:
                    break;
            }

        }

        return $inForm;
    }
    
     /**
     * コミケテーブル.請求書問番取得.
     * @return type
     */
    private function getToiawaseNo() {
        $toiawaseNoInfo = $this->_gyomuApiService->getToiawaseNo();
        if (@$toiawaseNoInfo['result'] != '0') { // 0は取得成功
            $errid = date('YmdHis') . '_' . str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789');
            
            Sgmov_Component_Log::err("======================================================================================");
            Sgmov_Component_Log::err("業務連携請求書問番取得時エラー");
            Sgmov_Component_Log::err("▼▼▼ ERROR_ID ▼▼▼");
            @Sgmov_Component_Log::err($errid);
            Sgmov_Component_Log::err("▼▼▼ レスポンス情報 ▼▼▼");
            @Sgmov_Component_Log::err($toiawaseNoInfo);
            Sgmov_Component_Log::err("▼▼▼ session 情報 ▼▼▼");
            @Sgmov_Component_Log::err($_SESSION);
            Sgmov_Component_Log::err("▼▼▼ server 情報 ▼▼▼");
            @Sgmov_Component_Log::err($_SERVER);
            Sgmov_Component_Log::err("▼▼▼ env 情報 ▼▼▼");
            @Sgmov_Component_Log::err($_ENV);
            Sgmov_Component_Log::err("======================================================================================");
                        
            // メールを送信する。
            // システム管理者メールアドレスを取得する。
            $mailTemplateList = array(
                "/common_error_event_webapi.txt",
            );
            $mailData = array();
            $mailData['errMsg'] = "業務連携請求書問番取得時にエラーが発生しました。" . PHP_EOL;
            $mailData['errMsg'] .=  "詳細は ERROR_ID をもとに ログ情報を確認してください。" . PHP_EOL . PHP_EOL;
            $mailData['errMsg'] .=  "[ERROR_ID]" . PHP_EOL;
            $mailData['errMsg'] .=  $errid . PHP_EOL . PHP_EOL;
            $mailData['errMsg'] .=  "[レスポンス情報]" . PHP_EOL;
            $mailData['errMsg'] .= @var_export($toiawaseNoInfo, true) . PHP_EOL . PHP_EOL;
            $mailData['errMsg'] .=  "[session情報]" . PHP_EOL;
            $mailData['errMsg'] .= @var_export($_SESSION, true) . PHP_EOL . PHP_EOL;
            $mailData['errMsg'] .=  "[server情報]" . PHP_EOL;
            $mailData['errMsg'] .= @var_export($_SERVER, true) . PHP_EOL . PHP_EOL;
            $mailData['errMsg'] .=  "[env情報]" . PHP_EOL;
            $mailData['errMsg'] .= @var_export($_ENV, true) . PHP_EOL . PHP_EOL;
            $mailData['errMsg'] = mb_substr($mailData['errMsg'] , 0, 1000);
            
            $mailTo = Sgmov_Component_Config::getLogMailTo();
            $objMail = new Sgmov_Service_CenterMail();
            $objMail->_sendThankYouMail($mailTemplateList, $mailTo, $mailData);
            
            $title = urlencode("システムエラー");
            $message = urlencode("エラーが発生しました。時間がたってからもう一度やりなおしてください。");
            Sgmov_Component_Redirect::redirectPublicSsl("/pcr/error?t={$title}&m={$message}");
        }
        return $toiawaseNoInfo['toiawaseNo'];
    }
    
    private function createDataForRequestIVR($db, $inForm, $passwordIVR) {
        //get data operator_phone_number from operator_id
        $phoneNumber = $this->_TravelService->fetchTravelPhoneNumberByOperatorId($db, $inForm['call_operator_id_cd_sel']);        
        $data = array(
            "telNo" 	=> !empty($phoneNumber['operator_phone_number']) ? str_replace("-", "", $phoneNumber['operator_phone_number']) : null, 
            "password" 	=> $passwordIVR,
            "orderId" 	=> $inForm['payment_order_id'], 
            "amount"	=> $inForm['delivery_charge'],
        );
        
        return $data;
    }
    private function sendPaymentInforRequestForIVRService($url, $data) {
        $requestData = http_build_query($data, "", "&");
         //header
        $header = array(
            "Content-Type: application/x-www-form-urlencoded",
            "Content-Length: ".strlen($requestData), 
        );
        $context = array(
            "http" => array(
                "method"  => "POST",
                "header"  => implode("\r\n", $header),
                "content" => $requestData,
            ),
        );
        $context2 = stream_context_create($context);
        $resultData = array();
        $isError = false;
        try {
            Sgmov_Component_Log::info('▼ IVR 決済情報連携API リクエスト :');
            Sgmov_Component_Log::info($data);
            Sgmov_Component_Log::info($requestData);
            $responce = file_get_contents($url, false, $context2);
            Sgmov_Component_Log::info('▼ IVR 決済情報連携API レスポンス :');
            Sgmov_Component_Log::info($responce);
            
        } catch (Exception $ex) {
            $isError = true;
            $resultData['result'] = 'NG';
            $resultData['message'] = "Error: ".$ex->getCode()."(".$ex->getMessage().")" ;
            Sgmov_Component_Log::error($resultData['message']);
        }
        if (!$isError) {
            preg_match('/HTTP\/1\.[0|1|x] ([0-9]{3})/', $http_response_header[0], $matches);
            $statusCode = $matches[1];
            Sgmov_Component_Log::info($statusCode);
            switch ($statusCode) {
                case '200':
                    // レスポンスコード = 200の場合 ///////////////////////////////////////////////////////////////////
                    Sgmov_Component_Log::info("https Status=200");
                    if (strpos($responce, "mstatus=success") === false) {
                        $resultData['result'] = 'NG';
                        $resultData['message'] = "IVR決済連携失敗：" . str_replace("mErrMsg=","", substr($responce, strpos($responce, "mErrMsg"))); 
                        Sgmov_Component_Log::info($resultData['message']);
                    } else {
                        $resultData['result'] = 'OK';
                        Sgmov_Component_Log::info("sendPaymentInforRequestForIVRService　OK");
                    }
                    break;
                case '404':
                default:
                    // 404の場合
                    $resultData['result'] = 'NG';
                    $resultData['message'] = "IVR決済情報を連携失敗しました！";
                    Sgmov_Component_Log::info("sendPaymentInforRequestForIVRService　error!");
                    break;
            }
        }
        Sgmov_Component_Log::debug($resultData);
        return $resultData;
    }
    
}
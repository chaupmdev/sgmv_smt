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
Sgmov_Lib::useView('pcr/Common');
Sgmov_Lib::useForms(array('Error', 'PcrSession', 'Pcr003Out'));
/**#@-*/

/**
 * 旅客手荷物受付サービスのお申し込み確認画面を表示します。
 * @package    View
 * @subpackage PCR
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pcr_Confirm extends Sgmov_View_Pcr_Common {

    /**
     * 共通サービス
     * @var Sgmov_Service_AppCommon
     */
    public $_appCommon;

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
     * ツアー会社サービス
     * @var Sgmov_Service_TravelAgency
     */
    private $_TravelAgencyService;

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
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_appCommon                        = new Sgmov_Service_AppCommon();
        $this->_CruiseRepeater                   = new Sgmov_Service_CruiseRepeater();
        $this->_PrefectureService                = new Sgmov_Service_Prefecture();
        $this->_TravelAgencyService              = new Sgmov_Service_TravelAgency();
        $this->_TravelService                    = new Sgmov_Service_Travel();
        $this->_TravelTerminalService            = new Sgmov_Service_TravelTerminal();
        $this->_TravelDeliveryChargeAreasService = new Sgmov_Service_TravelDeliveryChargeAreas();
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
        $featureId = self::FEATURE_ID;
        if (strpos($_SERVER['PHP_SELF'], 'confirm_call')) {
            $featureId = self::FEATURE_ID_IVR;
        }
        $sessionForm = $session->loadForm($featureId);

        if (!isset($sessionForm) || $sessionForm->status != self::VALIDATION_SUCCEEDED) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
        }

        // セッション情報を元に出力情報を設定
        $outForm = $this->_createOutFormByInForm($sessionForm->in, $db);

        // チケットを発行
        $ticket = $session->publishTicket($featureId, self::GAMEN_ID_PCR003);

        return array(
            'ticket'  => $ticket,
            'outForm' => $outForm,
        );
    }
    /**
     * 入力フォームの値を元に出力フォームを生成します。
     * @param Sgmov_Form_Pcr001In $inForm 入力フォーム
     * @return Sgmov_Form_Pcr003Out 出力フォーム
     */
    public function _createOutFormByInForm($inForm, $db) {

        $outForm = new Sgmov_Form_Pcr003Out();

        // TODO オブジェクトから値を直接取得できるよう修正する
        // オブジェクトから取得できないため、配列に型変換
        $inForm = (array)$inForm;

        // 送料
        $data = array(
            'prefecture_id' => $inForm['pref_cd_sel'],
        );
        $departure_charge = 0;
        $arrival_charge   = 0;

        // yamagami svnと本番ソースに差異があったため戻す
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
            if ($travelInfo['charge_flg'] == '1') {
                $departure_charge = $this->_TravelDeliveryChargeAreasService->fetchDeliveryCharge($db, $data + array('travel_terminal_id' => $inForm['travel_departure_cd_sel']), $inForm['req_flg']);
            } else {
                $departure_charge = $this->_TravelDeliveryChargeAreasService->fetchDeliveryChargeNewCharge($db, $data + array('travel_terminal_id' => $inForm['travel_departure_cd_sel']), $inForm['req_flg']);
            }
        }

        if ($checkArrival) {
            if ($travelInfo['charge_flg'] == '1') {
                $arrival_charge = $this->_TravelDeliveryChargeAreasService->fetchDeliveryCharge($db, $data + array('travel_terminal_id' => $inForm['travel_arrival_cd_sel']), $inForm['req_flg']);
            } else {
                $arrival_charge = $this->_TravelDeliveryChargeAreasService->fetchDeliveryChargeNewCharge($db, $data + array('travel_terminal_id' => $inForm['travel_arrival_cd_sel']), $inForm['req_flg']);
            }
        }

        // yamagami svnと本番ソースに差異があったため戻す
        //$discount = parent::_getDiscount($checkDeparture, $checkArrival, $db, $this->_TravelService, $this->_CruiseRepeater, $inForm);
        if ($checkDeparture && $checkArrival) {
        	$discount = $this->_TravelService->fetchDiscount($db,
        			array('travel_id'=>$inForm['travel_cd_sel']));
        	if (empty($discount['round_trip_discount'])) {
        		$discount['round_trip_discount'] = 0;
        	}
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

        $outForm->raw_delivery_charge = $this->_appCommon->getTani(
            number_format(
                $departure_charge * intval($inForm['departure_quantity'])
                + $arrival_charge * intval($inForm['arrival_quantity'])
                - ($discount['round_trip_discount'] + $discount['repeater_discount']) * min($inForm['departure_quantity'], $inForm['arrival_quantity'])
            ),
            '円（税込）'
        );
        $outForm->raw_repeater_discount = $discount['repeater_discount'] * min($inForm['departure_quantity'], $inForm['arrival_quantity']);

        $prefectures  = $this->_PrefectureService->fetchPrefectures($db);
        $travelAgency = $this->_TravelAgencyService->fetchTravelAgency($db, $inForm['req_flg'], self::SITE_FLAG);
        $travel = array();
        if (!empty($inForm['travel_agency_cd_sel'])) {
            $travel = $this->_TravelService->fetchTravel($db, array('travel_agency_id' => $inForm['travel_agency_cd_sel']), $inForm['req_flg'], self::SITE_FLAG);
        }
        $convenience = ($inForm['payment_method_cd_sel'] === '1');
        $departure = array();
        $arrival = array();
        if (!empty($inForm['travel_cd_sel'])) {
            $departure = $this->_TravelTerminalService->fetchTravelDeparture($db,array('travel_id'=>$inForm['travel_cd_sel']), $inForm['req_flg'], self::SITE_FLAG, $convenience);
            $arrival = $this->_TravelTerminalService->fetchTravelArrival($db,array('travel_id'=>$inForm['travel_cd_sel']), $inForm['req_flg'], self::SITE_FLAG, $convenience);
        }
        //$hour = $this->_fetchTime(0, 23);
        //$cargo_collection_st_time_lbls = $hour['names'];
        //$cargo_collection_ed_time_lbls = $hour['names'];

        $outForm->raw_surname  = $inForm['surname'];
        $outForm->raw_forename = $inForm['forename'];
        $outForm->raw_surname_furigana  = $inForm['surname_furigana'];
        $outForm->raw_forename_furigana = $inForm['forename_furigana'];
        $outForm->raw_number_persons = $this->_appCommon->getTani($inForm['number_persons'], '名');
        if (empty($inForm['tel1'])) {
            $outForm->raw_tel = '';
        } else {
            $outForm->raw_tel = $inForm['tel1'] . '-' . $inForm['tel2'] . '-' . $inForm['tel3'];
        }
        $outForm->raw_mail = $inForm['mail'];
        if (empty($inForm['zip1'])) {
            $outForm->raw_zip = '';
        } else {
            $outForm->raw_zip = $inForm['zip1'] . '-' . $inForm['zip2'];
        }
        $outForm->raw_pref     = $prefectures['names'][array_search($inForm['pref_cd_sel'], $prefectures['ids'])];
        $outForm->raw_address  = $inForm['address'];
        $outForm->raw_building = $inForm['building'];
        
        $outForm->raw_travel_agency = $travelAgency['names'][array_search($inForm['travel_agency_cd_sel'], $travelAgency['ids'])];
        if ($inForm['req_flg'] == self::PCR_IVR_REQUEST) {
            $outForm->raw_call_operator_id = $inForm['call_operator_id_cd_sel'];
        }
        
        $outForm->raw_travel        = $travel['names'][array_search($inForm['travel_cd_sel'], $travel['ids'])];
        $outForm->raw_room_number = $inForm['room_number'];
        $outForm->raw_terminal = $this->terminal_lbls[$inForm['terminal_cd_sel']];

        // TODO マジックナンバーを定数にする
        $outForm->raw_departure_exist_flag = ((intval($inForm['terminal_cd_sel']) & 1) === 1);
        $outForm->raw_arrival_exist_flag   = ((intval($inForm['terminal_cd_sel']) & 2) === 2);
        $outForm->raw_departure_quantity = $this->_appCommon->getTani($inForm['departure_quantity'], '個', '往路');
        $outForm->raw_arrival_quantity   = $this->_appCommon->getTani($inForm['arrival_quantity'], '個', '復路');
        if (!empty($departure['ids'])) {
            $outForm->raw_travel_departure = $departure['names'][array_search($inForm['travel_departure_cd_sel'], $departure['ids'])];
        } else {
            $outForm->raw_travel_departure = '';
        }

        $outForm->raw_cargo_collection_date    = $this->_appCommon->getYmd($inForm['cargo_collection_date_year_cd_sel'] . $inForm['cargo_collection_date_month_cd_sel'] . $inForm['cargo_collection_date_day_cd_sel']);
        if (!empty($inForm['cargo_collection_st_time_cd_sel'])) {
            if ($inForm['cargo_collection_st_time_cd_sel'] === '00') {
                $outForm->raw_cargo_collection_st_time = '時間帯指定なし';
            } else {
                $outForm->raw_cargo_collection_st_time = $this->_appCommon->getTani($inForm['cargo_collection_st_time_cd_sel'], '時');
            }
        }
        //$outForm->raw_cargo_collection_ed_time = $this->_appCommon->getTani($inForm['cargo_collection_ed_time_cd_sel'], '時');
        if (!empty($this->cargo_collection_ed_time_lbls[$inForm['cargo_collection_st_time_cd_sel']]) && $inForm['cargo_collection_st_time_cd_sel'] !== '00') {
            $outForm->raw_cargo_collection_ed_time = $this->_appCommon->getTani($this->cargo_collection_ed_time_lbls[$inForm['cargo_collection_st_time_cd_sel']], '時');
        }

        if (!empty($arrival['ids'])) {
            $outForm->raw_travel_arrival = $arrival['names'][array_search($inForm['travel_arrival_cd_sel'], $arrival['ids'])];
        } else {
            $outForm->raw_travel_arrival = '';
        }

        $outForm->raw_payment_method_cd_sel = $inForm['payment_method_cd_sel'];

        $convenience_store_cd = isset($inForm['convenience_store_cd_sel']) ? $inForm['convenience_store_cd_sel'] : null;
        if (!empty($this->convenience_store_lbls[$convenience_store_cd])) {
            $outForm->raw_convenience_store = $this->convenience_store_lbls[$convenience_store_cd];
        }

        if ($inForm['payment_method_cd_sel'] === '2' && $inForm['req_flg'] == '2') {
            $outForm->raw_card_number = str_repeat('*', strlen($inForm['card_number']) - 4).substr($inForm['card_number'], -4);
            $outForm->raw_card_expire = $this->_appCommon->getTani($inForm['card_expire_year_cd_sel'], '年').$this->_appCommon->getTani($inForm['card_expire_month_cd_sel'], '月');
            $outForm->raw_security_cd = $inForm['security_cd'];
        }
        
        $outForm->raw_req_flg = $inForm['req_flg'];
        return $outForm;
    }
}
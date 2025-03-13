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
Sgmov_Lib::useView('pct/Common');
Sgmov_Lib::useForms(array('Error', 'PctSession', 'Pcr001Out'));
/**#@-*/

/**
 * 旅客手荷物受付サービスのお申し込み入力画面を表示します。
 * @package    View
 * @subpackage PCR
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pct_Input extends Sgmov_View_Pct_Common {

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
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_appCommon             = new Sgmov_Service_AppCommon();
        $this->_PrefectureService     = new Sgmov_Service_Prefecture();
        $this->_TravelAgencyService   = new Sgmov_Service_TravelAgency();
        $this->_TravelService         = new Sgmov_Service_Travel();
        $this->_TravelTerminalService = new Sgmov_Service_TravelTerminal();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * セッションに情報があるかどうかを確認
     * </li><li>
     * 情報有り
     *   <ol><li>
     *   セッション情報を元に出力情報を作成
     *   </li></ol>
     * </li><li>
     * 情報無し
     *   <ol><li>
     *   出力情報を設定
     *   </li></ol>
     * </li><li>
     * テンプレート用の値をセット
     * </li><li>
     * チケット発行
     * </li></ol>
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['ticket']:チケット文字列
     * </li><li>
     * ['outForm']:出力フォーム
     * </li><li>
     * ['errorForm']:エラーフォーム
     * </li></ul>
     */
    public function executeInner() {
        // セッションに情報があるかどうかを確認
        $session = Sgmov_Component_Session::get();

        // 情報
        $featureId = self::FEATURE_ID;
        if (strpos($_SERVER['PHP_SELF'], 'input_call')) {
            $featureId = self::FEATURE_ID_IVR;
        } 
        $sessionForm = $session->loadForm($featureId);

        $inForm    = NULL;
        $errorForm = NULL;
        if (isset($sessionForm)) {
            $inForm    = $sessionForm->in;
            $errorForm = $sessionForm->error;
            // セッション破棄
            $sessionForm->error = NULL;
        }
        $outForm = $this->_createOutFormByInForm($inForm);

        // チケット発行
        
        $ticket = $session->publishTicket($featureId, self::GAMEN_ID_PCR001);
        return array(
            'ticket'    => $ticket,
            'outForm'   => $outForm,
            'errorForm' => $errorForm,
            'featureId' => $featureId
        );
    }

    /**
     * 入力フォームの値を出力フォームを生成します。
     * @param Sgmov_Form_Pve001In $inForm 入力フォーム
     * @return Sgmov_Form_Pve001Out 出力フォーム
     */
    private function _createOutFormByInForm($inForm) {

        $outForm = new Sgmov_Form_Pcr001Out();

        // TODO オブジェクトから値を直接取得できるよう修正する
        // オブジェクトから取得できないため、配列に型変換
        $inForm = (array)$inForm;
        // テンプレート用の値をセット
        $db = Sgmov_Component_DB::getPublic();
        
        $prefectures  = $this->_PrefectureService->fetchPrefectures($db);
        if (strpos($_SERVER['PHP_SELF'], 'input_call')) {
            $travelAgency = $this->_TravelAgencyService->fetchTravelAgency($db, self::PCR_IVR_REQUEST, self::SITE_FLAG);
        } else {
            $travelAgency = $this->_TravelAgencyService->fetchTravelAgency($db, self::PCR_WEB_REQUEST, self::SITE_FLAG);
        }
        
        
        
        if (@empty($travelAgency['ids'])) {
            //$title = urlencode("旅客手荷物をお申込できるクルーズサービスは現在ありません");
            //$message = urlencode("");
            //Sgmov_Component_Redirect::redirectPublicSsl("/pct/error?t={$title}&m={$message}");
            // SGH新サイトリリースによるリンク先変更対応
            Sgmov_Component_Redirect::redirectPublicSsl("/error.html");
        }
        if (!empty($inForm['travel_agency_cd_sel'])) {
            
            $travel = $this->_TravelService->fetchTravel($db, array('travel_agency_id' => $inForm['travel_agency_cd_sel']), $inForm['req_flg'], self::SITE_FLAG);
        } else {
            // Noticeエラー対策
            $travel = array('ids' => null, 'names' => null);
        }
        $travelPhones =  $this->_TravelService->fetchTravelOperator($db);
        
        // 復路非表示TravelAgancy.id list 
        $dispnoneArrivalTravelAgencyIdList = array();
        foreach ($travelAgency['dcruse_flgs'] as $key => $val) {
            if ($val == '1') {
                $dispnoneArrivalTravelAgencyIdList[] = $travelAgency['ids'][$key];
            }
        }
        $outForm->raw_dispnone_arrival_travel_agency_id_list = $dispnoneArrivalTravelAgencyIdList;
        
        if (!empty($inForm['travel_cd_sel'])) {
            $departure = $this->_TravelTerminalService->fetchTravelDeparture($db, array('travel_id' => $inForm['travel_cd_sel']), $inForm['req_flg'], self::SITE_FLAG);
            $arrival   = $this->_TravelTerminalService->fetchTravelArrival($db, array('travel_id' => $inForm['travel_cd_sel']), $inForm['req_flg'], self::SITE_FLAG);
        } else {
            // Noticeエラー対策
            $departure = array('ids' => null, 'names' => null, 'dates' => null);
            $arrival   = array('ids' => null, 'names' => null);
        }

        // 都道府県
        array_shift($prefectures['ids']);
        array_shift($prefectures['names']);
        $outForm->raw_pref_cds  = $prefectures['ids'];
        $outForm->raw_pref_lbls = $prefectures['names'];

        // コールセンター電話番号
        $outForm->raw_call_operator_id_cds  = $travelPhones['ids'];
        $outForm->raw_call_operator_id_lbls = $travelPhones['names'];


        // 船名
        $outForm->raw_travel_agency_cds  = $travelAgency['ids'];
        $outForm->raw_travel_agency_lbls = $travelAgency['names'];

        // ツアー名(乗船日)
        $outForm->raw_travel_cds  = $travel['ids'];
        $outForm->raw_travel_lbls = $travel['names'];

        // 出発地
        $outForm->raw_travel_departure_cds   = $departure['ids'];
        $outForm->raw_travel_departure_lbls  = $departure['names'];
        $outForm->raw_travel_departure_dates = $departure['dates'];

        // 到着地
        $outForm->raw_travel_arrival_cds  = $arrival['ids'];
        $outForm->raw_travel_arrival_lbls = $arrival['names'];

        $date = new DateTime();
        //$years  = $this->_appCommon->getYears(date('Y'), Sgmov_Service_AppCommon::INPUT_MOVEYTIYEAR_CNT, false);
        $years  = $this->_appCommon->getYears($date->format('Y'), 0, false);
        $months = $this->_appCommon->months;
        $days   = $this->_appCommon->days;
        array_shift($months);
        array_shift($days);
        $outForm->raw_cargo_collection_date_year_cds   = $years;
        $outForm->raw_cargo_collection_date_year_lbls  = $years;
        $outForm->raw_cargo_collection_date_month_cds  = $months;
        $outForm->raw_cargo_collection_date_month_lbls = $months;
        $outForm->raw_cargo_collection_date_day_cds    = $days;
        $outForm->raw_cargo_collection_date_day_lbls   = $days;

        //$outForm->raw_delivery_day_year_cds   = $years;
        //$outForm->raw_delivery_day_year_lbls  = $years;
        //$outForm->raw_delivery_day_month_cds  = $months;
        //$outForm->raw_delivery_day_month_lbls = $months;
        //$outForm->raw_delivery_day_day_cds    = $days;
        //$outForm->raw_delivery_day_day_lbls   = $days;

        $cargo_collection_st_time = $this->cargo_collection_st_time_lbls;
        $outForm->raw_cargo_collection_st_time_cds  = array_keys($cargo_collection_st_time);
        $outForm->raw_cargo_collection_st_time_lbls = array_values($cargo_collection_st_time);
        //$hour = $this->_fetchTime(0, 23);
        //$outForm->raw_cargo_collection_st_time_cds  = $hour['ids'];
        //$outForm->raw_cargo_collection_st_time_lbls = $hour['ids'];
        //$outForm->raw_cargo_collection_ed_time_cds  = $hour['ids'];
        //$outForm->raw_cargo_collection_ed_time_lbls = $hour['ids'];
        //$outForm->raw_delivery_time_cds  = $hour['ids'];
        //$outForm->raw_delivery_time_lbls = $hour['names'];
        //$minute = $this->_fetchTime(0, 30, 30);
        //$outForm->raw_cargo_collection_st_minute_cds  = $minute['ids'];
        //$outForm->raw_cargo_collection_st_minute_lbls = $minute['names'];
        //$outForm->raw_cargo_collection_ed_minute_cds  = $minute['ids'];
        //$outForm->raw_cargo_collection_ed_minute_lbls = $minute['names'];
        //$outForm->raw_delivery_minute_cds  = $minute['ids'];
        //$outForm->raw_delivery_minute_lbls = $minute['names'];

        $convenience_store = $this->convenience_store_lbls;
        $outForm->raw_convenience_store_cds  = array_keys($convenience_store);
        $outForm->raw_convenience_store_lbls = array_values($convenience_store);
        if (isset($_SESSION['PCR_CALL_CENTER'])) {
            $outForm->raw_call_operator_id_cd_sel = $_SESSION['PCR_CALL_CENTER']; 
        }
        
        if (empty($inForm)) {
            return $outForm;
        }

        $outForm->raw_surname  = $inForm['surname'];
        $outForm->raw_forename = $inForm['forename'];
        $outForm->raw_surname_furigana  = $inForm['surname_furigana'];
        $outForm->raw_forename_furigana = $inForm['forename_furigana'];

        $outForm->raw_number_persons = $inForm['number_persons'];

        $outForm->raw_tel1 = $inForm['tel1'];
        $outForm->raw_tel2 = $inForm['tel2'];
        $outForm->raw_tel3 = $inForm['tel3'];

        $outForm->raw_mail        = $inForm['mail'];
        $outForm->raw_retype_mail = $inForm['retype_mail'];

        $outForm->raw_zip1        = $inForm['zip1'];
        $outForm->raw_zip2        = $inForm['zip2'];
        $outForm->raw_pref_cd_sel = $inForm['pref_cd_sel'];
        $outForm->raw_address     = $inForm['address'];
        $outForm->raw_building    = $inForm['building'];

        $outForm->raw_travel_agency_cd_sel    = $inForm['travel_agency_cd_sel'];
        $outForm->raw_call_operator_id_cd_sel  = strpos($_SERVER['PHP_SELF'], 'input_call') ? $inForm['call_operator_id_cd_sel'] : "";
        $outForm->raw_travel_cd_sel           = $inForm['travel_cd_sel'];
        $outForm->raw_room_number             = $inForm['room_number'];
        $outForm->raw_terminal_cd_sel         = $inForm['terminal_cd_sel'];
        $outForm->raw_departure_quantity      = $inForm['departure_quantity'];
        $outForm->raw_arrival_quantity        = $inForm['arrival_quantity'];
        $outForm->raw_travel_departure_cd_sel = $inForm['travel_departure_cd_sel'];

        $outForm->raw_cargo_collection_date_year_cd_sel  = $inForm['cargo_collection_date_year_cd_sel'];
        $outForm->raw_cargo_collection_date_month_cd_sel = $inForm['cargo_collection_date_month_cd_sel'];
        $outForm->raw_cargo_collection_date_day_cd_sel   = $inForm['cargo_collection_date_day_cd_sel'];
        $outForm->raw_cargo_collection_st_time_cd_sel    = $inForm['cargo_collection_st_time_cd_sel'];
        //$outForm->raw_cargo_collection_st_minute_cd_sel  = $inForm['cargo_collection_st_minute_cd_sel'];
        $outForm->raw_cargo_collection_ed_time_cd_sel    = $inForm['cargo_collection_ed_time_cd_sel'];
        //$outForm->raw_cargo_collection_ed_minute_cd_sel  = $inForm['cargo_collection_ed_minute_cd_sel'];

        $outForm->raw_travel_arrival_cd_sel = $inForm['travel_arrival_cd_sel'];

        //$outForm->raw_delivery_day_year_cd_sel  = $inForm['delivery_day_year_cd_sel'];
        //$outForm->raw_delivery_day_month_cd_sel = $inForm['delivery_day_month_cd_sel'];
        //$outForm->raw_delivery_day_day_cd_sel   = $inForm['delivery_day_day_cd_sel'];
        //$outForm->raw_delivery_time_cd_sel      = $inForm['delivery_time_cd_sel'];
        //$outForm->raw_delivery_minute_cd_sel    = $inForm['delivery_minute_cd_sel'];

        $outForm->raw_payment_method_cd_sel    = $inForm['payment_method_cd_sel'];
        $outForm->raw_convenience_store_cd_sel = $inForm['convenience_store_cd_sel'];
        
        $outForm->raw_req_flg = $inForm['req_flg'];
        
        $outForm->raw_chb_agreement = isset($inForm['chb_agreement']) ? $inForm['chb_agreement']:"";
        
        return $outForm;
    }
}
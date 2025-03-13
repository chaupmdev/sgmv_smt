<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('atc/Common');
Sgmov_Lib::useForms(array('Error', 'Atc022Out'));
/**#@-*/

/**
 * ツアー配送料金マスタ入力画面を表示します。
 * @package    View
 * @subpackage ATC
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Atc_Copy extends Sgmov_View_Atc_Common {

    /**
     * ログインサービス
     * @var Sgmov_Service_Login
     */
    public $_loginService;

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
     * ツアーエリアサービス
     * @var Sgmov_Service_TravelProvinces
     */
    private $_TravelProvincesService;

    /**
     * ツアー配送料金サービス
     * @var Sgmov_Service_TravelDeliveryCharge
     */
    private $_TravelDeliveryChargeService;

    /**
     * ツアー配送料金エリアサービス
     * @var Sgmov_Service_TravelDeliveryChargeAreas
     */
    private $_TravelDeliveryChargeAreasService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_loginService                     = new Sgmov_Service_Login();
        $this->_TravelAgencyService              = new Sgmov_Service_TravelAgency();
        $this->_TravelService                    = new Sgmov_Service_Travel();
        $this->_TravelTerminalService            = new Sgmov_Service_TravelTerminal();
        $this->_TravelProvincesService           = new Sgmov_Service_TravelProvinces;
        $this->_TravelDeliveryChargeService      = new Sgmov_Service_TravelDeliveryCharge;
        $this->_TravelDeliveryChargeAreasService = new Sgmov_Service_TravelDeliveryChargeAreas;
    }

    /**
     * 処理を実行します。
     *
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
        if (isset($_POST['id'])) {
            return $this->_executeInnerUpdate($_POST);
        } else {
            return $this->_executeInnerReload($_POST);
        }
    }

    /**
     * 新規・変更ボタン押下の場合の処理を実行します。
     *
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['ticket']:チケット文字列
     * </li><li>
     * ['outForm']:出力フォーム
     * </li><li>
     * ['errorForm']:エラーフォーム
     * </li></ul>
     */
    public function _executeInnerUpdate($post) {
        Sgmov_Component_Log::debug('新規・変更ボタン押下の場合の処理を実行します。');

        // セッション削除
        $session = Sgmov_Component_Session::get();
        $session->deleteForm($this->getFeatureId());

        // 出力情報を作成
        $outForm = $this->_createOutFormByUpdate($post);
        $errorForm = new Sgmov_Form_Error();

        // チケット発行
        $ticket = $session->publishTicket($this->getFeatureId(), self::GAMEN_ID_ATC002);

        return array(
            'ticket'    => $ticket,
            'outForm'   => $outForm,
            'errorForm' => $errorForm,
        );
    }

    /**
     * 新規・変更ボタン押下ではない場合の処理を実行します。
     *
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['ticket']:チケット文字列
     * </li><li>
     * ['outForm']:出力フォーム
     * </li><li>
     * ['errorForm']:エラーフォーム
     * </li></ul>
     */
    public function _executeInnerReload($post) {
        Sgmov_Component_Log::debug('新規・変更ボタン押下ではない場合の処理を実行します。');

        // セッション
        $session = Sgmov_Component_Session::get();
        $sessionForm = $session->loadForm(self::FEATURE_ID);
Sgmov_Component_Log::debug($session);Sgmov_Component_Log::debug($sessionForm);
        // 出力情報を作成
        $outForm = $this->_createOutFormByReload($sessionForm);

        // TODO オブジェクトから値を直接取得できるよう修正する
        // オブジェクトから取得できないため、配列に型変換
        $sessionForm = (array)$sessionForm;
        $errorForm = $sessionForm['error'];

        // チケット発行
        $ticket = $session->publishTicket($this->getFeatureId(), self::GAMEN_ID_ATC002);

        return array(
            'ticket'    => $ticket,
            'outForm'   => $outForm,
            'errorForm' => $errorForm,
        );
    }

    /**
     * 入力フォームの値を出力フォームを生成します。
     * @return Sgmov_Form_Atc022Out 出力フォーム
     */
    private function _createOutFormByUpdate($post) {

        $outForm = new Sgmov_Form_Atc022Out();

        $outForm->raw_honsha_user_flag = $this->_loginService->getHonshaUserFlag();

        $db = Sgmov_Component_DB::getPublic();
        $travelAgency = $this->_TravelAgencyService->fetchTravelAgencies($db);

        // 船名
        $outForm->raw_travel_agency_cds  = $travelAgency['ids'];
        $outForm->raw_travel_agency_lbls = $travelAgency['names'];

        if (!empty($post['id'])) {
            $outForm->raw_travel_delivery_charge_id = $post['travel_delivery_charge_id'];
            $travelDeliveryChargeAreas = $this->_TravelDeliveryChargeAreasService->fetchDeliveryChargeAddProvinces($db,
                    array('travel_terminal_id' => $post['id']));
            $travelTerminal = $this->_TravelTerminalService->fetchTravelTerminalLimit($db, array('id' => $post['id']));
        }

        if (!empty($travelDeliveryChargeAreas) && !empty($travelDeliveryChargeAreas['ids'])) {
            // ツアーエリア
            $outForm->raw_travel_provinces_ids   = $travelDeliveryChargeAreas['ids'];
            $outForm->raw_travel_provinces_names = $travelDeliveryChargeAreas['names'];
            $outForm->raw_prefecture_names       = $travelDeliveryChargeAreas['prefecture_names'];
            $outForm->raw_delivery_chargs        = $travelDeliveryChargeAreas['delivery_chargs'];
        } else {
            $travelProvinces = $this->_TravelProvincesService->fetchTravelProvinces($db);
            // ツアーエリア
            $outForm->raw_travel_provinces_ids   = $travelProvinces['ids'];
            $outForm->raw_travel_provinces_names = $travelProvinces['names'];
            $outForm->raw_prefecture_names       = $travelProvinces['prefecture_names'];
        }

        if (empty($travelTerminal)) {
            return $outForm;
        }

        $travel = $this->_TravelService->fetchTravels($db, array('travel_agency_id' => $travelTerminal['travel_agency_id']));
        $travelTerminals = $this->_TravelTerminalService->fetchTravelTerminals($db, array('travel_id' => $travelTerminal['travel_id']));

        // 乗船日名
        $outForm->raw_travel_from_cds           = $travel['ids'];
        $outForm->raw_travel_from_lbls          = $travel['names'];
        $outForm->raw_round_trip_discounts_from = $travel['round_trip_discounts'];
        $outForm->raw_travel_to_cds             = $travel['ids'];
        $outForm->raw_travel_to_lbls            = $travel['names'];
        $outForm->raw_round_trip_discounts_to   = $travel['round_trip_discounts'];

        // ツアー発着地
        $outForm->raw_travel_terminal_from_cds  = $travelTerminals['ids'];
        $outForm->raw_travel_terminal_from_lbls = $travelTerminals['names'];
        $outForm->raw_travel_terminal_to_cds    = $travelTerminals['ids'];
        $outForm->raw_travel_terminal_to_lbls   = $travelTerminals['names'];

        $outForm->raw_travel_agency_from_cd_sel   = $travelTerminal['travel_agency_id'];
        $outForm->raw_travel_from_cd_sel          = $travelTerminal['travel_id'];
        $outForm->raw_travel_terminal_from_cd_sel = $travelTerminal['id'];
        $outForm->raw_travel_agency_to_cd_sel   = $travelTerminal['travel_agency_id'];
        $outForm->raw_travel_to_cd_sel          = $travelTerminal['travel_id'];
        $outForm->raw_travel_terminal_to_cd_sel = $travelTerminal['id'];

        $outForm->raw_travel_agency_to_name   = $travelAgency['names'][array_search($travelTerminal['travel_agency_id'], $travelAgency['ids'])];
        $outForm->raw_travel_to_name          = $travel['names'][array_search($travelTerminal['travel_id'], $travel['ids'])];
        $outForm->raw_travel_terminal_to_name = $travelTerminals['names'][array_search($travelTerminal['id'], $travelTerminals['ids'])];

        return $outForm;
    }

    /**
     * 入力フォームの値を出力フォームを生成します。
     * @return Sgmov_Form_Atc022Out 出力フォーム
     */
    private function _createOutFormByReload($inForm) {

        $outForm = new Sgmov_Form_Atc022Out();

        $outForm->raw_honsha_user_flag = $this->_loginService->getHonshaUserFlag();

        $db = Sgmov_Component_DB::getPublic();
        $travelAgency    = $this->_TravelAgencyService->fetchTravelAgencies($db);
        $travelProvinces = $this->_TravelProvincesService->fetchTravelProvinces($db);

        // 船名
        $outForm->raw_travel_agency_cds  = $travelAgency['ids'];
        $outForm->raw_travel_agency_lbls = $travelAgency['names'];

        // ツアーエリア
        $outForm->raw_travel_provinces_ids   = $travelProvinces['ids'];
        $outForm->raw_travel_provinces_names = $travelProvinces['names'];
        $outForm->raw_prefecture_names       = $travelProvinces['prefecture_names'];

        // TODO オブジェクトから値を直接取得できるよう修正する
        // オブジェクトから取得できないため、配列に型変換
        $inForm = (array)$inForm;

        if (!empty($inForm['travel_agency_from_cd_sel'])) {
            $travelFrom = $this->_TravelService->fetchTravels($db, array('travel_agency_id' => $inForm['travel_agency_from_cd_sel']));
        } else {
            $travelFrom = array(
                'ids'                  => array(),
                'names'                => array(),
                'round_trip_discounts' => array(),
            );
        }

        if (!empty($inForm['travel_agency_to_cd_sel'])) {
            $travelTo = $this->_TravelService->fetchTravels($db, array('travel_agency_id' => $inForm['travel_agency_to_cd_sel']));
        } else {
            $travelTo = array(
                'ids'                  => array(),
                'names'                => array(),
                'round_trip_discounts' => array(),
            );
        }

        if (!empty($inForm['travel_from_cd_sel'])) {
            $travelTerminalsFrom = $this->_TravelTerminalService->fetchTravelTerminals($db, array('travel_id' => $inForm['travel_from_cd_sel']));
        } else {
            $travelTerminalsFrom = array(
                'ids'   => array(),
                'names' => array(),
            );
        }

        if (!empty($inForm['travel_to_cd_sel'])) {
            $travelTerminalsTo = $this->_TravelTerminalService->fetchTravelTerminals($db, array('travel_id' => $inForm['travel_to_cd_sel']));
        } else {
            $travelTerminalsTo = array(
                'ids'   => array(),
                'names' => array(),
            );
        }

        // 乗船日名
        $outForm->raw_travel_from_cds           = $travelFrom['ids'];
        $outForm->raw_travel_from_lbls          = $travelFrom['names'];
        $outForm->raw_round_trip_discounts_from = $travelFrom['round_trip_discounts'];

        $outForm->raw_travel_to_cds           = $travelTo['ids'];
        $outForm->raw_travel_to_lbls          = $travelTo['names'];
        $outForm->raw_round_trip_discounts_to = $travelTo['round_trip_discounts'];

        // ツアー発着地
        $outForm->raw_travel_terminal_from_cds  = $travelTerminalsFrom['ids'];
        $outForm->raw_travel_terminal_from_lbls = $travelTerminalsFrom['names'];

        $outForm->raw_travel_terminal_to_cds  = $travelTerminalsTo['ids'];
        $outForm->raw_travel_terminal_to_lbls = $travelTerminalsTo['names'];

        $outForm->travel_delivery_charge_id  = $inForm['travel_delivery_charge_id'];

        $outForm->raw_travel_agency_from_cd_sel   = $inForm['travel_agency_from_cd_sel'];
        $outForm->raw_travel_from_cd_sel          = $inForm['travel_from_cd_sel'];
        $outForm->raw_travel_terminal_from_cd_sel = $inForm['travel_terminal_from_cd_sel'];

        $outForm->raw_travel_agency_to_cd_sel   = $inForm['travel_agency_to_cd_sel'];
        $outForm->raw_travel_to_cd_sel          = $inForm['travel_to_cd_sel'];
        $outForm->raw_travel_terminal_to_cd_sel = $inForm['travel_terminal_to_cd_sel'];

        $outForm->raw_travel_agency_to_name   = $travelAgency['names'][array_search($inForm['travel_agency_to_cd_sel'], $travelAgency['ids'])];
        $outForm->raw_travel_to_name          = $travelTo['names'][array_search($inForm['travel_to_cd_sel'], $travelTo['ids'])];
        $outForm->raw_travel_terminal_to_name = $travelTerminalsTo['names'][array_search($inForm['travel_terminal_to_cd_sel'], $travelTerminalsTo['ids'])];

        return $outForm;
    }
}
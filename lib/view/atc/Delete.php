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
Sgmov_Lib::useForms(array('Error', 'Atc012Out'));
/**#@-*/

/**
 * ツアー配送料金マスタ削除確認画面を表示します。
 * @package    View
 * @subpackage ATC
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Atc_Delete extends Sgmov_View_Atc_Common {

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

        // セッション削除
        $session = Sgmov_Component_Session::get();
        $session->deleteForm($this->getFeatureId());

        // 出力情報を作成
        $outForm = $this->_createOutForm($_POST);
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
     * 入力フォームの値を出力フォームを生成します。
     * @return Sgmov_Form_Atc012Out 出力フォーム
     */
    private function _createOutForm($post) {

        $outForm = new Sgmov_Form_Atc012Out();

        $outForm->raw_honsha_user_flag = $this->_loginService->getHonshaUserFlag();

        $db = Sgmov_Component_DB::getPublic();
        $travelAgency = $this->_TravelAgencyService->fetchTravelAgencies($db);

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

        $outForm->raw_travel_agency_name   = $travelAgency['names'][array_search($travelTerminal['travel_agency_id'], $travelAgency['ids'])];
        $outForm->raw_travel_name          = $travel['names'][array_search($travelTerminal['travel_id'], $travel['ids'])];
        $outForm->raw_travel_terminal_name = $travelTerminals['names'][array_search($travelTerminal['id'], $travelTerminals['ids'])];
        $outForm->raw_round_trip_discount  = $travel['round_trip_discounts'][array_search($travelTerminal['travel_id'], $travel['ids'])];

        return $outForm;
    }
}
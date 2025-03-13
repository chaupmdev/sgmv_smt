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
Sgmov_Lib::useForms(array('Error', 'Atc001Out'));
/**#@-*/

/**
 * ツアー配送料金マスタ一覧画面を表示します。
 * @package    View
 * @subpackage ATC
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Atc_List extends Sgmov_View_Atc_Common {

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
     * ツアー配送料サービス
     * @var Sgmov_Service_TravelDeliveryCharge
     */
    private $_TravelDeliveryChargeService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_loginService = new Sgmov_Service_Login();
        $this->_TravelAgencyService   = new Sgmov_Service_TravelAgency();
        $this->_TravelDeliveryChargeService = new Sgmov_Service_TravelDeliveryCharge();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * セッション情報の削除
     * </li><li>
     * GETパラメーターのチェック
     * </li><li>
     * GETパラメーターを元に出力情報を生成
     * </li></ol>
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['outForm']:出力フォーム
     * </li></ul>
     */
    public function executeInner() {
        Sgmov_Component_Log::debug('セッション情報の削除');
        Sgmov_Component_Session::get()->deleteForm($this->getFeatureId());

        $outForm = $this->_createOutFormByInForm();

        return array('outForm' => $outForm);
    }

    /**
     * 入力フォームの値を出力フォームを生成します。
     * @return Sgmov_Form_Atc001Out 出力フォーム
     */
    private function _createOutFormByInForm() {

        $db = Sgmov_Component_DB::getPublic();
        $travelAgency = $this->_TravelAgencyService->fetchTravelAgencies($db);
        //$travelDeliveryCharge = $this->_TravelDeliveryChargeService->fetchTravelDeliveryCharges($db);

        $outForm = new Sgmov_Form_Atc001Out();

        // 船名
        $outForm->raw_travel_agency_cd_sel = filter_input(INPUT_POST, 'travel_agency_cd_sel');
        $outForm->raw_travel_agency_cds    = $travelAgency['ids'];
        $outForm->raw_travel_agency_lbls   = $travelAgency['names'];

        // ツアー発着地名
        //$outForm->raw_travel_delivery_charge_ids = $travelDeliveryCharge['ids'];
        //$outForm->raw_travel_terminal_names      = $travelDeliveryCharge['names'];
        //$outForm->raw_departure_dates            = $travelDeliveryCharge['departure_dates'];
        //$outForm->raw_arrival_dates              = $travelDeliveryCharge['arrival_dates'];

        $outForm->raw_honsha_user_flag = $this->_loginService->getHonshaUserFlag();

        return $outForm;
    }
}
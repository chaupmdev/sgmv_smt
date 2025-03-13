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
Sgmov_Lib::useView('atr/Common');
Sgmov_Lib::useForms(array('Error', 'Atr001Out'));
/**#@-*/

/**
 * ツアー会社マスタ一覧画面を表示します。
 * @package    View
 * @subpackage ATR
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Atr_List extends Sgmov_View_Atr_Common {

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
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_loginService        = new Sgmov_Service_Login();
        $this->_TravelAgencyService = new Sgmov_Service_TravelAgency();
        $this->_TravelService       = new Sgmov_Service_Travel();
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
     * @return Sgmov_Form_Atr001Out 出力フォーム
     */
    private function _createOutFormByInForm() {

        $db = Sgmov_Component_DB::getPublic();
        $travelAgency = $this->_TravelAgencyService->fetchTravelAgencies($db);
        //$travel = $this->_TravelService->fetchTravels($db, null);

        $outForm = new Sgmov_Form_Atr001Out();

        // 船名
        $outForm->raw_travel_agency_cd_sel = filter_input(INPUT_POST, 'travel_agency_cd_sel');
        $outForm->raw_travel_agency_cds    = $travelAgency['ids'];
        $outForm->raw_travel_agency_lbls   = $travelAgency['names'];

        // 乗船日
        //$outForm->raw_travel_ids           = $travel['ids'];
        //$outForm->raw_travel_cds           = $travel['cds'];
        //$outForm->raw_travel_names         = $travel['names'];
        //$outForm->raw_travel_agency_ids    = $travel['travel_agency_ids'];
        //$outForm->raw_round_trip_discounts = $travel['round_trip_discounts'];
        //$outForm->raw_repeater_discounts   = $travel['repeater_discounts'];
        //$outForm->raw_embarkation_dates    = $travel['embarkation_dates'];
        //$outForm->raw_publish_begin_dates  = $travel['publish_begin_dates'];

        $outForm->raw_honsha_user_flag = $this->_loginService->getHonshaUserFlag();

        return $outForm;
    }
}
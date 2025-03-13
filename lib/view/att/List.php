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
Sgmov_Lib::useView('att/Common');
Sgmov_Lib::useForms(array('Error', 'Att001Out'));
/**#@-*/

/**
 * ツアー発着地マスタ一覧画面を表示します。
 * @package    View
 * @subpackage ATT
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Att_List extends Sgmov_View_Att_Common {

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
     * ツアー発着地サービス
     * @var Sgmov_Service_TravelTerminal
     */
    private $_TravelTerminalService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_loginService          = new Sgmov_Service_Login();
        $this->_TravelAgencyService   = new Sgmov_Service_TravelAgency();
        $this->_TravelTerminalService = new Sgmov_Service_TravelTerminal();
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
     * @return Sgmov_Form_Att001Out 出力フォーム
     */
    private function _createOutFormByInForm() {

        $db = Sgmov_Component_DB::getPublic();
        $travelAgency = $this->_TravelAgencyService->fetchTravelAgencies($db);
        //$travelTerminal = $this->_TravelTerminalService->fetchTravelTerminals($db, null);

        $outForm = new Sgmov_Form_Att001Out();

        // 船名
        $outForm->raw_travel_agency_cd_sel = filter_input(INPUT_POST, 'travel_agency_cd_sel');
        $outForm->raw_travel_agency_cds    = $travelAgency['ids'];
        $outForm->raw_travel_agency_lbls   = $travelAgency['names'];

        // 乗船日
        //$outForm->raw_travel_terminal_ids   = $travelTerminal['ids'];
        //$outForm->raw_travel_terminal_cds   = $travelTerminal['cds'];
        //$outForm->raw_travel_terminal_names = $travelTerminal['names'];
        //$outForm->raw_departure_dates       = $travelTerminal['departure_dates'];
        //$outForm->raw_arrival_dates         = $travelTerminal['arrival_dates'];

        $outForm->raw_honsha_user_flag = $this->_loginService->getHonshaUserFlag();

        return $outForm;
    }
}
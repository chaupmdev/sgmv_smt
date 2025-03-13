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
Sgmov_Lib::useView('atp/Common');
Sgmov_Lib::useForms(array('Error', 'Atp001Out'));
/**#@-*/

/**
 * ツアーエリアマスタ一覧画面を表示します。
 * @package    View
 * @subpackage ATP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Atp_List extends Sgmov_View_Atp_Common {

    /**
     * ログインサービス
     * @var Sgmov_Service_Login
     */
    public $_loginService;

    /**
     * ツアーエリアサービス
     * @var Sgmov_Service_TravelProvinces
     */
    private $_TravelProvincesService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_loginService = new Sgmov_Service_Login();
        $this->_TravelProvincesService = new Sgmov_Service_TravelProvinces();
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
     * @return Sgmov_Form_Atp001Out 出力フォーム
     */
    private function _createOutFormByInForm() {

        $db = Sgmov_Component_DB::getPublic();
        $travelProvinces = $this->_TravelProvincesService->fetchTravelProvinces($db);
Sgmov_Component_Log::debug($travelProvinces);
        $outForm = new Sgmov_Form_Atp001Out();

        // ツアーエリア
        $outForm->raw_travel_provinces_ids   = $travelProvinces['ids'];
        $outForm->raw_travel_provinces_cds   = $travelProvinces['cds'];
        $outForm->raw_travel_provinces_names = $travelProvinces['names'];
        $outForm->raw_prefecture_names       = $travelProvinces['prefecture_names'];

        $outForm->raw_honsha_user_flag = $this->_loginService->getHonshaUserFlag();

        return $outForm;
    }
}
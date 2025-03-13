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
Sgmov_Lib::useView('ata/Common');
Sgmov_Lib::useForms(array('Error', 'Ata012Out'));
/**#@-*/

/**
 * ツアー会社マスタ削除確認画面を表示します。
 * @package    View
 * @subpackage ATA
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Ata_Delete extends Sgmov_View_Ata_Common {

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
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_loginService = new Sgmov_Service_Login();
        $this->_TravelAgencyService = new Sgmov_Service_TravelAgency();
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
        $outForm = $this->_createOutForm();
        $errorForm = new Sgmov_Form_Error();

        // チケット発行
        $ticket = $session->publishTicket($this->getFeatureId(), self::GAMEN_ID_ATA012);

        return array(
            'ticket'    => $ticket,
            'outForm'   => $outForm,
            'errorForm' => $errorForm,
        );
    }

    /**
     * 出力フォームを生成します。
     * @return Sgmov_Form_Ata012Out 出力フォーム
     */
    private function _createOutForm() {

        $outForm = new Sgmov_Form_Ata012Out();

        $outForm->raw_honsha_user_flag = $this->_loginService->getHonshaUserFlag();

        $id = filter_input(INPUT_POST, 'id');
        if (empty($id)) {
            return $outForm;
        }

        $db = Sgmov_Component_DB::getPublic();
        $travelAgency = $this->_TravelAgencyService->fetchTravelAgencyLimit($db, array('id' => $id));
        
        if (empty($travelAgency)) {
            return $outForm;
        }

        // 船名
        $outForm->raw_travel_agency_id   = $travelAgency['id'];
        $outForm->raw_travel_agency_cd   = $travelAgency['cd'];
        $outForm->raw_travel_agency_name = $travelAgency['name'];

        return $outForm;
    }
}
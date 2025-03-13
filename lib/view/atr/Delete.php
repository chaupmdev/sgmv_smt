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
Sgmov_Lib::useForms(array('Error', 'Atr012Out'));
/**#@-*/

/**
 * ツアーマスタ削除確認画面を表示します。
 * @package    View
 * @subpackage ATR
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Atr_Delete extends Sgmov_View_Atr_Common {

    /**
     * ログインサービス
     * @var Sgmov_Service_Login
     */
    public $_loginService;

    /**
     * ツアーサービス
     * @var Sgmov_Service_Travel
     */
    private $_TravelService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_loginService = new Sgmov_Service_Login();
        $this->_TravelService = new Sgmov_Service_Travel();
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
        $ticket = $session->publishTicket($this->getFeatureId(), self::GAMEN_ID_ATR002);

        return array(
            'ticket'    => $ticket,
            'outForm'   => $outForm,
            'errorForm' => $errorForm,
        );
    }

    /**
     * 出力フォームを生成します。
     * @return Sgmov_Form_Atr012Out 出力フォーム
     */
    private function _createOutForm($post) {

        $outForm = new Sgmov_Form_Atr012Out();

        $outForm->raw_honsha_user_flag = $this->_loginService->getHonshaUserFlag();

        if (empty($post['id'])) {
            return $outForm;
        }

        $db = Sgmov_Component_DB::getPublic();
        $travel = $this->_TravelService->fetchTravelLimit($db, array('id' => $post['id']));
        
        if (empty($travel)) {
            return $outForm;
        }

        $outForm->raw_travel_id            = $travel['id'];
        $outForm->raw_travel_cd            = $travel['cd'];
        $outForm->raw_travel_name          = $travel['name'];
        $outForm->raw_travel_agency_name   = $travel['travel_agency_name'];
        $outForm->raw_round_trip_discount  = $travel['round_trip_discount'];
        $outForm->raw_repeater_discount    = $travel['repeater_discount'];
        $outForm->raw_embarkation_date     = $travel['embarkation_date_japanese'];
        $outForm->raw_publish_begin_date   = $travel['publish_begin_date_japanese'];

        return $outForm;
    }
}
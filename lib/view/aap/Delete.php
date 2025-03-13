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
Sgmov_Lib::useView('aap/Common');
Sgmov_Lib::useForms(array('Error', 'Aap012Out'));
/**#@-*/

/**
 * マンションマスタ削除確認画面を表示します。
 * @package    View
 * @subpackage AAP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Aap_Delete extends Sgmov_View_Aap_Common {

    /**
     * ログインサービス
     * @var Sgmov_Service_Login
     */
    public $_loginService;

    /**
     * マンションサービス
     * @var Sgmov_Service_Apartment
     */
    private $_ApartmentService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_loginService = new Sgmov_Service_Login();
        $this->_ApartmentService = new Sgmov_Service_Apartment();
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
        $ticket = $session->publishTicket($this->getFeatureId(), self::GAMEN_ID_AAP002);

        return array(
            'ticket'    => $ticket,
            'outForm'   => $outForm,
            'errorForm' => $errorForm,
        );
    }

    /**
     * 入力フォームの値を出力フォームを生成します。
     * @return Sgmov_Form_Aap012Out 出力フォーム
     */
    private function _createOutForm($post) {

        $outForm = new Sgmov_Form_Aap012Out();

        $outForm->raw_honsha_user_flag = $this->_loginService->getHonshaUserFlag();

        if (empty($post['id'])) {
            return $outForm;
        }

        $db = Sgmov_Component_DB::getPublic();
        $apartment = $this->_ApartmentService->fetchApartmentLimit($db, array('id' => $post['id']));

        if (empty($apartment)) {
            return $outForm;
        }

        $outForm->raw_apartment_id   = $apartment['id'];
        $outForm->raw_apartment_cd   = $apartment['cd'];
        $outForm->raw_apartment_name = $apartment['name'];
        $outForm->raw_zip1           = $apartment['zip1'];
        $outForm->raw_zip2           = $apartment['zip2'];
        $outForm->raw_address        = $apartment['address'];
        $outForm->raw_agency_cd      = $apartment['agency_cd'];

        return $outForm;
    }
}
<?php
/**
 * @package    ClassDefFile
 * @author     K.Sawada
 * @copyright  2009-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('abi/Common');
Sgmov_Lib::useForms(array('Error', 'Abi001Out'));
/**#@-*/

/**
 * Excel一括取込画面を表示します。
 * @package    View
 * @subpackage ABI
 * @author     K.Sawada
 * @copyright  2009-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Abi_Input extends Sgmov_View_Abi_Common {

    /**
     * ログインサービス
     * @var Sgmov_Service_Login
     */
    public $_loginService;


    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_loginService = new Sgmov_Service_Login();
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
        return $this->_executeInner($_POST);
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
    public function _executeInner($post) {
        Sgmov_Component_Log::debug('新規・変更ボタン押下の場合の処理を実行します。');

        // セッション削除
        $session = Sgmov_Component_Session::get();
        $session->deleteForm($this->getFeatureId());

        // 出力情報を作成
        $outForm = $this->_createOutForm($post);
        $errorForm = new Sgmov_Form_Error();
        if(@!empty($_SESSION["Sgmov_View_Abi.inputErrorInfo"])) {
            $errorForm = $_SESSION["Sgmov_View_Abi.inputErrorInfo"];
            $_SESSION["Sgmov_View_Abi.inputErrorInfo"] = NULL;
        }

        // チケット発行
        $ticket = $session->publishTicket($this->getFeatureId(), self::GAMEN_ID_ABI001);

        return array(
            'ticket'    => $ticket,
            'outForm'   => $outForm,
            'errorForm' => $errorForm,
        );
    }

    /**
     * 入力フォームの値を出力フォームを生成します。
     * @param $post POSTデータ
     * @return Sgmov_Form_Aap002Out 出力フォーム
     */
    private function _createOutForm($post) {

        $outForm = new Sgmov_Form_Abi001Out();

        $outForm->raw_honsha_user_flag = $this->_loginService->getHonshaUserFlag();
        
        return $outForm;
    }
}
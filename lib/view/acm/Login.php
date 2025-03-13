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
Sgmov_Lib::useAllComponents();
Sgmov_Lib::useForms(array('Acm001In', 'Acm001Out', 'Error'));
Sgmov_Lib::useServices('Login');
Sgmov_Lib::useView('Maintenance');
/**#@-*/

/**
 * ログイン画面を表示します。
 * @package    View
 * @subpackage ACM
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Acm_Login extends Sgmov_View_Maintenance {

    /**
     * 機能ID。管理共通(ACM)だけ特別に処理のIDを持ちます。
     */
    const FEATURE_ID = 'LOGIN';

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
     * <ol><li>
     * ログイン済みの場合はmenuへリダイレクト
     * </li><li>
     * ログインボタン押下ではない場合
     * <ol><li>
     * フォームを初期化
     * </li></ol>
     * </li><li>
     * ログインボタン押下の場合
     * <ol><li>
     * ログイン処理
     * </li><li>
     * 失敗の場合はアカウントだけ設定してフォームを返す
     * </li><li>
     * 成功の場合はmenuへリダイレクト
     * </li></ol>
     * </li></ol>
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['outForm']:出力フォーム
     * </li><li>
     * ['errorForm']:エラーフォーム
     * </li></ul>
     */
    public function executeInner() {
        $session = Sgmov_Component_Session::get();
        if ($this->_loginService->
                isLoggedIn()
        ) {
            Sgmov_Component_Redirect::redirectMaintenance('/acm/menu');
        }

        if (!isset($_POST['login_btn_x'])) {
            // ログインボタン押下ではない場合
            $outForm   = new Sgmov_Form_Acm001Out();
            $errorForm = new Sgmov_Form_Error();
            return array(
                'outForm'   => $outForm,
                'errorForm' => $errorForm,
            );
        }

        // ログインボタン押下の場合
        // 入力チェック
        $inForm = $this->_createInFormFromPost();
        $errorForm = $this->_validate($inForm);
        if ($errorForm->hasError()) {
            $outForm = new Sgmov_Form_Acm001Out();
            $outForm->raw_user_account = $inForm->user_account;
            return array(
                'outForm'   => $outForm,
                'errorForm' => $errorForm,
            );
        }

        // ログイン
        if ($this->_loginService->
                login($inForm->user_account, $inForm->pass)
        ) {
            Sgmov_Component_Redirect::redirectMaintenance('/acm/menu');
        } else {
            $outForm = new Sgmov_Form_Acm001Out();
            $outForm->raw_user_account = $inForm->user_account;

            $errorForm = new Sgmov_Form_Error();
            $errorForm->addError('top', '入力したユーザー名またはパスワードが間違っています。');
            return array(
                'outForm'   => $outForm,
                'errorForm' => $errorForm,
            );
        }
    }

    /**
     * 機能IDを取得します。
     * @return string 機能ID
     */
    public function getFeatureId() {
        return self::FEATURE_ID;
    }

    /**
     * POST情報から入力フォームを生成します。
     * @return Sgmov_Form_Acm001In 入力フォーム
     */
    public function _createInFormFromPost() {
        $inForm = new Sgmov_Form_Acm001In();
        $inForm->user_account = filter_input(INPUT_POST, 'user_account');
        $inForm->pass         = filter_input(INPUT_POST, 'pass');
        return $inForm;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_Acm001In $inForm 入力フォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($inForm) {
        // 入力チェック
        $errorForm = new Sgmov_Form_Error();

        // アカウント
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->user_account)->
                isNotEmpty()->
                isLengthLessThanOrEqualTo(100);
        if (!$v->isValid()) {
            $errorForm->addError('top_user_account', $v->getResultMessageTop());
        }

        // パスワード
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->pass)->
                isNotEmpty()->
                isLengthLessThanOrEqualTo(100);
        if (!$v->isValid()) {
            $errorForm->addError('top_pass', $v->getResultMessageTop());
        }

        return $errorForm;
    }
}
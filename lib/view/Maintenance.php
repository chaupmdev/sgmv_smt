<?php
/**
 * @package    ClassDefFile
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useComponents(array('System', 'Log', 'String'));
Sgmov_Lib::useServices('Login');
/**#@-*/

 /**
 * 管理画面の全ビューに共通の情報を管理する抽象クラスです。
 *
 * 処理の実行にはテンプレートメソッドパターンを使用しています。
 *
 * @package    View
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Maintenance
{
    /**
     * 未検査
     */
    const VALIDATION_NOT_YET = 0;

    /**
     * 検査失敗
     */
    const VALIDATION_FAILED = 1;

    /**
     * 検査成功
     */
    const VALIDATION_SUCCEEDED = 2;

    /**
     * 処理のテンプレートメソッドです。
     *
     * 全ビューに共通で必要な「エラー処理の開始」と「デバッグログの出力」を一括管理します。
     * $_POST 変数と $_GET 変数は全て正規化されます。
     *
     * @return array 処理に応じた値を持った配列を返します。
     */
    public final function execute()
    {
        // エラー処理を開始
        Sgmov_Component_System::startErrorHandling();

        // デバッグログ
        if (Sgmov_Component_Log::isDebug()) {
            Sgmov_Component_Log::debug('クラス:' . get_class($this));
            $dbg = Sgmov_Component_String::toDebugString(array('$_GET'=>$_GET, '$_POST'=>$_POST));
            Sgmov_Component_Log::debug('入力値:' . $dbg);
        }

        // 入力値の正規化
        $_POST = Sgmov_Component_String::normalizeInput($_POST);
        $_GET = Sgmov_Component_String::normalizeInput($_GET);

        // 管理画面用前処理
        if($this->getFeatureId() !== 'LOGIN'){
            $this->_checkAuth();
        }

        // 処理を実行
        $ret = $this->executeInner();

        // デバッグログ
        if (Sgmov_Component_Log::isDebug()) {
            $dbg = Sgmov_Component_String::toDebugString($ret);
            Sgmov_Component_Log::debug('戻り値:' . $dbg);
        }

        return $ret;
    }

    /**
     * 管理画面用の前処理を行います。
     * <ol><li>
     * セッションの継続を確認。セッションが切れている場合はエラー。
     * </li><li>
     * ログイン確認。ログインしていなければログイン画面へリダイレクト。
     * </li><li>
     * 権限確認。権限がない画面にアクセスした場合はエラー。
     * </li></ol>
     */
    public function _checkAuth(){
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();

        $loginService = new Sgmov_Service_Login();
        if(!$loginService->isLoggedIn()){
            Sgmov_Component_Redirect::redirectMaintenance('/acm/login');
        }

        $loginService->checkUserAuth($this->getFeatureId());
    }

    /**
     * メイン処理を記述するメソッドです。
     * @return array 処理に応じた値を持った配列を返します。
     */
    public abstract function executeInner();

    /**
     * 機能IDを取得します。
     * @return string 機能ID
     */
    public abstract function getFeatureId();
}
?>

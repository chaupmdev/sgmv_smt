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
Sgmov_Lib::useView('Maintenance');
Sgmov_Lib::useServices('Login');
/**#@-*/

 /**
 * ログアウト処理を実行します。
 * @package    View
 * @subpackage ACM
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Acm_Logout extends Sgmov_View_Maintenance
{
    /**
     * 機能ID。管理共通(ACM)だけ特別に処理のIDを持ちます。
     */
    const FEATURE_ID = 'LOGOUT';

    /**
     * ログインサービス
     * @var Sgmov_Service_Login
     */
    public $_loginService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        $this->_loginService = new Sgmov_Service_Login();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * ログアウト処理を実行
     * </li><li>
     * ログイン画面にリダイレクト
     * </li></ol>
     */
    public function executeInner()
    {
        $this->_loginService->
                logout();
        Sgmov_Component_Redirect::redirectMaintenance('/acm/login');
    }

    /**
     * 機能IDを取得します。
     * @return string 機能ID
     */
    public function getFeatureId()
    {
        return self::FEATURE_ID;
    }
}
?>

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
Sgmov_Lib::useForms(array('Acm002Out'));
Sgmov_Lib::useServices('Login');
Sgmov_Lib::useView('Maintenance');
/**#@-*/

 /**
 * メニュー画面を表示します。
 * @package    View
 * @subpackage ACM
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Acm_Menu extends Sgmov_View_Maintenance
{
    /**
     * 機能ID。管理共通(ACM)だけ特別に処理のIDを持ちます。
     */
    const FEATURE_ID = 'MENU';

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
     * 出力情報を設定。
     * </li></ol>
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['outForm']:出力フォーム
     * </li></ul>
     */
    public function executeInner()
    {
        $outForm = new Sgmov_Form_Acm002Out();
        $outForm->raw_honsha_user_flag = $this->_loginService->getHonshaUserFlag();

        return array('outForm'=>$outForm);
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

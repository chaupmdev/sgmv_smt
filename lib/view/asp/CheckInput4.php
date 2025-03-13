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
Sgmov_Lib::useView('asp/Common');
Sgmov_Lib::useForms(array('Error', 'AspSession', 'Asp006In'));
/**#@-*/

 /**
 * 金額設定方法選択入力情報をチェックします。
 * @package    View
 * @subpackage ASP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Asp_CheckInput4 extends Sgmov_View_Asp_Common
{
    /**
     * 処理を実行します。
     * <ol><li>
     * チケットの確認と破棄
     * </li><li>
     * 金額設定区分をセッションに保存
     * </li><li>
     * ASP008・ASP009の情報をセッションから削除
     * </li><li>
     * confirmへリダイレクト
     * </li></ol>
     */
    public function executeInner()
    {
        Sgmov_Component_Log::debug('チケットの確認と破棄');
        $session = Sgmov_Component_Session::get();
        $session->checkTicket($this->getFeatureId(), self::GAMEN_ID_ASP007, $this->_getTicket());

        Sgmov_Component_Log::debug('金額設定区分をセッションに保存');
        $sessionForm = $session->loadForm($this->getFeatureId());
        $sessionForm->priceset_kbn = self::PRICESET_KBN_NONE;

        Sgmov_Component_Log::debug('ASP008・ASP009の情報をセッションから削除');
        $sessionForm->asp008_in = NULL;
        $sessionForm->asp008_error = NULL;
        $sessionForm->asp008_status = NULL;
        $sessionForm->asp009_in = NULL;
        $sessionForm->asp009_error = NULL;
        $sessionForm->asp009_status = NULL;

        $session->saveForm($this->getFeatureId(), $sessionForm);

        $to = '/asp/confirm/' . $this->getFeatureGetParam();
        Sgmov_Component_Log::debug('リダイレクト ' . $to);
        Sgmov_Component_Redirect::redirectMaintenance($to);
    }

    /**
     * POST値からチケットを取得します。
     * チケットが存在しない場合は空文字列を返します。
     * @return string チケット文字列
     */
    public function _getTicket()
    {
        if (!isset($_POST['ticket'])) {
            $ticket = '';
        } else {
            $ticket = $_POST['ticket'];
        }
        return $ticket;
    }
}
?>

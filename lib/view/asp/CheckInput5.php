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
Sgmov_Lib::useForms(array('Error', 'AspSession', 'Asp008In'));
/**#@-*/

 /**
 * 特価一括編集金額入力情報をチェックします。
 * @package    View
 * @subpackage ASP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Asp_CheckInput5 extends Sgmov_View_Asp_Common
{
    /**
     * 処理を実行します。
     * <ol><li>
     * チケットの確認と破棄
     * </li><li>
     * 情報をセッションに保存
     * </li><li>
     * 入力チェック
     * </li><li>
     * 入力エラー有り:input5へリダイレクト
     * </li><li>
     * 入力エラー無し:
     *   <ol><li>
     *   金額設定区分をセッションに設定
     *   </li><li>
     *   ASP009の情報をセッションから削除
     *   </li><li>
     *   confirmへリダイレクト
     *   </li></ol>
     * </li></ol>
     */
    public function executeInner()
    {
        Sgmov_Component_Log::debug('チケットの確認と破棄');
        $session = Sgmov_Component_Session::get();
        $session->checkTicket($this->getFeatureId(), self::GAMEN_ID_ASP008, $this->_getTicket());

        Sgmov_Component_Log::debug('情報を取得');
        $inForm = $this->_createInFormFromPost($_POST);

        Sgmov_Component_Log::debug('入力チェック');
        $errorForm = $this->_validate($inForm);

        Sgmov_Component_Log::debug('情報をセッションに保存');
        $sessionForm = $session->loadForm($this->getFeatureId());
        $sessionForm->asp008_in = $inForm;
        $sessionForm->asp008_error = $errorForm;
        if ($errorForm->hasError()) {
            $sessionForm->asp008_status = self::VALIDATION_FAILED;
        } else {
            $sessionForm->asp008_status = self::VALIDATION_SUCCEEDED;
        }
        $session->saveForm($this->getFeatureId(), $sessionForm);

        if ($errorForm->hasError()) {
            $to = '/asp/input5/' . $this->getFeatureGetParam();
        } else {
            Sgmov_Component_Log::debug('金額設定区分をセッションに設定');
            $sessionForm->priceset_kbn = self::PRICESET_KBN_ALL;;

            Sgmov_Component_Log::debug('ASP009の情報をセッションから削除');
            $sessionForm->asp009_in = NULL;
            $sessionForm->asp009_error = NULL;
            $sessionForm->asp009_status = NULL;
            $session->saveForm($this->getFeatureId(), $sessionForm);

            $to = '/asp/confirm/' . $this->getFeatureGetParam();
        }
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

    /**
     * POST情報から入力フォームを生成します。
     * @param array $post ポスト情報
     * @return Sgmov_Form_Asp008In 入力フォーム
     */
    public function _createInFormFromPost($post)
    {
        $inForm = new Sgmov_Form_Asp008In();
        $inForm->sp_whole_charge = $post['sp_whole_charge'];
        return $inForm;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_Asp008In $inForm 入力フォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($inForm)
    {
        if (Sgmov_Component_Log::isDebug()) {
            Sgmov_Component_Log::debug('入力チェック:$inForm=' . Sgmov_Component_String::toDebugString($inForm));
        }
        $errorForm = new Sgmov_Form_Error();

        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->sp_whole_charge);
        $v->isNotEmpty()->
            isInteger();
        if (!$v->isValid()) {
            $errorForm->addError('top_sp_whole_charge', $v->getResultMessageTop());
        } else {
            // 数値文字列として保存しなおす(先頭の0を除去)
            $inForm->sp_whole_charge = (string) intval($inForm->sp_whole_charge);
            if ($inForm->sp_whole_charge === '0') {
                $errorForm->addError('top_sp_whole_charge', 'には0以外の値を入力してください。');
            }
        }
        return $errorForm;
    }
}
?>

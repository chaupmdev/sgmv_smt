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
Sgmov_Lib::useForms(array('Error', 'AspSession', 'Asp004In'));
/**#@-*/

 /**
 * 特価編集名称入力情報をチェックします。
 * @package    View
 * @subpackage ASP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Asp_CheckInput1 extends Sgmov_View_Asp_Common
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
     * 入力エラー有り:input1へリダイレクト
     * </li><li>
     * 入力エラー無し:input2へリダイレクト
     * </li></ol>
     */
    public function executeInner()
    {
        Sgmov_Component_Log::debug('チケットの確認と破棄');
        $session = Sgmov_Component_Session::get();
        $session->checkTicket($this->getFeatureId(), self::GAMEN_ID_ASP004, $this->_getTicket());

        Sgmov_Component_Log::debug('情報を取得');
        $inForm = $this->_createInFormFromPost($_POST);

        Sgmov_Component_Log::debug('入力チェック');
        $errorForm = $this->_validate($inForm);

        Sgmov_Component_Log::debug('情報をセッションに保存');
        $sessionForm = $session->loadForm($this->getFeatureId());
        $sessionForm->asp004_in = $inForm;
        $sessionForm->asp004_error = $errorForm;
        if ($errorForm->hasError()) {
            $sessionForm->asp004_status = self::VALIDATION_FAILED;
        } else {
            $sessionForm->asp004_status = self::VALIDATION_SUCCEEDED;
        }
        $sessionForm->asp005_status = NULL;
        $sessionForm->asp006_status = NULL;
        $sessionForm->asp008_status = NULL;
        $sessionForm->asp009_status = NULL;
        $session->saveForm($this->getFeatureId(), $sessionForm);

        if ($errorForm->hasError()) {
            $to = '/asp/input1/' . $this->getFeatureGetParam();
        } else {
            $to = '/asp/input2/' . $this->getFeatureGetParam();
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
     * @return Sgmov_Form_Asp004In 入力フォーム
     */
    public function _createInFormFromPost($post)
    {
        $inForm = new Sgmov_Form_Asp004In();
        $inForm->sp_name = $post['sp_name'];
        $inForm->sp_regist_user = $post['sp_regist_user'];
        if ($this->getFeatureId() === self::FEATURE_ID_EXTRA) {
            // 閑散繁忙
            $inForm->sp_content = '';
        } else if ($this->getFeatureId() === self::FEATURE_ID_CAMPAIGN) {
            // キャンペーン
            $inForm->sp_content = $post['sp_content'];
        }
        return $inForm;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_Asp004In $inForm 入力フォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($inForm)
    {
        if (Sgmov_Component_Log::isDebug()) {
            Sgmov_Component_Log::debug('入力チェック:$inForm=' . Sgmov_Component_String::toDebugString($inForm));
        }

        $errorForm = new Sgmov_Form_Error();

        // 名称
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->sp_name)->
                                        isNotEmpty()->
                                        isLengthLessThanOrEqualTo(80)->
                                        isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_name', $v->getResultMessageTop());
            $errorForm->addError('name', $v->getResultMessage());
        }
        // 登録者名
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->sp_regist_user)->
                                        isNotEmpty()->
                                        isLengthLessThanOrEqualTo(40)->
                                        isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_sp_regist_user', $v->getResultMessageTop());
            $errorForm->addError('sp_regist_user', $v->getResultMessage());
        }
        // 広告用テキスト
        if ($this->getFeatureId() === self::FEATURE_ID_CAMPAIGN) {
            // キャンペーン
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->sp_content)->
                                            isNotEmpty()->
                                            isLengthLessThanOrEqualTo(600)->
                                            isWebSystemNg();
            if (!$v->isValid()) {
                $errorForm->addError('top_sp_content', $v->getResultMessageTop());
                $errorForm->addError('sp_content', $v->getResultMessage());
            }
        }

        return $errorForm;
    }
}
?>

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
Sgmov_Lib::useView('pqu/Common');
Sgmov_Lib::useForms(array('Error', 'PquSession', 'Pqu001In'));
/**#@-*/

 /**
 * アンケート入力情報をチェックします。
 * @package    View
 * @subpackage PQU
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pqu_CheckInput extends Sgmov_View_Pqu_Common
{
    /**
     * 処理を実行します。
     * <ol><li>
     * セッションの継続を確認
     * </li><li>
     * チケットの確認と破棄
     * </li><li>
     * 入力チェック
     * </li><li>
     * 情報をセッションに保存
     * </li><li>
     * 入力エラー無し
     *   <ol><li>
     *   pqu/confirm へリダイレクト
     *   </li></ol>
     * </li><li>
     * 入力エラー有り
     *   <ol><li>
     *   pqu/input へリダイレクト
     *   </li></ol>
     * </li></ol>
     */
    public function executeInner()
    {
        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();

        // チケットの確認と破棄
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_PQU001, $this->_getTicket());

        // 入力チェック
        $inForm = $this->_createInFormFromPost($_POST);
        $errorForm = $this->_validate($inForm);

        // 情報をセッションに保存
        $sessionForm = new Sgmov_Form_PquSession();
        $sessionForm->in = $inForm;
        $sessionForm->error = $errorForm;
        if ($errorForm->hasError()) {
            $sessionForm->status = self::VALIDATION_FAILED;
        } else {
            $sessionForm->status = self::VALIDATION_SUCCEEDED;
        }
        $session->saveForm(self::FEATURE_ID, $sessionForm);

        // リダイレクト
        if ($errorForm->hasError()) {
            Sgmov_Component_Redirect::redirectPublicSsl('/pqu/input');
        } else {
            Sgmov_Component_Redirect::redirectPublicSsl('/pqu/confirm');
        }
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
     * @return Sgmov_Form_Pqu001In 入力フォーム
     */
    public function _createInFormFromPost($post)
    {
        $inForm = new Sgmov_Form_Pqu001In();

        if (isset($post['question1_cd_sel'])) {
            $inForm->question1_cd_sel = $post['question1_cd_sel'];
        } else {
            $inForm->question1_cd_sel = '';
        }

        if (isset($post['question2_1_sel_flag']) && $post['question2_1_sel_flag'] === '1') {
            $inForm->question2_1_sel_flag = '1';
        } else {
            $inForm->question2_1_sel_flag = '0';
        }

        if (isset($post['question2_2_sel_flag']) && $post['question2_2_sel_flag'] === '1') {
            $inForm->question2_2_sel_flag = '1';
        } else {
            $inForm->question2_2_sel_flag = '0';
        }

        if (isset($post['question2_3_sel_flag']) && $post['question2_3_sel_flag'] === '1') {
            $inForm->question2_3_sel_flag = '1';
        } else {
            $inForm->question2_3_sel_flag = '0';
        }

        if (isset($post['question2_4_sel_flag']) && $post['question2_4_sel_flag'] === '1') {
            $inForm->question2_4_sel_flag = '1';
        } else {
            $inForm->question2_4_sel_flag = '0';
        }

        if (isset($post['question2_5_sel_flag']) && $post['question2_5_sel_flag'] === '1') {
            $inForm->question2_5_sel_flag = '1';
        } else {
            $inForm->question2_5_sel_flag = '0';
        }

        $inForm->question2_5_text = $post['question2_5_text'];

        if (isset($post['question3_cd_sel'])) {
            $inForm->question3_cd_sel = $post['question3_cd_sel'];
        } else {
            $inForm->question3_cd_sel = '';
        }

        if (isset($post['question4_cd_sel'])) {
            $inForm->question4_cd_sel = $post['question4_cd_sel'];
        } else {
            $inForm->question4_cd_sel = '';
        }

        if (isset($post['question5_cd_sel'])) {
            $inForm->question5_cd_sel = $post['question5_cd_sel'];
        } else {
            $inForm->question5_cd_sel = '';
        }

        if (isset($post['question6_cd_sel'])) {
            $inForm->question6_cd_sel = $post['question6_cd_sel'];
        } else {
            $inForm->question6_cd_sel = '';
        }

        if (isset($post['question7_cd_sel'])) {
            $inForm->question7_cd_sel = $post['question7_cd_sel'];
        } else {
            $inForm->question7_cd_sel = '';
        }

        if (isset($post['question8_cd_sel'])) {
            $inForm->question8_cd_sel = $post['question8_cd_sel'];
        } else {
            $inForm->question8_cd_sel = '';
        }

        if (isset($post['question9_cd_sel'])) {
            $inForm->question9_cd_sel = $post['question9_cd_sel'];
        } else {
            $inForm->question9_cd_sel = '';
        }

        $inForm->question10_text = $post['question10_text'];
        return $inForm;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_Pqu001In $inForm 入力フォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($inForm)
    {
        // 通常の入力ではありえない値の場合はシステムエラー
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->question1_cd_sel)->
                                        isIn(array_keys($this->question1_lbls));
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->question3_cd_sel)->
                                        isIn(array_keys($this->question3_lbls));
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->question4_cd_sel)->
                                        isIn(array_keys($this->question4_lbls));
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->question5_cd_sel)->
                                        isIn(array_keys($this->question5_lbls));
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->question6_cd_sel)->
                                        isIn(array_keys($this->question6_lbls));
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->question7_cd_sel)->
                                        isIn(array_keys($this->question7_lbls));
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->question8_cd_sel)->
                                        isIn(array_keys($this->question8_lbls));
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->question9_cd_sel)->
                                        isIn(array_keys($this->question9_lbls));
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }

        // 入力チェック
        $errorForm = new Sgmov_Form_Error();

        // 全ての値が空の場合はエラー
        if ($inForm->question1_cd_sel === '' && $inForm->question2_1_sel_flag === '0' && $inForm->question2_2_sel_flag === '0' &&
             $inForm->question2_3_sel_flag === '0' && $inForm->question2_4_sel_flag === '0' && $inForm->question2_5_sel_flag === '0' &&
             $inForm->question2_5_text === '' && $inForm->question3_cd_sel === '' && $inForm->question4_cd_sel === '' &&
             $inForm->question5_cd_sel === '' && $inForm->question6_cd_sel === '' && $inForm->question7_cd_sel === '' &&
             $inForm->question8_cd_sel === '' && $inForm->question9_cd_sel === '' && $inForm->question10_text === '') {

            $errorForm->addError('top_all', 'アンケート項目が入力されていません。');
        } else {
            // 文字数がオーバーしている場合はエラー
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->question2_5_text)->
                                            isLengthLessThanOrEqualTo(1000);
            if (!$v->isValid()) {
                $errorForm->addError('top_question2_5_text', $v->getResultMessageTop());
                $errorForm->addError('question2_5_text', $v->getResultMessage());
            }

            // 文字数がオーバーしている場合はエラー
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->question10_text)->
                                            isLengthLessThanOrEqualTo(1000);
            if (!$v->isValid()) {
                $errorForm->addError('top_question10_text', $v->getResultMessageTop());
                $errorForm->addError('question10_text', $v->getResultMessage());
            }
        }
        return $errorForm;
    }
}
?>

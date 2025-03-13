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
Sgmov_Lib::useForms(array('Error', 'PquSession', 'Pqu001Out'));
/**#@-*/

 /**
 * アンケート入力画面を表示します。
 * @package    View
 * @subpackage PQU
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pqu_Input extends Sgmov_View_Pqu_Common
{
    /**
     * 処理を実行します。
     * <ol><li>
     * セッションに情報があるかどうかを確認
     * </li><li>
     * 情報有り
     *   <ol><li>
     *   セッション情報を元に出力情報を作成
     *   </li></ol>
     * </li><li>
     * 情報無し
     *   <ol><li>
     *   出力情報を設定
     *   </li></ol>
     * </li><li>
     * チケット発行
     * </li></ol>
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['ticket']:チケット文字列
     * </li><li>
     * ['outForm']:出力フォーム
     * </li><li>
     * ['errorForm']:エラーフォーム
     * </li></ul>
     */
    public function executeInner()
    {
        // セッションに情報があるかどうかを確認
        $session = Sgmov_Component_Session::get();
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        if (isset($sessionForm)) {
            // セッション情報を元に出力情報を作成
            $outForm = $this->_createOutFormByInForm($sessionForm->in);
            $errorForm = $sessionForm->error;
        } else {
            // 出力情報を設定
            $outForm = new Sgmov_Form_Pqu001Out();
            $errorForm = new Sgmov_Form_Error();
        }

        // チケット発行
        $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_PQU001);

        return array('ticket'=>$ticket,
                         'outForm'=>$outForm,
                         'errorForm'=>$errorForm);
    }

    /**
     * 入力フォームの値を元に出力フォームを生成します。
     * @param Sgmov_Form_Pqu001In $inForm 入力フォーム
     * @return Sgmov_Form_Pqu001Out 出力フォーム
     */
    public function _createOutFormByInForm($inForm)
    {
        $outForm = new Sgmov_Form_Pqu001Out();
        $outForm->raw_question1_cd_sel = $inForm->question1_cd_sel;
        $outForm->raw_question2_1_sel_flag = $inForm->question2_1_sel_flag;
        $outForm->raw_question2_2_sel_flag = $inForm->question2_2_sel_flag;
        $outForm->raw_question2_3_sel_flag = $inForm->question2_3_sel_flag;
        $outForm->raw_question2_4_sel_flag = $inForm->question2_4_sel_flag;
        $outForm->raw_question2_5_sel_flag = $inForm->question2_5_sel_flag;
        $outForm->raw_question2_5_text = $inForm->question2_5_text;
        $outForm->raw_question3_cd_sel = $inForm->question3_cd_sel;
        $outForm->raw_question4_cd_sel = $inForm->question4_cd_sel;
        $outForm->raw_question5_cd_sel = $inForm->question5_cd_sel;
        $outForm->raw_question6_cd_sel = $inForm->question6_cd_sel;
        $outForm->raw_question7_cd_sel = $inForm->question7_cd_sel;
        $outForm->raw_question8_cd_sel = $inForm->question8_cd_sel;
        $outForm->raw_question9_cd_sel = $inForm->question9_cd_sel;
        $outForm->raw_question10_text = $inForm->question10_text;
        return $outForm;
    }
}
?>

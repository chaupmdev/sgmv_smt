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
Sgmov_Lib::useForms(array('Error', 'PquSession', 'Pqu002Out'));
/**#@-*/

 /**
 * アンケート確認画面を表示します。
 * @package    View
 * @subpackage PQU
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pqu_Confirm extends Sgmov_View_Pqu_Common
{
    /**
     * 処理を実行します。
     * <ol><li>
     * セッションの継続を確認
     * </li><li>
     * セッションに入力チェック済みの情報があるかどうかを確認
     * </li><li>
     * セッション情報を元に出力情報を設定
     * </li><li>
     * チケット発行
     * </li></ol>
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['ticket']:チケット文字列
     * </li><li>
     * ['outForm']:出力フォーム
     * </li></ul>
     */
    public function executeInner()
    {
        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();

        // セッションに入力チェック済みの情報があるかどうかを確認
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        if (!isset($sessionForm) || $sessionForm->status != self::VALIDATION_SUCCEEDED) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
        }

        // セッション情報を元に出力情報を設定
        $outForm = $this->_createOutFormFromInForm($sessionForm->in);

        // チケットを発行
        $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_PQU002);

        return array('ticket'=>$ticket,
                         'outForm'=>$outForm);
    }

    /**
     * 入力フォームの値を元に出力フォームを生成します。
     * @param Sgmov_Form_Pqu001In $inForm 入力フォーム
     * @return Sgmov_Form_Pqu002Out 出力フォーム
     */
    public function _createOutFormFromInForm($inForm)
    {
        $outForm = new Sgmov_Form_Pqu002Out();
        $outForm->raw_question1 = $this->question1_lbls[$inForm->question1_cd_sel];

        $value = '';
        $tempValue = $this->question2_1_lbls[$inForm->question2_1_sel_flag];
        if (! empty($tempValue)) {
            $value .= $tempValue;
        }
        $tempValue = $this->question2_2_lbls[$inForm->question2_2_sel_flag];
        if (! empty($tempValue)) {
            if (! empty($value)) {
                $value .= ', ';
            }
            $value .= $tempValue;
        }
        $tempValue = $this->question2_3_lbls[$inForm->question2_3_sel_flag];
        if (! empty($tempValue)) {
            if (! empty($value)) {
                $value .= ', ';
            }
            $value .= $tempValue;
        }
        $tempValue = $this->question2_4_lbls[$inForm->question2_4_sel_flag];
        if (! empty($tempValue)) {
            if (! empty($value)) {
                $value .= ', ';
            }
            $value .= $tempValue;
        }
        $tempValue = $this->question2_5_lbls[$inForm->question2_5_sel_flag];
        if (! empty($tempValue)) {
            if (! empty($value)) {
                $value .= ', ';
            }
            $value .= $tempValue;
        }
        $outForm->raw_question2 = $value;

        $outForm->raw_question2_5_text = $inForm->question2_5_text;
        $outForm->raw_question3 = $this->question3_lbls[$inForm->question3_cd_sel];
        $outForm->raw_question4 = $this->question4_lbls[$inForm->question4_cd_sel];
        $outForm->raw_question5 = $this->question5_lbls[$inForm->question5_cd_sel];
        $outForm->raw_question6 = $this->question6_lbls[$inForm->question6_cd_sel];
        $outForm->raw_question7 = $this->question7_lbls[$inForm->question7_cd_sel];
        $outForm->raw_question8 = $this->question8_lbls[$inForm->question8_cd_sel];
        $outForm->raw_question9 = $this->question9_lbls[$inForm->question9_cd_sel];
        $outForm->raw_question10_text = $inForm->question10_text;
        return $outForm;
    }
}
?>

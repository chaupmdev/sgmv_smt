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
Sgmov_Lib::useView('pin/Common');
Sgmov_Lib::useForms(array('Error', 'PinSession', 'Pin002Out'));
/**#@-*/

 /**
 * お問い合わせ確認画面を表示します。
 * @package    View
 * @subpackage PIN
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pin_Confirm extends Sgmov_View_Pin_Common
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
        $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_PIN002);

        return array('ticket'=>$ticket,
                         'outForm'=>$outForm);
    }

    /**
     * 入力フォームの値を元に出力フォームを生成します。
     * @param Sgmov_Form_Pin001In $inForm 入力フォーム
     * @return Sgmov_Form_Pin002Out 出力フォーム
     */
    public function _createOutFormFromInForm($inForm)
    {
        // 都道府県を取得
        $db = Sgmov_Component_DB::getPublic();
        $service = new Sgmov_Service_Prefecture();
        $prefs = $service->fetchPrefectures($db);
        // 都道府県名を取得
        $key = array_search($inForm->pref_cd_sel, $prefs['ids']);
        $prefName = $prefs['names'][$key];

        $outForm = new Sgmov_Form_Pin002Out();
        $outForm->raw_inquiry_type = $this->inquiry_type_lbls[$inForm->inquiry_type_cd_sel];
        $outForm->raw_need_reply = $this->need_reply_lbls[$inForm->need_reply_cd_sel];
        $outForm->raw_company_name = $inForm->company_name;
        $outForm->raw_name = $inForm->name;
        $outForm->raw_furigana = $inForm->furigana;
        if ( empty($inForm->tel1)) {
            $outForm->raw_tel = '';
        } else {
            $outForm->raw_tel = $inForm->tel1 . '-' . $inForm->tel2 . '-' . $inForm->tel3;
        }
        $outForm->raw_mail = $inForm->mail;
        if ( empty($inForm->zip1)) {
            $outForm->raw_zip = '';
        } else {
            $outForm->raw_zip = $inForm->zip1 . '-' . $inForm->zip2;
        }
        $outForm->raw_address_all = $prefName . $inForm->address;
        $outForm->raw_inquiry_title = $inForm->inquiry_title;
        $outForm->raw_inquiry_content = $inForm->inquiry_content;
        return $outForm;
    }
}
?>

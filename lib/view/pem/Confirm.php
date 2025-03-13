<?php
/**
 * @package    ClassDefFile
 * @author     K.Hamada(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('pem/Common');
Sgmov_Lib::useForms(array('Error', 'PemSession', 'Pem002Out'));
/**#@-*/

 /**
 * 法人引越輸送確認画面を表示します。
 * @package    View
 * @subpackage PEM
 * @author     K.Hamada(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pem_Confirm extends Sgmov_View_Pem_Common
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
        $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_PEM002);

        return array('ticket'=>$ticket,
                         'outForm'=>$outForm);
    }

    /**
     * 入力フォームの値を元に出力フォームを生成します。
     * @param Sgmov_Form_Pem001In $inForm 入力フォーム
     * @return Sgmov_Form_Pem002Out 出力フォーム
     */
    public function _createOutFormFromInForm($inForm)
    {
        // 勤務地を取得
        $db = Sgmov_Component_DB::getPublic();
        $service = new Sgmov_Service_Center();
        $centers = $service->fetchCenters($db);
        // 勤務地名を取得
        $place = $inForm->work_place_flag_sels;

        for ($i = 0 ; $i <count($place); $i++) {
            $key = array_search($place[$i], $centers['ids']);
//        $key = array_search($inForm->work_place_flag_sels, $centers['ids']);
            $centerName[] = $centers['names'][$key];
//            $c_name .= $centerName;
        }

        // 都道府県を取得
        $db = Sgmov_Component_DB::getPublic();
        $service = new Sgmov_Service_Prefecture();
        $prefs = $service->fetchPrefectures($db);
        // 都道府県名を取得
        $key = array_search($inForm->pref_cd_sel, $prefs['ids']);
        $prefName = $prefs['names'][$key];

        $outForm = new Sgmov_Form_Pem002Out();
        $outForm->raw_employ_type = $this->employ_type_lbls[$inForm->employ_type_cd_sel];
        $outForm->raw_job_type = $this->job_type_lbls[$inForm->job_type_cd_sel];
        $outForm->raw_work_places = $centerName;
//        $outForm->raw_work_places = $inForm->work_place_flag_sels;
//        $outForm->raw_work_places = $p['0'];

        $outForm->raw_name = $inForm->name;
        $outForm->raw_furigana = $inForm->furigana;
        $outForm->raw_age = $this->age_lbls[$inForm->age_cd_sel];
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
        $outForm->raw_resume = $inForm->resume;
        return $outForm;
    }
}
?>

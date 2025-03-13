<?php
/**
 * @package    ClassDefFile
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('pcv/Common');
Sgmov_Lib::useForms(array('Error', 'PcvSession', 'Pcv001Out'));
/**#@-*/

 /**
 * 法人オフィス移転入力画面を表示します。
 * @package    View
 * @subpackage PCV
 * @author     K.Hamada(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pcv_Input extends Sgmov_View_Pcv_Common
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
     * テンプレート用の値をセット
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
            $outForm = new Sgmov_Form_Pcv001Out();
            $errorForm = new Sgmov_Form_Error();
        }

        // テンプレート用の値をセット
        $db = Sgmov_Component_DB::getPublic();
        $service = new Sgmov_Service_Prefecture();
        $prefs = $service->fetchPrefectures($db);
        $prefectures = $service->fetchPrefectures($db);
        // 現住所都道府県
        $outForm->raw_cur_pref_cds = $prefectures['ids'];
        $outForm->raw_cur_pref_lbls = $prefectures['names'];
        // 新住所都道府県
        $outForm->raw_new_pref_cds = $prefectures['ids'];
        $outForm->raw_new_pref_lbls = $prefectures['names'];

        $service = new Sgmov_Service_CenterArea();
        $fromareas = $service->fetchFromAreaList($db);
        $toareas = $service->fetchToAreaList($db);
        $years = $this->getYears();
        // 電話連絡可能開始時刻・終了時刻
        $time = $this->_fetchTime('0','23');
        $outForm->raw_contact_start_cds = $time['ids'];
        $outForm->raw_contact_start_lbls = $time['names'];
        $outForm->raw_contact_end_cds = $time['ids'];
        $outForm->raw_contact_end_lbls = $time['names'];
        //出発エリア
        $outForm->raw_from_area_cds = $fromareas['ids'];
        $outForm->raw_from_area_lbls = $fromareas['names'];
        //到着エリア
        $outForm->raw_to_area_cds = $toareas['ids'];
        $outForm->raw_to_area_lbls = $toareas['names'];
        //引越し予定日
        $outForm->raw_move_date_year_cds = $years;
        $outForm->raw_move_date_year_lbls = $years;
        $outForm->raw_move_date_month_cds = $this->months;
        $outForm->raw_move_date_month_lbls = $this->months;
        $outForm->raw_move_date_day_cds = $this->days;
        $outForm->raw_move_date_day_lbls = $this->days;
        //第一希望日
        $outForm->raw_visit_date1_year_cds = $years;
        $outForm->raw_visit_date1_year_lbls = $years;
        $outForm->raw_visit_date1_month_cds = $this->months;
        $outForm->raw_visit_date1_month_lbls = $this->months;
        $outForm->raw_visit_date1_day_cds = $this->days;
        $outForm->raw_visit_date1_day_lbls = $this->days;
        //第二希望日
        $outForm->raw_visit_date2_year_cds = $years;
        $outForm->raw_visit_date2_year_lbls = $years;
        $outForm->raw_visit_date2_month_cds = $this->months;
        $outForm->raw_visit_date2_month_lbls = $this->months;
        $outForm->raw_visit_date2_day_cds = $this->days;
        $outForm->raw_visit_date2_day_lbls = $this->days;

        // チケット発行
        $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_PCV001);

        // セッション破棄
        $session->deleteForm(self::FEATURE_ID);
        
        return array('ticket'=>$ticket,
                         'outForm'=>$outForm,
                         'errorForm'=>$errorForm);
    }

    /**
     * 入力フォームの値を元に出力フォームを生成します。
     * @param Sgmov_Form_Pcv001In $inForm 入力フォーム
     * @return Sgmov_Form_Pcv001Out 出力フォーム
     */
    public function _createOutFormByInForm($inForm)
    {
        $outForm = new Sgmov_Form_Pcv001Out();

		$outForm->raw_company_name = $inForm->company_name;
		$outForm->raw_company_furigana = $inForm->company_furigana;
		$outForm->raw_charge_name = $inForm->charge_name;
		$outForm->raw_charge_furigana = $inForm->charge_furigana;
		$outForm->raw_tel1 = $inForm->tel1;
		$outForm->raw_tel2 = $inForm->tel2;
		$outForm->raw_tel3 = $inForm->tel3;
		$outForm->raw_tel_type_cd_sel = $inForm->tel_type_cd_sel;
		$outForm->raw_tel_other = $inForm->tel_other;
		$outForm->raw_mail = $inForm->mail;
		$outForm->raw_contact_method_cd_sel = $inForm->contact_method_cd_sel;
		$outForm->raw_contact_available_cd_sel = $inForm->contact_available_cd_sel;
		$outForm->raw_contact_start_cd_sel = $inForm->contact_start_cd_sel;
		$outForm->raw_contact_end_cd_sel = $inForm->contact_end_cd_sel;
		$outForm->raw_from_area_cd_sel = $inForm->from_area_cd_sel;
		$outForm->raw_to_area_cd_sel = $inForm->to_area_cd_sel;
		$outForm->raw_move_date_year_cd_sel = $inForm->move_date_year_cd_sel;
		$outForm->raw_move_date_month_cd_sel = $inForm->move_date_month_cd_sel;
		$outForm->raw_move_date_day_cd_sel = $inForm->move_date_day_cd_sel;
		$outForm->raw_visit_date1_year_cd_sel = $inForm->visit_date1_year_cd_sel;
		$outForm->raw_visit_date1_month_cd_sel = $inForm->visit_date1_month_cd_sel;
		$outForm->raw_visit_date1_day_cd_sel = $inForm->visit_date1_day_cd_sel;
		$outForm->raw_visit_date2_year_cd_sel = $inForm->visit_date2_year_cd_sel;
		$outForm->raw_visit_date2_month_cd_sel = $inForm->visit_date2_month_cd_sel;
		$outForm->raw_visit_date2_day_cd_sel = $inForm->visit_date2_day_cd_sel;
		$outForm->raw_cur_zip1 = $inForm->cur_zip1;
		$outForm->raw_cur_zip2 = $inForm->cur_zip2;
		$outForm->raw_cur_pref_cd_sel = $inForm->cur_pref_cd_sel;
		$outForm->raw_cur_address = $inForm->cur_address;
		$outForm->raw_cur_elevator_cd_sel = $inForm->cur_elevator_cd_sel;
		$outForm->raw_cur_floor = $inForm->cur_floor;
		$outForm->raw_cur_road_cd_sel = $inForm->cur_road_cd_sel;
		$outForm->raw_new_zip1 = $inForm->new_zip1;
		$outForm->raw_new_zip2 = $inForm->new_zip2;
		$outForm->raw_new_pref_cd_sel = $inForm->new_pref_cd_sel;
		$outForm->raw_new_address = $inForm->new_address;
		$outForm->raw_new_elevator_cd_sel = $inForm->new_elevator_cd_sel;
		$outForm->raw_new_floor = $inForm->new_floor;
		$outForm->raw_new_road_cd_sel = $inForm->new_road_cd_sel;
		$outForm->raw_number_of_people = $inForm->number_of_people;
		$outForm->raw_tsubo_su = $inForm->tsubo_su;
		$outForm->raw_comment = $inForm->comment;

        return $outForm;
    }

    /**
     * 表示用00～24時間を取得する）
     * @param object $db
     * @return
     */
    public function _fetchTime($start,$end)
    {
        $ids = array();
        $names = array();

        // 先頭に空白を追加
        $ids[] = '';
        $names[] = '';

        for ($i = $start; $i <= $end; $i++) {
            $ids[] = sprintf("%02d", $i);
            $names[] = $i;
        }

        return array('ids'=>$ids,
                         'names'=>$names);
    }
}
?>

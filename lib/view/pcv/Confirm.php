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
Sgmov_Lib::useForms(array('Error', 'PcvSession', 'Pcv002Out'));
/**#@-*/

 /**
 * 法人引越輸送確認画面を表示します。
 * @package    View
 * @subpackage PCV
 * @author     K.Hamada(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pcv_Confirm extends Sgmov_View_Pcv_Common
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
        $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_PCV002);

        return array('ticket'=>$ticket,
                         'outForm'=>$outForm);
    }

    /**
     * 入力フォームの値を元に出力フォームを生成します。
     * @param Sgmov_Form_Pcv001In $sessionForm 入力フォーム
     * @return Sgmov_Form_Pcv002Out 出力フォーム
     */
    public function _createOutFormFromInForm($sessionForm)
    {
        $outForm = new Sgmov_Form_Pcv002Out();

        // セッション値の適用
        $db = Sgmov_Component_DB::getPublic();
        $service = new Sgmov_Service_CoursePlan();
        $CourseList = $service->fetchCourseList($db);
        $PlanList = $service->fetchPlanList($db);
        $service = new Sgmov_Service_CenterArea();
        $FromAreas = $service->fetchFromAreaList($db);
        $ToAreas = $service->fetchToAreaList($db);
        $service = new Sgmov_Service_Prefecture();
        $prefs = $service->fetchPrefectures($db);
        $years = $this->getYears();

        $outForm->raw_company_name = $sessionForm->company_name;
        $outForm->raw_company_furigana = $sessionForm->company_furigana;
        $outForm->raw_charge_name = $sessionForm->charge_name;
        $outForm->raw_charge_furigana = $sessionForm->charge_furigana;
        if ( empty($sessionForm->tel1)) {
            $outForm->raw_tel = '';
        } else {
            $outForm->raw_tel = $sessionForm->tel1 . '-' . $sessionForm->tel2 . '-' . $sessionForm->tel3;
        }
        $outForm->raw_tel_type = $this->tel_type_lbls[$sessionForm->tel_type_cd_sel];
        $outForm->raw_tel_other = $sessionForm->tel_other;
        $outForm->raw_mail = $sessionForm->mail;
        $outForm->raw_contact_method = $this->contact_method_lbls[$sessionForm->contact_method_cd_sel];
        $outForm->raw_contact_available = $this->contact_available_lbls[$sessionForm->contact_available_cd_sel];
        $outForm->raw_contact_start = $this->contact_start_lbls[$sessionForm->contact_start_cd_sel];
        $outForm->raw_contact_end = $this->contact_end_lbls[$sessionForm->contact_end_cd_sel];
        $outForm->raw_from_area = $FromAreas['names'][array_search($sessionForm->from_area_cd_sel,$FromAreas['ids'])];
        $outForm->raw_to_area = $ToAreas['names'][array_search($sessionForm->to_area_cd_sel,$ToAreas['ids'])];
        $outForm->raw_move_date = $sessionForm->move_date_year_cd_sel . "年" . $sessionForm->move_date_month_cd_sel . "月" . $sessionForm->move_date_day_cd_sel . "日";
        $outForm->raw_visit_date1 = $sessionForm->visit_date1_year_cd_sel . "年" . $sessionForm->visit_date1_month_cd_sel. "月" . $sessionForm->visit_date1_day_cd_sel. "日";
        $outForm->raw_visit_date2 = $sessionForm->visit_date2_year_cd_sel . "年" . $sessionForm->visit_date2_month_cd_sel. "月" . $sessionForm->visit_date2_day_cd_sel. "日";
        if ( empty($sessionForm->cur_zip1)) {
            $outForm->raw_cur_zip = '';
        } else {
            $outForm->raw_cur_zip = $sessionForm->cur_zip1 . '-' . $sessionForm->cur_zip2;
        }
        $outForm->raw_cur_address_all = $prefs['names'][array_search($sessionForm->cur_pref_cd_sel, $prefs['ids'])].$sessionForm->cur_address;
        $outForm->raw_cur_elevator = $this->elevator_lbls[$sessionForm->cur_elevator_cd_sel];
        $outForm->raw_cur_floor = $sessionForm->cur_floor;
        $outForm->raw_cur_road = $this->road_lbls[$sessionForm->cur_road_cd_sel];
        if ( empty($sessionForm->new_zip1)) {
            $outForm->raw_new_zip = '';
        } else {
            $outForm->raw_new_zip = $sessionForm->new_zip1 . '-' . $sessionForm->new_zip2;
        }
        $outForm->raw_new_address_all = $prefs['names'][array_search($sessionForm->new_pref_cd_sel, $prefs['ids'])].$sessionForm->new_address;
        $outForm->raw_new_elevator = $this->elevator_lbls[$sessionForm->new_elevator_cd_sel];
        $outForm->raw_new_floor = $sessionForm->new_floor;
        $outForm->raw_new_road = $this->road_lbls[$sessionForm->new_road_cd_sel];
        $outForm->raw_number_of_people = $sessionForm->number_of_people;
        $outForm->raw_tsubo_su = $sessionForm->tsubo_su;
        $outForm->raw_comment = $sessionForm->comment;

        return $outForm;
    }
}
?>

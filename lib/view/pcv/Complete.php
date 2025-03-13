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
Sgmov_Lib::useForms(array('Error', 'PcvSession', 'Pcv003Out'));
/**#@-*/

 /**
 * 法人オフィス移転訪問見積もり内容を登録し、完了画面を表示します。
 * @package    View
 * @subpackage PCV
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pcv_Complete extends Sgmov_View_Pcv_Common
{
    /**
     * 訪問見積もりサービス
     * @var Sgmov_Service_VisitEstimate
     */
    public $_VisitEstimateService;

    public function __construct()
    {
        $this->_VisitEstimateService = new Sgmov_Service_VisitEstimate();
    }
    /**
     * 処理を実行します。
     * <ol><li>
     * セッションの継続を確認
     * </li><li>
     * チケットの確認と破棄
     * </li><li>
     * 入力チェック
     * </li><li>
     * セッションから情報を取得
     * </li><li>
     * 情報をDBへ格納
     * </li><li>
     * 出力情報を設定
     * </li><li>
     * セッション情報を破棄
     * </li></ol>
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['outForm']:出力フォーム
     * </li></ul>
     */
    public function executeInner()
    {
        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();

        //チケットの確認と破棄
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_PCV002, $this->_getTicket());

        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        //登録用IDを取得
        $id = $this->_VisitEstimateService->select_id($db);

        // セッションから情報を取得
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        $data = $this->_createInsertDataFromInForm($sessionForm->in, $id);

        //情報をDBへ格納
        $this->_VisitEstimateService->insert($db, $data);

        // 出力情報を設定
        $outForm = new Sgmov_Form_Pcv003Out();
        $outForm->raw_mail = $sessionForm->in->mail;

        // セッション情報を破棄
        $session->deleteForm(self::FEATURE_ID);

        return array('outForm'=>$outForm);
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
     * 入力フォームの値を元にインサート用データを生成します。
     * @param Sgmov_Form_Pcv001-006In $inForm 入力フォーム
     * @return array インサート用データ
     */
    public function _createInsertDataFromInForm($sessionForm, $id)
    {
        $data = array();
        $data['id'] = $id;
        $data['pre_exist_flag'] = 'false';
        $data['company_flag'] = 'true';
        $data['course_id'] = null;
        $data['plan_id'] = null;
        $data['pre_aircon_exist_flag'] = null;
        $data['from_area_id'] = $sessionForm->from_area_cd_sel;
        $data['to_area_id'] = $sessionForm->to_area_cd_sel;
        if ($sessionForm->move_date_year_cd_sel === "") {
            $data['move_date'] = null;
        } else {
            $data['move_date'] = $sessionForm->move_date_year_cd_sel . "/" . $sessionForm->move_date_month_cd_sel . "/" . $sessionForm->move_date_day_cd_sel;
        }
        $data['pre_base_price'] = null;
        $data['pre_estimate_price'] = null;
        if ($sessionForm->visit_date1_year_cd_sel === "") {
            $data['visit_date1'] = null;
        } else {
            $data['visit_date1'] = $sessionForm->visit_date1_year_cd_sel . "/" . $sessionForm->visit_date1_month_cd_sel . "/" . $sessionForm->visit_date1_day_cd_sel;
        }
        if ($sessionForm->visit_date2_year_cd_sel === "") {
            $data['visit_date2'] = null;
        } else {
            $data['visit_date2'] = $sessionForm->visit_date2_year_cd_sel . "/" . $sessionForm->visit_date2_month_cd_sel . "/" . $sessionForm->visit_date2_day_cd_sel;
        }
        $data['cur_zip'] = $sessionForm->cur_zip1.$sessionForm->cur_zip2;
        $data['cur_pref_id'] = $sessionForm->cur_pref_cd_sel;
        $data['cur_address'] = $sessionForm->cur_address;
        if ($sessionForm->cur_elevator_cd_sel === "") {
            $data['cur_elevator_cd'] = null;
        } else {
            $data['cur_elevator_cd'] = $sessionForm->cur_elevator_cd_sel;
        }
        if ($sessionForm->cur_floor === "") {
            $data['cur_floor'] = null;
        } else {
            $data['cur_floor'] = $sessionForm->cur_floor;
        }
        if ($sessionForm->cur_road_cd_sel === "") {
            $data['cur_road_cd'] = null;
        } else {
            $data['cur_road_cd'] = $sessionForm->cur_road_cd_sel;
        }
        $data['new_zip'] = $sessionForm->new_zip1.$sessionForm->new_zip2;
        if ($sessionForm->new_pref_cd_sel === "") {
            $data['new_pref_id'] = null;
        } else {
            $data['new_pref_id'] = $sessionForm->new_pref_cd_sel;
        }
        $data['new_address'] = $sessionForm->new_address;
        if ($sessionForm->new_elevator_cd_sel === "") {
            $data['new_elevator_cd'] = null;
        } else {
            $data['new_elevator_cd'] = $sessionForm->new_elevator_cd_sel;
        }
        if ($sessionForm->new_floor === "") {
            $data['new_floor'] = null;
        } else {
            $data['new_floor'] = $sessionForm->new_floor;
        }
        if ($sessionForm->new_road_cd_sel === "") {
            $data['new_road_cd'] = null;
        } else {
            $data['new_road_cd'] = $sessionForm->new_road_cd_sel;
        }
        $data['name'] = $sessionForm->company_name;
        $data['furigana'] = $sessionForm->company_furigana;
        $data['tel'] = $sessionForm->tel1.$sessionForm->tel2.$sessionForm->tel3;
        if ($sessionForm->tel_type_cd_sel === "") {
            $data['tel_type_cd'] = null;
        } else {
            $data['tel_type_cd'] = $sessionForm->tel_type_cd_sel;
        }
        $data['tel_other'] = $sessionForm->tel_other;
        if ($sessionForm->contact_available_cd_sel === "") {
            $data['contact_available_cd'] = null;
        } else {
            $data['contact_available_cd'] = $sessionForm->contact_available_cd_sel;
        }
        if ($sessionForm->contact_start_cd_sel === "") {
            $data['contact_start_cd'] = null;
        } else {
            $data['contact_start_cd'] = sprintf("%02d",$sessionForm->contact_start_cd_sel)."00";
        }
        if ($sessionForm->contact_end_cd_sel === "") {
            $data['contact_end_cd'] = null;
        } else {
            $data['contact_end_cd'] = sprintf("%02d",$sessionForm->contact_end_cd_sel)."00";
        }
        $data['mail'] = $sessionForm->mail;
        $data['note'] = $sessionForm->comment;
        $data['company_name'] = $sessionForm->company_name;
        $data['company_furigana'] = $sessionForm->company_furigana;
        $data['charge_name'] = $sessionForm->charge_name;
        $data['charge_furigana'] = $sessionForm->charge_furigana;
        if ($sessionForm->contact_method_cd_sel === "") {
            $data['contact_method_cd'] = null;
        } else {
            $data['contact_method_cd'] = $sessionForm->contact_method_cd_sel;
        }
        if ($sessionForm->number_of_people === "") {
            $data['num_people'] = null;
        } else {
            $data['num_people'] = $sessionForm->number_of_people;
        }
        if ($sessionForm->tsubo_su === "") {
            $data['tsubo_su'] = null;
        } else {
            $data['tsubo_su'] = $sessionForm->tsubo_su;
        }
        return $data;
    }
}
?>

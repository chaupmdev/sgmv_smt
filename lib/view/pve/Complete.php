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
Sgmov_Lib::useView('pve/Common', 'CommonConst');
Sgmov_Lib::useForms(array('Error', 'PveSession', 'Pve003Out'));
/**#@-*/

 /**
 * 法人オフィス移転訪問見積もり内容を登録し、完了画面を表示します。
 * @package    View
 * @subpackage PVE
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pve_Complete extends Sgmov_View_Pve_Common
{
    /**
     * 概算見積もりサービス
     * @var Sgmov_Service_PreCampaign
     */
    public $_PreCampaignService;

    /**
     * 訪問見積もりサービス
     * @var Sgmov_Service_VisitEstimate
     */
    public $_VisitEstimateService;


    /**
     * マンション サービス
     * @var Sgmov_Service_Apartment
     */
    public $_apartmentService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        $this->_VisitEstimateService = new Sgmov_Service_VisitEstimate();
        $this->_PreCampaignService = new Sgmov_Service_PreCampaign();

        $this->_apartmentService = new Sgmov_Service_Apartment();
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
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_PVE002, $this->_getTicket());

        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        //登録用IDを取得
        $id = $this->_VisitEstimateService->select_id($db);

        // 概算見積もり情報
        /* @var $sessionForm_pre Sgmov_Form_PveSession */
        $sessionForm_pre = $session->loadForm(self::SCRID_TOPVE);

        // セッションから情報を取得
        /* @var $sessionForm Sgmov_Form_PveSession */
        $sessionForm = $session->loadForm(self::FEATURE_ID);
Sgmov_Component_Log::debug($sessionForm->in);Sgmov_Component_Log::debug($sessionForm_pre);
        $data = $this->_createInsertDataFromInForm($db, $sessionForm->in, $sessionForm_pre, $id);
Sgmov_Component_Log::debug($data);
        // 情報をDBへ格納
        $this->_VisitEstimateService->insert($db, $data);

        //キャンペーン情報があればセット
        if (isset($sessionForm_pre)) {
            $datapre = $this->_createInsertPreDataFromInForm($sessionForm_pre, $id);
            if ($datapre != NULL) {
                $this->_PreCampaignService->insert($db, $datapre);
            }
        }

        // 出力情報を設定
        $outForm = $this->_createOutFormFromInForm($sessionForm->in);

        // セッション情報を破棄
        $session->deleteForm(self::FEATURE_ID);
        $session->deleteForm(self::SCRID_TOPVE);

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
     * 入力フォームの値を元に訪問申し込みインサート用データを生成します。
     * @param $db
     * @param $_sessionForm Sgmov_Form_Pve001In ( 訪問見積りページ の入力値 )
     * @param $_sessionForm_pre Sgmov_Form_PveSession ( 概算見積りページ の入力値 )
     * @return array インサート用データ
     */
    public function _createInsertDataFromInForm($db, $_sessionForm, $_sessionForm_pre, $id)
    {
        $data = array();
        $data['id'] = $id;
        if (isset($_sessionForm_pre)) {
            //概算見積もり情報があるとき
            $data['pre_exist_flag'] = 1;
            if($_sessionForm_pre->pre_aircon_exist === '1'){
                $data['pre_aircon_exist_flag'] = 'true';
            }else if($_sessionForm_pre->pre_aircon_exist === '0'){
                $data['pre_aircon_exist_flag'] = 'false';
            }else{
                $data['pre_aircon_exist_flag'] = null;
            }
            $data['pre_base_price'] = $_sessionForm_pre->pre_base_price;
            $data['pre_estimate_price'] = $_sessionForm_pre->pre_estimate_price;
            $data['from_area_id'] = $_sessionForm_pre->pre_from_area;
            $data['to_area_id'] = $_sessionForm_pre->pre_to_area;
            $data['course_id'] = $_sessionForm_pre->pre_course;
            $data['plan_id'] = $_sessionForm_pre->pre_plan;
            $data['other_operation_id'] = $_sessionForm_pre->oc_id;
        } else {
            //訪問のみのとき
            $data['pre_exist_flag'] = 0;
            $data['pre_aircon_exist_flag'] = NULL;
            $data['pre_base_price'] = NULL;
            $data['pre_estimate_price'] = NULL;
            if (strlen($_sessionForm->course_cd_sel) > 0) {
                $data['course_id'] = $_sessionForm->course_cd_sel;
            } else {
                $data['course_id'] = null;
            }
            if (strlen($_sessionForm->plan_cd_sel) > 0) {
                $data['plan_id'] = $_sessionForm->plan_cd_sel;
            } else {
                $data['plan_id'] = null;
            }
            if (strlen($_sessionForm->from_area_cd_sel) > 0) {
                $data['from_area_id'] = $_sessionForm->from_area_cd_sel;
            } else {
                $data['from_area_id'] = null;
            }
            if (strlen($_sessionForm->to_area_cd_sel) > 0) {
                $data['to_area_id'] = $_sessionForm->to_area_cd_sel;
            } else {
                $data['to_area_id'] = null;
            }
        }
        $data['company_flag'] = 'false';
        if ($_sessionForm->move_date_year_cd_sel === "") {
            $data['move_date'] = null;
        } else {
            $data['move_date'] = $_sessionForm->move_date_year_cd_sel . "/" . $_sessionForm->move_date_month_cd_sel . "/" . $_sessionForm->move_date_day_cd_sel;
        }
        if ($_sessionForm->visit_date1_year_cd_sel === "") {
            $data['visit_date1'] = null;
        } else {
            $data['visit_date1'] = $_sessionForm->visit_date1_year_cd_sel . "/" . $_sessionForm->visit_date1_month_cd_sel . "/" . $_sessionForm->visit_date1_day_cd_sel;
        }
        if ($_sessionForm->visit_date2_year_cd_sel === "") {
            $data['visit_date2'] = null;
        } else {
            $data['visit_date2'] = $_sessionForm->visit_date2_year_cd_sel . "/" . $_sessionForm->visit_date2_month_cd_sel . "/" . $_sessionForm->visit_date2_day_cd_sel;
        }
        $data['cur_zip'] = $_sessionForm->cur_zip1 . $_sessionForm->cur_zip2;
        $data['cur_pref_id'] = $_sessionForm->cur_pref_cd_sel;
        $data['cur_address'] = $_sessionForm->cur_address;
        if ($_sessionForm->cur_elevator_cd_sel === "") {
            $data['cur_elevator_cd'] = null;
        } else {
            $data['cur_elevator_cd'] = $_sessionForm->cur_elevator_cd_sel;
        }
        if ($_sessionForm->cur_floor === "") {
            $data['cur_floor'] = null;
        } else {
            $data['cur_floor'] = $_sessionForm->cur_floor;
        }
        if ($_sessionForm->cur_road_cd_sel === "") {
            $data['cur_road_cd'] = null;
        } else {
            $data['cur_road_cd'] = $_sessionForm->cur_road_cd_sel;
        }
        $data['new_zip'] = $_sessionForm->new_zip1 . $_sessionForm->new_zip2;
        if ($_sessionForm->new_pref_cd_sel === "") {
            $data['new_pref_id'] = null;
        } else {
            $data['new_pref_id'] = $_sessionForm->new_pref_cd_sel;
        }
        $data['new_address'] = $_sessionForm->new_address;
        if ($_sessionForm->new_elevator_cd_sel === "") {
            $data['new_elevator_cd'] = null;
        } else {
            $data['new_elevator_cd'] = $_sessionForm->new_elevator_cd_sel;
        }
        if ($_sessionForm->new_floor === "") {
            $data['new_floor'] = null;
        } else {
            $data['new_floor'] = $_sessionForm->new_floor;
        }
        if ($_sessionForm->new_road_cd_sel === "") {
            $data['new_road_cd'] = null;
        } else {
            $data['new_road_cd'] = $_sessionForm->new_road_cd_sel;
        }
        $data['name'] = $_sessionForm->name;
        $data['furigana'] = $_sessionForm->furigana;
        $data['tel'] = $_sessionForm->tel1 . $_sessionForm->tel2 . $_sessionForm->tel3;
        if ($_sessionForm->tel_type_cd_sel === "") {
            $data['tel_type_cd'] = null;
        } else {
            $data['tel_type_cd'] = $_sessionForm->tel_type_cd_sel;
        }
        $data['tel_other'] = $_sessionForm->tel_other;
        if ($_sessionForm->contact_available_cd_sel === "") {
            $data['contact_available_cd'] = null;
        } else {
            $data['contact_available_cd'] = $_sessionForm->contact_available_cd_sel;
        }
        if ($_sessionForm->contact_start_cd_sel === "") {
            $data['contact_start_cd'] = null;
        } else {
            $data['contact_start_cd'] = sprintf("%02d",$_sessionForm->contact_start_cd_sel)."00";
        }
        if ($_sessionForm->contact_end_cd_sel === "") {
            $data['contact_end_cd'] = null;
        } else {
            $data['contact_end_cd'] = sprintf("%02d",$_sessionForm->contact_end_cd_sel)."00";
        }
        $data['mail'] = $_sessionForm->mail;
        $data['note'] = $_sessionForm->comment;
        $data['company_name'] = null;
        $data['company_furigana'] = null;
        $data['charge_name'] = null;
        $data['charge_furigana'] = null;
        $data['contact_method_cd'] = null;
        $data['num_people'] = null;
        $data['tsubo_su'] = null;

        switch ($_sessionForm->menu_personal) {
            case 'ladys':
                $apartment_id    = null;
                $work_summary_cd = 12; // 12: レディース引っ越し
                break;
            case 'moving':
                $apartment_id    = null;
                $work_summary_cd = 0;
                break;
            case 'transport':
                $apartment_id    = null;
                $work_summary_cd = 0;
                break;
            case 'overseas':
                $apartment_id    = null;
                $work_summary_cd = 0;
                break;
            case 'voyage':
                $apartment_id    = null;
                $work_summary_cd = 0;
                break;
            case 'mansion':
                $apartment_cd = $_sessionForm->apartment_cd_sel;

                $result = $this->_apartmentService->fetchApartment($db, array( "cd" => $apartment_cd ));
                if ($result->size()) {
                    $row = $result->get(0);
                    $apartment_id = $row["id"];
                } else {
                    $apartment_id = null;
                }
                $work_summary_cd = 0;
                break;
            default:
                $apartment_id    = null;
                $work_summary_cd = 0;
                break;
        }
        $data["apartment_id"] = $apartment_id;          // apartment_id integer
        $data["work_summary_cd"] = $work_summary_cd;    // work_summary_cd integer

        return $data;
    }
    /**
     * 入力フォームの値を元にキャンペーンインサート用データを生成します。
     * @param Sgmov_Form_Pve001-003In $inForm 入力フォーム
     * @return array インサート用データ
     */
    public function _createInsertPreDataFromInForm($sessionForm, $id)
    {
        $dataUmu = false;
        $seq = 1;
        $data = array();

        if (isset($sessionForm->pre_cam_discount_names[0]) && $sessionForm->pre_cam_discount_names[0] != NULL) {
            for ($i = 0; $i < count($sessionForm->pre_cam_discount_names); $i++) {
                $data[] = array($id, $seq, $sessionForm->pre_cam_discount_names[$i],
                            $sessionForm->pre_cam_discount_contents[$i], $sessionForm->pre_cam_discount_starts[$i],
                            $sessionForm->pre_cam_discount_ends[$i], $sessionForm->pre_cam_discount_prices[$i],
                            Sgmov_View_CommonConst::TOKKA_CAMPAIGNSETTEI);
                $seq++;
            }
            $dataUmu = true;
        }

        if (isset($sessionForm->pre_cam_kansanhanbo_names[0]) && $sessionForm->pre_cam_kansanhanbo_names[0] != NULL) {
            for ($i = 0; $i < count($sessionForm->pre_cam_kansanhanbo_names); $i++) {
                $data[] = array($id, $seq, $sessionForm->pre_cam_kansanhanbo_names[$i],
                            $sessionForm->pre_cam_kansanhanbo_contents[$i], $sessionForm->pre_cam_kansanhanbo_starts[$i],
                            $sessionForm->pre_cam_kansanhanbo_ends[$i], $sessionForm->pre_cam_kansanhanbo_prices[$i],
                            Sgmov_View_CommonConst::TOKKA_KANSAN_HANBOUKI_RYOKINSETTEI);
                $seq++;
            }
            $dataUmu = true;
        }

        if (!$dataUmu) {
            return NULL;
        }

        return $data;
    }

    /**
     * セッションの値を元に出力フォームを生成します。
     * @param $_sessionForm Sgmov_Form_Pve001In 入力フォーム
     * @return Sgmov_Form_Pve003Out 出力フォーム
     */
    public function _createOutFormFromInForm($_sessionForm)
    {
        $outForm = new Sgmov_Form_Pve003Out();

        $outForm->raw_mail = $_sessionForm->mail;
        $outForm->raw_pre_exist_flag = $_sessionForm->pre_exist_flag;

        $outForm->raw_menu_personal = $_sessionForm->menu_personal;
        $outForm->raw_apartment_cd_sel = $_sessionForm->apartment_cd_sel;

        return $outForm;
    }

}
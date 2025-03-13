<?php
/**
 * @package    ClassDefFile
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/**#@+
 * include files
 */
require_once dirname(__FILE__).'/../../Lib.php';
Sgmov_Lib::useView('pve/Common');
Sgmov_Lib::useForms(array('Error', 'PveSession', 'Pve002Out'));
/**#@-*/
/**
 * 訪問見積り確認画面を表示します。
 * @package    View
 * @subpackage PVE
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pve_Confirm extends Sgmov_View_Pve_Common {

    /**
     * 共通サービス
     * @var Sgmov_Service_AppCommon
     */
    public $_appCommon;
    /**
     * コースコードサービス
     * @var Sgmov_Service_Course
     */
    public $_CourseService;
    /**
     * 都道府県サービス
     * @var Sgmov_Service_Prefecture
     */
    public $_PrefectureService;
    /**
     * 拠点・エリアサービス
     * @var Sgmov_Service_CenterArea
     */
    public $_centerAreaService;
    /**
     * 郵便・住所サービス
     * @var Sgmov_Service_Yubin
     */
    public $_YubinService;

    /**
     * マンション サービス
     * @var Sgmov_Service_Apartment
     */
    public $_apartmentService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_centerAreaService = new Sgmov_Service_CenterArea();
        $this->_YubinService = new Sgmov_Service_Yubin();
        $this->_PrefectureService = new Sgmov_Service_Prefecture();
        $this->_CourseService = new Sgmov_Service_CoursePlan();
        $this->_appCommon = new Sgmov_Service_AppCommon();

        $this->_apartmentService = new Sgmov_Service_Apartment();
    }
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
    public function executeInner() {

        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();

        // DB接続
        $db = Sgmov_Component_DB::getPublic();

        // 概算見積もり情報
        /* @var $sessionForm_pre Sgmov_Form_PveSession */
        $sessionForm_pre = $session->loadForm(self::SCRID_TOPVE);

        // セッションに入力チェック済みの情報があるかどうかを確認
        /* @var $sessionForm Sgmov_Form_PveSession */
        $sessionForm = $session->loadForm(self::FEATURE_ID);

        if (!isset($sessionForm) || $sessionForm->status != self::VALIDATION_SUCCEEDED) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
        }

        // コース・プラン・出発エリア・到着エリアのリストを取得しておく
//        $Courses = $this->_CourseService->fetchCourseList($db);
        $Courses = $this->_appCommon->getCources_amount();
        $Plans = $this->_CourseService->fetchPlanList($db);
        $toAreas = $this->_centerAreaService->fetchToAreaList($db);
        $fromAreas = $this->_centerAreaService->fetchFromAreaList($db);
        $pref = $this->_PrefectureService->fetchPrefectures($db);

        $apartments = $this->_apartmentService->fetchApartments($db, true);

        // セッション情報を元に出力情報を設定
        $outForm = $this->_createOutFormFromInForm($sessionForm->in, $sessionForm_pre, $Courses, $Plans, $toAreas, $fromAreas, $pref, $apartments);

        // チケットを発行
        $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_PVE002);

        return array('ticket' => $ticket,
            'outForm' => $outForm);
    }
    /**
     * 入力フォームの値を元に出力フォームを生成します。
     * @param $_sessionForm Sgmov_Form_Pve001In 前ページからの入力値
     * @param $_sessionForm_pre Sgmov_Form_PveSession 概算見積りページからの入力値
     * @return Sgmov_Form_Pve002Out 出力フォーム
     */
    public function _createOutFormFromInForm($_sessionForm, $_sessionForm_pre, $Corses, $Plans, $toAreas, $fromAreas, $prefs, $apartments) {

        $outForm = new Sgmov_Form_Pve002Out();

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
        $years = $this->_appCommon->getYears(date('Y'), Sgmov_Service_AppCommon::INPUT_MOVEYTIYEAR_CNT, true);

        //概算見積もり情報が存在する場合
        if (isset($_sessionForm_pre)) {

            $outForm->raw_pre_exist_flag = $_sessionForm_pre->pre_exist_flag;
            $outForm->raw_pre_course = $_sessionForm_pre->pre_course_name;
            $outForm->raw_pre_plan = $_sessionForm_pre->pre_plan_name;
            $outForm->raw_pre_aircon_exist = $_sessionForm_pre->pre_aircon_exist;
            $outForm->raw_pre_from_area = $_sessionForm_pre->pre_from_area_name;
            $outForm->raw_pre_to_area = $_sessionForm_pre->pre_to_area_name;
            $outForm->raw_pre_move_date = $_sessionForm_pre->pre_move_date;
            $outForm->raw_pre_estimate_price = $_sessionForm_pre->pre_estimate_price;
            $outForm->raw_pre_estimate_base_price = $_sessionForm_pre->pre_estimate_base_price;
            $outForm->raw_pre_cam_discount_names = $_sessionForm_pre->pre_cam_discount_names;
            $outForm->raw_pre_cam_discount_contents = $_sessionForm_pre->pre_cam_discount_contents;
            $outForm->raw_pre_cam_discount_starts = $_sessionForm_pre->pre_cam_discount_starts;
            $outForm->raw_pre_cam_discount_ends = $_sessionForm_pre->pre_cam_discount_ends;
            $outForm->raw_oc_id = $_sessionForm_pre->oc_id;
            $outForm->raw_oc_name = $_sessionForm_pre->oc_name;
            $outForm->raw_oc_content = $_sessionForm_pre->oc_content;

        }

        $outForm->raw_menu_personal = $_sessionForm->menu_personal;     // 概算ページ在りでも 訪問ページへ引き継いでいる為 訪問ページの設定値を使用します。

        if ($outForm->raw_menu_personal == "mansion") {
            // マンション有りの場合
            $outForm->raw_apartment_name = $apartments["names"][ array_search($_sessionForm->apartment_cd_sel, $apartments["ids"]) ];
        } else {
            $outForm->raw_apartment_name = "";
        }

        //訪問見積もり
        $outForm->raw_course = $Corses['names'][array_search($_sessionForm->course_cd_sel, $Corses['ids'])];
        $outForm->raw_plan = $Plans['names'][array_search($_sessionForm->plan_cd_sel, $Plans['ids'])];

        $outForm->raw_from_area = $fromAreas['names'][array_search($_sessionForm->from_area_cd_sel, $fromAreas['ids'])];
        $outForm->raw_to_area = $toAreas['names'][array_search($_sessionForm->to_area_cd_sel, $toAreas['ids'])];

        $outForm->raw_move_date = $this->_appCommon->getYmd($_sessionForm->move_date_year_cd_sel.$_sessionForm->move_date_month_cd_sel.$_sessionForm->move_date_day_cd_sel);
        $outForm->raw_visit_date1 = $this->_appCommon->getYmd($_sessionForm->visit_date1_year_cd_sel.$_sessionForm->visit_date1_month_cd_sel.$_sessionForm->visit_date1_day_cd_sel);
        $outForm->raw_visit_date2 = $this->_appCommon->getYmd($_sessionForm->visit_date2_year_cd_sel.$_sessionForm->visit_date2_month_cd_sel.$_sessionForm->visit_date2_day_cd_sel);

        if (empty($_sessionForm->cur_zip1)) {
            $outForm->raw_cur_zip = '';
        } else {
            $outForm->raw_cur_zip = $_sessionForm->cur_zip1.'-'.$_sessionForm->cur_zip2;
        }
        $outForm->raw_cur_address_all = $prefs['names'][array_search($_sessionForm->cur_pref_cd_sel, $prefs['ids'])].$_sessionForm->cur_address;
        $outForm->raw_cur_elevator = $this->elevator_lbls[$_sessionForm->cur_elevator_cd_sel];
        $outForm->raw_cur_floor = $_sessionForm->cur_floor;
        $outForm->raw_cur_road = $this->road_lbls[$_sessionForm->cur_road_cd_sel];
        if (empty($_sessionForm->new_zip1)) {
            $outForm->raw_new_zip = '';
        } else {
            $outForm->raw_new_zip = $_sessionForm->new_zip1.'-'.$_sessionForm->new_zip2;
        }
        $outForm->raw_new_address_all = $prefs['names'][array_search($_sessionForm->new_pref_cd_sel, $prefs['ids'])].$_sessionForm->new_address;
        $outForm->raw_new_elevator = $this->elevator_lbls[$_sessionForm->new_elevator_cd_sel];
        $outForm->raw_new_floor = $_sessionForm->new_floor;
        $outForm->raw_new_road = $this->road_lbls[$_sessionForm->new_road_cd_sel];
        $outForm->raw_name = $_sessionForm->name;
        $outForm->raw_furigana = $_sessionForm->furigana;
        if (empty($_sessionForm->tel1)) {
            $outForm->raw_tel = '';
        } else {
            $outForm->raw_tel = $_sessionForm->tel1.'-'.$_sessionForm->tel2.'-'.$_sessionForm->tel3;
        }
        $outForm->raw_tel_type = $this->tel_type_lbls[$_sessionForm->tel_type_cd_sel];
        $outForm->raw_tel_other = $_sessionForm->tel_other;
        $outForm->raw_contact_available = $this->contact_available_lbls[$_sessionForm->contact_available_cd_sel];
        $outForm->raw_contact_start = $this->contact_start_lbls[$_sessionForm->contact_start_cd_sel];
        $outForm->raw_contact_end = $this->contact_end_lbls[$_sessionForm->contact_end_cd_sel];
        $outForm->raw_mail = $_sessionForm->mail;
        $outForm->raw_comment = $_sessionForm->comment;
        return $outForm;
    }

    /**
     * 指定文字列が空でない場合、指定単位を付けて返します。
     * 引き数の入力チェックは空かどうかのみ行います。
     *
     * @param $str 文字列
     * @param $tani 単位
     * @param $prace 単位を付ける位置
     * @return 指定単位付き文字列
     */
    public static function getTani($str, $tani, $prace) {

        if (isset($str) && $str != "") {
            if ($prace == 0) {
                return $tani.$str;
            }
            return $str.$tani;
        }

        return "";
    }
}
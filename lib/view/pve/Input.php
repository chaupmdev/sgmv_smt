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
Sgmov_Lib::useForms(array('Error', 'PveSession', 'Pve001Out'));
/**#@-*/
/**
 * 訪問見積もり申し込み入力画面を表示します。
 * @package    View
 * @subpackage PVE
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pve_Input extends Sgmov_View_Pve_Common {

    /**
     * 共通サービス
     * @var Sgmov_Service_AppCommon
     */
    public $_appCommon;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_appCommon = new Sgmov_Service_AppCommon();
    }

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
    public function executeInner() {
//        // GETパラメータ取得
//        $planCd = $this->_parseGetParameter();

        // セッションに情報があるかどうかを確認
        $session = Sgmov_Component_Session::get();

        $refer_menu_personal_flg = isset($_POST["referer"]) && $_POST["referer"] == "menu_personal" && isset($_POST["personal"]);
        if ($refer_menu_personal_flg)
        {
            // 個人向けサービス ページから飛んだ時は、残っている前回入力値 ( セッション ) を使用しません。

            $session->deleteForm(self::FEATURE_ID);
            $session->deleteForm(self::SCRID_TOPVE);

            /* @var $sessionForm Sgmov_Form_PveSession */
            $sessionForm = null;
            $sessionForm_pre = null;

        } else {

            // 訪問見積り情報  ( Sgmov_Form_PveSession )
            $sessionForm = $session->loadForm(self::FEATURE_ID);

            // 概算見積もり情報
            $sessionForm_pre = $session->loadForm(self::SCRID_TOPVE);
        }

      // DB接続
        $db = Sgmov_Component_DB::getPublic();

        $outForm = new Sgmov_Form_Pve001Out();
        $errorForm = NULL;
        if (isset($sessionForm_pre) && !isset($sessionForm)) {
            // 概算見積り情報あり、かつ、訪問見積り情報なし  ( 概算見積り ページ → 訪問見積り ページ )

            // 概算見積りページのセッション情報を使用します。

            $outForm = $this->_createOutFormByPRE($outForm, $sessionForm_pre);

            $errorForm = new Sgmov_Form_Error();

        } else {

            if (isset($sessionForm)) {
                // 訪問見積り情報あり（エラー発生時など）  ( .. → 訪問見積り ページ → 訪問見積り ページ (入力値エラーなどで同じページへ) )

                // 概算の入力値の上に、訪問の入力値で上書きします。

                if (isset($sessionForm_pre)) {
                    // 概算見積り情報の取得

                    $outForm = $this->_createOutFormByPRE($outForm, $sessionForm_pre);
                }
                // 訪問見積り情報の取得
                $outForm = $this->_createOutFormByInForm($outForm, $sessionForm->in);
                // セッション情報を元に出力情報を作成
                $errorForm = $sessionForm->error;
                // セッション破棄
                $sessionForm->error = NULL;

            } else {

                // 新規ページ ( お問い合わせ または 個人向けサービス ページ → 訪問見積り ページ )

                // セッション破棄
                $session->deleteForm(self::FEATURE_ID);
                $session->deleteForm(self::SCRID_TOPVE);
		
                // 出力情報を設定
                $outForm = new Sgmov_Form_Pve001Out();
                $errorForm = new Sgmov_Form_Error();

                if (isset($_POST["personal"])) {
                    // 個人様向けサービス ページ から
                    $outForm->raw_menu_personal = $_POST["personal"];
                }
            }

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
        $years = $this->_appCommon->getYears(date('Y'), Sgmov_Service_AppCommon::INPUT_MOVEYTIYEAR_CNT, true);

        // 電話連絡可能開始時刻・終了時刻
        $time = $this->_fetchTime('9', '17');
        
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
        $outForm->raw_move_date_month_cds = $this->_appCommon->months;
        $outForm->raw_move_date_month_lbls = $this->_appCommon->months;
        $outForm->raw_move_date_day_cds = $this->_appCommon->days;
        $outForm->raw_move_date_day_lbls = $this->_appCommon->days;

        // マンション名 データベースから値を取得
        $apartments = $outForm->_ApartmentService->fetchApartments($db, true);
        $outForm->raw_apartment_cds = $apartments["ids"];
        $outForm->raw_apartment_lbls = $apartments["names"];

//        //第一希望日
//        $outForm->raw_visit_date1_year_cds = $years;
//        $outForm->raw_visit_date1_year_lbls = $years;
//        $outForm->raw_visit_date1_month_cds = $this->_appCommon->months;
//        $outForm->raw_visit_date1_month_lbls = $this->_appCommon->months;
//        $outForm->raw_visit_date1_day_cds = $this->_appCommon->days;
//        $outForm->raw_visit_date1_day_lbls = $this->_appCommon->days;
//
//        //第二希望日
//        $outForm->raw_visit_date2_year_cds = $years;
//        $outForm->raw_visit_date2_year_lbls = $years;
//        $outForm->raw_visit_date2_month_cds = $this->_appCommon->months;
//        $outForm->raw_visit_date2_month_lbls = $this->_appCommon->months;
//        $outForm->raw_visit_date2_day_cds = $this->_appCommon->days;
//        $outForm->raw_visit_date2_day_lbls = $this->_appCommon->days;
//
//        // 初期選択プラン
//        if (isset($planCd)) {
//          $outForm->raw_plan_cd_sel = $planCd;
//        }

        // チケット発行
        $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_PVE001);
        return array('ticket' => $ticket,
            'outForm' => $outForm,
            'errorForm' => $errorForm);
    }
    /**
     * 概算見積もり入力フォームの値を元に出力フォームを生成します。
     * @param Sgmov_Form_Pve001Out $outForm 出力フォーム
     * @param Sgmov_Form_PveSession $inForm 入力フォーム
     * @return Sgmov_Form_Pve001Out 出力フォーム
     */
    public function _createOutFormByPRE($outForm, $inForm) {

        $outForm->raw_pre_exist_flag = 1;
        $outForm->raw_pre_course = $inForm->pre_course_name;
        $outForm->raw_pre_plan = $inForm->pre_plan_name;
        $outForm->raw_pre_aircon_exist = $inForm->pre_aircon_exist;
        $outForm->raw_pre_from_area = $inForm->pre_from_area_name;
        $outForm->raw_pre_to_area = $inForm->pre_to_area_name;
        $outForm->raw_pre_move_date = $inForm->pre_move_date;
        $outForm->raw_pre_estimate_price = $inForm->pre_estimate_price;
        $outForm->raw_pre_estimate_base_price = $inForm->pre_estimate_base_price;
        $outForm->raw_pre_estimate_price = $inForm->pre_estimate_price;
        $outForm->raw_pre_estimate_base_price = $inForm->pre_estimate_base_price;
        $outForm->raw_oc_id = $inForm->oc_id;
        $outForm->raw_oc_name = $inForm->oc_name;
        $outForm->raw_oc_content = $inForm->oc_content;
        $tmpDisCampNms = $inForm->pre_cam_discount_names;

        if (count($tmpDisCampNms) == 0 && $tmpDisCampNms != NULL) {
            $outForm->raw_pre_cam_discount_names = NULL;
            $outForm->raw_pre_cam_discount_contents = NULL;
            $outForm->raw_pre_cam_discount_starts = NULL;
            $outForm->raw_pre_cam_discount_ends = NULL;
            $outForm->raw_pre_cam_discount_prices = NULL;
        } else {
            $outForm->raw_pre_cam_discount_names = $inForm->pre_cam_discount_names;
            $outForm->raw_pre_cam_discount_contents = $inForm->pre_cam_discount_contents;
            $outForm->raw_pre_cam_discount_starts = $inForm->pre_cam_discount_starts;
            $outForm->raw_pre_cam_discount_ends = $inForm->pre_cam_discount_ends;
            $outForm->raw_pre_cam_discount_prices = $inForm->pre_cam_discount_prices;
        }

        $tmpKanHanCampNms = $inForm->pre_cam_kansanhanbo_names;
        if (count($tmpKanHanCampNms) == 0 && $tmpKanHanCampNms != NULL) {
            $outForm->raw_pre_cam_kansanhanbo_names = NULL;
            $outForm->raw_pre_cam_kansanhanbo_contents = NULL;
            $outForm->raw_pre_cam_kansanhanbo_starts = NULL;
            $outForm->raw_pre_cam_kansanhanbo_ends = NULL;
            $outForm->raw_pre_cam_kansanhanbo_prices = NULL;
        } else {
            $outForm->raw_pre_cam_kansanhanbo_names = $inForm->pre_cam_kansanhanbo_names;
            $outForm->raw_pre_cam_kansanhanbo_contents = $inForm->pre_cam_kansanhanbo_contents;
            $outForm->raw_pre_cam_kansanhanbo_starts = $inForm->pre_cam_kansanhanbo_starts;
            $outForm->raw_pre_cam_kansanhanbo_ends = $inForm->pre_cam_kansanhanbo_ends;
            $outForm->raw_pre_cam_kansanhanbo_prices = $inForm->pre_cam_kansanhanbo_prices;
        }

        $outForm->raw_course_cd_sel = $inForm->pre_course;
        $outForm->raw_plan_cd_sel = $inForm->pre_plan;
        $outForm->raw_from_area_cd_sel = $inForm->pre_from_area;
        $outForm->raw_to_area_cd_sel = $inForm->pre_to_area;
        $outForm->raw_move_date_year_cd_sel = substr($inForm->pre_move_date, 0, 3);
        $outForm->raw_move_date_month_cd_sel = substr($inForm->pre_move_date, 4, 5);
        $outForm->raw_move_date_day_cd_sel = substr($inForm->pre_move_date, 6, 7);

        $outForm->raw_menu_personal = $inForm->pre_menu_personal;

        return $outForm;
    }
    /**
     * 訪問見積り入力フォームの値を出力フォームを生成します。
     * @param Sgmov_Form_Pve001Out $outForm 出力フォーム
     * @param Sgmov_Form_Pve001In $inForm 入力フォーム
     * @return Sgmov_Form_Pve001Out 出力フォーム
     */
    public function _createOutFormByInForm($outForm, $inForm) {
Sgmov_Component_Log::debug($inForm);Sgmov_Component_Log::debug($outForm);
        $outForm->raw_course_cd_sel          = self::getSessionValue($inForm, 'course_cd_sel');
        $outForm->raw_plan_cd_sel            = self::getSessionValue($inForm, 'plan_cd_sel');
        $outForm->raw_from_area_cd_sel       = self::getSessionValue($inForm, 'from_area_cd_sel');
        $outForm->raw_to_area_cd_sel         = self::getSessionValue($inForm, 'to_area_cd_sel');
        $outForm->raw_move_date_year_cd_sel  = self::getSessionValue($inForm, 'move_date_year_cd_sel');
        $outForm->raw_move_date_month_cd_sel = self::getSessionValue($inForm, 'move_date_month_cd_sel');
        $outForm->raw_move_date_day_cd_sel   = self::getSessionValue($inForm, 'move_date_day_cd_sel');

        $outForm->raw_menu_personal    = self::getSessionValue($inForm, 'menu_personal');
        $outForm->raw_apartment_cd_sel = self::getSessionValue($inForm, 'apartment_cd_sel');

        $outForm->raw_visit_date1_year_cd_sel  = self::getSessionValue($inForm, 'visit_date1_year_cd_sel');
        $outForm->raw_visit_date1_month_cd_sel = self::getSessionValue($inForm, 'visit_date1_month_cd_sel');
        $outForm->raw_visit_date1_day_cd_sel   = self::getSessionValue($inForm, 'visit_date1_day_cd_sel');
        $outForm->raw_visit_date2_year_cd_sel  = self::getSessionValue($inForm, 'visit_date2_year_cd_sel');
        $outForm->raw_visit_date2_month_cd_sel = self::getSessionValue($inForm, 'visit_date2_month_cd_sel');
        $outForm->raw_visit_date2_day_cd_sel   = self::getSessionValue($inForm, 'visit_date2_day_cd_sel');
        $outForm->raw_cur_zip1                 = self::getSessionValue($inForm, 'cur_zip1');
        $outForm->raw_cur_zip2                 = self::getSessionValue($inForm, 'cur_zip2');
        $outForm->raw_cur_pref_cd_sel          = self::getSessionValue($inForm, 'cur_pref_cd_sel');
        $outForm->raw_cur_address              = self::getSessionValue($inForm, 'cur_address');
        $outForm->raw_cur_elevator_cd_sel      = self::getSessionValue($inForm, 'cur_elevator_cd_sel');
        $outForm->raw_cur_floor                = self::getSessionValue($inForm, 'cur_floor');
        $outForm->raw_cur_road_cd_sel          = self::getSessionValue($inForm, 'cur_road_cd_sel');
        $outForm->raw_new_zip1                 = self::getSessionValue($inForm, 'new_zip1');
        $outForm->raw_new_zip2                 = self::getSessionValue($inForm, 'new_zip2');
        $outForm->raw_new_pref_cd_sel          = self::getSessionValue($inForm, 'new_pref_cd_sel');
        $outForm->raw_new_address              = self::getSessionValue($inForm, 'new_address');
        $outForm->raw_new_elevator_cd_sel      = self::getSessionValue($inForm, 'new_elevator_cd_sel');
        $outForm->raw_new_floor                = self::getSessionValue($inForm, 'new_floor');
        $outForm->raw_new_road_cd_sel          = self::getSessionValue($inForm, 'new_road_cd_sel');
        $outForm->raw_name                     = self::getSessionValue($inForm, 'name');
        $outForm->raw_furigana                 = self::getSessionValue($inForm, 'furigana');
        $outForm->raw_tel1                     = self::getSessionValue($inForm, 'tel1');
        $outForm->raw_tel2                     = self::getSessionValue($inForm, 'tel2');
        $outForm->raw_tel3                     = self::getSessionValue($inForm, 'tel3');
        $outForm->raw_tel_type_cd_sel          = self::getSessionValue($inForm, 'tel_type_cd_sel');
        $outForm->raw_tel_other                = self::getSessionValue($inForm, 'tel_other');
        $outForm->raw_contact_available_cd_sel = self::getSessionValue($inForm, 'contact_available_cd_sel');
        $outForm->raw_contact_start_cd_sel     = self::getSessionValue($inForm, 'contact_start_cd_sel');
        $outForm->raw_contact_end_cd_sel       = self::getSessionValue($inForm, 'contact_end_cd_sel');
        $outForm->raw_mail                     = self::getSessionValue($inForm, 'mail');
        $outForm->raw_comment                  = self::getSessionValue($inForm, 'comment');
Sgmov_Component_Log::debug($outForm);
        return $outForm;
    }
    /**
     * 表示用00～24時間を取得する）
     * @param object $db
     * @return
     */
    public function _fetchTime($start, $end) {
        $ids = array();
        $names = array();
        // 先頭に空白を追加
        $ids[] = '';
        $names[] = '';
        for ($i = $start; $i <= $end; $i++) {
            $ids[] = sprintf("%02d", $i);
            $names[] = $i;
        }
        return array('ids' => $ids,
            'names' => $names);
    }

//
//    /**
//     * GETパラメータを取得します。
//     *
//     * @param none
//     * @return plan_cd
//     */
//    public function _parseGetParameter() {
//
//        if (!isset($_GET['param'])) {
//            return NULL;
//        } else {
//
//            $params = explode('/', $_GET['param']);
//
//            if (!ereg("^[0-9]+$", $params[0]) || !(1 <= $params[0] && $params[0] <= 5)) {
//                // 半角数字、または、1～5以内でない場合、0をセット
//                return 0;
//            }
//
//            // 先頭以外の要素は無視
//            return $params[0];
//        }
//
//    }

    /**
     * プルダウンを生成し、HTMLソースを返します。
     *
     * @param $cds コードの配列
     * @param $lbls ラベルの配列
     * @param $select 選択値
     * @return 生成されたプルダウン
     */
    public static function _createAreaPulldown($cds, $lbls, $select, $mode) {

        $html = "";

        if ($mode == Sgmov_View_Pve_Common::AREA_HYOJITYPE_OKINAWANASHI) {
            // 単身カーゴプラン
            // TODO 沖縄県が末尾にある前提
            for ($i = 0; $i < (count($cds) - 1); ++$i) {
                if ($select === $cds[$i]) {
                    $html .= '<option selected="selected" value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
                } else {
                    $html .= '<option value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
                }
            }
        } else if ($mode == Sgmov_View_Pve_Common::AREA_HYOJITYPE_AIRCARGO) {
            // 単身エアカーゴプラン
            // TODO 北海道（札幌市）、東京23区、大阪府、福岡県のコードが変わらない前提
            // TODO 将来的に、以下の東京23区、大阪府、福岡県のコードをシステム全体で1つのファイルに保持しておきたいところ
            for ($i = 0; $i < count($cds); ++$i) {
                if (in_array($cds[$i], array("", "1", "17", "32", "45"))) {
                    if ($select === $cds[$i]) {
                        $html .= '<option selected="selected" value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
                    } else {
                        $html .= '<option value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
                    }
                }
            }
        } else if ($mode == Sgmov_View_Pve_Common::AREA_HYOJITYPE_AIRCARGO_TO_FUK) {
            // 単身エアカーゴプラン(福岡発)
            // TODO 北海道（札幌市）、東京23区、大阪府、福岡県のコードが変わらない前提
            // TODO 将来的に、以下の東京23区、大阪府、福岡県のコードをシステム全体で1つのファイルに保持しておきたいところ
            for ($i = 0; $i < count($cds); ++$i) {
                if (in_array($cds[$i], array("", "1", "17"))) {
                    if ($select === $cds[$i]) {
                        $html .= '<option selected="selected" value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
                    } else {
                        $html .= '<option value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
                    }
                }
            }
        } else if ($mode == Sgmov_View_Pve_Common::AREA_HYOJITYPE_AIRCARGO_TO_TOK) {
            // 単身エアカーゴプラン(東京発)
            // TODO 北海道（札幌市）、東京23区、大阪府、福岡県のコードが変わらない前提
            // TODO 将来的に、以下の東京23区、大阪府、福岡県のコードをシステム全体で1つのファイルに保持しておきたいところ
            for ($i = 0; $i < count($cds); ++$i) {
                if (in_array($cds[$i], array("", "1", "45"))) {
                    if ($select === $cds[$i]) {
                        $html .= '<option selected="selected" value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
                    } else {
                        $html .= '<option value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
                    }
                }
            }
        } else if ($mode == Sgmov_View_Pve_Common::AREA_HYOJITYPE_AIRCARGO_TO_HOK) {
            // 単身エアカーゴプラン(北海道発)
            // TODO 北海道（札幌市）、東京23区、大阪府、福岡県のコードが変わらない前提
            // TODO 将来的に、以下の東京23区、大阪府、福岡県のコードをシステム全体で1つのファイルに保持しておきたいところ
            for ($i = 0; $i < count($cds); ++$i) {
                if (in_array($cds[$i], array("", "17", "32", "45"))) {
                    if ($select === $cds[$i]) {
                        $html .= '<option selected="selected" value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
                    } else {
                        $html .= '<option value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
                    }
                }
            }
        } else if ($mode == Sgmov_View_Pve_Common::AREA_HYOJITYPE_AIRCARGO_TO) {
            // 単身エアカーゴプラン(出発)
            // TODO 北海道（札幌市）、東京23区、福岡県のコードが変わらない前提
            // TODO 将来的に、以下の東京23区、福岡県のコードをシステム全体で1つのファイルに保持しておきたいところ
            for ($i = 0; $i < count($cds); ++$i) {
                if (in_array($cds[$i], array("", "1", "17", "45"))) {
                    if ($select === $cds[$i]) {
                        $html .= '<option id="air_' . $cds[$i] . '" value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
                    } else {
                        $html .= '<option id="air_' . $cds[$i] . '" value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
                    }
                }
            }
        } else {
            for ($i = 0; $i < count($cds); ++$i) {
                if ($select === $cds[$i]) {
                    $html .= '<option selected="selected" value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
                } else {
                    $html .= '<option value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
                }
            }
        }

        return $html;
    }


    /**
     * プルダウンを生成し、HTMLソースを返します。
     * TODO pre/Inputと同記述あり
     *
     * @param $cds コードの配列
     * @param $lbls ラベルの配列
     * @param $select 選択値
     * @return 生成されたプルダウン
     */
    public static function _createPulldown($cds, $lbls, $select) {

        $html = '';

        $count = count($cds);
        for ($i = 0; $i < $count; ++$i) {
            if ($select === $cds[$i]) {
                $html .= '<option selected="selected" value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
            } else {
                $html .= '<option value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
            }
        }

        return $html;
    }
}
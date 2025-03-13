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
Sgmov_Lib::useForms(array('Error', 'PveSession', 'Pve001In'));Sgmov_Component_Log::debug('開始');
/**#@-*/
/**
 * 訪問見積もり申し込み入力情報をチェックします。
 * @package    View
 * @subpackage PVE
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pve_CheckInput extends Sgmov_View_Pve_Common {

    /**
     * コースコードサービス
     * @var Sgmov_Service_Prefecture
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
     * コースプランサービス
     * @var Sgmov_Service_CoursePlan
     */
    public $_coursePlanService;


    /**
     * マンション サービス
     * @var Sgmov_Service_Apartment
     */
    public $_apartmentService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {Sgmov_Component_Log::debug('コンストラクタ開始');
        $this->_centerAreaService = new Sgmov_Service_CenterArea();
        $this->_YubinService = new Sgmov_Service_Yubin();
        $this->_PrefectureService = new Sgmov_Service_Prefecture();
        $this->_CourseService = new Sgmov_Service_CoursePlan();
        $this->_coursePlanService = new Sgmov_Service_CoursePlan();

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
     * 情報をセッションに保存
     * </li><li>
     * 入力エラー無し
     *   <ol><li>
     *   pve/confirm へリダイレクト
     *   </li></ol>
     * </li><li>
     * 入力エラー有り
     *   <ol><li>
     *   pve/input へリダイレクト
     *   </li></ol>
     * </li></ol>
     */
    public function executeInner() {

Sgmov_Component_Log::debug('開始');

        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();

Sgmov_Component_Log::debug($session);

        // 概算見積もり情報
        /* @var $sessionForm_pre Sgmov_Form_PveSession */
        $sessionForm_pre = $session->loadForm(self::SCRID_TOPVE);

Sgmov_Component_Log::debug($sessionForm_pre);

        // DB接続
        $db = Sgmov_Component_DB::getPublic();
        $db_yubin = Sgmov_Component_DB::getYubinPublic();

Sgmov_Component_Log::debug($db);

        // コース・プラン・出発エリア・到着エリアのリストを取得しておく
        $Courses   = $this->_CourseService->fetchCourseList($db);
        $Plans     = $this->_CourseService->fetchPlanList($db);
        $toAreas   = $this->_centerAreaService->fetchToAreaList($db);
        $fromAreas = $this->_centerAreaService->fetchFromAreaList($db);
        $pref      = $this->_PrefectureService->fetchPrefectures($db);

        $apartments = $this->_apartmentService->fetchApartments($db, true);		// 空白行の選択肢を含めておかないと、validator の isIn() でエラーになります

Sgmov_Component_Log::debug(__LINE__);

        // チケットの確認と破棄
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_PVE001, $this->_getTicket());

Sgmov_Component_Log::debug(__LINE__);

        // 入力チェック
        /* @var $inForm Sgmov_Form_Pve001In */
        $inForm = $this->_createInFormFromPost($_POST, $sessionForm_pre);
        $errorForm = $this->_validate($inForm, $db, $Courses['ids'], $Plans['ids'], $toAreas['ids'], $fromAreas['ids'], $pref, $apartments["ids"]);

Sgmov_Component_Log::debug('入力チェック終了');

        // 情報をセッションに保存

        $sessionForm = new Sgmov_Form_PveSession();
        $sessionForm->in = $inForm;
        $sessionForm->error = $errorForm;

Sgmov_Component_Log::debug('セッション保存1');

        if ($errorForm->hasError()) {
            $sessionForm->status = self::VALIDATION_FAILED;
        } else {
            $sessionForm->status = self::VALIDATION_SUCCEEDED;
        }

Sgmov_Component_Log::debug('セッション保存2');

        $session->saveForm(self::FEATURE_ID, $sessionForm);

Sgmov_Component_Log::debug('セッション保存終了');

        // リダイレクト
        if ($errorForm->hasError()) {

Sgmov_Component_Log::debug('リダイレクト開始1');

            Sgmov_Component_Redirect::redirectPublicSsl('/pve/input/');
        } else {

Sgmov_Component_Log::debug('リダイレクト開始2');

            Sgmov_Component_Redirect::redirectPublicSsl('/pve/confirm/');
        }

    }
    /**
     * POST値からチケットを取得します。
     * チケットが存在しない場合は空文字列を返します。
     * @return string チケット文字列
     */
    public function _getTicket() {
        if (!isset($_POST['ticket'])) {
            $ticket = '';
        } else {
            $ticket = $_POST['ticket'];
        }
        return $ticket;
    }
    /**
     * POST情報から入力フォームを生成します。
     *
     * 全ての値は正規化されてフォームに設定されます。
     *
     * @param $post array ポスト情報
     * @param $sessionForm_pre Sgmov_Form_PveSession
     * @return Sgmov_Form_Pve001In 入力フォーム
     */
    public function _createInFormFromPost($post, $sessionForm_pre) {

        $inForm = new Sgmov_Form_Pve001In();

        // チケット
        $inForm->ticket = $post['ticket'];

        //　概算見積り情報
        if ($post['pre_exist_flag'] === '1') {
            $inForm->pre_exist_flag = 1;
            $inForm->pre_course = $sessionForm_pre->pre_course;
            $inForm->pre_plan = $sessionForm_pre->pre_plan;
            $inForm->pre_aircon_exist = $sessionForm_pre->pre_aircon_exist;
            $inForm->pre_from_area = $sessionForm_pre->pre_from_area;
            $inForm->pre_to_area = $sessionForm_pre->pre_to_area;
            $inForm->pre_move_date = $sessionForm_pre->pre_move_date;
            $inForm->pre_estimate_price = $sessionForm_pre->pre_estimate_price;
            $inForm->pre_estimate_base_price = $sessionForm_pre->pre_estimate_base_price;
            $inForm->pre_cam_discount_names    = $sessionForm_pre->pre_cam_discount_names;
            $inForm->pre_cam_discount_contents = $sessionForm_pre->pre_cam_discount_contents;
            $inForm->pre_cam_discount_starts   = $sessionForm_pre->pre_cam_discount_starts;
            $inForm->pre_cam_discount_ends     = $sessionForm_pre->pre_cam_discount_ends;

            // 引越し予定日
            $inForm->move_date_year_cd_sel  = substr($post['pre_move_date'], 0, 4);
            $inForm->move_date_month_cd_sel = substr($post['pre_move_date'], 4, 2);
            $inForm->move_date_day_cd_sel   = substr($post['pre_move_date'], 6, 2);

            // 個人向けサービス ページの選択されたメニュー
            $inForm->menu_personal = isset($sessionForm_pre->pre_menu_personal) ? $sessionForm_pre->pre_menu_personal : "";	// input tag は残していますが セッションから。

        } else {
            $inForm->pre_exist_flag = 0;
            $inForm->move_date_year_cd_sel  = $post['move_date_year_cd_sel'];
            $inForm->move_date_month_cd_sel = $post['move_date_month_cd_sel'];
            $inForm->move_date_day_cd_sel   = $post['move_date_day_cd_sel'];

            // 個人向けサービス ページの選択されたメニュー
            $inForm->menu_personal = filter_input(INPUT_POST, 'personal');

        }

        //以下訪問項目
        if (isset($post['course_cd_sel'])) {
            $inForm->course_cd_sel = $post['course_cd_sel'];
        } else {
            $inForm->course_cd_sel = '';
        }
        if (isset($post['plan_cd_sel'])) {
            $inForm->plan_cd_sel = $post['plan_cd_sel'];
        } else {
            $inForm->plan_cd_sel = '';
        }

        // 出発地域コード
        if (isset($post['formareacd'])) {
//            $inForm->from_area_cd_sel = $post['from_area_cd_sel'];
            $inForm->from_area_cd_sel = $post['formareacd'];
        } else {
            $inForm->from_area_cd_sel = "";
        }
        // 到着地域コード
        if (isset($post['toareacd'])) {
//            $inForm->to_area_cd_sel = $post['to_area_cd_sel'];
            $inForm->to_area_cd_sel = $post['toareacd'];
        } else {
            $inForm->to_area_cd_sel = "";
        }

        //
        if ($inForm->menu_personal == "mansion") {
        	$inForm->apartment_cd_sel = filter_input(INPUT_POST, 'apartment_cd_sel');
        } else {
        	$inForm->apartment_cd_sel = "";
        }

        $inForm->visit_date1_year_cd_sel  = $post['visit_date1_year_cd_sel'];
        $inForm->visit_date1_month_cd_sel = $post['visit_date1_month_cd_sel'];
        $inForm->visit_date1_day_cd_sel   = $post['visit_date1_day_cd_sel'];
        $inForm->visit_date2_year_cd_sel  = $post['visit_date2_year_cd_sel'];
        $inForm->visit_date2_month_cd_sel = $post['visit_date2_month_cd_sel'];
        $inForm->visit_date2_day_cd_sel   = $post['visit_date2_day_cd_sel'];
        $inForm->cur_zip1 = $post['cur_zip1'];
        $inForm->cur_zip2 = $post['cur_zip2'];
        $inForm->cur_pref_cd_sel = $post['cur_pref_cd_sel'];
        $inForm->cur_address = $post['cur_address'];
        if (isset($post['cur_elevator_cd_sel'])) {
            $inForm->cur_elevator_cd_sel = $post['cur_elevator_cd_sel'];
        } else {
            $inForm->cur_elevator_cd_sel = '';
        }
        $inForm->cur_floor = $post['cur_floor'];
        if (isset($post['cur_road_cd_sel'])) {
            $inForm->cur_road_cd_sel = $post['cur_road_cd_sel'];
        } else {
            $inForm->cur_road_cd_sel = '';
        }
        $inForm->new_zip1 = $post['new_zip1'];
        $inForm->new_zip2 = $post['new_zip2'];
        $inForm->new_pref_cd_sel = $post['new_pref_cd_sel'];
        $inForm->new_address = $post['new_address'];
        if (isset($post['new_elevator_cd_sel'])) {
            $inForm->new_elevator_cd_sel = $post['new_elevator_cd_sel'];
        } else {
            $inForm->new_elevator_cd_sel = '';
        }
        $inForm->new_floor = $post['new_floor'];
        if (isset($post['new_road_cd_sel'])) {
            $inForm->new_road_cd_sel = $post['new_road_cd_sel'];
        } else {
            $inForm->new_road_cd_sel = '';
        }
        $inForm->name = $post['name'];
        $inForm->furigana = $post['furigana'];
        $inForm->tel1 = $post['tel1'];
        $inForm->tel2 = $post['tel2'];
        $inForm->tel3 = $post['tel3'];
        if (isset($post['tel_type_cd_sel'])) {
            $inForm->tel_type_cd_sel = $post['tel_type_cd_sel'];
        } else {
            $inForm->tel_type_cd_sel = '';
        }
        $inForm->tel_other = $post['tel_other'];
        if (isset($post['contact_available_cd_sel'])) {
            $inForm->contact_available_cd_sel = $post['contact_available_cd_sel'];
        } else {
            $inForm->contact_available_cd_sel = '';
        }
        $inForm->contact_start_cd_sel = $post['contact_start_cd_sel'];
        $inForm->contact_end_cd_sel = $post['contact_end_cd_sel'];
        $inForm->mail = $post['mail'];
        $inForm->comment = $post['comment'];

        return $inForm;
    }
    /**
     * 入力値の妥当性検査を行います。
     * @param $inForm Sgmov_Form_Pve001In 入力フォーム
     * @param $db
     * @param $CorseCds
     * @param $PlanCds
     * @param $toAreaCds
     * @param $fromAreaCds
     * @param $pref
     * @param array $apartmentCds ( 新規 マンション名情報 cd 配列 )
     *
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($inForm, $db, $CorseCds, $PlanCds, $toAreaCds, $fromAreaCds, $pref, $apartmentCds) {

        // 入力チェック
        $errorForm = new Sgmov_Form_Error();
        $min = strtotime(date('Ymd', strtotime('+1 week')));
//        $max = strtotime(date('Ymd', strtotime('+6 month')));
        $max = strtotime(date('Ymd', strtotime('+2 month')));
        //コースコード選択値 値範囲チェック 必須チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->course_cd_sel)->isIn($CorseCds);
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        $v->isSelected();
        if (!$v->isValid()) {
            $errorForm->addError('top_course_cd_sel', $v->getResultMessageTop());
        } else {
            $CourseCheck = true;
        }
        //プランコード選択値 値範囲チェック 必須チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->plan_cd_sel)->isIn($PlanCds);
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        $v->isSelected();
        if (!$v->isValid()) {
            $errorForm->addError('top_plan_cd_sel', $v->getResultMessageTop());
        } else {
            $PlanCheck = true;
        }
        //コースプランの整合性チェック
        if (isset($CourseCheck) && isset($PlanCheck)) {
            $PlanListByCourse = $this->_CourseService->fetchPlanListByCourse($db, $inForm->course_cd_sel);
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->plan_cd_sel)->isIn($PlanListByCourse['ids']);
            if (!$v->isValid()) {
                $errorForm->addError('top_plan_cd_sel', $v->getResultMessageTop());
            }
        }

        // マンション の 必須、選択チェック
        if ($inForm->menu_personal == "mansion") {
        	$v = Sgmov_Component_Validator::createSingleValueValidator($inForm->apartment_cd_sel)->isIn($apartmentCds);
        	if (!$v->isValid()) {
        		Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        	}
        	$v->isSelected();
        	if (!$v->isValid()) {
        		$errorForm->addError("apartment_cd_sel", $v->getResultMessageTop());
        	}
        }

        // 現在お住まいの地域 値範囲チェック 必須チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->from_area_cd_sel)->isIn($fromAreaCds);
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        $v->isSelected();
        if (!$v->isValid()) {
            $errorForm->addError('top_from_area_cd_sel', $v->getResultMessageTop());
        }
        // お引越し先の地域 値範囲チェック 必須チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->to_area_cd_sel)->isIn($toAreaCds);
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        $v->isSelected();
        if (!$v->isValid()) {
            $errorForm->addError('top_to_area_cd_sel', $v->getResultMessageTop());
        }

        // お引越し予定日 有効日チェック
        //print("お引越し予定日".substr($inForm->pre_move_date, 0, 4).substr($inForm->pre_move_date, 4, 2).substr($inForm->pre_move_date, 6, 2)."<br>");

        if ($inForm->pre_exist_flag === 1) {
            $v = Sgmov_Component_Validator::createDateValidator(substr($inForm->pre_move_date, 0, 4), substr($inForm->pre_move_date, 4, 2), substr($inForm->pre_move_date, 6, 2))->isDate($min, $max);
            $move_date_flag = 1;
        } else {
            $v = Sgmov_Component_Validator::createDateValidator($inForm->move_date_year_cd_sel, $inForm->move_date_month_cd_sel, $inForm->move_date_day_cd_sel)->isDate($min, $max);
            if ($inForm->move_date_year_cd_sel !== "" && $inForm->move_date_month_cd_sel !== "" && $inForm->move_date_day_cd_sel !== "") {
                $move_date_flag = 1;
            } else {
                $move_date_flag = "";
            }
        }
        if (!$v->isValid()) {
            $errorForm->addError('top_move_date', $v->getResultMessageTop());
        } else {
            ////////////////////////////////////////////////////////////////////
            // お引越し予定日 繁忙期:日付範囲チェック
            ////////////////////////////////////////////////////////////////////
            if ($inForm->plan_cd_sel == '1' || $inForm->plan_cd_sel == '2') { // 1:単身カーゴプランの場合 || 2:単身AIR CARGO プラン
                // 入力チェック
                $min2Date = date('Y/n/j', strtotime('2019-03-21 00:00:00'));
                $max2Date = date('Y/n/j', strtotime('2019-03-31 23:59:59'));
                $min2 = date('Y-m-d H:i:s', strtotime('2019-03-21 00:00:00'));
                $max2 = date('Y-m-d H:i:s', strtotime('2019-03-31 23:59:59'));
                $selectDate = date('Y-m-d H:i:s', 
                        strtotime("{$inForm->move_date_year_cd_sel}-{$inForm->move_date_month_cd_sel}-{$inForm->move_date_day_cd_sel} 00:00:00"));

//                $v = Sgmov_Component_Validator::createDateValidator($inForm->move_date_year_cd_sel, $inForm->move_date_month_cd_sel, $inForm->move_date_day_cd_sel)->isDate($min, $max);
                if ($min2 <= $selectDate && $selectDate <= $max2) {
                    $errorForm->addError('top_move_date', "には{$min2Date}から{$max2Date}までの期間は選択できません。");
                }
            } else if ($inForm->plan_cd_sel == '4' 
                    || $inForm->plan_cd_sel == '3' 
                    || $inForm->plan_cd_sel == '5') { // 4:まるごとおまかせプラン || 3:スタンダードプラン || 5:チャータープラン
                // 入力チェック
                $min2Date = date('Y/n/j', strtotime('2019-03-15 00:00:00'));
                $max2Date = date('Y/n/j', strtotime('2019-04-08 23:59:59'));
                $min2 = date('Y-m-d H:i:s', strtotime('2019-03-15 00:00:00'));
                $max2 = date('Y-m-d H:i:s', strtotime('2019-04-08 23:59:59'));
                $selectDate = date('Y-m-d H:i:s', 
                        strtotime("{$inForm->move_date_year_cd_sel}-{$inForm->move_date_month_cd_sel}-{$inForm->move_date_day_cd_sel} 00:00:00"));

//                $v = Sgmov_Component_Validator::createDateValidator($inForm->move_date_year_cd_sel, $inForm->move_date_month_cd_sel, $inForm->move_date_day_cd_sel)->isDate($min, $max);
                if ($min2 <= $selectDate && $selectDate <= $max2) {
                    $errorForm->addError('top_move_date', "には{$min2Date}から{$max2Date}までの期間は選択できません。");
                }
            }
        }

        // 訪問見積もり希望日時第一希望日 有効日チェック 訪問希望日1 < 予定日
        $v = Sgmov_Component_Validator::createDateValidator($inForm->visit_date1_year_cd_sel, $inForm->visit_date1_month_cd_sel, $inForm->visit_date1_day_cd_sel)->isDate($min, $max);
        if (!$v->isValid()) {
            $errorForm->addError('top_visit_date1', $v->getResultMessageTop());
        }
        if ($inForm->visit_date1_year_cd_sel !== "" && $inForm->visit_date1_month_cd_sel !== "" && $inForm->visit_date1_day_cd_sel !== "") {
            $visit_date1_flag = 1;
        } else {
            $visit_date1_flag = "";
        }
        // 有効日で引越し予定日と第一希望日に入力があれば訪問希望日1 < 予定日
        if (!$errorForm->hasError('top_visit_date1') && $move_date_flag === 1 && $visit_date1_flag === 1) {
            $move = mktime(0, 0, 0, $inForm->move_date_month_cd_sel, $inForm->move_date_day_cd_sel, $inForm->move_date_year_cd_sel);
            $visit1 = mktime(0, 0, 0, $inForm->visit_date1_month_cd_sel, $inForm->visit_date1_day_cd_sel, $inForm->visit_date1_year_cd_sel);
            if ($move <= $visit1) {
                $errorForm->addError('top_visit_date1', 'はお引越し予定日以前にしてください。');
            } else {
                ////////////////////////////////////////////////////////////////////
                // 訪問見積もり希望日時第一希望日 繁忙期:日付範囲チェック
                ////////////////////////////////////////////////////////////////////
                if ($inForm->plan_cd_sel == '1' || $inForm->plan_cd_sel == '2') { // 1:単身カーゴプランの場合 || 2:単身AIR CARGO プラン
                    // 入力チェック
                    $min2Date = date('Y/n/j', strtotime('2019-03-21 00:00:00'));
                    $max2Date = date('Y/n/j', strtotime('2019-03-31 23:59:59'));
                    $min2 = date('Y-m-d H:i:s', strtotime('2019-03-21 00:00:00'));
                    $max2 = date('Y-m-d H:i:s', strtotime('2019-03-31 23:59:59'));
                    $selectDate = date('Y-m-d H:i:s', 
                            strtotime("{$inForm->visit_date1_year_cd_sel}-{$inForm->visit_date1_month_cd_sel}-{$inForm->visit_date1_day_cd_sel} 00:00:00"));

    //                $v = Sgmov_Component_Validator::createDateValidator($inForm->visit_date1_year_cd_sel, $inForm->visit_date1_month_cd_sel, $inForm->visit_date1_day_cd_sel)->isDate($min, $max);
                    if ($min2 <= $selectDate && $selectDate <= $max2) {
                        $errorForm->addError('top_visit_date1', "には{$min2Date}から{$max2Date}までの期間は選択できません。");
                    }
                } else if ($inForm->plan_cd_sel == '4' 
                        || $inForm->plan_cd_sel == '3' 
                        || $inForm->plan_cd_sel == '5') { // 4:まるごとおまかせプラン || 3:スタンダードプラン || 5:チャータープラン
                    // 入力チェック
                    $min2Date = date('Y/n/j', strtotime('2019-03-15 00:00:00'));
                    $max2Date = date('Y/n/j', strtotime('2019-04-08 23:59:59'));
                    $min2 = date('Y-m-d H:i:s', strtotime('2019-03-15 00:00:00'));
                    $max2 = date('Y-m-d H:i:s', strtotime('2019-04-08 23:59:59'));
                    $selectDate = date('Y-m-d H:i:s', 
                            strtotime("{$inForm->visit_date1_year_cd_sel}-{$inForm->visit_date1_month_cd_sel}-{$inForm->visit_date1_day_cd_sel} 00:00:00"));

    //                $v = Sgmov_Component_Validator::createDateValidator($inForm->visit_date1_year_cd_sel, $inForm->visit_date1_month_cd_sel, $inForm->visit_date1_day_cd_sel)->isDate($min, $max);
                    if ($min2 <= $selectDate && $selectDate <= $max2) {
                        $errorForm->addError('top_visit_date1', "には{$min2Date}から{$max2Date}までの期間は選択できません。");
                    }
                }
            }
        }
        // 訪問見積もり希望日時第二希望日 有効日チェック 訪問希望日2 < 予定日
        $v = Sgmov_Component_Validator::createDateValidator($inForm->visit_date2_year_cd_sel, $inForm->visit_date2_month_cd_sel, $inForm->visit_date2_day_cd_sel)->isDate($min, $max);
        if (!$v->isValid()) {
            $errorForm->addError('top_visit_date2', $v->getResultMessageTop());
        }
        if ($inForm->visit_date2_year_cd_sel !== "" && $inForm->visit_date2_month_cd_sel !== "" && $inForm->visit_date2_day_cd_sel !== "") {
            $visit_date2_flag = 1;
        } else {
            $visit_date2_flag = "";
        }
        // 有効日で引越し予定日と第二希望日に入力があれば訪問希望日2 < 予定日
        if (!$errorForm->hasError('top_visit_date2') && $move_date_flag === 1 && $visit_date2_flag === 1) {
            $move = mktime(0, 0, 0, $inForm->move_date_month_cd_sel, $inForm->move_date_day_cd_sel, $inForm->move_date_year_cd_sel);
            $visit2 = mktime(0, 0, 0, $inForm->visit_date2_month_cd_sel, $inForm->visit_date2_day_cd_sel, $inForm->visit_date2_year_cd_sel);
            if ($move <= $visit2) {
                $errorForm->addError('top_visit_date2', 'はお引越し予定日以前にしてください。');
            } else {
                ////////////////////////////////////////////////////////////////////
                // 訪問見積もり希望日時第二希望日 繁忙期:日付範囲チェック
                ////////////////////////////////////////////////////////////////////
                if ($inForm->plan_cd_sel == '1' || $inForm->plan_cd_sel == '2') { // 1:単身カーゴプランの場合 || 2:単身AIR CARGO プラン
                    // 入力チェック
                    $min2Date = date('Y/n/j', strtotime('2019-03-21 00:00:00'));
                    $max2Date = date('Y/n/j', strtotime('2019-03-31 23:59:59'));
                    $min2 = date('Y-m-d H:i:s', strtotime('2019-03-21 00:00:00'));
                    $max2 = date('Y-m-d H:i:s', strtotime('2019-03-31 23:59:59'));
                    $selectDate = date('Y-m-d H:i:s', 
                            strtotime("{$inForm->visit_date2_year_cd_sel}-{$inForm->visit_date2_month_cd_sel}-{$inForm->visit_date2_day_cd_sel} 00:00:00"));

    //                $v = Sgmov_Component_Validator::createDateValidator($inForm->visit_date2_year_cd_sel, $inForm->visit_date2_month_cd_sel, $inForm->visit_date2_day_cd_sel)->isDate($min, $max);
                    if ($min2 <= $selectDate && $selectDate <= $max2) {
                        $errorForm->addError('top_visit_date2', "には{$min2Date}から{$max2Date}までの期間は選択できません。");
                    }
                } else if ($inForm->plan_cd_sel == '4' 
                        || $inForm->plan_cd_sel == '3' 
                        || $inForm->plan_cd_sel == '5') { // 4:まるごとおまかせプラン || 3:スタンダードプラン || 5:チャータープラン
                    // 入力チェック
                    $min2Date = date('Y/n/j', strtotime('2019-03-15 00:00:00'));
                    $max2Date = date('Y/n/j', strtotime('2019-04-08 23:59:59'));
                    $min2 = date('Y-m-d H:i:s', strtotime('2019-03-15 00:00:00'));
                    $max2 = date('Y-m-d H:i:s', strtotime('2019-04-08 23:59:59'));
                    $selectDate = date('Y-m-d H:i:s', 
                            strtotime("{$inForm->visit_date2_year_cd_sel}-{$inForm->visit_date2_month_cd_sel}-{$inForm->visit_date2_day_cd_sel} 00:00:00"));

    //                $v = Sgmov_Component_Validator::createDateValidator($inForm->visit_date2_year_cd_sel, $inForm->visit_date2_month_cd_sel, $inForm->visit_date2_day_cd_sel)->isDate($min, $max);
                    if ($min2 <= $selectDate && $selectDate <= $max2) {
                        $errorForm->addError('top_visit_date2', "には{$min2Date}から{$max2Date}までの期間は選択できません。");
                    }
                }
            }
        }
        // 現住所郵便番号(最後に存在確認をするので別名でバリデータを作成) 必須
        $cur_zipV = Sgmov_Component_Validator::createZipValidator($inForm->cur_zip1, $inForm->cur_zip2)->isNotEmpty()->isZipCode();
        if (!$cur_zipV->isValid()) {
            $errorForm->addError('top_cur_zip', $cur_zipV->getResultMessageTop());
        }
        // 現住所「都道府県」値範囲チェック 必須チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->cur_pref_cd_sel);
        $v->isIn($pref['ids']);
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        $v->isSelected();
        if (!$v->isValid()) {
            $errorForm->addError('top_cur_pref_cd_sel', $v->getResultMessageTop());
        }
        // 現住所「住所」必須チェック 40文字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->cur_address)->isNotEmpty()->isLengthLessThanOrEqualTo(40)->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_cur_address', $v->getResultMessageTop());
        }
        // 現住所補足情報「エレベーター」値範囲チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->cur_elevator_cd_sel)->isIn(array_keys($this->elevator_lbls));
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        // 現住所補足情報「階数」3桁 半角数値チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->cur_floor)->isInteger()->isLengthLessThanOrEqualTo(2)->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_cur_floor', $v->getResultMessageTop());
        }
        // 現住所補足情報「住居前道幅」値範囲チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->cur_road_cd_sel)->isIn(array_keys($this->road_lbls));
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        // 新住所郵便番号(最後に存在確認をするので別名でバリデータを作成)
        $new_zipV = Sgmov_Component_Validator::createZipValidator($inForm->new_zip1, $inForm->new_zip2)->isZipCode()->isWebSystemNg()->isWebSystemNg();
        if (!$new_zipV->isValid()) {
            $errorForm->addError('top_new_zip', $new_zipV->getResultMessageTop());
        }
        // 新住所「都道府県」値範囲チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->new_pref_cd_sel)->isIn($pref['ids']);
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        // 新住所「住所」40文字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->new_address)->isLengthLessThanOrEqualTo(40)->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_new_address', $v->getResultMessageTop());
        }
        // 新住所補足情報「エレベーター」値範囲チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->new_elevator_cd_sel)->isIn(array_keys($this->elevator_lbls));
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        // 新住所補足情報「階数」3桁 半角数値チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->new_floor)->isInteger()->isLengthLessThanOrEqualTo(2)->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_new_floor', $v->getResultMessageTop());
        }
        // 新住所補足情報「住居前道幅」値範囲チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->new_road_cd_sel)->isIn(array_keys($this->road_lbls));
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        //お名前 必須チェック 30文字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->name)->isNotEmpty()->isLengthLessThanOrEqualTo(30)->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_name', $v->getResultMessageTop());
        }
        //フリガナ 必須チェック 30文字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->furigana)->isNotEmpty()->isLengthLessThanOrEqualTo(30)->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_furigana', $v->getResultMessageTop());
        }
        // 電話番号 必須チェック 型チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createPhoneValidator($inForm->tel1, $inForm->tel2, $inForm->tel3)->isNotEmpty()->isPhone()->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_tel', $v->getResultMessageTop());
        }
        // 電話番号種類 値範囲チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->tel_type_cd_sel)->isIn(array_keys($this->tel_type_lbls));
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        // 電話番号種別 必須チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->tel_type_cd_sel)->isNotEmpty();
        if (!$v->isValid()) {
            $errorForm->addError('top_tel_type', $v->getResultMessageTop());
        }
        // 電話番号種類その他 20文字チェック 数字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->tel_other)->isInteger()->isLengthLessThanOrEqualTo(20)->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_tel_other', $v->getResultMessageTop());
        }
        // 電話連絡可能時間（終日||時間指定） 値範囲チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->contact_available_cd_sel)->isIn(array_keys($this->contact_available_lbls));
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        // 電話連絡可能開始時間 値範囲チェック
        $isContactDate = true;
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->contact_start_cd_sel)->isIn(array_keys($this->contact_start_lbls));
        if (!$v->isValid()) {
            $isContactDate = false;
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        // 電話連絡可能開始時間 値範囲チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->contact_end_cd_sel)->isIn(array_keys($this->contact_end_lbls));
        if (!$v->isValid()) {
            $isContactDate = false;
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        
        if ($isContactDate && $inForm->contact_available_cd_sel == '1') {
            if ($inForm->contact_end_cd_sel < $inForm->contact_start_cd_sel) {
//                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
                $errorForm->addError('top_tel', 'の電話連絡可能時間帯の開始時間は終了時間以前にしてください。');
            }
        }
        
        // メールアドレス 必須チェック 80文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->mail)->isNotEmpty()->isMail()->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_mail', $v->getResultMessageTop());
        }
        $v->isLengthLessThanOrEqualTo(80);
        if (!$v->isValid()) {
            $errorForm->addError('top_mail', $v->getResultMessageTop());
        }
        // 備考 300文字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comment)->isLengthLessThanOrEqualTo(300)->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_comment', $v->getResultMessageTop());
        }

        // エラーがない場合は現住所郵便番号存在チェック
        if (!$errorForm->hasError()) {
            $cur_zipV->zipCodeExist();
            if (!$cur_zipV->isValid()) {
                $errorForm->addError('top_cur_zip', $cur_zipV->getResultMessageTop());
            }
        }

        // エラーがない場合は新住所郵便番号存在チェック
        if (!$errorForm->hasError()) {
            $new_zipV->zipCodeExist();
            if (!$new_zipV->isValid()) {
                $errorForm->addError('top_new_zip', $new_zipV->getResultMessageTop());
            }
        }

        if (!$errorForm->hasError()) {
        // エラーがない場合はコースプラン・出発地域・到着地域の存在チェック
Sgmov_Component_Log::debug('コースプラン・出発地域・到着地域の存在チェック開始');
        $coursePlans  = $inForm->course_cd_sel;
        $coursePlans .= "_";
        $coursePlans .= $inForm->plan_cd_sel;
Sgmov_Component_Log::debug('コースプラン・出発地域・到着地域の存在チェック');
            if (!$this->_coursePlanService->checkCourcePlanFromTo2($db, $coursePlans, $inForm->from_area_cd_sel, $inForm->to_area_cd_sel)) {
                $errorForm->addError('top_cource_plan_from_to', '入力されたコース・プラン・出発地域・到着地域が不正です。');
            }
Sgmov_Component_Log::debug('コースプラン・出発地域・到着地域の存在チェック終了');
        }

        return $errorForm;
    }
}
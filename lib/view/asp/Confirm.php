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
Sgmov_Lib::useView('asp/Common');
Sgmov_Lib::useServices(array('Login', 'Calendar', 'CenterArea', 'CoursePlan', 'BasePrice'));
Sgmov_Lib::useForms(array('Error', 'AspSession', 'Asp010Out'));
/**#@-*/

 /**
 * 特価編集確認画面を表示します。
 * @package    View
 * @subpackage ASP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Asp_Confirm extends Sgmov_View_Asp_Common
{
    /**
     * ログインサービス
     * @var Sgmov_Service_Login
     */
    public $_loginService;

    /**
     * 拠点・エリアサービス
     * @var Sgmov_Service_CenterArea
     */
    public $_centerAreaService;

    /**
     * コースプランサービス
     * @var Sgmov_Service_CoursePlan
     */
    public $_coursePlanService;

    /**
     * 基本料金サービス
     * @var Sgmov_Service_BasePrice
     */
    public $_basePriceService;

    /**
     * カレンダーサービス
     * @var Sgmov_Service_Calendar
     */
    public $_calendarService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        $this->_loginService = new Sgmov_Service_Login();
        $this->_centerAreaService = new Sgmov_Service_CenterArea();
        $this->_coursePlanService = new Sgmov_Service_CoursePlan();
        $this->_basePriceService = new Sgmov_Service_BasePrice();
        $this->_calendarService = new Sgmov_Service_Calendar();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * セッションに入力チェック済みのASP004～ASP006と料金(ASP007またはASP008)情報があることを確認
     * <ol><li>
     * 表示ボタン押下の場合
     *   <ol><li>
     *   セッションにASP009情報が保存されていない場合システムエラー
     *   </li><li>
     *   入力チェック
     *   </li><li>
     *   エラーがない場合は現在の検索条件に入力値を適用する
     *   </li></ol>
     * </li><li>
     * 表示ボタン押下ではない場合
     *   <ol><li>
     *   セッションにASP010情報が存在しない場合は新規作成
     *   </li></ol>
     * </li><li>
     * セッションにASP010情報が存在する場合、
     * カレント情報がASP005情報と一致していなければクリア
     * </li><li>
     * セッションの情報を元に出力情報を生成
     * </li><li>
     * 検索条件入力エラーの場合は検索条件が適用されていないので入力値を設定
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
     * </li><li>
     * ['searchErrorForm']:検索部エラーフォーム
     * </li></ul>
     */
    public function executeInner()
    {
        Sgmov_Component_Log::debug('セッションに入力チェック済みのASP004～ASP006と料金(ASP008またはASP009)情報があることを確認');
        $session = Sgmov_Component_Session::get();

        /**
         * コード補完のためだけにドキュメントコメント使います。
         * @var Sgmov_Form_AspSession
         */
        $sessionForm = $session->loadForm($this->getFeatureId());
        // 不正遷移チェック
        if(is_null($sessionForm)){
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, 'sessionFormがnullです。');
        }
        if ($sessionForm->asp004_status !== self::VALIDATION_SUCCEEDED) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, 'ASP004が未チェックです。');
        }
        if ($sessionForm->asp005_status !== self::VALIDATION_SUCCEEDED) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, 'ASP005が未チェックです。');
        }
        if ($sessionForm->asp006_status !== self::VALIDATION_SUCCEEDED) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, 'ASP006が未チェックです。');
        }
        if ($sessionForm->priceset_kbn === self::PRICESET_KBN_ALL) {
            if ($sessionForm->asp008_status !== self::VALIDATION_SUCCEEDED) {
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, 'ASP008が未チェックです。');
            }
        } else if ($sessionForm->priceset_kbn === self::PRICESET_KBN_EACH) {
            if ($sessionForm->asp009_status !== self::VALIDATION_SUCCEEDED) {
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, 'ASP009が未チェックです。');
            }
        }

        $searchErrorForm = new Sgmov_Form_Error();
        if (isset($_POST['reading_btn_x'])) {
            Sgmov_Component_Log::debug('表示ボタン押下');

            // 不正遷移チェック
            if (!isset($sessionForm->asp010_in) || $sessionForm->priceset_kbn === self::PRICESET_KBN_NONE) {
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '不正遷移：料金設定無しで表示ボタン押下フラグが指定されました。');
            }

            // 入力チェック
            $inForm = $this->_createInFormFromPost($_POST);
            $searchErrorForm = $this->_validate($inForm, $sessionForm->asp005_in->course_plan_sel_cds, $sessionForm->asp005_in->from_area_sel_cds);

            if (!$searchErrorForm->hasError()) {
                Sgmov_Component_Log::debug('エラーがない場合は現在の検索条件に入力値を適用する');
                $sessionForm->asp010_in->cur_course_plan_cd = $inForm->course_plan_cd_sel;
                $sessionForm->asp010_in->cur_from_area_cd = $inForm->from_area_cd_sel;
            }
        } else {
            Sgmov_Component_Log::debug('表示ボタン押下ではない');
            if (!isset($sessionForm->asp010_in)) {
                $sessionForm->asp010_in = new Sgmov_Form_Asp010In();
            }
        }

        Sgmov_Component_Log::debug('カレント情報がASP005情報と一致していなければクリア');
        if (!in_array($sessionForm->asp010_in->cur_course_plan_cd, $sessionForm->asp005_in->course_plan_sel_cds)) {
            $sessionForm->asp010_in->cur_course_plan_cd = NULL;
        }
        if (!in_array($sessionForm->asp010_in->cur_from_area_cd, $sessionForm->asp005_in->from_area_sel_cds)) {
            $sessionForm->asp010_in->cur_from_area_cd = NULL;
        }

        // セッション情報の保存
        $session->saveForm($this->getFeatureId(), $sessionForm);
        if (isset($sessionForm->asp010_error)) {
            $errorForm = $sessionForm->asp010_error;
        } else {
            $errorForm = new Sgmov_Form_Error();
        }

        // セッションから出力情報を生成
        $outForm = $this->_createOutFormBySessionForm($sessionForm);

        if ($searchErrorForm->hasError()) {
            // セッションから出力情報を生成する際に「コースプラン」「出発地」の
            // 画面上の選択値にはカレントコースプラン、カレント出発地が適用される。
            //
            // 検索条件入力エラーの場合は表示用の値がカレントのものとは異なるため、
            // 全ての情報を設定した後に入力値(POST値)から適用する。
            $outForm->raw_course_plan_cd_sel = $inForm->course_plan_cd_sel;
            $outForm->raw_from_area_cd_sel = $inForm->from_area_cd_sel;
        }

        Sgmov_Component_Log::debug('チケット発行');
        $ticket = $session->publishTicket($this->getFeatureId(), self::GAMEN_ID_ASP010);

        return array('ticket'=>$ticket,
                         'outForm'=>$outForm,
                         'errorForm'=>$errorForm,
                         'searchErrorForm'=>$searchErrorForm);
    }

    /**
     * POST情報から入力フォームを生成します。
     *
     * @param array $post ポスト情報
     * @return Sgmov_Form_Asp010In 入力フォーム
     */
    public function _createInFormFromPost($post)
    {
        $inForm = new Sgmov_Form_Asp010In();
        if (isset($post['course_plan_cd_sel'])) {
            $inForm->course_plan_cd_sel = $post['course_plan_cd_sel'];
        } else {
            $inForm->course_plan_cd_sel = '';
        }
        if (isset($post['from_area_cd_sel'])) {
            $inForm->from_area_cd_sel = $post['from_area_cd_sel'];
        } else {
            $inForm->from_area_cd_sel = '';
        }
        return $inForm;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_Asp010In $inForm 入力フォーム
     * @param array $coursePlanCds コースプランコードの配列
     * @param array $fromAreaCds 出発エリアコードの配列
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($inForm, $coursePlanCds, $fromAreaCds)
    {
        $errorForm = new Sgmov_Form_Error();

        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->course_plan_cd_sel);
        // 入力エラー
        $v->isSelected();
        if (!$v->isValid()) {
            $errorForm->addError('top_course_plan_cd_sel', $v->getResultMessageTop());
        } else {
            $v->isIn($coursePlanCds);
            // 通常の入力ではありえない値の場合はシステムエラー
            if (!$v->isValid()) {
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT, 'コースプランリストにないコードが指定されました。');
            }
        }

        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->from_area_cd_sel);
        // 入力エラー
        $v->isSelected();
        if (!$v->isValid()) {
            $errorForm->addError('top_from_area_cd_sel', $v->getResultMessageTop());
        } else {
            // 通常の入力ではありえない値の場合はシステムエラー
            $v->isIn($fromAreaCds);
            if (!$v->isValid()) {
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT, '出発エリアリストにないコードが指定されました。');
            }
        }

        return $errorForm;
    }

    /**
     * コースプランコードと出発エリアコードからコースプラン出発エリアコードを作成します。
     *
     * コースプラン出発エリアコードは差額情報(sp_setting_charges)のキーとして使用されます。
     *
     * @param string $coursePlanCd
     * @param string $fromAreaCd
     * @return コースプラン出発エリアコード
     */
    public function _createCoursePlanAreaCd($coursePlanCd, $fromAreaCd)
    {
        return $coursePlanCd . Sgmov_Service_CoursePlan::ID_DELIMITER . $fromAreaCd;
    }

    /**
     * セッション情報を元に出力情報を生成します。
     * @param Sgmov_Form_AspSession $sessionForm セッションフォーム
     * @return Sgmov_Form_Asp010Out 出力情報
     */
    public function _createOutFormBySessionForm($sessionForm)
    {
        $db = Sgmov_Component_DB::getAdmin();
        $outForm = new Sgmov_Form_Asp010Out();

        // 本社ユーザーかどうか
        $outForm->raw_honsha_user_flag = $this->getHonshaUserFlag();
        // 一覧に戻るリンクのURL
        $outForm->raw_sp_list_url = $this->createSpListUrl($sessionForm->sp_list_kind, $sessionForm->sp_list_view_mode);
        // 閑散繁忙設定かキャンペーン設定か
        $outForm->raw_sp_kind = $this->getSpKind();
        // 戻り先URL
        if ($sessionForm->priceset_kbn === self::PRICESET_KBN_ALL) {
            $outForm->raw_back_url = '/asp/input5/' . $this->getFeatureGetParam();
        } else if ($sessionForm->priceset_kbn === self::PRICESET_KBN_EACH) {
            $outForm->raw_back_url = '/asp/input6/' . $this->getFeatureGetParam();
        } else if ($sessionForm->priceset_kbn === self::PRICESET_KBN_NONE) {
            $outForm->raw_back_url = '/asp/input4/' . $this->getFeatureGetParam();
        }

        // マスタ情報の取得
        // コース(先頭の空白は除去)
        $courseList = $this->_coursePlanService->
                            fetchCourseList($db);
        $courseIds = $courseList['ids'];
        array_shift($courseIds);
        $courseNames = $courseList['names'];
        array_shift($courseNames);

        // プラン(先頭の空白は除去)
        $planList = $this->_coursePlanService->
                            fetchPlanList($db);
        $planIds = $planList['ids'];
        array_shift($planIds);
        $planNames = $planList['names'];
        array_shift($planNames);

        // コースプラン(先頭の空白は除去)
        $coursePlanList = $this->_coursePlanService->
                                fetchCoursePlanList($db);
        $coursePlanIds = $coursePlanList['ids'];
        array_shift($coursePlanIds);
        $coursePlanNames = $coursePlanList['names'];
        array_shift($coursePlanNames);

        // 出発エリア(先頭の空白は除去)
        $fromAreaList = $this->_centerAreaService->
                                fetchFromAreaList($db);
        $fromAreaIds = $fromAreaList['ids'];
        array_shift($fromAreaIds);
        $fromAreaNames = $fromAreaList['names'];
        array_shift($fromAreaNames);

        // 到着エリア(先頭の空白は除去)
        $toAreaList = $this->_centerAreaService->
                            fetchToAreaList($db);
        $toAreaIds = $toAreaList['ids'];
        array_shift($toAreaIds);
        $toAreaNames = $toAreaList['names'];
        array_shift($toAreaNames);

        // ログインユーザー
        $user = $this->_loginService->
                        getLoginUser();

        // 特価内容
        $outForm->raw_sp_regist_date = date('Y/m/d');
        $outForm->raw_sp_charge_center = $user->centerName;
        $outForm->raw_sp_regist_user = $sessionForm->asp004_in->sp_regist_user;
        $outForm->raw_sp_name = $sessionForm->asp004_in->sp_name;
        if ($this->getFeatureId() === self::FEATURE_ID_CAMPAIGN) {
            $outForm->raw_sp_content = $sessionForm->asp004_in->sp_content;
        }
        // コース名・プラン名
        $temp = $this->_getCoursePlanStrings($sessionForm->asp005_in->course_plan_sel_cds, $courseIds, $courseNames, $planIds,
             $planNames);
        $outForm->raw_sp_course_lbls = $temp['courses'];
        $outForm->raw_sp_plan_lbls = $temp['plans'];
        // 出発エリア
        $outForm->raw_sp_from_area = $this->_getAreaString($sessionForm->asp005_in->from_area_sel_cds, $fromAreaIds, $fromAreaNames);
        // 到着エリア
        $outForm->raw_sp_to_area = $this->_getAreaString($sessionForm->asp005_in->to_area_sel_cds, $toAreaIds, $toAreaNames);
        // 期間
        $from = $sessionForm->asp006_in->sel_days[0];
        $to = $sessionForm->asp006_in->sel_days[count($sessionForm->asp006_in->sel_days) - 1];
        $outForm->raw_sp_period = $this->_getPeriodString($from, $to);

        // 金額設定有りの場合
        if ($sessionForm->priceset_kbn === self::PRICESET_KBN_ALL || $sessionForm->priceset_kbn === self::PRICESET_KBN_EACH) {
            // 有り
            $outForm->raw_sp_charge_set_flag = '1';

            // プルダウン用
            $outForm->raw_course_plan_cds = $sessionForm->asp005_in->course_plan_sel_cds;
            $outForm->raw_from_area_cds = $sessionForm->asp005_in->from_area_sel_cds;

            // 現在の差額一覧表示に使用されているコードを選択コードとして設定する。
            // 入力エラーがあった場合は選択コードはカレントコードに一致しないため後で設定しなおす。
            // 入力エラー以外の遷移ではカレントコードに一致するものが選択されている状態にするために
            // この操作を行っている。
            if (isset($sessionForm->asp010_in->cur_course_plan_cd)) {
                $outForm->raw_cur_course_plan_cd = $sessionForm->asp010_in->cur_course_plan_cd;
                $outForm->raw_course_plan_cd_sel = $sessionForm->asp010_in->cur_course_plan_cd;
            }
            if (isset($sessionForm->asp010_in->cur_from_area_cd)) {
                $outForm->raw_cur_from_area_cd = $sessionForm->asp010_in->cur_from_area_cd;
                $outForm->raw_from_area_cd_sel = $sessionForm->asp010_in->cur_from_area_cd;
            }
            // フラグ
            if (isset($sessionForm->asp010_in->cur_course_plan_cd) && isset($sessionForm->asp010_in->cur_from_area_cd)) {
                $outForm->raw_cond_selected_flag = '1';
            } else {
                $outForm->raw_cond_selected_flag = '0';
            }

            // コースプランの名称
            $outForm->raw_course_plan_lbls = array();
            foreach ($sessionForm->asp005_in->course_plan_sel_cds as $cd) {
                $key = array_search($cd, $coursePlanIds, TRUE);
                if ($key === FALSE) {
                    Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, '選択コースプランデータ不整合');
                }
                $outForm->raw_course_plan_lbls[] = $coursePlanNames[$key];
            }

            // 出発エリアの名称
            $outForm->raw_from_area_lbls = array();
            foreach ($sessionForm->asp005_in->from_area_sel_cds as $cd) {
                $key = array_search($cd, $fromAreaIds, TRUE);
                if ($key === FALSE) {
                    Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, '選択出発エリアデータ不整合');
                }
                $outForm->raw_from_area_lbls[] = $fromAreaNames[$key];
            }

            // 選択されているコースプラン・出発エリアと到着エリアリストの名称・カレンダーURL
            if ($outForm->raw_cond_selected_flag === '1') {
                // コースプランの名称
                $key = array_search($outForm->raw_cur_course_plan_cd, $coursePlanIds, TRUE);
                if ($key === FALSE) {
                    Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, 'コースプランデータ不整合');
                }
                $outForm->raw_cur_course_plan = $coursePlanNames[$key];

                // 出発エリアリストの名称
                $key = array_search($outForm->raw_cur_from_area_cd, $fromAreaIds, TRUE);
                if ($key === FALSE) {
                    Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, '出発エリアリストデータ不整合');
                }
                $outForm->raw_cur_from_area = $fromAreaNames[$key];

                // カレンダーURL用開始年月を取得
                $splits = explode(Sgmov_Service_Calendar::DATE_SEPARATOR, $sessionForm->asp006_in->sel_days[0], 3);
                $fromYYYYMM = $splits[0] . $splits[1];

                // 到着エリアリストの名称・カレンダーURL
                $outForm->raw_to_area_lbls = array();
                $outForm->raw_sp_calendar_urls = array();
                $calendarPrefix = '/asp/calendar/' . $this->getFeatureGetParam();
                $delim = Sgmov_Service_CoursePlan::ID_DELIMITER;
                foreach ($sessionForm->asp005_in->to_area_sel_cds as $cd) {
                    $key = array_search($cd, $toAreaIds, TRUE);
                    if ($key === FALSE) {
                        Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, '到着エリアリストデータ不整合');
                    }
                    $outForm->raw_to_area_lbls[] = $toAreaNames[$key];

                    $coursePlanFromToCd = $outForm->raw_cur_course_plan_cd . $delim . $outForm->raw_cur_from_area_cd . $delim . $cd;
                    $outForm->raw_sp_calendar_urls[] = "{$calendarPrefix}/{$coursePlanFromToCd}/{$fromYYYYMM}/edit";
                }

                // 基本料金
                $outForm->raw_sp_base_charges = array();
                $splits = explode(Sgmov_Service_CoursePlan::ID_DELIMITER, $outForm->raw_cur_course_plan_cd);
                $basePriceList = $this->_basePriceService->
                                        fetchBasePrices($db, $splits[0], $splits[1], $outForm->raw_cur_from_area_cd);
                $basePriceToAreaIds = $basePriceList['to_area_ids'];
                $basePrices = $basePriceList['base_prices'];
                foreach ($sessionForm->asp005_in->to_area_sel_cds as $cd) {
                    $key = array_search($cd, $basePriceToAreaIds, TRUE);
                    if ($key === FALSE) {
                        Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, '基本料金データ不整合');
                    }
                    $outForm->raw_sp_base_charges[] = $basePrices[$key];
                }

                // 金額
                if ($sessionForm->priceset_kbn === self::PRICESET_KBN_ALL) {
                    $count = count($sessionForm->asp005_in->to_area_sel_cds);
                    $outForm->raw_sp_setting_charges = array_fill(0, $count, $sessionForm->asp008_in->sp_whole_charge);
                } else if ($sessionForm->priceset_kbn === self::PRICESET_KBN_EACH) {
                    $coursePlanAreaCd = $this->_createCoursePlanAreaCd($sessionForm->asp010_in->cur_course_plan_cd, $sessionForm->asp010_in->cur_from_area_cd);
                    $outForm->raw_sp_setting_charges = $sessionForm->asp009_in->all_charges[$coursePlanAreaCd];
                }
            }
        } else {
            // 金額設定無し
            $outForm->raw_sp_charge_set_flag = '0';
        }

        return $outForm;
    }

    /**
     * 特価内容部分に表示するコースプラン文字列配列を生成します。
     *
     * @param $coursePlanSelCds 対象コースプランコードリスト
     * @param array $allCourseCds コースコードリスト
     * @param array $allCourseLbls コース名称リスト
     * @param array $allPlanCds プランコードリスト
     * @param array $allPlanLbls プラン名称リスト
     * @return array ['courses']:コース名配列 ['plans']プラン名配列(1次元目はコース名配列のkey)
     */
    public function _getCoursePlanStrings($coursePlanSelCds, $allCourseCds, $allCourseLbls, $allPlanCds, $allPlanLbls)
    {
        // $coursePlanSelCdsを変換
        $courses = array();
        $plans = array();
        foreach ($coursePlanSelCds as $cd) {
            $split = explode(Sgmov_Service_CoursePlan::ID_DELIMITER, $cd);
            $courseCd = $split[0];
            $planCd = $split[1];

            $key = array_search($courseCd, $courses, TRUE);
            if ($key === FALSE) {
                $key = count($courses);
                $courses[$key] = $courseCd;
                $plans[$key] = array();
            }

            $plans[$key][] = $planCd;
        }

        // コードを名称に置換
        $courseCount = count($courses);
        for ($i = 0; $i < $courseCount; $i++) {
            $key = array_search($courses[$i], $allCourseCds, TRUE);
            if ($key === FALSE) {
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, 'データ不整合');
            }
            $courses[$i] = $allCourseLbls[$key];

            $planCount = count($plans[$i]);
            for ($j = 0; $j < $planCount; $j++) {
                $key = array_search($plans[$i][$j], $allPlanCds, TRUE);
                if ($key === FALSE) {
                    Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, 'データ不整合');
                }
                $plans[$i][$j] = $allPlanLbls[$key];
            }
        }

        return array('courses'=>$courses,
                         'plans'=>$plans);
    }

    /**
     * エリア文字列を生成します。
     *
     * 全てのエリアと一致する場合は"全国"を返します。
     *
     * @param array $areaSelCds 対象エリアコードリスト
     * @param array $allAreaCds 到着エリアコードリスト
     * @param array $allAreaLbls 到着エリア名称リスト
     * @return string 到着エリア文字列
     */
    public function _getAreaString($areaSelCds, $allAreaCds, $allAreaLbls)
    {
        if (count(array_diff($allAreaCds, $areaSelCds)) == 0) {
            // == は順番に関係なく含まれている場合TRUEになる
            return '全国';
        }

        $delim = '、';
        $ret = '';
        foreach ($areaSelCds as $cd) {
            $key = array_search($cd, $allAreaCds, TRUE);
            if ($key === FALSE) {
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, 'データ不整合');
            }

            if (! empty($ret)) {
                $ret .= $delim;
            }
            $ret .= $allAreaLbls[$key];
        }

        return $ret;
    }

    /**
     * 期間文字列を生成します。
     *
     * 日付文字列は"YYYY-MM-DD"の形式であることを前提としています。
     *
     * @param string $fromStr 開始日
     * @param string $toStr 終了日
     * @return string 期間文字列
     */
    public function _getPeriodString($fromStr, $toStr)
    {
        return $this->_getDateStringToViewString($fromStr) . '～' . $this->_getDateStringToViewString($toStr);
    }

    /**
     * "YYYY-MM-DD"から"YYYY/MM/DD"に変換します。
     *
     * @param string $dateStr 日付文字列
     * @return string 表示用文字列
     */
    public function _getDateStringToViewString($dateStr)
    {
        $splits = explode(Sgmov_Service_Calendar::DATE_SEPARATOR, $dateStr, 3);
        return "{$splits[0]}/{$splits[1]}/{$splits[2]}";
    }

}
?>

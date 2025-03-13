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
Sgmov_Lib::useView('pre/Common');
Sgmov_Lib::useForms(array('Error', 'Pre002In'));
/**#@-*/
/**
 * 概算見積もり申し込み入力情報をチェックします。
 * @package    View
 * @subpackage PRE
 * @author     H.Tsuji(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pre_CheckInput extends Sgmov_View_Pre_Common {
    /**
     * コースコードサービス
     * @var Sgmov_Service_Prefecture
     */
    public $_CourseService;

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
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_centerAreaService = new Sgmov_Service_CenterArea();
        $this->_CourseService = new Sgmov_Service_CoursePlan();
        $this->_coursePlanService = new Sgmov_Service_CoursePlan();
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
     *   pre/confirm へリダイレクト
     *   </li></ol>
     * </li><li>
     * 入力エラー有り
     *   <ol><li>
     *   pre/input へリダイレクト
     *   </li></ol>
     * </li></ol>
     */
    public function executeInner() {

        // POST情報の取得
        $inForm = $this->_createInFormFromPost($_POST);

	    // DB接続
        $db = Sgmov_Component_DB::getPublic();

        // セッション接続
        $session = Sgmov_Component_Session::get();

        // コース・プラン・出発エリア・到着エリアのリストを取得
        $Courses = $this->_CourseService->fetchCourseList($db);
        $Plans = $this->_CourseService->fetchPlanList($db);
        $toAreas = $this->_centerAreaService->fetchToAreaList($db);
        $fromAreas = $this->_centerAreaService->fetchFromAreaList($db);

        // 入力チェックを行う
        $errorForm = $this->_validate($inForm, $db, $Courses['ids'], $Plans['ids'], $toAreas['ids'], $fromAreas['ids']);

        // 情報をセッションに保存
        // エラー情報をフォームにセット
        $inForm->error = $errorForm;
        // フォームをセッションにセット
        $session->saveForm(self::SCRID_PRE, $inForm);

        // リダイレクト
        if ($errorForm->hasError()) {
            Sgmov_Component_Redirect::redirectPublicSsl('/pre/input');
        } else {
            Sgmov_Component_Redirect::redirectPublicSsl('/pre/result');
        }

    }

    /**
     * POST情報から入力フォームを生成します。
     *
     * @param array $post ポスト情報
     * @return Sgmov_Form_Pre002In 入力フォーム
     */
    public function _createInFormFromPost($post) {
        Sgmov_Component_Log::debug("_createInFormFromPost Start");

        $inForm = new Sgmov_Form_Pre002In();

        // タイプコード
        if (isset($post['type_cd'])) {
            $inForm->type_cd = $post['type_cd'];
        } else {
            $inForm->type_cd = 0;
        }
        // 全選択ボタン押下フラグ
        if (isset($post['all_sentakbtn_click_flag'])) {
            $inForm->all_sentakbtn_click_flag = $post['all_sentakbtn_click_flag'];
        } else {
            $inForm->all_sentakbtn_click_flag = 0;
        }
        if (isset($post['init_cource_cd'])) {
            // 入力画面初期表示時コースコード
            $inForm->init_course_cd_sel = $post['init_cource_cd'];
        }
        if (isset($post['init_plan_cd'])) {
            // 入力画面初期表示時プランコード
            $inForm->init_plan_cd_sel = $post['init_plan_cd'];
        }
        // コースコード
        if (isset($post['course_cd_sel'])) {
            $inForm->course_cd_sel = $post['course_cd_sel'];
        } else {
            $inForm->course_cd_sel = "";
        }
        // プランコード
        if (isset($post['plan_cd_sel'])) {
            $inForm->plan_cd_sel = $post['plan_cd_sel'];
        } else {
            $inForm->plan_cd_sel = "";
        }
        // エアコン取り付け・取り外し
        if (isset($post['aircon_exist_flag_sel'])) {
            $inForm->aircon_exist_flag_sel = $post['aircon_exist_flag_sel'];
        } else {
            $inForm->aircon_exist_flag_sel = "";
        }

        // 個人向けサービス ページの選択されたメニュー
        $inForm->menu_personal = filter_input(INPUT_POST, 'personal');

        // 出発地域コード
        if (isset($post['formareacd'])) {
            $inForm->from_area_cd_sel = $post['formareacd'];
        } else {
            $inForm->from_area_cd_sel = "";
        }
        // 到着地域コード
        if (isset($post['toareacd'])) {
            $inForm->to_area_cd_sel = $post['toareacd'];
        } else {
            $inForm->to_area_cd_sel = "";
        }
        // 引越し予定日付（年）
        if (isset($post['move_date_year_cd_sel'])) {
            $inForm->move_date_year_cd_sel = $post['move_date_year_cd_sel'];
        } else {
            $inForm->move_date_year_cd_sel = "";
        }
        // 引越し予定日付（年）
        if (isset($post['move_date_month_cd_sel'])) {
            $inForm->move_date_month_cd_sel = $post['move_date_month_cd_sel'];
        } else {
            $inForm->move_date_month_cd_sel = "";
        }
        // 引越し予定日付（年）
        if (isset($post['move_date_day_cd_sel'])) {
            $inForm->move_date_day_cd_sel = $post['move_date_day_cd_sel'];
        } else {
            $inForm->move_date_day_cd_sel = "";
        }
        // 他社連携キャンペーンID
        if (isset($post['oc_id'])) {
            $inForm->raw_oc_id = $post['oc_id'];
        } else {
            $inForm->raw_oc_id = "";
        }

        // 他社連携キャンペーン名称
        if (isset($post['oc_name'])) {
            $inForm->raw_oc_name = $post['oc_name'];
        } else {
            $inForm->raw_oc_name = "";
        }

        // 他社連携キャンペーン内容
        if (isset($post['oc_content'])) {
            $inForm->raw_oc_content = $post['oc_content'];
        } else {
            $inForm->raw_oc_content = "";
        }

        Sgmov_Component_Log::debug("_createInFormFromPost End");
        return $inForm;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_Pre001In $inForm 入力フォーム
     * @param db データベース接続
     * @param CorseCds コースコード範囲リスト
     * @param PlanCds プランコード範囲リスト
     * @param toAreaCds お引越し先地域範囲リスト
     * @param fromAreaCds 現在お住まい地域範囲リスト
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($inForm, $db, $CorseCds, $PlanCds, $toAreaCds, $fromAreaCds) {
        $errorForm = new Sgmov_Form_Error();

        // 入力チェック
        $min = strtotime(date('Ymd', strtotime('+1 week')));
//        $max = strtotime(date('Ymd', strtotime('+6 month -1 days')));
        $max = strtotime(date('Ymd', strtotime('+2 month')));

        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->course_cd_sel)->isIn($CorseCds);
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }

        $v->isSelected();
        if (!$v->isValid()) {
            $errorForm->addError('top_course_cd_sel', $v->getResultMessageTop());
            $errorForm->addError('course_cd_sel', $v->getResultMessage());
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
            $errorForm->addError('plan_cd_sel', $v->getResultMessage());
        } else {
            $PlanCheck = true;
        }
        //コースプランの整合性チェック
        if (isset($CourseCheck) && isset($PlanCheck)) {
            $PlanListByCourse = $this->_CourseService->fetchPlanListByCourse($db, $inForm->course_cd_sel);
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->plan_cd_sel)->isIn($PlanListByCourse['ids']);
            if (!$v->isValid()) {
                $errorForm->addError('top_plan_cd_sel', $v->getResultMessageTop());
                $errorForm->addError('plan_cd_sel', $v->getResultMessage());
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
            $errorForm->addError('from_area_cd_sel', $v->getResultMessage());
        }

        // お引越し先の地域 値範囲チェック 必須チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->to_area_cd_sel)->isIn($toAreaCds);
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        $v->isSelected();
        if (!$v->isValid()) {
            $errorForm->addError('top_to_area_cd_sel', $v->getResultMessageTop());
            $errorForm->addError('to_area_cd_sel', $v->getResultMessage());
        }

        // お引越し予定日 年月日必須チェックチェック
        $len1 = Sgmov_Component_String::getCount($inForm->move_date_year_cd_sel);
        $len2 = Sgmov_Component_String::getCount($inForm->move_date_month_cd_sel);
        $len3 = Sgmov_Component_String::getCount($inForm->move_date_day_cd_sel);
        if ($len1 == 0 && $len2 == 0 && $len3 == 0) {
            $errorForm->addError('top_move_date_all', $v->getResultMessageTop());
            $errorForm->addError('move_date', $v->getResultMessageTop());
        } else {
            // お引越し予定日 有効日チェック
            $v = Sgmov_Component_Validator::createDateValidator($inForm->move_date_year_cd_sel, $inForm->move_date_month_cd_sel, $inForm->move_date_day_cd_sel)->isDate($min, $max);
            if (!$v->isValid()) {
                $errorForm->addError('top_move_date', $v->getResultMessageTop());
                $errorForm->addError('move_date', $v->getResultMessageTop());
            } else {
                ////////////////////////////////////////////////////////////////////
                // お引越し予定日 繁忙期:日付範囲チェック
                ///////////////////////////////////////////////////////////////////
                
                if ($inForm->plan_cd_sel == '1' || $inForm->plan_cd_sel == '2') { // 1:単身カーゴプランの場合 || 2:単身AIR CARGO プラン
                    // 入力チェック
                    $min2Date = date('Y/n/j', strtotime('2019-03-21 00:00:00'));
                    $max2Date = date('Y/n/j', strtotime('2019-03-31 23:59:59'));
                    $min2 = date('Y-m-d H:i:s', strtotime('2019-03-21 00:00:00'));
                    $max2 = date('Y-m-d H:i:s', strtotime('2019-03-31 23:59:59'));
                    $selectDate = date('Y-m-d H:i:s', 
                            strtotime("{$inForm->move_date_year_cd_sel}-{$inForm->move_date_month_cd_sel}-{$inForm->move_date_day_cd_sel} 00:00:00"));
                    
//                    $v = Sgmov_Component_Validator::createDateValidator(
//                            $inForm->move_date_year_cd_sel, $inForm->move_date_month_cd_sel, $inForm->move_date_day_cd_sel)->isDate($min2, $max2);
                    if ($min2 <= $selectDate && $selectDate <= $max2) {
                        $errorForm->addError('top_move_date', "には{$min2Date}から{$max2Date}までの期間は選択できません。");
                        $errorForm->addError('move_date', "には{$min2Date}から{$max2Date}までの期間は選択できません。");
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
                    
//                    $v = Sgmov_Component_Validator::createDateValidator(
//                            $inForm->move_date_year_cd_sel, $inForm->move_date_month_cd_sel, $inForm->move_date_day_cd_sel)->isDate($min2, $max2);
                    if ($min2 <= $selectDate && $selectDate <= $max2) {
                        $errorForm->addError('top_move_date', "には{$min2Date}から{$max2Date}までの期間は選択できません。");
                        $errorForm->addError('move_date', "には{$min2Date}から{$max2Date}までの期間は選択できません。");
                    }
                }
            }
        }

        if (!$errorForm->hasError()) {
        // エラーがない場合はコースプラン・出発地域・到着地域の存在チェック

        $coursePlans = $inForm->course_cd_sel;
        $coursePlans .= "_";
        $coursePlans .= $inForm->plan_cd_sel;

            if (!$this->_coursePlanService->checkCourcePlanFromTo2($db, $coursePlans, $inForm->from_area_cd_sel, $inForm->to_area_cd_sel)) {
                $errorForm->addError('top_cource_plan_from_to', '入力されたコース・プラン・出発地域・到着地域が不正です。');
            }
        }

        return $errorForm;
    }
}
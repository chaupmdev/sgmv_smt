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
Sgmov_Lib::useServices(array('Login', 'Calendar', 'CenterArea', 'CoursePlan', 'BasePrice', 'SpecialPrice'));
Sgmov_Lib::useForms(array('Error', 'AspSession', 'Asp002Out'));
/**#@-*/

 /**
 * 特価編集確認画面を表示します。
 * @package    View
 * @subpackage ASP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Asp_Detail extends Sgmov_View_Asp_Common
{
    /**
     * 情報が別のユーザーによって更新されていることなどが理由で
     * 見つからない場合に表示するメッセージ
     */
    const INVALID_DATA_ERROR_MESSAGE = '指定された情報が見つかりません';

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
     * 特価サービス
     * @var Sgmov_Service_SpecialPrice
     */
    public $_specialPriceService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        $this->_loginService = new Sgmov_Service_Login();
        $this->_centerAreaService = new Sgmov_Service_CenterArea();
        $this->_coursePlanService = new Sgmov_Service_CoursePlan();
        $this->_basePriceService = new Sgmov_Service_BasePrice();
        $this->_specialPriceService = new Sgmov_Service_SpecialPrice();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * GETパラメーターのチェック
     * </li><li>
     * GETパラメーターを元に出力情報を生成
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
        Sgmov_Component_Log::debug('GETパラメーターのチェック');
        $params = $this->_parseGetParameter();
        $listModeCd = $params['listModeCd'];
        $id = $params['id'];
        $selectedCoursePlanCd = $params['coursePlanId'];
        $selectedFromAreaCd = $params['fromId'];

        Sgmov_Component_Log::debug('GETパラメーターを元に出力情報を生成');
        $forms = $this->_createOutForm($listModeCd, $id, $selectedCoursePlanCd, $selectedFromAreaCd);

        $errorForm = $forms['errorForm'];
        $session = Sgmov_Component_Session::get();
        $sessionForm = $session->loadForm($this->getFeatureId());
        if (!is_null($sessionForm)) {
            if (isset($sessionForm->asp002_error)) {
                $errorForm->addError('top_delete', $sessionForm->asp002_error->getMessage('top_delete'));
            }
            $session->deleteForm($this->getFeatureId());
        }

        Sgmov_Component_Log::debug('チケット発行');
        $session = Sgmov_Component_Session::get();
        $ticket = $session->publishTicket($this->getFeatureId(), self::GAMEN_ID_ASP002);

        return array('ticket'=>$ticket,
                         'outForm'=>$forms['outForm'],
                         'errorForm'=>$errorForm);
    }

    /**
     * GETパラメータから特価IDを取得します。
     *
     * [パラメータ]
     * <ol><li>
     * 'open' or 'draft' or 'close'
     * </li><li>
     * 特価ID
     * </li><li>
     * [optional] コースID_プランID_出発エリアID
     * </li></ol>
     *
     * [例]
     * <ul><li>
     * /asp/detail/campaign/open/10
     * </li><li>
     * /asp/detail/campaign/close/10/1_1_1
     * </li></ul>
     * @return array
     * ['listModeCd']:一覧表示画面の表示モードコード
     * ['id']:特価ID
     * ['courseId']:コースID
     * ['planId']:プランID
     * ['fromId']:出発エリアID
     */
    public function _parseGetParameter()
    {
        if (!isset($_GET['param'])) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_GET[\'param\']が未設定です。');
        }

        $params = explode('/', $_GET['param'], 4);
        $paramCount = count($params);

        // 一覧表示画面の表示モードコード
        $listModeCd = $params[1];
        if ($params[1] === self::GET_PARAM_OPEN) {
            $listModeCd = self::SP_LIST_VIEW_OPEN;
        } else if ($params[1] === self::GET_PARAM_DRAFT) {
            $listModeCd = self::SP_LIST_VIEW_DRAFT;
        } else if ($params[1] === self::GET_PARAM_CLOSE) {
            $listModeCd = self::SP_LIST_VIEW_CLOSE;
        } else {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_GET[\'param\']が不正です。');
        }

        $id = $params[2];
        $v = Sgmov_Component_Validator::createSingleValueValidator($id);
        $v->isNotEmpty()->
            isInteger(0);
        if (!$v->isValid()) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_GET[\'param\']が不正です。');
        } else {
            // 数値文字列として取得しなおす(先頭の0を除去)
            $id = (string) intval($id);
        }

        if ($paramCount === 4) {
            // コースプラン・出発エリア指定がある場合
            $ids = explode(Sgmov_Service_CoursePlan::ID_DELIMITER, $params[3], 3);
            if (count($ids) != 3) {
                // 不正遷移
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_GET[\'param\']が不正です。');
            }

            // 各種ID
            $courseId = $ids[0];
            $v = Sgmov_Component_Validator::createSingleValueValidator($courseId);
            $v->isNotEmpty()->
                isInteger(0);
            if (!$v->isValid()) {
                // 不正遷移
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_GET[\'param\']が不正です。');
            } else {
                // 数値文字列として取得しなおす(先頭の0を除去)
                $courseId = (string) intval($courseId);
            }

            $planId = $ids[1];
            $v = Sgmov_Component_Validator::createSingleValueValidator($planId);
            $v->isNotEmpty()->
                isInteger(0);
            if (!$v->isValid()) {
                // 不正遷移
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_GET[\'param\']が不正です。');
            } else {
                // 数値文字列として取得しなおす(先頭の0を除去)
                $planId = (string) intval($planId);
            }
            $coursePlanId = $courseId . Sgmov_Service_CoursePlan::ID_DELIMITER . $planId;

            $fromAreaId = $ids[2];
            $v = Sgmov_Component_Validator::createSingleValueValidator($fromAreaId);
            $v->isNotEmpty()->
                isInteger(0);
            if (!$v->isValid()) {
                // 不正遷移
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_GET[\'param\']が不正です。');
            } else {
                // 数値文字列として取得しなおす(先頭の0を除去)
                $fromAreaId = (string) intval($fromAreaId);
            }
        } else {
            // コースプラン・出発エリア指定がない場合
            $coursePlanId = NULL;
            $fromAreaId = NULL;
        }

        return array('listModeCd'=>$listModeCd,
                         'id'=>$id,
                         'coursePlanId'=>$coursePlanId,
                         'fromId'=>$fromAreaId);
    }

    /**
     * GETパラメーターを元に出力情報を生成します。
     * @param string $listModeCd 一覧画面の表示モード
     * @param string $id 特価ID
     * @param string $coursePlanCd コースプランコード(未指定の場合NULL)
     * @param string $fromAreaCd 出発エリアコード(未指定の場合NULL)
     * @return array
     * ['outForm'] 出力フォーム
     * ['errorForm'] エラーフォーム
     */
    public function _createOutForm($listModeCd, $id, $selectedCoursePlanCd, $selectedFromAreaCd)
    {
        $outForm = new Sgmov_Form_Asp002Out();

        $db = Sgmov_Component_DB::getAdmin();

        // 基本情報の設定
        $this->_setBasicInfo($outForm, $listModeCd);

        // 特価の取得
        $spInfo = $this->_specialPriceService->
                        fetchSpecialPricesById($db, $id);
        if (is_null($spInfo)) {
            // 指定されたIDの特価が存在しない場合はエラー
            Sgmov_Component_Log::warning('指定されたIDの特価が存在しません');
            $errorForm = new Sgmov_Form_Error();
            $errorForm->addError('top', self::INVALID_DATA_ERROR_MESSAGE);
            return array('outForm'=>$outForm,
                             'errorForm'=>$errorForm);
        }
        $spInfo['cource_plan_ids'] = $this->_specialPriceService->
                                            fetchCoursesPlansSpecialPricesById($db, $id);
        $spInfo['from_area_ids'] = $this->_specialPriceService->
                                        fetchFromAreasSpecialPricesById($db, $id);
        $spInfo['to_area_ids'] = $this->_specialPriceService->
                                        fetchSpecialPricesToAreasById($db, $id);

        // GETパラメータが現在の特価情報に存在するかどうかをチェック
        $errorForm = $this->_checkInput($spInfo, $this->getSpKind(), $selectedCoursePlanCd, $selectedFromAreaCd);
        if ($errorForm->hasError()) {
            return array('outForm'=>$outForm,
                             'errorForm'=>$errorForm);
        }

        // 必要なマスターの取得
        $masters = $this->_getMasters($db);

        // 特価情報の設定
        $this->_setSpInfo($outForm, $spInfo, $masters);

        // 価格設定有りの場合
        if ($outForm->raw_sp_charge_set_flag === '1') {
            // プルダウンの設定
            $this->_setSpCoursePlanFromAreas($outForm, $spInfo, $masters);

            // 選択されている場合
            if (!is_null($selectedCoursePlanCd)) {
                $outForm->raw_course_plan_cd_sel = $selectedCoursePlanCd;
                $outForm->raw_from_area_cd_sel = $selectedFromAreaCd;
                $outForm->raw_cond_selected_flag = '1';

                // 金額情報の設定
                $this->_setSpDetailInfo($db, $outForm, $spInfo, $masters);
            } else {
                $outForm->raw_cond_selected_flag = '0';
            }
        }

        return array('outForm'=>$outForm,
                         'errorForm'=>$errorForm);
    }

    /**
     * 出力フォームに基本情報を設定します。
     * @param Sgmov_Form_Asp002Out $outForm 出力フォーム
     * @param string $listModeCd 一覧画面表示モード
     */
    public function _setBasicInfo($outForm, $listModeCd)
    {
        // 本社ユーザーかどうか
        $outForm->raw_honsha_user_flag = $this->getHonshaUserFlag();
        // 一覧に戻るリンクのURL
        $outForm->raw_sp_list_url = $this->createSpListUrl($this->getSpKind(), $listModeCd);
        // 閑散繁忙設定かキャンペーン設定か
        $outForm->raw_sp_kind = $this->getSpKind();

        // 一覧画面用
        $outForm->raw_sp_list_kind = $this->getSpKind();
        $outForm->raw_sp_list_view_mode = $listModeCd;
    }

    /**
     * 出力情報の生成に必要となるマスター情報を取得します。
     * @param Sgmov_Component_DB $db DB接続
     * @return array
     * ['courseIds'] コースIDの配列
     * ['courseNames'] コース名の配列
     * ['planIds'] プランIDの配列
     * ['planNames'] プラン名の配列
     * ['coursePlanIds'] コースプランIDの配列
     * ['coursePlanNames'] コースプラン名の配列
     * ['fromAreaIds'] 出発エリアIDの配列
     * ['fromAreaNames'] 出発エリア名の配列
     * ['toAreaIds'] 到着エリアIDの配列
     * ['toAreaNames'] 到着エリア名の配列
     */
    public function _getMasters($db)
    {
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
        return array('courseIds'=>$courseIds,
                         'courseNames'=>$courseNames,
                         'planIds'=>$planIds,
                         'planNames'=>$planNames,
                         'coursePlanIds'=>$coursePlanIds,
                         'coursePlanNames'=>$coursePlanNames,
                         'fromAreaIds'=>$fromAreaIds,
                         'fromAreaNames'=>$fromAreaNames,
                         'toAreaIds'=>$toAreaIds,
                         'toAreaNames'=>$toAreaNames);
    }

    /**
     * GETパラメーターを元に出力情報を生成します。
     * @param array $spInfo 特価情報
     * @param string $spKind 特価区分
     * @param string $selectedCoursePlanCd コースプランコード(未指定の場合NULL)
     * @param string $selectedFromAreaCd 出発エリアコード(未指定の場合NULL)
     * @return Sgmov_From_Error エラーフォーム
     */
    public function _checkInput($spInfo, $spKind, $selectedCoursePlanCd, $selectedFromAreaCd)
    {
        $errorForm = new Sgmov_Form_Error();
        if ($spInfo['special_price_division'] !== $spKind) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '指定された区分がDBの区分と一致しません');
        }
        if (!is_null($selectedCoursePlanCd) && !in_array($selectedCoursePlanCd, $spInfo['cource_plan_ids'], TRUE)) {
            Sgmov_Component_Log::warning('指定されたコースプランは存在しません');
            $errorForm->addError('top', self::INVALID_DATA_ERROR_MESSAG);
            return $errorForm;
        }
        if (!is_null($selectedFromAreaCd) && !in_array($selectedFromAreaCd, $spInfo['from_area_ids'], TRUE)) {
            Sgmov_Component_Log::warning('指定された出発エリアは存在しません');
            $errorForm->addError('top', self::INVALID_DATA_ERROR_MESSAG);
            return $errorForm;
        }
        return $errorForm;
    }

    /**
     * 出力フォームに特価の基本情報を設定します。
     * @param Sgmov_Form_Asp002Out $outForm 出力フォーム
     * @param array $spInfo 特価情報
     * @param array $masters マスター情報
     */
    public function _setSpInfo($outForm, $spInfo, $masters)
    {
        // 状況
        $splits = explode(Sgmov_Service_Calendar::DATE_SEPARATOR, $spInfo['max_date']);
        $maxTime = mktime(0, 0, 0, intval($splits[1]), intval($splits[2]) + 1, intval($splits[0]));
        if ($maxTime > time()) {
            $closed = FALSE;
            if ($spInfo['draft_flag'] === 't') {
                $outForm->raw_sp_status = self::SP_LIST_VIEW_DRAFT_LABEL;
            } else {
                $outForm->raw_sp_status = self::SP_LIST_VIEW_OPEN_LABEL;
            }
        } else {
            $closed = TRUE;
            $outForm->raw_sp_status = self::SP_LIST_VIEW_CLOSE_LABEL;
        }

        // 担当拠点かつ終了していない場合に編集可能
        if ($spInfo['center_id'] === $this->_loginService->getLoginUser()->centerId && !$closed) {
            $outForm->raw_sp_editable_flag = '1';
        } else {
            $outForm->raw_sp_editable_flag = '0';
        }

        // 特価内容
        $outForm->raw_sp_cd = $spInfo['id'];
        $outForm->raw_sp_timestamp = $spInfo['timestamp'];
        $outForm->raw_sp_regist_date = $this->_getDateStringToViewString($spInfo['created_day']);
        $outForm->raw_sp_charge_center = $spInfo['center_name'];
        $outForm->raw_sp_regist_user = $spInfo['create_user_name'];
        $outForm->raw_sp_name = $spInfo['title'];
        if ($this->getFeatureId() === self::FEATURE_ID_CAMPAIGN) {
            $outForm->raw_sp_content = $spInfo['description'];
        }

        // コース名・プラン名
        $temp = $this->_getCoursePlanStrings($spInfo['cource_plan_ids'], $masters['courseIds'], $masters['courseNames'],
             $masters['planIds'], $masters['planNames']);
        $outForm->raw_sp_course_lbls = $temp['courses'];
        $outForm->raw_sp_plan_lbls = $temp['plans'];
        // 出発エリア
        $outForm->raw_sp_from_area = $this->_getAreaString($spInfo['from_area_ids'], $masters['fromAreaIds'], $masters['fromAreaNames']);
        // 到着エリア
        $outForm->raw_sp_to_area = $this->_getAreaString($spInfo['to_area_ids'], $masters['toAreaIds'], $masters['toAreaNames']);
        // 期間
        $outForm->raw_sp_period = $this->_getPeriodString($spInfo['min_date'], $spInfo['max_date']);

        // 金額設定有無
        if ($spInfo['priceset_kbn'] === self::PRICESET_KBN_ALL || $spInfo['priceset_kbn'] === self::PRICESET_KBN_EACH) {
            // 有
            $outForm->raw_sp_charge_set_flag = '1';
        } else {
            // 無
            $outForm->raw_sp_charge_set_flag = '0';
        }
    }

    /**
     * 出力フォームに特価のプルダウン情報を設定します。
     * @param Sgmov_Form_Asp002Out $outForm 出力フォーム
     * @param array $spInfo 特価情報
     * @param array $masters マスター情報
     */
    public function _setSpCoursePlanFromAreas($outForm, $spInfo, $masters)
    {
        // プルダウン設定
        $outForm->raw_course_plan_cds = $spInfo['cource_plan_ids'];
        $outForm->raw_from_area_cds = $spInfo['from_area_ids'];

        // コースプラン名
        $outForm->raw_course_plan_lbls = array();
        foreach ($outForm->raw_course_plan_cds as $cd) {
            $outForm->raw_course_plan_lbls[] = $this->_getLabelString($cd, $masters['coursePlanIds'], $masters['coursePlanNames']);
        }

        // 出発エリア名
        $outForm->raw_from_area_lbls = array();
        foreach ($outForm->raw_from_area_cds as $cd) {
            $outForm->raw_from_area_lbls[] = $this->_getLabelString($cd, $masters['fromAreaIds'], $masters['fromAreaNames']);
        }
    }

    /**
     * 出力フォームに特価の明細情報を設定します。
     * @param Sgmov_Component_DB $db DB接続
     * @param Sgmov_Form_Asp002Out $outForm 出力フォーム
     * @param array $spInfo 特価情報
     * @param array $masters マスター情報
     */
    public function _setSpDetailInfo($db, $outForm, $spInfo, $masters)
    {
        // コースプランの名称
        $outForm->raw_cur_course_plan = $this->_getLabelString($outForm->raw_course_plan_cd_sel, $masters['coursePlanIds'],
             $masters['coursePlanNames']);

        // 出発エリアリストの名称
        $outForm->raw_cur_from_area = $this->_getLabelString($outForm->raw_from_area_cd_sel, $masters['fromAreaIds'], $masters['fromAreaNames']);

        // カレンダーURL用開始年月を取得
        $splits = explode(Sgmov_Service_Calendar::DATE_SEPARATOR, $spInfo['min_date'], 3);
        $fromYYYYMM = $splits[0] . $splits[1];

        // 到着エリアリストの名称・カレンダーURL
        $outForm->raw_to_area_lbls = array();
        $outForm->raw_sp_calendar_urls = array();
        $calendarPrefix = '/asp/calendar/' . $this->getFeatureGetParam();
        $delim = Sgmov_Service_CoursePlan::ID_DELIMITER;
        foreach ($spInfo['to_area_ids'] as $cd) {
            $outForm->raw_to_area_lbls[] = $this->_getLabelString($cd, $masters['toAreaIds'], $masters['toAreaNames']);

            $coursePlanFromToCd = $outForm->raw_course_plan_cd_sel . $delim . $outForm->raw_from_area_cd_sel . $delim . $cd;
            $outForm->raw_sp_calendar_urls[] = "{$calendarPrefix}/{$coursePlanFromToCd}/{$fromYYYYMM}";
        }

        // 基本料金
        $outForm->raw_sp_base_charges = array();
        $splits = explode(Sgmov_Service_CoursePlan::ID_DELIMITER, $outForm->raw_course_plan_cd_sel);
        $courseId = $splits[0];
        $planId = $splits[1];
        $basePriceList = $this->_basePriceService->
                                fetchBasePrices($db, $courseId, $planId, $outForm->raw_from_area_cd_sel);
        foreach ($spInfo['to_area_ids'] as $cd) {
            $outForm->raw_sp_base_charges[] = $this->_getLabelString($cd, $basePriceList['to_area_ids'], $basePriceList['base_prices']);
        }

        // 金額
        if ($spInfo['priceset_kbn'] === self::PRICESET_KBN_ALL) {
            $count = count($spInfo['to_area_ids']);
            $outForm->raw_sp_setting_charges = array_fill(0, $count, $spInfo['batchprice']);
        } else if ($spInfo['priceset_kbn'] === self::PRICESET_KBN_EACH) {
            $spDetailInfo = $this->_specialPriceService->
                                    fetchSpecialPriceDetailInfoById($db, $spInfo['id'], $courseId, $planId, $outForm->raw_from_area_cd_sel);
            foreach ($spInfo['to_area_ids'] as $cd) {
                $outForm->raw_sp_setting_charges[] = $this->_getLabelString($cd, $spDetailInfo['to_area_ids'], $spDetailInfo['price_differences']);
            }
        }
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
            $courses[$i] = $this->_getLabelString($courses[$i], $allCourseCds, $allCourseLbls);

            $planCount = count($plans[$i]);
            for ($j = 0; $j < $planCount; $j++) {
                $plans[$i][$j] = $this->_getLabelString($plans[$i][$j], $allPlanCds, $allPlanLbls);
            }
        }

        return array('courses'=>$courses,
                         'plans'=>$plans);
    }

    /**
     * 選択コードに対応するラベル文字列を取得します。
     *
     * @param array $selCd 選択コード
     * @param array $cds コードリスト
     * @param array $lbls 名称リスト
     * @return string 選択コードに対応するラベル文字列
     */
    public function _getLabelString($selCd, $cds, $lbls)
    {
        $key = array_search($selCd, $cds, TRUE);
        if ($key === FALSE) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
        }
        return $lbls[$key];
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

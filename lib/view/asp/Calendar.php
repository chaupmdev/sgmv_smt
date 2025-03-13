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
Sgmov_Lib::useServices(array('Calendar', 'CoursePlan', 'CenterArea', 'BasePrice', 'SpecialPrice'));
Sgmov_Lib::useForms(array('Error', 'AspSession', 'Asp012Out'));
/**#@-*/

 /**
 * 期間カレンダー画面を表示します。
 * @package    View
 * @subpackage ASP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Asp_Calendar extends Sgmov_View_Asp_Common
{

    /**
     * カレンダー日付に表示する特価タイトルの最大文字数
     */
    const TITLE_MAX_LENGTH = 10;

    /**
     * カレンダーサービス
     * @var Sgmov_Service_Calendar
     */
    public $_calendarService;

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
     * 特価サービス
     * @var Sgmov_Service_SpecialPrice
     */
    public $_specialPriceService;

    /**
     * 基本料金サービス
     * @var Sgmov_Service_BasePrice
     */
    public $_basePriceService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        $this->_calendarService = new Sgmov_Service_Calendar();
        $this->_centerAreaService = new Sgmov_Service_CenterArea();
        $this->_coursePlanService = new Sgmov_Service_CoursePlan();
        $this->_specialPriceService = new Sgmov_Service_SpecialPrice();
        $this->_basePriceService = new Sgmov_Service_BasePrice();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * GETパラメーターのチェック
     * </li><li>
     * 編集モードの場合はASP004～ASP006と金額情報が設定されていることを確認する
     * </li><li>
     * GETパラメーターから出力フォームを生成
     * </li><li>
     * カレンダー基本情報をセット
     * </li><li>
     * カレンダー公開料金情報をセット
     * </li><li>
     * セッション情報を元に出力情報を設定
     * </li><li>
     * チケット発行
     * </li></ol>
     *
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
        // GETパラメーターのチェック
        $params = $this->_parseGetParameter();
        $courseId = $params['courseId'];
        $planId = $params['planId'];
        $fromAreaId = $params['fromId'];
        $toAreaId = $params['toId'];
        $year = $params['year'];
        $month = $params['month'];
        $edit = $params['edit'];

        if ($edit) {
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
        }

        $outForm = $this->_createOutFormByGetParam($courseId, $planId, $fromAreaId, $toAreaId, $year, $month);
        Sgmov_Component_Log::debug('カレンダー基本情報をセット');
        $outForm = $this->_setCalendarBasicValues($outForm, $courseId, $planId, $fromAreaId, $toAreaId, $year, $month, $edit);
        if ($edit) {
            // 特価の新規作成ではなく編集の場合は、その金額はカレンダー公開料金としては取得しない
            $sp_cd = NULL;
            if(!empty($sessionForm->sp_cd)){
                $sp_cd = $sessionForm->sp_cd;
            }

            Sgmov_Component_Log::debug('カレンダー公開料金情報をセット');
            $outForm = $this->_setCalendarPriceValues($outForm, $courseId, $planId, $fromAreaId, $toAreaId, $sp_cd);

            Sgmov_Component_Log::debug('セッション情報を元に出力情報を設定');
            $outForm = $this->_setEditingPriceValues($outForm, $courseId, $planId, $fromAreaId, $toAreaId, $sessionForm);
            $outForm->raw_edit_flag = '1';
        } else {
            Sgmov_Component_Log::debug('カレンダー公開料金情報をセット');
            $outForm = $this->_setCalendarPriceValues($outForm, $courseId, $planId, $fromAreaId, $toAreaId);
            $outForm->raw_edit_flag = '0';
        }
        return array('outForm'=>$outForm);
    }

    /**
     * GETパラメータから特価IDを取得します。
     *
     * [パラメータ]
     * <ol><li>
     * コースID_プランID_出発エリアID_到着エリアID
     * </li><li>
     * 年月(YYYYMM)
     * </li><li>
     * 編集モードの場合はedit
     * </li></ol>
     *
     * [例]
     * <ul><li>
     * 編集中の情報
     *   <ul><li>
     *   /asp/calendar/campaign/1_1_1_1/201001/edit
     *   </li><li>
     *   /asp/calendar/extra/1_1_1_1/201001/edit
     *   </li></ul>
     * </li><li>
     * 詳細情報(どちらでも同じ内容が表示される)
     *   <ul><li>
     *   /asp/calendar/campaign/1_1_1_1/201001
     *   </li><li>
     *   /asp/calendar/extra/1_1_1_1/201001
     *   </li></ul>
     * </li></ul>
     * @return array
     * ['courseId']:コースID
     * ['planId']:プランID
     * ['fromId']:出発エリアID
     * ['toId']:到着エリアID
     * ['year']:年(YYYY)
     * ['month']:月(MM)
     * ['edit']:編集モード(FALSE:詳細、TRUE:編集)
     */
    public function _parseGetParameter()
    {
        if (!isset($_GET['param'])) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_GET[\'param\']が未設定です。');
        }

        $params = explode('/', $_GET['param'], 4);
        $paramCount = count($params);
        if ($paramCount == 3) {
            $edit = FALSE;
        } elseif ($paramCount == 4) {
            if ($params[3] === 'edit') {
                $edit = TRUE;
            } else {
                // 不正遷移
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_GET[\'param\']が不正です。');
            }
        } else {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_GET[\'param\']が不正です。');
        }

        $ids = explode(Sgmov_Service_CoursePlan::ID_DELIMITER, $params[1], 4);
        if (count($ids) != 4) {
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

        $toAreaId = $ids[3];
        $v = Sgmov_Component_Validator::createSingleValueValidator($toAreaId);
        $v->isNotEmpty()->

            isInteger(0);
        if (!$v->isValid()) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_GET[\'param\']が不正です。');
        } else {
            // 数値文字列として取得しなおす(先頭の0を除去)
            $toAreaId = (string) intval($toAreaId);
        }

        // 年月
        $ym = $params[2];

        // 1年後までの年月文字列を取得
        $period = $this->_calendarService->

                        getOneYearPeriod(time());
        $yyyymmList = $this->_calendarService->

                            getYYYYMMList($period['from'], $period['to']);
	
        $v = Sgmov_Component_Validator::createSingleValueValidator($ym);
        $v->isNotEmpty()->

            isIn($yyyymmList);
        if (!$v->isValid()) {
            // 不正遷移
	    // 当日から1年後の範囲に$ym（postデータ）がなければメンテナンスエラーにしている
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_GET[\'param\']が不正です。');
        }

        $year = Sgmov_Component_String::substr($ym, 0, 4);
        $month = Sgmov_Component_String::substr($ym, 4, 2);

        return array('courseId'=>$courseId,

                         'planId'=>$planId,

                         'fromId'=>$fromAreaId,

                         'toId'=>$toAreaId,

                         'year'=>$year,

                         'month'=>$month,

                         'edit'=>$edit);
    }

    /**
     * 出力フォームを生成します。
     * @param string $courseId コースID
     * @param string $planId プランID
     * @param string $fromAreaId 出発エリアID
     * @param string $toAreaId 到着エリアID
     * @param string $year 対象年(YYYY)
     * @param string $month 対象月(MM)
     * @return Sgmov_Form_Asp012Out 出力フォーム
     */
    public function _createOutFormByGetParam($courseId, $planId, $fromAreaId, $toAreaId, $year, $month)
    {
        $db = Sgmov_Component_DB::getAdmin();
        $outForm = new Sgmov_Form_Asp012Out();

        // コース
        $courseList = $this->_coursePlanService->

                            fetchCourseList($db);
        $outForm->raw_course = $this->_getNameByCode($courseId, $courseList['ids'], $courseList['names']);

        // プラン
        $planList = $this->_coursePlanService->

                            fetchPlanList($db);
        $outForm->raw_plan = $this->_getNameByCode($planId, $planList['ids'], $planList['names']);

        // 出発エリア
        $fromAreaList = $this->_centerAreaService->

                                fetchFromAreaList($db);
        $outForm->raw_from_area = $this->_getNameByCode($fromAreaId, $fromAreaList['ids'], $fromAreaList['names']);

        // 到着エリア
        $toAreaList = $this->_centerAreaService->

                            fetchToAreaList($db);
        $outForm->raw_to_area = $this->_getNameByCode($toAreaId, $toAreaList['ids'], $toAreaList['names']);

        $outForm->raw_cal_year = $year;
        $outForm->raw_cal_month = $month;
        return $outForm;
    }

    /**
     * コードの配列と名称の配列からコードに対応する名称を取得します。
     * @param string $cd 検索するコード
     * @param array $codes コード配列
     * @param array $labels 名称配列
     * @return string 検索コードに対応する名称
     */
    public function _getNameByCode($cd, $codes, $labels)
    {
        $key = array_search($cd, $codes, TRUE);
        if ($key === FALSE) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
        }
        return $labels[$key];
    }

    /**
     * 出力フォームにカレンダーの基本情報を設定します。
     * @param Sgmov_Form_Asp012Out $outForm 出力フォーム
     * @param string $courseId コースID
     * @param string $planId プランID
     * @param string $fromAreaId 出発エリアID
     * @param string $toAreaId 到着エリアID
     * @param string $year 対象年(YYYY)
     * @param string $month 対象月(MM)
     * @param boolean $edit 編集フラグ(TRUE:編集モード FALSE:詳細モード)
     * @return Sgmov_Form_Asp012Out 出力フォーム
     */
    public function _setCalendarBasicValues($outForm, $courseId, $planId, $fromAreaId, $toAreaId, $year, $month, $edit)
    {
        // 本日と1年後の日付を取得
        $validPeriod = $this->_calendarService->

                            getOneYearPeriod(time());
        $min = $validPeriod['from'];
        $max = $validPeriod['to'];

        // 週が途中の場合前月・次月含む、カレンダーの範囲を取得
        $monthCalendarPeriod = $this->_calendarService->

                                    getMonthCalendarShowPeriod($year, $month);
        $monthFrom = $monthCalendarPeriod['from'];
        $monthTo = $monthCalendarPeriod['to'];

        // 有効な開始終了日を取得（該当月の1日から末日の中で本日以降1年後以下の範囲のもの)
        $validFromDay = mktime(0, 0, 0, intval($month), 1, intval($year));
        if ($validFromDay < $min) {
            $validFromDay = $min;
        }
        $validToDay = mktime(0, 0, 0, intval($month) + 1, 0, intval($year));
        if ($validToDay > $max) {
            $validToDay = $max;
        }

        // 祝日を取得
        $db = Sgmov_Component_DB::getAdmin();
        $holidays = $this->_calendarService->

                            fetchHolidays($db, $monthFrom, $monthTo);

        // カレンダー情報を取得
        $calendar = $this->_calendarService->

                            getBasicDateInfoDaily($monthFrom, $monthTo, $validFromDay, $validToDay, $holidays);
        $outForm->raw_cal_days = $calendar['days'];
        $outForm->raw_cal_weekday_flags = $calendar['weekday_cds'];
        $outForm->raw_cal_holiday_flags = $calendar['holiday_flags'];
        $outForm->raw_cal_valid_flags = $calendar['between_flags'];

        // 前月・次月リンク
        $sep = Sgmov_Service_CoursePlan::ID_DELIMITER;
        $urlPrefix = "/asp/calendar/" . $this->getFeatureGetParam() . "/{$courseId}{$sep}{$planId}{$sep}{$fromAreaId}{$sep}{$toAreaId}/";

        if ($validFromDay == $min) {
            $outForm->raw_prev_month_link = NULL;
        } else {
            $prevMonth = date('Ym', mktime(0, 0, 0, intval($month) - 1, 1, intval($year)));
            $outForm->raw_prev_month_link = $urlPrefix . $prevMonth;
            if ($edit) {
                $outForm->raw_prev_month_link .= '/edit';
            }
        }
        if ($validToDay == $max) {
            $outForm->raw_next_month_link = NULL;
        } else {
            $nextMonth = date('Ym', mktime(0, 0, 0, intval($month) + 1, 1, intval($year)));
            $outForm->raw_next_month_link = $urlPrefix . $nextMonth;
            if ($edit) {
                $outForm->raw_next_month_link .= '/edit';
            }
        }

        return $outForm;
    }

    /**
     * 出力フォームにカレンダーの特価・料金情報を設定します。
     * @param Sgmov_Form_Asp012Out $outForm 出力フォーム
     * @param string $courseId コースID
     * @param string $planId プランID
     * @param string $fromAreaId 出発エリアID
     * @param string $toAreaId 到着エリアID
     * @param string $exclude_special_price_id [optional] 金額合計から除外する特価のID
     * @return Sgmov_Form_Asp012Out 出力フォーム
     */
    public function _setCalendarPriceValues($outForm, $courseId, $planId, $fromAreaId, $toAreaId, $exclude_special_price_id = NULL)
    {
        // DB接続
        $db = Sgmov_Component_DB::getAdmin();

        // 初期化
        $outForm->raw_cal_campaign_flags = array();
        $outForm->raw_cal_extra_flags = array();
        $outForm->raw_cal_prices = array();
        $outForm->raw_cal_sp_names = array();
        $outForm->raw_cal_sp_urls = array();
        $outForm->raw_cal_editing_flags = array();

        $daysCount = count($outForm->raw_cal_days);
        for ($i = 0; $i < $daysCount; $i++) {
            $campaign_flag = '0';
            $extra_flag = '0';
            $price = 0;
            $sp_names = array();
            $sp_urls = array();

            // 基本料金を取得
            $basePrices = $this->_basePriceService->

                                getBaseMinMaxPrice($db, $courseId, $planId, $fromAreaId, $toAreaId);
            $price = intval($basePrices['base_price']);

            // 特価情報を取得
            $specialPrices = $this->_specialPriceService->

                                    fetchSpecialPrices($db, $courseId, $planId, $fromAreaId, $toAreaId, $outForm->raw_cal_days[$i]);
            $ids = $specialPrices['ids'];
            $special_price_divisions = $specialPrices['special_price_divisions'];
            $titles = $specialPrices['titles'];
            $charges = $specialPrices['charges'];
            $spCount = count($ids);
            for ($j = 0; $j < $spCount; $j++) {
                // 編集中の特価は含まない
                if(!is_null($exclude_special_price_id) && $exclude_special_price_id === $ids[$j]){
                    continue;
                }

                if ($special_price_divisions[$j] === Sgmov_Service_SpecialPrice::SPECIAL_PRICE_DIVISION_EXTRA) {
                    $extra_flag = '1';
                } else if ($special_price_divisions[$j] === Sgmov_Service_SpecialPrice::SPECIAL_PRICE_DIVISION_CAMPAIGN) {
                    $campaign_flag = '1';
                } else {
                    Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, '特価データ不整合');
                }
                $price += intval($charges[$j]);
                if (Sgmov_Component_String::getCount($titles[$j]) > self::TITLE_MAX_LENGTH) {
                    $sp_names[] = Sgmov_Component_String::substr($titles[$j], 0, self::TITLE_MAX_LENGTH) . '...';
                } else {
                    $sp_names[] = $titles[$j];
                }
                $sp_urls[] = '/asp/cal_detail/' . $ids[$j];
            }

            // 設定
            $outForm->raw_cal_campaign_flags[] = $campaign_flag;
            $outForm->raw_cal_extra_flags[] = $extra_flag;
            $outForm->raw_cal_prices[] = $price;
            $outForm->raw_cal_sp_names[] = $sp_names;
            $outForm->raw_cal_sp_urls[] = $sp_urls;
            // 編集フラグは'0'で初期化しておく(編集モードではない場合のため)
            $outForm->raw_cal_editing_flags[] = '0';
        }

        return $outForm;
    }

    /**
     * 出力フォームにカレンダーの特価・料金情報を設定します。
     * @param Sgmov_Form_Asp012Out $outForm 出力フォーム
     * @param string $courseId コースID
     * @param string $planId プランID
     * @param string $fromAreaId 出発エリアID
     * @param string $toAreaId 到着エリアID
     * @param Sgmov_Form_AspSession $sessionForm セッションフォーム
     * @return Sgmov_Form_Asp012Out 出力フォーム
     */
    public function _setEditingPriceValues($outForm, $courseId, $planId, $fromAreaId, $toAreaId, $sessionForm)
    {
        // 編集フラグは'0'で初期化されている(_setCalendarPriceValues)
        $daysCount = count($outForm->raw_cal_days);
        if ($sessionForm->priceset_kbn === self::PRICESET_KBN_ALL) {
            Sgmov_Component_Log::warning(Sgmov_Component_String::toDebugString($sessionForm->asp006_in->sel_days, TRUE));
            for ($i = 0; $i < $daysCount; $i++) {
                if (!in_array($outForm->raw_cal_days[$i], $sessionForm->asp006_in->sel_days)) {
                    continue;
                }

                $outForm->raw_cal_editing_flags[$i] = '1';
                $outForm->raw_cal_prices[$i] = $outForm->raw_cal_prices[$i] + intval($sessionForm->asp008_in->sp_whole_charge);
            }
        } else if ($sessionForm->priceset_kbn === self::PRICESET_KBN_EACH) {
            $sep = Sgmov_Service_CoursePlan::ID_DELIMITER;
            $key = "{$courseId}{$sep}{$planId}{$sep}{$fromAreaId}";
            for ($i = 0; $i < $daysCount; $i++) {
                if (!in_array($outForm->raw_cal_days[$i], $sessionForm->asp006_in->sel_days)) {
                    continue;
                }

                $toAreaIndex = array_search($toAreaId, $sessionForm->asp009_in->to_area_sel_cds);
                if ($toAreaIndex === FALSE) {
                    Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
                }

                $outForm->raw_cal_editing_flags[$i] = '1';
                $outForm->raw_cal_prices[$i] = $outForm->raw_cal_prices[$i] + $sessionForm->asp009_in->all_charges[$key][$toAreaIndex];
            }
        } else if ($sessionForm->priceset_kbn === self::PRICESET_KBN_NONE) {
            for ($i = 0; $i < $daysCount; $i++) {
                if (in_array($outForm->raw_cal_days[$i], $sessionForm->asp006_in->sel_days)) {
                    continue;
                }
                $outForm->raw_cal_editing_flags[$i] = '1';
            }
        } else {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, '料金設定フラグ不整合');
        }

        return $outForm;
    }
}
?>

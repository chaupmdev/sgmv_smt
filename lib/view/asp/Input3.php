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
Sgmov_Lib::useServices(array('Calendar'));
Sgmov_Lib::useForms(array('Error', 'AspSession', 'Asp006Out'));
/**#@-*/

 /**
 * 特価編集個別編集期間入力画面を表示します。
 * @package    View
 * @subpackage ASP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Asp_Input3 extends Sgmov_View_Asp_Common
{
    /**
     * カレンダーサービス
     * @var Sgmov_Service_Calendar
     */
    public $_calendarService;

    /**
     * 月
     * @var array
     */
    public $_months = array('',
                             '01',
                             '02',
                             '03',
                             '04',
                             '05',
                             '06',
                             '07',
                             '08',
                             '09',
                             '10',
                             '11',
                             '12');

    /**
     * 日
     * @var array
     */
    public $_days = array('',
                             '01',
                             '02',
                             '03',
                             '04',
                             '05',
                             '06',
                             '07',
                             '08',
                             '09',
                             '10',
                             '11',
                             '12',
                             '13',
                             '14',
                             '15',
                             '16',
                             '17',
                             '18',
                             '19',
                             '20',
                             '21',
                             '22',
                             '23',
                             '24',
                             '25',
                             '26',
                             '27',
                             '28',
                             '29',
                             '30',
                             '31');

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        $this->_calendarService = new Sgmov_Service_Calendar();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * セッションに入力チェック済みのASP004・ASP005情報があるかどうかを確認
     * </li><li>
     * セッション情報を元に出力情報を設定
     * </li><li>
     * テンプレート用の値をセット
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
        Sgmov_Component_Log::debug('セッションに入力チェック済みのASP004・ASP005情報があるかどうかを確認');
        $session = Sgmov_Component_Session::get();
        $sessionForm = $session->loadForm($this->getFeatureId());
        if(is_null($sessionForm)){
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, 'sessionFormがnullです。');
        }
        if ($sessionForm->asp004_status !== self::VALIDATION_SUCCEEDED) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, 'ASP004が未チェックです。');
        }
        if ($sessionForm->asp005_status !== self::VALIDATION_SUCCEEDED) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, 'ASP005が未チェックです。');
        }

        Sgmov_Component_Log::debug('セッション情報を元に出力情報を設定');
        $outForm = $this->_createOutFormBySessionForm($sessionForm);
        if (isset($sessionForm->asp006_error)) {
            $errorForm = $sessionForm->asp006_error;
        } else {
            $errorForm = new Sgmov_Form_Error();
        }

        Sgmov_Component_Log::debug('テンプレート用の値をセット');
        $outForm = $this->_setTemplateValuesToOutForm($outForm);

        Sgmov_Component_Log::debug('チケット発行');
        $ticket = $session->publishTicket($this->getFeatureId(), self::GAMEN_ID_ASP006);

        return array('ticket'=>$ticket,
                         'outForm'=>$outForm,
                         'errorForm'=>$errorForm);
    }

    /**
     * セッションフォームの値を元に出力フォームを生成します。
     * @param Sgmov_Form_AspSession $sessionForm セッションフォーム
     * @return Sgmov_Form_Asp006Out 出力フォーム
     */
    public function _createOutFormBySessionForm($sessionForm)
    {
        $outForm = new Sgmov_Form_Asp006Out();

        // 本社ユーザーかどうか
        $outForm->raw_honsha_user_flag = $this->getHonshaUserFlag();
        // 一覧に戻るリンクのURL
        $outForm->raw_sp_list_url = $this->createSpListUrl($sessionForm->sp_list_kind, $sessionForm->sp_list_view_mode);
        // 閑散繁忙設定かキャンペーン設定か
        $outForm->raw_sp_kind = $this->getSpKind();

        // セッション値の適用
        if (isset($sessionForm->asp006_in)) {
            $outForm->raw_sel_days = $sessionForm->asp006_in->sel_days;
        }
        return $outForm;
    }

    /**
     * 出力フォームにテンプレート用の値を設定して返します。
     * @param Sgmov_Form_Asp006Out $outForm 出力フォーム
     * @return Sgmov_Form_Asp006Out 出力フォーム
     */
    public function _setTemplateValuesToOutForm($outForm)
    {
        $db = Sgmov_Component_DB::getAdmin();

        // 一括指定用プルダウン
        $outForm->raw_from_year_cds = $this->_getYears();
        $outForm->raw_from_year_lbls = $outForm->raw_from_year_cds;

        $outForm->raw_to_year_cds = $outForm->raw_from_year_cds;
        $outForm->raw_to_year_lbls = $outForm->raw_from_year_cds;

        $outForm->raw_from_month_cds = $this->_months;
        $outForm->raw_from_month_lbls = $this->_months;

        $outForm->raw_to_month_cds = $this->_months;
        $outForm->raw_to_month_lbls = $this->_months;

        $outForm->raw_from_day_cds = $this->_days;
        $outForm->raw_from_day_lbls = $this->_days;

        $outForm->raw_to_day_cds = $this->_days;
        $outForm->raw_to_day_lbls = $this->_days;

        // 本日と1年後を取得
        $period = $this->_calendarService->getOneYearPeriod(time());
        // カレンダー用の開始終了日を取得
        $calendarPeriod = $this->_calendarService->getMultipleMonthsCalendarPeriod($period['from'], $period['to']);
        // 祝日を取得
        $db = Sgmov_Component_DB::getAdmin();
        $holidays = $this->_calendarService->
                            fetchHolidays($db, $calendarPeriod['from'], $calendarPeriod['to']);

        // カレンダー情報を取得
        $calendar = $this->_calendarService->getBasicDateInfoMonthly($calendarPeriod['from'], $calendarPeriod['to'], $period['from'], $period['to'], $holidays['holidays']);

        $outForm->raw_days = $calendar['days'];
        $outForm->raw_weekday_cds = $calendar['weekday_cds'];
        $outForm->raw_holiday_flags = $calendar['holiday_flags'];
        $outForm->raw_check_show_flags = $calendar['between_flags'];

        return $outForm;
    }

    /**
     * 今年と来年の年の文字列配列(YYYY)を生成します。
     *
     * 先頭には空白の項目を含みます。
     *
     * @return array 今年と来年の年の配列
     */
    public function _getYears()
    {
        $years = array();
        $years[] = '';

        $min_year = date('Y');
        $max_year = $min_year + 1;
        for ($i = $min_year; $i <= $max_year; $i++) {
            $years[] = sprintf('%04d', $i);
        }

        return $years;
    }
}
?>

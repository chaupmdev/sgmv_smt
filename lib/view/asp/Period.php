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
Sgmov_Lib::useServices(array('Calendar', 'SpecialPrice'));
Sgmov_Lib::useForms(array('Error', 'AspSession', 'Asp013Out'));
/**#@-*/

 /**
 * 期間カレンダー画面を表示します。
 * @package    View
 * @subpackage ASP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Asp_Period extends Sgmov_View_Asp_Common
{
    /**
     * カレンダーサービス
     * @var Sgmov_Service_Calendar
     */
    public $_calendarService;

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
        $this->_calendarService = new Sgmov_Service_Calendar();
        $this->_specialPriceService = new Sgmov_Service_SpecialPrice();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * GETパラメーターのチェック
     * </li><li>
     * [編集モードの場合]
     *   <ol><li>
     *   セッションに入力チェック済みのASP006情報があるかどうかを確認
     *   </li><li>
     *   セッション情報を元に出力情報を設定
     *   </li></ol>
     * </li><li>
     * [詳細モードの場合]
     *   <ol><li>
     *   特価IDを元に出力情報を設定
     *   </li></ol>
     * </li><li>
     * テンプレート用の値をセット
     * </li></ol>
     *
     * @return array 生成されたフォーム情報。
     * ['outForm']:出力フォーム
     */
    public function executeInner()
    {
        // GETパラメーターのチェック
        $id = $this->_getSpecialPriceId();

        if ($id === 'edit') {
            Sgmov_Component_Log::debug('セッションに入力チェック済みのASP006情報があるかどうかを確認');
            $session = Sgmov_Component_Session::get();
            $sessionForm = $session->loadForm($this->getFeatureId());
            if(is_null($sessionForm)){
                // 不正遷移
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, 'sessionFormがnullです。');
            }
            if ($sessionForm->asp006_status !== self::VALIDATION_SUCCEEDED) {
                // 不正遷移
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, 'ASP006が未チェックです。');
            }

            Sgmov_Component_Log::debug('セッション情報を元に出力情報を設定');
            $outForm = $this->_createOutFormBySessionForm($sessionForm);
        } else {
            Sgmov_Component_Log::debug('特価IDを元に出力情報を設定');
            $outForm = $this->_createOutFormByDB($id);
        }

        Sgmov_Component_Log::debug('テンプレート用の値をセット');
        $outForm = $this->_setTemplateValuesToOutForm($outForm);

        return array('outForm'=>$outForm);
    }

    /**
     * GETパラメータから特価IDを取得します。
     * @return string 特価ID文字列。編集モードの場合は'edit'を返します。
     */
    public function _getSpecialPriceId()
    {
        if (!isset($_GET['param'])) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_GET[\'param\']が未設定です。');
        }
        $params = explode('/', $_GET['param'], 2);
        if (count($params) != 2) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_GET[\'param\']が不正です。');
        }

        $param = $params[1];
        if ($param === 'edit') {
            return $param;
        } else {
            $v = Sgmov_Component_Validator::createSingleValueValidator($param);
            $v->isNotEmpty()->
                isInteger(0);
            if (!$v->isValid()) {
                // 不正遷移
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_GET[\'param\']が不正です。');
            } else {
                // 数値文字列として取得しなおす(先頭の0を除去)
                $param = (string) intval($param);
            }
            return $param;
        }
    }

    /**
     * セッションフォームの値を元に出力フォームを生成します。
     * @param Sgmov_Form_AspSession $sessionForm セッションフォーム
     * @return Sgmov_Form_Asp013Out 出力フォーム
     */
    public function _createOutFormBySessionForm($sessionForm)
    {
        $outForm = new Sgmov_Form_Asp013Out();
        // セッション値の適用
        $outForm->raw_sel_days = $sessionForm->asp006_in->sel_days;
        return $outForm;
    }

    /**
     * DBの値を元に出力フォームを生成します。
     * @param string $id 特価ID
     * @return Sgmov_Form_Asp013Out 出力フォーム
     */
    public function _createOutFormByDB($id)
    {
        $outForm = new Sgmov_Form_Asp013Out();
        // DBから日付情報を取得
        $db = Sgmov_Component_DB::getAdmin();
        $result = $this->_specialPriceService->
                        fetchTargetDates($db, $id);
        $outForm->raw_sel_days = $result['target_dates'];
        return $outForm;
    }

    /**
     * 出力フォームにテンプレート用の値を設定して返します。
     * @param Sgmov_Form_Asp013Out $outForm 出力フォーム
     * @return Sgmov_Form_Asp013Out 出力フォーム
     */
    public function _setTemplateValuesToOutForm($outForm)
    {
        $db = Sgmov_Component_DB::getAdmin();

        // 本日と1年後を取得
        $period = $this->_calendarService->getOneYearPeriod(time());

        // 特価情報を取得
        $dates = $outForm->sel_days();

        // 表示開始日
        $startDate = strtotime(array_shift($dates));

        // 表示最終日
        $endDate = strtotime('+12 month', $startDate);

        // カレンダー用の開始終了日を取得
        $calendarPeriod = $this->_calendarService->
                                getMultipleMonthsCalendarPeriod($startDate, $endDate);

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

}
?>

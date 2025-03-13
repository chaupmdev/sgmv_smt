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
Sgmov_Lib::useServices('Calendar');
Sgmov_Lib::useForms(array('Error', 'AspSession', 'Asp006In'));
/**#@-*/

 /**
 * 特価編集個別編集期間入力情報をチェックします。
 * @package    View
 * @subpackage ASP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Asp_CheckInput3 extends Sgmov_View_Asp_Common
{
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
        $this->_calendarService = new Sgmov_Service_Calendar();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * チケットの確認と破棄
     * </li><li>
     * 情報をセッションに保存
     * </li><li>
     * 入力チェック
     * </li><li>
     * 入力エラー有り:input3へリダイレクト
     * </li><li>
     * 入力エラー無し:input4へリダイレクト
     * </li></ol>
     */
    public function executeInner()
    {
        Sgmov_Component_Log::debug('チケットの確認と破棄');
        $session = Sgmov_Component_Session::get();
        $session->checkTicket($this->getFeatureId(), self::GAMEN_ID_ASP006, $this->_getTicket());

        Sgmov_Component_Log::debug('情報を取得');
        $inForm = $this->_createInFormFromPost($_POST);

        Sgmov_Component_Log::debug('入力チェック');
        $errorForm = $this->_validate($inForm);

        Sgmov_Component_Log::debug('情報をセッションに保存');
        $sessionForm = $session->loadForm($this->getFeatureId());
        $sessionForm->asp006_in = $inForm;
        $sessionForm->asp006_error = $errorForm;
        if ($errorForm->hasError()) {
            $sessionForm->asp006_status = self::VALIDATION_FAILED;
        } else {
            $sessionForm->asp006_status = self::VALIDATION_SUCCEEDED;
        }
        $sessionForm->asp008_status = NULL;
        $sessionForm->asp009_status = NULL;
        $session->saveForm($this->getFeatureId(), $sessionForm);

        if ($errorForm->hasError()) {
            $to = '/asp/input3/' . $this->getFeatureGetParam();
        } else {
            $to = '/asp/input4/' . $this->getFeatureGetParam();
        }
        Sgmov_Component_Log::debug('リダイレクト ' . $to);
        Sgmov_Component_Redirect::redirectMaintenance($to);
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
     * POST情報から入力フォームを生成します。
     * @param array $post ポスト情報
     * @return Sgmov_Form_Asp006In 入力フォーム
     */
    public function _createInFormFromPost($post)
    {
        $inForm = new Sgmov_Form_Asp006In();
        if (isset($post['sel_days'])) {
            $inForm->sel_days = $post['sel_days'];
        } else {
            $inForm->sel_days = array();
        }
        return $inForm;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_Asp006In $inForm 入力フォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($inForm)
    {
        if (Sgmov_Component_Log::isDebug()) {
            Sgmov_Component_Log::debug('入力チェック:$inForm=' . Sgmov_Component_String::toDebugString($inForm));
        }
        $errorForm = new Sgmov_Form_Error();

        // 配列ではない場合はシステムエラー
        if (!is_array($inForm->sel_days)) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT, '不正入力：選択日付が配列ではありません。');
        } else if (count($inForm->sel_days) === 0) {
            $errorForm->addError('top_sel_days', 'を選択してください。');
        } else {
            // 値に重複がある場合はシステムエラー
            if (count($inForm->sel_days) != count(array_unique($inForm->sel_days))) {
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT, '不正入力：選択日付に重複があります。');
            }

            // 最大最小値を取得
            $period = $this->_calendarService->getOneYearPeriod(time());

            // 値が正しい日付文字列であること
            $outOfPeriod = FALSE;
            foreach ($inForm->sel_days as $day) {
                // '-'で分割
                $splits = explode(Sgmov_Service_Calendar::DATE_SEPARATOR, $day, 3);
                $v = Sgmov_Component_Validator::createDateValidator($splits[0], $splits[1], $splits[2]);
                $v->isDate($period['from'], $period['to']);

                if (!$v->isValid()) {
                    if ($v->_result === Sgmov_Component_Validator::INVALID_DATE_TOO_BIG || $v->_result === Sgmov_Component_Validator::INVALID_DATE_TOO_SMALL) {
                        // 日付が指定の範囲にあることを確認
                        // 0時をまたいだ場合に発生の可能性がある。
                        $outOfPeriod = TRUE;
                    } else {
                        // 通常の入力ではそれ以外のエラーはありえない
                        Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT, '不正入力：選択日付の入力値が不正です。');
                    }
                }
            }

            if ($outOfPeriod) {
                $errorForm->addError('top_sel_days', 'に範囲外のものが含まれています。');
            }
        }
        return $errorForm;
    }
}
?>

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
Sgmov_Lib::useServices(array('WorkPrice'));
Sgmov_Lib::useForms(array('Error', 'AspSession', 'Asp008Out'));
/**#@-*/

 /**
 * 特価一括編集金額入力画面を表示します。
 * @package    View
 * @subpackage ASP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Asp_Input5 extends Sgmov_View_Asp_Common
{
    /**
     * 一時料金サービス
     * @var Sgmov_Service_WorkPrice
     */
    public $_workPriceService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        $this->_workPriceService = new Sgmov_Service_WorkPrice();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * セッションに入力チェック済みのASP004～ASP006情報があるかどうかを確認
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
        Sgmov_Component_Log::debug('セッションに入力チェック済みのASP004～ASP006情報があるかどうかを確認');
        $session = Sgmov_Component_Session::get();
        $sessionForm = $session->loadForm($this->getFeatureId());
        if (is_null($sessionForm)) {
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
        if ($sessionForm->asp006_status !== self::VALIDATION_SUCCEEDED) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, 'ASP006が未チェックです。');
        }

        Sgmov_Component_Log::debug('セッション情報を元に出力情報を設定');
        $outForm = $this->_createOutFormBySessionForm($sessionForm);
        if (isset($sessionForm->asp008_error)) {
            $errorForm = $sessionForm->asp008_error;
        } else {
            $errorForm = new Sgmov_Form_Error();
        }

        Sgmov_Component_Log::debug('チケット発行');
        $ticket = $session->publishTicket($this->getFeatureId(), self::GAMEN_ID_ASP008);

        return array('ticket'=>$ticket,
                         'outForm'=>$outForm,
                         'errorForm'=>$errorForm);
    }

    /**
     * セッションフォームの値を元に出力フォームを生成します。
     * @param Sgmov_Form_AspSession $sessionForm セッションフォーム
     * @return Sgmov_Form_Asp008Out 出力フォーム
     */
    public function _createOutFormBySessionForm($sessionForm)
    {
        $outForm = new Sgmov_Form_Asp008Out();

        // 本社ユーザーかどうか
        $outForm->raw_honsha_user_flag = $this->getHonshaUserFlag();
        // 一覧に戻るリンクのURL
        $outForm->raw_sp_list_url = $this->createSpListUrl($sessionForm->sp_list_kind, $sessionForm->sp_list_view_mode);
        // 閑散繁忙設定かキャンペーン設定か
        $outForm->raw_sp_kind = $this->getSpKind();

        // セッション値の適用
        if (isset($sessionForm->asp008_in)) {
            $outForm->raw_sp_whole_charge = $sessionForm->asp008_in->sp_whole_charge;
        }

        // 差額の上限・下限料金
        $db = Sgmov_Component_DB::getAdmin();
        $ret = $this->_workPriceService->
                    fetchAllBaseDiffMinMaxPrice($db, $sessionForm->asp005_in->course_plan_sel_cds, $sessionForm->asp005_in->from_area_sel_cds,
                         $sessionForm->asp005_in->to_area_sel_cds, $sessionForm->asp006_in->sel_days);

        $outForm->raw_sp_diff_min = $ret['diff_min'];
        $outForm->raw_sp_diff_max = $ret['diff_max'];

        return $outForm;
    }

}
?>

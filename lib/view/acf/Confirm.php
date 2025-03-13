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
Sgmov_Lib::useView('acf/Common');
Sgmov_Lib::useServices(array('Login'));
Sgmov_Lib::useForms(array('Error', 'AcfSession', 'Acf003Out'));
/**#@-*/

 /**
 * 料金マスタメンテナンス確認画面を表示します。
 * @package    View
 * @subpackage ACF
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Acf_Confirm extends Sgmov_View_Acf_Common
{
    /**
     * ログインサービス
     * @var Sgmov_Service_Login
     */
    public $_loginService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        $this->_loginService = new Sgmov_Service_Login();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * セッションの継続を確認
     * </li><li>
     * セッションに入力チェック済みの情報があるかどうかを確認
     * </li><li>
     * セッション情報を元に出力情報を設定
     * </li><li>
     * チケット発行
     * </li></ol>
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['ticket']:チケット文字列
     * </li><li>
     * ['outForm']:出力フォーム
     * </li></ul>
     */
    public function executeInner()
    {
        // セッションに入力チェック済みの情報があるかどうかを確認
        $session = Sgmov_Component_Session::get();
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        if (!isset($sessionForm) || $sessionForm->status != self::VALIDATION_SUCCEEDED) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
        }

        // セッション情報を元に出力情報を設定
        $outForm = $this->_createOutFormFromInForm($sessionForm);

        // チケットを発行
        $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_ACF003);

        return array('ticket'=>$ticket,
                         'outForm'=>$outForm);
    }

    /**
     * セッションフォームの値を元に出力フォームを生成します。
     * @param Sgmov_Form_AcfSession $sessionForm 入力フォーム
     * @return Sgmov_Form_Acf003Out 出力フォーム
     */
    public function _createOutFormFromInForm($sessionForm)
    {
        $outForm = new Sgmov_Form_Acf003Out();
        $outForm->raw_honsha_user_flag = $this->_loginService->
                                                getHonshaUserFlag();

        $outForm->raw_course_plan = $sessionForm->cur_course_plan;
        $outForm->raw_from_area = $sessionForm->cur_from_area;
        $outForm->raw_to_area_lbls = $sessionForm->to_area_lbls;

        $outForm->raw_base_prices = array();
        $outForm->raw_max_prices = array();
        $outForm->raw_min_prices = array();
        $outForm->raw_base_price_edit_flags = array();
        $outForm->raw_max_price_edit_flags = array();
        $outForm->raw_min_price_edit_flags = array();
        for ($i = 0; $i < count($sessionForm->to_area_lbls); $i++) {
            $outForm->raw_base_prices[] = Sgmov_Component_String::number_format($sessionForm->base_prices[$i]);
            $outForm->raw_max_prices[] = Sgmov_Component_String::number_format($sessionForm->max_prices[$i]);
            $outForm->raw_min_prices[] = Sgmov_Component_String::number_format($sessionForm->min_prices[$i]);

            if ($sessionForm->base_prices[$i] !== $sessionForm->orig_base_prices[$i]) {
                $outForm->raw_base_price_edit_flags[] = '1';
            } else {
                $outForm->raw_base_price_edit_flags[] = '0';
            }

            if ($sessionForm->max_prices[$i] !== $sessionForm->orig_max_prices[$i]) {
                $outForm->raw_max_price_edit_flags[] = '1';
            } else {
                $outForm->raw_max_price_edit_flags[] = '0';
            }

            if ($sessionForm->min_prices[$i] !== $sessionForm->orig_min_prices[$i]) {
                $outForm->raw_min_price_edit_flags[] = '1';
            } else {
                $outForm->raw_min_price_edit_flags[] = '0';
            }
        }
        return $outForm;
    }
}
?>

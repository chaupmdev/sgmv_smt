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
Sgmov_Lib::useView('ptu/Common');
Sgmov_Lib::useForms(array('Error', 'PtuSession', 'Ptu002Out'));
/**#@-*/
/**
 * 旅客手荷物受付サービスのお申し込みのコンビニ決済入力画面を表示します。
 * @package    View
 * @subpackage PPR
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Ptu_ConvenienceStore extends Sgmov_View_Ptu_Common {
    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
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
    public function executeInner() {

        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();

        // DB接続
        $db = Sgmov_Component_DB::getPublic();

        // セッションに入力チェック済みの情報があるかどうかを確認
        $sessionForm = $session->loadForm(self::FEATURE_ID);

        if (!isset($sessionForm) || $sessionForm->status != self::VALIDATION_SUCCEEDED) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
        }

        // セッション情報を元に出力情報を設定
        $outForm = $this->_createOutFormByInForm();

        // チケットを発行
        $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_PTU002);

        return array(
            'ticket'  => $ticket,
            'outForm' => $outForm,
        );
    }
    /**
     * 入力フォームの値を元に出力フォームを生成します。
     * @param Sgmov_Form_Ptu001In $sessionForm 入力フォーム
     * @return Sgmov_Form_Ptu002Out 出力フォーム
     */
    public function _createOutFormByInForm() {

        $outForm = new Sgmov_Form_Ptu002Out();

        // セッション値の適用
        $db = Sgmov_Component_DB::getPublic();

        return $outForm;
    }
}
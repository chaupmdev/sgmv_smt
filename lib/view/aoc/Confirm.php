<?php
/**
 * @package    ClassDefFile
 * @author     T.Aono(SGS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('aoc/Common');
Sgmov_Lib::useServices(array('Login'));
Sgmov_Lib::useForms(array('Error', 'AocSession', 'Aoc003Out'));
/**#@-*/

 /**
 * 他社連携キャンペーン確認画面を表示します。
 * @package    View
 * @subpackage AOC
 * @author     T.Aono(SGS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Aoc_Confirm extends Sgmov_View_Aoc_Common
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
        $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_AOC003);

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

        $outForm = new Sgmov_Form_Aoc003Out();
        $outForm->raw_honsha_user_flag = $this->_loginService->
                                                getHonshaUserFlag();

        $outForm->raw_oc_name        = $sessionForm->oc_name;
        $outForm->raw_oc_flg         = $sessionForm->oc_flg;
        $outForm->raw_oc_content     = $sessionForm->oc_content;
		$outForm->raw_oc_application = $sessionForm->oc_application;
      
       return $outForm;
    }
}
?>

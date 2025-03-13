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
Sgmov_Lib::useServices(array('Login', 'OtherCampaign'));
Sgmov_Lib::useForms(array('Error', 'AocSession', 'Aoc004Out'));
/**#@-*/

 /**
 * 他社連携キャンペーン情報を登録し、完了画面を表示します。
 * @package    View
 * @subpackage AOC
 * @author     T.Aono(SGS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Aoc_Complete extends Sgmov_View_Aoc_Common
{
    /**
     * ログインサービス
     * @var Sgmov_Service_Login
     */
    public $_loginService;

    /**
     * 他社連携キャンペーンサービス
     * @var Sgmov_Service_BasePrice
     */
    public $_OtherCampaignService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        $this->_loginService = new Sgmov_Service_Login();
        $this->_OtherCampaignService = new Sgmov_Service_OtherCampaign();
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
     * セッションから情報を取得
     * </li><li>
     * 情報をDBへ格納
     * </li><li>
     * 同時更新エラーの場合はエラーを設定して入力画面へ遷移
     * </li><li>
     * 出力情報を設定
     * </li><li>
     * セッション情報を破棄
     * </li></ol>
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['outForm']:出力フォーム
     * </li></ul>
     */
    public function executeInner()
    {
        Sgmov_Component_Log::debug('チケットの確認と破棄');
        $session = Sgmov_Component_Session::get();
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_AOC003, $this->_getTicket());

        Sgmov_Component_Log::debug('セッションから情報を取得');
        $sessionForm = $session->loadForm(self::FEATURE_ID);
		
	    Sgmov_Component_Log::debug('ユーザーアカウントを取得');
        $user_account = $session->loadLoginUser()->account;

	    Sgmov_Component_Log::debug('DBを更新');
        $errorForm = $this->_updateOtherCampaigns($user_account, $sessionForm);
		
        if ($errorForm->hasError()) {
            Sgmov_Component_Log::debug('同時更新エラー');
            $sessionForm->error = $errorForm;
            $session->saveForm(self::FEATURE_ID, $sessionForm);
            Sgmov_Component_Redirect::redirectMaintenance('/aoc/input');
        }

        Sgmov_Component_Log::debug('出力情報を設定');
        $outForm = new Sgmov_Form_Aoc004Out();
        $outForm->raw_honsha_user_flag = $this->_loginService->

                                                getHonshaUserFlag();

        Sgmov_Component_Log::debug('セッション情報を破棄');
        $session->deleteForm(self::FEATURE_ID);

        return array('outForm'=>$outForm);
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
     * セッション情報を元に他社連携キャンペーン情報を更新します。
     *
     * 同時更新によって更新に失敗した場合はエラーフォームにメッセージを設定して返します。
     *
     * @param string $user_account ユーザーアカウント
     * @param Sgmov_Form_AocSession $sessionForm セッションフォーム
     * @return Sgmov_Form_Error エラーフォーム
     */
    public function _updateOtherCampaigns($user_account, $sessionForm)
    {
        // DBへ接続
        $db = Sgmov_Component_DB::getAdmin();
		

        // 情報をDBへ格納
        if(isset($sessionForm->oc_id) && (!empty($sessionForm->oc_id) || $sessionForm->oc_id == "0")){
		$ret = $this->_OtherCampaignService->
                    updateOtherCampaign($db, $sessionForm->oc_name,
                                             $sessionForm->oc_flg,
                                             $sessionForm->oc_content,
                                             $sessionForm->oc_application,
					     $sessionForm->oc_id);
	}else{
		$ret = $this->_OtherCampaignService->
                    insertOtherCampaign($db, $sessionForm->oc_name,
                                             $sessionForm->oc_flg,
                                             $sessionForm->oc_content,
                                             $sessionForm->oc_application);
	}
		
        $errorForm = new Sgmov_Form_Error();
        if ($ret === FALSE) {
            $errorForm->addError('top_conflict', '別のユーザーによって更新されています。すべての変更は適用されませんでした。');
        }
        return $errorForm;
    }
}
?>

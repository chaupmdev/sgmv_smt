<?php
/**
 * @package    ClassDefFile
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__).'/../../Lib.php';
Sgmov_Lib::useView('ren/Common');
Sgmov_Lib::useServices(array('Occupation'));
Sgmov_Lib::useForms(array('Error', 'RenSession', 'Ren001Out'));
/**#@-*/

/**
 * イベント手荷物受付サービスのお申し込み確認画面を表示します。
 * @package    View
 * @subpackage EVE
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Ren_Confirm extends Sgmov_View_Ren_Common {
    

    /**
     * 職種サービス
     * @var Sgmov_Service_Occupation
     */
    private $_OccupationService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_OccupationService     = new Sgmov_Service_Occupation();
        
        parent::__construct();
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

        if(@empty($_SERVER["HTTP_REFERER"]) || strpos($_SERVER["HTTP_REFERER"], "/ren/") == false) {
            // セッション情報を破棄
            $session->deleteForm(self::FEATURE_ID);
        }

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
        $resultData = $this->_createOutFormByInForm($sessionForm->in, $db);

        $outForm = $resultData["outForm"];
        $dispItemInfo = $resultData["dispItemInfo"];

        //  チケットを発行
        $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_REN003);
        return array(
            'ticket'  => $ticket,
            'outForm' => $outForm,
            'dispItemInfo' => $dispItemInfo,
        );
    }
    
    /**
     * 入力フォームの値を元に出力フォームを生成します。
     * @param Sgmov_Form_Ren001In $inForm 入力フォーム
     * @return Sgmov_Form_Ren001Out 出力フォーム
     */
    public function _createOutFormByInForm($inForm, $db) {
      
        return $this->createOutFormByInForm($inForm, new Sgmov_Form_Ren001Out());
    }
}
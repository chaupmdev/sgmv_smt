<?php
 /**
 * 01_会員確認。
 * @package    View
 * @subpackage event/InputResetPass
 * @author     GiapLN(FPT Software) 
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';

Sgmov_Lib::useView('event/Common');
Sgmov_Lib::useForms(array('Error', 'UserSession', 'ResetPass001Out'));
Sgmov_Lib::useServices(array('Eventsub'));
/**#@-*/

class Sgmov_View_Input_Reset_Pass extends Sgmov_View_Event_Common
{
    /**
     * イベントサブサービス
     * @var Sgmov_Service_Eventsub
     */
    protected $_EventsubService;
    
    public function __construct() {
        $this->_EventsubService       = new Sgmov_Service_Eventsub();
        parent::__construct();
    }
    
    public function executeInner() {
        $db = Sgmov_Component_DB::getPublic();
    	// GETパラメータ取得
        $inqcase = $this->_parseGetParameter();

        // セッションに情報があるかどうかを確認
        $session = Sgmov_Component_Session::get();
        $this->redirectForUrlInvalid();
        $this->redirectWhenEventInvalid($db, $inqcase);
        
        if (isset($_SESSION[self::LOGIN_ID])) {
            unset($_SESSION[self::LOGIN_ID]);
            $keyLogin  = strtoupper($inqcase).'_LOGIN';
            unset($_SESSION[$keyLogin]);
        }
        $sessionData = $_SESSION[self::FEATURE_ID];
        
        $sessionForm = $session->loadForm(self::RESET_PASS_ID);
        
        if (isset($sessionForm)) {
            // セッション情報を元に出力情報を作成
            $outForm = $this->_createOutFormByInForm($sessionForm->in);
            $errorForm = $sessionForm->error;
            $sessionForm->error = NULL;
        } else {
            // 出力情報を設定
            $outForm = new Sgmov_Form_ResetPass001Out();
            $errorForm = new Sgmov_Form_Error();
        }
        //GiapLN fix bug SMT6-71 18.03.22
        if (isset($_SESSION[self::RESET_PASS_ID]['is_reset_pass'])) {
            $isResetPass = true; 
            unset ($_SESSION[self::RESET_PASS_ID]['is_reset_pass']);
            
        } else {
            $isResetPass = false; 
        }
        // セッション破棄
        $session->deleteForm(self::RESET_PASS_ID);
        
        // テンプレート用の値をセット
        return array('outForm' => $outForm,
                    'errorForm' => $errorForm, 
                    'sessionData' => $sessionData,
                    'isResetPass' => $isResetPass,
                    'siteKey'=> self::SITE_KEY
                );
    }
    /**
     * 入力フォームの値を元に出力フォームを生成します。
     * @param Sgmov_Form_ResetPass001In $inForm 入力フォーム
     * @return Sgmov_Form_ResetPass001Out 出力フォーム
     */
    public function _createOutFormByInForm($inForm)
    {
        $outForm = new Sgmov_Form_ResetPass001Out();
        $outForm->raw_email = $inForm->email;
        return $outForm;
    }


}
?>
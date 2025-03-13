<?php
 /**
 * 11_パスワード変更。
 * @package    View
 * @subpackage event/InputPassChange
 * @author     GiapLN(FPT Software) 
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('event/Common');
Sgmov_Lib::useForms(array('Error', 'UserSession', 'UserPassChange001Out'));
Sgmov_Lib::useServices(array('EventLogin', 'Eventsub'));
/**#@-*/

class Sgmov_View_Event_InputPassChangeo extends Sgmov_View_Event_Common
{
	
    /**
     * イベントサブサービス
     * @var Sgmov_Service_Eventsub
     */
    protected $_EventsubService;
    
    protected $_EventLoginService;
    
    public function __construct() {
        $this->_EventsubService       = new Sgmov_Service_Eventsub();
        $this->_EventLoginService = new Sgmov_Service_EventLogin();
        parent::__construct();
    }
    
    
    public function executeInner() {
    	// GETパラメータ取得
        $inqcase = $this->_parseGetParameter();
        $db = Sgmov_Component_DB::getPublic();
        // セッションに情報があるかどうかを確認
        $session = Sgmov_Component_Session::get();
        $this->redirectForUrlInvalid();
        $this->redirectWhenEventInvalid($db, $inqcase); 
        
        //if (!isset($_SESSION[self::LOGIN_ID])) {
        if (!isset($_SESSION[self::LOGIN_ID]['email'])) {
            Sgmov_Component_Redirect::redirectPublicSsl('/event/login?event_nm='.$inqcase);
        } else {
            //GiapLN fix bug event login 2022/04/15
            $this->checkEventNameInUrl($inqcase);
            
            $email = $_SESSION[self::LOGIN_ID]['email'];
            $eLogins = $this->_EventLoginService->fetchEventLoginByEmail($db, $_SESSION[self::LOGIN_ID]['email']);
        }
        
        $sessionData = $_SESSION[self::FEATURE_ID];
        $sessionForm = $session->loadForm(self::PASS_CHANGE_ID);

        if (isset($sessionForm)) {
            // セッション情報を元に出力情報を作成
            $outForm = $this->_createOutFormByInForm($sessionForm->in);
            $errorForm = $sessionForm->error;
            $sessionForm->error = NULL;

        } else {
            // 出力情報を設定
            $outForm = new Sgmov_Form_UserPassChange001Out();
            $errorForm = new Sgmov_Form_Error();
        }

        // セッション破棄
        $session->deleteForm(self::PASS_CHANGE_ID);

        return array('outForm'=>$outForm,
                        'errorForm'=>$errorForm, 
                        'sessionData' => $sessionData,
                        'email' => $email,
                        'object' => $eLogins
            );
    }
    /**
     * 入力フォームの値を元に出力フォームを生成します。
     * @return Sgmov_Form_UserPassChange001Out 出力フォーム
     */
    public function _createOutFormByInForm($inForm)
    {
        $outForm = new Sgmov_Form_UserPassChange001Out();
        $outForm->raw_password_old = $inForm->password_old;
        $outForm->raw_password = $inForm->password;
        $outForm->raw_password_confirm = $inForm->password_confirm;
        
        return $outForm;
    }

}
?>
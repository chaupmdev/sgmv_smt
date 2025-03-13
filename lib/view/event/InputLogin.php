<?php
/**
* 06_ログイン画面。
* @package    View
* @subpackage event/InputLogin
* @author     GiapLN(FPT Software) 
*/

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('event/Common');
Sgmov_Lib::useForms(array('Error', 'UserSession', 'Login001Out'));
Sgmov_Lib::useServices(array('Eventsub', 'Comiket', 'EventLogin'));
/**#@-*/


class Sgmov_View_Input_Login extends Sgmov_View_Event_Common
{
    protected $_ComiketService;
    
    protected $_EventLoginService;
    
    protected $_EventsubService;
    
    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_ComiketService = new Sgmov_Service_Comiket();
        $this->_EventLoginService = new Sgmov_Service_EventLogin();
        $this->_EventsubService = new Sgmov_Service_Eventsub();
        parent::__construct();
    }
    
    
    public function executeInner()
    {
        // 都道府県を取得
        $db = Sgmov_Component_DB::getPublic();
        
    	// GETパラメータ取得
        $inqcase = $this->_parseGetParameter();

        // セッションに情報があるかどうかを確認
        $session = Sgmov_Component_Session::get();
        $this->redirectForUrlInvalid();
        $this->redirectWhenEventInvalid($db, $inqcase);
            
        if ($_SESSION[self::FEATURE_ID]['security_patten'] == 4) {
            Sgmov_Component_Redirect::redirectPublicSsl("/404.html");
        }
        
        $sessionData = $_SESSION[self::FEATURE_ID];

        $sessionForm = $session->loadForm(self::LOGIN_ID);
        
        if (isset($sessionForm)) {
            // セッション情報を元に出力情報を作成
            $outForm = $this->_createOutFormByInForm($sessionForm->in);
            $errorForm = $sessionForm->error;
            $sessionForm->error = NULL;

        } else {
            // 出力情報を設定
            $outForm = new Sgmov_Form_Login001Out();
            $errorForm = new Sgmov_Form_Error();
        }

        // セッション破棄
        $session->deleteForm(self::LOGIN_ID);

        // チケット発行
        return array('outForm'=>$outForm,
                        'errorForm'=>$errorForm, 
                        'sessionData' => $sessionData, 
                        'siteKey'=> self::SITE_KEY
            );
    }
    
    public function confirmFlagChange() {
        // 都道府県を取得
        $db = Sgmov_Component_DB::getPublic();
        
        if (isset($_SESSION[self::LOGIN_ID]['email'])) {
            $email = $_SESSION[self::LOGIN_ID]['email'];

            $eLogins = $this->_EventLoginService->fetchEventLoginValid($db, $email);
        
            $eventId = $_SESSION[self::FEATURE_ID]['event_id'];
            $eventSubId = $_SESSION[self::FEATURE_ID]['eventsub_id'];

            $eventNm = $_SESSION[self::FEATURE_ID]['event_name'];
            if ($eLogins['password_update_flag'] == '1') {
                if (empty($eLogins['name_sei']) && empty($eLogins['name_mei'])) {
                    //redirect to 「09_会員情報登録・変更」
                    Sgmov_Component_Redirect::redirectPublicSsl('/event/updateInfo?event_nm='.$eventNm);
                } else {
                    //redirect to 「11_パスワード変更」
                    Sgmov_Component_Redirect::redirectPublicSsl('/event/passChange?event_nm='.$eventNm);
                }
            } else {
                if (isset($_SESSION[self::FEATURE_ID]['destination'])) {
                    $destination = $_SESSION[self::FEATURE_ID]['destination'];
                    unset($_SESSION[self::FEATURE_ID]['destination']);
                    //redirect to event screen
                    Sgmov_Component_Redirect::redirectPublicSsl('/'.$eventNm.'/'.$destination);
                } else {
                    //check exists comiket 
                    $eComiket = $this->_ComiketService->fetchComiketData($db, array($email, $eventId, $eventSubId));
                    if (empty($eComiket)) {
                        //redirect to input screen
                        Sgmov_Component_Redirect::redirectPublicSsl('/'.$eventNm.'/input');
                    } else {
                        //redirect to input history
                        Sgmov_Component_Redirect::redirectPublicSsl('/event/inputHistory?event_nm='.$eventNm);
                    }
                }
            }
        }
    }
    
    /**
     * 入力フォームの値を元に出力フォームを生成します。
     * @param Sgmov_Form_Login001In $inForm 入力フォーム
     * @return Sgmov_Form_Login001Out 出力フォーム
     */
    public function _createOutFormByInForm($inForm)
    {
        $outForm = new Sgmov_Form_Login001Out();
        $outForm->raw_email = $inForm->email;
        $outForm->raw_password = $inForm->password;
                
        return $outForm;
    }
    
}
?>
<?php
/**
* 06_ログイン画面。
* @package    View
* @subpackage event/CheckInputLogin
* @author     GiapLN(FPT Software) 
*/

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('event/Common');
Sgmov_Lib::useForms(array('Error', 'UserSession', 'User002In'));
Sgmov_Lib::useServices(array('EventLogin', 'Comiket', 'CenterMail'));
/**#@-*/

class Sgmov_View_Event_CheckInputLogin extends Sgmov_View_Event_Common
{
    protected $_ComiketService;
    
    protected $_EventLoginService;
    
    protected $_CenterMailService;
    
    /**
     * __construct。
     */
    public function __construct() {
        $this->_ComiketService = new Sgmov_Service_Comiket();
        $this->_EventLoginService = new Sgmov_Service_EventLogin();
        $this->_CenterMailService = new Sgmov_Service_CenterMail();
        parent::__construct();
    }
    
    public function executeInner()
    {
        
        $db = Sgmov_Component_DB::getPublic();
        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();
        $eventNm = $_SESSION[self::FEATURE_ID]['event_name'];
        // 入力チェック
        $inForm = $this->_createInFormFromPost($_POST);
        
        $this->redirectWhenEventInvalid($db, $eventNm);

        $errorForm = $this->_validate($inForm);

        // 情報をセッションに保存
        $sessionForm = new Sgmov_Form_UserSession();
        $sessionForm->in = $inForm;
        $sessionForm->error = $errorForm;
        if ($errorForm->hasError()) {
            $sessionForm->status = self::VALIDATION_FAILED;
        } else {
            $sessionForm->status = self::VALIDATION_SUCCEEDED;
        }
        
        $session->saveForm(self::LOGIN_ID, $sessionForm);
        // リダイレクト
        if ($errorForm->hasError()) {
            Sgmov_Component_Redirect::redirectPublicSsl('/event/login?event_nm='.$eventNm);
        } else {
            $eLogins = $this->_EventLoginService->fetchEventLoginByEmail($db, $inForm->email);
            unset($_SESSION[self::LOGIN_ID][$eLogins['mail']]['count_login_fail']);
            $_SESSION[self::LOGIN_ID]['id'] = $eLogins['id'];
            $_SESSION[self::LOGIN_ID]['email'] = $eLogins['mail'];
            $_SESSION[self::LOGIN_ID]['password_update_flag'] = $eLogins['password_update_flag'];
            $_SESSION[self::LOGIN_ID]['user_type'] = 1;
            
            $keyLogin  = strtoupper($eventNm).'_LOGIN';
            $_SESSION[$keyLogin] = $_SESSION[self::LOGIN_ID];
            
            $this->confirmFlagChange($eLogins);
        }
                
    }

    /**
     * POST情報から入力フォームを生成します。
     * @param array $post ポスト情報
     * @return Sgmov_Form_User002In 
     */
    public function _createInFormFromPost($post)
    {
        $inForm = new Sgmov_Form_User002In();
        $inForm->email = mb_convert_kana(filter_input(INPUT_POST, 'email'), 'rnask', 'UTF-8');
        $inForm->password = filter_input(INPUT_POST, 'password');


        return $inForm;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_User002In $inForm 入力フォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($inForm)
    {
        // 都道府県を取得
        $db = Sgmov_Component_DB::getPublic();

        // 入力チェック
        $errorForm = new Sgmov_Form_Error();

        // メールアドレス
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->email)->
                                        isNotEmpty();
        if (!$v->isValid()) {
            $errorForm->addError('top_email','メールアドレス'.$v->getResultMessageTop());
        }

        
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->password)->
                                        isNotEmpty();
        if (!$v->isValid()) {
            $errorForm->addError('top_password', 'パスワード'.$v->getResultMessageTop());
        }
        
        // エラーがない場合はメールアドレスドメイン確認
//        $spamMailDomainList = Sgmov_Component_Config::getSpamMailDomainList();
//        foreach ($spamMailDomainList as $key => $val) {
//            if (!$errorForm->hasError() 
//                    && @strpos($inForm->mail, "@{$val}") !== false) {
//                // メールアドレスに @qq.comが含まれているかどうか
//                $errorForm->addError('top_mail', "は、@{$val}はご利用できません。");
//            }
//        }
        
        $eLogins = $this->_EventLoginService->fetchEventLoginByEmail($db, $inForm->email);
        
        if (!$errorForm->hasError()) {
            if (empty($eLogins)) {
                $errorForm->addError('top_email', 'メールアドレスまたはパスワードが正しくないです。');
            } else {
                if (!empty($eLogins['lock_date'])  && strtotime($eLogins['lock_date']) > strtotime(date('Y-m-d H:i:s'))) {
                    Sgmov_Component_Redirect::redirectPublicSsl('/event/accountLock?event_nm='.$_SESSION[self::FEATURE_ID]['event_name']);
                }
            }
        }
        
        if (!$errorForm->hasError()) {
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->email)->
                                            isLengthLessThanOrEqualTo(100)->
                                            isMail();
            if (!$v->isValid()) {
                $errorForm->addError('top_email', 'メールアドレスまたはパスワードが正しくないです。');
                if (isset($_SESSION[self::LOGIN_ID][$eLogins['mail']]['count_login_fail'])) {
                    $_SESSION[self::LOGIN_ID][$eLogins['mail']]['count_login_fail'] = $_SESSION[self::LOGIN_ID][$eLogins['mail']]['count_login_fail'] + 1;
                } else {
                    $_SESSION[self::LOGIN_ID][$eLogins['mail']]['count_login_fail'] = 1;
                }
            }
        }
        
        if (!$errorForm->hasError()) {
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->password)->isLengthLessThanOrEqualTo(50);
            if (!$v->isValid()) {
                $errorForm->addError('top_password', "メールアドレスまたはパスワードが正しくないです。");
                if (isset($_SESSION[self::LOGIN_ID][$eLogins['mail']]['count_login_fail'])) {
                    $_SESSION[self::LOGIN_ID][$eLogins['mail']]['count_login_fail'] = $_SESSION[self::LOGIN_ID][$eLogins['mail']]['count_login_fail'] + 1;
                } else {
                    $_SESSION[self::LOGIN_ID][$eLogins['mail']]['count_login_fail'] = 1;
                }
            }
        }
        
        if (!$errorForm->hasError()) {
            $password = $inForm->password;
            $errorPassStr = $this->checkpas($password);
            //GiapLN fix bug SMT6-112 2022/03/26
            //$pass = crypt($password, md5($inForm->email));
            $pass = md5($inForm->email.$password);
            
            if ($eLogins['password'] != $pass) {
                $errorForm->addError('top_email', 'メールアドレスまたはパスワードが正しくないです。');
                if (isset($_SESSION[self::LOGIN_ID][$eLogins['mail']]['count_login_fail'])) {
                    $_SESSION[self::LOGIN_ID][$eLogins['mail']]['count_login_fail'] = $_SESSION[self::LOGIN_ID][$eLogins['mail']]['count_login_fail'] + 1;
                } else {
                    $_SESSION[self::LOGIN_ID][$eLogins['mail']]['count_login_fail'] = 1;
                }
            }
        }
        
        if ($errorForm->hasError()) {
            if (!empty($eLogins)) {
                if (isset($_SESSION[self::LOGIN_ID][$eLogins['mail']]['count_login_fail'])) {
                    if ($_SESSION[self::LOGIN_ID][$eLogins['mail']]['count_login_fail'] >= 5) {
                        unset($_SESSION[self::LOGIN_ID][$eLogins['mail']]['count_login_fail']);
                        $this->_EventLoginService->procLockDate($db, $eLogins['id']);
                        Sgmov_Component_Redirect::redirectPublicSsl('/event/accountLock?event_nm='.$_SESSION[self::FEATURE_ID]['event_name']);
                    }
                }
            }
            
        } else {
            
        }

        return $errorForm;
    }
    
    public function confirmFlagChange($eLogins) {
        // 都道府県を取得
        $db = Sgmov_Component_DB::getPublic();
       
        $email = $_SESSION[self::LOGIN_ID]['email'];
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
?>

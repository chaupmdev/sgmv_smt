<?php
/**
 * 04_会員登録画面。
 * @package    View
 * @subpackage event/InputRegister
 * @author     GiapLN(FPT Software) 
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('event/Common');
Sgmov_Lib::useForms(array('Error', 'UserSession', 'User001In'));
Sgmov_Lib::useServices(array('EventLogin', 'CenterMail'));
/**#@-*/

class Sgmov_View_Event_CheckInputRegister extends Sgmov_View_Event_Common
{
    /**
     * イベントサブサービス
     * @var Sgmov_Service_Eventsub
     */
    protected $_EventLoginService;
    
    protected $_CenterMailService;


    public function __construct() {
        $this->_EventLoginService       = new Sgmov_Service_EventLogin();
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
        $this->redirectWhenEventInvalid($db, $eventNm);
        // 入力チェック
        $inForm = $this->_createInFormFromPost($_POST);
        
        $errorForm = $this->_validate($inForm);

        $sessionForm = new Sgmov_Form_UserSession();
        $sessionForm->in = $inForm;
        $sessionForm->error = $errorForm;
        if ($errorForm->hasError()) {
            $sessionForm->status = self::VALIDATION_FAILED;
        } else {
            $sessionForm->status = self::VALIDATION_SUCCEEDED;
        }
        
        $session->saveForm(self::REGISTER_ID, $sessionForm);
        
        // リダイレクト
        if ($errorForm->hasError()) {
            Sgmov_Component_Redirect::redirectPublicSsl('/event/register?event_nm='.$eventNm);
        } else {
            //check exists event_login
            $insertDate = date('Y-m-d H:i:s');
            $password = $this->generateTempPass();
            //GiapLN fix bug SMT6-112 2022/03/26
            //$pass = crypt($password, md5($inForm->email));
            $pass = md5($inForm->email.$password);
            //Sgmov_Component_Log::debug('Password debug:'.$password);
            $dataRow = array(
                'mail'                 => $inForm->email, 
                'password'             => $pass,
               // 'address'               => $password,
                'login_yuko_flag'      => 1, //0:無効; 1:有効
                'password_update_flag' => 1, //0:不要; 1:必要
                'created'              => $insertDate,
                'modified'             => $insertDate,
                'update_no'            => 1
            );
            $this->_EventLoginService->insert($db, $dataRow);
            //send mail 
            $sendTo = $inForm->email;
            $urlPublicSsl = Sgmov_Component_Config::getUrlPublicSsl();
            $urlLogin = $urlPublicSsl . "/event/login?event_nm=".$eventNm;
            $emailText = $inForm->email;
            $emailText .=" ";
            $data = array(
                'email'     => $emailText,
                'password'  => $password,
                'urlLogin'  => $urlLogin
            );
            $mailTemplate[] = "/user_event_login_first.txt";
            $this->_CenterMailService->_sendThankYouMail($mailTemplate, $sendTo, $data);
            unset($objMail);	
            //GiapLN fix bug SMT6-111 2022/03/26
            // セッション破棄
            $session->deleteForm(self::REGISTER_ID);
            
            //redirect to 02_会員登録完了画面 
            $_SESSION[self::FEATURE_ID]['registed'] = true;
            Sgmov_Component_Redirect::redirectPublicSsl('/event/registed?event_nm='.$eventNm);
            
        }
                
    }

    /**
     * POST情報から入力フォームを生成します。
     * @param array $post ポスト情報
     * @return Sgmov_Form_User001In 
     */
    public function _createInFormFromPost($post)
    {
        $inForm = new Sgmov_Form_User001In();
        $inForm->email = mb_convert_kana(filter_input(INPUT_POST, 'email'), 'rnask', 'UTF-8');
        $inForm->email_confirm = mb_convert_kana(filter_input(INPUT_POST, 'email_confirm'), 'rnask', 'UTF-8');
        

        return $inForm;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_User001In $inForm 入力フォーム
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
                                        isNotEmpty()->
                                        isLengthLessThanOrEqualTo(80)->
                                        isMail();
        if (!$v->isValid()) {
            $errorForm->addError('top_email', 'メールアドレス'.$v->getResultMessageTop());
        }



        // メールアドレス確認
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->email_confirm)->
                                        isNotEmpty()->
                                        isLengthLessThanOrEqualTo(80)->
                                        isMail();
        if (!$v->isValid()) {
            $errorForm->addError('top_email_confirm', 'メールアドレス確認'.$v->getResultMessageTop());
        }
        
       
        if (!$errorForm->hasError()) {
            if ($inForm->email_confirm != $inForm->email) {
                $errorForm->addError('top_email_confirm', 'メールアドレス確認の入力内容をお確かめください。');
            }
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
        if (!$errorForm->hasError()) {
            //$service = new Sgmov_Service_EventLogin();
            $eLogins = $this->_EventLoginService->fetchEventLoginByEmail($db, $inForm->email);
            if (!empty($eLogins)) {
                $errorForm->addError('top_email', 'メールアドレスが既に登録されています。');
            }
        }
        return $errorForm;
    }
}
?>

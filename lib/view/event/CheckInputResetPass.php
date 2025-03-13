<?php
 /**
 * 07_会員情報忘れ。
 * @package    View
 * @subpackage event/CheckInputResetPass
 * @author     GiapLN(FPT Software) 
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('event/Common');
Sgmov_Lib::useForms(array('Error', 'UserSession', 'ResetPass001In'));
Sgmov_Lib::useServices(array('EventLogin', 'CenterMail'));
/**#@-*/

class Sgmov_View_Event_CheckInputResetPass extends Sgmov_View_Event_Common
{
    
    protected $_EventLogin;
    protected $_CenterMail;
    
    public function __construct() {
        $this->_EventLogin       = new Sgmov_Service_EventLogin();
        $this->_CenterMail       = new Sgmov_Service_CenterMail();
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
        
        // 情報をセッションに保存
        $sessionForm = new Sgmov_Form_UserSession();
        $sessionForm->in = $inForm;
        $sessionForm->error = $errorForm;
        if ($errorForm->hasError()) {
            $sessionForm->status = self::VALIDATION_FAILED;
        } else {
            $sessionForm->status = self::VALIDATION_SUCCEEDED;
        }
        $session->saveForm(self::RESET_PASS_ID, $sessionForm);
        
        // リダイレクト
        if ($errorForm->hasError()) {
            Sgmov_Component_Redirect::redirectPublicSsl('/event/passReset?event_nm='.$eventNm);
        } else {
            //Update event_login
            $insertDate = date('Y-m-d H:i:s');
  
            $password = $this->generateTempPass();
            //GiapLN fix bug SMT6-112 2022/03/26
            //$pass = crypt($password, md5($inForm->email));
            $pass = md5($inForm->email.$password);
            //$passwordMd5 = md5($inForm->email.$password);
            $dataRow = array(
                'mail'                 => $inForm->email, 
                'password'             => $pass,
                'login_yuko_flag'      => 1, //0:無効; 1:有効
                'password_update_flag' => 1, //0:不要; 1:必要
                'modified'             => $insertDate
            );
            $this->_EventLogin->updateResetPass($db, $dataRow);
            //send mail 
            $eLogin = $this->_EventLogin->fetchEventLoginByEmail($db, $inForm->email);
            $sendTo = $inForm->email;
            
            $mailText = '';
            if (!empty($eLogin['name_sei']) && !empty($eLogin['name_mei'])) {
                $mailText = $eLogin['name_sei'].$eLogin['name_mei']." ";
            } else {
                $mailText = $inForm->email;
                $mailText .= " ";
            }
            
            $urlPublicSsl = Sgmov_Component_Config::getUrlPublicSsl();
            $urlLogin = $urlPublicSsl . "/event/login?event_nm=".$eventNm;

            $data = array(
                'email'     => $mailText,
                'password'  => $password,
                'urlLogin'  => $urlLogin
            );
            $mailTemplate[] = "/user_event_reset_pass.txt";
            $this->_CenterMail->_sendThankYouMail($mailTemplate, $sendTo, $data);
            //unset($objMail);	
            //GiapLN fix bug SMT6-71 18.03.22
            $_SESSION[self::RESET_PASS_ID]['is_reset_pass'] = true;
            //show message メールアドレスに仮パスワードを送信しました。
            Sgmov_Component_Redirect::redirectPublicSsl('/event/passReset?event_nm='.$eventNm);
            
        }
                
    }

    /**
     * POST情報から入力フォームを生成します。
     * @param array $post ポスト情報
     * @return Sgmov_Form_ResetPass001In 
     */
    public function _createInFormFromPost($post)
    {
        $inForm = new Sgmov_Form_ResetPass001In();
        $inForm->email = mb_convert_kana(filter_input(INPUT_POST, 'email'), 'rnask', 'UTF-8');

        return $inForm;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_ResetPass001In $inForm 入力フォーム
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
            $errorForm->addError('top_email','メールアドレス'. $v->getResultMessageTop());
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
            $eLogins = $this->_EventLogin->fetchEventLoginByEmail($db, $inForm->email);
            if (empty($eLogins)) {
                $errorForm->addError('top_email', 'メールアドレスがまだ登録していません。');
            }
        }
        return $errorForm;
    }
}
?>

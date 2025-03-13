<?php
 /**
 * 11_パスワード変更。
 * @package    View
 * @subpackage event/CheckInputPassChange
 * @author     GiapLN(FPT Software) 
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('event/Common');
Sgmov_Lib::useForms(array('Error', 'UserSession', 'UserPassChange001In'));
Sgmov_Lib::useServices(array('EventLogin','Comiket','CenterMail'));
/**#@-*/

class Sgmov_View_Event_CheckInputPassChange extends Sgmov_View_Event_Common
{
    protected $_ComiketService;
    
    protected $_EventLoginService;
    
    public function __construct() {
        $this->_ComiketService       = new Sgmov_Service_Comiket();
        $this->_EventLoginService = new Sgmov_Service_EventLogin();
        parent::__construct();
    }
    
    public function executeInner()
    {
        $db = Sgmov_Component_DB::getPublic();
        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();
        
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
        
        $session->saveForm(self::PASS_CHANGE_ID, $sessionForm);
        $eventNm = $_SESSION[self::FEATURE_ID]['event_name'];
        // リダイレクト
        if ($errorForm->hasError()) {
            Sgmov_Component_Redirect::redirectPublicSsl('/event/passChange?event_nm='.$eventNm);
        } else {
            //check exists event_login
            //Update event_login
            $insertDate = date('Y-m-d H:i:s');
            $email = $_SESSION[self::LOGIN_ID]['email'];
            $eventId = $_SESSION[self::FEATURE_ID]['event_id'];
            $eventSubId = $_SESSION[self::FEATURE_ID]['eventsub_id'];

            $password =  $inForm->password;
            //GiapLN fix bug SMT6-112 2022/03/26
            //$pass = crypt($password, md5($email));
            $pass = md5($email.$password);
            $dataRow = array(
                'password' => $pass,
                'password_update_flag' => 0, 
                'modified'             => $insertDate,
                'id'                    => $_SESSION[self::LOGIN_ID]['id']
            );

            
            $this->_EventLoginService->updatePassChange($db, $dataRow);
            $_SESSION[self::LOGIN_ID]['password_update_flag'] = 0;
            
            $keyLogin  = strtoupper($eventNm).'_LOGIN';
            $_SESSION[$keyLogin] = $_SESSION[self::LOGIN_ID];
            //GiapLN fix bug SMT6-111 2022/03/26
            // セッション破棄
            $session->deleteForm(self::PASS_CHANGE_ID);
            
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


    /**
     * POST情報から入力フォームを生成します。
     * @param array $post ポスト情報
     * @return Sgmov_Form_UserPassChange001In 
     */
    public function _createInFormFromPost($post)
    {
        $inForm = new Sgmov_Form_UserPassChange001In();
        
        $inForm->password_old = filter_input(INPUT_POST, 'password_old');
        $inForm->password = filter_input(INPUT_POST, 'password');
        $inForm->password_confirm = filter_input(INPUT_POST, 'password_confirm');
        
        return $inForm;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_UserPassChange001In $inForm 入力フォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($inForm)
    {
        // 都道府県を取得
        $db = Sgmov_Component_DB::getPublic();

        // 入力チェック
        $errorForm = new Sgmov_Form_Error();
        
        // 都道府県
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->password_old)->
                                        isNotEmpty()->
                                        isLengthLessThanOrEqualTo(50);
        if (!$v->isValid()) {
            $errorForm->addError('top_password_old', '現パスワード'.$v->getResultMessageTop());
        }
        
        // 都道府県
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->password)->
                                        isNotEmpty()->
                                        isLengthLessThanOrEqualTo(50);
        if (!$v->isValid()) {
            $errorForm->addError('top_password','新パスワード'.$v->getResultMessageTop());
        }
       
        // 都道府県
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->password_confirm)->
                                        isNotEmpty()->
                                        isLengthLessThanOrEqualTo(50);
        if (!$v->isValid()) {
            $errorForm->addError('top_password_confirm', 'パスワードの確認入力'.$v->getResultMessageTop());
        }
        
        if (!$errorForm->hasError()) {
            //check exists password 
            //$service = new Sgmov_Service_EventLogin();
            $email = $_SESSION[self::LOGIN_ID]['email'];
            $eLogin = $this->_EventLoginService->fetchEventLoginByEmail($db, $email);
            
            $password = $inForm->password_old;
//            $errorPassStr = $this->checkpas($password);
//            if (!empty($errorPassStr)) {
//                $errorForm->addError('top_password_old', $errorPassStr);
//            } else {
                //GiapLN fix bug SMT6-112 2022/03/26
                //$pass = crypt($password, md5($email));
                $pass = md5($email.$password);
                if ($eLogin['password'] != $pass) {
                    $errorForm->addError('top_password_old', '現パスワードをお確かめください。');
                }
            //}
        }
        
        if (!$errorForm->hasError()) {
            if ($inForm->password != $inForm->password_confirm) {
                $errorForm->addError('top_password', 'パスワードの確認入力をお確かめください。');
            } else {
                $password = $inForm->password;
                $errorPassStr = $this->checkpas($password);
                if (!empty($errorPassStr)) {
                    $errorForm->addError('top_password', '新パスワード'.$errorPassStr);
                }
            }
        }
        
        if (!$errorForm->hasError()) {
            if ($inForm->password === $inForm->password_old) {
                $errorForm->addError('top_password', '新パスワードは旧パスワードと違って入力してください。');
            }
        }
        return $errorForm;
    }
}
?>

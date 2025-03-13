<?php
 /**
 * 03_会員登録せずロボットチェック。
 * @package    View
 * @subpackage event/CheckInputRobot
 * @author     GiapLN(FPT Software) 
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('event/Common');
Sgmov_Lib::useForms(array('Error', 'UserSession', 'UserRobotCheck001In'));
Sgmov_Lib::useServices(array('EventLogin', 'CenterMail', 'Comiket'));

class Sgmov_View_Event_CheckInputRobot extends Sgmov_View_Event_Common
{
    protected $_EventLogin;
    protected $_CenterMail;
    
    protected $_ComiketService;
    
    public function __construct() {
        $this->_EventLogin       = new Sgmov_Service_EventLogin();
        $this->_CenterMail       = new Sgmov_Service_CenterMail();
        $this->_ComiketService = new Sgmov_Service_Comiket();
        parent::__construct();
    }
    
    public function executeInner() {
        $db = Sgmov_Component_DB::getPublic();
        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();
        $eventNm = $_SESSION[self::FEATURE_ID]['event_name'];
        $this->redirectWhenEventInvalid($db, $eventNm);
        // 入力チェック
        if (!empty($_GET['destination'])) {
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
            $session->saveForm(self::ROBOT_CHECK_ID, $sessionForm);
            if ($errorForm->hasError()) {
                Sgmov_Component_Redirect::redirectPublicSsl('/event/robotCheck?event_nm='.$eventNm.'&destination='.$_GET['destination']);
            } else {
                //GiapLN fix bug SMT6-111 2022/03/26
                // セッション破棄
                $session->deleteForm(self::ROBOT_CHECK_ID);
                
                $_SESSION[self::LOGIN_ID]['user_type'] = 0;
                $keyLogin  = strtoupper($eventNm).'_LOGIN';
                $_SESSION[$keyLogin] = $_SESSION[self::LOGIN_ID];
                Sgmov_Component_Redirect::redirectPublicSsl('/'.$eventNm.'/'.$_GET['destination']);
            }
        } else {
            $_SESSION[self::LOGIN_ID]['user_type'] = 0;
            $keyLogin  = strtoupper($eventNm).'_LOGIN';
            $_SESSION[$keyLogin] = $_SESSION[self::LOGIN_ID];
            Sgmov_Component_Redirect::redirectPublicSsl('/'.$eventNm.'/input');
        }    
    }
    
     /**
     * POST情報から入力フォームを生成します。
     * @param array $post ポスト情報
     * @return Sgmov_Form_UserRobotCheck001In 
     */
    public function _createInFormFromPost($post)
    {
        $inForm = new Sgmov_Form_UserRobotCheck001In();
        if (isset($post['email'])) {
            $inForm->email = $post['email'];
        } else {
            $inForm->email = '';
        }

        return $inForm;
    }
    
    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_UserRobotCheck001In $inForm 入力フォーム
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
            $errorForm->addError('top_email', $v->getResultMessageTop());
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
            $destination = $_GET['destination'];
            $arrDes = explode("/",  $destination);
            if (!isset($arrDes[1])) {
                $errorForm->addError('top_email', "メールアドレスや申込番号が間違いました。");
            } else {
                //GiapLN fix bug 2022/03/25 transfer by TuanLK 
                if (strlen($arrDes[1]) > 13 || strlen($arrDes[1]) < 10) {
                     $errorForm->addError('top_email', "メールアドレスや申込番号が間違いました。");
                } else {
                    $comiketId = substr($arrDes[1], 0, 10);
                    $comiketId = intval($comiketId);
                    $email = $inForm->email;
                    //$eventId = $_SESSION[self::FEATURE_ID]['event_id'];
                    $eventSubId = $_SESSION[self::FEATURE_ID]['eventsub_id'];
                    $result = $this->_ComiketService->checkComiketByEvent($db, $comiketId, $email, $eventSubId);
                    if (empty($result)) {
                        $errorForm->addError('top_email', "メールアドレスや申込番号が間違いました。");
                    }
                }
            }
        }
        
        return $errorForm;
    }
    
    
}
?>

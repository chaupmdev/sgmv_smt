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

Sgmov_Lib::useForms(array('Error', 'UserSession', 'User001Out'));
Sgmov_Lib::useServices(array('Eventsub'));

/**#@-*/

class Sgmov_View_Event_InputRegister extends Sgmov_View_Event_Common
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
        $sessionForm = $session->loadForm(self::REGISTER_ID);
        
        if (isset($sessionForm)) {
            // セッション情報を元に出力情報を作成
            $outForm = $this->_createOutFormByInForm($sessionForm->in);
            $errorForm = $sessionForm->error;
            $sessionForm->error = NULL;

        } else {
            // 出力情報を設定
            $outForm = new Sgmov_Form_User001Out();
            $errorForm = new Sgmov_Form_Error();
        }

        // セッション破棄
        $session->deleteForm(self::REGISTER_ID);

        return array('outForm'=>$outForm,
                        'errorForm'=>$errorForm, 
                        'sessionData' => $sessionData,
                        'siteKey'=> self::SITE_KEY
            );
    }
    /**
     * 入力フォームの値を元に出力フォームを生成します。
     * @param Sgmov_Form_User001In $inForm 入力フォーム
     * @return Sgmov_Form_User001Out 出力フォーム
     */
    public function _createOutFormByInForm($inForm)
    {
        $outForm = new Sgmov_Form_User001Out();
        $outForm->raw_email = $inForm->email;
        $outForm->raw_email_confirm = $inForm->email_confirm;
        return $outForm;
    }

}
?>
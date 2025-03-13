<?php
 /**
 * 01_会員確認。
 * @package    View
 * @subpackage event/UserSelect
 * @author     GiapLN(FPT Software) 
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';

Sgmov_Lib::useView('event/Common');
Sgmov_Lib::useServices(array('Eventsub'));

/**#@-*/



class Sgmov_View_Event_User_Select extends Sgmov_View_Event_Common
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
    public function executeInner()
    {
        $db = Sgmov_Component_DB::getPublic();
    	// GETパラメータ取得
        $inqcase = $this->_parseGetParameter();

        // セッションに情報があるかどうかを確認
        $session = Sgmov_Component_Session::get();
        unset($_SESSION[self::FEATURE_ID]);
        unset($_SESSION[self::REGISTER_ID]);//REGISTER_ID
        unset($_SESSION[self::LOGIN_ID]);//LOGIN_ID
        unset($_SESSION[self::RESET_PASS_ID]);//RESET_PASS_ID
        unset($_SESSION[self::UPDATE_INFO_ID]);//UPDATE_INFO_ID
        unset($_SESSION[self::PASS_CHANGE_ID]);//PASS_CHANGE_ID
        
        $this->redirectForUrlInvalid();
        $this->redirectWhenEventInvalid($db, $inqcase); 
        if ($_SESSION[self::FEATURE_ID]['security_patten'] == 4) {
            Sgmov_Component_Redirect::redirectPublicSsl("/event/robotCheck?event_nm=".$inqcase);
        }
        $sessionData = $_SESSION[self::FEATURE_ID];
        $baseUrl = Sgmov_Component_Config::getUrlPublicSsl();
        // チケット発行
        return array('sessionData' => $sessionData, 'baseUrl' => $baseUrl);
    }
}
?>
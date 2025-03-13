<?php
 /**
 * 10_アカウントロック。
 * @package    View
 * @subpackage event/AccountLock
 * @author     GiapLN(FPT Software) 
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('event/Common');
Sgmov_Lib::useForms(array('Error', 'UserSession', 'User001Out'));
/**#@-*/

class Sgmov_View_Event_AccountLock extends Sgmov_View_Event_Common
{
    public function __construct() {
        parent::__construct();
    }
    
    
    public function executeInner()
    {
        $db = Sgmov_Component_DB::getPublic();
    	// GETパラメータ取得
        $inqcase = $this->_parseGetParameter();

        // セッションに情報があるかどうかを確認
        $session = Sgmov_Component_Session::get();
        
        
        $this->redirectForUrlInvalid();
        
        $this->redirectWhenEventInvalid($db, $inqcase); 
        $sessionData = $_SESSION[self::FEATURE_ID];
        if (isset($_SESSION[self::LOGIN_ID])) {
            unset($_SESSION[self::LOGIN_ID]);
        }
        return array('sessionData' => $sessionData);
    }
    

}
?>
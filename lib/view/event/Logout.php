<?php
 /**
 * 。
 * @package    View
 * @subpackage event/Logout
 * @author     GiapLN(FPT Software) 
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('event/Common');
/**#@-*/

class Sgmov_View_Event_Logout extends Sgmov_View_Event_Common
{
    public function __construct() {
        parent::__construct();
    }
    
    
    public function executeInner()
    {
       //EVENTのSESSIONを解像
       if (isset($_SESSION[self::LOGIN_ID])) {
           unset($_SESSION[self::LOGIN_ID]);
       }
       $eventNm = $_SESSION[self::FEATURE_ID]['event_name'];
       //各イベントのSESSIONキー
       $keyEventLogin  = strtoupper($eventNm).'_LOGIN';
       //EVENTのSESSIONを解像
       if (isset($_SESSION[$keyEventLogin])) {
           unset($_SESSION[$keyEventLogin]);
       }
       Sgmov_Component_Redirect::redirectPublicSsl('/event/login?event_nm='.$eventNm);
    }

}
?>
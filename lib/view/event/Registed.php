<?php
 /**
 * 04_会員登録。
 * @package    View
 * @subpackage event/Registed
 * @author     GiapLN(FPT Software) 
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('event/Common');
Sgmov_Lib::useForms(array('Error', 'UserSession', 'User001Out'));

class Sgmov_View_Event_Registed extends Sgmov_View_Event_Common
{
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
          
        $sessionData = $_SESSION[self::FEATURE_ID];
        if (isset($_SESSION[self::FEATURE_ID]['registed']) && $_SESSION[self::FEATURE_ID]['registed'] === true) {
            unset($_SESSION[self::FEATURE_ID]['registed']);
            return array('sessionData' => $sessionData);
        } else {
            Sgmov_Component_Redirect::redirectPublicSsl('/event/userSelect?event_nm=dsn');
        }
        
    }

}
?>
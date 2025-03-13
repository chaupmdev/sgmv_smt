<?php
 /**
 * 12_申し込み履歴_スマホ。
 * @package    View
 * @subpackage event/MInputHistory
 * @author     GiapLN(FPT Software) 
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('event/Common');
Sgmov_Lib::useServices(array('EventLogin', 'Eventsub', 'Comiket', 'Event', 'ComiketDetail', 'OutBoundCollectCal'));
/**#@-*/

class Sgmov_View_M_Input_History extends Sgmov_View_Event_Common
{
	
    /**
     * イベントサブサービス
     * @var Sgmov_Service_Eventsub
     */
    protected $_EventsubService;
    
    protected $_EventService;
    
    protected $_EventLoginService;
    
    protected $_ComiketService;
    
    protected $_ComiketDetailService;


    /**
     * 往路・出荷日範囲計算マスタサービス
     * @var Sgmov_Service_OutBoundCollectCal
     */
    protected $_OutBoundCollectCal;
    
     
    public function __construct() {
        $this->_EventsubService       = new Sgmov_Service_Eventsub();
        $this->_EventService = new Sgmov_Service_Event();
        $this->_EventLoginService = new Sgmov_Service_EventLogin();
        $this->_ComiketService = new Sgmov_Service_Comiket();
        $this->_ComiketDetailService  = new Sgmov_Service_ComiketDetail();
        $this->_OutBoundCollectCal = new Sgmov_Service_OutBoundCollectCal();
        
        parent::__construct();
    }
    
    
    public function executeInner() {
    	// GETパラメータ取得
        $inqcase = $this->_parseGetParameter();
        
        $db = Sgmov_Component_DB::getPublic();
        // セッションに情報があるかどうかを確認
        $session = Sgmov_Component_Session::get();
        $this->redirectForUrlInvalid();
        
        //if (!isset($_SESSION[self::LOGIN_ID])) {
        if (!isset($_SESSION[self::LOGIN_ID]['email'])) {
            Sgmov_Component_Redirect::redirectPublicSsl('/event/login?event_nm='.$inqcase);
        } else {
            $this->redirectWhenEventInvalid($db, $inqcase);
            //GiapLN fix bug event login 2022/04/15
            $this->checkEventNameInUrl($inqcase);
            
            $email = $_SESSION[self::LOGIN_ID]['email'];
            $eventId = $_SESSION[self::FEATURE_ID]['event_id'];
            $eventSubId = $_SESSION[self::FEATURE_ID]['eventsub_id'];
            
            $eventSub = $this->_EventsubService->getEventId($db, $eventSubId);
            //$fullEventNm = ($inqcase === 'eve' || $inqcase === 'evp') ? $eventSub['eventname']. $eventSub['eventsubname'] : ($eventSub['eventname'] ."　". $eventSub['eventsubname']);
            $fullEventNm = $eventSub['eventname'] ."　". $eventSub['eventsubname'];

            $eLogin = $this->_EventLoginService->fetchEventLoginByEmail($db, $email);
            $fullNm = $eLogin['name_sei'].$eLogin['name_mei'].' 様';
            
            $comiketHistory = $this->_ComiketService->fetchComiketUserHistory($db, $email, $eventId, $eventSubId);
        }
        $sessionData = $_SESSION[self::FEATURE_ID];
        $countTotal = 0;
        $countDel = 0;
        if (!empty($comiketHistory)) {
            foreach ($comiketHistory as $key => $value) {
                $comiketId = $value['id'];
                //2022.07.19 - GiapLN fix bug disable/enable button 変更, キャンセル
                $isEnable = $this->checkReqDate($db, $comiketId);
                //GiapLN - End
                
                $comiketId = str_pad($comiketId,10,"0",STR_PAD_LEFT);
                $kd = self::getChkD($comiketId);
                $kd2 = self::getChkD2($comiketId);
                $comiketId2 = $comiketId.$kd2;
                $comiketId .= $kd;
                $comiketHistory[$key]['comiket_id_str'] = $comiketId;
                $comiketHistory[$key]['comiket_id_str_2'] = $comiketId2;
                $comiketHistory[$key]['isEnable'] = $isEnable;
                $countTotal++;
                if ($value['del_flg'] == 2) {
                    $countDel++;
                }
            }
        }
        $baseUrl = Sgmov_Component_Config::getUrlPublicSsl();
        
        return array('comiketHistory'=> $comiketHistory,
                    'sessionData' => $sessionData, 
                    'fullEventNm' => $fullEventNm,
                    'fullNm' => $fullNm,
                    'baseUrl' => $baseUrl,
                    'countTotal' => $countTotal, 
                    'countDel' => $countDel
            );
    }

}
?>
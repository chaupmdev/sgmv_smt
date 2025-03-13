<?php
/**
 * @package    ClassDefFile
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useAllComponents();
Sgmov_Lib::useView('Public');
Sgmov_Lib::useServices(array('Building'));
/**#@-*/

/**
 * イベントIDからブース情報を検索して返します。
 * @package    View
 * @subpackage EVB
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Evb_SearchBuilding extends Sgmov_View_Public {

    /**
     * 館マスタサービス(ブース番号)
     * @var type 
     */
    private $_BuildingService;
    
    /**
     * ツアーサービス
     * @var Sgmov_Service_Travel
     */
//    public $_TravelService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_BuildingService = new Sgmov_Service_Building();
//        $this->_TravelService = new Sgmov_Service_Travel();
    }

    /**
     * 処理を実行します。
     *
     */
    public function executeInner() {
        
Sgmov_Component_Log::debug("######################## 301 booth");
        
        $featureId            = filter_input(INPUT_POST, 'featureId');
        $fromGamenId          = filter_input(INPUT_POST, 'id');
        $ticket               = filter_input(INPUT_POST, 'ticket');
//        $eventId = filter_input(INPUT_POST, 'event_sel');
        $eventsubId = filter_input(INPUT_POST, 'eventsub_sel');
        
Sgmov_Component_Log::debug("######################## 301 booth2 = " .$eventsubId );        
        // チケット確認
        $this->_checkSession($featureId, $fromGamenId, $ticket);
        
        $returnList = array(
            'ids' => array(),
            'names' => array(),
        );
Sgmov_Component_Log::debug("######################## 302 booth");
        if(empty($eventsubId)) {
            return $returnList;
        }
Sgmov_Component_Log::debug("######################## 303 booth");
        try {
            // DB接続
            $db = Sgmov_Component_DB::getPublic();
            $returnList = $this->_BuildingService->fetchBuildingNameByEventsubId($db, $eventsubId);
Sgmov_Component_Log::debug("######################## 304 booth");
        }
        catch (exception $e) {
        }
Sgmov_Component_Log::debug("######################## 305 booth");
Sgmov_Component_Log::debug($returnList);
        return $returnList;
    }

    /**
     * チケットの確認を行います。
     * TODO ybn/SearchAddressと同記述あり
     */
    public function _checkSession($featureId, $fromGamenId, $ticket) {
        // セッション
        $session = Sgmov_Component_Session::get();

        // チケットの確認
        if (!isset($_SESSION[Sgmov_Component_Session::_KEY_TICKETS])) {
            Sgmov_Component_Log::warning('【ツアー会社検索 不正使用】チケットが存在していません。');
            header('HTTP/1.0 404 Not Found');
            exit;
        }

        $tickets = &$_SESSION[Sgmov_Component_Session::_KEY_TICKETS];
        if (!isset($tickets[$featureId]) || $tickets[$featureId] !== $fromGamenId . $ticket) {
            Sgmov_Component_Log::warning('【ツアー会社検索 不正使用】チケットが不正です。　'.$tickets[$featureId].' <=> '.$fromGamenId . $ticket);
            header('HTTP/1.0 404 Not Found');
            exit;
        } else {
            Sgmov_Component_Log::debug('ツアー会社検索実行 機能ID=>'.$tickets[$featureId].' <=> '.$fromGamenId . $ticket);
        }
    }
}
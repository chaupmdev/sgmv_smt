<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TCP)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useAllComponents();
Sgmov_Lib::useView('Public');
Sgmov_Lib::useServices(array('TravelTerminal'));
/**#@-*/

/**
 * ツアー会社からツアーを検索して返します。
 * @package    View
 * @subpackage TRA
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Trv_SearchTravelTerminals extends Sgmov_View_Public {

    /**
     * ツアーサービス
     * @var Sgmov_Service_TravelTerminal
     */
    public $_TravelTerminalService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_TravelTerminalService = new Sgmov_Service_TravelTerminal();
    }

    /**
     * 処理を実行します。
     *
     */
    public function executeInner() {

        $travel_cd_sel = filter_input(INPUT_POST, 'travel_cd_sel');
        $reqFlg        = filter_input(INPUT_POST, 'req_flg');
        $siteFlg       = filter_input(INPUT_POST, 'site_flg');
        try {
            if (empty($travel_cd_sel)) {
                throw new Exception;
            }
            // DB接続
            $db = Sgmov_Component_DB::getPublic();
            $travelTerminal = $this->_TravelTerminalService->fetchTravelTerminals($db, array('travel_id' => $travel_cd_sel), $reqFlg, $siteFlg);
        }
        catch (exception $e) {
            $travelTerminal = null;
        }
        return $travelTerminal;
    }
}
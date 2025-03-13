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
Sgmov_Lib::useServices(array('Travel'));
/**#@-*/

/**
 * ツアー会社からツアーを検索して返します。
 * @package    View
 * @subpackage TRA
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Tra_SearchTravels extends Sgmov_View_Public {

    /**
     * ツアーサービス
     * @var Sgmov_Service_Travel
     */
    public $_TravelService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_TravelService = new Sgmov_Service_Travel();
    }

    /**
     * 処理を実行します。
     *
     */
    public function executeInner() {

        $travel_agency_cd_sel = filter_input(INPUT_POST, 'travel_agency_cd_sel');
        $reqFlg               = filter_input(INPUT_POST, 'req_flg');
        $siteFlg               = filter_input(INPUT_POST, 'site_flg');
        try {
            if (empty($travel_agency_cd_sel)) {
                throw new Exception;
            }
            // DB接続
            $db = Sgmov_Component_DB::getPublic();
            $travel = $this->_TravelService->fetchTravels($db, array('travel_agency_id' => $travel_agency_cd_sel), $reqFlg, $siteFlg);
        }
        catch (exception $e) {
            $travel = null;
        }
        return $travel;
    }
}
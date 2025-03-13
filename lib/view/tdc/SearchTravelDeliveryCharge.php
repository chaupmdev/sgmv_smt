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
Sgmov_Lib::useServices(array('TravelDeliveryChargeAreas'));
/**#@-*/

/**
 * ツアー会社からツアーを検索して返します。
 * @package    View
 * @subpackage TDC
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Tdc_SearchTravelDeliveryCharge extends Sgmov_View_Public {

    /**
     * ツアー配送料金エリアサービス
     * @var Sgmov_Service_TravelDeliveryChargeAreas
     */
    private $_TravelDeliveryChargeAreasService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_TravelDeliveryChargeAreasService = new Sgmov_Service_TravelDeliveryChargeAreas;
    }

    /**
     * 処理を実行します。
     *
     */
    public function executeInner() {

        $travel_terminal_cd_sel = filter_input(INPUT_POST, 'travel_terminal_cd_sel');
        $reqFlg                 = filter_input(INPUT_POST, 'req_flg');
        try {
            if (empty($travel_terminal_cd_sel)) {
                throw new Exception;
            }
            // DB接続
            $db = Sgmov_Component_DB::getPublic();
            $travelDeliveryChargeAreas = $this->_TravelDeliveryChargeAreasService->fetchDeliveryChargeAddProvinces($db,
                    array('travel_terminal_id' => $travel_terminal_cd_sel), $reqFlg);
        }
        catch (exception $e) {
            $travelDeliveryChargeAreas = null;
        }
        return $travelDeliveryChargeAreas;
    }
}
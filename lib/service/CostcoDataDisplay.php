<?php
/**
 * @package    ClassDefFile
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
/**#@-*/

 /**
 * コストコデータ表示マスタ情報を扱います。
 *
 * @package Service
 * @author     K.Sawada
 * @copyright  2021-2021 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_CostcoDataDisplay {

    /**
     * コストコデータ表示マスタ情報の取得
     *
     * @param [type] $db
     * @param [type] $eventsubId
     * @param [type] $prefixDisplayKey
     * @return void
     */
    public function getInfo($db, $eventId, $eventsubId, $displayKey) {
        $query = "SELECT * FROM costco_data_display WHERE event_id = $1 AND eventsub_id = $2 AND display_key = $3 and start_date::date <= now()::date and end_date::date >= now()::date";
        if(empty($eventId) || empty($eventsubId) || @empty($displayKey)) {
            return array();
        }
        
        $result = $db->executeQuery($query, array($eventId, $eventsubId, $displayKey));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }
        
        $dataInfo = $result->get(0);
        
        return $dataInfo;
    }
}


<?php
/**
 * @package    ClassDefFile
 * @author     Y.Fujikawa
 * @copyright  2021-2022 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
/**#@-*/

 /**
 * 複数梱包時の配送料情報を扱います。
 *
 * @package Service
 * @author     Y.Fujikawa
 * @copyright  2021-2022 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_CostcoDeliveryFukusukonpo {

    /**
     * Undocumented function
     *
     * @param [type] $db
     * @param [type] $eventsubId
     * @param [type] $size
     * @return array
     */
    public function getInfo($db, $eventId, $eventsubId, $konposu) {
        $query = 'SELECT * FROM costco_delivery_fukusukonpo WHERE event_id = $1 AND eventsub_id = $2 AND kosu_min <= $3 and kosu_max >= $3 and start_date::date <= now()::date and end_date::date >= now()::date';
        if(empty($eventId) || empty($eventsubId) || empty($konposu)) {
            return array();
        }

        $result = $db->executeQuery($query, array($eventId, $eventsubId, $konposu));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }

        $dataInfo = $result->get(0);

        return $dataInfo;
    }
}


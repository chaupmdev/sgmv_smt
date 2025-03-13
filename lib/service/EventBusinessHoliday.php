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
 * EventBusinessHoliday
 *
 * @package Service
 * @author     K.Sawada
 * @copyright  2021-2021 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_EventBusinessHoliday {

    /**
     * getInfo
     *
     * @param [type] $db
     * @param [type] $eventId
     * @param [type] $eventsubId
     * @return array
     */
    public function getInfo($db, $eventId, $eventsubId) {
        $query = "SELECT * FROM event_business_holiday WHERE event_id = $1 AND eventsub_id = $2 AND  start_date <= $3 AND $3 <= end_date";
        if(empty($eventId) || empty($eventsubId)) {
            return array();
        }
        $dateTime = date('Y-m-d H:i:s');
        $result = $db->executeQuery($query, array($eventId, $eventsubId, $dateTime));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }
        $dataInfo = [];
        for($i = 0; $i < $resSize; $i++){
            $dataInfo[] = $result->get($i);
        }
        return $dataInfo;
    }
}


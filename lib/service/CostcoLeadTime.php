<?php
/**
 * @package    ClassDefFile
 * @author     Y.Fujikawa
 * @copyright  2022-2022 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
/**#@-*/

 /**
 * 配達希望日のリードタイム情報を取得します
 *
 * @package Service
 * @author     Y.Fujikawa
 * @copyright  2022-2022 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_CostcoLeadTime {

    /**
     * 配達希望日のリードタイム情報を取得します
     *
     * @param [type] $db
     * @param [type] $eventsubId
     * @param [type] $jis2
     * @return array
     */
    public function getInfo($db, $eventId, $eventsubId, $jis2) {
        //2022/02/20 GiapLN update ticket #SMT6-391
        $query = 'SELECT * FROM costco_lead_time WHERE event_id = $1 AND eventsub_id = $2 AND chaku_jis2 = $3 and start_date::date <= now()::date and end_date::date >= now()::date';
        if(empty($eventId) || empty($eventsubId) || empty($jis2)) {
            return array();
        }

        $result = $db->executeQuery($query, array($eventId, $eventsubId, $jis2));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }

        $dataInfo = $result->get(0);

        return $dataInfo;
    }
}


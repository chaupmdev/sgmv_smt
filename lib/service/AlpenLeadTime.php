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
class Sgmov_Service_AlpenLeadTime {

    /**
     * 配達希望日のリードタイム情報を取得します
     *
     * @param [type] $db
     * @param [type] $eventsubId
     * @param [type] $chakuJis2
     * @return array
     */
    public function getInfo($db, $eventId, $eventsubId, $chakuJis2) {
        $query = 'SELECT * FROM alpen_lead_time WHERE event_id = $1 AND eventsub_id = $2 AND chaku_jis2 = $3';

        if(empty($eventId) || empty($eventsubId) || empty($chakuJis2)) {
            return array();
        }

        $result = $db->executeQuery($query, array($eventId, $eventsubId, $chakuJis2));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }

        $dataInfo = $result->get(0);

        return $dataInfo;
    }
}


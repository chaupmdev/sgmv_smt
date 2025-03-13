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
 * アルペン宅配運賃マスタ情報を扱います。
 *
 * @package Service
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_AlpenBoxFare {
    /**
     * 
     * @param type $db
     * @param type $hatsuJis2
     * @param type $chakuJis2
     * @param type $boxId
     */
    public function fetchBoxFareByJis2AndBoxId($db, $hatsuJis2, $chakuJis2, $boxId, $eventsubId) {
        $query = 'SELECT 
                        * 
                  FROM
                        alpen_box_fare 
                  WHERE 
                        hatsu_jis2  = $1 
                    AND chaku_jis2  = $2 
                    AND box_id      = $3 
                    AND eventsub_id = $4';

        if(!isset($hatsuJis2) || !isset($chakuJis2) || empty($boxId) || empty($eventsubId)) {
            return array();
        }

        $result = $db->executeQuery($query, array($hatsuJis2, $chakuJis2, $boxId, $eventsubId));

        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }

        $dataInfo = $result->get(0);

        return $dataInfo;

    }
}


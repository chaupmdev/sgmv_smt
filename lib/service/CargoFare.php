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
 * カーゴマスタ情報を扱います。
 *
 * @package Service
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_CargoFare {
    
    /**
     * 
     * @param type $db
     * @param type $eventId
     * @return type
     */
    public function fetchCargoFareByJis2AndCargoNum($db, $hatsuJis2, $chakuJis2, $cargoCnt, $eventsubId) {
        $query = 'SELECT * FROM cargo_fare WHERE hatsu_jis2 = $1 AND chaku_jis2 = $2 AND cargo_cnt = $3 AND eventsub_id = $4';
        
        if(empty($hatsuJis2)
                || empty($chakuJis2)
                || empty($cargoCnt)
                || empty($eventsubId)) {
            return array();
        }
        
        $result = $db->executeQuery($query, array($hatsuJis2, $chakuJis2, $cargoCnt, $eventsubId));
        
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }
        
        $resultData = $result->get(0);

        return $resultData;
    }
}


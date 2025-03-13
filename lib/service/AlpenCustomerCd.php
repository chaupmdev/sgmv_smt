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
 * アルペン顧客コードマスタ情報を扱います。
 *
 * @package Service
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_AlpenCustomerCd {

    public function getInfo($db, $eventId, $eventsubId) {
        $query = 'SELECT * FROM alpen_customer_cd WHERE event_id = $1 AND eventsub_id = $2 ';
        if(empty($eventId) || empty($eventsubId) ) {
            return array();
        }

        $result = $db->executeQuery($query, array($eventId, $eventsubId));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }

        $dataInfo = $result->get(0);

        return $dataInfo;

    }

    /**
     * 
     */
    public function getCustomerCd($db, $eventsubId) {
        $query = 'SELECT * FROM alpen_customer_cd WHERE eventsub_id = $1';
        if(empty($eventsubId)) {
            return array();
        }

        $result = $db->executeQuery($query, array($eventsubId));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }

        $dataInfo = $result->get(0);

        return $dataInfo['customer_cd'];
    }

}

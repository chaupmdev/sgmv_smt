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
 * コストコ顧客コードマスタ情報を扱います。
 *
 * @package Service
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_CostcoCustomerCd {

    public function getInfo($db, $eventId, $eventsubId) {
        $query = 'SELECT * FROM costco_customer_cd WHERE event_id = $1 AND eventsub_id = $2 and start_date::date <= now()::date and end_date::date >= now()::date';
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
        $query = 'SELECT * FROM costco_customer_cd WHERE eventsub_id = $1 and start_date::date <= now()::date and end_date::date >= now()::date';
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

    
    // /**
    //  * 
    //  * @param type $db
    //  * @return type
    //  */
    // public function fetchBox($db) {
    //     $query = 'SELECT * FROM box ORDER BY cd, name';
        
    //     $result = $db->executeQuery($query);
        
    //     $returnList = array();
    //     for ($i = 0; $i < $result->size(); ++$i) {
    //         $returnList[] = $result->get($i);
    //     }
        
    //     return $returnList;
    // }
    
    // /**
    //  * 
    //  * @param type $db
    //  * @return type
    //  */
    // public function fetchBoxByEventsubId($db, $eventsubId) {
    //     $query = 'SELECT * FROM box WHERE eventsub_id = $1 ORDER BY cd, name';
        
    //     if(empty($eventsubId)) {
    //         return array();
    //     }
        
    //     $result = $db->executeQuery($query, array($eventsubId));
        
    //     $returnList = array();
    //     for ($i = 0; $i < $result->size(); ++$i) {
    //         $returnList[] = $result->get($i);
    //     }
        
    //     return $returnList;
    // }
    
    // /**
    //  * 
    //  * @param type $db
    //  * @return type
    //  */
    // public function fetchBox2($db, $eventsubId, $deliveryMeans, $outHomeWart, $customerKbn = null, $binshuKbn = null) {
        
    //     $queryParamList = array(
    //         $eventsubId,
    //         $deliveryMeans,
    //         $outHomeWart,
    //     );
        
    //     $query = 'SELECT * FROM box WHERE eventsub_id = $1 AND delivery_means = $2 AND out_home_wart = $3 ';
        
    //     if ($customerKbn == '0' || @!empty($customerKbn)) {
    //         $query .= ' AND customer_kbn = $4';
    //         $queryParamList[] = $customerKbn;
    //     }
        
    //     if ($binshuKbn == '0' || @!empty($binshuKbn)) {
    //         $query .= ' AND binshu_kbn = $5';
    //         $queryParamList[] = $binshuKbn;
    //     }
        
    //     $query .= ' ORDER BY CAST(cd AS integer), name';
        
    //     if(empty($eventsubId) || empty($deliveryMeans) || empty($outHomeWart)) {
    //         return array();
    //     }
        
    //     $result = $db->executeQuery($query, $queryParamList);
        
    //     $returnList = array();
    //     for ($i = 0; $i < $result->size(); ++$i) {
    //         $returnList[] = $result->get($i);
    //     }
        
    //     return $returnList;
    // }
    
    // /**
    //  * 
    //  * @param type $db
    //  * @return type
    //  */
    // public function fetchBoxById($db, $id) {
    //     $query = 'SELECT * FROM box WHERE id = $1';
        
    //     if(empty($id)) {
    //         return array();
    //     }
        
    //     $result = $db->executeQuery($query, array($id));
        
    //     $resSize = $result->size();
    //     if(empty($resSize)) {
    //         return array();
    //     }
        
    //     $dataInfo = $result->get(0);
        
    //     return $dataInfo;
    // }


    // /**
    //  * 宅配箱情報を取得する。
    //  *  
    //  * @param type $db
    //  * @param type $comiketId
    //  * @param type $type
    //  * @return array 
    //  */
    // public function getBoxAndComiketBoxInfo($db, $comiketId, $type) {
    //     $query = 'SELECT  
    //                box.name_display,
    //                box.name,
    //                comiket_box.num
    //             FROM box
    //             INNER JOIN comiket_box
    //                 ON comiket_box.box_id = box.id
    //             WHERE comiket_box.comiket_id = $1 AND comiket_box.type = $2 
    //             ORDER BY box.cd ASC';

    //     $result = $db->executeQuery($query, array($comiketId, $type));
    //     $resSize = $result->size();
    //     if(empty($resSize)) {
    //         return array();
    //     }

    //     $returnArr = array();
    //     for ($i = 0; $i < $resSize; $i++) {
    //         $row = $result->get($i);
    //         array_push($returnArr, $row);
    //     }

    //     return $returnArr;
    // }
}


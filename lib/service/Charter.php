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
 * 貸切マスタ情報を扱います。
 *
 * @package Service
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_Charter {
    
    /**
     * 
     * @param type $db
     * @param type $eventId
     * @return type
     */
    public function fetchCharter($db) {
        $query = 'SELECT * FROM charter ORDER BY cd, name';
        
        $result = $db->executeQuery($query);
        
        $returnList = array();
        for ($i = 0; $i < $result->size(); ++$i) {
            $returnList[] = $result->get($i);
        }
        
        return $returnList;
//        $query = 'SELECT prefecture_id, name FROM building WHERE event_id = $1 ORDER BY cd';
//        
//        $ids = array();
//        $names = array();
//        if(empty($eventId)) {
//            return array(
//            'ids'   => $ids,
//            'names' => $names,
//            );
//        }
//        
//        $result = $db->executeQuery($query, array($eventId));
//        for ($i = 0; $i < $result->size(); ++$i) {
//            $row     = $result->get($i);
//            $ids[]   = $row['id'];
//            $names[] = $row['name'];
//        }
//        
//        return array(
//            'ids'   => $ids,
//            'names' => $names,
//        );
    }
    
    /**
     * 
     * @param type $db
     * @param type $id
     * @return type
     */
    public function fetchCharterById($db, $id) {
        $query = 'SELECT * FROM charter WHERE id = $1';
        
        if(empty($id)) {
            return array();
        }
        
        $result = $db->executeQuery($query, array($id));
        
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }
        
        $row = $result->get(0);
        
        return $row;
    }
}


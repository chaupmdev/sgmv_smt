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
 * 宅配運賃マスタ情報を扱います。
 *
 * @package Service
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_BoxFare {
//    
//    /**
//     * 
//     * @param type $db
//     * @return type
//     */
//    public function fetchBox($db) {
//        $query = 'SELECT * FROM box ORDER BY cd, name';
//        
//        $result = $db->executeQuery($query);
//        
//        $returnList = array();
//        for ($i = 0; $i < $result->size(); ++$i) {
//            $returnList[] = $result->get($i);
//        }
//        
//        return $returnList;
//    }
//    
//    /**
//     * 
//     * @param type $db
//     * @return type
//     */
//    public function fetchBoxByEventsubId($db, $eventsubId) {
//        $query = 'SELECT * FROM box WHERE eventsub_id = $1 ORDER BY cd, name';
//        
//        if(empty($eventsubId)) {
//            return array();
//        }
//        
//        $result = $db->executeQuery($query, array($eventsubId));
//        
//        $returnList = array();
//        for ($i = 0; $i < $result->size(); ++$i) {
//            $returnList[] = $result->get($i);
//        }
//        
//        return $returnList;
//    }
//    
//    /**
//     * 
//     * @param type $db
//     * @return type
//     */
//    public function fetchBoxById($db, $id) {
//        $query = 'SELECT * FROM box WHERE id = $1';
//        
//        if(empty($id)) {
//            return array();
//        }
//        
//        $result = $db->executeQuery($query, array($id));
//        
//        if(empty($result)) {
//            return array();
//        }
//        
//        $dataInfo = $result->get(0);
//        
//        return $dataInfo;
//    }

    /**
     * 
     * @param type $db
     * @param type $hatsuJis2
     * @param type $chakuJis2
     * @param type $boxId
     */
    public function fetchBoxFareByJis2AndBoxId($db, $hatsuJis2, $chakuJis2, $boxId, $eventsubId) {
        $query = 'SELECT * FROM box_fare WHERE hatsu_jis2 = $1 AND chaku_jis2 = $2 AND box_id = $3 AND eventsub_id = $4';
        
        // バシ　変更　2020/02/27 0 の場合は、empty()は成功されるため、
        // if(empty($hatsuJis2) || empty($chakuJis2) || empty($boxId) || empty($eventsubId)) {
        if(!isset($hatsuJis2) || !isset($chakuJis2) || empty($boxId) || empty($eventsubId)) {
            return array();
        }
        
        $result = $db->executeQuery($query, array($hatsuJis2, $chakuJis2, $boxId, $eventsubId));
Sgmov_Component_Log::debug("################################# 500");
Sgmov_Component_Log::debug($result);
Sgmov_Component_Log::debug($result->size());
//        $result->size();
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }
        
        $dataInfo = $result->get(0);
        
        return $dataInfo;
        
    }
}


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
 * 宅配箱マスタ情報を扱います。
 *
 * @package Service
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_Eigyosho {
    
    /**
     * 
     * @param type $db
     * @return type
     */
    public function fetchEigyoSho($db) {
        $query = 'SELECT center_id,eigyosho_nm FROM eigyosho';
        
        $result = $db->executeQuery($query);
        
        $returnList = array();
        for ($i = 0; $i < $result->size(); ++$i) {
        	$row = $result->get($i);
            $returnList[$row['center_id']] = $row['eigyosho_nm'];
        }
        
        return $returnList;
    }

    /**
     * 
     * @param type $db
     * @return type
     */
    public function fetchEigyoShoByLocCd($db, $employCd , $isOrderBy = false) {
        $query = 'SELECT center_id, eigyosho_nm FROM eigyosho WHERE employ_cd = $1 ';
        if ($isOrderBy) {
            $query .= ' ORDER BY employ_cd, center_id ';
        }
        if(empty($employCd)) {
            return array();
        }
        
        $result = $db->executeQuery($query, array($employCd));
        
        $returnList = array();
        for ($i = 0; $i < $result->size(); ++$i) {
            $row = $result->get($i);
            $returnList[$row['center_id']] = trim($row['eigyosho_nm']);
        }
        
        return $returnList;
    }

    /**
     * 
     * @param type $db
     * @param type $employCd
     * @param type $centerId
     * @return type
     */
    public function fetchEigyoshoByEmpCdCenterId($db, $employCd , $centerId) {
        $query = 'SELECT * FROM eigyosho WHERE employ_cd = $1 AND center_id = $2 ';

        if(empty($employCd) || empty($centerId)) {
            return array();
        }
        
        $result = $db->executeQuery($query, array($employCd, $centerId));
        
//        $returnList = array();
//        for ($i = 0; $i < $result->size(); ++$i) {
//            $row = $result->get($i);
//        }
        
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }
        
        $row = $result->get(0);
        
        return $row;
    }


    /**
     * 
     * @param type $db
     * @return type
     */
    public function fetchEigyoShoNm($db) {
        $query = 'SELECT eigyosho_nm FROM eigyosho ORDER BY id';
        
        $result = $db->executeQuery($query);
        
        $addedList = array();
        $returnList = array();
        $count = 0;
        for ($i = 0; $i < $result->size(); ++$i) {
            $row = $result->get($i);
            if (in_array(trim($row['eigyosho_nm']), $addedList, true)) {
                continue;
            }
            $returnList[$count] = trim($row['eigyosho_nm']);
            $addedList[$returnList[$count]] = $returnList[$count];
            $count++;
        }
        
        return $returnList;
    }
}    
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
class Sgmov_Service_Occupation {
    
    /**
     * 
     * @param type $db
     * @return type
     */
    public function fetchOccupation($db) {
        $query = 'SELECT occupation_cd, occupation_nm FROM occupation GROUP BY occupation_nm';
        
        $result = $db->executeQuery($query);
        
        $returnList = array();
        for ($i = 0; $i < $result->size(); ++$i) {
        	$row = $result->get($i);
            $returnList[$row['occupation_cd']] = trim($row['occupation_nm']);
        }
        
        return $returnList;
    }

    /**
     * 
     * @param type $db
     * @return type
     */
    public function fetchOccupationNm($db) {
        $query = 'SELECT occupation_nm FROM occupation ORDER BY id';
        
        $result = $db->executeQuery($query);
        
        $addedList = array();
        $returnList = array();
        $count = 0;
        for ($i = 0; $i < $result->size(); ++$i) {
            $row = $result->get($i);
            if (in_array(trim($row['occupation_nm']), $addedList, true)) {
                continue;
            }
            $returnList[$count] = trim($row['occupation_nm']);
            $addedList[$returnList[$count]] = $returnList[$count];
            $count++;
        }
        
        return $returnList;
    }

    /**
     * 
     * @param type $db
     * @return type
     */
    public function fetchOccupationByEigyoCd($db, $centerId) {
        $query = 'SELECT occupation_cd, occupation_nm FROM occupation WHERE center_id = $1 ORDER BY center_id, occupation_cd';
        if(empty($centerId)) {
            return array();
        }
        
        $result = $db->executeQuery($query, array($centerId));
        
        $returnList = array();
        for ($i = 0; $i < $result->size(); ++$i) {
            $row = $result->get($i);
            $returnList[$row['occupation_cd']] = trim($row['occupation_nm']);
        }
        
        return $returnList;
    }
    
    /**
     * 
     * @param type $db
     * @return type
     */
    public function fetchOccupationByEmpCdAndEigyoCd($db, $employ_cd, $centerId) {
        $query = 'SELECT occupation_cd, occupation_nm FROM occupation WHERE employ_cd = $1 and center_id = $2 ORDER BY center_id, occupation_cd';
        if(empty($centerId)) {
            return array();
        }
        
        $result = $db->executeQuery($query, array($employ_cd, $centerId));
        
        $returnList = array();
        for ($i = 0; $i < $result->size(); ++$i) {
            $row = $result->get($i);
            $returnList[$row['occupation_cd']] = trim($row['occupation_nm']);
        }
        
        return $returnList;
    }

    /**
     * 
     * @param type $db
     * @return type
     */
    public function fetchWage($db, $employCd, $centerId, $occupationCd) {
       $query = 'SELECT wage FROM occupation WHERE employ_cd = $1 AND center_id = $2 AND occupation_cd = $3';
        if(empty($centerId) || empty($occupationCd)) {
            return array();
        }
        $queryParamList = array(
            $employCd,
            $centerId,
            $occupationCd
        );

        $result = $db->executeQuery($query, $queryParamList);
        
        $returnVal = "";
        for ($i = 0; $i < $result->size(); ++$i) {
            $row = $result->get($i);
            $returnList = $row['wage'];
        }
        
        return $returnList;
    }
}    
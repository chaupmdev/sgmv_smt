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
 * 館情報を扱います。
 *
 * @package Service
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_Building {
    
    /**
     * 
     * @param type $db
     * @param type $eventsubId
     * @return type
     */
    public function fetchBuildingByEventId($db, $eventsubId) {
        $query = 'SELECT id, name FROM building WHERE eventsub_id = $1 ORDER BY cd';
        
        $ids = array();
        $names = array();
        $resultList = array();
        if(empty($eventsubId)) {
            return array(
            'ids'   => $ids,
            'names' => $names,
            'list' => $resultList,
            );
        }
        
        $list = array();
        $result = $db->executeQuery($query, array($eventsubId));
        for ($i = 0; $i < $result->size(); ++$i) {
            $row     = $result->get($i);
            $list[] = $row;
            $ids[]   = $row['id'];
            $names[] = $row['name'];
        }
        
        return array(
            'ids'   => $ids,
            'names' => $names,
            'list' => $list,
        );
    }
    
    public function fetchBuildingById($db, $id) {
        $query = 'SELECT * FROM building WHERE id = $1;';
        
        if(empty($id)) {
            return array();
        }
        
        $result = $db->executeQuery($query, array($id));
        $dataInfo = $result->get(0);
        if(empty($dataInfo)) {
            return array();
        }

        return $dataInfo;
    }
    
    public function fetchBuildingNameByCd($db, $cd, $eventsubId = null) {
        $queryParamList = array($cd);
        
        if (is_array($eventsubId)) {
            $query = "SELECT name FROM building WHERE cd = $1 AND eventsub_id in("  . implode(',', $eventsubId) . ") GROUP BY name; ";
        } else {
            if(empty($eventsubId)) {
                $query = 'SELECT name FROM building WHERE cd = $1 GROUP BY name;';
            } else {
                $query = "SELECT name FROM building WHERE cd = $1 AND eventsub_id = $2 GROUP BY name; ";
                $queryParamList[] = $eventsubId;
            }
        }
        
        if(empty($cd)) {
            return array();
        }
        
        $result = $db->executeQuery($query, $queryParamList);
        
        $resSize = $result->size();
        if (@empty($resSize)) {
            return array();
        }
//Sgmov_Component_Log::debug($result);
        $dataInfo = @$result->get(0);
        
        if(empty($dataInfo)) {
            return array();
        }

        return $dataInfo;
    }
    
    public function fetchBuildingNameByEventsubId($db, $eventsubId) {
        
        if (is_array($eventsubId)) {
            $query = "SELECT cd, name FROM building WHERE eventsub_id in(" . implode(',', $eventsubId) . ") GROUP BY cd, name ORDER BY cd;";
        } else {
            $query = "SELECT cd, name FROM building WHERE eventsub_id = $1 GROUP BY cd, name ORDER BY cd asc, name asc;";
        
//            if(defined('RELATING_EVENTSUB_ID')) {
//                $query = "SELECT cd, name FROM building WHERE eventsub_id = $1 or eventsub_id = " . RELATING_EVENTSUB_ID  . " GROUP BY cd, name ORDER BY cd asc;";
//            } else {
//                $query = "SELECT cd, name FROM building WHERE eventsub_id = $1 GROUP BY cd, name ORDER BY cd asc;";
//            }
        }
        

        $ids = array();
        $names = array();
        $resultList = array();
        if(empty($eventsubId)) {
            return array(
            'ids'   => $ids,
            'names' => $names,
            'list' => $resultList,
            );
        }
        
        if (is_string($eventsubId)) {
            $result = $db->executeQuery($query, array($eventsubId));
        } else {
            $result = $db->executeQuery($query);
        }
            
        $resSize = $result->size();
        if(@empty($resSize)) {
            return array(
            'ids'   => $ids,
            'names' => $names,
            'list' => $resultList,
            );
        }
        
        for ($i = 0; $i < $result->size(); ++$i) {

            $row     = $result->get($i);
            $ids[]   = $row['cd'];
            $names[] = $row['name'];
            $resultList[] = $row;
        }

        return array(
            'ids'   => $ids,
            'names' => $names,
            'list' => $resultList,
        );
    }
    
    /**
     * 
     * @param type $db
     * @param type $eventsubId
     * @return type
     */
    public function fetchBuildingDataByEventsubId($db, $eventsubId) {
        
        if (is_array($eventsubId)) {
            $query = "SELECT * FROM building WHERE eventsub_id in(" . implode(',', $eventsubId) . ") ORDER BY cd;";
        } else {
            $query = "SELECT * FROM building WHERE eventsub_id = $1 ORDER BY cd asc;";
        }
        

        $ids = array();
        $names = array();
        $resultList = array();
        if(empty($eventsubId)) {
            return $resultList;
        }
        
        if (is_string($eventsubId)) {
            $result = $db->executeQuery($query, array($eventsubId));
        } else {
            $result = $db->executeQuery($query);
        }
            
        $resSize = $result->size();
        if(@empty($resSize)) {
            return $resultList;
        }
        
        for ($i = 0; $i < $result->size(); ++$i) {
            $row     = $result->get($i);
            $resultList[] = $row;
        }

        return $resultList;
    }
    /**
     * 
     * @param type $db
     * @param type $eventsubId
     * @return type
     */
    public function fetchBuildingDataByEventsubId2($db, $eventsubId) {
        
        if (is_array($eventsubId)) {
            $query = "SELECT * FROM building WHERE eventsub_id in(" . implode(',', $eventsubId) . ") ORDER BY cd asc, booth_position asc;";
        } else {
            $query = "SELECT * FROM building WHERE eventsub_id = $1 ORDER BY cd asc, booth_position asc;";
        }
        

        $ids = array();
        $names = array();
        $resultList = array();
        if(empty($eventsubId)) {
            return $resultList;
        }
        
        if (is_string($eventsubId)) {
            $result = $db->executeQuery($query, array($eventsubId));
        } else {
            $result = $db->executeQuery($query);
        }
            
        $resSize = $result->size();
        if(@empty($resSize)) {
            return $resultList;
        }
        
        for ($i = 0; $i < $result->size(); ++$i) {
            $row     = $result->get($i);
            $resultList[] = $row;
        }

        return $resultList;
    }
    
    
    public function fetchBuildingBoothPostionByBuildingCd($db, $buildingCd, $eventsubId = '') {
        
        if (is_array($eventsubId)) {
            $query = 'SELECT id, booth_position FROM building WHERE cd = $1 AND eventsub_id in(' . implode(',', $eventsubId) . ') ORDER BY id asc, booth_position asc;';
        } else {
            $query = 'SELECT id, booth_position FROM building WHERE cd = $1 AND eventsub_id = $2 ORDER BY id asc, booth_position asc;';
        }
        
        $ids = array();
        $names = array();
        $resultList = array();
        if(empty($buildingCd) || empty($eventsubId)) {
            return array(
            'ids'   => $ids,
            'names' => $names,
            'list' => $resultList,
            );
        }
        if (is_array($eventsubId)) {
            $result = $db->executeQuery($query, array($buildingCd));
        } else {
            $result = $db->executeQuery($query, array($buildingCd, $eventsubId));
        }
        for ($i = 0; $i < $result->size(); ++$i) {
            $row     = $result->get($i);
            $ids[]   = $row['id'];
            $names[] = $row['booth_position'];
            $resultList[] = $row;
        }
        
        return array(
            'ids'   => $ids,
            'names' => $names,
            'list' => $resultList,
        );
    }
    
    public function fetchBuildingNameByCdForAllClm($db, $cd, $eventsubId = null) {
        $queryParamList = array($cd);
        
        if (is_array($eventsubId)) {
            $query = "SELECT * FROM building WHERE cd = $1 AND eventsub_id in("  . implode(',', $eventsubId) . ");";
        } else {
            if(empty($eventsubId)) {
                $query = 'SELECT * FROM building WHERE cd = $1;';
            } else {
                $query = "SELECT * FROM building WHERE cd = $1 AND eventsub_id = $2;";
                $queryParamList[] = $eventsubId;
            }
        }
        
        if(empty($cd)) {
            return array();
        }
        
        $result = $db->executeQuery($query, $queryParamList);
        
        $resSize = $result->size();
        if (@empty($resSize)) {
            return array();
        }
//Sgmov_Component_Log::debug($result);
        $dataInfo = @$result->get(0);
        
        if(empty($dataInfo)) {
            return array();
        }

        return $dataInfo;
    }

    /**
     * Undocumented function
     *
     * @param [type] $db
     * @param [type] $cd
     * @param [type] $name
     * @param [type] $eventsubId
     * @return void
     */
    public function getBoothPositionByCdAndName($db, $cd, $name, $eventsubId) {
        //GiapLN hardcode for EVP 2024.12.18
        if ($eventsubId == '2505' && $cd == '東' && $name == '6') {
            $query = "SELECT * FROM building WHERE eventsub_id = $1 and cd = $2 and name = $3 ORDER BY created asc;";
        } else {
            $query = "SELECT * FROM building WHERE eventsub_id = $1 and cd = $2 and name = $3 ORDER BY cd asc, name asc;";
        }
        

        if(empty($cd) || empty($name) || empty($eventsubId)) {
            return array();
        }
        
        $result = $db->executeQuery($query, array($eventsubId, $cd, $name));

        $resultList = array();
        $resSize = $result->size();
        if(@empty($resSize)) {
            return $resultList;
        }
        
        for ($i = 0; $i < $result->size(); ++$i) {
            $row = $result->get($i);
            $resultList[] = $row;
        }

        return $resultList;

    }
}


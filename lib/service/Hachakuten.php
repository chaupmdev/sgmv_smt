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
 * イベント情報を扱います。
 *
 * @package Service
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_Hachakuten {

    const DELIVERY_TYPE_AIRPORT = 1;

    /**
     * 
     * @param type $db
     * @param string $code
     * @return array
     */
    public function fetchHachakutenByCode($db, $code) {
        $query = 'SELECT *
                    FROM mlk_hachakuten_mst 
                    WHERE hachakuten_shikibetu_cd = $1';

        $result = $db->executeQuery($query, array($code));
        return $result->size() > 0 ? $result->get(0) : [];
        
    }

    /**
     * 
     * @param type $db
     * @param string $code
     * @return array
     */
    public function fetchValidHachakutenByCode($db, $code) {
        $query = "SELECT *
                    FROM mlk_hachakuten_mst 
                    WHERE 
                        hachakuten_shikibetu_cd = $1 AND 
                        TO_TIMESTAMP(concat(start_date,' ',substring(input_end_time,1,2),':', substring(input_end_time,3,2)),'YYYY/MM/DD HH24:MI:SS') <= now() AND 
                        TO_TIMESTAMP(concat(end_date,' ',substring(input_end_time,1,2),':', substring(input_end_time,3,2)),'YYYY/MM/DD HH24:MI:SS') >= now()";

        //$date = date('Y-m-d');
        $result = $db->executeQuery($query, array($code));
        return $result->size() > 0 ? $result->get(0) : [];
    }
    
     /**
     * 
     * @param type $db
     * @param string $id
     * @return array
     */
    public function fetchHachakutenById($db, $id) {
        $query = 'SELECT *
                    FROM mlk_hachakuten_mst 
                    WHERE 
                        id = $1';

        //$date = date('Y-m-d');
        $result = $db->executeQuery($query, array($id));
        return $result->size() > 0 ? $result->get(0) : [];
    }
    
    /**
    * 
    * @param type $db
    * @param string $id
    * @return array
    */
   public function fetchAirportHachakutenById($db, $id) {
       $query = 'SELECT *
                   FROM mlk_hachakuten_mst 
                   WHERE id = $1 AND type = $2';

       //$date = date('Y-m-d');
       $result = $db->executeQuery($query, array($id, self::DELIVERY_TYPE_AIRPORT));
       return $result->size() > 0 ? $result->get(0) : [];
   }

    /**
     * 
     * @param type $db
     * @return array
     */
    public function fetchAllHachakuten($db, $codePath) {
        $query = "SELECT *
                    FROM mlk_hachakuten_mst 
                    WHERE 
                        hachakuten_shikibetu_cd != $1 AND
                        TO_TIMESTAMP(concat(start_date,' ',substring(input_end_time,1,2),':', substring(input_end_time,3,2)),'YYYY/MM/DD HH24:MI:SS') <= now() AND 
                        TO_TIMESTAMP(concat(end_date,' ',substring(input_end_time,1,2),':', substring(input_end_time,3,2)),'YYYY/MM/DD HH24:MI:SS') >= now()";

        
        //$date = date('Y-m-d');
        $result = $db->executeQuery($query, array($codePath));

        $returnList = array();
        for ($i = 0; $i < $result->size(); ++$i) {
            $row = $result->get($i);
            $row['name'] = "{$row['name_jp']}({$row['name_en']})";
            $row['nameUpperCase'] = strtoupper($row['name']);// "{$row['name_jp']}({$row['name_en']})";
            $returnList[] = $row;
        }
        
        return $returnList;
    }
    
    public function fetchHachakutenByType($db, $type) {
        $query = "SELECT *
                    FROM mlk_hachakuten_mst 
                    WHERE 
                        TO_TIMESTAMP(concat(start_date,' ',substring(input_end_time,1,2),':', substring(input_end_time,3,2)),'YYYY/MM/DD HH24:MI:SS') <= now() AND 
                        TO_TIMESTAMP(concat(end_date,' ',substring(input_end_time,1,2),':', substring(input_end_time,3,2)),'YYYY/MM/DD HH24:MI:SS') >= now() AND 
                        type = $1";

        $ids   = array('');
        $names = array('');
        
        //$date = date('Y-m-d');
        $result = $db->executeQuery($query, array($type));
        
        for ($i = 0; $i < $result->size(); ++$i) {
            $row     = $result->get($i);
            $ids[]   = $row['id'];
            $names[] = "{$row['name_jp']}({$row['name_en']})";
        }

        return array(
            'ids'   => $ids,
            'names' => $names,
        );
    }

    public function countAll($db) {
        $query = "SELECT hachakuten_shikibetu_cd FROM mlk_hachakuten_mst";
        $result = $db->executeQuery($query, []);
        return $result->size();
    }
    
    /**
     * getComiketByToiawaseNo
     * 
     * @param type $db
     * @param array $cds
     * @return array
     */
    public function checkExistCd($db, $cds) {
        $query = "SELECT hachakuten_shikibetu_cd FROM mlk_hachakuten_mst WHERE hachakuten_shikibetu_cd IN('" . implode('\',\'', $cds) . "')";
        $result = $db->executeQuery($query, []);
        $resSize = $result->size();
        if (empty($resSize)) {
            return array();
        }

        $returnList = array();
        for ($i = 0; $i < $result->size(); ++$i) {
            $returnList[] = $result->get($i)['hachakuten_shikibetu_cd'];
        }
        return $returnList;
    }
    
    /**
     * getComiketByToiawaseNo
     * 
     * @param type $db
     * @param array $existCds
     * @param array $csvAsArray
     * @return void
     */
    public function doImportSvc($db, $data) {
        try {

            // Clear all data before insert
            $query = "TRUNCATE mlk_hachakuten_mst RESTART IDENTITY CASCADE;";
            $remove = $db->executeQuery($query);
            
            // Do insert
            foreach ($data as $item) {
                $query = "INSERT INTO mlk_hachakuten_mst
                            ( 
                                hachakuten_shikibetu_cd,
                                note, 
                                zip, 
                                address, 
                                tel, 
                                name_jp, 
                                name_en, 
                                type, 
                                start_date, 
                                end_date, 
                                input_end_time, 
                                confirm_end_time, 
                                airport_flight_end_time
                            ) VALUES (
                                $1,
                                $2,
                                $3,
                                $4,
                                $5,
                                $6,
                                $7,
                                $8,
                                $9,
                                $10,
                                $11,
                                $12,
                                $13
                            );";
                Sgmov_Component_Log::debug("####### START INSERT mlk_hachakuten_mst #####");
                $db->executeUpdate($query, $item);
                Sgmov_Component_Log::debug("####### END INSERT mlk_hachakuten_mst #####");
            }
        } catch (Exception $e) {
            $debugString = Sgmov_Component_String::toDebugString(array('query'=>$query, 'params'=>$item));
            Sgmov_Component_Log::debug($debugString);
            Sgmov_Component_Log::debug($e);
        }
    }
    
    /**
     * getComiketByToiawaseNo
     * 
     * @param type $db
     * @param type $toiawase_no
     * @return array
     */
    public function getDataForExport($db, $request) {
        // $startDt = $request['start_date'] . ' 00:00:00';
        // $endDt = $request['end_date'] . ' 23:59:59';
        // $query = "SELECT * FROM mlk_hachakuten_mst
        //     WHERE (start_date <= $1 AND end_date >= $2) OR (start_date <= $3 AND end_date >= $4) OR (start_date >= $5 AND end_date <= $6)
        //     ORDER BY id ASC";
        // $params = array(
        //     $startDt,
        //     $startDt,
        //     $endDt,
        //     $endDt,
        //     $startDt,
        //     $endDt
        // );

        $query = "SELECT * FROM mlk_hachakuten_mst ORDER BY id ASC";
        $params = array();

        $result = $db->executeQuery($query, $params);
        $resSize = $result->size();
        if (empty($resSize)) {
            return array();
        }

        $returnList = array();
        for ($i = 0; $i < $result->size(); ++$i) {
            $returnList[] = $result->get($i);
        }
        return $returnList;
    }
}
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
class Sgmov_Service_MlkBoxFare {
    
    public function getMlkBoxFareByCode($db, $code, $boxId) {
        $query = 'SELECT *
                    FROM mlk_box_fare 
                    WHERE 
                        hachakuten_shikibetu_cd = $1 AND 
                        box_id = $2 AND 
                        start_date <= $3 AND 
                        end_date >= $4';

        $date = date('Y-m-d');
        $result = $db->executeQuery($query, array($code, $boxId, $date, $date));
        return $result->size() > 0 ? $result->get(0) : [];
        
        
    }
    
    /**
     * 
     * @param type $db
     * @return array
     */
    public function getAllMlkBoxFare($db) {
        $query = 'SELECT *
                    FROM mlk_box_fare 
                    WHERE 
                        start_date <= $1 AND 
                        end_date >= $2';

        
        $date = date('Y-m-d');
        $result = $db->executeQuery($query, array($date, $date));

        $returnList = array();
        for ($i = 0; $i < $result->size(); ++$i) {
            $returnList[] = $result->get($i);
        }
        
        return $returnList;
    }
    
    
    
}
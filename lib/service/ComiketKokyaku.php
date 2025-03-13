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
class Sgmov_Service_ComiketKokyaku {
    
//    /**
//     * 
//     * @param type $db
//     * @return type
//     */
//    public function fetchComiketKokyaku($db) {
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
    
    /**
     * 
     * @param type $db
     * @return type
     */
    public function fetchComiketKokyakuByCustomerCd($db, $customerCd) {
        $query = 'SELECT * FROM comiket_kokyaku WHERE sgw_kokyaku_cd = $1';
        
        if(empty($customerCd)) {
            return array();
        }
        
        $result = $db->executeQuery($query, array($customerCd));
        
        $returnList = array();
        for ($i = 0; $i < $result->size(); ++$i) {
            $returnList[] = $result->get($i);
        }
        
        return $returnList;
    }
}


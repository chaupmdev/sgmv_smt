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
 * カーゴマスタ情報を扱います。
 *
 * @package Service
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_Cargo {
    
    /**
     * 
     * @param type $db
     * @param type $eventId
     * @return type
     */
    public function fetchCargo($db) {
        $query = 'SELECT * FROM cargo ORDER BY cd';
        
        $result = $db->executeQuery($query);
        
        $returnList = array();
        for ($i = 0; $i < $result->size(); ++$i) {
            $returnList[] = $result->get($i);
        }
        
        return $returnList;
    }
}


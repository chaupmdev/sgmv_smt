<?php

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
/**#@-*/

 /**
 * 預かり箱マスタ情報を扱います。
 *
 * @package Service
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_AzukariBox {
    
    /**
     * 
     * @param type $db
     * @return type
     */
    public function fetchBox($db, $eventsubId, $comiketDetailAzukariKaisuTypeSel) {
        $query = 'SELECT * FROM azukari_box WHERE eventsub_id = $1 AND  azukari_kaisu_type = $2 ORDER BY cd, name';

        $queryParamList = array(
            $eventsubId,
            $comiketDetailAzukariKaisuTypeSel
        );

        $result = $db->executeQuery($query, $queryParamList);
        
        $returnList = array();
        for ($i = 0; $i < $result->size(); ++$i) {
            $returnList[] = $result->get($i);
        }
        
        return $returnList;
    }

        
    /**
     * 
     * @param type $db
     * @return type
     */
    public function fetchBoxById($db, $id) {
        $query = 'SELECT * FROM azukari_box WHERE id = $1';
        
        if(empty($id)) {
            return array();
        }
        
        $result = $db->executeQuery($query, array($id));
        
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }
        
        $dataInfo = $result->get(0);
        
        return $dataInfo;
    }
    
}
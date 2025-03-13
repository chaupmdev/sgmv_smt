<?php

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
/**#@-*/

 /**
 * イベント関連日程マスタ
 *
 * @package Service
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_EventDate {

	/**
     * 
     * @param type $db
     * @return type
     */
    public function fetchEventTerm($db, $eventsubId) {
        $query = 'SELECT  
        			target_date, 
        			from_to  
        		FROM 
        			event_date 
        		WHERE eventsub_id = $1 
        		GROUP BY
					target_date,
					from_to 
        		ORDER BY 
        			from_to';
        
        if(empty($eventsubId)) {
            return array();
        }
        
        $result = $db->executeQuery($query, array($eventsubId));
        
        $returnList = array();
        for ($i = 0; $i < $result->size(); ++$i) {
            $returnList[] = $result->get($i);
        }
        
        return $returnList;
    }



    public function getAzukariTerm($db, $eventsubId){
        $query = 'SELECT
                    group_cd,
                    from_to 
                FROM
                    event_date 
                WHERE
                    eventsub_id = $1
                GROUP BY
                    group_cd,
                    from_to
                ORDER BY 
                    group_cd,
                    from_to';
        
        if(empty($eventsubId)) {
            return array();
        }
        
        $result = $db->executeQuery($query, array($eventsubId));
        
        $returnList = array();
        for ($i = 0; $i < $result->size(); ++$i) {
            $returnList[] = $result->get($i);
        }
        
        return $returnList;
    }

}
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
 * コストコ_配送可能地域マスタ情報を扱います。
 *
 * @package Service
 * @author     K.Sawada
 * @copyright  2021-2021 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_CostcoHaisokanoJis5 {

    /**
     * コストコ_配送可能地域情報取得
     *
     * @param [type] $db
     * @param [type] $eventsubId
     * @param [type] $shohinCd
     * @return array
     */
    public function getInfo($db, $eventId, $eventsubId, $jis5, $haisoType = "0") {
        $query = 'SELECT * FROM costco_haisokano_jis5 WHERE event_id = $1 AND eventsub_id = $2 AND jis5cd = $3 AND haitatsu_fukano_flg = $4 and start_date::date <= now()::date and end_date::date >= now()::date';
        if(empty($eventId) || empty($eventsubId) || empty($jis5)) {
            return array();
        }
        
        $result = $db->executeQuery($query, array($eventId, $eventsubId, $jis5, $haisoType));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }
        
        $dataInfo = $result->get(0);
        
        return $dataInfo;
    }
}


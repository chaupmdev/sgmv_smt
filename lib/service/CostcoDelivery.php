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
 * コストコ配送料金マスタ情報を扱います。
 *
 * @package Service
 * @author     K.Sawada
 * @copyright  2021-2021 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_CostcoDelivery {

    /**
     * 配送料取得
     *
     * @param [type] $db
     * @param [type] $eventsubId
     * @param [type] $size
     * @return array
     */
    public function getInfo($db, $eventId, $eventsubId, $size) {
        $query = 'SELECT * FROM costco_delivery WHERE event_id = $1 AND  eventsub_id = $2 and size_min <= $3 and size_max >= $3 and start_date::date <= now()::date and end_date::date >= now()::date';
        if(empty($eventId) || empty($eventsubId) || empty($size)) {
            return array();
        }
        
        $result = $db->executeQuery($query, array($eventId, $eventsubId, $size));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }
        
        $dataInfo = $result->get(0);
        
        return $dataInfo;
    }

    /**
     * 配送料取得(転送料を含む配送料対応)
     *
     * @param [type] $db
     * @param [type] $eventsubId
     * @param [type] $size
     * @param [type] $prefId
     * @return array
     */
    public function getInfoPlusTensoryo($db, $eventId, $eventsubId, $size) {
        $query = 'SELECT * FROM costco_delivery '
                .'WHERE event_id    = $1 '
                .'AND   eventsub_id = $2 '
                .'AND   size_min   <= $3 and size_max >= $3 and start_date::date <= now()::date and end_date::date >= now()::date';

        if(empty($eventId) || empty($eventsubId) || empty($size)) {
            return array();
        }

        $result = $db->executeQuery($query, array($eventId, $eventsubId, $size));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }

        for($i=0;$i<$resSize;$i++){
            $dataInfo[]=$result->get($i);
        }
        return $dataInfo;
    }

}

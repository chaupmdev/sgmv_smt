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
 * コストコオプション料金マスタ情報を扱います。
 *
 * @package Service
 * @author     K.Sawada
 * @copyright  2021-2021 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_CostcoOption {

    /**
     * オプション情報取得
     *
     * @param [type] $db
     * @param [type] $eventsubId
     * @param [type] $type
     * @return array
     */
    public function getInfo($db, $eventId, $eventsubId, $type) {
        $query = 'SELECT * FROM costco_option WHERE event_id = $1 AND eventsub_id = $2 AND option_type = $3  and start_date::date <= now()::date and end_date::date >= now()::date order by yumusyou_kbn';
        if(@empty($eventId) || @empty($eventsubId) || @empty($type)) {
            return array();
        }

        $result = $db->executeQuery($query, array($eventId, $eventsubId, $type));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }

        for($i=0;$i<$resSize;$i++){
            $dataInfo[]=$result->get($i);
        }
        return $dataInfo;
    }
    
    /**
     * オプション情報取得
     *
     * @param [type] $db
     * @param [type] $eventsubId
     * @param [type] $type
     * @return array
     */
    public function getInfoBySelectOption($db, $eventId, $eventsubId, $type, $optionType) {
        $query = 'SELECT * FROM costco_option WHERE event_id = $1 AND eventsub_id = $2 AND option_type = $3 AND yumusyou_kbn = $4 and start_date::date <= now()::date and end_date::date >= now()::date ';
        if(@empty($eventId) || @empty($eventsubId) || @empty($type) || !isset($optionType)) {
            return array();
        }

        $result = $db->executeQuery($query, array($eventId, $eventsubId, $type, $optionType));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }

        $dataInfo = $result->get(0);
        return $dataInfo;
    }
    
    /**
     * オプション情報取得
     *
     * @param [type] $db
     * @param [type] $optionId
     * @return array
     */
    public function getInfoOptionById($db, $optionId) {
        $query = 'SELECT * FROM costco_option WHERE id = $1 and start_date::date <= now()::date and end_date::date >= now()::date ';
        if(@empty($optionId)) {
            return array();
        }

        $result = $db->executeQuery($query, array($optionId));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }

        $dataInfo = $result->get(0);
        return $dataInfo;
    }
    
    /**
     * オプション情報の全て取得
     *
     * @param [type] $db
     * @param [type] $eventsubId
     * @param [type] $type
     * @param [type] $optionKbn
     * @return array
     */
    public function getInfoAll($db, $eventId, $eventsubId, $type) {
        $query = 'SELECT * FROM costco_option WHERE event_id = $1 AND eventsub_id = $2 AND option_type = $3  and start_date::date <= now()::date and end_date::date >= now()::date order by yumusyou_kbn';
        if(@empty($eventId) || @empty($eventsubId) || @empty($type)) {
            return array();
        }

        $result = $db->executeQuery($query, array($eventId, $eventsubId, $type));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }

        for($i=0;$i<$resSize;$i++){
            $dataInfo[]=$result->get($i);
        }
        return $dataInfo;
    }

    /**
     * 階段上げ作業取得
     *
     * @param [type] $db
     * @param [type] $eventsubId
     * @param [type] $type
     * @return array
     */
    public function getInfoKaidan($db, $eventId, $eventsubId, $type) {
        $query = 'SELECT * FROM costco_option WHERE  event_id = $1 AND eventsub_id = $2 AND option_type = $3 and start_date::date <= now()::date and end_date::date >= now()::date ';
        if(@empty($eventId) || @empty($eventsubId) || @empty($type)) {
            return array();
        }

        $result = $db->executeQuery($query, array($eventId, $eventsubId, $type));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }

        $dataInfo = $result->get(0);
        return $dataInfo;
    }

    /**
     * 階段上げ作業取得
     *
     * @param [type] $db
     * @return array
     */
    public function getAll($db) {
        $query = "SELECT DISTINCT ON (option_type) option_type, option_name FROM  costco_option WHERE option_type != '3' GROUP BY option_type, id";
        $result = $db->executeQuery($query, array());
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


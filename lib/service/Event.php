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
class Sgmov_Service_Event {
    
    /**
     * 
     * @param type $db
     * @param type $date
     */
    public function fetchEventByEventId($db, $eventId) {
        $query = 'SELECT id, name FROM event WHERE event_id = $1 ORDER BY cd';
        
        if(empty($eventId)) {
            return array(
                'ids' => array(),
                'names' => array(),
            );
        }
        
        $ids   = array();
        $names = array();
//        $dataList = array();
        $result = $db->executeQuery($query, array($eventId));
        for ($i = 0; $i < $result->size(); ++$i) {
            $row     = $result->get($i);
            $ids[]   = $row['id'];
            $names[] = $row['name'];
        }
        
        return array(
            'ids'   => $ids,
            'names' => $names,
        );
    }
    
    /**
     * イベントデータ取得.
     * @param type $db
     * @param type $date
     */
    public function fetchEventInfoByEventId($db, $eventId) {
        $query = 'SELECT * FROM event WHERE id = $1 ORDER BY cd';
        
        $result = $db->executeQuery($query, array($eventId));
        $row     = $result->get(0);
        
        return $row;
    }
    
    /**
     * 
     * @param type $db
     * @param type $id
     * @return type
     */
    public function fetchEventById($db, $id) {
        $query = 'SELECT id, name FROM event WHERE id = $1';
        
        $result = $db->executeQuery($query, array($id));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }
        
        $row = $result->get(0);
        
        
        return $row;
    }
    
    /**
     * 
     * @param type $db
     * @param type $date
     */
    public function fetchEventListWithinTerm($db, $date=NULL, $dateTime=NULL) {

        $query = 
"
SELECT *
FROM
(
SELECT
DISTINCT
    event.id AS id,
    event.name AS name,
    event.cd AS cd
FROM
    event
    INNER JOIN
        eventsub
    ON  event.id = eventsub.event_id
 WHERE ($1 BETWEEN eventsub.departure_fr AND eventsub.departure_to) OR (eventsub.arrival_fr <= $2 AND $3 <= eventsub.arrival_to_time)
) a
ORDER BY
    a.cd
";
        
        if(empty($date)) {
            $date = date('Y-m-d');
        }
        
        if(empty($dateTime)) {
            $dateTime = date('Y-m-d H:i:s');
        }
        
        $ids   = array();
        $names = array();
        $returnList = array();
        $result = $db->executeQuery($query, array($date, $date, $dateTime));
        for ($i = 0; $i < $result->size(); ++$i) {
            $row = $result->get($i);
            $returnList[] = $row;
            $ids[]   = $row['id'];
            $names[] = $row['name'];
        }
        
        return array(
            "ids" => $ids,
            "names" => $names,
            "list" => $returnList,
        );
    }
    
    /**
     * 
     * @param type $db
     * @param type $date
     */
    public function fetchEventListWithinTerm2($db, $date=NULL, $dateTime=NULL, $shikibetu=NULL) {
        //同じイベントIDでイベントサブIDと識別が個別で複数ある為、識別の条件を追加する。（EVEとEVP）
        $shikibetuJoken = "";
        if (!empty($shikibetu)) {
            $shikibetuJoken = " AND LOWER(eventsub.shikibetsushi)=LOWER('{$shikibetu}') ";
        }
        $query = 
"
SELECT *
FROM
(
SELECT
DISTINCT
    event.id AS id,
    event.name || eventsub.name AS name,
    event.name AS event_name,
    eventsub.name AS eventsub_name,
--    eventsub.term_fr AS term_fr,
--    eventsub.term_to AS term_to,
    event.cd AS cd
--    eventsub.business AS business,
--    eventsub.individual AS individual,
--    eventsub.postcode AS postcode,
--    eventsub.address AS address,
--    eventsub.postcode || ' ' || eventsub.address AS place
FROM
    event
    INNER JOIN
        eventsub
    ON  event.id = eventsub.event_id {$shikibetuJoken}
 WHERE ($1 BETWEEN eventsub.departure_fr AND eventsub.departure_to) OR (eventsub.arrival_fr <= $2 AND $3 <= eventsub.arrival_to_time)
) a
ORDER BY
    a.cd
";
        
        if(empty($date)) {
            $date = date('Y-m-d');
        }
        
        if(empty($dateTime)) {
            $dateTime = date('Y-m-d H:i:s');
        }

        $returnList = array();
        $result = $db->executeQuery($query, array($date, $date, $dateTime));
        for ($i = 0; $i < $result->size(); ++$i) {
            $row = $result->get($i);
            $returnList[] = $row;
        }
        
        return $returnList;
    }


        /**
     * 
     * @param type $db
     * @param type $date
     */
    public function fetchEventAllList($db, $eventId) {

        $query = 
"
SELECT *
FROM
(
SELECT
DISTINCT
    event.id AS id,
    event.name || eventsub.name AS name,
    event.name AS event_name,
    eventsub.name AS eventsub_name,
    event.cd AS cd
FROM
    event
    INNER JOIN
        eventsub
    ON  event.id = eventsub.event_id
    where event.id = $1
) a
ORDER BY
    a.cd
";
        
      
        $returnList = array();
        $result = $db->executeQuery($query, array($eventId));
        for ($i = 0; $i < $result->size(); ++$i) {
            $row = $result->get($i);
            $returnList[] = $row;
        }
        
        return $returnList;
    }


    /**
     * 
     * @param type $db
     * @param string $shikibetsuShi
     * @return array
     */
    public function fetchEventByShikibetsushi($db, $shikibetsuShi, $date=NULL, $dateTime=NULL){
        $query = 'SELECT event.id as eventid,
                         eventsub.id as eventsubId,
                         event.name || eventsub.name AS name
                    FROM event 
                    INNER JOIN eventsub ON
                        event.id = eventsub.event_id 
                    WHERE 
                        eventsub.shikibetsushi = $1 
                        AND (($2 BETWEEN departure_fr AND departure_to) OR (arrival_fr <= $3 AND $4 <= arrival_to_time))
                    LIMIT 1';

        if(empty($shikibetsuShi)) {
            return array();
        }

        if(empty($date)) {
            $date = date('Y-m-d');
        }
        
        if(empty($dateTime)) {
            $dateTime = date('Y-m-d H:i:s');
        }
        
        $result = $db->executeQuery($query, array($shikibetsuShi, $date, $date, $dateTime));

        $row = array();
        if($result->size() > 0){
            $row = $result->get(0);
        }

        return $row;
    }
    
    /**
     * イベント識別子でeventを取得
     * @param type $db
     * @param string $shikibetsuShi
     * @return array
     */
    public function getEventWithShikibetsushi($db, $shikibetsuShi) {
        $query = 'SELECT event.*
                    FROM event 
                    INNER JOIN eventsub ON
                        event.id = eventsub.event_id  
                    WHERE eventsub.shikibetsushi = $1
                    LIMIT 1';

        if(empty($shikibetsuShi)) {
            return array();
        }

        $result = $db->executeQuery($query, array($shikibetsuShi));

        $row = array();
        if($result->size() > 0){
            $row = $result->get(0);
        }

        return $row;
    }

    /**
     * イベントIDとイベントサブIDでevent,eventsubを取得する
     * @param type $db
     * @param string $shikibetsuShi
     * @return array
     */
    public function getEventAndEventsub($db, $eventId, $eventsubId) {
        $query = 'SELECT     event.id                               event_id
                            ,event.cd                               cd
                            ,eventsub.id                            eventsub_id
                            ,event.name                             event_name
                            ,event.customer_cd                      customer_cd
                            ,event.shikibetsushi                    shikibetsushi
                            ,eventsub.name                          eventsub_name
                            ,eventsub.id                            event_id
                            ,eventsub.zip                           zip 
                            ,eventsub.address                       address 
                            ,eventsub.jis5cd                        jis5cd 
                            ,eventsub.term_fr                       term_fr 
                            ,eventsub.term_to                       term_to 
                            ,eventsub.business                      business 
                            ,eventsub.individual                    individual 
                            ,eventsub.departure_fr                  departure_fr 
                            ,eventsub.departure_to                  departure_to 
                            ,eventsub.arrival_fr                    arrival_fr 
                            ,eventsub.arrival_to                    arrival_to 
                            ,eventsub.custcd                        custcd 
                            ,eventsub.sgmvcd                        sgmvcd 
                            ,eventsub.out_bound_loading_fr          out_bound_loading_fr 
                            ,eventsub.out_bound_loading_to          out_bound_loading_to 
                            ,eventsub.out_bound_unloading_fr        out_bound_unloading_fr 
                            ,eventsub.out_bound_unloading_to        out_bound_unloading_to 
                            ,eventsub.in_bound_loading_fr           in_bound_loading_fr 
                            ,eventsub.in_bound_loading_to           in_bound_loading_to 
                            ,eventsub.in_bound_unloading_fr         in_bound_unloading_fr 
                            ,eventsub.in_bound_unloading_to         in_bound_unloading_to 
                            ,eventsub.in_bound_unloading_date_flg   in_bound_unloading_date_flg 
                            ,eventsub.arrival_to_time               arrival_to_time 
                            ,eventsub.manual_display                manual_display 
                            ,eventsub.paste_display                 paste_display 
                            ,eventsub.building_display              building_display 
                            ,eventsub.booth_display                 booth_display 
                            ,eventsub.kojin_box_col_date_flg        kojin_box_col_date_flg 
                            ,eventsub.kojin_box_col_time_flg        kojin_box_col_time_flg 
                            ,eventsub.kojin_box_dlv_date_flg        kojin_box_dlv_date_flg 
                            ,eventsub.kojin_box_dlv_time_flg        kojin_box_dlv_time_flg 
                            ,eventsub.kojin_cag_col_date_flg        kojin_cag_col_date_flg 
                            ,eventsub.kojin_cag_col_time_flg        kojin_cag_col_time_flg 
                            ,eventsub.kojin_cag_dlv_date_flg        kojin_cag_dlv_date_flg 
                            ,eventsub.kojin_cag_dlv_time_flg        kojin_cag_dlv_time_flg 
                            ,eventsub.hojin_box_col_date_flg        hojin_box_col_date_flg 
                            ,eventsub.hojin_box_col_time_flg        hojin_box_col_time_flg 
                            ,eventsub.hojin_box_dlv_date_flg        hojin_box_dlv_date_flg 
                            ,eventsub.hojin_box_dlv_time_flg        hojin_box_dlv_time_flg 
                            ,eventsub.hojin_cag_col_date_flg        hojin_cag_col_date_flg 
                            ,eventsub.hojin_cag_col_time_flg        hojin_cag_col_time_flg 
                            ,eventsub.hojin_cag_dlv_date_flg        hojin_cag_dlv_date_flg 
                            ,eventsub.hojin_cag_dlv_time_flg        hojin_cag_dlv_time_flg 
                            ,eventsub.hojin_kas_col_date_flg        hojin_kas_col_date_flg 
                            ,eventsub.hojin_kas_col_time_flg        hojin_kas_col_time_flg 
                            ,eventsub.hojin_kas_dlv_date_flg        hojin_kas_dlv_date_flg 
                            ,eventsub.hojin_kas_dlv_time_flg        hojin_kas_dlv_time_flg 
                            ,eventsub.kojin_box_col_flg             kojin_box_col_flg 
                            ,eventsub.kojin_box_dlv_flg             kojin_box_dlv_flg 
                            ,eventsub.kojin_cag_col_flg             kojin_cag_col_flg 
                            ,eventsub.kojin_cag_dlv_flg             kojin_cag_dlv_flg 
                            ,eventsub.hojin_box_col_flg             hojin_box_col_flg 
                            ,eventsub.hojin_box_dlv_flg             hojin_box_dlv_flg 
                            ,eventsub.hojin_cag_col_flg             hojin_cag_col_flg 
                            ,eventsub.hojin_cag_dlv_flg             hojin_cag_dlv_flg 
                            ,eventsub.hojin_kas_col_flg             hojin_kas_col_flg 
                            ,eventsub.hojin_kas_dlv_flg             hojin_kas_dlv_flg 
                            ,eventsub.venue                         venue 
                            ,eventsub.kojin_box_del_date_flg        kojin_box_del_date_flg 
                            ,eventsub.kojin_box_del_time_flg        kojin_box_del_time_flg 
                            ,eventsub.hojin_box_del_date_flg        hojin_box_del_date_flg 
                            ,eventsub.hojin_box_del_time_flg        hojin_box_del_time_flg 
                            ,eventsub.departure_fr_time             departure_fr_time 
                            ,eventsub.baggage_flg                   baggage_flg 
                            ,eventsub.milkrun_flg                   milkrun_flg 
                            ,eventsub.lang_id                       lang_id 
                            ,eventsub.parcel_room                   parcel_room 
                            ,eventsub.parcel_room_en                parcel_room_en 
                            ,eventsub.last_arrival_date             last_arrival_date 
                            ,eventsub.shikibetsushi                 eventsub_shikibetsushi 
                            ,eventsub.security_pattern              security_pattern 
                    FROM event 
                    INNER JOIN eventsub ON
                        event.id = eventsub.event_id  
                    WHERE event.id    = $1
                      AND eventsub.id = $2
                    ';

        if(empty($eventId) || empty($eventsubId)) {
            return array();
        }

        $result = $db->executeQuery($query, array($eventId, $eventsubId));

        $row = array();
        if($result->size() > 0){
            $row = $result->get(0);
        }

        return $row;
    }
    
    /**
     * イベントサブがある全てのイベント取得
     * 
     * @param type $db
     * @return array
     */
    public function fetchAllEventsHasEventSub($db) {
        $query = 'SELECT DISTINCT event.id, event.name
                    FROM event 
                    INNER JOIN eventsub ON
                        event.id = eventsub.event_id  
                    ORDER BY event.id';
        
        $result = $db->executeQuery($query);
        
        $returnList = array();
        for ($i = 0; $i < $result->size(); ++$i) {
            $returnList[] = $result->get($i);
        }
        
        return $returnList;
    }

}
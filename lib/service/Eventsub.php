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
class Sgmov_Service_Eventsub {

    /**
     * 現在日時を条件にイベント情報を取得
     * @param type $db
     * @param type $date
     */
    public function fetchEventsubListWithinTerm($db, $date=NULL, $dateTime=NULL) {
        $query = 'SELECT id, name FROM eventsub WHERE ($1 BETWEEN departure_fr AND departure_to) AND (arrival_fr <= $2 AND $3 <= arrival_to_time) ORDER BY cd';
        
        if(empty($date)) {
            $date = date('Y-m-d');
        }
        
        if(empty($dateTime)) {
            $dateTime = date('Y-m-d H:i:s');
        }
        
        $ids   = array();
        $names = array();
        $result = $db->executeQuery($query, array($date, $date, $dateTime));
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
     * 現在日時を条件にイベント情報を取得
     * @param type $db
     * @param type $date
     */
    public function fetchEventsubListWithinTerm2($db, $date=NULL, $dateTime=NULL) {
        $query = 'SELECT * FROM eventsub WHERE ($1 BETWEEN departure_fr AND departure_to) AND (arrival_fr <= $2 AND $3 <= arrival_to_time) ORDER BY cd';
        
        if(empty($date)) {
            $date = date('Y-m-d');
        }
        
        if(empty($dateTime)) {
            $dateTime = date('Y-m-d H:i:s');
        }
//        $ids   = array();
//        $names = array();
        $returnList = array();
        $result = $db->executeQuery($query, array($date, $date, $dateTime));
        for ($i = 0; $i < $result->size(); ++$i) {
            $row     = $result->get($i);
            $returnList[] = $row;
//            $ids[]   = $row['id'];
//            $names[] = $row['name'];
        }
        
        return $returnList;
    }
    
    /**
     * イベントサブIDを条件にイベント情報を取得
     * @param type $db
     * @param type $date
     */
    public function fetchEventsubByEventsubId($db, $eventsubId) {
// Sgmov_Component_Log::debug("##################################### 410 eventsub");
        $query = 'SELECT * FROM eventsub WHERE id = $1 ORDER BY cd';
// Sgmov_Component_Log::debug("##################################### 411 eventsub");

        if(empty($eventsubId)) {
            return array();
        }
// Sgmov_Component_Log::debug("##################################### 412 eventsub");
        
//        $ids   = array();
//        $names = array();
//        $dataList = array();
        $result = $db->executeQuery($query, array($eventsubId));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }
        
// Sgmov_Component_Log::debug("##################################### 413 eventsub");
// Sgmov_Component_Log::debug($result);
        $row     = $result->get(0);
//        for ($i = 0; $i < $result->size(); ++$i) {
//            $row     = $result->get($i);
//            $dataList[] = $row;
//            $ids[]   = $row['id'];
//            $names[] = $row['name'];
//        }
        
//        ///////////////////////////////////////////////////////////////////////////////////
//        // 暫定対応
//        ///////////////////////////////////////////////////////////////////////////////////
//        $rowList = $this->addEventsubInfo(array($row));
//        if(!empty($rowList)) {
//            $row = $rowList[0];
//        }
//        ///////////////////////////////////////////////////////////////////////////////////
        return $row;
//        return array(
//            'ids'   => $ids,
//            'names' => $names,
//            'list' => $dataList,
//        );
    }

    /**
     * イベントIDとイベントサブIDを条件にイベントサブ情報を取得
     * @param type $db
     * @param type $date
     */
    public function fetchEventsubIdAndSubid($db, $eventId, $eventsubId) {
        $query = 'SELECT * FROM eventsub WHERE event_id = $1 AND id = $2 ORDER BY cd';

        if(empty($eventId) || empty($eventsubId)) {
            return array();
        }
        $result = $db->executeQuery($query, array($eventId, $eventsubId));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }
        
        $row     = $result->get(0);
        return $row;

    }

    /**
     * 
     * @param type $db
     * @param type $id
     * @return type
     */
    public function fetchEventsubListWithinTermByEventId($db, $id, $date=NULL, $dateTime=NULL, $shikibetu=NULL) {
        //同じイベントIDでイベントサブIDと識別が個別で複数ある為、識別の条件を追加する。（EVEとEVP）
        $shikibetuJoken = "";
        if (!empty($shikibetu)) {
            $shikibetuJoken = " AND LOWER(shikibetsushi)=LOWER('{$shikibetu}') ";
        }
//        $query = "SELECT *, '〒' || substring(zip, 1, 3) || '-' || substring(zip, 4, 4) || ' ' || address AS place FROM eventsub WHERE event_id=$1 AND ($2 BETWEEN departure_fr AND departure_to) OR ($3 BETWEEN arrival_fr AND arrival_to) ORDER BY cd";
        $query = "SELECT *, '〒' || substring(zip, 1, 3) || substring(zip, 4, 4) || ' ' || address AS place FROM eventsub WHERE event_id=$1 {$shikibetuJoken} AND (($2 BETWEEN departure_fr AND departure_to) OR (arrival_fr <= $3 AND $4 <= arrival_to_time)) ORDER BY cd";
        if(empty($id)) {
            return array();
        }
        
        if(empty($date)) {
            $date = date('Y-m-d');
        }
        
        if(empty($dateTime)) {
            $dateTime = date('Y-m-d H:i:s');
        }
        
        $result = $db->executeQuery($query, array($id, $date, $date, $dateTime));
        
        $ids   = array();
        $names = array();
        $returnList = array();
        for ($i = 0; $i < $result->size(); ++$i) {
            $row = $result->get($i);
            $returnList[] = $row;
            $ids[]   = $row['id'];
            $names[] = $row['name'];
        }

        if(empty($returnList)) {
            return array();
        }
        
//        ///////////////////////////////////////////////////////////////////////////////////
//        // 暫定対応
//        ///////////////////////////////////////////////////////////////////////////////////
//        $returnList =  $this->addEventsubInfo($returnList);
//        ///////////////////////////////////////////////////////////////////////////////////
        
        return array(
            "ids" => $ids,
            "names" => $names,
            "list" => $returnList
        );
    }
    
    /**
     * 
     */
    public function fetchEventsubListAll($db) {
        $query = 'SELECT * FROM eventsub ORDER BY cd';
        
        $returnList = array();
        $result = $db->executeQuery($query);
        for ($i = 0; $i < $result->size(); ++$i) {
            $returnList[] = $result->get($i);
        }
        
//        ///////////////////////////////////////////////////////////////////////////////////
//        // 暫定対応
//        ///////////////////////////////////////////////////////////////////////////////////
//        return $this->addEventsubInfo($returnList);
//        ///////////////////////////////////////////////////////////////////////////////////
        return $returnList;
    }
    
    /**
     * 
     */
    public function fetchEventsubListByEventId($db, $eventId) {
        $query = 'SELECT * FROM eventsub WHERE event_id = $1 ORDER BY cd';
        
        $returnList = array();
        $result = $db->executeQuery($query, array($eventId));
        for ($i = 0; $i < $result->size(); ++$i) {
            $returnList[] = $result->get($i);
        }
        
//        ///////////////////////////////////////////////////////////////////////////////////
//        // 暫定対応
//        ///////////////////////////////////////////////////////////////////////////////////
//        return $this->addEventsubInfo($returnList);
//        ///////////////////////////////////////////////////////////////////////////////////
        return $returnList;
    }
    
    /**
     * 
     */
    public function fetchEventsubIdListByEventId($db, $eventId) {
        $query = 'SELECT * FROM eventsub WHERE event_id = $1 ORDER BY cd';
        
        $returnList = array();
        $result = $db->executeQuery($query, array($eventId));
        for ($i = 0; $i < $result->size(); ++$i) {
            $data = $result->get($i);
            $returnList[] = $data['id'];
        }
        
//        ///////////////////////////////////////////////////////////////////////////////////
//        // 暫定対応
//        ///////////////////////////////////////////////////////////////////////////////////
//        return $this->addEventsubInfo($returnList);
//        ///////////////////////////////////////////////////////////////////////////////////
        return $returnList;
    }
    
    /**
     * 暫定対応
     */
//    private function addEventsubInfo($dataList) {
//        $returnList = array();
//        $returnList = $dataList;
////        $week = array("日", "月", "火", "水", "木", "金", "土");
////        
////        foreach($dataList as $key => $val) {
////            if($val['event_id'] == '1') { // デザインフェスタ
////                
////                $outboundCollectFr = date('Y-m-d', strtotime('-11 day', strtotime($val["term_fr"])));
////                $outboundCollectTo = date('Y-m-d', strtotime('-5 day', strtotime($val["term_to"])));
////                $outboundDeliveryFr = date('Y-m-d', strtotime($val["term_fr"]));
////                $outboundDeliveryTo = date('Y-m-d', strtotime($val["term_to"]));
////                
////                $inboundCollectFr = date('Y-m-d', strtotime($val["term_fr"]));
////                $inboundCollectTo = date('Y-m-d', strtotime($val["term_to"]));
////                $inboundDeliveryFr = date('Y-m-d');
////                $inboundDeliveryTo = date('Y-m-d', strtotime('+42 day', strtotime($inboundDeliveryFr)));
////                
////                
////                $val['departure_collect_fr'] = $outboundCollectFr;
////                $val['departure_collect_to'] = $outboundCollectTo;
////                $val['departure_delivery_fr'] = $outboundDeliveryFr;
////                $val['departure_delivery_to'] = $outboundDeliveryTo;
////                
////                $val['arrival_collect_fr'] = $inboundCollectFr;
////                $val['arrival_collect_to'] = $inboundCollectTo;
////                $val['arrival_delivery_fr'] = $inboundDeliveryFr;
////                $val['arrival_delivery_to'] = $inboundDeliveryTo;
////                
////            } else if($val['event_id'] == '2') {  // コミケ
////                $outboundCollectFr = date('Y-m-d', strtotime('-11 day', strtotime($val["term_fr"])));
////                $outboundCollectTo = date('Y-m-d', strtotime('-5 day', strtotime($val["term_to"])));
////                $outboundDeliveryFr = date('Y-m-d', strtotime($val["term_fr"]));
////                $outboundDeliveryTo = date('Y-m-d', strtotime($val["term_to"]));
////                
////                $inboundCollectFr = date('Y-m-d', strtotime($val["term_fr"]));
////                $inboundCollectTo = date('Y-m-d', strtotime($val["term_to"]));
////                $inboundDeliveryFr = date('Y-m-d');
////                $inboundDeliveryTo = date('Y-m-d', strtotime('+42 day', strtotime($inboundDeliveryFr)));
////                
////                
////                $val['departure_collect_fr'] = $outboundCollectFr;
////                $val['departure_collect_to'] = $outboundCollectTo;
////                $val['departure_delivery_fr'] = $outboundDeliveryFr;
////                $val['departure_delivery_to'] = $outboundDeliveryTo;
////                
////                $val['arrival_collect_fr'] = $inboundCollectFr;
////                $val['arrival_collect_to'] = $inboundCollectTo;
////                $val['arrival_delivery_fr'] = $inboundDeliveryFr;
////                $val['arrival_delivery_to'] = $inboundDeliveryTo;
////            }
////            $returnList[] = $val;
////        }
//        
//        return $returnList;
//    }

    /**
     * 
     * @param type $db
     * @return integer event id
     */
    public function getEventId($db, $eventsubId) {
        $query = 'SELECT  
                  event.id,
                  eventsub.id AS eventsubId,
                  event.name As eventName,
                  eventsub.name As eventSubName,
                 eventsub.arrival_to_time
                FROM event
                INNER JOIN eventsub ON
                    event.id = eventsub.event_id 
                WHERE eventsub.id = $1';
       
        $result = $db->executeQuery($query, array($eventsubId));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }
        $dataInfo = $result->get(0);

        return $dataInfo;
    }

    /**
     * Undocumented function
     *
     * @param [type] $db
     * @param [type] $shikibetsushi
     * @return void
     */
    public function getEventsubInfoByShikibetsushi($db, $shikibetsushi, $date=null, $dateTime=null) {
        $query = ' SELECT *
                FROM eventsub
                WHERE shikibetsushi = $1 
                AND (($2 BETWEEN departure_fr AND departure_to) OR (arrival_fr <= $3 AND $4 <= arrival_to_time)) 
                ';

        if(empty($shikibetsushi)) {
            return array();
        }

        if(empty($date)) {
            $date = date('Y-m-d');
        }

        if(empty($dateTime)) {
            $dateTime = date('Y-m-d H:i:s');
        }

        $result = $db->executeQuery($query, array($shikibetsushi, $date, $date, $dateTime));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }
        $dataInfo = $result->get(0);

        return $dataInfo;
    }
    /**
     * getEventIdByShikibetsushi get info eventsub by Shikibetsushi
     *
     * @param [type] $db
     * @param String $shikibetsushi
     * @return Array
     */
    public function getEventIdByShikibetsushi($db, $shikibetsushi) {
        $query = ' SELECT eventsub.*
                FROM eventsub
                INNER JOIN event ON event.id = eventsub.event_id
                WHERE eventsub.shikibetsushi = $1 
                ORDER BY arrival_to_time DESC
                LIMIT 1
                ';
        $result = $db->executeQuery($query, array($shikibetsushi));
        
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }
        $dataInfo = $result->get(0);

        return $dataInfo;
    }
    //①有効なイベントリストを取得
    /**
     * getListEventValid get list event valid 
     *
     * @param [type] $db
     * @param String $shikibetsushi
     * @return Array
     */
    public function getListEventValid($db, $shikibetsushi) {
        $date = date('Y-m-d H:i:s');
        
        $query = ' 
                SELECT eventsub.*
                FROM eventsub
                INNER JOIN event ON event.id = eventsub.event_id
                WHERE 
                    eventsub.shikibetsushi = $1 
                    AND eventsub.arrival_to_time > $2 
                    AND eventsub.departure_fr_time < $3
                ORDER BY 
                    eventsub.arrival_to_time ASC
                ';
        $result = $db->executeQuery($query, array($shikibetsushi, $date, $date));
        
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }
        //$dataInfo = $result->get(0);
//        $returnList = array();
//        for ($i = 0; $i < $result->size(); ++$i) {
//            $returnList[] = $result->get($i);
//        }
//        
//        return $returnList;
        return $result->get(0);
    }
    
    //②最後の切れたイベント取得
    /**
     * getEventLastExpiration get event last expiration 
     *
     * @param [type] $db
     * @param String $shikibetsushi
     * @return Array
     */
    public function getEventLastExpiration($db, $shikibetsushi) {
        $date = date('Y-m-d H:i:s');
        $query = ' 
                SELECT eventsub.*
                FROM eventsub
                INNER JOIN event ON event.id = eventsub.event_id
                WHERE 
                    eventsub.shikibetsushi = $1 
                    AND eventsub.arrival_to_time < $2 
                    AND eventsub.departure_fr_time < $3
                ORDER BY 
                    eventsub.arrival_to_time DESC
                LIMIT 1
                ';
        $result = $db->executeQuery($query, array($shikibetsushi, $date, $date));
        
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }
        $dataInfo = $result->get(0);
        
        return $dataInfo;
    }
    
    //③木開催のイベントを取得する 
    /**
     * getEventInTheFuture get event in the  future 
     *
     * @param [type] $db
     * @param String $shikibetsushi
     * @return Array
     */
    public function getEventInTheFuture($db, $shikibetsushi) {
        $date = date('Y-m-d H:i:s');
        $query = ' 
                SELECT eventsub.*
                FROM eventsub
                INNER JOIN event ON event.id = eventsub.event_id
                WHERE 
                    eventsub.shikibetsushi = $1 
                    AND eventsub.arrival_to_time > $2 
                    AND eventsub.departure_fr_time > $3
                ORDER BY 
                    eventsub.arrival_to_time ASC
                LIMIT 1
                ';
        $result = $db->executeQuery($query, array($shikibetsushi, $date, $date));
        
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }
        $dataInfo = $result->get(0);
        
        return $dataInfo;
    }
    
    /**
     * 管理出力用にイベント情報一覧を取得する
     *
     * @param [type] $db
     * @param String $shikibetsushi
     * @return Array
     */
    public function getEventSubForExport($db, $idList) {
        $inStr=implode(',',$idList);
        $query =" 
                SELECT *
                FROM eventsub
                WHERE 
                    event_id in ({$inStr}) 
                ORDER BY 
                    cd DESC
                ";

        if(empty($idList)) {
            return array();
        }
        $result = $db->executeQuery($query, array());

        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }

        for($i=0;$i<$resSize;$i++) {
            $data = $result->get($i);
            $returnList[] = $data;
        }
        
        return $returnList;

    }
    
}
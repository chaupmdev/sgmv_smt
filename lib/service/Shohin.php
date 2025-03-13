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
 * コミケ申込データマスタ情報を扱います。
 *
 * @package Service
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_Shohin {

	// トランザクションフラグ
    private $transactionFlg = TRUE;

    /**
     * トランザクションフラグ設定.
     * @param type $flg TRUE=内部でトランザクション処理する/FALSE=内部でトランザクション処理しない
     */
    public function setTrnsactionFlg($flg) {
        $this->transactionFlg = $flg;
    }

    /**
     * 
     * @param type $db
     * @return type
     */
    public function fetchShohinByEventSubId($db, $eventsubId) {
        $query = 'SELECT * FROM shohin WHERE eventsub_id = $1 ORDER BY id ';
        
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
    
    /**
     * 
     * @param type $db
     * @return type
     */
    public function fetchShohinById($db, $id) {
        $query = 'SELECT * FROM shohin WHERE id = $1';
        
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

    /**
     * 
     * @param type $db
     * @return type
     */
    public function checkShohinInfo($db, $eventsubId, $riyoFlg, $listPtrn, $dateTime = NULL) {
        $query = "SELECT  
                   count(*)
                FROM shohin
                WHERE eventsub_id = $1 
                      AND (shohin_ryou_flg = $2 OR shohin_ryou_flg = '3') 
                      AND (list_ptrn = $3) 
                      AND ($4 BETWEEN term_fr AND term_to) ";


        
        if(empty($dateTime)) {
            $dateTime = date('Y-m-d H:i:s');
        }

        $result = $db->executeQuery($query, array($eventsubId, $riyoFlg, $listPtrn, $dateTime));
        $resSize = $result->size();

        if(empty($resSize)) {
            return array();
        }
        
        $dataInfo = $result->get(0);

        return $dataInfo;
    }

    /**
     * 
     * @param type $db
     * @return type
     */
    public function checkShohinTerm($db, $id, $dateTime = NULL) {
        $query = 'SELECT  
                    count(*)
                FROM shohin
                WHERE shohin.id = $1 AND ($2 BETWEEN shohin.term_fr AND shohin.term_to)';
        
        if(empty($dateTime)) {
            $dateTime = date('Y-m-d H:i:s');
        }
        $result = $db->executeQuery($query, array($id, $dateTime));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }
        $dataInfo = $result->get(0);

        return $dataInfo;
    }

    /**
     * 
     * @param type $db
     * @return type
     */
    public function getTerm($db, $eventsubId) {
        $query = 'SELECT  
                    min(term_fr),
                    max(term_to)
                FROM shohin
                WHERE eventsub_id = $1';
      
        $result = $db->executeQuery($query, array($eventsubId));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }
        $dataInfo = $result->get(0);
        return $dataInfo;
    }

    /**
     * 商品情報を取得する。
     *  
     * @param type $db
     * @param type $comiketId
     * @param type $type
     * @return array 
     */
    public function getShohinAndComiketBox($db, $comiketId, $type) {
        $query = 'SELECT  
                   shohin.name_display,
                   shohin.name,
                   comiket_box.num
                FROM shohin
                INNER JOIN comiket_box
                    ON comiket_box.box_id = shohin.id
                WHERE comiket_box.comiket_id = $1 AND comiket_box.type = $2';

        $result = $db->executeQuery($query, array($comiketId, $type));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }

        $returnArr = array();
        for ($i = 0; $i < $resSize; $i++) {
            $row = $result->get($i);
            array_push($returnArr, $row);
        }

        return $returnArr;
    }

    /**
     * 当日の商品を取得する。
     *
     * @param type $db
     * @return type
     */
    public function fetchShohinByEventSubIdWithInTerm($db, $eventsubId, $riyoFlg, $dateTime = NULL) {
        $query = "SELECT * FROM shohin 
                            WHERE eventsub_id = $1 
                                AND (shohin.shohin_ryou_flg = $2 OR shohin.shohin_ryou_flg = '3')
                                AND ($3 BETWEEN shohin.term_fr AND shohin.term_to) 
                            ORDER BY id ";
        
        if(empty($eventsubId)) {
            return array();
        }

        if(empty($dateTime)) {
            $dateTime = date('Y-m-d H:i:s');
        }

        $result = $db->executeQuery($query, array($eventsubId, $riyoFlg, $dateTime));
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
    public function fetchShohinByRyoFlg($db, $eventsubId, $ryoFlg, $boxId = null, $shohinPattern = null, $dateTime = NULL) {
        //$query = "SELECT * FROM shohin WHERE eventsub_id = $1  AND (shohin_ryou_flg = $2 OR shohin_ryou_flg = '3') ORDER BY id ";
        if(empty($dateTime)) {
            $dateTime = date('Y-m-d H:i:s');
        }

        $queryParamList = array(
            $eventsubId,
            $ryoFlg
        );

        $query = "SELECT
                    * 
                    FROM
                    shohin
                 LEFT JOIN ( SELECT
                      comiket_box.box_id
                      , sum(comiket_box.num) as count
                    FROM
                      comiket_box 
                      INNER join comiket 
                        ON comiket.id = comiket_box.comiket_id 
                    WHERE
                      eventsub_id = $1 
                      AND comiket.del_flg = '0' 
                      AND comiket.bpn_type = $2
                    GROUP BY
                      comiket_box.box_id 
                    ORDER BY
                      comiket_box.box_id ASC ) comiket_box 
                    ON comiket_box.box_id = shohin.id 
                WHERE
                  eventsub_id = $1 
                  AND (CAST(shohin.shohin_ryou_flg AS integer) = $2 OR shohin.shohin_ryou_flg = '3')
                 ";

        if(@!empty($boxId)){
          $query .= ' AND shohin.id = $3';
          array_push($queryParamList, $boxId);
        } 

        if(!empty($shohinPattern) && @empty($boxId)){
            $query .= ' AND list_ptrn = $3';
        }elseif (!empty($shohinPattern) && !@empty($boxId)) {
            $query .= ' AND list_ptrn = $3';
        }

        array_push($queryParamList, $shohinPattern);

        if ($ryoFlg == "2" && @empty($boxId)) {
            $query .= '  AND ($4 BETWEEN shohin.term_fr AND shohin.term_to)';
        }elseif ($ryoFlg == "2" && @!empty($boxId)) {
            $query .= ' AND ($5 BETWEEN shohin.term_fr AND shohin.term_to)';
        }
        array_push($queryParamList, $dateTime);
        
        $query .= ' ORDER BY shohin.id';
  
        if(empty($eventsubId)) {
            return array();
        }

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
    public function fetchShohin($db, $eventsubId, $ryoFlg, $shohinPattern = null, $boxId = null) {
        //$query = "SELECT * FROM shohin WHERE eventsub_id = $1  AND (shohin_ryou_flg = $2 OR shohin_ryou_flg = '3') ORDER BY id ";
        $dateTime = date('Y-m-d H:i:s');

        $queryParamList = array(
            $eventsubId,
            $ryoFlg,
            $shohinPattern
        );

        $query = "SELECT
                    * 
                    FROM
                    shohin
                 LEFT JOIN ( SELECT
                      comiket_box.box_id
                      , sum(comiket_box.num) as count
                    FROM
                      comiket_box 
                      INNER join comiket 
                        ON comiket.id = comiket_box.comiket_id 
                    WHERE
                      eventsub_id = $1 
                      AND comiket.del_flg = '0' 
                      AND comiket.bpn_type = $2
                    GROUP BY
                      comiket_box.box_id 
                    ORDER BY
                      comiket_box.box_id ASC ) comiket_box 
                    ON comiket_box.box_id = shohin.id 
                WHERE
                  eventsub_id = $1 
                  AND (CAST(shohin.shohin_ryou_flg AS integer) = $2 OR shohin.shohin_ryou_flg = '3') AND list_ptrn = $3
                 ";

          if(!empty($boxId)){
            $query .= ' AND shohin.id = $4';
            array_push($queryParamList, $boxId);
          }

          // 商品ヘッダパターン [当日物販用:2]
          if($ryoFlg == "2"){
            if(@empty($boxId)){
              $query .= '  AND ($4 BETWEEN shohin.term_fr AND shohin.term_to)';
              array_push($queryParamList, $dateTime);
            }else{
              $query .= ' AND ($5 BETWEEN shohin.term_fr AND shohin.term_to)';
              array_push($queryParamList, $dateTime);
            }
          }

        $query .= ' ORDER BY shohin.id';

  
        if(empty($eventsubId)) {
            return array();
        }

        $result = $db->executeQuery($query, $queryParamList);
        $returnList = array();
        for ($i = 0; $i < $result->size(); ++$i) {
            $returnList[] = $result->get($i);
        }
        
        return $returnList;
    }
}
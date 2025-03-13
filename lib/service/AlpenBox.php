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
class Sgmov_Service_AlpenBox {
    
    /**
     * ボックス情報全件を取得
     * @param type $db
     * @return type
     */
    public function fetchBox($db) {
        $query = 'SELECT * FROM alpen_box ORDER BY cd, name';

        $result = $db->executeQuery($query);

        $returnList = array();
        for ($i = 0; $i < $result->size(); ++$i) {
            $returnList[] = $result->get($i);
        }

        return $returnList;
    }
    
    /**
     * 店舗ごとのボックス情報を取得
     * @param type $db
     * @return type
     */
    public function fetchBoxByEventsubId($db, $eventsubId) {
        $query = 'SELECT * FROM alpen_box WHERE eventsub_id = $1 ORDER BY cd, name';
        
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
     * 顧客区分、便種区分を条件に取得
     * @param type $db
     * @return type
     */
    public function fetchBox2($db, $eventsubId, $deliveryMeans, $outHomeWart, $customerKbn = null, $binshuKbn = null) {
        if($deliveryMeans==""){
            $deliveryMeans=1;
        }
        $queryParamList = array(
            $eventsubId,
            $deliveryMeans,
            $outHomeWart,
        );

        $query = 'SELECT * FROM alpen_box WHERE eventsub_id = $1 AND delivery_means = $2 AND out_home_wart = $3 ';

        // 顧客区分
        if ($customerKbn == '0' || @!empty($customerKbn)) {
            $query .= ' AND customer_kbn = $4';
            $queryParamList[] = $customerKbn;
        }

        // 便種区分
        if ($binshuKbn == '0' || @!empty($binshuKbn)) {
            $query .= ' AND binshu_kbn = $5';
            $queryParamList[] = $binshuKbn;
        }

        $query .= ' ORDER BY CAST(cd AS integer), name';
        
        if(empty($eventsubId) || empty($deliveryMeans) || empty($outHomeWart)) {
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
     * idを条件に取得
     * @param type $db
     * @return type
     */
    public function fetchBoxById($db, $id) {
        $query = 'SELECT * FROM alpen_box WHERE id = $1';
        
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
     *  
     * @param type $db
     * @param type $appId
     * @param type $type
     * @return array 
     */
    public function getBoxAndComiketBoxInfo($db, $appId, $type) {
        $query = 'SELECT  
                   alpen_box.name_display,
                   alpen_box.name,
                   alpen_app_box.num
                FROM       alpen_box
                INNER JOIN alpen_app_box
                       ON  alpen_box.id = alpen_app_box.box_id
                WHERE   alpen_app_box.app_id = $1 
                    AND alpen_app_box.type   = $2 
                ORDER BY alpen_box.cd ASC';

        $result = $db->executeQuery($query, array($appId, $type));
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
}


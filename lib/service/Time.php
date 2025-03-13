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
class Sgmov_Service_Time {

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
     * class_cd が１のものを取得します
     * @param type $db
     */
    public function fetchTimeDataList($db) {
        $query = "SELECT * FROM time WHERE class_cd = '1' order by sort_cd";
        
        $result = $db->executeQuery($query);
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }

        $returnList = array();
        for ($i = 0; $i < $result->size(); ++$i) {
            $returnList[] = $result->get($i);
        }

        return $returnList;
    }
    
    /**
     * 
     * @param type $db
     * @param type $classCd
     */
    public function fetchTimeDataListByClassCd($db, $classCd) {
        $query = "SELECT * FROM time WHERE class_cd = $1 order by sort_cd";
        
        if(empty($classCd)) {
            return array();
        }
        
        $result = $db->executeQuery($query, array($classCd));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }

        $returnList = array();
        for ($i = 0; $i < $result->size(); ++$i) {
            $returnList[] = $result->get($i);
        }

        return $returnList;
    }
}
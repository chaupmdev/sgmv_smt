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
class Sgmov_Service_InBoundUnloadingCal {

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
     * @param type $eventsubId
     * @param type $hatsuJis2
     * @param type $chakuJis2
     * @return type
     */
    public function fetchInBoundUnloadingCalByHaChaku($db, $eventsubId, $hatsuJis2, $chakuJis2) {
        //$query = 'SELECT * FROM in_bound_unloading_cal WHERE eventsub_id=$1 AND hatsu_jis2=$2 AND chaku_jis2=$3';
        $query =  " SELECT"
                . "    *"
                . " FROM"
                . "    in_bound_unloading_cal"
                . " WHERE"
                . "    eventsub_id=$1"
                . "    AND hatsu_jis2=$2"
                . "    AND (chaku_jis2=$3 or chaku_jis2='0')"
                . " ORDER BY"
                . "    chaku_jis2 DESC"
                . " LIMIT 1";

        if(empty($eventsubId) || empty($hatsuJis2) || empty($chakuJis2)) {
            return array();
        }

        $result = $db->executeQuery($query, array($eventsubId, $hatsuJis2, $chakuJis2));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }
        $row = $result->get(0);

        return $row;
    }
}


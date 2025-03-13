<?php
/**
 * @package    ClassDefFile
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
/**#@-*/

/**
 * ツアー配送料金情報を扱います。
 *
 * @package Service
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_TravelDeliveryCharge {

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
     * ツアー配送料金マスタをDBから取得し、ツアー発着地名を返します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return ツアー発着地名
     */
    public function fetchTravelDeliveryCharges($db, $reqFlg = 2) {

        $query = "
            SELECT
                travel_delivery_charge.id,
                travel_terminal.name,
                TO_CHAR(travel_terminal.departure_date,'YYYY年MM月DD日') AS DEPARTURE_DATE,
                TO_CHAR(travel_terminal.arrival_date,'YYYY年MM月DD日')   AS ARRIVAL_DATE
            FROM
                travel_delivery_charge
                INNER JOIN
                travel_terminal
                ON
                    travel_delivery_charge.travel_terminal_id = travel_terminal.id
            WHERE
                travel_delivery_charge.dcruse_flg in ('0','1')
            AND 
                travel_delivery_charge.req_flg = '{$reqFlg}'
            AND
                travel_terminal.dcruse_flg in ('0','1')
            ORDER BY
                travel_terminal.cd,
                travel_delivery_charge.travel_terminal_id,
                travel_delivery_charge.id";

        $ids = array();
        $names = array();
        $departure_dates = array();
        $arrival_dates = array();

        $result = $db->executeQuery($query);
        for ($i = 0; $i < $result->size(); ++$i) {
            $row = $result->get($i);
            $ids[] = $row['id'];
            $names[] = $row['name'];
            $departure_dates[] = $row['departure_date'];
            $arrival_dates[] = $row['arrival_date'];
        }

        return array(
            'ids'             => $ids,
            'names'           => $names,
            'departure_dates' => $departure_dates,
            'arrival_dates'   => $arrival_dates,
        );
    }

    /**
     * ツアー配送料金情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function select_id($db) {

        $query    = 'SELECT max(id) + 1 AS id, nextval($1) FROM travel_delivery_charge;';
        $params   = array();
        $params[] = 'travel_delivery_charge_id_seq';

        if($this->transactionFlg) {
            $db->begin();
        }
        $data = $db->executeQuery($query, $params);
        if($this->transactionFlg) {
            $db->commit();
        }
        $row = $data->get(0);

        if ($row['id'] > $row['nextval']) {
            return $row['id'];
        }

        return $row['nextval'];
    }

    /**
     * ツアー配送料金情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function _insertTravelDeliveryCharge($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            'id',
            'travel_terminal_id',
        );

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }

        $query = '
            INSERT
            INTO
                travel_delivery_charge
            (
                id,
                travel_terminal_id,
                created,
                modified
            )
            VALUES
            (
                $1,
                $2,
                CURRENT_TIMESTAMP,
                CURRENT_TIMESTAMP
            );';

        if($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START INSERT travel_delivery_charge #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END INSERT travel_delivery_charge #####");
        if($this->transactionFlg) {
            $db->commit();
        }
        return ($affecteds === 1);
    }

    /**
     * ツアー配送料金情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function _updateTravelDeliveryCharge($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            'travel_terminal_id',
            'id',
        );

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }

        $query = '
            UPDATE
                travel_delivery_charge
            SET
                travel_terminal_id = $1,
                modified           = CURRENT_TIMESTAMP
            WHERE
                id = $2;';

        if($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START UPDATE travel_delivery_charge #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END UPDATE travel_delivery_charge #####");
        if($this->transactionFlg) {
            $db->commit();
        }
        return ($affecteds === 1);
    }

    /**
     *  ツアー配送料金マスタをDBから削除します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 削除するデータ
     */
    public function _deleteTravelDeliveryCharge($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            'id',
        );

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }

        $query = '
            DELETE
            FROM
                travel_delivery_charge
            WHERE
                id = $1;';

        if($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START DELETE travel_delivery_charge #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug($affecteds);
        Sgmov_Component_Log::debug("####### END DELETE travel_delivery_charge #####");
        if($this->transactionFlg) {
            $db->commit();
        }
        return ($affecteds === 1);
    }

    /**
     *  ツアー配送料金マスタをDBから取得します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param type $data 条件データ
     * @param array $data 取得するデータ
     * @return array 条件データに該当するデータ
     * @throws Sgmov_Component_Exception
     */
    public function fetchTravelDeliveryChargeLimit($db, $data) {
        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            'id',
        );

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }

        $query = "
            SELECT *
            FROM travel_delivery_charge
            WHERE
              id = $1
            AND
              dcruse_flg in ('0','1')
            LIMIT 1
            OFFSET 0";

        $result = $db->executeQuery($query, $params);
        // 引数のidが存在しない場合、エラーで止まってしまうため、@で回避($row はfalseになる)
        $row = @$result->get(0);
        return $row;

    }
}
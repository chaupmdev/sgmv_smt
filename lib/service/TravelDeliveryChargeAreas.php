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
 * ツアー配送料金エリア情報を扱います。
 *
 * @package Service
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_TravelDeliveryChargeAreas {

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
     * ツアー配送料金IDとエリアIDをキーに、DBから存在する有効なレコード数（該当エリアID件数）を取得し、返します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return count 該当エリアID情報数
     */
    public function countProvinces($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array('travel_delivery_charge_id', 'travel_areas_provinces_id');

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }
        $query = "
            SELECT
                COUNT(travel_areas_provinces_id)
            FROM
                travel_delivery_charge_areas
            WHERE
                travel_delivery_charge_id = $1
            AND
                travel_areas_provinces_id = $2
            AND
                dcruse_flg in ('0','1')
        ";

        $result = $db->executeQuery($query, $params);

        $row = $result->get(0);
        $res = $row['count'];

        return $res;
    }

    /**
     * ツアー配送料金エリアマスタをDBから取得し、配送料金を返します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return 配送料金
     */
    public function fetchDeliveryCharge($db, $data, $reqFlg = 2) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array('travel_terminal_id', 'prefecture_id');

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }
        $params[] = $reqFlg;
        $query = "
            SELECT
                travel_delivery_charge_areas.delivery_charg
            FROM
                travel_delivery_charge_areas
                INNER JOIN
                travel_delivery_charge
                ON
                    travel_delivery_charge_areas.travel_delivery_charge_id = travel_delivery_charge.id
                AND travel_delivery_charge.travel_terminal_id              = $1
                INNER JOIN
                travel_provinces
                ON
                    travel_delivery_charge_areas.travel_areas_provinces_id = travel_provinces.id
                INNER JOIN
                travel_provinces_prefectures
                ON
                    travel_provinces.id = travel_provinces_prefectures.provinces_id
                AND prefecture_id       = $2
            WHERE
                travel_delivery_charge_areas.dcruse_flg in ('0','1')
            AND 
                travel_delivery_charge_areas.req_flg = $3
            AND
                travel_delivery_charge.dcruse_flg in ('0','1')
            AND
                travel_provinces.dcruse_flg in ('0','1')
            AND
                travel_provinces_prefectures.dcruse_flg in ('0','1')
            ORDER BY
                travel_delivery_charge_areas.travel_delivery_charge_id,
                travel_delivery_charge_areas.travel_areas_provinces_id
            LIMIT 1 OFFSET 0";

        $query = preg_replace('/\s+/u', ' ', trim($query));

        $result = $db->executeQuery($query, $params);
        $row = $result->get(0);
        return $row['delivery_charg'];
    }

    /**
     * ツアー配送料金エリアマスタをDBから取得し、配送料金を返します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return 配送料金
     */
    public function fetchDeliveryChargeNewCharge($db, $data, $reqFlg = 2) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array('travel_terminal_id', 'prefecture_id');

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }
        $params[] = $reqFlg;
        $query = "
            SELECT
                travel_delivery_charge_areas.delivery_charg
            FROM
                travel_delivery_charge_areas
                INNER JOIN
                travel_delivery_charge
                ON
                    travel_delivery_charge_areas.travel_delivery_charge_id = travel_delivery_charge.id
                AND travel_delivery_charge.travel_terminal_id              = $1
                INNER JOIN
                travel_provinces_n
                ON
                    travel_delivery_charge_areas.travel_areas_provinces_id = travel_provinces_n.id
                INNER JOIN
                travel_provinces_prefectures_n
                ON
                    travel_provinces_n.id = travel_provinces_prefectures_n.provinces_id
                AND prefecture_id       = $2
            WHERE
                travel_delivery_charge_areas.dcruse_flg in ('0','1')
            AND 
                travel_delivery_charge_areas.req_flg = $3
            AND
                travel_delivery_charge.dcruse_flg in ('0','1')
            AND
                travel_provinces_n.dcruse_flg in ('0','1')
            AND
                travel_provinces_prefectures_n.dcruse_flg in ('0','1')
            ORDER BY
                travel_delivery_charge_areas.travel_delivery_charge_id,
                travel_delivery_charge_areas.travel_areas_provinces_id
            LIMIT 1 OFFSET 0";

        $query = preg_replace('/\s+/u', ' ', trim($query));

        $result = $db->executeQuery($query, $params);
        $row = $result->get(0);
        return $row['delivery_charg'];
    }

    /**
     * ツアー配送料金エリアマスタをDBから取得し、配送料金を返します。
     * 2019/02/04 atc（マスタメンテナンス）系からのみ使用されているので新料金マスタ対応は不要とする。
     * @param Sgmov_Component_DB $db DB接続
     * @return 配送料金
     */
    public function fetchDeliveryChargeAddProvinces($db, $data, $reqFlg = 2) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array('travel_terminal_id');

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }
        $params[] = $reqFlg;
        $query = "
            SELECT
                travel_provinces.id       AS ID,
                travel_provinces.cd       AS CD,
                travel_provinces.name     AS NAME,
                prefectures.name          AS PREFECTURE_NAME,
                travel_delivery_charge_areas.delivery_charg,
                travel_delivery_charge.id AS travel_delivery_charge_id
            FROM
                travel_delivery_charge_areas
                INNER JOIN
                travel_delivery_charge
                ON
                    travel_delivery_charge_areas.travel_delivery_charge_id = travel_delivery_charge.id
                AND travel_delivery_charge.travel_terminal_id              = $1
                INNER JOIN
                travel_provinces
                ON
                    travel_delivery_charge_areas.travel_areas_provinces_id = travel_provinces.id
                INNER JOIN
                travel_provinces_prefectures
                ON
                    travel_provinces.id = travel_provinces_prefectures.provinces_id
                LEFT OUTER JOIN
                prefectures
                ON
                    travel_provinces_prefectures.prefecture_id = prefectures.prefecture_id
            WHERE
                travel_delivery_charge_areas.dcruse_flg in ('0','1')
            AND 
                travel_delivery_charge_areas.req_flg = $2
            AND
                travel_delivery_charge.dcruse_flg in ('0','1')
            AND
                travel_provinces.dcruse_flg in ('0','1')
            AND
                travel_provinces_prefectures.dcruse_flg in ('0','1')
            ORDER BY
                travel_delivery_charge_areas.travel_delivery_charge_id,
                travel_delivery_charge_areas.travel_areas_provinces_id";

        $query = preg_replace('/\s+/u', ' ', trim($query));

        $ids                        = array();
        $cds                        = array();
        $names                      = array();
        $prefecture_names           = array();
        $delivery_chargs            = array();
        $travel_delivery_charge_ids = array();
        $old_id = null;

        $result = $db->executeQuery($query, $params);
        for ($i = 0, $j = -1; $i < $result->size(); ++$i) {
            $row = $result->get($i);
            if ($old_id === $row['id']) {
                $prefecture_names[$j][] = $row['prefecture_name'];
                continue;
            }
            $old_id = $row['id'];
            $ids[++$j] = $row['id'];
            $cds[$j]   = $row['cd'];
            $names[$j] = $row['name'];
            $prefecture_names[$j]           = array($row['prefecture_name']);
            $delivery_chargs[$row['id']]    = $row['delivery_charg'];
            $travel_delivery_charge_ids[$j] = $row['travel_delivery_charge_id'];
        }

        return array(
            'ids'                        => $ids,
            'cds'                        => $cds,
            'names'                      => $names,
            'prefecture_names'           => $prefecture_names,
            'delivery_chargs'            => $delivery_chargs,
            'travel_delivery_charge_ids' => $travel_delivery_charge_ids,
        );
    }

    /**
     * ツアー配送料金エリア情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function _insertTravelDeliveryChargeAreas($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            'travel_delivery_charge_id',
            'travel_areas_provinces_id',
            'delivery_charg',
        );

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }

        if(empty($params[2])) { // 料金：バッグ・スーツケース （1個当たり）
            // 空文字が設定されてしまうと、delivery_charg は integer 型なのでエラーが発生してしまうため null を設定
            $params[2] = NULL;
        }

        $query = '
            INSERT
            INTO
                travel_delivery_charge_areas
            (
                travel_delivery_charge_id,
                travel_areas_provinces_id,
                delivery_charg,
                created,
                modified
            )
            VALUES
            (
                $1,
                $2,
                $3,
                CURRENT_TIMESTAMP,
                CURRENT_TIMESTAMP
            );';

        if($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START INSERT travel_delivery_charge_areas #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END INSERT travel_delivery_charge_areas #####");
        if($this->transactionFlg) {
            $db->commit();
        }
        return ($affecteds === 1);
    }

    /**
     * ツアー配送料金エリア情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function _updateTravelDeliveryChargeAreas($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            'delivery_charg',
            'travel_delivery_charge_id',
            'travel_areas_provinces_id',
        );

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }

        if(empty($params[0])) { // 料金：バッグ・スーツケース （1個当たり）
            // 空文字が設定されてしまうと、delivery_charg は integer 型なのでエラーが発生してしまうため null を設定
            $params[0] = NULL;
        }

        $query = '
            UPDATE
                travel_delivery_charge_areas
            SET
                delivery_charg = $1,
                modified       = CURRENT_TIMESTAMP
            WHERE
                travel_delivery_charge_id = $2
            AND travel_areas_provinces_id = $3;';

        if($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START UPDATE travel_delivery_charge_areas #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END UPDATE travel_delivery_charge_areas #####");
        if($this->transactionFlg) {
            $db->commit();
        }
        return ($affecteds === 1);
    }

    /**
     * ツアー配送料金エリア情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function _insertSelectTravelDeliveryChargeAreas($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            'travel_delivery_charge_to_id',
            'travel_terminal_from_id',
            'travel_terminal_from_id',
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
                travel_delivery_charge_areas
            (
                travel_delivery_charge_id,
                travel_areas_provinces_id,
                delivery_charg,
                created,
                modified
            )
            SELECT
                $1,
                TDCA_FROM.travel_areas_provinces_id,
                TDCA_FROM.delivery_charg,
                CURRENT_TIMESTAMP,
                CURRENT_TIMESTAMP
            FROM
                travel_delivery_charge_areas AS TDCA_FROM
            INNER JOIN
            travel_delivery_charge
            ON
                TDCA_FROM.travel_delivery_charge_id = travel_delivery_charge.id
            WHERE
                travel_delivery_charge.travel_terminal_id = $2
            AND NOT EXISTS (
                SELECT
                    1
                FROM
                    travel_delivery_charge_areas AS TDCA_TEMP
                WHERE
                    travel_delivery_charge.travel_terminal_id = $3
             );';

        if($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START INSERT travel_delivery_charge_areas #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END INSERT travel_delivery_charge_areas #####");
        if($this->transactionFlg) {
            $db->commit();
        }
        return ($affecteds > 0);
    }

    /**
     * ツアー配送料金エリア情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function _updateSelectTravelDeliveryChargeAreas($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            'travel_terminal_from_id',
            'travel_delivery_charge_to_id',
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
                travel_delivery_charge_areas AS TDCA_TO
            SET
                delivery_charg = TDCA_FROM.delivery_charg,
                modified       = CURRENT_TIMESTAMP
            FROM
                (
                    SELECT
                        *
                    FROM
                        travel_delivery_charge_areas AS TDCA_TEMP
                    INNER JOIN
                    travel_delivery_charge
                    ON
                        TDCA_TEMP.travel_delivery_charge_id = travel_delivery_charge.id
                    WHERE
                        travel_delivery_charge.travel_terminal_id = $1
                ) AS TDCA_FROM

            WHERE
                TDCA_TO.travel_delivery_charge_id = $2
            AND TDCA_TO.travel_areas_provinces_id = TDCA_FROM.travel_areas_provinces_id;';

        if($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START UPDATE travel_delivery_charge_areas #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END UPDATE travel_delivery_charge_areas #####");
        if($this->transactionFlg) {
            $db->commit();
        }
        return ($affecteds > 0);
    }

    /**
     *  ツアー配送料金エリアマスタをDBから削除します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 削除するデータ
     */
    public function _deleteTravelDeliveryChargeAreas($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            'travel_delivery_charge_id',
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
                travel_delivery_charge_areas
            WHERE
                travel_delivery_charge_id = $1;';

        if($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START DELETE travel_delivery_charge_areas #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug($affecteds);
        Sgmov_Component_Log::debug("####### END DELETE travel_delivery_charge_areas #####");
        if($this->transactionFlg) {
            $db->commit();
        }
        return ($affecteds > 0);
    }
}
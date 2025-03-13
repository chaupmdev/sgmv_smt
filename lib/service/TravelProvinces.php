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
 * ツアーエリア情報を扱います。
 *
 * @package Service
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_TravelProvinces {

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
     * ツアーエリアマスタをDBから取得し、キーにツアーエリアIDを値にツアーエリア名を持つ配列を返します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array ['ids'] ツアーエリアIDの文字列配列、['names'] ツアーエリア名の文字列配列
     */
    public function fetchTravelProvinces($db) {

        $query = "
            SELECT
                travel_provinces.id   AS id,
                travel_provinces.cd   AS cd,
                travel_provinces.name AS name,
                prefectures.name      AS PREFECTURE_NAME
            FROM
                travel_provinces
                LEFT OUTER JOIN
                travel_provinces_prefectures
                ON
                    travel_provinces.id = travel_provinces_prefectures.provinces_id
                LEFT OUTER JOIN
                prefectures
                ON
                    travel_provinces_prefectures.prefecture_id = prefectures.prefecture_id
            WHERE
                travel_provinces.dcruse_flg in ('0','1')
            AND
                travel_provinces_prefectures.dcruse_flg in ('0','1')
            ORDER BY
                travel_provinces.cd,
                travel_provinces.id,
                travel_provinces_prefectures.prefecture_id";

        $ids = array();
        $cds = array();
        $names = array();
        $prefecture_names = array();
        $old_id = null;

        $result = $db->executeQuery($query);
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
            $prefecture_names[$j] = array($row['prefecture_name']);
        }

        return array(
            'ids'              => $ids,
            'cds'              => $cds,
            'names'            => $names,
            'prefecture_names' => $prefecture_names,
        );
    }

    /**
     * ツアーエリアマスタをDBから取得し、キーにツアーエリアIDを値にツアーエリア名を持つ配列を返します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array
     */
    public function fetchTravelProvinceLimit($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array('id');

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
                id,
                cd,
                name
            FROM
                travel_provinces
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

    /**
     * ツアーエリア情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function select_id($db) {

        $query    = 'SELECT max(id) + 1 AS id, nextval($1) FROM travel_provinces;';
        $params   = array();
        $params[] = 'travel_provinces_id_seq';

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
     * ツアーエリア情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function _insertTravelProvince($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            'id',
            'cd',
            'name',
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
                travel_provinces
            (
                id,
                cd,
                name,
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
        Sgmov_Component_Log::debug("####### START INSERT travel_provinces #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END INSERT travel_provinces #####");
        if($this->transactionFlg) {
            $db->commit();
        }
        return ($affecteds === 1);
    }

    /**
     * ツアーエリア情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function _updateTravelProvince($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            'cd',
            'name',
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
                travel_provinces
            SET
                cd       = $1,
                name     = $2,
                modified = CURRENT_TIMESTAMP
            WHERE
                id = $3;';

        if($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START UPDATE travel_provinces #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END UPDATE travel_provinces #####");
        if($this->transactionFlg) {
            $db->commit();
        }
        return ($affecteds === 1);
    }

    /**
     * ツアーエリア情報をDBから削除します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 削除するデータ
     */
    public function _deleteTravelProvince($db, $data) {

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
                travel_provinces
            WHERE
                id = $1;';

        if($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START DELETE travel_provinces #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END DELETE travel_provinces #####");
        if($this->transactionFlg) {
            $db->commit();
        }
        return ($affecteds === 1);
    }
}
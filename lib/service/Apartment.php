<?php

/**
 * include files.
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(false);

/**
 * マンションマスタを扱います。
 *
 * @package Service
 * @author (SMT)
 *
 */
class Sgmov_Service_Apartment {

    /**
     * マンションマスタをDBから取得し、キーにマンションIDを、 値にマンション名を持つ配列を返します。
     *
     * @param Sgmov_Component_DB $db データベース接続 オブジェクト
     * @return array ["ids"] マンションID の文字列配列、 ["names"] マンション名の文字列配列
     */
    public function fetchApartments($db, $space = false) {

        $query = '
            SELECT
                "id",
                "cd",
                "name",
                "zip_code",
                "address",
                "agency_cd"
            FROM
                "apartment"
            ORDER BY
                "cd" ASC,
                "id" ASC';

        $ids        = array();
        $cds        = array();
        $names      = array();
        $zip_codes  = array();
        $address    = array();
        $agency_cds = array();

        if ($space) {
            $ids[]        = '';
            $cds[]        = '';
            $names[]      = '';
            $zip_codes[]  = '';
            $address[]    = '';
            $agency_cds[] = '';
        }

        $result = $db->executeQuery($query);
        for ($i = 0; $i < $result->size(); ++$i) {
            $row = $result->get($i);
            $ids[]        = $row['id'];
            $cds[]        = $row['cd'];
            $names[]      = $row['name'];
            $zip_codes[]  = $row['zip_code'];
            $address[]    = $row['address'];
            $agency_cds[] = $row['agency_cd'];
        }

        return array(
            'ids'        => $ids,
            'cds'        => $cds,
            'names'      => $names,
            'zip_codes'  => $zip_codes,
            'address'    => $address,
            'agency_cds' => $agency_cds,
        );
    }

    /**
     *
     * @param Sgmov_Component_DB $db
     * @return Sgmov_Component_DBResult
     */
    public function fetchApartment($db, $criteria_values) {

        if (!is_array($criteria_values)) {
            $criteria_values = array('id' => $criteria_values);
        }

        $params           = array();
        $criteria_strings = array();

        // array_key_exists は使用できない環境..?
		$key = 'id';	if (key_exists($key, $criteria_values))	{	$params[] = $criteria_values[$key];	$criteria_strings[] = "\"$key\" = $" . count($params);	}
		$key = 'cd';	if (key_exists($key, $criteria_values))	{	$params[] = $criteria_values[$key];	$criteria_strings[] = "\"$key\" = $" . count($params);	}

		$query  = "SELECT * FROM \"apartment\"";
		if (!empty($criteria_strings)) {
            $query .= " WHERE " . implode(" AND ", $criteria_strings) . "";
        }
		$query .= " ORDER BY \"id\" ASC;";

		Sgmov_Component_Log::debug('step 8-3.');

		$result = $db->executeQuery($query, $params);

		Sgmov_Component_Log::debug('step 8-4.');

		return $result;
	}

    /**
     * マンションマスタをDBから取得し、住所を返します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return 住所
     */
    public function fetchAddress($db, $data) {

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
        $query = 'SELECT zip_code, address'
                .' FROM apartment'
                .' WHERE id = $1'
                .' ORDER BY id'
                .' LIMIT 1'
                .' OFFSET 0';

        $result = $db->executeQuery($query, $params);
        $row = $result->get(0);
        return $row;
    }

    /**
     * マンションマスタをDBから取得し、キーにマンションIDを値に持つ配列を返します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array
     */
    public function fetchApartmentLimit($db, $data) {

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

        $query = '
            SELECT
                apartment.id,
                apartment.cd,
                apartment.name,
                apartment.zip_code,
                SUBSTR(apartment.zip_code, 1, 3) AS zip1,
                SUBSTR(apartment.zip_code, 4, 4) AS zip2,
                apartment.address,
                apartment.agency_cd
            FROM
                apartment
            WHERE
                apartment.id = $1
            ORDER BY
                apartment.cd,
                apartment.id
            LIMIT 1
            OFFSET 0;';

        $result = $db->executeQuery($query, $params);
        $row = $result->get(0);
        return $row;
    }

    /**
     * マンション情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function select_id($db) {

        $query    = 'SELECT max(id) + 1 AS id, nextval($1) FROM apartment;';
        $params   = array();
        $params[] = 'apartment_id_seq';

        $db->begin();
        $data = $db->executeQuery($query, $params);
        $db->commit();
        $row = $data->get(0);

        if ($row['id'] > $row['nextval']) {
            return $row['id'];
        }

        return $row['nextval'];
    }

    /**
     * マンション情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function _insertApartment($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            'id',
            'cd',
            'name',
            'zip_code',
            'address',
            'agency_cd',
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
                apartment
            (
                id,
                cd,
                name,
                zip_code,
                address,
                agency_cd,
                created,
                modified
            )
            VALUES
            (
                $1,
                $2,
                $3,
                $4,
                $5,
                $6,
                CURRENT_TIMESTAMP,
                CURRENT_TIMESTAMP
            );';

        $db->begin();
        Sgmov_Component_Log::debug("####### START INSERT apartment #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END INSERT apartment #####");
        $db->commit();
        return ($affecteds === 1);
    }

    /**
     * マンション情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function _updateApartment($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            'cd',
            'name',
            'zip_code',
            'address',
            'agency_cd',
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
                apartment
            SET
                cd        = $1,
                name      = $2,
                zip_code  = $3,
                address   = $4,
                agency_cd = $5,
                modified  = CURRENT_TIMESTAMP
            WHERE
                id = $6;';

        $db->begin();
        Sgmov_Component_Log::debug("####### START UPDATE apartment #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END UPDATE apartment #####");
        $db->commit();
        return ($affecteds === 1);
    }

    /**
     * マンションマスタをDBから削除します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 削除するデータ
     */
    public function _deleteApartment($db, $data) {

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
                apartment
            WHERE
                id = $1;';

        $db->begin();
        Sgmov_Component_Log::debug("####### START DELETE apartment #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END DELETE apartment #####");
        $db->commit();
        return ($affecteds === 1);
    }
}
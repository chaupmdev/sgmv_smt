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
 * クルーズリピータ情報を扱います。
 *
 * @package Service
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_CruiseRepeater {
    /**
     * クルーズリピータ情報をDBから取得します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return クルーズリピータ
     */
    public function fetchCruiseRepeaters($db) {

        $query = "
            SELECT
                cruise_repeater.tel,
                cruise_repeater.zip,
                cruise_repeater.address,
                cruise_repeater.name,
                cruise_repeater.travel_cd,
                cruise_repeater.client_no
            FROM
                cruise_repeater
            ORDER BY
                cruise_repeater.tel";

        $tels = array();
        $zips = array();
        $names = array();
        $travel_cds = array();
        $client_nos = array();

        $result = $db->executeQuery($query);
        for ($i = 0; $i < $result->size(); ++$i) {
            $row = $result->get($i);
            $tels[] = $row['tel'];
            $zips[] = $row['zip'];
            $addresss[] = $row['address'];
            $names[] = $row['name'];
            $travel_cds[] = $row['travel_cd'];
            $client_nos[] = $row['client_no'];
        }

        return array(
            'tels'       => $tels,
            'zips'       => $zips,
            'names'      => $names,
            'travel_cds' => $travel_cds,
            'client_nos' => $client_nos,
        );
    }

    /**
     * クルーズリピータ情報をDBから取得します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return クルーズリピータ
     */
    public function fetchCruiseRepeaterLimit($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            'tel',
            'zip',
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
            SELECT
                cruise_repeater.tel,
                cruise_repeater.zip,
                cruise_repeater.address,
                cruise_repeater.name,
                cruise_repeater.travel_cd,
                cruise_repeater.client_no
            FROM
                cruise_repeater
            WHERE
                cruise_repeater.tel     = $1
                AND cruise_repeater.zip = $2
            ORDER BY
                cruise_repeater.tel';

        $tels = array();
        $zips = array();
        $names = array();
        $travel_cds = array();
        $client_nos = array();

        $result = $db->executeQuery($query, $params);
        for ($i = 0; $i < $result->size(); ++$i) {
            $row = $result->get($i);
            $tels[] = $row['tel'];
            $zips[] = $row['zip'];
            $addresss[] = $row['address'];
            $names[] = $row['name'];
            $travel_cds[] = $row['travel_cd'];
            $client_nos[] = $row['client_no'];
        }

        return array(
            'tels'       => $tels,
            'zips'       => $zips,
            'names'      => $names,
            'travel_cds' => $travel_cds,
            'client_nos' => $client_nos,
        );
    }

    /**
     * クルーズリピータ情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function _insertCruiseRepeater($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            'tel',
            'zip',
            'address',
            'name',
            'travel_cd',
            'client_no',
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
                cruise_repeater
            (
                tel,
                zip,
                address,
                name,
                travel_cd,
                client_no,
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
        Sgmov_Component_Log::debug("####### START INSERT cruise_repeater #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END INSERT cruise_repeater #####");
        $db->commit();
        return ($affecteds === 1);
    }

    /**
     * クルーズリピータ情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function _updateCruiseRepeater($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            'zip',
            'address',
            'name',
            'travel_cd',
            'client_no',
            'tel',
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
                cruise_repeater
            SET
                zip       = $1,
                address   = $2,
                name      = $3,
                travel_cd = $4,
                client_no = $5,
                modified  = CURRENT_TIMESTAMP
            WHERE
                tel = $6;';

        $db->begin();
        Sgmov_Component_Log::debug("####### START UPDATE cruise_repeater #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END UPDATE cruise_repeater #####");
        $db->commit();
        return ($affecteds === 1);
    }

    /**
     *  クルーズリピータ情報をDBから削除します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 削除するデータ
     */
    public function _deleteCruiseRepeater($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            'tel',
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
                cruise_repeater
            WHERE
                tel = $1;';

        $db->begin();
        Sgmov_Component_Log::debug("####### START DELETE cruise_repeater #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END DELETE cruise_repeater #####");
        $db->commit();
        return ($affecteds === 1);
    }
}
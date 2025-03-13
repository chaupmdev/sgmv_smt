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
 * ツアーエリア都道府県情報を扱います。
 *
 * @package Service
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_TravelProvincesPrefectures {

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
     * ツアーエリア都道府県情報をDBから取得し返却します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data
     * @return array
     */
    public function countTravelProvincesPrefectures($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array('provinces_id', 'prefecture_id');

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
                COUNT(*)
            FROM
                travel_provinces_prefectures
            WHERE
                provinces_id = $1
            AND
                prefecture_id = $2
            AND
                dcruse_flg in ('0','1')
            ";

        $result = $db->executeQuery($query, $params);
        $row = $result->get(0);
        return $row['count'];
    }

    /**
     * ツアーエリア都道府県情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function _insertTravelProvincesPrefecture($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            'provinces_id',
            'prefecture_id',
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
                travel_provinces_prefectures
            (
                provinces_id,
                prefecture_id,
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
        Sgmov_Component_Log::debug("####### START INSERT travel_provinces_prefectures #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END INSERT travel_provinces_prefectures #####");
        if($this->transactionFlg) {
            $db->commit();
        }
        return ($affecteds === 1);
    }

    /**
     * ツアーエリア都道府県情報をDBから削除します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 削除するデータ
     */
    public function _deleteTravelProvincesPrefecture($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            'provinces_id',
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
                travel_provinces_prefectures
            WHERE
                provinces_id = $1;';

        if($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START DELETE travel_provinces_prefectures #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END DELETE travel_provinces_prefectures #####");
        if($this->transactionFlg) {
            $db->commit();
        }
        return ($affecteds > 0);
    }

    /**
     * ツアーエリア都道府県情報をDBから削除します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 削除するデータ
     */
    public function _deleteAllTravelProvincesPrefecture($db) {

        $query = '
            DELETE
            FROM
                travel_provinces_prefectures
            ';

        if($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START DELETE travel_provinces_prefectures #####");
        $affecteds = $db->executeUpdate($query);
        Sgmov_Component_Log::debug("####### END DELETE travel_provinces_prefectures #####");
        if($this->transactionFlg) {
            $db->commit();
        }
        return ($affecteds > 0);
    }
}
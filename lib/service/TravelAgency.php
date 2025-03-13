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
 * ツアー会社情報を扱います。
 *
 * @package Service
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_TravelAgency {

    //通常版
    const CONVENIENCE_STORE_ADD_DAYS_STUJO = 7;//７日前から１０日前に変更したものから7日前に変更
    const CREDIT_CARD_ADD_DAYS_STUJO       = 7;//７日前から１０日前に変更->１０日前のままではなく7日前に変更
    //那覇版
    const CONVENIENCE_STORE_ADD_DAYS_NAHA = 7;//７日前から１０日前に変更したものから7日前に変更
    const CREDIT_CARD_ADD_DAYS_NAHA       = 7;//７日前から１０日前に変更->１０日前のままではなく7日前に変更

    const SITE_FLG_TSUJO_BAN = '1'; //通常版
    const SITE_FLG_NAHA_BAN = '2'; //那覇版
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
     * ツアー会社マスタをDBから取得し、キーにツアー会社IDを値に船名を持つ配列を返します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array ['ids'] ツアー会社IDの文字列配列、['names'] 船名の文字列配列
     */
    public function fetchTravelAgency($db, $reqFlg = 2, $siteFlg = '1', $convenience = false) {

        //$add_days = $convenience ? self::CONVENIENCE_STORE_ADD_DAYS : self::CREDIT_CARD_ADD_DAYS;
        if ($siteFlg == self::SITE_FLG_TSUJO_BAN) {
            $add_days = $convenience ? self::CONVENIENCE_STORE_ADD_DAYS_STUJO : self::CREDIT_CARD_ADD_DAYS_STUJO;
        } else {
            $add_days = $convenience ? self::CONVENIENCE_STORE_ADD_DAYS_NAHA : self::CREDIT_CARD_ADD_DAYS_NAHA;
        }

        $query = 'SELECT id, name, dcruse_flg'
                .' FROM travel_agency'
                .' INNER JOIN ('
                .' SELECT travel_agency_id'
                .' FROM travel'
                .' WHERE publish_begin_date <= current_date'
                .' AND current_date + ' . pg_escape_string($add_days) . ' <= coalesce(embarkation_date, current_date)'
                .' AND dcruse_flg in (\'0\',\'1\')'
                ." AND req_flg = '{$reqFlg}'"
                ." AND site_flg = '{$siteFlg}'"
                .' GROUP BY travel_agency_id'
                .' ) AS TRAVEL1'
                .' ON travel_agency.id = TRAVEL1.travel_agency_id'
                ." WHERE dcruse_flg in ('0','1') "
                .' ORDER BY cd';
        $ids = array();
        $names = array();
        $dcruse_flgs = array();

        $result = $db->executeQuery($query);
        for ($i = 0; $i < $result->size(); ++$i) {
            $row = $result->get($i);
            $ids[] = $row['id'];
            $names[] = $row['name'];
            $dcruse_flgs[] = $row['dcruse_flg'];
        }

        return array(
            'ids'   => $ids,
            'names' => $names,
            'dcruse_flgs' => $dcruse_flgs,
        );
    }

    /**
     * ツアー会社マスタをDBから取得し、キーにツアー会社IDを値に船名を持つ配列を返します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array ['ids'] ツアー会社IDの文字列配列
     *               ['cds'] ツアー会社コードの文字列配列
     *               ['names'] 船名の文字列配列
     */
    public function fetchTravelAgencies($db) {
        $query = "
            SELECT
                id,
                cd,
                name
            FROM
                travel_agency
            WHERE
                dcruse_flg in ('0','1')
            ORDER BY
                cd,
                id";

        $ids = array();
        $names = array();

        $result = $db->executeQuery($query);
        for ($i = 0; $i < $result->size(); ++$i) {
            $row = $result->get($i);
            $ids[] = $row['id'];
            $cds[] = $row['cd'];
            $names[] = $row['name'];
        }

        return array(
            'ids'   => $ids,
            'cds'   => $cds,
            'names' => $names,
        );
    }

    /**
     * ツアー会社マスタをDBから取得し、キーにツアー会社IDを値に船名を持つ配列を返します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array
     */
    public function fetchTravelAgencyLimit($db, $data) {

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
                travel_agency
            WHERE
                id = $1
            AND
                dcruse_flg in ('0','1')
            ORDER BY
                cd,
                id
            LIMIT 1
            OFFSET 0";

        $result = $db->executeQuery($query, $params);
        // 引数のidが存在しない場合、エラーで止まってしまうため、@で回避($row はfalseになる)
        $row = @$result->get(0);
        return $row;
    }

    /**
     * ツアー会社情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function select_id($db) {

        $query    = 'SELECT max(id) + 1 AS id, nextval($1) FROM travel_agency;';
        $params   = array();
        $params[] = 'travel_agency_id_seq';

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
     * ツアー会社情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function _insertTravelAgency($db, $data) {

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
                travel_agency
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
        Sgmov_Component_Log::debug("####### START INSERT travel_agency #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END INSERT travel_agency #####");
        if($this->transactionFlg) {
            $db->commit();
        }
        return ($affecteds === 1);
    }

    /**
     * ツアー会社情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function _updateTravelAgency($db, $data) {

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
                travel_agency
            SET
                cd       = $1,
                name     = $2,
                modified = CURRENT_TIMESTAMP
            WHERE
                id = $3;';

        if($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START UPDATE travel_agency #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END UPDATE travel_agency #####");
        if($this->transactionFlg) {
            $db->commit();
        }
        return ($affecteds === 1);
    }

    /**
     * ツアー会社情報をDBから削除します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 削除するデータ
     */
    public function _deleteTravelAgency($db, $data) {

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
                travel_agency
            WHERE
                id = $1;';
        if($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START DELETE travel_agency #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END DELETE travel_agency #####");
        if($this->transactionFlg) {
            $db->commit();
        }
        return ($affecteds === 1);
    }
}
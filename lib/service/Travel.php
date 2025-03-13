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
 * ツアー情報を扱います。
 *
 * @package Service
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_Travel {

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
     * ツアーマスタをDBから取得し、キーにツアーIDを値にツアー名(乗船日)を持つ配列を返します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param $data travel_agency_id
     * @return array ['ids'] ツアーIDの文字列配列、['names'] ツアー名(乗船日)の文字列配列
     */
    public function fetchTravel($db, $data, $reqFlg = 2, $siteFlg = '1', $convenience = false) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array('travel_agency_id');

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }
        $params[] = $reqFlg;
        $params[] = $siteFlg;
        //$add_days = $convenience ? self::CONVENIENCE_STORE_ADD_DAYS : self::CREDIT_CARD_ADD_DAYS;
        if ($siteFlg == self::SITE_FLG_TSUJO_BAN) {
            $add_days = $convenience ? self::CONVENIENCE_STORE_ADD_DAYS_STUJO : self::CREDIT_CARD_ADD_DAYS_STUJO;
        } else {
            $add_days = $convenience ? self::CONVENIENCE_STORE_ADD_DAYS_NAHA : self::CREDIT_CARD_ADD_DAYS_NAHA;
        }

        $query = 'SELECT id, name, embarkation_date'
                .' FROM travel'
                .' WHERE travel_agency_id = $1'
                .' AND publish_begin_date <= current_date'
                .' AND current_date + ' . pg_escape_string($add_days) . ' <= coalesce(embarkation_date, current_date)'
                ." AND dcruse_flg in ('0','1') "
                ." AND req_flg = $2"
                ." AND site_flg = $3"
                .' ORDER BY embarkation_date, cd, name, publish_begin_date';

        $ids               = array();
        $names             = array();
        $embarkation_dates = array();

        $result = $db->executeQuery($query, $params);
        for ($i = 0; $i < $result->size(); ++$i) {
            $row = $result->get($i);
            $ids[]               = $row['id'];
            $names[]             = $row['name'];
            $embarkation_dates[] = $row['embarkation_date'];
        }

        return array(
            'ids'               => $ids,
            'names'             => $names,
            'embarkation_dates' => $embarkation_dates,
        );
    }
    
    public function fetchTravelOperator($db) {
        $query = 'SELECT  id, operator_id '
                .' FROM travel_operator '
                . 'WHERE  start_date <= now()::date and end_date >= now()::date  '
                .' ORDER BY id';

        $ids               = array();
        $names             = array();

        $result = $db->executeQuery($query);
        for ($i = 0; $i < $result->size(); ++$i) {
            $row = $result->get($i);
            $ids[]               = $row['operator_id'];
            $names[]             = $row['operator_id'];
            
        }

        return array(
            'ids'               => $ids,
            'names'             => $names,
        );
    }
    
    public function fetchTravelPhoneNumberByOperatorId($db, $operatorId) {
        $query = 'SELECT  id, operator_phone_number '
                .' FROM travel_operator '
                . 'WHERE  start_date <= now()::date AND end_date >= now()::date AND  operator_id = $1'
                .' ORDER BY id';

        
        $params = array();
        $row = array();
        $params[] = $operatorId;
        
        $result = $db->executeQuery($query, $params);
        if ($result->size() > 0) {
            $row = $result->get(0);
        }
        return $row;
    }
    

    /**
     * ツアーマスタをDBから取得し、乗船日を返します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return string 乗船日
     */
    public function fetchEmbarkationDate($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array('travel_id');

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }

        $query = 'SELECT embarkation_date'
                .' FROM travel'
                .' WHERE id = $1'
                ." AND dcruse_flg in ('0','1') "
                .' ORDER BY embarkation_date'
                .' LIMIT 1'
                .' OFFSET 0';

        $result = $db->executeQuery($query, $params);
        $row = $result->get(0);
        return $row['embarkation_date'];
    }

    /**
     * ツアーマスタをDBから取得し、キーにツアーIDを値にツアー名(乗船日)を持つ配列を返します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array ['ids'] ツアーIDの文字列配列
     *               ['names'] ツアー名(乗船日)の文字列配列
     */
    public function fetchTravels($db, $reqFlg = 2, $data = null) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array('travel_agency_id');

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
                id,
                cd,
                name,
                travel_agency_id,
                round_trip_discount,
                repeater_discount,
                TO_CHAR(embarkation_date, 'YYYY年MM月DD日')   AS EMBARKATION_DATE,
                TO_CHAR(publish_begin_date, 'YYYY年MM月DD日') AS PUBLISH_BEGIN_DATE
            FROM
                travel
            WHERE
                travel_agency_id = $1
            AND
                req_flg = $2
            AND
                dcruse_flg in ('0','1')
            ORDER BY
                embarkation_date,
                cd,
                name,
                publish_begin_date,
                id";

        $ids                  = array();
        $cds                  = array();
        $names                = array();
        $travel_agency_ids    = array();
        $round_trip_discounts = array();
        $repeater_discounts   = array();
        $embarkation_dates    = array();
        $publish_begin_dates  = array();

        $result = $db->executeQuery($query, $params);
        for ($i = 0; $i < $result->size(); ++$i) {
            $row = $result->get($i);
            $ids[]                  = $row['id'];
            $cds[]                  = $row['cd'];
            $names[]                = $row['name'];
            $travel_agency_ids[]    = $row['travel_agency_id'];
            $round_trip_discounts[] = $row['round_trip_discount'];
            $repeater_discounts[]   = $row['repeater_discount'];
            $embarkation_dates[]    = $row['embarkation_date'];
            $publish_begin_dates[]  = $row['publish_begin_date'];
        }

        return array(
            'ids'                  => $ids,
            'cds'                  => $cds,
            'names'                => $names,
            'travel_agency_ids'    => $travel_agency_ids,
            'round_trip_discounts' => $round_trip_discounts,
            'repeater_discounts'   => $repeater_discounts,
            'embarkation_dates'    => $embarkation_dates,
            'publish_begin_dates'  => $publish_begin_dates,
        );
    }

    /**
     * ツアーマスタをDBから取得し、往復便割引を返します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return 往復便割引
     */
    public function fetchRoundTripDiscount($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array('travel_id');

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }
        $query = 'SELECT round_trip_discount'
                .' FROM travel'
                .' WHERE id = $1'
                ." AND dcruse_flg in ('0','1') "
                .' ORDER BY id'
                .' LIMIT 1'
                .' OFFSET 0';

        $result = $db->executeQuery($query, $params);
        $row = $result->get(0);
        return $row['round_trip_discount'];
    }

    /**
     * ツアーマスタをDBから取得し、割引を返します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return 割引
     */
    public function fetchDiscount($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array('travel_id');

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
                round_trip_discount,
                repeater_discount
            FROM
                travel
            WHERE
                id = $1
            AND
                dcruse_flg in ('0','1')
            LIMIT
                1
            OFFSET
                0";

        $result = $db->executeQuery($query, $params);
        $row = $result->get(0);
        return $row;
    }

    /**
     * ツアーマスタをDBから取得し、ツアー情報を返します。
     *
     * 先頭には空白の項目を含みます。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return ツアー情報
     */
    public function fetchTravelLimit($db, $data) {

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
                travel.id,
                travel.cd,
                travel.name,
                travel.travel_agency_id,
                travel_agency.name                                   AS travel_agency_name,
                travel.round_trip_discount,
                travel.repeater_discount,
                TO_CHAR(travel.embarkation_date, 'YYYY/MM/DD')       AS EMBARKATION_DATE,
                TO_CHAR(travel.embarkation_date, 'YYYY年MM月DD日')   AS EMBARKATION_DATE_JAPANESE,
                TO_CHAR(travel.publish_begin_date, 'YYYY/MM/DD')     AS PUBLISH_BEGIN_DATE,
                TO_CHAR(travel.publish_begin_date, 'YYYY年MM月DD日') AS PUBLISH_BEGIN_DATE_JAPANESE,
                travel.dcruse_flg AS dcruse_flg,
                travel.charge_flg AS charge_flg
            FROM
                travel
                LEFT JOIN travel_agency
                ON
                    travel.travel_agency_id = travel_agency.id
            WHERE
                travel.id = $1
            AND
                travel.dcruse_flg in ('0','1')
            AND
                travel_agency.dcruse_flg in ('0','1')
            ORDER BY
                travel.cd,
                travel.id
            LIMIT 1
            OFFSET 0;";

        $result = $db->executeQuery($query, $params);
        // 引数のidが存在しない場合、エラーで止まってしまうため、@で回避($row はfalseになる)
        $row = @$result->get(0);
        return $row;
    }

    /**
     * ツアー情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function select_id($db) {

        $query    = 'SELECT max(id) + 1 AS id, nextval($1) FROM travel;';
        $params   = array();
        $params[] = 'travel_id_seq';

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
     * ツアー情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function _insertTravel($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            'id',
            'cd',
            'name',
            'travel_agency_id',
            'round_trip_discount',
            'repeater_discount',
            'embarkation_date',
            'publish_begin_date'
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
                travel
            (
                id,
                cd,
                name,
                travel_agency_id,
                round_trip_discount,
                repeater_discount,
                embarkation_date,
                publish_begin_date,
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
                $7,
                $8,
                CURRENT_TIMESTAMP,
                CURRENT_TIMESTAMP
            );';

        if($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START INSERT travel #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END INSERT travel #####");
        if($this->transactionFlg) {
            $db->commit();
        }
        return ($affecteds === 1);
    }

    /**
     * ツアー情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function _updateTravel($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            'cd',
            'name',
            'travel_agency_id',
            'round_trip_discount',
            'repeater_discount',
            'embarkation_date',
            'publish_begin_date',
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
                travel
            SET
                cd                  = $1,
                NAME                = $2,
                travel_agency_id    = $3,
                round_trip_discount = $4,
                repeater_discount   = $5,
                embarkation_date    = $6,
                publish_begin_date  = $7,
                modified            = CURRENT_TIMESTAMP
            WHERE
                id = $8;';

        if($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START UPDATE travel #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END UPDATE travel #####");
        if($this->transactionFlg) {
            $db->commit();
        }
        return ($affecteds === 1);
    }

    /**
     * ツアー情報をDBから削除します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 削除するデータ
     */
    public function _deleteTravel($db, $data) {

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
                travel
            WHERE
                id = $1;';

        if($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START DELETE travel #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END DELETE travel #####");
        if($this->transactionFlg) {
            $db->commit();
        }
        return ($affecteds === 1);
    }
}
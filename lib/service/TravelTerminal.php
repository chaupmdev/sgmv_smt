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
 * ツアー発着地マスタ情報を扱います。
 *
 * @package Service
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_TravelTerminal {

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
     * ツアー発着地マスタをDBから取得し、キーにツアー発着地IDを値にツアー発着地名を持つ配列を返します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array ['ids'] ツアー発着地IDの文字列配列、['names'] ツアー発着地名の文字列配列
     */
    public function fetchTravelTerminals($db, $data, $reqFlg = 2) {

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
        $params[] = $reqFlg;
        $query = "
            SELECT
                travel_terminal.id,
                COALESCE(travel_terminal.cd, '')                                                            AS cd,
                COALESCE(travel_terminal.name, '')                                                          AS name,
                COALESCE(SUBSTR(travel_terminal.zip, 1, 3) || '-' || SUBSTR(travel_terminal.zip, 4, 4), '') AS ZIP,
                COALESCE(prefectures.name, '')                                                              AS prefecture_name,
                COALESCE(travel_terminal.address,'')                                                        AS ADDRESS,
                COALESCE(travel_terminal.building,'')                                                       AS BUILDING,
                COALESCE(travel_terminal.store_name,'')                                                     AS STORE_NAME,
                COALESCE(travel_terminal.tel,'')                                                            AS TEL,
                travel_terminal.terminal_cd,
                COALESCE(TO_CHAR(travel_terminal.departure_date,'YYYY年MM月DD日'), '')                      AS DEPARTURE_DATE,
                COALESCE(TO_CHAR(travel_terminal.departure_time,'HH時MI分'), '')                            AS DEPARTURE_TIME,
                COALESCE(TO_CHAR(travel_terminal.arrival_date,'YYYY年MM月DD日'), '')                        AS ARRIVAL_DATE,
                COALESCE(TO_CHAR(travel_terminal.arrival_time,'HH時MI分'), '')                              AS ARRIVAL_TIME,
                COALESCE(travel_terminal.departure_client_cd,'')                                            AS DEPARTURE_CLIENT_CD,
                COALESCE(travel_terminal.departure_client_branch_cd,'')                                     AS DEPARTURE_CLIENT_BRANCH_CD,
                COALESCE(travel_terminal.arrival_client_cd,'')                                              AS ARRIVAL_CLIENT_CD,
                COALESCE(travel_terminal.arrival_client_branch_cd,'')                                       AS ARRIVAL_CLIENT_BRANCH_CD,
                travel_delivery_charge.id                                                                   AS TRAVEL_DELIVERY_CHARGE_ID
            FROM
                travel_terminal
                LEFT OUTER JOIN
                prefectures
                ON
                    travel_terminal.pref_id = prefectures.prefecture_id
                LEFT OUTER JOIN
                travel_delivery_charge
                ON
                    travel_terminal.id = travel_delivery_charge.travel_terminal_id
            WHERE
                travel_terminal.travel_id = $1
            AND 
                travel_terminal.req_flg = $2
            AND
                travel_terminal.dcruse_flg in ('0','1')
            ORDER BY
                travel_terminal.departure_date,
                travel_terminal.departure_time,
                travel_terminal.arrival_date,
                travel_terminal.arrival_time,
                travel_terminal.cd,
                travel_terminal.id;";

        $ids                         = array();
        $cds                         = array();
        $names                       = array();
        $zips                        = array();
        $prefecture_names            = array();
        $address                     = array();
        $buildings                   = array();
        $store_names                 = array();
        $tels                        = array();
        $terminal_cds                = array();
        $departure_dates             = array();
        $departure_times             = array();
        $arrival_dates               = array();
        $arrival_times               = array();
        $departure_client_cds        = array();
        $departure_client_branch_cds = array();
        $arrival_client_cds          = array();
        $arrival_client_branch_cds   = array();
        $travel_delivery_charge_ids  = array();

        $result = $db->executeQuery($query, $params);
        for ($i = 0; $i < $result->size(); ++$i) {
            $row = $result->get($i);
            $ids[]                         = $row['id'];
            $cds[]                         = $row['cd'];
            $names[]                       = $row['name'];
            $zips[]                        = $row['zip'];
            $prefecture_names[]            = $row['prefecture_name'];
            $address[]                     = $row['address'];
            $buildings[]                   = $row['building'];
            $store_names[]                 = $row['store_name'];
            $tels[]                        = $row['tel'];
            $terminal_cds[]                = $row['terminal_cd'];
            $departure_dates[]             = $row['departure_date'];
            $departure_times[]             = $row['departure_time'];
            $arrival_dates[]               = $row['arrival_date'];
            $arrival_times[]               = $row['arrival_time'];
            $departure_client_cds[]        = $row['departure_client_cd'];
            $departure_client_branch_cds[] = $row['departure_client_branch_cd'];
            $arrival_client_cds[]          = $row['arrival_client_cd'];
            $arrival_client_branch_cds[]   = $row['arrival_client_branch_cd'];
            $travel_delivery_charge_ids[]  = $row['travel_delivery_charge_id'];
        }

        return array(
            'ids'                         => $ids,
            'cds'                         => $cds,
            'names'                       => $names,
            'zips'                        => $zips,
            'prefecture_names'            => $prefecture_names,
            'address'                     => $address,
            'buildings'                   => $buildings,
            'store_names'                 => $store_names,
            'tels'                        => $tels,
            'terminal_cds'                => $terminal_cds,
            'departure_dates'             => $departure_dates,
            'departure_times'             => $departure_times,
            'arrival_dates'               => $arrival_dates,
            'arrival_times'               => $arrival_times,
            'departure_client_cds'        => $departure_client_cds,
            'departure_client_branch_cds' => $departure_client_branch_cds,
            'arrival_client_cds'          => $arrival_client_cds,
            'arrival_client_branch_cds'   => $arrival_client_branch_cds,
            'travel_delivery_charge_ids'  => $travel_delivery_charge_ids,
        );
    }

    /**
     * ツアー発着地マスタをDBから取得し、キーにツアー発着地IDを値にツアー発着地名(乗船日)を持つ配列を返します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array ['ids'] ツアー発着地IDの文字列配列、['names'] ツアー発着地名の文字列配列
     */
    public function fetchTravelDeparture($db, $data, $reqFlg = 2, $siteFlg = '1', $convenience = false) {

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
        $params[] = $reqFlg;
        //$add_days = $convenience ? self::CONVENIENCE_STORE_ADD_DAYS : self::CREDIT_CARD_ADD_DAYS;
        if ($siteFlg == self::SITE_FLG_TSUJO_BAN) {
            $add_days = $convenience ? self::CONVENIENCE_STORE_ADD_DAYS_STUJO : self::CREDIT_CARD_ADD_DAYS_STUJO;
        } else {
            $add_days = $convenience ? self::CONVENIENCE_STORE_ADD_DAYS_NAHA : self::CREDIT_CARD_ADD_DAYS_NAHA;
        }
        $query = "SELECT id"
                .", coalesce(name, '') || '（出発日時：' || coalesce(to_char(departure_date, 'YYYY年FMMM月FMDD日'), '') || coalesce(to_char(departure_time, 'FMHH24時'), '') || '）' AS name"
                .", to_char(departure_date, 'YYYY/MM/DD') AS departure_date"
                ." FROM travel_terminal"
                ." WHERE travel_id = \$1 AND terminal_cd IN ('1', '3') AND req_flg = \$2"
                . " AND current_date + " . pg_escape_string($add_days) . " <= coalesce(departure_date, arrival_date, current_date)"
                ." ORDER BY departure_date, departure_time, cd";

        $ids = array();
        $names = array();
        $dates = array();

        $result = $db->executeQuery($query, $params);
        for ($i = 0; $i < $result->size(); ++$i) {
            $row = $result->get($i);
            $ids[] = $row['id'];
            $names[] = $row['name'];
            $dates[] = $row['departure_date'];
        }

        return array(
            'ids'   => $ids,
            'names' => $names,
            'dates' => $dates,
        );
    }

    /**
     * ツアー発着地マスタをDBから取得し、キーにツアー発着地IDを値にツアー発着地名(乗船日)を持つ配列を返します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array ['ids'] ツアー発着地IDの文字列配列、['names'] ツアー発着地名の文字列配列
     */
    public function fetchTravelArrival($db, $data, $reqFlg = 2, $siteFlg = '1', $convenience = false) {

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
        $params[] = $reqFlg;
        
        //$add_days = $convenience ? self::CONVENIENCE_STORE_ADD_DAYS : self::CREDIT_CARD_ADD_DAYS;
        if ($siteFlg == self::SITE_FLG_TSUJO_BAN) {
            $add_days = $convenience ? self::CONVENIENCE_STORE_ADD_DAYS_STUJO : self::CREDIT_CARD_ADD_DAYS_STUJO;
        } else {
            $add_days = $convenience ? self::CONVENIENCE_STORE_ADD_DAYS_NAHA : self::CREDIT_CARD_ADD_DAYS_NAHA;
        }
        $query = "SELECT id"
                .", coalesce(name, '') || '（到着日時：' || coalesce(to_char(arrival_date, 'YYYY年FMMM月FMDD日'), '') || coalesce(to_char(arrival_time, 'FMHH24時'), '') || '）' AS name"
                ." FROM travel_terminal"
                ." WHERE travel_id = \$1 AND terminal_cd IN ('2', '3') AND req_flg = \$2"
                . " AND current_date + " . pg_escape_string($add_days) . " <= coalesce(departure_date, arrival_date, current_date)"
                ." ORDER BY arrival_date, arrival_time, cd";

        $ids = array();
        $names = array();

        $result = $db->executeQuery($query, $params);
        for ($i = 0; $i < $result->size(); ++$i) {
            $row = $result->get($i);
            $ids[] = $row['id'];
            $names[] = $row['name'];
        }

        return array(
            'ids'   => $ids,
            'names' => $names,
        );
    }

    /**
     * ツアー発着地マスタをDBから取得し、キーにツアー発着地IDを値にツアー発着地名を持つ配列を返します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array ['ids'] ツアー発着地IDの文字列配列、['names'] ツアー発着地名の文字列配列
     */
    public function fetchTravelTerminalLimit($db, $data) {

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
                travel_terminal.id,
                travel_agency.id                  AS TRAVEL_AGENCY_ID,
                travel_agency.name                AS TRAVEL_AGENCY_NAME,
                travel_terminal.travel_id,
                travel.name                       AS TRAVEL_NAME,
                travel_terminal.cd,
                travel_terminal.name,
                SUBSTR(travel_terminal.zip, 1, 3) AS ZIP1,
                SUBSTR(travel_terminal.zip, 4, 4) AS ZIP2,
                travel_terminal.pref_id,
                prefectures.name                  AS PREF_NAME,
                travel_terminal.address,
                travel_terminal.building,
                travel_terminal.store_name,
                travel_terminal.tel,
                travel_terminal.terminal_cd,
                TO_CHAR(travel_terminal.departure_date,'YYYY/MM/DD')     AS DEPARTURE_DATE,
                TO_CHAR(travel_terminal.departure_date,'YYYY年MM月DD日') AS DEPARTURE_DATE_JAPANESE,
                TO_CHAR(travel_terminal.arrival_date,'YYYY/MM/DD')       AS ARRIVAL_DATE,
                TO_CHAR(travel_terminal.arrival_date,'YYYY年MM月DD日')   AS ARRIVAL_DATE_JAPANESE,
                TO_CHAR(travel_terminal.departure_time, 'HH24:MI')       AS DEPARTURE_TIME,
                TO_CHAR(travel_terminal.departure_time, 'HH24時MI分')    AS DEPARTURE_TIME_JAPANESE,
                TO_CHAR(travel_terminal.arrival_time, 'HH24:MI')         AS ARRIVAL_TIME,
                TO_CHAR(travel_terminal.arrival_time, 'HH24時MI分')      AS ARRIVAL_TIME_JAPANESE,
                travel_terminal.departure_client_cd,
                travel_terminal.departure_client_branch_cd,
                travel_terminal.arrival_client_cd,
                travel_terminal.arrival_client_branch_cd
            FROM
                travel_terminal
                LEFT JOIN travel
                ON
                    travel_terminal.travel_id = travel.id
                LEFT JOIN travel_agency
                ON
                    travel.travel_agency_id = travel_agency.id
                LEFT JOIN prefectures
                ON
                    travel_terminal.pref_id = prefectures.prefecture_id
            WHERE
                travel_terminal.id = $1
            ORDER BY
                travel_terminal.departure_date,
                travel_terminal.departure_time,
                travel_terminal.arrival_date,
                travel_terminal.arrival_time,
                travel_terminal.cd,
                travel_terminal.id
            LIMIT 1
            OFFSET 0;";

        $result = $db->executeQuery($query, $params);
        // 引数のidが存在しない場合、エラーで止まってしまうため、@で回避($row はfalseになる)
        $row = @$result->get(0);
        return $row;
    }

    /**
     * ツアー発着地情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function select_id($db) {

        $query    = 'SELECT max(id) + 1 AS id, nextval($1) FROM travel_terminal;';
        $params   = array();
        $params[] = 'travel_terminal_id_seq';

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
     * ツアー発着地情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function _insertTravelTerminal($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            'id',
            'travel_id',
            'cd',
            'name',
            'zip',
            'pref_id',
            'address',
            'building',
            'store_name',
            'tel',
            'terminal_cd',
            'departure_date',
            'departure_time',
            'arrival_date',
            'arrival_time',
            'departure_client_cd',
            'departure_client_branch_cd',
            'arrival_client_cd',
            'arrival_client_branch_cd',
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
                travel_terminal
            (
                id,
                travel_id,
                cd,
                name,
                zip,
                pref_id,
                address,
                building,
                store_name,
                tel,
                terminal_cd,
                departure_date,
                departure_time,
                arrival_date,
                arrival_time,
                departure_client_cd,
                departure_client_branch_cd,
                arrival_client_cd,
                arrival_client_branch_cd,
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
                $9,
                $10,
                $11,
                $12,
                $13,
                $14,
                $15,
                $16,
                $17,
                $18,
                $19,
                CURRENT_TIMESTAMP,
                CURRENT_TIMESTAMP
            );';

        if($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START INSERT travel_terminal #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END INSERT travel_terminal #####");
        if($this->transactionFlg) {
            $db->commit();
        }
        return ($affecteds === 1);
    }

    /**
     * ツアー発着地情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function _updateTravelTerminal($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            'travel_id',
            'cd',
            'name',
            'zip',
            'pref_id',
            'address',
            'building',
            'store_name',
            'tel',
            'terminal_cd',
            'departure_date',
            'departure_time',
            'arrival_date',
            'arrival_time',
            'departure_client_cd',
            'departure_client_branch_cd',
            'arrival_client_cd',
            'arrival_client_branch_cd',
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
                travel_terminal
            SET
                travel_id                  = $1,
                cd                         = $2,
                name                       = $3,
                zip                        = $4,
                pref_id                    = $5,
                address                    = $6,
                building                   = $7,
                store_name                 = $8,
                tel                        = $9,
                terminal_cd                = $10,
                departure_date             = $11,
                departure_time             = $12,
                arrival_date               = $13,
                arrival_time               = $14,
                departure_client_cd        = $15,
                departure_client_branch_cd = $16,
                arrival_client_cd          = $17,
                arrival_client_branch_cd   = $18,
                modified                   = CURRENT_TIMESTAMP
            WHERE
                id = $19;';

        if($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START UPDATE travel_terminal #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END UPDATE travel_terminal #####");
        if($this->transactionFlg) {
            $db->commit();
        }
        return ($affecteds === 1);
    }

    /**
     * ツアー発着地マスタをDBから削除します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 削除するデータ
     */
    public function _deleteTravelTerminal($db, $data) {

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
                travel_terminal
            WHERE
                id = $1;';

        if($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START DELETE travel_terminal #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END DELETE travel_terminal #####");
        if($this->transactionFlg) {
            $db->commit();
        }
        return ($affecteds === 1);
    }

    /**
     * ツアー発着地マスタの出発名や日時などを取得する
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param string $cruiseId
     */
    public function fetchDepartureNameForIVR($db, $cruiseId) {

        $query = "SELECT travel_terminal.id"
                .", coalesce(name, '') || '（出発日時：' || coalesce(to_char(departure_date, 'YYYY年FMMM月FMDD日'), '') || coalesce(to_char(departure_time, 'FMHH24時'), '') || '）' AS name"
                .", to_char(departure_date, 'YYYY/MM/DD') AS departure_date"
                ." FROM travel_terminal"
                ." JOIN cruise ON cruise.travel_departure_id = travel_terminal.id"
                ." WHERE travel_terminal.travel_id = cruise.travel_id AND travel_terminal.terminal_cd IN ('1', '3') AND travel_terminal.req_flg = cruise.req_flg"
                . " AND current_date + " . pg_escape_string(self::CREDIT_CARD_ADD_DAYS_STUJO) . " <= coalesce(departure_date, arrival_date, current_date)"
                . " AND cruise.id = \$1"
                . " ORDER BY departure_date, departure_time, cd"
                . " LIMIT 1";

        $result = $db->executeQuery($query, array($cruiseId));
        $name = '';
        if ($result->size() > 0) {
            $data = $result->get(0);
            $name = $data['name'];
        }
        return $name;
    }
    /**
     * ツアー発着地マスタの到着名や日時などを取得する
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param string $cruiseId
     */
    public function fetchArrivalNameForIVR($db, $cruiseId) {

        $query = "SELECT travel_terminal.id"
                . ", coalesce(name, '') || '（到着日時：' || coalesce(to_char(arrival_date, 'YYYY年FMMM月FMDD日'), '') || coalesce(to_char(arrival_time, 'FMHH24時'), '') || '）' AS name"
                . " FROM travel_terminal"
                . " JOIN cruise ON cruise.travel_arrival_id = travel_terminal.id"
                . " WHERE travel_terminal.travel_id = cruise.travel_id AND travel_terminal.terminal_cd IN ('2', '3') AND travel_terminal.req_flg = cruise.req_flg"
                . " AND current_date + " . pg_escape_string(self::CREDIT_CARD_ADD_DAYS_STUJO) . " <= coalesce(departure_date, arrival_date, current_date)"
                . " AND cruise.id = \$1"
                . " ORDER BY arrival_date, arrival_time, cd"
                . " LIMIT 1";

        $result = $db->executeQuery($query, array($cruiseId));
        $name = '';
        if ($result->size() > 0) {
            $data = $result->get(0);
            $name = $data['name'];
        }
        return $name;
    }
}
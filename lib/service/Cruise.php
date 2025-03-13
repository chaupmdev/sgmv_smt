<?php

/**
 * @package    ClassDefFile
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/* * #@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
/* * #@- */

/**
 * 旅客手荷物受付サービスのお申し込み情報を扱います。
 *
 * @package Service
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_Cruise {

    /**
     * 旅客手荷物受付サービスのお申し込み情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function select_id($db, $reqFlg = 2) {

        $query    = 'SELECT nextval($1);';
        $params   = array();
        if ($reqFlg == '1') {
            $params[] = 'cruise_ivr_id_seq';
        } else {
            $params[] = 'cruise_id_seq';
        }
        

        $db->begin();
        $data = $db->executeQuery($query, $params);
        $db->commit();
        $row = $data->get(0);

        return $row['nextval'];
    }

    /**
     * 旅客手荷物受付サービスのお申し込み情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function insert($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            'id',
            'merchant_result',
            'merchant_datetime',
            'batch_status',
            'surname',
            'forename',
            'surname_furigana',
            'forename_furigana',
            'number_persons',
            'tel',
            'mail',
            'zip',
            'pref_id',
            'address',
            'building',
            'travel_id',
            'room_number',
            'terminal_cd',
            'departure_quantity',
            'arrival_quantity',
            'travel_departure_id',
            'cargo_collection_date',
            'cargo_collection_st_time',
            'cargo_collection_ed_time',
            'travel_arrival_id',
            'payment_method_cd',
            'convenience_store_cd',
            'authorization_cd',
            'receipt_cd',
            'payment_order_id',
            'toiawase_no_departure',
            'toiawase_no_arrival',
            'req_flg',
            'call_operator_id'
        );

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }

        $query  = '
            INSERT
            INTO
                cruise
            (
                id,
                merchant_result,
                merchant_datetime,
                receipted,
                send_result,
                sent,
                batch_status,
                retry_count,
                surname,
                forename,
                surname_furigana,
                forename_furigana,
                number_persons,
                tel,
                mail,
                zip,
                pref_id,
                address,
                building,
                travel_id,
                room_number,
                terminal_cd,
                departure_quantity,
                arrival_quantity,
                travel_departure_id,
                cargo_collection_date,
                cargo_collection_st_time,
                cargo_collection_ed_time,
                travel_arrival_id,
                payment_method_cd,
                convenience_store_cd,
                authorization_cd,
                receipt_cd,
                payment_order_id,
                created,
                modified,
                toiawase_no_departure,
                toiawase_no_arrival, 
                req_flg,
                call_operator_id
            )
            VALUES
            (
                $1,
                $2,
                $3,
                null,
                0,
                null,
                $4,
                0,
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
                $20,
                $21,
                $22,
                $23,
                $24,
                $25,
                $26,
                $27,
                $28,
                $29,
                $30,
                current_timestamp,
                current_timestamp,
                $31,
                $32,
                $33,
                $34
            );';

        $query = preg_replace('/\s+/u', ' ', trim($query));

        $db->begin();
        Sgmov_Component_Log::debug("####### START INSERT cruise #####");
        $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END INSERT cruise #####");
        $db->commit();
    }
    
    /**
     * 対象レコードを取得
     * ・業務未連携
     * @return
     */
    public function selectData()
    {
        $db = Sgmov_Component_DB::getAdmin();
        $sql = "
        SELECT
        cruise.id,
        cruise.merchant_result,
        TO_CHAR(cruise.merchant_datetime,'YYYYMMDD')                                                                            AS MERCHANT_DATE,
        TO_CHAR(cruise.merchant_datetime,'HH24MI')                                                                              AS MERCHANT_TIME,
        cruise.receipted,
        TO_CHAR(cruise.receipted,'YYYYMMDD')                                                                                    AS RECEIPTED_DATE,
        TO_CHAR(cruise.receipted,'HH24MI')                                                                                      AS RECEIPTED_TIME,
        cruise.send_result,
        cruise.sent,
        cruise.batch_status,
        cruise.retry_count,
        cruise.surname,
        cruise.forename,
        cruise.surname_furigana,
        cruise.forename_furigana,
        cruise.number_persons,
        cruise.tel,
        cruise.mail,
        cruise.zip,
        CASE
            WHEN cruise.pref_id >= 18
                THEN cruise.pref_id + 5
            ELSE cruise.pref_id + 4
        END                                                                                                                     AS FROM_AREA_ID,
        PREF1.if_prefecture_code                                                                                                AS PREF_IFID,
        PREF1.name                                                                                                              AS PREF_NAME,
        cruise.address,
        cruise.building,
        cruise.travel_id,
        travel.name                                                                                                             AS TRAVEL_NAME,
        cruise.room_number,
        cruise.terminal_cd,
        cruise.departure_quantity,
        cruise.arrival_quantity,
        TRAVEL_TERMINAL1.name                                                                                                   AS DEPARTURE_NAME,
        TRAVEL_TERMINAL1.zip                                                                                                    AS DEPARTURE_ZIP,
        TRAVEL_TERMINAL1.pref_id                                                                                                AS DEPARTURE_PREF_ID,
        PREF2.if_prefecture_code                                                                                                AS DEPARTURE_PREF_IFID,
        TRAVEL_TERMINAL1.address                                                                                                AS DEPARTURE_ADDRESS,
        TRAVEL_TERMINAL1.building                                                                                               AS DEPARTURE_BUILDING,
        TO_CHAR(COALESCE(TRAVEL_TERMINAL1.departure_date, TRAVEL_TERMINAL3.DEPARTURE_DATE, travel.embarkation_date),'YYYYMMDD') AS DEPARTURE_DEPARTURE_DATE,
        TO_CHAR(TRAVEL_TERMINAL1.departure_time,'HH24MI')                                                                       AS DEPARTURE_DEPARTURE_TIME,
        TRAVEL_TERMINAL1.store_name                                                                                             AS DEPARTURE_STORE_NAME,
        TRAVEL_TERMINAL1.tel                                                                                                    AS DEPARTURE_TEL,
        TO_CHAR(cruise.cargo_collection_date,'YYYYMMDD')                                                                        AS CARGO_COLLECTION_DATE,
        TO_CHAR(cruise.cargo_collection_st_time,'HH24MI')                                                                       AS CARGO_COLLECTION_ST_TIME,
        TO_CHAR(cruise.cargo_collection_ed_time,'HH24MI')                                                                       AS CARGO_COLLECTION_ED_TIME,
        TRAVEL_TERMINAL2.name                                                                                                   AS ARRIVAL_NAME,
        TRAVEL_TERMINAL2.zip                                                                                                    AS ARRIVAL_ZIP,
        TRAVEL_TERMINAL2.pref_id                                                                                                AS ARRIVAL_PREF_ID,
        PREF3.if_prefecture_code                                                                                                AS ARRIVAL_PREF_IFID,
        TRAVEL_TERMINAL2.address                                                                                                AS ARRIVAL_ADDRESS,
        TRAVEL_TERMINAL2.building                                                                                               AS ARRIVAL_BUILDING,
        TO_CHAR(TRAVEL_TERMINAL2.arrival_date,'YYYYMMDD')                                                                       AS ARRIVAL_ARRIVAL_DATE,
        TO_CHAR(TRAVEL_TERMINAL2.arrival_time,'HH24MI')                                                                         AS ARRIVAL_ARRIVAL_TIME,
        TRAVEL_TERMINAL2.store_name                                                                                             AS ARRIVAL_STORE_NAME,
        TRAVEL_TERMINAL2.tel                                                                                                    AS ARRIVAL_TEL,
        TRAVEL_DELIVERY_CHARGE_AREAS1.delivery_charg                                                                            AS DEPARTURE_CHARG1,
        TRAVEL_DELIVERY_CHARGE_AREAS2.delivery_charg                                                                            AS ARRIVAL_CHARG2,
        travel.round_trip_discount,
        TRAVEL_TERMINAL1.departure_client_cd                                                                                    AS DEPARTURE_CLIENT_CD,
        TRAVEL_TERMINAL1.departure_client_branch_cd                                                                             AS DEPARTURE_CLIENT_BRANCH_CD,
        TRAVEL_TERMINAL2.arrival_client_cd                                                                                      AS ARRIVAL_CLIENT_CD,
        TRAVEL_TERMINAL2.arrival_client_branch_cd                                                                               AS ARRIVAL_CLIENT_BRANCH_CD,
        cruise.payment_method_cd,
        cruise.convenience_store_cd,
        cruise.authorization_cd,
        cruise.receipt_cd,
        CASE
            WHEN cruise_repeater.tel IS NOT NULL
                AND travel.repeater_discount > 0
                AND cruise.payment_method_cd = '2'
                AND cruise.terminal_cd = '3'
                THEN '1'
            ELSE '0'
        END                                                                                                                     AS REPEATER_FLAG,
        CASE
            WHEN cruise_repeater.tel IS NOT NULL
                THEN travel.repeater_discount
            ELSE 0
        END                                                                                                                     AS REPEATER_DISCOUNT,
        cruise.created,
        toiawase_no_departure,
        toiawase_no_arrival

        FROM
        cruise

        LEFT OUTER JOIN
        travel
        ON
            travel.id = cruise.travel_id
        AND
            travel.dcruse_flg in ('0','1')

        LEFT OUTER JOIN
        travel_terminal AS TRAVEL_TERMINAL1
        ON
            TRAVEL_TERMINAL1.id = cruise.travel_departure_id
        AND
            TRAVEL_TERMINAL1.travel_id = travel.id
        AND
            TRAVEL_TERMINAL1.dcruse_flg in ('0','1')

        LEFT OUTER JOIN
        travel_terminal AS TRAVEL_TERMINAL2
        ON
            TRAVEL_TERMINAL2.id = cruise.travel_arrival_id
        AND
            TRAVEL_TERMINAL2.travel_id = travel.id
        AND
            TRAVEL_TERMINAL2.dcruse_flg in ('0','1')

        LEFT OUTER JOIN
        (
            SELECT
                travel_id,
                MIN(departure_date) AS DEPARTURE_DATE
            FROM
                travel_terminal
            WHERE
                departure_date IS NOT NULL
            AND
                dcruse_flg in ('0','1')
            GROUP BY
                travel_id
        ) AS TRAVEL_TERMINAL3
        ON
            TRAVEL_TERMINAL3.travel_id = travel.id

        LEFT OUTER JOIN
        prefectures AS PREF1
        ON
            PREF1.prefecture_id = cruise.pref_id
        LEFT OUTER JOIN
        prefectures AS PREF2
        ON
            PREF2.prefecture_id = TRAVEL_TERMINAL1.pref_id

        LEFT OUTER JOIN
        prefectures AS PREF3
        ON
            PREF3.prefecture_id = TRAVEL_TERMINAL2.pref_id

        LEFT OUTER JOIN
        travel_provinces_prefectures{0} AS TRAVEL_PROVINCES_PREFECTURES1
        ON
            TRAVEL_PROVINCES_PREFECTURES1.prefecture_id = PREF1.prefecture_id
        AND
            TRAVEL_PROVINCES_PREFECTURES1.dcruse_flg in ('0','1')

        LEFT OUTER JOIN
        travel_provinces_prefectures{0} AS TRAVEL_PROVINCES_PREFECTURES2
        ON
            TRAVEL_PROVINCES_PREFECTURES2.prefecture_id = PREF1.prefecture_id
        AND
            TRAVEL_PROVINCES_PREFECTURES2.dcruse_flg in ('0','1')

        LEFT OUTER JOIN
        travel_provinces{0} AS TRAVEL_PROVINCES1
        ON
            TRAVEL_PROVINCES1.id = TRAVEL_PROVINCES_PREFECTURES1.provinces_id
        AND
            TRAVEL_PROVINCES1.dcruse_flg in ('0','1')

        LEFT OUTER JOIN
        travel_provinces{0} AS TRAVEL_PROVINCES2
        ON
            TRAVEL_PROVINCES2.id = TRAVEL_PROVINCES_PREFECTURES2.provinces_id
        AND
            TRAVEL_PROVINCES2.dcruse_flg in ('0','1')

        LEFT OUTER JOIN
        travel_delivery_charge AS TRAVEL_DELIVERY_CHARGE1
        ON
            TRAVEL_DELIVERY_CHARGE1.travel_terminal_id = cruise.travel_departure_id
        AND
            TRAVEL_DELIVERY_CHARGE1.travel_terminal_id = TRAVEL_TERMINAL1.id
        AND
            TRAVEL_DELIVERY_CHARGE1.dcruse_flg in ('0','1')

        LEFT OUTER JOIN
        travel_delivery_charge AS TRAVEL_DELIVERY_CHARGE2
        ON
            TRAVEL_DELIVERY_CHARGE2.travel_terminal_id = cruise.travel_arrival_id
        AND
            TRAVEL_DELIVERY_CHARGE2.travel_terminal_id = TRAVEL_TERMINAL2.id
        AND
            TRAVEL_DELIVERY_CHARGE2.dcruse_flg in ('0','1')

        LEFT OUTER JOIN
        travel_delivery_charge_areas AS TRAVEL_DELIVERY_CHARGE_AREAS1
        ON
            TRAVEL_DELIVERY_CHARGE_AREAS1.travel_delivery_charge_id = TRAVEL_DELIVERY_CHARGE1.id
        AND
            TRAVEL_DELIVERY_CHARGE_AREAS1.travel_areas_provinces_id = TRAVEL_PROVINCES1.id
        AND
            TRAVEL_DELIVERY_CHARGE_AREAS1.dcruse_flg in ('0','1')

        LEFT OUTER JOIN
        travel_delivery_charge_areas AS TRAVEL_DELIVERY_CHARGE_AREAS2
        ON
            TRAVEL_DELIVERY_CHARGE_AREAS2.travel_delivery_charge_id = TRAVEL_DELIVERY_CHARGE2.id
        AND
            TRAVEL_DELIVERY_CHARGE_AREAS2.travel_areas_provinces_id = TRAVEL_PROVINCES2.id
        AND
            TRAVEL_DELIVERY_CHARGE_AREAS2.dcruse_flg in ('0','1')

        LEFT OUTER JOIN
        cruise_repeater
        ON
            cruise_repeater.tel = cruise.tel
        AND
            cruise_repeater.zip = cruise.zip

    WHERE
        batch_status IN (1,2,3)
    AND
        ((TRAVEL_TERMINAL1.id IS NOT NULL AND TRAVEL_PROVINCES_PREFECTURES1.provinces_id IS NOT NULL) OR (TRAVEL_TERMINAL2.id IS NOT NULL AND TRAVEL_PROVINCES_PREFECTURES2.provinces_id IS NOT NULL))
    AND
        cruise.id <= 99999999
    AND
        travel.charge_flg = '{1}'
     ";

        $sql_old = str_replace('{0}', '', $sql);
        $sql_old = str_replace('{1}', '1', $sql_old);

        $sql_new = str_replace('{0}', '_n', $sql);
        $sql_new = str_replace('{1}', '0', $sql_new);

        $sql_uni = $sql_old.' union all '.$sql_new.' ORDER BY id;';

        $selectData = $db->executeQuery($sql_uni);

        return $selectData;
    }
}

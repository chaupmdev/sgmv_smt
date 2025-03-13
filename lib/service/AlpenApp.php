<?php

/**
 * @package    ClassDefFile
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
Sgmov_Lib::useServices(array('Event', 'Eventsub'));
/**#@-*/

/**
 * アルペン申込データマスタ情報を扱います。
 *
 * @package Service
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_AlpenApp
{

    // トランザクションフラグ
    private $transactionFlg = TRUE;

    /**
     * イベントサービス
     * @var Sgmov_Service_Event
     */
    private $_EventService;

    /**
     * イベントサービス
     * @var Sgmov_Service_Event
     */
    private $_EventsubService;

    /**
     * トランザクションフラグ設定.
     * @param type $flg TRUE=内部でトランザクション処理する/FALSE=内部でトランザクション処理しない
     */
    public function setTrnsactionFlg($flg)
    {
        $this->transactionFlg = $flg;
    }

    /**
     * イベント手荷物受付サービスのお申し込み情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function select_id($db)
    {

        $query    = 'SELECT nextval($1);';
        $params   = array();
        $params[] = 'alpen_app_id_seq';
        if ($this->transactionFlg) {
            $db->begin();
        }
        $data = $db->executeQuery($query, $params);
        if ($this->transactionFlg) {
            $db->commit();
        }
        $row = $data->get(0);

        return $row['nextval'];
    }

    /**
     *
     * @param type $db
     * @param type $id
     * @return type
     */
    public function fetchAlpenAppById($db, $id)
    {
        $query = 'SELECT * FROM alpen_app WHERE id=$1';

        if (empty($id)) {
            return array();
        }

        $result = $db->executeQuery($query, array($id));
        $resSize = $result->size();
        if (empty($resSize)) {
            return array();
        }

        $row = $result->get(0);

        return $row;
    }
    /**
     *
     * @param type $db
     * @param Array $params
     * @return Array
     */
    public function fetchAlpenAppData($db, $params)
    { //array($email, $eventId, $eventSubId)
        $query = 'SELECT * FROM alpen_app WHERE mail=$1 AND event_id = $2 AND eventsub_id = $3 AND del_flg != 2';

        if (empty($params)) {
            return array();
        }

        $result = $db->executeQuery($query, $params);
        $resSize = $result->size();
        if (empty($resSize)) {
            return array();
        }

        $row = $result->get(0);

        return $row;
    }

    /**
     * イベント手荷物受付サービスのお申し込み情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function insert($db, $data)
    {

        $this->_EventService = new Sgmov_Service_Event();
        $this->_EventsubService = new Sgmov_Service_Eventsub();

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            "id",
            "merchant_result",
            "merchant_datetime",
            "receipted",
            "send_result",
            "sent",
            "batch_status",
            "retry_count",
            "payment_method_cd",
            "convenience_store_cd",
            "receipt_cd",
            "authorization_cd",
            "payment_order_id",
            "div",
            "event_id",
            "eventsub_id",
            "customer_cd",
            "office_name",
            "personal_name_sei",
            "personal_name_mei",
            "zip",
            "pref_id",
            "address",
            "building",
            "tel",
            "mail",
            "booth_name",
            "building_name",
            "booth_position",
            "booth_num",
            "staff_sei",
            "staff_mei",
            "staff_sei_furi",
            "staff_mei_furi",
            "staff_tel",
            "choice",
            "amount",
            "amount_tax",
            "create_ip",
            //            "created",
            "modify_ip",
            //            "modified",
            "transaction_id",
            "auto_authoriresult",
            "haraikomi_url",
            "kounyuten_no",
            "del_flg",
            "customer_kbn",
            "bpn_type",
            //            "toiawase_no",


            // "amount_kokyaku",
            // "amount_tax_kokyaku",
        );

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            if ($key == 'tel' || $key == 'staff_tel') {
                $params[] = str_replace('-', '', $data[$key]);
            } elseif ($key == 'customer_cd' && @!empty($data[$key])) {
                // チェックディジットを計算して付加する
                // 顧客コードを配列化
                $arrKokyakuCd = str_split($data[$key]);

                // 掛け算数値配列（固定らしいのでベタ書き）
                $intCheck = array(
                    0 => 4,
                    1 => 3,
                    2 => 2,
                    3 => 9,
                    4 => 8,
                    5 => 7,
                    6 => 6,
                    7 => 5,
                    8 => 4,
                    9 => 3,
                    10 => 2,
                );

                $total = 0;
                for ($i = 0; $i < 11; $i++) {
                    $total += $arrKokyakuCd[$i] * $intCheck[$i];
                }

                $amari = 11 - ($total % 11);
                $amariLen = count(str_split($amari));
                $kokyakuCdCheckDigit = substr($amari, $amariLen - 1);

                // チェックディジットを付加して代入
                $data[$key] = $data[$key] . $kokyakuCdCheckDigit;
                $params[] = $data[$key];
            } else {
                $params[] = $data[$key];
            }
        }

        if (isset($data["list_ptrn"])) {
            $params = array_merge($params, array($data["list_ptrn"]));
        } else {
            $params = array_merge($params, array("0"));
        }

        $event = $this->_EventService->fetchEventInfoByEventId($db, $data["event_id"]);
        if (!empty($event)) {
            if (@empty($event["shikibetsushi"])) {
                $eventsub = $this->_EventsubService->fetchEventsubByEventsubId($db, $data["eventsub_id"]);
                if (@empty($eventsub["shikibetsushi"])) {
                    // サービスレベルでは、あまり$_SERVERのデータを参照するのはよろしくないが、とり急ぎ使用する
                    preg_match('/.*\/(\w{3})\/.*/', $_SERVER['HTTP_REFERER'], $m);
                    if (@!empty($m[1])) {
                        $params = array_merge($params, array($m[1]));
                    }
                } else {
                    $params = array_merge($params, array($eventsub["shikibetsushi"]));
                }
            } else {
                $params = array_merge($params, array($event["shikibetsushi"]));
            }
        }

        if (isset($data["amount_kokyaku"])) {
            $params = array_merge($params, array($data["amount_kokyaku"]));
        } else {
            $params = array_merge($params, array("0"));
        }

        if (isset($data["amount_tax_kokyaku"])) {
            $params = array_merge($params, array($data["amount_tax_kokyaku"]));
        } else {
            $params = array_merge($params, array("0"));
        }




        //Sgmov_Component_Log::debug("############################################ 402");
        //Sgmov_Component_Log::debug($params);

        $query  = '
            INSERT
            INTO
                alpen_app
            (
                id,
                merchant_result,
                merchant_datetime,
                receipted,
                send_result,
                sent,
                batch_status,
                retry_count,
                payment_method_cd,
                convenience_store_cd,
                receipt_cd,
                authorization_cd,
                payment_order_id,
                div,
                event_id,
                eventsub_id,
                customer_cd,
                office_name,
                personal_name_sei,
                personal_name_mei,
                zip,
                pref_id,
                address,
                building,
                tel,
                mail,
                booth_name,
                building_name,
                booth_position,
                booth_num,
                staff_sei,
                staff_mei,
                staff_sei_furi,
                staff_mei_furi,
                staff_tel,
                choice,
                amount,
                amount_tax,
                create_ip,
                created,
                modify_ip,
                modified,
                transaction_id,
                auto_authoriresult,
                haraikomi_url,
                kounyuten_no,
                del_flg,
                customer_kbn,
                bpn_type,
                list_ptrn,
                event_key,

                amount_kokyaku,
                amount_tax_kokyaku
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
                $31,
                $32,
                $33,
                $34,
                $35,
                $36,
                $37,
                $38,
                $39,
                current_timestamp,
                $40,
                current_timestamp,
                $41,
                $42,
                $43,
                $44,
                $45,
                $46,
                $47,
                $48,
                $49,

                $50,
                $51
            );';

        $query = preg_replace('/\s+/u', ' ', trim($query));
        if ($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START INSERT alpen_app #####");
        $res = $db->executeUpdate($query, $params);
        if (empty($res)) {
            throw new Exception();
        }
        Sgmov_Component_Log::debug("####### END INSERT alpen_app #####");
        Sgmov_Component_Log::debug($res);
        if ($this->transactionFlg) {
            $db->commit();
        }
    }
    /**
     * イベント手荷物受付サービスのお申し込み情報をDBに保存します。
     * QR決済様にdelivery_slip_noを追加
     * 他のシステムと共有しているので独立して追加した。2022.03.14 tamashiro
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function insert_add_delivery_slip_no($db, $data)
    {

        $this->_EventService = new Sgmov_Service_Event();
        $this->_EventsubService = new Sgmov_Service_Eventsub();

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            "id",
            "merchant_result",
            "merchant_datetime",
            "receipted",
            "send_result",
            "sent",
            "batch_status",
            "retry_count",
            "payment_method_cd",
            "convenience_store_cd",
            "receipt_cd",
            "authorization_cd",
            "payment_order_id",
            "div",
            "event_id",
            "eventsub_id",
            "customer_cd",
            "office_name",
            "personal_name_sei",
            "personal_name_mei",
            "zip",
            "pref_id",
            "address",
            "building",
            "tel",
            "mail",
            "booth_name",
            "building_name",
            "booth_position",
            "booth_num",
            "staff_sei",
            "staff_mei",
            "staff_sei_furi",
            "staff_mei_furi",
            "staff_tel",
            "choice",
            "amount",
            "amount_tax",
            "create_ip",
            "modify_ip",
            "transaction_id",
            "auto_authoriresult",
            "haraikomi_url",
            "kounyuten_no",
            "del_flg",
            "customer_kbn",
            "bpn_type",
            "delivery_slip_no",
        );

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            if ($key == 'tel' || $key == 'staff_tel') {
                $params[] = str_replace('-', '', $data[$key]);
            } elseif ($key == 'customer_cd' && @!empty($data[$key])) {
                // チェックディジットを計算して付加する
                // 顧客コードを配列化
                $arrKokyakuCd = str_split($data[$key]);

                // 掛け算数値配列（固定らしいのでベタ書き）
                $intCheck = array(
                    0 => 4,
                    1 => 3,
                    2 => 2,
                    3 => 9,
                    4 => 8,
                    5 => 7,
                    6 => 6,
                    7 => 5,
                    8 => 4,
                    9 => 3,
                    10 => 2,
                );

                $total = 0;
                for ($i = 0; $i < 11; $i++) {
                    $total += $arrKokyakuCd[$i] * $intCheck[$i];
                }

                $amari = 11 - ($total % 11);
                $amariLen = count(str_split($amari));
                $kokyakuCdCheckDigit = substr($amari, $amariLen - 1);

                // チェックディジットを付加して代入
                $data[$key] = $data[$key] . $kokyakuCdCheckDigit;
                $params[] = $data[$key];
            } else {
                $params[] = $data[$key];
            }
        }

        if (isset($data["list_ptrn"])) {
            $params = array_merge($params, array($data["list_ptrn"]));
        } else {
            $params = array_merge($params, array("0"));
        }

        $event = $this->_EventService->fetchEventInfoByEventId($db, $data["event_id"]);
        if (!empty($event)) {
            if (@empty($event["shikibetsushi"])) {
                $eventsub = $this->_EventsubService->fetchEventsubByEventsubId($db, $data["eventsub_id"]);
                if (@empty($eventsub["shikibetsushi"])) {
                    // サービスレベルでは、あまり$_SERVERのデータを参照するのはよろしくないが、とり急ぎ使用する
                    preg_match('/.*\/(\w{3})\/.*/', $_SERVER['HTTP_REFERER'], $m);
                    if (@!empty($m[1])) {
                        $params = array_merge($params, array($m[1]));
                    }
                } else {
                    $params = array_merge($params, array($eventsub["shikibetsushi"]));
                }
            } else {
                $params = array_merge($params, array($event["shikibetsushi"]));
            }
        }

        if (isset($data["amount_kokyaku"])) {
            $params = array_merge($params, array($data["amount_kokyaku"]));
        } else {
            $params = array_merge($params, array("0"));
        }

        if (isset($data["amount_tax_kokyaku"])) {
            $params = array_merge($params, array($data["amount_tax_kokyaku"]));
        } else {
            $params = array_merge($params, array("0"));
        }

        $query  = '
            INSERT
            INTO
                alpen_app
            (
                id,
                merchant_result,
                merchant_datetime,
                receipted,
                send_result,
                sent,
                batch_status,
                retry_count,
                payment_method_cd,
                convenience_store_cd,
                receipt_cd,
                authorization_cd,
                payment_order_id,
                div,
                event_id,
                eventsub_id,
                customer_cd,
                office_name,
                personal_name_sei,
                personal_name_mei,
                zip,
                pref_id,
                address,
                building,
                tel,
                mail,
                booth_name,
                building_name,
                booth_position,
                booth_num,
                staff_sei,
                staff_mei,
                staff_sei_furi,
                staff_mei_furi,
                staff_tel,
                choice,
                amount,
                amount_tax,
                create_ip,
                created,
                modify_ip,
                modified,
                transaction_id,
                auto_authoriresult,
                haraikomi_url,
                kounyuten_no,
                del_flg,
                customer_kbn,
                bpn_type,
                delivery_slip_no,
                list_ptrn,
                event_key,
                amount_kokyaku,
                amount_tax_kokyaku
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
                $31,
                $32,
                $33,
                $34,
                $35,
                $36,
                $37,
                $38,
                $39,
                current_timestamp,
                $40,
                current_timestamp,
                $41,
                $42,
                $43,
                $44,
                $45,
                $46,
                $47,
                $48,
                $49,
                $50,
                $51,
                $52
            );';

        $query = preg_replace('/\s+/u', ' ', trim($query));
        if ($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START INSERT alpen_app #####");
        $res = $db->executeUpdate($query, $params);
        if (empty($res)) {
            throw new Exception();
        }
        Sgmov_Component_Log::debug("####### END INSERT alpen_app #####");
        Sgmov_Component_Log::debug($res);
        if ($this->transactionFlg) {
            $db->commit();
        }
    }

    /**
     *
     * @param type $alpen_appId
     * @param type $delFlg
     */
    public function updateDelFlg($db, $alpen_appId, $delFlg)
    {
        //        $query = 'SELECT * FROM alpen_app WHERE id=$1';
        $query = 'UPDATE alpen_app SET del_flg=$1 WHERE id=$2';

        if ($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START UPDATE alpen_app #####");
        $res = $db->executeUpdate($query, array($delFlg, $alpen_appId));
        if (empty($res)) {
            throw new Exception();
        }
        Sgmov_Component_Log::debug("####### END UPDATE alpen_app #####");
        if ($this->transactionFlg) {
            $db->commit();
        }
    }

    /**
     *
     * @param type $alpen_appId
     * @param type $delFlg
     */
    public function updateSgfCancelFlg($db, $alpen_appId, $delFlg)
    {
        //        $query = 'SELECT * FROM alpen_app WHERE id=$1';
        $query = 'UPDATE alpen_app SET sgf_cancel_flg=$1 WHERE id=$2';

        if ($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START UPDATE alpen_app #####");
        $res = $db->executeUpdate($query, array($delFlg, $alpen_appId));
        if (empty($res)) {
            throw new Exception();
        }
        Sgmov_Component_Log::debug("####### END UPDATE alpen_app #####");
        if ($this->transactionFlg) {
            $db->commit();
        }
    }

    /**
     *
     * @param type $db
     * @param Array $params
     * @return boolean
     */
    public function checkAlpenAppByEvent($db, $alpen_appId, $email, $eventSubId)
    { //array($email, $eventId, $eventSubId)
        $query = 'SELECT alpen_app.* '
            . 'FROM alpen_app '
            . 'INNER JOIN eventsub ON eventsub.id = alpen_app.eventsub_id '
            . 'WHERE alpen_app.mail = $1 '
            . 'AND alpen_app.id = $2 '
            . 'AND eventsub.id = $3 ';

        $result = $db->executeQuery($query, array($email, $alpen_appId, $eventSubId));
        $resSize = $result->size();
        if (empty($resSize)) {
            return array();
        }

        $row = $result->get(0);

        return $row;
    }

    /**
     *
     * @param type $db
     * @param String $email
     * @param int $eventId
     * @param int $eventSubId
     * @return array
     */
    public function fetchAlpenAppUserHistory($db, $email, $eventId, $eventSubId)
    {
        $query = "SELECT
			cmk.id
			,ckd.toiawase_no_niugoki
			,cmk.created
			,CASE WHEN ckd.type = 1 THEN '搬入（お客様⇒会場）'
			WHEN ckd.type = 2 THEN '搬出（会場⇒お客様）'
			END AS type
                        , ckd.type AS detail_type
			,SUM(num) AS total
			,cmk.del_flg
                        ,ckd.delivery_date
                        ,ckd.collect_date
                        ,cmk.amount_tax
		FROM alpen_app AS cmk
		INNER JOIN alpen_app_detail AS ckd ON cmk.id = ckd.app_id
		INNER JOIN alpen_app_box AS cmbo ON cmk.id = cmbo.app_id
		WHERE
		cmk.event_id = $1
		AND cmk.eventsub_id = $2
		AND cmk.mail = $3
		GROUP BY
			cmk.id
			,ckd.toiawase_no_niugoki
			,cmk.created
			,ckd.type
			,cmk.del_flg
                        ,ckd.delivery_date
                        ,ckd.collect_date
                        ,cmk.amount_tax
		ORDER BY cmk.id DESC";

        $result = $db->executeQuery($query, array($eventId, $eventSubId, $email));
        $resSize = $result->size();
        if (empty($resSize)) {
            return array();
        }

        $returnList = array();
        for ($i = 0; $i < $result->size(); ++$i) {
            $returnList[] = $result->get($i);
        }


        return $returnList;
    }
}

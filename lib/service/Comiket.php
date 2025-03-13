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
 * コミケ申込データマスタ情報を扱います。
 *
 * @package Service
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_Comiket
{
    // postgresのint型最大値
    const INT_MAX = 2147483647;

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
        $params[] = 'comiket_id_seq';
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
    public function fetchComiketById($db, $id)
    {
        $query = 'SELECT * FROM comiket WHERE id=$1';
        
        //GiapLN fix bug max interger in query postgress 
        if (empty($id) || $id > self::INT_MAX) {
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
    public function fetchComiketData($db, $params)
    { //array($email, $eventId, $eventSubId)
        $query = 'SELECT * FROM comiket WHERE mail=$1 AND event_id = $2 AND eventsub_id = $3 AND del_flg != 2';

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
     *  QR決済重複チェック
     * @param type $db
     * @param array $param
     * @param type $kbn
     * @return type
     */
    public function fetchQRById($db, $param, $kbn)
    {
        if (empty($param)) {
            return array();
        }

        // MV-BASE
        if($kbn==0){
            $query = 'SELECT * FROM comiket WHERE qr_toiawase_no=$1 AND qr_kessai_meisai_id=$2';
            $result = $db->executeQuery($query, array($param['TOIAWASE_NO'], $param['KESSAI_MEISAI_ID']));
        }
        // SG-ARK
        else if($kbn==1){
            $query = 'SELECT * FROM comiket WHERE qr_ark_uketsuke_no=$1 AND qr_kessai_meisai_id=$2';
            $result = $db->executeQuery($query, array($param['ARK_UKETSUKE_NO'], $param['KESSAI_MEISAI_ID']));
        }

        // size()がレコードカウント
        $resSize = $result->size();
        if (empty($resSize)) {
            //レコードがなければ新規登録可能でtrueを返す
            return true;
        }
        //レコードがあれば重複するのでfalseを返す
        return false;
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
        
        //ミルクラン用:ミルクラン_発着選択：1：空港、2：サービスセンター、3：ホテル
//        if (isset($data["mlk_hachaku_type_cd"])) {
//            $params = array_merge($params, array($data["mlk_hachaku_type_cd"]));
//        } else {
//            $params = array_merge($params, array(""));
//        }
        //ミルクラン用:ミルクラン_発着地識別番号
//        if (isset($data["mlk_hachaku_shikibetu_cd"])) {
//            $params = array_merge($params, array($data["mlk_hachaku_shikibetu_cd"]));
//        } else {
//            $params = array_merge($params, array(""));
//        }

        //Sgmov_Component_Log::debug("############################################ 402");
        //Sgmov_Component_Log::debug($params);

        $query  = '
            INSERT
            INTO
                comiket
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
        
        /* 
         *                 mlk_hachaku_type_cd,
                mlk_hachaku_shikibetu_cd
         * $52,
                $53
         */
        $query = preg_replace('/\s+/u', ' ', trim($query));
        if ($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START INSERT comiket #####");
        $res = $db->executeUpdate($query, $params);
        if (empty($res)) {
            throw new Exception();
        }
        Sgmov_Component_Log::debug("####### END INSERT comiket #####");
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
                comiket
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
        Sgmov_Component_Log::debug("####### START INSERT comiket #####");
        $res = $db->executeUpdate($query, $params);
        if (empty($res)) {
            throw new Exception();
        }
        Sgmov_Component_Log::debug("####### END INSERT comiket #####");
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
    public function insert_add_qrcode($db, $data)
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
            "qr_uketsuke_no",
            "qr_toiawase_no",
            "qr_ark_uketsuke_no",
            "qr_kessai_meisai_id",
            "qr_uriage_kingaku",
            "qr_system_kbn",
            "qr_cd",
        );

        // indexが47番から7件分の下記キーの配列を削除する
        //  ['uketsuke_no']     ['toiawase_no'] ['ark_uketsuke_no']  ['kessai_meisai_id']
        //  ['uriage_kingaku']  ['system_kbn']  ['cd']
        array_splice($data, 47, 7);

        //QRコードのデータを整理する。
        switch ($data['qr_system_kbn']) {
            case 0:
                $data['qr_ark_uketsuke_no'] = null;
                break;
            case 1:
                $data['qr_uketsuke_no'] = null;
                $data['qr_toiawase_no'] = null;
                break;
        }

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

        // イベント識別子の取得
        $event = $this->_EventService->getEventAndEventsub($db, $data["event_id"], $data["eventsub_id"]);
        // イベント情報が取得できた場合
        if (!empty($event)) {
                $params = array_merge($params, array($event["shikibetsushi"]));
        }
        else{
            throw new Exception();
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
                comiket
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
                qr_uketsuke_no,
                qr_toiawase_no,
                qr_ark_uketsuke_no,
                qr_kessai_meisai_id,
                qr_uriage_kingaku,
                qr_system_kbn,
                qr_cd,
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
                $52,
                $53,
                $54,
                $55,
                $56,
                $57,
                $58,
                $59
            );';

        $query = preg_replace('/\s+/u', ' ', trim($query));
        if ($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START INSERT comiket #####");
        $res = $db->executeUpdate($query, $params);
        if (empty($res)) {
            throw new Exception();
        }
        Sgmov_Component_Log::debug("####### END INSERT comiket #####");
        Sgmov_Component_Log::debug($res);
        if ($this->transactionFlg) {
            $db->commit();
        }
    }

    /**
     *
     * @param type $result
     * @param type $errmsg
     * @param type $toiawase_no
     * @param type $kessai_meisai_id
     * @param type $ark_uketsuke_no
     *
     */
    public function updateQR_Result($db, $result, $qr)
    {
        $this->transactionFlg = true;
        if ($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START UPDATE comiket #####");
        // MV-BASEの場合
        if ($qr['qr_system_kbn'] == 0) {
            $query = 'UPDATE comiket SET qr_result=$1, qr_error=$2 WHERE qr_toiawase_no=$3 AND qr_kessai_meisai_id=$4';
            $res = $db->executeUpdate($query, [$result->result, $result->error, $qr['qr_toiawase_no'], $qr['qr_kessai_meisai_id']]);
        // ARKの場合
        } else {
            $query = 'UPDATE comiket SET qr_result=$1, qr_error=$2 WHERE qr_ark_uketsuke_no=$3 AND qr_kessai_meisai_id=$4';
            $res = $db->executeUpdate($query, [$result->result, $result->error, $qr['qr_ark_uketsuke_no'], $qr['qr_kessai_meisai_id']]);
        }

        if (empty($res)) {
            throw new Exception();
        }
        Sgmov_Component_Log::debug("####### END UPDATE comiket #####");
        if ($this->transactionFlg) {
            $db->commit();
        }
    }

    /**
     *
     * @param type $comiketId
     * @param type $delFlg
     */
    public function updateDelFlg($db, $comiketId, $delFlg)
    {
        //        $query = 'SELECT * FROM comiket WHERE id=$1';
        $query = 'UPDATE comiket SET del_flg=$1 WHERE id=$2';

        if ($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START UPDATE comiket #####");
        $res = $db->executeUpdate($query, array($delFlg, $comiketId));
        if (empty($res)) {
            throw new Exception();
        }
        Sgmov_Component_Log::debug("####### START UPDATE comiket #####");
        if ($this->transactionFlg) {
            $db->commit();
        }
    }

    /**
     *
     * @param type $comiketId
     * @param type $delFlg
     */
    public function updateSgfCancelFlg($db, $comiketId, $delFlg)
    {
        //        $query = 'SELECT * FROM comiket WHERE id=$1';
        $query = 'UPDATE comiket SET sgf_cancel_flg=$1 WHERE id=$2';

        if ($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START UPDATE comiket #####");
        $res = $db->executeUpdate($query, array($delFlg, $comiketId));
        if (empty($res)) {
            throw new Exception();
        }
        Sgmov_Component_Log::debug("####### END UPDATE comiket #####");
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
    public function checkComiketByEvent($db, $comiketId, $email, $eventSubId)
    { //array($email, $eventId, $eventSubId)
        $query = 'SELECT comiket.* '
            . 'FROM comiket '
            . 'INNER JOIN eventsub ON eventsub.id = comiket.eventsub_id '
            . 'WHERE comiket.mail = $1 '
            . 'AND comiket.id = $2 '
            . 'AND eventsub.id = $3 ';

        $result = $db->executeQuery($query, array($email, $comiketId, $eventSubId));
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
    public function fetchComiketUserHistory($db, $email, $eventId, $eventSubId)
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
            ,CASE WHEN cmk.payment_method_cd = 1 THEN 'コンビニ決済'
                  WHEN cmk.payment_method_cd = 2 THEN 'クレジットカード'
                  WHEN cmk.payment_method_cd = 3 THEN '電子マネー'
                  WHEN cmk.payment_method_cd = 4 THEN 'コンビニ後払い'
                  WHEN cmk.payment_method_cd = 5 THEN '法人売掛'
                  WHEN cmk.payment_method_cd = 5 THEN '法人売掛'
                  ELSE '支払いなし'
                END shiharai
		FROM comiket AS cmk
		INNER JOIN comiket_detail AS ckd ON cmk.id = ckd.comiket_id
		INNER JOIN comiket_box AS cmbo ON cmk.id = cmbo.comiket_id
		WHERE 
		1=1
		AND ((merchant_result = 1 AND payment_method_cd !=3) or payment_method_cd = 3)
		AND cmk.event_id = $1
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
            ,cmk.payment_method_cd
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

    /**
     * 送り状API用申込データ取得
     * @param type $db
     * @param type $id
     * @return type
     */
    public function fetchComiketByIdForApi($db, $id)
    {
        $queryComiket = 'SELECT * FROM comiket   WHERE id=$1';
        $queryAlpen   = 'SELECT * FROM alpen_app WHERE id=$1';

        //GiapLN fix bug max interger in query postgress 
        if (empty($id) || $id > self::INT_MAX) {
            return array();
        }

        $result = $db->executeQuery($queryComiket, array($id));
        $resSize = $result->size();
        if (empty($resSize)) {
            $result = $db->executeQuery($queryAlpen, array($id));
            $resSize = $result->size();
            if (empty($resSize)) {
                return array();
            }
        }

        $row = $result->get(0);

        return $row;
    }
    
    /**
     * イベントIDとイベントサブIDでコミケ情報取得
     * 
     * @param type $db
     * @param type $eventId
     * @param type $eventsubId
     * @return type
     */
    public function fetchComiketByEventAndEventsub($db, $eventId, $eventsubId)
    {
        if (empty($eventId) || empty($eventsubId)) {
            return array();
        }
        
        $query = "SELECT 
                    c.id,
                    CASE 
                        WHEN merchant_result = 0 THEN '未送信（送信失敗）'
                        WHEN merchant_result = 1 THEN '送信成功'
                    END merchant_result,
                    c.merchant_datetime,
                    c.receipted,
                    c.send_result,
                    c.sent,
                    CASE 
                        WHEN batch_status = 1 THEN '登録済'
                        WHEN batch_status = 2 THEN '申込み者へメール送付済'
                        WHEN batch_status = 3 THEN '連携データ送信済'
                        WHEN batch_status = 4 THEN '完了（管理者メール済）'
                    END batch_status,
                    c.retry_count,
                    CASE 
                        WHEN payment_method_cd = 1 THEN 'コンビニ決済'
                        WHEN payment_method_cd = 2 THEN 'クレジットカード'
                        WHEN payment_method_cd = 3 THEN '電子マネー'
                        WHEN payment_method_cd = 4 THEN 'コンビニ後払い'
                        WHEN payment_method_cd = 5 THEN '法人売掛'
                        WHEN payment_method_cd = 6 THEN '支払いなし'
                    END payment_method_cd,
                    CASE 
                        WHEN convenience_store_cd = 1 THEN 'セブンイレブン'
                        WHEN convenience_store_cd = 2 THEN 'イーコンテクスト決済'
                        WHEN convenience_store_cd = 3 THEN 'その他(デイリーヤマザキ)'
                    END convenience_store_cd,
                    c.receipt_cd,
                    c.authorization_cd,
                    c.payment_order_id,
                    CASE 
                        WHEN div = 1 THEN '個人'
                        WHEN div = 2 THEN '法人'
                        WHEN div = 3 THEN '設置'
                    END div,
                    c.event_id,
                    c.eventsub_id,
                    c.customer_cd,
                    c.office_name,
                    c.personal_name_sei,
                    c.personal_name_mei,
                    c.zip as c_zip,
                    c.pref_id as c_pref_id,
                    c.address as c_address,
                    c.building as c_building,
                    c.tel as c_tel,
                    c.mail,
                    c.booth_name,
                    c.building_name,
                    c.booth_position,
                    c.booth_num,
                    c.staff_sei,
                    c.staff_mei,
                    c.staff_sei_furi,
                    c.staff_mei_furi,
                    c.staff_tel,
                    CASE 
                        WHEN choice = 1 THEN '往路のみ'
                        WHEN choice = 2 THEN '復路のみ'
                        WHEN choice = 3 THEN '往路と復路'
                    END choice,
                    c.amount,
                    c.amount_tax,
                    c.create_ip,
                    c.created,
                    c.modify_ip,
                    c.modified,
                    c.transaction_id,
                    c.auto_authoriresult,
                    c.delivery_slip_no,
                    c.haraikomi_url,
                    c.kounyuten_no,
                    CASE 
                        WHEN del_flg = 0 THEN '初期中'
                        WHEN del_flg = 1 THEN '削除中(送信中、送信失敗)'
                        WHEN del_flg = 2 THEN '削除済'
                    END del_flg,
                    c.del_retry_count,
                    CASE 
                        WHEN sgf_cancel_flg = 0 THEN '送信必要なし'
                        WHEN sgf_cancel_flg = 1 THEN '送信必要あり'
                        WHEN sgf_cancel_flg = 2 THEN '送信済み'
                    END sgf_cancel_flg,
                    c.sgf_cancel_retry_count,
                    c.id_sub,
                    CASE 
                        WHEN customer_kbn = 1 THEN '出展者様'
                        WHEN customer_kbn = 2 THEN '一般のご利用者様(来場者様)'
                    END customer_kbn,
                    CASE 
                        WHEN bpn_type = 1 THEN '物販'
                        WHEN bpn_type = 2 THEN '当日物販'
                        ELSE '物販以外(配送データ)'
                    END bpn_type,
                    CASE 
                        WHEN list_ptrn = 1 THEN '飛沫ブロッカー用'
                        WHEN list_ptrn = 2 THEN '梱包資材用'
                    END list_ptrn,
                    c.event_key,
                    c.amount_kokyaku,
                    c.amount_tax_kokyaku,
                    c.qr_uketsuke_no,
                    c.qr_toiawase_no,
                    c.qr_ark_uketsuke_no,
                    c.qr_kessai_meisai_id,
                    c.qr_uriage_kingaku,
                    CASE 
                        WHEN qr_system_kbn = 0 THEN 'MV-BASE'
                        WHEN qr_system_kbn = 1 THEN 'SG-ARK'
                    END qr_system_kbn,
                    c.qr_cd,
                    c.qr_result,
                    c.qr_error,
                    cd.comiket_id,
                    CASE 
                        WHEN cd.type = 1 THEN '往路'
                        WHEN cd.type = 2 THEN '復路'
                    END type_format,
                    cd.cd,
                    cd.name,
                    cd.hatsu_jis5code,
                    cd.hatsu_shop_check_code,
                    cd.hatsu_shop_check_code_eda,
                    cd.hatsu_shop_code,
                    cd.hatsu_shop_local_code,
                    cd.chaku_jis5code,
                    cd.chaku_shop_check_code,
                    cd.chaku_shop_check_code_eda,
                    cd.chaku_shop_code,
                    cd.chaku_shop_local_code,
                    cd.zip,
                    cd.pref_id,
                    cd.address,
                    cd.building,
                    cd.tel,
                    cd.collect_date,
                    cd.collect_st_time,
                    cd.collect_ed_time,
                    cd.delivery_date,
                    cd.delivery_st_time,
                    cd.delivery_ed_time,
                    CASE 
                        WHEN cd.service = 1 THEN '宅配便'
                        WHEN cd.service = 2 THEN 'カーゴ'
                        WHEN cd.service = 3 THEN '貸切'
                    END service_format,
                    cd.note,
                    cd.fare,
                    cd.fare_tax,
                    cd.cost,
                    cd.cost_tax,
                    cd.delivery_timezone_cd,
                    cd.delivery_timezone_name,
                    cd.comiket_id_sub,
                    cd.no_chg_flg,
                    CASE 
                        WHEN cd.binshu_kbn = 0 THEN '飛脚宅配便'
                        WHEN cd.binshu_kbn = 1 THEN '飛脚クール便（冷蔵）'
                        WHEN cd.binshu_kbn = 2 THEN '飛脚クール便（冷凍）'
                    END binshu_kbn_format,
                    CASE 
                        WHEN cd.azukari_kaisu_type = 0 THEN 'なし'
                        WHEN cd.azukari_kaisu_type = 1 THEN '1回のみ'
                        WHEN cd.azukari_kaisu_type = 2 THEN '複数回'
                    END azukari_kaisu_type_format,
                    CASE 
                        WHEN cd.azukari_toriatsukai_type = 0 THEN 'なし'
                        WHEN cd.azukari_toriatsukai_type = 1 THEN '手荷物を持ち帰られる方'
                        WHEN cd.azukari_toriatsukai_type = 2 THEN '会場からご自宅まで手荷物を発送される方'
                    END azukari_toriatsukai_type_format,
                    cd.toiawase_no,
                    cd.toiawase_no_niugoki,
                    cd.fare_kokyaku,
                    cd.fare_tax_kokyaku,
                    cd.sagyo_jikan,
                    cd.kokyaku_futan_flg,
                    string_agg(cb.comiket_box_fomat::varchar, ', ') AS comiket_box_fomat
                FROM comiket c
                LEFT JOIN (SELECT * FROM comiket_detail) cd
                ON c.id = cd.comiket_id
                LEFT JOIN (
                    SELECT
                        subquery.comiket_id,
                        concat_ws(', ', subquery.comiket_id, subquery.type_format, subquery.box_id, subquery.num, subquery.fare_price, subquery.fare_amount, subquery.fare_price_tax, 
                        subquery.fare_amount_tax, subquery.cost_price, subquery.cost_amount, subquery.cost_price_tax, subquery.cost_amount_tax, subquery.comiket_id_sub, case when subquery.ziko_shohin_cd is null then '' else subquery.ziko_shohin_cd end, 
                        subquery.data_type_format, subquery.fare_price_kokyaku, subquery.fare_amount_kokyaku, subquery.fare_price_tax_kokyaku, subquery.fare_amount_tax_kokyaku, 
                        subquery.sagyo_jikan, case when subquery.shohin_cd is null then '' else subquery.shohin_cd end, case when subquery.note1 is null then '' else subquery.note1 end) as comiket_box_fomat
                    FROM (
                        SELECT *,
                        CASE 
                            WHEN type = 1 THEN '往路'
                            WHEN type = 2 THEN '復路'
                            WHEN type = 3 THEN 'ミルクラン'
                            WHEN type = 4 THEN '手荷物'
                            WHEN type = 5 THEN '物販'
                            WHEN type = 6 THEN '通常商品'
                            WHEN type = 7 THEN '顧客請求商品(D24)'
                            WHEN type = 8 THEN 'オプション'
                            WHEN type = 9 THEN 'リサイクル'
                            ELSE 'その他'
                        END type_format,
                        CASE 
                            WHEN data_type = 6 THEN '通常商品'
                            WHEN data_type = 7 THEN '顧客請求商品(D24)'
                            WHEN data_type = 8 THEN 'オプション'
                            WHEN data_type = 9 THEN 'リサイクル'
                            ELSE 'その他'
                        END data_type_format
                    FROM comiket_box
                    ) as subquery
                ) cb
                ON c.id = cb.comiket_id
                WHERE c.event_id = $1 AND c.eventsub_id = $2
                GROUP  BY 
                    c.id, 
                    c.merchant_result, 
                    c.merchant_datetime, 
                    c.receipted,
                    c.send_result,
                    c.sent,
                    c.batch_status,
                    c.retry_count,
                    c.payment_method_cd,
                    c.convenience_store_cd,
                    c.receipt_cd,
                    c.authorization_cd,
                    c.payment_order_id,
                    c.div,
                    c.event_id,
                    c.eventsub_id,
                    c.customer_cd,
                    c.office_name,
                    c.personal_name_sei,
                    c.personal_name_mei,
                    c.zip,
                    c.pref_id,
                    c.address,
                    c.building,
                    c.tel,
                    c.mail,
                    c.booth_name,
                    c.building_name,
                    c.booth_position,
                    c.booth_num,
                    c.staff_sei,
                    c.staff_mei,
                    c.staff_sei_furi,
                    c.staff_mei_furi,
                    c.staff_tel,
                    c.choice,
                    c.amount,
                    c.amount_tax,
                    c.create_ip,
                    c.created,
                    c.modify_ip,
                    c.modified,
                    c.transaction_id,
                    c.auto_authoriresult,
                    c.delivery_slip_no,
                    c.haraikomi_url,
                    c.kounyuten_no,
                    c.del_flg,
                    c.del_retry_count,
                    c.sgf_cancel_flg,
                    c.sgf_cancel_retry_count,
                    c.id_sub,
                    c.customer_kbn,
                    c.bpn_type,
                    c.list_ptrn,
                    c.event_key,
                    c.amount_kokyaku,
                    c.amount_tax_kokyaku,
                    c.qr_uketsuke_no,
                    c.qr_toiawase_no,
                    c.qr_ark_uketsuke_no,
                    c.qr_kessai_meisai_id,
                    c.qr_uriage_kingaku,
                    c.qr_system_kbn,
                    c.qr_cd,
                    c.qr_result,
                    c.qr_error,
                    cd.comiket_id,
                    cd.type,
                    cd.cd,
                    cd.name,
                    cd.hatsu_jis5code,
                    cd.hatsu_shop_check_code,
                    cd.hatsu_shop_check_code_eda,
                    cd.hatsu_shop_code,
                    cd.hatsu_shop_local_code,
                    cd.chaku_jis5code,
                    cd.chaku_shop_check_code,
                    cd.chaku_shop_check_code_eda,
                    cd.chaku_shop_code,
                    cd.chaku_shop_local_code,
                    cd.zip,
                    cd.pref_id,
                    cd.address,
                    cd.building,
                    cd.tel,
                    cd.collect_date,
                    cd.collect_st_time,
                    cd.collect_ed_time,
                    cd.delivery_date,
                    cd.delivery_st_time,
                    cd.delivery_ed_time,
                    cd.service,
                    cd.note,
                    cd.fare,
                    cd.fare_tax,
                    cd.cost,
                    cd.cost_tax,
                    cd.delivery_timezone_cd,
                    cd.delivery_timezone_name,
                    cd.comiket_id_sub,
                    cd.no_chg_flg,
                    cd.binshu_kbn,
                    cd.azukari_kaisu_type,
                    cd.azukari_toriatsukai_type,
                    cd.toiawase_no,
                    cd.toiawase_no_niugoki,
                    cd.fare_kokyaku,
                    cd.fare_tax_kokyaku,
                    cd.sagyo_jikan,
                    cd.kokyaku_futan_flg
                ORDER BY c.id
                        
        ";

        $result = $db->executeQuery($query, array($eventId, $eventsubId));
        $resSize = $result->size();
        if (empty($resSize)) {
            return array();
        }
        
        $returnList = array();
        for ($i = 0; $i < $resSize; ++$i) {
            $returnList[] = $result->get($i);
        }

        return $returnList;
    }

    /**
     *
     * @param type $db
     * @param String $cd
     
     * @return array
     */
    public function fetchComiketByDetailCD($db, $cd, $durationMonths)
    {
        $query = "SELECT cmk.*
		FROM comiket AS cmk
		INNER JOIN comiket_detail AS ckd ON cmk.id = ckd.comiket_id
        WHERE ckd.cd = $1 AND cmk.created > $2";
        $effectiveDate = date('Y-m-d', strtotime("-{$durationMonths} months", strtotime(date('Y-m-d'))));
        $result = $db->executeQuery($query, array($cd, $effectiveDate));
        //$result = $db->executeQuery($query, array($cd, $durationDays));
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

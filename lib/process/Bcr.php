<?php
/**
 * BCR/Send 旅客手荷物受付サービスのお申し込み送信バッチの、データ抽出＆チェック機能です。
 * @package    maintenance
 * @subpackage BCR
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('CommonConst');
Sgmov_Lib::useAllComponents(FALSE);
Sgmov_Lib::useServices(array('CenterMail', 'TravelTerminal'));
Sgmov_Lib::useprocess(array('BcrSender', 'BcrResponse'));
/**#@-*/

class Sgmov_Process_Bcr extends Sgmov_Process_BcrSender
{

    /**
     * 起動チェックファイル名
     */
    const OPRATION_FILE_NAME = 'operation_bcr.txt';

    // 消費税率
    const CURRENT_TAX = 1.10;

    const CREDIT_CARD_ADD_DAYS = 7;
    
    const PCR_IVR_REQUEST = 1;
    
    const PCR_WEB_REQUEST = 2;

    public function execute()
    {

        // バッチ起動チェックと起動
        $check1 = $this->startBcrcheck(Sgmov_Lib::getLogDir() . '/' . self::OPRATION_FILE_NAME);
        if ($check1 === false) {
            $this->errorInformation('startBcr');
        }

        // 1件以上対象があればバッチ処理の実行
        $alldata = $this->selectData();
        if ($alldata->size() > 0) {
            for ($i = 0; $i < $alldata->size(); ++$i) {
                $row = $alldata->get($i);
                $this->bcrOutline($row);
            }
        }

        // バッチ終了処理
        $check2 = $this->stopBcr(Sgmov_Lib::getLogDir() . '/' . self::OPRATION_FILE_NAME);
        if ($check2 == false) {
            $this->errorInformation('stopBcr');
        }
    }

    /**
     * バッチ起動チェック
     * @param object $file
     * @return true or false
     */
    public function startBcrcheck($file)
    {
        $check = file_exists($file);
        if ($check === true) {
            return false;
        } else {
            $check = touch($file);
            return true;
        }
    }

    /**
     * バッチ終了処理
     * @param object $file
     * @return true or false
     */
    public function stopBcr($file)
    {
        $check = unlink($file);
        return $check;
    }

    /**
     * システム管理者へバッチの起動失敗メールを送信
     * @param object $status
     * @return
     */
    public function errorInformation($status)
    {

        // システム管理者メールアドレスを取得する。
        $mail_to = Sgmov_Component_Config::getLogMailTo();
        //テンプレートメールを送信する。
        Sgmov_Component_Mail::sendTemplateMail($status, dirname(__FILE__) . '/../../lib/mail_template/bcr_error.txt', $mail_to);
        exit;
    }

    /**
     * 対象レコードを取得
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
        toiawase_no_arrival,
        cruise.req_flg 
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

    /**
     * バッチメイン処理
     * @param object $selectData
     * @return
     */
    public function bcrOutline($selectData)
    {

        if ($selectData["batch_status"] == 1) {
            //IFデータ送信
            $selectData = $this->sendData($selectData);
        }

        if ($selectData["batch_status"] == 2) {
            //管理者へメール送信（送信エラー時のみ）
            $selectData = $this->SendMailManager($selectData);
        }

        //メール用に値をセット
        $selectData = $this->setData($selectData);

        if ($selectData["batch_status"] == 3) {
            //担当者へメール送信
            $selectData = $this->SendMailTanto($selectData);
        }

        // 顧客へのメールはお申し込み時に送信する
        if ($selectData["batch_status"] == 4 && $selectData['req_flg'] == self::PCR_IVR_REQUEST && $selectData['payment_method_cd'] == '2') {//IVRのコールセンターの申込の時、カードで決済されたら、メールを送信する
           //顧客へ完了メール送信
           $selectData = $this->SendMailCustomer($selectData);
        }
    }

    /**
     * IFデータ送信
     * @param object $selectData
     * @return object $selectData
     */
    public function sendData($selectData)
    {

        //データ生成
        $csvdata = $this->makeIFcsv($selectData);

        //データ送信
        try {
            $res = Sgmov_Process_BcrSender::sendCsvToWs('MITUMORI_' . date('YmdHis') . '.csv', $csvdata);
        } catch(Sgmov_Component_Exception $sce) {
            $sce->setInformation($selectData);
            throw $sce;
        }

        $responce = new Sgmov_Process_BcrResponse;
        $responce->initialize($res);

        // レスポンス値によって処理のふりわけ
        switch ($responce->sendSts) {
        // 成功：update バッチ処理状況「送信済」 送信結果「成功」
        case 0:
            $db = Sgmov_Component_DB::getAdmin();
            $db->begin();
            $db->executeUpdate("UPDATE cruise SET batch_status='2',send_result='3',sent = current_timestamp,modified = current_timestamp WHERE id=$1;", array($selectData['id']));
            $db->commit();
            $selectData["batch_status"] = 2;
            $selectData["send_result"] = 3;
            break;

        //不正データ：update バッチ処理状況「送信済」 送信結果「失敗」
        case 1:
            $db = Sgmov_Component_DB::getAdmin();
            $db->begin();
            $db->executeUpdate("UPDATE cruise SET batch_status='2',send_result='1',sent = current_timestamp,modified = current_timestamp WHERE id=$1;", array($selectData['id']));
            $db->commit();
            $selectData["batch_status"] = 2;
            $selectData["send_result"] = 1;
            break;

        //システム障害：update 送信リトライ数「+1」
        case 2:
        //送信競合：update 送信リトライ数「+1」
        case 3:
            $db = Sgmov_Component_DB::getAdmin();
            $db->begin();
            $db->executeUpdate("UPDATE cruise SET retry_count=retry_count+1,sent = current_timestamp,modified = current_timestamp WHERE id=$1;", array($selectData['id']));
            $db->commit();
            ++$selectData["retry_count"];

            // 送信リトライ階数が21以上の場合バッチ処理状況「送信済」 送信結果「リトライオーバー」
            if ($selectData["retry_count"] >= 21) {
                $db = Sgmov_Component_DB::getAdmin();
                $db->begin();
                $db->executeUpdate("UPDATE cruise SET batch_status='2',send_result='2',sent = current_timestamp,modified = current_timestamp WHERE id=$1;", array($selectData['id']));
                $db->commit();
                $selectData["batch_status"] = 2;
                $selectData["send_result"] = 2;
            }
            break;

        // 登録済み：update バッチ処理状況「送信済」 送信結果「成功」
        case 4:
            $db = Sgmov_Component_DB::getAdmin();
            $db->begin();
            $db->executeUpdate("UPDATE cruise SET batch_status='2',send_result='3',sent = current_timestamp,modified = current_timestamp WHERE id=$1;", array($selectData['id']));
            $db->commit();
            $selectData["batch_status"] = 2;
            $selectData["send_result"] = 3;
            break;

        //それ以外 送信リトライ数「+1」 送信リトライ階数が21以上の場合バッチ処理状況「送信済」 送信結果「リトライオーバー」（タイムアウト）
        default:
            $db = Sgmov_Component_DB::getAdmin();
            $db->begin();
            $db->executeUpdate("UPDATE cruise SET retry_count=retry_count+1,sent = current_timestamp,modified = current_timestamp WHERE id=$1;", array($selectData['id']));
            $db->commit();
            ++$selectData["retry_count"];
            if ($selectData["retry_count"] >= 21) {
                $db = Sgmov_Component_DB::getAdmin();
                $db->begin();
                $db->executeUpdate("UPDATE cruise SET batch_status='2',send_result='2',sent = current_timestamp,modified = current_timestamp WHERE id=$1;", array($selectData['id']));
                $db->commit();
                $selectData["batch_status"] = 2;
                $selectData["send_result"] = 2;
            }
            break;
        }

        return $selectData;
    }

    /**
     * メール送信用にCDやFlagから値を作成
     * TODO PHP上で変換せず、SQLのCASE WHENで変換した方が処理が速い
     * @param object $selectData
     * @return object $selectData
     */
    public function setData($selectData)
    {

        // 受付番号
        //if (!empty($selectData['receipt_cd'])) {
        //    $selectData['mail_receipt_cd'] = $selectData['receipt_cd'];
        //} elseif (!empty($selectData['authorization_cd'])) {
        //    $selectData['mail_receipt_cd'] = $selectData['authorization_cd'];
        //}

        // 集荷希望日時
        if (!empty($selectData['cargo_collection_date'])) {
            $year  = ltrim(substr($selectData['cargo_collection_date'], 0, 4), '0') . '年';
            $month = ltrim(substr($selectData['cargo_collection_date'], 4, 2), '0') . '月';
            $day   = ltrim(substr($selectData['cargo_collection_date'], 6, 2), '0') . '日';
            $selectData['mail_cargo_collection_date'] = $year . $month . $day;
        }

        // 集荷希望開始時刻
        if (!empty($selectData['cargo_collection_st_time']) && $selectData['cargo_collection_st_time'] !== '0000') {
            $selectData['mail_cargo_collection_st_time'] = ltrim(substr($selectData['cargo_collection_st_time'], 0, 2), '0') . '時';
        }

        // 集荷希望終了時刻
        if (!empty($selectData['cargo_collection_ed_time']) && $selectData['cargo_collection_ed_time'] !== '0000') {
            $selectData['mail_cargo_collection_ed_time'] = ltrim(substr($selectData['cargo_collection_ed_time'], 0, 2), '0') . '時';
        }

        // 集荷の往復
        switch ($selectData['terminal_cd']) {
            case '1':
                $selectData['terminal'] = '往路のみ';
                break;
            case '2':
                $selectData['terminal'] = '復路のみ';
                break;
            case '3':
                $selectData['terminal'] = '往復';
                break;
            default:
                $selectData['terminal'] = '';
                break;
        }

        // お支払方法
        switch ($selectData['payment_method_cd']) {
            case '1':
                $selectData['payment_method'] = 'コンビニ決済';
                break;
            case '2':
                $selectData['payment_method'] = 'クレジットカード';
                break;
            default:
                $selectData['payment_method'] = '';
                break;
        }

        // コンビニ決済お支払店
        switch ($selectData['convenience_store_cd']) {
            case '1':
                $selectData['convenience_store'] = 'セブンイレブン';
                break;
            case '2':
                $selectData['convenience_store'] = 'ローソン、セイコーマート、ファミリーマート、ミニストップ';
                break;
            case '3':
                $selectData['convenience_store'] = 'デイリーヤマザキ';
                break;
            default:
                $selectData['convenience_store'] = '';
                break;
        }
Sgmov_Component_Log::debug($selectData);
        return $selectData;
    }

    /**
     * 管理者へメール送信（送信エラー時のみ）
     * @param object $selectData
     * @return object $selectData
     */
    public function SendMailManager($selectData)
    {

        if ($selectData["send_result"] == 1 || $selectData["send_result"] == 2) {
            // システム管理者メールアドレスを取得する。
            $mail_to = Sgmov_Component_Config::getLogMailTo();
            //メールを送信する。
            Sgmov_Component_Mail::sendTemplateMail($selectData, dirname(__FILE__) . '/../../lib/mail_template/bcr_error_send.txt', $mail_to);
        }
        $db = Sgmov_Component_DB::getAdmin();
        $db->begin();
        $db->executeUpdate("UPDATE cruise SET batch_status='3',modified = current_timestamp WHERE id=$1;", array($selectData['id']));
        $db->commit();
        $selectData["batch_status"] = 3;

        return $selectData;
    }

    /**
     * 担当者へメール送信
     * @param object $selectData
     * @return object $selectData
     */
    public function SendMailTanto($selectData)
    {

        $db = Sgmov_Component_DB::getAdmin();
        $_centerMailService = new Sgmov_Service_CenterMail();
        if ($selectData["send_result"] == 3) {
            //旅客手荷物受付サービスのお申し込み
            $_centerMailService->_sendAdminMailByFromAreaId($db, Sgmov_Service_CenterMail::FORM_KBN_PCR, $selectData['from_area_id'], $selectData, '/bcr_admin_pcr.txt');
        }

        //バッチステータスを更新
        //$db = Sgmov_Component_DB::getAdmin();
        $db->begin();
        $db->executeUpdate("UPDATE cruise SET batch_status='4',modified = current_timestamp WHERE id=$1;", array($selectData['id']));
        $db->commit();
        $selectData["batch_status"] = 4;

        return $selectData;
    }

    /**
     * 申込者へメール送信
     * @param object $selectData
     * @return object $selectData
     */
    public function SendMailCustomer($selectData)
    {
        //Generate mail content
        $mailData = $this->createMailDataByInForm($selectData);
        
        //Check mail template
        switch ($selectData['terminal_cd']) {
            case '1':
                $mailTemplate = '/pcr_user_departure_ivr.txt';
                break;
            case '2':
                $mailTemplate = '/pcr_user_arrival_ivr.txt';
                break;
            case '3':
            default:
                $mailTemplate = '/pcr_user_ivr.txt';
                break;
        }

        $centerMailService = new Sgmov_Service_CenterMail();
        $centerMailService->_sendThankYouMail($mailTemplate, $mailData['mail'], $mailData);

        // if ($selectData["send_result"] == 4) {
        //     // テンプレートメールを送信する（旅客手荷物受付サービスのお申し込み）
        //     Sgmov_Component_Mail::sendTemplateMail($selectData, dirname(__FILE__) . '/../../lib/mail_template/bcr_user_pcr.txt',
        //     $selectData["mail"]);
        // }

        //バッチステータスを更新
        //$db = Sgmov_Component_DB::getAdmin();
        //$db->begin();
        //$db->executeUpdate("UPDATE cruise SET batch_status='5',modified = current_timestamp WHERE id=$1;", array($selectData['id']));
        //$db->commit();
        //$selectData["batch_status"] = 5;

        return $selectData;
    }

    /**
     * DB値から送信用csvファイル作成
     * @param object $selectData
     * @return string $csv
     */
    public function makeIFcsv($selectData)
    {

        $csv = "";
        $csv .= "\"HEADER\"";
        $csv .= "\r\n";
        $csv .= $this->setCRUISE($selectData);
        $csv .= "\"TRAILER\"";

        return $csv;
    }

    /**
     * CRUISEセット
     * @param object $selectData
     * @return
     */
    public function setCRUISE($selectData)
    {
        if (mb_strlen($selectData['departure_address'], 'UTF-8') > 40 || mb_strlen($selectData['departure_building'], 'UTF-8') > 40) {
            $temp_departure_address = $selectData['departure_address'] . $selectData['departure_building'];
            $selectData['departure_address']  = mb_substr($temp_departure_address, 0, 40, 'UTF-8');
            $selectData['departure_building'] = mb_substr($temp_departure_address, 40, 80, 'UTF-8');
        }

        if (mb_strlen($selectData['arrival_address'], 'UTF-8') > 40 || mb_strlen($selectData['arrival_store_name'], 'UTF-8') > 40) {
            $temp_arrival_address = $selectData['arrival_address'] . $selectData['arrival_store_name'];
            $selectData['arrival_address']    = mb_substr($temp_arrival_address, 0, 40, 'UTF-8');
            $selectData['arrival_store_name'] = mb_substr($temp_arrival_address, 40, 80, 'UTF-8');
        }

        // 登録
        $sample = array(
            $selectData['id'], // CSVの送り先DBで左記項目は最大8桁
            $selectData['merchant_result'],
            $selectData['merchant_date'],
            $selectData['merchant_time'],
            $selectData['receipted_date'],
            $selectData['receipted_time'],
            $selectData['surname'],
            $selectData['forename'],
            $selectData['surname_furigana'],
            $selectData['forename_furigana'],
            $selectData['number_persons'],
            $selectData['tel'],
            $selectData['mail'],
            $selectData['zip'],
            $selectData['pref_ifid'],
            $selectData['address'],
            $selectData['building'],
            $selectData['travel_id'],
            $selectData['room_number'],
            $selectData['terminal_cd'],
            $selectData['departure_quantity'],
            $selectData['arrival_quantity'],
            $selectData['departure_zip'],
            $selectData['departure_pref_ifid'],
            $selectData['departure_address'],
            $selectData['departure_building'],
            $selectData['departure_departure_date'],
            $selectData['departure_departure_time'],
            '', //$selectData['departure_store_name'],
            $selectData['departure_tel'],
            $selectData['cargo_collection_date'],
            $selectData['cargo_collection_st_time'],
            $selectData['cargo_collection_ed_time'],
            $selectData['arrival_zip'],
            $selectData['arrival_pref_ifid'],
            $selectData['arrival_address'],
            $selectData['arrival_store_name'], //$selectData['arrival_building'],
            $selectData['arrival_arrival_date'],
            $selectData['arrival_arrival_time'],
            '', //$selectData['arrival_store_name'],
            $selectData['arrival_tel'],
            $selectData['departure_charg1'],
            $selectData['arrival_charg2'],
            $selectData['round_trip_discount'],
            $selectData['departure_client_cd'],
            $selectData['departure_client_branch_cd'],
            $selectData['arrival_client_cd'],
            $selectData['arrival_client_branch_cd'],
            $selectData['payment_method_cd'],
            $selectData['convenience_store_cd'],
            $selectData['authorization_cd'],
            $selectData['receipt_cd'],
            $selectData['repeater_flag'],
            $selectData['repeater_discount'],
            $selectData['toiawase_no_departure'],
            $selectData['toiawase_no_arrival'],
            $selectData['req_flg'],
        );

        // ダブルクォーテーションで囲んでつなげる
        $ret = '"CRUISE"';
        foreach ($sample as $item) {
            $ret .= ',' . $this->escapeIFcsv($item);
        }
        $ret .= "\r\n";

        return $ret;
    }

    /**
     * 値に対して、IFcsv用のエスケープ処理を行う
     * @param string $str
     * @return string $str
     */
    public function escapeIFcsv($str)
    {

        $str = str_replace("\r\n", "\n", $str);//改行コードを統一
        $str = str_replace("\r", "\n", $str);//改行コードを統一
        $str = str_replace("\n", '\r\n', $str);//改行コードを統一
        $str = str_replace('\\', '\\\\', $str);//\→\\に置換
        $str = str_replace(",", "\\,", $str);//,→\,に置換
        $str = str_replace('"', '\"', $str);//"→\"に置換
        $str = '"' . $str . '"';
        $str = mb_convert_encoding($str, 'SJIS-win', 'UTF-8');

        return $str;
    }
    

    private function generateCargoDateTime($selectData) {
        $cargoDate = '';
        $cargoStTime = '';
        $cargoEdTime = '';

        if (mb_strlen($selectData['cargo_collection_date']) == 8) {
            $year = substr($selectData['cargo_collection_date'], 0, -4);
            $month = ltrim(substr($selectData['cargo_collection_date'], 4, -2), '0');
            $day = ltrim(substr($selectData['cargo_collection_date'], 6), '0');
            $cargoDate = $year . '年' . $month . '月' . $day . '日';
        }

        if (!empty($selectData['cargo_collection_st_time']) && $selectData['cargo_collection_st_time'] != null) {
            if (mb_strlen($selectData['cargo_collection_st_time']) == 4) {
                $time = substr($selectData['cargo_collection_st_time'], 0, -2);
                $cargoStTime = ltrim($time, '0') . '時';
            }
        } else {
            $cargoStTime = '指定なし';
        }

        if (!empty($selectData['cargo_collection_ed_time']) && $selectData['cargo_collection_ed_time'] != null) {
            if (mb_strlen($selectData['cargo_collection_ed_time']) == 4) {
                $time = substr($selectData['cargo_collection_ed_time'], 0, -2);
                $cargoEdTime = ltrim($time, '0') . '時';
            }
        } else {
            $cargoEdTime = '指定なし';
        }

        return array($cargoDate, $cargoStTime, $cargoEdTime);
    }

    /**
     * 入力フォームの値を元にメール送信用データを生成します。
     * @param Sgmov_Form_Pin001In $inForm 入力フォーム
     * @return array インサート用データ
     */
    private function createMailDataByInForm($selectData) {

        $db = Sgmov_Component_DB::getAdmin();
        $travelTerminalService = new Sgmov_Service_TravelTerminal();
        
        $departureName = $travelTerminalService->fetchDepartureNameForIVR($db, $selectData['id']);
        $arrivalName = $travelTerminalService->fetchArrivalNameForIVR($db, $selectData['id']);

        list($cargoDate, $cargoStTime, $cargoEdTime) = $this->generateCargoDateTime($selectData);

        $deliveryCharge = $selectData['departure_charg1'] * intval($selectData['departure_quantity'])
                                   + $selectData['arrival_charg2'] * intval($selectData['arrival_quantity'])
                                   - ($selectData['round_trip_discount']) * min($selectData['departure_quantity'], $selectData['arrival_quantity']);
        $data = array(
            'surname'                  => $selectData['surname_furigana'],
            'forename'                 => $selectData['forename_furigana'],
            'surname_furigana'         => $selectData['surname_furigana'],
            'forename_furigana'        => $selectData['forename_furigana'],
            'number_persons'           => $selectData['number_persons'],
            'mail'                     => $selectData['mail'],
            'tel'                      => $selectData['tel'],
            'zip'                      => $selectData['zip'],
            'pref_name'                => $selectData['pref_name'],
            'address'                  => $selectData['address'],
            'building'                 => $selectData['building'],
            'travel_name'              => $selectData['travel_name'],
            'room_number'              => $selectData['room_number'],
            'departure_quantity'       => $selectData['departure_quantity'],
            'arrival_quantity'         => $selectData['arrival_quantity'],
            'departure_name'           => $departureName,
            'cargo_collection_date'    => $cargoDate,
            'cargo_collection_st_time' => $cargoStTime,
            'cargo_collection_ed_time' => $cargoEdTime,
            'arrival_name'             => $arrivalName,
            'amount'                   => '\\' . number_format(ceil((string)($deliveryCharge / self::CURRENT_TAX))),
            'amount_tax'               => '\\' . number_format($deliveryCharge),
            'toiawase_no_departure'    => $selectData['toiawase_no_departure'],
            'toiawase_no_arrival'      => $selectData['toiawase_no_arrival'],
            'convenience_store_late'   => '',
            'payment_method'           => $selectData['payment_method'],
        );

        // 受付番号
        $data['mail_receipt_cd'] = $selectData['id'];//コールセンターのみ、クルールのIDを設定する

        // 集荷の往復
        switch ($selectData['terminal_cd']) {
            case '1':
                $data['terminal'] = '往路のみ';
                break;
            case '2':
                $data['terminal'] = '復路のみ';
                break;
            case '3':
                $data['terminal'] = '往復';
                break;
            default:
                $data['terminal'] = '';
                break;
        }
        
        return $data;
    }
}
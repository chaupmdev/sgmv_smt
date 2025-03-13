<?php
/**
 * BTU/Send 単身カーゴプランのお申し込み送信バッチの、データ抽出＆チェック機能です。
 * @package    maintenance
 * @subpackage BTU
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('CommonConst');
Sgmov_Lib::useAllComponents(FALSE);
Sgmov_Lib::useServices('CenterMail');
Sgmov_Lib::useprocess(array('BtuSender', 'BtuResponse'));
/**#@-*/

class Sgmov_Process_Btu extends Sgmov_Process_BtuSender
{

    /**
     * 起動チェックファイル名
     */
    const OPRATION_FILE_NAME = 'operation_btu.txt';

    public function execute()
    {

        // バッチ起動チェックと起動
        $check1 = $this->startBtucheck(Sgmov_Lib::getLogDir() . '/' . self::OPRATION_FILE_NAME);
        if ($check1 === false) {
            $this->errorInformation('startBtu');
        }

        // 1件以上対象があればバッチ処理の実行
        $alldata = $this->selectData();
        if ($alldata->size() > 0) {
            for ($i = 0; $i < $alldata->size(); ++$i) {
                $row = $alldata->get($i);
                $this->btuOutline($row);
            }
        }

        // バッチ終了処理
        $check2 = $this->stopBtu(Sgmov_Lib::getLogDir() . '/' . self::OPRATION_FILE_NAME);
        if ($check2 == false) {
            $this->errorInformation('stopBtu');
        }
    }

    /**
     * バッチ起動チェック
     * @param object $file
     * @return true or false
     */
    public function startBtucheck($file)
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
    public function stopBtu($file)
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
        Sgmov_Component_Mail::sendTemplateMail($status, dirname(__FILE__) . '/../../lib/mail_template/btu_error.txt', $mail_to);
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
        dat_cargo.crg_id                            AS ID,
        dat_cargo.crg_binshu,
        dat_cargo.crg_merchant_result,
        TO_CHAR(dat_cargo.crg_datetime,'YYYYMMDD')  AS CARGO_DATE,
        TO_CHAR(dat_cargo.crg_datetime,'HH24MI')    AS CARGO_TIME,
        TO_CHAR(dat_cargo.crg_receipted,'YYYYMMDD') AS RECEIPTED_DATE,
        TO_CHAR(dat_cargo.crg_receipted,'HH24MI')   AS RECEIPTED_TIME,
        dat_cargo.crg_name1 || ' ' || crg_name2     AS NAME,
        dat_cargo.crg_telno,
        dat_cargo.crg_faxno,
        dat_cargo.crg_mail,
        replace(dat_cargo.crg_shukamoto_yubin, '-', '')               AS SHUKAMOTO_YUBIN,
        CASE
            WHEN dat_cargo.crg_shukamoto_ken >= 18
                THEN dat_cargo.crg_shukamoto_ken + 5
            ELSE dat_cargo.crg_shukamoto_ken + 4
        END                                                           AS FROM_AREA_ID,
        PREF1.if_prefecture_code                                      AS SHUKAMOTO_PREF_IFID,
        PREF1.name                                                    AS SHUKAMOTO_PREF_NAME,
        dat_cargo.crg_shukamoto_shi || dat_cargo.crg_shukamoto_banchi AS SHUKAMOTO_ADDRESS,
        dat_cargo.crg_haisosaki_name,
        replace(dat_cargo.crg_haisosaki_yubin, '-', '')               AS HAISOSAKI_YUBIN,
        PREF2.if_prefecture_code                                      AS HAISOSAKI_PREF_IFID,
        PREF2.name                                                    AS HAISOSAKI_PREF_NAME,
        dat_cargo.crg_haisosaki_shi || crg_haisosaki_banchi           AS HAISOSAKI_ADDRESS,
        dat_cargo.crg_haisosaki_telno,
        dat_cargo.crg_haisosaki_renraku,
        TO_CHAR(dat_cargo.crg_hanshutsu_dt,'YYYYMMDD')                AS HANSHUTSU_DATE,
        TO_CHAR(dat_cargo.crg_hanshutsu_dt,'YYYY年MM月DD日')          AS MAIL_HANSHUTSU_DATE,
        dat_cargo.crg_hansuhtsu_time,
        TO_CHAR(dat_cargo.crg_hannyu_dt,'YYYYMMDD')                   AS HANNYU_DATE,
        TO_CHAR(dat_cargo.crg_hannyu_dt,'YYYY年MM月DD日')             AS MAIL_HANNYU_DATE,
        dat_cargo.crg_hannyu_time,
        dat_cargo.crg_daisu,
        dat_cargo.crg_hinmoku,
        ITEM.cth_hinmoku_mei                                          AS HINMOKU_MEI,
        dat_cargo.crg_kihon_ryokin,
        dat_cargo.crg_hanshutsu_kei,
        dat_cargo.crg_hannyu_kei,
        dat_cargo.crg_hanbai_kakaku,
        dat_cargo.crg_hanbai_kakaku_zeigaku,
        dat_cargo.crg_hanbai_kakaku + crg_hanbai_kakaku_zeigaku       AS HANBAI_KAKAKU_TOTAL,
        dat_cargo.crg_send_result                                     AS SEND_RESULT,
        dat_cargo.crg_batch_status                                    AS BATCH_STATUS,
        dat_cargo.crg_retry_count                                     AS RETRY_COUNT,
        dat_cargo.crg_payment_method_cd,
        CASE dat_cargo.crg_payment_method_cd
            WHEN 1
                THEN 'コンビニ決済'
            WHEN 2
                THEN 'クレジットカード'
            ELSE ''
        END                                                           AS PAYMENT_METHOD,
        dat_cargo.crg_convenience_store_cd,
        CASE dat_cargo.crg_convenience_store_cd
            WHEN 1
                THEN 'セブンイレブン'
            WHEN 2
                THEN 'ローソン、セイコーマート、ファミリーマート、サークルＫサンクス、ミニストップ'
            WHEN 3
                THEN 'デイリーヤマザキ'
            ELSE ''
        END                                                           AS CONVENIENCE_STORE,
        dat_cargo.crg_authorization_cd,
        dat_cargo.crg_receipt_cd,
        dat_cargo.crg_insert_date
    FROM
        dat_cargo
        INNER JOIN
        (
            SELECT
                cop_crg_id
            FROM
                dat_cargo_opt
            GROUP BY
                cop_crg_id
        ) AS CARGO_OPT
        ON
            dat_cargo.crg_id = CARGO_OPT.cop_crg_id
        LEFT OUTER JOIN
        prefectures AS PREF1
        ON
            dat_cargo.crg_shukamoto_ken = PREF1.prefecture_id
        LEFT OUTER JOIN
        prefectures AS PREF2
        ON
            dat_cargo.crg_haisosaki_ken = PREF2.prefecture_id
        LEFT OUTER JOIN
        mst_cargo_tanpin_hinmoku AS ITEM
        ON
            dat_cargo.crg_hinmoku = ITEM.cth_hinmoku_code
    WHERE
            crg_batch_status IN (1,2,3)
        AND dat_cargo.crg_id <= 99999999
    ORDER BY
        dat_cargo.crg_id;";

        $selectData = $db->executeQuery($sql);

        return $selectData;
    }

    /**
     * バッチメイン処理
     * @param object $selectData
     * @return
     */
    public function btuOutline($selectData)
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
        //$selectData = $this->setData($selectData);

        if ($selectData["batch_status"] == 3) {
            //担当者へメール送信
            $selectData = $this->SendMailTanto($selectData);
        }

        // 顧客へのメールはお申し込み時に送信する
        //if ($selectData["batch_status"] == 4) {
        //    //顧客へ完了メール送信
        //    $selectData = $this->SendMailCustomer($selectData);
        //}
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
        $res = Sgmov_Process_BtuSender::sendCsvToWs('MITUMORI_' . date('YmdHis') . '.csv', $csvdata);

        $responce = new Sgmov_Process_BtuResponse;
        $responce->initialize($res);

        // レスポンス値によって処理のふりわけ
        switch ($responce->sendSts) {
        // 成功：update バッチ処理状況「送信済」 送信結果「成功」
        case 0:
            $db = Sgmov_Component_DB::getAdmin();
            $db->begin();
            $db->executeUpdate("UPDATE dat_cargo SET crg_batch_status='2',crg_send_result='3',crg_sent = current_timestamp,crg_update_date = current_timestamp WHERE crg_id=$1;", array($selectData['id']));
            $db->commit();
            $selectData["batch_status"] = 2;
            $selectData["send_result"] = 3;
            break;

        //不正データ：update バッチ処理状況「送信済」 送信結果「失敗」
        case 1:
            $db = Sgmov_Component_DB::getAdmin();
            $db->begin();
            $db->executeUpdate("UPDATE dat_cargo SET crg_batch_status='2',crg_send_result='1',crg_sent = current_timestamp,crg_update_date = current_timestamp WHERE crg_id=$1;", array($selectData['id']));
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
            $db->executeUpdate("UPDATE dat_cargo SET crg_retry_count=crg_retry_count+1,crg_sent = current_timestamp,crg_update_date = current_timestamp WHERE crg_id=$1;", array($selectData['id']));
            $db->commit();
            ++$selectData["retry_count"];

            // 送信リトライ階数が21以上の場合バッチ処理状況「送信済」 送信結果「リトライオーバー」
            if ($selectData["retry_count"] >= 21) {
                $db = Sgmov_Component_DB::getAdmin();
                $db->begin();
                $db->executeUpdate("UPDATE dat_cargo SET crg_batch_status='2',crg_send_result='2',crg_sent = current_timestamp,crg_update_date = current_timestamp WHERE crg_id=$1;", array($selectData['id']));
                $db->commit();
                $selectData["batch_status"] = 2;
                $selectData["send_result"] = 2;
            }
            break;

        // 登録済み：update バッチ処理状況「送信済」 送信結果「成功」
        case 4:
            $db = Sgmov_Component_DB::getAdmin();
            $db->begin();
            $db->executeUpdate("UPDATE dat_cargo SET crg_batch_status='2',crg_send_result='3',crg_sent = current_timestamp,crg_update_date = current_timestamp WHERE crg_id=$1;", array($selectData['id']));
            $db->commit();
            $selectData["batch_status"] = 2;
            $selectData["send_result"] = 3;
            break;

        //それ以外 送信リトライ数「+1」 送信リトライ階数が21以上の場合バッチ処理状況「送信済」 送信結果「リトライオーバー」（タイムアウト）
        default:
            $db = Sgmov_Component_DB::getAdmin();
            $db->begin();
            $db->executeUpdate("UPDATE dat_cargo SET retry_count=retry_count+1,crg_sent = current_timestamp,crg_update_date = current_timestamp WHERE crg_id=$1;", array($selectData['id']));
            $db->commit();
            ++$selectData["retry_count"];
            if ($selectData["retry_count"] >= 21) {
                $db = Sgmov_Component_DB::getAdmin();
                $db->begin();
                $db->executeUpdate("UPDATE dat_cargo SET crg_batch_status='2',crg_send_result='2',crg_sent = current_timestamp,crg_update_date = current_timestamp WHERE crg_id=$1;", array($selectData['id']));
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
            Sgmov_Component_Mail::sendTemplateMail($selectData, dirname(__FILE__) . '/../../lib/mail_template/btu_error_send.txt', $mail_to);
        }
        $db = Sgmov_Component_DB::getAdmin();
        $db->begin();
        $db->executeUpdate("UPDATE dat_cargo SET crg_batch_status='3',crg_update_date = current_timestamp WHERE crg_id=$1;", array($selectData['id']));
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
            switch ($selectData['crg_binshu']) {
                case '1':
                    //単身カーゴプランのお申し込み
                    $mailTemplate = '/btu_admin_ptu_alone.txt';
                    break;
                case '2':
                    //単品輸送プランのお申し込み
                    $mailTemplate = '/btu_admin_ptu_single.txt';
                    break;
            }
            $_centerMailService->_sendAdminMailByFromAreaId($db, Sgmov_Service_CenterMail::FORM_KBN_PTU, $selectData['from_area_id'], $selectData, $mailTemplate);
        }

        //バッチステータスを更新
        //$db = Sgmov_Component_DB::getAdmin();
        $db->begin();
        $db->executeUpdate("UPDATE dat_cargo SET crg_batch_status='4',crg_update_date = current_timestamp WHERE crg_id=$1;", array($selectData['id']));
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

        if ($selectData["send_result"] == 4) {
            // テンプレートメールを送信する（単身カーゴプランのお申し込み）
            Sgmov_Component_Mail::sendTemplateMail($selectData, dirname(__FILE__) . '/../../lib/mail_template/btu_user_ptu.txt',
            $selectData["mail"]);
        }

        //バッチステータスを更新
        $db = Sgmov_Component_DB::getAdmin();
        $db->begin();
        $db->executeUpdate("UPDATE dat_cargo SET crg_batch_status='5',crg_update_date = current_timestamp WHERE crg_id=$1;", array($selectData['id']));
        $db->commit();
        $selectData["batch_status"] = 5;

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
        $csv .= $this->setCARGO($selectData);
        $csv .= "\"TRAILER\"";

        return $csv;
    }

    /**
     * CARGOセット
     * @param object $selectData
     * @return string $ret
     */
    public function setCARGO($selectData)
    {
        // 登録
        $sample = array(
            $selectData['id'],
            $selectData['crg_binshu'],
            $selectData['crg_merchant_result'],
            $selectData['cargo_date'],
            $selectData['cargo_time'],
            $selectData['receipted_date'],
            $selectData['receipted_time'],
            $selectData['name'],
            $selectData['crg_telno'],
            $selectData['crg_faxno'],
            $selectData['crg_mail'],
            $selectData['shukamoto_yubin'],
            $selectData['shukamoto_pref_ifid'],
            $selectData['shukamoto_address'],
            $selectData['crg_haisosaki_name'],
            $selectData['haisosaki_yubin'],
            $selectData['haisosaki_pref_ifid'],
            $selectData['haisosaki_address'],
            $selectData['crg_haisosaki_telno'],
            $selectData['crg_haisosaki_renraku'],
            $selectData['hanshutsu_date'],
            $selectData['crg_hansuhtsu_time'],
            $selectData['hannyu_date'],
            $selectData['crg_hannyu_time'],
            $selectData['crg_daisu'],
            $selectData['crg_hinmoku'],
            $selectData['crg_kihon_ryokin'],
            $selectData['crg_hanshutsu_kei'],
            $selectData['crg_hannyu_kei'],
            $selectData['crg_hanbai_kakaku'],
            $selectData['crg_hanbai_kakaku_zeigaku'],
            $selectData['hanbai_kakaku_total'],
            $selectData['crg_payment_method_cd'],
            $selectData['crg_convenience_store_cd'],
            $selectData['crg_authorization_cd'],
            $selectData['crg_receipt_cd'],
        );

        // ダブルクォーテーションで囲んでつなげる
        $ret = '"H"';
        foreach ($sample as $item) {
            $ret .= ',' . $this->escapeIFcsv($item);
        }
        $ret .= "\r\n";

        $sql = "
    SELECT
        dat_cargo_opt.cop_crg_id,
        dat_cargo_opt.cop_mco_code,
        dat_cargo_opt.cop_chumon_num
    FROM
        dat_cargo_opt
    WHERE
        dat_cargo_opt.cop_crg_id = $1
    ORDER BY
        dat_cargo_opt.cop_crg_id,
        dat_cargo_opt.cop_mco_code,
        dat_cargo_opt.cop_chumon_num;";

        $db = Sgmov_Component_DB::getAdmin();
        $selectDataOpt = $db->executeQuery($sql, array($selectData['id']));

        for ($i = 0; $i < $selectDataOpt->size(); ++$i) {
            $row = $selectDataOpt->get($i);

            $sample = array(
                $row['cop_crg_id'],
                $row['cop_mco_code'],
                $row['cop_chumon_num'],
            );

            // ダブルクォーテーションで囲んでつなげる
            $ret .= '"M"';
            foreach ($sample as $item) {
                $ret .= ',' . $this->escapeIFcsv($item);
            }
            $ret .= "\r\n";
        }

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
}
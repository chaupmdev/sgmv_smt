<?php
/**
 * @package    ClassDefFile
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useAllComponents();
Sgmov_Lib::useView('Public');
/**#@-*/

/**
 * 旅客手荷物受付サービスのお申し込み内容をCSVファイルでダウンロードします。
 * @package    View
 * @subpackage ACR
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Acr_DownloadCsv extends Sgmov_View_Public {

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
    }

    public function executeInner() {

        $date = new DateTime();

        if (isset($_POST['year'])) {
            $year = intval($_POST['year']);
        }

        if (empty($year)) {
            $year = $date->format('Y');
        }

        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        $query = "
            SELECT
                cruise.id,
                CASE WHEN cruise.merchant_result IS NULL THEN '未送信'
                     WHEN cruise.merchant_result = '0' THEN '送信失敗'
                     WHEN cruise.merchant_result = '1' THEN '送信成功'
                END                                                                   AS MERCHANT_RESULT,
                TO_CHAR(cruise.created, 'YYYY年MM月DD日 HH24時MI分SS.US秒')           AS CREATED,
                TO_CHAR(cruise.merchant_datetime, 'YYYY年MM月DD日 HH24時MI分SS.US秒') AS MERCHANT_DATETIME,
                TO_CHAR(cruise.receipted, 'YYYY年MM月DD日 HH24時MI分SS.US秒')         AS RECEIPTED,
                CASE WHEN cruise.req_flg = '1' AND cruise.call_merchant_result IS NULL AND cruise.payment_method_cd = 2 THEN '未送信'
                    WHEN cruise.req_flg = '1' AND cruise.call_merchant_result = '0' THEN '0:成功'
                    WHEN cruise.req_flg = '1' AND cruise.call_merchant_result = '1' THEN '1:失敗'
                    WHEN cruise.req_flg = '1' AND cruise.call_merchant_result = '2' THEN '2:その他'
                    WHEN cruise.req_flg = '1' AND cruise.call_merchant_result = '3' THEN '3:エラー'
                    WHEN cruise.req_flg = '1' AND cruise.call_merchant_result = '4' THEN '4:決済データ送信から時間超過'
                    WHEN cruise.req_flg = '1' AND cruise.call_merchant_result = '5' THEN '5:未転送状態から時間超過'
                END                                                                 AS CALL_MERCHANT_RESULT,
                CASE cruise.send_result
                    WHEN '0' THEN '未送信'
                    WHEN '1' THEN '送信失敗'
                    WHEN '2' THEN 'リトライオーバー'
                    WHEN '3' THEN (CASE WHEN cruise.receipted >= cruise.cargo_collection_date THEN '送信成功（入金遅延）' ELSE '送信成功' END)
                END                                                                   AS SEND_RESULT,
                TO_CHAR(cruise.sent, 'YYYY年MM月DD日 HH24時MI分SS.US秒')              AS SENT,
                CASE cruise.batch_status
                    WHEN '0' THEN '未入金'
                    WHEN '1' THEN '入金済'
                    WHEN '2' THEN '連携データ送信済'
                    WHEN '3' THEN '管理者メール済'
                    WHEN '4' THEN '完了(担当者メール済)'
                END                                                                   AS BATCH_STATUS,
                cruise.retry_count,
                cruise.surname,
                cruise.forename,
                cruise.surname_furigana,
                cruise.forename_furigana,
                cruise.number_persons,
                cruise.tel,
                cruise.mail,
                cruise.zip,
                prefectures.name                                                      AS PREFECTURES_NAME,
                cruise.address,
                cruise.building,
                travel.name                                                           AS TERMINAL_NAME,
                cruise.room_number,
                CASE cruise.terminal_cd
                    WHEN '1' THEN '往路のみ'
                    WHEN '2' THEN '復路のみ'
                    WHEN '3' THEN '往復'
                END                                                                   AS TERMINAL_CD,
                cruise.departure_quantity,
                cruise.arrival_quantity,
                TRAVEL_TERMINAL1.name                                                 AS TRAVEL_TERMINAL_NAME1,
                TO_CHAR(cruise.cargo_collection_date, 'YYYY年MM月DD日')               AS CARGO_COLLECTION_DATE,
                TO_CHAR(cruise.cargo_collection_st_time, 'HH24時MI分')                AS CARGO_COLLECTION_ST_TIME,
                TO_CHAR(cruise.cargo_collection_ed_time, 'HH24時MI分')                AS CARGO_COLLECTION_ED_TIME,
                TRAVEL_TERMINAL2.name                                                 AS TRAVEL_TERMINAL_NAME2,
                CASE cruise.payment_method_cd
                    WHEN '1' THEN 'コンビニ決済'
                    WHEN '2' THEN 'クレジットカード'
                END                                                                   AS PAYMENT_METHOD_CD,
                CASE cruise.convenience_store_cd
                    WHEN '1' THEN 'セブンイレブン'
                    WHEN '2' THEN 'イーコンテクスト決済(ローソン、セイコーマート、ファミリーマート、サークルＫサンクス、ミニストップ)'
                    WHEN '3' THEN 'その他(デイリーヤマザキ)'
                END                                                                   AS CONVENIENCE_STORE_CD,
                cruise.authorization_cd,
                cruise.receipt_cd,
                cruise.payment_order_id,
                TO_CHAR(cruise.created, 'YYYY年MM月DD日 HH24時MI分SS.US秒')           AS CREATED,
                TO_CHAR(cruise.modified, 'YYYY年MM月DD日 HH24時MI分SS.US秒')          AS MODIFIED
            FROM
                cruise
                INNER JOIN
                travel
                ON
                    cruise.travel_id = travel.id
                LEFT OUTER JOIN
                travel_terminal              AS TRAVEL_TERMINAL1
                ON
                    cruise.travel_departure_id = TRAVEL_TERMINAL1.id
                AND travel.id                  = TRAVEL_TERMINAL1.travel_id
                LEFT OUTER JOIN
                travel_terminal              AS TRAVEL_TERMINAL2
                ON
                    cruise.travel_arrival_id = TRAVEL_TERMINAL2.id
                AND travel.id                = TRAVEL_TERMINAL2.travel_id
                LEFT OUTER JOIN
                prefectures
                ON
                    cruise.pref_id = prefectures.prefecture_id
            WHERE
                TO_CHAR(cruise.created, 'YYYY') = '" . pg_escape_string($year) . "'
            ORDER BY
                cruise.id;";

        $handle = fopen('php://memory', 'w+');
        $header = array(
            "お申し込みID",
            "決済データ送信結果",
            "登録日時(決済画面が表示された日時)",
            "決済データ送信日時",
            "入金確認日時",
            "IVR決済成功可否",
            "連携データ送信結果",
            "連携データ送信日時",
            "バッチ処理状況",
            "送信リトライ数",
            "お名前 姓",
            "お名前 名",
            "お名前フリガナ 姓",
            "お名前フリガナ 名",
            "同行のご 家族人数",
            "電話番号",
            "メールアドレス",
            "郵便番号",
            "都道府県",
            "市区町村",
            "番地・建物名",
            "乗船日",
            "乗船後の部屋番号",
            "集荷の往復",
            "往路 個数",
            "復路 個数",
            "出発地",
            "集荷希望日",
            "集荷希望開始時刻",
            "集荷希望終了時刻",
            "到着地",
            "お支払方法",
            "コンビニ決済お支払店舗",
            "クレジッ トカード決済時の承認番号",
            "コンビニ決済時の受付番号",
            "決済取引ID",
            "登録日時",
            "更新日時",
        );
        fwrite($handle, '"' . implode('","', $header) . '"' . PHP_EOL);

        $result = $db->executeQuery($query);
        $size = $result->size();
        for ($i = 0; $i < $size; ++$i) {
            $row = $result->get($i);
            fputcsv($handle, (array)$row);
        }
        rewind($handle);
        $csv = str_replace(PHP_EOL, "\r\n", stream_get_contents($handle));

        $filename = $date->format('Y-m-d-His') . '.csv';

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $filename);
        echo mb_convert_encoding($csv, 'SJIS-win', 'UTF-8');
    }

    public function getFeatureId() {
    }
}
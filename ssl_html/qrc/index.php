<?php
session_start();
/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
require_once dirname(__FILE__) . '/../../lib/component/Redirect.php';
// イベント識別子(URLのpath)はマジックナンバーで記述せずこの変数で記述してください
$dirDiv = Sgmov_Lib::setDirDiv(dirname(__FILE__));
Sgmov_Lib::useView($dirDiv . '/CheckInput');

if (isset($_GET["TEST"])) {
    $CD = Sgmov_Lib::getCheckDigit($_GET["UKETSUKE_NO"] .
        $_GET["TOIAWASE_NO"] .
        $_GET["ARK_UKETSUKE_NO"] .
        $_GET["KESSAI_MEISAI_ID"] .
        $_GET["URIAGE_KINGAKU"] .
        $_GET["SYSTEM_KBN"]);

    echo "CD:" . $CD;

    exit();
}

// ベーシック認証
//require_once('../../lib/component/auth.php');

/**GETパラーメータ受け取り後カード決済画面へ遷移のテスト
 *
 * >　SAGYOIRAI_NO         作業依頼番号（MV-BASEは①＋②＋④でキー） 　
 * >　TOIAWASE_NO          問い合わせ番号（MV-BASEは①＋②＋④でキー）
 * >　ARK_UKETSUKE_NO  SG-ARKの受付番号（SG-ARKの場合③のみでキー）
 * >　KESSAI_MEISAI_ID　　決済明細ID　　　　　　
 * >　URIAGE_KINGAKU    金額（税込）
 * >　SYSTEM_KBN           区分（0:MV-BASE、1:SG-ARＫ）
 * >　CD           チェックデジット（モジュラス11 ウェイト2－7）
 */
$bit = 0;
if (isset($_GET["UKETSUKE_NO"])) $bit += 1;
if (isset($_GET["TOIAWASE_NO"])) $bit += 2;
if (isset($_GET["ARK_UKETSUKE_NO"])) $bit += 4;
if (isset($_GET["KESSAI_MEISAI_ID"])) $bit += 8;
if (isset($_GET["URIAGE_KINGAKU"])) $bit += 16;
if (isset($_GET["SYSTEM_KBN"])) $bit += 32;
if (isset($_GET["CD"])) $bit += 64;
// echo "UKETSUKE_NO>" . mb_strlen($_GET["UKETSUKE_NO"]) . "<br>";
// echo "TOIAWASE_NO>" . mb_strlen($_GET["TOIAWASE_NO"]) . "<br>";
// echo "ARK_UKETSUKE_NO>" . mb_strlen($_GET["ARK_UKETSUKE_NO"]) . "<br>";
// echo "KESSAI_MEISAI_ID>" . mb_strlen($_GET["KESSAI_MEISAI_ID"]) . "<br>";
// echo "URIAGE_KINGAKU>" . mb_strlen($_GET["URIAGE_KINGAKU"]) . "<br>";
// echo "SYSTEM_KBN>" . mb_strlen($_GET["SYSTEM_KBN"]) . "<br>";
// echo "CD>" . mb_strlen($_GET["CD"]) . "<br>";
// exit();
//全てのkeyが含まれているかチェック
if ($bit != 127) {
    $url = $_SERVER["HTTP_HOST"] . "/carderr.php";

    $redirectUrl = "https://" . $url;
    header("HTTP/1.0 404 Not Found");
    header("Location: $redirectUrl");

    exit();
}
$param_ok = 0;
if (
    $_GET["SYSTEM_KBN"] == 0
    && mb_strlen($_GET["UKETSUKE_NO"]) == 10
    && mb_strlen($_GET["TOIAWASE_NO"]) == 12
    && mb_strlen($_GET["ARK_UKETSUKE_NO"]) == 0
    && (int)$_GET["URIAGE_KINGAKU"] > 0
) {
    $param_ok = 1;
} else if (
    $_GET["SYSTEM_KBN"] == 1
    && mb_strlen($_GET["UKETSUKE_NO"]) == 0
    && mb_strlen($_GET["TOIAWASE_NO"]) == 0
    && mb_strlen($_GET["ARK_UKETSUKE_NO"]) == 7
    && (int)$_GET["URIAGE_KINGAKU"] > 0
) {
    $param_ok = 1;
}
$CD = Sgmov_Lib::getCheckDigit($_GET["UKETSUKE_NO"] .
    $_GET["TOIAWASE_NO"] .
    $_GET["ARK_UKETSUKE_NO"] .
    $_GET["KESSAI_MEISAI_ID"] .
    $_GET["URIAGE_KINGAKU"] .
    $_GET["SYSTEM_KBN"]);

// echo "CD:" . $CD;
$_POST['CD'] = $CD;
if ($CD != $_GET["CD"]) {

    $url = $_SERVER["HTTP_HOST"] . "/qrc/cd_err.php";

    $redirectUrl = "https://" . $url;
    header("HTTP/1.0 404 Not Found");
    header("Location: $redirectUrl");

    exit();
}

if (mb_strlen($_GET["UKETSUKE_NO"]) > 0 && !is_numeric($_GET["UKETSUKE_NO"])) $param_ok = 0;
if (mb_strlen($_GET["TOIAWASE_NO"]) > 0 && !is_numeric($_GET["TOIAWASE_NO"])) $param_ok = 0;
if (mb_strlen($_GET["ARK_UKETSUKE_NO"]) > 0 && !is_numeric($_GET["ARK_UKETSUKE_NO"])) $param_ok = 0;
if (mb_strlen($_GET["KESSAI_MEISAI_ID"]) > 0 && !is_numeric($_GET["KESSAI_MEISAI_ID"])) $param_ok = 0;
if (mb_strlen($_GET["URIAGE_KINGAKU"]) > 0 && !is_numeric($_GET["URIAGE_KINGAKU"])) $param_ok = 0;
if (mb_strlen($_GET["SYSTEM_KBN"]) > 0 && !is_numeric($_GET["SYSTEM_KBN"])) $param_ok = 0;
if (mb_strlen($_GET["CD"]) > 0 && !is_numeric($_GET["CD"])) $param_ok = 0;

if ($param_ok == 1) {
    $_POST['UKETSUKE_NO'] = $_GET["UKETSUKE_NO"];

    //問い番を4文字で区切る
    $_POST['TOIAWASE_NO'] = $_GET["TOIAWASE_NO"];
    $TOIA_NO = str_split($_POST['TOIAWASE_NO'], 4);
    $_POST['TOIBAN'] = $TOIA_NO[0] . "-" . $TOIA_NO[1] . "-" . $TOIA_NO[2];

    $_POST['ARK_UKETSUKE_NO'] = $_GET["ARK_UKETSUKE_NO"];
    $_POST['KESSAI_MEISAI_ID'] = $_GET["KESSAI_MEISAI_ID"];
    $_POST['URIAGE_KINGAKU'] = $_GET["URIAGE_KINGAKU"];
    $_POST['SYSTEM_KBN'] = $_GET["SYSTEM_KBN"];
} else {
    //パラメータなし（QRコード以外の遷移では終了）
    // echo "QRコード読み込みエラー もう一度お試しください。";

    $url = $_SERVER["HTTP_HOST"] . "/carderr.php";

    $redirectUrl = "https://" . $url;
    header("HTTP/1.0 404 Not Found");
    header("Location: $redirectUrl");

    exit();
}

/**
 * 手荷物受付サービスのお申し込み入力情報をチェックします。
 * @package    ssl_html
 * @subpackage RMS
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@-*/
$_POST['ticket'] =  "c1921311dd050ff85093a1515b93e6f4";
$_POST['input_mode'] =  "9";
$_POST['input_type_email'] =  "text";
$_POST['input_type_number'] =  "text";
$_POST['comiket_customer_kbn_sel'] =  "0";
$_POST['event_sel'] =  "9";
$_POST['hid_timezone_flg'] =  "0";
$_POST['eventsub_sel'] =  "9";
$_POST['eventsub_address'] =  "QR決済";
$_POST['eventsub_term_fr'] =  "1900-01-01";
$_POST['eventsub_term_to'] =  "9999-12-31";
$_POST['comiket_div'] =  "1";
$_POST['comiket_customer_cd'] =  "";
$_POST['office_name'] =  "";
$_POST['comiket_personal_name_sei'] =  "QR";
$_POST['comiket_personal_name_mei'] =  "決済";
$_POST['comiket_zip1'] =  "617";
$_POST['comiket_zip2'] =  "8588";
$_POST['comiket_pref_cd_sel'] =  "26";
$_POST['comiket_address'] =  "向日市森本町戌亥";
$_POST['comiket_building'] =  "5-3";
$_POST['comiket_tel'] =  "075-934-8002";
$_POST['comiket_mail'] =  "katsu-tamashiro@spcom.co.jp";
$_POST['comiket_mail_retype'] =  "katsu-tamashiro@spcom.co.jp";
$_POST['comiket_detail_type_sel'] =  "2";
$_POST['comiket_detail_inbound_binshu_kbn_sel'] =  "0";
$_POST['comiket_detail_inbound_name'] =  "QR 決済";
$_POST['comiket_detail_inbound_zip1'] =  "617";
$_POST['comiket_detail_inbound_zip2'] =  "8588";
$_POST['comiket_detail_inbound_pref_cd_sel'] =  "26";
$_POST['comiket_detail_inbound_address'] =  "向日市森本町戌亥";
$_POST['comiket_detail_inbound_building'] =  "5-3";
$_POST['comiket_detail_inbound_tel'] =  "075-934-8002";
$_POST['comiket_detail_inbound_collect_date_year_sel'] =  "2022";
$_POST['comiket_detail_inbound_collect_date_month_sel'] =  "03";
$_POST['comiket_detail_inbound_collect_date_day_sel'] =  "19";
$_POST['comiket_detail_inbound_service_sel'] =  "1";
$_POST['hid_comiket-detail-inbound-delivery-date-from'] =  "2022-03-21";
$_POST['hid_comiket-detail-inbound-delivery-date-to'] =  "2022-03-26";
$_POST['hid_comiket-detail-inbound-delivery-date-from_ori'] =  "2022-03-21";
$_POST['hid_comiket-detail-inbound-delivery-date-to_ori'] =  "2999-01-01";
$_POST['comiket_detail_inbound_delivery_date_year_sel'] =  "2999";
$_POST['comiket_detail_inbound_delivery_date_month_sel'] =  "03";
$_POST['comiket_detail_inbound_delivery_date_day_sel'] =  "31";
$_POST['comiket_detail_inbound_delivery_time_sel'] =  "00,指定なし";
$_POST['comiket_box_inbound_num_ary'] = ['13287' => "", '13276' => "1", '13277' => "", '13278' => "", '13279' => "", '13280' => "", '13281' => "", '13282' => "", '13283' => "", '13284' => "", '13285' => "", '13286' => ""];
$_POST['comiket_detail_inbound_note1'] =  "";
$_POST['comiket_payment_method_cd_sel'] =  "2";
$_POST['comiket_convenience_store_cd_sel'] =  "";
$_POST['submit'] =  "同意して次に進む（入力内容の確認）";
$_POST['eventsub_zip'] = '0';
$_POST['comiket_id'] = '';
$_POST['customer_search_btn'] = '0';
$_POST['comiket_detail_inbound_collect_time_sel'] = '2';
$_POST['comiket_cargo_inbound_num_sel'] = '';
$_POST['comiket_charter_inbound_num_ary'] = '';
$_POST['comiket_detail_inbound_note1'] = '';
$_POST['comiket_detail_inbound_note2'] = '';
$_POST['comiket_detail_inbound_note3'] = '';
$_POST['comiket_detail_inbound_note4'] = '';
// 処理を実行
$view = new Sgmov_View_Qrc_CheckInput();
$view->execute();

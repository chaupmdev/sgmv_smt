<?php
/**
 * @package    ページ認証処理
 *             読み込んだページにベーシック認証をかけるか非公開ページへリダイレクトする
 * @author     Y.Fujikawa
 * @copyright  2022 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

if (isset($_GET['param']) && mb_strlen($_GET['param']) > 0) {
    $param = strtolower($_GET['param']);
} else {
    $param = "";
}
// ページ認証種別：変数のコメントアウトを外したほうが有効
// どちらもコメントアウトした場合は普通にサイトを表示します
$pageAuthType =  'basic'; // ベーシック認証
//$pageAuthType ='private'; // 非公開(開催前、開催終了ページにリダイレクト)

// イベント名・公開開始日時
$eventName = 'コミックマーケット105';
$openDateStr = '2024-12-29 00:00:00';
$endDateStr =  '2024-12-30 23:59:00';
$div = 'evp';
if (strpos($param, "mdr") !== false) {
    // イベント名・公開開始日時
    $eventName = 'みどり会冬季優待会';
    $openDateStr = '2023-12-01 15:00:00';
    $endDateStr =  '2023-12-03 20:00:00';
    $div = 'mdr';
} elseif (strpos($param, "twf") !== false) {
    // イベント名・公開開始日時
    $eventName = 'テーブルウェア・フェスティバル　～暮らしを彩る器展～';
    $openDateStr = '2024-11-28 09:00:00';
    $endDateStr =  '2024-12-04 19:00:00';
    $div = 'twf';
} elseif (strpos($param, "twi") !== false) {
    // イベント名・公開開始日時
    $eventName = 'テーブルウェア・フェスティバル　～暮らしを彩る器展～';
    $openDateStr = '2024-11-28 09:00:00';
    $endDateStr =  '2024-12-04 19:00:00';
    $div = 'evp';
} elseif (strpos($param, "hmj") !== false) {
    // イベント名・公開開始日時
    $eventName = 'ハンドメイドインジャパンフェス2024夏';
    $openDateStr = '2024-07-02 00:00:00';
    $endDateStr =  '2024-07-21 23:59:00';
    $div = 'hmj';
} elseif (strpos($param, "yid") !== false) {
    // イベント名・公開開始日時
    $eventName = 'ご当地よいどれ市2024';
    $openDateStr = '2024-03-13 15:00:00';
    $endDateStr =  '2024-03-14 12:00:00';
    $div = 'yid';
} elseif (strpos($param, "dsn") !== false) {
    // イベント名・公開開始日時
    $eventName = 'デザインフェスタ vol.60';
    $openDateStr = '2024-11-16 14:00:00';
    $endDateStr =  '2024-11-17 23:59:00';
    $div = 'dsn';
} elseif (strpos($param, "nss") !== false) {
    // イベント名・公開開始日時
    $eventName = 'にじそうさく09';
    $openDateStr = '2024-06-14 11:30:00';
    $endDateStr =  '2024-07-09 20:00:00';
    $div = 'nss';
}

// ベーシック認証 ID,パスワード
$user = 'sgmv2';
$pass = 'sagawa2';

////////////////////////////////////////////////////////////////////////////////////////////////////
// これ以降のソースは改修時以外触る必要はありません 
////////////////////////////////////////////////////////////////////////////////////////////////////
// 公開開始日時
$openDate = new dateTime($openDateStr);
$endDate = new dateTime($endDateStr);

$prm = array(
    'open' => $openDate,     'event' => $eventName, 'dir'  => $div // evp固定で問題ないです
);

$prm_end = array(
    'open' => $endDate,     'event' => $eventName, 'dir'  => $div // evp固定で問題ないです
);

// 現在日時
$nowDate = new dateTime();
// 現在日時テスト用
//$nowDate= new dateTime('2022-05-05 23:59:59');
//echo('<pre>');var_dump($nowDate);echo('</pre>');
//echo('<pre>');var_dump($openDate);echo('</pre>');

// リダイレクト処理
function redirectKohkaimae($prm){
    $title = urlencode("公開前です");
    $weekDay = ['(日)', '(月)', '(火)', '(水)', '(木)', '(金)', '(土)',];
    $day = $prm['open']->format('Y/m/d');
    $yobi = $weekDay[$prm['open']->format('w')];
    $time = $prm['open']->format('H:i');

    $message = urlencode("「" . $prm['event'] . "」の物販お申込は 「" . $day . " " . $yobi . " " . $time . "」 から受付を開始致します。");
    //$message = "受付は、12月５日(火)0:00から開始になります。";
    $browserTitle = "公開準備中｜SGムービング株式会社＜SGホールディングスグループ＞";
    $browserDiscption = "公開までしばらくお待ちください。";
    header("Location: /" . $prm['dir'] . "/error?t={$title}&m={$message}&bt={$browserTitle}&bd={$browserDiscption}");
    exit;
}

function redirectKohkaiato($prm){
    $title = urlencode("公開終了しています");
    $weekDay = ['(日)', '(月)', '(火)', '(水)', '(木)', '(金)', '(土)',];
    $day = $prm['open']->format('Y/m/d');
    $yobi = $weekDay[$prm['open']->format('w')];
    $time = $prm['open']->format('H:i');

    $message = urlencode("「" . $prm['event'] . "」の物販お申込は 「" . $day . " " . $yobi . " " . $time . "」 をもって終了しました。");
    header("Location: /" . $prm['dir'] . "/error?t={$title}&m={$message}");
    exit;
}

// 公開前
if ($nowDate < $openDate) {

    // 公開前ページを表示する
    if($pageAuthType=='private'){
        redirectKohkaimae($prm);
    }
    // Basic認証をかける
    else if($pageAuthType=='basic'){
        if (isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER["PHP_AUTH_USER"] == $user && $_SERVER["PHP_AUTH_PW"] == $pass)) {
            //print '<div style="position:absolute;top:0px;left:0;z-index:1020;">未公開</div>';
        } else {
            header("WWW-Authenticate: Basic realm=\"basic\"");
            header("HTTP/1.0 401 Unauthorized - basic");
            echo "<script>location.href='/404.html'</script>";
            exit();
        }
    }
} else if ($nowDate > $endDate){
    redirectKohkaiato($prm_end);
}

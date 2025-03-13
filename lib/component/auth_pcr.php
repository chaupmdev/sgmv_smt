<?php
/**
 * @package    ページ認証処理
 *             読み込んだページにベーシック認証をかけるか非公開ページへリダイレクトする
 * @author     TuanLK
 * @copyright  2023 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */


// ページ認証種別：変数のコメントアウトを外したほうが有効
// どちらもコメントアウトした場合は普通にサイトを表示します
$pageAuthType =  'basic'; // ベーシック認証
//$pageAuthType ='private'; // 非公開(開催前、開催終了ページにリダイレクト)

// イベント名
$eventName = '旅客手荷物受付サービス';

// 公開開始日時
$openDateStr = '2023-02-24 00:00:00';

// ベーシック認証 ID,パスワード
$user = 'sgmv2';
$pass = 'sagawa2';

////////////////////////////////////////////////////////////////////////////////////////////////////
// これ以降のソースは改修時以外触る必要はありません 
////////////////////////////////////////////////////////////////////////////////////////////////////
// 公開開始日時
$openDate = new dateTime($openDateStr);
$prm = array(
    'open' => $openDate,     'event' => $eventName, 'dir'  => 'pcr' // evp固定で問題ないです
);

// 現在日時
$nowDate = new dateTime();
// 現在日時テスト用
//$nowDate= new dateTime('2022-07-21 10:59:40');
//echo('<pre>');var_dump($nowDate);echo('</pre>');
//echo('<pre>');var_dump($openDate);echo('</pre>');

// リダイレクト処理
function redirectKohkaimae($prm){
    $title = urlencode("公開前です");
    $weekDay = ['(日)', '(月)', '(火)', '(水)', '(木)', '(金)', '(土)',];
    $day = $prm['open']->format('Y/m/d');
    $yobi = $weekDay[$prm['open']->format('w')];
    $time = $prm['open']->format('H:i');

    $message = urlencode("「" . $prm['event'] . "」のお申込は 「" . $day . " " . $yobi . " " . $time . "」 より申込開始となります。");
    $browserTitle = "公開準備中｜SGムービング株式会社＜SGホールディングスグループ＞";
    $browserDiscption = "公開までしばらくお待ちください。";
    header("Location: /" . $prm['dir'] . "/error?t={$title}&m={$message}&bt={$browserTitle}&bd={$browserDiscption}");
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
}

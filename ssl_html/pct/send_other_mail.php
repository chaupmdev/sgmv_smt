<?php
/**
 * 手荷物受付サービスの特別メール送信画面を表示する。
 * @package    ssl_html
 * @subpackage PCR
 * @author     Tuan
 * @copyright  2023 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
$user = 'sgmv2';
$pass = 'sagawa2';
if (isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER["PHP_AUTH_USER"] == $user && $_SERVER["PHP_AUTH_PW"] == $pass)) {
} else {
    header("WWW-Authenticate: Basic realm=\"basic\"");
    header("HTTP/1.0 401 Unauthorized - basic");
    echo "<script>location.href='/404.html'</script>";
    exit();
}
/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
///**#@-*/
//
// 処理を実行

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Sgmov_Lib::useView('pct/SendOtherMail');
    $view = new Sgmov_View_Pct_SendOtherMail();
    $forms = $view->execute();
    $kensu = $forms['kensu'];
}

?>
<!DOCTYPE html>
<html dir="ltr" lang="ja-jp" xml:lang="ja-jp">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0" />
    <meta http-equiv="content-style-type" content="text/css" />
    <meta http-equiv="content-script-type" content="text/javascript" />
    <meta name="Keywords" content="佐川急便,ＳＧムービング,sgmoving,無料見積り,お引越し,設置,法人,家具輸送,家電輸送,精密機器輸送,設置配送" />
    <meta name="Description" content="引越・設置のＳＧムービング株式会社のWEBサイトです。個人の方はもちろん、法人様への実績も豊富です。無料お見積りはＳＧムービング株式会社(フリーダイヤル:0570-056-006(受付時間:平日9時～17時))まで。" />
    <meta name="author" content="SG MOVING Co.,Ltd" />
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved." />
    <title>旅客手荷物受付サービスのメール送信│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css" rel="stylesheet" type="text/css" />
    <script src="/js/ga.js" type="text/javascript"></script>
</head>
<body>
<?php
    $gnavSettings = 'contact';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/header.php';
?>
    <div id="breadcrumb">
        <ul class="wrap">
            <li><a href="/">ホーム</a></li>
            <li><a href="/contact/">お問い合わせ</a></li>
            <li class="current">旅客手荷物受付サービスのメール送信</li>
        </ul>
    </div>
    <div id="main">
        <div class="wrap clearfix">
            <h1 class="page_title">旅客手荷物受付サービスのメール送信</h1>
            <form method="post" id = "myForm">
                    <p class="text_center">
                        <button type="button" onclick="confirmSend()" style="height: 50px">メールを送信する</button>
                    </p>
                    <?php
                        if (isset($kensu)) {
                            if ($kensu <= 0) {
                                echo "<script type='text/javascript'>alert('送信データがありません。');</script>";   
                            } else {
                                echo "<script type='text/javascript'>alert('メールを送信しました。($kensu 件)');</script>";
                            }
                        }
                    ?>
            </form>
        </div>
    </div>

<?php
    $footerSettings = 'under';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/footer.php';
?>    
    <script>
        function confirmSend() {
            if (confirm("メールを送信しますが、よろしいでしょうか？")) {
                // Nếu người dùng xác nhận OK, gửi dữ liệu bằng phương thức POST
                document.getElementById("myForm").submit();
            } 
        }
    </script>
</body>
</html>
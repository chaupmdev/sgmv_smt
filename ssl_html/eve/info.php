<?php
// Basic認証
require_once dirname(__FILE__) . '/../../lib/component/auth_eve_info.php';
?>

<!DOCTYPE html>
<html dir="ltr" lang="ja-jp" xml:lang="ja-jp">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0" />
    <meta http-equiv="content-style-type" content="text/css" />
    <meta http-equiv="content-script-type" content="text/javascript" />
    <meta name="Keywords" content="" />
    <meta name="Description" content="催事・イベント配送受付サービスのお申し込みのご案内です。" />
    <meta name="author" content="SG MOVING Co.,Ltd" />
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved." />
    <title>催事・イベント配送受付サービスのお申し込み｜お問い合わせ│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
<?php
    // キャッシュ対策
    $sysdate = new DateTime();
    $strSysdate = $sysdate->format('YmdHi');
?>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <link href="/eve/css/eve.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
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
            <li class="current">催事・イベント配送受付サービスのお申し込み</li>
        </ul>
    </div>
    <div id="main">
        <div class="wrap clearfix">
            <h1 class="page_title" style="margin-bottom:10px">催事・イベント配送受付サービスのお申し込み</h1>
            <div style="font-size: 13.5px;">
                <!--<p class="red">※サーバメンテナンスにより、下記時間帯は申込ができません。</p>-->
                <!--<p class="red">　12月13日（水）0:20～2:20</p>-->
                <br>
                スマートコンテナご利用希望のお客様は、「コミックマーケット105スマートコンテナ御見積依頼書」を使用してメールでお申し込みください。
                <table style="margin-top: 5px;">
                    <tr>
                        <td style="width:45%;line-height: 20px;">
                            <a href="/eve/pdf/manual/SGMVスマートコンテナ輸送のご案内【2024冬コミケ105版】.pdf" target="_blank" style="margin-top:15px;color: #1774bc; text-decoration: underline;">
                                SGMVスマートコンテナ輸送のご案内 はこちら
                            </a><br/>
                            <a href="/eve/excel/estimate/コミックマーケット105スマートコンテナ御見積依頼書.xlsx" target="_blank" style="color: #1774bc; text-decoration: underline;">
                                コミックマーケット105スマートコンテナ御見積依頼書 はこちら
                            </a>
                        </td>
                        <td style="padding: 0px;;margin: 0px;">
                            <div class="red" style="float:left;">
                                ※
                            </div>
                            <div class="red" style="margin-left:20px;">
                                集荷先の地域によって、手配可能な日程が異なります。
                                ご依頼のタイミング次第では、ご希望に沿えない場合がございますので、
                                お早目のお問い合わせをお願い申し上げます。
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>


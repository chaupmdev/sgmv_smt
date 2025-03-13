<?php
$user_id  = 'sgmv_cruise';
$password = '{Ebi1PusQ$p<7kRX(R>Omq#Uihv2QJ)G';
$server = $_SERVER;
if (!isset($server['PHP_AUTH_USER'])
    || !isset($server['PHP_AUTH_PW'])
    || $server['PHP_AUTH_USER'] !== $user_id
    || $server['PHP_AUTH_PW']   !== $password
) {
    header('WWW-Authenticate: Basic realm="Private Page"');
    header('HTTP/1.0 401 Unauthorized');
    exit('fail');
}

header('Content-type: text/html; charset=UTF-8');
header('X-UA-Compatible: IE=edge');
?><!DOCTYPE html>
<html dir="ltr" lang="ja-jp" xml:lang="ja-jp">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="robots" content="noindex,nofollow,noodp" />
    <meta name="googlebot" content="noindex,nofollow,nosnippet,noodp,noodp,noarchive,noimageindex" />
    <meta name="google" content="nositelinkssearchbox" />
    <title>旅客手荷物受付サービスのお申し込みデータ抽出</title>
    <link href="/common/css/acr.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <form action="./download_csv.php" method="post">
        <h1>旅客手荷物受付サービスのお申し込みデータ抽出</h1>
        <fieldset class="fieldset">
            <legend>ダウンロードするデータの検索条件</legend>
            <fieldset>
                <legend>検索対象の登録日（年）</legend>
                <input min="2000" max="2099" name="year" step="1" style="" type="number" value="<?php
                    echo date_create()->format('Y');
                ?>" />
                年
            </fieldset>
        </fieldset>
        <button>Download CSV</button>
    </form>
</body>
</html>
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
    <title>クルーズリピーターのデータ抽出</title>
    <link href="/common/css/acr.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <form action="./download_repeater.php" method="post">
        <h1>クルーズリピーターのデータ抽出</h1>
        <button>Download CSV</button>
    </form>
</body>
</html>
<?php
require_once(dirname(__FILE__) . '/../../lib/component/auth_event.php');

$param = filter_input(INPUT_GET, 'param');

//$now = new DateTime();
//$moushikomiKaishiDt = new DateTime("2020/11/28 10:00:00");
//if ($now < $moushikomiKaishiDt) {
//    $title = urlencode("公開開始前です");
//    $message = urlencode("みどり会の申込受付は、2020年11月28日 10:00 から開始します。");
//    header("Location: /mdr/error?t={$title}&m={$message}");
//    exit;
//}


// 現在
 $now = new DateTime();
// // 公開日
 $releaseDate = new DateTime("2020/11/28 09:59:59");

// // ベーシック認証
 if($now < $releaseDate){
 	switch (true) {
 		case !isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']):
 		case $_SERVER['PHP_AUTH_USER'] !== 'sgmv':
 		case $_SERVER['PHP_AUTH_PW']   !== 'sagawa':
 		    header('WWW-Authenticate: Basic realm="Enter username and password."');
 		    header('Content-Type: text/plain; charset=utf-8');
 		    die('このページを見るにはログインが必要です');
 	}

 	header('Content-Type: text/html; charset=utf-8');
 }

if(empty($param)) {
    include_once dirname(__FILE__) . '/input1.php';
} else {
    include_once dirname(__FILE__) . '/input2.php';
}
<?php

session_start();
session_destroy();

function mobile_redirect() {

    include_once('./lib/config.php');

    // 切り替え用URLです。falseにすれば対象を除外できます。
    $mobile = QUES_URL . 'i/';  // モバイル端末
    $sp = QUES_URL . 's/'; // スマートフォン
    $ptp = ""; //スマホ0 ガラケー1

    $ua = $_SERVER['HTTP_USER_AGENT'];
    // ドコモ
    if (preg_match('/^DoCoMo/', $ua)) {
        $mobileredirect = $mobile;
        // au
    } elseif (preg_match('/^KDDI-|^UP\.Browser/', $ua)) {
        $mobileredirect = $mobile;
        // SoftBank
    } elseif (preg_match('#^J-(PHONE|EMULATOR)/|^(Vodafone/|MOT(EMULATOR)?-[CV]|SoftBank/|[VS]emulator/)#', $ua)) {
        $mobileredirect = $mobile;
        // Willcom
    } elseif (preg_match('/(DDIPOCKET|WILLCOM);/', $ua)) {
        $mobileredirect = $mobile;
        // e-mobile
    } elseif (preg_match('#^(emobile|Huawei|IAC)/#', $ua)) {
        $mobileredirect = $mobile;
        // モバイル端末
    } elseif (preg_match('#(^Nokia\w+|^BlackBerry[0-9a-z]+/|^SAMSUNG\b|Opera Mini|Opera Mobi|PalmOS\b|Windows CE\b)#', $ua)) {
        $mobileredirect = $mobile;
        // スマートフォン
    } elseif (preg_match('#\b(iP(hone|od);|Android )|dream|blackberry9500|blackberry9530|blackberry9520|blackberry9550|blackberry9800|CUPCAKE|webOS|incognito|webmate#', $ua)) {
        $mobileredirect = $sp;
        // PC	
    } else {
        $mobileredirect = $mobile;
    }


    //設置 0       
    //引っ越し 1
    $param = $_GET["param"];
    $kbn = $_GET["kbn"];

    if ($kbn == 0) {
        $mobileredirect .= SETTI;
    } elseif ($kbn == 1) {
        $mobileredirect .= HIKKOSHI;
    }

    $mobileredirect .= '?param=' . $param . '&kbn=' . $kbn;


    return $mobileredirect;
}


$url = mobile_redirect();

if (false !== $url) {
    header('Location: ' . $url);
    exit;
} else {
    exit;
}
?>
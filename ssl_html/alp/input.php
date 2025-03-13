<?php
// ***************************************************************************
// 公開開始前
// ***************************************************************************

require_once('../../lib/component/auth.php');
/*
$title = urlencode("公開前です");
$message = urlencode("「コミックマーケット98　サークル向け」のお申込は 「2020/04/19(日)0：00」 から受付を開始致します。");
header("Location: /evp/error?t={$title}&m={$message}");
exit;
*/
// ***************************************************************************


$param = filter_input(INPUT_GET, 'param');

// REWRITE 

if(empty($param)) {

} else {
    include_once dirname(__FILE__) . '/input1.php';
}
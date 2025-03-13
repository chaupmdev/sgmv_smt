<?php
// ***************************************************************************
// 公開開始前
// ***************************************************************************
/*
//$title = urlencode("公開前です");
//$message = urlencode("「コミックマーケット98　サークル向け」のお申込は 「2020/04/19(日)0：00」 から受付を開始致します。");
$title = urlencode("未公開です");
$message = urlencode("「コミックマーケット99　サークル向け」のお申込画面は未公開です。");
header("Location: /evp/error?t={$title}&m={$message}");
exit;
*/
// ***************************************************************************


$param = filter_input(INPUT_GET, 'param');

if(empty($param)) {
    include_once dirname(__FILE__) . '/input1.php';
} else {
    include_once dirname(__FILE__) . '/input2.php';
}
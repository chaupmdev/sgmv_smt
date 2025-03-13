<?php

//-----------------メンテナンス期間設定 ------------------//
    require_once dirname(__FILE__) . '/../lib/component/maintain_event.php';
    require_once dirname(__FILE__) . '/../lib/component/maintain_pcr.php';
    require_once dirname(__FILE__) . '/../lib/component/maintain_pct.php';
    require_once dirname(__FILE__) . '/../lib/component/maintain_pcr_call.php';
    require_once dirname(__FILE__) . '/../lib/component/maintain_pct_call.php';
//----------------イベントのバナー表示設定期間------------//
// 現在日時
$nowDate = new DateTime('now');
$main_st = $nowDate->format('Y-m-d H:i');
$main_ed = $nowDate->format('Y-m-d H:i');
//イベントのメンテナンス中かをチェック
if ($main_stDate_ev <= $nowDate && $nowDate <= $main_edDate_ev) {
    $main_st = $main_stDate_ev->format('Y-m-d H:i');
    $main_ed = $main_edDate_ev->format('Y-m-d H:i');
}
//通常のクルーズのメンテナンス中かをチェック（沖縄那覇版）
if ($main_stDate_pcr <= $nowDate && $nowDate <= $main_edDate_pcr) {
    $main_st = $main_stDate_pcr->format('Y-m-d H:i');
    $main_ed = $main_edDate_pcr->format('Y-m-d H:i');
}
//通常版
if ($main_stDate_pct <= $nowDate && $nowDate <= $main_edDate_pct) {
    $main_st = $main_stDate_pct->format('Y-m-d H:i');
    $main_ed = $main_edDate_pct->format('Y-m-d H:i');
}

//クルーズのコールセンタのメンテナンス中かをチェック（沖縄那覇版）
if ($main_stDate_pcr_call <= $nowDate && $nowDate <= $main_edDate_pcr_call) {
    $main_st = $main_stDate_pcr_call->format('Y-m-d H:i');
    $main_ed = $main_edDate_pcr_call->format('Y-m-d H:i');
}
//通常版
if ($main_stDate_pct_call <= $nowDate && $nowDate <= $main_edDate_pct_call) {
    $main_st = $main_stDate_pct_call->format('Y-m-d H:i');
    $main_ed = $main_edDate_pct_call->format('Y-m-d H:i');
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0" />
	<meta name="Keywords" content="" />
	<meta name="Description" content="引越・設置のＳＧムービング株式会社のWEBサイトです。個人の方はもちろん、法人様への実績も豊富です。無料お見積りはＳＧムービング株式会社(フリーダイヤル:0570-056-006(受付時間:平日9時～17時))まで。" />
	<title>メンテナンス中│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
	<link href="/css/common.css" rel="stylesheet" type="text/css" />
	<link href="/css/error.css" rel="stylesheet" type="text/css" />
    <script src="/js/ga.js" type="text/javascript"></script>
	<!--[if lt IE 9]>
	<script charset="UTF-8" type="text/javascript" src="/js/jquery-1.12.0.min.js"></script>
	<script charset="UTF-8" type="text/javascript" src="/js/lodash-compat.min.js"></script>
	<![endif]-->
	<!--[if gte IE 9]><!-->
	<script charset="UTF-8" type="text/javascript" src="/js/jquery-2.2.0.min.js"></script>
	<script charset="UTF-8" type="text/javascript" src="/js/lodash.min.js"></script>
	<!--<![endif]-->
	<script charset="UTF-8" type="text/javascript" src="/js/common.js"></script>
</head>

<body>

<?php
	$gnavSettings = "";
	include_once($_SERVER['DOCUMENT_ROOT']."/parts/header.php");
?>

<div id="breadcrumb">
	<ul class="wrap">
		<li><a href="/">ホーム</a></li>
		<li class="current">メンテナンス中</li>
	</ul>
</div>

<div id="main">

	<div class="wrap clearfix">

			<h1 class="page_title" style="margin-bottom:30px;">ただいまメンテナンス中です</h1>

			<div class="section" style="margin-top:20px;">
				
					<div style="font-weight:bold;font-size: 1.2em;color: red;">
					     【メンテナンス日時】<?php echo $main_st;?> ～ <?php echo $main_ed;?>
					</div>
					<br/>
					<b>現在、サーバーメンテナンス中です。ご利用の皆様にはご不便をおかけいたしまして、<br/><br/>
					誠に申し訳ございませんが、ご了承くださいますようお願い申し上げます。<br/></b>
				
				<!--
				<div class="ful_column_2">

					<div class="column_item">
						<h3 class="column_item_title"><a href="/">トップページから探す</a></h3>
					</div>

					<div class="column_item mR0_block">
						<h3 class="column_item_title"><a href="/sitemap.php">サイトマップから探す</a></h3>
					</div>
				</div>
				-->
			</div>

	</div>


</div><!--main-->

<?php
	$footerSettings = "under";
	include_once($_SERVER['DOCUMENT_ROOT']."/parts/footer.php");
?>


</body>
</html>
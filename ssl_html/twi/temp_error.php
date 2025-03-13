<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0" />
	<meta name="Keywords" content="佐川急便,ＳＧムービング,sgmoving,無料見積り,お引越し,設置,法人,家具輸送,家電輸送,精密機器輸送,設置配送" />
	<meta name="Description" content="サイトマップのご案内です。" />
	<title>エラー│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
	<link rel="shortcut icon" href="/misc/favicon.ico" type="image/vnd.microsoft.icon" />
	<link href="/css/common.css" rel="stylesheet" type="text/css" />
	<link href="/css/sitemap.css" rel="stylesheet" type="text/css" />
	<!--[if lt IE 9]>
	<script charset="UTF-8" type="text/javascript" src="/js/jquery-1.12.0.min.js"></script>
	<script charset="UTF-8" type="text/javascript" src="/js/lodash-compat.min.js"></script>
	<![endif]-->
	<!--[if gte IE 9]><!-->
	<script charset="UTF-8" type="text/javascript" src="/js/jquery-2.2.0.min.js"></script>
	<script charset="UTF-8" type="text/javascript" src="/js/lodash.min.js"></script>
	<!--<![endif]-->
	<script charset="UTF-8" type="text/javascript" src="/js/common.js"></script>
	<script charset="UTF-8" type="text/javascript" src="/personal/js/anchor.js"></script>
    <script src="/js/ga.js" type="text/javascript"></script>
</head>

<body>

<?php
	$gnavSettings = "";
	include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/header.php';
?>

<div id="breadcrumb">
	<ul class="wrap">
		<li><a href="/">ホーム</a></li>
		<li class="current">エラー</li>
	</ul>
</div>

<div id="main">
		<form action="" method="post">
			<input name="inquiry_type_cd_sel" type="hidden" value="" />
			<input name="plan_cd_sel" type="hidden" value="" />
			<input name="personal" type="hidden" value="" />
			<input name="referer"  type="hidden" value="menu_personal" />
		</form>
	<div class="wrap clearfix">

		
            <b style="font-size: 20px;">
            対象データがありませんでしたので、<br/>通常登録お願いします。<br/><br/>
            </b>
            <?php if($_REQUEST['param'] == '1') : // デザインフェスタ?>
                <a href="/twi/input/?ev=001" style="color:blue;">デザインフェスタ登録画面はこちら</a>
            <?php elseif($_REQUEST['param'] == '2') : // コミケ ?>
                <a href="/twi/input/?ev=002" style="color:blue;">コミックマーケット登録画面はこちら</a>
            <?php endif; ?>
            

	</div>

</div><!--main-->
<?php
	$footerSettings = 'under';
	include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/footer.php';
?>

</body>
</html>
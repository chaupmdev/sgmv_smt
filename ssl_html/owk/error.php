<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
$broswerTitle = "エラー│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞";
$broswerDis = "サイトマップのご案内です。";

$brTitle = htmlspecialchars(urldecode(filter_input(INPUT_GET, 'bt')));
$brDis = htmlspecialchars(urldecode(filter_input(INPUT_GET, 'bd')));

if (!empty($brTitle)) {
    $broswerTitle = $brTitle;
}

if (!empty($brDis)) {
    $broswerDis = $brDis;
}

?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0" />
		<meta name="Keywords" content="佐川急便,ＳＧムービング,sgmoving,無料見積り,お引越し,設置,法人,家具輸送,家電輸送,精密機器輸送,設置配送" />
		<meta name="Description" content="<?php echo $broswerDis; ?>" />
		<title><?php echo $broswerTitle; ?></title>
		<link rel="shortcut icon" href="/misc/favicon.ico" type="image/vnd.microsoft.icon" />
		<link href="/css/common.css" rel="stylesheet" type="text/css" />
		<link href="/css/sitemap.css" rel="stylesheet" type="text/css" />
		<script charset="UTF-8" type="text/javascript" src="/js/jquery-2.2.0.min.js"></script>
		<script charset="UTF-8" type="text/javascript" src="/js/lodash.min.js"></script>
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
			<div class="wrap clearfix">
				<h2 class="page_title" style="font-size: 20px;">
				<?php echo  htmlspecialchars(urldecode(filter_input(INPUT_GET, 't'))); ?>
				</h2>
				<p><?php echo  htmlspecialchars(urldecode(filter_input(INPUT_GET, 'm'))); ?></p>
			</div>
		</div><!--main-->
		<?php
			$footerSettings = 'under';
			include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/footer.php';
		?>
	</body>
</html>
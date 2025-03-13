<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="オフィスや施設などの移転に関する事業案内ページです。">
<meta property="og:locale" content="ja_JP">
<meta property="og:type" content="website">
<meta property="og:url" content="https://www.sagawa-mov.co.jp/service/moving/transfer/">
<meta property="og:site_name" content="<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl-ogp.php'); ?>">
<meta property="og:image" content="/img/ogp/og_image_sgmv.jpg">
<meta property="twitter:card" content="summary_large_image">
<link rel="shortcut icon" href="/img/common/favicon.ico">
<link rel="apple-touch-icon" sizes="192x192" href="/img/ogp/touch-icon.png">
<title>（仮）輸送＋a｜移転・引越｜<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl.php'); ?></title>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_style.php'); ?>
<link rel="stylesheet" type="text/css" href="/css/service_solution_transportation.css">
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_script.php'); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/analytics_head.php'); ?>
</head>
<body>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/analytics_body.php'); ?>
<div id="container">
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/header.php'); ?>
    <main id="main" role="main">
		<div class="pageTitle">
			<div class="comBox">
				<p class="topLead"><span>輸送・配送</span>（仮）輸送＋a</p>
				<div class="comBtn03"><a href="/contact/" target="_blank"><span><small>お問合わせ・お申し込み</small></span></a></div>
				<ul id="pagePath">
					<li><a href="/">ホーム</a></li>
					<li><a href="/service/">事業案内</a></li>
					<li>（仮）輸送＋a</li>
				</ul>
			</div>
		</div>
		<div class="mainBox">
			<article id="conts">
				<div class="titleBox fadeInUp">
					<h1 class="headLine02 head01">他ページとの体裁統一のため、ここにキャッチコピーや<br class="pc">わかりやすいテキストを一文添えたいです。</h1>
				</div>
				<div class="comImgBox fadeInUp">
					<div class="photoBox"><img src="/img/service/solution/transportation/photo.jpg" alt="他ページとの体裁統一のため、ここにキャッチコピーやわかりやすいテキストを一文添えたいです。"></div>
					<div class="textBox">
						<dl>
							<dt>Point<span>01</span></dt>
							<dd>店舗様・対象店舗　回収時に簡易梱包を行った上で、輸送を行います。</dd>
							<dt>Point<span>02</span></dt>
							<dd>門前倉庫にて集約/一時保管を行い、本梱包を行います。</dd>
							<dt>Point<span>03</span></dt>
							<dd>一定の数量がたまり次第、10t車両にて保管倉庫へ搬入いたします。<small>(数量に関しては別途調整・仮搬入日を弊社にて設定)</small></dd>
						</dl>
					</div>
				</div>
				<section class="flow fadeInUp">
					<h2 class="headLine05 fadeInUp animate">輸送+α（仮）の流れ</h2>
					<div class="imgBox"><img src="/img/service/solution/transportation/img.png" alt="同一エリア内 店舗様 仮梱包 回収・一時輸送 什器保管倉庫  東京都江東区 SGムービング倉庫 一時保管・本梱包 10t車両 2マン運行・持ち込み"></div>
				</section>
			</article>
			<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/side-2column-service-moving.php'); ?>
		</div>
		<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/footer-contact.php'); ?>
    </main>
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/footer.php'); ?>
</div>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_script_foot.php'); ?>
<script src="/js/jquery.matchHeight.js"></script>
<script src="/js/sidebar.js"></script>
<script>
	$(function(){
		$('#gHeader .hBox #gNavi .hLinkList > li').eq(1).addClass('current');

		$('#conts .benefits .textList li table').matchHeight();
		$('#conts .service .photoList li .txtBox .ttl').matchHeight({byRow: false});
	})
</script>
</body>
</html>
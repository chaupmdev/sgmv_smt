<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="事務所や倉庫の移転の事例紹介ページです。">
<meta property="og:locale" content="ja_JP">
<meta property="og:type" content="website">
<meta property="og:url" content="https://www.sagawa-mov.co.jp/case/">
<meta property="og:site_name" content="<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl-ogp.php'); ?>">
<meta property="og:image" content="/img/ogp/og_image_sgmv.jpg">
<meta property="twitter:card" content="summary_large_image">
<meta property="og:title" content="<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl-name-ogp.php'); ?>">
<meta property="og:description" content="事務所や倉庫の移転の事例紹介ページです。">
<link rel="shortcut icon" href="/img/common/favicon.ico">
<link rel="apple-touch-icon" sizes="192x192" href="/img/ogp/touch-icon.png">
<title>事務所や倉庫の移転｜事例紹介｜<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl.php'); ?></title>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_style.php'); ?>
<link rel="stylesheet" type="text/css" href="/css/solution.css">
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_script.php'); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/analytics_head.php'); ?>
</head>
<body>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/analytics_body.php'); ?>
<div id="container">
	<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/header.php'); ?>
    <main id="main" role="main">
		<div class="solutionCase">
			<div class="pageTitle">
				<div class="comBox">
					<p class="topLead"><span>事例紹介<small>移転・引越</small></span>事務所や倉庫の移転</p>
					<ul id="pagePath">
						<li><a href="/">ホーム</a></li>
						<li><a href="/case/">事例紹介</a></li>
						<li>事務所や倉庫の移転</li>
					</ul>
				</div>
			</div>
			<div class="soContent">
				<article id="soConts">
					<h1 class="headLine13 fadeInUp">事務所や倉庫、工場など企業活動に関する<br class="pc">あらゆる施設の移転実績があります。<br>大小規模に関わらずご相談下さい。</h1>
					<h2 class="title fadeInUp">過去事例<img src="/img/solution/case03_photo.jpg" alt="過去事例"></h2>
					<table class="comTable fadeInUp">
						<tr>
							<th>金融・不動産</th>
							<td>住友生命／住友不動産／損保ジャパン／ソニー銀行／第一生命／第一フロンティア生命／東急不動産／東京海上日動／日本生命／野村不動産／三井住友海上／三井住友銀行／三井不動産／三菱UFJ銀行／三菱UFJリース／三菱地所／明治安田生命／森トラスト／森ビル／横浜銀行／りそな銀行</td>
						</tr>
						<tr>
							<th>メーカー</th>
							<td>アツギ／いすゞ自動車／KADOKAWA／コイト電工／ソフトバンク／タキヒヨー／トヨタ自動車／ハピネット・ロジスティクスサービス／富士通／PVHジャパン／ZOZO</td>
						</tr>
						<tr>
							<th>その他メーカー等</th>
							<td>アッカインターナショナル／井上金庫販売／ヴィス／鹿島建設／兼松エンジニアリング／鎌倉光機／郡リース／コナミ／小森コーポレーション／秀和ビルメンテナンス／シモンズ／住商グローバル・ロジスティクス／セブン＆アイホールディングス／テルウェル東日本／東邦ガス／パルコ／富士レビオ／北陸電力／モンテローザ／楽天／リクルート／菱重コールドチェーン／ローソン／JTB</td>
						</tr>
					</table>
                                                    <p align="right">※敬称略・50音順</p>
				</article>
				<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/side-2column-case.php'); ?>
			</div>
		</div>
		<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/footer-contact.php'); ?>
    </main>    
	<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/footer.php'); ?>
</div>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_script_foot.php'); ?>
<script src="/js/jquery.matchHeight.js"></script>
<script src="/js/muuri.min.js"></script>
<script src="/js/sidebar.js"></script>
<script>
	$(function(){
		$('#gHeader .hBox #gNavi .hLinkList > li').eq(2).addClass('current');
		$('#main .bgBox .linkList li a .txtBox .link').matchHeight();
		$('#main .bgBox .linkList li a .txtBox p').matchHeight();
	});
	$(window).on('load', function() {
		//filter
		var grid1 = new Muuri('.grid1', {
			showDuration: 600,
			showEasing: 'cubic-bezier(0.215, 0.61, 0.355, 1)',
			hideDuration: 600,
			hideEasing: 'cubic-bezier(0.215, 0.61, 0.355, 1)',
			visibleStyles: {
				opacity: '1',
				transform: 'scale(1)'
			},
			hiddenStyles: {
				opacity: '0',
				transform: 'scale(0.5)'
			}
		});
		// var grid2 = new Muuri('.grid2', {
		// 	showDuration: 600,
		// 	showEasing: 'cubic-bezier(0.215, 0.61, 0.355, 1)',
		// 	hideDuration: 600,
		// 	hideEasing: 'cubic-bezier(0.215, 0.61, 0.355, 1)',
		// 	visibleStyles: {
		// 		opacity: '1',
		// 		transform: 'scale(1)'
		// 	},
		// 	hiddenStyles: {
		// 		opacity: '0',
		// 		transform: 'scale(0.5)'
		// 	}
		// });


		$('#main .categoryDl dd ul li a').on('click', function() {
			$("#main .categoryDl dd ul .on").removeClass("on");
			var className = $(this).attr("class");
			className = className.split(' ');
			$("#main .categoryDl dd ul ." + className[0]).parent().addClass("on");
			if (className[0] == "all") {
				grid1.filter(function(item){
					return 1;
				});
				grid2.filter(function(item){
					return 1;
				});
			} else {
				grid1.filter("." + className[0]);
				grid2.filter("." + className[0]);
			}
			return false;
		});

	});
</script>
</body>
</html>
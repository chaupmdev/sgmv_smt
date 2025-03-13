<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="官公庁などの移転の事例紹介ページです。">
<meta property="og:locale" content="ja_JP">
<meta property="og:type" content="website">
<meta property="og:url" content="https://www.sagawa-mov.co.jp/case/">
<meta property="og:site_name" content="<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl-ogp.php'); ?>">
<meta property="og:image" content="/img/ogp/og_image_sgmv.jpg">
<meta property="twitter:card" content="summary_large_image">
<meta property="og:title" content="<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl-name-ogp.php'); ?>">
<meta property="og:description" content="官公庁などの移転の事例紹介ページです。">
<link rel="shortcut icon" href="/img/common/favicon.ico">
<link rel="apple-touch-icon" sizes="192x192" href="/img/ogp/touch-icon.png">
<title>官公庁などの移転｜事例紹介｜<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl.php'); ?></title>
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
					<p class="topLead"><span>事例紹介<small>移転・引越</small></span>官公庁などの移転</p>
					<ul id="pagePath">
						<li><a href="/">ホーム</a></li>
						<li><a href="/case/">事例紹介</a></li>
						<li>官公庁などの移転</li>
					</ul>
				</div>
			</div>		
			<div class="soContent">
				<article id="soConts">
					<h1 class="headLine13 fadeInUp">全国で官公庁の移転の実績があります。<br>企画・計画段階から仕様書作成、作業まで一本化し、安心・安全なサービスを提供します。</h1>
					<h2 class="title fadeInUp">過去事例<img src="/img/solution/case01_photo.jpg" alt="過去事例"></h2>
					<table class="comTable fadeInUp">
						<tr>
							<th>都府県</th>
							<td>青森県／愛媛県／大阪府／香川県／神奈川県／岐阜県／静岡県／東京都／長野県／奈良県／兵庫県／福岡県／宮城県／山形県／和歌山県</td>
						</tr>
						<tr>
							<th>市区町村</th>
							<td>会津若松市／奄美市／石垣市／鹿児島市／川口市／川崎市／京都市／札幌市／豊見城市／宮古島市／江戸川区／北区／江東区／杉並区／世田谷区／台東区／中央区／豊島区／中野区／練馬区／港区／大津町／庄内町／多良木町／中能登町／益城町／壬生町／湯浅町／与那原町／利府町／明日香村</td>
						</tr>
						<tr>
							<th>中央省庁・その他</th>
							<td>内閣府／厚生労働省／国土交通省／農林水産省／防衛省／気象庁／宮内庁／警視庁／社会保険庁／高齢・障害・求職者雇用支援機構／日本原子力研究機構／日本中央競馬会／日本年金機構</td>
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
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="教育施設や医療機関の移転の事例紹介ページです。">
<meta property="og:locale" content="ja_JP">
<meta property="og:type" content="website">
<meta property="og:url" content="https://www.sagawa-mov.co.jp/case/">
<meta property="og:site_name" content="<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl-ogp.php'); ?>">
<meta property="og:image" content="/img/ogp/og_image_sgmv.jpg">
<meta property="twitter:card" content="summary_large_image">
<meta property="og:title" content="<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl-name-ogp.php'); ?>">
<meta property="og:description" content="教育施設や医療機関の移転の事例紹介ページです。">
<link rel="shortcut icon" href="/img/common/favicon.ico">
<link rel="apple-touch-icon" sizes="192x192" href="/img/ogp/touch-icon.png">
<title>教育施設や医療機関の移転｜事例紹介｜<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl.php'); ?></title>
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
					<p class="topLead"><span>事例紹介<small>移転・引越</small></span>教育施設や医療機関の移転</p>
					<ul id="pagePath">
						<li><a href="/">ホーム</a></li>
						<li><a href="/case/">事例紹介</a></li>
						<li>教育施設や医療機関の移転</li>
					</ul>
				</div>
			</div>		
			<div class="soContent">
				<article id="soConts">
					<h1 class="headLine13 fadeInUp">教育施設や医療機関、研究施設など<br class="pc">特殊性の高い移転についても実績があります。<br>安心・安全な移転作業を提供します。</h1>
					<h2 class="title fadeInUp">過去事例<img src="/img/solution/case02_photo.jpg" alt="過去事例"></h2>
					<table class="comTable fadeInUp">
						<tr>
							<th>教育施設</th>
							<td>秋田大学／岩手大学／大分大学／大阪大学／岡山大学／鹿児島大学／金沢大学／九州工業大学／京都大学／熊本大学／神戸大学／佐賀大学／滋賀医科大学／滋賀大学／筑波大学／東京医科歯科大学／東京工業大学／東京大学／東京農工大学／東北学院大学／東北大学／長崎大学／名古屋大学／奈良県立医科大学／鳴門教育大学／新潟大学／広島大学／福岡工業大学／宮城教育大学／宮崎大学／山形大学／山口大学／琉球大学</td>
						</tr>
						<tr>
							<th>医療施設</th>
							<td>海老名総合病院／岡波総合病院／京都桂病院／島田病院／聖隷横浜病院／総合花巻病院／三豊市立永康病院／八雲病院／有隣病院／湯河原病院</td>
						</tr>
						<!--<tr>
							<th>研究施設</th>
							<td>サンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキスト</td>
						</tr>-->
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
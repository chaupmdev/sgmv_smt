<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="SDGsへの取り組みに関するページです。">
<meta property="og:locale" content="ja_JP">
<meta property="og:type" content="website">
<meta property="og:url" content="https://www.sagawa-mov.co.jp/sustainability/sdgs/">
<meta property="og:site_name" content="<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl-ogp.php'); ?>">
<meta property="og:image" content="/img/ogp/og_image_sdgs.jpg">
<meta property="twitter:card" content="summary_large_image">
<meta property="og:title" content="<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl-name-ogp.php'); ?>">
<meta property="og:description" content="SDGsへの取り組みに関するページです。">
<link rel="shortcut icon" href="/img/common/favicon.ico">
<link rel="apple-touch-icon" sizes="192x192" href="/img/ogp/touch-icon.png">
<title>SDGsへの取り組み｜サステナビリティ｜<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl.php'); ?></title>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_style.php'); ?>
<link rel="stylesheet" type="text/css" href="/css/sustainability_sdgs.css">
<link rel="stylesheet" type="text/css" href="/js/scrollhint/scroll-hint.css">
<script src="/js/scrollhint/scroll-hint.min.js"></script>
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
				<p class="topLead"><span>サステナビリティ</span>SDGsへの取り組み</p>
				<ul id="pagePath">
					<li><a href="/"><img class="home" src="/img/common/icon54.jpg" alt=""></a></li>
					<li><a href="/sustainability/">サステナビリティ</a></li>
					<li>SDGsへの取り組み</li>
				</ul>
			</div>
		</div>
		<div class="comBox">
			<section class="sec01">
				<h1 class="fadeInUp">SGムービングは、社会課題を解決し、<br>
				持続可能な社会の実現に向けた活動を積極的に展開しています。</h1>
				<div class="photo fadeInUp"><img src="/img/sustainability/sdgs/photo01.jpg" alt="SUSTAINABLE GOALS DEVELOPMENT"></div>
			</section>
			<section class="sec02">
				<h2 class="headLine06 fadeInUp">SGムービングの主な活動</h2>
				<p class="fadeInUp">SGムービングは、社会課題を解決し、持続可能な社会の実現に向けた活動を積極的に展開しています。</p>
				<div class="tableBox fadeInUp">
					<table>
						<thead>
							<tr>
								<th>重要課題</th>
								<th>主な活動・サービス</th>
								<th>関連するゴール</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>安全・安心なサービスの提供</th>
								<td>
									<ul class="txtList">
										<li>安全管理の徹底</li>
									</ul>
								</td>
								<td>
									<ul class="markList">
										<li><img src="/img/sustainability/sdgs/mark01.jpg" alt="3　すべての人に　健康と福祉を"></li>
									</ul>
								</td>
							</tr>
							<tr>
								<th rowspan="2">環境に配慮した事業推進</th>
								<td class="bdStyle01">
									<ul class="txtList">
										<li>SG-ARK（エスジーアーク）</li>
										<li>SGエコープ</li>
									</ul>
								</td>
								<td class="bdStyle01">
									<ul class="markList">
										<li><img src="/img/sustainability/sdgs/mark02.jpg" alt="12　つくる責任　つかう責任"></li>
									</ul>
								</td>
							</tr>
							<tr>
								<td>
									<ul class="txtList">
										<li>エコアクション21認証（段階的認証）取得</li>
									</ul>
								</td>
								<td>
									<ul class="markList">
										<li><img src="/img/sustainability/sdgs/mark03.jpg" alt="13　気候変動に　具体的な対策を"></li>
									</ul>
								</td>
							</tr>
							<tr>
								<th>個性・多様性を尊重した組織づくり</th>
								<td>
									<ul class="txtList">
										<li>プラチナくるみん認証取得</li>
										<li>社員エンゲージメントの指標値の向上</li>
										<li>特別支援学校からの現場実習生の受け入れ</li>
									</ul>
								</td>
								<td>
									<ul class="markList">
										<li><img src="/img/sustainability/sdgs/mark04.jpg" alt="5　ジェンダー平などを　実現しよう"></li>
										<li><img src="/img/sustainability/sdgs/mark05.jpg" alt="8　働きがいも　経済成長も"></li>
									</ul>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</section>
		</div>
		<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/footer-contact.php'); ?>
    </main>
	<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/footer.php'); ?>
</div>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_script_foot.php'); ?>
<script>
	$(function(){
		$('#sideBar .sNavi li').eq(1).addClass('on');

		$('#gHeader .hBox #gNavi .hLinkList > li').eq(2).addClass('current');
	})
	new ScrollHint('.tableBox', {
    i18n: {
      scrollable: 'スクロールできます',
			suggestiveShadow: true
    }
  });
</script>
</body>
</html>
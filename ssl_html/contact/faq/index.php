<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="よくあるご質問に関するページです。">
<meta property="og:locale" content="ja_JP">
<meta property="og:type" content="website">
<meta property="og:url" content="https://www.sagawa-mov.co.jp/">
<meta property="og:site_name" content="<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl-ogp.php'); ?>">
<meta property="og:image" content="/img/ogp/og_image_sgmv.jpg">
<meta property="twitter:card" content="summary_large_image">
<link rel="shortcut icon" href="/img/common/favicon.ico">
<link rel="apple-touch-icon" sizes="192x192" href="/img/ogp/touch-icon.png">
<title>よくあるご質問｜各種お問い合わせ｜<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl.php'); ?></title>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_style.php'); ?>
<link rel="stylesheet" type="text/css" href="/css/contact_faq.css">
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_script.php'); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/analytics_head.php'); ?>
</head>
<body>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/analytics_body.php'); ?>
<div id="container">
	<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/header.php'); ?>
    <main id="main" role="main">
		<div class="pageTitle style01">
			<div class="comBox">
				<h1 class="topLead">よくあるご質問<em>FAQ</em></h1>
				<ul id="pagePath">
					<li><a href="/"><img class="home" src="/img/common/icon54.jpg" alt=""></a></li>
					<li><a href="/contact/">各種お問い合わせ</a></li>
					<li>よくあるご質問</li>
				</ul>
			</div>
		</div>
		<div class="mainBox">
			<article id="conts">
				<div class="inner" id="a01">
					<h2 class="headLine04 fadeInUp">弊社サービスに関するご質問</h2>
					<dl class="comTextDl fadeInUp">
						<dt class="on">佐川急便と関係があるのですか？</dt>
						<dd style="display: block;">
							<p>当社は佐川急便を中核とするSGホールディングスグループの一員として大型家具家電の設置や移転を主軸に「輸送＋α」の付加価値を提供しています。</p>
						</dd>
						<dt>移転作業前にダンボール・ガムテープなどはもらえますか？</dt>
						<dd>
							<p>事前にご連絡いただければ、梱包資材などをご希望日にお届けいたします。</p>
						</dd>
						<dt>移転後に使ったダンボールの回収はしてもらえるのですか？</dt>
						<dd>
							<p>無償で回収しておりますが、エリアにより回収できない場合がございますので、ナビダイヤル<a href="tel:0570056006">0570-056-006</a>（受付時間：9時～18時）へお問い合わせください。</p>
						</dd>
					</dl>
				</div>
				<div class="inner" id="a02">
					<h2 class="headLine04 fadeInUp">オフィス移転に関するご質問</h2>
					<dl class="comTextDl fadeInUp">
						<dt class="on">移動先オフィスがビルの10Fにあるのですが、床や壁を傷付けずに搬入することは可能ですか？</dt>
						<dd style="display: block;">
							<p>移転作業時には、専用の資材を使用して養生を行い、床や壁を保護いたします。</p>
						</dd>
						<dt>パソコンケーブルやLANなどの配線作業もお任せできますか？</dt>
						<dd>
							<p>全国のパートナー企業と連携し、ご相談をいただければ、現地下見時に同行いたします。</p>
						</dd>
						<dt>移動先フロアーのオフィス家具や荷物の配置を一緒に考えてもらえますか？</dt>
						<dd>
							<p>CADオペレーターによるレイアウト策定や図面作成が可能です。</p>
						</dd>
					</dl>
				</div>
				<div class="inner" id="a03">
					<h2 class="headLine04 fadeInUp">お見積りに関するご質問</h2>
					<dl class="comTextDl fadeInUp">
						<dt class="on">移動先と移動荷物が変更になりました。再見積りも無料で行っていただけますか？</dt>
						<dd style="display: block;">
							<p>もちろん、無料にて承ります。</p>
						</dd>
						<dt>見積りフォームからの依頼でないと受け付けてもらえないのですか？</dt>
						<dd>
							<p>直接お電話でも受付可能です。<br>
								ナビダイヤル：<a href="tel:0570056006">0570-056-006</a>（受付時間：9時～18時）へお問い合わせください。</p>
						</dd>
					</dl>
				</div>
				
			</article>
			<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/side-2column-faq.php'); ?>
		</div>
		<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/footer-contact.php'); ?>
    </main>
	<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/footer.php'); ?>
</div>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_script_foot.php'); ?>
<script>
	$(function(){
        $('#main .sec01 .tabBox').hide();
        $('#main .sec01 .tabBox').eq(0).show();
	})
	$(function() {
	const sideBar = $('#sideBar .sNavi li');
	sideBar.eq(0).addClass('on');
	const mediaQuery = window.matchMedia('(min-width: 897px)')
  if (mediaQuery.matches) {
		const headHeight = $('#gHeaderItems').height();
		let tar2 = $("#a02").offset().top - headHeight;
		let tar3 = $("#a03").offset().top - headHeight;
		$(window).on("scroll", function() {
			let curPos = $(window).scrollTop();
			let chidNo = 0;
			if(curPos > tar2) chidNo = 1;
			if(curPos > tar3) chidNo = 2;
			sideBar.eq(chidNo).siblings().removeClass('on');
			if( !sideBar.eq(chidNo).hasClass('on') ) sideBar.eq(chidNo).addClass('on');
  	});
  }
});
</script>
</body>
</html>
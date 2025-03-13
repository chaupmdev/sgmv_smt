<?php $ua = strtolower($_SERVER["HTTP_USER_AGENT"]); $isMob = is_numeric(strpos($ua, "mobile")); 
$new = htmlspecialchars("", ENT_QUOTES);
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="台風18号による配送への影響について（2024年10月04日9時現在）">
<meta property="og:locale" content="ja_JP">
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl-ogp.php'); ?>">
<meta property="og:image" content="/img/ogp/og_image_logo.jpg">
<meta property="twitter:card" content="summary_large_image">
<link rel="shortcut icon" href="/img/common/favicon.ico">
<link rel="apple-touch-icon" sizes="192x192" href="/img/ogp/touch-icon.png">
<title>台風18号による配送への影響について（2024年10月04日9時現在）｜お知らせ｜<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl.php'); ?></title>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_style.php'); ?>
<link rel="stylesheet" type="text/css" href="/css/topics.css">
<style>
table, th, td {
  border:1px solid black;
  border-collapse: collapse;
}
th {
  color: white;
  background: #0070C0;
  font-weight: bold;
  text-align: center;
}
#tdNo {
    text-align: center;
}
#tdName {
    text-align: center;
    padding-left: 10px;
}
</style>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_script.php'); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/analytics_head.php'); ?>
</head>
<body data-newspage-type="single">
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/analytics_body.php'); ?>
<div id="container">
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/header.php'); ?>
<main id="main" role="main">
		<div class="pageTitle style01">
			<div class="comBox">
				<h1 class="topLead">お知らせ<em>News</em></h1>
				<ul id="pagePath">
				<li><a href="/"><img class="home" src="/img/common/icon54.jpg" alt=""></a></li>
					<li><a href="/news/">ニュース</a></li>
					<li>お知らせ</li>
				</ul>
			</div>
		</div>
		<div class="mainBox" data-stt-ignore>
			<article id="conts">
				<div class="titleBox fadeInUp">
					<p class="time">2024.10.04<span class="cat-news">お知らせ</span></p>
					<p class="title">台風18号による配送への影響について（2024年10月04日9時現在）</p></div>
				<div class="wpBox fadeInUp">
                    <p  style="font-weight: bold">重要なお知らせ</p>
                    <br>
                    <p>2024.10.04</p>
                    <br>
                    <p>お客さま各位</p>
                    <br>
                    <p>平素よりＳＧムービングのサービスをご利用いただき誠にありがとうございます。</p>
                    <br>
                    <p>台風18号の接近に伴いフェリーの遅延・欠航により、以下の地域でお荷物の配送設置に遅れが生じております。</p>
                    <br>
                    <p>【お荷物の配送設置が遅れる地域】</p>
                    <br>
<ul>
<li>・沖縄県全域</li>
</ul>
                    <br>
                    <p>今後の状況により対象地域に変更が生じる可能性があります。</p>
                    <p>お客さまには大変ご迷惑をおかけしますが、ご了承のほど宜しくお願い申し上げます。</p>              
                </div>
				<div class="comBtn02 newsTop fadeInUp"><a href="/news/"><span>ニューストップへ戻る</span></a></div>
			</article>
			<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/side-1column-topics.php'); ?>
		</div>
		<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/footer-contact.php'); ?>
    </main>
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/footer.php'); ?>
</div>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_script_foot.php'); ?>
<script src="/js/news.js"></script>

</body>
</html>
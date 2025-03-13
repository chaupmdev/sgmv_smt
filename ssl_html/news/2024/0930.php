<?php $ua = strtolower($_SERVER["HTTP_USER_AGENT"]); $isMob = is_numeric(strpos($ua, "mobile")); 
$new = htmlspecialchars("", ENT_QUOTES);
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="石川県の大雨に伴う配送への影響について（2024年9月30日8時現在）">
<meta property="og:locale" content="ja_JP">
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl-ogp.php'); ?>">
<meta property="og:image" content="/img/ogp/og_image_logo.jpg">
<meta property="twitter:card" content="summary_large_image">
<link rel="shortcut icon" href="/img/common/favicon.ico">
<link rel="apple-touch-icon" sizes="192x192" href="/img/ogp/touch-icon.png">
<title>石川県の大雨に伴う配送への影響について（2024年9月30日8時現在）｜お知らせ｜<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl.php'); ?></title>
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

.article-table {
  width: 100%;
}



.article-table th {
  background: #3B4A9F;
  color: #000;
  font-weight: 700;

  padding: 16px;
  vertical-align: top;
  text-align: left;
  line-height: 1.8;
  
}

.article-table td {
  padding: 16px;
  vertical-align: top;
  text-align: left;
  line-height: 1.8;
  
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
					<p class="time">2024.09.30<span class="cat-news">お知らせ</span></p>
					<p class="title">石川県の大雨に伴う配送への影響について（2024年9月30日8時現在）</p></div>
				<div class="wpBox fadeInUp">
                    <p  style="font-weight: bold; ">重要なお知らせ</p>
                    <br>
                    <p>2024.09.30</p>
                    <br>
                    <p>お客さま各位</p>
                    <br>
                    <p>平素よりSGムービングのサービスをご利用いただき誠にありがとうございます。</p>
                    <p>石川県で発生した大雨の影響により冠水・氾濫が発生し道路状況が悪化、以下の地域でお荷物のお預かり、お届けに大幅な遅れが生じております。</p>
                    <br>
                    <p>【お荷物のお預かり・お届けが遅れる地域】</p>
                    <br>

<table class="article-table">
<tr>
<th>都道府県</th><th>市区郡</th>
</tr>
<tr>
<td>石川県</td><td>輪島市、珠洲市、鳳珠郡能登町</td>
</tr>
</table>
                    <br>
                    <p style="font-weight: bold; ">※全国から上記地域向けへのお荷物は<span style="text-decoration: underline;" >遅延了承のうえお預かり（荷受け）しております。</span></p>  
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
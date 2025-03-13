<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="事業所一覧ページです。">
<meta property="og:locale" content="ja_JP">
<meta property="og:type" content="website">
<meta property="og:url" content="https://www.sagawa-mov.co.jp/corporate/office/">
<meta property="og:site_name" content="<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl-ogp.php'); ?>">
<meta property="og:image" content="/img/ogp/og_image_sgmv.jpg">
<meta property="twitter:card" content="summary_large_image">
<link rel="shortcut icon" href="/img/common/favicon.ico">
<link rel="apple-touch-icon" sizes="192x192" href="/img/ogp/touch-icon.png">
<title>事業所一覧｜私たちについて｜<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl.php'); ?></title>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_style.php'); ?>
<link rel="stylesheet" type="text/css" href="/css/office.css">
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_script.php'); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/analytics_head.php'); ?>

<style>
    .modal {
      display: none;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: hidden;
      background-color: rgba(0, 0, 0, 0.7);
    }

    .modal-content {
      position: relative;
      background-color: #fff;
      margin: 10% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
      max-width: 600px;
      text-align: center;
    }

    img {
      width: 100%;
      height: auto;
    }
    
    @media only screen and (max-width: 1366px) {
        .modal-content {
            position: relative;
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 70% !important;
            max-width: 450px;
            text-align: center;
            /* height: 65%; */
            height: auto !important;
        }

        img {
            width: 100%;
            height: 100%;
        }
    }

    @media screen and (max-width: 600px) {
        .modal-content {
            width: 90% !important;
            margin: 50% auto;
            height: auto !important;
        }
    }
</style>

</head>
<body>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/analytics_body.php'); ?>
<div id="container">
	<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/header.php'); ?>
	<main id="main" role="main">
		<div class="pageTitle">
			<div class="comBox">
				<h1 class="topLead"><span>私たちについて</span>事業所一覧</h1>
				<ul id="pagePath">
					<li><a href="/"><img class="home" src="/img/common/icon54.jpg" alt=""></a></li>
					<li><a href="/corporate/">私たちについて</a></li>
					<li>事業所一覧</li>
				</ul>
			</div>
		</div>
		<div class="mainBox">
			<article id="conts">
				<div class="borderBox fadeInUp">
					<div class="listBox">
						<p class="ttl">本　社</p>
						<ul>
							<li><a href="#a01">本社</a></li>
						</ul>
					</div>
					<div class="listBox blue">
						<p class="ttl">営業所</p>
						<ul>
							<li><a href="#tokyobase">TOKYO BASE</a></li>
							<li><a href="#sapporo">札幌</a></li>
							<li><a href="#sendai">仙台</a></li>
							<li><a href="#west-kanto">西関東</a></li>
							<li><a href="#kanagawa">神奈川</a></li>
							<li><a href="#nagoya">名古屋</a></li>
							<li><a href="#kyoto">京都</a></li>
							<li><a href="#osaka">大阪</a></li>
							<li><a href="#kobe">神戸</a></li>
							<li><a href="#fukuoka">福岡</a></li>
							<li><a href="#okinawa">沖縄</a></li>
						</ul>
					</div>
					<div class="listBox green">
						<p class="ttl">出張所</p>
						<ul>
							<li><a href="#north-tohoku">北東北</a></li>
							<li><a href="#east-kanto">東関東</a></li>
							<li><a href="#northern-kanto">北関東</a></li>
							<li><a href="#shinetsu">信越</a></li>
							<li><a href="#hokuriku">北陸</a></li>
							<li><a href="#chugoku">中国</a></li>
							<li><a href="#kouthern-kyushu">南九州</a></li>
						</ul>
					</div>
					<div class="listBox green">
						<p class="ttl">オフィス</p>
						<ul>
							<li><a href="#yokohama">横浜オフィス</a></li>
							<li><a href="#tottori">鳥取オフィス</a></li>
							<li><a href="#kagoshima">鹿児島オフィス</a></li>
						</ul>
					</div>
				</div>
				<div class="mapImg fadeInUp"><img src="/img/corporate/office/img01.jpg" alt="本社 営業所 出張所"></div>
				<div class="photoBox fadeInUp" id="a01">
					<div class="pho"><img src="/img/corporate/office/photo01.jpg" alt="SGムービング本社"></div>
					<p class="ttl">SGムービング本社<a href="#" class="mapLink open-modal" data-img="/img/corporate/office/map_honsha_tokyobase.png">地図</a></p>
					<p class="text">〒136-0075 東京都江東区新砂3-2-9 XフロンティアEAST6階<br class="sp"></p>
				</div>
				<h2 class="headLine03 fadeInUp">営業所</h2>
				<ul class="textList flexB fadeInUp">
					<li id="tokyobase">
						<p class="ttl">TOKYO BASE<a href="#" class="mapLink open-modal" data-img="/img/corporate/office/map_honsha_tokyobase.png">地図</a><a href="pdf/price_tokyobase.pdf" class="logi" target="_new">倉庫</a></p>
						<p class="text">〒136-0075<br>
								東京都江東区新砂3-2-9<br>
								XフロンティアEAST6階</p>
					</li>
					<li id="sapporo">
						<p class="ttl">札幌営業所<a href="#" class="mapLink open-modal" data-img="/img/corporate/office/sapporo.png">地図</a></p>
						<p class="text">〒007-0868<br>
								北海道札幌市東区伏古八条1-2-10</p>
					</li>
					<li id="sendai">
						<p class="ttl">仙台営業所<a href="#" class="mapLink open-modal" data-img="/img/corporate/office/sendai.png">地図</a></p>
						<p class="text">〒983-0034<br>
								宮城県仙台市宮城野区扇町4-6-8</p>
					</li>
					<li id="west-kanto">
						<p class="ttl">西関東営業所<a href="#" class="mapLink open-modal" data-img="/img/corporate/office/nishikanto.png">地図</a></p>
						<p class="text">〒190-0015<br>
								東京都立川市泉町935</p>
					</li>
					<li id="kanagawa">
						<p class="ttl">神奈川営業所<a href="#" class="mapLink open-modal" data-img="/img/corporate/office/kanagawa.png">地図</a><a href="pdf/price_kanagawa.pdf" class="logi" target="_new">倉庫</a></p>
						<p class="text">〒194-0004<br>
								東京都町田市鶴間7-30-1</p>
					</li>
					<li id="nagoya">
						<p class="ttl">名古屋営業所<a href="#" class="mapLink open-modal" data-img="/img/corporate/office/nagoya.png">地図</a><a href="pdf/price_nagoya.pdf" class="logi" target="_new">倉庫</a></p>
						<p class="text">〒485-0073<br>
								愛知県小牧市舟津八反田136</p>
					</li>
					<li id="kyoto">
						<p class="ttl">京都営業所<a href="#" class="mapLink open-modal" data-img="/img/corporate/office/kyoto.png">地図</a></p>
						<p class="text">〒612-8244<br>
								京都府京都市伏見区横大路<br>
								千両松町97</p>
					</li>
					<li id="osaka">
						<p class="ttl">大阪営業所<a href="#" class="mapLink open-modal" data-img="/img/corporate/office/osaka.png">地図</a><a href="pdf/price_oosaka.pdf" class="logi" target="_new">倉庫</a></p>
						<p class="text">〒554-0041<br>
								大阪府大阪市此花区北港白津2-5-33<br>
								SGリアルティ舞洲 6F</p>
					</li>
					<li id="kobe">
						<p class="ttl">神戸営業所<a href="#" class="mapLink open-modal" data-img="/img/corporate/office/kobe.png">地図</a></p>
						<p class="text">〒658-0024<br>
								兵庫県神戸市東灘区魚崎浜町36-1</p>
					</li>
					<li id="fukuoka">
						<p class="ttl">福岡営業所<a href="#" class="mapLink open-modal" data-img="/img/corporate/office/fukuoka.png">地図</a></p>
						<p class="text">〒812-0862<br>
								福岡県福岡市博多区立花寺1-1-43</p>
					</li>
					<li id="okinawa">
						<p class="ttl">沖縄営業所<a href="#" class="mapLink open-modal" data-img="/img/corporate/office/okinawa.png">地図</a></p>
						<p class="text">〒901-0225<br>
								沖縄県豊見城市豊崎3-26<br>
								琉球通運航空ビル1F</p>
					</li>
					<li id="logistics">
						<div class="comBtn comBtn02 btn01">
							<a href="#" id="logistics_modal_open">
								倉庫業法に基づく表示
							</a>
						</div>
						<div id="logistics_modal">
							<div class="logistics_modal_bg"></div>
							<div class="logistics_modal_contents">
								<img id="logistics_modal_close" class="btn_close" src="/img/index/icon_close.png" alt="">
								<div class="logistics_modal_content">
									<p class="title">倉庫業法に基づく表示</p>
									<ul class="links">
										<li class="link-item"><a href="pdf/price_tokyobase.pdf" target="_new">倉庫料金表【TOKYOBASE】</a></li>
										<li class="link-item"><a href="pdf/price_kanagawa.pdf" target="_new">倉庫料金表【神奈川営業所】</a></li>
										<li class="link-item"><a href="pdf/price_nagoya.pdf" target="_new">倉庫料金表【名古屋営業所】</a></li>
										<li class="link-item"><a href="pdf/price_oosaka.pdf" target="_new">倉庫料金表【大阪営業所】</a></li>
									</ul>
								</div>
							</div>
						</div>
					</li>
				</ul>
				<h2 class="headLine03 fadeInUp">出張所</h2>
				<ul class="textList flexB fadeInUp">
					<li id="north-tohoku">
						<p class="ttl">北東北出張所<a href="https://goo.gl/maps/jVcYj1f4srDVf8kcA" target="_blank" class="mapLink">地図</a></p>
						<p class="text">〒020-0846<br>
						岩手県盛岡市流通センター北1-32-3<br>
							佐川急便北東北支店内</p>
					</li>
					<li id="east-kanto">
						<p class="ttl">東関東出張所<a href="https://goo.gl/maps/mW9W34CzesMZtXfU9" target="_blank" class="mapLink">地図</a></p>
						<p class="text">〒305-0861<br>
							茨城県つくば市大字谷田部宇山崎6882-1<br>
							佐川急便つくば営業所内</p>
					</li>
					<li id="northern-kanto">
						<p class="ttl">北関東出張所<a href="https://maps.app.goo.gl/bc1kzr6UdJ7y4cbE7" target="_blank" class="mapLink">地図</a></p>
						<p class="text">〒349-0204<br>
						埼玉県白岡市篠津914-3<br>
						佐川急便北関東支店内</p>
					</li>
					<li id="shinetsu">
						<p class="ttl">信越出張所<a href="https://maps.app.goo.gl/yPyF3tWhyuePz5U4A" target="_blank" class="mapLink">地図</a></p>
						<p class="text">〒382-0045<br>
						長野県須坂市大字井上700-1<br>
							佐川急便信越支店内</p>
					</li>
					<li id="hokuriku">
						<p class="ttl">北陸出張所<a href="https://goo.gl/maps/F1p3PT7tRr3M9yUV7" target="_blank" class="mapLink">地図</a></p>
						<p class="text">〒920-0203<br>
							石川県金沢市木越町ト80<br>
							佐川急便北陸支店内</p>
					</li>
					<li id="chugoku">
						<p class="ttl">中国出張所<a href="https://goo.gl/maps/Gk8vXQyfJEST1UbE7" target="_blank" class="mapLink">地図</a></p>
						<p class="text">〒734-0013<br>
							広島県広島市南区出島1-19-20<br>
							佐川急便中国支店内</p>
					</li>
					<li id="kouthern-kyushu">
						<p class="ttl">南九州出張所<a href="https://goo.gl/maps/3PC6boqTBKFNSzZ99" target="_blank" class="mapLink">地図</a></p>
						<p class="text">〒861-8030<br>
						熊本県熊本市東区小山町1816-1<br>
						佐川急便南九州支店内</p>
					</li>
				</ul>
				<h2 class="headLine03 fadeInUp">オフィス</h2>
				<ul class="textList flexB fadeInUp">
					<li id="yokohama">
						<p class="ttl">横浜オフィス<a href="https://maps.app.goo.gl/2jkGamu59PF5LJWKA" target="_blank" class="mapLink">地図</a></p>
						<p class="text">〒236-0002<br>
							神奈川県横浜市金沢区鳥浜7-3<br>
							佐川急便神奈川支店内</p>
					</li>
					<li id="tottori">
						<p class="ttl">鳥取オフィス<a href="https://maps.app.goo.gl/uzWxSt4rszbtMMi9A" target="_blank" class="mapLink">地図</a></p>
						<p class="text">〒680-0874<br>
						鳥取県鳥取市叶字下井原114-5<br>
						佐川急便鳥取営業所内</p>
					</li>
					<li id="kagoshima">
						<p class="ttl">鹿児島オフィス<a href="https://maps.app.goo.gl/rTQ1z2C1ckNMPTpM8" target="_blank" class="mapLink">地図</a></p>
						<p class="text">〒890-0073<br>
						鹿児島県鹿児島市宇宿2-10-1<br>
						佐川急便鹿児島営業所内</p>
					</li>
				</ul>
			</article>
			<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/side-1column-corporate.php'); ?>
		</div>
		<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/footer-contact.php'); ?>
	</main>
	<!-- Map Modal -->
	<div id="mapModal" class="modal">
		<div class="modal-content">
		<img id="modalImg" src="" alt="Image">
		</div>
	</div>
	
	<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/footer.php'); ?>
</div>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_script_foot.php'); ?>
<script src="/js/sidebar.js"></script>
<script>
	$(function(){
		$('#gHeader .hBox #gNavi .hLinkList > li').eq(0).addClass('current');
	});
</script>
<script>

const modal = document.getElementById('mapModal');
const modalImg = document.getElementById('modalImg');
const links = document.querySelectorAll('.open-modal');
links.forEach(link => {
    link.addEventListener('click', (event) => {
        event.preventDefault();
        const imgSrc = link.getAttribute('data-img');
        modalImg.src = imgSrc;
        modal.style.display = 'block';
    });
});
window.addEventListener('click', function(event) {
	if (event.target === modal) {
		modal.style.display = 'none';
	}
});

document.getElementById('logistics_modal_open').addEventListener('click', function(e){
	e.preventDefault();
	logistics_modal_open();
});
document.getElementById('logistics_modal_close').addEventListener('click', function(e){
	logistics_modal_close();
});

function logistics_modal_open(){
	document.getElementById('logistics_modal').classList.add('active');
}
function logistics_modal_close(){
	document.getElementById('logistics_modal').classList.remove('active');
}
</script>
</body>
</html>
<?php
	
	//activeのエリア設定
	$gnavSettings_01 = "";
	$gnavSettings_02 = "";
	$gnavSettings_03 = "";
	$gnavSettings_04 = "";
	$gnavSettings_05 = "";
	if($gnavSettings == "index"){
		$gnavSettings_01 = 'class="active"';
	}elseif($gnavSettings == "personal"){
		$gnavSettings_02 = 'class="active"';
	}
	elseif($gnavSettings == "business"){
		$gnavSettings_03 = 'class="active"';
	}
	elseif($gnavSettings == "contact"){
		$gnavSettings_04 = 'class="active"';
	}
	elseif($gnavSettings == "corporate"){
		$gnavSettings_05 = 'class="active"';
	}
	
?>

<div id="header" class="clearfix">
	<div class="wrap clearfix">
		<div id="header_logo" class="clearfix">
			<div><a href="/"><img src="/images/common/img_header_01.png" alt="SGH" /></a></div>
			<div><a href="/"><img src="/images/common/img_header_02.png" alt="SGmoving" /></a><span>ＳＧムービングはお客様に高品質な輸送を提供します。</span></div>
		</div>
		<ul id="local_nav" class="clearfix">
			<li><a href="/quality/">品質と信頼</a></li>
			<li><a href="/corporate/recruit/">採用情報</a></li>
			<li><a href="/sitemap.php">サイトマップ</a></li>
		</ul>
		<div id="nav_btn"><a href="javascript:void(0)"><img src="/images/common/img_header_04.png" alt="menu" /></a></div>
	</div>
</div><!--header-->
<div id="nav">
	<div id="pc_nav">
		<ul class="wrap clearfix">
			<li><a href="/" <?php echo $gnavSettings_01;?>><img src="/images/common/img_header_03.png" alt="Home" /></a></li>
			<li><a href="/personal/" <?php echo $gnavSettings_02;?>>個人のお客様</a></li>
			<li><a href="/business/" <?php echo $gnavSettings_03;?>>法人のお客様</a></li>
			<li><a href="/contact/" <?php echo $gnavSettings_04;?>>お問い合わせ</a></li>
			<li><a href="/corporate/" <?php echo $gnavSettings_05;?>>会社情報</a></li>
		</ul>
	</div>
	<div id="sp_nav">
		<ul class="wrap clearfix">
			<li><a href="/">トップページ</a></li>
			<li><a href="/contact/">お問い合わせ</a></li>
			<li><a href="/personal/">個人のお客様</a></li>
			<li><a href="/business/">法人のお客様</a></li>
			<li><a href="/corporate/">会社情報</a></li>
			<li><a href="/topics/">お知らせ一覧</a></li>
			<li><a href="/corporate/recruit/">採用情報</a></li>
			<li><a href="/sitemap/">サイトマップ</a></li>
		</ul>
	</div>
</div>

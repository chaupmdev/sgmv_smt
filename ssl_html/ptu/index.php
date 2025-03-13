<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0" />
<meta name="Keywords" content="" />
<meta name="Description" content="引越・設置のＳＧムービング株式会社のWEBサイトです。個人の方はもちろん、法人様への実績も豊富です。無料お見積りはＳＧムービング株式会社(フリーダイヤル:0570-056-006)まで。" />
<title>単身カーゴプランのお申し込み│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
<link href="/css/common.css" rel="stylesheet" type="text/css" />
<link href="/css/plan.css" rel="stylesheet" type="text/css" />
<link href="/css/pre.css" rel="stylesheet" type="text/css" />
<link href="/css/form.css" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/underscore.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/common_new.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/mobilyslider.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/mob.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/api.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/multi_send.js"></script>
<script charset="UTF-8" type="text/javascript" src="/common/js/ajax_searchaddress.js"></script>
<script charset="UTF-8" type="text/javascript" src="/common/js/jquery.ah-placeholder.js"></script>
<script charset="UTF-8" type="text/javascript" src="/common/js/jquery.autoKana.js"></script>
<script charset="UTF-8" type="text/javascript" src="/ptu/js/input.js"></script>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){

	$(".accordion_button").click(function(e){
			$(this).next().slideToggle();
			$(this).toggleClass("active");
			e.preventDefault();
			return false;
		});
});
//]]>
</script>
    <script src="/js/ga.js" type="text/javascript"></script>
</head>

<body>

<?php
/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useComponents('Config');
Sgmov_Lib::useView('ptu/Input');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Ptu_Input();
$forms = $view->execute();

/**
* チケット
* @var string
*/
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Ptu001Out
 */
$ptu001Out = $forms['outForm'];

/**
* 便種
* @var string
*/
$binshu = $ptu001Out->binshu_cd();

/**
 * エラーフォーム
 * @var Sgmov_Form_Error
 */
$e = $forms['errorForm'];

    //タイトル
    if ($binshu == '906') {
    	$title = '単品輸送プランのお申し込み';
    	$komoku = '単品輸送品目';
    } else {
    	$title = '単身カーゴプランのお申し込み';
    	$komoku = 'カーゴ台数';
    }

    // スマートフォン・タブレット判定
    $detect = new MobileDetect();
    $isSmartPhone = $detect->isMobile();
    if ($isSmartPhone) {
    $inputTypeEmail  = 'email';
    $inputTypeNumber = 'number';
} else {
    $inputTypeEmail  = 'text';
    $inputTypeNumber = 'text';
}
?>

<?php
    $gnavSettings = 'contact';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/header.php';
?>
    <div id="breadcrumb">
        <ul class="wrap">
            <li><a href="/">ホーム</a></li>
            <li><a href="/contact/">お問い合わせ</a></li>
            <li class="current"><?php echo $title; ?></li>
        </ul>
    </div>
    <div id="main">
        <div class="wrap clearfix">
            <h1 class="page_title"><?php echo $title; ?></h1>
            <p class="sentence btm30">
                以下のフォームにもれなくご入力をお願いいたします。
                <br />※前日23時までにお支払の確認ができたお客様につきましては、翌日より集荷が可能となります。
                <br />
                <span class="red">
                    ※迷惑メールを設定されている方は「sagawa-mov.co.jp」を受信する設定にしてください。
                    <br />詳しくは<a href="#bounce_mail">こちら</a>
                </span>
            </p>

			<!--▼エラーメッセージ　ここから-->
<?php if (isset($e) && $e->hasError()) { ?>
		<div class="err_msg">
			<p class="sentence br attention">下記の項目が正しく入力・選択されていません。</p>
			<ul>
				<!-- <li><a href="#name">お名前 姓を入力してください。</a></li>
				<li><a href="#name">お名前 名を入力してください。</a></li>
				<li><a href="#tel">電話番号を入力してください。</a></li>
				<li><a href="#mail">メールアドレスを入力してください。</a></li>
				<li><a href="#retype_mail">アドレス確認を入力してください。</a></li>
				<li><a href="#zip">郵便番号を入力してください。</a></li>
				<li><a href="#pref">都道府県を選択してください。</a></li>
				<li><a href="#address">市区町村を入力してください。</a></li>
				<li><a href="#building">番地・建物名を入力してください。</a></li>
				<li><a href="#name_hksaki">お引越し先の名前 姓を入力してください。</a></li>
				<li><a href="#name_hksaki">お引越し先の名前 名を入力してください。</a></li>
				<li><a href="#zip_hksaki">お引越し先の郵便番号を入力してください。</a></li>
				<li><a href="#pref_hksaki">お引越し先の都道府県を選択してください。</a></li>
				<li><a href="#address_hksaki">お引越し先の市区町村を入力してください。</a></li>
				<li><a href="#building_hksaki">お引越し先の番地・建物名を入力してください。</a></li>
				<li><a href="#tel_hksaki">お引越し先の電話番号を入力してください。</a></li>
				<li><a href="#hikitori_yotehiji_date">お引取り予定日時を選択してください。</a></li>
				<li><a href="#hikoshi_yotehiji_date">お引越し予定日時を選択してください。</a></li>
				<li><a href="#tanhin_cd_sel">単品輸送品目を選択してください。</a></li> -->
<?php
		        // エラー表示
		        if ($e->hasErrorForId('top_surname')) {
		            echo '<li><a href="#name">お名前 姓' . $e->getMessage('top_surname') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_forename')) {
		            echo '<li><a href="#name">お名前 名' . $e->getMessage('top_forename') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_name')) {
		        	echo '<li><a href="#name">お名前' . $e->getMessage('top_name') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_tel')) {
		            echo '<li><a href="#tel">電話番号' . $e->getMessage('top_tel') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_fax')) {
		        	echo '<li><a href="#tel">FAX番号' . $e->getMessage('top_fax') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_mail')) {
		            echo '<li><a href="#mail">メールアドレス' . $e->getMessage('top_mail') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_retype_mail')) {
		            echo '<li><a href="#retype_mail">アドレス確認' . $e->getMessage('top_retype_mail') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_zip')) {
		            echo '<li><a href="#zip">郵便番号' . $e->getMessage('top_zip') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_pref_cd_sel')) {
		            echo '<li><a href="#pref">都道府県' . $e->getMessage('top_pref_cd_sel') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_address')) {
		            echo '<li><a href="#address">市区町村' . $e->getMessage('top_address') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_building')) {
		            echo '<li><a href="#building">番地・建物名' . $e->getMessage('top_building') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_addressbuild')) {
		        	echo '<li><a href="#address">市区町村・番地・建物名' . $e->getMessage('top_addressbuild') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_surname_hksaki')) {
		        	echo '<li><a href="#name_hksaki">お引越し先の名前 姓' . $e->getMessage('top_surname_hksaki') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_forename_hksaki')) {
		        	echo '<li><a href="#name_hksaki">お引越し先の名前 名' . $e->getMessage('top_forename_hksaki') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_name_hksaki')) {
		        	echo '<li><a href="#name_hksaki">お引越し先の名前' . $e->getMessage('top_name_hksaki') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_zip_hksaki')) {
		        	echo '<li><a href="#zip_hksaki">お引越し先の郵便番号' . $e->getMessage('top_zip_hksaki') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_pref_cd_sel_hksaki')) {
		        	echo '<li><a href="#pref_hksaki">お引越し先の都道府県' . $e->getMessage('top_pref_cd_sel_hksaki') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_address_hksaki')) {
		        	echo '<li><a href="#address_hksaki">お引越し先の市区町村' . $e->getMessage('top_address_hksaki') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_building_hksaki')) {
		        	echo '<li><a href="#building_hksaki">お引越し先の番地・建物名' . $e->getMessage('top_building_hksaki') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_addressbuild_hksaki')) {
		        	echo '<li><a href="#address_hksaki">お引越し先の市区町村・番地・建物名' . $e->getMessage('top_addressbuild_hksaki') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_tel_hksaki')) {
		        	echo '<li><a href="#tel_hksaki">お引越し先の電話番号' . $e->getMessage('top_tel_hksaki') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_tel_fuzai_hksaki')) {
		        	echo '<li><a href="#tel_fuzai_hksaki">お引越し先の不在連絡先' . $e->getMessage('top_tel_fuzai_hksaki') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_hikitori_yotehiji_date')) {
		        	echo '<li><a href="#hikitori_yotehiji_date">お引取り予定日時' . $e->getMessage('top_hikitori_yotehiji_date') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_hikoshi_yotehiji_date')) {
		        	echo '<li><a href="#hikoshi_yotehiji_date">お引越し予定日時' . $e->getMessage('top_hikoshi_yotehiji_date') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_hikoshi_yotehiji_time')) {
		        	echo '<li><a href="#hikoshi_yotehiji_date">お引越し予定時間帯' . $e->getMessage('top_hikoshi_yotehiji_time') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_textHanshutsu')) {
		        	echo '<li><a href="#textHanshutsu_004">お引取りオプション' . $e->getMessage('top_textHanshutsu') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_textHannyu')) {
		        	echo '<li><a href="#textHannyu_017">お引越しオプション' . $e->getMessage('top_textHannyu') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_cago_daisu')) {
		        	echo '<li><a href="#cago_daisu">カーゴ台数' . $e->getMessage('top_cago_daisu') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_tanhin_cd_sel')) {
		        	echo '<li><a href="#tanhin_cd_sel">単品輸送品目' . $e->getMessage('top_tanhin_cd_sel') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_payment_method_cd_sel')) {
		            echo '<li><a href="#payment_method">お支払い方法' . $e->getMessage('top_payment_method_cd_sel') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_payment_method_cd_sel_convenience')) {
		            echo '<li><a href="#payment_method">' . $e->getMessage('top_payment_method_cd_sel_convenience') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_travel_cd_sel_convenience')) {
		            echo '<li><a href="#payment_method">' . $e->getMessage('top_travel_cd_sel_convenience') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_convenience_store_cd_sel')) {
		            echo '<li><a href="#convenience">お支払店舗' . $e->getMessage('top_convenience_store_cd_sel') . '</a></li>';
		        }
		        if ($e->hasErrorForId('top_kingaku')) {
		        	echo '<li><a href="#kingaku">' . $e->getMessage('top_kingaku') . '</a></li>';
		        }
?>
			</ul>
		</div>
<?php } ?>
			<!--▲エラーメッセージ　ここまで-->

	 <form action="" data-feature-id="<?php echo Sgmov_View_Ptu_Common::FEATURE_ID ?>" data-id="<?php echo Sgmov_View_Ptu_Common::GAMEN_ID_PTU001 ?>" method="post">
        <input type="hidden" name="ticket" value="<?php echo $ticket ?>" />
        <input type="hidden" id="binshu_cd" name="binshu_cd" value="<?php echo $binshu; ?>" />
        <input type="hidden" id="shohizei" name="shohizei" value="<?php echo $ptu001Out->shohizei(); ?>" />
<?php
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')) {
        	echo '<input type="hidden" id="hd_browser_safira" name="hd_browser_safira" value="1" />';
    	} else {
    		echo '<input type="hidden" id="hd_browser_safira" name="hd_browser_safira" value="2" />';
    	}
?>


			<!--▼お客様情報　ここから-->
		<div class="section">
			<h4 class="cont_inner_title">お客様情報</h4>
			<div class="dl_block">
				<dl id="" class="form_list clearfix">
					<dt id="name">お名前</dt>
					<dd>
						<input autocapitalize="off" class="w_100<?php if (isset($e) && $e->hasErrorForId('top_surname')) { echo ' form_error'; } ?>" maxlength="32" name="surname" id="surname" placeholder="例）佐川" type="text" value="<?php echo $ptu001Out->surname();?>" />
                    	<input autocapitalize="off" class="w_100<?php if (isset($e) && $e->hasErrorForId('top_forename')) { echo ' form_error'; } ?>" maxlength="32" name="forename" id="forename" placeholder="例）花子" type="text" value="<?php echo $ptu001Out->forename();?>" />
					</dd>
				</dl>
				<dl>
					<dt class="even" id="tel">電話番号</dt>
	                <dd class="even">
	                    <input autocapitalize="off" class="w_70<?php if (isset($e) && $e->hasErrorForId('top_tel')) { echo ' form_error'; } ?>" inputmode="" maxlength="4" name="tel1" id="tel1" data-pattern="^[!-~]+$" placeholder="例）075" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $ptu001Out->tel1();?>" />
	                    -
	                    <input autocapitalize="off" class="w_70<?php if (isset($e) && $e->hasErrorForId('top_tel')) { echo ' form_error'; } ?>" inputmode="" maxlength="4" name="tel2" id="tel2" data-pattern="^[!-~]+$" placeholder="例）1234" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $ptu001Out->tel2();?>" />
	                    -
	                    <input autocapitalize="off" class="w_70<?php if (isset($e) && $e->hasErrorForId('top_tel')) { echo ' form_error'; } ?>" inputmode="" maxlength="4" name="tel3" id="tel3" data-pattern="^[!-~]+$" placeholder="例）5678" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $ptu001Out->tel3();?>" />
	                </dd>
				</dl>
				<dl>
					<dt id="tel">FAX番号</dt>
	                <dd >
	                    <input autocapitalize="off" class="w_70<?php if (isset($e) && $e->hasErrorForId('top_fax')) { echo ' form_error'; } ?>" inputmode="numeric" maxlength="4" name="fax1" data-pattern="^[!-~]+$" placeholder="例）123" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $ptu001Out->fax1();?>" />
	                    -
	                    <input autocapitalize="off" class="w_70<?php if (isset($e) && $e->hasErrorForId('top_fax')) { echo ' form_error'; } ?>" inputmode="numeric" maxlength="4" name="fax2" data-pattern="^[!-~]+$" placeholder="例）1234" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $ptu001Out->fax2();?>" />
	                    -
	                    <input autocapitalize="off" class="w_70<?php if (isset($e) && $e->hasErrorForId('top_fax')) { echo ' form_error'; } ?>" inputmode="numeric" maxlength="4" name="fax3" data-pattern="^[!-~]+$" placeholder="例）1234" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $ptu001Out->fax3();?>" />
	                </dd>
				</dl>
				<dl>
					<dt class="condition_02 even" id="mail">メールアドレス</dt>
	                <dd class="width_change even" id="mail_address">
	                    <input class="w_280" autocapitalize="off"<?php if (isset($e) && $e->hasErrorForId('top_mail')) { echo ' class="form_error"'; } ?> inputmode="email" name="mail" data-pattern="^[!-~]+$" placeholder="例）ringo@sagawa.com" type="<?php echo $inputTypeEmail; ?>" value="<?php echo $ptu001Out->mail();?>" />
	                    <br class="sp_only" /><span>※申込完了の際に申込完了メールを送付させていただきますので、ご入力ください。</span>
	                    <p class="attention">
	                        ※迷惑メールを設定されている方は「sagawa-mov.co.jp」を受信する設定にしてください。
	                        <br />詳しくは<a href="#bounce_mail">こちら</a>
	                    </p>
	                </dd>
				</dl>
				<dl>
					<dt id="retype_mail">アドレス確認</dt>
					<dd class="width_change">
						<input class="w_280" autocapitalize="off"<?php if (isset($e) && $e->hasErrorForId('top_retype_mail')) { echo ' class="form_error"'; } ?> inputmode="email" name="retype_mail" data-pattern="^[!-~]+$" placeholder="例）ringo@sagawa.com" type="<?php echo $inputTypeEmail; ?>" value="<?php echo $ptu001Out->retype_mail();?>" />
					</dd>
				</dl>
			</div>
		</div>
			<!--▲お客様情報　ここまで-->
			<!--▼現在のお住まいについて　ここから-->
		<div class="section">
			<h4 class="cont_inner_title">現在のお住まいについて</h4>
			<div class="dl_block">
				<dl class="form_list clearfix">
					<dt id="zip" class="even">郵便番号</dt>
					<dd class="even">
						<input autocapitalize="off" class="w_70<?php if (isset($e) && $e->hasErrorForId('top_zip')) { echo ' form_error'; } ?>" maxlength="3" inputmode="numeric" name="zip1" data-pattern="^[!-~]+$" placeholder="例）136" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $ptu001Out->zip1();?>" />
	                    -
	                    <input autocapitalize="off" class="w_70<?php if (isset($e) && $e->hasErrorForId('top_zip')) { echo ' form_error'; } ?>" maxlength="4" inputmode="numeric" name="zip2" data-pattern="^[!-~]+$" placeholder="例）0082" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $ptu001Out->zip2();?>" />
	                    <input class="m110" name="adrs_search_btn" type="button" value="住所検索" />
	                    <span style="font-size:12px;">　※郵便番号が不明な方は<a href = "http://www.post.japanpost.jp/zipcode/" TARGET="_blank">こちら...</a></span>
					</dd>
				</dl>
				<dl>
					<dt id="pref">都道府県</dt>
					<dd>
						<select id="pref_cd_sel" name="pref_cd_sel" class="ken todofuken <?php if (isset($e) && $e->hasErrorForId('top_pref_cd_sel')) { echo ' form_error'; } ?>">
                        	<option value="">県を選択</option>
							<?php echo Sgmov_View_Ptu_Input::_createPulldown($ptu001Out->pref_cds(), $ptu001Out->pref_lbls(), $ptu001Out->pref_cd_sel());?>
                    	</select>
					</dd>
				</dl>
				<dl>
					<dt id="address" class="even">市区町村</dt>
					<dd class="even width_change">
						<input class="w_220" autocapitalize="off"<?php if (isset($e) && ($e->hasErrorForId('top_address') || $e->hasErrorForId('top_addressbuild'))) { echo ' class="form_error"'; } ?> maxlength="28" name="address" placeholder="例）江東区新木場" type="text" value="<?php echo $ptu001Out->address();?>" />
					</dd>
				</dl>
				<dl>
					<dt id="building">番地・建物名</dt>
					<dd class="width_change w_220"><input autocapitalize="off"<?php if (isset($e) && ($e->hasErrorForId('top_building') || $e->hasErrorForId('top_addressbuild'))) { echo ' class="form_error"'; } ?> maxlength="28" name="building" placeholder="例）2-14-11" type="text" value="<?php echo $ptu001Out->building();?>" /></dd>
				</dl>
			</div>
		</div>
		<!--▲現在のお住まいについて　ここまで-->

		<!--▼お引越し先のお住まいについて　ここから-->
		<div class="section">
			<h4 class="cont_inner_title">お引越し先のお住まいについて</h4>
			<div class="dl_block">
				<dl class="form_list">
					<dt id="name_hksaki">お名前</dt>
					<dd>
						<input id="surname_hksaki" name="surname_hksaki" class="w_100<?php if (isset($e) && $e->hasErrorForId('top_surname_hksaki')) { echo ' form_error'; } ?>" type="text" maxlength="32" placeholder="例）佐川" value="<?php echo $ptu001Out->surname_hksaki();?>" autocapitalize="off" />
						<input id="forename_hksaki" name="forename_hksaki" class="w_100<?php if (isset($e) && $e->hasErrorForId('top_forename_hksaki')) { echo ' form_error'; } ?>" type="text" maxlength="32" placeholder="例）花子" value="<?php echo $ptu001Out->forename_hksaki();?>" autocapitalize="off" />
						<input class="m110" name="name_copy_btn" type="button" value="既入力情報をコピー" />
					</dd>
				</dl>
				<dl>
					<dt id="zip_hksaki" class="even">郵便番号</dt>
					<dd class="even">
						<input name="zip1_hksaki" class="w_70<?php if (isset($e) && $e->hasErrorForId('top_zip_hksaki')) { echo ' form_error'; } ?>" maxlength="3" placeholder="例）136" value="<?php echo $ptu001Out->zip1_hksaki();?>" data-pattern="^[!-~]+$" type="<?php echo $inputTypeNumber; ?>" autocapitalize="off" inputmode="numeric" />
						-
						<input name="zip2_hksaki" class="w_70<?php if (isset($e) && $e->hasErrorForId('top_zip_hksaki')) { echo ' form_error'; } ?>" maxlength="4" placeholder="例）0082" value="<?php echo $ptu001Out->zip2_hksaki();?>" data-pattern="^[!-~]+$" type="<?php echo $inputTypeNumber; ?>" autocapitalize="off" inputmode="numeric" />
						<input name="adrs_search_btn_hksaki" class="m110" type="button" value="住所検索" />
						<span style="font-size: 12px;">　※郵便番号が不明な方は<a href="http://www.post.japanpost.jp/zipcode/" target="_blank">こちら...</a></span> </dd>
				</dl>
				<dl>
					<dt id="pref_hksaki">都道府県</dt>
					<dd>
						<select id="pref_cd_sel_hksaki" name="pref_cd_sel_hksaki" class="ken todofuken <?php if (isset($e) && $e->hasErrorForId('top_pref_cd_sel_hksaki')) { echo ' form_error'; } ?>">
							<option value="">県を選択</option>
							<?php echo Sgmov_View_Ptu_Input::_createPulldown($ptu001Out->pref_cds(), $ptu001Out->pref_lbls(), $ptu001Out->pref_cd_sel_hksaki());?>
						</select>
					</dd>
				</dl>
				<dl>
					<dt class="even" id="address_hksaki">市区町村</dt>
					<dd class="width_change even">
						<input class="w_220" name="address_hksaki" type="text"<?php if (isset($e) && ($e->hasErrorForId('top_address_hksaki')|| $e->hasErrorForId('top_addressbuild_hksaki'))) { echo ' class="form_error"'; } ?> maxlength="44" placeholder="例）江東区新木場" value="<?php echo $ptu001Out->address_hksaki();?>" autocapitalize="off" />
					</dd>
				</dl>
				<dl>
					<dt id="building_hksaki">番地・建物名</dt>
					<dd class="width_change">
						<input class="w_220" name="building_hksaki" type="text"<?php if (isset($e) && ($e->hasErrorForId('top_building_hksaki')|| $e->hasErrorForId('top_addressbuild_hksaki'))) { echo ' class="form_error"'; } ?> maxlength="44" placeholder="例）2-14-11" value="<?php echo $ptu001Out->building_hksaki();?>" autocapitalize="off" />
					</dd>
				</dl>
				<dl>
					<dt id="tel_hksaki" class="even">電話番号</dt>
					<dd class="even">
						<input name="tel1_hksaki" id="tel1_hksaki" class="w_70<?php if (isset($e) && $e->hasErrorForId('top_tel_hksaki')) { echo ' form_error'; } ?>" type="<?php echo $inputTypeNumber; ?>" maxlength="4" placeholder="例）075" value="<?php echo $ptu001Out->tel1_hksaki();?>" data-pattern="^[!-~]+$" autocapitalize="off" inputmode="numeric" />
						-
						<input name="tel2_hksaki" id="tel2_hksaki" class="w_70<?php if (isset($e) && $e->hasErrorForId('top_tel_hksaki')) { echo ' form_error'; } ?>" type="<?php echo $inputTypeNumber; ?>" maxlength="4" placeholder="例）1234" value="<?php echo $ptu001Out->tel2_hksaki();?>" data-pattern="^[!-~]+$" autocapitalize="off" inputmode="numeric" />
						-
						<input name="tel3_hksaki" id="tel3_hksaki" class="w_70<?php if (isset($e) && $e->hasErrorForId('top_tel_hksaki')) { echo ' form_error'; } ?>" type="<?php echo $inputTypeNumber; ?>" maxlength="4" placeholder="例）5678" value="<?php echo $ptu001Out->tel3_hksaki();?>" data-pattern="^[!-~]+$" autocapitalize="off" inputmode="numeric" />
						<input class="m110" name="tel_copy_btn" type="button" value="既入力情報をコピー" />
					</dd>
				</dl>
				<dl>
					<dt id="tel_fuzai_hksaki">不在時連絡先</dt>
					<dd>
						<input name="tel1_fuzai_hksaki" class="w_70<?php if (isset($e) && $e->hasErrorForId('top_tel_fuzai_hksaki')) { echo ' form_error'; } ?>" type="<?php echo $inputTypeNumber; ?>" maxlength="4" placeholder="例）075" value="<?php echo $ptu001Out->tel1_fuzai_hksaki();?>" data-pattern="^[!-~]+$" autocapitalize="off" inputmode="numeric" />
						-
						<input name="tel2_fuzai_hksaki" class="w_70<?php if (isset($e) && $e->hasErrorForId('top_tel_fuzai_hksaki')) { echo ' form_error'; } ?>" type="<?php echo $inputTypeNumber; ?>" maxlength="4" placeholder="例）1234" value="<?php echo $ptu001Out->tel2_fuzai_hksaki();?>" data-pattern="^[!-~]+$" autocapitalize="off" inputmode="numeric" />
						-
						<input name="tel3_fuzai_hksaki" class="w_70<?php if (isset($e) && $e->hasErrorForId('top_tel_fuzai_hksaki')) { echo ' form_error'; } ?>" type="<?php echo $inputTypeNumber; ?>" maxlength="4" placeholder="例）5678" value="<?php echo $ptu001Out->tel3_fuzai_hksaki();?>" data-pattern="^[!-~]+$" autocapitalize="off" inputmode="numeric" />
					</dd>
				</dl>
				<dl>
					<dt id="hikitori_yotehiji" class="condition_02 even">お引取り予定日時</dt>
					<dd id="hikitori_yotehiji_date" class=" even">
						<p><?php echo $ptu001Out->frmDt();?>から<?php echo $ptu001Out->toDt();?>まで選択できます。<br/>
						</p>
						<div class="ymdwidth">
							<select id="hikitori_yotehiji_date_year_cd_sel" name="hikitori_yotehiji_date_year_cd_sel" class="hiduke todofuken optChg <?php if (isset($e) && $e->hasErrorForId('top_hikitori_yotehiji_date')) { echo ' form_error'; } ?>">
								<option value="">年を選択</option>
								<?php echo Sgmov_View_Ptu_Input::_createPulldown($ptu001Out->hikitori_yotehiji_date_year_cds(), $ptu001Out->hikitori_yotehiji_date_year_lbls(), $ptu001Out->hikitori_yotehiji_date_year_cd_sel());?>
							</select>
							年
							<select id="hikitori_yotehiji_date_month_cd_sel" name="hikitori_yotehiji_date_month_cd_sel" class="hiduke todofuken optChg <?php if (isset($e) && $e->hasErrorForId('top_hikitori_yotehiji_date')) { echo ' form_error'; } ?>">
								<option value="">月を選択</option>
								<?php echo Sgmov_View_Ptu_Input::_createPulldown($ptu001Out->hikitori_yotehiji_date_month_cds(), $ptu001Out->hikitori_yotehiji_date_month_lbls(), $ptu001Out->hikitori_yotehiji_date_month_cd_sel());?>
							</select>
							月
							<select id="hikitori_yotehiji_date_day_cd_sel" name="hikitori_yotehiji_date_day_cd_sel" class="hiduke todofuken optChg <?php if (isset($e) && $e->hasErrorForId('top_hikitori_yotehiji_date')) { echo ' form_error'; } ?>">
								<option value="">日を選択</option>
								<?php echo Sgmov_View_Ptu_Input::_createPulldown($ptu001Out->hikitori_yotehiji_date_day_cds(), $ptu001Out->hikitori_yotehiji_date_day_lbls(), $ptu001Out->hikitori_yotehiji_date_day_cd_sel());?>
							</select>
							日
						</div>
						<label class="radio-label" for="yoteji_nashi">
		                	<input checked="checked" class="radio-btn" id="yoteji_nashi" name="hikitori_yoteji_sel" type="radio" value="1" />
		                    時間帯指定なし
		                </label>
		                <br/>

		                <label class="radio-label" for="yoteji_shite">
		                    <input<?php if ($ptu001Out->hikitori_yoteji_sel() === '2') echo ' checked="checked"'; ?> class="radio-btn " id="yoteji_shite" name="hikitori_yoteji_sel" type="radio" value="2" />
		                    時間帯を指定
		                </label>
		                <select id="hikitori_yotehiji_time_cd_sel" name="hikitori_yotehiji_time_cd_sel" class="hiduke <?php if (isset($e) && $e->hasErrorForId('top_hikoshi_yotehiji_time')) { echo 'form_error'; } ?>">
							<?php echo Sgmov_View_Ptu_Input::_createPulldown($ptu001Out->hikitori_yotehiji_time_cds(), $ptu001Out->hikitori_yotehiji_time_lbls(), $ptu001Out->hikitori_yotehiji_time_cd_sel());?>
						</select><br/>
						<label class="radio-label" for="yoteji_justime">
		                    <input<?php if ($ptu001Out->hikitori_yoteji_sel() === '3') echo ' checked="checked"'; ?> class="radio-btn " id="yoteji_justime" name="hikitori_yoteji_sel" type="radio" value="3" />
		                    ジャストタイム
		                </label>
						<select id="hikitori_yotehiji_justime_cd_sel" name="hikitori_yotehiji_justime_cd_sel" class="hiduke <?php if (isset($e) && $e->hasErrorForId('top_hikoshi_yotehiji_time')) { echo 'form_error'; } ?>">
							<?php echo Sgmov_View_Ptu_Input::_createPulldown($ptu001Out->hikitori_yotehiji_justime_cds(), $ptu001Out->hikitori_yotehiji_justime_lbls(), $ptu001Out->hikitori_yotehiji_justime_cd_sel());?>
						</select>
					</dd>
				</dl>
				<dl>
					<dt id="hikoshi_yotehiji" class="condition_02 ">お引越し予定日時</dt>
					<dd id="hikoshi_yotehiji_date" class="">
						<p><?php echo $ptu001Out->frmDt();?>から<?php echo $ptu001Out->toDt();?>まで選択できます。<br/>
						</p>
						<div class="ymdwidth">
							<select id="hikoshi_yotehiji_date_year_cd_sel" name="hikoshi_yotehiji_date_year_cd_sel" class="hiduke <?php if (isset($e) && $e->hasErrorForId('top_hikoshi_yotehiji_date')) { echo ' form_error'; } ?>">
								<option value="">年を選択</option>
								<?php echo Sgmov_View_Ptu_Input::_createPulldown($ptu001Out->hikoshi_yotehiji_date_year_cds(), $ptu001Out->hikoshi_yotehiji_date_year_lbls(), $ptu001Out->hikoshi_yotehiji_date_year_cd_sel());?>
							</select>
							年
							<select id="hikoshi_yotehiji_date_month_cd_sel" name="hikoshi_yotehiji_date_month_cd_sel" class="hiduke <?php if (isset($e) && $e->hasErrorForId('top_hikoshi_yotehiji_date')) { echo ' form_error'; } ?>">
								<option value="">月を選択</option>
								<?php echo Sgmov_View_Ptu_Input::_createPulldown($ptu001Out->hikoshi_yotehiji_date_month_cds(), $ptu001Out->hikoshi_yotehiji_date_month_lbls(), $ptu001Out->hikoshi_yotehiji_date_month_cd_sel());?>
							</select>
							月
							<select id="hikoshi_yotehiji_date_day_cd_sel" name="hikoshi_yotehiji_date_day_cd_sel" class="hiduke <?php if (isset($e) && $e->hasErrorForId('top_hikoshi_yotehiji_date')) { echo ' form_error'; } ?>">
								<option value="">日を選択</option>
								<?php echo Sgmov_View_Ptu_Input::_createPulldown($ptu001Out->hikoshi_yotehiji_date_day_cds(), $ptu001Out->hikoshi_yotehiji_date_day_lbls(), $ptu001Out->hikoshi_yotehiji_date_day_cd_sel());?>
							</select>
							日
						</div>
						<label class="radio-label" for="hikoshi_yoteji_nashi">
		                    <input checked="checked" class="radio-btn" id="hikoshi_yoteji_nashi" name="hikoshi_yoteji_sel" type="radio" value="1" />
		                    時間帯指定なし
		                </label><br/>

		                <label class="radio-label" for="hikoshi_yoteji_shite">
		                    <input<?php if ($ptu001Out->hikoshi_yoteji_sel() === '2') echo ' checked="checked"'; ?> class="radio-btn" id="hikoshi_yoteji_shite" name="hikoshi_yoteji_sel" type="radio" value="2" />
		                    時間帯を指定
		                </label>
						<select id="hikoshi_yotehiji_time_cd_sel" name="hikoshi_yotehiji_time_cd_sel" class="hiduke <?php if (isset($e) && $e->hasErrorForId('top_hikoshi_yotehiji_time')) { echo 'form_error'; } ?>">
							<?php echo Sgmov_View_Ptu_Input::_createPulldown($ptu001Out->hikoshi_yotehiji_time_cds(), $ptu001Out->hikoshi_yotehiji_time_lbls(), $ptu001Out->hikoshi_yotehiji_time_cd_sel());?>
						</select>
						<br/>
						<label class="radio-label" for="hikoshi_yoteji_justime">
		                    <input<?php if ($ptu001Out->hikoshi_yoteji_sel() === '3') echo ' checked="checked"'; ?> class="radio-btn " id="hikoshi_yoteji_justime" name="hikoshi_yoteji_sel" type="radio" value="3" />
		                    ジャストタイム
		                </label>
						<select id="hikoshi_yotehiji_justime_cd_sel" name="hikoshi_yotehiji_justime_cd_sel" class="hiduke <?php if (isset($e) && $e->hasErrorForId('top_hikoshi_yotehiji_time')) { echo 'form_error'; } ?>">
							<?php echo Sgmov_View_Ptu_Input::_createPulldown($ptu001Out->hikoshi_yotehiji_justime_cds(), $ptu001Out->hikoshi_yotehiji_justime_lbls(), $ptu001Out->hikoshi_yotehiji_justime_cd_sel());?>
						</select>
					</dd>
				</dl>
				<dl>
					<dt class="even"><?php echo $komoku; ?></dt>
					<dd class="even">
					<?php if ($binshu == '906') { ?>
						<select id="tanhin_cd_sel" name="tanhin_cd_sel" class="tanhinChange <?php if (isset($e) && $e->hasErrorForId('top_tanhin_cd_sel')) { echo ' form_error'; } ?>" >
							<option value="">品目を選択</option>
							<?php echo Sgmov_View_Ptu_Input::_createPulldown($ptu001Out->tanhin_cds(), $ptu001Out->tanhin_lbls(), $ptu001Out->tanhin_cd_sel());?>
						</select>
					<?php } else { ?>
						<input id="cago_daisu" name="cago_daisu" class="w_70 cagoChange <?php if (isset($e) && $e->hasErrorForId('top_cago_daisu')) { echo ' form_error'; } ?>" type="<?php echo $inputTypeNumber; ?>" maxlength="3" data-pattern="^[!-~]+$" value="<?php echo $ptu001Out->cago_daisu();?>" />台
					<?php } ?>
					</dd>
				</dl>
				<dl>
					<dt class="">基本料金（税抜）</dt>
					<dd class=""> <span class="money" id="kihonKin"> </span>
						<input type="hidden" value="" name="hidden_kihonKin" id="hidden_kihonKin" />
					</dd>
				</dl>
			</div>
		</div>
			<!--▲お引越し先のお住まいについて　ここまで-->
			<!--▼オプションリスト　ここから-->
		<div class="section">
			<h4 class="cont_inner_title">オプション</h4>
			<div class="ul_block">
				<!--▽オプション　お引越し時　ここから-->
				<ul class="option_ul">
		<li class="ttl_cell">お引取り時</li>
		<li class="item_cell">
			<span class="name">項目</span>
			<span class="work">作業区分</span>
			<span class="fees">単価（税抜）</span>
			<span class="slct">選択</span>
			<span class="cmmt">備考</span>
		</li>
<?php
        $contact_hanshutsu_cds = $ptu001Out->hanshutsu_cds();
        $contact_hanshutsu_komoku_names = $ptu001Out->hanshutsu_komoku_names();
        $contact_hanshutsu_sagyo_names = $ptu001Out->hanshutsu_sagyo_names();
        $contact_hanshutsu_tankas = $ptu001Out->hanshutsu_tankas();
        $contact_hanshutsu_input_kbns = $ptu001Out->hanshutsu_input_kbns();
        $contact_hanshutsu_bikos = $ptu001Out->hanshutsu_bikos();

        $text_hanshutsu = $ptu001Out->textHanshutsu();
        $checkbox_hanshutsu = $ptu001Out->checkboxHanshutsu();
        $y = 0;
        for ($i = 0; $i < count($contact_hanshutsu_cds); $i++) {
            $cd = $contact_hanshutsu_cds[$i];
            $komoku_name = $contact_hanshutsu_komoku_names[$i];
            $sagyo_name = $contact_hanshutsu_sagyo_names[$i];
            $tanka = $contact_hanshutsu_tankas[$i];
            $kbn = $contact_hanshutsu_input_kbns[$i];
            $biko = $contact_hanshutsu_bikos[$i];

            $chkShow = '';
            if ($cd == '002') {
            	$chkShow = 'chkHikitoriTime';
            }
            if ($cd == '003') {
            	$chkShow = 'chkHikitoriJustTime';
            }

            $chkOptText = '';
            if (isset($e) && $e->hasErrorForId('top_textHanshutsu')) {
            	$chkOptText = 'form_error';
            }

            echo '<li class="hanshutsuOpt">';
            echo '<span class="name">'.$komoku_name.'</span>';
            echo '<span class="work">'.$sagyo_name.'</span>';
            echo '<span id="hanshutsuTanka" class="money fees">'.$tanka.'</span>';
            if ($kbn === '2') {
            	$val = '';
            	if (!empty($text_hanshutsu)) {
            		$val = $text_hanshutsu[$y];
            	}
            	echo '<span class="slct"><input class="outOpt textHst '.$chkOptText.'" name="textHanshutsu[]" type="'.$inputTypeNumber.'" id="textHanshutsu_'.$cd.'" size="3" maxlength="3" value="'.$val.'"></span>';
            	$y++;
            } else {
            	$checked = '';
            	if (!empty($checkbox_hanshutsu)) {
            		if (in_array($cd, $checkbox_hanshutsu)) {
            			$checked = 'checked';
            		}
            	}
            	echo '<span class="slct"><label><input class="outOpt chkHst '.$chkShow.'" type="checkbox" name="checkboxHanshutsu[]" value="'.$cd.'" '.$checked.' id="checkboxHanshutsu_'.$cd.'"></label></span>';
            }
            echo '<span class="cmmt">'.$biko.'</span>';
            echo '<input type="hidden" id="hd_HANSHUTSU_kbn" name="hd_HANSHUTSU_kbn" value="'.$kbn.'" />';
            echo '<input type="hidden" id="hd_HANSHUTSU_cd" name="hd_HANSHUTSU_cd" value="'.$cd.'" />';
            echo '</li>';
        }
?>
		<li class="sum">
			<span class="name">お引取り合計（税抜）</span>
			<span class="total" id="hanshutsuSum"></span>
			<input type="hidden" id="hidden_hanshutsuSum" name="hidden_hanshutsuSum" value="" />
		</li>
	</ul>
				<!--△オプション　お引取り時　ここまで-->
				<!--▽オプション　お引越し時　ここから-->
				<ul class="option_ul">
		<li class="ttl_cell">お引越し時</li>
		<li class="item_cell">
			<span class="name">項目</span>
			<span class="work">作業区分</span>
			<span class="fees">単価（税抜）</span>
			<span class="slct">選択</span>
			<span class="cmmt">備考</span>
		</li>
<?php
        $contact_hannyu_cds = $ptu001Out->hannyu_cds();
        $contact_hannyu_komoku_names = $ptu001Out->hannyu_komoku_names();
        $contact_hannyu_sagyo_names = $ptu001Out->hannyu_sagyo_names();
        $contact_hannyu_tankas = $ptu001Out->hannyu_tankas();
        $contact_hannyu_input_kbns = $ptu001Out->hannyu_input_kbns();
        $contact_hannyu_bikos = $ptu001Out->hannyu_bikos();

        $text_hannyu = $ptu001Out->textHannyu();
        $checkbox_hannyu = $ptu001Out->checkboxHannyu();
        $y = 0;
        for ($i = 0; $i < count($contact_hannyu_cds); $i++) {
            $cd = $contact_hannyu_cds[$i];
            $komoku_name = $contact_hannyu_komoku_names[$i];
            $sagyo_name = $contact_hannyu_sagyo_names[$i];
            $tanka = $contact_hannyu_tankas[$i];
            $kbn = $contact_hannyu_input_kbns[$i];
            $biko = $contact_hannyu_bikos[$i];

            $chkShowHanyu = '';
            if ($cd == '015') {
            	$chkShowHanyu = 'chkHikoshiTime';
            }
            if ($cd == '016') {
            	$chkShowHanyu = 'chkHikoshiJustTime';
            }

            $chkText = '';
            if (isset($e) && $e->hasErrorForId('top_textHannyu')) {
            	$chkText = 'form_error';
            }

            echo '<li class="hannyuOpt">';
            echo '<span class="name">'.$komoku_name.'</span>';
            echo '<span class="work">'.$sagyo_name.'</span>';
            echo '<span id="hannyuTanka" class="fees money">'.$tanka.'</span>';
            if ($kbn === '2') {
            	$val = '';
            	if (!empty($text_hannyu)) {
            		$val = $text_hannyu[$y];
            	}
            	echo '<span class="slct"><input class="inOpt textHyu '.$chkText.'" name="textHannyu[]" type="'.$inputTypeNumber.'" id="textHannyu_'.$cd.'" size="3" maxlength="3" value="'.$val.'"></span>';
            	$y++;
            } else {
            	$checked1 = '';
            	if (!empty($checkbox_hannyu)) {
            		if (in_array($cd, $checkbox_hannyu)) {
            			$checked1 = 'checked';
            		}
            	}
            	echo '<span class="slct"><label><input class="inOpt chkHyu '.$chkShowHanyu.'" type="checkbox" name="checkboxHannyu[]" value="'.$cd.'" '.$checked1.' id="CheckboxHannyu_'.$cd.'"></label></span>';
            }
            echo '<span class="cmmt">'.$biko.'</span>';
            echo '<input type="hidden" id="hd_HANNYU_kbn" name="hd_HANNYU_kbn" value="'.$kbn.'" />';
            echo '<input type="hidden" id="hd_HANNYU_cd" name="hd_HANNYU_cd" value="'.$cd.'" />';
            echo '</li>';
        }
?>
		<li class="sum">
			<span class="name">お引越し合計（税抜）</span>
			<span class="total" id="hannyuSum"></span>
			<input type="hidden" id="hidden_hannyuSum" name="hidden_hannyuSum" value="" />
		</li>
	</ul>
	<!--△オプション　お引越し時　ここまで-->
			</div>
			<div class="result">
		<table id="kingaku" class="result_tbl">
			<tbody><tr>
				<td>お見積り（税抜）</td><td id="mitumoriZeinuki" class="right money"> </td>
				<input type="hidden" id="hidden_mitumoriZeinuki" name="hidden_mitumoriZeinuki" value="" />
			</tr>
			<tr>
				<td>消費税</td><td id="zeiKin" class="right money"> </td>
				<input type="hidden" id="hidden_zeiKin" name="hidden_zeiKin" value="" />
			</tr>
			<tr>
				<td>お見積り（税込）</td><td id="mitumoriZeikomi" class="right money"> </td>
				<input type="hidden" id="hidden_mitumoriZeikomi" name="hidden_mitumoriZeikomi" value="" />
			</tr>
		</tbody></table>
		</div>
		</div>
			<!--▲オプションリスト　ここまで-->

		<!--▼往復便ご利用のお客様の復路発送ここから-->
		<div class="gray_block"> <strong>往復便ご利用のお客様の復路発送</strong>
			<ul class="disc_ul">
				<li>往復便ご利用のお客様は、下船日当日にターミナルで受付いたします。復路用伝票のご記入は不要ですので、お客様のお名前をターミナル内SGムービング受付カウンター係員にお申し付けください。</li>
				<li>お荷物が増えた場合は下船日当日の追加お申し込みも承ります。ターミナル内SGムービング受付カウンターへお越しください。伝票をお渡しいたします。追加分の配送代金は現金でお支払いください。</li>
			</ul>
			<strong>片道便(復路)のお申し込み</strong>
			<ul class="disc_ul">
				<li>復路便のお申し込みも事前にインターネットでお申し込みされると港での面倒な手続きを省略できます(下船日当日のターミナルでの受付はかなりの混雑が予想されます)。復路便のみお申し込みのお客様の伝票は、下船後にターミナルのSGムービング受付カウンターでお渡しいたします。</li>
				<li> 事前のお申し込みがなく復路便のみのご利用は、下船日当日にターミナルで受付いたします。<br />
					事前にお申し込みされていないお客様の伝票は受付カウンターにご用意しております。配送代金は受付時に現金でお支払いください。 </li>
			</ul>
		</div>
		<!--▲往復便ご利用のお客様の復路発送ここまで-->
		<!--▼お支払い方法-->
		<div class="payment_method clearfix"> <span>ご希望のお支払い方法をお選びください。</span>
			<label class="radio-label" for="pay_card">
				<input<?php if ($ptu001Out->payment_method_cd_sel() !== '1') echo ' checked="checked"'; ?> class="radio-btn" id="pay_card" name="payment_method_cd_sel" type="radio" value="2" />
				クレジットカード </label>
			<label class="radio-label" for="pay_convenience_store">
				<input<?php if ($ptu001Out->payment_method_cd_sel() === '1') echo ' checked="checked"'; ?> class="radio-btn" id="pay_convenience_store" name="payment_method_cd_sel" type="radio" value="1" />
				コンビニ決済 </label>
			<div id="convenience" style="display:none;">
				<select<?php if (isset($e) && $e->hasErrorForId('top_convenience_store_cd_sel')) { echo ' class="form_error"'; } ?> id="convenience_store_cd_sel" name="convenience_store_cd_sel">
                        <option value="">コンビニを選択してください</option>
<?php
        echo Sgmov_View_Ptu_Input::_createPulldown($ptu001Out->convenience_store_cds(), $ptu001Out->convenience_store_lbls(), $ptu001Out->convenience_store_cd_sel());
?>
                            </select>
                        </div>
                    </div>
                    <!--▲お支払い方法-->

                    <div class="sectino attention_area">

                        <!--▼ご連絡メールが届かない場合ここから-->
                        <div id="bounce_mail" class="accordion">
                            <h3 class="accordion_button">ご連絡メールが届かない場合</h3>
                            <div id="bounce_mail_contents" class="ac_content">
                                <p class="sentence">
                                    お申込み受付後、ご登録いただいたメールアドレスに「sgmoving_system@sagawa-mov.co.jp」より、自動で「旅客手荷物受付サービスのお申し込み受付のご連絡」のメールをお送りしております。
                                    <br />メールが届かない原因として、以下のことが考えられます。
                                </p>
                                <h4 class="ttl">入力されたメールアドレスを確認してください。</h4>
                                <p class="sentence">
                                    メールアドレスに入力されたメールアドレスに間違いがないか、ご利用可能なメールアドレスかをご確認ください。
                                </p>
                                <h4 class="ttl">メール受信制限設定を確認してください。</h4>
                                <p class="sentence">
                                    スマートフォンや携帯電話のメール設定でドメイン指定受信をされているお客さまは、受信できない場合がございますので、必ず「sagawa-mov.co.jp」を受信する設定にしてください。
                                    <br />ドメイン指定受信の設定に付きましては、以下の通りに設定してください。
                                </p>
                                <ul>
                                    <li class="btm30">
                                        <h5 class="ttl">【スマートフォンの設定方法】</h5>
                                        <p class="sentence">
                                            各キャリアのWEBサイトをご確認ください。
                                        </p>
                                        <p class="text_link">
                                            <a href="https://www.nttdocomo.co.jp/info/spam_mail/measure/domain/index.html" target="_blank">DoCoMo 受信／拒否設定</a>
                                        </p>
                                        </p>
                                        <p class="text_link">
                                            <a href="http://www.au.kddi.com/support/mobile/trouble/forestalling/mail/anti-spam/fillter/" target="_blank">au 迷惑メールフィルター機能</a>
                                        </p>
                                        </p>
                                        <p class="text_link">
                                            <a href="http://www.softbank.jp/mobile/support/antispam/settings/indivisual/whiteblack/" target="_blank">ソフトバンク 受信許可・拒否設定</a>
                                        </p>
                                    </li>

                                    <li>
                                        <h5 class="ttl">【携帯電話の設定方法】</h5>
                                        <ul>
                                            <li>
                                                <h6>【DoCoMo】</h6>
                                                <p class="sentence">
                                                    お手持ちの携帯からｉｍｏｄｅのトップページ（ｉMENU）にアクセス
                                                    <br />&#8594;料金＞お申し込み＞設定
                                                    <br />&#8594;ｉモード設定（オプション設定）
                                                    <br />&#8594;メール設定
                                                    <br />&#8594;迷惑メール対策
                                                    <br />&#8594;受信＞拒否設定
                                                    <br />&#8594;ステップ3、4で「sagawa-mov.co.jp」を入れてください。
                                                    <br />「かんたん設定」を行うと届かなくなる可能性があります。
                                                </p>
                                            </li>

                                            <li>
                                                <h6>【au】</h6>
                                                <p class="sentence">
                                                    お手持ちの携帯でメールフィルターを呼び出し
                                                    <br />&#8594;指定受信リスト設定
                                                    <br />&#8594;「sagawa-mov.co.jp」を受信可能に設定してください。
                                                    <br />「ＵＲＬ付メール受信拒否設定」「ＨＴＭＬメール受信拒否設定」につきましても合わせてご確認ください。
                                                </p>
                                            </li>

                                            <li>
                                                <h6>【softbank】</h6>
                                                <p class="sentence">
                                                    お手持ちの携帯からメニューリストにアクセス
                                                    <br />&#8594;My Softbank
                                                    <br />&#8594;各種変更手続き
                                                    <br />&#8594;オリジナルメール設定で「sagawa-mov.co.jp」を受信可能ドメインに設定してください。
                                                    <br />「ＵＲＬ付きリンク付きメール拒否設定」につきましても合わせてご確認ください。
                                                </p>
                                            </li>
                                        </ul>
                                    </li>

                                    <li>
                                        <h5 class="ttl">【Yahoo！メールの設定方法】</h5>
                                        <ul class="btm30">
                                            <li>
                                                Yahoo!メールトップページにアクセス
                                                <br />&#8594;トップページ右上の[メールオプション]をクリック
                                                <br />&#8594;表示されるページで[なりすましメール拒否設定]をクリック
                                                <br />&#8594;「リストに追加」欄で、「sagawa-mov.co.jp」を入力
                                                <br />&#8594;[リストに追加]ボタンをクリックしてください。
                                            </li>
                                        </ul>
                                    </li>
                                </ul>

                                <h4 class="ttl">迷惑メールフォルダ等に移動していないかを確認してください。</h4>
                                <p class="sentence">
                                    メールソフトやウィルス対策ソフトのフィルタ設定、プロバイダの迷惑メール対策等により、迷惑メールと判定されている可能性があります。
                                    <br />迷惑メールフォルダ等にお申し込み受付のご連絡メールが移動していないかご確認ください。
                                    <br />機能や設定方法、対策等につきましては、各社ホームページ等でご確認ください。
                                </p>

                                <h4 class="ttl">URLを含む電子メールが受信拒否になっていないか確認してください。</h4>
                                <p class="sentence btm30">
                                    本文にＵＲＬを含むメールを受信しない設定をされている場合、お申込み受付のご連絡メールを受信できない場合があります。
                                    <br />設定・解除方法等につきましては、ご利用の端末販売会社のホームページ等でご確認ください。
                                </p>
                            </div>
                        </div>
                        <!--▲ご連絡メールが届かない場合ここまで-->
                        <!--▼個人情報の取り扱いここから-->
                        <div id="privacy_policy" class="accordion">
                            <h3 class="accordion_button">個人情報の取り扱い</h3>
                            <div id="privacy_contents" class="ac_content">
                                <p class="sentence">
                                    SGムービング株式会社（以下「当社」）は、以下の方針に基づき、個人情報保護の管理・運用を行っております。
                                    <br />必ずお読みください。
                                    <br />本サイトにおいて個人情報をご提供頂いた場合、当社の個人情報の取り扱いに関しご同意いただいたものといたします。
                                </p>
                                <h4 class="ttl">個人情報の取扱について</h4>
                                <ol>
                                    <li>
                                        <h3>個人情報の取扱の基本方針</h3>
                                        <p>ご入力いただいた個人情報は、当社が定める「個人情報保護方針」に従い、適切な保護措置を講じ、厳重に管理いたします。</p>
                                    </li>
                                    <li>
                                        <h3>当社が保有する個人情報の利用目的</h3>
                                        <p>ご入力いただいた個人情報は、以下の目的のみ利用致します。</p>
                                        <ul>
                                            <li>お引越しの見積作成およびお引越し作業を行うため。</li>
                                            <li>お引越しに付帯する作業およびサービスを行うため。</li>
                                            <li>お客様などへの報告や必要な処理を行うため。</li>
                                            <li>お客様からの各種お問い合わせや資料請求などにご対応するため。</li>
                                        </ul>
                                        <p>上記以外の目的で個人情報を利用する場合は、改めて目的をお知らせし、同意を得るものと致します。</p>
                                    </li>
                                    <li>
                                        <h3>個人情報の第三者提供について</h3>
                                        <p>ご提供いただいた個人情報は、ご本人のご同意なしに第三者への提供は致しません。但し、法令に基づき、国の機関または地方公共団体等より法的義務を伴う協力要請を受けた場合には、例外的にご本人の同意なく関連機関等に提供する場合がございます。</p>
                                    </li>
                                    <li>
                                        <h3>個人情報の取扱いの委託について</h3>
                                        <p>ご提供いただいた個人情報はご本人の同意なしに委託することはありません。委託する場合は、当社が一定の選定基準に基づき選定した委託先と契約を取り交わした上で、適切に行います。</p>
                                    </li>
                                    <li>
                                        <h3>個人情報提供の任意性</h3>
                                        <p>当社が必要とする個人情報をご提供頂くことは任意です。ただし、個人情報を提供いただけない場合は、当社の各種サービスのご提供が行えなくなるなどの支障がでる恐れがあります。</p>
                                    </li>
                                    <li>
                                        <h3>当社の個人情報保護管理者</h3>
                                        <p>個人情報保護管理者：営業部　部長</p>
                                    </li>
                                    <li>
                                        <h3>個人情報に関する苦情、相談、開示等の求め先について</h3>
                                        <p>ご自身の個人情報について、苦情、相談、利用目的の通知、開示、内容の訂正、追加又は削除、利用の停止、消去　及び第三者への提供の停止を請求する権利があり、当社は合理的な範囲で対応致します。これらの権利行使を行う場合は、下記の窓口にて受付を致します。</p>
                                    </li>
                                </ol>
                                <p id="contact" class="sentence">
                                    ≪お問い合わせ窓口≫
                                    <br />
                                    <span>所在地</span>：東京都江東区新砂3-2-9　Xフロンティア　EAST 6階
                                    <br />
                                    <span>名称</span>：SGムービング株式会社 &#160; 本社 &#160; 管理部
                                    <br />
                                    <span>連絡先</span>：03-5857-2457
                                </p>
                            </div>
                        </div>
                        <!--▲個人情報の取り扱いここまで-->
                        <!--▼特定商取引法に基づく表記ここから-->
                        <div id="transactions" class="accordion">
                            <h3 class="accordion_button">特定商取引法に基づく表記</h3>
                            <div id="transactions_contents" class="ac_content">
                                <dl>
                                    <dt>販売業者：</dt>
                                    <dd>SGムービング株式会社</dd>
                                </dl>
                                <dl>
                                    <dt>運営統括責任者：</dt>
                                    <dd>柏本 浩靖</dd>
                                </dl>
                                <dl>
                                    <dt>住所：</dt>
                                    <dd>
                                        東京都江東区新砂3-2-9　Xフロンティア　EAST 6階
                                        <br />電話番号：0120-35-4192
                                    </dd>
                                </dl>
                                <dl>
                                    <dt>配送料金以外の必要料金：</dt>
                                    <dd>天候などによりお客様の乗下船地が変更された場合は、SGムービングにてお荷物の移動を行う際の費用を別途請求させていただく場合がございます。</dd>
                                </dl>
                                <dl>
                                    <dt>お支払方法：</dt>
                                    <dd>
                                        &#9312;クレジットカード支払い（一括支払いのみ）
                                        <br />&#9313;コンビニエンスストア支払い
                                    </dd>
                                </dl>
                                <dl>
                                    <dt>申込の有効期限：</dt>
                                    <dd>
                                        <!--▼※PC版　有効期限一覧表-->
                                        <table class="pc_only spTabH">
                                            <tr>
                                                <th rowspan="2" scope="col">&nbsp;</th>
                                                <th rowspan="2" scope="col">
                                                    集荷ご依頼
                                                    <br />受付開始日
                                                </th>
                                                <th scope="col">
                                                    クレジット
                                                    <br />カード払い
                                                </th>
                                                <th colspan="2" scope="col">コンビニ払い</th>
                                                <th colspan="2" scope="col">手荷物集荷</th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    集荷ご依頼
                                                    <br />受付終了日
                                                </td>
                                                <td>
                                                    集荷ご依頼
                                                    <br />受付終了日
                                                </td>
                                                <td>お支払い期限</td>
                                                <td>開始日</td>
                                                <td>終了日</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    インターネット
                                                    <br />申し込み
                                                </th>
                                                <td>※1</td>
                                                <td>
                                                    ご乗船日の
                                                    <br />7日前
                                                </td>
                                                <td>
                                                    ご乗船日の
                                                    <br />10日前
                                                </td>
                                                <td>
                                                    ご乗船日の
                                                    <br />10日前
                                                </td>
                                                <td>
                                                    ご乗船日の
                                                    <br />11日前
                                                </td>
                                                <td>
                                                    ご乗船日の
                                                    <br />5日前
                                                </td>
                                            </tr>
                                        </table>
                                        <!--▲※PC版　有効期限一覧表-->
                                        <!--▼※スマホ版　有効期限一覧表-->
                                        <div class="sp_only pcH">
                                            <h4 class="ttl">インターネット申し込みの場合</h4>
                                            <ul>
                                                <li>
                                                    <strong>集荷ご依頼受付開始日</strong>
                                                    <p>集荷ご依頼受付開始日は乗船日により異なります。ガイドブックなどをご確認の上お申し込みください。</p>
                                                </li>
                                                <li>
                                                    <strong>集荷ご依頼受付終了日</strong>
                                                    <p>
                                                        クレジットカード払いの場合：ご乗船日の7日前
                                                        <br />コンビニ払いの場合：ご乗船日の10日前
                                                        <br />コンビニ払いの場合のお支払い期限：ご乗船日の10日前
                                                    </p>
                                                </li>
                                                <li>
                                                    <strong>手荷物集荷</strong>
                                                    <p>
                                                        開始日：ご乗船日の11日前
                                                        <br />終了日：ご乗船日の5日前
                                                    </p>
                                                </li>
                                            </ul>
                                        </div>
                                        <!--▲※スマホ版　有効期限一覧表-->
                                        <p class="pc_only">※1 お申し込み受付期間は乗船日により異なります。ガイドブックなどをご確認の上お申し込みください。</p>
                                        <p>※ お申し込み期間終了後のお申し込みはできませんのでご注意ください。</p>
                                        <p>※ コンビニ払いをご希望の場合、クレジットカード払いに比べご依頼受付終了日が早くなります。コンビニ払いの受付期間を過ぎた場合はクレジットカードでのお支払いのみとなりますので予めご了承ください。</p>
                                        <p><strong class="red">※ コンビニ払いで入金確認が取れない場合は集荷にお伺いしませんのでご注意ください。お支払いは期日までにお願いいたします。</strong></p>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt>お荷物の破損について：</dt>
                                    <dd>
                                        <p>下船時、ターミナル内で引き取ったお荷物に破損(劣化による破損は除く)などがありましたら、通関前にターミナル内に待機しているクルーズスタッフにお申し出ください。</p>
                                        <p>
                                            お帰り後、ご自宅で荷物の破損などを確認した場合は、
                                            <br />
                                            <strong>03-5534-1411(SGムービング)</strong>へご連絡ください。
                                        </p>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt>お荷物の集荷：</dt>
                                    <dd>ご入力いただいた情報をもとに本サービスでお荷物の配送業務を担当します佐川急便が伝票を作成し、集荷の際にお客様の荷物に貼付し集荷いたします。</dd>
                                </dl>
                                <dl>
                                    <dt>キャンセル：</dt>
                                    <dd>
                                        旅客手荷物受付サービスをキャンセルされたい場合は、お手数ですがお客様ご自身でSGムービングにご連絡頂き、キャンセルをお申し出ください。
                                        <br />(往復でお申し込みされているお客様が、お預かり後キャンセルされた場合は、復路代金で返送する為、全額ご返金はございません)
                                        <br />(往路しかお申し込みされていないお客様が、お預かり後キャンセルされた場合は、返送代金をご請求させて頂きます)
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        <!--▲特定商取引法に基づく表記ここまで-->
                    </div>
                    <p class="sentence">
                        <strong class="red">※迷惑メールを設定されている方は「sagawa-mov.co.jp」を受信する設定にしてください。
                        <br />詳しくは「<a href="#bounce_mail">ご連絡メールが届かない場合</a>」をご確認ください。</strong>
                    </p>

                    <p class="sentence"><span class="sp_only pcH">上記「個人情報の取り扱い」および「特定商取引法に基づく表記」の</span>内容についてご同意頂ける方は、下のボタンを押してください。</p>

                    <p class="text_center">
                        <input id="submit_button" type="button" name="submit_button" value="同意して次に進む（入力内容の確認）"/>
                        <!-- <input id= "confirm_btn" name="confirm_btn" type="button" value="同意して次に進む（入力内容の確認）" /> -->
                    </p>
                </form>
            </div>
        </div>
    </div>
    <!--main-->

<?php
    $footerSettings = 'under';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/footer.php';
?>
</body>
</html>
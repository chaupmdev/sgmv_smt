<?php
/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('ptu/Confirm');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Ptu_Confirm();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Ptu003Out
 */
$ptu003Out = $forms['outForm'];

/**
* 便種
* @var string
*/
$binshu = $ptu003Out->binshu_cd();

    //タイトル
    if ($binshu == '906') {
        $title = '単品輸送サービスのお申し込み（入力内容確認）';
        $komoku = '単品輸送品目';
    } else {
        $title = '単身カーゴ引越しサービスのお申し込み（入力内容確認）';
        $komoku = 'カーゴ台数';
    }
?>
<!DOCTYPE html>
<html dir="ltr" lang="ja-jp" xml:lang="ja-jp">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0" />
	<meta name="Keywords" content="" />
	<meta name="Description" content="<?php echo $title; ?>のご案内です。お問い合わせ・お申し込みなど、随時お受けしております。どなたでもお気軽にお問い合わせください。" />
	<title><?php echo $title; ?>｜お問い合わせ│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
	<link rel="shortcut icon" href="/misc/favicon.ico" type="image/vnd.microsoft.icon" />
	<link href="/css/common.css" rel="stylesheet" type="text/css" />
	<link href="/css/plan.css" rel="stylesheet" type="text/css" />
	<link href="/css/pre.css" rel="stylesheet" type="text/css" />
	<link href="/css/form.css" rel="stylesheet" type="text/css" />
	<!--[if lt IE 9]>
	<link href="/css/form_ie8.css" rel="stylesheet" type="text/css" />
	<![endif]-->
	<!--[if lt IE 9]>
	<script charset="UTF-8" type="text/javascript" src="/js/jquery-1.12.0.min.js"></script>
	<script charset="UTF-8" type="text/javascript" src="/js/lodash-compat.min.js"></script>
	<![endif]-->
	<!--[if gte IE 9]><!-->
	<script charset="UTF-8" type="text/javascript" src="/js/jquery-2.2.0.min.js"></script>
	<script charset="UTF-8" type="text/javascript" src="/js/lodash.min.js"></script>
	<!--<![endif]-->
	<script charset="UTF-8" type="text/javascript" src="/js/common.js"></script>
	<script charset="UTF-8" type="text/javascript" src="/common/js/mobilyslider.js"></script>
	<script charset="UTF-8" type="text/javascript" src="/common/js/mob.js"></script>
	<script charset="UTF-8" type="text/javascript" src="/common/js/api.js"></script>
	<script charset="UTF-8" type="text/javascript" src="/common/js/ajax_searchaddress.js"></script>
	<script charset="UTF-8" type="text/javascript" src="/common/js/jquery.ah-placeholder.js"></script>
	<script charset="UTF-8" type="text/javascript" src="/common/js/jquery.autoKana.js"></script>
	<script charset="UTF-8" type="text/javascript" src="/common/js/multi_send.js"></script>
	<script charset="UTF-8" type="text/javascript" src="/ptu/js/confirm.js"></script>
    <script src="/js/ga.js" type="text/javascript"></script>
</head>

<body>
<?php
	$gnavSettings = "contact";
	include_once($_SERVER['DOCUMENT_ROOT']."/parts/header.php");
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
		<p class="sentence btm30">ご入力内容をご確認のうえ、「入力内容を送信する」ボタンを押してください。
修正する場合は「修正する」ボタンを押してください。 </p>


		<form action="" method="post">
		<!--▼お客様情報　ここから-->
		<div class="section">
			<h4 class="cont_inner_title">お客様情報</h4>
			<div class="dl_block">
				<dl>
					<dt id="name">お名前</dt>
					<dd><?php echo $ptu003Out->surname().PHP_EOL; ?>
                    <?php echo $ptu003Out->forename().PHP_EOL; ?></dd>
				</dl>
				<dl>
					<dt id="tel" class="even">電話番号</dt>
					<dd><?php echo $ptu003Out->tel(); ?></dd>
				</dl>
				<dl>
					<dt>FAX番号</dt>
                	<dd><?php echo $ptu003Out->fax(); ?>&nbsp;</dd>
				</dl>
				<dl>
					<dt id="mail" class="condition_02 even"> メールアドレス </dt>
					<dd id="mail_address"><?php echo $ptu003Out->mail(); ?></dd>
				</dl>
			</div>
		</div>
		<!--▲お客様情報　ここまで-->
		<!--▼現在のお住まいについて　ここから-->
		<div class="section">
			<h4 class="cont_inner_title">現在のお住まいについて</h4>
			<div class="dl_block">
				<dl>
					<dt id="name">郵便番号</dt>
					<dd><?php echo $ptu003Out->zip(); ?>&nbsp;</dd>
				</dl>
				<dl>
					<dt id="tel" class="even">都道府県</dt>
					<dd><?php echo $ptu003Out->pref(); ?></dd>
				</dl>
				<dl>
					<dt id="mail" class="condition_02 even">市区町村</dt>
					<dd id="mail_address"><?php echo $ptu003Out->address(); ?></dd>
				</dl>
				<dl>
					<dt id="mail" class="condition_02 even">番地・建物名</dt>
					<dd id="mail_address"><?php echo $ptu003Out->building(); ?></dd>
				</dl>
			</div>
		</div>
		<!--▲現在のお住まいについて　ここまで-->
		<!--▼お引越し先のお住まいについて　ここから-->
		<div class="section">
			<h4 class="cont_inner_title">お引越し先のお住まいについて</h4>
			<div class="dl_block">
				<dl>
					<dt id="name">お名前</dt>
					<dd><?php echo $ptu003Out->surname_hksaki().PHP_EOL; ?>
                    <?php echo $ptu003Out->forename_hksaki().PHP_EOL; ?></dd>
				</dl>
				<dl>
					<dt id="name">郵便番号</dt>
					<dd><?php echo $ptu003Out->zip_hksaki(); ?>&nbsp;</dd>
				</dl>
				<dl>
					<dt id="tel" class="even">都道府県</dt>
					<dd><?php echo $ptu003Out->pref_cd_sel_hksaki(); ?></dd>
				</dl>
				<dl>
					<dt id="mail" class="condition_02 even">市区町村</dt>
					<dd id="mail_address"><?php echo $ptu003Out->address_hksaki(); ?></dd>
				</dl>
				<dl>
					<dt id="mail" class="condition_02 even">番地・建物名</dt>
					<dd id="mail_address"><?php echo $ptu003Out->building_hksaki(); ?></dd>
				</dl>
				<dl>
					<dt id="tel" class="even">電話番号</dt>
					<dd><?php echo $ptu003Out->tel_hksaki(); ?></dd>
				</dl>
				<dl>
					<dt>不在時連絡先</dt>
                	<dd><?php echo $ptu003Out->tel_fuzai_hksaki(); ?>&nbsp;</dd>
				</dl>
				<dl>
					<dt class="even">お引取り予定日時</dt>
	                <dd class="even">
	                    <?php echo $ptu003Out->hikitori_yotehiji_date_cd_sel().PHP_EOL; ?>
	                    <?php echo $ptu003Out->hikitori_yotehiji_time_cd_sel().PHP_EOL; ?>
	                </dd>
				</dl>
				<dl>
					<dt class="">お引越し予定日時</dt>
	                <dd class="">
	                    <?php echo $ptu003Out->hikoshi_yotehiji_date_cd_sel().PHP_EOL; ?>
	                    <?php echo $ptu003Out->hikoshi_yotehiji_time_cd_sel().PHP_EOL; ?>
	                </dd>
				</dl>
				<dl>
					<dt class="even"><?php echo $komoku; ?></dt>

<?php if ($binshu == '906') { ?>
	                <dd class="even"><?php echo $ptu003Out->tanhin_cd_sel(); ?>&nbsp;</dd>
<?php } else { ?>
					<dd class="even"><?php echo $ptu003Out->cago_daisu(); ?>&nbsp;</dd>
<?php } ?>
				</dl>
				<dl>
					<dt class="">お引取りオプション</dt>
                	<dd class=""><?php echo $ptu003Out->hanshutsu_opt(); ?>&nbsp;</dd>
				</dl>
				<dl>
					<dt class="even">お引越しオプション</dt>
                	<dd class="even"><?php echo $ptu003Out->hannyu_opt(); ?>&nbsp;</dd>
				</dl>

<?php if ($ptu003Out->payment_method_cd_sel() === '1') { ?>
				<dl><dt>お見積り金額</dt>
                <dd><?php echo $ptu003Out->delivery_charge(); ?>円（税込）</dd></dl>
                <dl><dt class="even">お支払い方法</dt>
                <dd class="even">コンビニ決済</dd></dl>
                <dl><dt class="">お支払い店舗</dt>
                <dd class=""><?php echo $ptu003Out->convenience_store(); ?></dd></dl>
<?php } ?>
			</div>
		</div>
		<!--▲お引越し先のお住まいについて　ここまで-->
		<!--▼進む・戻るボタンエリア　ここから-->
<?php if ($ptu003Out->payment_method_cd_sel() === '1') { ?>
		<div class="btn_area">
			<a class="back" data-action="/ptu/input/" href="#">修正する</a><a class="next" data-action="/ptu/complete/" href="#">入力内容を送信する</a>
			<input name="ticket" type="hidden" value="<?php echo $ticket ?>" />
            <input type="hidden" id="binshu_cd" name="binshu_cd" value="<?php echo $binshu; ?>" />
		</div>
<?php } else { ?>
		<div class="btn_area">
			<a class="back" data-action="/ptu/input/" href="#">修正する</a>
		</div>
		<div class="section">
			<h4 class="cont_inner_title">お支払い情報</h4>
			<div class="dl_block">
				<dl><dt>お見積り金額</dt>
                <dd><?php echo $ptu003Out->delivery_charge(); ?>円（税込）</dd></dl>
                <dl><dt class="even">有効期限</dt>
                <dd class="even">
                    <?php echo $ptu003Out->card_expire().PHP_EOL; ?>
                </dd></dl>
                <dl><dt>カード番号</dt>
                <dd>
                    <?php echo $ptu003Out->card_number().PHP_EOL; ?>
                    <span class="f80">※下4桁のみの表示となります</span>
                </dd></dl>
                <dl><dt class="even">セキュリティコード</dt>
                <dd class="even"><?php echo $ptu003Out->security_cd(); ?></dd></dl>
                <dl><dt>お支払い方法</dt>
                <dd>1回</dd></dl>
			</div>
		</div>
		<div class="btn_area">
			<a class="back" data-action="/ptu/credit_card/" href="#">修正する</a><a class="next" data-action="/ptu/complete/" href="#">入力内容を送信する ＞</a>
			<input name="ticket" type="hidden" value="<?php echo $ticket ?>" />
            <input type="hidden" id="binshu_cd" name="binshu_cd" value="<?php echo $binshu; ?>" />
		</div>
<?php } ?>
			<!--▲進む・戻るボタンエリア　ここまで-->
		</form>
	</div>
</div>
</body>
</html>
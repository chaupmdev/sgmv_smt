<?php
/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('ptu/CreditCard');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Ptu_CreditCard();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Ptu002Out
 */
$ptu002Out = $forms['outForm'];

/**
* 便種
* @var string
*/
$binshu = $ptu002Out->binshu_cd();

/**
 * エラーフォーム
 * @var Sgmov_Form_Error
 */
$e = $forms['errorForm'];

    //タイトル
    if ($binshu == '906') {
        $title = '単品輸送プランのお申し込み';
    } else {
        $title = '単身カーゴプランのお申し込み';
    }

    // スマートフォン・タブレット判定
    $detect = new MobileDetect();
    $isSmartPhone = $detect->isMobile();
    if ($isSmartPhone) {
        $inputTypeNumber = 'number';
    } else {
        $inputTypeNumber = 'text';
    }
?>
<!DOCTYPE html>
<html dir="ltr" lang="ja-jp" xml:lang="ja-jp">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0" />
	<meta name="Keywords" content="" />
	<meta name="Description" content="引越・設置のＳＧムービング株式会社のWEBサイトです。個人の方はもちろん、法人様への実績も豊富です。無料お見積りはＳＧムービング株式会社(フリーダイヤル:0570-056-006)まで。" />
	<title><?php echo $title; ?>｜お問い合わせ│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
	<link rel="shortcut icon" href="/misc/favicon.ico" type="image/vnd.microsoft.icon" />
	<link href="/css/common.css" rel="stylesheet" type="text/css" />
	<link href="/css/plan.css" rel="stylesheet" type="text/css" />
	<link href="/css/pre.css" rel="stylesheet" type="text/css" />
	<link href="/css/form.css" rel="stylesheet" type="text/css" />
	<!--[if lt IE 9]>
	<link href="/css/form_ie8.css" rel="stylesheet" type="text/css" />
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
	<script charset="UTF-8" type="text/javascript" src="/common/js/multi_send.js"></script>
	<script charset="UTF-8" type="text/javascript" src="/common/js/ajax_searchaddress.js"></script>
	<script charset="UTF-8" type="text/javascript" src="/common/js/jquery.ah-placeholder.js"></script>
	<script charset="UTF-8" type="text/javascript" src="/common/js/jquery.autoKana.js"></script>
	<script charset="UTF-8" type="text/javascript" src="/ptu/js/credit_card.js"></script>
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
		<div class="section">
			<p class="sentence br">確定送料をご確認の上、クレジットカード情報をご入力ください。<br />
				※審査の結果、決済不可の場合、または申込(決済)後にキャンセル・金額変更があった場合等は、必ず下記問い合わせ番号にお電話をお願いいたします。<br />
				4月6日より下記SGムービングクルーズ専用ダイヤルにて、お申し込みを受付致します。<br />
				インターネットお申し込みについてのご質問については、※<a class="text_link" data-inquiry-type="11" href="https://www.sagawa-mov.co.jp/pin/" target = "_brank">こちら</a>からのみになります。</p>
			<dl class="plain_dl">
				<dt>TEL：</dt>
				<dd> <strong>0120-35-4192</strong>(固定電話から) <br />
					<strong>03-5763-9188</strong>(携帯電話から) <br />
					(土日祝祭日含む9:00～17:00)</dd>
			</dl>



			<?php if (isset($e) && $e->hasError()) { ?>
	        <section id="err_msg">
	            <p>下記の項目が正しく入力・選択されていません。</p>
	            <ul>
			<?php
			        // エラー表示
			        if ($e->hasErrorForId('top_card_expire_month_cd_sel')) {
			            echo '<li><a href="#card_expire_month">有効期限 月' . $e->getMessage('top_card_expire_month_cd_sel') . '</a></li>';
			        }
			        if ($e->hasErrorForId('top_card_expire_year_cd_sel')) {
			            echo '<li><a href="#card_expire_year">有効期限 年' . $e->getMessage('top_card_expire_year_cd_sel') . '</a></li>';
			        }
			        if ($e->hasErrorForId('top_card_expire')) {
			            echo '<li><a href="#card_expire_month">カードの有効期限' . $e->getMessage('top_card_expire') . '</a></li>';
			        }
			        if ($e->hasErrorForId('top_card_number')) {
			            echo '<li><a href="#card_number">クレジットカード番号' . $e->getMessage('top_card_number') . '</a></li>';
			        }
			        if ($e->hasErrorForId('top_security_cd')) {
			            echo '<li><a href="#security_cd">セキュリティコード' . $e->getMessage('top_security_cd') . '</a></li>';
			        }
			?>

			            </ul>
			            <p class="under">
			                インターネットでお申し込みが出来なかった場合は、4月6日より下記SGムービングクルーズ専用ダイヤルにて、お申し込みを受付致します。
			            	<br />インターネットお申し込みについてのご質問については、※<a data-inquiry-type="11" href="<?php echo Sgmov_Component_Config::getUrlPublicSsl(); ?>/pin/" target = "_brank">こちら</a>からのみになります。
			                <br />TEL：0120-35-4192(固定電話から)
			                <br />03-5763-9188(携帯電話から)
			                <br />(土日祝祭日含む9:00～17:00)
			            </p>
			</section>
			<?php } ?>

			<form action="/ptu/check_credit_card" method="post">
            <input name="ticket" type="hidden" value="<?php echo $ticket ?>" />
            <input type="hidden" id="binshu_cd" name="binshu_cd" value="<?php echo $binshu; ?>" />


			<div class="result02">
				<table id="kingaku" class="result_tbl">
					<tbody>
						<tr>
							<td nowrap>確定送料:</td>
							<td nowrap class="right money"><span><?php echo $ptu002Out->delivery_charge(); ?></span>円（税込） </td>
						</tr>
					</tbody>
				</table>
			</div>

			<h4 class="table_title">カード情報</h4>

			<div class="dl_block">

            <dl class="form_list clearfix" id="card_list">
                <dt>有効期限</dt>
                <dd>
                    <label for="card_expire_month">
                        <select<?php if (isset($e) && ($e->hasErrorForId('top_card_expire_month_cd_sel') || $e->hasErrorForId('top_card_expire'))) { echo ' class="form_error"'; } ?> id="card_expire_month" name="card_expire_month_cd_sel">
                            <option value="" selected="selected">月を選択</option>
<?php
        echo Sgmov_View_Ptu_CreditCard::_createPulldown($ptu002Out->card_expire_month_cds(), $ptu002Out->card_expire_month_lbls(), $ptu002Out->card_expire_month_cd_sel());
?>
                        </select>
                        月
                    </label>
                    <label for="card_expire_year">
                        <select<?php if (isset($e) && ($e->hasErrorForId('top_card_expire_year_cd_sel') || $e->hasErrorForId('top_card_expire'))) { echo ' class="form_error"'; } ?> id="card_expire_year" name="card_expire_year_cd_sel">
                            <option value="" selected="selected">年を選択</option>
<?php
        echo Sgmov_View_Ptu_CreditCard::_createPulldown($ptu002Out->card_expire_year_cds(), $ptu002Out->card_expire_year_lbls(), $ptu002Out->card_expire_year_cd_sel());
?>
                        </select>
                        年
                    </label>
                    <span class="f80">カードに記載されている順のまま入力してください</span>
                </dd>
			</dl>
			<dl>
                <dt class="even condition">
                    カード番号
                    <p class="f12">※半角数字・ハイフンなし</p>
                </dt>
                <dd class="even">
                    <input autocapitalize="off" class="w_280<?php if (isset($e) && $e->hasErrorForId('top_card_number')) { echo ' form_error'; } ?>" id="card_number" inputmode="numeric" maxlength="16" name="card_number" data-pattern="^\d+$" placeholder="例）9999999999999999" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $ptu002Out->card_number(); ?>" />
                </dd>
			</dl>
			<dl>
                <dt class="condition">
                    セキュリティコード
                    <p class="f12">※半角数字</p>
                </dt>
                <dd>
                    <input autocapitalize="off" class="w_60<?php if (isset($e) && $e->hasErrorForId('top_security_cd')) { echo ' form_error'; } ?>" id="security_cd" inputmode="numeric" maxlength="4" name="security_cd" data-pattern="^\d+$" placeholder="例）9999" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $ptu002Out->security_cd(); ?>" />
                    <span class="f80 instruction_security_cd">
                        セキュリティコードはカード裏面のサインパネルに表示されている数字末尾3桁です
                        <br />（アメリカンエクスプレスカードのみ4桁）
                    </span>
                </dd>
			</dl>
			<dl>
                <dt class="even">お支払い方法</dt>
                <dd class="even">
                    1回
                </dd>
            </dl>

			</div>

		<p class="text_center">
			<input id="submit_button" type="button" name="submit_button" value="内容を確認する"/>
			<!-- <input id= "confirm_btn" name="confirm_btn" type="button" value="同意して次に進む（入力内容の確認）" /> -->
		</p>

</form>
		</div>
	</div>
</div>
<!--main-->

<?php
	$footerSettings = "under";
	include_once($_SERVER['DOCUMENT_ROOT']."/parts/footer.php");
?>
</body>
</html>
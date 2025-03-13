<?php
/**
 * お問い合わせ入力画面を表示します。
 * @package    ssl_html
 * @subpackage PIN
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */

require_once dirname(__FILE__) . '/../../../lib/Lib.php';
Sgmov_Lib::useView('pin/Input');
/**#@-*/
// 処理を実行
$view = new Sgmov_View_Pin_Input();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Pin001Out
 */
$pin001Out = $forms['outForm'];
/**
 * エラーフォーム
 * @var Sgmov_Form_Error
 */
$e = $forms['errorForm'];
?>
<!DOCTYPE html>
<html dir="ltr" lang="ja-jp" xml:lang="ja-jp">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0">
    <meta http-equiv="content-style-type" content="text/css">
    <meta http-equiv="content-script-type" content="text/javascript">
    <meta name="Description" content="一般的なお問い合わせページです。">
    <meta name="author" content="SG MOVING Co.,Ltd">
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved.">
    <meta property="og:locale" content="ja_JP">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.sagawa-mov.co.jp/pin/input/">
    <meta property="og:site_name" content="<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl-ogp.php'); ?>">
    <meta property="og:image" content="/img/ogp/og_image_sgmv.jpg">
    <meta property="twitter:card" content="summary_large_image">
    <link rel="shortcut icon" href="/img/common/favicon.ico">
    <link rel="apple-touch-icon" sizes="192x192" href="/img/ogp/touch-icon.png">
    <title>一般的なお問い合わせ｜お問い合わせ｜<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl.php'); ?></title>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_style.php'); ?>
    <link href="/css/form/common.css" rel="stylesheet" type="text/css">
    <link href="/css/form/form.css" rel="stylesheet" type="text/css">
    <!--[if lt IE 9]>
    <link href="/css/form_ie8.css" rel="stylesheet" type="text/css">
    <![endif]-->
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_script.php'); ?>
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/analytics_head.php'); ?>
    <script src="/js/form/ga.js" type="text/javascript"></script>
</head>
<body>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/analytics_body.php'); ?>
<!-- ヘッダStart ************************************************ -->
<div id="container">
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/header.php'); ?>
<!-- ヘッダEnd ************************************************ -->
    <div id="main">
		<div class="pageTitle style01">
			<div class="comBox">
				<h1 class="topLead">一般的なお問い合わせ<em>Inquiry</em></h1>
				<ul id="pagePath">
					<li><a href="/"><img class="home" src="/img/common/icon54.jpg" alt=""></a></li>
					<li><a href="/contact/">お問い合わせ</a></li>
					<li>一般的なお問い合わせ</li>
				</ul>
			</div>
		</div>
        <div class="wrap clearfix">
<!--            <p class="sentence">
                午前9時から午後5時までに拝見しております。
                <br />お問い合わせフォームからのお問い合わせにつきましては、お返事が数日かかる場合がございますので、あらかじめご了承ください。 特にお急ぎのお客様は、恐れ入りますがフリーコール 0570-056-006(受付時間:9時～17時)までお電話にてお問い合わせください。
            </p>-->
<?php if (isset($e) && $e->hasError()) { ?>
            <div class="err_msg" id="error">
                <p class="sentence br attention">[!ご確認ください]下記の項目が正しく入力・選択されていません。</p>
                <ul>
<?php
        // エラー表示
        if ($e->hasErrorForId('top_inquiry_type_cd_sel')) {
            echo '<li><a href="#cate">種類' . $e->getMessage('top_inquiry_type_cd_sel') . '</a></li>';
        }
        if ($e->hasErrorForId('top_need_reply_cd_sel')) {
            echo '<li><a href="#need_reply_cd_sel">ＳＧムービングからの回答' . $e->getMessage('top_need_reply_cd_sel') . '</a></li>';
        }
        if ($e->hasErrorForId('top_company_name')) {
            echo '<li><a href="#name">会社名' . $e->getMessage('top_company_name') . '</a></li>';
        }
        if ($e->hasErrorForId('top_name')) {
            echo '<li><a href="#name">お名前' . $e->getMessage('top_name') . '</a></li>';
        }
        if ($e->hasErrorForId('top_furigana')) {
            echo '<li><a href="#furigana">フリガナ' . $e->getMessage('top_furigana') . '</a></li>';
        }
        if ($e->hasErrorForId('top_tel')) {
            echo '<li><a href="#tel">電話番号' . $e->getMessage('top_tel') . '</a></li>';
        }
        if ($e->hasErrorForId('top_mail')) {
            echo '<li><a href="#mail">メールアドレス' . $e->getMessage('top_mail') . '</a></li>';
        }
        if ($e->hasErrorForId('top_mail_2')) {
            echo '<li><a href="#mail">メールアドレス' . $e->getMessage('top_mail_2') . '</a></li>';
        }
        if ($e->hasErrorForId('top_zip')) {
            echo '<li><a href="#add">郵便番号' . $e->getMessage('top_zip') . '</a></li>';
        }
        if ($e->hasErrorForId('top_pref_cd_sel')) {
            echo '<li><a href="#add">都道府県' . $e->getMessage('top_pref_cd_sel') . '</a></li>';
        }
        if ($e->hasErrorForId('top_address')) {
            echo '<li><a href="#add">住所' . $e->getMessage('top_address') . '</a></li>';
        }
        if ($e->hasErrorForId('top_inquiry_title')) {
            echo '<li><a href="#title">お問い合わせ件名' . $e->getMessage('top_inquiry_title') . '</a></li>';
        }
        if ($e->hasErrorForId('top_inquiry_content')) {
            echo '<li><a href="#comment">お問い合わせ内容' . $e->getMessage('top_inquiry_content') . '</a></li>';
        }
?>
                </ul>
            </div>

<?php } ?>
            <form action="/../pin/check_input" data-feature-id="<?php echo Sgmov_View_Pin_Common::FEATURE_ID ?>" data-id="<?php echo Sgmov_View_Pin_Common::GAMEN_ID_PIN001 ?>" method="post">
                <div class="section">
                    <input type="hidden" name="ticket" value="<?php echo $ticket ?>" />
                    <div class="dl_block">
                        <dl>
                            <dt>種類<span class="nes">必須</span></dt>
                            <dd<?php if (isset($e) && $e->hasErrorForId('top_inquiry_type_cd_sel')) { echo ' class="form_error"'; } ?> id="cate">
                                <ul class="clearfix">
                                    <li>
                                        <label class="radio-label" for="inquiry_service">
                                            <input<?php if($pin001Out->inquiry_type_cd_sel() === '1') echo ' checked="checked"'; ?> id="inquiry_service" name="inquiry_type_cd_sel" type="radio" value="1" />
                                            サービスについて
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="inquiry_quality">
                                            <input<?php if($pin001Out->inquiry_type_cd_sel() === '2') echo ' checked="checked"'; ?> id="inquiry_quality" name="inquiry_type_cd_sel" type="radio" value="2" />
                                            品質について
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="inquiry_rec">
                                            <input<?php if($pin001Out->inquiry_type_cd_sel() === '3') echo ' checked="checked"'; ?> id="inquiry_rec" name="inquiry_type_cd_sel" type="radio" value="3" />
                                            採用について
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="inquiry_privacy">
                                            <input<?php if($pin001Out->inquiry_type_cd_sel() === '4') echo ' checked="checked"'; ?> id="inquiry_privacy" name="inquiry_type_cd_sel" type="radio" value="4" />
                                            個人情報について
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="inquiry_setting">
                                            <input<?php if($pin001Out->inquiry_type_cd_sel() === '7') echo ' checked="checked"'; ?> id="inquiry_setting" name="inquiry_type_cd_sel" type="radio" value="7" />
                                            設置輸送
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="inquiry_moving">
                                            <input<?php if($pin001Out->inquiry_type_cd_sel() === '9') echo ' checked="checked"'; ?> id="inquiry_moving" name="inquiry_type_cd_sel" type="radio" value="9" />
                                            お引越し
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="inquiry_foreign">
                                            <input<?php if($pin001Out->inquiry_type_cd_sel() === '10') echo ' checked="checked"'; ?> id="inquiry_foreign" name="inquiry_type_cd_sel" type="radio" value="10" />
                                            海外引越し
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="inquiry_apartment">
                                            <input<?php if($pin001Out->inquiry_type_cd_sel() === '12') echo ' checked="checked"'; ?> id="inquiry_apartment" name="inquiry_type_cd_sel" type="radio" value="12" />
                                            マンションご入居
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="inquiry_charter">
                                            <input<?php if($pin001Out->inquiry_type_cd_sel() === '16') echo ' checked="checked"'; ?> id="inquiry_charter" name="inquiry_type_cd_sel" type="radio" value="16" />
                                            チャータープラン
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="inquiry_entrust">
                                            <input<?php if($pin001Out->inquiry_type_cd_sel() === '18') echo ' checked="checked"'; ?> id="inquiry_entrust" name="inquiry_type_cd_sel" type="radio" value="18" />
                                            引越おまかせプラン
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="inquiry_wonder">
                                            <input<?php if($pin001Out->inquiry_type_cd_sel() === '19') echo ' checked="checked"'; ?> id="inquiry_wonder" name="inquiry_type_cd_sel" type="radio" value="19" />
                                             ＳＧ－ＷＯＮＤＥＲ（イベント受付システム）
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="inquiry_etc">
                                            <input<?php if($pin001Out->inquiry_type_cd_sel() === '5') echo ' checked="checked"'; ?> id="inquiry_etc" name="inquiry_type_cd_sel" type="radio" value="5" />
                                            その他
                                        </label>
                                    </li>
<!--                                    
                                    <li>
                                        <label class="radio-label" for="inquiry_singlesetting">
                                            <input<?php if($pin001Out->inquiry_type_cd_sel() === '6') echo ' checked="checked"'; ?> id="inquiry_singlesetting" name="inquiry_type_cd_sel" type="radio" value="6" />
                                            単品設置輸送
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="inquiry_ladysmoving">
                                            <input<?php if($pin001Out->inquiry_type_cd_sel() === '8') echo ' checked="checked"'; ?> id="inquiry_ladysmoving" name="inquiry_type_cd_sel" type="radio" value="8" />
                                            レディースムービング
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="inquiry_cruise">
                                            <input<?php if($pin001Out->inquiry_type_cd_sel() === '11') echo ' checked="checked"'; ?> id="inquiry_cruise" name="inquiry_type_cd_sel" type="radio" value="11" />
                                            旅客手荷物受付サービス
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="inquiry_cargo">
                                            <input<?php if($pin001Out->inquiry_type_cd_sel() === '13') echo ' checked="checked"'; ?> id="inquiry_cargo" name="inquiry_type_cd_sel" type="radio" value="13" />
                                            カーゴプラン
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="inquiry_option">
                                            <input<?php if($pin001Out->inquiry_type_cd_sel() === '14') echo ' checked="checked"'; ?> id="inquiry_option" name="inquiry_type_cd_sel" type="radio" value="14" />
                                            オプション
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="inquiry_aid">
                                            <input<?php if($pin001Out->inquiry_type_cd_sel() === '15') echo ' checked="checked"'; ?> id="inquiry_aid" name="inquiry_type_cd_sel" type="radio" value="15" />
                                            生活応援プラン
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="inquiry_basics">
                                            <input<?php if($pin001Out->inquiry_type_cd_sel() === '17') echo ' checked="checked"'; ?> id="inquiry_basics" name="inquiry_type_cd_sel" type="radio" value="17" />
                                           スタンダードプラン
                                        </label>
                                    </li>-->
                                </ul>
                            </dd>
                        </dl>
                        <dl>
                            <dt>
                                ＳＧムービングからの
                                <br class="pc_only" />回答<span>必須</span>
                            </dt>
                            <dd<?php if (isset($e) && $e->hasErrorForId('top_need_reply_cd_sel')) { echo ' class="form_error"'; } ?> id ="need_reply_cd_sel">
                                <ul class="three_col clearfix">
                                    <li>
                                        <label class="radio-label" for="need1">
                                            <input <?php if($pin001Out->need_reply_cd_sel() === '1') echo 'checked'; ?> id="need1" name="need_reply_cd_sel" type="radio" value="1" />
                                            必要
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="need2">
                                            <input <?php if($pin001Out->need_reply_cd_sel() === '0') echo 'checked'; ?> id="need2" name="need_reply_cd_sel" type="radio" value="0" />
                                            不要
                                        </label>
                                    </li>
                                </ul>
                            </dd>
                        </dl>
                        <dl>
                            <dt>会社名</dt>
                            <dd<?php if (isset($e) && $e->hasErrorForId('top_company_name')) { echo ' class="form_error"'; } ?>>
                                <input class="w_220" maxlength="80" name="company_name" type="text" value="<?php echo $pin001Out->company_name(); ?>" />
                            </dd>
                        </dl>
                        <dl>
                            <dt id="name">お名前<span>必須</span></dt>
                            <dd<?php if (isset($e) && $e->hasErrorForId('top_name')) { echo ' class="form_error"'; } ?>>
                                <input class="w_220" maxlength="40" name="name" type="text" value="<?php echo $pin001Out->name(); ?>" />
                            </dd>
                        </dl>
                        <dl>
                            <dt id="furigana">フリガナ<span>必須</span></dt>
                            <dd<?php if (isset($e) && $e->hasErrorForId('top_furigana')) { echo ' class="form_error"'; } ?>>
                                <input class="w_220" maxlength="40" name="furigana" type="text" value="<?php echo $pin001Out->furigana(); ?>" />
                            </dd>
                        </dl>
                        <dl>
                            <dt>電話番号</dt>
                            <dd<?php if (isset($e) && $e->hasErrorForId('top_tel')) { echo ' class="form_error"'; } ?> id = "tel">
                                <input class="w_70" maxlength="5" name="tel1" type="text" value="<?php echo $pin001Out->tel1(); ?>" />
                                -
                                <input class="w_70" maxlength="5" name="tel2" type="text" value="<?php echo $pin001Out->tel2(); ?>" />
                                -
                                <input class="w_70" maxlength="4" name="tel3" type="text" value="<?php echo $pin001Out->tel3(); ?>" />
                            </dd>
                        </dl>
                        <dl>
                            <dt id="mail">メールアドレス<span id="mailNeedImg">必須</span></dt>
                            <dd<?php if (isset($e) && ($e->hasErrorForId('top_mail') || $e->hasErrorForId('top_mail_2'))) { echo ' class="form_error"'; } ?>>
                                <input class="w_220" maxlength="80" name="mail" type="text" value="<?php echo $pin001Out->mail(); ?>" />
                            </dd>
                        </dl>
                        <dl>
                            <dt id="add">住所<span>必須</span></dt>
                            <dd<?php if (isset($e) && ($e->hasErrorForId('top_zip') || $e->hasErrorForId('top_address') || $e->hasErrorForId('top_pref_cd_sel'))) { echo ' class="form_error"'; } ?>>
                                <ul>
                                    <li> 〒
                                        <input class="w_70" maxlength="3" name="zip1" type="text" value="<?php echo $pin001Out->zip1(); ?>" />
                                        -
                                        <input class="w_70" maxlength="4" name="zip2" type="text" value="<?php echo $pin001Out->zip2(); ?>" />
                                        <input class="button ml10" name="address_search_btn" type="button" value="住所検索" />
                                    </li>
                                    <li>
                                        <select class="w110" name="pref_cd_sel">
<?php
        $pref_cds = $pin001Out->pref_cds();
        $pref_lbls = $pin001Out->pref_lbls();
        $count = count($pref_cds);
        $option = '';
        for ($i = 0; $i < $count; ++$i) {
            $cd = $pref_cds[$i];
            $lbl = $pref_lbls[$i];
            if ($pin001Out->pref_cd_sel() === $cd) {
                $option .= '<option value="' . $cd . '" selected="selected">' . $lbl . '</option>' . PHP_EOL;
            } else {
                $option .= '<option value="' . $cd . '">' . $lbl . '</option>' . PHP_EOL;
            }
        }
        echo $option;
?>
                                        </select>
                                        都道府県
                                    </li>
                                    <li>
                                        <input class="w220" name="address" maxlength="80" type="text" value="<?php echo $pin001Out->address(); ?>" />
                                        市町村以下
                                    </li>
                                </ul>
                            </dd>
                        </dl>
                        <dl>
                            <dt>件名</dt>
                            <dd<?php if (isset($e) && $e->hasErrorForId('top_inquiry_title')) { echo ' class="form_error"'; } ?>>
                                <input class="w80p" name="inquiry_title" maxlength="80" type="text" value="<?php echo $pin001Out->inquiry_title(); ?>" />
                            </dd>
                        </dl>
                        <dl>
                            <dt id="comment">お問い合わせ内容<span>必須</span></dt>
                            <dd<?php if (isset($e) && $e->hasErrorForId('top_inquiry_content')) { echo ' class="form_error"'; } ?>>
                                <textarea class="w100p" cols="70" name="inquiry_content" rows="9"><?php echo $pin001Out->inquiry_content(); ?></textarea>
                                <p>※1000文字まででお願いいたします。</p>
                            </dd>
                        </dl>
                    </div>
                </div>

                <!-- ▽▽お問い合わせにあたって　section　ここから -->
                <div class="border_box"><strong>お問い合わせにあたって</strong>
                    <ul>
                        <li>ご連絡までにお時間をいただく場合がございます。ご了承ください。</li>
                        <li>電話番号、メールアドレスが誤っておりますとご連絡ができません。間違いがないかご確認ください。</li>
                    </ul>
                </div>
                <!-- △△お問い合わせにあたって　section　ここまで -->
                        <!--▼個人情報の取り扱いここから-->
                        <?php
                        include_once dirname(__FILE__) . '/../parts/input_attention_area.php';
                        ?>
                        <!--▲個人情報の取り扱いここまで-->
                </div>
                <!-- △△個人情報の取り扱い　section　ここまで -->
                <div class="text_center comBtn02 btn01 fadeInUp animate">
                    <div class="btnInner">
                        <input id="submit_button" name="confirm_btn" type="button" value="同意して次に進む（入力内容の確認）" />
                    </div>
                </div>
            </form>
        </div>
</div>
    <!--main-->

<!-- フッターStart ************************************************ -->
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/footer.php'); ?>
<!-- フッターEnd ************************************************ -->
    <!--[if lt IE 9]>
    <script charset="UTF-8" type="text/javascript" src="/js/form/jquery-1.12.0.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/form/lodash-compat.min.js"></script>
    <![endif]-->
    <!--[if gte IE 9]><!-->
    <script charset="UTF-8" type="text/javascript" src="/js/form/jquery-2.2.0.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/form/lodash.min.js"></script>
    <!--<![endif]-->
    <script charset="UTF-8" type="text/javascript" src="/js/form/common.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/form/multi_send.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/form/ajax_searchaddress.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/form/hissuChange.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/form/radio.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/form/input.js"></script>
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_script_foot.php'); ?>
    <script> 
	if($('#checkbox_agreement').is(":checked")) {
		$("#submit_button").attr("disabled", false);
		document.getElementById("submit_button").style.opacity = "1";
	} else {
		$("#submit_button").attr("disabled", true);
		document.getElementById("submit_button").style.opacity = "0.2";
	}
	function changePageButton() {
            var checked = $('#checkbox_agreement').prop("checked");
            if (checked) {
                    $("#submit_button").attr("disabled", false);
                    document.getElementById("submit_button").style.opacity = "1";
            } else {
                    $("#submit_button").attr("disabled", true);
                    document.getElementById("submit_button").style.opacity = "0.2";
            }
	}

	$('#checkbox_agreement').on('click', function() {
		if($(this).is(":checked")) {
			$(this).val(1);
		} else {
			$(this).val(0);
		}
		changePageButton();
	});
    </script>
</body>
</html>
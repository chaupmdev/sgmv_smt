<?php
/**
 * お問い合わせ確認画面を表示します。
 * @package    ssl_html
 * @subpackage PIN
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../../lib/Lib.php';
Sgmov_Lib::useView('pin/Confirm');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Pin_Confirm();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Pin002Out
 */
$pin002Out = $forms['outForm'];
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
    <meta property="og:url" content="https://www.sagawa-mov.co.jp/pin/confirm/">
    <meta property="og:site_name" content="<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl-ogp.php'); ?>">
    <meta property="og:image" content="/img/ogp/og_image_sgmv.jpg">
    <meta property="twitter:card" content="summary_large_image">
    <link rel="shortcut icon" href="/img/common/favicon.ico">
    <link rel="apple-touch-icon" sizes="192x192" href="/img/ogp/touch-icon.png">
    <title>一般的なお問い合わせの確認｜<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl.php'); ?></title>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_style.php'); ?>
    <link href="/css/form/common.css" rel="stylesheet" type="text/css">
    <link href="/css/form/form.css" rel="stylesheet" type="text/css">
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
				<h1 class="topLead">一般的なお問い合わせの確認<em>Confirm</em></h1>
				<ul id="pagePath">
					<li><a href="/"><img class="home" src="/img/common/icon54.jpg" alt=""></a></li>
					<li><a href="/contact/">お問い合わせ</a></li>
					<li><a href="/pin/input/">一般的なお問い合わせ</a></li>
					<li>入力内容確認</li>
				</ul>
			</div>
		</div>
        <div class="wrap clearfix">

            <h1 class="page_title">お問い合わせの確認</h1>
                    <p class="sentence">
                        ご入力内容をご確認のうえ、「入力内容を送信する」ボタンを押してください。
                        <br />修正する場合は「修正する」ボタンを押してください。
                    </p>

                    <form action="/pin/complete/" method="post">
                        <div class="section">
                            <input type="hidden" name="ticket" value="<?php echo $ticket ?>" />
                            <div class="dl_block">
                                <dl>
                                    <dt>種類</dt>
                                    <dd><?php echo $pin002Out->inquiry_type() ?></dd>
                                </dl>
                                <dl>
                                    <dt>ＳＧムービングからの回答</dt>
                                    <dd><?php echo $pin002Out->need_reply() ?></dd>
                                </dl>
                                <dl>
                                    <dt>会社名</dt>
                                    <dd><?php echo $pin002Out->company_name() ?></dd>
                                </dl>
                                <dl>
                                    <dt>お名前</dt>
                                    <dd><?php echo $pin002Out->name() ?></dd>
                                </dl>
                                <dl>
                                    <dt>フリガナ</dt>
                                    <dd><?php echo $pin002Out->furigana() ?></dd>
                                </dl>
                                <dl>
                                    <dt>電話番号</dt>
                                    <dd><?php echo $pin002Out->tel() ?></dd>
                                </dl>
                                <dl>
                                    <dt>メールアドレス</dt>
                                    <dd><?php echo $pin002Out->mail() ?></dd>
                                </dl>
                                <dl>
                                    <dt>住所</dt>
                                    <dd>
                                        〒<?php echo $pin002Out->zip() ?>

                                        <br /><?php echo $pin002Out->address_all() ?>

                                    </dd>
                                </dl>
                                <dl>
                                    <dt>件名</dt>
                                    <dd><?php echo $pin002Out->inquiry_title() ?></dd>
                                </dl>
                                <dl>
                                    <dt>お問い合わせ内容</dt>
                                    <dd><?php echo $pin002Out->inquiry_content() ?></dd>
                                </dl>
                            </div>
                        </div>

                        <div class="border_box">
                            <strong>お問い合わせにあたって</strong>
                            <ul>
                                <li>ご連絡までにお時間をいただく場合がございます。ご了承ください。</li>
                                <li>電話番号、メールアドレスが誤っておりますとご連絡ができません。間違いがないかご確認ください。</li>
                            </ul>
                        </div>

                        <div class="btn_area">
                            <div class="text_center comBtn02 btn01 fadeInUp animate">
                                <a class="back" href="/pin/input/">修正する</a>
                            </div>

                            <div class="text_center comBtn02 btn01 fadeInUp animate">
                                <div class="btnInner">
                                    <input id="submit_button" name="submit" type="submit" value="入力内容を送信する">
                                </div>
                            </div>
                        </div>
            </form>
        </div>
    </div>
    <!--main-->
</div>
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
    <script charset="UTF-8" type="text/javascript" src="/js/form/radio.js"></script>
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_script_foot.php'); ?>
</body>
</html>
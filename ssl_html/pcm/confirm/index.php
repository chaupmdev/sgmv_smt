<?php
/**
 * 法人引越輸送確認画面を表示します。
 * @package    ssl_html
 * @subpackage PCM
 * @author     K.Hamada(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../../lib/Lib.php';
Sgmov_Lib::useView('pcm/Confirm');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Pcm_Confirm();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Pcm002Out
 */
$pcm002Out = $forms['outForm'];
?>
<!DOCTYPE html>
<html dir="ltr" lang="ja-jp" xml:lang="ja-jp">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0">
    <meta http-equiv="content-style-type" content="text/css">
    <meta http-equiv="content-script-type" content="text/javascript">
    <meta name="Description" content="引越輸送のお問い合わせページです。">
    <meta name="author" content="SG MOVING Co.,Ltd">
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved.">
    <meta property="og:locale" content="ja_JP">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.sagawa-mov.co.jp/pcm/confirm/">
    <meta property="og:site_name" content="<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl-ogp.php'); ?>">
    <meta property="og:image" content="/img/ogp/og_image_sgmv.jpg">
    <meta property="twitter:card" content="summary_large_image">
    <link rel="shortcut icon" href="/img/common/favicon.ico">
    <link rel="apple-touch-icon" sizes="192x192" href="/img/ogp/touch-icon.png">
    <title>引越輸送のお問い合わせの入力内容確認｜<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl.php'); ?></title>
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
    <div id="container">
    <!-- ヘッダStart ************************************************ -->
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/header.php'); ?>
    <!-- ヘッダEnd ************************************************ -->

    <div id="main">
        <div class="pageTitle style01">
            <div class="comBox">
                <h1 class="topLead">入力内容確認<em>Confirm</em></h1>
                <ul id="pagePath">
                    <li><a href="/"><img class="home" src="/img/common/icon54.jpg" alt=""></a></li>
                    <li><a href="/contact/">お問い合わせ</a></li>
                    <li><a href="/pcm/input/">引越輸送のお問い合わせ</a></li>
                    <li>入力内容確認</li>
                </ul>
            </div>
        </div>

        <!-- Start ************************************************ -->
        
        <!-- End ************************************************ -->
        <div class="wrap clearfix">
            <p class="sentence">ご入力内容をご確認のうえ、「入力内容を送信する」ボタンを押してください。
                <br />
            修正する場合は「修正する」ボタンを押してください。</p>

        <form action="/pcm/complete/" method="post">
            <input type="hidden" name="ticket" value="" />
            <!-- ▼お問い合わせ情報 ここから-->
            <div class="section">
                <h3 class="cont_inner_title">お問い合わせ情報</h3>
                <div class="dl_block">
                    <dl>
                        <dt>種類</dt>
                        <dd><?php echo $pcm002Out->inquiry_type() ?></dd>
                    </dl>
                    <dl>
                        <dt>カテゴリー</dt>
                        <dd><?php echo $pcm002Out->inquiry_category() ?></dd>
                    </dl>
                    <dl>
                        <dt>件名</dt>
                        <dd><?php echo $pcm002Out->inquiry_title() ?></dd>
                    </dl>
                    <dl>
                        <dt>お問い合わせ内容</dt>
                        <dd><?php echo $pcm002Out->inquiry_content() ?></dd>
                    </dl>
                </div>
            </div>
            <!-- ▲お問い合わせ情報 ここまで--> 
            <!-- ▼お客様情報 ここから-->
            <div class="section">
                <h3 class="cont_inner_title">お客様情報</h3>
                <div class="dl_block">
                    <dl>
                        <dt>会社名</dt>
                        <dd><?php echo $pcm002Out->company_name() ?></dd>
                    </dl>
                    <dl>
                        <dt>部署名</dt>
                        <dd><?php echo $pcm002Out->post_name() ?></dd>
                    </dl>
                    <dl>
                        <dt>担当者名</dt>
                        <dd><?php echo $pcm002Out->charge_name() ?></dd>
                    </dl>
                    <dl>
                        <dt>担当者名フリガナ</dt>
                        <dd><?php echo $pcm002Out->charge_furigana() ?></dd>
                    </dl>
                    <dl>
                        <dt>電話番号</dt>
                        <dd>
                            <?php echo $pcm002Out->tel() ?>
                            <br /><?php echo $pcm002Out->tel_type() ?>
                            <?php echo $pcm002Out->tel_other() ?>
                        </dd>
                    </dl>
                    <dl>
                        <dt>FAX番号</dt>
                        <dd><?php echo $pcm002Out->fax() ?></dd>
                    </dl>
                    <dl>
                        <dt>メールアドレス</dt>
                        <dd><?php echo $pcm002Out->mail() ?></dd>
                    </dl>
                    <dl>
                        <dt>連絡方法</dt>
                        <dd><?php echo $pcm002Out->contact_method() ?></dd>
                    </dl>
                    <dl>
                        <dt>電話連絡可能時間帯</dt>
                        <dd>
                            <?php echo $pcm002Out->contact_available() ?>
                            <?php echo $pcm002Out->contact_start() ?>
                            <?php if(($pcm002Out->contact_start() != '') || ($pcm002Out->contact_end() != '')){ echo '～'; }?>
                            <?php echo $pcm002Out->contact_end() ?>
                        </dd>
                    </dl>
                    <dl>
                        <dt>住所</dt>
                        <dd>
                            〒<?php echo $pcm002Out->zip() ?>
                            <br /><?php echo $pcm002Out->address_all() ?>
                        </dd>
                    </dl>
                </div>
            </div>
            <!-- ▲お客様情報 ここまで-->
            <div class="border_box"><strong>お問い合わせにあたって</strong>
                <ul>
                    <li>ご連絡までにお時間をいただく場合がございます。ご了承ください。</li>
                    <li>電話番号、メールアドレスが誤っておりますとご連絡ができません。間違いがないかご確認ください。</li>
                </ul>
            </div>
            <div class="btn_area">
                <div class="text_center comBtn02 btn01 fadeInUp animate">
                    <a class="back" href="/pcm/input/">修正する</a>
                </div>
                <div class="text_center comBtn02 btn01 fadeInUp animate">
                    <div class="btnInner">
                        <input id="submit_button" type="submit" name="submit" value="入力内容を送信する" class="next">
                        <input type="hidden" name="ticket" value="<?php echo $ticket ?>" />
                    </div>
                </div>
            </div>
        </form>
        </div>
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
    <script charset="UTF-8" type="text/javascript" src="/js/form/radio.js"></script>
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_script_foot.php'); ?>
</body>

</html>
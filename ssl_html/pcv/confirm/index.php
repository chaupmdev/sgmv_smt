<?php
/**
 * 法人オフィス移転確認画面を表示します。
 * @package    ssl_html
 * @subpackage PCV
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../../lib/Lib.php';
Sgmov_Lib::useView('pcv/Confirm');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Pcv_Confirm();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Pcv002Out
 */
$pcv002Out = $forms['outForm'];
?>
<!DOCTYPE html>
<html dir="ltr" lang="ja-jp" xml:lang="ja-jp">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0">
    <meta http-equiv="content-style-type" content="text/css">
    <meta http-equiv="content-script-type" content="text/javascript">
    <meta name="Description" content="オフィス移転訪問お見積りページです。">
    <meta name="author" content="SG MOVING Co.,Ltd">
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved.">
    <meta property="og:locale" content="ja_JP">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.sagawa-mov.co.jp/pcv/confirm/">
    <meta property="og:site_name" content="<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl-ogp.php'); ?>">
    <meta property="og:image" content="/img/ogp/og_image_sgmv.jpg">
    <meta property="twitter:card" content="summary_large_image">
    <link rel="shortcut icon" href="/img/common/favicon.ico">
    <link rel="apple-touch-icon" sizes="192x192" href="/img/ogp/touch-icon.png">
    <title>オフィス移転訪問お見積りフォームの入力内容確認｜<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl.php'); ?></title>
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
                <h1 class="topLead">オフィス移転訪問お見積りフォームの入力内容確認<em>Confirm</em></h1>
                <ul id="pagePath">
                    <li><a href="/"><img class="home" src="/img/common/icon54.jpg" alt=""></a></li>
                    <li><a href="/contact/">お問い合わせ</a></li>
                    <li><a href="/pcv/input/">オフィス移転訪問お見積りフォーム</a></li>
                    <li>入力内容確認</li>
                </ul>
            </div>
        </div>

        <!-- Start ************************************************ -->
        
        <!-- End ************************************************ -->

        <div class="wrap clearfix">
                <p class="sentence">お客様の下へお伺いし、詳細なお見積りを出させていただきます。<br />
                    下記の必要項目にご記入の上、送信ください。<br />
                    こちらよりご連絡させていただきます。<br />
                    ※3月15日～4月10日は繁忙期のため、概算お見積り適用外となります。<br />
                    別途お見積りさせていただきます。
                </p>

                <form action="/pcv/complete/" method="post">
                    <!-- ▽▽お客様情報　section　ここから -->
                    <div class="section">
                        <h3 class="cont_inner_title">お客様情報</h3>
                        <div class="dl_block">
                            <input type="hidden" name="ticket" value="<?php echo $ticket ?>" />
                            <dl>
                                <dt>会社名</dt>
                                <dd><?php echo $pcv002Out->company_name() ?></dd>
                            </dl>
                            <dl>
                                <dt>会社名フリガナ</dt>
                                <dd><?php echo $pcv002Out->company_furigana() ?></dd>
                            </dl>
                            <dl>
                                <dt>担当者名</dt>
                                <dd><?php echo $pcv002Out->charge_name() ?></dd>
                            </dl>
                            <dl>
                                <dt>担当者名フリガナ</dt>
                                <dd><?php echo $pcv002Out->charge_furigana() ?></dd>
                            </dl>
                            <dl>
                                <dt>電話番号</dt>
                                <dd>
                                    <?php echo $pcv002Out->tel() ?>
                                    <br /><?php echo $pcv002Out->tel_type() ?>
                                    <?php echo $pcv002Out->tel_other() ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt>メールアドレス</dt>
                                <dd><?php echo $pcv002Out->mail() ?></dd>
                            </dl>
                            <dl>
                                <dt>連絡方法</dt>
                                <dd><?php echo $pcv002Out->contact_method() ?></dd>
                            </dl>
                            <dl>
                                <dt>電話連絡可能時間帯</dt>
                                <dd>
                                    <?php echo $pcv002Out->contact_available() ?>
                                    <?php echo $pcv002Out->contact_start() ?>～<?php echo $pcv002Out->contact_end() ?>
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <!-- △△お客様情報　section　ここまで --> 
                    <!-- ▽▽お引越情報　section　ここから -->
                    <div class="section">
                        <h3 class="cont_inner_title">お引越し情報</h3>
                        <div class="dl_block">
                            <dl>
                                <dt>現在お住まいの地域</dt>
                                <dd><?php echo $pcv002Out->from_area() ?></dd>
                            </dl>
                            <dl>
                                <dt>お引越先の地域</dt>
                                <dd><?php echo $pcv002Out->to_area() ?></dd>
                            </dl>
                            <dl>
                                <dt>お引越し予定日</dt>
                                <dd><?php echo $pcv002Out->move_date() ?></dd>
                            </dl>
                            <dl>
                                <dt>訪問お見積り希望日</dt>
                                <dd>
                                    <div>第1希望日：<?php echo $pcv002Out->visit_date1() ?></div>
                                    <div>第2希望日：<?php echo $pcv002Out->visit_date2() ?></div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <!-- △△お引越情報　section　ここまで --> 
                    <!-- ▽▽現在のお住まいについて　section　ここから -->
                    <div class="section">
                        <h3 class="cont_inner_title">現在のお住まいについて</h3>
                        <div class="dl_block">
                            <dl>
                                <dt>現住所</dt>
                                <dd>
                                    〒<?php echo $pcv002Out->cur_zip() ?>
                                    <br /><?php echo $pcv002Out->cur_address_all() ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt>エレベーターの有無</dt>
                                <dd><?php echo $pcv002Out->cur_elevator() ?></dd>
                            </dl>
                            <dl>
                                <dt>現在お住まいの階</dt>
                                <dd><?php echo $pcv002Out->cur_floor() ?>階</dd>
                            </dl>
                            <dl>
                                <dt>住居前道幅</dt>
                                <dd><?php echo $pcv002Out->cur_road() ?></dd>
                            </dl>
                        </div>
                    </div>
                    <!-- △△現在のお住まいについて　section　ここまで --> 
                    <!-- ▽▽お引越し先のお住まいについて　section　ここから -->
                    <div class="section">
                        <h3 class="cont_inner_title">お引越し先のお住まいについて</h3>
                        <div class="dl_block">
                            <dl>
                                <dt>新住所</dt>
                                <dd>
                                    〒<?php echo $pcv002Out->new_zip() ?>
                                    <br /><?php echo $pcv002Out->new_address_all() ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt>エレベーターの有無</dt>
                                <dd><?php echo $pcv002Out->new_elevator() ?></dd>
                            </dl>
                            <dl>
                                <dt>新しいお住まいの階</dt>
                                <dd><?php echo $pcv002Out->new_floor() ?>階</dd>
                            </dl>
                            <dl>
                                <dt>住居前道幅</dt>
                                <dd><?php echo $pcv002Out->new_road() ?></dd>
                            </dl>
                            <dl>
                                <dt>移動人数</dt>
                                <dd><?php echo $pcv002Out->number_of_people() ?>人</dd>
                            </dl>
                            <dl>
                                <dt>フロア坪数</dt>
                                <dd><?php echo $pcv002Out->tsubo_su() ?>坪</dd>
                            </dl>
                            <dl>
                                <dt>備考欄</dt>
                                <dd><?php echo $pcv002Out->comment() ?></dd>
                            </dl>
                        </div>
                    </div>
                    <!-- △△お引越し先のお住まいについて　section　ここまで --> 

                    <!-- ▽▽お問い合わせにあたって　section　ここから -->
                    <div class="border_box"><strong>お問い合わせにあたって</strong>
                        <ul>
                            <li>ご連絡までにお時間をいただく場合がございます。ご了承ください。</li>
                            <li>電話番号、メールアドレスが誤っておりますとご連絡ができません。間違いがないかご確認ください。</li>
                        </ul>
                    </div>		
                    <!-- △△お問い合わせにあたって　section　ここまで --> 
                    <div class="btn_area">
                        <div class="text_center comBtn02 btn01 fadeInUp animate">
                            <a class="back" href="/pcv/input/">修正する</a>
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
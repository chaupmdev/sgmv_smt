<?php
/**
 * コミケサービスのお申し込み確認画面を表示します。
 * @package    ssl_html
 * @subpackage EVE
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../../lib/Lib.php';
Sgmov_Lib::useView('ren/Confirm');

// 処理を実行
$view = new Sgmov_View_Ren_Confirm();
$forms = $view->executeInner();

/**#@-*/
/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];
/**
 * フォーム
 * @var Sgmov_Form_Eve001Out
 */
$renOutForm = $forms['outForm'];

$dispItemInfo = $forms['dispItemInfo'];
?>
<!DOCTYPE html>
<html dir="ltr" lang="ja-jp" xml:lang="ja-jp">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0">
    <meta http-equiv="content-style-type" content="text/css">
    <meta http-equiv="content-script-type" content="text/javascript">
    <meta name="Description" content="エントリーページです。">
    <meta name="author" content="SG MOVING Co.,Ltd">
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved.">
    <meta property="og:locale" content="ja_JP">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.sagawa-mov.co.jp/ren/confirm/">
    <meta property="og:site_name" content="<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl-ogp.php'); ?>">
    <meta property="og:image" content="/img/ogp/og_image_sgmv.jpg">
    <meta property="twitter:card" content="summary_large_image">
    <link rel="shortcut icon" href="/img/common/favicon.ico">
    <link rel="apple-touch-icon" sizes="192x192" href="/img/ogp/touch-icon.png">
    <title>エントリーの確認｜<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl.php'); ?></title>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_style.php'); ?>
    <link href="/css/form/common.css" rel="stylesheet" type="text/css">
    <link href="/css/form/form.css" rel="stylesheet" type="text/css">
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_script.php'); ?>
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/analytics_head.php'); ?>
    <script src="/js/form/ga.js" type="text/javascript"></script>
    <style type="text/css">
        .dl_block dl:last-child {
            border-bottom: solid 1px #ccc;
        }
        .emp{
            border-bottom: none;
            border-top: none;
        }
        @media only screen and (max-width: 980px){
            .dl_block dl:last-child { 
                border-bottom: none;
            }
            .emp{
                border-bottom: solid 1px #ccc;
                border-top: solid 1px #ccc;
            }
        }
    </style>
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
                <h1 class="topLead">新卒採用エントリーの確認<em>Confirm</em></h1>
                <ul id="pagePath">
                    <li><a href="/"><img class="home" src="/img/common/icon54.jpg" alt=""></a></li>
                    <li><a href="/contact/">お問い合わせ</a></li>
                    <li><a href="/ren/input/">新卒採用エントリーフォーム</a></li>
                    <li>入力内容確認</li>
                </ul>
            </div>
        </div>

        <!-- Start ************************************************ -->
        
        <!-- End ************************************************ -->

        <div class="wrap clearfix">
             <p class="sentence" style="margin-bottom: 5px;">
                    以下の内容でお間違いなければご応募下さい。
                </p>

            <div class="section">
                <form action="/ren/complete/" data-feature-id="" method="post">
                    <input name="ticket" type="hidden" value="<?php echo $ticket ?>" />
                    <input name="comiket_id" type="hidden" value="" />

                    <div class="section">

                        <div class="dl_block comiket_block">
                            <dl>
                                <dt id="event_sel">
                                    お名前(漢字)
                                </dt>
                                <dd>
                                    <?php echo $renOutForm->personal_name(); ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="eventsub_address">
                                     お名前(フリガナ)
                                </dt>
                                <dd>
                                   <?php echo $renOutForm->personal_name_furi(); ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="eventsub_term">
                                    性別
                                </dt>
                                <dd>
                                    <?php 
                                    if($renOutForm->sei() == 1):
                                        echo "男性";
                                    elseif ($renOutForm->sei() == 2):
                                        echo "女性";
                                    elseif ($renOutForm->sei() == 3):
                                        echo "その他";
                                    endif;?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="eventsub_term">
                                    生年月日
                                </dt>
                                <dd>
                                    <?php echo $renOutForm->date_of_birth_year_cd_sel();?>年 <?php echo str_pad($renOutForm->date_of_birth_month_cd_sel(), 2, "0", STR_PAD_LEFT) ;?>月<?php echo str_pad($renOutForm->date_of_birth_day_cd_sel(), 2, "0", STR_PAD_LEFT) ;?>日
                                </dd>
                            </dl>
                            <dl>
                                <dt id="eventsub_term">
                                    郵便番号
                                </dt>
                                <dd>
                                    <?php echo "〒".$renOutForm->zip1()."-".$renOutForm->zip2()."\n"; ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="eventsub_term">
                                    都道府県
                                </dt>
                                <dd>
                                    <?php echo $renOutForm->pref_nm();?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_address">
                                    市区町村
                                </dt>
                                <dd>
                                    <span class=""><?php echo $renOutForm->address();?></span>
                                </dd>
                            </dl>
                             <dl>
                                <dt id="comiket_building">
                                    番地・建物名・部屋番号
                                </dt>
                                <dd>
                                    <span class=""><?php echo $renOutForm->building();?></span>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="eventsub_term">
                                    電話番号（携帯電話番号）
                                </dt>
                                <dd>
                                <?php echo $renOutForm->tel(); ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="eventsub_term">
                                    メールアドレス
                                </dt>
                                <dd>
                                    <?php echo $renOutForm->mail(); ?>
                                </dd>
                            </dl>
                            
                            
                        </div>

                    <div class="btn_area">
                        <div class="text_center comBtn02 btn01 fadeInUp animate">
                            <a class="back" href="/ren/input">修正する</a>
                        </div>
                        <div class="text_center comBtn02 btn01 fadeInUp animate">
                            <div class="btnInner">
                                <input id="submit_button" class="disable_button" name="submit" type="submit" value="入力内容を送信する">
                            </div>
                        </div>
                    </div>
                </form>
                <div class="attention_area">

                </div>
            </div>
        </div>
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
    <script charset="UTF-8" type="text/javascript" src="/js/form/ajax_searchaddress.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/smooth_scroll.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/jquery.ah-placeholder.js"></script>
    
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_script_foot.php'); ?>
</body>

</html>
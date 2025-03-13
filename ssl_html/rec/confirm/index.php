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
Sgmov_Lib::useView('rec/Confirm');

// 処理を実行
$view = new Sgmov_View_Rec_Confirm();
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
$recOutForm = $forms['outForm'];

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
    <meta name="Description" content="中途採用エントリーページです。">
    <meta name="author" content="SG MOVING Co.,Ltd">
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved.">
    <meta property="og:locale" content="ja_JP">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.sagawa-mov.co.jp/rec/confirm/">
    <meta property="og:site_name" content="<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl-ogp.php'); ?>">
    <meta property="og:image" content="/img/ogp/og_image_sgmv.jpg">
    <meta property="twitter:card" content="summary_large_image">
    <link rel="shortcut icon" href="/img/common/favicon.ico">
    <link rel="apple-touch-icon" sizes="192x192" href="/img/ogp/touch-icon.png">
    <title>中途採用エントリーの確認｜<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl.php'); ?></title>
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
                <h1 class="topLead">中途採用エントリーの確認<em>Confirm</em></h1>
                <ul id="pagePath">
                    <li><a href="/"><img class="home" src="/img/common/icon54.jpg" alt=""></a></li>
                    <li><a href="/contact/">お問い合わせ</a></li>
                    <li><a href="/rec/input/">中途採用エントリーフォーム</a></li>
                    <li>入力内容確認</li>
                </ul>
            </div>
        </div>

        <!-- Start ************************************************ -->
        
        <!-- End ************************************************ -->

        <div class="wrap clearfix">
<!--            <h1 class="page_title">中途採用エントリーフォーム</h1>-->
             <p class="sentence" style="margin-bottom: 5px;">
                    以下の内容でお間違いなければご応募下さい。
                </p>

            <div class="section">
                <form action="/rec/complete/" data-feature-id="" method="post">
                    <input name="ticket" type="hidden" value="<?php echo $ticket ?>" />
                    <input name="comiket_id" type="hidden" value="" />

                    <div class="section">

                        <div class="dl_block comiket_block">
                            <dl>
                                <dt id="event_sel">
                                    お名前(漢字)
                                </dt>
                                <dd>
                                    <?php echo $recOutForm->personal_name(); ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="eventsub_address">
                                     お名前(フリガナ)
                                </dt>
                                <dd>
                                   <?php echo $recOutForm->personal_name_furi(); ?>
                                </dd>
                            </dl>
                            <div style="width: 100%;display: flex;">
                                <dl style ="width: 50%;">
                                    <dt id="eventsub_term" style="width: 17%">
                                        性別
                                    </dt>
                                    <dd style="width: 17%">
                                        <?php if($recOutForm->sei() == 1):
                                            echo "男性";
                                        elseif ($recOutForm->sei() == 2) :
                                            echo "女性";
                                        endif;?>
                                    </dd>
                                </dl>
                                <dl style ="width: 50%;">
                                    <dt id="eventsub_term">
                                        ご年齢
                                    </dt>
                                    <dd>
                                        <?php echo $recOutForm->age();?>歳
                                    </dd>
                                </dl>
                            </div>
                            <dl>
                                <dt id="eventsub_term">
                                    郵便番号
                                </dt>
                                <dd>
                                    <?php echo "〒".$recOutForm->zip1()."-".$recOutForm->zip2()."\n"; ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="eventsub_term">
                                    都道府県
                                </dt>
                                <dd>
                                    <?php echo $recOutForm->pref_nm();?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_address">
                                    市区町村
                                </dt>
                                <dd>
                                    <span class=""><?php echo $recOutForm->address();?></span>
                                </dd>
                            </dl>
                             <dl>
                                <dt id="comiket_building">
                                    番地・建物名・部屋番号
                                </dt>
                                <dd>
                                    <span class=""><?php echo $recOutForm->building();?></span>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="eventsub_term">
                                    電話番号（携帯電話番号）
                                </dt>
                                <dd>
                                <?php echo $recOutForm->tel(); ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="eventsub_term">
                                    メールアドレス
                                </dt>
                                <dd>
                                    <?php echo $recOutForm->mail(); ?>
                                </dd>
                            </dl>
                            <dl style="display:none">
                                <dt id="eventsub_term">
                                    現在の就業状況必
                                </dt>
                                <dd>
                                    <?php echo $dispItemInfo["current_employment_status_lbls"][$recOutForm->current_employment_status()]; ?>
                                </dd>
                            </dl>
                            <dl class ="emp" >
                                <dt id="eventsub_term">
                                    希望雇用形態など
                                </dt>
                                <dd>
                                <?php $wage = "月給：";
                                 if(isset($dispItemInfo["employ_cd_lbls"][$recOutForm->employ_cd()])):
                                    echo "<span style='white-space:nowrap;'>".$dispItemInfo["employ_cd_lbls"][$recOutForm->employ_cd()]."&nbsp&nbsp&nbsp</span>";
                                    endif;
                                    if(isset($dispItemInfo["center_id_lbls"][$recOutForm->center_id()])):
                                        echo "<span style='white-space:nowrap;'>".@str_replace('_', '　', $dispItemInfo["center_id_lbls"][$recOutForm->center_id()])."&nbsp&nbsp&nbsp</span>";
                                    endif;
                                    if(isset($dispItemInfo["occupation_cd_lbls"][$recOutForm->occupation_cd()])):
                                        echo "<span style='white-space:nowrap;'>".$dispItemInfo["occupation_cd_lbls"][$recOutForm->occupation_cd()]."&nbsp&nbsp&nbsp</span>";
                                    endif;

                                    if($recOutForm->employ_cd() == "02"){
                                       $wage = "時給：";
                                    }
                                    echo "<span style='white-space:nowrap;'>".$wage."￥".number_format($dispItemInfo["wage"])."～</span>";?>
                                </dd>
                            </dl>
                            <dl style="display:none">
                                <dt id="eventsub_term">
                                    連絡可能な時間帯
                                </dt>
                                <dd>
                                    <?php
                                        $tc = count($recOutForm->contact_time());
                                        $i = 0;
                                        foreach (array_keys($recOutForm->contact_time()) as $key) {
                                            $i++;
                                            if(in_array($key, array_keys($dispItemInfo["contact_time_lbls"]))){
                                                $comma = "、";
                                                if($tc == $i){
                                                    $comma = "";
                                                }
                                                echo "<span style='white-space:nowrap;'>".$dispItemInfo["contact_time_lbls"][$key]."&nbsp</span>".$comma;
                                            }
                                        }
                                   ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="eventsub_term">
                                    ご質問など
                                </dt>
                                <dd>
                                    <?php echo nl2br($recOutForm->question()); ?>
                                </dd>
                            </dl>
                        </div>

                    <div class="btn_area">
                        <div class="text_center comBtn02 btn01 fadeInUp animate">
                            <a class="back" href="/rec/input">修正する</a>
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
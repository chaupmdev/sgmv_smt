<?php
/**
 * 訪問見積もり申し込み確認画面を表示します。
 * @package    ssl_html
 * @subpackage PVE
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../../lib/Lib.php';
Sgmov_Lib::useView('pve/Confirm');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Pve_Confirm();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Pve002Out
 */
$pve002Out = $forms['outForm'];
?>
<!DOCTYPE html>
<html dir="ltr" lang="ja-jp" xml:lang="ja-jp">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0">
    <meta http-equiv="content-style-type" content="text/css">
    <meta http-equiv="content-script-type" content="text/javascript">
    <meta name="Description" content="訪問お見積りページです。">
    <meta name="author" content="SG MOVING Co.,Ltd">
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved.">
    <meta property="og:locale" content="ja_JP">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.sagawa-mov.co.jp/pve/confirm/">
    <meta property="og:site_name" content="<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl-ogp.php'); ?>">
    <meta property="og:image" content="/img/ogp/og_image_sgmv.jpg">
    <meta property="twitter:card" content="summary_large_image">
    <link rel="shortcut icon" href="/img/common/favicon.ico">
    <link rel="apple-touch-icon" sizes="192x192" href="/img/ogp/touch-icon.png">
    <title><?php
    //タイトル
    if ($pve002Out->pre_exist_flag() === '1') {
        $title = 'お引越し申し込みの確認';
    } else {
        $title = '訪問お見積りの確認';
    }
    echo $title;
    ?>｜<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl.php'); ?></title>
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
                <h1 class="topLead">訪問お見積りの確認<em>Confirm</em></h1>
                <ul id="pagePath">
                    <li><a href="/"><img class="home" src="/img/common/icon54.jpg" alt=""></a></li>
                    <li><a href="/contact/">お問い合わせ</a></li>
                    <li><a href="/pve/input/">訪問お見積りフォーム</a></li>
                    <li>入力内容確認</li>
                </ul>
            </div>
        </div>

        <!-- Start ************************************************ -->
        
        <!-- End ************************************************ -->

        <div class="wrap clearfix">
<!--            <h1 class="page_title"><?php echo $title; ?></h1>-->

            <form action="/pve/complete/" method="post">

<?php
    if ($pve002Out->pre_exist_flag() === '1') {
?>

                <div class="section">

                    <h2 class="section_title">概算お見積り条件</h2>

                    <!--▼条件表示エリアここから-->
                    <div class="dl_block">
                        <dl>
                            <dt>お引越しコース</dt>
                            <dd><?php echo $pve002Out->pre_course(); ?></dd>
                        </dl>
                        <dl>
                            <dt>お引越しプラン</dt>
                            <dd><?php echo $pve002Out->pre_plan(); ?></dd>
                        </dl>
                        <dl>
                            <dt>お引越し先</dt>
                            <dd><?php echo $pve002Out->pre_from_area(); ?>から<?php echo $pve002Out->pre_to_area(); ?></dd>
                        </dl>
                        <dl>
                            <dt>お引越し予定日</dt>
                            <dd><?php
        echo substr($pve002Out->pre_move_date(), 0, 4) . '年' . substr($pve002Out->pre_move_date(), 4, 2) . '月' . substr($pve002Out->pre_move_date(), 6, 2) . '日';
                            ?></dd>
                        </dl>
                        <dl>
                            <dt>概算お見積り金額</dt>
                            <dd>&yen;<?php echo number_format($pve002Out->pre_estimate_price()); ?></dd>
                        </dl>
                        <dl>
                            <dt>エアコンの取り付け、取り外し</dt>
                            <dd><?php echo Sgmov_View_Pve_Common::_getAirconKbnNm($pve002Out->pre_aircon_exist()); ?></dd>
                        </dl>
                    </div>

<?php
        // キャンペーン情報が存在する場合、出力する
        echo Sgmov_View_Pve_Common::_createCampInfoHtml($pve002Out->pre_cam_discount_names(), $pve002Out->pre_cam_discount_contents(),
                    $pve002Out->pre_cam_discount_starts(), $pve002Out->pre_cam_discount_ends());
?>

                </div>
                <div style="display: none;">

<?php
    }
?>

                <div class="section">
                    <h3 class="cont_inner_title">1.お引越し先の間取りをお選びください。</h3>
                    <div class="dl_block">
                        <dl>
                            <dt>間取り</dt>
                            <dd><?php echo $pve002Out->course() ?></dd>
                        </dl>
                    </div>
                </div>
                <div class="section">
                    <h3 class="cont_inner_title">2.ご希望のお引越しプランをお選びください。</h3>
                    <div class="dl_block">
                        <dl>
                            <dt>お引越しプラン</dt>
                            <dd><?php echo $pve002Out->plan() ?></dd>
                        </dl>
                    </div>
                </div>

                <div class="section other">
                    <h3 class="cont_inner_title">3.その他のお引越し条件をお選びください。</h3>
                    <div class="dl_block">
                        <dl class="default_dl">
                            <dt id="add_now">現在お住まいの地域</dt>
                            <dd><?php echo $pve002Out->from_area() ?></dd>
                        </dl>
                        <dl>
                            <dt>お引越し先の地域</dt>
                            <dd><?php echo $pve002Out->to_area() ?></dd>
                        </dl>
                        <dl>
                            <dt>お引越し予定日</dt>
                            <dd><?php echo $pve002Out->move_date() ?></dd>
                        </dl>
<?php
/*

                        <dl>
                            <dt>エアコンの取り付け、取り外し</dt>
                            <dd><?php echo $pve002Out->aircon_exist() ?></dd>
                        </dl>

*/
?>
                    </div>
                </div>

<?php
    if ($pve002Out->pre_exist_flag() === '1') {
?>

                </div>

<?php
    }
?>
                <div class="section other">
                    <h3 class="cont_inner_title">4.詳細なお引越し情報を入力してください。</h3>
                    <div class="dl_block">

<?php
    if ($pve002Out->menu_personal() === 'mansion') {
?>
                        <dl>
                            <dt>マンション</dt>
                            <dd><?php echo $pve002Out->apartment_name(); ?></dd>
                        </dl>
<?php
    }
?>

                        <dl>
                            <dt id="corp_name">訪問お見積り希望日</dt>
                            <dd>
                                <ul>
                                    <li>第1希望日：<?php echo $pve002Out->visit_date1() ?></li>
                                    <li>第2希望日：<?php echo $pve002Out->visit_date2() ?></li>
                                </ul>
                            </dd>
                        </dl>
                    </div>

                    <h3 class="cont_inner_title">現在のお住まいについて</h3>
                    <div class="dl_block">
                        <dl>
                            <dt id="add_now">現住所</dt>
                            <dd>
                                〒<?php echo $pve002Out->cur_zip() ?>
                                <br /><?php echo $pve002Out->cur_address_all() ?>
                            </dd>
                        </dl>
                        <dl>
                            <dt>エレベーターの有無</dt>
                            <dd><?php echo $pve002Out->cur_elevator() ?></dd>
                        </dl>
                        <dl>
                            <dt>現在お住まいの階</dt>
                            <dd><?php echo Sgmov_View_Pve_Confirm::getTani($pve002Out->cur_floor(), "階", 1); ?></dd>
                        </dl>
                        <dl>
                            <dt>住居前道幅</dt>
                            <dd><?php echo $pve002Out->cur_road() ?></dd>
                        </dl>
                    </div>

                    <h3 class="cont_inner_title">お引越し先のお住まいについて</h3>
                    <div class="dl_block">
                        <dl>
                            <dt>新住所</dt>
                            <dd>
                                <?php echo Sgmov_View_Pve_Confirm::getTani($pve002Out->new_zip(), "〒", 0); ?>

                                <br /><?php echo $pve002Out->new_address_all() ?>

                        </dl>
                        <dl>
                            <dt>エレベーターの有無</dt>
                            <dd><?php echo $pve002Out->new_elevator() ?></dd>
                        </dl>
                        <dl>
                            <dt>新しいお住まいの階</dt>
                            <dd><?php echo Sgmov_View_Pve_Confirm::getTani($pve002Out->new_floor(), "階", 1); ?></dd>
                        </dl>
                        <dl>
                            <dt>住居前道幅</dt>
                            <dd><?php echo $pve002Out->new_road() ?></dd>
                        </dl>
                    </div>

                    <h3 class="cont_inner_title">お客様情報</h3>
                    <div class="dl_block">
                        <dl>
                            <dt>お名前</dt>
                            <dd><?php echo $pve002Out->name() ?></dd>
                        </dl>
                        <dl>
                            <dt>フリガナ</dt>
                            <dd><?php echo $pve002Out->furigana() ?></dd>
                        </dl>
                        <dl>
                            <dt>メールアドレス</dt>
                            <dd class="width_change"><?php echo $pve002Out->mail() ?></dd>
                        </dl>
                        <dl>
                            <dt>電話番号</dt>
                            <dd>
                                <ul>
                                    <li><?php echo $pve002Out->tel() ?></li>
                                    <li>
                                        <?php echo $pve002Out->tel_type() ?>

                                        <?php echo $pve002Out->tel_other() ?>

                                        <br /><?php echo $pve002Out->contact_available() ?>

                                    </li>
                                </ul>
                                <p>電話連絡可能時間帯</p>
                                <ul class="clearfix" id="time_zone">
                                    <li><?php echo Sgmov_View_Pve_Confirm::getTani($pve002Out->contact_start(), "～", 1); ?><?php echo $pve002Out->contact_end() ?></li>
                                </ul>
                            </dd>
                        </dl>
                        <dl>
                            <dt>備考欄</dt>
                            <dd class="width_change">
                                <?php echo $pve002Out->comment() ?>

                            </dd>
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
                        <a class="back" href="/pve/input/">修正する</a>
                    </div>

                    <div class="text_center comBtn02 btn01 fadeInUp animate">
                        <div class="btnInner">
                            <input id="submit_button" name="submit" type="submit" value="入力内容を送信する">
                        </div>
                    </div>
                    <input name="ticket" type="hidden" value="<?php echo $ticket ?>" />
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
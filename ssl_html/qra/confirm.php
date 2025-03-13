<?php

/**
 * コミケサービスのお申し込み確認画面を表示します。
 * @package    ssl_html
 * @subpackage RMS
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
// イベント識別子(URLのpath)はマジックナンバーで記述せずこの変数で記述してください
$dirDiv = Sgmov_Lib::setDirDiv(dirname(__FILE__));
Sgmov_Lib::useView($dirDiv . '/Confirm');

/**#@-*/


// 処理を実行
$view = new Sgmov_View_Qra_Confirm();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_qra001Out
 */
$qraOutForm = $forms['outForm'];


$dispItemInfo = $forms['dispItemInfo'];

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

<!DOCTYPE html>
<html dir="ltr" lang="ja-jp" xml:lang="ja-jp">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0" />
    <meta http-equiv="content-style-type" content="text/css" />
    <meta http-equiv="content-script-type" content="text/javascript" />
    <meta name="Keywords" content="" />
    <meta name="Description" content="クレジットカードお支払い受付サービスのお申し込みのご案内です。[旅行カバン等のお荷物承ります]フォームにもれなくご入力をお願いいたします。前日23時までにお支払の確認ができたお客様につきましては、翌日より集荷が可能となります。" />
    <meta name="author" content="SG MOVING Co.,Ltd" />
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved." />
    <title>クレジットカードお支払い受付サービスのお申し込み｜お問い合わせ│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css" rel="stylesheet" type="text/css" />
    <link href="/<?= $dirDiv ?>/css/eve.css" rel="stylesheet" type="text/css" />
    <!--[if lt IE 9]>
    <link href="/css/form_ie8.css" rel="stylesheet" type="text/css" />
    <![endif]-->
    <script src="/js/ga.js" type="text/javascript"></script>
</head>

<body>
    <?php
    $gnavSettings = 'contact';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/header.php';
    ?>
    <div id="breadcrumb">
        <ul class="wrap">
            <li><a href="/">ホーム</a></li>
            <li><a href="/contact/">お問い合わせ</a></li>
            <li class="current">クレジットカードお支払い受付サービスのお申し込み</li>
        </ul>
    </div>
    <div id="main">
        <div class="wrap clearfix">
            <h1 class="page_title">クレジットカードお支払い受付サービスのお申し込み</h1>
            <p class="sentence">
                ご入力内容をご確認のうえ、「入力内容を送信する」ボタンを押してください。
                <br />修正する場合は「修正する」ボタンを押してください。
            </p>


            <div class="section">
                <form action="/<?= $dirDiv ?>/complete" id="ev_form" data-feature-id="<?php echo Sgmov_View_Qra_Common::FEATURE_ID ?>" data-id="<?php echo Sgmov_View_Qra_Common::GAMEN_ID_QRA001 ?>" method="post">
                    <input name="ticket" type="hidden" value="<?php echo $ticket ?>" />
                    <input name="comiket_id" type="hidden" value="<?php echo $qraOutForm->comiket_id(); ?>" />

                    <div class="section">


                        <?php if ($qraOutForm->comiket_div() == Sgmov_View_Qra_Common::COMIKET_DEV_INDIVIDUA) : // 個人
                        ?>

                            <?php if ($qraOutForm->comiket_payment_method_cd_sel() == '2') : // クレジット
                            ?>

                                <h4 class="table_title">クレジットお支払い情報</h4>
                                <div class="dl_block">
                                    <dl>
                                        <dt>合計金額</dt>
                                        <dd>
                                            ￥<?php echo number_format($qraOutForm->delivery_charge()) . PHP_EOL; ?>
                                            <?php if (intval($qraOutForm->repeater_discount()) > 0) : ?>
                                                <span class="f80">※リピータ割引（<?php echo number_format($qraOutForm->repeater_discount()); ?>円）が適用されました</span>
                                            <?php endif; ?>
                                        </dd>
                                    </dl>
                                    <dl>
                                        <dt>有効期限</dt>
                                        <dd>
                                            <?php echo $qraOutForm->card_expire_year_cd_sel(); ?>年<?php echo $qraOutForm->card_expire_month_cd_sel(); ?>月
                                        </dd>
                                    </dl>
                                    <dl>
                                        <dt>カード番号</dt>
                                        <dd>
                                            <?php echo str_repeat('*', strlen($qraOutForm->card_number()) - 4) . substr($qraOutForm->card_number(), -4) . PHP_EOL; ?>
                                            <span class="f80">※下4桁のみの表示となります</span>
                                        </dd>
                                    </dl>
                                    <dl>
                                        <dt>セキュリティコード</dt>
                                        <dd><?php echo $qraOutForm->security_cd(); ?></dd>
                                    </dl>
                                    <dl>
                                        <dt>お支払い方法</dt>
                                        <dd>1回</dd>
                                    </dl>
                                </div>

                                <div class="btn_area">
                                    <a class="back" href="/<?= $dirDiv ?>/credit_card/">修正する</a>
                                    <input id="submit_button" type="submit" name="submit" value="入力内容を送信する" />
                                </div>

                            <?php endif; ?>

                        <?php else : ?>
                            <div class="btn_area">
                                <a class="back" href="/<?= $dirDiv ?>/<?php echo $dispItemInfo['back_input_path']; ?>">修正する</a>
                                <input id="submit_button" type="submit" name="submit" value="入力内容を送信する" />
                            </div>
                        <?php endif; ?>


                </form>
                <!--main-->

                <?php
                $footerSettings = 'under';
                include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/footer.php';
                ?>

                <script charset="UTF-8" type="text/javascript" src="/js/jquery-2.2.0.min.js"></script>
                <script charset="UTF-8" type="text/javascript" src="/js/lodash.min.js"></script>
                <!--<![endif]-->
                <script charset="UTF-8" type="text/javascript" src="/common/js/multi_send.js"></script>
                <script charset="UTF-8" type="text/javascript" src="/js/common.js"></script>
                <script charset="UTF-8" type="text/javascript" src="/js/smooth_scroll.js"></script>
                <script charset="UTF-8" type="text/javascript" src="/common/js/ajax_searchaddress.js"></script>
                <script charset="UTF-8" type="text/javascript" src="/common/js/jquery.ah-placeholder.js"></script>
                <script>
                    $(function() {
                        $('input[name="submit"]').on('click', function() {
                            if (!multiSend.block()) {
                                return false;
                            }
                            let s_tel = "<?php echo $qraOutForm->comiket_detail_inbound_tel(); ?>";
                            $("<input>", {
                                type: "hidden",
                                name: "comiket_staff_tel",
                                value: s_tel
                            }).appendTo("#ev_form");
                            $('form').first().submit();
                        });
                    });
                </script>
                <script>
                    $(function() {
                        function dispAttentionEventOnly() {
                            var eventId = $('input[name="event_sel"]').val();
                            var g_fifoVal = 500;
                            if (eventId == '2') { // イベント = コミケ
                                $('.disp_comiket').show(g_fifoVal);
                                $('.disp_design').hide(g_fifoVal);
                                $('.disp_gooutcamp').hide(g_fifoVal);
                            } else if (eventId == '1' || eventId == '3') {
                                $('.disp_comiket').hide(g_fifoVal);
                                $('.disp_design').show(g_fifoVal);
                                $('.disp_gooutcamp').hide(g_fifoVal);
                            } else if (eventId == '4') {
                                $('.disp_comiket').hide(g_fifoVal);
                                $('.disp_design').hide(g_fifoVal);
                                $('.disp_gooutcamp').show(g_fifoVal);
                            } else {
                                $('.disp_comiket').hide(g_fifoVal);
                                $('.disp_design').hide(g_fifoVal);
                                $('.disp_gooutcamp').hide(g_fifoVal);
                                $('.disp_etc').show(g_fifoVal);
                            }
                        }

                        dispAttentionEventOnly();
                    });
                </script>
</body>

</html>

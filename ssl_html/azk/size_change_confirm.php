<?php

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('azk/SizeChangeConfirm');

/**#@-*/

// 処理を実行
$view = new Sgmov_View_Azk_SizeChangeConfirm();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Eve001Out
 */
$eveOutForm = $forms['outForm'];

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
    <meta name="Description" content="催事・イベント手荷物お預かりサービスのお申し込み（個人用）のご案内です。[旅行カバン等のお荷物承ります]フォームにもれなくご入力をお願いいたします。前日23時までにお支払の確認ができたお客様につきましては、翌日より集荷が可能となります。" />
    <meta name="author" content="SG MOVING Co.,Ltd" />
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved." />
    <title>催事・イベント手荷物預かりサービスの個数・サイズ変更お申し込み｜お問い合わせ│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css" rel="stylesheet" type="text/css" />
    <link href="/azk/css/eve.css" rel="stylesheet" type="text/css" />
    <script src="/js/ga.js" type="text/javascript"></script>
</head>
<body>
<?php
    $gnavSettings = 'contact';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/header.php';
?>
    <!-- <div id="breadcrumb">
        <ul class="wrap">
            <li><a href="/">ホーム</a></li>
            <li><a href="/contact/">お問い合わせ</a></li>
            <li class="current">催事・イベント手荷物お預かりサービスの個数・サイズ変更お申し込み</li>
        </ul>
    </div> -->
    <div id="main">
        <div class="wrap clearfix">
            <h1 class="page_title">催事・イベント手荷物預かりサービスの個数・サイズ変更お申し込み</h1>
            <p class="sentence">
                <?php if (@empty($dispItemInfo['is_cancel'])) : ?>
                    ご入力内容をご確認のうえ、「入力内容を送信する」ボタンを押してください。
                    <br />修正する場合は「修正する」ボタンを押してください。
                <?php else: ?>
                    <strong class="red" style="font-size: 1.5em;">入力画面の、宅配数量の合計が "0" でしたので、以下の内容をキャンセルします。</strong>
                    <br><br>ご入力内容をご確認のうえ、「キャンセル送信する」ボタンを押してください。
                    <br />修正する場合は「修正する」ボタンを押してください。
                <?php endif; ?>
            </p>


            <div class="section">
                <form action="/azk/size_change_complete" data-feature-id="<?php echo Sgmov_View_Azk_Common::FEATURE_ID ?>" data-id="<?php echo Sgmov_View_Azk_Common::GAMEN_ID_AZK001 ?>" method="post">
                    <input name="ticket" type="hidden" value="<?php echo $ticket ?>" />
                    <input name="comiket_id" type="hidden" value="<?php echo $eveOutForm->comiket_id(); ?>" />

                    <div class="section">

                        <div class="dl_block comiket_block">
                            <dl>
                                <dt id="event_sel" style=" border-bottom: solid 1px #ccc !important;">
                                    出展イベント
                                </dt>
                                <dd>
                                    <table>
                                        <tr>
                                            <td class="event_eventsub_td_name" style="">
<?php
        $eventName = Sgmov_View_Azk_Confirm::_getLabelSelectPulldownData($eveOutForm->event_cds(), $eveOutForm->event_lbls(), $eveOutForm->event_cd_sel());
        echo $eventName;
?>
                                                &nbsp;
                                                <input type="hidden" name="event_sel" value="<?php echo $eveOutForm->event_cd_sel(); ?>">
                                                <span  class="eventsub_sel">
<?php
        $eventsubName = Sgmov_View_Azk_Confirm::_getLabelSelectPulldownData($eveOutForm->eventsub_cds(), $eveOutForm->eventsub_lbls(), $eveOutForm->eventsub_cd_sel());
        echo $eventsubName;
?>
                                                    </span>
                                                </td>
                                            <td class="event_eventsub_td_dl_item" style=""></td>
                                        </tr>
                                    </table>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="eventsub_address">
                                    会場名
                                </dt>
                                <dd>
                                    <span class="event-place-lbl">
                                        <?php
                                            $selectedEventData = $dispItemInfo["eventsub_selected_data"];
                                            echo $selectedEventData["venue"];
                                        ?>
                                    </span>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="eventsub_term" style=" border-bottom: solid 1px #ccc !important;">
                                    期間
                                </dt>
                                <dd>
                                    <?php if($eveOutForm->eventsub_term_fr_nm() == $eveOutForm->eventsub_term_to_nm()):?>
                                        <span class="event-term_fr-lbl"><?php echo $eveOutForm->eventsub_term_fr_nm(); ?></span>
                                    <?php else: ?>
                                        <span class="event-term_fr-lbl"><?php echo $eveOutForm->eventsub_term_fr_nm(); ?></span>
                                        <span class="event-term_fr-str">&nbsp;から&nbsp;</span>
                                        <span class="event-term_to-lbl"><?php echo $eveOutForm->eventsub_term_to_nm(); ?></span>
                                    <?php endif; ?>
                                </dd>
                            </dl>
                            <dl style="display: none;">
                                <dt id="comiket_div">
                                    識別
                                </dt>
                                <dd>
                                    <?php foreach($dispItemInfo['comiket_div_lbls'] as $key => $val) : ?>
                                        <?php if ($eveOutForm->comiket_div() == $key) : ?>
                                            <?php if($eveOutForm->event_cd_sel() == '2') :  // コミケの場合?>
                                                <?php $val = '電子決済の方(クレジット、コンビニ決済、電子マネー)'; ?>
                                                <?php if($eveOutForm->comiket_div() == Sgmov_View_Azk_Common::COMIKET_DEV_BUSINESS) : ?>
                                                <?php $val = '請求書にて請求'; ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <?php echo $val; ?>
                                            <?php break; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_personal_name">
                                    お申込者
                                </dt>
                                <dd>
                                    <span class="comiket_personal_name_sei-lbl"><?php echo $eveOutForm->comiket_personal_name_sei();?></span>
                                    <span class="comiket_personal_name_mei-lbl"><?php echo $eveOutForm->comiket_personal_name_mei();?></span>
                                </dd>
                            </dl> 
                          <dl>
                                <dt id="comiket_tel" style=" border-bottom: solid 1px #ccc !important;">
                                    電話番号
                                </dt>
                                <dd>
                                    <span class="comiket_tel-lbl"><?php echo $eveOutForm->comiket_tel();?></span>
                                </dd>
                            </dl> 
                            <dl>
                                <dt id="comiket_mail">
                                    メールアドレス
                                </dt>
                                <dd>
                                    <?php echo $eveOutForm->comiket_mail();?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_name">
                                    取り出し回数
                                </dt>
                                <dd>
                                    <?php foreach($dispItemInfo['comiket_detail_azukari_kaisu_type_lbls'] as $key => $val) : ?>
                                        <?php if ($eveOutForm->comiket_detail_azukari_kaisu_type_sel() == $key): ?>
                                            <?php echo $val; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_collect_date">
                                    利用日
                                </dt>
                                <dd>
                                   <?php echo $eveOutForm->comiket_detail_collect_date_year_sel(); ?>年<?php echo $eveOutForm->comiket_detail_collect_date_month_sel(); ?>月<?php echo $eveOutForm->comiket_detail_collect_date_day_sel(); ?>日
                                    （<?php echo Sgmov_View_Azk_Confirm::_getWeek($eveOutForm->comiket_detail_collect_date_year_sel(), $eveOutForm->comiket_detail_collect_date_month_sel(), $eveOutForm->comiket_detail_collect_date_day_sel()); ?>）
                                    &nbsp;
                                </dd>
                            </dl>
                            <dl class="service-outbound-item" service-id="1">
                                <dt id="comiket_box_num_ary">
                                    手荷物数量
                                </dt>
                                <dd>
                                    <?php if (@empty($dispItemInfo['is_cancel'])) : ?>
                                        <table>
                                        <?php foreach($dispItemInfo['comiket_box_lbls'] as $key => $val) : ?>
                                            <?php $boxNum = $eveOutForm->comiket_box_num_ary($val["id"]); ?>
                                            <?php if(!empty($boxNum)) : ?>
                                                <tr>
                                                    <td class='comiket_box_item_name'>
                                                        <?php echo empty($val["name"]) ? "" : $val["name"]; ?>&nbsp;
                                                    </td>
                                                    <td class='comiket_box_item_value'>
                                                        <?php echo $boxNum;?>個
                                                        &nbsp;
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                        </table>
                                    <?php else: ?>
                                         <table>
                                            <?php foreach($dispItemInfo['box'] as $key => $val) : ?>
                                                <tr>
                                                    <td class='comiket_box_item_name'>
                                                        <?php echo empty($val["name"]) ? "" : $val["name"]; ?>&nbsp;
                                                    </td>
                                                    <td class='comiket_box_item_value'>
                                                        <?php echo $val["num"];?>個
                                                        &nbsp;
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </table>
                                    <?php endif; ?>
                                </dd>
                            </dl>
                               <!-- 
                                <dl>
                                    <dt id="comiket_detail_note">
                                        備考
                                    </dt>
                                    <dd>
                                        <?php // echo nl2br($eveOutForm->comiket_detail_note()); ?>
                                        <?php echo $eveOutForm->comiket_detail_note1(); ?><br/>
                                        <?php echo $eveOutForm->comiket_detail_note2(); ?>
                                    </dd>
                                </dl> -->
                            </div>
                    </div>

        <!--▼お支払い方法-->
    <?php if ($eveOutForm->comiket_payment_method_cd_sel() === '1') : ?>
                      <!--   <h4 class="table_title">コンビニお支払い情報</h4>
                        <div class="dl_block">
                                <dl>
                                    <dt>お支払い総額</dt>
                                    <dd>￥<?php echo number_format($eveOutForm->delivery_charge()); ?></dd>
                                </dl>
                                <dl>
                                    <dt>お支払い店舗</dt>
                                    <dd>
                                        <?php if ($eveOutForm->comiket_convenience_store_cd_sel() === '1') : ?>
                                            セブンイレブン
                                        <?php elseif ($eveOutForm->comiket_convenience_store_cd_sel() === '2'): ?>
                                            ローソン、セイコーマート、ファミリーマート、ミニストップ
                                        <?php elseif ($eveOutForm->comiket_convenience_store_cd_sel() === '3'): ?>
                                            デイリーヤマザキ
                                        <?php endif; ?>
                                    </dd>
                                </dl>
                        </div>

                        <div class="btn_area">
                            <a class="back" href="/azk/<?php echo $dispItemInfo['back_input_path']; ?>/">修正する</a>
                            <?php if (@empty($dispItemInfo['is_cancel'])) : ?>
                                <input id="submit_button" type="submit" name="submit" value="入力内容を送信する" />
                            <?php else: ?>
                                <input id="submit_button" type="submit" name="submit" value="キャンセル送信する" />
                            <?php endif; ?>
                        </div>
 -->
    <?php endif; ?>


    <?php if ($eveOutForm->comiket_payment_method_cd_sel() == '2') : // クレジット ?>
                        <div class="btn_area">
                            <a class="back" href="/azk/<?php echo $dispItemInfo['back_input_path']; ?>/"> 修正する</a>
                        </div>

                        <h4 class="table_title">クレジットお支払い情報</h4>
                        <div class="dl_block">
                            <dl>
                                <dt>お支払い総額</dt>
                                <dd>
                                    ￥<?php echo number_format($eveOutForm->delivery_charge()).PHP_EOL; ?>
                                    <?php if (intval($eveOutForm->repeater_discount()) > 0) : ?>
                                        <span class="f80">※リピータ割引（<?php echo number_format($eveOutForm->repeater_discount()); ?>円）が適用されました</span>
                                    <?php endif; ?>
                                </dd>
                            </dl>
                            <?php if (@empty($dispItemInfo['is_cancel'])) : ?>
                                <dl>
                                    <dt>有効期限</dt>
                                    <dd>
                                        <?php echo $eveOutForm->card_expire_year_cd_sel(); ?>年<?php echo $eveOutForm->card_expire_month_cd_sel(); ?>月
                                    </dd>
                                </dl>
                                <dl>
                                    <dt>カード番号</dt>
                                    <dd>
                                        <?php echo str_repeat('*', strlen($eveOutForm->card_number())-4).substr($eveOutForm->card_number(), -4) .PHP_EOL; ?>
                                        <span class="f80">※下4桁のみの表示となります</span>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt>セキュリティコード</dt>
                                    <dd><?php echo $eveOutForm->security_cd(); ?></dd>
                                </dl>
                                <dl>
                                    <dt>お支払い方法</dt>
                                    <dd>1回</dd>
                                </dl>
                            <?php endif; ?>
                        </div>

                        <div class="btn_area">
                            
                            <?php if (@empty($dispItemInfo['is_cancel'])) : ?>
                                <a class="back" href="/azk/size_change_credit_card/">修正する</a>
                                <input id="submit_button" type="submit" name="submit" value="入力内容を送信する" />
                            <?php else: ?>
                                <input id="submit_button" type="submit" name="submit" value="キャンセル送信する" />
                            <?php endif; ?>
                        </div>

    <?php endif; ?>

    <?php if ($eveOutForm->comiket_payment_method_cd_sel() == '3') : // 電子マネー ?>
       <h4 class="table_title">電子マネー情報</h4>
        <div class="dl_block">
            <dl>
                <dt>お支払い総額（仕分け特別料金含む）</dt>
                <dd>￥<?php echo number_format($eveOutForm->delivery_charge()); ?></dd>
            </dl>
        </div>
       <strong class="red">※電子マネーでのお支払いは、￥10,000を超える場合はお取り扱い出来ません。</strong>
        <div class="btn_area">
            <a class="back" href="/azk/size_change/">修正する</a>
            <?php if (@empty($dispItemInfo['is_cancel'])) : ?>
                <input id="submit_button" type="submit" name="submit" value="入力内容を送信する" />
            <?php else: ?>
                <input id="submit_button" type="submit" name="submit" value="キャンセル送信する" />
            <?php endif; ?>
        </div>
    <?php endif; ?>

                </form>
            </div>
        </div>
    </div>
    <!--main-->

<?php
    $footerSettings = 'under';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/footer.php';
?>

    <script charset="UTF-8" type="text/javascript" src="/js/jquery-2.2.0.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/lodash.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/multi_send.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/common.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/smooth_scroll.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/ajax_searchaddress.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/jquery.ah-placeholder.js"></script>

    <script>
        $(function() {
            $('input[name="submit"]').on('click', function () {
                if (!multiSend.block()) {
                    return false;
                }
                $('form').first().submit();
            });
        });
    </script>
    <script>
        $(function() {
            function dispAttentionEventOnly() {
                var eventId = $('input[name="event_sel"]').val();
                var g_fifoVal = 500;
                if(eventId == '2') { // イベント = コミケ
                    $('.disp_comiket').show(g_fifoVal);
                    $('.disp_design').hide(g_fifoVal);
                    $('.disp_gooutcamp').hide(g_fifoVal);
                } else if(eventId == '1' || eventId == '3') {
                    $('.disp_comiket').hide(g_fifoVal);
                    $('.disp_design').show(g_fifoVal);
                    $('.disp_gooutcamp').hide(g_fifoVal);
                } else if(eventId == '4') {
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


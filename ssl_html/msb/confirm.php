<?php
/**
 * コミケサービスのお申し込み確認画面を表示します。
 * @package    ssl_html
 * @subpackage MSB
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('msb/Confirm');

/**#@-*/

// 処理を実行
$view = new Sgmov_View_Msb_Confirm();
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
    <meta name="Description" content="催事・イベント配送受付サービスのお申し込み（個人用）のご案内です。[旅行カバン等のお荷物承ります]フォームにもれなくご入力をお願いいたします。前日23時までにお支払の確認ができたお客様につきましては、翌日より集荷が可能となります。" />
    <meta name="author" content="SG MOVING Co.,Ltd" />
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved." />
    <title>催事・イベント配送受付サービスのお申し込み（個人用）｜お問い合わせ│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css" rel="stylesheet" type="text/css" />
    <link href="/msb/css/eve.css" rel="stylesheet" type="text/css" />
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
            <li class="current">催事・イベント配送受付サービスのお申し込み</li>
        </ul>
    </div>
    <div id="main">
        <div class="wrap clearfix">
            <h1 class="page_title">催事・イベント配送受付サービスのお申し込み</h1>
            <p class="sentence">
                ご入力内容をご確認のうえ、「入力内容を送信する」ボタンを押してください。
                <br />修正する場合は「修正する」ボタンを押してください。
            </p>


            <div class="section">
                <form action="/msb/complete" data-feature-id="<?php echo Sgmov_View_Msb_Common::FEATURE_ID ?>" data-id="<?php echo Sgmov_View_Msb_Common::GAMEN_ID_MSB001 ?>" method="post">
                    <input name="ticket" type="hidden" value="<?php echo $ticket ?>" />
                    <input name="comiket_id" type="hidden" value="<?php echo $eveOutForm->comiket_id(); ?>" />

                    <div class="section">

                        <div class="dl_block comiket_block">
                            <dl>
                                <dt id="event_sel">
                                    出展イベント
                                </dt>
                                <dd>
                                        <table>
                                            <tr>
                                                <td class="event_eventsub_td_name" style="">
<?php
        $eventName = Sgmov_View_Msb_Confirm::_getLabelSelectPulldownData($eveOutForm->event_cds(), $eveOutForm->event_lbls(), $eveOutForm->event_cd_sel());
        echo $eventName;
?>
                                                    &nbsp;
                                                    <input type="hidden" name="event_sel" value="<?php echo $eveOutForm->event_cd_sel(); ?>">
                                                    <span  class="eventsub_sel">
<?php
        $eventsubName = Sgmov_View_Msb_Confirm::_getLabelSelectPulldownData($eveOutForm->eventsub_cds(), $eveOutForm->eventsub_lbls(), $eveOutForm->eventsub_cd_sel());
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
                                <dt id="eventsub_term">
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
                                                <?php if($eveOutForm->comiket_div() == Sgmov_View_Msb_Common::COMIKET_DEV_BUSINESS) : ?>
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
                                <dt id="comiket_zip">
                                    郵便番号
                                </dt>

                                <dd>
                                    〒<span class="comiket_zip1-lbl"><?php echo $eveOutForm->comiket_zip1();?></span>
                                    <span class="comiket_zip1-str">
                                        -
                                    </span>
                                    <span class="comiket_zip2-lbl"><?php echo $eveOutForm->comiket_zip2();?></span>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_pref">
                                    都道府県
                                </dt>
                                <dd>
                                    <span class="comiket_pref_nm-lbl"><?php echo $eveOutForm->comiket_pref_nm();?></span>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_address">
                                    市区町村
                                </dt>
                                <dd>
                                    <span class="comiket_address-lbl"><?php echo $eveOutForm->comiket_address();?></span>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_building">
                                    番地・建物名・部屋番号
                                </dt>
                                <dd>
                                    <span class="comiket_building-lbl"><?php echo $eveOutForm->comiket_building();?></span>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_tel">
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
                                    <br>
                                    <p class="red">
                                        ※迷惑メールを設定されている方は「sagawa-mov.co.jp」を受信する設定にしてください。
                                        <br />詳しくは
                                        <a href="#bounce_mail">こちら</a>
                                    </p>
                                </dd>
                            </dl>
                               
                            <dl>
                                <dt id="comiket_staff_seimei">
                                    当日の担当者名
                                </dt>
                                <dd>
                                    <?php echo $eveOutForm->comiket_staff_sei() ?>&nbsp;<?php echo $eveOutForm->comiket_staff_mei() ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_staff_seimei_furi">
                                    当日の担当者名（フリガナ）
                                </dt>
                                <dd>
                                    <?php echo $eveOutForm->comiket_staff_sei_furi() ?>&nbsp;<?php echo $eveOutForm->comiket_staff_mei_furi() ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_staff_tel">
                                    当日の担当者電話番号
                                </dt>
                                <dd>
                                    <?php echo $eveOutForm->comiket_staff_tel();?>
                                </dd>
                            </dl>
                        </div>

<?php /**********************************************************************************************************************/ ?>
                        <div style="" class="input-outbound input-outbound-title">手荷物預かり</div>
<?php /**********************************************************************************************************************/ ?>
                        <div class="dl_block input-outbound comiket_block">
                            <dl>
                                <dt id="comiket_detail_name">
                                    集荷先名
                                </dt>
                                <dd>
                                    <?php echo $eveOutForm->comiket_detail_name() ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_zip">
                                    集荷先郵便番号
                                </dt>
                                <dd>
                                    〒<?php echo $eveOutForm->comiket_detail_zip1();?>
                                    -
                                    <?php echo $eveOutForm->comiket_detail_zip2();?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_pref">
                                    集荷先都道府県
                                </dt>
                                <dd>
                                    <?php
                                        echo Sgmov_View_Msb_Confirm::_getLabelSelectPulldownData($eveOutForm->comiket_detail_pref_cds(), $eveOutForm->comiket_detail_pref_lbls(), $eveOutForm->comiket_detail_pref_cd_sel());
                                    ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_address">
                                    集荷先市区町村
                                </dt>
                                <dd>
                                    <?php echo $eveOutForm->comiket_detail_address();?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_building">
                                    集荷先番地・建物名・部屋番号
                                </dt>
                                <dd>
                                    <?php echo $eveOutForm->comiket_detail_building();?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_tel">
                                    集荷先TEL
                                </dt>
                                <dd>
                                    <?php echo $eveOutForm->comiket_detail_tel();?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_note">
                                    備考
                                </dt>
                                <dd>
                                    <?php echo $eveOutForm->comiket_detail_note1(); ?><br/>
                                    <?php echo $eveOutForm->comiket_detail_note2(); ?>
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <!--▼お支払い方法-->
                    <?php if ($eveOutForm->comiket_payment_method_cd_sel() === '1') : ?>
                        <h4 class="table_title">コンビニお支払い情報</h4>
                        <div class="dl_block">
                                <dl>
                                    <dt>お支払い総額（仕分け特別料金含む）</dt>
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
                            <a class="back" href="/msb/<?php echo $dispItemInfo['back_input_path']; ?>/">修正する</a>
                            <input id="submit_button" type="submit" name="submit" value="入力内容を送信する" />
                        </div>
                    <?php endif; ?>

                    <?php if ($eveOutForm->comiket_payment_method_cd_sel() == '2') : // クレジット ?>
                        <div class="btn_area">
                            <a class="back" href="/msb/<?php echo $dispItemInfo['back_input_path']; ?>/"> 修正する</a>
                        </div>

                        <h4 class="table_title">クレジットお支払い情報</h4>
                        <div class="dl_block">
                            <dl>
                                <dt>お支払い総額（仕分け特別料金含む）</dt>
                                <dd>
                                    ￥<?php echo number_format($eveOutForm->delivery_charge()).PHP_EOL; ?>
                                    <?php if (intval($eveOutForm->repeater_discount()) > 0) : ?>
                                        <span class="f80">※リピータ割引（<?php echo number_format($eveOutForm->repeater_discount()); ?>円）が適用されました</span>
                                    <?php endif; ?>
                                </dd>
                            </dl>
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
                        </div>

                        <div class="btn_area">
                            <a class="back" href="/msb/credit_card/">修正する</a>
                            <input id="submit_button" type="submit" name="submit" value="入力内容を送信する" />
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
                            <a class="back" href="/msb/<?php echo $dispItemInfo['back_input_path']; ?>">修正する</a>
                            <input id="submit_button" type="submit" name="submit" value="入力内容を送信する" />
                        </div>
                    <?php endif; ?>
                </form>
                <div class="attention_area">
                    <?php
                        ///////////////////////////////////////////////
                        // 迷惑メールエリア
                        ///////////////////////////////////////////////
                        include_once dirname(__FILE__) . '/parts/input_attention_area_spammail.php';
                    ?>
                </div>
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
    <!--<![endif]-->
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


<?php
/**
 * コミケサービスのお申し込み確認画面を表示します。
 * @package    ssl_html
 * @subpackage BPN
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('bpn/SizeChangeConfirm');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Bpn_SizeChangeConfirm();
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
$bpnOutForm = $forms['outForm'];

$dispItemInfo = $forms['dispItemInfo'];


$screen = "";
if($bpnOutForm->bpn_type() == "2"){
    $screen = "当日";
}


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
    <meta name="Description" content="<?php echo $screen;?>物販受付サービスのお申し込み（個人用）のご案内です。[旅行カバン等のお荷物承ります]フォームにもれなくご入力をお願いいたします。前日23時までにお支払の確認ができたお客様につきましては、翌日より集荷が可能となります。" />
    <meta name="author" content="SG MOVING Co.,Ltd" />
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved." />
    <?php if(empty($screen)):?>
        <title>卓上飛沫ブロッカーの数量変更お申し込み│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
    <?php else:?>
        <title><?php echo $screen;?>物販受付サービスの数量変更お申し込み│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
    <?php endif;?>
    
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css" rel="stylesheet" type="text/css" />
    <link href="/bpn/css/bpn.css" rel="stylesheet" type="text/css" />
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
            <?php if(empty($screen)):?>
                <li class="current">卓上飛沫ブロッカーの数量変更お申し込み</li>
            <?php else:?>
                <li class="current"><?php echo $screen;?>物販受付サービスの数量変更お申し込み</li>
            <?php endif;?>
        </ul>
    </div>
    <div id="main">
        <div class="wrap clearfix">
            <?php if(empty($screen)):?>
                <h1 class="page_title">卓上飛沫ブロッカーの数量変更お申し込み</h1>
            <?php else:?>
                <h1 class="page_title"><?php echo $screen;?>物販受付サービスの数量変更お申し込み</h1>
            <?php endif;?>
            
            <p class="sentence">
                <?php if (@empty($dispItemInfo['is_cancel'])) : ?>
                    ご入力内容をご確認のうえ、「入力内容を送信する」ボタンを押してください。
                    <br />修正する場合は「修正する」ボタンを押してください。
                <?php else: ?>
                    <strong class="red" style="font-size: 1.7em;">入力画面の、商品数量の合計が "0" でしたので、以下の内容をキャンセルします。</strong>
                    <br><br>ご入力内容をご確認のうえ、「キャンセル送信する」ボタンを押してください。
                    <br />修正する場合は「修正する」ボタンを押してください。
                <?php endif; ?>
            </p>

            <div class="section">
                <form action="/bpn/size_change_complete" data-feature-id="<?php echo Sgmov_View_Bpn_Common::FEATURE_ID ?>" data-id="<?php echo Sgmov_View_Bpn_Common::GAMEN_ID_BPN001 ?>" method="post">
                    <input name="ticket" type="hidden" value="<?php echo $ticket ?>" />
                    <input name="comiket_id" type="hidden" value="<?php echo $bpnOutForm->comiket_id(); ?>" />

                    <div class="section">

                        <div class="dl_block comiket_block">
                            <dl>
                                <dt id="event_sel">
                                    出展イベント
                                </dt>
                                <dd<?php
                                    if (isset($e) && ($e->hasErrorForId('event_sel'))) {
                                        echo ' class="form_error"';
                                    }
                                ?>>
                                    <!--<div class="comiket_event_eventsub_select" style='padding-top: 20px;'>-->
                                        <table>
                                            <tr>
                                                <td class="event_eventsub_td_name" style="">
<?php
        $eventName = Sgmov_View_Bpn_Confirm::_getLabelSelectPulldownData($bpnOutForm->event_cds(), $bpnOutForm->event_lbls(), $bpnOutForm->event_cd_sel());
        echo $eventName;
?>
                                    &nbsp;
                                    <input type="hidden" name="event_sel" value="<?php echo $bpnOutForm->event_cd_sel(); ?>">
                                    <span  class="eventsub_sel">
<?php
        $eventsubName = Sgmov_View_Bpn_Confirm::_getLabelSelectPulldownData($bpnOutForm->eventsub_cds(), $bpnOutForm->eventsub_lbls(), $bpnOutForm->eventsub_cd_sel());
        echo $eventsubName;
?>
                                    </span>
                                   
                                                </td>
                                                <td class="event_eventsub_td_dl_item" style="">
                                   
                                                </td>
                                            </tr>
                                        </table>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="eventsub_address">
                                    会場名
                                </dt>
                                <dd<?php
                                    if (isset($e)
                                       && ($e->hasErrorForId('eventsub_address'))
                                    ) {
                                        echo ' class="form_error"';
                                    }
                                ?>>
                                    <span class="event-place-lbl">
                                        <?php
                                            $selectedEventData = $dispItemInfo["eventsub_selected_data"];
                                            echo $selectedEventData["venue"];
                                        ?>
                                        <?php /* $eventsubZip = $bpnOutForm->eventsub_zip(); ?>
                                        <?php if(!empty($eventsubZip)) : ?>
                                            〒<?php  echo substr($eventsubZip, 0, 3); ?>-<?php  echo substr($eventsubZip, 3); ?>&nbsp;
                                        <?php endif; ?>
                                        <?php echo $bpnOutForm->eventsub_address(); */ ?>
                                    </span>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="eventsub_term">
                                    期間
                                </dt>
                                <dd<?php
                                    if (isset($e)
                                       && ($e->hasErrorForId('eventsub_term_fr') || $e->hasErrorForId('eventsub_term_to'))
                                    ) {
                                        echo ' class="form_error"';
                                    }
                                ?>>
                                <?php if($bpnOutForm->eventsub_term_fr_nm() == $bpnOutForm->eventsub_term_to_nm()):?>
                                    <span class="event-term_fr-lbl"><?php echo $bpnOutForm->eventsub_term_fr_nm(); ?></span>
                                <?php else: ?>
                                    <span class="event-term_fr-lbl"><?php echo $bpnOutForm->eventsub_term_fr_nm(); ?></span>
                                    <span class="event-term_fr-str">&nbsp;から&nbsp;</span>
                                    <span class="event-term_to-lbl"><?php echo $bpnOutForm->eventsub_term_to_nm(); ?></span>
                                <?php endif; ?>
                                </dd>
                            </dl>
                        
                        <?php if($dispItemInfo["bpnType"] == "1") : // 物販?>
                            <?php if ($bpnOutForm->comiket_div() == Sgmov_View_Bpn_Common::COMIKET_DEV_BUSINESS) : // 法人 and 顧客コードを使用する ?>
                                <dl>
                                    <dt id="comiket_customer_cd">
                                        顧客コード
                                    </dt>
                                    <dd<?php
                                        if (isset($e)
                                           && ($e->hasErrorForId('comiket_customer_cd'))
                                        ) {
                                            echo ' class="form_error"';
                                        }
                                    ?>>
                                            <?php echo $bpnOutForm->comiket_customer_cd() ?>
                                    </dd>
                                </dl>

                                <dl>
                                    <dt id="office_name">
                                        お申込者
                                    </dt>
                                    <dd<?php
                                        if (isset($e)
                                           && ($e->hasErrorForId('office_name'))
                                        ) {
                                            echo ' class="form_error"';
                                        }
                                    ?>>
                                    <span class="office_name-lbl"><?php echo $bpnOutForm->office_name();?></span>
                                    </dd>
                                </dl>
                            <?php else : // 個人 ?>
                                <dl>
                                    <dt id="comiket_personal_name">
                                        お申込者
                                    </dt>
                                    <dd<?php
                                        if (isset($e)
                                           && ($e->hasErrorForId('comiket_personal_name'))
                                        ) {
                                            echo ' class="form_error"';
                                        }
                                    ?>>
                                    <span class="comiket_personal_name_sei-lbl"><?php echo $bpnOutForm->comiket_personal_name_sei();?></span>
                                    <span class="comiket_personal_name_mei-lbl"><?php echo $bpnOutForm->comiket_personal_name_mei();?></span>
<!--                                    <br/>
                                    <br/>
                                    <strong class="red">※ 法人の場合は、姓のみです。</strong>-->
                                    </dd>
                                </dl>
                            <?php endif; ?>
                            <dl>
                                <dt id="comiket_zip">
                                    郵便番号
                                </dt>

                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_zip')) { echo ' class="form_error"'; } ?>>
                                    〒<span class="comiket_zip1-lbl"><?php echo $bpnOutForm->comiket_zip1();?></span>
                                    <span class="comiket_zip1-str">
                                        -
                                    </span>
                                    <span class="comiket_zip2-lbl"><?php echo $bpnOutForm->comiket_zip2();?></span>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_pref">
                                    都道府県
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_pref')) { echo ' class="form_error"'; } ?>>
                                    <span class="comiket_pref_nm-lbl"><?php echo $bpnOutForm->comiket_pref_nm();?></span>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_address">
                                    市区町村
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_address')) { echo ' class="form_error"'; } ?>>
                                    <span class="comiket_address-lbl"><?php echo $bpnOutForm->comiket_address();?></span>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_building">
                                    番地・建物名・部屋番号
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_building')) { echo ' class="form_error"'; } ?>>
                                    <span class="comiket_building-lbl"><?php echo $bpnOutForm->comiket_building();?></span>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_tel">
                                    電話番号
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_tel')) { echo ' class="form_error"'; } ?>>
                                    <span class="comiket_tel-lbl"><?php echo $bpnOutForm->comiket_tel();?></span>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_mail">
                                    メールアドレス
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_mail')) { echo ' class="form_error"'; } ?>>
                                    <?php echo $bpnOutForm->comiket_mail();?>

<!--                                    <br class="sp_only" />
                                    <p>※申込完了の際に申込完了メールを送付させていただきます。</p>
                                    <p class="red">
                                        ※必ず「sagawa-mov.co.jp」からのメールを受信する設定にしてください。

                                    </p>-->
                                </dd>
                            </dl>
<!--                            <dl>
                                <dt id="comiket_mail_retype">
                                    アドレス確認
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_mail_retype')) { echo ' class="form_error"'; } ?>>
                                        <?php echo $bpnOutForm->comiket_mail_retype();?>
                                </dd>
                            </dl>-->
                           
                    <?php endif; ?>        
                        </div>
                    </div>

                    <?php if (@empty($dispItemInfo['is_cancel'])) : ?>
                        <div class="input-buppan-title">商品情報</div>
                        <div class="dl_block comiket_block">
                            <dl>
                                <dt>
                                    商品一覧
                                </dt>
                                <dd>
                                    <table>
                                    <?php foreach($dispItemInfo['input_buppan_lbls'] as $key => $val):
                                        $boxNum = $bpnOutForm->comiket_box_buppan_num_ary($val["id"]); 
                                        if(!empty($boxNum)) :?>
                                        <tr>
                                            <td class='comiket_box_item_name'>
                                               <?php echo empty($val["name"]) ? "" : $val["name"]; ?>&nbsp;
                                            </td>
                                            <td class='comiket_box_item_value'>
                                               <b><?php echo $boxNum;?>枚 &nbsp;</b>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                </table>
                                </dd>
                            </dl>
                        </div>  
                    <?php else : ?>
                        <div style="" class="input-buppan-title">商品情報</div>
                        <div class="dl_block comiket_block">
                            <dl>
                                <dt>
                                    商品一覧
                                </dt>
                                <dd>
                                    <table>
                                    <?php foreach($dispItemInfo['buppan_lbls'] as $key => $val): ?>
                                        <tr>
                                            <td class='comiket_box_item_name'>
                                               <?php echo empty($val["name"]) ? "" : $val["name"]; ?>&nbsp;
                                            </td>
                                            <td class='comiket_box_item_value'>
                                               <b><?php echo $val["num"];?>枚 &nbsp;</b>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                                </dd>
                            </dl>
                        </div> 
                    <?php endif;?>  

<?php if($bpnOutForm->comiket_div() == Sgmov_View_Bpn_Common::COMIKET_DEV_BUSINESS) : // 法人 ?>
<!--                        <h4 class="table_title">お支払い情報</h4>
                        <div class="dl_block">
                                <dl>
                                    <dt>合計金額</dt>
                                    <dd>￥<?php echo number_format($bpnOutForm->delivery_charge()); ?></dd>
                                </dl>
                        </div>-->
<?php endif; ?>
<?php if($bpnOutForm->comiket_div() == Sgmov_View_Bpn_Common::COMIKET_DEV_INDIVIDUA) : // 個人 ?>
                        <!--▼お支払い方法-->
    <?php if ($bpnOutForm->comiket_payment_method_cd_sel() === '1') : ?>
                        <h4 class="table_title">コンビニお支払い情報</h4>
                        <div class="dl_block">
                                <dl>
                                    <dt>合計金額</dt>
                                    <dd>￥<?php echo number_format($bpnOutForm->delivery_charge_buppan()); ?></dd>
                                </dl>
                                <dl>
                                    <dt>お支払い店舗</dt>
                                    <dd>
                                        <?php if ($bpnOutForm->comiket_convenience_store_cd_sel() === '1') : ?>
                                            セブンイレブン
                                        <?php elseif ($bpnOutForm->comiket_convenience_store_cd_sel() === '2'): ?>
                                            ローソン、セイコーマート、ファミリーマート、ミニストップ
                                        <?php elseif ($bpnOutForm->comiket_convenience_store_cd_sel() === '3'): ?>
                                            デイリーヤマザキ
                                        <?php endif; ?>
                                    </dd>
                                </dl>
                        </div>

                        <div class="btn_area">
                            <a class="back" href="/bpn/<?php echo $dispItemInfo['back_input_path']; ?>/">修正する</a>
                            <?php if (@empty($dispItemInfo['is_cancel'])) : ?>
                                <input id="submit_button" type="submit" name="submit" value="入力内容を送信する" />
                            <?php else: ?>
                                <input id="submit_button" type="submit" name="submit" value="キャンセル送信する" />
                            <?php endif; ?>
                        </div>

    <?php endif; ?>


    <?php if ($bpnOutForm->comiket_payment_method_cd_sel() == '2') : // クレジット ?>
                        <div class="btn_area">
                            <a class="back" href="/bpn/<?php echo $dispItemInfo['back_input_path']; ?>/"> 修正する</a>
                        </div>

                        <h4 class="table_title">クレジットお支払い情報</h4>
                        <div class="dl_block">
                            <dl>
                                <dt>合計金額</dt>
                                <dd>
                                    ￥<?php echo number_format($bpnOutForm->delivery_charge_buppan()).PHP_EOL; ?>
                                    <?php if (intval($bpnOutForm->repeater_discount()) > 0) : ?>
                                        <span class="f80">※リピータ割引（<?php echo number_format($bpnOutForm->repeater_discount()); ?>円）が適用されました</span>
                                    <?php endif; ?>
                                </dd>
                            </dl>
                            <?php if (@empty($dispItemInfo['is_cancel'])) : ?>
                                <dl>
                                    <dt>有効期限</dt>
                                    <dd>
                                        <?php echo $bpnOutForm->card_expire_year_cd_sel(); ?>年<?php echo $bpnOutForm->card_expire_month_cd_sel(); ?>月
                                    </dd>
                                </dl>
                                <dl>
                                    <dt>カード番号</dt>
                                    <dd>
                                        <?php echo str_repeat('*', strlen($bpnOutForm->card_number())-4).substr($bpnOutForm->card_number(), -4) .PHP_EOL; ?>
                                        <span class="f80">※下4桁のみの表示となります</span>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt>セキュリティコード</dt>
                                    <dd><?php echo $bpnOutForm->security_cd(); ?></dd>
                                </dl>
                                <dl>
                                    <dt>お支払い方法</dt>
                                    <dd>1回</dd>
                                </dl>
                            <?php endif; ?>
                        </div>

                        <div class="btn_area">
                            
                            <?php if (@empty($dispItemInfo['is_cancel'])) : ?>
                                <a class="back" href="/bpn/size_change_credit_card/">修正する</a>
                                <input id="submit_button" type="submit" name="submit" value="入力内容を送信する" />
                            <?php else: ?>
                                <input id="submit_button" type="submit" name="submit" value="キャンセル送信する" />
                            <?php endif; ?>
                        </div>

    <?php endif; ?>

    <?php if ($bpnOutForm->comiket_payment_method_cd_sel() == '3') : // 電子マネー ?>
       <h4 class="table_title">電子マネー情報</h4>
        <div class="dl_block">
            <dl>
                <dt>合計金額</dt>
                <dd>￥<?php echo number_format($bpnOutForm->delivery_charge_buppan()); ?></dd>
            </dl>
        </div>
       <strong class="red">※電子マネーでのお支払いは、￥10,000を超える場合はお取り扱い出来ません。</strong>
        <div class="btn_area">
            <a class="back" href="/bpn/size_change/">修正する</a>
            <?php if (@empty($dispItemInfo['is_cancel'])) : ?>
                <input id="submit_button" type="submit" name="submit" value="入力内容を送信する" />
            <?php else: ?>
                <input id="submit_button" type="submit" name="submit" value="キャンセル送信する" />
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($bpnOutForm->comiket_payment_method_cd_sel() == '4') : // コンビニ後払 ?>
        <h4 class="table_title">コンビニ後払い情報</h4>
        <div class="dl_block">
            <dl>
                <dt>合計金額</dt>
                <dd>￥<?php echo number_format($bpnOutForm->delivery_charge_buppan()); ?></dd>
            </dl>
        </div>
        <strong class="red">※ コンビニ後払いについて：決済時に、お申し込みに時間がかかる場合又は、お申し込みができない場合がございますのでご了承ください。</strong>
        <div class="btn_area">
            <a class="back" href="/bpn/size_change/">修正する</a>
            <?php if (@empty($dispItemInfo['is_cancel'])) : ?>
                <input id="submit_button" type="submit" name="submit" value="入力内容を送信する" />
            <?php else: ?>
                <input id="submit_button" type="submit" name="submit" value="キャンセル送信する" />
            <?php endif; ?>
        </div>
    <?php endif; ?>

<?php else : ?>
        <div class="btn_area">
            <a class="back" href="/bpn/<?php echo $dispItemInfo['back_input_path']; ?>">修正する</a>
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

    <!--[if lt IE 9]>
    <script charset="UTF-8" type="text/javascript" src="/js/jquery-1.12.0.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/lodash-compat.min.js"></script>
    <![endif]-->
    <!--[if gte IE 9]><!-->
    <script charset="UTF-8" type="text/javascript" src="/js/jquery-2.2.0.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/lodash.min.js"></script>
    <!--<![endif]-->
    <script charset="UTF-8" type="text/javascript" src="/common/js/multi_send.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/common.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/smooth_scroll.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/ajax_searchaddress.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/jquery.ah-placeholder.js"></script>
<?php /*
    if (!$isSmartPhone) {
?>
    <script charset="UTF-8" type="text/javascript" src="/common/js/jquery.autoKana.js"></script>
<?php
    } */
?>
    <!--<script charset="UTF-8" type="text/javascript" src="/eve/js/input.js"></script>-->
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
//console.log("############ dispAttentionEventOnly");
                var eventId = $('input[name="event_sel"]').val();
//console.log("############ " + eventId);
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


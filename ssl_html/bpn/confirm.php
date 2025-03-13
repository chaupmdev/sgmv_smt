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
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('bpn/Confirm');

/**#@-*/

// 処理を実行
$view = new Sgmov_View_Bpn_Confirm();
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


/**
 * エラーフォーム
 * @var Sgmov_Form_Error
 */
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

$title = "卓上飛沫ブロッカーのお申込み";
if($bpnOutForm->shohin_pattern() == "2"):
    $title = "梱包資材のお申込み";
endif;

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
    <meta name="Description" content="<?php echo $title;?>（個人用）のご案内です。[旅行カバン等のお荷物承ります]フォームにもれなくご入力をお願いいたします。前日23時までにお支払の確認ができたお客様につきましては、翌日より集荷が可能となります。" />
    <meta name="author" content="SG MOVING Co.,Ltd" />
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved." />
    <title><?php echo $title;?>│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
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
            <li class="current"><?php echo $title;?></li>
        </ul>
    </div>
    <div id="main">
        <div class="wrap clearfix">
            <h1 class="page_title"><?php echo $title;?></h1>
            <p class="sentence">
                ご入力内容をご確認のうえ、「入力内容を送信する」ボタンを押してください。
                <br />修正する場合は「修正する」ボタンを押してください。
            </p>


            <div class="section">
                <form action="/bpn/complete/" data-feature-id="<?php echo Sgmov_View_Bpn_Common::FEATURE_ID ?>" data-id="<?php echo Sgmov_View_Bpn_Common::GAMEN_ID_BPN001 ?>" method="post">
                    <input name="ticket" type="hidden" value="<?php echo $ticket ?>" />
                    <input name="comiket_id" type="hidden" value="<?php echo $bpnOutForm->comiket_id(); ?>" />

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
                                    <?php if($bpnOutForm->eventsub_cd_sel() == "303" && $bpnOutForm->bpn_type() == "1"):?>
                                        会期
                                    <?php else :?>
                                        期間
                                    <?php endif; ?>
                                </dt>
                                <dd>
                                    <?php if($bpnOutForm->eventsub_term_fr_nm() == $bpnOutForm->eventsub_term_to_nm()):?>
                                        <span class="event-term_fr-lbl"><?php echo $bpnOutForm->eventsub_term_fr_nm(); ?></span>
                                    <?php else: ?>
                                        <span class="event-term_fr-lbl"><?php echo $bpnOutForm->eventsub_term_fr_nm(); ?></span>
                                        <span class="event-term_fr-str">&nbsp;から&nbsp;</span>
                                        <span class="event-term_to-lbl"><?php echo $bpnOutForm->eventsub_term_to_nm(); ?></span>
                                    <?php endif; ?>
                                </dd>
                            </dl>

                        <?php if($bpnOutForm->bpn_type() == "1") :?>
                            <dl>
                                <dt id="comiket_personal_name">
                                    お申込者
                                </dt>
                                <dd>
                                    <span class="comiket_personal_name_sei-lbl"><?php echo $bpnOutForm->comiket_personal_name_sei();?></span>
                                    <span class="comiket_personal_name_mei-lbl"><?php echo $bpnOutForm->comiket_personal_name_mei();?></span>
                                </dd>
                            </dl>
                            <dl style="display: none;">
                                <dt id="comiket_div">
                                    識別
                                </dt>
                                <dd></dd>
                            </dl>
                            <dl>
                                <dt id="comiket_zip">
                                    郵便番号
                                </dt>
                                <dd>
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
                                <dd>
                                    <span class="comiket_pref_nm-lbl"><?php echo $bpnOutForm->comiket_pref_nm();?></span>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_address">
                                    市区町村
                                </dt>
                                <dd>
                                    <span class="comiket_address-lbl"><?php echo $bpnOutForm->comiket_address();?></span>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_building">
                                    番地・建物名・部屋番号
                                </dt>
                                <dd>
                                    <span class="comiket_building-lbl"><?php echo $bpnOutForm->comiket_building();?></span>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_tel">
                                    電話番号
                                </dt>
                                <dd>
                                    <span class="comiket_tel-lbl"><?php echo $bpnOutForm->comiket_tel();?></span>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_mail">
                                    メールアドレス
                                </dt>
                                <dd>
                                    <?php echo $bpnOutForm->comiket_mail();?>
                                    <p class="red">
                                        ※必ず「sagawa-mov.co.jp」からのメールを受信する設定にしてください。
                                        <br />詳しくは
                                        <a href="#bounce_mail">こちら</a>
                                    </p>
                                </dd>
                            </dl>
                    <?php endif; ?>
                        <?php if($bpnOutForm->eventsub_cd_sel() == "303" && $bpnOutForm->bpn_type() == "1"):?>
                            <dl>
                                <dt id="comiket_mail">
                                    商品引き渡し日
                                </dt>
                                <dd>
                                    <?php echo $bpnOutForm->collect_year_cd_sel();?>年<?php echo $bpnOutForm->collect_month_cd_sel(); ?>月<?php echo $bpnOutForm->collect_day_cd_sel(); ?>日（<?php echo Sgmov_View_Bpn_Confirm::_getWeek($bpnOutForm->collect_year_cd_sel(), $bpnOutForm->collect_month_cd_sel(), $bpnOutForm->collect_day_cd_sel()); ?>）
                                </dd>
                            </dl>
                            <?php if ($dispItemInfo["eventsub_selected_data"]["booth_display"] == "1") : ?>
                                <dl>
                                    <dt id="comiket_booth_name">
                                        ブース名
                                    </dt>
                                    <dd>
                                        <?php echo $bpnOutForm->comiket_booth_name() ?>
                                    </dd>
                                </dl>
                            <?php endif; ?>
                            <?php if ($dispItemInfo["eventsub_selected_data"]["building_display"] == "1") : ?>
                                <dl>
                                    <dt id="building_booth_id_sel">
                                        ブースNO
                                    </dt>
                                    <dd>
                                        <table style="width:100%;">
                                            <tr>
                                                <td style="padding-right: 20px;">

                                                    <?php $comiketId = $bpnOutForm->comiket_id(); ?>
                                                    <?php if(empty($comiketId)) : ?>
                                                        <?php
                                                            $building_name =  Sgmov_View_Bpn_Confirm::_getLabelSelectPulldownData($bpnOutForm->building_name_ids(), $bpnOutForm->building_name_lbls(), $bpnOutForm->building_name_sel());
                                                            $boothPosition = Sgmov_View_Bpn_Confirm::_getLabelSelectPulldownData($bpnOutForm->building_booth_position_ids(), $bpnOutForm->building_booth_position_lbls(), $bpnOutForm->building_booth_position_sel());
                                                        ?>
                                                    <?php else: ?>
                                                            <?php
                                                            if ($bpnOutForm->building_name() == ''
                                                                    && $bpnOutForm->building_booth_position() == ''
                                                                    && $bpnOutForm->comiket_booth_num() == '') :
                                                                    $building_name = "その他";
                                                                    $boothPosition = "その他";
                                                                ?>
                                                            <?php else: 
                                                                    $building_name = Sgmov_View_Bpn_Confirm::_getLabelSelectPulldownData($bpnOutForm->building_name_ids(), $bpnOutForm->building_name_lbls(), $bpnOutForm->building_name_sel());
                                                                    $boothPosition =  $bpnOutForm->building_booth_position();
                                                            endif; 
                                                    endif; 
                                                   
                                                        if(empty($building_name)):
                                                            $building_name = $bpnOutForm->building_name();
                                                        endif;

                                                        if($building_name != "その他"): 
                                                            echo $building_name; ?>
                                                            <span style="font-size: 0.5em;">ホール</span>&nbsp;&nbsp; 
                                                        <?php endif; ?>

                                                        <?php echo $boothPosition; ?>

                                                    &nbsp;<?php echo @sprintf('%02s', $bpnOutForm->comiket_booth_num());?>
                                                    <?php // echo $bpnOutForm->building_booth_id_sel_nm(); ?>
                                                </td>
                                                <td style="">
                                                    <?php // echo $bpnOutForm->comiket_booth_num();?>
                                                </td>
                                            </tr>
                                        </table>
                                    </dd>
                                </dl>
                            <?php endif; ?>
                        <?php endif;?>
                        </div>
                    </div>

                <div style="" class="input-buppan-title">商品情報</div>
                <div class="dl_block comiket_block">
                    <dl class="service-buppan-item" service-id="1">
                        <dt>商品一覧</dt>
                        <dd>
                            <table>
                                <?php foreach($dispItemInfo['input_buppan_lbls'] as $key => $val):
                                    $boxNum = $bpnOutForm->comiket_box_buppan_num_ary($val["id"]); 
                                    if(!empty($boxNum) && $boxNum != "00" ) :?>
                                    <tr>
                                        <td class='comiket_box_item_name'>
                                           <b><?php echo empty($val["name"]) ? "" : $val["name"]; ?></b>&nbsp;
                                        </td>
                                        <td class='comiket_box_item_value'>
                                           <span style="margin-left: 6px;"><b><?php echo number_format($boxNum);?>枚</b> &nbsp;</span>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </table>
                        </dd>
                    </dl>
                </div>

                <!--▼お支払い方法-->
                        <!-- TODO VASI -->
    <?php if ($bpnOutForm->comiket_payment_method_cd_sel() === '1') : ?>
                        <h4 class="table_title">コンビニお支払い情報</h4>
                        <div class="dl_block">
                                <dl>
                                    <dt>合計金額</dt>
                                    <dd>￥
                                        <?php echo number_format($bpnOutForm->delivery_charge_buppan()); ?></dd>
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
                            <a class="back" href="/bpn/<?php echo $dispItemInfo['back_input_path']; ?>">修正する</a>
                            <input id="submit_button" type="submit" name="submit" value="入力内容を送信する" />
                        </div>
    <?php endif; ?>


    <?php if ($bpnOutForm->comiket_payment_method_cd_sel() == '2') : // クレジット ?>
                       <div class="btn_area">
                            <a class="back" href="/bpn/<?php echo $dispItemInfo['back_input_path']; ?>"> 修正する</a>
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
                        </div>

                        <div class="btn_area">
                            <a class="back" href="/bpn/credit_card/">修正する</a>
                            <input id="submit_button" type="submit" name="submit" value="入力内容を送信する" />
                        </div>

    <?php endif; ?>

    <?php if ($bpnOutForm->comiket_payment_method_cd_sel() == '3') : // 電子 ?>
                <!-- お支払方法 -->
                <h4 class="table_title">電子マネー情報</h4>
                <!--<h4 class="table_title">現金情報</h4>-->
                <div class="dl_block">
                    <dl>
                        <dt>合計金額</dt>
                        <dd>￥<?php echo number_format($bpnOutForm->delivery_charge_buppan()); ?></dd>
                    </dl>
                </div>
                <div class="btn_area" style="padding: 0px !important;">
                    <?php if($bpnOutForm->bpn_type() == "1"):?>
                        <a class="back" href="/bpn/<?php echo $dispItemInfo['back_input_path']; ?>">修正する</a>
                    <?php else:?>
                        <a class="back" href="/bpn/<?php echo $dispItemInfo['back_input_path']; ?>">修正する</a>
                    <?php endif; ?>
                    <input id="submit_button" type="submit" name="submit" value="入力内容を送信する" />
                </div>
                <br>
                <!-- お支払方法 -->
    <?php endif; ?>


                </form>
                <div class="attention_area">
<?php
                ///////////////////////////////////////////////
                // 迷惑メールエリア
                ///////////////////////////////////////////////
                //include_once dirname(__FILE__) . '/parts/input_attention_area_spammail.php';
                //if($bpnOutForm->eventsub_cd_sel() == "302" && $bpnOutForm->bpn_type() == "2" && $bpnOutForm->shohin_pattern() == "2"):
                    include_once dirname(__FILE__) . '/parts/input_attention_area_spammail_active_shohin.php';
                //else:
                    //include_once dirname(__FILE__) . '/parts/input_attention_area_spammail.php';
                //endif;
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
<style type="text/css">
    .size-span{
        font-size: 0.8em !important;
    }
    .act{
        display: none;
    }
</style>
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
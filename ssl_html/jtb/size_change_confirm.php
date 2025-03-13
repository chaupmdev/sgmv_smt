<?php
/**
 * コミケサービスのお申し込み確認画面を表示します。
 * @package    ssl_html
 * @subpackage JTB
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
// イベント識別子(URLのpath)はマジックナンバーで記述せずこの変数で記述してください
$dirDiv=Sgmov_Lib::setDirDiv(dirname(__FILE__));
Sgmov_Lib::useView($dirDiv.'/SizeChangeConfirm');

/**#@-*/

// 処理を実行
$view = new Sgmov_View_Jtb_SizeChangeConfirm();
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
    <title>催事・イベント配送受付サービスの個数・サイズ変更お申し込み｜お問い合わせ│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css" rel="stylesheet" type="text/css" />
    <link href="/<?=$dirDiv?>/css/eve.css" rel="stylesheet" type="text/css" />
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
            <li class="current">催事・イベント配送受付サービスの個数・サイズ変更お申し込み</li>
        </ul>
    </div>
    <div id="main">
        <div class="wrap clearfix">
            <h1 class="page_title">催事・イベント配送受付サービスの個数・サイズ変更お申し込み</h1>
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
                <form action="/<?=$dirDiv?>/size_change_complete" data-feature-id="<?php echo Sgmov_View_Jtb_Common::FEATURE_ID ?>" data-id="<?php echo Sgmov_View_Jtb_Common::GAMEN_ID_JTB001 ?>" method="post">
                    <input name="ticket" type="hidden" value="<?php echo $ticket ?>" />
                    <input name="comiket_id" type="hidden" value="<?php echo $eveOutForm->comiket_id(); ?>" />
                    <input type="hidden" name="event_sel" value="<?php echo $eveOutForm->event_cd_sel(); ?>">
                    
                    <div class="section">
                        <div class="dl_block comiket_block">
                            <dl>
                                <dt id="tour_name">
                                    ツアー名
                                </dt>
                                <dd>
                                <span class="event-place-lbl">
                                    立山アルペンルートツアー
                                    <?php
                                        //$selectedEventData = $dispItemInfo["eventsub_selected_data"];
                                        //echo @$selectedEventData["venue"];
                                    ?>
                                </span>
                                <input class="" style="width:80%;" autocapitalize="off" inputmode="eventsub_tour_name" name="eventsub_tour_name" data-pattern="" placeholder="" type="hidden" value="<?php //echo @$selectedEventData["venue"]; ?>" />
                                </dd>
                            </dl>
                            <dl>
                                <dt id="ryo_kan_name">
                                    旅館名
                                </dt>
                                <dd>
                                <select name="comiket_ryo_kan">
                                    <option value="">選択してください</option>
<?php
        //echo Sgmov_View_Jtb_Input::_createPulldown($eve001Out->comiket_pref_cds(), $eve001Out->comiket_pref_lbls(), $eve001Out->comiket_pref_cd_sel());
?>
                                </select>
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
                                                <?php if($eveOutForm->comiket_div() == Sgmov_View_Jtb_Common::COMIKET_DEV_BUSINESS) : ?>
                                                <?php $val = '請求書にて請求'; ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <?php echo $val; ?>
                                            <?php break; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </dd>
                            </dl>
                            <?php if ($eveOutForm->comiket_div() == Sgmov_View_Jtb_Common::COMIKET_DEV_BUSINESS) : // 法人 and 顧客コードを使用する ?>
                                <dl>
                                    <dt id="comiket_customer_cd">
                                        顧客コード
                                    </dt>
                                    <dd>
                                        <?php echo $eveOutForm->comiket_customer_cd() ?>
                                    </dd>
                                </dl>

                                <dl>
                                    <dt id="office_name">
                                        お申込者
                                    </dt>
                                    <dd>
                                        <span class="office_name-lbl"><?php echo $eveOutForm->office_name();?></span>
                                    </dd>
                                </dl>
                            <?php else : // 個人 ?>
                                <dl>
                                    <dt id="comiket_personal_name">
                                        お申込者
                                    </dt>
                                    <dd>
                                        <span class="comiket_personal_name_sei-lbl"><?php echo $eveOutForm->comiket_personal_name_sei();?></span>
                                        <span class="comiket_personal_name_mei-lbl"><?php echo $eveOutForm->comiket_personal_name_mei();?></span>
                                    </dd>
                                </dl>
                            <?php endif; ?>
                            <dl>
                                <dt id="comiket_zip" style=" border-bottom: solid 1px #ccc !important;">
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
                                <dt id="comiket_address" style=" border-bottom: solid 1px #ccc !important;">
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
                            <?php // if($eveOutForm->event_cd_sel() != '4') : ?>
                                <?php if($dispItemInfo["eventsub_selected_data"]["booth_display"] == "1") : ?>
                                    <dl>
                                        <dt id="comiket_booth_name">
                                            <?php if($eveOutForm->event_cd_sel() == '10') :  // 国内クルーズ?>
                                                部屋番号
                                            <?php else : ?>
                                                ブース名
                                            <?php endif; ?>
                                        </dt>
                                        <dd>
                                            <?php echo $eveOutForm->comiket_booth_name() ?>
                                        </dd>
                                    </dl>
                                <?php endif; ?>
                                <?php if($dispItemInfo["eventsub_selected_data"]["building_display"] == "1") : ?>
                                    <dl>
                                        <dt id="building_booth_id_sel">
                                            <?php if($eveOutForm->event_cd_sel() == '2') :  // コミケ?>
                                                ブース番号
                                            <?php else : ?>
                                                ブースNO
                                            <?php endif; ?>
                                        </dt>
                                        <dd<?php if (isset($e) && $e->hasErrorForId('building_booth_id_sel')) { echo ' class="form_error"'; } ?>>
                                           <table style="width:100%;">
                                                <tr>
                                                        <td style="padding-right: 20px;">

                                                            <?php $comiketId = $eveOutForm->comiket_id(); ?>
                                                            <?php if(empty($comiketId)) : ?>
                                                                <?php
                                                                    $building_name = Sgmov_View_Jtb_Confirm::_getLabelSelectPulldownData($eveOutForm->building_name_ids(), $eveOutForm->building_name_lbls(), $eveOutForm->building_name_sel());
                                                                    $boothPosition = Sgmov_View_Jtb_Confirm::_getLabelSelectPulldownData($eveOutForm->building_booth_position_ids(), $eveOutForm->building_booth_position_lbls(), $eveOutForm->building_booth_position_sel());
                                                                ?>
                                                            <?php else: ?>
                                                                    <?php
                                                                    if ($eveOutForm->building_name() == ''
                                                                            && $eveOutForm->building_booth_position() == ''
                                                                            && $eveOutForm->comiket_booth_num() == '') :
                                                                        // 本 if 文が通れば 「その他　その他　0000」 が表示され $eve001Out->building_name()
                                                                        // building_booth_position(), comiket_booth_num() は空表示
                                                                            $building_name = "その他";
                                                                            $boothPosition = "その他";
                                                                        ?>
                                                                    <?php else : 
                                                                        $building_name = $eveOutForm->building_name(); 
                                                                        $boothPosition = $eveOutForm->building_booth_position();  ?>
                                                                    <?php endif; ?>
                                                            <?php endif; ?>

                                                            <?php 
                                                                if(empty($building_name)):
                                                                    $building_name = $eveOutForm->building_name();
                                                                endif;
                                                            
                                                                if($building_name != "その他"): 
                                                                    echo $building_name; ?>
                                                                    <span style="font-size: 0.5em;">ホール</span>&nbsp;&nbsp; 
                                                                <?php endif; ?>
                                                                
                                                                <?php echo $eveOutForm->building_booth_position(); ?>
                                                            &nbsp;<?php echo $eveOutForm->comiket_booth_num();?>
                                                            <?php // echo $eveOutForm->building_booth_id_sel_nm(); ?>
                                                        </td>
                                                        <td style="">
                                                            <?php // echo $eveOutForm->comiket_booth_num();?>
                                                        </td>
                                                </tr>
                                            </table>
                                        </dd>
                                    </dl>
                                <?php endif; ?>
                            <?php // endif; ?>
                            <dl>
                                <dt id="comiket_staff_seimei" style=" border-top: solid 1px #ccc !important;">
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
                                <dt id="comiket_staff_tel" style=" border-top: solid 1px #ccc !important;">
                                    当日の担当者電話番号
                                </dt>
                                <dd>
                                    <?php echo $eveOutForm->comiket_staff_tel();?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_type_sel">
                                    往復
                                </dt>
                                <dd>
                                    <div class="comiket_detail_type_sel-dd">
                                        <?php foreach($dispItemInfo['comiket_detail_type_lbls'] as $key => $val) : ?>
                                            <?php if ($eveOutForm->comiket_detail_type_sel() == $key): ?>
                                                <?php echo $val; ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                        <?php if($eveOutForm->comiket_detail_type_sel() == "1" || $eveOutForm->comiket_detail_type_sel() == "3") : // 搬入の場合 ?>
<?php /**********************************************************************************************************************/ ?>
                            <div style="" class="input-outbound input-outbound-title">搬入</div>
<?php /**********************************************************************************************************************/ ?>
                            <div class="dl_block input-outbound comiket_block">
                                <dl>
                                    <dt id="comiket_detail_outbound_name">
                                        集荷先名
                                    </dt>
                                    <dd>
                                        <?php echo $eveOutForm->comiket_detail_outbound_name() ?>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt id="comiket_detail_outbound_zip">
                                        集荷先郵便番号
                                    </dt>

                                    <dd>
                                        〒<?php echo $eveOutForm->comiket_detail_outbound_zip1();?>
                                        -
                                        <?php echo $eveOutForm->comiket_detail_outbound_zip2();?>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt id="comiket_pref">
                                        集荷先都道府県
                                    </dt>
                                    <dd>
<?php
        echo Sgmov_View_Jtb_Confirm::_getLabelSelectPulldownData($eveOutForm->comiket_detail_outbound_pref_cds(), $eveOutForm->comiket_detail_outbound_pref_lbls(), $eveOutForm->comiket_detail_outbound_pref_cd_sel());
?>

                                    </dd>
                                </dl>
                                <dl>
                                    <dt id="comiket_detail_outbound_address">
                                        集荷先市区町村
                                    </dt>
                                    <dd>
                                        <?php echo $eveOutForm->comiket_detail_outbound_address();?>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt id="comiket_detail_outbound_building">
                                        集荷先番地・建物名・部屋番号
                                    </dt>
                                    <dd>
                                        <?php echo $eveOutForm->comiket_detail_outbound_building();?>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt id="comiket_detail_outbound_tel">
                                        集荷先TEL
                                    </dt>
                                    <dd>
                                        <?php echo $eveOutForm->comiket_detail_outbound_tel();?>
                                    </dd>
                                </dl>
                                <?php if ( $view->checkColAndDelDate("outbound", $eveOutForm->comiket_div(), $eveOutForm->comiket_detail_outbound_service_sel(), $dispItemInfo["eventsub_selected_data"]) ) : ?>
                                <dl>
                                    <dt id="comiket_detail_outbound_collect_date">
                                        お預かり日時
                                    </dt>
                                    <dd>
<?php echo $eveOutForm->comiket_detail_outbound_collect_date_year_sel(); ?>年<?php echo $eveOutForm->comiket_detail_outbound_collect_date_month_sel(); ?>月<?php echo $eveOutForm->comiket_detail_outbound_collect_date_day_sel(); ?>日
                                        （<?php echo Sgmov_View_Jtb_Confirm::_getWeek($eveOutForm->comiket_detail_outbound_collect_date_year_sel(), $eveOutForm->comiket_detail_outbound_collect_date_month_sel(), $eveOutForm->comiket_detail_outbound_collect_date_day_sel()); ?>）
                                        &nbsp;
<?php
        if ( $view->checkColAndDelTime("outbound", $eveOutForm->comiket_div(), $eveOutForm->comiket_detail_outbound_service_sel(), $dispItemInfo["eventsub_selected_data"]) ) {
            echo Sgmov_View_Jtb_Confirm::_getTimeFormatSelectPulldownData($eveOutForm->comiket_detail_outbound_collect_time_cds(), $eveOutForm->comiket_detail_outbound_collect_time_lbls(), $eveOutForm->comiket_detail_outbound_collect_time_sel());
        }
?>
                                    </dd>
                                </dl>
                                <?php endif; ?>
                                <dl style="display: none;">
                                    <dt id="comiket_detail_outbound_service_sel">
                                        サービス選択
                                    </dt>
                                    <dd>

                                        <?php foreach($dispItemInfo['comiket_detail_service_lbls'] as $key => $val) : ?>
                                            <?php if ($eveOutForm->comiket_detail_outbound_service_sel() == $key) : ?>
                                                <?php echo $val; ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt id="comiket_detail_outbound_delivery_date">
                                        引渡し日
                                    </dt>
                                    <dd>
<?php echo $eveOutForm->comiket_detail_outbound_delivery_date_year_sel(); ?>年<?php echo $eveOutForm->comiket_detail_outbound_delivery_date_month_sel(); ?>月<?php echo $eveOutForm->comiket_detail_outbound_delivery_date_day_sel(); ?>日
                                        （<?php echo Sgmov_View_Jtb_Confirm::_getWeek($eveOutForm->comiket_detail_outbound_delivery_date_year_sel(), $eveOutForm->comiket_detail_outbound_delivery_date_month_sel(), $eveOutForm->comiket_detail_outbound_delivery_date_day_sel()); ?>）
                                        &nbsp;
<?php
        echo Sgmov_View_Jtb_Confirm::_getTimeFormatSelectPulldownData($eveOutForm->comiket_detail_outbound_delivery_time_cds(), $eveOutForm->comiket_detail_outbound_delivery_time_lbls(), $eveOutForm->comiket_detail_outbound_delivery_time_sel());
?>
                                    </dd>
                                </dl>
                                <?php if ($eveOutForm->comiket_detail_outbound_service_sel() == "1") : // 宅配 ?>
                                    <dl class="service-outbound-item" service-id="1">
                                        <dt id="comiket_box_outbound_num_ary">
                                             宅配数量
                                        </dt>
                                        <dd>
                                            <table>
                                                <tr>
                                                    <td class='box_table_td' style='vertical-align: middle;width:40%;'>
                                                        <table>
                                                            <?php if (@empty($dispItemInfo['is_cancel'])) : ?>
                                                                <table>
                                                                    <?php foreach($dispItemInfo['outbound_box_lbls'] as $key => $val) : ?>
                                                                        <?php $boxNum = $eveOutForm->comiket_box_outbound_num_ary($val["id"]); ?>
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
                                                            <?php else : ?>
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
                                                    <td style='padding-top:5px;'>
                                                        <?php if($eveOutForm->comiket_div() == Sgmov_View_Jtb_Common::COMIKET_DEV_INDIVIDUA) : // 個人 ?>
                                                            <!--<img src='/<?=$dirDiv?>/images/about_boxsize.png' width='80%'/>-->
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </dd>
                                    </dl>
                                <?php endif; ?>
                                <?php if ($eveOutForm->comiket_detail_outbound_service_sel() == "2") : // カーゴ ?>
                                    <dl class="service-outbound-item" service-id="2">
                                        <dt id="comiket_cargo_outbound_num_ary">
                                            カーゴ数量
                                        </dt>
                                        <dd>
<?php
                                                echo Sgmov_View_Jtb_Confirm::_getLabelSelectPulldownData($eveOutForm->comiket_cargo_outbound_num_cds(), $eveOutForm->comiket_cargo_outbound_num_lbls(), $eveOutForm->comiket_cargo_outbound_num_sel());
?>台

                                        </dd>
                                    </dl>
                                <?php endif; ?>
                                <?php if ($eveOutForm->comiket_detail_outbound_service_sel() == "3") : // 貸切 ?>
                                    <dl class="service-outbound-item" service-id="3">
                                        <dt id="comiket_charter_outbound_num_ary">
                                            台数貸切
                                        </dt>
                                        <dd>
                                            <?php if ($eveOutForm->comiket_div() == Sgmov_View_Jtb_Common::COMIKET_DEV_INDIVIDUA) : // 個人 ?>
                                                <?php $outboundCharterNum = $eveOutForm->comiket_charter_outbound_num_ary("0"); ?>
                                                <?php echo $outboundCharterNum;?>台
                                            <?php elseif($eveOutForm->comiket_div() == Sgmov_View_Jtb_Common::COMIKET_DEV_BUSINESS) : // 法人  ?>
                                                <div class="comiket-charter-outbound-num" div-id="<?php echo Sgmov_View_Jtb_Common::COMIKET_DEV_BUSINESS; ?>"> <!-- 法人 -->
                                                    <table>
                                                        <?php foreach($dispItemInfo['charter_lbls'] as $key => $val) : ?>
                                                            <?php $outboundCharterNum = $eveOutForm->comiket_charter_outbound_num_ary($val["id"]); ?>
                                                            <?php if(!empty($outboundCharterNum)) : ?>
                                                                <tr>
                                                                    <td class='comiket_charter_item_name'>
                                                                        <?php echo $val["name"]; ?>&nbsp;
                                                                    </td>
                                                                    <td class='comiket_charter_item_value'>
                                                                        <?php echo $outboundCharterNum;?>台
                                                                    </td>
                                                                </tr>
                                                            <?php endif; ?>
                                                            &nbsp;
                                                        <?php endforeach; ?>
                                                    </table>
                                                </div>
                                            <?php endif; ?>
                                        </dd>
                                    </dl>
                                <?php endif; ?>
                                <dl style="display:none">
                                    <dt id="comiket_detail_outbound_note">
                                        備考
                                    </dt>
                                    <dd>
                                        <?php // echo nl2br($eveOutForm->comiket_detail_outbound_note()); ?>
                                        <?php echo $eveOutForm->comiket_detail_outbound_note1(); ?><br/>
                                        <?php echo $eveOutForm->comiket_detail_outbound_note2(); ?>
                                    </dd>
                                </dl>
                            </div>
                        <?php endif; // 搬入の場合  ?>
                        <?php if($eveOutForm->comiket_detail_type_sel() == "2" || $eveOutForm->comiket_detail_type_sel() == "3") : // 搬出の場合 ?>
<?php /**********************************************************************************************************************/ ?>
                            <div style="" class="input-inbound input-inbound-title">搬出</div>
<?php /**********************************************************************************************************************/ ?>
                            <div class="dl_block input-inbound comiket_block">

                                <dl>
                                    <dt id="comiket_detail_inbound_name">
                                        お届け先名
                                    </dt>
                                    <dd>
                                    <?php echo $eveOutForm->comiket_detail_inbound_name() ?>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt id="comiket_detail_zip_inbound">
                                        お届け先郵便番号
                                    </dt>

                                    <dd>
                                        〒<?php echo $eveOutForm->comiket_detail_inbound_zip1();?>
                                        -
                                        <?php echo $eveOutForm->comiket_detail_inbound_zip2();?>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt id="comiket_detail_inbound_pref">
                                        お届け先都道府県
                                    </dt>
                                    <dd>
<?php
        echo Sgmov_View_Jtb_Confirm::_getLabelSelectPulldownData($eveOutForm->comiket_detail_inbound_pref_cds(), $eveOutForm->comiket_detail_inbound_pref_lbls(), $eveOutForm->comiket_detail_inbound_pref_cd_sel());
?>

                                    </dd>
                                </dl>
                                <dl>
                                    <dt id="comiket_detail_inbound_address">
                                        お届け先市区町村
                                    </dt>
                                    <dd>
                                        <?php echo $eveOutForm->comiket_detail_inbound_address();?>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt id="comiket_detail_inbound_building">
                                        お届け先番地・建物名・部屋番号
                                    </dt>
                                    <dd>
                                        <?php echo $eveOutForm->comiket_detail_inbound_building();?>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt id="comiket_detail_inbound_tel">
                                        お届け先TEL
                                    </dt>
                                    <dd>
                                        <?php echo $eveOutForm->comiket_detail_inbound_tel();?>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt id="comiket_detail_inbound_collect_date">
                                        お預かり日
                                    </dt>
                                    <dd>
                                       <?php echo $eveOutForm->comiket_detail_inbound_collect_date_year_sel(); ?>年<?php echo $eveOutForm->comiket_detail_inbound_collect_date_month_sel(); ?>月<?php echo $eveOutForm->comiket_detail_inbound_collect_date_day_sel(); ?>日
                                        （<?php echo Sgmov_View_Jtb_Confirm::_getWeek($eveOutForm->comiket_detail_inbound_collect_date_year_sel(), $eveOutForm->comiket_detail_inbound_collect_date_month_sel(), $eveOutForm->comiket_detail_inbound_collect_date_day_sel()); ?>）
                                        &nbsp;
<?php
    echo Sgmov_View_Jtb_Confirm::_getTimeFormatSelectPulldownData($eveOutForm->comiket_detail_inbound_collect_time_cds(), $eveOutForm->comiket_detail_inbound_collect_time_lbls(), $eveOutForm->comiket_detail_inbound_collect_time_sel());
?>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt id="comiket_detail_inbound_delivery_date">
                                        お届け指定日時
                                    </dt>
                                    <dd>
                                        <?php echo $eveOutForm->comiket_detail_inbound_delivery_date_year_sel(); ?>年<?php echo $eveOutForm->comiket_detail_inbound_delivery_date_month_sel(); ?>月<?php echo $eveOutForm->comiket_detail_inbound_delivery_date_day_sel(); ?>日
                                        （<?php echo Sgmov_View_Jtb_Confirm::_getWeek($eveOutForm->comiket_detail_inbound_delivery_date_year_sel(), $eveOutForm->comiket_detail_inbound_delivery_date_month_sel(), $eveOutForm->comiket_detail_inbound_delivery_date_day_sel()); ?>）
                                        &nbsp;
<?php
    if ( $view->checkColAndDelTime("inbound", $eveOutForm->comiket_div(), $eveOutForm->comiket_detail_inbound_service_sel(), $dispItemInfo["eventsub_selected_data"]) ) :
        echo Sgmov_View_Jtb_Confirm::_getTimeFormatSelectPulldownDataForInBound($eveOutForm->comiket_detail_inbound_delivery_time_cds(), $eveOutForm->comiket_detail_inbound_delivery_time_lbls(), $eveOutForm->comiket_detail_inbound_delivery_time_sel());
    endif;
 ?>

                                    </dd>
                                </dl>
                                <?php // endif; ?>
                                <?php if ($eveOutForm->comiket_detail_inbound_service_sel() == "1") : // 宅配 ?>
                                    <dl class="service-outbound-item" service-id="1">
                                        <dt id="comiket_box_inbound_num_ary">
                                            宅配数量
                                        </dt>
                                        <dd>
                                            <?php if (@empty($dispItemInfo['is_cancel'])) : ?>
                                                <table>
                                                <?php foreach($dispItemInfo['inbound_box_lbls'] as $key => $val) : ?>
                                                    <?php $boxNum = $eveOutForm->comiket_box_inbound_num_ary($val["id"]); ?>
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

                                            <?php if(isset($dispItemInfo['inbound_box_lbls']) && 2 <= count($dispItemInfo['inbound_box_lbls'])) : ?>
                                            <?php endif; ?>
                                        </dd>
                                    </dl>
                                <?php endif; ?>
                                <?php if ($eveOutForm->comiket_detail_inbound_service_sel() == "2") : // カーゴ ?>
                                    <dl class="service-outbound-item" service-id="2">
                                        <dt id="comiket_cargo_inbound_num_ary">
                                            カーゴ数量
                                        </dt>
                                        <dd>
<?php
                                                echo Sgmov_View_Jtb_Confirm::_getLabelSelectPulldownData($eveOutForm->comiket_cargo_inbound_num_cds(), $eveOutForm->comiket_cargo_inbound_num_lbls(), $eveOutForm->comiket_cargo_inbound_num_sel());
?>台
                                        </dd>
                                    </dl>
                                <?php endif; ?>
                                <?php if ($eveOutForm->comiket_detail_inbound_service_sel() == "3") : // 貸切 ?>
                                    <dl class="service-outbound-item" service-id="3">
                                        <dt id="comiket_charter_inbound_num_ary">
                                            台数貸切
                                        </dt>
                                        <dd>
                                            <?php if ($eveOutForm->comiket_div() == Sgmov_View_Jtb_Common::COMIKET_DEV_INDIVIDUA) : // 個人 ?>
                                                <?php $outboundCharterNum = $eveOutForm->comiket_charter_inbound_num_ary("0"); ?>
                                                <?php echo $outboundCharterNum;?>台
                                            <?php elseif($eveOutForm->comiket_div() == Sgmov_View_Jtb_Common::COMIKET_DEV_BUSINESS) : // 法人  ?>

                                                <div class="comiket-charter-outbound-num" div-id="<?php echo Sgmov_View_Jtb_Common::COMIKET_DEV_BUSINESS; ?>"> <!-- 法人 -->
                                                    <table>
                                                        <?php foreach($dispItemInfo['charter_lbls'] as $key => $val) : ?>
                                                            <?php $outboundCharterNum = $eveOutForm->comiket_charter_inbound_num_ary($val["id"]); ?>
                                                            <?php if(!empty($outboundCharterNum)) : ?>
                                                            <tr>
                                                                <!--<div style="margin-bottom: 10px;">-->
                                                                <td class='comiket_charter_item_name'>
                                                                    <?php echo $val["name"]; ?>&nbsp;
                                                                 </td>
                                                                 <td class='comiket_charter_item_value'>
                                                                     <?php echo $outboundCharterNum;?>台
                                                                 </td>
                                                                <!--</div>-->
                                                            </tr>
                                                            <?php endif; ?>
                                                            &nbsp;
                                                        <?php endforeach; ?>
                                                    </table>
                                                </div>
                                            <?php endif; ?>
                                        </dd>
                                    </dl>
                                <?php endif; ?>
                                <dl style="display:none">
                                    <dt id="comiket_detail_inbound_note">
                                        備考
                                    </dt>
                                    <dd>
                                        <?php // echo nl2br($eveOutForm->comiket_detail_inbound_note()); ?>
                                        <?php echo $eveOutForm->comiket_detail_inbound_note1(); ?><br/>
                                        <?php echo $eveOutForm->comiket_detail_inbound_note2(); ?>
                                    </dd>
                                </dl>
                            </div>
                        <?php endif; // 搬出の場合  ?>
                    </div>
<?php if($eveOutForm->comiket_div() == Sgmov_View_Jtb_Common::COMIKET_DEV_BUSINESS) : // 法人 ?>

<?php endif; ?>
<?php if($eveOutForm->comiket_div() == Sgmov_View_Jtb_Common::COMIKET_DEV_INDIVIDUA) : // 個人 ?>
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
                            <a class="back" href="/<?=$dirDiv?>/<?php echo $dispItemInfo['back_input_path']; ?>/">修正する</a>
                            <?php if (@empty($dispItemInfo['is_cancel'])) : ?>
                                <input id="submit_button" type="submit" name="submit" value="入力内容を送信する" />
                            <?php else: ?>
                                <input id="submit_button" type="submit" name="submit" value="キャンセル送信する" />
                            <?php endif; ?>
                        </div>

    <?php endif; ?>


    <?php if ($eveOutForm->comiket_payment_method_cd_sel() == '2') : // クレジット ?>
                        <div class="btn_area">
                            <a class="back" href="/<?=$dirDiv?>/<?php echo $dispItemInfo['back_input_path']; ?>/"> 修正する</a>
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
                                <a class="back" href="/<?=$dirDiv?>/size_change_credit_card/">修正する</a>
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
            <a class="back" href="/<?=$dirDiv?>/size_change/">修正する</a>
            <?php if (@empty($dispItemInfo['is_cancel'])) : ?>
                <input id="submit_button" type="submit" name="submit" value="入力内容を送信する" />
            <?php else: ?>
                <input id="submit_button" type="submit" name="submit" value="キャンセル送信する" />
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($eveOutForm->comiket_payment_method_cd_sel() == '4') : // コンビニ後払 ?>
        <h4 class="table_title">コンビニ後払い情報</h4>
        <div class="dl_block">
            <dl>
                <dt>お支払い総額（仕分け特別料金含む）</dt>
                <dd>￥<?php echo number_format($eveOutForm->delivery_charge()); ?></dd>
            </dl>
        </div>
        <strong class="red">※ コンビニ後払いについて：決済時に、お申し込みに時間がかかる場合又は、お申し込みができない場合がございますのでご了承ください。</strong>
        <div class="btn_area">
            <a class="back" href="/<?=$dirDiv?>/size_change/">修正する</a>
            <?php if (@empty($dispItemInfo['is_cancel'])) : ?>
                <input id="submit_button" type="submit" name="submit" value="入力内容を送信する" />
            <?php else: ?>
                <input id="submit_button" type="submit" name="submit" value="キャンセル送信する" />
            <?php endif; ?>
        </div>
    <?php endif; ?>

<?php else : ?>
        <div class="btn_area">
            <a class="back" href="/<?=$dirDiv?>/<?php echo $dispItemInfo['back_input_path']; ?>">修正する</a>
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


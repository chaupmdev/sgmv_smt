<?php
/**
 * コミケサービスのお申し込み確認画面を表示します。
 * @package    ssl_html
 * @subpackage UNA
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
//メンテナンス期間チェック
require_once dirname(__FILE__) . '/../../lib/component/maintain_event.php';
// 現在日時
$nowDate = new DateTime('now');
if ($main_stDate_ev <= $nowDate && $nowDate <= $main_edDate_ev) {
    header("Location: /maintenance.php");
    exit;
}
/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
// イベント識別子(URLのpath)はマジックナンバーで記述せずこの変数で記述してください
$dirDiv=Sgmov_Lib::setDirDiv(dirname(__FILE__));
Sgmov_Lib::useView($dirDiv.'/Confirm');

/**#@-*/

// 処理を実行
$view = new Sgmov_View_Una_Confirm();
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
    <meta name="Description" content="手荷物配送サービスのお申し込み（個人用）のご案内です。[旅行カバン等のお荷物承ります]フォームにもれなくご入力をお願いいたします。前日23時までにお支払の確認ができたお客様につきましては、翌日より集荷が可能となります。" />
    <meta name="author" content="SG MOVING Co.,Ltd" />
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved." />
    <title>手荷物配送サービスのお申し込み（個人用）｜お問い合わせ│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
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
            <li class="current">手荷物配送サービスのお申し込み</li>
        </ul>
    </div>
    <div id="main">
        <div class="wrap clearfix">
            <h1 class="page_title">手荷物配送サービスのお申し込み</h1>
            <p class="sentence">
                ご入力内容をご確認のうえ、「入力内容を送信する」ボタンを押してください。
                <br />修正する場合は「修正する」ボタンを押してください。
            </p>


            <div class="section">
                <form action="/<?=$dirDiv?>/complete" data-feature-id="<?php echo Sgmov_View_Una_Common::FEATURE_ID ?>" data-id="<?php echo Sgmov_View_Una_Common::GAMEN_ID_UNA001 ?>" method="post">
                    <input name="ticket" type="hidden" value="<?php echo $ticket ?>" />
                    <input name="comiket_id" type="hidden" value="<?php echo $eveOutForm->comiket_id(); ?>" />

                    <div class="section">

                        <div class="dl_block comiket_block">
                            <dl>
                                <dt id="event_sel">
                                    ツアー名
                                </dt>
                                <dd>
                                        <table>
                                            <tr>
                                                <td class="event_eventsub_td_name" style="">
<?php
        $eventName = Sgmov_View_Una_Confirm::_getLabelSelectPulldownData($eveOutForm->event_cds(), $eveOutForm->event_lbls(), $eveOutForm->event_cd_sel());
        echo $eventName;
?>
                                                    &nbsp;
                                                    <input type="hidden" name="event_sel" value="<?php echo $eveOutForm->event_cd_sel(); ?>">
                                                    <span  class="eventsub_sel">
<?php
        $eventsubName = Sgmov_View_Una_Confirm::_getLabelSelectPulldownData($eveOutForm->eventsub_cds(), $eveOutForm->eventsub_lbls(), $eveOutForm->eventsub_cd_sel());
        echo $eventsubName;
?>
                                                    </span>
                                                </td>
                                                <td class="event_eventsub_td_dl_item" style=""></td>
                                            </tr>
                                        </table>
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
                                                <?php if($eveOutForm->comiket_div() == Sgmov_View_Una_Common::COMIKET_DEV_BUSINESS) : ?>
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
                                <dt id="terminal_cd">
                                    集荷の往復
                                </dt>
                                <dd>
                                    <?php 
                                    if ($eveOutForm->raw_comiket_detail_type_sel2 == '1') { 
                                        echo '自宅 → 宿泊施設';
                                    } else {
                                        echo '往復';
                                    }
                                    ?>
                                    
                                </dd>
                            </dl>
                            
                            
                            <dl service-id="1">
                                <dt id="comiket_box_outbound_num_ary">
                                    宅配数量
                                </dt>
                                <dd>
                                   <?php foreach($dispItemInfo['box_lbls'] as $key => $val) : ?>
                                        <?php $boxNum = $eveOutForm->comiket_box_outbound_num_ary($val["id"]); ?>
                                        <?php if(!empty($boxNum)) : ?>
                                            <?php if($key == 0) : ?>
                                                <?php echo $boxNum;?>個<br/>
                                            <?php else : ?> 
                                                <div style="margin-top: 7px;">復路 &nbsp;<?php echo $boxNum;?>個</div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </dd>
                            </dl>
                            
                            <dl>
                                <dt id="building_name_sel">
                                    宿泊先
                                </dt>
                                <dd>
                                    <span class="building_name_sel-lbl"><?php echo $eveOutForm->building_booth_position();?></span>
                                </dd>
                            </dl>
                            
                            <dl>
                                <dt id="comiket_detail_outbound_delivery_date">
                                    宿泊日（引き渡し希望日）
                                </dt>
                                <dd>
                                    <?php echo $eveOutForm->comiket_detail_outbound_delivery_date_year_sel();?>年<?php echo $eveOutForm->comiket_detail_outbound_delivery_date_month_sel();?>月<?php echo $eveOutForm->comiket_detail_outbound_delivery_date_day_sel(); ?>日
                                    （<?php echo Sgmov_View_Una_Confirm::_getWeek($eveOutForm->comiket_detail_outbound_delivery_date_year_sel(), $eveOutForm->comiket_detail_outbound_delivery_date_month_sel(), $eveOutForm->comiket_detail_outbound_delivery_date_day_sel()); ?>）
                                    &nbsp;
<?php
   // echo Sgmov_View_Una_Confirm::_getTimeFormatSelectPulldownData($eveOutForm->comiket_detail_outbound_delivery_time_cds(), $eveOutForm->comiket_detail_outbound_delivery_time_lbls(), $eveOutForm->comiket_detail_outbound_delivery_time_sel());
?>
                                </dd>
                            </dl>
                            
                            <?php if ( $view->checkColAndDelDate("outbound", $eveOutForm->comiket_div(), $eveOutForm->comiket_detail_outbound_service_sel(), $dispItemInfo["eventsub_selected_data"]) ) : ?>
                            <dl>
                                <dt id="comiket_detail_outbound_collect_date">
                                    お預かり希望日時
                                </dt>
                                <dd>
                                    <?php echo $eveOutForm->comiket_detail_outbound_collect_date_year_sel();?>年<?php echo $eveOutForm->comiket_detail_outbound_collect_date_month_sel(); ?>月<?php echo $eveOutForm->comiket_detail_outbound_collect_date_day_sel(); ?>日
                                    （<?php echo Sgmov_View_Una_Confirm::_getWeek($eveOutForm->comiket_detail_outbound_collect_date_year_sel(), $eveOutForm->comiket_detail_outbound_collect_date_month_sel(), $eveOutForm->comiket_detail_outbound_collect_date_day_sel()); ?>）
                                    &nbsp;

<?php
    if ( $view->checkColAndDelTime("outbound", $eveOutForm->comiket_div(), $eveOutForm->comiket_detail_outbound_service_sel(), $dispItemInfo["eventsub_selected_data"]) ) {
        echo Sgmov_View_Una_Confirm::_getTimeFormatSelectPulldownData($eveOutForm->comiket_detail_outbound_collect_time_cds(), $eveOutForm->comiket_detail_outbound_collect_time_lbls(), $eveOutForm->comiket_detail_outbound_collect_time_sel());
    }
?>
                                </dd>
                            </dl>
                            <?php endif; ?>
                            
                            <?php if ($eveOutForm->raw_comiket_detail_type_sel2 == '3'): ?>
                            <dl>
                                <dt id="comiket_detail_collect_date">
                                    復路集荷日
                                </dt>
                                <dd>
                                    <?php
                                    $eveOutForm->raw_comiket_detail_collect_date_sel = str_replace('/', '-', $eveOutForm->raw_comiket_detail_collect_date_sel);
                                    $arrDateArrival = explode("-", $eveOutForm->raw_comiket_detail_collect_date_sel);
                                    $year = $arrDateArrival[0];
                                    $month = $arrDateArrival[1];
                                    $day = $arrDateArrival[2];
                                    ?>
                                    <?php echo $year.'年'.$month.'月'.$day.'日';?>
                                    （<?php echo Sgmov_View_Una_Confirm::_getWeek($year, $month, $day); ?>）
                                </dd>
                            </dl>
                            <?php endif; ?>
                        </div>
                    </div>

                <div class="btn_area">
                    <a class="back" href="/<?=$dirDiv?>/<?php echo $dispItemInfo['back_input_path']; ?>">修正する</a>
                    <input id="submit_button" type="submit" name="submit" value="入力内容を送信する" />
                </div>


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


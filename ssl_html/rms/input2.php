<?php
/**
 * 催事・イベント配送受付お申し込み編集画面を表示します。
 * @package    ssl_html
 * @subpackage RMS
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('rms/Input2');

/**#@-*/

// 処理を実行
$view = new Sgmov_View_Rms_Input2();
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
$eve001Out = $forms['outForm'];

$dispItemInfo = $forms['dispItemInfo'];
$eventsubInfo = $dispItemInfo["eventsub_list"][0];

/**
 * エラーフォーム
 * @var Sgmov_Form_Error
 */
$e = $forms['errorForm'];

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

// GiapLN implement SMT6-85
$userType = isset($_SESSION['RMS_LOGIN']['user_type']) ? $_SESSION['RMS_LOGIN']['user_type'] : -1;

$eventNm = isset($_SESSION['EVENT']['event_name']) ? $_SESSION['EVENT']['event_name'] : '';
$baseUrl = Sgmov_Component_Config::getUrlPublicSsl();
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
    <meta name="Description" content="催事・イベント配送受付サービスのお申し込みのご案内です。" />
    <meta name="author" content="SG MOVING Co.,Ltd" />
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved." />
    <title>催事・イベント配送受付サービスのお申し込み｜お問い合わせ│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
<?php
    // キャッシュ対策
    $sysdate = new DateTime();
    $strSysdate = $sysdate->format('YmdHi');
?>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <link href="/rms/css/eve.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
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
            <p class="sentence" style="margin-bottom: 5px;">
                以下のフォームにもれなくご入力をお願いいたします。
                <br />
                <span class="red" style="font-size: 1.5em;font-weight: bolder;">
                    必ず「sagawa-mov.co.jp」からのメールを受信する設定にしてください。
                </span>
                <span class="red">
                    <br>詳しくは<a href="#bounce_mail">こちら</a>
                </span>
                <br />梱包方法ガイドライン
                <br /><a href="http://www.sagawa-exp.co.jp/send/howto-packing/" target="_blank" style="color: #1774bc; text-decoration: underline;">http://www.sagawa-exp.co.jp/send/howto-packing/</a>
                <br />「商標についてーリンクガイドライン」の一読と確認をお願いいたします。
                <br /><a href="http://www.sagawa-exp.co.jp/help/" target="_blank" style="color: #1774bc; text-decoration: underline;">http://www.sagawa-exp.co.jp/help/</a>
                <br />沖縄など航空便対応エリアからご利用のお客様は、「航空便ご利用上の注意」及び「航空宅配便等個建運送約款」をご確認の上でお申し込みください。
                <br /><a href="/pdf/kokubin_goriyoujyono_tyui.pdf" target="_blank" style="color: #1774bc; text-decoration: underline;">航空便ご利用上の注意はこちら</a>
                <br /><a href="/pdf/kokutakuhaibinnado_unso_yakkan.pdf" target="_blank" style="color: #1774bc; text-decoration: underline;">航空宅配便等個建運送約款はこちら</a>
            </p>
            <p class="sentence disp_gooutcamp" style="display: none;">
                <span class="red">
                    ※貸切（チャーター）ご希望の方は、以下よりご連絡ください。（法人のみ）<br/>
                    SG ムービング株式会社<br/>
                    rooms41係 <br/>
                    TEL: 03-5857-2462(受付時間:平日10時～17時)
                </span>
            </p>
            <div id="timeover" class="message_flame" style="display: none;">
            </div>


<?php
    if (isset($e) && $e->hasError()) {
?>
            <div class="err_msg">
                <p class="sentence br attention">[!ご確認ください]下記の項目が正しく入力・選択されていません。</p>
                <ul>
<?php
        // エラー表示
        foreach($e->_errors as $key => $val) {
            echo "<li><a href='#" . $key . "'>" . $val . '</a></li>';
        }
?>

                </ul>
                <p class="under">
                    インターネットでお申し込みが出来なかった場合は、
                    <br />下記ダイヤルにお問い合わせください。
                    <br />
                    <br />SG ムービング株式会社<!-- 東京営業所 -->
                    <br />
                    <?php
                            $selectedEventNmae = Sgmov_View_Rms_Input::_getLabelSelectPulldownData($eve001Out->event_cds(), $eve001Out->event_lbls(), $eve001Out->event_cd_sel());
                            if(!empty($selectedEventNmae)) {
                                $selectedEventNmae .= '係';
                            }
                            echo $selectedEventNmae;
                    ?>
                    <br />TEL: 03-5857-2462(受付時間:平日10時～17時)
                </p>
            </div>

<?php
    }
?>
            <div class="section other">
                <form action="/rms/check_input2" data-feature-id="<?php echo Sgmov_View_Rms_Common::FEATURE_ID ?>" data-id="<?php echo Sgmov_View_Rms_Common::GAMEN_ID_RMS001 ?>" method="post">
                    <input name="ticket" type="hidden" value="<?php echo $ticket ?>" />
                    <input name="comiket_id" type="hidden" value="<?php echo $eve001Out->comiket_id(); ?>" />

                    <input name='input_type_email' type='hidden' value='<?php echo $inputTypeEmail; ?>' />
                    <input name='input_type_number' type='hidden' value='<?php echo $inputTypeNumber; ?>' />

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
<?php
        $eventName = Sgmov_View_Rms_Input::_getLabelSelectPulldownData($eve001Out->event_cds(), $eve001Out->event_lbls(), $eve001Out->event_cd_sel());
        echo $eventName;
        echo '<br><br><img src="/rms/images/logo.png">';
?>
<span class='eventsub_sel'>
<?php
        $eventsubName = Sgmov_View_Rms_Input::_getLabelSelectPulldownData($eve001Out->eventsub_cds(), $eve001Out->eventsub_lbls(), $eve001Out->eventsub_cd_sel());
        echo $eventsubName;
?>
</span>
<?php if($eventsubInfo["is_manual_display"]) : ?>
<br/>
<br/>
<div class="sp_dl_area2">

    <div class="eventsub_dl_link manual" >
        <img src="/images/common/img_icon_pdf.gif" width="18" height="21" alt="">
        <a style="color: blue;" class="manual_link" href="/rms/pdf/manual/<?php echo $eventName; ?>.pdf<?php echo '?' . $strSysdate; ?>" target="_blank">宅配便WEB申込みのご案内</a>
    </div>
</div>
<?php endif; ?>

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

                                <?php if($eve001Out->eventsub_term_fr_nm() == $eve001Out->eventsub_term_to_nm()):?>
                                    <span class="event-term_fr-lbl"><?php echo $eve001Out->eventsub_term_fr_nm(); ?></span>
                                <?php else: ?>
                                    <span class="event-term_fr-lbl"><?php echo $eve001Out->eventsub_term_fr_nm(); ?></span>
                                    <span class="event-term_fr-str">&nbsp;から&nbsp;</span>
                                    <span class="event-term_to-lbl"><?php echo $eve001Out->eventsub_term_to_nm(); ?></span>
                                <?php endif; ?>
                                </dd>
                            </dl>
                            <dl style="display: none;">
                                <dt id="comiket_div">
                                    識別
                                </dt>
                                <dd>
                                    <?php foreach($dispItemInfo['comiket_div_lbls'] as $key => $val) : ?>
                                        <?php if ($eve001Out->comiket_div() == $key) : ?>
                                            <?php echo $val; ?>
                                            <?php break; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </dd>
                            </dl>
                            <?php if ($eve001Out->comiket_div() == Sgmov_View_Rms_Common::COMIKET_DEV_BUSINESS) : // 法人?>
                                <dl>
                                    <dt id="comiket_customer_cd">
                                        顧客コード<span>必須</span>
                                    </dt>
                                    <dd<?php
                                        if (isset($e)
                                           && ($e->hasErrorForId('comiket_customer_cd'))
                                        ) {
                                            echo ' class="form_error"';
                                        }
                                    ?>>
                                            <?php echo $eve001Out->comiket_customer_cd() ?>
                                    </dd>
                                </dl>

                                <dl>
                                    <dt id="office_name">
                                        お申込者<span>必須</span>
                                    </dt>
                                    <dd<?php
                                        if (isset($e)
                                           && ($e->hasErrorForId('office_name'))
                                        ) {
                                            echo ' class="form_error"';
                                        }
                                    ?>>
                                    <span class="office_name-lbl"><?php echo $eve001Out->office_name();?></span>
                                    </dd>
                                </dl>
                            <?php else : // 個人 ?>
                                <dl>
                                    <dt id="comiket_personal_name">
                                        お申込者<span>必須</span>
                                    </dt>
                                    <dd>
                                        <span class="comiket_personal_name_sei-lbl"><?php echo $eve001Out->comiket_personal_name_sei();?></span>
                                        <span class="comiket_personal_name_mei-lbl"><?php echo $eve001Out->comiket_personal_name_mei();?></span>
                                    </dd>
                                </dl>
                            <?php endif; ?>
                            <dl>
                                <dt id="comiket_zip">
                                    郵便番号<span>必須</span>
                                </dt>

                                <dd>
                                    〒<span class="comiket_zip1-lbl"><?php echo $eve001Out->comiket_zip1();?></span>
                                    <span class="comiket_zip1-str">
                                        -
                                    </span>
                                    <span class="comiket_zip2-lbl"><?php echo $eve001Out->comiket_zip2();?></span>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_pref">
                                    都道府県<span>必須</span>
                                </dt>
                                <dd>
                                    <span class="comiket_pref_nm-lbl"><?php echo $eve001Out->comiket_pref_nm();?></span>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_address">
                                    市区町村<span>必須</span>
                                </dt>
                                <dd>
                                    <span class="comiket_address-lbl"><?php echo $eve001Out->comiket_address();?></span>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_building">
                                    番地・建物名・部屋番号<span>必須</span>
                                </dt>
                                <dd>
                                    <span class="comiket_building-lbl"><?php echo $eve001Out->comiket_building();?></span>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_tel">
                                    電話番号<span>必須</span>
                                </dt>
                                <dd>
                                    <span class="comiket_tel-lbl"><?php echo $eve001Out->comiket_tel();?></span>
                                </dd>
                            </dl>
                            <!-- GiapLN implement SMT6-85 -->
                            <?php if ($userType === 0) : ?>
                            <dl>
                                <dt id="comiket_mail">
                                    メールアドレス<span>必須</span>
                                </dt>
                                <dd>
                                    <?php echo $eve001Out->comiket_mail();?>
                                    <br>
                                    <p class="red">
                                        ※迷惑メールを設定されている方は「sagawa-mov.co.jp」を受信する設定にしてください。
                                        <br />詳しくは
                                        <a href="#bounce_mail">こちら</a>
                                    </p>
                                </dd>
                            </dl>
                            <?php endif; ?>
                        </div>
                        <div style="display:none;">
                            <?php  include_once dirname(__FILE__) . '/parts/input_cstmr.php'; ?>
                            <input type="radio" name="comiket_detail_type_sel" value="2" checked="checked"/>
                        </div>
<?php

                        ///////////////////////////////////////////////
                        // 搬出情報入力エリア
                        ///////////////////////////////////////////////
                        include_once dirname(__FILE__) . '/parts/input_inbound.php';
?>


                    </div>

                    <?php if ($eve001Out->comiket_div() == Sgmov_View_Rms_Common::COMIKET_DEV_INDIVIDUA) : ?>
                        <?php include_once dirname(__FILE__) . '/parts/input_payment_method.php'; ?>
                    <?php endif; ?>

                    <!--▲お支払い方法-->

<?php
                        ///////////////////////////////////////////////
                        // アテンションエリア
                        ///////////////////////////////////////////////
                        include_once dirname(__FILE__) . '/parts/input_attention_area.php';
?>

                    <p class="sentence">
                        <strong class="red">※迷惑メールを設定されている方は、必ず「sagawa-mov.co.jp」からのメールを受信する設定にしてください。
                            <br>詳しくは「<a href="#bounce_mail">ご連絡メールが届かない場合</a>」をご確認ください。</strong>
                    </p>

                    <p class="sentence"><span class="sp_only pcH">上記「個人情報の取り扱い」および「特定商取引法に基づく表記」の</span>内容についてご同意頂ける方は、下のボタンを押してください。</p>

                    <p class="text_center">
                        <input id="submit_button" type="submit" name="submit" value="同意して次に進む（入力内容の確認）">
                    </p>
                    
                    <p class="text_center">
                        <input  class="btnBackEvent" type="button" name="btnBack" onclick="backRedirectEvent('<?php echo $eventNm; ?>', '<?php echo $userType; ?>', '<?php echo $baseUrl; ?>');" value="戻る">
                    </p>
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
<?php
    if (!$isSmartPhone) {
?>
    <script charset="UTF-8" type="text/javascript" src="/common/js/jquery.autoKana.js"></script>
<?php
    }
?>
    <script charset="UTF-8" type="text/javascript" src="/rms/js/define.js" id="definejs-arg"
    data-G_DEV_INDIVIDUAL="<?php echo Sgmov_View_Rms_Common::COMIKET_DEV_INDIVIDUA; ?>"
    data-G_DEV_BUSINESS="<?php echo Sgmov_View_Rms_Common::COMIKET_DEV_BUSINESS; ?>"
    ></script>
    <script charset="UTF-8" type="text/javascript" src="/rms/js/input2.js?<?php echo $strSysdate; ?>"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/from_to_pulldate.js?<?php echo $strSysdate; ?>"></script>
</body>
</html>


<?php
/**
 * 物販送受付お申し込み入力画面を表示します。
 * @package    ssl_html
 * @subpackage BPN
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('bpn/SizeChange');

/**#@-*/

// 処理を実行
$view = new Sgmov_View_Bpn_SizeChange();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_bpn001Out
 */
$bpn001Out = $forms['outForm'];

$dispItemInfo = $forms['dispItemInfo'];

// 物販タイプ（１：物販、２：当日物販）
$bpnType = $forms['bpnType'];

$screen = "";
if($bpnType == "2"){
    $screen = "当日";
}

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
    <meta name="Description" content="物販受付サービスのお申し込みのご案内です。" />
    <meta name="author" content="SG MOVING Co.,Ltd" />
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved." />
    <?php if(empty($screen)):?>
        <title>卓上飛沫ブロッカーの数量変更お申し込み│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
    <?php else:?>
        <title><?php echo $screen;?>物販受付サービスの数量変更お申し込み｜お問い合わせ│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
    <?php endif;?>
    
<?php
    // キャッシュ対策
    $sysdate = new DateTime();
    $strSysdate = $sysdate->format('YmdHi');
?>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <link href="/bpn/css/bpn.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
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
                商品数量変更のうえ、「同意して次に進む（入力内容の確認）」ボタンを押してください。
            </p>

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
                    <br/>
                    <?php
                            $selectedEventNmae = Sgmov_View_Bpn_Input::_getLabelSelectPulldownData($bpn001Out->event_cds(), $bpn001Out->event_lbls(), $bpn001Out->event_cd_sel());
                            if(!empty($selectedEventNmae)) {
                                $selectedEventNmae .= '係';
                            }
                            echo $selectedEventNmae;
                    ?>
                    <br/>TEL: 03-5857-2462(受付時間:平日10時～17時)<?php if ($bpn001Out->eventsub_cd_sel() == '4') : ?>※年内は12月28日まで<?php endif; ?>
                </p>
            </div>

<?php
    }
?>
            <div class="section other">
                <form action="/bpn/size_change_check_input" data-feature-id="<?php echo Sgmov_View_Bpn_Common::FEATURE_ID ?>" data-id="<?php echo Sgmov_View_Bpn_Common::GAMEN_ID_BPN001; ?>" method="post">
                    <input name="param" type="hidden" value="<?php echo @filter_input(INPUT_GET, 'param'); ?>" />
                    <input name="ticket" type="hidden" value="<?php echo $ticket ?>" />
                    <input name="input_mode" type="hidden" value="<?php echo $bpn001Out->input_mode(); ?>" />
                    <input name='input_type_email' type='hidden' value='<?php echo $inputTypeEmail; ?>' />
                    <input name='input_type_number' type='hidden' value='<?php echo $inputTypeNumber; ?>' />

                    <div class="section">
                        <div class="comiket_block">

<?php
                        ///////////////////////////////////////////////
                        // 顧客情報入力エリア
                        ///////////////////////////////////////////////
                    if($bpnType == "1"):
                        include_once dirname(__FILE__) . '/parts/input_cstmr_size_change.php';
                    else:
                        include_once dirname(__FILE__) . '/parts/input_event_size_change.php';
                    endif;    
                    ///////////////////////////////////////////////
                    // 物販情報入力エリア
                    ///////////////////////////////////////////////
                    if($bpnType == "1"):
                        include_once dirname(__FILE__) . '/parts/input_shohin_size_change.php';
                    else:
                        include_once dirname(__FILE__) . '/parts/input_active_shohin_size_change.php';
                    endif;  
?>
                        </div>

                    <!--▼お支払い方法-->

                    <?php include_once dirname(__FILE__) . '/parts/input_payment_method_size_change.php'; ?>

                    <!--▲お支払い方法-->
<?php
                        ///////////////////////////////////////////////
                        // アテンションエリア
                        ///////////////////////////////////////////////
//                        include_once dirname(__FILE__) . '/parts/input_attention_area.php';
?>
              
                <?php 
                if(!isset($dispItemInfo["input_buppan_lbls"]["expiry_all"])):?>
                    <p class="text_center">
                        <input id="submit_button" type="submit" name="submit" value="同意して次に進む（入力内容の確認）">
                    </p>
                <?php endif;?>
                    <br>
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
<?php
    if (!$isSmartPhone) {
?>
    <script charset="UTF-8" type="text/javascript" src="/common/js/jquery.autoKana.js"></script>
<?php
    }
?>
    <script charset="UTF-8" type="text/javascript" src="/bpn/js/define.js" id="definejs-arg"
    data-G_DEV_INDIVIDUAL="<?php echo Sgmov_View_Bpn_Common::COMIKET_DEV_INDIVIDUA; ?>"
    data-G_DEV_BUSINESS="<?php echo Sgmov_View_Bpn_Common::COMIKET_DEV_BUSINESS; ?>"
    >
    </script>
    <script charset="UTF-8" type="text/javascript" src="/bpn/js/input.js?<?php echo $strSysdate; ?>"></script>
</body>
</html>


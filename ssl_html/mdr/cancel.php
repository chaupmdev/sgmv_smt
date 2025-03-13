<?php
/**
 * 催事・イベント配送受付お申し込み入力画面を表示します。
 * @package    ssl_html
 * @subpackage MDR
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('mdr/Cancel');

/**#@-*/

// 処理を実行
$view = new Sgmov_View_Mdr_Cancel();
$forms = $view->execute();

/** チケット **/
$ticket = $forms['ticket'];

/** フォーム **/
$eve001Out = $forms['outForm'];

/** デフォルト設定 **/
$dispItemInfo = $forms['dispItemInfo'];

/**　エラーフォーム **/
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

//GiapLN Update add button Back  2022/04/07
$userType = isset($_SESSION['MDR_LOGIN']['user_type']) ? $_SESSION['MDR_LOGIN']['user_type'] : -1;
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
    <title>催事・イベント配送受付サービスのキャンセルお申し込み｜お問い合わせ│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
<?php
    // キャッシュ対策
    $sysdate = new DateTime();
    $strSysdate = $sysdate->format('YmdHi');
?>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <link href="/mdr/css/eve.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <link href="/css/confirmDialog.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
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
            <li class="current">催事・イベント配送受付サービスのキャンセルお申し込み</li>
        </ul>
    </div>
    <div id="main">
        <div class="wrap clearfix">
            <h1 class="page_title">催事・イベント配送受付サービスのキャンセルお申し込み</h1>
            <p class="sentence">
                ご確認のうえ、「キャンセル送信する」ボタンを押してください。
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
                            $selectedEventNmae = Sgmov_View_Mdr_Input::_getLabelSelectPulldownData($eve001Out->event_cds(), $eve001Out->event_lbls(), $eve001Out->event_cd_sel());
                            if(!empty($selectedEventNmae)) {
                                $selectedEventNmae .= '係';
                            }
                            echo $selectedEventNmae;
                    ?>
                    <br/>TEL: 03-5534-1080(受付時間:平日10時～17時)<?php if ($eve001Out->eventsub_cd_sel() == '4') : ?>※年内は12月28日まで<?php endif; ?>
                </p>
            </div>

<?php
    }
?>
            <div class="section other">
                <form action="/mdr/cancel_comp" data-feature-id="<?php echo Sgmov_View_Mdr_Common::FEATURE_ID ?>" data-id="<?php echo Sgmov_View_Mdr_Common::GAMEN_ID_MDR001 ?>" method="post">
                    <input name="param" type="hidden" value="<?php echo @filter_input(INPUT_GET, 'param'); ?>" />
                    <input name="ticket" type="hidden" value="<?php echo $ticket ?>" />
                    <input name="input_mode" type="hidden" value="<?php echo $eve001Out->input_mode(); ?>" />
                    <input name='input_type_email' type='hidden' value='<?php echo $inputTypeEmail; ?>' />
                    <input name='input_type_number' type='hidden' value='<?php echo $inputTypeNumber; ?>' />

                    <div class="section">
                        <div class="comiket_block">

<?php
                        ///////////////////////////////////////////////
                        // 顧客情報入力エリア
                        ///////////////////////////////////////////////
                        include_once dirname(__FILE__) . '/parts/input_cstmr_cancel.php';

                        ///////////////////////////////////////////////
                        // 搬出情報入力エリア
                        ///////////////////////////////////////////////
                        include_once dirname(__FILE__) . '/parts/input_inbound_cancel.php';
?>
                    </div>

                    <!--▼お支払い方法-->

                    <?php include_once dirname(__FILE__) . '/parts/input_payment_method_cancel.php'; ?>

                    <!--▲お支払い方法-->
   
                    <p class="text_center">
                        <input id="submit_button" onclick="document.getElementById('myModal').style.display='block'" type="button" name="submit" value="キャンセル送信する">
                    </p>
                    <?php if ($userType === 1) {?>
                    <p class="text_center">
                        <input  class="btnBackEvent" type="button" name="btnBack" onclick="backRedirectEvent('<?php echo $eventNm; ?>', '<?php echo $userType; ?>','<?php echo $baseUrl; ?>');" value="戻る">
                    </p>
                    <?php } ?>
                    <br>
                    <?php
                        include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/confirmMsg.php';
                    ?>
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
    <script charset="UTF-8" type="text/javascript" src="/mdr/js/define.js" id="definejs-arg"
    data-G_DEV_INDIVIDUAL="<?php echo Sgmov_View_Mdr_Common::COMIKET_DEV_INDIVIDUA; ?>"
    data-G_DEV_BUSINESS="<?php echo Sgmov_View_Mdr_Common::COMIKET_DEV_BUSINESS; ?>">
    </script>
    <script charset="UTF-8" type="text/javascript" src="/mdr/js/input.js?<?php echo $strSysdate; ?>"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/confirmDialog.js?<?php echo $strSysdate; ?>"></script>
</body>
</html>


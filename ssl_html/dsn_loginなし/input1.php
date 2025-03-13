<?php
/**
 * 催事・イベント配送受付お申し込み入力画面を表示します。
 * @package    ssl_html
 * @subpackage DSN
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

// Basic認証
require_once dirname(__FILE__) . '/../../lib/component/auth_event.php';

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
// イベント識別子(URLのpath)はマジックナンバーで記述せずこの変数で記述してください
$dirDiv=Sgmov_Lib::setDirDiv(dirname(__FILE__));
Sgmov_Lib::useView($dirDiv.'/Input');

/**#@-*/

// 処理を実行
$view = new Sgmov_View_Dsn_Input();
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
    <link href="/<?=$dirDiv?>/css/eve.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
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
            <h1 class="page_title"  style="margin-bottom:15px !important;">催事・イベント配送受付サービスのお申し込み</h1>
            <p class="sentence" style="margin-bottom: 5px;margin-top: 10px;">
                お申し込みから当日までの流れは<a style="color: #1774bc;" href="<?=$dispItemInfo['dispEvent']['manual']?>" target="_blank">こちら</a><br />
                以下のフォームにもれなくご入力をお願いいたします。
                <br />
                <span class="red" style="font-size: 1.5em;font-weight: bolder;">
                    必ず「sagawa-mov.co.jp」からのメールを受信する設定にしてください。
                </span>
                <span class="red">
                    <br>詳しくは<a href="#bounce_mail">こちら</a>
                </span>
                <br /><a href="http://www.sagawa-exp.co.jp/send/howto-packing/" target="_blank" style="color: #1774bc; text-decoration: underline;">梱包方法ガイドライン</a>
                <br />「<a href="http://www.sagawa-exp.co.jp/help/" target="_blank" style="color: #1774bc; text-decoration: underline;">商標についてーリンクガイドライン</a>」の一読と確認をお願いいたします。
                <br />沖縄など航空便対応エリアからご利用のお客様は、「航空便ご利用上の注意」及び「航空宅配便等個建運送約款」をご確認の上でお申し込みください。
                <br /><a href="/pdf/kokubin_goriyoujyono_tyui.pdf" target="_blank" style="color: #1774bc; text-decoration: underline;">航空便ご利用上の注意はこちら</a>
                <br /><a href="/pdf/kokutakuhaibinnado_unso_yakkan.pdf" target="_blank" style="color: #1774bc; text-decoration: underline;">航空宅配便等個建運送約款はこちら</a>
            </p>
            <p class="sentence disp_gooutcamp" style="display: none;">
                <span class="red">
                    ※貸切（チャーター）ご希望の方は、以下よりご連絡ください。（法人のみ）<br/>
                    SG ムービング株式会社<br/>
                    <?=$dispItemInfo['dispEvent']['customName']?>?> <br/>
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
                    <br/><?=$dispItemInfo['dispEvent']['customName']?>
                    <br/>TEL: <?=$dispItemInfo['dispEvent']['tel1']?>(受付時間:平日10時～17時)<?php if ($eve001Out->eventsub_cd_sel() == '4') : ?>※年内は12月28日まで<?php endif; ?>
                </p>
            </div>

<?php
    }
?>
            <div class="section other">
                <form action="/<?=$dirDiv?>/check_input" data-feature-id="<?php echo Sgmov_View_Dsn_Common::FEATURE_ID ?>" data-id="<?php echo Sgmov_View_Dsn_Common::GAMEN_ID_DSN001 ?>" method="post">
                    <input name="ticket" type="hidden" value="<?php echo $ticket ?>" />
                    <input name="input_mode" type="hidden" value="<?php echo $eve001Out->input_mode(); ?>" />
                    <input name='input_type_email' type='hidden' value='<?php echo $inputTypeEmail; ?>' />
                    <input name='input_type_number' type='hidden' value='<?php echo $inputTypeNumber; ?>' />
                    <input id="comiket_customer_kbn_sel1" class="comiket_customer_kbn" name="comiket_customer_kbn_sel" type="hidden" value="0">
<?php
                        ///////////////////////////////////////////////
                        // 顧客情報入力エリア
                        ///////////////////////////////////////////////
                        include_once dirname(__FILE__) . '/parts/input_cstmr.php';

                        ///////////////////////////////////////////////
                        // 搬入情報入力エリア
                        ///////////////////////////////////////////////
                        include_once dirname(__FILE__) . '/parts/input_outbound.php';

                        ///////////////////////////////////////////////
                        // 搬出情報入力エリア
                        ///////////////////////////////////////////////
                        include_once dirname(__FILE__) . '/parts/input_inbound.php';
?>
                    </div>

                    <!--▼お支払い方法-->

                    <?php include_once dirname(__FILE__) . '/parts/input_payment_method.php'; ?>

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
                     <p id="contact" class="sentence">
                        お問い合わせ先 
                        <br />
                        <?=$dispItemInfo['dispEvent']['sgName']?> <?=$dispItemInfo['dispEvent']['customName']?>
                        　<?=$dispItemInfo['dispEvent']['tel1']?> (10:00～17:00) 
                        <br />
                        callcenter-kyouyuu@sagawa-exp.co.jp
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
    <script charset="UTF-8" type="text/javascript" src="/<?=$dirDiv?>/js/define.js" id="definejs-arg"
    data-G_DEV_INDIVIDUAL="<?php echo Sgmov_View_Dsn_Common::COMIKET_DEV_INDIVIDUA; ?>"
    data-G_DEV_BUSINESS="<?php echo Sgmov_View_Dsn_Common::COMIKET_DEV_BUSINESS; ?>">
    </script>
    <script charset="UTF-8" type="text/javascript" src="/<?=$dirDiv?>/js/input.js?<?php echo $strSysdate; ?>"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/from_to_pulldate.js?<?php echo $strSysdate; ?>"></script>
</body>
</html>
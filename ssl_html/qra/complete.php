<?php

/**
 * 手荷物受付サービスのお申し込み完了画面を表示します。
 * @package    ssl_html
 * @subpackage RMS
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
// イベント識別子(URLのpath)はマジックナンバーで記述せずこの変数で記述してください
$dirDiv = Sgmov_Lib::setDirDiv(dirname(__FILE__));
Sgmov_Lib::useView($dirDiv . '/Complete');
Sgmov_Lib::useForms(array('Error', 'QraSession'));
/**#@-*/


$exceptionFlg = FALSE;
// 処理を実行
$view = new Sgmov_View_Qra_Complete();
$qraOutForm = array();
$eventData = array();
$eventsubData = array();

try {
    $forms = $view->execute();
    /** フォーム　*/
    $qraOutForm = $forms['outForm'];
    /** イベント情報　*/
    $eventData =  $forms['eventData'];
    $eventsubData =  $forms['eventsubData'];

    $typeSel = $forms['type_sel'];
    $convenienceSel = $forms['convenience_sel'];
    /** 支払方法　**/
    $paymentMethodCd = $forms['payment_method_cd'];

    $collectDate = $forms['collect_date'];
    $entryType = $forms['type'];
    $qrCodeString = @$qraOutForm->qr_code_string();
    $merchantResult = false;
    if ($paymentMethodCd != '3') { // 電子マネーではない場合
        if ($typeSel != 1 && $qraOutForm->merchant_result() == 0 && @!empty($qrCodeString)) {
            $merchantResult = true;
        }
        if ($qraOutForm->merchant_result() == 0) {
            $merchantResult = true;
        }
    }
} catch (Sgmov_Component_Exception $e) {
    $exceptionFlg = TRUE;
    $exInfo = $e->getInformaton();
    $qraOutForm = $exInfo['outForm'];
    $paymentMethodCd = $exInfo['payment_method_cd'];
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
    <meta name="Keywords" content="佐川急便,ＳＧムービング,sgmoving,無料見積り,お引越し,設置,法人,家具輸送,家電輸送,精密機器輸送,設置配送" />
    <meta name="Description" content="引越・設置のＳＧムービング株式会社のWEBサイトです。個人の方はもちろん、法人様への実績も豊富です。無料お見積りはＳＧムービング株式会社(フリーダイヤル:0570-056-006(受付時間:平日9時～17時))まで。" />
    <meta name="author" content="SG MOVING Co.,Ltd" />
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved." />
    <title>クレジットカードお支払い受付サービスのお申し込み│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css" rel="stylesheet" type="text/css" />
    <script src="/js/ga.js" type="text/javascript"></script>
</head>

<body>
    <style>
        .contents-info {
            width: 50%;
            margin-top: 0px;
            margin-left: auto;
            margin-right: auto;
        }

        .cnv-url {
            font-size: unset;
            word-break: break-all;
        }

        @media screen and (max-width:905px) {
            .contents-info {
                clear: both;
                width: 100%;
                margin-top: 30px;
            }

            .cnv-url {
                font-size: 2.15vw;
            }
        }

        .dsp-inbl {
            display: inline-block;
        }

        .ml10px {
            margin-left: 10px !important;
        }

        .mb10px {
            margin-bottom: 10px !important;
        }

        .ml46px {
            margin-left: 46px !important;
        }

        .fws15 {
            font-size: 15px;
        }

        .fl-r {
            float: right;
        }

        .ht34px {
            height: 34px;
        }
    </style>
    <?php
    $gnavSettings = 'contact';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/header.php';
    ?>

    <div id="breadcrumb">
        <ul class="wrap">
            <li><a href="/">ホーム</a></li>
            <li><a href="/contact/">お問い合わせ</a></li>
            <li class="current">クレジットカードお支払い受付サービスのお申し込み内容の確定</li>
        </ul>
    </div>

    <div id="main">
        <div class="wrap clearfix">
            <h1 class="page_title">クレジットカードお支払い受付サービスのお申し込み内容の確定</h1>
            <div class="section">
                <?php if ($merchantResult) : ?>
                    <h2 class="complete_msg">
                        決済いただき、ありがとうございます。
                    </h2>
                    <br>作業員に決済が終了したことをお伝えください。
                <?php endif; ?>

            </div>
            <!--section  -->
        </div>
        <!--wrap clearfix-->
    </div>
    <!--main-->


    <?php $footerSettings = 'under';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/footer.php'; ?>

    <script charset="UTF-8" type="text/javascript" src="/js/lodash.min.js"></script>
    <!--<![endif]-->
    <script charset="UTF-8" type="text/javascript" src="/common/js/multi_send.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/common.js"></script>
</body>

</html>

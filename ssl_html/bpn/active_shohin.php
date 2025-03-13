<?php
/**
 * 当日物販受付お申し込み入力画面を表示します。
 * @package    ssl_html
 * @subpackage EVE
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('bpn/ActiveShohin');


/**#@-*/

// 処理を実行
$view = new Sgmov_View_Bpn_ActiveShohin();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Bpn001Out
 */
$bpn001Out = $forms['outForm'];

/**
 * 表示項目
 * @var array
 */
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

// 画面タイトル
$title = "卓上飛沫ブロッカーのお申込み";
if($bpn001Out->bpn_type() == "2" && $bpn001Out->shohin_pattern() == "2"):
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
    <meta name="Description" content="当日物販受付サービスのお申し込みのご案内です。" />
    <meta name="author" content="SG MOVING Co.,Ltd" />
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved." />
    <title><?php echo $title;?>│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
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
    <script charset="UTF-8" type="text/javascript" src="/js/jquery-2.2.0.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/lodash.min.js"></script>
    <script src="/js/ga.js" type="text/javascript"></script>
    <script src="/js/common.js"></script>
        <!--<![endif]-->
    <script src="/js/smooth_scroll.js"></script>
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
            <?php if($bpn001Out->bpn_type() == "2" && $bpn001Out->shohin_pattern() !== "2"):?>
                <p class="sentence mb5px">
                    以下のフォームにもれなくご入力をお願いいたします。
                    <br />
                     <span class="red fs1pt5em fwb">
                        必ず「sagawa-mov.co.jp」からのメールを受信する設定にしてください。
                    </span>
                    <span class="red">
                        <br>詳しくは<a href="#bounce_mail">こちら</a>
                    </span>
                    <br />「商標についてーリンクガイドライン」の一読と確認をお願いいたします。
                    <br /><a href="http://www.sagawa-exp.co.jp/help/" target="_blank">http://www.sagawa-exp.co.jp/help/</a>
                </p>
             <?php endif;?>
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
                <p class="under lhn">
                    インターネットでお申し込みが出来なかった場合は、
                    <br />下記ダイヤルにお問い合わせください。
                    <br />
                    <br />SG ムービング株式会社<!-- 東京営業所 -->
                    <br/>
                    <?php
                            $selectedEventNmae = Sgmov_View_Bpn_ActiveShohin::_getLabelSelectPulldownData($bpn001Out->event_cds(), $bpn001Out->event_lbls(), $bpn001Out->event_cd_sel());
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
                <form id = "form1" action="/bpn/check_input2" method="post" data-feature-id="<?php echo Sgmov_View_Bpn_Common::FEATURE_ID ?>" data-id="<?php echo Sgmov_View_Bpn_Common::GAMEN_ID_BPN001 ?>" method="post">
                    <input name="ticket" type="hidden" value="<?php echo $ticket; ?>" />
                    <input name="input_mode" type="hidden" value="<?php echo $bpn001Out->input_mode(); ?>" />
                    <input name="bpn_type" type="hidden" value="<?php echo $bpn001Out->bpn_type(); ?>" />
                    <input name="shikibetsushi" type="hidden" value="<?php echo $bpn001Out->shikibetsushi(); ?>" />
                    <input name="shohin_pattern" type="hidden" value="<?php echo $bpn001Out->shohin_pattern(); ?>" />
                    <input name='input_type_email' type='hidden' value='<?php echo $inputTypeEmail; ?>' />
                    <input name='input_type_number' type='hidden' value='<?php echo $inputTypeNumber; ?>' />

                    <div class="section">
<?php

                        ///////////////////////////////////////////////
                        // 顧客情報入力エリア
                        ///////////////////////////////////////////////
                        include_once dirname(__FILE__) . '/parts/input_event.php';

                        if($bpn001Out->shohin_pattern() == "2"){
                            ///////////////////////////////////////////////
                            // 商品パターン２
                            ///////////////////////////////////////////////
                            include_once dirname(__FILE__) . '/parts/input_cnpszi.php';
                        }else{
                            ///////////////////////////////////////////////
                            // 物販情報入力エリア
                            ///////////////////////////////////////////////
                            include_once dirname(__FILE__) . '/parts/input_active_shohin.php';
                        }
                       
?>
                    </div>


                    <?php include_once dirname(__FILE__) . '/parts/input_payment_method_active_shohin.php'; ?>

                    <!--▲お支払い方法-->

                  
                    <?php 
                        if(!isset($dispItemInfo["input_buppan_lbls"]["expiry_all"])): ?>
                            <!--<p class="sentence">内容についてご同意頂ける方は、下のボタンを押してください。</p>-->
                            <p class="text_center">
                                <input id="submit_button" type="button" name="submit1" value="同意して次に進む（入力内容の確認）" style="margin-top: 0px !important;">
                            </p><br><br>
                    <?php 
                        endif;    
                    ?>

                    <div class="pay_digital_money_attention">
                    <!--<div class="pay_digital_money_attention dsp-none">-->
                        ※ご利用可能な電子マネーは<a href="/pdf/available_degital_money.pdf" class = "clr_blue tdu" target="_blank">こちら</a><br/><br/>
                        <strong class="red">※ 電子マネーでのお支払いは、残高不足にご注意ください。なお1万円を超える場合は、お取り扱いできません。</strong>
                        <br>
                    </div>
                    <br>
                    <div class="pay_digital_money_attention dsp-none">
                        ※ご利用可能な電子マネーは<a href="/pdf/available_degital_money.pdf" class = "clr_blue tdu" target="_blank">こちら</a><br/><br/>
                        <strong class="red">※ 電子マネーでのお支払いは、残高不足にご注意ください。なお1万円を超える場合は、お取り扱いできません。</strong>
                        <br>
                        <br>
                        <strong class="red">運賃目安料金表は<a href="https://www2.sagawa-exp.co.jp/send/fare/input/" class = "clr_blue" target="_blank">こちら</a></strong>
                        <br>
                    </div>

                    <!--<p class="sentence">
                       <strong class="red">※迷惑メールを設定されている方は、必ず「sagawa-mov.co.jp」からのメールを受信する設定にしてください。
                            <br>詳しくは「<a href="#bounce_mail">ご連絡メールが届かない場合</a>」をご確認ください。</strong>
                    </p>-->
                   

<?php
                        ///////////////////////////////////////////////
                        // アテンションエリア
                        ///////////////////////////////////////////////
                        
                //     include_once dirname(__FILE__) . '/parts/input_attention_area.php';
                //if($bpn001Out->eventsub_cd_sel() == "302" && $bpn001Out->bpn_type() == "2" && $bpn001Out->shohin_pattern() == "2"):
                
                // 初期表示　accordition　false　テンプレート
                include_once dirname(__FILE__) . '/parts/input_attention_area_active_shohin.php';
                //else:
                  //  include_once dirname(__FILE__) . '/parts/input_attention_area.php';
                //endif;
?>

                   

             <?php 
                 //if($bpn001Out->bpn_type() == "2" && $bpn001Out->shohin_pattern() !== "2"):
                    //if(!isset($dispItemInfo["input_buppan_lbls"]["expiry_all"])):?>
                       <!--  <p class="sentence"><span class="sp_only pcH">上記「個人情報の取り扱い」および「特定商取引法に基づく表記」の</span>内容についてご同意頂ける方は、下のボタンを押してください。</p>
                        <p class="text_center">
                            <input id="submit_button" type="button" name="submit1" value="同意して次に進む（入力内容の確認）">
                        </p> -->
              <?php //endif; 
              //endif;?>      
                </form>
            </div>
        </div>
    </div>
    <!--main-->
      <script type="text/javascript">
        $(document).ready(function(){
            $("#submit_button").on("click", function(){
                $(this).attr("disabled", "disabled");
                $("#form1").submit();
            });
        });
    </script>

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
    data-G_DEV_INDIVIDUAL="<?php //echo Sgmov_View_Eve_Common::COMIKET_DEV_INDIVIDUA; ?>"
    data-G_DEV_BUSINESS="<?php //echo Sgmov_View_Eve_Common::COMIKET_DEV_BUSINESS; ?>"
    >
    </script>
    <script charset="UTF-8" type="text/javascript" src="/bpn/js/input.js?<?php echo $strSysdate; ?>"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/from_to_pulldate.js?<?php echo $strSysdate; ?>"></script>
    <style type="text/css">
        .act{
            display: none;
        }
    </style>
</body>
</html>


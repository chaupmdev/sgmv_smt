<?php
/**
 * 手荷物受付サービスのお申し込み完了画面を表示します。
 * @package    ssl_html
 * @subpackage Zzy
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('zzy/Complete');
Sgmov_Lib::useForms(array('Error', 'EveSession'));
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Zzy_Complete();
$eveOutForm = array();
$eventData = array();
$eventsubData = array();

try {
    $forms = $view->execute();

    /** フォーム　**/
    $eveOutForm = $forms['outForm'];

    /** 往路　**/
    $typeSel = $forms['type_sel'];

    /** 支払方法　**/
    $paymentMethodCd = $forms['payment_method_cd'];

    /** コンビニー　**/
    $convenienceSel = $forms['convenience_sel'];

    /** イベント情報　**/
    $eventData =  $forms['eventData'];
    
    /** イベントサブ情報　**/
    $eventsubData =  $forms['eventsubData'];

    /** 預り日　**/
    $collectDate = $forms['collect_date'];

    /** コミケID **/
    $existsComiketId = $forms["existsComiketId"];

    $merchantResult = false;
    if ($paymentMethodCd != '3') { // 電子マネーではない場合
        if($eveOutForm->merchant_result() == 0){ 
            // ベリトランス連携失敗
            $merchantResult = true;
        }
    }

} catch(Sgmov_Component_Exception $e) {
    $exInfo = $e->getInformaton();
    $eveOutForm = $exInfo['outForm'];
    $paymentMethodCd = $exInfo['payment_method_cd'];
}

$detect = new MobileDetect();
$isSmartPhone = $detect->isMobile();
if ($isSmartPhone) {
    $inputTypeEmail  = 'email';
    $inputTypeNumber = 'number';
} else {
    $inputTypeEmail  = 'text';
    $inputTypeNumber = 'text';
}

// キャッシュ対策
$sysdate = new DateTime();
$strSysdate = $sysdate->format('YmdHi');
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
    <title>催事・イベント配送受付サービスのお申し込みフォームの完了│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css" rel="stylesheet" type="text/css" />
    <link href="/zzy/css/eve.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <script src="/js/ga.js" type="text/javascript"></script>
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
        clear:both;
        width: 100%;
    }
    .cnv-url {
        font-size: 2.15vw;
    }
}

</style>
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
        <li class="current">催事・イベント配送受付サービスのお申し込みフォームの完了</li>
    </ul>
</div>

<div id="main">
    <div class="wrap clearfix">
        <h1 class="page_title">催事・イベント配送受付サービスのお申し込みフォームの完了</h1>
        <div class="section">


            <?php if($merchantResult):?> <!-- merchantResult if -->
                <?php if($paymentMethodCd == 2):　?>
                    <!-- クレジットカード-->
                    <h2 class="complete_msg">
                        クレジットカードでの決済が
                        <br />できませんでした。
                    </h2>
                    <br/>
                    お手数ですが、別のお支払い方法を選択して、
                    <br/>再登録ください。
                <?php endif;?> 
            <?php else:?> 
                <h2 class="complete_msg">ご登録ありがとうございました。</h2><br><br>
                登録したメールアドレスに登録完了メールを送りました。<br><br>
                <?php if($typeSel == "1"): ?>
                    <table class="default_tbl">
                        <tr>
                            <th scope="row">申込み番号</th>
                            <td><?php echo sprintf('%010d', $eveOutForm->qr_code_string()); ?></td>
                        </tr>
                    </table>
                    <br/><br/>
                <?php endif; ?>
            <?php endif;?>

            <div style="clear: both;">
                <!--決済　-->
                <?php if ($eventsubData['manual_display'] == '1') { ?>
                    ご不明な点はメールに添付されている説明書、もしくは下記のボタンから説明書をダウンロードして、ご確認ください。<br/><br/>
                    <div class ="fs20px">
                        <a class="button_link2 clr-blue" href="/zzy/pdf/manual/<?php echo $eventData['name']; ?><?php echo $eventsubData['name']; ?>.pdf" target="_blank">
                            <img src="/images/common/img_icon_pdf.gif" width="18" height="21" alt="">&nbsp;説明書のDL
                        </a>
                    </div>
                <?php } ?>

                <?php if($typeSel == "2") : // 2:搬出?>
                    <br/>・ イベント当日は、受付にて、メールで表示されたQRコードを読込後、送り状を渡します。
                    <?php if($paymentMethodCd === '3') : ?>
<!--                        <br/><br/>・ イベント当日、受付にて電子マネーでの決済をお願いします。-->
                        <br/><br/>・ イベント当日、受付にて現金でお支払いお願いします。
                    <?php endif; ?>
                    <br/>
                <?php endif; ?>

                <br/>

                <?php if(!empty($existsComiketId)): ?>
                    <b>引き継き搬出申込される方は<a href="/zzy/input2/<?php echo $existsComiketId; ?>" class ="clr-blue fwb txt-underline" target="_self">こちら</a></b>
                <?php else : ?>
                    <b>引き継き宅配をご利用される方は<a href="/zzy/input" target="_self" class ="clr-blue fwb txt-underline">こちら</a></b>
                <?php endif;?>                    
               
            </div>
            <br/>
            
        <?php if($typeSel != "1"): ?>
            <script charset="UTF-8" type="text/javascript" src="/js/jquery-2.2.0.min.js"></script>
            <script charset="UTF-8" type="text/javascript" src="/js/jquery.qrcode.min.js"></script>
            <script>
                $(function(){
                    $("#qrcode-output").qrcode({text:"<?php echo $eveOutForm->qr_code_string(); ?>"});
                });
            </script>
            <?php if(!$merchantResult): ?><!-- merchantResult else -->  
                <div class="contents-info">
                    <table class="default_tbl w_100Per table-layout-fixed">
                        <tr>
                            <td class ="pb10px">
                                <!-- 配送用申込み情報 -->
                                <table class="default_tbl w_100Per">
                                    <tbody>
                                        <tr>
                                            <th scope="row" class = "w_30Per wsp-nowrap"><b>申込み番号</b></th>
                                            <td><?php echo sprintf('%010d', $eveOutForm->qr_code_string()); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <br/>
                                <div id="qrcode-output" class ="tac mt10px"></div>
                            </td>
                        </tr>
                    </table>
                </div>
            <?php endif;?><!-- merchantResult endif -->  
        <?php endif; ?>

            <div style="clear: both;">
                <?php if($merchantResult):?>
                    <div class="mt10px mb10px fs15px">
                        <div class= "dsp-inbl">
                            <img class= "fl-r ht34px" src="/images/common/img_tel_001.png" >
                        </div>
                        <div class="ml46px">
                            <span><b>受付時間：平日10時～17時</span>
                        </div>
                    </div>
                <?php endif;?>
                <div class="btn_area">
                    <a class="next" href="/">ホームへ戻る</a>
                </div>
            </div>
        </div><!--section  -->
    </div><!--wrap clearfix-->
</div><!--main-->


<?php  $footerSettings = 'under';
        include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/footer.php';?>

    <script charset="UTF-8" type="text/javascript" src="/js/lodash.min.js"></script>
    <!--<![endif]-->
    <script charset="UTF-8" type="text/javascript" src="/common/js/multi_send.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/common.js"></script>
</body>
</html>
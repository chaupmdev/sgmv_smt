<?php

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('azk/Complete');
Sgmov_Lib::useForms(array('Error', 'AzkSession'));
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Azk_Complete();

try {
    $forms = $view->execute();

    /** フォーム　**/
    $eveOutForm = $forms['outForm'];

    $eventsubData = $forms['eventsubData'];

    /** イベント**/
    $eventName = Sgmov_View_Azk_Common::_getLabelSelectPulldownData($eveOutForm->event_cds(), $eveOutForm->event_lbls(), $eveOutForm->event_cd_sel());

    // 支払い方法
    $paymentMethodCd = $eveOutForm->comiket_payment_method_cd_sel();

    $merchantResult = false;
    if ($paymentMethodCd != '3') { // 電子マネーではない場合
        if($eveOutForm->merchant_result() == 0){ 
            // ベリトランス連携失敗
            $merchantResult = true;
        }
    }
} catch(Sgmov_Component_Exception $e) {
    $exInfo = $e->getInformaton();
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
    <title>催事・イベント手荷物預かりサービスのお申し込みフォームの完了│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
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
        clear:both;
        width: 100%;
        margin-top: 30px;
    }
    .cnv-url {
        font-size: 2.15vw;
    }
}
.dsp-inbl{ display: inline-block; }

.mb10px{ margin-bottom: 10px !important; }

.ml46px{ margin-left: 46px !important; }

.fws15{font-size: 15px;}

.fl-r{float:right;}

.ht34px{height: 34px;}
</style>
<?php
    $gnavSettings = 'contact';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/header.php';
?>

<!-- <div id="breadcrumb">
    <ul class="wrap">
        <li><a href="/">ホーム</a></li>
        <li><a href="/contact/">お問い合わせ</a></li>
        <li class="current">催事・イベント配送受付サービスのお申し込みフォームの完了</li>
    </ul>
</div> -->

<div id="main">
    <div class="wrap clearfix">
        <h1 class="page_title">催事・イベント手荷物預かりサービスのお申し込みフォームの完了</h1>
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
                
            <?php endif;?>

            <div style="clear: both;">
<!--                 <?php if(!$merchantResult && $paymentMethodCd == 1): ?>
                    <div>
                        <?php switch ($convenienceSel) {
                            case '1':?>
                                <br>※コンビニ：受付番号をメモまたは払込票を印刷し、
                                <br><br>セブンイレブンのレジカウンターにてお支払い手続きをお願いいたいます。
                                <br>
                        <?php break;
                            case '2':?>
                                <br>※受付番号を控えて、コンビニ備え付けの端末でお支払い手続きをお願いいたします。
                                <br><br>端末操作方法は、各コンビニエンスストアのホームページにてご確認ください。
                                <br>
                                <ul class="cnv_list">
                                    <li>・ローソン</li>
                                    <li>・セイコーマート</li>
                                    <li>・ファミリーマート</li>
                                    <li>・ミニストップ</li>
                                </ul>
                                <br>
                        <?php break;
                            case '3': ?>
                            <ul class="cnv_list">
                                <li></li>
                            </ul>
                            <br>※受付番号をメモまたは払込票を印刷し、
                            <br><br>デイリーヤマザキ店頭レジにてお支払い手続きをお願いいたいます。
                            <br>
                        <?php break;
                            default:
                            break;
                        }?>
                    </div>
                <?php endif;?> -->


                <!--決済　-->
                <?php if ($eventsubData['manual_display'] == '1') { ?>
                    <br/><br/>ご不明な点はメールに添付されている説明書、もしくは下記のボタンから説明書をダウンロードして、ご確認ください。<br/><br/>
                    <div style="font-size:20px;">
                        <a class="button_link2" href="/azk/pdf/manual/<?php echo trim($eventName); ?>.pdf" target="_blank" style="color:blue;">
                            <img src="/images/common/img_icon_pdf.gif" width="18" height="21" alt="">&nbsp;説明書のDL
                        </a>
                    </div>
                <?php } ?>

                <?php if(!$merchantResult):?>
                    <!--送り状発行-->
                    <br/>・ イベント当日は、受付にて、メールで表示されたQRコードを読込後、送り状を渡します。
                    <?php if($paymentMethodCd === '3') : ?>
                        <br/><br/>・ イベント当日、受付にて電子マネーでの決済をお願いします。
                    <?php endif; ?>
                    <br/><br/>
                    <!-- <b>引き継き宅配をご利用される方は<a href="/azk/input/<?php echo $eveOutForm->shikibetsushi(); ?>" target="_self" style="color:blue;font-weight: bold;text-decoration: underline;">こちら</a></b> -->
                <?php endif; ?>
            </div>
            <br/><br>
            
            <script charset="UTF-8" type="text/javascript" src="/js/jquery-2.2.0.min.js"></script>
            <script charset="UTF-8" type="text/javascript" src="/js/jquery.qrcode.min.js"></script>
            <script>
                $(function(){
                    $("#qrcode-output").qrcode({text:"<?php echo $eveOutForm->qr_code_string(); ?>"});
                });
            </script>
            <?php if(!$merchantResult): ?><!-- merchantResult else -->  
                <div class="contents-info">
                    <table class="default_tbl" style="width:100%;table-layout: fixed;">
                        <tr>
                            <td style="padding-bottom: 10px;">
                                <!-- 配送用申込み情報 -->
                                <table class="default_tbl" style="width:100%;">
                                    <tbody>
                                        <tr>
                                            <th scope="row" style="width: 30%;white-space: nowrap;"><b>申込み番号</b></th>
                                            <td><?php echo sprintf('%010d', $eveOutForm->qr_code_string()); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- <?php if($paymentMethodCd == 1) : // コンビニ前払い?>
                                    <div style="margin-top: 10px;">
                                        コンビニ前払いの受付番号は以下の通りです。
                                    </div>
                                    <br>
                                    <table class="default_tbl" style="width:100%;table-layout: fixed;">
                                        <tbody>
                                            <tr>
                                                <th scope="row"><b>コンビニ：受付番号</b></th>
                                            </tr>
                                            <tr>
                                                <td scope="row"><?php echo $eveOutForm->receipt_cd(); ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><b>払込票URL</b></th>
                                            </tr>
                                            <tr>
                                                <td class="cnv-url">
                                                    <a href="<?php echo $eveOutForm->payment_url(); ?>" target="_blank"><?php echo $eveOutForm->payment_url(); ?></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php endif;?> -->
                                <br/>
                                <div id="qrcode-output" style="text-align: center;margin-top: 10px;"></div>
                            </td>
                        </tr>
                    </table>
                </div>
            <?php endif;?><!-- merchantResult endif -->  

            <div style="clear: both;">
                <?php if($merchantResult):?>
                    <div class="mt10px mb10px fws15">
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
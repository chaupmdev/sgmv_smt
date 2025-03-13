<?php
/**
 * 手荷物受付サービスのお申し込み完了画面を表示します。
 * @package    ssl_html
 * @subpackage EVE
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('bpn/Complete');
Sgmov_Lib::useForms(array('Error', 'BpnSession'));
/**#@-*/

$merchantResult = FALSE;
// 処理を実行
$view = new Sgmov_View_Bpn_Complete();
//$eveOutForm = array();
$eventsubData = array();

$buppanOutForm = array();

try {
    $forms = $view->execute();

    // 配送/物販
    $typeSel = $forms['type_sel'];

    // お支払方法
    $paymentMethodCd = $forms['payment_method_cd'];

    /**
     * フォーム
     * @var
     */
    //$eveOutForm = $forms['outForm'];
    $eventsubData =  $forms['eventsubData'];
    $collectDate = $forms['collect_date'];

    // 物販
    $buppanOutForm = $forms['outFormBuppan'];

    $buppanCollectDate = $forms['collect_date'];
    // コンビニ前払い
    $convenienceSel = "";
    if($paymentMethodCd == 1){
        $convenienceSel = $forms['convenience_sel'];
    }

    // 成功・失敗
    $merchantResult = false;

    // 入力画面
    $returnToInputPath = $forms['returnToInputPath'];

} catch(Sgmov_Component_Exception $e) {
    $merchantResult = true;
    $exInfo = $e->getInformaton();
    $eveOutForm = $exInfo['outForm'];
    $paymentMethodCd = $exInfo['payment_method_cd'];
}

$title = "卓上飛沫ブロッカーのお申込み";
if($buppanOutForm->shohin_pattern() == "2"):
    $title = "梱包資材のお申込み";
endif;

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
    <title><?php echo $title;?>の完了│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css" rel="stylesheet" type="text/css" />
    <script src="/js/ga.js" type="text/javascript"></script>
    <link href="/bpn/css/bpn.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
</head>
<body>
<style>
.contents-info {
    width: 50%;
    margin: auto !important;
    margin-top: 0px;
}
.cnv-url {
    font-size: unset;
    word-break:break-all;
}
@media screen and (max-width:905px) {
    .contents-info {
        clear:both;
        width: 100%;
        margin-top: 30px;
        margin-right: auto;
        margin-left: auto;
    }
    .cnv-url {
        font-size: 2.15vw;
    }
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
        <li class="current"><?php echo $title;?>の完了</li>
    </ul>
</div>

<div id="main">
    <div class="wrap clearfix">
        <h1 class="page_title"><?php echo $title;?>の完了</h1>
        <div class="section">
            <?php if($merchantResult):?> <!-- merchantResult if -->
                <?php if($paymentMethodCd == 1): ?>
                    <!-- コンビニ前払い -->
                    <h2 class="complete_msg">
                        コンビニでの決済が
                        <br />できませんでした。
                    </h2>
                    <br/>
                    お手数ですが、別のお支払い方法を選択して、
                    <br/>再登録ください。
                <?php elseif($paymentMethodCd == 2):　?>
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
                登録したメールアドレスに登録完了メールを送りました。<br>
            <?php endif;?> 


            <div style="clear: both;">
                <br/>
                <?php 
                if(!$merchantResult && $paymentMethodCd == 1): ?>
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
                            <br><br>
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
            <?php endif;?>

                ・ イベント当日は、受付にて、メールで表示されたQRコードを読込後、商品を渡します。<br/><br/>
             <?php if($paymentMethodCd === '3') : ?>
                ・ イベント当日、受付にて電子マネーでの決済をお願いします。
                <!--・ イベント当日、受付にて現金でお支払いお願いします。-->
            <?php endif; ?>
            <br/><br>

            <?php if($buppanOutForm->bpn_type() == "1"):?>
                <b>引き継き物販をご利用される方は<a href="/bpn/input/<?php echo @$buppanOutForm->shikibetsushi();?>/<?php echo $buppanOutForm->bpn_type(); ?>/<?php echo $buppanOutForm->shohin_pattern(); ?>" class ="clr_blue fwb tdu" target="_self" >こちら</a></b>
            <?php else:?>
                <b>引き継き物販をご利用される方は<a href="/bpn/input/<?php echo @$buppanOutForm->shikibetsushi();?>/<?php echo $buppanOutForm->bpn_type(); ?>/<?php echo $buppanOutForm->shohin_pattern(); ?>" target="_self" class ="clr_blue fwb tdu">こちら</a></b>
            <?php endif;?>

            <br><br>
            
            <script charset="UTF-8" type="text/javascript" src="/js/jquery-2.2.0.min.js"></script>
            <script charset="UTF-8" type="text/javascript" src="/js/jquery.qrcode.min.js"></script>
            <script>
                $(function(){
                    $("#qrcode-output").qrcode({text:"<?php echo $buppanOutForm->qr_code_string(); ?>"});
                });
            </script>

            <?php if(!$merchantResult): ?><!-- merchantResult else -->  
            <?php $comiketIdBuppan = $buppanOutForm->qr_code_string();?>
            <?php if($typeSel != 1 && !@empty($comiketIdBuppan)):?>
                <div  class="contents-info" >
                    <table class="default_tbl w_100per table_layout_fixed">
                       <!--  <tr>
                            <th scope="row"><h2 class="complete_msg">物販申込み情報</h2></th>
                        </tr> -->
                        <tr>
                            <td class ="pd-btm10px">
                                <!-- 物販用申込み情報 -->
                                <table class="default_tbl w_100per">
                                    <tbody>
                                        <tr>
                                            <th scope="row" class ="w_30per ws-nowrap"><b>申込み番号</b></th>
                                            <td><?php echo sprintf('%010d', $buppanOutForm->qr_code_string()); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                                <?php if($paymentMethodCd == 1): ?>
                                    <div class ="mt10px">
                                        コンビニ前払いの受付番号は以下の通りです。
                                    </div>
                                    <br>
                                    <table class="default_tbl w_100per table_layout_fixed">
                                        <tbody>
                                            <tr>
                                                <th scope="row"><b>コンビニ：受付番号</b></th>
                                            </tr>
                                            <tr>
                                                <td scope="row"><?php echo $buppanOutForm->receipt_cd(); ?></td>
                                            </tr>   
                                            <tr>
                                                <th scope="row"><b>払込票URL</b></th>
                                            </tr>
                                            <tr>
                                                <td class="cnv-url wb-break-all">
                                                    <a href="https://www.veritrans.co.jp/user_support/seven_dummy.html" target="_blank">https://www.veritrans.co.jp/user_support/seven_dummy.html</a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php endif;?>
                                <br/>
                                <div id="qrcode-output" class ="tac mt10px"></div>
                            </td>
                        </tr>
                    </table>
                </div>
            <?php endif;?>
        <?php endif;?> <!-- merchantResult endif -->     
          

                <br><br>
                <?php if($merchantResult):?>
                    <div class="mt10px mb10px fs15px">
                        <div class= "dsp-inblck">
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

    <!--[if lt IE 9]>
    <script charset="UTF-8" type="text/javascript" src="/js/jquery-1.12.0.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/lodash-compat.min.js"></script>
    <![endif]-->
    <!--[if gte IE 9]><!-->
<!--    <script charset="UTF-8" type="text/javascript" src="/js/jquery-2.2.0.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/jquery.qrcode.min.js"></script>-->
    <script charset="UTF-8" type="text/javascript" src="/js/lodash.min.js"></script>
    <!--<![endif]-->
    <script charset="UTF-8" type="text/javascript" src="/common/js/multi_send.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/common.js"></script>
</body>
</html>
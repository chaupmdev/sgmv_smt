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
Sgmov_Lib::useView('bpn/SizeChangeComplete');
Sgmov_Lib::useForms(array('Error', 'BpnSession'));
/**#@-*/

$exceptionFlg = FALSE;
// 処理を実行
$view = new Sgmov_View_Bpn_SizeChangeComplete();
$bpn001Out = array();
$eventsubData = array();

try {
    $forms = $view->execute();
    /**
     * フォーム
     * @var
     */
    $bpn001Out = $forms['outFormBuppan'];
    $eventsubData =  $forms['eventsubData'];
    $collectDate = $forms['collect_date'];
    $paymentMethodCd = $forms['payment_method_cd'];
    $isCancelOnly = @$forms['is_cancel_only'];
    $bpnType = @$forms['bpnType'];

    $screen = "";
    if($bpnType == "2"){
        $screen = "当日";
    }

} catch(Sgmov_Component_Exception $e) {
    $exceptionFlg = TRUE;
    $exInfo = $e->getInformaton();
    $bpn001Out = $exInfo['outFormBuppan'];
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
    <?php if (@empty($isCancelOnly)) : ?>
        <!-- ********************************************************************************************** -->
        <!-- 個数・サイズ変更 の場合表示 -->
        <!-- ********************************************************************************************** -->
        <?php if(empty($screen)):?>
            <title>卓上飛沫ブロッカーの数量変更お申し込みの完了│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
        <?php else:?>
            <title><?php echo $screen; ?>物販受付サービスの数量変更お申し込みの完了│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
        <?php endif;?>
    <?php else: ?>
        <!-- ********************************************************************************************** -->
        <!-- キャンセルのみの場合表示 -->
        <!-- ********************************************************************************************** -->
        <?php if(empty($screen)):?>
            <title>卓上飛沫ブロッカーのキャンセルお申し込みの完了│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
        <?php else:?>
            <title><?php echo $screen; ?>物販受付サービスのキャンセルお申し込みの完了│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
        <?php endif;?>
    <?php endif; ?>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css" rel="stylesheet" type="text/css" />
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
            <?php if (@empty($isCancelOnly)) : ?>
                <!-- ********************************************************************************************** -->
                <!-- 個数・サイズ変更 の場合表示 -->
                <!-- ********************************************************************************************** -->
                <?php if(empty($screen)):?>
                    <li class="current">卓上飛沫ブロッカーの数量変更お申し込みの完了</li>
                <?php else:?>
                    <li class="current"><?php echo $screen; ?>物販受付サービスの数量変更お申し込みの完了</li>
                <?php endif;?>
            <?php else: ?>
                <!-- ********************************************************************************************** -->
                <!-- キャンセルのみの場合表示 -->
                <!-- ********************************************************************************************** -->
                <?php if(empty($screen)):?>
                    <li class="current">卓上飛沫ブロッカーのキャンセル処理完了</li>
                <?php else:?>
                    <li class="current"><?php echo $screen; ?>物販受付サービスのキャンセル処理完了</li>
                <?php endif;?>
            <?php endif; ?>
        </ul>
    </div>

    <div id="main">
        <div class="wrap clearfix">
            <?php if (@empty($isCancelOnly)) : ?>
                <!-- ********************************************************************************************** -->
                <!-- 個数・サイズ変更 の場合表示 -->
                <!-- ********************************************************************************************** -->
                <?php if(empty($screen)):?>
                    <h1 class="page_title">卓上飛沫ブロッカーの数量変更お申し込みの完了</h1>
                <?php else:?>
                   <h1 class="page_title"><?php echo $screen; ?>物販受付サービスの数量変更お申し込みの完了</h1>
                <?php endif;?>
                
            <?php else: ?>
                <!-- ********************************************************************************************** -->
                <!-- キャンセルのみの場合表示 -->
                <!-- ********************************************************************************************** -->
                <?php if(empty($screen)):?>
                    <h1 class="page_title">卓上飛沫ブロッカーのキャンセル処理完了</h1>
                <?php else:?>
                    <h1 class="page_title"><?php echo $screen; ?>物販受付サービスのキャンセル処理完了</h1>
                <?php endif;?>
            <?php endif; ?>
                
            <div class="section" style="<?php if (@empty($isCancelOnly)) : ?>display:none;<?php endif; ?>">
            <!-- ********************************************************************************************** -->
            <!-- キャンセルのみの場合表示 -->
            <!-- ********************************************************************************************** -->
                <h2 class="complete_msg">お申込みのキャンセル処理が完了しました。</h2><br/><br/>
                登録しているメールアドレスにキャンセル処理完了メールを送りました。<br/><br/>
                
                <table class="default_tbl">
                    <tr>
                        <th scope="row">申込み番号</th>
                        <td><?php echo sprintf('%010d', $bpn001Out->qr_code_string()); ?></td>
                    </tr>
                </table>

                <div class="btn_area">
                    <input id="submit_button" type="button" name="button" value="トップページに戻る" onclick="location.href='/';" />
                </div>
            </div>
            
            <div class="section" style="<?php if (@$isCancelOnly == true) : ?>display:none;<?php endif; ?>">
            <!-- ********************************************************************************************** -->
            <!-- 個数・サイズ変更 の場合表示 -->
            <!-- ********************************************************************************************** -->
<?php
    if($exceptionFlg) {
        if ($paymentMethodCd === '4') {
?>
                <h2 class="complete_msg">
                    コンビニ後払いの処理が
                    <br />できませんでした。
                    <br/>
                    <br/>
                    お手数ですが、別のお支払い方法を選択して、
                    <br/>再登録ください。
                </h2>
<?php
        }
    } else {

        if ($bpn001Out->merchant_result() === '0'
                && ($paymentMethodCd === '1' || $paymentMethodCd === '2')) {
            if ($paymentMethodCd=== '2') {
?>
                <h2 class="complete_msg">
                    クレジットカードでの決済が
                    <br />できませんでした。
                </h2>
<?php
            } else {
?>
                <h2 class="complete_msg">
                    コンビニでの決済が
                    <br />できませんでした。
                </h2>
<?php
            }
        } else {
?>
            <h2 class="complete_msg">ご登録ありがとうございました。</h2><br/><br/>
            登録したメールアドレスに登録完了メールを送りました。<br/>



          <!--   <table class="default_tbl">
                <tr>
                    <th scope="row">申込み番号</th>
                    <td><?php// echo sprintf('%010d', $bpn001Out->qr_code_string()); ?></td>
                </tr>
            </table> -->

<?php
        }
    }
    //if ($bpn001Out->merchant_result() === '1' && $paymentMethodCd === '1') {
?>
                <!-- コンビニ前払いの受付番号は以下の通りです。 -->
                
              <!--   <table class="default_tbl">
                    <tr>
                        <th scope="row">受付番号</th>
                        <td><?php// echo $bpn001Out->receipt_cd(); ?></td>
                    </tr> -->

<?php
      //  if ($bpn001Out->payment_url() !== '') {
?>
                    <?php //if($eventsubData['paste_display'] == '1') : ?>
                 <!--    <tr>
                        <th scope="row">払込票URL</th>
                        <td>
                            <a href="<?php// echo $bpn001Out->payment_url(); ?>" target="_blank"><?php //echo $bpn001Out->payment_url(); ?></a>
                        </td>
                    </tr> -->
                    <?php //endif; ?>
<?php
      //  }
    ?>       <div style="clear: both;">
                    <br/>
                    <?php 
                    if($paymentMethodCd == 1): ?>
                    <div>
                        <?php   switch ($bpn001Out->convenience_store_cd_sel()) {
                            case '1':?>
                                <br>※コンビニ：受付番号をメモまたは払込票を印刷し、
                                <br><br>セブンイレブンのレジカウンターにてお支払い手続きをお願いいたいます。
                                <br><br>
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
                                <br><br>
                        <?php break;
                            case '3': ?>
                            <ul class="cnv_list">
                                <li>・デイリーヤマザキ</li>
                            </ul>
                            <br>※受付番号をメモまたは払込票を印刷し、
                            <br><br>デイリーヤマザキ店頭レジにてお支払い手続きをお願いいたいます。
                            <br><br>
                        <?php break;
                            default:
                            break;
                        }?>
                    </div>
                <?php endif;?>

                ・ イベント当日は、受付にて、メールで表示されたQRコードを読込後、商品を渡します。<br/>

                <?php if($paymentMethodCd === '3') : ?>
                    ・ イベント当日、受付にて電子マネーでの決済をお願いします。
                <?php endif; ?>
                <br/>
                <br/>

                <style type="text/css">
                    .contents-info {
                        width: 50%;
                        margin: auto !important;
                        margin-top: 0px;
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
                </style>

                <script charset="UTF-8" type="text/javascript" src="/js/jquery-2.2.0.min.js"></script>
                <script charset="UTF-8" type="text/javascript" src="/js/jquery.qrcode.min.js"></script>
                <script>
                    $(function(){
                        $("#qrcode-output").qrcode({text:"<?php echo $bpn001Out->qr_code_string(); ?>"});
                    });
                </script>

                <!-- </table> -->
                <div  class="contents-info" >
                    <table class="default_tbl" style="width:100%;table-layout: fixed;">
                       <!--  <tr>
                            <th scope="row"><h2 class="complete_msg">物販申込み情報</h2></th>
                        </tr> -->
                        <tr>
                            <td style="padding-bottom: 10px;">
                                <!-- 物販用申込み情報 -->
                                <table class="default_tbl" style="width:100%;">
                                    <tbody>
                                        <tr>
                                            <th scope="row" style="width: 30%;white-space: nowrap;"><b>申込み番号</b></th>
                                            <td><?php echo sprintf('%010d', $bpn001Out->qr_code_string()); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                                <?php if($paymentMethodCd == 1): ?>
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
                                                <td scope="row"><?php echo $bpn001Out->receipt_cd(); ?></td>
                                            </tr>   
                                            <tr>
                                                <th scope="row"><b>払込票URL</b></th>
                                            </tr>
                                            <tr>
                                                <td class="cnv-url" style="word-break: break-all;">
                                                   <a href="<?php echo $bpn001Out->payment_url(); ?>" target="_blank"><?php echo $bpn001Out->payment_url(); ?></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php endif;?>
                                <br/>
                                <div id="qrcode-output" style="text-align: center;margin-top: 10px;"></div>
                            </td>
                        </tr>
                    </table>
                </div>


        <div class="btn_area">
            <a class="next" href="/">ホームへ戻る</a>
        </div>


            </div>
        </div>
    </div><!--main-->
</div>
    <?php
        $footerSettings = 'under';
        include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/footer.php';
    ?>
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
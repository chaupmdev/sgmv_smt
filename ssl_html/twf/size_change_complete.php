<?php
/**
 * 手荷物受付サービスのお申し込み完了画面を表示します。
 * @package    ssl_html
 * @subpackage TWF
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
// イベント識別子(URLのpath)はマジックナンバーで記述せずこの変数で記述してください
$dirDiv=Sgmov_Lib::setDirDiv(dirname(__FILE__));
Sgmov_Lib::useView($dirDiv.'/SizeChangeComplete');
Sgmov_Lib::useForms(array('Error', 'EveSession'));
/**#@-*/

$exceptionFlg = FALSE;
// 処理を実行
$view = new Sgmov_View_Twf_SizeChangeComplete();
$eveOutForm = array();
$eventData = array();
$eventsubData = array();

try {
    $forms = $view->execute();

    /** フォーム　**/
    $eveOutForm = $forms['outForm'];
    /** イベント情報　**/
    $eventData =  $forms['eventData'];
    /** イベントサブ情報　**/
    $eventsubData =  $forms['eventsubData'];
    /** 預り日　**/
    $collectDate = $forms['collect_date'];
    /** 往路　**/
    $entryType = $forms['type_sel'];
    /** お支払方法　**/
    $paymentMethodCd = $forms['payment_method_cd'];
    /** キャンセルフラグ　**/
    $isCancelOnly = @$forms['is_cancel_only'];

} catch(Sgmov_Component_Exception $e) {
    $exceptionFlg = TRUE;
    $exInfo = $e->getInformaton();
    $eveOutForm = $exInfo['outForm'];
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
        <title>催事・イベント配送受付サービスの個数・サイズ変更お申し込みの完了│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
    <?php else: ?>
        <!-- ********************************************************************************************** -->
        <!-- キャンセルのみの場合表示 -->
        <!-- ********************************************************************************************** -->
        <title>催事・イベント配送受付サービスのキャンセルお申し込みの完了│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
    <?php endif; ?>
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
        margin-right: auto;
        margin-left: auto;
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
</style>
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
                <li class="current">催事・イベント配送受付サービスの個数・サイズ変更お申し込みの完了</li>
            <?php else: ?>
                <!-- ********************************************************************************************** -->
                <!-- キャンセルのみの場合表示 -->
                <!-- ********************************************************************************************** -->
                <li class="current">催事・イベント配送受付サービスのキャンセル処理完了</li>
            <?php endif; ?>
        </ul>
    </div>

    <div id="main">
        <div class="wrap clearfix">
            <?php if (@empty($isCancelOnly)) : ?>
                <!-- ********************************************************************************************** -->
                <!-- 個数・サイズ変更 の場合表示 -->
                <!-- ********************************************************************************************** -->
                <h1 class="page_title">催事・イベント配送受付サービスの個数・サイズ変更お申し込みの完了</h1>
            <?php else: ?>
                <!-- ********************************************************************************************** -->
                <!-- キャンセルのみの場合表示 -->
                <!-- ********************************************************************************************** -->
                <h1 class="page_title">催事・イベント配送受付サービスのキャンセル処理完了</h1>
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
                        <td><?php echo sprintf('%010d', $eveOutForm->qr_code_string()); ?></td>
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

        if ($eveOutForm->merchant_result() === '0'
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
            登録したメールアドレスに登録完了メールを送りました。<br/><br/>
            <?php if($entryType == "1"): ?>
                <table class="default_tbl">
                    <tr>
                        <th scope="row">申込み番号</th>
                        <td><?php echo sprintf('%010d', $eveOutForm->qr_code_string()); ?></td>
                    </tr>
                </table>
                <br/><br/>
            <?php endif; ?>
<?php
        }
    }
    if ($eveOutForm->merchant_result() === '1' && $paymentMethodCd === '1') {
?>

                <p class="sentence btm30">
                    コンビニ前払いの受付番号は以下の通りです。

<?php
        if ($eveOutForm->payment_url() !== '') {
?>
                    <br />コンビニのレジカウンターにて決済手続きをお願いいたします。
<?php
    if ($entryType === '1') {
?>
                 <!--    <br /><span style="color: red;">※お支払いはお預かり日時の<?php //echo $collectDate;?>の前日までに入金していただきますようお願いいたします。</span> -->
<?php
    }
?>
<?php
            switch ($eveOutForm->convenience_store_cd_sel()) {
                case '1':
?>
                    <br />
                    <a href="/cvs/pc/711.html" target="_blank">手続き方法はこちらをご参照ください。</a>
<?php
                    break;
                case '2':
                    break;
                case '3':
?>
                    <br />
                    <a href="/cvs/pc/dailyamazaki.html" target="_blank">手続き方法はこちらをご参照ください。</a>
<?php
                    break;
                default:
                    break;
            }
?>

                </p>

<?php
        } else {
?>
                    <br />コンビニ備え付けの端末にて支払い手続きをお願いいたします。
                    <br />端末の操作方法は下記をご参照ください。
                </p>

<?php
            switch ($eveOutForm->convenience_store_cd_sel()) {
                case '1':
                    break;

                case '2':
?>
                <ul class="cnv_list">
                    <li>・ローソン</li>
                    <li>・セイコーマート</li>
                    <li>・ファミリーマート</li>
                    <li>・ミニストップ</li>
                </ul>
<?php
                    break;

                case '3':
?>
                <ul class="cnv_list">
                    <li>・デイリーヤマザキ</li>
                </ul>
<?php
                    break;

                default:
                    break;
            }
        }
?>
<?php
    }
?>



<?php
if ($eveOutForm->merchant_result() === '1'
        || $paymentMethodCd === '3'  // 電子マネー
        || $paymentMethodCd === '5'  // 法人売掛
        ) {
?>

        <!--貼付標 -->
        <?php if(($eveOutForm->comiket_detail_type_sel() == "1" || $eveOutForm->comiket_detail_type_sel() == "3") // 搬入、もしくは往復
                        && (
                                ($eveOutForm->comiket_detail_outbound_service_sel() == "1" || $eveOutForm->comiket_detail_outbound_service_sel() == "2") // 宅配、またはカーゴ
                           )
                        && $eventsubData['paste_display'] == '1'
                        ) : ?>
                    以下の方法で送り状を用意できます。<br/>
                    荷物を送る際に必要になるので用意してください<br/>
                    ・ 画面下の「配達カードのDL」からPDFをダウンロードし、配達カードを印刷後、荷物に貼り付けて、発送ください。<br/><br/>
                    <div style="font-size:20px;">
                        <a href="/<?=$dirDiv?>/paste_tag/<?php echo sprintf('%010d', $eveOutForm->qr_code_string()) . Sgmov_View_Twf_Common::getChkD2($eveOutForm->qr_code_string()); ?>/" target="_blank" style="color:blue;" download>
                            <img src="/images/common/img_icon_pdf.gif" width="18" height="21" alt="">&nbsp;配達カードのDL
                        </a>
                    </div><br/><br/>
        <?php endif; ?>

        <!--説明書 -->
        //<?php if ($eventsubData['manual_display'] == '1') { ?>
<!--            ご不明な点はメールに添付されている出展ガイド、もしくは下記のボタンから出展ガイドをダウンロードして、ご確認ください。<br/><br/>
            <div style="font-size:20px;">
                <a class="button_link2" href="<?=$forms['dispItemInfo']['dispEvent']['manual2']?>" target="_blank" style="color:blue;">
                    <img src="/images/common/img_icon_pdf.gif" width="18" height="21" alt="">&nbsp;出展ガイドのDL
                </a>
            </div>-->
        //<?php } ?>
        
        <!--決済 -->
        <?php if(($eveOutForm->comiket_detail_type_sel() == "2" || $eveOutForm->comiket_detail_type_sel() == "3") // 搬出、もしくは往復
                    && (
                            ($eveOutForm->comiket_detail_inbound_service_sel() == "1" || $eveOutForm->comiket_detail_inbound_service_sel() == "2") // 宅配、またはカーゴ
                       )
                    ) : ?>
            ・ イベント当日は、受付にて、メールで表示されたQRコードを読込後、送り状を渡します。<br/><br/>
            <?php if($paymentMethodCd === '3') : ?>
                ・ イベント当日、受付にて電子マネーでの決済をお願いします。
            <?php endif; ?>
            <br/>
        <?php endif; ?>

               
<?php
    } else {
?>
                <p class="sentence btm30">
<!--                    インターネットでお申し込みが出来なかった場合は、
                    <br />4月6日より下記SGムービングクルーズ専用ダイヤルにて、お申し込みを受付致します。
                    <br />インターネットお申し込みについてのご質問については、※<a data-inquiry-type="10" href="/pin/">こちら</a>からのみなります。
                    <br />TEL：<span class="b">0120-35-4192</span>(固定電話から)
                    <br />　　　<span class="b">03-5763-9188</span>(携帯電話から)
                    <br />　　(受付時間:平日10時～17時)-->
                </p>
<?php
    }
?>

                 <br/>

            <?php if ($entryType != '1') :?>
                <script charset="UTF-8" type="text/javascript" src="/js/jquery-2.2.0.min.js"></script>
                <script charset="UTF-8" type="text/javascript" src="/js/jquery.qrcode.min.js"></script>
                <script>
                    $(function(){
                        $("#qrcode-output").qrcode({text:"<?php echo $eveOutForm->qr_code_string(); ?>"});
                    });
                </script>

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
                                <?php if($paymentMethodCd == 1) : // コンビニ前払い?>
                                    <div style="margin-top: 10px;">
                                        コンビニ前払いの受付番号は以下の通りです。
                                    </div><br>
                                    <table class="default_tbl" style="width:100%;table-layout: fixed;">
                                        <tbody>
                                            <tr>
                                                <th scope="row"><b>コンビニ：受付番号</b></th>
                                            </tr>
                                            <tr>
                                                <td scope="row"><?php echo $eveOutForm->receipt_cd(); ?></td>
                                            </tr>

                                            <?php if ($eveOutForm->payment_url() !== '') { ?>
                                                <tr>
                                                    <th scope="row"><b>払込票URL</b></th>
                                                </tr>
                                                <tr>
                                                    <td class="cnv-url">
                                                        <a href="<?php echo $eveOutForm->payment_url(); ?>" target="_blank"><?php echo $eveOutForm->payment_url(); ?></a>
                                                    </td>
                                                </tr>
                                            <?php } ?>    
                                        </tbody>
                                    </table>
                                <?php endif;?>

                            <?php if ($eveOutForm->merchant_result() === '1'
                                    || $paymentMethodCd === '3'  // 電子マネー
                                    || $paymentMethodCd === '5'  // 法人売掛
                                ) { ?>

                                    <?php if ($paymentMethodCd === '4') : ?>
                                        <div style="margin-top: 10px;">
                                            コンビニ後払い情報
                                         </div><br>
                                        <table class="default_tbl">
                                            <tr>
                                                <th scope="row">ご購入店受注番号</th>
                                                <td><?php echo $eveOutForm->sgf_shop_order_id(); ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">お問合せ番号</th>
                                                <td><?php echo $eveOutForm->sgf_transaction_id(); ?></td>
                                            </tr>
                                        </table>
                                        <br/>
                                        <div>
                                            <strong class="red">※ コンビニ後払いについて：決済時に、お申し込みに時間がかかる場合又は、お申し込みができない場合がございますのでご了承ください。</strong>
                                        </div>
                                        <br/>
                                    <?php endif; ?>
                            <?php }?>

                                <br/>
                                  <div id="qrcode-output" style="text-align: center;"></div>
                            </td>
                        </tr>
                    </table>
                </div>
            <?php endif; ?>

                <div class="btn_area">
                    <a class="next" href="/">ホームへ戻る</a>
                </div>

            </div>
        </div>
    </div><!--main-->
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
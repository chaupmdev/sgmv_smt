<?php

/**
 * コストコ配送サービスのお申し込み完了画面を表示します。
 * @package    ssl_html
 * @subpackage CSC
 * @author     K.Sawada
 * @copyright  2021-2021 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('csc/Complete2');
Sgmov_Lib::useForms(array('Error', 'EveSession'));

// // 処理を実行
$view = new Sgmov_View_Csc_Complete2();

$result = array();
try {
    $result = $view->execute();
} catch(Exception $e) {
    $exInfo = $e->getMessage();
    $result = array(
        'status' => 'error',
        'message' => 'エラーが発生しました。',
        'res_data' => array(
            'error_info' => $exInfo,
        ),
    );
}

// $eventInfo = @$result['res_data']['event'];
// $eventsubInfo = @$result['res_data']['eventsub'];
// $inputInfo = @$result['res_data']['input_info'];
// $prefInfoList = @$result['res_data']['pref_info'];
if ($result['status'] == 'success') {
    $comiketId = @$result['res_data']['comiket_id'];
    $eventsubId = @$result['res_data']['eventsub_id'];
    if (@empty($comiketId) || @empty($eventsubId)) {
        Sgmov_Component_Redirect::redirectPublicSsl("/404.html");
        exit;
    }

}


$week = array('日', '月', '火', '水', '木', '金', '土');

function _h($val) {
    return @htmlspecialchars($val);
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
    <meta name="Description" content="配送受付サービスのお申し込みのご案内です。" />
    <meta name="author" content="SG MOVING Co.,Ltd" />
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved." />
    <title>配送受付サービスのお申し込み｜ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css?202110301711" rel="stylesheet" type="text/css" />
    <link href="/csc/css/eve.css?202110301711" rel="stylesheet" type="text/css" />
    <script src="/js/ga.js" type="text/javascript"></script>
</head>

<body>

    <!-- ヘッダStart ************************************************ -->
    <header id="header" class="Header--simple">
        <div class="Header__inner">
            <div class="Header__head">
                <h1 class="header-logo">
                    <a href="http://www.sg-hldgs.co.jp/" target="_blank" rel="noopener"><span class="header-logo__image"><img src="/images/common/img_header_01.png" alt="SGH"></span></a>
                    <a href="/"><span class="header-logo__image"><img src="/images/common/img_header_02.png" alt="SGmoving"></span></a>
                </h1>
                <!--/Header__head-->
            </div>
            <!--/Header__inner-->
        </div>
        <!--/Header-->
    </header>
    <!-- ヘッダEnd ************************************************ -->
    <div id="breadcrumb">
        <ul class="wrap">

        </ul>
    </div>
    <div id="main">
        <div class="wrap clearfix">
            <h1 class="page_title" style="margin-bottom:15px !important;">配送受付入力完了</h1>

            <div class="section other">
                <?php 
                if ($result['status'] == 'success') {
                ?>
                <h2 class="complete_msg">ご登録ありがとうございました。</h2><br><br> 
                ご指定のメールアドレスへQRコードを送信しました。<br><br> 
                デリバリーサービスカウンタースタッフにこちらのQRコードをご提示ください。 <br><br><br>
                <div class="contents-info">
                    <table class="default_tbl" style="width: 100%; max-width: 400px; display: table; table-layout: fixed; margin: 10px auto;">
                        <tbody>
                            <tr>
                                <td style="padding-bottom: 10px;">
                                    <table class="default_tbl" style="width: 100%;">
                                        <tbody>
                                            <tr>
                                                <th scope="row" style="width: 30%; white-space: nowrap;"><b>申込み番号</b></th>
                                                <td><?= @sprintf('%010d', $comiketId) ?></td>
                                            </tr>
                                        </tbody>
                                    </table><br>
                                    <div id="qrcode-output" style="text-align: center; margin-top: 10px;"></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php 
                } else {
                    ?>
                    <span style="white-space: pre-wrap;"><?= $result['message']; ?></span><br><br> 
                    <?php 
                }
                ?>
            </div>
            <?php 
            if ($result['status'] == 'success') {
            ?>
                <div class="btn_area"><input id="submit_button" type="button" name="submit" onclick='location.href="/csc/input/<?= $eventsubId ?>";' value="追加申込"></div>
            <?php 
            } else {
                ?>
                    <div class="btn_area"><input id="submit_button" type="button" name="submit" onclick='location.href="/"' value="ホームへ戻る"></div>
                <?php 
            }
            ?>
        </div>
    </div>
    <!--main-->

    <!-- フッターStart ************************************************ -->
    <footer id="footer" class="Footer--simple">
        <div class="Footer__inner">
            <div class="Footer__foot">
                <div class="Footer__foot__inner">
                    <div class="footer-copyright">
                        <small class="footer-copyright__label">&copy; SG Moving Co.,Ltd. All Rights Reserved.</small>
                    </div>
                </div>
                <!-- /Footer__foot -->
            </div>
            <!-- /Footer__inner -->
        </div>
        <!-- /Footer -->
    </footer>
    <!-- フッターEnd ************************************************ -->
    <script charset="UTF-8" type="text/javascript" src="/js/jquery-2.2.0.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/lodash.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/multi_send.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/common.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/smooth_scroll.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/ajax_searchaddress.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/jquery.ah-placeholder.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/jquery.autoKana.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/from_to_pulldate.js?202110301711"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/jquery.qrcode.min.js"></script>
    <script>
        $(function(){
            $("#qrcode-output").qrcode({text:"<?php echo isset($comiketId) ? $comiketId : ''; ?>"});
        });
    </script>
</body>

</html>
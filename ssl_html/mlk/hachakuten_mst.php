<?php

/**
 * コストコ配送サービスのお申し込み確認画面を表示します。
 * @package    ssl_html
 * @subpackage CSC
 * @author     K.Sawada
 * @copyright  2021-2021 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('mlk/HachakutenMst');
Sgmov_Lib::useForms(array('Error', 'EveSession'));

// // 処理を実行
$view = new Sgmov_View_HachakutenMst();
$baseUrl = Sgmov_Component_Config::getUrlPublicSsl();

$result = array();
try {
    $result = $view->execute();
} catch (Exception $e) {
    $exInfo = $e->getMessage();
    $result = array(
        'status' => 'error',
        'message' => 'エラーが発生しました。',
        'res_data' => array(
            'error_info' => $exInfo,
        ),
    );
}

$success = @$_SESSION["MLK"]["SUCCESS"];
$countBeforeImpMsg = @$_SESSION["MLK"]["COUNT_BEFORE_IMPORT_MSG"];
$countAfterImpMsg = @$_SESSION["MLK"]["COUNT_AFTER_IMPORT_MSG"];
$inputInfo = @$result['res_data']['input_info'];
if (!empty($inputInfo)) {
    $selectedDataType = $inputInfo['data_type'];
}
$errorInfoList = @$result['res_data']['error_info'];

function _h($val)
{
    return @htmlspecialchars($val);
}


$request = @$result['res_data']['request'];
$queryString = '';
$isErrorSearch = @$result['res_data']['isErrorSearch'];
if (!empty($_SERVER['QUERY_STRING'])){
    //"&"で分割
    $params = explode("&", $_SERVER['QUERY_STRING']);
    $newParams = array();
    if (count($newParams) != 0) { $queryString = "&" . implode("&", $newParams); }
}

$dateNow = date('Y-m-d');


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
    <meta name="Description" content="配送受付システムのお申し込みのご案内です。" />
    <meta name="author" content="SG MOVING Co.,Ltd" />
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved." />
    <title>配送受付システムのお申し込み｜ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/form.css?202110301711" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css" rel="stylesheet" type="text/css" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/csc/css/eve.css?202110301711" rel="stylesheet" type="text/css" />
    
    <link href="/css/sitemap.css" rel="stylesheet" type="text/css" />
    <link href="/csc/css/dashboard.css" rel="stylesheet" type="text/css" />
    <link href="/css/eve.css" rel="stylesheet" type="text/css">
    <link href="/css/form.css" rel="stylesheet" type="text/css">

    <script src="/js/ga.js" type="text/javascript"></script>

    <style>
        .block_title {
            background-color: #2296F3;
            padding: 10px;
            border-radius: 5px;
            font-size: 90%;
            color: #fff;
            margin-bottom: 27px;
            font-weight: bold;
        }
    </style>
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
            <h1 class="page_title" style="margin-bottom:15px !important;">発着地マスタメンテ</h1>
            <!-- <form id="form_export" method="post" action="/mlk/hachakuten_mst" enctype="multipart/form-data" style="text-align: center;">
                <input id="submit_button" class="btn btn--blue btn-search" type="submit" name="btnExport" style="background-color:#1774bc;height: 56px;" value="CSV出力"></input>
            </form>
            <br>
            <hr class="wrap clearfix"></hr> -->
            <?php if (@!empty($errorInfoList)) : ?>
                <div class="err_msg">
                    <p class="sentence br attention"> [!ご確認ください]下記の項目が正しく入力・選択されていません。 </p>
                    <ul>
                        <?php foreach ($errorInfoList as $key => $val) : ?>
                            <li><a href="#<?= $val['key'] ?>_err_apply"><?= $val['itemName'] . $val['errMsg'] ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="section other">
                <form id="form_create" action="/mlk/hachakuten_mst" method="post" enctype="multipart/form-data">
                    <div class="dl_block comiket_block" style="margin-bottom: 0;">

                        <dl>
                            <dt id="shohin_cd_err_apply" style=" border-top: solid 1px #ccc !important;width: 30%;">
                                取り込みファイル<span>必須</span>
                            </dt>
                            <dd class="shohin_cd_err_apply">
                                <input type="file" name="up_file" id="up_file" class="up_file" style="width: 40%;">
                            </dd>
                        </dl>
                    </div>
                    <div class="btn_area">
                        <input id="submit_button" class="btn btn--blue btn-search" type="submit" style="background-color:#1774bc;height: 56px;" name="import" value="CSV取込"></input>
                    </div>
                </form>
            </div>
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
    <script charset="UTF-8" type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js"></script>

    <script>
        $(document).ready(function(){
            var baseUrl = '<?= $baseUrl ?>';
            var success = '<?= $success ?>';
            var countBeforeImpMsg = "<?= $countBeforeImpMsg ?>";
            var countAfterImpMsg = "<?= $countAfterImpMsg ?>";

            if (success == 1) {
                console.log(baseUrl);
                document.getElementsByTagName("html")[0].style.visibility = "hidden";
                alert(countBeforeImpMsg + "\n" + countAfterImpMsg);
                window.location.href = baseUrl + '/mlk/hachakuten_mst';
            }
        });
        $(function() {
            var baseUrl = '<?= $baseUrl ?>';

            <?php if (@!empty($errorInfoList)) : ?>
                <?php foreach ($errorInfoList as $key => $val) : ?>
                    $('.<?= $val['key'] ?>_err_apply').addClass('err_input');
                <?php endforeach; ?>
            <?php endif; ?>

            $('#submit_button').on('click', function() {
                // $(this).css('pointer-events','none');
                $('#form_create').submit();
                return true;
            });
        });

        function clearRequestSearch() {
            let date = new Date();
            let dayx = date.getDate();
            if (dayx < 10) {
                dayx = '0' + dayx;
            }
            let monthx = Number(date.getMonth()+1);
            if (monthx < 10) {
                monthx = '0' + monthx;
            }
            let yearx = date.getFullYear();
            let strDate = yearx + '-' + monthx + '-' + dayx;
            $('input[name="start_date"]').val(strDate);
            $('input[name="end_date"]').val(strDate);
        }
    </script>
</body>

</html>
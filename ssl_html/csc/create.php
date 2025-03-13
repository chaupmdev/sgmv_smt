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
Sgmov_Lib::useView('csc/Create');
Sgmov_Lib::useForms(array('Error', 'EveSession'));

// // 処理を実行
$view = new Sgmov_View_Csc_Create();
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

$success = @$_SESSION["CSC"]["SUCCESS"];
$costcoOptions = @$result['res_data']['costco_options'];
$inputInfo = @$result['res_data']['input_info'];
$selectedDataType = '6';
if (!empty($inputInfo)) {
    $selectedDataType = $inputInfo['data_type'];
}
$errorInfoList = @$result['res_data']['error_info'];
$dataTypeOptions = array(
    '' => '',
    '6' => '6：D24でない',
    '7' => '7：D24'
);
$week = array('日', '月', '火', '水', '木', '金', '土');

function _h($val)
{
    return @htmlspecialchars($val);
}

$defaultCreateDt = date('Y-m-d');

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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css" rel="stylesheet" type="text/css" />
    <link href="/csc/css/form.css?202110301711" rel="stylesheet" type="text/css" />
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
            <h1 class="page_title" style="margin-bottom:15px !important;">商品マスタ登録画面</h1>

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
                <form id="form_create" action="/csc/create" data-feature-id="CSC" data-id="CSC001" method="post">
                    <div class="dl_block comiket_block">

                        <!-- 商品コード -->
                        <dl>
                            <dt id="shohin_cd_err_apply" style=" border-top: solid 1px #ccc !important;">
                                商品コード<span>必須</span>
                            </dt>
                            <dd class="shohin_cd_err_apply">
                                <input id="shohin_cd" class="shohin_cd" maxlength='30' inputmode="" name="shohin_cd" data-pattern="" type="text" style="width: 40%;" value="<?= @_h($inputInfo['shohin_cd']) ?>">
                            </dd>
                        </dl>
                        <!-- END 商品コード -->

                        <!-- 商品名 -->
                        <dl>
                            <dt id="shohin_name_err_apply" style=" border-top: solid 1px #ccc !important;">
                                商品名<span>必須</span>
                            </dt>
                            <dd class="shohin_name_err_apply">
                                <input id="shohin_name" class="shohin_name" maxlength='100' inputmode="" name="shohin_name" data-pattern="" type="text" style="width: 80%;" value="<?= @_h($inputInfo['shohin_name']) ?>">
                            </dd>
                        </dl>
                        <!-- END 商品名 -->

                        <!-- サイズ -->
                        <dl>
                            <dt id="size_err_apply" style=" border-top: solid 1px #ccc !important;">
                                サイズ<span>必須</span>
                            </dt>
                            <dd class="size_err_apply">
                                <input id="size" class="size" inputmode="" name="size" data-pattern="" type="text" style="width: 15%;" value="<?= @_h($inputInfo['size']) ?>">
                            </dd>
                        </dl>
                        <!-- END サイズ -->

                        <!-- オプションid -->
                        <dl>
                            <dt id="option_id_err_apply" style=" border-top: solid 1px #ccc !important;">
                                オプションid<span>必須</span>
                            </dt>
                            <dd class="option_id_err_apply">
                                <select class='option_id' name="option_id">
                                    <?php foreach ($costcoOptions as $key => $val) : ?>
                                        <option value="<?= @$key ?>" <?= @$key == @$inputInfo['option_id'] ? ' selected' : ''; ?>>
                                            <?= @$val ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </dd>
                        </dl>
                        <!-- END オプションid -->

                        <!-- データ種別 -->
                        <dl>
                            <dt id="data_type_err_apply" style=" border-top: solid 1px #ccc !important;">
                                データ種別<span>必須</span>
                            </dt>
                            <dd class="data_type_err_apply">
                                <select class='data_type' name="data_type">
                                    <?php foreach ($dataTypeOptions as $key => $val) : ?>
                                        <option value="<?= @$key ?>" <?= @$key == @$selectedDataType ? ' selected' : ''; ?>>
                                            <?= @$val ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </dd>
                        </dl>
                        <!-- END データ種別 -->

                        <!-- 重量 -->
                        <dl>
                            <dt id="juryo_err_apply" style=" border-top: solid 1px #ccc !important;">
                                重量<span>必須</span>
                            </dt>
                            <dd class="juryo_err_apply">
                                <input id="juryo" class="juryo" inputmode="" name="juryo" data-pattern="" type="number" style="width: 15%;" value="<?= @_h($inputInfo['juryo']) ?>">
                            </dd>
                        </dl>
                        <!-- END 重量 -->

                        <!-- 適用開始日 -->
                        <dl>
                            <dt id="start_date_err_apply" style="width: 25%;">適用開始日<span>必須</span></dt>
                            <dd class="start_date_err_apply">
                                <div class="comiket_detail_inbound_delivery_date_parts" style="white-space: nowrap;">
                                    <input type="date" id="start_date" max="9999-12-31" class="td-date" style="width: 20%;" name="start_date" placeholder="" tabindex="0" value="<?= (!empty($inputInfo)) ? @_h($inputInfo['start_date']) : $defaultCreateDt ?>">
                                </div>
                            </dd>
                        </dl>
                        <!-- END 適用開始日 -->

                        <!-- 梱包数 -->
                        <dl>
                            <dt id="konposu_err_apply" style=" border-top: solid 1px #ccc !important;">
                                梱包数<span>必須</span>
                            </dt>
                            <dd class="konposu_err_apply">
                                <input id="konposu" class="konposu" inputmode="" name="konposu" data-pattern="" type="number" style="width: 15%;" value="<?= @_h($inputInfo['konposu']) ?>">
                            </dd>
                        </dl>
                        <!-- END 梱包数 -->
                    </div>
                    <!-- ************************************************************************************************************* -->
                    <div class="btn_area">
                        <input id="back_button" class="back_button" type="button" name="back_button" value="戻る"></input>
                        <input id="submit_button" class="submit_button" type="submit" name="submit" style="background-color:#1774bc;" value="登録"></input>
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

            if (success == 1) {
                console.log(baseUrl);
                document.getElementsByTagName("html")[0].style.visibility = "hidden";
                alert('データを登録しました。');
                window.location.href = baseUrl + '/csc/mst_shohin_list?flg_save=1';
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
            $('#back_button').on('click', function() {
                window.location.href = baseUrl + '/csc/mst_shohin_list?flg_back=1';
            });
        });
    </script>
</body>

</html>
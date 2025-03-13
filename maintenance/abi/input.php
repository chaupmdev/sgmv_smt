<?php
 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('abi/Input');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Abi_Input();
$forms = $view->execute();
/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

$outForm = $forms['outForm'];

$errorForm = $forms['errorForm'];
//var_dump($errorForm);
?><!DOCTYPE html>
<html dir="ltr" lang="ja-jp" xml:lang="ja-jp">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <title>ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
    <meta name="author" content="SG MOVING Co.,Ltd" />
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved." />
    <link href="/common/css/top_top.css" rel="stylesheet" type="text/css" media="screen,tv,projection,print" />
    <link href="/common/css/top_print.css" rel="stylesheet" type="text/css" media="print" />
    <link href="/common/css/top_main_new.css" rel="stylesheet" type="text/css" media="screen,tv,projection,print" />
    <link href="/common/css/cruise.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <div class="helpNav">
        <p>
            <a id="pageTop" name="pageTop"></a>このページの先頭です
        </p>
        <p>
            <a href="#contentTop">メニューを飛ばして本文を読む</a>
        </p>
    </div>
    <div id="wrapper">
        <div id="header">
            <!-- ▼SGH共通ヘッダー start -->
            <div id="sghHeader">
                <h1><a href="<?php // echo Sgmov_Component_Config::getUrlMaintenance(); ?>"><img src="/common/img/ttl_sgmoving-logo.png" alt="ＳＧムービング" width="118" height="40" /></a></h1>
                <p class="sgh">
                    <a href="http://www.sg-hldgs.co.jp/" target="_blank">
                        <img src="/common/img/pct_sgh-logo.png" alt="ＳＧホールディングス" width="41" height="29" />
                    </a>
                </p>
            </div>
            <!-- /#sghHeader -->
            <!-- ▲／SGH共通ヘッダー end -->

            <!-- ▼グローバルナビ start -->
            <dl id="globalNav">
                <dt>サイト内総合メニュー</dt>
                <dd>
                    <ul>
                        <li class="nav01">
                            <a href="/acm/menu"><img src="/common/img/nav_global01.gif" alt="メニュー" width="91" height="41" /></a>
                        </li>
                        <li class="nav02">
<?php if($outForm->honsha_user_flag() === '1'){ ?>
                            <a href="/acf/input"><img src="/common/img/nav_global02.gif" alt="料金マスタ設定" width="131" height="41" /></a>
<?php }else{ ?>
                            <img src="/common/img/nav_global02_off.gif" alt="料金マスタ設定" width="131" height="41" />
<?php } ?>
                        </li>
                        <li class="nav03">
                            <a href="/asp/list/extra"><img src="/common/img/nav_global03.gif" alt="閑散・繁忙期料金設定" width="169" height="41" /></a>
                        </li>
                        <li class="nav04">
                            <a href="/asp/list/campaign"><img src="/common/img/nav_global04.gif" alt="キャンペーン特価設定" width="168" height="41" /></a>
                        </li>
                        <li class="nav05">
<?php if($outForm->honsha_user_flag() === '1'){ ?>
                            <a href="/aoc/list/"><img src="/common/img/nav_global05.gif" alt="他社連携キャンペーン設定" width="242" height="41" /></a>
<?php }else{ ?>
                            <img src="/common/img/nav_global05_off.gif" alt="他社連携キャンペーン設定" width="242" height="41" />
<?php } ?>
                        </li>
                        <li class="nav06">
                            <a href="/acm/logout"><img src="/common/img/nav_global06.gif" alt="ログアウト" width="99" height="41" /></a>
                        </li>
                    </ul>
                </dd>
            </dl>
            <!-- /#globalNav -->
            <!-- ▲／グローバルナビ end -->
        </div>
        <!-- /#header -->
    </div>
    <!-- /#wrapper -->

    <!-- /#topWrap -->
    <div id="topWrap">

    <div class="helpNav">
    <p><a id="contentTop" name="contentTop"></a>ここから本文です</p>
    </div>

    <form method="post" action="/abi/check_input" enctype="multipart/form-data">
    <table width="900">
        <tr>
            <td colspan="3">
                <h2>
                    <!--エクセル一括取込-->
                </h2>
            </td>
        </tr>
        <tr>
            <td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
        </tr>
        <tr>
            <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
            <td><div class="caption">Excelファイル一括取込</div>
                <div class="outline">
                <table>
                    <tr>
                            <td>取込対象のExcelファイルを選択してください（<a href="/abi/download/sample_travel_mst.xlsx">サンプルダウンロード</a>）</td>
                    </tr>
                    <?php 
                        $uploadMaxFilesize = $outForm->upload_file_size()
                    ?>
                    <?php if(!@empty($uploadMaxFilesize)) : ?>
                    <tr>
                            <td style="color: blue;">※ アップロードファイルサイズは&nbsp;<?php echo $outForm->upload_file_size();?>&nbsp;まで</td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="attention">
                                &nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td style="white-space: nowrap;">ファイル：</td>
                                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                                    <td>
                                        <input type="file" name="up_file" id="up_file" class="up_file" style="width:400px;">
                                    </td>
                                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                                    <td><input type="button" id="import-submit" value="取込" style="width:70px;" onclick="if(document.getElementById('up_file').value != ''){if(window.confirm('取込開始しますがよろしいですか？')){document.getElementById('import-submit').disabled ='true';submit();}}"></td>
                                </tr>
                                <tr>
                                        <td colspan="5">
                                            <?php if(is_array($errorForm)) : ?>
                                                <?php foreach($errorForm as $key => $errMsg) : ?>
                                                        <?php // if(0 === strpos($key, "top_")) : ?>
                                                            <br/>
                                                            <font style="color:red;"><?php if(0!==mb_strpos($errMsg, "・")): ?>・<?php endif; ?><?php echo $errMsg; ?>
                                                        <?php // endif; ?>
                                                <?php endforeach; ?>    
                                            <?php endif; ?>
                                        </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                </div>
            </td>
            <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
        </tr>
    </table>
    <input type='hidden' name='ticket' value='<?php echo $ticket ?>' />
    <input type='hidden' name='formareacd' />
    </form>
            
    </div><!-- /#topWrap -->

    <div id="footer">
        <address><img src="/common/img/txt_copyright.png" alt="&copy; SG Moving Co.,Ltd. All Rights Reserved." /></address>
    </div>
    <!-- /#footer -->
    <!--[if lt IE 9]>
    <script charset="UTF-8" type="text/javascript" src="/common/js/jquery-1.12.4.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/lodash-compat.min.js"></script>
    <![endif]-->
    <!--[if gte IE 9]><!-->
    <script charset="UTF-8" type="text/javascript" src="/common/js/jquery-3.1.1.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/lodash.min.js"></script>
    <!--<![endif]-->
    <script charset="UTF-8" type="text/javascript" src="/common/js/aapList.js"></script>
</body>
</html>
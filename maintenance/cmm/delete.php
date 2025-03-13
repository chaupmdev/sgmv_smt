<?php
/**
 * コメントマスタメンテナンス削除確認画面を表示します。
 * @package    maintenance
 * @subpackage CMM
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('cmm/Delete');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Cmm_Delete();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Cmm012Out
 */
$cmm012Out = $forms['outForm'];

/**
 * エラーフォーム
 * @var Sgmov_Form_Error
 */
$e = $forms['errorForm'];
?>
<!DOCTYPE html>
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
    <link href="/common/css/top_main.css" rel="stylesheet" type="text/css" media="screen,tv,projection,print" />
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
                <h1><a href="<?php echo Sgmov_Component_Config::getUrlMaintenance(); ?>"><img src="/common/img/ttl_sgmoving-logo.png" alt="ＳＧムービング" width="118" height="40" /></a></h1>
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
<?php if($cmm012Out->honsha_user_flag() === '1'){ ?>
                            <a href="/acf/delete"><img src="/common/img/nav_global02.gif" alt="料金マスタ設定" width="131" height="41" /></a>
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
<?php if($cmm012Out->honsha_user_flag() === '1'){ ?>
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

    <div id="topWrap">

        <div class="helpNav">
            <p><a id="contentTop" name="contentTop"></a>ここから本文です</p>
        </div>

<?php if($cmm012Out->sp_list_kind() === Sgmov_View_Cmm_Common::SP_LIST_KIND_COMMENTS){ ?>
        <h2 class="page_ttl"><img src="/common/img/cruise/ttl_master_07.png" width="900" height="36" alt="お客様の声コメント設定" /></h2>
        <h3 class="register_ttl">お客様の声コメント情報の削除</h3>
<?php }else if($cmm012Out->sp_list_kind() === Sgmov_View_Cmm_Common::SP_LIST_KIND_ATTENTION){ ?>
        <h2 class="page_ttl"><img src="/common/img/cruise/ttl_master_08.png" width="900" height="36" alt="この子に注目コメント設定" /></h2>
        <h3 class="register_ttl">この子に注目コメント情報の削除</h3>
<?php } ?>

        <form action="" method="post">
            <div class="register_area">

<?php if (isset($e) && $e->hasErrorForId('top_conflict')) { ?>
                <p class="red"><?php echo $e->getMessage('top_conflict'); ?></p>
<?php } ?>
                <ul class="register_item" id="comment_data_item">
<?php if($cmm012Out->sp_list_kind() === Sgmov_View_Cmm_Common::SP_LIST_KIND_COMMENTS){ ?>
                    <li id="comment_data_item_01" class="clearfix">
                        <fieldset>
                            <legend>タイトル</legend>
                            <?php echo $cmm012Out->comment_title() . PHP_EOL; ?>
                        </fieldset>
                    </li>
                    <li id="comment_data_item_02" class="clearfix">
                        <fieldset>
                            <legend>住所</legend>
                            <?php echo $cmm012Out->comment_address() . PHP_EOL; ?>
                        </fieldset>
                    </li>
<?php }else if($cmm012Out->sp_list_kind() === Sgmov_View_Cmm_Common::SP_LIST_KIND_ATTENTION){ ?>
                    <li id="comment_data_item_03" class="clearfix">
                        <fieldset>
                            <legend>営業所</legend>
                            <?php echo $cmm012Out->center_name() . PHP_EOL; ?>
                        </fieldset>
                    </li>
<?php } ?>
                    <li id="comment_data_item_04" class="clearfix">
                        <fieldset>
                            <legend>氏名</legend>
                            <?php echo $cmm012Out->comment_name() . PHP_EOL; ?>
                        </fieldset>
                    </li>
                    <li id="comment_data_item_05" class="clearfix">
                        <fieldset>
                            <legend>コメント</legend>
                            <div class="comment">
                                <?php echo $cmm012Out->comment_text() . PHP_EOL; ?>
                            </div>
                        </fieldset>
                    </li>
                    <li id="comment_data_item_06" class="clearfix">
                        <fieldset>
                            <legend>掲載開始日</legend>
                            <?php echo $cmm012Out->comment_start_date() . PHP_EOL; ?>
                        </fieldset>
                    </li>
                    <li id="comment_data_item_07" class="clearfix">
                        <fieldset>
                            <legend>掲載開始日</legend>
                            <?php echo $cmm012Out->comment_end_date() . PHP_EOL; ?>
                        </fieldset>
                    </li>
                </ul>
                <input id="comment_id" name="comment_id" type="hidden" value="<?php echo $cmm012Out->comment_id(); ?>" />
                <input name="ticket" type="hidden" value="<?php echo $ticket; ?>" />
                <input id="cmm_list_king" name="cmm_list_king" type="hidden" value="<?php echo $cmm012Out->sp_list_kind(); ?>" />
            </div>
            <img alt="一覧に戻る" id="back_list" src="/common/img/cruise/btn_back_list.png" />
            <img alt="削除する" id="delete" src="/common/img/cruise/btn_delete.png" />
        </form>
    </div>
    <!-- /#topWrap -->
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
    <script charset="UTF-8" type="text/javascript" src="/common/js/cmmDelete.js"></script>
</body>
</html>
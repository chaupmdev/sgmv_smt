<?php
/**
 * ツアー発着地マスタメンテナンス削除確認画面を表示します。
 * @package    maintenance
 * @subpackage ATT
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('att/Delete');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Att_Delete();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Att012Out
 */
$att012Out = $forms['outForm'];

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
<?php if($att012Out->honsha_user_flag() === '1'){ ?>
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
<?php if($att012Out->honsha_user_flag() === '1'){ ?>
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

        <h2 class="page_ttl"><img src="/common/img/cruise/ttl_master_05.png" width="900" height="36" alt="ツアー発着地マスタ設定" /></h2>
        <h3 class="register_ttl">ツアー発着地情報の削除</h3>

        <form action="" method="post">
            <div class="register_area">
<?php if (isset($e) && $e->hasErrorForId('top_conflict')) { ?>
                <p class="red"><?php echo $e->getMessage('top_conflict'); ?></p>
<?php } ?>
                <ul class="register_item" id="travel_terminal_item">
                    <li id="travel_terminal_item_01" class="clearfix">
                        <fieldset>
                            <legend>船名</legend>
                            <?php echo $att012Out->travel_agency_name() . PHP_EOL; ?>
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_02">
                        <fieldset>
                            <legend>乗船日</legend>
                            <?php echo $att012Out->travel_name() . PHP_EOL; ?>
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_03">
                        <fieldset>
                            <legend>ツアー発着地コード</legend>
                            <?php echo $att012Out->travel_terminal_cd() . PHP_EOL; ?>
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_04">
                        <fieldset>
                            <legend>ツアー発着地名</legend>
                            <?php echo $att012Out->travel_terminal_name() . PHP_EOL; ?>
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_05">
                        <fieldset>
                            <legend>郵便番号</legend>
                            <?php echo $att012Out->zip1() . PHP_EOL; ?>
                            -
                            <?php echo $att012Out->zip2() . PHP_EOL; ?>
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_06">
                        <fieldset>
                            <legend>都道府県</legend>
                            <?php echo $att012Out->pref_name() . PHP_EOL; ?>
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_07">
                        <fieldset>
                            <legend>市区町村</legend>
                            <?php echo $att012Out->address() . PHP_EOL; ?>
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_08">
                        <fieldset>
                            <legend>番地・建物名</legend>
                            <?php echo $att012Out->building() . PHP_EOL; ?>
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_09">
                        <fieldset>
                            <legend>発着店名(営業所名)</legend>
                            <?php echo $att012Out->store_name() . PHP_EOL; ?>
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_10">
                        <fieldset>
                            <legend>電話番号</legend>
                            <?php echo $att012Out->tel() . PHP_EOL; ?>
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_11">
                        <fieldset>
                            <legend>発着</legend>
                            <label for="terminal_cd1">
                                出発地の選択肢に表示<?php if ((intval($att012Out->terminal_cd()) & 1) === 1) {echo 'する' . PHP_EOL;} else {echo 'しない' . PHP_EOL;} ?>
                            </label>
                            <label for="terminal_cd2">
                                到着地の選択肢に表示<?php if ((intval($att012Out->terminal_cd()) & 2) === 2) {echo 'する' . PHP_EOL;} else {echo 'しない' . PHP_EOL;} ?>
                            </label>
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_12">
                        <fieldset>
                            <legend>出発日</legend>
                            <?php echo $att012Out->departure_date() . PHP_EOL; ?>
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_13">
                        <fieldset>
                            <legend>出発時刻</legend>
                            <?php echo $att012Out->departure_time() . PHP_EOL; ?>
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_14">
                        <fieldset>
                            <legend>到着日</legend>
                            <?php echo $att012Out->arrival_date() . PHP_EOL; ?>
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_15">
                        <fieldset>
                            <legend>到着時刻</legend>
                            <?php echo $att012Out->arrival_time() . PHP_EOL; ?>
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_16">
                        <fieldset>
                            <legend>往路 顧客コード</legend>
                            <?php echo $att012Out->departure_client_cd() . PHP_EOL; ?>
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_17">
                        <fieldset>
                            <legend>往路 顧客コード枝番</legend>
                            <?php echo $att012Out->departure_client_branch_cd() . PHP_EOL; ?>
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_18">
                        <fieldset>
                            <legend>復路 顧客コード</legend>
                            <?php echo $att012Out->arrival_client_cd() . PHP_EOL; ?>
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_19">
                        <fieldset>
                            <legend>復路 顧客コード枝番</legend>
                            <?php echo $att012Out->arrival_client_branch_cd() . PHP_EOL; ?>
                        </fieldset>
                    </li>
                </ul>
                <input id="travel_terminal_id" name="travel_terminal_id" type="hidden" value="<?php echo $att012Out->travel_terminal_id(); ?>" />
                <input name="ticket" type="hidden" value="<?php echo $ticket; ?>" />
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
    <script charset="UTF-8" type="text/javascript" src="/common/js/attDelete.js"></script>
</body>
</html>
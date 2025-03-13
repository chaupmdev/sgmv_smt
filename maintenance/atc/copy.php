<?php
/**
 * ツアー配送料金マスタメンテナンスコピー画面を表示します。
 * @package    maintenance
 * @subpackage ATC
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('atc/Copy');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Atc_Copy();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Atc022Out
 */
$atc022Out = $forms['outForm'];

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
    <link href="/common/css/top_main_new.css" rel="stylesheet" type="text/css" media="screen,tv,projection,print" />
    <link href="/common/css/cruise.css" rel="stylesheet" type="text/css" />
    <link href="/common/css/jquery-ui.css" rel="stylesheet" type="text/css" />
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
<?php if($atc022Out->honsha_user_flag() === '1'){ ?>
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
<?php if($atc022Out->honsha_user_flag() === '1'){ ?>
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

        <h2 class="page_ttl"><img src="/common/img/cruise/ttl_master_04.png" width="900" height="36" alt="ツアー配送料金マスタ設定" /></h2>
        <h3 class="register_ttl">ツアー配送料金情報のコピー</h3>

        <form action="" method="post">
            <div class="copy register_area">
<?php if (isset($e) && $e->hasErrorForId('top_conflict')) { ?>
                <p class="red"><?php echo $e->getMessage('top_conflict'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_travel_agency_from_cd_sel')) { ?>
                <p class="red">船名（コピー元）<?php echo $e->getMessage('top_travel_agency_from_cd_sel'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_travel_from_cd_sel')) { ?>
                <p class="red">乗船日（コピー元）<?php echo $e->getMessage('top_travel_from_cd_sel'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_travel_terminal_from_cd_sel')) { ?>
                <p class="red">ツアー発着地（コピー元）<?php echo $e->getMessage('top_travel_terminal_from_cd_sel'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_travel_agency_to_cd_sel')) { ?>
                <p class="red">船名（コピー先）<?php echo $e->getMessage('top_travel_agency_to_cd_sel'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_travel_to_cd_sel')) { ?>
                <p class="red">乗船日（コピー先）<?php echo $e->getMessage('top_travel_to_cd_sel'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_travel_terminal_to_cd_sel')) { ?>
                <p class="red">ツアー発着地（コピー先）<?php echo $e->getMessage('top_travel_terminal_to_cd_sel'); ?></p>
<?php } ?>
                <ul class="register_item" id="travel_delivery_charge_item">
                    <li id="travel_delivery_charge_item_00">
                        <strong>コピー元</strong>
                    </li>
                    <li id="travel_delivery_charge_item_01">
                        <fieldset>
                            <legend>船名</legend>
                            <select<?php if (isset($e) && $e->hasErrorForId('top_travel_agency_from_cd_sel')) { echo ' class="bg_red"'; } ?> name="travel_agency_from_cd_sel">
                                <option value="">選択してください</option>
<?php
    echo Sgmov_View_Atc_Common::_createPulldown($atc022Out->travel_agency_cds(), $atc022Out->travel_agency_lbls(), $atc022Out->travel_agency_from_cd_sel());
?>
                            </select>
                        </fieldset>
                    </li>
                    <li id="travel_delivery_charge_item_02">
                        <fieldset>
                            <legend>乗船日名</legend>
                            <select<?php if (isset($e) && $e->hasErrorForId('top_travel_from_cd_sel')) { echo ' class="bg_red"'; } ?> name="travel_from_cd_sel">
                                <option value="">選択してください</option>
<?php
    echo Sgmov_View_Atc_Common::_createPulldownAddRoundTripDiscount($atc022Out->travel_from_cds(), $atc022Out->travel_from_lbls(), $atc022Out->travel_from_cd_sel(), $atc022Out->round_trip_discounts_from());
?>
                            </select>
                        </fieldset>
                    </li>
                    <li id="travel_delivery_charge_item_03">
                        <fieldset>
                            <legend>発着地</legend>
                            <select<?php if (isset($e) && $e->hasErrorForId('top_travel_terminal_from_cd_sel')) { echo ' class="bg_red"'; } ?> name="travel_terminal_from_cd_sel">
                                <option value="">選択してください</option>
<?php
    echo Sgmov_View_Atc_Common::_createPulldown($atc022Out->travel_terminal_from_cds(), $atc022Out->travel_terminal_from_lbls(), $atc022Out->travel_terminal_from_cd_sel());
?>
                            </select>
                        </fieldset>
                    </li>
                    <li id="travel_delivery_charge_item_06">
                        <strong>コピー先</strong>
                    </li>
                    <li id="travel_delivery_charge_item_07">
                        <fieldset>
                            <legend>船名</legend>
                            <?php echo $atc022Out->travel_agency_to_name() . PHP_EOL; ?>
                            <select class="<?php if (isset($e) && $e->hasErrorForId('top_travel_agency_to_cd_sel')) { echo 'bg_red '; } ?>hide" name="travel_agency_to_cd_sel">
                                <option value="">選択してください</option>
<?php
    echo Sgmov_View_Atc_Common::_createPulldown($atc022Out->travel_agency_cds(), $atc022Out->travel_agency_lbls(), $atc022Out->travel_agency_to_cd_sel());
?>
                            </select>
                        </fieldset>
                    </li>
                    <li id="travel_delivery_charge_item_08">
                        <fieldset>
                            <legend>乗船日名</legend>
                            <?php echo $atc022Out->travel_to_name() . PHP_EOL; ?>
                            <select class="<?php if (isset($e) && $e->hasErrorForId('top_travel_to_cd_sel')) { echo 'bg_red '; } ?>hide" name="travel_to_cd_sel">
                                <option value="">選択してください</option>
<?php
    echo Sgmov_View_Atc_Common::_createPulldownAddRoundTripDiscount($atc022Out->travel_to_cds(), $atc022Out->travel_to_lbls(), $atc022Out->travel_to_cd_sel(), $atc022Out->round_trip_discounts_to());
?>
                            </select>
                        </fieldset>
                    </li>
                    <li id="travel_delivery_charge_item_09">
                        <fieldset>
                            <legend>発着地</legend>
                            <?php echo $atc022Out->travel_terminal_to_name() . PHP_EOL; ?>
                            <select class="<?php if (isset($e) && $e->hasErrorForId('top_travel_terminal_to_cd_sel')) { echo 'bg_red '; } ?>hide" name="travel_terminal_to_cd_sel">
                                <option value="">選択してください</option>
<?php
    echo Sgmov_View_Atc_Common::_createPulldown($atc022Out->travel_terminal_to_cds(), $atc022Out->travel_terminal_to_lbls(), $atc022Out->travel_terminal_to_cd_sel());
?>
                            </select>
                        </fieldset>
                    </li>
                    <li id="travel_delivery_charge_item_04">
                        <fieldset>
                            <legend>配送料金（変更前）</legend>
                            <table class="list_table" id="travel_delivery_charge_to_resist_table">
                                <thead>
                                    <tr>
                                        <th>エリア</th>
                                        <th>
                                            バッグ・スーツケース
                                            <br />（1個当たり）
                                        </th>
                                        <th>往復便料金</th>
                                    </tr>
                                </thead>
                                <tbody>
<?php
    // 表の内容はAjaxで作成するため、初期表示時点では作成不要
    //// 呼び出すたびにエンティティ化されるので先に取得しておく
    //$tc_travel_delivery_charge_ids = $atc022Out->travel_delivery_charge_ids();
    //$tc_travel_provinces_ids       = $atc022Out->travel_provinces_ids();
    //$tc_travel_provinces_names     = $atc022Out->travel_provinces_names();
    //$tc_prefecture_names           = $atc022Out->prefecture_names();
    //$tc_delivery_chargs            = $atc022Out->delivery_chargs();
    //
    //$html = '';
    //$count = count($tc_travel_provinces_ids);
    //for ($i = 0; $i < $count;  ++$i) {
    //    $tc_travel_provinces_id = null;
    //    if (isset($tc_travel_provinces_ids[$i])) {
    //        $tc_travel_provinces_id = $tc_travel_provinces_ids[$i];
    //    }
    //
    //    $tc_travel_provinces_name = null;
    //    if (isset($tc_travel_provinces_names[$i])) {
    //        $tc_travel_provinces_name = $tc_travel_provinces_names[$i];
    //    }
    //
    //    $tc_prefecture_name = null;
    //    if (!empty($tc_prefecture_names[$i])) {
    //        $tc_prefecture_name = implode(PHP_EOL, $tc_prefecture_names[$i]);
    //    }
    //
    //    $tc_delivery_charg = null;
    //    if ($tc_travel_provinces_id !== null && isset($tc_delivery_chargs[$tc_travel_provinces_id])) {
    //        $tc_delivery_charg = $tc_delivery_chargs[$tc_travel_provinces_id];
    //    }
    //
    //    $html .= '
    //                                <tr>
    //                                    <th title="' . $tc_prefecture_name . '">' . $tc_travel_provinces_name . '</th>
    //                                    <td class="number">
    //                                        ' . $tc_delivery_charg . '
    //                                        円
    //                                    </td>
    //                                    <td class="number"></td>
    //                                </tr>';
    //}
    //echo $html;
?>
                                </tbody>
                            </table>
                        </fieldset>
                    </li>
                    <li id="travel_delivery_charge_item_05">
                        <fieldset>
                            <legend>配送料金（変更後）</legend>
                            <table class="list_table" id="travel_delivery_charge_from_resist_table">
                                <thead>
                                    <tr>
                                        <th>エリア</th>
                                        <th>
                                            バッグ・スーツケース
                                            <br />（1個当たり）
                                        </th>
                                        <th>往復便料金</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </fieldset>
                    </li>
                </ul>
                <div class="clear"></div>
                <input id="travel_delivery_charge_id" name="travel_delivery_charge_id" type="hidden" value="<?php echo $atc022Out->travel_delivery_charge_id(); ?>" />
                <input name="ticket" type="hidden" value="<?php echo $ticket; ?>" />
            </div>
            <img alt="一覧に戻る" id="back_list" src="/common/img/cruise/btn_back_list.png" />
            <img alt="登録する" id="register" src="/common/img/cruise/btn_register.png" />
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
    <script charset="UTF-8" type="text/javascript" src="/common/js/jquery-ui.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/api.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/atcCopy.js"></script>
</body>
</html>
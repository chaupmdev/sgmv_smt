<?php
/**
 * ツアー配送料金マスタメンテナンス入力画面を表示します。
 * @package    maintenance
 * @subpackage ATC
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('atc/Input');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Atc_Input();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Atc002Out
 */
$atc002Out = $forms['outForm'];

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
<?php if($atc002Out->honsha_user_flag() === '1'){ ?>
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
<?php if($atc002Out->honsha_user_flag() === '1'){ ?>
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
        <h3 class="register_ttl">ツアー配送料金情報の登録</h3>

        <form action="" method="post">
            <div class="register_area">
<?php if (isset($e) && $e->hasErrorForId('top_conflict')) { ?>
                <p class="red"><?php echo $e->getMessage('top_conflict'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_travel_agency_cd_sel')) { ?>
                <p class="red">船名<?php echo $e->getMessage('top_travel_agency_cd_sel'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_travel_cd_sel')) { ?>
                <p class="red">乗船日<?php echo $e->getMessage('top_travel_cd_sel'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_travel_terminal_cd_sel')) { ?>
                <p class="red">ツアー発着地<?php echo $e->getMessage('top_travel_terminal_cd_sel'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_delivery_charg')) { ?>
                <p class="red">ツアー配送料金<?php echo $e->getMessage('top_delivery_charg'); ?></p>
<?php } ?>
                <ul class="register_item" id="travel_delivery_charge_item">
                    <li id="travel_delivery_charge_item_01">
                        <fieldset>
                            <legend>船名</legend>
                            <?php echo $atc002Out->travel_agency_name() . PHP_EOL; ?>
                            <select class="hidden<?php if (isset($e) && $e->hasErrorForId('top_travel_agency_cd_sel')) { echo ' bg_red'; } ?>"  name="travel_agency_cd_sel">
                                <option value="">選択してください</option>
<?php
    echo Sgmov_View_Atc_Common::_createPulldown($atc002Out->travel_agency_cds(), $atc002Out->travel_agency_lbls(), $atc002Out->travel_agency_cd_sel());
?>
                            </select>
                        </fieldset>
                    </li>
                    <li id="travel_delivery_charge_item_02">
                        <fieldset>
                            <legend>乗船日名</legend>
                            <?php echo $atc002Out->travel_name() . PHP_EOL; ?>
                            <select class="hidden<?php if (isset($e) && $e->hasErrorForId('top_travel_cd_sel')) { echo ' bg_red'; } ?>"  name="travel_cd_sel">
                                <option value="">選択してください</option>
<?php
    echo Sgmov_View_Atc_Common::_createPulldownAddRoundTripDiscount($atc002Out->travel_cds(), $atc002Out->travel_lbls(), $atc002Out->travel_cd_sel(), $atc002Out->round_trip_discounts());
?>
                            </select>
                        </fieldset>
                    </li>
                    <li id="travel_delivery_charge_item_03">
                        <fieldset>
                            <legend>発着地</legend>
                            <?php echo $atc002Out->travel_terminal_name() . PHP_EOL; ?>
                            <select class="hidden<?php if (isset($e) && $e->hasErrorForId('top_travel_terminal_cd_sel')) { echo ' bg_red'; } ?>" name="travel_terminal_cd_sel">
                                <option value="">選択してください</option>
<?php
    echo Sgmov_View_Atc_Common::_createPulldown($atc002Out->travel_terminal_cds(), $atc002Out->travel_terminal_lbls(), $atc002Out->travel_terminal_cd_sel());
?>
                            </select>
                        </fieldset>
                    </li>
                    <li id="travel_delivery_charge_item_04">
                        <fieldset>
                            <legend>配送料金</legend>
                            <table class="list_table" id="travel_delivery_charge_resist_table">
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
    // 呼び出すたびにエンティティ化されるので先に取得しておく
    $tc_travel_delivery_charge_ids = $atc002Out->travel_delivery_charge_ids();
    $tc_travel_provinces_ids       = $atc002Out->travel_provinces_ids();
    $tc_travel_provinces_names     = $atc002Out->travel_provinces_names();
    $tc_prefecture_names           = $atc002Out->prefecture_names();
    $tc_delivery_chargs            = $atc002Out->delivery_chargs();

    $html = '';
    $count = count($tc_travel_provinces_ids);
    for ($i = 0; $i < $count;  ++$i) {
        $tc_travel_provinces_id = null;
        if (isset($tc_travel_provinces_ids[$i])) {
            $tc_travel_provinces_id = $tc_travel_provinces_ids[$i];
        }

        $tc_travel_provinces_name = null;
        if (isset($tc_travel_provinces_names[$i])) {
            $tc_travel_provinces_name = $tc_travel_provinces_names[$i];
        }

        $tc_prefecture_name = null;
        if (!empty($tc_prefecture_names[$i])) {
            $tc_prefecture_name = implode(PHP_EOL, $tc_prefecture_names[$i]);
        }

        $tc_delivery_charg = null;
        if ($tc_travel_provinces_id !== null && isset($tc_delivery_chargs[$tc_travel_provinces_id])) {
            $tc_delivery_charg = $tc_delivery_chargs[$tc_travel_provinces_id];
        }

        $html .= '
                                    <tr>
                                        <th title="' . $tc_prefecture_name . '">' . $tc_travel_provinces_name . '</th>
                                        <td class="number">
                                            <input autocapitalize="off" inputmode="numeric" name="delivery_charg[' . $tc_travel_provinces_id . ']" data-pattern="^\d+$" type="number" value="' . $tc_delivery_charg . '" />
                                            円
                                        </td>
                                        <td class="number"></td>
                                    </tr>';
    }
    echo $html;
?>
                                </tbody>
                            </table>
                        </fieldset>
                    </li>
                </ul>
                <input id="travel_delivery_charge_id" name="travel_delivery_charge_id" type="hidden" value="<?php echo $atc002Out->travel_delivery_charge_id(); ?>" />
                <input name="ticket" type="hidden" value="<?php echo $ticket; ?>" />
            </div>
            <img alt="一覧に戻る" id="back_list" src="/common/img/cruise/btn_back_list.png" />
            <img alt="初期値に戻す" id="back_default" src="/common/img/cruise/btn_default.png" />
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
    <script charset="UTF-8" type="text/javascript" src="/common/js/atcInput.js"></script>
</body>
</html>
<?php
/**
 * ツアー発着地マスタメンテナンス入力画面を表示します。
 * @package    maintenance
 * @subpackage ATT
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('att/Input');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Att_Input();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Att002Out
 */
$att002Out = $forms['outForm'];

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
    <link href="/common/css/jquery-ui.css" rel="stylesheet" type="text/css" />
    <link href="/common/css/jquery.ui.timepicker.css" rel="stylesheet" type="text/css" />
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
<?php if($att002Out->honsha_user_flag() === '1'){ ?>
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
<?php if($att002Out->honsha_user_flag() === '1'){ ?>
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
        <h3 class="register_ttl">ツアー発着地情報の登録</h3>

        <form action="" data-feature-id="<?php echo Sgmov_View_Att_Common::FEATURE_ID; ?>" data-id="<?php echo Sgmov_View_Att_Common::GAMEN_ID_ATT002; ?>" method="post">
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
<?php if (isset($e) && $e->hasErrorForId('top_travel_terminal_cd')) { ?>
                <p class="red">ツアー発着地コード<?php echo $e->getMessage('top_travel_terminal_cd'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_travel_terminal_name')) { ?>
                <p class="red">ツアー発着地<?php echo $e->getMessage('top_travel_terminal_name'); ?></p>
<?php } ?>

<?php if (isset($e) && $e->hasErrorForId('top_zip')) { ?>
                <p class="red">郵便番号<?php echo $e->getMessage('top_zip'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_pref_cd_sel')) { ?>
                <p class="red">都道府県<?php echo $e->getMessage('top_pref_cd_sel'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_address')) { ?>
                <p class="red">住所共通<?php echo $e->getMessage('top_address'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_building')) { ?>
                <p class="red">往路住所<?php echo $e->getMessage('top_building'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_store_name')) { ?>
                <p class="red">復路住所<?php echo $e->getMessage('top_store_name'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_tel')) { ?>
                <p class="red">電話番号<?php echo $e->getMessage('top_tel'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_terminal_cd')) { ?>
                <p class="red">発着<?php echo $e->getMessage('top_terminal_cd'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_departure_date')) { ?>
                <p class="red">出発日<?php echo $e->getMessage('top_departure_date'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_departure_time')) { ?>
                <p class="red">出発時刻<?php echo $e->getMessage('top_departure_time'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_arrival_date')) { ?>
                <p class="red">到着日<?php echo $e->getMessage('top_arrival_date'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_arrival_time')) { ?>
                <p class="red">到着時刻<?php echo $e->getMessage('top_arrival_time'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_departure_client_cd')) { ?>
                <p class="red">往路 顧客コード<?php echo $e->getMessage('top_departure_client_cd'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_departure_client_branch_cd')) { ?>
                <p class="red">往路 顧客コード枝番<?php echo $e->getMessage('top_departure_client_branch_cd'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_arrival_client_cd')) { ?>
                <p class="red">復路 顧客コード<?php echo $e->getMessage('top_arrival_client_cd'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_arrival_client_branch_cd')) { ?>
                <p class="red">復路 顧客コード枝番<?php echo $e->getMessage('top_arrival_client_branch_cd'); ?></p>
<?php } ?>
                <ul class="register_item" id="travel_terminal_item">
                    <li id="travel_terminal_item_01" class="clearfix">
                        <fieldset>
                            <legend>船名</legend>
                            <select<?php if (isset($e) && $e->hasErrorForId('top_travel_agency_cd_sel')) { echo ' class="bg_red"'; } ?> name="travel_agency_cd_sel">
                                <option value="">選択してください</option>
<?php
    echo Sgmov_View_Att_Common::_createPulldown($att002Out->travel_agency_cds(), $att002Out->travel_agency_lbls(), $att002Out->travel_agency_cd_sel());
?>
                            </select>
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_02">
                        <fieldset>
                            <legend>乗船日</legend>
                            <select<?php if (isset($e) && $e->hasErrorForId('top_travel_cd_sel')) { echo ' class="bg_red"'; } ?> name="travel_cd_sel">
                                <option value="">選択してください</option>
<?php
    echo Sgmov_View_Att_Common::_createPulldown($att002Out->travel_cds(), $att002Out->travel_lbls(), $att002Out->travel_cd_sel());
?>
                            </select>
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_03">
                        <fieldset>
                            <legend>ツアー発着地コード</legend>
                            <input autocapitalize="off"<?php if (isset($e) && $e->hasErrorForId('top_travel_terminal_cd')) { echo ' class="bg_red"'; } ?> id="travel_terminal_cd" inputmode="numeric" name="travel_terminal_cd" type="text" value="<?php echo $att002Out->travel_terminal_cd(); ?>" />
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_04">
                        <fieldset>
                            <legend>ツアー発着地名</legend>
                            <input autocapitalize="off"<?php if (isset($e) && $e->hasErrorForId('top_travel_terminal_name')) { echo ' class="bg_red"'; } ?> id="travel_terminal_name" name="travel_terminal_name" type="text" value="<?php echo $att002Out->travel_terminal_name(); ?>" />
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_05">
                        <fieldset>
                            <legend>郵便番号</legend>
                            <input autocapitalize="off"<?php if (isset($e) && $e->hasErrorForId('top_zip')) { echo ' class="bg_red"'; } ?> id="zip1" inputmode="numeric" name="zip1" type="text" value="<?php echo $att002Out->zip1(); ?>" />
                            -
                            <input autocapitalize="off"<?php if (isset($e) && $e->hasErrorForId('top_zip')) { echo ' class="bg_red"'; } ?> id="zip2" inputmode="numeric" name="zip2" type="text" value="<?php echo $att002Out->zip2(); ?>" />
                            <input name="adrs_search_btn" type="button" value="住所検索" />
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_06">
                        <fieldset>
                            <legend>都道府県</legend>
                            <select<?php if (isset($e) && $e->hasErrorForId('top_')) { echo ' class="bg_red"'; } ?> id="pref_cd_sel" name="pref_cd_sel">
                                <option value="">選択してください</option>
<?php
    echo Sgmov_View_Att_Common::_createPulldown($att002Out->pref_cds(), $att002Out->pref_lbls(), $att002Out->pref_cd_sel());
?>
                            </select>
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_07">
                        <fieldset>
                            <legend>住所共通</legend>
<!-- 一時コメント
                            <legend>市区町村</legend>
-->
                            <input autocapitalize="off"<?php if (isset($e) && $e->hasErrorForId('top_address')) { echo ' class="bg_red"'; } ?> id="address" name="address" type="text" value="<?php echo $att002Out->address(); ?>" />
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_08">
                        <fieldset>
                            <legend>往路住所</legend>
<!-- 一時コメント
                            <legend>番地・建物名</legend>
-->
                            <input autocapitalize="off"<?php if (isset($e) && $e->hasErrorForId('top_building')) { echo ' class="bg_red"'; } ?> id="building" name="building" type="text" value="<?php echo $att002Out->building(); ?>" />
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_09">
                        <fieldset>
                            <legend>復路住所</legend>
<!-- 一時コメント
                            <legend>発着店名(営業所名)</legend>
-->
                            <input autocapitalize="off"<?php if (isset($e) && $e->hasErrorForId('top_store_name')) { echo ' class="bg_red"'; } ?> id="store_name" name="store_name" type="text" value="<?php echo $att002Out->store_name(); ?>" />
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_10">
                        <fieldset>
                            <legend>電話番号</legend>
                            <input autocapitalize="off"<?php if (isset($e) && $e->hasErrorForId('top_tel')) { echo ' class="bg_red"'; } ?> id="tel" inputmode="numeric" name="tel" type="text" value="<?php echo $att002Out->tel(); ?>" />
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_11">
                        <fieldset>
                            <legend>発着</legend>
                            <label for="terminal_cd1">
                                <input<?php if ((intval($att002Out->terminal_cd()) & 1) === 1) {echo ' checked="checked"';} ?> id="terminal_cd1" name="terminal_cd1" type="checkbox" value="1" />
                                出発地の選択肢に表示する
                            </label>
                            <label for="terminal_cd2">
                                <input<?php if ((intval($att002Out->terminal_cd()) & 2) === 2) {echo ' checked="checked"';} ?> id="terminal_cd2" name="terminal_cd2" type="checkbox" value="2" />
                                到着地の選択肢に表示する
                            </label>
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_12">
                        <fieldset>
                            <legend>出発日</legend>
                            <input autocapitalize="off" class="<?php if (isset($e) && $e->hasErrorForId('top_departure_date')) { echo 'bg_red '; } ?>datepicker" id="departure_date" name="departure_date" type="text" value="<?php echo $att002Out->departure_date(); ?>" />
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_13">
                        <fieldset>
                            <legend>出発時刻</legend>
                            <input autocapitalize="off" class="<?php if (isset($e) && $e->hasErrorForId('top_departure_time')) { echo 'bg_red '; } ?>timepicker" id="departure_time" name="departure_time" type="text" value="<?php echo $att002Out->departure_time(); ?>" />
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_14">
                        <fieldset>
                            <legend>到着日</legend>
                            <input autocapitalize="off" class="<?php if (isset($e) && $e->hasErrorForId('top_arrival_date')) { echo 'bg_red '; } ?>datepicker" id="arrival_date" name="arrival_date" type="text" value="<?php echo $att002Out->arrival_date(); ?>" />
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_15">
                        <fieldset>
                            <legend>到着時刻</legend>
                            <input autocapitalize="off" class="<?php if (isset($e) && $e->hasErrorForId('top_arrival_time')) { echo 'bg_red '; } ?>timepicker" id="arrival_time" name="arrival_time" type="text" value="<?php echo $att002Out->arrival_time(); ?>" />
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_16">
                        <fieldset>
                            <legend>往路 顧客コード</legend>
                            <input autocapitalize="off"<?php if (isset($e) && $e->hasErrorForId('top_departure_client_cd')) { echo ' class="bg_red"'; } ?> id="departure_client_cd" inputmode="numeric" name="departure_client_cd" type="text" value="<?php echo $att002Out->departure_client_cd(); ?>" />
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_17">
                        <fieldset>
                            <legend>往路 顧客コード枝番</legend>
                            <input autocapitalize="off"<?php if (isset($e) && $e->hasErrorForId('top_departure_client_branch_cd')) { echo ' class="bg_red"'; } ?> id="departure_client_branch_cd" inputmode="numeric" name="departure_client_branch_cd" type="text" value="<?php echo $att002Out->departure_client_branch_cd(); ?>" />
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_18">
                        <fieldset>
                            <legend>復路 顧客コード</legend>
                            <input autocapitalize="off"<?php if (isset($e) && $e->hasErrorForId('top_arrival_client_cd')) { echo ' class="bg_red"'; } ?> id="arrival_client_cd" inputmode="numeric" name="arrival_client_cd" type="text" value="<?php echo $att002Out->arrival_client_cd(); ?>" />
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_19">
                        <fieldset>
                            <legend>復路 顧客コード枝番</legend>
                            <input autocapitalize="off"<?php if (isset($e) && $e->hasErrorForId('top_arrival_client_branch_cd')) { echo ' class="bg_red"'; } ?> id="arrival_client_branch_cd" inputmode="numeric" name="arrival_client_branch_cd" type="text" value="<?php echo $att002Out->arrival_client_branch_cd(); ?>" />
                        </fieldset>
                    </li>
<!-- 一時コメント
                    <li id="travel_terminal_item_20">
                        <fieldset>
                            <legend>決済選択区分</legend>
                            <label for="yyyy">
                                <input<?php if ((intval($att002Out->terminal_cd()) & 2) === 2) {echo ' checked="checked"';} ?> id="yyyy" name="yyyy" type="checkbox" value="2" />
                                クレジットカード選択可
                            </label>
                            <label for="xxxx">
                                <input<?php if ((intval($att002Out->terminal_cd()) & 1) === 1) {echo ' checked="checked"';} ?> id="xxxx" name="xxxx" type="checkbox" value="1" />
                                コンビニ決済選択可
                            </label>
                        </fieldset>
                    </li>
-->
                </ul>
                <input id="travel_terminal_id" name="travel_terminal_id" type="hidden" value="<?php echo $att002Out->travel_terminal_id(); ?>" />
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
    <script charset="UTF-8" type="text/javascript" src="/common/js/jquery.ui.datepicker-ja.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/ajax_searchaddress.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/jquery.ui.timepicker.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/jquery.ui.timepicker-ja.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/api.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/attInput.js"></script>
</body>
</html>
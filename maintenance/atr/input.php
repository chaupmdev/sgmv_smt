<?php
/**
 * ツアーマスタメンテナンス入力画面を表示します。
 * @package    maintenance
 * @subpackage ATR
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('atr/Input');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Atr_Input();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Atr002Out
 */
$atr002Out = $forms['outForm'];

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
<?php if($atr002Out->honsha_user_flag() === '1'){ ?>
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
<?php if($atr002Out->honsha_user_flag() === '1'){ ?>
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

        <h2 class="page_ttl"><img src="/common/img/cruise/ttl_master_02.png" width="900" height="36" alt="ツアーマスタ設定" /></h2>
        <h3 class="register_ttl">ツアー情報の登録</h3>

        <form action="" method="post">
            <div class="register_area">
<?php if (isset($e) && $e->hasErrorForId('top_conflict')) { ?>
                <p class="red"><?php echo $e->getMessage('top_conflict'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_travel_cd')) { ?>
                <p class="red">ツアーコード<?php echo $e->getMessage('top_travel_cd'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_travel_name')) { ?>
                <p class="red">乗船日名<?php echo $e->getMessage('top_travel_name'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_travel_agency_cd_sel')) { ?>
                <p class="red">船名<?php echo $e->getMessage('top_travel_agency_cd_sel'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_round_trip_discount')) { ?>
                <p class="red">往復便割引<?php echo $e->getMessage('top_round_trip_discount'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_repeater_discount')) { ?>
                <p class="red">リピータ割引<?php echo $e->getMessage('top_repeater_discount'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_embarkation_date')) { ?>
                <p class="red">乗船日<?php echo $e->getMessage('top_embarkation_date'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_publish_begin_date')) { ?>
                <p class="red">掲載開始日<?php echo $e->getMessage('top_publish_begin_date'); ?></p>
<?php } ?>
                <ul class="register_item" id="travel_item">
                    <li id="travel_item_01" class="clearfix">
                        <fieldset>
                            <legend>ツアーコード</legend>
                            <input autocapitalize="off"<?php if (isset($e) && $e->hasErrorForId('top_travel_cd')) { echo ' class="bg_red"'; } ?> id="travel_cd" inputmode="numeric" name="travel_cd" type="text" value="<?php echo $atr002Out->travel_cd(); ?>" />
                        </fieldset>
                    </li>
                    <li id="travel_item_02">
                        <fieldset>
                            <legend>乗船日名</legend>
                            <input autocapitalize="off"<?php if (isset($e) && $e->hasErrorForId('top_travel_name')) { echo ' class="bg_red"'; } ?> id="travel_name" name="travel_name" type="text" value="<?php echo $atr002Out->travel_name(); ?>" />
                        </fieldset>
                    </li>
                    <li id="travel_item_03">
                        <fieldset>
                            <legend>船名</legend>
                            <select<?php if (isset($e) && $e->hasErrorForId('top_travel_agency_cd_sel')) { echo ' class="bg_red"'; } ?> name="travel_agency_cd_sel">
                                <option value="">選択してください</option>
<?php
    echo Sgmov_View_Atr_Common::_createPulldown($atr002Out->travel_agency_cds(), $atr002Out->travel_agency_lbls(), $atr002Out->travel_agency_cd_sel());
?>
                            </select>
                        </fieldset>
                    </li>
                    <li id="travel_item_04">
                        <fieldset>
                            <legend>往復便割引</legend>
                            <input autocapitalize="off"<?php if (isset($e) && $e->hasErrorForId('top_round_trip_discount')) { echo ' class="bg_red"'; } ?> id="round_trip_discount" inputmode="numeric" name="round_trip_discount" type="number" value="<?php echo $atr002Out->round_trip_discount(); ?>" />
                            円
                        </fieldset>
                    </li>
                    <li id="travel_item_05">
                        <fieldset>
                            <legend>リピータ割引</legend>
                            <input autocapitalize="off"<?php if (isset($e) && $e->hasErrorForId('top_repeater_discount')) { echo ' class="bg_red"'; } ?> id="repeater_discount" inputmode="numeric" name="repeater_discount" type="number" value="<?php echo $atr002Out->repeater_discount(); ?>" />
                            円
                        </fieldset>
                    </li>
                    <li id="travel_item_06">
                        <fieldset>
                            <legend>乗船日</legend>
                            <input autocapitalize="off" class="<?php if (isset($e) && $e->hasErrorForId('top_embarkation_date')) { echo 'bg_red '; } ?>datepicker" id="embarkation_date" name="embarkation_date" type="text" value="<?php echo $atr002Out->embarkation_date(); ?>" />
                        </fieldset>
                    </li>
                    <li id="travel_item_07">
                        <fieldset>
                            <legend>掲載開始日</legend>
                            <input autocapitalize="off" class="<?php if (isset($e) && $e->hasErrorForId('top_publish_begin_date')) { echo 'bg_red '; } ?>datepicker" id="publish_begin_date" name="publish_begin_date" type="text" value="<?php echo $atr002Out->publish_begin_date(); ?>" />
                        </fieldset>
                    </li>
                </ul>
                <input id="travel_id" name="travel_id" type="hidden" value="<?php echo $atr002Out->travel_id(); ?>" />
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
    <script charset="UTF-8" type="text/javascript" src="/common/js/atrInput.js"></script>
</body>
</html>
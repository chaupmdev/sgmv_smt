<?php
/**
 * マンションマスタメンテナンス入力画面を表示します。
 * @package    maintenance
 * @subpackage AAP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('aap/Input');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Aap_Input();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Aap002Out
 */
$aap002Out = $forms['outForm'];

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
<?php if($aap002Out->honsha_user_flag() === '1'){ ?>
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
<?php if($aap002Out->honsha_user_flag() === '1'){ ?>
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

        <h2 class="page_ttl"><img src="/common/img/cruise/ttl_master_06.png" width="900" height="36" alt="マンションマスタ設定" /></h2>
        <h3 class="register_ttl">マンション情報の登録</h3>

        <form action="" data-feature-id="<?php echo Sgmov_View_Aap_Common::FEATURE_ID; ?>" data-id="<?php echo Sgmov_View_Aap_Common::GAMEN_ID_AAP002; ?>" method="post">
            <div class="register_area">
<?php if (isset($e) && $e->hasErrorForId('top_conflict')) { ?>
                <p class="red"><?php echo $e->getMessage('top_conflict'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_apartment_cd')) { ?>
                <p class="red">マンションコード<?php echo $e->getMessage('top_apartment_cd'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_apartment_name')) { ?>
                <p class="red">マンション名<?php echo $e->getMessage('top_apartment_name'); ?></p>
<?php } ?>

<?php if (isset($e) && $e->hasErrorForId('top_zip')) { ?>
                <p class="red">郵便番号<?php echo $e->getMessage('top_zip'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_pref_cd_sel')) { ?>
                <p class="red">都道府県<?php echo $e->getMessage('top_pref_cd_sel'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_address')) { ?>
                <p class="red">住所<?php echo $e->getMessage('top_address'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_agency_cd')) { ?>
                <p class="red">取引先コード<?php echo $e->getMessage('top_agency_cd'); ?></p>
<?php } ?>
                <ul class="register_item" id="apartment_item">
                    <li id="apartment_item_01" class="clearfix">
                        <fieldset>
                            <legend>マンションコード</legend>
                            <input autocapitalize="off"<?php if (isset($e) && $e->hasErrorForId('top_apartment_cd')) { echo ' class="bg_red"'; } ?> id="apartment_cd" inputmode="numeric" name="apartment_cd" type="text" value="<?php echo $aap002Out->apartment_cd(); ?>" />
                        </fieldset>
                    </li>
                    <li id="apartment_item_02">
                        <fieldset>
                            <legend>マンション名</legend>
                            <input autocapitalize="off"<?php if (isset($e) && $e->hasErrorForId('top_apartment_name')) { echo ' class="bg_red"'; } ?> id="apartment_name" name="apartment_name" type="text" value="<?php echo $aap002Out->apartment_name(); ?>" />
                        </fieldset>
                    </li>
                    <li id="apartment_item_03">
                        <fieldset>
                            <legend>郵便番号</legend>
                            <input autocapitalize="off"<?php if (isset($e) && $e->hasErrorForId('top_zip')) { echo ' class="bg_red"'; } ?> id="zip1" inputmode="numeric" name="zip1" type="text" value="<?php echo $aap002Out->zip1(); ?>" />
                            -
                            <input autocapitalize="off"<?php if (isset($e) && $e->hasErrorForId('top_zip')) { echo ' class="bg_red"'; } ?> id="zip2" inputmode="numeric" name="zip2" type="text" value="<?php echo $aap002Out->zip2(); ?>" />
                            <input name="adrs_search_btn" type="button" value="住所検索" />
                            <input name="pref_cd_sel" type="hidden" value="" />
                        </fieldset>
                    </li>
                    <li id="apartment_item_04">
                        <fieldset>
                            <legend>住所</legend>
                            <input autocapitalize="off"<?php if (isset($e) && $e->hasErrorForId('top_address')) { echo ' class="bg_red"'; } ?> id="address" name="address" type="text" value="<?php echo $aap002Out->address(); ?>" />
                        </fieldset>
                    </li>
                    <li id="apartment_item_05">
                        <fieldset>
                            <legend>取引先コード</legend>
                            <input autocapitalize="off"<?php if (isset($e) && $e->hasErrorForId('top_agency_cd')) { echo ' class="bg_red"'; } ?> id="agency_cd" inputmode="numeric" name="agency_cd" type="text" value="<?php echo $aap002Out->agency_cd(); ?>" />
                        </fieldset>
                    </li>
                </ul>
                <input id="apartment_id" name="apartment_id" type="hidden" value="<?php echo $aap002Out->apartment_id(); ?>" />
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
    <script charset="UTF-8" type="text/javascript" src="/common/js/ajax_searchaddress.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/aapInput.js"></script>
</body>
</html>
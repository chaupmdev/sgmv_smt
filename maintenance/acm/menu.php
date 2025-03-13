<?php
/**
 * メニュー画面を表示します。
 * @package    maintenance
 * @subpackage ACM
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('acm/Menu');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Acm_Menu();
$forms = $view->execute();

/**
 * フォーム
 * @var Sgmov_Form_Acm002Out
 */
$acm002Out = $forms['outForm'];
?>
<!DOCTYPE html>
<html dir="ltr" lang="ja-jp" xml:lang="ja-jp">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <title>ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
    <meta name="author" content="SG MOVING Co.,Ltd" />
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved." />
    <link href="/common/css/top_top.css" rel="stylesheet" type="text/css" media="screen,tv,projection,print" />
    <link href="/common/css/top_print.css" rel="stylesheet" type="text/css" media="print" />
    <link href="/common/css/top_main_new.css" rel="stylesheet" type="text/css" media="screen,tv,projection,print" />
</head>
<body id="menuCat">
    <div class="helpNav">
        <p><a id="pageTop" name="pageTop"></a>このページの先頭です</p>
        <p><a href="#contentTop">メニューを飛ばして本文を読む</a></p>
    </div>

    <div id="wrapper">

        <div id="header">
            <!-- ▼SGH共通ヘッダー start -->
            <div id="sghHeader">
                <h1><a href="<?php echo Sgmov_Component_Config::getUrlMaintenance(); ?>"><img src="/common/img/ttl_sgmoving-logo.gif" alt="ＳＧムービング" width="118" height="40" /></a></h1>
                <p class="sgh">
                    <a href="http://www.sg-hldgs.co.jp/" target="_blank">
                        <img src="/common/img/pct_sgh-logo.gif" alt="ＳＧホールディングス" width="41" height="29" />
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
<?php if($acm002Out->honsha_user_flag() === '1'){ ?>
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
<?php if($acm002Out->honsha_user_flag() === '1'){ ?>
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

        <div id="mainbox">
            <h2><img src="/common/img/acm/ttl_menu_01.gif" alt="メニュー" /></h2>
            <table class="box_s menutbl">
                <colgroup>
                    <col class="menu" />
                    <col />
                </colgroup>
                <tbody>
<?php if($acm002Out->honsha_user_flag() === '1'){ ?>
                    <tr class="sp">
                        <td class="menu">
                            <a href="/acf/input">料金マスタ設定</a>
                        </td>
                        <td class="txt_s">
                            基本となる料金のマスタデータを修正
                        </td>
                    </tr>
<?php } ?>
                    <tr class="sp">
                        <td class="menu">
                            <a href="/asp/list/extra">閑散・繁忙期料金設定</a>
                        </td>
                        <td class="txt_s">
                            閑散期・繁忙期の一時料金値下げ・値上げを設定
                        </td>
                    </tr>
                    <tr class="sp">
                        <td class="menu">
                            <a href="/asp/list/campaign">キャンペーン特価設定</a>
                        </td>
                        <td class="txt_s">
                            ホームページにキャンペーンとして表示される特価を設定
                        </td>
                    </tr>
<?php if($acm002Out->honsha_user_flag() === '1'){ ?>
                    <tr class="sp">
                        <td class="menu">
                            <a href="/aoc/list">他社連携キャンペーン設定</a>
                        </td>
                        <td class="txt_s">
                            他社連携キャンペーンの情報を設定
                        </td>
                    </tr>
<?php /* ?>
                    <tr class="sp">
                        <td class="menu">
                            <a href="/aap/list">マンションマスタ設定</a>
                        </td>
                        <td class="txt_s">
                            マンション名、取次店コードを設定
                        </td>
                    </tr>
<?php */ ?>
                    <tr aria-selected="false" class="sp">
                        <td class="menu">
                            <a href="#">ツアー関連マスタ設定</a>
                        </td>
                        <td class="txt_s">
                            ツアーに関連するマスタを表示
                        </td>
                    </tr>
                    <tr aria-expanded="false" aria-hidden="true" class="sp">
                        <td class="menu">
                            <a href="/ata/list">ツアー会社マスタ設定</a>
                        </td>
                        <td class="txt_s">
                            ツアーの船名を設定
                        </td>
                    </tr>
                    <tr aria-expanded="false" aria-hidden="true" class="sp">
                        <td class="menu">
                            <a href="/atr/list">ツアーマスタ設定</a>
                        </td>
                        <td class="txt_s">
                            ツアーの乗船日名、往復割引、リピータ割引を設定
                        </td>
                    </tr>
                    <tr aria-expanded="false" aria-hidden="true" class="sp">
                        <td class="menu">
                            <a href="/att/list">ツアー発着地マスタ設定</a>
                        </td>
                        <td class="txt_s">
                            ツアーの発着地情報、顧客情報を設定
                        </td>
                    </tr>
                    <tr aria-expanded="false" aria-hidden="true" class="sp">
                        <td class="menu">
                            <a href="/atp/list">ツアーエリアマスタ設定</a>
                        </td>
                        <td class="txt_s">
                            ツアー料金判定のエリアを設定
                        </td>
                    </tr>
                    <tr aria-expanded="false" aria-hidden="true" class="sp">
                        <td class="menu">
                            <a href="/atc/list">ツアー配送料金マスタ設定</a>
                        </td>
                        <td class="txt_s">
                            ツアーの配送料金を設定
                        </td>
                    </tr>
                    <tr aria-expanded="false" aria-hidden="true" class="sp">
                        <td class="menu">
                            <a href="/abi/input">ツアー関連データ一括取込</a>
                        </td>
                        <td class="txt_s">
                            ツアー関連データ一括取込
                        </td>
                    </tr>
<?php /* ?>
                    <tr class="sp">
                        <td class="menu">
                            <a href="/cmm/list/comments">お客様の声コメント設定</a>
                        </td>
                        <td class="txt_s">
                            お客様の声の掲載内容を設定
                        </td>
                    </tr>
                    <tr class="sp">
                        <td class="menu">
                            <a href="/cmm/list/attention">この子に注目コメント設定</a>
                        </td>
                        <td class="txt_s">
                            この子に注目の掲載内容を設定
                        </td>
                    </tr>
<?php */ ?>
<?php } ?>
                    <tr class="sp">
                        <td class="menu">
                            <a href="/acm/logout">ログアウト</a>
                        </td>
                        <td class="txt_s">
                            管理画面からログアウトします
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- /#mainbox -->
    </div>
    <!-- /#topWrap -->

    <div id="footer">
        <address><img src="/common/img/txt_copyright.gif" alt="&copy; SG Moving Co.,Ltd. All Rights Reserved." /></address>
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
    <script charset="UTF-8" type="text/javascript" src="/common/js/enterDisable.js"></script>
</body>
</html>
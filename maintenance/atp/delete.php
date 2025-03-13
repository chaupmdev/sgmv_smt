<?php
/**
 * ツアーエリアマスタメンテナンス削除確認画面を表示します。
 * @package    maintenance
 * @subpackage ATP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('atp/Delete');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Atp_Delete();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Atp002Out
 */
$atp002Out = $forms['outForm'];

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
<?php if($atp002Out->honsha_user_flag() === '1'){ ?>
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
<?php if($atp002Out->honsha_user_flag() === '1'){ ?>
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

        <h2 class="page_ttl"><img src="/common/img/cruise/ttl_master_01.png" width="900" height="36" alt="ツアーエリアマスタ設定" /></h2>
        <h3 class="register_ttl">ツアーエリア情報の登録</h3>

        <form action="" method="post">
            <div class="register_area">
<?php if (isset($e) && $e->hasErrorForId('top_conflict')) { ?>
                <p class="red"><?php echo $e->getMessage('top_conflict'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_travel_province_cd')) { ?>
                <p class="red">ツアーエリアコード<?php echo $e->getMessage('top_travel_province_cd'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_travel_province_name')) { ?>
                <p class="red">ツアーエリア名<?php echo $e->getMessage('top_travel_province_name'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_prefecture_ids')) { ?>
                <p class="red">都道府県<?php echo $e->getMessage('top_prefecture_ids'); ?></p>
<?php } ?>
                <ul class="register_item" id="travel_province_item">
                    <li id="travel_province_item_01" class="clearfix">
                        <fieldset>
                            <legend>ツアーエリアコード</legend>
                            <?php echo $atp002Out->travel_province_cd() . PHP_EOL; ?>
                        </fieldset>
                    </li>
                    <li id="travel_province_item_02">
                        <fieldset>
                            <legend>ツアーエリア名</legend>
                            <?php echo $atp002Out->travel_province_name() . PHP_EOL; ?>
                        </fieldset>
                    </li>
                    <li id="travel_province_item_03">
                        <fieldset>
                            <legend class="item_name" id="pref_name">都道府県</legend>
                            <fieldset class="hide travel_province_list">
                                <legend>エリアに含まない</legend>
                                <ul id="unselected">
<?php
    // 呼び出すたびにエンティティ化されるので先に取得しておく
    $tp_prefecture_ids   = $atp002Out->prefecture_ids();
    $tp_prefecture_names = $atp002Out->prefecture_names();
    $tp_selected_cds     = $atp002Out->selected_cds();

    $unselected_html = '';
    $selected_html   = '';
    $count = count($tp_prefecture_ids);
    for ($i = 0; $i < $count;  ++$i) {
        switch ($tp_selected_cds[$i]) {
            case '1':
                $unselected = ' class="hide"';
                $selected   = '';
                break;
            case '2':
                $unselected = ' class="hide"';
                $selected   = ' class="hide"';
                break;
            default:
                $unselected = '';
                $selected   = ' class="hide"';
                break;
        }
        $unselected_html .= '
                                    <li' . $unselected . ' data-prefecture-id="' . $tp_prefecture_ids[$i] . '">' . $tp_prefecture_names[$i] . '</li>';
        $selected_html .= '
                                    <li' . $selected . ' data-prefecture-id="' . $tp_prefecture_ids[$i] . '">' . $tp_prefecture_names[$i] . '</li>';
    }
    echo $unselected_html;
?>
                                </ul>
                            </fieldset>
                            <div class="hide" id="control_btn">
                                <div id="add_btn">
                                    <p>エリアに追加する</p>
                                    <input disabled="disabled" name="add" type="button" value="＞追加" />
                                    <br />
                                    <input disabled="disabled" name="add_all" type="button" value="＞＞全て追加" />
                                </div>
                                <div id="delete_btn">
                                    <p>エリアから削除する</p>
                                    <input disabled="disabled" name="delete" type="button" value="削除＜" />
                                    <br />
                                    <input disabled="disabled" name="delete_all" type="button" value="全て削除＜＜" />
                                </div>
                            </div>
                            <fieldset class="travel_province_list">
                                <legend>エリアに含む</legend>
                                <ul id="selected">
<?php
    echo $selected_html;
?>
                                </ul>
                            </fieldset>
                        </fieldset>
                    </li>
                </ul>
                <input id="travel_province_id" name="travel_province_id" type="hidden" value="<?php echo $atp002Out->travel_province_id(); ?>" />
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
    <script charset="UTF-8" type="text/javascript" src="/common/js/atpDelete.js"></script>
</body>
</html>
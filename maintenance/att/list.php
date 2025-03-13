<?php
/**
 * ツアー発着地マスタ一覧画面を表示します。
 * @package    maintenance
 * @subpackage ATT
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('att/List');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Att_List();
$forms = $view->execute();

/**
 * フォーム
 * @var Sgmov_Form_Att001Out
 */
$att001Out = $forms['outForm'];
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
<?php if($att001Out->honsha_user_flag() === '1'){ ?>
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
<?php if($att001Out->honsha_user_flag() === '1'){ ?>
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
        <h2 class="page_ttl"><img src="/common/img/cruise/ttl_master_05.png" width="900" height="36" alt="ツアー発着地マスタ設定" /></h2>

        <h3 class="list_ttl">ツアー発着地一覧</h3>
        <form action="/att/input" method="post">
            <div class="register_area">
                <p>どの船の出発地・到着地を表示するか選択してください。</p>
                <ul class="register_item" id="travel_terminal_item">
                    <li id="travel_terminal_item_01" class="clearfix">
                        <fieldset>
                            <legend>船名</legend>
                            <select name="travel_agency_cd_sel">
                                <option value="">選択してください</option>
<?php
    echo Sgmov_View_Att_Common::_createPulldown($att001Out->travel_agency_cds(), $att001Out->travel_agency_lbls(), $att001Out->travel_agency_cd_sel());
?>
                            </select>
                        </fieldset>
                    </li>
                    <li id="travel_terminal_item_02">
                        <fieldset>
                            <legend>乗船日</legend>
                            <select name="travel_cd_sel">
                                <option value="">選択してください</option>
<?php
    //echo Sgmov_View_Att_Common::_createPulldown($att001Out->travel_cds(), $att001Out->travel_lbls(), $att001Out->travel_cd_sel());
?>
                            </select>
                        </fieldset>
                    </li>
            </div>
            <p class="add_btn">
                <a data-id="" href="#"><img alt="新規追加" src="/common/img/cruise/btn_new.png" /></a>
            </p>

            <div class="long_table">
                <table class="list_table" id="travel_terminal_table">
                    <colgroup>
                        <col id="code" />
                        <col id="name" />
                        <col id="zip" />
                        <col id="pref" />
                        <col id="address" />
                        <col id="building" />
                        <col id="store_name" />
                        <col id="tel" />
                        <col id="terminal_cd" />
                        <col id="departure_date" />
                        <col id="departure_time" />
                        <col id="arrival_date" />
                        <col id="arrival_time" />
                        <col id="departure_client_cd" />
                        <col id="departure_client_branch_cd" />
                        <col id="arrival_client_cd" />
                        <col id="arrival_client_branch_cd" />
<!-- 一時コメント
                        <col />
-->
                        <col id="edit" />
                        <col id="delete" />
                    </colgroup>
                    <thead>
                        <tr>
                            <th rowspan="2" scope="col">ツアー<br />発着地コード</th>
                            <th rowspan="2" scope="col">ツアー<br />発着地名</th>
                            <th colspan="5" scope="colgroup">住所</th>
                            <th rowspan="2" scope="col">電話番号</th>
                            <th rowspan="2" scope="col">発着区分</th>
                            <th colspan="2" scope="colgroup">出発日時</th>
                            <th colspan="2" scope="colgroup">到着日時</th>
                            <th colspan="2" scope="colgroup">往路 顧客コード</th>
                            <th colspan="2" scope="colgroup">復路 顧客コード</th>
<!-- 一時コメント
                            <th rowspan="2" scope="col">決済選択区分</th>
-->
                            <th rowspan="2" scope="col">詳細・編集</th>
                            <th rowspan="2" scope="col">削除</th>
                        </tr>
                        <tr>
                            <th scope="col">郵便番号</th>
                            <th scope="col">都道府県</th>
                            <th scope="col">住所共通</th>
                            <th scope="col">往路住所</th>
                            <th scope="col">復路住所</th>
                            <th scope="col">出発日</th>
                            <th scope="col">出発時刻</th>
                            <th scope="col">到着日</th>
                            <th scope="col">到着時刻</th>
                            <th scope="col">コード</th>
                            <th scope="col">枝番</th>
                            <th scope="col">コード</th>
                            <th scope="col">枝番</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
    // 表の内容はAjaxで作成するため、初期表示時点では作成不要
    //// 呼び出すたびにエンティティ化されるので先に取得しておく
    //$tt_ids             = $att001Out->travel_terminal_ids();
    //$tt_cds             = $att001Out->travel_terminal_cds();
    //$tt_names           = $att001Out->travel_terminal_names();
    //$tt_departure_dates = $att001Out->departure_dates();
    //$tt_arrival_dates   = $att001Out->arrival_dates();
    //
    //$html = '';
    //$count = count($tt_names);
    //for ($i = 0; $i < $count;  ++$i) {
    //        $html .= '
    //                    <tr data-id="' . $tt_ids[$i] . '">
    //                        <td>' . $tt_cds[$i] . '</td>
    //                        <td>' . $tt_names[$i] . '</td>
    //                        <td class="date">' . $tt_departure_dates[$i] . '</td>
    //                        <td class="date">' . $tt_arrival_dates[$i] . '</td>
    //                        <td><a href="#">▼編集</a></td>
    //                        <td><a data-delete="1" href="#">▼削除</a></td>
    //                    </tr>';
    //}
    //echo $html;
?>
                    </tbody>
                </table>
            </div>

            <p class="add_btn">
                <a data-id="" href="#"><img alt="新規追加" src="/common/img/cruise/btn_new.png" /></a>
            </p>
            <input name="id" type="hidden" value="" />
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
    <script charset="UTF-8" type="text/javascript" src="/common/js/api.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/attList.js"></script>
</body>
</html>
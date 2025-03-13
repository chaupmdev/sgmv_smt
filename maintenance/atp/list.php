<?php
/**
 * ツアーエリアマスタ一覧画面を表示します。
 * @package    maintenance
 * @subpackage ATP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('atp/List');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Atp_List();
$forms = $view->execute();

/**
 * フォーム
 * @var Sgmov_Form_Atp001Out
 */
$atp001Out = $forms['outForm'];
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
<?php if($atp001Out->honsha_user_flag() === '1'){ ?>
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
<?php if($atp001Out->honsha_user_flag() === '1'){ ?>
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
        <h2 class="page_ttl"><img src="/common/img/cruise/ttl_master_01.png" width="900" height="36" alt="ツアーエリアマスタ設定" /></h2>

        <h3 class="list_ttl">ツアーエリア一覧</h3>
        <form action="/atp/input" method="post">
            <p class="add_btn">
                <a data-id="" href="#"><img alt="新規追加" src="/common/img/cruise/btn_new.png" /></a>
            </p>

            <table class="list_table" id="travel_province_table">
                <colgroup>
                    <col id="code" />
                    <col id="name" />
                    <col id="prefecture" />
                    <col id="edit" />
                    <col id="delete" />
                </colgroup>
                <thead>
                    <tr>
                        <th scope="col">エリアコード</th>
                        <th scope="col">エリア名</th>
                        <th scope="col">都道府県</th>
                        <th scope="col">詳細・編集</th>
                        <th scope="col">削除</th>
                    </tr>
                </thead>
                <tbody>
<?php
    // 呼び出すたびにエンティティ化されるので先に取得しておく
    $tp_ids              = $atp001Out->travel_provinces_ids();
    $tp_cds              = $atp001Out->travel_provinces_cds();
    $tp_names            = $atp001Out->travel_provinces_names();
    $tp_prefecture_names = $atp001Out->prefecture_names();

    $html = '';
    $count = count($tp_names);
    for ($i = 0; $i < $count;  ++$i) {
        $html .= '
                    <tr data-id="' . $tp_ids[$i] . '">
                        <td class="number">' . $tp_cds[$i] . '</td>
                        <td>' . $tp_names[$i] . '</td>
                        <td>' . implode('・', $tp_prefecture_names[$i]) . '</td>
                        <td><a href="#">▼編集</a></td>
                        <td><a data-delete="1" href="#">▼削除</a></td>
                    </tr>';
    }
    echo $html;
?>
                </tbody>
            </table>

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
    <script charset="UTF-8" type="text/javascript" src="/common/js/atpList.js"></script>
</body>
</html>
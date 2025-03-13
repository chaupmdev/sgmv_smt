<?php
/**
 * コメントマスタ一覧画面を表示します。
 * @package    maintenance
 * @subpackage CMM
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('cmm/List');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Cmm_List();
$forms = $view->execute();

/**
 * フォーム
 * @var Sgmov_Form_Cmm001Out
 */
$cmm001Out = $forms['outForm'];

$formAction = '';
if($cmm001Out->sp_list_kind() === Sgmov_View_Cmm_Common::SP_LIST_KIND_COMMENTS){
    $formAction = 'comments';
}else if($cmm001Out->sp_list_kind() === Sgmov_View_Cmm_Common::SP_LIST_KIND_ATTENTION){
    $formAction = 'attention';
}
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
<?php if ($cmm001Out->honsha_user_flag() === '1') { ?>
                            <a href="/acf/input"><img src="/common/img/nav_global02.gif" alt="料金マスタ設定" width="131" height="41" /></a>
<?php } else { ?>
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
<?php if ($cmm001Out->honsha_user_flag() === '1') { ?>
                            <a href="/aoc/list/"><img src="/common/img/nav_global05.gif" alt="他社連携キャンペーン設定" width="242" height="41" /></a>
<?php } else { ?>
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
<?php if ($cmm001Out->sp_list_kind() === Sgmov_View_Cmm_Common::SP_LIST_KIND_COMMENTS) { ?>
        <h2 class="page_ttl"><img src="/common/img/cruise/ttl_master_07.png" width="900" height="36" alt="お客様の声コメント設定" /></h2>
        <h3 class="list_ttl">お客様の声コメント一覧</h3>
<?php } elseif ($cmm001Out->sp_list_kind() === Sgmov_View_Cmm_Common::SP_LIST_KIND_ATTENTION) { ?>
        <h2 class="page_ttl"><img src="/common/img/cruise/ttl_master_08.png" width="900" height="36" alt="この子に注目コメント設定" /></h2>
        <h3 class="list_ttl">この子に注目コメント一覧</h3>
<?php } ?>
        <form action="/cmm/input/<?php echo $formAction; ?>" method="post">
            <p class="add_btn">
                <a data-id="" href="#"><img alt="新規追加" src="/common/img/cruise/btn_new.png" /></a>
            </p>

            <table class="list_table" id="comment_table">
                <colgroup>
                    <col id="picture_1" />
                    <col id="picture_2" />
<?php if ($cmm001Out->sp_list_kind() === Sgmov_View_Cmm_Common::SP_LIST_KIND_COMMENTS) { ?>
                    <col id="title" />
<?php } elseif ($cmm001Out->sp_list_kind() === Sgmov_View_Cmm_Common::SP_LIST_KIND_ATTENTION) { ?>
                    <col id="office" />
<?php } ?>
                    <col id="name" />
                    <col id="post_start_date" />
                    <col id="post_end_date" />
                    <col id="edit" />
                    <col id="delete" />
                </colgroup>
                <thead>
                    <tr>
                        <th scope="col">写真[1]</th>
                        <th scope="col">写真[2]</th>
<?php if ($cmm001Out->sp_list_kind() === Sgmov_View_Cmm_Common::SP_LIST_KIND_COMMENTS) { ?>
                        <th scope="col">タイトル</th>
<?php } elseif ($cmm001Out->sp_list_kind() === Sgmov_View_Cmm_Common::SP_LIST_KIND_ATTENTION) { ?>
                        <th scope="col">営業所</th>
<?php } ?>
                        <th scope="col">氏名</th>
                        <th scope="col">掲載開始日</th>
                        <th scope="col">掲載終了日</th>
                        <th scope="col">詳細・編集</th>
                        <th scope="col">削除</th>
                    </tr>
                </thead>
                <tbody>
<?php
    // 呼び出すたびにエンティティ化されるので先に取得しておく
    $cmm_ids         = $cmm001Out->comment_ids();
    $cmm_flgs        = $cmm001Out->comment_flgs();
    $cmm_titles      = $cmm001Out->comment_titles();
    $cmm_addresses   = $cmm001Out->comment_addresses();
    $cmm_names       = $cmm001Out->comment_names();
    $cmm_offices     = $cmm001Out->comment_offices();
    $cmm_texts       = $cmm001Out->comment_texts();
    $cmm_start_dates = $cmm001Out->comment_start_dates();
    $cmm_end_dates   = $cmm001Out->comment_end_dates();
    $center_names    = $cmm001Out->center_names();

    $html = '';
    $count = count($cmm_ids);
    for ($i = 0; $i < $count; ++$i) {
        $comment_file_1 = '/common/img/cmm/no_image.png';
        $comment_file_2 = '/common/img/cmm/no_image.png';

        $filePath = '../../maintenance/common/img/cmm/files/';
        $filePath2 = '/common/img/cmm/files/';
        $fileNames = array($cmm_ids[$i] . '_1.jpg', $cmm_ids[$i] . '_2.jpg');
        if (is_readable($filePath . $fileNames[0])) {
            $comment_file_1 = $filePath2 . $fileNames[0];
        }
        if (is_readable($filePath . $fileNames[1])) {
            $comment_file_2 = $filePath2 . $fileNames[1];
        }

        $html .= '
                    <tr data-id="' . $cmm_ids[$i] . '">
                        <td><img alt="" src="' . $comment_file_1 . '" width="100" /></td>
                        <td><img alt="" src="' . $comment_file_2 . '" width="100" /></td>';
        if ($cmm001Out->sp_list_kind() === Sgmov_View_Cmm_Common::SP_LIST_KIND_COMMENTS) {
            $html .= '
                        <td>' . $cmm_titles[$i] . '</td>';
        } elseif ($cmm001Out->sp_list_kind() === Sgmov_View_Cmm_Common::SP_LIST_KIND_ATTENTION) {
            $html .= '
                        <td>' . $center_names[$i] . '</td>';
        }
        $html .= '
                        <td>' . $cmm_names[$i] . '</td>
                        <td class="date">' . $cmm_start_dates[$i] . '</td>
                        <td class="date">' . $cmm_end_dates[$i] . '</td>
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
            <input id="cmm_list_king" name="cmm_list_king" type="hidden" value="<?php echo $cmm001Out->sp_list_kind(); ?>" />
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
    <script charset="UTF-8" type="text/javascript" src="/common/js/cmmList.js"></script>
</body>
</html>
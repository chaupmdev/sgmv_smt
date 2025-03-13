<?php
/**
 * メールアドレス一覧を表示する
 * これら開発側利用システムは共通モジュールを使わずに構築しています。
 * @package    maintenance
 * @subpackage sgs_setting
 * @author     M.Kokawa(SGS)
 * @copyright  2010- SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
 /**#@-*/
require_once (dirname(__FILE__) . '/function.php');

/**
 * 拠点を取得する(初期表示)
 *
 */
// DB接続
//$con = PgDBConnect(DB_HOST,DB_PORT,DB_NAME,DB_ADMIN_USER,DB_ADMIN_PSWD);
$con = PgDBConnect('db-hosting1', '5432', 'moving_db_test', 'mov_admin', '');


//拠点とメールアドレスの配列取得
$sql = GetMailsAndCenters($_GET['id']);

//クエリ実行
$result = ExecuteQuery($sql, $con);
$ids = array();
$centerids = array();
$centernames = array();
$forms = array();
$mails = array();
$setkbns = array();
for ($i = 0; $i < pg_num_rows($result); $i++) {
    $row = pg_fetch_array($result, $i);
    $ids[] = $row['id'];
    $centerids[] = $row['center_id'];
    $centernames[] = $row['name'];
    $mails[] = $row['mail'];
    $setkbns[] = $row['set_kbn'];
}
print_r($centernames);exit;
//DB切断
$res = PgDBClose($con);

?>
 <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="Content-Style-Type" content="text/css">
        <meta http-equiv="Content-Script-Type" content="text/javascript">
        <title>メールアドレス変更 | ＳＧシステム専用 | 佐川引越センター株式会社＜ＳＧホールディングスグループ＞</title>
        <meta name="author" content="Sagawa Moving Center Co.,Ltd">
        <meta name="copyright" content="Sagawa Moving Center Co.,Ltd All rights reserved.">
        <meta NAME="ROBOTS" CONTENT="NOINDEX,NOFOLLOW,NOARCHIVE">
        <link href="/common/css/top_top.css" rel="stylesheet" type="text/css" media="screen,tv,projection,print">
        <link href="/common/css/top_print.css" rel="stylesheet" type="text/css" media="print">
        <link href="/common/css/top_main.css" rel="stylesheet" type="text/css" media="screen,tv,projection,print">
        </script>
    </head>
    <body id="menuCat">
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
                    <h1><a href="/"><img src="/common/img/ttl_sgmoving-logo.gif" alt="佐川引越センター" width="118" height="40"></a></h1>
                    <p class="sgh">
                        <a href="http://www.sg-hldgs.co.jp/" target="_blank"><img src="/common/img/pct_sgh-logo.gif" alt="ＳＧホールディングス" width="41" height="29"></a>
                    </p>
                </div><!-- /#sghHeader --><!-- ▲／SGH共通ヘッダー end -->
            </div><!-- /#header -->
            <div id="topWrap">
                <div class="helpNav">
                    <p>
                        <a id="contentTop" name="contentTop"></a>ここから本文です
                    </p>
                </div>
                <div id="mainbox">
                    <table width="640">
                        <tr>
                            <td colspan="3">
<?php
    //初期表示
    if (count($names) > 0) {
        $html = '<p>メールアドレスを変更したい拠点をクリックしてください。</p>';
        $count = count($names);
        for ($i = 0; $i < $count; ++$i) {
            $html .= '<div><a href="./index.php?center=' . $ids[$i] . '">';
            $html .= $names[$i];
            $html .= '</a></div>';
        }
        echo $html;
    }
?>
                            </td>
                        </tr>
<?php
    //センター指定、メールアドレス変更時
    $count = count($form_names);
    for ($i = 0; $i < $count; ++$i) {

?>
                        <tr>
                            <td colspan="3">
                                <?php echo $form_names[$i]; ?>

                            </td>
                        </tr>
                        <tr>
                            <form actoin ="" method="post">
                                <td>
                                    &nbsp;
                                </td>
                                <td>
                                    <table class="box_s">
                                        <tr class="sp">
                                            <td>
                                                <input type="text" value="aaa@aaa.aaaa.aa">
                                            </td>
                                            <td>
                                                <input type="submit" name="更新">
                                            </td>
                                        </tr>
                                        <tr class="sp">
                                            <td>
                                                <input type="text" value="aaa@aaa.aaaa.aa">
                                            </td>
                                            <td>
                                                <input type="submit" name="更新">
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <img src="/common/img/spacer.gif" width="10" height="1" alt="">
                                </td>
                            </form>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <img src="/common/img/spacer.gif" width="1" height="10" alt="">
                            </td>
                        </tr>
<?php
    }
?>
                        <tr>
                            <td colspan="3">
                                <img src="/common/img/spacer.gif" width="1" height="10" alt="">
                            </td>
                        </tr>
                    </table>
                </div><!-- /#mainbox -->
            </div><!-- /#topWrap -->
            <div id="footer">
                <address>
                    <img src="/common/img/txt_copyright.gif" alt="&copy; Sagawa Moving Center Co.,Ltd. All Rights Reserved.">
                </address>
            </div><!-- /#footer -->
        </div><!-- /#wrapper -->
    </body>
</html>
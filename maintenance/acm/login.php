<?php
/**
 * ログインチェックと、ログイン済みの場合はメニュー表示処理、未ログインの場合はログイン画面を表示します。
 * @package    maintenance
 * @subpackage ACM
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('acm/Login');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Acm_Login();
$forms = $view->execute();

/**
 * フォーム
 * @var Sgmov_Form_Acm001Out
 */
$acm001Out = $forms['outForm'];

/**
 * エラーフォーム
 * @var Sgmov_Form_Error
 */
$e = $forms['errorForm'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Content-Style-Type" content="text/css">
    <meta http-equiv="Content-Script-Type" content="text/javascript">
    <title>ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
    <meta name="author" content="SG MOVING Co.,Ltd">
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved.">
    <link href="/common/css/top_top.css" rel="stylesheet" type="text/css" media="screen,tv,projection,print">
    <link href="/common/css/top_print.css" rel="stylesheet" type="text/css" media="print">
    <link href="/common/css/top_main.css" rel="stylesheet" type="text/css" media="screen,tv,projection,print">
    <!--[if lt IE 9]>
    <script charset="UTF-8" type="text/javascript" src="/common/js/jquery-1.12.4.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/lodash-compat.min.js"></script>
    <![endif]-->
    <!--[if gte IE 9]><!-->
    <script charset="UTF-8" type="text/javascript" src="/common/js/jquery-3.1.1.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/lodash.min.js"></script>
    <!--<![endif]-->
    <script type="text/javascript" src="/common/js/enterDisable.js"></script>
</head>
<body id="topCat">

<div class="helpNav">
<p><a id="pageTop" name="pageTop"></a>このページの先頭です</p>
<p><a href="#contentTop">メニューを飛ばして本文を読む</a></p>
</div>

<div id="wrapper">
<div id="header_b">
<!-- ▼SGH共通ヘッダー start -->
<div id="sghHeader">
<h1><a href="/"><img src="/common/img/ttl_sgmoving-logo.gif" alt="ＳＧムービング" width="118" height="40"></a></h1>
<p class="sgh"><a href="http://www.sg-hldgs.co.jp/" target="_blank"><img src="/common/img/pct_sgh-logo.gif" alt="ＳＧホールディングス" width="41" height="29"></a></p>
</div><!-- /#sghHeader -->
<!-- ▲／SGH共通ヘッダー end -->
<!-- ▼グローバルナビ start -->
<!-- ▲／グローバルナビ end -->
</div><!-- /#header -->

<div id="topWrap">

<div class="helpNav">
<p><a id="contentTop" name="contentTop"></a>ここから本文です</p>
</div>

<div id="mainbox">
<form method="post" action="">
<table width="640">
    <tr>
        <td colspan="3" style="text-align: left;"><h2><img src="/common/img/acm/ttl_login_01.gif" alt="ログイン"></h2></td>
    </tr>
    <tr>
        <td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
    </tr>
    <?php if ($e->hasError()) { ?>
        <?php if ($e->hasErrorForId('top')) { ?>
            <tr>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                <td width="620" align="left" class="red"><?php echo $e->getMessage('top'); ?></td>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
            </tr>
        <?php } ?>
        <?php if ($e->hasErrorForId('top_user_account')) { ?>
            <tr>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                <td width="620" align="left" class="red">ユーザーID<?php echo $e->getMessage('top_user_account'); ?></td>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
            </tr>
        <?php } ?>
        <?php if ($e->hasErrorForId('top_pass')) { ?>
            <tr>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                <td width="620" align="left" class="red">パスワード<?php echo $e->getMessage('top_pass'); ?></td>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
            </tr>
        <?php } ?>
        <tr>
            <td colspan="3"><img src="/common/img/spacer.gif" alt="" width="1" height="10"></td>
        </tr>
    <?php } ?>
    <tr>
        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
        <td>
            <table class="box_s">
                <tr>
                    <td>
                        <table>
                            <tr class="sp">
                                <td class="bg_blue" style="white-space: nowrap">ユーザーID</td>
                                <td style="text-align:left;">
                                    <input type="text" size="50" name="user_account" value="<?php echo $acm001Out->user_account()?>" style="ime-mode: disabled;width:400px;">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
                            </tr>
                            <tr class="sp">
                                <td class="bg_blue">パスワード</td>
                                <td style="text-align:left;">
                                    <input type="password" size="50" name="pass" style="ime-mode: disabled;width:400px;">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
                            </tr>
                            <tr>
                                <td colspan="2"><input type="image" src="/common/img/acm/btn_login_01.gif" alt="ログイン" name="login_btn" value=""></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
    </tr>
</table>
</form>
</div><!-- /#mainbox -->
</div><!-- /#topWrap -->

<div id="footer">
<address><img src="/common/img/txt_copyright.gif" alt="&copy; SG Moving Co.,Ltd. All Rights Reserved."></address>
</div><!-- /#footer -->

</div><!-- /#wrapper -->
</body>
</html>
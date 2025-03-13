<?php
/**
 * メンテナンス画面用アプリケーションエラーページです。
 * @package    Error
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

require_once dirname(__FILE__) . '/../form/Acm002Out.php';


if(isset($forms['outForm']) == false || get_class($forms['outForm']) != 'sgmov_form_acm002out') {
    $loginService = new Sgmov_Service_Login();

    $acm002Out = new Sgmov_Form_Acm002Out();
    $acm002Out->raw_honsha_user_flag = $loginService->getHonshaUserFlag();
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<title>エラー│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
<meta name="author" content="SG MOVING Co.,Ltd">
<meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved.">
<link href="/common/css/top_top.css" rel="stylesheet" type="text/css" media="screen,tv,projection,print">
<link href="/common/css/top_print.css" rel="stylesheet" type="text/css" media="print">
<link href="/common/css/top_main.css" rel="stylesheet" type="text/css" media="screen,tv,projection,print">
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
<h1><a href="/"><img src="/common/img/ttl_sgmoving-logo.gif" alt="ＳＧムービング" width="118" height="40"></a></h1>
<p class="sgh"><a href="http://www.sg-hldgs.co.jp/" target="_blank"><img src="/common/img/pct_sgh-logo.gif" alt="ＳＧホールディングス" width="41" height="29"></a></p>
</div><!-- /#sghHeader -->
<!-- ▲／SGH共通ヘッダー end -->
<!-- ▼グローバルナビ start -->
<dl id="globalNav">
<dt>サイト内総合メニュー</dt>
<dd>
<ul>
<li class="nav01"><a href="/acm/menu"><img src="/common/img/nav_global01.gif" alt="メニュー" width="91" height="41"></a></li>
<?php if($acm002Out->honsha_user_flag() === '1'){ ?>
  <li class="nav02"><a href="/acf/input"><img src="/common/img/nav_global02.gif" alt="料金マスタ設定" width="131" height="41"></a></li>
<?php }else{ ?>
  <li class="nav02"><img src="/common/img/nav_global02_off.gif" alt="料金マスタ設定" width="131" height="41"></li>
<?php } ?>
<li class="nav03"><a href="/asp/list/extra"><img src="/common/img/nav_global03.gif" alt="閑散・繁忙期料金設定" width="169" height="41"></a></li>
<li class="nav04"><a href="/asp/list/campaign"><img src="/common/img/nav_global04.gif" alt="キャンペーン特価設定" width="168" height="41"></a></li>
<?php if ($acm002Out->honsha_user_flag() === '1') { ?>
<li class="nav05"><a href="/aoc/list"><img src="/common/img/nav_global05.gif" alt="他社連携キャンペーン設定" width="242" height="41"></a></li>
<?php } else { ?>
<li class="nav05"><img src="/common/img/nav_global05_off.gif" alt="他社連携キャンペーン設定" width="242" height="41"></li>
<?php
 } 
?><li class="nav06"><a href="/acm/logout"><img src="/common/img/nav_global06.gif" alt="ログアウト" width="99" height="41"></a></li>
</ul>
</dd>
</dl><!-- /#globalNav -->
<!-- ▲／グローバルナビ end -->
</div><!-- /#header -->

<div id="topWrap">

<div class="helpNav">
<p><a id="contentTop" name="contentTop"></a>ここから本文です</p>
</div>

<div id="mainbox">
<table width="640">
    <tr>
        <td><?php echo 'メンテナンス画面エラー<br />';
                if (isset($msg)) {
                echo $msg;
                }
            ?>
        </td>
    </tr>
</table>
</div><!-- /#mainbox -->
</div><!-- /#topWrap -->

<div id="footer">
<address><img src="/common/img/txt_copyright.gif" alt="&copy; SG Moving Co.,Ltd. All Rights Reserved."></address>
</div><!-- /#footer -->

</div><!-- /#wrapper -->
</body>
</html>

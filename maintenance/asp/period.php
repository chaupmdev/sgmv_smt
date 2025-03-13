<?php
/**
 * 期間カレンダー画面を表示します。
 * @package    maintenance
 * @subpackage ASP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('asp/Period');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Asp_Period();
$forms = $view->execute();

/**
 * フォーム
 * @var Sgmov_Form_Asp013Out
 */
$asp013Out = $forms['outForm'];
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
<script type="text/javascript" src="/common/js/jquery.js"></script>
<script type="text/javascript" src="/common/js/enterDisable.js"></script>
<script type="text/javascript">
<!--
    $(function() {
        // 次のページリンククリック時
        $("#next_page_link").click(function(){
            $("#prev_table").hide();
            $("#next_table").show();
            $(this).hide();
            $("#prev_page_link").show();
        });

        // 前のページリンククリック時
        $("#prev_page_link").click(function(){
            $("#next_table").hide();
            $("#prev_table").show();
            $(this).hide();
            $("#next_page_link").show();
        });
    });
-->
</script>
</head>

<body>

<div class="helpNav">
<p><a id="pageTop" name="pageTop"></a>このページの先頭です</p>
<p><a href="#contentTop">メニューを飛ばして本文を読む</a></p>
</div>

<div id="wrapper">
<div id="header_login">
<!-- ▼SGH共通ヘッダー start -->
<div id="sghHeader">
<h1><a href="/"><img src="/common/img/ttl_sgmoving-logo.gif" alt="ＳＧムービング" width="118" height="40"></a></h1>
<p class="sgh"><a href="http://www.sg-hldgs.co.jp/" target="_blank"><img src="/common/img/pct_sgh-logo.gif" alt="ＳＧホールディングス" width="41" height="29"></a></p>
<!-- /#sghHeader -->
</div>
<!-- ▲／SGH共通ヘッダー end -->
<!-- ▼グローバルナビ start -->
<!-- ▲／グローバルナビ end -->
<!-- /#header -->
</div>

<div id="topWrap">

<div class="helpNav">
<p><a id="contentTop" name="contentTop"></a>ここから本文です</p>
</div>
<table width="900">
	<tr>
		<td colspan="3"><h2><img src="/common/img/asp/ttl_price_05.gif" alt="設定期間の確認"></h2></td>
	</tr>
    <tr>
        <td colspan="3">元の画面が別の画面に移動すると消えます。</td>
    </tr>
    <tr>
        <td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
    </tr>
	<tr>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
		<td>
			<table width="880">
				<tr>
					<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                    <td width="430" align="left"><a href="javascript:void(0);" id="prev_page_link" style="display:none;">←前のページ</a></td>
                    <td width="430" align="right"><a href="javascript:void(0);" id="next_page_link">次のページ→</a></td>
					<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
				</tr>
			</table>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
	</tr>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" alt="" width="1" height="10"></td>
	</tr>
	<tr>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
		<td>
<?php
            // 呼び出すたびにエンティティ化されるので先に取得しておく
            $days = $asp013Out->days();
            $holiday_flags = $asp013Out->holiday_flags();
            $weekday_cds = $asp013Out->weekday_cds();
            $check_show_flags = $asp013Out->check_show_flags();
            $sel_days = $asp013Out->sel_days();

            $weekdays = array('日','月','火','水','木','金','土');

            $count = count($days);
            // 前半7ヶ月分はデフォルトで表示
            // 後半6ヶ月分(5ヶ月分)はデフォルトで非表示
?>
            <table width="880" id="prev_table"><tr>
                <td><img src='/common/img/spacer.gif' width='15' height='1' alt=''></td>
<?php
                // 月ループ
                for($i=0; $i<7; $i++){
                    $ymd = explode('-', $days[$i][0]);
                    $year = $ymd[0];
                    $month = $ymd[1];
?>
                    <td><img src='/common/img/spacer.gif' width='10' height='1' alt=''></td>
                    <td valign='top'>
                        <table width='110' class='calendar'>
                            <tr><th colspan='2'><?php echo $year;?>年<?php echo $month;?>月</th></tr>
<?php
                    // 日ループ
                    for($j=0; $j<31; $j++){
                        if(!isset($days[$i][$j])){
                            // 日付のないセル
?>
                            <tr><th>&nbsp;</th><td>&nbsp;</td></tr>
<?php
                        }else{
                            $ymd = explode('-', $days[$i][$j]);
                            $day = $ymd[2];

                            $weekday_class = '';
                            if($holiday_flags[$i][$j] || $weekday_cds[$i][$j] === '0' ){
                                $weekday_class = "class='red'";
                            }else if($weekday_cds[$i][$j] === '6' ){
                                $weekday_class = "class='blue'";
                            }

                            $checked = '';
                            if(in_array($days[$i][$j], $sel_days)){
                                $checked = 'checked=\'checked\'';
                            }

                            if($check_show_flags[$i][$j] === '0' || !in_array($days[$i][$j], $sel_days)){
                                // チェックボックスのないセル
?>
                                <tr>
                                    <th><?php echo $day;?>日（<span <?php echo $weekday_class;?>><?php echo $weekdays[$weekday_cds[$i][$j]];?></span>）</th>
                                    <td>&nbsp;</td>
                                </tr>
<?php
                            } else {
                                 // チェックボックスのあるセル
?>
                                <tr>
                                    <th><?php echo $day;?>日（<span <?php echo $weekday_class;?>><?php echo $weekdays[$weekday_cds[$i][$j]];?></span>）</th>
                                    <td><img src='/common/img/asp/icn_check.gif'></td>
                                </tr>
<?php
                            }
                        }
                    } // 日ループ
?>
                        </table>
                    </td>
<?php
                } // 月ループ
?>
                <td><img src="/common/img/spacer.gif" width="25" height="1" alt=""></td>
            </tr></table>

            <table width="880" id="next_table" style="display:none;"><tr>
                <td><img src='/common/img/spacer.gif' width='15' height='1' alt=''></td>
<?php
                // 月ループ
                for($i=7; $i<$count; $i++){
                    $ymd = explode('-', $days[$i][0]);
                    $year = $ymd[0];
                    $month = $ymd[1];
?>
                    <td><img src='/common/img/spacer.gif' width='10' height='1' alt=''></td>
                    <td valign='top'>
                        <table width='110' class='calendar'>
                            <tr><th colspan='2'><?php echo $year;?>年<?php echo $month;?>月</th></tr>
<?php
                    // 日ループ
                    for($j=0; $j<31; $j++){
                        if(!isset($days[$i][$j])){
                            // 日付のないセル
?>
                            <tr><th>&nbsp;</th><td>&nbsp;</td></tr>
<?php
                        }else{
                            $ymd = explode('-', $days[$i][$j]);
                            $day = $ymd[2];

                            $weekday_class = '';
                            if($holiday_flags[$i][$j] || $weekday_cds[$i][$j] === '0' ){
                                $weekday_class = "class='red'";
                            }else if($weekday_cds[$i][$j] === '6' ){
                                $weekday_class = "class='blue'";
                            }

                            $checked = '';
                            if(in_array($days[$i][$j], $sel_days)){
                                $checked = 'checked=\'checked\'';
                            }

                            if($check_show_flags[$i][$j] === '0' || !in_array($days[$i][$j], $sel_days)){
                                // チェックボックスのないセル
?>
                                <tr>
                                    <th><?php echo $day;?>日（<span <?php echo $weekday_class;?>><?php echo $weekdays[$weekday_cds[$i][$j]];?></span>）</th>
                                    <td>&nbsp;</td>
                                </tr>
<?php
                            } else {
                                 // チェックボックスのあるセル
?>
                                <tr>
                                    <th><?php echo $day;?>日（<span <?php echo $weekday_class;?>><?php echo $weekdays[$weekday_cds[$i][$j]];?></span>）</th>
                                    <td><img src='/common/img/asp/icn_check.gif'></td>
                                </tr>
<?php
                            }
                        }
                    } // 日ループ
?>
                        </table>
                    </td>
<?php
                } // 月ループ

                // 残り領域を埋める
                if($count === 12){
                    // 後半が5ヶ月分の場合
?>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                    <td><img src="/common/img/spacer.gif" width="110" height="1" alt=""></td>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                    <td><img src="/common/img/spacer.gif" width="110" height="1" alt=""></td>
                    <td><img src="/common/img/spacer.gif" width="25" height="1" alt=""></td>
<?php
                }else{
                    // 後半が6ヶ月分の場合
?>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                    <td><img src="/common/img/spacer.gif" width="110" height="1" alt=""></td>
                    <td><img src="/common/img/spacer.gif" width="25" height="1" alt=""></td>
<?php
                }
?>
            </tr></table>
        </td>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
	</tr>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
	</tr>
	<tr>
		<td colspan="3" align="center"><a href="#" onClick="window.close();">×　閉じる</a></td>
	</tr>
</table>
<!-- /#topWrap --></div>

<div id="footer">

<address><img src="/common/img/txt_copyright.gif" alt="&copy; SG Moving Co.,Ltd. All Rights Reserved."></address>
<!-- /#footer --></div>

<!-- /#wrapper --></div>
</body>
</html>

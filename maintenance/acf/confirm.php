<?php
/**
 * 料金マスタメンテナンス確認画面を表示します。
 * @package    maintenance
 * @subpackage ACF
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('acf/Confirm');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Acf_Confirm();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Acf003Out
 */
$acf003Out = $forms['outForm'];

// 呼び出すたびにエンティティ化されるので先に取得しておく
$to_area_lbls = $acf003Out->to_area_lbls();
$base_prices = $acf003Out->base_prices();
$max_prices = $acf003Out->max_prices();
$min_prices = $acf003Out->min_prices();
$base_price_edit_flags = $acf003Out->base_price_edit_flags();
$max_price_edit_flags = $acf003Out->max_price_edit_flags();
$min_price_edit_flags = $acf003Out->min_price_edit_flags();
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
<script type="text/javascript" src="/common/js/enterDisable.js"></script>
</head>

<body id="masterCat">

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
<?php if($acf003Out->honsha_user_flag() === '1'){ ?>
  <li class="nav02"><a href="/acf/input"><img src="/common/img/nav_global02.gif" alt="料金マスタ設定" width="131" height="41"></a></li>
<?php }else{ ?>
  <li class="nav02"><img src="/common/img/nav_global02_off.gif" alt="料金マスタ設定" width="131" height="41"></li>
<?php } ?>
<li class="nav03"><a href="/asp/list/extra"><img src="/common/img/nav_global03.gif" alt="閑散・繁忙期料金設定" width="169" height="41"></a></li>
<li class="nav04"><a href="/asp/list/campaign"><img src="/common/img/nav_global04.gif" alt="キャンペーン特価設定" width="168" height="41"></a></li>
<?php if($acf003Out->honsha_user_flag() === '1'){ ?>
<li class="nav05"><a href="/aoc/list/"><img src="/common/img/nav_global05.gif" alt="他社連携キャンペーン設定" width="242" height="41"></a></li>
<?php }else{ ?>
<li class="nav05"><img src="/common/img/nav_global05_off.gif" alt="他社連携キャンペーン設定" width="242" height="41"></li>
<?php } ?>
<li class="nav06"><a href="/acm/logout"><img src="/common/img/nav_global06.gif" alt="ログアウト" width="99" height="41"></a></li>
</ul>
</dd>
</dl><!-- /#globalNav -->
<!-- ▲／グローバルナビ end -->
</div><!-- /#header -->

</div><!-- /#wrapper -->

<div id="topWrap">

<div class="helpNav">
<p><a id="contentTop" name="contentTop"></a>ここから本文です</p>
</div>

<table width="900">
	<tr>
		<td colspan="3"><h2><img src="/common/img/acf/ttl_master_02.gif" alt="料金マスタ設定　内容の確認"></h2></td>
	</tr>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
	</tr>
	<tr>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
		<td>
			<table class="inner" width="880">
				<tr>
					<td colspan="3">入力内容をご確認のうえ、よろしければ「登録する」ボタンを押してください。</td>
				</tr>
				<tr>
					<td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
				</tr>
				<tr>
					<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
					<td>
						<table class="box_s">
							<tr class="sp">
								<td class="bg_blue">コース・プラン</td>
								<td><?php echo $acf003Out->course_plan() ?></td>
							</tr>
							<tr>
								<td colspan="2"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
							</tr>
							<tr class="sp">
								<td class="bg_blue">出発エリア</td>
								<td><?php echo $acf003Out->from_area() ?></td>
							</tr>
						</table>
					</td>
					<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
				</tr>
			</table>
		</td>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
	</tr>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
	</tr>
	<tr>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
		<td align="right">
			<table>
				<tr>
					<td><img src="/common/img/spacer.gif" width="7" height="1" alt=""></td>
					<td><img src="/common/img/spacer.gif" width="6" height="1" alt=""></td>
					<td>※</td><td><div class="example">&nbsp;</div></td><td>…変更された料金</td>
					<td><img src="/common/img/spacer.gif" width="6" height="1" alt=""></td>
					<td><img src="/common/img/spacer.gif" width="7" height="1" alt=""></td>
				</tr>
			</table>
		</td>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
	</tr>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
	</tr>
	<tr>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
		<td>
			<table class="inner" width="880">
				<tr>
					<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
					<td>
						<table width="425" class="bdr">
							<tr>
								<th width="121" rowspan="2">到着エリア</th>
								<th width="135" rowspan="2">基本料金（円）</th>
								<th width="135">上限料金（円）</th>
							</tr>
							<tr>
								<th>下限料金（円）</th>
							</tr>
                            <?php
                                // 2で割って端数を切り上げ
                                $leftCount = ceil(count($to_area_lbls) / 2);
                                for($i=0;$i<$leftCount;$i++){
                                    echo "<tr>\n";
                                    echo "    <td rowspan='2'>{$to_area_lbls[$i]}</td>\n";
                                    if($base_price_edit_flags[$i] === '1'){
                                        echo "<td rowspan='2' class='price bg_red'>{$base_prices[$i]}</td>\n";
                                    }else{
                                        echo "<td rowspan='2' class='price'>{$base_prices[$i]}</td>\n";
                                    }
                                    if($max_price_edit_flags[$i] === '1'){
                                        echo "    <td class='price bg_red'>{$max_prices[$i]}</td>\n";
                                    }else{
                                        echo "    <td class='price'>{$max_prices[$i]}</td>\n";
                                    }
                                    echo "</tr>\n";
                                    echo "<tr>\n";
                                    if($min_price_edit_flags[$i] === '1'){
                                        echo "    <td class='price bg_red'>{$min_prices[$i]}</td>\n";
                                    }else{
                                        echo "    <td class='price'>{$min_prices[$i]}</td>\n";
                                    }
                                    echo "</tr>\n";
                                }
                            ?>
						</table>
					</td>
					<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
					<td>
						<table width="425" class="bdr">
							<tr>
								<th width="121" rowspan="2">到着エリア</th>
								<th width="135" rowspan="2">基本料金（円）</th>
								<th width="135">上限料金（円）</th>
							</tr>
							<tr>
								<th>下限料金（円）</th>
							</tr>
                            <?php
                                $areaCount = count($to_area_lbls);
                                for($i=$leftCount;$i<$areaCount;$i++){
                                    echo "<tr>\n";
                                    echo "    <td rowspan='2'>{$to_area_lbls[$i]}</td>\n";
                                    if($base_price_edit_flags[$i] === '1'){
                                        echo "<td rowspan='2' class='price bg_red'>{$base_prices[$i]}</td>\n";
                                    }else{
                                        echo "<td rowspan='2' class='price'>{$base_prices[$i]}</td>\n";
                                    }
                                    if($max_price_edit_flags[$i] === '1'){
                                        echo "    <td class='price bg_red'>{$max_prices[$i]}</td>\n";
                                    }else{
                                        echo "    <td class='price'>{$max_prices[$i]}</td>\n";
                                    }
                                    echo "</tr>\n";
                                    echo "<tr>\n";
                                    if($min_price_edit_flags[$i] === '1'){
                                        echo "    <td class='price bg_red'>{$min_prices[$i]}</td>\n";
                                    }else{
                                        echo "    <td class='price'>{$min_prices[$i]}</td>\n";
                                    }
                                    echo "</tr>\n";
                                }
                            ?>
						</table>
					</td>
					<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
				</tr>
				<tr>
					<td colspan="5"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
				</tr>
				<tr>
					<td colspan="5" align="center">
						<table>
							<tr>
								<td>
                                    <form method='post' action='/acf/complete'>
    								    <input type="image" src="/common/img/acf/btn_regist.gif" alt="登録する">
                                        <input type='hidden' name='ticket' value='<?php echo $ticket ?>' />
                                    </form>
                                </td>
								<td><img src="/common/img/spacer.gif" width="10" height="1" alt="">
								<td><a href="/acf/input"><img src="/common/img/acf/btn_modification.gif" alt="修正する" /></a></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
	</tr>
</table>
</div><!-- /#topWrap -->

<div id="footer">
<address><img src="/common/img/txt_copyright.gif" alt="&copy; SG Moving Co.,Ltd. All Rights Reserved."></address>
</div><!-- /#footer -->

</body>
</html>

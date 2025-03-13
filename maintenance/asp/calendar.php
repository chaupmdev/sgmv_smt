<?php
/**
 * 料金カレンダー画面を表示します。
 * @package    maintenance
 * @subpackage ASP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('asp/Calendar');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Asp_Calendar();
$forms = $view->execute();

/**
 * フォーム
 * @var Sgmov_Form_Asp012Out
 */
$asp012Out = $forms['outForm'];
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
    <script type="text/javascript" src="/common/js/heightLine.js"></script>
    <script type="text/javascript" src="/common/js/enterDisable.js"></script>
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
		<td colspan="3"><h2><img src="/common/img/asp/ttl_calendar_01.gif" alt="カレンダー表示の確認"></h2></td>
	</tr>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
	</tr>
	<tr>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
		<td>
			<table>
				<tr>
					<td>
						<table width="300" class="line">
							<tr>
								<td class="sp"><?php echo $asp012Out->course(); ?>　<?php echo $asp012Out->plan(); ?><br>
								出発エリア：<?php echo $asp012Out->from_area(); ?><br>
								到着エリア：<?php echo $asp012Out->to_area(); ?></td>
							</tr>
						</table>
					</td>
					<td><img src="/common/img/spacer.gif" width="280" height="1" alt=""></td>
					<td align="right">
						<table width="300" class="dash">
							<tr>
								<td class="sp">
									<table>
    							    <?php if($asp012Out->edit_flag() === '1') { ?>
										<tr>
											<td valign="top"><div class="example2">&nbsp;</div></td>
											<td valign="top"><img src="/common/img/spacer.gif" width="5" height="1" alt=""></td>
											<td valign="top">金額変更となる日</td>
										</tr>
                                    <?php } ?>
										<tr>
											<td valign="top"><img src="/common/img/asp/icn_price.gif"></td>
											<td valign="top"><img src="/common/img/spacer.gif" width="5" height="1" alt=""></td>
											<td valign="top">閑散／繁忙期料金設定あり</td>
										</tr>
										<tr>
											<td valign="top"><img src="/common/img/asp/icn_campaign.gif"></td>
											<td valign="top"><img src="/common/img/spacer.gif" width="5" height="1" alt=""></td>
											<td valign="top">キャンペーン設定あり</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
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
		<td class="ttl"><?php echo $asp012Out->cal_year(); ?>年<?php echo $asp012Out->cal_month(); ?>月の設定料金</td>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
	</tr>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
	</tr>
	<tr>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
		<td>
			<table width="880">
				<tr>
					<td><img src="/common/img/spacer.gif" width="9" height="1" alt=""></td>
					<td valign="top">
						<table width="862" class="cltbl">
							<tr>
								<th width="120">月</th>
								<th width="120">火</th>
								<th width="120">水</th>
								<th width="120">木</th>
								<th width="120">金</th>
								<th width="120"><span class="blue">土</span></th>
								<th width="120"><span class="red">日</span></th>
							</tr>

<?php
    // 呼び出すたびにエンティティ化されるので先に取得しておく
    $cal_days = $asp012Out->cal_days();
    $cal_weekday_flags = $asp012Out->cal_weekday_flags();
    $cal_holiday_flags = $asp012Out->cal_holiday_flags();
    $cal_valid_flags = $asp012Out->cal_valid_flags();
    $cal_extra_flags = $asp012Out->cal_extra_flags();
    $cal_campaign_flags = $asp012Out->cal_campaign_flags();
    $cal_editing_flags = $asp012Out->cal_editing_flags();
    $cal_prices = $asp012Out->cal_prices();
    $cal_sp_names = $asp012Out->cal_sp_names();
    $cal_sp_urls = $asp012Out->cal_sp_urls();

    $year = $asp012Out->cal_year();
    $month = $asp012Out->cal_month();

    $dayCount = count($cal_days);
    for($i=0;$i<$dayCount;$i++){
        if($i % 7 == 0){
            echo "<tr>\n";
        }

        if($cal_valid_flags[$i] === '0'){
            $class = 'class="bg_gray2"';
        }else if($cal_editing_flags[$i] === '1'){
            $class = 'class="bg_yellow"';
        }else{
            $class = '';
        }

        $splits = explode(Sgmov_Service_Calendar::DATE_SEPARATOR, $cal_days[$i]);
        // 週の途中で始まっている(終わっている)場合今月分以外の日付情報も持っているので
        // 今月分だけを取り出す
        if($splits[0] === $year && $splits[1] === $month){
            $day = $splits[2];
        }else{
            $day = '';
        }

        $weekday_class = '';
        if($cal_holiday_flags[$i] || $cal_weekday_flags[$i] === '0' ){
            $weekday_class = "class='red'";
        }else if($cal_weekday_flags[$i] === '6' ){
            $weekday_class = "class='blue'";
        }

        if($cal_valid_flags[$i] === '0'){
            echo "<td width='120' {$class}>\n";
            echo "<div class='heightLine'><span {$weekday_class}>{$day}</span>\n";
            echo "</div>\n";
            echo "<table class='icnbox'><tr>\n";
            echo "<td></td>\n";
            echo "<td></td>\n";
            echo "</tr></table>\n";
            echo "</td>\n";
        }else{
            echo "<td width='120' {$class}>\n";
            echo "<div class='heightLine'><span {$weekday_class}>{$day}</span>\n";

            $spCount = count($cal_sp_names[$i]);
            for($j=0;$j<$spCount;$j++){
                echo "<p><a href='" . $cal_sp_urls[$i][$j] . "' target='_blank'>" . $cal_sp_names[$i][$j] . "</a></p>\n";
            }
            echo "</div>\n";

            $price = "&yen;" . Sgmov_Component_String::number_format($cal_prices[$i]);
            echo "<table class='icnbox'><tr>\n";
            echo "<td>{$price}</td>\n";

            echo "<td>";
            if($cal_extra_flags[$i] === '1'){
                echo "<img src='/common/img/asp/icn_price.gif' class='icn'>";
            }
            if($cal_campaign_flags[$i] === '1'){
                echo "<img src='/common/img/asp/icn_campaign.gif' class='icn'>";
            }
            echo "</td>\n";

            echo "</tr></table>\n";
            echo "</td>\n";
        }
        if($i % 7 == 6){
            echo "</tr>\n";
        }
    }
?>
						</table>
					</td>
					<td><img src="/common/img/spacer.gif" width="9" height="1" alt=""></td>
				</tr>
				<tr>
					<td><img src="/common/img/spacer.gif" width="9" height="1" alt=""></td>
					<td>
						<table width="862">
							<tr>
							    <td align="left">
<?php if($asp012Out->prev_month_link() !== ''){ ?>
								<a href="<?php echo $asp012Out->prev_month_link(); ?>">← 前の月</a>
<?php } ?>
                                </td>
								<td align="right">
<?php if($asp012Out->next_month_link()  !== ''){ ?>
                                    <a href="<?php echo $asp012Out->next_month_link(); ?>">次の月 →</a>
<?php } ?>
                                </td>
							</tr>
						</table>
					</td>
					<td><img src="/common/img/spacer.gif" width="9" height="1" alt=""></td>
			</table>
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

<?php
/**
 * 特価編集個別編集期間入力画面を表示します。
 * @package    maintenance
 * @subpackage ASP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('asp/Input3');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Asp_Input3();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Asp006Out
 */
$asp006Out = $forms['outForm'];

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
<script type="text/javascript" src="/common/js/jquery.js"></script>
<script type="text/javascript" src="/common/js/enterDisable.js"></script>
<script type="text/javascript">
<!--
    $(function() {
        // 一覧に戻るリンククリック時
        $("#back_to_list").click(function(){
            if(!confirm("入力を中止して一覧に戻ります。\編集中の内容は破棄されますがよろしいですか？")){
                // キャンセル
                return false;
            }
        });

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

        // 一括チェックボタンクリック時
        $("#date_all_btn").click(function(){
            // エラーメッセージ表示要素
            var msg_elem = $("#date_all_error_msg");

            // 入力値
            var from_year = $("#from_year").attr("value");
            var from_month = $("#from_month").attr("value");
            var from_day = $("#from_day").attr("value");

            var to_year = $("#to_year").attr("value");
            var to_month = $("#to_month").attr("value");
            var to_day = $("#to_day").attr("value");

            // 未選択項目有り
            if(from_year == '' || from_month == '' ||  from_day == ''
                || to_year == '' || to_month == '' || to_day == ''){
                msg_elem.text("日付を指定してください");
                msg_elem.show();
                return;
            }

            // 日付型の作成
            var fromDate = new Date(from_year, from_month - 1, from_day);
            var toDate = new Date(to_year, to_month - 1, to_day);

            // 日付として正しくない
            if(from_year != fromDate.getFullYear()
                || from_month != fromDate.getMonth() + 1
                || from_day != fromDate.getDate()
                || to_year != toDate.getFullYear()
                || to_month != toDate.getMonth() + 1
                || to_day != toDate.getDate()){
                msg_elem.text("指定された日付は存在しません");
                msg_elem.show();
                return;
            }

            // 開始 > 終了になっている
            if(fromDate > toDate){
                msg_elem.text("日付の指定が間違っています(開始 > 終了)");
                msg_elem.show();
                return;
            }

            // 最初のチェックボックスの日付
            var firstDateSplits = $("input[@name='sel_days[]']:first").attr("id").split("_");
            var firstDate = new Date(firstDateSplits[0], firstDateSplits[1] - 1, firstDateSplits[2]);

            // 最後のチェックボックスの日付
            var lastDateSplits = $("input[@name='sel_days[]']:last").attr("id").split("_");
            var lastDate = new Date(lastDateSplits[0], lastDateSplits[1] - 1, lastDateSplits[2]);

            // 有効範囲にない
            if(fromDate < firstDate || lastDate < toDate){
                var msg = firstDateSplits[0] + "年" + firstDateSplits[1] + "月" + firstDateSplits[2] + "日～"
                    + lastDateSplits[0] + "年" + lastDateSplits[1] + "月" + lastDateSplits[2] + "日"
                    + "の範囲で指定してください";
                msg_elem.text(msg);
                msg_elem.show();
                return;
            }

            // エラーなし
            $("#date_all_error_msg").hide();

            // 曜日フラグ
            var weekdayFlag = new Array(true, true, true, true, true, true, true);
            // 1つも選択されていなければ全てtrue
            if($("input[@id^='weekdayFlag']:checked").length > 0){
                for(var i=0;i<7;i++){
                    if($("#weekdayFlag" + i).attr("checked") == ""){
                        weekdayFlag[i] = false;
                    }
                }
            }

            // 祝日フラグ
            var holidayFlag = $("input[@type='radio']:checked").attr("value");

            // 選択実行
            var msg;
            $("input[@name='sel_days[]']").each(function(){
                var me = $(this)
                var splits = me.attr("id").split("_");
                var myDate = new Date(splits[0], splits[1] - 1, splits[2]);

                // 日付範囲外
                if(myDate < fromDate || toDate < myDate){
                    me.attr("checked", "");
                    return;
                }

                // 祝日
                if(splits[4] == '1'){
                    if(holidayFlag == '1'){
                        // 祝日含む
                        me.attr("checked", "checked");
                        return;
                    } else if(holidayFlag == '2'){
                        // 祝日含まない
                        me.attr("checked", "");
                        return;
                    }
                }

                // 曜日
                if(weekdayFlag[splits[3]]){
                    me.attr("checked", "checked");
                    return;
                }else{
                    me.attr("checked", "");
                    return;
                }
            });
        });
    });
-->
</script>
</head>

<?php if($asp006Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA){ ?>
<body id="priceCat">
<?php } else if($asp006Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN){ ?>
<body id="campaignCat">
<?php } ?>

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
<?php if($asp006Out->honsha_user_flag() === '1'){ ?>
  <li class="nav02"><a href="/acf/input"><img src="/common/img/nav_global02.gif" alt="料金マスタ設定" width="131" height="41"></a></li>
<?php }else{ ?>
  <li class="nav02"><img src="/common/img/nav_global02_off.gif" alt="料金マスタ設定" width="131" height="41"></li>
<?php } ?>
<li class="nav03"><a href="/asp/list/extra"><img src="/common/img/nav_global03.gif" alt="閑散・繁忙期料金設定" width="169" height="41"></a></li>
<li class="nav04"><a href="/asp/list/campaign"><img src="/common/img/nav_global04.gif" alt="キャンペーン特価設定" width="168" height="41"></a></li>
<?php if($asp006Out->honsha_user_flag() === '1'){ ?>
<li class="nav05"><a href="/aoc/list"><img src="/common/img/nav_global05.gif" alt="他社連携キャンペーン設定" width="242" height="41"></a></li>
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

<?php if($asp006Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA){ ?>
    <form method='post' action='/asp/check_input3/extra'>
<?php } else if($asp006Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN){ ?>
    <form method='post' action='/asp/check_input3/campaign'>
<?php } ?>
<table width="900">
<?php if($asp006Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA){ ?>
    <tr>
        <td colspan="3"><h2><img src="/common/img/asp/ttl_price_01.gif" alt="閑散・繁忙期料金設定"></h2></td>
    </tr>
<?php } else if($asp006Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN){ ?>
    <tr>
        <td colspan="3"><h2><img src="/common/img/asp/ttl_campaign_01.gif" alt="キャンペーン設定"></h2></td>
    </tr>
<?php } ?>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
	</tr>
    <tr>
        <td width="10"><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
        <td width="880" align="right"><a id='back_to_list' href="<?php echo $asp006Out->sp_list_url(); ?>">← 一覧に戻る</a></td>
        <td width="10"><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
    </tr>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
	</tr>
	<tr>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
		<td class="ttl">期間を指定してください</td>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
	</tr>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" alt="" width="1" height="10"></td>
	</tr>
	<tr>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
		<td>
			<table>
				<tr>
					<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
					<td width="860" align="left" valign="top">
						<table class="line">
							<tr>
								<td class="sp">一括して期間をチェックする<br>
                                    <span id="date_all_error_msg" style="display:none" class="red"></span>
									<table>
										<tr>
											<td><select id="from_year">
                                                <?php
                                                    $cds = $asp006Out->from_year_cds();
                                                    $lbls = $asp006Out->from_year_lbls();
                                                    for($i = 0;$i<count($cds);$i++){
                                                        $cd = $cds[$i];
                                                        $lbl = $lbls[$i];
                                                        echo "<option value='{$cd}'>{$lbl}</option>\n";
                                                    }
                                                ?>
                                                </select>年
											</td>
											<td><img src="/common/img/spacer.gif" width="5" height="1" alt=""></td>
											<td><select id="from_month">
                                                <?php
                                                    $cds = $asp006Out->from_month_cds();
                                                    $lbls = $asp006Out->from_month_lbls();
                                                    for($i = 0;$i<count($cds);$i++){
                                                        $cd = $cds[$i];
                                                        $lbl = $lbls[$i];
                                                        echo "<option value='{$cd}'>{$lbl}</option>\n";
                                                    }
                                                ?>
												</select>月
											</td>
											<td><img src="/common/img/spacer.gif" width="5" height="1" alt=""></td>
											<td><select id="from_day">
                                                <?php
                                                    $cds = $asp006Out->from_day_cds();
                                                    $lbls = $asp006Out->from_day_lbls();
                                                    for($i = 0;$i<count($cds);$i++){
                                                        $cd = $cds[$i];
                                                        $lbl = $lbls[$i];
                                                        echo "<option value='{$cd}'>{$lbl}</option>\n";
                                                    }
                                                ?>
												</select>日
											</td>
											<td><img src="/common/img/spacer.gif" width="5" height="1" alt=""></td>
											<td>～</td>
											<td><img src="/common/img/spacer.gif" width="5" height="1" alt=""></td>
											<td><select id="to_year">
                                                <?php
                                                    $cds = $asp006Out->to_year_cds();
                                                    $lbls = $asp006Out->to_year_lbls();
                                                    for($i = 0;$i<count($cds);$i++){
                                                        $cd = $cds[$i];
                                                        $lbl = $lbls[$i];
                                                        echo "<option value='{$cd}'>{$lbl}</option>\n";
                                                    }
                                                ?>
												</select>年
											</td>
											<td><img src="/common/img/spacer.gif" width="5" height="1" alt=""></td>
											<td><select id="to_month">
                                                <?php
                                                    $cds = $asp006Out->to_month_cds();
                                                    $lbls = $asp006Out->to_month_lbls();
                                                    for($i = 0;$i<count($cds);$i++){
                                                        $cd = $cds[$i];
                                                        $lbl = $lbls[$i];
                                                        echo "<option value='{$cd}'>{$lbl}</option>\n";
                                                    }
                                                ?>
												</select>月
											</td>
											<td><img src="/common/img/spacer.gif" width="5" height="1" alt=""></td>
											<td><select id="to_day">
                                                <?php
                                                    $cds = $asp006Out->to_day_cds();
                                                    $lbls = $asp006Out->to_day_lbls();
                                                    for($i = 0;$i<count($cds);$i++){
                                                        $cd = $cds[$i];
                                                        $lbl = $lbls[$i];
                                                        echo "<option value='{$cd}'>{$lbl}</option>\n";
                                                    }
                                                ?>
												</select>日
											</td>
											<td><img src="/common/img/spacer.gif" width="5" height="1" alt=""></td>
											<td><a id="date_all_btn" href="javascript:void(0);"><img src="/common/img/asp/btn_checkall.gif" alt="一括でチェックをつける"></a></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td class="sp">
									<table>
										<tr>
											<td><input type="checkbox" id="weekdayFlag1">月</td>
											<td><img src="/common/img/spacer.gif" width="5" height="1" alt=""></td>
											<td><input type="checkbox" id="weekdayFlag2">火</td>
											<td><img src="/common/img/spacer.gif" width="5" height="1" alt=""></td>
											<td><input type="checkbox" id="weekdayFlag3">水</td>
											<td><img src="/common/img/spacer.gif" width="5" height="1" alt=""></td>
											<td><input type="checkbox" id="weekdayFlag4">木</td>
											<td><img src="/common/img/spacer.gif" width="5" height="1" alt=""></td>
											<td><input type="checkbox" id="weekdayFlag5">金</td>
											<td><img src="/common/img/spacer.gif" width="5" height="1" alt=""></td>
											<td class="blue"><input type="checkbox" id="weekdayFlag6">土</td>
											<td><img src="/common/img/spacer.gif" width="5" height="1" alt=""></td>
											<td class="red"><input type="checkbox" id="weekdayFlag0">日</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td class="sp">
									<table>
										<tr>
											<td class="red"><input type="radio" value="0" name="holiday_radio" checked="checked">祝日を考慮しない</td>
											<td><img src="/common/img/spacer.gif" width="5" height="1" alt=""></td>
											<td class="red"><input type="radio" value="1" name="holiday_radio">祝日を含む</td>
											<td><img src="/common/img/spacer.gif" width="5" height="1" alt=""></td>
											<td class="red"><input type="radio" value="2" name="holiday_radio">祝日を含まない</td>
										</tr>
									</table>
								</td>
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
		<td colspan="3"><img src="/common/img/spacer.gif" alt="" width="1" height="10"></td>
	</tr>
    <?php if ($e->hasError()) { ?>
        <?php if ($e->hasErrorForId('top_sel_days')) { ?>
            <tr>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                <td width="880" align="left" class="red">日付<?php echo $e->getMessage('top_sel_days'); ?></td>
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
            $days = $asp006Out->days();
            $holiday_flags = $asp006Out->holiday_flags();
            $weekday_cds = $asp006Out->weekday_cds();
            $check_show_flags = $asp006Out->check_show_flags();
            $sel_days = $asp006Out->sel_days();

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

                            if($check_show_flags[$i][$j] === '0'){
                                // チェックボックスのないセル
?>
                                <tr>
                                    <th><?php echo $day;?>日（<span <?php echo $weekday_class;?>><?php echo $weekdays[$weekday_cds[$i][$j]];?></span>）</th>
                                    <td>&nbsp;</td>
                                </tr>
<?php
                            } else {
                                 // チェックボックスのあるセル
                                 $id = $year . '_' . $month . '_' . $day . '_' . $weekday_cds[$i][$j] . '_' . $holiday_flags[$i][$j];
?>
                                <tr>
                                    <th><?php echo $day;?>日（<span <?php echo $weekday_class;?>><?php echo $weekdays[$weekday_cds[$i][$j]];?></span>）</th>
                                    <td><input id="<?php echo $id; ?>" type='checkbox' name='sel_days[]' value='<?php echo $days[$i][$j]; ?>' <?php echo $checked; ?>></td>
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

                            if($check_show_flags[$i][$j] === '0'){
                                // チェックボックスのないセル
?>
                                <tr>
                                    <th><?php echo $day;?>日（<span <?php echo $weekday_class;?>><?php echo $weekdays[$weekday_cds[$i][$j]];?></span>）</th>
                                    <td>&nbsp;</td>
                                </tr>
<?php
                            } else {
                                 // チェックボックスのあるセル
                                 $id = $year . '_' . $month . '_' . $day . '_' . $weekday_cds[$i][$j] . '_' . $holiday_flags[$i][$j];
?>
                                <tr>
                                    <th><?php echo $day;?>日（<span <?php echo $weekday_class;?>><?php echo $weekdays[$weekday_cds[$i][$j]];?></span>）</th>
                                    <td><input type='checkbox' id="<?php echo $id; ?>" name='sel_days[]' value='<?php echo $days[$i][$j] ?>' <?php echo $checked?>></td>
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
		<td colspan="3"><img src="/common/img/spacer.gif" alt="" width="1" height="10"></td>
	</tr>
	<tr>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
		<td>
			<table>
				<tr>
					<td colspan="3" align="center">
						<table>
                            <tr>
                            <?php if($asp006Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA){ ?>
                                <td><input type="image" src="/common/img/asp/btn_next.gif" alt="次へ"></td>
                                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                                <td><a href="/asp/input2/extra"><img src="/common/img/asp/btn_back.gif" alt="戻る" /></a></td>
                            <?php } else if($asp006Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN){ ?>
                                <td><input type="image" src="/common/img/asp/btn_next.gif" alt="次へ"></td>
                                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                                <td><a href="/asp/input2/campaign"><img src="/common/img/asp/btn_back.gif" alt="戻る" /></a></td>
                            <?php } ?>
                            </tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
	</tr>
</table>
<input type='hidden' name='ticket' value='<?php echo $ticket ?>' />
</form>
<!-- /#topWrap --></div>

<div id="footer">

<address><img src="/common/img/txt_copyright.gif" alt="&copy; SG Moving Co.,Ltd. All Rights Reserved."></address>
<!-- /#footer --></div>

<!-- /#wrapper --></div>
</body>
</html>

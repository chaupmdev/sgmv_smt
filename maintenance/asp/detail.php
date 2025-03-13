<?php
/**
 * 特価詳細画面を表示します。
 * @package    maintenance
 * @subpackage ASP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('asp/Detail');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Asp_Detail();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Asp002Out
 */
$asp002Out = $forms['outForm'];

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
    var winPeriod;
    var winCalendar;

    // 期間カレンダー画面を開きます
    function openPeriodWindow(url){
        winPeriod = window.open(url,"detail_period");
    }

    // カレンダー画面を開きます
    function openCalendarWindow(url){
        winCalendar = window.open(url,"detail_calendar");
    }

    // アクションを設定してサブミット
    function onSubmit(url){
        $("form").attr("action", url);
    }

    // 表示ボタン押下時
    function onClickReadingButton(baseUrl){
        var error = false;

        var coursePlanCd = $("#course_plan_cd_sel").attr("value");
        if(coursePlanCd.length == 0){
            $("#reading_btn_error1").show();
            error = true;
        }else{
            $("#reading_btn_error1").hide();
        }

        var fromAreaCd = $("#from_area_cd_sel").attr("value");
        if(fromAreaCd.length == 0){
            $("#reading_btn_error2").show();
            error = true;
        }else{
            $("#reading_btn_error2").hide();
        }

        if (!error) {
            location.href= baseUrl + "/" + coursePlanCd + "_" + fromAreaCd;
        }
    }

    // 削除ボタン押下時
    function onClickDeleteButton(url, flag){
        if(flag == '1'){
            if(!confirm('この閑散・繁忙期料金設定を削除しますか？')){
                // キャンセル
                return false;
            }
        }else if(flag == '2'){
            if(!confirm('このキャンペーンを削除しますか？')){
                // キャンセル
                return false;
            }
        }
        onSubmit(url);
    }

    $(function() {
        // 画面を遷移するときに子ウィンドウを閉じる
        $(window).unload(function(){
            if(winCalendar && !winCalendar.closed){
                winCalendar.close();
            }
            if(winPeriod && !winPeriod.closed){
                winPeriod.close();
            }
        });
    });
-->
</script>
</head>

<?php if($asp002Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA){ ?>
<body id="priceCat">
<?php } else if($asp002Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN){ ?>
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
<?php if($asp002Out->honsha_user_flag() === '1'){ ?>
  <li class="nav02"><a href="/acf/input"><img src="/common/img/nav_global02.gif" alt="料金マスタ設定" width="131" height="41"></a></li>
<?php }else{ ?>
  <li class="nav02"><img src="/common/img/nav_global02_off.gif" alt="料金マスタ設定" width="131" height="41"></li>
<?php } ?>
<li class="nav03"><a href="/asp/list/extra"><img src="/common/img/nav_global03.gif" alt="閑散・繁忙期料金設定" width="169" height="41"></a></li>
<li class="nav04"><a href="/asp/list/campaign"><img src="/common/img/nav_global04.gif" alt="キャンペーン特価設定" width="168" height="41"></a></li>
<?php if($asp002Out->honsha_user_flag() === '1'){ ?>
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

<table width="900">
<?php if($asp002Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA){ ?>
    <tr>
        <td colspan="3"><h2><img src="/common/img/asp/ttl_price_02.gif" alt="閑散・繁忙期料金設定　詳細"></h2></td>
    </tr>
<?php } else if($asp002Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN){ ?>
    <tr>
        <td colspan="3"><h2><img src="/common/img/asp/ttl_campaign_02.gif" alt="キャンペーン詳細"></h2></td>
    </tr>
<?php } ?>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
	</tr>
	<tr>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
		<td colspan="3" align="right">
            <form method='post' action=''>
			<table>
				<tr>
<?php
    if($asp002Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA){
        if($asp002Out->sp_editable_flag() === '1'){
?>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                    <td><input type="image" src="/common/img/asp/btn_new.gif" alt="新規追加" name="create_btn" onClick="onSubmit('/asp/input1/extra');"></td>
                    <td><img src="/common/img/spacer.gif" width="5" height="1" alt=""></td>
                    <td><input type="image" src="/common/img/asp/btn_change.gif" alt="変更する" name="edit_btn" onClick="onSubmit('/asp/input1/extra');"></td>
                    <td><img src="/common/img/spacer.gif" width="5" height="1" alt=""></td>
                    <td><input type="image" src="/common/img/asp/btn_delete.gif" alt="削除する" name="delete_btn" onClick="return onClickDeleteButton('/asp/delete/extra', '1');"></td>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
<?php   }else{ ?>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                    <td colspan="5"><input type="image" src="/common/img/asp/btn_new.gif" alt="新規追加" name="create_btn" onClick="onSubmit('/asp/input1/extra');"></td>
                    <td><img src="/common/img/spacer.gif" width="5" height="1" alt=""></td>
<?php
        }
    } else if($asp002Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN){
        if($asp002Out->sp_editable_flag() === '1'){
?>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                    <td><input type="image" src="/common/img/asp/btn_new.gif" alt="新規追加" name="create_btn" onClick="onSubmit('/asp/input1/campaign');"></td>
                    <td><img src="/common/img/spacer.gif" width="5" height="1" alt=""></td>
                    <td><input type="image" src="/common/img/asp/btn_change.gif" alt="変更する" name="edit_btn" onClick="onSubmit('/asp/input1/campaign');"></td>
                    <td><img src="/common/img/spacer.gif" width="5" height="1" alt=""></td>
                    <td><input type="image" src="/common/img/asp/btn_delete.gif" alt="削除する" name="delete_btn" onClick="return onClickDeleteButton('/asp/delete/campaign', '2');"></td>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
<?php   }else{ ?>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                    <td colspan="5"><input type="image" src="/common/img/asp/btn_new.gif" alt="新規追加" name="create_btn" onClick="onSubmit('/asp/input1/campaign');"></td>
                    <td><img src="/common/img/spacer.gif" width="5" height="1" alt=""></td>
<?php
        }
    }
?>
				</tr>
				<tr>
					<td colspan="7"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
				</tr>
                <tr>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                    <td colspan="5"><a id='back_to_list' href="<?php echo $asp002Out->sp_list_url(); ?>">← 一覧に戻る</a></td>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                </tr>
				<tr>
					<td colspan="7"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
				</tr>
			</table>
            <input type='hidden' name='sp_cd' value='<?php echo $asp002Out->sp_cd() ?>' />
            <input type='hidden' name='sp_timestamp' value='<?php echo $asp002Out->sp_timestamp(); ?>' />
            <input type='hidden' name='sp_list_kind' value='<?php echo $asp002Out->sp_list_kind(); ?>' />
            <input type='hidden' name='sp_list_view_mode' value='<?php echo $asp002Out->sp_list_view_mode(); ?>' />
            <input type='hidden' name='ticket' value='<?php echo $ticket; ?>' />
            <input type='hidden' name='gamen_id' value='ASP002'>
            </form>
		</td>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
	</tr>
<?php if($asp002Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA){ ?>
    <tr>
        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
        <td class="ttl">閑散・繁忙期料金設定内容</td>
        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
    </tr>
<?php } else if($asp002Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN){ ?>
    <tr>
        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
        <td class="ttl">キャンペーン詳細内容</td>
        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
    </tr>
<?php } ?>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" alt="" width="1" height="10"></td>
	</tr>
<?php if ($e->hasErrorForId('top')) { ?>
    <tr>
        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
        <td width="880" align="left" class="red"><?php echo $e->getMessage('top'); ?></td>
        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
    </tr>
    <?php if ($e->hasErrorForId('top_delete')) { ?>
    <tr>
        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
        <td width="880" align="left" class="red"><?php echo $e->getMessage('top_delete'); ?></td>
        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
    </tr>
    <?php } ?>
<?php } else { ?>
    <?php if ($e->hasErrorForId('top_delete')) { ?>
    <tr>
        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
        <td width="880" align="left" class="red"><?php echo $e->getMessage('top_delete'); ?></td>
        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
    </tr>
    <?php } ?>
	<tr>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
		<td>
			<table class="inner" width="880">
				<tr>
					<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
					<td>
						<table width="860" class="bdr">
                            <tr>
                                <th width="90">登録日</th>
                                <td width="120"><?php echo $asp002Out->sp_regist_date(); ?></td>
                                <th width="85">担当</th>
                                <td width="100"><?php echo $asp002Out->sp_charge_center(); ?></td>
                                <th width="100">登録者名</th>
                                <td width="100"><?php echo $asp002Out->sp_regist_user(); ?></td>
                                <th width="85">状況</th>
                                <td width="90"><?php echo $asp002Out->sp_status(); ?></td>
                            </tr>
                            <tr>
                                <th>名称</th>
                                <td colspan="7"><?php echo $asp002Out->sp_name(); ?></td>
                            </tr>
<?php if($asp002Out->sp_kind() === '2'){ // キャンペーン ?>
                            <tr>
                                <th>広告内容</th>
                                <td colspan="7"><?php echo $asp002Out->sp_content(); ?></td>
                            </tr>
<?php } ?>
<?php
    // 呼び出すたびにエンティティ化されるので先に取得しておく
    $sp_course_lbls = $asp002Out->sp_course_lbls();
    $sp_plan_lbls = $asp002Out->sp_plan_lbls();

    $courseCount = count($sp_course_lbls);
    $rowspan = 0;
    for($i=0;$i<$courseCount;$i++){
        $rowspan += count($sp_plan_lbls[$i]);
    }

    for($i=0;$i<$courseCount;$i++){
        echo "<tr>";
        if($i === 0){
            // 最初だけ
            echo "<th rowspan='{$rowspan}'>コース・プラン</th>";
        }
        $planCount = count($sp_plan_lbls[$i]);
        echo "<td rowspan='{$planCount}' colspan='2'>{$sp_course_lbls[$i]}</td>";
        for($j=0;$j<$planCount;$j++){
            if($j > 0){
                // 2番目以降
                echo "</tr><tr>";
            }
            echo "<td colspan='5'>{$sp_plan_lbls[$i][$j]}</td>";
        }
        echo "</tr>";
    }
?>
                            <tr>
                                <th>出発エリア</th>
                                <td colspan="7"><?php echo $asp002Out->sp_from_area(); ?></td>
                            </tr>
                            <tr>
                                <th>到着エリア</th>
                                <td colspan="7"><?php echo $asp002Out->sp_to_area(); ?></td>
                            </tr>
                            <tr>
                                <th rowspan="3">期間</th>
<?php
    if($asp002Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA){
        $url = '/asp/period/extra/' . $asp002Out->sp_cd();
        echo '<td colspan="7">' . $asp002Out->sp_period();
        echo '　（<a href="javascript:void(0);" onClick="openPeriodWindow(' . "'{$url}'" . ');">対象日をカレンダーで確認する</a>）</td>';
    }else if($asp002Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN){
        $url = '/asp/period/campaign/' . $asp002Out->sp_cd();
        echo '<td colspan="7">' . $asp002Out->sp_period();
        echo '　（<a href="javascript:void(0);" onClick="openPeriodWindow(' . "'{$url}'" . ');">対象日をカレンダーで確認する</a>）</td>';
    }
?>
						</table>
					</td>
					<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" alt="" width="1" height="20"></td>
	</tr>
<?php
    // 料金設定有
    if($asp002Out->sp_charge_set_flag() === '1'){
 ?>
	<tr>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
		<td class="ttl">料金設定一覧</td>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
	</tr>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" alt="" width="1" height="10"></td>
	</tr>
    <tr id="reading_btn_error1" style="display:none;">
        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
        <td width="880" align="left" class="red">コース・プランを選択してください</td>
        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
    </tr>
    <tr id="reading_btn_error2" style="display:none;">
        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
        <td width="880" align="left" class="red">出発地を選択してください</td>
        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
    </tr>
	<tr>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
		<td>
			<table class="inner" width="880">
				<tr>
					<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
					<td>
						<table>
							<tr>
								<td class="multiselect">
									<table>
										<tr>
											<td>コース・プラン</td>
										</tr>
										<tr>
                                            <td><select id='course_plan_cd_sel' name="" size="4">
                                                <?php
                                                    $cds = $asp002Out->course_plan_cds();
                                                    $lbls = $asp002Out->course_plan_lbls();
                                                    for($i = 0;$i<count($cds);$i++){
                                                        $cd = $cds[$i];
                                                        $lbl = $lbls[$i];
                                                        if($asp002Out->course_plan_cd_sel() === $cd){
                                                            echo "<option value='{$cd}' selected>{$lbl}</option>\n";
                                                        }else{
                                                            echo "<option value='{$cd}'>{$lbl}</option>\n";
                                                        }
                                                    }
                                                ?>
                                                </select>
                                            </td>
                                        </tr>
									</table>
								</td>
								<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
								<td class="multiselect">
									<table>
										<tr>
											<td>出発地</td>
										</tr>
										<tr>
                                            <td><select id="from_area_cd_sel" name="" size="4">
                                                <?php
                                                    $cds = $asp002Out->from_area_cds();
                                                    $lbls = $asp002Out->from_area_lbls();
                                                    for($i = 0;$i<count($cds);$i++){
                                                        $cd = $cds[$i];
                                                        $lbl = $lbls[$i];
                                                        if($asp002Out->from_area_cd_sel() === $cd){
                                                            echo "<option value='{$cd}' selected>{$lbl}</option>\n";
                                                        }else{
                                                            echo "<option value='{$cd}'>{$lbl}</option>\n";
                                                        }
                                                    }
                                                ?>
                                                </select>
                                            </td>
										</tr>
									</table>
								</td>
								<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
								<td valign="bottom">の料金を</td>
								<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                                <td valign="bottom">
                                <?php
                                    if($asp002Out->sp_list_view_mode() === Sgmov_View_Asp_Detail::SP_LIST_VIEW_OPEN){
                                        $mode = 'open';
                                    }else if($asp002Out->sp_list_view_mode() === Sgmov_View_Asp_Detail::SP_LIST_VIEW_CLOSE){
                                        $mode = 'close';
                                    }else if($asp002Out->sp_list_view_mode() === Sgmov_View_Asp_Detail::SP_LIST_VIEW_DRAFT){
                                        $mode = 'draft';
                                    }

                                    if($asp002Out->sp_kind() === '1'){
                                        // 閑散繁忙期
                                        $url = '/asp/detail/extra/' . $mode . '/' . $asp002Out->sp_cd();
                                        echo '<input type="image" src="/common/img/asp/btn_show.gif" alt="表示する" onClick="onClickReadingButton(\''. $url . '\');">';
                                    } else if($asp002Out->sp_kind() === '2'){
                                        // キャンペーン
                                        $url = '/asp/detail/campaign/'. $mode . '/' . $asp002Out->sp_cd();
                                        echo '<input type="image" src="/common/img/asp/btn_show.gif" alt="表示する" onClick="onClickReadingButton(\''. $url . '\');">';
                                    }
                                ?>
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
<?php
    // 料金部表示非表示
    if($asp002Out->cond_selected_flag() === '1'){
        // 呼び出すたびにエンティティ化されるので先に取得しておく
        $to_area_lbls = $asp002Out->to_area_lbls();
        $sp_base_charges = $asp002Out->sp_base_charges();
        $sp_setting_charges = $asp002Out->sp_setting_charges();
        $sp_calendar_urls = $asp002Out->sp_calendar_urls();

        $count = count($to_area_lbls);
 ?>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" alt="" width="1" height="15"></td>
	</tr>
	<tr>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
		<td align="left">
			<table>
				<tr>
					<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
					<td>
						<table class="box_s">
							<tr class="sp">
								<td class="bg_blue">コース・プラン</td>
								<td><?php echo $asp002Out->cur_course_plan(); ?></td>
								<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
								<td class="bg_blue">出発エリア</td>
								<td><?php echo $asp002Out->cur_from_area(); ?></td>
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
	<tr>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
		<td>
			<table class="inner" width="880">
				<tr>
					<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
					<td>
						<table width="425" class="bdr">
							<tr>
								<th width="80">到着エリア</th>
								<th width="110">基本料金（円）</th>
								<th width="90">設定値（円）</th>
								<th width="90">カレンダー</th>
							</tr>
<?php
                            // 2で割って端数を切り上げ
                            $leftCount = ceil($count / 2);
                            for($i=0;$i<$leftCount;$i++){
                                $base = Sgmov_Component_String::number_format($sp_base_charges[$i]);
                                $price = Sgmov_Component_String::number_format($sp_setting_charges[$i]);

                                if(intval($sp_setting_charges[$i], 10) < 0){
                                    $class = 'class="price red"';
                                }else{
                                    $class = 'class="price blue"';
                                    $price = '+' . $price;
                                }

                                echo "<tr>\n";
                                echo "    <td>{$to_area_lbls[$i]}</td>\n";
                                echo "    <td class='price'>{$base}</td>\n";
                                echo "    <td {$class}>{$price}</td>\n";
                                echo "    <td><a href=\"javascript:void(0);\" onClick=\"openPeriodWindow('{$sp_calendar_urls[$i]}');\">▼カレンダーで確認</a></td>\n";
                                echo "</tr>\n";
                            }
?>
						</table>
					</td>
					<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
					<td valign='top'>
                        <?php if($count < 2){ ?>
                            <table width="425" class="bdr">
                            </table>
                        <?php } else { ?>
    						<table width="425" class="bdr">
    							<tr>
    								<th width="80">到着エリア</th>
    								<th width="110">基本料金（円）</th>
    								<th width="90">設定値（円）</th>
    								<th width="90">カレンダー</th>
    							</tr>
<?php
                                for($i=$leftCount;$i<$count;$i++){
                                    $base = Sgmov_Component_String::number_format($sp_base_charges[$i]);
                                    $price = Sgmov_Component_String::number_format($sp_setting_charges[$i]);

                                    if(intval($sp_setting_charges[$i], 10) < 0){
                                        $class = 'class="price red"';
                                    }else{
                                        $class = 'class="price blue"';
                                        $price = '+' . $price;
                                    }

                                    echo "<tr>\n";
                                    echo "    <td>{$to_area_lbls[$i]}</td>\n";
                                    echo "    <td class='price'>{$base}</td>\n";
                                    echo "    <td {$class}>{$price}</td>\n";
                                    echo "    <td><a href=\"javascript:void(0);\" onClick=\"openPeriodWindow('{$sp_calendar_urls[$i]}');\">▼カレンダーで確認</a></td>\n";
                                    echo "</tr>\n";
                                }
?>
    						</table>
                        <?php } ?>
					</td>
					<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
				</tr>
			</table>
		</td>
		<td><img src="/common/img/spacer.gif" width="6" height="1" alt=""></td>
	</tr>
<?php
        }// 料金表示
    }// 金額設定
?>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" alt="" width="1" height="10"></td>
	</tr>
	<tr>
		<td colspan="3" align="right">
            <form method='post' action=''>
			<table><tr>
<?php
    if($asp002Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA){
        if($asp002Out->sp_editable_flag() === '1'){ // 編集可能
?>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                    <td><input type="image" src="/common/img/asp/btn_new.gif" alt="新規追加" name="create_btn" onClick="onSubmit('/asp/input1/extra');"></td>
                    <td><img src="/common/img/spacer.gif" width="5" height="1" alt=""></td>
                    <td><input type="image" src="/common/img/asp/btn_change.gif" alt="変更する" name="edit_btn" onClick="onSubmit('/asp/input1/extra');"></td>
                    <td><img src="/common/img/spacer.gif" width="5" height="1" alt=""></td>
                    <td><input type="image" src="/common/img/asp/btn_delete.gif" alt="削除する" name="delete_btn" onClick="return onClickDeleteButton('/asp/delete/extra', '1');"></td>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
<?php   }else{ ?>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                    <td colspan="5"><input type="image" src="/common/img/asp/btn_new.gif" alt="新規追加" name="create_btn" onClick="onSubmit('/asp/input1/extra');"></td>
                    <td><img src="/common/img/spacer.gif" width="5" height="1" alt=""></td>
<?php
        }
    } else if($asp002Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN){
        if($asp002Out->sp_editable_flag() === '1'){ // 編集可能
?>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                    <td><input type="image" src="/common/img/asp/btn_new.gif" alt="新規追加" name="create_btn" onClick="onSubmit('/asp/input1/campaign');"></td>
                    <td><img src="/common/img/spacer.gif" width="5" height="1" alt=""></td>
                    <td><input type="image" src="/common/img/asp/btn_change.gif" alt="変更する" name="edit_btn" onClick="onSubmit('/asp/input1/campaign');"></td>
                    <td><img src="/common/img/spacer.gif" width="5" height="1" alt=""></td>
                    <td><input type="image" src="/common/img/asp/btn_delete.gif" alt="削除する" name="delete_btn" onClick="return onClickDeleteButton('/asp/delete/campaign', '1');"></td>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
<?php   }else{ ?>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                    <td colspan="5"><input type="image" src="/common/img/asp/btn_new.gif" alt="新規追加" name="create_btn" onClick="onSubmit('/asp/input1/campaign');"></td>
                    <td><img src="/common/img/spacer.gif" width="5" height="1" alt=""></td>
<?php
        }
    }
?>
			</tr></table>
            <input type='hidden' name='sp_cd' value='<?php echo $asp002Out->sp_cd(); ?>' />
            <input type='hidden' name='sp_timestamp' value='<?php echo $asp002Out->sp_timestamp(); ?>' />
            <input type='hidden' name='sp_list_kind' value='<?php echo $asp002Out->sp_list_kind(); ?>' />
            <input type='hidden' name='sp_list_view_mode' value='<?php echo $asp002Out->sp_list_view_mode(); ?>' />
            <input type='hidden' name='ticket' value='<?php echo $ticket; ?>' />
            <input type='hidden' name='gamen_id' value='ASP002'>
            </form>
		</td>
	</tr>
<?php } ?>
</table>
<!-- /#topWrap --></div>

<div id="footer">

<address><img src="/common/img/txt_copyright.gif" alt="&copy; SG Moving Co.,Ltd. All Rights Reserved."></address>
<!-- /#footer --></div>

<!-- /#wrapper --></div>
</body>
</html>

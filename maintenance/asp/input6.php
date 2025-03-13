<?php
/**
 * 特価個別編集金額入力画面を表示します。
 * @package    maintenance
 * @subpackage ASP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('asp/Input6');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Asp_Input6();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Asp009Out
 */
$asp009Out = $forms['outForm'];

/**
 * エラーフォーム
 * @var Sgmov_Form_Error
 */
$e = $forms['errorForm'];

/**
 * 検索部エラーフォーム
 * @var Sgmov_Form_Error
 */
$searchError = $forms['searchErrorForm'];

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
    function onSubmit(url){
        $("form").attr("action", url);
    }

    $(function() {
        // 一覧に戻るリンククリック時
        $("#back_to_list").click(function(){
            if(!confirm("入力を中止して一覧に戻ります。\編集中の内容は破棄されますがよろしいですか？")){
                // キャンセル
                return false;
            }
        });

        // imageボタンではなくimgを使用する場合のホバー時カーソル設定
        $(".sgmov_clickable").hover(function(){
            $(this).css("cursor","pointer");
        },function(){
            $(this).css("cursor","default");
        });

        // 金額ボックスの設定
        $("#sp_whole_charge, input[@name^='sp_setting_charges[']").bind("keydown keypress keyup", function(){
            var strVal = this.value;
            var me = $(this);
            if(strVal.match(/^[-+]?(0|[1-9][0-9]*)$/)){
                var intVal = parseInt(strVal, 10);
                if(intVal > 0){
                    // 正
                    me.attr("class", "price_i blue");
                }else if(intVal < 0){
                    // 負
                    me.attr("class", "price_i red");
                }else{
                    // 0
                    me.attr("class", "price_i");
                }

                // 最大最小値
                if (intVal > parseInt($("#max_" + me.attr("id")).text())) {
                    $("#arrow_" + me.attr("id")).text("↑");
                } else if (intVal < parseInt($("#min_" + me.attr("id")).text())) {
                    $("#arrow_" + me.attr("id")).text("↓");
                } else {
                    $("#arrow_" + me.attr("id")).text("");
                }
            }else{
                // 数値ではない場合
                me.attr("class", "price_i");
                $("#arrow_" + me.attr("id")).text("");
            }
        });

        // 一括設定ボタン
        $("#charge_all_set_btn").click(function(){
            var strVal = $("#sp_whole_charge").attr("value");
            // 0以外の整数
            if (strVal.match(/^[-+]?[1-9][0-9]*$/)) {
                // エラーメッセージは隠す
                $("#charge_all_set_error").hide();

                // 値適用
                $("input[@name^='sp_setting_charges[']").attr("value", strVal);

                // 色適用
                $("input[@name^='sp_setting_charges[']").trigger('keydown');
            }else{
                // エラーメッセージを表示
                $("#charge_all_set_error_mgs").text("0以外の整数を入力してください");
                $("#charge_all_set_error").show();
            }
        });

        // 初期表示時
        $("#sp_whole_charge, input[@name^='sp_setting_charges[']").trigger('keydown');
    });
-->
</script>
</head>

<?php if($asp009Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA){ ?>
<body id="priceCat">
<?php } else if($asp009Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN){ ?>
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
<?php if($asp009Out->honsha_user_flag() === '1'){ ?>
  <li class="nav02"><a href="/acf/input"><img src="/common/img/nav_global02.gif" alt="料金マスタ設定" width="131" height="41"></a></li>
<?php }else{ ?>
  <li class="nav02"><img src="/common/img/nav_global02_off.gif" alt="料金マスタ設定" width="131" height="41"></li>
<?php } ?>
<li class="nav03"><a href="/asp/list/extra"><img src="/common/img/nav_global03.gif" alt="閑散・繁忙期料金設定" width="169" height="41"></a></li>
<li class="nav04"><a href="/asp/list/campaign"><img src="/common/img/nav_global04.gif" alt="キャンペーン特価設定" width="168" height="41"></a></li>
<?php if($asp009Out->honsha_user_flag() === '1'){ ?>
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

<form method='post' action=''>
<table width="900">
<?php if($asp009Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA){ ?>
    <tr>
        <td colspan="3"><h2><img src="/common/img/asp/ttl_price_01.gif" alt="閑散・繁忙期料金設定"></h2></td>
    </tr>
<?php } else if($asp009Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN){ ?>
    <tr>
        <td colspan="3"><h2><img src="/common/img/asp/ttl_campaign_01.gif" alt="キャンペーン設定"></h2></td>
    </tr>
<?php } ?>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
	</tr>
    <tr>
        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
        <td align="right"><a id='back_to_list' href="<?php echo $asp009Out->sp_list_url(); ?>">← 一覧に戻る</a></td>
        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
    </tr>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
	</tr>
	<tr>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
		<td class="ttl">料金設定一覧</td>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
	</tr>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
	</tr>
    <?php if ($searchError->hasError()) { ?>
        <?php if ($searchError->hasErrorForId('top_course_plan_cd_sel')) { ?>
            <tr>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                <td width="880" align="left" class="red">コース・プラン<?php echo $searchError->getMessage('top_course_plan_cd_sel'); ?></td>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
            </tr>
        <?php } ?>
        <?php if ($searchError->hasErrorForId('top_from_area_cd_sel')) { ?>
            <tr>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                <td width="880" align="left" class="red">出発地<?php echo $searchError->getMessage('top_from_area_cd_sel'); ?></td>
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
                                            <td><select name="course_plan_cd_sel" size="4">
                                                <?php
                                                    $cds = $asp009Out->course_plan_cds();
                                                    $lbls = $asp009Out->course_plan_lbls();
                                                    for($i = 0;$i<count($cds);$i++){
                                                        $cd = $cds[$i];
                                                        $lbl = $lbls[$i];
                                                        if($asp009Out->course_plan_cd_sel() === $cd){
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
                                            <td><select name="from_area_cd_sel" size="4">
                                                <?php
                                                    $cds = $asp009Out->from_area_cds();
                                                    $lbls = $asp009Out->from_area_lbls();
                                                    for($i = 0;$i<count($cds);$i++){
                                                        $cd = $cds[$i];
                                                        $lbl = $lbls[$i];
                                                        if($asp009Out->from_area_cd_sel() === $cd){
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
                                <?php if($asp009Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA){ ?>
                                    <td valign="bottom"><input type="image" src="/common/img/asp/btn_show.gif" alt="表示する" name="reading_btn"  onClick="onSubmit('/asp/input6/extra');"></a></td>
                                <?php } else if($asp009Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN){ ?>
                                    <td valign="bottom"><input type="image" src="/common/img/asp/btn_show.gif" alt="表示する" name="reading_btn"  onClick="onSubmit('/asp/input6/campaign');"></a></td>
                                <?php } ?>
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
    if($asp009Out->cond_selected_flag() === '1'){
        // 呼び出すたびにエンティティ化されるので先に取得しておく
        $to_area_lbls = $asp009Out->to_area_lbls();
        $sp_base_charges = $asp009Out->sp_base_charges();
        $sp_setting_charges = $asp009Out->sp_setting_charges();

        $sp_diff_maxs = $asp009Out->sp_diff_maxs();
        $sp_diff_mins = $asp009Out->sp_diff_mins();

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
								<td><?php echo $asp009Out->cur_course_plan() ?></td>
								<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
								<td class="bg_blue">出発エリア</td>
								<td><?php echo $asp009Out->cur_from_area() ?></td>
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
		<td align="left">
			<table>
				<tr>
					<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
					<td>
						<table width="600" class="line">
                            <tr id="charge_all_set_error" style="display:none;">
                                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                                <td class="red" colspan="3" id="charge_all_set_error_mgs"></td>
                            </tr>
							<tr>
								<td class="sp">差額設定を一括で変更</td>
								<td class="sp"><input type="text" value="" class="price_i" id="sp_whole_charge" maxlength="9"></td>
								<td>円に</td>
								<td class="sp"><img src="/common/img/asp/btn_all.gif" alt="一括変更" id="charge_all_set_btn" class="sgmov_clickable"></td>
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
<?php
    if ($e->hasError()) {
        $coursePlanCds = $asp009Out->course_plan_cds();
        $coursePlanLbls = $asp009Out->course_plan_lbls();

        $fromAreaCds = $asp009Out->from_area_cds();
        $fromAreaLbls = $asp009Out->from_area_lbls();

        $coursePlanCount = count($coursePlanCds);
        $fromAreaCount = count($fromAreaCds);
        for($i=0;$i<$coursePlanCount;$i++){
            for($j=0;$j<$fromAreaCount;$j++){
                $coursePlanCd = $coursePlanCds[$i];
                $fromAreaCd = $fromAreaCds[$j];

                $errorMsgId = "top_{$coursePlanCd}_{$fromAreaCd}";
                if ($e->hasErrorForId($errorMsgId)) {
                    $coursePlanLbl = $coursePlanLbls[$i];
                    $fromAreaLbl = $fromAreaLbls[$j];

                    $msg = $coursePlanLbl . ' ' . $fromAreaLbl . $e->getMessage($errorMsgId);

?>
            <tr>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                <td width="880" align="left" class="red"><?php echo $msg; ?></td>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
            </tr>
<?php
                }
            }
        }
?>
        <tr>
            <td colspan="3"><img src="/common/img/spacer.gif" alt="" width="1" height="10"></td>
        </tr>
<?php
    }
?>
	<tr>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
		<td>
			<table>
				<tr>
					<td>
						<table width="880">
							<tr>
								<td><img src="/common/img/spacer.gif" alt="" width="10" height="1"></td>
								<td>
									<table width="425" class="bdr">
										<tr>
											<th width="121">到着エリア</th>
											<th width="110">基本料金（円）</th>
                                            <th width="25" class="bg_yellow"></th>
											<th width="135" class="bg_yellow">差額設定（円）</th>
										</tr>
                                        <?php
                                            // コードを取得しておく
                                            $coursePlanAreaCd = $asp009Out->cur_course_plan_cd()  .  '_' . $asp009Out->cur_from_area_cd();
                                            $errorKeyPrefix = "item_{$coursePlanAreaCd}_";

                                            // 2で割って端数を切り上げ
                                            $leftCount = ceil($count / 2);
                                            for($i=0;$i<$leftCount;$i++){
                                                $base = Sgmov_Component_String::number_format($sp_base_charges[$i]);

                                                if($e->hasErrorForId($errorKeyPrefix . $i)){
                                                    $class = 'class="bg_red"';
                                                }else{
                                                    $class = '';
                                                }

                                                echo "<tr>\n";
                                                echo "    <td>{$to_area_lbls[$i]}</td>\n";
                                                echo "    <td class='price_i'>{$base}</td>\n";
                                                echo "    <td id='arrow_sp_diff_{$i}'></td>\n";
                                                echo "    <td {$class}>\n";
                                                echo "        <span style='display:none;' id='max_sp_diff_{$i}'>{$sp_diff_maxs[$i]}</span>\n";
                                                echo "        <span style='display:none;' id='min_sp_diff_{$i}'>{$sp_diff_mins[$i]}</span>\n";
                                                echo "        <input type='text' value='{$sp_setting_charges[$i]}' id='sp_diff_{$i}' class='price_i' name='sp_setting_charges[{$i}]'>\n";
                                                echo "    </td>\n";
                                                echo "</tr>\n";
                                            }
                                        ?>
									</table>
								</td>
								<td><img src="/common/img/spacer.gif" alt="" width="10" height="1"></td>
								<td valign='top'>
								    <?php if($count < 2){ ?>
                                        <table width="425" class="bdr">
                                        </table>
                                    <?php } else { ?>
    									<table width="425" class="bdr">
    										<tr>
    											<th width="121">到着エリア</th>
    											<th width="110">基本料金（円）</th>
                                                <th width="25" class="bg_yellow"></th>
    											<th width="135" class="bg_yellow">差額設定（円）</th>
    										</tr>
                                            <?php
                                                for($i=$leftCount;$i<$count;$i++){
                                                    $base = Sgmov_Component_String::number_format($sp_base_charges[$i]);

                                                    if($e->hasErrorForId($errorKeyPrefix . $i)){
                                                        $class = 'class="bg_red"';
                                                    }else{
                                                        $class = '';
                                                    }

                                                    echo "<tr>\n";
                                                    echo "    <td>{$to_area_lbls[$i]}</td>\n";
                                                    echo "    <td class='price_i'>{$base}</td>\n";
                                                    echo "    <td id='arrow_sp_diff_{$i}'></td>\n";
                                                    echo "    <td {$class}>\n";
                                                    echo "        <span style='display:none;' id='max_sp_diff_{$i}'>{$sp_diff_maxs[$i]}</span>\n";
                                                    echo "        <span style='display:none;' id='min_sp_diff_{$i}'>{$sp_diff_mins[$i]}</span>\n";
                                                    echo "        <input type='text' value='{$sp_setting_charges[$i]}' id='sp_diff_{$i}' class='price_i' name='sp_setting_charges[{$i}]'>\n";
                                                    echo "    </td>\n";
                                                    echo "</tr>\n";
                                                }
                                            ?>
    									</table>
                                    <?php } ?>
								</td>
								<td><img src="/common/img/spacer.gif" alt="" width="10" height="1"></td>
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
		<td>
        <?php if($asp009Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA){ ?>
            <table>
                <tr>
                    <td><input type="image" src="/common/img/asp/btn_confirm.gif" alt="内容確認へ" onClick="onSubmit('/asp/check_input6/extra');"></td>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                    <td><a href="/asp/input4/extra"><img src="/common/img/asp/btn_back.gif" alt="戻る"></a></td>
                </tr>
            </table>
        <?php } else if($asp009Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN){ ?>
            <table>
                <tr>
                    <td><input type="image" src="/common/img/asp/btn_confirm.gif" alt="内容確認へ" onClick="onSubmit('/asp/check_input6/campaign');"></td>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                    <td><a href="/asp/input4/campaign"><img src="/common/img/asp/btn_back.gif" alt="戻る"></a></td>
                </tr>
            </table>
        <?php } ?>
		</td>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
	</tr>
<?php } else { // 料金部表示非表示 ?>
    <tr>
        <td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
    </tr>
    <tr>
        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
        <td>
        <?php if($asp009Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA){ ?>
            <table>
                <tr>
                    <td><a href="/asp/input4/extra"><img src="/common/img/asp/btn_back.gif" alt="戻る"></a></td>
                </tr>
            </table>
        <?php } else if($asp009Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN){ ?>
            <table>
                <tr>
                    <td><a href="/asp/input4/campaign"><img src="/common/img/asp/btn_back.gif" alt="戻る"></a></td>
                </tr>
            </table>
        <?php } ?>
        </td>
        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
    </tr>
<?php }  // 料金部表示非表示 ?>
</table>
<input type='hidden' name='ticket' value='<?php echo $ticket; ?>' />
</form>
<!-- /#topWrap --></div>

<div id="footer">

<address><img src="/common/img/txt_copyright.gif" alt="&copy; SG Moving Co.,Ltd. All Rights Reserved."></address>
<!-- /#footer --></div>

<!-- /#wrapper --></div>
</body>
</html>

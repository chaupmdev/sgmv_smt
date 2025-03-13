<?php
/**
 * 料金マスタメンテナンス入力画面を表示します。
 * @package    maintenance
 * @subpackage ACF
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('acf/Input');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Acf_Input();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Acf002Out
 */
$acf002Out = $forms['outForm'];

/**
 * エラーフォーム
 * @var Sgmov_Form_Error
 */
$e = $forms['errorForm'];
//print_r($e);
//exit;

/**
 * 検索部エラーフォーム
 * @var Sgmov_Form_Error
 */
$searchError = $forms['searchErrorForm'];

// 料金部を表示する場合
if($acf002Out->cond_selected_flag() === '1'){
    // 呼び出すたびにエンティティ化されるので先に取得しておく
    $to_area_cds = $acf002Out->to_area_cds();
    $to_area_lbls = $acf002Out->to_area_lbls();
    $base_prices = $acf002Out->base_prices();
    $max_prices = $acf002Out->max_prices();
    $min_prices = $acf002Out->min_prices();
    $orig_base_prices = $acf002Out->orig_base_prices();
    $orig_max_prices = $acf002Out->orig_max_prices();
    $orig_min_prices = $acf002Out->orig_min_prices();

    $to_area_count = count($to_area_cds);
}
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
<script type="text/javascript" src="/common/js/acfCourcePlanCheck.js"></script>
<script type="text/javascript" src="/common/js/enterDisable.js"></script>
<script type="text/javascript">
<!--
    /**
     * 料金変更チェック
     */
    function priceCheck(obj, origPrice) {
        var priceElem = $(obj);
        if(priceElem.attr('value') == origPrice){
            if(priceElem.hasClass('bg_red')){
                priceElem.removeClass('bg_red');
            }
        }else{
            if(!priceElem.hasClass('bg_red')){
                priceElem.addClass('bg_red');
            }
        }
    }

    $(function() {
        // 表示ボタンを押した場合はフォームのアクションを""に設定する
        $("#reading_btn").click(function(){
            if($("input:text[@class*='bg_red']").length > 0){
                // 変更がある場合
                if(!confirm('現在編集中の金額情報は破棄されますがよろしいですか？')){
                    // キャンセル
                    return false;
                }
            }
            $("form").attr("action", "");
        });

        // 確認ボタンを押した場合はフォームのアクションを"/acf/check_input"に設定する
        $("#confirm_btn").click(function(){
            $("form").attr("action", "/acf/check_input");
        });
    });
-->
</script>
</head>

<body id="masterCat" onload="inputOnload(false)">

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
<?php if($acf002Out->honsha_user_flag() === '1'){ ?>
  <li class="nav02"><a href="/acf/input"><img src="/common/img/nav_global02.gif" alt="料金マスタ設定" width="131" height="41"></a></li>
<?php }else{ ?>
  <li class="nav02"><img src="/common/img/nav_global02_off.gif" alt="料金マスタ設定" width="131" height="41"></li>
<?php } ?>
<li class="nav03"><a href="/asp/list/extra"><img src="/common/img/nav_global03.gif" alt="閑散・繁忙期料金設定" width="169" height="41"></a></li>
<li class="nav04"><a href="/asp/list/campaign"><img src="/common/img/nav_global04.gif" alt="キャンペーン特価設定" width="168" height="41"></a></li>
<?php if($acf002Out->honsha_user_flag() === '1'){ ?>
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

<form method="post" action="">
<table width="900">
	<tr>
		<td colspan="3"><h2><img src="/common/img/acf/ttl_master_01.gif" alt="料金マスタ設定"></h2></td>
	</tr>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
	</tr>
	<tr>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
		<td><div class="caption">コース・プラン・出発エリアの選択</div>
			<div class="outline">
			<table>
				<tr>
					<td>料金設定を行うコース・プラン、出発エリアを選択してください。</td>
				</tr>
				<tr>
					<td class="attention">【注意】条件を変更して再表示すると、編集中の料金データは破棄されます。</td>
				</tr>
                <?php if ($searchError->hasErrorForId('top_course_plan_cd_sel')) { ?>
                    <tr>
                        <td class="red">コース・プラン<?php echo $searchError->getMessage('top_course_plan_cd_sel'); ?></td>
                    </tr>
                <?php } ?>
                <?php if ($searchError->hasErrorForId('top_from_area_cd_sel')) { ?>
                    <tr>
                        <td class="red">出発エリア<?php echo $searchError->getMessage('top_from_area_cd_sel'); ?></td>
                    </tr>
                <?php } ?>
				<tr>
					<td>
						<table>
							<tr>
								<td>コース・プランを選択</td>
								<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
								<td><select name="course_plan_cd_sel" size="1" style="width:250px;" onChange="checkCoucePlan(true)">
                                    <?php
                                    echo Sgmov_View_Acf_Input::_createPulldown($acf002Out->course_plan_cds(), $acf002Out->course_plan_lbls(), $acf002Out->course_plan_cd_sel(), 0);
                                    ?>
                                    </select>
								</td>
								<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>出発エリアを選択</td>
								<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
								<td>
									<select name="from_area_cd_sel[]" id="fromarea1" size="1" style="width:250px;" onChange="getFromAreaCd(true)">
                                    <?php
                                    echo Sgmov_View_Acf_Input::_createPulldown($acf002Out->from_area_cds(), $acf002Out->from_area_lbls(), $acf002Out->from_area_cd_sel(), Sgmov_View_Acf_Common::AREA_HYOJITYPE_NORMAL);
                                    ?>
									</select>
									<select name="from_area_cd_sel[]" id="fromarea2" size="1" style="width:250px;" style="display: none" onChange="getFromAreaCd(true)">
                                    <?php
                                    echo Sgmov_View_Acf_Input::_createPulldown($acf002Out->from_area_cds(), $acf002Out->from_area_lbls(), $acf002Out->from_area_cd_sel(), Sgmov_View_Acf_Common::AREA_HYOJITYPE_OKINAWANASHI);
                                    ?>
									</select>
									<select name="from_area_cd_sel[]" id="fromarea3" size="1" style="width:250px;" style="display: none" onChange="getFromAreaCd(true)">
                                    <?php
                                    echo Sgmov_View_Acf_Input::_createPulldown($acf002Out->from_area_cds(), $acf002Out->from_area_lbls(), $acf002Out->from_area_cd_sel(), Sgmov_View_Acf_Common::AREA_HYOJITYPE_AIRCARGO);
                                    ?>
									</select>
								</td>
								<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
								<td><input type="image" src="/common/img/acf/btn_list.gif" alt="一覧を表示する" name="reading_btn" id="reading_btn"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			</div>
		</td>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
	</tr>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" alt="" width="1" height="20"></td>
	</tr>
    <?php
        // 料金部表示非表示
        if($acf002Out->cond_selected_flag() === '1'){
     ?>
    	<tr>
    		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
    		<td class="ttl">料金一覧</td>
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
    					<td colspan="5">料金を設定してください。</td>
    				</tr>
    				<tr>
    					<td colspan="5"><img src="/common/img/spacer.gif" alt="" width="1" height="10"></td>
    				</tr>
                    <?php if ($e->hasErrorForId('top_empty')) { ?>
                        <tr>
                            <td class="red" colspan="5"><?php echo $e->getMessage('top_empty'); ?></td>
                        </tr>
                        <tr>
                            <td colspan="5"><img src="/common/img/spacer.gif" alt="" width="1" height="10"></td>
                        </tr>
                    <?php } ?>
                    <?php if ($e->hasErrorForId('top_invalid')) { ?>
                        <tr>
                            <td class="red" colspan="5"><?php echo $e->getMessage('top_invalid'); ?></td>
                        </tr>
                        <tr>
                            <td colspan="5"><img src="/common/img/spacer.gif" alt="" width="1" height="10"></td>
                        </tr>
                    <?php } ?>
                    <?php if ($e->hasErrorForId('top_minmax')) { ?>
                        <tr>
                            <td class="red" colspan="5"><?php echo $e->getMessage('top_minmax'); ?></td>
                        </tr>
                        <tr>
                            <td colspan="5"><img src="/common/img/spacer.gif" alt="" width="1" height="10"></td>
                        </tr>
                    <?php } ?>
                    <?php if ($e->hasErrorForId('top_noedit')) { ?>
                        <tr>
                            <td class="red" colspan="5"><?php echo $e->getMessage('top_noedit'); ?></td>
                        </tr>
                        <tr>
                            <td colspan="5"><img src="/common/img/spacer.gif" alt="" width="1" height="10"></td>
                        </tr>
                    <?php } ?>
                    <?php if ($e->hasErrorForId('top_conflict')) { ?>
                        <tr>
                            <td class="red" colspan="5"><?php echo $e->getMessage('top_conflict'); ?></td>
                        </tr>
                        <tr>
                            <td colspan="5"><img src="/common/img/spacer.gif" alt="" width="1" height="10"></td>
                        </tr>
                    <?php } ?>
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
                                    $leftCount = ceil($to_area_count / 2);
                                    for($i=0;$i<$leftCount;$i++){
                                        echo "<tr>\n";
                                        echo "    <td rowspan='2'>\n";
                                        echo "        {$to_area_lbls[$i]}\n";
                                        echo "        <input type='hidden' value='{$to_area_cds[$i]}' name='to_area_cds[{$i}]'>\n";
                                        echo "    </td>\n";

                                        // 基本
                                        if($e->hasErrorForId('base_prices_' . $i)){
                                            echo "<td rowspan='2' class='bg_red'>\n";
                                        }else{
                                            echo "<td rowspan='2'>\n";
                                        }
                                        if($base_prices[$i] === $orig_base_prices[$i]){
                                            $class = 'price_i';
                                        }else{
                                            $class = 'price_i bg_red';
                                        }
                                        echo "        <input type='text' value='{$base_prices[$i]}' class='{$class}' name='base_prices[{$i}]' onBlur='priceCheck(this, {$orig_base_prices[$i]});'>\n";
                                        echo "    </td>\n";

                                        // 上限
                                        if($e->hasErrorForId('max_prices_' . $i)){
                                            echo "<td class='bg_red'>\n";
                                        }else{
                                            echo "<td>\n";
                                        }
                                        if($max_prices[$i] === $orig_max_prices[$i]){
                                            $class = 'price_i';
                                        }else{
                                            $class = 'price_i bg_red';
                                        }
                                        echo "        <input type='text' value='{$max_prices[$i]}' class='{$class}' name='max_prices[{$i}]' onBlur='priceCheck(this, {$orig_max_prices[$i]});'>\n";
                                        echo "    </td>\n";
                                        echo "</tr>\n";

                                        // 下限
                                        echo "<tr>\n";
                                        if($e->hasErrorForId('min_prices_' . $i)){
                                            echo "<td class='bg_red'>\n";
                                        }else{
                                            echo "<td>\n";
                                        }
                                        if($min_prices[$i] === $orig_min_prices[$i]){
                                            $class = 'price_i';
                                        }else{
                                            $class = 'price_i bg_red';
                                        }
                                        echo "        <input type='text' value='{$min_prices[$i]}' class='{$class}' name='min_prices[{$i}]' onBlur='priceCheck(this, {$orig_min_prices[$i]});'>\n";
                                        echo "    </td>\n";
                                        echo "</tr>\n";
                                    }
                                ?>
    						</table>
    					</td>
    					<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
    					<td valign="top">
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
                                    for($i=$leftCount;$i<$to_area_count;$i++){
                                        echo "<tr>\n";
                                        echo "    <td rowspan='2'>\n";
                                        echo "        {$to_area_lbls[$i]}\n";
                                        echo "        <input type='hidden' value='{$to_area_cds[$i]}' name='to_area_cds[{$i}]'>\n";
                                        echo "    </td>\n";

                                        // 基本
                                        if($e->hasErrorForId('base_prices_' . $i)){
                                            echo "<td rowspan='2' class='bg_red'>\n";
                                        }else{
                                            echo "<td rowspan='2'>\n";
                                        }
                                        if($base_prices[$i] === $orig_base_prices[$i]){
                                            $class = 'price_i';
                                        }else{
                                            $class = 'price_i bg_red';
                                        }
                                        echo "        <input type='text' value='{$base_prices[$i]}' class='{$class}' name='base_prices[{$i}]' onBlur='priceCheck(this, {$orig_base_prices[$i]});'>\n";
                                        echo "    </td>\n";

                                        // 上限
                                        if($e->hasErrorForId('max_prices_' . $i)){
                                            echo "<td class='bg_red'>\n";
                                        }else{
                                            echo "<td>\n";
                                        }
                                        if($max_prices[$i] === $orig_max_prices[$i]){
                                            $class = 'price_i';
                                        }else{
                                            $class = 'price_i bg_red';
                                        }
                                        echo "        <input type='text' value='{$max_prices[$i]}' class='{$class}' name='max_prices[{$i}]' onBlur='priceCheck(this, {$orig_max_prices[$i]});'>\n";
                                        echo "    </td>\n";
                                        echo "</tr>\n";

                                        // 下限
                                        echo "<tr>\n";
                                        if($e->hasErrorForId('min_prices_' . $i)){
                                            echo "<td class='bg_red'>\n";
                                        }else{
                                            echo "<td>\n";
                                        }
                                        if($min_prices[$i] === $orig_min_prices[$i]){
                                            $class = 'price_i';
                                        }else{
                                            $class = 'price_i bg_red';
                                        }
                                        echo "        <input type='text' value='{$min_prices[$i]}' class='{$class}' name='min_prices[{$i}]' onBlur='priceCheck(this, {$orig_min_prices[$i]});'>\n";
                                        echo "    </td>\n";
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
    					<td colspan="5" align="center"><input type="image" src="/common/img/acf/btn_confirm.gif" alt="確認する" name="confirm_btn" id="confirm_btn"></td>
    				</tr>
    			</table>
    		</td>
    		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
    	</tr>
    <?php }// 料金部表示非表示 ?>
</table>
<input type='hidden' name='ticket' value='<?php echo $ticket ?>' />
<input type='hidden' name='formareacd' />
</form>
</div><!-- /#topWrap -->

<div id="footer">
<address><img src="/common/img/txt_copyright.gif" alt="&copy; SG Moving Co.,Ltd. All Rights Reserved."></address>
</div><!-- /#footer -->

</body>
</html>

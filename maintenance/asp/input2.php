<?php
/**
 * 特価編集発着地入力画面を表示します。
 * @package    maintenance
 * @subpackage ASP
 * @author     M.Shiiba(TPE)
 * @author     H.Tsuji(SCS) カーゴコースを選択した場合の各種チェックボックスの活性・非活性処理の追加
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('asp/Input2');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Asp_Input2();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Asp005Out
 */
$asp005Out = $forms['outForm'];

/**
 * エラーフォーム
 * @var Sgmov_Form_Error
 */
$e = $forms['errorForm'];

//　選択コースコードを取得
$course_plan_sel_cds = $asp005Out->course_plan_sel_cds();

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
<link href="/common/css/form.css" rel="stylesheet" type="text/css" media="screen,tv,projection,print">
<link href="/common/css/top_main.css" rel="stylesheet" type="text/css" media="screen,tv,projection,print">
<script type="text/javascript" src="/common/js/jquery.js"></script>
<script type="text/javascript" src="/common/js/enterDisable.js"></script>
<?php
// 本社ユーザーか否かで使用するJavaScriptを切り替える
if ($asp005Out->honsha_user_flag()) {
	echo '<script type="text/javascript" src="/common/js/cargoOperationHonsha.js"></script>';
} else {
	if (Sgmov_Component_Session::get()->loadLoginUser()->centerId == '2') {
		echo '<script type="text/javascript" src="/common/js/cargoOperationSapporo.js"></script>';
	} else if (Sgmov_Component_Session::get()->loadLoginUser()->centerId == '4') {
		echo '<script type="text/javascript" src="/common/js/cargoOperationTokyo.js"></script>';
	} else if (Sgmov_Component_Session::get()->loadLoginUser()->centerId == '15') {
		echo '<script type="text/javascript" src="/common/js/cargoOperationFukuoka.js"></script>';
	} else if (Sgmov_Component_Session::get()->loadLoginUser()->centerId == '17') {
		echo '<script type="text/javascript" src="/common/js/cargoOperationOkinawa.js"></script>';
	} else {
		echo '<script type="text/javascript" src="/common/js/cargoOperationSonota.js"></script>';
	}
}
?>
<script type="text/javascript">
<!--

    /** コースプラン全選択 */
   function checkAllCoursePlans(){
      	beforeCheck1_1 = document.getElementById("1_1").checked;
		beforeCheck1_2 = document.getElementById("1_2").checked;
		
		if (!beforeCheck1_2) {
			$("[@name='course_plan_sel_cds[]']").attr("checked", "checked");
		}

        // 単身カーゴプラン・単身エアカーゴプランを非活性化する
        allCourcePlanBtn(true, beforeCheck1_1, beforeCheck1_2);
   }

    /** コースプラン非選択 */
   function uncheckAllCoursePlans(){
   		beforeCheck1_1 = document.getElementById("1_1").checked;
		beforeCheck1_2 = document.getElementById("1_2").checked;
        $("[@name='course_plan_sel_cds[]']").attr("checked", "");
        // 単身カーゴプラン・単身エアカーゴプランを活性化する
        allCourcePlanBtn(false, beforeCheck1_1, beforeCheck1_2);
   }

    /** 出発エリア全選択 */
   function checkAllFromArea(){
        $("input[@id^='from_center_']").attr("checked", "checked");
        $("input[@id^='area_from_center_']").attr("checked", "checked");
        // ONLOAD処理
        inputOnload("");
   }

    /** 出発エリア非選択 */
   function uncheckAllFromArea(){
        $("input[@id^='from_center_']").attr("checked", "");
        $("input[@id^='area_from_center_']").attr("checked", "");
        // ONLOAD処理
        inputOnload("");
   }

    /** 到着エリア全選択 */
   function checkAllToArea(){
        $("input[@id^='to_center_']").attr("checked", "checked");
        $("input[@id^='area_to_center_']").attr("checked", "checked");
        // ONLOAD処理
        inputOnload("");
   }

    /** 到着エリア非選択 */
   function uncheckAllToArea(){
        $("input[@id^='to_center_']").attr("checked", "");
        $("input[@id^='area_to_center_']").attr("checked", "");
        // ONLOAD処理
        inputOnload("");
   }

    /** 拠点チェックボックスクリック時 */
    function onCenterClicked(centerObj){
        var elem = $(centerObj);
        var checked = elem.attr("checked");
        var areaIdPrefix = "area_" + elem.attr("id") + "_";
        $("input[@id^='" + areaIdPrefix + "']").attr("checked", checked);
        // ONLOAD処理
        inputOnload("");
    }

    /** エリアチェックボックスクリック時 */
    function onAreaClicked(centerId, centerIdSyosai){
        var areaIdPrefix = "area_" + centerId + "_";
        var areaCount = $("input[@id^='" + areaIdPrefix + "']").length;
        var areaCheckedCount = $("input[@id^='" + areaIdPrefix + "']:checked").length;
        if(areaCount == areaCheckedCount){
            $("#" + centerId).attr("checked", "checked");
        }else{
            $("#" + centerId).attr("checked", "");
        }
        // 単身カーゴプランまたは単身エアカーゴの活性・非活性処理
        clickArea(centerIdSyosai);
    }

    $(function() {
        // 一覧に戻るリンククリック時
        $("#back_to_list").click(function(){
            if(!confirm("入力を中止して一覧に戻ります。\編集中の内容は破棄されますがよろしいですか？")){
                // キャンセル
                return false;
            }
        });

        // 初期表示時の拠点チェックボックスの設定
        $("input[@id^='from_center_']").each(function(){
            onAreaClicked($(this).attr("id"), null);
        });
        $("input[@id^='to_center_']").each(function(){
            onAreaClicked($(this).attr("id"), null);
        });
    });

-->
</script>

</head>

<?php if($asp005Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA){ // 閑散繁忙期 ?>
<body id="priceCat">
<?php } else if($asp005Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN){ // キャンペーン ?>
<body id="campaignCat" onload="inputOnload(
<?php
if (in_array("1_1", $course_plan_sel_cds)) {
	echo 1;
} else {
	echo 2;
}
?>)">
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
<?php if($asp005Out->honsha_user_flag() === '1'){ ?>
  <li class="nav02"><a href="/acf/input"><img src="/common/img/nav_global02.gif" alt="料金マスタ設定" width="131" height="41"></a></li>
<?php }else{ ?>
  <li class="nav02"><img src="/common/img/nav_global02_off.gif" alt="料金マスタ設定" width="131" height="41"></li>
<?php } ?>
<li class="nav03"><a href="/asp/list/extra"><img src="/common/img/nav_global03.gif" alt="閑散・繁忙期料金設定" width="169" height="41"></a></li>
<li class="nav04"><a href="/asp/list/campaign"><img src="/common/img/nav_global04.gif" alt="キャンペーン特価設定" width="168" height="41"></a></li>
<?php if($asp005Out->honsha_user_flag() === '1'){ ?>
<li class="nav05"><a href="/aoc/list"><img src="/common/img/nav_global05.gif" alt="他社連携キャンペーン設定" width="242" height="41"></a></li>
<?php }else{ ?>
<li class="nav05"><img src="/common/img/nav_global05_off.gif" alt="他社連携キャンペーン設定" width="242" height="41"></li>
<?php } ?><li class="nav06"><a href="/acm/logout"><img src="/common/img/nav_global06.gif" alt="ログアウト" width="99" height="41"></a></li>
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

<?php if($asp005Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA){ ?>
    <form method='post' action='/asp/check_input2/extra'>
<?php } else if($asp005Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN){ ?>
    <form method='post' action='/asp/check_input2/campaign'>
<?php } ?>
<table width="900">
<?php if($asp005Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA){ ?>
	<tr>
		<td colspan="3"><h2><img src="/common/img/asp/ttl_price_01.gif" alt="閑散・繁忙期料金設定"></h2></td>
	</tr>
<?php } else if($asp005Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN){ ?>
    <tr>
        <td colspan="3"><h2><img src="/common/img/asp/ttl_campaign_01.gif" alt="キャンペーン設定"></h2></td>
    </tr>
<?php } ?>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
	</tr>
	<tr>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
        <td align="right"><a id='back_to_list' href="<?php echo $asp005Out->sp_list_url(); ?>">← 一覧に戻る</a></td>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
	</tr>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
	</tr>
	<tr>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
		<td class="ttl">コース・発着エリア設定</td>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
	</tr>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
	</tr>
    <?php if ($e->hasError()) { ?>
        <?php if ($e->hasErrorForId('top_cource_plan_from_to')) { ?>
            <tr>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                <td width="880" align="left" class="red"><?php echo $e->getMessage('top_cource_plan_from_to'); ?></td>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
            </tr>
        <?php } ?>
        <?php if ($e->hasErrorForId('top_course_plan_sel_cds')) { ?>
            <tr>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                <td width="880" align="left" class="red">コース・プラン<?php echo $e->getMessage('top_course_plan_sel_cds'); ?></td>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
            </tr>
        <?php } ?>
        <?php if ($e->hasErrorForId('top_from_area_sel_cds')) { ?>
            <tr>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                <td width="880" align="left" class="red">出発エリア<?php echo $e->getMessage('top_from_area_sel_cds'); ?></td>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
            </tr>
        <?php } ?>
        <?php if ($e->hasErrorForId('top_to_area_sel_cds')) { ?>
            <tr>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                <td width="880" align="left" class="red">到着エリア<?php echo $e->getMessage('top_to_area_sel_cds'); ?></td>
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
					<td width="860" align="right"><span class="orange">★</span>は必須入力項目です。</td>
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
		<td>
			<table class="inner" width="880">
				<tr>
					<td><img src="/common/img/spacer.gif" width="7" height="1" alt=""></td>
					<td>
						<table width="860" class="bdr">
                            <?php
                                // 呼び出すたびにエンティティ化されるので先に取得しておく
                                $course_lbls = $asp005Out->course_lbls();
                                $course_plan_cds = $asp005Out->course_plan_cds();
                                $plan_lbls = $asp005Out->plan_lbls();
                                //$course_plan_sel_cds = $asp005Out->course_plan_sel_cds(); //onloadで値を使用するので上に移動
                                $count1 = count($course_lbls);
                            ?>
							<tr>
								<th rowspan="<?php echo $count1 + 2; ?>" width="150" class="lt"><span class="orange">★</span>コース・プラン</th>
								<td>
									<table>
										<tr>
											<td>コース・プランを選択してください。（複数可）</td>
											<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
											<td><a onClick="checkAllCoursePlans();"><img src="/common/img/asp/btn_cource_plan_select.gif" class="cursor_hand" alt="全コース・プラン選択"></a></td>
											<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
											<td><a onClick="uncheckAllCoursePlans();"><img src="/common/img/asp/btn_cource_plan_cancel.gif" class="cursor_hand" alt="全コース・プラン解除"></a></td>
										</tr>
									</table>
								</td>
							</tr>
                            <?php
                            // コース/プランの全HTML要素（ID）
                            $cource_ids = "";
                            // 単身カーゴプランHTML・単身エアカーゴプランHTML
                            $htmlCargoPlan = array("", "");
                            // 単身カーゴプランHTML・単身エアカーゴプランHTML
                            $htmlCargoPlanOrder = array(1, 0);
                                                        
                                for($i=0; $i<$count1; $i++){
                                    echo "<tr><td>{$course_lbls[$i]}<br>\n";
                                    echo "<table><tr>\n";

                                    $count2 = count($course_plan_cds[$i]);
                                    $endTr = "";
                                    
                                    if (substr($course_plan_cds[$i][0], 0, 1) == Sgmov_View_Asp_Common::COURCE_CARGO_ID) {
                                    	
					// 「Sgmov_View_Asp_Common::getCourceComment()」のSgmov_View_Asp_Commonが抽象クラスのため、エラーが発生（古いバージョンはとおる）
					// していたため、以下の様に修正(getCourceCommentメソッド内部では以下のコードが実装されている)
					$courceComment = array(Sgmov_View_Asp_Common::TANSHIN_CARGO_PLAN_COMMENT, Sgmov_View_Asp_Common::TANSHIN_AIRCARGO_PLAN_COMMENT);
                                    	
                                    	// コースプランコードの1桁目（コースコード）が1の場合
                                    	for($j=0; $j<$count2; $j++){
	                                        $checked = '';
	                                        if(in_array($course_plan_cds[$i][$j], $course_plan_sel_cds)){
	                                            $checked = 'checked=\'checked\'';
	                                        }
	                                        $htmlCargoPlan[$htmlCargoPlanOrder[$j]] .= "<td>\n";
	                                        $htmlCargoPlan[$htmlCargoPlanOrder[$j]] .= "<table border=\"0\">\n<tr>\n<td width='230px'><input type='checkbox' name='course_plan_sel_cds[]' value='{$course_plan_cds[$i][$j]}' id='{$course_plan_cds[$i][$j]}' onClick='clickTanshinCargoPlan(\"{$course_plan_cds[$i][$j]}\")' {$checked}>{$plan_lbls[$i][$j]}</td>\n";
	                                        $htmlCargoPlan[$htmlCargoPlanOrder[$j]] .= "<td width='470px'>{$courceComment[$j]}</td>\n</tr>\n</table>\n";
	                                        $htmlCargoPlan[$htmlCargoPlanOrder[$j]] .= "</td>\n</tr>\n";
	                                        
	                                        $cource_ids .= $course_plan_cds[$i][$j].':';
	                                        //echo "<td><input type='checkbox' name='course_plan_sel_cds[]' value='{$course_plan_cds[$i][$j]}' {$checked}>{$plan_lbls[$i][$j]}{$courceComment[$j]}</td></tr>\n<tr>";
	                                    }
	                                    echo $htmlCargoPlan[0]."</table>\n</td>\n</tr>\n<tr>\n<td>{$course_lbls[$i]}<br>\n<table>\n<tr>\n".$htmlCargoPlan[1];
                                    } else {
	                                    for($j=0; $j<$count2; $j++){
	                                        $checked = '';
	                                        if(in_array($course_plan_cds[$i][$j], $course_plan_sel_cds)){
	                                            $checked = 'checked=\'checked\'';
	                                        }
	                                        echo "<td><input type='checkbox' name='course_plan_sel_cds[]' value='{$course_plan_cds[$i][$j]}' id='{$course_plan_cds[$i][$j]}' {$checked}>{$plan_lbls[$i][$j]}</td>\n";
	                                    	$cource_ids .= $course_plan_cds[$i][$j].':';
	                                    }
	                                    $endTr = "</tr>";
                                    }

                                    echo $endTr."</table>\n";
                                    echo "</td></tr>\n";
                                }
                            ?>
                            <?php
                                // 呼び出すたびにエンティティ化されるので先に取得しておく
                                $from_center_lbls = $asp005Out->from_center_lbls();
                                $from_area_cds = $asp005Out->from_area_cds();
                                $from_area_lbls = $asp005Out->from_area_lbls();
                                $from_area_sel_cds = $asp005Out->from_area_sel_cds();
                                $count1 = count($from_center_lbls);

                                $rows = $count1 + 1;
                                // 本社で東京営業所があれば千葉営業所を出すので行数は1増える
                                //if($asp005Out->honsha_user_flag() === '1' && in_array('東京営業所', $from_center_lbls)){
                                //    $rows++;
                                //}
                            ?>
							<tr>
								<th rowspan="<?php echo $rows ?>" class="lt"><span class="orange">★</span>出発エリア</th>
								<td>
									<table>
										<tr>
											<td>出発エリアを選択してください。（複数可）</td>
											<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                                            <?php if($asp005Out->honsha_user_flag() === '1'){ ?>
    											<td><a onClick="checkAllFromArea();"><img src="/common/img/asp/btn_sarea_select.gif" class="cursor_hand" alt="全出発エリア選択"></a></td>
    											<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
    											<td><a onClick="uncheckAllFromArea();"><img src="/common/img/asp/btn_sarea_cancel.gif" class="cursor_hand" alt="全出発エリア解除"></a></td>
                                            <?php } ?>
										</tr>
									</table>
								</td>
                            </tr>
                            <?php
                            
                                // 出発地域の全HTML要素（ID）
                            	$fromarea_ids = "";

                                for($i=0; $i<$count1; $i++){
                                    echo "<tr><td><input id='from_center_{$i}' type='checkbox' onClick='onCenterClicked(this);'>{$from_center_lbls[$i]}<br>\n";
                                    echo "<table><tr>\n";
                                    
									$fromarea_ids .= 'from_center_'.$i.':';
									
                                    $count2 = count($from_area_cds[$i]);
                                    for($j=0; $j<$count2; $j++){
                                        $checked = '';
                                        if(in_array($from_area_cds[$i][$j], $from_area_sel_cds)){
                                            $checked = 'checked=\'checked\'';
                                        }
                                        echo "<td><input id='area_from_center_{$i}_{$j}' type='checkbox' name='from_area_sel_cds[]' value='{$from_area_cds[$i][$j]}' {$checked} onClick=\"onAreaClicked('from_center_{$i}', 'area_from_center_{$i}_{$j}');\">{$from_area_lbls[$i][$j]}</td>\n";
                                    	$fromarea_ids .= 'area_from_center_'.$i.'_'.$j.':';
                                    }

                                    echo "</tr></table>\n";
                                    echo "</td></tr>\n";

                                    // 東京営業所の下には千葉営業所を出す
                                    /*
                                    if($asp005Out->honsha_user_flag() === '1' && $from_center_lbls[$i] === '東京営業所'){
                                        echo "<tr>";
                                        echo "    <td>千葉営業所<br>";
                                        echo "        <table>";
                                        echo "            <tr>";
                                        echo "                <td>東京営業所にて対応</td>";
                                        echo "            </tr>";
                                        echo "        </table>";
                                        echo "    </td>";
                                        echo "</tr>";
                                    }
                                    */
                                }
                            ?>
                            <?php
                                // 呼び出すたびにエンティティ化されるので先に取得しておく
                                $to_center_lbls = $asp005Out->to_center_lbls();
                                $to_area_cds = $asp005Out->to_area_cds();
                                $to_area_lbls = $asp005Out->to_area_lbls();
                                $to_area_sel_cds = $asp005Out->to_area_sel_cds();
                                $count1 = count($to_center_lbls);

                                $rows = $count1 + 1;
                                // 本社で東京営業所があれば千葉営業所を出すので行数は1増える(到着エリアは権限に関係ない)
                                //if(in_array('東京営業所', $to_center_lbls)){
                                //    $rows++;
                                //}
                            ?>
							<tr>
								<th rowspan="<?php echo $rows; ?>" class="lt"><span class="orange">★</span>到着エリア</th>
								<td>
									<table>
										<tr>
											<td>到着エリアを選択してください。（複数可）</td>
											<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
											<td><a onClick="checkAllToArea();"><img src="/common/img/asp/btn_garea_select.gif" class="cursor_hand" alt="全到着エリア選択"></a></td>
											<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
											<td><a onClick="uncheckAllToArea();"><img src="/common/img/asp/btn_garea_cancel.gif" class="cursor_hand" alt="全到着エリア解除"></a></td>
										</tr>
									</table>
								</td>
                            </tr>
                            <?php
                            
                                // 出発地域の全HTML要素（ID）
                            	$toarea_ids = "";
                            	
                                for($i=0; $i<$count1; $i++){
                                    echo "<tr><td><input id='to_center_{$i}' type='checkbox' onClick='onCenterClicked(this);'>{$to_center_lbls[$i]}<br>\n";
                                    echo "<table><tr>\n";
                                    
									$toarea_ids .= 'to_center_'.$i.':';
									
                                    $count2 = count($to_area_cds[$i]);
                                    for($j=0; $j<$count2; $j++){
                                        $checked = '';
                                        if(in_array($to_area_cds[$i][$j], $to_area_sel_cds)){
                                            $checked = 'checked=\'checked\'';
                                        }
                                        echo "<td><input id='area_to_center_{$i}_{$j}' type='checkbox' name='to_area_sel_cds[]' value='{$to_area_cds[$i][$j]}' {$checked} onClick=\"onAreaClicked('to_center_{$i}');\">{$to_area_lbls[$i][$j]}</td>\n";
                                        $toarea_ids .= 'area_to_center_'.$i.'_'.$j.':';
                                    }

                                    echo "</tr></table>\n";
                                    echo "</td></tr>\n";

                                    // 東京営業所の下には千葉営業所を出す
                                    /*
                                    if($to_center_lbls[$i] === '東京営業所'){
                                        echo "<tr>";
                                        echo "    <td>千葉営業所<br>";
                                        echo "        <table>";
                                        echo "            <tr>";
                                        echo "                <td>東京営業所にて対応</td>";
                                        echo "            </tr>";
                                        echo "        </table>";
                                        echo "    </td>";
                                        echo "</tr>";
                                    }
                                    */
                                }
                            ?>
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
			<table>
				<tr>
					<td colspan="3" align="center">
						<table>
							<tr>
                            <?php if($asp005Out->sp_kind() === '1'){ // 閑散繁忙期 ?>
								<td><input type="image" src="/common/img/asp/btn_next.gif" alt="次へ"></td>
								<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                                <td><a href="/asp/input1/extra"><img src="/common/img/asp/btn_back.gif" alt="戻る" /></a></td>
                            <?php } else if($asp005Out->sp_kind() === '2'){ // キャンペーン ?>
                                <td><input type="image" src="/common/img/asp/btn_next.gif" alt="次へ"></td>
                                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                                <td><a href="/asp/input1/campaign"><img src="/common/img/asp/btn_back.gif" alt="戻る" /></a></td>
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
<input type='hidden' name='ticket' value='<?php echo $ticket; ?>' />
<input type='hidden' name='cource_ids' value='<?php echo $cource_ids; ?>' />
<input type='hidden' name='fromarea_ids' value='<?php echo $fromarea_ids; ?>' />
<input type='hidden' name='toarea_ids' value='<?php echo $toarea_ids; ?>' />
</form>
<!-- /#topWrap --></div>

<div id="footer">

<address><img src="/common/img/txt_copyright.gif" alt="&copy; SG Moving Co.,Ltd. All Rights Reserved."></address>
<!-- /#footer --></div>

<!-- /#wrapper --></div>
</body>
</html>

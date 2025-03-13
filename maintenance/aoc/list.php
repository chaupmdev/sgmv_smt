<?php
/**
 * 特価一覧画面を表示します。
 * @package    maintenance
 * @subpackage AOC
 * @author     T.Aono(SGS)
 * @copyright  SG SYSTEMS CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('aoc/List');
/**#@-*/

// 処理を実行

$view = new Sgmov_View_Aoc_List();
$forms = $view->execute();

/**
 * フォーム
 * @var Sgmov_Form_Aoc001Out
 */
$aoc001Out = $forms['outForm'];

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

<body id="othercompanyCat">

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

<?php if($aoc001Out->honsha_user_flag() === '1'){ ?>
<li class="nav02"><a href="/acf/input"><img src="/common/img/nav_global02.gif" alt="料金マスタ設定" width="131" height="41"></a></li>
<?php }else{ ?>
<li class="nav02"><img src="/common/img/nav_global02_off.gif" alt="料金マスタ設定" width="131" height="41"></li>
<?php } ?>
<li class="nav03"><a href="/asp/list/extra"><img src="/common/img/nav_global03.gif" alt="閑散・繁忙期料金設定" width="169" height="41"></a></li>
<li class="nav04"><a href="/asp/list/campaign"><img src="/common/img/nav_global04.gif" alt="キャンペーン特価設定" width="168" height="41"></a></li>
<?php if($aoc001Out->honsha_user_flag() === '1'){ ?>
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

<div id="topWrap">

<div class="helpNav">
<p><a id="contentTop" name="contentTop"></a>ここから本文です</p>
</div>

<table width="900">
    <tr>
        <td colspan="3"><h2><img src="/common/img/aoc/ttl_othercompany_01.gif" alt="他社連携キャンペーン設定"></h2></td>
    </tr>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
	</tr>
	<tr>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
		<td align="right">
   		    <form method="post" action="/aoc/input/">
       		    <input type="image" src="/common/img/aoc/btn_new.gif" alt="新規追加" name="create_btn">
                <input type='hidden' name='gamen_id' value='AOC001'>
            </form>
        </td>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
	</tr>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" alt="" width="1" height="10"></td>
	</tr>
    <tr>
        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
        <td class="ttl">他社連携キャンペーン一覧</td>
        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
    </tr>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" alt="" width="1" height="10"></td>
	</tr>
	<?php if (count($aoc001Out->oc_ids()) == 0) { ?>
    <tr>
        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
        <td width="880" align="left" class="red">他社連携キャンペーンはありません。</td>
        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
    </tr>
	<?php }else{ ?>
	<tr>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
		<td>
			<table class="inner" width="880">
				<tr>
					<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
					<td>
						<table width="860" class="bdr">
                            <tr>
                                <th width="80">登録日</th>
                                <th width="518">キャンペーン名称</th>
                                <th width="92">ステータス</th>
                                <th width="92">詳細・編集</th>
                            </tr>
                            
                        <?php
						    // 呼び出すたびにエンティティ化されるので先に取得しておく
						    $oc_ids          = $aoc001Out->oc_ids();
						    $oc_modifieds    = $aoc001Out->oc_modifieds();
						    $oc_names        = $aoc001Out->oc_names();
							$oc_applications = $aoc001Out->oc_applications();
						
						    $count = count($oc_names);
						    for($i=0;$i<$count;$i++) {
						        if($oc_applications[$i] === '2'){
						            $trClass = 'class="bg_gray"';
						        }else{
						            $trClass = '';
						        }
							?>
                            <tr <?php echo $trClass; ?>>
                                <td><?php echo date('Y/m/d', strtotime($oc_modifieds[$i])); ?></td>
                                <td><?php echo $oc_names[$i]; ?></td>
                                <td>
                                	<?php
                                	 if($oc_applications[$i] === '1'){
                                	 	echo "ON";
                                	 }elseif($oc_applications[$i] === '2'){
                                	 	echo "OFF";
                                	 }?>
                                </td>
                                 <td><a href="/aoc/input/<?php echo $oc_ids[$i]; ?>">▼編集</a></td>
                            </tr>
                        <?php } ?>
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
    <?php } ?>
	<tr>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
        <td align="right">
            <form method="post" action="/aoc/input/">
                <input type="image" src="/common/img/aoc/btn_new.gif" alt="新規追加" name="create_btn">
                <input type='hidden' name='gamen_id' value='AOC001'>
            </form>
		</td>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
	</tr>
</table>
</div><!-- /#topWrap -->

<div id="footer">
<address><img src="/common/img/txt_copyright.gif" alt="&copy; SG Moving Co.,Ltd. All Rights Reserved."></address>
</div><!-- /#footer -->

</div><!-- /#wrapper -->
</body>
</html>

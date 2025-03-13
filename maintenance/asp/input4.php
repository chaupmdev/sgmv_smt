<?php
/**
 * 金額設定方法選択画面を表示します。
 * @package    maintenance
 * @subpackage ASP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('asp/Input4');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Asp_Input4();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Asp007Out
 */
$asp007Out = $forms['outForm'];
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
    });
-->
</script>
</head>

<?php if($asp007Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA){ ?>
<body id="priceCat">
<?php } else if($asp007Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN){ ?>
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
<?php if($asp007Out->honsha_user_flag() === '1'){ ?>
  <li class="nav02"><a href="/acf/input"><img src="/common/img/nav_global02.gif" alt="料金マスタ設定" width="131" height="41"></a></li>
<?php }else{ ?>
  <li class="nav02"><img src="/common/img/nav_global02_off.gif" alt="料金マスタ設定" width="131" height="41"></li>
<?php } ?>
<li class="nav03"><a href="/asp/list/extra"><img src="/common/img/nav_global03.gif" alt="閑散・繁忙期料金設定" width="169" height="41"></a></li>
<li class="nav04"><a href="/asp/list/campaign"><img src="/common/img/nav_global04.gif" alt="キャンペーン特価設定" width="168" height="41"></a></li>
<?php if($asp007Out->honsha_user_flag() === '1'){ ?>
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
<?php if($asp007Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA){ ?>
    <tr>
        <td colspan="3"><h2><img src="/common/img/asp/ttl_price_01.gif" alt="閑散・繁忙期料金設定"></h2></td>
    </tr>
<?php } else if($asp007Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN){ ?>
    <tr>
        <td colspan="3"><h2><img src="/common/img/asp/ttl_campaign_01.gif" alt="キャンペーン設定"></h2></td>
    </tr>
<?php } ?>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
	</tr>
    <tr>
        <td width="10"><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
        <td width="880" align="right"><a id='back_to_list' href="<?php echo $asp007Out->sp_list_url(); ?>">← 一覧に戻る</a></td>
        <td width="10"><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
    </tr>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
	</tr>
	<tr>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
		<td class="ttl">料金設定の方法を選択してください</td>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
	</tr>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" alt="" width="1" height="10"></td>
	</tr>
<?php if($asp007Out->priceset_kbn() === Sgmov_View_Asp_Common::PRICESET_KBN_ALL) { ?>
    <tr>
        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
        <td>現在編集中の情報は「料金を一律で設定する」方法で設定されています。<br
        >その他の方法を選択すると料金情報が上書きされることに注意してください。
        </td>
        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
    </tr>
    <tr>
        <td colspan="3"><img src="/common/img/spacer.gif" alt="" width="1" height="10"></td>
    </tr>
<?php } else if($asp007Out->priceset_kbn() === Sgmov_View_Asp_Common::PRICESET_KBN_EACH){ ?>
    <tr>
        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
        <td>現在編集中の情報は「料金を個別に設定する」方法で設定されています。<br
        >その他の方法を選択すると料金情報が上書きされることに注意してください。
        </td>
        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
    </tr>
    <tr>
        <td colspan="3"><img src="/common/img/spacer.gif" alt="" width="1" height="10"></td>
    </tr>
<?php } ?>
    <tr>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
		<td>
			<table width="880">
				<tr>
					<td><img src="/common/img/spacer.gif" alt="" width="10" height="1"></td>
					<td align="left">
                        <?php if($asp007Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA){ ?>
                            <table width="860">
                                <tr>
                                    <td><input type="image" src="/common/img/asp/btn_pricesetup_01.gif" alt="料金を個別に設定する" onClick="onSubmit('/asp/input6/extra');"></td>
                                </tr>
                                <tr>
                                    <td><img src="/common/img/spacer.gif" alt="" width="1" height="10"></td>
                                </tr>
                                <tr>
                                    <td><input type="image" src="/common/img/asp/btn_pricesetup_02.gif" alt="料金を一律で設定する" onClick="onSubmit('/asp/input5/extra');"></td>
                                </tr>
                                <tr>
                                    <td><img src="/common/img/spacer.gif" alt="" width="1" height="10"></td>
                                </tr>
                                <tr>
                                    <td><input type="image" src="/common/img/asp/btn_pricesetup_03.gif" alt="料金の変更は行わない" onClick="onSubmit('/asp/check_input4/extra');"></td>
                                </tr>
                            </table>
                        <?php } else if($asp007Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN){ ?>
                            <table width="860">
                                <tr>
                                    <td><input type="image" src="/common/img/asp/btn_pricesetup_01.gif" alt="料金を個別に設定する" onClick="onSubmit('/asp/input6/campaign');"></td>
                                </tr>
                                <tr>
                                    <td><img src="/common/img/spacer.gif" alt="" width="1" height="10"></td>
                                </tr>
                                <tr>
                                    <td><input type="image" src="/common/img/asp/btn_pricesetup_02.gif" alt="料金を一律で設定する" onClick="onSubmit('/asp/input5/campaign');"></td>
                                </tr>
                                <tr>
                                    <td><img src="/common/img/spacer.gif" alt="" width="1" height="10"></td>
                                </tr>
                                <tr>
                                    <td><input type="image" src="/common/img/asp/btn_pricesetup_03.gif" alt="料金の変更は行わない" onClick="onSubmit('/asp/check_input4/campaign');"></td>
                                </tr>
                            </table>
                        <?php } ?>
					</td>
					<td><img src="/common/img/spacer.gif" alt="" width="10" height="1"></td>
				</tr>
			</table>
		</td>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
	</tr>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" alt="" width="1" height="10"></td>
	</tr>
	<tr>
        <?php if($asp007Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA){ ?>
    		<td colspan="3"><a href="/asp/input3/extra"><img src="/common/img/asp/btn_back.gif" alt="戻る"></a></td>
        <?php } else if($asp007Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN){ ?>
            <td colspan="3"><a href="/asp/input3/campaign"><img src="/common/img/asp/btn_back.gif" alt="戻る"></a></td>
        <?php } ?>
	</tr>
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

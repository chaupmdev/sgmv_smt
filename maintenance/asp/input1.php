<?php
/**
 * 特価編集名称入力画面を表示します。
 * @package    maintenance
 * @subpackage ASP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__).'/../../lib/Lib.php';
Sgmov_Lib::useView('asp/Input1');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Asp_Input1();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Asp004Out
 */
$asp004Out = $forms['outForm'];

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
    });
-->
</script>

</head>

<?php if ($asp004Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA) { ?>
<body id="priceCat">
<?php } else
    if ($asp004Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN) {
?>
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
<?php if ($asp004Out->honsha_user_flag() === '1') { ?>
  <li class="nav02"><a href="/acf/input"><img src="/common/img/nav_global02.gif" alt="料金マスタ設定" width="131" height="41"></a></li>
<?php } else { ?>
  <li class="nav02"><img src="/common/img/nav_global02_off.gif" alt="料金マスタ設定" width="131" height="41"></li>
<?php } ?>
<li class="nav03"><a href="/asp/list/extra"><img src="/common/img/nav_global03.gif" alt="閑散・繁忙期料金設定" width="169" height="41"></a></li>
<li class="nav04"><a href="/asp/list/campaign"><img src="/common/img/nav_global04.gif" alt="キャンペーン特価設定" width="168" height="41"></a></li>
<?php if($asp004Out->honsha_user_flag() === '1'){ ?>
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

<?php if ($asp004Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA) { ?>
    <form method='post' action='/asp/check_input1/extra'>
    <table width="900">
        <tr>
            <td colspan="3"><h2><img src="/common/img/asp/ttl_price_01.gif" alt="閑散・繁忙期料金設定"></h2></td>
        </tr>
        <tr>
            <td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
        </tr>
        <tr>
            <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
            <td align="right"><a id='back_to_list' href="<?php echo $asp004Out->sp_list_url(); ?>">← 一覧に戻る</a></td>
            <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
        </tr>
        <tr>
            <td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
        </tr>
        <tr>
            <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
            <td class="ttl">名称設定</td>
            <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
        </tr>
        <tr>
            <td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
        </tr>
        <?php if (isset($e)) {
				if ($e->hasError()) {
					if ($e->hasErrorForId('top_name')) {
		?>
                <tr>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                    <td width="880" align="left" class="red">設定名称<?php echo $e->getMessage('top_name'); ?></td>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                </tr>
            <?php } ?>
            <?php if ($e->hasErrorForId('top_sp_regist_user')) { ?>
                <tr>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                    <td width="880" align="left" class="red">登録者名<?php echo $e->getMessage('top_sp_regist_user'); ?></td>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="3"><img src="/common/img/spacer.gif" alt="" width="1" height="10"></td>
            </tr>
        <?php } } ?>
        <tr>
            <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
            <td>
                <table class="inner" width="880">
                    <tr>
                        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                        <td><span class="orange">★</span>は必須入力項目です。</td>
                        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                    </tr>
                    <tr>
                        <td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
                    </tr>
                    <tr>
                        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                        <td>
                            <table width="860">
                                <tr>
                                    <td valign="top" class="sp bg_blue"><span class="orange">★</span>設定名称</td>
                                    <td class="sp"><input type="text" size="50" name="sp_name" value="<?php echo $asp004Out->sp_name(); ?>"><br>
                                    <span class="red">例）閑散（繁忙）設定（○○支社）</span></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
                                </tr>
                                <tr>
                                    <td valign="top" class="sp bg_blue"><span class="orange">★</span>登録者名</td>
                                    <td class="sp"><input type="text" size="50" name="sp_regist_user" value="<?php echo $asp004Out->sp_regist_user(); ?>"></td>
                                </tr>
                            </table>
                        </td>
                        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                    </tr>
                    <tr>
                        <td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
                    </tr>
                    <tr>
                        <td colspan="3" align="center">
                            <table>
                                <tr>
                                    <td><input type="image" src="/common/img/asp/btn_next.gif" alt="次へ"></td>
                                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                                    <td><a href="<?php echo $asp004Out->cancel_btn_url(); ?>"><img src="/common/img/asp/btn_cancel.gif" alt="キャンセル" /></a></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
        </tr>
    </table>
    <input type='hidden' name='ticket' value='<?php echo $ticket; ?>' />
    </form>
<?php } else
        if ($asp004Out->sp_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN) {
?>
    <form method='post' action='/asp/check_input1/campaign'>
    <table width="900">
    	<tr>
    		<td colspan="3"><h2><img src="/common/img/asp/ttl_campaign_01.gif" alt="キャンペーン設定"></h2></td>
    	</tr>
    	<tr>
    		<td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
    	</tr>
    	<tr>
    		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
    		<td align="right"><a id='back_to_list' href="<?php echo $asp004Out->sp_list_url(); ?>">← 一覧に戻る</a></td>
    		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
    	</tr>
    	<tr>
    		<td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
    	</tr>
    	<tr>
    		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
    		<td class="ttl">名称・広告テキスト設定</td>
    		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
    	</tr>
        <tr>
            <td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
        </tr>
        <?php if (isset($e)) {
                if ($e->hasError()) {
                    if ($e->hasErrorForId('top_name')) {
?>
                <tr>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                    <td width="880" align="left" class="red">キャンペーン名称<?php echo $e->getMessage('top_name'); ?></td>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                </tr>
            <?php } ?>
            <?php if ($e->hasErrorForId('top_sp_content')) { ?>
                <tr>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                    <td width="880" align="left" class="red">広告用テキスト<?php echo $e->getMessage('top_sp_content'); ?></td>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                </tr>
            <?php } ?>
            <?php if ($e->hasErrorForId('top_sp_regist_user')) { ?>
                <tr>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                    <td width="880" align="left" class="red">登録者名<?php echo $e->getMessage('top_sp_regist_user'); ?></td>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="3"><img src="/common/img/spacer.gif" alt="" width="1" height="10"></td>
            </tr>
        <?php }
            }
?>
        <tr>
            <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
            <td>
                <table class="inner" width="880">
                    <tr>
                        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                        <td><span class="orange">★</span>は必須入力項目です。</td>
                        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                    </tr>
                    <tr>
                        <td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
                    </tr>
                    <tr>
                        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                        <td>
                            <table width="860">
    							<tr>
    								<td valign="top" class="sp bg_blue"><span class="orange">★</span>キャンペーン名称</td>
    								<td class="sp" valign="top"><input type="text" size="50" name="sp_name" value="<?php echo $asp004Out->sp_name(); ?>"></td>
    							</tr>
    							<tr>
    								<td colspan="2"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
    							</tr>
    							<tr class="sp">
    								<td rowspan="2" valign="top" class="sp bg_blue"><span class="orange">★</span>広告用テキスト</td>
    								<td class="sp" valign="top">テンプレートから引用<br>
                                        <script type="text/javascript">
                                        <!--
                                            $(function() {
                                                // テンプレートプルダウンを変更した場合
                                                $("#template_select").change(function(){
                                                    var index = this.selectedIndex;
                                                    var value = "";
                                                    if(index == 1){
                                                        value = "日ごろのご愛顧に感謝し、キャンペーンを実施いたします！\n期間中に対象となるコース・プラン（単身カーゴプラン、単身AIR CARGOプランを除く）をご利用いただきますと、全国どこからでも一律3,000円割引となります。\nぜひご利用ください。";
                                                    }else if(index == 2){
                                                        value = "都内の近距離引越しをご利用のお客様へキャンペーンを実施いたします！\n期間中に都内で引越しをされるお客様には、通常有料となるエアコンの取り付け・取り外しを2台まで無料とさせていただきます。（3台目からは費用をいただきます。）\nぜひご利用ください。";
                                                    }else if(index == 3){
                                                        value = "○○感謝祭を開催中！○○地域からお引越しされるお客様を対象に、5,000円キャッシュバックを開催中です！\n一部のコース・プランではご利用いただけませんので対象となる期間・コース・プランをよくご確認のうえ、ぜひご利用ください。";
                                                    }else{
                                                        return;
                                                    }

                                                    $("#sp_content").val(value);
                                                });
                                            });
                                        -->
                                        </script>
    									<select id="template_select" name="template_select">
    									<option value="" selected></option>
    									<option value="">サンプル1</option>
    									<option value="">サンプル2</option>
    									<option value="">サンプル3</option>
    									</select>
    								</td>
    							</tr>
    							<tr>
    								<td class="sp" valign="top">600文字まででお願いします。<br>
    									<textarea id="sp_content" name="sp_content" rows="3" cols="50" wrap="hard"><?php echo $asp004Out->sp_content(); ?></textarea>
    								</td>
    							</tr>
    							<tr>
    								<td colspan="2"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
    							</tr>
    							<tr>
    								<td valign="top" class="sp bg_blue"><span class="orange">★</span>登録者名</td>
                                    <td class="sp" valign="top"><input type="text" size="50" name="sp_regist_user" value="<?php echo $asp004Out->sp_regist_user(); ?>"></td>
    							</tr>
    						</table>
    					</td>
    					<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
    				</tr>
    				<tr>
    					<td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
    				</tr>
    				<tr>
    					<td colspan="3" align="center">
    						<table>
    							<tr>
    								<td><input type="image" src="/common/img/asp/btn_next.gif" alt="次へ" ></td>
    								<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
    								<td><a href="<?php echo $asp004Out->cancel_btn_url(); ?>"><img src="/common/img/asp/btn_cancel.gif" alt="キャンセル" /></a></td>
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
    </form>
<?php } ?>
<!-- /#topWrap --></div>

<div id="footer">

<address><img src="/common/img/txt_copyright.gif" alt="&copy; SG Moving Co.,Ltd. All Rights Reserved."></address>
<!-- /#footer --></div>

<!-- /#wrapper --></div>
</body>
</html>

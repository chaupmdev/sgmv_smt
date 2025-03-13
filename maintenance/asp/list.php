<?php
/**
 * 特価一覧画面を表示します。
 * @package    maintenance
 * @subpackage ASP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('asp/List');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Asp_List();
$forms = $view->execute();

/**
 * フォーム
 * @var Sgmov_Form_Asp001Out
 */
$asp001Out = $forms['outForm'];
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

<?php if($asp001Out->sp_list_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA){ ?>
<body id="priceCat">
<?php } else if($asp001Out->sp_list_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN){ ?>
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
<?php if($asp001Out->honsha_user_flag() === '1'){ ?>
  <li class="nav02"><a href="/acf/input"><img src="/common/img/nav_global02.gif" alt="料金マスタ設定" width="131" height="41"></a></li>
<?php }else{ ?>
  <li class="nav02"><img src="/common/img/nav_global02_off.gif" alt="料金マスタ設定" width="131" height="41"></li>
<?php } ?>
<li class="nav03"><a href="/asp/list/extra"><img src="/common/img/nav_global03.gif" alt="閑散・繁忙期料金設定" width="169" height="41"></a></li>
<li class="nav04"><a href="/asp/list/campaign"><img src="/common/img/nav_global04.gif" alt="キャンペーン特価設定" width="168" height="41"></a></li>
<?php if($asp001Out->honsha_user_flag() === '1'){ ?>
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
<?php if($asp001Out->sp_list_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA){ ?>
    <tr>
        <td colspan="3"><h2><img src="/common/img/asp/ttl_price_01.gif" alt="閑散・繁忙期料金設定"></h2></td>
    </tr>
<?php } else if($asp001Out->sp_list_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN){ ?>
    <tr>
        <td colspan="3"><h2><img src="/common/img/asp/ttl_campaign_01.gif" alt="キャンペーン特価設定"></h2></td>
    </tr>
<?php } ?>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
	</tr>
	<tr>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
		<td align="left">
			<table class="tabmenu">
<?php if($asp001Out->sp_list_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA){ ?>
    <?php if($asp001Out->sp_list_view_mode() === Sgmov_View_Asp_Common::SP_LIST_VIEW_OPEN) { ?>
                    <tr>
                        <td class="bg_act"><a>公開中</a></td>
                        <td><a href="/asp/list/extra/draft">下書き</a></td>
                        <td><a href="/asp/list/extra/close">終了</a></td>
                    </tr>
    <?php } else if($asp001Out->sp_list_view_mode() === Sgmov_View_Asp_Common::SP_LIST_VIEW_DRAFT) { ?>
                    <tr>
                        <td><a href="/asp/list/extra/open">公開中</a></td>
                        <td class="bg_red"><a>下書き</a></td>
                        <td><a href="/asp/list/extra/close">終了</a></td>
                    </tr>
    <?php } else if($asp001Out->sp_list_view_mode() === Sgmov_View_Asp_Common::SP_LIST_VIEW_CLOSE) { ?>
                    <tr>
                        <td><a href="/asp/list/extra/open">公開中</a></td>
                        <td><a href="/asp/list/extra/draft">下書き</a></td>
                        <td class="bg_gray"><a>終了</a></td>
                    </tr>
    <?php } ?>
<?php } else if($asp001Out->sp_list_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN){ ?>
    <?php if($asp001Out->sp_list_view_mode() === Sgmov_View_Asp_Common::SP_LIST_VIEW_OPEN) { ?>
                    <tr>
                        <td class="bg_act"><a>公開中</a></td>
                        <td><a href="/asp/list/campaign/draft">下書き</a></td>
                        <td><a href="/asp/list/campaign/close">終了</a></td>
                    </tr>
    <?php } else if($asp001Out->sp_list_view_mode() === Sgmov_View_Asp_Common::SP_LIST_VIEW_DRAFT) { ?>
                    <tr>
                        <td><a href="/asp/list/campaign/open">公開中</a></td>
                        <td class="bg_red"><a>下書き</a></td>
                        <td><a href="/asp/list/campaign/close">終了</a></td>
                    </tr>
    <?php } else if($asp001Out->sp_list_view_mode() === Sgmov_View_Asp_Common::SP_LIST_VIEW_CLOSE) { ?>
                    <tr>
                        <td><a href="/asp/list/campaign/open">公開中</a></td>
                        <td><a href="/asp/list/campaign/draft">下書き</a></td>
                        <td class="bg_gray"><a>終了</a></td>
                    </tr>
    <?php } ?>
<?php } ?>
			</table>
		</td>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
	</tr>
	<tr>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
		<td align="right">
<?php if($asp001Out->sp_list_view_mode() !== Sgmov_View_Asp_Common::SP_LIST_VIEW_CLOSE){ ?>
    <?php if($asp001Out->sp_list_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA){ ?>
    		    <form method="post" action="/asp/input1/extra">
        		    <input type="image" src="/common/img/asp/btn_new.gif" alt="新規追加" name="create_btn">
                    <input type='hidden' name='sp_list_kind' value='<?php echo $asp001Out->sp_list_kind(); ?>' />
                    <input type='hidden' name='sp_list_view_mode' value='<?php echo $asp001Out->sp_list_view_mode(); ?>' />
                    <input type='hidden' name='gamen_id' value='ASP001'>
                </form>
    <?php } else if($asp001Out->sp_list_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN){ ?>
                <form method="post" action="/asp/input1/campaign">
                    <input type="image" src="/common/img/asp/btn_new.gif" alt="新規追加" name="create_btn">
                    <input type='hidden' name='sp_list_kind' value='<?php echo $asp001Out->sp_list_kind(); ?>' />
                    <input type='hidden' name='sp_list_view_mode' value='<?php echo $asp001Out->sp_list_view_mode(); ?>' />
                    <input type='hidden' name='gamen_id' value='ASP001'>
                </form>
    <?php } ?>
<?php } ?>
        </td>
		<td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
	</tr>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" alt="" width="1" height="10"></td>
	</tr>
<?php if($asp001Out->sp_list_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA){ ?>
    <?php if($asp001Out->sp_list_view_mode() === Sgmov_View_Asp_Common::SP_LIST_VIEW_OPEN) { ?>
        <tr>
            <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
            <td class="ttl">公開中の閑散・繁忙期料金設定</td>
            <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
        </tr>
    <?php } else if($asp001Out->sp_list_view_mode() === Sgmov_View_Asp_Common::SP_LIST_VIEW_DRAFT) { ?>
        <tr>
            <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
            <td class="ttl">下書きの閑散・繁忙期料金設定</td>
            <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
        </tr>
    <?php } else if($asp001Out->sp_list_view_mode() === Sgmov_View_Asp_Common::SP_LIST_VIEW_CLOSE) { ?>
        <tr>
            <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
            <td class="ttl">終了した閑散・繁忙期料金設定</td>
            <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
        </tr>
    <?php } ?>
<?php } else if($asp001Out->sp_list_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN){ ?>
    <?php if($asp001Out->sp_list_view_mode() === Sgmov_View_Asp_Common::SP_LIST_VIEW_OPEN) { ?>
        <tr>
            <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
            <td class="ttl">公開中のキャンペーン</td>
            <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
        </tr>
    <?php } else if($asp001Out->sp_list_view_mode() === Sgmov_View_Asp_Common::SP_LIST_VIEW_DRAFT) { ?>
        <tr>
            <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
            <td class="ttl">下書きのキャンペーン</td>
            <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
        </tr>
    <?php } else if($asp001Out->sp_list_view_mode() === Sgmov_View_Asp_Common::SP_LIST_VIEW_CLOSE) { ?>
        <tr>
            <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
            <td class="ttl">終了したキャンペーン</td>
            <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
        </tr>
    <?php } ?>
<?php } ?>
	<tr>
		<td colspan="3"><img src="/common/img/spacer.gif" alt="" width="1" height="10"></td>
	</tr>
<?php if (count($asp001Out->sp_cds()) == 0) { ?>
    <?php if($asp001Out->sp_list_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA){ ?>
        <?php if($asp001Out->sp_list_view_mode() === Sgmov_View_Asp_Common::SP_LIST_VIEW_OPEN) { ?>
            <tr>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                <td width="880" align="left" class="red">公開中の閑散・繁忙期料金設定はありません</td>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
            </tr>
        <?php } else if($asp001Out->sp_list_view_mode() === Sgmov_View_Asp_Common::SP_LIST_VIEW_DRAFT) { ?>
            <tr>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                <td width="880" align="left" class="red">下書きの閑散・繁忙期料金設定はありません</td>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
            </tr>
        <?php } else if($asp001Out->sp_list_view_mode() === Sgmov_View_Asp_Common::SP_LIST_VIEW_CLOSE) { ?>
            <tr>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                <td width="880" align="left" class="red">終了した閑散・繁忙期料金設定はありません</td>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
            </tr>
        <?php } ?>
    <?php } else if($asp001Out->sp_list_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN){ ?>
        <?php if($asp001Out->sp_list_view_mode() === Sgmov_View_Asp_Common::SP_LIST_VIEW_OPEN) { ?>
            <tr>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                <td width="880" align="left" class="red">公開中のキャンペーンはありません</td>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
            </tr>
        <?php } else if($asp001Out->sp_list_view_mode() === Sgmov_View_Asp_Common::SP_LIST_VIEW_DRAFT) { ?>
            <tr>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                <td width="880" align="left" class="red">下書きのキャンペーンはありません</td>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
            </tr>
        <?php } else if($asp001Out->sp_list_view_mode() === Sgmov_View_Asp_Common::SP_LIST_VIEW_CLOSE) { ?>
            <tr>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                <td width="880" align="left" class="red">終了したキャンペーンはありません</td>
                <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
            </tr>
        <?php } ?>
    <?php } ?>
<?php } else { ?>
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
                                <th width="80">担当</th>
                                <th width="110">名称</th>
                                <th width="170">期間</th>
                                <th width="170">出発エリア</th>
                                <th width="80">状況</th>
                                <th width="92">詳細参照</th>
                            </tr>
<?php
    // 呼び出すたびにエンティティ化されるので先に取得しておく
    $sp_cds = $asp001Out->sp_cds();
    $sp_charge_flags = $asp001Out->sp_charge_flags();
    $sp_created_dates = $asp001Out->sp_created_dates();
    $sp_charge_centers = $asp001Out->sp_charge_centers();
    $sp_names = $asp001Out->sp_names();
    $sp_periods = $asp001Out->sp_periods();
    $sp_from_areas = $asp001Out->sp_from_areas();
    $sp_detail_urls = $asp001Out->sp_detail_urls();

    $count = count($sp_cds);
    for($i=0;$i<$count;$i++) {
        if($sp_charge_flags[$i] === '1'){
            $trClass = 'class="bg_yellow"';
        }else{
            $trClass = '';
        }
?>
                            <tr <?php echo $trClass; ?>>
                                <td width="80"><?php echo $sp_created_dates[$i]; ?></td>
                                <td width="80"><?php echo $sp_charge_centers[$i]; ?></td>
                                <td width="110"><?php echo $sp_names[$i]; ?></td>
                                <td width="170"><?php echo $sp_periods[$i]; ?></td>
                                <td width="170"><?php echo $sp_from_areas[$i]; ?></td>
                                <?php if($asp001Out->sp_list_view_mode() === Sgmov_View_Asp_Common::SP_LIST_VIEW_OPEN) { ?>
                                    <td width="80" class="bg_act">公開中</td>
                                <?php } else if($asp001Out->sp_list_view_mode() === Sgmov_View_Asp_Common::SP_LIST_VIEW_DRAFT) { ?>
                                    <td width="80" class="bg_red">下書き</td>
                                <?php } else if($asp001Out->sp_list_view_mode() === Sgmov_View_Asp_Common::SP_LIST_VIEW_CLOSE) { ?>
                                    <td width="80" class="bg_gray">終了</td>
                                <?php } ?>
                                <td width="92"><a href="<?php echo $sp_detail_urls[$i]; ?>">▼詳細参照</a></td>
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
<?php if($asp001Out->sp_list_view_mode() !== Sgmov_View_Asp_Common::SP_LIST_VIEW_CLOSE){ ?>
    <?php if($asp001Out->sp_list_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_EXTRA){ ?>
                <form method="post" action="/asp/input1/extra">
                    <input type="image" src="/common/img/asp/btn_new.gif" alt="新規追加" name="create_btn">
                    <input type='hidden' name='sp_list_kind' value='<?php echo $asp001Out->sp_list_kind(); ?>' />
                    <input type='hidden' name='sp_list_view_mode' value='<?php echo $asp001Out->sp_list_view_mode(); ?>' />
                    <input type='hidden' name='gamen_id' value='ASP001'>
                </form>
    <?php } else if($asp001Out->sp_list_kind() === Sgmov_View_Asp_Common::SP_LIST_KIND_CAMPAIGN){ ?>
                <form method="post" action="/asp/input1/campaign">
                    <input type="image" src="/common/img/asp/btn_new.gif" alt="新規追加" name="create_btn">
                    <input type='hidden' name='sp_list_kind' value='<?php echo $asp001Out->sp_list_kind(); ?>' />
                    <input type='hidden' name='sp_list_view_mode' value='<?php echo $asp001Out->sp_list_view_mode(); ?>' />
                    <input type='hidden' name='gamen_id' value='ASP001'>
                </form>
    <?php } ?>
<?php } ?>
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

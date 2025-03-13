<?php
/**
 * 他社連携キャンペーン入力画面を表示します。
 * @package    maintenance
 * @subpackage AOC
 * @author     T.Aono(SGS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('aoc/Input');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Aoc_Input();
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
$aoc002Out = $forms['outForm'];

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
<script type="text/javascript" src="/common/js/acfCourcePlanCheck.js"></script>
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
<?php if ($aoc002Out->honsha_user_flag() === '1') { ?>
<li class="nav02"><a href="/acf/input"><img src="/common/img/nav_global02.gif" alt="料金マスタ設定" width="131" height="41"></a></li>
<?php } else { ?>
<li class="nav02"><img src="/common/img/nav_global02_off.gif" alt="料金マスタ設定" width="131" height="41"></li>
<?php } ?>
<li class="nav03"><a href="/asp/list/extra"><img src="/common/img/nav_global03.gif" alt="閑散・繁忙期料金設定" width="169" height="41"></a></li>
<li class="nav04"><a href="/asp/list/campaign"><img src="/common/img/nav_global04.gif" alt="キャンペーン特価設定" width="168" height="41"></a></li>
<?php if ($aoc002Out->honsha_user_flag() === '1') { ?>
<li class="nav05"><a href="/aoc/list"><img src="/common/img/nav_global05.gif" alt="他社連携キャンペーン設定" width="242" height="41"></a></li>
<?php } else { ?>
<li class="nav05"><img src="/common/img/nav_global05_off.gif" alt="他社連携キャンペーン設定" width="242" height="41"></li>
<?php
 } 
?>
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

<form method='post' action='/aoc/check_input/'>
    <table width="900">
        <tr>
            <td colspan="3"><h2><img src="/common/img/aoc/ttl_othercompany_01.gif" alt="他社連携キャンペーン設定"></h2></td>
        </tr>
        <tr>
            <td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
        </tr>
        <tr>
            <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
            <td align="right"><a href="/aoc/list/">← 一覧に戻る</a></td>
            <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
        </tr>
        <tr>
            <td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
        </tr>
        <tr>
            <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
            <td class="ttl">キャンペーン登録・編集</td>
            <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
        </tr>
        <tr>
            <td colspan="3"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
        </tr>
                <?php  if ($e->hasErrorForId('top_oc_name')) { ?>
                <tr>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                    <td width="880" align="left" class="red">キャンペーン名称<?php echo $e->getMessage('top_oc_name'); ?></td>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                </tr>
                <?php } ?>
                <?php  if ($e->hasErrorForId('top_oc_flg')) { ?>
                <tr>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                    <td width="880" align="left" class="red">フラグ<?php echo $e->getMessage('top_oc_flg'); ?></td>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                </tr>
                <?php } ?>
                <?php if ($e->hasErrorForId('top_oc_content')) { ?>
                <tr>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                    <td width="880" align="left" class="red">内容<?php echo $e->getMessage('top_oc_content'); ?></td>
                    <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                </tr>
                <?php } ?>
           <tr>
                <td colspan="3"><img src="/common/img/spacer.gif" alt="" width="1" height="10"></td>
            </tr>
        <tr>
            <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
            <td>
                <table class="inner" width="880">
                    <tr>
                        <td><img src="/common/img/spacer.gif" width="10" height="1" alt=""></td>
                        <td>全て必須入力です。<BR>
                            フラグは半角英数字で入力してください。<BR>
                            半角カタカナ・機種依存文字（例：&#12849;&#9312;&#13133;&#9834;など）は利用しないでください。</td>
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
                                    <td valign="top" class="sp bg_blue" style="white-space: nowrap;">キャンペーン名称</td>
                                    <td class="sp" valign="top"><input type="text" size="50" name="oc_name" maxlength="40" value="<?php echo $aoc002Out->oc_name(); ?>" style="width:400px;"></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
                                </tr>
                                <tr>
                                    <td valign="top" class="sp bg_blue">フラグ</td>
                                    <td class="sp" valign="top"><input type="text" size="50" name="oc_flg" maxlength="10" value="<?php echo $aoc002Out->oc_flg(); ?>" style="width:400px;"></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
                                </tr>
                                <tr>
                                    <td valign="top" class="sp bg_blue">内容</td>
                                    <td class="sp" valign="top"><input type="text" size="100" name="oc_content" maxlength="100" value="<?php echo $aoc002Out->oc_content(); ?>" style="width:700px;"></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><img src="/common/img/spacer.gif" width="1" height="10" alt=""></td>
                                </tr>
                                <tr>
                                    <td valign="top" class="sp bg_blue">ステータス</td>
                                    <td class="sp" valign="top">
                                            <?php 
                                                if($aoc002Out->oc_application() == 1){
                                                    $application_checked_on  = 'checked="checked"';
                                                    $application_checked_off = '';
                                                }else{
                                                    $application_checked_on  = '';
                                                    $application_checked_off = 'checked="checked"';
                                                }
                                            ?>
                                            <input type="radio" name="oc_application" id="radio1" value="1" <?php echo $application_checked_on; ?>>&nbsp;<label for="radio1">ON</label>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type="radio" name="oc_application" id="radio2" value="2" <?php echo $application_checked_off; ?>>&nbsp;<label for="radio2">OFF</label>
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
                        <td colspan="3" align="center">
                            <table>
                                <tr>
                                    <td><input type="image" src="/common/img/aoc/btn_confirm.gif" alt="確認する" ></td>
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
    <!-- 他社連携キャンペーンID -->
    <?php if($aoc002Out->raw_oc_id){ ?>
    <input name='oc_id' type='hidden' value='<?php echo $aoc002Out->raw_oc_id; ?>'> 
    <?php } ?>
    </form>
<!-- /#topWrap --></div>

<div id="footer">

<address><img src="/common/img/txt_copyright.gif" alt="&copy; SG Moving Co.,Ltd. All Rights Reserved."></address>
<!-- /#footer --></div>

<!-- /#wrapper --></div>
</body>
</html>
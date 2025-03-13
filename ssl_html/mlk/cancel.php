<?php
/**
 * ホテルサービスのお申込み確認画面を表示します。
 * @package    ssl_html
 * @subpackage EVE
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
require_once dirname(__FILE__) . '/../../lib/component/maintain_event.php';
// 現在日時
$nowDate = new DateTime('now');
if ($main_stDate_ev <= $nowDate && $nowDate <= $main_edDate_ev) {
    header("Location: /maintenance.php");
    exit;
}


/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';

//Sgmov_Component_Log::debug('############## ssl_html_cancel');
Sgmov_Lib::useView('mlk/CancelConf');


// 処理を実行
$view = new Sgmov_View_Eve_CancelConf();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Eve001Out
 */
$eveOutForm = $forms['outForm'];

/**
 * エラーフォーム
 * @var Sgmov_Form_Error
 */

// スマートフォン・タブレット判定
$detect = new MobileDetect();
$isSmartPhone = $detect->isMobile();
if ($isSmartPhone) {
    $inputTypeEmail  = 'email';
    $inputTypeNumber = 'number';
} else {
    $inputTypeEmail  = 'text';
    $inputTypeNumber = 'text';
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0" />
		<meta name="Keywords" content="佐川急便,ＳＧムービング,sgmoving,無料見積り,お引越し,設置,法人,家具輸送,家電輸送,精密機器輸送,設置配送" />
		<meta name="Description" content="サイトマップのご案内です。" />
		<title>キャンセル│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
                <?php
                    // キャッシュ対策
                    $sysdate = new DateTime();
                    $strSysdate = $sysdate->format('YmdHi');
                ?>
		<link rel="shortcut icon" href="/misc/favicon.ico" type="image/vnd.microsoft.icon" />
		<link href="/css/common.css" rel="stylesheet" type="text/css" />
		<link href="/css/sitemap.css" rel="stylesheet" type="text/css" />
        <link href="/css/form.css" rel="stylesheet" type="text/css" />
        <link href="/mlk/css/mlk.css" rel="stylesheet" type="text/css" />
        <link href="/css/confirmDialog.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
		<!--[if gte IE 9]><!-->
        <script charset="UTF-8" type="text/javascript" src="/js/jquery-2.2.0.min.js"></script>
        <script charset="UTF-8" type="text/javascript" src="/js/lodash.min.js"></script>
		<!--<![endif]-->
		<script charset="UTF-8" type="text/javascript" src="/js/common.js"></script>
		<script charset="UTF-8" type="text/javascript" src="/personal/js/anchor.js"></script>
		<script src="/js/ga.js" type="text/javascript"></script>
        <!--自動翻訳用-->
        <script src="https://d.shutto-translation.com/trans.js?id=9363"></script>
	</head>
	<body>
		<?php
			$gnavSettings = "";
			include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/header.php';
		?>
		<div id="breadcrumb">
			<ul class="wrap">
				<li><a href="/">ホーム</a></li>
				<li class="current">手荷物当日配送サービスのキャンセルお申込み</li>
			</ul>
		</div>
		<div id="main">
                    <div class="wrap clearfix">
                        <h1 class="page_title" style="margin-bottom:0;">手荷物当日配送サービスのキャンセルお申込み</h1>
                        <?php
                            include_once dirname(__FILE__) . '/parts/trans.php';
                        ?>

                        <p class="sentence">
                            ご確認のうえ、「キャンセル送信する」ボタンを押してください。
                        </p>

                    
                        <div class="section">
                            <form action="/mlk/cancel_comp" data-feature-id="<?php echo Sgmov_View_Eve_Common::FEATURE_ID ?>" data-id="<?php echo Sgmov_View_Eve_Common::GAMEN_ID_EVE001 ?>" method="post">
                                <input name="param" type="hidden" value="<?php echo @filter_input(INPUT_GET, 'id'); ?>" />
                                <input name="ticket" type="hidden" value="<?php echo $ticket ?>" />
                                <input name="comiket_id" type="hidden" value="<?php echo $eveOutForm['comiket']['id']; ?>" />
                                <div class="section">
                                    <div class="dl_header">●お届け先(To)</div>
                                    <div class="dl_block comiket_block">
                                        <dl>
                                            <dt id="delivery_date">
                                                お預かり/お届け日
                                            </dt>
                                            <dd>
                                                <?php echo date('Y/m/d', strtotime($eveOutForm['comiket_detail_list'][0]['collect_date']));  ?>
                                            </dd>
                                        </dl>
                                    </div>
                                    
                                    <div class="dl_header">●お預け先(From)</div>
                                    <div class="dl_block comiket_block">
                                        <dl>
                                            <dt id="comiket_id">
                                                申込番号
                                            </dt>
                                            <dd>
                                                <?php echo $eveOutForm['comiket_detail_list'][0]['cd']; ?> 
                                            </dd>
                                        </dl>
                                        <dl>
                                            <dt id="hotel_name">
                                                お預け先名称
                                            </dt>
                                            <dd>
                                                <?php echo $eveOutForm['hachakuten_from']['name_jp']; ?>
                                            </dd>
                                        </dl>

                                        <dl>
                                            <dt id="hotel_address">
                                                住所
                                            </dt>
                                            <dd>
                                                <?php echo  $eveOutForm['hachakuten_from']['address']; ?>
                                            </dd>
                                        </dl>
                                        <dl>
                                            <dt id="hotel_tel">
                                                電話番号
                                            </dt>
                                            <dd>
                                                <?php echo $eveOutForm['hachakuten_from']['tel']; ?>
                                            </dd>
                                        </dl>
                                    </div>
                                    
                                    <div class="dl_header">●お届け先(To)</div>
                                    <div class="dl_block comiket_block">
                                        <?php

                                        ?>
                                        <dl>
                                            <dt>
                                                お届け先の選択
                                            </dt>
                                            <dd>
                                                <?php echo $eveOutForm['addressee_type_nm']; ?>
                                                <br/>
                                                <br/>
                                                <span data-stt-ignore><?php echo $eveOutForm['hachakuten_to']['name_jp'] . "({$eveOutForm['hachakuten_to']['name_en']})"; ?></span>
                                            </dd>
                                        </dl>
                                        <dl>
                                            <dt>
                                                住所
                                            </dt>
                                            <dd>
                                                <?php echo $eveOutForm['hachakuten_to']['address']; ?>
                                            </dd>
                                        </dl>

                                        <dl>
                                            <dt>
                                                電話番号
                                            </dt>
                                            <dd>
                                                <?php echo $eveOutForm['hachakuten_to']['tel']; ?>
                                            </dd>
                                        </dl>
                                        <?php if ($eveOutForm['addressee_type_cd'] == Sgmov_View_Eve_Common::DELIVERY_TYPE_AIRPORT): ?>
                                        <dl>
                                            <dt>
                                                搭乗日時
                                            </dt>
                                            <dd>
                                                 <?php echo date('Y/m/d', strtotime($eveOutForm['comiket_detail_delivery_date'])); ?>&nbsp;<?php echo sprintf("%02d", $eveOutForm['comiket_detail_delivery_date_hour']) ; ?>時<?php echo sprintf("%02d", $eveOutForm['comiket_detail_delivery_date_min']) ; ?>分
                                            </dd>
                                        </dl>
                                        <dl>
                                            <dt>
                                                便名
                                            </dt>
                                            <dd>
                                                <span data-stt-ignore><?php echo $eveOutForm['comiket_detail_delivery_note']; ?></span>
                                            </dd>
                                        </dl>
                                        <?php endif; ?>
                                    </div>
                                    <div class="dl_header">●ご利用者情報(Applicant information)</div>
                                    <div class="dl_block comiket_block">
                                        <dl>
                                            <dt>
                                                名前
                                            </dt>
                                            <dd>
                                                <span data-stt-ignore><?php echo $eveOutForm['comiket']['staff_sei']; ?>&nbsp;<?php echo $eveOutForm['comiket']['staff_mei']; ?></span>
                                            </dd>
                                        </dl>

                                        <dl>
                                            <dt>
                                                電話番号
                                            </dt>
                                            <dd>
                                                <span data-stt-ignore><?php echo $eveOutForm['comiket']['staff_tel']; ?></span>
                                            </dd>
                                        </dl>

                                        <dl>
                                            <dt>
                                                メールアドレス
                                            </dt>
                                            <dd>
                                                <span data-stt-ignore><?php echo $eveOutForm['comiket']['mail']; ?></span>
                                            </dd>
                                        </dl>

                                        <dl>
                                            <dt>
                                                備考
                                            </dt>
                                            <dd>
                                                <span data-stt-ignore><?php echo $eveOutForm['comiket_detail_list'][0]['note']; ?></span>
                                            </dd>
                                        </dl>
                                    </div> 
                                    
                                    <div class="dl_header header-size">●荷物情報</div>
                                    <div class="dl_block comiket_block">
                                        <dl>
                                            <dt>
                                                サイズ
                                            </dt>
                                            <dd>
                                                <?php echo $eveOutForm['comiket_box_name']; ?> 
                                            </dd>
                                        </dl>
                                    </div>
                                    
                                    <div class="dl_header header-payment">●お支払い方法</div>
                                    <div class="dl_block comiket_block">
                                        <dl>
                                            <dt>
                                                お支払い方法
                                            </dt>
                                            <dd>
                                                クレジットカード
                                            </dd>
                                        </dl>
                                    </div>
                                    
                                    <h4 class="table_title">クレジットお支払い情報</h4>
                                    <div class="dl_block">
                                    <dl>
                                        <dt>確定送料(仕分け特別料金含む)</dt>
                                        <dd>
                                            <span data-stt-ignore>￥<?php echo number_format($eveOutForm['comiket']['amount_tax']).PHP_EOL; ?></span>
                                        </dd>
                                    </dl>
                            
                                    <div class="btn_area">
                                        <input id="submit_button"  onclick="document.getElementById('myModal').style.display='block'" type="button" name="submit" value="キャンセル送信する" />
                                    </div>
                                    <br>
                                    <?php
                                        include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/confirmMsg.php';
                                    ?>
                                    
                                </div>
                            </form>
                        </div>
                    </div>
		</div><!--main-->
		<?php
                    $footerSettings = 'under';
                    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/footer.php';
		?>
                <!--自動翻訳用-->
                <link href="/mlk/css/trans.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
                <script charset="UTF-8" type="text/javascript" src="/common/js/confirmDialog.js?<?php echo $strSysdate; ?>"></script>
	</body>
</html>
<?php
/**
 * ホテルサービスのお申込み確認画面を表示します。
 * @package    ssl_html
 * @subpackage EVE
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
//メンテナンス期間チェック
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
Sgmov_Lib::useView('mlk/CancelComp');

// 処理を実行
$view = new Sgmov_View_Eve_CancelComp();
$forms = $view->execute();

/**
 * フォーム
 * @var Sgmov_Form_Eve001Out
 */
//$eveOutForm = $forms['outForm'];

$dispItemInfo = $forms['dispItemInfo'];

/**
 * エラーフォーム
 * @var Sgmov_Form_Error
 */
//$e = $forms['errorForm'];

//error_log(var_export($e->_errors, true));

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
		<meta name="Keywords" content="手荷物当日配送サービス" />
		<meta name="Description" content="手荷物当日配送サービスのお申込みのご案内です。" />
		<title>手荷物当日配送サービスのキャンセルお申込みの完了│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
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
				<li class="current">手荷物当日配送サービスのキャンセル処理完了</li>
			</ul>
		</div>
		<div id="main">
                    <div class="wrap clearfix">
                        <h1 class="page_title" style="margin-bottom:0;">手荷物当日配送サービスのキャンセル処理完了</h1>
                        <?php
                            include_once dirname(__FILE__) . '/parts/trans.php';
                        ?>
                    
                        <div class="section">
                            <form action="/" data-feature-id="<?php echo Sgmov_View_Eve_Common::FEATURE_ID ?>" data-id="<?php echo Sgmov_View_Eve_Common::GAMEN_ID_EVE001 ?>" method="post">
                                <div class="section">
                                    <h2 class="complete_msg">お申込みのキャンセル処理が完了しました。</h2><br/><br/>
                                    登録したメールアドレスにキャンセル受付メールを送りました。<br/><br/>
                                    <table class="default_tbl">
                                        <tr>
                                            <th scope="row">申込番号</th>
                                            <td><?php echo $dispItemInfo['comiket']['tagId']; ?></td>
                                        </tr>
                                    </table>

                                    <!--<div class="btn_area">
                                        <input id="submit_button" type="button" name="button" value="トップページに戻る" onclick="location.href='/';" />
                                    </div>-->
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
	</body>
</html>
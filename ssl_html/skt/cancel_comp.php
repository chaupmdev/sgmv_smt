<?php
/**
 * コミケアピールのお申し込みキャンセル完了画面を表示します。
 * @package    ssl_html
 * @subpackage EVE
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */



/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
// イベント識別子(URLのpath)はマジックナンバーで記述せずこの変数で記述してください
$dirDiv=Sgmov_Lib::setDirDiv(dirname(__FILE__));
Sgmov_Lib::useView($dirDiv.'/CancelComp');

/**#@-*/

// 処理を実行
$view = new Sgmov_View_Evp_CancelComp();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
//$ticket = $forms['ticket'];

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
		<meta name="Keywords" content="佐川急便,ＳＧムービング,sgmoving,無料見積り,お引越し,設置,法人,家具輸送,家電輸送,精密機器輸送,設置配送" />
		<meta name="Description" content="サイトマップのご案内です。" />
		<title>催事・イベント配送受付サービスのキャンセルお申し込み│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
		<link rel="shortcut icon" href="/misc/favicon.ico" type="image/vnd.microsoft.icon" />
		<link href="/css/common.css" rel="stylesheet" type="text/css" />
		<link href="/css/sitemap.css" rel="stylesheet" type="text/css" />
            <link href="/css/form.css" rel="stylesheet" type="text/css" />
            <link href="/mlk/css/eve.css" rel="stylesheet" type="text/css" />
                
		<!--[if lt IE 9]>
			<script charset="UTF-8" type="text/javascript" src="/js/jquery-1.12.0.min.js"></script>
			<script charset="UTF-8" type="text/javascript" src="/js/lodash-compat.min.js"></script>
		<![endif]-->
		<!--[if gte IE 9]><!-->
			<script charset="UTF-8" type="text/javascript" src="/js/jquery-2.2.0.min.js"></script>
			<script charset="UTF-8" type="text/javascript" src="/js/lodash.min.js"></script>
		<!--<![endif]-->
		<script charset="UTF-8" type="text/javascript" src="/js/common.js"></script>
		<script charset="UTF-8" type="text/javascript" src="/personal/js/anchor.js"></script>
		<script src="/js/ga.js" type="text/javascript"></script>
	</head>
	<body>
		<?php
			$gnavSettings = "";
			include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/header.php';
		?>
		<div id="breadcrumb">
			<ul class="wrap">
				<li><a href="/">ホーム</a></li>
				<li class="current">催事・イベント配送受付サービスのキャンセル処理完了</li>
			</ul>
		</div>
		<div id="main">
                    <div class="wrap clearfix">
                        <h1 class="page_title">催事・イベント配送受付サービスのキャンセル処理完了しました。</h1>

                    
                        <div class="section">
                            <form action="/" method="post">
                                <div class="section">
                                    <h2 class="complete_msg">お申込みのキャンセル処理が完了しました。</h2><br/><br/>
                                    登録しているメールアドレスにキャンセル処理完了メールを送りました。<br/><br/>
                                    <table class="default_tbl">
                                        <tr>
                                            <th scope="row">申込み番号</th>
                                            <td><?php echo sprintf('%010d', $dispItemInfo['comiket']['id']); ?></td>
                                        </tr>
                                    </table>

                                    <div class="btn_area">
                                        <input id="submit_button" type="button" name="button" value="トップページに戻る" onclick="location.href='/';" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
		</div><!--main-->
		<?php
                    $footerSettings = 'under';
                    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/footer.php';
		?>
	</body>
</html>
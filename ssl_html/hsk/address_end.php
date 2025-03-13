<?php
/**
 * 品質選手権住所入力完了画面を表示します。
 * @package	ssl_html
 * @subpackage HSK
 * @author	 J.Yamagami
 * @copyright  2019-2019 SP-MediaTec CO,.LTD. All rights reserved.
 */
require_once dirname ( __FILE__ ) . '/../../lib/Lib.php';
Sgmov_Lib::useView ('hsk/AddressEnd');
$view = new Sgmov_View_Hsk_AddressEnd ();
$result = $view->execute ();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<?php require_once './parts/html_head.php'; ?>
</head>
<body>
<?php require_once './parts/page_head.php'; ?>
	<div class="main main-raised">
	<div class="container">
		<div>
			<div class="title border-bottom" style="padding: 10px;">
				<h4 style="font-weight: normal;">
					<span style="font-weight: 600;"> ありがとうございました。 </span>
				</h4>
			</div>
		</div>
	</div>
	<!-- container -->
</div>
<!-- main -->
<?php require_once './parts/page_footer.php'; ?>
</body>
</html>
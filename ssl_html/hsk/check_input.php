<?php
/**
 * 品質選手権アンケート入力画面を表示します。
 * @package    ssl_html
 * @subpackage HSK
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';

Sgmov_Lib::useView('hsk/CheckInput');

// 処理を実行
$view = new Sgmov_View_Hsk_CheckInput();
$result = $view->execute();

echo json_encode($result);
?>

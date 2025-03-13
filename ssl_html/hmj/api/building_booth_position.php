<?php
/**
 * 催事・イベント配送受付お申し込み入力画面を表示します。
 * @package    ssl_html
 * @subpackage EVP
 * @author     K.Sawada(SCS)
 * @copyright  2018-2021 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */

require_once dirname(__FILE__) . '/../../../lib/Lib.php';
Sgmov_Lib::useView('hmj/BuildingBoothPosition');

/**#@-*/
// 処理を実行
$view = new Sgmov_View_Dsn_BuildingBoothPosition();
$result = $view->execute();

echo json_encode($result);

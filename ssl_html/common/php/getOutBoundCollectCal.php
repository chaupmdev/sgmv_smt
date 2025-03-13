<?php
/**
 * お届け日時から日付情報を検索し、json形式で返します。
 * @package    ssl_html
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../../lib/Lib.php';
Sgmov_Lib::useView('cst/GetOutBoundCollectCal');

// 処理を実行
$view = new Sgmov_View_Cst_GetOutBoundCal();
$data = $view->execute();

echo json_encode($data);

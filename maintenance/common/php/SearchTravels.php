<?php
/**
 * ツアー会社からツアーを検索し、json形式で返します。
 * @package    ssl_html
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../../lib/Lib.php';
Sgmov_Lib::useView('tra/SearchTravels');

// 処理を実行
$view = new Sgmov_View_Tra_SearchTravels();
$data = $view->execute();

echo json_encode($data);
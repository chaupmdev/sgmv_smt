<?php
/**
 * Excel出力
 * 
 * @package    ssl_html
 * @subpackage EXPORT
 * @author     DucPM31
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('export/Excel');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Export_Excel();
$view->execute();
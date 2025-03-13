<?php error_reporting(-1);ini_set('display_errors', '1');
 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('pre/Index', 'pre/Common');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Pre_Index();
$view->execute();
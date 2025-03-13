<?php
 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('pre/Topve', 'pre/Common');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Pre_Topve();
$view->execute();
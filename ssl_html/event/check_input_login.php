<?php
/**

 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('event/CheckInputLogin');
/**#@-*/

// 06_ログイン画面
$view = new Sgmov_View_Event_CheckInputLogin();
$view->execute();
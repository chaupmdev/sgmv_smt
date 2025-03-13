<?php
/**

 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('event/CheckInputRegister');
/**#@-*/

// 04_会員登録画面
$view = new Sgmov_View_Event_CheckInputRegister();
$view->execute();
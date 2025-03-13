<?php
/**

 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('event/CheckInputResetPass');
/**#@-*/

// 会員情報忘れ
$view = new Sgmov_View_Event_CheckInputResetPass();
$view->execute();
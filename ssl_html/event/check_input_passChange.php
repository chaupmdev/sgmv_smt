<?php

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('event/CheckInputPassChange');
/**#@-*/

// 11_パスワード変更
$view = new Sgmov_View_Event_CheckInputPassChange();
$view->execute();
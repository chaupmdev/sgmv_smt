<?php
/**

 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';

Sgmov_Lib::useView('event/CheckInputRobot');

/**#@-*/

// 03_会員登録せずロボットチェック
$view = new Sgmov_View_Event_CheckInputRobot();

$view->execute();
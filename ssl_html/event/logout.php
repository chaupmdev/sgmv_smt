<?php
/**

 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';

Sgmov_Lib::useView('event/Logout');
/**#@-*/

$view = new Sgmov_View_Event_Logout();
$view->execute();

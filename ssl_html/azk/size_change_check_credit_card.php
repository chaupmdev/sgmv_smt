<?php

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('azk/SizeChangeCheckCreditCard');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Azk_SizeChangeCheckCreditCard();
$view->execute();
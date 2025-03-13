<?php
/**
 * ログアウト処理を実行します。
 * @package    maintenance
 * @subpackage ACM
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('acm/Logout');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Acm_Logout();
$view->execute();
?>

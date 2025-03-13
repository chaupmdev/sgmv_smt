<?php
/**
 * 法人オフィス移転入力情報をチェックします。
 * @package    ssl_html
 * @subpackage PCV
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('pcv/CheckInput');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Pcv_CheckInput();
$view->execute();
?>
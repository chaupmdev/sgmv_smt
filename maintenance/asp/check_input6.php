<?php
/**
 * 特価個別編集金額入力情報をチェックします。
 * @package    maintenance
 * @subpackage ASP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('asp/CheckInput6');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Asp_CheckInput6();
$view->execute();
?>
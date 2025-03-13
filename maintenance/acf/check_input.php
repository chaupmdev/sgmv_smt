<?php
/**
 * 料金マスタメンテナンス入力情報をチェックします。
 * @package    maintenance
 * @subpackage ACF
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('acf/CheckInput');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Acf_CheckInput();
$view->execute();
?>
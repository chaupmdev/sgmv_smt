<?php
/**
 * 法人設置輸送入力情報をチェックします。
 * @package    ssl_html
 * @subpackage PCS
 * @author     K.Hamada(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('pcs/CheckInput');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Pcs_CheckInput();
$view->execute();
?>
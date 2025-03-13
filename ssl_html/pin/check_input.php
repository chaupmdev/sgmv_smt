<?php
/**
 * お問い合わせ入力情報をチェックします。
 * @package    ssl_html
 * @subpackage PIN
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('pin/CheckInput');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Pin_CheckInput();
$view->execute();
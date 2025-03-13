<?php
/**
 * 概算見積もり申し込み入力情報をチェックします。
 * @package    ssl_html
 * @subpackage PRE
 * @author     H.Tsuji(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('pre/CheckInput');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Pre_CheckInput();
$view->execute();
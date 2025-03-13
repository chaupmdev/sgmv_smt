<?php
/**
 * 他社連携キャンペーン入力情報をチェックします。
 * @package    maintenance
 * @subpackage AOC
 * @author     T.Aono(SGS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('aoc/CheckInput');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Aoc_CheckInput();
$view->execute();
?>
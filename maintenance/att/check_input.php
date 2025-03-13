<?php
/**
 * ツアー発着地マスタメンテナンス入力情報をチェックします。
 * @package    maintenance
 * @subpackage ATT
 * @author     T.Aono(SGS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('att/CheckInput');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Att_CheckInput();
$view->execute();
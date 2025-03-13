<?php
/**
 * ツアー発着地マスタメンテナンス削除確認情報をチェックします。
 * @package    maintenance
 * @subpackage ATT
 * @author     T.Aono(SGS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('att/CheckDelete');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Att_CheckDelete();
$view->execute();
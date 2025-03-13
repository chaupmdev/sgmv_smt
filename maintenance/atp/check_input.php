<?php
/**
 * ツアーエリアマスタメンテナンス入力情報をチェックします。
 * @package    maintenance
 * @subpackage ATP
 * @author     T.Aono(SGS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('atp/CheckInput');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Atp_CheckInput();
$view->execute();
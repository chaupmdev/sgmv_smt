<?php
/**
 * マンションマスタメンテナンス入力情報をチェックします。
 * @package    maintenance
 * @subpackage AAP
 * @author     T.Aono(SGS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('aap/CheckInput');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Aap_CheckInput();
$view->execute();
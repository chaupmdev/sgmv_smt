<?php
/**
 * ツアーエリアマスタメンテナンス削除確認情報をチェックします。
 * @package    maintenance
 * @subpackage ATP
 * @author     T.Aono(SGS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('atp/CheckDelete');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Atp_CheckDelete();
$view->execute();
<?php
/**
 * ツアー会社マスタメンテナンス削除情報をチェックします。
 * @package    maintenance
 * @subpackage ATA
 * @author     T.Aono(SGS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('ata/CheckDelete');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Ata_CheckDelete();
$view->execute();
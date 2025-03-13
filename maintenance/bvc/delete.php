<?php
/**
 * BVC/Delete 訪問見積もり申し込みDBから、データ送信済みで1年以上更新されていないデータを削除します。
 * @package    maintenance
 * @subpackage BVC
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useProcess('Bvc');
/**#@-*/

// 処理を実行
$delete = new Sgmov_Process_Bvc();
$delete->execute();
?>
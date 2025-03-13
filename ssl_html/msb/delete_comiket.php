<?php
/**
 * イベント輸送サービスのデータを削除します。
 * @package    ssl_html
 * @subpackage DSN
 * @author     Juj-Yamagami(SP)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('dsn/DeleteComiket');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Dsn_DeleteComiket();
$view->execute();
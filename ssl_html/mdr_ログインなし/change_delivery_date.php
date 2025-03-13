<?php
/**
 * イベント輸送サービスの往路の搬入日を変更します。
 * @package    ssl_html
 * @subpackage MDR
 * @author     Juj-Yamagami(SP)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('mdr/ChangeDeliveryDate');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Mdr_ChangeDeliveryDate();
$view->execute();
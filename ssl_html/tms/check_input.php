<?php
/**
 * 手荷物受付サービスのお申し込み入力情報をチェックします。
 * @package    ssl_html
 * @subpackage EVE
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('tms/CheckInput');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Tms_CheckInput();
$view->execute();
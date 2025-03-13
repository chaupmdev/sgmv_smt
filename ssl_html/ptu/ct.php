<?php
/**
 * 手荷物受付サービスのお申し込み入力画面を表示します。
 * @package    ssl_html
 * @subpackage PPR
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('ptu/Ct');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Ptu_Ct();
$forms = $view->execute();
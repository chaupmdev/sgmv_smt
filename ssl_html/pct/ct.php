<?php
/**
 * 手荷物受付サービスのお申し込み件数を表示します。
 * @package    ssl_html
 * @subpackage PCR
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('pct/Ct');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Pct_Ct();
$forms = $view->execute();
<?php
/**
 * イベント輸送サービスのクレジット情報を出力します。
 * @package    ssl_html
 * @subpackage EVE
 * @author     Juj-Yamagami(SP)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('evc/CreditInfo');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Evc_CreditInfo();
$view->execute();
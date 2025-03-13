<?php
/**
 * イベント輸送サービスのメールアドレスを変更します。
 * @package    ssl_html
 * @subpackage EVE
 * @author     Juj-Yamagami(SP)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('evc/ChangeMail');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Evc_ChangeMail();
$view->execute();
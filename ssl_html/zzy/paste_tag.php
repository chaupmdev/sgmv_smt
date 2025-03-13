<?php
/**
 * イベント輸送サービスの貼付票PDFを出力します。
 * @package    ssl_html
 * @subpackage Zzy
 * @author     Juj-Yamagami(SP)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('zzy/PasteTag');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Zzy_PasteTag();
$view->execute();
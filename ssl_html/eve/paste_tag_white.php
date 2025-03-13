<?php
/**
 * イベント輸送サービスの貼付票PDFを出力します。
 * @package    ssl_html
 * @subpackage EVE
 * @author     Juj-Yamagami(SP)
 * @copyright  2018-2020 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('eve/PasteTagWhite');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Eve_PasteTag_White();
$view->execute();
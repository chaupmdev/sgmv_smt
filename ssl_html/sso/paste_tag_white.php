<?php
/**
 * 【シーフードショー大阪・アグリフードEXPO大阪】の白紙貼付票PDFを出力します。
 * @package    ssl_html
 * @subpackage EVE
 * @author     Juj-Yamagami(SP)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('sso/PasteTagWhite');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Sso_PasteTag_White();
$view->execute();
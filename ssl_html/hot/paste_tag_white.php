<?php
/**
 * イベント輸送サービスの貼付票PDFを出力します。
 * @package    ssl_html
 * @subpackage DSN
 * @author     Juj-Yamagami(SP)
 * @copyright  2018-2020 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
// イベント識別子(URLのpath)はマジックナンバーで記述せずこの変数で記述してください
$dirDiv=Sgmov_Lib::setDirDiv(dirname(__FILE__));
Sgmov_Lib::useView($dirDiv.'/PasteTagWhite');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Dsn_PasteTag_White();
$view->execute();
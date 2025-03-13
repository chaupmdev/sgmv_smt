<?php
/**
 * コミックマーケット99 個人向の貼付票PDFを出力します。
 * @package    ssl_html
 * @subpackage EVP
 * @author     Juj-Yamagami(SP)
 * @copyright  2021-2021 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
// イベント識別子(URLのpath)はマジックナンバーで記述せずこの変数で記述してください
$dirDiv=Sgmov_Lib::setDirDiv(dirname(__FILE__));
Sgmov_Lib::useView($dirDiv.'/PasteTag');

/**#@-*/

// 処理を実行
$view = new Sgmov_View_Evp_PasteTag();
$view->execute();
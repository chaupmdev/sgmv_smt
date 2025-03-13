<?php
/**
 * ハンドメイドジャパンの貼付票PDFを出力します。
 * @package    ssl_html
 * @subpackage HMJ
 * @author     Juj-Yamagami(SP)
 * @copyright  2022-2022 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
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
$view = new Sgmov_View_Hmj_PasteTag();
$view->execute();
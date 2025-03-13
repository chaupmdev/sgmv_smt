<?php
/**
 * イベント輸送サービスのデータを削除します。
 * @package    ssl_html
 * @subpackage TWF
 * @author     Juj-Yamagami(SP)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
// イベント識別子(URLのpath)はマジックナンバーで記述せずこの変数で記述してください
$dirDiv=Sgmov_Lib::setDirDiv(dirname(__FILE__));
Sgmov_Lib::useView($dirDiv.'/DeleteComiket');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Twf_DeleteComiket();
$view->execute();
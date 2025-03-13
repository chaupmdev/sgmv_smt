<?php
/**
 * イベント輸送サービスの往路の搬入日を変更します。
 * @package    ssl_html
 * @subpackage UNA
 * @author     Juj-Yamagami(SP)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
// イベント識別子(URLのpath)はマジックナンバーで記述せずこの変数で記述してください
$dirDiv=Sgmov_Lib::setDirDiv(dirname(__FILE__));
Sgmov_Lib::useView($dirDiv.'/ChangeDeliveryDate');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Unf_ChangeDeliveryDate();
$view->execute();
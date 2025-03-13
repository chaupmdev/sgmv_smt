<?php
/**
 * 手荷物受付サービスのお申込み入力情報をチェックします。
 * @package    ssl_html
 * @subpackage EVE
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
//メンテナンス期間チェック
require_once dirname(__FILE__) . '/../../lib/component/maintain_event.php';
// 現在日時
$nowDate = new DateTime('now');
if ($main_stDate_ev <= $nowDate && $nowDate <= $main_edDate_ev) {
    header("Location: /maintenance.php");
    exit;
}
/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('mlk/CheckInput');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Eve_CheckInput();
$view->execute();
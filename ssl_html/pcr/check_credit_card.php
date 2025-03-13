<?php
/**
 * 手荷物受付サービスのお申し込み入力情報をチェックします。
 * @package    ssl_html
 * @subpackage PCR
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
//メンテナンス期間チェック
require_once dirname(__FILE__) . '/../../lib/component/maintain_pcr.php';
// 現在日時
$nowDate = new DateTime('now');
if ($main_stDate_pcr <= $nowDate && $nowDate <= $main_edDate_pcr) {
    header("Location: /maintenance.php");
    exit;
}
/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('pcr/CheckCreditCard');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Pcr_CheckCreditCard();
$view->execute();
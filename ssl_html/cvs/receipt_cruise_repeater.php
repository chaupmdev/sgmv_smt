<?php
/**
 * RCR クルーズリピータマスタ更新
 * @package    maintenance
 * @subpackage RCR
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
Sgmov_Lib::useProcess('Rcr');
/**#@-*/

// 処理を実行
try {
    $receipt = new Sgmov_Process_Rcr();
    $receipt->execute();
} catch(Exception $e) {
    // 管理者宛にエラー通知メール
    $mail_to = Sgmov_Component_Config::getLogMailToSpm();
    Sgmov_Component_Mail::sendTemplateMail(null,
        dirname(__FILE__) . '/../../lib/mail_template/rcr_connect_error.txt', $mail_to);

    // ログに出力
    Sgmov_Component_Log::err(var_export($e, TRUE));
}
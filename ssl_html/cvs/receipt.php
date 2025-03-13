<?php
/**
 * CVS 旅客手荷物受付サービスのお申し込みDBにコンビニ入金データを登録します。
 * @package    maintenance
 * @subpackage CVS
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
 
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
Sgmov_Lib::useProcess('Cvs');
/**#@-*/

// 処理を実行
try {
    $receipt = new Sgmov_Process_Cvs();
    $receipt->execute();
} catch(Exception $e) {
    // 管理者宛にエラー通知メール
    $mail_to = Sgmov_Component_Config::getLogMailToSpm();
    Sgmov_Component_Mail::sendTemplateMail('stopCvs',
        dirname(__FILE__) . '/../../lib/mail_template/bcr_connect_error.txt', $mail_to);

    // ログに出力
    Sgmov_Component_Log::err(var_export($e, TRUE));
}
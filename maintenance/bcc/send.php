<?php
/**
 * BCC
 * @package    maintenance
 * @subpackage BCC
 * @author     FPT-AnNV6
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
Sgmov_Lib::useProcess('Bcc');
/**#@-*/

// 処理を実行
try {
    $send = new Sgmov_Process_Bcc();
    $send->execute();
} catch(Sgmov_Component_Exception $e) {
    // ログに出力
    Sgmov_Component_Log::err(var_export($e, TRUE));
    
} catch(Exception $e) {
    // 管理者宛にエラー通知メール
    $mail_to = Sgmov_Component_Config::getLogMailToSpm();
    Sgmov_Component_Mail::sendTemplateMail('stopBcc',
        dirname(__FILE__) . '/../../lib/mail_template/bcc_connect_error.txt', $mail_to);

    // ログに出力
    Sgmov_Component_Log::err(var_export($e, TRUE));
}
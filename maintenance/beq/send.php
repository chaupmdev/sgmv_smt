<?php
/**
 * BEQ アンケートDBから、データ送信をします。
 * @package    maintenance
 * @subpackage BEQ
 * @author     M.TAMADA(NS)
 * @copyright  2016 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
Sgmov_Lib::useProcess('Beq');
/**#@-*/

// 処理を実行
try {
	Sgmov_Component_Log::debug('StartBeq');
    $send = new Sgmov_Process_Beq();
    $send->execute();
} catch(Exception $e) {
    // 管理者宛にエラー通知メール
    $mail_to = Sgmov_Component_Config::getLogMailToSpm();

    Sgmov_Component_Mail::sendTemplateMail('stopBeq',
        dirname(__FILE__) . '/../../lib/mail_template/beq_connect_error.txt', $mail_to);

    // ソケット接続失敗の場合はロックファイルの削除
    if (file_exists(Sgmov_Lib::getLogDir() . '/' . Sgmov_Process_Beq::OPRATION_FILE_NAME)) {
        @unlink(Sgmov_Lib::getLogDir() . '/' . Sgmov_Process_Beq::OPRATION_FILE_NAME);
    }

    // ログに出力
    Sgmov_Component_Log::err(var_export($e, TRUE));
}
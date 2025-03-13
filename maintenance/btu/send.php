<?php
/**
 * BTU 単身カーゴプランのお申し込みDBから、データ送信をします。
 * @package    maintenance
 * @subpackage BTU
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
Sgmov_Lib::useProcess('Btu');
/**#@-*/

// 処理を実行
try {
    $send = new Sgmov_Process_Btu();
    $send->execute();
} catch(Exception $e) {
    // 管理者宛にエラー通知メール
    $mail_to = Sgmov_Component_Config::getLogMailToSpm();
    Sgmov_Component_Mail::sendTemplateMail('stopBtu',
        dirname(__FILE__) . '/../../lib/mail_template/btu_connect_error.txt', $mail_to);

    // ソケット接続失敗の場合はロックファイルの削除
    if (file_exists(Sgmov_Lib::getLogDir() . '/' . Sgmov_Process_Btu::OPRATION_FILE_NAME)) {
        @unlink(Sgmov_Lib::getLogDir() . '/' . Sgmov_Process_Btu::OPRATION_FILE_NAME);
    }

    // ログに出力
    Sgmov_Component_Log::err(var_export($e, TRUE));
}
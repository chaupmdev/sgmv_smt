<?php
/**
 * BCR 旅客手荷物受付サービスのお申し込みDBから、データ送信をします。
 * @package    maintenance
 * @subpackage BCR
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
Sgmov_Lib::useProcess('Bcr');
/**#@-*/

// 処理を実行
try {
    $send = new Sgmov_Process_Bcr();
    $send->execute();
} catch(Sgmov_Component_Exception $e) {
    
    $information = $e->getInformaton();

    // 管理者宛にエラー通知メール
    $mail_to = Sgmov_Component_Config::getLogMailToSpm();
    
    if(empty($information)) {
        $information["id"] = " -- 取得できませんでした。--";
        $information["created"] = " -- 取得できませんでした。--";
        Sgmov_Component_Mail::sendTemplateMail($information,
            dirname(__FILE__) . '/../../lib/mail_template/bcr_connect_error.txt', $mail_to);
    }  else {
        Sgmov_Component_Mail::sendTemplateMail($information,
            dirname(__FILE__) . '/../../lib/mail_template/bcr_connect_error.txt', $mail_to);
    }

    // ソケット接続失敗の場合はロックファイルの削除
    if (file_exists(Sgmov_Lib::getLogDir() . '/' . Sgmov_Process_Bcr::OPRATION_FILE_NAME)) {
        @unlink(Sgmov_Lib::getLogDir() . '/' . Sgmov_Process_Bcr::OPRATION_FILE_NAME);
    }

    // ログに出力
    Sgmov_Component_Log::err(var_export($e, TRUE));
    
} catch(Exception $e) {
    // 管理者宛にエラー通知メール
    $mail_to = Sgmov_Component_Config::getLogMailToSpm();
    Sgmov_Component_Mail::sendTemplateMail('stopBcr',
        dirname(__FILE__) . '/../../lib/mail_template/bcr_connect_error.txt', $mail_to);

    // ソケット接続失敗の場合はロックファイルの削除
    if (file_exists(Sgmov_Lib::getLogDir() . '/' . Sgmov_Process_Bcr::OPRATION_FILE_NAME)) {
        @unlink(Sgmov_Lib::getLogDir() . '/' . Sgmov_Process_Bcr::OPRATION_FILE_NAME);
    }

    // ログに出力
    Sgmov_Component_Log::err(var_export($e, TRUE));
}
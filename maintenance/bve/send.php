<?php
/**
 * BVC/Delete 訪問見積もり申し込みDBから、データ送信済みで1年以上更新されていないデータを削除します。
 * @package    maintenance
 * @subpackage BVE
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
Sgmov_Lib::useProcess('Bve');
/**#@-*/

// 処理を実行
try {
    $delete = new Sgmov_Process_Bve();
    $delete->execute();
} catch(Sgmov_Component_Exception $e) {
    
    $information = $e->getInformaton();

    // 管理者宛にエラー通知メール
    $mail_to = Sgmov_Component_Config::getLogMailToSpm();
    
    if(empty($information)) {
        $information["id"] = " -- 取得できませんでした。--";
        $information["created"] = " -- 取得できませんでした。--";
        Sgmov_Component_Mail::sendTemplateMail($information,
            dirname(__FILE__) . '/../../lib/mail_template/bve_connect_error.txt', $mail_to);
    } else {
        Sgmov_Component_Mail::sendTemplateMail($information,
            dirname(__FILE__) . '/../../lib/mail_template/bve_connect_error.txt', $mail_to);
    }

    // ソケット接続失敗の場合はロックファイルの削除
    if (file_exists(Sgmov_Lib::getLogDir() . '/' . Sgmov_Process_Bve::OPRATION_FILE_NAME)) {
        @unlink(Sgmov_Lib::getLogDir() . '/' . Sgmov_Process_Bve::OPRATION_FILE_NAME);
    }

    // ログに出力
    Sgmov_Component_Log::err(var_export($e, TRUE));
    
} catch(Exception $e) {
    // 管理者宛にエラー通知メール
    $mail_to = Sgmov_Component_Config::getLogMailTo();
    Sgmov_Component_Mail::sendTemplateMail('stopBve',
        dirname(__FILE__) . '/../../lib/mail_template/bve_connect_error.txt', $mail_to);

    // ソケット接続失敗の場合はロックファイルの削除
    if(file_exists(Sgmov_Lib::getLogDir() . '/' . Sgmov_Process_Bve::OPRATION_FILE_NAME)) {
        @unlink(Sgmov_Lib::getLogDir() . '/' . Sgmov_Process_Bve::OPRATION_FILE_NAME);
    }

    // ログに出力
    Sgmov_Component_Log::err(var_export($e, TRUE));
}

?>
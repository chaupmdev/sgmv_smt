<?php
/**
 * イベント輸送サービスのお申し込み送信バッチです。コンビニ先払の入金済・未送信データが対象。
 * @package    /maintenance/bcm/
 * @author     Juj-Yamagami(SP)
 * @copyright  2018 Sp Media-Tec CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
Sgmov_Lib::useProcess('Bcm');
/**#@-*/

// 処理を実行
try {
    $send = new Sgmov_Process_Bcm();
    $send->execute();
} catch(Exception $e) {

    // 管理者宛にエラー通知メール
    $mail_to = Sgmov_Component_Config::getLogMailToSpm();
    Sgmov_Component_Mail::sendTemplateMail('stopBcm', dirname(__FILE__) . '/../../lib/mail_template/bcm_connect_error.txt', $mail_to);

    // ソケット接続失敗の場合はロックファイルの削除
    if (file_exists(Sgmov_Lib::getLogDir() . '/' . Sgmov_Process_Bcm::OPRATION_FILE_NAME)) {
        @unlink(Sgmov_Lib::getLogDir() . '/' . Sgmov_Process_Bcm::OPRATION_FILE_NAME);
    }

    // ログに出力
    Sgmov_Component_Log::err(var_export($e, TRUE));
}
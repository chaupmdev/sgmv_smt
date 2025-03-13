<?php

/**
 * コストコ配送サービスのお申し込み完了画面を表示します。
 * @package    ssl_html
 * @subpackage CSC
 * @author     K.Sawada
 * @copyright  2021-2021 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('csc/GetShohinInfo');
Sgmov_Lib::useForms(array('Error', 'EveSession'));
/**#@-*/

// Sgmov_Component_Log::debug("######################");
// Sgmov_Component_Log::debug($_POST);
// Sgmov_Component_Log::debug($_REQUEST);
// // 処理を実行
$view = new Sgmov_View_Csc_GetShohinInfo();

Sgmov_Component_Log::debug("###################### ZZZZZZZZZZZZZZ");
Sgmov_Component_Log::debug($_SESSION);

$_SESSION['session_test_key2'] = 'session_test_val2';

$result = array();
try {
    $result = $view->execute();
} catch(Exception $e) {
    $exInfo = $e->getMessage();
    $result = array(
        'status' => 'error',
        'message' => 'エラーが発生しました。',
        'res_data' => array(
            'error_info' => $exInfo,
        ),
    );
}


Sgmov_Component_Log::debug("###################### ZZZZZZZZZZZZZZ");
Sgmov_Component_Log::debug($result);


header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Credentials: true");

echo json_encode($result);


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
Sgmov_Lib::useView('mlk/GetCommiket');
Sgmov_Lib::useForms(array('Error', 'EveSession'));
/**#@-*/

// // 処理を実行
$view = new Sgmov_View_Csc_GetCommmiket();


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

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Credentials: true");

echo json_encode($result);


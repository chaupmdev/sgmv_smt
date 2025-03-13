<?php

/**
 * コストコ配送サービスの入力バリデーション
 * @package    ssl_html
 * @subpackage CSC
 * @author     K.Sawada
 * @copyright  2021-2021 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */


require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('csc/CheckInput2');
Sgmov_Lib::useForms(array('Error', 'EveSession'));
/**#@-*/

// // 処理を実行
$view = new Sgmov_View_Csc_CheckInput2();

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



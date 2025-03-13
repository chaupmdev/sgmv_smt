<?php

/**
 * コストコ配送サービスで配達希望日のリードタイムを取得
 * @package    ssl_html
 * @subpackage CSC
 * @author     FPT-AnNV6
 * @copyright  2022-2022 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('csc/CheckAddress');
Sgmov_Lib::useForms(array('Error', 'EveSession'));
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Csc_CheckAddress();

Sgmov_Component_Log::debug($_SESSION);

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

Sgmov_Component_Log::debug($result);

echo json_encode($result);


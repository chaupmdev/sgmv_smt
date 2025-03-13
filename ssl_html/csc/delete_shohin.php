<?php

require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('csc/MstShohin');
Sgmov_Lib::useForms(array('Error', 'EveSession'));

//処理を実行
$view = new Sgmov_View_Csc_MstShohin(); 
Sgmov_Component_Log::debug("9");
$result = array();
if (!empty($_GET['id'])) {
    try {
        $result = $view->deleteShohin($_GET['id']);
        Sgmov_Component_Redirect::redirectPublicSsl("/csc/mst_shohin_list?flg_del=1");
        exit;
    } catch (Exception $e) {
        $exInfo = $e->getMessage();
        $result = array(
            'status' => 'error',
            'message' => 'エラーが発生しました。',
            'res_data' => array(
                'error_info' => $exInfo,
            ),
        );
        Sgmov_Component_Redirect::redirectPublicSsl("/500.html");
        exit;
    }
} else {
    Sgmov_Component_Redirect::redirectPublicSsl("/csc/mst_shohin_list");
    exit();
    
}

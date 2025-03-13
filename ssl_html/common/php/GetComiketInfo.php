<?php
/**
 * ComiketTblの no_chg_flg を "1"(ON) に変更します
 * @package    ssl_html
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */


/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../../lib/Lib.php';


Sgmov_Lib::useView('cst/GetComiketInfo');


// 処理を実行
$view = new Sgmov_View_Cst_GetComiketInfo();


$data = $view->execute();
//echo 'AAAAAAAAAAAAAA';
//exit;

echo json_encode($data);

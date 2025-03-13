<?php
/**
 * イベントIDからブース情報を検索し、json形式で返します。
 * @package    ssl_html
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../../lib/Lib.php';
Sgmov_Lib::useView('evb/SearchBuildingBoothPostion');

Sgmov_Component_Log::debug("######################## 601"); 
// 処理を実行
$view = new Sgmov_View_Evb_SearchBuildingBoothPostion();
Sgmov_Component_Log::debug("######################## 602"); 
$data = $view->execute();
Sgmov_Component_Log::debug("######################## 603"); 

echo json_encode($data);
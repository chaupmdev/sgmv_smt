<?php
/**
 * 郵便番号から住所を検索し、適当に整形してjson形式で返します。
 * @package    ssl_html
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */

require_once dirname(__FILE__) . '/../../../lib/Lib.php';
Sgmov_Lib::useView('ybn/SearchAddressForOuterSystem');

// 処理を実行
$view = new Sgmov_View_Yvn_SearchAddressForOuterSystem();
list ($temp1, $temp2, $temp3) = $view->execute();

    //整型
    $data['jpref'] = $temp1;
    $data['jcity'] = $temp2;
    $data['jarea'] = $temp3;
    $data['jstrt'] = "";

    echo json_encode($data);

?>

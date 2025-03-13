<?php
/**
 * 品質選手権住所入力チェックします。
 * @package    ssl_html
 * @subpackage HSK
 * @author     J.Yamagami
 * @copyright  2019-2019 SP-MediaTec CO,.LTD. All rights reserved.
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('hsk/AddressCheckInput');
$view = new Sgmov_View_Hsk_AddressCheckInput();
$result = $view->execute();
echo json_encode($result);
?>

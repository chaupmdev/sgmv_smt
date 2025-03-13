<?php
/**
 * 郵便番号から郵便番号DLLをソケット通信で検索し、時間帯指定不可地区をjson形式で返します。
 * @package    ssl_html
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../../lib/Lib.php';
Sgmov_Lib::useView('sck/SearchTimeZoneFlag');

// 処理を実行
$view = new Sgmov_View_Sck_SearchTimeZoneFlag();
$data = $view->execute();

echo json_encode($data);
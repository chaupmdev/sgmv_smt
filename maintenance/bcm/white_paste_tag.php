<?php
/**
 * イベント輸送サービスの白紙貼付票PDFを出力します。
 * @package    ssl_html
 * @subpackage EVE
 * @author     Juj-Yamagami(SP)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
Sgmov_Lib::useProcess('WhitePasteTag');
/**#@-*/

var_dump($argv);

// 実行の構成でスクリプト引数にセットしたイベントサブIDを取得する。
$eventsub_id = $argv[1];

// 処理を実行
$view = new Sgmov_View_Eve_WhitePasteTag();
$view->execute($eventsub_id);
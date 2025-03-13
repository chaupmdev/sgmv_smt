<?php
/**
 * 催事・イベント配送受付お申し込み入力画面を表示します。
 * @package    ssl_html
 * @subpackage EVP
 * @author     K.Sawada(SCS)
 * @copyright  2018-2021 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */

require_once dirname(__FILE__) . '/../../../lib/Lib.php';
// イベント識別子(URLのpath)はマジックナンバーで記述せずこの変数で記述してください
// サブディレクトリは取得ができないので固定で設定するしかない
//$dirDiv=Sgmov_Lib::setDirDiv(dirname(__FILE__));
Sgmov_Lib::useView('skt/BuildingBoothPosition');

/**#@-*/
// 処理を実行
$view = new Sgmov_View_Evp_BuildingBoothPosition();
$result = $view->execute();

echo json_encode($result);

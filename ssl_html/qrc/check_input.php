<?php
/**
 * 手荷物受付サービスのお申し込み入力情報をチェックします。
 * @package    ssl_html
 * @subpackage QRC
 * @author     K.Tamashiro(SP)
 * @copyright  2022 Sp Media-Tec CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
// イベント識別子(URLのpath)はマジックナンバーで記述せずこの変数で記述してください
$dirDiv=Sgmov_Lib::setDirDiv(dirname(__FILE__));
Sgmov_Lib::useView($dirDiv.'/CheckInput');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Qrc_CheckInput();
$view->execute();
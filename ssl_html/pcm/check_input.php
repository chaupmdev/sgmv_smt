<?php
/**
 * 法人引越輸送入力情報をチェックします。
 * @package    ssl_html
 * @subpackage PCM
 * @author     K.Hamada(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('pcm/CheckInput');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Pcm_CheckInput();
$view->execute();
?>
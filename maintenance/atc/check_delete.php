<?php
/**
 * ツアー配送料金マスタメンテナンス削除確認情報をチェックします。
 * @package    maintenance
 * @subpackage ATC
 * @author     T.Aono(SGS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('atc/CheckDelete');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Atc_CheckDelete();
$view->execute();
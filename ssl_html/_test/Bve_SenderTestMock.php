<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useComponents(array('Log', 'String'));
/**#@-*/

$array = array('$_POST' => $_POST, '$_FILES' => $_FILES);
$log = Sgmov_Component_String::toDebugString($array);
Sgmov_Component_Log::info($log);
?>
br
"HEADER"
"0","710","",""
"TRAILER"
br
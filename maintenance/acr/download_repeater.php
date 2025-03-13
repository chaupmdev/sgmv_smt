<?php
/**
 * クルーズリピーター情報をダウンロードします。
 * @package    maintenance
 * @subpackage ACR
 * @author     T.Aono(SGS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

$user_id  = 'sgmv_cruise';
$password = '{Ebi1PusQ$p<7kRX(R>Omq#Uihv2QJ)G';
$server = $_SERVER;
if (!isset($server['PHP_AUTH_USER'])
    || !isset($server['PHP_AUTH_PW'])
    || $server['PHP_AUTH_USER'] !== $user_id
    || $server['PHP_AUTH_PW']   !== $password
) {
    header('WWW-Authenticate: Basic realm="Private Page"');
    header('HTTP/1.0 401 Unauthorized');
    exit('fail');
}

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('acr/DownloadRepeater');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Acr_DownloadRepeater();
$view->execute();
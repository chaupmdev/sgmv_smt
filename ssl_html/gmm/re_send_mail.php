<?php
/**
 * イベント輸送サービスのメールアドレスを変更します。
 * @package    ssl_html
 * @subpackage EVE
 * @author     Juj-Yamagami(SP)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */

echo '現在ご利用できません。';
exit;

require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('gmm/ReSendMail');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Gmm_ReSendMail();
$view->execute();
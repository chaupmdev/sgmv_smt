<?php
/**
 * コミックマーケット99 個人向の貼付票PDFを出力します。
 * @package    ssl_html
 * @subpackage EVP
 * @author     Juj-Yamagami(SP)
 * @copyright  2021-2021 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

// ***************************************************************************
// ページが見つかりません
// ***************************************************************************
//GiapLN commnet 2022/04/12 
//header("Location: /evp/error_toiawase");
//exit;

// ***************************************************************************

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';

$p = filter_input ( INPUT_GET, "param" );

// 引数チェック
if(!checkId($p)){
	return;
}

// イベントごとに貼付け表を切り替える。
redirectByComiketId ( $p );

function checkId ($p) {

	if(strlen($p) != 11){
		return false;
	}

	if(!is_numeric($p)){
		return false;
	}

	$id = substr($p, 0, 10);
	$cd = substr($p, 10, 1);
	$sp = intval($id) % 7;
	if($sp !== intval($cd)){
		return false;
	}

	return true;
}

function redirectByComiketId ($param) {
	Sgmov_Lib::useAllComponents ( FALSE );
	//$db = Sgmov_Component_DB::getAdmin ();
	$db = Sgmov_Component_DB::getPublic();

	$comiket_id = intval(substr($param, 0, 10));

	$sql = "SELECT event_key FROM comiket WHERE id = $1";

	$list = $db->executeQuery ( $sql, array (
				$comiket_id
	) );

	if ($list->size() == 0) {
		return null;
	}

	$comiket = $list->get ( 0 );

	if (!empty($comiket["event_key"]) && $comiket["event_key"] != 'evp') { // eveの場合は（転送したら無限ループになるので）そのままviewへ送る
		//Sgmov_Component_Redirect::redirectPublicSsl("/{$comiket['event_key']}/paste_tag/{$param}");
        Sgmov_Component_Redirect::redirectPublicSsl("/404.html");
		exit;
	}
}

Sgmov_Lib::useView('evp/PasteTag');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Evp_PasteTag();
$view->execute();
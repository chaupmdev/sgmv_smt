<?php
/**
 * イベント輸送サービスのクレジット情報を出力します。
 * @package    /lib/view/eve
 * @author     Juj-Yamagami(SP)
 * @copyright  2018 Sp Media-Tec CO,.LTD. All rights reserved.
 */

/**
 * #@+
 * include files
 */
require_once dirname ( __FILE__ ) . '/../../Lib.php';
Sgmov_Lib::useAllComponents ( FALSE );

/**
 * #@-
 */
class Sgmov_View_Fmt_CreditInfo {

	/**
	 * 処理
	 */
	public function execute() {
		header('Access-Control-Allow-Origin: *');

		$addr = $_SERVER['SERVER_ADDR'];
		Sgmov_Component_Log::debug ( 'addr='.$addr );

		$referer = $_SERVER['HTTP_REFERER'];
		Sgmov_Component_Log::debug ( 'referer='.$referer );

		$p = filter_input ( INPUT_GET, "param" );

		if(!$this->check($p)){
			echo -1;
			exit;
		}
		$comiket_id = intval(substr($p, 0, 10));

		Sgmov_Component_Log::debug ( $comiket_id );

		$comiket = $this->selectComiket ( $comiket_id );

		Sgmov_Component_Log::debug ( $comiket );

		if ($comiket == null) {
			echo -2;
			exit;
		}

		$this->createString ( $comiket );

		exit;
	}

	/**
	 * チェックディジット
	 * @param string $p
	 */
	private function check($p){

		if(strlen($p) != 11){
			Sgmov_Component_Log::debug ( '11桁ではない' );
			return false;
		}

		if(!is_numeric($p)){
			Sgmov_Component_Log::debug ( '数値ではない' );
			return false;
		}

		$id = substr($p, 0, 10);
		$cd = substr($p, 10, 1);

		Sgmov_Component_Log::debug ( 'id:'.$id );
		Sgmov_Component_Log::debug ( 'cd:'.$cd );

		$sp = intval($id) % 7;

		Sgmov_Component_Log::debug ( 'sp:'.$sp );

		if($sp !== intval($cd)){
			Sgmov_Component_Log::debug ( 'CD不一致' );
			return false;
		}

		return true;
	}

	/**
	 * コミケ申込データを取得
	 *
	 * @param int $comiket_id
	 * @return array
	 */
	private function selectComiket($comiket_id) {
		$db = Sgmov_Component_DB::getAdmin ();

		$sql = "select
				c.id,
				c.receipted,
				c.payment_method_cd,
				c.convenience_store_cd,
				c.receipt_cd,
				c.authorization_cd,
				c.payment_order_id,
				c.transaction_id,
				c.auto_authoriresult
				from comiket c
				where c.id = $1 ";

		$list = $db->executeQuery ( $sql, array (
				$comiket_id
		) );

		Sgmov_Component_Log::debug ( 'size='.$list->size () );

		if ($list->size () == 0) {
			return null;
		}

		return $list->get ( 0 );
	}

	/**
	 * 文字列編集
	 *
	 * @param array $comiket
	 */
	private function createString($comiket) {
		Sgmov_Component_Log::debug ( 'createString start' );

		header("Content-type: application/json");
		echo json_encode($comiket);

		Sgmov_Component_Log::debug ( 'createString end' );

		return;
	}

}
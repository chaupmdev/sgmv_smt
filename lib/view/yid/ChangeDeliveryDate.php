<?php
/**
 * イベント輸送サービスの往路の搬入日を変更します。
 * @package    /lib/view/twf
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
class Sgmov_View_Yid_ChangeDeliveryDate {

	/**
	 * 処理
	 */
	public function execute() {
		header('Access-Control-Allow-Origin: *');

		$addr = $_SERVER['SERVER_ADDR'];
		Sgmov_Component_Log::debug ( 'addr='.$addr );

		$referer = $_SERVER['HTTP_REFERER'];
		Sgmov_Component_Log::debug ( 'referer='.$referer );

		$comiket_id = filter_input ( INPUT_GET, "comiket_id" );

		if(!$this->check($comiket_id)){
			echo -1;
			exit;
		}
		$comiket_id = intval(substr($comiket_id, 0, 10));

		Sgmov_Component_Log::debug ( 'comiket_id = '.$comiket_id );

		$delivery_date = filter_input ( INPUT_GET, "delivery_date" );
		Sgmov_Component_Log::debug ( $delivery_date );
		if(!$this->checkDate($delivery_date)){
			echo -2;
			exit;
		}

		$count = $this->updateComiketDetail ( $comiket_id, $delivery_date );

		echo strval($count);
		exit;
	}

	/**
	 * チェックディジットをチェック
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
	 * お届け日をチェック
	 * @param string $p
	 */
	private function checkDate($p){

		if(strlen($p) != 10){
			Sgmov_Component_Log::debug ( '10桁ではない' );
			return false;
		}

		list($Y, $m, $d) = explode('/', $p);

		$ret = checkdate($m, $d, $Y);

		if(!$ret){
			Sgmov_Component_Log::debug ( '日付ではない' );
		}

		return $ret;
	}

	/**
	 * コミケ申込明細データのお届け日を更新
	 *
	 * @param int $comiket_id
	 * @param string $delivery_date
	 * @return array
	 */
	private function updateComiketDetail($comiket_id, $delivery_date ) {
		$db = Sgmov_Component_DB::getAdmin ();

		$sql = "update
				 comiket_detail
				set
				 delivery_date = $1
				where
				 comiket_id = $2
				and
				 type = 1
				";

		$count = $db->executeUpdate ( $sql, array (
				$delivery_date, $comiket_id
		) );

		Sgmov_Component_Log::debug ( 'count='.$count );

		return $count;
	}

}
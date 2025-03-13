<?php

/**
 * イベント輸送サービスのデータを削除します。親-子-孫と関連データがありますが、親であるcomiketのみを削除します。
 * @package    /lib/view/rms
 * @author     Juj-Yamagami(SP)
 * @copyright  2018 Sp Media-Tec CO,.LTD. All rights reserved.
 */

/**
 * #@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);

/**
 * #@-
 */
class Sgmov_View_Qrc_DeleteComiket
{

	/**
	 * 処理
	 */
	public function execute()
	{
		header('Access-Control-Allow-Origin: *');

		$addr = $_SERVER['SERVER_ADDR'];
		Sgmov_Component_Log::debug('addr=' . $addr);

		$referer = $_SERVER['HTTP_REFERER'];
		Sgmov_Component_Log::debug('referer=' . $referer);

		$p = filter_input(INPUT_GET, "param");

		if (!$this->check($p)) {
			echo -1;
			exit;
		}
		$comiket_id = intval(substr($p, 0, 10));

		Sgmov_Component_Log::debug($comiket_id);

		$count = $this->deleteComiket($comiket_id);

		echo strval($count);
		exit;
	}

	/**
	 * チェックディジット
	 * @param string $p
	 */
	private function check($p)
	{

		if (strlen($p) != 11) {
			Sgmov_Component_Log::debug('11桁ではない');
			return false;
		}

		if (!is_numeric($p)) {
			Sgmov_Component_Log::debug('数値ではない');
			return false;
		}

		$id = substr($p, 0, 10);
		$cd = substr($p, 10, 1);

		Sgmov_Component_Log::debug('id:' . $id);
		Sgmov_Component_Log::debug('cd:' . $cd);

		$sp = intval($id) % 7;

		Sgmov_Component_Log::debug('sp:' . $sp);

		if ($sp !== intval($cd)) {
			Sgmov_Component_Log::debug('CD不一致');
			return false;
		}

		return true;
	}

	/**
	 * コミケ申込データを削除
	 *
	 * @param int $comiket_id
	 * @return int $count
	 */
	private function deleteComiket($comiket_id)
	{
		$db = Sgmov_Component_DB::getAdmin();

		$sql = "delete
				from comiket
				where id = $1 ";

		$count = $db->executeUpdate($sql, array(
			$comiket_id
		));

		Sgmov_Component_Log::debug('count=' . $count);

		return $count;
	}
}

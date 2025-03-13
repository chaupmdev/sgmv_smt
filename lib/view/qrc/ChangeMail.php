<?php

/**
 * イベント輸送サービスのメールアドレスを変更します。
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
class Sgmov_View_Qrc_ChangeMail
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

		$comiket_id = filter_input(INPUT_GET, "comiket_id");

		if (!$this->check($comiket_id)) {
			echo -1;
			exit;
		}
		$comiket_id = intval(substr($comiket_id, 0, 10));

		Sgmov_Component_Log::debug('comiket_id = ' . $comiket_id);

		$mail = filter_input(INPUT_GET, "mail");
		Sgmov_Component_Log::debug($mail);
		if (!$this->checkMail($mail)) {
			echo -2;
			exit;
		}

		$count = $this->updateComiket($comiket_id, $mail);

		echo strval($count);
		exit;
	}

	/**
	 * チェックディジットをチェック
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
	 * メールアドレスをチェック
	 * @param string $p
	 */
	private function checkMail($p)
	{

		if (strlen($p) > 100) {
			Sgmov_Component_Log::debug('100桁以内ではない');
			return false;
		}

		if (!filter_var($p, FILTER_VALIDATE_EMAIL)) {
			Sgmov_Component_Log::debug('メールアドレスとして不正');
			return false;
		}

		return true;
	}

	/**
	 * コミケ申込データのメールアドレスを更新
	 *
	 * @param int $comiket_id
	 * @param string $mail
	 * @return array
	 */
	private function updateComiket($comiket_id, $mail)
	{
		$db = Sgmov_Component_DB::getAdmin();

		$sql = "update
				 comiket
				set
				 mail = $1
				where
				 id = $2
				";

		$count = $db->executeUpdate($sql, array(
			$mail, $comiket_id
		));

		Sgmov_Component_Log::debug('count=' . $count);

		return $count;
	}
}

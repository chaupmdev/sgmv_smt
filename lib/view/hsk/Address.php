<?php

/**
 * @package    ClassDefFile
 * @author     J.Yamagami
 * @copyright  2018-2018 SP-MediaTec CO,.LTD. All rights reserved.
 */
/**
 * #@+
 * include files
 */
require_once dirname ( __FILE__ ) . '/../../Lib.php';
Sgmov_Lib::useView ( 'hsk/AddressCommon' );
/**
 * #@-
 */

/**
 * 品質選手権住所入力画面を表示します。
 *
 * @package View
 * @subpackage HSK
 * @author J.Yamagami
 * @copyright 2019-2019 SP-MediaTec CO,.LTD. All rights reserved.
 */
class Sgmov_View_Hsk_Address extends Sgmov_View_Hsk_AddressCommon {

	/**
	 * 機能ID
	 */
	const FEATURE_ID = 'EVE';

	/**
	 * EVE001の画面ID
	 */
	const GAMEN_ID_EVE001 = 'EVE001';

	/**
	 */
	public function executeInner() {
		$id = @$_GET ['param'];
		$this->checkId ( $id );

		$outInfo = array ();



		$kibobiList = array (
				"2019/11/25" => "2019/11/25",
				"2019/11/26" => "2019/11/26",
				"2019/11/27" => "2019/11/27",
				"2019/11/28" => "2019/11/28",
				"2019/11/29" => "2019/11/29"
		);

		$kibojiList = array (
				"指定なし" => "指定なし",
				"10:00-13:00" => "10:00-13:00",
				"12:00-15:00" => "12:00-15:00",
				"15:00-18:00" => "15:00-18:00",
				"18:00-20:00" => "18:00-20:00"
		);

		$outInfo ['kenList'] = $this->kenList;
		$outInfo ['kibobiList'] = $kibobiList;
		$outInfo ['kibojiList'] = $kibojiList;

		// セッションに情報があるかどうかを確認

		$session = Sgmov_Component_Session::get();
		$ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_EVE001);

		return array (
				'outInfo' => $outInfo,
				'ticket' => $ticket
		);
	}
}
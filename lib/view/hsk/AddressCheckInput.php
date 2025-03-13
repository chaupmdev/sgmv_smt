<?php

/**
 * @package    ClassDefFile
 * @author     J.Yamagami
 * @copyright  2019-2019 SP-MediaTec CO,.LTD. All rights reserved.
 */
require_once dirname ( __FILE__ ) . '/../../Lib.php';
Sgmov_Lib::useView ( 'hsk/AddressCommon' );
/**
 * 品質選手権住所登録入力チェックします。
 *
 * @package View
 * @subpackage HSK
 * @author J.Yamagami
 * @copyright 2019-2019 SP-MediaTec CO,.LTD. All rights reserved.
 */
class Sgmov_View_Hsk_AddressCheckInput extends Sgmov_View_Hsk_AddressCommon {
	public function executeInner() {
		$result = $this->checkInput ();
		return $result;
	}
}
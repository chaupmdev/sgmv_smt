<?php
/**
 * @package    ClassDefFile
 * @author     J.Yamagami
 * @copyright  2019-2019 SP-MediaTec CO,.LTD. All rights reserved.
 */
/**
 * #@+
 * include files
 */
require_once dirname ( __FILE__ ) . '/../../Lib.php';
Sgmov_Lib::useView ( 'hsk/AddressCommon' );
Sgmov_Lib::useServices ( array ('EnqueteSenshukenAddress') );

/**
 * #@-
 */

/**
 * 品質選手権住所入力完了画面を表示します。
 *
 * @package View
 * @subpackage HSK
 * @author J.Yamagami
 * @copyright 2019-2019 SP-MediaTec CO,.LTD. All rights reserved.
 */
class Sgmov_View_Hsk_AddressEnd extends Sgmov_View_Hsk_AddressCommon {

// 	/**
// 	 * 品質選手権住所入力データサービス
// 	 *
// 	 * @var Sgmov_Service_EnqueteSenshukenAddress
// 	 */
// 	private $_EnqueteSenshuken;

	/**
	 * コンストラクタでサービスを初期化します。
	 */
	public function __construct() {
		parent::__construct ();
		//$this->_EnqueteSenshuken = new Sgmov_Service_EnqueteSenshukenAddress ();
	}

	/**
	 */
	public function executeInner() {

		Sgmov_Component_Log::info ( $_POST );

		$id = @$_POST ['id'];
		if (! $this->checkId ( $id )) {
			Sgmov_Component_Redirect::redirectPublicSsl ( "/hsk/error" );
		}

		$checkInputRes = $this->checkInput ();
		if (@! empty ( $checkInputRes )) {
			Sgmov_Component_Redirect::redirectPublicSsl ( "/hsk/error" );
		}

		// DBへ接続
		$db = Sgmov_Component_DB::getPublic ();
		$insertInfo = array ();
		$dbKeyInfo = $this->_EnqueteSenshuken->getDbValInit ();
		foreach ( $dbKeyInfo as $key => $val ) {
			$insertInfo [$key] = $val;
		}

		// ID
		$insertInfo ['id'] = @$_POST ['id'];

		// お名前
		$insertInfo ['personal_name'] = @$_POST ['personal_name'];

		// 郵便番号1
		$insertInfo ['address_zip1'] = @$_POST ['address_zip1'];

		// 郵便番号2
		$insertInfo ['address_zip2'] = @$_POST ['address_zip2'];

		// 都道府県
		$insertInfo ['address_ken'] = @$_POST ['address_ken'];

		// 市区町村
		$insertInfo ['address_shi'] = @$_POST ['address_shi'];

		// 番地・建物名
		$insertInfo ['address_ban'] = @$_POST ['address_ban'];

		// 電話番号
		$insertInfo ['denwa'] = @$_POST ['denwa'];

		// 配達希望日
		$insertInfo ['kibobi'] = @$_POST ['kibobi'];

		// 配達希望時刻
		$insertInfo ['kiboji'] = @$_POST ['kiboji'];

		Sgmov_Component_Log::debug ( $insertInfo );

		$this->_EnqueteSenshuken->insert ( $db, $insertInfo );

		return true;
	}
}
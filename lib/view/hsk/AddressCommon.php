<?php
/**
 * @package    ClassDefFile
 * @author     J.Yamagami
 * @copyright  2019-2019 SP-MediaTec CO,.LTD. All rights reserved.
 */
require_once dirname ( __FILE__ ) . '/../../Lib.php';
Sgmov_Lib::useAllComponents ();
Sgmov_Lib::useView ( 'Public' );
Sgmov_Lib::useServices ( array (
		'EnqueteSenshukenAddress','SocketZipCodeDll'
) );
/**
 * 共通処理を管理する抽象クラスです。
 *
 * @package View
 * @subpackage Hsk
 * @author J.Yamagami
 * @copyright 2019-2019 SP-MediaTec CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Hsk_AddressCommon extends Sgmov_View_Public {

	// 都道府県
	protected $kenList = array (
			"1" =>"北海道",
			"2" =>"青森県",
			"3" =>"岩手県",
			"4" =>"宮城県",
			"5" =>"秋田県",
			"6" =>"山形県",
			"7" =>"福島県",
			"8" =>"茨城県",
			"9" =>"栃木県",
			"10" =>"群馬県",
			"11" =>"埼玉県",
			"12" =>"千葉県",
			"13" =>"東京都",
			"14" =>"神奈川県",
			"15" =>"新潟県",
			"16" =>"富山県",
			"17" =>"石川県",
			"18" =>"福井県",
			"19" =>"山梨県",
			"20" =>"長野県",
			"21" =>"岐阜県",
			"22" =>"静岡県",
			"23" =>"愛知県",
			"24" =>"三重県",
			"25" =>"滋賀県",
			"26" =>"京都府",
			"27" =>"大阪府",
			"28" =>"兵庫県",
			"29" =>"奈良県",
			"30" =>"和歌山県",
			"31" =>"鳥取県",
			"32" =>"島根県",
			"33" =>"岡山県",
			"34" =>"広島県",
			"35" =>"山口県",
			"36" =>"徳島県",
			"37" =>"香川県",
			"38" =>"愛媛県",
			"39" =>"高知県",
			"40" =>"福岡県",
			"41" =>"佐賀県",
			"42" =>"長崎県",
			"43" =>"熊本県",
			"44" =>"大分県",
			"45" =>"宮崎県",
			"46" =>"鹿児島県",
			"47" =>"沖縄県"
	);

	/**
	 * データサービス
	 *
	 * @var Sgmov_Service_EnqueteSenshukenAddress
	 */
	protected $_EnqueteSenshuken;

	/**
	 * ソケット通信で郵便番号DLLを検索し、郵便番号・住所情報を扱います。
	 * @var Sgmov_Service_SocketZipCodeDll
	 */
	protected $_SocketZipCodeDll;

	/**
	 * コンストラクタでサービスを初期化します。
	 */
	public function __construct() {
		$this->_EnqueteSenshuken = new Sgmov_Service_EnqueteSenshukenAddress ();
		$this->_SocketZipCodeDll = new Sgmov_Service_SocketZipCodeDll();
	}

	/**
	 *
	 * @return boolean
	 */
	protected function checkId($id = "") {
		if ($id == "") {
			$id = @$_POST ['id'];
		}

		if (@empty ( $id )) {
			Sgmov_Component_Redirect::redirectPublicSsl ( "/hsk/error" );
			exit ();
		}

		$db = Sgmov_Component_DB::getPublic ();
		$resInfo = $this->_EnqueteSenshuken->fetchEnqueteSenshukenAddressById ( $db, $id );

		if (@! empty ( $resInfo )) {
			$message = urlencode ( "既に登録済みです。" );
			Sgmov_Component_Redirect::redirectPublicSsl ( "/hsk/error?m={$message}" );
			exit ();
		}

		return true;
	}

	/**
	 *
	 * @return array
	 */
	protected function checkInput() {
		$errInfo = array ();
		$isErr = false;

		if (@empty ( $_POST ['id'] )) {
			Sgmov_Component_Redirect::redirectPublicSsl ( "/hsk/error" );
			exit ();
		}

		$personal_name = @$_POST ['personal_name'];
		$address_zip1 = @$_POST ['address_zip1'];
		$address_zip2 = @$_POST ['address_zip2'];
		$address_ken = @$_POST ['address_ken'];
		$address_shi = @$_POST ['address_shi'];
		$address_ban = @$_POST ['address_ban'];
		$denwa = @$_POST ['denwa'];
		$kibobi = @$_POST ['kibobi'];
		$kiboji = @$_POST ['kiboji'];

		$errInfo ['personal_name'] = "";
		$errInfo ['address_zip'] = "";
		$errInfo ['address_ken'] = "";
		$errInfo ['address_shi'] = "";
		$errInfo ['address_ban'] = "";
		$errInfo ['address'] = "";
		$errInfo ['denwa'] = "";
		$errInfo ['kibobi'] = "";
		$errInfo ['kiboji'] = "";

		// お名前
		if (@empty ( $personal_name )) {
			$errInfo ['personal_name'] = "・お名前を入力してください。";
			$isErr = true;
		}

		// 郵便番号
		$zipV = Sgmov_Component_Validator::createZipValidator($address_zip1, $address_zip2)->isNotEmpty()->isZipCode();
		if (!$zipV->isValid()) {
			$errInfo ['address_zip'] = "・郵便番号" . $zipV->getResultMessageTop();
			$isErr = true;
		}

		// 都道府県
		if (@empty ( $address_ken )) {
			$errInfo ['address_ken'] = "・都道府県を選択してください。";
			$isErr = true;
		} else {
			$address_ken = $this->kenList[$address_ken];
		}

		// 市区町村 必須チェック 14文字チェック WEBシステムNG文字チェック
		$v = Sgmov_Component_Validator::createSingleValueValidator($address_shi)->isNotEmpty()->isLengthLessThanOrEqualTo(14)->isNotHalfWidthKana()->isWebSystemNg();
		if (!$v->isValid()) {
			$errInfo ['address_shi'] = "・市区町村" . $v->getResultMessageTop();
			$isErr = true;
		} else {
			if ( !empty($address_shi) && empty($errInfo ['address_ken']) && strpos($address_shi, $address_ken) !== false) {
				$errInfo ['address_shi'] = "・市区町村には都道府県名は入力しないで下さい。";
				$isErr = true;
			}
		}

		// 番地・建物名 30文字チェック WEBシステムNG文字チェック
		$v = Sgmov_Component_Validator::createSingleValueValidator($address_ban)->isLengthLessThanOrEqualTo(30)->isNotHalfWidthKana()->isWebSystemNg();
		if (!$v->isValid()) {
			$errInfo ['address_ban'] = "・番地・建物名" . $v->getResultMessageTop();
			$isErr = true;
		} else {
			if ( !empty($address_ban) && empty($errInfo ['address_ken']) && strpos($address_ban, $address_ken) !== false) {
				$errInfo ['address_ban'] = "・番地・建物名には都道府県名は入力しないで下さい。";
				$isErr = true;
			}
		}

		// 電話番号 必須チェック 型チェック WEBシステムNG文字チェック
		$denwa = @str_replace(array('-', 'ー', '−', '―', '‐', 'ｰ'), "", $denwa);
                //GiapLN imp ticket #SMT6-381 2022/12/29
		$v = Sgmov_Component_Validator::createSingleValueValidator($denwa)->isNotEmpty()->isPhoneHyphen()->isLengthLessThanOrEqualToForPhone();
		if (!$v->isValid()) {
			$errInfo ['denwa'] = "・電話番号" . $v->getResultMessageTop();
			$isErr = true;
		} else {
			$v = Sgmov_Component_Validator::createSingleValueValidator($denwa)->isLengthMoreThanOrEqualTo(8)->isLengthLessThanOrEqualTo(12);
			if (!$v->isValid()) {
				$errInfo ['denwa'] = "・電話番号の数値部分" . $v->getResultMessageTop();
				$isErr = true;
			}
		}

		// 住所チェック
		if(empty($errInfo ['address_zip'] ) && empty($errInfo ['address_ken'] ) && empty($errInfo ['address_shi']) && empty($errInfo ['address_ban']) ){
			$receive = $this->_getAddress($address_zip1.$address_zip2 , $address_ken.$address_shi.$address_ban);
			if (empty($receive['ShopCodeFlag'])) {
				$errInfo ['address'] = "・住所の入力内容をお確かめください。";
				$isErr = true;
				// 			} elseif (!empty($receive['ExchangeFlag'])) {
				// 				$errInfo ['address'] = "・住所は配達できない地域の恐れがあります。"; // 不可地区でエラーにしてはいけない
				// 				$isErr = true;
			} elseif (!empty($receive['TimeZoneFlag']) && (!empty($kiboji) && $kiboji != "指定なし")) {
				$errInfo ['address'] = "・住所は時間帯指定できない地域の恐れがあります。";
				$isErr = true;
				// 			} elseif (!empty($receive['RelayFlag'])) {
				// 				$errInfo ['address'] = "・住所は配達できない地域の恐れがあります。";// 不可地区でエラーにしてはいけない
				// 				$isErr = true;
			}
		}

		// 配達希望日
		if (@empty ( $kibobi )) {
			$errInfo ['kibobi'] = "・配達希望日を選択してください。";
			$isErr = true;
		}

		// 配達時間
		if (@empty ( $kiboji )) {
			$errInfo ['kiboji'] = "・配達時間を選択してください。";
			$isErr = true;
		}

		if ($isErr) {
			// エラーメッセージ全て
			$errInfo ['errMsgAll'] = "";

			foreach ( $errInfo as $key => $val ) {
				if (@! empty ( $val )) {
					$errInfo ['errMsgAll'] .= "<a href='#alert-{$key}' onclick='movePageLink(this);return false;' class='pagelink-err anchor-link'>{$val}</a><br>";
					$errInfo [$key] = "<span id='alert-{$key}'></span>{$val}";
				}
			}
			//$errInfo ['errMsgAll'] = "以下の入力エラーがあります。<br/>(メッセージを押すと該当箇所にジャンプします)<br/><br/>" . $errInfo ['errMsgAll'];
			$errInfo ['errMsgAll'] = "以下の入力エラーがあります。<br/><br/>" . $errInfo ['errMsgAll'];
			$errInfo ['isErr'] = true;
		} else {
			$errInfo = array ();
		}

		return $errInfo;
	}

	/**
	 * 住所情報を取得します。
	 * @param type $zip
	 * @param type $address
	 * @return type
	 */
	private function _getAddress($zip, $address) {
		return $this->_SocketZipCodeDll->searchByAddress($address, $zip);
	}

}
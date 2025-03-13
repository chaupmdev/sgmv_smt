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
require_once dirname ( __FILE__ ) . '/../Lib.php';
Sgmov_Lib::useAllComponents ( TRUE );
/**
 * #@-
 */

/**
 * アンケート品質選手権住所登録情報を扱います。
 *
 * @package Service
 * @author J.Yamagami
 * @copyright 2019-2019 SP-MediaTec CO,.LTD. All rights reserved.
 */
class Sgmov_Service_EnqueteSenshukenAddress {

	// トランザクションフラグ
	private $transactionFlg = TRUE;

	/**
	 * トランザクションフラグ設定.
	 *
	 * @param type $flg
	 *        	TRUE=内部でトランザクション処理する/FALSE=内部でトランザクション処理しない
	 */
	public function setTrnsactionFlg($flg) {
		$this->transactionFlg = $flg;
	}

	/**
	 *
	 * @param type $db
	 * @param type $id
	 * @return type
	 */
	public function fetchEnqueteSenshukenAddressById($db, $id) {
		$query = 'select * from enquete_senshuken_address where id=$1';

		if (empty ( $id )) {
			return array ();
		}

		$result = $db->executeQuery ( $query, array (
				$id
		) );
		$resSize = $result->size ();
		if (empty ( $resSize )) {
			return array ();
		}

		$row = $result->get ( 0 );

		return $row;
	}

	/**
	 *
	 * @return type
	 */
	public function getDbValInit() {
		return array (
				"id" => '',
				"personal_name" => '',
				"address_zip1" => '',
				"address_zip2" => '',
				"address_ken" => '',
				"address_shi" => '',
				"address_ban" => '',
				"denwa" => '',
				"kibobi" => '',
				"kiboji" => ''
		);
	}

	/**
	 * 品質選手権の住所情報をDBに保存します。
	 *
	 * @param Sgmov_Component_DB $db
	 *        	DB接続
	 * @param array $data
	 *        	保存するデータ
	 */
	public function insert($db, $data) {

		// この順番でSQLのプレースホルダーに適用されます。
		$dbValInitInfo = $this->getDbValInit ();
		$keys = array_keys ( $dbValInitInfo );

		// パラメータのチェック
		$params = array ();
		foreach ( $keys as $key ) {
			if (! array_key_exists ( $key, $data )) {
				throw new Sgmov_Component_Exception ( '$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT );
			}

			$params [] = $data [$key];
		}

		$query = "
insert into enquete_senshuken_address
(
id,
personal_name,
address_zip1,
address_zip2,
address_ken,
address_shi,
address_ban,
denwa,
kibobi,
kiboji,
created,
modified
)
values
(
$1,
$2,
$3,
$4,
$5,
$6,
$7,
$8,
$9,
$10,
current_timestamp,
current_timestamp
);
";
		$query = preg_replace ( '/\s+/u', ' ', trim ( $query ) );
		if ($this->transactionFlg) {
			$db->begin ();
		}
                Sgmov_Component_Log::debug("####### START INSERT enquete_senshuken_address #####");
		$res = $db->executeUpdate ( $query, $params );
		if (empty ( $res )) {
			throw new Exception ();
		}
                Sgmov_Component_Log::debug("####### END INSERT enquete_senshuken_address #####");
		if ($this->transactionFlg) {
			$db->commit ();
		}
	}
}
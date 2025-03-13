<?php
/**
 * BCMイベント輸送サービスのコンビニ先払専用のお申し込み送信バッチ。
 * @package    /lib/process
 * @subpackage BCM
 * @author     Juj-Yamagami(SP)
 * @copyright  2018 Sp Media-Tec CO,.LTD. All rights reserved.
 */

/**
 * #@+
 * include files
 */
require_once dirname ( __FILE__ ) . '/../Lib.php';
Sgmov_Lib::useView ( 'CommonConst' );
Sgmov_Lib::useAllComponents ( FALSE );
Sgmov_Lib::useProcess ( array (
		'BcmSender',
		'BcmResponse'
) );
Sgmov_Lib::useServices ( array (
		'BcmData',
		'SgFinancial'
) );
/**
 * #@-
 */
class Sgmov_Process_Bcm extends Sgmov_Process_BcmSender {

	/**
	 * 起動チェックファイル名
	 */
	const OPRATION_FILE_NAME = 'operation_bcm.txt';

	/**
	 * ステータス：登録済
	 */
	const STATUS_ENTRY = 1;

	/**
	 * ステータス：申込み者へメール送付済
	 */
	const STATUS_MAIL = 2;

	/**
	 * ステータス：連携データ送信済
	 */
	const STATUS_SEND = 3;

	/**
	 * ステータス：完了
	 */
	const STATUS_FINISH = 4;

	/**
	 * リザルト：未送信
	 */
	const RESULT_UNSENT = 0;

	/**
	 * リザルト：送信失敗
	 */
	const RESULT_FAILURE = 1;

	/**
	 * リザルト：リトライオーバー
	 */
	const RESULT_RETRY = 2;

	/**
	 * リザルト：送信成功
	 */
	const RESULT_SUCCESS = 3;

	/**
	 * 支払方法：コンビニ後払い
	 */
	const PAYMENT_METHOD_CONVINI_AFTER = 4;

	/**
	 * 処理
	 */
	public function execute() {

		// バッチ起動チェックと起動
		$check1 = $this->startBcmcheck ( Sgmov_Lib::getLogDir () . '/' . self::OPRATION_FILE_NAME );
		if ($check1 === false) {
			$this->errorInformation ( 'startBcm' );
		}

		// 1件以上対象があればバッチ処理の実行
		$list = $this->selectComiketList ();
		$comiket = null;
		for($i = 0; $i < $list->size (); ++ $i) {
			$comiket = $list->get ( $i );
			$this->bcmOutline ( $comiket );
		}

		// 1件以上対象があればキャンセル処理の実行
		$list = $this->selectCancelList ();
		$comiket = null;
		for($i = 0; $i < $list->size (); ++ $i) {
			$comiket = $list->get ( $i );
			$this->bclOutline ( $comiket );
		}

		// コンビニ後払リトライ対応
		$list = $this->selectSgfCancelList ();
		$comiket = null;
		for($i = 0; $i < $list->size (); ++ $i) {
			$comiket = $list->get ( $i );
			$this->sgfCancelOutline ( $comiket );
		}

		// バッチ終了処理
		$check2 = $this->stopBcm ( Sgmov_Lib::getLogDir () . '/' . self::OPRATION_FILE_NAME );
		if ($check2 == false) {
			$this->errorInformation ( 'stopBcm' );
		}

		return;
	}

	/**
	 * バッチ起動チェック
	 *
	 * @param string $file
	 * @return true or false
	 */
	private function startBcmcheck($file) {
		$check = file_exists ( $file );
		if ($check === true) {
			return false;
		} else {
			$check = touch ( $file );
			return true;
		}
	}

	/**
	 * システム管理者へバッチの起動失敗メールを送信
	 *
	 * @param string $status
	 */
	private function errorInformation($status) {

		// システム管理者メールアドレスを取得する。
		$mail_to = Sgmov_Component_Config::getLogMailTo ();

		// テンプレートメールを送信する。
		Sgmov_Component_Mail::sendTemplateMail ( $status, dirname ( __FILE__ ) . '/../../lib/mail_template/bcm_error.txt', $mail_to );

		exit ();
	}

	/**
	 * コミケ申込データでコンビニ決済が入金済みになったデータを取得
	 *
	 * @return Sgmov_Component_DBResult
	 */
	private function selectComiketList() {
		$db = Sgmov_Component_DB::getAdmin ();
		// TODO オンライン連携と同時に走った場合を考慮し、batch_statusを2,3に限定する検討。ただしコンビニ前払いは1,2,3とする。
                // 
                // ▼ 2019/07/30 sawada
                // ＳＧムービングサイト：コンビニ決済入金処理エラーが発生した場合（集荷当日に入金があった場合）
                // 手動で業務連携したい場合は、where句 の 「c.id = 346」の「346」部分を連携対象の「comiket.id(申込ID)」にしてください(業務連携バッチが拾ってくれます)
		$sql = "select
				 c.*
				from
				 comiket as c
				 left join comiket_detail as d on d.comiket_id = c.id and d.type = 1
				where
				 c.batch_status in (1, 2, 3)
				and
				(
				 (c.payment_method_cd = 1 and c.merchant_result = 1 and c.receipted is not null and (d.comiket_id is null or c.receipted < d.collect_date or c.id in (6604,6367)))
				 or
				 (c.payment_method_cd = 2 and c.merchant_result = 1 and c.receipted is not null)
				 or
				 (c.payment_method_cd = 3)
				 or
				 (c.payment_method_cd = 4 and c.merchant_result = 1 and c.auto_authoriresult = 'OK')
				 or
				 (c.payment_method_cd = 5)
				 or
				 (c.payment_method_cd = 6)
				)
				and
				 c.id <= 99999999
				order by
				 c.id
				";

		$list = $db->executeQuery ( $sql );

		Sgmov_Component_Log::debug ( 'メイン size=' . $list->size () );

		return $list;
	}

	/**
	 * バッチメイン処理
	 *
	 * @param object $comiket
	 */
	private function bcmOutline(&$comiket) {
		Sgmov_Component_Log::debug ( 'バッチメイン処理 batch_status:' . $comiket ["batch_status"] );

		if ($comiket ["batch_status"] == self::STATUS_ENTRY || $comiket ["batch_status"] == self::STATUS_MAIL) {
			Sgmov_Component_Log::debug ( 'バッチメイン処理IFデータ送信する' );
			$this->sendData ( $comiket );

			if (($comiket ['sendSts'] == '0' || $comiket ['sendSts'] == '4') && $comiket ['auto_authoriresult'] == 'OK' && $comiket ['payment_method_cd'] == self::PAYMENT_METHOD_CONVINI_AFTER) {
				$this->sendSgFinancialShipmentReport ( $comiket );
			} else {
			}
		} else {
			Sgmov_Component_Log::debug ( 'バッチメイン処理 IFデータ送信しない' );
		}

		if ($comiket ["batch_status"] == self::STATUS_SEND) {
			Sgmov_Component_Log::debug ( 'バッチメイン処理 ステータスを「4：完了」へ更新（必要があれば管理者へエラーメール送信）する' );
			$this->SendMailManager ( $comiket ); // ステータス更新と管理者へエラーメール送信（ただし実際にメール送信するのはエラー時のみ）
		} else {
			Sgmov_Component_Log::debug ( 'バッチメイン処理 ステータスを「4：完了」へ更新しない' );
		}

		return;
	}

	/**
	 * IFデータ送信
	 *
	 * @param object $comiket
	 * @return object $comiket
	 */
	private function sendData(&$comiket) {

		// データ生成
		$service = new Sgmov_Service_BcmData ();
		$csvdata = $service->makeIFcsv ( $comiket );

		// データ送信
		$res = null;
		try {
			$res = Sgmov_Process_BcmSender::sendCsvToWs ( 'EVENT_CNV_' . date ( 'YmdHis' ) . '.csv', $csvdata );
		} catch ( Sgmov_Component_Exception $sce ) {
			Sgmov_Component_Log::debug ( $sce ); // throwしない
		}

		Sgmov_Component_Log::debug (mb_convert_encoding($res, 'UTF-8', 'Shift_JIS'));

		$responce = new Sgmov_Process_BcmResponse ();
		$responce->initialize ( $res );

		Sgmov_Component_Log::debug('sendSts:'.$responce->sendSts);

		$comiket ["sendSts"] = $responce->sendSts;

		// レスポンス値によって処理のふりわけ
		switch ($responce->sendSts) {

			case 0 : // 成功：update バッチ処理状況「送信済」 送信結果「成功」
				$db = Sgmov_Component_DB::getAdmin ();
				$db->begin ();
				$db->executeUpdate ( "UPDATE comiket SET batch_status = " . self::STATUS_SEND . ", send_result = " . self::RESULT_SUCCESS . ", delivery_slip_no = $1, sent = current_timestamp, modified = current_timestamp WHERE id = $2;", array (
						$responce->deliverySlipNo,
						$comiket ['id']
				) );
				$db->commit ();
				$comiket ["batch_status"] = self::STATUS_SEND;
				$comiket ["send_result"] = self::RESULT_SUCCESS;
				$comiket ["delivery_slip_no"] = $responce->deliverySlipNo;
				break;

			case 1 : // 型や桁などの形式が不正な場合：update バッチ処理状況「送信済」 送信結果「失敗」
				$db = Sgmov_Component_DB::getAdmin ();
				$db->begin ();
				$db->executeUpdate ( "UPDATE comiket SET batch_status = " . self::STATUS_SEND . ", send_result = " . self::RESULT_FAILURE . ", sent = current_timestamp, modified = current_timestamp WHERE id = $1;", array (
						$comiket ['id']
				) );
				$db->commit ();
				$comiket ["batch_status"] = self::STATUS_SEND;
				$comiket ["send_result"] = self::RESULT_FAILURE;
				break;

			case 2 : // システム障害 update 送信リトライ数「+1」
			case 3 : // SQLデータベースエラー update 送信リトライ数「+1」
				$db = Sgmov_Component_DB::getAdmin ();
				$db->begin ();
				$db->executeUpdate ( "UPDATE comiket SET retry_count = retry_count + 1, sent = current_timestamp, modified = current_timestamp WHERE id = $1;", array (
						$comiket ['id']
				) );
				$db->commit ();
				++ $comiket ["retry_count"];

				// 送信リトライ階数が21以上の場合バッチ処理状況「送信済」 送信結果「リトライオーバー」
				if ($comiket ["retry_count"] >= 21) {
					$db = Sgmov_Component_DB::getAdmin ();
					$db->begin ();
					$db->executeUpdate ( "UPDATE comiket SET batch_status = " . self::STATUS_SEND . ", send_result=" . self::RESULT_RETRY . ", sent = current_timestamp, modified = current_timestamp WHERE id = $1;", array (
							$comiket ['id']
					) );
					$db->commit ();
					$comiket ["batch_status"] = self::STATUS_SEND;
					$comiket ["send_result"] = self::RESULT_RETRY;
				}
				break;

			case 4 : // 一意制約違反(送信競合)：update バッチ処理状況「送信済」 送信結果「成功」
				$db = Sgmov_Component_DB::getAdmin ();
				$db->begin ();
				$db->executeUpdate ( "UPDATE comiket SET batch_status = " . self::STATUS_SEND . ", send_result = " . self::RESULT_SUCCESS . ", delivery_slip_no = $1, sent = current_timestamp, modified = current_timestamp WHERE id = $2;", array (
						$responce->deliverySlipNo,
						$comiket ['id']
				) );
				$db->commit ();
				$comiket ["batch_status"] = self::STATUS_SEND;
				$comiket ["send_result"] = self::RESULT_SUCCESS;
				$comiket ["delivery_slip_no"] = $responce->deliverySlipNo;
				break;

			default : // それ以外 送信リトライ数「+1」 送信リトライ階数が21以上の場合バッチ処理状況「送信済」 送信結果「リトライオーバー」（タイムアウト）
				$db = Sgmov_Component_DB::getAdmin ();
				$db->begin ();
				$db->executeUpdate ( "UPDATE comiket SET retry_count = retry_count + 1, sent = current_timestamp, modified = current_timestamp WHERE id = $1;", array (
						$comiket ['id']
				) );
				$db->commit ();
				++ $comiket ["retry_count"];
				if ($comiket ["retry_count"] >= 21) {
					$db = Sgmov_Component_DB::getAdmin ();
					$db->begin ();
					$db->executeUpdate ( "UPDATE comiket SET batch_status = " . self::STATUS_SEND . ", send_result=" . self::RESULT_RETRY . ", sent = current_timestamp, modified = current_timestamp WHERE id = $1;", array (
							$comiket ['id']
					) );
					$db->commit ();
					$comiket ["batch_status"] = self::STATUS_SEND;
					$comiket ["send_result"] = self::RESULT_RETRY;
				}
				break;
		}

		return $comiket;
	}

	/**
	 * 取引登録が正常終了かつ業務連携バッチ処理の結果が成功なら出荷報告処理を行う
	 *
	 * @param array $comiket
	 */
	private function sendSgFinancialShipmentReport(&$comiket) {
		if (empty ( $comiket ['transaction_id'] )) {
			Sgmov_Component_Log::info ( 'transaction_id is empty' );
			return;
		}

		if (empty ( $comiket ['delivery_slip_no'] )) {
			Sgmov_Component_Log::info ( 'delivery_slip_no is empty' );
			return;
		}

		$param = array (
				'res_sgf_transactionId' => $comiket ['transaction_id']
		);
		try {
			$service = new Sgmov_Service_SgFinancial ();
			$shipmentCompResArr = $service->requestShipmentReport ( $param, $comiket ['delivery_slip_no'] );
		} catch ( Exception $e ) {
			$shipmentCompResArr ['result'] = 'NG';
		}
		if ($shipmentCompResArr ['result'] == 'OK') {
			Sgmov_Component_Log::info ( 'SgFinancial-ShipmentReport-OK' );
		} else {
			$mail_to = Sgmov_Component_Config::getLogMailTo (); // 出荷報告処理APIでエラーとなった場合はシステム管理者にエラーメールを送信する
			Sgmov_Component_Mail::sendTemplateMail ( $param, dirname ( __FILE__ ) . '/../mail_template/eve_error_for_sgfinancial_shipment_report.txt', $mail_to );
			Sgmov_Component_Log::info ( 'SgFinancial-ShipmentReport-NG' );
			Sgmov_Component_Log::info ( $shipmentCompResArr );
		}
		return;
	}

	/**
	 * 管理者へメール送信（送信エラー時のみ）
	 *
	 * @param object $comiket
	 * @return object $comiket
	 */
	private function SendMailManager(&$comiket) {
		if ($comiket ["send_result"] == self::RESULT_FAILURE || $comiket ["send_result"] == self::RESULT_RETRY) {

			// システム管理者メールアドレスを取得する。
			$mail_to = Sgmov_Component_Config::getLogMailTo ();

			// メールを送信する。
			Sgmov_Component_Mail::sendTemplateMail ( $comiket, dirname ( __FILE__ ) . '/../../lib/mail_template/bcm_error_send.txt', $mail_to );
		}

		$db = Sgmov_Component_DB::getAdmin ();
		$db->begin ();
		$db->executeUpdate ( "UPDATE comiket SET batch_status = " . self::STATUS_FINISH . ", modified = current_timestamp WHERE id = $1;", array (
				$comiket ['id']
		) );
		$db->commit ();
		$comiket ["batch_status"] = self::STATUS_FINISH;

		return $comiket;
	}

	/**
	 * バッチ終了処理
	 *
	 * @param string $file
	 * @return true or false
	 */
	private function stopBcm($file) {
		$check = unlink ( $file );
		return $check;
	}

	/**
	 * コミケ申込データでキャンセルになったデータを取得
	 *
	 * @return Sgmov_Component_DBResult
	 */
	private function selectCancelList() {
		$db = Sgmov_Component_DB::getAdmin ();
		$sql = "select
				 c.*
				from
				 comiket as c
				where
				 c.del_flg = 1
				and
				 c.send_result = 3
				and
				 c.batch_status = 4
				and
				 (c.del_retry_count is null or c.del_retry_count < 21)
				order by
				 c.id
				";
		$list = $db->executeQuery ( $sql );
		Sgmov_Component_Log::debug ( 'キャンセル size=' . $list->size () );
		return $list;
	}

	/**
	 * キャンセルメイン処理
	 *
	 * @param object $comiket
	 */
	private function bclOutline(&$comiket) {
		Sgmov_Component_Log::debug ( 'キャンセルメイン処理 IFデータ送信する' );
		$this->sendCancel ( $comiket );
		if ($comiket ["del_flg"] == 1 && $comiket ["del_retry_count"] >= 21 ) {
			Sgmov_Component_Log::debug ( 'キャンセルメイン処理 管理者へエラーメール送信' );
			$this->SendMailCancel ( $comiket );
		}
		return;
	}

	/**
	 * IFキャンセル送信
	 *
	 * @param object $comiket
	 * @return object $comiket
	 */
	private function sendCancel(&$comiket) {

		// データ生成
		$service = new Sgmov_Service_BcmData ();
		$csvdata = $service->makeIFcancel ( $comiket );

		// データ送信
		$res = null;
		try {
			$cancel = 1;
			$res = Sgmov_Process_BcmSender::sendCsvToWs ( 'EVENT_CANCEL_' . date ( 'YmdHis' ) . '.csv', $csvdata , $cancel);
		} catch ( Sgmov_Component_Exception $sce ) {
			Sgmov_Component_Log::debug ( $sce ); // throwしない
		}

		Sgmov_Component_Log::debug (mb_convert_encoding($res, 'UTF-8', 'Shift_JIS'));

		$responce = new Sgmov_Process_BcmResponse ();
		$responce->initialize ( $res );

		Sgmov_Component_Log::debug('sendSts:'.$responce->sendSts);

		$comiket ["sendSts"] = $responce->sendSts;

		// レスポンス値によって処理のふりわけ
		switch ($responce->sendSts) {

			case 0 : // 成功：update バッチ処理状況「送信済」 送信結果「成功」
			case 4 : // 該当データが存在しない
				$db = Sgmov_Component_DB::getAdmin ();
				$db->begin ();
				$db->executeUpdate ( "UPDATE comiket SET del_flg = 2, modified = current_timestamp WHERE id = $1;", array ($comiket ['id']) );
				$db->commit ();
				$comiket ["del_flg"] = 2;
				break;

			case 1 : // 型や桁などの形式が不正な場合
			case 5 : // 該当データが「集荷依頼送信済」
				$db = Sgmov_Component_DB::getAdmin ();
				$db->begin ();
				$db->executeUpdate ( "UPDATE comiket SET del_retry_count = 99, modified = current_timestamp WHERE id = $1;", array ($comiket ['id']) );
				$db->commit ();
				$comiket ["del_flg"] = 1;
				$comiket ["del_retry_count"] = 99;
				break;

			default : // 2:システム障害, 3:SQLデータベースエラー, それ以外 送信リトライ数「+1」 送信リトライ階数が21以上の場合バッチ処理状況「送信済」 送信結果「リトライオーバー」（タイムアウト）
				$db = Sgmov_Component_DB::getAdmin ();
				$db->begin ();
				$db->executeUpdate ( "UPDATE comiket SET del_retry_count = coalesce(del_retry_count, 0) + 1, modified = current_timestamp WHERE id = $1;", array ($comiket ['id']) );
				$db->commit ();
				$comiket ["del_flg"] = 1;
				$comiket ["del_retry_count"]++;
				break;
		}

		return $comiket;
	}

	/**
	 * 管理者へキャンセル失敗メール送信（送信エラー時のみ）
	 *
	 * @param object $comiket
	 * @return object $comiket
	 */
	private function SendMailCancel(&$comiket) {
		$mail_to = Sgmov_Component_Config::getLogMailTo (); // システム管理者メールアドレスを取得する。
		Sgmov_Component_Mail::sendTemplateMail ( $comiket, dirname ( __FILE__ ) . '/../../lib/mail_template/bcm_error_cancel_send.txt', $mail_to ); // メールを送信する。
		return $comiket;
	}

	/**
	 * コミケ申込データでSGFキャンセルになったデータを取得
	 *
	 * @return Sgmov_Component_DBResult
	 */
	private function selectSgfCancelList() {
		$db = Sgmov_Component_DB::getAdmin ();
		$sql = "select
				 c.*
				from
				 comiket as c
				where
				 c.sgf_cancel_flg = 1
				and
				 (c.sgf_cancel_retry_count is null or c.sgf_cancel_retry_count < 21)
				order by
				 c.id
				";
		$list = $db->executeQuery ( $sql );
		Sgmov_Component_Log::debug ( 'SGFキャンセル size=' . $list->size () );
		return $list;
	}

	/**
	 * SGFキャンセルメイン処理
	 *
	 * @param object $comiket
	 */
	private function sgfCancelOutline(&$comiket) {
		Sgmov_Component_Log::debug ( 'SGFキャンセルメイン処理 APIデータ送信する' );
		$this->sendSgfCancel ( $comiket );
		if ($comiket ["sgf_cancel_flg"] == 1 && $comiket ["sgf_cancel_retry_count"] >= 21 ) {
			Sgmov_Component_Log::debug ( 'SGFキャンセルメイン処理 管理者へエラーメール送信' );
			$this->SendMailSgfCancel ( $comiket );
		}
		return;
	}

	/**
	 * SGFキャンセルAPI送信
	 *
	 * @param object $comiket
	 * @return object $comiket
	 */
	private function sendSgfCancel(&$comiket) {

		$sgf = new Sgmov_Service_SgFinancial();

		$cancelResArr = array();
		try {
			$cancelResArr = $sgf->requestCancel(array('res_sgf_transactionId' => $comiket['transaction_id']));
		} catch(Exception $e) {
			$cancelResArr["result"] = "NG";
		}

		if($cancelResArr["result"] == "OK") {
			Sgmov_Component_Log::info("BCM sendSgfCancel OK " . $comiket['id'] . " " . $comiket['transaction_id']);

			// sgf_cancel_flgを2：送信済みへ更新
			$db = Sgmov_Component_DB::getAdmin ();
			$db->begin ();
			$db->executeUpdate ( "UPDATE comiket SET sgf_cancel_flg = 2, modified = current_timestamp WHERE id = $1;", array ($comiket ['id']) );
			$db->commit ();
			$comiket ["sgf_cancel_flg"] = 2;

		} else {
			Sgmov_Component_Log::info("BCM sendSgfCancel NG " . $comiket['id'] . " " . $comiket['transaction_id'] . " " . ($comiket ["sgf_cancel_retry_count"]+1) );

			// リトライ回数をインクリメントする
			$db = Sgmov_Component_DB::getAdmin ();
			$db->begin ();
			$db->executeUpdate ( "UPDATE comiket SET sgf_cancel_retry_count = coalesce(sgf_cancel_retry_count, 0) + 1, modified = current_timestamp WHERE id = $1;", array ($comiket ['id']) );
			$db->commit ();
			$comiket ["sgf_cancel_retry_count"]++;
		}

		return $comiket;
	}

	/**
	 * 管理者へSGFキャンセル失敗メール送信
	 *
	 * @param object $comiket
	 * @return object $comiket
	 */
	private function SendMailSgfCancel(&$comiket) {
		$mail_to = Sgmov_Component_Config::getLogMailTo (); // システム管理者メールアドレスを取得する。
		Sgmov_Component_Mail::sendTemplateMail ( $comiket, dirname ( __FILE__ ) . '/../../lib/mail_template/bcm_error_sgf_cancel_send.txt', $mail_to );
		return $comiket;
	}
}
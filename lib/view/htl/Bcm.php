<?php
/**
 * イベント輸送サービスのお申し込み送信機能です。
 * @package    /lib/view/eve
 * @author     Juj-Yamagami(SP)
 * @copyright  2018 Sp Media-Tec CO,.LTD. All rights reserved.
 */

/**
 * #@+
 * include files
 */
require_once dirname ( __FILE__ ) . '/../../Lib.php';
Sgmov_Lib::useView ( 'CommonConst' );
Sgmov_Lib::useAllComponents ( FALSE );
Sgmov_Lib::useProcess ( array (
		'BcmSender',
		'BcmResponse'
) );
Sgmov_Lib::useServices ( 'BcmData' );
/**
 * #@-
 */
class Sgmov_View_Eve_Bcm extends Sgmov_Process_BcmSender {

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
	 * 処理
	 *
	 * @param int $comiket_id
	 */
	public function execute($comiket_id) {
		Sgmov_Component_Log::debug ( $comiket_id );

		// 1件以上対象があればバッチ処理の実行
		$comiket = $this->selectComiket ( $comiket_id );

		if ($comiket != null) {
			$this->bcmOutline ( $comiket );
		}

		Sgmov_Component_Log::debug ( $comiket );

		return $comiket;
	}

	/**
	 * コミケ申込データを取得
	 *
	 * @param int $comiket_id
	 * @return object $comiket
	 */
	private function selectComiket($comiket_id) {
		$db = Sgmov_Component_DB::getAdmin ();

		$sql = "select
				*
				from
				 comiket
				where
				 id = $1
				and
				 payment_method_cd <> 1
				and
				(
				 (payment_method_cd = 2 and merchant_result = 1 and receipted is not null)
				 or
				 (payment_method_cd = 3)
				 or
				 (payment_method_cd = 4 and merchant_result = 1 and auto_authoriresult = 'OK')
				 or
				 (payment_method_cd = 5)
				)
				";

		$list = $db->executeQuery ( $sql, array (
				$comiket_id
		) );

		Sgmov_Component_Log::debug ( 'size=' . $list->size () );

		if ($list->size () == 0) {
			return null;
		}

		return $list->get ( 0 );
	}

	/**
	 * バッチメイン処理
	 *
	 * @param object $comiket
	 */
	private function bcmOutline(&$comiket) {
		Sgmov_Component_Log::debug ( 'batch_status:' . $comiket ["batch_status"] );

		// オンラインなので基本的には「2:メール送付済」のはず
		if ($comiket ["batch_status"] == self::STATUS_ENTRY || $comiket ["batch_status"] == self::STATUS_MAIL) {
			Sgmov_Component_Log::debug ( 'IFデータ送信' );
			$this->sendData ( $comiket );
		}

		if ($comiket ["batch_status"] == self::STATUS_SEND) {
			Sgmov_Component_Log::debug ( 'ステータスを「4：完了」へ更新（必要があれば管理者へエラーメール送信）する' );
			$this->SendMailManager ( $comiket ); // ステータス更新と管理者へエラーメール送信（ただし実際にメール送信するのはエラー時のみ）
		} else {
			Sgmov_Component_Log::debug ( 'ステータスを「4：完了」（必要があれば管理者へエラーメール送信）しない' );
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

		Sgmov_Component_Log::debug ( $csvdata );

		// データ送信
		try {
			$res = Sgmov_Process_BcmSender::sendCsvToWs ( 'EVENT_' . date ( 'YmdHis' ) . '.csv', $csvdata );
		} catch ( Sgmov_Component_Exception $sce ) {
			Sgmov_Component_Log::debug ( $sce ); // throwしない
		}

		Sgmov_Component_Log::debug ( $res );

		$responce = new Sgmov_Process_BcmResponse ();
		$responce->initialize ( $res );

		Sgmov_Component_Log::debug ( $responce->sendSts );
		$comiket ["sendSts"] = $responce->sendSts;

		// レスポンス値によって処理のふりわけ
		switch ($responce->sendSts) {
			case 0 : // 成功：update バッチ処理状況「送信済」送信結果「成功」。エラー時は管理者にメールしても無駄なのでここで完了にする。
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

			case 1 : // 不正データ(データ内容に原因があるので直さない限り何度やっても無駄、一発アウト)：update バッチ処理状況「送信済」 送信結果「失敗」
				$db = Sgmov_Component_DB::getAdmin ();
				$db->begin ();
				$db->executeUpdate ( "UPDATE comiket SET batch_status = " . self::STATUS_SEND . ", send_result = " . self::RESULT_FAILURE . ", sent = current_timestamp, modified = current_timestamp WHERE id = $1;", array (
						$comiket ['id']
				) );
				$db->commit ();
				$comiket ["batch_status"] = self::STATUS_SEND;
				$comiket ["send_result"] = self::RESULT_FAILURE;
				break;

			case 2 :
			case 3 : // システム障害または送信競合（インフラ不調が原因なのでインフラが回復すれば成功する可能性がある）：update 送信リトライ数「+1」
				$db = Sgmov_Component_DB::getAdmin ();
				$db->begin ();
				$db->executeUpdate ( "UPDATE comiket SET retry_count = retry_count + 1, sent = current_timestamp, modified = current_timestamp WHERE id = $1;", array (
						$comiket ['id']
				) );
				$db->commit ();
				++ $comiket ["retry_count"];
				break;

			case 4 : // 登録済み：update バッチ処理状況「送信済」 送信結果「成功」
				$db = Sgmov_Component_DB::getAdmin ();
				$db->begin ();
				$db->executeUpdate ( "UPDATE comiket SET batch_status = " . self::STATUS_SEND . ", send_result=" . self::RESULT_SUCCESS . ", sent = current_timestamp, modified = current_timestamp WHERE id = $1;", array (
						$comiket ['id']
				) );
				$db->commit ();
				$comiket ["batch_status"] = self::STATUS_SEND;
				$comiket ["send_result"] = self::RESULT_SUCCESS;
				break;

			default : // それ以外 送信リトライ数「+1」 送信リトライ階数が21以上の場合バッチ処理状況「送信済」 送信結果「リトライオーバー」（タイムアウト）
				$db = Sgmov_Component_DB::getAdmin ();
				$db->begin ();
				$db->executeUpdate ( "UPDATE comiket SET retry_count = retry_count + 1, sent = current_timestamp, modified = current_timestamp WHERE id = $1;", array (
						$comiket ['id']
				) );
				$db->commit ();
				++ $comiket ["retry_count"];
				break;
		}

		return $comiket;
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
}
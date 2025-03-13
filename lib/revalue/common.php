<?php
	require_once($_SERVER["DOCUMENT_ROOT"]."/../lib/revalue/config.inc");

	/**
	 * 汎用共通関数
	 */
	class Common {

		/**
		 * 対象文字列の空チェック
		 *
		 * @param object $value 対象文字列
		 *
		 * @return 空文字・NULL・変数セットされていない状態であればTrue、それ以外であればFalse
		 */
		public function isEmpty($value) {
			$result = false;

			// 空文字・NULL・変数セットされていない場合,Trueを設定
			if($value === "" || is_null($value) || !isset($value)) {
				$result = true;
			}

			return $result;
		}

		/**
		 * 対象文字列の半角数値チェック
		 *
		 * @param object $value 対象文字列
		 *
		 * @return 半角数値のみであればTrue、それ以外であればFalse
		 */
		public function isNumeric($value) {
			$result = false;

			// 空文字・NULL・変数セットされていない場合,Trueを設定
			if(preg_match("/^[0-9]+$/", $value)) {
				$result = true;
			}

			return $result;
		}

		/**
		 * 対象文字列の半角英字チェック
		 *
		 * @param object $value 対象文字列
		 *
		 * @return 半角英字のみであればTrue、それ以外であればFalse
		 */
		public function isAlphabet($value) {
			$result = false;

			// 空文字・NULL・変数セットされていない場合,Trueを設定
			if(preg_match("/^[a-zA-Z]+$/", $value)) {
				$result = true;
			}

			return $result;
		}

		/**
		 * 対象文字列のメールアドレス形式チェック
		 *
		 * @param object $value 対象文字列
		 *
		 * @return メールアドレス形式であればTrue、それ以外であればFalse
		 */
		public function isMailFormat($value) {
			$result = false;

			// メールアドレス形式の場合,Trueを設定
			if(preg_match("/^[a-zA-Z0-9_\.\-]+?@[A-Za-z0-9_\.\-]+\.[A-Za-z0-9_\-]+$/", $value)) {
				$result = true;
			}

			return $result;
		}

		/**
		 * 対象文字列の電話番号形式チェック
		 *
		 * @param object $value 対象文字列
		 *
		 * @return 電話番号形式であればTrue、それ以外であればFalse
		 */
		public function isTelFormat($value) {
			$hyphenCount = 0;			// '-' のカウント
			$result = false;
			$valueArray = array();
			$setValue = "";
			$errorFlag = false;

			// - で文字列を分割し、配列で取得
			for($i=0;$i < $this->getStringCount($value);$i++) {
				if($value[$i] === "-") {
					$valueArray[count($valueArray)] = $setValue;
					$setValue = "";

				} else if($this->isNumeric($value[$i]) == true) {
					$setValue .= $value[$i];

				} else {
					// '-' と 数値以外がある場合、エラーフラグを立てる
					$errorFlag = true;
				}
			}
			$valueArray[count($valueArray)] = $setValue;

			if($errorFlag == false && count($valueArray) == 3) {
				// すべて数値であり、且つ分割数が3の場合

				// 合計桁数を取得
				$valueSize = $this->getStringCount($valueArray[0])
							 + $this->getStringCount($valueArray[1])
							 + $this->getStringCount($valueArray[2]);

				// 桁数が10または11の場合、Trueを返す
				// ※固定電話が10桁、携帯電話が11桁であるため
				if($valueSize == 10 || $valueSize == 11) {
					$result = true;
				}
			}

			return $result;
		}

		/**
		 * 対象文字列の郵便番号形式チェック
		 *
		 * @param object $value 対象文字列
		 *
		 * @return 郵便番号形式であればTrue、それ以外であればFalse
		 */
		public function isPostalFormat($value) {
			$result = false;
			$valueArray = array();
			$setValue = "";
			$errorFlag = false;

			// '-' で文字列を分割し、配列で取得
			for($i=0;$i < $this->getStringCount($value);$i++) {
				if($value[$i] === "-") {
					$valueArray[count($valueArray)] = $setValue;
					$setValue = "";

				} else if($this->isNumeric($value[$i]) == true) {
					$setValue .= $value[$i];

				} else {
					// '-' と 数値以外がある場合、エラーフラグを立てる
					$errorFlag = true;
				}
			}
			$valueArray[count($valueArray)] = $setValue;

			if($errorFlag == false && count($valueArray) == 2) {
				// すべて数値であり、且つ分割数が2の場合

				if($this->getStringCount($valueArray[0]) == 3
					&& $this->getStringCount($valueArray[1]) == 4) {
					// 3桁と4桁の場合
					$result = true;
				}
			}

			return $result;

		}

		/**
		 * 対象文字列の日付形式チェック(YYYY/MM/DD)
		 *
		 * @param object $value 対象文字列
		 *
		 * @return 日付形式であればTrue、それ以外であればFalse
		 */
		public function isDateSlashFormat($value) {
			$result = false;
			$valueArray = array();
			$setValue = "";
			$errorFlag = false;

			// '/' で文字列を分割し、配列で取得
			for($i=0;$i < $this->getStringCount($value);$i++) {
				if($value[$i] === "/") {
					$valueArray[count($valueArray)] = $setValue;
					$setValue = "";

				} else if($this->isNumeric($value[$i]) == true) {
					$setValue .= $value[$i];

				} else {
					// '/' と 数値以外がある場合、エラーフラグを立てる
					$errorFlag = true;
				}
			}
			$valueArray[count($valueArray)] = $setValue;

			if($errorFlag == false && count($valueArray) == 3) {
				// すべて数値であり、且つ分割数が3の場合

				// 月・日の上限チェック
				if($valueArray[1] >= 1 && $valueArray[1] <= 12) {
					// 1月～12月
					if($valueArray[2] >= 1 && $valueArray[2] <= 31) {
						// 1日～31日
						$result = true;
					}
				}
			}

			return $result;
		}

		/**
		 * 対象の文字列の文字数取得
		 *
		 * @param object $value 対象文字列
		 *
		 * @return  文字数
		 */
		public function getStringCount($value, $encoding = "UTF-8") {
			$count = 0;

			if($this->isEmpty($value) == true) {
				// 文字列が空の場合は0を取得
				$count = 0;

			} else {
				// 文字数を取得
				$count = mb_strlen($value, $encoding);
			}

			return $count;
		}

		/**
		 * メール送信
		 *
		 * @param object $subject メールの件名
		 * @param object $body    メールの本文
		 * @param array  $addressArray メールのアドレス配列
		 *
		 * @return メール送信正常終了ならTrue、失敗ならFalseを返す
		 */
		public function sendMail($subject, $body, $addressArray) {
			$outputBody = "";		// 出力する本文

			// メールヘッダに送信元アドレスを設定
			$header = "From: <".$addressArray['From'].">\r\n";

			// 返送先アドレスの指定があればメールヘッダに設定
			if(isset($addressArray['ReplyTo'])) {
				$header .= "Reply-To: <".$addressArray['ReplyTo'].">\r\n";
			}

			// エラー時の返送先アドレスの指定があればメールヘッダに設定
			if(isset($addressArray['ErrorTo'])) {
				$header .= "Return-Path: <".$addressArray['ErrorTo'].">\r\n";
			}

			// メールヘッダにその他情報を設定
			$header .= "Date: ".date("r")."\r\n" .
						"Content-Type: text/plain; charset=ISO-2022-JP \r\n".
						"X-Mailer: PHP/" . phpversion() . "\r\n";

			// メールの宛先を取得
			$to = $addressArray['To'];

			// 50文字で自動改行
			$outputBody = $this->getLimitLengthAddNewLineValue($body, 50);

			// sendMailコマンド実行時の引数を設定
			// -f ： 送信元サーバのメールアドレスとして認識させるメールアドレスを設定する
			//      この設定がないとスパムメールとして判断される場合がある
			$parameter = "-f ".$addressArray['From'];

			// 現在の言語設定と内部文字エンコーディングを取得
			$languare = mb_language();
			$internalEncoding = mb_internal_encoding();

			// 言語設定と内部文字エンコーディングをメールに合わせて設定
			mb_language("Japanese");
			mb_internal_encoding("UTF-8");

			// メール送信を実行
			$result = @mb_send_mail($to, $subject, $outputBody, $header, $parameter);

			// 言語設定と内部文字エンコーディングを初期値に戻す
			mb_language($languare);
			mb_internal_encoding($internalEncoding);

			return $result;
		}

		/**
		 * 改行コードまでに上限文字数を超えた場合、改行コードを埋め込む
		 *
		 * @param object $value  対象文字列
		 * @param object $length  改行コードまでの上限文字数
		 * @param object $encoding [optional] 文字コード（デフォルト：UTF-8）
		 * @return 改行コードを埋め込んだ文字列
		 */
		public function getLimitLengthAddNewLineValue($value, $length, $encoding = "UTF-8") {
			$valueArray = array();
			$result = "";
			$line = "";

			// 改行コードで分割
			$valueArray = preg_split("/\r\n|\r|\n/", $value);

			for($i = 0;$i < count($valueArray);$i++) {
				$line = $valueArray[$i];

				// 上限文字数を超えた場合は改行コードを埋め込む
				while($this->getStringCount($line, $encoding) > $length) {
					$result .= mb_substr($line, 0, $length, $encoding)."\n";
					$line = mb_substr($line, $length, $this->getStringCount($line) - $length, $encoding);
				}
				$result .= $line."\n";
			}

			return $result;
		}

		/**
		 * テキストファイルの内容を文字列で取得
		 * 引数に連想配列を設定した場合、キー文字列にデータを埋め込む
		 *
		 * @param object $path ファイルパス
		 * @param object $data 連想配列データ
		 *
		 * @return ファイルデータに連想配列データを埋め込んだ文字列
		 */
		public function getFileData($path, $data) {
			$result = "";
			$dataKeyList = array();

			// ファイルを読み込む
			$result = file_get_contents($path);

			// 連想配列データのキーリストを取得
			$dataKeyList = array_keys($data);

			// ファイルデータに連想配列データを埋め込む
			for($i = 0;$i < count($dataKeyList);$i++) {
				// キーを取得
				$key = $dataKeyList[$i];

				// ファイルデータ上のキー文字列にデータを埋め込む
				$result = str_replace("[\$".$key."]", $data[$key], $result);
			}

			return $result;
		}

		/**
		 * HTMLの特殊文字列をエスケープした文字列を取得（XSS対策）
		 *
		 * @param object $value 対象文字列
		 *
		 * @return エスケープ後文字列
		 */
		public function getXSSEscapeValue($value) {
			$result = "";

			// 特殊文字列をエスケープする
			// & (アンパサンド)		→ &amp;
			// " (ダブルクォート)	→ &quot;
			// < (小なり)			→ &lt;
			// > (大なり)			→ &gt;
			$result = htmlspecialchars($value);

			return $result;
		}

		/**
		 * HTMLの特殊文字列をエスケープした文字列をデコードして取得
		 *
		 * @param object $value HTML特殊文字列エスケープ済みの文字列
		 *
		 * @return デコード後文字列
		 */
		public function getXSSEscapeDecodeValue($value) {
			$result = "";

			// 特殊文字列のエスケープをデコードする
			// &amp;	→ & (アンパサンド)
			// &quot;	→ " (ダブルクォート)
			// &lt;		→ < (小なり)
			// &gt;		→ > (大なり)
			$result = htmlspecialchars_decode($value);

			return $result;
		}

		/**
		 * SQLの特殊文字列をエスケープした文字列を取得（SQLインジェクション対策）
		 *
		 * @param object $value 対象文字列
		 *
		 * @return エスケープ後文字列
		 */
		public function getSQLEscapeValue($value) {
			$result = "";

			// 特殊文字列をエスケープする
			// ' (シングルクオート)	→ ''
			// \ (バックスラッシュ)	→ \\
			$result = str_replace("'", "''", $value);
			$result = str_replace("\\", "\\\\", $result);

			return $result;
		}

		/**
		 * 配列全てのHTML特殊文字列をエスケープして取得
		 *
		 * @param object $array 対象配列
		 *
		 * @return エスケープ後配列
		 */
		public function getXSSEscapeArray($array) {
			$result = array();
			$keyArray = array();

			if(isset($array) && is_array($array)) {
				$keyArray = array_keys($array);
			}

			// 対象の配列全てに対してXSS対策
			for($i=0;$i < count($keyArray);$i++) {
				$key = $keyArray[$i];

				if(is_array($array[$key]) == false) {
					$result[$key] = $this->getXSSEscapeValue($array[$key]);
				} else {
					$result[$key] = $this->getXSSEscapeArray($array[$key]);
				}
			}

			return $result;
		}

		/**
		 * 配列全てのHTMLの特殊文字列をエスケープした文字列をデコードして取得
		 *
		 * @param object $array HTML特殊文字列エスケープ済みの文字列配列
		 *
		 * @return デコード後配列
		 */
		public function getXSSEscapeDecodeArray($array) {
			$result = array();
			$keyArray = array();

			if(isset($array) && is_array($array)) {
				$keyArray = array_keys($array);
			}

			// 対象の配列全てに対してXSS対策のデコード
			for($i=0;$i < count($keyArray);$i++) {
				$key = $keyArray[$i];

				if(is_array($array[$key]) == false) {
					$result[$key] = $this->getXSSEscapeDecodeValue($array[$key]);
				} else {
					$result[$key] = $this->getXSSEscapeDecodeArray($array[$key]);
				}
			}

			return $result;
		}

		/**
		 * 配列全てのSQL特殊文字列をエスケープして取得
		 *
		 * @param object $array 対象配列
		 *
		 * @return エスケープ後配列
		 */
		public function getSQLEscapeArray($array) {
			$result = array();
			$keyArray = array();

			if(isset($array) && is_array($array)) {
				$keyArray = array_keys($array);
			}

			// 対象の配列全てに対してSQLインジェクション対策
			for($i=0;$i < count($keyArray);$i++) {
				$key = $keyArray[$i];

				if(is_array($array[$key]) == false) {
					$result[$key] = $this->getSQLEscapeValue($array[$key]);
				} else {
					$result[$key] = $this->getSQLEscapeArray($array[$key]);
				}
			}

			return $result;
		}

		/**
		 * 改行コードの前に brタグ を追加して取得
		 *
		 * @param object $value 対象文字列
		 *
		 * @return brタグ追加後の文字列
		 */
		public function getBrTagAddValue($value) {
			$result = "";

			// すべての改行文字 (\r\n、 \n\r、\n および \r) の前に '<br />' を挿入して返します。
			$result = nl2br($value);

			return $result;
		}

		/**
		 * EUC_JPからUTF8へ文字列をエンコードして取得
		 *
		 * @param object $value 対象の文字列
		 *
		 * @return UTF8にエンコードした文字列
		 */
		public function getEucToUtf8($value) {
			$result = "";

			// エンコードを実行
			$result = mb_convert_encoding($value, "UTF-8", "EUC-JP");

			return $result;
		}

		/**
		 * UTF8からEUC_JPへ文字列をエンコードして取得
		 *
		 * @param object $value 対象の文字列
		 *
		 * @return EUC_JPにエンコードした文字列
		 */
		public function getUtf8ToEuc($value) {
			$result = "";

			// エンコードを実行
			$result = mb_convert_encoding($value, "EUC-JP", "UTF-8");

			return $result;
		}

		/**
		 * 配列全てをEUC_JPからUTF8へ文字列をエンコードして取得
		 *
		 * @param object $array 対象配列
		 *
		 * @return UTF8にエンコードした配列
		 */
		public function getEucToUtf8Array($array) {
			$result = array();
			$keyArray = array();

			if(isset($array) && is_array($array)) {
				$keyArray = array_keys($array);
			}

			// 対象の配列全てに対してEUC_JPからUTF8へエンコード
			for($i=0;$i < count($keyArray);$i++) {
				$key = $keyArray[$i];
				if(is_array($array[$key]) == false) {
					$result[$key] = $this->getEucToUtf8($array[$key]);
				} else {
					$result[$key] = $this->getEucToUtf8Array($array[$key]);
				}
			}

			return $result;
		}

		/**
		 * 配列全てをUTF8からEUC_JPへ文字列をエンコードして取得
		 *
		 * @param object $array 対象配列
		 *
		 * @return EUC_JPにエンコードした配列
		 */
		public function getUtf8ToEucArray($array) {
			$result = array();
			$keyArray = array();

			if(isset($array) && is_array($array)) {
				$keyArray = array_keys($array);
			}

			// 対象の配列全てに対してUTF8からEUC_JPへエンコード
			for($i=0;$i < count($keyArray);$i++) {
				$key = $keyArray[$i];
				if(is_array($array[$key]) == false) {
					$result[$key] = $this->getUtf8ToEuc($array[$key]);
				} else {
					$result[$key] = $this->getUtf8ToEucArray($array[$key]);
				}
			}

			return $result;
		}

		/**
		 * ファイルが存在する場合は追記、存在しない場合は新規作成
		 *
		 * @param object $path ファイルパス
		 * @param object $value 出力内容
		 */
		public function outputFile($path, $value) {
			$fileObject;			// ファイルオブジェクト

			try {
				// ファイルを追記モードで開く、ファイルが存在しない場合は新規作成
				$fileObject = @fopen($path, "a");
				if(!$fileObject) {
					throw new Exception("ファイルオープンに失敗しました。\n"."パス：".$path);
				}

				// ファイルへの書き込み
				if(!fwrite($fileObject, $value)) {
					throw new Exception("ファイルの書き込みに失敗しました。\n"."パス：".$path);
				}

				// ファイルを閉じる
				fclose($fileObject);

			} catch(Exception $e) {
				if($fileObject) {
					// ファイルを閉じる
					fclose($fileObject);
				}

				throw $e;
			}
		}

		/**
		 * ポストデータを連想配列で取得
		 *
		 * @param object $post ポストデータ（$_POST）
		 * @return
		 */
		public function getPostData($post) {
			$result = array();		// 戻り値
			$keys = array();		// キーリスト

			// ポストデータのキーリストを取得
			$keys = array_keys($post);

			// ポストデータをすべて取得
			for($i=0;$i < count($keys);$i++) {
				$result[$keys[$i]] = $post[$keys[$i]];
			}

			return $result;
		}

		/**
		 * 許容する拡張子のリストを取得
		 *
		 * @return 拡張子文字列の配列
		 */
		public function getFileTypeArray() {
			$common = new Common();	// 共通関数
			$result = array();		// 戻り値
			$types = "";			// 拡張子のリスト
			$setValue = "";

			// 拡張子のタイプ文字列を取得
			$types = UPLOAD_FILE_TYPE;

			for($i=0;$i < $common->getStringCount($types);$i++) {
				if($types[$i] === ",") {
					$result[count($result)] = $setValue;
					$setValue = "";

				} else {
					$setValue .= $types[$i];
				}
			}
			$result[count($result)] = $setValue;

			return $result;
		}

		/**
		 * メッセージ配列からHTML表示するための文字列を取得
		 *
		 * @param object $messageArray メッセージ配列
		 *
		 * @return
		 */
		public function getHtmlMessage($messageArray) {
			$message = "";

			for($i = 0;$i < count($messageArray);$i++) {
				if($i > 0) {
					$message .= "<br>\n";
				}
				$message .= $messageArray[$i];
			}

			return $message;
		}
	}

	/**
	 * 入力チェック用共通関数
	 */
	class Validate {
		/**
		 * 必須チェック
		 *
		 * @param object $value チェック対象
		 *
		 * @return 入力値がなければNG
		 */
		public function checkRequisite($value) {
			$common = new Common();		// 共通関数
			$result = NG;		// チェック結果

			// 空文字以外の場合、OK
			if($common->isEmpty($value) == false) {
				$result = OK;
			}

			return $result;
		}

		/**
		 * 文字数チェック
		 *
		 * @param object $value チェック対象
		 * @param object $maxLength 最大文字数
		 * @param object $minLength [optional] 最小文字数
		 *
		 * @return 最大・最小文字数に収まらない場合NG
		 */
		public function checkLength($value, $maxLength, $minLength = 0) {
			$common = new Common();		// 共通関数
			$result = NG;		// チェック結果

			// 最大文字数いない場合、OK
			if($common->getStringCount($value) <= $maxLength) {

				// 最小文字数が設定されており、上回っている場合OK
				if($minLength > 0) {
					if($common->getStringCount($value) >= $minLength) {
						$result = OK;
					}
				} else {
					$result = OK;
				}
			}

			return $result;
		}

		/**
		 * 半角数値チェック
		 *
		 * @param object $value チェック対象
		 *
		 * @return 半角数値でない場合NG
		 */
		public function checkNumeric($value) {
			$common = new Common();		// 共通関数
			$result = NG;		// チェック結果

			// 半角数値のみの場合、OK
			if($common->isNumeric($value) == true) {
				$result = OK;
			}

			return $result;
		}

		/**
		 * 半角英字チェック
		 *
		 * @param object $value チェック対象
		 *
		 * @return 半角英字でない場合NG
		 */
		public function checkAlphabet($value) {
			$common = new Common();		// 共通関数
			$result = NG;		// チェック結果

			// 半角英字のみの場合、OK
			if($common->isAlphabet($value) == true) {
				$result = OK;
			}

			return $result;
		}

		/**
		 * 半角英数字チェック
		 *
		 * @param object $value チェック対象
		 *
		 * @return 半角英数字でない場合NG
		 */
		public function checkNumericAlphabet($value) {
			$common = new Common();		// 共通関数
			$result = NG;		// チェック結果

			// 半角英数字のどちらかの場合、OK
			if($common->isNumeric($value) == true || $common->isAlphabet($value) == true) {
				$result = OK;
			}
			return $result;
		}

		/**
		 * 郵便番号形式チェック
		 *
		 * @param object $value チェック対象
		 *
		 * @return 郵便番号形式でない場合NG
		 */
		public function checkPostalFormat($value) {
			$common = new Common();		// 共通関数
			$result = NG;		// チェック結果

			// 郵便番号形式の場合、OK
			if($common->isPostalFormat($value) == true) {
				$result = OK;
			}

			return $result;
		}

		/**
		 * 電話番号形式チェック
		 *
		 * @param object $value チェック対象
		 *
		 * @return 電話番号形式でない場合NG
		 */
		public function checkTelFormat($value) {
			$common = new Common();		// 共通関数
			$result = NG;		// チェック結果

			// 電話番号形式の場合、OK
			if($common->isTelFormat($value) == true) {
				$result = OK;
			}

			return $result;
		}

		/**
		 * メール形式チェック
		 *
		 * @param object $value チェック対象
		 *
		 * @return メール形式でない場合NG
		 */
		public function checkMailFormat($value) {
			$common = new Common();		// 共通関数
			$result = NG;		// チェック結果

			// メール形式の場合、OK
			if($common->isMailFormat($value) == true) {
				$result = OK;
			}

			return $result;
		}

		/**
		 * 日付チェック(YYYY/MM/DD)
		 *
		 * @param object $value チェック対象
		 *
		 * @return メール形式でない場合NG
		 */
		public function checkDateSlash($value) {
			$common = new Common();		// 共通関数
			$result = NG;		// チェック結果
			$valueArray = array();
			$setValue = "";
			$errorFlag = false;

			// 日付形式(YYYY/MM/DD)チェック
			if($common->isDateSlashFormat($value) == true) {

				for($i=0;$i < $common->getStringCount($value);$i++) {
					if($value[$i] === "/") {
						$valueArray[count($valueArray)] = $setValue;
						$setValue = "";

					} else if($common->isNumeric($value[$i]) == true) {
						$setValue .= $value[$i];

					}
				}
				$valueArray[count($valueArray)] = $setValue;

				// 有効な日付の場合、OK
				if(checkdate($valueArray[1],$valueArray[2],$valueArray[0]) == true) {
					$result = OK;
				}
			}

			return $result;
		}

		/**
		 * アップロードファイルのチェック
		 * （設定ファイルに定義されたファイルサイズ・拡張子に合致するかを確認）
		 *
		 * @param object $value チェック対象のファイルパス
		 *
		 * @return 設定ファイルで許容されていないファイルの場合NG、許容される場合OK
		 */
		public function checkUploadFile($value) {
			$common = new Common();		// 共通関数
			$result = NG;				// チェック結果
			$fileTypeList = array();		// 拡張子リスト
			$fileType = "";

			if(file_exists($value)) {
				// 0バイトより大きく、設定ファイルのアップロードファイル上限値以下の場合、OK
				if(filesize($value) > 0 && filesize($value) <= UPLOAD_FILE_MAX_SIZE) {
					// 許容するファイルの拡張子リストを取得
					$fileTypeList = $common->getFileTypeArray();

					// 拡張子を大文字で取得
					$fileType = pathinfo($value, PATHINFO_EXTENSION);
					$fileType = strtoupper($fileType);

					for($i=0;$i < count($fileTypeList);$i++) {
						if($fileType === $fileTypeList[$i]) {
							$result = OK;
						}
					}
				}
			}

			return $result;
		}
	}

	/**
	 * DB用共通関数
	 */
	class DBCommon {

		/**
		 * DBコネクションを取得
		 *
		 * @param object $connectionString 接続文字列
		 *
		 * @return DBコネクション
		 */
		public function getConnection($connectionString) {
			$connection;				// DBコネクション
			$common = new Common();		// 共通関数

			try {
				// DB接続
				$connection = @pg_connect(CONNECTION_STRING);
				if(!$connection) {
					throw new Exception("DB接続に失敗しました。");
				}

			} catch(Exception $e) {
				throw $e;
			}

			return $connection;
		}

		/**
		 * SQLの実行結果を配列で取得
		 *
		 * @param object $connection DBコネクション
		 * @param object $sql SQL
		 *
		 * @return 取得データの連想配列
		 */
		public function getSelectList($connection, $sql) {
			$result = array();		// 戻り値
			$list = array();		// クエリ結果
			$common = new Common();	// 共通関数

			try {
				// SQL実行
				$list = @pg_query($connection, $sql);
				if(!$list) {
					throw new Exception("SQL実行に失敗しました。\n".
										$common->getEucToUtf8(pg_last_error())."\n".
										$sql);
				}

				// 実行結果を配列に格納
				while ($row = pg_fetch_assoc($list)) {
					$result[] = $common->getEucToUtf8Array($row);
				}

			} catch(Exception $e) {
				// 例外処理
				throw $e;
			}
			return $result;
		}

		/**
		 * SQLの実行結果を件数で取得（select count 専用）
		 *
		 * @param object $connection DBコネクション
		 * @param object $sql SQL
		 *
		 * @return 取得データの連想配列
		 */
		public function getSelectCount($connection, $sql) {
			$result = array();		// 戻り値
			$list = array();		// クエリ結果
			$common = new Common();	// 共通関数
			$row = array();
			$count = 0;

			try {
				// SQL実行
				$list = @pg_query($connection, $sql);
				if(!$list) {
					throw new Exception("SQL実行に失敗しました。\n".
										$common->getEucToUtf8(pg_last_error())."\n".
										$sql);
				}

				// 実行結果を配列に格納
				$row = pg_fetch_assoc($list);
				$keys = array_keys($row);
				$count = $row[$keys[0]];

			} catch(Exception $e) {
				// 例外処理
				throw $e;
			}
			return $count;
		}

		/**
		 * SQLのパラメータにセットする文字列の値を取得
		 *
		 * @param object $value 対象の値
		 *
		 * @return 値が空の場合は NULL 、それ以外の場合は '値'
		 */
		public function getStringEmptyToNull($value) {
			$result = "";				// 戻り値
			$common = new Common();		// 共通関数

			// 値に応じて戻り値を設定
			if($common->isEmpty($value)) {
				$result = "NULL";
			} else {
				$result = "'".$value."'";
			}

			return $result;
		}

		/**
		 * SQLのパラメータにセットする数値の値を取得
		 *
		 * @param object $value 対象の値
		 *
		 * @return 値が空の場合は NULL 、それ以外の場合は 値
		 */
		public function getNumberEmptyToNull($value) {
			$result = "";				// 戻り値
			$common = new Common();		// 共通関数

			// 値に応じて戻り値を設定
			if($common->isEmpty($value)) {
				$result = "NULL";
			} else {
				$result = $value;
			}

			return $result;
		}
	}

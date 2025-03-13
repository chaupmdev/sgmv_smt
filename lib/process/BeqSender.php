<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
/**#@-*/

/**
 * BCR_旅客手荷物受付サービスのお申し込み送信バッチの、送信機能です。
 *
 * [注意事項(共通)]
 *
 * エラーハンドリングでエラーが例外に変換されることを
 * 前提として設計されています。
 *
 * テストのため全て public で宣言します。
 * 名前がアンダーバーで始まるものは使用しないでください。
 *
 * テストでモックを使用するものや、実装を含めると複雑になるものは
 * 実装が分離されています。
 *
 * @package Process
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Process_BeqSender {

    /**
     * リクエストのユーザーIDのキー
     */
    const REQUEST_USER_ID_KEY = 'userId';

    /**
     * リクエストのパスワードのキー
     */
    const REQUEST_PASSWORD_KEY = 'passWord';

    /**
     * リクエストのファイルのキー
     */
    const REQUEST_FILE_KEY = 'filename';

    /**
     * リクエストのデータのキー
     */
    const REQUEST_DATA_KEY = 'data';

    /**
     * 送信プロトコル。ssl の場合は 'ssl://' を指定します。
     * @var string
     */
    public $_eqProtocol;

    /**
     * 送信先ホスト名
     * @var string
     */
    public $_eqHost;

    /**
     * 送信先パス
     * @var string
     */
    public $_eqPath;

    /**
     * 送信先ポート
     * @var integer
     */
    public $_eqPort;

    /**
     * ユーザーID
     * @var string
     */
    public $_eqUserId;

    /**
     * パスワード
     * @var string
     */
    public $_eqPassWord;

    /**
     * 各パラメーターを初期化します。
     */
    public function __construct() {
        $this->_eqProtocol = Sgmov_Component_Config::getEqProtocol();
        $this->_eqHost     = Sgmov_Component_Config::getEqHost();
        $this->_eqPath     = Sgmov_Component_Config::getEqPath();
        $this->_eqPort     = Sgmov_Component_Config::getEqPort();
        $this->_eqUserId   = Sgmov_Component_Config::getEqUserId();
        $this->_eqPassWord = Sgmov_Component_Config::getEqPassword();
    }

    /**
     * CSV データを Web システムへ送信し、結果の生データを返します。
     * @param string $sendFileName 送信するCSVデータのファイル名です。
     * @param string $strCsvData 送信するCSVデータ。改行を含む文字列です。
     * @return string 受信したデータを返します。
     * @throws Sgmov_Component_Exception 処理に失敗した場合例外を投げます。
     */
    public function sendCsvToEq($sendFileName, $strCsvData) {
        // リクエストの生成
        $request = $this->_createRequest($sendFileName, $strCsvData);

        // デバッグログを出力
        Sgmov_Component_Log::info ("リクエストデータ\n" . mb_convert_encoding($request, 'UTF-8', 'SJIS-win'));

        // 接続
        $fp = $this->_connectToEq();

        // デバッグログを出力
        if (Sgmov_Component_Log::isDebug()) {
            Sgmov_Component_Log::debug("接続確認\n" . $fp);
        }

        try {

            // デバッグログを出力
            if (Sgmov_Component_Log::isDebug()) {
                Sgmov_Component_Log::debug("IF処理開始\n" . $fp);
            }

            // データ送信
            $this->_send($fp, $request);

            // デバッグログを出力
            if (Sgmov_Component_Log::isDebug()) {
                Sgmov_Component_Log::debug("データ送信完了\n" . $fp);
            }

            // ステータスラインの受信
            $status = $this->_recvStatusLine($fp);

            // 受信ステータスをログ出力
            if (Sgmov_Component_Log::isDebug()) {
                Sgmov_Component_Log::debug("recvStatus\n" . $status);
            }

            // ステータスコードの確認
            $this->_checkStatusCode($status);

            // データの受信
            $response = $this->_recv($fp);

            // レスポンス値を強制出力（強制のためwarningレベル）
            Sgmov_Component_Log::warning("レスポンス\n" . $response);

            //接続終了
            @fclose($fp);
        } catch (Exception $e) {
            //接続終了
            @fclose($fp);

            // デバッグログを出力
            if (Sgmov_Component_Log::isDebug()) {
                Sgmov_Component_Log::debug("エラー内容ログ出力\n" . $e);
            }

            throw $e;
        }

        return $response;
    }

    /**
     * リクエストを生成します。
     * @param string $sendFileName 送信するCSVデータのファイル名です。
     * @param string $strCsvData 送信するCSVデータ。改行を含む文字列です。
     * @return string 生成されたリクエストボディ
     */
    public function _createRequest($sendFileName, $strCsvData) {
        $boundary      = "-----" . md5(uniqid());
        $requestBody   = $this->_createRequestBody($sendFileName, $strCsvData, $boundary);
        $requestHeader = $this->_createRequestHeader($boundary, strlen($requestBody));
        return $requestHeader . $requestBody;
    }

    /**
     * リクエストボディを生成します。
     * @param string $sendFileName 送信するCSVデータのファイル名です。
     * @param string $strCsvData 送信するCSVデータ。改行を含む文字列です。
     * @param string $boundary 使用するバウンダリ。
     * @return string 生成されたリクエストボディ
     */
    public function _createRequestBody($sendFileName, $strCsvData, $boundary) {
        $body = "";

        // ユーザーID
        $body .= "--{$boundary}\r\n";
        $body .= "Content-Disposition: form-data; name=\"" . self::REQUEST_USER_ID_KEY . "\"\r\n";
        $body .= "\r\n";
        $body .= $this->_eqUserId . "\r\n";

        // パスワード
        $body .= "--{$boundary}\r\n";
        $body .= "Content-Disposition: form-data; name=\"" . self::REQUEST_PASSWORD_KEY . "\"\r\n";
        $body .= "\r\n";
        $body .= $this->_eqPassWord . "\r\n";

        // ファイル名
        $body .= "--{$boundary}\r\n";
        $body .= "Content-Disposition: form-data; name=\"" . self::REQUEST_FILE_KEY . "\"\r\n";
        $body .= "\r\n";
        $body .= $sendFileName . "\r\n";

        // ファイルデータを設定
        $body .= "--{$boundary}\r\n";
        $body .= "Content-Disposition: form-data; name=\"" . self::REQUEST_DATA_KEY . "\"; filename=\"{$sendFileName}\"\r\n";
        $body .= "Content-Type: text/plain\r\n";
        $body .= "\r\n";
        $body .= "{$strCsvData}\r\n";

        // 送信データ末尾の区切り文字を追加
        $body .= "--{$boundary}--\r\n";
        $body .= "\r\n\r\n";
        return $body;
    }

    /**
     * リクエストヘッダーを生成します。ヘッダー後の空行を含みます。
     * @param string $boundary 使用するバウンダリ。
     * @param integer $contentLength リクエストボディのバイト数
     * @return string 生成されたリクエストヘッダー
     */
    public function _createRequestHeader($boundary, $contentLength) {
        $header = "";
        $header .= "POST " . $this->_eqPath . " HTTP/1.1\r\n";
        $header .= "Host: " . $this->_eqHost . "\r\n";
        $header .= "Content-type: multipart/form-data, boundary={$boundary}\r\n";
        $header .= "Connection: close\r\n";
        $header .= "Content-length: {$contentLength}\r\n";
        $header .= "\r\n";
        return $header;
    }

    /**
     * ウェブシステムへ接続します。
     * @return resource ソケットハンドル
     * @throws Sgmov_Component_Exception 処理に失敗した場合例外を投げます。
     */
    public function _connectToEq() {
        $errno  = null;
        $errstr = null;
        $fp = fsockopen($this->_eqProtocol . $this->_eqHost, $this->_eqPort, $errno, $errstr, 30);
        if (!$fp) {Sgmov_Component_Log::debug($this->_eqProtocol);Sgmov_Component_Log::debug($this->_eqHost);Sgmov_Component_Log::debug($this->_eqPort);Sgmov_Component_Log::debug($errno);Sgmov_Component_Log::debug($errstr);
            throw new Sgmov_Component_Exception('接続に失敗しました。', Sgmov_Component_ErrorCode::ERROR_BCR_WS_CONNECT);
        }
        return $fp;
    }

    /**
     * ウェブシステムへデータを送信します。
     * @param resource $fp ソケットハンドル
     * @param string $request リクエスト文字列
     * @throws Sgmov_Component_Exception 処理に失敗した場合例外を投げます。
     */
    public function _send($fp, $request) {
        if (!fwrite($fp, $request)) {

            // デバッグログを出力
            if (Sgmov_Component_Log::isDebug()) {
                Sgmov_Component_Log::debug("リクエストの送信に失敗しました。\n" . $fp);
            }

            throw new Sgmov_Component_Exception('リクエストの送信に失敗しました。', Sgmov_Component_ErrorCode::ERROR_BCR_WS_SEND);
        }
    }

    /**
     * ウェブシステムからステータスラインを受信します。
     * @param resource $fp ソケットハンドル
     * @return string ステータスライン
     * @throws Sgmov_Component_Exception 処理に失敗した場合例外を投げます。
     */
    public function _recvStatusLine($fp) {
        // デバッグログを出力
        if (Sgmov_Component_Log::isDebug()) {
            Sgmov_Component_Log::debug("ステータスラインの受信開始。\n" . $fp);
        }

        if (!($data = fgets($fp))) {

            // デバッグログを出力
            if (Sgmov_Component_Log::isDebug()) {
                Sgmov_Component_Log::debug("ステータスラインの受信に失敗しました。\n" . $fp);
            }

            throw new Sgmov_Component_Exception('ステータスラインの受信に失敗しました。', Sgmov_Component_ErrorCode::ERROR_BCR_WS_RECV_STATUS);
        }
        return $data;
    }

    /**
     * ステータスコードが 200 OK であることを確認します。
     * @param string $status ステータスライン
     * @throws Sgmov_Component_Exception 200 OK 以外の場合例外を投げます。
     */
    public function _checkStatusCode($status) {
        if (substr_count($status, "200 OK") == 0) {
            throw new Sgmov_Component_Exception('ステータスが200ではありません。', Sgmov_Component_ErrorCode::ERROR_BCR_WS_BAD_STATUS);
        }
    }

    /**
     * ウェブシステムからデータを受信します。
     * @param resource $fp ソケットハンドル
     * @return string 受信データ
     * @throws Sgmov_Component_Exception 処理に失敗した場合例外を投げます。
     */
    public function _recv($fp) {
        $response = '';

        while (!feof($fp)) {
            if (($buf = fread($fp, 4096)) == FALSE) {
                throw new Sgmov_Component_Exception('データの受信に失敗しました。', Sgmov_Component_ErrorCode::ERROR_BCR_WS_RECV_DATA);
            }
            $response .= $buf;
        }
        return $response;
    }
}
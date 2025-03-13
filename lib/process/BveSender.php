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
 * BVE_訪問見積もり申し込み送信バッチの、送信機能です。
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
class Sgmov_Process_BveSender
{
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
    const REQUEST_FILE_KEY = 'fileName';

    /**
     * リクエストのデータのキー
     */
    const REQUEST_DATA_KEY = 'fileData';

    /**
     * 送信プロトコル。ssl の場合は 'ssl://' を指定します。
     * @var string
     */
    public $_wsProtocol;

    /**
     * 送信先ホスト名
     * @var string
     */
    public $_wsHost;

    /**
     * 送信先パス
     * @var string
     */
    public $_wsPath;

    /**
     * 送信先ポート
     * @var integer
     */
    public $_wsPort;

    /**
     * ユーザーID
     * @var string
     */
    public $_wsUserId;

    /**
     * パスワード
     * @var string
     */
    public $_wsPassWord;

    /**
     * 各パラメーターを初期化します。
     */
    public function __construct()
    {
        $this->_wsProtocol = Sgmov_Component_Config::getWsProtocol();
        $this->_wsHost = Sgmov_Component_Config::getWsHost();
        $this->_wsPath = Sgmov_Component_Config::getWsPath();
        $this->_wsPort = Sgmov_Component_Config::getWsPort();
        $this->_wsUserId = Sgmov_Component_Config::getWsUserId();
        $this->_wsPassWord = Sgmov_Component_Config::getWsPassword();
    }

    /**
     * CSV データを Web システムへ送信し、結果の生データを返します。
     * @param string $sendFileName 送信するCSVデータのファイル名です。
     * @param string $strCsvData 送信するCSVデータ。改行を含む文字列です。
     * @return string 受信したデータを返します。
     * @throws Sgmov_Component_Exception 処理に失敗した場合例外を投げます。
     */
    public function sendCsvToWs($sendFileName, $strCsvData)
    {
        // リクエストの生成
        // $request = $this->_createRequest($sendFileName, $strCsvData);

        // // デバッグログを出力
        // if (Sgmov_Component_Log::isDebug()) {
        //     Sgmov_Component_Log::debug("リクエストデータ\n" . $request);
        // }

        // // 接続
        // $fp = $this->_connectToWs();
        
        // // デバッグログを出力
        // if (Sgmov_Component_Log::isDebug()) {
        //     Sgmov_Component_Log::debug("接続確認\n" . $fp);
        // }
        //CSVファイルを保存するパス
        //$filePath = "";
        $body = "";
        try {
            
            // // デバッグログを出力
            // if (Sgmov_Component_Log::isDebug()) {
            //     Sgmov_Component_Log::debug("IF処理開始\n" . $fp);
            // }
            
            // // データ送信
            // $this->_send($fp, $request);
            
            // // デバッグログを出力
            // if (Sgmov_Component_Log::isDebug()) {
            //     Sgmov_Component_Log::debug("データ送信完了\n" . $fp);
            // }
            
            // // ステータスラインの受信
            // $status = $this->_recvStatusLine($fp);

            // // 受信ステータスをログ出力
            // if (Sgmov_Component_Log::isDebug()) {
            //     Sgmov_Component_Log::debug("recvStatus\n" . $status);
            // }

            // // ステータスコードの確認
            // $this->_checkStatusCode($status);

            // // データの受信
            // $response = $this->_recv($fp);

            // // レスポンス値を強制出力（強制のためwarningレベル）
            // Sgmov_Component_Log::warning("レスポンス\n" . $response);

            // //接続終了
            // @fclose($fp);

            Sgmov_Component_Log::warning("============ Start sendCsvToWs ============\n");
//            $filePath = sys_get_temp_dir() . "\\" . $sendFileName;
//            $file = fopen($filePath, "w");
//            fwrite($file, $strCsvData);
//            fclose($file);
            Sgmov_Component_Log::warning("csv data:");
            Sgmov_Component_Log::warning($strCsvData);
//            Sgmov_Component_Log::warning("filePath\n" . $filePath);
            if ($this->_wsPort != '80') {
                $url = $this->_wsProtocol . $this->_wsHost . ':' . $this->_wsPort . $this->_wsPath;
            } else {
                $url = $this->_wsProtocol . $this->_wsHost . $this->_wsPath;
            }
            Sgmov_Component_Log::warning("url:" . $url);
            $boundary = "-----" . md5(uniqid());
            $requestBody = $this->_createRequestBody($sendFileName, $strCsvData, $boundary);
            
            Sgmov_Component_Log::info ("リクエストデータ\n" . mb_convert_encoding($requestBody, 'UTF-8', 'SJIS-win'));
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HEADER => 1,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $requestBody,
                CURLOPT_HTTPHEADER => array(
                    "Expect: 100-continue",
                    "Content-Type: multipart/form-data; boundary={$boundary}"
                ),
            ));

            $response = curl_exec($curl);
            if (Sgmov_Component_Log::isDebug()) {
                Sgmov_Component_Log::debug("response\n" . $response);
            }

            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            //unlink($filePath);
            // 受信ステータスをログ出力
            if (Sgmov_Component_Log::isDebug()) {
                Sgmov_Component_Log::debug("recvStatus\n" . $httpcode);
            }
            if ($httpcode != 200) {
                throw new Sgmov_Component_Exception('ステータスが200ではありません。', Sgmov_Component_ErrorCode::ERROR_BVE_WS_BAD_STATUS);
            }
            $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $header = substr($response, 0, $header_size);
            Sgmov_Component_Log::info("header\n" . $header);
            $body = substr($response, $header_size);
            // レスポンス値を強制出力（強制のためwarningレベル）
            Sgmov_Component_Log::warning("レスポンス\n" . $body);

            curl_close($curl);
            Sgmov_Component_Log::warning("============ End sendCsvToWs ============\n");
        }
        catch (Exception $e) {
            //接続終了
//            if (!empty($filePath))
//            {
//                unlink($filePath);
//            }            
            // デバッグログを出力
            if (Sgmov_Component_Log::isDebug()) {
                Sgmov_Component_Log::debug("エラー内容ログ出力\n" . $e);
            }
            
            throw $e;
        }

        return $body;
    }

    /**
     * リクエストを生成します。
     * @param string $sendFileName 送信するCSVデータのファイル名です。
     * @param string $strCsvData 送信するCSVデータ。改行を含む文字列です。
     * @return string 生成されたリクエストボディ
     */
    public function _createRequest($sendFileName, $strCsvData){
        $boundary = "-----" . md5(uniqid());
        $requestBody = $this->_createRequestBody($sendFileName, $strCsvData, $boundary);
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
    public function _createRequestBody($sendFileName, $strCsvData, $boundary)
    {
        $body = "";

        // ユーザーID
        $body .= "--{$boundary}\r\n";
        $body .= "Content-Disposition: form-data; name=\"" . self::REQUEST_USER_ID_KEY . "\"\r\n";
        $body .= "\r\n";
        $body .= $this->_wsUserId . "\r\n";

        // パスワード
        $body .= "--{$boundary}\r\n";
        $body .= "Content-Disposition: form-data; name=\"" . self::REQUEST_PASSWORD_KEY . "\"\r\n";
        $body .= "\r\n";
        $body .= $this->_wsPassWord . "\r\n";

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
    public function _createRequestHeader($boundary, $contentLength)
    {
        $header = "";
        $header .= "POST " . $this->_wsPath . " HTTP/1.1\r\n";
        $header .= "Host: " . $this->_wsHost . "\r\n";
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
    public function _connectToWs()
    {
        $fp = fsockopen($this->_wsProtocol . $this->_wsHost, $this->_wsPort, $errno, $errstr, 30);
        if(!$fp){
            throw new Sgmov_Component_Exception('接続に失敗しました。', Sgmov_Component_ErrorCode::ERROR_BVE_WS_CONNECT);
        }
        return $fp;
    }

    /**
     * ウェブシステムへデータを送信します。
     * @param resource $fp ソケットハンドル
     * @param string $request リクエスト文字列
     * @throws Sgmov_Component_Exception 処理に失敗した場合例外を投げます。
     */
    public function _send($fp, $request)
    {
        if(!fwrite($fp, $request)){
            
            // デバッグログを出力
            if (Sgmov_Component_Log::isDebug()) {
                Sgmov_Component_Log::debug("リクエストの送信に失敗しました。\n" . $fp);
            }
            
            throw new Sgmov_Component_Exception('リクエストの送信に失敗しました。', Sgmov_Component_ErrorCode::ERROR_BVE_WS_SEND);
        }
    }

    /**
     * ウェブシステムからステータスラインを受信します。
     * @param resource $fp ソケットハンドル
     * @return string ステータスライン
     * @throws Sgmov_Component_Exception 処理に失敗した場合例外を投げます。
     */
    public function _recvStatusLine($fp)
    {
        // デバッグログを出力
        if (Sgmov_Component_Log::isDebug()) {
            Sgmov_Component_Log::debug("ステータスラインの受信開始。\n" . $fp);
        }
            
        if(!($data = fgets($fp))) {
            
            // デバッグログを出力
            if (Sgmov_Component_Log::isDebug()) {
                Sgmov_Component_Log::debug("ステータスラインの受信に失敗しました。\n" . $fp);
            }
            
           throw new Sgmov_Component_Exception('ステータスラインの受信に失敗しました。', Sgmov_Component_ErrorCode::ERROR_BVE_WS_RECV_STATUS);
        }
        return $data;
    }

    /**
     * ステータスコードが 200 OK であることを確認します。
     * @param string $status ステータスライン
     * @throws Sgmov_Component_Exception 200 OK 以外の場合例外を投げます。
     */
    public function _checkStatusCode($status)
    {
        if (substr_count($status, "200 OK") == 0) {
            throw new Sgmov_Component_Exception('ステータスが200ではありません。', Sgmov_Component_ErrorCode::ERROR_BVE_WS_BAD_STATUS);
        }
    }

    /**
     * ウェブシステムからデータを受信します。
     * @param resource $fp ソケットハンドル
     * @return string 受信データ
     * @throws Sgmov_Component_Exception 処理に失敗した場合例外を投げます。
     */
    public function _recv($fp){
        $response = '';

        while (!feof($fp)) {
            if(($buf = fread($fp, 4096)) == FALSE) {
                throw new Sgmov_Component_Exception('データの受信に失敗しました。', Sgmov_Component_ErrorCode::ERROR_BVE_WS_RECV_DATA);
            }
            $response .= $buf;
        }
        return $response;
    }
}
?>
<?php
/**
 * CVS 旅客手荷物受付サービスのコールセンターお申し込みカード決済受付
 * @package    maintenance
 * @subpackage CVS_IVR
 * @author     SMT.Tuan
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
Sgmov_Lib::use3gMdk(array('3GPSMDK'));
/**#@-*/


class Sgmov_Process_Cvs_IVR {

    const BATCH_STATUS = 0;//未連携
    
    const SOSHIN_SHIPPAI    = 0;//0:送信失敗
    const SOSHIN_SEIKO      = 1;//1:送信成功
    
    const KESSAI_SEIKO      = 0;//決済成功

    /**
     *
     * TODO 処理をクラスメソッドに分ける
     * @param
     * @return
     */
    public function execute() {
        
        # ログ出力オブジェクトを作成
        $logger = TGMDK_Logger::getInstance();
        $logger->info('IVRカード決済受取開始。');
        Sgmov_Component_Log::info('IVRカード決済受取開始。');

        # ベリトランスペイメントゲートウェイからの入金通知電文を取得
        $headers = apache_request_headers();
        foreach ($headers as $header => $value) {
            $logger->debug($header . ': ' . $value);
            Sgmov_Component_Log::debug($header . ': ' . $value);
        }

        if (!isset($headers['Content-Length']) || strlen($headers['Content-Length']) <= 0) {
            # 読み込めないので 500 を応答
            header("HTTP/1.1 500 Internal Server Error\r\n");
            header("Content-Type: text/html\r\n\r\n");
            Sgmov_Component_Log::info('IVRカード決済受取失敗。');
            exit;
        }
        
        $body = '';
        $fp   = fopen('php://input', 'r');
        if ($fp == FALSE) {
            $logger->error('IVRカード決済の受信に失敗しました。');
            Sgmov_Component_Log::info('IVRカード決済の受信に失敗しました。');
            # 読み込めないので 500 を応答
            header("HTTP/1.1 500 Internal Server Error\r\n");
            header("Content-Type: text/html\r\n\r\n");
            exit;
        }

        while (!feof($fp)) {
            $body .= fgets($fp);
        }
        fclose($fp);
        Sgmov_Component_Log::info('Body: ' . $body);

        # Content-HMAC を利用して電文の改竄チェックを行う
        $hmac = $headers{'content-hmac'};
        $logger->info('content-hmac: ' . $hmac);
        if (strlen($hmac) <= 0) {
            # Content-HMACがありません
            $logger->error('content-hmacがありません。');
            header("HTTP/1.1 500 Internal Server Error\r\n");
            header("Content-Type: text/html\r\n\r\n");
            Sgmov_Component_Log::info('IVRカード決済の受信エラー：content-hmacがありません。');
            exit;
        }
        Sgmov_Component_Log::info($hmac);
        $checkResult = $this->checkMessage($body, $hmac);
        if (!$checkResult) {
            $logger->error('IVRカード決済の検証に失敗しました。');
            # 改竄の疑いあり 500 を応答
            header("HTTP/1.1 500 Internal Server Error\r\n");
            header("Content-Type: text/html\r\n\r\n");
            Sgmov_Component_Log::info('IVRカード決済の検証に失敗しました。');
            exit;
        }
        $logger->info('IVRカード決済の検証に成功しました。');   
        Sgmov_Component_Log::info('IVRカード決済の検証に成功しました。');
        
        $post = $_POST;

        $pushtime = $post['pushTime'];
        $orderId = $post['orderId'];
        $cardOrderResult = $post['cardOrderResult'];
        $mstatus = isset($post['mstatus']) ? $post['mstatus'] : "";
        $dummy = $post['dummy'];
        $kankyo = $dummy == 0 ? "0：本番" : "1：テスト";
        
        $dateTmp = strtotime($pushtime);
        $kessaiDateTime = date('Y/m/d H:i:s',$dateTmp);
        
        Sgmov_Component_Log::info("【{$kankyo}】の環境でIVRカード決済処理開始。");
            
        # 決済結果を確認
        if ($cardOrderResult == 0 && $mstatus == "success") {
            # 書き込む
            $logger->info('IVRカード決済成功書込開始。');
            Sgmov_Component_Log::info('IVRカード決済成功書込開始。');
            $db = Sgmov_Component_DB::getAdmin();
            $db->begin();
            $count = $this->updateKesaiSeiko($db, $orderId, $kessaiDateTime, $kessaiDateTime);
            if($count != '1') {
                $this->errorUpdateProc($orderId);
            } else {
                $logger->info("IVRの【{$orderId}】の決済通知書込成功。");
                Sgmov_Component_Log::info("IVRの【{$orderId}】の決済通知書込成功。");   
            }
            $db->commit();
            
            $logger->info('IVRカード決済通知処理終了。ベリトランスペイメントゲートウェイへ 200 OK を応答。');
            Sgmov_Component_Log::info('入金通知処理終了。ベリトランスペイメントゲートウェイへ 200 OK を応答。');
        } else {
            $logger->info("IVRの【{$orderId}】の決済が{$cardOrderResult}でした。(0:成功、1:失敗、2:その他、3:エラー)");
            Sgmov_Component_Log::info("IVRの【{$orderId}】の決済が{$cardOrderResult}でした。(0:成功、1:失敗、2:その他、3:エラー)");
            $this->updateKesaiShippai($db, $orderId, $kessaiDateTime, $cardOrderResult);
            $logger->info('IVRカード決済通知処理終了。（決済失敗）');
            Sgmov_Component_Log::info('IVRカード決済通知処理終了。（決済失敗）');
        }

        # ダミーの HTML 文を出力
        echo "Content-type: text/html\r\n\r\n";
        echo "Push data Accepted.\n";
    }
    
    /**
     * 
     */
    private function errorUpdateProc($order_id) {
        // システム管理者メールアドレスを取得する。
        $mail_to = Sgmov_Component_Config::getLogMailTo ();

        Sgmov_Component_Log::debug ('mail_to:'.$mail_to);

        // メールを送信する。
        Sgmov_Component_Mail::sendTemplateMail (array('id' => $order_id), dirname ( __FILE__ ) . '/../../lib/mail_template/ivr/ivr_cvs_error.txt.txt', $mail_to );
    }
    
    /**
     * 署名を検証する。
     *
     * @access public
     * @static
     * @param String $msgBody 署名の元となる文字列
     * @param String $sContentHmac 検証の対象となる署名文字列
     */
    private function checkMessage($msgBody, $sContentHmac) {
        // パラメータ【$msgBody】の入力チェック
        if (empty($msgBody)) {
            return FALSE;
        }

        // パラメータ【$sContentHmac】の入力チェック
        if (empty($sContentHmac)) {
            return FALSE;
        }

        // 区切り文字列
        $delimiter = ";v=";

        // 区切り文字の位置を算出
        $pos = strpos($sContentHmac, $delimiter);

        // 区切り文字が見つからない場合
        if ($pos == FALSE) {
            return FALSE;
        }

        // パラメータからHmac部分の文字列のみを取得する
        $s_pos = $pos + strlen($delimiter);
        $param_hmac = substr($sContentHmac, $s_pos);

        // Hmacの算出
        $hmac = $this->calcHmac($msgBody);
        if (empty($hmac)) {
            return FALSE;
        }

        // Hmacが一致したかチェック
        if ($param_hmac == $hmac) {
            return TRUE;
        }

        return FALSE;
    }
    
    /**
     * Hmacのハッシュを求める。
     *
     * @access private
     * @static
     * @param String $msgBody
     * @return String 求めたHmacハッシュ値
     */
    private function calcHmac($msgBody) {
        // マーチャントパスワード
        $merchant_secret_key = Sgmov_Component_Config::getPswdPaymentInforApiForIVR();

        // バイナリ文字列に変更
        parse_str($msgBody, $output);
        
        $callSid = $output['callSid'];
        $orderId = $output['orderId'];
        $amount = $output['amount'];
        //HMAC 値には、下記の値を連結した文字列（区切り文字なし、数値はカンマなし）を"h="に設定されたアルゴリズムでハッシュ化した値を設定する。
        //Call SID、取引 ID、金額
        
        $body = $callSid. $orderId. $amount;
        // ハッシュ生成
        return hash_hmac("sha256", $body, $merchant_secret_key);
    }

    /**
     *
     * IVRカード決済完了の状態を登録
     * @param string $payment_order_id
     * @param string $orderDateTime
     * @param string $incomingDateTime
     * @return
     */
    private function updateKesaiSeiko($db, $payment_order_id, $orderDateTime, $incomingDateTime) {
        try {
            $query = '
                UPDATE
                    cruise
                SET
                    merchant_result = $1,
                    merchant_datetime = $2,
                    receipted = $3,
                    batch_status = $4,
                    call_merchant_result = $5,
                    modified = current_timestamp
                    WHERE
                    payment_order_id = $6;';
            $query = preg_replace('/\s+/u', ' ', trim($query));
            $count = $db->executeUpdate($query, array(
                self::SOSHIN_SEIKO,
                $incomingDateTime,
                $orderDateTime,
                1,
                self::KESSAI_SEIKO,
                $payment_order_id
            ));
            return $count;
        } catch ( Sgmov_Component_Exception $sce ) {
			Sgmov_Component_Log::err ("IVRカード決済：データベースに決済済の状態を更新失敗しました。");
            Sgmov_Component_Log::err ($sce);
            $this->errorUpdateProc($payment_order_id);
		}
    }
    
    /**
     *
     * IVRカード決済失敗を登録
     * @param string $payment_order_id
     * @param string $incomingDateTime
     * @param string $orderDateTime
     * @return
     */
    private function updateKesaiShippai($db, $payment_order_id, $incomingDateTime, $cardOrderResult) {
        try {
            $query = '
                UPDATE
                    cruise
                SET
                    merchant_result = $1,
                    merchant_datetime = $2,
                    call_merchant_result = $3,
                    modified = current_timestamp
                    WHERE
                    payment_order_id = $4;';
            $query = preg_replace('/\s+/u', ' ', trim($query));
            $count = $db->executeUpdate($query, array(
                self::SOSHIN_SHIPPAI,
                $incomingDateTime,
                $cardOrderResult,
                $payment_order_id
            ));
            return $count;
        } catch ( Sgmov_Component_Exception $sce ) {
			Sgmov_Component_Log::err ("IVRカード決済：データベースに決済済の状態を更新失敗しました。");
            Sgmov_Component_Log::err ($sce);
            $this->errorUpdateProc($payment_order_id);
		}
    }
}

<?php
/**
 * BCC
 * @package    maintenance
 * @subpackage BCC
 * @author     FPT-AnNV6
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
Sgmov_Lib::use3gMdk(array('3GPSMDK'));
/**#@-*/


class Sgmov_Process_Bcc {

    const API_ENDPOINT = 'https://ivr.veritrans.co.jp/ivrcore/api/payment-results?orderId=%s';

    const CONTENT_HMAC = 'content-hmac: h=HmacSHA256;s=%s;v=%s';

    const REQ_FLG = '1';//コールセンターのデータ

    const PAYMENT_METHOD_CD = 2;//カード決済

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
        Sgmov_Component_Log::info("IVR_決済結果取得バッチの処理開始。");
        $db = Sgmov_Component_DB::getAdmin();
                
        Sgmov_Component_Log::info("1.未決済と決済失敗の対象を取得する処理開始");
        $query3 = "SELECT * FROM cruise "
                . "WHERE req_flg = $1 "
                . "AND payment_method_cd = $2 "
                . "AND receipted IS NULL "
                . "AND batch_status = $3"
                . "AND (call_merchant_result IS NULL OR call_merchant_result IN (1,2))";
        $data = $db->executeQuery($query3, [
            self::REQ_FLG,
            self::PAYMENT_METHOD_CD,
            self::BATCH_STATUS
        ]);
                
        if ($data->size() <= 0){
            Sgmov_Component_Log::info("IVR_対象データが無い為、未決済と決済失敗の処理終了");
        } else {
            Sgmov_Component_Log::info("対象データの件数：{$data->size()}");
            $merchant_secret_key = Sgmov_Component_Config::getPswdPaymentInforApiForIVR();
            $merchantId = Sgmov_Component_Config::getMerchantIdForIVR();

            for ($i = 0; $i < $data->size(); ++$i) {
                $row = $data->get($i);
                $res = $this->callIvrPaymentResults($row['payment_order_id'], $merchant_secret_key, $merchantId);

                if ($res !== NULL) {
                    //通話中かどうか判断する
                    if (isset($res->cardOrderResult)) {
                        $incomingDateTime = $res->incomingDateTime;
                        //決済完了且つ処理結果ステータスが成功の場合
                        //if ($res->cardOrderResult == 0 && isset($res->cardTransactionResults[0]->mstatus) && $res->cardTransactionResults[0]->mstatus == 'success') {
                        if ($res->cardOrderResult == 0 && isset($res->cardTransactionResults)) {
                            //カード取引明細の配列の中で、最終の物を取得する
                            $lastCardTransactionResults= end($res->cardTransactionResults);
                            if ($lastCardTransactionResults->mstatus == 'success') {
                                $orderDateTime = $lastCardTransactionResults->orderDateTime;
                                $update = $this->updateKesaiSeiko($db, $row['payment_order_id'], $orderDateTime, $incomingDateTime);
                                if($update != '1') {
                                    Sgmov_Component_Log::err("IVR:payment_order_idが{$row['payment_order_id']}で決済完了の状態が登録されませんでした。");
                                    $this->errorUpdateProc($row['payment_order_id']);
                                } else {
                                    Sgmov_Component_Log::info("payment_order_idが{$row['payment_order_id']}で決済完了の状態を登録しました。");
                                }
                            } else {
                                Sgmov_Component_Log::err("IVR:payment_order_idが{$row['payment_order_id']}でカード取引結果が{$res->cardOrderResult}で、3回目まで異常終了でした。（カード取引結果が0:成功、1:失敗、2:その他、3:エラー）");
                            }
                        } else {
                            $update = $this->updateKesaiShippai($db, $row['payment_order_id'], $incomingDateTime, $res->cardOrderResult);
                            Sgmov_Component_Log::info("payment_order_idが{$row['payment_order_id']}でカード取引結果が{$res->cardOrderResult}で登録しました。（カード取引結果が0:成功、1:失敗、2:その他、3:エラー）");
                        }
                    } else {
                        Sgmov_Component_Log::info("payment_order_idが{$row['payment_order_id']}で通話中なので、スキップする。");
                    }
                } else {
                    Sgmov_Component_Log::info("payment_order_idが{$row['payment_order_id']}で決済情報が無い為、スキップする。");
                }
            }
            
            Sgmov_Component_Log::info("未決済と決済失敗の処理終了");
        }
        
        //未決済データの中で決済画面表示から1日経過の物を更新⇒5:決済画面表示から1日経過
        Sgmov_Component_Log::info("2.未決済データの中で決済画面表示から1日経過を更新する処理開始。");
        $query = "UPDATE cruise SET "
                . "call_merchant_result = 5"
                . ",modified = current_timestamp "
                . "WHERE req_flg = '1' "
                . "AND payment_method_cd = 2 "
                . "AND receipted IS NULL "
                . "AND batch_status = 0 "
                . "AND call_merchant_result IS NULL "
                . "AND (created + INTERVAL '1 day' < CURRENT_TIMESTAMP)";
        $count = $db->executeUpdate($query);
        Sgmov_Component_Log::info("未決済データの中で決済画面表示から1日経過を更新する処理終了：{$count}件");
        
        //決済失敗のデータの中で決済データ送信から1日経過の物を更新⇒4:決済データ送信から1日経過
        Sgmov_Component_Log::info("3.「決済失敗」と「その他」のデータの中で決済データ送信から1日経過の物を更新する処理開始。");
        $query2 = "UPDATE cruise SET "
                . "call_merchant_result = 4 "
                . ",modified = current_timestamp "
                . "WHERE req_flg = '1' "
                . "AND payment_method_cd = 2 "
                . "AND receipted IS NULL "
                . "AND batch_status = 0 "
                . "AND (call_merchant_result = 1 OR call_merchant_result = 2) "
                . "AND (merchant_datetime + INTERVAL '1 day' < CURRENT_TIMESTAMP)";
        $count2 = $db->executeUpdate($query2);
        Sgmov_Component_Log::info("「決済失敗」と「その他」のデータの中で決済データ送信から1日経過の物を更新する処理終了：{$count2}件");
        
        Sgmov_Component_Log::info("IVR_決済結果取得バッチの処理終了。");
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
    
    /**
     * IVR決済で決済結果取得 API
     * @param string $payment_order_id
     * @param string $merchant_secret_key
     * @param string $merchantId
     * @return true or false
     */
    private function callIvrPaymentResults($payment_order_id, $merchant_secret_key, $merchantId) {
        try {
            $v = hash_hmac('sha256', $payment_order_id, $merchant_secret_key);
    
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
                CURLOPT_URL => sprintf(self::API_ENDPOINT, $payment_order_id),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    sprintf(self::CONTENT_HMAC, $merchantId, $v)
                ),
            ));
            Sgmov_Component_Log::info (sprintf(self::API_ENDPOINT, $payment_order_id));
            Sgmov_Component_Log::info ("Header:" .sprintf(self::CONTENT_HMAC, $merchantId, $v));
            $response = curl_exec($curl);
            $info = curl_getinfo($curl);
            $start = $info['header_size'];
            $body = substr($response, $start, strlen($response) - $start);
            curl_close($curl);
            if ($info['http_code'] == 200) {
                Sgmov_Component_Log::debug($body);
                if ($body && $body != null) {
                    $obj_body = json_decode($body);
                    if ($obj_body->results && isset($obj_body->results[0])) {
                        Sgmov_Component_Log::info ($obj_body->results[0]);
                        return $obj_body->results[0];
                    } else {
                        Sgmov_Component_Log::info ("payment_order_idが{$payment_order_id}で　IVRの決済結果取得APIのBodyを取得できない。");
                        return null;
                    }
                }
            } else {
                Sgmov_Component_Log::info ("payment_order_idが{$payment_order_id}で　IVRの決済結果取得APIのヘーダ―が200以外でした。");
                return null;
            }
        } catch ( Sgmov_Component_Exception $sce ) {
			Sgmov_Component_Log::err ($sce);
            Sgmov_Component_Log::err ("IVRの決済結果取得APIを呼ぶ失敗しました。");
            return null;
		}
    }
    
    /**
     * 
     */
    private function errorUpdateProc($order_id) {
        // システム管理者メールアドレスを取得する。
        $mail_to = Sgmov_Component_Config::getLogMailTo ();

        Sgmov_Component_Log::debug ('mail_to:'.$mail_to);

        // メールを送信する。
        Sgmov_Component_Mail::sendTemplateMail (array('id' => $order_id), dirname ( __FILE__ ) . '/../../lib/mail_template/ivr/ivr_cvs_error.txt', $mail_to );
    }

}

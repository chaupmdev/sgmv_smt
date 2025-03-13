<?php
/**
 * CVS 旅客手荷物受付サービスのお申し込みコンビニ決済受付
 * @package    maintenance
 * @subpackage CVS
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
Sgmov_Lib::use3gMdk(array('3GPSMDK'));
/**#@-*/

class Sgmov_Process_Cvs {

    /**
     *
     * TODO 処理をクラスメソッドに分ける
     * @param
     * @return
     */
    public function execute() {

        # ログ出力オブジェクトを作成
        $logger = TGMDK_Logger::getInstance();
        $logger->info('コンビニ決済入金通知データ受取開始。');
        Sgmov_Component_Log::info('コンビニ決済入金通知データ受取開始。');

        # ベリトランスペイメントゲートウェイからの入金通知電文を取得
        $headers = apache_request_headers();
        foreach ($headers as $header => $value) {
            $logger->debug($header . ': ' . $value);
            Sgmov_Component_Log::debug($header . ': ' . $value);
        }

        if (!isset($headers['Content-Length']) || strlen($headers['Content-Length']) <= 0) {
            # 読み込めないので 500 を応答
            header("HTTP/1.0 500 Internal Server Error\r\n");
            header("Content-Type: text/html\r\n\r\n");
            exit;
        }

        $body = '';
        $fp   = fopen('php://input', 'r');
        if ($fp == FALSE) {
            $logger->error('入金通知データの受信に失敗しました。');
            Sgmov_Component_Log::err('入金通知データの受信に失敗しました。');
            # 読み込めないので 500 を応答
            header("HTTP/1.0 500 Internal Server Error\r\n");
            header("Content-Type: text/html\r\n\r\n");
            #echo ("<html><head><title>Input failed.</title></head>");
            #echo ("<body>Input failed.</body></html>");
            exit;
        }

        while (!feof($fp)) {
            $body .= fgets($fp);
        }
        fclose($fp);
        //$logger->debug('Body: ' . $body);
        Sgmov_Component_Log::debug('Body: ' . $body);

        # Content-HMAC を利用して電文の改竄チェックを行う
        $hmac = $headers{'content-hmac'};
        $logger->info('content-hmac: ' . $hmac);
        if (strlen($hmac) <= 0) {
            # Content-HMACがありません
            $logger->error('content-hmacがありません。');
            header("HTTP/1.0 500 Internal Server Error\r\n");
            header("Content-Type: text/html\r\n\r\n");
            exit;
        }
        if (!TGMDK_MerchantUtility::checkMessage($body, $hmac)) {
            $logger->error('入金通知データの検証に失敗しました。');
            # 改竄の疑いあり 500 を応答
            header("HTTP/1.0 500 Internal Server Error\r\n");
            header("Content-Type: text/html\r\n\r\n");
            #echo ("<html><head><title>HMAC failed.</title></head>");
            #echo ("<body>HMAC failed.</body></html>");
            exit;
        }
        $logger->info('入金通知データの検証に成功しました。');

        $post = $_POST;

        $pushtime = $post['pushTime'];
        $pushid = $post['pushId'];

        # 書き込む
        $number_of_notify = $post['numberOfNotify'];
        $logger->info('入金通知書込開始。');
        Sgmov_Component_Log::info('入金通知書込開始。');

        $query = '
            UPDATE
                cruise
            SET
                batch_status = $1,
                receipted    = current_timestamp,
                modified     = current_timestamp
            WHERE
                payment_order_id = $2;';

        $query_cargo = '
            UPDATE
                dat_cargo
            SET
                crg_batch_status = $1,
                crg_receipted    = current_timestamp,
                crg_update_date  = current_timestamp
            WHERE
                payment_order_id = $2;';

        $query_event = '
            UPDATE
                comiket
            SET
                receipted = current_timestamp,
                modified = current_timestamp
            WHERE
                payment_order_id = $1;';

        //新引越
        //2022/10/24 TuanLK 新引越の対応
        $query_payment = '
            UPDATE
                dat_n_hk_shiharai 
            SET
                pay_merchant_result = 1,
                pay_receipted    = current_timestamp,
                pay_modified     = current_timestamp 
            WHERE
                pay_payment_order_id = $1;';       

        $query = preg_replace('/\s+/u', ' ', trim($query));
        $query_cargo = preg_replace('/\s+/u', ' ', trim($query_cargo));
        $query_event = preg_replace('/\s+/u', ' ', trim($query_event));
        $query_payment = preg_replace('/\s+/u', ' ', trim($query_payment));
        
///////////////////////////////////////////////////////////////////
//$number_of_notify = '2';
//$post['orderId0000'] = 'sagawa-moving_20150414215223_swoH0VxW84LyMUFTI6SQne1chEdtZbOmPG9KgY5fpkCuqA2Nvj3DaR7rliBzZZ';
//$post['orderId0001'] = 'sagawa-moving_20150414215223_swoH0VxW84LyMUFTI6SQne1chEdtZbOmPG9KgY5fpkCuqA2Nvj3DaR7rliBzXJ';
///////////////////////////////////////////////////////////////////

        for ($i = 0; $i < $number_of_notify; ++$i) {
            $index = sprintf('%04s', $i);

            # オーダーID
            $key   = 'orderId' . $index;
            $order_id = $post[$key];

            # CVSタイプ
            //$key   = 'cvsType' . $index;
            //$value = $post[$key];

            # 受付番号
            //$key   = 'receiptNo' . $index;
            //$receipt_cd = $post[$key];

            # 入金日時
            //$key   = 'receiptDate' . $index;
            //$value = $post[$key];

            # 入金金額
            //$key   = 'rcvAmount' . $index;
            //$value = $post[$key];

            # ダミー決済フラグ
            //$key   = 'dummy' . $index;
            //$value = $post[$key];

            # レコードを書き込む
            Sgmov_Component_Log::info($order_id);
            //新引越
            if (substr($order_id, 0, 23) === 'sagawa-moving-hikkoshi_') {
                # レコードを書き込む
                $db = Sgmov_Component_DB::getHikkoshiDetail();
                $db->begin();
                $count = $db->executeUpdate($query_payment, array($order_id));//Hard-code
                 if($count != '1') {
                      $this->errorUpdateProc($order_id);
                 }
                 $db->commit();
            } elseif (substr($order_id, 0, 20) === 'sagawa-moving-cargo_' || substr($order_id, 0, 20) === 'sagawa-moving-event_' || substr($order_id, 0, 14) === 'sagawa-moving_') {
                $db = Sgmov_Component_DB::getAdmin();
                $db->begin();

                if (substr($order_id, 0, 20) === 'sagawa-moving-cargo_') {
                    Sgmov_Component_Log::debug('カーゴデータを更新');
                    $db->executeUpdate($query_cargo, array('1', $order_id));
                } elseif (substr($order_id, 0, 20) === 'sagawa-moving-event_') {
                    Sgmov_Component_Log::debug('イベントデータを更新');
                    $count = $db->executeUpdate($query_event, array($order_id));
                    if($count != '1') {
                        $this->errorUpdateProc($order_id);
                    }
                    $this->checkComiket($order_id);
                } elseif (substr($order_id, 0, 14) === 'sagawa-moving_') {
                    Sgmov_Component_Log::debug('旅客手荷物受付サービスのお申し込みを更新');
                    $count = $db->executeUpdate($query, array('1', $order_id));
                    if($count != '1') {
                        $this->errorUpdateProc($order_id);
                    }
                }

                $db->commit();
            } else {
                $this->errorUpdateProc($order_id);
            }

            //$db->commit();
        }

        $logger->info('入金通知書込終了。');
        Sgmov_Component_Log::info('入金通知書込終了。');
        $logger->info('入金通知処理終了。ベリトランスペイメントゲートウェイへ 200 OK を応答。');
        Sgmov_Component_Log::info('入金通知処理終了。ベリトランスペイメントゲートウェイへ 200 OK を応答。');

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
        Sgmov_Component_Mail::sendTemplateMail (array('id' => $order_id), dirname ( __FILE__ ) . '/../../lib/mail_template/cvs_bcm_error.txt', $mail_to );
    }

    /**
     * コミケ申込で遅すぎた入金にたいしてエラー通知メール送信する。
     * @param string $order_id
     */
    private function checkComiket($order_id) {
        $comiket = $this->selectComiket($order_id);

        Sgmov_Component_Log::debug ($comiket);

        if($comiket == null){
            return;
        }

        // システム管理者メールアドレスを取得する。
        $mail_to = Sgmov_Component_Config::getLogMailTo ();

        Sgmov_Component_Log::debug ('mail_to:'.$mail_to);

        // メールを送信する。
        Sgmov_Component_Mail::sendTemplateMail ( $comiket, dirname ( __FILE__ ) . '/../../lib/mail_template/cvs_bcm_late.txt', $mail_to );

        return;
    }

    /**
     * コミケ申込データを取得
     * @param string $order_id
     * @return array
     */
    private function selectComiket($order_id) {
        $db = Sgmov_Component_DB::getAdmin ();
        $sql = "select
                c.id as id,
                to_char(c.receipted, 'yyyy-mm-dd hh24:mi:ss') as receipted,
                to_char(d.collect_date, 'yyyy-mm-dd') as collect_date
                from comiket c
                inner join comiket_detail d on c.id = d.comiket_id and d.type = 1
                where c.payment_order_id = $1 and c.receipted is not null and c.receipted >= d.collect_date ";
        $list = $db->executeQuery ( $sql, array (
                $order_id
        ) );
        Sgmov_Component_Log::debug ( 'size='.$list->size () );
        if ($list->size () == 0) {
            return null;
        }
        return $list->get ( 0 );
    }

}
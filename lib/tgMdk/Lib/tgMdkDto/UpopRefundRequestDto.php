<?php
/**
 * 決済サービスタイプ：UPOP、コマンド名：返金の要求Dtoクラス<br>
 *
 * @author Veritrans, Inc.
 *
 */
class UpopRefundRequestDto extends AbstractPaymentRequestDto
{

    /**
     * 決済サービスタイプ<br>
     * 半角英数字<br>
     * 必須項目、固定値<br>
     */
    private $SERVICE_TYPE = "upop";

    /**
     * 決済サービスコマンド<br>
     * 半角英数字<br>
     * 必須項目、固定値<br>
     */
    private $SERVICE_COMMAND = "Refund";

    /**
     * 取引ID<br>
     * 半角英数字<br/>
     * 最大桁数：100<br/>
     * 返金対象の取引IDを指定する。<br/>
     */
    private $orderId;

    /**
     * 決済金額<br>
     * 半角数字<br/>
     * 最大桁数：12<br/>
     * 返金する金額を指定します。<br/>
     * - 残額と同額指定時は通常の返金とし、1円以上残額未満の場合は部分返金とします。<br/>
     */
    private $amount;


    /**
     * ログ用文字列(マスク済み)<br>
     * 半角英数字<br>
     */
    private $maskedLog;


    /**
     * 決済サービスタイプを取得する<br>
     * @return 決済サービスタイプ<br>
     */
    public function getServiceType() {
        return $this->SERVICE_TYPE;
    }

    /**
     * 決済サービスコマンドを取得する<br>
     * @return 決済サービスコマンド<br>
     */
    public function getServiceCommand() {
        return $this->SERVICE_COMMAND;
    }

    /**
     * 取引IDを取得する<br>
     * @return 取引ID<br>
     */
    public function getOrderId() {
        return $this->orderId;
    }

    /**
     * 取引IDを設定する<br>
     * @param orderId 取引ID<br>
     */
    public function setOrderId($orderId) {
        $this->orderId = $orderId;
    }

    /**
     * 決済金額を取得する<br>
     * @return 決済金額<br>
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * 決済金額を設定する<br>
     * @param amount 決済金額<br>
     */
    public function setAmount($amount) {
        $this->amount = $amount;
    }


    /**
     * ログ用文字列(マスク済み)を設定する<br>
     * @param  maskedLog ログ用文字列(マスク済み)<br>
     */
    public function _setMaskedLog($maskedLog) {
        $this->maskedLog = $maskedLog;
    }

    /**
     * ログ用文字列(マスク済み)を取得する<br>
     * @return ログ用文字列(マスク済み)<br>
     */
    public function toString() {
        return (string)$this->maskedLog;
    }

}
?>
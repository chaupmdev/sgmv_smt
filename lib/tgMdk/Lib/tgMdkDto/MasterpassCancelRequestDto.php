<?php
/**
 * 決済サービスタイプ：MasterPass、コマンド名：取消の要求Dtoクラス<br>
 *
 * @author Veritrans, Inc.
 *
 */
class MasterpassCancelRequestDto extends AbstractPaymentRequestDto
{

    /**
     * 決済サービスタイプ<br>
     * 半角英数字<br>
     * 必須項目、固定値<br>
     */
    private $SERVICE_TYPE = "masterpass";

    /**
     * 決済サービスコマンド<br>
     * 半角英数字<br>
     * 必須項目、固定値<br>
     */
    private $SERVICE_COMMAND = "Cancel";

    /**
     * 取引ID<br>
     * 半角英数字<br/>
     * 最大桁数：80<br/>
     * キャンセル対象の取引IDを指定する。<br/>
     */
    private $orderId;

    /**
     * 金額<br>
     * 半角数字<br/>
     * 最大桁数：8<br/>
     * 減額する金額を指定します。<br/>
     * 指定なしまたは残額と同額指定時は通常の取消とし、1円以上残額未満の場合部分取消となります。<br/>
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
     * 金額を取得する<br>
     * @return 金額<br>
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * 金額を設定する<br>
     * @param amount 金額<br>
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
    public function __toString() {
        return (string)$this->maskedLog;
    }

}
?>
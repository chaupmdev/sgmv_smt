<?php
/**
 * 検索結果:決済トランザクションのクラス<br>
 *
 * @author Veritrans, Inc.
 */
class TransactionInfo {

    /**
     * トランザクション管理ID<br>
     */
    private $txnId;
    /**
     * コマンド<br>
     */
    private $command;
    /**
     * ステータスコード<br>
     */
    private $mstatus;
    /**
     * 結果コード<br>
     */
    private $vResultCode;
    /**
     * 取引日時<br>
     */
    private $txnDatetime;
    /**
     * 金額<br>
     */
    private $amount;

    /**
     * 固有トランザクション情報<br>
     */
    private $properTransactionInfo;

    /**
     * トランザクション管理IDを取得する<br>
     *
     * @return $this->トランザクション管理ID<br>
     */
    public function getTxnId() {
        return $this->txnId;
    }

    /**
     * トランザクション管理IDを設定する<br>
     *
     * @param txnId トランザクション管理ID<br>
     */
    public function setTxnId($txnId) {
        $this->txnId = $txnId;
    }

    /**
     * コマンドを取得する<br>
     *
     * @return $this->コマンド<br>
     */
    public function getCommand() {
        return $this->command;
    }

    /**
     * コマンドを設定する<br>
     *
     * @param command コマンド<br>
     */
    public function setCommand($command) {
        $this->command = $command;
    }

    /**
     * ステータスコードを取得する<br>
     *
     * @return $this->ステータスコード<br>
     */
    public function getMstatus() {
        return $this->mstatus;
    }

    /**
     * ステータスコードを設定する<br>
     *
     * @param mstatus ステータスコード<br>
     */
    public function setMstatus($mstatus) {
        $this->mstatus = $mstatus;
    }

    /**
     * 結果コードを取得する<br>
     *
     * @return $this->結果コード<br>
     */
    public function getVResultCode() {
        return $this->vResultCode;
    }

    /**
     * 結果コードを設定する<br>
     *
     * @param vResultCode 結果コード<br>
     */
    public function setVResultCode($vResultCode) {
        $this->vResultCode = $vResultCode;
    }

    /**
     * 取引日時を取得する<br>
     *
     * @return $this->取引日時<br>
     */
    public function getTxnDatetime() {
        return $this->txnDatetime;
    }

    /**
     * 取引日時を設定する<br>
     *
     * @param txnDatetime 取引日時<br>
     */
    public function setTxnDatetime($txnDatetime) {
        $this->txnDatetime = $txnDatetime;
    }

    /**
     * 金額を取得する<br>
     *
     * @return $this->金額<br>
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * 金額を設定する<br>
     *
     * @param amount 金額<br>
     */
    public function setAmount($amount) {
        $this->amount = $amount;
    }

    /**
     * 固有トランザクション情報を取得する<br>
     *
     * @return $this->固有トランザクション情報<br>
     */
    public function getProperTransactionInfo() {
        return $this->properTransactionInfo;
    }

    /**
     * 固有トランザクション情報を設定する<br>
     *
     * @param amount 固有トランザクション情報<br>
     */
    public function setProperTransactionInfo($properTransactionInfo) {
        $this->properTransactionInfo = $properTransactionInfo;
    }

}
?>
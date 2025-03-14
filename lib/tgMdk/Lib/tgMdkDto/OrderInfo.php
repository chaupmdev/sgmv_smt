<?php
/**
 * 検索結果:オーダー情報のクラス<br>
 *
 * @author Veritrans, Inc.
 */
class OrderInfo {

    /**
     * インデックス<br>
     * 検索されたオーダーのインデックス<br>
     */
    private $index;

    /**
     * 決済サービスタイプ<br>
     */
    private $serviceTypeCd;

    /**
     * オーダーID<br>
     */
    private $orderId;
    /**
     * オーダー決済状態<br>
     */
    private $orderStatus;
    /**
     * 最終成功トランザクションタイプ<br>
     */
    private $lastSuccessTxnType;
    /**
     * 詳細トランザクションタイプ<br>
     */
    private $successDetailTxnType;
    /**
     * 固有オーダー情報<br>
     */
    private $properOrderInfo;
    /**
     * 決済トランザクションリスト<br>
     */
    private $transactionInfos;
    /**
     * 取引メモ1<br>
     */
    private $memo1;
    /**
     * 取引メモ2<br>
     */
    private $memo2;
    /**
     * 取引メモ3<br>
     */
    private $memo3;
    /**
     * キー情報<br>
     */
    private $freeKey;
    /**
     * 会員ID<br>
     */
    private $accountId;

    /**
     *
     * インデックスを取得する<br>
     *
     * @return $this->インデックス<br>
     */
    public function getIndex() {
        return $this->index;
    }

    /**
     * インデックスを設定する<br>
     *
     * @param index インデックス<br>
     */
    public function setIndex($index) {
        $this->index = $index;
    }

    /**
     * 決済サービスタイプを取得する<br>
     *
     * @return $this->決済サービスタイプ<br>
     */
    public function getServiceTypeCd() {
        return $this->serviceTypeCd;
    }

    /**
     * 決済サービスタイプを設定する<br>
     *
     * @param serviceTypeCd 決済サービスタイプ<br>
     */
    public function setServiceTypeCd($serviceTypeCd) {
        $this->serviceTypeCd = $serviceTypeCd;
    }

    /**
     * オーダーIDを取得する<br>
     *
     * @return $this->オーダーID<br>
     */
    public function getOrderId() {
        return $this->orderId;
    }

    /**
     * オーダーIDを設定する<br>
     *
     * @param orderId オーダーID<br>
     */
    public function setOrderId($orderId) {
        $this->orderId = $orderId;
    }

    /**
     * オーダー決済状態を取得する<br>
     *
     * @return $this->オーダー決済状態<br>
     */
    public function getOrderStatus() {
        return $this->orderStatus;
    }

    /**
     * オーダー決済状態を設定する<br>
     *
     * @param orderStatus オーダー決済状態<br>
     */
    public function setOrderStatus($orderStatus) {
        $this->orderStatus = $orderStatus;
    }

    /**
     * 最終成功トランザクションタイプを取得する<br>
     *
     * @return $this->最終成功トランザクションタイプ<br>
     */
    public function getLastSuccessTxnType() {
        return $this->lastSuccessTxnType;
    }

    /**
     * 最終成功トランザクションタイプを設定する<br>
     *
     * @param lastSuccessTxnType 最終成功トランザクションタイプ<br>
     */
    public function setLastSuccessTxnType($lastSuccessTxnType) {
        $this->lastSuccessTxnType = $lastSuccessTxnType;
    }

    /**
     * 詳細トランザクションタイプを取得する<br>
     *
     * @return $this->詳細トランザクションタイプ<br>
     */
    public function getSuccessDetailTxnType() {
        return $this->successDetailTxnType;
    }

    /**
     * 詳細トランザクションタイプを設定する<br>
     *
     * @param successDetailTxnType 詳細トランザクションタイプ<br>
     */
    public function setSuccessDetailTxnType($successDetailTxnType) {
        $this->successDetailTxnType = $successDetailTxnType;
    }

    /**
     * 固有オーダー情報を取得する<br>
     *
     * @return $this->固有オーダー情報<br>
     */
    public function getProperOrderInfo() {
        return $this->properOrderInfo;
    }

    /**
     * 固有オーダー情報を設定する<br>
     *
     * @param properOrderInfo 固有オーダー情報<br>
     */
    public function setProperOrderInfo($properOrderInfo) {
        $this->properOrderInfo = $properOrderInfo;
    }

    /**
     * 決済トランザクションリストを取得する<br>
     *
     * @return $this->決済トランザクションリスト<br>
     */
    public function getTransactionInfos() {
        return $this->transactionInfos;
    }

    /**
     * 決済トランザクションリストを設定する<br>
     *
     * @param transactioninfos 決済トランザクションリスト<br>
     */
    public function setTransactionInfos($transactionInfos) {
        $this->transactionInfos = $transactionInfos;
    }

    /**
     * 取引メモ1を取得する<br>
     * @return 取引メモ1<br>
     */
    public function getMemo1() {
        return $this->memo1;
    }

    /**
     * 取引メモ1を設定する<br>
     * @param $memo1 取引メモ1<br>
     */
    public function setMemo1($memo1) {
        $this->memo1 = $memo1;
    }

    /**
     * 取引メモ2を取得する<br>
     * @return 取引メモ2<br>
     */
    public function getMemo2() {
        return $this->memo2;
    }

    /**
     * 取引メモ2を設定する<br>
     * @param $memo2 取引メモ2<br>
     */
    public function setMemo2($memo2) {
        $this->memo2 = $memo2;
    }

    /**
     * 取引メモ3を取得する<br>
     * @return 取引メモ3<br>
     */
    public function getMemo3() {
        return $this->memo3;
    }

    /**
     * 取引メモ3を設定する<br>
     * @param $memo3 取引メモ3<br>
     */
    public function setMemo3($memo3) {
        $this->memo3 = $memo3;
    }

    /**
     * キー情報を取得する<br>
     * @return キー情報<br>
     */
    public function getFreeKey() {
        return $this->freeKey;
    }

    /**
     * キー情報を設定する<br>
     * @param $freeKey キー情報<br>
     */
    public function setFreeKey($freeKey) {
        $this->freeKey = $freeKey;
    }

    /**
     * 会員IDを取得する<br>
     * @return 会員ID<br>
     */
    public function getAccountId() {
        return $this->accountId;
    }

    /**
     * 会員IDを設定する<br>
     * @param $accountId 会員ID<br>
     */
    public function setAccountId($accountId) {
        $this->accountId = $accountId;
    }
}
?>
<?php
/**
 * 検索条件:Alipay検索パラメータクラス<br>
 *
 * @author Veritrans, Inc.
 *
 */
class AlipaySearchParameter
{

    /**
     * 詳細取引決済状態<br>
     */
    private $detailOrderType;

    /**
     * 決済センターとの取引ID<br>
     */
    private $centerTradeId;

    /**
     * 支払日時<br>
     */
    private $paymentTime;

    /**
     * 清算日時<br>
     */
    private $settlementTime;

    /**
     * 決済種別<br>
     */
    private $payType;

    /**
     * 詳細取引決済状態を取得する<br>
     *
     * @return 詳細取引決済状態<br>
     */
    public function getDetailOrderType() {
        return $this->detailOrderType;
    }

    /**
     * 詳細取引決済状態を設定する<br>
     *
     * @param detailOrderType 詳細取引決済状態<br>
     */
    public function setDetailOrderType($detailOrderType) {
        $this->detailOrderType = $detailOrderType;
    }

    /**
     * 決済センターとの取引IDを取得する<br>
     *
     * @return 決済センターとの取引ID<br>
     */
    public function getCenterTradeId() {
        return $this->centerTradeId;
    }

    /**
     * 決済センターとの取引IDを設定する<br>
     *
     * @param centerTradeId 決済センターとの取引ID<br>
     */
    public function setCenterTradeId($centerTradeId) {
        $this->centerTradeId = $centerTradeId;
    }

    /**
     * 支払日時を取得する<br>
     *
     * @return 支払日時<br>
     */
    public function getPaymentTime() {
        return $this->paymentTime;
    }

    /**
     * 支払日時を設定する<br>
     *
     * @param paymentTime 支払日時<br>
     */
    public function setPaymentTime($paymentTime) {
        $this->paymentTime = $paymentTime;
    }

    /**
     * 清算日時を取得する<br>
     *
     * @return 清算日時<br>
     */
    public function getSettlementTime() {
        return $this->settlementTime;
    }

    /**
     * 清算日時を設定する<br>
     *
     * @param settlementTime 清算日時<br>
     */
    public function setSettlementTime($settlementTime) {
        $this->settlementTime = $settlementTime;
    }

    /**
     * 決済種別を取得する<br>
     *
     * @return 決済種別<br>
     */
    public function getPayType() {
        return $this->payType;
    }

    /**
     * 決済種別を設定する<br>
     *
     * @param payType 決済種別<br>
     */
    public function setPayType($payType) {
        $this->payType = $payType;
    }

}
?>
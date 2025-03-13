<?php
/**
 * 決済サービスタイプ：Alipay、コマンド名：与信の要求Dtoクラス<br>
 *
 * @author Veritrans, Inc.
 *
 */
class AlipayAuthorizeRequestDto extends AbstractPaymentRequestDto
{

    /**
     * 決済サービスタイプ<br>
     * 半角英数字<br>
     * 必須項目、固定値<br>
     */
    private $SERVICE_TYPE = "alipay";

    /**
     * 決済サービスコマンド<br>
     * 半角英数字<br>
     * 必須項目、固定値<br>
     */
    private $SERVICE_COMMAND = "Authorize";

    /**
     * 取引ID<br>
     * 半角英数字<br/>
     * 最大桁数：100<br/>
     * - マーチャント側で取引を一意に表す注文管理IDを指定します。<br/>
     * - 申込処理ごとに一意である必要があります。<br/>
     * - 半角英数字、“-”（ハイフン）、“_”（アンダースコア）も使用可能です。<br/>
     */
    private $orderId;

    /**
     * 決済金額<br>
     * 半角数字<br/>
     * 最大桁数：7<br/>
     * 決済金額を日本円と中国元で指定します。<br/>
     * - 日本円(JPY)：1 - 9999999<br/>
     * - 中国元(CNY)：100 - 5000000<br/>
     *                      取引金額を100倍した値を指定します。（amount=決済金額×100）<br/>
     *                      上限金額は中国元換算で50,000元。<br/>
     * （例　<br/>
     * 日本円：1円の場合は1を指定します。<br/>
     * 中国元：1.99元の場合は199を指定します。）<br/>
     */
    private $amount;

    /**
     * 通貨<br>
     * 英字<br/>
     * 最大桁数：10<br/>
     * 決済通貨を指定します。<br/>
     * "JPY"：日本円<br/>
     * "CNY"：中国元（オンライン決済のみ）<br/>
     */
    private $currency;

    /**
     * 決済完了時URL<br>
     * URL(半角)<br/>
     * 最大桁数：256<br/>
     * 決済完了後に、店舗側サイトに画面遷移を戻すためのURLを指定します。<br/>
     * - 半角256文字以内<br/>
     * <br/>
     * payType=0（オンライン決済）：指定必須<br/>
     * payType=1（バーコード決済（店舗スキャン型））：指定できません<br/>
     * payType=2（バーコード決済（消費者スキャン型））：指定できません<br/>
     */
    private $successUrl;

    /**
     * 決済エラー時URL<br>
     * URL(半角)<br/>
     * 最大桁数：256<br/>
     * 決済エラー時に、店舗側サイトに画面遷移を戻すためのURLを指定します。<br/>
     * - 半角256文字以内<br/>
     * <br/>
     * payType=0（オンライン決済）：指定必須<br/>
     * payType=1（バーコード決済（店舗スキャン型））：指定できません<br/>
     * payType=2（バーコード決済（消費者スキャン型））：指定できません<br/>
     */
    private $errorUrl;

    /**
     * 商品名<br>
     * 文字列<br/>
     * 最大桁数：100<br/>
     * 商品名<br/>
     * - 文字列100桁以内<br/>
     * <br/>
     * payType=0（オンライン決済）：指定必須<br/>
     * payType=1（バーコード決済（店舗スキャン型））：指定必須<br/>
     * payType=2（バーコード決済（消費者スキャン型））：指定必須<br/>
     */
    private $commodityName;

    /**
     * 商品詳細<br>
     * 文字列<br/>
     * 最大桁数：200<br/>
     * 商品詳細<br/>
     * - 文字列200桁以内<br/>
     * <br/>
     * payType=0（オンライン決済）：指定任意 <br/>
     * payType=1（バーコード決済（店舗スキャン型））：指定できません<br/>
     * payType=2（バーコード決済（消費者スキャン型））：指定任意（商品詳細を指定する場合は商品IDの指定が必須）<br/>
     */
    private $commodityDescription;

    /**
     * 商品ID<br>
     * 半角英数字<br/>
     * 最大桁数：64<br/>
     * マーチャントシステム内で商品やサービスを特定するID<br/>
     * <br/>
     * payType=0（オンライン決済）：指定できません<br/>
     * payType=1（バーコード決済（店舗スキャン型））：指定できません<br/>
     * payType=2（バーコード決済（消費者スキャン型））：指定任意<br/>
     */
    private $commodityId;

    /**
     * 与信同時売上フラグ<br>
     * 英字（boolean）<br/>
     * - true : 与信同時売上（設定可能な値は"true"のみです。）<br/>
     * ※ 未指定の場合は、true:与信同時売上。<br/>
     */
    private $withCapture = "true";

    /**
     * 決済種別<br>
     * 半角数字<br/>
     * 最大桁数：1<br/>
     * 決済種別を指定します。<br/>
     * "0"：オンライン決済<br/>
     * "1"：バーコード決済（店舗スキャン型）<br/>
     * "2"：バーコード決済（消費者スキャン型）<br/>
     * ※未指定の場合は、0:オンライン決済。<br/>
     */
    private $payType;

    /**
     * ユーザ識別コード<br>
     * 半角英数字<br/>
     * 最大桁数：32<br/>
     * 消費者をAlipayユーザとして識別するためのコードを指定します。<br/>
     * <br/>
     * payType=0（オンライン決済）：指定できません<br/>
     * payType=1（バーコード決済（店舗スキャン型））：指定必須<br/>
     * payType=2（バーコード決済（消費者スキャン型））：指定できません<br/>
     */
    private $identityCode;


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
     * 通貨を取得する<br>
     * @return 通貨<br>
     */
    public function getCurrency() {
        return $this->currency;
    }

    /**
     * 通貨を設定する<br>
     * @param currency 通貨<br>
     */
    public function setCurrency($currency) {
        $this->currency = $currency;
    }

    /**
     * 決済完了時URLを取得する<br>
     * @return 決済完了時URL<br>
     */
    public function getSuccessUrl() {
        return $this->successUrl;
    }

    /**
     * 決済完了時URLを設定する<br>
     * @param successUrl 決済完了時URL<br>
     */
    public function setSuccessUrl($successUrl) {
        $this->successUrl = $successUrl;
    }

    /**
     * 決済エラー時URLを取得する<br>
     * @return 決済エラー時URL<br>
     */
    public function getErrorUrl() {
        return $this->errorUrl;
    }

    /**
     * 決済エラー時URLを設定する<br>
     * @param errorUrl 決済エラー時URL<br>
     */
    public function setErrorUrl($errorUrl) {
        $this->errorUrl = $errorUrl;
    }

    /**
     * 商品名を取得する<br>
     * @return 商品名<br>
     */
    public function getCommodityName() {
        return $this->commodityName;
    }

    /**
     * 商品名を設定する<br>
     * @param commodityName 商品名<br>
     */
    public function setCommodityName($commodityName) {
        $this->commodityName = $commodityName;
    }

    /**
     * 商品詳細を取得する<br>
     * @return 商品詳細<br>
     */
    public function getCommodityDescription() {
        return $this->commodityDescription;
    }

    /**
     * 商品詳細を設定する<br>
     * @param commodityDescription 商品詳細<br>
     */
    public function setCommodityDescription($commodityDescription) {
        $this->commodityDescription = $commodityDescription;
    }

    /**
     * 商品IDを取得する<br>
     * @return 商品ID<br>
     */
    public function getCommodityId() {
        return $this->commodityId;
    }

    /**
     * 商品IDを設定する<br>
     * @param commodityId 商品ID<br>
     */
    public function setCommodityId($commodityId) {
        $this->commodityId = $commodityId;
    }

    /**
     * 与信同時売上フラグを取得する<br>
     * @return 与信同時売上フラグ<br>
     */
    public function getWithCapture() {
        return $this->withCapture;
    }

    /**
     * 与信同時売上フラグを設定する<br>
     * @param withCapture 与信同時売上フラグ<br>
     */
    public function setWithCapture($withCapture) {
        $this->withCapture = $withCapture;
    }

    /**
     * 決済種別を取得する<br>
     * @return 決済種別<br>
     */
    public function getPayType() {
        return $this->payType;
    }

    /**
     * 決済種別を設定する<br>
     * @param payType 決済種別<br>
     */
    public function setPayType($payType) {
        $this->payType = $payType;
    }

    /**
     * ユーザ識別コードを取得する<br>
     * @return ユーザ識別コード<br>
     */
    public function getIdentityCode() {
        return $this->identityCode;
    }

    /**
     * ユーザ識別コードを設定する<br>
     * @param identityCode ユーザ識別コード<br>
     */
    public function setIdentityCode($identityCode) {
        $this->identityCode = $identityCode;
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
<?php
/**
 * 決済サービスタイプ：カード、コマンド名：再申込の要求Dtoクラス
 *
 * @author Veritrans, Inc.
 *
 */
class CardReAuthorizeRequestDto extends AbstractPaymentCreditRequestDto
{

    /**
     * 決済サービスタイプ<br>
     * 半角英数字<br>
     * 必須項目、固定値<br>
     */
    private $SERVICE_TYPE = "card";

    /**
     * 決済サービスコマンド<br>
     * 半角英数字<br>
     * 必須項目、固定値<br>
     */
    private $SERVICE_COMMAND = "ReAuthorize";

    /**
     * 取引ID<br>
     * 半角英数字<br>
     * 100 文字以内<br>
     * マーチャント側で取引を一意に表す注文管理IDを指定します。<br>
     * 申込処理ごとに一意である必要があります。<br>
     * 半角英数字、“-”（ハイフン）、“_”（アンダースコア）も使用可能です。<br>
     */
    private $orderId;

    /**
     * 元取引ID<br>
     * 半角英数字<br>
     * 100 文字以内<br>
     * マーチャントで過去の取引を表す注文管理IDを指定します。<br>
     * 半角英数字、“-”（ハイフン）、“_”（アンダースコア）も使用可能です。<br>
     */
    private $originalOrderId;

    /**
     * 決済金額<br>
     * 半角数字<br>
     * 8 桁以内<br>
     * 決済金額を指定します。<br>
     * 1 以上かつ 99999999 以下である必要があります。<br>
     */
    private $amount;

    /**
     * カード番号<br>
     * 半角英数字、ハイフン、ブランク、ピリオド<br>
     * 26 文字以内<br>
     * クレジットカード番号を指定します。<br>
     * 例） クレジットカード番号は19桁まで処理が可能。<br>
     * （ハイフンを含んでも含まなくても同様に処理が可能）<br>
     * 戻り値としては､上2桁/下4桁の計6桁が返ります。<br>
     * ※ eLIOカードは、指定できません<br>
     */
    private $cardNumber;

    /**
     * カード有効期限<br>
     * 半角数字、スラッシュ<br>
     * 5 文字固定<br>
     * クレジットカードの有効期限を指定します。<br>
     * MM/YY （月 + "/" + 年）の形式<br>
     * 例） "11/09"<br>
     */
    private $cardExpire;

    /**
     * カードオプションタイプ<br>
     * 半角英数字<br>
     * 10 文字以内<br>
     * カードオプションタイプを指定します。<br>
     * "mpi"： <br>
     * "house"： <br>
     * "elio"： 指定できません<br>
     * ※ 指定が無い場合は、デフォルトのカードオプション<br>
     * カード番号(No3)が設定されていない場合は、元取引のカードオプションタイプとなります。<br>
     * カードオプション毎の必須項目については<br>
     * 「（別紙）パラメータ組み合わせ」を参照ください。<br>
     */
    private $cardOptionType;

    /**
     * カード接続センター<br>
     * 半角英数字<br>
     * 5 文字以内<br>
     * カード接続センターを指定します。（任意指定）<br>
     * "sln"： Sln接続"<br>
     * "jcn""： Jcn接続<br>
     * ※ 指定が無い場合は、デフォルトの接続センターを検証<br>
     */
    private $cardCenter;

    /**
     * 仕向け先コード<br>
     * 半角数字<br>
     * 2 桁固定<br>
     * 仕向け先カード会社コードを指定します。<br>
     * （店舗が加盟店契約をしているカード会社）<br>
     * ※ 最終的に決済を行うカード発行会社ではなく、決済要求電文が最初に仕向けられる加盟店管理会社となります。<br>
     * 01 シティカードジャパン株式会社（ダイナースカード）<br>
     * 02 株式会社 ジェーシービー <br>
     * 03 三菱UFJニコス株式会社（旧DCカード）<br>
     * 04 三井住友カード株式会社（りそなカード株式会社などVISAジャパングループ）<br>
     * 05 三菱UFJニコス株式会社（旧UFJカード）<br>
     * 06 ユーシーカード株式会社<br>
     * 07 アメリカン・エキスプレス・インターナショナル<br>
     * 08 株式会社 ジャックス  <br>
     * 09 三菱UFJニコス株式会社（旧日本信販）<br>
     * 10 株式会社 オリエントコーポレーション<br>
     * 11 株式会社 セントラルファイナンス<br>
     * 12 株式会社 アプラス<br>
     * 13 株式会社 ライフ<br>
     * 14 楽天KC株式会社<br>
     * 17 三菱UFJニコス株式会社（旧協同クレジット）<br>
     * 20 GEコンシューマー・ファイナンス株式会社（ジーシーカード）<br>
     * 21 株式会社 クレディセゾン<br>
     * 22 ポケットカード 株式会社<br>
     * 23 株式会社オーエムシーカード<br>
     * 24 イオンクレジットサービス株式会社<br>
     * 28 株式会社 バンクカードサービス<br>
     * 31 トヨタファイナンス 株式会社<br>
     * 32 株式会社 JALカード<br>
     * 36 株式会社クオーク<br>
     * 37 GEコンシューマー・ファイナンス株式会社（GEカード）<br>
     * 38 東急カード株式会社（TOPカード）<br>
     * 40 （株）UCS<br>
     * 47 （株）ほくせん<br>
     * 48 （株）ソニーファイナンスインターナショナル<br>
     * 49 ヤフー（株）<br>
     * 50 （株）ゆめカード<br>
     * 51 （株）オークス<br>
     * 52 東日本旅客鉄道（株）（ビューカード）<br>
     */
    private $acquirerCode;

    /**
     * JPO<br>
     * 半角英数字<br>
     * 138 文字以内<br>
     * JPOを指定します。（任意指定）<br>
     * "10"<br>
     * "21"+"支払詳細"<br>
     * "22"+"支払詳細"<br>
     * "23"+"支払詳細"<br>
     * "24"+"支払詳細"<br>
     * "25"+"支払詳細"<br>
     * "31"+"支払詳細"<br>
     * "32"+"支払詳細"<br>
     * "33"+"支払詳細"<br>
     * "34"+"支払詳細"<br>
     * "61"+"支払詳細"<br>
     * "62"+"支払詳細"<br>
     * "63"+"支払詳細"<br>
     * "69"+"支払詳細"<br>
     * ※ 指定が無い場合は、デフォルトのJPO（一括払い：パターン"10"）<br>
     * 支払詳細については「（別紙）支払詳細」を参照ください。<br>
     */
    private $jpo;

    /**
     * 売上フラグ<br>
     * 半角英数字<br>
     * 5 文字以内<br>
     * 売上フラグを指定します。（任意指定）<br>
     * "true"： 与信・売上<br>
     * "false"： 与信のみ<br>
     * ※ 指定が無い場合は、false<br>
     */
    private $withCapture;

    /**
     * 売上日<br>
     * 半角数字<br>
     * 8 桁固定<br>
     * 売上日を指定します。（任意指定）<br>
     * YYYYMMDD の形式<br>
     * 例） "20090905"<br>
     * 売上フラグ（No.11)がfalseの場合は利用できません。<br>
     */
    private $salesDay;

    /**
     * 商品コード<br>
     * 半角数字<br>
     * 7 桁以内<br>
     * 商品コードを指定します。（任意指定）<br>
     * ※ 指定が無い場合は、デフォルトの商品コード<br>
     */
    private $itemCode;

    /**
     * 3Dメッセージバージョン<br>
     * 半角英数字<br>
     * 10 桁 or 0桁<br>
     * Message Version Numberを指定します。<br>
     * 例） “1.0.2”<br>
     */
    private $dddMessageVersion;

    /**
     * 3DトランザクションId<br>
     * 半角英数字、"+"、"-"、"="<br>
     * 28 文字 or 0文字<br>
     * Transaction Identifier(XID)を指定します。<br>
     * 20桁バイナリ値をBase64にて28桁英数字に変換した値を指定します。<br>
     */
    private $dddTransactionId;

    /**
     * 3Dトランザクションステータス<br>
     * 半角英数字<br>
     * 1 桁以内<br>
     * 3Dセキュアトランザクションステータスを指定します。<br>
     * "Y"：本人認証成功<br>
     * "N"：本人認証失敗（イシュアまたは会員が原因）<br>
     * "U"：本人認証失敗（上記以外が原因）<br>
     * "A"：Attempt（暫定的に本人認証成功）<br>
     * " ": スペース<br>
     * ""：値なし<br>
     */
    private $dddTransactionStatus;

    /**
     * 3DCAVVアルゴリズム<br>
     * 半角英数字<br>
     * 1 桁以内<br>
     * 3DセキュアCAVVアルゴリズムを指定します。<br>
     * "0"：HMAC<br>
     * "1"：CVV<br>
     * "2"：CVV with ATN<br>
     * "3"：SPA Algorithm<br>
     * " "：スペース<br>
     * ""：値なし<br>
     */
    private $dddCavvAlgorithm;

    /**
     * 3DCAVV<br>
     * 半角英数字<br>
     * 28 桁 or 0桁<br>
     * 3DセキュアCAVV を指定します。<br>
     */
    private $dddCavv;

    /**
     * 3DECI<br>
     * 半角数字<br>
     * 2 桁固定<br>
     * 3Dセキュア ECI を指定します。<br>
     * "01"：Attempt（Master Card）<br>
     * "02"：認証成功（Master Card）<br>
     * "05"：認証成功（VISA、JCB）<br>
     * "06"：Attempt（VISA、JCB）または未参加（Master Card、VISA、JCB）<br>
     * "07"：認証実行不能（Master Card、VISA、JCB）<br>
     */
    private $dddEci;

    /**
     * セキュリティコード<br>
     * 半角数字<br>
     * 4 桁以内<br>
     * セキュリティコードを指定します。<br>
     */
    private $securityCode;

    /**
     * 認証コード（eLIO）<br>
     * 半角数字<br>
     * 7 桁以内<br>
     * 使用できません。<br>
     */
    private $authFlag;

    /**
     * 誕生日<br>
     * 半角数字<br>
     * 4 桁以内<br>
     * SLN認証アシストサービス用パラメータ[ 誕生日 ]<br>
     * カード利用者が入力するカード保有者の生月日(MMDD形式）を設定します。<br>
     * カード接続センター(No.7)がjcnと設定しているときは利用できません。<br>
     */
    private $birthday;

    /**
     * 電話番号<br>
     * 半角数字<br>
     * 4 桁以内<br>
     * SLN認証アシストサービス用パラメータ[ 電話番号 ]<br>
     * カード利用者が入力するカード保有者の自宅電話番号下4桁を設定します。<br>
     * カード接続センター(No.7)がjcnと設定しているときは利用できません。<br>
     */
    private $tel;

    /**
     * 名前（名）カナ<br>
     * 半角カナ（ｱ～ﾝ）、半濁点<br>
     * 15バイト以内<br>
     * SLN認証アシストサービス用パラメータ[ 名前（名）カナ ] <br>
     * カード利用者が入力するカード保有者のカナ氏名（名）を設定します。<br>
     * カード接続センター(No.7)がjcnと設定しているときは利用できません。<br>
     */
    private $firstKanaName;

    /**
     * 名前（姓）カナ<br>
     * 半角カナ（ｱ～ﾝ）、半濁点<br>
     * 15バイト以内<br>
     * SLN認証アシストサービス用パラメータ[ 名前（姓）カナ ] <br>
     * カード利用者が入力するカード保有者のカナ氏名（姓）を設定します。<br>
     * カード接続センター(No.7)がjcnと設定しているときは利用できません。<br>
     */
    private $lastKanaName;

    /**
     * 通貨単位<br>
     * 英字3桁<br>
     */
    private $currencyUnit;

    /**
     * PIN<br>
     */
    private $pin;

    /**
     * 初回請求年月<br>
     * 半角数字<br>
     * 4 文字固定<br>
     * 初回請求年月を指定します。<br>
     * YYMM （年月）の形式<br>
     * 例） "1310"<br>
     */
    private $firstPayment;

    /**
     * ボーナス初回年月<br>
     * 半角数字<br>
     * 4 文字固定<br>
     * ボーナス初回年月を指定します。<br>
     * YYMM （年月）の形式<br>
     * 例） "1312"<br>
     */
    private $bonusFirstPayment;

    /**
     * 決済金額（多通貨）<br>
     * 半角数字＋小数点<br>
     * 半角数字は 8 桁以内<br>
     * 決済金額（多通貨）を指定します。<br>
     * 0 より大きくかつ 99999999 以下である必要があります。<br>
     */
    private $mcAmount;

    /**
     * POSデータコード<br>
     * 半角英数字<br>
     * 12 桁固定<br>
     * POSデータコードを指定します。<br>
     */
    private $posDataCode;

    /**
     * 端末識別番号<br>
     * 半角数字<br>
     * 13 桁固定<br>
     * 端末識別番号を指定します。<br>
     */
    private $terminalId;

    /**
     * ログ用文字列(マスク済み)<br>
     * 半角英数字<br>
     */
    private  $maskedLog;

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
     * @param  orderId 取引ID<br>
     */
    public function setOrderId($orderId) {
        $this->orderId = $orderId;
    }

    /**
     * 元取引IDを取得する<br>
     * @return 元取引ID<br>
     */
    public function getOriginalOrderId() {
        return $this->originalOrderId;
    }

    /**
     * 元取引IDを設定する<br>
     * @param  originalOrderId 元取引ID<br>
     */
    public function setOriginalOrderId($originalOrderId) {
        $this->originalOrderId = $originalOrderId;
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
     * @param  amount 決済金額<br>
     */
    public function setAmount($amount) {
        $this->amount = $amount;
    }

    /**
     * カード番号を取得する<br>
     * @return カード番号<br>
     */
    public function getCardNumber() {
        return $this->cardNumber;
    }

    /**
     * カード番号を設定する<br>
     * @param  cardNumber カード番号<br>
     */
    public function setCardNumber($cardNumber) {
        $this->cardNumber = $cardNumber;
    }

    /**
     * カード有効期限を取得する<br>
     * @return カード有効期限<br>
     */
    public function getCardExpire() {
        return $this->cardExpire;
    }

    /**
     * カード有効期限を設定する<br>
     * @param  cardExpire カード有効期限<br>
     */
    public function setCardExpire($cardExpire) {
        $this->cardExpire = $cardExpire;
    }

    /**
     * カードオプションタイプを取得する<br>
     * @return カードオプションタイプ<br>
     */
    public function getCardOptionType() {
        return $this->cardOptionType;
    }

    /**
     * カードオプションタイプを設定する<br>
     * @param  cardOptionType カードオプションタイプ<br>
     */
    public function setCardOptionType($cardOptionType) {
        $this->cardOptionType = $cardOptionType;
    }

    /**
     * カード接続センターを取得する<br>
     * @return カード接続センター<br>
     */
    public function getCardCenter() {
        return $this->cardCenter;
    }

    /**
     * カード接続センターを設定する<br>
     * @param  cardCenter カード接続センター<br>
     */
    public function setCardCenter($cardCenter) {
        $this->cardCenter = $cardCenter;
    }

    /**
     * 仕向け先コードを取得する<br>
     * @return 仕向け先コード<br>
     */
    public function getAcquirerCode() {
        return $this->acquirerCode;
    }

    /**
     * 仕向け先コードを設定する<br>
     * @param  acquirerCode 仕向け先コード<br>
     */
    public function setAcquirerCode($acquirerCode) {
        $this->acquirerCode = $acquirerCode;
    }

    /**
     * JPOを取得する<br>
     * @return JPO<br>
     */
    public function getJpo() {
        return $this->jpo;
    }

    /**
     * JPOを設定する<br>
     * @param  jpo JPO<br>
     */
    public function setJpo($jpo) {
        $this->jpo = $jpo;
    }

    /**
     * 売上フラグを取得する<br>
     * @return 売上フラグ<br>
     */
    public function getWithCapture() {
        return $this->withCapture;
    }

    /**
     * 売上フラグを設定する<br>
     * @param  withCapture 売上フラグ<br>
     */
    public function setWithCapture($withCapture) {
        $this->withCapture = $withCapture;
    }

    /**
     * 売上日を取得する<br>
     * @return 売上日<br>
     */
    public function getSalesDay() {
        return $this->salesDay;
    }

    /**
     * 売上日を設定する<br>
     * @param  salesDay 売上日<br>
     */
    public function setSalesDay($salesDay) {
        $this->salesDay = $salesDay;
    }

    /**
     * 商品コードを取得する<br>
     * @return 商品コード<br>
     */
    public function getItemCode() {
        return $this->itemCode;
    }

    /**
     * 商品コードを設定する<br>
     * @param  itemCode 商品コード<br>
     */
    public function setItemCode($itemCode) {
        $this->itemCode = $itemCode;
    }

    /**
     * 3Dメッセージバージョンを取得する<br>
     * @return 3Dメッセージバージョン<br>
     */
    public function getDddMessageVersion() {
        return $this->dddMessageVersion;
    }

    /**
     * 3Dメッセージバージョンを設定する<br>
     * @param  dddMessageVersion 3Dメッセージバージョン<br>
     */
    public function setDddMessageVersion($dddMessageVersion) {
        $this->dddMessageVersion = $dddMessageVersion;
    }

    /**
     * 3DトランザクションIdを取得する<br>
     * @return 3DトランザクションId<br>
     */
    public function getDddTransactionId() {
        return $this->dddTransactionId;
    }

    /**
     * 3DトランザクションIdを設定する<br>
     * @param  dddTransactionId 3DトランザクションId<br>
     */
    public function setDddTransactionId($dddTransactionId) {
        $this->dddTransactionId = $dddTransactionId;
    }

    /**
     * 3Dトランザクションステータスを取得する<br>
     * @return 3Dトランザクションステータス<br>
     */
    public function getDddTransactionStatus() {
        return $this->dddTransactionStatus;
    }

    /**
     * 3Dトランザクションステータスを設定する<br>
     * @param  dddTransactionStatus 3Dトランザクションステータス<br>
     */
    public function setDddTransactionStatus($dddTransactionStatus) {
        $this->dddTransactionStatus = $dddTransactionStatus;
    }

    /**
     * 3DCAVVアルゴリズムを取得する<br>
     * @return 3DCAVVアルゴリズム<br>
     */
    public function getDddCavvAlgorithm() {
        return $this->dddCavvAlgorithm;
    }

    /**
     * 3DCAVVアルゴリズムを設定する<br>
     * @param  dddCavvAlgorithm 3DCAVVアルゴリズム<br>
     */
    public function setDddCavvAlgorithm($dddCavvAlgorithm) {
        $this->dddCavvAlgorithm = $dddCavvAlgorithm;
    }

    /**
     * 3DCAVVを取得する<br>
     * @return 3DCAVV<br>
     */
    public function getDddCavv() {
        return $this->dddCavv;
    }

    /**
     * 3DCAVVを設定する<br>
     * @param  dddCavv 3DCAVV<br>
     */
    public function setDddCavv($dddCavv) {
        $this->dddCavv = $dddCavv;
    }

    /**
     * 3DECIを取得する<br>
     * @return 3DECI<br>
     */
    public function getDddEci() {
        return $this->dddEci;
    }

    /**
     * 3DECIを設定する<br>
     * @param  dddEci 3DECI<br>
     */
    public function setDddEci($dddEci) {
        $this->dddEci = $dddEci;
    }

    /**
     * セキュリティコードを取得する<br>
     * @return セキュリティコード<br>
     */
    public function getSecurityCode() {
        return $this->securityCode;
    }

    /**
     * セキュリティコードを設定する<br>
     * @param  securityCode セキュリティコード<br>
     */
    public function setSecurityCode($securityCode) {
        $this->securityCode = $securityCode;
    }

    /**
     * 認証コード（eLIO）を取得する<br>
     * @return 認証コード（eLIO）<br>
     */
    public function getAuthFlag() {
        return $this->authFlag;
    }

    /**
     * 認証コード（eLIO）を設定する<br>
     * @param  authFlag 認証コード（eLIO）<br>
     */
    public function setAuthFlag($authFlag) {
        $this->authFlag = $authFlag;
    }

    /**
     * 誕生日を取得する<br>
     * @return 誕生日<br>
     */
    public function getBirthday() {
        return $this->birthday;
    }

    /**
     * 誕生日を設定する<br>
     * @param  birthday 誕生日<br>
     */
    public function setBirthday($birthday) {
        $this->birthday = $birthday;
    }

    /**
     * 電話番号を取得する<br>
     * @return 電話番号<br>
     */
    public function getTel() {
        return $this->tel;
    }

    /**
     * 電話番号を設定する<br>
     * @param  tel 電話番号<br>
     */
    public function setTel($tel) {
        $this->tel = $tel;
    }

    /**
     * 名前（名）カナを取得する<br>
     * @return 名前（名）カナ<br>
     */
    public function getFirstKanaName() {
        return $this->firstKanaName;
    }

    /**
     * 名前（名）カナを設定する<br>
     * @param  firstKanaName 名前（名）カナ<br>
     */
    public function setFirstKanaName($firstKanaName) {
        $this->firstKanaName = $firstKanaName;
    }

    /**
     * 名前（姓）カナを取得する<br>
     * @return 名前（姓）カナ<br>
     */
    public function getLastKanaName() {
        return $this->lastKanaName;
    }

    /**
     * 名前（姓）カナを設定する<br>
     * @param  lastKanaName 名前（姓）カナ<br>
     */
    public function setLastKanaName($lastKanaName) {
        $this->lastKanaName = $lastKanaName;
    }

    /**
     * 通貨単位を取得する<br>
     * @return 通貨単位<br>
     */
    public function getCurrencyUnit() {
        return $this->currencyUnit;
    }

    /**
     * 通貨単位を設定する<br>
     * @param  currencyUnit 通貨単位<br>
     */
    public function setCurrencyUnit($currencyUnit) {
        $this->currencyUnit = $currencyUnit;
    }

    /**
     * PINを取得する<br>
     * @return PIN<br>
     */
    public function getPin() {
        return $this->pin;
    }

    /**
     * PINを設定する<br>
     * @param  pin PIN<br>
     */
    public function setPin($pin) {
        $this->pin = $pin;
    }

    /**
     * 初回請求年月を取得する<br>
     * @return 初回請求年月<br>
     */
    public function getFirstPayment() {
        return $this->firstPayment;
    }

    /**
     * 初回請求年月を設定する<br>
     * @param  firstPayment 初回請求年月<br>
     */
    public function setFirstPayment($firstPayment) {
        $this->firstPayment = $firstPayment;
    }

    /**
     * ボーナス初回年月を取得する<br>
     * @return ボーナス初回年月<br>
     */
    public function getBonusFirstPayment() {
        return $this->bonusFirstPayment;
    }

    /**
     * ボーナス初回年月を設定する<br>
     * @param  bonusFirstPayment ボーナス初回年月<br>
     */
    public function setBonusFirstPayment($bonusFirstPayment) {
        $this->bonusFirstPayment = $bonusFirstPayment;
    }

    /**
     * 決済金額（多通貨）を取得する<br>
     * @return 決済金額（多通貨）<br>
     */
    public function getMcAmount() {
        return $this->mcAmount;
    }

    /**
     * 決済金額（多通貨）を設定する<br>
     * @param mcAmount 決済金額（多通貨）<br>
     */
    public function setMcAmount($mcAmount) {
        $this->mcAmount = $mcAmount;
    }

    /**
     * POSデータコードを取得する<br>
     * @return POSデータコード<br>
     */
    public function getPosDataCode() {
        return $this->posDataCode;
    }

    /**
     * POSデータコードを設定する<br>
     * @param posDataCode POSデータコード<br>
     */
    public function setPosDataCode($posDataCode) {
        $this->posDataCode = $posDataCode;
    }

    /**
     * 端末識別番号を取得する<br>
     * @return 端末識別番号<br>
     */
    public function getTerminalId() {
        return $this->terminalId;
    }

    /**
     * 端末識別番号を設定する<br>
     * @param terminalId 端末識別番号<br>
     */
    public function setTerminalId($terminalId) {
        $this->terminalId = $terminalId;
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


    /**
     * 拡張パラメータ<br>
     * 並列処理用の拡張パラメータを保持する。
     */
    private $optionParams;

    /**
     * 拡張パラメータリストを取得する<br>
     * @return 拡張パラメータリスト<br>
     */
    public function getOptionParams()
    {
        return $this->optionParams;
    }

    /**
     * 拡張パラメータリストを設定する<br>
     * @param  optionParams 拡張パラメータリスト<br>
     */
    public function setOptionParams($optionParams)
    {
        $this->optionParams = $optionParams;
    }

}
?>

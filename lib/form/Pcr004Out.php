<?php
/**
 * @package    ClassDefFile
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useComponents(array('String'));
/**#@-*/

/**
 * 確認画面の出力フォームです。
 * TODO TwigやSmartyなどのテンプレートエンジンの導入を検討する
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Pcr004Out {

    /**
     * お支払店コード選択値
     * @var string
     */
    public $raw_convenience_store_cd_sel = '';

    /**
     * メールアドレス
     * @var string
     */
    public $raw_mail = '';

    /**
     * 決済データ送信結果
     * @var string
     */
    public $raw_merchant_result = '';

    /**
     * お支払方法コード選択値
     * @var string
     */
    public $raw_payment_method_cd_sel = '';

    /**
     * コンビニ決済 払込票URL
     * @var string
     */
    public $raw_payment_url = '';

    /**
     * コンビニ決済 受付番号
     * @var string
     */
    public $raw_receipt_cd = '';

    /**
     * エンティティ化されたお支払店コード選択値を返します。
     * @return string エンティティ化されたお支払店コード選択値
     */
    public function convenience_store_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_convenience_store_cd_sel);
    }

    /**
     * エンティティ化されたメールアドレスを返します。
     * @return string エンティティ化されたメールアドレス
     */
    public function mail() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_mail);
    }

    /**
     * エンティティ化された決済データ送信結果を返します。
     * @return string エンティティ化された決済データ送信結果
     */
    public function merchant_result() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_merchant_result);
    }

    /**
     * エンティティ化されたお支払方法コード選択値を返します。
     * @return string エンティティ化されたお支払方法コード選択値
     */
    public function payment_method_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_payment_method_cd_sel);
    }

    /**
     * エンティティ化されたコンビニ決済 払込票URLを返します。
     * @return string エンティティ化されたコンビニ決済 払込票URL
     */
    public function payment_url() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_payment_url);
    }

    /**
     * エンティティ化されたコンビニ決済 受付番号を返します。
     * @return string エンティティ化されたコンビニ決済 受付番号
     */
    public function receipt_cd() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_receipt_cd);
    }
}
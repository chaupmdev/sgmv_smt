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
class Sgmov_Form_Pcr002Out {

    /**
     * 送料
     * @var string
     */
    public $raw_delivery_charge = '';

    /**
     * リピータ割引
     * @var string
     */
    public $raw_repeater_discount = '';

    /**
     * クレジットカード番号
     * @var string
     */
    public $raw_card_number = '';

    /**
     * 有効期限 月コード選択値
     * @var string
     */
    public $raw_card_expire_month_cd_sel = '';

    /**
     * 有効期限 月コードリスト
     * @var array
     */
    public $raw_card_expire_month_cds = array();

    /**
     * 有効期限 月コードラベルリスト
     * @var array
     */
    public $raw_card_expire_month_lbls = array();

    /**
     * 有効期限 年コード選択値
     * @var string
     */
    public $raw_card_expire_year_cd_sel = '';

    /**
     * 有効期限 年コードリスト
     * @var array
     */
    public $raw_card_expire_year_cds = array();

    /**
     * 有効期限 年コードラベルリスト
     * @var array
     */
    public $raw_card_expire_year_lbls = array();

    /**
     * セキュリティコード
     * @var string
     */
    public $raw_security_cd = '';

    /**
     * エンティティ化された送料を返します。
     * @return string エンティティ化された送料
     */
    public function delivery_charge() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_delivery_charge);
    }

    /**
     * エンティティ化されたリピータ割引を返します。
     * @return string エンティティ化されたリピータ割引
     */
    public function repeater_discount() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_repeater_discount);
    }

    /**
     * エンティティ化されたクレジットカード番号を返します。
     * @return string エンティティ化されたクレジットカード番号
     */
    public function card_number() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_card_number);
    }

    /**
     * エンティティ化された有効期限 月コード選択値を返します。
     * @return string エンティティ化された有効期限 月コード選択値
     */
    public function card_expire_month_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_card_expire_month_cd_sel);
    }

    /**
     * エンティティ化された有効期限 月コードリストを返します。
     * @return array エンティティ化された有効期限 月コードリスト
     */
    public function card_expire_month_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_card_expire_month_cds);
    }

    /**
     * エンティティ化された有効期限 月ラベルリストを返します。
     * @return array エンティティ化された有効期限 月ラベルリスト
     */
    public function card_expire_month_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_card_expire_month_lbls);
    }

    /**
     * エンティティ化された有効期限 年コード選択値を返します。
     * @return string エンティティ化された有効期限 年コード選択値
     */
    public function card_expire_year_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_card_expire_year_cd_sel);
    }

    /**
     * エンティティ化された有効期限 年コードリストを返します。
     * @return array エンティティ化された有効期限 年コードリスト
     */
    public function card_expire_year_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_card_expire_year_cds);
    }

    /**
     * エンティティ化された有効期限 年ラベルリストを返します。
     * @return array エンティティ化された有効期限 年ラベルリスト
     */
    public function card_expire_year_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_card_expire_year_lbls);
    }

    /**
     * エンティティ化されたセキュリティコードを返します。
     * @return string エンティティ化されたセキュリティコード
     */
    public function security_cd() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_security_cd);
    }
}
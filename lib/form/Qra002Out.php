<?php

/**
 * @package    ClassDefFile
 * @author     自動生成
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useComponents(array('String'));
Sgmov_Lib::useForms(array('Qra001Out'));
/**#@-*/

/**
 * 確認画面の出力フォームです。
 * TODO TwigやSmartyなどのテンプレートエンジンの導入を検討する
 * @package    Form
 * @author     自動生成
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Qra002Out extends Sgmov_Form_Qra001Out
{

    /**
     * 送料
     * @var string
     */
    public $raw_delivery_charge = '';

    /**
     * 送料(無料対応用)
     * @var string
     */
    public $raw_delivery_charge_free = '';

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
     * 受付番号
     * @var string
     */
    public $raw_UKETSUKE_NO = '';

    /**
     * 問合せ番号
     * @var string
     */
    public $raw_toiawase_no = '';
    /**
     * 4桁区切り番号
     * @var string
     */
    public $raw_toiban = '';
    /**
     * ARK受付番号
     * @var string
     */
    public $raw_ark_uketsuke_no = '';
    /**
     * 決済明細ID
     * @var string
     */
    public $raw_kessai_meisai_id = '';
    /**
     * 売上金額
     * @var string
     */
    public $raw_uriage_kingaku = '';
    /**
     * システム区分
     * @var string
     */
    public $raw_system_kbn = '';
    /**
     * システム区分
     * @var string
     */
    public $raw_cd = '';
    /**
     * エンティティ化された送料を返します。
     * @return string エンティティ化された送料
     */
    public function delivery_charge()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_delivery_charge);
    }

    /**
     * エンティティ化された送料を返します。
     * @return string エンティティ化された送料
     */
    public function delivery_charge_free()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_delivery_charge_free);
    }

    /**
     * エンティティ化されたリピータ割引を返します。
     * @return string エンティティ化されたリピータ割引
     */
    public function repeater_discount()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_repeater_discount);
    }

    /**
     * エンティティ化されたクレジットカード番号を返します。
     * @return string エンティティ化されたクレジットカード番号
     */
    public function card_number()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_card_number);
    }

    /**
     * エンティティ化された有効期限 月コード選択値を返します。
     * @return string エンティティ化された有効期限 月コード選択値
     */
    public function card_expire_month_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_card_expire_month_cd_sel);
    }

    /**
     * エンティティ化された有効期限 月コードリストを返します。
     * @return array エンティティ化された有効期限 月コードリスト
     */
    public function card_expire_month_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_card_expire_month_cds);
    }

    /**
     * エンティティ化された有効期限 月ラベルリストを返します。
     * @return array エンティティ化された有効期限 月ラベルリスト
     */
    public function card_expire_month_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_card_expire_month_lbls);
    }

    /**
     * エンティティ化された有効期限 年コード選択値を返します。
     * @return string エンティティ化された有効期限 年コード選択値
     */
    public function card_expire_year_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_card_expire_year_cd_sel);
    }

    /**
     * エンティティ化された有効期限 年コードリストを返します。
     * @return array エンティティ化された有効期限 年コードリスト
     */
    public function card_expire_year_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_card_expire_year_cds);
    }

    /**
     * エンティティ化された有効期限 年ラベルリストを返します。
     * @return array エンティティ化された有効期限 年ラベルリスト
     */
    public function card_expire_year_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_card_expire_year_lbls);
    }

    /**
     * エンティティ化されたセキュリティコードを返します。
     * @return string エンティティ化されたセキュリティコード
     */
    public function security_cd()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_security_cd);
    }

    /**
     * エンティティ化されたセキュリティコードを返します。
     * @return string エンティティ化されたセキュリティコード
     */
    public function uketsuke_no()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_uketsuke_no);
    }
    public function toiawase_no()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_toiawase_no);
    }
    public function toiban()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_toiban);
    }
    public function ark_uketsuke_no()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_ark_uketsuke_no);
    }
    public function kessai_id()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_kessai_meisai_id);
    }
    public function uriage_kingaku()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_uriage_kingaku);
    }
    public function system_kbn()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_system_kbn);
    }
    public function cd()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cd);
    }
}

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
class Sgmov_Form_Ptu003Out {

    /**
     * 送料
     * @var string
     */
    public $raw_delivery_charge = '';

    /**
     * お名前 姓
     * @var string
     */
    public $raw_surname = '';

    /**
     * お名前 名
     * @var string
     */
    public $raw_forename = '';

    /**
     * 電話番号
     * @var string
     */
    public $raw_tel = '';

    /**
    * 郵便番号
    * @var string
    */
    public $raw_fax = '';

    /**
     * メールアドレス
     * @var string
     */
    public $raw_mail = '';

    /**
     * 郵便番号
     * @var string
     */
    public $raw_zip = '';

    /**
     * 都道府県
     * @var string
     */
    public $raw_pref = '';

    /**
     * 市区町村
     * @var string
     */
    public $raw_address = '';

    /**
     * 番地・建物名
     * @var string
     */
    public $raw_building = '';


    /**
    * お名前 姓
    * @var string
    */
    public $raw_surname_hksaki = '';

    /**
     * お名前 名
     * @var string
     */
    public $raw_forename_hksaki = '';

    /**
     * 郵便番号
     * @var string
     */
    public $raw_zip_hksaki = '';

    /**
     * 都道府県コード選択値
     * @var string
     */
    public $raw_pref_cd_sel_hksaki = '';

    /**
     * 市区町村
     * @var string
     */
    public $raw_address_hksaki = '';

    /**
     * 番地・建物名
     * @var string
     */
    public $raw_building_hksaki = '';

    /**
     * 電話番号
     * @var string
     */
    public $raw_tel_hksaki = '';

    /**
     * 不在時連絡先
     * @var string
     */
    public $raw_tel_fuzai_hksaki = '';

    /**
    * お引取り予定日コード選択値
    * @var string
    */
    public $raw_hikitori_yotehiji_date_cd_sel = '';

    /**
    * お引取り予定コード選択値
    * @var string
    */
    public $raw_hikitori_yotehiji_time_cd_sel = '';

    /**
    * お引取り予定月コード選択値
    * @var string
    */
    public $raw_hikoshi_yotehiji_date_cd_sel = '';

    /**
    * お引取り予定コード選択値
    * @var string
    */
    public $raw_hikoshi_yotehiji_time_cd_sel = '';

    /**
    * カーゴ台数
    * @var string
    */
    public $raw_cago_daisu = '';

    /**
    * 単品輸送品目
    * @var string
    */
    public $raw_tanhin_cd_sel = '';

    /**
    * エンティティ化された単品輸送品目選択値を返します。
    * @return string エンティティ化された都道府県コード選択値
    */
    public function tanhin_cd_sel() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_tanhin_cd_sel);
    }

    /**
    * 搬出OPT
    * @var string
    */
    public $raw_hanshutsu_opt = '';

    /**
    * 搬入OPT
    * @var string
    */
    public $raw_hannyu_opt = '';

    public function hanshutsu_opt() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_hanshutsu_opt);
    }
    public function hannyu_opt() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_hannyu_opt);
    }

    public $raw_binshu_cd = '';

    public function binshu_cd() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_binshu_cd);
    }

    /**
     * お支払方法コード選択値
     * @var string
     */
    public $raw_payment_method_cd_sel = '';

    /**
     * お支払方法
     * @var string
     */
    public $raw_payment_method = '';

    /**
     * お支払店
     * @var string
     */
    public $raw_convenience_store = '';

    /**
     * クレジットカード番号
     * @var string
     */
    public $raw_card_number = '';

    /**
     * 有効期限
     * @var string
     */
    public $raw_card_expire = '';

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
     * エンティティ化されたお名前 姓を返します。
     * @return string エンティティ化されたお名前 姓
     */
    public function surname() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_surname);
    }

    /**
     * エンティティ化されたお名前 名を返します。
     * @return string エンティティ化されたお名前 名
     */
    public function forename() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_forename);
    }

    /**
     * エンティティ化された電話番号1を返します。
     * @return string エンティティ化された電話番号
     */
    public function tel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel);
    }

    /**
    * エンティティ化された郵便番号1を返します。
    * @return string エンティティ化された郵便番号
    */
    public function fax() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_fax);
    }

    /**
     * エンティティ化されたメールアドレスを返します。
     * @return string エンティティ化されたメールアドレス
     */
    public function mail() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_mail);
    }

    /**
     * エンティティ化された郵便番号を返します。
     * @return string エンティティ化された郵便番号
     */
    public function zip() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_zip);
    }

    /**
     * エンティティ化された都道府県を返します。
     * @return string エンティティ化された都道府県
     */
    public function pref() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_pref);
    }

    /**
     * エンティティ化された市区町村を返します。
     * @return string エンティティ化された市区町村
     */
    public function address() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_address);
    }

    /**
     * エンティティ化された番地・建物名を返します。
     * @return string エンティティ化された番地・建物名
     */
    public function building() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_building);
    }

    // =====================================================================================================
    /**
    * エンティティ化されたお名前 姓を返します。
    * @return string エンティティ化されたお名前 姓
    */
    public function surname_hksaki() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_surname_hksaki);
    }

    /**
     * エンティティ化されたお名前 名を返します。
     * @return string エンティティ化されたお名前 名
     */
    public function forename_hksaki() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_forename_hksaki);
    }

    /**
     * エンティティ化された郵便番号を返します。
     * @return string エンティティ化された郵便番号
     */
    public function zip_hksaki() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_zip_hksaki);
    }

    /**
    * エンティティ化された都道府県コード選択値を返します。
    * @return string エンティティ化された都道府県コード選択値
    */
    public function pref_cd_sel_hksaki() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_pref_cd_sel_hksaki);
    }

    /**
     * エンティティ化された市区町村を返します。
     * @return string エンティティ化された市区町村
     */
    public function address_hksaki() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_address_hksaki);
    }

    /**
     * エンティティ化された番地・建物名を返します。
     * @return string エンティティ化された番地・建物名
     */
    public function building_hksaki() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_building_hksaki);
    }

    /**
     * エンティティ化された電話番号1を返します。
     * @return string エンティティ化された電話番号1
     */
    public function tel_hksaki() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_tel_hksaki);
    }

    /**
     * エンティティ化されたFAX番号1を返します。
     * @return string エンティティ化されたFAX番号1
     */
    public function tel_fuzai_hksaki() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_tel_fuzai_hksaki);
    }

    /**
     * エンティティ化されたFAX番号3を返します。
     * @return string エンティティ化されたFAX番号3
     */
    public function cago_daisu() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_cago_daisu);
    }

    /**
    * エンティティ化されたお引取り予定年コード選択値を返します。
    * @return string エンティティ化されたお引取り予定年コード選択値
    */
    public function hikitori_yotehiji_date_cd_sel() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_hikitori_yotehiji_date_cd_sel);
    }

    /**
    * エンティティ化されたお引取り予定年コード選択値を返します。
    * @return string エンティティ化されたお引取り予定年コード選択値
    */
    public function hikoshi_yotehiji_date_cd_sel() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_hikoshi_yotehiji_date_cd_sel);
    }

    /**
    * エンティティ化されたお引取り予定開始時刻コード選択値を返します。
    * @return string エンティティ化されたお引取り予定開始時刻コード選択値
    */
    public function hikitori_yotehiji_time_cd_sel() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_hikitori_yotehiji_time_cd_sel);
    }

    /**
    * エンティティ化されたお引取り予定開始時刻コード選択値を返します。
    * @return string エンティティ化されたお引取り予定開始時刻コード選択値
    */
    public function hikoshi_yotehiji_time_cd_sel() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_hikoshi_yotehiji_time_cd_sel);
    }




    // =====================================================================================================
    /**
     * エンティティ化されたお支払方法コード選択値を返します。
     * @return string エンティティ化されたお支払方法コード選択値
     */
    public function payment_method_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_payment_method_cd_sel);
    }

    /**
     * エンティティ化されたお支払方法を返します。
     * @return string エンティティ化されたお支払方法
     */
    public function payment_method() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_payment_method);
    }

    /**
     * エンティティ化されたお支払店を返します。
     * @return string エンティティ化されたお支払店
     */
    public function convenience_store() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_convenience_store);
    }

    /**
     * エンティティ化されたクレジットカード番号を返します。
     * @return string エンティティ化されたクレジットカード番号
     */
    public function card_number() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_card_number);
    }

    /**
     * エンティティ化された有効期限を返します。
     * @return string エンティティ化された有効期限
     */
    public function card_expire() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_card_expire);
    }

    /**
     * エンティティ化されたセキュリティコードを返します。
     * @return string エンティティ化されたセキュリティコード
     */
    public function security_cd() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_security_cd);
    }
}
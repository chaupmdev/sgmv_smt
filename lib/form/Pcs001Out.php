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
 * 法人設置輸送入力画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Pcs001Out
{
    /**
     * お問い合わせ種類コード選択値
     * @var string
     */
    public $raw_inquiry_type_cd_sel = '';

    /**
     * お問い合わせカテゴリーコード選択値
     * @var string
     */
    public $raw_inquiry_category_cd_sel = '';

    /**
     * お問い合わせ件名
     * @var string
     */
    public $raw_inquiry_title = '';

    /**
     * お問い合わせ内容
     * @var string
     */
    public $raw_inquiry_content = '';

    /**
     * 会社名
     * @var string
     */
    public $raw_company_name = '';

    /**
     * 部署名
     * @var string
     */
    public $raw_post_name = '';

    /**
     * 担当者名
     * @var string
     */
    public $raw_charge_name = '';

    /**
     * 担当者名フリガナ
     * @var string
     */
    public $raw_charge_furigana = '';

    /**
     * 電話番号1
     * @var string
     */
    public $raw_tel1 = '';

    /**
     * 電話番号2
     * @var string
     */
    public $raw_tel2 = '';

    /**
     * 電話番号3
     * @var string
     */
    public $raw_tel3 = '';

    /**
     * 電話種類コード選択値
     * @var string
     */
    public $raw_tel_type_cd_sel = '';

    /**
     * 電話種類その他
     * @var string
     */
    public $raw_tel_other = '';

    /**
     * FAX番号1
     * @var string
     */
    public $raw_fax1 = '';

    /**
     * FAX番号2
     * @var string
     */
    public $raw_fax2 = '';

    /**
     * FAX番号3
     * @var string
     */
    public $raw_fax3 = '';

    /**
     * メールアドレス
     * @var string
     */
    public $raw_mail = '';

    /**
     * 連絡方法コード選択値
     * @var string
     */
    public $raw_contact_method_cd_sel = '';

    /**
     * 電話連絡可能コード選択値
     * @var string
     */
    public $raw_contact_available_cd_sel = '';

    /**
     * 電話連絡可能開始時刻コード選択値
     * @var string
     */
    public $raw_contact_start_cd_sel = '';

    /**
     * 電話連絡可能開始時刻コードリスト
     * @var array
     */
    public $raw_contact_start_cds = array();

    /**
     * 電話連絡可能開始時刻ラベルリスト
     * @var array
     */
    public $raw_contact_start_lbls = array();

    /**
     * 電話連絡可能終了時刻コード選択値
     * @var string
     */
    public $raw_contact_end_cd_sel = '';

    /**
     * 電話連絡可能終了時刻コードリスト
     * @var array
     */
    public $raw_contact_end_cds = array();

    /**
     * 電話連絡可能終了時刻ラベルリスト
     * @var array
     */
    public $raw_contact_end_lbls = array();

    /**
     * 郵便番号1
     * @var string
     */
    public $raw_zip1 = '';

    /**
     * 郵便番号2
     * @var string
     */
    public $raw_zip2 = '';

    /**
     * 都道府県コード選択値
     * @var string
     */
    public $raw_pref_cd_sel = '';

    /**
     * 都道府県コードリスト
     * @var array
     */
    public $raw_pref_cds = array();

    /**
     * 都道府県ラベルリスト
     * @var array
     */
    public $raw_pref_lbls = array();

    /**
     * 住所
     * @var string
     */
    public $raw_address = '';
    
    /**
     * checkbox agreement
     * @var string
     */
    public $raw_chb_agreement = '';

    /**
     * エンティティ化されたお問い合わせ種類コード選択値を返します。
     * @return string エンティティ化されたお問い合わせ種類コード選択値
     */
    public function inquiry_type_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_inquiry_type_cd_sel);
    }

    /**
     * エンティティ化されたお問い合わせカテゴリーコード選択値を返します。
     * @return string エンティティ化されたお問い合わせカテゴリーコード選択値
     */
    public function inquiry_category_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_inquiry_category_cd_sel);
    }

    /**
     * エンティティ化されたお問い合わせ件名を返します。
     * @return string エンティティ化されたお問い合わせ件名
     */
    public function inquiry_title()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_inquiry_title);
    }

    /**
     * エンティティ化されたお問い合わせ内容を返します。
     * @return string エンティティ化されたお問い合わせ内容
     */
    public function inquiry_content()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_inquiry_content);
    }

    /**
     * エンティティ化された会社名を返します。
     * @return string エンティティ化された会社名
     */
    public function company_name()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_company_name);
    }

    /**
     * エンティティ化された部署名を返します。
     * @return string エンティティ化された部署名
     */
    public function post_name()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_post_name);
    }

    /**
     * エンティティ化された担当者名を返します。
     * @return string エンティティ化された担当者名
     */
    public function charge_name()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_charge_name);
    }

    /**
     * エンティティ化された担当者名フリガナを返します。
     * @return string エンティティ化された担当者名フリガナ
     */
    public function charge_furigana()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_charge_furigana);
    }

    /**
     * エンティティ化された電話番号1を返します。
     * @return string エンティティ化された電話番号1
     */
    public function tel1()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel1);
    }

    /**
     * エンティティ化された電話番号2を返します。
     * @return string エンティティ化された電話番号2
     */
    public function tel2()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel2);
    }

    /**
     * エンティティ化された電話番号3を返します。
     * @return string エンティティ化された電話番号3
     */
    public function tel3()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel3);
    }

    /**
     * エンティティ化された電話種類コード選択値を返します。
     * @return string エンティティ化された電話種類コード選択値
     */
    public function tel_type_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel_type_cd_sel);
    }

    /**
     * エンティティ化された電話種類その他を返します。
     * @return string エンティティ化された電話種類その他
     */
    public function tel_other()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel_other);
    }

    /**
     * エンティティ化されたFAX番号1を返します。
     * @return string エンティティ化されたFAX番号1
     */
    public function fax1()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_fax1);
    }

    /**
     * エンティティ化されたFAX番号2を返します。
     * @return string エンティティ化されたFAX番号2
     */
    public function fax2()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_fax2);
    }

    /**
     * エンティティ化されたFAX番号3を返します。
     * @return string エンティティ化されたFAX番号3
     */
    public function fax3()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_fax3);
    }

    /**
     * エンティティ化されたメールアドレスを返します。
     * @return string エンティティ化されたメールアドレス
     */
    public function mail()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_mail);
    }

    /**
     * エンティティ化された連絡方法コード選択値を返します。
     * @return string エンティティ化された連絡方法コード選択値
     */
    public function contact_method_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_method_cd_sel);
    }

    /**
     * エンティティ化された電話連絡可能コード選択値を返します。
     * @return string エンティティ化された電話連絡可能コード選択値
     */
    public function contact_available_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_available_cd_sel);
    }

    /**
     * エンティティ化された電話連絡可能開始時刻コード選択値を返します。
     * @return string エンティティ化された電話連絡可能開始時刻コード選択値
     */
    public function contact_start_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_start_cd_sel);
    }

    /**
     * エンティティ化された電話連絡可能開始時刻コードリストを返します。
     * @return array エンティティ化された電話連絡可能開始時刻コードリスト
     */
    public function contact_start_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_start_cds);
    }

    /**
     * エンティティ化された電話連絡可能開始時刻ラベルリストを返します。
     * @return array エンティティ化された電話連絡可能開始時刻ラベルリスト
     */
    public function contact_start_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_start_lbls);
    }

    /**
     * エンティティ化された電話連絡可能終了時刻コード選択値を返します。
     * @return string エンティティ化された電話連絡可能終了時刻コード選択値
     */
    public function contact_end_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_end_cd_sel);
    }

    /**
     * エンティティ化された電話連絡可能終了時刻コードリストを返します。
     * @return array エンティティ化された電話連絡可能終了時刻コードリスト
     */
    public function contact_end_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_end_cds);
    }

    /**
     * エンティティ化された電話連絡可能終了時刻ラベルリストを返します。
     * @return array エンティティ化された電話連絡可能終了時刻ラベルリスト
     */
    public function contact_end_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_end_lbls);
    }

    /**
     * エンティティ化された郵便番号1を返します。
     * @return string エンティティ化された郵便番号1
     */
    public function zip1()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_zip1);
    }

    /**
     * エンティティ化された郵便番号2を返します。
     * @return string エンティティ化された郵便番号2
     */
    public function zip2()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_zip2);
    }

    /**
     * エンティティ化された都道府県コード選択値を返します。
     * @return string エンティティ化された都道府県コード選択値
     */
    public function pref_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_pref_cd_sel);
    }

    /**
     * エンティティ化された都道府県コードリストを返します。
     * @return array エンティティ化された都道府県コードリスト
     */
    public function pref_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_pref_cds);
    }

    /**
     * エンティティ化された都道府県ラベルリストを返します。
     * @return array エンティティ化された都道府県ラベルリスト
     */
    public function pref_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_pref_lbls);
    }

    /**
     * エンティティ化された住所を返します。
     * @return string エンティティ化された住所
     */
    public function address()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_address);
    }
    /**
     * エンティティ化されたお問い合わせ内容を返します。
     * @return string エンティティ化されたお問い合わせ内容
    */
    public function chb_agreement()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_chb_agreement);
    }

}
?>

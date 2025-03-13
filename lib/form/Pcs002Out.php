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
 * 法人設置輸送確認画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Pcs002Out
{
    /**
     * お問い合わせ種類
     * @var string
     */
    public $raw_inquiry_type = '';

    /**
     * お問い合わせカテゴリー
     * @var string
     */
    public $raw_inquiry_category = '';

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
     * 電話番号
     * @var string
     */
    public $raw_tel = '';

    /**
     * 電話種類
     * @var string
     */
    public $raw_tel_type = '';

    /**
     * 電話種類その他
     * @var string
     */
    public $raw_tel_other = '';

    /**
     * FAX番号
     * @var string
     */
    public $raw_fax = '';

    /**
     * メールアドレス
     * @var string
     */
    public $raw_mail = '';

    /**
     * 連絡方法
     * @var string
     */
    public $raw_contact_method = '';

    /**
     * 電話連絡可能
     * @var string
     */
    public $raw_contact_available = '';

    /**
     * 電話連絡可能開始時刻
     * @var string
     */
    public $raw_contact_start = '';

    /**
     * 電話連絡可能終了時刻
     * @var string
     */
    public $raw_contact_end = '';

    /**
     * 郵便番号
     * @var string
     */
    public $raw_zip = '';

    /**
     * 都道府県住所
     * @var string
     */
    public $raw_address_all = '';

    /**
     * エンティティ化されたお問い合わせ種類を返します。
     * @return string エンティティ化されたお問い合わせ種類
     */
    public function inquiry_type()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_inquiry_type);
    }

    /**
     * エンティティ化されたお問い合わせカテゴリーを返します。
     * @return string エンティティ化されたお問い合わせカテゴリー
     */
    public function inquiry_category()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_inquiry_category);
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
     * エンティティ化されたお問い合わせ内容を返します（改行文字の前にBRタグが挿入されます）。
     * @return string エンティティ化されたお問い合わせ内容
     */
    public function inquiry_content()
    {
        return Sgmov_Component_String::nl2br(Sgmov_Component_String::htmlspecialchars($this->raw_inquiry_content));
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
     * エンティティ化された電話番号を返します。
     * @return string エンティティ化された電話番号
     */
    public function tel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel);
    }

    /**
     * エンティティ化された電話種類を返します。
     * @return string エンティティ化された電話種類
     */
    public function tel_type()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel_type);
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
     * エンティティ化されたFAX番号を返します。
     * @return string エンティティ化されたFAX番号
     */
    public function fax()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_fax);
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
     * エンティティ化された連絡方法を返します。
     * @return string エンティティ化された連絡方法
     */
    public function contact_method()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_method);
    }

    /**
     * エンティティ化された電話連絡可能を返します。
     * @return string エンティティ化された電話連絡可能
     */
    public function contact_available()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_available);
    }

    /**
     * エンティティ化された電話連絡可能開始時刻を返します。
     * @return string エンティティ化された電話連絡可能開始時刻
     */
    public function contact_start()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_start);
    }

    /**
     * エンティティ化された電話連絡可能終了時刻を返します。
     * @return string エンティティ化された電話連絡可能終了時刻
     */
    public function contact_end()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_end);
    }

    /**
     * エンティティ化された郵便番号を返します。
     * @return string エンティティ化された郵便番号
     */
    public function zip()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_zip);
    }

    /**
     * エンティティ化された都道府県住所を返します。
     * @return string エンティティ化された都道府県住所
     */
    public function address_all()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_address_all);
    }

}
?>

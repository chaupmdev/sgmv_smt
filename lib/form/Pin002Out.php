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
 * お問い合わせ確認画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Pin002Out
{
    /**
     * お問い合わせ種類
     * @var string
     */
    public $raw_inquiry_type = '';

    /**
     * ＳＧムービングからの回答
     * @var string
     */
    public $raw_need_reply = '';

    /**
     * 会社名
     * @var string
     */
    public $raw_company_name = '';

    /**
     * お名前
     * @var string
     */
    public $raw_name = '';

    /**
     * フリガナ
     * @var string
     */
    public $raw_furigana = '';

    /**
     * 電話番号
     * @var string
     */
    public $raw_tel = '';

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
     * 都道府県住所
     * @var string
     */
    public $raw_address_all = '';

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
     * エンティティ化されたお問い合わせ種類を返します。
     * @return string エンティティ化されたお問い合わせ種類
     */
    public function inquiry_type()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_inquiry_type);
    }

    /**
     * エンティティ化されたＳＧムービングからの回答を返します。
     * @return string エンティティ化されたＳＧムービングからの回答
     */
    public function need_reply()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_need_reply);
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
     * エンティティ化されたお名前を返します。
     * @return string エンティティ化されたお名前
     */
    public function name()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_name);
    }

    /**
     * エンティティ化されたフリガナを返します。
     * @return string エンティティ化されたフリガナ
     */
    public function furigana()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_furigana);
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
     * エンティティ化されたメールアドレスを返します。
     * @return string エンティティ化されたメールアドレス
     */
    public function mail()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_mail);
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

}
?>

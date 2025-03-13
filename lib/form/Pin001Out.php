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
 * お問い合わせ入力画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Pin001Out
{
    /**
     * お問い合わせ種類コード選択値
     * @var string
     */
    public $raw_inquiry_type_cd_sel = '';

    /**
     * ＳＧムービングからの回答コード選択値
     * @var string
     */
    public $raw_need_reply_cd_sel = '';

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
     * メールアドレス
     * @var string
     */
    public $raw_mail = '';

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
     * エンティティ化されたＳＧムービングからの回答コード選択値を返します。
     * @return string エンティティ化されたＳＧムービングからの回答コード選択値
     */
    public function need_reply_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_need_reply_cd_sel);
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
     * エンティティ化されたメールアドレスを返します。
     * @return string エンティティ化されたメールアドレス
     */
    public function mail()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_mail);
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
     * エンティティ化されたお問い合わせ内容を返します。
     * @return string エンティティ化されたお問い合わせ内容
    */
    public function chb_agreement()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_chb_agreement);
    }
}
?>

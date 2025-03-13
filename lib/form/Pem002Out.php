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
 * 採用エントリー確認画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Pem002Out
{
    /**
     * 採用区分
     * @var string
     */
    public $raw_employ_type = '';

    /**
     * 職種
     * @var string
     */
    public $raw_job_type = '';

    /**
     * 勤務地リスト
     * @var array
     */
    public $raw_work_places = array();

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
     * 年齢
     * @var string
     */
    public $raw_age = '';

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
     * 志望動機・自己PR
     * @var string
     */
    public $raw_resume = '';

    /**
     * エンティティ化された採用区分を返します。
     * @return string エンティティ化された採用区分
     */
    public function employ_type()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_employ_type);
    }

    /**
     * エンティティ化された職種を返します。
     * @return string エンティティ化された職種
     */
    public function job_type()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_job_type);
    }

    /**
     * エンティティ化された勤務地リストを返します。
     * @return array エンティティ化された勤務地リスト
     */
    public function work_places()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_work_places);
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
     * エンティティ化された年齢を返します。
     * @return string エンティティ化された年齢
     */
    public function age()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_age);
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
     * エンティティ化された志望動機・自己PRを返します（改行文字の前にBRタグが挿入されます）。
     * @return string エンティティ化された志望動機・自己PR
     */
    public function resume()
    {
        return Sgmov_Component_String::nl2br(Sgmov_Component_String::htmlspecialchars($this->raw_resume));
    }

}
?>

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
 * 採用エントリー入力画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Pem001Out
{
    /**
     * 採用区分コード選択値
     * @var string
     */
    public $raw_employ_type_cd_sel = '';

    /**
     * 職種コード選択値
     * @var string
     */
    public $raw_job_type_cd_sel = '';

    /**
     * 勤務地選択フラグ選択値リスト
     * @var array
     */
    public $raw_work_place_flag_sels = array();

    /**
     * 勤務地コードリスト
     * @var array
     */
    public $raw_work_place_cds = array();

    /**
     * 勤務地ラベルリスト
     * @var array
     */
    public $raw_work_place_lbls = array();

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
     * 年齢コード選択値
     * @var string
     */
    public $raw_age_cd_sel = '';

    /**
     * 年齢コードリスト
     * @var array
     */
    public $raw_age_cds = array();

    /**
     * 年齢ラベルリスト
     * @var array
     */
    public $raw_age_lbls = array();

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
     * 志望動機・自己PR
     * @var string
     */
    public $raw_resume = '';

    /**
     * エンティティ化された採用区分コード選択値を返します。
     * @return string エンティティ化された採用区分コード選択値
     */
    public function employ_type_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_employ_type_cd_sel);
    }

    /**
     * エンティティ化された職種コード選択値を返します。
     * @return string エンティティ化された職種コード選択値
     */
    public function job_type_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_job_type_cd_sel);
    }

    /**
     * エンティティ化された勤務地選択フラグ選択値リストを返します。
     * @return array エンティティ化された勤務地選択フラグ選択値リスト
     */
    public function work_place_flag_sels()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_work_place_flag_sels);
    }

    /**
     * エンティティ化された勤務地コードリストを返します。
     * @return array エンティティ化された勤務地コードリスト
     */
    public function work_place_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_work_place_cds);
    }

    /**
     * エンティティ化された勤務地ラベルリストを返します。
     * @return array エンティティ化された勤務地ラベルリスト
     */
    public function work_place_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_work_place_lbls);
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
     * エンティティ化された年齢コード選択値を返します。
     * @return string エンティティ化された年齢コード選択値
     */
    public function age_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_age_cd_sel);
    }

    /**
     * エンティティ化された年齢コードリストを返します。
     * @return array エンティティ化された年齢コードリスト
     */
    public function age_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_age_cds);
    }

    /**
     * エンティティ化された年齢ラベルリストを返します。
     * @return array エンティティ化された年齢ラベルリスト
     */
    public function age_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_age_lbls);
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
     * エンティティ化された志望動機・自己PRを返します。
     * @return string エンティティ化された志望動機・自己PR
     */
    public function resume()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_resume);
    }

}
?>

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
 * アンケート結果ダウンロード画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Aqu001Out
{
    /**
     * 本社ユーザーフラグ
     * @var string
     */
    public $raw_honsha_user_flag = '';

    /**
     * 開始年コードリスト
     * @var array
     */
    public $raw_from_year_cds = array();

    /**
     * 開始年ラベルリスト
     * @var array
     */
    public $raw_from_year_lbls = array();

    /**
     * 開始月コードリスト
     * @var array
     */
    public $raw_from_month_cds = array();

    /**
     * 開始月ラベルリスト
     * @var array
     */
    public $raw_from_month_lbls = array();

    /**
     * 開始日コードリスト
     * @var array
     */
    public $raw_from_day_cds = array();

    /**
     * 開始日ラベルリスト
     * @var array
     */
    public $raw_from_day_lbls = array();

    /**
     * 開始年コード選択値
     * @var string
     */
    public $raw_from_year_cd_sel = '';

    /**
     * 開始月コード選択値
     * @var string
     */
    public $raw_from_month_cd_sel = '';

    /**
     * 開始日コード選択値
     * @var string
     */
    public $raw_from_day_cd_sel = '';

    /**
     * 終了年コードリスト
     * @var array
     */
    public $raw_to_year_cds = array();

    /**
     * 終了年ラベルリスト
     * @var array
     */
    public $raw_to_year_lbls = array();

    /**
     * 終了月コードリスト
     * @var array
     */
    public $raw_to_month_cds = array();

    /**
     * 終了月ラベルリスト
     * @var array
     */
    public $raw_to_month_lbls = array();

    /**
     * 終了日コードリスト
     * @var array
     */
    public $raw_to_day_cds = array();

    /**
     * 終了日ラベルリスト
     * @var array
     */
    public $raw_to_day_lbls = array();

    /**
     * 終了年コード選択値
     * @var string
     */
    public $raw_to_year_cd_sel = '';

    /**
     * 終了月コード選択値
     * @var string
     */
    public $raw_to_month_cd_sel = '';

    /**
     * 終了日コード選択値
     * @var string
     */
    public $raw_to_day_cd_sel = '';

    /**
     * オフィス移転フラグ
     * @var string
     */
    public $raw_office_flag = '';

    /**
     * 設置輸送フラグ
     * @var string
     */
    public $raw_setting_flag = '';

    /**
     * 個人向けお引越しフラグ
     * @var string
     */
    public $raw_personal_flag = '';

    /**
     * ダウンロード済みフラグ
     * @var string
     */
    public $raw_downloaded_flag = '';

    /**
     * エンティティ化された本社ユーザーフラグを返します。
     * @return string エンティティ化された本社ユーザーフラグ
     */
    public function honsha_user_flag()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_honsha_user_flag);
    }

    /**
     * エンティティ化された開始年コードリストを返します。
     * @return array エンティティ化された開始年コードリスト
     */
    public function from_year_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_year_cds);
    }

    /**
     * エンティティ化された開始年ラベルリストを返します。
     * @return array エンティティ化された開始年ラベルリスト
     */
    public function from_year_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_year_lbls);
    }

    /**
     * エンティティ化された開始月コードリストを返します。
     * @return array エンティティ化された開始月コードリスト
     */
    public function from_month_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_month_cds);
    }

    /**
     * エンティティ化された開始月ラベルリストを返します。
     * @return array エンティティ化された開始月ラベルリスト
     */
    public function from_month_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_month_lbls);
    }

    /**
     * エンティティ化された開始日コードリストを返します。
     * @return array エンティティ化された開始日コードリスト
     */
    public function from_day_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_day_cds);
    }

    /**
     * エンティティ化された開始日ラベルリストを返します。
     * @return array エンティティ化された開始日ラベルリスト
     */
    public function from_day_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_day_lbls);
    }

    /**
     * エンティティ化された開始年コード選択値を返します。
     * @return string エンティティ化された開始年コード選択値
     */
    public function from_year_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_year_cd_sel);
    }

    /**
     * エンティティ化された開始月コード選択値を返します。
     * @return string エンティティ化された開始月コード選択値
     */
    public function from_month_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_month_cd_sel);
    }

    /**
     * エンティティ化された開始日コード選択値を返します。
     * @return string エンティティ化された開始日コード選択値
     */
    public function from_day_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_day_cd_sel);
    }

    /**
     * エンティティ化された終了年コードリストを返します。
     * @return array エンティティ化された終了年コードリスト
     */
    public function to_year_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_to_year_cds);
    }

    /**
     * エンティティ化された終了年ラベルリストを返します。
     * @return array エンティティ化された終了年ラベルリスト
     */
    public function to_year_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_to_year_lbls);
    }

    /**
     * エンティティ化された終了月コードリストを返します。
     * @return array エンティティ化された終了月コードリスト
     */
    public function to_month_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_to_month_cds);
    }

    /**
     * エンティティ化された終了月ラベルリストを返します。
     * @return array エンティティ化された終了月ラベルリスト
     */
    public function to_month_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_to_month_lbls);
    }

    /**
     * エンティティ化された終了日コードリストを返します。
     * @return array エンティティ化された終了日コードリスト
     */
    public function to_day_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_to_day_cds);
    }

    /**
     * エンティティ化された終了日ラベルリストを返します。
     * @return array エンティティ化された終了日ラベルリスト
     */
    public function to_day_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_to_day_lbls);
    }

    /**
     * エンティティ化された終了年コード選択値を返します。
     * @return string エンティティ化された終了年コード選択値
     */
    public function to_year_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_to_year_cd_sel);
    }

    /**
     * エンティティ化された終了月コード選択値を返します。
     * @return string エンティティ化された終了月コード選択値
     */
    public function to_month_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_to_month_cd_sel);
    }

    /**
     * エンティティ化された終了日コード選択値を返します。
     * @return string エンティティ化された終了日コード選択値
     */
    public function to_day_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_to_day_cd_sel);
    }

    /**
     * エンティティ化されたオフィス移転フラグを返します。
     * @return string エンティティ化されたオフィス移転フラグ
     */
    public function office_flag()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_office_flag);
    }

    /**
     * エンティティ化された設置輸送フラグを返します。
     * @return string エンティティ化された設置輸送フラグ
     */
    public function setting_flag()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_setting_flag);
    }

    /**
     * エンティティ化された個人向けお引越しフラグを返します。
     * @return string エンティティ化された個人向けお引越しフラグ
     */
    public function personal_flag()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_personal_flag);
    }

    /**
     * エンティティ化されたダウンロード済みフラグを返します。
     * @return string エンティティ化されたダウンロード済みフラグ
     */
    public function downloaded_flag()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_downloaded_flag);
    }

}
?>

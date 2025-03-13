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
 * 特価個別編集期間入力画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Asp006Out
{
    /**
     * 本社ユーザーフラグ
     * @var string
     */
    public $raw_honsha_user_flag = '';

    /**
     * 特価一覧URL
     * @var string
     */
    public $raw_sp_list_url = '';

    /**
     * 特価種別
     * @var string
     */
    public $raw_sp_kind = '';

    /**
     * 開始年コードリスト
     * @var array
     */
    public $raw_from_year_cds = array();

    /**
     * 開始月コードリスト
     * @var array
     */
    public $raw_from_month_cds = array();

    /**
     * 開始日コードリスト
     * @var array
     */
    public $raw_from_day_cds = array();

    /**
     * 開始年ラベルリスト
     * @var array
     */
    public $raw_from_year_lbls = array();

    /**
     * 開始月ラベルリスト
     * @var array
     */
    public $raw_from_month_lbls = array();

    /**
     * 開始日ラベルリスト
     * @var array
     */
    public $raw_from_day_lbls = array();

    /**
     * 終了年コードリスト
     * @var array
     */
    public $raw_to_year_cds = array();

    /**
     * 終了月コードリスト
     * @var array
     */
    public $raw_to_month_cds = array();

    /**
     * 終了日コードリスト
     * @var array
     */
    public $raw_to_day_cds = array();

    /**
     * 終了年ラベルリスト
     * @var array
     */
    public $raw_to_year_lbls = array();

    /**
     * 終了月ラベルリスト
     * @var array
     */
    public $raw_to_month_lbls = array();

    /**
     * 終了日ラベルリスト
     * @var array
     */
    public $raw_to_day_lbls = array();

    /**
     * 日付リスト
     * @var array
     */
    public $raw_days = array();

    /**
     * 祝日フラグリスト
     * @var array
     */
    public $raw_holiday_flags = array();

    /**
     * 曜日コードリスト
     * @var array
     */
    public $raw_weekday_cds = array();

    /**
     * チェックボックス表示フラグリスト
     * @var array
     */
    public $raw_check_show_flags = array();

    /**
     * 選択日付リスト
     * @var array
     */
    public $raw_sel_days = array();

    /**
     * エンティティ化された本社ユーザーフラグを返します。
     * @return string エンティティ化された本社ユーザーフラグ
     */
    public function honsha_user_flag()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_honsha_user_flag);
    }

    /**
     * エンティティ化された特価一覧URLを返します。
     * @return string エンティティ化された特価一覧URL
     */
    public function sp_list_url()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_list_url);
    }

    /**
     * エンティティ化された特価種別を返します。
     * @return string エンティティ化された特価種別
     */
    public function sp_kind()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_kind);
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
     * エンティティ化された開始月コードリストを返します。
     * @return array エンティティ化された開始月コードリスト
     */
    public function from_month_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_month_cds);
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
     * エンティティ化された開始年ラベルリストを返します。
     * @return array エンティティ化された開始年ラベルリスト
     */
    public function from_year_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_year_lbls);
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
     * エンティティ化された開始日ラベルリストを返します。
     * @return array エンティティ化された開始日ラベルリスト
     */
    public function from_day_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_day_lbls);
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
     * エンティティ化された終了月コードリストを返します。
     * @return array エンティティ化された終了月コードリスト
     */
    public function to_month_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_to_month_cds);
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
     * エンティティ化された終了年ラベルリストを返します。
     * @return array エンティティ化された終了年ラベルリスト
     */
    public function to_year_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_to_year_lbls);
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
     * エンティティ化された終了日ラベルリストを返します。
     * @return array エンティティ化された終了日ラベルリスト
     */
    public function to_day_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_to_day_lbls);
    }

    /**
     * エンティティ化された日付リストを返します。
     * @return array エンティティ化された日付リスト
     */
    public function days()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_days);
    }

    /**
     * エンティティ化された祝日フラグリストを返します。
     * @return array エンティティ化された祝日フラグリスト
     */
    public function holiday_flags()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_holiday_flags);
    }

    /**
     * エンティティ化された曜日コードリストを返します。
     * @return array エンティティ化された曜日コードリスト
     */
    public function weekday_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_weekday_cds);
    }

    /**
     * エンティティ化されたチェックボックス表示フラグリストを返します。
     * @return array エンティティ化されたチェックボックス表示フラグリスト
     */
    public function check_show_flags()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_check_show_flags);
    }

    /**
     * エンティティ化された選択日付リストを返します。
     * @return array エンティティ化された選択日付リスト
     */
    public function sel_days()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sel_days);
    }

}
?>

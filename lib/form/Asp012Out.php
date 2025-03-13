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
 * 料金カレンダー画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Asp012Out
{
    /**
     * 編集フラグ
     * @var string
     */
    public $raw_edit_flag = '';

    /**
     * コース
     * @var string
     */
    public $raw_course = '';

    /**
     * プラン
     * @var string
     */
    public $raw_plan = '';

    /**
     * 出発エリア
     * @var string
     */
    public $raw_from_area = '';

    /**
     * 到着エリア
     * @var string
     */
    public $raw_to_area = '';

    /**
     * カレンダー年
     * @var string
     */
    public $raw_cal_year = '';

    /**
     * カレンダー月
     * @var string
     */
    public $raw_cal_month = '';

    /**
     * 前月リンクアドレス
     * @var string
     */
    public $raw_prev_month_link = '';

    /**
     * 次月リンクアドレス
     * @var string
     */
    public $raw_next_month_link = '';

    /**
     * カレンダー日付リスト
     * @var array
     */
    public $raw_cal_days = array();

    /**
     * カレンダー祝日フラグリスト
     * @var array
     */
    public $raw_cal_holiday_flags = array();

    /**
     * カレンダー曜日コードリスト
     * @var array
     */
    public $raw_cal_weekday_flags = array();

    /**
     * カレンダー有効日付フラグリスト
     * @var array
     */
    public $raw_cal_valid_flags = array();

    /**
     * カレンダーキャンペーンフラグリスト
     * @var array
     */
    public $raw_cal_campaign_flags = array();

    /**
     * カレンダー閑散繁忙フラグリスト
     * @var array
     */
    public $raw_cal_extra_flags = array();

    /**
     * カレンダー編集情報フラグリスト
     * @var array
     */
    public $raw_cal_editing_flags = array();

    /**
     * カレンダー料金リスト
     * @var array
     */
    public $raw_cal_prices = array();

    /**
     * カレンダー対象日特価名称リスト
     * @var array
     */
    public $raw_cal_sp_names = array();

    /**
     * カレンダー対象日特価URLリスト
     * @var array
     */
    public $raw_cal_sp_urls = array();

    /**
     * エンティティ化された編集フラグを返します。
     * @return string エンティティ化された編集フラグ
     */
    public function edit_flag()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_edit_flag);
    }

    /**
     * エンティティ化されたコースを返します。
     * @return string エンティティ化されたコース
     */
    public function course()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_course);
    }

    /**
     * エンティティ化されたプランを返します。
     * @return string エンティティ化されたプラン
     */
    public function plan()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_plan);
    }

    /**
     * エンティティ化された出発エリアを返します。
     * @return string エンティティ化された出発エリア
     */
    public function from_area()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_area);
    }

    /**
     * エンティティ化された到着エリアを返します。
     * @return string エンティティ化された到着エリア
     */
    public function to_area()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_to_area);
    }

    /**
     * エンティティ化されたカレンダー年を返します。
     * @return string エンティティ化されたカレンダー年
     */
    public function cal_year()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cal_year);
    }

    /**
     * エンティティ化されたカレンダー月を返します。
     * @return string エンティティ化されたカレンダー月
     */
    public function cal_month()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cal_month);
    }

    /**
     * エンティティ化された前月リンクアドレスを返します。
     * @return string エンティティ化された前月リンクアドレス
     */
    public function prev_month_link()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_prev_month_link);
    }

    /**
     * エンティティ化された次月リンクアドレスを返します。
     * @return string エンティティ化された次月リンクアドレス
     */
    public function next_month_link()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_next_month_link);
    }

    /**
     * エンティティ化されたカレンダー日付リストを返します。
     * @return array エンティティ化されたカレンダー日付リスト
     */
    public function cal_days()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cal_days);
    }

    /**
     * エンティティ化されたカレンダー祝日フラグリストを返します。
     * @return array エンティティ化されたカレンダー祝日フラグリスト
     */
    public function cal_holiday_flags()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cal_holiday_flags);
    }

    /**
     * エンティティ化されたカレンダー曜日コードリストを返します。
     * @return array エンティティ化されたカレンダー曜日コードリスト
     */
    public function cal_weekday_flags()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cal_weekday_flags);
    }

    /**
     * エンティティ化されたカレンダー有効日付フラグリストを返します。
     * @return array エンティティ化されたカレンダー有効日付フラグリスト
     */
    public function cal_valid_flags()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cal_valid_flags);
    }

    /**
     * エンティティ化されたカレンダーキャンペーンフラグリストを返します。
     * @return array エンティティ化されたカレンダーキャンペーンフラグリスト
     */
    public function cal_campaign_flags()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cal_campaign_flags);
    }

    /**
     * エンティティ化されたカレンダー閑散繁忙フラグリストを返します。
     * @return array エンティティ化されたカレンダー閑散繁忙フラグリスト
     */
    public function cal_extra_flags()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cal_extra_flags);
    }

    /**
     * エンティティ化されたカレンダー編集情報フラグリストを返します。
     * @return array エンティティ化されたカレンダー編集情報フラグリスト
     */
    public function cal_editing_flags()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cal_editing_flags);
    }

    /**
     * エンティティ化されたカレンダー料金リストを返します。
     * @return array エンティティ化されたカレンダー料金リスト
     */
    public function cal_prices()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cal_prices);
    }

    /**
     * エンティティ化されたカレンダー対象日特価名称リストを返します。
     * @return array エンティティ化されたカレンダー対象日特価名称リスト
     */
    public function cal_sp_names()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cal_sp_names);
    }

    /**
     * エンティティ化されたカレンダー対象日特価URLリストを返します。
     * @return array エンティティ化されたカレンダー対象日特価URLリスト
     */
    public function cal_sp_urls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cal_sp_urls);
    }

}
?>

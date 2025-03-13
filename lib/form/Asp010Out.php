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
 * 特価編集確認画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Asp010Out
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
     * 戻り先URL
     * @var string
     */
    public $raw_back_url = '';

    /**
     * 特価登録日
     * @var string
     */
    public $raw_sp_regist_date = '';

    /**
     * 特価担当
     * @var string
     */
    public $raw_sp_charge_center = '';

    /**
     * 特価登録者名
     * @var string
     */
    public $raw_sp_regist_user = '';

    /**
     * 特価状況
     * @var string
     */
    public $raw_sp_status = '';

    /**
     * 特価名称
     * @var string
     */
    public $raw_sp_name = '';

    /**
     * 特価内容
     * @var string
     */
    public $raw_sp_content = '';

    /**
     * 特価コースラベルリスト
     * @var string
     */
    public $raw_sp_course_lbls = '';

    /**
     * 特価プランラベルリスト
     * @var string
     */
    public $raw_sp_plan_lbls = '';

    /**
     * 特価出発エリア
     * @var string
     */
    public $raw_sp_from_area = '';

    /**
     * 特価到着エリア
     * @var string
     */
    public $raw_sp_to_area = '';

    /**
     * 特価期間
     * @var string
     */
    public $raw_sp_period = '';

    /**
     * 特価料金設定有無フラグ
     * @var string
     */
    public $raw_sp_charge_set_flag = '';

    /**
     * コースプランコード選択値
     * @var string
     */
    public $raw_course_plan_cd_sel = '';

    /**
     * コースプランコードリスト
     * @var array
     */
    public $raw_course_plan_cds = array();

    /**
     * コースプランラベルリスト
     * @var array
     */
    public $raw_course_plan_lbls = array();

    /**
     * 出発エリアコード選択値
     * @var string
     */
    public $raw_from_area_cd_sel = '';

    /**
     * 出発エリアコードリスト
     * @var array
     */
    public $raw_from_area_cds = array();

    /**
     * 出発エリアラベルリスト
     * @var array
     */
    public $raw_from_area_lbls = array();

    /**
     * 条件選択済みフラグ
     * @var string
     */
    public $raw_cond_selected_flag = '';

    /**
     * カレントコースプランコード
     * @var string
     */
    public $raw_cur_course_plan_cd = '';

    /**
     * カレントコースプラン
     * @var string
     */
    public $raw_cur_course_plan = '';

    /**
     * カレント出発エリアコード
     * @var string
     */
    public $raw_cur_from_area_cd = '';

    /**
     * カレント出発エリア
     * @var string
     */
    public $raw_cur_from_area = '';

    /**
     * 到着エリアラベルリスト
     * @var array
     */
    public $raw_to_area_lbls = array();

    /**
     * 特価基本料金リスト
     * @var array
     */
    public $raw_sp_base_charges = array();

    /**
     * 特価料金設定値リスト
     * @var array
     */
    public $raw_sp_setting_charges = array();

    /**
     * 特価カレンダーURLリスト
     * @var array
     */
    public $raw_sp_calendar_urls = array();

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
     * エンティティ化された戻り先URLを返します。
     * @return string エンティティ化された戻り先URL
     */
    public function back_url()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_back_url);
    }

    /**
     * エンティティ化された特価登録日を返します。
     * @return string エンティティ化された特価登録日
     */
    public function sp_regist_date()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_regist_date);
    }

    /**
     * エンティティ化された特価担当を返します。
     * @return string エンティティ化された特価担当
     */
    public function sp_charge_center()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_charge_center);
    }

    /**
     * エンティティ化された特価登録者名を返します。
     * @return string エンティティ化された特価登録者名
     */
    public function sp_regist_user()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_regist_user);
    }

    /**
     * エンティティ化された特価状況を返します。
     * @return string エンティティ化された特価状況
     */
    public function sp_status()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_status);
    }

    /**
     * エンティティ化された特価名称を返します。
     * @return string エンティティ化された特価名称
     */
    public function sp_name()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_name);
    }

    /**
     * エンティティ化された特価内容を返します（改行文字の前にBRタグが挿入されます）。
     * @return string エンティティ化された特価内容
     */
    public function sp_content()
    {
        return Sgmov_Component_String::nl2br(Sgmov_Component_String::htmlspecialchars($this->raw_sp_content));
    }

    /**
     * エンティティ化された特価コースラベルリストを返します。
     * @return string エンティティ化された特価コースラベルリスト
     */
    public function sp_course_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_course_lbls);
    }

    /**
     * エンティティ化された特価プランラベルリストを返します。
     * @return string エンティティ化された特価プランラベルリスト
     */
    public function sp_plan_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_plan_lbls);
    }

    /**
     * エンティティ化された特価出発エリアを返します。
     * @return string エンティティ化された特価出発エリア
     */
    public function sp_from_area()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_from_area);
    }

    /**
     * エンティティ化された特価到着エリアを返します。
     * @return string エンティティ化された特価到着エリア
     */
    public function sp_to_area()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_to_area);
    }

    /**
     * エンティティ化された特価期間を返します。
     * @return string エンティティ化された特価期間
     */
    public function sp_period()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_period);
    }

    /**
     * エンティティ化された特価料金設定有無フラグを返します。
     * @return string エンティティ化された特価料金設定有無フラグ
     */
    public function sp_charge_set_flag()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_charge_set_flag);
    }

    /**
     * エンティティ化されたコースプランコード選択値を返します。
     * @return string エンティティ化されたコースプランコード選択値
     */
    public function course_plan_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_course_plan_cd_sel);
    }

    /**
     * エンティティ化されたコースプランコードリストを返します。
     * @return array エンティティ化されたコースプランコードリスト
     */
    public function course_plan_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_course_plan_cds);
    }

    /**
     * エンティティ化されたコースプランラベルリストを返します。
     * @return array エンティティ化されたコースプランラベルリスト
     */
    public function course_plan_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_course_plan_lbls);
    }

    /**
     * エンティティ化された出発エリアコード選択値を返します。
     * @return string エンティティ化された出発エリアコード選択値
     */
    public function from_area_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_area_cd_sel);
    }

    /**
     * エンティティ化された出発エリアコードリストを返します。
     * @return array エンティティ化された出発エリアコードリスト
     */
    public function from_area_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_area_cds);
    }

    /**
     * エンティティ化された出発エリアラベルリストを返します。
     * @return array エンティティ化された出発エリアラベルリスト
     */
    public function from_area_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_area_lbls);
    }

    /**
     * エンティティ化された条件選択済みフラグを返します。
     * @return string エンティティ化された条件選択済みフラグ
     */
    public function cond_selected_flag()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cond_selected_flag);
    }

    /**
     * エンティティ化されたカレントコースプランコードを返します。
     * @return string エンティティ化されたカレントコースプランコード
     */
    public function cur_course_plan_cd()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_course_plan_cd);
    }

    /**
     * エンティティ化されたカレントコースプランを返します。
     * @return string エンティティ化されたカレントコースプラン
     */
    public function cur_course_plan()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_course_plan);
    }

    /**
     * エンティティ化されたカレント出発エリアコードを返します。
     * @return string エンティティ化されたカレント出発エリアコード
     */
    public function cur_from_area_cd()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_from_area_cd);
    }

    /**
     * エンティティ化されたカレント出発エリアを返します。
     * @return string エンティティ化されたカレント出発エリア
     */
    public function cur_from_area()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_from_area);
    }

    /**
     * エンティティ化された到着エリアラベルリストを返します。
     * @return array エンティティ化された到着エリアラベルリスト
     */
    public function to_area_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_to_area_lbls);
    }

    /**
     * エンティティ化された特価基本料金リストを返します。
     * @return array エンティティ化された特価基本料金リスト
     */
    public function sp_base_charges()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_base_charges);
    }

    /**
     * エンティティ化された特価料金設定値リストを返します。
     * @return array エンティティ化された特価料金設定値リスト
     */
    public function sp_setting_charges()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_setting_charges);
    }

    /**
     * エンティティ化された特価カレンダーURLリストを返します。
     * @return array エンティティ化された特価カレンダーURLリスト
     */
    public function sp_calendar_urls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_calendar_urls);
    }

}
?>

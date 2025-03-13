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
 * 特価個別編集金額入力画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Asp009Out
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
     * カレントコースプラン
     * @var string
     */
    public $raw_cur_course_plan = '';

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
     * 特価差額上限リスト
     * @var array
     */
    public $raw_sp_diff_maxs = array();

    /**
     * 特価差額下限リスト
     * @var array
     */
    public $raw_sp_diff_mins = array();

    /**
     * 特価料金設定値リスト
     * @var array
     */
    public $raw_sp_setting_charges = array();

    /**
     * カレントコースプランコード
     * @var string
     */
    public $raw_cur_course_plan_cd = '';

    /**
     * カレント出発エリアコード
     * @var string
     */
    public $raw_cur_from_area_cd = '';

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
     * エンティティ化されたカレントコースプランを返します。
     * @return string エンティティ化されたカレントコースプラン
     */
    public function cur_course_plan()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_course_plan);
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
     * エンティティ化された特価差額上限リストを返します。
     * @return array エンティティ化された特価差額上限リスト
     */
    public function sp_diff_maxs()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_diff_maxs);
    }

    /**
     * エンティティ化された特価差額下限リストを返します。
     * @return array エンティティ化された特価差額下限リスト
     */
    public function sp_diff_mins()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_diff_mins);
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
     * エンティティ化されたカレントコースプランコードを返します。
     * @return string エンティティ化されたカレントコースプランコード
     */
    public function cur_course_plan_cd()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_course_plan_cd);
    }

    /**
     * エンティティ化されたカレント出発エリアコードを返します。
     * @return string エンティティ化されたカレント出発エリアコード
     */
    public function cur_from_area_cd()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_from_area_cd);
    }

}
?>

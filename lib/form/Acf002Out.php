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
 * 料金マスタ入力画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Acf002Out
{
    /**
     * 本社ユーザーフラグ
     * @var string
     */
    public $raw_honsha_user_flag = '';

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
     * 到着エリアコードリスト
     * @var array
     */
    public $raw_to_area_cds = array();

    /**
     * 到着エリアラベルリスト
     * @var array
     */
    public $raw_to_area_lbls = array();

    /**
     * 基本料金リスト
     * @var array
     */
    public $raw_base_prices = array();

    /**
     * 上限料金リスト
     * @var array
     */
    public $raw_max_prices = array();

    /**
     * 下限料金リスト
     * @var array
     */
    public $raw_min_prices = array();

    /**
     * 元基本料金リスト
     * @var array
     */
    public $raw_orig_base_prices = array();

    /**
     * 元上限料金リスト
     * @var array
     */
    public $raw_orig_max_prices = array();

    /**
     * 元下限料金リスト
     * @var array
     */
    public $raw_orig_min_prices = array();

    /**
     * エンティティ化された本社ユーザーフラグを返します。
     * @return string エンティティ化された本社ユーザーフラグ
     */
    public function honsha_user_flag()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_honsha_user_flag);
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
     * エンティティ化された到着エリアコードリストを返します。
     * @return array エンティティ化された到着エリアコードリスト
     */
    public function to_area_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_to_area_cds);
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
     * エンティティ化された基本料金リストを返します。
     * @return array エンティティ化された基本料金リスト
     */
    public function base_prices()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_base_prices);
    }

    /**
     * エンティティ化された上限料金リストを返します。
     * @return array エンティティ化された上限料金リスト
     */
    public function max_prices()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_max_prices);
    }

    /**
     * エンティティ化された下限料金リストを返します。
     * @return array エンティティ化された下限料金リスト
     */
    public function min_prices()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_min_prices);
    }

    /**
     * エンティティ化された元基本料金リストを返します。
     * @return array エンティティ化された元基本料金リスト
     */
    public function orig_base_prices()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_orig_base_prices);
    }

    /**
     * エンティティ化された元上限料金リストを返します。
     * @return array エンティティ化された元上限料金リスト
     */
    public function orig_max_prices()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_orig_max_prices);
    }

    /**
     * エンティティ化された元下限料金リストを返します。
     * @return array エンティティ化された元下限料金リスト
     */
    public function orig_min_prices()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_orig_min_prices);
    }

}
?>

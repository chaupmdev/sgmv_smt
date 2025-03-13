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
 * 料金マスタ確認画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Acf003Out
{
    /**
     * 本社ユーザーフラグ
     * @var string
     */
    public $raw_honsha_user_flag = '';

    /**
     * コースプラン
     * @var string
     */
    public $raw_course_plan = '';

    /**
     * 出発エリア
     * @var string
     */
    public $raw_from_area = '';

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
     * 基本料金変更フラグリスト
     * @var array
     */
    public $raw_base_price_edit_flags = array();

    /**
     * 上限料金変更フラグリスト
     * @var array
     */
    public $raw_max_price_edit_flags = array();

    /**
     * 下限料金変更フラグリスト
     * @var array
     */
    public $raw_min_price_edit_flags = array();

    /**
     * エンティティ化された本社ユーザーフラグを返します。
     * @return string エンティティ化された本社ユーザーフラグ
     */
    public function honsha_user_flag()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_honsha_user_flag);
    }

    /**
     * エンティティ化されたコースプランを返します。
     * @return string エンティティ化されたコースプラン
     */
    public function course_plan()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_course_plan);
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
     * エンティティ化された基本料金変更フラグリストを返します。
     * @return array エンティティ化された基本料金変更フラグリスト
     */
    public function base_price_edit_flags()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_base_price_edit_flags);
    }

    /**
     * エンティティ化された上限料金変更フラグリストを返します。
     * @return array エンティティ化された上限料金変更フラグリスト
     */
    public function max_price_edit_flags()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_max_price_edit_flags);
    }

    /**
     * エンティティ化された下限料金変更フラグリストを返します。
     * @return array エンティティ化された下限料金変更フラグリスト
     */
    public function min_price_edit_flags()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_min_price_edit_flags);
    }

}
?>

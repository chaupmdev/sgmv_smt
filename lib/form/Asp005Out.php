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
 * 特価編集発着地入力画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Asp005Out
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
     * コースラベルリスト
     * @var array
     */
    public $raw_course_lbls = array();

    /**
     * コースプランコードリスト
     * @var array
     */
    public $raw_course_plan_cds = array();

    /**
     * プランラベルリスト
     * @var array
     */
    public $raw_plan_lbls = array();

    /**
     * コースプラン選択コードリスト
     * @var array
     */
    public $raw_course_plan_sel_cds = array();

    /**
     * 出発拠点ラベルリスト
     * @var array
     */
    public $raw_from_center_lbls = array();

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
     * 出発エリア選択コードリスト
     * @var array
     */
    public $raw_from_area_sel_cds = array();

    /**
     * 到着拠点ラベルリスト
     * @var array
     */
    public $raw_to_center_lbls = array();

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
     * 到着エリア選択コードリスト
     * @var array
     */
    public $raw_to_area_sel_cds = array();

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
     * エンティティ化されたコースラベルリストを返します。
     * @return array エンティティ化されたコースラベルリスト
     */
    public function course_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_course_lbls);
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
     * エンティティ化されたプランラベルリストを返します。
     * @return array エンティティ化されたプランラベルリスト
     */
    public function plan_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_plan_lbls);
    }

    /**
     * エンティティ化されたコースプラン選択コードリストを返します。
     * @return array エンティティ化されたコースプラン選択コードリスト
     */
    public function course_plan_sel_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_course_plan_sel_cds);
    }

    /**
     * エンティティ化された出発拠点ラベルリストを返します。
     * @return array エンティティ化された出発拠点ラベルリスト
     */
    public function from_center_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_center_lbls);
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
     * エンティティ化された出発エリア選択コードリストを返します。
     * @return array エンティティ化された出発エリア選択コードリスト
     */
    public function from_area_sel_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_area_sel_cds);
    }

    /**
     * エンティティ化された到着拠点ラベルリストを返します。
     * @return array エンティティ化された到着拠点ラベルリスト
     */
    public function to_center_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_to_center_lbls);
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
     * エンティティ化された到着エリア選択コードリストを返します。
     * @return array エンティティ化された到着エリア選択コードリスト
     */
    public function to_area_sel_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_to_area_sel_cds);
    }

}
?>

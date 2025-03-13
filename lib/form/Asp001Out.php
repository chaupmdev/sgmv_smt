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
 * 特価一覧画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Asp001Out
{
    /**
     * 本社ユーザーフラグ
     * @var string
     */
    public $raw_honsha_user_flag = '';

    /**
     * 特価一覧種別
     * @var string
     */
    public $raw_sp_list_kind = '';

    /**
     * 特価一覧表示モード
     * @var string
     */
    public $raw_sp_list_view_mode = '';

    /**
     * 特価コードリスト
     * @var array
     */
    public $raw_sp_cds = array();

    /**
     * 特価担当フラグリスト
     * @var array
     */
    public $raw_sp_charge_flags = array();

    /**
     * 特価登録日リスト
     * @var array
     */
    public $raw_sp_created_dates = array();

    /**
     * 特価担当リスト
     * @var array
     */
    public $raw_sp_charge_centers = array();

    /**
     * 特価名称リスト
     * @var array
     */
    public $raw_sp_names = array();

    /**
     * 特価期間リスト
     * @var array
     */
    public $raw_sp_periods = array();

    /**
     * 特価出発エリアリスト
     * @var array
     */
    public $raw_sp_from_areas = array();

    /**
     * 特価詳細URLリスト
     * @var array
     */
    public $raw_sp_detail_urls = array();

    /**
     * エンティティ化された本社ユーザーフラグを返します。
     * @return string エンティティ化された本社ユーザーフラグ
     */
    public function honsha_user_flag()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_honsha_user_flag);
    }

    /**
     * エンティティ化された特価一覧種別を返します。
     * @return string エンティティ化された特価一覧種別
     */
    public function sp_list_kind()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_list_kind);
    }

    /**
     * エンティティ化された特価一覧表示モードを返します。
     * @return string エンティティ化された特価一覧表示モード
     */
    public function sp_list_view_mode()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_list_view_mode);
    }

    /**
     * エンティティ化された特価コードリストを返します。
     * @return array エンティティ化された特価コードリスト
     */
    public function sp_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_cds);
    }

    /**
     * エンティティ化された特価担当フラグリストを返します。
     * @return array エンティティ化された特価担当フラグリスト
     */
    public function sp_charge_flags()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_charge_flags);
    }

    /**
     * エンティティ化された特価登録日リストを返します。
     * @return array エンティティ化された特価登録日リスト
     */
    public function sp_created_dates()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_created_dates);
    }

    /**
     * エンティティ化された特価担当リストを返します。
     * @return array エンティティ化された特価担当リスト
     */
    public function sp_charge_centers()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_charge_centers);
    }

    /**
     * エンティティ化された特価名称リストを返します。
     * @return array エンティティ化された特価名称リスト
     */
    public function sp_names()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_names);
    }

    /**
     * エンティティ化された特価期間リストを返します。
     * @return array エンティティ化された特価期間リスト
     */
    public function sp_periods()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_periods);
    }

    /**
     * エンティティ化された特価出発エリアリストを返します。
     * @return array エンティティ化された特価出発エリアリスト
     */
    public function sp_from_areas()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_from_areas);
    }

    /**
     * エンティティ化された特価詳細URLリストを返します。
     * @return array エンティティ化された特価詳細URLリスト
     */
    public function sp_detail_urls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_detail_urls);
    }

}
?>

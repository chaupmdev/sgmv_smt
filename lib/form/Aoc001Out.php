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
class Sgmov_Form_Aoc001Out
{
    /**
     * 本社ユーザーフラグ
     * @var string
     */
    public $raw_honsha_user_flag = '';
	
	 /**
     * 他社連携キャンペーンID
     * @var array
     */
    public $raw_oc_ids = array();
	

    /**
     * 他社連携キャンペーン名称
     * @var array
     */
    public $raw_oc_names = array();

    /**
     * 他社連携キャンペーン内容
     * @var array
     */
    public $raw_oc_contents = array();

    /**
     * 他社連携キャンペーン適用
     * @var array
     */
    public $raw_oc_applications = array();

 
    /**
     * エンティティ化された本社ユーザーフラグを返します。
     * @return string エンティティ化された本社ユーザーフラグ
     */
    public function honsha_user_flag()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_honsha_user_flag);
    }

    /**
     * エンティティ化された他社連携キャンペーンIDリストを返します。
     * @return array エンティティ化された他社連携キャンペーンIDリスト
     */
    public function oc_ids()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_oc_ids);
    }

    /**
     * エンティティ化された他社連携キャンペーン名称リストを返します。
     * @return array エンティティ化された他社連携キャンペーン名称リスト
     */
    public function oc_names()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_oc_names);
    }

    /**
     * エンティティ化された他社連携キャンペーン内容リストを返します。
     * @return array エンティティ化された他社連携キャンペーン内容リスト
     */
    public function oc_contents()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_oc_contents);
    }

    /**
     * エンティティ化された他社連携キャンペーン適用リストを返します。
     * @return array エンティティ化された他社連携キャンペーン適用リスト
     */
    public function oc_applications()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_oc_applications);
    }

    /**
     * エンティティ化された他社連携キャンペーン登録日付リストを返します。
     * @return array エンティティ化された他社連携登録日付エリアリスト
     */
    public function oc_createds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_oc_createds);
    }

   /**
     * エンティティ化された他社連携キャンペーン更新日付リストを返します。
     * @return array エンティティ化された他社連携キャンペーン更新日付エリアリスト
     */
    public function oc_modifieds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_oc_modifieds);
    }
}
?>

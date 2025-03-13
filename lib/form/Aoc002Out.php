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
class Sgmov_Form_Aoc002Out
{
    /**
     * 本社ユーザーフラグ
     * @var string
     */
    public $raw_honsha_user_flag = '';
 
     /**
     * 他社連携キャンペーンID
     * @var string
     */
    public $raw_oc_id = '';
 
     /**
     * 他社連携キャンペーン名称
     * @var string
     */
    public $raw_oc_name = '';
	
	 /**
     * 他社連携キャンペーンフラグ
     * @var array
     */
    public $raw_oc_flg = '';

    /**
     * 他社連携キャンペーン内容
     * @var array
     */
    public $raw_oc_content = '';

    /**
     * 他社連携キャンペーン適用
     * @var 'string
     */
    public $raw_oc_application = '';

 
    /**
     * エンティティ化された本社ユーザーフラグを返します。
     * @return string エンティティ化された本社ユーザーフラグ
     */
    public function honsha_user_flag()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_honsha_user_flag);
    }


    /**
     * エンティティ化された他社連携キャンペーン名称リストを返します。
     * @return string エンティティ化された他社連携キャンペーン名称リスト
     */
    public function oc_name()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_oc_name);
    }
	
    /**
     * エンティティ化された他社連携キャンペーンフラグリストを返します。
     * @return string エンティティ化された他社連携キャンペーン名称リスト
     */
    public function oc_flg()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_oc_flg);
    }

    /**
     * エンティティ化された他社連携キャンペーン内容リストを返します。
     * @return string エンティティ化された他社連携キャンペーン内容リスト
     */
    public function oc_content()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_oc_content);
    }

    /**
     * エンティティ化された他社連携キャンペーンキャンペーン適用リストを返します。
     * @return string エンティティ化された他社連携キャンペーンキャンペーン適用リスト
     */
    public function oc_application()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_oc_application);
    }

    /**
     * エンティティ化された他社連携キャンペーン登録日付リストを返します。
     * @return string エンティティ化された他社連携キャンペーン登録日付リスト
     */
    public function oc_created()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_oc_created);
    }

   /**
     * エンティティ化された他社連携キャンペーン更新日付リストを返します。
     * @return 'string エンティティ化された他社連携キャンペーン更新日付エリアリスト
     */
    public function oc_modified()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_oc_modified);
    }


}
?>

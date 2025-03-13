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
 * 特価編集名称入力画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Asp004Out
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
     * キャンセルボタンURL
     * @var string
     */
    public $raw_cancel_btn_url = '';

    /**
     * 特価種別
     * @var string
     */
    public $raw_sp_kind = '';

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
     * 特価登録者名
     * @var string
     */
    public $raw_sp_regist_user = '';

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
     * エンティティ化されたキャンセルボタンURLを返します。
     * @return string エンティティ化されたキャンセルボタンURL
     */
    public function cancel_btn_url()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cancel_btn_url);
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
     * エンティティ化された特価名称を返します。
     * @return string エンティティ化された特価名称
     */
    public function sp_name()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_name);
    }

    /**
     * エンティティ化された特価内容を返します。
     * @return string エンティティ化された特価内容
     */
    public function sp_content()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_content);
    }

    /**
     * エンティティ化された特価登録者名を返します。
     * @return string エンティティ化された特価登録者名
     */
    public function sp_regist_user()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_regist_user);
    }

}
?>

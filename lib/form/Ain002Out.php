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
 * 問合管理状況更新画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Ain002Out
{
    /**
     * 本社ユーザーフラグ
     * @var string
     */
    public $raw_honsha_user_flag = '';

    /**
     * 更新件数
     * @var string
     */
    public $raw_update_count = '';

    /**
     * 状況
     * @var string
     */
    public $raw_status = '';

    /**
     * 更新者
     * @var string
     */
    public $raw_updater_name = '';

    /**
     * エンティティ化された本社ユーザーフラグを返します。
     * @return string エンティティ化された本社ユーザーフラグ
     */
    public function honsha_user_flag()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_honsha_user_flag);
    }

    /**
     * エンティティ化された更新件数を返します。
     * @return string エンティティ化された更新件数
     */
    public function update_count()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_update_count);
    }

    /**
     * エンティティ化された状況を返します。
     * @return string エンティティ化された状況
     */
    public function status()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_status);
    }

    /**
     * エンティティ化された更新者を返します。
     * @return string エンティティ化された更新者
     */
    public function updater_name()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_updater_name);
    }

}
?>

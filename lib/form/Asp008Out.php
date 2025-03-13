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
 * 特価一括編集金額入力画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Asp008Out
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
     * 特価差額上限値
     * @var string
     */
    public $raw_sp_diff_max = '';

    /**
     * 特価差額下限値
     * @var string
     */
    public $raw_sp_diff_min = '';

    /**
     * 特価一括料金設定値
     * @var string
     */
    public $raw_sp_whole_charge = '';

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
     * エンティティ化された特価差額上限値を返します。
     * @return string エンティティ化された特価差額上限値
     */
    public function sp_diff_max()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_diff_max);
    }

    /**
     * エンティティ化された特価差額下限値を返します。
     * @return string エンティティ化された特価差額下限値
     */
    public function sp_diff_min()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_diff_min);
    }

    /**
     * エンティティ化された特価一括料金設定値を返します。
     * @return string エンティティ化された特価一括料金設定値
     */
    public function sp_whole_charge()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_whole_charge);
    }

}
?>

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
 * 訪問見積完了画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Pve003Out
{
    /**
     * メールアドレス
     * @var string
     */
    public $raw_mail = '';

    /**
     * 概算見積存在フラグ
     * @var string
     */
    public $raw_pre_exist_flag = '';


    public $raw_menu_personal = "";
    public $raw_apartment_cd_sel = "";


    /**
     * エンティティ化されたメールアドレスを返します。
     * @return string エンティティ化されたメールアドレス
     */
    public function mail()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_mail);
    }

    /**
     * エンティティ化された概算見積存在フラグを返します。
     * @return string エンティティ化された概算見積存在フラグ
     */
    public function pre_exist_flag()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_pre_exist_flag);
    }

    public function menu_personal() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_menu_personal);
    }
    public function apartment_cd_sel() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_apartment_cd_sel);
    }


}
?>

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
 * 訪問見積入力画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Mve002Out
{
    /**
     * プランコード選択値
     * @var string
     */
    public $raw_plan_cd_sel = '';

    /**
     * プランコードリスト
     * @var array
     */
    public $raw_plan_cds = array();

    /**
     * プランラベルリスト
     * @var array
     */
    public $raw_plan_lbls = array();

    /**
     * エンティティ化されたプランコード選択値を返します。
     * @return string エンティティ化されたプランコード選択値
     */
    public function plan_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_plan_cd_sel);
    }

    /**
     * エンティティ化されたプランコードリストを返します。
     * @return array エンティティ化されたプランコードリスト
     */
    public function plan_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_plan_cds);
    }

    /**
     * エンティティ化されたプランラベルリストを返します。
     * @return array エンティティ化されたプランラベルリスト
     */
    public function plan_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_plan_lbls);
    }

}
?>

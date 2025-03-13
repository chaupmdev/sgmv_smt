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
class Sgmov_Form_Mve001Out
{
    /**
     * コースコード選択値
     * @var string
     */
    public $raw_course_cd_sel = '';

    /**
     * コースコードリスト
     * @var array
     */
    public $raw_course_cds = array();

    /**
     * コースラベルリスト
     * @var array
     */
    public $raw_course_lbls = array();

    /**
     * エンティティ化されたコースコード選択値を返します。
     * @return string エンティティ化されたコースコード選択値
     */
    public function course_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_course_cd_sel);
    }

    /**
     * エンティティ化されたコースコードリストを返します。
     * @return array エンティティ化されたコースコードリスト
     */
    public function course_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_course_cds);
    }

    /**
     * エンティティ化されたコースラベルリストを返します。
     * @return array エンティティ化されたコースラベルリスト
     */
    public function course_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_course_lbls);
    }

}
?>

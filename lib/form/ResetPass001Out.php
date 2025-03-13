<?php
/**
 * @package    ClassDefFile
 * @author     GIapLN FPT Software
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useComponents(array('String'));
/**#@-*/

class Sgmov_Form_ResetPass001Out
{

    /**
     * メールアドレス
     * @var string
     */
    public $raw_email = '';


    /**
     * エンティティ化されたお問い合わせ種類コード選択値を返します。
     * @return string エンティティ化されたお問い合わせ種類コード選択値
     */
    public function email()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_email);
    }
}
?>

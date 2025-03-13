<?php
/**
 * @package    ClassDefFile
 * @author     GiapLN FPT software
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useComponents(array('String'));
/**#@-*/

class Sgmov_Form_Login001Out
{

    /**
     * メールアドレス
     * @var string
     */
    public $raw_email = '';

    /**
     * パスワード
     * @var string
     */
    public $raw_password = '';

 
    public function email()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_email);
    }

 
    public function password()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_password);
    }
}
?>

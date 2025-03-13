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

class Sgmov_Form_UserPassChange001Out
{
    //password_old
    public $raw_password_old = '';

    
    public function password_old()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_password_old);
    }
    
    //password
    public $raw_password = '';

   
    public function password()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_password);
    }
    
    //password_confirm
    public $raw_password_confirm = '';

    public function password_confirm()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_password_confirm);
    }
}
?>

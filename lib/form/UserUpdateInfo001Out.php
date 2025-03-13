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

class Sgmov_Form_UserUpdateInfo001Out
{
    public $raw_comiket_personal_name_sei = '';
    
    public function comiket_personal_name_sei()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_personal_name_sei);
    }

    public $raw_comiket_personal_name_mei = '';

    public function comiket_personal_name_mei()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_personal_name_mei);
    }

    public $raw_comiket_zip1 = '';

    public function comiket_zip1()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_zip1);
    }
 
    public $raw_comiket_zip2 = '';

   
    public function comiket_zip2()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_zip2);
    }
    
    public $raw_comiket_pref_cd_sel = '';
    public $raw_comiket_pref_cds = array();
    public $raw_comiket_pref_lbls = array();


    
     /**
     * �G���e�B�e�B�����ꂽ�s���{���R�[�h�I��l��Ԃ��܂��B
     * @return string �G���e�B�e�B�����ꂽ�s���{���R�[�h�I��l
     */
    public function comiket_pref_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_pref_cd_sel);
    }

    /**
     * �G���e�B�e�B�����ꂽ�s���{���R�[�h���X�g��Ԃ��܂��B
     * @return array �G���e�B�e�B�����ꂽ�s���{���R�[�h���X�g
     */
    public function comiket_pref_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_pref_cds);
    }

    /**
     * �G���e�B�e�B�����ꂽ�s���{���R�[�h���x�����X�g��Ԃ��܂��B
     * @return array �G���e�B�e�B�����ꂽ�s���{���R�[�h���x�����X�g
     */
    public function comiket_pref_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_pref_lbls);
    }
    
    //comiket_address
    public $raw_comiket_address = '';

    
    public function comiket_address()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_address);
    }
    
    //comiket_building
    public $raw_comiket_building = '';

    public function comiket_building()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_building);
    }
    
    //comiket_tel
    public $raw_comiket_tel = '';

    
    public function comiket_tel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_tel);
    }
    
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

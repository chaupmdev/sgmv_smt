<?php
/**
 * @package    ClassDefFile
 * @author     ��������
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useComponents(array('String'));
/**#@-*/

 /**
 * �K�〈�ϓ��͉�ʂ̏o�̓t�H�[���ł��B
 *
 * @package    Form
 * @author     ��������
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Mve005Out
{
    /**
     * �����O
     * @var string
     */
    public $raw_name = '';

    /**
     * �t���K�i
     * @var string
     */
    public $raw_furigana = '';

    /**
     * �d�b�ԍ�
     * @var string
     */
    public $raw_tel = '';

    /**
     * �d�b��ރR�[�h�I��l
     * @var string
     */
    public $raw_tel_type_cd_sel = '';

    /**
     * �d�b��ނ��̑�
     * @var string
     */
    public $raw_tel_other = '';

    /**
     * �d�b�A���\�R�[�h�I��l
     * @var string
     */
    public $raw_contact_available_cd_sel = '';

    /**
     * �d�b�A���\�J�n�����R�[�h�I��l
     * @var string
     */
    public $raw_contact_start_cd_sel = '';

    /**
     * �d�b�A���\�J�n�����R�[�h���X�g
     * @var array
     */
    public $raw_contact_start_cds = array();

    /**
     * �d�b�A���\�J�n�������x�����X�g
     * @var array
     */
    public $raw_contact_start_lbls = array();

    /**
     * �d�b�A���\�I�������R�[�h�I��l
     * @var string
     */
    public $raw_contact_end_cd_sel = '';

    /**
     * �d�b�A���\�I�������R�[�h���X�g
     * @var array
     */
    public $raw_contact_end_cds = array();

    /**
     * �d�b�A���\�I���������x�����X�g
     * @var array
     */
    public $raw_contact_end_lbls = array();

    /**
     * ���[���A�h���X
     * @var string
     */
    public $raw_mail = '';

    /**
     * ���l
     * @var string
     */
    public $raw_comment = '';

    /**
     * �G���e�B�e�B�����ꂽ�����O��Ԃ��܂��B
     * @return string �G���e�B�e�B�����ꂽ�����O
     */
    public function name()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_name);
    }

    /**
     * �G���e�B�e�B�����ꂽ�t���K�i��Ԃ��܂��B
     * @return string �G���e�B�e�B�����ꂽ�t���K�i
     */
    public function furigana()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_furigana);
    }

    /**
     * �G���e�B�e�B�����ꂽ�d�b�ԍ���Ԃ��܂��B
     * @return string �G���e�B�e�B�����ꂽ�d�b�ԍ�
     */
    public function tel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel);
    }

    /**
     * �G���e�B�e�B�����ꂽ�d�b��ރR�[�h�I��l��Ԃ��܂��B
     * @return string �G���e�B�e�B�����ꂽ�d�b��ރR�[�h�I��l
     */
    public function tel_type_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel_type_cd_sel);
    }

    /**
     * �G���e�B�e�B�����ꂽ�d�b��ނ��̑���Ԃ��܂��B
     * @return string �G���e�B�e�B�����ꂽ�d�b��ނ��̑�
     */
    public function tel_other()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel_other);
    }

    /**
     * �G���e�B�e�B�����ꂽ�d�b�A���\�R�[�h�I��l��Ԃ��܂��B
     * @return string �G���e�B�e�B�����ꂽ�d�b�A���\�R�[�h�I��l
     */
    public function contact_available_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_available_cd_sel);
    }

    /**
     * �G���e�B�e�B�����ꂽ�d�b�A���\�J�n�����R�[�h�I��l��Ԃ��܂��B
     * @return string �G���e�B�e�B�����ꂽ�d�b�A���\�J�n�����R�[�h�I��l
     */
    public function contact_start_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_start_cd_sel);
    }

    /**
     * �G���e�B�e�B�����ꂽ�d�b�A���\�J�n�����R�[�h���X�g��Ԃ��܂��B
     * @return array �G���e�B�e�B�����ꂽ�d�b�A���\�J�n�����R�[�h���X�g
     */
    public function contact_start_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_start_cds);
    }

    /**
     * �G���e�B�e�B�����ꂽ�d�b�A���\�J�n�������x�����X�g��Ԃ��܂��B
     * @return array �G���e�B�e�B�����ꂽ�d�b�A���\�J�n�������x�����X�g
     */
    public function contact_start_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_start_lbls);
    }

    /**
     * �G���e�B�e�B�����ꂽ�d�b�A���\�I�������R�[�h�I��l��Ԃ��܂��B
     * @return string �G���e�B�e�B�����ꂽ�d�b�A���\�I�������R�[�h�I��l
     */
    public function contact_end_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_end_cd_sel);
    }

    /**
     * �G���e�B�e�B�����ꂽ�d�b�A���\�I�������R�[�h���X�g��Ԃ��܂��B
     * @return array �G���e�B�e�B�����ꂽ�d�b�A���\�I�������R�[�h���X�g
     */
    public function contact_end_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_end_cds);
    }

    /**
     * �G���e�B�e�B�����ꂽ�d�b�A���\�I���������x�����X�g��Ԃ��܂��B
     * @return array �G���e�B�e�B�����ꂽ�d�b�A���\�I���������x�����X�g
     */
    public function contact_end_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_end_lbls);
    }

    /**
     * �G���e�B�e�B�����ꂽ���[���A�h���X��Ԃ��܂��B
     * @return string �G���e�B�e�B�����ꂽ���[���A�h���X
     */
    public function mail()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_mail);
    }

    /**
     * �G���e�B�e�B�����ꂽ���l��Ԃ��܂��B
     * @return string �G���e�B�e�B�����ꂽ���l
     */
    public function comment()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comment);
    }

}
?>

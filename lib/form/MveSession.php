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
Sgmov_Lib::useForms(array('Error', 'Mve001In', 'Mve002In', 'Mve003In', 'Mve004In', 'Mve005In'));
/**#@-*/

 /**
 * 携帯訪問見積もりフォームのセッションフォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_MveSession
{
//    public $gamen_id;

    public $mve001_error;

    /**
     * MVE001��̓t�H�[��
     * @var Sgmov_Form_Mve001In
     */
    public $mve001_in;

    /**
     * MVE001���
     * @var string
     */
    public $mve001_status;

    /**
     * MVE002�G���[�t�H�[��
     * @var Sgmov_Form_Error
     */
    public $mve002_error;

    /**
     * MVE002��̓t�H�[��
     * @var Sgmov_Form_Mve002In
     */
    public $mve002_in;

    /**
     * MVE002���
     * @var string
     */
    public $mve002_status;

    /**
     * MVE003�G���[�t�H�[��
     * @var Sgmov_Form_Error
     */
    public $mve003_error;

    /**
     * MVE003��̓t�H�[��
     * @var Sgmov_Form_Mve003In
     */
    public $mve003_in;

    /**
     * MVE003���
     * @var string
     */
    public $mve003_status;

    /**
     * MVE004�G���[�t�H�[��
     * @var Sgmov_Form_Error
     */
    public $mve004_error;

    /**
     * MVE004��̓t�H�[��
     * @var Sgmov_Form_Mve004In
     */
    public $mve004_in;

    /**
     * MVE004
     * @var string
     */
    public $mve004_status;

    /**
     * エラーフォーム
     * @var Sgmov_Form_Error
     */
    public $mve005_error;

    /**
     * MVE005��̓t�H�[��
     * @var Sgmov_Form_Mve005In
     */
    public $mve005_in;

    /**
     * MVE005���
     * @var string
     */
    public $mve005_status;

}
?>

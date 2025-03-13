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
Sgmov_Lib::useForms(array('Pcv001In', 'Error'));
/**#@-*/

 /**
 * 訪問見積もり申し込みフォーム（法人向け）のセッションフォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_PcvSession
{
    /**
     * 入力フォーム
     * @var Sgmov_Form_Pcv001In
     */
    public $in;

    /**
     * 状態
     * @var string
     */
    public $status;

    /**
     * エラーフォーム
     * @var Sgmov_Form_Error
     */
    public $error;

}
?>

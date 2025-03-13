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
Sgmov_Lib::useForms(array('Pcm001In', 'Error'));
/**#@-*/

 /**
 * お問い合わせ・申し込みフォーム（法人向け引越輸送）のセッションフォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_PcmSession
{
    /**
     * 入力フォーム
     * @var Sgmov_Form_Pcm001In
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

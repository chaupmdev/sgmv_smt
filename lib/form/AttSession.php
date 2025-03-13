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
Sgmov_Lib::useForms(array('Error', 'Att002In'));
/**#@-*/

/**
 * ツアー発着地マスタ設定のセッションフォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Att2Session {

    /**
     * エラーフォーム
     * @var Sgmov_Form_Error
     */
    public $error;

    /**
     * 入力フォーム
     * @var Sgmov_Form_Att002In
     */
    public $in;

    /**
     * 状態
     * @var string
     */
    public $status;
}
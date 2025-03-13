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
Sgmov_Lib::useForms(array('Error', 'Atc002In'));
/**#@-*/

/**
 * ツアー配送料金マスタ設定のセッションフォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Atc2Session {

    /**
     * エラーフォーム
     * @var Sgmov_Form_Error
     */
    public $error;

    /**
     * 入力フォーム
     * @var Sgmov_Form_Atc002In
     */
    public $in;

    /**
     * 状態
     * @var string
     */
    public $status;
}
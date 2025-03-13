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
Sgmov_Lib::useComponents(array('String'));
/**#@-*/

/**
 * ツアー会社マスタ設定画面の入力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Aap002In {

    /**
     * マンションID
     * @var string
     */
    public $apartment_id   = '';

    /**
     * マンションコード
     * @var string
     */
    public $apartment_cd   = '';

    /**
     * マンション名
     * @var string
     */
    public $apartment_name = '';

    /**
     * マンション郵便番号1
     * @var string
     */
    public $zip1           = '';

    /**
     * マンション郵便番号2
     * @var string
     */
    public $zip2           = '';

    /**
     * マンション住所
     * @var string
     */
    public $address        = '';

    /**
     * 取引先コード
     * @var string
     */
    public $agency_cd      = '';
}
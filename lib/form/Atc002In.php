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
 * ツアー配送料金マスタ設定画面の入力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Atc002In {

    /**
     * ツアー配送料金ID
     * @var string
     */
    public $travel_delivery_charge_id = '';

    /**
     * 船名コード選択値
     * @var string
     */
    public $travel_agency_cd_sel = '';

    /**
     * 乗船日名コード選択値
     * @var string
     */
    public $travel_cd_sel = '';

    /**
     * ツアー発着地コード選択値
     * @var string
     */
    public $travel_terminal_cd_sel = '';

    /**
     * ツアー配送料金
     * @var array
     */
    public $delivery_charg = '';
}
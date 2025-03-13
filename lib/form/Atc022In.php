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
 * ツアー配送料金マスタコピー画面の入力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Atc022In {

    /**
     * ツアー配送料金ID
     * @var string
     */
    public $travel_delivery_charge_id = '';

    /**
     * 船名コード選択値
     * @var string
     */
    public $travel_agency_from_cd_sel = '';

    /**
     * 乗船日名コード選択値
     * @var string
     */
    public $travel_from_cd_sel = '';

    /**
     * ツアー発着地コード選択値
     * @var string
     */
    public $travel_terminal_from_cd_sel = '';

    /**
     * 船名コード選択値
     * @var string
     */
    public $travel_agency_to_cd_sel = '';

    /**
     * 乗船日名コード選択値
     * @var string
     */
    public $travel_to_cd_sel = '';

    /**
     * ツアー発着地コード選択値
     * @var string
     */
    public $travel_terminal_to_cd_sel = '';
}
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
class Sgmov_Form_Ata002In {

    /**
     * 船名ID
     * @var string
     */
    public $travel_agency_id = '';

    /**
     * 船名コード
     * @var string
     */
    public $travel_agency_cd = '';

    /**
     * 船名
     * @var string
     */
    public $travel_agency_name = '';
}
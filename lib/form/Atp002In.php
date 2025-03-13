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
class Sgmov_Form_Atp002In {

    /**
     * ツアーエリアID
     * @var string
     */
    public $travel_province_id = '';

    /**
     * ツアーエリアコード
     * @var string
     */
    public $travel_province_cd = '';

    /**
     * ツアーエリア名
     * @var string
     */
    public $travel_province_name = '';

    /**
     * 都道府県ID
     * @var array()
     */
    public $prefecture_ids = '';
}
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
class Sgmov_Form_Atr002In {

    /**
     * ツアーID
     * @var string
     */
    public $travel_id = '';

    /**
     * ツアーコード
     * @var string
     */
    public $travel_cd = '';

    /**
     * 乗船日名
     * @var string
     */
    public $travel_name = '';

    /**
     * 船名コード選択値
     * @var string
     */
    public $travel_agency_cd_sel = '';

    /**
     * 往復便割引
     * @var string
     */
    public $round_trip_discount = '';

    /**
     * リピータ割引
     * @var string
     */
    public $repeater_discount = '';

    /**
     * 乗船日
     * @var string
     */
    public $embarkation_date = '';

    /**
     * 掲載開始日
     * @var string
     */
    public $publish_begin_date = '';
}
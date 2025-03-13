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
class Sgmov_Form_Att002In {

    /**
     * ツアー発着地ID
     * @var string
     */
    public $travel_terminal_id = '';

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
     * ツアー発着地コード
     * @var string
     */
    public $travel_terminal_cd = '';

    /**
     * ツアー発着地名
     * @var string
     */
    public $travel_terminal_name = '';

    /**
     * 郵便番号1
     * @var string
     */
    public $zip1 = '';

    /**
     * 郵便番号2
     * @var string
     */
    public $zip2 = '';

    /**
     * 都道府県コード選択値
     * @var string
     */
    public $pref_cd_sel = '';

    /**
     * 市区町村
     * @var string
     */
    public $address = '';

    /**
     * 番地・建物名
     * @var string
     */
    public $building = '';

    /**
     * 発着店名(営業所名)
     * @var string
     */
    public $store_name = '';

    /**
     * 電話番号
     * @var string
     */
    public $tel = '';

    /**
     * 発着区分
     * @var string
     */
    public $terminal_cd = '';

    /**
     * 出発日
     * @var string
     */
    public $departure_date = '';

    /**
     * 出発時刻
     * @var string
     */
    public $departure_time = '';

    /**
     * 到着日
     * @var string
     */
    public $arrival_date = '';

    /**
     * 到着時刻
     * @var string
     */
    public $arrival_time = '';

    /**
     * 往路 顧客コード
     * @var string
     */
    public $departure_client_cd = '';

    /**
     * 往路 顧客コード枝番
     * @var string
     */
    public $departure_client_branch_cd = '';

    /**
     * 復路 顧客コード
     * @var string
     */
    public $arrival_client_cd = '';

    /**
     * 復路 顧客コード枝番
     * @var string
     */
    public $arrival_client_branch_cd = '';
}
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
 * ツアー配送料金一覧画面の出力フォームです。
 * TODO TwigやSmartyなどのテンプレートエンジンの導入を検討する
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Atc001Out {

    /**
     * 本社ユーザーフラグ
     * @var string
     */
    public $raw_honsha_user_flag = '';

    /**
     * 船名コード選択値
     * @var string
     */
    public $raw_travel_agency_cd_sel = '';

    /**
     * 船名コードリスト
     * @var array
     */
    public $raw_travel_agency_cds = array();

    /**
     * 船名コードラベルリスト
     * @var array
     */
    public $raw_travel_agency_lbls = array();

    /**
     * ツアー配送料ID
     * @var array
     */
    public $raw_travel_delivery_charge_ids = array();

    /**
     * ツアー発着地名
     * @var array
     */
    public $raw_travel_terminal_names = array();

    /**
     * 出発日リスト
     * @var array
     */
    public $raw_departure_dates = array();

    /**
     * 到着日リスト
     * @var array
     */
    public $raw_arrival_dates = array();

    /**
     * エンティティ化された本社ユーザーフラグを返します。
     * @return string エンティティ化された本社ユーザーフラグ
     */
    public function honsha_user_flag() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_honsha_user_flag);
    }

    /**
     * エンティティ化された船名コード選択値を返します。
     * @return string エンティティ化された船名コード選択値
     */
    public function travel_agency_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_agency_cd_sel);
    }

    /**
     * エンティティ化された船名コードリストを返します。
     * @return array エンティティ化された船名コードリスト
     */
    public function travel_agency_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_agency_cds);
    }

    /**
     * エンティティ化された船名コードラベルリストを返します。
     * @return array エンティティ化された船名コードラベルリスト
     */
    public function travel_agency_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_agency_lbls);
    }

    /**
     * エンティティ化されたツアー配送料IDを返します。
     * @return array エンティティ化されたツアー配送料ID
     */
    public function travel_delivery_charge_ids() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_delivery_charge_ids);
    }

    /**
     * エンティティ化されたツアー発着地名を返します。
     * @return array エンティティ化されたツアー発着地名
     */
    public function travel_terminal_names() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_terminal_names);
    }

    /**
     * エンティティ化された出発日リストを返します。
     * @return array エンティティ化された出発日リスト
     */
    public function departure_dates() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_departure_dates);
    }

    /**
     * エンティティ化された到着日リストを返します。
     * @return array エンティティ化された到着日リスト
     */
    public function arrival_dates() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_arrival_dates);
    }
}
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
 * ツアー配送料金設定画面の出力フォームです。
 * TODO TwigやSmartyなどのテンプレートエンジンの導入を検討する
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Atc002Out {

    /**
     * 本社ユーザーフラグ
     * @var string
     */
    public $raw_honsha_user_flag = '';

    /**
     * ツアー配送料金ID
     * @var string
     */
    public $raw_travel_delivery_charge_id = '';

    /**
     * 船名
     * @var string
     */
    public $raw_travel_agency_name = '';

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
     * 乗船日名
     * @var string
     */
    public $raw_travel_name = '';

    /**
     * 乗船日名コード選択値
     * @var string
     */
    public $raw_travel_cd_sel = '';

    /**
     * 乗船日名コードリスト
     * @var array
     */
    public $raw_travel_cds = array();

    /**
     * 乗船日名コードラベルリスト
     * @var array
     */
    public $raw_travel_lbls = array();

    /**
     * ツアー発着地名
     * @var string
     */
    public $raw_travel_terminal_name = '';

    /**
     * ツアー発着地コード選択値
     * @var string
     */
    public $raw_travel_terminal_cd_sel = '';

    /**
     * ツアー発着地コードリスト
     * @var array
     */
    public $raw_travel_terminal_cds = array();

    /**
     * ツアー発着地コードラベルリスト
     * @var array
     */
    public $raw_travel_terminal_lbls = array();

    /**
     * 往復便割引リスト
     * @var array
     */
    public $raw_round_trip_discounts = array();

    /**
     * エリアID
     * @var array
     */
    public $raw_travel_provinces_ids = array();

    /**
     * エリア名
     * @var array
     */
    public $raw_travel_provinces_names = array();

    /**
     * 都道府県名
     * @var array
     */
    public $raw_prefecture_names = array();

    /**
     * ツアー配送料金IDリスト
     * @var array
     */
    public $raw_travel_delivery_charge_ids = array();

    /**
     * 配送料金リスト
     * @var array
     */
    public $raw_delivery_chargs = array();

    /**
     * エンティティ化された本社ユーザーフラグを返します。
     * @return string エンティティ化された本社ユーザーフラグ
     */
    public function honsha_user_flag() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_honsha_user_flag);
    }

    /**
     * エンティティ化されたツアー配送料金IDを返します。
     * @return string エンティティ化されたツアー配送料金ID
     */
    public function travel_delivery_charge_id() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_delivery_charge_id);
    }

    /**
     * エンティティ化された船名を返します。
     * @return string エンティティ化された船名
     */
    public function travel_agency_name() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_agency_name);
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
     * エンティティ化された乗船日名を返します。
     * @return string エンティティ化された乗船日名
     */
    public function travel_name() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_name);
    }

    /**
     * エンティティ化された乗船日名コード選択値を返します。
     * @return string エンティティ化された乗船日名コード選択値
     */
    public function travel_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_cd_sel);
    }

    /**
     * エンティティ化された乗船日名コードリストを返します。
     * @return array エンティティ化された乗船日名コードリスト
     */
    public function travel_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_cds);
    }

    /**
     * エンティティ化された乗船日名コードラベルリストを返します。
     * @return array エンティティ化された乗船日名コードラベルリスト
     */
    public function travel_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_lbls);
    }

    /**
     * エンティティ化されたツアー発着地名を返します。
     * @return string エンティティ化されたツアー発着地名
     */
    public function travel_terminal_name() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_terminal_name);
    }

    /**
     * エンティティ化されたツアー発着地コード選択値を返します。
     * @return string エンティティ化されたツアー発着地コード選択値
     */
    public function travel_terminal_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_terminal_cd_sel);
    }

    /**
     * エンティティ化されたツアー発着地コードリストを返します。
     * @return array エンティティ化されたツアー発着地コードリスト
     */
    public function travel_terminal_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_terminal_cds);
    }

    /**
     * エンティティ化されたツアー発着地コードラベルリストを返します。
     * @return array エンティティ化されたツアー発着地コードラベルリスト
     */
    public function travel_terminal_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_terminal_lbls);
    }

    /**
     * エンティティ化された往復便割引リストを返します。
     * @return array エンティティ化された往復便割引リスト
     */
    public function round_trip_discounts() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_round_trip_discounts);
    }

    /**
     * エンティティ化されたエリアIDを返します。
     * @return array エンティティ化されたエリアID
     */
    public function travel_provinces_ids() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_provinces_ids);
    }

    /**
     * エンティティ化されたエリア名を返します。
     * @return array エンティティ化されたエリア名
     */
    public function travel_provinces_names() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_provinces_names);
    }

    /**
     * エンティティ化された都道府県名を返します。
     * @return array エンティティ化された都道府県名
     */
    public function prefecture_names() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_prefecture_names);
    }

    /**
     * エンティティ化されたツアー配送料金IDリストを返します。
     * @return array エンティティ化されたツアー配送料金IDリスト
     */
    public function travel_delivery_charge_ids() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_delivery_charge_ids);
    }

    /**
     * エンティティ化された配送料金リストを返します。
     * @return array エンティティ化された配送料金リスト
     */
    public function delivery_chargs() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_delivery_chargs);
    }
}
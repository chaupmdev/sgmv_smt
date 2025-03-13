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
 * ツアーマスタ一覧画面の出力フォームです。
 * TODO TwigやSmartyなどのテンプレートエンジンの導入を検討する
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Atr001Out {

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
     * 乗船日IDリスト
     * @var array
     */
    public $raw_travel_ids = array();

    /**
     * 乗船日コードリスト
     * @var array
     */
    public $raw_travel_cds = array();

    /**
     * 乗船日名リスト
     * @var array
     */
    public $raw_travel_names = array();

    /**
     * ツアー会社IDリスト
     * @var array
     */
    public $raw_travel_agency_ids = array();

    /**
     * 往復便割引リスト
     * @var array
     */
    public $raw_round_trip_discounts = array();

    /**
     * リピータ割引リスト
     * @var array
     */
    public $raw_repeater_discounts = array();

    /**
     * 乗船日リスト
     * @var array
     */
    public $raw_embarkation_dates = array();

    /**
     * 掲載開始日リスト
     * @var array
     */
    public $raw_publish_begin_dates = array();

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
     * エンティティ化された乗船日IDリストを返します。
     * @return array エンティティ化された乗船日IDリスト
     */
    public function travel_ids() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_ids);
    }

    /**
     * エンティティ化された乗船日コードリストを返します。
     * @return array エンティティ化された乗船日コードリスト
     */
    public function travel_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_cds);
    }

    /**
     * エンティティ化された乗船日名リストを返します。
     * @return array エンティティ化された乗船日名リスト
     */
    public function travel_names() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_names);
    }

    /**
     * エンティティ化されたツアー会社IDリストを返します。
     * @return array エンティティ化されたツアー会社IDリスト
     */
    public function travel_agency_ids() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_agency_ids);
    }

    /**
     * エンティティ化された往復便割引リストを返します。
     * @return array エンティティ化された往復便割引リスト
     */
    public function round_trip_discounts() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_round_trip_discounts);
    }

    /**
     * エンティティ化されたリピータ割引リストを返します。
     * @return array エンティティ化されたリピータ割引リスト
     */
    public function repeater_discounts() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_repeater_discounts);
    }

    /**
     * エンティティ化された乗船日リストを返します。
     * @return array エンティティ化された乗船日リスト
     */
    public function embarkation_dates() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_embarkation_dates);
    }

    /**
     * エンティティ化された掲載開始日リストを返します。
     * @return array エンティティ化された掲載開始日リスト
     */
    public function publish_begin_dates() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_publish_begin_dates);
    }
}
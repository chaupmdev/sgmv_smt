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
 * ツアーマスタ設定画面の出力フォームです。
 * TODO TwigやSmartyなどのテンプレートエンジンの導入を検討する
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Atr002Out {

    /**
     * 本社ユーザーフラグ
     * @var string
     */
    public $raw_honsha_user_flag = '';

    /**
     * ツアーID
     * @var string
     */
    public $raw_travel_id = '';

    /**
     * ツアーコード
     * @var string
     */
    public $raw_travel_cd = '';

    /**
     * 乗船日名
     * @var string
     */
    public $raw_travel_name = '';

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
     * 往復便割引
     * @var string
     */
    public $raw_round_trip_discount = '';

    /**
     * リピータ割引
     * @var string
     */
    public $raw_repeater_discount = '';

    /**
     * 乗船日
     * @var string
     */
    public $raw_embarkation_date = '';

    /**
     * 掲載開始日
     * @var string
     */
    public $raw_publish_begin_date = '';

    /**
     * エンティティ化された本社ユーザーフラグを返します。
     * @return string エンティティ化された本社ユーザーフラグ
     */
    public function honsha_user_flag() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_honsha_user_flag);
    }

    /**
     * エンティティ化されたツアーIDを返します。
     * @return string エンティティ化されたツアーID
     */
    public function travel_id() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_id);
    }

    /**
     * エンティティ化されたツアーコードを返します。
     * @return string エンティティ化されたツアーコード
     */
    public function travel_cd() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_cd);
    }

    /**
     * エンティティ化された乗船日名を返します。
     * @return string エンティティ化された乗船日名
     */
    public function travel_name() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_name);
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
     * エンティティ化された往復便割引を返します。
     * @return string エンティティ化された往復便割引
     */
    public function round_trip_discount() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_round_trip_discount);
    }

    /**
     * エンティティ化されたリピータ割引を返します。
     * @return string エンティティ化されたリピータ割引
     */
    public function repeater_discount() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_repeater_discount);
    }

    /**
     * エンティティ化された乗船日を返します。
     * @return string エンティティ化された乗船日
     */
    public function embarkation_date() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_embarkation_date);
    }

    /**
     * エンティティ化された掲載開始日を返します。
     * @return string エンティティ化された掲載開始日
     */
    public function publish_begin_date() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_publish_begin_date);
    }
}
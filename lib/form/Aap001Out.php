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
 * マンションマスタ一覧画面の出力フォームです。
 * TODO TwigやSmartyなどのテンプレートエンジンの導入を検討する
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Aap001Out {

    /**
     * 本社ユーザーフラグ
     * @var string
     */
    public $raw_honsha_user_flag = '';

    /**
     * マンションID
     * @var array
     */
    public $raw_apartment_ids = array();

    /**
     * マンションコード
     * @var array
     */
    public $raw_apartment_cds = array();

    /**
     * マンション名
     * @var array
     */
    public $raw_apartment_names = array();

    /**
     * マンション郵便番号
     * @var array
     */
    public $raw_apartment_zip_codes = array();

    /**
     * マンション住所
     * @var array
     */
    public $raw_apartment_address = array();

    /**
     * 取引先コード
     * @var array
     */
    public $raw_apartment_agency_cds = array();

    /**
     * エンティティ化された本社ユーザーフラグを返します。
     * @return string エンティティ化された本社ユーザーフラグ
     */
    public function honsha_user_flag() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_honsha_user_flag);
    }

    /**
     * エンティティ化されたマンションIDを返します。
     * @return string エンティティ化されたマンションID
     */
    public function apartment_ids() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_apartment_ids);
    }

    /**
     * エンティティ化されたマンションコードを返します。
     * @return string エンティティ化されたマンションコード
     */
    public function apartment_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_apartment_cds);
    }

    /**
     * エンティティ化されたマンション名を返します。
     * @return string エンティティ化されたマンション名
     */
    public function apartment_names() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_apartment_names);
    }

    /**
     * エンティティ化されたマンションを返します。
     * @return string エンティティ化されたマンション名
     */
    public function apartment_zip_codes() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_apartment_zip_codes);
    }

    /**
     * エンティティ化されたマンション住所を返します。
     * @return string エンティティ化されたマンション住所
     */
    public function apartment_address() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_apartment_address);
    }

    /**
     * エンティティ化された取引先コードを返します。
     * @return string エンティティ化された取引先コード
     */
    public function apartment_agency_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_apartment_agency_cds);
    }
}
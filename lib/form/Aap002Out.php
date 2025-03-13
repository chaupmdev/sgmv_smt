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
 * マンションマスタ設定画面の出力フォームです。
 * TODO TwigやSmartyなどのテンプレートエンジンの導入を検討する
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Aap002Out {

    /**
     * 本社ユーザーフラグ
     * @var string
     */
    public $raw_honsha_user_flag = '';

    /**
     * マンションID
     * @var string
     */
    public $raw_apartment_id = '';

    /**
     * マンションコード
     * @var string
     */
    public $raw_apartment_cd = '';

    /**
     * マンション名
     * @var string
     */
    public $raw_apartment_name = '';

    /**
     * マンション郵便番号1
     * @var string
     */
    public $raw_zip1 = '';

    /**
     * マンション郵便番号2
     * @var string
     */
    public $raw_zip2 = '';

    /**
     * マンション住所
     * @var string
     */
    public $raw_address = '';

    /**
     * 取引先コード
     * @var string
     */
    public $raw_agency_cd = '';

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
    public function apartment_id() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_apartment_id);
    }

    /**
     * エンティティ化されたマンションコードを返します。
     * @return string エンティティ化されたマンションコード
     */
    public function apartment_cd() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_apartment_cd);
    }

    /**
     * エンティティ化されたマンション名を返します。
     * @return string エンティティ化されたマンション名
     */
    public function apartment_name() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_apartment_name);
    }

    /**
     * エンティティ化されたマンション郵便番号1を返します。
     * @return string エンティティ化されたマンション郵便番号1
     */
    public function zip1() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_zip1);
    }

    /**
     * エンティティ化されたマンション郵便番号2を返します。
     * @return string エンティティ化されたマンション郵便番号2
     */
    public function zip2() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_zip2);
    }

    /**
     * エンティティ化されたマンション住所を返します。
     * @return string エンティティ化されたマンション市区町村
     */
    public function address() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_address);
    }

    /**
     * エンティティ化されたマンション取引先コードを返します。
     * @return string エンティティ化されたマンション取引先コード
     */
    public function agency_cd() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_agency_cd);
    }
}
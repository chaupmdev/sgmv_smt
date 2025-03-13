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
 * ツアーエリア一覧画面の出力フォームです。
 * TODO TwigやSmartyなどのテンプレートエンジンの導入を検討する
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Atp001Out {

    /**
     * 本社ユーザーフラグ
     * @var string
     */
    public $raw_honsha_user_flag = '';

    /**
     * エリアID
     * @var array
     */
    public $raw_travel_provinces_ids = array();

    /**
     * エリアコード
     * @var array
     */
    public $raw_travel_provinces_cds = array();

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
     * エンティティ化された本社ユーザーフラグを返します。
     * @return string エンティティ化された本社ユーザーフラグ
     */
    public function honsha_user_flag() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_honsha_user_flag);
    }

    /**
     * エンティティ化されたエリアIDを返します。
     * @return array エンティティ化されたエリアID
     */
    public function travel_provinces_ids() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_provinces_ids);
    }

    /**
     * エンティティ化されたエリアコードを返します。
     * @return array エンティティ化されたエリアコード
     */
    public function travel_provinces_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_provinces_cds);
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
}
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
 * ツアーエリア削除確認画面の出力フォームです。
 * TODO TwigやSmartyなどのテンプレートエンジンの導入を検討する
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Atp012Out {

    /**
     * 本社ユーザーフラグ
     * @var string
     */
    public $raw_honsha_user_flag = '';

    /**
     * ツアーエリアコードID
     * @var string
     */
    public $raw_travel_province_id = '';

    /**
     * ツアーエリアコードコード
     * @var string
     */
    public $raw_travel_province_cd = '';

    /**
     * ツアーエリアコード名
     * @var string
     */
    public $raw_travel_province_name = '';

    /**
     * 都道府県ID
     * @var array
     */
    public $raw_prefecture_ids = array();

    /**
     * 都道府県名
     * @var array
     */
    public $raw_prefecture_names = array();

    /**
     * 都道府県選択
     * @var array
     */
    public $raw_selected_cds = array();

    /**
     * エンティティ化された本社ユーザーフラグを返します。
     * @return string エンティティ化された本社ユーザーフラグ
     */
    public function honsha_user_flag() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_honsha_user_flag);
    }

    /**
     * エンティティ化されたツアーエリアコードIDを返します。
     * @return string エンティティ化されたツアーエリアコードID
     */
    public function travel_province_id() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_province_id);
    }

    /**
     * エンティティ化されたツアーエリアコードコードを返します。
     * @return string エンティティ化されたツアーエリアコードコード
     */
    public function travel_province_cd() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_province_cd);
    }

    /**
     * エンティティ化されたツアーエリアコード名を返します。
     * @return string エンティティ化されたツアーエリアコード名
     */
    public function travel_province_name() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_province_name);
    }

    /**
     * エンティティ化された都道府県IDを返します。
     * @return array エンティティ化された都道府県ID
     */
    public function prefecture_ids() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_prefecture_ids);
    }

    /**
     * エンティティ化された都道府県名を返します。
     * @return array エンティティ化された都道府県名
     */
    public function prefecture_names() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_prefecture_names);
    }

    /**
     * エンティティ化された都道府県選択を返します。
     * @return array エンティティ化された都道府県選択
     */
    public function selected_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_selected_cds);
    }
}
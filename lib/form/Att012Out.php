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
 * ツアー発着地マスタ削除確認画面の出力フォームです。
 * TODO TwigやSmartyなどのテンプレートエンジンの導入を検討する
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Att012Out {

    /**
     * 本社ユーザーフラグ
     * @var string
     */
    public $raw_honsha_user_flag = '';

    /**
     * ツアー発着地ID
     * @var string
     */
    public $raw_travel_terminal_id = '';

    /**
     * 船名
     * @var string
     */
    public $raw_travel_agency_name = '';

    /**
     * 乗船日名
     * @var string
     */
    public $raw_travel_name = '';

    /**
     * ツアー発着地コード
     * @var string
     */
    public $raw_travel_terminal_cd = '';

    /**
     * ツアー発着地名
     * @var string
     */
    public $raw_travel_terminal_name = '';

    /**
     * 郵便番号1
     * @var string
     */
    public $raw_zip1 = '';

    /**
     * 郵便番号2
     * @var string
     */
    public $raw_zip2 = '';

    /**
     * 都道府県名
     * @var string
     */
    public $raw_pref_name = '';

    /**
     * 市区町村
     * @var string
     */
    public $raw_address = '';

    /**
     * 番地・建物名
     * @var string
     */
    public $raw_building = '';

    /**
     * 発着店名(営業所名)
     * @var string
     */
    public $raw_store_name = '';

    /**
     * 電話番号
     * @var string
     */
    public $raw_tel = '';

    /**
     * 発着区分
     * @var string
     */
    public $raw_terminal_cd = '';

    /**
     * 出発日
     * @var string
     */
    public $raw_departure_date = '';

    /**
     * 出発時刻
     * @var string
     */
    public $raw_departure_time = '';

    /**
     * 到着日
     * @var string
     */
    public $raw_arrival_date = '';

    /**
     * 到着時刻
     * @var string
     */
    public $raw_arrival_time = '';

    /**
     * 往路 顧客コード
     * @var string
     */
    public $raw_departure_client_cd = '';

    /**
     * 往路 顧客コード枝番
     * @var string
     */
    public $raw_departure_client_branch_cd = '';

    /**
     * 復路 顧客コード
     * @var string
     */
    public $raw_arrival_client_cd = '';

    /**
     * 復路 顧客コード枝番
     * @var string
     */
    public $raw_arrival_client_branch_cd = '';

    /**
     * エンティティ化された本社ユーザーフラグを返します。
     * @return string エンティティ化された本社ユーザーフラグ
     */
    public function honsha_user_flag() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_honsha_user_flag);
    }

    /**
     * エンティティ化されたツアー発着地IDを返します。
     * @return string エンティティ化されたツアー発着地ID
     */
    public function travel_terminal_id() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_terminal_id);
    }

    /**
     * エンティティ化された船名を返します。
     * @return string エンティティ化された船名コード選択値
     */
    public function travel_agency_name() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_agency_name);
    }

    /**
     * エンティティ化された乗船日名を返します。
     * @return string エンティティ化された乗船日名
     */
    public function travel_name() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_name);
    }

    /**
     * エンティティ化されたツアー発着地コードを返します。
     * @return string エンティティ化されたツアー発着地コード
     */
    public function travel_terminal_cd() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_terminal_cd);
    }

    /**
     * エンティティ化されたツアー発着地名を返します。
     * @return string エンティティ化されたツアー発着地名
     */
    public function travel_terminal_name() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_terminal_name);
    }

    /**
     * エンティティ化された郵便番号1を返します。
     * @return string エンティティ化された郵便番号1
     */
    public function zip1() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_zip1);
    }

    /**
     * エンティティ化された郵便番号2を返します。
     * @return string エンティティ化された郵便番号2
     */
    public function zip2() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_zip2);
    }

    /**
     * エンティティ化された都道府県名を返します。
     * @return string エンティティ化された都道府県名
     */
    public function pref_name() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_pref_name);
    }

    /**
     * エンティティ化された市区町村を返します。
     * @return string エンティティ化された市区町村
     */
    public function address() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_address);
    }

    /**
     * エンティティ化された番地・建物名を返します。
     * @return string エンティティ化された番地・建物名
     */
    public function building() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_building);
    }

    /**
     * エンティティ化された発着店名(営業所名)を返します。
     * @return string エンティティ化された発着店名(営業所名)
     */
    public function store_name() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_store_name);
    }

    /**
     * エンティティ化された電話番号を返します。
     * @return string エンティティ化された電話番号
     */
    public function tel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel);
    }

    /**
     * エンティティ化された発着区分を返します。
     * @return string エンティティ化された発着区分
     */
    public function terminal_cd() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_terminal_cd);
    }

    /**
     * エンティティ化された出発日を返します。
     * @return string エンティティ化された出発日
     */
    public function departure_date() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_departure_date);
    }

    /**
     * エンティティ化された出発時刻を返します。
     * @return string エンティティ化された出発時刻
     */
    public function departure_time() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_departure_time);
    }

    /**
     * エンティティ化された到着日を返します。
     * @return string エンティティ化された到着日
     */
    public function arrival_date() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_arrival_date);
    }

    /**
     * エンティティ化された到着時刻を返します。
     * @return string エンティティ化された到着時刻
     */
    public function arrival_time() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_arrival_time);
    }

    /**
     * エンティティ化された往路 顧客コードを返します。
     * @return string エンティティ化された往路 顧客コード
     */
    public function departure_client_cd() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_departure_client_cd);
    }

    /**
     * エンティティ化された往路 顧客コード枝番を返します。
     * @return string エンティティ化された往路 顧客コード枝番
     */
    public function departure_client_branch_cd() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_departure_client_branch_cd);
    }

    /**
     * エンティティ化された復路 顧客コードを返します。
     * @return string エンティティ化された復路 顧客コード
     */
    public function arrival_client_cd() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_arrival_client_cd);
    }

    /**
     * エンティティ化された復路 顧客コード枝番を返します。
     * @return string エンティティ化された復路 顧客コード枝番
     */
    public function arrival_client_branch_cd() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_arrival_client_branch_cd);
    }
}
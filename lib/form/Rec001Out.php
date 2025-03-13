<?php
/**
 * @package    ClassDefFile
 * @author     自動生成
 * @copyright  2020-2020 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useComponents(array('String'));
/**#@-*/

/**
 * 入力画面の出力フォームです。
 * TODO TwigやSmartyなどのテンプレートエンジンの導入を検討する
 * @package    Form
 * @author     自動生成
 * @copyright  2020-2020 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Rec001Out {
	 /**
     * お名前（漢字）
     * @var string
     */
    public $raw_personal_name = '';

    /**
     * お名前（フリガナ）
     * @var string
     */
    public $raw_personal_name_furi = '';

    /**
     * 性別
     * @var string
     */
    public $raw_sei = '';

    /**
     * 年齢
     * @var string
     */
    public $raw_age  = array();

    /**
     * 郵便番号
     * @var string
     */
    public $raw_zip1 = '';


    /**
     * 郵便番号
     * @var string
     */
    public $raw_zip2 = '';

    /**
     * 都道府県
     * @var array
     */
    public $raw_pref_cds = array();

    /**
     * 都道府県コードラベルリスト
     * @var array
     */
    public $raw_pref_lbls = array();

    /**
     * 都道府県
     * @var string
     */
    public $raw_pref_id = '';

    /**
     * 都道府県
     * @var array
     */
    public $raw_pref_nm = '';

    /**
     * 市区町村
     * @var string
     */
    public $raw_address = '';

    /**
     * 番地・建物名・部屋番号
     * @var string
     */
    public $raw_building = '';

    /**
     * 電話番号
     * @var string
     */
    public $raw_tel = '';

    /**
     * メールアドレス
     * @var string
     */
    public $raw_mail = '';

    /**
     * 希望雇用形態
     * @var string
     */
    public $raw_employ_cd = '';

    /**
     * 希望勤務地
     * @var string
     */
    public $raw_center_id = '';

    /**
     * 希望職種
     * @var string
     */
    public $raw_occupation_cd = '';

    /**
     * ご質問など
     * @var string
     */
    public $raw_question = '';

    /**
     * ご質問など
     * @var string
     */
    public $raw_wage = '';

    /**
     * 希望勤務地
     * @var string
     */
    public $raw_center_id_hidden = '';

    /**
     * 希望職種
     * @var string
     */
    public $raw_occupation_cd_hidden = '';

    /**
     * 現在の就業状況
     * @var string
     */
    public $raw_current_employment_status = '';

    /**
     * 連絡可能な時間帯
     * @var string
     */
    public $raw_contact_time = array();

    /**
     * エンティティ化されたお名前（漢字）を返します。
     * @return string エンティティ化された識別コード選択値
     */
    public function personal_name() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_personal_name);
    }

    /**
     * エンティティ化されたお名前（フリガナ）返します。
     * @return string エンティティ化された識別コード選択値
     */
    public function personal_name_furi() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_personal_name_furi);
    }

    /**
     * エンティティ化された性別を返します。
     * @return string エンティティ化された識別コード選択値
     */
    public function sei() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sei);
    }

    /**
     * エンティティ化された年齢を返します。
     * @return string エンティティ化された識別コード選択値
     */
    public function age() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_age);
    }


    /**
     * エンティティ化された郵便番号を返します。
     * @return array エンティティ化された都道府県コードリスト
     */
    public function zip1() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_zip1);
    }


    /**
     * エンティティ化された郵便番号を返します。
     * @return array エンティティ化された都道府県コードリスト
     */
    public function zip2() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_zip2);
    }

    /**
     * エンティティ化された都道府県コードリストを返します。
     * @return array エンティティ化された都道府県コードリスト
     */
    public function pref_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_pref_cds);
    }

    /**
     * エンティティ化された都道府県コードラベルリストを返します。
     * @return array エンティティ化された都道府県コードラベルリスト
     */
    public function pref_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_pref_lbls);
    }

    /**
     * エンティティ化された都道府県コード選択値を返します。
     * @return string エンティティ化された都道府県コード選択値
     */
    public function pref_id() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_pref_id);
    }

    /**
     * エンティティ化された都道府県コードラベルリストを返します。
     * @return array エンティティ化された都道府県コードラベルリスト
     */
    public function pref_nm() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_pref_nm);
    }
    /**
     * エンティティ化された市区町村を返します。
     * @return array エンティティ化された都道府県コードリスト
     */
    public function address() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_address);
    }

    /**
     * エンティティ化された番地・建物名・部屋番号を返します。
     * @return array エンティティ化された都道府県コードリスト
     */
    public function building() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_building);
    }

    /**
     * エンティティ化された電話番号を返します。
     * @return array エンティティ化された都道府県コードリスト
     */
    public function tel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel);
    }

    /**
     * エンティティ化されたメールアドレスを返します。
     * @return array エンティティ化された都道府県コードリスト
     */
    public function mail() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_mail);
    }

    /**
     * エンティティ化された希望雇用形態を返します。
     * @return array エンティティ化された都道府県コードリスト
     */
    public function employ_cd() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_employ_cd);
    }

    /**
     * エンティティ化された希望勤務地を返します。
     * @return array エンティティ化された都道府県コードリスト
     */
    public function center_id() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_center_id);
    }

    /**
     * エンティティ化された希望職種を返します。
     * @return array エンティティ化された都道府県コードリスト
     */
    public function occupation_cd() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_occupation_cd);
    }

    /**
     * エンティティ化されたご質問などを返します。
     * @return array エンティティ化された都道府県コードリスト
     */
    public function question() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_question);
    }

    /**
     * エンティティ化されたなど希望勤務地を返します。
     * @return array エンティティ化された都道府県コードリスト
     */
    public function center_id_hidden() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_center_id_hidden);
    }

    /**
     * エンティティ化された希望職種などを返します。
     * @return array エンティティ化された都道府県コードリスト
     */
    public function occupation_cd_hidden() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_occupation_cd_hidden);
    }

    /**
     * エンティティ化された現在の就業状況などを返します。
     * @return string エンティティ化された現在の就業状況選択値
     */
    public function current_employment_status() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_current_employment_status);
    }

    /**
     * エンティティ化された連絡可能な時間帯などを返します。
     * @return string エンティティ化された連絡可能な時間帯選択値
     */
    public function contact_time() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_time);
    }
    
}
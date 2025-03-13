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
class Sgmov_Form_Ren001Out {
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
     * ご質問など
     * @var string
     */
    public $raw_question = '';

    /**
     * ご質問など
     * @var string
     */
    public $raw_wage = '';


    
    public $raw_date_of_birth_year_cd_sel = '';
    public $raw_date_of_birth_year_cds = array();
    public $raw_date_of_birth_year_lbls = array();
    
    public $raw_date_of_birth_month_cd_sel = '';
    public $raw_date_of_birth_month_cds = array();
    public $raw_date_of_birth_month_lbls = array();
    
    public $raw_date_of_birth_day_cd_sel = '';
    public $raw_date_of_birth_day_cds = array();
    public $raw_date_of_birth_day_lbls = array();
    

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
     * エンティティ化されたご質問などを返します。
     * @return array エンティティ化された都道府県コードリスト
     */
    public function question() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_question);
    }

    
    
    public function date_of_birth_year_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_date_of_birth_year_cd_sel);
    }

    
    public function date_of_birth_year_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_date_of_birth_year_cds);
    }

    
    public function date_of_birth_year_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_date_of_birth_year_lbls);
    }
    
    public function date_of_birth_month_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_date_of_birth_month_cd_sel);
    }

    
    public function date_of_birth_month_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_date_of_birth_month_cds);
    }

    
    public function date_of_birth_month_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_date_of_birth_month_lbls);
    }
    
    public function date_of_birth_day_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_date_of_birth_day_cd_sel);
    }

    
    public function date_of_birth_day_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_date_of_birth_day_cds);
    }

    
    public function date_of_birth_day_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_date_of_birth_day_lbls);
    }
    
    
}
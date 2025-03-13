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
 * 入力画面の出力フォームです。
 * TODO TwigやSmartyなどのテンプレートエンジンの導入を検討する
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Ptu001Out {

    /**
     * お名前 姓
     * @var string
     */
    public $raw_surname = '';

    /**
     * お名前 名
     * @var string
     */
    public $raw_forename = '';

    /**
     * 電話番号1
     * @var string
     */
    public $raw_tel1 = '';

    /**
     * 電話番号2
     * @var string
     */
    public $raw_tel2 = '';

    /**
     * 電話番号3
     * @var string
     */
    public $raw_tel3 = '';

    /**
     * FAX番号1
     * @var string
     */
    public $raw_fax1 = '';

    /**
     * FAX番号2
     * @var string
     */
    public $raw_fax2 = '';

    /**
     * FAX番号3
     * @var string
     */
    public $raw_fax3 = '';

    /**
     * メールアドレス
     * @var string
     */
    public $raw_mail = '';

    /**
     * メールアドレス確認
     * @var string
     */
    public $raw_retype_mail = '';

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
     * 都道府県コード選択値
     * @var string
     */
    public $raw_pref_cd_sel = '';

    /**
     * 都道府県コードリスト
     * @var array
     */
    public $raw_pref_cds = array();

    /**
     * 都道府県コードラベルリスト
     * @var array
     */
    public $raw_pref_lbls = array();

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
    * お名前 姓
    * @var string
    */
    public $raw_surname_hksaki = '';

    /**
     * お名前 名
     * @var string
     */
    public $raw_forename_hksaki = '';

    /**
    * 郵便番号1
    * @var string
    */
    public $raw_zip1_hksaki = '';

    /**
     * 郵便番号2
     * @var string
     */
    public $raw_zip2_hksaki = '';

    /**
    * 都道府県コード選択値
    * @var string
    */
    public $raw_pref_cd_sel_hksaki = '';

    /**
    * 市区町村
    * @var string
    */
    public $raw_address_hksaki = '';

    /**
     * 番地・建物名
     * @var string
     */
    public $raw_building_hksaki = '';

    /**
    * 電話番号1
    * @var string
    */
    public $raw_tel1_hksaki = '';

    /**
     * 電話番号2
     * @var string
     */
    public $raw_tel2_hksaki = '';

    /**
     * 電話番号3
     * @var string
     */
    public $raw_tel3_hksaki = '';

    /**
     * 不在時連絡先1
     * @var string
     */
    public $raw_tel1_fuzai_hksaki = '';

    /**
     * 不在時連絡先2
     * @var string
     */
    public $raw_tel2_fuzai_hksaki = '';

    /**
     * 不在時連絡先3
     * @var string
     */
    public $raw_tel3_fuzai_hksaki = '';

    /**
     * お引取り予定日コード選択値
     * @var string
     */
    public $raw_hikitori_yotehiji_date_year_cd_sel = '';

    /**
     * お引取り予定日コードリスト
     * @var array
     */
    public $raw_hikitori_yotehiji_date_year_cds = array();

    /**
     * お引取り予定日コードラベルリスト
     * @var array
     */
    public $raw_hikitori_yotehiji_date_year_lbls = array();

    /**
     * お引取り予定月コード選択値
     * @var string
     */
    public $raw_hikitori_yotehiji_date_month_cd_sel = '';

    /**
     * お引取り予定月コードリスト
     * @var array
     */
    public $raw_hikitori_yotehiji_date_month_cds = array();

    /**
     * お引取り予定月コードラベルリスト
     * @var array
     */
    public $raw_hikitori_yotehiji_date_month_lbls = array();

    /**
     * お引取り予定日コード選択値
     * @var string
     */
    public $raw_hikitori_yotehiji_date_day_cd_sel = '';

    /**
     * お引取り予定日コードリスト
     * @var array
     */
    public $raw_hikitori_yotehiji_date_day_cds = array();

    /**
     * お引取り予定日コードラベルリスト
     * @var array
     */
    public $raw_hikitori_yotehiji_date_day_lbls = array();

    /**
     * お引取り予定コード選択値
     * @var string
     */
    public $raw_hikitori_yotehiji_time_cd_sel = '';

    /**
     * お引取り予定コードリスト
     * @var array
     */
    public $raw_hikitori_yotehiji_time_cds = array();

    /**
     * お引取り予定コードラベルリスト
     * @var array
     */
    public $raw_hikitori_yotehiji_time_lbls = array();

    /**
    * お引取り予定コード選択値
    * @var string
    */
    public $raw_hikitori_yoteji_sel = '';

    /**
    * お引取り予定コード選択値
    * @var string
    */
    public $raw_hikitori_yotehiji_justime_cd_sel = '';

    /**
     * お引取り予定コードリスト
     * @var array
     */
    public $raw_hikitori_yotehiji_justime_cds = array();

    /**
     * お引取り予定コードラベルリスト
     * @var array
     */
    public $raw_hikitori_yotehiji_justime_lbls = array();

    /**
     * お引越し予定年コード選択値
     * @var string
     */
    public $raw_hikoshi_yotehiji_date_year_cd_sel = '';

    /**
     * お引越し予定年コードリスト
     * @var array
     */
    public $raw_hikoshi_yotehiji_date_year_cds = array();

    /**
     * お引越し予定年コードラベルリスト
     * @var array
     */
    public $raw_hikoshi_yotehiji_date_year_lbls = array();

    /**
     * お引越し予定月コード選択値
     * @var string
     */
    public $raw_hikoshi_yotehiji_date_month_cd_sel = '';

    /**
     * お引越し予定月コードリスト
     * @var array
     */
    public $raw_hikoshi_yotehiji_date_month_cds = array();

    /**
     * お引越し予定月コードラベルリスト
     * @var array
     */
    public $raw_hikoshi_yotehiji_date_month_lbls = array();

    /**
     * お引越し予定日コード選択値
     * @var string
     */
    public $raw_hikoshi_yotehiji_date_day_cd_sel = '';

    /**
     * お引越し予定日コードリスト
     * @var array
     */
    public $raw_hikoshi_yotehiji_date_day_cds = array();

    /**
     * お引越し予定日コードラベルリスト
     * @var array
     */
    public $raw_hikoshi_yotehiji_date_day_lbls = array();

    /**
     * お引越し予定時刻コード選択値
     * @var string
     */
    public $raw_hikoshi_yotehiji_time_cd_sel = '';

    /**
     * お引越し予定時刻コードリスト
     * @var array
     */
    public $raw_hikoshi_yotehiji_time_cds = array();

    /**
     * お引越し予定時刻コードラベルリスト
     * @var array
     */
    public $raw_hikoshi_yotehiji_time_lbls = array();

    /**
    * お引越し予定コード選択値
    * @var string
    */
    public $raw_hikoshi_yoteji_sel = '';

    /**
    * お引越し予定時刻コード選択値
    * @var string
    */
    public $raw_hikoshi_yotehiji_justime_cd_sel = '';

    /**
     * お引越し予定時刻コードリスト
     * @var array
     */
    public $raw_hikoshi_yotehiji_justime_cds = array();

    /**
     * お引越し予定時刻コードラベルリスト
     * @var array
     */
    public $raw_hikoshi_yotehiji_justime_lbls = array();


    /**
    * カーゴ台数
    * @var string
    */
    public $raw_cago_daisu = '';

    /**
    * 単品輸送品目選択値
    * @var string
    */
    public $raw_tanhin_cd_sel = array();

    public $raw_tanNmFree = array();

    /**
     * 単品輸送品目コードリスト
     * @var array
     */
    public $raw_tanhin_cds = array();

    /**
     * 単品輸送品目ラベルリスト
     * @var array
     */
    public $raw_tanhin_lbls = array();

    /**
     * 消費税
     * @var string
     */
    public $raw_shohizei = '';

    /**
     * 開始日
     * @var string
     */
    public $raw_frmDt = '';

    /**
    * 終了日
    * @var string
    */
    public $raw_toDt = '';

    /**
    * エンティティ化された単品輸送品目選択値を返します。
    * @return string エンティティ化された都道府県コード選択値
    */
    public function tanNmFree() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_tanNmFree);
    }

    /**
    * エンティティ化された単品輸送品目選択値を返します。
    * @return string エンティティ化された都道府県コード選択値
    */
    public function tanhin_cd_sel() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_tanhin_cd_sel);
    }

    /**
     * エンティティ化された単品輸送品目コードリストを返します。
     * @return array エンティティ化された都道府県コードリスト
     */
    public function tanhin_cds() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_tanhin_cds);
    }

    /**
     * エンティティ化された単品輸送品目ラベルリストを返します。
     * @return array エンティティ化された都道府県コードラベルリスト
     */
    public function tanhin_lbls() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_tanhin_lbls);
    }

    /**
    * 搬出OPT TEXT
    * @var string
    */
    public $raw_textHanshutsu = array();

    /**
    * 搬出OPT Checkbox
    * @var string
    */
    public $raw_checkboxHanshutsu = array();

    public function textHanshutsu() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_textHanshutsu);
    }
    public function checkboxHanshutsu() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_checkboxHanshutsu);
    }

    /**
    * 搬入OPT TEXT
    * @var string
    */
    public $raw_textHannyu = array();

    /**
     * 搬入OPT Checkbox
     * @var string
     */
    public $raw_checkboxHannyu = array();

    public function textHannyu() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_textHannyu);
    }
    public function checkboxHannyu() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_checkboxHannyu);
    }

    public $raw_binshu_cd = '';

    public function binshu_cd() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_binshu_cd);
    }

    /**
     * お支払方法コード選択値
     * @var string
     */
    public $raw_payment_method_cd_sel = '';

    /**
     * お支払店コード選択値
     * @var string
     */
    public $raw_convenience_store_cd_sel = '';

    /**
     * お支払店コードリスト
     * @var array
     */
    public $raw_convenience_store_cds = array();

    /**
     * お支払店コードラベルリスト
     * @var array
     */
    public $raw_convenience_store_lbls = array();

    /**
     * クレジットカード番号
     * @var string
     */
    public $raw_card_number = '';

    /**
     * 有効期限 月
     * @var string
     */
    public $raw_card_expire_month_cd_sel = '';

    /**
     * 有効期限 年
     * @var string
     */
    public $raw_card_expire_year_cd_sel = '';

    /**
     * セキュリティコード
     * @var string
     */
    public $raw_security_cd = '';

    public $raw_hanshutsu_cds = array();
    public $raw_hanshutsu_komoku_names = array();
    public $raw_hanshutsu_sagyo_names = array();
    public $raw_hanshutsu_tankas = array();
    public $raw_hanshutsu_input_kbns = array();
    public $raw_hanshutsu_bikos = array();

    public function hanshutsu_cds() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_hanshutsu_cds);
    }
    public function hanshutsu_komoku_names() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_hanshutsu_komoku_names);
    }
    public function hanshutsu_sagyo_names() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_hanshutsu_sagyo_names);
    }
    public function hanshutsu_tankas() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_hanshutsu_tankas);
    }
    public function hanshutsu_input_kbns() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_hanshutsu_input_kbns);
    }
    public function hanshutsu_bikos() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_hanshutsu_bikos);
    }

    public $raw_hannyu_cds = array();
    public $raw_hannyu_komoku_names = array();
    public $raw_hannyu_sagyo_names = array();
    public $raw_hannyu_tankas = array();
    public $raw_hannyu_input_kbns = array();
    public $raw_hannyu_bikos = array();

    public function hannyu_cds() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_hannyu_cds);
    }
    public function hannyu_komoku_names() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_hannyu_komoku_names);
    }
    public function hannyu_sagyo_names() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_hannyu_sagyo_names);
    }
    public function hannyu_tankas() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_hannyu_tankas);
    }
    public function hannyu_input_kbns() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_hannyu_input_kbns);
    }
    public function hannyu_bikos() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_hannyu_bikos);
    }

    /**
     * エンティティ化されたお名前 姓を返します。
     * @return string エンティティ化されたお名前 姓
     */
    public function surname() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_surname);
    }

    /**
     * エンティティ化されたお名前 名を返します。
     * @return string エンティティ化されたお名前 名
     */
    public function forename() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_forename);
    }

    /**
     * エンティティ化された電話番号1を返します。
     * @return string エンティティ化された電話番号1
     */
    public function tel1() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel1);
    }

    /**
     * エンティティ化された電話番号2を返します。
     * @return string エンティティ化された電話番号2
     */
    public function tel2() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel2);
    }

    /**
     * エンティティ化された電話番号3を返します。
     * @return string エンティティ化された電話番号3
     */
    public function tel3() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel3);
    }

    /**
    * エンティティ化されたFAX番号1を返します。
    * @return string エンティティ化されたFAX番号1
    */
    public function fax1() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_fax1);
    }

    /**
     * エンティティ化されたFAX番号2を返します。
     * @return string エンティティ化されたFAX番号2
     */
    public function fax2() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_fax2);
    }

    /**
    * エンティティ化されたFAX番号3を返します。
    * @return string エンティティ化されたFAX番号3
    */
    public function fax3() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_fax3);
    }

    /**
     * エンティティ化されたメールアドレスを返します。
     * @return string エンティティ化されたメールアドレス
     */
    public function mail() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_mail);
    }

    /**
     * エンティティ化されたメールアドレス確認を返します。
     * @return string エンティティ化されたメールアドレス確認
     */
    public function retype_mail() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_retype_mail);
    }

    /**
     * エンティティ化された郵便番号を返します。
     * @return string エンティティ化された郵便番号
     */
    public function zip1() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_zip1);
    }

    /**
     * エンティティ化された郵便番号を返します。
     * @return string エンティティ化された郵便番号
     */
    public function zip2() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_zip2);
    }

    /**
     * エンティティ化された都道府県コード選択値を返します。
     * @return string エンティティ化された都道府県コード選択値
     */
    public function pref_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_pref_cd_sel);
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
    * エンティティ化されたお名前 姓を返します。
    * @return string エンティティ化されたお名前 姓
    */
    public function surname_hksaki() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_surname_hksaki);
    }

    /**
     * エンティティ化されたお名前 名を返します。
     * @return string エンティティ化されたお名前 名
     */
    public function forename_hksaki() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_forename_hksaki);
    }

    /**
    * エンティティ化された郵便番号を返します。
    * @return string エンティティ化された郵便番号
    */
    public function zip1_hksaki() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_zip1_hksaki);
    }

    /**
     * エンティティ化された郵便番号を返します。
     * @return string エンティティ化された郵便番号
     */
    public function zip2_hksaki() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_zip2_hksaki);
    }

    /**
    * エンティティ化された都道府県コード選択値を返します。
    * @return string エンティティ化された都道府県コード選択値
    */
    public function pref_cd_sel_hksaki() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_pref_cd_sel_hksaki);
    }

    /**
    * エンティティ化された市区町村を返します。
    * @return string エンティティ化された市区町村
    */
    public function address_hksaki() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_address_hksaki);
    }

    /**
     * エンティティ化された番地・建物名を返します。
     * @return string エンティティ化された番地・建物名
     */
    public function building_hksaki() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_building_hksaki);
    }

    /**
    * エンティティ化された電話番号1を返します。
    * @return string エンティティ化された電話番号1
    */
    public function tel1_hksaki() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_tel1_hksaki);
    }

    /**
     * エンティティ化された電話番号2を返します。
     * @return string エンティティ化された電話番号2
     */
    public function tel2_hksaki() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_tel2_hksaki);
    }

    /**
     * エンティティ化された電話番号3を返します。
     * @return string エンティティ化された電話番号3
     */
    public function tel3_hksaki() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_tel3_hksaki);
    }

    /**
     * エンティティ化されたFAX番号1を返します。
     * @return string エンティティ化されたFAX番号1
     */
    public function tel1_fuzai_hksaki() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_tel1_fuzai_hksaki);
    }

    /**
     * エンティティ化されたFAX番号2を返します。
     * @return string エンティティ化されたFAX番号2
     */
    public function tel2_fuzai_hksaki() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_tel2_fuzai_hksaki);
    }

    /**
     * エンティティ化されたFAX番号3を返します。
     * @return string エンティティ化されたFAX番号3
     */
    public function tel3_fuzai_hksaki() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_tel3_fuzai_hksaki);
    }

    /**
    * エンティティ化されたカーゴ台数を返します。
    * @return string エンティティ化されたカーゴ台数
    */
    public function cago_daisu() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_cago_daisu);
    }

    /**
    * エンティティ化された消費税を返します。
    * @return string エンティティ化された消費税
    */
    public function shohizei() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_shohizei);
    }

    /**
    * エンティティ化された開始日を返します。
    * @return string エンティティ化された開始日
    */
    public function frmDt() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_frmDt);
    }

    /**
    * エンティティ化された終了日を返します。
    * @return string エンティティ化された終了日
    */
    public function toDt() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_toDt);
    }

    /**
     * エンティティ化されたお引取り予定年コード選択値を返します。
     * @return string エンティティ化されたお引取り予定年コード選択値
     */
    public function hikitori_yotehiji_date_year_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_hikitori_yotehiji_date_year_cd_sel);
    }

    /**
     * エンティティ化されたお引取り予定年コードリストを返します。
     * @return array エンティティ化されたお引取り予定年コードリスト
     */
    public function hikitori_yotehiji_date_year_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_hikitori_yotehiji_date_year_cds);
    }

    /**
     * エンティティ化されたお引取り予定年ラベルリストを返します。
     * @return array エンティティ化されたお引取り予定年ラベルリスト
     */
    public function hikitori_yotehiji_date_year_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_hikitori_yotehiji_date_year_lbls);
    }

    /**
     * エンティティ化されたお引取り予定月コード選択値を返します。
     * @return string エンティティ化されたお引取り予定月コード選択値
     */
    public function hikitori_yotehiji_date_month_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_hikitori_yotehiji_date_month_cd_sel);
    }

    /**
     * エンティティ化されたお引取り予定月コードリストを返します。
     * @return array エンティティ化されたお引取り予定月コードリスト
     */
    public function hikitori_yotehiji_date_month_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_hikitori_yotehiji_date_month_cds);
    }

    /**
     * エンティティ化されたお引取り予定月ラベルリストを返します。
     * @return array エンティティ化されたお引取り予定月ラベルリスト
     */
    public function hikitori_yotehiji_date_month_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_hikitori_yotehiji_date_month_lbls);
    }

    /**
     * エンティティ化されたお引取り予定日コード選択値を返します。
     * @return string エンティティ化されたお引取り予定日コード選択値
     */
    public function hikitori_yotehiji_date_day_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_hikitori_yotehiji_date_day_cd_sel);
    }

    /**
     * エンティティ化されたお引取り予定日コードリストを返します。
     * @return array エンティティ化されたお引取り予定日コードリスト
     */
    public function hikitori_yotehiji_date_day_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_hikitori_yotehiji_date_day_cds);
    }

    /**
     * エンティティ化されたお引取り予定日ラベルリストを返します。
     * @return array エンティティ化されたお引取り予定日ラベルリスト
     */
    public function hikitori_yotehiji_date_day_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_hikitori_yotehiji_date_day_lbls);
    }

    /**
     * エンティティ化されたお引取り予定開始時刻コード選択値を返します。
     * @return string エンティティ化されたお引取り予定開始時刻コード選択値
     */
    public function hikitori_yotehiji_time_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_hikitori_yotehiji_time_cd_sel);
    }

    /**
     * エンティティ化されたお引取り予定開始時刻コードリストを返します。
     * @return array エンティティ化されたお引取り予定開始時刻コードリスト
     */
    public function hikitori_yotehiji_time_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_hikitori_yotehiji_time_cds);
    }

    /**
     * エンティティ化されたお引取り予定開始時刻ラベルリストを返します。
     * @return array エンティティ化されたお引取り予定開始時刻ラベルリスト
     */
    public function hikitori_yotehiji_time_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_hikitori_yotehiji_time_lbls);
    }

    /**
    * エンティティ化されたお引越し予定時刻コード選択値を返します。
    * @return string エンティティ化されたお引越し予定時刻コード選択値
    */
    public function hikitori_yoteji_sel() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_hikitori_yoteji_sel);
    }

    /**
    * エンティティ化されたお引取り予定開始時刻コード選択値を返します。
    * @return string エンティティ化されたお引取り予定開始時刻コード選択値
    */
    public function hikitori_yotehiji_justime_cd_sel() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_hikitori_yotehiji_justime_cd_sel);
    }

    /**
     * エンティティ化されたお引取り予定開始時刻コードリストを返します。
     * @return array エンティティ化されたお引取り予定開始時刻コードリスト
     */
    public function hikitori_yotehiji_justime_cds() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_hikitori_yotehiji_justime_cds);
    }

    /**
     * エンティティ化されたお引取り予定開始時刻ラベルリストを返します。
     * @return array エンティティ化されたお引取り予定開始時刻ラベルリスト
     */
    public function hikitori_yotehiji_justime_lbls() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_hikitori_yotehiji_justime_lbls);
    }

    /**
     * エンティティ化されたお引越し予定年コード選択値を返します。
     * @return string エンティティ化されたお引越し予定年コード選択値
     */
    public function hikoshi_yotehiji_date_year_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_hikoshi_yotehiji_date_year_cd_sel);
    }

    /**
     * エンティティ化されたお引越し予定年コードリストを返します。
     * @return array エンティティ化されたお引越し予定年コードリスト
     */
    public function hikoshi_yotehiji_date_year_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_hikoshi_yotehiji_date_year_cds);
    }

    /**
     * エンティティ化されたお引越し予定年ラベルリストを返します。
     * @return array エンティティ化されたお引越し予定年ラベルリスト
     */
    public function hikoshi_yotehiji_date_year_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_hikoshi_yotehiji_date_year_lbls);
    }

    /**
     * エンティティ化されたお引越し予定月コード選択値を返します。
     * @return string エンティティ化されたお引越し予定月コード選択値
     */
    public function hikoshi_yotehiji_date_month_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_hikoshi_yotehiji_date_month_cd_sel);
    }

    /**
     * エンティティ化されたお引越し予定月コードリストを返します。
     * @return array エンティティ化されたお引越し予定月コードリスト
     */
    public function hikoshi_yotehiji_date_month_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_hikoshi_yotehiji_date_month_cds);
    }

    /**
     * エンティティ化されたお引越し予定月ラベルリストを返します。
     * @return array エンティティ化されたお引越し予定月ラベルリスト
     */
    public function hikoshi_yotehiji_date_month_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_hikoshi_yotehiji_date_month_lbls);
    }

    /**
     * エンティティ化されたお引越し予定日コード選択値を返します。
     * @return string エンティティ化されたお引越し予定日コード選択値
     */
    public function hikoshi_yotehiji_date_day_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_hikoshi_yotehiji_date_day_cd_sel);
    }

    /**
     * エンティティ化されたお引越し予定日コードリストを返します。
     * @return array エンティティ化されたお引越し予定日コードリスト
     */
    public function hikoshi_yotehiji_date_day_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_hikoshi_yotehiji_date_day_cds);
    }

    /**
     * エンティティ化されたお引越し予定日ラベルリストを返します。
     * @return array エンティティ化されたお引越し予定日ラベルリスト
     */
    public function hikoshi_yotehiji_date_day_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_hikoshi_yotehiji_date_day_lbls);
    }

    /**
     * エンティティ化されたお引越し予定時刻コード選択値を返します。
     * @return string エンティティ化されたお引越し予定時刻コード選択値
     */
    public function hikoshi_yotehiji_time_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_hikoshi_yotehiji_time_cd_sel);
    }

    /**
     * エンティティ化されたお引越し予定時刻コードリストを返します。
     * @return array エンティティ化されたお引越し予定時刻コードリスト
     */
    public function hikoshi_yotehiji_time_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_hikoshi_yotehiji_time_cds);
    }

    /**
     * エンティティ化されたお引越し予定時刻ラベルリストを返します。
     * @return array エンティティ化されたお引越し予定時刻ラベルリスト
     */
    public function hikoshi_yotehiji_time_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_hikoshi_yotehiji_time_lbls);
    }

    /**
    * エンティティ化されたお引越し予定時刻コード選択値を返します。
    * @return string エンティティ化されたお引越し予定時刻コード選択値
    */
    public function hikoshi_yoteji_sel() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_hikoshi_yoteji_sel);
    }

    /**
    * エンティティ化されたお引越し予定時刻コード選択値を返します。
    * @return string エンティティ化されたお引越し予定時刻コード選択値
    */
    public function hikoshi_yotehiji_justime_cd_sel() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_hikoshi_yotehiji_justime_cd_sel);
    }

    /**
     * エンティティ化されたお引越し予定時刻コードリストを返します。
     * @return array エンティティ化されたお引越し予定時刻コードリスト
     */
    public function hikoshi_yotehiji_justime_cds() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_hikoshi_yotehiji_justime_cds);
    }

    /**
     * エンティティ化されたお引越し予定時刻ラベルリストを返します。
     * @return array エンティティ化されたお引越し予定時刻ラベルリスト
     */
    public function hikoshi_yotehiji_justime_lbls() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_hikoshi_yotehiji_justime_lbls);
    }

    /**
     * エンティティ化されたお支払方法コード選択値を返します。
     * @return string エンティティ化されたお支払方法コード選択値
     */
    public function payment_method_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_payment_method_cd_sel);
    }

    /**
     * エンティティ化されたお支払店コード選択値を返します。
     * @return string エンティティ化されたお支払店コード選択値
     */
    public function convenience_store_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_convenience_store_cd_sel);
    }

    /**
     * エンティティ化されたお支払店コードリストを返します。
     * @return string エンティティ化されたお支払店コードリスト
     */
    public function convenience_store_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_convenience_store_cds);
    }

    /**
     * エンティティ化されたお支払店ラベルリストを返します。
     * @return string エンティティ化されたお支払店ラベルリスト
     */
    public function convenience_store_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_convenience_store_lbls);
    }

    /**
     * エンティティ化されたクレジットカード番号を返します。
     * @return string エンティティ化されたクレジットカード番号
     */
    public function card_number() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_card_number);
    }

    /**
     * エンティティ化された有効期限 月を返します。
     * @return string エンティティ化された有効期限 月
     */
    public function card_expire_month_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_card_expire_month_cd_sel);
    }

    /**
     * エンティティ化された有効期限 年を返します。
     * @return string エンティティ化された有効期限 年
     */
    public function card_expire_year_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_card_expire_year_cd_sel);
    }

    /**
     * エンティティ化されたセキュリティコードを返します。
     * @return string エンティティ化されたセキュリティコード
     */
    public function security_cd() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_security_cd);
    }
}
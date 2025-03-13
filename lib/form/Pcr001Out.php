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
class Sgmov_Form_Pcr001Out {

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
     * お名前フリガナ 姓
     * @var string
     */
    public $raw_surname_furigana = '';

    /**
     * お名前フリガナ 名
     * @var string
     */
    public $raw_forename_furigana = '';

    /**
     * 同行のご家族人数
     * @var string
     */
    public $raw_number_persons = '';

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
     * コールセンター電話番号選択値
     * @var array
     */
    public $raw_call_operator_id_cd_sel = '';

    /**
     * コールセンター電話番号リスト
     * @var array
     */
    public $raw_call_operator_id_cds = array();

    /**
     * コールセンター電話番号ラベルリスト
     * @var array
     */
    public $raw_call_operator_id_lbls = array();
    

    /**
     * ツアー名コード選択値
     * @var string
     */
    public $raw_travel_cd_sel = '';

    /**
     * ツアー名コードリスト
     * @var array
     */
    public $raw_travel_cds = array();

    /**
     * ツアー名コードラベルリスト
     * @var array
     */
    public $raw_travel_lbls = array();

    /**
     * 船内のお部屋番号
     * @var string
     */
    public $raw_room_number = '';

    /**
     * 集荷の往復コード選択値
     * @var string
     */
    public $raw_terminal_cd_sel = '';

    /**
     * 配送荷物個数 往路
     * @var string
     */
    public $raw_departure_quantity = '';

    /**
     * 配送荷物個数 復路
     * @var string
     */
    public $raw_arrival_quantity = '';

    /**
     * 出発地コード選択値
     * @var string
     */
    public $raw_travel_departure_cd_sel = '';

    /**
     * 出発地コードリスト
     * @var array
     */
    public $raw_travel_departure_cds = array();

    /**
     * 出発地コードラベルリスト
     * @var array
     */
    public $raw_travel_departure_lbls = array();

    /**
     * 乗船日リスト
     * @var array
     */
    public $raw_travel_departure_dates = array();

    /**
     * 集荷希望年コード選択値
     * @var string
     */
    public $raw_cargo_collection_date_year_cd_sel = '';

    /**
     * 集荷希望年コードリスト
     * @var array
     */
    public $raw_cargo_collection_date_year_cds = array();

    /**
     * 集荷希望年コードラベルリスト
     * @var array
     */
    public $raw_cargo_collection_date_year_lbls = array();

    /**
     * 集荷希望月コード選択値
     * @var string
     */
    public $raw_cargo_collection_date_month_cd_sel = '';

    /**
     * 集荷希望月コードリスト
     * @var array
     */
    public $raw_cargo_collection_date_month_cds = array();

    /**
     * 集荷希望月コードラベルリスト
     * @var array
     */
    public $raw_cargo_collection_date_month_lbls = array();

    /**
     * 集荷希望日コード選択値
     * @var string
     */
    public $raw_cargo_collection_date_day_cd_sel = '';

    /**
     * 集荷希望日コードリスト
     * @var array
     */
    public $raw_cargo_collection_date_day_cds = array();

    /**
     * 集荷希望日コードラベルリスト
     * @var array
     */
    public $raw_cargo_collection_date_day_lbls = array();

    /**
     * 集荷希望開始時刻コード選択値
     * @var string
     */
    public $raw_cargo_collection_st_time_cd_sel = '';

    /**
     * 集荷希望開始時刻コードリスト
     * @var array
     */
    public $raw_cargo_collection_st_time_cds = array();

    /**
     * 集荷希望開始時刻コードラベルリスト
     * @var array
     */
    public $raw_cargo_collection_st_time_lbls = array();

    /**
     * 集荷希望終了時刻コード選択値
     * @var string
     */
    public $raw_cargo_collection_ed_time_cd_sel = '';

    /**
     * 集荷希望終了時刻コードリスト
     * @var array
     */
    public $raw_cargo_collection_ed_time_cds = array();

    /**
     * 集荷希望終了時刻コードラベルリスト
     * @var array
     */
    public $raw_cargo_collection_ed_time_lbls = array();

    /**
     * 到着地コード選択値
     * @var string
     */
    public $raw_travel_arrival_cd_sel = '';

    /**
     * 到着地コードリスト
     * @var array
     */
    public $raw_travel_arrival_cds = array();

    /**
     * 到着地コードラベルリスト
     * @var array
     */
    public $raw_travel_arrival_lbls = array();

    /**
     * 配達指定年コード選択値
     * @var string
     */
    public $raw_delivery_date_year_cd_sel = '';

    /**
     * 配達指定年コードリスト
     * @var array
     */
    public $raw_delivery_date_year_cds = array();

    /**
     * 配達指定年コードラベルリスト
     * @var array
     */
    public $raw_delivery_date_year_lbls = array();

    /**
     * 配達指定月コード選択値
     * @var string
     */
    public $raw_delivery_date_month_cd_sel = '';

    /**
     * 配達指定月コードリスト
     * @var array
     */
    public $raw_delivery_date_month_cds = array();

    /**
     * 配達指定月コードラベルリスト
     * @var array
     */
    public $raw_delivery_date_month_lbls = array();

    /**
     * 配達指定日コード選択値
     * @var string
     */
    public $raw_delivery_date_day_cd_sel = '';

    /**
     * 配達指定日コードリスト
     * @var array
     */
    public $raw_delivery_date_day_cds = array();

    /**
     * 配達指定日コードラベルリスト
     * @var array
     */
    public $raw_delivery_date_day_lbls = array();

    /**
     * 配達指定時刻コード選択値
     * @var string
     */
    public $raw_delivery_time_cd_sel = '';

    /**
     * 配達指定時刻コードリスト
     * @var array
     */
    public $raw_delivery_time_cds = array();

    /**
     * 配達指定時刻コードラベルリスト
     * @var array
     */
    public $raw_delivery_time_lbls = array();

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
    
    /**
     * 申込区分
     * @var string
     */
    public $raw_req_flg = '';
    /**
     * 復路のみ非表示一覧
     * @var type array
     */
    public $raw_dispnone_arrival_travel_agency_id_list = array();
    
    /**
     * 携帯番号
     * @var string
     */
    public $raw_tel_mobile = '';
    
    /**
     * checkbox agreement
     * @var string
     */
    public $raw_chb_agreement = '';

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
     * エンティティ化されたお名前フリガナ 姓を返します。
     * @return string エンティティ化されたお名前フリガナ 姓
     */
    public function surname_furigana() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_surname_furigana);
    }

    /**
     * エンティティ化されたお名前フリガナ 名を返します。
     * @return string エンティティ化されたお名前フリガナ 名
     */
    public function forename_furigana() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_forename_furigana);
    }

    /**
     * エンティティ化された同行のご家族人数を返します。
     * @return string エンティティ化された同行のご家族人数
     */
    public function number_persons() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_number_persons);
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
     * エンティティ化されたコールセンター電話番号選択値を返します。
     * @return array エンティティ化されたコールセンター電話番号選択値
     */
    public function call_operator_id_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_call_operator_id_cd_sel);
    }

    /**
     * エンティティ化されたコールセンター電話番号リストを返します。
     * @return array エンティティ化されたコールセンター電話番号リスト
     */
    public function call_operator_id_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_call_operator_id_cds);
    }

    /**
     * エンティティ化されたコールセンター電話番号ラベルリストを返します。
     * @return array エンティティ化されたコールセンター電話番号ラベルリスト
     */
    public function call_operator_id_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_call_operator_id_lbls);
    }
    

    /**
     * エンティティ化されたツアー名コード選択値を返します。
     * @return string エンティティ化されたツアー名コード選択値
     */
    public function travel_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_cd_sel);
    }

    /**
     * エンティティ化されたツアー名コードリストを返します。
     * @return array エンティティ化されたツアー名コードリスト
     */
    public function travel_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_cds);
    }

    /**
     * エンティティ化されたツアー名コードラベルリストを返します。
     * @return array エンティティ化されたツアー名コードラベルリスト
     */
    public function travel_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_lbls);
    }

    /**
     * エンティティ化された船内のお部屋番号を返します。
     * @return string エンティティ化された船内のお部屋番号
     */
    public function room_number() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_room_number);
    }

    /**
     * エンティティ化された集荷の往復コード選択値を返します。
     * @return string エンティティ化された集荷の往復コード選択値
     */
    public function terminal_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_terminal_cd_sel);
    }

    /**
     * エンティティ化された配送荷物個数 往路を返します。
     * @return string エンティティ化された配送荷物個数 往路
     */
    public function departure_quantity() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_departure_quantity);
    }

    /**
     * エンティティ化された配送荷物個数 復路を返します。
     * @return string エンティティ化された配送荷物個数 復路
     */
    public function arrival_quantity() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_arrival_quantity);
    }

    /**
     * エンティティ化された出発地コード選択値を返します。
     * @return string エンティティ化された出発地コード選択値
     */
    public function travel_departure_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_departure_cd_sel);
    }

    /**
     * エンティティ化された出発地コードリストを返します。
     * @return string エンティティ化された出発地コードリスト
     */
    public function travel_departure_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_departure_cds);
    }

    /**
     * エンティティ化された出発地コードラベルリストを返します。
     * @return string エンティティ化された出発地コードラベルリスト
     */
    public function travel_departure_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_departure_lbls);
    }

    /**
     * エンティティ化された乗船日リストを返します。
     * @return string エンティティ化された乗船日リスト
     */
    public function travel_departure_dates() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_departure_dates);
    }

    /**
     * エンティティ化された集荷希望年コード選択値を返します。
     * @return string エンティティ化された集荷希望年コード選択値
     */
    public function cargo_collection_date_year_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cargo_collection_date_year_cd_sel);
    }

    /**
     * エンティティ化された集荷希望年コードリストを返します。
     * @return array エンティティ化された集荷希望年コードリスト
     */
    public function cargo_collection_date_year_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cargo_collection_date_year_cds);
    }

    /**
     * エンティティ化された集荷希望年ラベルリストを返します。
     * @return array エンティティ化された集荷希望年ラベルリスト
     */
    public function cargo_collection_date_year_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cargo_collection_date_year_lbls);
    }

    /**
     * エンティティ化された集荷希望月コード選択値を返します。
     * @return string エンティティ化された集荷希望月コード選択値
     */
    public function cargo_collection_date_month_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cargo_collection_date_month_cd_sel);
    }

    /**
     * エンティティ化された集荷希望月コードリストを返します。
     * @return array エンティティ化された集荷希望月コードリスト
     */
    public function cargo_collection_date_month_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cargo_collection_date_month_cds);
    }

    /**
     * エンティティ化された集荷希望月ラベルリストを返します。
     * @return array エンティティ化された集荷希望月ラベルリスト
     */
    public function cargo_collection_date_month_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cargo_collection_date_month_lbls);
    }

    /**
     * エンティティ化された集荷希望日コード選択値を返します。
     * @return string エンティティ化された集荷希望日コード選択値
     */
    public function cargo_collection_date_day_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cargo_collection_date_day_cd_sel);
    }

    /**
     * エンティティ化された集荷希望日コードリストを返します。
     * @return array エンティティ化された集荷希望日コードリスト
     */
    public function cargo_collection_date_day_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cargo_collection_date_day_cds);
    }

    /**
     * エンティティ化された集荷希望日ラベルリストを返します。
     * @return array エンティティ化された集荷希望日ラベルリスト
     */
    public function cargo_collection_date_day_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cargo_collection_date_day_lbls);
    }

    /**
     * エンティティ化された集荷希望開始時刻コード選択値を返します。
     * @return string エンティティ化された集荷希望開始時刻コード選択値
     */
    public function cargo_collection_st_time_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cargo_collection_st_time_cd_sel);
    }

    /**
     * エンティティ化された集荷希望開始時刻コードリストを返します。
     * @return array エンティティ化された集荷希望開始時刻コードリスト
     */
    public function cargo_collection_st_time_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cargo_collection_st_time_cds);
    }

    /**
     * エンティティ化された集荷希望開始時刻ラベルリストを返します。
     * @return array エンティティ化された集荷希望開始時刻ラベルリスト
     */
    public function cargo_collection_st_time_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cargo_collection_st_time_lbls);
    }

    /**
     * エンティティ化された集荷希望終了時刻コード選択値を返します。
     * @return string エンティティ化された集荷希望終了時刻コード選択値
     */
    public function cargo_collection_ed_time_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cargo_collection_ed_time_cd_sel);
    }

    /**
     * エンティティ化された集荷希望終了時刻コードリストを返します。
     * @return array エンティティ化された集荷希望終了時刻コードリスト
     */
    public function cargo_collection_ed_time_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cargo_collection_ed_time_cds);
    }

    /**
     * エンティティ化された集荷希望終了時刻ラベルリストを返します。
     * @return array エンティティ化された集荷希望終了時刻ラベルリスト
     */
    public function cargo_collection_ed_time_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cargo_collection_ed_time_lbls);
    }

    /**
     * エンティティ化された到着地コード選択値を返します。
     * @return string エンティティ化された到着地コード選択値
     */
    public function travel_arrival_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_arrival_cd_sel);
    }

    /**
     * エンティティ化された到着地コードリストを返します。
     * @return string エンティティ化された到着地コードリスト
     */
    public function travel_arrival_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_arrival_cds);
    }

    /**
     * エンティティ化された到着地コードラベルリストを返します。
     * @return string エンティティ化された到着地コードラベルリスト
     */
    public function travel_arrival_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_arrival_lbls);
    }

    /**
     * エンティティ化された配達指定年コード選択値を返します。
     * @return string エンティティ化された配達指定年コード選択値
     */
    public function delivery_day_year_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_delivery_day_year_cd_sel);
    }

    /**
     * エンティティ化された配達指定年コードリストを返します。
     * @return array エンティティ化された配達指定年コードリスト
     */
    public function delivery_day_year_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_delivery_day_year_cds);
    }

    /**
     * エンティティ化された配達指定年ラベルリストを返します。
     * @return array エンティティ化された配達指定年ラベルリスト
     */
    public function delivery_day_year_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_delivery_day_year_lbls);
    }

    /**
     * エンティティ化された配達指定月コード選択値を返します。
     * @return string エンティティ化された配達指定月コード選択値
     */
    public function delivery_day_month_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_delivery_day_month_cd_sel);
    }

    /**
     * エンティティ化された配達指定月コードリストを返します。
     * @return array エンティティ化された配達指定月コードリスト
     */
    public function delivery_day_month_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_delivery_day_month_cds);
    }

    /**
     * エンティティ化された配達指定月ラベルリストを返します。
     * @return array エンティティ化された配達指定月ラベルリスト
     */
    public function delivery_day_month_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_delivery_day_month_lbls);
    }

    /**
     * エンティティ化された配達指定日コード選択値を返します。
     * @return string エンティティ化された配達指定日コード選択値
     */
    public function delivery_day_day_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_delivery_day_day_cd_sel);
    }

    /**
     * エンティティ化された配達指定日コードリストを返します。
     * @return array エンティティ化された配達指定日コードリスト
     */
    public function delivery_day_day_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_delivery_day_day_cds);
    }

    /**
     * エンティティ化された配達指定日ラベルリストを返します。
     * @return array エンティティ化された配達指定日ラベルリスト
     */
    public function delivery_day_day_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_delivery_day_day_lbls);
    }

    /**
     * エンティティ化された配達指定時刻コード選択値を返します。
     * @return string エンティティ化された配達指定時刻コード選択値
     */
    public function delivery_time_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_delivery_time_cd_sel);
    }

    /**
     * エンティティ化された配達指定時刻コードリストを返します。
     * @return array エンティティ化された配達指定時刻コードリスト
     */
    public function delivery_time_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_delivery_time_cds);
    }

    /**
     * エンティティ化された配達指定時刻ラベルリストを返します。
     * @return array エンティティ化された配達指定時刻ラベルリスト
     */
    public function delivery_time_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_delivery_time_lbls);
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
    
    public function dispnone_arrival_travel_agency_id_list() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_dispnone_arrival_travel_agency_id_list);
    }
    
    /**
     * エンティティ化された申込区分を返します。
     * @return string エンティティ化された申込区分
     */
    public function req_flg() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_req_flg);
    }
    
    /**
     * エンティティ化された携帯番号を返します。
     * @return string エンティティ化された携帯番号
     */
    public function tel_mobile() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel_mobile);
    }
    
    /**
     * エンティティ化されたお問い合わせ内容を返します。
     * @return string エンティティ化されたお問い合わせ内容
    */
    public function chb_agreement()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_chb_agreement);
    }
}
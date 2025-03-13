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
 * 確認画面の出力フォームです。
 * TODO TwigやSmartyなどのテンプレートエンジンの導入を検討する
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Pcr003Out {

    /**
     * 送料
     * @var string
     */
    public $raw_delivery_charge = '';

    /**
     * リピータ割引
     * @var string
     */
    public $raw_repeater_discount = '';

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
     * 郵便番号
     * @var string
     */
    public $raw_zip = '';

    /**
     * 都道府県
     * @var string
     */
    public $raw_pref = '';

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
     * 船名
     * @var string
     */
    public $raw_travel_agency = '';
    
    /**
     * コールセンター電話番号
     * @var string
     */
    public $raw_call_operator_id = '';

    /**
     * ツアーコード
     * @var string
     */
    public $raw_travel_cd = '';

    /**
     * ツアー名
     * @var string
     */
    public $raw_travel = '';

    /**
     * 船内のお部屋番号
     * @var string
     */
    public $raw_room_number = '';

    /**
     * 集荷の往復
     * @var string
     */
    public $raw_terminal = '';

    /**
     * 往路存在フラグ
     * @var boolean
     */
    public $raw_departure_exist_flag = false;

    /**
     * 復路存在フラグ
     * @var boolean
     */
    public $raw_arrival_exist_flag = false;

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
     * 出発地
     * @var string
     */
    public $raw_travel_departure = '';

    /**
     * 集荷希望日
     * @var string
     */
    public $raw_cargo_collection_date = '';

    /**
     * 集荷希望開始時刻
     * @var string
     */
    public $raw_cargo_collection_st_time = '';

    /**
     * 集荷希望終了時刻
     * @var string
     */
    public $raw_cargo_collection_ed_time = '';

    /**
     * 到着地
     * @var string
     */
    public $raw_travel_arrival = '';

    /**
     * お支払方法コード選択値
     * @var string
     */
    public $raw_payment_method_cd_sel = '';

    /**
     * お支払方法
     * @var string
     */
    public $raw_payment_method = '';

    /**
     * お支払店
     * @var string
     */
    public $raw_convenience_store = '';

    /**
     * クレジットカード番号
     * @var string
     */
    public $raw_card_number = '';

    /**
     * 有効期限
     * @var string
     */
    public $raw_card_expire = '';

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
     * 携帯番号
     * @var string
     */
    public $raw_tel_mobile = '';
    
    /**
     * エンティティ化された送料を返します。
     * @return string エンティティ化された送料
     */
    public function delivery_charge() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_delivery_charge);
    }

    /**
     * エンティティ化されたリピータ割引を返します。
     * @return string エンティティ化されたリピータ割引
     */
    public function repeater_discount() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_repeater_discount);
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
     * @return string エンティティ化された電話番号
     */
    public function tel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel);
    }

    /**
     * エンティティ化されたメールアドレスを返します。
     * @return string エンティティ化されたメールアドレス
     */
    public function mail() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_mail);
    }

    /**
     * エンティティ化された郵便番号を返します。
     * @return string エンティティ化された郵便番号
     */
    public function zip() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_zip);
    }

    /**
     * エンティティ化された都道府県を返します。
     * @return string エンティティ化された都道府県
     */
    public function pref() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_pref);
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
     * エンティティ化された船名を返します。
     * @return string エンティティ化された船名
     */
    public function travel_agency() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_agency);
    }
    
    /**
     * エンティティ化されたコールセンター電話番号を返します。
     * @return string エンティティ化されたコールセンター電話番号
     */
    public function call_operator_id() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_call_operator_id);
    }
    
    /**
     * エンティティ化されたツアーコードを返します。
     * @return string エンティティ化されたツアーコード
     */
    public function travel_cd() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_cd);
    }

    /**
     * エンティティ化されたツアー名を返します。
     * @return string エンティティ化されたツアー名
     */
    public function travel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel);
    }

    /**
     * エンティティ化された船内のお部屋番号を返します。
     * @return string エンティティ化された船内のお部屋番号
     */
    public function room_number() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_room_number);
    }

    /**
     * エンティティ化された集荷の往復を返します。
     * @return string エンティティ化された集荷の往復
     */
    public function terminal() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_terminal);
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
     * エンティティ化された出発地を返します。
     * @return string エンティティ化された出発地
     */
    public function travel_departure() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_departure);
    }

    /**
     * エンティティ化された集荷希望日を返します。
     * @return string エンティティ化された集荷希望日
     */
    public function cargo_collection_date() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cargo_collection_date);
    }

    /**
     * エンティティ化された集荷希望開始時刻を返します。
     * @return string エンティティ化された集荷希望開始時刻
     */
    public function cargo_collection_st_time() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cargo_collection_st_time);
    }

    /**
     * エンティティ化された集荷希望終了時刻を返します。
     * @return string エンティティ化された集荷希望終了時刻
     */
    public function cargo_collection_ed_time() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cargo_collection_ed_time);
    }

    /**
     * エンティティ化された到着地を返します。
     * @return string エンティティ化された到着地
     */
    public function travel_arrival() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_arrival);
    }

    /**
     * エンティティ化された配達指定年を返します。
     * @return string エンティティ化された配達指定年
     */
    public function delivery_day_year() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_delivery_day_year);
    }

    /**
     * エンティティ化された配達指定月を返します。
     * @return string エンティティ化された配達指定月
     */
    public function delivery_day_month() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_delivery_day_month);
    }

    /**
     * エンティティ化された配達指定日を返します。
     * @return string エンティティ化された配達指定日
     */
    public function delivery_day_day() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_delivery_day_day);
    }

    /**
     * エンティティ化された配達指定時刻を返します。
     * @return string エンティティ化された配達指定時刻
     */
    public function delivery_time() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_delivery_time);
    }

    /**
     * エンティティ化されたお支払方法コード選択値を返します。
     * @return string エンティティ化されたお支払方法コード選択値
     */
    public function payment_method_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_payment_method_cd_sel);
    }

    /**
     * エンティティ化されたお支払方法を返します。
     * @return string エンティティ化されたお支払方法
     */
    public function payment_method() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_payment_method);
    }

    /**
     * エンティティ化されたお支払店を返します。
     * @return string エンティティ化されたお支払店
     */
    public function convenience_store() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_convenience_store);
    }

    /**
     * エンティティ化されたクレジットカード番号を返します。
     * @return string エンティティ化されたクレジットカード番号
     */
    public function card_number() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_card_number);
    }

    /**
     * エンティティ化された有効期限を返します。
     * @return string エンティティ化された有効期限
     */
    public function card_expire() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_card_expire);
    }

    /**
     * エンティティ化されたセキュリティコードを返します。
     * @return string エンティティ化されたセキュリティコード
     */
    public function security_cd() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_security_cd);
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
}
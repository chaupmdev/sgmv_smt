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
 * 法人訪問見積入力画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Pcv001Out
{
    /**
     * 会社名
     * @var string
     */
    public $raw_company_name = '';

    /**
     * 会社名フリガナ
     * @var string
     */
    public $raw_company_furigana = '';

    /**
     * 担当者名
     * @var string
     */
    public $raw_charge_name = '';

    /**
     * 担当者名フリガナ
     * @var string
     */
    public $raw_charge_furigana = '';

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
     * 電話種類コード選択値
     * @var string
     */
    public $raw_tel_type_cd_sel = '';

    /**
     * 電話種類その他
     * @var string
     */
    public $raw_tel_other = '';

    /**
     * メールアドレス
     * @var string
     */
    public $raw_mail = '';

    /**
     * 連絡方法コード選択値
     * @var string
     */
    public $raw_contact_method_cd_sel = '';

    /**
     * 電話連絡可能コード選択値
     * @var string
     */
    public $raw_contact_available_cd_sel = '';

    /**
     * 電話連絡可能開始時刻コード選択値
     * @var string
     */
    public $raw_contact_start_cd_sel = '';

    /**
     * 電話連絡可能開始時刻コードリスト
     * @var array
     */
    public $raw_contact_start_cds = array();

    /**
     * 電話連絡可能開始時刻ラベルリスト
     * @var array
     */
    public $raw_contact_start_lbls = array();

    /**
     * 電話連絡可能終了時刻コード選択値
     * @var string
     */
    public $raw_contact_end_cd_sel = '';

    /**
     * 電話連絡可能終了時刻コードリスト
     * @var array
     */
    public $raw_contact_end_cds = array();

    /**
     * 電話連絡可能終了時刻ラベルリスト
     * @var array
     */
    public $raw_contact_end_lbls = array();

    /**
     * 出発エリアコード選択値
     * @var string
     */
    public $raw_from_area_cd_sel = '';

    /**
     * 出発エリアコードリスト
     * @var array
     */
    public $raw_from_area_cds = array();

    /**
     * 出発エリアラベルリスト
     * @var array
     */
    public $raw_from_area_lbls = array();

    /**
     * 到着エリアコード選択値
     * @var string
     */
    public $raw_to_area_cd_sel = '';

    /**
     * 到着エリアコードリスト
     * @var array
     */
    public $raw_to_area_cds = array();

    /**
     * 到着エリアラベルリスト
     * @var array
     */
    public $raw_to_area_lbls = array();

    /**
     * 引越予定日年コード選択値
     * @var string
     */
    public $raw_move_date_year_cd_sel = '';

    /**
     * 引越予定日月コード選択値
     * @var string
     */
    public $raw_move_date_month_cd_sel = '';

    /**
     * 引越予定日日コード選択値
     * @var string
     */
    public $raw_move_date_day_cd_sel = '';

    /**
     * 引越予定日年コードリスト
     * @var array
     */
    public $raw_move_date_year_cds = array();

    /**
     * 引越予定日年ラベルリスト
     * @var array
     */
    public $raw_move_date_year_lbls = array();

    /**
     * 引越予定日月コードリスト
     * @var array
     */
    public $raw_move_date_month_cds = array();

    /**
     * 引越予定日月ラベルリスト
     * @var array
     */
    public $raw_move_date_month_lbls = array();

    /**
     * 引越予定日日コードリスト
     * @var array
     */
    public $raw_move_date_day_cds = array();

    /**
     * 引越予定日日ラベルリスト
     * @var array
     */
    public $raw_move_date_day_lbls = array();

    /**
     * 訪問見積第一希望日年コード選択値
     * @var string
     */
    public $raw_visit_date1_year_cd_sel = '';

    /**
     * 訪問見積第一希望日月コード選択値
     * @var string
     */
    public $raw_visit_date1_month_cd_sel = '';

    /**
     * 訪問見積第一希望日日コード選択値
     * @var string
     */
    public $raw_visit_date1_day_cd_sel = '';

    /**
     * 訪問見積第一希望日年コードリスト
     * @var array
     */
    public $raw_visit_date1_year_cds = array();

    /**
     * 訪問見積第一希望日年ラベルリスト
     * @var array
     */
    public $raw_visit_date1_year_lbls = array();

    /**
     * 訪問見積第一希望日月コードリスト
     * @var array
     */
    public $raw_visit_date1_month_cds = array();

    /**
     * 訪問見積第一希望日月ラベルリスト
     * @var array
     */
    public $raw_visit_date1_month_lbls = array();

    /**
     * 訪問見積第一希望日日コードリスト
     * @var array
     */
    public $raw_visit_date1_day_cds = array();

    /**
     * 訪問見積第一希望日日ラベルリスト
     * @var array
     */
    public $raw_visit_date1_day_lbls = array();

    /**
     * 訪問見積第二希望日年コード選択値
     * @var string
     */
    public $raw_visit_date2_year_cd_sel = '';

    /**
     * 訪問見積第二希望日月コード選択値
     * @var string
     */
    public $raw_visit_date2_month_cd_sel = '';

    /**
     * 訪問見積第二希望日日コード選択値
     * @var string
     */
    public $raw_visit_date2_day_cd_sel = '';

    /**
     * 訪問見積第二希望日年コードリスト
     * @var array
     */
    public $raw_visit_date2_year_cds = array();

    /**
     * 訪問見積第二希望日年ラベルリスト
     * @var array
     */
    public $raw_visit_date2_year_lbls = array();

    /**
     * 訪問見積第二希望日月コードリスト
     * @var array
     */
    public $raw_visit_date2_month_cds = array();

    /**
     * 訪問見積第二希望日月ラベルリスト
     * @var array
     */
    public $raw_visit_date2_month_lbls = array();

    /**
     * 訪問見積第二希望日日コードリスト
     * @var array
     */
    public $raw_visit_date2_day_cds = array();

    /**
     * 訪問見積第二希望日日ラベルリスト
     * @var array
     */
    public $raw_visit_date2_day_lbls = array();

    /**
     * 現住所郵便番号1
     * @var string
     */
    public $raw_cur_zip1 = '';

    /**
     * 現住所郵便番号2
     * @var string
     */
    public $raw_cur_zip2 = '';

    /**
     * 現住所都道府県コード選択値
     * @var string
     */
    public $raw_cur_pref_cd_sel = '';

    /**
     * 現住所都道府県コードリスト
     * @var array
     */
    public $raw_cur_pref_cds = array();

    /**
     * 現住所都道府県ラベルリスト
     * @var array
     */
    public $raw_cur_pref_lbls = array();

    /**
     * 現住所住所
     * @var string
     */
    public $raw_cur_address = '';

    /**
     * 現住所エレベーター有無フラグ選択値
     * @var string
     */
    public $raw_cur_elevator_cd_sel = '';

    /**
     * 現住所階数
     * @var string
     */
    public $raw_cur_floor = '';

    /**
     * 現住所住居前道幅コード選択値
     * @var string
     */
    public $raw_cur_road_cd_sel = '';

    /**
     * 新住所郵便番号1
     * @var string
     */
    public $raw_new_zip1 = '';

    /**
     * 新住所郵便番号2
     * @var string
     */
    public $raw_new_zip2 = '';

    /**
     * 新住所都道府県コード選択値
     * @var string
     */
    public $raw_new_pref_cd_sel = '';

    /**
     * 新住所都道府県コードリスト
     * @var array
     */
    public $raw_new_pref_cds = array();

    /**
     * 新住所都道府県ラベルリスト
     * @var array
     */
    public $raw_new_pref_lbls = array();

    /**
     * 新住所住所
     * @var string
     */
    public $raw_new_address = '';

    /**
     * 新住所エレベーター有無フラグ選択値
     * @var string
     */
    public $raw_new_elevator_cd_sel = '';

    /**
     * 新住所階数
     * @var string
     */
    public $raw_new_floor = '';

    /**
     * 新住所住居前道幅コード選択値
     * @var string
     */
    public $raw_new_road_cd_sel = '';

    /**
     * 移動人数
     * @var string
     */
    public $raw_number_of_people = '';

    /**
     * フロア坪数
     * @var string
     */
    public $raw_tsubo_su = '';

    /**
     * 備考
     * @var string
     */
    public $raw_comment = '';

    /**
     * エンティティ化された会社名を返します。
     * @return string エンティティ化された会社名
     */
    public function company_name()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_company_name);
    }

    /**
     * エンティティ化された会社名フリガナを返します。
     * @return string エンティティ化された会社名フリガナ
     */
    public function company_furigana()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_company_furigana);
    }

    /**
     * エンティティ化された担当者名を返します。
     * @return string エンティティ化された担当者名
     */
    public function charge_name()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_charge_name);
    }

    /**
     * エンティティ化された担当者名フリガナを返します。
     * @return string エンティティ化された担当者名フリガナ
     */
    public function charge_furigana()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_charge_furigana);
    }

    /**
     * エンティティ化された電話番号1を返します。
     * @return string エンティティ化された電話番号1
     */
    public function tel1()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel1);
    }

    /**
     * エンティティ化された電話番号2を返します。
     * @return string エンティティ化された電話番号2
     */
    public function tel2()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel2);
    }

    /**
     * エンティティ化された電話番号3を返します。
     * @return string エンティティ化された電話番号3
     */
    public function tel3()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel3);
    }

    /**
     * エンティティ化された電話種類コード選択値を返します。
     * @return string エンティティ化された電話種類コード選択値
     */
    public function tel_type_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel_type_cd_sel);
    }

    /**
     * エンティティ化された電話種類その他を返します。
     * @return string エンティティ化された電話種類その他
     */
    public function tel_other()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel_other);
    }

    /**
     * エンティティ化されたメールアドレスを返します。
     * @return string エンティティ化されたメールアドレス
     */
    public function mail()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_mail);
    }

    /**
     * エンティティ化された連絡方法コード選択値を返します。
     * @return string エンティティ化された連絡方法コード選択値
     */
    public function contact_method_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_method_cd_sel);
    }

    /**
     * エンティティ化された電話連絡可能コード選択値を返します。
     * @return string エンティティ化された電話連絡可能コード選択値
     */
    public function contact_available_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_available_cd_sel);
    }

    /**
     * エンティティ化された電話連絡可能開始時刻コード選択値を返します。
     * @return string エンティティ化された電話連絡可能開始時刻コード選択値
     */
    public function contact_start_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_start_cd_sel);
    }

    /**
     * エンティティ化された電話連絡可能開始時刻コードリストを返します。
     * @return array エンティティ化された電話連絡可能開始時刻コードリスト
     */
    public function contact_start_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_start_cds);
    }

    /**
     * エンティティ化された電話連絡可能開始時刻ラベルリストを返します。
     * @return array エンティティ化された電話連絡可能開始時刻ラベルリスト
     */
    public function contact_start_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_start_lbls);
    }

    /**
     * エンティティ化された電話連絡可能終了時刻コード選択値を返します。
     * @return string エンティティ化された電話連絡可能終了時刻コード選択値
     */
    public function contact_end_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_end_cd_sel);
    }

    /**
     * エンティティ化された電話連絡可能終了時刻コードリストを返します。
     * @return array エンティティ化された電話連絡可能終了時刻コードリスト
     */
    public function contact_end_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_end_cds);
    }

    /**
     * エンティティ化された電話連絡可能終了時刻ラベルリストを返します。
     * @return array エンティティ化された電話連絡可能終了時刻ラベルリスト
     */
    public function contact_end_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_end_lbls);
    }

    /**
     * エンティティ化された出発エリアコード選択値を返します。
     * @return string エンティティ化された出発エリアコード選択値
     */
    public function from_area_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_area_cd_sel);
    }

    /**
     * エンティティ化された出発エリアコードリストを返します。
     * @return array エンティティ化された出発エリアコードリスト
     */
    public function from_area_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_area_cds);
    }

    /**
     * エンティティ化された出発エリアラベルリストを返します。
     * @return array エンティティ化された出発エリアラベルリスト
     */
    public function from_area_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_area_lbls);
    }

    /**
     * エンティティ化された到着エリアコード選択値を返します。
     * @return string エンティティ化された到着エリアコード選択値
     */
    public function to_area_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_to_area_cd_sel);
    }

    /**
     * エンティティ化された到着エリアコードリストを返します。
     * @return array エンティティ化された到着エリアコードリスト
     */
    public function to_area_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_to_area_cds);
    }

    /**
     * エンティティ化された到着エリアラベルリストを返します。
     * @return array エンティティ化された到着エリアラベルリスト
     */
    public function to_area_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_to_area_lbls);
    }

    /**
     * エンティティ化された引越予定日年コード選択値を返します。
     * @return string エンティティ化された引越予定日年コード選択値
     */
    public function move_date_year_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_move_date_year_cd_sel);
    }

    /**
     * エンティティ化された引越予定日月コード選択値を返します。
     * @return string エンティティ化された引越予定日月コード選択値
     */
    public function move_date_month_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_move_date_month_cd_sel);
    }

    /**
     * エンティティ化された引越予定日日コード選択値を返します。
     * @return string エンティティ化された引越予定日日コード選択値
     */
    public function move_date_day_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_move_date_day_cd_sel);
    }

    /**
     * エンティティ化された引越予定日年コードリストを返します。
     * @return array エンティティ化された引越予定日年コードリスト
     */
    public function move_date_year_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_move_date_year_cds);
    }

    /**
     * エンティティ化された引越予定日年ラベルリストを返します。
     * @return array エンティティ化された引越予定日年ラベルリスト
     */
    public function move_date_year_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_move_date_year_lbls);
    }

    /**
     * エンティティ化された引越予定日月コードリストを返します。
     * @return array エンティティ化された引越予定日月コードリスト
     */
    public function move_date_month_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_move_date_month_cds);
    }

    /**
     * エンティティ化された引越予定日月ラベルリストを返します。
     * @return array エンティティ化された引越予定日月ラベルリスト
     */
    public function move_date_month_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_move_date_month_lbls);
    }

    /**
     * エンティティ化された引越予定日日コードリストを返します。
     * @return array エンティティ化された引越予定日日コードリスト
     */
    public function move_date_day_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_move_date_day_cds);
    }

    /**
     * エンティティ化された引越予定日日ラベルリストを返します。
     * @return array エンティティ化された引越予定日日ラベルリスト
     */
    public function move_date_day_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_move_date_day_lbls);
    }

    /**
     * エンティティ化された訪問見積第一希望日年コード選択値を返します。
     * @return string エンティティ化された訪問見積第一希望日年コード選択値
     */
    public function visit_date1_year_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date1_year_cd_sel);
    }

    /**
     * エンティティ化された訪問見積第一希望日月コード選択値を返します。
     * @return string エンティティ化された訪問見積第一希望日月コード選択値
     */
    public function visit_date1_month_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date1_month_cd_sel);
    }

    /**
     * エンティティ化された訪問見積第一希望日日コード選択値を返します。
     * @return string エンティティ化された訪問見積第一希望日日コード選択値
     */
    public function visit_date1_day_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date1_day_cd_sel);
    }

    /**
     * エンティティ化された訪問見積第一希望日年コードリストを返します。
     * @return array エンティティ化された訪問見積第一希望日年コードリスト
     */
    public function visit_date1_year_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date1_year_cds);
    }

    /**
     * エンティティ化された訪問見積第一希望日年ラベルリストを返します。
     * @return array エンティティ化された訪問見積第一希望日年ラベルリスト
     */
    public function visit_date1_year_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date1_year_lbls);
    }

    /**
     * エンティティ化された訪問見積第一希望日月コードリストを返します。
     * @return array エンティティ化された訪問見積第一希望日月コードリスト
     */
    public function visit_date1_month_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date1_month_cds);
    }

    /**
     * エンティティ化された訪問見積第一希望日月ラベルリストを返します。
     * @return array エンティティ化された訪問見積第一希望日月ラベルリスト
     */
    public function visit_date1_month_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date1_month_lbls);
    }

    /**
     * エンティティ化された訪問見積第一希望日日コードリストを返します。
     * @return array エンティティ化された訪問見積第一希望日日コードリスト
     */
    public function visit_date1_day_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date1_day_cds);
    }

    /**
     * エンティティ化された訪問見積第一希望日日ラベルリストを返します。
     * @return array エンティティ化された訪問見積第一希望日日ラベルリスト
     */
    public function visit_date1_day_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date1_day_lbls);
    }

    /**
     * エンティティ化された訪問見積第二希望日年コード選択値を返します。
     * @return string エンティティ化された訪問見積第二希望日年コード選択値
     */
    public function visit_date2_year_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date2_year_cd_sel);
    }

    /**
     * エンティティ化された訪問見積第二希望日月コード選択値を返します。
     * @return string エンティティ化された訪問見積第二希望日月コード選択値
     */
    public function visit_date2_month_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date2_month_cd_sel);
    }

    /**
     * エンティティ化された訪問見積第二希望日日コード選択値を返します。
     * @return string エンティティ化された訪問見積第二希望日日コード選択値
     */
    public function visit_date2_day_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date2_day_cd_sel);
    }

    /**
     * エンティティ化された訪問見積第二希望日年コードリストを返します。
     * @return array エンティティ化された訪問見積第二希望日年コードリスト
     */
    public function visit_date2_year_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date2_year_cds);
    }

    /**
     * エンティティ化された訪問見積第二希望日年ラベルリストを返します。
     * @return array エンティティ化された訪問見積第二希望日年ラベルリスト
     */
    public function visit_date2_year_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date2_year_lbls);
    }

    /**
     * エンティティ化された訪問見積第二希望日月コードリストを返します。
     * @return array エンティティ化された訪問見積第二希望日月コードリスト
     */
    public function visit_date2_month_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date2_month_cds);
    }

    /**
     * エンティティ化された訪問見積第二希望日月ラベルリストを返します。
     * @return array エンティティ化された訪問見積第二希望日月ラベルリスト
     */
    public function visit_date2_month_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date2_month_lbls);
    }

    /**
     * エンティティ化された訪問見積第二希望日日コードリストを返します。
     * @return array エンティティ化された訪問見積第二希望日日コードリスト
     */
    public function visit_date2_day_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date2_day_cds);
    }

    /**
     * エンティティ化された訪問見積第二希望日日ラベルリストを返します。
     * @return array エンティティ化された訪問見積第二希望日日ラベルリスト
     */
    public function visit_date2_day_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date2_day_lbls);
    }

    /**
     * エンティティ化された現住所郵便番号1を返します。
     * @return string エンティティ化された現住所郵便番号1
     */
    public function cur_zip1()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_zip1);
    }

    /**
     * エンティティ化された現住所郵便番号2を返します。
     * @return string エンティティ化された現住所郵便番号2
     */
    public function cur_zip2()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_zip2);
    }

    /**
     * エンティティ化された現住所都道府県コード選択値を返します。
     * @return string エンティティ化された現住所都道府県コード選択値
     */
    public function cur_pref_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_pref_cd_sel);
    }

    /**
     * エンティティ化された現住所都道府県コードリストを返します。
     * @return array エンティティ化された現住所都道府県コードリスト
     */
    public function cur_pref_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_pref_cds);
    }

    /**
     * エンティティ化された現住所都道府県ラベルリストを返します。
     * @return array エンティティ化された現住所都道府県ラベルリスト
     */
    public function cur_pref_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_pref_lbls);
    }

    /**
     * エンティティ化された現住所住所を返します。
     * @return string エンティティ化された現住所住所
     */
    public function cur_address()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_address);
    }

    /**
     * エンティティ化された現住所エレベーター有無フラグ選択値を返します。
     * @return string エンティティ化された現住所エレベーター有無フラグ選択値
     */
    public function cur_elevator_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_elevator_cd_sel);
    }

    /**
     * エンティティ化された現住所階数を返します。
     * @return string エンティティ化された現住所階数
     */
    public function cur_floor()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_floor);
    }

    /**
     * エンティティ化された現住所住居前道幅コード選択値を返します。
     * @return string エンティティ化された現住所住居前道幅コード選択値
     */
    public function cur_road_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_road_cd_sel);
    }

    /**
     * エンティティ化された新住所郵便番号1を返します。
     * @return string エンティティ化された新住所郵便番号1
     */
    public function new_zip1()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_new_zip1);
    }

    /**
     * エンティティ化された新住所郵便番号2を返します。
     * @return string エンティティ化された新住所郵便番号2
     */
    public function new_zip2()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_new_zip2);
    }

    /**
     * エンティティ化された新住所都道府県コード選択値を返します。
     * @return string エンティティ化された新住所都道府県コード選択値
     */
    public function new_pref_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_new_pref_cd_sel);
    }

    /**
     * エンティティ化された新住所都道府県コードリストを返します。
     * @return array エンティティ化された新住所都道府県コードリスト
     */
    public function new_pref_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_new_pref_cds);
    }

    /**
     * エンティティ化された新住所都道府県ラベルリストを返します。
     * @return array エンティティ化された新住所都道府県ラベルリスト
     */
    public function new_pref_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_new_pref_lbls);
    }

    /**
     * エンティティ化された新住所住所を返します。
     * @return string エンティティ化された新住所住所
     */
    public function new_address()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_new_address);
    }

    /**
     * エンティティ化された新住所エレベーター有無フラグ選択値を返します。
     * @return string エンティティ化された新住所エレベーター有無フラグ選択値
     */
    public function new_elevator_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_new_elevator_cd_sel);
    }

    /**
     * エンティティ化された新住所階数を返します。
     * @return string エンティティ化された新住所階数
     */
    public function new_floor()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_new_floor);
    }

    /**
     * エンティティ化された新住所住居前道幅コード選択値を返します。
     * @return string エンティティ化された新住所住居前道幅コード選択値
     */
    public function new_road_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_new_road_cd_sel);
    }

    /**
     * エンティティ化された移動人数を返します。
     * @return string エンティティ化された移動人数
     */
    public function number_of_people()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_number_of_people);
    }

    /**
     * エンティティ化されたフロア坪数を返します。
     * @return string エンティティ化されたフロア坪数
     */
    public function tsubo_su()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tsubo_su);
    }

    /**
     * エンティティ化された備考を返します。
     * @return string エンティティ化された備考
     */
    public function comment()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comment);
    }

}
?>

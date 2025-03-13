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
 * 法人訪問見積確認画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Pcv002Out
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
     * 電話番号
     * @var string
     */
    public $raw_tel = '';

    /**
     * 電話種類
     * @var string
     */
    public $raw_tel_type = '';

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
     * 連絡方法
     * @var string
     */
    public $raw_contact_method = '';

    /**
     * 電話連絡可能
     * @var string
     */
    public $raw_contact_available = '';

    /**
     * 電話連絡可能開始時刻
     * @var string
     */
    public $raw_contact_start = '';

    /**
     * 電話連絡可能終了時刻
     * @var string
     */
    public $raw_contact_end = '';

    /**
     * 出発エリア
     * @var string
     */
    public $raw_from_area = '';

    /**
     * 到着エリア
     * @var string
     */
    public $raw_to_area = '';

    /**
     * 引越予定日
     * @var string
     */
    public $raw_move_date = '';

    /**
     * 訪問見積第一希望日
     * @var string
     */
    public $raw_visit_date1 = '';

    /**
     * 訪問見積第二希望日
     * @var string
     */
    public $raw_visit_date2 = '';

    /**
     * 現住所郵便番号
     * @var string
     */
    public $raw_cur_zip = '';

    /**
     * 現住所都道府県住所
     * @var string
     */
    public $raw_cur_address_all = '';

    /**
     * 現住所エレベーター有無
     * @var string
     */
    public $raw_cur_elevator = '';

    /**
     * 現住所階数
     * @var string
     */
    public $raw_cur_floor = '';

    /**
     * 現住所住居前道幅
     * @var string
     */
    public $raw_cur_road = '';

    /**
     * 新住所郵便番号
     * @var string
     */
    public $raw_new_zip = '';

    /**
     * 新住所都道府県住所
     * @var string
     */
    public $raw_new_address_all = '';

    /**
     * 新住所エレベーター有無
     * @var string
     */
    public $raw_new_elevator = '';

    /**
     * 新住所階数
     * @var string
     */
    public $raw_new_floor = '';

    /**
     * 新住所住居前道幅
     * @var string
     */
    public $raw_new_road = '';

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
     * エンティティ化された電話番号を返します。
     * @return string エンティティ化された電話番号
     */
    public function tel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel);
    }

    /**
     * エンティティ化された電話種類を返します。
     * @return string エンティティ化された電話種類
     */
    public function tel_type()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel_type);
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
     * エンティティ化された連絡方法を返します。
     * @return string エンティティ化された連絡方法
     */
    public function contact_method()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_method);
    }

    /**
     * エンティティ化された電話連絡可能を返します。
     * @return string エンティティ化された電話連絡可能
     */
    public function contact_available()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_available);
    }

    /**
     * エンティティ化された電話連絡可能開始時刻を返します。
     * @return string エンティティ化された電話連絡可能開始時刻
     */
    public function contact_start()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_start);
    }

    /**
     * エンティティ化された電話連絡可能終了時刻を返します。
     * @return string エンティティ化された電話連絡可能終了時刻
     */
    public function contact_end()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_end);
    }

    /**
     * エンティティ化された出発エリアを返します。
     * @return string エンティティ化された出発エリア
     */
    public function from_area()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_area);
    }

    /**
     * エンティティ化された到着エリアを返します。
     * @return string エンティティ化された到着エリア
     */
    public function to_area()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_to_area);
    }

    /**
     * エンティティ化された引越予定日を返します。
     * @return string エンティティ化された引越予定日
     */
    public function move_date()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_move_date);
    }

    /**
     * エンティティ化された訪問見積第一希望日を返します。
     * @return string エンティティ化された訪問見積第一希望日
     */
    public function visit_date1()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date1);
    }

    /**
     * エンティティ化された訪問見積第二希望日を返します。
     * @return string エンティティ化された訪問見積第二希望日
     */
    public function visit_date2()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date2);
    }

    /**
     * エンティティ化された現住所郵便番号を返します。
     * @return string エンティティ化された現住所郵便番号
     */
    public function cur_zip()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_zip);
    }

    /**
     * エンティティ化された現住所都道府県住所を返します。
     * @return string エンティティ化された現住所都道府県住所
     */
    public function cur_address_all()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_address_all);
    }

    /**
     * エンティティ化された現住所エレベーター有無を返します。
     * @return string エンティティ化された現住所エレベーター有無
     */
    public function cur_elevator()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_elevator);
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
     * エンティティ化された現住所住居前道幅を返します。
     * @return string エンティティ化された現住所住居前道幅
     */
    public function cur_road()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_road);
    }

    /**
     * エンティティ化された新住所郵便番号を返します。
     * @return string エンティティ化された新住所郵便番号
     */
    public function new_zip()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_new_zip);
    }

    /**
     * エンティティ化された新住所都道府県住所を返します。
     * @return string エンティティ化された新住所都道府県住所
     */
    public function new_address_all()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_new_address_all);
    }

    /**
     * エンティティ化された新住所エレベーター有無を返します。
     * @return string エンティティ化された新住所エレベーター有無
     */
    public function new_elevator()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_new_elevator);
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
     * エンティティ化された新住所住居前道幅を返します。
     * @return string エンティティ化された新住所住居前道幅
     */
    public function new_road()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_new_road);
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
     * エンティティ化された備考を返します（改行文字の前にBRタグが挿入されます）。
     * @return string エンティティ化された備考
     */
    public function comment()
    {
        return Sgmov_Component_String::nl2br(Sgmov_Component_String::htmlspecialchars($this->raw_comment));
    }

}
?>
